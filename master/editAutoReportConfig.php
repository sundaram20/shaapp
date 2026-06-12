<?php include_once("../config/auto_loader.php");

/*if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','add');
else
	checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','edit');

*/
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			 $sql = " SELECT * FROM `app_auto_report_config_details` WHERE `id_shop` = '".addslashes($_SESSION['id_app_shop'])."' AND  `id_auto_report_config` = '".addslashes(trim($_POST['id_auto_report_config']))."' ";
			$Query= mysqli_query($appConnect,$sql);
$numRows= mysqli_num_rows($Query);
			if($numRows == '0'){

				//checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','add');
				$addSql = "   	INSERT INTO `app_auto_report_config_details` SET
							
							`id_auto_report_config` = '".addslashes(trim($_POST['id_auto_report_config']))."',
							`id_shop_group` = '".addslashes($_POST['id_shop_group'])."',
							`to_email` = '".addslashes($_POST['to_email'])."',
							`cc_email` = '".addslashes($_POST['cc_email'])."',
							`id_shop` = '".addslashes($_SESSION['id_app_shop'])."'";

				$addSql .= ",`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_user_modified_by` = '".$_SESSION['userId']."'
							,`id_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
							
				if(mysqli_query($appConnect,$addSql)){
					//unset($_POST);
					$lastInsertId= mysqli_insert_id();
					$_SESSION['successMsg'] = 'New Report details has been added sucessfully.';
					header("location:manageAutoReportConfig.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Report details has not been saved. Please make corrections below.';
				}
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Report Master Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			//checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','update');
			 $editSql = "   	UPDATE `app_auto_report_config_details` SET 
							
							`id_auto_report_config` = '".addslashes(trim($_POST['id_auto_report_config']))."',
							`id_shop_group` = '".addslashes($_POST['id_shop_group'])."',
							`to_email` = '".addslashes($_POST['to_email'])."',
							`cc_email` = '".addslashes($_POST['cc_email'])."',
							`id_shop` = '".addslashes($_SESSION['id_app_shop'])."'";
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(mysqli_query($appConnect,$editSql)){
				$_SESSION['successMsg'] = ' details has been updated sucessfully.';
				header("location:manageAutoReportConfig.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Report details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `app_auto_report_config_details`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['id_app_shop'])."'";
	$Query= mysqli_query($appConnect,$sql);
	
	if(mysqli_num_rows($Query) > 0){
		$row = mysqli_fetch_object($Query); 
		
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
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Report</a></li>  
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->field_value ?> </span> <a></h3>
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
                
				 
				<?php 
				//print_r($_SESSION);
				$resCatshop = mysqli_query($appConnect,"SELECT * FROM app_shops WHERE id='".$_SESSION['id_app_shop']."'");
								if(mysqli_num_rows($resCatshop)){
									$resultCatshop = mysqli_fetch_object($resCatshop);
									$id_shop_group	=$resultCatshop->id_shop_group;
									}?>
                                     <input type="hidden" value="<?php echo $id_shop_group;?>" name="id_shop_group" id="id_shop_group" />
				<div class="form-group">
              				<label for="id_mst_room_types">Report Name<font color="#FF0000">*</font></label>
             				<?php $categoryDropDown = '<select class="form-control select2" name="id_auto_report_config" id="id_auto_report_config">
										<option value="">Select Report Type</option>';
								$resCat = mysqli_query($appConnect,"SELECT * FROM app_auto_report_config");
								if(mysqli_num_rows($resCat)){
									while($resultCat = mysqli_fetch_object($resCat)){
										
										if($row->id_auto_report_config == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								}
								echo $categoryDropDown .= '</select>';
							?>
							<?php echo $err_room_id;?>
            			</div>
                 <div class="form-group">
                  <label for="name">To Email<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-dashcube"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter To Email" id="to_email" name="to_email" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->to_email);?>"  data-parsley-required>
				<?php echo $err_to_email;?></div>
                </div>       
                        
				 <div class="form-group">
                  <label for="name">CC Email </label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter cc Email" id="cc_email" name="cc_email" value="<?php if($_POST) echo $_POST['cc_email'];else echo stripslashes($row->cc_email);?>"  >
				<?php echo $err_cc_email;?></div>
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
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageAutoReportConfig.php"); '>
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


