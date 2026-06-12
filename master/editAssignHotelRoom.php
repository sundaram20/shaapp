<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'view');
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){
	header("location:editHotels.php");
}
/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){		
	$err = 0;
	
	if(empty($_POST['id_mst_room_types'])){
		$err++;
		$err_room_id = '<font style="color:red;font-weight:normal;" ><br>Please select room.</font>';
	}
	if(empty($_POST['single_pax_price'])){
		$err++;
		$err_single_pax_price = '<font style="color:red;font-weight:normal;" ><br>Please enter single pax price.</font>';
	}
	if(empty($_POST['double_pax_price'])){
		$err++;
		$err_double_pax_price = '<font style="color:red;font-weight:normal;" ><br>Please enter double pax price.</font>';
	}
	
	if(empty($_POST['inventory'])){
		$err++;
		$err_inventory = '<font style="color:red;font-weight:normal;" ><br>Please enter inventory.</font>';
	}	
	if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
	   //no error
	}else{
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
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['image']['name'].'.</font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Invalid file type '.$_FILES['image']['type'].'. Please use only JEPG,GIF,PNG,BMP only</font>';
			}
		}elseif($_POST['old_image'] == ''){
			$err++;
			$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			$query = "SELECT * From `".TBL_ASSIGN_HOTEL_ROOM."` WHERE id_mst_room_types = '".$_POST['id_mst_room_types']."' AND status_active_date = '".date('Y-m-d' , strtotime($_POST['status_active_date']))."' AND id_mst_hotels = '".encryptor(decrypt,$_REQUEST['eId'])."'";

			//echo $query; exit;

			$resSQL = mysqli_query($connNew, $query);
			$numRows = mysqli_num_rows($resSQL);
			if($numRows>0){
				$_SESSION['errorMsg'] = 'Room Type and status active date are already insert. Please make corrections below. ';
			}else{

				checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'add');
				
				$addSql = "  INSERT INTO `".TBL_ASSIGN_HOTEL_ROOM."` SET 
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`id_mst_hotels` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',
							`single_pax_price` = '".addslashes($_POST['single_pax_price'])."',
							`double_pax_price` = '".addslashes($_POST['double_pax_price'])."',
							`ids_mst_room_amenities` = '".addslashes(implode(',',$_POST['ids_mst_room_amenities']))."',							
							`description` = '".addslashes($_POST['description'])."',
							`online_url` = '".addslashes($_POST['online_url'])."',
							`inventory` = '".addslashes($_POST['inventory'])."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
				if($_FILES['image']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

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

				//echo $addSql; die;
				
$sql1 = executeSql("SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_assign_hotel_rooms',
							`form_code` = 'mst_hotel_room_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);
				
				
				
				
				
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Hotel room assigned details has been added sucessfully.';
					header("location:manageAssignHotelRoom.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Hotel room assigned details has not been saved. Please make corrections below.';
				}

			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'update');
			
			
			
	
 $auditquery = "SELECT * From `".TBL_ASSIGN_HOTEL_ROOM."` WHERE id = '".addslashes(encryptor(decrypt,$_POST['id']))."'  ";

  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	 $idd = $auditrow -> id;
	  $room = $auditrow -> id_mst_room_types;
	  $inven = $auditrow -> inventory; ;
	  $single = $auditrow -> single_pax_price;
	  $double = $auditrow -> double_pax_price;
	  $url = $auditrow -> online_url;
	  $amenities = $auditrow -> ids_mst_room_amenities;
	  $image = $auditrow -> image;
	  $display = $auditrow -> display_order;
	  $stat= $auditrow -> status;
	 
    if($room != $_POST['id_mst_room_types']){
		$old_data = selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$room."'");
		$new_data = selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['id_mst_room_types']."'  ");
		$roomno = "Room Type Details Changed from ". $old_data." - to - " . $new_data;
	}
	if($inven != $_POST['inventory']){
		 $inventry ="inventory Details Changed from " .  $inven." - to - ".$_POST['inventory'];
	}
	if($single != $_POST['single_pax_price']){
		 $singlepax ="Single pax Details Changed from " .   $single ." - to - " . $_POST['single_pax_price'];
	} 
	if($double != $_POST['double_pax_price']){
		$doublepax ="Double Pax Details Details Changed from " .   $double ." - to - " . $_POST['double_pax_price'];
	} 
	if($url != $_POST['online_url']){
		 $online = "Online Url Details Changed from " .  $url ." - to - " . $_POST['online_url'];
	}
	if($display != $_POST['display_order']){
		$dis ="Display Order Details Changed from " .   $display ." - to - " . $_POST['display_order'];
	}
	if($stat != $_POST['status']){
		if($stat == 1){$stat='Active';}else{$stat='Inactive';}
		if( $_POST['status'] == 1){$old_data='Active';}else{$old_data='Inactive';}
		$sta ="Status Details Changed from " .   $stat ." - to - " . $old_data;
	}
	
 }			
			
			
			
			$editSql = "   	UPDATE `".TBL_ASSIGN_HOTEL_ROOM."` SET 
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`id_mst_hotels` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',
							`single_pax_price` = '".addslashes($_POST['single_pax_price'])."',
							`double_pax_price` = '".addslashes($_POST['double_pax_price'])."',
							`description` = '".addslashes($_POST['description'])."',
							`ids_mst_room_amenities` = '".addslashes(implode(',',$_POST['ids_mst_room_amenities']))."',
							`online_url` = '".addslashes($_POST['online_url'])."',
							`inventory` = '".addslashes($_POST['inventory'])."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
			if($_FILES['image']['name'] != ''){
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}

			if($_POST['status'] == "1"){

				$editSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
			}else{
				$editSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
			}
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['id']))."'";				
					
			     $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['id']))."',
							`tables_name` = 'mst_assign_hotel_rooms',
							`form_code` = 'mst_hotel_room_form',
							`changes` =  '".addslashes($roomno).",".addslashes($inventry).",".addslashes($singlepax).",".addslashes($doublepax).",".addslashes($online).",".addslashes($dis).",".addslashes($sta)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
           executeSql($auditeditSql);	
			
			
			if(executeSql($editSql)){
				
				$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor(decrypt,$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['id_mst_room_types']."'").' details has been updated sucessfully.';
				header("location:manageAssignHotelRoom.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor(decrypt,$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['id_mst_room_types']."'").' details has not been saved.Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Add') && $_REQUEST['date'] == 'adddate'){

			if($_POST['status_active_date'] <= $_POST['active_date']){
				$err++;
				$err_status_active = '<font style="color:red;font-weight:normal;" ><br>Date should be greater than Current Date.</font>';
			}
			else if($_POST['active_date'] == $_POST['status_active_date']){
				$err++;
				$err_status_active = '<font style="color:red;font-weight:normal;" ><br>Please change active date.</font>';
			}else{
				checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'add');
				$addSql = "   	INSERT INTO `".TBL_ASSIGN_HOTEL_ROOM."` SET 
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`id_mst_hotels` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."',
							`single_pax_price` = '".addslashes($_POST['single_pax_price'])."',
							`double_pax_price` = '".addslashes($_POST['double_pax_price'])."',
							`ids_mst_room_amenities` = '".addslashes(implode(',',$_POST['ids_mst_room_amenities']))."',							
							`description` = '".addslashes($_POST['description'])."',
							`online_url` = '".addslashes($_POST['online_url'])."',
							`inventory` = '".addslashes($_POST['inventory'])."',
							`display_order` = '".addslashes($_POST['display_order'])."'";
				if($_FILES['image']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

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

				$addAmend = "UPDATE `".TBL_ASSIGN_HOTEL_ROOM."` SET 
					`amend_yn` = '1'
				 WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['id']))."'";

				 // update amend_yn
				 executeSql($addAmend);
				 
				//echo $addSql; die;
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Hotel room assigned details has been added sucessfully.';
					header("location:manageAssignHotelRoom.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Hotel room assigned details has not been saved. Please make corrections below.';
				}
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Hotel room assigned details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['id']) && $_REQUEST['action']=='edit'){
	$sql = "  SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."'";
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
      <h1>
       Hotel Manager
        <small>Assign Room To Hotel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assign Room To Hotel</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
           <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li  ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Overview</a></li>   
              <li ><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
              <li ><a href="editHotelVideoGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Video Gallery</a></li>
			  <li class="active"><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Types</a></li>        
			  
			  <!--<li><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li> 
			  <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>-->
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Room : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST[eId]))."'"); ?> </a></h3>     
			   <a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  
			</div> 
            <!-- /.box-header -->
            <!-- form start -->  			        
			<form name="form1"  method="post" enctype="multipart/form-data" role="form">
                <input type="hidden" value="<?php echo $_REQUEST['id'];?>" name="id" />
				<div class="form-group has-error" align="center">
					<?php if($_SESSION['errorMsg']){?>
						<p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
				</div>
              	<div class="box-body" style="padding-top: 0px;">	
              		<div class="card text-dark bg-light">
                		<div class="bg-primary text-center">
                    		<h5 style="padding: 5px;">Rooms General Details</h5>
                		</div> 
                		<hr>
                	</div>
            		<div class="row">
            		
            			<div class="form-group col-md-4">
              				<label for="id_mst_room_types">Room Type<font color="#FF0000">*</font></label>
             				<?php $categoryDropDown = '<select class="form-control select2" name="id_mst_room_types" id="id_mst_room_types">
										<option value="">Select Room Type</option>';
								$resCat = selectSql(TBL_ROOM_TYPE," where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
								if($db->num_rows2($resCat)){
									while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_room_types'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_room_types == $resultCat->id){
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
            			<div class="form-group col-md-4">
							<label for="inventory">Inventory<font color="#FF0000">*</font></label>
							<input type="text"  class="form-control" placeholder="Enter Inventory" id="room_inventory" name="inventory" value="<?php if($_POST) echo $_POST['inventory'];else echo stripslashes($row->inventory);?>">
							<?php echo $err_inventory;?>
            			</div>
            			<div class="form-group col-md-4">
            				<label for="single_pax_price">Single Pax Price<font color="#FF0000">*</font></label>
              				<input type="text" class="form-control" placeholder="Enter single pax price" id="single_pax_price" name="single_pax_price" value="<?php if($_POST['single_pax_price']) echo $_POST['single_pax_price'];else echo stripslashes($row->single_pax_price);?>">
							<?php echo $err_single_pax_price;?>
            			</div>
            		</div>
            		<div class="row">
            			
            			<div class="form-group col-md-4">
              				<label for="double_pax_price">Double Pax Price<font color="#FF0000">*</font></label>
              				<input type="text" class="form-control" placeholder="Enter double pax price" id="double_pax_price" name="double_pax_price" value="<?php if($_POST['double_pax_price']) echo $_POST['double_pax_price'];else echo stripslashes($row->double_pax_price);?>">
							<?php echo $err_double_pax_price;?>
            			</div>
            			<div class="form-group col-md-4">
                  			<label for="online_url">Online Url</label>
                  			<input type="text" class="form-control" placeholder="Enter online url" id="online_url" name="online_url" value="<?php if( $_POST['online_url']) echo $_POST['online_url'];else echo stripslashes($row->online_url);?>">
							<?php echo $err_online_url;?>
                		</div>

            		</div>
            		<div class="row">
            			<div class="form-group col-md-12">
                 			<label for="description">Description</label>
				   			<textarea class="ckeditor" id="description" name="description" rows="10" cols="80"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>
                  
							<?php echo $err_description;?>
                		</div>
            		</div>

            		<div class="row">
				        <div class="form-group col-sm-12 col-md-12">
				           	<label for="userlevelId">Room Amenities</label>
								<select class="form-control select2" name="ids_mst_room_amenities[]" multiple="multiple" style="width:100%">				  
					                <?php 
										$sqlUserActions = selectSql(TBL_ROOM_AMENITIES," where id_shop='".$_SESSION['shop']."' ",'');
										$iCounterActions = 0;
										while($resUserActions = $db->fetch_object2($sqlUserActions)){
											$chkSql = "SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE FIND_IN_SET('".$resUserActions->id."',ids_mst_room_amenities) and id='".addslashes(encryptor(decrypt,$_REQUEST['id']))."' ";
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
				        </div>
				    </div>
            		

            		<div class="card text-dark bg-light">
                		<div class="bg-primary text-center">
                    		<h5 style="padding: 5px;">Room Image</h5>
                		</div> 
                		<hr>
                	</div>

                	<div class="row">
                		<div class="col-sm-3">
							<div class="form-group">				
						 		<label for="image">Upload Room Image &nbsp;&nbsp;</label>
								<div class="btn btn-default btn-file">
							  		<i class="fa fa-upload"></i> Upload
							 		<input type="file" class="form-control" placeholder="Select Room Image" id="image" name="image" value="<?php if($_POST) echo $_POST['image'];else echo stripslashes($row->image);?>" onchange="readURL(this);">
							 		<input type="hidden" name="old_image" value="<?php echo stripslashes($row->image);?>"/>					 
									<?php echo $err_image;?>
								</div>
								<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>							
								<a href="javascript:void(0);" id="delete" class="btn btn-danger" onClick="removeImage('removeImage.php','<?php echo TBL_ASSIGN_HOTEL_ROOM; ?>','<?php echo $row->id; ?>','image','imageCallback','hotel_room','<?php echo $row->image; ?>');" >Remove</a>
							</div>	
						</div>
						<div class="col-sm-9">													
							<ul class="mailbox-attachments clearfix"> 
								<li id="imageCallback">
									<?php if(@file_exists($image_path.$row->image) && $row->image!=''){ ?>
									<span class="mailbox-attachment-icon has-img">							 
										<img src="<?php echo $image_display_path.$row->image; ?>" alt="Room Image">							  
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
										<img src="../images/no-hotel-image.jpg" alt="Room Image" id="blah">							  
									  </span>			
									  <div class="mailbox-attachment-info">
										<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
											<span class="mailbox-attachment-size">
											   <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
											  <a href="images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
											</span>
									  </div>							
									<?php }?> 
									  
								</li>                
							</ul>			  
					 	</div>		
                	</div>
                	<div class="row">
                		<div class="form-group col-md-12">
		                  	<label for="display_order">Display Order</label>
		                  	<input type="number" class="form-control" placeholder="Enter display order" id="display_order" name="display_order" value="<?php if($_POST) echo $_POST['display_order'];else echo stripslashes($row->display_order);?>">
							<?php echo $err_display_order;?>
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
                  				<input type="text" class="form-control pickerdate" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y',strtotime($row->status_active_date));  ?>"  <?php echo $disabled; ?>/>

								<?php }else{ ?>
								<input type="text" class="form-control pickerdate" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y'); ?>"  />
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
                  				<input type="text" class="form-control pickerdate" name="status_inactive_date" id="status_inactive_date" value="<?php echo date('d-m-Y',strtotime($row->status_inactive_date));  ?>" autocomplete="off"  readonly="readonly" />
								<?php }else{ ?>
								<input type="text" class="form-control pickerdate" name="status_inactive_date" id="status_inactive_date"  autocomplete="off" placeholder="dd-mm-yyyy" />
								<?php } ?>
                  				<!--<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php if($row->status_inactive_date != '0000-00-00')echo date('d-m-Y',strtotime($row->status_inactive_date));?>"placeholder="dd-mm-yyyy" autocomplete="off" /> -->
                  			</div>
                  			
                  		</div>
                	</div>
                	<?php if($row->date_created){?>
                	<div class="row">
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
                	</div>
                	<?php } ?>
				</div>
				<div class="box-footer">
					<input type='submit' value='<?php if($_REQUEST['id']=='') echo 'Add'; else if($_REQUEST['id'] != '' && $_REQUEST['date'] == 'adddate') echo 'Add'; else echo 'Edit'  ?>' class="btn btn-primary" name="Save" >
					&nbsp;&nbsp;&nbsp;&nbsp;
			   		<input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
						
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
	
	function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah').attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
    }
	
</script>


<script type="text/javascript">

	function audittrial(clicked_value){
		//alert(clicked_value);
		//var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var table ='mst_assign_hotel_rooms';
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