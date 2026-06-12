<?php include_once("../../config/auto_loader.php");

//echo "<pre>";print_r($_REQUEST);echo "</pre>";
//die;
$start_date	=	$_REQUEST['start_date'];
$end_date	=	$_REQUEST['end_date'];

$DuphotelId = explode(",", $_REQUEST['DuphotelId']);


$DuplicateID	=	explode('|',$_REQUEST['rate_level_id']);

$dupId	=	$DuplicateID['0'];

$rate_category_id	=	$DuplicateID['0'];

$RateSQL = executeSql("SELECT * FROM `".TBL_RATE."` where id='".addslashes($dupId)."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

$RateRecordRow = $db->fetch_object2($RateSQL);

	  $addSql  = "  INSERT INTO `".TBL_CUSTOMER."` SET
				`id_default_group` = '1',
				`id_company` = '".addslashes($_POST['company_id'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`mobile` = '".addslashes($_POST['mobile'])."',
				`type` = '2'";
	  $addSql .= "	,`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`last_modified_by` = '".$_SESSION['userId']."'
				,`status` = '1'";
		
	//executeSql($addSql);
		//$id_contacts= $db->insert_id(); 
		
		
		
		 	 checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'add');

			 $start_date	=	selectColumn(TBL_RATE_SEASON,'start_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");				
			 $end_date		=	selectColumn(TBL_RATE_SEASON,'end_date'," WHERE `id` = '".addslashes($_POST['seasonId'])."'");					
		
	
				$checkExisting = executeSql("SELECT * FROM `".TBL_RATE."` where company_id='".addslashes($_POST['company_id'])."' and rate_level_id='".addslashes($_POST['new_rate_level_id'])."' and seasonId='".addslashes($_POST['seasonId'])."' and market='".addslashes($_POST['market'])."' AND `id_shop` = '".addslashes($_SESSION['shop'])."'");
			if(num_rows($checkExisting)>0){
				
			if($_REQUEST['RateEditId']==''){		
			$err++;
			echo '<p class="help-block">Rate details has been already added for this data.</p>'; 							
			}
			if($_REQUEST['RateEditId']!=''){
			
			if($_REQUEST['HotelDuplicateInsert']==2){ //SELECTED HOTEL INSERT AND RESELECT OTHER HOTEL IN SAME RATE LETTER
			
			
			
			$resultCat 	= $db->fetch_object2($checkExisting);
			
			$rate_id= $resultCat->id;

		foreach($DuphotelId as $value){
			executeSql("DELETE from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes($_REQUEST['RateEditId'])."' and hotel_id='".$value."'");
			executeSql("DELETE from `".TBL_RATE_DETAILS."` where rate_id='".addslashes($_REQUEST['RateEditId'])."' and hotel_id='".$value."'   ");
		}

		foreach($DuphotelId as $value){
				
			
			//echo "SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'";
				
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `fs_rate_assign_details` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
				
			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			
			executeSql("INSERT INTO `fs_rate_assign_details` SELECT * FROM temp_rate_assign");
			$rateAssignId = $db->insert_id();	
							
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
			
			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,  rate_assign_id='".$rateAssignId."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");
		
			executeSql("DROP TEMPORARY TABLE temp_rate_detail");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign");

				}
				
			echo '<p class="help-block">Selected Hotel Rate details has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 2000);</script>'; 		
			}
			
				
		if($_REQUEST['HotelDuplicateInsert']==1){	
					
			$resultCat 	= $db->fetch_object2($checkExisting);

			$rate_id= $resultCat->id;
		
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");
			$rateAssignId = $db->insert_id();	
			
			
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			
			
			$resCat = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".$rate_id."'");
			
			
			while($resultCat = $db->fetch_object2($resCat)){
				
			executeSql("DELETE from `".TBL_RATE_DETAILS."` where rate_id='".addslashes($_REQUEST['RateEditId'])."' and hotel_id='".$resultCat->hotel_id."'   ");

			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,rate_assign_id='".$resultCat->id."' where rate_assign_id=(select id from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes($dupId)."' and hotel_id='".$resultCat->hotel_id."')");
					
			}
			
			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");
			
			executeSql("DROP TEMPORARY TABLE temp_rate_detail");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign");
			
			//echo '<p class="help-block">All Hotel Rate details has been updated sucessfully.</p>';
			
			 
			 echo '<p class="help-block">All Hotel Rate details has been updated sucessfully</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 2000);</script>';	
			 
		}	
				
				
				
			}
				 
					//header("location:manageRateLetters.php");
				
				
					
			
}else{
	
				
			if($_REQUEST['HotelDuplicateInsert']==1){  //ALL HOTEL INSERT
				
//echo "ALL HOTEL INSERT";
				
		
			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				
			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));
			//echo $rateName 	= 'WH'.$newId;
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode.$newId;
			
$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");
			$resultCat 	= $db->fetch_object2($resCat);
		
				$ratePointDetail = json_encode($resultCat);
						$addRate = "    INSERT INTO `".TBL_RATE."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`sub_code` = '01',
										`company_id` = '".addslashes($_POST['company_id'])."',
										`id_contacts` = '".addslashes($_POST['id_contacts'])."',
										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',
										`rate_category_id` = '".addslashes($rate_category_id)."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',							
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										
										`rate_points` = '".addslashes($resultCat->rate_points)."',							
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						$addRate .= "	,`date_created` = '".currenDateTime()."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";					
			executeSql($addRate);
			$rate_id= mysql_insert_id();					
		
		
		
		
			executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");
			$rateAssignId = $db->insert_id();	
			
			
			executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."'");
			
			
			$resCat = executeSql("SELECT * from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".$rate_id."'");
			
			
			while($resultCat = $db->fetch_object2($resCat)){
				
				
						executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,rate_assign_id='".$resultCat->id."' where rate_assign_id=(select id from `".TBL_RATE_ASSIGN_DETAILS."` where rate_id='".addslashes($dupId)."' and hotel_id='".$resultCat->hotel_id."')");
					
					
					}
			
			
			
			
			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");
			
			executeSql("DROP TEMPORARY TABLE temp_rate_detail");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign");
			
			//echo '<p class="help-block">All Hotel Rate details has been updated sucessfully.</p>';
			echo '<p class="help-block">All Hotel Rate details has been updated sucessfully</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 2000);</script>';
			//header("location:manageRateLetters.php");
			}
			
			
	if($_REQUEST['HotelDuplicateInsert']==2){ //SELECTED HOTEL INSERT
				
			//echo "SELECTED HOTEL INSERT";		
			//die;	
			$GetShopShortCode	=	selectColumn(TBL_SHOP,'short_code'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");				
			//$lastRecordRes = executeSql("SELECT AUTO_INCREMENT as maxId FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = '".TBL_RATE."' and TABLE_SCHEMA='".$DB_NAME."'");
			$lastRecordRes = executeSql("SELECT MAX(`rate_name`) as maxId FROM `fs_rate` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."'");
			$lastRecordRow = $db->fetch_object2($lastRecordRes);
			//$newId 		= sprintf("%'03d", ($lastRecordRow->maxId));
			//echo $rateName 	= 'WH'.$newId;
			$mystring 	   = $lastRecordRow->maxId;			
			$newId 		   = preg_replace('/[^0-9-_\.]/','', $mystring);			
			$newId 		   = sprintf("%'03d", ($newId+1));
			$rateName 	   = $GetShopShortCode.$newId;
			
$resCat 	= executeSql("SELECT * from `".TBL_RATE."` where id='".addslashes($dupId)."'");
			$resultCat 	= $db->fetch_object2($resCat);
		
				$ratePointDetail = json_encode($resultCat);
						 $addRate = "    INSERT INTO `".TBL_RATE."` SET 
										`id_shop` = '".addslashes($_SESSION['shop'])."',
										`id_shop_group` = '1',
										`rate_name` = '".addslashes($rateName)."',
										`sub_code` = '01',
										`company_id` = '".addslashes($_POST['company_id'])."',
										`id_contacts` = '".addslashes($_POST['id_contacts'])."',
										`rate_level_id` = '".addslashes($_POST['new_rate_level_id'])."',
										`rate_category_id` = '".addslashes($rate_category_id)."',
										`seasonId` = '".addslashes($_POST['seasonId'])."',							
										`remarks` = '".addslashes($_POST['remarks'])."',
										`market` = '".addslashes($_POST['market'])."',
										
										`rate_points` = '".addslashes($resultCat->rate_points)."',							
										`start_date` = '".addslashes(date('Y-m-d',strtotime($start_date)))."',
										`end_date` = '".addslashes(date('Y-m-d',strtotime($end_date)))."'";
						 $addRate .= "	,`date_created` = '".currenDateTime()."'
										,`last_modified` = '".currenDateTime()."'
										,`last_modified_by` = '".$_SESSION['userId']."'
										,`status` = '1'";					
			executeSql($addRate);
			$rate_id= mysql_insert_id();

			foreach($DuphotelId as $value){
					
						
			$value;
				
					executeSql("CREATE TEMPORARY TABLE temp_rate_assign AS SELECT * FROM `".TBL_RATE_ASSIGN_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
			executeSql("UPDATE temp_rate_assign SET id=NULL , rate_id='".$rate_id."',`date_created` = '".currenDateTime()."',`last_modified` = '".currenDateTime()."',`last_modified_by` = '".$_SESSION['userId']."'");
			executeSql("INSERT INTO `".TBL_RATE_ASSIGN_DETAILS."` SELECT * FROM temp_rate_assign");
			$rateAssignId = $db->insert_id();	
				
					
					
					
				executeSql("CREATE TEMPORARY TABLE temp_rate_detail SELECT * FROM `".TBL_RATE_DETAILS."` WHERE rate_id='".addslashes($dupId)."' and hotel_id='".$value."'");
			
			executeSql("UPDATE temp_rate_detail SET id=NULL , rate_id='".$rate_id."' ,  rate_assign_id='".$rateAssignId."'");
					
			executeSql("INSERT INTO `".TBL_RATE_DETAILS."` SELECT * FROM temp_rate_detail");
		
			executeSql("DROP TEMPORARY TABLE temp_rate_detail");
			executeSql("DROP TEMPORARY TABLE temp_rate_assign");
				
				
				
				}	
			echo '<p class="help-block">Selected Hotel Rate details has been updated sucessfully.</p><script>window.setTimeout(function() {window.location.href = "manageRateLetters.php";}, 2000);</script>'; 
			}
			
			
			
			
			
			
			
		}
?>
