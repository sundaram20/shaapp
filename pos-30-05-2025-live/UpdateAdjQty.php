<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'pos_purch_pay','view');
set_time_limit(800);
include_once("include/function.php");



?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php");

 $GetKotdocSql = "SELECT * FROM `".TBL_PURCH."` WHERE  id_shop='2' AND pos_bill_type= '1' AND doc_type!='24' and cancelled=0 AND DATE(date_created)<'2024-01-04' and total_adj_qty='0'";

	                  $KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
	                 while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
$purch_id =$ResultKotdocQuery->id;
  //Update Payment =====================================
						 
		$qty	=  selectColumn('pos_purch_details','sum(qty)'," WHERE id_pos_purch='".$purch_id."'");

$adj_qty	=  selectColumn('pos_purch_details','sum(adj_qty)'," WHERE id_pos_purch='".$purch_id."'");
		if(	$qty>0){			 
		echo '<br>'.$UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET 
				
				 `total_qty`='".$qty."',
				 `total_adj_qty`='".$adj_qty."'
				
				 
				  where`id` = '".$purch_id."'
				  ";   
				  mysqli_query($connNew,$UpdateTotalAmount);  
		}
		//Update Payment =====================================	
					 }
  ?>
<?php include_once("../includes/footer.php"); ?>
