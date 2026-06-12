	<?php include_once("../../config/auto_loader.php");
	
//debugData($_REQUEST);

	
   
	$updatePurch = executeSql("UPDATE `".TBL_ATTRIBUTES."`  SET 			
			`field_category` = ''			
			where  table_name ='shift'
		");
	$updatePurch = executeSql("UPDATE `".TBL_ATTRIBUTES."`  SET 			
			`field_category` = 'default'			
			where  `id`='".$_REQUEST['id_attribute_shift']."' and  table_name ='shift'
		");
