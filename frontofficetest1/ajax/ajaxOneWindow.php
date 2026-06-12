<?php
include_once("../../config/auto_loader.php");

//print_r($_REQUEST);
$id_hotel =$_REQUEST['id_mst_hotels'];
//debugeData($_REQUEST);
//die;

/*foreach($_REQUEST['bd_date'] as $dateInc=>$dateLoop){
	///echo '<br>'.$dateInc.'==='.$dateLoop;
	$r=0;
	 $noOfRooms='';
	  $noOfRooms	=	$_REQUEST['noofRooms'][$dateInc];
	for($r=0;$r<$noOfRooms;$r++){
	
	
	//echo '=='.$r;
//	echo '<br>'.'No Of Room='.$noOfRooms.'rloop='.$r.'DateLoop='.$dateInc.'==='.$dateLoop;
	}
	//echo '<br>';
	//echo $dateInc.'==='.$dateLoop;
	
	}
*/



$resourceId=$_REQUEST['res_room'];
 
$parentid=$_REQUEST['parentId'];
$parent = explode("-", $parentid);
$pid = $parent[0]; 

  $bk_no=$_REQUEST['res_bookingNo'];
  $id_doc_type_configuration = $_REQUEST['id_doc_type_configuration'];
  $id_guest = $_REQUEST['id_mst_guest'];
  $id_company = $_REQUEST['id_mst_company'];
  $id_company_contacts = $_REQUEST['res_bookerName'];
  $pay_sts=$_REQUEST['res_paymentStatus'];
  $tar_amt=$_REQUEST['res_tariffamt'];
  $total_addon_amount=$_REQUEST['res_addonamt'];
  $res_taxes=$_REQUEST['total_taxes'];
  $id_hotel=$_REQUEST['id_mst_hotels'];
  $payment_received=$_REQUEST['payment_received'];
  $bal=$_REQUEST['balance'];
  $bk_date=date('Y-m-d',strtotime($_REQUEST['res_bookingDate']));
  $check_in=date('Y-m-d',strtotime($_REQUEST['res_checkinDate']));
  $check_out=date('Y-m-d',strtotime($_REQUEST['res_checkOutDate']));
  
  $id_mst_attributes_company_group=$_REQUEST['id_mst_attributes_company_group'];
				
  
  
  
  
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
  
  $sub_total = $_REQUEST['subtotal'];//($tar_amt + $bal + $total_addon_amount) - $dis;
  $net_booking_amount =$_REQUEST['total'];// $sub_total + $res_taxes;
  
  
  $bk_sts=$_REQUEST['res_bookingStatus'];
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
  

/*echo ''.$sql = "SELECT * FROM ".FO_RESERVATIONS." WHERE id_mst_room_types='$resourceId' && checkin <= '$checkOut_test' && checkout >= '$checkIn_test' && id_mst_room_no_allocation = '$pid' ";
$res = mysqli_query($connNew,$sql);
$rowcount=mysqli_num_rows($res);*/


$roomtype=sizeof($_REQUEST['roomtype']);
$bd_date=sizeof($_REQUEST['bd_date']);
  $roomno=sizeof($_REQUEST['roomno']);
  //var_dump($_REQUEST['bd_food']); 
  
  $foodval = explode(',', $_REQUEST['foodval']);

/*for($i=0;$i<$roomtype;$i++){  
	for($k=0;$k<$bd_date;$k++){
		$sumtotal += $_REQUEST['bd_food'][$k];
	}
}
echo $sumtotal;*/

