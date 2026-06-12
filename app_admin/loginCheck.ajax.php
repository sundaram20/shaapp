<?php
include_once('../config/fron_autoload.php');


$msg='';
$id_user = selectField('app_users','id','WHERE user_name="'.$_REQUEST['user_name'].'" AND password="'.md5($_REQUEST['password']).'" ',$appConnect);

if($id_user > 0){
	$msg='<span style="color:green;">Logged In Successfully ! </span>';
	$_SESSION['app_id_user']=$id_user;
	$_SESSION['app_user_name']=trim($_REQUEST['user_name']);
	
	$updateData = array();
	$updateData['logged_in_at'] = date('Y-m-d H:i:s');
	$updateData['ip_address'] = $USER_IP;
	updateData('app_users',$updateData,' id="'.$id_user.'" ',$appConnect);
}
else{
	$msg='<span style="color:red;">User Name Or Password is Incorrect ! </span>';
}

echo $msg;