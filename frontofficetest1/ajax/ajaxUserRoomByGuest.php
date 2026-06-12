<?php
	include_once("../../config/auto_loader.php");
	include_once("../functions/function.php");

 $jsondeocde = json_decode($_REQUEST['guest_room'], true);
 //debugData($jsondeocde);
//debugData($_REQUEST);die;
$_REQUEST['guest_room'];
$id_mst_room_types =$_REQUEST['id_mst_room_types'];

foreach($jsondeocde as $roomNumberWiseGuest){
	
	$id_mst_room_no_allocation	= $roomNumberWiseGuest['id_room_no'];
	$id_mst_guest	=  $roomNumberWiseGuest['id_mst_guest'];
	 $insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
	`id_mst_guest`='".$id_mst_guest."'
				 where
				`id_fo_reservations` = '".$_REQUEST['resvId']."' and `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."'
				
				and id_mst_room_types ='".$id_mst_room_types."'
				 ";
				$insertOrder = mysqli_query($connNew,$insertGrid);
				
				$roomNumberWiseGuest['id_folio_guest'];
				
				
				
				
if($roomNumberWiseGuest['id_folio_guest']=='1'){
				
	$sql= "SELECT *  FROM `fo_reservations_details` WHERE `id_fo_reservations` = '".$_REQUEST['resvId']."' and id_mst_room_no_allocation = '".$id_mst_room_no_allocation."' order by dated asc";
	$sqlFolio = mysqli_query($connNew, $sql);

	if (!$sqlFolio) {
		echo "SQL error: " . mysqli_error($connNew);
	}
	
	$rowFolio = mysqli_fetch_object($sqlFolio);
	
	if (!$rowFolio) {
		echo "No record found.";
	} else {
		//debugData($rowFolio);
	}
	 
	//$sqlFolio = mysqli_query($connNew,$sql);	
	//$rowFolio = mysqli_fetch_object($sqlFolio);
	//debugData($rowFolio);
	
	$id_fo_folio_to = $rowFolio->id_fo_folio_to;
	 
	 
	 $room_no	= $roomNumberWiseGuest['id_room_no'];
	 
	 $id_fo_bill	= selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_fo_folio_to."'");
	 
	 $updateFoBill =  "UPDATE `fo_bill` SET `id_owner_room`='".$room_no."' where`id` = '".$id_fo_bill."'";
	 mysqli_query($connNew,$updateFoBill);	
		}
			
	}

		    

//debugData($_REQUEST);die;
	

		//echo "Check-in Processed Successfully";

	