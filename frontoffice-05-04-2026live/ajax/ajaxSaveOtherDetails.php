<?php
include_once("../../config/auto_loader.php");

// Sanitize inputs
$guestId   = intval($_POST['guest_id']);
$idFolio   = intval($_POST['id_folio']);
$idRes     = intval($_POST['id_res']);
$cFormNo   = trim($_POST['c_form_number']);
$cFormExp  = trim($_POST['c_form_expiry']);
$arrivalinindia  = trim($_POST['arrival_in_india']);
$purpose   = trim($_POST['purpose_of_visit']);
$arrival   = trim($_POST['arrival_from']);
$departure = trim($_POST['departure_to']);

$sql  = "UPDATE fo_reservations_details 
             SET c_form_no = ?, c_form_expiry = ?, arrival_in_india = ?, purpose_of_visit = ?, 
                 arrival_from = ?, departure_to = ?
             WHERE id_mst_guest = ? AND id_fo_reservations = ?";
    $stmt = $connNew->prepare($sql);
    $stmt->bind_param("ssssssii", $cFormNo, $cFormExp, $arrivalinindia, $purpose, $arrival, $departure, $guestId, $idRes);

if ($stmt->execute()) {
    echo json_encode(['status' => 'success', 'message' => 'Details saved successfully.']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Failed to save. Please try again.']);
}

$stmt->close();
$connNew->close();
?>