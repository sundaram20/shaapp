<?php
	
include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

$connMenu = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,'app');

if(!isset($_REQUEST['del'])){
	$sqlMenu ="UPDATE ".APP_SUB_MENU." SET ".$_REQUEST['filed']."='".$_REQUEST['value']."' WHERE id='".$_REQUEST['id']."' " ;


	if(mysqli_query($connMenu,$sqlMenu)){
		echo "Updated Successfully";	
	}
	else{
		echo "failed to update";
	}
}
else{
	$sqlMenu ="DELETE FROM ".APP_SUB_MENU."  WHERE id='".$_REQUEST['id']."' " ;


	if(mysqli_query($connMenu,$sqlMenu)){
		echo "Deleted Successfully";	
	}
	else{
		echo "failed to delete";
	}
}	

?>