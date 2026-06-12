<?php
include_once("../../config/auto_loader.php");
include_once("../../functions/inventoryUpdateFunctions.php");
//include_once("../apiPmsToCrsReservation.php");
// debugData($_REQUEST);die;


$check_in = date('Y-m-d', strtotime($_REQUEST['checkin_extend_date']));
$check_out = date('Y-m-d', strtotime($_REQUEST['checkout_extend_date']));

if (strtotime($check_out) <= strtotime($check_in)) {
   // echo "Invalid Date: Checkout cannot be earlier than or equal to Check-in.";
    // Optionally, stop execution or redirect
	$ResultArray['message']="Invalid Date: Check-in: ".$check_in." checkout: ".$check_out ;
	$ResultArray['id_follio']=$id_fo_folio_to_update;
	$ResultArray['editid']=$_REQUEST['editid'];
	$ResultArray['id_fo_reservations']=$fo_reservations_id;
	$ResultArray['status']=0;
	
	echo  json_encode($ResultArray);
    exit;
}


$resourceId=$_REQUEST['res_room'];
 
$parentid=$_REQUEST['parentId'];
$parent = explode("-", $parentid);
$pid = $parent[0]; 

  $bk_no=$_REQUEST['res_bookingNo'];
  $id_doc_type_configuration = $_REQUEST['id_doc_type_configuration'];
  $id_guest = $_REQUEST['id_mst_guest_form'];
  $id_company = $_REQUEST['id_mst_company_new'];
  $id_company_contacts = $_REQUEST['id_mst_company_contacts_new'];
  $pay_sts=$_REQUEST['res_paymentStatus'];
  $tar_amt=$_REQUEST['res_tariffamt'];
  $total_addon_amount=$_REQUEST['res_addonamt'];
  $res_taxes=array_sum($_REQUEST['perday_tax']);
  $id_hotel=$_REQUEST['id_mst_hotels_new'];
  $payment_received=$_REQUEST['payment_received'];
  $bal=array_sum($_REQUEST['perday_tax'])+array_sum($_REQUEST['tariff_per_room_per_night']);
  $bk_date=date('Y-m-d',strtotime($_REQUEST['bookingDate']));
  $check_in=date('Y-m-d',strtotime($_REQUEST['checkin_extend_date']));
  $check_out=date('Y-m-d',strtotime($_REQUEST['checkout_extend_date']));
  
  $id_mst_attributes_company_group=$_REQUEST['id_mst_attributes_company_group'];
				
  $_REQUEST['perday_tax'];
  $_REQUEST['tariff_per_room_per_night'];
  
  
  
  $res_arrivingtime=$_REQUEST['res_arrivingtime'];
  $res_arrivingfrom=$_REQUEST['res_arrivingfrom'];
  $pickupd=$_REQUEST['res_departingto'];
  $res_pickuprequired=$_REQUEST['res_pickuprequired'];
  $remarks=$_REQUEST['res_remarks'];
  $pickupa=$_REQUEST['res_modeoftravel'];
  $pickup_details=$_REQUEST['res_pickupdetails'];
  $spe_rqt=$_REQUEST['res_specialrequest'];
  $internal_remarks=$_REQUEST['internal_remarks'];
  $res_segment=$_REQUEST['res_segment'];
  $res_bookingsourcee=$_REQUEST['res_bookingsourcee'];
  $res_bookingthrough=$_REQUEST['res_bookingthrough'];
  
  $dis=$_REQUEST['total_discount']; 
  $bk_stsa=date('Y-m-d',strtotime($_REQUEST['res_holdTillDate']));
  $confirm=date('Y-m-d',strtotime($_REQUEST['res_confirmDate']));
  $other_ref=$_REQUEST['other_ref'];
  $id_mst_attributes_amendment=$_REQUEST['res_amendment'];
  
  $sub_total = array_sum($_REQUEST['tariff_per_room_per_night']);//($tar_amt + $bal + $total_addon_amount) - $dis;
  $net_booking_amount =$bal=array_sum($_REQUEST['perday_tax'])+array_sum($_REQUEST['tariff_per_room_per_night']);// $sub_total + $res_taxes;
  
  
  $bk_sts=$_REQUEST['res_bookingStatus_new'];
  $bk_stsb=$_REQUEST['res_cancellation'];
  $hotel_name=$_REQUEST['res_hotelName'];
  $guest_name=$_REQUEST['res_guestName'];
  $bk_type=$_REQUEST['res_bookingType'];
  $source=$_REQUEST['res_source'];
  $bk_name=$_REQUEST['res_bookerName'];
  $rate_type=$_REQUEST['res_rateType'];
  $rate_letter=$_REQUEST['res_rateLetter'];
  $room_type=$_REQUEST['roomtype'];
  $plan=$_REQUEST['plan'];
  $adult=$_REQUEST['adultperperson'];
  $child=$_REQUEST['childperperson'];
  $ex_child=$_REQUEST['extrachild'];
  $tariff=$_REQUEST['tariffperperson'];
  $room_tax=$_REQUEST['taxes'];
  $charge=$_REQUEST['chargespernight'];
  $res_cancellation=$_REQUEST['res_cancellation'];
  
  $item=$_REQUEST['item'];
  $des=$_REQUEST['additionalcharges'];
  $qty=$_REQUEST['qty'];
  $unit=$_REQUEST['unit'];
  $rate=$_REQUEST['rate'];
  $tax_value=$_REQUEST['taxvalue'];
  $amount=$_REQUEST['amount'];
  $ad_ricve=$_REQUEST['res_advance'];
  $ref=$_REQUEST['res_reference'];
  $bill_to=$_REQUEST['res_billto'];
  
  $checkIn_test= date('Y-m-d', strtotime( $check_in . " + 1 days"));
  $checkOut_test= date('Y-m-d', strtotime( $check_out . " - 1 days"));
  
  $startTimeStamp = strtotime($check_in);
  $endTimeStamp = strtotime($checkOut_test);
  $timeDiff = abs($endTimeStamp - $startTimeStamp);
  $numberDays = $timeDiff/86400; 
  $numberDays = intval($numberDays);
  $numberDays = $numberDays + 1;
  




