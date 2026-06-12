<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'view');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_INDENT,'add');
			 //Indent No Check Here
			 $doc_no = $_POST['doc_no'];

			 $sql5 = " SELECT * FROM `".TBL_INV_INDENT."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."' and `doc_type` = '2' and `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."' ";
				$db->query($sql5);
				$numRows= $db->num_rows();
					if($numRows > 0)   {
						while($row5 = $db->fetch_object()){ 
							$doc_no= $row5->doc_no; 
							$doc_no = $doc_no+1; 
						} 
					}else{
						 $doc_no = $_POST['doc_no'];
					}

			 //Values Add Here

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$doc_no.''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			//Indent Table Section Here
			$addSql = "   	INSERT INTO `".TBL_INV_INDENT."` SET

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date'])))."',  
							`doc_no` = '".addslashes($doc_no)."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',  
							`id_mst_attributes_department` = '".addslashes($_POST['id_mst_attributes_department'])."', 
							`remarks` = '".addslashes($_POST['remarks'])."',
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

							$lastInsertId= $db->insert_id();

				//Indent Details Table Here Detault Value Set
				$addSql = "   	INSERT INTO `".TBL_INV_INDENT_DETAILS."` SET

							`id_inv_indent` = '".addslashes($lastInsertId)."', 
							`doc_type` = '".addslashes($_POST['doc_type'])."',  
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`alt_qty` = '".addslashes($_POST['alt_qty'])."',  
							`qty` = '".addslashes($_POST['qty'])."',  
							`bal_qty` = '".addslashes($_POST['qty'])."',  
							`alt_unit` = '".addslashes($_POST['alt_unit'])."', 
							`main_unit` = '".addslashes($_POST['main_unit'])."', 
							`remarks_indent_details` = '".addslashes($_POST['remarks_indent_details'])."', 
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

				//Indent Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];

							for($i = 1; $i <= $counter1; $i++){

								if($_POST['id_inv_items'.''.$i] != '' && $_POST['main_unit'.''.$i] !='' ){

									$addSql = "INSERT INTO `".TBL_INV_INDENT_DETAILS."` SET

									`id_inv_indent` = '".addslashes($lastInsertId)."',
									`doc_type` = '".addslashes($_POST['doc_type'])."',   
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
									`qty` = '".addslashes($_POST['qty'.''.$i])."',
									`bal_qty` = '".addslashes($_POST['qty'.''.$i])."',    
									`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
									`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
									`remarks_indent_details` = '".addslashes($_POST['remarks_indent_details'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
							}

			if(1){
				//unset($_POST);addslashes(encryptor(decrypt,$_POST[eId]))."'")
				

				$_SESSION['successMsg'] = 'New  Indent Purhcase Order has been added sucessfully.';
				
				if($_POST['another']!=''){
					header("location:print.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&print=1");	
				}else{
					header("location:print.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1");
				}
				
				
				
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Indent Purchase Order has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'update');

			if($_POST['prefix'] !='' && $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['doc_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			
			
			
			
			
			
			
			
			
		//Audit Trail Section
			 $auditquery = "SELECT * From `".TBL_INV_INDENT."` WHERE id = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";

			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				
				 $idd = $auditrow ->id;
				 $doc_type = $auditrow ->doc_type; 
				 $department = $auditrow ->id_mst_attributes_department; 
				
				 $doc_date =  date('d-m-Y' , strtotime(addslashes($auditrow ->doc_date))); 
				 $remarks = $auditrow ->remarks; 
				 $bill_no = $auditrow ->mdoc_no; 
				}

				if($doc_type != $_POST['doc_type']){
					 $doc_type_s ="Document Type Details Changed from " .  $doc_type." - to - ".$_POST['doc_type'];
				}

				if($department != $_POST['id_mst_attributes_department']){ 
					$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$department."' AND table_name ='".'department'."'");
					$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['id_mst_attributes_department']."' AND table_name ='".'department'."' ");
					$department_s = "Department Details Changed from ". $old_data." - to - " .$new_data ;
				}
				if($doc_date != $_POST['doc_date']){
					
					$indate = date('d-m-Y' , strtotime(addslashes($_POST['doc_date']))) ;
					 $doc_date_s ="Indent Date Details Changed from " .  $doc_date." - to - ".$indate;
				}
				if($remarks != $_POST['remarks']){
					 $remarks_s ="Remarks Details Changed from " .  $remarks." - to - ".$_POST['remarks'];
				}

			//Multiple Data First Rows
				$auditquery = "SELECT * From `".TBL_INV_INDENT_DETAILS."` WHERE id = '".addslashes($_POST['update_id'])."'  ";

			  	$auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				
				 $id_inv_items = $auditrow ->id_inv_items;
				 $qty = $auditrow ->qty;
				 $alt_qty = $auditrow ->alt_qty;
				 $alt_unit = $auditrow ->alt_unit;
				 $remarks_indent_details = $auditrow ->remarks_indent_details;


