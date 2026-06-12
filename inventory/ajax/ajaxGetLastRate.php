<?php include_once("../../config/auto_loader.php");
$returnArr=array();
//if($_REQUEST['id_inv_po']!=''){
 $id_inv_items=	$_REQUEST['id_inv_items'];
	$sql5 = " SELECT max(id) as id FROM `".TBL_PRICE_MATRIX_DETAILS."` WHERE `id_inv_items`='".$id_inv_items."'  ";
				$db->query($sql5);
				$numRows2= $db->num_rows();
					if($numRows2 > 0)   {
							$row5 = $db->fetch_object();
		 $returnArr['rate']  =  selectColumn(TBL_PRICE_MATRIX_DETAILS,'rate'," WHERE  `id` = '".addslashes($row5->id)."'");
							}
		$returnArr['rowCount']=$_REQUEST['rowCount']=='undefined'?'':$_REQUEST['rowCount'];		 
		echo json_encode($returnArr);
?>


