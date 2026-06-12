<?php
//CRON URL
//error_reporting(E_ALL);
set_time_limit(450000);
date_default_timezone_set('Asia/Kolkata');
///////////////// CRON JOB PATH ///////////////////
$path=$_SERVER['DOCUMENT_ROOT'];
//include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
//include_once($path."/phplib/roomstatus.library.php");
include_once($path."/phplib/dompdf/dompdf_config.inc.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once($path."/phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");

include_once($path."/functions/inventoryUpdateFunctions.php");
$DB_HOST = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
//"ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
 
$DB_NAME_APP					='app';
$DB_USERNAME_APP                = "inroomhu_crsRooms";  // Database Username
$DB_PASSWORD_APP                = "Kallal9876#";
$DB_REPORT_ERROR                = true;               // To Report Error
$DB_PERSISTENT_CONN             = false;

	$appConnectReport = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);

	
	$GroupShopid=array();
	$sqlAutoReport = "SELECT * FROM fs_shop_hotel_detail where  status='1' ";	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);

	while($dataAutoReport = mysqli_fetch_object($resAutoReport)){
		
		$id_shop_group	=	$dataAutoReport->id_app_shops;//.rand(1000, 9999).rand(1000, 9999);//$dataAutoReport->id_shop_group;	
		$GroupShopid[$id_shop_group]['shop_id'][0]=$dataAutoReport->id_app_shops;
		
		
	}
	echo '<pre>';
	echo $to_email;
	
	$CronSql = "SELECT id,run_status FROM `fs_cron_inventory_master` WHERE   (`run_status`='0' || `run_status`='1') AND status='1'  AND success_status='0' order by id ";
$resCron = mysqli_query($appConnectReport,$CronSql);
$rowCron = mysqli_fetch_object($resCron);
$rowNum = mysqli_num_rows($resCron);
print_r($rowCron);
if($rowNum>0){
	
	if($rowCron->run_status=='0'){
 		 $editRateDetail = "UPDATE `fs_cron_inventory_master` SET `run_status` = '1' , datetime_start='".date('Y-m-d H:i:s')."'   WHERE `id` = '".$rowCron->id."' ";
		mysqli_query($appConnectReport,$editRateDetail);	
	}



   echo $hotelSql = "SELECT * FROM  fs_cron_inventory_master_detail WHERE id_cron_master=".$rowCron->id."  AND status='1' and `run_status`='0' ORDER BY display_order ";



  $resHotel = mysqli_query($appConnectReport,$hotelSql);




$rowHotel = mysqli_fetch_object($resHotel);
		
		$auto_ids_shops =$rowHotel->id_app_shops;
	
    echo $sqlShopCodeChk = "SELECT * FROM ".APP_SHOP." where status = '1' AND id IN (".$auto_ids_shops.")";
	
	$resShopChk = mysqli_query($appConnectReport,$sqlShopCodeChk);
	mysqli_num_rows($resShopChk);
	if(mysqli_num_rows($resShopChk) > 0){		
		while($dataShopChk = mysqli_fetch_object($resShopChk)){
			
			if($rowHotel->run_status=='0'){
 		$cronMasterDetail = "UPDATE `fs_cron_inventory_master_detail` SET `run_status` = '1' , datetime_start='".date('Y-m-d H:i:s')."'   WHERE `id` = '".$rowHotel->id."' ";
		mysqli_query($appConnectReport,$cronMasterDetail);	
	}
			
			
		print_r($dataShopChk);
		$DB_NAME		=	$dataShopChk->database;
		$DB_USERNAME	=	$dataShopChk->user_name;
		$DB_PASSWORD	=	$dataShopChk->password;
		
		$DB_REPORT_ERROR                = true;    // To Report Error
		$DB_PERSISTENT_CONN             = false;   

		$process = $_REQUEST['process'];		
		
		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

		$db->open() or die($db->error());






$StartDate	=	date('d-m-Y');
$endDate	=date('Y-m-d', strtotime('+60 days'));

		

updateOTA('1',$StartDate,$endDate,$connNew);

mysqli_close($connNew);
$db->close();
			
			$master_detail12 = "UPDATE `fs_cron_inventory_master_detail` SET `success_status` = '1' , datetime_completed='".date('Y-m-d H:i:s')."'  WHERE `id` = '".$rowHotel->id."' ";
	mysqli_query($appConnectReport,$master_detail12);
		}
}	
echo '<pre>';
print_r($attach);

echo $hotelSql = "SELECT id FROM  fs_cron_inventory_master_detail WHERE  id_cron_master=".$rowCron->id."  AND status=1 and `run_status`='0' ORDER BY display_order ";



  $resHotel = mysqli_query($appConnectReport,$hotelSql);

$rowNumEnd = mysqli_num_rows($resHotel);

if($rowNumEnd==0){


 $editRateDetail4 = "UPDATE `fs_cron_inventory_master` SET `success_status` = '1' , datetime_completed='".date('Y-m-d H:i:s')."'   WHERE `id` = '".$rowCron->id."' ";
mysqli_query($appConnectReport,$editRateDetail4);
}

}
mysqli_close($appConnectReport);
?>