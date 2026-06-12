<?php
include_once("../../config/auto_loader.php");

$id_fo_bill = $_REQUEST['id_fo_bill'];
$status = $_REQUEST['status'];
$id_reservation = $_REQUEST['id_reservation'];
$checkout_time = $_REQUEST['checkout_time'] ?? '';
$dataArray = array();

$sqlVa = "SELECT * FROM ".FO_BILL." where id='".$id_fo_bill."' and id_reservations='".$id_reservation."' and `doc_no`='0' and id_doc_type_configuration='0'";
$Vali =	mysqli_query($connNew,$sqlVa);
if (mysqli_num_rows($Vali) > 0) {
	$dataArray['status'] = '0';
	$dataArray['message'] = ' Please Generate FO Bill.';
	echo json_encode($dataArray);
	die;
} else {
	
		//Checkout force system date as checkout Date
			/*$force_system_date_as_checkout_date	=	selectColumn(TBL_SHOP,'force_system_date_as_checkout_date'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
			if($force_system_date_as_checkout_date=='1'){
				
			$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
			$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
			$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
			$rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
			$DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
			 $reservation_checkin =	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_reservation.'"')));
			//Same Day Checkout
			if (strtotime($reservation_checkin) == strtotime($DatedNightAudit) && $_REQUEST['precheckout'] == '1') {
				$_REQUEST['precheckout']=0;
				$dataArray['status']='1';
				 $dataArray['message']='Checkout not allowed for old date';
	 			echo json_encode($dataArray);
				die;
			}
	
			}*/
		//Checkout force system date as checkout Date
	
	
		$sqlCheckoutStatus = mysqli_query($connNew,"Select * From  ".FO_BILL."    WHERE id='".$id_fo_bill."' AND status='2' and id_reservations='".$id_reservation."'");
		if (mysqli_num_rows($sqlCheckoutStatus) >0 ) {
			$dataArray['status'] = '1';
			$dataArray['message'] = 'Checkout Already Processed';
			echo json_encode($dataArray);
			die;
		} else {
			$reservation_checkout =	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
			$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
			$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
			$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
			$rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
			$DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));

			if (strtotime($reservation_checkout) > strtotime($DatedNightAudit) && $_REQUEST['precheckout'] == '0') {
				$_REQUEST['precheckout']=0;
				$dataArray['status']='5';
				 $dataArray['message']='Do you want to Pre-Checkout ?';
	 			echo json_encode($dataArray);
				die;
			} else {
				$_REQUEST['precheckout'] = 1;
			}
			
			if ($_REQUEST['precheckout'] == '1') {
				
						$reservation_checkout	=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));						

						if (strtotime($reservation_checkout) < strtotime($DatedNightAudit)) {
							$dataArray['status']='0';
							 $dataArray['message']='you are in wrong day close date?';
							 echo json_encode($dataArray);
							die;
						}
						$DateArray	=	array();
						while(strtotime($DatedNightAudit)!=strtotime($reservation_checkout)){ 
						
						$check_status=	selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="0" and dated="'.date("Y-m-d",strtotime($DatedNightAudit)).'"');
							
							
							if($check_status=='0'){ //echo date("Y-m-d",strtotime($DatedNightAudit));;
								$DateArray[]=date("Y-m-d",strtotime($DatedNightAudit));
							}
								$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($DatedNightAudit)));	
						}
						$DateArray= "'".implode ( "','", $DateArray )."'";
						
						
				
				
				//================================================================================
					$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$id_reservation."' Group BY `fo_reservations_details`.`order_by_room`");
					if(mysqli_num_rows($sqlOrderDetail) >0 ){
					
					while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					
					
					
					$id_mst_room_no_allocationArray[]=$rowOrderDetail->id_mst_room_no_allocation;
					$roomNumberArray[]=$roomNo;
					}
					
					
					}
				$roomNumberArray	=implode(',',$roomNumberArray);
				$id_mst_room_no_allocationArray	=implode(',',$id_mst_room_no_allocationArray);
				//================================================================================
				 $updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='4'	where `id` IN (".$id_mst_room_no_allocationArray.") ";
				
				mysqli_query($connNew,$updateRoomstatus);
				// Get Dayclose
				$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
				$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
				$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
				$Dated = date('Y-m-d',strtotime($rowNightAudit->dated));
				$DatedNightAudit2 = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
				$DatedNightAudit1 = date('Y-m-d',strtotime($rowNightAudit->dated));
				 $DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
				$sql = "UPDATE ".FO_BILL." SET status='2' , `checkout_date`='".date($DatedNightAudit2.' H:i:s')."' WHERE id='".$id_fo_bill."' and id_reservations='".$id_reservation."'  ";

