<?php include_once("../config/auto_loader.php");
 
if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_PURCH,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_PURCH,'edit');	

//debugData($_REQUEST);
//exit;

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 

			 
			 //Purch No Check Here
			 $po_no = $_POST['po_no'];

			 $sql5 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `po_no`='".$po_no."'  and `doc_type` = '1'  ";
				$db->query($sql5);
				$numRows= $db->num_rows();
					if($numRows > 0){
						while($row5 = $db->fetch_object()){ 
							$po_no= $row5->po_no; 
							$po_no = $po_no+1; 
						} 
					}else{
						 $po_no = $_POST['po_no'];
					}

			 //Values Add Here

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$po_no.''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			
 $itemDetailSizeOf=	sizeof($_POST['id_req_no']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
	 
	  $id_inv_indent1 = explode('-', $_POST['id_req_no'][$i]); 
        $id_po .= $id_inv_indent1[0].',';
 }		
		
  $id_inv_poo = rtrim($id_po,',');			
			
			//Purch Table Section Here
			$addSql = "   	INSERT INTO `".TBL_INV_PURCH."` SET

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`po_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
							`po_no`='".$po_no."',
							`id_inv_po` = '".addslashes($id_inv_poo)."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',  
							`id_mst_attributes_department` = '".addslashes($_POST['id_mst_attributes_department1'])."', 
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
							


				//Purch Details Table Here Detault Value Set
				$addSql = "   	INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

							`id_inv_purch` = '".addslashes($lastInsertId)."',
							`doc_type` = '".'6'."',  
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`alt_qty` = '".addslashes($_POST['alt_qty'])."',  
							`qty` = '".addslashes($_POST['qty'])."',  
							`alt_unit` = '".addslashes($_POST['alt_unit'])."', 
							`main_unit` = '".addslashes($_POST['main_unit'])."', 
							`remarks_purch_details` = '".addslashes($_POST['remarks_purch_details'])."', 
							`id_inv_indent_details` = '".addslashes($_POST['id_inv_indent_details'])."', 
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
					executeSql($addSql);

							//$lastInsertId= $db->insert_id();

							//Order Qty Check Here
							$order_total= 0;$balance_qty =0;
							$id_inv_indent_details = $_POST["id_inv_indent_details"];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  AND `doc_type` = '6'  ";
		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();

		                    $order_total = $row1->qty;
						//Total Qty Get
						$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
						$balance_qty = $total_qty - $order_total;
						//Order Qty Update Indent Details Table
						 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";

							executeSql($editSql);

				//Purch Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];
							for($i = 1; $i <= $counter1; $i++){


								if($_POST['id_inv_items'.''.$i] != '' && $_POST['main_unit'.''.$i] !='' ){

								$addSql = "	INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

									`id_inv_purch` = '".addslashes($lastInsertId)."',
									`doc_type` = '".'6'."',   
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
									`qty` = '".addslashes($_POST['qty'.''.$i])."',  
									`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
									`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
									`remarks_purch_details` = '".addslashes($_POST['remarks_purch_details'.''.$i])."', 
									`id_inv_indent_details` = '".$_POST['id_inv_indent_details'.''.$i]."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									
									executeSql($addSql);
								}

							//Order Qty Check Here
							$order_total= 0;
							$balance_qty =0;
							$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  AND `doc_type` = '6'   ";
		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();
		                    $order_total = $row1->qty;
							//Total Qty Get
							$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
							$balance_qty = $total_qty - $order_total;
							//Order Qty Update Indent Details Table
							 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
							 
							executeSql($editSql);
							}

			if(1){
				//unset($_POST);addslashes(encryptor(decrypt,$_POST[eId]))."'")
				

				$_SESSION['successMsg'] = 'New  SIN has been added sucessfully.';
				
				if($_POST['another']!=''){
					header("location:editStoreIssueNote.php?submenu=".$_GET['submenu']."&print=1");	
				}else{
					header("location:printStoreIssueNote.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']."&print=1");
				}
				
				
				
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' SIN has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'update');

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['po_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			
 $itemDetailSizeOf=	sizeof($_POST['id_req_no']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
	  $id_inv_indent1 = explode('-', $_POST['id_req_no'][$i]); 
        $id_po .= $id_inv_indent1[0].',';
 }		
$id_inv_poo = rtrim($id_po,',');



$sql12 = "SELECT * FROM inv_indent WHERE id IN ($id_inv_poo) ";
		$res1 = mysqli_query($connNew,$sql12);
			while($row1 = mysqli_fetch_object($res1)){
				$no1 .=  $row1->indent_no.',';
				$no_1 .=  date('d-m-Y' , strtotime(addslashes($row1->indent_date)));
			}
		$no = rtrim($no1,',');	
		$no_11 = rtrim($no_1,',');	




$auditquery = "SELECT * From `".TBL_INV_PURCH."` WHERE id = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";

			  $auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){
				 $id_inv_indent = $auditrow ->id_inv_po;
				 $doc_type = $auditrow ->doc_type; 
				 $department = $auditrow ->id_mst_attributes_department; 
				
				 $indent_date =  date('d-m-Y' , strtotime(addslashes($auditrow ->indent_date))); 
				 $remarks = $auditrow ->remarks; 
				 $bill_no = $auditrow ->mdoc_no; 
				}
				
		$sql122 = "SELECT * FROM inv_indent WHERE id IN ($id_inv_indent) ";
		$res12 = mysqli_query($connNew,$sql122);
			while($row11 = mysqli_fetch_object($res12)){
				$no2 .=  $row11->indent_no.',';
				$no_21 .=  date('d-m-Y' , strtotime(addslashes($row11->indent_date)));
			}
		$no21 = rtrim($no2,',');
		$no211 = rtrim($no_21,',');
					
				
				if($id_inv_indent != $id_inv_poo){
					$ch1 ="Requestio No Changed from " . $no21 ." - to - ".$no ;
				}	
				
				

				if($doc_type != $_POST['doc_type']){
					 $doc_type_s ="Document Type Details Changed from " .  $doc_type." - to - ".$_POST['doc_type'];
				}

				if($department != $_POST['id_mst_attributes_department']){ 
					$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$department."' AND table_name ='".'department'."'");
					$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['id_mst_attributes_department']."' AND table_name ='".'department'."' ");
					$ch_1 = "Department Details Changed from ". $old_data." - to - " .$new_data ;
				}
				if($indent_date != $_POST['indent_date']){
					
					$indate = date('d-m-Y' , strtotime(addslashes($_POST['indent_date']))) ;
					 $ch2 ="Indent Date Details Changed from " .  $indent_date." - to - ".$indate;
				}
				if($remarks != $_POST['remarks']){
					 $ch3 ="Remarks Details Changed from " .  $remarks." - to - ".$_POST['remarks'];
				}


//Multiple Data First Rows
				$auditquery = "SELECT * From `".TBL_INV_PURCH_DETAILS."` WHERE id = '".addslashes($_POST['update_id'])."'  ";

			  	$auditresSQL = mysqli_query($connNew, $auditquery);	
				while($auditrow = mysqli_fetch_object($auditresSQL)){ 
				
				 $id_inv_items = $auditrow ->id_inv_items;
				 $qty = $auditrow ->qty;
				 $alt_qty = $auditrow ->alt_qty;
				 $remarks_indent_details = $auditrow ->remarks_purch_details;


				if($id_inv_items != $_POST['id_inv_items']){ 
					
				//$id_inv_items_s = "Item Code Details Changed from ". $old_data ." |". $old_data1 ." |". $old_data2 ." ".$old_data3." - to - " .$new_data ." |". $new_data1 ." |".  $new_data3  ;
				}
				if($qty != $_POST['qty']){
					if($_POST['qty'.''.$i]){
						$ch4 ="Quantity Changed from " .  $qty." - to - ".$_POST['qty']. " in Rowno 1"  ;
					}	
				}
				if($alt_qty != $_POST['alt_qty']){
					if($_POST['alt_qty'.''.$i]){
					$ch5 ="Alt Quantity Changed from " .  $alt_qty." - to - ".$_POST['alt_qty']. " in Rowno 1" ;
					}
				}
				if($remarks_indent_details != $_POST['remarks_indent_details']){
					if($_POST['remarks_indent_details'.''.$i]){
					$ch6 ="Remarks  Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details']. " in Rowno 1" ;
					}
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
						$auditquery = "SELECT * From `".TBL_INV_PURCH_DETAILS."` WHERE id = '".addslashes($_POST['update_id'.''.$i])."'  ";

					  	$auditresSQL = mysqli_query($connNew, $auditquery);	
						while($auditrow = mysqli_fetch_object($auditresSQL)){ 
						
						 $id_inv_items = $auditrow ->id_inv_items;
						 $qty = $auditrow ->qty;
						 $alt_qty = $auditrow ->alt_qty;
						 $remarks_indent_details = $auditrow ->remarks_indent_details;

							
						if($id_inv_items != $_POST['id_inv_items'.''.$i]){ 
							//Answer Section
						//$id_inv_items_s = "Item Code Details Changed from ". $old_data ." |". $old_data1 ." |". $old_data3 ." ".$old_data3." - to - " .$new_data ." |". $new_data1 ." |". $new_data3 . " in Row ". $val ." ";
						}
						
						
						if($qty != $_POST['qty'.''.$i]){
							
							if($_POST['qty'.''.$i] != ''){
							 $ch7 =" Quantity  Changed from " .  $qty." - to - ".$_POST['qty'.''.$i] . " in Rowno ". $val ." <br/> ";
							}
						
						}
						if($alt_qty != $_POST['alt_qty'.''.$i]){
							if($_POST['alt_qty'.''.$i] != ''){
							 $ch8 =" Alt Quantity Changed from " .  $alt_qty." - to - ".$_POST['alt_qty'.''.$i] . " in Rowno ". $val ." <br/>";
							}
						}
						if($remarks_indent_details != $_POST['remarks_indent_details'.''.$i]){
							if($_POST['remarks_indent_details'.''.$i] != ''){
								$ch9 .=" Remarks Changed from " .  $remarks_indent_details." - to - ".$_POST['remarks_indent_details'.''.$i] . " in Rowno ". $val ." <br/> ";
							}
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
						$ne = selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$_POST['id_inv_items'.''.$i].'" ');
						$ch10 .= $ne ." Store Issue Note Details Added  <br/> ";
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


	
			
			//Update Purch Table
			 $editSql = "  UPDATE `".TBL_INV_PURCH."`  SET  
							  
							`id_inv_po` = '".addslashes($id_inv_poo)."',  						 
							`id_mst_attributes_department` = '".addslashes($_POST['id_mst_attributes_department1'])."', 
							`remarks` = '".addslashes($_POST['remarks'])."'
							";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
					executeSql($editSql);

				//Update Purch Details
							$editSql = " UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  
														   
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`alt_qty` = '".addslashes($_POST['alt_qty'])."',  
							`qty` = '".addslashes($_POST['qty'])."',  
							`alt_unit` = '".addslashes($_POST['alt_unit'])."', 
							`main_unit` = '".addslashes($_POST['main_unit'])."', 
							`remarks_purch_details` = '".addslashes($_POST['remarks_purch_details'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['update_id'])."'";
						executeSql($editSql);

							//Order Qty Check Here
							$order_total= 0;$balance_qty =0;
							$id_inv_indent_details = $_POST["id_inv_indent_details"];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  AND `doc_type` = '6'  ";
		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();
		                    $order_total = $row1->qty;
							//Total Qty Get
							$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
							$balance_qty = $total_qty - $order_total;
							//Order Qty Update Indent Details Table
							$editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
							
							
				executeSql($editSql);

				//Update Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								$editSql = "UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  
								   
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
								`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
								`qty` = '".addslashes($_POST['qty'.''.$i])."',  
								`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
								`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
								`remarks_purch_details` = '".addslashes($_POST['remarks_purch_details'.''.$i])."',
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['update_id'.''.$i])."'";
							executeSql($editSql);

							//Order Qty Check Here
							$order_total= 0;$balance_qty =0;
							$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
							$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  AND `doc_type` = '6'  ";

		                   	$db->query($sql1);
		                    $row1 = $db->fetch_object();
		                    $order_total = $row1->qty;
							//Total Qty Get
							$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
							$balance_qty = $total_qty - $order_total;
							//Order Qty Update Indent Details Table
							 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
							executeSql($editSql);
							}
				//Update Field More Fields Add Here
				
				
				
				
				
   $auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
	`tables_name` = 'inv_purch , inv_purch_details',
	`form_code` = 'Store Issue Note',
	`changes` =  '".addslashes($ch1).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9).",".addslashes($ch10)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";	


