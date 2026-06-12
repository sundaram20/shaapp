<?php
error_reporting(E_ALL);
set_time_limit(600);
//CRON URL

/*/usr/local/bin/php -q /home/admingcs/public_html/sales/adminpanel/cronjobs/followupReportAutoMailer.php /dev/null 2>&1 */

///////////////// LOCAL PATH ///////////////////
/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
include_once("../../phplib/class.database.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel.php");
include_once("../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include_once("../../adminpanel/includes/reportFunctionsFollowupNotification.php");*/


///////////////// CRON JOB PATH ///////////////////
//$path = getcwd().'/httpdocs/crs';
//echo $_SERVER['DOCUMENT_ROOT'];die;
$path = $_SERVER['DOCUMENT_ROOT'];//'/var/www/vhosts/roomstatushub.in/httpdocs/sales';
include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
include_once($path."/phplib/class.database.php");

include_once($path."/frontoffice/functions/function.php");


/*********** DATEA BASE CONNECTIONS *************/
	$DB_NAME='demo';
	$HOST_NAME = $_SERVER['SERVER_NAME'];
	$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];

$DB_HOST                        = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
	   // Database Host Server
	$DB_USERNAME                    = "demo";              // Database Username
	$DB_PASSWORD                    = "Kv7a1!p3";              // Password for he Db User
	$DB_NAME                        = "hip";              // Database name
	$DB_REPORT_ERROR                = true;                        // To Report Error
	$DB_PERSISTENT_CONN             = false;   
$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
$db->open() or die($db->error());

$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

/************** Setting Variables **********************/








   
   	$post_tariff_date=date('Y-m-11');
	$id_post_tariff='1';
	$id_fo_bill='';
	$shop='2';
   	
	// Head Email
   		
		 $ContentTable=postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew);
		
 			
mysqli_close($connNew);
exit;
?>