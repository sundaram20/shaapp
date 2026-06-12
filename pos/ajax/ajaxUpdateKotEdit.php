	<?php include_once("../../config/auto_loader.php");


	$returnArray['purch_id']='';
	$returnArray['msg']='';
	if(empty($_REQUEST['id_attribute_table_group']) && $_REQUEST['id_attribute_table_group']==""){
		$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Table Group.</font>';
		echo json_encode($returnArray);
		exit;
	}

	if(empty($_REQUEST['id_attribute_table'])){
		$returnArray['msg']='Please select Table.';
		echo json_encode($returnArray);
		exit;
	}

	if(empty($_REQUEST['id_attribute_shift'])){
		$returnArray['msg']='Please select shift.';
		echo json_encode($returnArray);
		exit;
	}
	
	if(empty($_REQUEST['id_attribute_steward'])){
		$returnArray['msg']='Please select steward.';
		echo json_encode($returnArray);
		exit;
	}

	if(empty($_REQUEST['pax'])){
		$returnArray['msg']='Please select Pax.';
		echo json_encode($returnArray);
		exit;
	}

checkKotIsBilledOrNot($_REQUEST['id_pos_purch']);
		
		
	
	 $pos_purch_id=$_REQUEST['id_pos_purch'];
	//debugData($_REQUEST);
	//	debugData($_SESSION);
		
//die;




	
//die;
	//exit;

	if($_REQUEST['id_pos_purch']!=''){
		
		 $pos_purch_id=$_REQUEST['id_pos_purch'];
		//ie;
	//audit trail	
	$auditquery = "SELECT * From `".TBL_PURCH."` WHERE id = '".$pos_purch_id."'  ";
	  $auditresSQL = mysqli_query($connNew, $auditquery);	

	  
		while($auditrow = mysqli_fetch_object($auditresSQL)){ 
		
		  $c1 = $auditrow -> id_attribute_table;
		  $c2 = $auditrow -> pax; ;
		  $c3 = $auditrow -> id_attribute_shift;
		  $c4 = $auditrow -> id_attribute_steward;
		 
	    if($c1 != $_REQUEST['id_attribute_table']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c1."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_table']."'  ");
			$ch1 = "Table Details Changed from ". $old_data." - to - " . $new_data;
		}
		if($c2 != $_REQUEST['pax']){
			 $ch2 ="Pax Details Changed from " .  $c2." - to - ".$_REQUEST['pax'];
		}
		 if($c3 != $_REQUEST['id_attribute_shift']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c3."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_shift']."'  ");
			$ch3 = "Shift Details Changed from ". $old_data." - to - " . $new_data;
		}
		 if($c4 != $_REQUEST['id_attribute_steward']){
			$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c4."'");
			$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_attribute_steward']."'  ");
			$ch4 = "Steward Details Changed from ". $old_data." - to - " . $new_data;
		}
	 
	 }	
	 
	//end audit	
	
		$updatePurch = executeSql("UPDATE `".TBL_PURCH."`  SET 
			`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
			`pax` = '".$_REQUEST['pax']."',
			`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
			`id_attribute_table` = '".$_REQUEST['id_attribute_table']."', 
			`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."'
			where  `id`='".$pos_purch_id."' 
		");			


							
	
}
	
		
	$k='0';	
	
	$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
	$pos_bill_type= 1; //'1 For KOT and 2 For sale';
	 $doc_type_kot	= $_REQUEST['doc_type_kot'];
	foreach($_SESSION['POSKOT'][$UniqueCodeGen]['itemID'] as $uniqueCode =>$dataCode){
		
		
		 $id_inv_items	=	$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode];
		 
		 $id_mst_charges_sales_local	=	$_SESSION['POSKOT'][$UniqueCodeGen]['id_sale_local'][$uniqueCode];
		

		// $id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");id_inv_items_details
		
//echo "SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$_SESSION['POSKOT'][$UniqueCodeGen]['itemID1'][$uniqueCode]."' ";		


$sqlitemDetail1     = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS."` WHERE id ='".$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]."' ");
$rowitemDetail1     =  mysqli_fetch_object($sqlitemDetail1);


$sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode]."' and id='".$_SESSION['POSKOT'][$UniqueCodeGen]['itemID1'][$uniqueCode]."' ");
$numitemDetail=	mysqli_num_rows($sqlitemDetail);
$rowitemDetail     =  mysqli_fetch_object($sqlitemDetail);

