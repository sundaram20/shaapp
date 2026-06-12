<?php
include_once("../../config/auto_loader.php");


//$dataArr[]='';
$dataArr[]=selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE status='1' AND `id` = '".addslashes($_POST['id'])."'");

				//$dataArr[]='';
						
	echo json_encode($dataArr);
	
		

	
 ?>


