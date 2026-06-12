<?php
include_once('../config/fron_autoload.php');
$data['logged_out_at']=date('Y-m-d H:i:s');
updateData('app_users',$data,'id='.$_SESSION['app_id_user'].' ',$appConnect);
session_destroy();
unset($_SESSION['app_id_user']);
unset($_SESSION['app_user_name']);

header('LOCATION:appIndex.php');
?>