<?php include_once("../../config/auto_loader.php");

	$mdoc_no	=	$_REQUEST['mdoc_no'];
	$id_mst_outlet	=	$_REQUEST['id_mst_outlet'];
	$id	=	$_REQUEST['id'];
	
	if($_REQUEST['id_serve_status']==0){
	//$serve_status = selectColumn(TBL_PURCH,'serve_status'," WHERE `mdoc_no`='".$mdoc_no."' and `id`= '".$id."'");
	 $serve_status = selectColumn(TBL_PURCH_DETAILS,'serve_status'," WHERE  `cook_status` = '0' and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'"); 
	//if($serve_status=='0'){
		$served='2';
		//}else{
		//	$served='0';
		//	}	
			//debugData($_REQUEST);
   $count=selectColumn(TBL_PURCH_DETAILS,'id'," WHERE `cook_status` = '0' and `id_pos_purch`='".$id."' and  `id_mst_outlet`='".$id_mst_outlet."' "); 
if($_REQUEST['ServerStatusWithoutCook']==1){
	$count='';
	}

	if($count==''){
		 
		 $updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`serve_status` = '".$served."'
								
								where  `cook_status` = '1'  and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'
						  		");
								
	 $arrayLi['status']=1;
	 }elseif($served==0){
		  $updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`serve_status` = '".$served."'
								
								where   `cook_status` = '1'  and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'
						  		");
		 $arrayLi['status']=2;
		$arrayLi['Msg']='One Or More items are still not Ready';
		
		 }else{
		$arrayLi['status']=0;
		$arrayLi['Msg']='One Or More items are still not Ready';
		
		 }
	}else{
		//$serve_status = selectColumn(TBL_PURCH,'serve_status'," WHERE `mdoc_no`='".$mdoc_no."' and `id`= '".$id."'");
	 $serve_status = selectColumn(TBL_PURCH_DETAILS,'serve_status'," WHERE  `cook_status` = '0' and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'"); 
	//if($serve_status=='0'){
		$served='0';
		//}else{
		//	$served='0';
		//	}	
			//debugData($_REQUEST);
   $count=selectColumn(TBL_PURCH_DETAILS,'id'," WHERE `cook_status` = '0' and `id_pos_purch`='".$id."' and  `id_mst_outlet`='".$id_mst_outlet."' "); 
if($_REQUEST['ServerStatusWithoutCook']==1){
	$count='';
	}

	if($count==''){
		 
		 $updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`serve_status` = '".$served."'
								
								where  `cook_status` = '1'  and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'
						  		");
								
	 $arrayLi['status']=1;
	 }elseif($served==0){
		  $updatePurch = executeSql("UPDATE  `".TBL_PURCH_DETAILS."`  SET 
								`serve_status` = '".$served."'
								
								where   `cook_status` = '1'  and `id_pos_purch`='".$id."'  and `id_mst_outlet`='".$id_mst_outlet."'
						  		");
		 $arrayLi['status']=2;
		$arrayLi['Msg']='One Or More items are still not Ready';
		
		 }else{
		$arrayLi['status']=0;
		$arrayLi['Msg']='One Or More items are still not Ready';
		
		 }
		}
	echo json_encode($arrayLi);
/*** printing end ***/


