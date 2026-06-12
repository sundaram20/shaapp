<?php
function ImageSizer($max_width, $max_height, $img_path)
{
	list($width, $height) = getimagesize($img_path); 
	$ratioh = $max_height/$height; 
	$ratiow = $max_width/$width; 
	$ratio = min($ratioh, $ratiow); 
	$res[0] = intval($ratio*$width); 
	$res[1] = intval($ratio*$height);
	
	return $res;
}
function re_img($image_name,$thumb_name, $maxX) 
 { 
    $src_img = imagecreatefromjpeg("$image_name"); 
    $x=imagesx($src_img); 
    $y=imagesy($src_img); 
    
    $w=$x;
    $h=$y;
    
    if ($x > $maxX)
	{
		$w=$x;
		$x=$maxX;
		$y=round($maxX * $h / $w);
		$dst_img = imagecreatetruecolor($x,$y);
	    imagecopyresized($dst_img,$src_img,0,0,0,0,$x,$y,$w,$h); 
	    imagejpeg($dst_img, "$thumb_name", 100); 
	    
    }
	else
	{
		rename ($image_name ,$thumb_name);
	}   
	return true;  
 }
function resizeToSpecificSize($srcImage, $w, $h)
{
	$info   = getimagesize($srcImage);
	$imageType = $info['mime'];
	$ow = $info[0];
	$oh = $info[1];
		
	$new = imagecreatetruecolor($w,$h);
	
	if (preg_match('/jpg|jpeg/',$imageType))
	{
		$image = imagecreatefromjpeg($srcImage);
		imagecopyresized($new, $image , 0 , 0 , 0 , 0 , $w, $h , $ow, $oh );
		@unlink($srcImage);
		imagejpeg($new,$srcImage,60); 
		
	}
	else if (preg_match('/png/',$imageType))
	{
		$image = imagecreatefrompng($srcImage);
		imagealphablending($image , false);
		imagealphablending($new, false);
		imagesavealpha($new, true);		
		imagecopyresized($new, $image ,  0 , 0 , 0 , 0 , $w, $h , $ow, $oh );
		@unlink($srcImage);
		imagejpeg($new,$srcImage,60); 
	}
	else if (preg_match('/gif/',$imageType))
	{
		$image = imagecreatefromgif($srcImage);
		imagecopyresized($new, $image ,0 , 0 , 0 , 0 , $w, $h   , $ow, $oh);
		@unlink($srcImage);
		imagejpeg($new,$srcImage,60); 
	}
	
	imagedestroy($image); 
	imagedestroy($new);
}
function corpImageByCords($srcImage,$targetImg, $coords, $previewWidth)
{
	$info   = getimagesize($srcImage);
	$imageType = $info['mime'];
	
	if (preg_match('/jpg|jpeg/',$imageType))
	$image = imagecreatefromjpeg($srcImage);
	else if (preg_match('/png/',$imageType))
	{
	$image = imagecreatefrompng($srcImage);
	imagealphablending($image , false);
	}
	else if (preg_match('/gif/',$imageType))
	$image = imagecreatefromgif($srcImage);
	
	
	$ow = imagesx($image);
	$oh = imagesy($image);
	
	$resizeRatio = $ow / $previewWidth;
	$temp = explode('|', $coords);
	
	$x1 = round($temp[0] * $resizeRatio);
	$x2 = round($temp[2] * $resizeRatio);
	$y1 = round($temp[1] * $resizeRatio);
	$y2 = round($temp[3] * $resizeRatio);
	
	$newH = round($y2-$y1);
	$newW = round($x2-$x1);
	
	$filename = $targetImg;
	
	
	if ($newH > 1)
	{
		$new = imagecreatetruecolor($newW,$newH);
		
		@unlink($filename);
		
		 if (preg_match('/jpg|jpeg/',$imageType))
		 {
			 imagecopy($new, $image , 0 , 0 , $x1 , $y1 , $newW,$newH );
			 imagejpeg($new,$filename,100); 
		}
		 else if (preg_match('/png/',$imageType))
		 {
		 			
		  imagealphablending($new, false);
			imagesavealpha($new, true);		
		 	imagecopy($new, $image , 0 , 0 , $x1 , $y1 , $newW,$newH );
			imagepng($new,$filename,0);
			
		 }
		 else if (preg_match('/gif/',$imageType))
		{
			imagecopy($new, $image , 0 , 0 , $x1 , $y1 , $newW,$newH );
			imagegif($new,$filename);
		}
		
		imagedestroy($image); 
		imagedestroy($new); 
	}
}
 function corp_img($src,$dest,$new_w,$new_h, $orginalName = '')
 {
	 $size = getimagesize($src);
	 //print_r($size);
     $trueW=$size[0];
     $trueH=$size[1];
		
	  $y=false;  // IMAGE IS HORISONAL
	  if (($new_h/$trueH) > ($new_w/$trueW))
	  {
	     $imgH = $new_h;
	     $imgW = round(($new_h / $trueH) * $trueW);
	     $imgProp = round(($imgW - $new_w)/2);
	     $y=true;
	  } 
	  else 
	  {
	     $imgW = $new_w;
	     $imgH = round(($new_w / $trueW) * $trueH);
	     $imgProp = round(($imgH - $new_h)/2);	     
	  }
	 $new = imagecreatetruecolor($imgW,$imgH);
	 $res = imagecreatetruecolor($new_w,$new_h);
	 $mimType = $size['mime'];
	 //echo "MIMTYPE=".$mimType;
	 if (preg_match('/jpg|jpeg/',$mimType))
	 {
		$img =imagecreatefromjpeg($src);
	 }
	 else if (preg_match('/png/',$mimType))
	 {
		$img =imagecreatefrompng($src);
		
		imagealphablending($res , false);
		imagesavealpha($res , true);
		
		imagealphablending($new , false);
		imagesavealpha($new , true);
		
		imagealphablending($img , true);	 
		
	}	
	 else if (preg_match('/gif/',$mimType))
	 {
		$img =imagecreatefromgif($src);
	 }
     imagecopyresampled ($new, $img, 0, 0, 0, 0, $imgW, $imgH, $trueW, $trueH);
     imagedestroy($img);
     if ($y)
     {
     imagecopyresampled ($res, $new, 0, 0,  $imgProp,0, $new_w, $new_h,$new_w, $new_h);
     }
     else
     {
     imagecopyresampled ($res, $new, 0, 0,0, $imgProp,  $new_w, $new_h,$new_w, $new_h);    
     }
     
	 if (preg_match('/jpg|jpeg/',$mimType))
	 {
		 imagejpeg($res, "$dest", 80); 
	 }
	 else if (preg_match('/png/',$mimType))
	 {
		imagepng($res, "$dest", 0);
	 }	
	 else if (preg_match('/gif/',$mimType))
	 {
		imagegif($res, "$dest");
	 }     
   
    
 }
?>