$next_table = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$id_inv_items."'");
					$next_table1 = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$_POST['id_inv_items']."'");

					$old_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$id_inv_items."'");
					$old_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$id_inv_items."'");
					$old_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$id_inv_items."'");
					$old_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table."'");


				if($id_inv_items != $_POST['id_inv_items']){ 
					$next_table = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$id_inv_items."'");
					$next_table1 = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$_POST['id_inv_items']."'");

					$old_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$id_inv_items."'");
					$old_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$id_inv_items."'");
					$old_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$id_inv_items."'");
					$old_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table."'");
					$new_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$_POST['id_inv_items']."' ");
					$new_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$_POST['id_inv_items']."' ");
					$new_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$_POST['id_inv_items']."' ");
					$new_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table1."' ");
					
				$id_inv_items_s = "Item Code Details Changed from ". $old_data ." | ". $old_data1 ." | ". $old_data2 ." ".$old_data3." - to - " .$new_data ." | ". $new_data1 ." | ".  $new_data3 ." in 1st Row " ;
				}
				if($qty != $_POST['qty']){
					//$qty_s ="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'] ." in 1st Row " ;
					$qty_s ="Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $qty." - to - ".$_POST['qty'] ;
				}
				if($alt_qty != $_POST['alt_qty']){
					//$alt_qty_s ="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'] ." in 1st Row ";
					$alt_qty_s ="Alt Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'] ;
				}
				if($alt_unit != $_POST['alt_unit']){
					//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'] ." in 1st Row " ;
					$alt_unitt ="Alt Unit Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'] ;
				}
				if($remarks_indent_details != $_POST['remarks_indent_details']){
					//$remarks_indent_details_s ="Remarks Intent Details  Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'] ." in 1st Row " ;
					$remarks_indent_details_s ="Remarks Intent Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'];
				}
				
				}

				//Multiple Data Many Rows
				if($_POST['update_count'] == ''){
						$update_count = 0;								
					}else{
						$update_count = $_POST['update_count'];									
					}

				for($i = 1; $i <= $update_count; $i++){
					$val = $i;
					$val = $val + 1;
						$auditquery = "SELECT * From `".TBL_INV_INDENT_DETAILS."` WHERE id = '".addslashes($_POST['update_id'.''.$i])."'  ";

					  	$auditresSQL = mysqli_query($connNew, $auditquery);	
						while($auditrow = mysqli_fetch_object($auditresSQL)){ 
						
						 $id_inv_items = $auditrow ->id_inv_items;
						 $qty = $auditrow ->qty;
						 $alt_qty = $auditrow ->alt_qty;
						 $alt_unit = $auditrow ->alt_unit;
						 $remarks_indent_details = $auditrow ->remarks_indent_details;

							$next_table = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$id_inv_items."'");
							$old_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$id_inv_items."'");
							$old_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$id_inv_items."'");
							$old_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$id_inv_items."'");
							$old_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table."'");


						if($id_inv_items != $_POST['id_inv_items'.''.$i]){ 
							$next_table = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$id_inv_items."'");
							$next_table1 = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."'");

							$old_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$id_inv_items."'");
							$old_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$id_inv_items."'");
							$old_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$id_inv_items."'");
							$old_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table."'");
							//Post Data Section
							$new_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table1."' ");

							//Answer Section
						$id_inv_items_s .="Item Code Details Changed from ". $old_data ." | ". $old_data1 ." | ". $old_data2 ." ".$old_data3." - to - " .$new_data ." | ". $new_data1 ." | ". $new_data3 . " in Row ". $val ." ";
						}
						if($qty != $_POST['qty'.''.$i]){
							// $qty_s .="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " in Row ". $val ." ";
							 $qty_s .=" Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " <br/> ";
						}
						if($alt_qty != $_POST['alt_qty'.''.$i]){
							// $alt_qty_s .="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i] . " in Row ". $val ." ";
							 $alt_qty_s .=" Alt Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i]  . " <br/>";
						}
						if($alt_unit != $_POST['alt_unit'.''.$i]){
							//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " in Row ". $val ." ";
							$alt_unitt = " Alt Unit Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . "  <br/>";
						}
						if($remarks_indent_details != $_POST['remarks_indent_details'.''.$i]){
							// $remarks_indent_details_s .="Remarks Intent Details Details Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'.''.$i] . " in Row ". $val ." ";
							 $remarks_indent_details_s .=" Remarks Intent Details Details in". $old_data ." |". $old_data1 ." |". $old_data3 ." Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'.''.$i] ." <br/> ";
						}
				
					}
				}
				//Insert Data
				if($_POST['counter1'] == ''){
					$counter1 = 0;								
				}else{
					$counter1 = $_POST['counter1'];									
				}

				for($i = $counter1; $i > $update_count; $i--){

					if($_POST['id_inv_items'.''.$i]){ 
							
							$next_table1 = selectColumn('inv_items','id_mst_attributes_group_main'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."'");
							
							//Post Data Section
							$new_data  = selectColumn('inv_items','item_code'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data1 = selectColumn('inv_items','name'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data2 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$_POST['id_inv_items'.''.$i]."' ");
							$new_data3 = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$next_table1."' ");

					//Answer Section
						//$id_inv_items_s .="Requisition Details Insert from ".$new_data ." |". $new_data1 ." |". $new_data2." ". $new_data3 . " ";
						$id_inv_items_s .= $new_data." |". $new_data1 ." |". $new_data3 ." Requisition Details Added  <br/> ";
						}
						if($_POST['qty'.''.$i]){
							// $qty_s .="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " ";
						}
						if($_POST['alt_qty'.''.$i]){
							// $alt_qty_s .="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i] . " ";
						}
						if($_POST['alt_unit'.''.$i]){
							//$alt_unitt .="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " ";
						}
						if($_POST['remarks_indent_details'.''.$i]){
							// $remarks_indent_details_s .="Remarks Intent Details Details Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'.''.$i] . " ";
						}
				
				}	
			
			
			
			
			
			
			
			
			
			
			
			
			//Update Indent Table
			 $editSql = "   	UPDATE `".TBL_INV_INDENT."`  SET  

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date'])))."',  
							`doc_no` = '".addslashes($_POST['doc_no'])."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',  
							`id_mst_attributes_department` = '".addslashes($_POST['id_mst_attributes_department'])."', 
							`remarks` = '".addslashes($_POST['remarks'])."',
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							executeSql($editSql);

				//Update Indent Details
							$editSql = "   	UPDATE `".TBL_INV_INDENT_DETAILS."`  SET  

							`id_inv_indent` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`doc_type` = '".addslashes($_POST['doc_type'])."',   
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`alt_qty` = '".addslashes($_POST['alt_qty'])."',  
							`qty` = '".addslashes($_POST['qty'])."',
							`bal_qty` = '".addslashes($_POST['qty'])."',   
							`alt_unit` = '".addslashes($_POST['alt_unit'])."', 
							`main_unit` = '".addslashes($_POST['main_unit'])."', 
							`remarks_indent_details` = '".addslashes($_POST['remarks_indent_details'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['update_id'])."'";
							executeSql($editSql);
							
							
							
							
 $auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
	`tables_name` = 'inv_intent , inv_intent_details',
	`form_code` = 'Indent',
	`changes` =  '".addslashes($department_s).",".addslashes($doc_date_s).",".addslashes($doc_type_s).",".addslashes($remarks_s).",".addslashes($id_inv_items_s).",".addslashes($qty_s).",".addslashes($alt_qty_s).",".addslashes($alt_unitt).",".addslashes($remarks_indent_details_s)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";					
