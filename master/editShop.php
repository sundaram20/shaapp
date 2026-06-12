<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'view');
$image_path = $UPLOAD_FILES.'/shop/';
$image_display_path = $UPLOAD_FILES_PATH ."/shop/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" >Please enter user title.</font>';
	}	
	//------------------------------
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Please enter user email.</font>';
	}else if($db->num_rows2(selectSql(TBL_SHOP,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `email` = '".addslashes($_POST['email'])."'",''))){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Email all-ready exists in our database.</font>';
	}	
	if(empty($_POST['phone'])){	
	//no error	
	}
	else if(!preg_match("/^[0-9]+$/", $_POST['phone']))
	{ 
		$err++;
		$err_phone = '<br/><font style="color:#FF0000;font-weight:normal;">Please enter Valid Phone No..</font>';
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
        	$insert_image = $fname[0].$unique.".".$fname[1];			
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
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'add');
			$addSql = "   	INSERT INTO `".TBL_SHOP."` SET 
							`id_shop_group` = '1',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`rateletter_url` = '".addslashes($_POST['rateletter_url'])."',
							`email_format_url` = '".addslashes($_POST['email_format_url'])."',	
							`website_url` = '".addslashes($_POST['website_url'])."',						
							`short_code` = '".addslashes($_POST['short_code'])."'";
			$addSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
				if($_FILES['image']['name'] != ''){				
				$addSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$addSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$addSql .= "	,`notify_email` = '".addslashes($_POST['notify_email'])."'";
			$addSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$addSql .= "	,`id_mst_state` = '".addslashes($_POST['id_mst_state'])."'";
			$addSql .= "	,`id_mst_country_lang` = '105'";
			$addSql .= "	,`postcode` = '".addslashes($_POST['postcode'])."'";			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							
							,`use_night_audit_date` = '".addslashes($_POST['use_night_audit_date'])."'
							,`status` = '".addslashes($_POST['status'])."'";
			
			
$sql1 = executeSql("SELECT * FROM `".TBL_SHOP."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_shop',
							`form_code` = 'Manage Users',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);	
			
			
			
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New Shop details has been added sucessfully.';
				header("location:manageShop.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shop details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'update');
			
			
	 $auditquery = "SELECT * From `".TBL_SHOP."` WHERE id =  '".addslashes($_POST['eId'])."' ";
   $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $id = $auditrow -> id;
	  $c1 = $auditrow -> name;
	  $c2 = $auditrow -> short_code;
	  $c3 = $auditrow -> email;
	  $c4 = $auditrow -> phone;
	  $c5 = $auditrow -> address;
	  $c6 = $auditrow -> city;
	  $c7 = $auditrow -> id_mst_state;
	  $c8 = $auditrow -> postcode;
	  $c9 = $auditrow -> status; 
 
	  
    if($c1 != $_POST['name']){
		$ch1 = "Name Changed Details from ". $c1 ." - to - " . $_POST['name'];
	}
	if($c2 != $_POST['short_code']){
		$ch2 ="Short Code Details Changed from " . $c2." - to - ".$_POST['short_code'];
	}
	if($c3 != $_POST['email']){
		$ch3 ="Email Details Changed from " . $c3 ." - to - " . $_POST['email'];
	} 
	if($c4 != $_POST['phone']){
		$ch4 ="Phone Details Changed from " . $c4 ." - to - " . $_POST['phone'];
	}
	
	if($c5 != $_POST['address']){
		$ch5 ="Address Details Changed from " . $c5 ." - to - " . $_POST['address'];
	}
	
	if($c6 != $_POST['city']){
		$ch6 ="City Details Changed from " . $c6 ." - to - " . $_POST['city'];
	}
	
	if($c7 != $_POST['id_mst_state']){
		 $old_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$c7."'");
		 $new_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$_POST['id_mst_state']."'  ");
		$ch7 ="State Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c8 != $_POST['postcode']){
		$ch8 ="PinCode  Details Changed from " . $c8 ." - to - " . $_POST['postcode'];
	}
	
	if($c9 != $_POST['status']){
		if($c9 == 1){$old='Active';}else{$old='Inactive';}
		if( $_POST['status'] == 1){$new='Active';}else{$new='Inactive';}
		$ch9 ="Status Details Changed from " .   $old ." - to - " . $new;
	}
	
	}		
			
			$editSql = "   	UPDATE `".TBL_SHOP."` SET 
			               `id_shop_group` = '1',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`rateletter_url` = '".addslashes($_POST['rateletter_url'])."',
							`email_format_url` = '".addslashes($_POST['email_format_url'])."',	
							`website_url` = '".addslashes($_POST['website_url'])."',	
							`short_code` = '".addslashes($_POST['short_code'])."',
							`use_night_audit_date` = '".addslashes($_POST['use_night_audit_date'])."'";							
			$editSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
				if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			$editSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$editSql .= "	,`notify_email` = '".addslashes($_POST['notify_email'])."'";
			$editSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$editSql .= "	,`id_mst_state` = '".addslashes($_POST['id_mst_state'])."'";
			$editSql .= "	,`id_mst_country_lang` = '105'";
			$editSql .= "	,`postcode` = '".addslashes($_POST['postcode'])."'";
			$editSql .= "	,`force_system_date_as_checkout_date` = '".addslashes($_POST['force_system_date_as_checkout_date'])."'";

			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['eId'])."'";
			
			
		$auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',
							`tables_name` = 'mst_shop',
							`form_code` = 'Manage Users',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