$bdfoodcount = 0;
for($i=0;$i<$roomno;$i++){ 
	$total_adults += $_REQUEST['adultperroom'][$i];
$bd_datee = $bd_date / $roomno ; 
	for($k=0;$k<$bd_datee;$k++){
		// $sumtotal += $_REQUEST['bd_food'][$bdfoodcount];
		 $total_food_price += $_REQUEST['bd_food'][$bdfoodcount];
		$total_extrabed_price += $_REQUEST['bd_extrabed'][$bdfoodcount];
		$total_child_without_bed += $_REQUEST['bd_extrachild'][$bdfoodcount];
		$bdfoodcount++;
	}
	//$val[]=$sumtotal;
	//$sumtotal=0;
} 
//var_dump($val);


	include_once("../functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	
		//if($rowcount!=1){
		///	echo "Already Reserved";	
		//}else{
	if($_REQUEST['eId']!=''){ //EDIT =========================================
		$insertGrid = "UPDATE `".FO_RESERVATIONS."` SET   
				
				`id_mst_room_no_allocation`='".$pid."',
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
					
					
					
					`last_modified` = '".currenDateTime()."',
					`last_modified_by` = '".$_SESSION['userId']."'  where id='".$_POST['eId']."'";
				//echo $insertGrid;die;
			//$insertOrder	=mysqli_query($connNew,$insertGrid);
		
		
		if(mysqli_query($connNew,$insertGrid)){	
	
	mysqli_query($connNew,"DELETE from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations`='".$_POST['eId']."' ");
	//executeSql("DELETE from `".TBL_OTHERCHARGES_DETAIL."` where id_order='".addslashes(encryptor(decrypt,$_POST['eId']))."' ");
	}
	if($_REQUEST['bd_date']==''){
	while(strtotime($check_in)!=strtotime($check_out)){
			$_REQUEST['bd_date'][]=	date('Y-m-d',strtotime($check_in));
			
	$check_in = date('Y-m-d',strtotime('+1 day',strtotime($check_in)));

	
			
			}}
	//print_r($_REQUEST['bd_date']);
	
	
 foreach($_REQUEST['noofRooms'] as $roomInc=>$roomLoop){
//echo '<br>'.$roomInc.'==='.$roomLoop;
	
	for($r=0;$r<$roomLoop;$r++){
		foreach($_REQUEST['bd_date'] as $dateInc=>$dateLoop){
			//echo $dateInc.'==='.$dateLoop;
	
				$roomsno = $_REQUEST['roomno'][$roomInc];
				$Ch = explode(",", $roomsno);
				
				   $roomdetails = " INSERT INTO `".FO_RESERVATIONS_DETAILS."` SET
						`id_fo_reservations` = '".$_POST['eId']."', 
						`id_shop` = '".$_SESSION['shop']."',
						`id_mst_hotels` = '".$id_hotel."',
						`id_mst_room_no_allocation` = '".$Ch[$r]."',
						`id_mst_guest`='".$id_guest."',
						`id_rate` = '',
						`plan` = '0',
						`id_fo_rate_plan` = '".$_REQUEST['plan'][$roomInc]."',
						`dated` = '".date('Y-m-d',strtotime($dateLoop))."',
						`id_mst_room_types` = '".$_REQUEST['roomtype'][$roomInc]."',
						`room_quantity` = '".$_REQUEST['noofRooms'][$roomInc]."',
						`adults_per_room` = '".$_REQUEST['adultperroom'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$_REQUEST['tariffperroom'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$_REQUEST['taxes'][$roomInc]."',
						`unique_code` = '0', `order_by_room` = '".$r."' ";
				
				mysqli_query($connNew,$roomdetails);
	
	
	
	
		}
	}
	
	
	}
	
	echo 'Booking Process Updated Successfully';
	
	
	}else{
			
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
				//	echo $insertGrid;die;
			mysqli_query($connNew,$insertGrid);
			$fo_reservations_id = mysqli_insert_id($connNew);
			
				//echo "Reserved Successfully";
				   
					for($i=0;$i<$numberDays;$i++){/*
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='$resourceId' and allocation_date='$check_in' and id_mst_hotels = '$id_hotel' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						while($rownew = mysqli_fetch_object($resnew)){ 
							$crs_available = $rownew ->crs_available - 1 ; 
							$confirmed = $rownew->confirmed + 1 ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='$resourceId' and allocation_date='$check_in' and id_mst_hotels = '$id_hotel'";
						
						mysqli_query($connNew,$insertGrid);	
							$check_in= date('Y-m-d', strtotime( $check_in . " + 1 days"));
						}
					*/} 
				//} 
				
				
$no_room=sizeof($_REQUEST['noofRooms']);	
$roomno=sizeof($_REQUEST['roomno']);
   
for($i=0;$i<=$no_room;$i++){
	$rooms += $_REQUEST['noofRooms'][$i];
}
	
$noofdetails = $rooms + $numberDays;
$bd_date=sizeof($_REQUEST['bd_date']);	

$bdfoodcount=0;



 foreach($_REQUEST['noofRooms'] as $roomInc=>$roomLoop){
//echo '<br>'.$roomInc.'==='.$roomLoop;
	
	for($r=0;$r<$roomLoop;$r++){
		foreach($_REQUEST['bd_date'] as $dateInc=>$dateLoop){
			//echo $dateInc.'==='.$dateLoop;
	
				$roomsno = $_REQUEST['roomno'][$roomInc];
				$Ch = explode(",", $roomsno);
				
				   $roomdetails = " INSERT INTO `".FO_RESERVATIONS_DETAILS."` SET
						`id_fo_reservations` = '".$fo_reservations_id."', 
						`id_shop` = '".$_SESSION['shop']."',
						`id_mst_hotels` = '".$id_hotel."',
						`id_mst_guest`='".$id_guest."',
						`id_mst_room_no_allocation` = '".$Ch[$r]."',
						
						`id_rate` = '',
						`plan` = '0',
						`id_fo_rate_plan` = '".$_REQUEST['plan'][$roomInc]."',
						`dated` = '".date('Y-m-d',strtotime($dateLoop))."',
						`id_mst_room_types` = '".$_REQUEST['roomtype'][$roomInc]."',
						`room_quantity` = '".$_REQUEST['noofRooms'][$roomInc]."',
						`adults_per_room` = '".$_REQUEST['adultperroom'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$_REQUEST['tariffperroom'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$_REQUEST['taxes'][$roomInc]."',
						`unique_code` = '0' ,`order_by_room` = '".$r."'  ";
				
				mysqli_query($connNew,$roomdetails);
	
	
	
	
		}
	}
	
		//FO_INVENTORY =======================
	foreach($_REQUEST['bd_date'] as $dateInc=>$dateLoop){
		
		 $sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$_REQUEST['roomtype'][$roomInc]."' and allocation_date='".date('Y-m-d',strtotime($dateLoop))."' and id_mst_hotels = '".$id_hotel."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						while($rownew = mysqli_fetch_object($resnew)){ 
							$crs_available = $rownew ->crs_available - $roomLoop ; 
							$confirmed = $rownew->confirmed + $roomLoop ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$_REQUEST['roomtype'][$roomInc]."' and allocation_date='".date('Y-m-d',strtotime($dateLoop))."' and id_mst_hotels = '".$id_hotel."'";
						//echo '<br>'.$insertGrid;
						mysqli_query($connNew,$insertGrid);	
							
						}
		
		
	}
	//FO_INVENTORY=========================
	}

/*for($i=0;$i<$roomno;$i++){
	
$noofrooms = $_REQUEST['noofRooms'][$i];
$bd_datee = $bd_date / $roomno;
if($i>=1){$bdfoodcount = $bdfoodcount_s; $value = $bdfoodcount;}

	for($j=0;$j<$noofrooms;$j++){

		if($i==0){$bdfoodcount = 0;}	 
		if($i>=1){$bdfoodcount = $value;}
		//$bdfoodcount= $i * $noofrooms;
		
		for($k=0;$k<$bd_datee;$k++){
			
			$roomsno = $_REQUEST['roomno'][$i];
			 $Ch = explode(",", $roomsno);
			
			  $roomdetails = " INSERT INTO `".FO_RESERVATIONS_DETAILS."` SET
				`id_fo_reservations` = '".$fo_reservations_id."', 
				`id_shop` = '".$_SESSION['shop']."',
				`id_mst_hotels` = '".$id_hotel."',
				`id_mst_room_no_allocation` = '".$Ch[$j]."',
				`id_rate` = '',
				`plan` = '".$_REQUEST['plantype'][$i]."',
				`id_fo_rate_plan` = '".$_REQUEST['bd_plan'][$bdfoodcount]."',
				`dated` = '".date('Y-m-d',strtotime($_REQUEST['bd_date'][$bdfoodcount]))."',
				`id_mst_room_types` = '".$_REQUEST['roomtype'][$i]."',
				`room_quantity` = '1',
				`adults_per_room` = '".$_REQUEST['adultperroom'][$i]."',
				`child_without_bed` = '".$_REQUEST['bd_extrachild'][$bdfoodcount]."',
				`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$bdfoodcount]."',
				`tariff_price_per_day_per_room` = '".$_REQUEST['bd_tariff'][$bdfoodcount]."',
				`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$bdfoodcount]."',
				`tax_per_day_per_room` = '".$_REQUEST['bd_tax'][$bdfoodcount]."',
				`unique_code` = '0'  ";
				$bdfoodcount++;
			mysqli_query($connNew,$roomdetails);
		}
				$l=$j;$l++;
				if($l == $noofrooms){$bdfoodcount_s = $bdfoodcount;}
	}
	
}*/	



				
$addons = sizeof($_REQUEST['item']);	
for($i=0;$i<$addons;$i++){
	 $addons_details = " INSERT INTO `".FO_RESERVATION_ADDONS_DETAILS."` SET
	
		`id_fo_reservations` = '".$fo_reservations_id."', 
		`id_shop` = '".$_SESSION['shop']."',
		`id_mst_hotels` = '".$id_hotel."',
		`id_mst_room_no_allocation` = '".$pid."',
		`item` = '".$_REQUEST['item'][$i]."',
		`additional_description` = '".$_REQUEST['additionalcharges'][$i]."',
		`qty` = '".$_REQUEST['qty'][$i]."',
		`unit` = '".$_REQUEST['unit'][$i]."',
		`rate` = '".$_REQUEST['rate'][$i]."',
		`tax_percent` = '".$_REQUEST['tax'][$i]."',
		`tax_value` = '".$_REQUEST['taxvalue'][$i]."',
		`amount` = '".$_REQUEST['amount'][$i]."' ";
		
	mysqli_query($connNew,$addons_details);
}			
	
$payment=sizeof($_REQUEST['mode']);
for($i=0;$i<$payment;$i++){
	  $payment_details = " INSERT INTO `".FO_RESERVATION_PAYMENT_DETAILS."` SET
	
		`id_fo_reservations` = '".$fo_reservations_id."', 
		`id_shop` = '".$_SESSION['shop']."',
		`id_mst_hotels` = '".$id_hotel."',
		`id_mst_room_no_allocation` = '".$pid."',
		`mode` = '".$_REQUEST['mode'][$i]."',
		`details` = '".$_REQUEST['details'][$i]."',
		`amount` = '".$_REQUEST['amount'][$i]."'  ";
	mysqli_query($connNew,$payment_details);
}				
		
		echo  'Booking Process created Successfully';
		}

