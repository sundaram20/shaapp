<?php include_once("../config/auto_loader.php");
$image_display_path = $UPLOAD_FILES_PATH."/outlets/";
include_once("include/pos_function.php");
include_once("include/function.php");

////////////////////////////////////////////////////////////////////////

/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION);
echo '</pre>';

if($_REQUEST['updateid']!=''){
echo encryptor(decrypt,$_REQUEST['updateid']);

}
die;*/
/*if($_REQUEST['id_posbilling']!=''){
echo $_REQUEST['id_posbilling'];

}*/
//die;
$date 				   =    date('Y-m-d');
$po_date		 		=	date('Y-m-d' , strtotime(addslashes($_POST['po_date'])));
$doc_type		 	   =	$_REQUEST['doc_type'];
$pos_bill_type	      = 	2; //'1 For KOT and 2 For sale';
$kot_doc_no	         =	@implode(',' , $_REQUEST['id_kot']);
$pos_purch_id_array	 =	'';
$item_BillSplit		 =	array();

foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
			array_push($item_BillSplit,$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']);
		}	
sort($item_BillSplit);
$arrayBillSplit = array_unique($item_BillSplit);
count($arrayBillSplit);


foreach($arrayBillSplit as $Count){
	
	
$doc_type=$_REQUEST['doc_type'];
$id_subsection	=	$_REQUEST['outlet'];
$retunDocConfig	=	docConfigNoValidator($doc_type,$po_date,$id_subsection);

$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no=$retunDocConfig['po_no'];
$mdoc_no=$retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix=$retunDocConfig['prefix'];
$suffix	= $retunDocConfig['suffix'];

//die;
	
	/*$id_doc_type_configuration = selectColumn(TBL_DOC_TYPE_CONFIG,'id',"Where `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."'");
	
$po_no = selectColumn(TBL_PURCH,'doc_no',"Where `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id_doc_type_configuration."' order by id desc");
if($po_no==''){
	 $sql4 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."'  ";
	$db->query($sql4);  
	$numRows= $db->num_rows(); 
	while($row4 = $db->fetch_object()){	
	
		if($row4->effective_date <= $date and $row4->effective_date <= $po_date){	
			 $idss = $row4->id;
		 } 
	}

$sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ";


	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$id= $row->id; 
	    	$method= $row->method; 
	    	$start_no= $row->start_no;
			$po_no=$row->start_no;
	    	$prefix= $row->prefix; 
		    $suffix= $row->suffix;  
	    }
	
	}else{
		$po_no	=$po_no+1;
		}*/


		$id_pos_details_split	=array();
		
	foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		
		
			if($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']==$Count){
				
				$id_pos_details_split[]	=	$_REQUEST['id_pos_detail'][$porchdetailID]['id'];
				
				$discount_amount_additional = ($_REQUEST['additional_discount_amount']/count($arrayBillSplit));

				$others_charges_net_amount = ($_REQUEST['others_charges_net_amount']/count($arrayBillSplit));
			
				$sgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_sgst'];

				$cgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cgst'];

				$igst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_igst'];

				$cess_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_cess'];

				$vat_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_vat'];

				$surcharge_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_Tax_surcharge'];
 		
				$sub_total_items += $_REQUEST['id_pos_detail'][$porchdetailID]['item_TotalAmountItem'];

				$total_item_discount_amount  += $_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'];
							
				$round_off_amount	  = $_REQUEST['round_off_amount'];
				
				}
			}
			$net_amount			= ($_REQUEST['sub_total_items']+$_REQUEST['sc_sgst']+$_REQUEST['sc_cgst']+$_REQUEST['service_charge_amount']+$sgst_net_amount+$cgst_net_amount +$igst_net_amount + $cess_net_amount +$vat_net_amount +$surcharge_net_amount)-($total_item_discount_amount);
			
			 $totalBeforeRound	=(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			 $net_amount		 =(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			
			$RoundOfAmount		=	round((round($net_amount,0)-$totalBeforeRound),2);
			
			$grant_total_amount=stripslashes(round($net_amount,0));
			
			 $id_pos_details_split2	=implode(',',$id_pos_details_split);
			
			$sub_total_items	  = $_REQUEST['sub_total_items'];
			
			if($_REQUEST['id_posbilling']==''){ //ADD 
		$addSql  = "  INSERT INTO `".TBL_PURCH."` SET
				`id_shop` = '".$_SESSION['shop']."',
				`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
				`doc_no`='".$po_no."',
				`sc_reverse`= '".$_REQUEST['revServiceCharge']."',	
				`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
				`mdoc_no`=	'".($_REQUEST['prefix']).($po_no).($_REQUEST['suffix'])."',
				`doc_type` = '".$doc_type."',
				`id_mst_outlet` = '".$_REQUEST['outlet']."',				
				`pos_bill_type`='".$pos_bill_type."',
				`kot_doc_no` = '".$kot_doc_no."',
				`id_pos_details_split` = '".$id_pos_details_split2."',
				`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
				`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
				`pax` = '".$_REQUEST['noOfPax']."',
				`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
				`sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',
				`discount_amount_additional`= '".$discount_amount_additional."',
				`others_charges_net_amount`= '".$others_charges_net_amount."',			
				`sgst_total_items` = '".$sgst_net_amount."',
				`cgst_total_items`= '".$cgst_net_amount."',
				`igst_total_items`= '".$igst_net_amount."',
				`cess_total_items`= '".$cess_net_amount."',
				`vat_total_items`= '".$vat_net_amount."',
				`sc_sgst`= '".$_REQUEST['sc_sgst']."',
				`sc_cgst`= '".$_REQUEST['sc_cgst']."',
				`surcharge_total_items`= '".$surcharge_net_amount."',		
				`sub_total_items`= '".$sub_total_items."',
				`total_discount_items`= '".$total_item_discount_amount."',
				`net_amount_items`= '".$net_amount."',
				`round_off_amount`= '".$RoundOfAmount."',
				`grant_total_amount`= '".$grant_total_amount."',
				`remarks` = '".$_REQUEST['remarks']."',
				";
$addSql .= "	`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`id_mst_user_created_by` = '".$_SESSION['userId']."'
				,`id_mst_user_modified_by` = '".$_SESSION['userId']."'

				";	

			// echo $addSql;			

			// die;	

			executeSql($addSql);

			$pos_purch_id= mysql_insert_id();
			}else{
				
				
/*				
$auditquery = "SELECT * From `".TBL_PURCH."` WHERE id = '".$_REQUEST['id_posbilling']."'  ";
  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $c1 = $auditrow -> id_mst_outlet;
	  $c2 = $auditrow -> doc_date;
	 
    if($c1 != $_REQUEST['outlet']){
		if($c1 == 2){$cl1='Kuuraku';}else if($c1 == 3){$cl1='Kuuraku bar';}
		if( $_POST['status'] == 1){$old_data='Active';}else{$old_data='Inactive';}
		$ch1 ="Outlet Details Changed from " .   $cl1 ." - to - " . $old_data;
	}
	if($c2 != $_REQUEST['po_date']){
		 $ch2 ="Date Details Changed from " .  $c2." - to - ". $_REQUEST['po_date'];
	}	
	}	*/
				
				
				
				$addSql  = "  UPDATE `".TBL_PURCH."` SET
				`id_shop` = '".$_SESSION['shop']."',
				`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
				`doc_no`='".$po_no."',
				`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
				`sc_reverse`= '".$_REQUEST['revServiceCharge']."',	
				`doc_type` = '".$doc_type."',
				`id_mst_outlet` = '".$_REQUEST['outlet']."',				
				`pos_bill_type`='".$pos_bill_type."',
				
				`id_pos_details_split` = '".$id_pos_details_split2."',
				`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
				`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
				`pax` = '".$_REQUEST['noOfPax']."',
				`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
				`sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',
				`discount_amount_additional`= '".$discount_amount_additional."',
				`others_charges_net_amount`= '".$others_charges_net_amount."',			
				`sgst_total_items` = '".$sgst_net_amount."',
				`cgst_total_items`= '".$cgst_net_amount."',
				`igst_total_items`= '".$igst_net_amount."',
				`cess_total_items`= '".$cess_net_amount."',
				`vat_total_items`= '".$vat_net_amount."',
				`sc_sgst`= '".$_REQUEST['sc_sgst']."',
				`sc_cgst`= '".$_REQUEST['sc_cgst']."',
				`surcharge_total_items`= '".$surcharge_net_amount."',		
				`sub_total_items`= '".$sub_total_items."',
				`total_discount_items`= '".$total_item_discount_amount."',
				`net_amount_items`= '".$net_amount."',
				`round_off_amount`= '".$RoundOfAmount."',
				`grant_total_amount`= '".$grant_total_amount."',
				`remarks` = '".$_REQUEST['remarks']."'
				";
            $addSql .= "	WHERE id='".$_REQUEST['id_posbilling']."'
				";	

			executeSql($addSql);
			
			/* $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['id']))."',
							`tables_name` = 'tbl_purch',
							`form_code` = 'billing_manager_form',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";	
         executeSql($auditeditSql); */
			

			$pos_purch_id=$_REQUEST['id_posbilling'];
				
				
			}
			
			$pos_purch_id_array = array();
			$pos_purch_id_array[]	=	$pos_purch_id;	
			//echo "hello"; exit;					
			$net_amount	='';						
			$discount_percent_additional= '';
			$discount_amount_additional ='';
			$others_charges_net_amount ='';			
			$sgst_net_amount = '';
			$cgst_net_amount = '';
			$igst_net_amount = '';
			$cess_net_amount = '';
			$vat_net_amount = '';
			$surcharge_net_amount = ''; 		
			$sub_total_items = '';
			$total_item_discount_amount ='';			
			$id_pos_details_split='';

	}
//die;

			$item_BillSplit=array();
			foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		

$auditquery = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."'  ";
  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $c1 = $auditrow -> item_discount_percent;
	///  $c2 = $auditrow -> doc_date;
	 
    if($c1 != $_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage']){
	   $ch1 ="Disc% Details Changed from " .   $c1 ." - to - " . $_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'];
	}	
	}
		
		
				
				
				
		    $insertPosKotDetail = "UPDATE `".TBL_PURCH_DETAILS."` SET 

						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',

						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_percentage'])."',

						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'])."',

						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',

						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_attributes_unit_main'])."',

						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['sale_rate'])."'

						  ";

		               $insertPosKotDetail .= "		

						WHERE `id`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."'				 

						  ";	


		
		$auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id'])."',
							`tables_name` = 'tbl_purch',
							`form_code` = 'billing_manager_form',
							`changes` =  '".addslashes($ch1)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";	
         executeSql($auditeditSql);		
						  

		executeSql($insertPosKotDetail);			

							if(!in_array($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'],$item_BillSplit)){

								$item_BillSplit[]=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

							}

							 $_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

							//$splitIDarray=$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit'];

								

			}
	 //$pos_purch_id_array	=	array('0' => 12,'1' => 13 ); 
		
	
	
	$printgroup	= fetchdataprint($pos_purch_id_array,$grouparray=0);
	
	$bill_print_preview=1;
	
	
	if($bill_print_preview==1){
		include_once("printPreview.php");
		//echo printPreview($printgroup);
	}else{
		
		printBill($printgroup);
	}
		/*	die;
			

			

			unset($_SESSION['POSKOT']);

			$pos_purch_id.'_'.'Bill Generated Successfully. Please Wait...';

			echo '<script>window.setTimeout(function() {window.location.href = "'.$SITE_URL.'/pos/managePosKot.php";}, 2000);</script>';

			

			exit;*/
