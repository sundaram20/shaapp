<?php include_once("../../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
 
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add 
		echo '<pre>';
		print_r($_REQUEST);
		echo '<pre>';
	//die;

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG,'add');
			$addSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG."` SET
							`app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
								
			if(executeSql($addSql)){

				$lastInsertId= $db->insert_id();
				$i=0;
				foreach($_POST['outlet'] as $value){
					
					$addDetailSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG_DETAIL."` SET
								`id_mst_doc_type_config` = '".$lastInsertId."',
								`id_subsection` = '".$_POST['outlet'][$i]."', 							
								`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  							
								`start_no` = '".addslashes($_POST['start_no'][$i])."',  
								`numeric_part` = '".addslashes($_POST['numeric_part'][$i])."', 
								`prefix` = '".addslashes($_POST['prefix'][$i])."',
								`suffix` = '".addslashes($_POST['suffix'][$i])."'
								
								";					
					echo executeSql($addDetailSql);
					$i++;
				}
				unset($_POST);
				$_SESSION['successMsg'] = 'New  Document Type Configuration has been added sucessfully.';
				header("location:managePosDocumentConfig.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
	 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'update');
			 $editSql = "   UPDATE `".TBL_DOC_TYPE_CONFIG."`  SET  
							`app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`start_no` = '".addslashes($_POST['start_no'])."',  
							`numeric_part` = '".addslashes($_POST['numeric_part'])."', 
							`prefix` = '".addslashes($_POST['prefix'])."',
							`suffix` = '".addslashes($_POST['suffix'])."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){  
				    $id_mst_doc_type_config= encryptor(decrypt,$_POST['eId']);
					mysqli_query($connNew,"DELETE FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$id_mst_doc_type_config."'");
					
					
					$i=0;
					foreach($_POST['outlet'] as $value){

					
					$addDetailSql = "   INSERT INTO `".TBL_DOC_TYPE_CONFIG_DETAIL."` SET
								`id_mst_doc_type_config` = '".$id_mst_doc_type_config."',
								`id_subsection` = '".$_POST['outlet'][$i]."', 							
								`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  							
								`start_no` = '".addslashes($_POST['start_no'][$i])."',  
								`numeric_part` = '".addslashes($_POST['numeric_part'][$i])."', 
								`prefix` = '".addslashes($_POST['prefix'][$i])."',
								`suffix` = '".addslashes($_POST['suffix'][$i])."'
								
								";					
					echo executeSql($addDetailSql);
					$i++;
				}
		
				$_SESSION['successMsg'] = selectColumn(TBL_DOC_TYPE_CONFIG, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
 
				header("location:managePosDocumentConfig.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
 

				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_DOC_TYPE_CONFIG,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections.';
	}
}

?>
