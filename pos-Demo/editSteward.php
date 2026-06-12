<?php include_once("../config/auto_loader.php");

$image_path='images/steward/';
$image_display_path='images/steward/';

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){


	$err = 0; 
	// image upload
	if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
		//echo "hello";
	   //no error
		}else{
		//echo "hello";
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
					//echo "hj1";exit;
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
	//Insert Here
	

	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `field_value` = '".addslashes(trim($_POST['field_value']))."' and `table_name` = 'steward' ";
			$db->query($sql);
			$numRows= $db->num_rows();
			if($numRows == '0'){

			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
			$addSql = "   	INSERT INTO `".TBL_ATTRIBUTES."` SET

							`table_name` = 'steward',
							`field_name` = 'steward_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
						
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

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
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Steward details has been added sucessfully.';
				header("location:manageSteward.php?eId=".encryptor(encrypt,$lastInsertId)."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Steward details has not been saved. Please make corrections below.';
			}
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Steward Master Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'update');
			 $editSql = "   	UPDATE `".TBL_ATTRIBUTES."` SET 
							`table_name` = 'steward',
							`field_name` = 'steward_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
								`image` = '".addslashes($_POST['image'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";


							
			if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageSteward.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Steward details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."`
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
	
	
   <!-- <section class="content-header">
      <h1>
       Steward Manager
        <small>Manage Steward</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manageSteward.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Steward</li>
      </ol>
    </section> -->
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>  
            </ul>
			<div class="box-header with-border">
             
				 <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->field_value ?> </span>
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
                
				 <div class="form-group">
                  <label for="name">Steward Name<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-delicious"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Steward Name" id="field_value" name="field_value" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  data-parsley-required>
				<?php echo $err_unit_name;?></div>
                </div>
				
				<div class="form-group">
                  <label for="name">Passcode </label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="password" class="form-control" placeholder="Enter Passcode" id="field_description" name="field_description" value="<?php if($_POST) echo $_POST['field_field_description'];else echo stripslashes($row->field_description);?>"  >
				<?php echo $err_unit_field_description;?></div>
                </div>
				
                <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
           				   
           				   	<!-- image Section-->
				<div class="form-group ">				
							 <label for="image">Description &nbsp;&nbsp;</label>
								<div class="btn btn-default btn-file">
								  <i class="fa fa-upload"></i> Upload
								 <input type="file" class="form-control" placeholder="Outlet Image" id="image" name="image" value="" onchange="readURL(this);">	
									
								 <input type="hidden" name="old_image" value="<?php echo $row->image;?>" />					 
							
								</div>
								<p class="help-block">Must be of width:300px and height:300px.<br />Max. Size: 100KB</p>	
				</div>	


				<div class="form-group">													
							<ul class="mailbox-attachments clearfix"> 
										<li id="imageCallback" style="width: 150px !important;">
										<?php if(@file_exists($image_path.$row->image) && $row->image!=''){  ?>

										<span class="mailbox-attachment-icon has-img">							 
											<img src="<?php echo $image_display_path.$row->image; ?>" alt="Outlet Image">							  
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
											<img src="../images/no-hotel-image.jpg" alt="Item Image" id="blah">							  
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
					
				<!-- image end -->	

				<div class="form-group">
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
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
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageSteward.php"); '>
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


