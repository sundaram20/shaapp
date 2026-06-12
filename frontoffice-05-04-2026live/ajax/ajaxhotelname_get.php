<?php
include_once("../../config/auto_loader.php");

	include_once("../functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	$data = addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix']);

	echo json_encode($data);
 
	

	
 ?>


