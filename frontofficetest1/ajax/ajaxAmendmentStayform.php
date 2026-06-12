<?php
include_once("../../config/auto_loader.php");

if (empty($_REQUEST['checkoutExtend_date'])) {
    echo "Please Select Checkout Date.";
    exit;
}

$room_no        = $_REQUEST['amend_room_no'] ?? '';
$order_by_room  = $_REQUEST['amend_order_by_room'] ?? '';
$reservation_id = addslashes(encryptor('decrypt', $_REQUEST['ext_id']));
$new_checkout   = date('Y-m-d', strtotime($_REQUEST['checkoutExtend_date']));

/* ================= GET RESERVATION ================= */

$sql = "SELECT * FROM `".FO_RESERVATIONS."`
        WHERE id = '".$reservation_id."'";
$db->query($sql);
$row = $db->fetch_object();

if (!$row) {
    echo "Reservation not found.";
    exit;
}

/* ================= GET LAST NIGHT FROM DETAILS ================= */

$sqlOrderDetailCheckout = mysqli_query($connNew,"
    SELECT MAX(dated) as maxdate
    FROM `".FO_RESERVATIONS_DETAILS."`
    WHERE id_fo_reservations = '".$row->id."'
    AND id_mst_room_no_allocation = '".$room_no."'
    AND order_by_room = '".$order_by_room."'
");

$rowOrderDetailCheckout = mysqli_fetch_object($sqlOrderDetailCheckout);

if (empty($rowOrderDetailCheckout->maxdate)) {
    echo "Room stay details not found.";
    exit;
}

/*
IMPORTANT HOTEL LOGIC:
FO_RESERVATIONS_DETAILS.dated = Night date
FO_RESERVATIONS.checkout      = Departure date
So checkout = last night + 1
*/

$last_night   = $rowOrderDetailCheckout->maxdate;
$old_checkout = date('Y-m-d', strtotime($last_night . " +1 day"));

$checkout_timestamp = strtotime($old_checkout);
$extend_timestamp   = strtotime($new_checkout);

/* =========================================================
   =================== EXTEND STAY =========================
   ========================================================= */

if ($extend_timestamp > $checkout_timestamp) {

    $start = strtotime($old_checkout); // first new night
    $end   = $extend_timestamp;

    while ($start < $end) {

        mysqli_query($connNew,"
            INSERT INTO `".FO_RESERVATIONS_DETAILS."`
            (id_fo_reservations, id_shop, id_mst_hotels, id_mst_guest,
             id_mst_room_no_allocation, id_mst_room_no_reserved, plan, id_rate, id_fo_rate_plan,
             dated, id_mst_room_types, room_quantity, adults_per_room,
             child_without_bed, extra_bed_price_per_day_per_room,
             tariff_price_per_day_per_room, food_price_per_day_per_room,
             tax_per_day_per_room, unique_code, id_fo_bill,
             checkin_status, id_fo_folio, id_fo_folio_to, order_by_room, fo_folio_temp, checkin_time, room_availability)

            SELECT id_fo_reservations, id_shop, id_mst_hotels, id_mst_guest,
                   id_mst_room_no_allocation, id_mst_room_no_reserved, plan, id_rate, id_fo_rate_plan,
                   '".date('Y-m-d',$start)."',
                   id_mst_room_types, room_quantity, adults_per_room,
                   child_without_bed, extra_bed_price_per_day_per_room,
                   tariff_price_per_day_per_room, food_price_per_day_per_room,
                   tax_per_day_per_room, unique_code, '0',
                   '0', '0', '0', order_by_room, fo_folio_temp, checkin_time, room_availability
            FROM `".FO_RESERVATIONS_DETAILS."`
            WHERE id_fo_reservations = '".$row->id."'
            AND id_mst_room_no_allocation = '".$room_no."'
            AND order_by_room = '".$order_by_room."'
            ORDER BY dated ASC
            LIMIT 1
        ");

        $start = strtotime("+1 day", $start);
    }

    $message = "Stay Extended Successfully.";
}

/* =========================================================
   =================== REMOVE STAY =========================
   ========================================================= */

elseif ($extend_timestamp < $checkout_timestamp) {

    // delete nights from new_checkout to old_checkout - 1

    $start = strtotime($new_checkout);
    $end   = strtotime($old_checkout);

    while ($start < $end) {

        mysqli_query($connNew,"
            DELETE FROM `".FO_RESERVATIONS_DETAILS."`
            WHERE dated = '".date('Y-m-d',$start)."'
            AND id_fo_reservations = '".$row->id."'
            AND id_mst_room_no_allocation = '".$room_no."'
            AND order_by_room = '".$order_by_room."'
        ");

        $start = strtotime("+1 day", $start);
    }

    $message = "Stay Reduced Successfully.";
}

/* ================= SAME DATE ================= */

else {
    echo "Checkout date unchanged.";
    exit;
}

/* =========================================================
   ================= RECALCULATE TOTALS ====================
   ========================================================= */

$sqlSum = mysqli_query($connNew,"
    SELECT 
        SUM(tariff_price_per_day_per_room) AS tariff,
        SUM(tax_per_day_per_room) AS tax
    FROM `".FO_RESERVATIONS_DETAILS."`
    WHERE id_fo_reservations = '".$row->id."'
");

$sumRow = mysqli_fetch_object($sqlSum);

$subTotal = $sumRow->tariff ?? 0;
$taxTotal = $sumRow->tax ?? 0;
$netTotal = $subTotal + $taxTotal;

mysqli_query($connNew,"
    UPDATE `".FO_RESERVATIONS."` SET 
        checkout = '".$new_checkout."',   
        sub_total = '".$subTotal."',
        total_tax = '".$taxTotal."',						
        net_booking_amount = '".$netTotal."',				
        balance = '".$netTotal."',
        last_modified = '".currenDateTime()."',
        last_modified_by = '".$_SESSION['userId']."'
    WHERE id = '".$reservation_id."'
");

echo $message;

?>