<?php
//CRON URL
//error_reporting(E_ALL);
set_time_limit(450000);



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


$DB_HOST = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
 
$DB_NAME_APP					='app';
$DB_USERNAME_APP                = "inroomhu_crsRooms";  // Database Username
$DB_PASSWORD_APP                = "Kallal9876#";
$DB_REPORT_ERROR                = true;               // To Report Error
$DB_PERSISTENT_CONN             = false;

	$appConnectReport = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);

	

	
	
    echo $sqlShopCodeChk = "SELECT * FROM ".APP_SHOP." where status = '1' ";
	
	$resShopChk = mysqli_query($appConnectReport,$sqlShopCodeChk);
	mysqli_num_rows($resShopChk);
	if(mysqli_num_rows($resShopChk) > 0){		
		while($dataShopChk = mysqli_fetch_object($resShopChk)){
	//print_r($dataShopChk);
		$DB_NAME	=	$dataShopChk->database;
		 $DB_USERNAME	=	$dataShopChk->user_name;
		$DB_PASSWORD	=	$dataShopChk->password;
		
		$DB_REPORT_ERROR                = true;    // To Report Error
		$DB_PERSISTENT_CONN             = false;   

		$process = $_REQUEST['process'];		
		
		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

		$db->open() or die($db->error());

	


$id_shop=2;

$season =array();
$SqlShop = "SELECT name,city,shop_code FROM `mst_shops` where id='2' and status='1' ";
$resShop = mysqli_query($connNew,$SqlShop);
$rowShop = mysqli_fetch_object($resShop);

echo '<br>'.$rowShop->shop_code;
		/*	
		// ✅ Check table exists
$tableCheck = mysqli_query($connNew, "
    SELECT 1 
    FROM information_schema.tables 
    WHERE table_schema = '$DB_NAME' 
    AND table_name = 'fo_reservations_details'
");

if(mysqli_num_rows($tableCheck) > 0){

    // ✅ Check column exists
    $columnCheck = mysqli_query($connNew, "
        SELECT 1 
        FROM information_schema.columns 
        WHERE table_schema = '$DB_NAME' 
        AND table_name = 'fo_reservations_details'
        AND column_name = 'id_mst_room_no_reserved'
    ");

    if(mysqli_num_rows($columnCheck) == 0){

        $alterSql = "ALTER TABLE fo_reservations_details 
                     ADD id_mst_room_no_reserved INT(11) 
                     NOT NULL DEFAULT 0 
                     AFTER id_mst_room_no_allocation";

        if(mysqli_query($connNew, $alterSql)){
            echo "✅ Updated DB: ".$DB_NAME."<br>";
        } else {
            echo "❌ Error in ".$DB_NAME." : ".mysqli_error($connNew)."<br>";
        }

    } else {
        echo "⏭ Column already exists in ".$DB_NAME."<br>";
    }

} else {
    echo "⚠ fo_reservations_details not found in ".$DB_NAME."<br>";
}*/
			
		
	/*$updateSql = "UPDATE fo_reservations_details
              SET id_mst_room_no_reserved = id_mst_room_no_allocation
              WHERE id_mst_room_no_reserved IS NULL 
                 OR id_mst_room_no_reserved = 0";

if(mysqli_query($connNew, $updateSql)){
    echo "✅ Data Updated in DB: ".$DB_NAME."<br>";
} else {
    echo "❌ Error in UPDATE ".$DB_NAME." : ".mysqli_error($connNew)."<br>";
}		
*/
			
			// ✅ Create api_inv_request table





/*$autoFix = "ALTER TABLE `fo_reservations_details` ADD `tax_percent` FLOAT(55,2) NOT NULL AFTER `id_tax_configuration`;

";

if(mysqli_query($connNew, $autoFix)){
    echo "🔧 [$DB_NAME] date_created OK<br>";
} else {
    echo "⚠ [$DB_NAME] date_created Issue: ".mysqli_error($connNew)."<br>";
}
*/
$autoFix = "SELECT * FROM `fs_channel_manager` ";


$RowCh    =   mysqli_query($connNew, $autoFix);
while($RecodCh = mysqli_fetch_object($RowCh)){

 
 echo "🔧 [$RecodCh->id] ".$RecodCh->name." OK<br>";







        }
			
mysqli_close($connNew);
$db->close();
		}echo "<hr>";
}	


mysqli_close($appConnectReport);
?>