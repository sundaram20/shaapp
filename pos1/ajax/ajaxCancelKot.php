<?php include_once("../../config/auto_loader.php");


if(empty($_REQUEST['remark']) && $_REQUEST['remark']==""){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Table Group.</font>';
	echo json_encode($returnArray);
	exit;
}

if(empty($_REQUEST['pos_purch_id'])){
	$returnArray['msg']='<font style="color:red;font-weight:normal;" ><br>Please select Table.</font>';
	echo json_encode($returnArray);
	exit;
}
$pos_purch_id=$_REQUEST['pos_purch_id'];

/*debugData($_REQUEST);

 
exit;*/
checkKotIsBilledOrNot($_REQUEST['pos_purch_id']);
if($_REQUEST['pos_purch_id']!='' ){
	
	$updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
								`cancel_remark` = '".$_REQUEST['remark']."',								
								`cancel_date` = '".currenDateTime()."'
								,`cancelled` = '1'								
								,`cancel_by` = '".$_SESSION['userId']."'							
								where  `id`='".$pos_purch_id."' 
						  		"); 
			 $kot_doc_no= selectColumn(TBL_PURCH,'kot_doc_no'," WHERE `id`='".$pos_purch_id."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ");													
			$docnoArray=	explode(',',$kot_doc_no);
			
			if($_REQUEST['cancel2']=='both'){					
			
 //$kot_doc_no= selectColumn(TBL_PURCH,'kot_doc_no'," WHERE `id`='".$pos_purch_id."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ");
 
 			foreach($docnoArray as $docNoData){
	 
						 $updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
													`cancel_remark` = '".$_REQUEST['remark']."',								
													`cancel_date` = '".currenDateTime()."'
													,`cancelled` = '1'								
													,`cancel_by` = '".$_SESSION['userId']."'							
													where  `id`='".$docNoData."' 
													"); 
						   executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
													`adj_qty` = '0'	
													where  FIND_IN_SET(`id_pos_purch`,'".$docNoData."') 	");
													
													
													
					 $auditeditSql = " INSERT audit_trail SET 
			`voucher_id` = '".$docNoData."',
			`tables_name` = 'pos_purch , pos_purch_details',
			`form_code` = 'KOT',
			`changes` =  ',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,".addslashes($_REQUEST['remark'])."',
			`date_created` = '".currenDateTime()."',
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."',
			`id_mst_user_created_by` = '".$_SESSION['userId']."',
			`type` = 2 ";	


	executeSql($auditeditSql);								
				 }		
					
			
			}else{
			
			
			foreach($docnoArray as $docNoData){
			
			
			
	$updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`adj_qty` = '0'	
								where  FIND_IN_SET(`id_pos_purch`,'".$docNoData."') ");
			}
								
			}
		if($_REQUEST['cancel2']==''){
			$calcelType	=	'KOT';
		}else{
			$calcelType	=	'POS';
			}
		  $auditeditSql2 = " INSERT audit_trail SET 
			`voucher_id` = '".$pos_purch_id."',
			`tables_name` = 'pos_purch , pos_purch_details',
			`form_code` = '".$calcelType."',
			`changes` =  ',,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,".addslashes($_REQUEST['remark'])."',
			`date_created` = '".currenDateTime()."',
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."',
			`id_mst_user_created_by` = '".$_SESSION['userId']."',
			`type` = 2 ";	


	executeSql($auditeditSql2);

	  }

$returnArray['purch_id']=$pos_purch_id;
$returnArray['msg']='KOT Cancel successfully';
echo json_encode($returnArray);



/*** printing end ***/


