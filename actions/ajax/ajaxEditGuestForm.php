<?php 
include_once("../../config/auto_loader.php");

$image_path = $UPLOAD_FILES.'/guestImages/';

$image_display_path = $UPLOAD_FILES_PATH ."/guestImages/";

if($_POST['Save']){		

	$err = 0;

	/* bachend validation
		if(empty($_POST['address'])){
			$err++;
			$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
		}

	*/
		
	if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
	   	//echo "not found";
	}else{
		if($_FILES['image']['name'] !=''){
			if($_FILES['image']['size']>0 && $_FILES['image']['size']<1048576){
				if(($_FILES['image']['type'] == 'image/jpeg') || ($_FILES['image']['type'] == 'image/png') || ($_FILES['image']['type'] == 'image/bmp') || ($_FILES['image']['type'] == 'image/gif')){
				$unique = rand(00000,99999);
	        	$filename= basename($_FILES['image']['name']);
	        	$fname = getNameExt($filename);
	        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];			
					if(@move_uploaded_file($_FILES['image']['tmp_name'],$image_path.$insert_image)){	
						resize($insert_image,$image_path, $image_path, $width=350,$height=220,$thumb='medium-');
						resize($insert_image,$image_path, $image_path, $width=150,$height=100,$thumb='small-');	
						//////end resize////////
						if(@file_exists($image_path.$_POST['old_image']) && ($_POST['old_image'] != $_FILES['image']['name'])){
							@unlink($image_path.$_POST['old_image']);
							@unlink($image_path.'medium-'.$_POST['old_image']);
							@unlink($image_path.'small-'.$_POST['old_image']);
						}	
					}else{
						$err++;
						$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['image']['name'].'.</font>';
					}
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Invalid file type '.$_FILES['image']['type'].'. Please use only JEPG,GIF,PNG,BMP only</font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
			}
		}else{
			//$err++;
			//$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}

	if($err == 0){

		if(($_POST['Save'] == 'Add') && empty($_POST['gId']) || !isset($_POST['gId'])){
			
			checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'add');

			//Guest No Check Here
			 $doc_no = $_POST['doc_no'];

			 $sql5 = " SELECT * FROM `".TBL_GUEST."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_no`='".$doc_no."' and `doc_type` = '501' ";
				$db->query($sql5);
				$numRows= $db->num_rows();
				
				if($numRows > 0)   {
					while($row5 = $db->fetch_object()){ 
						$doc_no= $row5->doc_no; 
						$doc_no = $doc_no+1;
						
					} 
				}else{
					 $doc_no = $_POST['doc_no'];
				}

			 //getGuestRegistrationNo($_POST['doc_type'],$_POST['guest_resdate'],TBL_GUEST);

			if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
				$guest_reg_no = $_POST['prefix'].''.$doc_no.''.$_POST['suffix'];
			}else{
				$guest_reg_no = $_POST['guest_reg_no'];
			}


			$sql = "SELECT * FROM `".TBL_GUEST."` WHERE first_name = '".trim(addslashes($_POST['first_name']))."' AND last_name = '".trim(addslashes($_POST['last_name']))."' AND  email = '".trim(addslashes($_POST['email']))."' ";

			$db->query($sql);
			$numRows= $db->num_rows();
			
			if($numRows > 0){

				$message  = 'Duplicate entries not allowed. Please make corrections below.';

				echo $message ;

			}else{

				$addSql = "   	INSERT INTO `".TBL_GUEST."` SET 

							`id_shop_group` = '1',

							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`doc_type` = '".addslashes($_POST['doc_type'])."', 

							`guest_reg_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['guest_reg_date'])))."',

							`doc_no` = '".addslashes($doc_no)."', 

							`id_mst_doc_type_configuration` = '".addslashes($_POST['id_mst_doc_type_configuration'])."', 

							`guest_reg_no` = '".addslashes($guest_reg_no)."', 

							`id_mst_attributes_title`='".trim($_POST['id_mst_attributes_title'])."',

							`first_name` = '".trim(addslashes($_POST['first_name']))."',

							`last_name` = '".trim(addslashes($_POST['last_name']))."',

							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',

							`primary_mobile` = '".trim(addslashes($_POST['primary_mobile']))."',

							`primary_landline` = '".trim(addslashes($_POST['primary_landline']))."',

							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',

							`secondary_landline` = '".trim(addslashes($_POST['secondary_landline']))."',

							`secondary_mobile` = '".trim(addslashes($_POST['secondary_mobile']))."',

							`email` = '".trim(addslashes($_POST['email']))."',

							`address` = '".trim(addslashes($_POST['address']))."',

							`city` = '".trim(addslashes($_POST['city']))."',

							`postcode` = '".addslashes($_POST['postcode'])."',

							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',

							`other_country` = '".trim(addslashes($_POST['other_country']))."',

							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',

							`other_state` = '".trim(addslashes($_POST['other_state']))."',

							`id_mst_country_lang_nationality` = '".addslashes($_POST['id_mst_country_lang_nationality'])."',

							`other_nationality` = '".trim(addslashes($_POST['other_nationality']))."',

							`date_anniversary_month` = '".addslashes($_POST['date_anniversary_month'])."',

							`date_anniversary_day` = '".addslashes($_POST['date_anniversary_day'])."',

							`date_birth_month` = '".addslashes($_POST['date_birth_month'])."',

							`date_birth_day` = '".addslashes($_POST['date_birth_day'])."',

							`guest_vipstatus` = '".addslashes($_POST['guest_vipstatus'])."',

							`membership_status` = '".addslashes($_POST['membership_status'])."',

							`gender` = '".addslashes($_POST['gender'])."',

							`guest_note` = '".addslashes($_POST['guest_note'])."',

							`proof_type` = '".addslashes($_POST['proof_type'])."',

							`voter_no` = '".addslashes($_POST['voter_no'])."',

							`adhar_no` = '".addslashes($_POST['adhar_no'])."',

							`passport_no` = '".addslashes($_POST['passport_no'])."',

							`authority` = '".addslashes($_POST['authority'])."',

							`passport_expiry_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['passport_expiry_date'])))."'";

				if($_FILES['image']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

			

				$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'

							,`status` = '".addslashes($_POST['status'])."'";


							// $addSql or die();
							
$sql1 = executeSql("SELECT * FROM `".TBL_GUEST."` ORDER BY id DESC LIMIT 1");
	 while($row = $db->fetch_object2($sql1)){
	 $idd = $row -> id;
	 if($idd == '0'){
		 $last_id = '1';
	 }else{
		 $last_id =  $idd + 1;
	 }
 }

			$auditaddSql = "INSERT INTO audit_trail SET
							`voucher_id` = '".$last_id."',
							`tables_name` = 'mst_guest',
							`form_code` = 'mst_guest_manager_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				  executeSql($auditaddSql);					
							

				if(executeSql($addSql)){

					//unset($_POST);
					//$message['successCode'] = 200;
					$message  = 'New contact details has been added sucessfully.';
					echo $message ; 
					//echo json_encode($message);
					//header("location:editCompany.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);

					exit;

				}else{

					$err++;
					//$message['successCode'] = 301;
					//$message['errorMsg'] = 'New Contact details has not been saved. Please make corrections below.';
					//echo json_encode($message);

					$message  = 'New Contact details has not been saved. Please make corrections below.';

					echo $message ;

				}


			}

			
		}else if($_POST['Save'] == 'Edit' && !empty($_POST['gId']) && isset($_POST['gId'])){

		checkUserLevelPermission($_SESSION['userLevel'],TBL_GUEST,'update');

		if($_POST['prefix'] !='' OR $_POST['suffix'] !=''){
			$guest_reg_no = $_POST['prefix'].''.$_POST['doc_no'].''.$_POST['suffix'];
		}else{
			$guest_reg_no = $_POST['guest_reg_no'];
		}
		
		
		
		
	$auditquery = "SELECT * From `".TBL_GUEST."` WHERE id = '".addslashes(encryptor(decrypt,$_POST['gId']))."' ";

    $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){
	 
	  $c1  = $auditrow -> id_mst_attributes_title;
	  $c2  = $auditrow -> first_name;
	  $c3  = $auditrow -> last_name;
	  $c4  = $auditrow -> primary_mobile;
	  $c5  = $auditrow -> secondary_mobile;
	  $c6  = $auditrow -> email;
	  $c7  = $auditrow -> address;
	  $c8  = $auditrow -> city;
	  $c9  = $auditrow -> postcode;
	  $c10 = $auditrow -> id_mst_country_lang;
	  $c11 = $auditrow -> id_mst_state;
	  $c12 = $auditrow -> date_birth_day;
	  $c13 = $auditrow -> date_birth_month;
	  $c14 = $auditrow -> date_anniversary_day;
	  $c15 = $auditrow -> date_anniversary_month;
	  $c16 = $auditrow -> gender;
	  $c17 = $auditrow -> guest_vipstatus;
	  $c18 = $auditrow -> membership_status;
	  $c19 = $auditrow -> guest_note;
	  $c20 = $auditrow -> proof_type;
	  $c21 = $auditrow -> voter_no;
	  $c22  = $auditrow -> primary_landline;
	  $c23  = $auditrow -> secondary_landline;
	  $c24 = $auditrow -> id_mst_country_lang_nationality;
	  $c25 = $auditrow -> adhar_no;
	  $c26 = $auditrow -> passport_no;
	  $c27 = $auditrow -> authority;
	  $c28 = date('d-m-Y' , strtotime($auditrow -> passport_expiry_date));
	  $c29 = $auditrow -> status;
 
	if($c1 != $_REQUEST['id_mst_attributes_title']){
	    $old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c1."'");
		$new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_REQUEST['id_mst_attributes_title']."'  ");
		$ch1 ="Title Changed from " . $old_data ." - to - " . $new_data;
	}
	if($c2 != $_POST['first_name']){
		$ch2 ="Firstname Details Changed from " .   $c2 ." - to - " . $_POST['first_name'];
	} 
	if($c3 != $_POST['last_name']){
		$ch3 ="Firstname Details Changed from " .   $c3 ." - to - " . $_POST['last_name'];
	} 
	if($c4 != $_POST['primary_mobile']){
		$ch4 ="Primary Mobile Changed from " .   $c4 ." - to - " . $_POST['primary_mobile'];
	}
	if($c22 != $_POST['primary_landline']){
		$ch5 ="Primary Landline Changed from " .   $c22 ." - to - " . $_POST['primary_landline'];
	}
	if($c5 != $_POST['secondary_mobile']){
		$ch6 ="Secondary Mobile Changed from " .   $c5 ." - to - " . $_POST['secondary_mobile'];
	}
	if($c23 != $_POST['secondary_landline']){
		$ch7 ="Secondary Landline Changed from " .   $c23 ." - to - " . $_POST['secondary_landline'];
	}
	if($c6 != $_POST['email']){
		$ch8 ="Email Details Changed from " .   $c6 ." - to - " . $_POST['email'];
	}
	if($c7 != trim($_POST['address'])){
		$ch9 ="Address Details Details Changed from " .   $c7 ." - to - " . $_POST['address'];
	} 
	if($c8 != $_POST['city']){
		$ch10 ="City Details Changed from " .   $c8 ." - to - " . $_POST['city'];
	}
	if($c9 != $_POST['postcode']){
		$ch11 ="Pincode Details Changed from " .   $c9 ." - to - " . $_POST['postcode'];
	}
	if($c10 != $_POST['id_mst_country_lang']){
		$old_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$c10."'");
		$new_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$_POST['id_mst_country_lang']."'  ");
		$ch12 = "Country Details Changed from " .  $old_data ." - to - " . $new_data;
	}
	if($c11 != $_POST['id_mst_state']){
		$old_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$c11."'");
		$new_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$_POST['id_mst_state']."'  ");
		$ch13 ="State Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c24 != $_POST['id_mst_country_lang_nationality']){
		$old_data = selectColumn(TBL_COUNTRY_LANG,'nationality'," WHERE `id_country` = '".$c24."'");
		$new_data = selectColumn(TBL_COUNTRY_LANG,'nationality'," WHERE `id_country` = '".$_POST['id_mst_state']."'  ");
		$ch14 ="Nationality Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	

	if($c12 != $_POST['date_birth_day']){
		$ch15 ="Date of Birth Day Details Changed from " . $c12 ." - to - " . $_POST['date_birth_day'];
	}
	
