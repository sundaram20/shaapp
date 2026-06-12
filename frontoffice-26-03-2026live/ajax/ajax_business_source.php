<?php
include_once("../../config/auto_loader.php");

		$key=$_GET['q'];
		 
		$selectnew = "select *  from ".TBL_ATTRIBUTES." where status='1' and id_shop='".addslashes($_SESSION['shop'])."'  AND table_name='company_group'  and field_value !='' AND field_value LIKE '{$key}%' order BY field_value LIMIT 0,20";
		$romm[] = '<option>Select '.$title.' Name</option>';
		$resnew = mysqli_query($connNew,$selectnew);

			while($rownew = mysqli_fetch_object($resnew)){  
				$romm[] = array('id'=>$rownew->id, 'text'=>$rownew->field_value );
			}	
					
	echo json_encode($romm);
	
 ?>


