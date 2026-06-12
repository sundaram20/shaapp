<?php  include_once("../config/auto_loader.php");
  ?>
<?php // include_once("../includes/header.php")?>
<?php // include_once("../includes/left.php"); ?>
    <style>
        /* Base & Reset */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif; 
        }

        body {
            background-color: #555;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* Strict A4 Setup */
        .page {
            background: #fff;
            width: 210mm;
            min-height: 297mm;  /* was: height: 297mm */
    		overflow: visible;
            /* 8mm is the safe-zone so the printer doesn't cut the outer border */
            padding: 8mm; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.3);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        /* NEW: The Outer Border Wrapper */
        .page-content {
            border: 2px solid #000;
            /* padding: 8px 10px; */
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Stretches to fill the page height */
        }

        .print-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #000;
            color: #fff;
            border: none;
            padding: 12px 24px;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
            border-radius: 4px;
            z-index: 1000;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 6px;
            /* margin-bottom: 6px; */
            padding-left: 6px;
    padding-right: 6px;
        }

        .header img {
            max-height: 55px;
            max-width: 220px;
        }

        .hotel-info {
            text-align: right;
            font-size: 11px;
            color: #222;
            line-height: 1.3;
        }
        .hotel-info strong {
            font-size: 14px;
            color: #000;
            display: block;
            margin-bottom: 3px;
        }

        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            /* margin-bottom: 6px; */
            /* text-decoration: underline; */
            padding: 4px 0px;
        }

        /* Section Styling */
        .section {
            border: 1px solid #000;
            margin-bottom: 6px; 

            border-right: 0;
            border-left: 0;
        }

        .section-header {
            background-color: #eaeaea;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 11px;
            text-transform: uppercase;
            border-bottom: 1px solid #000;
        }

        /* Grid Layouts for Data */
        .grid-row {
            display: flex;
            border-bottom: 1px solid #000;
        }
        .grid-row:last-child { border-bottom: none; }

        .grid-col {
            flex: 1;
            padding: 3.5px 10px;
            border-right: 1px solid #000;
        }
        .grid-col:last-child { border-right: none; }

        /* Form Fields */
        .field {
            display: flex;
            flex-direction: column;
            margin-bottom: 4px;
        }
        .field.inline {
            flex-direction: row;
            align-items: center;
        }
        .field label {
            font-size: 10px;
            color: #444;
            text-transform: uppercase;
            font-weight: bold;
        }
        .field.inline label {
            margin-right: 8px;
            /* width: 110px;*/
        }
        .field .value {
            font-size: 11.5px;
            font-weight: bold;
            color: #000;
            min-height: 15px; 
            margin-top: 2px;
        }
        .field.inline .value { margin-top: 0; display :flex ; align-items : center; }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        th {
            background-color: #eaeaea;
            font-size: 11px;
            text-transform: uppercase;
            color: #000;
            font-weight: bold;
        }
        td.value {
            font-weight: bold;
            font-size: 11.5px;
            padding: 5px;
        }

        .section-header tr th{
            padding: 5px!important;
        }

        /* Guest Details Custom Split */
        .details-split {
            display: flex;
        }
        .details-left {
            flex: 1.2; 
            border-right: 1px solid #000;
        }
        .details-right {
            flex: 1;
        }
        .details-padding {
            padding: 8px 10px;
        }
        .foreign-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3px 12px;
        }

        /* Rules Section */
        .rules-box {
            border: 1px solid #000;
            padding: 8px 10px;
            margin-bottom: 6px; 
            border-left: 0!important;
            border-right: 0!important;
        }
        .rules-box h4 {
            text-align: center;
            margin-bottom: 3px;
            font-size: 11px;
            text-transform: uppercase;
        }
        .rules-intro {
            text-align: center; 
            margin-bottom: 6px; 
            font-style: italic;
            font-size: 9px;
        }
        .rules-list {
            column-count: 2; 
            column-gap: 20px;
            font-size: 8.5px;
            line-height: 1.3;
        }
        .rules-list p {
            margin-bottom: 4px;
            break-inside: avoid;
        }
        .declaration {
            font-style: italic;
            font-weight: bold;
            text-align: center;
            margin-top: 6px;
            font-size: 9px;
        }

        /* Signatures */
        .signatures {
            display: flex;
            border: 1px solid #000;
            margin-top: auto; 
        }
        .sig-box {
            flex: 1;
            text-align: center;
            padding: 25px 10px 5px 10px;
            border-right: 1px solid #000;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .sig-box:last-child {
            border-right: none;
        }

        /* Print Media Query */
        @media print {
            @page {
                size: A4;
                margin: 0mm; 
            }
            body { 
                background: none; 
                padding: 0; 
                margin: 0;
            }
            .page { 
                width: 210mm; 
                height: auto;
				min-height: 297mm;
				overflow: visible; 
                margin: 0; 
                padding: 5mm; 
                box-shadow: none; 
                border: none; 
                page-break-after: avoid;
                page-break-inside: avoid;
            }
			.rules-box, .section, .signatures {
        page-break-inside: avoid;
    }
            .print-btn { display: none; }
            .section-header, th { 
                background-color: #eaeaea !important; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
            }
        }

        th, td{
            padding: 4px 6px!important;;
        }
		
		/*===========*/
		
		.value.empty-field {
    border-bottom: 1px solid #000;
    min-width: 140px;
    display: inline-block;
}