executeSql($auditeditSql);							
							
							
							
							

				//Update Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								$editSql = "   	UPDATE `".TBL_INV_INDENT_DETAILS."`  SET  

								`id_inv_indent` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
								`doc_type` = '".addslashes($_POST['doc_type'])."',   
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
								`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
								`qty` = '".addslashes($_POST['qty'.''.$i])."',
								`bal_qty` = '".addslashes($_POST['qty'.''.$i])."',   
								`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
								`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
								`remarks_indent_details` = '".addslashes($_POST['remarks_indent_details'.''.$i])."',
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['update_id'.''.$i])."'";
								executeSql($editSql);
							}
				//Update Field More Fields Add Here

							if($_POST['counter1'] == ''){
								$counter1 = 0;								
							}else{
								$counter1 = $_POST['counter1'];									
							}

							for($i = $counter1; $i > $update_count; $i--){

								 
								if($_POST['id_inv_items'.''.$i] != '' && $_POST['main_unit'.''.$i] !='' ){

									$addSql = "INSERT INTO `".TBL_INV_INDENT_DETAILS."` SET

									`id_inv_indent` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
									`doc_type` = '".addslashes($_POST['doc_type'])."',  
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
									`qty` = '".addslashes($_POST['qty'.''.$i])."', 
									`bal_qty` = '".addslashes($_POST['qty'.''.$i])."',  
									`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
									`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
									`remarks_indent_details` = '".addslashes($_POST['remarks_indent_details'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
							}
								
			if(1){  

				$_SESSION['successMsg'] = selectColumn(TBL_INV_INDENT, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';

				if($_POST['another']!=''){
					header("location:print.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&print=1");	
				}else{
					header("location:print.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1");
				}

				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_INDENT,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Indent Purhcase Order has not been saved. Please make corrections.';
	}
}
// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_INDENT."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' and `doc_type` = '2' ";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>


<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style>
	.select2-container{
		width:100%!important;
	}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
	 	
   <?php  $session=$_GET['submenu'];
   
  // echo encryptor(decrypt,$_REQUEST['eId']);
   ?>
    <section class="content-header">
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>-->
        <h5 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no; ?> </span></h5>
      <?php echo breadCrumbs(); ?>
    </section>
	
	
    <!-- Main content -->
    <section class="content">
	<hr class="br-line">		
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
		  
		  
	<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-head" >
           <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" >Alteration History</label>
            </div>
            <div class="modal-body" class="alt-body">
                <table class="table table-striped table-bordered dataTable no-footer">
				<div class="alt-bill"> Bill No - <?php echo $row->mdoc_no ?> </div>
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn c-btn" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button> 
            </div>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->		


         
           
			 <div class="nav-tabs-custom mb-0 shadow-none">
		 
			<!--<div class="box-header with-border">
                <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span>
            </div>-->
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
				
				<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']) ?>" name="indent_id" id="indent_id">
				
				<input type="hidden" value="<?php echo $_GET['submenu'];?>" name="submenu" id="submenu" />
				
				<input type="hidden" value="<?php echo $_GET['session'];?>" name="session" id="session" />
				
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 
					 	

              <div class="box-body">

              	<div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Indent  Request</h5>
              		</div> -->
              		

	              	<div class="row">	

	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Document Type</label>
	              			
	              			
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()">	                	  		                  	  
			                  	 	<option selected="selected" value="2">Indent Purchase Order</option>  
			                  	</select>	 
	              			<?php 
	              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_doc_type_configuration."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 
									} 
	              			?>
	              		
	              			
 
	              		</div>  
	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Indent Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
							
								
		                  	<input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Indent Date" id="doc_date" name="doc_date" value="<?php if($_POST) echo $_POST['doc_date'];elseif($row->doc_date!='') echo date('d-m-Y',strtotime($row->doc_date));else echo date('d-m-Y'); ?>" onclick="hideandshow()"> 
		                  	</div>
	              		</div>


		                <div class="form-group col-xs-12 col-md-2 col-sm-6 p-0">
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 /*#ind{
	              				 	display: none;
	              				 }*/
	              			</style>
	              			<?php } ?>

	              			<div class=" col-xs-12 col-md-12 col-sm-12" >
		              				<label for="name">Indent No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				 <?php	$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object())

										?>
		              				<input type="text" class="form-control" placeholder="Enter Indent No" id="mdoc_no2" name="mdoc_no2" value="<?php echo stripslashes($row->mdoc_no);?>" readonly>
		              				</div> 
			                	</div>
	              			<div id="ind" name="ind" style="display:none;">
	              				<div class=" col-xs-6 col-md-4 col-sm-6" style="display:none;">
	              					<label for="name">Prefix</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
		              				</div>
			                	</div>
		              			<div class=" col-xs-6 col-md-4 col-sm-6" style="display:none;">
		              				<label for="name">Indent No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Indent No" id="doc_no" name="doc_no" value="<?php if($_POST) echo $_POST['doc_no'];else echo stripslashes($row->doc_no);?>" readonly> 
		              				</div>
			                	</div>
			                	<div class=" col-xs-12 col-md-4 col-sm-12" style="display:none;">
			                		<label for="name">Suffix</label>
			                		<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-right"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Suffix" id="suffix" name="suffix" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($suffix);?>" readonly>
		              				</div> 
			                	</div>
			                </div>
			                <?php if($row->id ==''  || $prefix != ''){ 
			                	$mdocRequired='';

			                	?>
			                  <style type="text/css">
			                  	#hideandshow{
			                  		display: none;
			                  	}

			                  </style>
		              	  	<?php }else{$mdocRequired='data=data-parsley-required';} ?>
		                  	<div id="hideandshow" name="hideandshow">
				                <div class="form-group col-xs-12 col-md-12 col-sm-6">
				                  <label for="name">Manual Indent No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input <?php echo $mdocRequired;?>  type="text" class="form-control" placeholder="Enter Manual Indent No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		          

	              		<div class="form-group col-xs-12 col-md-2 col-sm-6" >
	              			<label for="name">Department <font color="#FF0000">*</font></label>
	              			
	              			
	              			<?php $categoryDropDown = '<select class="form-control select2" 
							id="id_mst_attributes_department"
	              			name="id_mst_attributes_department" data-parsley-required data-parsley-errors-container="#outletError">
									<option value="">Select Department</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'department'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_department'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_department == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>  <span id="outletError"></span>
						<?php echo $err_deparment;?>
						
		                  	 
	                  </div>

		                <div class="form-group col-xs-12 col-md-2 col-sm-6">
		                  <label for="name">Remarks</label>
		                  	<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="far fa-comment-alt"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row->remarks);?>"> 
		              		</div>
		                </div> 
 

			            <div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>

		            <div class="row">
	              	</div> 

		        </div>
		      
		        <!-- The Modal -->
				<div class="modal" id="config_model">
				    <div class="modal-dialog modal-wd95" >
				      <div class="modal-content"  >
				      
				        <!-- Modal Header -->
				       
				        <!-- Modal body -->
				        <div class="modal-body ox-sl">
				        	<div class="d-flex justify-content-space-between">
				        	 <!-- <h6 class="modal-title">User/Production Indent Status</h6>-->
				             
				        	<input type="text" id="myInput" class="p-5 br-grey search-wid" onkeyup="myFunction()" style="" placeholder="Search For Item Name" title="Type In Item Name">
				        </div>
							
							<h6 style="color:#172635">Suggestion = (Requirment+Min Stock Level) - (Stock In Hand+Po Balance+Indent Balance) </h6>
							
				        	<table id="myTable2" border="1" class="table table-striped   table-responsive table-bordered dataTable no-footer max-h2" style="background:#fff;width:1226px;display: table-caption;">
				            <thead>
				                <tr style="text-align: center; font-size: 14px;">
				                    <th class="wd-5">S.NO</th>
				                    <th  class="wd-10">Item Code</th>
				                    <th class="wd-15">Description</th> 
				                    <th  class="wd-10">Requirment</th> 
				                    <th class="wd-10">Stock In Hand</th> 
				                    <th class="wd-10">Min Stock Level</th> 
				                    <th class="wd-10">PO Balance</th> 			                    
				                    <th class="wd-10">Indent Balance</th> <!-- pending -->
				                    <th class="wd-10">Suggestion</th> 
				                    <th class="wd-10">Indent Qty</th>  
				                </tr>
				            </thead>
				            <tbody id="indent_help">
				        	<?php 
				        		//help data will come here after ajax call
				        	?> 
				        	</tbody>
				        	</table>
				        </div>
				        
				        <!-- Modal footer -->
				        <div class="modal-footer">
				        	<button type="button" class="btn o-btn"  data-dismiss="modal" onclick="indent_po();"><i class="fa fa-plus-circle" aria-hidden="true" ></i> Create Indent </button>
				          <button type="button" class="btn c-btn" data-dismiss="modal" onclick="dismiss()"><i class="far fa-window-close" aria-hidden="true"></i> Close</button>
				        </div>
				        
				      </div>
					</div>
				</div>
		         <div class="box-body  table-responsive2">
                	<button type="button" style="float:right;" id="config_button" name="config_button" class="btn o-btn" data-toggle="modal" data-target="#config_model"><i class="fa fa-check-square-o"></i> Indent Help
    						</button> 

              	<div class="card text-dark bg-light">
              			
              		
	              	<div class="row">
	              		<?php 
              				$sql2 = " SELECT * FROM `".TBL_INV_INDENT."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'2'."' ";
							$db->query($sql2);  
							$numRows= $db->num_rows();

								if($numRows != 0){
									while($row2 = $db->fetch_object()){ 
										$id_indent_id= $row2->id; 
										$id_indent_id = $id_indent_id + 1; 
									}
								}
								else{
									 $id_indent_id = '1'; 
								}
              			?>	
	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;" >
		                  <label for="name"></label>
		                  <input type="text" class="form-control" id="id_inv_indent" name="id_inv_indent"  value="<?php echo $id_indent_id; ?>" > 
		                </div>
		            </div>
             
		            <div class="row">
		            	<hr class="br-line">
              		<div class="text-center ">
              			<h6  class="tb-heads">Indent  Details</h6>
              		</div>  
		            	<table id="myTable1" class="table table-striped table-bordered dataTable no-footer order-list1 max-h2">
				            <thead>
				                <tr>
				                    <th>Item Code</th>
				                    <th>Item Description</th> 
				                    <th>Qty</th> 
				                    <th>Unit</th> 
				                    <th>Alt.Qty</th> 
				                    <th>AltUnit</th> 				                    
				                    <th>Remarks</th> 
				                </tr>
				            </thead>
				            <tbody>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_INDENT_DETAILS."` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items;
							 		 $array['alt_qty'.''.$i] = $rowsID->alt_qty;
							 		 $array['qty'.''.$i] = $rowsID->qty;
							 		 $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		 $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['remarks_indent_details'.''.$i] = $rowsID->remarks_indent_details;
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0 || $_REQUEST['eId']==''){
								 	$k='';
								 	//$j=1;
								 }else{
								 	$k = $j;
								 }
				            	?>
				            	<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;"  >
				                  <label for="name">Update Id</label>
				                  <input type="text" class="form-control" id="update_id<?php echo $k;?>" name="update_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

				                  <label for="name">Update Count</label>
				                  <input type="text" class="form-control" id="update_count" name="update_count" value="<?php echo $k;?>"> 
				                </div>
				                <tr>
				                	<td class="form-group col-xs-12 col-md-3 col-sm-2"> 
					                 	<input hidden id="select<?php echo $k;?>" name="select<?php echo $k;?>">
					                 <select class="form-control select2" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" onchange="itemget(this.id)" data-parsley-required data-parsley-errors-container="#outletError2">
										<?php $categoryDropDown = '<option value="">Select Item Code</option>';
										
									$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both','Other Items') AND id_shop = ".$_SESSION['shop'] ." ";
										$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
										
											while($sqlRow = mysqli_fetch_object($QuerySQL1)){
										        $list = $sqlRow->id;
												$string .= $list.',';
											}
									$item_list = rtrim($string,',');										
										
											 						 
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE  mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_inv_items'] == $row1->id){
														$selected = 'selected="selected"';
														$conversion_qty_val=$row1->conversion_qty;
													}elseif($array['id_inv_items'.''.$j] == $row1->id){
														$selected = 'selected="selected"';
														$item_description =  $row1->name;
														$conversion_qty_val=$row1->conversion_qty;
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name.' | '.$row1->field_value).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select><span id="outletError2"></span>'; 
										?> 
					                </td> 
				                    <td class="form-group col-xs-12 col-sm-2">
				                        <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"  value="<?php if($_POST) echo $_POST['item_description'];else echo stripslashes($item_description); ?>" readonly />
				                    </td> 
				                    <td class="form-group col-xs-12 col-sm-1"> 
				                        <input type="text" data-parsley-required autocomplete="off"  name="qty<?php echo $k;?>" id="qty<?php echo $k;?>" placeholder="Qty" onkeyup="qtycalc(this.id)" class="form-control discountvalue" value="<?php if($_POST) echo $_POST['qty'];else echo stripslashes($array['qty'.''.$j]); ?>" required />
				                    </td>
				                    <td class="form-group col-xs-12 col-sm-1"> 
				                        <input type="text"  autocomplete="off"  name="main_unit<?php echo $k;?>" id="main_unit<?php echo $k;?>" placeholder="Unit"  class="form-control" readonly value="<?php if($_POST) echo $_POST['main_unit'];else echo stripslashes($array['main_unit'.''.$j]); ?>"/>
				                    </td>
									
				                    <input type="hidden" value="<?php echo $conversion_qty_val;?>" name="conversion_qty<?php echo $k;?>"  id="conversion_qty<?php echo $k;?>">


				                    <td class="form-group col-xs-12 col-sm-1">
				                        <input type="text" data-parsley-required  autocomplete="off"  name="alt_qty<?php echo $k;?>" id="alt_qty<?php echo $k;?>" placeholder="Alt Qty" onkeyup="altqtycalc(this.id)" class="form-control discountvalue" value="<?php if($_POST) echo $_POST['alt_qty'];else echo stripslashes($array['alt_qty'.''.$j] ); ?>" />
				                    </td>
				                    <td class="form-group col-xs-12 col-sm-1"> 
				                        <input type="text"  autocomplete="off"  name="alt_unit<?php echo $k;?>" id="alt_unit<?php echo $k;?>" placeholder="Alt Unit"   class="form-control" readonly value="<?php if($_POST) echo $_POST['alt_unit'];else echo stripslashes($array['alt_unit'.''.$j]); ?>" />
				                    </td>				                    
				                    <td class="form-group col-xs-12 col-sm-3"> 
				                        <input type="text"  autocomplete="off"  name="remarks_indent_details<?php echo $k;?>" id="remarks_indent_details<?php echo $k;?>" placeholder="Remarks"  class="form-control" value="<?php if($_POST) echo $_POST['remarks_indent_details'];else echo stripslashes($array['remarks_indent_details'.''.$j]); ?>"/>
				                    </td>
				                    <?php if($k>=1){?>
				                    <td> 

					                 <a  class="btn n-btn abtn ibtnDel2" style="cursor:pointer;" title="Delete" id="<?php echo $array['id'.''.$j]; ?>"  name="<?php echo $array['id'.''.$j]; ?>"><i class="fa fa-trash-o"></i></a>
				                    </td>
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				                	 <td class="form-group col-xs-12 col-sm-2"><a class="deleteRow"></a></td>
				                </tr> 
				            	<?php } ?>
				            	<input type="text" name="counter1" id="counter1" value="<?php echo $k; ?>" hidden=""> 
				            </tbody>

				            <tfoot>
				                <tr> 
									<td colspan="7" style="text-align: left;">
										<!-- <input  type="button" class="btn btn-sm btn-block" id="addrow1" value="Add More" /> -->
										   <hr class="hr-m">
										<a type="button" class="btn n-btn btn-block"  id="addrow1"  value="Add More" > <span><i class="fa fa-plus"></i> Add Row</span> </a>
									</td> 
				                </tr>
				                <tr>
				                </tr>
				            </tfoot>
				        </table>
		            </div>
		        </div>            		 
		            
		        <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
		         <div class="row" style="display:none"> 	            	
						<div class="form-group col-xs-12 col-md-6 col-sm-2"> 
		                	<label for="status">Status : </label> 
			                <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                name="status" id="status" /> Active
							<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
							name="status"  id="status"   /> Inactive
							 <?php echo $err_status;?>
							 
		                </div>  
		            </div> 

		        </div>
		     
  <!-- /.box-body -->	
   <hr class="br-line mb-10">     
			 <div class="box-footer br-none p-0">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn" name="Save"  >
				
			   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("manageIndentPO.php?submenu=<?php echo $_GET["submenu"] ?>&session=<?php echo $_GET['session']?>"); '><i class="far fa-window-close" aria-hidden="true"></i> Close</a>
			   
			 <!--<input type='button' value='Another' class="btn btn-success"  onclick="saveornot();"> -->
			   
			 <!--  <a href="editIndentPO.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Indent</i></a> -->
				    
			   
			  <!-- <?php/* if($_REQUEST['print'] == 1){ ?>
			      
				
				   <a href="print.php?eId='<?php echo $_GET['eId']; ?>'&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu'] ?>&action=edit&page=<?php $_REQUEST['page']?>"   class="btn btn-success"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a>  
				   
				    
	        	<?php //} */?>-->

              			
              			
              		
			 </div>

				<?php if($row->date_created){?>
					<div class="row mt-10">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 

									<a type='button' value='Alteration History' class="btn o-btn" onclick="audittrial(this.value);" style="float:right"> <i class="fas fa-history"></i> Alteration History</a>

				<?php } ?>  
				  
         	</div>
			
			
	

	<!-- Another Modal -->
