<?php
include_once("../../config/auto_loader.php");

$id_hotel=$_POST['id_hotel'];
$id_room=$_POST['id_room'];



	
	$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel " ;
	$resneww = mysqli_query($connNew,$selectneww);

	$dataArr[] =  '<option value="">Select Type</option>';
	
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
                $selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name;
					$id = $rownew->id;
					
				if($id == $id_room){
						$selected="selected";
					}
					else{
						$selected="";
					}
				$dataArr[] =  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
		
				}	
	}	
	echo json_encode($dataArr);
	
	
	/*
	$selectnewq="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$id_room";
	$resnewq = mysqli_query($connNew,$selectnewq);
				while($rownewq = mysqli_fetch_object($resnewq)){
					$rownewq->name;
					$dataArr[] =  '<option value="" selected="selected">'.$rownewq.'</option>';
					
				}
	
	*/
	
 ?>


