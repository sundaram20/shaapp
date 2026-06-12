<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'edit');

$image_path = $UPLOAD_FILES.'/users/';
$image_display_path = $UPLOAD_FILES_PATH ."/users/";


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	
	$err = 0;
	if($_POST['id_mst_user_levels'] == ''){
		$err++;
		$err_userlevelId = '<font style="color:red;font-weight:normal;">Please select user level.</font>';
	}
	if($_POST['id_shop'] == ''){
		$err++;
		$err_id_shop = '<font style="color:red;font-weight:normal;">Please select shop.</font>';
	}


	if(empty($_POST['name'])){
		$err++;
		$err_name = '<font style="color:red;font-weight:normal;" >Please enter user name.</font>';
	}

	if(empty($_POST['id_mst_attributes_designations'])){
		$err++;
		$$err_designation = '<font style="color:red;font-weight:normal;" >Please select designation.</font>';
	}

	if(empty($_POST['id_mst_team'])){
		$err++;
		$$err_myteam = '<font style="color:red;font-weight:normal;" >Please select team.</font>';
	}

	if(empty($_POST['ids_mst_team'])){
		$err++;
		$$err_myteam_member = '<font style="color:red;font-weight:normal;" >Please select team.</font>';
	}

	if(empty($_POST['user_name'])){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" ><br>Please enter username.</font>';
	}else if($db->num_rows2(selectSql(TBL_USERS,"WHERE `id` NOT IN('".addslashes($_REQUEST['eId'])."') AND `user_name` = '".addslashes(trim($_POST['user_name']))."'",'')) &&$_POST['Save'] == 'Add'){
		$err++;
		$err_username = '<font style="color:red;font-weight:normal;" >Username already exists in our database.</font>';
	}


	//------------------------------