if($ch1=='' && $ch3=='' && $ch4=='' && $ch5=='' && $ch6=='' && $ch7=='' && $ch8=='' && $ch9=='' && $ch10==''  ){
						
}else{
	executeSql($auditeditSql);
}


							if($_POST['counter1'] == ''){
								$counter1 = 0;								
							}else{
								$counter1 = $_POST['counter1'];									
							}

							for($i = $counter1; $i > $update_count; $i--){

								 
								if($_POST['id_inv_items'.''.$i] != '' && $_POST['main_unit'.''.$i] !='' ){

									$addSql = "INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET
									`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
									`id_inv_po` = '".addslashes($_POST["id_inv_po".''.$i])."',   
									`id_inv_po_details` = '".addslashes($_POST["id_inv_po_details".''.$i])."',   
									`doc_type` = '".'6'."',  
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`alt_qty` = '".addslashes($_POST['alt_qty'.''.$i])."',  
									`qty` = '".addslashes($_POST['qty'.''.$i])."',  
									`alt_unit` = '".addslashes($_POST['alt_unit'.''.$i])."', 
									`main_unit` = '".addslashes($_POST['main_unit'.''.$i])."', 
									`remarks_purch_details` = '".addslashes($_POST['remarks_purch_details'.''.$i])."', 
									`id_inv_indent_details` = '".addslashes($_POST['id_inv_indent_details'.''.$i])."', 
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
								//Order Qty Check Here
								$order_total= 0;$balance_qty =0;
								$id_inv_indent_details = $_POST["id_inv_indent_details".''.$i];
								$sql1 = "SELECT sum(qty) as qty FROM `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent_details`='".$id_inv_indent_details."'  AND `doc_type` = '3'  ";
			                   	$db->query($sql1);
			                    $row1 = $db->fetch_object();
			                    $order_total = $row1->qty;
								//Total Qty Get
								$total_qty=selectColumn(TBL_INV_INDENT_DETAILS,'qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$id_inv_indent_details."' ");
								$balance_qty = $total_qty - $order_total;
								//Order Qty Update Indent Details Table
								 $editSql = "UPDATE `".TBL_INV_INDENT_DETAILS."` SET `ordered_qty` = '".$order_total."', `bal_qty` = '".$balance_qty."' WHERE `id` = '".addslashes($id_inv_indent_details)."'";
								executeSql($editSql);
							}
								
			if(1){  

				$_SESSION['successMsg'] = selectColumn(TBL_INV_PURCH, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';  
				
				if($_POST['another']!=''){
					header("location:editStoreIssueNote.php?submenu=".$_GET['submenu']."&print=1");	
				}else{
					header("location:printStoreIssueNote.php?eId=".$_GET['eId']."&session=".$_GET['session']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']."&print=1"); 
				}

				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_PURCH,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Purch has not been saved. Please make corrections.';
	}
}
// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Purch Table

	$sql = "  SELECT * FROM `".TBL_INV_PURCH."`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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

<title>RoomStatusHUB | Edit Store Issue Note Manager</title>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php //echo '<span style="color:'.$color.'">&nbsp;<i class="fa '.$icon.'"></i> '.$submenu.'</span>'; ?>
		<?php //echo '<span style="color:'.$data['color'].'">&nbsp;<i class="fa '.$data['icon'].'"></i> '.$data['submenu'].'</span>'; ?>
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>-->
      <h5 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span></h5>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <!-- Main content -->
    <section class="content">
	
	   <hr class="br-line">
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom mb-0  shadow-none">

		 
			<!--<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span>
            </div>-->
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
				
				<input type="hidden" value="<?php echo $_GET['submenu'];?>" name="submenu" id="submenu" />
					<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="store_id" id="store_id">

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
              			<h5 style="padding: 5px;">General</h5>
              		</div> -->
              	

	              	<div class="row">	

	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Document Type</label>
	              		
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" style="width:100%">	                	  		                  	  
			                  	 	<option selected="selected" value="6">Store Issue Note</option>  
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
	              			<label for="name">Store Issue Note Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Store Issue Note Date" id="po_date" name="po_date" value="<?php if($_POST) echo $_POST['po_date'];elseif($row->po_date!='') echo date('d-m-Y',strtotime($row->po_date));else echo date('d-m-Y');?>" onchange="hideandshow()" onclick="hideandshow()">
						  
						 
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
	              			<div class=" col-xs-12 col-md-12 col-sm-12">
		              				<label for="name">SIN No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Enter SIN No" id="mdoc_no2" name="mdoc_no2" value="<?php if($_POST) echo $_POST['mdoc_no2'];else echo stripslashes($row->mdoc_no);?>" readonly>
		              				</div> 
			                	</div>
	              			<div id="ind" name="ind"  style="display:none;">
	              				<div class="col-xs-6 col-md-4 col-sm-6 tab-mb" style="display:none;">
	              					<label for="name">Prefix</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
		              				</div>
			                	</div>
		              			<div class=" col-xs-6 col-md-4 col-sm-6" style="display:none;">
		              				<label for="name">SIN No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Enter SIN No" id="po_no" name="po_no" value="<?php if($_POST) echo $_POST['po_no'];else echo stripslashes($row->po_no);?>" readonly>
		              				</div> 
			                	</div>
			                	<div class=" col-xs-12 col-md-4 col-sm-6" style="display:none;">
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
				                <div class="form-group col-xs-6 col-md-4 col-sm-6">
				                  <label for="name">Manual SIN No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input type="text" class="form-control" placeholder="Enter Manual SIN No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		          	
					
					<?php if($row->id !=''){
	              				$readonly = 'disabled';
	              			}else{
	              				$readonly = '';
	              			}
	              			?>

	              		<div class="form-group col-xs-12 col-md-2 col-sm-6" >
	              			<label for="name">Department <font color="#FF0000">*</font></label>
	              			
	              			
						   	<select class="form-control select2" name="id_mst_attributes_department" id="id_mst_attributes_department" onchange="departments()" data-parsley-required data-parsley-errors-container="#outletError" <?php echo $readonly; ?> style="width:100%">

	              			<?php $categoryDropDown = '
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
								 	echo $categoryDropDown .= '</select>  <span id="outletError"></span>';
								  ?>
						<?php echo $err_deparment;?>
						
									
							<input type="text" class="form-control "  id="id_mst_attributes_department1" name="id_mst_attributes_department1" value="<?php if($_POST) echo $_POST['id_mst_attributes_department'];else echo stripslashes($row->id_mst_attributes_department);?>" style="display: none;" >			
										
							
						
								</div>
	                
					  
					   <div class="form-group col-xs-12 col-md-2 col-sm-6">
					    <label for="name">Requestion No</label>

	              			
		<?php 
		
	//echo "SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  A.id IN ($row->id_inv_po) GROUP BY B.id_inv_indent"; ?>
							
							 <select class="form-control select2" autocomplete="off" name="id_req_no[]" multiple id="id_req_no" onchange="req_details(this,this.id);" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError2">
										<?php $categoryDropDown = '<option value="">Select Requestion No</option>';

										if($row->id != ''){
											//$sql="SELECT * FROM ".TBL_INV_INDENT." WHERE id IN ($row->id_inv_po)";
											$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  A.id IN ($row->id_inv_po) GROUP BY B.id_inv_indent";
										}else{
											$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent
						                   	WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']." GROUP BY B.id_inv_indent";
										}
											
												$db->query($sql); 
												
												$id_inv_po_select = explode(',', $row->id_inv_po);
												
							                    while($row1 = $db->fetch_object()){

												if(in_array($row1->id_inv_indent,$id_inv_po_select)){
													$selected = 'selected="selected"';
													}else if($_REQUEST['id_inv_poo']){
													$selected = 'selected="selected"';
													}												
													else{
														$selected = '';
													} 

												$categoryDropDown .= '<option '.$selected.' value="'.$row1->id_inv_indent.'-'.$row1->id.'">'.ucfirst($row1->indent_no.' | '.date('d-m-Y',strtotime($row1->indent_date))).'</option>';
												}
												
											 	echo $categoryDropDown .= '</select>';  
										?> 
										
					   <span id="outletError2"></span>
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
 

			            <div class="form-group col-xs-12 col-md-2 col-sm-6" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>

		            <div class="row">
	              	</div> 

		        </div>
		    

		        <!-- The Modal -->
					<div class="modal" id="config_model"  >
					    <div class="modal-dialog modal-wd95">
					      <div class="modal-content" >
					  					      
					       <!-- Modal body -->
					        <div class="modal-body ox-sl">
					        	<input type="text" id="myInput"  class="p-5 br-grey search-wid" onkeyup="myFunction()" placeholder="Search For Item Description" title="Type In Item Description">
					        	 <input type="checkbox" name="checkbox" id="checkbox"  onclick="popupshow_checkbox(this.id);" >Show All
					        	<table id="popuptable"  class="table table-striped  table-bordered dataTable no-footer" style="background:#fff;width:1226px;display: table-caption;" border="1"> 
					        	</table>
					        </div>
					        
					        <!-- Modal footer -->
					        <div class="modal-footer">
					        	<button type="button" class="btn o-btn ok"  data-dismiss="modal" onclick="po();"><i class="fa fa-plus-circle" aria-hidden="true" ></i> Insert</button>
					          <button type="button" class="btn c-btn" data-dismiss="modal"><i class="far fa-window-close" aria-hidden="true"></i> Close</button>
					        </div> 
		              		</div>
		                </div> 
		            </div>
		            <button type="button" id="config_button" name="config_button" class="btn btn-info" data-toggle="modal" data-target="#config_model"  style="display: none"><i class="fa fa-check-square-o"> PO Help</i>
    				</button>

		         <div class="box-body table-responsive2">

              	<div class="card text-dark bg-light">
              	
	              	<div class="row">
	              		<?php 
              				$sql2 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'6'."' ";
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
		                  <input type="text" class="form-control" id="id_inv_po" name="id_inv_po"  value="<?php echo $id_indent_id; ?>" > 
		                </div>
		            </div>

		            <div class="row">
		            		 <hr class="br-line">
		            		 	<div class="text-center ">
			              			<h6 class="tb-heads">Store Issue Note Details</h6>
			              		</div> 
			           <div class="ox-scroll"> 		 
		            	<table id="myTable1" class="table table-striped  table-bordered dataTable no-footer   order-list1 max-h">
				            <thead>
				                <tr>
				                    <th>Requestion No</th>
				                    <th>Item Code</th>
				                    <th>Item Description</th> 
				                    <th>Qty</th> 
				                    <th>Unit</th> 
				                    <th>Alt.Qty</th> 
				                    <th>AltUnit</th> 				                    
				                    <th>Remarks</th> 
				                    <th></th>

				                </tr>
				            </thead>
				            <tbody id="polist">
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Purch Details Here First Row Only Select
				            	 $sql2 = "  SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items;
							 		 $array['alt_qty'.''.$i] = $rowsID->alt_qty;
							 		 $array['qty'.''.$i] = $rowsID->qty;
							 		 $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		 $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['remarks_purch_details'.''.$i] = $rowsID->remarks_purch_details;
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent;
							 		 $array['id_inv_indent_details'.''.$i] = $rowsID->id_inv_indent_details;
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
				                	<td style="width:10%">

<?php 

//$item_code  =  selectColumn(TBL_INV_INDENT_DETAILS,'id'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");



//echo $sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  B.id IN (".$array['id_inv_indent_details'.''.$j].") GROUP BY B.id_inv_indent"; ?>
									
					                 <select class="form-control select2" name="id_inv_indent<?php echo $k;?>" id="id_inv_indent<?php echo $k;?>" onchange="popupshow(this.id);" style="width:100%" data-parsley-required data-parsley-errors-container="#outletError3" >
										<?php $categoryDropDown = '<option value="">Select Requestion No</option>';

											/*$sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no, 
						                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, 
						                   	inv_items.item_code, inv_items.name, 
						                   	mst_attributes.field_value 
						                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and  inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '1'  group by inv_indent.indent_no ";	*/	



										//$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent  	WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']." GROUP BY B.id_inv_indent";


										if($row->id != ''){
											$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  B.id IN (".$array['id_inv_indent_details'.''.$j].") GROUP BY B.id_inv_indent";
											
											//$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE  A.id IN ($row->id_inv_po) GROUP BY B.id_inv_indent";
										}else{
						                   	$sql="SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent
						                   	WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']." GROUP BY B.id_inv_indent";
										} 

												$db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    if($_REQUEST['id_inv_indent'] == $row1->id_inv_indent){
													$selected = 'selected="selected"';
												}elseif($row1->id_inv_indent == selectColumn(TBL_INV_INDENT_DETAILS,'id_inv_indent',"WHERE id='".$array['id_inv_indent_details'.''.$j]."' ")){
													$selected = 'selected="selected"';													
												}else{
													$selected = '';
												}  
												$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'-'.$row1->id_inv_indent.'">'.ucfirst($row1->indent_no.' | '.date('d-m-Y',strtotime($row1->indent_date))).'</option>';
												}
												if($row->id !=''){
													if($array['id_inv_indent_details'.''.$j] == 0)	 {
														$categoryDropDown .= '<option selected="selected" value="na">NA</option>';
													}
												}
											 	echo $categoryDropDown .= '</select> <span id="outletError3"></span>';  
										?> 
										<input type="text"  autocomplete="off" name="id_inv_indent_details<?php echo $k;?>" id="id_inv_indent_details<?php echo $k;?>" placeholder="ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_indent_details'];else echo stripslashes($array['id_inv_indent_details'.''.$j]); ?>" readonly=""  style="display:none;" />
					                </td>  
					                
				                	<td style="width:7%;"> 
					                 	<input hidden id="select<?php echo $k;?>" name="select<?php echo $k;?>"> 
					                 	<?php 
				                		//Name Get
				                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			//Item Description Get
				                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			$conversion_qty =  selectColumn(TBL_INV_ITEMS,'conversion_qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                		?>
										<input type="text"  autocomplete="off" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" placeholder="Item ID"  class="form-control"  value="<?php if($_POST) echo $_POST['id_inv_items'];else echo stripslashes($array['id_inv_items'.''.$j]); ?>" style="display:none;" /> 
										<input type="text"  autocomplete="off" name="item_code<?php echo $k;?>" id="item_code<?php echo $k;?>" placeholder="Item Code"  class="form-control"  value="<?php echo $item_code; ?>" readonly="" /> 
					                </td> 
				                    <td style="width:18%;">
				                       <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"   value="<?php echo $item_description; ?>" readonly="" />
				                    </td> 
				                    <td  style="width:6%;"> 
				                        <input type="text"  autocomplete="off"  name="qty<?php echo $k;?>" id="qty<?php echo $k;?>" placeholder="Qty" onkeyup="qtycalc(this.id)" onclick="qtycalc(this.id)" class="form-control discountvalue" value="<?php if($_POST) echo $_POST['qty'];else echo stripslashes($array['qty'.''.$j]); ?>"  required />
				                    </td>
									<!--conversion-->
										<input type="hidden"  autocomplete="off"  name="conversion_qty<?php echo $k;?>" id="conversion_qty<?php echo $k;?>" placeholder="Qty"  class="form-control" value="<?php if($_POST) echo $_POST['conversion_qty'];else echo $conversion_qty; ?>" />
									<!--conversion end-->



				                    <td style="width:6%;"> 
				                        <input type="text"  autocomplete="off"  name="main_unit<?php echo $k;?>" id="main_unit<?php echo $k;?>" placeholder="Unit"  class="form-control" readonly="" value="<?php if($_POST) echo $_POST['main_unit'];else echo stripslashes($array['main_unit'.''.$j]); ?>"/>
				                    </td>
				                    <td  style="width:5%;">
				                        <input type="text"  autocomplete="off"  name="alt_qty<?php echo $k;?>" id="alt_qty<?php echo $k;?>" placeholder="Alt Qty" onkeyup="altqtycalc(this.id)" class="form-control discountvalue" value="<?php if($_POST) echo $_POST['alt_qty'];else echo stripslashes($array['alt_qty'.''.$j] ); ?>" />
				                    </td>
				                    <td  style="width:5%;"> 
				                        <input type="text"  autocomplete="off"  name="alt_unit<?php echo $k;?>" id="alt_unit<?php echo $k;?>" placeholder="Alt Unit"   class="form-control" readonly="" value="<?php if($_POST) echo $_POST['alt_unit'];else echo stripslashes($array['alt_unit'.''.$j]); ?>" />
				                        <!-- Conversion Rate Per Unit -->
					                  	 <input type="text"  autocomplete="off" name="conver_rate_per_unit<?php echo $k;?>" id="conver_rate_per_unit<?php echo $k;?>" placeholder="conver_rate_per_unit"  class="form-control discountvalue"   value="<?php if($_POST) echo $_POST['conver_rate_per_unit'];else echo stripslashes($array['conver_rate_per_unit'.''.$j]); ?>"  style="display:none;"/>
				                    </td>				                    
				                    <td  style="width:10%;"> 
				                        <input type="text"  autocomplete="off"  name="remarks_purch_details<?php echo $k;?>" id="remarks_purch_details<?php echo $k;?>" placeholder="Remarks"  class="form-control" value="<?php if($_POST) echo $_POST['remarks_purch_details'];else echo stripslashes($array['remarks_purch_details'.''.$j]); ?>"/>
				                        <input type="text"  autocomplete="off"  name="item_amount<?php echo $k;?>" id="item_amount<?php echo $k;?>" placeholder="Amount"  class="form-control" value="<?php if($_POST) echo $_POST['item_amount'];else echo stripslashes($array['item_amount'.''.$j]); ?>" style="display:none;"/>
				                    </td>
				                    <?php if($k>=1){?>
				                    <td> 

					                   	<img src="images/delete.gif"  class="ibtnDel2" style="cursor:pointer;" title="Delete" id="<?php echo $array['id'.''.$j]; ?>"  name="<?php echo $array['id'.''.$j]; ?>"/>
				                    </td>
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				                	 <td  style="width:2%;"><a class="deleteRow"></a></td>
				                </tr> 
				            	<?php } ?>
				            	<input type="text" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" hidden=""> 
				            </tbody>
				            <tfoot>
				                <tr> 
			                        <td colspan="9" style="text-align: left;">
			                            <a  type="button" class="btn n-btn btn-block"  style="font-size:14px;font-weight:700" id="addrow1" value="Add Row"><i class="fa fa-plus"></i> Add Row</a>

			                             <input  type="button" class="btn btn-sm btn-block" id="addrow2" value="Add More" style="display: none;" />
			                        </td> 
				                </tr>
				                <tr>
				                </tr>
				            </tfoot>
				        </table>
				        <!--below end of scroll-->
		            </div>
		        </div>            		 
		            
		        <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
		            <br>
		            <div class="row"> 	            	
						<div class="form-group col-xs-12 col-md-6 col-sm-2 mb-0"> 
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
 
		        	<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn" name="Save"  >
			
			   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("manageStoreIssueNote.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"); '><i class="far fa-window-close"></i>
			   	Close
			   </a>
		
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
									<a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right"><i class="fas fa-history"></i> Alteration History
				</a>
			
				<?php } ?>  
				
         	</div>

	
	<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header modal-head">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title alt-pre" id="roomtitle1">Alteration History</label>
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
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn c-btn" data-dismiss="modal"> <i class="far fa-window-close"></i>  Close</button> 
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
                <label class="modal-title" id="roomtitle1" style="font-size:22px;"> <?php echo $add; ?></label>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-striped">
				
				<div style="text-align:center;font-weight:600;font-size:15px">

<div id="mge"></div>


				<br/>
					<input type='submit' value="<?=($_REQUEST['eId']==''?'Add':'Edit')?>" class="btn btn-success" onclick="yes();" name="Save"  >
					<input type='button' value="No" class="btn btn-success" onclick="nosave();" name="no"  >
					<input type='hidden' value="" id="another" name="another"  >
				</div> 
				
			</table>
            </div>
        </div>
    </div>
</div>
<!-- End Another Modal -->					
			
              <!-- /.box-body -->	
			 <div class="box-footer p-0">                                       
			
			 <!--<input type='button' value='Another' class="btn btn-success"  onclick="saveornot();">-->		
			   
			   
   
			   <!--<?php if($row->id !=''){?>
			    &nbsp;&nbsp;&nbsp;&nbsp; 
			    <a href="printStoreIssueNote.php?eId='<?php echo $_GET['eId']; ?>'&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>&action=edit&page=<?php $_REQUEST['page']?>"  type="button" class="btn btn-success" style="padding:8px 12px;"><i class="fa fa-print" aria-hidden="true"> Print </i></a>
				
			   
			  <--  <a href="printStoreIssueNote.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>" target="_blank" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> --
			      <?php } ?>-->
			   
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	

 		
<?php include_once("../includes/footer.php");?>  
<script type="text/javascript">
 
 
 
 

	//Delete Row Section Here
	function saveornot(){
		var id_inv_indent = document.getElementById("id_inv_indent").value;
		var id_mst_attributes_department = document.getElementById("id_mst_attributes_department").value;
		var submenu = document.getElementById("submenu").value;
		var eId = document.getElementById("eId").value;
		
		
		if(eId==''){
			 $("#mge").html("You want to Add the Current Records ?");
			//document.getElementById("mge").value = "You want to Add the Current Records ?";
		}else{
			 $("#mge").html("You want to Save the Current Changes of the Records ?");
			//document.getElementById("mge").value = "You want to Save the Current Changes of the Records ?";
		}
		
		
		if(id_mst_attributes_department == '' && id_inv_indent==''){
			window.location.href="editStoreIssueNote.php?submenu="+submenu;
		}else{
			//alert();
			$('#anotherModal').modal('show');
		}
	}	
	
	function yes(){
		
		var submenu = document.getElementById("submenu").value;
		
		document.getElementById("another").value = "Another";
		$('#anotherModal').modal('hide');
		
		//window.location.href="editStoreIssueNote.php?submenu="+submenu;
	}
	
	
	
	function nosave(){
		var submenu = document.getElementById("submenu").value;
		//alert(session)
		window.location.href="editStoreIssueNote.php?submenu="+submenu;
	} 
	 
	 
	function audittrial(clicked_value){
		
		var id = document.getElementById("store_id").value;
		$('#auditModal').modal('show');
		var form_name ='Store Issue Note';
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
	 
	 

	$("table.order-list1").on("click", ".ibtnDel2", function (event) { 
		$(this).closest("tr").remove(); 
		var clicked_id = $(this).attr("id");

			$.ajax({
				type: "POST",
				url: "../ajax/SINManageDeleteRow.php",
				data:{clicked_id:clicked_id},
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

	    var po_date = document.getElementById("po_date").value; 
		 
		if(doc_type != '' && po_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/StoreIssueNoteManage.php",
				data:{doc_type:doc_type, po_date:po_date},
				success: function(data){
					var mydata = JSON.parse(data);  
					if(mydata['method'] == 1){
						$('#hideandshow').hide();   
						$('#ind').show();   
						<?php if($row->id == ''){?>
								$("#mdoc_no2").val( mydata['prefix']+mydata['po_no']+ mydata['suffix']);
							document.getElementById("po_no").value = mydata['po_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = mydata['prefix'];
						document.getElementById("suffix").value = mydata['suffix'];

					}else{
						$('#hideandshow').show();
						$('#ind').hide(); 
						<?php if($row->id == ''){?> 
							//document.getElementById("po_no").value = mydata['po_no'];
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
	$sql2 = " SELECT max(po_date) as po_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'6'."' ";
		$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$po_date= $row2->po_date;
				$po_date = date('d-m-Y' , strtotime(addslashes($po_date)));  
			}  
			if($row->id != '' && $_REQUEST['print'] == 1){ 

?>
<script type="text/javascript">
	var eid = '<?php echo $_GET['eId']; ?>';  
</script>

	<button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display: none;">
    </button>
	<!-- The Modal -->
	<div class="modal" id="myModal21">
	    <div class="modal-dialog">
	      <div class="modal-content"  style="margin-top: 50%; width: 72%;margin-left: 20%;"> 
	       
	        <!-- Modal body -->
	        <div class="modal-body">
	        	<center>
	          <a href="editStoreIssueNote.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Store Issue Note</i></a> 
	          <a href="printStoreIssueNote.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>&submenu=<?php echo $_GET["submenu"]; ?>" type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> 
	          <button type="button" class="btn btn-danger" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button>
	          <a href="manageStoreIssueNote.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>  
        	  <!-- <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button> -->
        	</center>
	        </div> 
	        
	      </div>
		</div>
	</div>
	<script type="text/javascript">
		document.getElementById('button').click();
	</script>

<?php } ?>

 <script type="text/javascript">
 	$( document ).ready(function() {
 		<?php 
 		/*	if($row->id == ''){
			$sql2 = " SELECT max(po_date) as po_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='6' ";
			$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$po_date= $row2->po_date; 
				if($po_date == ''){
					$po_date = selectColumn(TBL_DOC_TYPE_CONFIG,'effective_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='6' ");
				}
				$po_date = date('d-m-Y' , strtotime(addslashes($po_date)));  
			} */ 

?>
 	/*	var dates = '<?php echo date('d-m-Y',strtotime($po_date)); ?>';
 		//document.getElementById("po_date").value = dates; 
 		document.getElementById('po_date').click();   
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });  */

		//Button hide 
	<?php //} ?>
		 
	});
	
	
	
	
	$( document ).ready(function() {
 		
 		var dates = '<?php echo ($po_date!=''?date("d-m-Y",strtotime($po_date)):date('d-m-Y')); ?>';
 		//document.getElementById("po_date").value = dates; 
 		$('#po_date').click();  
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });

		//Button hide 
		 
	}); 
	
	
	
	
	function req_details(sel,clicked_id){

 //alert();
  		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = document.getElementById("counter1").value; 
	var eid = document.getElementById("eId").value; 
	//alert(eid);	
var id_inv_indent = [],opt;	
var len = sel.options.length;

	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			id_inv_indent.push(opt.value);
		}
	}	
		
		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_indent_details").value; 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = document.getElementById('id_inv_indent_details'+i).value;
	 			var value = document.getElementById('item_amount'+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		} 		  
		
		
			//console.log(id_inv_indent);
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					//url: "../ajax/PopupdatashowSIN.php",
					url: "../ajax/Requestion_details_SIN.php",
					data:{id_inv_indent:id_inv_indent, array:myArray,counter1:counter1,match:match,eid:eid},
					//dataType: "html",
					datatype:'JSON',					
					success: function(data){  
						//$("#popuptable").html(data); 
						
						data = JSON.parse(data);
						$("#polist").html(data.data);
						$("#counter1").val(data.count);
						var counerby = data.countby;
						var type = data.type;
						//alert(counerby);					
						for(var i=0; i<=counerby; i++){
							if(i==0){ 
								$("#qty").click();
							}else{
								$("#qty"+i).click();
							}
						}
						
					}
			}); 
	 	  //  document.getElementById('config_button').click(); 
	 	 
  	}
	
	
	
	

	//Popup Show Section
  	function popupshow(clicked_id){

 
  		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = document.getElementById("counter1").value; 
		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_indent_details").value; 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = document.getElementById('id_inv_indent_details'+i).value;
	 			var value = document.getElementById('item_amount'+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		} 		  
		
		if(match >=1){
			var id_inv_indent = document.getElementById("id_inv_indent"+match);
	    	var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value; 
		}else{
			var id_inv_indent = document.getElementById("id_inv_indent");
	    	var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value;
		}
			//console.log(id_inv_indent);
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					url: "../ajax/PopupdatashowSIN.php",
					data:{id_inv_indent:id_inv_indent, array:myArray,counter1:counter1,match:match},
					dataType: "html", 	
					success: function(data){  

						$("#popuptable").html(data); 
					}
			}); 
	 	    document.getElementById('config_button').click(); 
	 	 
  	}
  	//Popup Show Check Box
  	function popupshow_checkbox(clicked_id){

  		var checkbox = document.getElementById("checkbox").checked;
 		if(checkbox == true){
 			var checkbox = 1;
 		}else{
 			var checkbox = 0;
 		}

 
  // 		var regex = /[+-]?\d+(?:\.\d+)?/g;
		// var match = parseInt(regex.exec(clicked_id));
		var myArray = new Array();
		var counter1 = document.getElementById("counter1").value; 
		match = counter1;
		if(isNaN(parseInt(match)) == true){
			match = '0';
		}else{}
		if(counter1 == 0){
	   		myArray[0] = 0;	   		 
	    } 
	 	for(var i=0; i<=counter1; i++){
	 		if(i == 0){
	 			var id_inv_details = document.getElementById("id_inv_indent_details").value; 
		 			myArray[i] = id_inv_details;
		 			
	 		}else{
			 	var id_inv_details = document.getElementById('id_inv_indent_details'+i).value;
	 			var value = document.getElementById('item_amount'+i).value;
	 			if(value >= 0){
			 		myArray[i] = id_inv_details;
			 	}
			}
		} 		  
		
		if(match >=1){
			var id_inv_indent = document.getElementById("id_inv_indent"+match);
	    	var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value; 
		}else{
			var id_inv_indent = document.getElementById("id_inv_indent");
	    	var id_inv_indent = id_inv_indent.options[id_inv_indent.selectedIndex].value;
		}
			//console.log(id_inv_indent);
		   // Pass Ajax Data
		    $.ajax({
					type: "POST",
					url: "../ajax/PopupdatashowSIN.php",
					data:{id_inv_indent:id_inv_indent, array:myArray,counter1:counter1,match:match,checkbox:checkbox},
					dataType: "html", 	
					success: function(data){  

						$("#popuptable").html(data); 
						document.getElementById('myInput').value='';
					}
			}); 
	 	  //  document.getElementById('config_button').click(); 
	 	 
  	}
  	//Popup Window Data Get Here
	function po(){

		var checkbox = document.getElementById("checkbox").checked;
		if(checkbox == true){
	 	    $("#checkbox").prop("checked", false);
	 	}

		var wcounts = document.getElementById("wcounts").value;
		var counter1 = document.getElementById("counter1").value;  		
		var wmatch = document.getElementById("wmatch").value;
	
//alert(wmatch);
///alert(counter1);

	
		if(wmatch == counter1) {

			if(counter1 == 0){
				var loopcounting = 0;
			}else{
				var loopcounting = counter1;
			} 
			var count = 0;

			 
			for(var i = 1; i <= wcounts; i++){

				var wselect = document.getElementById("wselect"+i).value; 


				if(wselect >=1 && loopcounting == 0){
					
					//alert('0');
					//Widnow Form Date Get Here
					var wid = document.getElementById("wid"+i).value; 
					var witemid = document.getElementById("witemid"+i).value;  
					var podate = document.getElementById("podate"+i).value; 
					var pono = document.getElementById("pono"+i).value; 			 
					var wpop = document.getElementById("wpop"+i).value;
					var witem_code = document.getElementById("witem_code"+i).value;  
					var witem_description = document.getElementById("witem_description"+i).value;  
					var windent_qty = document.getElementById("windent_qty"+i).value;  
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value; 
					var wconversion_qty = document.getElementById("wconversion_qty"+i).value; 
					var wbalance = document.getElementById("wbalance"+i).value; 
					//Table Row Date Fetch Here  
					$("#conversion_qty").val(wconversion_qty);
					$("#id_inv_indent").val(wpop);
					document.getElementById("id_inv_indent_details").value = wid;
					document.getElementById("item_code").value = witem_code;
					document.getElementById("id_inv_items").value = witemid;
					document.getElementById("item_description").value = witem_description;
					document.getElementById("qty").value = wbalance; 
					document.getElementById("main_unit").value = wmain_unit; 
					document.getElementById("alt_unit").value = walt_unit; 
					document.getElementById("conver_rate_per_unit").value = wconversion_qty; 
				 

					 document.getElementById('qty').click();  
					//Form Data Empty
					document.getElementById("wselect"+i).value = '';
					loopcounting = loopcounting + 1;  
					count = count + 1;

				}else if(wselect >=1 && loopcounting>= 1){ 
				//alert('1');
				
					// //Button Click Here
					if(count != 0){
					 	document.getElementById('addrow2').click();
					}
					 var counter1 = document.getElementById("counter1").value; 
					  
					//Widnow Form Date Get Here
					var wpop = document.getElementById("wpop"+i).value;
					var wpops = document.getElementById("wpops"+i).value; 
					 
					var podate = document.getElementById("podate"+i).value; 
					var pono = document.getElementById("pono"+i).value; 
					var wid = document.getElementById("wid"+i).value;
					var witemid = document.getElementById("witemid"+i).value; 
					var witem_code = document.getElementById("witem_code"+i).value; 
					var witem_description = document.getElementById("witem_description"+i).value;
					var windent_qty = document.getElementById("windent_qty"+i).value;   
					var wmain_unit = document.getElementById("wmain_unit"+i).value; 
					var walt_unit = document.getElementById("walt_unit"+i).value;
					var wconversion_qty = document.getElementById("wconversion_qty"+i).value;
					var wbalance = document.getElementById("wbalance"+i).value;
					//alert(wconversion_qty); 
					//Table Row Date Fetch Here   
					document.getElementById("id_inv_indent_details"+counter1).value = wid;
					//document.getElementById("conversion_qty"+counter1).value = wconversion_qty;
					$("#conversion_qty"+counter1).val(wconversion_qty);
					document.getElementById("item_code"+counter1).value = witem_code;
					document.getElementById("id_inv_items"+counter1).value = witemid;
					document.getElementById("item_description"+counter1).value = witem_description;
					document.getElementById("qty"+counter1).value = wbalance;
					document.getElementById("main_unit"+counter1).value = wmain_unit; 
					document.getElementById("alt_unit"+counter1).value = walt_unit;
					document.getElementById("conver_rate_per_unit"+counter1).value = wconversion_qty;

					var id_mst_attributes_department = document.getElementById("id_mst_attributes_department");
				    var id_mst_attributes_department = id_mst_attributes_department.options[id_mst_attributes_department.selectedIndex].value; 
				    
				    var phpfile = 'StoreIssueNoteIndentoGet.php';
				     
				    
				    if(id_mst_attributes_department != '') {

						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_attributes_department:id_mst_attributes_department,counter1:counter1,wpop:wpop,wpops:wpops,podate:podate},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			//Select Box Id Set Here
				    			var count = mydata['i']; 
				    			var counter1 = mydata['counter1'];
				    			var wpop = mydata['wpop'];
				    			var wpops = mydata['wpops'];
				    			var podate = mydata['podate'];

				    			var join = "<option value='"+wpop+"' selected='selected'>" + wpops + ' | ' + podate + "</option>";
				    			 
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
				    					join += "<option value='" + mydata['id'+i] + "'>" + mydata['indent_no'+i] + ' | ' + mydata['date'+i] + "</option>";
				    				}
				    			} 
				    			document.getElementById("id_inv_indent"+counter1).innerHTML = join;
			 				}
						});
					}
					 	

						
					 document.getElementById('qty'+counter1).click();
					//Form Data Empty
					document.getElementById("wselect"+i).value = '';
					loopcounting = loopcounting + 1;
					count = count +1;

				}else{

				}
			}
		}else if(wmatch != counter1) {
			if(wmatch == 0){

				for(var i = 1; i <= wcounts; i++){

					var wselect = document.getElementById("wselect"+i).value; 		 
					if(wselect >=1){
						//Widnow Form Date Get Here
						var wid = document.getElementById("wid"+i).value;  			 
						var witemid = document.getElementById("witemid"+i).value;  			 
						var wpop = document.getElementById("wpop"+i).value; 
					var podate = document.getElementById("podate"+i).value; 
						var witem_code = document.getElementById("witem_code"+i).value;  
						var witem_description = document.getElementById("witem_description"+i).value;  
						var windent_qty = document.getElementById("windent_qty"+i).value;  
						var wmain_unit = document.getElementById("wmain_unit"+i).value; 
						var walt_unit = document.getElementById("walt_unit"+i).value;
						var wconversion_qty = document.getElementById("wconversion_qty"+i).value; 
						var wbalance = document.getElementById("wbalance"+i).value;
						//Table Row Date Fetch Here  
						$("#id_inv_indent").val(wpop);
						document.getElementById("id_inv_indent_details").value = wid;
						document.getElementById("id_inv_items").value = witemid;
						document.getElementById("item_code").value = witem_code;
						document.getElementById("item_description").value = witem_description;
						document.getElementById("qty").value = wbalance;
						document.getElementById("main_unit").value = wmain_unit; 
						document.getElementById("alt_unit").value = walt_unit; 
						document.getElementById("conver_rate_per_unit").value = wconversion_qty; 

						document.getElementById('qty').click();				 

						//Form Data Empty
						document.getElementById("wselect"+i).value = '';
						loopcounting = loopcounting + 1;  
						count = count + 1;

					}
				}

			}else if(wmatch >=1){
				for(var i = 1; i <= wcounts; i++){

					var wselect = document.getElementById("wselect"+i).value; 		 
					if(wselect >=1){
						//Widnow Form Date Get Here 
						var wpop = document.getElementById("wpop"+i).value;
						var wpops = document.getElementById("wpops"+i).value; 
						var wid = document.getElementById("wid"+i).value;
						var witemid = document.getElementById("witemid"+i).value; 
						var podate = document.getElementById("podate"+i).value; 
						var witem_code = document.getElementById("witem_code"+i).value; 
						var witem_description = document.getElementById("witem_description"+i).value;
						var windent_qty = document.getElementById("windent_qty"+i).value;   
						var wmain_unit = document.getElementById("wmain_unit"+i).value; 
						var walt_unit = document.getElementById("walt_unit"+i).value;
						var wconversion_qty = document.getElementById("wconversion_qty"+i).value;
						var wbalance = document.getElementById("wbalance"+i).value;
						 
						//Table Row Date Fetch Here   
						document.getElementById("id_inv_indent_details"+wmatch).value = wid;
						document.getElementById("item_code"+wmatch).value = witem_code;
						document.getElementById("id_inv_items"+wmatch).value = witemid;
						document.getElementById("item_description"+wmatch).value = witem_description;
						document.getElementById("qty"+wmatch).value = wbalance;
						document.getElementById("main_unit"+wmatch).value = wmain_unit; 
						document.getElementById("alt_unit"+wmatch).value = walt_unit;
						document.getElementById("conver_rate_per_unit"+wmatch).value = wconversion_qty;

						var id_mst_attributes_department = document.getElementById("id_mst_attributes_department");
				    var id_mst_attributes_department = id_mst_attributes_department.options[id_mst_attributes_department.selectedIndex].value; 
				    
				    var phpfile = 'StoreIssueNoteIndentoGet.php';
				     
					 
					 if(id_mst_attributes_department != '') {

						$.ajax({
							type: "POST",
							url: "../ajax/"+phpfile,
							data:{id_mst_attributes_department:id_mst_attributes_department,wpop:wpop,wpops:wpops,podate:podate},
							success: function(data){
								//console.log(data);
								var mydata = JSON.parse(data); 

				    			//Select Box Id Set Here
				    			var count = mydata['i']; 
				    			var counter1 = mydata['counter1'];
				    			var wpop = mydata['wpop'];
				    			var wpops = mydata['wpops'];
				    			var podate = mydata['podate'];

				    			var join = "<option value='"+wpop+"' selected='selected'>" + wpops + ' | ' + podate + "</option>";
				    			 
				    			for(var i=1; i<count;i++){
				    				if(wpop != mydata['id'+i]){
				    					join += "<option value='" + mydata['id'+i] + "'>" + mydata['indent_no'+i] + ' | ' + mydata['date'+i] + "</option>";
				    				}
				    			} 
				    			document.getElementById("id_inv_indent"+wmatch).innerHTML = join;
			 				}
						});
					}
					
					 document.getElementById('qty'+wmatch).click();

					//Form Data Empty
					document.getElementById("wselect"+i).value = '';

					}
				}
			}else{}
		} 
	}

	//Deparments Credit Days Get Here
	function departments(){ 
		var id_mst_attributes_department = document.getElementById("id_mst_attributes_department");
	    var id_mst_attributes_department = id_mst_attributes_department.options[id_mst_attributes_department.selectedIndex].value;
	    document.getElementById("id_mst_attributes_department1").value=id_mst_attributes_department;
		
	    var phpfile = 'StoreIssueNoteIndentoGet.php';
	     
	    
	    if(id_mst_attributes_department != '') {

			$.ajax({
				type: "POST",
				url: "../ajax/"+phpfile,
				data:{id_mst_attributes_department:id_mst_attributes_department},
				success: function(data){
					console.log(data);
					var mydata = JSON.parse(data); 

	    			//Select Box Id Set Here
	    			var count = mydata['i']; 
	    			var join = "<option value='' > Select Requestion No </option>";
	    			 
	    			for(var i=1; i<count;i++){
	    				join += "<option value='" + mydata['id'+i] + "'>" + mydata['indent_no'+i] + ' | ' + mydata['date'+i]  + "</option>";
	    			} 
	    			document.getElementById("id_inv_indent").innerHTML = join;
	    			document.getElementById("id_req_no").innerHTML = join;
 				}
			});
		} 
	}


//Select 2  Resolve Here


	 

    $("#addrow1").on("click", function () { 	
		
		var counter11 =  document.getElementById("counter1").value;  
        
        counter11++;   

if(counter11==0){
	var counter1 =  ''; 
}else{
	var counter1 =  counter11; 
}		

        var id_mst_attributes_department = document.getElementById("id_mst_attributes_department");
	    var id_mst_attributes_department = id_mst_attributes_department.options[id_mst_attributes_department.selectedIndex].value; 
	    
	    var phpfile = 'StoreIssueNoteIndentoGet.php';
	     
	    
	    if(id_mst_attributes_department != '') {

			$.ajax({
				type: "POST",
				url: "../ajax/"+phpfile,
				data:{id_mst_attributes_department:id_mst_attributes_department},
				success: function(data){
					//console.log(data);
					var mydata = JSON.parse(data); 

	    			//Select Box Id Set Here
	    			var count = mydata['i']; 
	    			var join = "<option value='' selected='selected'> Select </option>";
	    			 
	    			for(var i=1; i<count;i++){
	    				join += "<option value='" + mydata['id'+i] + "'>" + mydata['indent_no'+i] + ' | ' + mydata['date'+i] + "</option>";
	    			} 
	    			document.getElementById("id_inv_indent"+counter1).innerHTML = join;
 				}
			});
		}     

        var newRow1 = $("<tr>");
        var cols1 = ""; 
       
/*

'<td><select onchange="popupshow1(this.id)"  name="id_inv_indent' + counter1 + '" id="id_inv_indent' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Requestion No</option><?php 
	                $sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no, 
						                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, 
						                   	inv_items.item_code, inv_items.name, 
						                   	mst_attributes.field_value 
						                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' group by inv_indent.indent_no  ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id."-".$row1->id_inv_indent; ?>"><?php echo $row1->indent_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->indent_date))) ?></option> <?php } 
                  	?></select> </td>';
*/	
	   
	   
       cols1 += '<td><select onchange="popupshow(this.id)"  name="id_inv_indent' + counter1 + '" id="id_inv_indent' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Requestion No</option><?php 
	                //$sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no,  inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit,inv_items.item_code, inv_items.name,  	mst_attributes.field_value 	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' group by inv_indent.indent_no  ";
	                
					
					$sql = "SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']." GROUP BY B.id_inv_indent";
					
					
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id."-".$row1->id_inv_indent; ?>"><?php echo $row1->indent_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->indent_date))) ?></option> <?php } 
                  	?></select> </td>';

            cols1 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="ID" class="form-control" name="id_inv_indent_details' + counter1 + '" id="id_inv_indent_details' + counter1 + '" readonly=""/></td>';

        cols1 += '<td><div id="hideshow_item_code'+ counter1 +'"><input type="text"  autocomplete="off" placeholder="Item Code" class="form-control" name="item_code' + counter1 + '" id="item_code' + counter1 + '" readonly=""/>';
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';

		cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue" onkeyup="qtycalc_rows(this.id)" onclick="qtycalc_rows(this.id)" name="qty' + counter1 + '" id="qty' + counter1 + '"/><input  type="hidden"  autocomplete="off" placeholder="Qty" class="form-control" name="conversion_qty' + counter1 + '" id="conversion_qty' + counter1 + '"/></td>';  
		
	

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Unit" class="form-control" name="main_unit' + counter1 + '" id="main_unit' + counter1 + '" readonly=""/></td>'; 

        cols1 += '<td><input onkeyup="altqtycalc_rows(this.id)" type="text"  autocomplete="off" placeholder="Alt Qty" class="form-control discountvalue" name="alt_qty' + counter1 + '" id="alt_qty' + counter1 + '"/></td>'; 

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control" name="alt_unit' + counter1 + '" id="alt_unit' + counter1 + '" readonly=""/><input type="text"  autocomplete="off" placeholder="conver_rate_per_unit" class="form-control discountvalue"  name="conver_rate_per_unit' + counter1 + '" id="conver_rate_per_unit' + counter1 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="Item ID" class="form-control" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" style="display:none;"/></td>';         

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Remarks" class="form-control" name="remarks_purch_details' + counter1 + '" id="remarks_purch_details' + counter1 + '"/><input  type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="item_amount' + counter1 + '" id="item_amount' + counter1 + '" style="display:none;"/></td>'; 		  
		cols1 += '<td><img src="images/delete.gif"  class="ibtnDel1" style="cursor:pointer;" title="Delete"/></td>'; 
		document.getElementById("counter1").value = counter1;  
		newRow1.append(cols1);
        $("table.order-list1").append(newRow1); 
          $(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) {
        $(this).closest("tr").hide();                
    }); 
    //Two Table
    $("#addrow2").on("click", function () { 	
		
		var counter2 =  document.getElementById("counter1").value;  
        
        counter2++;    
     

        var newRow1 = $("<tr>");
        var cols1 = ""; 
       
       cols1 += '<td><select onchange="popupshow(this.id)"  name="id_inv_indent' + counter2 + '" id="id_inv_indent' + counter2 + '" class="form-control select3"  style="width:100%"><option>Select Requestion No</option><?php 
	              //  $sql = "SELECT inv_indent.indent_date, inv_indent.mdoc_no,  inv_indent.indent_no,  inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id, inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, inv_items.item_code, inv_items.name,  mst_attributes.field_value FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' group by inv_indent.indent_no  ";
					
	               
					$sql = "SELECT A.indent_no,A.indent_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']." GROUP BY B.id_inv_indent";
					
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id."-".$row1->id_inv_indent; ?>"><?php echo $row1->indent_no.' | '.date('d-m-Y' , strtotime(addslashes($row1->indent_date))) ?></option> <?php } 
                  	?></select> </td>';

            cols1 += '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="ID" class="form-control" name="id_inv_indent_details' + counter2 + '" id="id_inv_indent_details' + counter2 + '" readonly=""/></td>';

        cols1 += '<td><div id="hideshow_item_code'+ counter2 +'"><input type="text"  autocomplete="off" placeholder="Item Code" class="form-control" name="item_code' + counter2 + '" id="item_code' + counter2 + '" readonly=""/>';
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter2 + '" id="item_description' + counter2 + '" readonly=""/></td>';

		cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Qty" class="form-control discountvalue" onkeyup="qtycalc_rows(this.id)" onclick="qtycalc_rows(this.id)" name="qty' + counter2 + '" id="qty' + counter2 + '"/></td>';  

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Unit" class="form-control" name="main_unit' + counter2 + '" id="main_unit' + counter2 + '" readonly=""/></td>'; 

        cols1 += '<td><input onkeyup="altqtycalc_rows(this.id)" type="text"  autocomplete="off" placeholder="Alt Qty" class="form-control discountvalue" name="alt_qty' + counter2 + '" id="alt_qty' + counter2 + '"/></td>';
        // conversion_qty	
        cols1 += '<input  type="hidden"   placeholder="Con Qty" class="form-control" name="conversion_qty' + counter2 + '" id="conversion_qty' + counter2 + '"/>'; 

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Alt Unit" class="form-control" name="alt_unit' + counter2 + '" id="alt_unit' + counter2 + '" readonly=""/><input type="text"  autocomplete="off" placeholder="conver_rate_per_unit" class="form-control discountvalue"  name="conver_rate_per_unit' + counter2 + '" id="conver_rate_per_unit' + counter2 + '" style="display:none;"/><input type="text"  autocomplete="off" placeholder="Item ID" class="form-control" name="id_inv_items' + counter2 + '" id="id_inv_items' + counter2 + '" style="display:none;"/></td>';         

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Remarks" class="form-control" name="remarks_purch_details' + counter2 + '" id="remarks_purch_details' + counter2 + '"/><input  type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="item_amount' + counter2 + '" id="item_amount' + counter2 + '" style="display:none;"/></td>'; 		  
		cols1 += '<td><img src="images/delete.gif"  class="ibtnDel1" style="cursor:pointer;" title="Delete"/></td>'; 
		document.getElementById("counter1").value = counter2;  
		newRow1.append(cols1);
        $("table.order-list1").append(newRow1); 
          $(".select3").select2({});
         
         $(".select3").last().next().next().remove();

        
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
						console.log(data); 
						var mydata = JSON.parse(data);
 
						document.getElementById("item_description"+match).value = mydata['name'];
						document.getElementById("alt_unit"+match).value = mydata['alt_unit'];
						document.getElementById("main_unit"+match).value = mydata['main_unit'];

						document.getElementById("conversion_qty"+match).value = mydata['conversion_qty'];

						var conversion_qty=document.getElementById("conversion_qty"+match).value;

						var main_unit_row = document.getElementById("main_unit"+match).value;
						var alt_unit1_row = document.getElementById("alt_unit"+match).value;

						if(main_unit_row == alt_unit1_row){ 
					     	var qty = document.getElementById("qty"+match).value; 	 
							var kg = conversion_qty; 
							var grams = qty * kg;  
							document.getElementById("alt_qty"+match).value = grams; 
						}else{
							var qty = document.getElementById("qty"+match).value; 	 
							var kg = conversion_qty; 
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
						//console.log(data); 
						var mydata = JSON.parse(data);

						document.getElementById("item_description").value = mydata['name'];
						document.getElementById("alt_unit").value = mydata['alt_unit'];
						document.getElementById("main_unit").value = mydata['main_unit'];  

						document.getElementById("conversion_qty"+match).value = mydata['conversion_qty'];

						var conversion_qty=document.getElementById("conversion_qty"+match).value;

						var main_unit = document.getElementById("main_unit").value;
						var alt_unit = document.getElementById("alt_unit").value;

						if(main_unit == alt_unit){
							var qty = document.getElementById("qty").value;
							var kg = conversion_qty; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}else{
							var qty = document.getElementById("qty").value;
							var kg = conversion_qty; 
							var grams = qty * kg;  
							document.getElementById("alt_qty").value = grams;
						}  
					}
				});
			}	
	    
		}
			
	}
	
	
function qtycalc(clicked_id){
//alert(clicked_id);
	var eid = document.getElementById("eId").value;	
		
		var counter1 = document.getElementById("counter1").value;
			
		
//if(eid == ''){
	
//alert(counter1);
		for(var i = 0; i <= counter1; i++){
				if(i==0){
					var main_unit = document.getElementById("main_unit").value;
					var alt_unit = document.getElementById("alt_unit").value;


					if(main_unit == alt_unit){
						var qty = document.getElementById("qty").value;
						var kg = document.getElementById("conversion_qty").value; 
						
			//alert(qty);			
			//alert(kg);			
						var grams = qty * kg;  
						document.getElementById("alt_qty").value = grams;
					}else{
						var qty = document.getElementById("qty").value;
						var kg = document.getElementById("conversion_qty").value; 
						var grams = qty * kg;  
						document.getElementById("alt_qty").value = grams;
					}
				}else{
					var main_unit_row = document.getElementById("main_unit"+i).value;
					var alt_unit1_row = document.getElementById("alt_unit"+i).value;

					if(main_unit_row == alt_unit1_row){ 
						var qty = document.getElementById("qty"+i).value; 	 
						var kg = document.getElementById("conversion_qty"+i).value; 
						var grams = qty * kg;  
						document.getElementById("alt_qty"+i).value = grams; 
					}else{
						var qty = document.getElementById("qty"+i).value; 	 
						var kg = document.getElementById("conversion_qty"+i).value; 
						var grams = qty * kg;  
						document.getElementById("alt_qty"+i).value = grams; 
					}
				}
			}
	//}	
	
}	
	
	
	
	
	

	//Quantity Check Here
	/* function qtycalc(clicked_id){

		<?php  if($row->id ==''){ ?>

		var main_unit = document.getElementById("main_unit").value;
		var alt_unit = document.getElementById("alt_unit").value;

		if(main_unit == alt_unit){
			var qty= document.getElementById("qty").value;
			var kg = document.getElementById("conversion_qty").value; 
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}else{
			var qty = document.getElementById("qty").value;
			var kg = document.getElementById("conversion_qty").value; 
			
			
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}
		

		<?php  }else{ ?>
		
		var regex = /[+-]?\d+(?:\.\d+)?/g;
	     	var match = parseInt(regex.exec(clicked_id));
			
			var main_unit_row = document.getElementById("main_unit"+match).value;
					var alt_unit1_row = document.getElementById("alt_unit"+match).value;

					if(main_unit_row == alt_unit1_row){ 
						var qty = document.getElementById("qty"+match).value; 	 
						var kg = document.getElementById("conversion_qty"+match).value; 
						var grams = qty * kg;  
						document.getElementById("alt_qty"+match).value = grams; 
					}else{
						var qty = document.getElementById("qty"+match).value; 	 
						var kg = document.getElementById("conversion_qty"+match).value; 
						var grams = qty * kg;  
						document.getElementById("alt_qty"+match).value = grams; 
		<?php }?>
 
	} */

	//Quantity Rows Section Here Check Here
	function qtycalc_rows(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
     	var match = parseInt(regex.exec(clicked_id)); 
     	var main_unit_row = document.getElementById("main_unit"+match).value;
		var alt_unit1_row = document.getElementById("alt_unit"+match).value;

//alert(clicked_id);

		if(main_unit_row == alt_unit1_row){ 
		//alert();
	     	var qty = document.getElementById("qty"+match).value; 	 
			var kg = document.getElementById("conversion_qty"+match).value; 
			var grams = qty * kg; 
			document.getElementById("alt_qty"+match).value = grams; 
		}else{
			//alert(match);
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
				var kg = document.getElementById("conversion_qty").value; 
				
				
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
				var kg = document.getElementById("conversion_qty").value; 
				
				
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
			var alt_unit1_row = document.getElementById("alt_unit"+match).value;

			if(main_unit_row == alt_unit1_row){ 
		     	var alt_qty = document.getElementById("alt_qty"+match).value; 
				var kg = document.getElementById("conversion_qty"+match).value; 
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
		var alt_unit1_row = document.getElementById("alt_unit"+match).value;

		if(main_unit_row == alt_unit1_row){ 
	     	var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = document.getElementById("conversion_qty"+match).value; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}else{
			var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = document.getElementById("conversion_qty"+match).value; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}    			 
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

