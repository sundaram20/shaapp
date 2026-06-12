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
	/*if(empty($_POST['priContactMobile'])){
		$err++;
		$err_priContactMobile = '<font style="color:red;font-weight:normal;" ><br>Please enter mobile number.</font>';
	}
	if(empty($_POST['priContactLandline'])){
		$err++;
		$err_priContactLandline = '<font style="color:red;font-weight:normal;" ><br>Please enter mobile number.</font>';
	} */
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
							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',
							`other_country` = '".addslashes($_POST['other_country'])."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',
							`primary_mobile` = '".addslashes($_POST['primary_mobile'])."',
							`primary_landline` = '".addslashes($_POST['primary_landline'])."',
							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',
							`secondary_landline` = '".addslashes($_POST['secondary_landline'])."',
							`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."',
							`fax` = '".addslashes($_POST['fax'])."',
							`id_area` = '".addslashes($_POST['area'])."',
							`id_nac` = '".addslashes($_POST['id_nac'])."',
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
							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',
							`other_country` = '".addslashes($_POST['other_country'])."',
							`credit_limit`='".addslashes($_POST['credit_limit'])."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`city` = '".addslashes($_POST['city'])."',
							`other_state` = '".addslashes($_POST['other_state'])."',
							`address` = '".addslashes($_POST['address'])."',
							`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."',
							`primary_mobile` = '".addslashes($_POST['primary_mobile'])."',
							`primary_landline` = '".addslashes($_POST['primary_landline'])."',
							`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."',
							`secondary_landline` = '".addslashes($_POST['secondary_landline'])."',
							`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."',						
							`fax` = '".addslashes($_POST['fax'])."',
							`id_area` = '".addslashes($_POST['area'])."',
							`id_nac` = '".addslashes($_POST['id_nac'])."',
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
if(!empty($_REQUEST['eId']) && ($_REQUEST['action']=='edit' || $_REQUEST['action']=='change')){
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
                  	 <?php $categoryDropDown = '<select class="form-control select2"  style="width:100%" name="id_default_group" id="id_default_group" data-parsley-required data-parsley-errors-container="#err_default_group">
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
                  <span id="err_default_group"><?php echo $err_default_group;?></span> 
              	</div>
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
	              	<label for="primary_contact_type">Primary contact<font color="#FF0000">*</font></label>
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
	               	<span id="primary_contactError"><?php echo $err_priContactMobile;?></span>
	            </div>
	            
	            <div id="primaryContactDiv">
                  <?php if($row->primary_contact_type == 1){ ?>
                  <div class="form-group col-md-2 col-sm-2">
                    <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                    </label>
                    <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
                    <span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
                  </div>
              		<?php }else if($row->primary_contact_type == 2){?>
              		<div class="form-group col-md-2 col-sm-2">
                    <label for="primary_landline">Landline<font color="#FF0000">*</font>
                    </label>
                    <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_landline" name="primary_landline" value="<?php if($_POST) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" data-parsley-type="digits"  data-parsley-required />
                    <span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span>
                  </div>
              		<?php }else{ ?>
              		<div class="form-group col-md-2 col-sm-2">
                    <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
                    </label>
                    <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits"  data-parsley-required />
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
		                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
		              </div>
	            	<?php }else if($row->secondary_contact_type == 2){ ?>
	            		<div class="form-group col-md-2 col-sm-2">
		                <label for="secondary_mobile">Mobile</label>
		                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" />
		              </div>
	            	<?php }else{ ?>
	            		<div class="form-group col-md-2 col-sm-2">
		                <label for="secondary_landline">Landline</label>
		                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>" data-parsley-type="digits" />
		              </div>
	            	<?php }	?>
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
                  <label for="id_mst_country_lang" >Country<font color="#FF0000">*</font></label>
                  <select class="form-control select2" style="width:100%" name="id_mst_country_lang" id="id_mst_country_lang" data-parsley-errors-container="#countryError" onchange="getState(this.value,'','');" required="required" data-parsley-required>
                    <option value="">Select Country</option>
                    <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if(num_rows($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_country_lang == $resultCat->id_country){
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
								<?php if($row->id_mst_country_lang == 10000){?>
								<option value="10000" selected="selected">Other</option>
							   <?php } else{ ?>
							   		<option value="10000">Other</option>
							   <?php } ?>
                  </select>
                  <span id="countryError"></span> 
              	</div>
                <div class="form-group col-md-4 col-sm-4">
	                <label for="id_mst_state">State<font color="#FF0000">*</font></label>
	                <div class="input-group"> 
	                    <div class="input-group-addon">
	                        <i class="fa fa-adjust"></i> 
	                    </div>
	                      <div id="state"> 
	                       <select class="form-control select2"  name="id_mst_state" id="id_mst_state"  style="width:100%" data-parsley-errors-container="#id_mst_stateError" data-parsley-required>
	                        <?php  if(!empty($row->id_mst_state) && $row->id_mst_state != 10000){
	                           $resCat = selectSql(TBL_STATE," where id_mst_country_lang='".$row->id_mst_country_lang."' ",' ORDER BY `name` ');
	                          if(num_rows($resCat)){
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
	                    </div>
	                </div>
	                <span id="id_mst_stateError"><?php echo $err_id_mst_stateError;?></span>
	            </div>
                <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo $row->postcode;?>">
                  <?php echo $err_postcode;?> 
              	</div>
              </div>

              <div class="row">
              	<div id="otherCountryDiv" class="form-group col-sm-4">
              		<?php if($row->id_mst_country_lang == 10000){ ?>
          				<label col="other_country">Other Country<font color="#FF0000">*</font></label>
          				<input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required />
          				<span id="other_countryError"></span>
              		<?php } ?>
              	</div>
              	<div id="otherStateDiv" class="form-group col-sm-4">
              		<?php if($row->id_mst_state == 10000){ ?>
          				<label col="other_state">Other State<font color="#FF0000">*</font></label>
          				<input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required />
          				<span id="other_stateError"></span>
              		<?php } ?>
              	</div>
              </div>
              
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="details">Details</label>
                  <textarea class="form-control" name="details" id="details"  rows="1" placeholder="Enter Details" automcomplete="off"><?php if($_POST) echo $_POST['details'];else echo $row->details;?></textarea>
                  <?php echo $err_details;?> </div>
                <div class="form-group col-sm-3">
                  <label for="area">Area<font color="#FF0000">*</font></label>
                  <?php 
                  // AND FIND_IN_SET(id,'".$_SESSION['teamMemberAreas']."')
                  $areaDropDown = '<select class="form-control select2" name="area" id="area" style="width:100%" data-parsley-required onChange="areaOnChg(this.value);">
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
                  <select class="form-control select2" onChange="openCreditLimit(this.value);" name="company_credibility" id="company_credibility" style="width:100%" data-parsley-errors-container="#company_credibilityError" data-parsley-required>
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
              		<label for="id_nac">National account code <font color="#FF0000">*</font></label>
              		<select name="id_nac" id="id_nac" class="form-control select2" style="width: 100%" data-parsley-errors-container="#id_nacError" data-parsley-required>
	                  
	                  <?php if($row->id_nac == 1){ ?>
	                  	<option value="1" selected="selected">A</option>
	                  	<option value="2">B</option>
	              	  <?php }else if($row->id_nac == 2){ ?>
	                  	<option value="2" selected="selected">B</option>
	                  	<option value="1">A</option>
	                  <?php }else{ ?>
	                  	<option value="" selected="selected">Select Please</option>
	                  	<option value="1">A</option>
	                  	<option value="2">B</option>
	                  <?php } ?>
	               	</select>
	               	<span id="id_nacError"><?php echo $err_id_nacError;?></span>
              	</div>
                <div class="form-group col-sm-4 col-md-4">
                  <label for="deals_in">Deals In</label>
                  <?php $dealsInDropDown = '<select class="form-control select2"  style="width:100%" name="deals_in" id="deals_in"  data-parsley-errors-container="#deals_inError">
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
                  <span id="deals_inError"><?php echo $err_deals_in;?></span>
                  <!--- Credit form part-->
                  <input type="hidden" name="credithidden" id="credithidden" value="<?php if($row->credit_form != '')echo $row->credit_form; else echo '' ; ?>">
                   </div>
		
                  
                  <div class="form-group col-sm-4 col-md-4">
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
                   <div class="row">
          				<?php if(!empty($_REQUEST['eId'])){ 

                  			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'view');
                  			//if($_REQUEST['eId'] == ''){ header("location:editCompany.php"); }

							if($_REQUEST['action'] == 'change' && ($_REQUEST['activeId'] || $_REQUEST['inactiveId'])){

								if($_REQUEST['activeId'] != ''){

									checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'activate');

									$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));

									$statusSql = "	UPDATE `".TBL_CUSTOMER."`

													SET `status` = '1'

													,`last_modified` = '".currenDateTime()."'

													,`id_mst_user_modified_by` = '".$_SESSION['userId']."'

													WHERE `id` = '".addslashes($statusId)."'";

									}elseif($_REQUEST['inactiveId'] != ''){

										checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'deactivate');

										$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));

										$statusSql = "	UPDATE `".TBL_CUSTOMER."` 

														SET `status` = '0' 

														,`last_modified` = '".currenDateTime()."'

														,`id_mst_user_modified_by` = '".$_SESSION['userId']."'

														WHERE `id` = '".addslashes($statusId)."'";

									}
									if(executeSql($statusSql)){
									
										$err = 0;		

										$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';

									}else{

										$err = 1;

										$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';

									}


								}


								// ----------cate---------

								$sql = " SELECT * FROM `".TBL_CUSTOMER."` WHERE type='2' ";

								if($_REQUEST['eId'] != ''){

									$sql .= " AND `id_mst_company` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								}
								//echo $sql; die();
								
								$db->query($sql);

								$numRows= $db->num_rows();

								$pagging = new pagingClass($sql,$setpage);

								$db->query($pagging->getQuery());

								$total = $db->num_rows();

                  			?>
					  		<div class="col-sm-12">
						  		<div class="box box-primary">
						  			<div class="box-header with-border ">
						  			<button type="button" class="btn btn-success btn-xs toggleBtn" id="btnShow"><i class="fa fa-plus"></i></button>
									<h3 class="box-title">List of Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
									<a href="editCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add New Contacts</a>
								</div>
						  		</div>
								
					  			<div  id="showContactDiv" style="display: none" class="">
					  				<div class="form-group has-error" align="center">
										<?php if($_SESSION['errorMsg']){?>
										 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
										<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
										<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
										<?php unset($_SESSION['successMsg']);}?>

									</div>     
										<input type="hidden" value="" name="act" />
										<div id="listingDiv"></div>
										<div class="box-body table-responsive">
											<table id="example2" class="table">
												<thead class="bg-primary">
													<tr>
													<th width="10%">S.No</th>
													<th>Contact Name</th>
													<th>Primary Contact</th>
													<th>Secondary Contact</th>
													<th>Email</th>
													<th>Status</th>
													<th>Action</th>
													</tr>
												</thead>
												<tbody>
													<?php 				 				
														if($total > 0){$counter = 1;
														  while($rowcontact = $db->fetch_object()){?>
														  	<tr>

											                  <td><?php echo $counter++;?>.&nbsp;</td>

															  <td><?php echo $rowcontact->first_name.' '.$rowcontact->last_name;   ?></td>

															  <td>
															  	<?php if($rowcontact->primary_contact_type == 1){
															  			echo $rowcontact->primary_mobile;
															  		}else{
															  			echo $rowcontact->primary_landline;
															  		} 
															  	?>
															  </td>
															  <td>
															  	<?php if($rowcontact->secondary_contact_type == 1){
															  			echo $rowcontact->secondary_landline;
															  		}else{
															  			echo $rowcontact->secondary_mobile;
															  		} 
															  	?>
															  </td>
															  <td><?php echo $rowcontact->email ?></td>
											                  <td><?=$rowcontact->status=='1'?'<span onclick="location.href=\'editCompany.php?inactiveId='.encryptor(encrypt,$rowcontact->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:darkgreen;cursor:pointer;">Active</span>':'<span onclick="location.href=\'editCompany.php?activeId='.encryptor(encrypt,$rowcontact->id).'&eId='.$_GET['eId'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 

															  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCustomer.php?eId=<?=$_GET['eId']?>&id=<?=encryptor(encrypt,$rowcontact->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" /></td>
															</tr>
														<?php }?> 
													<tr>	 
														<td align="right" colspan="7"><?php  echo $pagging->getLinks();?> </td>
													</tr>                
												<?php }else {?>
												 <tr>
								                    <td height="200" align="center" colspan="7">---- No Record Found ---- </td>
								                 </tr>                 
												<?php }?>
												</tbody>           
											</table>
										</div>
									
					  			</div>	
					  		</div>
			  		<?php } ?>
         		 </div>
                 
				  <div class="row">
				  <!--<div class="form-group col-sm-4" style="margin-top:10px;">
	                  <label for="booking">Booking &nbsp;&nbsp;&nbsp; </label>
	                  <input type="checkbox" class="flat-red"  id="booking" name="booking" value="1" <?php if($_POST['booking']=='1'){echo 'checked="checked"'; }else if(stripslashes($row->booking)=='1'){echo 'checked="checked"'; } ?>>
	                  <?php echo $err_is_online_booking;?> </div>-->
					
					  
	                <div class="form-group col-sm-4" style="margin-top:10px;">
	                  <label for="status">Status </label>
	                  <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked/>
	                  Active
	                  <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/>
	                  Inactive <?php echo $err_status;?> 
	              	</div>
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
              <a   class="btn btn-danger" href='manageCompany.php'>Close</a>
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
		$(document).on('change', '#primary_contact_type', function(){
          var primaryContact = $(this).val();
          if(primaryContact == 1){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primary_mobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required /><span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span></div>';

              $("#primaryContactDiv").html(mobile);

          }else if(primaryContact == 2){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primary_landline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primary_landline" name="primary_landline" value="<?php if($_POST) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" "data-parsley-type="digits" data-parsley-required /><span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span></div>';

              $("#primaryContactDiv").html(landline);
          }
        });

        $(document).on('change', '#secondary_contact_type', function(){
          var secondaryContact = $(this).val();
          if(secondaryContact == 1){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_landline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits"/></div>';

              $("#secondaryContactDiv").html(landline);

          }else if(secondaryContact == 2){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_mobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits"/></div>';

              $("#secondaryContactDiv").html(mobile);
          }
        });

        $(document).on('change', '#id_mst_country_lang', function(){
            var otherCountry  = $(this).val();
            if(otherCountry == 10000){
              var countryDiv = `<label col="other_country">Other Country<font color="#FF0000">*</font></label><input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required /><span id="other_countryError"></span>`;

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
        		var stateDiv = `<label col="other_state">Other State<font color="#FF0000">*</font></label><input type="text" name="other_state" id="other_state" class="form-control" placeholder="Enter State Name" value="<?php if($_POST) echo $_POST['other_state']; else echo $row->other_state;?>" data-parsley-errors-container="#other_stateError" data-parsley-required /><span id="other_stateError"></span>`;

        		$("#otherStateDiv").html(stateDiv);

        	}else{
        		$("#otherStateDiv").html('<div></div>');
        	}
        });

      /*  $("#btnShow").on('click',function(){

        	$('#showContactDiv').toggle();
        	
        }); */

        $(document).on('click','#btnShow',function(){
	        if ($(this).children().is('.fa-plus')) {
		          $(this).children().removeClass('fa-plus');
		          $(this).children().addClass('fa-minus');
	      }else{
	      		$(this).children().removeClass('fa-minus');
	          $(this).children().addClass('fa-plus');
	      }
      	 $('#showContactDiv').toggle();
		});
        
	});

	function getState(countryId){
		$.ajax({
	          type : 'POST',
	          url : '../actions/ajax/ajaxGetState.php',
	          data : {countryId : countryId},
	          success : function(data){
	            $("#id_mst_state").html(data); 
	            if($("#id_mst_state").val() != 10000)
	            {
	              $("#otherStateDiv").html('<div></div>');
	            }
	          }
            });
	}

	<?php if(empty($_REQUEST['eId']) && $_REQUEST['eId']==''){ ?>
	
		window.onload = getState($("#id_mst_country_lang").val(),'','');

   	<?php } ?>

</script>
