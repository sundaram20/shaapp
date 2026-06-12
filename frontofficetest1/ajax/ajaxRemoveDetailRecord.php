<?php
include_once("../../config/auto_loader.php");

	//debugData($_REQUEST);//die;
	$uncode	= $_REQUEST['uncode'];
	
	
	$id_reservation_detail	= $_REQUEST['id_reservation_detailArray_'.$uncode];
$array	=explode(',',$id_reservation_detail);
	foreach($array as $reservationRoomType){
		
		
		//foreach($id_res as $reservationRoomType){
	//debugData($id_res);
	
	  $Sql = "DELETE FROM `fo_reservations_details` WHERE `id` = '".$reservationRoomType."'";
	 mysqli_query($connNew,$Sql);
	
		//}
		
		
		}
	
		echo json_encode($dataArray);
 ?>



