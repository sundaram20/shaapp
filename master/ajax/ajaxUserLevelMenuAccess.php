<?php
include_once("../../config/auto_loader.php");
$return = '<option value="">---Select Menu---</option>';
if($_POST['id_module'] !=''){
	

	$sqlModule = "SELECT * FROM ".APP_MENU." WHERE FIND_IN_SET(".$_POST['id_module'].",ids_module)  AND status=1 ORDER BY display_order";
	$resModule = mysqli_query($appConnect,$sqlModule);
	
	

	while($rowModule = mysqli_fetch_object($resModule)){
		$return .='<option value="'.$rowModule->id.'">'.strtoupper($rowModule->name).'</option>' ;
	}

	

}

echo $return ;
?>