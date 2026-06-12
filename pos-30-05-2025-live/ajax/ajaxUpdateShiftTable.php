	<?php include_once("../../config/auto_loader.php");
	
//debugData($_REQUEST);

	$returnArray['msg']='';
	$pos_purch_id	=$_REQUEST['ids_purch'];
   
	if(empty($_REQUEST['id_table_shift'])){
		echo '1';
		
		exit;
	}

	if($_REQUEST['id_table_shift']!=''){
	
	
		 $updatePurch = executeSql("UPDATE `".TBL_PURCH."`  SET 
			
			`id_attribute_table` = '".$_REQUEST['id_table_shift']."', 
			
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."'
			where  `id` IN (".$pos_purch_id." ) and `id_attribute_table` = '".$_REQUEST['id_table_selected']."'
		");			


		echo 'select Table changed Successfully';
		//echo json_encode($returnArray);
		//exit;
	}
