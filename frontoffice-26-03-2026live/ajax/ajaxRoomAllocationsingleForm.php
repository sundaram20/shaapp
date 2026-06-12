<?php
	include_once("../../config/auto_loader.php");
	include_once("../functions/function.php");
	//echo $resvId;
	

	//debugData($_REQUEST);
	
	
	
	//debugData($_REQUEST);//die;
	$jsondeocde 			= 	json_decode($_REQUEST['bookedRoom'], true);
	$RoomNoArray		   =	explode(',',$_REQUEST['dataselected']);
	$id_mst_room_types	 =	$_REQUEST['id_mst_room_types'];
	//debugData($RoomNoArray);
	//die;
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		  $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					 
					 
		$TodaysData	=	$NightAuditDated;//date('Y-m-d');  
	
		$sql		   =	"SELECT * FROM ".FO_RESERVATIONS."  where id='".addslashes($_REQUEST['resvId'])."' ";
		$res 	       = 	mysqli_query($connNew,$sql);		
		$row           = 	mysqli_fetch_object($res);
		
		$checkout	 =	$row->checkout;
		$checkin	  =	$row->checkin;
		$dated 		= 	$checkin;
		$DateArray	=	array();
		
		//================================
		$sqlOrderGroupby = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and checkin_status='0' and  no_showoff ='0'   and id_mst_room_types='".addslashes($id_mst_room_types)."'  group by order_by_room ");
		while($rowOrderDetailGroupBy= mysqli_fetch_object($sqlOrderGroupby)){ 
			$groupbyReservedRoom[]=$rowOrderDetailGroupBy->id_mst_room_no_allocation;
		}
	   
	   
	  $sqlOrderDetailOrder = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."'  and  id_mst_room_types='".addslashes($id_mst_room_types)."' group by order_by_room ");
		 
	   $NewOrderBy=array();
	   while($rowOrderDetailOrder= mysqli_fetch_object($sqlOrderDetailOrder)){
		   
		  
		   if($rowOrderDetailOrder->checkin_status==0 ){	
							$NewOrderBy[] =$rowOrderDetailOrder->order_by_room;
							}
	   }
	   $NewOrderBy = implode(',',$NewOrderBy);
	   
	   
	   $sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."'  and  id_mst_room_types='".addslashes($id_mst_room_types)."'and  order_by_room IN (".$NewOrderBy.")  group by order_by_room ");
		
		 $UserSelectedDiff	= array_diff($RoomNoArray,$groupbyReservedRoom);
		//debugData($UserSelectedDiff);
		//die;
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			$order_by_roomArray=array();
			$order_by_roomCheckInArray=array();
			$ArrayOrder	=0;
			while($rowOrderDetail2= mysqli_fetch_object($sqlOrderDetail2)){
				if($rowOrderDetail2->id_mst_room_no_allocation==0){
					$order_by_roomArray[] =$rowOrderDetail2->order_by_room;
				}
							
					//debugData($order_by_roomArray);			
	$room_noCheckArray= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail2->id_mst_room_no_allocation."'");
	
	if(in_array($room_noCheckArray,$RoomNoArray)){
									
	//foreach (array_keys($RoomNoArray, $room_noCheckArray) as $key) {
     //unset($RoomNoArray[$key]);
	//}
			
								//$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation'] =	$rowOrderDetail2->id_mst_room_no_allocation;
								//$order_by_roomAllocationInArray[$ArrayOrder]['Status'] 			=	'Matched';
								//$order_by_roomAllocationInArray[$ArrayOrder]['StatusMatched'] 	 =	'1';
								
								
								$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation']=0;
									$order_by_roomAllocationInArray[$ArrayOrder]['Status'] ='Not Matched';
									$order_by_roomAllocationInArray[$ArrayOrder]['StatusMatched'] ='0';
								$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved']='zero';
								//$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved']	 =	$rowOrderDetail2->id_mst_room_no_allocation;
								$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_types'] =	$rowOrderDetail2->id_mst_room_types;
									
								}else{
								
									$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation']=0;
									$order_by_roomAllocationInArray[$ArrayOrder]['Status'] ='Not Matched';
									$order_by_roomAllocationInArray[$ArrayOrder]['StatusMatched'] ='0';
									$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_types']=$rowOrderDetail2->id_mst_room_types;
									
									//foreach($UserSelectedDiff as $data){										
										
									if($rowOrderDetail2->id_mst_room_no_allocation=='0'){
								
										$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved']='zero';
									
									}else{
								
										$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved']=$rowOrderDetail2->id_mst_room_no_allocation;
																			
										}
								}
										$order_by_roomAllocationInArray[$ArrayOrder]['order_by_room']=$rowOrderDetail2->order_by_room;
								
				$ArrayOrder++;				
				}	
	
		}
		//==============================
		
		//echo '===============';
		//debugData($groupbyReservedRoom);
	//	debugData($order_by_roomAllocationInArray);
		//die;
		
		
		
		
		while(strtotime($dated)!=strtotime($checkout)){
				$DateArray[]=date("Y-m-d",strtotime($dated));
				$dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));	
			}
		  $DateArray= "'".implode ( "','", $DateArray )."'";
		
		
		
		$sqlOrderDetailArray = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' group by id_mst_room_types ,id_fo_rate_plan ");
		
		if(mysqli_num_rows($sqlOrderDetailArray) >0 ){
			$RoomWiseArray=array();			
				while($rowOrderDetailArray= mysqli_fetch_object($sqlOrderDetailArray)){
					$RoomWiseArray[]= $rowOrderDetailArray->id_mst_room_types;
					
				}
		}
		 $id_rooms = implode(',',$RoomWiseArray);
		 
	
