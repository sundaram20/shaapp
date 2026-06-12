<?php include_once("../config/auto_loader.php");
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter user name.</font>';
	}else if(mysqli_num_rows(mysqli_query($connNew,"	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".addslashes($_SESSION['userId'])."') AND `name` = '".addslashes($_POST['name'])."'"))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Name all-ready exists in our database.</font>';
	}
	if(empty($_POST['user_name'])){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Please enter username.</font>';
	}else if(mysqli_num_rows(mysqli_query($connNew,"	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".addslashes($_SESSION['userId'])."') AND `user_name` = '".addslashes($_POST['user_name'])."'"))){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Username all-ready exists in our database.</font>';
	}
	//------------------------------
	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Please enter user email.</font>';
	}else if(mysqli_num_rows(mysqli_query($connNew,"	SELECT * FROM `".TBL_USERS."` 
											WHERE `id` NOT IN('".$_SESSION['userId']."') AND `email` = '".addslashes($_POST['email'])."'"))){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" ><br>Email all-ready exists in our database.</font>';
	}
	if(empty($_POST['password'])){
		$err++;
		$err_password = '<font style="color:red;font-weight:normal;" ><br>Please enter password.</font>';
	}
	//---------------------------------
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_SESSION['userId'])){//add			
		}else if(($_POST['Save'] == 'Edit') && !empty($_SESSION['userId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'update');
			//`user_level` = '".addslashes($_POST['userlevelId'])."',
			$editSql = "   	UPDATE `".TBL_USERS."` SET 
			                `name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`user_name` = '".addslashes($_POST['user_name'])."'";
			$editSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_SESSION['userId'])."'";
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'User details for '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' has been updated sucessfully.';				
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_SESSION['userId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_SESSION['userId'])){
	$sqlUserDetail = "  SELECT * FROM `".TBL_USERS."`
						WHERE `id` = '".addslashes($_SESSION['userId'])."'";
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
    <section class="content-header">
      <h1>
       Profile Manager
        <small>Preview</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">User</a></li>
        <li class="active">Profile</li>
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
              <h3 class="box-title"><?php echo $_SESSION['userId']==''?'Add':'Edit'?> Profile </h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form name="form1"  method="post" enctype="multipart/form-data" role="form">
              <input type="hidden" value="<?php echo $_SESSION['userId'];?>" name="eId" />
					<div class="form-group has-error">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
               <!-- <div class="form-group">
                  <label for="userlevelId">User Level</label>
                  <?php $categoryDropDown = '<select  class="form-control" name="userlevelId" disabled id="userlevelId">
											<option value="">Select User Level</option>';
											  $resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `status` = '1'",' ORDER BY `name`');
											  if(mysqli_num_rows($resUserLevel)){
											  	while($resultUserLevel = mysqli_fetch_object($resUserLevel)){
													if($rowUserDetail->user_level == $resultUserLevel->id){
														$selected = 'selected="selected"';
													}elseif($rowUserDetail->user_level == $resultUserLevel->id){
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
                </div>-->
				 <div class="form-group">
                  <label for="name">Name</label>
                  <input type="text" class="form-control" placeholder="Enter your name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>">
				<?php echo $err_name;?>
                </div>
				
				<div class="form-group">
                  <label for="user_name">Username</label>
                  <input type="text" class="form-control" placeholder="Enter your username" id="user_name" name="user_name" value="<?php if($_POST) echo $_POST['user_name'];else echo stripslashes($rowUserDetail->user_name);?>">
				 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_username;?>
                </div>
				
				<div class="form-group">
                  <label for="email">Email</label>
                  <input type="email" class="form-control" placeholder="Enter your email" id="email" name="email"  value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>">
				 <p class="help-block">&nbsp;Must be unique.</p><?php echo $err_email;?>
                </div>
				
				<div class="form-group">
                  <label for="password">Password</label>
                  <input type="password" class="form-control" placeholder="Enter your password" id="password" name="password"  value="<?php if($_POST) echo $_POST['password'];else echo stripslashes(base64_decode($rowUserDetail->password));?>">
				 <?php echo $err_password;?>
                </div>    
				
				<?php if($rowUserDetail->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes($rowUserDetail->date_created);?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes($rowUserDetail->last_modified);?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = @mysqli_fetch_object(@mysqli_query($connNew,"SELECT `user_name` FROM `".TBL_USERS."` WHERE `id` = '".$rowUserDetail->id_mst_user_modified_by."'"));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_SESSION['userId']==''?'Add':'Edit')?>' class="btn c-btn" name="Save" >
				
			   <input type='button' value='Cancel' class="btn c-btn" onclick='location.replace("manageUsers.php"); '>
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