<?php 

 $query 			  =	mysqli_query($connNew ,"select * From ".FO_RESERVATIONS." Where other_reference='".$other_reference."'");
 $appNumberOfRows	=	mysqli_num_rows($query);
 $y=1;
if($appNumberOfRows>'0'){
	if(strtotime($checkin)>=strtotime(date('Y-m-d'))){
	//if($y=='1'){
	$BookingRecordrow=	mysqli_fetch_object($query);																										
		$SqlCheckStatusDetails = mysqli_query($connNew,"SELECT checkin_status FROM fo_reservations_details WHERE id_fo_reservations='".$BookingRecordrow->id."' order by checkin_status desc ");
		$NumRowCheckStatus	=	mysqli_num_rows($SqlCheckStatusDetails); 
		$resRoomCheckStatusDetails= mysqli_fetch_object($SqlCheckStatusDetails);
			
//Get API Details//
	if($resRoomCheckStatusDetails->checkin_status =='0' ||  $NumRowCheckStatus=='0'){	
//Get API Details//
	
		  
	  $bookingThrough	 =$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingThrough'];
	  $Segment			=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['Segment'];
	  $amendmentIn		=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['amendmentIn'];
	  $bookingSource	  =$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingSource'];
	  $bookingStatus	  		=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['bookingStatus'];
	  
	  $paymentStatus	  	  =$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['paymentStatus'];
	  $specialInstructions	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['specialInstructions'];
	 // $billingInstructions	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['billingInstructions'];
	 // $internal_remarks	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['HotelRemarks'];
	  $booking_status		=	selectColumn('fo_booking_status','id'," WHERE name='".addslashes($bookingStatus)."'");
	  
		
		
	$billingInstructions = !empty($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['billingInstructions']) 
    ? $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['billingInstructions'] 
    : "";	
		
	$internal_remarks = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['HotelRemarks'];
if (is_array($internal_remarks)) {
    $internal_remarks = $internal_remarks[0] ?? ''; // Access first element or set as empty if not present
}
$internal_remarks= empty($internal_remarks) ? "" : $internal_remarks;	
	
		
		$specialInstructions = $xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['MiscellaneousInfo']['specialInstructions'];
if (is_array($specialInstructions)) {
    $specialInstructions = $specialInstructions[0] ?? ''; // Access first element or set as empty if not present
}
$specialInstructions= empty($specialInstructions) ? "" : $specialInstructions;
		
		
	 // $booking_status		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'bookingstatus' and field_value='".addslashes($bookingStatus)."'");
  
	  
	  
$res_bookingthrough		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'booking_through' and field_value='".addslashes($bookingThrough)."'");
$res_segment		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'segment' and  status='1' and field_value='".addslashes($Segment)."'");
$id_mst_attributes_amendment		=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'amendment_in' and status='1' and field_value='".addslashes($amendmentIn)."'");

$res_bookingsourcee =	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'booking_source' and status='1' and field_value='".addslashes($bookingSource)."'");
	
	
	$pay_sts=	selectColumn(TBL_ATTRIBUTES,'id'," WHERE `table_name` = 'payment_status' and status='1' and field_value='".addslashes($paymentStatus)."'");

/*$paymentStatus		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE id_lang='1' AND status='1' and id_order_state='".addslashes($row->payment_status)."'");
$bookingThrough		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->booking_hrough)."'");

$Segment		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->segment_id)."'");
$amendmentIn		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and am_id='".addslashes($row->amendment_remarks_id)."'");
$bookingSource		=	selectColumn(TBL_ATTRIBUTES,'name'," WHERE status='1' and id='".addslashes($row->id_booking_source)."'");
*/		
				$sql = "UPDATE ".FO_RESERVATIONS."  SET
			
			
			`sub_total`='".$subtotal."',
			`net_booking_amount`='".$net_booking_amount."',
			`booking_confirm_date`='".$confirm."',
			`tentative_hold_date`='".$bk_stsa."',
			
			`id_mst_attributes_cancellation`='".$res_cancellation."',
			`id_mst_attributes_amendment`='".$id_mst_attributes_amendment."',
			`no_of_days`='".$no_of_days."',
			`id_mst_company_contacts`='".$id_company_contacts."',
			`id_mst_company`='".$id_mst_company."',
			`id_mst_attributes_payment_status`='".$pay_sts."',
			`booking_status`='".$booking_status."',
			`room_tariff_price`='".$totalprice."',
			`discount`='".$dis."',
			`total_addon_price`='".$total_addon_amount."',
			`total_tax`='".$total_tax."',
			`amount_received`='".$amount_received."',
			`balance`='".$net_booking_amount."',
			`booking_date`='".$date_created."',
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
			`res_internal_remarks`='".$internal_remarks."',
			`id_mst_attributes_segments`='".$res_segment."',
			`id_mst_attributes_booking_source`='".$res_bookingsourcee."',
			`id_mst_attributes_booking_through`='".$res_bookingthrough."',						
			`food_plan_price`='".$total_food_price."',
			`extra_bed_price`='".$total_extrabed_price."',
			`total_adults`='".$total_adults."',
			`total_child_with_bed`='".$total_extrabed_price."',
			`total_child_without_bed`='".$total_child_without_bed."',
				
			
			`last_modified` = '".currenDateTime()."',
			`last_modified_by` = '".$last_modified_by."' 
			where `other_reference`='".$other_reference."'";
				//	echo $insertGrid;die;
					
			//`reference`='".$ReferenceID."',
			
				
				
				//echo $sql; die;
			

if (mysqli_query($connNew, $sql)) {
	//$rese_id = mysqli_insert_id($connNew);
	$sqlPri = "SELECT id,id_mst_guest FROM fo_reservations WHERE other_reference='$other_reference'";
	$resultPri = mysqli_query($connNew, $sqlPri);



	$resPri = mysqli_fetch_object($resultPri);
		$rese_id= $resPri->id;
	$id_mst_guest=$resPri->id_mst_guest;
		$guest_id=$resPri->id_mst_guest;
	$updateInventorys = executeSql("UPDATE  `mst_guest`  SET 
								`id_mst_attributes_title`	='".($GuestTitle)."',
								`first_name`	='".($first_name)."',
								`last_name`	='".($last_name)."',
								`email`	='".($email)."'
								
								where 
								`id`			='".$guest_id."'");
		
  }
	$unique_codes = 'xml_'.rand();
		
	 
		
	executeSql("DELETE from fo_reservations_details where id_fo_reservations='".$rese_id."' ");
	executeSql("DELETE from `".FO_RESERVATION_ADDONS_DETAILS."` where `id_fo_reservations` = '".$rese_id."' ");
	
	$kkk	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay']);

	if($kkk	==1){
		
		$RoomStayCount=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		$RoomStayCount1=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];
		}else{
				$RoomStayCount	=	array();
				$RoomStayCount[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
				$RoomStayCount1[]	=	$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'];	
			}
			
							
		
			
			

foreach($RoomStayCount as $stay=>$k){
	
	 $TotalRoomS['NumberOfUnits']	+=	$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['NumberOfUnits'];
	 $TotalRoomS['RatePlanCode']	=	$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
	 $TotalRoomS['RoomTypeCode'] 	=   $RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['RoomTypeCode'];
	 
	 $SetTypeCode					=	 $TotalRoomS['RoomTypeCode'];	 
	 $SetTypeCodeNew[$SetTypeCode]['NumberOfUnits']	+=$RoomStayCount[$stay]['RoomRates']['RoomRate']['@attributes']['NumberOfUnits'];
}




//print_r($RoomStayCount);
	
	//echo $y=1;die;
	foreach($RoomStayCount as $counts=>$datarooms){
	
	
	//print_r($datarooms['RoomRates']['RoomRate']['Rates']);
	
	
	$roomRateArrayCheck	=	array_key_exists('0',$datarooms['RoomRates']['RoomRate']['Rates']['Rate']);
	if($roomRateArrayCheck==1){
	
	$NewData	=	$datarooms['RoomRates']['RoomRate']['Rates']['Rate'][0]['Base'];
		 $adult			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
		 $RatePlanCode = $datarooms['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
		
		
			$subtotal1 = $NewData['@attributes']['AmountBeforeTax'];
			 $total_tax1 = $NewData['Taxes']['@attributes']['Amount'];
			$EffectiveDate = $NewData['@attributes']['EffectiveDate'];
		
	}else{
		
		$NewData = $datarooms['RoomRates']['RoomRate']['Rates']['Rate']['Base'];
	
		 $adult			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
		 $RatePlanCode = $datarooms['RoomRates']['RoomRate']['@attributes']['RatePlanCode'];
		
		
			$subtotal1 = $NewData['@attributes']['AmountBeforeTax'];
			 $total_tax1 = $NewData['Taxes']['@attributes']['Amount'];
			$EffectiveDate = $NewData['@attributes']['EffectiveDate'];
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	 $RoomStayCount[$counts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	 $RatePLanInclusive	= $datarooms['RatePlans']['RatePlan']['RatePlanInclusions']['@attributes']['TaxInclusive'];

	//foreach($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['RoomStays']['RoomStay'] as $counts=>$datarooms){
		
		
		$IsArray	=	array_key_exists('0',$datarooms['RoomRates']['RoomRate']);

		if($IsArray	==1){
		
		$RoomRateArray=$datarooms['RoomRates']['RoomRate'];
		
		}else{
				$RoomRateArray	=	array();
				$RoomRateArray[]=$datarooms['RoomRates']['RoomRate'];
			}
	foreach($RoomRateArray as $RoomRatecounts=>$RoomRatedata){
		
	//	echo $totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
			
	//debugData($RoomRateArray);
	
	$checkout='';$checkin='';
	
	
		
	
	if($RatePLanInclusive=='true'){
	$totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	
	$NewpriceValue	 =	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountAfterTax'];
	}else{
	$totalpriceRoom	=	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];
	$NewpriceValue	 =	$RoomRateArray[$RoomRatecounts]['Rates']['Rate']['Base']['@attributes']['AmountBeforeTax'];
		}
	
	$roomCodeRoom	  =    $RoomRateArray[$RoomRatecounts]['@attributes']['RoomTypeCode'];
	
	//echo '=='.$checkin		   =	$RoomStayCount['TimeSpan']['@attributes']['Start'];
	//$checkout		  =	date('Y-m-d',strtotime('+1 day',strtotime($RoomStayCount[$RoomRatecounts]['TimeSpan']['@attributes']['End'])));
	
	$checkout=	date('Y-m-d',	strtotime($multiRoom[0]['TimeSpan']['@attributes']['End']));
	$checkin=$multiRoom[0]['TimeSpan']['@attributes']['Start'];
	
	$roomTypeRoom	  =	$xmlarray['reservations']['reservation']['0']['room'][$counts]['name'];
	

	$adults			= 	$RoomRateArray[$RoomRatecounts]['@attributes']['Adults'];
	$child 			 = 	$xmlarray['reservations']['reservation']['0']['room'][$RoomRatecounts]['numberofchild'];
	
	$RatePlansMappingID	=$RoomRateArray[$RoomRatecounts]['@attributes']['MealPlan'];
	
	//$RoomStayCount[$counts]['Total']['@attributes']['AmountBeforeTax'];
	$RoomType_NumberOfUnits=$RoomRateArray[$RoomRatecounts]['@attributes']['NumberOfUnits'];
 	$dated 			 = $checkin;
	
	
	
	//$noDaysin=  count($xmlarray['reservations']['reservation']['0']['room'][$counts]['price']);
	
	$daysNew 		   =  abs((strtotime($checkin) - strtotime($checkout))/ 86400 );
		if($daysNew == '0'){
			$noDaysin = '1';
		}else {
			$noDaysin = $daysNew;
		}
	 $NewpriceValue	=	$NewpriceValue;////round($NewpriceValue/$noDaysin);
	

	$SqlCheckOrderBY1 = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."'    order by id desc ");
		$NumRowOrderBY1	=	mysqli_num_rows($SqlCheckOrderBY1); 
		$resRoomOrderBY1= mysqli_fetch_object($SqlCheckOrderBY1);
		if($NumRowOrderBY1	>0){ 
			$order_by_room=$resRoomOrderBY1->order_by_room;
			$order_by_room++;
			$Set=1;
			$Setorder_by_room =$order_by_room;
			
		}
		
		
	foreach($multiRoom as $countstart=>$datarooms){
	
		
while(strtotime($dated)!=strtotime($checkout)){ 
				
		//echo '<br>'.date('Y-m-d',strtotime($dated));
		
		$SQLRateId = mysqli_query($connNew,"SELECT * FROM fs_rate_mapping WHERE booking_engine_id ='".addslashes($RatePlansMappingID)."' and channel_id='".$channelId."'");
		$FetchArrayRateId 	 = mysqli_fetch_object($SQLRateId);
		$Room_rate_plan_id	= addslashes($FetchArrayRateId->rate_id);

		if($Room_rate_plan_id=='0' || $Room_rate_plan_id==''){
			$Error .= 'Rate mapping - '.$RatePlansMappingID.' Not Found<Br>';
		}
		
		
				
		$SelectTaxDateSQL	=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($id_shop)."'  order by start_date desc");
		$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
			//$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
			
			$resNewTaxInclution= executeSql("SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($id_shop)."' AND ((tax_slabs_from <=  '".$NewpriceValue."' and tax_slabs_to  >= '".$NewpriceValue."') OR ( tax_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");

			if(num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				//$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
				//$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
				//$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				
				$resRatePlanDetail 		= selectSql('fs_rate_plan',"where id='".addslashes($Room_rate_plan_id)."' ",' ORDER BY `name`'); 
				$resRateDetail = mysqli_fetch_object($resRatePlanDetail);
				$tax_detail=$resRateDetail->tax_detail;
				
				     $tax_new_percent	=$rowNewTaxInclution->tax_percent;
					 $totalpriceRoom=round($NewpriceValue);
					$taxparsentamount=round(($NewpriceValue*$tax_new_percent)/100);
					$tax_perday_perroom= $taxparsentamount;
				/*if($tax_detail=='2'){ 
					$tax_new_percent	=$rowNewTaxInclution->tax_percent;
					 $totalpriceRoom=round($NewpriceValue);
					$taxparsentamount=round(($NewpriceValue*$tax_new_percent)/100);
					$tax_perday_perroom= $taxparsentamount;
				//}else{*/
				
					//$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
					//$totalpriceRoom=round($NewpriceValue/$tax_new_percent);
					//$tax_perday_perroom= $NewpriceValue-$totalpriceRoom;
				//}
				$totalpriceRoom=round($totalpriceRoom*$RoomType_NumberOfUnits,2);
				$SubTotalAssignDetail	+=($totalpriceRoom);
			    $SubTotalTax	+=round($tax_perday_perroom*$RoomType_NumberOfUnits,2);
			}
			
						
	
$queryRoomId = mysqli_query($connNew,"SELECT * FROM fs_room_mapping WHERE hotel_mapping_id ='".$resChannel->id."' and booking_engine_id='".$roomCodeRoom."'");
$resRoomId = mysqli_fetch_object($queryRoomId);

		$id_mst_room_types=$resRoomId->room_id;
		$room_quantity = 1;
		
		if($id_mst_room_types=='0' || $id_mst_room_types==''){
			$Error .= 'Room Type - '.$roomCodeRoom.' Not Found<Br>';
		}
		
		$rate_id=0;
		

	$hotel_idMapping 		=  $multiRoom[0]['BasicPropertyInfo']['@attributes']['HotelCode'];
	$checkout=	date('Y-m-d',	strtotime($multiRoom[0]['TimeSpan']['@attributes']['End']));
	$checkin=$multiRoom[0]['TimeSpan']['@attributes']['Start'];
	$adults[]			= 	$datarooms['RoomRates']['RoomRate']['@attributes']['Adult'];
	
	
		
		
		
		/*$sql = "SELECT * FROM fo_rate_plan WHERE name='$RatePlanCode'";
		$result = mysqli_query($connNew, $sql);
		
		if (mysqli_num_rows($result) > 0) {
		  // output data of each row
		  while($row = mysqli_fetch_assoc($result)) {
				   $idrate_plan = $row["id"];
				  
		  }
		} 
		else{*/
			/*echo '<?xml version="1.0" encoding="UTF-8"?>
			<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns="http://www.opentravel.org/OTA/2003/05" hotel_name="'.$hotel_name.'"  Version="1.0">
			<Success>"'.$RatePlanCode.'" not exist</Success>
			</OTA_HotelResNotifRS>';
			//exit;
			$Error .= 'Rate Plan Code '.$RatePlanCode.' Not Found<Br>';
		}*/

	$id_fo_reservations = $rese_id;
	$id_mst_hotels = $hotel_id;
	$id_mst_guest=$guest_id;
	$plan ="1";
	$id_rate="0";
	$id_fo_rate_plan=$idrate_plan;
	//$dated=$EffectiveDate;
	$room_quantity="1";
	$adults_per_room=$adult;
	$tariff_price_per_day_per_room=$subtotal1;
	$tax_per_day_per_room=$total_tax1;
	$unique_code=$unique_codes;
	$checkin_status="0";
	$date_created = date('Y-m-d');


	
	
	
	
		$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."' AND `id_mst_room_types`='".$id_mst_room_types."' AND  `dated`='".addslashes(date("Y-m-d",strtotime($dated)))."' AND `room_quantity`='".$RoomType_NumberOfUnits."' AND `adults_per_room`='".$adults_per_room."' and `tariff_price_per_day_per_room`='".$subtotal1."' ");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
		
		if($Set==''){ $order_by_room='1';
		/*$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."' AND `id_mst_room_types`='".$id_mst_room_types."' AND  `dated`='".addslashes(date("Y-m-d",strtotime($dated)))."' AND `room_quantity`='".$RoomType_NumberOfUnits."' AND `adults_per_room`='".$adults_per_room."'");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
		//Count Inc===============
		$SqlCheckOrderBY = mysqli_query($connNew,"SELECT * FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' AND `id_mst_hotels`='".$id_mst_hotels."' AND `id_mst_guest`='".$id_mst_guest."'  order by id desc ");
		$NumRowOrderBY	=	mysqli_num_rows($SqlCheckOrderBY); 
		$resRoomOrderBY= mysqli_fetch_object($SqlCheckOrderBY);
		if($NumRowOrderBY	==0){ 
			$order_by_room='1';$order_by_room;
		}else{
			
		$order_by_room='1';
		
			echo '===Step2'.$RoomType_NumberOfUnits.'==='.$order_by_room;
			
			}*/
	}
	
	
		//if($NumRow	==0){
			
			//$order_by_room='0';
			for($r=1;$r<=$RoomType_NumberOfUnits;$r++){
				
		 $Insert_into_Order_Details= "INSERT INTO fo_reservations_details (id_fo_reservations,id_mst_hotels,id_mst_guest,id_mst_room_types,plan,id_rate,id_fo_rate_plan,dated,room_quantity,adults_per_room,tariff_price_per_day_per_room,tax_per_day_per_room,unique_code,checkin_status,id_shop,order_by_room,tax_percent) 
	Values('$id_fo_reservations','$id_mst_hotels','$id_mst_guest',$id_mst_room_types,'$Room_rate_plan_id','$id_rate','$Room_rate_plan_id','$dated','$RoomType_NumberOfUnits','$adults_per_room','$subtotal1','$tax_per_day_per_room','$unique_code','$checkin_status','".addslashes($id_shop)."','$order_by_room','$tax_new_percent')";
	//exit;
	 	$order_by_room++;
	$Insert_into_Order_Details_Run = executeSql($Insert_into_Order_Details);
		
			}
//FO_INVENTORY =======================	
		 $sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$id_mst_room_types."' and allocation_date='".date('Y-m-d',strtotime($dated))."' and id_mst_hotels = '".$id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						while($rownew = mysqli_fetch_object($resnew)){ 
							$crs_available = $rownew ->crs_available - $RoomType_NumberOfUnits ; 
							$confirmed = $rownew->confirmed + $RoomType_NumberOfUnits ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$id_mst_room_types."' and allocation_date='".date('Y-m-d',strtotime($dated))."' and id_mst_hotels = '".$id_mst_hotels."'";
						//echo '<br>'.$insertGrid;
						mysqli_query($connNew,$insertGrid);	
							
						}
			//FO_INVENTORY =======================	
		//}

	
		$dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));
											   
		if($Set=='1'){
			$order_by_room = $Setorder_by_room;
		}									   
		} 
	}
}
	
}
	
	
	//Other Charges Start=================================================================
$OtherChargesArray	=	array_key_exists('0',$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge']);
if($OtherChargesArray==1){
	$OtherChargesArrayList	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge'];
	}else{
	$OtherChargesArrayList[]	=$xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']['OtherCharge'];
	}
	
	if($xmlarray['Body']['OTA_HotelResNotifRQ']['HotelReservations']['HotelReservation']['OtherCharges']!=''){
	$total_addon_price='';
foreach($OtherChargesArrayList as $otherInc=>$OtherChargesArrayValue){
		
		
 $addons_details = " INSERT INTO `".FO_RESERVATION_ADDONS_DETAILS."` SET
	
		`id_fo_reservations` = '".$id_fo_reservations."', 
		`id_shop` = '".$id_shop."',
		`id_mst_hotels` = '".$id_mst_hotels."',
		`id_mst_room_no_allocation` = '".$pid."',
		`item` = '".$OtherChargesArrayValue['chargesitem']."',
		`additional_description` = '".$OtherChargesArrayValue['chargesDescription']."',
		`qty` = '".$OtherChargesArrayValue['chargesNumberCount']."',
		`unit` = '".$OtherChargesArrayValue['chargesNumberOfDays']."',
		`rate` = '".$OtherChargesArrayValue['chargesPrice']."',
		`tax_percent` = '".$OtherChargesArrayValue['chargesTaxPercentage']."',
		`tax_value` = '".$OtherChargesArrayValue['chargesTaxTotal']."',
		`amount` = '".$OtherChargesArrayValue['chargesNetAmount']."' ";
		
		$total_addon_price +=$OtherChargesArrayValue['chargesNetAmount'];
		$total_addon_tax +=$OtherChargesArrayValue['tax_value'];
		
		
	mysqli_query($connNew,$addons_details);


}
}else{
		$total_addon_price = '';
		$total_addon_tax ='';
		
	
	}
 $TotalAddonAmount	=	$total_addon_price;
 
		$SqlCheckOrderDetails = mysqli_query($connNew,"SELECT sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room,sum(tax_per_day_per_room) as tax_per_day_per_room FROM fo_reservations_details WHERE id_fo_reservations='".$id_fo_reservations."' ");
		$NumRow	=	mysqli_num_rows($SqlCheckOrderDetails); 
		$resRoomOrderDetails= mysqli_fetch_object($SqlCheckOrderDetails);
$balance	=$resRoomOrderDetails->tariff_price_per_day_per_room;
$total_tax	=$resRoomOrderDetails->tax_per_day_per_room;
$updateInventory = executeSql("UPDATE  `".FO_RESERVATIONS."`  SET 
								`total_addon_price`	='".($total_addon_price)."',								
								`total_addon_tax`	='".($total_addon_tax)."',
								`balance`	='".($balance+$total_tax+$TotalAddonAmount)."',
								`total_tax`	='".($total_tax+$total_addon_tax)."',
								`net_booking_amount`	='".($balance+$total_tax+$TotalAddonAmount)."'
								where 
								`id`			='".$id_fo_reservations."'");
		
		

	$Booking_no = $BookingRecordrow->booking_no;
updateOTA('1', $checkin,$checkout,$connNew);
$data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $BookingRecordrow->id,
			'PmsBooking_no' => $Booking_no,
			'Error' => $Error ?? '',
    		'status' =>'Modify success',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
         echo $json;
		 executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='success',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='modify");
	
		  
	exit;
}else{	/*echo '<?xml version="1.0" encoding="UTF-8"?>
			<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns="http://www.opentravel.org/OTA/2003/05" hotel_name="'.$hotel_name.'"  Version="1.0">
			<Success>Checkin Not Greater than Todays Date</Success>
			</OTA_HotelResNotifRS>';*/
			//exit;
		  
		  $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
			'Error' => $Error ?? 'Because the check-in has already passed, CRS modifications will not update in the PMS.',
    		'status' =>'CRS modifications will not update in the PMS',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
         echo $json;
		 executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='Failed',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='modify");
	
		  
}


//Other Charges Start=================================================================
	
	}else{	/*echo '<?xml version="1.0" encoding="UTF-8"?>
			<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xmlns:xsd="http://www.w3.org/2001/XMLSchema"
			xmlns="http://www.opentravel.org/OTA/2003/05" hotel_name="'.$hotel_name.'"  Version="1.0">
			<Success>Checkin Not Greater than Todays Date</Success>
			</OTA_HotelResNotifRS>';*/
			//exit;
		  
		  $data = [
			'EchoToken' => $ReferenceID,
			'CrsResID_Value' => $otherRefrenceId,
			'PmsResID_Value' => $rese_id,
			'Error' => $Error ?? 'Checkin Not Greater than Todays Date',
    		'status' =>'Checkin Not Greater than Todays Date',

			];
	
		$json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
         echo $json;
		 executeSql("Insert into api_request set channel_id = '1' , request='".$postData."',date_created='".date('Y-m-d H:i:s')."',company_name='".$CompanyName."',booking_referance_id='0',response_status='Failed',id_pms_response='".$otherRefrenceId."',failed_at='".date('Y-m-d H:i:s')."',booking_type='modify");
	
		  
}
	}
	

?>