<?php include_once("../config/auto_loader.php");
//updateInventoryCRS
 include_once("functions/updateInventoryCRS.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'view');
$image_path = $UPLOAD_FILES.'/hotel_room/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_room/";
/////////////////////////////////////////////////////////////////////////////////////
if($_REQUEST['eId'] == ''){
	header("location:editmanageRoomNo.php");
}
/////////////////////////////////////////////////////////////////////////////////////
//---------------------------------------------------------------------------------------------------------





function updateInventoryManageBlock($id_mst_room_types, $id_mst_hotels) {
    global $connNew;

    $query = "SELECT * FROM `".TBL_ROOMNO."` 
              WHERE id_mst_room_types = '$id_mst_room_types' 
              AND id_mst_hotels = '$id_mst_hotels' 
              AND status='1' 
              AND blocked_room_dates != '' ";

    $resSQL = mysqli_query($connNew, $query);
$arrayOfDates = [];
    while ($Record = mysqli_fetch_object($resSQL)) {
        $roomId = $Record->id;
        $record1 = explode(',',$Record->blocked_room_dates);
print_r($record1);
		
	 foreach ($record1 as $selectedDateRange) {
    // First explode by '-'
     $dates = explode('-', $selectedDateRange);

    // Clean spaces (important)
    $startDate = trim($dates[0]);
    $endDate = trim($dates[1]);

    // Convert to timestamps
    $startTimestamp = strtotime(str_replace('/', '-', $startDate)); // Convert 01/05/2025 to 01-05-2025 for strtotime
    $endTimestamp = strtotime(str_replace('/', '-', $endDate));

    if ($startTimestamp && $endTimestamp) {
        // Loop from start to end
        for ($current = $startTimestamp; $current <= $endTimestamp; $current = strtotime('+1 day', $current)) {
            $currentDate = date('Y-m-d', $current);
            
            // Check if date already exists
            if (isset($arrayOfDates[$currentDate])) {
                $arrayOfDates[$currentDate] += 1; // add 1 if date exists
            } else {
                $arrayOfDates[$currentDate] = 1; // first time add 1
            }
        }
    }
}
		
       
    }
	 // Now, update the blocked_hotel field based on the array of dates
    foreach ($arrayOfDates as $date => $count) {
        // Prepare the query to update blocked hotel for each date
        $queryUpdate = "UPDATE fo_inventory 
                        SET blocked_hotel = '$count' 
                        WHERE id_mst_room_types = '$id_mst_room_types' 
                        AND id_mst_hotels = '$id_mst_hotels' 
                        AND allocation_date = STR_TO_DATE('$date', '%Y-%m-%d')";

        // Execute the query
        mysqli_query($connNew, $queryUpdate);
    }
	
	updateInventoryCRS($connNew,$arrayOfDates,$id_mst_room_types,$id_mst_hotels);
	
}

if($_POST['Save']){		
	$err = 0;
	
	if(empty($_POST['id_mst_hotels'])){
		$err++;
		$err_room_id = '<font style="color:red;font-weight:normal;" ><br>Please select room.</font>';
	}
	if(empty($_POST['id_mst_room_types'])){
		$err++;
		$err_single_pax_price = '<font style="color:red;font-weight:normal;" ><br>Please Select RoomTypes.</font>';
	}
	if(empty($_POST['room_no'])){
		$err++;
		$err_roomno = '<font style="color:red;font-weight:normal;" ><br>Please enter room numbers.</font>';
	}
	
	if(empty($_POST['occupany'])){
		$err++;
		$err_occupany = '<font style="color:red;font-weight:normal;" ><br>Please select occupany.</font>';
	}

	if(empty($_POST['block_floor'])){
		$err++;
		$err_block_floor = '<font style="color:red;font-weight:normal;" ><br>Please Enter Block/Floor.</font>';
	}
	if(empty($_POST['management_block'])){
		//$err++;
		//$err_management_block = '<font style="color:red;font-weight:normal;" ><br>Please Enter Managment Block.</font>';
	}
	
	
	if($err == 0){//No error
		$execlusionArr = array();
foreach (explode(',',$_REQUEST['blocked_room_dates']) as $index => $dates) {
	if($dates!=0){
	 array_push($execlusionArr, $dates);
	}
}
		//echo '11111';print_r($_REQUEST['blocked_room_dates']);
		//die;
		
		if(($_POST['Save'] == 'Add') && empty($_POST['id'])){//add
			$query = "SELECT * From `".TBL_ROOMNO."` WHERE id_mst_room_types = '".$_POST['id_mst_room_types']."' AND roomno = '".$_POST['roomno'] ."'";

			//echo $query; exit;

			$resSQL = mysqli_query($connNew, $query);
			$numRows = mysqli_num_rows($resSQL);
           
			
			if($numRows>0){
				$_SESSION['errorMsg'] = 'Room Type and status active date are already insert. Please make corrections below. ';
			}else{
            //echo $last_id = mysqli_insert_id($numRows);
				//checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOMNO,'add');
				
				
				
				
				
				$addSql = "  INSERT INTO `".TBL_ROOMNO."` SET 
							`id_mst_hotels` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`room_no` = '".addslashes($_POST['room_no'])."',
							`occupany` = '".addslashes($_POST['occupany'])."',
							`id_mst_hotel_room_block` = '".addslashes($_POST['block_floor'])."',
							`room_status` = '".addslashes($_POST['room_status'])."',
							`house_keeping_status`='".addslashes($_POST['house_keeping_status'])."',
							`management_block` = 'No',
							`description` = '".addslashes($_POST['description'])."',
							`blocked_room_dates`='".implode(',',$execlusionArr)."',
							`display_order` = '".addslashes($_POST['display_order'])."' ";

				
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
			
			
 $sql1 = executeSql("SELECT * FROM `".TBL_ROOMNO."` ORDER BY id DESC LIMIT 1");
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
							`tables_name` = 'mst_roomno',
							`form_code` = 'mst_roomno_form',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',



							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
				
				  executeSql($auditaddSql);
				   
				
				if(executeSql($addSql)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Hotel room no assigned details has been added sucessfully.';
					header("location:manageRoomNo.php?eId=".$_REQUEST['eId']."&id_mst_room_types=".encryptor('encrypt', $_REQUEST['id_mst_room_types'])."&action=edit&page=".$_REQUEST['page']);
					exit;
				}else{
					$err++;
					$_SESSION['errorMsg'] = 'Hotel room no assigned details has not been saved. Please make corrections below.';
				}
				
				//echo $addSql; die;
				

			}
		}else if(($_POST['Save'] == 'Edit') && !empty($_POST['id'])){//update
			//echo "Edit";exit;
			//checkUserLevelPermission($_SESSION['userLevel'],TBL_ASSIGN_HOTEL_ROOM,'update');
			
			


$auditquery = "SELECT * From `".TBL_ROOMNO."` WHERE  id = '".addslashes(encryptor('decrypt',$_POST['id']))."' ";

  $auditresSQL = mysqli_query($connNew, $auditquery);	
	while($auditrow = mysqli_fetch_object($auditresSQL)){ 
	  $idd = $auditrow -> id;
	  $roono = $auditrow -> room_no;
	  $occupany = $auditrow -> occupany;
	  $block_floor = $auditrow -> id_mst_hotel_room_block;
	  $room_status = $auditrow -> room_status;
	  $management_block = $auditrow -> 	management_block;
	  $description = $auditrow -> description;
	  $display_order = $auditrow -> display_order;
	  $status = $auditrow -> status;
	  
	  if($room_status==1){
		 $status2 = "Dirty";
	 }else if($room_status==2){
		 $status2 = "Reserved";
	 }else if($room_status==3){
		 $status2 = "Occupied";
	 }else if($room_status==4){
		 $status2 = "Clean";
	 }else if($room_status==5){
		 $status2 = "Blocked";
	 }else if($room_status==6){
		 $status2 = "Under Maintenance";
	 }
						$room = $_POST['room_status'];
									if($room==1){
										 $status1 = "Dirty";
									 }else if($room==2){
										 $status1 = "Reserved";
									 }else if($room==3){
										 $status1 = "Occupied";
									 }else if($room==4){
										 $status1 = "Clean";
									 }else if($room==5){
										 $status1 = "Blocked";
									 }else if($room==6){
										 $status1 = "Under Maintenance";
									 }
	
    if($roono != $_POST['room_no']){
	 $changeroomno = "Roomno Details Changed from ".  $roono ." - to - " . $_POST['room_no'];
	}
	if($occupany != $_POST['occupany']){
		 $changeocc ="Occupancy Details Changed from " .  $occupany." - to - ".$_POST['occupany'];
	}
	if($block_floor != $_POST['block_floor']){
		 $changeblock ="Block/Floor Details Changed from " .   $block_floor ." - to - " . $_POST['block_floor'];
	} 
	if($room_status != $_POST['room_status']){
		$changeroomstatus ="Room Status Details Changed from " .   $status2 ." - to - " . $status1;	
	} 
	if($management_block != $_POST['management_block']){
		$changemanageblock = "Manage Block Details Changed from " .  $management_block ." - to - " . $_POST['management_block'];
	}
	if($display_order != $_POST['display_order']){
		$changedisorder ="Display Order Details Changed from " .   $display_order ." - to -" . $_POST['display_order'];
	} 
	if($status != $_POST['status']){
		if($status == 1){$status='Active';}else{$status='Inactive';}
		if( $_POST['status'] == 1){$old_data='Active';}else{$old_data='Inactive';}
		$changestatus ="Status Details Changed from " .   $status ." - to - " . $old_data;
	}
 
 }	


$execlusionArr = array();
foreach (explode(',',$_REQUEST['blocked_room_dates']) as $index => $dates) {
	if($dates!=0){
	 array_push($execlusionArr, $dates);
	}
}
			


    // Final date string to update back in DB
    $finalDatesString = implode(',', $execlusionArr);

    	
			updateInventoryManageBlock($_POST['id_mst_room_types'],addslashes(encryptor('decrypt',$_REQUEST['eId'])));
			
			$editSql = "   	UPDATE `".TBL_ROOMNO."` SET 
							`id_mst_hotels` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`room_no` = '".addslashes($_POST['room_no'])."',
							`occupany` = '".addslashes($_POST['occupany'])."',
							`id_mst_hotel_room_block` = '".addslashes($_POST['block_floor'])."',
							`room_status` = '".addslashes($_POST['room_status'])."',
							`management_block` = 'No',
							`house_keeping_status`='".addslashes($_POST['house_keeping_status'])."',
							`description` = '".addslashes($_POST['description'])."',
							`blocked_room_dates`='".$finalDatesString."',
							`display_order` = '".addslashes($_POST['display_order'])."' ";
							
							if($_POST['status'] == "1"){

					$editSql .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
				}else{
					$editSql .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
				$editSql .= "	,`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'";
		
	
			$editSql .= "WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";		

//echo $editSql;die;
            $auditeditSql = " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor('decrypt',$_POST['id']))."',
							`tables_name` = 'mst_roomno',
							`form_code` = 'mst_roomno_form',
							`changes` =  '".addslashes($changeroomno).",".addslashes($changeocc).",".addslashes($changeblock).",".addslashes($changeroomstatus).",".addslashes($changemanageblock).",".addslashes($changedisorder).",".addslashes($changestatus)."',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";					
			
			//$auditeditSql .= "WHERE `user_id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";	
              executeSql($auditeditSql);			
											
			if (executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['id_mst_room_types']."'").' details has been updated sucessfully.';
				header("location:manageRoomNo.php?eId=".$_REQUEST['eId']."&id_mst_room_types=".encryptor('encrypt', $_REQUEST['id_mst_room_types'])."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".encryptor('decrypt',$_REQUEST['eId'])."'").'-'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$_POST['id_mst_room_types']."'").' details has not been saved.Please make corrections below.';
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
				checkUserLevelPermission($_SESSION['userLevel'],TBL_ROOMNO,'add');
				$addSql1 = "  INSERT INTO `".TBL_ROOMNO."` SET 
							`id_mst_hotels` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."',
							`blocked_room_dates`='".implode(',',$execlusionArr)."',
							`id_mst_room_types` = '".addslashes($_POST['id_mst_room_types'])."',
							`room_no` = '".addslashes($_POST['room_no'])."',
							`occupany` = '".addslashes($_POST['occupany'])."',
							`id_mst_hotel_room_block` = '".addslashes($_POST['block_floor'])."',
							`room_status` = '".addslashes($_POST['room_status'])."',
							
							`house_keeping_status`='".addslashes($_POST['house_keeping_status'])."',
							`description` = '".addslashes($_POST['description'])."',
							`display_order` = '".addslashes($_POST['display_order'])."' ";
				if($_FILES['image']['name'] != ''){				
					$addSql1 .= "	,`image` = '".addslashes($insert_image)."'";
				}else{
					$addSql1 .= "	,`image` = '".addslashes($_POST['old_image'])."'";
				}

				if($_POST['status'] == "1"){

					$addSql1 .= " ,`status_active_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_active_date'])))."' ";
				}else{
					$addSql1 .= " ,`status_inactive_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['status_inactive_date'])))."' ";
				}
			 	 $addSql1 .= "	,`date_created` = '".currenDateTime()."'
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_created_by` = '".$_SESSION['userId']."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'";
				
				$auditaddSql = "INSERT INTO audit_trail SET
							`voucher_id` = '".$last_id."',
							`tables_name` = 'mst_roomno',
							`form_code` = 'Manage RoomNo',
							`changes` = 'No Change',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";

				 executeSql($auditaddSql);

				$addAmend = "UPDATE `".TBL_ROOMNO."` SET 
					`amend_yn` = '1'
				 WHERE `id` = '".addslashes(encryptor('decrypt',$_POST['id']))."'";

				 // update amend_yn
				 executeSql($addAmend);
				 
				 
				 $auditeditSql1 =  " INSERT audit_trail SET 
			                `voucher_id` = '".addslashes(encryptor('decrypt',$_POST['id']))."',
							`tables_name` = 'mst_roomno',
							`form_code` = 'Manage RoomNo',
							`changes` =  '".addslashes($changeroomno).",".addslashes($changeocc).",".addslashes($changeblock).",".addslashes($changeroomstatus).",".addslashes($changemanageblock).",".addslashes($changedes).",".addslashes($changedisorder).",".addslashes($changestatus)."',	
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 2 ";		
			
			    // $auditeditSql1 .= "WHERE `table_name` = 'mst_roomno'";		
			     executeSql($auditeditSql1);
				 
				 
				 
				//echo $addSql; die;
				if(executeSql($addSql1)){
					unset($_POST);
					$_SESSION['successMsg'] = 'New Hotel room assigned details has been added sucessfully.';
					header("location:manageRoomNo.php?eId=".$_REQUEST['eId']."&action=edit&page=".$_REQUEST['page']);
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
	$sql = "  SELECT * FROM `".TBL_ROOMNO."`
								WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
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
        <small>Manage Room Number To Hotel</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Room Number To Hotel</li>
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
				<li><a href="manageAssignHotelRoom.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Room Types</a></li>
              <li ><a href="editHotelGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>">Photo Gallery</a></li> 
              <li ><a href="editHotelVideoGallery.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Video Gallery</a></li>
				<li class="active"><a href="editmanageRoomNo.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" data-toggle="tab">Manage Rooms</a></li>     
			  
			  <!--<li><a href="roomno.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >roomno</a></li> 
			  <li  ><a href="calendar.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" >Calendar</a></li>-->
            </ul> 
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Room : <a><?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'"); ?> </a></h3>     
			   <a href="manageRoomNo.php?eId=<?php echo $_GET['eId']; ?>&id_mst_room_types=<?php echo $_REQUEST['id_mst_room_types']; ?>&action=edit&page=<?php echo $_GET['page']; ?>" class="btn btn-success pull-right"><i class="fa fa-angle-double-left"></i> Back</a>  
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
							<label for="id_mst_hotel">Hotel Name<font color="#FF0000">*</font></label>
            				<input readonly="" type="text"  class="form-control" placeholder="ID MST HOTELS" id="id_mst_hotels" name="id_mst_hotels" value="<?php echo selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'");?> ">
            			</div>
						
						
						<div class="form-group col-md-4">
							<label for="id_mst_room_types1">Room Type<font color="#FF0000">*</font></label>
            				<input readonly="" type="text"  class="form-control" placeholder="ID MST HOTELS" id="id_mst_room_types1" name="id_mst_room_types1" value="<?php echo selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[id_mst_room_types]))."'");?> ">
            			</div> 
						
						
            				<input readonly="" type="hidden"  class="form-control" placeholder="ID MST HOTELS" id="id_mst_room_types" name="id_mst_room_types" value="<?php echo selectColumn(TBL_ROOM_TYPE,'id'," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST[id_mst_room_types]))."'");?> ">
            		
            		
						
            			<div class="form-group col-md-4">
							<label for="roomno">Room No<font color="#FF0000">*</font></label>
							<input type="text"  class="form-control" placeholder="Enter Room No" id="room_no" name="room_no" value="<?php if($_POST) echo $_POST['room_no'];else echo stripslashes($row->room_no);?>">
							<?php echo $err_roomno;?>
            			</div>

            			

            			<div class="form-group col-md-3">
              				<label for="occupany">Occupancy<font color="#FF0000">*</font></label>
             				<select class="form-control select2" name="occupany" id="occupany">
								<option value="">Select Occupancy</option>
								<?php if($row->occupany == 'Single'){?><option value="Single" selected="selected">Single</option><?php }?><?php if($row->occupany == 'Double'){?><option value="Double" selected="selected">Double</option><?php }?><?php if($row->occupany == 'Twin'){?><option value="Twin" selected="selected">Twin</option><?php }?><?php if($row->occupany == 'Triple'){?><option value="Triple" selected="selected">Triple</option><?php }?>
								<option value="Single">Single</option>
								<option value="Double">Double</option>
							    <option value="Twin">Twin</option>
 <option value="Quad">Quad</option>
								<option value="Triple">Triple</option>
							</select>
							
							<?php echo $err_occupany;?>
            			</div>
						
						
						<div class="form-group col-md-3">
							<?php 
								$sqlHot="SELECT id,name FROM ".TBL_HOTEL_ROOM_BLOCK." WHERE id_shop='".$_SESSION['shop']."' && status='1' ";
								$resHot=mysqli_query($connNew,$sqlHot);
							?><?php //echo $row->id_mst_hotel_room_block; ?>
							<label for="block_floor">Block/Floor<font color="#FF0000">*</font></label>
							
							<select class="select2 form-control" id="block_floor" name="block_floor">
							
									<option value="">---SELECT BLOCK---</option> 
									<?php while($objHot=mysqli_fetch_object($resHot)){
										if($_REQUEST['block_floor']==$objHot->id){
											$selected="selected";
										}elseif($row->id_mst_hotel_room_block == $objHot->id){
											$selected = 'selected';
										}
										else{
											$selected="";
										}
										echo "<option ".$selected." value='".$objHot->id."'>".$objHot->name."</option>";
									} ?>
							</select>
							<?php echo $err_room_id;?>
            			</div>
					

						
                        
                        <div class="form-group col-md-3">
              				<label for="room_status">Housekeeping Status <font color="#FF0000">*</font></label>
             				<select class="form-control select2" name="house_keeping_status" id="house_keeping_status">
    <option value="">Select Housekeeping Status</option>
    <option value="1" <?php if (!empty($row->house_keeping_status) && $row->house_keeping_status == '1') echo 'selected'; ?>>Dirty</option>
    <option value="4" <?php if (!empty($row->house_keeping_status) && $row->house_keeping_status == '4') echo 'selected'; ?>>Clean</option>
</select>
							
							<?php echo $err_room_status;?>
            			</div>
						
						<div class="form-group col-md-3">
              				<label for="room_status">Room Status<font color="#FF0000">*</font></label>
             				<select class="form-control select2" name="room_status" id="room_status">
								<option value="">Select Status</option>
								<?php 
								 $sqlHotRoomStatus="SELECT id,name FROM fo_room_status WHERE  status='1' ";
								$resHotRoomStatus=mysqli_query($connNew,$sqlHotRoomStatus);
							?><?php //echo $row->id_mst_hotel_room_block; ?>
									<?php while($objHotRoomStatus=mysqli_fetch_object($resHotRoomStatus)){
										if($_REQUEST['room_status']==$objHotRoomStatus->id){
											$selected="selected";
										}elseif($row->room_status == $objHotRoomStatus->id){
											$selected = 'selected';
										}
										else{
											$selected="";
										}
										echo "<option ".$selected." value='".$objHotRoomStatus->id."'>".$objHotRoomStatus->name."</option>";
									} ?>
							
							</select>
							
							<?php echo $err_room_status;?>
            			</div>
					<!--<div class="form-group col-md-3">
    <label for="management_block">Management Block<font color="#FF0000">*</font></label>
    <select class="form-control select2" name="management_block" id="management_block" onchange="toggleBlockDates();">
    <option value="">Select Management Block</option>
    <option value="Yes" <?php if (!empty($row->management_block) && $row->management_block == 'Yes') echo 'selected'; ?>>Yes</option>
    <option value="No" <?php if (!empty($row->management_block) && $row->management_block == 'No') echo 'selected'; ?>>No</option>
</select>
    <?php echo $err_management_block; ?>
</div>-->
            			<!--<div class="form-group col-md-4">
            				<label for="management_block">Management Block<font color="#FF0000">*</font></label>
              				<input type="text" class="form-control" placeholder="Enter Management Block" id="management_block" name="management_block" value="<?php if($_POST['management_block']) echo $_POST['management_block'];else echo stripslashes($row->management_block);?>">
							<?php echo $err_management_block;?>
            			</div> -->
                        
				<div  class="col-md-4" id="exclusionDatesBox">
							<div class="form-group">
							    <label for="blocked_room_dates">Blocked Dates</label>
							     <input <?php //echo $disabled; ?> class="form-control" type="text" name="daterangemulti" value="">
							     <input type="hidden" id="blocked_room_dates" name="blocked_room_dates" value="<?php echo $row->blocked_room_dates;?>">      
							     <table class="table table-striped" id="recordExeclusion">
							         	<tr style="background-color: #3C8DBC;color:#FFF;">
							         		<th>Date Range</th>
							         		<th>Remove</th>
							         	</tr>
							         	<?php
							         	if(isset($disabled)){
							         		$noaction='style="display:none;"';
							         	}
							         	else{
							         		$noaction='';
							         	}
							         	$incDate=0;
							         	if(isset($_REQUEST['eId']) && $row->blocked_room_dates !=''){
							         		foreach (explode(',',$row->blocked_room_dates) as $index => $range) {
							         				echo '<tr id="date_'.$index.'">
							         						<td>'.$range.'</td>
							         						<td><i '.$disabled1.' onclick="removeThis('.$index.');" '.$noaction1.' class="fa fa-trash"></i></td>
							         						</tr>';
							         				 $incDate++;
							         		}	
							         	}
							         	?>
							    </table>    	  
							</div>
						</div>		
            		</div>
					<script>
document.addEventListener('DOMContentLoaded', function() {
    toggleBlockDates(); // apply status on load
});
</script>
					<script>
function toggleBlockDates() {
    var managementBlock = document.getElementById('management_block').value;
    var exclusionBox = document.getElementById('exclusionDatesBox');
    var inputs = exclusionBox.querySelectorAll('input, button, select, textarea, i');
// Disable all inputs inside exclusion box
        exclusionBox.style.pointerEvents = 'none';
        exclusionBox.style.opacity = '0.5'; // make it look disabled
        inputs.forEach(function(input) {
            input.disabled = true;
        });
    
}
</script>
					
            		<div class="row">
            			<div class="form-group col-md-12">
                 			<label for="description">Description</label>
				   			<textarea class="ckeditor" id="description" name="description" rows="10" cols="80"><?php if($_POST) echo $_POST['description'];else echo stripslashes($row->description);?></textarea>
                  
							<?php echo $err_description;?>
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
                	<?php if($row->date_created){?>
                	<div class="row">
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
                	</div>
                	<?php } ?>
				</div>
				<div class="box-footer">
					<!--<input type='submit' value='<?=($_REQUEST['id']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" > -->
					<input type='submit' value='<?php if($_REQUEST['id']=='') echo 'Add'; else if($_REQUEST['id'] != '' && $_REQUEST['date'] == 'adddate') echo 'Add'; else echo 'Edit'  ?>' class="btn btn-primary" name="Save" >
					&nbsp;&nbsp;&nbsp;&nbsp;
			   		<input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageRoomNo.php?eId=<?php echo $_GET['eId']; ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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

<script type="text/javascript">
	var exeCount=<?php echo $incDate;?>;

	var exeArray=[];
	<?php
		if(isset($_REQUEST['eId'])){ ?>
		exeArray=<?php echo json_encode(explode(',',$row->blocked_room_dates)); ?>
	
	<?php	}
	?>
			
$(function() {
   // var exeCount = 0;
    //var exeArray = [];

    var daterangeInput = $('input[name="daterangemulti"]');

    daterangeInput.daterangepicker({
        locale: {
            format: 'DD/MM/YYYY'
        }
    });

    daterangeInput.on('apply.daterangepicker', function(ev, picker) {
        // Append row and update array
        $("#recordExeclusion").append('<tr id="date_' + exeCount + '"><td>' + picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY') + '</td><td><i onclick="removeThis(' + exeCount + ');" class="fa fa-trash"></i></td></tr>');
        
        exeArray[exeCount] = picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY');
        $("#blocked_room_dates").val(exeArray);
        exeCount++;

        // Close the calendar
        picker.hide();
    });
});
	
	
	
	function removeThis(val){ //alert(val);
		 $('#date_' + val).remove();         // Remove the row from DOM
    exeArray[val] = '';                 // Clear the value

    // Filter out empty strings
    const filtered = exeArray.filter(date => date && date.trim() !== '');
    
    // Update hidden input with cleaned data
    $("#blocked_room_dates").val(filtered.join(','));
	 
	}
</script>

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
	
	function audittrial(clicked_value){
		//alert(clicked_value);
		//var id = document.getElementById('id_mst_hotels').value;
		$('#auditModal').modal('show');
		var table ='mst_roomno';
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


