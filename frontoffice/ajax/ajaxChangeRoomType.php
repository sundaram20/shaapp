<?php include_once("../../config/auto_loader.php");
include_once("../../functions/inventoryUpdateFunctions.php");

$updated_room_type = $_REQUEST['updated_room_type'];
$id_mst_room_types = $_REQUEST['id_mst_room_types'];
$reservation_id = $_REQUEST['reservation_id'];
$room_count = $_REQUEST['room_count'];

$ArrayOfRoom	=	explode(',',$_REQUEST['selected_rooms']);
foreach($ArrayOfRoom  as $ArrayOfRoom){

	
	
	$ListArrayValue	=	explode('-',$ArrayOfRoom);
$reservation_id  =$ListArrayValue['0'];
	$id_mst_room_types  =$ListArrayValue['1'];
	$id_mst_room_no_allocation  =$ListArrayValue['2'];
	$order_by_room  =$ListArrayValue['3'];

    mysqli_query($connNew, "update fo_reservations_details set id_mst_room_types = '".$updated_room_type."',id_mst_room_no_allocation='0' where id_fo_reservations = '".$reservation_id."' and order_by_room = '".$order_by_room."'  and id_mst_room_types = '".$id_mst_room_types."' and checkin_status = '0'  ");
	
	if($id_mst_room_no_allocation>0){
	mysqli_query($connNew, "update mst_room_no_allocation set room_status = '4' where id = '".$id_mst_room_no_allocation."'  ");
	
	
	}
	

}

$resDetailSQL = "SELECT id_mst_hotels,checkin,checkout FROM fo_reservations WHERE id = ?";
if ($stmt = mysqli_prepare($connNew, $resDetailSQL)) {
    mysqli_stmt_bind_param($stmt, "i", $reservation_id);
    
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

//print_r($_REQUEST);
//die;
//and id_mst_room_no_allocation = '0';
/*$reservation_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$reservation_id."' and id_mst_room_types = '".$id_mst_room_types."'  and checkin_status = '0' group by order_by_room limit ".$room_count);
while ($reservation_result = mysqli_fetch_object($reservation_query)) {
    mysqli_query($connNew, "update fo_reservations_details set id_mst_room_types = '".$updated_room_type."',id_mst_room_no_allocation='0' where id_fo_reservations = '".$reservation_result->id_fo_reservations."' and order_by_room = '".$reservation_result->order_by_room."'");
}*///

echo "Room Type Changed Successfully";