<?php 
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include_once("../../config/appConfig.php");
//include_once("../../config/fron_autoload_staah-test.php"); 
//include_once("../../config/fron_autoload_staah.php");
//include_once("../../adminpanel/includes/inventoryUpdateFunctions.php");
include_once("../../functions/inventoryUpdateFunctions.php");
// Load JSON from file
  $postData = file_get_contents('php://input');

global $db;

 $id_shop=2;
 $channelId='2';

$xml = simplexml_load_string($postData, "SimpleXMLElement", LIBXML_NOCDATA);

if ($xml === false) {
    echo "Failed to parse XML";
    exit;
}
date_default_timezone_set('Asia/Kolkata'); // change to your timezone




if($postData){
$xmlarray = json_decode(json_encode($xml), true);

	//print_r($xmlarray);
	$apiUserName = $xmlarray['POS']['Source']['Authentication']['UserName'];
	$apiPassword = $xmlarray['POS']['Source']['Authentication']['Password'];
	$hotelCode   = $xmlarray['POS']['Source']['Authentication']['hotelcode'];
	$reservation = $xmlarray['HotelReservations']['HotelReservation'];


	$other_reference = $reservation['UniqueID']['@attributes']['ID'];
	
mysqli_query($appConnect,"Insert into api_test set request='".$postData."',type='Res',other_reference='".$other_reference."',date_created='".date('Y-m-d H:i:s')."',hotel_code='".$hotelCode."'");
$query =	mysqli_query($appConnect ,"select * from app_shops_channel_mapping Where user_name='".$apiUserName."' and password='".$apiPassword."' and  id_channel_mapping='".$hotelCode."'");
$appNumberOfRows=	mysqli_num_rows($query);
if($appNumberOfRows=='1'){	
	$row=	mysqli_fetch_object($query);
	include_once("../../config/api_auto_loader.php");
	include_once("../guestDocConfig.php");
	

	
/* ===== INSERT REQUEST ===== */
$log_sql = "INSERT INTO api_request SET
    channel_id = '".intval($channelId)."',
    type = '0',
    request = '".mysqli_real_escape_string($connNew, $postData)."',
    company_name = '',
    booking_referance_id = '',
    id_hotel = '0',
    id_order = '0',
    echotoken_id = '',
    booking_type = '',
    response_status = 'INIT',
    response_ack = '',
    failed_at = '',
    `count` = '1',
    date_created = '".date('Y-m-d H:i:s')."'
";

mysqli_query($connNew, $log_sql);
$log_id = mysqli_insert_id($connNew);
	
	include_once("Reservations.php");
	
	
	
	
	
}
	
}
 //mysqli_query($appConnect,"Insert into api_test set request='".$postData."',type='Res',date_created='".date('Y-m-d H:i:s')."'");
//include_once("reservation.php");
//include_once("Reservations.php");
/*echo '<?xml version="1.0" encoding="UTF-8"?>
				<OTA_HotelResNotifRS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
				xmlns:xsd="http://www.w3.org/2001/XMLSchema"
				xmlns="http://www.opentravel.org/OTA/2003/05" EchoToken="'.$reference.'" TimeStamp="'.date('Y-m-d H:i:s').'" Version="1.0">
				<Success>Success</Success>
				</OTA_HotelResNotifRS>';*/
		
//print_r($xml);
die;

?>
