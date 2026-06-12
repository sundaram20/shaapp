<?php
include_once("../../config/auto_loader.php");


$id_reservation					  =	$_REQUEST['noshowoff_id'];
$RoomNoShowOff		   			   =	$_REQUEST['RoomNoShowOff'];
$id_room		   =	$_REQUEST['id_RoomNoShowOff'];

// print_r($_REQUEST);

//die;

$order_by_room=array();
//================================================================================


//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$id_reservation."' and id_mst_room_no_allocation='".$id_mst_room_no_allocation."'  and  `no_showoff`='0' and 	  `checkin_status`='0' Group BY `fo_reservations_details`.`order_by_room` order by id asc";

	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$id_reservation."' and id_mst_room_types='".$id_room."'  and  `no_showoff`='0' and 	  `checkin_status`='0' Group BY `fo_reservations_details`.`order_by_room` ");
	if(mysqli_num_rows($sqlOrderDetail) >0 ){
	
	while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
		
	
	
	     //$order_by_room[]	=	$rowOrderDetail->order_by_room;
	
	}
	
	
	}
				//$order_by_room	=implode(',',$order_by_room);
				



$order_by_room=explode(',',$_REQUEST['RoomNoShowOff_orderbyroom']);

//foreach($order_by_room as $orderNumber){
for($i=0;$i<$RoomNoShowOff;$i++){
echo $insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 	
	 			 `no_showoff`='1'			 
				  where
				  `id_fo_reservations` = '".$id_reservation."'	and  `no_showoff`='0'   
				   and  order_by_room ='".$order_by_room[$i]."' ";
				//echo $insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGrid);








}



 ?>