if($numitemDetail>0){
	$id_inv_items_details = $rowitemDetail->id;
	//$id_inv_items1 = $rowitemDetail->id_item;
}else{
	 //$id_inv_items1 = $rowitemDetail1->id;
	 $id_inv_items_details = '0';
} 
		
		 $id_inv_items1 = $_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode];
		if($_REQUEST['id_pos_purch']!='' ){	//echo 'ewwe';die;
			foreach($arrayitem   as $fvalue=>$cdata){
				 $fvalue; //debugData($cdata); 
				if($fvalue==$id_inv_items1){
					$id_inv_items1 = $cdata['id_mst_items'];//$_SESSION['POSKOT'][$UniqueCodeGen]['itemID'][$uniqueCode];
				}
			}
		}
		
		

		$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
		$resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');
		$resultCat = $db->fetch_object2($resCat);
		 $Taxapplication 	  = $resultCat->tax_applicable;
		$Totalitem_amount	= ($_SESSION['POSKOT'][$UniqueCodeGen]['price'][$uniqueCode]*$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]);
		
		if($Taxapplication==1){
								 $sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_sgst."'");

								$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cgst."'");

								$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_igst."'");
				
								$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cess."'");

								
								$sumTaxPersentge	=$sgst+$cgst+$igst+$cess;

								$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);

								$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;	

								$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	

								$Tax_igst	=	(($Totalitem_amount*($igst))/100);	

								$Tax_cess	=	(($Totalitem_amount*($cess))/100);	

								//die;

								$Tax_sgst_percentage	=	$sgst;	

								$Tax_cgst_percentage	=	$cgst;	

								$Tax_igst_percentage	=	$igst;	

								$Tax_cess_percentage	=	$cess;

								$Tax_sgst_id	=	$resultCat->id_mst_charges_sgst;	

								$Tax_cgst_id	=	$resultCat->id_mst_charges_cgst;	

								$Tax_igst_id	=	$resultCat->id_mst_charges_igst;	

								$Tax_cess_id	=	$resultCat->id_mst_charges_cess;	

									

								$Tax_vat		  = '0';	

								$Tax_surcharge	= '0';

								

								$Tax_vat_percentage		  =	'0';

								$Tax_surcharge_percentage	=	'0';

								

								$Tax_vat_id	   = '0';

								$Tax_surcharge_id = '0';

								

								$TotalAmountItem=	$Totalitem_amount+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess); 

						}elseif($Taxapplication==2){

							

								$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_vat."'");

								$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_surcharge."'");
								
								$sumTaxPersentge	=$vat+$surcharge;

								$sumTaxAmount	=(($Totalitem_amount*($vat+$surcharge))/100);

								$Tax_sgst	=	'0';	

								$Tax_cgst	=	'0';

								$Tax_igst	=	'0';

								$Tax_cess	=	'0';

								$Tax_sgst_percentage	=	'0';	

								$Tax_cgst_percentage	=	'0';	

								$Tax_igst_percentage	=	'0';	

								$Tax_cess_percentage	=	'0';

								$Tax_sgst_id	=	'0';	

								$Tax_cgst_id	=	'0';	

								$Tax_igst_id	=	'0';	

								$Tax_cess_id	=	'0';

								$Tax_vat		  =	(($Totalitem_amount*($vat))/100);	

								$Tax_surcharge	=	(($Totalitem_amount*($surcharge))/100);

								

								$Tax_vat_percentage		  =	$vat;	

								$Tax_surcharge_percentage	=	$surcharge;

								

								$Tax_vat_id	   = $resultCat->id_mst_charges_vat;

								$Tax_surcharge_id = $resultCat->id_mst_charges_surcharge;

								

								$TotalAmountItem  =	$Totalitem_amount+($Tax_vat+$Tax_surcharge); 

						}else{
							

							if($id_mst_charges_sales_local==0){

								$Tax_sgst	=	'0';
								$Tax_cgst	=	'0';
								$Tax_igst	=	'0';
								$Tax_cess	=	'0';	
								$Tax_sgst_percentage	=	'0';
								$Tax_cgst_percentage	=	'0';
								$Tax_igst_percentage	=	'0';
								$Tax_cess_percentage	=	'0';
								$Tax_sgst_id	=	'0';
								$Tax_cgst_id	=	'0';
								$Tax_igst_id	=	'0';	
								$Tax_cess_id	=	'0';
								$Tax_vat		    =    '0';
								$Tax_surcharge	  =    '0';
								$Tax_vat_percentage		  =	'0';
								$Tax_surcharge_percentage	=	'0';
								$Tax_vat_id	   = '0';
								$Tax_surcharge_id = '0';
								$sumTaxAmount	   =	'0';
								$sumTaxPersentge	= 	'0';
								$TotalAmountItem	=	$Totalitem_amount; 
							}
						}
			
	     $insertPosKotDetail = "UPDATE  `".TBL_PURCH_DETAILS."` SET 
							  
							 
							  `qty`='".addslashes($_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode])."',
							 
							 
							  `item_sgst_amount`='".addslashes($Tax_sgst)."',
							  `item_cgst_amount`='".addslashes($Tax_cgst)."',
							  `item_igst_amount`='".addslashes($Tax_igst)."',
							  `item_cess_amount`='".addslashes($Tax_cess)."',
							  `item_vat_amount`='".addslashes($Tax_vat)."',
							  `item_surcharge_amount`='".addslashes($Tax_surcharge)."'

							  

							   ";

	        	        $insertPosKotDetail .= "		
							
							,`last_modified` = '".currenDateTime()."'
							
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'	 WHERE `id_pos_purch`='".addslashes($pos_purch_id)."' and id='".$_SESSION['POSKOT'][$UniqueCodeGen]['id_purch_details'][$uniqueCode]."'				 
							  ";
					//echo $insertPosKotDetail;		
		mysqli_query($connNew,$insertPosKotDetail);
		$last_id= mysqli_insert_id($connNew);
							
					
		 $auditquery1 = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".$last_id."'  ";
			$auditresSQL1 = mysqli_query($connNew, $auditquery1);	
			
			while($auditrow1 = mysqli_fetch_object($auditresSQL1)){
				 $id = $auditrow1 -> id;
				 $item = $auditrow1 -> item_description;
				 $pos_id = $auditrow1 -> id_pos_purch;
				
				 
			$bill = selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".$pos_id."'");
	

/*	echo $qty[$k].'<br>';
	echo $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'<br>'; */
	
				if($qty[$k] != $_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode]){
				    $chh1 .= $item. " Quantity Changed from " .$qty[$k]." - to - ".$_SESSION['POSKOT'][$UniqueCodeGen]['quantity'][$uniqueCode].'<br>' ;
					
				}
			}


	