<div class="modal fade" id="anotherModal" tabindex="-1" role="dialog" aria-labelledby="anotherModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" >Alteration History</label>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
				
				<div style="text-align:center;font-weight:600;font-size:15px"> 
				<div id="mge"></div>
				
				<br/>
					<input type='submit' value="<?=($_REQUEST['eId']==''?'Add':'Edit')?>" class="btn c-btn" onclick="yes();" name="Save"  >
					<input type='button' value="No" class="btn n-btn" onclick="nosave();" name="no"  >
					<input type='hidden' value="" id="another" name="another"  >
				</div> 
				
			</table>
            </div>
        </div>
    </div>
</div>
<!-- End Another Modal -->  		
			
             
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	

 <?php

											 $sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both','Other Items') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												$list2=array();
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list2[] = $sqlRow->id;
														//$string .= $list.',';
													}	$uarray =array_unique($list2);
													
													
													$input = array_map("unserialize", array_unique(array_map("serialize",$list2)));
											$item_list2 = implode(',',$input);										
	
	                									 						 
							                   
											 	 ?>		
<?php include_once("../includes/footer.php");?> 

<script type="text/javascript">

function saveornot(){
		var id_inv_items = document.getElementById("id_inv_items").value;
		var id_mst_attributes_department = document.getElementById("id_mst_attributes_department").value;
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		
		
		var eId = document.getElementById("eId").value;
		
		if(eId==''){
			 $("#mge").html("You want to Add the Current Records ?");
			//document.getElementById("mge").value = "You want to Add the Current Records ?";
		}else{
			 $("#mge").html("You want to Save the Current Changes of the Records ?");
			//document.getElementById("mge").value = "You want to Save the Current Changes of the Records ?";
		}
		
		if(id_mst_attributes_department == '' && id_inv_items==''){
			window.location.href="editIndentPo.php?submenu="+submenu+"&session="+session;
		}else{
			//alert();
			$('#anotherModal').modal('show');
		}
	}	
	
	function yes(){
		
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		
		$('#anotherModal').modal('hide');
		document.getElementById("another").value = "Another";
		
		//window.location.href="editIndentPo.php?submenu="+submenu+"&session="+session;
	}
	
	
	
	function nosave(){
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		//alert(session)
		window.location.href="editIndentPo.php?submenu="+submenu+"&session="+session;
	}
		


