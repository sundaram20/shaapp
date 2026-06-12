<?php
include_once("../../../config/auto_loader.php");
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    
    window.location.href='<?php echo $SITE_URL;?>/crs/adminpanel/index.php';
   
   </script>
<?php	
}

include_once("../functionMtdYtd.php");
echo tableViewMtdYtdfunction($_REQUEST['period'],$_REQUEST['id_hotel'],$_REQUEST['id_mst_hotel'],$_REQUEST['id_group_master'],$_REQUEST['reportType'],$_REQUEST['viewMonthwise'],$_REQUEST['summaryReportType'],$_REQUEST['CronSet']);

?>