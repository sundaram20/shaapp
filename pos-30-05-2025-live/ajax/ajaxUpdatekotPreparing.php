<?php include_once("../../config/auto_loader.php");

	$mdoc_no	=	$_REQUEST['mdoc_no'];
	$id_mst_outlet	=	$_REQUEST['id_mst_outlet'];
	$id_pos_purch	=	$_REQUEST['id_pos_purch'];
	
	//if($_REQUEST['id_serve_status']==0){
	//$serve_status = selectColumn(TBL_PURCH,'serve_status'," WHERE `mdoc_no`='".$mdoc_no."' and `id`= '".$id."'");
	 echo '===='.$kot_preparing = selectColumn(TBL_PURCH,'kot_preparing'," WHERE  `id`='".$id_pos_purch."'"); 
	 
	 
	if($kot_preparing=='0'){
		$UpdateValue	=	'1';
		
		}else{
			$UpdateValue	=	'0';
			}
		$updatePurch = executeSql("UPDATE  `".TBL_PURCH."`  SET 
								`kot_preparing` = '".$UpdateValue."'
								
								where  `id`='".$id_pos_purch."'  
						  		");
								
	
	//echo json_encode($arrayLi);
/*** printing end ***/