$('document').ready(function(){
	//alert();
		$('#doc_date').click();
});

	$( document ).ready(function() {
 		var dates = '<?php echo ($doc_date!=""?date("d-m-Y",strtotime($doc_date)):date("d-m-Y")); ?>'; 
		//$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });
		$('.dates').datepicker({ dateFormat: "dd-mm-yy"});
		//Button hide 
		 
	});
	//Popup Window Data Get Here
	function indent_po(){
		
		var wcounts = document.getElementById("wcounts").value;
		var counter1 = document.getElementById("counter1").value; 
		var item_description = document.getElementById("item_description").value; 

		if(counter1 == 0){
			var loopcounting = 0;
		}else{
			var loopcounting = counter1;
		} 
		var count =0;
		 
		for(var i = 1; i <= wcounts; i++){

			var windent_qty = $("#windent_qty"+i).val();
			
			//alert(windent_qty);

			if(windent_qty != '' && windent_qty != '0' && windent_qty != undefined && loopcounting == 0){
				
				//Widnow Form Date Get Here
				//alert(windent_qty);
				var wid = document.getElementById("wid"+i).value;  
				var witem_description = document.getElementById("witem_description"+i).value; 
				var wmain_unit = document.getElementById("wmain_unit"+i).value; 
				var walt_unit = document.getElementById("walt_unit"+i).value; 
				//Table Row Date Fetch Here 
				
				$("#id_inv_items").val(wid).change();
				document.getElementById("item_description").value = witem_description;
				document.getElementById("qty").value = windent_qty;
				document.getElementById("main_unit").value = wmain_unit;
				document.getElementById("alt_unit").value = walt_unit;

				// Quantity Calcualtion of Alt Quantity

				if(wmain_unit == walt_unit){
					var qty = windent_qty;
					var kg = 1; 
					var grams = qty * kg;  
					document.getElementById("alt_qty").value = grams;
				}else{
					var qty = windent_qty;
					var kg = 1000; 
					var grams = qty * kg;  
					document.getElementById("alt_qty").value = grams;
				}
				count = count + 1;
				//Form Data Empty
				document.getElementById("windent_qty"+i).value = '';
				loopcounting = loopcounting + 1;  
			}else if(windent_qty != '' && windent_qty != '0' && windent_qty !=undefined){ 
				// //Button Click Here
				if(count != 0){
				 	document.getElementById('addrow1').click();
				}
				 var counter1 = document.getElementById("counter1").value; 
				//Widnow Form Date Get Here
				var wid = $("#wid"+i).val();  
				var witem_description = $("#witem_description"+i).val();  
				var wmain_unit = $("#wmain_unit"+i).val(); 
				var walt_unit = $("#walt_unit"+i).val(); 
				//Table Row Date Fetch Here 
				$("#id_inv_items"+counter1).val(wid).change();
				document.getElementById("item_description"+counter1).value = witem_description;
				document.getElementById("qty"+counter1).value = windent_qty;
				document.getElementById("main_unit"+counter1).value = wmain_unit;
				document.getElementById("alt_unit"+counter1).value = walt_unit;

				// Quantity Calcualtion of Alt Quantity

				if(wmain_unit == walt_unit){
					var qty = windent_qty;
					var kg = 1; 
					var grams = qty * kg;  
					document.getElementById("alt_qty"+counter1).value = grams;
				}else{
					var qty = windent_qty;
					var kg = 1000; 
					var grams = qty * kg;  
					document.getElementById("alt_qty"+counter1).value = grams;
				}

				//Form Data Empty
				$("#windent_qty"+i).val('');
				loopcounting = loopcounting + 1;
				count = count + 1;

			}else{

			}
		}
	}
