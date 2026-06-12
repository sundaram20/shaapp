<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'edit');

/////////////////////////////////////////////////////////////////////////////////////

if($_REQUEST['eId'] == ''){ header("location:editCompany.php"); }

/////////////////////////////////////////////////////////////////////////////////////

//---------------------------------------------------------------------------------------------------------

if($_POST['Save']){		

	$err = 0;

	/*if(empty($_POST['email'])){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter email id.</font>';

	}elseif(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter valid email id.</font>';

	}else if($db->num_rows2(selectSql(TBL_CUSTOMER,"WHERE `id_customer` NOT IN('".addslashes(encryptor(decrypt,$_POST[id]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'  and type='2' AND `email` = '".addslashes($_POST['email'])."'",''))){

		$err++;

		$err_email = '<font style="color:red;font-weight:normal;" >Email all-ready exists in our database.</font>';

	}*/

	

	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'add');

			$addSql = "   	INSERT INTO `".TBL_CUSTOMER."` SET 

							`id_shop_group` = '1',

							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_company_group` = '".addslashes($_POST['id_default_group'])."',

							`id_company` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',

							`id_company_person` = '0',
							`title`='".trim($_REQUEST['title'])."',

							`first_name` = '".trim(addslashes($_POST['first_name']))."',

							`last_name` = '".trim(addslashes($_POST['last_name']))."',

							`id_designation` = '".trim(addslashes($_POST['designation']))."',

							`other_designation` = '".trim(addslashes($_POST['other_designation']))."',

							`grade` = '".trim(addslashes($_POST['grade']))."',

							`email` = '".trim(addslashes($_POST['email']))."',

							`dateofanniversaryMonth` = '".addslashes($_POST['dateofanniversaryMonth'])."',

							`dateofanniversaryday` = '".addslashes($_POST['dateofanniversaryday'])."',

							`dateofBirthMonth` = '".addslashes($_POST['dateofBirthMonth'])."',

							`dateofBirthday` = '".addslashes($_POST['dateofBirthday'])."',

							`id_mst_country` = '".addslashes($_POST['id_mst_country'])."',

							`other_country` = '".trim(addslashes($_POST['other_country']))."',

							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',

							`postcode` = '".addslashes($_POST['postcode'])."',

							`city` = '".trim(addslashes($_POST['city']))."',

							`other_state` = '".trim(addslashes($_POST['other_state']))."',

							`address` = '".trim(addslashes($_POST['address']))."',

							`primary_contact` = '".addslashes($_POST['primary_contact'])."',

							`primary_mobile` = '".trim(addslashes($_POST['primary_mobile']))."',

							`primary_landline` = '".trim(addslashes($_POST['primary_landline']))."',
							`secondary_contact` = '".addslashes($_POST['secondary_contact'])."',
							`secondary_landline` = '".trim(addslashes($_POST['secondary_landline']))."',
							`secondary_mobile` = '".trim(addslashes($_POST['secondary_mobile']))."',

							`type` = '2'";

			

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'

							,`status` = '".addslashes($_POST['status'])."'";

							

			if(executeSql($addSql)){

				unset($_POST);

				$_SESSION['successMsg'] = 'New contact details has been added sucessfully.';

				header("location:editCompany.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'New Contact details has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update

		

			checkUserLevelPermission($_SESSION['userLevel'],TBL_CUSTOMER,'update');

			$editSql = "   	UPDATE `".TBL_CUSTOMER."` SET 

							`id_shop_group` = '1',
							`title`='".trim($_REQUEST['title'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',

							`id_company_group` = '".addslashes($_POST['id_default_group'])."',

							`id_company` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',

							`id_company_person` = '0',

							`first_name` = '".addslashes($_POST['first_name'])."',

							`last_name` = '".addslashes($_POST['last_name'])."',

							`id_designation` = '".addslashes($_POST['designation'])."',

							`other_designation` = '".addslashes($_POST['other_designation'])."',

							`grade` = '".addslashes($_POST['grade'])."',

							`email` = '".addslashes($_POST['email'])."',

							`dateofanniversaryMonth` = '".addslashes($_POST['dateofanniversaryMonth'])."',

							`dateofanniversaryday` = '".addslashes($_POST['dateofanniversaryday'])."',

							`dateofBirthMonth` = '".addslashes($_POST['dateofBirthMonth'])."',

							`dateofBirthday` = '".addslashes($_POST['dateofBirthday'])."',

							`id_mst_country` = '".addslashes($_POST['id_mst_country'])."',

							`other_country` = '".addslashes($_POST['other_country'])."',

							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',

							`postcode` = '".addslashes($_POST['postcode'])."',

							`city` = '".addslashes($_POST['city'])."',

							`other_state` = '".addslashes($_POST['other_state'])."',

							`address` = '".addslashes($_POST['address'])."',

							`primary_contact` = '".addslashes($_POST['primary_contact'])."',

							`primary_mobile` = '".addslashes($_POST['primary_mobile'])."',

							`primary_landline` = '".addslashes($_POST['primary_landline'])."',

							`secondary_contact` = '".addslashes($_POST['secondary_contact'])."',
							`secondary_landline` = '".addslashes($_POST['secondary_landline'])."',
							`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."',

							`type` = '2'";

			

			$editSql .= "	,`last_modified` = '".currenDateTime()."'

							,`status` = '".addslashes($_POST['status'])."'

							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'

							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['id']))."'";								

			if(executeSql($editSql)){

				

				$_SESSION['successMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id` = '".encryptor(decrypt,$_POST['id'])."'").' details has been updated sucessfully.';

				header("location:editCompany.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = selectColumn(TBL_CUSTOMER,'first_name'," WHERE `id_customer` = '".encryptor(decrypt,$_POST['id'])."'").' details has not been saved.Please make corrections below.';

			}

		}

	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'Contact details has not been saved. Please make corrections.';

	}

}

