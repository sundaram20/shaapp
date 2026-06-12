<?php
include_once("../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	if($_REQUEST['areaId'] !=0 && $_REQUEST['areaId']!=''){
		$sql="SELECT B.name FROM `fs_areas_assign` AS A
			  LEFT JOIN `fs_users` AS B ON A.user_id=B.id
			  WHERE A.id='".$_REQUEST['areaId']."' ";
		$res = mysqli_query($conn,$sql);
		
		if($res){
			$data = mysqli_fetch_object($res);
			if($data->name==""){
				echo "Executive not assigned";
			}
			else{
				echo "Sales Executive : ".$data->name;
			}
		}	  
	}
	else{
		echo "";
	}

?>