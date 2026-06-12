<?php include_once("../config/auto_loader.php");

$reservation_detail_query = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` where id_mst_room_no_allocation > '0' and checkin_status = '1' ORDER BY `fo_reservations_details`.`dated` ASC");
while ($reservation_result = mysqli_fetch_object($reservation_detail_query)) {
    $room_availability = 'Reserv';

    if ($reservation_result->checkin_status == '1') {
        $room_availability = 'Checkin';
    }

    if ($reservation_result->checkout_status == '1') {
        $room_availability = 'Checkout';
    }

    if ($reservation_result->checkout_status == '0') {
        $checkin_status = selectColumn(FO_BILL,'status'," WHERE `id` = '".$reservation_result->id_fo_bill."'");
        $room_availability = $checkin_status == '2' ? 'Checkout' : 'Checkin';
    }

    mysqli_query($connNew, "update `fo_reservations_details` SET room_availability = '".$room_availability."' where id = '".$reservation_result->id."'");
}
echo "update successfully";
exit;