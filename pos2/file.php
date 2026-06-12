<?php include_once("../config/fron_autoload.php"); 

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

	echo $target_dir = $_SERVER['DOCUMENT_ROOT'].'/app/pos/import/';
	

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
    	echo "<pre>";print_r($emapData);
    	exit();
    	$count++;                                      // add this line

	    if($count>1){   
			
		}
		
    }                                             
}

?>