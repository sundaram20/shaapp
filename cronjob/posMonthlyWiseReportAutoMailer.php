<?php
//CRON URL
//error_reporting(E_ALL);
//set_time_limit(8050000);



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
set_time_limit(8050000);
include_once($path."/pos/include/function.php");
$DB_HOST = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
 
$DB_NAME_APP					='app';
$DB_USERNAME_APP                = "inroomhu_crsRooms";  // Database Username
$DB_PASSWORD_APP                = "Kallal9876#";
$DB_REPORT_ERROR                = true;               // To Report Error
$DB_PERSISTENT_CONN             = false;

$appConnectReport = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);

	$sqlAutoReport = "SELECT * FROM app_auto_report_config where id='1' ";
	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);
	$dataAutoReport = mysqli_fetch_object($resAutoReport);
	$auto_ids_shops	=	$dataAutoReport->ids_shop;
	
    $sqlShopCodeChk = "SELECT * FROM ".APP_SHOP." where id IN (".$auto_ids_shops.")";
	
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

	
		$dompdf = new DOMPDF();
	

$id_shop=2;

$season =array();
$SqlShop = "SELECT name,city FROM `mst_shops` where id='2' ";
$resShop = mysqli_query($connNew,$SqlShop);
$rowShop = mysqli_fetch_object($resShop);

date('d-m-Y');
$previousmonthStart= date("Y-n-j", strtotime("first day of previous month"));
$previousmonthEnd = date("Y-n-j", strtotime("last day of previous month"));
			
			$LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
  $period	=date('d-m-Y', strtotime($previousmonthStart))." to ".date('d-m-Y', strtotime($previousmonthEnd));

$id_report_type="197";
$id_main_group	="";
$id_data_main_group	="";
$id_item=	"";
$id_order_by	="2";
$id_sub_group	="";
$showItemReport=	"0";
$kot_nc	="0";
$shop='2';
$report_show='3';
$cronSet='1';

//Day WiseReport
/*$pdfNameReport1=$dataShopChk->shop_code.'-DayWise-'.date('d-m-Y');
echo '<br>';
echo consolidatedItemWiseReport($period,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$kot_nc,$appConnectReport,$connNew,$shop,$cronSet,$pdfNameReport1); */

//Item WiseReport
$id_report_type="196";
echo $pdfNameReport2=$dataShopChk->shop_code.'-ItemWise-'.date('d-m-Y');
			
echo consolidatedItemWiseReport($period,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$kot_nc,$appConnectReport,$connNew,$shop,$cronSet,$pdfNameReport2,$dompdf);

//settlementSummaryReport
/*$objPHPExcel = new PHPExcel();
  $pdfNameReport3=$dataShopChk->shop_code.'-settlementSummaryReport-'.date('d-m-Y');
echo settlementSummaryReport($period,$id_outlet,$id_shift,$objPHPExcel,$appConnectReport,$connNew,$shop,$cronSet,$pdfNameReport3);*/			


//$attach['consolidatedItemWiseReport']['pdf'][$dataShopChk->shop_code]=$pdfNameReport1;
//$attach[$dataShopChk->shop_code]['pdf'][]=$pdfNameReport2;
$attach['consolidatedItemWiseReport']['pdf'][$dataShopChk->shop_code]=$pdfNameReport2;


mysqli_close($connNew);
$db->close();
		}
}	
echo '<pre>';
print_r($attach);

//die;		
$attachPath=$path.'/mailattach/';
//$attach=array();
//$attach['pdf'][]=$pdfNameReport1;
//$attach['pdf'][]=$pdfNameReport2;
//$attach['excel'][]=$pdfNameReport3;
$cc = array();
//$cc[]= 'sundaram@roomstatushub.com';
//$cc[]= 'roomstatushublogs@gmail.com';
//echo $mailto = 'ravi@hirohama.co.in';
			
