<?php
include_once('../config/fron_autoload.php');



/****** FILE UPDATION START ***/
$target_dir = "migrations/";
$randName = 'migration_'.date('d-m-Y').'_'.$_SESSION['app_id_user'];
$ext = '.'.pathinfo(basename($_FILES["migrationFile"]["name"]), PATHINFO_EXTENSION);
$saveName = $randName.$ext;

$target_file = $target_dir.$saveName;

if(file_exists($target_file)){
	unlink($target_file);
}

if (move_uploaded_file($_FILES["migrationFile"]["tmp_name"],$target_file)) {
   	//uploaded successfully
}
else {
   	$data = 'ERROR WHILE UPLOADING FILE !';
    echo json_encode($data);
    exit;
}
/****** FILE UPDATION END ***/

$updateData['id_app_user']=$_SESSION['app_id_user'];
$updateData['migrated_at']=date('Y-m-d H:i:s');
$updateData['title']=$_POST['title'];
$updateData['description']=$_POST['description'];
$updateData['file_name']=$saveName;

insertData(APP_MIGRATIONS,$updateData,$appConnect);

if(file_exists($target_file)){
	
	include_once($target_file);
	
	$sqlDb = "SELECT * FROM ".APP_SHOP." ";
	$resDb = executeQuery($sqlDb,$appConnect);
	while($rowDb = mysqli_fetch_object($resDb)){

		$newConn = @mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $rowDb->database);

		if($newConn){

			foreach ($migrate as $index => $query) {

				$flag = mysqli_query($newConn,$query);

				if(mysqli_errno($newConn)==0 || mysqli_errno($newConn)==1060 || mysqli_errno($newConn)==1050 || mysqli_errno($newConn)==1146){
					//0 NO ERROR
					//1060 DUPLICATE COLUMN
					//1050 DUPLICATE TABLE
					//1146 TABLE NOT FOUND
				}
				else{
					$data = "Failed ! Error In Query".mysqli_errno($newConn);
					echo json_encode($data.mysqli_error($newConn));
					exit;
				}
			}
			mysqli_close($newConn);
		}
		else{
			$data = $rowDb->database.' Databsase Not Found!';
			echo json_encode($data);
			exit;
		}
		
	}
	$data = 'Migration Successfully !';
	echo json_encode($data);	
}
else{
	$data = 'Migration File Not Found !';
    echo json_encode($data);
    exit;
}


?>