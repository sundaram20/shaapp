<?php
include_once("../../config/auto_loader.php");

 $resourceId=$_REQUEST['res_room'];
 
  $bk_sts=$_REQUEST['res_bookingStatus'];
  $parentid=$_REQUEST['parentId'];
  $bk_no="w310";
  $bk_date=$_REQUEST['res_bookingDate'];
  $bk_sts=$_REQUEST['res_bookingStatus'];
  
  $bk_stsa=$_REQUEST['res_holdTillDate'];
  $bk_stsb=$_REQUEST['res_cancellation'];
  
  $hotel_name=$_REQUEST['res_hotelName'];
  $check_in=$_REQUEST['res_checkinDate'];
  $check_out=$_REQUEST['res_checkOutDate'];
  $guest_name=$_REQUEST['res_guestName'];
  $bk_type=$_REQUEST['res_bookingType'];
  $source=$_REQUEST['res_source'];
  $bk_name=$_REQUEST['res_bookerName'];
  $rate_type=$_REQUEST['res_rateType'];
  $rate_letter=$_REQUEST['res_rateLetter'];
  $room_type=$_REQUEST['roomtype'];
  $plan=$_REQUEST['plan'];
  $no_room=$_REQUEST['noofRooms'];
  $adult=$_REQUEST['adultperperson'];
  $child=$_REQUEST['childperperson'];
  $ex_child=$_REQUEST['extrachild'];
  $tariff=$_REQUEST['tariffperperson'];
  $room_tax=$_REQUEST['taxes'];
  $charge=$_REQUEST['chargespernight'];
  
  $item=$_REQUEST['item'];
  $des=$_REQUEST['additionalcharges'];
  $qty=$_REQUEST['qty'];
  $unit=$_REQUEST['unit'];
  $rate=$_REQUEST['rate'];
  $tax=$_REQUEST['tax'];
  $tax_value=$_REQUEST['taxvalue'];
  $amount=$_REQUEST['amount'];
  
  $tar_amt="5000";
  $add_amt="5000";
  $dis=$_REQUEST['res_discount'];
  $sum_tax="5000";
  $ad_ricve=$_REQUEST['res_advance'];
  $ref=$_REQUEST['res_reference'];
  $bal="500";
  $pay_sts=$_REQUEST['res_paymentStatus'];
  $bill_to=$_REQUEST['res_billto'];
  $remarks=$_REQUEST['res_remarks'];
  $pickup=$_REQUEST['res_pickuprequired'];
  
  $pickupa=$_REQUEST['res_modeoftravel'];
  $pickupb=$_REQUEST['res_pickupdetails'];
  $pickupc=$_REQUEST['res_arrivingfrom'];
  $pickupd=$_REQUEST['res_departingto'];
  $spe_rqt=$_REQUEST['res_specialrequest'];
  $id_hotel=$_REQUEST['id_mst_hotels'];
  
  $checkIn_test= date('Y-m-d', strtotime( $check_in . " + 1 days"));
  $checkOut_test= date('Y-m-d', strtotime( $check_out . " - 1 days"));
  
  $startTimeStamp = strtotime($check_in);
  $endTimeStamp = strtotime($checkOut_test);
  $timeDiff = abs($endTimeStamp - $startTimeStamp);
  $numberDays = $timeDiff/86400; 
  $numberDays = intval($numberDays);
  $numberDays = $numberDays + 1;
	 
					
					
 
//$val = date('Y-m-d', strtotime( $checkOut . " - 1 days"));




