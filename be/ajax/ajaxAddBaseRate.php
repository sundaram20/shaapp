<?php
include_once("../../config/auto_loader.php");


$dateArr = explode(' to ',$_REQUEST['effective_date']);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$i=0;

$chkExisting = selectColumn(TBL_BASE_RATE,'count(id)','WHERE   id_shop="'.$_SESSION['shop'].'" AND id_hotel="'.$_REQUEST['id_hotel'].'" AND id_room_plan_link="'.$_REQUEST['id_link'].'" AND effective_date BETWEEN "'.$from.'" AND "'.$to.'" ');

if($chkExisting>0){
	echo "Data Already Exists For This Period ! Please Use Edit option.";
	exit;
}
else{
	while(strtotime($from)<=strtotime($to)){

		$singleGrid=$_REQUEST['single_pax_price'];
		$doubleGrid=$_REQUEST['double_pax_price'];
		$extraBedGrid=$_REQUEST['extra_bed_price'];
		$extraChildGrid=$_REQUEST['extra_child_price'];
		$minStayGrid=0;
		$maxStayGrid=0;
		$dateGrid=$from;

		$insertGrid = "INSERT INTO ".TBL_BASE_RATE." 
					SET `effective_date`='".$dateGrid."',
					`id_hotel`='".$_REQUEST['id_hotel']."',
					`id_room_plan_link`='".$_REQUEST['id_link']."',
					`id_shop`='".$_SESSION['shop']."',
					`id_season`='0',
					`single_pax_price`='".$singleGrid."',
					`double_pax_price`='".$doubleGrid."',
					`extra_bed_price`='".$extraBedGrid."',
					`extra_child_price`='".$extraChildGrid."',
					`min_stay`='".$minStayGrid."',
					`max_stay`='".$maxStayGrid."',
					`date_created`='".date('Y-m-d H:i:s')."',
					`last_modified`='".date('Y-m-d H:i:s')."',
					`id_mst_user_created_by`='".$_SESSION['userId']."', 
					`id_mst_user_modified_by`='".$_SESSION['userId']."',
					 `status`='1' ";

		mysqli_query($connNew,$insertGrid);			 

		$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
	}
	echo "Data Submitted Successfully !";	
}

?>