@media print {
    .value.empty-field {
        border-bottom: 1px solid #000 !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
		
    </style>


    <button class="print-btn" onclick="window.print()">Print Form</button>

<?php 
 $id_guest=addslashes(encryptor(decrypt,$_REQUEST['gId']));
$id_res = addslashes(encryptor(decrypt,$_REQUEST['resId']));
$id_folio = addslashes(encryptor(decrypt,$_REQUEST['folioId']));
$print_time = date('d-m-Y h:i:s A');

$SQL_Hotel = "select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";	
	$query_Hotel=mysqli_query($connNew, $SQL_Hotel);		
	$row_Hotel=mysqli_fetch_object($query_Hotel);
		
		
		
		//Rajasthan
		$HotelName	   =$row_Hotel->name;
		$HotelState	  =selectColumn(TBL_STATE,'name','WHERE id_state="'.$row_Hotel->id_mst_state.'"');
		$HotelCountry	  =selectColumn('mst_country_lang','name','WHERE id_country="'.$row_Hotel->id_mst_country_lang.'"');
		$HotelCity	   =$row_Hotel->city;
		$HotelPincode	=$row_Hotel->pincode;
		$HotelGST		=$row_Hotel->gstin;
		$HotelAddress	=$row_Hotel->address;
		$Hotelsecondary_landline	=$row_Hotel->primary_mobile;	
		$Hotelsecondary_mobile	=$row_Hotel->secondary_mobile!==''?'PH +91 '.$row_Hotel->secondary_mobile:'';	
		$Hotelsecondary_landline = $Hotelsecondary_landline.' '.$Hotelsecondary_mobile;
        $Hotelpan	=$row_Hotel->pan;
        $HotelEmail	=$row_Hotel->email;

function fv($value, $minWidth = '140px') {
    if (!empty(trim((string)$value))) {
        return '<div class="value">' . htmlspecialchars($value) . '</div>';
    }
    return '<div class="value empty-field" style="min-width:' . $minWidth . ';">&nbsp;</div>';
}

$logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');

?>

    <div class="page">
        <div class="page-content">
            <header class="header">
                <img src="<?php echo $SITE_URL.'/uploaded_files/outlets/'.$logoImage; ?>">
                <div class="hotel-info">
                    <strong><?php echo $HotelName; ?></strong>
                    <?= $HotelAddress; ?><br>
                    <?= $HotelCity.','.$HotelState.','.$HotelCountry;; ?><br>
                    GSTIN/UIN: <?=$HotelGST;?><br>
                    Email: <?=$HotelEmail;?>
                </div>
            </header>
			
			<div class="print-time-bar" style="text-align:right; font-size:10px; color:#444; padding: 2px 6px; border-bottom: 1px solid #000;">
				<strong>Print Date & Time:</strong> <?php echo $print_time; ?>
			</div>

            <div class="title">Guest Registration Card</div>
			
			<?php 
			//============mst_guest=================
			
			$SQL_Guest = "SELECT * FROM " . TBL_GUEST . " WHERE id = ? AND status = ? AND id_shop = ?";
			$stmtGuest = $connNew->prepare($SQL_Guest);

			$guestStatus = 1;
			$shopId = $_SESSION['shop'];
			
			$stmtGuest->bind_param("iii", $id_guest, $guestStatus, $shopId);
			$stmtGuest->execute();
			$resultGuest = $stmtGuest->get_result();
			$row_Guest = $resultGuest->fetch_object();
			
			if ($row_Guest) {
				$guest_first_name = $row_Guest->first_name;
				$guest_last_name = $row_Guest->last_name;
				$guest_name = $guest_first_name . ' ' . $guest_last_name;
				$guest_reg_no = $row_Guest->guest_reg_no;
				$guest_gst_no = ($row_Guest && isset($row_Guest->guest_gst_no)) ? $row_Guest->guest_gst_no : '';
				
				$birth_date = $row_Guest->date_birth_day;
				$birth_month = $row_Guest->date_birth_month;
				
				if (!empty($birth_date) && !empty($birth_month)) {
					$birth_day = str_pad($birth_date, 2, "0", STR_PAD_LEFT) . '/' . str_pad($birth_month, 2, "0", STR_PAD_LEFT);
				} else {
					$birth_day = "";
				}
				
				$id_nationality = $row_Guest->id_mst_country_lang_nationality;
				$id_country = $row_Guest->id_mst_country_lang;
				
				$aadhar = ($row_Guest && isset($row_Guest->adhar_no)) ? $row_Guest->adhar_no : '';
				
				$passport_no = ($row_Guest && isset($row_Guest->passport_no)) ? $row_Guest->passport_no : '';
				$passport_raw = ($row_Guest && !empty($row_Guest->passport_expiry_date)) ? $row_Guest->passport_expiry_date : '';
				$passportExp = (!empty($passport_raw) && $passport_raw != '0000-00-00' && $passport_raw != '1970-01-01') 
               ? date("d/m/Y", strtotime($passport_raw)) 
               : '';
				
				//$visa_no = ($row_Guest && isset($row_Guest->passport_no)) ? $row_Guest->passport_no : 'N/A';
				$visa_raw = ($row_Guest && !empty($row_Guest->visa_expiry_date)) ? $row_Guest->visa_expiry_date : '';
				
				$visaExp = (!empty($visa_raw) && $visa_raw != '0000-00-00' && $visa_raw != '1970-01-01') 
           ? date("d/m/Y", strtotime($visa_raw)) 
           : '';
				
			} else {
				$guest_name = "";
				$guest_reg_no = "";
				$guest_gst_no = "";
			}
			
			//===================mst_country_lang===================
			
			$SQL_country = "SELECT * FROM mst_country_lang WHERE id_country = ? AND status = ?";
			$stmtCountry = $connNew->prepare($SQL_country);
			
			$country_status = 1;

			$stmtCountry->bind_param("ii", $id_country, $country_status);
			$stmtCountry->execute();
			$resultCountry = $stmtCountry->get_result();
			$row_Country = $resultCountry->fetch_object();
			
			$nationality = ($row_Country && isset($row_Country->nationality)) ? $row_Country->nationality : '';
			$country_name = ($row_Country && isset($row_Country->name)) ? $row_Country->name : '';
			
			//==============fo_reservations_details===============
			
			/*$SQL_res_id = "SELECT id_fo_reservations FROM fo_reservations_details WHERE id_mst_guest = ? ORDER BY id ASC LIMIT 1";
			$resid = $connNew->prepare($SQL_res_id);
			$resid->bind_param("i", $id_guest);
			$resid->execute();
			$result = $resid->get_result();
			$residresult = $result->fetch_assoc();*/
			
			//$res_id = ($residresult && !empty($residresult['id_fo_reservations'])) ? $residresult['id_fo_reservations'] : 0;
			$res_id = $id_res;
			
			$SQL_other_details = "SELECT * FROM fo_reservations_details WHERE id_fo_reservations = ? AND id_fo_folio = ? GROUP BY order_by_room ORDER BY id ASC";
$otherDetail = $connNew->prepare($SQL_other_details);
$otherDetail->bind_param("ii", $res_id, $id_folio);
$otherDetail->execute();
$resultDetails = $otherDetail->get_result();

$all_details = [];
$shopId = $_SESSION['shop'];
$statusActive = 1;

while($row = $resultDetails->fetch_assoc()){
    // Fetch Room Type Name for THIS row
    $row['room_type_name'] = selectColumn('mst_room_types', 'name', "WHERE id = '".$row['id_mst_room_types']."' AND id_shop = '$shopId'");
    
    // Fetch Room Number for THIS row
    $row['room_no_val'] = selectColumn('mst_room_no_allocation', 'room_no', "WHERE id = '".$row['id_mst_room_no_reserved']."'");
    
    // Fetch Rate Plan Name for THIS row
    $row['rate_plan_name'] = selectColumn('fo_rate_plan', 'name', "WHERE id = '".$row['id_fo_rate_plan']."' AND  id_shop = '$shopId'");
    
    $all_details[] = $row;
}
			
			$SQL_otherForm_details = "SELECT * FROM fo_reservations_details WHERE id_fo_reservations = ? AND id_mst_guest = ? AND id_fo_folio = ? GROUP BY order_by_room ORDER BY id ASC LIMIT 1";
			
			$otherFormDetail = $connNew->prepare($SQL_otherForm_details);
			$otherFormDetail->bind_param("iii", $res_id, $id_guest, $id_folio);
			$otherFormDetail->execute();
			$latestresult = $otherFormDetail->get_result();
			
			$latestdetail = $latestresult->fetch_assoc();
			
			//$latestdetail = !empty($all_details) ? $all_details[0] : null;
			
			//$res_id = ($latestdetail && !empty($latestdetail['id_fo_reservations'])) ? $latestdetail['id_fo_reservations'] : 0;
			
			$c_form_no = ($latestdetail && !empty($latestdetail['c_form_no'])) ? $latestdetail['c_form_no'] : '';
			
			$arrivalDate_raw = ($latestdetail && !empty($latestdetail['arrival_in_india'])) ? $latestdetail['arrival_in_india'] : '';
			$arrival_date = (!empty($arrivalDate_raw) && $arrivalDate_raw != '0000-00-00') ? date("d/m/Y", strtotime($arrivalDate_raw)) : '';
			
			$purpose_of_visit = ($latestdetail && !empty($latestdetail['purpose_of_visit'])) ? $latestdetail['purpose_of_visit'] : '';
			
			$arrival_from = ($latestdetail && !empty($latestdetail['arrival_from'])) ? $latestdetail['arrival_from'] : '';
			
			$departure_to = ($latestdetail && !empty($latestdetail['departure_to'])) ? $latestdetail['departure_to'] : '';
			
			$id_room_type = ($latestdetail && !empty($latestdetail['id_mst_room_types'])) ? $latestdetail['id_mst_room_types'] : 0;
			
			//$id_rate_plan = ($latestdetail && !empty($latestdetail['id_fo_rate_plan'])) ? $latestdetail['id_fo_rate_plan'] : 0;
			
			//$id_room_no = ($latestdetail && !empty($latestdetail['id_mst_room_no_allocation'])) ? $latestdetail['id_mst_room_no_allocation'] : 0;
			
			//$adults_per_room = ($latestdetail && !empty($latestdetail['adults_per_room'])) ? $latestdetail['adults_per_room'] : 0;
			 
			
			//===============fo_reservations===============
			
			$SQL_reservation = "SELECT * FROM fo_reservations WHERE id = ? ORDER BY id DESC";
			$reservation = $connNew->prepare($SQL_reservation);
			$reservation->bind_param("i", $res_id);
			$reservation->execute();
			$reserveationResult = $reservation->get_result();
			$reservationRow = $reserveationResult->fetch_assoc();
			
			 
			$booking_no = ($reservationRow && !empty($reservationRow['booking_no'])) ? $reservationRow['booking_no'] : '-';
			
			$checkin_raw = ($reservationRow && !empty($reservationRow['checkin'])) ? $reservationRow['checkin'] : '-';
			
			$checkin = ($checkin_raw !== '-') ? date("d/m/Y", strtotime($checkin_raw)) : '-';
			
			$checkout_raw = ($reservationRow && !empty($reservationRow['checkout'])) ? $reservationRow['checkout'] : '-';
			
			$checkout = ($checkout_raw !== '-') ? date("d/m/Y", strtotime($checkout_raw)) : '-';
			
			$id_company = ($reservationRow && !empty($reservationRow['id_mst_company'])) ? $reservationRow['id_mst_company'] : 0;
			
			$payment_instruction = ($reservationRow && !empty($reservationRow['res_payment_instruction'])) ? $reservationRow['res_payment_instruction'] : '';
			
			$special_instruction = ($reservationRow && !empty($reservationRow['res_special_notes'])) ? $reservationRow['res_special_notes'] : '';
			
			//================mst_room_types===============
			
			$id_room_type = ($latestdetail && !empty($latestdetail['id_mst_room_types'])) ? $latestdetail['id_mst_room_types'] : 0;
			$id_shop = 2;
			$status = 1;
			
			$SQL_roomtype = "SELECT name FROM mst_room_types WHERE id = ? AND id_shop = ? AND status = ? ORDER BY id DESC";
			$room = $connNew->prepare($SQL_roomtype);
			$room->bind_param("iii", $id_room_type,$id_shop,$status);
			$room->execute();
			$roomResult = $room->get_result();
			$roomRow = $roomResult->fetch_assoc();
			
			$room_type = ($roomRow && !empty($roomRow['name'])) ? $roomRow['name'] : '';
			
			//==================mst_room_no_allocation====================
			
			$id_room_no = ($latestdetail && !empty($latestdetail['id_mst_room_no_allocation'])) ? $latestdetail['id_mst_room_no_allocation'] : 0;
			$status = 1;
			
			$SQL_roomNo = "SELECT room_no FROM mst_room_no_allocation WHERE id = ? AND status = ? ORDER BY id DESC";
			$roomNo = $connNew->prepare($SQL_roomNo);
			$roomNo->bind_param("ii", $id_room_no,$status);
			$roomNo->execute();
			$roomNoResult = $roomNo->get_result();
			$roomNoRow = $roomNoResult->fetch_assoc();
			
			$room_no = ($roomNoRow && !empty($roomNoRow['room_no'])) ? $roomNoRow['room_no'] : '';
			
			//====================fo_rate_plan================
			
			$id_rate_plan = ($latestdetail && !empty($latestdetail['id_fo_rate_plan'])) ? $latestdetail['id_fo_rate_plan'] : 0;
			$id_shop = 2;
			$status = 1;
			
			$SQL_rateplan = "SELECT name FROM fo_rate_plan WHERE id = ? AND id_shop = ? AND status = ? ORDER BY id DESC";
			$plan = $connNew->prepare($SQL_rateplan);
			$plan->bind_param("iii", $id_rate_plan,$id_shop,$status);
			$plan->execute();
			$planResult = $plan->get_result();
			$rateplanRow = $planResult->fetch_assoc();
			
			$rate_plan = ($rateplanRow && !empty($rateplanRow['name'])) ? $rateplanRow['name'] : '';
			
			//===================mst_company===================
			$id_company = ($reservationRow && !empty($reservationRow['id_mst_company'])) ? $reservationRow['id_mst_company'] : 0;
			$id_shop = 2;
			$status = 1;
			
			$SQL_company = "SELECT * FROM mst_company WHERE id = ? AND id_shop = ? AND status = ? ORDER BY id DESC";
			$company = $connNew->prepare($SQL_company);
			$company->bind_param("iii", $id_company,$id_shop,$status);
			$company->execute();
			$companyResult = $company->get_result();
			$companyRow = $companyResult->fetch_assoc();
			
			$company_name = ($companyRow && !empty($companyRow['name'])) ? $companyRow['name'] : '';
			$company_city = ($companyRow && !empty($companyRow['city'])) ? $companyRow['city'] : '';
			$company_group_id = ($companyRow && !empty($companyRow['id_mst_attributes_company_group'])) ? $companyRow['id_mst_attributes_company_group'] : '';
			
			$company_grp_name = selectColumn('mst_attributes','field_value',"WHERE id = '".$company_group_id."'");
			
			//===============fo_receipt================
			
			// Query to get the FIRST res_detail record
			$SQL_first_detail = "SELECT id_fo_bill FROM fo_reservations_details WHERE id_mst_guest = ? AND id_fo_folio =? AND id_fo_reservations = ? AND id_fo_bill > 0 ORDER BY id ASC LIMIT 1";
			$stmt_first = $connNew->prepare($SQL_first_detail);
			$stmt_first->bind_param("iii", $id_guest, $id_folio, $res_id); 
			$stmt_first->execute();
			$firstResult = $stmt_first->get_result();
			$firstDetail = $firstResult->fetch_assoc();

			$id_fo_bill = (isset($firstDetail['id_fo_bill'])) ? $firstDetail['id_fo_bill'] : '';
			$id_fo_folio = $id_folio;
			
			$SQL_receipt = "SELECT *, 'standard' as receipt_type 
			FROM fo_receipt 
			WHERE id_fo_bill = ? 
			AND id_fo_folio = ? 
			AND id_fo_bill > 0 
			ORDER BY id DESC LIMIT 1";
			$receipt = $connNew->prepare($SQL_receipt);
			$receipt->bind_param("ii", $id_fo_bill, $id_fo_folio);
			$receipt->execute();
			$receiptResult = $receipt->get_result();
			$receiptRow = $receiptResult->fetch_assoc();
			
			if(!$receiptRow){
			 $SQL_advance = "SELECT *, 'advance' as receipt_type 
                    FROM fo_receipt 
                    WHERE id_reservation = ? 
                    AND (id_fo_bill = ? OR id_fo_bill = 0 OR id_fo_bill IS NULL) 
                    ORDER BY id DESC LIMIT 1";
				$advReceipt = $connNew->prepare($SQL_advance);
				$advReceipt->bind_param("ii", $res_id, $id_fo_bill);
				$advReceipt->execute();
				$advResult = $advReceipt->get_result();
				$receiptRow = $advResult->fetch_assoc();
			}
			
			$payment_mode = ($receiptRow && !empty($receiptRow['payment_mode'])) ? $receiptRow['payment_mode'] : '';
			
			$id_pay_company = ($receiptRow && !empty($receiptRow['id_company'])) ? $receiptRow['id_company'] : 0;
			
			if (!empty($id_pay_company) && $id_pay_company > 0) {
    			$billed_company_name = selectColumn('mst_company', 'name', "WHERE id = '$id_pay_company'") ?? '';
			} else {
    			$billed_company_name = '';
			}
			
			?>

            <div class="section">
                <div class="grid-row">
                    <div class="grid-col" style="">
                        <div class="field inline" style="margin-bottom: 0!important; ">
							<label>Serial No:</label>
							<div class="value">
								<?=$guest_reg_no;?>
							</div>
						</div>
                    </div>
                    <div class="grid-col">
                        <div class="field inline" style="margin-bottom: 0!important;">
							<label>C Form No:</label>
							<div class="value">
								<?=$c_form_no;?>
							</div>
						</div>
                    </div>
                    <div class="grid-col">
                        <div class="field inline" style="margin-bottom: 0!important;">
							<label>Booking No:</label>
							<div class="value">
								<?=$booking_no;?>
							</div>
						</div>
                    </div>
                </div>
                <div class="grid-row">
                    <div class="grid-col" style="flex: 1.5;">
                        <div class="field" style="margin-bottom: 0!important;">
							<label>Guest Name</label>
							<div class="value">
								<?=$guest_name;?>
							</div>
						</div>
                    </div>
                    <div class="grid-col">
                        <div class="field " style="margin-bottom: 0!important;">
							<label>Check In</label>
							<div class="value">
								<?=$checkin;?>
							</div>
						</div>
                    </div>
                    <div class="grid-col">
                        <div class="field" style="margin-bottom: 0!important;">
							<label>Check Out</label>
							<div class="value">
								<?=$checkout;?>
							</div>
						</div>
                    </div>
                </div>
            </div>

            <div class="section">
                <div class="section-header " style="border-bottom: none!important;">Reservation Details</div>
               <table>
    <thead>
        <tr>
            <th style="border-left: none; ">Room Type</th>
            <th>Room No.</th>
            <th>Pax</th>
            <th>Meal Plan</th>
            <!--<th style="border-right: none;">Rate</th>-->
        </tr>
    </thead>
    <tbody>
		<?php if (!empty($all_details)): ?>
		<?php foreach ($all_details as $detail): ?>
        <tr>
            <td class="value" style="border-left: none;"><?= $detail['room_type_name'] ?: ''; ?></td>
            <td class="value"><?= $detail['room_no_val'] ?: 'Not Allocated'; ?></td>
            <td class="value"><?= $detail['adults_per_room']; ?></td>
            <td class="value"><?= $detail['rate_plan_name'] ?: ''; ?></td>
            <!--<td style="border-right: none;"></td>-->
        </tr>
	<?php endforeach; ?>
            <?php else: ?>
        <tr><td colspan="4">No reservation details found.</td></tr>
            <?php endif; ?>
    </tbody>
</table>
<table style="border-top: none;">
    <thead>
        <tr>
            <th style="border-top: none; border-left: none;">Source</th>
            <th style="border-top: none;"><?= $company_grp_name; ?></th>
            <!--<th style="border-top: none;">Direct</th>
            <th style="border-top: none;">Corporate Name</th>
            <th style="border-top: none; border-right: none;">OTA Name</th>-->
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="value" style=" border-left: none; border-bottom: none;"></td>
            <td class="value" style="border-bottom: none;"><?=$company_name.' '.$company_city;?></td>
            <!--<td style="border-bottom: none;"></td>
            <td style="border-bottom: none;"></td>
            <td class="value" style="border-right: none; border-bottom: none;"></td>-->
        </tr>
    </tbody>
</table>
            </div>

            <div class="section details-split">
                <div class="details-left">
                    <div class="section-header">Travel & Billing Information</div>
                   <div class="details-padding">
    <div class="field inline"><label>Company Name:</label><?= fv($billed_company_name) ?></div>
    <!--<div class="field inline"><label>Designation:</label><div class="value">Regional Manager</div></div>-->
    <div class="field inline"><label>Purpose of Visit:</label><?= fv($purpose_of_visit) ?></div>
    <div class="field inline"><label>Arriving From:</label><?= fv($arrival_from) ?></div>
    <div class="field inline"><label>Proceeding To:</label><?= fv($departure_to) ?></div>
    <div class="field inline"><label>Billing Instruct:</label><?= fv($payment_instruction) ?></div>
    <div class="field inline"><label>Mode of Payment:</label><?= fv($payment_mode) ?></div>
    <div class="field inline" style="margin-top: 12px;"><label>Guest Status:</label><div class="value">_____________</div></div>
    <div class="field inline"><label>Guest GST No:</label><?= fv($guest_gst_no) ?></div>
</div>
                </div>

                <div class="details-right">
                    <div class="section-header">Identification & Visa Details</div>
              <div class="details-padding foreign-grid">
    <div class="field"><label>Date of Birth</label><?= fv($birth_day) ?></div>
				 
    <div class="field"><label>Nationality</label><?= fv($nationality) ?></div>

		
				  
    <div class="field"><label>Aadhar No.</label><?=fv($aadhar);?></div>
				 
				  <?php// if (!empty($passport_no) && $passport_no !== 'N/A'): ?>
    <div class="field"><label>Passport No.</label><?= fv($passport_no) ?></div>
	<div class="field"><label>Date of  Passport Expiry</label><?=fv($passportExp);?></div>
				 <?php// endif; ?> 
    <!--<div class="field"><label>Place of Issue</label><div class="value">London</div></div>-->
    <!--<div class="field"><label>Date of Issue</label><div class="value">10/05/2020</div></div>-->
    
    <?php// if (!empty($visaExp) && $visaExp !== 'N/A'): ?>
    <!--<div class="field"><label>Visa No.</label><div class="value">V987654321</div></div>-->
    <!--<div class="field"><label>Visa Date Expiry</label><div class="value"><?=$visaExp;?></div></div>-->
				  <?php// endif; ?> 
	<!--<div class="field"><label>Visa Place Issue</label><div class="value">London</div></div>
    <div class="field"><label>Visa Date Issue</label><div class="value">01/03/2026</div></div>-->
    
    <div class="field"><label>Arrival in India</label><?=fv($arrival_date);?></div>
    <!--<div class="field"><label>Duration of Stay</label><div class="value">14 Days</div></div>-->
</div>
                </div>
            </div>

            <div class="section">
                <div class="section-header">Special Instructions</div>
                <div style="padding: 5px; "><?=htmlspecialchars($special_instruction);?></div>
            </div>

            <div class="rules-box">
                <h4>Rules and Regulations</h4>
                <div class="rules-intro">Guest signing this document represents that he/she will abide by the house rules and is authorized by person/s staying with him/her to execute this document on their behalf and agrees:</div>
                
                <div class="rules-list">
                    <p><strong>1. Check In & Checkout Time:</strong> Our Check-In time is 1.00 PM and Check-Out time is 11.00 AM.</p>
                    <p><strong>2. Departure:</strong> "Check-Out" times are 11 AM. On failure of the same guest will be charged as per rate or if you wish to retain your room can contact with front office.</p>
                    <p><strong>3. Payment:</strong> To pay the room charges mentioned above and to settle the hotel bills by mode of payment acceptable by the hotel. Personal cheques are not accepted. In case the organization/individual fails to settle the bill, the guest will be personally liable. The guest agrees to be held responsible for any and all charges incurred during the stay and agrees to settle all his account on demand. The guest authorizes the hotel management to charge his/her credit/debit card for any charges not settled upon departure.</p>
                    <p><strong>4. Liquor:</strong> Consumption of liquor in public place like lawn/swimming pool/restaurant is totally prohibited.</p>
                    <p><strong>5. Luggage Storage:</strong> Guest Luggage can be stored in the left luggage room at the guest sole risk as to loss or damage from any cause.</p>
                    <p><strong>6. Damage to Property:</strong> Guest will be held responsible for any loss or damage to the hotel property caused by them.</p>
                    <p><strong>7. Government Rules & Regulation:</strong> Guest is requested to observe the government Rules & Regulations in force from time to time in registration & alcoholic drinks & fire alarms.</p>
                    <p><strong>8. Personal Servants & Pets:</strong> No Personal servants are allowed in the guest room and other areas in the hotel. Pets: No pets are allowed in the rooms or other parts of the hotel.</p>
                    <p><strong>9. Arms & Ammunition:</strong> No Arms & Ammunition are allowed in the rooms or other parts of the hotel.</p>
                    <p><strong>10. Non Residential Guest:</strong> No outsider / Visitors are allowed in the guest rooms.</p>
                    <p><strong>11. Amendments of Rules:</strong> The Management reserves to itself the right to add to alter or amend any of the above terms & conditions and rules.</p>
                    <p><strong>12. Liability:</strong> Resort will not be responsible or pay you compensation for any injury, illness, death, loss, damage, expense, cost or other claim of any description.</p>
                    <p><strong>13. Valuables:</strong> Resort or Hotel Management is not responsible for your personal belongings and valuables like money, jewellery or any other valuables left by guests in the rooms.</p>
                    <p><strong>14. Parking:</strong> The Resort is not responsible for damage or disappearance of vehicles kept in the hotel’s parking area or valuables inside the vehicle. The hotel is obliged to clearly express at the parking area that the area is not supervised and the Resort is not responsible for the property kept in there.</p>
                    <p><strong>15. Disputes:</strong> All disputes arising from or incidental to stay itself or through any Franchisor or any other persons directly or indirectly involved shall be subject matter of dispute between guest and resort only and shall be referable to arbitration in accordance with the Indian Arbitration and conciliation Act, 1996 conducted in the city where the hotel is situated by a sole arbitrator who shall inter alia shall have qualification of ten years experience in the hospitality industry and be duly appointed by Indian Council of Arbitration. All disputes between the guest and the Resort shall be governed by Indian Law and only courts in India having territorial jurisdiction where the hotel is situated and none other shall have jurisdiction in respect of such disputes.</p>
                </div>
                
                <div class="declaration">All personal particulars provided above are true and correct. I have carefully read and understood the terms and agree that the same shall be binding upon me.</div>
            </div>

            <div class="signatures" style="border-bottom: 0;">
                <div class="sig-box" style="border-left: 0;">Prepared By (PPDA)</div>
                <div class="sig-box">Front Office Manager (FOM)</div>
                <div class="sig-box" style="border-right: 0;">Guest Signature</div>
            </div>
        </div>
    </div>
