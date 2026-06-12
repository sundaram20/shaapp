<?php
function columnifySet($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
	{
	    $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
	    $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

	    $leftLines = explode("\n", $leftWrapped);
	    $rightLines = explode("\n", $rightWrapped);
	    $allLines = array();
	    for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
	        $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
	        $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
	        $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
	    }
		//return implode($allLines) . "\n";
	    return implode($allLines, "\n");
}


		
	function cellColorExcel($cells,$color,$objPHPExcel){
    	//global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}	

function customRound($value) {
    $decimal = $value - floor($value);
    
    if ($decimal > 0.6) {
        return round($value); // normal rounding
    } else {
        return floor($value) + 0.5; // round down to .5
    }
}
function frontofficeSalesRegisterReceipts($Date,$id_outlet,$id_shift,$objPHPExcel,$connNew,$id_shop,$cronSet,$pdfName){
	

	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConn .= " AND p.`doc_date` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
		//$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		$SqlConn2 .= " AND p.`doc_date` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		//$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$id_shift = "'" . implode("','", explode(",", $id_shift)) . "'";
		$sqlPaymentMode .= " AND pp.`payment_mode` IN (".$id_shift.")";
		$sqlPaymentModeRe .= " AND `payment_mode` IN (".$id_shift.")";
	}








$head_cntr = "C";
	$setcellcount	=1;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($styleThinBlackBorderOutline);

$con=$setcellcount;



$objPHPExcel->getActiveSheet()
->getStyle('M')
->getNumberFormat()
->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2 );

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);


			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$con=1;	
			//Voucher Type	Voucher No.	Voucher Dt.	Account Name	Narration	Dr Amount	Cr Amount		vchref	vchDate	Assessable Value	Surcharge


				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Voucher Type')
			->setCellValue('B'.$con, 'Recepit No')
			->setCellValue('C'.$con, 'Recipt Date.')
			->setCellValue('D'.$con, 'Rcpt Id.')					
			->setCellValue('E'.$con, 'Account Name')
			->setCellValue('F'.$con, 'Narration')
			->setCellValue('G'.$con, 'Dr Amount')				
			->setCellValue('H'.$con, 'Cr Amount')
	->setCellValue('I'.$con, 'Fo Bill No')
			->setCellValue('J'.$con, 'Fo Bill date');
			/*
			->setCellValue('H'.$con, 'Type')
			->setCellValue('I'.$con, 'vchref')
			->setCellValue('J'.$con, 'vchDate')
			->setCellValue('K'.$con, 'Assessable Value')
			->setCellValue('L'.$con, 'Surcharge')
			->setCellValue('M'.$con, 'GSTIN')
			->setCellValue('N'.$con, 'State')
			->setCellValue('O'.$con, 'Place of Supply')	
			->setCellValue('P'.$con, 'GST Type')
			->setCellValue('Q'.$con, 'Outlet');*/
					
		
$con++;
$SalesRegisterArray=array();


  $SQLSalesReportPaymentx ="
SELECT 
    p.id AS bill_id,
    p.folio_no,
    p.mdoc_no,
    p.doc_date as date_created,
    p.id_reservations,
    p.id_fo_folio_to,

    r.booking_no,
    r.doc_date,
    r.checkin,
    r.checkout,
pp.id as id_fo_receipt,
pp.doc_date as date_fo_receipt,
    SUM(IFNULL(pp.amount, 0)) AS total_dramount

FROM 
    fo_bill p
LEFT JOIN 
    fo_reservations r ON r.id = p.id_reservations
LEFT JOIN 
    fo_receipt pp ON pp.id_fo_bill = p.id AND IFNULL(pp.amount, 0) > 0

WHERE 
    1=1
    $SqlConn $sqlPaymentMode

GROUP BY 
    p.id

ORDER BY 
    p.date_created asc
";




// Execute query
$querySalesReportPayment = mysqli_query($connNew, $SQLSalesReportPaymentx);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);

