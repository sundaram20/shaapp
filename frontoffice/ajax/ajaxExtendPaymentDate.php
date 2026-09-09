<?php
include_once("../../config/auto_loader.php");

$response = array('status' => '0', 'message' => 'Something went wrong.');

$id           = isset($_POST['extend_res_id']) ? (int)$_POST['extend_res_id'] : 0;
$newDateInput = isset($_POST['extend_new_date']) ? trim($_POST['extend_new_date']) : '';

if ($id <= 0 || $newDateInput == '') {
    $response['message'] = 'Invalid request. Reservation ID or new date missing.';
    echo json_encode($response);
    exit;
}

// Convert dd-mm-yyyy -> Y-m-d for storage
$newDate = date('Y-m-d', strtotime(str_replace('/', '-', $newDateInput)));

if ($newDate == '1970-01-01' || $newDate === false) {
    $response['message'] = 'Invalid date format.';
    echo json_encode($response);
    exit;
}

// Fetch current row to validate + get current extend count
$checkSql = "SELECT id, payment_date, payment_extend FROM fo_reservations WHERE id = '".$id."' LIMIT 1";
$checkResult = mysqli_query($connNew, $checkSql);

if (!$checkResult || mysqli_num_rows($checkResult) == 0) {
    $response['message'] = 'Reservation not found.';
    echo json_encode($response);
    exit;
}

$row = mysqli_fetch_object($checkResult);

$updateSql = "UPDATE fo_reservations 
              SET payment_date = '".addslashes($newDate)."', 
                  payment_extend = payment_extend + 1,
                  last_modified = NOW(),
                  last_modified_by = '".addslashes($_SESSION['userId'])."'
              WHERE id = '".$id."'";

$updateResult = mysqli_query($connNew, $updateSql);

if ($updateResult) {

    $response['status']  = '1';
    $response['message'] = 'Payment date extended successfully.';
} else {
    $response['message'] = 'Failed to update: '.mysqli_error($connNew);
}

echo json_encode($response);
exit;