<?php include_once("../config/auto_loader.php");

 $clicked_id = $_POST["clicked_id"];
  
  
  
  
$item = selectColumn(TBL_INV_PURCH_DETAILS,'id_inv_items','WHERE id="'.$clicked_id.'" ');
$datas = selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$item.'" ');
$pid = selectColumn(TBL_INV_PURCH_DETAILS,'id_inv_purch','WHERE id="'.$clicked_id.'" ');

$data = $datas. " Item Row Removed ";

$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($pid)."',
	`tables_name` = 'inv_purch , inv_purch_details',
	`form_code` = 'Store Issue Note',
	`changes` =  '".addslashes($data)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";	

		executeSql($auditeditSql);
	
  
  
  $delSql = "DELETE FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";
  if(executeSql($delSql)){
  	$res['delete'] = 1;
  }else{
  	$res['delete'] = 2;
  }

echo json_encode($res);
empty($res);
?>