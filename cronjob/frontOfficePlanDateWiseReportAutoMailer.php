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

include_once($path."/frontoffice/functions/functionPlanDateWiseDailyReport.php");
$DB_HOST = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";
 
$DB_NAME_APP					='app';
$DB_USERNAME_APP                = "inroomhu_crsRooms";  // Database Username
$DB_PASSWORD_APP                = "Kallal9876#";
$DB_REPORT_ERROR                = true;               // To Report Error
$DB_PERSISTENT_CONN             = false;

	$appConnectReport = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);

	/*$sqlAutoReport = "SELECT * FROM app_auto_report_config where id='5' and status='1' ";	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);
	$dataAutoReport = mysqli_fetch_object($resAutoReport);
	$auto_ids_shops	=	$dataAutoReport->ids_shop;*/
	
	$sqlAutoReport = "SELECT * FROM app_auto_report_config_details where id_auto_report_config='9'  and status='1' ";	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);
$emailArray=array();
	while($dataAutoReport = mysqli_fetch_object($resAutoReport)){
		
		
		 $sqlShopCodeChkW = "SELECT * FROM ".APP_SHOP." where status = '1' AND id IN (".$dataAutoReport->id_shop.")";
	
	$resShopChkW = mysqli_query($appConnectReport,$sqlShopCodeChkW);

	
		$dataShopChkW = mysqli_fetch_object($resShopChkW);
		
		
		
		
		
		
		$id_shop_group	=	$dataAutoReport->id_shop_group;	
		$GroupShopid[$id_shop_group]['shop_id'][]=$dataAutoReport->id_shop;
		$GroupShopid[$id_shop_group]['Email'][$dataShopChkW->shop_code]['to_email']=$dataAutoReport->to_email;
		$GroupShopid[$id_shop_group]['Email'][$dataShopChkW->shop_code]['cc_email']=$dataAutoReport->cc_email;
		
		
		
		
		
		
		
	}
	echo '<pre>';
	echo $to_email;
	
	print_r($GroupShopid);

