<?php 
include_once("../../config/auto_loader.php");
include_once("../functions/function.php");

header('Content-Type: application/json');

$postData = file_get_contents('php://input');
$requestData = json_decode($postData, true);

$createdFolios = [];

if (!isset($requestData['splitOption']) || !isset($requestData['selectedRows']) || empty($requestData['selectedRows'])) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
    exit;
}

// Grouping logic
if ($requestData['splitOption'] == '2' || $requestData['splitOption'] == '4') {
    $groupedByRoomType = [];
    foreach ($requestData['selectedRows'] as $row) {
        $roomType = $row['roomType'];
        if (!isset($groupedByRoomType[$roomType])) {
            $groupedByRoomType[$roomType] = [];
        }
        $groupedByRoomType[$roomType][] = $row;
    }
} else {
    $groupedByRoomType['GroupWise'] = $requestData['selectedRows'];
}
//debugData($requestData);//die;
// Get latest night audit date
$sqlNightAudit = mysqli_query($connNew, "SELECT max(night_audit_date) as dated FROM night_audit ORDER BY id DESC LIMIT 1");
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$NightAuditDated = date('Y-m-d', strtotime('+1 day', strtotime($rowNightAudit->dated)));

$id_mst_guest = $requestData['id_mst_guest'];
$id_resevation = $requestData['id_resevation'];
$id_fo_bill = $requestData['id_fo_bill'];

$folio_bill_query = mysqli_query($connNew, "SELECT * FROM fo_folio WHERE id_fo_bill = '$id_fo_bill' ORDER BY id DESC");
$folio_bill_result = mysqli_fetch_object($folio_bill_query);
$folio_id = $folio_bill_result->id ?? 0;

$createdFolioIds = [];

foreach ($groupedByRoomType as $groupwise => $datalisting) {

    if (empty($datalisting)) {
        continue; // Skip empty group
    }

    $doc_type = '804';
    $doc_table_name = 'fo_folio';
    $date = date('Y-m-d');
    $id_subsection = '1';
    $id_shop = $_SESSION['shop'];

    // Call doc config
    $docConfig = docTypeConfig($doc_type, $date, $id_subsection, $doc_table_name, $connNew, $id_shop);
    $mdoc_no = $docConfig['prefix'] . $docConfig['po_no'] . $docConfig['suffix'];

    // Check if doc_no already exists (very rare)
    $existing = mysqli_fetch_object(mysqli_query($connNew, "
        SELECT COUNT(*) as count FROM fo_folio 
        WHERE doc_no = '{$docConfig['po_no']}' AND doc_type = '{$doc_type}'
    "));
   /* if ($existing && $existing->count > 0) {
        continue; // prevent duplicate
    }*/

    // Insert fo_folio
    $insertdocConfig = "INSERT INTO fo_folio SET
        id_mst_shops = '{$id_shop}',
        id_mst_guest = '{$id_mst_guest}',
        id_fo_bill = '{$id_fo_bill}',
        id_doc_type_configuration = '{$docConfig['id_doc_type_configuration']}',
        doc_no = '{$docConfig['po_no']}',
        doc_date = '{$NightAuditDated}',
        mdoc_no = '{$mdoc_no}',
        doc_type = '{$doc_type}',
        date_created = '".currenDateTime()."',
        id_mst_user_created_by = '{$_SESSION['userId']}',
        last_modified = '".currenDateTime()."',
        id_mst_user_modified_by = '{$_SESSION['userId']}'";
    mysqli_query($connNew, $insertdocConfig);
    $id_fo_folio = mysqli_insert_id($connNew);

    // Get safe doc_no for fo_bill
    $data = mysqli_fetch_object(mysqli_query($connNew, "SELECT MAX(doc_no) as maxno FROM fo_bill WHERE doc_type = '803'"));
    $doc_no = ($data && $data->maxno != '') ? $data->maxno + 1 : 1;
    $po_no = $docConfig['prefix'] . $doc_no . $docConfig['suffix'];

    // Insert fo_bill
    $insertGrid = "INSERT INTO fo_bill SET
        id_reservations = '{$id_resevation}',
        id_mst_shops = '{$id_shop}',
        folio_no = '{$po_no}',
        id_fo_folio = '{$id_fo_folio}',
        id_fo_folio_to = '{$id_fo_folio}',
        date_created = '".currenDateTime()."',
        id_mst_user_created_by = '{$_SESSION['userId']}',
        last_modified = '".currenDateTime()."',
        id_mst_user_modified_by = '{$_SESSION['userId']}'";
    mysqli_query($connNew, $insertGrid);
    $id_fo_bill_new = mysqli_insert_id($connNew);
    $createdFolioIds[] = $id_fo_bill_new;

    // Update the rows assigned to this new folio
	//debugData($datalisting);
    foreach ($datalisting as $folioRow) {
        $PID = $folioRow['id_table'];
        $tablename = $folioRow['table'];
        $id_owner_room = $folioRow['id_mst_room_no_allocation'];

         $update2 = "UPDATE `{$tablename}` 
                   SET id_fo_folio_to = '{$id_fo_folio}', 
                       id_fo_bill = '{$id_fo_bill_new}' 
                   WHERE id IN ({$PID})";
        mysqli_query($connNew, $update2);
    }

    // Handle parent linking
    if ($requestData['splitOption'] == '2' || $requestData['splitOption'] == '3') {
      
		mysqli_query($connNew, "
            UPDATE fo_folio 
            SET id_parent_folio = '{$folio_id}', 
                id_fo_bill = '{$id_fo_bill_new}' 
            WHERE id = '{$id_fo_folio}'
        ");
    }

    // Set guest based on room
    $id_mst_guest_room = selectColumn('fo_reservations_details', 'id_mst_guest', "
        WHERE id_fo_reservations = '{$id_resevation}' 
        AND id_mst_room_no_allocation = '{$id_owner_room}'"
    );

    mysqli_query($connNew, "UPDATE fo_bill SET id_owner_room = '{$id_owner_room}' WHERE id = '{$id_fo_bill_new}'");
    mysqli_query($connNew, "UPDATE fo_folio SET id_fo_bill = '{$id_fo_bill_new}', id_mst_guest = '{$id_mst_guest_room}' WHERE id = '{$id_fo_folio}'");
	
	
	mysqli_query($connNew, "UPDATE pos_purch_pay SET id_fo_bill = '{$id_fo_bill_new}'  WHERE id_fo_bill = '{$id_fo_bill}'");
	
	

    $createdFolios[] = [
        'id_fo_folio' => $id_fo_folio,
        'mdoc_no' => $mdoc_no
    ];
}

// Final response
$response = [
    'status' => 'success',
    'message' => 'Folio split completed successfully.',
    'splitOption' => $requestData['splitOption'],
    'groupsProcessed' => count($createdFolios),
    'createdFolioIds' => $createdFolioIds,
    'folioCount' => count($createdFolios),
    'folios' => $createdFolios
];

echo json_encode($response);
exit;
?>
