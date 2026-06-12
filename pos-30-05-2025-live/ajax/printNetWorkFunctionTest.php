<?php

if($configSetting!='1'){

include_once("../../config/auto_loader.php");
}
require  '../../phplib/printerLib/autoload.php';
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;



error_reporting(E_ALL);
if($_REQUEST['print_posid']!=''){
	$pos_purch_id= $_REQUEST['print_posid'];
}
$id_mst_doc_type_config = selectColumn(TBL_PURCH,'id_doc_type_configuration'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$pos_purch_id."'  ");
$EnableSplitPrint = selectColumn(TBL_DOC_TYPE_CONFIG,'enable_split_print'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");
$EnablePrintAfterSave = selectColumn(TBL_DOC_TYPE_CONFIG,'enable_print_after_save'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");
$id_mst_printer_default = selectColumn(TBL_DOC_TYPE_CONFIG,'id_mst_printer'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$id_mst_doc_type_config."'  ");


 /*echo $sqlDIffPrint = "SELECT C.id_mst_attributes_printer AS id_printer FROM ".TBL_PURCH." A  LEFT JOIN ".TBL_PURCH_DETAILS." B ON A.id=B.id_pos_purch LEFT JOIN ".TBL_INV_ITEMS." C ON B.id_mst_items=C.id WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."'  GROUP BY id_printer ORDER BY id_printer";	*/
 
	 $sqlDIffPrint = "SELECT A.* FROM ".TBL_PURCH." A  WHERE A.id_shop='".$_SESSION['shop']."' AND A.id='".$pos_purch_id."'  "; 
$resDiffPrint = mysqli_query($connNew,$sqlDIffPrint);

$rowDiffPrint = mysqli_fetch_object($resDiffPrint);


	$sqlToPrint = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id_pos_purch = '".$pos_purch_id."' ";
	
	$resToPrint = mysqli_query($connNew,$sqlToPrint);
	$listPrintArray=array();
	$listprintHeaderArray=array();
	while($rowToPrint = mysqli_fetch_object($resToPrint)){
		
	$id_mst_attributes_printer = selectColumn(TBL_INV_ITEMS,'id_mst_attributes_printer'," WHERE `id_shop` = '".$_SESSION['shop']."' AND `id` = '".$rowToPrint->id_mst_items."'  ");


$printName	= selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer" AND  id="'.$id_mst_attributes_printer.'" and field_category=2');


	if($EnableSplitPrint=='0'){
		$id_mst_attributes_printer='1';
		}else{
		$orginalCopy_printer=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer"  and field_category=1');
	
	$orginalCopy_printer =$id_mst_printer_default;
	if($orginalCopy_printer=='0'){
		$orginalCopy_printer=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="printer"  and field_category=1');
	}
			//'orginalCopy00017812';
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$orginalCopy_printer][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	$printdivvalue[$orginalCopy_printer]='printTable'.$orginalCopy_printer;
			}
	$listPrintArray['printview'][$printName][$rowToPrint->id_mst_items]['item_description']=$rowToPrint->item_description;
	$listPrintArray['printview'][$printName][$rowToPrint->id_mst_items]['qty']=$rowToPrint->qty;	
	$printdivvalue[$printName]='printTable'.$printName;
	
	

	}
	
	
	
	$Table_no	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="table" AND id="'.$rowDiffPrint->id_attribute_table.'" ');
	$doc_date = $rowDiffPrint->doc_date;
	$timestamp = strtotime($doc_date);
	$doc_date = date('d-M-Y', $timestamp);
	$doc_time = date('h:i A', $timestamp);
	$steward	= selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="steward" AND  id="'.$rowDiffPrint->id_attribute_steward.'" ');
	$listprintHeaderArray['printHeaderview']['kot_no']=$rowDiffPrint->mdoc_no;	
	$listprintHeaderArray['printHeaderview']['doc_date']=$doc_date;	
	$listprintHeaderArray['printHeaderview']['doc_time']=$doc_time;
	$listprintHeaderArray['printHeaderview']['table_no']=$Table_no;
	$listprintHeaderArray['printHeaderview']['pax']=$rowDiffPrint->pax;
	$listprintHeaderArray['printHeaderview']['steward']=$steward;	
	
	 //$printdivvalue=implode(',',$printdivvalue);
	//debugData($listPrintArray);
	//print_r($printdivvalue);die;
 ?>  

<?php 
//die;
foreach($listPrintArray as $maintitle=>$GroupArray){  
		//debugData($GroupArray);
		foreach($GroupArray as $id_printer => $GroupNameArray){ 
			// $printer='';
    		// $printer='';
    
$printer_type=selectColumn(TBL_ATTRIBUTES,'field_category','WHERE table_name="printer"  and id="'.$id_printer.'"');
$printer_name=selectColumn(TBL_ATTRIBUTES,'field_value','WHERE table_name="printer"  and id="'.$id_printer.'"');
 $Printer_ip=selectColumn(TBL_ATTRIBUTES,'field_description','WHERE table_name="printer"  and id="'.$id_printer.'"');
$Printer_ip_port=selectColumn(TBL_ATTRIBUTES,'printer_port','WHERE table_name="printer"  and id="'.$id_printer.'"');

	if($Printer_ip!=''){
			echo  $ip_address = $Printer_ip.$printer_name;			
			/* Connecting To Printer **/
			
	}

		}
}



?>





