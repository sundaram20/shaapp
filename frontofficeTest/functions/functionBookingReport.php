<?php

function cellColor($cells,$color){
    	global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}

function DateWiseReports(
    $reservation_checkindate,       // $Date
    $modifiedDate,
    $companyId,         // $id_report_type
    $checkinRadio,      // $report_show
    $bookingStatus,     // $showItemReport
    $otherRefNo,        // $kot_nc
    $reservationNo,
    $appConnect,        // $appConnect
    $connNew,           // $connNew
    $shop,              // $shop
    $cronSet,           // $cronSet
    $pdfNameReport3,    // $pdfNameReport3
    $objPHPExcel        // $objPHPExcel
){
	
	 // 1. Convert date ranges to MySQL format
    function convertDateRange($dateRange) {
        $dates = explode(' to ', $dateRange);
        if (count($dates) === 2) {
            $start = DateTime::createFromFormat('d-m-Y', trim($dates[0]))->format('Y-m-d');
            $end   = DateTime::createFromFormat('d-m-Y', trim($dates[1]))->format('Y-m-d');
            return [$start, $end];
        }
        $single = DateTime::createFromFormat('d-m-Y', trim($dateRange))->format('Y-m-d');
        return [$single, $single];
    }

    list($startDate, $endDate) = convertDateRange($reservation_checkindate);
    list($startModified, $endModified) = convertDateRange($modifiedDate);

    // 2. Build dynamic SQL
    $sql = "SELECT 
                r.mdoc_no,
                r.id_mst_company,
                r.checkin,
                r.checkout,
                r.date_created,
                r.booking_status,
				r.reference,
				r.id_mst_guest,
				r.res_payment_status,
                s.name AS booking_status_name,
                c.name AS company_name,
                c.address AS company_address,
                d.id_fo_reservations,
                d.id_mst_room_types,
                d.id_fo_rate_plan,
                d.order_by_room,
				d.adults_per_room,
				d.child_below_5_year,
				d.child_above_5_year,
                SUM(d.tax_per_day_per_room) AS total_tax_per_day_per_room,
                SUM(d.tariff_price_per_day_per_room) AS total_tariff_price_per_day_per_room,
				 COUNT(DISTINCT d.order_by_room) + 1 AS room_count,
				  COUNT(d.id) as no_of_days 
            FROM fo_reservations AS r
            LEFT JOIN fo_booking_status AS s ON r.booking_status = s.id
            LEFT JOIN mst_company AS c ON r.id_mst_company = c.id
            LEFT JOIN fo_reservations_details AS d ON r.id = d.id_fo_reservations
            WHERE 1=1";

    $params = [];
    $types = '';

    // 3. Dynamic filters
    if (!empty($companyId)) {
        $sql .= " AND r.id_mst_company = ?";
        $params[] = $companyId;
        $types .= 'i';
    }

    if (!empty($bookingStatus) && is_array($bookingStatus)) {
        $placeholders = implode(',', array_fill(0, count($bookingStatus), '?'));
        $sql .= " AND r.booking_status IN ($placeholders)";
        foreach ($bookingStatus as $status) {
            $params[] = $status;
            $types .= 'i';
        }
    }

    if (!empty($reservationNo)) {
        $sql .= " AND r.mdoc_no LIKE ?";
        $params[] = '%' . $reservationNo . '%';
        $types .= 's';
    }

    if (!empty($otherRefNo)) {
        $sql .= " AND r.mdoc_no LIKE ?";
        $params[] = '%' . $otherRefNo . '%';
        $types .= 's';
    }

   if ($startDate && $endDate) {
    if ($checkinRadio == '1') { echo $startDate.$endDate;
        // Filter by check-in date
        $sql .= " AND DATE(r.checkin) BETWEEN ? AND ?";
        $params[] = $startDate;
        $params[] = $endDate;
        $types .= 'ss';
    } elseif ($checkinRadio == '2') {
        // Filter by modification date (date_created)
        $sql .= " AND DATE(r.date_created) BETWEEN ? AND ?";
        $params[] = $startModified; // you can use same start/end from $bookingDate or a separate $modifiedDate if you have it
        $params[] = $endModified;
        $types .= 'ss';
    }
}

    // 4. Group by for reservation details aggregation
    $sql .= " GROUP BY 
                r.mdoc_no,
                d.id_mst_room_types,
                d.id_fo_rate_plan
                
              ORDER BY r.id ASC";
//echo $sql;die;
    // 5. Prepare and execute
    $stmt = $connNew->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $connNew->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // 6. Fetch records
    /*($records = [];
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
        //print_r($row); // optional: print for debugging
    }*/
	$records = [];
while ($row = $result->fetch_assoc()) {
    $mdoc_no = $row['mdoc_no'];

    // Initialize if first time
    if (!isset($records[$mdoc_no])) {
        $records[$mdoc_no] = [
            'row_data' => $row,
            'room_types' => [],
            'total_rooms' => 0,
            'total_count_per_room' => 0,
            'total_tariff' => 0,
            'total_tax' => 0
        ];
    }

    // Fetch room details for this reservation
  /*  $sqlOrderDetail = mysqli_query($connNew, "
        SELECT r.name AS room_type_name, COUNT(*) AS num_rooms, COUNT(*) AS count_per_room
        FROM `".FO_RESERVATIONS_DETAILS."` d
        JOIN ".TBL_ROOM_TYPE." r ON r.id = d.id_mst_room_types
        WHERE `id_mst_room_types` = '".$row['id_mst_room_types']."'
          AND `id_fo_rate_plan` = '".$row['id_fo_rate_plan']."'
          AND `id_fo_reservations` = '".$row['id_fo_reservations']."'
        GROUP BY d.id_mst_room_types
    ");

    while ($room = mysqli_fetch_assoc($sqlOrderDetail)) {
        // Track room types as a comma-separated string
        $records[$mdoc_no]['room_types'][] = $room['room_type_name'];
        $records[$mdoc_no]['total_rooms'] += $room['num_rooms'];
        $records[$mdoc_no]['total_count_per_room'] += $room['count_per_room'];
    }*/
	$sqlOrderDetail = mysqli_query($connNew, "
    SELECT cnt, COUNT(*) AS num_rooms
    FROM (
        SELECT order_by_room, COUNT(*) AS cnt
        FROM `".FO_RESERVATIONS_DETAILS."`
        WHERE `id_mst_room_types` = '".$row['id_mst_room_types']."'
          AND `id_fo_rate_plan` = '".$row['id_fo_rate_plan']."'
          AND `id_fo_reservations` = '".$row['id_fo_reservations']."'
        GROUP BY order_by_room
    ) AS sub
    GROUP BY cnt
    ORDER BY cnt
");

if(mysqli_num_rows($sqlOrderDetail) > 0) {
    $rowOrderDetail = mysqli_fetch_object($sqlOrderDetail);
    // Access results directly
    $countPerRoom = $rowOrderDetail->cnt;      // number of reservations per room
    $numberOfRooms = $rowOrderDetail->num_rooms;  // number of rooms with that count
	 	//$records[$mdoc_no]['room_types'][] = $room['room_type_name'];
        $records[$mdoc_no]['total_rooms'] += $numberOfRooms;
        $records[$mdoc_no]['total_count_per_room'] = $countPerRoom;
$records[$mdoc_no]['room_types'][$row['id_mst_room_types']] = selectColumn(TBL_ROOM_TYPE, 'name', "WHERE id='".addslashes($row['id_mst_room_types'])."'").' - '.selectColumn('fo_rate_plan', 'name', "WHERE id='".addslashes($row['id_fo_rate_plan'])."'").'('.$numberOfRooms.')';
    //echo "Count per room: $countPerRoom | Number of rooms: $numberOfRooms";
}
 //$records[$mdoc_no]['room_types'][] = selectColumn(TBL_ROOM_TYPE, 'name', "WHERE id='".addslashes($row['id_mst_room_types'])."'").' - '.selectColumn('fo_rate_plan', 'name', "WHERE id='".addslashes($row['id_fo_rate_plan'])."'");
      
		
    // Sum tariff & tax
    $records[$mdoc_no]['total_tariff'] += $row['total_tariff_price_per_day_per_room'];
    $records[$mdoc_no]['total_tax'] += $row['total_tax_per_day_per_room'];
}

// Now write to Excel


    // 7. Return for further processing (Excel/PDF)
    //return $records;
	
	
	//echo '<pre>';print_r($records); 
	//die;
	

	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");


 

$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
//$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A7', "Date Wise Report As On ".date('d-m-Y',strtotime($startDate)));
	//$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:K7');
	

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 12,
        'name'  => 'Verdana'
    ));

 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
	
	$objPHPExcel->setActiveSheetIndex(0);
    $sheet = $objPHPExcel->getActiveSheet();
    $sheet->setTitle('Booking Report');

    // Headers
    $headers = [
        'A'=>'Reservation No',
		'B'=>'Other Ref Id',
		
		'C'=>'Booking Date',		
		'D'=>'Check-in',
		'E'=>'Check-out',
		'F'=>'Source',
        'G'=>'Booking Status',
		'H'=>'Room Type',
			
		'I'=>'No Of Rooms',	
		'J'=>'No Of Days',
		'K'=>'Room Nights',
        'L'=>'Room Tariff','M'=>'Tax','N'=>'Room Tariff inclusive Taxes',
		'O'=>'Guest Name','P'=>'Guest Email', 'Q'=>'Guest Mobile',
		'R'=>'Payment Mode', 'S'=>'Booking Time'
    ];
    $colRow = 1;


