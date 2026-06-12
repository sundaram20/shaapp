<?php
include_once("../../config/auto_loader.php");
$return = '<option value="">---Select Module---</option>';
if($_POST['id_user_level'] !=''){
	
	$sql = "SELECT ids_module_access FROM ".TBL_USER_LEVELS." WHERE id='".$_POST['id_user_level']."' ";
	$res = mysqli_query($connNew,$sql);

	$ids_module = mysqli_fetch_object($res)->ids_module_access;

	$sqlModule = "SELECT * FROM ".APP_MODULE." WHERE id IN (".$ids_module.") AND status=1 ORDER BY display_order";
	$resModule = mysqli_query($appConnect,$sqlModule);
	
	

	while($rowModule = mysqli_fetch_object($resModule)){
		$return .='<option value="'.$rowModule->id.'">'.strtoupper($rowModule->name).'</option>' ;
	}

	

}

echo $return ;
?>