// ----------cate---------

if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_CUSTOMER."`

								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' and type='2'";

	$db->query($sql);

	if($db->num_rows() > 0){

		$row = $db->fetch_object();

	}						

}	

							



?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<div class="content-wrapper">

    <!-- Content Header (Page header) -->

    <section class="content-header">

      <h1>

       Company Manager

        <small>Manage Contacts</small>

      </h1>

      <ol class="breadcrumb">

        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>

        <li class="active">Manage Contacts</li>

      </ol>

    </section>

    <!-- Main content -->

    <section class="content">

      <div class="row">

        <!-- left column -->

        <div class="col-md-12">

          <!-- general form elements -->

           <div class="nav-tabs-custom">

			<ul class="nav nav-tabs">

			   <li ><a href="editCompany.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li> 

			  <li class="active"><a href="manageCustomer.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Contacts</a></li>           

			   

            </ul> 

            <div class="box-header with-border">

               <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Contacts : <a><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".addslashes($_REQUEST['eId'])."'"); ?></a></h3>    

			   <a href="editCompany.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  

			</div> 

            <!-- /.box-header -->

            <!-- form start -->  			        

			  <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" id="form1">

                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />

				<input type="hidden" value="<?php echo selectColumn(TBL_COMPANY,'id_company_group'," WHERE `id` = '".encryptor(decrypt,$_REQUEST['eId'])."'");?>" name="id_default_group" />

					<div class="form-group has-error" align="center">

						<?php if($_SESSION['errorMsg']){?>

						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>

						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>

					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>

						<?php unset($_SESSION['successMsg']);}?>

					 </div>

              <div class="box-body">

				<div class="row">	

				<div class="form-group col-sm-2">

                  <label for="title">Title<font color="#FF0000">*</font></label>

                  <select class="form-control select2" style="width: 100%;" id="title" name="title" data-parsley-errors-container="#titleError" data-parsley-required>
                                <option value="">Select Title</option>
                              <?php 
                                $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop= ".addslashes($_SESSION['shop'])." and `table_name` = 'title' ");

                                if(num_rows($resCat)){

                                  while($resultCat = $db->fetch_object2($resCat)){  
                                   
                                    if($row->title == $resultCat->field_value){

                                      $selected = 'selected="selected"';

                                    }else{

                                      $selected = '';

                                    }

                                    $titleDropDown .= '<option '.$selected.' value="'.$resultCat->field_value.'">'.ucfirst($resultCat->field_value).'</option>';
                                  }
                                }
                                 echo $titleDropDown;
                              ?>
                            </select>
                        <span id="titleError"><?php echo $err_title;?></span>

                </div> 		  

			     <div class="form-group col-sm-3">

                  <label for="first_name">First Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter First name" id="first_name" name="first_name" value="<?php if($_POST) echo $_POST['first_name'];else echo stripslashes($row->first_name);?>" data-parsley-required >

				<?php echo $err_first_name;?>

                </div>                

				 <div class="form-group col-sm-3">

                  <label for="last_name">Last Name<font color="#FF0000">*</font></label>

                  <input type="text" class="form-control" placeholder="Enter Last name" id="last_name" name="last_name" value="<?php if($_POST['last_name']) echo $_POST['last_name'];else echo stripslashes($row->last_name);?>" data-parsley-required >

				<?php echo $err_last_name;?>

                </div>	

				
                <div class="form-group col-sm-2">
                  <label for="id_designation">Designation<font color="#FF0000">*</font></label>
                 <?php
                 $desSql = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name='designations' AND status=1 AND id_shop='".$_SESSION['shop']."' ";

                 $resCat = mysqli_query($connNew,$desSql); 
                 $categoryDropDown = '<select class="form-control select2" style="width:100%" name="designation" data-parsley-errors-container="#designationError" data-parsley-required id="id_designation">
						<option value="">Select Designation</option>';
						  
							  if(mysqli_num_rows($resCat)>0){
						  	while($resultCat = mysqli_fetch_object($resCat)){
								if($_REQUEST['designation'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($row->id_designation == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
								}
							 }

							 if($row->id_designation == 100){
							 	$categoryDropDown .= '<option value="100" selected="selected">Other</option>';
							 }else{
							 	$categoryDropDown .= '<option value="100">Other</option>';
							 }

				echo $categoryDropDown .= '</select>';
											  ?>
				<span id="designationError"><?php echo $err_designation;?></span>
                </div>
            	<div id="otherDesignationDiv">
            		<?php if($row->id_designation == 100){ ?>
            			<div class="form-group col-sm-2">
            				<label col="other_designation">Other Designation<font color="#FF0000">*</font></label><input type="text" name="other_designation" id="other_designation" class="form-control" placeholder="Enter Designation Name"  value="<?php if($_POST) echo $_POST['other_designation']; else echo $row->other_designation;?>" data-parsley-errors-container="#other_designationError" data-parsley-required />
            				<span id="other_designationError"></span>
            			</div>
            		<?php } ?>
		   		</div>
			   </div>	
				<div class="row">

					<div class="form-group col-sm-4">
	                  <label for="">Grade</label>
	                  <input type="text" class="form-control" placeholder="Enter grade" id="grade" name="grade" value="<?php if($_POST) echo $_POST['grade'];else echo stripslashes($row->grade);?>" >
						<?php echo $err_grade;?>
	                </div>

					<div class="form-group col-sm-4">
	                  <label for="email">Email Id</label>
	                  <input type="text" class="form-control" placeholder="Enter email id" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>" data-parsley-type="email"  >
						<?php echo $err_email;?>
	                </div>

	                <div class="form-group col-sm-2">
		              	<label for="primary_contact">Primary contact<font color="#FF0000">*</font></label>
		              	<select name="primary_contact" id="primary_contact" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primary_contactError" data-parsley-required>
		              	<?php if($row->primary_contact == 1){?>
	                        <option value="1" selected="selected">Mobile</option>
	                        <option value="2">Landline</option>
	                    <?php }else if($row->primary_contact == 2){ ?>
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
	                  <?php if($row->primary_contact == 1){ ?>
	                  <div class="form-group col-md-2 col-sm-2">
	                    <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
	                    </label>
	                    <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
	                    <span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
	                  </div>
	              		<?php }else if($row->primary_contact == 2){?>
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
	                    <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
	                    <span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span>
	                  </div>
	              	<?php } ?>
	                </div>

				</div>				

			    <div class="row">
			    	<div class="form-group col-md-2 col-sm-2">
	                  <label for="secondary_contact">Secondary contact</label>
	                  <select name="secondary_contact" id="secondary_contact" class="form-control select2" style="width: 100%">
	                  	<?php if($row->secondary_contact == 1){?>
	                      <option value="1" selected="selected">Landline</option>
	                      <option value="2">Mobile</option>
	                    <?php }else if($row->secondary_contact == 2){?>
	                      <option value="2" selected="selected">Mobile</option>
	                      <option value="1">Landline</option>
	                    <?php }else{ ?>
	                      <option value="1" selected="selected">Landline</option>
	                      <option value="2">Mobile</option>
	                   <?php } ?>
	                  </select>
	                </div>
		            <div id="secondaryContactDiv">
		              <?php if($row->secondary_contact == 1){ ?>
			              <div class="form-group col-md-2 col-sm-2">
			                <label for="secondary_landline">Landline</label>
			                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
			              </div>
		            	<?php }else if($row->secondary_contact == 2){ ?>
		            		<div class="form-group col-md-2 col-sm-2">
			                <label for="secondary_mobile">Mobile</label>
			                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" />
			              </div>
		            	<?php }else{ ?>
		            		<div class="form-group col-md-2 col-sm-2">
			                <label for="secondary_landline">Landline</label>
			                <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits" />
			              </div>
		            	<?php }	?>
		            </div>
			  		<!--<div class="form-group  col-sm-4">
					  <label for="mobile">Mobile Number</label>
					  <input type="text" class="form-control" placeholder="Enter mobile number" id="mobile" name="mobile" value="<?php //if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>" data-parsley-type="digits" data-parsley-length="[10, 10]" >
					<?php echo $err_mobile;?>
					</div>	-->			

					<div class="form-group col-sm-4">
	                  <label for="address">Address</label>
					   <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address" ><?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?></textarea>
						<?php echo $err_address;?>
	                </div>		  

					<div class="form-group col-sm-4">
	                  <label for="city">City</label>
	                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" >
						<?php echo $err_city;?>
	                </div>
			  </div>

			 	<div class="row">	

				 <div class="form-group col-sm-4">

						<label for="id_mst_country" >Country</label>  

								<select class="form-control select2" name="id_mst_country" id="id_mst_country" style="width:100%" data-parsley-errors-container="#id_mst_countryError" onchange="getState(this.value,'','');" >

									<option value="">Select Country</option>

									<?php 

									$resCat = selectSql(TBL_COUNTRY,"where id_lang='1' ",' ORDER BY `name`');

												  if(num_rows($resCat)){

													while($resultCat = $db->fetch_object2($resCat)){

														if($_REQUEST['id_mst_country'] == $resultCat->id_country){

															$selected = 'selected="selected"';

														}elseif($row->id_mst_country == $resultCat->id_country){

														$selected = 'selected="selected"';

														}else{

															$selected = '';

														}

														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';

													}

												  }

												  echo $countryDropDown;
									 ?>
									<?php if($row->id_mst_country == 10000){?>
									<option value="10000" selected="selected">Other</option>
								   <?php } else{ ?>
								   		<option value="10000">Other</option>
								   <?php } ?>
								</select>

							  <span id="id_mst_countryError"></span>

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
		                                   $resCat = selectSql(TBL_STATE," where id_mst_country ='".$row->id_mst_country."' ",' ORDER BY `name` ');
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
		                        <span id="id_stateError"><?php echo $err_id_mst_stateError;?></span>
		                    </div>

							<div class="form-group col-sm-4">

			                  <label for="postcode">Pincode</label>

			                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($row->postcode);?>">

							<?php echo $err_postcode;?>

			                </div>
			  			</div>	
			  			<div class="row">
			              	<div id="otherCountryDiv" class="form-group col-sm-4">
			              		<?php if($row->id_mst_country == 10000){ ?>
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
						<div class="form-group col-md-4 col-sm-2">
                          <label for="dobday">Date of Birth</label>
                         <div class="row">
                            <div class="form-group col-xs-12 col-md-6 col-sm-2">
                              <select class="form-control select2" style="width: 100%;" id="dobday" name="dobday">
                              	<?php
                              	 if(!empty($row->dateofBirthday)){
                              	 	for($Birthday = 1; $Birthday <= 31; $Birthday++){
	                          			if($Birthday==$row->dateofBirthday){
	                          				$selected = 'selected="selected"';
	                          			}else{

	                          				$selected = '';
	                          			}
	                          			echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
									} 
                              	 }else{
                              	 	$selected = 'selected="selected"';
                              	 	echo "<option value='' $selected>Select Day</option>";
                              	 	for($Birthday = 1; $Birthday <= 31; $Birthday++){

	                          			echo "<option value=\"$Birthday\">$Birthday</option>";
									} 
                              	 }
                              	 
                      			?>
                              </select>  
                            </div>
	                          <div class="form-group col-md-6 col-sm-2">
	                            
	                            <select class="form-control select2" style="width: 100%;" id="dobmonth" name="dobmonth">
		                           <?php 
		                           		if(!empty($row->dateofBirthMonth)){
		                           			 for($i = 1; $i <= 12; $i++){
			         							if($i==$row->dateofBirthMonth){
													$selected = 'selected="selected"';
												}else {
													$selected = '';
												}
						
											    $dt = DateTime::createFromFormat('!m', $i);
											    echo "<option value=\"$i\" $selected >".$dt->format('F')."</option>";
			 								}
		                           		}else{
		                           			$selected = 'selected="selected"';
			                              	 	echo "<option value='' $selected>Select Month</option>";
			                              	 	for($i = 1; $i <= 12; $i++){
							
												    $dt = DateTime::createFromFormat('!m', $i);
												    echo "<option value=\"$i\">".$dt->format('F')."</option>";
				 								}
		                           		}
		                           ?>
	                          	</select>  
	                        </div>
                         </div> 
                        </div>   
                        <div class="form-group col-md-4 col-sm-2">
                            <label for="doaday">Anniversary</label>
                            <div class="row">
                             	<div class="form-group col-xs-12 col-md-6 col-sm-2">
	                                <select class="form-control select2" style="width: 100%;" id="doaday" name="doaday">
	                                  	<?php 
											if(!empty($row->dateofanniversaryday)){
			                              	 	for($Birthday = 1; $Birthday <= 31; $Birthday++){
				                          			if($Birthday==$row->dateofanniversaryday){
				                          				$selected = 'selected="selected"';
				                          			}else{

				                          				$selected = '';
				                          			}
				                          			echo "<option value=\"$Birthday\" $selected>$Birthday</option>";
												} 
			                              	 }else{
			                              	 	$selected = 'selected="selected"';
			                              	 	echo "<option value='' $selected>Select Day</option>";
			                              	 	for($Birthday = 1; $Birthday <= 31; $Birthday++){

				                          			echo "<option value=\"$Birthday\">$Birthday</option>";
												} 
			                              	 }

                              			?>
	                                </select>  
                                </div>
                                <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                    <select class="form-control select2" style="width: 100%;" id="doamonth" name="doamonth" >
		 								<?php 
		 									if(!empty($row->dateofanniversaryMonth)){
			                           			for($i = 1; $i <= 12; $i++){
				         							if($i==$row->dateofanniversaryMonth){
														$selected = 'selected="selected"';
													}else {
														$selected = '';
													}
							
												    $dt = DateTime::createFromFormat('!m', $i);
												    echo "<option value=\"$i\" $selected >".$dt->format('F')."</option>";
				 								}
			                           		}else{
			                           			$selected = 'selected="selected"';
				                              	 	echo "<option value='' $selected>Select Month</option>";
				                              	 	for($i = 1; $i <= 12; $i++){
								
													    $dt = DateTime::createFromFormat('!m', $i);
													    echo "<option value=\"$i\">".$dt->format('F')."</option>";
					 								}
			                           		}
		 								?>
                                  	</select>  
                                </div>
                           </div>
                        </div> 
						<!--div class="form-group col-sm-4">
		                  <label for="dob">Date of Birth</label>
		                  <input type="text" class="form-control pickerdate" placeholder="Enter date of birth" id="dob" name="dob" value="<?php //if($_POST) echo $_POST['dob'];else if($row->dob) echo stripslashes(date('d-m-Y',strtotime($row->dob))); else echo date('d-m-Y') ;?>" data-parsley-required>
							<?php //echo $err_dob;?>
		                </div -->



	                	<!--div class="form-group col-sm-4">

		                  <label for="doa">Date of Anniversary</label>

		                  <input type="text" class="form-control pickerdate" placeholder="Enter date of Anniversary" id="doa" name="doa" value="<?php //if($_POST) echo $_POST['doa'];else if($row->doa) echo stripslashes(date('d-m-Y',strtotime($row->doa))); else echo date('d-m-Y') ;?>">

							<?php //echo $err_dob;?>
		                </div -->

					<div class="form-group col-sm-4" style="margin-top:30px;">
	                  <label for="status">Status </label>
	                 <input type="radio" class="flat-red"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked /> Active 
					 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/> Inactive

					 <?php echo $err_status;?>
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

				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

				&nbsp;&nbsp;&nbsp;&nbsp;

			   <a class="btn btn-danger" href='manageCompany.php'>Close</a>

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



<!--<script type="text/javascript">

	$("document").ready(function(){

		var state_val = $("#id_state").val();

		console.log(state_val);

		

			

		/*if(state_val == ""){

			$("#form1").submit(function(e){

				$("#errorTxt").HTML('Kindly select the state');

				return false;



			});

		}*/

	});

</script>-->

<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('change', '#primary_contact', function(){
          var primaryContact = $(this).val();
          if(primaryContact == 1){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primary_mobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-length="[10, 10]" data-parsley-required /><span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span></div>';

              $("#primaryContactDiv").html(mobile);

          }else if(primaryContact == 2){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primary_landline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primary_landline" name="primary_landline" value="<?php if($_POST) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" "data-parsley-type="digits" data-parsley-required /><span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span></div>';

              $("#primaryContactDiv").html(landline);
          }
        });

        $(document).on('change', '#secondary_contact', function(){
          var secondaryContact = $(this).val();
          if(secondaryContact == 1){
            var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_landline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits"/></div>';

              $("#secondaryContactDiv").html(landline);

          }else if(secondaryContact == 2){
            var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_mobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits" data-parsley-length="[10, 10]"/></div>';

              $("#secondaryContactDiv").html(mobile);
          }
        });

        $(document).on('change', '#id_mst_country', function(){
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

        $(document).on('change', '#id_designation', function(){
        	var otherDesignation  = $(this).val();
        	if(otherDesignation == 100){
        		var designation = `<div class="form-group col-sm-2"><label col="other_designation">Other Designation<font color="#FF0000">*</font></label><input type="text" name="other_designation" id="other_designation" class="form-control" placeholder="Enter Designation Name value="<?php if($_POST) echo $_POST['other_designation']; else echo $row->other_designation;?>"data-parsley-errors-container="#other_designationError" data-parsley-required /><span id="other_designationError"></span></div>`;

        		$("#otherDesignationDiv").html(designation);

        	}else{
        		$("#otherDesignationDiv").html('<div></div>');
        	}
        });
	});

</script>
