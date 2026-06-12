<?php

$reservation = $xmlarray['HotelReservations']['HotelReservation'];

$ReferenceID = $xmlarray['@attributes']['EchoToken'] ?? '';
$ResStatus   = $xmlarray['@attributes']['ResStatus'] ?? '';

$other_reference = $reservation['UniqueID']['@attributes']['ID'];

$roomStay = $reservation['RoomStays']['RoomStay'];
/* ================= NORMALIZE ROOMSTAYS ================= */
$roomStays = $reservation['RoomStays']['RoomStay'];
if (!isset($roomStays[0])) {
    $roomStays = [$roomStays];
}

/* ================= NORMALIZE GUESTS ================= */
$resGuests = $reservation['ResGuests']['ResGuest'];
if (!isset($resGuests[0])) {
    $resGuests = [$resGuests];
}
//print_r($roomStays);die;


// $hotelCode = $roomStays[0]['BasicPropertyInfo']['@attributes']['HotelCode'];
//$hotelCode   = $xmlarray['POS']['Source']['Authentication']['hotelcode'];
$hotel_name = $roomStays[0]['BasicPropertyInfo']['@attributes']['HotelName'];

$checkin  = $roomStays[0]['TimeSpan']['@attributes']['Start'];
$checkout = $roomStays[0]['TimeSpan']['@attributes']['End'];


$payStatus = trim($reservation['ResGlobalInfo']['Total']['PayStatus'] ?? '');
$payStatusLower = strtolower($payStatus);

// Step 1: Check if exists
$sqlgroup_sub = "SELECT * FROM `mst_attributes` 
    WHERE status='1' 
    AND table_name='payment_status' 
    AND LOWER(field_value) = '".$payStatusLower."'";

$resTogroup_sub = mysqli_query($connNew, $sqlgroup_sub);
$rowgroup_sub = mysqli_fetch_object($resTogroup_sub);

if ($rowgroup_sub) {

    // ✅ Found
    $id_payStatus = $rowgroup_sub->id;

} else {

    // ❌ Not found → Insert dynamically
    $insertPayStatus = trim($reservation['ResGlobalInfo']['Total']['PayStatus'] ?? '');
    $sqlInsert = "INSERT INTO `mst_attributes` 
    (`table_name`, `field_name`, `field_value`, `field_category`, `field_description`, 
    `printer_port`, `image`, `id_table_group`, `ids_mst_user`, `id_mst_room_no`, 
    `id_country`, `id_shop`, `status`, `display_order`, `date_created`, `last_modified`, 
    `id_mst_user_created_by`, `id_mst_user_modified_by`) 
    VALUES (
        'payment_status',
        'payment_status_name',
        '".mysqli_real_escape_string($connNew, $insertPayStatus)."',
        '".mysqli_real_escape_string($connNew, $insertPayStatus)."',
        'Auto Inserted',
        '0',
        '',
        '0',
        '',
        NULL,
        '0',
        '".$id_shop."',
        '1',
        '0',
        NOW(),
        NOW(),
        '".$_SESSION['id_user']."',
        '".$_SESSION['id_user']."'
    )";

    mysqli_query($connNew, $sqlInsert);

    // Get inserted ID
    $id_payStatus = mysqli_insert_id($connNew);
}


/* =========================================================
   API LOG - UPDATE BASIC DETAILS
========================================================= */

