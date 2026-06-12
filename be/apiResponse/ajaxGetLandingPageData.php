<?php
error_reporting(0);
include_once("../../config/fron_autoload.php");

//LOCAL SETUP
/*$DB_HOST        = "localhost";                 
$DB_USERNAME    = "root";              
$DB_PASSWORD    = ""; 
$DB_NAME 		= "app";*/

//SERVER SETUP
$DB_HOST        = "localhost";                 
$DB_USERNAME    = "crsRoomstatus";              
$DB_PASSWORD    = "crs123#"; 
$DB_NAME 		= "app";


$connApp = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

//error_reporting(0);

$shop_code = $_REQUEST['shop_code'];

$sqlApp = "SELECT * FROM app_shops WHERE shop_code='".$shop_code."' ";
$resApp = mysqli_query($connApp,$sqlApp);
$rowApp = mysqli_fetch_object($resApp); 

$database = $rowApp->database;

if($rowApp->id_shop=='' && $rowApp->id_shop==0){
	$id_shop = $rowApp->id;
}
else{
	$id_shop = $rowApp->id_shop;
}

$dataArray = array();


if($shop_code !='' && $database !=''){
	mysqli_close($connApp);
	$connShop = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $database);
	
	if($connShop){
		// fetching landing page data
		$sqlLand = "SELECT * FROM `web_landing_page_details` WHERE id_shop='".$id_shop."' ";
		$resLan  = mysqli_query($connShop,$sqlLand);
		$rowLan = mysqli_fetch_object($resLan);
		
		if(mysqli_num_rows($resLan)>0){
			$dataArray['landingPage']=$rowLan;
		}

		// fetching hotels data
		$sqlHot = "SELECT * FROM ".TBL_HOTELS." WHERE id_shop='".$id_shop."'  ";
		$resHot = mysqli_query($connShop,$sqlHot);
		
		if(mysqli_num_rows($resHot)){
			$dataArray['hotelsData'] = array();
			while($rowHot=mysqli_fetch_object($resHot)){
				array_push($dataArray['hotelsData'],$rowHot);
			}
			
		}

		// banner Images
		$bannerSql = "SELECT * FROM web_banner_images WHERE id_shop='".$id_shop."' AND status=1 ORDER BY display_order ";
		$resBan = mysqli_query($connShop,$bannerSql);
		
		if(mysqli_num_rows($resBan)>0){
			$dataArray['bannerData']=array();
			while($rowBan = mysqli_fetch_object($resBan)){
				array_push($dataArray['bannerData'],$rowBan);
			}
		}

	}
	else{
		array_push($dataArray,'DATA BASE NOT FOUND');
	}

	mysqli_close($connShop);

}
else{
	mysqli_close($connApp);
	array_push($dataArray,'SHOP CODE IS INCORRECT ! ');
}


echo json_encode($dataArray);

?>


