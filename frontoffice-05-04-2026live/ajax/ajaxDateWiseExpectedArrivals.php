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
include_once("../functions/functionDateWiseExpectedArrivalsReport.php");



//$type=$_POST['type'];

$id_main_group=$_REQUEST['id_main_group'];//is_array($_REQUEST['id_main_group'])?implode(',',$_REQUEST['id_main_group']):'';
$id_sub_group=$_REQUEST['id_sub_group'];//is_array($_REQUEST['id_sub_group'])?implode(',',$_REQUEST['id_sub_group']):'';
$id_items=$_REQUEST['id_item'];//is_array($_REQUEST['id_item'])?implode(',',$_REQUEST['id_item']):'';

$report_show	= $_REQUEST['ReportShowType'];
//print_r($_REQUEST);die;
$cronSet='0';
echo ExpectedArrivalsDateWiseReport($_REQUEST['period'],$report_show,$_SESSION['shop'],$pdfName,$cronSet); 
