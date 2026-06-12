<?php
	include_once("../../config/auto_loader.php"); 
	//extract($_POST);
		//print_r(encryptor(decrypt,$_REQUEST['Id']));
	    $Id = encryptor(decrypt,$_REQUEST['Id']);
		//print_r($Id);
	
	
	
	 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					 $NightAuditDated = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
	
	
	 $id_mst_room_types = $_REQUEST['id_mst_room_types'];
	 $sql	=	"SELECT * FROM ".FO_RESERVATIONS." where id='".$Id."' ";
	$res 	 = 	mysqli_query($connNew,$sql);
	$roomCheckinArray	=array();
	$roomReservedArray	=array();
	$roomReservedRoomArray=array();
$roomNoArray=array();

$listRoomInBooking=array();
	while($row = mysqli_fetch_object($res)){
		 $checkinDate=	date('d-m-Y',strtotime($row->checkin));
		$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."'  and `no_showoff`='0' group by id_mst_room_types,id_mst_room_no_allocation ");
		
		$Reservationid=$row->id;
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';$roomCheckinArray=array();
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
							$rowOrderDetail->id_mst_room_types;
							$RoomWiseArray[$rowOrderDetail->id_mst_room_types][]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;	
							if($rowOrderDetail->checkin_status==1){	
								$croonNO2	=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."' and `id_mst_room_types` = '".$rowOrderDetail->id_mst_room_types."' and status='1'");
								$roomCheckinArray[]	=	$croonNO2;
							}else{
								if($rowOrderDetail->id_mst_room_no_allocation>0){
								$croonNO	=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."' and `id_mst_room_types` = '".$rowOrderDetail->id_mst_room_types."' and status='1'");
								$roomReservedArray[]	=	$croonNO;}
							}
							
							
							
	}	

		//==============================
			$sqlOrderDetailRoom = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' and checkin_status='0' and `no_showoff`='0' group by order_by_room order by id asc");
		
		$roomNoshowoffArray=array();
		
		if(mysqli_num_rows($sqlOrderDetailRoom) >0 ){
			
				while($rowOrderDetailRoom= mysqli_fetch_object($sqlOrderDetailRoom)){
					
							$listRoomInBooking[]='1';
								$roomReservedRoomQty +='1';
							
								if($rowOrderDetailRoom->no_showoff==1){	
							 $roomNoshowoffArray[]=$rowOrderDetailRoom->id_mst_room_no_allocation;
							}
							
	}
		}
		//===================================
		
		
		
		}
		
		
		if(is_array($listRoomInBooking) ){
			$listRoomInBooking = count($listRoomInBooking);
			}else{
			$listRoomInBooking = 0;
			}
			
		if(is_array($roomNoshowoffArray) ){
			$roomNoshowoffArrayCount = count($roomNoshowoffArray);
			
			}else{
			$roomNoshowoffArrayCount = 0;
			}	
			//echo $roomNoshowoffArrayCount;
			$listRoomInBooking=$listRoomInBooking-$roomNoshowoffArrayCount;
			
		//debugData($listRoomInBooking);
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
	//debugData($RoomWiseArray);
	
	
	if(!empty($roomNoArray)){
	$id_mst_room_no_allocation =implode(',',$roomNoArray);
	$conn	=	" AND id NOT IN (".$id_mst_room_no_allocation.")";
}	
	foreach($RoomWiseArray as $id_room=>$room_no_allocation){
		
		$id_room;
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
		}	
	 $selectFree="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room."' and room_status='4' and status='1'  $conn " ;
							 $resFree = mysqli_query($connNew,$selectFree);
							 while($rowFree= mysqli_fetch_object($resFree)){
								//$Blo = $rowneww->id_mst_hotel_room_block;
								 $allocation[] =$rowFree->room_no;
								
							} 
							
							//debugData($BookedRoom);
	//debugData($allocation_id);
	//echo '<br>============'.$id_room;
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
$roomCount	=	"'".$listRoomInBooking."'";
$ReservationidData	=	"'".$Reservationid."'";
$id_mst_room_types	=	"'".addslashes($id_mst_room_types)."'";
//die;
		$start .='
          <div class="row" >
            <div class="col-md-12 col-sm-12">
              <h4>'.$RoomName.'</h4><div style="text-align: center;
			  margin: 9px;
			  font-size: 13px;" id="RoomCountSelected_'.$listRoomInBooking.'_'.$Reservationid.'"></div>
            </div>
            <div class="col-md-12">
              <div class="row text-center">';
			  foreach($allocation_id as $roomDetails){ 
			  if(in_array($roomDetails['roomNo'],$BookedRoom)){
				  $roomNumbers	=	"'".$roomDetails['roomNo']."'";
                $start .='<label class="checkbox-label" for="myCheckbox">
    <input type="checkbox"  checked="checked" id="myCheckboxData_'.$listRoomInBooking.'_'.$Reservationid.'" name="expected_arrivals_rooms[]" class="roomdata_'.$listRoomInBooking.'_'.$Reservationid.'_'.$roomDetails['roomNo'].'" onclick="ValidateRoomSelected('.$roomCount.','.$ReservationidData.','.$roomNumbers.');" value="'.$roomDetails['roomNo'].'"> '.$roomDetails['roomNo'].'
  </label>';
			  }else{
				  
				  $roomNumbers	=	"'".$roomDetails['roomNo']."'";
                $start .='<label class="checkbox-label" for="myCheckbox">
    <input type="checkbox" id="myCheckboxData_'.$listRoomInBooking.'_'.$Reservationid.'" name="expected_arrivals_rooms[]" class="roomdata_'.$listRoomInBooking.'_'.$Reservationid.'_'.$roomDetails['roomNo'].'" onclick="ValidateRoomSelected('.$roomCount.','.$ReservationidData.','.$roomNumbers.');" value="'.$roomDetails['roomNo'].'"> '.$roomDetails['roomNo'].'
  </label>';
				  
				   }
			  
			  
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
		
		
		if(strtotime($NightAuditDated) ==	strtotime($checkinDate) ){
         $returnData['rr'] .='<button class="btn btn-primary pull-right"  style="margin-left:10px;"  onClick="updateCheckinTime('.$Reservationid.','.$id_mst_room_types.');">Check-in</button>';
		 }
        $returnData['rr'] .='&nbsp;&nbsp;&nbsp;<button class="btn btn-primary pull-right" onClick="RoomAllocationsingleForm('.$Reservationid.','.$id_mst_room_types.');">Allocate </button>';
		  
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