$postmonth = $_POST['date_birth_month'];
	if($c13 != $postmonth){
		if($c13 == 1){$old='January';}else if($c13 == 2){$old='Febuary';}else if($c13 == 3){$old='March';}else if($c13 == 4){$old='April';}else if($c13 == 5){$old='May';}else if($c13 == 6){$old='June';}else if($c13 == 7){$old='July';}else if($c13 == 8){$old='Augest';}else if($c13 == 9){$old='September';}else if($c13 == 10){$old='October';}else if($c13 == 11){$old='November';}else if($c13 == 12){$old='December';}
		if($postmonth == 1){$new='January';}else if($postmonth == 2){$new='Febuary';}else if($postmonth == 3){$new='March';}else if($postmonth == 4){$new='April';}else if($postmonth == 5){$new='May';}else if($postmonth == 6){$new='June';}else if($postmonth == 7){$new='July';}else if($postmonth == 8){$new='Augest';}else if($postmonth == 9){$new='September';}else if($postmonth == 10){$new='October';}else if($postmonth == 11){$new='November';}else if($postmonth == 12){$new='December';}
		$ch16 ="Date of Birth Month Details Changed from " . $old ." - to - " . $new;
	}
	
$p= $_POST['date_anniversary_day'];
  if($p=='')
 {
	$aniday='0' ;
 }
 else
 {
	$aniday=$p ;
 }
 
 if($c14=='0')
 {
	$oldaniday='0' ;
 }
 else
 {
	$oldaniday=$c14 ;
 }
	
	if($oldaniday != $aniday){
		$ch17 ="Anniversary Day Details Changed from " . $c14 ." - to - " . $_POST['date_anniversary_day'];
	}
	
