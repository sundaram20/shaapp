<?php
include_once("../../config/auto_loader.php");

 echo $first_name = $_REQUEST['booker_first_name'];
$last_name = $_REQUEST['booker_last_name'];
$email = $_REQUEST['booker_email'];
$primary_mobile = $_REQUEST['booker_mobile'];
$city = $_REQUEST['booker_city'];
$postcode = $_REQUEST['booker_postcode']; 
 $id=$_POST['id']; 
 
 

				$selectnew="SELECT * FROM ".TBL_COMPANY_CONTACTS."  where id=$id  ";
				$resnew = mysqli_query($connNew,$selectnew); 	
  			 
					while($rownew = mysqli_fetch_object($resnew)){ 
						$data['id'] =$rownew->id;
						$data['first_name'] =$rownew->first_name;
						$data['last_name'] =$rownew->last_name;
						$data['email'] =$rownew->email;
						$data['primary_mobile'] =$rownew->primary_mobile;
						$data['city'] =$rownew->city;
						$data['postcode'] =$rownew->postcode;
				 				
 $insertGrid = "UPDATE ".TBL_COMPANY_CONTACTS." SET
								
								`first_name`='".$first_name."',
								`last_name`='".$last_name."',
								`email`='".$email."',
								`primary_mobile`='".$primary_mobile."',
								`city`='".$city."',
								`postcode`='".$postcode."' ";
					$insertGrid .=" WHERE id = '$id' ";	
								
							   mysqli_query($connNew,$insertGrid); 
				 
				 }
					

 
	echo json_encode($data);

	
 ?>


