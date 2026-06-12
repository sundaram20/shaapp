<?php  
$DB_HOST                        = "ls-b2e60044536f2eec0addbe53dd9287ba11700950.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
$DB_NAME	=	'krk-ch';
 $DB_USERNAME	=	'krk-ch';
		 $DB_PASSWORD	=	'aQi05y$3';
			
$nconn = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
}else{
	
	echo '22222';}
?>

<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>FS ORDER</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

//print_r($_REQUEST);

if($_REQUEST['submit']	==	'Upload csv'){

print_r($_FILES['fileToUpload']['name']);

//echo $target_dir = $_SERVER['DOCUMENT_ROOT'].'/crs/import/';
	$target_dir = $_SERVER['DOCUMENT_ROOT']."/import/";
	//$target_dir = "/home/admingcs/public_html/cms/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	
	//$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
		
//$csv_file = "fernhillsP01-04-2018.csv";

$csv_file = $_FILES['fileToUpload']['name'];

				$fieldseparator = ",";
				$lineseparator = "\n";
				
				
				$file = fopen($csv_file, "r");
//$sql_data = "SELECT * FROM prod_list_1 ";

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
    //echo "<pre>";print_r($emapData);
    //exit();
    $count++;                                      // add this line

    if($count>1){   
	
	
	  echo $purch_id=$emapData[0]; 
	  $item_description=$emapData[3]; 
	  $update_id=$emapData[5]; 
	 
	 
	 
   
			
			
	echo "<br>";
	
	

	echo $query4 = 	"SELECT id,id_pos_purch,id_mst_items,item_description,item_amount,date_created FROM `pos_purch_details` WHERE `id_mst_items` > '573'  
	  and id='".$purch_id."'  ";
	$result42 		= mysqli_query($nconn,$query4);
	echo '=='.$CheckResultRow	= mysqli_num_rows($result42);
	if($CheckResultRow	==1){
	
	$query4data = mysqli_fetch_array($result42);
	
	
//echo "UPDATE  fs_orders  SET  type='L' WHERE other_reference='".$bookingnumber."' and id_shop ='".addslashes($_SESSION['shop'])."'";

echo "Record Count=".$count."   Insert Value = ".$updateprice = mysqli_query($nconn,"UPDATE `pos_purch_details` SET `id_mst_items` = '".$update_id."' WHERE `pos_purch_details`.`id` = '".$purch_id."'");
  //  executeSql
	}
	
		
			
			
				
		
	

		
		
    }                                             
}
echo "Sucessful";

}





?>