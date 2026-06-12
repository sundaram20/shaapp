<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if($_POST['multiple_first_name']!='' ){

//print_r($_POST);
if($_REQUEST['multiple_EditCustomerID']!=''){}else{
		
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
				`doc_type` = '".addslashes($doc_type)."', 
				`primary_contact_type` = '1',
				`guest_reg_date` = '".date('Y-m-d' , strtotime(addslashes($guest_reg_date)))."',

							`doc_no` = '".addslashes($doc_no)."', 

							`id_mst_doc_type_configuration` = '".addslashes($res['id_mst_doc_type_configuration'])."', 

				`guest_reg_no` = '".addslashes($guest_reg_no)."', 
				`id_mst_attributes_title` = '".addslashes($_POST['multiple_Nametitle'])."',
				`id_shop` = '".addslashes($_SESSION['shop'])."',
				`id_mst_country_lang` = '".addslashes($_POST['multiple_id_country'])."',
				`guest_vipstatus` = '".addslashes($_POST['multiple_user_type'])."',
				`first_name` = '".addslashes($_POST['multiple_first_name'])."',
				`last_name` = '".addslashes($_POST['multiple_last_name'])."',
				`email` = '".addslashes($_POST['multiple_email'])."',
				`city`= '".addslashes($_POST['multiple_city'])."',
				`primary_mobile` = '".addslashes($_POST['multiple_mobile'])."'
				";
		//if($_POST['proof_type']!=''){			
				 $addSql .= ",			`proof_type` = '".addslashes($_POST['multiple_proof_type'])."',
							`voter_no` = '".addslashes($_POST['multiple_voter_no'])."',
							`adhar_no` = '".addslashes($_POST['multiple_adhar_no'])."',
							`passport_no` = '".addslashes($_POST['multiple_passport_no'])."',
							`authority` = '".addslashes($_POST['multiple_authority'])."',
							`passport_expiry_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['multiple_passport_expiry_date'])))."',
							`visa_expiry_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['multiple_visa_expiry_date'])))."',
							`cform_expiry_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['multiple_cform_expiry_date'])))."'";
	//}		
				
				
				
				
$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
				,`status` = '1'";
		//echo $addSql;
	executeSql($addSql); 
		$lastInsertId= $db->insert_id();
		
	}?>
		
        
        <?php 
		
		
		
		if($_REQUEST['multiple_guest_id_edit_reservation']!='' &&   $_REQUEST['multiple_guest_id_mst_room_no_allocation']!='' &&  $_REQUEST['multiple_guest_type']=='2' && $_REQUEST['multiple_edit_order_by_room']!=''){}
		
		//=Folio Guest============================================================
		if($_REQUEST['multiple_guest_id_edit_reservation']!='' &&   $_REQUEST['multiple_guest_id_mst_room_no_allocation']!='' &&  $_REQUEST['multiple_guest_type']=='1' && $_REQUEST['multiple_edit_order_by_room']!='' ){}
		
		if($_REQUEST['multiple_id_folio']>0  && $_REQUEST['owner_guest']=='1'){}
		//=Folio Guest============================================================
		?>
        
       
		
	<?php 
				
} ?>

<?php 
//Sharer Guest===============
 if($id_multiple_guest!='' &&  $_REQUEST['multiple_guest_type']=='2'){} 
			 
			 
	//Main Guest=========================		 
	if($_REQUEST['multiple_guest_type']=='1'){
		
	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes($_REQUEST['multiple_guest_id_edit_reservation'])."' and `order_by_room` = '".addslashes($_REQUEST['multiple_edit_order_by_room'])."' ORDER BY id_mst_room_types,order_by_room, dated  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$rowOrderDetail->id_mst_room_types;
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){ 
				$Roomtype	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$room_no	=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				//$Guestname	=	selectColumn(TBL_GUEST,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
				$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and id='".$rowOrderDetail->id_mst_guest."'";
            
            $query=mysqli_query($connNew, $SQL);
            
            
            
              $resultCat=mysqli_fetch_assoc($query);
				  
				  $Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$resultCat['id_mst_attributes_title']."'"); 				
				  if($resultCat['proof_type']=='1'){ //Voter Id
					$ProofDetail='<br/><b>Voter Id : </b>'.$resultCat['voter_no'];
		  } 
		  elseif($resultCat['proof_type']=='2'){ //Adhar
			$ProofDetail='<br/><b>Adhar : </b>'.$resultCat['adhar_no'];
		  } 
		  elseif($resultCat['proof_type']=='3'){//Passport
			$ProofDetail='<br/><b>Passport  : </b> No : '.$resultCat['passport_no'].' <br/><b>Authority:</b> '.$resultCat['authority'] .'<br><b>Passport expiry date: </b>'.date('d-m-Y',strtotime($resultCat['passport_expiry_date']) ).' <br/><b>Visa Expiry Date: </b>'.date('d-m-Y',strtotime($resultCat['visa_expiry_date'])).'<br><b>C Form Expiry Date: </b>'.date('d-m-Y',strtotime($resultCat['cform_expiry_date']))   ;
		  } else{
			$ProofDetail='-';
		  }  
		  


            
      
        
       
        $mainGuest	=	$resultCat['guest_reg_no'] . ' -<b> Name : </b>'.ucfirst($Title).''.ucfirst($resultCat['first_name']).' '.ucfirst($resultCat['last_name']).'
		 <br/><b> Email :</b> '.$resultCat['email'].' <br/><b> Mobile : </b>'.$resultCat['primary_mobile'];
		
		//Id Proof Details
		$mainGuest= $mainGuest.$ProofDetail; 
		
		 } } 
		 
		 
		 
		 
		if($_REQUEST['multiple_id_folio']>0  && $_REQUEST['owner_guest']=='1'){}
		 
		 	
	}
			 
			 
			 
			 
		
		  
?> <select class="form-control select2" name="id_guest" id="id_guest" data-parsley-errors-container="#guestError" >
                      <option value="">Select Guest</option>
                      <?php 
									$resCat = "SELECT * FROM `".TBL_GUEST."` where status='1'  and id_shop='".addslashes($_SESSION['shop'])."' ";
												 $db->query($resCat);
	$numRows= $db->num_rows();

	if($numRows > 0){
		while($resultCat = $db->fetch_object()){
			
			
			
													
														if($lastInsertId == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$guestDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">Name : '.ucfirst($resultCat->title).''.ucfirst($resultCat->first_name).' '.ucfirst($resultCat->last_name).' | Email : '.$resultCat->email.' | Mobile : '.$resultCat->mobile.'</option>';
													}
												  }
												  echo $guestDropDown;
									
									 ?>
                    </select>
		 <div class="input-group-addon guest_open"> <i class="fa fa-plus"></i> </div>

