<?php include_once("../../config/auto_loader.php");

$id_fo_bill = $_REQUEST['id_fo_bill'];

$id_folio = selectColumn('fo_bill','id_fo_folio'," WHERE `id` = '".$id_fo_bill."'");
$get_results = get_pending_records_folio_wise($connNew, $id_folio);

$get_folio_query = mysqli_query($connNew, "select * from fo_folio where id_parent_folio = '".$id_folio."'");
while ($folio_result = mysqli_fetch_object($get_folio_query)) {
    $result = get_pending_records_folio_wise($connNew, $folio_result->id);
    $get_results = array_merge($get_results, $result);
}

echo json_encode($get_results);
exit;

function get_pending_records_folio_wise($connNew, $id_folio) {
    $folio_query = mysqli_query($connNew, "select * from fo_folio where id = '".$id_folio."'");
    $folio_result = mysqli_fetch_object($folio_query);

    $mdoc_no = selectColumn('fo_bill','mdoc_no'," WHERE `id` = '".$folio_result->id_fo_bill."'");

    $reservation_query = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as tariff_price_per_day_per_room ,SUM(tax_per_day_per_room) as tax_per_day_per_room FROM `fo_reservations_details` WHERE `id_fo_folio_to` = '".$folio_result->id."' GROUP by id_fo_folio_to");
    $reservation_result = mysqli_fetch_object($reservation_query);

    $reservation_addon_query = mysqli_query($connNew, "SELECT SUM(total) as total FROM `fo_reservations_addons_details` WHERE `id_fo_folio_to` = '".$folio_result->id."' GROUP by id_fo_folio_to");
    $reservation_addon_result = mysqli_fetch_object($reservation_addon_query);

    /*$pos_query = mysqli_query($connNew, "SELECT SUM(grant_total_amount) as grant_total_amount FROM `pos_purch` WHERE `id_fo_folio_to` = '".$folio_result->id."'  and cancelled='0' GROUP by id_fo_folio_to");
    $pos_result = mysqli_fetch_object($pos_query);*/
	$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($folio_result->id)."' and cancelled=0 ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$PosTotal='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
				$PosTotal += 	($rowOrderDetail->sub_total_items-$rowOrderDetail->total_discount_items)+($rowOrderDetail->sgst_total_items+$rowOrderDetail->cgst_total_items+$rowOrderDetail->vat_total_items+$rowOrderDetail->surcharge_total_items);
				}
				}

    $receipt_query = mysqli_query($connNew, "SELECT SUM(amount) as amount FROM `fo_receipt` WHERE `id_fo_folio` = '".$folio_result->id."' GROUP by id_fo_folio");
    $receipt_result = mysqli_fetch_object($receipt_query);

    $total_amount = ($reservation_result->tariff_price_per_day_per_room ?? 0) + ($reservation_result->tax_per_day_per_room ?? 0) + ($reservation_addon_result->total) + ($PosTotal);
    $balance = round($total_amount - (round($receipt_result->amount) ?? 0));

    $result = [];

    if ($mdoc_no == '') {
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