<?php

include_once("../../config/auto_loader.php");

header('Content-Type: application/json');

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT * FROM ".TBL_GUEST." WHERE id = '".$id."' AND id_shop = '".addslashes($_SESSION['shop'])."' LIMIT 1";
$res = mysqli_query($connNew, $sql);
$row = mysqli_fetch_assoc($res);

if (!$row) {
    echo json_encode([]);
    exit;
}

echo json_encode($row);
exit;