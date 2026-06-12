<?php
//include_once("../config/fron_autoload.php"); 
//$DB_HOST="localhost:3306 (MariaDB)";
//$DB_HOST                        = "ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
$DB_HOST                        = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";

$DB_USERNAME='unit_rnp';
$DB_PASSWORD='461mef^F8';
echo 'Database Name: '.$DB_NAME='unit_rnp';


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

//$connNew = $connCrs = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


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
				$id_mst_attributes_group_sub=$emapData[4];  
				
				$id_mst_attributes_unit_main=$emapData[5];	 
				$id_mst_attributes_unit_alt=$emapData[6]; 				
				
			
  $sqlgroup_main = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE   status='1' AND table_name='item_group_main' AND  LOWER(field_value) LIKE '%".strtolower($emapData[3])."%' ";
	 $resTogroup_main = mysqli_query($connNew2,$sqlgroup_main);
 	 
	   $rowgroup_main =  mysqli_fetch_object($resTogroup_main);
		$id_mst_attributes_group_main= $rowgroup_main->id;


	 $sqlgroup_sub = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE   status='1' AND table_name='item_group_sub' AND  LOWER(field_value) LIKE '%".strtolower($emapData[4])."%' ";
	 $resTogroup_sub = mysqli_query($connNew2,$sqlgroup_sub);
 	
	   $rowgroup_sub =  mysqli_fetch_object($resTogroup_sub);
		$id_mst_attributes_group_sub= $rowgroup_sub->id;



	 $sqlunitMain = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE   status='1' AND table_name='unit' AND  LOWER(field_value) LIKE '%".strtolower($emapData[5])."%' ";
	 $resTounitMain = mysqli_query($connNew2,$sqlunitMain);
 	 
	   $rowunitMain =  mysqli_fetch_object($resTounitMain);
		$id_mst_attributes_unit_main= $rowunitMain->id;



	 $sqlunitSub = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE  status='1' AND table_name='unit' AND  LOWER(field_value) LIKE '%".strtolower($emapData[6])."%' ";
	 $resTounitSub = mysqli_query($connNew2,$sqlunitSub);
 		   $rowunitSub =  mysqli_fetch_object($resTounitSub);
		$id_mst_attributes_unit_alt= $rowunitSub->id;
			//print_r($rowunitSub);
	            $conversion_qty=$emapData[7]; 		
	            $store='860';//$emapData[11]; 								
	            $itemMenuType='17'; // 17 is Ingredients
			$min_qty=$emapData[15]==''?'0.00':$emapData[15]; 	
			$max_qty=$emapData[16]==''?'0.00':$emapData[16]; 	
			
  $queryCompany = "SELECT item_code,name FROM inv_items WHERE name LIKE '%".$ItemName."%' and item_code='".$itemCode."' " ;


$resultCompany = mysqli_query($connNew2,$queryCompany);
 $NumberCompany = mysqli_num_rows($resultCompany);
			
			if($NumberCompany>0){
			
			}else{
			echo '<br><br><br><p style="color:red;font-weight:bold;">ItemCode Not Exist Error'.$itemCode.'================'.$ItemName.'</p><br>';
			
			}
			

if(($NumberCompany=='0' || $NumberCompany=='') && $id_mst_attributes_group_main>0 && $id_mst_attributes_group_sub>0 && $id_mst_attributes_unit_main>0 && $id_mst_attributes_unit_alt>0 ){		
			$addNewCompanyName ="INSERT INTO `inv_items` SET 
							 `item_code`='".$itemCode."',
							 `name`='".$ItemName."',
							 `id_mst_attributes_item_type`='".$itemMenuType."',
							 `id_mst_attributes_group_main`='".$id_mst_attributes_group_main."',
							 `id_mst_attributes_group_sub`='".$id_mst_attributes_group_sub."',
							 `id_mst_attributes_unit_main`='".$id_mst_attributes_unit_main."',
							 `id_mst_attributes_unit_alt`='".$id_mst_attributes_unit_alt."',
							 `id_mst_charges_sales_local`='0',
							 `id_mst_charges_sales_interstate`='0',
							 `id_mst_charges_purchase_local`='0',
							 `id_mst_charges_purchase_interstate`='0',
							 `id_mst_attributes_store`='".$store."',
							 `id_mst_attributes_printer`='0',
							 `ids_mst_outlet`='0',
							 `conversion_qty`='".$conversion_qty."',
							 `min_qty`='".$min_qty."',
							 `max_qty`='".$max_qty."',
							 `rol`='0.00',
							 `roq`='0.00',
							 `item_class`='A',
							 `bal_qty`='0.00',
							 `open_qty`='0.00',
							 `open_amount`='0.00',
							 `last_purchase_rate`='0.00',
							 `item_enable_desc_billing`='0',
							 `stockable_enable_disable`='0',
							 `edit_name_enable_disable`='0',
							 `item_get_expiry_details`='0',
							 `item_production_item`='0',
							 `item_allow_additional`='0',
							 `item_disable`='0',
							 `sale_rate`='0',
							 `purchase_rate`='0.00',
							 `batch_details`='0',
							 `item_details`='1',
							 `display_order`='0',
							 `item_image`='',
							 `id_shop`='2',
							 `status`='1',
							 `deactivate_date`='',
							 `date_created`='".currenDateTime()."',
							 `last_modified`='".currenDateTime()."',
							 `id_mst_user_created_by`='10',
							 `id_mst_user_modified_by`='10'";
											 
											 
											
echo '<br><br><br>'.$addNewCompanyName;
	  
//			die;
								$InsertSucess	=	 mysqli_query($connNew2,$addNewCompanyName);
								if($InsertSucess==1){
								echo $count.' - Sucessful Record <br>';
								}else{
				echo '<br><br><br><p style="color:red;font-weight:bold;">Error'.$itemCode.'================'.$ItemName.'</p><br>';
								}


	
	
}else{
					echo '<p style="color:Green;font-weight:bold;">ItemName Already Exist.'.$ItemName.'</p><br>';
}
		
    }                                             
}
echo "Sucessful";

}


?>