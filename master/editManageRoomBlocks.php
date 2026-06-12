<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_ROOM_BLOCK,'view');

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){
	$err = 0;
	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" ><br>Please enter name.</font>';
	}
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			if($db->num_rows2(selectSql(TBL_HOTEL_ROOM_BLOCK,"WHERE `id` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `name` = '".addslashes(trim($_POST['name']))."'",''))){
				$_SESSION['errorMsg'] = 'Hotel Room Blocks name already exists in our database.';
			}else{

				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_ROOM_BLOCK,'add');
				$addSql = "   	INSERT INTO `".TBL_HOTEL_ROOM_BLOCK."` SET 
								`name` = '".addslashes(trim($_POST['name']))."',
								`id_shop` = '".addslashes($_SESSION['shop'])."',
								`id_shop_group` = '1'";
				$addSql .= "	,`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'";
				
				
				
				$sql1 = executeSql(" SELECT * FROM `".TBL_HOTEL_ROOM_BLOCK."` ORDER BY id DESC LIMIT 1");
		
				 while($row = $db->fetch_object2($sql1)){
					 $idd = $row -> id;
					 if($idd == '0'){
						 $last_id = '1';
					 }else{
						 $last_id =  $idd + 1;
					 } 
					/*	 $resSQL1 = mysqli_query($connNew, $sql1);
						$numRows = mysqli_num_rows($resSQL1);
						 while($row = $db->fetch_object2($sql1)){
						 $idd = $row -> id;
						 if($numRows>0){
							 $last_id = '1';
						 }else{
							 $last_id =  $idd + 1;
						 }
					 }*/
				 }
			
				    $auditaddSql = "  INSERT INTO audit_trail SET
							`voucher_id` = '".$last_id."',
							`tables_name` = 'mst_hotel_room_block',
							`form_code` = 'maange_room_room_blocks_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);
				
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Hotel Room Blocks details has been added sucessfully.';
					header("location:manageRoomBlocks.php");
					exit;
				}
			}	
			
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_ROOM_BLOCK,'update');
		
		
	$auditquery = "SELECT * From `".TBL_HOTEL_ROOM_BLOCK."` WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'  ";

  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	  $idd = $auditrow -> id;
	  $name = $auditrow -> name;
	  $status = $auditrow -> status;
	
    if($name != $_POST['name']){
	 $changename = "Block Name Details Changed from ".  $name ." - to - " . $_POST['name'];
	}
    if($status != $_POST['status']){
		if($status == 1){$status='Active';}else{$status='Inactive';}
		if( $_POST['status'] == 1){$old_data='Active';}else{$old_data='Inactive';}
	   $sta = "Status Details Changed from ".  $status ." - to - " . $old_data;
	}
 }		
		
		
			$editSql = "   	UPDATE `".TBL_HOTEL_ROOM_BLOCK."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_shop_group` = '1'";
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			
			$auditeditSql = "  INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`tables_name` = 'mst_hotel_room_block',
							`form_code` = 'maange_room_room_blocks_form',
							`changes` =  '".addslashes($changename).",".addslashes($sta)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
			//$auditeditSql .= "WHERE `user_id` = '".addslashes(encryptor(decrypt,$_POST['id']))."'";		
			executeSql($auditeditSql);	
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'Hotel Room Blocks '.selectColumn(TBL_HOTEL_ROOM_BLOCK,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageRoomBlocks.php?&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Hotel Room Blocks '.selectColumn(TBL_HOTEL_ROOM_BLOCK,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Hotel Room Blocks details has not been saved.Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_HOTEL_ROOM_BLOCK."`
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



    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Room Blocks</li>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->name ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" role="form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error text-center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
              <div class="box-body">
                
				<div class="row">
					<div class="form-group col-md-12">
	                  	<label for="name">Room Block Name<font color="#FF0000">*</font></label>
	                  	<input type="text" class="form-control" placeholder="EnterBlock Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>">
						<?php echo $err_name;?>
	                </div>

	                <div class="form-group col-md-12">
	                  	<label for="status">Status</label>
	                 	<input type="radio" class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($row->status == 1)echo "checked";}?> value="1" name="status" checked/> Active
					 	<input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($row->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
					 	<?php echo $err_status;?>
	                </div>

	                <?php if($row->date_created){?>
				  
						<div class="form-group col-md-3">
		                  <label for="date_created">Date Created</label>
		                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div> 
						
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$row->id_mst_user_modified_by."'",''));?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">				
		                </div>  
				  
				  <?php } ?>  
				</div>
								
				
				
				          
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageGeneralServices.php"); '>
					<input type='button' value='Audit Trail' class="btn btn-success" onclick="audittrial(this.value);" style="float:right">
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
		var table ='mst_hotel_room_block';
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