$p2= $_POST['date_anniversary_month'];
  if($p2=='')
 {
	$animonth='0' ;
 }
 else
 {
	$animonth=$p2 ;
 }
 
 if($c15=='0')
 {
	$oldanimonth='0' ;
 }
 else
 {
	$oldanimonth=$c15 ;
 }	
	
	if($oldanimonth != $animonth){
		if($c15 == 1){$old='January';}else if($c15 == 2){$old='Febuary';}else if($c15 == 3){$old='March';}else if($c15 == 4){$old='April';}else if($c15 == 5){$old='May';}else if($c15 == 6){$old='June';}else if($c15 == 7){$old='July';}else if($c15 == 8){$old='Augest';}else if($c15 == 9){$old='September';}else if($c15 == 10){$old='October';}else if($c15 == 11){$old='November';}else if($c15 == 12){$old='December';}
		if($p2 == 1){$new='January';}else if($p2 == 2){$new='Febuary';}else if($p2 == 3){$new='March';}else if($p2 == 4){$new='April';}else if($p2 == 5){$new='May';}else if($p2 == 6){$new='June';}else if($p2 == 7){$new='July';}else if($p2 == 8){$new='Augest';}else if($p2 == 9){$new='September';}else if($p2 == 10){$new='October';}else if($p2 == 11){$new='November';}else if($p2 == 12){$new='December';}
		$ch18 ="Anniversary Month Details Changed from " . $old ." - to - " . $new;
	}
	if($c16 != $_POST['gender']){
		if($c16 == 1){$old='Male';}else if($c16 == 2){$old='Female';}else if($c16 == 3){$old='Other';}
		if($_POST['gender'] == 1){$new='Male';}else if($_POST['gender'] == 2){$new='Female';}else if($_POST['gender'] == 3){$new='Other';}
		$ch19 ="Gender Details Changed from " . $old ." - to - " . $new;
	}
	if($c17 != $_POST['guest_vipstatus']){
		if($c17 == 1){$old='VIP';}else if($c17 == 2){$old='CIP';}
		if($_POST['guest_vipstatus'] == 1){$new='VIP';}else if($_POST['guest_vipstatus'] == 2){$new='CIP';}
		$ch20 ="Guest VIP Status Details Changed from " . $old ." - to - " . $new;
	}
	if($c18 != $_POST['membership_status']){
		if($c18 == 0){$old='Non Member';}else if($c18 == 1){$old='Member';}
		if($_POST['membership_status'] == 0){$new='Non Member';}else if($_POST['membership_status'] == 1){$new='Member';}
		$ch21 ="Membership Status Details Changed from " . $old ." - to - " . $new;
	}
	if($c19 != $_POST['guest_note']){
		$ch22 ="Guest Note Details Changed from " . $c19 ." - to - " . $_POST['guest_note'];
	} 
	if($c20 != $_POST['proof_type']){
		if($c20 == 1){$old='Voter Id';}else if($c20 == 2){$old='Adhar';}else if($c20 == 3){$old='Passport';}
		if($_POST['proof_type'] == 1){$new='Voter Id';}else if($_POST['proof_type'] == 2){$new='Adhar';}else if($_POST['proof_type'] == 3){$new='Passport';}
		$ch23 ="Id Proof Details Details Changed from " . $old ." - to - " . $new;
	} 
	if($c21 != $_POST['voter_no']){
		if($_POST['proof_type'] == 1){
		   $ch24 ="Voter Id Number Details Changed from " . $c21 ." - to - " . $_POST['voter_no'];
		}
	} 
	if($c25 != $_POST['adhar_no']){
		if($_POST['proof_type'] == 2){
		  $ch25 ="Aadhar Number  Number Details Changed from " . $c25 ." - to - " . $_POST['adhar_no'];
		}
	}
	
	
	if($c26 != $_POST['passport_no']){
		if($_POST['proof_type'] == 3){
		  $ch26 ="Passport Number Number Details Changed from " . $c26 ." - to - " . $_POST['passport_no'];
		}
	} 
	if($c27 != $_POST['authority']){
		if($_POST['proof_type'] == 3){
			$ch27 ="Authority Number Details Changed from " . $c27 ." - to - " . $_POST['authority'];
		}
	} 
	if($c28 != $_POST['passport_expiry_date']){
		if($_POST['proof_type'] == 3){
			$ch28 ="Expiry Date Number Details Changed from " . $c28 ." - to - " . $_POST['passport_expiry_date'];
		}
	}
	
	if($c29 != $_POST['status']){
		if($c29 == 1){$old='Active';}else{$old='Inactive';}
		if( $_POST['status'] == 1){$new='Active';}else{$new='Inactive';}
		$ch29 ="Status Details Changed from " .   $old ." - to - " . $new;
	}
 }			
		
		
		
		

		$editSql = "   	UPDATE `".TBL_GUEST."` SET 

							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`guest_reg_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['guest_reg_date'])))."',
							`doc_no` = '".addslashes($_POST['doc_no'])."', 
							`id_mst_doc_type_configuration` = '".addslashes($_POST['id_mst_doc_type_configuration'])."',
							`guest_reg_no` = '".addslashes($guest_reg_no)."', 
							`id_mst_attributes_title`='".trim($_REQUEST['id_mst_attributes_title'])."',
							`first_name` = '".trim(addslashes($_POST['first_name']))."',
							`last_name` = '".trim(addslashes($_POST['last_name']))."',
							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',
							`primary_mobile` = '".trim(addslashes($_POST['primary_mobile']))."',
							`primary_landline` = '".trim(addslashes($_POST['primary_landline']))."',
							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',
							`secondary_landline` = '".trim(addslashes($_POST['secondary_landline']))."',
							`secondary_mobile` = '".trim(addslashes($_POST['secondary_mobile']))."',
							`email` = '".trim(addslashes($_POST['email']))."',
							`address` = '".trim(addslashes($_POST['address']))."',
							`city` = '".trim(addslashes($_POST['city']))."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',
							`other_country` = '".trim(addslashes($_POST['other_country']))."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`other_state` = '".trim(addslashes($_POST['other_state']))."',
							`id_mst_country_lang_nationality` = '".addslashes($_POST['id_mst_country_lang_nationality'])."',
							`other_nationality` = '".trim(addslashes($_POST['other_nationality']))."',
							`date_anniversary_month` = '".addslashes($_POST['date_anniversary_month'])."',
							`date_anniversary_day` = '".addslashes($_POST['date_anniversary_day'])."',
							`date_birth_month` = '".addslashes($_POST['date_birth_month'])."',
							`date_birth_day` = '".addslashes($_POST['date_birth_day'])."',
							`guest_vipstatus` = '".addslashes($_POST['guest_vipstatus'])."',
							`membership_status` = '".addslashes($_POST['membership_status'])."',
							`gender` = '".addslashes($_POST['gender'])."',
							`guest_note` = '".addslashes($_POST['guest_note'])."',
							`proof_type` = '".addslashes($_POST['proof_type'])."',
							`voter_no` = '".addslashes($_POST['voter_no'])."',
							`adhar_no` = '".addslashes($_POST['adhar_no'])."',
							`passport_no` = '".addslashes($_POST['passport_no'])."',
							`authority` = '".addslashes($_POST['authority'])."',
							`passport_expiry_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['passport_expiry_date'])))."'";

			if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			} 

			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['gId']))."'";

			if(executeSql($editSql)){

                $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['gId']))."',
							`tables_name` = 'mst_guest',
							`form_code` = 'mst_guest_manager_form',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9).",".addslashes($ch10).",".addslashes($ch11).",".addslashes($ch12).",".addslashes($ch13).",".addslashes($ch14).",".addslashes($ch15).",".addslashes($ch16).",".addslashes($ch17).",".addslashes($ch18).",".addslashes($ch19).",".addslashes($ch20).",".addslashes($ch21).",".addslashes($ch22).",".addslashes($ch23).",".addslashes($ch24).",".addslashes($ch25).",".addslashes($ch26).",".addslashes($ch27).",".addslashes($ch28).",".addslashes($ch29)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";	
                        executeSql($auditeditSql);
							
							

				
				$message = selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".encryptor(decrypt,$_POST['gId'])."'").' details has been updated sucessfully.';

				echo $message ;

				/*header("location:editCompany.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']); */

				exit;

			}else{

				$err++;

				$message  = selectColumn(TBL_GUEST,'first_name'," WHERE `id_customer` = '".encryptor(decrypt,$_POST['id'])."'").' details has not been saved.Please make corrections below.';
				echo $message ;

			}
		} 
		
	}

}

?>