<?php include_once("../../config/auto_loader.php");
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
//error_reporting(E_ALL);

$returnArray['purch_id']='';
$returnArray['msg']='';
if(empty($_REQUEST['id_attribute_table_group']) && $_REQUEST['id_attribute_table_group']==""){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Table Group.</font>';
	echo json_encode($returnArray);
	exit;
}

if(empty($_REQUEST['id_attribute_table'])){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Table.</font>';
	echo json_encode($returnArray);
	exit;
}

if(empty($_REQUEST['id_attribute_shift'])){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select shift.</font>';
	echo json_encode($returnArray);
	exit;
}

if(empty($_REQUEST['pax'])){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Pax.</font>';
	echo json_encode($returnArray);
	exit;
}
/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION['POSKOT']);*/
//die;

$pos_bill_type= 1; //'1 For KOT and 2 For sale';
$doc_type_kot	= $_REQUEST['doc_type_kot'];
//$maxDocDate = selectColumn(TBL_DOC_TYPE_CONFIG,'MAX(effective_date)','WHERE id_shop="'.$_SESSION['shop'].'" AND doc_type="'.$doc_type_kot.'"');

//$doc_no_prefix = selectColumn(TBL_DOC_TYPE_CONFIG,'CONCAT(id,"|",prefix,"|",suffix)','WHERE effective_date="'.$maxDocDate.'" AND doc_type="'.$doc_type_kot.'" ');

//$doc_no=selectColumn(TBL_PURCH,'MAX(doc_no)','WHERE id_doc_type_configuration="'.explode('|',$doc_no_prefix)[0].'"')+1; 

//echo 'mdoc_no'.$mdoc_no=@explode('|',$doc_no_prefix)[1].$doc_no.@explode('|',$doc_no_prefix)[2];

$date =date('Y-m-d');
/*echo '-'.$doc_type_kot;
echo '-'.$date;
echo '-'.$id_subsection;
print_r($id_subsection);*/

$retunDocConfig	=	docConfigNoValidator($doc_type_kot,$date,$id_subsection);
//print_r($retunDocConfig);
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$doc_no=$retunDocConfig['po_no'];
$mdoc_no=$retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];

//print_r($retunDocConfig);

//die;

if($_REQUEST['id_pos_purch']=='' || $_REQUEST['id_pos_purch']=='undefined'){
	
$addSql  = " INSERT INTO `".TBL_PURCH."` SET
				`id_shop` = '".$_SESSION['shop']."',
				`id_doc_type_configuration`='".$id_doc_type_configuration."',
				`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."',
				`id_attribute_table_group`='".$_REQUEST['id_attribute_table_group']."',
				`doc_no`='".$doc_no."',
				`mdoc_no`='".$mdoc_no."',
				`doc_type`='".$doc_type_kot."',
				`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
				`pax` = '".$_REQUEST['pax']."',
				`id_attribute_table` = '".$_REQUEST['id_attribute_table']."',
				`doc_date`='".date('Y-m-d')."',
				`pos_bill_type`='".$pos_bill_type."'
				";
$addSql .= "	,`date_created` = '".currenDateTime()."'
				,`last_modified` = '".currenDateTime()."'
				,`id_mst_user_created_by` = '".$_SESSION['userId']."'
				,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
				";	
mysqli_query($connNew,$addSql);
$pos_purch_id= mysqli_insert_id($connNew);
}else{
	$pos_purch_id=$_REQUEST['id_pos_purch'];
	
	$updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
								`id_attribute_steward` = '".$_REQUEST['id_attribute_steward']."',
								`pax` = '".$_REQUEST['pax']."',
								`id_attribute_table` = '".$_REQUEST['id_attribute_table']."', 
								`id_attribute_shift` = '".$_REQUEST['id_attribute_shift']."'
								where  `id`='".$pos_purch_id."' 
						  		");
								
	executeSql("DELETE from `".TBL_PURCH_DETAILS."` where `id_pos_purch`='".$pos_purch_id."' ");
	}
		
