<?php
include_once("../config/auto_loader.php");

include_once("functions/function.php");

		 $TodaysDate	=	date('Y-m-d');
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $post_tariff_date = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated))); 

									
					$id_post_tariff='1';
					$id_fo_bill	= '';	
					$shop=$_SESSION['shop'];
					 $DateArray['status2']= $y= postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew);
					//deubugData($y);
					//print_r($DateArray);
					
 ?>


