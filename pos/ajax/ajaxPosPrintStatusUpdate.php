<?php include_once("../../config/auto_loader.php");
include_once("../include/pos_function.php");
include_once("../include/function.php");
	
	$id_pos_purch_bill	=	$_REQUEST['id_pos_purch_bill'];
	
	
		$updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
								`printed` = printed+'1'
								
								where   `id`='".$id_pos_purch_bill."' 
						  		");
								
	
								
	$ch5 ="POS BILL Printed " .   date('d-m-Y H:i:s');
	
								
	 $auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes($id_pos_purch_bill)."',
	`tables_name` = 'pos_purch , pos_purch_details',
	`form_code` = 'POS',
	`changes` =  '".addslashes($ch5)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";
	executeSql($auditeditSql);
	//echo json_encode($arrayLi);
/*** printing end ***/