foreach($_SESSION['POSKOT']['itemID'] as $uniqueCode =>$dataCode){
	//$dataArr=array();
	//$dataArr['id_pos_purch']=$pos_purch_id;
	
	//insertData(TBL_PURCH_DETAILS,$dataArr)
	
	$id_inv_items	=	$_SESSION['POSKOT']['itemID'][$uniqueCode];

	$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

	$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
	$resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');
	$resultCat = $db->fetch_object2($resCat);
	 $Taxapplication 	  = $resultCat->tax_applicable;
	$Totalitem_amount	= ($_SESSION['POSKOT']['price'][$uniqueCode]*$_SESSION['POSKOT']['quantity'][$uniqueCode]);
	
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
   $insertPosKotDetail = "INSERT INTO `".TBL_PURCH_DETAILS."` SET 
						  `id_pos_purch`='".addslashes($pos_purch_id)."',
						  `id_mst_items`='".addslashes($_SESSION['POSKOT']['itemID'][$uniqueCode])."',
						   `id_mst_outlet`='".addslashes($_SESSION['POSKOT']['id_outlet'][$uniqueCode])."',
						  `qty`='".addslashes($_SESSION['POSKOT']['quantity'][$uniqueCode])."',
						  `adj_qty`='".addslashes($_SESSION['POSKOT']['adj_qty'][$uniqueCode])."',
						   `item_amount_before_discount`='".addslashes($_SESSION['POSKOT']['price'][$uniqueCode])."',
						  `item_amount`='".addslashes($_SESSION['POSKOT']['price'][$uniqueCode])."',
						  `item_description`='".addslashes($_SESSION['POSKOT']['name'][$uniqueCode])."',
						  
						  `item_sgst_amount`='".addslashes($Tax_sgst)."',
						  `item_cgst_amount`='".addslashes($Tax_cgst)."',
						  `item_igst_amount`='".addslashes($Tax_igst)."',
						  `item_cess_amount`='".addslashes($Tax_cess)."',
						  `item_vat_amount`='".addslashes($Tax_vat)."',
						  `item_surcharge_amount`='".addslashes($Tax_surcharge)."',
						  

						  `item_sgst_percent`='".addslashes($sgst)."',
						  `item_cgst_percent`='".addslashes($cgst)."',
						  `item_igst_percent`='".addslashes($igst)."',
						  `item_cess_percent`='".addslashes($cess)."',
						  `item_vat_percent`='".addslashes($vat)."',
						  `item_surcharge_percent`='".addslashes($surcharge)."',

						 `id_mst_charges_sgst`='".addslashes($Tax_sgst_id)."',						
						 `id_mst_charges_cgst`='".addslashes($Tax_cgst_id)."',						
						 `id_mst_charges_igst`='".addslashes($Tax_igst_id)."',						
						 `id_mst_charges_cess`='".addslashes($Tax_cess_id)."',						
						 `id_mst_charges_vat`='".addslashes($Tax_vat_id)."',						
						 `id_mst_charges_surcharge`='".addslashes($Tax_surcharge_id)."',
						  `id_mst_charges_sales_local`='".addslashes($id_mst_charges_sales_local)."',
						  
						  ";

	$insertPosKotDetail .= "		
						`date_created` = '".currenDateTime()."'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_created_by` = '".$_SESSION['userId']."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'					 
						  ";
						 //echo $insertPosKotDetail;
						 
						 // die;
	executeSql($insertPosKotDetail);
}

$returnArray['purch_id']=$pos_purch_id;
$returnArray['msg']='KOT Created successfully';
echo json_encode($returnArray);


$sqlDIffPrint = "SELECT C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."'  GROUP BY id_printer ORDER BY id_printer";	
$resDiffPrint = mysqli_query($connNew,$sqlDIffPrint);



while($rowDiffPrint = mysqli_fetch_object($resDiffPrint)){

	$sqlToPrint = "SELECT B.qty,C.name,C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."' AND C.id_mst_attributes_printer='".$rowDiffPrint->id_printer."' ";	

	$resToPrint = mysqli_query($connNew,$sqlToPrint);

	$ip_address = selectColumn(TBL_ATTRIBUTES,'field_description','WHERE table_name="printer" AND id="'.$rowDiffPrint ->id_printer.'"');
	
		
	/** Connecting To Printer **/
	//$connector = new NetworkPrintConnector($ip_address);
				

	/* Start the printer */
	//$printer = new Printer($connector);

	/* Print top logo */
	//$logo = EscposImage::load("resources/escpos-php.png", false);
	//$printer -> setJustification(Printer::JUSTIFY_CENTER);
	//$printer -> graphics($logo);

	/*** printing Data ***/
	/*$printer -> setJustification(Printer::JUSTIFY_LEFT);
	$printer -> text(str_pad("KOT NO : ".selectColumn(TBL_PURCH,'mdoc_no','WHERE id="'.$pos_purch_id.'" '),25," "));

	$printer -> setJustification(Printer::JUSTIFY_RIGHT);
	$printer -> text('Date : '.date('d-M-Y')."\n");

	$printer -> setJustification(Printer::JUSTIFY_LEFT);
	$id_table = selectColumn(TBL_PURCH,'id_attribute_table','WHERE id="'.$pos_purch_id.'" ');
	$printer->text(str_pad('Table No : '.selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="table" AND id="'.$id_table.'" '),25," "));

	$id_steward = selectColumn(TBL_PURCH,'id_attribute_steward','WHERE id="'.$pos_purch_id.'" ');
	$printer -> setJustification(Printer::JUSTIFY_RIGHT);
	$printer->text('Time : '.date('H:i:s')."\n");
	$printer -> setJustification(Printer::JUSTIFY_LEFT);
	$printer->text(str_pad('Pax : '.selectColumn(TBL_PURCH,'pax','WHERE id="'.$pos_purch_id.'" '),25," "));

	$printer->text('Steward : '.selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="steward" AND  id="'.$id_steward.'" ')."\n");

	$printer->text("-----------------------------------------------\n");
	$printer->text(str_pad("S.no.  Description ",38," "));
	$printer -> setJustification(Printer::JUSTIFY_LEFT);
	$printer->text("Qty");
	$printer->text("\n----------------------------------------------\n");
	$sno=1;
	while($rowToPrint = mysqli_fetch_object($resToPrint)){
		$printer -> setJustification(Printer::JUSTIFY_LEFT);
		$printer->text(str_pad($sno++.".       ".strtoupper($rowToPrint->name),38," "));
		$printer -> setJustification(Printer::JUSTIFY_RIGHT);
		$printer->text(round($rowToPrint->qty,0)."\n");
	}
	$printer -> cut();
	$printer -> pulse();
	$printer -> close();*/
}

unset($_SESSION['POSKOT']);
/*** printing end ***/