$mailto = 'roomstatushublogs@gmail.com';
//EMAIL START==================================================================================

 if($mailto !=''){// && file_exists($attach)){
	 
	 
	foreach($attach as $reportType=>$attachname2)
	{	 
	if($reportType =='consolidatedItemWiseReport'){
		$msg 	 = "Please find the attachment for the Item Wise Report<br/><br/> RoomStatusHUB Team.";
		$sub	 = 'POS Item Wise Report';
		$cc='';		
$cc=array();
	$cc[]= 'sundaram@roomstatushub.com';
		}else{
	
$msg 	 = "Please find the attachment for the settlement Summary Report<br/><br/> RoomStatusHUB Team.";
$sub	 = 'settlement Summary Report-1';
$cc='';		
$cc=array();
/*
$cc[]='cm@hirohama.co.in';
$cc[]='cakrishnarajsingh@gmail.com';
$cc[]='kuurakuacs@gmail.com';
$cc[]='accounts.gurgaon@hirohama.co.in';
$cc[]='accounts.manager@hirohama.co.in';
*/
		}
$msg 	 = wordwrap($msg,70);
$From	= "support@roomstatushub.com";



//$recipients =explode(",",$_POST['ccId']);

$mail = new PHPMailer(); // create a new object	
$mail->IsSMTP(); // enable SMTP
//$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
$mail->SMTPAuth = true; // authentication enabled
$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
$mail->Host = "smtp.gmail.com";
$mail->Port = 465; // or 587
$mail->IsHTML(true);
$mail->Username = "support@roomstatushub.com";
$mail->Password = "kxfm xrpv znoi xmhx";
$mail->SetFrom($From);
$mail->AddReplyTo($From);
$mail->Subject = $sub;
$mail->Body = $msg;
//$mail->AddAddress($mailto);
$mail->AddAddress('k-honda@hirohama-india.com');	
$mail->AddAddress('hirohama@kuuraku.co.jp');	
$mail->AddAddress('tagawa@kuuraku.in');	
$mail->AddAddress('ishizawa@kuuraku.in');	
$mail->AddAddress('yamamoto@hirohama.co.in');	
$mail->AddAddress('ravi@hirohama.co.in');	
$mail->AddAddress('mandal@kuuraku.in');	
$mail->AddAddress('suzuki@kuuraku.in');	
$mail->AddAddress('kishimoto@kuuraku.in');			


	foreach($cc as $ccmail)
	{	
		
   		//$mail->AddCC($ccmail);
	}
	 
		// $mail->AddBCC("ravi@hirohama.co.in", "support");
		//$mail->AddBCC("sundaram@roomstatushub.com", "support");
		$mail->AddBCC("roomstatushublogs@gmail.com", "support");
	 //echo $fileType;
		foreach($attachname2 as $fileType=>$attachname3)
		{
	 	foreach($attachname3 as $attachname)
		{
			if($fileType=='pdf'){
		echo '=====>'.$attachname=$attachname.'.pdf';
   		$mail->addAttachment($attachPath.$attachname,$attachname,"base64","application/pdf");
			}
			if($fileType=='excel'){
		$attachname=$attachname.'.xls';
   		$mail->addAttachment($attachPath.$attachname,$attachname,"base64","application/excel");
			}
		}
	 }
				  
	//die;
	$sendMail = $mail->Send(); 
		 	
		
 }
 }
		 unset($sendMail);
		 
		  //EMAIL REMOVE ATTACHMENT
			foreach($attach as $reportType=>$attachname2)
			{
			foreach($attachname2 as $fileType=>$attachname3)
				{
				foreach($attachname3 as $attachname)
					{
						if($fileType=='pdf'){
						$attachname=$attachname.'.pdf';
						}else{
						$attachname=$attachname.'.xls';
						}
						unlink($attachPath.$attachname);
			
					}
				}
			}
			 //EMAIL REMOVE ATTACHMENT

//EMAIL END ===================================================================================



mysqli_close($appConnectReport);
?>