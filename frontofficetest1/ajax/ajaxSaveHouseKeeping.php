<?php
include_once("../../config/auto_loader.php");

$room_type = $_REQUEST['room_type'];
$room_no = $_REQUEST['room_no'];
$room_status = $_REQUEST['room_status'];
//$last_cleaned = $_REQUEST['last_cleaned'];
$last_cleaned = date('d-m-Y', strtotime( $_REQUEST['last_cleaned'] ));
$last_cleaned_time = $_REQUEST['last_cleaned_time'];
$executive = $_REQUEST['executive'];
$activity = $_REQUEST['activity'];
$remarks = $_REQUEST['remarks'];
 
 $id=$_REQUEST['id']; 
	
 if($room_status==1){
	 $status = "Dirty";
 }else if($room_status==2){
	 $status = "Reserved";
 }else if($room_status==3){
	 $status = "Occupied";
 }else if($room_status==4){
	 $status = "Cleaned";
 }else if($room_status==5){
	 $status = "Blocked";
 }else if($room_status==6){
	 $status = "Under Maintenance";
 }

$sql = "SELECT * FROM ".FO_HOUSE_KEEPING." WHERE id_mst_room_allocation='$id' ";
$res = mysqli_query($connNew,$sql);
$rowcount=mysqli_num_rows($res);
while($row = mysqli_fetch_object($res)){
 $idd1 = $row -> id;
 $executive1 = $row -> executive;
 $date1 = $row -> date;
 $time1 = $row -> time;
 $status_update1 = $row -> status_update;
 $act = $row -> activity;
 $remark = $row -> remarks;
 
  if($executive1 != $executive){
	 $exe = "Executive Details Changed from ".  $executive1 ." - to - " . $executive;
	}
	if($date1 != $last_cleaned){
		 $dat ="Date Details Changed from " .  $date1." - to - ".$last_cleaned;
	}
	if($time1 != $last_cleaned_time){
		 $tim ="Time Changed from " .   $time1 ." - to - " . $last_cleaned_time;
	} 
	if($status_update1 != $status){
		$stat ="Status Details Changed from " .   $status_update1 ." - to - " . $status;
	} 
	if($act != $activity){
		$stat ="Activity Details Changed from " .   $act ." - to - " . $activity;
	} 
	if($remark != $remarks){
		$stat ="Remarks Details Changed from " .   $remark ." - to - " . $remarks;
	} 
	
}

$sql1 = " SELECT * FROM `".FO_HOUSE_KEEPING."` ORDER BY id DESC LIMIT 1";
		while($row1 = mysqli_fetch_object($sql1)){
			$idd = $row1 -> id;
			 if($idd == '0'){
				 $last_id = '1';
			 }else{
			     $last_id =  $idd + 1;
			 }
		 }




if($rowcount){
	    $insertGrid1 = "UPDATE ".FO_HOUSE_KEEPING." SET
				`executive`='".$executive."',
				`date`='".$last_cleaned."',
				`time`='".$last_cleaned_time."',
				`status_update`='".$status."' ,
				`activity`='".$activity."',
				`remarks`='".$remarks."' ";
				$insertGrid1 .=" WHERE id_mst_room_allocation='$id'";
		mysqli_query($connNew,$insertGrid1); 
	
}else{
	
	 $insertGrid1 = "INSERT ".FO_HOUSE_KEEPING." SET
				`id_mst_room_allocation`='".$id."',
				`executive`='".$executive."',
				`date`='".$last_cleaned."',
				`time`='".$last_cleaned_time."',
				`status_update`='".$status."' ,
				`activity`='".$activity."',
				`remarks`='".$remarks."' ";
		mysqli_query($connNew,$insertGrid1); 

		$insertGrid2 = "  INSERT INTO audit_trail SET
							`voucher_id` = '".$last_id."',
							`tables_name` = 'fo_house_keeping',
							`form_code` = 'house_keeping_form',
							`changes` = 'No Changes',
							`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`type` = 1 ";	
		mysqli_query($connNew,$insertGrid2); 
		
}

        $insertGrid3 = " INSERT audit_trail SET 
						`voucher_id` = '".$idd1."',
						`tables_name` = 'fo_house_keeping',
						`form_code` = 'house_keeping_form',
						`changes` =  '".addslashes($exe).",".addslashes($dat).",".addslashes($tim).",".addslashes($stat)."',
						`date_created` = '".currenDateTime()."',
						`last_modified` = '".currenDateTime()."',
						`id_mst_user_modified_by` = '".$_SESSION['userId']."',
						`id_mst_user_created_by` = '".$_SESSION['userId']."',
						`type` = 2 ";
		mysqli_query($connNew,$insertGrid3); 	
					
        $insertGrid = "UPDATE ".TBL_ROOMNO." SET
				`room_status`='".$room_status."' ";
		$insertGrid .=" WHERE id = '$id' ";	
		 mysqli_query($connNew,$insertGrid);


 
	echo json_encode($data);

	
 ?>