// Apply top vertical alignment and wrap text for the entire data range

    foreach($headers as $col=>$title){
        $sheet->setCellValue($col.$colRow,$title);
        $sheet->getStyle($col.$colRow)->getFont()->setBold(true);
        cellColor($col.$colRow,'D9D9D9');
    }

    // Data
   $rowNum = 2; // Assuming row 1 is header
foreach ($records as $mdoc_no => $rec) {
    $row = $rec['row_data'];
	$res_payment_status = $row['res_payment_status'];
    $id_mst_guest = $row['id_mst_guest'];
    $id_mst_attributes_title = selectColumn(TBL_GUEST, 'id_mst_attributes_title', "WHERE `id` = '".$id_mst_guest."'");              
    $Title = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id_shop='".$shop."' AND status='1' AND `table_name`='title' AND id='".$id_mst_attributes_title."'");             
    $Firstname = selectColumn(TBL_GUEST, 'first_name', "WHERE `id` = '".$id_mst_guest."'");
    $Lastname = selectColumn(TBL_GUEST, 'last_name', "WHERE `id` = '".$id_mst_guest."'");
	 $Email = selectColumn(TBL_GUEST, 'email', "WHERE `id` = '".$id_mst_guest."'");
	 $Mobile = selectColumn(TBL_GUEST, 'primary_mobile', "WHERE `id` = '".$id_mst_guest."'");
	
	$payment_status = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id_shop='".$shop."' AND status='1' AND `table_name`='payment_status' AND id='".$res_payment_status."'");
	
    $guestName = $Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));

    $sheet->setCellValue('A'.$rowNum, trim($row['mdoc_no']));
    $sheet->setCellValue('B'.$rowNum, trim($row['reference']));
    $sheet->setCellValue('C'.$rowNum, date('d-M-Y', strtotime($row['date_created'])));
    $sheet->setCellValue('D'.$rowNum, date('d-M-Y', strtotime($row['checkin'])));
    $sheet->setCellValue('E'.$rowNum, date('d-M-Y', strtotime($row['checkout'])));
    $sheet->setCellValue('F'.$rowNum, $row['company_name']);
    $sheet->setCellValue('G'.$rowNum, $row['booking_status_name']);
	

