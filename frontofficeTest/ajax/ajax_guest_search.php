<?php
include_once("../../config/auto_loader.php");

header('Content-Type: application/json');

$search     = $_GET['search'] ?? '';
$selectedId = $_GET['id'] ?? '';

$where = "status='1' AND id_shop = '".addslashes($_SESSION['shop'])."'";

$data = [];
$addedIds = [];

/* ==============================
   🔥 QUERY 1: SELECTED ID
============================== */
if (!empty($selectedId)) {

    $selectedId = (int)$selectedId;

     $sqlSelected = "SELECT id, guest_reg_no, first_name, last_name, email, city
                    FROM ".TBL_GUEST."
                    WHERE $where AND id = '".$selectedId."' 
                    LIMIT 1";

    $resSelected = mysqli_query($connNew, $sqlSelected);

    if ($row = mysqli_fetch_assoc($resSelected)) {

        $addedIds[] = $row['id'];

        $data[] = [
            "id"   => $row['id'],
            "text" => $row['guest_reg_no'].' - '.$row['first_name'].' '.$row['last_name'].' - '.$row['email'].' - '.$row['city']
        ];
    }
}

/* ==============================
   🔥 QUERY 2: SEARCH / DEFAULT (20)
============================== */
$sql = "SELECT id, guest_reg_no, first_name, last_name, email, city
        FROM ".TBL_GUEST."
        WHERE $where";

if (!empty($search)) {

    $search = addslashes($search);

    $sql .= " AND (
        first_name LIKE '%$search%' OR
        last_name LIKE '%$search%' OR
        email LIKE '%$search%' OR
        guest_reg_no LIKE '%$search%'
    )";
}

$sql .= " ORDER BY id DESC LIMIT 20";

$res = mysqli_query($connNew, $sql);

/* ==============================
   🔥 MERGE (REMOVE DUPLICATE)
============================== */
while ($row = mysqli_fetch_assoc($res)) {

    if (in_array($row['id'], $addedIds)) continue;

    $addedIds[] = $row['id'];

    $data[] = [
        "id"   => $row['id'],
        "text" => $row['guest_reg_no'].' - '.$row['first_name'].' '.$row['last_name'].' - '.$row['email'].' - '.$row['city']
    ];
}

/* ==============================
   🔥 OUTPUT
============================== */
echo json_encode($data);
exit;