</script>


<script type="text/javascript">

	$('#config_button').click(function(){
		let id_department = $("#id_mst_attributes_department").val();
		if(id_department==''){
			alert("Please select department");
			$('#config_model').modal('show');
		}
		else{
			$.ajax({
				url:'ajax/fetchIndentHelpList.php',
				type:'POST',
				data:'id_department='+id_department,
				success:function(data){
					//console.log(data);
					$("#indent_help").html(data);
				}
			});
		}
	});
 

	//Delete Row Section Here
	 

	$("table.order-list1").on("click", ".ibtnDel2", function (event) { 
		$(this).closest("tr").remove(); 
		var clicked_id = $(this).attr("id");
var submenu = document.getElementById("submenu").value;
			$.ajax({
				type: "POST",
				url: "../ajax/IndentManageDeleteRow.php",
				//data:{clicked_id:clicked_id,submenu:submenu},
				data:'clicked_id='+clicked_id+'&submenu='+submenu,
				success: function(data){
					var mydata = JSON.parse(data);  
					if(mydata['delete'] == 1){      
					} 
				}
			});     
                      
    });

	function hideandshow() {
		
		var doc_type = document.getElementById("doc_type");
	    var doc_type = doc_type.options[doc_type.selectedIndex].value;

	    var doc_date = document.getElementById("doc_date").value; 
		
		// alert(doc_date);
		if(doc_type != '' && doc_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/IndentManage.php",
				data:{doc_type:doc_type, doc_date:doc_date},
				success: function(data){
					var mydata = JSON.parse(data);
				
					if(mydata['method'] == 1){
						$('#hideandshow').hide();   
						$('#ind').show();   
						<?php if($row->id == ''){?>
								$("#mdoc_no2").val( mydata['prefix']+mydata['doc_no']+ mydata['suffix']);
							document.getElementById("doc_no").value = mydata['doc_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = mydata['prefix'];
						document.getElementById("suffix").value = mydata['suffix'];

					}else{
						$('#hideandshow').show();
						$('#ind').hide(); 
						<?php if($row->id == ''){?> 
							//document.getElementById("doc_no").value = mydata['doc_no'];
							//document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = '';
						document.getElementById("suffix").value = '';
					}
				}
			});
		}
	} 

</script>
<?php 
	$sql2 = " SELECT max(doc_date) as doc_date FROM `".TBL_INV_INDENT."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'2'."' ";
		$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$doc_date= $row2->doc_date;
				$doc_date = date('d-m-Y' , strtotime(addslashes($doc_date)));  
			}  
			if($row->id != '' && $_REQUEST['print'] == 1){ 

?>
<script type="text/javascript">
	var eid = '<?php echo $_GET["eId"]; ?>';  
</script>

	<button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display: none;">
    </button>
	<!-- The Modal -->
	<div class="modal" id="myModal">
	    <div class="modal-dialog">
	      <div class="modal-content" style="margin-top: 50%; width: 70%;margin-left: 20%;">    	         
	        
	        <!-- Modal body -->
	        <div class="modal-body">
	        	<center>
	          <a href="editIndentPO.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" type="button" class="btn btn-success" id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Indent PO </i></a> 
			  
			  <!--<a href="print.php?eId=<?php echo $_GET['eId']; ?>&submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?php $_REQUEST['page']?>"  type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> -->
			  
	       <!--   <a href="print.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>" target="_blank" type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> -->
			  
	          <button type="button" class="btn n-btn" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button> 
	          <a href="manageIndentPO.php?submenu=<?php echo $_GET["submenu"] ?>&session=<?php echo $_GET['session']; ?>" type="button" class="n-btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>   
        	</center>
	        </div> 
	        
	      </div>
		</div>
	</div>
	<script type="text/javascript">
		//document.getElementById('button').click();
	</script>

<?php } ?>

 <script type="text/javascript">
 	
 	