if($_REQUEST['id_pos_purch']=='' || $_REQUEST['id_pos_purch']=='undefined'){
	$chh11 = '';
}else{
	$chh11 = $chh1;
}

		
$k++;
}
//die;
$rowcount = count($qty);
$number = $k;
$lastDigit = $number % 10;
$lastDigit; // 6

$value = $rowcount - $lastDigit;


if($value != 0){
	$delete1 = $value. " Item Row Removed ";
 }else{
	 $delete1 = '';
 }
 
//echo $a; 
 
if($_REQUEST['id_pos_purch']=='' || $_REQUEST['id_pos_purch']=='undefined'){
	$delete = '';
}else{
	$delete= $delete1;
	$chh1='';
}


	
	//echo $chh11;
  $auditeditSql = " INSERT audit_trail SET 
	`voucher_id` = '".$pos_purch_id."',
	`tables_name` = 'pos_purch , pos_purch_details',
	`form_code` = 'KOT',
	`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($chh11).",".addslashes($delete)."',
	`date_created` = '".currenDateTime()."',
	`last_modified` = '".currenDateTime()."',
	`id_mst_user_modified_by` = '".$_SESSION['userId']."',
	`id_mst_user_created_by` = '".$_SESSION['userId']."',
	`type` = 2 ";	

if($chh11=='' && $ch1=='' && $ch2=='' && $ch3=='' && $ch4=='' && $delete==''){
	
}else{
	executeSql($auditeditSql);
}

//Update Total QTY=START===============================================
	$total_qty = selectColumn(TBL_PURCH_DETAILS,'sum(qty)'," WHERE `id_pos_purch`='".addslashes($pos_purch_id)."'");
	$total_adj_qty = selectColumn(TBL_PURCH_DETAILS,'sum(adj_qty)'," WHERE `id_pos_purch`='".addslashes($pos_purch_id)."'");
	$UpdateTotalQTY =  "UPDATE `".TBL_PURCH."` SET 
				
				 `total_qty`='".$total_qty."',
				 `total_adj_qty`='".$total_adj_qty."'
				
				 
				  where`id` = '".$pos_purch_id."'
				  ";   
				  mysqli_query($connNew,$UpdateTotalQTY);
						
	//Update Total QTY=END===============================================	

							
	
	
	$mdocsql = "SELECT * From `".TBL_PURCH."` WHERE id = '".$pos_purch_id."'  ";
	$mdocsql1 = mysqli_query($connNew, $mdocsql);	
	$mdocsql11 = mysqli_fetch_object($mdocsql1);
		
	$returnArray['purch_id']=$pos_purch_id;
	
if($_REQUEST['id_pos_purch']=='' || $_REQUEST['id_pos_purch']=='undefined'){	
	$returnArray['msg']='KOT NO - '.$mdoc_no.' Created successfully';
	$returnArray['printer']=encryptor(encrypt, $pos_purch_id);
}else{
	$returnArray['msg']='KOT NO - '.$mdocsql11->mdoc_no.' Updated successfully';
	$returnArray['printer']=encryptor(encrypt, $pos_purch_id);
}	
	
	
echo json_encode($returnArray);


	

	unset($_SESSION['POSKOT'][$UniqueCodeGen]);
	/*** printing end ***/


