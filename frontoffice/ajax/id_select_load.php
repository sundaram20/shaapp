<?php
include_once("../../config/auto_loader.php");

$room=$_POST['noofRooms'];
$id_room=$_POST['id_room'];
$i=1;
	$selectneww="SELECT * FROM ".TBL_ROOM_ALLOCATION." where id_mst_room_types=$id_room " ;
	$resneww = mysqli_query($connNew,$selectneww);
	while($rowneww = mysqli_fetch_object($resneww)){
		   
		if($room >= $i){
			$dataArr[] =  '<div class="col-md-2 col-sm-2"><button class="btn btn-danger btn-sm" style="padding:5px 23px;margin-bottom:15px;" onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></div>';
		}
		else{
	    $dataArr[] =  '<div class="col-md-2 col-sm-2"><button class="btn btn-success btn-sm" style="padding:5px 23px;margin-bottom:15px;" onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></div>';
		}
		$i++;
				}	

	echo json_encode($dataArr);
	
 ?>


