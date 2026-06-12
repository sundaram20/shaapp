<?php
include_once("../../config/auto_loader.php");


header('Content-Type: application/json');

$room_id = $_REQUEST['rm_id'];
$cur_room_status = $_REQUEST['cur_room_status'];

// Sanitize inputs
$room_id = mysqli_real_escape_string($connNew, $room_id);
$cur_room_status = mysqli_real_escape_string($connNew, $cur_room_status);

// Update query
$cronMasterDetail = "UPDATE `mst_room_no_allocation` 
                     SET `house_keeping_status` = '$cur_room_status' 
                     WHERE `id` = '$room_id'";

$result = mysqli_query($connNew, $cronMasterDetail);

// Define status labels (optional)
$statusLabels = [
    4 => 'Clean',
    2 => 'Maintenance',
	 3 => 'Block',
	 1 => 'Dirty'
];

if ($result) {
    if (mysqli_affected_rows($connNew) > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Room status updated successfully.',
            'house_keeping_status' => $cur_room_status,
            'status_label' => $statusLabels[$cur_room_status] ?? 'Unknown'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'No change made (same status as before).'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . mysqli_error($connNew)
    ]);
}




 ?>