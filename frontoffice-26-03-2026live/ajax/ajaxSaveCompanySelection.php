<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['id_fo_folio_to']>0){
	$Message=array();
	$addSql = "   	UPDATE `fo_folio` SET 
				
				`id_bill_to_company` = '".addslashes($_REQUEST['selected_company_id'])."'
				
				";
 $addSql .= "	
				,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
				
				
				WHERE `id` = '".addslashes($_REQUEST['id_fo_folio_to'])."'"
				;
				
	
	
	
	executeSql($addSql);
		$Message['msg']= 'Bill To Company Update Successfully';
							$Message['status']= '1';
							$Message['msg_value']= selectColumn(MST_COMPANY,'name'," WHERE `id` = '".addslashes($_REQUEST['id_fo_folio_to'])."'");
							echo json_encode($Message);
		
}else{
	$Message['msg']= 'Please Select Company';
	$Message['status']= '1';
	$Message['msg_value']= '';
							echo json_encode($Message);
}
	