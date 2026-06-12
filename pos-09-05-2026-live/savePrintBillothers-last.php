<?php
include_once("../config/auto_loader.php");
$image_display_path = $UPLOAD_FILES_PATH."/outlets/";
include_once("include/inv_function.php");
include_once("include/function1.php");




/*
if($_REQUEST['id_posbilling']!=''){
	$sql2 = " SELECT * FROM  pos_purch WHERE  `id`='".$_REQUEST['id_posbilling']."'";
	$db->query($sql2); 
	while($row2 = $db->fetch_object()){ 
		$id_pos_details_split = $row2->id_pos_details_split; 
	}
} */
//echo $_REQUEST['sc_sgst'].'<br>';
//echo $_REQUEST['sc_cgst'];
  
//exit;


/*for($i = 0; $i<$itemDetailSizeOf; $i++)
{
  $tyy .= $test[$i].",";
} */
//echo $id_item_name = rtrim($tyy,',');


$itemDetailSizeOf=	sizeof($_REQUEST['itemName']);
$test = $_REQUEST['itemName'];

for($i=0;$i<$itemDetailSizeOf;$i++){
        $id_item_name1 = $_REQUEST['itemName'][$i];
         
		if($id_item_name1 !=0){ 
	
			$sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$id_item_name1."'");
				$numitemDetail=	mysqli_num_rows($sqlitemDetail);
				
			if($numitemDetail>0){
				$sqlitemDetail = "SELECT *  from `".TBL_PURCH_DETAILS."` WHERE `id_mst_items_details` IN ($id_item_name1) ";
				$resitem1 = mysqli_query($connNew,$sqlitemDetail);
				$rowitemDetail = mysqli_fetch_object($resitem1);
				 $newid =  $rowitemDetail->id;
			}else{
				  $sqlitemDetail = "SELECT *  from `".TBL_PURCH_DETAILS."` WHERE `id_mst_items` IN ($id_item_name1) ";
				$resitem1 = mysqli_query($connNew,$sqlitemDetail);
				$rowitemDetail = mysqli_fetch_object($resitem1);
				 $newid =  $rowitemDetail->id;
			}
			
			 $string .= $newid.',';
		}
}
$id_select = rtrim($string,',');

/*$id_pos_details_split;

if($_REQUEST['id_posbilling']!=''){
	$id_select_val = explode(',', $id_select);
	$id_pos_details_split = explode(',', $id_pos_details_split); 
	$k=0;
	for($i=0; $i<count($id_select_val);$i++){
		if($id_select_val[$i]==$id_pos_details_split[$i]){
			if($k==0){
				$id_select  = $id_select_val[$i];
			}else{
				$id_select .= ','.''.$id_select_val[$i];			
			}
			$k++;
		}
		
	}
	
}*/


//exit;

$countno = $_REQUEST['counter'];
$date 				   =    date('Y-m-d');
$po_date		 		=	date('Y-m-d' , strtotime(addslashes($_POST['po_date'])));
$doc_type		 	   =	$_REQUEST['doc_type'];
$pos_bill_type	      = 	2; //'1 For KOT and 2 For sale';
//$kot_doc_nokot_doc_no	         =	@implode(',' , $_REQUEST['id_kot']);
$pos_purch_id_array	 =	'';
$item_BillSplit		 =	array();

foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		array_push($item_BillSplit,$_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']);
	}	
