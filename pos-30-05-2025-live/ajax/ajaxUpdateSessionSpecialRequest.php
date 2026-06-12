<?php include_once("../../config/auto_loader.php"); ?>

<?php

		$uniqueCode=$_REQUEST['specialRequestUniqueCode'];
		$special_request_name=$_REQUEST['special_request_name'];
		$UniqueCodeGen=$_REQUEST['UniqueCodeGen'];
		$_SESSION['POSKOT'][$UniqueCodeGen]['special_request_name'][$uniqueCode]=$special_request_name;
//debugData($_SESSION);
//die;





?>
