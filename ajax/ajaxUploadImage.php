<?php
	include_once("../config/auto_loader.php");
	
	$message=array();
	if(($_FILES['photo']['name'] == '')){
		
		   //no error
	}else{

		$image_path = $UPLOAD_FILES.'/'.$_REQUEST['folder'].'/';
    	$image_display_path = $UPLOAD_FILES_PATH ."/".$_REQUEST['folder']."/";
		
		if($_FILES['photo']['size']>0 && $_FILES['photo']['size']<1048576){
			if(($_FILES['photo']['type'] == 'image/jpeg') || ($_FILES['photo']['type'] == 'image/png') || ($_FILES['photo']['type'] == 'image/bmp') || ($_FILES['photo']['type'] == 'image/gif')){
				$unique = rand(00000,99999);
	        	$filename= basename($_FILES['photo']['name']);
	        	$fname = getNameExt($filename);
	        	$insert_image = $_SESSION['shop_code'].'-'.$fname[0].$unique.".".$fname[1];		

				if(@move_uploaded_file($_FILES['photo']['tmp_name'],$image_path.$insert_image)){	
					resize($insert_image,$image_path, $image_path, $width=400,$height=300,$thumb='medium-');
					resize($insert_image,$image_path, $image_path, $width=200,$height=100,$thumb='small-');	
						//////end resize////////
						if(@file_exists($image_path.$_POST['image']) && ($_POST['image'] != $_FILES['photo']['name'])){
							@unlink($image_path.$_POST['image']);
							@unlink($image_path.'medium-'.$_POST['image']);
							@unlink($image_path.'small-'.$_POST['image']);
							@unlink($image_path.'main-'.$_POST['image']);
						}
					$message[0]=1;	
					$message[1]=$image_path.'medium-'.$insert_image;
					$message[2]=$insert_image;		
				}
				else{
					
					$message[0] = '<font style="color:red;font-weight:normal;" >Unable to upload file '.$_FILES['photo']['name'].'.<br></font>';
				}
			}
			else{
					
				$message[0] = '<font style="color:red;font-weight:normal;" >Invalid file type '.$_FILES['photo']['type'].'. Please use only JEPG,GIF,PNG,BMP only<br></font>';
			}
		}
		else{
				
				$message[0] = '<font style="color:red;font-weight:normal;" ><br>Image not selected or size is greater than 1MB.</font>';
		}
	}

	echo json_encode($message);
?>