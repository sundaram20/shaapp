<?php
include_once("../../config/auto_loader.php");

$id = $_POST['id'] ?? 0;

if ($id <= 0) exit("Invalid");

$sql = "DELETE FROM fo_receipt WHERE id = '$id'";
executeSql($sql);

echo "success";
?>
