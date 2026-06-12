<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>

<?php include_once("../config/auto_loader.php");

if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'activate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_BE_INVENTORY."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'deactivate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_BE_INVENTORY."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Room Type '.selectColumn(TBL_BE_INVENTORY,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
		
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Room Type '.selectColumn(TBL_BE_INVENTORY,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}
else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'delete');
	$delSql = "DELETE FROM `".TBL_BE_INVENTORY."` WHERE `id` = '".$_REQUEST['delId']."'";
	
	$sqlDelUserLevel = selectRow(TBL_BE_INVENTORY," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Room Type '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete room type '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_BE_INVENTORY."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_BE_INVENTORY."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_BE_INVENTORY."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}
// ----------cate---------
$sql = " SELECT *,MAX(allocation_date) AS max_date FROM `".TBL_BE_INVENTORY."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  ";

if($_REQUEST['id_hotel'] != ''){
	$sql .= " AND `id_hotel` LIKE '".addslashes($_REQUEST['id_hotel'])."'";
}
if($_REQUEST['id_room'] != ''){
	$sql .= " AND `id_room` = '".addslashes($_REQUEST['id_room'])."'";
}

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `last_modified` DESC";
}else{
	$sql .= " GROUP BY id_hotel,id_room ORDER BY `last_modified` DESC";
}
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<style>
    .content-header>.breadcrumb {
        top: 5px;
        font-size: 11px;
        padding: 0;
        position: relative;
        top: 0 !important;
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: #f4f5f7;
        margin: 0;
        padding: 0;
    }

    .summary-box {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        padding: 20px;
        display: flex;
        align-items: center;
        margin-bottom: 20px;
        transition: all 0.3s ease;
    }

    .summary-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1);
    }

    .summary-box .icon {
        font-size: 40px;
        color: #fff;
        background-color: rgba(0, 0, 0, 0.1);
        padding: 15px;
        border-radius: 50%;
        margin-right: 15px;
    }

    .summary-box .details h3 {
        margin: 0;
        font-size: 30px;
        33 font-weight: bold;
    }

    .summary-box .details p {
        margin: 0;
        font-size: 16px;
        color: #6c757d;
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff, #00d4ff);
    }

    .bg-gradient-success {
        background: linear-gradient(135deg, #28a745, #81fbb8);
    }

    .bg-gradient-warning {
        background: linear-gradient(135deg, #ffc107, #ffecb3);
    }

    .bg-gradient-danger {
        background: linear-gradient(135deg, #dc3545, #ff758f);
    }

    .chart-box {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        /* padding: 20px; */
        margin-bottom: 20px;
    }

    .chart-box-header {
        border-bottom: 1px solid #e9e9e9;
        padding: 0.8rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .chart-box-footer {
        border-top: 1px solid #e9e9e9;
    }

    .chart-box-body {

        padding: 0px 6px;
    }



    .chart-box .chart-box-header h4 {
        /* margin-bottom: 20px; */
        font-size: 1.5rem;
        font-weight: 600;
        color: #333;
        margin: 0;
    }

    .tdStatTable tbody tr td {
        padding: 0.7rem !important;
        font-size: 1.3rem !important;
        border-bottom: 1px solid #dbdbdb;
    }


    .tdStatTable thead tr td {
        padding: 0.7rem !important;
        font-size: 1.3rem !important;
        border-bottom: 1px solid #dbdbdb;
    }


    .guestListTbl .roomN,
    .aTime {

        text-align: center !important;

    }

    .guestListTbl thead tr td {

        font-weight: 600 !important;
    }

    .chart-box-body {

        /* Adjust height if necessary */
        overflow-y: auto;
        /* border: 1px solid #ccc; */
    }


    .scroll-container {
        height: 24rem;
        /* Matches chart-box-body height */
        overflow: hidden;
        /* Hide overflow for visual effect */
        position: relative;
    }

    .scroll-content {
        height: 100%;
        /* Full height for scrolling */
        overflow-y: auto;
        /* position: absolute; */
        top: 0;
    }

    /* WebKit browsers */
    .scroll-content::-webkit-scrollbar {
        width: 6px;
        /* Width of the scrollbar */

    }

    .scroll-content::-webkit-scrollbar-thumb {
        background: #888;
        /* Color of the scrollbar thumb */
        border-radius: 10px;
        /* Roundness of the scrollbar thumb */

    }

    .scroll-content::-webkit-scrollbar-thumb:hover {
        background: #555;
        /* Darker color on hover */
    }

    .cstmSercies-sort-arrows {
        display: inline-block;
        vertical-align: middle;
        margin-left: 5px;
        font-size: 14px;
        color: #3a3838;
        cursor: pointer;
    }

    .cstmSercies-sort-arrows i {
        display: block;
        line-height: 0.2;
        /* Ensure arrows are stacked correctly */
    }

    /* Optional: Add hover effect */
    .cstmSercies-sort-arrows:hover i {
        color: #000;
        /* Darken on hover to indicate interactivity */
    }

    /* Additional styling to align the text with the arrows */
    thead td {
        position: relative;
        padding-right: 15px;
        /* Adjust for arrow spacing */
        text-align: left;
    }

    .td-container {
        display: flex;
        align-items: center;
    }

    .td-container .guest-name {
        margin-right: 8px;
        /* Adjust spacing as needed */
    }

    .tod {
        font-size: 11px !important;
    }

    <style>

    /* Base styles for larger screens */
    .responsive-container {
        width: 45%;
        box-shadow: rgba(0, 0, 0, 0.04) 0px 3px 5px;
        background: #fff;
        margin: 10px;
        padding: 15px 10px;
        border-radius: 7px;
    }

    .responsive-header {
        margin: 0px;
        border-bottom: 1px solid #d9d9d9;
        padding: 0px 0px 5px 10px;
        margin-bottom: 5px;
    }

    .responsive-content {
        display: flex;
        flex-wrap: wrap;
    }

    .responsive-item {
        width: 25%;
        border-right: 1px solid #cfcfcf;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .responsive-item:last-child {
        border-right: none;
    }

    <blade media|%20(max-width%3A%20768px)%20%7B%0D>.responsive-container {
        width: 90%;
    }

    .responsive-item {
        width: 50%;
        border-right: none;
    }

    .responsive-item:nth-child(odd) {
        border-right: 1px solid #cfcfcf;
    }
    }

    <blade media|%20(max-width%3A%20480px)%20%7B%0D>.responsive-item {
        width: 100%;
    }
    }
</style>

</style>

<div class="content-wrapper" style="background : #f4f5f7!important;">
    <!-- Content Header (Page header) -->

    <section class="content-header "
        style="border-bottom: 1px solid #dfdfdf; padding: 0.7rem!important; display: flex; align-items: center; justify-content : space-between; background : #fff!important;">
        <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
            <span style="font-size : 2rem;">&nbsp;<i class="fa "></i>Front-Office Dashboard</span>


        </h3>
        <?php echo breadCrumbs(); ?>
    </section>
    <!-- Content Header ends -->


    <section class="content" style="padding-left: 0; padding-right: 0;">

        <div class="container-fluid" style="padding : 0px 10px!important;">

            <style>
                .cstmDashToday-section h3 {
                    font-size: 1.3rem;
                    font-weight: 600;
                    margin: 0 !important;
                    margin-bottom: 16px !important;
                }

                .cstmDashToday-section p {
                    font-size: 14px !important;
                    margin: 0 !important;
                    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
                }

                .cstmDashToday-section {
                    margin-bottom: 20px;
                }
            </style>

            <div class="cstmDashToday-section">
                <div style="">
                    <!-- <div style="width:55%;">


                        <div style="box-shadow:rgba(0, 0, 0, 0.04) 0px 3px 5px;background:#fff;margin:10px;padding:15px 10px;border-radius:8px;">
                            <h4 style="margin:0px;border-bottom:1px solid #d9d9d9;padding:0px 0px 5px 10px;margin-bottom:5px;">Today</h4>
                            <div style="display:flex;flex-wrap:wrap;">
                                <div style="width:20%!important;border-right:1px solid #cfcfcf;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Rooms Occupied</h3>
                                        <p id="occupied_rooms">0</p>
                                    </div>
                                </div>
                                <div style="width:20%!important;border-right:1px solid #cfcfcf;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Rooms Vacant</h3>
                                        <p id="vacent_rooms">0</p>
                                    </div>
                                </div>
                                <div style="width:60%!important;">
                                    <div style="font-size:16px!important;font-weight:600;width:100%!important;text-align:center;margin-bottom:10px;">In-house</div>
                                    <div style="display:flex;justify-content:space-around;">
                                        <div style="display:flex;align-items:center;justify-content:center; ">
                                            <p>Adult&nbsp;-&nbsp;</p>
                                            <p id="adults">0</p>
                                        </div>
                                        <div style="display:flex;align-items:center;justify-content:center;">
                                            <p>Child&nbsp;-&nbsp;</p>
                                            <p id="child_above_5_year">0</p>
                                        </div>
                                        <div style="display:flex;align-items:center;justify-content:center;">
                                            <p>Below 5&nbsp;-&nbsp;</p>
                                            <p id="child_below_5_year">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="width:45%">
                        <div style="box-shadow:rgba(0, 0, 0, 0.04) 0px 3px 5px;background:#fff;margin:10px;padding:15px 10px;border-radius:7px;">
                            <h4 style="margin:0px;border-bottom:1px solid #d9d9d9;padding:0px 0px 5px 10px;margin-bottom:5px;">Today</h4>
                            <div style="display:flex;flex-wrap:wrap;">
                                <div style="width:25%!important;border-right:1px solid #cfcfcf;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Today's Check-In</h3>
                                        <p id="checkin">0</p>
                                    </div>
                                </div>
                                <div style="width:25%!important;border-right:1px solid #cfcfcf;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Arrivals Pending</h3>
                                        <p id="arrival_pendings">0</p>
                                    </div>
                                </div>
                                <div style="width:25%!important;border-right:1px solid #cfcfcf;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Today's Check-Out</h3>
                                        <p id="checkout">0</p>
                                    </div>
                                </div>
                                <div style="width:25%!important;">
                                    <div style="display:flex;flex-direction:column;align-items:center;justify-content:center;">
                                        <h3>Check-Out Pending</h3>
                                        <p id="checkout_pendings">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <style>
                        .section {
                            background: #fff;
                            box-shadow: rgba(0, 0, 0, 0.04) 0px 3px 5px;
                            border-radius: 8px;
                            padding: 15px 10px;
                        }

                        .section h4 {
                            margin: 0;
                            border-bottom: 1px solid #d9d9d9;
                            padding: 0 0 5px 10px;
                            margin-bottom: 5px;
                        }

                        .section-55 {
                            display: flex;
                            flex-direction: column;
                        }

                        .section-55 .info {
                            display: flex;
                            flex-wrap: wrap;
                        }





                        .info div p {
                            margin: 0;
                            font-size: 24px;
                            font-weight: bold;
                        }



                        .section-45 {
                            display: flex;
                            flex-direction: column;
                            justify-content: center;
                        }

                        .card-containerFo {
                            display: flex;
                            flex-wrap: wrap;

                            /* Space between cards */
                            justify-content: space-between;
                            /* Distribute space between cards */
                        }

                        .card-containerFo .card {
                            background: #fff;

                            border-right: 1px solid #cfcfcf;

                            text-align: center;
                            width: 25%;
                            box-sizing: border-box;
                            /* Ensure padding and border are included in width */
                        }

                        .card-containerFo div:last-child {

                            border-right: 0px solid #cfcfcf;
                        }

                        .card h3 {
                            margin: 0 0 10px 0;
                        }

                        .card p {
                            margin: 0;
                            font-size: 24px;
                            font-weight: bold;
                        }

                        .roomOcc,
                        .vacantRoom {
                            width: 20%;
                            border-right: 1px solid #cfcfcf;
                        }

                        .roomOcc,
                        .vacantRoom div {

                            text-align: center;
                        }

                        .inHouse {
                            width: 60%;
                        }

                        .inHousePCont {
                            display: flex;
                            justify-content: space-around;
                            align-items: center;
                        }


                        @media only screen and (max-width: 768px) {
                        
                            .roomOcc,
                        .vacantRoom {
                            width: 50%;
                        }

                        .vacantRoom {
                           
                            border-right: 0px solid #cfcfcf;
                        }

                        .inHouse {
                            width: 100%;
                            border-top: 1px solid #cfcfcf;
                            margin-top : 5px;
                             padding-top : 10px;

                        }

                        .col-md-5 .section {

                            margin-top : 15px!important;
                        }

                        .card-containerFo .card{
                            width: 50%;
                            border-bottom: 1px solid #cfcfcf;
                            padding : 10px 5px;
                        }

                        .card-containerFo div:nth-child(2) {

                         border-right: 0px solid #cfcfcf;
                            }   

                            .card-containerFo div:nth-child(3) {

                         border-bottom: 0px solid #cfcfcf;
                                                         }   

                                                         .card-containerFo div:nth-child(4) {

border-bottom: 0px solid #cfcfcf;
                                }   
                                
                                .thisCollectionBx{
                                    width : 99%!important;
                                }


                        }



                        /* Styles for In-house section */
                    </style>
<?php
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
$today = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$yesterday = date('d-m-Y',strtotime('-1 day',strtotime($today)));
?>
                    <div class="" style="display : flex;  flex-wrap : wrap;">
                        <div class="col-md-7 col-sm-12">
                            <div class="section section-55">
                                <h4><?php echo $today; ?></h4>
                                <div class="info">
                                    <div class="roomOcc">
                                        <div>
                                            <h3>Rooms Occupied</h3>
                                            <p id="occupied_rooms">0</p>
                                        </div>
                                    </div>
                                    <div class="vacantRoom">
                                        <div>
                                            <h3>Rooms Vacant</h3>
                                            <p id="vacant_rooms">0</p>
                                        </div>
                                    </div>
                                    <div class="inHouse">
                                        <div
                                            style="font-size:1.4rem;font-weight:600;text-align:center;margin-bottom:10px;">
                                            In-house</div>
                                        <div class="inHousePCont">
                                            <p id="adults">0</p>
                                            <p id="child_above_5_year">0</p>
                                            <p id="child_below_5_year">0</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-12">
                            <div class="section ">
                                <h4><?php echo $today; ?></h4>
                                <div class="card-containerFo">
                                    <div class="card">
                                        <h3><?php echo $today; ?>'s Check-In</h3>
                                        <p id="checkin">0</p>
                                    </div>
                                    <div class="card">
                                        <h3>Arrivals Pending</h3>
                                        <p id="arrival_pendings">0</p>
                                    </div>
                                    <div class="card">
                                        <h3><?php echo $today; ?>'s Check-Out</h3>
                                        <p id="checkout">0</p>
                                    </div>
                                    <div class="card">
                                        <h3>Check-Out Pending</h3>
                                        <p id="checkout_pendings">0</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex" style="flex-wrap : wrap;">



                <div class="col-md-4 col-sm-12">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><?php echo $today; ?>'s Check-in</h4>
                            <div style="display: flex;">
                                <button
                                    style="padding: 0.36rem !important; margin-right: 8px; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg class="text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="#0000007d"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <button
                                    style="padding: 0.5rem!important; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#0000007d"
                                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="chart-box-body" style="">
                            <div class="scroll-container">
                                <div class="scroll-content">
                                    <table class="tdStatTable guestListTbl" style="width: 100%!important;">
                                        <thead style="">
                                            <tr style="position: sticky; top: 0; background: #fff!important;">
                                                <td class="td-container">
                                                    Guest Name
                                                    <!-- <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span> -->
                                                </td>
                                                <td class="roomN">
                                                    Room No.
                                                    <!-- <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span> -->
                                                </td>
                                                <td class="aTime">
                                                    No Of Adult
                                                    <!-- <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span> -->
                                                </td>
                                                <td class="aTime">
                                                    No Of Children
                                                    <!-- <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span> -->
                                                </td>
                                                <td class="aTime">
                                                    Plan
                                                    <!-- <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span> -->
                                                </td>
                                                <!-- <td class="aTime">
                                                    Time of Arrival
                                                    <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span>
                                                </td> -->
                                            </tr>
                                        </thead>

                                        <tbody id="checkin_table"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="chart-box-footer"
                            style="padding: 0px 10px!important; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-weight: 600!important; font-size: 1.4rem;">7</span> of <span
                                    style="font-weight: 600!important; font-size: 1.4rem;">25</span>
                            </div>
                            <div>
                                <button type="button" class="prevBtn" style="background: none; border: 0;">
                                    <svg class="hover:bg-gray-200 rounded-2xl w-7 h-7 text-gray-800 dark:text-white transition-all text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m14 8-4 4 4 4"></path>
                                    </svg>
                                </button>
                                <button type="button" class="nextBtn" style="background: none; border: 0;">
                                    <svg class="hover:bg-gray-200 rounded-2xl w-7 h-7 text-gray-800 dark:text-white transition-all text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m10 16 4-4-4-4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>

                <script>
                    document.querySelectorAll('.nextBtn').forEach((nextBtn, index) => {
                        nextBtn.addEventListener('click', function () {
                            const scrollContent = this.closest('.chart-box').querySelector(
                                '.scroll-content');
                            console.log(`Button ${index + 1} clicked, scrolling content:`,
                                scrollContent); // Debugging log
                            scrollContent.scrollBy({
                                top: scrollAmount,
                                behavior: 'smooth'
                            });
                            console.log('Scrolled down to:', scrollContent.scrollTop);
                        });
                    });

                    document.querySelectorAll('.prevBtn').forEach((prevBtn, index) => {
                        prevBtn.addEventListener('click', function () {
                            const scrollContent = this.closest('.chart-box').querySelector(
                                '.scroll-content');
                            console.log(`Button ${index + 1} clicked, scrolling content:`,
                                scrollContent); // Debugging log
                            scrollContent.scrollBy({
                                top: -scrollAmount,
                                behavior: 'smooth'
                            });
                            console.log('Scrolled up to:', scrollContent.scrollTop);
                        });
                    });
                </script>


                <div class="col-md-4 col-sm-12">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><?php echo $today; ?>'s Check-out</h4>
                            <div style="display: flex;">
                                <button
                                    style="padding: 0.36rem !important; margin-right: 8px; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg class="text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="#0000007d"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <button
                                    style="padding: 0.5rem!important; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#0000007d"
                                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="chart-box-body" style="">
                            <div class="scroll-container">
                                <div class="scroll-content">
                                    <table class="tdStatTable guestListTbl" style="width: 100%!important;">
                                        <thead style="">
                                            <tr style="position: sticky; top: 0; background: #fff!important;">
                                                <td class="td-container">
                                                    Guest Name
                                                    <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span>
                                                </td>
                                                <td class="roomN">
                                                    Room No.
                                                    <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span>
                                                </td>
                                                <td class="aTime">
                                                    <span class="tod"> Time of Depature</span>
                                                    <span class="cstmSercies-sort-arrows">
                                                        <i class="fa-solid fa-sort-up"></i>
                                                        <i class="fa-solid fa-sort-down"></i>
                                                    </span>
                                                </td>
                                            </tr>
                                        </thead>

                                        <tbody id="checkout_table"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- <div class="chart-box-footer"
                            style="padding: 0px 10px!important; display: flex; align-items: center; justify-content: space-between;">
                            <div>
                                <span style="font-weight: 600!important; font-size: 1.4rem;">7</span> of <span
                                    style="font-weight: 600!important; font-size: 1.4rem;">25</span>
                            </div>
                            <div>
                                <button type="button" class="prevBtn" style="background: none; border: 0;">
                                    <svg class="hover:bg-gray-200 rounded-2xl w-7 h-7 text-gray-800 dark:text-white transition-all text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m14 8-4 4 4 4"></path>
                                    </svg>
                                </button>
                                <button type="button" class="nextBtn" style="background: none; border: 0;">
                                    <svg class="hover:bg-gray-200 rounded-2xl w-7 h-7 text-gray-800 dark:text-white transition-all text-gray-700"
                                        aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                        fill="none" viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="m10 16 4-4-4-4"></path>
                                    </svg>
                                </button>
                            </div>
                        </div> -->
                    </div>
                </div>

                <script>
                    const scrollAmount = 12 * 16; // Convert rem to px (assuming 16px base font-size)

                    document.querySelectorAll('.nextBtn').forEach((nextBtn, index) => {
                        nextBtn.addEventListener('click', function () {
                            const scrollContent = this.closest('.chart-box').querySelector(
                                '.scroll-content');
                            console.log(`Button ${index + 1} clicked, scrolling content:`,
                                scrollContent); // Debugging log
                            scrollContent.scrollBy({
                                top: scrollAmount,
                                behavior: 'smooth'
                            });
                            console.log('Scrolled down to:', scrollContent.scrollTop);
                        });
                    });

                    document.querySelectorAll('.prevBtn').forEach((prevBtn, index) => {
                        prevBtn.addEventListener('click', function () {
                            const scrollContent = this.closest('.chart-box').querySelector(
                                '.scroll-content');
                            console.log(`Button ${index + 1} clicked, scrolling content:`,
                                scrollContent); // Debugging log
                            scrollContent.scrollBy({
                                top: -scrollAmount,
                                behavior: 'smooth'
                            });
                            console.log('Scrolled up to:', scrollContent.scrollTop);
                        });
                    });
                </script>
                <style>
                    .cstmSeries-collections-table td {
                        text-align: left;
                        padding: 10px;
                    }

                    .cstmSeries-collection-item {
                        display: flex;
                        flex-direction: column;
                        font-weight: 600;
                        font-size: 1.4rem !important;
                    }

                    .cstmSeries-collection-item span:first-child {
                        font-size: 1.4rem !important;
                    }

                    .cstmSeries-total {
                        font-weight: 700;
                        font-size: 1.6rem !important;
                        color: #000;
                        border-left: 1px solid #ddd;
                        padding-left: 10px;
                    }

                    .cstmSeries-collections-table tr td:not(:first-child) {
                        border-left: 1px solid #ddd;
                    }
                </style>
                <div class="col-md-4 col-sm-12 thisCollectionBx">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4><?php echo $yesterday; ?></h4>
                            <div style="display: flex;">
                                <button
                                    style="padding: 0.36rem !important; margin-right: 8px; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg class="text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="#0000007d"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                </button>

                                <button
                                    style="padding: 0.5rem!important; border: 0!important; box-shadow: inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius: 3px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#0000007d"
                                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="chart-box-body" style="">
                            <div class="scroll-container">
                                <div class="scroll-content">
                                    <table class="tdStatTable" style="width: 100%!important;">
                                        <tbody>
                                            <tr>
                                                <td style="font-weight : 600; font-size : 1.4rem!important;">Room Nights
                                                </td>
                                                <td style="font-weight : 600; font-size : 1.4rem!important;"
                                                    id="yesterday_room_nights">0</td>
                                                <td style="font-size : 1.5rem!important;">
                                                    <div style="margin-left : 10px;" id="room_color">
                                                        <i id="room_icon"></i>
                                                        <span style="padding-left : 0.8rem;"
                                                            id="room_percentage">0%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight : 600; font-size : 1.4rem!important;">ARR</td>
                                                <td style="font-weight : 600; font-size : 1.4rem!important;"
                                                    id="yesterday_arr">0</td>
                                                <td style="font-size : 1.5rem!important;">
                                                    <div style="margin-left : 10px;" id="arr_color">
                                                        <i id="arr_icon"></i>
                                                        <span style="padding-left : 0.8rem;"
                                                            id="arr_percentage">0%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="font-weight : 600; font-size : 1.4rem!important;">Revenue (in
                                                    lakhs)</td>
                                                <td style="font-weight : 600; font-size : 1.4rem!important; max-width : 33px;"
                                                    id="yesterday_revenue">0</td>
                                                <td style="font-size : 1.5rem!important;">
                                                    <div style="margin-left : 10px;" id="revenue_color">
                                                        <i id="revenue_icon"></i>
                                                        <span style="padding-left : 0.8rem;"
                                                            id="revenue_percentage">0%</span>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <table class="tdStatTable cstmSeries-collections-table"
                                        style="width: 100%!important; margin-top: 10px; border-top: 1px solid #ddd;">
                                        <tr>
                                            <td style="font-weight : 600; font-size : 1.4rem!important;">Collection</td>
                                            <td class="cstmSeries-collection-item">
                                                <span>💵 Cash</span></br><span
                                                    id="yesterday_cash_receipt_amount">0</span>
                                            </td>
                                            <td class="cstmSeries-collection-item">
                                                <span>💳 Others</span></br><span
                                                    id="yesterday_other_receipt_amount">0</span>
                                            </td>
                                            <td class="cstmSeries-total">
                                                <strong>Total</strong></br><strong
                                                    id="yesterday_total_receipt_amount">0</strong>
                                            </td>
                                        </tr>
                                    </table>


                                </div>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- <div class="col-lg-6">
                    <div class="panel panel-default cstmSerciesPanelCustom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Monthly Revenue</h3>
                        </div>
                        <div class="panel-body">
                            <div id="cstmSerciesMonthlyRevenueChart" class="cstmSerciesChartContainer"></div>
                        </div>
                    </div>
                </div>



                <div class="col-lg-6">
                    <div class="panel panel-default cstmSerciesPanelCustom">
                        <div class="panel-heading">
                            <h3 class="panel-title">Room Nights</h3>
                        </div>
                        <div class="panel-body">
                            <div id="cstmSerciesRoomNightsChart" class="cstmSerciesChartContainer"></div>
                        </div>
                    </div>
                </div> -->


                <div class="col-lg-8">
                    <div class="chart-box">
                        <div class="chart-box-header">
                            <h4>Occupancy Report</h4>
                            <div style="display : flex;">
                                <button
                                    style="padding: 0.36rem !important; margin-right : 8px; border : 0!important; box-shadow : inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius : 3px;">
                                    <svg class="text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="#0000007d"
                                        viewBox="0 0 24 24">
                                        <path fill-rule="evenodd"
                                            d="M5 5a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1h1a1 1 0 0 0 1-1 1 1 0 1 1 2 0 1 1 0 0 0 1 1 2 2 0 0 1 2 2v1a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V7a2 2 0 0 1 2-2ZM3 19v-7a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2Zm6.01-6a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm-10 4a1 1 0 1 1 2 0 1 1 0 0 1-2 0Zm6 0a1 1 0 1 0-2 0 1 1 0 0 0 2 0Zm2 0a1 1 0 1 1 2 0 1 1 0 0 1-2 0Z"
                                            clip-rule="evenodd"></path>
                                    </svg></button>

                                <button
                                    style="padding: 0.5rem!important; border : 0!important; box-shadow : inset 0 2px 4px 0 rgb(0 0 0 / 0.05); border-radius : 3px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="#0000007d"
                                        class="bi bi-bar-chart-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1z">
                                        </path>
                                    </svg></button>
                            </div>
                        </div>

                        <div class="chart-box-body">
                            <div class="occTableScrollCont" style="height: 24rem; overflow-y: auto;">
                                <table class="tdStatTable guestListTbl" style="width: 100%;">
                                    <thead>
                                        <tr class="thisIsOccTr" style="position: sticky; top: 0; background: #fff;">
                                            <td class="text-center">Date</td>
                                            <td class="text-center">Inventory</td>
                                            <!--<td class="text-center">Availability</td>-->
                                            <!--<td class="text-center">Confirmed RNS</td>-->

                                            <td class="text-center">Occupied Rooms</td>
                                            <td class="text-center">Occupancy %</td>

                                        </tr>
                                    </thead>
                                    <tbody id="data-rows" style="font-size: 1.4rem;"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>





            </div>




            <style>
                .cstmSerciesDashboardHeader {
                    margin-bottom: 30px;
                    text-align: center;
                }

                .cstmSerciesPanelCustom {
                    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                }

                .cstmSerciesTableResponsive {
                    margin-top: 20px;
                }

                .cstmSerciesChartContainer {
                    margin-top: 30px;
                }
            </style>
            <!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
            <script type="text/javascript">
                google.charts.load('current', {
                    'packages': ['corechart', 'bar']
                });
                google.charts.setOnLoadCallback(drawCharts);

                function drawCharts() {
                    // Monthly Revenue Chart
                    var revenueData = google.visualization.arrayToDataTable([
                        ['Month', 'Revenue'],
                        ['January', 8000],
                        ['February', 8500],
                        ['March', 9500],
                        ['April', 11000],
                        ['May', 10500],
                        ['June', 12000],
                        ['July', 13000],
                        ['August', 12500],
                        ['September', 14000],
                        ['October', 15000],
                        ['November', 14500],
                        ['December', 15500]
                    ]);
                    var revenueOptions = {
                        title: 'Monthly Revenue',
                        hAxis: {
                            title: 'Month'
                        },
                        vAxis: {
                            title: 'Revenue (in USD)'
                        },
                        colors: ['#1b9e77']
                    };
                    var revenueChart = new google.visualization.ColumnChart(document.getElementById(
                        'cstmSerciesMonthlyRevenueChart'));
                    revenueChart.draw(revenueData, revenueOptions);

                    // Room Nights Chart
                    var roomNightsData = google.visualization.arrayToDataTable([
                        ['Month', 'Room Nights'],
                        ['January', 400],
                        ['February', 450],
                        ['March', 500],
                        ['April', 550],
                        ['May', 600],
                        ['June', 650],
                        ['July', 700],
                        ['August', 750],
                        ['September', 800],
                        ['October', 850],
                        ['November', 900],
                        ['December', 950]
                    ]);
                    var roomNightsOptions = {
                        title: 'Room Nights',
                        hAxis: {
                            title: 'Month'
                        },
                        vAxis: {
                            title: 'Room Nights'
                        },
                        colors: ['#d95f02']
                    };
                    var roomNightsChart = new google.visualization.ColumnChart(document.getElementById(
                        'cstmSerciesRoomNightsChart'));
                    roomNightsChart.draw(roomNightsData, roomNightsOptions);
                }
            </script>

            <div class="container">
                <div class="row">


                </div>
            </div> -->

            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>




        </div>




    </section>

</div>
<script type="text/javascript">
    $.ajax({
        type: "POST",
        url: 'ajax/ajaxGetDashboardData.php',
        dataType: 'json',
        success: function (response) {
            if (response != '') {
                $('#occupied_rooms').text(response.occupied_rooms);
                $('#vacant_rooms').text(response.vacant_rooms);
                $('#checkin').text(response.checkin);
                $('#arrival_pendings').text(response.arrival_pendings);
                $('#checkout').text(response.checkout);
                $('#checkout_pendings').text(response.checkout_pendings);
                $('#adults').text("Adults - " + response.adults);
                $('#child_below_5_year').text("Below 5 - " + response.child_below_5_year);
                $('#child_above_5_year').text("Child - " + response.child_above_5_year);
                $('#yesterday_room_nights').text(response.yesterday_room_nights);
                $('#yesterday_revenue').text(response.yesterday_revenue);
                $('#yesterday_arr').text(response.yesterday_arr);
                $('#yesterday_total_receipt_amount').text(response.yesterday_total_receipt_amount);
                $('#yesterday_cash_receipt_amount').text(response.yesterday_cash_receipt_amount);
                $('#yesterday_other_receipt_amount').text(response.yesterday_other_receipt_amount);
                if (response.room_percentage > 0) {
                    $('#room_percentage').text('+' + response.room_percentage + '%');
                    $('#room_color').css('color', 'rgb(30, 166, 80) !important');
                    $('#room_icon').addClass('fa-solid fa-arrow-up');
                } else {
                    $('#room_percentage').text(response.room_percentage + '%');
                    $('#room_color').css('color', '#dc2626 !important');
                    $('#room_icon').addClass('fa-solid fa-arrow-down');
                }

                if (response.revenue_percentage > 0) {
                    $('#revenue_percentage').text('+' + response.revenue_percentage + '%');
                    $('#revenue_color').css('color', 'rgb(30, 166, 80) !important');
                    $('#revenue_icon').addClass('fa-solid fa-arrow-up');
                } else {
                    $('#revenue_percentage').text(response.revenue_percentage + '%');
                    $('#revenue_color').css('color', '#dc2626 !important');
                    $('#revenue_icon').addClass('fa-solid fa-arrow-down');
                }

                if (response.arr_percentage > 0) {
                    $('#arr_percentage').text('+' + response.arr_percentage + '%');
                    $('#arr_color').css('color', 'rgb(30, 166, 80) !important');
                    $('#arr_icon').addClass('fa-solid fa-arrow-up');
                } else {
                    $('#arr_percentage').text(response.arr_percentage + '%');
                    $('#arr_color').css('color', '#dc2626 !important');
                    $('#arr_icon').addClass('fa-solid fa-arrow-down');
                }

                var checkin_html = '';
                if (Object.keys(response.checkin_results).length > 0) {
                    Object.values(response.checkin_results).forEach(checkin => {
                        checkin_html += '<tr><td>' + checkin.guest_name + '</td><td>' + checkin
                            .room_no + '</td><td>' + checkin.adults_per_room + '</td><td>'
                             + checkin.child_per_room + '</td><td>' + checkin.plan_name
                              + '</td></tr>';
                    });
                } else {
                    checkin_html += '<tr><td colspan="6">No Result Found</td></tr>';
                }

                $('#checkin_table').html(checkin_html);

                var checkout_html = '';
                if (Object.keys(response.checkout_results).length > 0) {
                    Object.values(response.checkout_results).forEach(checkout => {
                        checkout_html += '<tr><td>' + checkout.guest_name + '</td><td>' + checkout
                            .room_no + '</td><td>' + checkout.checkout_time + '</td></tr>';
                    });
                } else {
                    checkout_html += '<tr><td colspan="3">No Result Found</td></tr>';
                }

                $('#checkout_table').html(checkout_html);
//<td class=text-center>' + occupancy.room_nights + '</td>
                var occupancy_reports_html = '';
                if (Object.keys(response.occupancy_reports).length > 0) {
                    Object.entries(response.occupancy_reports).forEach(([key, occupancy]) => {
						//<td class=text-center>' + occupancy.availability + '</td>
                        occupancy_reports_html += '<tr><td class=text-center>' + key + '</td><td class=text-center>' + occupancy.inventry + '</td><td class=text-center>' + occupancy.occupied + '</td><td class=text-center>' + occupancy.occupied_percentage + '%</td></tr>';
                    });
                } else {
                    occupancy_reports_html += '<tr><td colspan="4">No Result Found</td></tr>';
                }

                $('#data-rows').html(occupancy_reports_html);
            }
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', status, error);
        }
    });
</script>
<?php include_once("../includes/footer.php")?>