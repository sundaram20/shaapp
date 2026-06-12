<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_OUTLETS,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_OUTLETS,'edit');

$image_path = $UPLOAD_FILES.'/outlets/';

$image_display_path = $UPLOAD_FILES_PATH."/outlets/";


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	// image upload
	if(($_POST['old_image'] == '') && ($_FILES['image']['name'] == '')){
		//echo "hello";
	   //no error
		}else{
		//echo "hello";
		if($_FILES['image']['name'] !=''){
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
					//echo "hj1";exit;
					$err_image = '<font style="color:red;font-weight:normal;" ><br>Unable to upload file '.$_FILES['image']['name'].'.</font>';
				}
			}else{
				$err++;
				$err_image = '<font style="color:red;font-weight:normal;" ><br>Invalid file type '.$_FILES['image']['type'].'. Please use only JEPG,GIF,PNG,BMP only</font>';
			}
		}else{
			$err++;
			$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
		}else{
			//$err++;
			//$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			$sql = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `name` = '".addslashes(trim($_POST['name']))."'  ";
			$resultsql = mysqli_query($connNew, $sql);
			$numRows= mysqli_num_rows($resultsql);
			if($numRows == '0'){

			
			$addSql = "   	INSERT INTO `".TBL_OUTLETS."` SET
							`id_shop`='".$_SESSION['shop']."',
							`name` = '".addslashes(trim($_POST['name']))."',
							`description` = '".$_REQUEST['description']."',
							`service_charge_apply` = '".$_REQUEST['service_charge']."',
							`service_charge_per` ='".$_REQUEST['service_charge_per']."',
							`id_service_charge` ='".$_REQUEST['id_service_charge']."',
							`taxtype` ='".$_REQUEST['taxtype']."',
							`outlettype` ='".$_REQUEST['outlettype']."',
							`outlet_chargeable`  ='".$_REQUEST['outlet_chargeable']."',
							`outlet_code` = '".$_POST['outlet_code']."',
							`address` = '".$_REQUEST['address']."',
							`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
							`id_mst_state` = '".$_REQUEST['id_mst_state']."',
							`city` = '".$_REQUEST['city']."',
							`pincode` = '".$_REQUEST['pincode']."',
							`registered_office_address` = '".$_REQUEST['registered_office_address']."',
							`pan_no` = '".$_REQUEST['pan_no']."',
							`tin_no` = '".$_REQUEST['tin_no']."',
							`cin_no` = '".$_REQUEST['cin_no']."',
							`fssai_no` = '".$_REQUEST['fssai_no']."',
							
							`hsn_code` = '".$_REQUEST['hsn_code']."',
							`gst_no` = '".$_REQUEST['gst_no']."',
							`email` = '".$_REQUEST['email']."',
							`website` = '".$_REQUEST['website']."',
							`phone` = '".$_REQUEST['phone']."',
							`mobile` = '".$_REQUEST['mobile']."'";

			if($_FILES['image']['name'] != ''){				
				$addSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$addSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";

							//echo $addSql; exit;
							
$sql1 = executeSql("SELECT * FROM `".TBL_OUTLETS."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_outlets',
							`form_code` = 'outlet_manager_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);					
							
							
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= mysqli_insert_id($connNew);
				$_SESSION['successMsg'] = 'New Outlet details has been added sucessfully.';
				header("location:manageOutlets.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Outlet details has not been saved. Please make corrections below.';
			}
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Outlet  Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
			checkUserLevelPermission($_SESSION['userLevel'],TBL_OUTLETS,'update');
			
			
			
 $auditquery = "SELECT * From `".TBL_OUTLETS."` WHERE id =  '".addslashes(encryptor(decrypt,$_POST['eId']))."' ";
   $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	
	  $idd = $auditrow -> id;
	  $c1 = $auditrow -> name;
	  $c2 = $auditrow -> description;
	  $c3 =$auditrow -> outlet_code;
	  $c4= $auditrow -> address;
	  $c5= $auditrow -> id_mst_country_lang;
	  $c6= $auditrow -> city;
	  $c7= $auditrow -> pincode;
	  $c8= $auditrow -> registered_office_address;
	  $c9= $auditrow -> pan_no;
	  $c10 = $auditrow -> tin_no;
	  $c11 = $auditrow -> cin_no;
	  $c12 = $auditrow -> gst_no;
	  $c13 = $auditrow -> hsn_code;
	  $c14 = $auditrow -> website;
	  $c15= $auditrow -> email;
	  $c16= $auditrow -> phone;
	  $c17= $auditrow -> mobile;
	  $c18= $auditrow -> service_charge;
	  $c19= $auditrow -> outlettype;
	  $c20= $auditrow -> outlet_chargeable;
	  $c21= $auditrow -> taxtype;
	  $c22= $auditrow -> status;
	  $c23= $auditrow -> id_service_charge;
 
	  
    if($c1 != $_POST['name']){
		$ch1 = "Outlet Name Changed Details from ".  $c1 ." - to - " . $_POST['name'];
	}
	if($c2 != $_POST['description']){
		$ch2 ="Description Details Changed from " .  $c2." - to - ".$_POST['description'];
	}
	if($c3 != $_POST['outlet_code']){
		$ch3 ="Outlet Code Details Changed from " .   $c3 ." - to - " . $_POST['outlet_code'];
	} 
	if($c4 != $_POST['address']){
		$ch4 ="Address Details Changed from " . $c4 ." - to - " . $_POST['address'];
	} 
	if($c5 != $_POST['id_mst_country_lang']){
		 $old_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$c5."'");
		 $new_data = selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$_POST['id_mst_country_lang']."'  ");
		$ch5 = "Country Details Changed from " .  $old_data ." - to - " . $new_data;
		
	}
	
	if($c6 != $_POST['city']){
		$ch6 ="City Details Changed from " .   $c6 ." - to - " . $_POST['city'];
	}
	if($c7 != $_POST['pincode']){
		$ch7 ="Pincode Details Changed from " .   $c7 ." - to - " . $_POST['pincode'];
	}
	if($c8 != $_POST['registered_office_address']){
		$ch8 ="Registered Office Address Details Changed from " .   $c8 ." - to - " . $_POST['registered_office_address'];
	}
	if($c9 != $_POST['pan_no']){
		$ch9 ="PAN NO Details Changed from " .   $c9 ." - to - " . $_POST['pan_no'];
	}
	if($c10 != $_POST['tin_no']){
		 $ch10 ="TIN NO Details Changed from " .   $c10 ." - to - " . $_POST['tin_no'];
	}
	if($c11 != $_POST['cin_no']){
		$ch11 ="CIN NO Details Changed from " .   $c11 ." - to - " . $_POST['address'];
	}
	if($c12 != $_POST['gst_no']){
		$ch12 ="GST NO Details Changed from " .   $c12 ." - to - " . $_POST['gst_no'];
	}
	if($c13 != $_POST['hsn_code']){
		$ch13 ="HSN NO Details Changed from " .   $c13 ." - to - " . $_POST['hsn_code'];
	}
	if($c14 != $_POST['website']){
		$ch14 ="Website Details Changed from " .   $c14 ." - to - " . $_POST['website'];
	}
	if($c15 != $_POST['email']){
		$ch15 ="Email Details Changed from " .   $c15 ." - to - " . $_POST['email'];
	}
	if($c16 != $_POST['phone']){
		$ch16 ="Phone Details Changed from " .   $c16 ." - to - " . $_POST['phone'];
	}
	if($c17 != $_POST['mobile']){
		$ch17 ="Mobile Details Changed from " .   $c17 ." - to - " . $_POST['mobile'];
	}
	
	if($c19 != $_POST['outlettype']){
		if($c19 == 1){$old='POS';}else if($c19 == 2){$old='Laundry';}else if($c19 == 3){$old='Spa and Health Club';}else if($c19 == 4){$old='Others';}
		if($_POST['outlettype'] == 1){$new='POS';}else if($_POST['outlettype'] == 2){$new='Laundry';}else if($_POST['outlettype'] == 3){$new='Spa and Health Club';}else if($_POST['outlettype'] == 4){$new='Others';}
		$ch19  ="Outlet Type Details Changed from " .   $old ." - to - " . $new;
	}
	
	if($c20 != $_POST['outlet_chargeable']){
		if($c20 == 1){$old='Yes';}else{$old='No';}
		if( $_POST['outlet_chargeable'] == 1){$new='Yes';}else{$new='No';}
		$ch20 ="Non Chargeable Changed from " .   $old ." - to - " . $new;
	}
	if($c21 != $_POST['taxtype']){ 
	        if($c21 == 1){$old='GST';}else{$old='VAT';}
		if( $_POST['taxtype'] == 1){$new='GST';}else{$new='VAT';}
		$ch21  ="Tax Type Details Changed from " .   $old ." - to - " . $new;
	}
	if($c22 != $_POST['status']){
		if($c22 == 1){$old='Active';}else{$old='Inactive';}
		if( $_POST['status'] == 1){$new='Active';}else{$new='Inactive';}
		$ch22 ="Status Details Changed from " .   $old ." - to - " . $new;
	}
	
		if( $_POST['service_charge'] == '0'){
			$ch22 ="Service Charge Applicable Details Changed from Yes - to - No";
		}
		
	/*	else if( $_POST['service_charge'] == '1'){
			 $old_data = selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$c23."'");
			 $ch23 = "Service Charge Changed to " .  $old_data ;
		} */
		
	
	if($c23 != $_POST['id_service_charge']){
	 $old_data = selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$c23."'");
	  $new_data = selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$_POST['id_service_charge']."'  ");
		 
		if($_POST['service_charge'] == '0'){
			$ch23 = "";
		}else{
			$ch23 = "Service Charge Changed from " .  $old_data ." - to - " . $new_data;
		}
	}


			
	}		
			
			 $editSql = "   UPDATE `".TBL_OUTLETS."` SET 
							`name` = '".addslashes(trim($_POST['name']))."',
							`service_charge_apply` = '".$_REQUEST['service_charge']."',
							`service_charge_per` ='".$_REQUEST['service_charge_per']."',
							`id_service_charge` ='".$_REQUEST['id_service_charge']."',
							`taxtype` ='".$_REQUEST['taxtype']."',
							`outlettype` ='".$_REQUEST['outlettype']."',
							`outlet_chargeable`  ='".$_REQUEST['outlet_chargeable']."',
							`description` = '".$_REQUEST['description']."',
							`outlet_code` = '".$_POST['outlet_code']."',
							`address` = '".$_POST['address']."',
							`id_mst_country_lang` = '".$_REQUEST['id_mst_country_lang']."',
							`id_mst_state` = '".$_REQUEST['id_mst_state']."',
							`city` = '".$_REQUEST['city']."',
							`pincode`='".$_REQUEST['pincode']."',
							`registered_office_address` = '".$_REQUEST['registered_office_address']."',
							`pan_no` = '".$_REQUEST['pan_no']."',
							`tin_no` = '".$_REQUEST['tin_no']."',
							`cin_no` = '".$_REQUEST['cin_no']."',
							`fssai_no` = '".$_REQUEST['fssai_no']."',
							`hsn_code` = '".$_REQUEST['hsn_code']."',
							`gst_no` = '".$_REQUEST['gst_no']."',
							`email` = '".$_REQUEST['email']."',
							`website` = '".$_REQUEST['website']."',
							`phone` = '".$_REQUEST['phone']."',
							`mobile` = '".$_REQUEST['mobile']."'";

			if($_FILES['image']['name'] != ''){				
				$editSql .= "	,`image` = '".addslashes($insert_image)."'";
			}else{
				$editSql .= "	,`image` = '".addslashes($_POST['old_image'])."'";
			}
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			
		echo	 $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor(decrypt,$_POST['eId']))."',
							`tables_name` = 'mst_outlets',
							`form_code` = 'outlet_manager_form',
							`changes` =  '".addslashes($ch1).",".addslashes($ch2).",".addslashes($ch3).",".addslashes($ch4).",".addslashes($ch5).",".addslashes($ch6).",".addslashes($ch7).",".addslashes($ch8).",".addslashes($ch9).",".addslashes($ch10).",".addslashes($ch11).",".addslashes($ch12).",".addslashes($ch13).",".addslashes($ch14).",".addslashes($ch15).",".addslashes($ch16).",".addslashes($ch17).",".addslashes($ch18).",".addslashes($ch19).",".addslashes($ch20).",".addslashes($ch21).",".addslashes($ch22).",".addslashes($ch23).",".addslashes($ch25)." ',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
                    executeSql($auditeditSql);
			
			
			
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_OUTLETS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:manageOutlets.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_OUTLETS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Outlet details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_OUTLETS."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Outlet</a></li>  
            </ul>
			<div class="box-header with-border">
			
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->name ?> </span>
			  
			  <a><?php echo selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
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
                
				 <div class="form-group col-md-4">
                  <label for="name">Outlet Name<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-outdent"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Outlet Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>"  data-parsley-required>
				<?php echo $err_unit_name;?></div>
                </div>
				
				
				 <div class="form-group col-md-4">
                  <label for="name">Description</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Description" id="description" name="description" value="<?php if($_POST) echo $_POST['field_description'];else echo stripslashes($row->description);?>" >
				<?php echo $err_unit_field_description;?></div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">Outlet Code<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-creative-commons"></i> 
					   	</div>
                  <input data-parsley-required type="text" class="form-control" placeholder="Enter Code" id="outlet_code" name="outlet_code" value="<?php if($_POST) echo $_POST['outlet_code'];else echo stripslashes($row->outlet_code);?>" >
				<?php echo $err_unit_field_description;?>
			</div>
                </div> 


                <div class="form-group col-md-4">
                  <label for="name">Address<font color="#FF0000">*</font></label>
                  <textarea id="address" name="address" data-parsley-required style="resize: vertical;" placeholder="enter address" class="form-control" cols="4" rows="1"><?php echo ($_POST['address']!=''?$_POST['address']:$row->address)?></textarea>
                </div> 
				
				<div class="form-group col-md-2">
                  <label for="name">Country<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-globe"></i> 
					   	</div>
				<?php
					$countrySql='SELECT * FROM '.TBL_COUNTRY_LANG.' ';
					$resCountry = mysqli_query($connNew,$countrySql);
					
								
				?>	   	
                  <select onchange="getState(this.value,'','');" required="required" id='id_mst_country_lang' name="id_mst_country_lang" class="select2 form-control" style="width:100%">
                  	<option value="">--Select Country--</option>
					<?php
						while ($rowCountry=mysqli_fetch_object($resCountry)) {
							
							if($rowCountry->id_country==$row->id_mst_country_lang)
								$selected="selected='selected'";
							else
								$selected="";
					?>	
					<option <?php echo $selected; ?> value="<?php echo $rowCountry->id_country; ?>"><?php echo $rowCountry->name ;?></option>
				<?php } ?>
                  </select>
				  </div>
                </div> 

                <!--<div class="form-group col-md-2">
                  <label for="name">State<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-map"></i> 
					   	</div>
                  <select data-parsley-required id='id_mst_state' name="id_mst_state" class="select2 form-control">
                  	
                  </select>
				  </div>
                </div> -->

                <div class="form-group col-md-2">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-bank"></i> 
					   	</div>
                  <input data-parsley-required type="text" class="form-control" placeholder="Enter City" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo stripslashes($row->city);?>" >
				  </div>
                </div> 
                <div class="form-group col-md-4">
                  <label for="name">Pincode<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-map-marker"></i> 
					   	</div>
                  <input data-parsley-required type="text" class="form-control" placeholder="Enter Pin" id="pincode" name="pincode" value="<?php if($_POST) echo $_POST['pincode'];else echo stripslashes($row->pincode);?>" >
				  </div>
                </div> 
                <div class="form-group col-md-4">
                  <label for="name">Registered Office Address</label>
                  <textarea id="registered_office_address" name="registered_office_address"  style="resize: vertical;" placeholder="Enter registered office address" class="form-control" cols="4" rows="1"><?php echo ($_POST['registered_office_address']!=''?$_POST['registered_office_address']:$row->registered_office_address)?></textarea>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">PAN NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Pan No." id="pan_no" name="pan_no" value="<?php if($_POST) echo $_POST['pan_no'];else echo stripslashes($row->pan_no);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">TIN NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Tin No." id="tin_no" name="tin_no" value="<?php if($_POST) echo $_POST['tin_no'];else echo stripslashes($row->tin_no);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">CIN NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Cin No." id="cin_no" name="cin_no" value="<?php if($_POST) echo $_POST['cin_no'];else echo stripslashes($row->cin_no);?>" >
				  </div>
                </div> 
                
				
                
                
                <div class="form-group col-md-4">
                  <label for="name">GST NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter GST No." id="gst_no" name="gst_no" value="<?php if($_POST) echo $_POST['gst_no'];else echo stripslashes($row->gst_no);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">HSN NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter HSN No." id="hsn_code" name="hsn_code" value="<?php if($_POST) echo $_POST['hsn_code'];else echo stripslashes($row->hsn_code);?>" >
				  </div>
                </div> 

				<hr width="95%"  />
                <div class="form-group col-md-4"> 
                
                  <label for="name">FSSAI NO.</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter FSSAI No." id="fssai_no" name="fssai_no" value="<?php if($_POST) echo $_POST['fssai_no'];else echo stripslashes($row->fssai_no);?>" >
				  </div>
                </div> 
				<div class="form-group col-md-4">
                  <label for="name">Website</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-chrome"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Website." id="website" name="website" value="<?php if($_POST) echo $_POST['website'];else echo stripslashes($row->website);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">Email</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-envelope"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Email." id="email" name="email" value="<?php if($_POST) echo $_POST['email'];else echo stripslashes($row->email);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">Phone</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-phone"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Phone number(s)." id="phone" name="phone" value="<?php if($_POST) echo $_POST['phone'];else echo stripslashes($row->phone);?>" >
				  </div>
                </div> 

                <div class="form-group col-md-4">
                  <label for="name">Mobile</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-mobile"></i> 
					   	</div>
                  <input  type="text" class="form-control" placeholder="Enter Mobile number(s)." id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>" >
				  </div>
                </div> 
				

				<!-- image Section-->
				<div class="form-group col-sm-2">				
							 <label for="image">Logo &nbsp;&nbsp;</label>
								<div class="btn btn-default btn-file">
								  <i class="fa fa-upload"></i> Upload
								 <input type="file" class="form-control" placeholder="Outlet Image" id="image" name="image" value="" onchange="readURL(this);">	
									
								 <input type="hidden" name="old_image" value="<?php echo $row->image;?>" />					 
							
								</div>
								<p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>	
				</div>				
													 
						
				<div class="col-sm-2">													
							<ul class="mailbox-attachments clearfix"> 
										<li id="imageCallback" style="width: 150px !important;">
										<?php if(@file_exists($image_path.$row->image) && $row->image!=''){  ?>

										<span class="mailbox-attachment-icon has-img">							 
											<img src="<?php echo $image_display_path.$row->image; ?>" alt="Outlet Image">							  
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
											<img src="../images/no-hotel-image.jpg" alt="Item Image" id="blah">							  
										  </span>			
										  <div class="mailbox-attachment-info">
											<a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
												<span class="mailbox-attachment-size">
												   <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
												  <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
												</span>
										  </div>							
										<?php }?> 
										  
										</li>                
									  </ul>			  
						 </div>
					
				<!-- image end -->

				<div class="form-group col-md-4">
                  <label for="name">Service Charge Applicable</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
					<?php

						if($row->service_charge_apply==1)
							$selectedYes="selected='selected'";
						else
							$selectedYes="";
					?>   	
                  <select onchange="serviceCharge(this.value);" name="service_charge" id="service_charge" class="select2 form-control" style="width:100%">
                  	<option  value="0">No</option>
                  	<option <?php echo $selectedYes; ?> value="1">Yes</option>
                  	
                  </select>
				  </div>
                </div>
                 

                <div style="display: none;" id="per_box" class="form-group col-md-4">
                <input type="hidden" value="<?php echo $row->service_charge_per;?>" name="service_charge_per" id="service_charge_per">
                  <label for="name">Service Charge</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-percent"></i> 
					   	</div>
					   			
		                 <select  class="form-control" name="id_service_charge" id="id_service_charge" onchange="salesaccountlocal()" style="width:100%">
									<option value="">Select Service Charge</option>
								  <?php $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '3' and transaction_type = '1' ",' ORDER BY `name`');
								  if(mysqli_num_rows($resCat)){
								  	while($resultCat = mysqli_fetch_object($resCat)){			
										if($_REQUEST['id_service_charge'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_service_charge == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						
				  </div>
                  <style type="text/css">
							#sgst{display: none; color: green;} 
							#cgst{display: none; color: green;}
							#percentage{display: none; color: green;}
						
						</style>
                        <div id="percentage" name="percentage"><ul><li>percentage</li></ul></div>
		                <div id="sgst" name="sgst"><ul><li>sgst</li></ul></div>
		                <div id="cgst" name="cgst"><ul><li>cgst</li></ul></div>
		                
                </div>
				
</div>

           		<div class="box-body">
                <div class="form-group col-md-4">
                  <label for="name">Outlet Type</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
					
                  <select  name="outlettype" id="outlettype"  class="select2 form-control" style="width:100%">
                  <option  value="">Select Outlet Type</option>
                  	<option  value="1" <?php if($row->outlettype==1){
							echo "selected='selected'";} ?> >POS</option>
                  	<option <?php if($row->outlettype==2){
							echo "selected='selected'";} ?>  value="2">Laundry</option>
                    	<option <?php if($row->outlettype==3){
							echo "selected='selected'";} ?>  value="3">Spa and Health Club</option>
                       <option <?php if($row->outlettype==4){
							echo "selected='selected'";} ?>  value="4">Others</option>             
                  	
                  </select>
				  </div>
                </div>
                <div class="form-group col-md-4">
               
                  <label for="name">Non Chargeable</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-percent"></i> 
					   	</div>
					   			
		                 <?php

						if($row->outlet_chargeable==1)
							$selectedoutlet_chargeableYes="selected='selected'";
						else
							$selectedoutlet_chargeableYes="";
					?>   	
                  <select name="outlet_chargeable" id="outlet_chargeable" class="select2 form-control" style="width:100%">
                  	<option  value="0">No</option>
                  	<option <?php echo $selectedoutlet_chargeableYes; ?> value="1">Yes</option>
                  	
                  </select>
						
				  </div>
                  
		                
                </div>
                <div class="form-group col-md-4">
                  <label for="name">Tax Type</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-legal"></i> 
					   	</div>
					
                  <select  name="taxtype" id="taxtype" class="select2 form-control" style="width:100%">
                  <option  value="">Select Tax Type</option>
                  	<option  value="1" <?php if($row->taxtype==1){
							echo "selected='selected'";} ?> >GST</option>
                  	<option <?php if($row->taxtype==2){
							echo "selected='selected'";} ?>  value="2">VAT</option>
                  	
                  </select>
				  </div>
                </div>
                
                <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>	                				
				<div class="form-group col-md-12">
                  <label for="status">Status</label><br>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
                </div>
				<div class="box-body">
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
              

              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageOutlets.php"); '>
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
	
	$('#outlet_code').change(function(){
		
		$.ajax({
			url:'../ajax/fetchOutletCode.php',
			type:'POST',
			data:'field_name=outlet_code&value='+$(this).val(),
			success:function(data){
				console.log(data)
				/*if(data.id!=''){
					alert('Outlet Code Already Exists');
					$('#outlet_code').val('');
				}
				else{
					
				}*/
			}
		})
	});

	getState($('#id_mst_country_lang').val(),<?php echo $row->id_mst_state?>,$('#id_mst_state').val());

	
	serviceCharge($('#service_charge').val());
	function serviceCharge(value){
		
		if(value==1){
			$('#per_box').show();
			$('#service_charge_per').attr('required','required');
		}	
		else{
			$('#per_box').hide();
			$('#service_charge_per').removeAttr('required','required');
		}	
	}


function salesaccountlocal(){ 

		var id_service_charge = document.getElementById("id_service_charge");
	 	var selectedValue = id_service_charge.options[id_service_charge.selectedIndex].value;

	 var account = 'salesaccountlocal';
	$.ajax({
        type: "post",
        url: "../ajax/charges_master.php",
        cache: false, 
        data: { selectedValue : selectedValue, account:account } ,
        dataType: 'json',
        success: function(data)
		{  
			if(data == null){
				$("#sgst").css("display", "none");
				$("#cgst").css("display", "none");
				$("#vat").css("display", "none");
				$("#cess").css("display", "none");
				$("#surcharge").css("display", "none");
				$("#percentage").css("display", "none");
				
			}else{
				console.log(data);
				$("#sgst").css("display", "block");	
				$("#cgst").css("display", "block");	
				$("#vat").css("display", "block");	
				$("#cess").css("display", "block");	
				$("#surcharge").css("display", "block");
				$("#percentage").css("display", "block");	
				$("#percentage").html('PERCENTAGE: '+ data['percentage']+' %');
				$("#service_charge_per").val(data['percentage']);
				var node = document.createElement("LI"); 
				var textnode1 = document.createTextNode('SGST: '+ data['sgst'] + ',   ');
				var textnode2 = document.createTextNode('CGST: '+ data['cgst'] + ',   ');
				var textnode3 = document.createTextNode('VAT: '+ data['vat'] + ',   ');
				var textnode4 = document.createTextNode('CESS: '+ data['cess'] + ',   ');
				var textnode5 = document.createTextNode('SURCHARGE: '+ data['surcharge'] + ',   ');
				
				
				if(data['sgst'] == undefined) {
					$("#sgst").css("display", "none");
					$("#cgst").css("display", "none");
					$("#cess").css("display", "none");
  				}else{
  					
					var item = document.getElementById("sgst").childNodes[0];
	  				item.replaceChild(textnode1, item.childNodes[0]);
	  				var item = document.getElementById("cgst").childNodes[0];
	  				item.replaceChild(textnode2, item.childNodes[0]);
					var item = document.getElementById("cess").childNodes[0];
	  				item.replaceChild(textnode4, item.childNodes[0]);
					var item = document.getElementById("percentage").childNodes[0];
	  				item.replaceChild(textnode6, item.childNodes[0]);
  				}if(data['vat'] == undefined){
  					$("#vat").css("display", "none");					
					$("#surcharge").css("display", "none");
					
  				}else{
	  				var item = document.getElementById("vat").childNodes[0];
	  				item.replaceChild(textnode3, item.childNodes[0]);
	  				var item = document.getElementById("surcharge").childNodes[0];
	  				item.replaceChild(textnode5, item.childNodes[0]); 
	  			}
 
 
				 
			}
		}
    });
}
	
function readURL(input) {
	alert("hello");
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
		var table ='mst_outlets';
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