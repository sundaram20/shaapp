<?php 

function FoDateWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport) {
	global $connNew;
	global $objPHPExcel;

	if($date != '') {
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate = date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
	}

    $head_cntr = "C";
	$setcellcount = 1;
	$HotesCount = $setcellcount;
	$Comy =	$setcellcount;
    $styleThinBlackBorderOutline = array(
	    'borders' => array(
	        'allborders' => array(
	            'style' => PHPExcel_Style_Border::BORDER_THIN,
	            'color' => array('argb' => '000'),
	        ),
	    ),
    );

    $objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleThinBlackBorderOutline);
    $con = $setcellcount;
    $objPHPExcel->getActiveSheet()->getStyle('M')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
    $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
	$con = 1;
	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A'.$con, 'Hotel Name')
        ->setCellValue('B'.$con, 'Reservation Id')
        ->setCellValue('C'.$con, 'Reference')
        ->setCellValue('D'.$con, 'Room No')
        ->setCellValue('E'.$con, 'Room Type')
        ->setCellValue('F'.$con, 'Rate Plan')
        ->setCellValue('G'.$con, 'Guest Name')
        ->setCellValue('H'.$con, 'Source')
        ->setCellValue('I'.$con, 'Booking Status')
        ->setCellValue('J'.$con, 'Checkin - Checkout')
        ->setCellValue('K'.$con, 'No Of Nights')
        ->setCellValue('L'.$con, 'Rooms')
        ->setCellValue('M'.$con, 'Adults');

    $con++;
    $SalesRegisterArray = array();
    $datewise_array = array();
    $datawisearrayFinal = [];

	if ($_REQUEST['period'] != '') {
		$splitArray = explode(" to ",$_REQUEST['period']);
		$checkin = $splitArray['0'];
		$checkout = $splitArray['1'];
		$checkinDate = date('Y-m-d',strtotime($checkin));
		$checkoutDate = date('Y-m-d',strtotime($checkout));
		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
			$datewise_array[] = $checkinDate;
			$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
		}
	}
    $quoted_dates = array_map(function($date) {
        return "'" . $date . "'";
    }, $datewise_array);
    $dates_string = implode(', ', $quoted_dates);
    $ics = 1;
    foreach ($datewise_array as $checkinDatearr) {
        $sql = "SELECT resdetails.id, resdetails.id_mst_hotels, resdetails.id_fo_reservations, resdetails.no_showoff, reservation.mdoc_no,resdetails.id_mst_room_no_allocation, reservation.reference, resdetails.id_mst_room_types, resdetails.id_fo_rate_plan, resdetails.id_mst_guest, reservation.id_mst_company, reservation.booking_status, resdetails.checkin_date, resdetails.checkout_date, reservation.no_of_days, resdetails.adults_per_room, resdetails.dated, resdetails.child_below_5_year, resdetails.child_above_5_year, resdetails.room_quantity, resdetails.tariff_price_per_day_per_room, resdetails.tax_per_day_per_room,resdetails.checkin_status as res_details_checkin_status FROM 
		
		fo_reservations_details as resdetails 
		INNER JOIN mst_room_no_allocation as room ON resdetails.id_mst_room_no_allocation = room.id INNER JOIN mst_room_types as room_types ON room_types.id = room.id_mst_room_types INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations = reservation.id WHERE resdetails.`no_showoff` = '0' AND reservation.booking_status in ('1','2') AND resdetails.dated = '".$checkinDatearr."' order by resdetails.id";
        $SQLSalesReportPayment = $sql;
        $querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
        $NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);
        while ($row = mysqli_fetch_object($querySalesReportPayment)) {
            $datawisearrayFinal[$checkinDatearr][$row->id]["booking_no"] = $row->mdoc_no;
            $datawisearrayFinal[$checkinDatearr][$row->id]["id_fo_reservations"] = $row->id_fo_reservations;
            $datawisearrayFinal[$checkinDatearr][$row->id]["id_mst_room_no_allocation"] = $row->id_mst_room_no_allocation;
            $datawisearrayFinal[$checkinDatearr][$row->id]["id_mst_hotels"] = $row->id_mst_hotels;
            $datawisearrayFinal[$checkinDatearr][$row->id]["id_mst_room_types"] = $row->id_mst_room_types;
            $datawisearrayFinal[$checkinDatearr][$row->id]["id_fo_rate_plan"] = $row->id_fo_rate_plan;
            $datawisearrayFinal[$checkinDatearr][$row->id]["reference"] = $row->reference;
            $datawisearrayFinal[$checkinDatearr][$row->id]["company"] = $row->id_mst_company;
            $datawisearrayFinal[$checkinDatearr][$row->id]["customer"] = $row->id_mst_guest;
            $datawisearrayFinal[$checkinDatearr][$row->id]["booking_status"] = $row->booking_status;
            $datawisearrayFinal[$checkinDatearr][$row->id]["checkin"] = $row->checkin_date == '' ? '' : date('Y-m-d',strtotime($row->checkin_date));
            $datawisearrayFinal[$checkinDatearr][$row->id]["checkout"] = $row->checkout_date == '' ? '' : date('Y-m-d',strtotime($row->checkout_date));
            $datawisearrayFinal[$checkinDatearr][$row->id]["no_of_days"] = $row->no_of_days;
            $datawisearrayFinal[$checkinDatearr][$row->id]["adults_per_room"] = $row->adults_per_room;
            $datawisearrayFinal[$checkinDatearr][$row->id]["total_child"] = $row->child_below_5_year + $row->child_above_5_year;
            $datawisearrayFinal[$checkinDatearr][$row->id]["no_showoff"] = $row->no_showoff;
            $datawisearrayFinal[$checkinDatearr][$row->id]["per_day_price"] = $row->tariff_price_per_day_per_room;
            $datawisearrayFinal[$checkinDatearr][$row->id]["per_day_tax"] = $row->tax_per_day_per_room;
            $datawisearrayFinal[$checkinDatearr][$row->id]["per_day_total"] = $row->tariff_price_per_day_per_room + $row->tax_per_day_per_room;
			$datawisearrayFinal[$checkinDatearr][$row->id]["res_details_checkin_status"] = $row->res_details_checkin_status;
        }
	}
    $contentqq = '
        <style>
            body {
                margin:0px; 
                padding:0px;
                font-size:13px !important;
            }
            .table-bordered {
                border: 1px solid #000;
                border-collapse: collapse;
            }
            .table {
                font-size:11px !important; 
                margin-bottom: 20px;	   
                width:100%;
            } 
            table {
                font-size:11px !important; 
                background-color: transparent;
                border-collapse: collapse;
                border-spacing: 0;
            }
            .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th { border-collapse: collapse; border: 1px solid #000; }
            .table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th { color: #000; border-collapse: collapse; border: 1px solid #000; }
            .fitwidth {}
            .page_break { page-break-before: always;float:left;}
            .page_autobreak { page-break-before: always;}
            .generalTermClass table { width:100% !important;}
        </style>
        <style>
            .line:hover {
                background-color:#cf5;
                cursor: pointer;
            }
            .subgrouphideclass:hover {
                background-color:#cf5;
                cursor: pointer;
            }
            .table {
                margin: 0 auto;
                width:100%;
                border-collapse: collapse;
                table-layout:fixed;
            }
            .table td,
            .table th {
                padding:5px 10px;
                border:1px solid #444;
            }';
    $contentsss .= '</style>';

    $content111 ='';
    $content = '<style>
        body {
            margin:0px; 
            padding:0px;
            font-size:13px !important;
        
        }
        .table-bordered {
                border: 1px solid #000;
            border-collapse: collapse;
        }
        .table {
            font-size:11px !important; 
            margin-bottom: 20px;	   
            width:100%;
        } 
        table {
            font-size:11px !important; 
            background-color: transparent;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th { border-collapse: collapse; border: 1px solid #000; }
        .table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th { color: #000; border-collapse: collapse; border: 1px solid #000;}
        .fitwidth {}
        .page_break { page-break-before: always;float:left; }
        .page_autobreak{ page-break-before: always; }
        .generalTermClass table { width:100% !important; }
    </style>';
    $content = '<style>
        body { 
            margin:0px; 
            padding:0px;
            font-size:13px !important;
        
        }
        .table-bordered {
                border: 1px solid #000;
            border-collapse: collapse;
        }
        .table {
            font-size:11px !important; 
            margin-bottom: 20px;	   
            width:100%;
        } 
        table {
            font-size:11px !important; 
            background-color: transparent;
            border-collapse: collapse;
            border-spacing: 0;
        }
        .table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th { border-collapse: collapse; border: 1px solid #000; }
        .table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th { color: #000; border-collapse: collapse; border: 1px solid #000; }
        .fitwidth {}
        .page_break { page-break-before: always;float:left; }
        .page_autobreak{ page-break-before: always; }
        .generalTermClass table { width:100% !important; }
    </style>';
    $foldername = "/app";
    $pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;
    $BackgroundColorMain = 'background-color:#edf2f4;';
    $BackgroundColor = 'background-color:#fff;';
?>
	<table class="table">
        <tbody>
            <?php
                foreach ($datawisearrayFinal as $dateCheckin => $dateData) {?>
                    <tr><th colspan="11" style="background-color:#01B9F5; color: white;">Date: <?php echo dateformat_date($dateCheckin)?></th></tr>
                    <tr>
                        <td colspan="11">
                            <table class="table" style="width:100%;">
                                <tr style="margin-bottom:0px;border:1px;width:100%;text-align:center;color:#000;background-color:#c2d69a;">
                                    <th>Reservation Id</th>
                                    <th>Room No</th>
                                    <th>Room Type</th>
                                    <th>Rate Plan</th>
                                    <th>Guest Name</th>
                                    <th>Source</th>
                                    <th>Status</th>
                                    <th>Adults</th>
                                    <th>Child</th>
                                    <th>Tariff</th>
                                    <th>Tax</th>
                                    <th>total</th>
                                </tr>
                                <?php
                                    $total_rooms = 0;
                                    $adults = 0;
                                    $child = 0;
                                    $tariffs = 0;
                                    $taxes = 0;
                                    $total = 0;
					                foreach ($dateData as $hotelcheckarr => $order_data) {
                                ?>
                                        <tr>
                                            <td><?php echo $order_data['booking_no']; ?></td>
                                            <td><?php echo selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$order_data['id_mst_room_no_allocation']."'"); ?></td>
                                            <td><?php echo selectColumn('mst_room_types','name'," WHERE `id` = '".$order_data['id_mst_room_types']."'"); ?></td>
                                            <td><?php echo selectColumn('fo_rate_plan','name'," WHERE `id` = '".$order_data['id_fo_rate_plan']."'"); ?></td>
                                            <td style="width:200px"><?php echo selectColumn('mst_guest','CONCAT(first_name," ",last_name)'," WHERE `id` = '".$order_data['customer']."'"); ?></td>
                                            <td style="width:200px"><?php echo selectColumn('mst_company','name'," WHERE `id` = '".$order_data['company']."'"); ?></td>
                                            <td>
                                                <?php
                                                    $status = "Occupied";
                                                    if ($dateCheckin == $order_data['checkin']) {
                                                        $status = "Checkin/Occupied";
                                                    }
                                                    if ($order_data['res_details_checkin_status']=='1' && $dateCheckin != $order_data['checkin']) {
                                                        $status =  "Occupied";
                                                    }
                                                    //if ($dateCheckin == $order_data['checkin'] && $dateCheckin == $order_data['checkout']) {
                                                    //    $status =  "Checkin/Checkout";
                                                    //}
                                                    echo $status;

                                                   // if ($status == "Checkin/Occupied" || $status == "Occupied") {
                                                        $total_rooms += 1;
                                                        $adults += $order_data['adults_per_room'];
                                                        $child += $order_data['total_child'];
                                                        $tariffs += $order_data['per_day_price'];
                                                        $taxes += $order_data['per_day_tax'];
                                                        $total += $order_data['per_day_total'];
                                                   // }
                                                ?>
                                            </td>
                                            <?php
                                           // if ($status == "Checkin/Occupied" || $status == "Occupied") {
												 ?>
                                            <td><?php echo $order_data['adults_per_room']; ?></td>
                                            <td><?php echo $order_data['total_child']; ?></td>
                                            <td><?php echo $order_data['per_day_price']; ?></td>
                                            <td><?php echo $order_data['per_day_tax']; ?></td>
                                            <td><?php echo $order_data['per_day_total']; ?></td>
                                            <?php /* } else { ?>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                                <td>0</td>
                                            <?php }*/ ?>
                                        </tr>
                                <?php
					                }
				                ?>
                                <tr>
                                    <td colspan="5"></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b>Day Total</b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b>No Of Occupied Rooms: <?php echo $total_rooms; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $adults; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $child; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $tariffs; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $taxes; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $total; ?></b></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <?php
				}
				?>
        </tbody>
    </table>
<?php
    $date = date('d-m-Y');
    $Filename='FoDateWiseOccupancyReport_'.$date;
    if ($report_show == 3) {
        $dompdf = new DOMPDF();
        $dompdf->set_paper('landscape', 'landscape');
        $dompdf->load_html($content);
        $dompdf->render();
        $font = Font_Metrics::get_font("helvetica", "bold");
        $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
        $dompdf->output();
        $dompdf->stream($Filename.'.pdf', array("Attachment" => true));
    } else if($report_show == 2) {
        $test = $content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;
        die;
    } else {
        echo $content;
        die;
    }
}
?>