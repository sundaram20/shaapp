<?php include_once("../../config/auto_loader.php");

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
//print_r($_REQUEST);
//die;
//and id_mst_room_no_allocation = '0';
/*$reservation_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$reservation_id."' and id_mst_room_types = '".$id_mst_room_types."'  and checkin_status = '0' group by order_by_room limit ".$room_count);
while ($reservation_result = mysqli_fetch_object($reservation_query)) {
    mysqli_query($connNew, "update fo_reservations_details set id_mst_room_types = '".$updated_room_type."',id_mst_room_no_allocation='0' where id_fo_reservations = '".$reservation_result->id_fo_reservations."' and order_by_room = '".$reservation_result->order_by_room."'");
}*///

echo "Room Type Changed Successfully";