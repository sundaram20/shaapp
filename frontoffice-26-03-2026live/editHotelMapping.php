<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PACKAGE_LINKING,'add');

if($_POST['Save']){

	$err = 0;
	if($db->num_rows2(selectSql(TBL_CHANNEL_MANAGER,"WHERE  `channel_type` = '3'  AND `id` = '".addslashes($_POST['channel_id'])."'",''))){
		$err = 0;
	}else{
	
	if(empty($_POST['booking_engine_id'])){

		$err++;

		$err_booking_engine_id = '<font style="color:red;font-weight:normal;" ><br>Please enter mapping id.</font>';

	}

	if(empty($_POST['hotel_id'])){

		$err++;

		$err_hotel_id = '<font style="color:red;font-weight:normal;" ><br>Please select hotel.</font>';

	}else if($db->num_rows2(selectSql(TBL_HOTEL_MAPPING,"WHERE  `id` NOT IN('".addslashes(encryptor('decrypt',$_POST[eId]))."') and `id_shop` = '".addslashes($_SESSION['shop'])."' AND `hotel_id` = '".addslashes($_POST['hotel_id'])."' AND `channel_id` = '".addslashes($_POST['channel_id'])."'",''))){

		$err++;

		$err_hotel_id = '<font style="color:red;font-weight:normal;" ><br>Hotel all-ready mapped in our database.</font>';

	}	
	}
	if($err == 0){//No error

		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_MAPPING,'add');

			$addSql = "   	INSERT INTO `".TBL_HOTEL_MAPPING."` SET 

							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."',

							`id_shop` = '".addslashes($_POST['shop_id'])."',

							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`channel_user_name`='".trim($_POST['channel_user_name'])."',
							`channel_password`='".trim($_POST['channel_password'])."',
							`auto_sync_inv`='".$_POST['auto_sync_inv']."',
							`auto_sync_rate`='".$_POST['auto_sync_rate']."',
							`bookingflow`='".$_POST['bookingflow']."',
							`id_shop_group` = '1',
							
							`tagged_to` = '".addslashes($_POST['tagged_to'])."',
							`auto_online_alloc` = '".addslashes($_POST['auto_online'])."',
							`notification_email` = '".trim($_POST['notify_id'])."',
							
							`crm_url` = '".addslashes($_POST['crm_url'])."',
							`hotel_id` = '".addslashes($_POST['hotel_id'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'

							,`last_modified` = '".currenDateTime()."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							,`status` = '".addslashes($_POST['status'])."'";
//echo $addSql;die;
			if(executeSql($addSql)){

				unset($_POST);

				$_SESSION['successMsg'] = 'New Mapping details has been added sucessfully.';

				header("location:manageHotelMapping.php");

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'Mapping has not been saved. Please make corrections below.';

			}

		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update

			checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTEL_MAPPING,'update');

			$editSql = "   	UPDATE `".TBL_HOTEL_MAPPING."` SET 

							`booking_engine_id` = '".addslashes($_POST['booking_engine_id'])."',

							`id_shop` = '".addslashes($_POST['shop_id'])."',

							`channel_id` = '".addslashes($_POST['channel_id'])."',
							`channel_user_name`='".trim($_POST['channel_user_name'])."',
							`channel_password`='".trim($_POST['channel_password'])."',
							`bookingflow`='".$_POST['bookingflow']."',
							`id_shop_group` = '1',
							`tagged_to` = '".addslashes($_POST['tagged_to'])."',
							`auto_online_alloc` = '".addslashes($_POST['auto_online'])."',
							`auto_sync_inv`='".$_POST['auto_sync_inv']."',
							`auto_sync_rate`='".$_POST['auto_sync_rate']."',

							`notification_email` = '".trim($_POST['notify_id'])."',
							`crm_url` = '".addslashes($_POST['crm_url'])."',
							`hotel_id` = '".addslashes($_POST['hotel_id'])."'";

			$editSql .= "	,`last_modified` = '".currenDateTime()."'

							,`status` = '".addslashes($_POST['status'])."'

							,`last_modified_by` = '".$_SESSION['userId']."'

							WHERE `id` = '".addslashes(encryptor('decrypt',$_POST[eId]))."'";

			
			if(executeSql($editSql)){

				$_SESSION['successMsg'] = 'Mapping  details has been updated sucessfully.';

				header("location:manageHotelMapping.php?&page=".$_REQUEST['page']);

				exit;

			}else{

				$err++;

				$_SESSION['errorMsg'] = 'Mapping details has not been saved.Please make corrections below.';

			}

		}

	}else{//Error

		$err++;

		$_SESSION['errorMsg'] = 'Mapping details has not been saved.Please make corrections.';

	}

}

// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_HOTEL_MAPPING."`

								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'";

	$db->query($sql);

	if($db->num_rows() > 0){

		$row = $db->fetch_object();
		
		if($db->num_rows2(selectSql(TBL_CHANNEL_MANAGER,"WHERE  `channel_type` = '3'  AND `id` = '".addslashes($row->channel_id)."'",''))){
		$displaydiv2='style="display:none;"';
		
		
		}else{
			$displaydiv1='style="display:none;"';
		}
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
    
    
   <ul class="nav nav-tabs">

			   <li class="active" ><a href="#tab_1" data-toggle="tab">Hotel Mappping</a></li>   

			  <li><a href="manageRoomMapping.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Room Mapping</a></li>  

			  </ul>
    
    
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
		
      
        
        <!-- /.box-header -->
		<form name="form1"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">

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

  <label for="shop_id">Shop<font color="#FF0000">*</font></label>

   <?php $shopDropDown = '<select class="form-control select2" name="shop_id"  id="shop_id" onchange="getHotelMappingPage();">

								<option value="">Select Shop</option>';

							  $resCat = selectSql(TBL_SHOP," where status='1' AND `id` = '".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');

							  if($db->num_rows2($resCat)){

								  while($resultCat = $db->fetch_object2($resCat)){

									if($_REQUEST['shop_id'] == $resultCat->id){

										$selected = 'selected="selected"';

									}else if($row->id_shop == $resultCat->id){

										$selected = 'selected="selected"';

									}else{

										$selected = '';

									}

									$shopDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

								}

							  }

								 echo $shopDropDown .= '</select>';

							  ?>

<?php echo $err_shop_id;?>

</div>



  <div class="form-group">

  <label for="channel_id">Channel<font color="#FF0000">*</font></label>

<?php $channelDropDown = '<select class="form-control select2" name="channel_id" id="channel_id"  onchange="getChannelMappingType(this.value);getHotelMappingPage();">

								<option value="">Select Channel</option>';

							  $resCat = selectSql(TBL_CHANNEL_MANAGER," where status='1'",' ORDER BY `id`');

							  if($db->num_rows2($resCat)){

								  while($resultCat = $db->fetch_object2($resCat)){

									if($_REQUEST['channel_id'] == $resultCat->id){

										$selected = 'selected="selected"';

									}else if($row->channel_id == $resultCat->id){

										$selected = 'selected="selected"';

									}else{

										$selected = '';

									}

									$channelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

								}

							  }

								 echo $channelDropDown .= '</select>';

							  ?>

<?php echo $err_channel_id;?>

</div>



 <div class="form-group">

  <label for="hotel_id">Hotel<?php echo TBL_HOTELS; ?><font color="#FF0000">*</font></label>
<?php


$categoryDropDown = '<select class="form-control select2" name="hotel_id" id="hotel_id">
								<option value="">Select Hotel</option>';

/*if($db->num_rows2(selectSql(TBL_CHANNEL_MANAGER,"WHERE  `channel_type` = '3'  AND `id` = '".addslashes($row->channel_id)."'",''))){
if($row->hotel_id ==0){
$selected4 = 'selected="selected"';
}else{
$selected4 = '';
}
$categoryDropDown  .= '<option '.$selected4.' value="0">All Hotel</option>';
}else{*/

$resCat = selectSql(TBL_HOTELS," where status='1' AND `id_shop` = '".addslashes($_SESSION['shop'])."' ",' ORDER BY `id`');
							  if($db->num_rows2($resCat)){
								  while($resultCat = $db->fetch_object2($resCat)){
									if($_REQUEST['hotel_id'] == $resultCat->id){
										$selected = 'selected="selected"';
									}else if($row->hotel_id == $resultCat->id){
										$selected = 'selected="selected"';
									}else{
										$selected = '';
									}
									$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).', '.ucfirst($resultCat->city).'</option>';
								}
							  }
//}
								 echo $categoryDropDown .= '</select>';
							  ?>
   <?php /*$categoryDropDown = '<select class="form-control select2" name="hotel_id" id="hotel_id">

								<option value="">Select Shop First</option>';											 

								 echo $categoryDropDown .= '</select>';

							  ?>

<?php echo $err_hotel_id;*/?>

</div>

<div id="pms_channel" <?php //echo $displaydiv2; ?>> 
<div class="form-group">
  <label for="channel_user_name">Channel User Name<font color="#FF0000">*</font></label>
  <input  data-parsley-required name="channel_user_name" id="channel_user_name" type="text" value="<?php echo $row->channel_user_name; ?>" class="form-control">
</div>

<div class="form-group">

  <label for="channel_password">Channel Password<font color="#FF0000">*</font></label>
   <input  data-parsley-required name="channel_password"  id="channel_password" type="password" value="<?php echo $row->channel_password; ?>" class="form-control">
</div>    


<div class="form-group">

  <label for="auto_sync_inv">Auto Update Channel Inventory</label>
  <?php
  $auto_sync_active='';
  $auto_sync_inactive='';

  if($row->auto_sync_inv==1)
	  $auto_sync_active='selected="selected"';
  else
	  $auto_sync_inactive='selected="selected"';

  ?>
  <select name="auto_sync_inv" id="" class="form-control select2">
	  <option <?php echo $auto_sync_active;?> value='1'>Yes</option>
	  <option <?php echo $auto_sync_inactive;?> value='0'>No</option>
  </select>
</div>  


<?php /*?><div class="form-group">

  <label for="auto_sync_rate">Auto Update Channel Rate</label>
  <?php
  $auto_sync_rate_active='';
  $auto_sync_rate_inactive='';

  if($row->auto_sync_rate==1)
	  $auto_sync_rate_active='selected="selected"';
  else
	  $auto_sync_rate_inactive='selected="selected"';

  ?>
  <select name="auto_sync_rate" id="auto_sync_rate" class="form-control select2">
	  <option <?php echo $auto_sync_rate_active;?> value='1'>Yes</option>
	  <option <?php echo $auto_sync_rate_inactive;?> value='0'>No</option>
  </select>
</div><?php */?>


<div class="form-group">

  <label for="booking_engine_id">Mapping Id<font color="#FF0000">*</font></label>

  <input type="text" class="form-control" placeholder="Enter mapping Id" id="booking_engine_id" name="booking_engine_id" value="<?php if($_POST) echo $_POST['booking_engine_id'];else echo stripslashes($row->booking_engine_id);?>" data-parsley-required>

<?php echo $err_booking_engine_id;?>

</div>

<div class="form-group">

  <label for="tagged_to">OTA Booking Default Sales Executive<font color="#FF0000">*</font></label>

  <?php $userDropDown = '<select class="form-control select2" name="tagged_to"  id="tagged_to" data-parsley-required>

								<option value="">Select User</option>';

							  $resCat1 = selectSql(TBL_USERS," where status='1' AND id_shop='".$_SESSION['shop']."' ",' ORDER BY `id`');

							  if($db->num_rows2($resCat1)){

								  while($resultCat1 = $db->fetch_object2($resCat1)){

									if($_REQUEST['tagged_to'] == $resultCat1->id){

										$selected = 'selected="selected"';

									}else if($row->tagged_to == $resultCat1->id){

										$selected = 'selected="selected"';

									}else{

										$selected = '';

									}

									$userDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.ucfirst($resultCat1->name).'</option>';

								}

							  }

								 echo $userDropDown .= '</select>';

							  ?>

</div>

<div class="form-group">

  <label for="">Auto Inventory Sold Out Update</label> &nbsp&nbsp

 <!--<input value="1" name="auto_online" type="radio" class="flat-red " <?php if($_POST['auto_online'] == '1'){echo "checked";}else{if($row->auto_online_alloc == 1)echo "checked";}?>   /> Active

 <input  value="0" name="auto_online" type="radio" class="flat-red " <?php if($_POST['auto_online'] == '0'){echo "checked";}else{if($row->auto_online_alloc == 0)echo "checked";}?> /> Inactive-->
 <?php 
	 if($row->auto_online_alloc == 1)
		 $select = 'selected="selected"'; 
	 else
		 $select0 = 'selected="selected"'; 
 ?>

 <select name="auto_online" id="auto_online">
	 <option value="1" <?php echo $select ?> >Yes</option>
	 <option value="0" <?php echo $select0 ?> >No</option>
 </select>

 <?php echo $err_status;?>

</div>	

<div class="form-group notify_id">

  <label for="notify_id">Notification Email<font color="#FF0000">*</font></label>

  <input type="text" class="form-control" placeholder="Enter Email Id(s)" id="notify_id" name="notify_id" value="<?php if($_POST) echo $_POST['notify_id'];else echo stripslashes($row->notification_email);?>" data-parsley-required>

<?php echo $err_booking_engine_id;?>


</div>			
</div>

<div id="crmshow"  <?php echo $displaydiv1; ?>>
<div class="form-group">

  <label for="notify_id">url<font color="#FF0000">*</font></label>

  <input type="text" class="form-control" placeholder="Enter url" id="crm_url" name="crm_url" value="<?php if($_POST) echo $_POST['crm_url'];else echo stripslashes($row->crm_url);?>" data-parsley-required>

<?php echo $err_crm_url;?>


</div>	
</div>

<div class="form-group">
                  <label for="status">Booking Flow  </label>
                 <select  name="bookingflow" id="bookingflow"  class="select2 form-control" style="width:100%">
                  <option  value="">Select Booking Flow </option>
                  	<option  value="0" <?php if($row->bookingflow==0){
							echo "selected='selected'";} ?> >Two way Both sent and received</option>
                  	<option <?php if($row->bookingflow==1){
							echo "selected='selected'";} ?>  value="1">Two way Without crs Modify</option>
                    	           
                  	
                  </select>
				
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

<!-- /.box-body -->	

<div class="box-footer">                                       

<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >

&nbsp;&nbsp;&nbsp;&nbsp;

<input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageHotelMapping.php"); '>

</div>

</form>			
      
    </section>
    
    <!-- /.content -->
  </div>
<script type="text/javascript">var dayExtend=0; </script>                                   
<?php include_once("../includes/footer.php")?>  

