<?php include_once("../../config/auto_loader.php");

$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['id']."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
$db->query($sql);

if($db->num_rows() > 0){
$row = $db->fetch_object();

header('Content-Type: application/json');
echo json_encode($row);
}