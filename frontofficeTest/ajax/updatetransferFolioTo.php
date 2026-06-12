<?php 

include_once("../../config/auto_loader.php");
include_once("../functions/function.php");
debugData($_REQUEST);//die;
$splitArray=explode(',',$_REQUEST['folio_split']);

if($_REQUEST['folio_split']!=''){
$id_mst_guest	= $_REQUEST['id_mst_guest'];
$id_resevation	= $_REQUEST['id_resevation'];

$TableArray=array();
$TableArrayID=array();
		foreach($splitArray as $Data ){
			$Data;
			
			
			$split =explode('-',$Data);
			$TableArray['Table'][$split[1]][$split[0]]['id']=$split[0];
			$TableArray['Table'][$split[1]][$split[0]]['tableName']=$split[1];
			$TableArrayID[$split[1]][]=$split[0];
			}
	//debugData($TableArrayID);
	//die;
		
	$resvId=$id_resevation;
	$id_mst_guest=$id_mst_guest;	
	
		
		 
		 
		 
		 
	foreach($TableArrayID as $tablename=>$splitid){
		
		 $PID	=	implode(',',$splitid);
		 
		 $transfer_folio_to = $_REQUEST['transfer_folio_to'];
		 $id_fo_bill = selectColumn('fo_folio','id_fo_bill','WHERE id = "'.$transfer_folio_to.'"');
		 
		 $insertFolioGrid =  "UPDATE `".$tablename."` SET 			 
				`id_fo_folio_to`='".addslashes($_REQUEST['transfer_folio_to'])."',
				`id_fo_bill`='".addslashes($id_fo_bill)."'	
						 
				  where `id` IN (".$PID.")   ";
		
		mysqli_query($connNew,$insertFolioGrid);
	}
	//die;
	echo " Folio Transfer Successfully ";
	die;
	}else{
		
		echo " Please Select ";
		die;
		
		}
?>