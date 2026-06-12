<?php
	include_once("../../config/auto_loader.php"); 
	//extract($_POST);
	//print_r(encryptor(decrypt,$_REQUEST['Id']));
	   $Id = encryptor(decrypt,$_REQUEST['Id']);
	//print_r($Id);
	
	 $id_mst_room_types = $_REQUEST['id_mst_room_types'];
	 $sql	=	"SELECT * FROM ".FO_RESERVATIONS." where id='".$Id."' ";
	$res 	= 	mysqli_query($connNew,$sql);
	$roomCheckinArray	=array();
	$roomReservedArray	=array();
	$roomReservedRoomArray=array();
$roomNoArray=array();

					 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					 $NightAuditDated = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					 
				
$listRoomInBooking=array();
	while($row = mysqli_fetch_object($res)){
		$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."'  group by id_mst_room_types,id_mst_room_no_allocation ");
		$checkinDate=	date('d-m-Y',strtotime($row->checkin));
		$Reservationid=$row->id;
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';$roomCheckinArray=array();
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
							$rowOrderDetail->id_mst_room_types;
							$RoomWiseArray[$rowOrderDetail->id_mst_room_types][]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;	
							if($rowOrderDetail->checkin_status==1){	
								$croonNO2=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."' and `id_mst_room_types` = '".$rowOrderDetail->id_mst_room_types."' and status='1'");
							$roomCheckinArray[]='==='.$croonNO2;
							}else{
								if($rowOrderDetail->id_mst_room_no_allocation>0){
							$croonNO=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."' and `id_mst_room_types` = '".$rowOrderDetail->id_mst_room_types."' and status='1'");
								$roomReservedArray[]=$croonNO;}
							}
							
							
							
	}	

		//==============================
			$sqlOrderDetailRoom = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' and checkin_status='0' group by order_by_room ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetailRoom) >0 ){
			
				while($rowOrderDetailRoom= mysqli_fetch_object($sqlOrderDetailRoom)){
					
							$listRoomInBooking[$rowOrderDetailRoom->id_mst_room_types]+='1';
								$roomReservedRoomQty +='1';
							
	}
		}
		//===================================
		
		
		
		}
		//echo $roomReservedRoomQty;
		
		//==================================================
		$res_checkinDate=date('Y-m-d',strtotime($row->checkin));
		$res_checkOutDate=date('Y-m-d',strtotime($row->checkout));
		  $SQLReservation="SELECT DISTINCT id_mst_room_no_allocation FROM `fo_reservations_details` WHERE DATE(dated) >= '".$res_checkinDate."' and DATE(dated)<= '".$res_checkOutDate."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' ORDER BY `fo_reservations_details`.`id_mst_room_no_allocation` ASC" ;
			$QueryReservation = mysqli_query($connNew,$SQLReservation);

				
				while($RowReservation = mysqli_fetch_object($QueryReservation)){
					if($RowReservation->id_mst_room_no_allocation>0){
					$roomNoArray[]=$RowReservation->id_mst_room_no_allocation;
					}
				}
				//==================================================
	}
	//debugData($roomNoArray);
	if(!empty($roomNoArray)){
$id_mst_room_no_allocation =implode(',',$roomNoArray);
	$conn	=	" AND id NOT IN (".$id_mst_room_no_allocation.")";
}	
	foreach($RoomWiseArray as $id_room=>$room_no_allocation){
		
		// $id_room;
		$allocation=array();
		$allocation_id=array();
		$Roomnopicked=array();$check='';
		foreach($room_no_allocation as $roomlist){
			$rr= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$roomlist['id_mst_room_no_allocation']."' and status='1'");			
			$Roomnopicked[] =selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$roomlist['id_mst_room_no_allocation']."' and status='1'");
			$BookedRoom[] =$rr>0?$rr:'0';
			}
		$list=		implode(',',$allocation);
		if($roomlist['id_mst_room_no_allocation']=='0'){
		$allocation[]	=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$roomlist['id_mst_room_no_allocation']."' and status='1'");
		}
		
		$selectnew="SELECT * FROM ".TBL_ROOMNO."  where id_mst_room_types=$id_room  and status='1' ";//$conn
				$resnew = mysqli_query($connNew,$selectnew); 
				  while($rownew = mysqli_fetch_object($resnew)){
  			        //  $allocation[] =$rownew->room_no;
					  $allocation_id[$rownew->id]['roomNo'] =$rownew->room_no;
					  $allocation_id[$rownew->id]['id_room']=$rownew->id;
		}	//debugData($allocation_id);
	  $selectFree="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room."'  and status='1'  $conn " ;
							 $resFree = mysqli_query($connNew,$selectFree);
							 while($rowFree= mysqli_fetch_object($resFree)){
								
								 $allocation[] =$rowFree->room_no;
							
							} 
							
			/* $SQLReservation="SELECT DISTINCT id_mst_room_no_allocation FROM `fo_reservations_details` WHERE DATE(dated) >= '".$res_checkinDate."' and DATE(dated)<= '".$res_checkOutDate."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' ORDER BY `fo_reservations_details`.`id_mst_room_no_allocation` ASC" ;
			$QueryReservation = mysqli_query($connNew,$SQLReservation);

				
				while($RowReservation = mysqli_fetch_object($QueryReservation)){
					if($RowReservation->id_mst_room_no_allocation>0){
					//$roomNoArray[]=$RowReservation->id_mst_room_no_allocation;
					
					 echo $selectFree="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room."' and room_status='4' and status='1'  $conn " ;
							 $resFree = mysqli_query($connNew,$selectFree);
							 while($rowFree= mysqli_fetch_object($resFree)){
								
								 $allocation[] =$rowFree->room_no;
							
							} 
					
					
					}
				}				
						*/	
							
							
							
							
							
							
	//debugData($allocation);
	
	$TotalPendingCheckin	=	 $roomReservedRoomQty-count($roomCheckinArray);
			$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_room."' and status=1");
			//$check	=array_merge($chek,$Roomnopicked);
			//$x=array();
			$BookedRoom1[] ='0';
			$x=$BookedRoom1;
			$y= array($Id.'_1'=>array_map('intval', $x) );
			$roomTypes[] = 
