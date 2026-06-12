<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']==''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PRICE_MATRIX,'add');
}
else{
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PRICE_MATRIX,'edit');
}


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_PRICE_MATRIX,'add');
			 //Price Matrix No Check Here
			 $doc_no = $_POST['doc_no'];

			  $sql5 = " SELECT * FROM `".TBL_PRICE_MATRIX."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."'  and `doc_type` = '51'  and `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."'  ";
				$db->query($sql5);
				$numRows2= $db->num_rows();
					if($numRows2 > 0)   {
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
			//Price Matrix Table Section Here
			
		if($_POST['rate']>'0'){	
			
			$addSql = "   	INSERT INTO `".TBL_PRICE_MATRIX."` SET

							`doc_type` = '".$_POST['doc_type']."', 
							`doc_date` = '".date('Y-m-d' , strtotime($_POST['doc_date']))."',  
							`effective_date` = '".date('Y-m-d' , strtotime($_POST['effective_date']))."',  
							`doc_no` = '".$doc_no."',  
							`id_doc_type_configuration` = '".$_POST['id_doc_type_configuration']."',  
							`id_mst_party_supplier` = '".$_POST['id_mst_party_supplier']."', 
							`remarks` = '".addslashes($_POST['remarks'])."',
							
							`mdoc_no` = '".$mdoc_no."',
							`id_shop` = '".$_SESSION['shop']."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
			
			
			//echo $addSql;
			//die;
				executeSql($addSql);

							$lastInsertId= $db->insert_id();

				//Price Matrix Details Table Here Detault Value Set
				
			
				
				$addSql = " INSERT INTO `".TBL_PRICE_MATRIX_DETAILS."` SET

							`id_inv_price_matrix` = '".$lastInsertId."', 
							`doc_type` = '".$_POST['doc_type']."',  
							`id_inv_items` = '".$_POST['id_inv_items']."',  
						 
							`last_rate` = '".$_POST['last_rate']."',  
							`rate` = '".$_POST['rate']."',  
							`remarks_price_matrix_details` = '".$_POST['remarks_price_matrix_details']."', 
							`approved` = '".$_POST['approved']."',  
							`id_shop` = '".$_SESSION['shop']."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".$_POST['status']."'";
			
			
					executeSql($addSql);
							
			}			

				        //Price Matrix Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];

							for($i = 1; $i <= $counter1; $i++){

								if($_POST['id_inv_items'.''.$i] != '' && $_POST['rate'.''.$i] > '0' ){

									$addSql = "   	INSERT INTO `".TBL_PRICE_MATRIX_DETAILS."` SET

									`id_inv_price_matrix` = '".$lastInsertId."',
									`doc_type` = '".$_POST['doc_type']."',   
									`id_inv_items` = '".$_POST['id_inv_items'.''.$i]."',  
								
									`last_rate` = '".$_POST['last_rate'.''.$i]."',  
									`rate` = '".$_POST['rate'.''.$i]."',  

									`remarks_price_matrix_details` = '".$_POST['remarks_price_matrix_details'.''.$i]."', 
									`approved` = '".$_POST['approved'.''.$i]."',  

									`id_shop` = '".$_SESSION['shop']."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".$_POST['status']."'";
								executeSql($addSql);
								}
							}
							
  	if($_POST['rate']>'0' && 1){	
			
				//unset($_POST);addslashes(encryptor(decrypt,$_POST[eId]))."'")
				$_SESSION['successMsg'] = 'New  Price Matrix has been added sucessfully.';
				
				if($_POST['another']!=''){
					header("location:printPriceMatrix.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&print=1");	
				}else{
					header("location:printPriceMatrix.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1");
				}
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Price Matrix has not been saved. Please make corrections below.';
			} 
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_PRICE_MATRIX,'update');

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['doc_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}

			//Audit Trail Section
			 $auditquery = "SELECT * From `".TBL_PRICE_MATRIX."` WHERE id = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";
    
			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				
				 $idd = $auditrow ->id;
				 $doc_type = $auditrow ->doc_type; 
				 $supplier = $auditrow ->id_mst_party_supplier; 
				
				 $doc_date =  date('d-m-Y' , strtotime(addslashes($auditrow->doc_date))); 
				 $remarks = $auditrow ->remarks; 
				 $last_rate = $auditrow ->last_rate; 
				 $rate = $auditrow ->rate; 
				 $bill_no = $auditrow ->mdoc_no; 
				}

				if($doc_type != $_POST['doc_type']){
					 $doc_type_s ="Document Type Details Changed from " .  $doc_type." - to - ".$_POST['doc_type'];
				}

				if($supplier != $_POST['id_mst_party_supplier']){ 
					$old_data = selectColumn(TBL_PARTY,'company_name'," WHERE `id` = '".$supplier."' ");
					$new_data = selectColumn(TBL_PARTY,'company_name'," WHERE `id` = '".$_POST['id_mst_party_supplier']."'  ");
					$supplier_s = "Supplier Details Changed from ". $old_data." - to - " .$new_data ;
				}
				if($doc_date != $_POST['doc_date']){
					
					$indate = date('d-m-Y' , strtotime(addslashes($_POST['doc_date']))) ;
					 $doc_date_s ="Price Matrix Date Details Changed from " .  $doc_date." - to - ".$indate;
				}
				if($remarks != $_POST['remarks']){
					 $remarks_s ="Remarks Details Changed from " .$remarks." - to - ".$_POST['remarks'];
				}

				if($last_rate != $_POST['last_rate']){
					$last_rate_s ="Last Rate Details Changed from " .$last_rate." - to - ".$_POST['last_rate'];
			   }
			   if($rate != $_POST['rate']){
				$rate_s ="Rate Details Changed from " .$rate." - to - ".$_POST['rate'];
		   }


			//Multiple Data First Rows
				$auditquery = "SELECT * From `".TBL_PRICE_MATRIX_DETAILS."` WHERE id = '".addslashes($_POST['update_id'])."'  ";

			  	$auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				
				 $id_inv_items = $auditrow ->id_inv_items;
				// $qty = $auditrow ->qty;
				// $alt_qty = $auditrow ->alt_qty;
				// $alt_unit = $auditrow ->alt_unit;
				 $remarks_price_matrix_details = $auditrow ->remarks_price_matrix_details;
                 $last_rate = $auditrow ->last_rate;
			     $rate = $auditrow ->rate;
				 $approved = $auditrow ->approved;



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
					
				$id_inv_items_s = "Item Code Details Changed from ". $old_data ." |". $old_data1 ." |". $old_data2 ." ".$old_data3." - to - " .$new_data ." |". $new_data1 ." |".  $new_data3  ;
				}
				if($qty != $_POST['qty']){
					//$qty_s ="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'] ." in 1st Row " ;
					$qty_s ="Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $qty." - to - ".$_POST['qty']  ;
				}
				if($last_rate != $_POST['last_rate']){
					//$alt_qty_s ="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'] ." in 1st Row ";
					$last_rate_s ="Last rate Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $last_rate." - to - ".$_POST['last_rate'] ;
				}
				if($rate != $_POST['rate']){
					//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'] ." in 1st Row " ;
					$rate_s ="Rate Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $rate." - to - ".$_POST['rate'] ;
				}
				if($approved != $_POST['approved']){
					//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'] ." in 1st Row " ;
					$approved_s ="Approved in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $approved." - to - ".$_POST['approved'] ;
				}
				if($remarks_price_matrix_details != $_POST['remarks_price_matrix_details']){
					//$remarks_price_matrix_details_s ="Remarks Indent Details  Changed from " .  $remarks_price_matrix_details." - to - ".$_POST['remarks_price_matrix_details'] ." in 1st Row " ;
					$remarks_price_matrix_details_s ="Remarks Price Matrix Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $remarks_price_matrix_details." - to - ".$_POST['remarks_price_matrix_details'] ;
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
						$auditquery = "SELECT * From `".TBL_PRICE_MATRIX_DETAILS."` WHERE id = '".addslashes($_POST['update_id'.''.$i])."'  ";

					  	$auditresSQL = mysqli_query($connNew, $auditquery);	
						while($auditrow = mysqli_fetch_object($auditresSQL)){ 
						
						 $id_inv_items = $auditrow ->id_inv_items;
						/// $qty = $auditrow ->qty;
						// $alt_qty = $auditrow ->alt_qty;
						 $last_rate = $auditrow ->last_rate;
						 $rate = $auditrow ->rate;
						 $approved = $auditrow ->approved;

						// $alt_unit = $auditrow ->alt_unit;
						 $remarks_price_matrix_details = $auditrow ->remarks_price_matrix_details;

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
						$id_inv_items_s = "Item Code Details Changed from ". $old_data ." |". $old_data1 ." |". $old_data3 ." ".$old_data3." - to - " .$new_data ." |". $new_data1 ." |". $new_data3 . " in Row ". $val ." ";
						}
					/*	if($qty != $_POST['qty'.''.$i]){
							// $qty_s .="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " in Row ". $val ." ";
							 $qty_s =" Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . " Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " <br/> ";
						}
						if($alt_qty != $_POST['alt_qty'.''.$i]){
							// $alt_qty_s .="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i] . " in Row ". $val ." ";
							 $alt_qty_s =" Alt Quantity Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i]  ." <br/>";
						}
						if($alt_unit != $_POST['alt_unit'.''.$i]){
							//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " in Row ". $val ." ";
							$alt_unitt = " Alt Unit Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i]  ."  <br/>";
						} */
						if($last_rate != $_POST['last_rate'.''.$i]){
							//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " in Row ". $val ." ";
							$last_rate_s = " Last Rate Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $last_rate." - to - ".$_POST['last_rate'.''.$i]  ."  <br/>";
						}
							if($rate != $_POST['rate'.''.$i]){
							//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " in Row ". $val ." ";
							$rate_s = " Rate Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $rate." - to - ".$_POST['rate'.''.$i]  ."  <br/>";
						}
						if($approved != $_POST['approved'.''.$i]){
							//$alt_unitt ="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " in Row ". $val ." ";
							$approved_s = " Approved Details in ".$old_data ." |". $old_data1 ." |". $old_data3 . "Changed from " .  $approved." - to - ".$_POST['approved'.''.$i]  ."  <br/>";
						}
						if($remarks_price_matrix_details != $_POST['remarks_price_matrix_details'.''.$i]){
							// $remarks_price_matrix_details_s .="Remarks Indent Details Details Changed from " .  $remarks_price_matrix_details." - to - ".$_POST['remarks_price_matrix_details'.''.$i] . " in Row ". $val ." ";
							 $remarks_price_matrix_details_s .=" Remarks Details in ". $old_data ." |". $old_data1 ." |". $old_data3 ." Changed from " .  $remarks_price_matrix_details." - to - ".$_POST['remarks_price_matrix_details'.''.$i] ." <br/> ";
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
						$id_inv_items_s .= $new_data." |". $new_data1 ." |" . $new_data3 ." Price Matrix Details Added  <br/> ";
						}
						if($_POST['qty'.''.$i]){
							// $qty_s .="Quantity Details Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " ";
						}
						if($_POST['last_rate'.''.$i]){
							// $alt_qty_s .="Alt Quantity Details Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i] . " ";
						}
						if($_POST['rate'.''.$i]){
							//$alt_unitt .="Alt Unit Details Changed from " .  $alt_unit." - to - ".$_POST['alt_unit'.''.$i] . " ";
						}
						if($_POST['remarks_price_matrix_details'.''.$i]){
							// $remarks_price_matrix_details_s .="Remarks Indent Details Details Changed from " .  $remarks_price_matrix_details." - to - ".$_POST['remarks_price_matrix_details'.''.$i] . " ";
						}
				
				}
				//Update Price Matrix Table
				
				if($_POST['rate']>'0'){						
		
						$editSql = "  UPDATE `".TBL_PRICE_MATRIX."` SET  
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['doc_date'])))."',  
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  

							`doc_no` = '".addslashes($_POST['doc_no'])."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',  
							`id_mst_party_supplier` = '".addslashes($_POST['id_mst_party_supplier'])."', 
							`remarks` = '".addslashes($_POST['remarks'])."',
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							executeSql($editSql);

						//Update Price Matrix Details
							$editSql = "   	UPDATE `".TBL_PRICE_MATRIX_DETAILS."`  SET  
							
							`id_inv_price_matrix` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`doc_type` = '".addslashes($_POST['doc_type'])."',   
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
						
							`last_rate` = '".addslashes($_POST['last_rate'])."',  
							`rate` = '".addslashes($_POST['rate'])."',  
						
							`remarks_price_matrix_details` = '".addslashes($_POST['remarks_price_matrix_details'])."',
							`approved` = '".addslashes($_POST['approved'])."',  

							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['update_id'])."'";
							executeSql($editSql);
			  	}
						//Update Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								$editSql = "   	UPDATE `".TBL_PRICE_MATRIX_DETAILS."`  SET  

								`id_inv_price_matrix` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
								`doc_type` = '".addslashes($_POST['doc_type'])."',   
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
								 
								`last_rate` = '".addslashes($_POST['last_rate'.''.$i])."',  
								`rate` = '".addslashes($_POST['rate'.''.$i])."',  
							
								`remarks_price_matrix_details` = '".addslashes($_POST['remarks_price_matrix_details'.''.$i])."',
								`approved` = '".addslashes($_POST['approved'.''.$i])."',  

								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['update_id'.''.$i])."'";
								executeSql($editSql);
							}

							$auditeditSql = " INSERT audit_trail SET 
				                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
								`tables_name` = 'inv_price_matrix , inv_price_matrix_details',
								`form_code` = 'Price Matrix',
								`changes` =  '".addslashes($supplier_s).",".addslashes($doc_date_s).",".addslashes($doc_type_s).",".addslashes($remarks_s).",".addslashes($id_inv_items_s).",".addslashes($remarks_price_matrix_details_s).",".addslashes($rates_s).",".addslashes($last_rate_s).",".addslashes($approved_s)."',
								`date_created` = '".currenDateTime()."',
								`last_modified` = '".currenDateTime()."',
								`id_mst_user_modified_by` = '".$_SESSION['userId']."',
								`id_mst_user_created_by` = '".$_SESSION['userId']."',
								`type` = 2 ";					
			
           					executeSql($auditeditSql);

				    //Update Field More Fields Add Here 

							if($_POST['counter1'] == ''){
								$counter1 = 0;								
							}else{
								$counter1 = $_POST['counter1'];									
							}

							for($i = $counter1; $i > $update_count; $i--){
								 
								if($_POST['id_inv_items'.''.$i] != ''  && $_POST['rate'.''.$i] > '0' ){

									$addSql = "INSERT INTO `".TBL_PRICE_MATRIX_DETAILS."` SET
									`id_inv_price_matrix` = '".addslashes(encryptor(decrypt,$_POST[eId]))."', 
									`doc_type` = '".addslashes($_POST['doc_type'])."',  
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									
									`last_rate` = '".addslashes($_POST['last_rate'.''.$i])."',  
									`rate` = '".addslashes($_POST['rate'.''.$i])."',  
								
									`remarks_price_matrix_details` = '".addslashes($_POST['remarks_price_matrix_details'.''.$i])."',
									`approved` = '".addslashes($_POST['approved'.''.$i])."',  

 
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
				$_SESSION['successMsg'] = selectColumn(TBL_PRICE_MATRIX, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.'; 

				if($_POST['another']!=''){
					header("location:printPriceMatrix.php?submenu=".$_GET['submenu']."&session=".$_GET['session']."&print=1");	
				}else{
					header("location:printPriceMatrix.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1"); 
				}
					exit;
			}else{
				//echo "not";
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_PRICE_MATRIX,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Price Matrix has not been saved. Please make corrections.';
	}
}
// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Price Matrix Table

	$sql = "  SELECT * FROM `".TBL_PRICE_MATRIX."`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";					
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_price_matrix_id =  encryptor(decrypt,$_GET['id_price_matrix_id']);
	}else{
 
		$id_price_matrix_id = encryptor(decrypt,$_GET['id_price_matrix_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>


<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style>
	.select2-container{
		width:100%!important;
	}
	table.dataTable tfoot th, table.dataTable tfoot td {
    border-top: none;
}
#myTable1 tr .priceDownArrow{
  display:none;
}
#myTable1 tr:first-child .priceDownArrow{
  display:block;
}
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
 	
   <?php  $session=$_GET['submenu'];
//echo encryptor(decrypt,$_REQUEST['eId']);
   ?>
    <section class="content-header">
     <!-- <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
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
         
           
			 <div class="nav-tabs-custom mb-0 shadow-none">
		 
			<!--<div class="box-header with-border">
			<h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no; ?> </span>
					
            
            </div>-->
            <!-- /.box-header -->
            <!-- form start -->  	
<?php //echo encryptor(decrypt,$_REQUEST['eId']);?>

			
			 <form name="price_matrix_form" action=""  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="price_matrix_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
				<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="price_matrix_id" id="price_matrix_id">
				
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
              			<h5 style="padding: 5px;">Requisition Request</h5>
              		</div> -->
              	
	              	<div class="row">	

	              		<div class="form-group col-xs-6 col-md-2 col-sm-6 " >
	              			<label for="name">Document Type</label>
	              		
	              			
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" >	                	  		                  	  
			                  	 	<option selected="selected" value="51">Price Matrix</option>  
			                  	</select>	 
	              			<?php 
	              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 
									} 
	              			?></div>
	              			
 
 
	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Document Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Document Date" id="doc_date" name="doc_date" value="<?php if($_POST) echo $_POST['doc_date'];elseif($row->doc_date!='') echo date('d-m-Y',strtotime($row->doc_date));else echo date('d-m-Y');?>" onchange="hideandshow()">
		                  </div> 
	              		</div>


		                <div class="form-group col-xs-12 col-md-2 col-sm-12 p-0">
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 /*#ind{
	              				 	display: none;
	              				 }*/
	              			</style>
	              			<?php } ?>
	              				<div class=" col-xs-12 col-md-12 col-sm-12">
		              				<label for="name">Document No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
								   
								   <!--<?php/*	//$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->doc_no."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object())

										*/?>-->
		              			<input type="text" class="form-control" placeholder="Enter Document No" id="mdoc_no2" name="mdoc_no2" value="<?php if($_POST) echo $_POST['mdoc_no2'];else echo stripslashes($row->mdoc_no);?>" readonly>
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
		              				<label for="name">Document No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Enter Document No" id="doc_no" name="doc_no" value="<?php if($_POST) echo $_POST['doc_no'];else echo stripslashes($row->doc_no);?>" readonly>
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
			                <?php if($row->id ==''  || $prefix != ''){ ?>
			                  <style type="text/css">
			                  	#hideandshow{
			                  		display: none;
			                  	}
			                  </style>
		              	  	<?php } ?>
		                  	<div id="hideandshow" name="hideandshow">
				                <div class="form-group col-xs-12 col-md-12 col-sm-6">
				                  <label for="name">Manual Document No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input type="text" class="form-control" placeholder="Enter Manual Document No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		       
	              <div class="form-group col-xs-12 col-md-4 col-sm-12 form-p" >
	              			<label for="name">Supplier <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fas fa-truck-loading"></i> 
						   	</div>
	              			<select class="form-control select2 id_mst_party_supplier" name="id_mst_party_supplier" id="id_mst_party_supplier" onchange="comShow(this.value);//partybilltobe(this.value);//comShow2(this.value);" data-parsley-required  data-parsley-errors-container="#outletError"  <?php echo $readonly; ?> style="width:100%">
								<?php /*?><?php $categoryDropDown = '	<option value="">Select Supplier</option>';
								  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_party_supplier'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_party_supplier == $resultCat->id){
											$selected = 'selected="selected"';
											$ledger = $resultCat->ledger;
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option'.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
									}
								  }
								 	echo $categoryDropDown .= ' ';
								  ?><?php */?>
                                  </select>
						        <?php echo $err_deparment;?>
								</div><span id="outletError"></span>
								<?php //if($ledger == 1){ ?>

		                  	 	<input type="text" class="form-control "  id="id_mst_party_supplier1" name="id_mst_party_supplier1" value="<?php if($_POST) echo $_POST['id_mst_party_supplier'];else echo stripslashes($row->id_mst_party_supplier);?>" style="display: none;" >
		                  	 	    <div><span id="comData" style="color: red"></span></div>
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


 
	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Effective Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Effective Date" id="effective_date" name="effective_date" value="<?php if($_POST) echo $_POST['effective_date'];elseif($row->effective_date!='') echo date('d-m-Y',strtotime($row->effective_date));else echo date('d-m-Y');?>" onchange="hideandshow()">
		                  </div> 
	              		</div>


			            <div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>
                   

		        </div>
		     

		         <div class="box-body table-responsive2">

              	<div class="card text-dark bg-light">

	              	<div class="row">
	              		<?php 
              				$sql2 = " SELECT * FROM `".TBL_PRICE_MATRIX."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'51'."' ";
							$db->query($sql2);  
							$numRows= $db->num_rows();

								if($numRows != 0){
									while($row2 = $db->fetch_object()){ 
										$id_price_matrix_id= $row2->id; 
										$id_price_matrix_id = $id_price_matrix_id + 1; 
									}
								}
								else{
									 $id_price_matrix_id = '1'; 
								}
              			?>	
	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;" >
		                  <label for="name"></label>
		                  <input type="text" class="form-control" id="id_inv_price_matrix" name="id_inv_price_matrix"  value="<?php echo $id_price_matrix_id; ?>" > 
		                </div>
		            </div>

		            <div class="row">
		            	 <hr class="br-line">
              		<div class="text-center ">
              			<h6 class="tb-heads">Price Matrix Details</h6>
              		</div>  
		            	<table id="myTable1" class="table table-striped  table-bordered dataTable no-footer   order-list1 max-h2">
				            <thead>
				                <tr>
				                    <th style=" width:200px;padding: 5px 9px;">Item Code</th>
				                    <th>Item Description</th>  
				                    <th>Last Rate</th> 	  
				                    <th>Rate</th>     
				                    <th>Approved (1 = Yes, 0 = No)</th>               
				                    <th>Remarks</th> 
								

				                </tr>
				            </thead>
				            <tbody id="tableBody">
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Price Matrix Details Here First Row Only Select
				            	$sql2 = "  SELECT * FROM  `".TBL_PRICE_MATRIX_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_price_matrix` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_price_matrix'.''.$i] = $rowsID->id_inv_price_matrix;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items;
							 		// $array['alt_qty'.''.$i] = $rowsID->alt_qty;
							 		 $array['last_rate'.''.$i] = $rowsID->last_rate;
							 		 $array['rate'.''.$i] = $rowsID->rate;
							 		// $array['qty'.''.$i] = $rowsID->qty;
							 		// $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		// $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['remarks_price_matrix_details'.''.$i] = $rowsID->remarks_price_matrix_details;
									 $array['approved'.''.$i] = $rowsID->approved;
										
			
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
									
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
					                 <select class="form-control select2" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" onchange="itemget(this.id);getLastRate(this.value,<?php echo $k;?>);" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError4">
										<?php $categoryDropDown = '<option value="">Select Item Code</option>';
											 	

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list = $sqlRow->id;
														$string .= $list.',';
													}
											$item_list = rtrim($string,',');
												
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	
							                    	$conv_qty = $row1->id;
							                    	if($_REQUEST['id_inv_items'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_inv_items'.''.$j] == $row1->id){
														$selected = 'selected="selected"';
														$item_description =  $row1->name;
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name.' | '.$row1->field_value).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>  <span id="outletError4"></span>'; 

											 	$conversion_qty  = selectColumn('inv_items','conversion_qty'," WHERE `id` = '".$array['id_inv_items'.''.$j]."' ");
										?> 
					                </td> 
				                    <td class="form-group col-xs-12 col-sm-3">
				                        <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"  value="<?php if($_POST) echo $_POST['item_description'];else echo stripslashes($item_description); ?>" readonly />

				                        <?php 
								//	$last_rate  = selectColumn(TBL_PRICE_MATRIX_DETAILS,'rate',"  WHERE `id_mst_party_supplier` = '".$array['id_inv_price_matrix'.''.$j]."' ORDER BY ID LIMIT 1 ");  

								//echo  selectColumn(TBL_PRICE_MATRIX_DETAILS,'rate',"  WHERE `id_inv_items` = '".$array['id_inv_items'.''.$j]."' ORDER BY ID LIMIT 1 ");  

									?>

				                      <td class="form-group col-xs-12 col-sm-1"> 
				                        <input type="text"  autocomplete="off" readonly name="last_rate<?php echo $k;?>" id="last_rate<?php echo $k;?>" placeholder="Last Rate"  class="form-control" value="<?php if($_POST) echo $_POST['last_rate'];else echo stripslashes($array['last_rate'.''.$j]); ?>"/>

				                      <td class="form-group col-xs-12 col-sm-1"> 
				                        <input type="text"  autocomplete="off"  name="rate<?php echo $k;?>" id="rate<?php echo $k;?>" placeholder="Rate"  class="form-control" value="<?php if($_POST) echo $_POST['rate'];else echo stripslashes($array['rate'.''.$j]); ?>"/>

				                    </td> 
				                      
				                  
				                    <input type="hidden" name="conversion_qty<?php echo $k;?>" id="conversion_qty<?php echo $k;?>" value="<?php echo $conversion_qty;?>">				                    
				                   
				                    <?php
								$topArrowInc	=	'<a href="javascript:void(0);" title="Fill Down" style="width:36px;" onclick="fillPriceLeftInc('.$k.');"  class="text-green input-group-addon " ><i  class="arrows fa fa fa-angle-double-down" style="margin-left:-4px;"></i></a>'; ?>
				
									<td  class="form-group col-xs-12 col-sm-1">
									<div  style="display:inline-flex">
													                    <!-- <input type="text"  autocomplete="off"  name="approved<?php echo $k;?>" id="approved<?php echo $k;?>" placeholder="Approved"  class="form-control" value="<?php if($_POST) echo $_POST['approved'];else echo stripslashes($array['approved'.''.$j]); ?>-->
                                      
									<input type="number" min="0" max="1" maxlength="1" class="form-control parsley-error" onclick=""  style="width: 176px;" name="approved<?php echo $k;?>" id="approved<?php echo $k;?>"
                                      value="<?php if($_POST) echo $_POST['approved'];else echo stripslashes($array['approved'.''.$j]); ?>">  <?php echo $topArrowInc;?>

								  </div>
								</td>
								 <td class="form-group col-xs-12 col-sm-2"> 
				                        <input type="text"  autocomplete="off"  name="remarks_price_matrix_details<?php echo $k;?>" id="remarks_price_matrix_details<?php echo $k;?>" placeholder="Remarks"  class="form-control" value="<?php if($_POST) echo $_POST['remarks_price_matrix_details'];else echo stripslashes($array['remarks_price_matrix_details'.''.$j]); ?>"/>
				                    </td>
				                    <?php if($k>=1){?>
				                    <td> 
										<a class="btn n-btn  abtn ibtnDel2" style="cursor:pointer;" title="Delete" id="<?php echo $array['id'.''.$j]; ?>"  name="<?php echo $array['id'.''.$j]; ?>"><i class="fa fa-trash-o"></i></a>
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
				            	<input type="hidden" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" > 
				            </tbody>
				            <tfoot>
				                <tr> 
									<td colspan="7" style="text-align:right;">
										  <hr class="hr-m">
									<a  type="button" class="btn n-btn btn-block" id="addrow1" value="Add Row" /> <i class="fa fa-plus"></i> Add Row</a>

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
		 
		        <hr class="br-line mb-10">     
		         <div class="box-footer p-0 br-none">   
		                                     
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn " name="Save"   >
				
			   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("managePriceMatrix.php?submenu=<?php echo $_GET['submenu']?>&session=<?php echo $_GET['session']; ?>"); '>
			   	<i class="far fa-window-close"></i>
			   	Close
			   </a>
			   

		      <!--    &nbsp;&nbsp;&nbsp;&nbsp; <a href="editprice_matrix.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>"  class="btn btn-success" id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true">Another</i></a> -->
				  
				<!--<input type='button' value='Another' class="btn btn-success"  onclick="saveornot();">-->
				  
			
			   <?php 	//if($row->id != '' && $_REQUEST['print'] == '1'){  ?>
		         &nbsp;&nbsp;&nbsp;&nbsp; <!-- <a href="printPriceMatrix.php?eId='<?php echo $_GET['eId']; ?>'&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu'] ?>&action=edit&page=<?php $_REQUEST['page']?>"   class="btn btn-success"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> -->
	        	<?php// } ?>
					        	 
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
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail );?>">				
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

						<a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
					 <i class="fas fa-history"></i> Alteration History
				</a>
				  
				<?php } ?>  
         	</div>
			
			
	
	<!-- Audit Trail Modal -->
<div class="modal" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-head">
           <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title alt-pre" id="roomtitle1" >Alteration History</label>
            </div>
            <div class="modal-body alt-body">
                <table class="table table-bordered table-striped">
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
			
            <div class="modal-footer alt-ft">
               <button type="button" class="btn c-btn" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button> 
            </div>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->



	<!-- Another Modal -->
<div class="modal fade" id="anotherModal" tabindex="-1" role="dialog" aria-labelledby="anotherModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Requestion Note</label>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
				
				<div style="text-align:center;font-weight:600;font-size:15px"> 
				<div id="mge"></div>
				
				<br/>
					<input type='submit' value="<?=($_REQUEST['eId']==''?'Add':'Edit')?>" class="btn c-btn" onclick="yes();" name="Save"  >
					<input type='button' value="No" class="btn c-btn" onclick="nosave();" name="no"  >
					<input type='hidden' value="" id="another" name="another"  >
				</div> 
				
			</table>
            </div>
        </div>
    </div>
</div>
<!-- End Another Modal -->
	

			
			
			
              <!-- /.box-body -->	
			
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

											$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												$list2=array();
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list2[] = $sqlRow->id;
														//$string .= $list.',';
													}	print_r($list2);$uarray =array_unique($list2);
													
													
													$input = array_map("unserialize", array_unique(array_map("serialize",$list2)));
											$item_list2 = implode(',',$input);										
											 						 
							      //text-green input-group-addon  priceDownArrow             
	 ?>										 	 
	
<?php include_once("../includes/footer.php");?>  
<script type="text/javascript">
 

	//Delete Row Section Here
	 

	$("table.order-list1").on("click", ".ibtnDel2", function (event) { 
		$(this).closest("tr").remove(); 
		var clicked_id = $(this).attr("id");
		var submenu = document.getElementById("submenu").value;
			$.ajax({
				type: "POST",
				url: "../ajax/priceMatrixManageDeleteRow.php",
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
	  var submenu = document.getElementById("submenu").value;
		 
		if(doc_type != '' && doc_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/priceMatrixManage.php",
				data:{doc_type:doc_type, doc_date:doc_date},
				success: function(data){
					var mydata = JSON.parse(data);  
					if(mydata['method'] == 1){
						$('#hideandshow').hide();   
						$('#ind').show();   
						<?php if($row->id == ''){?>
						$("#mdoc_no2").val( mydata['prefix']+mydata['doc_no']+ mydata['suffix']);
							document.getElementById("doc_no").value = mydata['doc_no'];
							//document.getElementById("mdoc_no2").value = mydata['mdoc_no'] + mydata['prefix'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						
						<?php } ?>
						document.getElementById("prefix").value = mydata['prefix'];
						document.getElementById("suffix").value = mydata['suffix'];
				

					}else{
						
						$('#hideandshow').show();
						$('#ind').hide(); 
						<?php if($row->id == ''){?> 
							document.getElementById("doc_no").value = mydata['doc_no'];
							//document.getElementById("mdoc_no").value = mydata['mdoc_no'];
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
	$sql2 = " SELECT max(doc_date) as doc_date FROM `".TBL_PRICE_MATRIX."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'51'."' ";
		$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$doc_date= $row2->doc_date;
				$doc_date = date('d-m-Y' , strtotime(addslashes($doc_date)));  
			}  
			if($row->id != '' && $_REQUEST['print'] == 1){ 

?>
<script type="text/javascript">
	var eid = '<?php echo $_GET['eId']; ?>';  
</script>

	<button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display: none;">
    </button>
	<!-- The Modal -->
	<div class="modal" id="myModal">
	    <div class="modal-dialog">
	      <div class="modal-content"  style="margin-top: 50%; width: 95%;margin-left: 20%;"> 
	       
	        <!-- Modal body -->
	        <div class="modal-body">
	        	<center>
	          <a href="editPriceMatrix.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another price_matrix</i></a> 
	          <a href="printPriceMatrix.php?eId=<?php echo $_GET['eId']; ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu'] ?>&action=edit&page=<?php $_REQUEST['page']?>"  type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> 
	          <button type="button" class="btn btn-danger" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button>
	          <a href="managePriceMatrix.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>" type="button" class="btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>
	          
				</div>  
        	  <!-- <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button> -->
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
 	function saveornot(){
		var id_inv_items = document.getElementById("id_inv_items").value;
		var id_mst_party_supplier = document.getElementById("id_mst_party_supplier").value;
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
		
		
		if(id_mst_party_supplier == '' && id_inv_items==''){
			window.location.href="editPriceMatrix.php?submenu="+submenu+"&session="+session;
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
		
		//window.location.href="editprice_matrix.php?submenu="+submenu+"&session="+session;
	}
	
	
	
	function nosave(){
		var submenu = document.getElementById("submenu").value;
		var session = document.getElementById("session").value;
		//alert(session)
		window.location.href="editPriceMatrix.php?submenu="+submenu+"&session="+session;
	}
		
		
 	function audittrial(clicked_value){
		
		var id = document.getElementById("price_matrix_id").value;
		$('#auditModal').modal('show');
		var form_name ='Price Matrix';
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

 	$( document ).ready(function() {
 		var dates = '<?php echo date('d-m-Y',strtotime($doc_date)); ?>'; 
 		hideandshow();
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });
		
		//Button hide 
		 
	});


//Select 2  Resolve Here

	var counter1 =  document.getElementById("counter1").value;  

	 

    $("#addrow1").on("click", function () { 
        
        counter1++;  

        var newRow1 = $("<tr >");
        var cols1 = ""; 
        cols1 += '<td ><select onchange="itemget(this.id);getLastRate(this.value,'+ counter1 + ')" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" class="form-control select3" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError7"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_mst_attributes_item_type IN ('".$item_list2."') and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'  ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.addslashes($row1->name).' | '.addslashes($row1->field_value) ?></option> <?php } 
                  	?></select>  <span id="outletError7"></span> </td>'; 
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';

		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Last Rate" class="form-control" readonly name="last_rate' + counter1 + '" id="last_rate' + counter1 + '" readonly=""/></td>';
	
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Rate" class="form-control" name="rate' + counter1 + '" id="rate' + counter1 + '" /></td>';

		//cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue" onkeyup="qtycalc_rows(this.id)"  name="qty' + counter1 + '" id="qty' + counter1 + '" required /></td>';  


       // cols1 += '<td><input type="text"  autocomplete="off" placeholder="Unit" class="form-control" name="main_unit' + counter1 + '" id="main_unit' + counter1 + '" readonly=""/></td>'; 

       // cols1 += '<td><input onkeyup="altqtycalc_rows(this.id)" type="text"  autocomplete="off" placeholder="Alt Qty" class="form-control discountvalue" name="alt_qty' + counter1 + '" id="alt_qty' + counter1 + '"/></td>'; 

       // cols1 += '<td><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control" name="alt_unit' + counter1 + '" id="alt_unit' + counter1 + '" readonly=""/></td>';

       // cols1 += '<input type="hidden"  class="form-control" name="conversion_qty' + counter1 + '" id="conversion_qty' + counter1 + '" />';        

       
		cols1 += '<td><input type="text" style="width:176px;float:left;" autocomplete="off" placeholder="Approved" class="form-control" name="approved' + counter1 + '" id="approved' + counter1 + '"/><a href="javascript:void(0);" style="height:34px;width:36px;" title="Fill Down" onclick="fillPriceLeftInc(' + counter1 + ');"  class="text-green input-group-addon " ><i  class="arrows fa fa fa-angle-double-down" style="margin-left:-4px;"></i></a></td>'; 
		
		
				  
        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Remarks" class="form-control" name="remarks_price_matrix_details' + counter1 + '" id="remarks_price_matrix_details' + counter1 + '"/></td>'; 		  
		
		cols1 += '<td><a class="btn n-btn abtn ibtnDel1" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></td>'; 
		
		document.getElementById("counter1").value=counter1; 
		
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

			var id_inv_items = document.getElementById("id_inv_items");
		    var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value; 

		    var regex = /[+-]?\d+(?:\.\d+)?/g;
		    var match = parseInt(regex.exec(clicked_id));

		if(match >=1 ){

			 var id_inv_items = document.getElementById("id_inv_items"+match);
		   	 var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;

		     
 			 
		     	var regex = /[+-]?\d+(?:\.\d+)?/g;
		     	var match = parseInt(regex.exec(clicked_id));
		     	var id_inv_items = document.getElementById("id_inv_items"+match);
		    	var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;
		    
 			 
		    $.ajax({

					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data);
 
						document.getElementById("item_description"+match).value = mydata['name'];
						//document.getElementById("alt_unit"+match).value = mydata['alt_unit'];
						//document.getElementById("main_unit"+match).value = mydata['main_unit'];

						//document.getElementById("conversion_qty"+match).value = mydata['conversion_qty'];

						//var main_unit_row = document.getElementById("main_unit"+match).value;
						//var alt_unit1_row = document.getElementById("alt_unit"+match).value;

						/*if(main_unit_row == alt_unit1_row){ 
					     	var qty = document.getElementById("qty"+match).value; 	 
							var kg = 1; 
							var grams = qty * kg;  
							document.getElementById("alt_qty"+match).value = grams; 
						}else{
							var qty = document.getElementById("qty"+match).value; 	 
							var kg = document.getElementById("conversion_qty"+match).value; 
							var grams = qty * kg;  
							document.getElementById("alt_qty"+match).value = grams; 
						}*/
	 
					}
				}); 
		}else{		 
		 
			 
			if(id_inv_items != '') {

				$.ajax({
					type: "POST",
					url: "../ajax/Itemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data);

						document.getElementById("item_description").value = mydata['name'];
						//document.getElementById("alt_unit").value = mydata['alt_unit'];
						//document.getElementById("main_unit").value = mydata['main_unit'];

					//	document.getElementById("conversion_qty").value = mydata['conversion_qty'];  
//
					//	var main_unit = document.getElementById("main_unit").value;
						//var alt_unit = document.getElementById("alt_unit").value;

						/*if(main_unit == alt_unit){
							var qty = document.getElementById("qty").value;
							var kg = 1; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}else{
							var qty = document.getElementById("qty").value;
							var kg = document.getElementById("conversion_qty").value; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}  */
					}
				});
			}	
	    
		}
			
	}


	<?php  
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>

