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



if($_REQUEST['pos_purch_id']!='' ){
	$pos_purch_id=$_REQUEST['pos_purch_id'];
	
	
	$updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
								`cancel_remark` = '".$_REQUEST['remark']."',
								`cancel_date` = '".currenDateTime()."'
								,`cancelled` = '1'
								
								,`cancel_by` = '".$_SESSION['userId']."'
							
								where  `id`='".$pos_purch_id."' 
						  		"); 
								
								
			 $kot_doc_no= selectColumn(TBL_PURCH,'id_pos_details_split'," WHERE `id`='".$pos_purch_id."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ");
										
								
					/*$ResultKotdocQuerymdoc_no=   array();
					   $GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$kot_doc_no."') ";

	                  $KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
	                 while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
					 $ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
					 
					 
					 
					 
					 
					 }
					 */
					 
					// echo "UPDATE  `".TBL_PURCH_DETAILS."`  SET `adj_qty` = '0'	where  FIND_IN_SET(`id_pos_purch`,'".$kot_doc_no."') 
						  		//";			
	$updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`adj_qty` = '0'	
								where  FIND_IN_SET(`id`,'".$kot_doc_no."') 
						  		");
								
								
	
	}
		

$returnArray['purch_id']=$pos_purch_id;
$returnArray['msg']='Details Cancel successfully';
echo json_encode($returnArray);



/*** printing end ***/


