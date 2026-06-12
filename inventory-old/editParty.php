<?php include_once("../config/auto_loader.php");
 
if($_REQUEST['eId']=='') 
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PARTY,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PARTY,'edit');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	 

	if(($_POST['old_attachment'] == '') && ($_FILES['attachment']['name'] == '')){
	   //no error
		}else{
		if($_FILES['attachment']['name'] !=''){
		if($_FILES['attachment']['size']>0 && $_FILES['attachment']['size']<1048576){
		 
			$unique = rand(00000,99999);
        	$filename= basename($_FILES['attachment']['name']);
        	$fname = getNameExt($filename);
        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];			
				if(@move_uploaded_file($_FILES['attachment']['tmp_name'],$image_path.$insert_image)){	
					resize($insert_image,$image_path, $image_path, $width=350,$height=220,$thumb='medium-');
					resize($insert_image,$image_path, $image_path, $width=150,$height=100,$thumb='small-');	
					//////end resize////////
					if(@file_exists($image_path.$_POST['old_attachment']) && ($_POST['old_attachment'] != $_FILES['attachment']['name'])){
						@unlink($image_path.$_POST['old_attachment']);
						@unlink($image_path.'medium-'.$_POST['old_attachment']);
						@unlink($image_path.'small-'.$_POST['old_attachment']);
					}	
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['attachment']['name'].'.</font>';
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
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add 

			$sql = " SELECT * FROM `".TBL_PARTY."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `company_name` = '".addslashes($_POST['company_name'])."' ";
			$db->query($sql);
			$numRows= $db->num_rows();
			if($numRows == '0'){

			checkUserLevelPermission($_SESSION['userLevel'],TBL_PARTY,'add');
			$addSql = "   	INSERT INTO `".TBL_PARTY."` SET

							`company_name` = '".addslashes($_POST['company_name'])."', 
							`company_mailing_name` = '".addslashes($_POST['company_mailing_name'])."', 
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',
							`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."',
							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',

							`fax` = '".addslashes($_POST['fax'])."',
							`address` = '".addslashes($_POST['address'])."',
							`city` = '".addslashes($_POST['city'])."',

							`id_mst_attributes_country` = '".addslashes($_POST['id_mst_attributes_country'])."',
							`id_mst_attributes_state` = '".addslashes($_POST['id_mst_attributes_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`ledger` = '".addslashes($_POST['ledger'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code'])."',
							`id_mst_attributes_party_category` = '".addslashes($_POST['id_mst_attributes_party_category'])."',
							`credit_limit` = '".addslashes($_POST['credit_limit'])."',
							`credit_days` = '".addslashes($_POST['credit_days'])."',
							`gstin` = '".addslashes($_POST['gstin'])."',
							`payment_terms` = '".addslashes($_POST['payment_terms'])."',
							`account_no` = '".addslashes($_POST['account_no'])."',

							`bank` = '".addslashes($_POST['bank'])."',
							`ifsc_no` = '".addslashes($_POST['ifsc_no'])."',
							`branch` = '".addslashes($_POST['branch'])."',
							`remarks` = '".addslashes($_POST['remarks'])."'"; 

			if($_FILES['attachment']['name'] != ''){				
				$addSql .= "	,`attachment` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`attachment` = '".addslashes($_POST['old_attachment'])."'";
			}

			$addSql .= "	,`id_shop` = '".addslashes($_SESSION['shop'])."'
							,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New  Party has been added sucessfully.';
				header("location:manageParty.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Party has not been saved. Please make corrections below.';
			}}else{
				$err++;
				$_SESSION['errorMsg'] = 'Company Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_PARTY,'update');
			 $editSql = "   	UPDATE `".TBL_PARTY."`  SET  
							`company_name` = '".addslashes($_POST['company_name'])."', 
							`company_mailing_name` = '".addslashes($_POST['company_mailing_name'])."', 
							`phone` = '".addslashes($_POST['phone'])."',
							`mobile` = '".addslashes($_POST['mobile'])."',
							`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."',
							`email` = '".addslashes($_POST['email'])."',
							`secondary_email` = '".addslashes($_POST['secondary_email'])."',

							`fax` = '".addslashes($_POST['fax'])."',
							`address` = '".addslashes($_POST['address'])."',
							`city` = '".addslashes($_POST['city'])."',

							`id_mst_attributes_country` = '".addslashes($_POST['id_mst_attributes_country'])."',
							`id_mst_attributes_state` = '".addslashes($_POST['id_mst_attributes_state'])."',
							`postcode` = '".addslashes($_POST['postcode'])."',
							`ledger` = '".addslashes($_POST['ledger'])."',
							`transaction_currency_code` = '".addslashes($_POST['transaction_currency_code'])."',
							`id_mst_attributes_party_category` = '".addslashes($_POST['id_mst_attributes_party_category'])."',
							`credit_limit` = '".addslashes($_POST['credit_limit'])."',
							`credit_days` = '".addslashes($_POST['credit_days'])."',
							`gstin` = '".addslashes($_POST['gstin'])."',
							`payment_terms` = '".addslashes($_POST['payment_terms'])."',
							`account_no` = '".addslashes($_POST['account_no'])."',

							`bank` = '".addslashes($_POST['bank'])."',
							`ifsc_no` = '".addslashes($_POST['ifsc_no'])."',
							`branch` = '".addslashes($_POST['branch'])."',
							`remarks` = '".addslashes($_POST['remarks'])."'"; 

			if($_FILES['attachment']['name'] != ''){				
				$editSql .= "	,`attachment` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`attachment` = '".addslashes($_POST['old_attachment'])."'";
			}
			 
			$editSql .= "	,`id_shop` = '".addslashes($_SESSION['shop'])."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_PARTY, 'company_name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageParty.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_PARTY,'company_name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Party has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_PARTY."`
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
    <!-- Content Header (Page header) -->
  	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li> 
			   <?php if($row->id != ''){ ?>   

			   	<li><a href="manageContacts.php?eId=<?php echo encryptor(encrypt, $row->id); ?>&action=edit&page=<?php echo $_GET['page']; ?>">Contacts</a></li> 

				<?php } else{?>

					 <li><a href="javascript:;">Contact</a></li> 
				<?php } ?>
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->company_name ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>

              <div class="box-body">

              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Company Information</h5>
              		</div> 
              		<hr>
	              	<div class="row">		              	 

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Company Name<font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-crosshairs"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Company Name" id="company_name" name="company_name" value="<?php if($_POST) echo $_POST['company_name'];else echo stripslashes($row->company_name);?>"  data-parsley-required> 
		              		</div>
		                </div>	

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Company Mailing Name</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-maxcdn"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Company Cailing Name" id="company_mailing_name" name="company_mailing_name" value="<?php if($_POST) echo $_POST['company_mailing_name'];else echo stripslashes($row->company_mailing_name);?>" onclick="pasteCompanyname();"> </div>
		                </div>		                
						
		            </div>
		            <div class="row">
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Phone <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-phone"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Phone Number" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($row->phone);?>"  data-parsley-required>
						<?php echo $err_phone;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Mobile <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-mobile"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Mobile Number" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>"  data-parsley-required>
						<?php echo $err_phone;?></div>
		                </div>        	 		                		                
		            </div>

		            <div class="row">
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Secondary Mobile Number</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-mobile"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Secondary Mobile Number" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST) echo $_POST['secondary_mobile'];else echo stripslashes($row->secondary_mobile);?>" >
						<?php echo $err_phone;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Party Category <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-cart-plus"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_party_category" data-parsley-required style="width:100%">
									<option value="">Select Party Category</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'party_category'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_party_category'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_party_category == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							<?php echo $err_item_maingroup;?></div>
		                </div>	

		               		                
  
		            </div>
		            <div class="row">

		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">City</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-check-circle-o"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" > </div>
		                </div>	

		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Country <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-globe"></i> 
						   	</div>
		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_country" id="id_mst_attributes_country" data-parsley-required onChange="getState_countrybased();" style="width:100%">
									<option value="">Select Country</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'country'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_country'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_country == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							<?php echo $err_item_maingroup;?></div>
		                </div>

		            </div>
		            <div class="row">
		            	
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">State <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-stack-exchange"></i> 
					   		</div>

		                  <?php
		                  	 $country =  encryptor(decrypt, $_REQUEST['eId']);  
		                  	 $country_id = selectColumn(TBL_PARTY,'id_mst_attributes_country'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$country."'");
		                   ?>


		                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_state" id="id_mst_attributes_state" data-parsley-required style="width:100%">
									<option value="">Select State</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'state'."' and id_country = '".$country_id."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_attributes_state'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_attributes_state == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							<?php echo $err_item_maingroup;?></div>
		                </div>
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Postal Code</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-product-hunt"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Postal Code" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($row->postcode);?>"  >
						<?php echo $err_phone;?></div>
		                </div> 
		            </div>
		            <div class="row">
		            	
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Local/Interstate <font color="#FF0000">*</font></label>
		                  		<?php if($row->id == !''){  ?>
			                 		<div class="input-group"> 
				              			<div class="input-group-addon">
											<i class="fa fa-podcast"></i> 
									   	</div>
			                 		<?php $type=1;
			                 		if($row->ledger == '1'){
			                 			$categoryDropDown = '<select class="form-control select2" name="ledger" data-parsley-required style="width:100%">
										<option value="1">Local</option>';
			                 		}else{
			                 			$categoryDropDown = '<select class="form-control select2" name="ledger" data-parsley-required style="width:100%">
										<option value="2">Interstate</option>';
			                 		}
			                 		echo $categoryDropDown;
			                 	?>			                 	    
									<option value="1">Local</option>
			                  	 	<option value="2">Interstate</option>
								</select></div>
			                 	<?php 
								} else{ ?>
									<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-podcast"></i> 
								   	</div>
			                 	<select class="form-control select2" name="ledger" data-parsley-required style="width:100%">
									<option value="">Select Ledger</option>
									<option value="1">Local</option>
			                  	 	<option value="2">Interstate</option>
								</select></div>
							<?php } ?> 
		                </div>		            
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Transaction Currency <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-rupee"></i> 
					   		</div>
		                  	<?php $categoryDropDown = '<select class="form-control select2" name="transaction_currency_code" id="transaction_currency_code" data-parsley-required style="width:100%">
									<option value="">Select Currency</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND table_name ='".'currency'."' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['transaction_currency_code'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->transaction_currency_code == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
							<?php echo $err_item_maingroup;?></div>
		                </div> 
		            </div>
		        </div>
		        <hr>

		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Company Address</h5>
              		</div> 
              		<hr>
	              	<div class="row">

	              		 <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Company Address<font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-address-card-o"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Company Address " id="address" name="address" value="<?php if($_POST) echo $_POST['address'];else echo stripslashes($row->address);?>"  data-parsley-required> </div>
		                </div>	

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Email <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-envelope"></i> 
					   		</div>
		                  <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>"  data-parsley-required>
						<?php echo $err_phone;?></div>
		                </div>
		            </div>
		            <div class="row">  
		            	<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Secondary Email</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-envelope"></i> 
					   		</div>
		                  <input type="email" class="form-control" placeholder="Enter Secondary Email" id="secondary_email" name="secondary_email" value="<?php if($_POST) echo $_POST['secondary_email'];else echo stripslashes($row->secondary_email);?>"  >
						<?php echo $err_phone;?></div>
		                </div>   
		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">FAX No</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-fax"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter FAX No" id="fax" name="fax" value="<?php if($_POST) echo $_POST['fax'];else echo stripslashes($row->fax);?>"  >
						<?php echo $err_phone;?></div>
		                </div> 
		                	                						
		            </div> 
 
		        </div>
		        <hr>


		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Bank Account Details</h5>
              		</div> 
	              	<div class="row">

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Account Number</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-list-ol"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Account Number" id="account_no" name="account_no" value="<?php if($_POST) echo $_POST['account_no'];else echo stripslashes($row->account_no);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Bank </label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-university"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Bank" id="bank" name="bank" value="<?php if($_POST) echo $_POST['bank'];else echo stripslashes($row->bank);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
		            </div>

		            <div class="row">

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">IFSC NO</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-list-ol"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter IFSC NO" id="ifsc_no" name="ifsc_no" value="<?php if($_POST) echo $_POST['ifsc_no'];else echo stripslashes($row->ifsc_no);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Branch </label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-bandcamp"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Branch" id="branch" name="branch" value="<?php if($_POST) echo $_POST['branch'];else echo stripslashes($row->branch);?>" >
						<?php echo $err_item_srno;?></div>
		                </div>
		            </div>

		        </div>

		        <hr>

		        <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Payment Details</h5>
              		</div>
              		<hr>
              		<div class="row">		              	 

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Credit Limit </label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-credit-card"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Credit Limit " id="credit_limit" name="credit_limit" value="<?php if($_POST) echo $_POST['credit_limit'];else echo stripslashes($row->credit_limit);?>" > 
		                </div>	</div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Credit Days</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-sun-o"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Credit Days" id="credit_days" name="credit_days" value="<?php if($_POST) echo $_POST['credit_days'];else echo stripslashes($row->credit_days);?>" > </div>
		                </div>		                						
		            </div> 

		            <div class="row">		              	 

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">GSTIN </label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-th"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter GSTIN" id="gstin" name="gstin" value="<?php if($_POST) echo $_POST['gstin'];else echo stripslashes($row->gstin);?>" > 
		              		</div>
		                </div>	

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Payment Terms</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-th-list"></i> 
					   		</div>
		                  <input type="text" class="form-control" placeholder="Enter Payment Terms" id="payment_terms" name="payment_terms" value="<?php if($_POST) echo $_POST['payment_terms'];else echo stripslashes($row->payment_terms);?>" > </div>
		                </div>		                						
		            </div> 
	              	 
		        </div>
		        
		        <hr>
 

		         <div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Attachments / Remarks</h5>
              		</div> 
              		<hr>
              		 <div class="row">	
						
						<div class="col-sm-3">
							<div class="form-group">				
							 <label for="image">Attachment &nbsp;&nbsp;</label>
								<div class="btn btn-default btn-file">
								  <i class="fa fa-upload"></i> Upload
								 <input type="file" class="form-control" placeholder="Item Image" id="attachment" name="attachment" value="" onchange="readURL(this);">	
								 <input type="hidden" name="old_image" value="<?php echo stripslashes($row->attachment);?>"/>					 
							
								</div>
								<p class="help-block">Document Upload Here</p>							 
						</div>	
						<?php echo $err_image;?>
						</div>								
						<div class="col-sm-3">													
							<ul class="mailbox-attachments clearfix"> 
										<li id="imageCallback">
										<?php if(@file_exists($image_path.$row->attachment) && $row->attachment!=''){ ?>
										<span class="mailbox-attachment-icon has-img">							 
											<img src="<?php echo $image_display_path.$row->attachment; ?>" id="blah">							  
										 </span>			
										  <div class="mailbox-attachment-info">
											<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->attachment; ?></a>
												<span class="mailbox-attachment-size">
												  <?php echo round(filesize($image_path.$row->attachment)/ 1024 ,2).' KB'; ?>
												  <a href="<?php echo $image_display_path.$row->attachment; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
												</span>
										  </div>
										<?php }else{ ?>							
										<span class="mailbox-attachment-icon has-img">							 
											<img src="../images/no-hotel-image.jpg" id="blah">							  
										  </span>			
										  <div class="mailbox-attachment-info">
											<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> Document Upload Here</a>
												<span class="mailbox-attachment-size">
												   <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
												  <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
												</span>
										  </div>							
										<?php }?> 
										  
										</li>                
									  </ul>			  
						 </div>
						 <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Remarks </label>
			                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-renren"></i> 
						   	</div>
			                  <input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row->remarks);?>" >
							<?php echo $err_item_srno;?></div>
		                </div>
						</div>
					</div>

					<?php 
			        	if($row->status == ''){
			        		$status = 1;
			        	}else{
			        		$status = $row->status;
			        	}
			        ?>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                	<label for="status">Status :</label>
			                <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                name="status" id="status" /> Active
							<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
							name="status"  id="status"   /> Inactive
							 <?php echo $err_status;?>
							 
		                </div>

					</div>
				</div> 

				<?php if($row->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 

                <div class="form-group">
                  <label for="last_modified_by">Created By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-success" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp; 
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("manageParty.php"); '>
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
 					
<?php include_once("../includes/footer.php")?>

 <script type="text/javascript">


function getState_countrybased() {
		var country_id = document.getElementById("id_mst_attributes_country");
	    var country_id = country_id.options[country_id.selectedIndex].value;
		 
		$.ajax({
			type: "POST",
			url: "../ajax/Stateget.php",
			data:'country_id='+country_id,
			success: function(data){
				console.log(data);
				$("#id_mst_attributes_state").html(data); 
			}
		});
	} 

	//Pasete Company name
	function pasteCompanyname(){
		var company_name = document.getElementById("company_name").value; 
		document.getElementById("company_mailing_name").value = company_name;
	}

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



