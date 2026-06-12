<?php
include_once("../../config/auto_loader.php");

$id=$_POST['id'];

				$selectnew="SELECT * FROM ".TBL_COMPANY_CONTACTS."  where id_mst_company=$id";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->first_name; 
					$romm = $rownew->email; 
					$romm = $rownew->primary_mobile; 
		
				}	
								
				

	echo json_encode($romm);
 
	

	
 ?>


