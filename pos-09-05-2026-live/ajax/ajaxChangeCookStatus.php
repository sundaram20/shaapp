			
<?php

include_once("../../config/auto_loader.php");

$select_qty = $_GET["qty"];
$pos_purch_details_id = $_GET["id"];
$status = $_GET["status"];
$old_qty = $_GET["old_qty"];


$select = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".$pos_purch_details_id."'  ";
	$selectt = mysqli_query($connNew, $select);	
		while($selecttt = mysqli_fetch_object($selectt)){
		   $qty = $selecttt -> qty;
		   $row_old_qty = $selecttt -> old_qty;
		   $check_orderis_ready = $selecttt -> check_orderis_ready;
		   $total =  $qty - $select_qty;
				$final =  number_format($total,2);
		}

if($status=='0'){ 
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `cook_status` = '0'	where  `id`='".$pos_purch_details_id."' ");
	//$updatePurch = "UPDATE `".TBL_PURCH_DETAILS."`  SET  `check_orderis_ready` = '0', qty='".$row_old_qty."', old_qty='".$select_qty."'	where  `id`='".$pos_purch_details_id."' ";
}else{
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `cook_status` = '1' where  `id`='".$pos_purch_details_id."' ");
	//$updatePurch = "UPDATE `".TBL_PURCH_DETAILS."`  SET  `check_orderis_ready` = '".$select_qty."', `qty` = '".$final."', old_qty='".$select_qty."' where  `id`='".$pos_purch_details_id."' ";
}
//echo "Reserved Successfully";


	        	