//Select 2  Resolve Here

	var counter1 =  document.getElementById("counter1").value;  

	 

    $("#addrow1").on("click", function () { 	
		
        $('#config_button').click();
        counter1++;        

        var newRow1 = $("<tr>");
        var cols1 = ""; 
        cols1 += '<td><select onchange="itemget(this.id)" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" class="form-control select3"  style="width: 100%;"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN (".$item_list2.") and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'  ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.addslashes($row1->name).' | '.addslashes($row1->field_value) ?></option> <?php } 
                  	?></select> </td>'; 
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';

		cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue" onkeyup="qtycalc_rows(this.id)"  name="qty' + counter1 + '" id="qty' + counter1 + '" required /></td>';  

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Unit" class="form-control" name="main_unit' + counter1 + '" id="main_unit' + counter1 + '" readonly=""/></td>'; 

        cols1 += '<td><input onkeyup="altqtycalc_rows(this.id)" type="text"  autocomplete="off" placeholder="Alt Qty" class="form-control discountvalue" name="alt_qty' + counter1 + '" id="alt_qty' + counter1 + '"/></td>'; 

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control" name="alt_unit' + counter1 + '" id="alt_unit' + counter1 + '" readonly=""/>';   
        
        cols1 += '<input type="hidden" class="form-control"   name="conversion_qty' + counter1 + '" id="conversion_qty' + counter1 + '" /></td>';       

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Remarks" class="form-control" name="remarks_indent_details' + counter1 + '" id="remarks_indent_details' + counter1 + '"/></td>'; 		  
		cols1 += '<td><a class="btn n-btn abtn ibtnDel1" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>'; 
		document.getElementById("counter1").value = counter1;  

		newRow1.append(cols1);
        $("table.order-list1").append(newRow1); 
          $(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) {
        $(this).closest("tr").remove();                
    });    

</script>

