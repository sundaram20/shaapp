<?php include_once("../../config/auto_loader.php");
if (($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')) {
?>
<script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
</script>
<?php
}
include_once("../functions/functionDateWiseOccupancyReport.php");

$id_main_group = $_REQUEST['id_main_group'];
$id_sub_group = $_REQUEST['id_sub_group'];
$id_items = $_REQUEST['id_item'];

$report_show = $_REQUEST['ReportShowType'];

echo FoDateWiseReport($_REQUEST['period'],$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type'],$report_show,$_REQUEST['id_order_by'],$_REQUEST['showItemReport']);
