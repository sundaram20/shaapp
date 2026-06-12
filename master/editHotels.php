<?php 
	include_once("../config/auto_loader.php");

	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');

	$image_path = $UPLOAD_FILES.'/hotel_gallery/';

	$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

	//---------------------------------------------------------------------------------------------------------
	if($_POST['Save']){
		$err = 0;
		if(empty($_POST['name'])){
			$err++;
			$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter hotel name.</font>';
		}else if($db->num_rows2(selectSql(TBL_HOTELS,"WHERE `id` NOT IN('".addslashes(encryptor(decrypt,$_POST['eId']))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes(trim($_POST['name']))."'",'')) &&$_POST['Save'] == 'Add'){
			$err++;
			$err_name = '<font style="color:red;font-weight:normal;" ><br>Hotel name already exists in our database.</font>';
		}
		if(empty($_POST['hotel_code'])){
			$err++;
			$err_hotel_code = '<font style="color:red;font-weight:normal;" ><br>Please enter hotel code.</font>';
		}else if($db->num_rows2(selectSql(TBL_HOTELS,"WHERE `id` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `hotel_code` = '".addslashes(trim($_POST['hotel_code']))."'",'')) &&$_POST['Save'] == 'Add'){
			$err++;
			$err_hotel_code = '<font style="color:red;font-weight:normal;" ><br>Hotel code already exists in our database.</font>';
		}
		if(empty($_POST['id_mst_hotel_category'])){
			$err++;
			$err_id_mst_hotel_category = '<font style="color:red;font-weight:normal;" ><br>Please select hotel category.</font>';
		}
		if(empty($_POST['address'])){
			$err++;
			$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
		}
		
	
		if(empty($_POST['city'])){
			$err++;
			$err_city = '<font style="color:red;font-weight:normal;" ><br>Please enter city.</font>';
		}

		if(empty($_POST['id_mst_country_lang'])){
			$err++;
			$err_countryError = '<font style="color:red;font-weight:normal;" ><br>Please enter country.</font>';
		}
		if(empty($_POST['id_mst_state'])){
			$err++;
			$err_stateError = '<font style="color:red;font-weight:normal;" ><br>Please enter state.</font>';
		}
		if(empty($_POST['pincode'])){
			$err++;
			$err_pincode = '<font style="color:red;font-weight:normal;" ><br>Please enter pincode.</font>';
		}else if(!preg_match("/^[A-Za-z0-9\s\-]{3,12}$/", $_POST['pincode'])){
			$err++;
			$err_pincode = '<font style="color:#FF0000;"><br>Please enter valid pincode.</font>';
		}

		if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';
		}elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			$err++;
			$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';
		}else if($db->num_rows2(selectSql(TBL_HOTELS,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `email` = '".addslashes(trim($_POST['email']))."'",'')) &&$_POST['Save'] == 'Add'){
			$err++;
			$err_email = '<font style="color:red;font-weight:normal;" >Email already exists in our database.</font>';
		}	

		if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
	   //no error
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

		if($err == 0){//No error
			if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
				//echo "hello";
				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'add');
				$addSql = "INSERT INTO `".TBL_HOTELS."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_mst_hotel_category` = '".addslashes($_POST['id_mst_hotel_category'])."',

							`hotel_code` = '".addslashes($_POST['hotel_code'])."',

							`name` = '".addslashes(trim($_POST['name']))."',

							`address` = '".addslashes(trim($_POST['address']))."',

							`city` = '".addslashes($_POST['city'])."',
							`udyam` = '".addslashes($_POST['udyam'])."',
							`tin` = '".addslashes($_POST['tin'])."',
							`fssai` = '".addslashes($_POST['fssai'])."',

							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',
							`cancellation_policy` = '".addslashes($_POST['cancellation_policy'])."',
							`other_country` = '".addslashes($_POST['other_country'])."',

							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',

							`other_state` = '".addslashes($_POST['other_state'])."',

							`id_mst_zonal` = '".addslashes($_POST['id_mst_zonal'])."',

							`pincode` = '".addslashes($_POST['pincode'])."',

							`gstin` = '".addslashes($_POST['gstin'])."',

							`pan` = '".addslashes($_POST['pan'])."',
							`cin_no` = '".addslashes($_POST['cin_no'])."',
							`google_map_url` = '".addslashes($_POST['google_map_url'])."',

							`review_url` = '".addslashes($_POST['review_url'])."',

							`hotel_website_url` = '".addslashes($_POST['hotel_website_url'])."',

							`hotel_tagline` = '".addslashes($_POST['hotel_tagline'])."',

							`brief_description` = '".addslashes($_POST['brief_description'])."',

							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',

							`primary_mobile` = '".trim(addslashes($_POST['primary_mobile']))."',

							`primary_landline` = '".trim(addslashes($_POST['primary_landline']))."',

							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',

							`secondary_landline` = '".trim(addslashes($_POST['secondary_landline']))."',

							`secondary_mobile` = '".trim(addslashes($_POST['secondary_mobile']))."',

							`email` = '".addslashes($_POST['email'])."',
							
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',

							`general_manager` = '".addslashes($_POST['general_manager'])."',

							`general_manager_contact` = '".addslashes($_POST['general_manager_contact'])."',

							`general_manager_email` = '".addslashes($_POST['general_manager_email'])."',

							`bank_name` = '".addslashes($_POST['bank_name'])."',
							
							`bank_account_legal_name` = '".addslashes($_POST['bank_account_legal_name'])."',

							`bank_account_no` = '".addslashes($_POST['bank_account_no'])."',

							`bank_account_type` = '".addslashes($_POST['bank_account_type'])."',

							`bank_ifsc_code` = '".addslashes($_POST['bank_ifsc_code'])."',

							`bank_swift_code` = '".addslashes($_POST['bank_swift_code'])."',

							`bank_branch` = '".addslashes($_POST['bank_branch'])."',

							`ids_mst_hotel_general_services` = '".addslashes($_POST['ids_mst_hotel_general_services'])."',
							
							`ids_mst_hotel_kids_related_services` = '".addslashes($_POST['ids_mst_hotel_kids_related_services'])."',

							`ids_mst_hotel_outdoor_services` = '".addslashes($_POST['ids_mst_hotel_outdoor_services'])."',

							`ids_mst_hotel_conference_services` = '".addslashes($_POST['ids_mst_hotel_conference_services'])."',

							`ids_mst_hotel_dining_services` = '".addslashes($_POST['ids_mst_hotel_dining_services'])."',
							
							`display_order` = '".addslashes($_POST['display_order'])."'";
							
				if($_FILES['image']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

				if($_POST['status'] == "1"){

				$addSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";

				}else{
					$addSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
				$addSql .= " ,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
				
				
								
$sql1 = executeSql("SELECT * FROM `".TBL_HOTELS."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_hotels',
							`form_code` = 'manage hotels',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);
				
				
				
				
				if(executeSql($addSql)){
					//unset($_POST);
					$lastInsertId= $db->insert_id();
					$_SESSION['successMsg'] = 'New Hotel details has been added sucessfully.';
					header("location:editHotels.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Hotel details has not been saved. Please make corrections below.';
				}
			}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
				//echo print_r($_POST);exit;
				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'update');
				
				
					
  $auditquery = "SELECT * From `".TBL_HOTELS."` WHERE id = '".addslashes(encryptor(decrypt,$_POST['eId']))."' ";

  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $idd = $auditrow -> id;
	   $cname = $auditrow -> name;
	  $ccode = $auditrow -> hotel_code;
	  $ccategory = $auditrow -> id_mst_hotel_category;
	  $caddress = $auditrow -> address;
	  $ccountry = $auditrow -> id_mst_country_lang;
	  $cstate = $auditrow -> id_mst_state;
	  $ccity = $auditrow -> city;
	  $cpin = $auditrow -> pincode;
	  $czonal= $auditrow -> id_mst_zonal;
	  $cgstin= $auditrow -> gstin;
	  $cpan= $auditrow -> pan;
	  $cmap= $auditrow -> google_map_url;
	  $crurl= $auditrow -> review_url;
	  $churl= $auditrow -> hotel_website_url;
	  $ctag= $auditrow -> hotel_tagline;
	  $cctype= $auditrow -> primary_contact_type;
	  $cmob= $auditrow -> primary_mobile;
	  $clan= $auditrow -> primary_landline;
	  $csec= $auditrow -> secondary_contact_type;
	  $csecl= $auditrow -> secondary_landline;
	  $csecm= $auditrow -> secondary_mobile;
	  $cmail= $auditrow -> email;
	  $csecmail= $auditrow -> secondary_email;
	  $cman= $auditrow -> general_manager;
	  $cmcon= $auditrow -> general_manager_contact;
	  $cmanem= $auditrow -> general_manager_email;
	  $cbank= $auditrow -> bank_name;
	  $cbnam= $auditrow -> bank_account_legal_name;
	  $cacc= $auditrow -> bank_account_no;
	  $cactype= $auditrow -> bank_account_type;
	  $cifsc= $auditrow -> bank_ifsc_code;
	  $cswift= $auditrow -> bank_swift_code;
	  $cbran= $auditrow -> bank_branch;
	  $cdis= $auditrow -> display_order;
	  $cstat= $auditrow -> status;
	  
 
	  
    if($cname != $_POST['name']){
	 $ch1 = "Hotel Name Changed from ".  $cname ." - to - " . $_POST['name'];
	}
	if($ccode != $_POST['hotel_code']){
		 $ch2 ="Hotel Code Changed from " .  $ccode." - to - ".$_POST['hotel_code'];
	}
	if($ccategory != $_POST['id_mst_hotel_category']){
		$old_data = selectColumn(TBL_HOTEL_CATEGORY,'name'," WHERE `id` = '".$ccategory."'");
		 $new_data = selectColumn(TBL_HOTEL_CATEGORY,'name'," WHERE `id` = '".$_POST['id_mst_hotel_category']."'  ");
		$ch3 ="Hotel Category Changed from " .   $old_data ." - to - " . $new_data;
	} 
	if($caddress != $_POST['address']){
		$ch4 ="Address Details Details Changed from " .   $caddress ." - to - " . $_POST['address'];
	} 
	if($ccountry != $_POST['id_mst_country_lang']){
		$old_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$ccountry."'");
		 $new_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$_POST['id_mst_country_lang']."'  ");
		 if((!empty($ccountry)) && (!empty($_POST['id_mst_country_lang']))){
			$ch5 = "Country Details Changed from " .  $old_data ." - to - " . $new_data;
		 }
	}
	
	if($cstate==''){
		$cstate1 = '0';
	}else{
		$cstate1 = $cstate;
	}
	
	if($cstate1 != $_POST['id_mst_state']){
		$old_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$cstate1."'");
		 $new_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$_POST['id_mst_state']."'  ");
		 
		if((!empty($cstate1)) && (!empty($_POST['id_mst_state']))){ 
		$ch6 ="State Details Changed from " .   $old_data ." - to - " . $new_data;
		}
		
	}
	if($ccity != $_POST['city']){
		$ch7 ="City Details Changed from " .   $ccity ." - to - " . $_POST['city'];
	}
	if($cpin != $_POST['pincode']){
		$ch8 ="Pincode Details Changed from " .   $cpin ." - to - " . $_POST['pincode'];
	}
	if($czonal != $_POST['id_mst_zonal']){
		
		$old_data = selectColumn(TBL_ZONAL,'name'," WHERE `id` = '".$czonal."'");
		$new_data = selectColumn(TBL_ZONAL,'name'," WHERE `id` = '".$_POST['id_mst_zonal']."'  ");
		 
		if((!empty($czonal)) && (!empty($_POST['id_mst_zonal']))){
		$ch9 ="Zonal Details Changed from " .   $old_data ." - to - " . $new_data;
		}
		
	}
	if($cgstin != $_POST['gstin']){
		if((!empty($cgstin)) && (!empty($_POST['gstin']))){
		$ch34 ="GSTIN Details Changed from " .   $cgstin ." - to - " . $_POST['gstin'];
		}
	}
	if($cpan != $_POST['pan']){
		if((!empty($cpan)) && (!empty($_POST['pan']))){
		$ch10 ="PAN Details Changed from " .   $cpan ." - to - " . $_POST['pan'];
		}
	}
	if($cmap != $_POST['google_map_url']){
		$ch11 ="Google Map Url Details Changed from " .   $cmap ." - to - " . $_POST['google_map_url'];
	}
	if($crurl != $_POST['review_url']){
		$ch12 ="Review Url Details Changed from " .   $crurl ." - to - " . $_POST['review_url'];
	}
	if($churl != $_POST['hotel_website_url']){
		$ch13 ="Website Url Details Changed from " .   $churl ." - to - " . $_POST['hotel_website_url'];
	}
	if($ctag != $_POST['hotel_tagline']){
		$ch14 ="Hotel Tagline Details Changed from " .   $ctag ." - to - " . $_POST['hotel_tagline'];
	}
	if($cctype != $_POST['primary_contact_type']){
		//$ch15 ="Primary Contact Details Changed from " .   $cctype ." - to - " . $_POST['primary_contact_type'];
	}
	if($cmob != $_POST['primary_mobile']){
		$ch16 ="Primary Mobile Changed from " .   $cmob ." - to - " . $_POST['primary_mobile'];
	}
	if($clan != $_POST['primary_landline']){
		$ch17 ="Primary Landline Changed from " .   $clan ." - to - " . $_POST['primary_landline'];
	}
	if($csec != $_POST['secondary_contact_type']){
		//$ch18 ="Secondary Contact Details Changed from " .   $csec ." - to - " . $_POST['secondary_contact_type'];
	}
	if($csecl != $_POST['secondary_landline']){
		$ch19 ="Secondary Landline Details Changed from " .   $csecl ." - to - " . $_POST['secondary_landline'];
	}
	if($csecm != $_POST['secondary_mobile']){
		$ch20 ="Secondary Mobile Details Changed from " .   $csecm ." - to - " . $_POST['secondary_mobile'];
	}
	if($cmail != $_POST['email']){ 
		$ch21 ="Email Details Changed from " .   $cmail ." - to - " . $_POST['email'];
	}
	if($cman != $_POST['general_manager']){
		$ch22 ="General Manager Details Changed from " .   $cman ." - to - " . $_POST['general_manager'];
	}
	if($cmcon != $_POST['general_manager_contact']){
		 if((!empty($cmcon)) && (!empty($_POST['general_manager_contact']))){
		$ch23  ="General Manager Contact Details Changed from " .   $cmcon ." - to - " . $_POST['general_manager_contact'];
		 }
	}
	if($cmanem != $_POST['general_manager_email']){
		 if((!empty($cmanem)) && (!empty($_POST['general_manager_email']))){
		$ch24 ="General Manager Email Details Changed from " .   $cmanem ." - to - " . $_POST['general_manager_email'];
		 }
	}
	if($cbank != $_POST['bank_name']){
		$ch25 ="Bank Name Details Changed from " .   $cbank ." - to - " . $_POST['bank_name'];
	}
	if($cbnam != $_POST['bank_account_legal_name']){
		 if((!empty($cbnam)) && (!empty($_POST['bank_account_legal_name']))){
		$ch26 ="Account Name Details Changed from " .   $cbnam ." - to - " . $_POST['bank_account_legal_name'];
		 }
	}
	if($cacc != $_POST['bank_account_no']){
		 if((!empty($cacc)) && (!empty($_POST['bank_account_no']))){
		$ch27 ="Account No Details Changed from " .   $cacc ." - to - " . $_POST['bank_account_no'];
		 }
	}
	if($cactype != $_POST['bank_account_type']){
		 if((!empty($cactype)) && (!empty($_POST['bank_account_type']))){
		$ch28 ="Account Type Details Changed from " .   $cactype ." - to - " . $_POST['bank_account_type'];
		 }
	}
	if($cifsc != $_POST['bank_ifsc_code']){
		
		if((!empty($cifsc)) && (!empty($_POST['bank_ifsc_code']))){
		$ch29 ="IFSC Details Changed from " .   $cifsc ." - to - " . $_POST['bank_ifsc_code'];
		}
	}
	if($cswift != $_POST['bank_swift_code']){
		$ch30 ="SWIFT Details Changed from " .   $cswift ." - to - " . $_POST['bank_swift_code'];
	}
	if($cbran != $_POST['bank_branch']){
		$ch31 ="Bank Branch Details Changed from " .   $cbran ." - to - " . $_POST['bank_branch'];
	}
	if($cdis != $_POST['display_order']){
		$ch32 ="Display Order Details Changed from " .   $cdis ." - to - " . $_POST['display_order'];
	}
	if($cstat != $_POST['status']){
		if($cstat == 1){$cstat='Active';}else{$cstat='Inactive';}
		if( $_POST['status'] == 1){$old_data='Active';}else{$old_data='Inactive';}
		$ch33 ="Status Details Changed from " .   $cstat ." - to - " . $old_data;
	}
		if($csecmail != $_POST['secondary_email']){ 
		$ch35 ="Email Details Changed from " .   $csecmail ." - to - " . $_POST['secondary_email'];
	}
	
	
 }		
		
				
			 	$editSql = "   	UPDATE `".TBL_HOTELS."` SET 

							`id_shop_group` = '1',

							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_mst_hotel_category` = '".addslashes($_POST['id_mst_hotel_category'])."',

							`hotel_code` = '".addslashes($_POST['hotel_code'])."',

							`name` = '".addslashes(trim($_POST['name']))."',

							`address` = '".addslashes(trim($_POST['address']))."',

							`city` = '".addslashes($_POST['city'])."',
							`udyam` = '".addslashes($_POST['udyam'])."',
							`tin` = '".addslashes($_POST['tin'])."',
							`fssai` = '".addslashes($_POST['fssai'])."',
							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',

							`other_country` = '".addslashes($_POST['other_country'])."',

							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',

							`other_state` = '".addslashes($_POST['other_state'])."',
							`cancellation_policy` = '".addslashes($_POST['cancellation_policy'])."',
							`id_mst_zonal` = '".addslashes($_POST['id_mst_zonal'])."',

							`pincode` = '".addslashes($_POST['pincode'])."',

							`gstin` = '".addslashes($_POST['gstin'])."',

							`pan` = '".addslashes($_POST['pan'])."',
							`cin_no` = '".addslashes($_POST['cin_no'])."',
							`google_map_url` = '".addslashes($_POST['google_map_url'])."',

							`review_url` = '".addslashes($_POST['review_url'])."',

							`hotel_website_url` = '".addslashes($_POST['hotel_website_url'])."',

							`hotel_tagline` = '".addslashes($_POST['hotel_tagline'])."',

							`brief_description` = '".addslashes($_POST['brief_description'])."',

							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',

							`primary_mobile` = '".trim(addslashes($_POST['primary_mobile']))."',

							`primary_landline` = '".trim(addslashes($_POST['primary_landline']))."',

							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',

							`secondary_landline` = '".trim(addslashes($_POST['secondary_landline']))."',

							`secondary_mobile` = '".trim(addslashes($_POST['secondary_mobile']))."',

							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',

							`general_manager` = '".addslashes($_POST['general_manager'])."',

							`general_manager_contact` = '".addslashes($_POST['general_manager_contact'])."',

							`general_manager_email` = '".addslashes($_POST['general_manager_email'])."',

							`bank_name` = '".addslashes($_POST['bank_name'])."',
							
							`bank_account_legal_name` = '".addslashes($_POST['bank_account_legal_name'])."',

							`bank_account_no` = '".addslashes($_POST['bank_account_no'])."',

							`bank_account_type` = '".addslashes($_POST['bank_account_type'])."',

							`bank_ifsc_code` = '".addslashes($_POST['bank_ifsc_code'])."',

							`bank_swift_code` = '".addslashes($_POST['bank_swift_code'])."',

							`bank_branch` = '".addslashes($_POST['bank_branch'])."',

							`ids_mst_hotel_general_services` = '".addslashes($_POST['ids_mst_hotel_general_services'])."',
							
							`ids_mst_hotel_kids_related_services` = '".addslashes($_POST['ids_mst_hotel_kids_related_services'])."',

							`ids_mst_hotel_outdoor_services` = '".addslashes($_POST['ids_mst_hotel_outdoor_services'])."',

							`ids_mst_hotel_conference_services` = '".addslashes($_POST['ids_mst_hotel_conference_services'])."',

							`ids_mst_hotel_dining_services` = '".addslashes($_POST['ids_mst_hotel_dining_services'])."',
							
							`display_order` = '".addslashes($_POST['display_order'])."'";
						
				if($_FILES['image']['name'] != ''){				
					$editSql .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

				if($_POST['status'] == "1"){

				$editSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
				}else{
					$editSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
		
				$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'";
				//echo '-----w'.$editSql;exit;				
				
				 $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."',
							`tables_name` = 'mst_hotels',
							`form_code` = 'manage hotels',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9).",".addslashes($ch10).",".addslashes($ch11).",".addslashes($ch12).",".addslashes($ch13).",".addslashes($ch14).",".addslashes($ch15).",".addslashes($ch16).",".addslashes($ch17).",".addslashes($ch18).",".addslashes($ch19).",".addslashes($ch20).",".addslashes($ch21).",".addslashes($ch22).",".addslashes($ch23).",".addslashes($ch24).",".addslashes($ch25).",".addslashes($ch26).",".addslashes($ch27).",".addslashes($ch28).",".addslashes($ch29).",".addslashes($ch30).",".addslashes($ch31).",".addslashes($ch32).",".addslashes($ch33).",".addslashes($ch34).",".addslashes($ch35)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
if($ch1=='' && $ch2=='' && $ch3=='' && $ch4=='' && $ch5=='' && $ch6=='' && $ch7=='' && $ch8=='' && $ch9=='' && $ch10=='' && $ch11=='' && $ch12=='' && $ch13=='' && $ch14=='' && $ch15=='' && $ch16=='' && $ch17=='' && $ch18=='' && $ch19=='' && $ch20=='' && $ch21=='' && $ch22=='' && $ch23=='' && $ch24=='' && $ch25=='' && $ch26=='' && $ch27=='' && $ch28=='' && $ch29=='' && $ch30=='' && $ch31=='' && $ch32=='' && $ch33=='' && $ch34=='' && $ch35=='' ){
	
}else{							
	executeSql($auditeditSql);
}
				
				
				
				//echo '-----www'.$editSql;exit;
				if(executeSql($editSql)){
					$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
					header("location:editHotels.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
				}
			}
		}else{//Error
			$err++;
			$_SESSION['errorMsg'] = 'Hotel details has not been saved. Please make corrections.';
		}
	}

	// ----------cate---------
	if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
		$sql = "  SELECT * FROM `".TBL_HOTELS."`
									WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
		 $db->query($sql);
		
		if($db->num_rows() > 0){
			$row = $db->fetch_object();
			
		}						
	}
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<div class="content-wrapper">


	
<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->
	<!-- Content Header (Page header) -->
	
	<section class="content-header">

   <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
	
 <!--   <section class="content-header">
      <h1>
        <span style="color: #f25e74;"> Hotel Manager </span>
        <small>Manage Hotels</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Hotels</li>
      </ol>
    </section> -->

    <!-- Main content -->
    <section class="content">
    	<div class="row">
    		<div class="col-md-12">
    			<div class="nav-tabs-custom">
    				<ul class="nav nav-tabs">
					   <li class="active" ><a href="#tab_1" data-toggle="tab">Hotel</a></li>  
					   <li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Types</a></li>   
		              <li><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
		              <li><a href="manageHotelVideoGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Video Gallery</a></li>
					  
					 <input type='button' value='Back' class="btn btn-success" onclick='location.replace("manageHotels.php");' style="float:right;margin:10px" />
					  
		              
		            </ul>
		            <div class="box-header with-border">
		              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add Hotel ':'Edit Hotel : '?>  <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
		            </div>
		            <!-- /.box-header -->
					<!-- form start -->  			        
			 		<form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
			 			<input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
			 			<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" id="id_mst_hotels" name="id_mst_hotels" />
			 			<div class="form-group has-error" align="center">
							<?php if($_SESSION['errorMsg']){?>
							 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
							<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
						 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
							<?php unset($_SESSION['successMsg']);}?>
					 	</div>
					 	<div class="box-body" style="padding-top:0px">
					 		<div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">Hotels General Details</h5>
				                </div> 
				                <hr>
				            </div>
					 		<div class="row">
					 			<div class="form-group col-md-4 col-sm-4">
					 				<label for="name">Hotel Name<font color="#FF0000">*</font></label>
					 				<input type="text" class="form-control" placeholder="Enter Hotel Name" id="name" name="name" value="<?php if($_POST['name']) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
									<span><?php echo $err_name;?></span>
					 			</div>
					 			<div class="form-group col-md-4 col-sm-4">
					 				<label for="hotel_code">Hotel Code<font color="#FF0000">*</font></label>
                  					<input type="text" class="form-control" placeholder="Enter Hotel Code" id="hotel_code" name="hotel_code" value="<?php if($_POST['hotel_code']) echo $_POST['hotel_code'];else echo stripslashes($row->hotel_code);?>"  data-parsley-required>
									<span><?php echo $err_hotel_code;?></span>
					 			</div>
					 			<div class="form-group col-md-4 col-sm-4">
					 				<label for="id_mst_hotel_category">Hotel Category <font color="#FF0000">*</font></label>
					 				<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_hotel_category" data-parsley-required data-parsley-errors-container="#hotel_categoryError" style="width:100%">
										<option value="">Select Hotel Category</option>';
										$resCat = selectSql(TBL_HOTEL_CATEGORY,"where id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
										if($db->num_rows2($resCat)){
										  	while($resultCat = $db->fetch_object2($resCat)){
												if($_REQUEST['id_mst_hotel_category'] == $resultCat->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_hotel_category == $resultCat->id){
													$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
											}
										}
										echo $categoryDropDown .= '</select>';
									?>
									<span id="hotel_categoryError"><?php echo $err_id_mst_hotel_category;?></span>
					 			</div>
					 		</div>
					 		<div class="row">
					 			<div class="form-group col-md-4 col-sm-4">
				                  <label for="address">Address<font color="#FF0000">*</font></label>
								   <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" data-parsley-required><?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?></textarea>
									<?php echo $err_address;?>
				                </div>	
				                
								
					 			
					 			<div class="form-group col-md-4 col-sm-4">
					 				<label for="id_mst_country_lang">Country<font color="#FF0000">*</font></label>
					 				<select class="form-control select2" name="id_mst_country_lang" id="id_mst_country_lang" style="width:100%" data-parsley-errors-container="#countryError" data-parsley-required >
					 					<option value="">Select Country</option>
					 					<?php 
                                			$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

                                			if($db->num_rows2($resCat)){

                                  				while($resultCat = $db->fetch_object2($resCat)){  
                                    				if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){

                                      					$selected = 'selected="selected"';

                                   					}elseif($row->id_mst_country_lang == $resultCat->id_country){

                                      					$selected = 'selected="selected"';

                                    				}else{

                                      					$selected = '';

                                    				}

                                    				$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                                  				}
                                			}
                                 			echo $countryDropDown;
                              			?>
                              			<?php if($row->id_mst_country_lang == 10000){?>
                            				<option value="10000" selected="selected">Other</option>
                             			<?php } else{ ?>
                                			<option value="10000">Other</option>
                             			<?php } ?>
					 				</select>
					 				<span id="countryError"><?php echo $err_countryError;?></span>
					 			</div>
					 			<div class="form-group col-md-4 col-sm-4">
					 				 <label for="id_mst_state">State<font color="#FF0000">*</font></label>
					 				<select class="form-control select2"  name="id_mst_state" id="id_mst_state"  style="width:100%" data-parsley-errors-container="#stateError" data-parsley-required>
					 					<?php  if(!empty($row->id_mst_state) && $row->id_mst_state != 10000){
                                   		$resCat = selectSql(TBL_STATE," where id_mst_country_lang='".$row->id_mst_country_lang."' ",' ORDER BY `name` ');
                                  		if($db->num_rows2($resCat)){
                                    		while($resultCat = $db->fetch_object2($resCat)){  
                                        		if($row->id_mst_state == $resultCat->id_state){

                                          			$selected = 'selected="selected"';

                                        		}else{

                                          			$selected = '';

                                        		}

                                        		$stateDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
                                    		}
                                 		}
                                   		echo $stateDropDown;
                                   		echo '<option value="10000">Other</option>';
                                 		}else if($row->id_mst_state == 10000){?>
                                			<option value="10000" selected="selected">Other</option>
                               			<?php } else{ ?>
                                  			<option value="" selected="selected">Select Please</option>
                                  			<option value="10000">Other</option>
                                		<?php } ?>
                              		</select>
                              		<span id="stateError"><?php echo $err_stateError;?></span>
					 			</div>
					 		</div>
					 		<div class="row">
					 			<div class="form-group col-md-4 col-sm-4"></div>
				 				<div id="otherCountryDiv" class="form-group col-sm-4">
			                      <?php if($row->id_mst_country_lang == 10000){ ?>
			                        <label for="other_country">Other Country<font color="#FF0000">*</font></label>
			                        <input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST['other_country']) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required />
			                        <span id="other_countryError"></span>
			                      <?php } ?>
                				</div>
                				<div id="otherStateDiv" class="form-group col-sm-4">
			                      <?php if($row->id_mst_state == 10000){ ?>
			                        <label for="other_state">Other State<font color="#FF0000">*</font></label>
			                        <input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST['other_state']) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required />
			                        <span id="other_stateError"></span>
			                      <?php } ?>
                				</div>
					 		</div>
				 			<div class="row">
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="city">City<font color="#FF0000">*</font></label>
				 					<input type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?php if($_POST['city']) echo $_POST['city'];else echo stripslashes($row->city);?>" data-parsley-required>
				 					<span><?php echo $err_city;?></span>
				 				</div>
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="pincode">Pincode<font color="#FF0000">*</font></label>
				 					<input type="text" class="form-control" placeholder="Enter Pincode" id="pincode" name="pincode" value="<?php if($_POST['pincode']) echo $_POST['pincode'];else echo stripslashes($row->pincode);?>" data-parsley-required >
				 					<span><?php echo $err_pincode;?></span>
				 				</div>
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="zonal">Zonal</label>
				 					<?php $zonalDropDown = '<select class="form-control select2" name="id_mst_zonal" style="width:100%">
										<option value="">Select zonal</option>';
										$resCat = selectSql(TBL_ZONAL," ",' ORDER BY `name`');
										if($db->num_rows2($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
													if($row->id_mst_zonal == $resultCat->id){
														$selected = 'selected="selected"';
													}
													else{
														$selected = '';
													}
													$zonalDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
											}
										}
										echo $zonalDropDown .= '</select>';
									?>
				 				</div>
				 			</div>
				 			<div class="row">
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="gstin">GSTIN</label>
				 					<input type="text" class="form-control" placeholder="Enter GSTIN" id="gstin" name="gstin" value="<?php if($_POST['gstin']) echo $_POST['gstin'];else echo stripslashes($row->gstin);?>">
				 				</div>
								
<div class="form-group col-md-4 col-sm-4">
				 					<label for="pan">CIN</label>
				 					<input type="text" class="form-control" placeholder="Enter CIN" id="cin_no" name="cin_no" value="<?php if($_POST['cin_no']) echo $_POST['cin_no'];else echo stripslashes($row->cin_no);?>">
				 				</div>
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="pan">PAN</label>
				 					<input type="text" class="form-control" placeholder="Enter PAN" id="pan" name="pan" value="<?php if($_POST['pan']) echo $_POST['pan'];else echo stripslashes($row->pan);?>">
				 				</div>
								
								
								<div class="form-group col-md-4 col-sm-4">
				 					<label for="tin">TIN</label>
				 					<input type="text" class="form-control" placeholder="Enter TIN" id="tin" name="tin" value="<?php if($_POST['tin']) echo $_POST['tin'];else echo stripslashes($row->tin);?>">
				 				</div>
								<div class="form-group col-md-4 col-sm-4">
				 					<label for="udyam">UDYAM</label>
				 					<input type="text" class="form-control" placeholder="Enter UDYAM" id="udyam" name="udyam" value="<?php if($_POST['udyam']) echo $_POST['udyam'];else echo stripslashes($row->udyam);?>">
				 				</div>
								<div class="form-group col-md-4 col-sm-4">
				 					<label for="fssai">FSSAI</label>
				 					<input type="text" class="form-control" placeholder="Enter FSSAI" id="fssai" name="fssai" value="<?php if($_POST['fssai']) echo $_POST['fssai'];else echo stripslashes($row->fssai);?>">
				 				</div>
								
								
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="google_map_url">Google Map Url</label>
				 					<input type="text" class="form-control" placeholder="Enter Google Map Url" id="google_map_url" name="google_map_url" value="<?php if($_POST['google_map_url']) echo $_POST['google_map_url'];else echo stripslashes($row->google_map_url);?>">
				 				</div>
				 			</div>
				 			<div class="row">
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="review_url">Review Url</label>
				 					<input type="text" class="form-control" placeholder="Enter Review Url" id="review_url" name="review_url" value="<?php if($_POST['review_url']) echo $_POST['review_url'];else echo stripslashes($row->review_url);?>">
				 				</div>
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="hotel_website_url">Website Url</label>
				 					<input type="text" class="form-control" placeholder="Enter Website Url" id="hotel_website_url" name="hotel_website_url" value="<?php if($_POST['hotel_website_url']) echo $_POST['hotel_website_url'];else echo stripslashes($row->hotel_website_url);?>">
				 				</div>
				 				<div class="form-group col-md-4 col-sm-4">
				 					<label for="hotel_tagline">Hotel Tagline</label>
                  					<input type="text" class="form-control" placeholder="Enter Hotel Tagline" id="hotel_tagline" name="hotel_tagline" value="<?php if($_POST['hotel_tagline']) echo $_POST['hotel_tagline'];else echo stripslashes($row->hotel_tagline);?>">
				 				</div>
				 			</div>
				 			<div class="row">
				 				<div class="form-group col-md-12 col-sm-12">
				 					<label for="brief_description">Overview</label>
				 					<textarea class="ckeditor" id="description" name="brief_description" rows="10" cols="80"><?php if($_POST) echo $_POST['brief_description'];else echo stripslashes($row->brief_description);?></textarea>
				 					<span><?php echo $err_brief_description;?></span>
				 				</div>
				 			</div>
							<div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">Reservations Contact Details</h5>
				                </div> 
				                <hr>
				            </div>
				 			<div class="row">
				 				<div class="form-group col-sm-2">
                      				<label for="primary_contact_type">Primary Contact</label>
                      				<select name="primary_contact_type" id="primary_contact_type" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primary_contactError" data-parsley-required>
                        				<?php if($row->primary_contact_type == 1){?>
                              				<option value="1" selected="selected">Mobile</option>
                              				<option value="2">Landline</option>
                          				<?php }else if($row->primary_contact_type == 2){ ?>
                            				<option value="2" selected="selected">Landline</option>
                            				<option value="1">Mobile</option>
                          				<?php }else{ ?>
                          					<option value="1" selected="selected">Mobile</option>
                          					<option value="2">Landline</option>
                        				<?php } ?>  
                      				</select>
                      				<span id="primary_contactError"></span>
                    			</div>
								<div id="primaryContactDiv">
								<?php if($row->primary_contact_type == 1){ ?>
                      				<div class="form-group col-md-2 col-sm-2">
                        				<label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                        				</label>
                        				<input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
                        				<span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
                      				</div>
                      			<?php }else if($row->primary_contact_type == 2){?>
                      				<div class="form-group col-md-2 col-sm-2">
                        				<label for="primary_landline">Landline<font color="#FF0000">*</font>
                        				</label>
                        				<input type="text" class="form-control" placeholder="Enter Mobile" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" data-parsley-type="digits"  data-parsley-required />
                        				<span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span>
                      				</div>
                      			<?php }else{ ?>
                      				<div class="form-group col-md-2 col-sm-2">
                        				<label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                        				</label>
                        				<input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
                        				<span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span>
                      				</div>
                    			<?php } ?>
								</div>
								<div class="form-group col-md-2 col-sm-2">
			                      <label for="secondary_contact_type">Secondary contact</label>
			                      <select name="secondary_contact_type" id="secondary_contact_type" class="form-control select2" style="width: 100%">
			                        <?php if($row->secondary_contact_type == 1){?>
			                          <option value="1" selected="selected">Landline</option>
			                          <option value="2">Mobile</option>
			                        <?php }else if($row->secondary_contact_type == 2){?>
			                          <option value="2" selected="selected">Mobile</option>
			                          <option value="1">Landline</option>
			                        <?php }else{ ?>
			                          <option value="1" selected="selected">Landline</option>
			                          <option value="2">Mobile</option>
			                       <?php } ?>
			                      </select>
			                    </div>
			                    <div id="secondaryContactDiv">
			                    	<?php if($row->secondary_contact_type == 1){ ?>
			                        <div class="form-group col-md-2 col-sm-2">
			                          <label for="secondary_landline">Landline</label>
			                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
			                        </div>
                      				<?php }else if($row->secondary_contact_type == 2){ ?>
				                        <div class="form-group col-md-2 col-sm-2">
				                          <label for="secondary_mobile">Mobile</label>
				                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" />
				                        </div>
                      				<?php }else{ ?>
				                        <div class="form-group col-md-2 col-sm-2">
				                          <label for="secondary_landline">Landline</label>
				                          <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
				                        </div>
                      				<?php } ?>
			                    </div>
			                    <div class="form-group col-md-2 col-sm-2">
			                    	<label for="email">Email Id<font color="#FF0000">*</font></label>
                  					<input type="text" class="form-control" placeholder="Enter Email Id" id="email" name="email" value="<?php if($_POST['email']) echo $_POST['email'];else echo stripslashes($row->email);?>"  data-parsley-type="email" data-parsley-errors-container="#emailError" data-parsley-required />
                  					<span><?php echo $err_email;?></span>
			                    </div>
								 <div class="form-group col-md-2 col-sm-2">
			                    	<label for="email">Secondary Email Id</label>
                  					<input type="text" class="form-control" placeholder="Enter Secondary Email Id" id="secondary_email" name="secondary_email" value="<?php if($_POST['secondary_email']) echo $_POST['secondary_email'];else echo stripslashes($row->secondary_email);?>"  data-parsley-type="email" data-parsley-errors-container="#emailError" />
                  					<span><?php echo $err_email;?></span>
			                    </div>
				 			</div>
				 			<hr/>
				 			<div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">General Manager Contact Details</h5>
				                </div> 
				                <hr>
				            </div>
				            <div class="row">
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="general_manager">General Manager Name</label>
				            		<input type="text" class="form-control" placeholder="Enter General Manager Name" id="general_manager" name="general_manager" value="<?php if($_POST['general_manager']) echo $_POST['general_manager'];else echo stripslashes($row->general_manager);?>">
                  					<span><?php echo $err_general_manager;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="general_manager_contact">General Manager Contact</label>
				            		<input type="text" class="form-control" placeholder="Enter General Manager Contact" id="general_manager_contact" name="general_manager_contact" value="<?php if($_POST['general_manager_contact']) echo $_POST['general_manager_contact'];else echo stripslashes($row->general_manager_contact);?>">
                  					<span><?php echo $err_general_manager_contact;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="general_manager_email">General Manager Email</label>
				            		<input type="text" class="form-control" placeholder="Enter General Manager Email" id="general_manager_email" name="general_manager_email" value="<?php if($_POST['general_manager_email']) echo $_POST['general_manager_email'];else echo stripslashes($row->general_manager_email);?>"data-parsley-type="email" />
                  					<span><?php echo $err_general_manager_email;?></span>
				            	</div>
				            </div>
				            <hr/>
				            <div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">Hotel Bank Details</h5>
				                </div> 
				                <hr>
				            </div>
				            <div class="row">
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_account_legal_name">Account Name</label>
				            		<input type="text" class="form-control" placeholder="Enter Account Name" id="bank_account_legal_name" name="bank_account_legal_name" value="<?php if($_POST['bank_account_legal_name']) echo $_POST['bank_account_legal_name'];else echo stripslashes($row->bank_account_legal_name);?>" />
                  					<span><?php echo $err_bank_account_legal_name;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_account_no">Account Number</label>
				            		<input type="text" class="form-control" placeholder="Enter Account Number" id="bank_account_no" name="bank_account_no" value="<?php if($_POST['bank_account_no']) echo $_POST['bank_account_no'];else echo stripslashes($row->bank_account_no);?>" />
                  					<span><?php echo $err_bank_account_no;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_account_type">Account Type</label>
				            		<input type="text" class="form-control" placeholder="Enter Account Type" id="bank_account_type" name="bank_account_type" value="<?php if($_POST['bank_account_type']) echo $_POST['bank_account_type'];else echo stripslashes($row->bank_account_type);?>" />
                  					<span><?php echo $err_bank_account_type;?></span>
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_name">Bank Name</label>
				            		<input type="text" class="form-control" placeholder="Enter Bank Name" id="bank_name" name="bank_name" value="<?php if($_POST['bank_name']) echo $_POST['bank_name'];else echo stripslashes($row->bank_name);?>" />
                  					<span><?php echo $err_bank_name;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_ifsc_code">IFSC Code</label>
				            		<input type="text" class="form-control" placeholder="Enter IFSC Code" id="bank_ifsc_code" name="bank_ifsc_code" value="<?php if($_POST['bank_ifsc_code']) echo $_POST['bank_ifsc_code'];else echo stripslashes($row->bank_ifsc_code);?>" />
                  					<span><?php echo $err_bank_ifsc_code;?></span>
				            	</div>
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_swift_code">SWIFT Code</label>
				            		<input type="text" class="form-control" placeholder="Enter SWIFT Code" id="bank_swift_code" name="bank_swift_code" value="<?php if($_POST['bank_swift_code']) echo $_POST['bank_swift_code'];else echo stripslashes($row->bank_swift_code);?>" />
                  					<span><?php echo $err_bank_swift_code;?></span>
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-md-4 col-sm-4">
				            		<label for="bank_branch">Branch</label>
				            		<input type="text" class="form-control" placeholder="Enter Branch" id="bank_branch" name="bank_branch" value="<?php if($_POST['bank_branch']) echo $_POST['bank_branch'];else echo stripslashes($row->bank_branch);?>" />
                  					<span><?php echo $err_bank_branch;?></span>
				            	</div>
				            </div>
				            <hr/>
				            <div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">Hotel Logo</h5>
				                </div> 
				                <hr>
				            </div>
				            <div class="row">
				            	<div class="col-sm-3 col-md-3">
				            		<div class="form-group">				
						 				<label for="image">Logo Image &nbsp;&nbsp;</label>
										<div class="btn btn-default btn-file">
							  				<i class="fa fa-upload"></i> Upload
							 				<input type="file" class="form-control" placeholder="Menu Image" id="image" name="image" value="" onchange="readURL(this);">	
							 				<input type="hidden" name="old_image" value="<?php echo stripslashes($row->image);?>"/>					 
						
										</div>
										<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
									</div>	
									<?php echo $err_image;?>
				            	</div>
				            	<div class="col-sm-9">
				            		<ul class="mailbox-attachments clearfix"> 
									<li id="imageCallback">
									<?php if(@file_exists($image_path.$row->image) && $row->image!=''){ ?>
									<span class="mailbox-attachment-icon has-img">							 
										<img src="<?php echo $image_display_path.$row->image; ?>" alt="Room Image">							  
									</span>			
									<div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->image; ?></a>
											<span class="mailbox-attachment-size">
											  <?php echo round(filesize($image_path.$row->image)/ 1024 ,2).' KB'; ?>
											  <a href="<?php echo $image_display_path.$row->image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									</div>
									<?php }else{ ?>							
									<span class="mailbox-attachment-icon has-img">							 
										<img src="../images/no-hotel-image.jpg" alt="Hotel Image" id="blah">							  
									</span>			
									<div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
											<span class="mailbox-attachment-size">
											   <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
											  <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									</div>							
									<?php }?>  
									</li>                
								  </ul>			  	
				            	</div>
				            </div>
				            <hr/>
				            <div class="card text-dark bg-light">
				                <div class="bg-primary text-center">
				                    <h5 style="padding: 5px;">Services & Facilities</h5>
				                </div> 
				                <hr>
				            </div>
				            <div class="row">
				            	<div class="form-group col-md-12 col-sm-12">
				            		<label for="userlevelId">General Services</label>
									<textarea class="ckeditor" id="ids_mst_hotel_general_services" name="ids_mst_hotel_general_services" rows="10" cols="80"><?php if($_POST) echo $_POST['ids_mst_hotel_general_services'];else echo stripslashes($row->ids_mst_hotel_general_services);?></textarea>
									
				            				
				            		
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-sm-12 col-md-12">
				            		<label for="userlevelId">Outdoor Actitvites</label>
									
									<textarea class="ckeditor" id="ids_mst_hotel_outdoor_services" name="ids_mst_hotel_outdoor_services" rows="10" cols="80"><?php if($_POST) echo $_POST['ids_mst_hotel_outdoor_services'];else echo stripslashes($row->ids_mst_hotel_outdoor_services);?></textarea>
									
				            		
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-sm-12 col-md-12">
				            		<label for="userlevelId">Dining Services</label>
									<textarea class="ckeditor" id="ids_mst_hotel_dining_services" name="ids_mst_hotel_dining_services" rows="10" cols="80"><?php if($_POST) echo $_POST['ids_mst_hotel_dining_services'];else echo stripslashes($row->ids_mst_hotel_dining_services);?></textarea>
									
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-sm-12 col-md-12">
				            		<label for="userlevelId">Kids Related Facilities</label>
				            		<textarea class="ckeditor" id="ids_mst_hotel_kids_related_services" name="ids_mst_hotel_kids_related_services" rows="10" cols="80"><?php if($_POST) echo $_POST['ids_mst_hotel_kids_related_services'];else echo stripslashes($row->ids_mst_hotel_kids_related_services);?></textarea>
												            		
				            	</div>
				            </div>
				            <div class="row">
				            	<div class="form-group col-sm-12 col-md-12">
				            		<label for="userlevelId">Conferences & Meetings Services</label>
				            		<textarea class="ckeditor" id="ids_mst_hotel_conference_services" name="ids_mst_hotel_conference_services" rows="10" cols="80"><?php if($_POST) echo $_POST['ids_mst_hotel_conference_services'];else echo stripslashes($row->ids_mst_hotel_conference_services);?></textarea>
				            		
				            	</div>
				            </div>
							  <div class="row">
				            	<div class="form-group col-sm-12 col-md-12">
				            		<label for="cancellation_policy">Cancellation Policy</label>
				            		<textarea class="ckeditor" id="cancellation_policy" name="cancellation_policy" rows="10" cols="80"><?php if($_POST) echo $_POST['cancellation_policy'];else echo stripslashes($row->cancellation_policy);?></textarea>
				            		
				            	</div>
				            </div>
							
				            <div class="row">
				            	<div class="form-group col-md-12 col-sm-12">
				            		<label for="display_order">Display Order</label>
				            		<input type="number" class="form-control" placeholder="Enter display order" id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>">
				            		<span><?php echo $err_display_order;?></span>
				            	</div>
				            </div>
				            <div class="row">
								<div class="col-md-4 form-group">
		                  			<label for="status">Status</label>
		                  			<div class="input-group">
		                  				<div class="input-group-addon">
		                  					<input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked/> Active
		                  				</div>
		                  				<?php if($row->status == '1'){ ?>
		                  				<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y',strtotime($row->status_active_date));  ?>" readonly="readonly"/>
										<?php }else{ ?>
										<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y'); ?>"  />
										<?php } ?>
		                  			</div>	
                  				</div>
                  				<div class="col-md-4 form-group">
                  					<label for="status">&nbsp;</label>
                  					<div class="input-group">
			                  			<div class="input-group-addon">
			                  				<input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
							 					<?php echo $err_status;?>
			                  			</div>
                  						<?php if($row->status == "0"){ ?>
                  						<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php echo date('d-m-Y',strtotime($row->status_inactive_date));  ?>" autocomplete="off"  readonly="readonly"/>
										<?php }else{ ?>
										<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date"  autocomplete="off" placeholder="dd-mm-yyyy" />
										<?php } ?>
                  						<!--<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php if($row->status_active_date != '0000-00-00')echo date('d-m-Y',strtotime($row->status_active_date));?>"placeholder="dd-mm-yyyy" autocomplete="off" /> -->
                  					</div>
                  			
                  				</div>
				            </div>
				            <?php if($row->date_created){?>
			                <div class="row">
			                  <div class="form-group col-sm-3 col-md-3">
			                    <label for="date_created">Date Created</label>
                  				<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">	
			                  </div>
			                  <div class="form-group col-sm-3 col-md-3">
			                    <label for="last_modified">Last Updated</label>
                  				<input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
			                  </div>
			                  <div class="form-group col-sm-3 col-md-3">
			                   <label for="last_modified_by">Created By</label>
				   				<?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  				<input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">	
			                  </div>
			                  <div class="form-group col-sm-3 col-md-3">
			                  	<label for="last_modified_by">Last Updated By</label>
				   				<?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  				<input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">	
			                  </div>
			                </div>
			              <?php } ?>
					 	</div>

					 	<div class="box-footer">
					 		<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
					 		&nbsp;&nbsp;&nbsp;&nbsp;
			   				<input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageHotels.php");' />
							<input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">
					 	</div>
			 		</form>
    			</div>
    		</div>
    	</div>
    </section>
</div>

<?php include_once("../includes/footer.php")?>

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('change', '#primary_contact_type', function(){
            var primaryContact = $(this).val();
            if(primaryContact == 1){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primary_mobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required /><span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span></div>';

                $("#primaryContactDiv").html(mobile);

            }else if(primaryContact == 2){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primary_landline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" "data-parsley-type="digits" data-parsley-required /><span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span></div>';

                $("#primaryContactDiv").html(landline);
            }
        });

        $(document).on('change', '#secondary_contact_type', function(){
            var secondaryContact = $(this).val();
            if(secondaryContact == 1){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_landline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(landline);

            }else if(secondaryContact == 2){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_mobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(mobile);
            }
        });

        $(document).on('change', '#id_mst_country_lang', function(){
            var otherCountry  = $(this).val();
            if(otherCountry == 10000){
              var countryDiv = `<label col="other_country">Other Country <font color="#FF0000">*</font></label><input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST['other_country']) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required /><span id="other_countryError"></span>`;

              $("#otherCountryDiv").html(countryDiv);

            }else{
              $("#otherCountryDiv").html('<div></div>');
            }
            $.ajax({
              type : 'POST',
              url : '../actions/ajax/ajaxGetState.php',
              data : {countryId : otherCountry},
              success : function(data){
                $("#id_mst_state").html(data); 
                if($("#id_mst_state").val() != 10000)
                {
                  $("#otherStateDiv").html('<div></div>');
                }
              }
            });

        });

        $(document).on('change', '#id_mst_state', function(){
            var otherState  = $(this).val();
            if(otherState == 10000){
              var stateDiv = `<label col="other_state">Other State<font color="#FF0000">*</font></label><input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST['other_state']) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required /><span id="other_stateError"></span>`;

              $("#otherStateDiv").html(stateDiv);

            }else{
              $("#otherStateDiv").html('<div></div>');
            }
        });


	});

	function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
    }
</script>
<script type="text/javascript">
	function audittrial(clicked_value){
		//alert(clicked_value);
		var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var form_name ='manage hotels';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>