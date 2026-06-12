<?php
include_once("../../config/auto_loader.php");

$id_room=$_POST['id_room'];

					$selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$id_room";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name; 
		
				}	
								
				

	echo json_encode($romm);
 
	

	
 ?>


