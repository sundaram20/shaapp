<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'edit');

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	/*if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter Company name.</font>';
	}else if($db->num_rows2(selectSql(TBL_COMPANY,"WHERE `id_company` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `name` = '".addslashes($_POST['name'])."' AND city='".$_POST['city']."' ",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Company Name all-ready exists in our database.</font>';
	}*/
	if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}
	if(empty($_POST['city'])){
		$err++;
		$err_city = '<font style="color:red;font-weight:normal;" ><br>Please enter city.</font>';
	}	
	if(empty($_POST['mobile'])){
		$err++;
		$err_mobile = '<font style="color:red;font-weight:normal;" ><br>Please enter mobile number.</font>';
	}
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';
	}elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';
	}

	/*if(empty($_POST['eId'])){
		$chkSql="SELECT id_company FROM `".TBL_COMPANY."` WHERE UPPER(name)='".strtoupper($_POST['name'])."' AND id_shop='".$_SESSION['shop']."' AND area='".$_POST['area']."' AND status=1 ";

		$resChk = mysqli_query($connNew,$chkSql);

		if(mysqli_num_rows($resChk)>0){
			$_SESSION['errorMsg'] = 'Duplicate Found ';
			$err++;
		}
	}*/	


	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'add');

			$addSql = "   	INSERT INTO `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`id_area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',
							`credit_form` = '".trim($_POST['credithidden'])."',
							`booking` = '".addslashes($_POST['booking'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Company details has been added sucessfully.';
				header("location:editCompany.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_COMPANY,'update');
			$editSql = "   	UPDATE `".TBL_COMPANY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_company_group` = '".addslashes($_POST['id_default_group'])."',
							`id_lang` = '1',
							`name` = '".addslashes(trim($_POST['name']))."',
							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',
							`id_country` = '".addslashes($_POST['id_country'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`id_state` = '".addslashes($_POST['id_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',							
							`fax` = '".addslashes($_POST['fax'])."',
							`id_area` = '".addslashes($_POST['area'])."',
							`company_credibility` = '".addslashes($_POST['company_credibility'])."',
							`deals_in` = '".addslashes($_POST['deals_in'])."',
							`details` = '".addslashes($_POST['details'])."',	
							`credit_form` = '".trim($_POST['credithidden'])."',						
							`booking` = '".addslashes($_POST['booking'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:editCompany.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved. Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Company details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_COMPANY."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	$db->query($sql);
	if($db->num_rows() > 0){
		$row = $db->fetch_object();
	}

	/*$userType=selectColumn(TBL_USERS,'user_type','WHERE id="'.$_SESSION['userId'].'" ');
	if($row->created_by != $_SESSION['userId'] && $userType==2 ){
		$disable="disabled='disabled'";
	}*/						
}	
							

?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style type="text/css">
	.fa-cloud-upload:hover{
		color: #3C8DBC;
	}

	.fa-cloud-download:hover{
		color: #3C8DBC;
	}
</style>
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> Company Manager <small>Manage Company</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Manage Company</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div class="row">

    	 <!--########## Credit Form Upload jump#######-->  
    	   
    	   <!-- Modal -->
    	     <div class="modal fade" id="creditFormModal" role="dialog" >
    	       <div class="modal-dialog">
    	       
    	         <!-- Modal content-->
    	         <div class="modal-content" style="width: 300px; margin: 0px auto;">
    	           <div class="modal-header">
    	             <button type="button" class="close" data-dismiss="modal">&times;</button>
    	             <h4 class="modal-title">Upload Credit Form </h4><br>
    	             <span id="returnTxt" style="color: Green;"></span>
    	           </div>
    	           <div class="modal-body">
    	             <form name="creditimport" method="post" enctype="multipart/form-data" id="creditimport">
    	               <div >
    	                 <label for="file">Choose File : <span style="color: red;">*</span></label>
    	                 <input type="file" name="creditImport" class="form-control" id="creditImport">
    	               </div><br>
    	               <div >
    	                 <input type="submit" value="uplaod" name="submit" class="btn btn-primary" id="importCredit"><span style="color:red;margin-left:50px; ">*</span> = Required 
    	                 Field<br><span id="returnTxt" style="color: #3C8DBC;margin-left:75px;">File size should be less than 5MB.</span>
    	               </div>

    	            </form>
    	           </div>
    	         </div>
    	         
    	       </div>
    	     </div>
    	     
    	   
    	<!--########## credit form uplaod  Modal End#######-->  





      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>
            <li><a href="manageCustomer.php?eId=<?php echo $_REQUEST['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Contacts</a></li>
          </ul>
          <div class="box-header with-border">
            <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Company : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
          </div>
          <!-- /.box-header -->
          <!-- form start -->
          <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
            <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
            <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_default_group">Company Group<font color="#FF0000">*</font></label>
                  <?php $categoryDropDown = '<select class="form-control select2" name="id_default_group" id="id_default_group" data-parsley-required data-parsley-errors-container="#err_default_group">
					<option value="">Select Company  Group</option>';
											  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='company_group' ",' ORDER BY `field_value`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_default_group'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->id_company_group == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
                  <span id="err_default_group"><?php echo $err_default_group;?></span> </div>
                <div class="form-group col-sm-4">
                  <label for="name">Company Name<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#mylist" placeholder="Enter Company name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required >
                  <ul id="mylist" style="display:none;">
                    <?php  $resCat = selectSql(TBL_COMPANY," where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													$companyDropDown .= '<li>'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</li>';
												}
											  }
											 	echo $companyDropDown;
					?>
                  </ul>
                  <?php echo $err_name;?> </div>
                <div class="form-group col-sm-4">
                  <label for="email">Email Id<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo $row->email;?>" data-parsley-type="email" data-parsley-required >
                  <?php echo $err_email;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email" value="<?php if($_POST) echo $_POST['secondary_email'];else echo $row->secondary_email;?>" data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                <!--<div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo $row->phone;?>" >
                  <?php echo $err_phone;?> </div> -->
	             <div class="form-group col-sm-2">
	              	<label for="primaryContact">Primary contact<font color="#FF0000">*</font></label>
	              	<select name="primaryContact" id="primaryContact" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primaryContactError" data-parsley-required>
	                  <option value="Mobile" selected="selected">Mobile</option>
	                  <option value="Landline">Landline</option>
	               	</select>
	               	<span id="primaryContactError"><?php echo $err_primaryContactError;?></span>
	            </div>
	            <div id="primaryContactDiv">
                  <div class="form-group col-md-2 col-sm-2">
                    <label for="priContactMobile">Mobile<font color="#FF0000">*</font>
                    </label>
                    <input type="text" class="form-control" placeholder="Enter Mobile" id="priContactMobile" name="priContactMobile" value="<?php if($_POST) echo $_POST['priContactMobile']; else echo $row->priContactMobile;?>" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required />
                    <span><?php echo $err_priContactMobileError;?></span>
                  </div>
                </div>
                <div class="form-group col-md-2 col-sm-2">
                  <label for="secondaryContact">Secondary contact</label>
                  <select name="secondaryContact" id="secondaryContact" class="form-control select2" style="width: 100%">
                      <option value="Landline" selected="selected">Landline</option>
                      <option value="Mobile">Mobile</option>
                  </select>
                </div>
	            <div id="secondaryContactDiv">
	              <div class="form-group col-md-2 col-sm-2">
	                <label for="secContactLandline">Landline</label>
	                <input type="text" class="form-control" placeholder="Enter Landline" id="secContactLandline" name="secContactLandline" value="<?php if($_POST) echo $_POST['secContactLandline']; else echo $row->secContactLandline;?>"data-parsley-type="digits" />
	              </div>
	            </div>
                <!--div class="form-group  col-sm-4">
                  <label for="mobile">Mobile Number<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" value="<?php //if($_POST) echo $_POST['mobile'];else echo $row->mobile;?>" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required>
                  <?php // echo $err_mobile;?> </div -->
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" value="<?php if($_POST) echo $_POST['fax'];else echo $row->fax;?>">
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address<font color="#FF0000">*</font></label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" data-parsley-required><?php if($_POST) echo $_POST['address'];else echo $row->address;?>
</textarea>
                  <?php echo $err_address;?> </div>
                <div class="form-group col-sm-4">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo $row->city;?>" data-parsley-required>
                  <?php echo $err_city;?> </div>
              </div>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>
                  <select class="form-control select2" name="id_country" id="id_country" data-parsley-errors-container="#countryError" onchange="getState(this.value,'','');" required="required" data-parsley-required>
                    <option value="">Select Country</option>
                    <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_country'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($row->id_country == $resultCat->id_country){
														$selected = 'selected="selected"';
														}elseif(110 == $resultCat->id_country){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $countryDropDown;
									
									 ?>
								<option value="other">Other</option>
                  </select>
                  <span id="countryError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                  <div id="state">
                    <select class="form-control" name="id_state" id="id_state" data-parsley-errors-container="#stateError">
                      <option value="">Please Select State</option>
                      <option value="other">Other</option>
                    </select>
                  </div>
                  <span id="stateError"></span> </div>
                <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo $row->postcode;?>">
                  <?php echo $err_postcode;?> </div>
              </div>

              <div class="row">
              	<div id="otherCountryDiv"></div>
              	<div id="otherStateDiv"></div>
              </div>
              
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="details">Details</label>
                  <textarea class="form-control" name="details" id="details"  rows="1" placeholder="Enter Details" automcomplete="off"><?php if($_POST) echo $_POST['details'];else echo $row->details;?>
</textarea>
                  <?php echo $err_details;?> </div>
                <div class="form-group col-sm-3">
                  <label for="area">Area<font color="#FF0000">*</font></label>
                  <?php 
                  // AND FIND_IN_SET(id,'".$_SESSION['teamMemberAreas']."')
                  $areaDropDown = '<select class="form-control select2" name="area" id="area" data-parsley-required onChange="areaOnChg(this.value);">
								<option value="">Select Area</option>';
											  $resCat = selectSql(TBL_AREAS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['area'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->id_area == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$areaDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $areaDropDown .= '</select>';
											  ?>
                  <span id="areaError"><?php echo $err_area;?></span> 
              <span id="areaExe" style="color: red"></span>	</div>
                

                <div class="form-group col-sm-3">
                  <label for="company_credibility">Company Credibility</label>
                  <select class="form-control" onChange="openCreditLimit(this.value);" name="company_credibility" id="company_credibility" data-parsley-errors-container="#company_credibilityError" data-parsley-required>
                    <option value="1" <?php if($_REQUEST['company_credibility']=='1'){echo 'selected="selected"';}elseif($row->company_credibility=='1'){echo 'selected="selected"';} ?>>Credit Allowed</option>
                    <option value="2"  <?php if($_REQUEST['company_credibility']=='2'){echo 'selected="selected"';}elseif($row->company_credibility!='1'){echo 'selected="selected"';}?>>Credit Not Allowed</option>
                  </select>
                  <span id="company_credibilityError"><?php echo $err_company_credibility;?></span> </div>
              	<?php 
              	if($row->company_credibility=='1'){
              		$dispalyBox ='style="display:visible"';
              	}
              	else{
              		$dispalyBox ='style="display:none"';
              	}
              	?>


              	<div  <?php echo $dispalyBox;?> class="form-group col-sm-2"  id="credit_limit">
                  <label for="company_credibility">Credit Limit (In Lacs)</label>
                  <input class="form-control"  type="text" name="credit_limit" value="<?php echo $row->credit_limit; ?>">
                </div>
              </div>

              <div class="row">
              	<div class="form-group col-sm-4">
              		<label for="nationalAccountCode">National account code</label>
              		<select name="nationalAccountCode" id="nationalAccountCode" class="form-control select2" style="width: 100%" data-parsley-errors-container="#nationalAccountCodeError" data-parsley-required>
	                  <option value="" selected="selected">Select Please</option>
	                  <option value="A">A</option>
	                  <option value="B">B</option>
	               	</select>
	               	<span id="nationalAccountCodeError"><?php echo $err_nationalAccountCodeError;?></span>
              	</div>
                <div class="form-group col-sm-4">
                  <label for="deals_in">Deals In</label>
                  <?php $dealsInDropDown = '<select class="form-control select2" name="deals_in" id="deals_in"  data-parsley-errors-container="#deals_inError">
						<option value="">Select Company Domain</option>';
											  $resCat = selectSql(TBL_COMPANY_AREA," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['deals_in'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif($row->deals_in == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$dealsInDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $dealsInDropDown .= '</select>';
											  ?>
                  <span id="deals_inError"><?php echo $err_deals_in;?></span> </div>
				  
                

                  
                  <!--- Credit form part-->
                  <input type="hidden" name="credithidden" id="credithidden" value="<?php if($row->credit_form != '')echo $row->credit_form; else echo '' ; ?>">
                  <div class="form-group col-sm-3">
                      	 <label for="creditform">Credit Form</label><br>
                           	<i  class="fa fa-cloud-upload fa-3x" value="" data-toggle="modal" data-target="#creditFormModal"></i>&nbsp Upload

                           	<?php
                           	if($row->credit_form != '')
                           		$link = "ajax/ajaxCreditFormDownload.php?fileName=".$row->credit_form;
                           	else
                           		$link = "#";
                           	?>

                           	<a style="color:#333333;" href="<?php echo $link;?>"><i  class="fa fa-cloud-download fa-3x" value="" ></i></a> &nbsp Download
                   </div>
                   <!--- Credit form part End-->
                  </div>
                  <hr/>
                  <?php if(!empty($_REQUEST['eId'])){ 
                  	if($_REQUEST['eId'] == ''){ header("location:editCompany.php"); }

					if($_REQUEST['action'] == 'change'){

						if($_REQUEST['activeId'] != ''){

							checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');

							$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));

							$statusSql = "	UPDATE `".TBL_CUSTOMER."`

											SET `status` = '1'

											,`last_modified` = '".currenDateTime()."'

											,`last_modified_by` = '".$_SESSION['userId']."'

											WHERE `id_customer` = '".addslashes($statusId)."'";

						}elseif($_REQUEST['inactiveId'] != ''){

							checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');

							$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));

							$statusSql = "	UPDATE `".TBL_CUSTOMER."` 

											SET `status` = '0' 

											,`last_modified` = '".currenDateTime()."'

											,`last_modified_by` = '".$_SESSION['userId']."'

											WHERE `id_customer` = '".addslashes($statusId)."'";

						}

							

						if(executeSql($statusSql)){

							$err = 0;		

							$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has been changed sucessfully.';

						}else{

							$err = 1;

							$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' status has not been changed sucessfully.';

						}

					}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){

						checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');

						$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` = '".addslashes(encryptor(decrypt,$_REQUEST['delId']))."'";

						$sqlDelUsers = selectRow(TBL_CUSTOMER," WHERE `id_customer` = '".addslashes(encryptor(decrypt,$_REQUEST['delId']))."'");

						if(executeSql($delSql)){		

							$err = 0;		

							$_SESSION['successMsg'] = 'One Contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'").' has been deleted sucessfully.';

						}else{

							$err = 1;

							$_SESSION['errorMsg'] = 'Unable to delete contact '.selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".$statusId."'");

						}

					}



					///////////////

					if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){

						checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');	

						$activateIds = implode(',',$_REQUEST['ids']);	

						$statusSql = "	UPDATE `".TBL_CUSTOMER."`

											SET `status` = '1'

											,`last_modified` = '".currenDateTime()."'

											,`last_modified_by` = '".$_SESSION['userId']."'

											WHERE `id_customer` IN (".addslashes($activateIds).")";	

															

						if(executeSql($statusSql)){

							$err = 0;

							$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';

						}else{

							$err = 1;

							$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';

						}	

					}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){

						checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');	

						$deactivateIds = implode(',',$_REQUEST['ids']);	

						$statusSql = "	UPDATE `".TBL_CUSTOMER."`

											SET `status` = '0'

											,`last_modified` = '".currenDateTime()."'

											,`last_modified_by` = '".$_SESSION['userId']."'

											WHERE `id_customer` IN (".addslashes($deactivateIds).")";	

															

						if(executeSql($statusSql)){

							$err = 0;

							$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';

						}else{

							$err = 1;

							$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';

						}	

					}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){

						checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'delete');	

						$deleteIds = implode(',',$_REQUEST['ids']);	

						$delSql = "DELETE FROM `".TBL_CUSTOMER."` WHERE `id_customer` IN (".addslashes($deleteIds).")";	

						if(executeSql($delSql)){		

							$err = 0;		

							$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';

						}else{

							$err = 1;

							$_SESSION['errorMsg'] = 'Unable to delete selected records';

						}

					}	



					// ----------cate---------

					$sql = " SELECT * FROM `".TBL_CUSTOMER."` WHERE type='2' ";

					if($_REQUEST['eId'] != ''){

						$sql .= " AND `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

					}



					//echo $sql;

					$db->query($sql);

					$numRows= $db->num_rows();

					$pagging = new pagingClass($sql,$setpage);

					$db->query($pagging->getQuery());

					$total = $db->num_rows();
                  ?>

                  <div class="row">
			  		<div class="col-sm-12">
				  		<div class="box-header with-border">
							<h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
							<a href="editCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Contacts</a>
						</div>
						<div class="form-group has-error" align="center">
							<?php if($_SESSION['errorMsg']){?>
							 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
							<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
							<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
							<?php unset($_SESSION['successMsg']);}?>

						</div>     
			  			<div class="box">
				  			<div class="box-header">
								<h3 class="box-title">Contacts List</h3> 
							</div>
							<form name="listingForm" action="" method="post">
								<input type="hidden" value="" name="act" />
								<div id="listingDiv"></div>
								<div class="box-body table-responsive">
									<table id="example2" class="table table-bordered table-striped">
										<thead>
											<tr>
											<th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> Check All&nbsp;</th>
											<th>Contact Name</th>
											<th>Status</th>
											<th>Action</th>
											</tr>
										</thead>
										<tbody>
											<?php 				 				
												if($total > 0){$counter = 1;
												  while($row = $db->fetch_object()){?>
												  	<tr>

									                  <td><?php echo $counter++;?>.&nbsp;</td>

													  <td><?php echo $row->first_name.' '.$row->last_name;   ?></td>

									                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCustomer.php?inactiveId='.encryptor(encrypt,$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCustomer.php?activeId='.encryptor(encrypt,$row->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

													  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomer.php?eId=<?=$_GET['eId']?>&id=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageCustomer.php?delId=<?=encryptor(encrypt,$row->id_customer)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/>--></td>
													</tr>
												<?php }?> 
												<!-- <tr>

												 <td align="left" colspan="5">

												 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 

												 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;

												  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>

												</tr>-->
											<tr>	 
												<td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
											</tr>                
										<?php }else {?>
										 <tr>
						                    <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
						                 </tr>                 
										<?php }?>
										</tbody>           
									</table>
								</div>
							</form>
			  			</div>	
			  		</div>
			  </div> 
			  <?php } ?>
			  <div class="row">
			  <!--<div class="form-group col-sm-4" style="margin-top:10px;">
                  <label for="booking">Booking &nbsp;&nbsp;&nbsp; </label>
                  <input type="checkbox" class="flat-red"  id="booking" name="booking" value="1" <?php if($_POST['booking']=='1'){echo 'checked="checked"'; }else if(stripslashes($row->booking)=='1'){echo 'checked="checked"'; } ?>>
                  <?php echo $err_is_online_booking;?> </div>-->
				  
				  
                <div class="form-group col-sm-4" style="margin-top:10px;">
                  <label for="status">Status </label>
                  <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/>
                  Active
                  <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/>
                  Inactive <?php echo $err_status;?> </div>
              </div>
			  
			  
              <?php if($row->date_created){?>
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                </div>
                <div class="form-group col-sm-4">
                  <label for="last_modified_by">Last Updated By</label>
                  <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">
                </div>
              </div>
              <?php } ?>
             </div>
            
            <!-- /.box-body -->
            <div class="box-footer">
              <input type='submit' <?php echo $disable;?> value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
              &nbsp;&nbsp;&nbsp;&nbsp;
              <a   class="btn btn-default" href='manageCompany.php'>Cancel</a>
            </div>
          </form>
        </div>
        <!-- /.box -->
      </div>
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<script>
  window.onload = function() { getState(<?php if($_REQUEST['id_country']){echo "'".$_REQUEST['id_country']."'";}elseif($row->id_country != ''){echo "'".$row->id_country."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['id_state']){echo "'".$_REQUEST['id_state']."'";}elseif($row->id_state != ''){echo "'".$row->id_state."'";}else { echo "'"."'";} ?>,<?php if($_REQUEST['other_state'] != ''){echo "'".$_REQUEST['other_state']."'";}elseif($row->other_state != ''){echo "'".$row->other_state."'";}else { echo "'"."'";} ?>); };
  </script>
<?php include_once("../includes/footer.php")?>

<script type="text/javascript">
	$("document").ready(function(){
		var areaId = $("#area").val();
		 $.ajax({
		 type        : 'POST',
		 url         : '../ajax/ajaxAreaExecutive.php', 
		 data        : 'areaId='+areaId,
		 success     : function(data){
		   $("#areaExe").html(data);
		 } 
		})

		
	});
	function areaOnChg(id){
			var areaId = id;
			 $.ajax({
			 type        : 'POST',
			 url         : '../ajax/ajaxAreaExecutive.php', 
			 data        : 'areaId='+areaId,
			 success     : function(data){
			   $("#areaExe").html(data);
			 } 
			})
		} 
</script>

<script type="text/javascript">
	function openPage(){
		window.location = "manageCompany.php";
	}
</script>

<script type="text/javascript">
	//jump
	$("document").ready(function(){
		$("#importCredit").click(function(){
        $("#creditimport").submit(function(e){
          e.preventDefault();	
          var fileName = $("#creditImport").val();
          console.log(fileName);
          if(fileName == ""){
          	alert("Kindly Select a file.");
          }  
          else{
            $.ajax({
            type        : 'POST',
            contentType : false,
            processData : false,
            dataType	:'json', 
            url         : '../ajax/ajaxCreditFormUpload.php', 
            data        : new FormData(this),
            success     : function(data){
              $("#returnTxt").html(data[0]);
              $("#credithidden").val(data[1]);
            } 
           })
          }
        });
      });

	/*$(".fa-cloud-download").click(function(){
		var fileName = $("#credithidden").val();
		console.log(fileName);
		if(fileName == ""){
			alert("Credit Form not uploaded yet !")
		}
		else{
			$.ajax({
            type        : 'POST', 
            url         : 'ajax/ajaxCreditFormDownload.php', 
            data        : 'fileName='+fileName,
            success     : function(data){
              alert()
            } 
           })
		}
	});*/
	});
	function openCreditLimit(val){
		if(val==1){
			$("#credit_limit").show();
		}
		else{
			$("#credit_limit").hide();
		}
	}
</script>

<script type="text/javascript">  
	$(document).ready(function(){
		$(document).on('change', '#primaryContact', function(){
          var primaryContact = $(this).val();
          if(primaryContact == "Mobile"){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primaryContactMobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primaryContactMobile" name="primaryContactMobile" value="<?php if($_POST) echo $_POST['primaryContactMobile']; else echo $row->primaryContactMobile;?>" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required /><span><?php echo $err_primaryContactMobileError;?></span></div>';

              $("#primaryContactDiv").html(mobile);

          }else if(primaryContact == "Landline"){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primaryContactLandline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primaryContactLandline" name="primaryContactLandline" value="<?php if($_POST) echo $_POST['primaryContactLandline']; else echo $row->primaryContactLandline;?>"data-parsley-type="digits" data-parsley-required /><span><?php echo $err_primaryContactLandlineError;?></span></div>';

              $("#primaryContactDiv").html(landline);
          }
        });

        $(document).on('change', '#secondaryContact', function(){
          var secondaryContact = $(this).val();
          if(secondaryContact == "Landline"){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secContactLandline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secContactLandline" name="secContactLandline" value="<?php if($_POST) echo $_POST['secContactLandline']; else echo $row->secContactLandline;?>"data-parsley-type="digits"/></div>';

              $("#secondaryContactDiv").html(landline);

          }else if(secondaryContact == "Mobile"){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondaryContactMobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondaryContactMobile" name="secondaryContactMobile" value="<?php if($_POST) echo $_POST['secondaryContactMobile']; else echo $row->secondaryContactMobile;?>"data-parsley-type="digits" data-parsley-length="[10, 10]"/></div>';

              $("#secondaryContactDiv").html(mobile);
          }
        });
	});
</script>
