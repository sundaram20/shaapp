<?php
include_once("../../config/auto_loader.php");

$id_fo_bill=$_REQUEST['id_fo_bill'];
$status=$_REQUEST['status'];
$id_reservation=encryptor(decrypt,$_REQUEST['id_reservation']);
$id_room=$_REQUEST['id_room'];
$checkout_time=$_REQUEST['checkout_time'] ?? '';
$dataArray=array();
 $sqlVa= "SELECT * FROM ".FO_BILL." where id='".$id_fo_bill."' and id_reservations='".$id_reservation."' and 
			`doc_no`='0' and id_doc_type_configuration='0'";
$Vali=	mysqli_query($connNew,$sqlVa);		
if(mysqli_num_rows($Vali)>0){ 
	$dataArray['status']='0';
	$dataArray['message']=' Please Generate FO Bill.';
	//$dataArray['value']=$id_reservation.'_'.$id_fo_bill.'_'.$_REQUEST['id_room'];
	 echo json_encode($dataArray);
	 die;
	
}else{
		$sqlCheckoutStatus = mysqli_query($connNew,"Select * From  ".FO_BILL."    WHERE id='".$id_fo_bill."' AND status='2' and id_reservations='".$id_reservation."'  ");
		if(mysqli_num_rows($sqlCheckoutStatus) >0 ){
			 //checkout Already Processed;
				$dataArray['status']='1';
				$dataArray['message']='Checkout Already Processed';
				echo json_encode($dataArray);
				die;
		}else{
			
			
			
			 $reservation_checkout	=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
			
					 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					  $rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
					  $DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					  
			if(strtotime($reservation_checkout)>strtotime($rowNightAuditDated) && $_REQUEST['precheckout']=='0'){
				$_REQUEST['precheckout']=0;
				$dataArray['status']='5';
				 $dataArray['message']='Do you want to Pre-Checkout ?';
	 			echo json_encode($dataArray);
				die;
			}else{
				$_REQUEST['precheckout']=1;
				}

			if($_REQUEST['precheckout']=='1'){
				
						$reservation_checkout	=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));						
						$DateArray	=	array();
						while(strtotime($DatedNightAudit)!=strtotime($reservation_checkout)){ 
						
						$check_status=	selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="0" and dated="'.date("Y-m-d",strtotime($DatedNightAudit)).'" and id_mst_room_no_allocation="'.$id_room.'"');
							
							
							if($check_status=='0'){ echo date("Y-m-d",strtotime($DatedNightAudit));;
								$DateArray[]=date("Y-m-d",strtotime($DatedNightAudit));
							}
								$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($DatedNightAudit)));	
						}
						$DateArray= "'".implode ( "','", $DateArray )."'";
						
						
				
				
				//================================================================================
					$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$id_reservation."'  and id_mst_room_no_allocation='".$id_room."' Group BY `fo_reservations_details`.`order_by_room`");
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
				$DatedNightAudit1 = date('Y-m-d',strtotime($rowNightAudit->dated));
				//$sql = "UPDATE ".FO_BILL." SET status='2' , `checkout_date`='".date($Dated.' H:i:s')."' WHERE id='".$id_fo_bill."' and id_reservations='".$id_reservation."'  ";

//if(mysqli_query($connNew,$sql)){
	
	
	
	
	 $insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 	
	 			 `no_showoff`='1'			 
				  where
				  `id_fo_reservations` = '".$id_reservation."'				  
				   and  DATE(dated) IN (".stripslashes($DateArray).") and id_mst_room_no_allocation='".$id_room."' ";
				//echo $insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGrid);
	
	
		
	
			if ($checkout_time != '') {
				$updateCheckoutTime = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkout_time`='".$checkout_time."', `room_availability`='Checkout' where`id_fo_reservations` = '".$id_reservation."' and id_mst_room_no_allocation='".$id_room."'";
				$insertOrder = mysqli_query($connNew,$updateCheckoutTime);
			}
		
		
	$checkOutID=	selectColumn(FO_RESERVATIONS_DETAILS,'id','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" and id_mst_room_no_allocation="'.$id_room.'" order by dated desc');
	$checkOutDated=	selectColumn(FO_RESERVATIONS_DETAILS,'dated','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" and id_mst_room_no_allocation="'.$id_room.'" order by dated desc');
	
	
	 $insertGrid2 =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
				
				`checkout_status`='1',
				`checkout_date` = '".date($checkOutDated.' H:i:s')."'
							 
				  where
				  
				  `id_fo_reservations` = '".$id_reservation."'	
				   and id='".$checkOutID."' and id_mst_room_no_allocation='".$id_room."'			   
				   ";
			
		mysqli_query($connNew,$insertGrid2);
			
			
			
			
			
			
			
			
			
			
			
			
			
		$sqlOrderDetailCheckInStatus = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$id_reservation."'  and `no_showoff`='0' and checkin_status='1' and `checkout_status`='0'");
					if(mysqli_num_rows($sqlOrderDetailCheckInStatus) >0 ){
						
					}else{
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
		 
		 
		 	
 $sqlCh = "UPDATE ".FO_BILL." SET status='2' , `checkout_date`='".date($NightAuditDated.' H:i:s')."' WHERE id='".$id_fo_bill."' and id_reservations='".$id_reservation."'  ";

mysqli_query($connNew,$sqlCh);
						}
			
			
	
	$dataArray['status']='1';
	$dataArray['message']='Checkout updated sucessfully';
	 echo json_encode($dataArray);
/*}else{
	$dataArray['status']='0';
	$dataArray['message']='Please verify data';
	 echo json_encode($dataArray);
}*/
	 
		}
	 
	 
			

		
		
		}
}
 ?>