window.onload = function() {
onLoadIdCompany(<?php echo $row->id_mst_party_supplier; ?>);
comShow(<?php echo $row->id_mst_party_supplier; ?>);

//supplier();
						
						};
							
<?php } ?>	

</script>	





<script>


//first function

function comShow(id){
			var comId = id;
			 $.ajax({
			 type        : 'POST',
			 url         : 'ajax/ajaxComShow.php', 
			 data        : 'comId='+comId,
			 success     : function(data){
			   $("#comData").html(data);
			    //$("#comData2").val($(this).val());
             // $("#comData2").html(data);
			 } 
			})
		} 


//second function




 //COMPANY AUTO COMPLETE START==================================================================
	comCheck = () =>{
		window.location.href='https://www.app.roomstatushub.in/adminpanel/index.php';
	}
     $('.id_mst_party_supplier').select2({
        placeholder: 'Select Supplier ',
        ajax: {
          url: "ajax/ajaxSearchSupplierName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });
	  //COMPANY AUTO COMPLETE END==================================================================
	  
	  function onLoadIdCompany(id){	 
	 $.ajax({	  
	   url: "ajax/ajaxSearchSupplierName.php?id_mst_party_supplier="+id,
	   dataType: 'json',
          delay: 1,
	  success: function(data){
		
                var id = data[0].id;
                var companyname = data[0].text;
				var tr_str = "<option value=" + id +">" + companyname + "</option>" ;
				$("#id_mst_party_supplier").append(tr_str);
				//$("#CompanyGroupDetails").html(companyname);
					    
          }           
	})

	}

function fillPriceLeftInc(selctedCount){

var counter1 = document.getElementById("counter1").value;
if(selctedCount == 'undefined' || selctedCount == null){
	var selctedCount = '1';
 val =  document.getElementById('approved').value;
}else{
	val = document.getElementById('approved'+selctedCount).value;
	}
	
  for(i=selctedCount;i<=counter1;i++){
	 // alert(i);
    document.getElementById('approved'+i).value = val;


  }
}

document.getElementById('approved').addEventListener('input',function (e){
  e.target.value=e.target.value.replace(/[^0-1]/g,'').replace(/(.{1})/g, '$1 ').trim();
});


	//autofill down arrow



 function getLastRate(id_inv_items,rowCount){	
 //alert(id_inv_items+'   '+rowCount); 
	 $.ajax({	  
	   url: "ajax/ajaxGetLastRate.php?id_inv_items="+id_inv_items+'&rowCount='+rowCount,
	  type: "GET",
	  success: function(result){
		var response = JSON.parse(result);
	
				$("#last_rate"+response.rowCount).val(response.rate);
				//$("#CompanyGroupDetails").html(companyname);
					    
          }           
	})

	}
</script>
