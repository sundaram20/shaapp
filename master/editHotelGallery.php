<?php 
	include_once("../config/auto_loader.php");
	checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_GALLERY,'view');
	$image_path = $UPLOAD_FILES.'/hotel_gallery/';
	$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

	$image_path_room = $UPLOAD_FILES.'/hotel_room/';
	$image_display_path_room = $UPLOAD_FILES_PATH ."/hotel_room/";
//---------------------------------------------------------------------------------------------------------

	if($_REQUEST['eId'] == ''){
		header("location:editHotels.php");
	}

	if($_POST['Save']){

		$err = 0; 
		if($_POST['hotelId']==''){
			$err++;
			$err_hotel_id = '<font style="color:red;font-weight:normal;" >Please select hotel.<br></font>';
		}else if( $_REQUEST['eId'] == ''){	
			if($db->num_rows2(selectSql(TBL_HOTEL_GALLERY,"WHERE `id` NOT IN('".addslashes($_POST['id'])."') AND `id_hotel` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'",''))){
				$err++;
				$err_hotel_id = '<font style="color:red;font-weight:normal;" >Photo Gallery is already assigned for this hotel.<br></font>';
			}
		}

		if(($_FILES['photo']['name'] == '')){
	  	 //no error
		}else{
			if($_FILES['photo']['size']>0 && $_FILES['photo']['size']<1048576){
				if(($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/bmp') || ($_FILES['photo']['type'] == 'image/gif')){
					$unique = rand(00000,99999);
		        	$filename= basename($_FILES['photo']['name']);
		        	$fname = getNameExt($filename);
		        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];		

					if(@move_uploaded_file($_FILES['photo']['tmp_name'],$image_path.$insert_image)){	
					
						resize($insert_image,$image_path, $image_path, $width=350,$height=220,$thumb='medium-');
						resize($insert_image,$image_path, $image_path, $width=200,$height=100,$thumb='small-');	
						//////end resize////////
						if(@file_exists($image_path.$_POST['image']) && ($_POST['image'] != $_FILES['photo']['name'])){
							@unlink($image_path.$_POST['image']);
							@unlink($image_path.'medium-'.$_POST['image']);
							@unlink($image_path.'small-'.$_POST['image']);
							@unlink($image_path.'main-'.$_POST['image']);
						}	
					}else{
						$err++;
						$err_image = '<font style="color:red;font-weight:normal;" >Unable to upload file '.$_FILES['photo']['name'].'.<br></font>';
						}
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" >Invalid file type '.$_FILES['photo']['type'].'. Please use only JEPG,GIF,PNG,BMP only<br></font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
			}
		}

		if($err == 0){//No error
			if(($_POST['id']) > '1'){
				//update
				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_GALLERY,'update');
				$editSql = "   	UPDATE `".TBL_HOTEL_GALLERY."` SET 	
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',						
							`caption` = '".addslashes($_POST['caption'])."',
							`display_order` = '".addslashes($_POST['order_display'])."',
							`image_type` = '2'";
							
				if($_FILES['photo']['name'] != ''){				
					$editSql .= "	,`image` = '".addslashes($insert_image)."'";
				}				
							
				$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['id'])."'";

								
				if(executeSql($editSql)){
					$_SESSION['successMsg'] = 'Gallery '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'").' details has been updated sucessfully.';
					header("location:editHotelGallery.php?&eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Gallery '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'").' details has not been saved.Please make corrections below.';
				}
			}else{//add
				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_GALLERY,'add');
				$addSql = "   	INSERT INTO `".TBL_HOTEL_GALLERY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_hotel` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."',
							`caption` = '".addslashes($_POST['caption'])."',
							`display_order` = '".addslashes($_POST['order_display'])."',
							`image_type` = '2'";
				if($_FILES['photo']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}
				$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
				if(executeSql($addSql)){
					//unset($_POST);
					$_SESSION['successMsg'] = 'New Gallery details has been added sucessfully.';
					header("location:editHotelGallery.php?&eId=".$_POST['hotelId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Gallery has not been saved. Please make corrections below.';
				}
			}
		}else{//Error
			$err++;
			$_SESSION['errorMsg'] = 'Gallery details has not been saved. Please make corrections.';
		}
	}

	if($_POST['SaveGal']){
		$err = 0; 
		if($_POST['hotelId']==''){
			$err++;
			$err_hotel_id = '<font style="color:red;font-weight:normal;" >Please select hotel.<br></font>';
		}else if( $_REQUEST['eId'] == ''){	
			if($db->num_rows2(selectSql(TBL_ROOM_GALLERY,"WHERE `id` NOT IN('".addslashes($_POST['id'])."') AND `id_hotel` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'",''))){
				$err++;
				$err_hotel_id = '<font style="color:red;font-weight:normal;" >Photo Gallery is already assigned for this hotel.<br></font>';
			}
		}
  
  		if(($_FILES['photo']['name'] == '')){
	   		//no error
		}else{
			if($_FILES['photo']['size']>0 && $_FILES['photo']['size']<1048576){
				if(($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/bmp') || ($_FILES['photo']['type'] == 'image/gif')){
					$unique = rand(00000,99999);
		        	$filename= basename($_FILES['photo']['name']);
		        	$fname = getNameExt($filename);
		        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];		

					if(@move_uploaded_file($_FILES['photo']['tmp_name'],$image_path_room.$insert_image)){	
				
						resize($insert_image,$image_path_room, $image_path_room, $width=350,$height=220,$thumb='medium-');
						resize($insert_image,$image_path_room, $image_path_room, $width=200,$height=100,$thumb='small-');	
						//////end resize////////
						if(@file_exists($image_path_room.$_POST['image']) && ($_POST['image'] != $_FILES['photo']['name'])){
							@unlink($image_path_room.$_POST['image']);
							@unlink($image_path_room.'medium-'.$_POST['image']);
							@unlink($image_path_room.'small-'.$_POST['image']);
							@unlink($image_path_room.'main-'.$_POST['image']);
						}	
					}else{
						$err++;
						$err_image = '<font style="color:red;font-weight:normal;" >Unable to upload file '.$_FILES['photo']['name'].'.<br></font>';
					}
				}else{
					$err++;
					$err_image = '<font style="color:red;font-weight:normal;" >Invalid file type '.$_FILES['photo']['type'].'. Please use only JEPG,GIF,PNG,BMP only<br></font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
			}
		}
  
  
		if($err == 0){//No error
			if(($_POST['id']) > '1'){
				//update
				checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOM_GALLERY,'update');
				$editSql = "   	UPDATE `".TBL_ROOM_GALLERY."` SET 	
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',						
							`caption` = '".addslashes($_POST['caption'])."',
							`display_order` = '".addslashes($_POST['order_display'])."',
							`id_room` = '".addslashes($_POST['roomType'])."',
							`image_type` = '2'";
							
				if($_FILES['photo']['name'] != ''){				
					$editSql .= "	,`image` = '".addslashes($insert_image)."'";
				}				
							
				$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes($_POST['id'])."'";

								
				if(executeSql($editSql)){
					$_SESSION['successMsg'] = 'Gallery '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'").' details has been updated sucessfully.';
					header("location:editHotelGallery.php?&eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
				$err++;
					$_SESSION['errorMsg'] = 'Gallery '.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."'").' details has not been saved.Please make corrections below.';
				}
			}else{//add
				checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_GALLERY,'add');
				$addSql = "   	INSERT INTO `".TBL_ROOM_GALLERY."` SET 
							`id_shop_group` = '1',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_hotel` = '".addslashes(encryptor(decrypt,$_REQUEST['hotelId']))."',
							`caption` = '".addslashes($_POST['caption'])."',
							`id_room` = '".addslashes($_POST['roomType'])."',
							`display_order` = '".addslashes($_POST['order_display'])."',
							`image_type` = '2'";
				if($_FILES['photo']['name'] != ''){				
					$addSql .= "	,`image` = '".addslashes($insert_image)."'";
				}
				$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
				if(executeSql($addSql)){
					//unset($_POST);
					$_SESSION['successMsg'] = 'New Gallery details has been added sucessfully.';
					header("location:editHotelGallery.php?&eId=".$_POST['hotelId']."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Gallery has not been saved. Please make corrections below.';
				}
			}
		}else{//Error
			$err++;
			$_SESSION['errorMsg'] = 'Gallery details has not been saved. Please make corrections.';
		}
	}

	// ----------cate---------
	if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
		$sql = "  SELECT * FROM `".TBL_HOTEL_GALLERY."`
									WHERE `id_hotel` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' and id_shop='".$_SESSION['shop']."' and image_type =2 order by display_order";							
		$db->query($sql);
								
	}	