<script type="text/javascript">

	
	function itemget(clicked_id) {

			var id_inv_items = $("#id_inv_items").val();
		    //var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value; 

		   // var regex = /[+-]?\d+(?:\.\d+)?/g;
		    var match = counter1;//parseInt(regex.exec(clicked_id));


		if(match >=1 ){

			 var id_inv_items = $("#id_inv_items"+counter1).val();
		   	 //var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;

		     
 			 
		     	/*var regex = /[+-]?\d+(?:\.\d+)?/g;
		     	var match = parseInt(regex.exec(clicked_id));
		     	var id_inv_items = $("id_inv_items"+match).val();*/
		    	//var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;
		    
 			 
		    $.ajax({

					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						console.log(data); 
						var mydata = JSON.parse(data);
 
						document.getElementById("item_description"+match).value = mydata['name'];
						document.getElementById("alt_unit"+match).value = mydata['alt_unit'];
						document.getElementById("main_unit"+match).value = mydata['main_unit'];
						document.getElementById("conversion_qty"+match).value = mydata['conversion_qty'];

						var main_unit_row = document.getElementById("main_unit"+match).value;
						var alt_unit_row = document.getElementById("alt_unit"+match).value;

						if(main_unit_row == alt_unit_row){ 
					     	var qty = document.getElementById("qty"+match).value; 	 
							var kg = 1; 
							var grams = qty * kg;  
							document.getElementById("alt_qty"+match).value = grams; 
						}else{
							var qty = document.getElementById("qty"+match).value; 	 
							var kg = document.getElementById("conversion_qty"+match).value; 
							var grams = qty * kg;  
							document.getElementById("alt_qty"+match).value = grams; 
						}
	 
					}
				}); 
		}else{		

		 
			 
			if(id_inv_items != '') {

				$.ajax({
					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						 
						var mydata = JSON.parse(data);
						document.getElementById("conversion_qty").value = mydata['conversion_qty'];
						document.getElementById("item_description").value = mydata['name'];
						document.getElementById("alt_unit").value = mydata['alt_unit'];
						document.getElementById("main_unit").value = mydata['main_unit'];

						var main_unit = document.getElementById("main_unit").value;
						var alt_unit = document.getElementById("alt_unit").value;

						if(main_unit == alt_unit){
							var qty = document.getElementById("qty").value;
							var kg = 1; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}else{
							var qty = document.getElementById("qty").value;
							var kg = document.getElementById("conversion_qty").value; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}  
					}
				});
			}	
	    
		}
			
	}

	//Quantity Check Here
	function qtycalc(clicked_id){
		console.log('called me');
		<?php  if($row->id ==''){ ?>

		var main_unit = document.getElementById("main_unit").value;
		var alt_unit = document.getElementById("alt_unit").value;

		if(main_unit == alt_unit){
			var qty = document.getElementById("qty").value;
			var kg = 1; 
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}else{
			var qty = document.getElementById("qty").value;
			var kg = document.getElementById("conversion_qty").value; 
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}
		

		<?php  }else{ ?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var qty = document.getElementById("qty").value;
				var kg = 1; 
				var grams = qty * kg;  
				document.getElementById("alt_qty").value = grams;
			}else{
				var qty = document.getElementById("qty").value;
				var kg = document.getElementById("conversion_qty").value; 
				var grams = qty * kg;  
				document.getElementById("alt_qty").value = grams;
			}


			var regex = /[+-]?\d+(?:\.\d+)?/g;
	     	var match = parseInt(regex.exec(clicked_id));

	     	var main_unit_row = document.getElementById("main_unit"+match).value;
			var alt_unit_row = document.getElementById("alt_unit"+match).value;

			if(main_unit_row == alt_unit_row){ 
		     	var qty = document.getElementById("qty"+match).value; 	 
				var kg = 1; 
				var grams = qty * kg;  
				document.getElementById("alt_qty"+match).value = grams; 
			}else{
				var qty = document.getElementById("qty"+match).value; 	 
				var kg = document.getElementById("conversion_qty"+match).value; 
				var grams = qty * kg;  
				document.getElementById("alt_qty"+match).value = grams; 
			}
		<?php }?>
 
	}

	//Quantity Rows Section Here Check Here
	function qtycalc_rows(clicked_id){
		console.log('called');
		var regex = /[+-]?\d+(?:\.\d+)?/g;
     	var match = parseInt(regex.exec(clicked_id)); 
     	var main_unit_row = document.getElementById("main_unit"+match).value;
		var alt_unit_row = document.getElementById("alt_unit"+match).value;

		if(main_unit_row == alt_unit_row){ 
	     	var qty = document.getElementById("qty"+match).value; 	 
			var kg = 1; 
			var grams = qty * kg;  
			document.getElementById("alt_qty"+match).value = grams; 
		}else{
			var qty = document.getElementById("qty"+match).value; 	 
			var kg = document.getElementById("conversion_qty"+match).value; 
			var grams = qty * kg;  
			document.getElementById("alt_qty"+match).value = grams; 
		}
 
	}

	//Alt Quantity Check Here
	function altqtycalc(clicked_id){

		<?php  if($row->id ==''){?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}else{
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = document.getElementById("conversion_qty").value; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}

			


		<?php  }else{ ?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}else{
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = document.getElementById("conversion_qty").value; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}

			var regex = /[+-]?\d+(?:\.\d+)?/g;
	     	var match = parseInt(regex.exec(clicked_id));

	     	var main_unit_row = document.getElementById("main_unit"+match).value;
			var alt_unit_row = document.getElementById("alt_unit"+match).value;

			if(main_unit_row == alt_unit_row){ 
		     	var alt_qty = document.getElementById("alt_qty"+match).value; 
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty"+match).value = qty;  
			}else{
				var alt_qty = document.getElementById("alt_qty"+match).value; 
				var kg = document.getElementById("conversion_qty"+match).value; 
				var qty = alt_qty / kg;  
				document.getElementById("qty"+match).value = qty;  
			}		 
	     	 

		<?php }?>

		 
	}

	//Alt Quantity Check Here
	function altqtycalc_rows(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
     	var match = parseInt(regex.exec(clicked_id)); 
     	var main_unit_row = document.getElementById("main_unit"+match).value;
		var alt_unit_row = document.getElementById("alt_unit"+match).value;

		if(main_unit_row == alt_unit_row){ 
	     	var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = 1; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}else{
			var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = document.getElementById("conversion_qty"+match).value; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}    			 
	}

	//Serach Field
	function myFunction() {
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTable2");
	  tr = table.getElementsByTagName("tr");
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[2];
	    if (td) {
	      txtValue = td.textContent || td.innerText;
	      if (txtValue.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }       
	  }
	}
	function dismiss(){ 
	 	//document.getElementById('myInput').value=''; 
	}
	
	
function audittrial(clicked_value){
		//alert(clicked_value);
		var id = document.getElementById("indent_id").value;
		//alert(id);
		$('#auditModal').modal('show');
		var form_name ='Indent';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}	
	
	
</script>	

<script>
$(document).on('click', '.discountvalue' ,function (e) {
 $(".discountvalue").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });
        });
</script>

