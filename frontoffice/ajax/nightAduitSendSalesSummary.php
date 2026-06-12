<?php 
//include_once("../../config/auto_loader.php");
?><?php
//CRON URL
//error_reporting(E_ALL);
//set_time_limit(450000);

///////////////// CRON JOB PATH ///////////////////
$path=$_SERVER['DOCUMENT_ROOT'];

//echo '<pre>';
//print_r($_SESSION);
include_once($path."/frontoffice/functions/nightAuditReport.php");
$DB_HOST = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306 (MySQL)";//"ls-235f49fc2901dbe9e4e44f452f1c69fb1a321fad.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com";
 
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
	$auto_ids_shops	=	$_SESSION['id_app_shop'];
	
	
	$GroupShopid=array();
	 $sqlAutoReport = "SELECT * FROM app_auto_report_config_details where id_auto_report_config='8' and id_shop='".$auto_ids_shops."' and status='1' ";	
	$resAutoReport = mysqli_query($appConnectReport,$sqlAutoReport);
$emailArray=array();
	while($dataAutoReport = mysqli_fetch_object($resAutoReport)){
		
		$id_shop_group	=	$dataAutoReport->id_shop.rand(1000, 9999);//$dataAutoReport->id_shop_group;	
		$GroupShopid[$id_shop_group]['shop_id'][]=$dataAutoReport->id_shop;
		$GroupShopid[$id_shop_group]['Email']['user']['to_email']=$dataAutoReport->to_email;
		$GroupShopid[$id_shop_group]['Email']['user']['cc_email']=$dataAutoReport->cc_email;
		
		
	}
	//echo '<pre>';
	//echo $to_email;
	
	//print_r($GroupShopid);

foreach($GroupShopid as $y=>$GroupShopid1){	
	
		$auto_ids_shops =implode(',',$GroupShopid1['shop_id']);
	
  


$id_shop=2;

$season =array();
$SqlShop = "SELECT name,city FROM `mst_shops` where id='2' and status='1' ";
$resShop = mysqli_query($connNew,$SqlShop);
$rowShop = mysqli_fetch_object($resShop);

date('d-m-Y');
$period	=date('Y-m-d', strtotime('-1 days'));
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

$shop_code	=$_SESSION['shop_code'];
//SalesRegisterReport
$objPHPExcel = new PHPExcel();
  $pdfNameReport3=$_SESSION['shop_code'].'-salesSummaryReports-'.date('d-m-Y');
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

//echo $period;die;
echo nightAuditReports($period,$id_report_type,$report_show,$_REQUEST['showItemReport'],$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel);



			//posSalesRegisterReport($period,$id_outlet,$id_shift,$objPHPExcel,$connNew,$shop,$cronSet,$pdfNameReport3);
			//$attach['consolidatedItemWiseReport']['pdf'][$shop_code]=$pdfNameReport1;
			//$attach[$shop_code]['pdf'][]=$pdfNameReport2;
$attach['SalesRegisterReport']['excel'][$shop_code]=$pdfNameReport3;

	
//echo '<pre>';
//print_r($attach);

//die;		
$attachPath=$path.'/mailattach/';
//$attach=array();
//$attach['pdf'][]=$pdfNameReport1;
//$attach['pdf'][]=$pdfNameReport2;
//$attach['excel'][]=$pdfNameReport3;
$cc = array();

			
 $mailto = 'roomstatushublogs@gmail.com';

//EMAIL START==================================================================================
foreach($GroupShopid1['Email'] as $e=>$email1){
		
			
		
 if($mailto !=''){
	 // && file_exists($attach)){
	 
	 
	foreach($attach as $reportType=>$attachname2)
	{	 
	foreach($attachname2 as $fileType2=>$attachname13)
		{  
	 	foreach($attachname13 as $k2=>$attachname12)
		{
			$shop_code =$k2;
		}
	}
	
$msg 	 = "Please find the attachment for the Daily Sales Summary Report<br/><br/> RoomStatusHUB Team.";
$sub	 = $shop_code .'- Daily Sales Summary Report';
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
		foreach($attachname2 as $fileType=>$attachname3)
		{
	 	foreach($attachname3 as $attachname)
		{
			if($fileType=='pdf'){
		$attachname=$attachname.'.pdf';
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

}
 }
mysqli_close($appConnectReport);
?>