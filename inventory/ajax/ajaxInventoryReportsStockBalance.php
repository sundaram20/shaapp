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
include_once("../include/function.php");



//$type=$_POST['type'];

$id_main_group=$_REQUEST['id_main_group'];//is_array($_REQUEST['id_main_group'])?implode(',',$_REQUEST['id_main_group']):'';
$id_sub_group=$_REQUEST['id_sub_group'];//is_array($_REQUEST['id_sub_group'])?implode(',',$_REQUEST['id_sub_group']):'';
$id_items=$_REQUEST['id_item'];//is_array($_REQUEST['id_item'])?implode(',',$_REQUEST['id_item']):'';

$report_show	= $_REQUEST['ReportShowType'];
//print_r($_REQUEST);die;
if($_REQUEST['id_report_type']=='242'){
echo InventoryStockBalanceReport($_REQUEST['period'],$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type'],$report_show,$_REQUEST['id_order_by'],$_REQUEST['showItemReport'],$appConnect,$connNew,$_SESSION['shop'],$cronSet,$pdfName); 
}

if($_REQUEST['id_report_type']=='243'){
echo InventoryStockStatementReport($_REQUEST['period'],$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type'],$report_show,$_REQUEST['id_order_by'],$_REQUEST['showItemReport'],$appConnect,$connNew,$_SESSION['shop'],$cronSet,$pdfName); 
}