/*



 
 
 
			
$sqll = "SELECT * FROM ".FO_RESERVATIONS." WHERE id_mst_hotels='$id_hotel' && id_mst_room_types='$resourceId' ";
$ress = mysqli_query($connNew,$sqll);
  while($row = mysqli_fetch_object($ress)){
					
		 $id_reser=$row->id;
					
			$sqla = "SELECT * FROM ".TBL_ROOM_ALLOCATION." WHERE id='$parentid' ";
			$resa = mysqli_query($connNew,$sqla);
			while($rowa = mysqli_fetch_object($resa)){
					 $idw=$rowa->id;
					// $idw1=$rowa->room_no;
					
echo $itemDetailSizeOf =	sizeof($_REQUEST['roomtype']);					
for($i=0;$i<$itemDetailSizeOf;$i++){

}							
			   
			   $insertGrid = "INSERT INTO ".FO_RESERVATIONS_DETAILS."  SET
								`id_fo_reservations`='".$id_reser."',
								`id_mst_room_allocation`='".$idw."' ";
							  // mysqli_query($connNew,$insertGrid); 
			}
  }	









				   
$sqla = "SELECT * FROM ".TBL_ROOM_ALLOCATION." WHERE id_mst_hotels='$id_hotel' && id_mst_room_types='$resourceId'";
$resa = mysqli_query($connNew,$sqla);
while($rowa = mysqli_fetch_object($resa)){
					
		 $idw=$rowa->id;
   
 echo  $insertGrid = "INSERT INTO ".FO_RESERVATIONS_DETAILS."  SET
 
					`id_mst_room_allocation`='".$idw."' ";
					
	         	   mysqli_query($connNew,$insertGrid); 
}

(resourceId='$resourceId' && check_In <= '$checkIn' && check_Out >= '$checkIn') ||
(resourceId='$resourceId' && check_In <= '$checkOut' && check_Out >= '$checkOut') ||
(resourceId='$resourceId' &&check_In >= '$checkIn' && check_Out <= '$checkOut')



$val = date('Y-m-d', strtotime( $checkOut . " - 1 days"));
$sql = "SELECT * FROM ".FO_RESERVATIONS." WHERE resourceId='$resourceId' && check_In <= '$checkIn' && check_Out >= '$checkIn' ";
$res = mysqli_query($connNew,$sql);
$rowcount=mysqli_num_rows($res);
	
				
				if($rowcount){
					echo "Already Reserved";							
				   
				}else{
					$insertGrid = "INSERT INTO ".FO_RESERVATIONS."  SET
					
					`guest_Name`='".$guestName."',
					`check_In`='".$checkIn."',
					`check_Out`='".$checkOut."',
					`resourceId`='".$resourceId."'	";
					
	         	   mysqli_query($connNew,$insertGrid);
				   
				   echo "Reserved Successfully";
				} 
				
*/

?>