/*	if(empty($_POST['email'])){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Please enter user email.</font>';
	}else if($db->num_rows2(selectSql(TBL_USERS,"WHERE `id` NOT IN('".$_REQUEST['eId']."') AND `email` = '".addslashes($_POST['email'])."'",'')) && $_POST['Save'] == 'Add'){
		$err++;
		$err_email = '<font style="color:red;font-weight:normal;" >Email already exists in our database.</font>';
	}  */

	if(empty($_POST['password'])){
		$err++;
		$err_password = '<font style="color:red;font-weight:normal;" ><br>Please enter password.</font>';
	}
	
	
	if(empty($_POST['zip'])){
		$err++;
		$err_zip = '<br/><font style="color:#FF0000;">Please enter zip code.</font>';
	}else if(!preg_match("/^[0-9]{6}+$/", $_POST['zip'])){
		$err++;
		$err_zip = '<br><font style="color:#FF0000;">Please enter valid zip code.</font>';
	}


	if($_POST['id_mst_state'] == ''){
		$err++;
		$err_location = '<font style="color:red;font-weight:normal;" ><br>Please select State.</font>';
	}

	/*if(empty($_POST['address'])){
		$err++;
		$err_address = '<font style="color:red;font-weight:normal;" ><br>Please enter address.</font>';
	}	*/


	if(empty($_POST['city'])){
		$err++;
		$err_city = '<font style="color:red;font-weight:normal;" ><br>Please Enter City name.</font>';
	}
	
	//---------------------------------
	if($err == 0){//No error
		//echo $err; exit;
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add
			checkUserLevelPermission($_SESSION['userLevel'],TBL_USERS,'add');
			$addSql = "   	INSERT INTO `".TBL_USERS."` SET 
							`id_shop` = '".addslashes($_POST['id_shop'])."',
							`id_shop_group` = '1',
							`id_mst_user_levels` = '".addslashes($_POST['id_mst_user_levels'])."',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`user_name` = '".addslashes(trim($_POST['user_name']))."'";
			$addSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$addSql .= "	,`id_mst_attributes_designations` = '".addslashes($_POST['id_mst_attributes_designations'])."'";
			$addSql .= "	,`other_designation` = '".addslashes($_POST['other_designation'])."'";
			$addSql .= "	,`ids_mst_hotels` = '".addslashes(implode(',',$_POST['ids_mst_hotels']))."'";

			$addSql .= "	,`ids_mst_outlet` = '".addslashes(implode(',',$_POST['ids_mst_outlet']))."'";

			$addSql .= "	,`id_mst_team` = '".addslashes($_POST['id_mst_team'])."'";

			$addSql .= "	,`ids_mst_team` = '".addslashes(implode(',',$_POST['ids_mst_team']))."'";
			
			$addSql .= "	,`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."'";
			$addSql .= "	,`primary_mobile` = '".addslashes($_POST['primary_mobile'])."'";
			$addSql .= "	,`primary_landline` = '".addslashes($_POST['primary_landline'])."'";
			$addSql .= "	,`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."'";
			$addSql .= "	,`secondary_landline` = '".addslashes($_POST['secondary_landline'])."'";
			$addSql .= "	,`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."'";
			$addSql .= "	,`company` = '".addslashes($_POST['company'])."'";
			$addSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$addSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";
			$addSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$addSql .= "	,`id_mst_state` = '".addslashes($_POST['id_mst_state'])."'";
			$addSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";
			$addSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";
			
			$addSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";

			$addSql .= "	,`dsr_num_days` = '".addslashes($_POST['dsr_num_days'])."'";

			$addSql .= "	,`geo_location_interval` = '".addslashes($_POST['geo_location_interval'])."'";

			if($_POST['status'] == "1"){

				$addSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";

			}else{
				$addSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
			}
			
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_created_by`='".$_SESSION['userId']."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			
$sql1 = executeSql("SELECT * FROM `".TBL_USERS."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_users',
							`form_code` = 'User Manager',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);	
			
			
			
			if(executeSql($addSql)){
				$_SESSION['successMsg'] = 'New User details has been added sucessfully.';
				header("location:manageUsers.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User details has not been saved. Please make corrections below.';
			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			//print_r($_POST);exit;
			
 $auditquery = "SELECT * From `".TBL_USERS."` WHERE id =  '".addslashes(encryptor(decrypt,$_POST['eId']))."' ";
   $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $idd = $auditrow -> id;
	  $c1 = $auditrow -> name;
	  $c2 = $auditrow -> user_name;
	  $c3 = base64_decode($auditrow -> password);
	  $c4= $auditrow -> primary_contact_type;
	  $c5= $auditrow -> primary_mobile;
	  $c6= $auditrow -> primary_landline;
	  $c7= $auditrow -> secondary_contact_type;
	  $c8= $auditrow -> secondary_landline;
	  $c9= $auditrow -> secondary_mobile;
	  $c10 = $auditrow -> id_mst_attributes_designations;
	  $c11 = $auditrow -> email;
	  $c12 = $auditrow -> company;
	  $c13 = $auditrow -> address;
	  $c14 = $auditrow -> address2;
	  $c15= $auditrow -> city;
	  $c16= $auditrow -> id_mst_state;
	  $c17= $auditrow -> zip;
	  $c18= $auditrow -> skype;
	  $c19= $auditrow -> comments;
	  $c20= $auditrow -> id_shop;
	  $c21= $auditrow -> id_mst_user_levels;
	  $c22= $auditrow -> id_mst_team;
	  $c23= $auditrow -> dsr_num_days;
	  $c24= $auditrow -> geo_location_interval;
	  $c25= $auditrow -> status; 
	  $c26= $auditrow -> ids_mst_outlet;
	  $c27= $auditrow -> ids_mst_hotels;
 
	  
    if($c1 != $_POST['name']){
		$ch1 = "Name Changed Details from ".  $c1 ." - to - " . $_POST['name'];
	}
	if($c2 != $_POST['user_name']){
		$ch2 ="Username Details Changed from " .  $c2." - to - ".$_POST['user_name'];
	}
	if($c3 != $_POST['password']){
		$ch3 ="Password Details Changed from " .   $c3 ." - to - " . $_POST['password'];
	} 
	if(($c5 != $_POST['primary_mobile']) && ($c6 != $_POST['primary_landline'])){
		if($_POST['primary_contact_type'] == 1)
		{$ch4 ="Primary Mobile Details Changed from " . $c5 ." - to - " . $_POST['primary_mobile'];}else
		{$ch5 ="Primary Landline Details Changed from " . $c6 ." - to - " . $_POST['primary_landline'];}
	} 
	
if($c8 != $_POST['secondary_landline']){
		$ch6 ="Secondary Landline Details Changed from " .   $c8 ." - to - " . $_POST['secondary_landline'];
	}
	if($c9 != $_POST['secondary_mobile']){
		$ch7 ="Secondary Mobile Details Changed from " .   $c9 ." - to - " . $_POST['secondary_mobile'];
	}
	if($c10 != $_POST['id_mst_attributes_designations']){
		$old_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$c10."'");
		 $new_data = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$_POST['id_mst_attributes_designations']."'  ");
		 $ch8 = "Country Details Changed from " .  $old_data ." - to - " . $new_data;
	}
	if($c11 != $_POST['email']){
		$ch9 ="Email Details Changed from " .   $c11 ." - to - " . $_POST['email'];
	}
	
	if($c12 != $_POST['company']){
		 $ch10 ="Company Details Changed from " .   $c12 ." - to - " . $_POST['company'];
	}
	if($c13 != $_POST['address']){
		$ch11 ="Address1 Details Changed from " .   $c13 ." - to - " . $_POST['address'];
	}
	if($c14 != $_POST['address2']){
		$ch12 ="Address2 Details Changed from " .   $c14 ." - to - " . $_POST['address2'];
	}
	if($c19 != $_POST['comments']){
		$ch17 ="About Details Changed from " .   $c19 ." - to - " . $_POST['comments'];
	}
	if($c15 != $_POST['city']){
		$ch13 ="City Details Changed from " .   $c15 ." - to - " . $_POST['city'];
	}
	if($c16 != $_POST['id_mst_state']){
		$old_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$c16."'");
		 $new_data = selectColumn(TBL_STATE,'name'," WHERE `id_state` = '".$_POST['id_mst_state']."'  ");
		$ch14 ="State Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c17 != $_POST['zip']){
		$ch15 ="Zip Details Changed from " .   $c17 ." - to - " . $_POST['zip'];
	}
	  
	if($c18 != $_POST['skype']){
		$ch16 ="Skype Details Changed from " .   $c18 ." - to - " . $_POST['skype'];
	}
	
	if($c20 != $_POST['id_shop']){
	    $old_data = selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$c20."'");
		$new_data = selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$_POST['id_shop']."'  ");
		$ch18 ="Shop Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c21 != $_POST['id_mst_user_levels']){
		$old_data = selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$c21."'");
		$new_data = selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$_POST['id_mst_user_levels']."'  ");
		$ch19 ="User Level Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c22 != $_POST['id_mst_team']){
		$old_data = selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$c22."'");
		$new_data = selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$_POST['id_mst_team']."'  ");
		$ch20 ="My Team Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c23 != $_POST['dsr_num_days']){
		$ch21 ="DSR Back Date Allow Details Changed from " .   $c23 ." - to - " . $_POST['dsr_num_days'];
	}
	
	if($c24 != $_POST['geo_location_interval']){
		$ch22 ="App Goe-Location intervals (min)  Changed from " .   $c24 ." - to - " . $_POST['geo_location_interval'];
	}
	if($c25 != $_POST['status']){
		if($c25 == 1){$old='Active';}else{$old='Inactive';}
		if( $_POST['status'] == 1){$new='Active';}else{$new='Inactive';}
		$ch23 ="Status Details Changed from " .   $old ." - to - " . $new;
	}	
	 if($c26 != $_POST['ids_mst_outlet']){
	    $old_data = selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$c26."'");
		$new_data = selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$_POST['ids_mst_outlet']."'  ");
		$ch24 ="Hotel Access Details Changed from " .   $old_data ." - to - " . $new_data;
	}
	if($c27 != $_POST['ids_mst_hotels']){
		$old_data = selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$c27."'");
		$new_data = selectColumn(TBL_USER_LEVELS,'name'," WHERE `id` = '".$_POST['ids_mst_hotels']."'  ");
		$ch25 ="Outlet Access Details Changed from " .   $old_data ." - to - " . $new_data;
	}
			
	}		
			$editSql = "   	UPDATE `".TBL_USERS."` SET 
							`id_shop` = '".addslashes($_POST['id_shop'])."',
							`id_shop_group` = '1',
			                `id_mst_user_levels` = '".addslashes($_POST['id_mst_user_levels'])."',
							`name` = '".addslashes($_POST['name'])."',
							`email` = '".addslashes($_POST['email'])."',
							`user_name` = '".addslashes(trim($_POST['user_name']))."'";
			$editSql .= "	,`password` = '".base64_encode($_POST['password'])."'";
			$editSql .= "	,`id_mst_attributes_designations` = '".addslashes($_POST['id_mst_attributes_designations'])."'";
			$editSql .= "	,`other_designation` = '".addslashes($_POST['other_designation'])."'";
			$editSql .= "	,`ids_mst_hotels` = '".addslashes(implode(',',$_POST['ids_mst_hotels']))."'";
			
			$editSql .= "	,`ids_mst_outlet` = '".addslashes(implode(',',$_POST['ids_mst_outlet']))."'";

			$editSql .= "	,`id_mst_team` = '".addslashes($_POST['id_mst_team'])."'";

			$editSql .= "	,`ids_mst_team` = '".addslashes(implode(',',$_POST['ids_mst_team']))."'";
			
			$editSql .= "	,`primary_contact_type` = '".addslashes($_POST['primary_contact_type'])."'";
			$editSql .= "	,`primary_mobile` = '".addslashes($_POST['primary_mobile'])."'";
			$editSql .= "	,`primary_landline` = '".addslashes($_POST['primary_landline'])."'";
			$editSql .= "	,`secondary_contact_type` = '".addslashes($_POST['secondary_contact_type'])."'";
			$editSql .= "	,`secondary_landline` = '".addslashes($_POST['secondary_landline'])."'";
			$editSql .= "	,`secondary_mobile` = '".addslashes($_POST['secondary_mobile'])."'";
			$editSql .= "	,`company` = '".addslashes($_POST['company'])."'";
			$editSql .= "	,`address` = '".addslashes($_POST['address'])."'";
			$editSql .= "	,`address2` = '".addslashes($_POST['address2'])."'";
			$editSql .= "	,`city` = '".addslashes($_POST['city'])."'";
			$editSql .= "	,`id_mst_state` = '".addslashes($_POST['id_mst_state'])."'";
			$editSql .= "	,`zip` = '".addslashes($_POST['zip'])."'";
			$editSql .= "	,`skype` = '".addslashes($_POST['skype'])."'";
			
			$editSql .= "	,`comments` = '".addslashes($_POST['comments'])."'";

			$editSql .= "	,`dsr_num_days` = '".addslashes($_POST['dsr_num_days'])."'";

			$editSql .= "	,`geo_location_interval` = '".addslashes($_POST['geo_location_interval'])."'";

			if($_POST['status'] == "1"){

				$editSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
			}else{
				$editSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
			}
			
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`status` = '".addslashes($_POST['status'])."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'";

							//echo $editSql; exit;
			
		    $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."',
							`tables_name` = 'mst_users',
							`form_code` = 'User Manager',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9).",".addslashes($ch10).",".addslashes($ch11).",".addslashes($ch12).",".addslashes($ch13).",".addslashes($ch14).",".addslashes($ch15).",".addslashes($ch16).",".addslashes($ch17).",".addslashes($ch18).",".addslashes($ch19).",".addslashes($ch20).",".addslashes($ch21).",".addslashes($ch22).",".addslashes($ch23)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";

