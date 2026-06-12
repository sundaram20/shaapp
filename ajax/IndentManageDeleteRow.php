<?php include_once("../config/auto_loader.php");

  $clicked_id = $_POST["clicked_id"];
  $submenu = $_POST["submenu"];
  
 // exit;
  
  $next_table = selectColumn('inv_indent_details','id_inv_items'," WHERE `id` = '".addslashes($clicked_id)."'");
  $next_table1 = selectColumn('inv_indent_details','id_inv_indent'," WHERE `id` = '".addslashes($clicked_id)."'");
  $bill_no = selectColumn('inv_indent','mdoc_no'," WHERE `id` = '".addslashes($next_table1)."'");
  $datas = selectColumn('inv_items','name'," WHERE `id` = '".$next_table."'");
  $ids = selectColumn('inv_indent_details','id_inv_indent'," WHERE `id` = '".$clicked_id."'");
  
  $data = $datas. " Item Row Removed ";
 
 // $data = $items ." Details Deleted";
 
 if($submenu=='96'){
	 $form = "Requestion Note";
 }else{
	 $form = "Indent";
 }

$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".$ids."',
	`tables_name` = 'pos_purch , pos_purch_details',
	`form_code` = '".$form."',
	`changes` =  '".addslashes($data)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";					

executeSql($auditeditSql);

 $delSql = "DELETE FROM `".TBL_INV_INDENT_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";
  if(executeSql($delSql)){
  	$res['delete'] = 1;
  }else{
  	$res['delete'] = 2;
  }

echo json_encode($res);
empty($res);
?>