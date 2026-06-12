<?php
function resizer_main($image, $dest_file_prefix, $w, $h)
{	//image_name = uploaded image. Name or your file field in your form.
	//w,h - width and height to fit image in
	global $use_imagecreatetruecolor, $use_imagecopyresampled, $IMG_ROOT, $JPG_QUALITY, $HTTP_POST_FILES;
	$image_name = $HTTP_POST_FILES [$image]["name"];
	$image = $HTTP_POST_FILES [$image]["tmp_name"];

	if(trim($image) == "" || trim($image) =="none") 
	{	return false;
	}

	$arr_img = image_from_upload($image);
	if( $arr_img["w"] != $w && $arr_img["h"] != $h)
	{	$wh	= get_sizes($arr_img["w"], $arr_img["h"], $w, $h);
		$img_res = img_get_resized($arr_img["img"], $arr_img["w"], $arr_img["h"], $wh["w"], $wh["h"], $use_imagecreatetruecolor, $use_imagecopyresampled);
		
	} 
	else 
	{	//wow it is exactly like needed!!!
		$img_res = $arr_img["img"];
		
	}
	
	//$file_name = make_filename($image_name, $dest_file_prefix);
	$file_name=$image_name;
	ImageJPEG($img_res,"$IMG_ROOT/$dest_file_prefix$file_name");
	return "$dest_file_prefix$file_name";
}

function fs_resizer_main($image, $image_name, $dest_file_prefix, $w, $h)
{	 
	global $use_imagecreatetruecolor, $use_imagecopyresampled, $IMG_ROOT, $JPG_QUALITY;
	
	 

	if(trim($image) == "" || trim($image) =="none") 
	{	return false;
	}

	$arr_img = image_from_upload($image);

	if( $arr_img["w"] != $w && $arr_img["h"] != $h)
	{	$wh	= get_sizes($arr_img["w"], $arr_img["h"], $w, $h);
		$img_res = img_get_resized($arr_img["img"], $arr_img["w"], $arr_img["h"], $wh["w"], $wh["h"], $use_imagecreatetruecolor, $use_imagecopyresampled);
	} 
	else 
	{	$img_res = $arr_img["img"];
	}
	
	$file_name=$image_name;
	 
	if(substr($file_name,-4)==".gif")
		ImageGIF($img_res,"$IMG_ROOT/$dest_file_prefix$file_name");
	else
		ImageJPEG($img_res,"$IMG_ROOT/$dest_file_prefix$file_name");
	$img_res='';
	
	return "$dest_file_prefix$file_name";
}

function image_from_upload($uploaded_file)
{	
	$img_sz =  getimagesize( $uploaded_file ); 
	## returns array with some properties like dimensions and type;
	####### Now create original image from uploaded file. Be carefull! GIF is often not supported, as far as I remember from GD 1.6
	
	switch( $img_sz[2] )
	{	case 1: 
			
			$img_type = "GIF";
			$img = ImageCreateFromGif($uploaded_file); 
			break;
		case 2: 
			
			$img = ImageCreateFromJpeg($uploaded_file); 
			$img_type = "JPG";
			break;
		/*case 3: 
			$img = ImageCreateFromPng($uploaded_file); 
			$img_type = "PNG";
			break;
		case 4: 
			$img = ImageCreateFromSwf($uploaded_file); 
			$img_type = "SWF";
			break;*/
		default: die("<br><font color=\"red\"><b>Sorry, this image type is not supported yet.</b></font><br>");
	}//case
	return array("img"=>$img, "w"=>$img_sz[0], "h"=>$img_sz[1], "type"=>$img_sz[2], "html"=>$img_sz[3]);

}


function get_sizes($src_w, $src_h, $dst_w,$dst_h )
{
	//src_w ,src_h-- start width and height
	//dst_w ,dst_h-- end width and height
	//return array  w=>new width h=>new height mlt => multiplier
	//the function tries to shrink or enalrge src_w,h in such a way to best fit them into dst_w,h
	//keeping x to y ratio unchanged
	//dst_w or/and dst_h can be "*" in this means that we dont care about that dimension
	//for example if dst_w="*" then we will try to resize by height not caring about width 
	//(but resizing width in such a way to keep the xy ratio)
	//if both = "*" we dont resize at all.
	#### Calculate multipliers
	$mlt_w = $dst_w / $src_w;
	$mlt_h = $dst_h / $src_h;

	$mlt = $mlt_w < $mlt_h ? $mlt_w:$mlt_h;
	if($dst_w == "*") $mlt = $mlt_h;
	if($dst_h == "*") $mlt = $mlt_w;
	if($dst_w == "*" && $dst_h == "*") $mlt=1;

	#### Calculate new dimensions
	$img_new_w =  round($src_w * $mlt);
	$img_new_h =  round($src_h * $mlt);
	return array("w" => $img_new_w, "h" => $img_new_h, "mlt_w"=>$mlt_w, "mlt_h"=>$mlt_h,  "mlt"=>$mlt);
}

function img_get_resized($img_original,$img_w,$img_h,$img_new_w,$img_new_h,$use_imagecreatetruecolor=false, $use_imagecopyresampled=false)
{	//$img_original, -- image to be resized
	//$img_w, -- its width
	//$img_h, -- its height
	//$img_new_w, -- resized width
	//$img_new_h -- height
	//$use_imagecreatetruecolor, $use_imagecopyresampled allow use of these function 
	//if they exist on the server

	if( $use_imagecreatetruecolor && function_exists("imagecreatetruecolor"))
	{	//echo("Using ImageCreateTruecolor (better quality)<br>");
		$img_resized = imagecreatetruecolor($img_new_w,$img_new_h) or die("<br><font color=\"red\"><b>Failed to create destination image.</b></font><br>"); 
	} 
	else 
	{	//echo("Using ImageCreate (usual quality)<br>");
		$img_resized = imagecreate($img_new_w,$img_new_h) or die("<br><font color=\"red\"><b>Failed to create destination image.</b></font><br>"); 
	}

	if($use_imagecopyresampled && function_exists("imagecopyresampled"))
	{	//echo("Using ImageCopyResampled (better quality)<br>");
		imagecopyresampled($img_resized, $img_original, 0, 0, 0, 0,$img_new_w, $img_new_h, $img_w,$img_h) or die("<br><font color=\"red\"><b>Failed to resize @ ImageCopyResampled()</b></font><br>"); 
	}
	else
	{	//echo("Using ImageCopyResized (usual quality)<br>");
		imagecopyresized($img_resized, $img_original, 0, 0, 0, 0,$img_new_w, $img_new_h, $img_w,$img_h) or die("<br><font color=\"red\"><b>Failed to resize @ ImageCopyResized()</b></font><br>"); 
	}
	return $img_resized;
}

function make_filename($image_name)
{	## creates unique name, here I assume that it will never happen that in same second 
	## two files with same name on user's site will be uploaded. However you can use your
	## ways to generate unique name. Function unqueid() for example.
	$file_name = time()."_$image_name";  		

	#kick the original extension
	$pos = strrpos($file_name, '.');
	//personally I think jpeg rulez so I hardoce its extension here
	$file_name = substr($file_name, 0,$pos).".jpg";
	return $file_name;
}



?>