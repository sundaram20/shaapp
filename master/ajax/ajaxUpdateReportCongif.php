<?php 
	include_once("../../config/auto_loader.php"); 

	if($_POST['Save']){
		$err = 0;
		if($err==0){
			if($_POST['Save'] == 'Add' && empty($_POST['tname']) || !isset($_POST['tname'])){

				checkUserLevelPermission($_SESSION['userLevel'],TBL_REPORT,'add');

				$deleteQuery = "DELETE FROM ".TBL_REPORT." WHERE  table_name = '".$_POST['id_report_config']."' AND id_shop = ".$_SESSION['shop'];

				//echo $deleteQuery; exit;
				$result = mysqli_query($appConnect, $deleteQuery);

				foreach($_POST['listtable'] as $index => $field){
					foreach($field as $subindex){
						//print_r($field);
						if(isset($_POST['listtable'][$subindex]['display']) && isset($_POST['listtable'][$subindex]['field_label'])){

							$sessionShop = addslashes($_SESSION['shop']);
							$sessionUserId = $_SESSION['userId']; 

							$table_name = $_POST['id_report_config'];
							$field_name = $_POST['listtable'][$subindex]['table_field'];
							$field_label = $_POST['listtable'][$subindex]['field_label'];
							$display_order = $_POST['listtable'][$subindex]['display_order'];
							$display = 1;
							if(isset($_POST['listtable'][$subindex]['default_select'])){
								$default_select = 1;
							}
							else{
								$default_select = 0;
							}
							if(isset($_POST['listtable'][$subindex]['enabled_order'])){
								$enabled_order = 1;
							}
							else{
								$enabled_order = 0;
							}
							//echo $_POST['listtable'][$subindex]['display']." ".$_POST['listtable'][$subindex]['field_label']." ".$default_select ." ".$sessionShop." ".$sessionUserId." ".$table_name." ".$field_name." ";

							$arrFields=array("id_shop" =>$sessionShop,"table_name"=>addslashes($table_name),"field_name"=>addslashes($field_name),"field_label"=>addslashes($field_label),"display"=>addslashes($display),"default_select"=>addslashes($default_select),"display_order"=>addslashes($display_order),"enable_order_by"=>addslashes($enabled_order),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);

							

							$result = insertData(TBL_REPORT,$arrFields);

							
							
						}
					
					}
					
				} 

			}else if($_POST['Save'] == 'Edit' && !empty($_POST['tname']) || isset($_POST['tname'])){
				$deleteQuery = "DELETE FROM ".TBL_REPORT." WHERE  table_name = '".$_POST['tname']."' AND id_shop = ".$_SESSION['shop'];

				//echo $deleteQuery; exit;
				$result = mysqli_query($appConnect, $deleteQuery);

				foreach($_POST['listtable'] as $index => $field){
					foreach($field as $subindex){
						//print_r($field);
						if(isset($_POST['listtable'][$subindex]['display']) && isset($_POST['listtable'][$subindex]['field_label'])){

							$sessionShop = addslashes($_SESSION['shop']);
							$sessionUserId = $_SESSION['userId']; 

							$table_name = $_POST['tname'];
							$field_name = $_POST['listtable'][$subindex]['table_field'];
							$field_label = $_POST['listtable'][$subindex]['field_label'];
							$display_order = $_POST['listtable'][$subindex]['display_order'];
							$display = 1;
							if(isset($_POST['listtable'][$subindex]['default_select'])){
								$default_select = 1;
							}
							else{
								$default_select = 0;
							}
							if(isset($_POST['listtable'][$subindex]['enabled_order'])){
								$enabled_order = 1;
							}
							else{
								$enabled_order = 0;
							}

							$arrFields=array("id_shop" =>$sessionShop,"table_name"=>addslashes($table_name),"field_name"=>addslashes($field_name),"field_label"=>addslashes($field_label),"display"=>addslashes($display),"default_select"=>addslashes($default_select),"display_order"=>addslashes($display_order),"enable_order_by"=>addslashes($enabled_order),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);

							$result = insertData(TBL_REPORT,$arrFields);

							//echo $result;
							
						}
					
					}
					
				} 
			}
			if($_POST['Save'] == 'Add' && $result == true){

				echo "records are inserted";

			}else if($_POST['Save'] == 'Edit' && $result == true){
				echo "records are updated";
			}
		}
	}