if(mysqli_query($connNew,$sql)){
	
	
	
	
	 $insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 	
	 			 `no_showoff`='1',				 			 
				  where
				  `id_fo_reservations` = '".$id_reservation."'				  
				   and  DATE(dated) IN (".stripslashes($DateArray).") ";
				//echo $insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGrid);


			if ($checkout_time != '') {
				$updateCheckoutTime = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkout_time`='".$checkout_time."' where `id_fo_reservations` = '".$id_reservation."'";
				$insertOrder = mysqli_query($connNew,$updateCheckoutTime);
			}
	
	
		//================================================================================
					$sqlOrderDetailOrderby = mysqli_query($connNew,"SELECT id FROM fo_reservations_details WHERE id_fo_reservations = '".$id_reservation."' AND checkin_status = '1' AND dated = ( SELECT MAX(dated) FROM fo_reservations_details WHERE id_fo_reservations = '".$id_reservation."'  AND checkin_status = '1' and `no_showoff`='0' )");
					if(mysqli_num_rows($sqlOrderDetailOrderby) >0 ){
					
					while($rowOrderDetailOrder= mysqli_fetch_object($sqlOrderDetailOrderby)){
					//$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					
					
					
					$idOfOrder[]=$rowOrderDetailOrder->id;
					//$roomNumberArray[]=$roomNo;
					}
					
					
					}
				$idOfOrder	=implode(',',$idOfOrder);
				//$id_mst_room_no_allocationArray	=implode(',',$id_mst_room_no_allocationArray);
				//================================================================================
	
	
	
	//$checkOutID=	selectColumn(FO_RESERVATIONS_DETAILS,'id','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" order by dated desc');
	$checkOutDated=	selectColumn(FO_RESERVATIONS_DETAILS,'dated','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" and no_showoff="0"  and checkout_status="0" order by dated desc');
	
	
	 $insertGrid2 =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
				
				`checkout_status`='1',
				`checkout_date` = '".date($checkOutDated.' H:i:s')."'
							 
				  where
				  
				  `id_fo_reservations` = '".$id_reservation."'	
				  and  id IN (".stripslashes($idOfOrder).") ";
				  
				  // and id='".$checkOutID."'			   
				  // ";
			
		mysqli_query($connNew,$insertGrid2);
		
		
		
					$reservationcheckout	=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));						
						$DateArrayDate	=	array();
						while(strtotime($DatedNightAudit)!=strtotime($reservationcheckout)){ 
						
						$check_status=	selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="0" and dated="'.date("Y-m-d",strtotime($DatedNightAudit)).'" ');
							
							
							if($check_status=='0'){ //echo date("Y-m-d",strtotime($DatedNightAudit));;
								$DateArrayDate[]=date("Y-m-d",strtotime($DatedNightAudit));
							}
								$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($DatedNightAudit)));	
						}
						$DateArrayDate= "'".implode ( "','", $DateArrayDate )."'";
		
		$insertGridDate =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 	
	 			 `no_showoff`='1'			 
				  where
				  `id_fo_reservations` = '".$id_reservation."'				  
				   and  DATE(dated) IN (".stripslashes($DateArrayDate).")  ";
				//echo $insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGridDate);
		
		
		
		
	
	$dataArray['status']='1';
	$dataArray['message']='Checkout updated sucessfully';
	$dataArray['checkoutdate']=date('d-m-Y',strtotime($DatedNightAudit2));
	 echo json_encode($dataArray);
}else{
	$dataArray['status']='0';
	$dataArray['message']='Please verify data';
	 echo json_encode($dataArray);
}
	 
		}
	 
	 
			

		}
}
 ?>


