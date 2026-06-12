<?php
include_once("../../config/auto_loader.php");
include_once("../../functions/inventoryUpdateFunctions.php");


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
$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 	
	 			 `no_showoff`='1'			 
				  where
				  `id_fo_reservations` = '".$id_reservation."'	and  `no_showoff`='0'   
				   and  order_by_room ='".$order_by_room[$i]."' ";
				//echo $insertGrid;//die;
			$insertOrder	=mysqli_query($connNew,$insertGrid);




}

$resDetailSQL = "SELECT id_mst_hotels,checkin,checkout FROM fo_reservations WHERE id = ?";
if ($stmt = mysqli_prepare($connNew, $resDetailSQL)) {
    mysqli_stmt_bind_param($stmt, "i", $id_reservation);
    
    mysqli_stmt_execute($stmt);
    $resResult = mysqli_stmt_get_result($stmt);
    $reservationData = mysqli_fetch_object($resResult);
    
    if ($reservationData) {
        $apiHotelID = $reservationData->id_mst_hotels;
        $check_in = date('Y-m-d', strtotime($reservationData->checkin));
        $check_out = date('Y-m-d', strtotime($reservationData->checkout));
    }
    
    mysqli_stmt_close($stmt);
}

$check_in = $check_in;
	$check_out = $check_out;
    $apiHotelID= $apiHotelID;
	$sqlMappingInventory = 'SELECT auto_sync_inv FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
								WHERE  B.hotel_id="'.$apiHotelID.'" AND B.status=1 and channel_type=1';
		$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
		$resultMapping   =    mysqli_fetch_object($QueryMapping);
		$autoInventoryUpdate=$resultMapping->auto_sync_inv;
		
		
		
		//if($autoInventoryUpdate==1){
			updateOTA($apiHotelID,date('Y-m-d',strtotime($check_in)),date('Y-m-d',strtotime($check_out)),$connNew);
		//}



 ?>