$roomtype=sizeof($_REQUEST['roomtype']);
$bd_date=sizeof($_REQUEST['bd_date']);
$roomno=sizeof($_REQUEST['roomno']);
  
  
$foodval = explode(',', $_REQUEST['foodval']);



$bdfoodcount = 0;
for($i=0;$i<$roomno;$i++){ 
	$total_adults += $_REQUEST['adultperroom'][$i];
$bd_datee = $bd_date / $roomno ; 
	for($k=0;$k<$bd_datee;$k++){
		
		 $total_food_price += $_REQUEST['bd_food'][$bdfoodcount];
		$total_extrabed_price += $_REQUEST['bd_extrabed'][$bdfoodcount];
		$total_child_without_bed += $_REQUEST['bd_extrachild'][$bdfoodcount];
		$bdfoodcount++;
	}
	
} 
if($_REQUEST['editid']==''){
	
	include_once("../functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	$insertGrid = "INSERT INTO ".FO_RESERVATIONS."  SET
				
				
				`id_mst_shops`='".$_SESSION['shop']."',
				`id_shop_group`='1',
				`id_mst_country_lang`='0',
				`id_cart`='0',
				`id_mst_currency_base`='0',
				`id_mst_currency_transaction`='0',
				`conversion_rate`='1',
				`sub_total`='".$sub_total."',
				`net_booking_amount`='".$net_booking_amount."',
				`booking_confirm_date`='".$confirm."',
				`tentative_hold_date`='".$bk_stsa."',
				`other_reference`='".$other_ref."',
				`id_mst_attributes_cancellation`='".$res_cancellation."',
				`id_mst_attributes_amendment`='".$id_mst_attributes_amendment."',
				`no_of_days`='".$numberDays."',
				`booking_no`='".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
				`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
				`doc_no`='".addslashes($docConfig['po_no'])."',
				`doc_date`='".date('Y-m-d',strtotime($date))."',
				`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
				`doc_type` = '".addslashes($id_doc_type)."',
				`reference`='".$_REQUEST['reference']."',
					`created_by_pms` = '1',	
					`res_internal_remarks`='".$_REQUEST['res_interenal_remarks']."',
					`payment_date`='".date('Y-m-d',strtotime($_REQUEST['tentative_date']))."',
					
					`res_special_notes`='".$_REQUEST['res_special_notes']."',
					`res_payment_instruction`='".$_REQUEST['res_payment_instruction']."',
					`res_payment_status`='".$_REQUEST['res_payment_status']."',
					`res_complimentary_booking`='".$_REQUEST['res_complimentary_booking']."',
					`id_mst_hotels`='".$id_hotel."',
					`id_mst_guest`='".$id_guest."',
					`id_mst_attributes_company_group`='".$id_mst_attributes_company_group."',
					`id_mst_company`='".$id_company."',
					`id_mst_company_contacts`='".$id_company_contacts."',
					`id_mst_attributes_payment_status`='".$pay_sts."',
					`booking_status`='".$bk_sts."',
					`room_tariff_price`='".$tar_amt."',
					`discount`='".$dis."',
					`total_addon_price`='".$total_addon_amount."',
					`total_tax`='".$res_taxes."',
					`amount_received`='".$payment_received."',
					`balance`='".$bal."',
					`booking_date`='".$bk_date."',
					`checkin`='".$check_in."',
					`checkout`='".$check_out."',
					`arrival_time`='".$res_arrivingtime."',
					`arrival_from`='".$res_arrivingfrom."',
					`departing_to`='".$pickupd."',
					`pickup`='".$res_pickuprequired."',
					`pickup_details`='".$pickup_details."',
					`id_mst_attributes_mode_of_travel`='".$pickupa."',
					`special_requests`='".$spe_rqt."',
					`internal_remarks`='".$internal_remarks."',
					`id_mst_attributes_segments`='".$res_segment."',
					`id_mst_attributes_booking_source`='".$res_bookingsourcee."',
					`id_mst_attributes_booking_through`='".$res_bookingthrough."',						
					`food_plan_price`='".$total_food_price."',
					`extra_bed_price`='".$total_extrabed_price."',
					`total_adults`='".$total_adults."',
					`total_child_with_bed`='".$total_extrabed_price."',
					`total_child_without_bed`='".$total_child_without_bed."',
					
					`date_created` = '".currenDateTime()."',
					`created_by` = '".$_SESSION['userId']."',
					`last_modified` = '".currenDateTime()."',
					`last_modified_by` = '".$_SESSION['userId']."' ";
					//echo $insertGrid;//die;
			mysqli_query($connNew,$insertGrid);
			$fo_reservations_id = mysqli_insert_id($connNew);
			
			
			$apiHotelID=$id_hotel;
	$ChannelID='1';
	$BookingID=$fo_reservations_id;
			
			
			
			
			
			
			
			
			
			
			

	
}
if($_REQUEST['editid']!='' && $_REQUEST['editid']>0){ //EDIT===========

 		$fo_reservations_id = 	$_REQUEST['editid'];
		//$id_fo_folio_to_update= selectColumn(FO_RESERVATIONS_DETAILS,'id_fo_folio_to'," WHERE `id_fo_reservations` = '".$fo_reservations_id."'");
		$id_fo_folio_to_update=$_REQUEST['id_fo_folio_to'];
}else{
	
	$id_fo_folio_to_update=0;
	}

//Delete


	$r=0;
	// echo "<pre>";
	// print_r($_REQUEST['ReservationDataArray']);
	// exit;
	foreach($_REQUEST['ReservationDataArray'] as $roomInc12=>$ReservationData1){
		
		foreach($ReservationData1 as $ReservationData2){
			
		foreach($ReservationData2['resdate'] as $roomInc=>$loop2){
			
			//debugData($ReservationData2['id_reservation_detail']);
			$ReservationData2['id_reservation_detail'][$roomInc];
			
			if($ReservationData2['postcharges'][$roomInc]=='1'){
				$id_fo_folio_to= selectColumn('fo_bill','id_fo_folio_to'," WHERE `id_reservations` = '".$fo_reservations_id."'");
				$id_fo_bill= selectColumn('fo_bill','id'," WHERE `id_reservations` = '".$fo_reservations_id."'");
				$checkinStatus='1';
			}else{
				
				$id_fo_folio_to= '0';
				$id_fo_bill=  '0';
				$checkinStatus='0';
				}
		if($ReservationData2['id_reservation_detail'][$roomInc]==0){ //ADD Detail Table
//debugData($ReservationData2['resdate']);
	
//echo '=='.$roomInc;

//echo $_REQUEST['tariff_per_room_inclusive_tax'][$roomInc];
$new_folio_to = (isset($_REQUEST['id_fo_folio_to'])) ? $_REQUEST['id_fo_folio_to'] : $id_fo_folio_to;
$roomdetails = " INSERT INTO `".FO_RESERVATIONS_DETAILS."` SET
						`id_fo_reservations` = '".$fo_reservations_id."', 
						`id_shop` = '".$_SESSION['shop']."',
						`id_mst_hotels` = '".$id_hotel."',
						`id_mst_room_no_allocation` = '0',
						`id_mst_guest`='".$id_guest."',
						`id_rate` = '',
						`plan` = '0',
						`child_below_5_year`= '".$ReservationData2['child_below_5_year'][$roomInc]."',
						`child_above_5_year`= '".$ReservationData2['child_above_5_year'][$roomInc]."',
						`id_fo_rate_plan` = '".$ReservationData2['rate_plan_id'][$roomInc]."',
						`dated` = '".date('Y-m-d',strtotime($loop2))."',
						`id_mst_room_types` = '".$ReservationData2['room_type_id'][$roomInc]."',
						`room_quantity` = '1',
						`adults_per_room` = '".$ReservationData2['adult_per_room'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$ReservationData2['perday_tax'][$roomInc]."',
						`fo_folio_temp` = '".$new_folio_to."',
						`unique_code` = '0', `order_by_room` = '".$ReservationData2['order_by_room'][$roomInc]."' ";
				
				mysqli_query($connNew,$roomdetails);
	// echo $roomdetails;
		}else{
			//EDIT========================================> Detail Table
// echo "hgnghn";
			$new_folio_to = (isset($_REQUEST['id_fo_folio_to']) && $id_fo_folio_to != 0) ? $_REQUEST['id_fo_folio_to'] : $id_fo_folio_to;
			$reservation_query = mysqli_query($connNew, "select * from `".FO_RESERVATIONS_DETAILS."` where id = '".$ReservationData2['id_reservation_detail'][$roomInc]."'");
			$reservation_result = mysqli_fetch_object($reservation_query);
			if ($ReservationData2['room_number'][$roomInc] > 0 && $reservation_result->id_mst_room_no_allocation > 0 && $ReservationData2['room_number'][$roomInc] != $reservation_result->id_mst_room_no_allocation) {
				mysqli_query($connNew, "update mst_room_no_allocation set room_status = '4' where id = '".$reservation_result->id_mst_room_no_allocation."'");
				mysqli_query($connNew, "update mst_room_no_allocation set room_status = '3' where id = '".$ReservationData2['room_number'][$roomInc]."'");
			}
			// echo "hi";
			// exit;
			
			  $roomdetails = " UPDATE  `".FO_RESERVATIONS_DETAILS."` SET				 
						
						`checkin_status`='".$checkinStatus."',
						`id_fo_folio`='".addslashes($id_fo_folio_to)."',
						`id_fo_folio_to`='".addslashes($new_folio_to)."',				 
						`id_fo_bill`='".$id_fo_bill."',
						`id_mst_room_no_allocation` ='".$ReservationData2['room_number'][$roomInc]."',
						`id_mst_room_no_reserved` ='".$ReservationData2['room_number'][$roomInc]."',
						`child_below_5_year`= '".$ReservationData2['child_below_5_year'][$roomInc]."',
						`child_above_5_year`= '".$ReservationData2['child_above_5_year'][$roomInc]."',
						`id_fo_rate_plan` = '".$ReservationData2['rate_plan_id'][$roomInc]."',						
						`id_mst_room_types` = '".$ReservationData2['room_type_id'][$roomInc]."',
						`room_quantity` = '1',
						`adults_per_room` = '".$ReservationData2['adult_per_room'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$ReservationData2['perday_tax'][$roomInc]."'
						
						
						WHERE  
						`id_fo_reservations` = '".$fo_reservations_id."'  and `dated` = '".date('Y-m-d',strtotime($loop2))."'
						AND id='".$ReservationData2['id_reservation_detail'][$roomInc]."'
						
						";
				mysqli_query($connNew,$roomdetails);
			
			}
		}
		$r++;
	}

	}
	
  $tariff_price_per_day_per_room	= selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room)','WHERE `id_fo_reservations` = "'.$fo_reservations_id.'" ');                     
  $tax_per_day_per_room	= selectColumn(FO_RESERVATIONS_DETAILS,'sum(tax_per_day_per_room)','WHERE `id_fo_reservations` = "'.$fo_reservations_id.'" ');                     
  $net_booking_amount	=	$tariff_price_per_day_per_room+	$tax_per_day_per_room;
	
	
		   
