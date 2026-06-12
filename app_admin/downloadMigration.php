<?php
include_once('../config/fron_autoload.php');
$file = selectField(APP_MIGRATIONS,'file_name','WHERE id=(select MAX(id) FROM '.APP_MIGRATIONS.') ');

if($file !=''){
	$file = 'migrations/'.$file;
	if(!file_exists($file)){ // file does not exist
	    echo "File Not Found";
	} else {
	    header("Cache-Control: public");
	    header("Content-Description: File Transfer");
	    header("Content-Disposition: attachment; filename=migration_".date('d-m-Y H:i:s').".php");
	    header("Content-Type: application/zip");
	    header("Content-Transfer-Encoding: binary");
	    // read the file from disk
	    readfile($file);
	}
}
else{
	echo  'File Not Found';
}

?>