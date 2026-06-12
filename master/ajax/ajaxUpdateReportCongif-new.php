<?php 
	include_once("../../config/auto_loader.php"); 

	$deleteQuery = "DELETE FROM report_config WHERE  table_name = '".$_POST['id_report_config']."' AND id_shop = ".$_SESSION['shop'];

	//echo $deleteQuery; exit;
	$result = mysqli_query($appConnect, $deleteQuery);

	foreach($_POST['formdata']  as $index => $field){
		$table_name = $_POST['id_report_config'];
		
			$realIndex = $field['display']; 
			$field_name = $field['table_field']; 
			$field_label = $field['field_label'];
			$display = 1;
			$defaultIndex = $field['default_select']; 

			if (array_key_exists('display', $field) && !empty($field['display']) && array_key_exists('default_select', $field) && !empty($field['default_select'])) {
				$default=1;
				echo " if ";
				echo $field['field_label']." ".$default." de=".$defaultIndex." dis".$field['display']."<br>";
			  }elseif (array_key_exists('display', $field) && !empty($field['display'])  && empty($field['default_select'])) {
			  	echo " else ";
			  	$default=0;
			  	echo $field['field_label']." ".$default." de=".$defaultIndex." dis".$field['display']."<br>";
			  }

			$sessionShop = addslashes($_SESSION['shop']);

			$sessionUserId = $_SESSION['userId']; 

			echo $field_name;
			
			/*$arrFields=array("id_shop" =>$sessionShop,"table_name"=>addslashes($table_name),"field_name"=>addslashes($field_name),"field_label"=>addslashes($field_label),"display"=>addslashes($display),"default_select"=>addslashes($default_select),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);

			//print_r($arrFields);

			$result = insertData(TBL_REPORT,$arrFields); */

		



	}

	if($result>0){
		echo "Records are inserted";
	}

?>