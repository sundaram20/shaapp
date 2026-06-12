<?php
include_once("../../config/auto_loader.php");

$id=$_POST['id'];
$autoload=$_POST['autoload'];

				$selectnew="SELECT * FROM ".TBL_COMPANY."  where name like '%".$autoload."%' and id_mst_attributes_company_group=$id  ";
				$resnew = mysqli_query($connNew,$selectnew);
				$output = '<ul class="list-unstyled autoul">';		

  				if ($resnew->num_rows > 0) {
					while($rownew = mysqli_fetch_object($resnew)){ 
						$output .= '<li class="auto">'.ucwords($rownew->name).' - '.ucwords($rownew->city).'</li>';
					}	
				}else{
  			 		$output .= '<li> List not Found</li>';
  				}
				$output .= '</ul>';
	  			echo $output;

 
	

	
 ?>