if($ch1=='' && $ch2=='' && $ch3=='' && $ch4=='' && $ch5=='' && $ch6=='' && $ch7=='' && $ch8=='' && $ch9=='' && $ch10=='' && $ch11=='' && $ch12=='' && $ch13=='' && $ch14=='' && $ch15=='' && $ch16=='' && $ch17=='' && $ch18=='' && $ch19=='' && $ch20=='' && $ch21=='' && $ch22=='' && $ch23=='' ){
	
}else{							
	executeSql($auditeditSql);
}					
					
					
					
					
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = 'User details '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."'").' has been updated sucessfully.';
				header("location:manageUsers.php");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'User '.selectColumn(TBL_USERS,'name'," WHERE `id` = '".addslashes($_POST['eId'])."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'User details has not been saved.Please make corrections.';
	}
}

//echo $_REQUEST['eId']; exit;
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
	$sqlUserDetail = "  SELECT * FROM `".TBL_USERS."`
						WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'";
	$db->query($sqlUserDetail);
	if($db->num_rows() > 0){
		$rowUserDetail = $db->fetch_object();
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
    <h1>  <span style="color: #f25e74;"> User Manager </span> <small>Manage Users</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="manageUsers.php">User Manager</a></li>
      <li class="active">Manage Users</li>
    </ol>
  </section> -->
  
  
  <!-- Main content -->
  <section class="content">
  
  
  	
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
  
  <div class="row">
    <!-- left column -->
    <div class="col-md-12">
    <!-- general form elements -->
    <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $rowUserDetail->name ?> </span>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <form name="form1"  method="post" enctype="multipart/form-data" role="form">
      <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
      <input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="userid" id="userid" />
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
                <h5 style="padding: 5px;">Users Level Details</h5>
            </div> 
            <hr>
        </div>
      	
    <div class="row">
    	<div class="form-group col-md-4">
        	<label for="name">Name<font color="#FF0000">*</font></label>
        	<input type="text" class="form-control" placeholder="Enter Name" id="name" name="name" value="<?php if($_POST['name']) echo $_POST['name'];else echo stripslashes($rowUserDetail->name);?>" data-parsley-required>
        	<span><?php echo $err_name;?></span>
        </div>
     	<div class="form-group col-md-4">
        	<label for="user_name">Username<font color="#FF0000">*</font></label>
        	<input type="text" class="form-control" placeholder="Enter Username" id="user_name" name="user_name" value="<?php if($_POST['user_name']) echo $_POST['user_name'];else echo stripslashes($rowUserDetail->user_name);?>" data-parsley-required>
        	<span><?php echo $err_username;?></span> 
        </div>

        <div class="form-group col-md-4">
        	<label for="password">Password<font color="#FF0000">*</font></label>
        	<input type="password" class="form-control" placeholder="Enter Password" id="password" name="password"  value="<?php if($_POST) echo $_POST['password'];else echo stripslashes(base64_decode($rowUserDetail->password));?>" data-parsley-required>
        	<span><?php echo $err_password;?></span>
        </div>
      	
      
    </div>
    
    <div class="row">   
        <div class="form-group col-sm-2">
	          <label for="primary_contact_type">Primary contact<font color="#FF0000">*</font></label>
	          <select name="primary_contact_type" id="primary_contact_type" class="form-control select2" style="width: 100%" data-parsley-errors-container="#primary_contactError" data-parsley-required>
	            <?php if($rowUserDetail->primary_contact_type == 1){?>
	                  <option value="1" selected="selected">Mobile</option>
	                  <option value="2">Landline</option>
	              <?php }else if($rowUserDetail->primary_contact_type == 2){ ?>
	                <option value="2" selected="selected">Landline</option>
	                <option value="1">Mobile</option>
	              <?php }else{ ?>
	              <option value="1" selected="selected">Mobile</option>
	              <option value="2">Landline</option>
	            <?php } ?>  
	          </select>
	          <span id="primary_contactError"></span>
	        </div>
	        <div id="primaryContactDiv">
	          <?php if($rowUserDetail->primary_contact_type == 1){ ?>
	          <div class="form-group col-md-2 col-sm-2">
	            <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
	            </label>
	            <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $rowUserDetail->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
	            <span id="primary_mobileError"><?php echo $err_primary_mobile;?></span>
	          </div>
	          <?php }else if($rowUserDetail->primary_contact_type == 2){?>
	          <div class="form-group col-md-2 col-sm-2">
	            <label for="primary_landline">Landline<font color="#FF0000">*</font>
	            </label>
	            <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $rowUserDetail->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" data-parsley-type="digits"  data-parsley-required />
	            <span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span>
	          </div>
	          <?php }else{ ?>
	          <div class="form-group col-md-2 col-sm-2">
	            <label for="primary_mobile">Mobile<font color="#FF0000">*</font>
	            </label>
	            <input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $rowUserDetail->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required />
	            <span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span>
	          </div>
	        <?php } ?>
	        </div>
	        <div class="form-group col-md-2 col-sm-2">
	          <label for="secondary_contact_type">Secondary contact</label>
	          <select name="secondary_contact_type" id="secondary_contact_type" class="form-control select2" style="width: 100%">
	            <?php if($rowUserDetail->secondary_contact_type == 1){?>
	              <option value="1" selected="selected">Landline</option>
	              <option value="2">Mobile</option>
	            <?php }else if($rowUserDetail->secondary_contact_type == 2){?>
	              <option value="2" selected="selected">Mobile</option>
	              <option value="1">Landline</option>
	            <?php }else{ ?>
	              <option value="1" selected="selected">Landline</option>
	              <option value="2">Mobile</option>
	           <?php } ?>
	          </select>
	        </div>
	        <div id="secondaryContactDiv">
	          <?php if($rowUserDetail->secondary_contact_type == 1){ ?>
	            <div class="form-group col-md-2 col-sm-2">
	              <label for="secondary_landline">Landline</label>
	              <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $rowUserDetail->secondary_landline;?>"data-parsley-type="digits" />
	            </div>
	          <?php }else if($rowUserDetail->secondary_contact_type == 2){ ?>
	            <div class="form-group col-md-2 col-sm-2">
	              <label for="secondary_mobile">Mobile</label>
	              <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $rowUserDetail->secondary_mobile;?>"data-parsley-type="digits" />
	            </div>
	          <?php }else{ ?>
	            <div class="form-group col-md-2 col-sm-2">
	              <label for="secondary_landline">Landline</label>
	              <input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $rowUserDetail->secondary_landline;?>"data-parsley-type="digits" />
	            </div>
	          <?php } ?>
    		</div>
    		<div class="form-group col-sm-2">
                  <label for="id_mst_attributes_designations">Designation<font color="#FF0000">*</font></label>
                 <?php
                 $desSql = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name='designations' AND status=1 AND id_shop='".$_SESSION['shop']."' ";

                 $resCat = mysqli_query($connNew,$desSql); 
                 $categoryDropDown = '<select class="form-control select2" style="width:100%" name="id_mst_attributes_designations" data-parsley-errors-container="#designationError" data-parsley-required id="id_mst_attributes_designations">
						<option value="">Select Designation</option>';
						  
							if(mysqli_num_rows($resCat)>0){
						  	while($resultCat = mysqli_fetch_object($resCat)){
								if($_REQUEST['id_mst_attributes_designations'] == $resultCat->id){
									$selected = 'selected="selected"';
								}elseif($rowUserDetail->id_mst_attributes_designations == $resultCat->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
								}
							 }

							 if($rowUserDetail->id_mst_attributes_designations == 100){
							 	$categoryDropDown .= '<option value="100" selected="selected">Other</option>';
							 }else{
							 	$categoryDropDown .= '<option value="100">Other</option>';
							 }

				echo $categoryDropDown .= '</select>';
											  ?>
				<span id="designationError"><?php echo $err_designation;?></span>
                </div>
            	<div id="otherDesignationDiv">
            		<?php if($rowUserDetail->id_mst_attributes_designations == 100){ ?>
            			<div class="form-group col-sm-2">
            				<label col="other_designation">Other Designation<font color="#FF0000">*</font></label><input type="text" name="other_designation" id="other_designation" class="form-control" placeholder="Enter Designation Name"  value="<?php if($_POST) echo $_POST['other_designation']; else echo $rowUserDetail->other_designation;?>" data-parsley-errors-container="#other_designationError" data-parsley-required />
            				<span id="other_designationError"></span>
            			</div>
            		<?php } ?>
		   		</div>
    	</div> 
    	<div class="row">
    		<div class="form-group col-md-4">
        		<label for="email">Email</label>
        		<input type="email" class="form-control" placeholder="Enter Email" id="email" name="email" value="<?php if($_POST['email']) echo $_POST['email'];else echo stripslashes($rowUserDetail->email);?>" data-parsley-required>
        		<span><?php echo $err_email;?></span>
        	</div>
    		<div class="form-group col-md-4">
        		<label for="company">Company</label>
        		<input type="text" class="form-control" placeholder="Enter company" id="company" name="company" value="<?php if($_POST['company']) echo $_POST['company'];else echo stripslashes($rowUserDetail->company);?>" data-parsley-required>
        		<span><?php echo $err_company;?> </span>
        	</div>
        	<div class="form-group col-md-4">
        		<label for="address">Address 1</label>
				<input type="text" class="form-control" placeholder="Enter address" id="address" name="address" value="<?php if($_POST['address']) echo $_POST['address'];else echo stripslashes($rowUserDetail->address);?>" data-parsley-required>
        		
        		<span><?php echo $err_address;?></span>
        	</div>
      		
    	</div>
    	<div class="row">
    		<div class="form-group col-md-4">
        		<label for="address2">Address 2</label>
				<input type="text" class="form-control" placeholder="Enter address2" id="address2" name="address2" value="<?php if($_POST['address2']) echo $_POST['address2'];else echo stripslashes($rowUserDetail->address2);?>" data-parsley-required>
        		
				</textarea>
				
			</div>
    		<div class="form-group col-md-4">
        		<label for="city">City<font color="#FF0000">*</font></label>
        		<input type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?php if($_POST['city']) echo $_POST['city'];else echo stripslashes($rowUserDetail->city);?>" data-parsley-required>
        		<span><?php echo $err_city;?></span>
        	</div>
        	<div class="form-group col-md-4">
		        <label for="id_mst_state">State<font color="#FF0000">*</font></label>
		        <?php $categoryDropDown = '<select class="form-control" name="id_mst_state" id="id_mst_state" data-parsley-required>
					<option value="">Select State</option>';
					$resLocation = selectSql(TBL_STATE," WHERE `status` = '1' AND `id_mst_country_lang` ='110'",' ORDER BY `name`');
					if($db->num_rows2($resLocation)){
						while($resultLocation = $db->fetch_object2($resLocation)){
							if($_REQUEST['id_mst_state'] == $resultLocation->id_state){
								$selected = 'selected="selected"';
							}elseif($rowUserDetail->id_mst_state == $resultLocation->id_state){
								$selected = 'selected="selected"';
							}else{
								$selected = '';
							}
							$categoryDropDown .= '<option '.$selected.' value="'.$resultLocation->id_state.'">'.ucfirst($resultLocation->name).'</option>';
							}
					}
					echo $categoryDropDown .= '</select>';
				?>
        		<span><?php echo $err_location;?> </span>
        	</div>

    	</div>
    	<div class="row">

    		<div class="form-group col-md-4">
        		<label for="zip">Zip Code <font color="#FF0000">*</font></label>
        		<input type="text" class="form-control" placeholder="Enter Zip Code" id="zip" name="zip" value="<?php if($_POST['zip']) echo $_POST['zip'];else echo stripslashes($rowUserDetail->zip);?>" >
        		<span><?php echo $err_zip;?></span>
        	</div>
    		<div class="form-group col-md-4">
        		<label for="skype">Skype</label>
        		<input type="text" class="form-control" placeholder="Enter Skype id" id="skype" name="skype" value="<?php if($_POST['skype']) echo $_POST['skype'];else echo stripslashes($rowUserDetail->skype);?>">
        		
        	</div>
        	<div class="form-group col-md-4">
        		<label for="comments">About</label>
				<input type="text" class="form-control" placeholder="Enter comments" id="comments" name="comments" value="<?php if($_POST['comments']) echo $_POST['comments'];else echo stripslashes($rowUserDetail->comments);?>" data-parsley-required>
		        
				</textarea>
        	</div>
    	</div>
    	<div class="card text-dark bg-light">
    		<hr/>
            <div class="bg-primary text-center">
                <h5 style="padding: 5px;">Access Controls</h5>
            </div> 
            <hr>
        </div>

        <div class="row">
      		<div class="form-group col-md-6">
      			<label for="id_shop">Shop<font color="#FF0000">*</font></label>
      			<select class="form-control select2" name="id_shop" onchange="getHotel(this.value,'<?php echo $rowUserDetail->ids_mst_hotels; ?>','<?php echo $rowUserDetail->id; ?>');" style="width:100%">
        			<?php $shopDropDown = '<option value="">Select shop</option>';
						$resUserShop = selectSql(TBL_SHOP," WHERE `id` = '".addslashes($_SESSION['shop'])."' and  `status` = '1'",' ORDER BY `name`');
						if($db->num_rows2($resUserShop)){
							while($resultUserShop = $db->fetch_object2($resUserShop)){
								if($_REQUEST['id_shop'] == $resultUserShop->id){
									$selected = 'selected="selected"';
								}elseif($rowUserDetail->id_shop == $resultUserShop->id){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$shopDropDown .= '<option '.$selected.' value="'.$resultUserShop->id.'">'.ucfirst($resultUserShop->name).'</option>';
								}
							}
						echo $shopDropDown .= '</select>';
					?>
        		<?php echo $err_id_shop;?>
        	</div> 
        	<div class="form-group col-md-6"> 
		        <label for="userlevelId">User Level<font color="#FF0000">*</font></label> 
		        <?php
		        	$categoryDropDown = '<select class="form-control select2" name="id_mst_user_levels" style="width:100%" data-parsley-required>
					<option value="">Select User Level</option>
													  	';
					$resUserLevel = selectSql(TBL_USER_LEVELS," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
					if($db->num_rows2($resUserLevel)){
						while($resultUserLevel = $db->fetch_object2($resUserLevel)){
							if($_REQUEST['id_mst_user_levels'] == $resultUserLevel->id){
								$selected = 'selected="selected"';
							}elseif($rowUserDetail->id_mst_user_levels == $resultUserLevel->id){
								$selected = 'selected="selected"';
							}else{
								$selected = '';
							}
							$categoryDropDown .= '<option '.$selected.' value="'.$resultUserLevel->id.'">'.ucfirst($resultUserLevel->name).'</option>';
						}
					}
					echo $categoryDropDown .= '</select>';
				?>
		        <span><?php echo $err_userlevelId;?></span>
		    </div>
      	</div>
      	<div class="row">
      		<!-- <div class="form-group col-md-12"> 
      				<label for="ids_mst_hotels">Hotel Access </label> 
      				<select class="form-control select2" name="ids_mst_hotels[]" multiple="multiple" id="ids_mst_hotels" placeholder="Select Hotel" style="width:100%"> 
      				</select>
      				<p class="help-block">&nbsp;Leave Empty for all hotels.</p>
      				<?php echo $err_hotel_access;?>
      		</div> -->

		    <div class="form-group col-md-12"> 
		        <label for="ids_mst_hotels">Hotel Access </label> 

		        <?php
	        		$hotelSql = "SELECT * FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."'  ";
	        		$resHotel = mysqli_query($connNew,$hotelSql);

	        	?>
		        <select class="form-control select2" name="ids_mst_hotels[]" multiple="multiple" id="ids_mst_hotels" placeholder="Select Hotel" style="width:100%"> 
		        	<?php 
	       				while($rowHotel = mysqli_fetch_object($resHotel)) { 
	       					
	       					if(in_array($rowHotel->id, explode(',',$rowUserDetail->ids_mst_hotels)))
	       						$selected="selected='selected'";
	       					else
	       						$selected="";	
	       			?>
						<option <?php echo $selected; ?> value="<?php echo $rowHotel->id ;?>"><?php echo $rowHotel->name; ?></option>
	       			<?php } ?>
		      	</select>
		      	<p class="help-block">&nbsp;Leave Empty for all hotels.</p>
		      	<?php echo $err_hotel_access;?>
		    </div>
		    <!--outlet-->
			<div class="form-group col-md-12"> 
	        	<label for="outlet_access">Outlet Access</label> 
	        	<?php
	        		$outletSql = "SELECT * FROM ".TBL_OUTLETS." WHERE id_shop='".$_SESSION['shop']."'  ";
	        		$resOutlet = mysqli_query($connNew,$outletSql);

	        	?>
	       		<select class="form-control select2" name="ids_mst_outlet[]" multiple="multiple" id="ids_mst_outlet" placeholder="Select Outlet" style="width: 100%"> 
	       			<?php 
	       				while($rowOutlet = mysqli_fetch_object($resOutlet)) { 
	       					
	       					if(in_array($rowOutlet->id, explode(',',$rowUserDetail->ids_mst_outlet)))
	       						$selected="selected='selected'";
	       					else
	       						$selected="";	
	       			?>
						<option <?php echo $selected; ?> value="<?php echo $rowOutlet->id ;?>"><?php echo $rowOutlet->name; ?></option>
	       			<?php } ?>	
	      		</select>
	      		<p class="help-block">&nbsp;Leave Empty for all outlets.</p>
	    	</div> 
			<!--outlet end-->	

			<div class="form-group col-md-12">

				<label for="id_mst_team">My Team <font color="#FF0000">*</font></label>

				<?php 
				//echo $_SESSION['shop'];
					$teamDropDown = '<select class="form-control select2" name="id_mst_team" id="id_mst_team" data-parsley-errors-container="#teamError" data-parsley-required style="width:100%">
					<option value="">---Select Your Team---</option>';
					$resTeam = selectSql(TBL_TEAM," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and`status` = '1'",' ORDER BY `name`');
					if($db->num_rows2($resTeam)){
						while($resultTeam = $db->fetch_object2($resTeam)){
							if($_REQUEST['id_mst_team'] == $resultTeam->id){
								$selected="selected='selected'";	
							}elseif($rowUserDetail->id_mst_team == $resultTeam->id){
								$selected = 'selected="selected"';
							}else{
								$selected="";
							}
							$teamDropDown .= '<option '.$selected.' value="'.$resultTeam->id.'">'.ucfirst($resultTeam->name).'</option>';
						}
					}
					echo $teamDropDown .= '</select>';
				?>
				<span id="teamError"><?php echo $err_myteam ?></span>
			</div>

			<div class="form-group col-md-12">
				<label for="ids_mst_team">Member Of Team(s) <font color="#FF0000">*</font></label>
				<?php 
					$teamSql = "SELECT * from ".TBL_TEAM." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY `name`";
					$resTeamSQl = mysqli_query($connNew,$teamSql); 
				?>
				<select class="form-control select2" name="ids_mst_team[]" id="ids_mst_team" multiple="multiple" data-parsley-errors-container="#team_memeberError" data-parsley-required>
				<?php
					if(mysqli_num_rows($resTeamSQl)){
						while ($rowTeamSql = mysqli_fetch_object($resTeamSQl)) {
							if(in_array($rowTeamSql->id, explode(',',$rowUserDetail->ids_mst_team)))
	       						$selected="selected='selected'";
	       					else
	       						$selected="";
	       					?>
							<option <?php echo $selected; ?> value="<?php echo $rowTeamSql->id ;?>"><?php echo $rowTeamSql->name; ?></option>
				<?php } } ?>
					
				</select>
				<span id="team_memeberError"><?php echo $err_myteam_member ?></span>
			</div>
    	</div>

        <div class="card text-dark bg-light">
        	<hr>
            <div class="bg-primary text-center">
                <h5 style="padding: 5px;">Additional Setting</h5>
            </div> 
            <hr>
        </div>

        <div class="row">
        	<div class="form-group col-md-6">
        		<label for="dsr_num_days"> DSR Back Date Allow.</label>

                  <input type="number" class="form-control" placeholder="Enter display order" id="dsr_num_days" name="dsr_num_days" value="<?php if($_POST['dsr_num_days']) echo $_POST['dsr_num_days']; else echo $rowUserDetail->dsr_num_days;?>">

        	</div>

        	<div class="form-group col-md-6">
        		<label for="geo_location_interval">App Goe-Location intervals (min)</label>
        		<input type="number" class="form-control" placeholder="Enter display order" id="geo_location_interval" name="geo_location_interval" value="<?php if($_POST['geo_location_interval']) echo $_POST['geo_location_interval']; else echo $rowUserDetail->geo_location_interval;?>" />
        	</div>
        </div>


    	<div class="row">
			<div class="col-md-4 form-group">
      			<label for="status">Status</label>
      			<div class="input-group">
      				<div class="input-group-addon">
      					<input type="radio"  class="flat-red" <?php if($_POST['status'] == '1'){echo "checked";}else{if($rowUserDetail->status == 1)echo "checked";}?> value="1" name="status" checked/> Active
      				</div>
      				<?php if($rowUserDetail->status == "1"){ ?>
      				<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y',strtotime($rowUserDetail->status_active_date));  ?>" autocomplete="off" readonly="readonly"/>
					<?php }else{ ?>
					<input type="text" class="form-control datepicker" name="status_active_date" id="status_active_date" value="<?php echo date('d-m-Y'); ?>"  />
					<?php } ?>
      			</div>	
				</div>
				<div class="col-md-4 form-group">
					<label for="status">&nbsp;</label>
					<div class="input-group">
          			<div class="input-group-addon">
          				<input type="radio" class="flat-red" <?php if($_POST['status'] == '0'){echo "checked";}else{if($rowUserDetail->status == "0")echo "checked";}?> value="0" name="status"/> Inactive
		 					<?php echo $err_status;?>
          			</div>
						<?php if($rowUserDetail->status == "0"){ ?>
						<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php echo date('d-m-Y',strtotime($rowUserDetail->status_inactive_date));  ?>" autocomplete="off"  readonly="readonly"/>
					<?php }else{ ?>
					<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date"  autocomplete="off" placeholder="dd-mm-yyyy" />
					<?php } ?>
						<!--<input type="text" class="form-control datepicker" name="status_inactive_date" id="status_inactive_date" value="<?php if($row->status_active_date != '0000-00-00')echo date('d-m-Y',strtotime($row->status_active_date));?>"placeholder="dd-mm-yyyy" autocomplete="off" /> -->
					</div>
			
				</div>
        </div>
        <div class="row">
		    <?php if($rowUserDetail->date_created){?>
		    <div class="form-group col-md-4">
		        <label for="date_created">Date Created</label>
		        <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($rowUserDetail->date_created));?>">
		    </div>
      		<div class="form-group col-md-4">
        		<label for="last_modified">Last Updated</label>
        		<input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($rowUserDetail->last_modified));?>">
      		</div>
      		<div class="form-group col-md-4">
        		<label for="last_modified_by">Last Updated By</label>
        		<?php $sqlUserDetail = $db->fetch_obj2(selectSql(TBL_USERS,"WHERE `id` = '".$rowUserDetail->id_mst_user_modified_by."'",''));?>
        		<input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail->user_name);?>">
      		</div>
      		<?php } ?>
        </div>
      
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
        &nbsp;&nbsp;&nbsp;&nbsp;
        <input type='button' value='Close' class="btn btn-danger" onclick='window.location.replace("manageUsers.php"); '>
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
	$(document).ready(function(){
		$(document).on('change', '#primary_contact_type', function(){
            var primaryContact = $(this).val();
            if(primaryContact == 1){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="primary_mobile">Mobile<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Mobile" id="primary_mobile" name="primary_mobile" value="<?php if($_POST['primary_mobile']) echo $_POST['primary_mobile']; else echo $row->primary_mobile;?>" data-parsley-errors-container="#primary_mobileError" data-parsley-type="digits" data-parsley-required /><span id="primary_mobileError"><?php echo $err_primary_mobileError;?></span></div>';

                $("#primaryContactDiv").html(mobile);

            }else if(primaryContact == 2){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="primary_landline">Landline<font color="#FF0000">*</font></label><input type="text" class="form-control" placeholder="Enter Landline" id="primary_landline" name="primary_landline" value="<?php if($_POST['primary_landline']) echo $_POST['primary_landline']; else echo $row->primary_landline;?>" data-parsley-errors-container="#primary_landlineError" "data-parsley-type="digits" data-parsley-required /><span id="primary_landlineError"><?php echo $err_primary_landlineError;?></span></div>';

                $("#primaryContactDiv").html(landline);
            }
        });

        $(document).on('change', '#secondary_contact_type', function(){
            var secondaryContact = $(this).val();
            if(secondaryContact == 1){
              var landline = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_landline">Landline</label><input type="text" class="form-control" placeholder="Enter Landline" id="secondary_landline" name="secondary_landline" value="<?php if($_POST['secondary_landline']) echo $_POST['secondary_landline']; else echo $row->secondary_landline;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(landline);

            }else if(secondaryContact == 2){
              var mobile = '<div class="form-group col-md-2 col-sm-2"><label for="secondary_mobile">Mobile</label><input type="text" class="form-control" placeholder="Enter Mobile" id="secondary_mobile" name="secondary_mobile" value="<?php if($_POST['secondary_mobile']) echo $_POST['secondary_mobile']; else echo $row->secondary_mobile;?>"data-parsley-type="digits"/></div>';

                $("#secondaryContactDiv").html(mobile);
            }
        });

        $(document).on('change', '#id_mst_attributes_designations', function(){
        	var otherDesignation  = $(this).val();
        	if(otherDesignation == 100){
        		var designation = `<div class="form-group col-sm-2"><label col="other_designation">Other Designation<font color="#FF0000">*</font></label><input type="text" name="other_designation" id="other_designation" class="form-control" placeholder="Enter Designation Name value="<?php if($_POST) echo $_POST['other_designation']; else echo $row->other_designation;?>"data-parsley-errors-container="#other_designationError" data-parsley-required /><span id="other_designationError"></span></div>`;

        		$("#otherDesignationDiv").html(designation);

        	}else{
        		$("#otherDesignationDiv").html('<div></div>');
        	}
        });

    });

</script>


<script>
window.onload = function() {

				getHotel('<?php echo $rowUserDetail->id_shop; ?>','<?php echo $rowUserDetail->ids_mst_hotels; ?>','<?php echo $rowUserDetail->id; ?>');
				
				
				 };
							
</script>
<script type="text/javascript">
	function audittrial(clicked_value){
		//alert(clicked_value);
		var id = document.getElementById('userid').value;
		$('#auditModal').modal('show');
		var form_name ='User Manager';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
</script>