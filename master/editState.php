<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_STATE,'view');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	
	if(empty($_POST['state_name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			if($db->num_rows2(selectSql(TBL_STATE,"WHERE  `name` = '".addslashes(trim($_POST['state_name']))."'",''))){
				$_SESSION['errorMsg'] = 'State name already exists in our database.';
			}else{

				checkUserLevelPermission($_SESSION['userLevel'],TBL_STATE,'add');
				$addSql = "   	INSERT INTO `".TBL_STATE."` SET

							`name` = '".addslashes(trim($_POST['state_name']))."',
							`id_mst_country_lang` = '".addslashes($_POST['id_mst_country_lang'])."',
							";

				$addSql .= "
							`status` = '".addslashes($_POST['status'])."'";
				if(executeSql($addSql)){
					//unset($_POST);
					$lastInsertId= $db->insert_id();
					$_SESSION['successMsg'] = 'New state details has been added sucessfully.';
					header("location:manageState.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
					exit;
				}

			}

			
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_STATE,'update');
			 $editSql = "   	UPDATE `".TBL_STATE."` SET 
							
							`id_mst_country_lang` = '".addslashes(trim($_POST['name']))."',
							`name` = '".addslashes(trim($_POST['state_name']))."',
							";
			 
			$editSql .= "	`status` = '".addslashes($_POST['status'])."' 
							WHERE `id_state` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_STATE,'name'," WHERE  'id_state' = '".addslashes(encryptor(decrypt,$_POST['eId']))."'").' details has been updated sucessfully.';
				header("location:manageState.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_STATE,'name'," WHERE 'id' = '".addslashes(encryptor(decrypt,$_POST['eId']))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'State details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_STATE."`
								WHERE `id_state` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
	$resultSQL =  mysqli_query($connNew, $sql);
	
	if(mysqli_num_rows($resultSQL) > 0){
		$row = mysqli_fetch_object($resultSQL); 
		
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
	
	
   <!-- <section class="content-header">
      <h1>
       State Manager
        <small>State Grade</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manageState.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">State Grade</li>
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
			   <li class="active" ><a href="#tab_1" data-toggle="tab">State</a></li>  
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->name ?> </span> <a><?php echo selectColumn(TBL_STATE,'name'," WHERE  'id_state' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
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

              	<div class="form-group" id="sgst" name="sgst">
                  <label for="name">Country  <font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-globe"></i> 
					   	</div>
                 <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_country_lang" id="id_mst_country_lang"  data-parsley-required>

							<option value="">Select Country</option>';
						  $resCat = selectSql(TBL_COUNTRY_LANG,"where  id_lang='1' ",' ORDER BY `name`');
						  if($db->num_rows2($resCat)){
						  	while($resultCat = $db->fetch_object2($resCat)){
						  		//echo $resultCat->id_country;
								if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){
									//$selected = 'selected="selected"';
								}
								elseif($row->id_mst_country_lang == $resultCat->id_country){
									$selected = 'selected="selected"';
								}else{
									$selected = "";
								}  
									$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
								 
							}
						  }
						 	echo $categoryDropDown .= '</select>';
						  ?>
					<?php echo $err_country;?></div>
                </div>
                
				 <div class="form-group">
                  <label for="name">State Name<font color="#FF0000">*</font></label>
                   <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-stack-exchange"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter State Name" id="state_name" name="state_name" value="<?php if($_POST) echo $_POST['state_name'];else echo stripslashes($row->name);?>"  data-parsley-required>
				</div>
				<?php echo $err_name;?>
                </div>
				
				

                <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
           				                				
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
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageState.php"); '>
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


