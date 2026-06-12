<?php
	$target_dir = "../creditform/";
	$randName = rand(10000,50000);
	$saveName = $randName.basename($_FILES["creditImport"]["name"]);
	$target_file = $target_dir .$saveName;
	// Check file size
	if ($_FILES["creditImport"]["size"] < 500000) {
	    $data = ['File should be less than 5MB'];
	    echo json_encode($data);
	}
	else {
	    if (move_uploaded_file($_FILES["creditImport"]["tmp_name"],$target_file)) {
	    	$data = array();
	    	$data = ['The file has been uploaded successfully',$saveName];
	    	$data['filename'] = $saveName;
	    	$data['target_url'] = $target_file;
	        echo json_encode($data);
	    } else {
	    	$data = ['Error While Uploading !'];
	        echo json_encode($data);
	    }
	}
	//echo $_FILES["creditImport"]["name"];

?>