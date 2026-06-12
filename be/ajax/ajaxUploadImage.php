<?php
include_once("../../config/auto_loader.php");

$image_display_path=$image_path = $UPLOAD_FILES.'/offers/';

if($_POST['SaveGal']){
	  
  	if(($_FILES['photo']['name'] == '')){
  		   //no error
  	}else{
  		if($_FILES['photo']['size']>0 && $_FILES['photo']['size']<15242880){
  			if(($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/bmp') || ($_FILES['photo']['type'] == 'image/gif')){
  				$unique = rand(00000,99999);
  	        	$filename= basename($_FILES['photo']['name']);
  	        	$fname = getNameExt($filename);
  	        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];		

  				if(@move_uploaded_file($_FILES['photo']['tmp_name'],$image_path.$insert_image)){	
  				
  						resize($insert_image,$image_path, $image_path, $width=400,$height=300,$thumb='medium-');
  						
  						//////end resize////////
  						if(@file_exists($image_path.$_POST['image']) && ($_POST['image'] != $_FILES['photo']['name'])){
  							@unlink($image_path.$_POST['image']);
  							@unlink($image_path.'medium-'.$_POST['image']);
  							@unlink($image_path.'small-'.$_POST['image']);
  							@unlink($image_path.'main-'.$_POST['image']);
  							
  						}else{
	  						$err++;
	  						$err_image = '<font style="color:red;font-weight:normal;" >Unable to upload file '.$_FILES['photo']['name'].'.<br></font>';
	  						echo json_encode(1);
	  						exit;
  						}
  				}else{
  					$err++;
  					$err_image = '<font style="color:red;font-weight:normal;" >Invalid file type '.$_FILES['photo']['type'].'. Please use only JEPG,GIF,PNG,BMP only<br></font>';
  					echo json_encode(2);
  					exit;
  				}
  			}else{
  				$err++;
  				$err_image = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 5MB.</font>';
  				echo json_encode(3);
  				exit;
  			}
  	  }
  	echo json_encode($insert_image); 
    exit;	
 	}	
 	
}