//if($_REQUEST['editid']!='' && $_REQUEST['editid']>0){ //EDIT===========

 $roomdetails = " UPDATE  `".FO_RESERVATIONS."` SET				 
						
						
					`res_internal_remarks`='".$_REQUEST['res_interenal_remarks']."',
					`res_special_notes`='".$_REQUEST['res_special_notes']."',
					`res_payment_instruction`='".$_REQUEST['res_payment_instruction']."',
					`res_payment_status`='".$_REQUEST['res_payment_status']."',
					`res_complimentary_booking`='".$_REQUEST['res_complimentary_booking']."',
					`id_mst_guest`='".$id_guest."',
					`reference`='".$_REQUEST['reference']."',
					`id_mst_attributes_company_group`='".$id_mst_attributes_company_group."',
					`id_mst_company`='".$id_company."',
					`id_mst_company_contacts`='".$id_company_contacts."',
					`id_mst_attributes_payment_status`='".$pay_sts."',
					`booking_status`='".$bk_sts."',
					`payment_date`='".date('Y-m-d',strtotime($_REQUEST['tentative_date']))."',
					`checkin`='".$check_in."',
					`checkout`='".$check_out."',
					`sub_total`='".$tariff_price_per_day_per_room."',
					`net_booking_amount`='".$net_booking_amount."',
					`total_addon_price`='".$total_addon_amount."',
					`total_tax`='".$tax_per_day_per_room."',
					
					`balance`='".$net_booking_amount."'
						
						WHERE  
						`id` = '".$fo_reservations_id."'  
						
						";
					mysqli_query($connNew,$roomdetails);
					//}
	
	//echo  'Booking Process created Successfully';
	$ResultArray=array();

	//$y= apiPmsToCrsReservation($fo_reservations_id);
	$apiHotelID=$id_hotel;
	$ChannelID='1';
	$BookingID=$fo_reservations_id;
	
	
	
	$r=0;
	foreach($_REQUEST['PostChargesDataArray'] as $roomInc12=>$ReservationData1){
		
		foreach($ReservationData1 as $ReservationData2){
			//debugData($ReservationData1);
		foreach($ReservationData2['resdate'] as $roomInc=>$loop2){
			
			
			$ReservationData2['id_reservation_detail'][$roomInc];
		if($ReservationData2['id_reservation_detail'][$roomInc]==0){ //ADD Detail Table
			
if($ReservationData2['res_unit_id'][$roomInc]=='1'){
	$res_unit	='Per room';
	}
	if($ReservationData2['res_unit_id'][$roomInc]=='2'){
	$res_unit	='Per Adult';
	}
	if($ReservationData2['res_unit_id'][$roomInc]=='3'){
	$res_unit	='Per Nos';
	}

  $roomdetails = " INSERT INTO `fo_reservations_addons_details` SET
						`id_fo_reservations` = '".$fo_reservations_id."',
						`id_shop` = '".$_SESSION['shop']."',
						`id_mst_hotels` = '".$id_hotel."',
						`id_mst_room_no_allocation` = '".$ReservationData2['room_number_id'][$roomInc]."',
						`id_mst_charges` = '".$ReservationData2['ledger_id'][$roomInc]."',
						`additional_description` = '".$ReservationData2['additional_description'][$roomInc]."',
						`id_fo_folio_to` = '".$id_fo_folio_to_update."',
						`dated` = '".date('Y-m-d',strtotime($loop2))."',
						`days` = '".$ReservationData2['res_no_days_id'][$roomInc]."',
						`qty` = '".$ReservationData2['res_no_of_Room_id'][$roomInc]."',
						
						
		`unit` = '".$res_unit."',
		`rate` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
		`tax_percent` = '".$ReservationData2['res_no_days_id'][$roomInc]."',
		`tax_value` = '".$ReservationData2['perday_tax'][$roomInc]."',
		`amount` = '".($ReservationData2['tariff_per_room_per_night'][$roomInc]*$ReservationData2['res_no_of_Room_id'][$roomInc])."',
		`total` = '".$ReservationData2['total'][$roomInc]."' ";
				
				mysqli_query($connNew,$roomdetails);
				
		 		
	//echo '<br><br><br>'.$roomdetails;
		}else{
			//EDIT========================================> Detail Table
			//echo "==========edit";
			/* $roomdetails = " UPDATE  `".FO_RESERVATIONS_DETAILS."` SET				 
						
						
						
						`id_fo_rate_plan` = '".$ReservationData2['rate_plan_id'][$roomInc]."',						
						`id_mst_room_types` = '".$ReservationData2['room_type_id'][$roomInc]."',
						`room_quantity` = '1',
						`adults_per_room` = '".$ReservationData2['adult_per_room'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$ReservationData2['perday_tax'][$roomInc]."'
						
						
						WHERE  
						`id_fo_reservations` = '".$fo_reservations_id."'  and `dated` = '".date('Y-m-d',strtotime($loop2))."'
						AND id='".$ReservationData2['id_reservation_detail'][$roomInc]."'
						
						";
				
				mysqli_query($connNew,$roomdetails);*/
			
			}
		}
		$r++;
	}

	}
	
	//$reservations_addons_details =  "UPDATE `fo_reservations_addons_details` SET `id_fo_folio_to`='".addslashes($id_fo_folio_to_update)."'	  where  id_fo_reservations='".addslashes($fo_reservations_id)."'    ";
			
	 //mysqli_query($connNew,$reservations_addons_details);		
		
	if($checkinStatus=='0'){	
		$today = date('Y-m-d');
	$check_in = date('Y-m-d', strtotime($_REQUEST['checkin_extend_date']));
	$check_out = date('Y-m-d', strtotime($_REQUEST['checkout_extend_date']));
		
		if ($check_in < $today) {
			$final_date = $today;
		} else {
			$final_date = $check_in;
		}
		
    $apiHotelID=$_REQUEST['id_mst_hotels_new'];
	$sqlMappingInventory = 'SELECT auto_sync_inv FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
								WHERE  B.hotel_id="'.$apiHotelID.'" AND B.status=1 and channel_type=1';
		$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
		$resultMapping   =    mysqli_fetch_object($QueryMapping);
		$autoInventoryUpdate=$resultMapping->auto_sync_inv;
		
		
		
		if($autoInventoryUpdate==1){
			updateOTA($apiHotelID,$final_date,date('Y-m-d',strtotime($check_out)));
		}
	}
	
	include_once("../apiPmsToCrsReservation.php");

	$ResultArray['message']='Booking Process created Successfully'.$y ;
	$ResultArray['id_follio']=$id_fo_folio_to_update;
	$ResultArray['editid']=$_REQUEST['editid'];
	$ResultArray['id_fo_reservations']=$fo_reservations_id;
	$ResultArray['status']=1;
	echo  json_encode($ResultArray);
	
	
	die;
	
	
	
	
	
 ?>