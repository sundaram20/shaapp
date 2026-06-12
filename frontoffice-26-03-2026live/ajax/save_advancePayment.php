<?php 
include_once("../../config/auto_loader.php");

$res_id = $_POST['booking_id'] ?? ""; 
$cash = $_POST['cash_payment'] ?? 0;
$card = $_POST['card_payment'] ?? 0;
$upi = $_POST['upi_payment'] ?? 0;
$company = $_POST['company_payment'] ?? 0;
$cheque = $_POST['cheque_payment'] ?? 0;

$cash_remark = $_POST['cash_remarks'] ?? "";
$card_remark = $_POST['card_remarks'] ?? "";
$upi_remark = $_POST['upi_remarks'] ?? "";
$company_remark = $_POST['company_remarks'] ?? "";
$cheque_remark = $_POST['cheque_remarks'] ?? "";

$company_id = $_POST['id_company_name'] ?? 0;

$total_amount = $cash + $card + $upi + $company + $cheque;

if ($total_amount <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid payment amount"
    ]);
    exit;
}

// --------------------------------------------------
// Function to insert row
// --------------------------------------------------
function insertPayment($res_id, $mode, $id_type, $amount, $remark, $company_id) {
    if ($amount <= 0) return 0; // do not insert zero rows
    $id_user = $_SESSION['userId'];
    $sql = "
        INSERT INTO fo_receipt 
        (id_reservation, id_type, payment_mode, amount, is_advance, remark, doc_date, id_company, `time`, ccredit, date_created, id_mst_user_created_by, id_mst_user_modified_by)
        VALUES
        ('$res_id', '$id_type', '$mode', '$amount', '1', '$remark', CURDATE(), $company_id, CURTIME(), '$mode', NOW(), '$id_user', '$id_user')
    ";
    $result = executeSql($sql);
    global $db;
    if (!$result) {
    echo json_encode([
        "status" => "error",
        "message" => "SQL Error: " . $db->error
    ]);
    exit;
}
    return $db->insert_id();
}

$inserted_ids = [];

$inserted_ids[] = insertPayment($res_id, "CASH", "1", $cash, $cash_remark, "0");
$inserted_ids[] = insertPayment($res_id, "CARD", "2", $card, $card_remark, "0");
$inserted_ids[] = insertPayment($res_id, "UPI", "6", $upi, $upi_remark, "0");
$inserted_ids[] = insertPayment($res_id, "COMPANY", "4", $company, $company_remark, $company_id);
$inserted_ids[] = insertPayment($res_id, "CHEQUE", "5", $cheque, $cheque_remark, "0");

// Filter valid IDs
$inserted_ids = array_filter($inserted_ids);

// Last inserted ID to show in receipt
$last_payment_id = end($inserted_ids);



// --------------------------------------------------
// Response
// --------------------------------------------------
echo json_encode([
    "status"  => "success",
    "message" => "Payment saved",
    "payment_id" => $last_payment_id, // used for print receipt
    "reservation_id" => encryptor(encrypt, $res_id) // used for print receipt
]);

exit;


?>