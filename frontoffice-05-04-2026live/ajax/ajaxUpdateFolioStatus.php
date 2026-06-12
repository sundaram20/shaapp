<?php include_once("../../config/auto_loader.php");

$id_fo_folio = $_REQUEST['id_fo_folio'];
$status = $_REQUEST['status'];
$id_reservation = $_REQUEST['id_reservation'];
$id_fo_bill = $_REQUEST['id_fo_bill'];

$get_results = get_pending_records_folio_wise($connNew, $id_fo_folio);

$get_folio_query = mysqli_query($connNew, "select * from fo_folio where id_parent_folio = '".$id_fo_folio."'");
while ($folio_result = mysqli_fetch_object($get_folio_query)) {
    $result = get_pending_records_folio_wise($connNew, $folio_result->id);
    $get_results = array_merge($get_results, $result);
}
//debugData($get_results);die;
//if (count($get_results) == 0) {
	$sqlCheckoutStatus = mysqli_query($connNew, "Select * From  ".FO_BILL." WHERE id = '".$id_fo_bill."' AND status = '2' and id_reservations = '".$id_reservation."'");
	if ((mysqli_num_rows($sqlCheckoutStatus) > 0 && $_REQUEST['ReservationAvaliable'] == '1') || $_REQUEST['ReservationAvaliable'] == '0') {
		$sql = "UPDATE fo_folio SET folio_status='".$status."' WHERE id='".$id_fo_folio."'";
		if (mysqli_query($connNew,$sql)) {
			echo $status;
		} else {
			echo 0;
		}
	} else {
		echo 0;
	}
//} else {
	//echo 2;
//}

function get_pending_records_folio_wise($connNew, $id_folio) {
    $folio_query = mysqli_query($connNew, "select * from fo_folio where id = '".$id_folio."'");
    $folio_result = mysqli_fetch_object($folio_query);

    $mdoc_no = selectColumn('fo_bill','mdoc_no'," WHERE `id` = '".$folio_result->id_fo_bill."'");

    $reservation_query = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as tariff_price_per_day_per_room ,SUM(tax_per_day_per_room) as tax_per_day_per_room FROM `fo_reservations_details` WHERE `id_fo_folio_to` = '".$folio_result->id."' GROUP by id_fo_folio_to");
    $reservation_result = mysqli_fetch_object($reservation_query);

    $reservation_addon_query = mysqli_query($connNew, "SELECT SUM(total) as total FROM `fo_reservations_addons_details` WHERE `id_fo_folio_to` = '".$folio_result->id."' GROUP by id_fo_folio_to");
    $reservation_addon_result = mysqli_fetch_object($reservation_addon_query);

    $pos_query = mysqli_query($connNew, "SELECT SUM(grant_total_amount) as grant_total_amount FROM `pos_purch` WHERE `id_fo_folio_to` = '".$folio_result->id."'  and cancelled='0' GROUP by id_fo_folio_to");
    $pos_result = mysqli_fetch_object($pos_query);

    $receipt_query = mysqli_query($connNew, "SELECT SUM(amount) as amount FROM `fo_receipt` WHERE `id_fo_folio` = '".$folio_result->id."' GROUP by id_fo_folio");
    $receipt_result = mysqli_fetch_object($receipt_query);

    $total_amount = ($reservation_result->tariff_price_per_day_per_room ?? 0) + ($reservation_result->tax_per_day_per_room ?? 0) + ($reservation_addon_result->total) + ($pos_result->grant_total_amount);
    $balance = round($total_amount) - (round($receipt_result->amount) ?? 0);

    $result = [];

    if ($mdoc_no == '' && $_SESSION['database']!='hip') {
        array_push($result, 'FO Bill not Generate.');
    }

    if ($balance > 0) {
        array_push($result, 'Your Receipt Balance Is Pending.');
    }

    $results = [];

    if (!empty($result)) {
        $results[$folio_result->mdoc_no] = $result;
    }

    return $results;
}
?>