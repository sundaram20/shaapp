<?php
include_once("../../config/auto_loader.php");


$room_id = $_REQUEST['rm_id'];
$cur_room_status = $_REQUEST['cur_room_status'];

if ($cur_room_status !== '') {
    // Use prepared statement to prevent SQL injection
    $rmsql = "UPDATE mst_room_no_allocation SET room_status = ? WHERE id = ?";
    
    $stmt = mysqli_prepare($connNew, $rmsql);

    // Bind the parameters
    mysqli_stmt_bind_param($stmt, "ii", $cur_room_status, $room_id);

    // Execute the statement
    mysqli_stmt_execute($stmt);

    // Check if the update was successful
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "Room status changed";
    } else {
        echo "Failed to change room status";
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid room status";
}
 ?>