$sql = "SELECT * FROM ".FO_RESERVATIONS." WHERE id_mst_room_types='$resourceId' && check_in <= '$checkOut_test' && check_out >= '$checkIn_test' && parentId = '$parentid' ";
$res = mysqli_query($connNew,$sql);
$rowcount=mysqli_num_rows($res);
				
				if($rowcount){
					echo "Already Reserved";	
				}else{
				 $insertGrid = "INSERT INTO ".FO_RESERVATIONS."  SET
 
					`id_mst_room_types`='".$resourceId."',
					`parentId`='".$parentid."',
					`booking_no`='".$bk_no."',
					`booking_date`='".$bk_date."',
					`booking_status`='".$bk_sts."',
					`hold_till_date`='".$bk_stsa."',
					`cancellation_reason`='".$bk_stsb."',
					`hotel_name`='".$hotel_name."',
					`check_in`='".$check_in."',
					`check_out`='".$check_out."',
					`guest_name`='".$guest_name."',
					`booking_type`='".$bk_type."',
					`source`='".$source."',
					`booker_name`='".$bk_name."',					
					`rate_type`='".$rate_type."',
					`rate_letter`='".$rate_letter."',
					`room_type`='".$room_type."',
					`plan`='".$plan."',
					`no_of_room`='".$no_room."',
					`adult`='".$adult."',
					`child`='".$child."',
					`extra_child`='".$ex_child."',
					`tariff_per_room`='".$tariff."',
					`room_taxes`='".$room_tax."',
					`charges`='".$charge."',
					
					`item`='".$item."',
					`additional_description`='".$des."',
					`qty`='".$qty."',
					`unit`='".$unit."',
					`rate`='".$rate."',
					`tax`='".$tax."',
					`tax_value`='".$tax_value."',
					`amount`='".$amount."',
					
					`tariff_amount`='".$tar_amt."',
					`add_on_amount`='".$add_amt."',
					`discount`='".$dis."',
					`taxes`='".$sum_tax."',
					`advance_received`='".$ad_ricve."',
					`reference`='".$ref."',
					`balance`='".$bal."',
					`payment_status`='".$pay_sts."',
					`bill_to`='".$bill_to."',
					`remarks`='".$remarks."',
					`pickup_required`='".$pickup."',
					`model_of_travel`='".$pickupa."',
					`pickup_details`='".$pickupb."',
					`arriving_form`='".$pickupc."',
					`departing_to`='".$pickupd."',
					`special_request`='".$spe_rqt."',
					`id_mst_hotels`='".$id_hotel."' ";
					
	         	   mysqli_query($connNew,$insertGrid);
				   
				/*   for($i=0;$i<$numberDays;$i++){	
						$sqla = "SELECT * FROM ".FS_INVENTORY." WHERE id_mst_room_types='$resourceId' and allocation_date='$check_in' and id_mst_hotels = '$id_hotel' ";
						$resnew = mysqli_query($connNew,$sqla);
						while($rownew = mysqli_fetch_object($resnew)){ 
							$crs_available = $rownew ->crs_available - 1 ; 
							$confirmed = $rownew->confirmed + 1 ; 
							
							$insertGrid = "UPDATE ".FS_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='$resourceId' and allocation_date='$check_in' and id_mst_hotels = '$id_hotel'";			 
							mysqli_query($connNew,$insertGrid);	
												
							$check_in= date('Y-m-d', strtotime( $check_in . " + 1 days"));
						}
					} */
					
				   
				   echo "Reserved Successfully";
				} 
			
$sqll = "SELECT * FROM ".FO_RESERVATIONS." WHERE id_mst_hotels='$id_hotel' && id_mst_room_types='$resourceId' ";
$ress = mysqli_query($connNew,$sqll);
  while($row = mysqli_fetch_object($ress)){
					
		 $id_reser=$row->id;
					
			$sqla = "SELECT * FROM ".TBL_ROOM_ALLOCATION." WHERE id='$parentid' ";
			$resa = mysqli_query($connNew,$sqla);
			while($rowa = mysqli_fetch_object($resa)){
								
					 $idw=$rowa->id;
					// $idw1=$rowa->room_no;
			   
			   $insertGrid = "INSERT INTO ".FO_RESERVATIONS_DETAILS."  SET
								
								`id_fo_reservations`='".$id_reser."',
								`id_mst_room_allocation`='".$idw."' ";
								
							   mysqli_query($connNew,$insertGrid); 
			}
  }	

/*
				   
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



