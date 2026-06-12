			
<?php

include_once("../../config/auto_loader.php");


$id_pos_purch_details = $_GET["id_pos_purch_details"];

$UpdateStatus= array();

$select = "SELECT * From `".TBL_PURCH_DETAILS."` WHERE id = '".$id_pos_purch_details."'  ";
	$selectt = mysqli_query($connNew, $select);	
		$selecttt = mysqli_fetch_object($selectt);
		 $verifiedstatus = $selecttt->verified;
if($verifiedstatus=='1'){ 
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `verified` = '0'	where  `id`='".$id_pos_purch_details."' ");
	$UpdateStatus['Status']='0';
	$UpdateStatus['Msg']='';
	
}else{
	$serve_status=selectColumn(TBL_PURCH_DETAILS,'serve_status'," WHERE id = '".$id_pos_purch_details."'"); 
	$cook_status=selectColumn(TBL_PURCH_DETAILS,'cook_status'," WHERE id = '".$id_pos_purch_details."'"); 
	$UpdateStatus['Status']='0';
	$UpdateStatus['Msg']='';						
	if($cook_status=='1'){
	$updatePurch = executeSql("UPDATE `".TBL_PURCH_DETAILS."`  SET  `verified` = '1' where  `id`='".$id_pos_purch_details."' ");//echo "UPDATE `".TBL_PURCH_DETAILS."`  SET  `verified` = '1' where  `id`='".$id_pos_purch_details."' "."2 Successfully";
	}else{
		$UpdateStatus['Status']='1';
		$UpdateStatus['Msg']='Please check Ready Status';
	}
	}
	
	
	
	
echo json_encode($UpdateStatus);


	        	