$sqlCheckVacant = mysqli_query($connNew,"Select `".TBL_ROOMNO."`.* from `".TBL_ROOMNO."` where room_no In (".$_REQUEST['dataselected'].")  and id_mst_room_types IN (".$id_mst_room_types.") and room_status NOT IN (4,2)");
	if(mysqli_num_rows($sqlCheckVacant) >0 ){
		//while($rowReservationVac= mysqli_fetch_object($sqlCheckVacant)){
			
			//echo '<pre>';print_r($rowReservationVac);echo '<pre>';
		//}
	echo 'Room Is Occupied Checkout And Try';	
	die;	

}


		$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."'  and `checkin_status`='0' and no_showoff ='0' ");
		
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
		$rowReservationDetail= mysqli_fetch_object($sqlOrderDetail);
		$id_mst_room_no_allocation=$rowReservationDetail->id_mst_room_no_allocation;	
		
		
			
						
		//	while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
			//	print_r($rowOrderDetail);
			
					
		/*foreach($order_by_roomArray  as $key1=>$order_by_room){
			echo '<br>'.$key1.'=='.$RoomNoArray[$key1];
			$mergeArray[$order_by_room]['RoomOrder']=$order_by_room;
			//foreach($RoomNoArray as $arrayValue){
				//echo '==='.$RoomNoArray[$key1];
				$mergeArray[$order_by_room]['RoomNo']=$RoomNoArray[$key1];
			//}
		}*/
		//debugData($order_by_roomArray);die;
		//$order_by_roomArray2 =array_combine( $order_by_roomArray, $RoomNoArray );		
		//$order_by_room=0;	
		//debugData($mergeArray);
	
		// debugData($RoomNoArray);
		//$RoomNoArray = array_values(array_filter($RoomNoArray));
		// debugData($RoomNoArray);
			$loopKeys=0;
		//debugData($order_by_roomAllocationInArray);//die;
		foreach($order_by_roomAllocationInArray as  $key=>$orderListValue){ //debugData($orderListValue);echo '====='.$RoomNoArray[$key].'-'.$key;
		if($orderListValue['StatusMatched']=='0'){
			if($_REQUEST['id_mst_room_types']==$orderListValue['id_mst_room_types']){ //echo ' ====SS='.$key;
			//foreach($RoomNoArray as $key=>$arrayValue){	
			$loopKeys;
			$RoomNoArray[$key]; 
			//echo '----------id_mst_room_types'," WHERE `room_no` = '".$RoomNoArray[$key]."' and id_mst_room_types IN (".$id_rooms.")";
				$id_mst_room_types=         selectColumn(TBL_ROOMNO,'id_mst_room_types'," WHERE `room_no` = '".$RoomNoArray[$key]."' and id_mst_room_types IN (".$id_rooms.")");
				$id_mst_room_no_allocation= selectColumn(TBL_ROOMNO,'id'," WHERE `room_no` = '".$RoomNoArray[$key]."' and id_mst_room_types IN (".$id_rooms.")");
			
		
		
			
				//`id_mst_room_no_allocation`='".$id_mst_room_no_allocation."',
			$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
				
				
				 `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."'			 
				  where
				  `id_fo_reservations` = '".$_REQUEST['resvId']."' and no_showoff ='0'  and checkin_status ='0' 
				   and order_by_room='".$orderListValue['order_by_room']."'		
				   and id_mst_room_types='".$orderListValue['id_mst_room_types']."' 
				   and  DATE(dated) IN (".stripslashes($DateArray).") ";
				//echo '<br><br><br>'.$insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGrid);
			
			//echo '<br><br><br>1=========='.$insertGrid;
		
		$updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='2'	  where id='".$id_mst_room_no_allocation."'";
			  	mysqli_query($connNew,$updateRoomstatus);
		
		
		
		//echo 'step4';die;
			 //echo '<br>2=========='.$insertGrid2;
		 // $updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='3'	  where id='".$id_mst_room_no_allocation."'     ";
				//echo $insertGrid;die;
			//$insertOrder	=mysqli_query($connNew,$insertGrid);
			//mysqli_query($connNew,$updateRoomstatus);	
			
			
			//}
				
				}
			
			
		}
		}
		
		
			echo "Room Allocation Processed Successfully";
			
			
		
		}else{
			$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."'  ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			
			echo 'Check-in Status Already Update';
		}else{
			echo 'Invalid Check-in Date';
		}
			}
	