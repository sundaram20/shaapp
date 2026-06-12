<?php
	include_once("../../config/auto_loader.php");

	$sqlQuery = "DESCRIBE " . $_POST['table_name']; 
	$resShowTable = mysqli_query($connNew,$sqlQuery);


	$return = '<table  class="table table-bordered  text-center text-white">
					<thead>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th colspan="9" ><h4>' . strtoupper($_POST['table_name']) . '</h4></th>
						</tr>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th rowspan="2" style="width:10%;vertical-align: middle;">S.No.</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Name</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Label</th>
							<th colspan="7" style="width:70%;" >Report Config</th>
						</tr>
						
						<tr style="background-color:#252525;color:#fff;">
							<th style="width:12.5%;" >Display
							</th>
							<th style="width:12.5%;" >Default Select
							</th>
						</tr>
					</thead><tbody>';	
	$sno = 0;
	while($rowShowTable = mysqli_fetch_object($resShowTable)){

		$resCat = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".$_POST['table_name']."' AND id_shop = ". $_SESSION['shop']." AND field_name = '".$rowShowTable->Field."'");

		 if(mysqli_num_rows($resCat)){

		 	$resultCat = mysqli_fetch_object($resCat);
		 	
		 	$field_label = $resultCat->field_label;
		 	$display = $resultCat->display;
		 	if($display==1){
		 		$displaychecked = "checked";
		 	}
		 	$default_select = $resultCat->default_select;
		 	if($default_select==1){
		 		$defaultchecked = "checked";
		 	}		

		 	
		 }else{
		 	$field_label = "";
		 	$displaychecked = "";
		 	$defaultchecked = "";
		 }		
		$return .='<tr>
							<th style="width:5%;">'.($sno+1).'</th>
							<th style="width:26%;">'.$rowShowTable->Field.'</th>
							<input type="hidden" name="formdata['.$sno.'][table_field]" value="'. $rowShowTable->Field . '" />
							<th style="width:29%;"><input type="text" class="form-control" name="formdata['.$sno.'][field_label]" value="'.$field_label.'"/></th>
							<th style="width:20%;" ><input  type="checkbox" value="'.$sno.'" name="formdata['.$sno.'][display]" '.$displaychecked.'/></th>
							<th style="width:20%;" ><input  type="checkbox"  value="'.$sno.'" name="formdata['.$sno.'][default_select]" '.$defaultchecked.'/></th>
							
						</tr>' ;
		$sno++;	

	}		
	$return .= '</tbody></table>';  

	echo $return ;
	mysqli_close($connNew);
	mysqli_close($appConnect);
?>
