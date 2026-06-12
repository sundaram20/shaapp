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

	$addSql = "   	INSERT INTO `fs_cron_inventory_master` SET 
							`name` = 'cron-".currenDateTime()."',
							
							
								`run_status` = '0',	
								`success_status` = '0'					
							";
			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
echo $addSql;
mysqli_query($appConnectReport,$addSql);				
				$id_cron_master = mysqli_insert_id($appConnectReport);

				
	$GroupShopid=array();
	echo $sqlAutoReport = "SELECT * FROM fs_shop_hotel_detail where  status='1' ";	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);

	while($dataAutoReport = mysqli_fetch_object($resAutoReport)){
		
		$id_shop_group	=	$dataAutoReport->id_app_shops;//.rand(1000, 9999).rand(1000, 9999);//$dataAutoReport->id_shop_group;	
		$GroupShopid[$id_shop_group]['shop_id'][0]=$dataAutoReport->id_app_shops;

		
		
	}
	print_r($GroupShopid);
foreach($GroupShopid as $y=>$GroupShopid1){	
	
		echo '========'.$auto_ids_shops =implode(',',$GroupShopid1['shop_id']);
	
  
	$addSqlHotel = "   	INSERT INTO `fs_cron_inventory_master_detail` SET 
							`id_cron_master` = '".addslashes($id_cron_master)."',
							`name` = '".addslashes($resUserHotel->name)."',
							`display_order` = '".addslashes($resUserHotel->display_order)."',
							
							`city` = '".addslashes($resUserHotel->city)."',
							
							`id_app_shops` = '".addslashes($auto_ids_shops)."',
							
								`run_status` = '0',	
								`success_status` = '0'					
							";
			echo '<br/><br/><br/>'.$addSqlHotel .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`last_modified_by` = '".$_SESSION['userId']."'
							,`status` = '1'";
			mysqli_query($appConnectReport,$addSqlHotel);
					$addSqlHotel='';
	
}



mysqli_close($appConnectReport);
?>