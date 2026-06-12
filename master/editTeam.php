<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'view');

/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/



//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;	
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			if($db->num_rows2(selectSql(TBL_TEAM,"WHERE `id` NOT IN('".addslashes(encryptor(decrypt,$_POST['eId']))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes(trim($_POST['name']))."'",''))){
				$_SESSION['errorMsg'] = 'Team already exists in our database.';
			}else{
				checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'add');
				$addSql = "   	INSERT INTO `".TBL_TEAM."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1',
							`id_mst_users_level_1`='".addslashes($_POST['id_mst_users_level_1'])."',
							`id_mst_users_level_2`='".addslashes($_POST['id_mst_users_level_2'])."',
							`id_mst_users_level_3`='".addslashes($_POST['id_mst_users_level_3'])."',
							`id_mst_users_level_4`='".addslashes($_POST['id_mst_users_level_4'])."',
							`id_mst_users_level_5`='".addslashes($_POST['id_mst_users_level_5'])."',
							`ids_mst_users_dsr_reporting`='".implode(',',$_POST['ids_mst_users_dsr_reporting'])."',
							`ids_mst_users_monthly_reporting`='".implode(',',$_POST['ids_mst_users_monthly_reporting'])."' "
							;
				$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
							
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'Team details has been added sucessfully.';
					header("location:manageTeam.php");
					exit;
				}

			}
			
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_TEAM,'update');
			$editSql = "   	UPDATE `".TBL_TEAM."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop_group` = '1',
							`id_mst_users_level_1`='".addslashes($_POST['id_mst_users_level_1'])."',
							`id_mst_users_level_2`='".addslashes($_POST['id_mst_users_level_2'])."',
							`id_mst_users_level_3`='".addslashes($_POST['id_mst_users_level_3'])."',
							`id_mst_users_level_4`='".addslashes($_POST['id_mst_users_level_4'])."',
							`id_mst_users_level_5`='".addslashes($_POST['id_mst_users_level_5'])."',
							`ids_mst_users_dsr_reporting`='".implode(',',$_POST['ids_mst_users_dsr_reporting'])."',
							`ids_mst_users_monthly_reporting`='".implode(',',$_POST['ids_mst_users_monthly_reporting'])."' " 

							;
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Closing Type '.selectColumn(TBL_TEAM,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'").' details has been updated sucessfully.';
				header("location:manageTeam.php?&page=".$_REQUEST['page']);
				
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Team '.selectColumn(TBL_TEAM,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Team details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = " SELECT * FROM `".TBL_TEAM."` WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."' ";

	//echo $sql; exit;
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
	
  <!--  <section class="content-header">
      <h1>
        <span style="color: #f25e74;">  User Master </span>
        <small>Team Master</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Team Master</li>
      </ol>
    </section>  -->
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->name ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error text-center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              
              	<div class="box-body">
              		
	                <div class="form-group  ">
	                  <label for="name">Team Name<font color="#FF0000">*</font></label>
	                  <input type="text" class="form-control" placeholder="Enter Team Name" id="name" name="name" value="<?php if($_POST['name']) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
					<?php echo $err_name;?>
                	</div>

                	<div class="card text-dark bg-light">
			        	<hr>
			            <div class="bg-primary text-center">
			                <h5 style="padding: 5px;">Assign User To Each Levels</h5>
			            </div> 
			            <hr>
			        </div>

			        <div class="row">
			        	<!-- user levels start-->
			        	<div class="form-group col-md-12">
			        		<label for="id_mst_users_level_1"> 
							    Level 1 User <font color="#FF0000">*</font>
							</label> 
							<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_level_1" required="required" style="width: 100%">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_mst_users_level_1'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_users_level_1 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
			        	</div>

			        	<div class="form-group col-md-12">
			        		<label for="id_mst_users_level_2"> 
							    Level 2 User <font color="#FF0000">*</font>
							</label> 
							<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_level_2" required="required" style="width: 100%">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_mst_users_level_2'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_users_level_2 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
			        	</div>

			        	<div class="form-group col-md-12">
			        		<label for="id_mst_users_level_3"> 
							    Level 3 User 
							</label> 
							<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_level_3" style="width: 100%">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_mst_users_level_3'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_users_level_3 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
			        	</div>

			        	<div class="form-group col-md-12">
			        		<label for="id_mst_users_level_4"> 
							    Level 4 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_level_4" style="width: 100%">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_mst_users_level_4'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_users_level_4 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
			        	</div>

			        	<div class="form-group col-md-12">
			        		<label for="id_mst_users_level_5"> 
							    Level 5 User 
							</label> 

		        			<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_level_5" style="width: 100%">
								  	<option value="">Select User </option>';
									  $resUserLevel = selectSql(TBL_USERS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
									    if($db->num_rows2($resUserLevel)){
										  	while($resultUserLevel = $db->fetch_object2($resUserLevel)){
												if($_REQUEST['id_mst_users_level_5'] == $resultUserLevel->id){
													$selected = 'selected="selected"';
												}elseif($row->id_mst_users_level_5 == $resultUserLevel->id){
														$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
											}

									  	}
									echo $categoryDropDown .= '</select>';
		  				    ?>
			        	</div>
			        </div>
	                <!-- user level end -->

	                <div class="card text-dark bg-light">
						<hr>
					    <div class="bg-primary text-center">
					        <h5 style="padding: 5px;">Reporting Section</h5>
					    </div> 
					    <hr>
					</div>

					<div class="row">
						<div class="form-group col-md-12">
							<label for="ids_mst_users_dsr_reporting"> 
							    Dsr Reporting<font color="#FF0000">*</font> 
							</label>
							    <?php  
							    	$sqlUserSql = "SELECT * FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND status='1'  ";

		        			
						 			$sqlUserActions = mysqli_query($connNew,$sqlUserSql);  

				   				?>
				   				<select required="required" class="form-control select2" name="ids_mst_users_dsr_reporting[]" multiple="multiple" style="width: 100%">				  
                  					<?php 
									
										$iCounterActions = 0;
										while($resUserActions = mysqli_fetch_object($sqlUserActions)){
											$chkSql = "SELECT * FROM `".TBL_TEAM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_mst_users_dsr_reporting ) AND id='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

											if($db->num_rows2(executeSql($chkSql)) > 0){
												$selected = 'selected="selected"';
											}else if($_POST['ids_mst_users_dsr_reporting']){
												$selected = 'selected="selected"';
											}													
											else{
												$selected = '';
											}
											echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
											$iCounterActions++;
										}
									?>
								</select>
							</label> 
						</div>

						<div class="form-group col-md-12">
							<label for="ids_mst_users_monthly_reporting"> 
							    Monthly Reporting<font color="#FF0000">*</font> 
							</label> 

		        			<?php  
		        				$sqlUserSql = "SELECT * FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."' AND status='1'  ";
						 		$sqlUserActions = mysqli_query($connNew,$sqlUserSql);  

				   			?>

				   			<select required="required" class="form-control select2" name="ids_mst_users_monthly_reporting[]" multiple="multiple" style="width: 100%">				  
                  				<?php 
									
									$iCounterActions = 0;
									while($resUserActions = mysqli_fetch_object($sqlUserActions)){
										$chkSql = "SELECT * FROM `".TBL_TEAM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_mst_users_monthly_reporting ) AND id='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

										if($db->num_rows2(executeSql($chkSql)) > 0){
											$selected = 'selected="selected"';
										}else if($_POST['ids_mst_users_monthly_reporting']){
											$selected = 'selected="selected"';
										}													
										else{
											$selected = '';
										}
										echo '<option '.$selected.' value="'.$resUserActions->id.'">'.$resUserActions->name.'</option>';
						
										$iCounterActions++;
									}
								?>
							</select>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-12">				
							<div class="form-group">
			                  <label for="status">Status</label>
			                 <input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked /> Active
							 <input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
							 <?php echo $err_status;?>
			                </div>
		            	</div>
					</div>

					<div class="row">
						<?php if($row->date_created){?>
							<div class="form-group col-md-4">
			                	<label for="date_created">Date Created</label>
			                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
			                </div> 
				
							<div class="form-group col-md-4">
                  				<label for="last_modified">Last Updated</label>
                  				<input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                			</div> 
				
							<div class="form-group col-md-4">
                  				<label for="last_modified_by">Last Updated By</label>
				   				<?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
                  				<input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->username);?>">				
                			</div>  
				  
						<?php } ?>
					</div>      
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageTeam.php"); '>
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


