<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'edit');

$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if($_POST['userlevelId'] == ''){
		$err++;
		$err_userlevelId = '<font style="color:red;font-weight:normal;">Please select user level.</font>';
	}
	if($_POST['id_shop'] == ''){
		$err++;
		$err_id_shop = '<font style="color:red;font-weight:normal;">Please select shop.</font>';
	}
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" >Please enter user title.</font>';
	}
	if(empty($_POST['username'])){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Please enter username.</font>';
	}else if($db->num_rows2(selectSql(TBL_USERS,"WHERE `id` NOT IN('".addslashes($_REQUEST[eId])."') AND `user_name` = '".addslashes($_POST['username'])."'",'')) &&$_POST['Save'] == 'Add'){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" >Username all-ready exists in our database.</font>';
	}
	//------------------------------
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Please enter user email.</font>';
	}else if($db->num_rows2(selectSql(TBL_USERS,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `email` = '".addslashes($_POST['email'])."'",'')) && $_POST['Save'] == 'Add'){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Email all-ready exists in our database.</font>';
	}
	if(empty($_POST['password'])){
		$err++;
		$err_password = '<font style="color:red;font-weight:normal;" ><br>Please enter password.</font>';
	}
	if(empty($_POST['mobile'])){
		$err++;
		$err_mobile = '<br/><font style="color:#FF0000;">Please enter valid mobile.</font>';
	}else if(!preg_match("/^[0-9]{10}+$/", $_POST['mobile'])){
		$err++;
		$err_mobile = '<br><font style="color:#FF0000;">Please enter valid mobile.</font>';
	}
	if(empty($_POST['phone'])){	
	//no error	
	}
	else if(!preg_match("/^[0-9]+$/", $_POST['phone']))
	{ 
		$err++;
		$err_phone = '<br/><font style="color:#FF0000;font-weight:normal;">Please enter Valid Phone No..</font>';
	}
	if(empty($_POST['zip'])){
		$err++;
		$err_zip = '<br/><font style="color:#FF0000;">Please enter zip code.</font>';
	}else if(!preg_match("/^[0-9]{6}+$/", $_POST['zip'])){
		$err++;
		$err_zip = '<br><font style="color:#FF0000;">Please enter valid zip code.</font>';
	}
	if($_POST['location'] == ''){
		$err++;
		$err_location = '<font style="color:red;font-weight:normal;" ><br>Please select Location.</font>';
	}
	if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}	
	if(empty($_POST['city'])){
		$err++;
		$err_city = '<font style="color:red;font-weight:normal;" ><br>Please Enter City name.</font>';
	}
	
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'add');
			$addSql = "   	INSERT INTO `".TBL_USERS."` SET 
							`id_shop` = '".addslashes($_POST['id_shop'])."',
							`id_shop_group` = '1',
							`id_user_level` = '".addslashes($_POST['userlevelId'])."',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`user_name` = '".addslashes($_POST['username'])."'";
			$addSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$addSql .= "	,`ids_hotel_access` = '".addslashes(implode(',',$_POST['hotel_access']))."'";
			$editSql .= "	,`ids_outlet_access` = '".addslashes(implode(',',$_POST['outlet_access']))."'";
			
			$addSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
			$addSql .= "	,`mobile` = '".addslashes($_POST['mobile'])."'";
			$addSql .= "	,`company` = '".addslashes($_POST['company'])."'";
			$addSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$addSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";
			$addSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$addSql .= "	,`location` = '".addslashes($_POST['location'])."'";
			$addSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";
			$addSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";
			
			$addSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";
			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by`='".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New User details has been added sucessfully.';
				header("location:manageUsers.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			
			$editSql = "   	UPDATE `".TBL_USERS."` SET 
							`id_shop` = '".addslashes($_POST['id_shop'])."',
							`id_shop_group` = '1',
			                `id_user_level` = '".addslashes($_POST['userlevelId'])."',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`user_name` = '".addslashes($_POST['username'])."'";
			$editSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$editSql .= "	,`ids_hotel_access` = '".addslashes(implode(',',$_POST['hotel_access']))."'";
			$editSql .= "	,`ids_outlet_access` = '".addslashes(implode(',',$_POST['outlet_access']))."'";
			
			$editSql .= "	,`phone` = '".addslashes($_POST['phone'])."'";
			$editSql .= "	,`mobile` = '".addslashes($_POST['mobile'])."'";
			$editSql .= "	,`company` = '".addslashes($_POST['company'])."'";
			$editSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$editSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";
			$editSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$editSql .= "	,`location` = '".addslashes($_POST['location'])."'";
			$editSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";
			$editSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";
			
			$editSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";
			
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['eId'])."'";
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'User details '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' has been updated sucessfully.';
				header("location:manageUsers.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlUserDetail = "  SELECT * FROM `".TBL_USERS."`
						WHERE `id` = '".addslashes($_REQUEST['eId'])."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUserDetail = $db->fetch_object();
	}						
}	
?>
<?php include_once("../includes/left.php")?>
<?php include_once("../includes/header.php")?>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <h1> User Manager <small>Manage Users</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="manageUsers.php">User Manager</a></li>
      <li class="active">Manage Users</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Users</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form name="form1"  method="post" enctype="multipart/form-data" role="form">
      <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
      <div class="form-group has-error">
        <?php if($_SESSION['errorMsg']){?>
        <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
        <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
        <?php unset($_SESSION['successMsg']);}?>
      </div>
      <div class="box-body">
      <div class="form-group">
      <label for="id_shop">Shop<font color="#FF0000">*</font></label>
      <select class="form-control select2" name="id_shop" onchange="getHotel(this.value,'<?php echo $rowUserDetail->ids_hotel_access; ?>','<?php echo $rowUserDetail->id; ?>');">
        <?php $shopDropDown = '<option value="">Select shop</option>';
											  $resUserShop = selectSql(TBL_SHOP," WHERE `id` = '".addslashes($_SESSION['shop'])."' and  `status` = '1'",' ORDER BY `name`');
											  if($db->num_rows2($resUserShop)){
											  	while($resultUserShop = $db->fetch_object2($resUserShop)){
													if($_REQUEST['id_shop'] == $resultUserShop->id){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->id_shop == $resultUserShop->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$shopDropDown .= '<option '.$selected.' value="'.$resultUserShop->id.'">'.ucfirst($resultUserShop->name).'</option>';
												}
											  }
											 	echo $shopDropDown .= '</select>';
											  ?>
        <?php echo $err_id_shop;?>
        </div> 
        <div class="form-group"> 
        <label for="userlevelId"> 
        User Level<font color="#FF0000">*</font> 
        </label> 
        <?php
         
        $categoryDropDown = '<select class="form-control select2" name="userlevelId">
											  	<option value="">Select User Level</option>
											  	';
											  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
											  if($db->num_rows2($resUserLevel)){
											  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
													if($_REQUEST['userlevelId'] == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->id_user_level == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
        <?php echo $err_userlevelId;?>
        </div>
        <div class="form-group"> 
        <label for="hotel_access"> 
        Hotel Access 
        </label> 
        <select class="form-control select2" name="hotel_access[]" multiple="multiple" id="hotel_access" placeholder="Select Hotel"> 
      </select>
      <p class="help-block">&nbsp;Leave Empty for all hotels.</p>
      <?php echo $err_hotel_access;?>
      </div> 
		<!--outlet-->
		<div class="form-group"> 
        	<label for="hotel_access"> 
       	 		Outlet Access
        	</label> 
        	<?php
        		$outletSql = "SELECT * FROM ".TBL_OUTLETS." WHERE id_shop='".$_SESSION['shop']."'  ";
        		$resOutlet = mysqli_query($connNew,$outletSql);

        	?>
       		<select class="form-control select2" name="outlet_access[]" multiple="multiple" id="outlet_access" placeholder="Select Outlet"> 
       			<?php 
       				while($rowOutlet = mysqli_fetch_object($resOutlet)) { 
       					
       					if(in_array($rowOutlet->id, explode(',',$rowUserDetail->ids_outlet_access)))
       						$selected="selected='selected'";
       					else
       						$selected="";	
       			?>
					<option <?php echo $selected; ?> value="<?php echo $rowOutlet->id ;?>"><?php echo $rowOutlet->name; ?></option>
       			<?php } ?>	
      		</select>
      		<p class="help-block">&nbsp;Leave Empty for all outlets.</p>
      		<?php echo $err_hotel_access;?>
      </div> 
	<!--outlet end-->	

      <div class="form-group">
        <label for="name">Name<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>">
        <?php echo $err_name;?> </div>
      <div class="form-group">
        <label for="username">Username<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your username" id="username" name="username" value="<?php if($_POST) echo $_POST['username'];else echo stripslashes($rowUserDetail->user_name);?>">
        <p class="help-block">&nbsp;Must be unique.</p>
        <?php echo $err_username;?> </div>
      <div class="form-group">
        <label for="email">Email<font color="#FF0000">*</font></label>
        <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>">
        <p class="help-block">&nbsp;Must be unique.</p>
        <?php echo $err_email;?> </div>
      <div class="form-group">
        <label for="password">Password<font color="#FF0000">*</font></label>
        <input type="password" class="form-control" placeholder="Enter your password" id="password" name="password"  value="<?php if($_POST) echo $_POST['password'];else echo stripslashes(base64_decode($rowUserDetail->password));?>">
        <?php echo $err_password;?> </div>
      <div class="form-group">
        <label for="phone">Phone</label>
        <input type="text" class="form-control" placeholder="Enter your phone" id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($rowUserDetail->phone);?>">
        <?php echo $err_phone;?> </div>
      <div class="form-group">
        <label for="phone">Mobile<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your mobile" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($rowUserDetail->mobile);?>">
        <?php echo $err_mobile;?> </div>
      <div class="form-group">
        <label for="company">Company</label>
        <textarea class="form-control" id="company" name="company" ><?php if($_POST) echo $_POST['company'];else echo stripslashes($rowUserDetail->company);?>
</textarea>
        <?php echo $err_company;?> </div>
      <div class="form-group">
        <label for="address">Address 1</label>
        <textarea class="form-control" id="address" name="address"><?php if($_POST) echo $_POST['address'];else echo stripslashes($rowUserDetail->address);?>
</textarea>
        <?php echo $err_address;?> </div>
      <div class="form-group">
        <label for="address2">Address 2</label>
        <textarea class="form-control" id="address2" name="address2" ><?php if($_POST) echo $_POST['address2'];else echo stripslashes($rowUserDetail->address2);?>
</textarea>
        <?php echo $err_address2;?> </div>
      <div class="form-group">
        <label for="city">City<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your mobile" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($rowUserDetail->city);?>">
        <?php echo $err_city;?> </div>
      <div class="form-group">
        <label for="location">State<font color="#FF0000">*</font></label>
        <?php $categoryDropDown = '<select class="form-control" name="location" id="location">
											  	<option value="">Select State</option>';
											  $resLocation = selectSql(TBL_STATE," WHERE `status` = '1' AND `id_country` ='110'",' ORDER BY `name`');
											  if($db->num_rows2($resLocation)){
											  	while($resultLocation = $db->fetch_object2($resLocation)){
													if($_REQUEST['location'] == $resultLocation->id_state){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->location == $resultLocation->id_state){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultLocation->id_state.'">'.ucfirst($resultLocation->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
        <?php echo $err_location;?> </div>
      <div class="form-group">
        <label for="zip">Zip Code <font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter your zip code" id="zip" name="zip" value="<?php if($_POST) echo $_POST['zip'];else echo stripslashes($rowUserDetail->zip);?>">
        <?php echo $err_zip;?> </div>
      <div class="form-group">
        <label for="skype">Skype</label>
        <input type="text" class="form-control" placeholder="Enter your skype id" id="skype" name="skype" value="<?php if($_POST) echo $_POST['skype'];else echo stripslashes($rowUserDetail->skype);?>">
        <?php echo $err_skype;?> </div>
      <div class="form-group">
        <label for="comments">About</label>
        <textarea class="form-control" id="comments" name="comments" ><?php if($_POST) echo $_POST['comments'];else echo stripslashes($rowUserDetail->comments);?>
</textarea>
        <?php echo $err_comments;?> </div>
      <div class="form-group">
        <label for="status">Status</label>
        <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserDetail->status == 1)echo "checked";}?> value="1" name="status"/>
        Active
        <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserDetail->status == 0)echo "checked";}?> value="0" name="status"/>
        Inactive <?php echo $err_status;?> </div>
      <?php if($rowUserDetail->date_created){?>
      <div class="form-group">
        <label for="date_created">Date Created</label>
        <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserDetail->date_created));?>">
      </div>
      <div class="form-group">
        <label for="last_modified">Last Updated</label>
        <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserDetail->last_modified));?>">
      </div>
      <div class="form-group">
        <label for="last_modified_by">Last Updated By</label>
        <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserDetail->id_mst_user_modified_by."'",''));?>
        <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">
      </div>
      <?php } ?>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' value='Cancel' class="btn btn-default" onclick='window.location.replace("manageUsers.php"); '>
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
<script>
window.onload = function() {

				getHotel('<?php echo $rowUserDetail->id_shop; ?>','<?php echo $rowUserDetail->ids_hotel_access; ?>','<?php echo $rowUserDetail->id; ?>');
				
				
				 };
							
</script>
