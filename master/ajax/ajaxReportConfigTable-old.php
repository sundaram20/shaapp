<?php
	include_once("../../config/auto_loader.php");

	$sqlQuery = "DESCRIBE " . $_POST['table_name']; 
	$table_name = $_POST['table_name'];
	$resShowTable = mysqli_query($connNew,$sqlQuery);


	$return = '<table  class="table table-bordered  text-center text-white">
					<thead>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th colspan="9" ><h4>' . strtoupper($_POST['table_name']) . '</h4></th>
						</tr>
						<tr  style="background-color:#3C8DBC;color:#fff;">
							<th rowspan="2" style="width:5%;vertical-align: middle;">S.No.</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Name</th>
							<th rowspan="2"  style="width:10%;vertical-align: middle;" >Label</th>
							
							<th colspan="7" style="width:75%;">Report Config</th>
						</tr>
						
						<tr style="background-color:#252525;color:#fff;">
							<th style="width:10%;">Display Order
							</th>
							<th style="width:7%;">Enabled Order
							</th>
							<th style="width:7%;">Display
							</th>
							<th style="width:7%;">Default Select
							</th>
						</tr>
					</thead><tbody>';	
	$sno = 0;
	while($rowShowTable = mysqli_fetch_object($resShowTable)){

  		$resCat = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".$_POST['table_name']."' AND id_shop = ". $_SESSION['shop']." AND field_name = '".$rowShowTable->Field."' Order By display_order DESC");

		 if(mysqli_num_rows($resCat)){

		 	$resultCat = mysqli_fetch_object($resCat);
		 	$fieldId = $resultCat->id;
		 	$field_label = $resultCat->field_label;
		 	$display_order = $resultCat->display_order;
		 	//$enabled_order = $resultCat->enable_order_by;
		 	$display = $resultCat->display;
		 	if($display==1){
		 		$displaychecked = "checked";
		 	}else{
		 		$displaychecked = "";
		 	}
		 	if($resultCat->default_select==1){
		 		$defaultchecked = "checked";
		 	}else{
		 		$defaultchecked = "";
		 	}		
		 	if($resultCat->enable_order_by==1){
		 		$enabled_order = "checked";
		 	}else{
		 		$enabled_order = "";
		 	}	
		 	
		 }else{
		 	$field_label = "";
		 	$fieldId = "";
		 	$displaychecked = "";
		 	$defaultchecked = "";
		 	$enabled_order = "";
		 	$display_order = "";
		 }	

		 $OnKeyUpOne ="changeDisplay(this.value,'".$rowShowTable->Field."','".$fieldId."')";	
		$return .='<tr>
							<th style="width:5%;">'.($sno+1).'</th>
							<th style="width:25%;">'.$rowShowTable->Field.'</th>
							<input type="hidden" name="listtable['.$rowShowTable->Field.']['.table_field.']" value="'. $rowShowTable->Field . '" id="table_field" />
							<input type="hidden" class="form-control" name="listtable['.$rowShowTable->Field.']['.fieldId.']" value="'.$fieldId.'"/>
							<th style="width:25%;"><input type="text" class="form-control" name="listtable['.$rowShowTable->Field.']['.field_label.']" value="'.$field_label.'"/></th>
							<th style="width:6%;"><input type="number" class="form-control" name="listtable['.$rowShowTable->Field.']['.display_order.']"  value="'.$display_order.'" onchange="'.$OnKeyUpOne.'" id="display_order" /></th>
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.enabled_order.']" '.$enabled_order.' /></th>
							<th style="width:13%;" ><input  type="checkbox" value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.display.']" '.$displaychecked.'/></th>
							
							<th style="width:13%;" ><input  type="checkbox"  value="'.$sno.'" name="listtable['.$rowShowTable->Field.']['.default_select.']" '.$defaultchecked.' /></th>
							
						</tr>' ;
		$sno++;	

	}		

	$return .= '</tbody></table>';  

	echo $return ;
	mysqli_close($connNew);
	mysqli_close($appConnect);
?>
