<?php include_once("../config/auto_loader.php");
include_once("../includes/header.php");
include_once("../includes/left.php");?>
<style>
    .ranges {
        padding: 9px !important;
    }
    .daterangepicker .ranges li:hover {
        background-color: #08c !important;
    }
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #222;
    }
    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }
    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }
    .spinner {
        width: 55px;
        height: 55px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(247,23,82);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }
    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<style>
    .overlay {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: fixed;
        background: #222;
    }

    .overlay__inner {
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        position: absolute;
    }

    .overlay__content {
        left: 50%;
        position: absolute;
        top: 50%;
        transform: translate(-50%, -50%);
    }

    .spinner {
        width: 55px;
        height: 55px;
        display: inline-block;
        border-width: 2px;
        border-color: rgba(247,23,82);
        border-top-color: #fff;
        animation: spin 1s infinite linear;
        border-radius: 100%;
        border-style: solid;
    }

    @keyframes spin {
        100% {
            transform: rotate(360deg);
        }
    }
</style>
<?php
if ($_REQUEST['occupancy'] == 'Generate') {
    global $objPHPExcel;
    $date_filter = $_REQUEST['datefilter'];
    $date_array = explode(' to ', $date_filter);
    $from = date("Y-m-d" ,strtotime($date_array[0]));
    $to = date("Y-m-d" ,strtotime($date_array[1]));

    $startDate = new DateTime($from);
    $endDate = new DateTime($to);

    // Create an array to hold the dates
    $dateRange = [];
    $results = [];
    $prev_date = '';
    while ($startDate <= $endDate) {
        $date = $startDate->format('Y-m-d');
        $occupancy_query = "SELECT resdetails.id, resdetails.id_mst_hotels, resdetails.id_fo_reservations, reservation.mdoc_no,resdetails.id_mst_room_no_allocation, reservation.reference, resdetails.id_mst_room_types, resdetails.id_fo_rate_plan, resdetails.id_mst_guest, reservation.id_mst_company, reservation.booking_status, reservation.checkin, reservation.checkout, reservation.no_of_days, resdetails.adults_per_room, resdetails.dated FROM fo_reservations_details as resdetails INNER JOIN mst_room_no_allocation as room ON resdetails.id_mst_room_no_allocation = room.id INNER JOIN mst_room_types as room_types ON room_types.id = room.id_mst_room_types INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations = reservation.id WHERE resdetails.`no_showoff` = '0' AND reservation.booking_status in ('1','2') AND resdetails.dated = '".$date."' order by resdetails.id;";
        $occupancy_query = mysqli_query($connNew, $occupancy_query);

        while ($occupancy = mysqli_fetch_object($occupancy_query)) {
            $adults_per_room = 0;
    
            $queryRoomId = mysqli_query($connNew,"SELECT count(id) total_room FROM `fo_reservations_details` WHERE fo_reservations_details.id_fo_reservations ='".$occupancy->id_fo_reservations."' Group BY order_by_room");
            $resRoomId = mysqli_fetch_object($queryRoomId);
            $total_room = mysqli_num_rows($queryRoomId);
    
            $queryRoomIdAdult = mysqli_query($connNew,"SELECT  adults_per_room FROM `fo_reservations_details` WHERE fo_reservations_details.id_fo_reservations ='".$occupancy->id_fo_reservations."' Group BY order_by_room");
            while($resRoomIdAdult = mysqli_fetch_object($queryRoomIdAdult)) {
                $adults_per_room += $resRoomIdAdult->adults_per_room;
            }
    
            if ($prev_date != $occupancy->dated) {
                $record = ['checkin' => dateformat_date($occupancy->dated)];
                $prev_date = $occupancy->dated;
                $results[] = $record;
            }
    
            $record = [
                'hotel_name' => selectColumn('mst_hotels','name'," WHERE `id` = '".$occupancy->id_mst_hotels."'"),
                'mdoc_no' => $occupancy->mdoc_no,
                'reference' => $occupancy->reference,
                'room_no' => selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$occupancy->id_mst_room_no_allocation."'"),
                'room_type' => selectColumn('mst_room_types','name'," WHERE `id` = '".$occupancy->id_mst_room_types."'"),
                'rate_plan' => selectColumn('fo_rate_plan','name'," WHERE `id` = '".$occupancy->id_fo_rate_plan."'"),
                'guest_name' => selectColumn('mst_guest','CONCAT(first_name," ",last_name)'," WHERE `id` = '".$occupancy->id_mst_guest."'"),
                'company_name' => selectColumn('mst_company','name'," WHERE `id` = '".$occupancy->id_mst_company."'"),
                'booking_status' => selectColumn('fo_booking_status','name'," WHERE `id` = '".$occupancy->booking_status."'"),
                'checkin_checkout' => dateformat_date($occupancy->checkin)." - ".dateformat_date($occupancy->checkout),
                'no_of_days' => $occupancy->no_of_days,
                'total_rooms' => $total_room,
                'adults_per_room' => $adults_per_room,
            ];
            $results[] = $record;
        }
        $startDate->modify('+1 day'); // Move to the next day
    }
    
    // Create a new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    // Set properties
    $objPHPExcel->getProperties()->setCreator("Your Name")
        ->setLastModifiedBy("Your Name")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHPExcel.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");

        $styleThinBlackBorderOutline = array(
            'borders' => array(
            'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN,
            'color' => array('argb' => '000'),
            ),
            ),
         );
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray($styleThinBlackBorderOutline);

    $sheet = $objPHPExcel->getActiveSheet();

    $sheet->setCellValue('A1', 'Hotel Name');
    $sheet->setCellValue('B1', 'Reservation Id');
    $sheet->setCellValue('C1', 'Reference');
    $sheet->setCellValue('D1', 'Room No');
    $sheet->setCellValue('E1', 'Room Type');
    $sheet->setCellValue('F1', 'Rate Plan');
    $sheet->setCellValue('G1', 'Guest Name');
    $sheet->setCellValue('H1', 'Source');
    $sheet->setCellValue('I1', 'Booking Status');
    $sheet->setCellValue('J1', 'Checkin - Checkout');
    $sheet->setCellValue('K1', 'No Of Nights');
    $sheet->setCellValue('L1', 'Rooms');
    $sheet->setCellValue('M1', 'Adults');
    $rowNumber = 2;
    foreach ($results as $row) {
        $column = 'A';
        foreach ($row as $cell) {
            $sheet->setCellValue($column . $rowNumber, $cell);
            $column++;
        }
        $rowNumber++;
    }
    
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle('DataSheet');
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    
    ob_end_clean();
    // Redirect output to a client's web browser (Excel2007)
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="Date Wise Occupancy Report.xlsx"');
    header('Cache-Control: max-age=0');
    
    // Save Excel 2007 file
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    exit;
}
?>    
<div class="content-wrapper">
<?php $session=$_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="form-group col-xs-3 col-md-2 col-sm-2 c-box">
                        <a type="button" class="btn c-btn"  href="javascript:void(0)"><i class="fa fa-fw fa-print"></i> Print</a>
                    </div>
                    <div class="form-group col-xs-9 col-sm-3 col-md-10 mb-0">
                        <div class="btn-group" style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
                            <button type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
                                <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
                                <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fas fa-file-image"></i>&nbsp;JPG</a></li>
                            </ul>
                        </div>
                        <div class="btn-group s-btt">
                            <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Share</a>
                            <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown">
                                <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><i class="fas fa-envelope-open-text"></i>&nbsp;Email</a></li>
                                <li><a title="Share on Whatsapp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fab fa-whatsapp"></i>&nbsp;Whatsapp</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xs-12">
                <?php echo breadCrumbs(); ?>
            </div>
        </div>
    </section>
    <section class="content ">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <small class="text-center has-error">
                        <?php if($_SESSION['errorMsg']){?>
                            <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
                        <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
                            <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
                        <?php unset($_SESSION['successMsg']);}?>
                        </small>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <input type="hidden" value="1" name="searchFormSubmit" />
                            <input type="hidden" value="1" name="report_show" id="report_show" />
                            <div class="col-md-2 col-sm-12">
                                <div class="form-group">
                                    <label> Period</label>
                                    <input type="text" class="form-control pull-right dateRangeReport" placeholder="Select From -  To" name="datefilter" id="per_report_date" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer pt-0 pl-0">
                        <input name="Download" type="button" class="btn o-btn" value="Generate" onclick="loadOccupancyReport(1);" />
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="row">
                    <div id="ShowResultContent" style="padding:0px 10px 0px 10px;"></div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    function loadOccupancyReport(ReportShowType) {
        var id_report_type = $("#id_report_type").val();
	    var id_order_by = $("#id_order_by").val();
	    // if (id_report_type == '') {
        //     document.getElementById('id_report_type_error').innerHTML = 'Please Select Report Type';
        //     return false;
		// }
		// document.getElementById('id_report_type_error').style.display = "none";
        $("#showPrintExplode").hide();
        $("#loading").show();
        var id_order_by = $("#id_order_by").val();
        var period = $("#per_report_date").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_data_main_group = $("#id_data_main_group").val();
		var showItemReport = $("#showItemReport").val();
		var id_item = $("#id_item").val();
		reportTypeFile  ='ajaxGetOccupancyReport.php';
		$.ajax({
            url:'ajax/'+reportTypeFile,
            type:'POST',
            data:'period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_data_main_group='+id_data_main_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&id_sub_group='+id_sub_group+'&showItemReport='+showItemReport,
            success:function(data) {
                $("#ShowResultContent").html(data);
                $("#loading").hide();
                $("#showPrintExplode").show();
            }
		});
    }

	downloadExcelPdf = (ReportShowType) => {
    
        var period = $("#per_report_date").val();
        var id_report_type = $("#id_report_type").val();
        var id_main_group = $("#id_main_group").val();
        var id_sub_group = $("#id_sub_group").val();
        var id_item = $("#id_item").val();
        var id_order_by = $("#id_order_by").val();
        var showItemReport = $("#showItemReport").val();

        let url2 = 'ajax/ajaxGetOccupancyReport.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
        window.open(url2);
    }
</script>
<?php include_once("../includes/footer.php")?>