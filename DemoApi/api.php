<?php 


require_once("rest.inc.php");
$DB_HOST     = "ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
/*
$DB_HOST     = "ls-73c1d44d0baaf1a357e5233ea2688df20d6ae29b.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
//"ls-1e1a832b49b7699b7b7884943d61708e56e5da6e.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";

$DB_USERNAME = "welcom_booking_engine";              // Database Username
$DB_PASSWORD = "Whbe@*963"; 
$DB          = "welcom_be_1";
$keyValue 	 = 'key123';
$pushConn=	mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB) ;

*/

// Check connection
$DB_USERNAME_APP                   = "inroomhu_crsRooms";              // Database Username
$DB_PASSWORD_APP                   = "Kallal9876#";
$DB_NAME_APP                        = "app";
$conn = mysqli_connect($DB_HOST,$DB_USERNAME_APP,$DB_PASSWORD_APP,$DB_NAME_APP);

if($conn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
if($pushConn === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

 
$path = '/var/www/vhosts/app.roomstatushub.in/httpdocs';
include($path."/phplib/functions.library.php");
include($path."/phplib/msgs.inc.php");
include($path."/phplib/class.database.php");
include($path."/phplib/data.constant.php");





include($path."/pos/include/functionAPI.php"); 
    	
function processApi()
{
	
    $func = strtolower(trim(str_replace("/","",$_REQUEST['rquest'])));
	$func();
	if((int)method_exists($this,$func) > 0){
		 
	}else{
		 $result[] = 'Please check key and property value.'; //$this->response('',404);
	}
// If the method not exist with in this class, response would be "Page not found".
	
}
//echo $func
$mailsubject= $func;
function POSservices()
{	
	
	global $conn;
	global $pushConn;
	global $keyValue;
			$rawPost = file_get_contents('php://input');
			$jsondeocde = json_decode($rawPost, true);
			$username = $jsondeocde['auth']['username'];
			$pass = $jsondeocde['auth']['pass'];
			$code = $jsondeocde['auth']['code'];
	//echo '=============================='; 
	//print_r($jsondeocde);
	
	 $sqlShopCodeChk = "SELECT * FROM app_shops WHERE shop_code= '".$code."' ";	
	$resShopChk = mysqli_query($conn,$sqlShopCodeChk);
	if($resShopChk && mysqli_num_rows($resShopChk) == 1){
		//echo 'step1334232';die;
			$dataShopChk    = mysqli_fetch_object($resShopChk);			
			 $DB_NAME		=	$dataShopChk->database;
			 $DB_USERNAME	=	$dataShopChk->user_name;
			 $DB_PASSWORD	=	$dataShopChk->password;
			$module_access	=	$dataShopChk->module_access;
			$shop_code	=	$dataShopChk->shop_code;
			
		$process = $_REQUEST['process'];
		//mysqli_close($conn);
		$DB_REPORT_ERROR                = true;                        // To Report Error
		$DB_PERSISTENT_CONN             = false;  
		//$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
		//$db->open() or die($db->error());
		
			mysqli_set_charset($connNew,"utf8");
			if(!empty($username) && !empty($pass) && !empty($code)  ){			
				 $countRate = count($jsondeocde['data']['period']);
					for($i=0;$i<$countRate;$i++){
						$fromRate = addslashes($jsondeocde['data']['period'][$i]['startDate']);
						$toRate = addslashes($jsondeocde['data']['period'][$i]['endDate']);
						$Date	=	$fromRate. ' to '.$toRate;	
						settlementSummaryReportAPI($Date,$id_outlet,$id_shift,$connNew);
						die;
					}			
				}
  
  
	}else{			
		 $msg = array('message' => "error",'status' => "Invalid Username ,password OR code");
		 $mailMsg="failure";
		 //$pushConn->response($this->json($msg), 200); // If no records "No Content" status
			echo json_encode($msg);
		 }
		 
		 
   }





processApi();
?>