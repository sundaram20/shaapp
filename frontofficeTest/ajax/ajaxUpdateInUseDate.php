<?php
include_once("../../config/auto_loader.php");
	include_once("../functions/function.php");
$post_tariff_date	=	date('Y-m-d',strtotime($_REQUEST['post_tariff_date']));
$id_post_tariff=$_REQUEST['id_post_tariff'];
$id_fo_bill	= $_REQUEST['id_fo_bill'];	
$shop=$_SESSION['shop'];
//print_r($_REQUEST);


echo postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew);
	
	?>