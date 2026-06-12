<?php
include_once("../../config/auto_loader.php");
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}


//error_reporting(E_ALL);
include_once("../include/functionFlashReport.php");



//$type=$_POST['type'];

$id_main_group=$_REQUEST['id_main_group'];//is_array($_REQUEST['id_main_group'])?implode(',',$_REQUEST['id_main_group']):'';
$id_sub_group=$_REQUEST['id_sub_group'];//is_array($_REQUEST['id_sub_group'])?implode(',',$_REQUEST['id_sub_group']):'';
$id_items=$_REQUEST['id_item'];
//is_array($_REQUEST['id_item'])?implode(',',$_REQUEST['id_item']):'';

$report_show	= $_REQUEST['ReportShowType'];
//print_r($_REQUEST);
$kot_nc	= $_REQUEST['kot_nc'];
$cronSet='';
$pdfName='';

$DateArray=array();
$DateArray['Day']['StartDate']=$_REQUEST['period'];
$DateArray['Day']['EndDate']=$_REQUEST['period'];
if($_REQUEST['id_report_type']=='270'){
$today = new DateTime($_REQUEST['period'], new DateTimeZone('UTC'));
$day_of_week = $today->format('w');
$today->modify('- ' . (($day_of_week - 1 + 7) % 7) . 'days');
$sunday = clone $today;
$sunday->modify('+ 6 days');
$wstart= $today->format('Y-m-d');
$wend= $sunday->format('Y-m-d');
$DateArray['week']['StartDate']=date('d-m-Y',strtotime($wstart));
 $DateArray['week']['EndDate']=date('d-m-Y',strtotime($wend)); 
}
//die;
$DateArray['mtd']['StartDate']=date('01-m-Y',strtotime($_REQUEST['period']));
$DateArray['mtd']['EndDate']=$_REQUEST['period'];
//die;
echo FlashReport($DateArray,$_REQUEST['id_report_type'],$report_show,$_REQUEST['showItemReport'],$kot_nc,$appConnect,$connNew,$_SESSION['shop'],$cronSet,$pdfName,$objPHPExcel); 

