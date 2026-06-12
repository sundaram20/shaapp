<?php 


	     $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
			
			
	 $id_doc_type='804'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name='fo_folio';
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	//debugData($docConfig);
	
	//die;
	  $insertdocConfig = "INSERT INTO fo_folio  SET
				
				
				`id_mst_shops`='".$_SESSION['shop']."',				
				`id_mst_guest`='".$id_mst_guest."',
				`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
				`doc_no`='".addslashes($docConfig['po_no'])."',
				`doc_date`='".date('Y-m-d',strtotime($NightAuditDated))."',
				`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
				`doc_type` = '".addslashes($id_doc_type)."',
					
					`date_created` = '".currenDateTime()."',
					`id_mst_user_created_by` = '".$_SESSION['userId']."',
					`last_modified` = '".currenDateTime()."',
					`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
					mysqli_query($connNew,$insertdocConfig);
					$id_fo_folio = mysqli_insert_id($connNew);
					
			//FOLIO END=====================================	
			
			
			//Fo BILL=======================================================
			
			
			
		 $insertGrid = "INSERT INTO ".FO_BILL."  SET
				
				`id_reservations` = '".addslashes($resvId)."',	
				`id_mst_shops`='".$_SESSION['shop']."',		
				
				`id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."',
					
					`date_created` = '".currenDateTime()."',
					`id_mst_user_created_by` = '".$_SESSION['userId']."',
					`last_modified` = '".currenDateTime()."',
					`id_mst_user_modified_by` = '".$_SESSION['userId']."' ";
					//echo $insertGrid;die;
			mysqli_query($connNew,$insertGrid);
			$id_fo_bill = mysqli_insert_id($connNew);
		 
		 //Fo BILL=======================================================
		
		
		
		
		 $updateFolioGrid =  "UPDATE `fo_folio` SET 			 
				`id_fo_bill`='".addslashes($id_fo_bill)."'			 
				  where`id` IN (".$id_fo_folio.")   ";
				
		mysqli_query($connNew,$updateFolioGrid);

		if (isset($_REQUEST['id_owner_room'])) {
			if ($_REQUEST['id_owner_room'] != 0) {
				$room_no = $_REQUEST['id_owner_room'];
				$updateFoBill =  "UPDATE `fo_bill` SET `id_owner_room`='".$room_no."' where`id` = '".$id_fo_bill."'";
				mysqli_query($connNew,$updateFoBill);
			}
		}
			
		 
		?>