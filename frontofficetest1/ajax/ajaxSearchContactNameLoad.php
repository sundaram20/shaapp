<?php
include_once("../../config/auto_loader.php");



		
	

		   $selectnew = "select *  from ".TBL_COMPANY_CONTACTS." where status='1'  and first_name !='' and `id` = '".$_REQUEST['res_bookerName']."'";

		//$romm .= '<option>Select Booker Name</option>';

		$resnew = mysqli_query($connNew,$selectnew);
		
		
		$rownew = mysqli_fetch_object($resnew);

				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$rownew->id_mst_attributes_title."'"); 


			$Name	=	$Title.ucwords($rownew->first_name).' '.ucwords($rownew->last_name);
			
	echo	'<option value="'.$rownew->id.'" selected="selected">'.$Name.' - '.ucwords($rownew->email).' - '.ucwords($rownew->primary_mobile).'</option>';
			
			
		

	
 ?>


