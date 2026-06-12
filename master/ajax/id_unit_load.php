<?php
include_once("../../config/auto_loader.php");

									
				

	$selectnewp="SELECT * FROM ".TBL_ATTRIBUTES." where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='unit' ORDER BY `field_value` ";
				 
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				$dataArr[] =  '<option value="">Select Unit</option>';
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					
					$dataArr[] =  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->field_value.'</option>';
				} 

	echo json_encode($dataArr);
	
?>