<?php 

include_once("../../config/auto_loader.php");
include_once("../functions/function.php");

//debugData($_REQUEST);

$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_mst_room_no_allocation='0' and id_fo_reservations ='".$_REQUEST['roomAllocation_id_reservations']."' and id_mst_hotels ='".$_REQUEST['roomAllocation_id_hotel']."' and id_mst_room_types ='".$_REQUEST['roomAllocation_id_room']."' order by order_by_room ");
			
			
			if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
				//debugData($rowOrderDetail->order_by_room);	
				
				$arrayRoom[$rowOrderDetail->order_by_room][$rowOrderDetail->id]['id']=$rowOrderDetail->id;
				$arrayRoom[$rowOrderDetail->order_by_room][$rowOrderDetail->id]['Dated']=$rowOrderDetail->dated;
				}
			}
			//debugData($arrayRoom);
			foreach($_REQUEST['all_room_select'] as $k=>$roomNo){
				
				foreach($arrayRoom[$k]  as $roomList){
				 echo $updateRoomstatus =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `id_mst_room_no_allocation`='".$roomNo."'	where `id` = '".$roomList['id']."' and dated='".$roomList['Dated']."' ";				
				mysqli_query($connNew,$updateRoomstatus);
				
				}
				}
?>