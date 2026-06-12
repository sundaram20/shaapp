<?php include_once("../../config/auto_loader.php");
$hotelId = $_REQUEST['hotelId'];
$id_room_type = $_REQUEST['id_room_type'];
$room_id = $_REQUEST['room_id'];
$roomdefaultvalue;

$occupied_room_ids = [];
$occupied_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."' and room.id_mst_room_types = '".$id_room_type."' and room_availability = 'Checkin'");
if (mysqli_num_rows($occupied_room_query) > 0) {
    while ($row = mysqli_fetch_object($occupied_room_query)) {
		$occupied_room_ids[] = $row->id;
	}
}
$resRoom = mysqli_query($connNew, "SELECT * FROM  ".TBL_ROOMNO." where  id_mst_room_types = '" . $id_room_type . "' and status = '1' and management_block = 'No'");
$hotelRoomType .= '<option value="0">Any</option>';
$room_no = selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$room_id."'");
$hotelRoomType .= '<option value="'.$room_id.'">'.$room_no.'</option>';
while ($rowRoom = mysqli_fetch_object($resRoom)) {
	$check_checkout = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_allocation = '".$room['room_id']."' and checkin_status = '1' and checkout_status = '0' and room_availability = 'checkout' and checkout_date is not null");
	if (!in_array($rowneww->id, $occupied_room_ids) && mysqli_num_rows($check_checkout) == 0) {
		$hotelRoomType .= '<option '.$selected.' value="'.$rowRoom->id.'">'.$rowRoom->room_no.'</option>';
	}
}
$hotelRoomType  .=	'</select>';
echo $hotelRoomType;
die;
?>