//echo $_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'];
//exit;
	
	
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
	
	
		
	/////////////////////////////////////////////////////////////////////////////////////////////		
	
	
	
	
	//////////////////////////////////////////////////////////////////////////////////////////////			
	


	$id_pos_details_split	=array();
		
	foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
		
			if($_REQUEST['id_pos_detail'][$porchdetailID]['item_BillSplit']==$Count){
				
				$id_pos_details_split[]	=	$_REQUEST['id_pos_detail'][$porchdetailID]['id'];
				
				$discount_amount_additional = ($_REQUEST['additional_discount_amount']/count($arrayBillSplit));

				$others_charges_net_amount = ($_REQUEST['others_charges_net_amount']/count($arrayBillSplit));
			
				$sgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst'];
				
				$cgst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst'];

				$igst_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_igst'];

				$cess_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_cess'];

				$vat_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_vat'];

				$surcharge_net_amount += $_REQUEST['id_pos_detail'][$porchdetailID]['item_sur'];
				
				
				/*
				$sgst_net_amount = $_REQUEST['sgst_net_amount'];
				$cgst_net_amount = $_REQUEST['cgst_net_amount'];
				$igst_net_amount = $_REQUEST['igst_net_amount'];
				$cess_net_amount = $_REQUEST['cess_net_amount'];
				$vat_net_amount = $_REQUEST['vat_net_amount'];
				$surcharge_net_amount = $_REQUEST['surcharge_net_amount'];
				*/
				
				
 		
				$sub_total_items += $_REQUEST['id_pos_detail'][$porchdetailID]['total'];

				//$total_item_discount_amount  += $_REQUEST['id_pos_detail'][$porchdetailID]['item_discount_amount'];
			 $total_item_discount_amount  = $_REQUEST['total_discount_amount'];
				
				$round_off_amount	  = $_REQUEST['round_off_amount'];
				
				}
			}
			//$net_amount			= ($_REQUEST['sub_total_items']+$_REQUEST['sc_sgst']+$_REQUEST['sc_cgst']+$_REQUEST['service_charge_amount']+$sgst_net_amount+$cgst_net_amount +$igst_net_amount + $cess_net_amount +$vat_net_amount +$surcharge_net_amount)-($total_item_discount_amount);
			
			$net_amount	  = $_REQUEST['id_pos_detail'][$porchdetailID]['total'];
			
			$totalBeforeRound	=(($net_amount+$others_charges_net_amount)-$discount_amount_additional);
			
			$RoundOfAmount		=	round((round($net_amount,0)-$totalBeforeRound),2);
			
			$grant_total_amount=stripslashes(round($net_amount,0));
			
			//$id_item_name[]	=	$id_item_name1;
			$id_pos_details_split2	=implode(',' , $id_pos_details_split);
			
			$sub_total_items	  = $_REQUEST['sub_total_items'];
			//echo "i";
			
			if($_REQUEST['id_posbilling']==''){
				
			       $addSql  = "  INSERT INTO `".TBL_PURCH."` SET
						`id_mst_outlet` = '".$_REQUEST['outlet']."',
				        `id_pos_details_split` = '".$id_select."',
						`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
						`id_shop` = '".$_SESSION['shop']."',
						`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
						`doc_no`='".$po_no."',
						`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
						`mdoc_no`=	'".($_REQUEST['prefix']).($po_no).($_REQUEST['suffix'])."',
						`doc_type` = '".$doc_type."',				
				    `pos_bill_type`='".$pos_bill_type."',
					`discount_amount_additional`= '".$_REQUEST['additional_discount_amount']."',
					`others_charges_net_amount`= '".$others_charges_net_amount."',	
				    `sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',		
					`sgst_total_items` = '".$sgst_net_amount."',
					`cgst_total_items`= '".$cgst_net_amount."',
					`igst_total_items`= '".$igst_net_amount."',
					`sub_total_items`= '".$sub_total_items."',
					`total_discount_items`= '".$_REQUEST['total_discount_amount']."',
					`grant_total_amount`= '".$_REQUEST['net_amount']."',
					`net_amount_items`= '".$_REQUEST['net_amount1']."',
				    `round_off_amount`= '".$_REQUEST['round_off_amount']."',
					`cess_total_items`= '".$_REQUEST['cess_net_amount']."',
					`vat_total_items`= '".$_REQUEST['vat_net_amount']."',
					`surcharge_total_items`= '".$_REQUEST['surcharge_net_amount']."',
					`sc_reverse`= '".$_REQUEST['revServiceCharge']."',
					`sc_sgst`= '".$_REQUEST['sc_sgst']."',
					`sc_cgst`= '".$_REQUEST['sc_cgst']."',
					`remarks` = '".$_REQUEST['remarks']."',
						";
				$addSql .= " `date_created` = '".currenDateTime()."'
					,`last_modified` = '".currenDateTime()."'
					,`id_mst_user_created_by` = '".$_SESSION['userId']."'
					,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
				    ";	
					
			executeSql($addSql);
			
			

		$sqlitemDetail2 = "SELECT id  from `".TBL_PURCH."` ORDER BY id DESC";
        $resitem2 = mysqli_query($connNew,$sqlitemDetail2);
		$rowitemDetail2=mysqli_fetch_object($resitem2);
		 $id=$rowitemDetail2->id; 
			
			// $pos_purch_id= mysqli_insert_id();
			 $pos_purch_id= $id;
			
			}else{
	
	
 $auditquery1 = "SELECT * From `".TBL_PURCH."` WHERE id = '".$_REQUEST['id_posbilling']."'  ";
  $auditresSQL1 = mysqli_query($connNew, $auditquery1);	
	while($auditrow1 = mysqli_fetch_object($auditresSQL1)){ 
	
		$c1 = $auditrow1 -> id_attribute_steward;
		$c2 = $auditrow1 -> doc_date;
		$c3 = $auditrow1 -> id_mst_outlet;
		//$c4 = $auditrow1 -> remarks;
		 
		if($c1 != $_REQUEST['id_attribute_steward']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c1."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_steward']."'  ");
		   $ch2 ="Steward Name Details Changed from " .   $old_data ." - to - " . $new_data;
		} 
		if($c2 != date('Y-m-d',strtotime($_REQUEST['po_date']))){
		   $ch3 ="Date Details Changed from " .   date('d-m-Y',strtotime($c2)) ." - to - ". date('d-m-Y',strtotime($_REQUEST['po_date']));
		}
		if($c3 != $_REQUEST['outlet']){
		   $old_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$c3."'");
			$new_data = selectColumn(mst_outlets,'name'," WHERE `id` = '".$_REQUEST['outlet']."'  ");
		   $ch4 ="Outlet Details Changed from " .   $old_data ." - to - " . $new_data;
		}
		/* if($c4 != $_REQUEST['remarks']){
		   $ch5 ="Remarks Details Changed from " . $c3 ." - to - ". $_REQUEST['remarks'];
		}	*/
	}	
			
			     $editSql  = "  UPDATE `".TBL_PURCH."` SET
					`id_mst_outlet` = '".$_REQUEST['outlet']."',
				        `id_pos_details_split` = '".$id_select."',
						`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
						`id_shop` = '".$_SESSION['shop']."',
						`id_doc_type_configuration`	='".$id_doc_type_configuration."',				
						`doc_no`='".$po_no."',
						`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
						`doc_type` = '".$doc_type."',				
						`pos_bill_type`='".$pos_bill_type."',
					`discount_amount_additional`= '".$_REQUEST['additional_discount_amount']."',
					`others_charges_net_amount`= '".$others_charges_net_amount."',	
				    `sc_charges_net_amount` = '".$_REQUEST['service_charge_amount']."',		
					`sgst_total_items` = '".$sgst_net_amount."',
					`cgst_total_items`= '".$cgst_net_amount."',
					`igst_total_items`= '".$igst_net_amount."',
					`sub_total_items`= '".$sub_total_items."',
					`total_discount_items`= '".$_REQUEST['total_discount_amount']."',
					`grant_total_amount`= '".$_REQUEST['net_amount']."',
					`net_amount_items`= '".$_REQUEST['net_amount1']."',
				    `round_off_amount`= '".$_REQUEST['round_off_amount']."',
					`cess_total_items`= '".$_REQUEST['cess_net_amount']."',
					`vat_total_items`= '".$_REQUEST['vat_net_amount']."',
					`surcharge_total_items`= '".$_REQUEST['surcharge_net_amount']."',
					`sc_reverse`= '".$_REQUEST['revServiceCharge']."',
					`sc_sgst`= '".$_REQUEST['sc_sgst']."',
					`sc_cgst`= '".$_REQUEST['sc_cgst']."',
					`remarks` = '".$_REQUEST['remarks']."'
					WHERE id='".$_REQUEST['id_posbilling']."'   ";
					
		  executeSql($editSql);
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

			$item_BillSplit = array();
			foreach($_REQUEST['id_pos_detail'] as $porchdetailID =>$dataCode){
				
				

$auditquery = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_inv_detail'])."'  ";
  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
		$c1 = $auditrow -> item_discount_percent;
		 
		if($c1 != $_REQUEST['id_pos_detail'][$porchdetailID]['discount']){
		   $ch1 ="Disc% Details Changed from " .   $c1 ." - to - " . $_REQUEST['id_pos_detail'][$porchdetailID]['discount'];
		}	
	}
	

	
	
	
	    $insertPosKotDetail = "UPDATE `".TBL_PURCH_DETAILS."` SET 
						  `qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `adj_qty`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_qty'])."',
						  `item_discount_percent`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['discount'])."',
						  `item_discount_amount`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_dis'])."',
						  `id_mst_charges_sales_interstate`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_mst_charges_sales_Interstate'])."',
						  `main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_unit'])."',
						  `rate_per_main_unit`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['item_rate'])."',
						  `item_sgst_amount` = '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_sgst']."',
						  `item_sgst_percent` = '".$_REQUEST['Tax_sgst_percentage']."',
						  `item_cgst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cgst']."',
						  `item_cgst_percent`= '".$_REQUEST['Tax_cgst_percentage']."',
						  `item_igst_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_igst']."',
						  `item_igst_percent`= '".$_REQUEST['Tax_igst_percentage']."',
						  `item_cess_amount`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['item_cess']."',
						  `item_cess_percent`= '".$_REQUEST['id_pos_detail'][$porchdetailID]['Tax_cess_percentage']."'
						   WHERE `id`='".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_inv_detail'])."'
						  ";
   

	 $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes($_REQUEST['id_pos_detail'][$porchdetailID]['id_inv_detail'])."',
							`tables_name` = 'pos_purch_table',
							`form_code` = 'LaundrySpaOthers',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";	
							
if($ch1=='' && $ch2=='' && $ch3=='' && $ch4==''){
	//echo "empty";
}else{
	//echo "no";
	executeSql($auditeditSql);
}
			
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
		include_once("printPreviewlaudryspaothers.php");
		//echo printPreview($printgroup);
	}else{
		printBill($printgroup);
	}
		/*	die;
			

			

			unset($_SESSION['POSKOT']);

			$pos_purch_id.'_'.'Bill Generated Successfully. Please Wait...';

			echo '<script>window.setTimeout(function() {window.location.href = "'.$SITE_URL.'/pos/managePosKot.php";}, 2000);</script>';

			

			exit;*/
