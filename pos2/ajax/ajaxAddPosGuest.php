<?php include_once("../../config/auto_loader.php");



$name=$_REQUEST['name'];
$mobile=$_REQUEST['mobile'];
//echo $name;

/*debugData($_REQUEST);

 
exit;*/

if($_REQUEST['name']!='' || $_REQUEST['mobile']!='') {

	$err = 0; 
	/*if(empty($_POST['mobile']) && empty($_POST['name'])){

		$err++;

		$err_mobile = '<font style="color:red;font-weight:normal;" >Please enter user Name or  mobile no .</font>';

	} */
	

//	if($db->num_rows2(selectSql(TBL_POS_GUEST,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `mobile` = '".addslashes($_POST['mobile'])."'",''))){
    	$sql = " SELECT * FROM `".TBL_POS_GUEST."`  WHERE  `id` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') AND `mobile`='".$_REQUEST['mobile']."' ";
			//echo $sql;

		//echo $sql;

			$db->query($sql);
			$numRows= $db->num_rows();

			if($numRows > 0){  
		$err++;

		$err_mobile = '<font style="color:red;font-weight:normal;" >Mobile no all-ready exists in our database.</font>';

	}

	
	if($err == 0){//No error
		

		//	$sql = " SELECT * FROM `".TBL_POS_GUEST."`  WHERE `mobile`='".$_REQUEST['mobile']."' ";
			//echo $sql;

		

		/*	$db->query($sql);
			$numRows= $db->num_rows();

			if($numRows == '0'){  */

			checkUserLevelPermission($_SESSION['userLevel'],TBL_POS_GUEST,'add');
			$addSql = "   	INSERT INTO `".TBL_POS_GUEST."` SET

							
							`name` = '".addslashes(trim($_REQUEST['name']))."',
							`mobile` = '".addslashes($_REQUEST['mobile'])."' ";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '1' ";


			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New POS Guest details has been added sucessfully.';

			//	header("location:manageKot.php?submenu=178&session=22");
				header("Refresh:0");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'POS Guest details has not been saved. Please make corrections below.';
			}
			/*}else{
				$err++;
				$_SESSION['errorMsg'] = 'POS Guest Mobile No Already Exist.';
			}  */
	

		//Update Section Here

		
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'POS Guest details has not been saved. Please make corrections.';
	}
}