if($ch1=='' && $ch2=='' && $ch3=='' && $ch4=='' && $ch5=='' && $ch6=='' && $ch7=='' && $ch8=='' && $ch9==''){
	
}else{							
	executeSql($auditeditSql);
}
			
			
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Shop details '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' has been updated sucessfully.';
				header("location:manageShop.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Shop '.selectColumn(TBL_SHOP,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Shop details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlUserDetail = "  SELECT * FROM `".TBL_SHOP."`
						WHERE `id` = '".addslashes($_REQUEST['eId'])."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUserDetail = $db->fetch_object();
	}						
}

?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	  	
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
	
	<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <!-- <section class="content-header">
      <h1>
       <span style="color: #f25e74;">  User Manager </span>
        <small>Manage Shop</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageShop.php">User Manager</a></li>
        <li class="active">Manage Shop</li>
      </ol>
    </section> -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
             <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $rowUserDetail->name ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->          
			  <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
                  <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
                  <input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="userid" id="userid" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body" style="padding-top: 0px;">
              	<div class="card text-dark bg-light">
		            <div class="bg-primary text-center">
		                <h5 style="padding: 5px;">General Shop Details</h5>
		            </div> 
		            <hr>
		        </div>
                <div class="row">
                	<div class="form-group col-md-4">
	                  <label for="name">Name<font color="#FF0000">*</font></label>
	                  <input type="text" data-parsley-required class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>">
						<?php echo $err_name;?>
	                </div>
                
	                <div class="form-group col-md-4">
	                  <label for="name">Short Code<font color="#FF0000">*</font></label>
	                  <input type="text" data-parsley-required class="form-control" placeholder="Enter Short Code" id="short_code" name="short_code" value="<?php if($_POST) echo $_POST['short_code'];else echo stripslashes($rowUserDetail->short_code);?>">
						<?php echo $err_short_code;?>
	                </div>

	                <div class="form-group col-md-4">
	                  <label for="email">Email<font color="#FF0000">*</font></label>
	                  <input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>">
					 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_email;?>
	                </div>
                </div>
				
				<div class="row">
					<div class="form-group col-md-4">
	                  <label for="phone">Phone</label>
	                  <input type="text" class="form-control" placeholder="Enter Phone" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($rowUserDetail->phone);?>">
					 <?php echo $err_phone;?>
	                </div>  
					<div class="form-group col-md-4">
	                  <label for="address">Address</label>
	                  <textarea class="form-control" id="address" name="address" rows="1"><?php if($_POST['address']) echo $_POST['address'];else echo stripslashes($rowUserDetail->address);?></textarea>
					 <?php echo $err_address;?>
	                </div>  

	                <div class="form-group col-md-4">
	                  <label for="city">City<font color="#FF0000">*</font></label>
	                  <input type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($rowUserDetail->city);?>">
					 <?php echo $err_city;?>
	                </div>

	                <div class="form-group col-md-4">
                  		<label for="state">State<font color="#FF0000">*</font></label>
                 		<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_state" id="id_mst_state">
							<option value="">Select State</option>';
							$resLocation = selectSql(TBL_STATE," WHERE `status` = '1' AND `id_mst_country_lang` ='110'",' ORDER BY `name`');
							if($db->num_rows2($resLocation)){
								while($resultLocation = $db->fetch_object2($resLocation)){
									if($_REQUEST['id_mst_state'] == $resultLocation->id_state){
											$selected = 'selected="selected"';
									}elseif($rowUserDetail->id_mst_state == $resultLocation->id_state){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$categoryDropDown .= '<option '.$selected.' value="'.$resultLocation->id_state.'">'.ucfirst($resultLocation->name).'</option>';
									}
							}
							echo $categoryDropDown .= '</select>';
						?>
                        <?php echo $err_location;?>
                	</div> 

                	<div class="form-group col-md-4">
	                  <label for="postcode">Pin Code <font color="#FF0000">*</font></label>
	                  <input type="text" class="form-control" placeholder="Enter Pincode" id="postcode" name="postcode" value="<?php if($_POST) echo $_POST['postcode'];else echo stripslashes($rowUserDetail->postcode);?>">
					 <?php echo $err_postcode;?>
	                </div>


                	<div class="form-group col-md-4">
	                  <label for="website_url">Website URL</label>
	                  <input type="text"  class="form-control" placeholder="Enter Website Url" id="website_url" name="website_url" value="<?php if($_POST) echo $_POST['website_url'];else echo stripslashes($rowUserDetail->website_url);?>">
					 <?php echo $err_website_url;?>
	                </div>
				
				</div>
				<div class="card text-dark bg-light">
		        	<hr>
		            <div class="bg-primary text-center">
		                <h5 style="padding: 5px;">Shop Logo</h5>
		            </div> 
		            <hr>
		        </div>
				<div class="row">					
					<div class="col-sm-3">
						<div class="form-group">				
						 <label for="image">Logo &nbsp;&nbsp;</label>
							<div class="btn btn-default btn-file">
							  <i class="fa fa-upload"></i> Upload
							 <input type="file" class="form-control" placeholder="Select Room Image" id="image" name="image" value="" onchange="readURL(this);">	
							 <input type="hidden" name="old_image" value="<?php echo stripslashes($rowUserDetail->image);?>"/>					 
						
							</div>
							<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
							<a href="javascript:void(0);" id="delete" class="btn btn-danger" onClick="removeImage('../ajax/ajaxremoveImage.php','<?php echo TBL_SHOP; ?>','<?php echo $rowUserDetail->id; ?>','image','imageCallback','shop','<?php echo $rowUserDetail->image; ?>');" >Remove</a>
							<!--<a href="javascript:void(0);" id="<?php echo $rowUserDetail->id; ?>" class="btn btn-danger" onClick="removeImage(this.id);" >Remove</a>-->
					</div>	
					<?php echo $err_image;?>
					</div>								
					<div class="col-sm-9">													
						<ul class="mailbox-attachments clearfix"> 
									<li id="imageCallback">
									<?php if(@file_exists($image_path.$rowUserDetail->image) && $rowUserDetail->image!=''){ ?>
									<span class="mailbox-attachment-icon has-img">							 
										<img src="<?php echo $image_display_path.$rowUserDetail->image; ?>" alt="Room Image">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $rowUserDetail->image; ?></a>
											<span class="mailbox-attachment-size">
											  <?php echo round(filesize($image_path.$rowUserDetail->image)/ 1024 ,2).' KB'; ?>
											  <a href="<?php echo $image_display_path.$rowUserDetail->image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									  </div>
									<?php }else{ ?>							
									<span class="mailbox-attachment-icon has-img">							 
										<img src="../images/no-hotel-image.jpg" alt="Item Image" id="blah">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
											<span class="mailbox-attachment-size">
											   <?php echo round(filesize('images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
											  <a href="images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									  </div>							
									<?php }?> 
									  
									</li>                
								  </ul>			  
					 </div>
				</div>
				<hr/>
				<div class="row">
					<div class="form-group col-md-12">
		                <label for="status">Status</label>
		                <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserDetail->status == 1)echo "checked";}?> value="1" name="status" checked /> Active
						<input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserDetail->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
						 <?php echo $err_status;?>
		            </div>
<div class="form-group col-md-12">
		                	<label for="enableUseNightAuditDate">Use Night Audit Date as Doc Date  : </label> 
			               <input type="radio"  name="use_night_audit_date" id="use_night_audit_date" <?php if($_POST['use_night_audit_date'] == '1'){echo "checked";}else{if($rowUserDetail->use_night_audit_date == 1)echo "checked";}?> value="1" /> Yes

			                <input type="radio"  name="use_night_audit_date" id="use_night_audit_date" <?php if($_POST['use_night_audit_date'] == '0'){echo "checked";}else{if($rowUserDetail->use_night_audit_date == 0)echo "checked";}?> value="0" /> No
							 
		                </div> 
					
				<div class="form-group col-md-12">
		                	<label for="enableUseNightAuditDate">Force system date as checkout Date  : </label> 
			               <input type="radio"  name="force_system_date_as_checkout_date" id="force_system_date_as_checkout_date" <?php if($_POST['force_system_date_as_checkout_date'] == '1'){echo "checked";}else{if($rowUserDetail->force_system_date_as_checkout_date == 1)echo "checked";}?> value="1" /> Yes

			                <input type="radio"  name="force_system_date_as_checkout_date" id="force_system_date_as_checkout_date" <?php if($_POST['force_system_date_as_checkout_date'] == '0'){echo "checked";}else{if($rowUserDetail->force_system_date_as_checkout_date == 0)echo "checked";}?> value="0" /> No
							 
		                </div> 	
					
		            <?php if($rowUserDetail->date_created){?>
				  
						<div class="form-group col-md-4">
		                  <label for="date_created">Date Created</label>
		                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserDetail->date_created));?>">				
		                </div> 
				
						<div class="form-group col-md-4">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserDetail->last_modified));?>">				
		                </div> 
				
						<div class="form-group col-md-4">
		                  <label for="last_modified_by">Last Updated By</label>
						    <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserDetail->id_mst_user_modified_by."'",''));?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
		                </div>  

				  
				  <?php } ?>
				</div>
				
				
				            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='window.location.replace("manageShop.php"); '>
		<input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">
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
<?php include_once("../includes/footer.php"); ?>
<script type="text/javascript">
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
		var id = document.getElementById('userid').value;
		$('#auditModal').modal('show');
		var form_name ='Manage Users';
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