foreach($GroupShopid as $y=>$GroupShopid1){
	
	
		$auto_ids_shops =implode(',',$GroupShopid1['shop_id']);
		
		


		
	
	
     $sqlShopCodeChk = "SELECT * FROM ".APP_SHOP." where status = '1' AND id IN (".$auto_ids_shops.")";
	
	$resShopChk = mysqli_query($appConnectReport,$sqlShopCodeChk);
	mysqli_num_rows($resShopChk);
	if(mysqli_num_rows($resShopChk) > 0){	
		$attach='';
$attach=array();
		while($dataShopChk = mysqli_fetch_object($resShopChk)){
		print_r($dataShopChk);
		$DB_NAME	=	$dataShopChk->database;
		$DB_USERNAME	=	$dataShopChk->user_name;
		$DB_PASSWORD	=	$dataShopChk->password;
		
		$DB_REPORT_ERROR                = true;    // To Report Error
		$DB_PERSISTENT_CONN             = false;   

		$process = $_REQUEST['process'];		
		
		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

		$db->open() or die($db->error());

	


echo '===Shop'.$id_shop=$dataShopChk->id;

$season =array();
$SqlShop = "SELECT name,city FROM `mst_shops` where id='2' and status='1' ";
$resShop = mysqli_query($connNew,$SqlShop);
$rowShop = mysqli_fetch_object($resShop);

date('d-m-Y');
$period	=date('d-m-Y', strtotime('-1 days'))." to ".date('d-m-Y', strtotime('-1 days'));
$id_report_type="197";
$id_main_group	="";
$id_data_main_group	="";
$id_item=	"";
$id_order_by	="1";
$id_sub_group	="";
$showItemReport=	"0";
$kot_nc	="0";
$shop='2';
$report_show='3';
$cronSet='1';





//SalesRegisterReport
$objPHPExcel = new PHPExcel();
  $pdfNameReport3=$dataShopChk->shop_code.'-Resevation_vs_Occupied_with_Meal_Plan_'.date('d-m-Y');
			$shopCodeSub	=$dataShopChk->shop_code;
//echo SalesRegisterReport($period,$id_outlet,$id_shift,$objPHPExcel,$appConnectReport,$connNew,$shop,$cronSet,$pdfNameReport3);			

$DateArray=array();
$DateArray['Day']['StartDate']=date('d-m-Y', strtotime('-1 days'));
$DateArray['Day']['EndDate']=date('d-m-Y', strtotime('-1 days'));

$today = new DateTime(date('d-m-Y', strtotime('-1 days')), new DateTimeZone('UTC'));
$day_of_week = $today->format('w');
$today->modify('- ' . (($day_of_week - 1 + 7) % 7) . 'days');
$sunday = clone $today;
$sunday->modify('+ 6 days');
$wstart= $today->format('Y-m-d');
$wend= $sunday->format('Y-m-d');
$DateArray['week']['StartDate']=date('d-m-Y',strtotime($wstart));
 $DateArray['week']['EndDate']=date('d-m-Y',strtotime($wend)); 

$DateArray['mtd']['StartDate']=date('01-m-Y',strtotime(date('d-m-Y', strtotime('-1 days'))));
$DateArray['mtd']['EndDate']=date('d-m-Y', strtotime('-1 days'));


$id_report_type='270';
//echo FlashReport($DateArray,$id_report_type,$report_show,$_REQUEST['showItemReport'],$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel); 
$period= date('d-m-Y', strtotime('+1 days'))." to ".date('d-m-Y', strtotime('+1 days'));
$id_report_type= "1";
$id_main_group= "undefined";
$id_data_main_group= "undefined";
$id_item= "undefined";
$ReportShowType= "1";
$id_order_by= "undefined";
$id_sub_group= "undefined";
$showItemReport= "0";
$res_bookingStatus_new= '';
$hk_status= "0";
$id_mst_shops=	$dataShopChk->id_shop;
echo FoPlanDateWiseReportDaily($period,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$res_bookingStatus_new,$hk_status,$shop,$pdfNameReport3,$id_mst_shops,$cronSet);
			
			
			$pdfNameReport3;
			

$attach[$dataShopChk->shop_code]['excel'][$dataShopChk->shop_code]=$pdfNameReport3;


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
//die;
//EMAIL START==================================================================================
	print_r($GroupShopid1['Email']);
foreach($GroupShopid1['Email'] as $e=>$email1){
		
			$shop_code=$e;
		
 if($mailto !=''){
	 // && file_exists($attach)){
	 echo 'Attacha=>'.$e;
	 print_r($attach[$e]);
	foreach($attach[$e] as $reportType=>$attachname2)
	{	 
	
	
	
$msg 	 = "Please find the attachment for the Resevation vs Occupied with Meal Plan<br/><br/> RoomStatusHUB Team.";
$sub	 = $shop_code.'-  Resevation vs Occupied with Meal Plan';
$cc='';		
$cc=array();
/*
$cc[]='cm@hirohama.co.in';
$cc[]='cakrishnarajsingh@gmail.com';
$cc[]='kuurakuacs@gmail.com';
$cc[]='accounts.gurgaon@hirohama.co.in';
$cc[]='accounts.manager@hirohama.co.in';
*/
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
//$mail->AddAddress('roomstatushublogs@gmail.com');	
//$mail->AddCC('sundaram@roomstatushub.com');
		 $to_email = $email1['to_email'];
		 $toArray = explode(';',$to_email);
      for($i=0;$i<count($toArray);$i++){ 
  			$mail->addAddress($toArray[$i]);
      }
		 $cc_email = $email1['cc_email'];
		 $ccArray = explode(';',$cc_email);
      for($i=0;$i<count($ccArray);$i++){ 
		  $mail->AddCC($ccArray[$i]);
      }
	
	 
		
		//$mail->AddBCC("sundaram@roomstatushub.com", "support");
		//$mail->AddBCC("roomstatushublogs@gmail.com", "support");
	 //echo $fileType;
		
		//print_r($attachname2);
		//foreach($attachname2 as $fileType=>$attachname3)
		//{ echo 'Shafeeer=====>'.$fileType;
	 	foreach($attachname2 as $attachname)
		{
			if($reportType=='pdf'){
		$attachname=$attachname.'.pdf';
   		$mail->addAttachment($attachPath.$attachname,$attachname,"base64","application/pdf");
			}
			if($reportType=='excel'){
		echo '=====>'.$attachname=$attachname.'.xls';
   		$mail->addAttachment($attachPath.$attachname,$attachname,"base64","application/excel");
			}
		}
	// }
				  
	//die;
	echo 'Email=====>'.$sendMail = $mail->Send(); 
		 	
		
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
						//unlink($attachPath.$attachname);
			
					}
				}
			}
			 //EMAIL REMOVE ATTACHMENT

//EMAIL END ===================================================================================

}
 }
mysqli_close($appConnectReport);
?>