array("room_id"=>$id_room,"RoomName"=>$RoomName,"RoomDetails"=>$allocation,"id_mst_room_no_allocation"=>$allocation_id,"Roomnopicked"=>$Roomnopicked,"bookedRoom"=>$y,"BookingId"=>$Id.'_1',"BlockedRoom"=>$x,"roomCheckinArray"=>$roomCheckinArray,
"roomReservedArray"=>$roomReservedArray,'TotalPendingCheckin'=>$TotalPendingCheckin);
//debugData($listRoomInBooking);
$roomCount	=	"'".$listRoomInBooking[$id_room]."'";
$ReservationidData	=	"'".$Reservationid."'";
$id_mst_room_types	=	"'".addslashes($id_mst_room_types)."'";
//die;
	$start .=	'
          <div class="row" >
            <div class="col-md-12 col-sm-12">
              <h4>'.$RoomName.'</h4><div style="text-align: center;
			  margin: 9px;
			  font-size: 13px;" id="RoomCountSelected_'.$listRoomInBooking[$id_room].'_'.$Reservationid.'"></div>
            </div>
            <div class="col-md-12">
              <div class="row text-center">';
			  foreach($allocation_id as $roomDetails){ $roomNumbers	=	"'".$roomDetails['roomNo']."'";
                $start .='<label class="checkbox-label" for="myCheckbox">
    <input type="checkbox" id="myCheckboxData_'.$listRoomInBooking[$id_room].'_'.$Reservationid.'" name="expected_arrivals_rooms[]" class="roomdata_'.$listRoomInBooking[$id_room].'_'.$Reservationid.'_'.$roomDetails['roomNo'].'" onclick="ValidateRoomSelected('.$roomCount.','.$ReservationidData.','.$roomNumbers.');" value="'.$roomDetails['roomNo'].'"> '.$roomDetails['roomNo'].'
  </label>';
			  }
                $start .='</div>
            </div>
          </div>
          
         ';	
		}
		
$returnData = array(); 	
$returnData['rr']='<td colspan="9">
<div class="row">
    <div class="col-md-12 col-sm-12">
      <div class="box box-primary box-outline">
        <div class="box-body">'.$start. '<div id="showBookedRoom_${resId}"></div>
        </div>
        <div class="box-footer">';
		if($NightAuditDated ==	$checkinDate ){
          $returnData['rr'] .='<button class="btn btn-primary pull-right"  style="margin-left:10px;" onClick="updateCheckinTime('.$Reservationid.','.$id_mst_room_types.');">Check-in</button> ';
		}else{
		$returnData['rr'] .='&nbsp;&nbsp;&nbsp;<button class="btn btn-primary pull-right" onClick="RoomAllocationsingleForm('.$Reservationid.','.$id_mst_room_types.');">Allocate </button>';
		}
		$returnData['rr'] .='</div>
      </div>
    </div>
  </div></td>';

		
		
		
		
		//echo '111'.$BookedRoom;
		//print_r($BookedRoom);
		//echo $returnData =  array_map('intval', $BookedRoom);
		echo json_encode($returnData);
		die;
	
	
	
?>