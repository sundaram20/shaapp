<?php
	
include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");

$connMenu = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,'app');



if($_REQUEST['id_module']!=""){
	$sqlMenu ="SELECT  *  FROM ".APP_MENU." WHERE FIND_IN_SET(".$_REQUEST['id_module'].",ids_module) order by display_order " ;
    $resMenu=mysqli_query($connMenu,$sqlMenu);
    echo "<option value=''>---Select Menu---</option>";
    while($rowMenu = mysqli_fetch_object($resMenu)){
    	echo "<option value='".$rowMenu->id."'>".$rowMenu->name."</option>";
    }	
}
else{
	echo "<option value=''>---Select Menu---</option>";
}

?>