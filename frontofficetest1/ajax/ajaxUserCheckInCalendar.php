<?php
	include_once("../../config/auto_loader.php");
	include_once("../functions/function.php");
	//echo $resvId;
	

	//debugData($_REQUEST);//die;
	//$jsondeocde 			= 	json_decode($_REQUEST['bookedRoom'], true);
	//$RoomNoArray		   =	explode(',',$_REQUEST['dataselected']);
	$id_mst_room_types	 =	$_REQUEST['id_mst_room_types'];
	$id_room	 =	$_REQUEST['id_room'];
	//debugData($RoomNoArray);
	//die;
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					 
			$_REQUEST['resvId']=addslashes(encryptor(decrypt,$_REQUEST['resvId']));		 
		$TodaysData	=	$NightAuditDated;//date('Y-m-d');  
	
		$sql		   =	"SELECT * FROM ".FO_RESERVATIONS."  where id='".addslashes($_REQUEST['resvId'])."' ";
		$res 	       = 	mysqli_query($connNew,$sql);		
		$row           = 	mysqli_fetch_object($res);
		
		$checkout	 =	$row->checkout;
		$checkin	  =	$row->checkin;
		$dated 		= 	$checkin;
		$DateArray	=	array();
		
		//================================
		
		//==============================
		
		//echo '===============';
		//debugData($groupbyReservedRoom);
		//debugData($order_by_roomAllocationInArray);
		//die;
		
		
		
		
		while(strtotime($dated)!=strtotime($checkout)){
				$DateArray[]=date("Y-m-d",strtotime($dated));
				$dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));	
			}
		  $DateArray= "'".implode ( "','", $DateArray )."'";
		
		
		
		 
		 
		$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and   DATE(dated)='".addslashes($TodaysData)."' and `checkin_status`='0' ");
		
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
		$rowReservationDetail= mysqli_fetch_object($sqlOrderDetail);
			
		
		$id_fo_bill	=		selectColumn(FO_BILL,'id'," WHERE `id_reservations` = '".addslashes($_REQUEST['resvId'])."' ");
		$id_fo_folio	=		selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id_reservations` = '".addslashes($_REQUEST['resvId'])."' ");
	
		if($id_fo_bill==0 || $id_fo_bill==''){
			
			
	$id_doc_type='804'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name='fo_folio';
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	//debugData($docConfig);
	
	
	  $insertdocConfig = "INSERT INTO fo_folio  SET
				
				
				`id_mst_shops`='".$_SESSION['shop']."',				
				`id_mst_guest`='".$row->id_mst_guest."',
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
				
				`id_reservations` = '".addslashes($_REQUEST['resvId'])."',	
				`id_mst_shops`='".$_SESSION['shop']."',				
				`folio_no`='".addslashes($_REQUEST['po_no'])."',
				`id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."',
				`id_doc_type_configuration`	='".addslashes($_REQUEST['id_doc_type_configuration'])."',
				`doc_no`='".addslashes($_REQUEST['po_no'])."',
				`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
				`mdoc_no`=	'".addslashes($_REQUEST['prefix']).addslashes($_REQUEST['po_no']).addslashes($_REQUEST['suffix'])."',
				`doc_type` = '".addslashes($_REQUEST['id_doc_type_configuration'])."',
					
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
		}
			
						
		

		
					
			
			
		 $insertGrid2 =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
				
				`checkin_status`='1',
				`id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."',				 
				 `id_fo_bill`='".$id_fo_bill."'			 
				  where
				  `id_fo_reservations` = '".$_REQUEST['resvId']."'	
				   and order_by_room='".$_REQUEST['order_by_room']."'			   
				   and id_mst_room_types='".$id_mst_room_types."' 
				   and  DATE(dated) ='".$TodaysData."' and `id_mst_room_no_allocation`='".$id_room."'	";
				//echo $insertGrid;die;
			//$insertOrder	=mysqli_query($connNew,$insertGrid);
		mysqli_query($connNew,$insertGrid2);
		
		//echo 'step4';die;
			 //echo '<br>2=========='.$insertGrid2;
		  $updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='3'	  where id='".$id_room."'     ";
				//echo $insertGrid;die;
			//$insertOrder	=mysqli_query($connNew,$insertGrid);
			mysqli_query($connNew,$updateRoomstatus);	
			
			
			
			
		
		
		
			echo "Check-in Processed Successfully";
			
			
		
		}else{
			$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and   DATE(dated)='".addslashes($TodaysData)."' and `checkin_status`='1' ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			
			echo 'Check-in Status Already Update';
		}else{
			echo 'Invalid Check-in Date';
		}
			}
	