/*	if($_POST['Save']){	
		$err = 0;
		if($err==0){
			if($_POST['Save'] == 'Add' && empty($_POST['tname']) || !isset($_POST['tname'])){

				checkUserLevelPermission($_SESSION['userLevel'],TBL_REPORT,'add');

				echo "Add data";
				$deleteQuery = "DELETE FROM ".TBL_REPORT." WHERE  table_name = '".$_POST['id_report_config']."' AND id_shop = ".$_SESSION['shop'];

				//echo $deleteQuery; exit;
				$result = mysqli_query($appConnect, $deleteQuery);

				foreach($_POST['listtable'] as $index => $field){
					foreach($field as $subindex){
						//print_r($field);
						if(isset($_POST['listtable'][$subindex]['display']) && isset($_POST['listtable'][$subindex]['field_label'])){

							$sessionShop = addslashes($_SESSION['shop']);
							$sessionUserId = $_SESSION['userId']; 

							$table_name = $_POST['id_report_config'];
							$field_name = $_POST['listtable'][$subindex]['table_field'];
							$field_label = $_POST['listtable'][$subindex]['field_label'];
							$display = 1;
							if(isset($_POST['listtable'][$subindex]['default_select'])){
								$default_select = 1;
							}
							else{
								$default_select = 0;
							}
							//echo $_POST['listtable'][$subindex]['display']." ".$_POST['listtable'][$subindex]['field_label']." ".$default_select ." ".$sessionShop." ".$sessionUserId." ".$table_name." ".$field_name." ";

							$arrFields=array("id_shop" =>$sessionShop,"table_name"=>addslashes($table_name),"field_name"=>addslashes($field_name),"field_label"=>addslashes($field_label),"display"=>addslashes($display),"default_select"=>addslashes($default_select),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);

							//print_r($arrFields);

							$result = insertData(TBL_REPORT,$arrFields);

							echo $result;
							
						}
					
					}
					
				} 

			}else if($_POST['Save'] == 'Edit' && !empty($_POST['tname']) || isset($_POST['tname'])){
				checkUserLevelPermission($_SESSION['userLevel'],TBL_REPORT,'update');
				foreach($_POST['listtable'] as $index => $field){

					foreach($field as $subindex){
						if(isset($_POST['listtable'][$subindex]['display']) && isset($_POST['listtable'][$subindex]['field_label'])){

							$sessionShop = addslashes($_SESSION['shop']);
							$sessionUserId = $_SESSION['userId']; 
							$table_name = $_POST['tname'];
							$field_name = $_POST['listtable'][$subindex]['table_field'];
							$fieldId = $_POST['listtable'][$subindex]['fieldId'];
							$field_label = $_POST['listtable'][$subindex]['field_label'];
							$display = 1;
							if(isset($_POST['listtable'][$subindex]['default_select'])){
								$default_select = 1;
							}
							else{
								$default_select = 0;
							}
							$arrFields=array("id_shop" =>$sessionShop,"table_name"=>addslashes($table_name),"field_name"=>addslashes($field_name),"field_label"=>addslashes($field_label),"display"=>addslashes($display),"default_select"=>addslashes($default_select),"date_created"=>currenDateTime(),"last_modified"=>currenDateTime(),"id_mst_user_modified_by"=>$sessionUserId,"id_mst_user_created_by"=>$sessionUserId);

							//print_r($arrFields);

							$condition = "table_name='".$_POST['tname']."' AND id = ".$fieldId;

							$result = updateData(TBL_REPORT,$arrFields,$condition);

							//echo $result;
							
						}
					
					}
					
				} 
			}
			if($_POST['Save'] == 'Add' && $result == true){

				echo "records are inserted";

			}else if($_POST['Save'] == 'Edit' && $result == true){
				echo "records are updated";
			}
		}
	} */
?>