mysqli_query($connNew, "
    UPDATE api_request SET
        company_name = '".mysqli_real_escape_string($connNew, $CompanyName)."',
        booking_referance_id = '".mysqli_real_escape_string($connNew, $other_reference)."',
        id_hotel = '".intval($hotel_id)."',
        echotoken_id = '".mysqli_real_escape_string($connNew, $ReferenceID)."',
        booking_type = '".mysqli_real_escape_string($connNew, $ResStatus)."'
    WHERE id = '".intval($log_id)."'
");
/* =========================================================
   3. HOTEL MAPPING
========================================================= */

$resChannel = mysqli_fetch_object(
    executeSql("SELECT * FROM fs_hotel_mapping 
                WHERE booking_engine_id='$hotelCode' 
                AND channel_id='$channelId'")
);

if (!$resChannel) {
    sendResponse("Hotel Code not mapped");
}

$hotel_id = $resChannel->hotel_id;

/* =========================================================
   4. CANCEL
========================================================= */
if ($xmlarray['@attributes']['CancelType'] == 'Cancel') {
	
	$otherRefrenceId = @$xmlarray['UniqueID']['@attributes']['ID'];
	
    $res = mysqli_query($connNew,
        "SELECT id,checkin,checkout,id_mst_hotels FROM fo_reservations WHERE other_reference='$otherRefrenceId'"
    );

    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $id  = $row['id'];

        mysqli_query($connNew,
            "UPDATE fo_reservations SET booking_status='4' WHERE id='$id'"
        );
		/* ===== AUTO INVENTORY UPDATE ON CANCEL ===== */
        if (empty($Error)) {

            $check_in  = date('Y-m-d', strtotime($row['checkin']));
            $check_out = date('Y-m-d', strtotime($row['checkout']));
            $apiHotelID = $row['id_mst_hotels'];

            $sqlMappingInventory = "
                SELECT auto_sync_inv 
                FROM ".TBL_CHANNEL_MANAGER." AS A 
                INNER JOIN ".TBL_HOTEL_MAPPING." AS B 
                    ON A.id = B.channel_id
                WHERE B.hotel_id = '".addslashes($apiHotelID)."' 
                AND B.status = 1 
                AND A.channel_type = 1
            ";

            $QueryMapping  = mysqli_query($connNew, $sqlMappingInventory);
            $resultMapping = mysqli_fetch_object($QueryMapping);

            $autoInventoryUpdate = $resultMapping->auto_sync_inv ?? 0;

           // if ($autoInventoryUpdate == 1) {
                updateOTA($apiHotelID, $check_in, $check_out,$connNew);
            //}
        }
        sendResponse('cancel success', $ReferenceID, $otherRefrenceId, $id);
    } else {
        sendResponse('cancel not found', $ReferenceID);
    }
}
/* =========================================================
   5. $CompanyName
========================================================= */

$CompanyName = $xmlarray['POS']['Source']['BookingChannel']['CompanyName'];
//$CompanyName = mysqli_real_escape_string($connNew, trim($CompanyName));

 $sql = "SELECT id FROM mst_company WHERE LOWER(name)=LOWER('$CompanyName')";
$result = mysqli_query($connNew, $sql);

if(mysqli_num_rows($result) > 0){

    $res = mysqli_fetch_assoc($result);
    $company_id = $res['id'];

    mysqli_query($connNew, "UPDATE mst_company 
        SET id_company_crs='".mysqli_real_escape_string($connNew, $CrsCompanyId)."' 
        WHERE id='".$company_id."'");

}else{

    mysqli_query($connNew, "INSERT INTO mst_company 
        (name,status,id_mst_attributes_company_group,id_shop,id_company_crs) 
        VALUES 
        ('$CompanyName','1','$id_mst_attributes_company_group','$id_shop','$CrsCompanyId')");

    $company_id = mysqli_insert_id($connNew);
}

$id_mst_company = $company_id;

/* =========================================================
   5. GUEST
========================================================= */
$guestData = $resGuests[0]['Profiles']['ProfileInfo']['Profile']['Customer'];



$guest_reg_no	=addslashes($guestResultConfig['prefix']).addslashes($guestResultConfig['doc_no']).addslashes($guestResultConfig['suffix']);
	
 //$guest = $reservation['ResGuests']['ResGuest']['Profiles']['ProfileInfo']['Profile']['Customer'];

//$first_name = $guestData['PersonName']['GivenName'] ?? '';
//$last_name  = $guestData['PersonName']['Surname'] ?? '';
//$email      = $guestData['Email'] ?? '';
$mobile     = $guestData['Telephone']['@attributes']['PhoneNumber'] ?? '';
$email = '';

if (isset($guestData['Email'])) {
    if (is_array($guestData['Email'])) {
        // Empty tag case → keep blank
        $email = '';
    } else {
        $email = trim($guestData['Email']);
    }
}
$last_name = trim(
    is_array($guestData['PersonName']['Surname'] ?? '') 
        ? ($guestData['PersonName']['Surname'][0] ?? '') 
        : ($guestData['PersonName']['Surname'] ?? '')
);

$first_name = trim(
    is_array($guestData['PersonName']['GivenName'] ?? '') 
        ? ($guestData['PersonName']['GivenName'][0] ?? '') 
        : ($guestData['PersonName']['GivenName'] ?? '')
);

 $guestSql = "INSERT INTO mst_guest 
(first_name,last_name,email,primary_mobile,id_shop,date_created,doc_type,doc_no,id_mst_doc_type_configuration,guest_reg_no,status,id_mst_attributes_title)
VALUES ('$first_name','$last_name','$email','$mobile','$id_shop',NOW(),'501','".$guestResultConfig['doc_no']."','".$guestResultConfig['id_mst_doc_type_configuration']."','".$guest_reg_no."','1','".$GuestTitle."')";

mysqli_query($connNew, $guestSql);
$guest_id = mysqli_insert_id($connNew);

/* =========================================================
   6. RATE CALCULATION
========================================================= */
foreach ($roomStays as $roomStay) {
	
	$rates = $roomStay['RoomRates']['RoomRate']['Rates']['Rate'];

if (!isset($rates[0])) $rates = [$rates];

$subtotal = 0;
$total_tax = 0;

foreach ($rates as $rate) {
    $base = $rate['Base']['@attributes'];
    $subtotal += $base['AmountBeforeTax'] ?? 0;
    $total_tax += $base['TaxAmt'] ?? 0;
}
	
	
	if (isset($guestCounts[0])) {
        foreach ($guestCounts as $g) {
            if ($g['@attributes']['AgeQualifyingCode'] == '10') {
                $total_adults += (int)$g['@attributes']['Count'];
            }
        }
    } else {
        if ($guestCounts['@attributes']['AgeQualifyingCode'] == '10') {
            $total_adults += (int)$guestCounts['@attributes']['Count'];
        }
    }

	
} 
$net_booking_amount = $subtotal + $total_tax;
//$total_adults = $roomStay['GuestCounts']['GuestCount']['@attributes']['Count'] ?? 1;

/* =========================================================
   7. CHECK EXISTING (MODIFY / INSERT)
========================================================= */
$query = mysqli_query($connNew,
    "SELECT id FROM fo_reservations WHERE other_reference='$other_reference'"
);
//"SELECT id FROM fo_reservations WHERE other_reference='$other_reference'";
if (mysqli_num_rows($query) > 0) {

    // MODIFY
    $row = mysqli_fetch_assoc($query);
    $rese_id = $row['id'];

    mysqli_query($connNew,
        "UPDATE fo_reservations SET
            sub_total='$subtotal',
            total_tax='$total_tax',
            net_booking_amount='$net_booking_amount',
            total_adults='$total_adults',
            checkin='$checkin',
            checkout='$checkout',
			`res_payment_status`='".$id_payStatus."',
            last_modified=NOW()
         WHERE id='$rese_id'"
    );

    // delete old room details
    mysqli_query($connNew,
        "DELETE FROM fo_reservations_details WHERE id_fo_reservations='$rese_id'"
    );

    $statusMsg = 'modify success';

} else {
$id_configuration_type = "27";
$doc_date = date('Y-m-d');
$mdoc_no = $hotel_code.'/'.($max_id+1);
$doc_type = "801";
$id_shop_group = "1";
$id_mst_shop = "2";
$id_mst_country_lang = "0";
$id_mst_hotels = $hotel_id;
	$booking_status="1";	
//$booking_status		=	selectColumn('fo_booking_status','id'," WHERE name='".addslashes($bookingStatus)."'");
$daysNew = (strtotime($checkout) - strtotime($checkin)) / 86400;

$no_of_days = ($daysNew <= 0) ? 1 : $daysNew;

	$id_doc_type_configuration='801';

	include_once("../../frontoffice/functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop=2);
    // INSERT
    $sql = "INSERT INTO fo_reservations SET

	
		
			`id_mst_shops`='2',
			`id_shop_group`='1',
			`id_mst_country_lang`='0',
			`id_cart`='0',
			`id_mst_currency_base`='0',
			`id_mst_currency_transaction`='0',
			`conversion_rate`='1',
			`sub_total`='".$subtotal."',
			`net_booking_amount`='".$net_booking_amount."',
			`booking_confirm_date`='".$confirm."',
			`tentative_hold_date`='".$bk_stsa."',
			`other_reference`='".$other_reference."',
			`reference`='".$other_reference."',
			`id_mst_attributes_cancellation`='".$res_cancellation."',
			`id_mst_attributes_amendment`='".$id_mst_attributes_amendment."',
			`no_of_days`='".$no_of_days."',
			`booking_no`='".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
			`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
			`doc_no`='".addslashes($docConfig['po_no'])."',
			`doc_date`='".date('Y-m-d',strtotime($date))."',
			`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
			`doc_type` = '".addslashes($id_doc_type)."',
			`id_mst_hotels`='".$id_mst_hotels."',
			`id_mst_guest`='".$guest_id."',
			`id_mst_attributes_company_group`='".$id_mst_attributes_company_group."',
			`id_mst_company`='".$id_mst_company."',
			`id_mst_company_contacts`='".$id_company_contacts."',
			`id_mst_attributes_payment_status`='".$pay_sts."',
			`booking_status`='".$booking_status."',
			`room_tariff_price`='".$totalprice."',
			`discount`='".$dis."',
			`total_addon_price`='".$total_addon_amount."',
			`total_tax`='".$total_tax."',
			`amount_received`='".$amount_received."',
			`balance`='".$net_booking_amount."',
			`booking_date`='".date('Y-m-d',$BookingDate)."',
			`checkin`='".$checkin."',
			`checkout`='".$checkout."',
			`arrival_time`='".$res_arrivingtime."',
			`arrival_from`='".$res_arrivingfrom."',
			`departing_to`='".$pickupd."',
			`pickup`='".$res_pickuprequired."',
			`pickup_details`='".$pickup_details."',
			`id_mst_attributes_mode_of_travel`='".$pickupa."',
			`special_requests`='".$spe_rqt."',
			`res_special_notes`='".$specialInstructions."',
			`res_payment_status`='".$id_payStatus."',
			`res_internal_remarks`='".$internal_remarks."',
			`id_mst_attributes_segments`='".$res_segment."',
			`id_mst_attributes_booking_source`='".$res_bookingsourcee."',
			`id_mst_attributes_booking_through`='".$res_bookingthrough."',						
			`food_plan_price`='".$total_food_price."',
			`extra_bed_price`='".$total_extrabed_price."',
			`total_adults`='".$total_adults."',
			`total_child_with_bed`='".$total_extrabed_price."',
			`total_child_without_bed`='".$total_child_without_bed."',
			
			`date_created` = '".date('Y-m-d H:i:s')."',
			`created_by` = '".$last_modified_by."',
			`last_modified` = '".date('Y-m-d H:i:s')."',
			`last_modified_by` = '".$last_modified_by."'";
	//echo $sql;die;
    mysqli_query($connNew, $sql);
    $rese_id = mysqli_insert_id($connNew);

    $statusMsg = 'commit success';
}

/* =========================================================
   8. ROOM INSERT
========================================================= */
$roomTypeCode = $roomStay['RoomTypes']['RoomType']['@attributes']['RoomTypeCode'];

$roomMap = mysqli_fetch_object(
    mysqli_query($connNew,
        "SELECT * FROM fs_room_mapping 
         WHERE booking_engine_id='$roomTypeCode'"
    )
);

$id_mst_room_types = $roomMap->room_id ?? 0;

$start = strtotime($checkin);
$end   = strtotime($checkout);

$roomTypes = $roomStay['RoomTypes']['RoomType'];

if (!isset($roomTypes[0])) {
    $roomTypes = [$roomTypes];
}

$order_by_room = 1;

foreach ($roomStays as $roomStay) {
	
	
	// ================= ROOM TYPE MAPPING =================
$roomTypes = $roomStay['RoomTypes']['RoomType'] ?? [];

// Handle single vs multiple
if (isset($roomTypes['@attributes'])) {
    $roomTypes = [$roomTypes];
}

$id_mst_room_types = 0;

foreach ($roomTypes as $rt) {

    $roomTypeCode = $rt['@attributes']['RoomTypeCode'] ?? '';

    if (!empty($roomTypeCode)) {

        $roomMap = mysqli_fetch_object(
            mysqli_query($connNew,
                "SELECT room_id 
                 FROM fs_room_mapping 
                 WHERE booking_engine_id='".addslashes($roomTypeCode)."'
                 LIMIT 1"
            )
        );

        $id_mst_room_types = $roomMap->room_id ?? 0;
    }
}
	
	$guestCounts = $roomStay['GuestCounts']['GuestCount'] ?? [];

if (isset($guestCounts[0])) {
    // Multiple GuestCount
    foreach ($guestCounts as $gc) {
        if ($gc['@attributes']['AgeQualifyingCode'] == '10') {
            $total_adults += (int)$gc['@attributes']['Count'];
        }
        if ($gc['@attributes']['AgeQualifyingCode'] == '8') {
            $total_children += (int)$gc['@attributes']['Count'];
        }
    }
} else {
    // Single GuestCount
    if (!empty($guestCounts)) {
        if ($guestCounts['@attributes']['AgeQualifyingCode'] == '10') {
            $total_adults = (int)$guestCounts['@attributes']['Count'];
        }
        if ($guestCounts['@attributes']['AgeQualifyingCode'] == '8') {
            $total_children = (int)$guestCounts['@attributes']['Count'];
        }
    }
}

    // ✅ GET RATE PLAN ONCE (NOT INSIDE DATE LOOP)
    if (isset($roomStay['RatePlans']['RatePlan'][0])) {
        $RatePlansMappingID = $roomStay['RatePlans']['RatePlan'][0]['@attributes']['RatePlanCode'];
    } else {
        $RatePlansMappingID = $roomStay['RatePlans']['RatePlan']['@attributes']['RatePlanCode'];
    }

    $Room_rate_plan_id = 0;

    $SQLRateId = mysqli_query($connNew,"
        SELECT rate_id 
        FROM fs_rate_mapping 
        WHERE booking_engine_id ='".mysqli_real_escape_string($connNew, $RatePlansMappingID)."' 
        AND channel_id='$channelId'
        LIMIT 1
    ");

    if(mysqli_num_rows($SQLRateId) > 0){
        $FetchArrayRateId = mysqli_fetch_object($SQLRateId);
        $Room_rate_plan_id = $FetchArrayRateId->rate_id;
    }

    // ✅ GET RATE PLAN DETAILS ONCE
    $tax_detail = 1;
    if($Room_rate_plan_id > 0){
        $resRatePlanDetail = selectSql(
            'fo_rate_plan',
            "WHERE id='".addslashes($Room_rate_plan_id)."'",
            'ORDER BY name'
        );
        $resRateDetail = mysqli_fetch_object($resRatePlanDetail);
        $tax_detail = $resRateDetail->tax_detail ?? 1;
    }

    // ================= RATE ARRAY =================
    $rates = $roomStay['RoomRates']['RoomRate']['Rates']['Rate'];
    if (!isset($rates[0])) $rates = [$rates];

    $rateArr = [];

    foreach ($rates as $rate) {

        $date = $rate['@attributes']['EffectiveDate'];
        $base = $rate['Base']['@attributes'];

        $rateArr[$date] = [
            'BaseAmount'      => (float)($base['BaseAmount'] ?? 0),
            'AmountBeforeTax' => (float)($base['AmountBeforeTax'] ?? 0),
            'tax'             => (float)($base['TaxAmt'] ?? 0)
        ];
    }

    // ================= DATE LOOP =================
    $start = strtotime($roomStay['TimeSpan']['@attributes']['Start']);
    $end   = strtotime($roomStay['TimeSpan']['@attributes']['End']);

    while ($start < $end) {

        $dated = date('Y-m-d', $start);

        // ================= PRICE =================
        $NewBaseAmount = $rateArr[$dated]['BaseAmount'] ?? 0;
        $NewpriceValue = $NewBaseAmount;

        $basePrice = round($NewpriceValue, 2);

        // ================= TAX DATE RULE =================
        $taxDateSql = "
            SELECT id 
            FROM ".TBL_TAX_DATE_RULE." 
            WHERE id_shop='".addslashes($id_shop)."'
            AND start_date <= '".$dated."'
            ORDER BY start_date DESC 
            LIMIT 1
        ";

        $SelectTaxDateSQL = mysqli_query($connNew, $taxDateSql);
        $SelectTaxDateRow = mysqli_fetch_assoc($SelectTaxDateSQL);

        $SlectedDateNewTax_id = $SelectTaxDateRow['id'] ?? 0;

        // ================= TAX SLAB =================
       /* $taxRuleSql = "
            SELECT * 
            FROM ".TBL_TAX_RULE." 
            WHERE id_shop='".addslashes($id_shop)."' 
            AND '".$NewpriceValue."' BETWEEN tax_inc_slabs_from AND tax_inc_slabs_to
            AND tax_uniqueid='".$SlectedDateNewTax_id."'
            LIMIT 1
        ";

        $resNewTaxInclution = mysqli_query($connNew, $taxRuleSql);

        $tax_percent = 0;
        $tax_perday_perroom = 0;
        $base_without_tax = $basePrice;

        if(mysqli_num_rows($resNewTaxInclution) > 0){

            $rowNewTaxInclution = mysqli_fetch_assoc($resNewTaxInclution);
            $tax_percent = (float)$rowNewTaxInclution['tax_percent'];

            // ================= YOUR SPECIAL RULE =================
            if ($NewBaseAmount >= 7876 && $NewBaseAmount <= 8851) {
                $tax_percent = 0;
                $tax_perday_perroom = 0;
                $base_without_tax = $basePrice;
            } else {

                $tax_perday_perroom = round(($basePrice * $tax_percent) / 100, 2);

                if($tax_detail == 2){
                    // TAX INCLUDED
                    $base_without_tax = $basePrice - $tax_perday_perroom;
                } else {
                    // TAX EXTRA
                    $base_without_tax = $basePrice;
                }
            }

        }*/
		// ================= TAX SLAB =================
$taxRuleSql = "
    SELECT * 
    FROM ".TBL_TAX_RULE." 
    WHERE id_shop='".addslashes($id_shop)."' 
    AND '".$NewpriceValue."' BETWEEN tax_inc_slabs_from AND tax_inc_slabs_to
    AND tax_uniqueid='".$SlectedDateNewTax_id."'
    LIMIT 1
";

$resNewTaxInclution = mysqli_query($connNew, $taxRuleSql);

$tax_percent = 0;
$tax_perday_perroom = 0;
$base_without_tax = $basePrice;

if(mysqli_num_rows($resNewTaxInclution) > 0){

    $rowNewTaxInclution = mysqli_fetch_assoc($resNewTaxInclution);
    $tax_percent = (float)$rowNewTaxInclution['tax_percent'];

    // ================= SPECIAL RULE =================
    if ($NewBaseAmount >= 7876 && $NewBaseAmount <= 8851) {

        $tax_percent = 0;
        $tax_perday_perroom = 0;
        $base_without_tax = $basePrice;

    } else {

        if($tax_detail == 2){
            // ✅ TAX INCLUDED (REVERSE CALCULATION)

            $tax_perday_perroom = round(
                ($basePrice * $tax_percent) / (100 + $tax_percent),
                2
            );

            $base_without_tax = round(
                $basePrice - $tax_perday_perroom,
                2
            );

        } else {
            // ✅ TAX EXTRA (NORMAL CALCULATION)

            $tax_perday_perroom = round(
                ($basePrice * $tax_percent) / 100,
                2
            );

            $base_without_tax = $basePrice;
        }
    }

} else {

    // ❌ NO TAX SLAB FOUND
    $tax_percent = 0;
    $tax_perday_perroom = 0;
    $base_without_tax = $basePrice;
}

        // ================= TOTALS =================
        $totalpriceRoom = round($base_without_tax, 2);

        $SubTotalAssignDetail += $totalpriceRoom;
        $SubTotalTax += $tax_perday_perroom;

        // ================= INSERT =================
        mysqli_query($connNew,"
            INSERT INTO fo_reservations_details
            (id_fo_reservations,id_mst_hotels,id_mst_guest,
             id_mst_room_types,dated,room_quantity,
             adults_per_room,tariff_price_per_day_per_room,
             tax_per_day_per_room,order_by_room,id_shop, plan,
             id_rate,id_fo_rate_plan)
            VALUES
            ('$rese_id','$hotel_id','$guest_id',
             '$id_mst_room_types','$dated','1',
             '".intval($total_adults)."','$totalpriceRoom','$tax_perday_perroom',
             '$order_by_room','$id_shop',
             '".intval($Room_rate_plan_id)."',
             '0',
             '".intval($Room_rate_plan_id)."')
        ");

        $start = strtotime('+1 day', $start);
    }

    $order_by_room++;
}

//adv insert

if($payStatus=='Fully Paid' && !empty($rese_id)){
	
	$total_booking_amount = round(
    (float)($reservation['ResGlobalInfo']['Total']['@attributes']['TotalBookingAmount'] ?? 0), 
    2
);
	
	 $sql_rec_check = "SELECT id FROM fo_receipt WHERE id_reservation = '$rese_id' AND amount = '$total_booking_amount' LIMIT 1";
	$check_result = executeSql($sql_rec_check);
	
	if (mysqli_num_rows($check_result) == 0) {
	
	$id_user = $_SESSION['userId'] ?? '9';
	
	$php_date = date("Y-m-d");
    $php_time = date("H:i:s");
    $php_now  = date("Y-m-d H:i:s");
	
	$id_type = '4';
	$mode = 'COMPANY';
	$remark = 'Online Payment';
	
	$sql = "
        INSERT INTO fo_receipt 
        (id_reservation, id_type, payment_mode, amount, is_advance, remark, doc_date, id_company, `time`, ccredit, date_created, id_mst_user_created_by, id_mst_user_modified_by)
        VALUES
        ('$rese_id', '$id_type', '$mode', '$total_booking_amount', '1', '$remark', '$php_date', '$id_mst_company', '$php_time', '$mode', '$php_now', '$id_user', '$id_user')
    ";
	
    $result = executeSql($sql);
   
    if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "SQL Error: " . ($connNew->error ?? '')
    ]);
    exit;
	}
		
  }
	
}

/* =========================================================
   9. FINAL RESPONSE
========================================================= */
//sendResponse($statusMsg, $ReferenceID, $other_reference, $rese_id);


/* =========================================================
   FUNCTION
========================================================= */

	if (empty($Error)) {

    /* ===== AUTO INVENTORY UPDATE ===== */
    $check_in  = date('Y-m-d', strtotime($roomStay['TimeSpan']['@attributes']['Start']));
    $check_out = date('Y-m-d', strtotime($roomStay['TimeSpan']['@attributes']['End']));

    $apiHotelID = $hotel_id;

    $sqlMappingInventory = "
        SELECT auto_sync_inv 
        FROM ".TBL_CHANNEL_MANAGER." AS A 
        INNER JOIN ".TBL_HOTEL_MAPPING." AS B 
            ON A.id = B.channel_id
        WHERE B.hotel_id = '".addslashes($apiHotelID)."' 
        AND B.status = 1 
        AND A.channel_type = 1
    ";

    $QueryMapping  = mysqli_query($connNew, $sqlMappingInventory);
    $resultMapping = mysqli_fetch_object($QueryMapping);

    $autoInventoryUpdate = $resultMapping->auto_sync_inv ?? 0;

   // if ($autoInventoryUpdate == 1) {
        updateOTA(
            $apiHotelID,
            $check_in,
            $check_out,$connNew
        );
    //}

    // ✅ SUCCESS RESPONSE
    sendResponse($statusMsg, $ReferenceID, $other_reference, $rese_id);

} else {

    // ❌ ERROR RESPONSE
    sendResponse("error: ".$Error, $ReferenceID, $other_reference, 0);
}
function sendResponse($status, $echoToken = '', $crs = '', $pms = 0) {
    /*echo json_encode([
        'EchoToken' => $echoToken,
        'CrsResID_Value' => $crs,
        'PmsResID_Value' => $pms,
        'status' => $status
    ]);*/
	
	 global $connNew, $log_id;

    $responseXML =  '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$echoToken.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>'.$status.'</Success>
				</OTA_HotelResNotifRS>';
     /* ===== STATUS TYPE ===== */
    $statusLower = strtolower($status);

    if(strpos($statusLower, 'error') !== false){
        $finalStatus = 'FAILED';
        $failedAt = date('Y-m-d H:i:s');
    } elseif(strpos($statusLower, 'cancel') !== false){
        $finalStatus = 'CANCEL';
        $failedAt = '';
    } elseif(strpos($statusLower, 'modify') !== false){
        $finalStatus = 'MODIFY';
        $failedAt = '';
    } else {
        $finalStatus = 'SUCCESS';
        $failedAt = '';
    }

    /* ===== UPDATE LOG ===== */
    mysqli_query($connNew, "
        UPDATE api_request SET
            response_ack = '".mysqli_real_escape_string($connNew, $responseXML)."',
            response_status = '".$finalStatus."',
            id_order = '".intval($pms)."',
            failed_at = '".$failedAt."'
        WHERE id = '".intval($log_id)."'
    ");

    echo $responseXML;
    exit;
}
?>