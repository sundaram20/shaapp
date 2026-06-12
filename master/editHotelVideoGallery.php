<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_VIDEO_GALLERY,'view');
$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_VIDEO_GALLERY,'add');
			$addSql = "   	INSERT INTO `".TBL_VIDEO_GALLERY."` SET 
							`caption` = '".addslashes($_POST['name'])."',
							`video_url`= '".addslashes($_POST['video'])."',
							`id_hotel` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`display_order` = '".addslashes($_POST['display_order'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";

			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Video details has been added sucessfully.';
				header("location:manageHotelVideoGallery.php?eId=".$_REQUEST['eId']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Video has not been saved. Please make corrections below.';
			}
	}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		checkUserLevelPermission($_SESSION['userLevel'],TBL_VIDEO_GALLERY,'update');
		$editSql = "   	UPDATE `".TBL_VIDEO_GALLERY."` SET 
						`caption` = '".addslashes($_POST['name'])."',
						`video_url`= '".addslashes($_POST['video'])."',
						`id_hotel` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
						`display_order` = '".addslashes($_POST['display_order'])."',
						`id_shop` = '".addslashes($_SESSION['shop'])."',
						`id_shop_group` = '1'";
		 $editSql .= "	,`last_modified` = '".currenDateTime()."'
						,`status` = '".addslashes($_POST['status'])."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."'";
							
		if(executeSql($editSql)){
			$_SESSION['successMsg'] = 'Video '.selectColumn(TBL_VIDEO_GALLERY,'caption'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
			header("location:manageHotelVideoGallery.php?eId=".$_REQUEST['eId']."&page=".$_REQUEST['page']);
			exit;
		}else{
			$err++;
			$_SESSION['errorMsg'] = 'Video '.selectColumn(TBL_VIDEO_GALLERY,'caption'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
		}	
	}
}
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_VIDEO_GALLERY."`
								 WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
        Hotel Manager
        <small>Manage Video Gallery</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Video Gallery</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Video</h3>
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
                  <label for="name">Video Title<font color="#FF0000">*</font></label>
                  <input required="required" type="text" class="form-control" placeholder="Enter Title" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->caption);?>">
				<?php echo $err_name;?>
                </div>

                <div class="form-group">
                  <label for="video">Video Embed YouTube Link<font color="#FF0000">*</font></label>
                  <input required="required" type="text" class="form-control" placeholder="Enter Video URL" id="video" name="video" value="<?php if($_POST) echo $_POST['video'];else echo stripslashes($row->video_url);?>">
				
                </div>
                <div class="form-group">
                  <label for="name">Display Order<font color="#FF0000">*</font></label>
                  <input required="required" type="number" min='1' class="form-control"  id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>">
				
                </div>
								
				<div class="form-group">
                  <label for="status">Status</label>
                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
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
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageHotels.php"); '>
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