// Correct way to add line breaks
/*$roomTypes = implode(PHP_EOL, $rec['room_types']); // PHP_EOL is "\n"
$sheet->setCellValue('H'.$rowNum, $roomTypes);
$sheet->getStyle('H'.$rowNum)->getAlignment()->setWrapText(true);
// Make sure wrap text is enabled
$sheet->getStyle('H'.$rowNum)->getAlignment()->applyFromArray(
    array(
        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP, // align top
        'wrap'       => true                                    // enable wrap text
    )
);*/$roomTypes = implode(PHP_EOL, $rec['room_types']);
//$ratePlans = implode("\n", $roomTypes);
$sheet->setCellValue('H'.$rowNum, trim($roomTypes));
$sheet->getStyle('H'.$rowNum)->getAlignment()->applyFromArray(
    array(
        'wrap' => true,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
    )
);
//$sheet->getRowDimension($rowNum)->setRowHeight(-1);
   // $sheet->setCellValue('I'.$rowNum, 'Multiple'); // Or keep rate plan separate if needed
    $sheet->setCellValue('I'.$rowNum, $rec['total_rooms']);
    $sheet->setCellValue('J'.$rowNum, $rec['total_count_per_room']);
    $sheet->setCellValue('K'.$rowNum, $rec['total_rooms'] * $rec['total_count_per_room']);
    $sheet->setCellValue('L'.$rowNum, $rec['total_tariff']);
    $sheet->setCellValue('M'.$rowNum, $rec['total_tax']);
    $sheet->setCellValue('N'.$rowNum, $rec['total_tariff'] + $rec['total_tax']);
    $sheet->setCellValue('O'.$rowNum, $guestName);
	$sheet->setCellValue('P'.$rowNum, $Email);
	$sheet->setCellValue('Q'.$rowNum, $Mobile);
	$sheet->setCellValue('R'.$rowNum, $payment_status);
  $sheet->setCellValue('S'.$rowNum, date('d-M-Y H:i:s', strtotime($row['date_created'])));
	
	$sheet->getStyle('A'.$rowNum.':T'.$rowNum)->getAlignment()->applyFromArray(
    array(
        'wrap' => true,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP,
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_LEFT
    )
);
    $rowNum++;
}//'vertical'   => PHPExcel_Style_Alignment::VERTICAL_TOP,     
$sheet->getColumnDimension('A')->setWidth(12);
	$sheet->getColumnDimension('B')->setWidth(15);
	$sheet->getColumnDimension('C')->setWidth(12);
	$sheet->getColumnDimension('D')->setWidth(12);
	$sheet->getColumnDimension('E')->setWidth(12);
	$sheet->getColumnDimension('F')->setWidth(30);
	$sheet->getColumnDimension('H')->setWidth(45);
	$sheet->getColumnDimension('O')->setWidth(45);
	$sheet->getColumnDimension('S')->setWidth(25);
    // ===== Grand Total Row =====
    $con = $rowNum;
    /*$sheet->setCellValue('A'.$con,'Grand Total')
          ->setCellValue('B'.$con,'')
          ->setCellValue('C'.$con,'')
          ->setCellValue('D'.$con,$Net_Today)
          ->setCellValue('E'.$con,'')
          ->setCellValue('F'.$con,'')
          ->setCellValue('G'.$con,$Net_MTD)
          ->setCellValue('H'.$con,'')
          ->setCellValue('I'.$con,'')
          ->setCellValue('J'.$con,$Net_YTD);*/

    //cellColor('A'.$con.':D'.$con,'CDECFF');
   // cellColor('E'.$con.':G'.$con,'BCB7B7');
 //   cellColor('H'.$con.':J'.$con,'CDECFF');
    $sheet->getStyle('A'.$con.':K'.$con)->getFont()->setBold(true);
 
	
	//$sheet->getRowDimension('A'.$con.':K'.$con)->setRowHeight(-1);
	//die;
	// Rename worksheet
		// $objPHPExcel->getSecurity()->setLockWindows(true);
         //$objPHPExcel->getSecurity()->setLockStructure(true);
        // $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");
         //$objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');
         //$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
         // This should be enabled in order to enable any of the following!
        // $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
         //$objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);	
		 $objPHPExcel->getActiveSheet()->setTitle('Night Audit');	
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		 $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
		 
		 $objPHPExcel->getActiveSheet()
			->getPageMargins()->setTop(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setRight(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setLeft(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setBottom(0.25);

//Print
	$objPHPExcel->setActiveSheetIndex(0);
	//ob_end_clean();



if($cronSet=='1'){
	$Filename=	$pdfNameReport3;//'nightAuditReports'.date('d-M-Y');
//$objPHPExcel->getActiveSheet(0)->setCellValue('A1',"Flash Summary Report As On  ".date('d-m-Y',strtotime($ReportAsOnDate)));
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');	
$objWriter->save('/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/'.$Filename.'.xls');

}else{
ob_end_clean();
	$filename=	'BookingReports'.date('d-M-Y').'.xls';
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	//exit;
}
	}

	?>