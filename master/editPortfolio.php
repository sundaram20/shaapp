<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PORTFOLIO_ACCOUNT,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PORTFOLIO_ACCOUNT,'edit');

$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}
		
	if(empty($_POST['id_mst_users_primary'])){
		$err++;
		$err_primary_user_id = '<font style="color:red;font-weight:normal;" ><br>Please select User.</font>';
	}	
	if(empty($_POST['ids_mst_users_secondary'])){
		$err++;
		$err_secondary_user_id = '<font style="color:red;font-weight:normal;" ><br>Please select User.</font>';
	}	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			if($db->num_rows2(selectSql(TBL_PORTFOLIO_ACCOUNT,"WHERE `id` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."'AND `name` = '".addslashes(trim($_POST['name']))."'",''))){
		
				$_SESSION['errorMsg'] = 'Area name already exists in our database.';
			}else{

				checkUserLevelPermission($_SESSION['userLevel'],TBL_PORTFOLIO_ACCOUNT,'add');
				$addSql = "   	INSERT INTO `".TBL_PORTFOLIO_ACCOUNT."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`description` = '".addslashes($_POST['description'])."',
							`id_shop_group` = '1',
							`id_mst_users_primary` = '".addslashes($_POST['id_mst_users_primary'])."',
							`ids_mst_users_secondary` = '".addslashes(implode(',',$_POST['ids_mst_users_secondary']))."'";

				if($_POST['status'] == "1"){

					$addSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";

				}else{
						$addSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
				$addSql .= "	,`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'";
				
				
$sql1 = executeSql("SELECT * FROM `".TBL_PORTFOLIO_ACCOUNT."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_portfolio_account',
							`form_code` = 'company_manager_portfolio_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);	
				
				
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Portfolio details has been added sucessfully.';
					header("location:managePortfolio.php");
					exit;
				}

			}
			
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_PORTFOLIO_ACCOUNT,'update');
			
$auditquery = "SELECT * From `".TBL_PORTFOLIO_ACCOUNT."` WHERE id = '".addslashes(encryptor(decrypt,$_POST['eId']))."' ";
   $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $idd = $auditrow -> id;
	  $c1  = $auditrow -> name;
	  $c2  = $auditrow -> id_mst_state;
	  $c3  = $auditrow -> description;
	  $c4  = $auditrow -> id_mst_users_primary;
	  $c5  = $auditrow -> ids_mst_users_secondary;
	  $c6  = $auditrow -> status;
 
	  
    if($c1 != $_POST['name']){
		$ch1 = "Name Changed Details from ".  $c1 ." - to - " . $_POST['name'];
	}
	if($c2 != $_POST['id_mst_state']){
		$old_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$c2."'");
		 $new_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$_POST['id_mst_state']."'  ");
		$ch2 ="State Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c3 != $_POST['description']){
		$ch3 ="Area Description Details Changed from " .  $c3." - to - ".$_POST['description'];
	}
	if($c4 != $_POST['id_mst_users_primary']){ 
	   $old_data = selectColumn(TBL_USERS,'user_name'," WHERE `id` = '".$c4."'");
		$new_data = selectColumn(TBL_USERS,'user_name'," WHERE `id` = '".$_POST['id_mst_users_primary']."'  ");
		$ch4 ="Corporate Executive / Primary User Details Changed from " .   $old_data ." - to - " . $new_data;
	} 
	if($c5 != $_POST['ids_mst_users_secondary']){ 
	    $old_data = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$c5."'");
		$new_data = selectColumn(TBL_USERS,'name'," WHERE `id` = '".$_POST['ids_mst_users_secondary']."'  ");
		//$ch5 ="Unit Executive / Secondary User Details Changed from " .   $old_data ." - to - " . $new_data;
	} 
	if($c6 != $_POST['status']){
		if($c6 == 1){$old='Active';}else{$old='Inactive';}
		if( $_POST['status'] == 1){$new='Active';}else{$new='Inactive';}
		$ch6 ="Status Details Changed from " . $old ." - to - " . $new;
	}	
		
	}		
			
			$editSql = "   	UPDATE `".TBL_PORTFOLIO_ACCOUNT."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`description` = '".addslashes($_POST['description'])."',
							`id_shop_group` = '1',
							`id_mst_users_primary` = '".addslashes($_POST['id_mst_users_primary'])."',
							`ids_mst_users_secondary` = '".addslashes(implode(',',$_POST['ids_mst_users_secondary']))."'";

			if($_POST['status'] == "1"){

				$editSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
			}else{
					$editSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
			}

			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'";
			
			$auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."',
							`tables_name` = 'mst_portfolio_account',
							`form_code` = 'company_manager_portfolio_form',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
                  executeSql($auditeditSql);
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Portfolio '.selectColumn(TBL_PORTFOLIO_ACCOUNT,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:managePortfolio.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Portfolio '.selectColumn(TBL_PORTFOLIO_ACCOUNT,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Add') && $_REQUEST['date'] == 'adddate'){
			//echo "status_active date " .$_POST['status_active_date']."<br/>";
			//echo "row active date ".$_POST['active_date']; exit;
			if($_POST['status_active_date'] <= $_POST['active_date']){
				$err++;
				$err_status_active = '<font style="color:red;font-weight:normal;" ><br>Date should be greater than Current Date.</font>';
			}
			else if($_POST['active_date'] == $_POST['status_active_date']){
				$err++;
				$err_status_active = '<font style="color:red;font-weight:normal;" ><br>Please change active date.</font>';
			}else{
				checkUserLevelPermission($_SESSION['userLevel'],TBL_PORTFOLIO_ACCOUNT,'add');
				$addSql = "  INSERT INTO `".TBL_PORTFOLIO_ACCOUNT."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_mst_state` = '".addslashes($_POST['id_mst_state'])."',
							`description` = '".addslashes($_POST['description'])."',
							`id_shop_group` = '1',
							`id_mst_users_primary` = '".addslashes($_POST['id_mst_users_primary'])."',
							`ids_mst_users_secondary` = '".addslashes(implode(',',$_POST['ids_mst_users_secondary']))."'";

				if($_POST['status'] == "1"){

					$addSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
				}else{
					$addSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
				$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";

				$addAmend = "UPDATE `".TBL_PORTFOLIO_ACCOUNT."` SET 
					`amend_yn` = '1'
				 WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'";

				 // update amend_yn
				 executeSql($addAmend);
				 
				//echo $addSql; die;
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Portfolio details has been added sucessfully.';
					header("location:managePortfolio.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Portfolio details has not been saved. Please make corrections below.';
				}
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Portfolio details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_PORTFOLIO_ACCOUNT."`
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
	
  <!--  <section class="content-header">
      <h1>
        Company Manager
        <small>Manage Portfolio</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Portfolio</li>
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
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				 <div class="row">
				 	<div class="form-group col-md-12">
		                <label for="name">Area Name<font color="#FF0000">*</font></label>
		                <input type="text" class="form-control" placeholder="Enter Area Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required>
						<?php echo $err_name;?>
		            </div>
		            <div class="form-group col-md-12">
                  		<label for="description">Area Description</label>
                  		<input type="text" class="form-control" placeholder="Enter Area Description" id="description" name="description" value="<?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?>" >
						<?php echo $err_description;?>
               		</div>
               		<div class="form-group col-md-12">
                  		<label for="name">State<font color="#FF0000">*</font></label>
                   		<?php $categoryStateDropDown = '<select required="required" class="form-control select2" name="id_mst_state">
							<option value="">Select State</option>';
							$resStateCat = selectSql(TBL_STATE," where status='1' AND `id_mst_country_lang` ='110' ",' ORDER BY `name`');
							if($db->num_rows2($resStateCat)){
								while($resultStateCat = $db->fetch_object2($resStateCat)){
									if($_REQUEST['id_mst_state'] == $resultStateCat->id_state){
										$selected = 'selected="selected"';
									}else if($row->id_mst_state == $resultStateCat->id_state){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$categoryStateDropDown .= '<option '.$selected.' value="'.$resultStateCat->id_state.'">'.ucfirst($resultStateCat->name).'</option>';
								}
							}
							echo $categoryStateDropDown .= '</select>';
						?>
                	</div>	

                	<div class="form-group col-md-12">
		                <label for="id_mst_users_primary">Corporate Executive / Primary User<font color="#FF0000">*</font></label>
		                   <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_users_primary">
								<option value="">Select User</option>';
								$resCat = selectSql(TBL_USERS," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
							  	if($db->num_rows2($resCat)){
							  		while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_users_primary'] == $resultCat->id){
											$selected = 'selected="selected"';
										}else if($row->id_mst_users_primary == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->user_name).'</option>';
									}
							 	 }
								echo $categoryDropDown .= '</select>';
							?>
							<?php echo $err_primary_user_id;?>
                	</div>
                	<div class="form-group col-md-12">
		                <label for="name">Unit Executive / Secondary User<font color="#FF0000">*</font></label>
		                <select class="form-control select2" name="ids_mst_users_secondary[]" multiple="multiple" style="width:100%">	
		                	<?php 
								$sqlUserActions = selectSql(TBL_USERS," where id_shop='".$_SESSION['shop']."' ",'');
								$iCounterActions = 0;
								while($resUserActions = $db->fetch_object2($sqlUserActions)){
									$chkSql = "SELECT * FROM `".TBL_PORTFOLIO_ACCOUNT."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_mst_users_secondary ) and id='".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
									if($db->num_rows2(executeSql($chkSql)) > 0){
										$selected = 'selected="selected"';
									}else if($_POST[$selected]){
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
		                  
						<?php echo $err_secondary_user_id;?>
                	</div>
                	
				</div>

				<div class="row">
                		<div class="col-md-4 form-group">
                  			<label for="status">Status</label>
                  			<div class="input-group">
                  				<div class="input-group-addon">
                  					<input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked/> Active
                  				</div>
                  				<?php 
                  					if($_REQUEST['date'] == 'adddate'){
                  						$disabled = '';
                  					}else{
                  						$disabled = 'readonly="readonly"';
                  					}
                  				?>
                  				<?php if($row->status == "1"){ ?>
                  				<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y',strtotime($row->status_active_date));  ?>"  <?php echo $disabled; ?>/>

								<?php }else{ ?>
								<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y'); ?>"  />
								<?php } ?>
								<input type="hidden" name="active_date" value="<?php echo date('d-m-Y',strtotime($row->status_active_date)); ?>" />
                  			</div>
                  			<span><?php echo $err_status_active;?></span>
                  		</div>
                  		<div class="col-md-4 form-group">
                  			<label for="status">&nbsp;</label>
                  			<div class="input-group">
                  				<div class="input-group-addon">
                  					<input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
				 					<?php echo $err_status;?>
                  				</div>
                  				<?php if($row->status == "0"){ ?>
                  				<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php echo date('d-m-Y',strtotime($row->status_inactive_date));  ?>" autocomplete="off"  readonly="readonly" />
								<?php }else{ ?>
								<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date"  autocomplete="off" placeholder="dd-mm-yyyy" />
								<?php } ?>
                  				<!--<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php if($row->status_inactive_date != '0000-00-00')echo date('d-m-Y',strtotime($row->status_inactive_date));?>"placeholder="dd-mm-yyyy" autocomplete="off" /> -->
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
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">				
		                </div>  
				  
				  	<?php } ?> 
				</div>
				           
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?php if($_REQUEST['eId']=='') echo 'Add'; else if($_REQUEST['eId'] != '' && $_REQUEST['date'] == 'adddate') echo 'Add'; else echo 'Edit'  ?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("managePortfolio.php"); '>
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
<?php include_once("../includes/footer.php")?>


<script type="text/javascript">
	function audittrial(clicked_value){
		//alert(clicked_value);
		//var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var table ='mst_portfolio_account';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>