?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<!-- hotel gallery modal--> 
<div class="modal fade" id="adminsform" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<form method="post" role="form"  enctype="multipart/form-data">
      		<input name="id" type="hidden" class="form-control">
      		<input type="hidden" name="formType" value="forHotelGallery">
	  		<input name="image" type="hidden" class="form-control">	  
      		<div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Modal title</h4>
	        </div>
        	<div class="modal-body">
          		<div class="row"> 
		   			<div class="col-xs-12 col-md-12">             
                		<label for="hotelId">Hotel : <?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor(decrypt,$_REQUEST['eId'])."'");   ?> </label>
                		<?php 
							if($_REQUEST['eId'] != ''){ ?>
								<input type="hidden" name="hotelId" value="<?php echo $_REQUEST['eId']; ?>" />
							<?php }else {
					 			$categoryDropDown = '<select  name="hotelId" id="hotelId" >
								<option value="">Select Hotel</option>
								<option value="0">Main Website</option>';
								$resCat = selectSql(TBL_HOTELS,"where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
								if(mysql_num_rows($resCat)){
									while($resultCat = $db->fetch_object2($resCat)){
										if(encryptor(decrypt,$_REQUEST['eId']) == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								}
							echo $categoryDropDown .= '</select>';
							}
						?>
            
            		</div>
		   
					<div class="col-xs-12 col-md-12">
						<div class="form-group">
						<label>Display Title </label>
						<input name="caption" class="form-control">
						</div>
					</div>
		            <div class="col-xs-12 col-md-12">
		              <div class="form-group">
		                <label>Display Order</label>
		                <input type="number" name="order_display" class="form-control">
		              </div>
		            </div> 
		            <div class="col-xs-12 col-md-12">
		              <div class="form-group">
		                <label>Photo <small>(600 x 300)</small></label>
		                <input type="file" name="photo">
		              </div>
		            </div>      

                 
            				  
					<!--<div class="col-xs-12 col-md-4">
					  <label for="date_created">Date Created</label>
					  <input type="text" disabled="disabled" class="form-control" id="date_created" name="date_created">				
					</div> 
				
					<div class="col-xs-12 col-md-4">
					  <label for="last_modified">Last Updated</label>
					  <input type="text" disabled="disabled" class="form-control" id="last_modified" name="last_modified">				
					</div> 
				
					<div class="col-xs-12 col-md-4">
					  <label for="last_modified_by">Last Updated By</label>
					   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
					  <input type="text" value="<?=$sqlUserDetail ?>" disabled="disabled" class="form-control"   >				
					</div>  -->
										
          		</div>
        	</div>
	        <div class="modal-footer">
	         	<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	          	<button type="submit" class="btn btn-primary" name="Save" value="1">Save</button>
	        </div>
      	</div>
    </form>
  </div>
</div>
<!-- hotel gallery End--> 

<!-- room gallery modal--> 
<div class="modal fade" id="galleryform" tabindex="-1" role="dialog" data-backdrop="static" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    	<form method="post" role="form"  enctype="multipart/form-data">
	      	<input name="id" type="hidden" class="form-control">
	     	<input type="hidden" name="formType" value="forRoomGallery">
		  	<input name="image" type="hidden" class="form-control">	  
      		<div class="modal-content">
	        <div class="modal-header">
	          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	          <h4 class="modal-title">Modal title</h4>
	        </div>
        	<div class="modal-body">
          		<div class="row"> 
		   			<div class="col-xs-12 col-md-12">             
                		<label for="hotelId">Hotel : <?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor(decrypt,$_REQUEST['eId'])."'");   ?> </label>
                		<?php 
							if($_REQUEST['eId'] != ''){ ?>
								<input type="hidden" name="hotelId" value="<?php echo $_REQUEST['eId']; ?>" />
							<?php }else {
				 				$categoryDropDown = '<select  name="hotelId" id="hotelId" >
									<option value="">Select Hotel</option>
									<option value="0">Main Website</option>';
									$resCat = selectSql(TBL_HOTELS,"where id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');
									if(mysql_num_rows($resCat)){
										while($resultCat = $db->fetch_object2($resCat)){
											if(encryptor(decrypt,$_REQUEST['eId']) == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
										}
									}
									echo $categoryDropDown .= '</select>';
								}
							?>
            			</div>
		   
		  				<div class="col-xs-12 col-md-12">
              				<div class="form-group">
                				<label>Select Room<span style="color: red;">*</span></label>
                				<select class="form-control" name="roomType" required="required">
                					<option value="">Select Room</option>
                						<?php
                							$fetchRoom ="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM." WHERE id_mst_hotels = ".encryptor(decrypt,$_REQUEST['eId'])." "; 
                							$resFetch = mysqli_query($connNew,$fetchRoom);
					                		while($rowRoom = mysqli_fetch_object($resFetch)){
					                			echo '<option value="'.$rowRoom->id_mst_room_types.'">'.selectColumn(TBL_ROOM_TYPE,'name','where id='.$rowRoom->id_mst_room_types.' ').'</option>';
					                		}
                						?>
                				</select>				
				 
              				</div>
            			</div>
		  
		             
			            <div class="col-xs-12 col-md-12">
			              <div class="form-group">
			                <label>Display Title </label>
			                <input name="caption" class="form-control">
			              </div>
			            </div>
			            <div class="col-xs-12 col-md-12">
			              <div class="form-group">
			                <label>Display Order<span style="color: red;">*</span></label>
			                <input type="number" required="required" min="1" value="1" name="order_display" class="form-control">
			              </div>
			            </div> 
			            <div class="col-xs-12 col-md-12">
			              <div class="form-group">
			                <label>Photo <small>(600 x 300)</small></label>
			                <input type="file" name="photo">
			              </div>
			            </div>      

                 
            				  
						<!--<div class="col-xs-12 col-md-4">
						  <label for="date_created">Date Created</label>
						  <input type="text" disabled="disabled" class="form-control" id="date_created" name="date_created">				
						</div> 
							
						<div class="col-xs-12 col-md-4">
						  <label for="last_modified">Last Updated</label>
						  <input type="text" disabled="disabled" class="form-control" id="last_modified" name="last_modified">				
						</div> 
							
						<div class="col-xs-12 col-md-4">
						  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
						  <input type="text" value="<?=$sqlUserDetail ?>" disabled="disabled" class="form-control"   >				
						</div>  -->
			
										
          			</div>
       			</div>
		        <div class="modal-footer">
		          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		          <button type="submit" class="btn btn-primary" name="SaveGal" value="1">Save</button>
		        </div>
      		</div>
    	</form>
  	</div>
</div>
<!-- room gallery End--> 

<div class="content-wrapper">
	<!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Hotel Manager
        <small>Manage Gallery</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Gallery</li>
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
					   <li  ><a href="editHotels.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Hotel</a></li>   
						<li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Types</a></li>    
		              <li class="active"><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Photo Gallery</a></li> 
		              <li><a href="manageHotelVideoGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Video Gallery</a></li>
					   
					  
					  <!--<li><a href="inventory.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Inventory</a></li> 
					  <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>  -->
		            </ul> 
		            <div class="box-header with-border">
		            	<h3 class="box-title"> Hotel  Gallery : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
					  
					   	<a href="javascript:;" onClick="return addForm('adminsform', 'Add New Photo');" data-toggle="modal" data-target="#adminsform"  class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add Hotel Photo</a>
		            </div>
		            <!-- /.box-header -->

		            <div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					</div>

					<div class="box-body">  
						<div  class="row">
							<div align="center"><?php echo $err_hotel_id; echo $err_image; ?></div>
							<ul class="sortable-list list-unstyled" > 
								<?php if($db->num_rows() > 0){$counter = 1;
						 	  		while($row = $db->fetch_object()){ ?>	 

						 	  		<?php  
										if($row->status == 0){
										 $activeStatus = 'inactive-record';
										 $statusTitle = 'Active';
										 $statusIcon = 'eye-slash';
										 
										} else {
										 $activeStatus = '';
										 $statusTitle = 'Inactive';
										 $statusIcon = 'eye';
										 
										}
										if($row->banner_status==0){
											$bannerTitle = 'Show to banner';
											$bannerIcon = 'window-maximize';
										}
										else{
											$bannerTitle = 'Remove from banner';
											$bannerIcon = 'window-close';
										}

										if($row->gallery_status==0){
											$galleryTitle = 'Show to gallery';
											$galleryIcon = 'file-image-o';
										}
										else{
											$galleryTitle = 'Remove from gallery';
											$galleryIcon = 'file-excel-o';
										}

		                 			?>	

		                 		<div  class="col-xs-12 col-md-4 col-lg-4 <?=$activeStatus?>" id="<?=$row->id?>" >
	                  				<li class="panel panel-default">
					  
						  				<?php if(@file_exists($image_path.'main-'.$row->image)){ ?>
						                    <div class="sortable-heading panel-body" >	<img src="<?=$image_display_path.'main-'.$row->image?>" class="img-responsive" style="height:150px; width:300px;"/>
						                    </div>
						  				<?php }else {?>
										   	<div class="sortable-heading panel-body"><img src="<?=$image_display_path.'medium-'.$row->image?>" class="img-responsive" style="height:150px;width:300px;"/>
											</div>
						  				<?php } ?>

		                    			<div class="panel-footer">    
			                    			<span  title="Display Order" style="font-weight: bold;cursor: pointer;">Display <?=$row->display_order?> &nbsp</span>					
											<a href="javascript:;" onClick="return cropImg('cropImg','<?php echo '../uploaded_files/hotel_gallery/'.$row->image; ?>','<?php echo '../uploaded_files/hotel_gallery/main-'.$row->image; ?>', '0|0|100|100','600x300');" data-toggle="modal" data-target="#cropImg" class="btn btn-sm btn-success"><i data-toggle="tooltip" title="Crop Image"  class="fa fa-crop fa-fw"></i></a> 
								
											<a href="javascript:;"  onclick="return editForm('adminsform', 'Edit Photo','<?=TBL_HOTEL_GALLERY?>', <?=$row->id?>);" data-toggle="modal" data-target="#adminsform" class="btn btn-sm btn-warning"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil fa-fw"></i></a>
								
											<a href="javascript:;" style="background-color:#232323;" onClick="swapBannerStatus(<?=$row->id?>,<?=$row->banner_status?>, '<?=TBL_HOTEL_GALLERY?>', $(this));" class="btn btn-sm btn-primary"><i class="fa fa-<?=$bannerIcon?> fa-fw" data-toggle="tooltip"  title="<?=$bannerTitle?>"></i> </a> 

											<a href="javascript:;"  onClick="swapGalleryStatus(<?=$row->id?>,<?=$row->gallery_status?>, '<?=TBL_HOTEL_GALLERY?>', $(this));" class="btn btn-sm btn-primary"><i class="fa fa-<?=$galleryIcon?> fa-fw" data-toggle="tooltip"  title="<?=$galleryTitle?>"></i> </a> 
								
								 			<a href="javascript:;"  onClick="swapStatus(<?=$row->id?>,<?=$row->status?>, '<?=TBL_HOTEL_GALLERY?>', $(this));" class="btn btn-sm btn-info"><i class="fa fa-<?=$statusIcon?> fa-fw" data-toggle="tooltip"  title="<?=$statusTitle?>"></i> </a> 

											<a href="javascript:;" title="delete" data-toggle="tooltip" onClick="deleteFunction('photo', <?=$row->id?>, '<?=TBL_HOTEL_GALLERY?>','hotel_gallery');" class="btn btn-sm btn-danger"><i class="fa fa-trash fa-fw"></i> </a>
			               				</div>
	                				</li>
	            				</div>	
	            				<?php }  }?>	
							</ul>
						</div>
					</div>	
					<!-- /.box-body -->
		   		</div>
		   		<!-- /.box -->
        	</div>
    	</div>
    	<!-- /.row -->

    	<div class="row">
    		<!-- left column -->
        	<div class="col-md-12">
        		<!-- general form elements -->
		   		<div class="nav-tabs-custom">
		   			<div class="box-header with-border">
		              <h3 class="box-title">Room  Gallery</h3>
					  
					   <a href="javascript:;" onClick="return addForm('galleryform', 'Add New Room Photo');" data-toggle="modal" data-target="#galleryform"  class="btn btn-success pull-right"><i class="fa fa-plus fa-x1"></i> Add Room Photo</a>
		            </div>
		            <!-- /.box-header -->	

		            <div class="box-body" > 
		            	<?php 
						   $roomGalSql="SELECT id_mst_room_types,id_mst_hotels FROM ".TBL_ASSIGN_HOTEL_ROOM." WHERE id_mst_hotels='".encryptor(decrypt,$_REQUEST['eId'])."' ";

						   $resRoomGal = mysqli_query($connNew,$roomGalSql);
						   				   

						   while($rowGal=mysqli_fetch_object($resRoomGal)){
						   	
						   	echo '<div class="box-header with-border">
	              					<h3 class="box-title">'.selectColumn(TBL_ROOM_TYPE,'name','where id='.$rowGal->id_mst_room_types.' ').'</h3>
				  	            </div>';
					   
			        	?>   
			        	<div  class="row">
			        		<div align="center"><?php echo $err_hotel_id; echo $err_image; ?></div>
			        		<ul class="sortable-list list-unstyled" >
			        			<?php
			        				$gallerySql="SELECT * FROM ".TBL_ROOM_GALLERY." WHERE id_room=".$rowGal->id_mst_room_types." AND id_hotel=".$rowGal->id_mst_hotels." ";
					   
					   				$resGallery = mysqli_query($connNew,$gallerySql);

					   				if(mysqli_num_rows($resGallery) > 0){$counter = 1;
									while($rowGallery = mysqli_fetch_object($resGallery)){
								?>	
											 
									<?php  
										if($rowGallery->status == 0){
											$activeStatus = 'inactive-record';
											$statusTitle = 'Active';
											$statusIcon = 'eye-slash';
										 
										} else {
											$activeStatus = '';
										 	$statusTitle = 'Inactive';
										 	$statusIcon = 'eye';
										 
										}
										if($rowGallery->banner_status==0){
											$bannerTitle = 'Show to banner';
											$bannerIcon = 'window-maximize';
										}
										else{
											$bannerTitle = 'Remove from banner';
											$bannerIcon = 'window-close';
										}

										if($rowGallery->gallery_status==0){
											$galleryTitle = 'Show at accomodation';
											$galleryIcon = 'file-image-o';
										}
										else{
											$galleryTitle = 'Remove from accomodation';
											$galleryIcon = 'file-excel-o';
										}

			                 		?>	
			                 		<div  class="col-xs-12 col-md-4 col-lg-4 <?=$activeStatus?>" id="<?=$row->id?>" >
			                 			<li class="panel panel-default">
						  
							  				<?php if(@file_exists($image_path_room.'main-'.$rowGallery->image)){ ?>
			                    				<div class="sortable-heading panel-body" >	
			                    					<img src="<?=$image_display_path_room.'main-'.$rowGallery->image?>" class="img-responsive" style="height:150px; width:300px;"/>
			                    				</div>
							  				<?php }else {?>
											   	<div class="sortable-heading panel-body"><img src="<?=$image_display_path_room.'medium-'.$rowGallery->image?>" class="img-responsive" style="height:150px;width:300px;"/>
												</div>
							  				<?php } ?>

			                    			<div class="panel-footer">    
				                    			<span  title="Display Order" style="font-weight: bold;cursor: pointer;">Display <?=$rowGallery->display_order?> &nbsp</span>					
												<a href="javascript:;" onClick="return cropImg('cropImg','<?php echo '../uploaded_files/hotel_room/'.$rowGallery->image; ?>','<?php echo '../uploaded_files/hotel_room/main-'.$rowGallery->image; ?>', '0|0|100|100','600x300');" data-toggle="modal" data-target="#cropImg" class="btn btn-sm btn-success"><i data-toggle="tooltip" title="Crop Image"  class="fa fa-crop fa-fw"></i></a> 
									
												<a href="javascript:;"  onclick="return editForm('galleryform', 'Edit Photo','<?=TBL_ROOM_GALLERY?>', <?=$rowGallery->id?>);" data-toggle="modal" data-target="#galleryform" class="btn btn-sm btn-warning"><i data-toggle="tooltip" title="Edit" class="fa fa-pencil fa-fw"></i></a>
									
												<a href="javascript:;" style="background-color:#232323;" onClick="swapBannerStatus(<?=$rowGallery->id?>,<?=$rowGallery->banner_status?>, '<?=TBL_ROOM_GALLERY?>', $(this));" class="btn btn-sm btn-primary"><i class="fa fa-<?=$bannerIcon?> fa-fw" data-toggle="tooltip"  title="<?=$bannerTitle?>"></i> </a> 

												<a href="javascript:;"  onClick="swapGalleryStatus(<?=$rowGallery->id?>,<?=$rowGallery->gallery_status?>, '<?=TBL_ROOM_GALLERY?>', $(this));" class="btn btn-sm btn-primary"><i class="fa fa-<?=$galleryIcon?> fa-fw" data-toggle="tooltip"  title="<?=$galleryTitle?>"></i> </a> 
									
									 			<a href="javascript:;"  onClick="swapStatus(<?=$rowGallery->id?>,<?=$rowGallery->status?>, '<?=TBL_ROOM_GALLERY?>', $(this));" class="btn btn-sm btn-info"><i class="fa fa-<?=$statusIcon?> fa-fw" data-toggle="tooltip"  title="<?=$statusTitle?>"></i> </a> 

									 			<a href="javascript:;" title="delete" data-toggle="tooltip" onClick="deleteFunction('photo', <?=$rowGallery->id?>, '<?=TBL_ROOM_GALLERY?>','hotel_gallery');" class="btn btn-sm btn-danger"><i class="fa fa-trash fa-fw"></i> </a>
				               				</div>
		                				</li>
			                 		</div>	
		                 		<?php } } ?>		 
			        		</ul>
			        	</div> 
			        	<?php } ?>   
		            </div>
		   		</div>
        	</div>
    	</div>
    </section>
</div>

<?php include_once("_inc_crop_img.php");
include_once("../includes/footer.php");
?>