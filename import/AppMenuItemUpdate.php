
<?php
//include_once("../config/fron_autoload.php"); 
//$DB_HOST="localhost:3306 (MariaDB)";
$DB_HOST                        = "ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";

$DB_USERNAME='tchssl';
$DB_PASSWORD='K7leo!20';
echo 'Database Name: '.$DB_NAME='tch_ssl';
$connNew2=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];
$LIB_DIR      = "$DOCUMENT_ROOT$MAP_VROOT_PATH/phplib";



include("$LIB_DIR/imageprocess.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/roomstatus.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/PHPMailer/PHPMailerAutoload.php");
include("$LIB_DIR/admin.pagingClass.php");
include("$LIB_DIR/dompdf/dompdf_config.inc.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include("$LIB_DIR/class.mailer.php");
$DB_REPORT_ERROR                = true;                        // To Report Error
$DB_PERSISTENT_CONN             = false; 

$connNew = $connCrs = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);




?>
<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>Item Import</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

if($_REQUEST['submit']	==	'Upload csv'){


	 $target_dir = $_SERVER['DOCUMENT_ROOT']."/import/";

	//$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
	$csv_file = $_FILES['fileToUpload']['name'];
	$fieldseparator = ",";
	$lineseparator = "\n";
	$file = fopen($csv_file, "r");
	$count = 1;                                         // add this line
	
	if(!file_exists($csv_file)) {
			echo "File not found. Make sure you specified the correct path.\n";
			exit;
		}		
		$file = fopen($csv_file,"r");		
		if(!$file) {
			echo "Error opening data file.\n";
			exit;
		}		
		$size = filesize($csv_file);		
		if(!$size) {
			echo "File is empty.\n";
			exit;
		}
	$CountInc=1;
	while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
		{    
    	$count++;                                      // add this line
//echo "<pre>";print_r($emapData);
   
    	if($count>=1){   
	
				$itemCode=$emapData[0]; 
				$ItemName=$emapData[1]; 
				
				$id_mst_attributes_group_main=$emapData[3]; 
			$id_mst_attributes_group_sub =$emapData[4]; 
					

			
			
 echo  $queryCompany = "SELECT item_code,name FROM inv_items WHERE name LIKE '%".$ItemName."%' and item_code='".$itemCode."'" ;


$resultCompany = mysqli_query($connNew2,$queryCompany);
 $NumberCompany = mysqli_num_rows($resultCompany);

if($NumberCompany=='1'){		
			 echo $addNewCompanyName ="UPDATE  `inv_items` SET 
			
							 `id_mst_attributes_group_main`='".$id_mst_attributes_group_main."',
							`id_mst_attributes_group_sub`='".$id_mst_attributes_group_sub."',
							 `last_modified`='".currenDateTime()."',
							 
							 `id_mst_user_modified_by`='10' 
							 
							 WHERE name LIKE '%".$ItemName."%' and item_code='".$itemCode."' ";
											 
											 
											
//echo '<br><br><br>'.$addNewCompanyName;
	  
			//die;
								$InsertSucess	=	 mysqli_query($connNew2,$addNewCompanyName);
								if($InsertSucess==1){
								echo $count.' - Sucessful Record <br>';
								}else{
								echo '<p style="color:red;font-weight:bold;">Error'.$ItemName.'</p><br>';
								}


	
	
}else{
echo '<p style="color:Green;font-weight:bold;">ItemName Already Exist.'.$ItemName.'</p><br>';
}
		
    }                                             
}
echo "Sucessful";

}


?>