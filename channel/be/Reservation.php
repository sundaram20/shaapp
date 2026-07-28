<?php
include_once("../../config/appConfig.php");
//include_once("../../functions/inventoryUpdateFunctionsNewBE.php");
//$postData = file_get_contents('BookingCRSToPMS.json');
/////////////////////////////////////////////////////////


if ($postData) {


$headers = getallheaders();

    	$data = array_combine(
            array_map('trim', array_keys($reservationJson)),
            array_values($reservationJson)
        );

     $mappingHotelCode = $data['hotel']['code'] ?? '';

     $api_key_header = isset($headers['Authorization']) ? $headers['Authorization'] : (isset($_SERVER['HTTP_X_AUTHORIZATION']) ? $_SERVER['HTTP_X_AUTHORIZATION'] : '');
	
     $conn_Sql = "select * from app_be_shop_mapping Where id_hotel_mapping='".$mappingHotelCode."' and token='".$api_key_header."'";
     $query =	mysqli_query($appConnect , $conn_Sql);
     $appNumberOfRows=	mysqli_num_rows($query);

    if ($appNumberOfRows == 0) {
         http_response_code(401);
         echo json_encode(["error" => "Unauthorized - Invalid API Key-Connection Failed"]);
         exit;

        header('Content-Type: application/json');
        echo json_encode([
            "Status" => "Fail",
            "Errors" => [
                "Code"      => "497",
                "ShortText" => "Authorization Required"
            ]
        ]);
        exit;

    }else{

        $row = mysqli_fetch_object($query);
        $id_app_shops = $row->id_app_shops;
        include_once("../../config/api_auto_loader.php");
        include_once("../guestDocConfig.php");
        $id_shop    = 2;
        $channelId  = '3';

        //API CODE START====================================================================================

        $id            = $data['reservationId'] ?? '';
        $ReferenceID   = $data['reservationId'] ?? '';
        $hotel_name    = $data['hotel']['name'] ?? ''; 
        $mappingHotelCode = $data['hotel']['code'] ?? '';

        $sql    = "SELECT * FROM fs_hotel_mapping WHERE booking_engine_id ='" . $mappingHotelCode . "' and channel_id='" . $channelId . "'";
        $result = mysqli_query($connNew, $sql); 

        if (mysqli_num_rows($result) == 0) {
            executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='" . $ReferenceID . "',response_status='Hotel Code not exist',id_pms_response='" . $id . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='Commit'");

            $out = [
                'EchoToken'      => $id,
                'CrsResID_Value' => $id,
                'status'         => 'Hotel Code not exist ' . $mappingHotelCode,
            ];
            echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            exit;

        } else {
            $row      = mysqli_fetch_assoc($result);
            $hotel_id = $row["hotel_id"];
            $hotel_mapping_id = $row["id"];
        }

        $BookingResStatus = $data['status'] ?? '';      // Commit | Cancel | Modify
        $otherRefrenceId  = $id;
        $date_created     = isset($data['createdAt']) ? date('Y-m-d H:i:s', strtotime($data['createdAt'])) : date('Y-m-d H:i:s');
        $BookingDate      = $date_created;

        //CANCEL BOOKING===========================================
        if ($BookingResStatus == 'Cancel') {

            $sql    = "SELECT id,booking_no,checkin,checkout FROM fo_reservations WHERE other_reference='" . $otherRefrenceId . "'";
            $result = mysqli_query($connNew, $sql);
            $rowCnt = mysqli_num_rows($result);

            if ($rowCnt > 0) {
                $res      = mysqli_fetch_assoc($result);
                $id_order = $res['id'];
            } else {
                $id_order = 0;
            }

            if ($id_order > 0 && $id_order != '') {

                $updateSql = "UPDATE " . FO_RESERVATIONS . " SET booking_status='4',last_modified='" . date('Y-m-d H:i:s') . "'  WHERE id='" . $id_order . "' AND other_reference='" . $otherRefrenceId . "' ";

                if (mysqli_query($connNew, $updateSql)) {

                    $updateDetailsSql = "UPDATE fo_reservations_details SET id_mst_room_no_allocation='0'  WHERE id_fo_reservations='" . $id_order . "'";
                    mysqli_query($connNew, $updateDetailsSql);

                    $checkinInv  = date('Y-m-d', strtotime($res['checkin']));
                    $checkoutInv = date('Y-m-d', strtotime($res['checkout']));
                    
                    //updateOTA_JSON($hotel_id, $checkinInv, $checkoutInv);

                    $out = [
                        'EchoToken'      => $ReferenceID,
                        'CrsResID_Value' => $otherRefrenceId,
                        'PmsResID_Value' => $id_order,
                        'PmsBooking_no'  => $res['booking_no'],
                        'status'         => 'cancel success',
                    ];
                    echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                    executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='0',response_status='cancel success',id_pms_response='" . $otherRefrenceId . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='cancel'");

                } else {
                    $out = [
                        'EchoToken'      => $ReferenceID,
                        'CrsResID_Value' => $otherRefrenceId,
                        'status'         => 'cancel Failed',
                    ];
                    echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                    executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='0',response_status='cancel failed',id_pms_response='" . $otherRefrenceId . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='cancel'");
                }

            } else {
                $out = [
                    'EchoToken'      => $ReferenceID,
                    'CrsResID_Value' => $otherRefrenceId,
                    'status'         => 'cancel Request, but reservation does not exist',
                ];
                echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

                executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='0',response_status='cancel Request, but reservation does not exist',id_pms_response='" . $otherRefrenceId . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='cancel'");
            }

            exit;
        }
        //CANCEL BOOKING===========================================

        if($BookingResStatus=='Commit'){
            $booking_status = '1';
        }else if($BookingResStatus=='Modify'){
             $booking_status = '1';
        }else if($BookingResStatus=='Cancel'){
            $booking_status = '4';
        }else{
            $booking_status = '2';
        }

        //MODIFY BOOKING===========================================
       
        $existsQuery = mysqli_query($connNew, "select * From " . FO_RESERVATIONS . " Where other_reference='" . $otherRefrenceId . "'");
        if (mysqli_num_rows($existsQuery) > 0) {      
            //include_once("apiRequestCrsToPmsModifyJson.php");

            executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='0',response_status='success',id_pms_response='" . $otherRefrenceId . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='Modify Req, Please verify'");
            exit;
        }

        //MODIFY BOOKING===========================================
        //-------------------- GUEST --------------------
        $NamePrefix = $data['guest']['name']['title']     ?? '';
        $first_name = $data['guest']['name']['firstName'] ?? '';
        $last_name  = $data['guest']['name']['lastName']  ?? '';
        $email      = $data['guest']['contact']['email']  ?? '';
        $telephone  = $data['guest']['contact']['phone']  ?? '';

        $guest_reg_date = date('Y-m-d');
        $primary_mobile = $telephone;

        $GuestTitle = 0;
        $sqlGuestTitle    = "SELECT id FROM " . TBL_ATTRIBUTES . " WHERE field_value='" . $NamePrefix . "' and table_name ='title' AND status='1'";
        $resultGuestTitle = mysqli_query($connNew, $sqlGuestTitle);
        if (mysqli_num_rows($resultGuestTitle) > 0) {
            $resGuestTitle = mysqli_fetch_assoc($resultGuestTitle);
            $GuestTitle    = $resGuestTitle['id'];
        }

        $guest_reg_no = addslashes($guestResultConfig['prefix']) . addslashes($guestResultConfig['doc_no']) . addslashes($guestResultConfig['suffix']);

        if ($BookingResStatus != 'Modify') {
            $sqlGuest = "INSERT INTO mst_guest (guest_reg_date,id_shop,first_name,last_name,email,primary_mobile,date_created,doc_type,doc_no,id_mst_doc_type_configuration,guest_reg_no,status,id_mst_attributes_title) VALUES ('" . $guest_reg_date . "','" . $id_shop . "','" . addslashes($first_name) . "','" . addslashes($last_name) . "','" . addslashes($email) . "','" . addslashes($primary_mobile) . "','" . $date_created . "','501','" . $guestResultConfig['doc_no'] . "','" . $guestResultConfig['id_mst_doc_type_configuration'] . "','" . $guest_reg_no . "','1','" . $GuestTitle . "')";
            if (mysqli_query($connNew, $sqlGuest)) {
                $guest_id = mysqli_insert_id($connNew);
            }
        }

        //-------------------- HOTEL CODE (internal) --------------------
        $sql    = "SELECT * FROM mst_hotels WHERE name='" . addslashes($hotel_name) . "'";
        $result = mysqli_query($connNew, $sql);
        $hotel_code = '';
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $hotel_code = $row["hotel_code"];
            }
        }

        $id_doc_type    = '801'; //DOCUMENT TYPE FOLIO 803
        $doc_table_name = FO_RESERVATIONS;
        $date           = date('Y-m-d');
        $id_subsection  = '1';
        include_once("../../frontoffice/functions/function.php");
        $docConfig = docTypeConfig($id_doc_type, $date, $id_subsection, $doc_table_name, $connNew, $id_shop);

        //-------------------- BOOKING SOURCE / COMPANY --------------------
        //$CompanyName      = $data['bookingSource']['name']    ?? '';
        $CompanyName      = 'Booking Engine';
        $CompanyGroupName = $data['bookingSource']['channel'] ?? '';

    /*=================================
        $id_mst_attributes_company_group = 0;

        $sqlCompanyGroup    = "SELECT id FROM " . TBL_ATTRIBUTES . " WHERE field_value='" . addslashes($CompanyGroupName) . "' and status='1' and id_shop='" . addslashes($id_shop) . "' AND table_name='company_group'";
        $resultCompanyGroup = mysqli_query($connNew, $sqlCompanyGroup);
        if (mysqli_num_rows($resultCompanyGroup) > 0) {
            $resCompanyGroup = mysqli_fetch_assoc($resultCompanyGroup);
            $id_mst_attributes_company_group = $resCompanyGroup['id'];
        } elseif ($BookingResStatus != 'Modify') {
            $sqlGroup = "INSERT INTO " . TBL_ATTRIBUTES . " (field_value,status,id_shop,table_name) VALUES ('" . addslashes($CompanyGroupName) . "','1','" . $id_shop . "','company_group')";
            if (mysqli_query($connNew, $sqlGroup)) {
                $id_mst_attributes_company_group = mysqli_insert_id($connNew);
            }
        }
    =================================*/

        $sql    = "SELECT id FROM mst_company WHERE name='" . addslashes($CompanyName) . "'";
        $result = mysqli_query($connNew, $sql);
        if (mysqli_num_rows($result) > 0) {
            $res        = mysqli_fetch_assoc($result);
            $company_id = $res['id'];
            } else {
            $sql = "INSERT INTO mst_company (name,status,id_mst_attributes_company_group,id_shop,id_company_crs) VALUES ('" . addslashes($CompanyName) . "','1','" . $id_mst_attributes_company_group . "','" . $id_shop . "','" . addslashes($CrsCompanyId) . "')";
            if (mysqli_query($connNew, $sql)) {
                $company_id = mysqli_insert_id($connNew);
            }
        }
        $id_mst_company = $company_id;


        //-------------------- STAY / ROOMS --------------------
        $other_reference = $otherRefrenceId;
        $checkin  = $data['stay']['checkIn']  ?? '';
        $checkout = $data['stay']['checkOut'] ?? '';
        
        if (empty($checkin) || empty($checkout) || strtotime($checkin) === false || strtotime($checkout) === false || strtotime($checkout) <= strtotime($checkin)) {

        executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='" . $ReferenceID . "',response_status='Invalid checkin/checkout date',id_pms_response='" . $id . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='Commit'");

        $out = [
            'EchoToken'      => $id,
            'CrsResID_Value' => $id,
            'status'         => 'Invalid checkin/checkout date',
        ];
        echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
        $no_of_days = max(1, abs((strtotime($checkin) - strtotime($checkout)) / 86400));

        $rooms = $data['rooms'] ?? [];
        $total_rooms  = count($rooms);
        $total_adults = 0;
        $subtotal     = 0;
        $total_tax    = 0;

        foreach ($rooms as $r) {
            $total_adults += (int)($r['adults'] ?? 0);
            $subtotal     += (float)($r['rate']['baseAmount'] ?? 0);
            $total_tax    += (float)($r['rate']['taxAmount'] ?? 0);
        }
        $net_booking_amount = $subtotal + $total_tax;

        // ASSUMPTION: JSON sample has no "amount received" field for Commit
        // bookings (unlike the XML AmountReceived attribute). Defaulting to 0;
        // wire this up if your CRS adds a paid/received amount.
        
        //$amount_received = $data['amountReceived'] ?? 0;

        $balance = round($net_booking_amount - $amount_received);

        $id_mst_guest    = $guest_id ?? 0;
        $last_modified_by = 9;

        $res_bookingthrough = 0;
        $res_segment = 0;
        $id_mst_attributes_amendment = 0;
        $res_bookingsourcee = 0;
        $pay_sts = 251;
        $internal_remarks = '';
        $specialInstructions = '';
        // Use per-room remarks/specialRequest if you want something populated:
        if (!empty($rooms[0]['remarks'])) {
            $internal_remarks = $rooms[0]['remarks'];
        }
        if (!empty($rooms[0]['specialRequest'])) {
            $specialInstructions = $rooms[0]['specialRequest'];
        }

         $sql = "INSERT INTO " . FO_RESERVATIONS . " SET
            `id_mst_shops`='2',
            `id_shop_group`='1',
            `id_mst_country_lang`='0',
            `id_cart`='0',
            `id_mst_currency_base`='0',
            `id_mst_currency_transaction`='0',
            `conversion_rate`='1',
            `sub_total`='" . $subtotal . "',
            `net_booking_amount`='" . $net_booking_amount . "',
            `other_reference`='" . addslashes($other_reference) . "',
            `reference`='" . addslashes($ReferenceID) . "',
            `id_mst_attributes_amendment`='" . $id_mst_attributes_amendment . "',
            `no_of_days`='" . $no_of_days . "',
            `booking_no`='" . addslashes($docConfig['prefix']) . addslashes($docConfig['po_no']) . addslashes($docConfig['suffix']) . "',
            `id_doc_type_configuration`='" . addslashes($docConfig['id_doc_type_configuration']) . "',
            `doc_no`='" . addslashes($docConfig['po_no']) . "',
            `doc_date`='" . date('Y-m-d', strtotime($date)) . "',
            `mdoc_no`='" . addslashes($docConfig['prefix']) . addslashes($docConfig['po_no']) . addslashes($docConfig['suffix']) . "',
            `doc_type`='" . addslashes($id_doc_type) . "',
            `id_mst_hotels`='" . $hotel_id . "',
            `id_mst_guest`='" . $id_mst_guest . "',
            `id_mst_attributes_company_group`='" . $id_mst_attributes_company_group . "',
            `id_mst_company`='" . $id_mst_company . "',
            `id_mst_company_contacts`='" . $id_company_contacts . "',
            `id_mst_attributes_payment_status`='" . $pay_sts . "',
            `booking_status`='" . $booking_status . "',
            `total_tax`='" . $total_tax . "',
            `amount_received`='" . $amount_received . "',
            `balance`='" . $balance . "',
            `booking_date`='" . date('Y-m-d', strtotime($date_created)) . "',
            `checkin`='" . $checkin . "',
            `checkout`='" . $checkout . "',
            `res_special_notes`='" . addslashes($specialInstructions) . "',
            `res_internal_remarks`='" . addslashes($internal_remarks) . "',
            `id_mst_attributes_segments`='" . $res_segment . "',
            `id_mst_attributes_booking_source`='" . $res_bookingsourcee . "',
            `id_mst_attributes_booking_through`='" . $res_bookingthrough . "',
            `total_adults`='" . $total_adults . "',
            `date_created` = '" . currenDateTime() . "',
            `created_by` = '" . $last_modified_by . "',
            `last_modified` = '" . currenDateTime() . "',
            `last_modified_by` = '" . $last_modified_by . "' ";

        $rese_id = 0;
        $Error   = '';
        if (mysqli_query($connNew, $sql)) {
            $rese_id = mysqli_insert_id($connNew);
        } else {
            $Error .= mysqli_error($connNew);
        }

        $id_fo_reservations = $rese_id;
        $id_mst_hotels       = $hotel_id;
        $unique_code         = 'json_' . rand();

        //-------------------- PER ROOM / PER NIGHT DETAILS + INVENTORY --------------------
        foreach ($rooms as $roomInc => $room) {

            $roomCodeRoom = $room['roomTypeCode'] ?? '';
            $RatePlanMappingCode = $room['ratePlanCode'] ?? '';
            $adults_per_room = $room['adults'] ?? 0;

            $tariff = (float)($room['rate']['baseAmount'] ?? 0);

            $queryRoomId = mysqli_query($connNew, "SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='" . ($hotel_mapping_id) . "' and booking_engine_id='" . addslashes($roomCodeRoom) . "'");
            if (mysqli_num_rows($queryRoomId) > 0) {
                $resRoomId       = mysqli_fetch_object($queryRoomId);
                $id_mst_room_types = $resRoomId->room_id;
            } else {
                $Error .= 'Room Type - ' . $roomCodeRoom . ' Not Found<br>';
                continue;
            }

            $SQLRateId = mysqli_query($connNew, "SELECT * FROM fs_rate_mapping WHERE booking_engine_id = '" . addslashes($RatePlanMappingCode) . "' AND channel_id = '" . addslashes($channelId) . "'");
            if (mysqli_num_rows($SQLRateId) > 0) {
                $FetchArrayRateId = mysqli_fetch_object($SQLRateId);
                $Room_rate_plan_id = $FetchArrayRateId->rate_id;
            } else {
                $Error .= 'Rate mapping - ' . $RatePlanMappingCode . ' Not Found<br>';
                $Room_rate_plan_id = 0;
            }

            //$tax_detail = selectColumn(TBL_RATE_PLAN, 'tax_detail', " WHERE `id` = '" . $Room_rate_plan_id . "'");

            $nights = max(1, (int)$no_of_days);
            $tariff_per_night = round($tariff, 2);

            $order_by_room = 1;
            $dated = $checkin;

            while (strtotime($dated) != strtotime($checkout)) {

                
                //if ($tax_detail == '1') { //inclusive
                    //$resNewTaxInclution = mysqli_query($connNew, "SELECT * FROM `" . TBL_TAX_RULE . "` WHERE id_shop='" . addslashes($id_shop) . "' AND ((tax_inc_slabs_from <= '$price' AND tax_inc_slabs_to >= '$price') OR (tax_inc_slabs_from BETWEEN '$price' AND '$price') OR (tax_inc_slabs_to BETWEEN '$price' AND '$price')) ORDER BY start_date DESC LIMIT 1");
                //} else { //exclusive
                    $resNewTaxInclution = mysqli_query($connNew, "SELECT * FROM `" . TBL_TAX_RULE . "` WHERE id_shop='" . addslashes($id_shop) . "' AND ((tax_slabs_from <= '$price' AND tax_slabs_to >= '$price') OR (tax_slabs_from BETWEEN '$price' AND '$price') OR (tax_slabs_to BETWEEN '$price' AND '$price')) ORDER BY start_date DESC LIMIT 1");
                //}

                $tax_percent = 0;
                $tax_rule_id = 0;
                if (mysqli_num_rows($resNewTaxInclution) > 0) {
                    $rowTax      = mysqli_fetch_object($resNewTaxInclution);
                    $tax_percent = $rowTax->tax_percent;
                    $tax_rule_id = $rowTax->id;

                    //$tax_multiplier = ($tax_percent / 100);
                    $tax_amount = round(($price * $tax_percent)/100, 2, PHP_ROUND_HALF_UP);
                    //echo 'tax amount---'.$tax_amount = round($price - $tariff_excl_tax, 2);
                }else{
                    $tariff_excl_tax = $price;
                    $tax_amount = 0;
                }

                 $Insert_into_Order_Details = "INSERT INTO fo_reservations_details (id_fo_reservations,id_mst_hotels,id_mst_guest,id_mst_room_types,plan,id_rate,id_fo_rate_plan,dated,room_quantity,adults_per_room,tariff_price_per_day_per_room,tax_per_day_per_room,unique_code,checkin_status,id_shop,order_by_room,tax_percent,id_tax_configuration)
                    Values('$id_fo_reservations','$id_mst_hotels','$id_mst_guest',$id_mst_room_types,'$Room_rate_plan_id','0','$Room_rate_plan_id','" . addslashes(date('Y-m-d', strtotime($dated))) . "','1','$adults_per_room','$tariff_per_night','$tax_amount','$unique_code','0','" . addslashes($id_shop) . "','$order_by_room','$tax_percent','$tax_rule_id')";
                executeSql($Insert_into_Order_Details);
                $order_by_room++;

                //FO_INVENTORY
                $sqla   = "SELECT * FROM " . FO_INVENTORY . " WHERE id_mst_room_types='" . $id_mst_room_types . "' and allocation_date='" . date('Y-m-d', strtotime($dated)) . "' and id_mst_hotels = '" . $id_mst_hotels . "' ";
                $resnew = mysqli_query($connNew, $sqla);
                while ($rownew = mysqli_fetch_object($resnew)) {
                    $crs_available = $rownew->crs_available - 1;
                    $confirmed     = $rownew->confirmed + 1;
                    $insertGrid = "UPDATE " . FO_INVENTORY . " SET `crs_available`='" . $crs_available . "',`confirmed`='" . $confirmed . "' WHERE id_mst_room_types='" . $id_mst_room_types . "' and allocation_date='" . date('Y-m-d', strtotime($dated)) . "' and id_mst_hotels = '" . $id_mst_hotels . "'";
                    mysqli_query($connNew, $insertGrid);
                }

                $dated = date('Y-m-d', strtotime('+1 day', strtotime($dated)));
            }
        }

        //-------------------- ROLL UP TOTALS --------------------
        $SqlCheckOrderDetails = mysqli_query($connNew, "SELECT sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room,sum(tax_per_day_per_room) as tax_per_day_per_room FROM fo_reservations_details WHERE id_fo_reservations='" . $id_fo_reservations . "' ");
        $resRoomOrderDetails  = mysqli_fetch_object($SqlCheckOrderDetails);
        $balance   = $resRoomOrderDetails->tariff_price_per_day_per_room;
        $total_tax = $resRoomOrderDetails->tax_per_day_per_room;

        executeSql("UPDATE `" . FO_RESERVATIONS . "` SET
            `balance`='" . ($balance + $total_tax) . "',
            `total_tax`='" . $total_tax . "',
            `net_booking_amount`='" . ($balance + $total_tax) . "'
            where `id`='" . $id_fo_reservations . "'");

            //updateOTA_JSON($hotel_id, $checkinInv, $checkoutInv);

        $Booking_no = addslashes($docConfig['prefix']) . addslashes($docConfig['po_no']) . addslashes($docConfig['suffix']);
        $out = [
            'EchoToken'      => $ReferenceID,
            'CrsResID_Value' => $otherRefrenceId,
            'PmsResID_Value' => $rese_id,
            'PmsBooking_no'  => $Booking_no,
            'Error'          => $Error,
            'status'         => $rese_id ? 'success' : 'Booking Failed',
        ];
        echo json_encode($out, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

        executeSql("Insert into api_request set channel_id = '" . $channelId . "' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . addslashes($CompanyName) . "',booking_referance_id='0',response_status='" . ($rese_id ? 'success' : 'Booking failed') . "',id_pms_response='" . $otherRefrenceId . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='commit'");

        exit;
        //API CODE END======================================================================================
    }
} else {
    // Bad / empty / non-JSON payload
    http_response_code(400);
    echo json_encode(['status' => 'Invalid or empty JSON payload'], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

?>