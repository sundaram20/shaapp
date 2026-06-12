<?php
function alterationHistoryReportReport($Date,$id_outlet,$id_shift,$objPHPExcel){

    global $connNew;

    $form_name = 'POS';
    $where = "1=1";

    // ================= DATE FILTER =================
    if($Date != ''){
        $DateExplode = explode(' to ', $_REQUEST['datefilterreport']);
        $startDate = date('Y-m-d', strtotime($DateExplode[0]));
        $endDate   = date('Y-m-d', strtotime($DateExplode[1]));
        $endDate   = date("Y-m-d", strtotime("+1 day", strtotime($endDate)));

        $where .= " AND a.date_created BETWEEN '$startDate' AND '$endDate'";
    }

    // ================= FORM FILTER =================
    $where .= " AND a.form_code='".mysqli_real_escape_string($connNew,$form_name)."'";

    // ================= EXCEL SETUP =================
    $objPHPExcel->getProperties()
        ->setCreator("System")
        ->setTitle("POS Audit Trail Report")
        ->setSubject("POS Audit")
        ->setDescription("POS Audit Trail Export");

    $sheet = $objPHPExcel->setActiveSheetIndex(0);

    $rowCount = 1;

    // ================= TITLE =================
    $sheet->setCellValue('A1', 'POS Audit Trail Report');
    $sheet->mergeCells('A1:F1');
    $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
    $sheet->getStyle('A1')->getAlignment()
          ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    $rowCount = 3;

    // ================= HEADERS =================
    $sheet->setCellValue('A'.$rowCount, 'Document No')      
          ->setCellValue('B'.$rowCount, 'Bill Date')
          ->setCellValue('C'.$rowCount, 'User')
          ->setCellValue('D'.$rowCount, 'Status')
          ->setCellValue('E'.$rowCount, 'Changes');

    $sheet->getStyle('A'.$rowCount.':E'.$rowCount)->getFont()->setBold(true);
    $rowCount++;

    // ================= FETCH DATA =================
    $SQL = "SELECT 
                a.*,
                u.name AS user_name
            FROM audit_trail a
            LEFT JOIN ".TBL_USERS." u 
                ON u.id = a.id_mst_user_created_by
            WHERE $where
            ORDER BY a.voucher_id ASC, a.id ASC";

    $query = mysqli_query($connNew, $SQL);

    // Track voucher occurrence
    $voucherTracker = [];

    while($row = mysqli_fetch_object($query)){

        $voucherId = $row->voucher_id;
		$voucherId	= selectColumn('pos_purch','mdoc_no'," WHERE `id` = '".$row->voucher_id."'");
        $date_created = date('d-m-Y H:i:s', strtotime($row->date_created));
        $userName = $row->user_name;

        // ================= BILL MODIFIED LOGIC =================
        if(!isset($voucherTracker[$voucherId])){
            $statusText = 'New Entry';
            $voucherTracker[$voucherId] = 1;
        } else {
            $statusText = 'Bill Modified';
        }

        // ================= FORMAT CHANGES =================
        $formattedChanges = '';

        if($row->changes != '' && strtolower($row->changes) != 'no change'){

            $changeArray = explode(',', $row->changes);

            foreach($changeArray as $change){
                $change = trim(str_replace('-', ' ', $change));
                if($change != ''){
                    $formattedChanges .= $change . "\n";
                }
            }
        }

        // ================= WRITE TO EXCEL =================
        $sheet->setCellValue('A'.$rowCount, $voucherId)              
              ->setCellValue('B'.$rowCount, $date_created)
              ->setCellValue('C'.$rowCount, $userName)
              ->setCellValue('D'.$rowCount, $statusText)
              ->setCellValue('E'.$rowCount, $formattedChanges);

       // $sheet->getStyle('E'.$rowCount)->getAlignment()->setWrapText(true);

        $rowCount++;
    }

    // ================= AUTO COLUMN SIZE =================
    foreach(range('A','E') as $col){
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // ================= PAGE SETUP =================
    $sheet->setTitle('POS Audit Trail');
    $sheet->getPageSetup()
          ->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

    // ================= DOWNLOAD =================
    ob_end_clean();

    $filename = "POS_Audit_Trail_".date('d-M-Y').".xls";

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$filename.'"');
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}
?>