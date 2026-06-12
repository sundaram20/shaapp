<?php
include_once("../../config/auto_loader.php");

			  $id=$_POST['id'];

				$selectnew="SELECT * FROM ".TBL_ATTRIBUTES."  where id=$id";
				$resnew = mysqli_query($connNew,$selectnew);
				$rownew = mysqli_fetch_object($resnew);
					
					$romm[] = $rownew->field_value; 
		
							
				

	echo json_encode($romm);
 
	

	
 ?>


