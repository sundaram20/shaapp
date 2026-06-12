<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PACKAGE_LINKING,'add');

if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter user level title.</font>';
	}else if($db->num_rows2(selectSql(TBL_CHANNEL_MANAGER,"WHERE `id` NOT IN('".addslashes(encryptor('decrypt',$_POST['eId']))."') AND `name` = '".addslashes($_POST['name'])."'",''))){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Channel name all-ready exists in our database.</font>';
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'add');
			$addSql = "   	INSERT INTO `".TBL_CHANNEL_MANAGER."` SET 
							`name` = '".addslashes($_POST['name'])."',	
							`channel_type`	='".addslashes($_POST['channel_type'])."',
							`channel_mapping_code` = '".addslashes($_POST['channel_shop_code'])."',
							`description` = '".addslashes($_POST['description'])."'";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				unset($_POST);
				$_SESSION['successMsg'] = 'New Channel details has been added sucessfully.';
				header("location:manageChannels.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Channel has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'update');
			$editSql = "   	UPDATE `".TBL_CHANNEL_MANAGER."` SET 
							`name` = '".addslashes($_POST['name'])."',	
							`channel_type`	='".addslashes($_POST['channel_type'])."',
							`channel_mapping_code` = '".addslashes($_POST['channel_shop_code'])."',
							`description` = '".addslashes($_POST['description'])."'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Channel '.selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'").' details has been updated sucessfully.';
				header("location:manageChannels.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Channel '.selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['eId']))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Channel details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_CHANNEL_MANAGER."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'";
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
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>

    <!-- Main content -->
    <section class="content">		
	<div class="box box-success">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
		
      
        
        <!-- /.box-header -->
		<form name="addForm" id="addForm" action="" method="post">
        <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
        <div class="box-body" id="rowGrid">
          <div class="row" >
            <div class="col-md-12">
              <div class="form-group">
        <label for="hotel">Channel Name<font color="#FF0000">*</font></label>
        <input type="text" class="form-control" placeholder="Enter Channel name" id="name" name="name"  value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>">
        <?php echo $err_name;?>
         </div>
          
              <div class="form-group">
        		<label for="room">Description<font color="#FF0000">*</font></label>
        	
				   <textarea class="ckeditor" id="description" name="description" rows="10" cols="80"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>
                   <?php echo $err_description;?>
         	</div>
             <div class="form-group">
                           <label for="channel_type" >Channel Type</label>
                           <select name="channel_type" id="channel_type" class="form-control">
                            <option value="">Select Channal Type</option>
                            <option value="1" <?php if($row->channel_type == '1'){ echo 'selected="selected"';} ?>>Channel</option>
                            <option value="2" <?php if($row->channel_type == '2'){ echo 'selected="selected"';} ?>>PMS</option>
							 <option value="3" <?php if($row->channel_type == '3'){ echo 'selected="selected"';} ?>>CRM</option>  
							 <option value="4" <?php if($row->channel_type == '4'){ echo 'selected="selected"';} ?>>BE</option>   
 
                          </select>
                         </div>

				<div class="form-group">
					<label for="channel_shop_code">Channel Shop Code</label>
					<input type="text" class="form-control" placeholder="Enter Channel Shop Code" id="channel_shop_code" name="channel_shop_code" value="<?php if($_POST) echo htmlspecialchars($_POST['channel_shop_code']); else echo stripslashes($row->channel_mapping_code); ?>">
					<?php echo $err_channel_shop_code; ?>
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
                      <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->last_modified_by."'",''));?>
                    <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                  </div>  
                  <?php } ?>    
            </div>
            <!-- from to date -->
          

          </div>
          <!-- /.row -->
        
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageChannels.php"); '>
        <!-- <input name="saveForm" id="saveForm" type="submit" class="btn btn-primary" value="<?php echo ($_REQUEST['action']!='Edit'?'Save':'Edit') ;?>" />
        <a href="manageLinkPackages.php" class="btn btn-warning">Cancel</a>
        <span id="loadMe" style="display: none;color: red;" >Wait Uploading</span> -->
        </div>
		</form>		
      
    </section>
    
    <!-- /.content -->
  </div>
<script type="text/javascript">var dayExtend=0; </script>                                   
<?php include_once("../includes/footer.php")?>  

