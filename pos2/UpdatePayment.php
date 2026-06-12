<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'pos_purch_pay','view');

include_once("include/function.php");



?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php");

 $GetKotdocSql = "SELECT * FROM `".TBL_PURCH."` WHERE pos_bill_type=2 and payment_amount_received=0 and doc_date <='2023-11-01' ";

	                  $KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
	                 while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
$purch_id =$ResultKotdocQuery->id;
  //Update Payment =====================================
						 
		$total_amount_recevied	=  selectColumn(TBL_PURCH_PAY,'sum(amount)'," WHERE id_purch='".$purch_id."'");
		if(	$total_amount_recevied>0){			 
		echo '<br>'.$UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET 
				
				 `payment_amount_received`='".$total_amount_recevied."'
				
				 
				  where`id` = '".$purch_id."'
				  ";   
				  mysqli_query($connNew,$UpdateTotalAmount);  
		}
		//Update Payment =====================================	
					 }
  ?>
<?php include_once("../includes/footer.php"); ?>
