<?php
include_once("../../config/auto_loader.php");

$period = $_REQUEST['effective_date'];

$dateArr = explode(' to ',$period);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$i=0;

if($from !='' && $to !=''){
	while(strtotime($from)<=strtotime($to)){
		$chkExisting=selectColumn(FO_INVENTORY,'id','WHERE `id_mst_hotels`="'.$_REQUEST['id_hotel'].'" AND id_mst_room_types="'.$_REQUEST['id_room'].'" AND  allocation_date="'.$from.'" ');
		
		if($chkExisting>0){
			$online_inventory=$_REQUEST['online_inventory'];
			$insertGrid = "UPDATE  ".FO_INVENTORY." 
						SET `crs_available`='".$online_inventory."',
							`last_modified`='".date('Y-m-d H:i:s')."',
							`id_mst_user_modified_by`='".$_SESSION['userId']."',
							`status`='".$_REQUEST['status']."'
						 ";
		 	$insertGrid.='WHERE `id_mst_hotels`="'.$_REQUEST['id_hotel'].'" AND id_mst_room_types="'.$_REQUEST['id_room'].'" AND  allocation_date="'.$from.'"';			  

			mysqli_query($connNew,$insertGrid);
			$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
		}
		else{
			$online_inventory=$_REQUEST['online_inventory'];
			

			 $insertGrid = "INSERT INTO ".FO_INVENTORY." 
						SET `allocation_date`='".$from."',
						`id_mst_hotels`='".$_REQUEST['id_hotel']."',
						`id_mst_room_types`='".$_REQUEST['id_room']."',
						`id_shop`='".$_SESSION['shop']."',
						`crs_available`='".$online_inventory."',
						`date_created`='".date('Y-m-d H:i:s')."',
						`last_modified`='".date('Y-m-d H:i:s')."',
						`id_mst_user_created_by`='".$_SESSION['userId']."', 
						`id_mst_user_modified_by`='".$_SESSION['userId']."',
						 `status`='".$_REQUEST['status']."' ";

			mysqli_query($connNew,$insertGrid);			 

			$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
				
		}
			
	}
	echo "Data Submitted Successfully !";
}
else{
	echo "Error In Date";
}
?>