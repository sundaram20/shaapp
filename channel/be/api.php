<?php 
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);


//include_once("../../config/fron_autoload_staah-test.php"); 
//include_once("../../config/fron_autoload_staah.php");
//include_once("../../adminpanel/includes/inventoryUpdateFunctions.php");
// Load JSON from file
 $postData = file_get_contents('php://input');

 //executeSql("Insert into api_request set channel_id = '2' , request='" . addslashes($postData) . "',date_created='" . date('Y-m-d H:i:s') . "',company_name='" . ($data['bookingSource']['name'] ?? '') . "',booking_referance_id='" . $ReferenceID . "',response_status='Req Received',id_pms_response='" . $id . "',failed_at='" . date('Y-m-d H:i:s') . "',booking_type='Commit'");


global $db;

$reservationJson = json_decode($postData, true);

if (isset($reservationJson['reservationId'])) {
    
	include_once("Reservation.php"); 
	
}

?>
