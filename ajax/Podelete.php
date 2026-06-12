<?php include_once("../config/auto_loader.php");

 $others = $_POST["others"];
 $doc_type = $_POST["doc_type"];

 if($others == 'others'){
	 $clicked_id = $_POST["clicked_id"];
	//echo  "DELETE FROM `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";
	
	
if($doc_type == '4'){
	$form_name = "Good Receipt Note";
}else if($doc_type == '5'){
	$form_name = "Purchase Bill";
}else if($doc_type == '12'){
	$form_name = "Direct Purchase";
}else if($doc_type == '8'){
	$form_name = "Debit Note";
}

$pid = selectColumn(TBL_INV_OTHERS_CHARGES_PURCH,'id_inv_purch','WHERE id="'.$clicked_id.'" ');

$data ="Other Charges Item Row Removed ";

//$data = $datas. " Item Row Removed ";

$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($pid)."',
	`tables_name` = 'inv_purch , inv_purch_details',
	`form_code` = '".$form_name."',
	`changes` =  '".addslashes($data)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";					

if($others == 'others'){
		executeSql($auditeditSql);
	}else{
		
	}
	
	  $delSql = "DELETE FROM `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";
	  if(executeSql($delSql)){
	  	$res['delete'] = 1;
	  }else{
	  	$res['delete'] = 2;
	  }
	echo json_encode($res);
	empty($res);
}if($others == 'po'){
	
	$clicked_id = $_POST["clicked_id"];	
//echo "DELETE FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";

if($doc_type == '4'){
	$form_name = "Good Receipt Note";
}else if($doc_type == '5'){
	$form_name = "Purchase Bill";
}else if($doc_type == '12'){
	$form_name = "Direct Purchase";
}else if($doc_type == '8'){
	$form_name = "Debit Note";
}

$item = selectColumn(TBL_INV_PURCH_DETAILS,'id_inv_items','WHERE id="'.$clicked_id.'" ');
$datas = selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$item.'" ');
$pid = selectColumn(TBL_INV_PURCH_DETAILS,'id_inv_purch','WHERE id="'.$clicked_id.'" ');

$data = $datas. "  Item Row Removed ";

$auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($pid)."',
	`tables_name` = 'inv_purch , inv_purch_details',
	`form_code` = '".$form_name."',
	`changes` =  '".addslashes($data)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";	

	if($others == 'po'){
		executeSql($auditeditSql);
	}else{
		
	}

	  $delSql = "DELETE FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes($clicked_id)."'";
	  
	  if(executeSql($delSql)){
	  	$res['delete'] = 1;
	  }else{
	  	$res['delete'] = 2;
	  }

	echo json_encode($res);
	empty($res);
}
?>