$SalesRegisterArray = array();
$SalesRegisterArrayDr=array();
$ics = 1;
while ($record = mysqli_fetch_object($querySalesReportPayment)) {
    
    // Subquery: Get individual payment rows for this bill
   echo $sqlReceipt = "
        SELECT 
            CASE 
                WHEN IFNULL(amount, 0) > 0 THEN payment_mode
                ELSE '0'
            END AS payment_type,

            CASE 
                WHEN payment_mode = 'CASH' AND IFNULL(amount, 0) > 0 THEN 'Cash Sales'
                WHEN payment_mode = 'COMPANY' AND IFNULL(amount, 0) > 0 THEN id_company
                WHEN payment_mode = 'CARD' AND IFNULL(amount, 0) > 0 THEN id_charges_master
                WHEN payment_mode = 'CHEQUE' AND IFNULL(amount, 0) > 0 THEN 'CHEQUE'
                WHEN payment_mode = 'UPI' AND IFNULL(amount, 0) > 0 THEN 'UPI'
                WHEN payment_mode = 'ONLINETRANSFER' AND IFNULL(amount, 0) > 0 THEN id_charges_master
                ELSE '0'
            END AS payment_remarks,

            IFNULL(amount, 0) AS dramount,
            id_company,id as id_fo_receipt,
doc_date as date_fo_receipt
        FROM fo_receipt 
        WHERE id_fo_bill = '".addslashes($record->bill_id)."'  and payment_mode != 'BIllONHOLD' 
        AND IFNULL(amount, 0) > 0 $sqlPaymentModeRe ORDER by doc_date asc
    ";//WHEN payment_mode = 'BIllONHOLD' AND IFNULL(amount, 0) > 0 THEN 'Bill On Hold'

    $queryReceipt = mysqli_query($connNew, $sqlReceipt);
    while ($rowPay = mysqli_fetch_object($queryReceipt)) { 
        $ptype = $rowPay->payment_type;
        $payment_remarks = $rowPay->payment_remarks;
        $dramount = $rowPay->dramount;
        $gst = '';
        $state = '';
        $city = '';
        $gstType = '';
if($payment_remarks != 'Bill On Hold'){
        // Handle company GST info
        if (!empty($rowPay->id_company)) {
            $payment_remarks = ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'")));//'Cash----';
			//ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'")));
            $gst = selectColumn(MST_COMPANY, 'fax', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $id_mst_state = selectColumn(MST_COMPANY, 'id_mst_state', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $state_name = ucwords(strtolower(selectColumn(TBL_STATE, 'name', "WHERE id_state='{$id_mst_state}'")));
			$state = $state_name!=''?$state_name:'Rajasthan';
            $city = 'Rajasthan'; // optional dynamic city
            $gstType = ($gst != '') ? 'Regular' : 'Unregistered/Consumer';
        }

        if ($payment_remarks == 'Cash Sales' && $gst == '' && $rowPay->id_company == '0') {
			$payment_remarks = 'Cash';
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
 if ($payment_remarks == 'UPI' ) {$payment_remarks = 'UPI';
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
        if (in_array($ptype, ['CARD', 'ONLINETRANSFER'])) {
            $payment_remarks = 'CREDIT CARD COLLECTION'; //selectColumn(TBL_CHARGES, 'name', "WHERE id='{$payment_remarks}' AND id_shop='{$id_shop}'");
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }

        // RoomTo logic (optional if needed per payment type)
        if ($ptype == 'ROOMTO') {
            $id_reservations = selectColumn(FO_BILL, 'id_reservations', "WHERE id_mst_shops='{$id_shop}' AND id='{$record->bill_id}'");

            $sqlOrderDetail = mysqli_query($connNew, "
                SELECT * FROM `".FO_RESERVATIONS_DETAILS."`
                WHERE id_fo_reservations='".addslashes($id_reservations)."'
                GROUP BY id_mst_room_no_allocation
            ");

            while ($row = mysqli_fetch_object($sqlOrderDetail)) {
                $guestFirstName = selectColumn(TBL_GUEST, 'first_name', "WHERE id='{$row->id_mst_guest}'");
                $guestLastName = selectColumn(TBL_GUEST, 'last_name', "WHERE id='{$row->id_mst_guest}'");
                $titleId = selectColumn(TBL_GUEST, 'id_mst_attributes_title', "WHERE id='{$row->id_mst_guest}'");
                $guestTitle = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id='{$titleId}' AND table_name='title'");
                $payment_remarks = trim("{$guestTitle}{$guestFirstName} {$guestLastName}");
            }
        }

        // Assign values
        $PaymentKey = $ptype . $ics++;
        $voucher_type = selectColumn(TBL_DOC_TYPE_CONFIG, 'doc_name', "WHERE id='{$record->id_doc_type_configuration}' AND id_shop='{$id_shop}'");
        $doc_type = selectColumn(TBL_PURCH, 'doc_type', "WHERE id='{$record->id}' AND id_shop='{$id_shop}'");

        $folioTo = $record->id_fo_folio_to;
        $mdoc = $record->mdoc_no;
	    $resc 	=	explode('/',$mdoc);
	
	$ptype    = $rowPay->payment_type;
    $dramount = (float)$rowPay->dramount;

    /* -------- Payment Remarks -------- */
   

    $SalesRegisterArray['Sales Register'][$folioTo][$mdoc][$ptype][$PaymentKey] = [
        'voucher_type' => selectColumn(
            TBL_DOC_TYPE_CONFIG,
            'doc_name',
            "WHERE id='{$record->id_doc_type_configuration}' AND id_shop='{$id_shop}'"
        ),
        'date_created' => $record->date_created,
        'mdoc_no'      => $mdoc,
        'doc_type'     => selectColumn(
            TBL_PURCH,
            'doc_type',
            "WHERE id='{$record->id}' AND id_shop='{$id_shop}'"
        ),
        'Account_Name' => $payment_remarks,
        'dramount'     => $dramount,
        'ptype'        => 'Cr',
        'vchref'       => $mdoc,
        'vchDate'      => $rowPay->doc_date,
        'outlet_name'  => 'Front Office',
        'id_fo_receipt'=> $rowPay->id_fo_receipt
    ];
	



$id_bill_to_company = selectColumn('fo_folio', 'id_bill_to_company', "WHERE id_mst_shops='{$id_shop}' AND id_fo_bill='{$record->bill_id}'");
if ($id_bill_to_company>0) {
            $Account_Name =  ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$id_bill_to_company}' AND id_shop='{$id_shop}'")));
			
}else{
	$Account_Name =  'Direct Guest A/c';

	}

 $sqlCR = "
    SELECT IFNULL(SUM(amount),0) AS total_amount
    FROM fo_receipt
    WHERE id_fo_bill = '".addslashes($record->bill_id)."' and doc_date='".$rowPay->date_fo_receipt."'  and payment_mode != 'BIllONHOLD' 
    AND IFNULL(amount,0) > 0 $sqlPaymentModeRe
";
$resCR = mysqli_query($connNew, $sqlCR);
$rowCR = mysqli_fetch_object($resCR);

$crTotal = (float)$rowCR->total_amount;
	$ptypeKey=$rowPay->date_fo_receipt;
	
	$SalesRegisterArrayDr['Sales Register'][$folioTo][$mdoc]['DR-'.$rowPay->date_fo_receipt.$mdoc][$ptypeKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $record->date_created,
            'mdoc_no'      => $mdoc,
			'receipt'	   => $resc[1],
            'doc_type'     => $doc_type,
            'narration'    => $record->field_value ?? '',
            'Account_Name' => $Account_Name.'',
            'cramount'     => $crTotal,
            'ptype'        => 'Cr',
            'vchref'       => $mdoc,
            'vchDate'      => $rowPay->doc_date,
            'gst'          => $gst,
            'state'        => $state,
            'city'         => $city,
            'gst_type'     => $gstType,
			'outlet_name'  =>'Front Office',
			'id_fo_receipt'=>$rowPay->id_fo_receipt,
			'date_fo_receipt'=>$rowPay->date_fo_receipt,
        ];
	$drTotal = 0;
       
	   $SalesRegisterArrayDr['Sales Register'][$folioTo][$mdoc]['CR-'.$rowPay->date_fo_receipt.$mdoc][$PaymentKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $record->date_created,
            'mdoc_no'      => $mdoc,
			'receipt'	   => $resc[1],
            'doc_type'     => $doc_type,
            'narration'    => $record->field_value ?? '',
            'Account_Name' => $payment_remarks,
            'dramount'     => $dramount,
            'ptype'        => 'Dr',
            'vchref'       => $mdoc,
            'vchDate'      => $rowPay->doc_date,
            'gst'          => $gst,
            'state'        => $state,
            'city'         => $city,
            'gst_type'     => $gstType,
			'outlet_name'  =>'Front Office',
			'id_fo_receipt'=>$rowPay->id_fo_receipt,
			'date_fo_receipt'=>$rowPay->date_fo_receipt,
        ];

        // Track total per bill
        if (!isset($ArrayOfFoBill[$record->bill_id])) {
            $ArrayOfFoBill[$record->bill_id] = [
                'bill_id'         => $record->bill_id,
                'id_reservations' => $record->id_reservations,
                'id_fo_folio_to'  => $record->id_fo_folio_to,
                'date_created'    => $record->date_created,
                'mdoc_no'         => $mdoc,
                'total_dramount'  => 0,
            ];
        }

        $ArrayOfFoBill[$record->bill_id]['total_dramount'] += $dramount;
    }
	
	}
}

//================================================================================
	
	$ArrayOfFoPurchBill=array();
// Subquery: Get individual payment rows for this bill
   $sqlReceipt = "
      SELECT 
    pp.id_purch,
    
    CASE 
        WHEN IFNULL(pp.amount, 0) > 0 THEN pp.payment_mode
        ELSE '0'
    END AS payment_type,

    CASE 
        WHEN pp.payment_mode = 'CASH' AND IFNULL(pp.amount, 0) > 0 THEN 'Cash Sales'
        WHEN pp.payment_mode = 'BIllONHOLD' AND IFNULL(pp.amount, 0) > 0 THEN 'Bill On Hold'
		 WHEN pp.payment_mode = 'COMPANY' AND IFNULL(pp.amount, 0) > 0 THEN 'id_company'
        WHEN pp.payment_mode = 'CARD' AND IFNULL(pp.amount, 0) > 0 THEN pp.id_charges_master
        WHEN pp.payment_mode = 'CHEQUE' AND IFNULL(pp.amount, 0) > 0 THEN 'Cash Sales'
        WHEN pp.payment_mode = 'UPI' AND IFNULL(pp.amount, 0) > 0 THEN 'UPI'
        WHEN pp.payment_mode = 'ONLINETRANSFER' AND IFNULL(pp.amount, 0) > 0 THEN pp.id_charges_master
        ELSE '0'
    END AS payment_remarks,

    IFNULL(pp.amount, 0) AS dramount,
    pp.id_company,

    -- Fields from pos_purch
    p.id_shop,
    p.id_doc_type_configuration,
    p.id_mst_outlet,
    p.id_fo_folio_to,
    p.mdoc_no,
    p.date_created,
    p.sub_total_items,
    p.sgst_total_items,
    p.cgst_total_items,
    p.igst_total_items,
    p.vat_total_items,
    p.round_off_amount,
    p.net_amount_items,
    p.grant_total_amount,
    p.discount_amount_additional,
    p.total_discount_items,
    p.others_charges_net_amount,
    p.cancelled

FROM pos_purch_pay pp
INNER JOIN pos_purch p ON p.id = pp.id_purch

WHERE 
    IFNULL(pp.amount, 0) > 0
    AND pp.payment_mode != 'ROOMTO'
    AND IFNULL(p.cancelled, 0) != 1  $SqlConn 
    ";

    $queryReceipt = mysqli_query($connNew, $sqlReceipt);
    while ($rowPay = mysqli_fetch_object($queryReceipt)) {
        $ptype = $rowPay->payment_type;
        $payment_remarks = $rowPay->payment_remarks;
        $dramount = $rowPay->dramount;
        $gst = '';
        $state = '';
        $city = '';
        $gstType = '';
		 if($payment_remarks != 'Bill On Hold'){
$outlet_name = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowPay->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');	
        // Handle company GST info
        if (!empty($rowPay->id_company)) {
            $payment_remarks =ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'"))); //'Cash'; '------------'.
            $gst = selectColumn(MST_COMPANY, 'fax', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $id_mst_state = selectColumn(MST_COMPANY, 'id_mst_state', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $state = ucwords(strtolower(selectColumn(TBL_STATE, 'name', "WHERE id_state='{$id_mst_state}'")));
            $city = 'Rajasthan'; // optional dynamic city
            $gstType = ($gst != '') ? 'Regular' : 'Unregistered/Consumer';
        }

        if ($payment_remarks == 'Cash Sales' && $gst == '' && $rowPay->id_company == '0') {
			$payment_remarks = 'Cash';
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
 if ($payment_remarks == 'UPI') {$payment_remarks = 'UPI';
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
        if (in_array($ptype, ['CARD', 'ONLINETRANSFER'])) {
            $payment_remarks = 'CARD';//selectColumn(TBL_CHARGES, 'name', "WHERE id='{$payment_remarks}' AND id_shop='{$id_shop}'");
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }

        // RoomTo logic (optional if needed per payment type)
      

        // Assign values
        $PaymentKey = $ptype . $rowPay->id_purch;
        $voucher_type = selectColumn(TBL_DOC_TYPE_CONFIG, 'doc_name', "WHERE id='{$record->id_doc_type_configuration}' AND id_shop='{$id_shop}'");
        $doc_type = selectColumn(TBL_PURCH, 'doc_type', "WHERE id='{$record->id}' AND id_shop='{$id_shop}'");

        $folioTo = $rowPay->mdoc_no.$rowPay->id_purch;
         $mdoc = $rowPay->mdoc_no;
	$resc 	=	explode('/',$mdoc);
			 
			 
			 
			 
			 //===========================================================
			 /* $sqlCR = "
    SELECT IFNULL(SUM(amount),0) AS total_amount
    FROM pos_purch_pay
    WHERE id_purch = '".addslashes($rowPay->id_purch)."' and doc_date='".date('Y-m-d',strtotime($rowPay->date_created))."'
    AND IFNULL(amount,0) > 0
";//die;
$resCR = mysqli_query($connNew, $sqlCR);
$rowCR = mysqli_fetch_object($resCR);

$crTotal = (float)$rowCR->total_amount;
	$ptypeKey=$rowPay->date_fo_receipt;
	
	$SalesRegisterArrayDr['Sales Register'][$folioTo][$mdoc]['DR-'.$rowPay->date_created.$mdoc][$ptypeKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $record->date_created,
            'mdoc_no'      => $mdoc,
			'receipt'	   => $resc[1],
            'doc_type'     => $doc_type,
            'narration'    => $record->field_value ?? '',
            'Account_Name' => $Account_Name,
            'cramount'     => $crTotal,
            'ptype'        => 'Cr',
            'vchref'       => $mdoc,
            'vchDate'      => $rowPay->doc_date,
            'gst'          => $gst,
            'state'        => $state,
            'city'         => $city,
            'gst_type'     => $gstType,
			'outlet_name'  =>$outlet_name,
		'id_fo_receipt'=>$mdoc,
			'date_fo_receipt'=>$rowPay->date_created,
        ];
	$drTotal = 0;*/
			 //===========================================================
			 
			 
      /*  $SalesRegisterArrayDr['Sales Register'][$folioTo][$mdoc][$ptype][$PaymentKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $rowPay->date_created,
            'mdoc_no'      => $mdoc,
			'receipt'	   => $resc[2],
            'doc_type'     => $doc_type,
            'narration'    => $rowPay->field_value ?? '',
            'Account_Name' => $payment_remarks,
            'dramount'     => $dramount,
            'ptype'        => 'Dr',
            'vchref'       => $mdoc,
            'vchDate'      => $rowPay->date_created,
            'gst'          => $gst,
            'state'        => $state,
            'city'         => $city,
            'gst_type'     => $gstType,
			'outlet_name'  =>$outlet_name,
			'id_fo_receipt'=>$mdoc,
			'date_fo_receipt'=>$rowPay->date_created,
        ];

        // Track total per bill
       if (!isset($ArrayOfFoPurchBill[$rowPay->id_purch])) {
            $ArrayOfFoPurchBill[$rowPay->id_purch] = [
                'bill_id'         => $rowPay->id_purch,             
                'date_created'    => $rowPay->date_created,
                'id_fo_folio_to'  => $mdoc.$rowPay->id_purch,
				'mdoc_no'         => $mdoc,
                'total_dramount'  => $dramount,
            ];
        }
			 
			 */
		 }
        //$ArrayOfFoPurchBill[$record->bill_id]['total_dramount'] += $dramount;
    }
	
	//=====================================================================================================

	
	//=====================================================================================================


//die;
//debugdata($SalesRegisterArrayDr);

//die;
foreach ($ArrayOfFoBill as $billData){
	
	$varNet_items='';
	$grant_total_amount_pos='';
	$round_Off='';
	
	$charges_Charges_Round_Off ='';
	$charges_sgst_Round_Off ='';
	$charges_cgst_Round_Off ='';
	
	$tariff_Round_Off ='';
	$tariff_sgst_Round_Off ='';
	$tariff_cgst_Round_Off ='';
	
    $bill_id         = $billData['bill_id'];
    $id_reservations = $billData['id_reservations'];
    $id_fo_folio_to  = $billData['id_fo_folio_to'];
	$date_created    = $billData['date_created'];
     $matchedMdocNo   = $billData['mdoc_no'];
	 $total_dramount= $billData['total_dramount'];
   
   $CreditRoundOff=0;
   $RoundoffAdd=0;
   
   $id_bill_to_company = selectColumn('fo_folio', 'id_bill_to_company', "WHERE id_mst_shops='{$id_shop}' AND id_fo_bill='{$bill_id}'");
if ($id_bill_to_company>0) {
            $Account_Name =  ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$id_bill_to_company}' AND id_shop='{$id_shop}'")));
			
}else{
	$Account_Name =  'Direct Guest A/c';

	}
   
	$taxMethod_sgst='Tariff Sales';	
	//$Account_Name = 'Room Tariff Sales';
	$FifthArray = 'Front Office';
	$reservation_query = mysqli_query($connNew, "select SUM(tax_per_day_per_room) AS total_tax,
        SUM(tariff_price_per_day_per_room) AS total_tariff, fo_reservations_details.* from fo_reservations_details where id_fo_bill = '".$bill_id."' and id_fo_reservations='".$id_reservations."' and checkin_status='1' ");
while ($reservation = mysqli_fetch_object($reservation_query)) {
	
    $percentage = round(($reservation->total_tax ?? 0) / ($reservation->total_tariff ?? 0) * 100);
	
    $reservation_percentage[] = $percentage;
	
	
   
	
	
	
				$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				
				$reservation_id_mst_charges_sales_local=$id_reservations;						
				
				$tax_per_day_per_room_sgst	=$reservation->total_tariff ?? 0;
				$total_tax	=$reservation->total_tax ?? 0;
					$tariff_Round_Off +=$reservation->total_tariff ?? 0;
	
	$resc 	=	explode('/',$matchedMdocNo);
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['receipt']=$resc[1];
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['cramount']+=$tax_per_day_per_room_sgst+$total_tax;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['outlet_name']='Front Office';
		$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['date_fo_receipt']=$vchDate;
	

		$RoundoffAdd+=$tax_per_day_per_room_sgst+$total_tax;
	
}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
//============Additon Charges Start=================================================================
//echo "<br>============Additon Charges Starts============select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."' and id_fo_reservations='".$id_reservations."'";
$reservation_query = mysqli_query($connNew, "select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."' and id_fo_reservations='".$id_reservations."'");
while ($reservation = mysqli_fetch_object($reservation_query)) {
	
    
	
	$amount = $reservation->rate * $reservation->qty * $reservation->days;
    $tax = $reservation->tax_value * $reservation->qty * $reservation->days;
    $percentage = ($tax / $amount) * 100;
   
	
	$reservation->id_fo_bill =$k;
	
				$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				//$taxMethod_sgst='Charges Sales';	
				$reservation_id_mst_charges_sales_local=$id_reservations;//'charges_Sales';						
				//$Account_Name = 'Food Plan Sales';
				$tax_per_day_per_room_sgst	=$reservation->tariff_price_per_day_per_room ?? 0;
	$charges_Charges_Round_Off+=$amount;
	$resc 	=	explode('/',$matchedMdocNo);
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['voucher_type']='Charges';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['receipt']=$resc[1];
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['cramount']+=$amount+$tax ;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['outlet_name']='Front Office';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sgst]['date_fo_receipt']=$vchDate;
	
	
	
	
	$RoundoffAdd+=$amount+$tax ;
	
	
	
	
	
	
	
	
	
	
}
//=============Additonal Charges end===================================================================


	
	
	
	
	
	
	
// FOOD START=========================================================================>




//Purch Data Start============================================================================================================================================
//debugdata($GetArrayForFoBillNo);
//Purch Data Start============================================================================================================================================
//debugdata($GetArrayForFoBillNo);
$varNet_itemss='';
 $SQLSalesReport1="SELECT 
    p.id,
    p.id_attribute_shift,
    p.id_doc_type_configuration,
    p.id_fo_bill,
    p.id_mst_charges_discounts,
    p.doc_type,
    comp.name AS outlet_name,
    usr.name AS user_name,
    p.mdoc_no,
    p.id_mst_outlet,
    p.sub_total_items,
    p.sgst_total_items,
    p.cgst_total_items,
    p.igst_total_items,
    p.cess_total_items,
    p.vat_total_items,
    p.surcharge_total_items,
    p.round_off_amount,
    p.net_amount_items,
    p.grant_total_amount,
    p.others_charges_net_amount,
    p.discount_amount_additional,
    p.total_discount_items,
    att.field_value AS shift_name,
    DATE(p.date_created) AS date_created,
    p.pax,
    p.id_attribute_table
FROM pos_purch AS p
INNER JOIN mst_attributes AS att 
    ON att.id = p.id_attribute_shift
INNER JOIN mst_outlets AS comp 
    ON comp.id = p.id_mst_outlet
INNER JOIN mst_users AS usr 
    ON usr.id = p.id_mst_user_created_by
WHERE 
    p.cancelled != 1 
    AND p.id_fo_bill IN (".$bill_id.")
GROUP BY p.id";
 
 
 
 
 "select p.id ,p.id_attribute_shift ,p.id_doc_type_configuration,p.id_fo_bill,
sum(ppd.item_sgst_amount) as sub_sgst_amount,
sum(ppd.item_cgst_amount) as sub_cgst_amount,
sum(ppd.item_vat_amount) as sub_vat_amount,
sum(ppd.item_surcharge_amount) as sub_surcharge_amount,
p.sc_sgst,p.sc_cgst,p.sc_charges_net_amount,
sum((ppd.rate_per_main_unit*ppd.qty)) as sub_item_amount,p.id_mst_charges_discounts,
p.doc_type,  comp.name as 'outlet_Name' ,usr.name ,p.mdoc_no ,p.id_mst_outlet ,p.sub_total_items ,p.sgst_total_items ,p.cgst_total_items ,p.igst_total_items ,p.cess_total_items ,p.vat_total_items ,p.surcharge_total_items ,p.round_off_amount ,p.net_amount_items ,p.grant_total_amount ,p.others_charges_net_amount ,p.discount_amount_additional ,p.total_discount_items ,att.field_value ,DATE(p.date_created) as date_created ,p.pax ,p.id_attribute_table,
ppd.id_mst_charges_sales_local,
ppd.id_mst_charges_sgst,
ppd.id_mst_charges_cgst,
ppd.id_mst_charges_igst,
ppd.id_mst_charges_cess,
ppd.id_mst_charges_vat,
ppd.id_mst_charges_surcharge,
ppd.item_sgst_percent,
ppd.item_cgst_percent

from pos_purch p 
INNER JOIN pos_purch_details ppd on ppd.id_pos_purch = p.id 
INNER JOIN mst_attributes att on att.id = p.id_attribute_shift 
INNER JOIN mst_outlets as comp on comp.id = p.id_mst_outlet 
INNER JOIN mst_users usr on usr.id=p.id_mst_user_created_by WHERE p.id!=0 and cancelled!=1 AND  p.id_fo_bill IN (".$bill_id.") $SqlConn112  group by p.id_fo_bill";

//echo $SQLSalesReport1;//die;
	$querySalesReport = mysqli_query($connNew,$SQLSalesReport1);
$NumberOfRowsSalesReport = mysqli_num_rows($querySalesReport);

$enable_split_bill_by_sales_account_groupLoop=2;
while($RecordsSalesReport	   =	mysqli_fetch_object($querySalesReport)){

	
$vchref=$RecordsSalesReport->mdoc_no;
$vchDate=$RecordsSalesReport->date_created;
	//================================================
	/*$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);*/
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	//echo '<br>==============================>'.$RecordsSalesReport->id_mst_charges_sales_local.$RecordsSalesReport->mdoc_no.'------'.$incs++.'====='.$RecordsSalesReport->id;
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	//================================================
	

	//selectColumn(TBL_OUTLETS,'name','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');	

$array = $checkArray;
$searchObject = $matchedMdocNo;
$keys = array_keys($array, $searchObject);




		$checkArray[$matchedMdocNo]=$matchedMdocNo;
		//debugData($y);
		if($enable_split_bill_by_sales_account_group==1){
			if($keys[0]==$matchedMdocNo){
		$enable_split_bill_by_sales_account_groupLoop=2;	
			}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
		}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
			
			
			//$matchedMdocNo =$RecordsSalesReport->mdoc_no;
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReport->id_doc_type_configuration.'" AND id_shop="'.$id_shop.'" ');
	
	
			
	$resc 	=	explode('/',$matchedMdocNo);
	
	
	selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sales_local.'"  ');
	$taxMethod_sales_local='Tariff Sales';	//$taxMethod_sales_local='id_mst_charges_sales_local';
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['receipt']=$resc[1];
		
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['id_mst_charges_sgst']=$outlet_name;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['cramount']+=$RecordsSalesReport->grant_total_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['assessable_value']=$RecordsSalesReport->sub_item_amount;//$RecordsSalesReport->sub_item_amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['outlet_name']=$outlet_name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_sales_local]['date_fo_receipt']=$vchDate;
	
	//Sales At Local=======================
	$RoundoffAdd+=$RecordsSalesReport->grant_total_amount;
}
	
	

	//	echo '<br>=============>'. 'Round Off'.'Total Amount==>'.$total_dramount.'=========';			
	 $varNet_items	=	$RoundoffAdd;
	//echo '<br>===========================>charges: '.$tariff_Round_Off.'====>'.$tariff_cgst_Round_Off.'====>'.$tariff_sgst_Round_Off;
	//echo '<br>===========================>'.$matchedMdocNo.'====>'.$total_dramount.'====>'.$varNet_items;
	//$round_Off	=	round((round($total_dramount,0)-$varNet_items),2);
	//$RecordsSalesReport->grant_total_amount-$RecordsSalesReport->net_amount_items;
	
	//-$discounts
	$difference = ($total_dramount) - $varNet_items;

	$round_Off= number_format($difference, 2);
	
	if($round_Off>0 && $round_Off!=0.00){
			//$taxMethod_round_off='round_off_amount';
	
			$taxMethod_round_off='Tariff Sales';		
											
	//$Account_Name = 'Cash';//'Balance / Excess';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['receipt']=$resc[1];
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['cramount']+=$round_Off;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['outlet_name']=$outlet_name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['date_fo_receipt']=$vchDate;
	}
	
	if($round_Off<0 && $round_Off!=0.00  && $round_Off!='0'){
			//$taxMethod_round_off='round_off_amount';
	$taxMethod_round_off='Excess';
							
											
	$Account_Name = 'Excess';//'Balance / Excess';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['receipt']=$resc[1];
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['dramount']+= -$round_Off;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['outlet_name']=$outlet_name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$FifthArray][$taxMethod_round_off]['date_fo_receipt']=$vchDate;
	}
	
	
	
	
}


//SECOND======================================

foreach ($ArrayOfFoPurchBill as $billData){
	
	$varNet_items='';
	$grant_total_amount_pos='';
	$round_Off='';
	
	$charges_Charges_Round_Off ='';
	$charges_sgst_Round_Off ='';
	$charges_cgst_Round_Off ='';
	
	$tariff_Round_Off ='';
	$tariff_sgst_Round_Off ='';
	$tariff_cgst_Round_Off ='';
	
    $bill_id         = $billData['bill_id'];
    $id_reservations = $billData['id_reservations'];
    $id_fo_folio_to  = $billData['id_fo_folio_to'];
	$date_created    = $billData['date_created'];
     $matchedMdocNo   = $billData['mdoc_no'];
	 $total_dramount= $billData['total_dramount'];
   
	

	
	
	
	
	
	
	




//Purch Data Start============================================================================================================================================
//debugdata($GetArrayForFoBillNo);
$varNet_itemss='';
 $SQLSalesReport1="SELECT 
    p.id,
    p.id_attribute_shift,
    p.id_doc_type_configuration,
    p.id_fo_bill,
    p.id_mst_charges_discounts,
    p.doc_type,
    comp.name AS outlet_name,
    usr.name AS user_name,
    p.mdoc_no,
    p.id_mst_outlet,
    p.sub_total_items,
    p.sgst_total_items,
    p.cgst_total_items,
    p.igst_total_items,
    p.cess_total_items,
    p.vat_total_items,
    p.surcharge_total_items,
    p.round_off_amount,
    p.net_amount_items,
    p.grant_total_amount,
    p.others_charges_net_amount,
    p.discount_amount_additional,
    p.total_discount_items,
    att.field_value AS shift_name,
    DATE(p.date_created) AS date_created,
    p.pax,
    p.id_attribute_table
FROM pos_purch AS p
INNER JOIN mst_attributes AS att 
    ON att.id = p.id_attribute_shift
INNER JOIN mst_outlets AS comp 
    ON comp.id = p.id_mst_outlet
INNER JOIN mst_users AS usr 
    ON usr.id = p.id_mst_user_created_by
 WHERE p.id!=0 and cancelled!=1 AND  p.id IN (".$bill_id.") $SqlConn112  group by p.id_fo_bill";

//echo $SQLSalesReport1;//die;
	$querySalesReport = mysqli_query($connNew,$SQLSalesReport1);
$NumberOfRowsSalesReport = mysqli_num_rows($querySalesReport);

$enable_split_bill_by_sales_account_groupLoop=2;
while($RecordsSalesReport	   =	mysqli_fetch_object($querySalesReport)){

	
$vchref=$RecordsSalesReport->mdoc_no;
$vchDate=$RecordsSalesReport->date_created;
	//================================================
	/*$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);*/
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	//echo '<br>==============================>'.$RecordsSalesReport->id_mst_charges_sales_local.$RecordsSalesReport->mdoc_no.'------'.$incs++.'====='.$RecordsSalesReport->id;
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	//================================================
	
$outlet_name = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');
		

$array = $checkArray;
$searchObject = $matchedMdocNo;
$keys = array_keys($array, $searchObject);




		$checkArray[$matchedMdocNo]=$matchedMdocNo;
		//debugData($y);
		if($enable_split_bill_by_sales_account_group==1){

			if($keys[0]==$matchedMdocNo){
		$enable_split_bill_by_sales_account_groupLoop=2;	
			}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
		}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
			
			
			//$matchedMdocNo =$RecordsSalesReport->mdoc_no;
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReport->id_doc_type_configuration.'" AND id_shop="'.$id_shop.'" ');
	
			
	$Account_Name_local =  'Direct Guest A/c';
	//$Account_Name_local = 'Credit Sales';selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sales_local.'"  ');
	$taxMethod_sales_local='id_mst_charges_sales_local';
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local;
	$resc 	=	explode('/',$RecordsSalesReport->mdoc_no);
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['receipt']=$resc[2];
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['id_mst_charges_sgst']=$outlet_name;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['Account_Name']=$Account_Name_local;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['cramount']+=$RecordsSalesReport->grant_total_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['assessable_value']=$RecordsSalesReport->sub_item_amount;//$RecordsSalesReport->sub_item_amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['outlet_name']=$outlet_name;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['id_fo_receipt']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$outlet_name][$taxMethod_sales_local]['date_fo_receipt']=$vchDate;
	


}


	
	
}



//debugdata($data);die;
//debugdata($SalesRegisterArray);die;
//SalesRegisterArrayDebit = $SalesRegisterArray;

$SalesRegisterArray =$SalesRegisterArrayDr;//array_replace_recursive($SalesRegisterArray, $SalesRegisterArrayDr);
$count=0;
//die;
foreach($SalesRegisterArray as $SalesRegisterArraylist=> $SalesRegisterArray1){
	
	
	foreach($SalesRegisterArray1 as $SalesRegisterArraylist1=> $SalesRegisterArray2){
		$FirstSatrt='';
		$count++;
		foreach($SalesRegisterArray2 as $SalesRegisterArraylist2=> $SalesRegisterArray3){
			//echo '==================='.$VoucherNo=$SalesRegisterArraylist2;
			//debugdata($SalesRegisterArray2);
			
			foreach($SalesRegisterArray3 as $SalesRegisterArraylist3=> $SalesRegisterArray4){
				//debugdata($SalesRegisterArray4);
				foreach ($SalesRegisterArray4 as $SalesRegisterArraylist4 => $SalesRegisterArray5) {

    if (
        ($SalesRegisterArray5['dramount'] ?? 0) > 0 ||
        ($SalesRegisterArray5['cramount'] ?? 0) > 0
    ) {

        $currentReceiptDate = $SalesRegisterArray5['date_fo_receipt'];
//$prevReceiptDate != $currentReceiptDate  ||
        /* -------- DATE CHANGE CHECK -------- */
       if (
    $prevReceipt !== $SalesRegisterArray5['receipt'] // new receipt number
) {
    // new receipt → reset counter
    $rcptCounter = 1;
    $prevReceipt = $SalesRegisterArray5['receipt'];
    $prevReceiptDate = $currentReceiptDate;

} elseif (
    $prevReceiptDate !== $currentReceiptDate // same receipt, new date
) {
    // same receipt, date changed → increment
    $rcptCounter++;
    $prevReceiptDate = $currentReceiptDate;
}

        $InvDate = date('d-m-Y', strtotime($currentReceiptDate));

        $objPHPExcel->getActiveSheet()
            ->getStyle('A'.$con.':J'.$con)
            ->applyFromArray($styleThinBlackBorderOutline);

        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$con, 'Receipt')
            ->setCellValue(
                'B'.$con,
                'RCPT'.$SalesRegisterArray5['receipt'].'-'.$rcptCounter
            )
            //->setCellValue('C'.$con, $SalesRegisterArray5['mdoc_no'])
            ->setCellValue('C'.$con, $InvDate)
            ->setCellValue('D'.$con, 'REF'.$SalesRegisterArray5['id_fo_receipt'])
            ->setCellValue('E'.$con, $SalesRegisterArray5['Account_Name'])
            ->setCellValue('F'.$con, $SalesRegisterArray5['narration'])
            ->setCellValue(
                'G'.$con,
                ($SalesRegisterArray5['dramount'] ?? 0) > 0
                    ? $SalesRegisterArray5['dramount']
                    : 0
            )
            ->setCellValue(
                'H'.$con,
                ($SalesRegisterArray5['cramount'] ?? 0) > 0
                    ? $SalesRegisterArray5['cramount']
                    : 0
            )
            ->setCellValue('I'.$con, $SalesRegisterArray5['mdoc_no'])
            ->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])));

        $con++;
    }
}
				/*foreach($SalesRegisterArray4 as $SalesRegisterArraylist4=> $SalesRegisterArray5){
					
					
					if($SalesRegisterArray5['dramount']>0 || $SalesRegisterArray5['cramount']>0){   //Amount Debit or Credit >0
					$InvDate=date('d-m-Y',strtotime($SalesRegisterArray5['date_fo_receipt']));
					
					
					//print_r($Md);die;
					//PHPExcel_Style_NumberFormat::toFormattedString(date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])), 'dd-mmm-yyyy');
					
			//$InvDate = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($SalesRegisterArray5['date_created']));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':H'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Receipt')//$SalesRegisterArray5['voucher_type']
				
				->setCellValue('B'.$con, $SalesRegisterArraylist2!=$FirstSatrt?'RCPT'.$SalesRegisterArray5['receipt']:'')
				->setCellValue('C'.$con, $SalesRegisterArray5['mdoc_no']);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('D'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_fo_receipt']))); 
						$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('E'.$con, $SalesRegisterArray5['id_fo_receipt']);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('F'.$con, $SalesRegisterArray5['Account_Name'])
				->setCellValue('G'.$con, $SalesRegisterArray5['narration'])
				->setCellValue('H'.$con, $SalesRegisterArray5['dramount']>0?$SalesRegisterArray5['dramount']:0)

				->setCellValue('I'.$con, $SalesRegisterArray5['cramount']>0?$SalesRegisterArray5['cramount']:0)
						->setCellValue('J'.$con, $SalesRegisterArray5['mdoc_no'])
						->setCellValue('K'.$con, $SalesRegisterArray5['date_created']);

				
					$con++;
			$FirstSatrt=$SalesRegisterArraylist2;
			//debugdata($SalesRegisterArray5);
					}
				}*/
				
			}
			
		}
		
	}
	
}//die;
$objPHPExcel->getActiveSheet($sheetIndexFive)->getColumnDimension('E')->setWidth(35);
		
		 $objPHPExcel->getActiveSheet()->setTitle('Sales Register');	
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
	ob_end_clean();

if($cronSet=='1'){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/mailattach/'.$pdfName.'.xls');$objPHPExcel='';//exit;
}else{



	$filename=	'SalesReceipt'.date('d-M-Y').'.xls';
	// Redirect output to a client's web browser (Excel2007)
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