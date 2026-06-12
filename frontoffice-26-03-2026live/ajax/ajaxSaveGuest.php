<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['first_name']!='' ){


if($_REQUEST['EditCustomerID']!=''){
	
	
	$addSql = "   	UPDATE `".TBL_GUEST."` SET 
				`id_shop_group` = '1',
				`id_mst_attributes_title` = '".addslashes($_POST['Nametitle'])."',
				`id_shop` = '".addslashes($_SESSION['shop'])."',
				`id_country` = '".addslashes($_POST['id_country'])."',
				`guest_vipstatus` = '".addslashes($_POST['user_type'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`city`= '".addslashes($_POST['city'])."',
				`primary_mobile` = '".addslashes($_POST['mobile'])."',
				`primary_contact_type` = '1',
				";
 $addSql .= "	
				,`last_modified_by` = '".$_SESSION['userId']."'
				
				
				WHERE `id_customer` = '".addslashes($_REQUEST['EditCustomerID'])."'"
				;
				
	
	
	
	executeSql($addSql);	
	$lastInsertId=	addslashes($_REQUEST['EditCustomerID']);	
	
	}else{
		
		 $doc_type = '501';
 $guest_reg_date = date('Y-m-d');
 $date = date('Y-m-d');
 $status  =1;
 $idss = 0;

$sql4 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."'  "; 
	$db->query($sql4);  
	$numRows= $db->num_rows(); 
	while($row4 = $db->fetch_object()){	  
		if(($row4->effective_date <= $date  || $row4->effective_date >= $date) && $row4->effective_date <= $guest_reg_date){
			$idss = $row4->id;
		 } 
	}

$sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ";

//echo $sql or  exit();

//print_r($sql);

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$id= $row->id; 
	    	$method= $row->method; 
	    	$start_no= $row->start_no;
	    	$prefix= $row->prefix; 
		    $suffix= $row->suffix;  
	    }

	if($numRows == 0){

		$sqls = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";
		    $db->query($sqls);  
		    while($rows = $db->fetch_object()){  
		    	$id= $rows->id; 
		    	$method= $rows->method; 
		    	$start_no= $rows->start_no; 
		    	$prefix= $rows->prefix; 
		    	$suffix= $rows->suffix; 
		    }
	}
 
if($method == '1'){

	$sql2 = " SELECT * FROM `".TBL_GUEST."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_mst_doc_type_configuration` = '".$id."' ";
	$db->query($sql2);  
	$numRows= $db->num_rows();

		if($numRows != 0){
			while($row2 = $db->fetch_object()){ 
				$doc_no = $row2->doc_no;
				$doc_no  = $doc_no + 1;
				$res['doc_no'] = $doc_no;
				$res['id_mst_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['doc_no'] = $start_no;
			$res['id_mst_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}

	$sql3 = " SELECT * FROM `".TBL_GUEST."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_mst_doc_type_configuration` = '".$id."' ";
	$db->query($sql3);
	$numRows= $db->num_rows();

	if($numRows > 0){
		while($row3 = $db->fetch_object()){
			$doc_no = $row3->doc_no;
			$doc_no  = $doc_no + 1;
			$res['doc_no'] = $doc_no;
			$res['id_mst_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['doc_no'] = $start_no;
		$res['id_mst_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}
//print_r($res);
//die;
			$doc_no = $res['doc_no'];
			$guest_reg_no = $res['prefix'].''.$doc_no.''.$res['suffix'];
			
$addSql = "   	INSERT INTO `".TBL_GUEST."` SET
				`id_shop_group` = '1',
				`doc_type` = '".addslashes($_POST['doc_type'])."', 
`primary_contact_type` = '1',
							`guest_reg_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['guest_reg_date'])))."',

							`doc_no` = '".addslashes($doc_no)."', 

							`id_mst_doc_type_configuration` = '".addslashes($_POST['id_mst_doc_type_configuration'])."', 

							`guest_reg_no` = '".addslashes($guest_reg_no)."', 
				`id_mst_attributes_title` = '".addslashes($_POST['Nametitle'])."',
				`id_shop` = '".addslashes($_SESSION['shop'])."',
				`id_mst_country_lang` = '".addslashes($_POST['id_country'])."',
				`guest_vipstatus` = '".addslashes($_POST['user_type'])."',
				`first_name` = '".addslashes($_POST['first_name'])."',
				`last_name` = '".addslashes($_POST['last_name'])."',
				`email` = '".addslashes($_POST['email'])."',
				`city`= '".addslashes($_POST['city'])."',
				`primary_mobile` = '".addslashes($_POST['mobile'])."'
				";
$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
				,`status` = '1'";
		
	executeSql($addSql); 
		$lastInsertId= $db->insert_id();
		
	}?>
		
		 <select class="form-control select2" name="id_guest" id="id_guest" data-parsley-errors-container="#guestError" >
                      <option value="">Select Guest</option>
                      <?php 
									$resCat = selectSql(TBL_GUEST,"where status='1' and type='1' and id_shop='".addslashes($_SESSION['shop'])."' and  id_customer='".$lastInsertId."'",'');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($lastInsertId == $resultCat->id_customer){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id_customer.'">Name : '.ucfirst($resultCat->title).''.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
                    </select>
		 <div class="input-group-addon guest_open"> <i class="fa fa-plus"></i> </div>
		
	<?php 
				
}
?>