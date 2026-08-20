<?php
include_once("../../config/auto_loader.php");

header('Content-Type: application/json');

$guestId = (int)($_GET['guest_id'] ?? 0);

if ($guestId <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT
            fr.id,
            fr.booking_no,
            fr.checkin,
            fr.checkout,
            mra.room_no
        FROM fo_reservations fr
        LEFT JOIN fo_reservations_details frd
            ON frd.id_fo_reservations = fr.id AND frd.id_mst_guest = '".$guestId."'
        LEFT JOIN mst_room_no_allocation mra
            ON mra.id = frd.id_mst_room_no_reserved
        WHERE fr.id_mst_guest = '".$guestId."'
        GROUP BY fr.id
        ORDER BY fr.checkin DESC";

$res = mysqli_query($connNew, $sql);

$data = [];

while ($row = mysqli_fetch_assoc($res)) {
    $data[] = [
        "booking_no" => $row['booking_no'],
        "checkin"    => !empty($row['checkin']) ? date('d-m-Y', strtotime($row['checkin'])) : '',
        "checkout"   => !empty($row['checkout']) ? date('d-m-Y', strtotime($row['checkout'])) : '',
        "room_nos"   => $row['room_no'] ?: '-'
    ];
}

echo json_encode($data);
exit;