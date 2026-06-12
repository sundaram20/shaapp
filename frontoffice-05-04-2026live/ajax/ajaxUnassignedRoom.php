<?php
	include_once("../../config/auto_loader.php"); 
	extract($_POST);
	if($Id == encryptor(encrypt,'12')){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
			array("room_id"=>"2","RoomName"=>"Suit Room","RoomDetails"=>array("201","202","203","204")),
		);
	}else if($Id == 2){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
		);
	}else if($Id == 3){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
			array("room_id"=>"3","RoomName"=>"Super Delux Room","RoomDetails"=>array("301","302","303","304")),
		);
	}else if($Id == encryptor(encrypt,'21')){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
			array("room_id"=>"2","RoomName"=>"Suit Room","RoomDetails"=>array("201","202","203","204")),
		);
	}else if($Id == 5){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
			array("room_id"=>"2","RoomName"=>"Suit Room","RoomDetails"=>array("201","202","203","204")),
		);
	}else if($Id == 6){
		$roomTypes = array(
			array("room_id"=>"1","RoomName"=>"Delux Room","RoomDetails"=>array("101","102","103","104")),
			array("room_id"=>"2","RoomName"=>"Suit Room","RoomDetails"=>array("201","202","203","204")),
		);
	}
	
	echo json_encode($roomTypes);
?>