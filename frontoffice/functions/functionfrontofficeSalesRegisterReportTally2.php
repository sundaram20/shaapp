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
function frontofficeSalesRegisterReportTally2($Date,$id_outlet,$id_shift,$objPHPExcel,$connNew,$id_shop,$cronSet,$pdfName,$id_charges_name){
	//echo $id_shift;die;
	global $connNew;
	global $objPHPExcel;
	
	
	$chagesFieldName = ($id_charges_name == 1) ? 'name' : 'display_alias_name';
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date('Y-m-d',strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
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
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleThinBlackBorderOutline);

$con=$setcellcount;



$objPHPExcel->getActiveSheet()
->getStyle('M')
->getNumberFormat()
->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2 );

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Q'.$con)->applyFromArray($styleThinBlackBorderOutline);


			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Q'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$con=1;	
			//Voucher Type	Voucher No.	Voucher Dt.	Account Name	Narration	Dr Amount	Cr Amount		vchref	vchDate	Assessable Value	Surcharge

				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Voucher Type')
			->setCellValue('B'.$con, 'Voucher No.')
			->setCellValue('C'.$con, 'Voucher Dt.')
			->setCellValue('D'.$con, 'Account Name')
			->setCellValue('E'.$con, 'Narration')
			->setCellValue('F'.$con, 'Dr Amount')				
			->setCellValue('G'.$con, 'Cr Amount')
			->setCellValue('H'.$con, 'Type')
			->setCellValue('I'.$con, 'vchref')			
			->setCellValue('J'.$con, 'vchDate')
			->setCellValue('K'.$con, 'Assessable Value')
			->setCellValue('L'.$con, 'Surcharge')
			->setCellValue('M'.$con, 'GSTIN')
			->setCellValue('N'.$con, 'State')
			->setCellValue('O'.$con, 'Place of Supply')	
			->setCellValue('P'.$con, 'GST Type')
			->setCellValue('Q'.$con, 'Outlet');
					
		
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
	p.id_owner_room,
	
    r.booking_no,
    r.doc_date,
    r.checkin,
    r.checkout,
	r.reference,
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
    p.date_created DESC
";

//echo $SQLSalesReportPaymentx;die;


// Execute query
$querySalesReportPayment = mysqli_query($connNew, $SQLSalesReportPaymentx);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);

$SalesRegisterArray = array();
$ics = 1;
while ($record = mysqli_fetch_object($querySalesReportPayment)) {
     $room_id_mst_guest = selectColumn(FO_RESERVATIONS_DETAILS, 'id_mst_guest', "WHERE id_mst_room_no_allocation='{$record->id_owner_room}' AND id_fo_reservations='{$record->id_reservations}'");		

            
                $guestFirstName = selectColumn(TBL_GUEST, 'first_name', "WHERE id='{$room_id_mst_guest}'");
                $guestLastName = selectColumn(TBL_GUEST, 'last_name', "WHERE id='{$room_id_mst_guest}'");
                $titleId = selectColumn(TBL_GUEST, 'id_mst_attributes_title', "WHERE id='{$room_id_mst_guest}'");
                $guestTitle = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id='{$titleId}' AND table_name='title'");
                $room_mst_guest = trim("{$guestTitle}{$guestFirstName} {$guestLastName}");
            
    // Subquery: Get individual payment rows for this bill
 /*  $sqlReceipt = "
        SELECT 
            CASE 
                WHEN IFNULL(amount, 0) > 0 THEN payment_mode
                ELSE '0'
            END AS payment_type,

            CASE 
                WHEN payment_mode = 'CASH' AND IFNULL(amount, 0) > 0 THEN 'Cash Sales'
                WHEN payment_mode = 'BIllONHOLD' AND IFNULL(amount, 0) > 0 THEN 'Bill On Hold'
                WHEN payment_mode = 'CARD' AND IFNULL(amount, 0) > 0 THEN id_charges_master
                WHEN payment_mode = 'CHEQUE' AND IFNULL(amount, 0) > 0 THEN 'Cash Sales'
                WHEN payment_mode = 'UPI' AND IFNULL(amount, 0) > 0 THEN 'UPI'
                WHEN payment_mode = 'ONLINETRANSFER' AND IFNULL(amount, 0) > 0 THEN id_charges_master
                ELSE '0'
            END AS payment_remarks,

            IFNULL(amount, 0) AS dramount,
            id_company
        FROM fo_receipt 
        WHERE id_fo_bill = '".addslashes($record->bill_id)."' 
        AND IFNULL(amount, 0) > 0
    ";
*/


$sqlReceipt = "SELECT 
    MAX(payment_mode) AS payment_type,
    CASE 
        WHEN MAX(IFNULL(id_company, 0)) > 0 THEN MAX(id_company)
        ELSE 'Direct Guest A/c'
    END AS payment_remarks,
    SUM(IFNULL(amount, 0)) AS dramount,
    MAX(id_company) AS id_company,id_fo_bill
FROM fo_receipt 
WHERE id_fo_bill = '".addslashes($record->bill_id)."' 
  AND IFNULL(amount, 0) > 0  $sqlPaymentModeRe
GROUP BY id_fo_bill";//echo $sqlReceipt;die;
    $queryReceipt = mysqli_query($connNew, $sqlReceipt);
    while ($rowPay = mysqli_fetch_object($queryReceipt)) {
        $ptype = $rowPay->payment_type;
        $payment_remarks = $rowPay->payment_remarks;
        $dramount = $rowPay->dramount;
        $gst = '';
        $state = '';
        $city = '';
        $gstType = '';

        // Handle company GST info
        if (!empty($rowPay->id_company)) {
            echo '<br>================>'.$record->mdoc_no.'==='.$payment_remarks = ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'")));
            $gst = selectColumn(MST_COMPANY, 'fax', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $id_mst_state = selectColumn(MST_COMPANY, 'id_mst_state', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $state_name = ucwords(strtolower(selectColumn(TBL_STATE, 'name', "WHERE id_state='{$id_mst_state}'")));
			$state = $state_name!=''?$state_name:'Rajasthan';
           
			//$city_name = selectColumn(MST_COMPANY, 'city', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");//'Rajasthan'; // optional dynamic city
			$city = 'Rajasthan';
            $gstType = ($gst != '') ? 'Regular' : 'Unregistered/Consumer';
        }

$id_bill_to_company = selectColumn('fo_folio', 'id_bill_to_company', "WHERE id_mst_shops='{$id_shop}' AND id_fo_bill='{$rowPay->id_fo_bill}'");
if ($id_bill_to_company>0) {
            $payment_remarks = ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$id_bill_to_company}' AND id_shop='{$id_shop}'")));
            $gst = selectColumn(MST_COMPANY, 'fax', "WHERE id='{$id_bill_to_company}' AND id_shop='{$id_shop}'");
            $id_mst_state = selectColumn(MST_COMPANY, 'id_mst_state', "WHERE id='{$id_bill_to_company}' AND id_shop='{$id_shop}'");
            $state_name = ucwords(strtolower(selectColumn(TBL_STATE, 'name', "WHERE id_state='{$id_mst_state}'")));
			$state = $state_name!=''?$state_name:'Rajasthan';
           // $city_name = selectColumn(MST_COMPANY, 'city', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");//'Rajasthan'; // optional dynamic city
			$city = 'Rajasthan';
            $gstType = ($gst != '') ? 'Regular' : 'Unregistered/Consumer';
        }


        if ($payment_remarks == 'Cash Sales' && $gst == '' && $rowPay->id_company == '0') {
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
 if ($payment_remarks == 'UPI' ) {
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
        if (in_array($ptype, ['CARD', 'ONLINETRANSFER'])) {
           // $payment_remarks = selectColumn(TBL_CHARGES, 'name', "WHERE id='{$payment_remarks}' AND id_shop='{$id_shop}'");
           // $state = 'Rajasthan';
           // $city = 'Rajasthan';
           // $gstType = 'Unregistered/Consumer';
        }
if ($payment_remarks == 'Direct Guest A/c'  && $rowPay->id_company == '0') {
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

        $SalesRegisterArray['Sales Register'][$folioTo][$mdoc][$ptype][$PaymentKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $record->date_created,
            'mdoc_no'      => $mdoc,
			 'reference'      => $record->reference,
            'doc_type'     => $doc_type,
            'narration'    => $record->field_value ?? '',
            'Account_Name' => $payment_remarks,
            'dramount'     => $dramount,
			'guest_name' =>$room_mst_guest,
			'checkin' =>$record->checkin,
			'checkout' =>$record->checkout,
            'ptype'        => 'Dr',
            'vchref'       => $mdoc,
            'vchDate'      => $record->date_created,
            'gst'          => $gst,
            'state'        => $state,
            'city'         => $city,
            'gst_type'     => $gstType,
			'outlet_name'  =>'Front Office',
        ];

        // Track total per bill
        if (!isset($ArrayOfFoBill[$record->bill_id])) {
            $ArrayOfFoBill[$record->bill_id] = [
                'bill_id'         => $record->bill_id,
                'id_reservations' => $record->id_reservations,
				'reference'      => $record->reference,
				'guest_name' =>$room_mst_guest,
				'checkin' =>$record->checkin,
			'checkout' =>$record->checkout,
                'id_fo_folio_to'  => $record->id_fo_folio_to,
                'date_created'    => $record->date_created,
                'mdoc_no'         => $mdoc,
                'total_dramount'  => 0,
            ];
        }

        $ArrayOfFoBill[$record->bill_id]['total_dramount'] += $dramount;
    }
	
	
}

//================================================================================
	
	$ArrayOfFoPurchBill=array();
// Subquery: Get individual payment rows for this bill
   /*$sqlReceipt = "
      SELECT 
    pp.id_purch,
    
    CASE 
        WHEN IFNULL(pp.amount, 0) > 0 THEN pp.payment_mode
        ELSE '0'
    END AS payment_type,

    CASE 
        WHEN pp.payment_mode = 'CASH' AND IFNULL(pp.amount, 0) > 0 THEN 'Cash Sales'
        WHEN pp.payment_mode = 'BIllONHOLD' AND IFNULL(pp.amount, 0) > 0 THEN 'Bill On Hold'
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
    ";*/
	
	$sqlReceipt = "SELECT 
    pp.id_purch,
    
    CASE 
        WHEN IFNULL(pp.amount, 0) > 0 THEN pp.payment_mode
        ELSE '0'
    END AS payment_type,

    CASE 
        WHEN IFNULL(pp.id_company, 0) > 0 THEN pp.id_company
        ELSE 'Direct Guest A/c'
    END AS payment_remarks,

    SUM(IFNULL(pp.amount, 0)) AS dramount,
    MAX(pp.id_company) AS id_company,

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
    AND IFNULL(p.cancelled, 0) != 1
    $SqlConn

GROUP BY 
    pp.id_purch";

    $queryReceipt = mysqli_query($connNew, $sqlReceipt);
    while ($rowPay = mysqli_fetch_object($queryReceipt)) {
        $ptype = $rowPay->payment_type;
        $payment_remarks = $rowPay->payment_remarks;
        $dramount = $rowPay->dramount;
        $gst = '';
        $state = '';
        $city = '';
        $gstType = '';
		$outlet_name = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowPay->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');	
        // Handle company GST info
        if (!empty($rowPay->id_company)) {
            $payment_remarks = ucwords(strtolower(selectColumn(MST_COMPANY, 'name', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'")));
            $gst = selectColumn(MST_COMPANY, 'fax', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $id_mst_state = selectColumn(MST_COMPANY, 'id_mst_state', "WHERE id='{$rowPay->id_company}' AND id_shop='{$id_shop}'");
            $state = ucwords(strtolower(selectColumn(TBL_STATE, 'name', "WHERE id_state='{$id_mst_state}'")));
            $city = 'Rajasthan'; // optional dynamic city
            $gstType = ($gst != '') ? 'Regular' : 'Unregistered/Consumer';
        }
		if ($payment_remarks == 'Direct Guest A/c'  && $rowPay->id_company == '0') {
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
        if ($payment_remarks == 'Cash Sales' && $gst == '' && $rowPay->id_company == '0') {
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
 	 if ($payment_remarks == 'UPI') {
            $state = 'Rajasthan';
            $city = 'Rajasthan';
            $gstType = 'Unregistered/Consumer';
        }
        if (in_array($ptype, ['CARD', 'ONLINETRANSFER'])) {
          //  $payment_remarks = selectColumn(TBL_CHARGES, 'name', "WHERE id='{$payment_remarks}' AND id_shop='{$id_shop}'");
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

        $SalesRegisterArray['Sales Register'][$folioTo][$mdoc][$ptype][$PaymentKey] = [
            'voucher_type' => $voucher_type,
            'date_created' => $rowPay->date_created,
            'mdoc_no'      => $mdoc,
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

        //$ArrayOfFoPurchBill[$record->bill_id]['total_dramount'] += $dramount;
    }
	
	//=====================================================================================================




//die;
//debugdata($ArrayOfFoBill);
//debugdata($SalesRegisterArray);
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
	 $total_dramount	= $billData['total_dramount'];
    $room_mst_guest= '';
	
	$reservation_query = mysqli_query($connNew, "select SUM(tax_per_day_per_room) AS total_tax,
        SUM(tariff_price_per_day_per_room) AS total_tariff, fo_reservations_details.* from fo_reservations_details where id_fo_bill = '".$bill_id."' and id_fo_reservations='".$id_reservations."' and checkin_status='1' ");
while ($reservation = mysqli_fetch_object($reservation_query)) {
	
    $percentage = round(($reservation->total_tax ?? 0) / ($reservation->total_tariff ?? 0) * 100);
	
    $reservation_percentage[] = $percentage;
	
	$Sql_Count_status= 	"SELECT
			t.dated,
			t.room_count,
			d.no_of_days
		FROM
		(
			SELECT
				dated,
				COUNT(DISTINCT order_by_room) AS room_count
			FROM fo_reservations_details
			WHERE id_fo_bill = '".$bill_id."' and id_fo_reservations='".$id_reservations."' and checkin_status='1' 
			GROUP BY dated
		) AS t
		CROSS JOIN
		(
			SELECT COUNT(DISTINCT dated) AS no_of_days
			FROM fo_reservations_details
			WHERE  id_fo_bill = '".$bill_id."' and id_fo_reservations='".$id_reservations."' and checkin_status='1' 
		) AS d
		ORDER BY t.dated DESC";
$reservation_Count_status = mysqli_query($connNew,$Sql_Count_status);
$rowCount_status= mysqli_fetch_object($reservation_Count_status);

		$SelectTaxDateSQL = mysqli_query($connNew,
    "SELECT * FROM `" . TBL_TAX_DATE_RULE . "` 
     WHERE id_shop='" . addslashes($_SESSION['shop']) . "' 
       AND start_date <= CURDATE() AND status='1' 
     ORDER BY start_date DESC"
);
$SelectTaxDateRow = mysqli_fetch_object($SelectTaxDateSQL);
$SlectedDateNewTax_id = $SelectTaxDateRow->id ?? 0;


	$tax_detail	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$reservation->id_fo_rate_plan."'");
	//echo '------------------====>'.$reservation->id_fo_rate_plan.'--'.$tax_detail	;
	if($tax_detail=='1'){//1 for inclusive
		
		
	$price =($reservation->total_tariff/$rowCount_status->room_count)/$rowCount_status->no_of_days;
	
       /*$resNewTaxInclution = mysqli_query(
        $connNew,
        "SELECT * FROM `" . TBL_TAX_RULE . "` 
         WHERE id_shop='" . addslashes($_SESSION['shop']) . "' 
           AND ((tax_inc_slabs_from <= '$price' AND tax_inc_slabs_to >= '$price') 
             OR (tax_inc_slabs_from BETWEEN '$price' AND '$price') 
             OR (tax_inc_slabs_to BETWEEN '$price' AND '$price')) 
           AND tax_uniqueid='$SlectedDateNewTax_id' 
         ORDER BY start_date DESC LIMIT 1"
    );	*/
	
	$resNewTaxInclution = mysqli_query(
        $connNew,
        "SELECT * FROM `" . TBL_TAX_RULE . "` 
         WHERE id_shop='" . addslashes($_SESSION['shop']) . "' 
           AND ((tax_slabs_from <= '$price' AND tax_slabs_to >= '$price') 
             OR (tax_slabs_from BETWEEN '$price' AND '$price') 
             OR (tax_slabs_to BETWEEN '$price' AND '$price')) 
           AND tax_uniqueid='$SlectedDateNewTax_id' 
         ORDER BY start_date DESC LIMIT 1"
    );
		 
		 
	}else{ //2 for exclusive	
		
		
	$price =($reservation->total_tariff/$rowCount_status->room_count)/$rowCount_status->no_of_days;//$reservation->total_tariff;
	 $resNewTaxInclution = mysqli_query(
        $connNew,
        "SELECT * FROM `" . TBL_TAX_RULE . "` 
         WHERE id_shop='" . addslashes($_SESSION['shop']) . "' 
           AND ((tax_slabs_from <= '$price' AND tax_slabs_to >= '$price') 
             OR (tax_slabs_from BETWEEN '$price' AND '$price') 
             OR (tax_slabs_to BETWEEN '$price' AND '$price')) 
           AND tax_uniqueid='$SlectedDateNewTax_id' 
         ORDER BY start_date DESC LIMIT 1"
    );
	}

	$tax_percent = 0;
   
    if (mysqli_num_rows($resNewTaxInclution) > 0) {
        $rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
        $tax_percent = $rowNewTaxInclution->tax_percent;
        
    } else {
        $tax_percent = '5';
        
    }
   if($reservation->tax_percent>0){

$tax_percent=$reservation->tax_percent;

   }
	//$doc_type = selectColumn(TBL_PURCH, 'doc_type', "WHERE id='{$record->id}' AND id_shop='{$id_shop}'");
	//$tax_percent=$reservation->tax_percent;
	
				$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_sgst='Tariff Sales';	
				$reservation_id_mst_charges_sales_local=$id_reservations;						
				echo '<br>===================>'.$Account_Name = 'ROOM TARIFF INCOME '.round($tax_percent).'%';
				$tax_per_day_per_room_sgst	=$reservation->total_tariff ?? 0;
				
					$tariff_Round_Off +=$reservation->total_tariff ?? 0;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['reference']=$reference;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['guest_name']=$room_mst_guest;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$tax_per_day_per_room_sgst;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']='Front Office';
	
	
				$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_sgst='sgst_'.round($percentage / 2);	
				$reservation_id_mst_charges_sales_local=$id_reservations;//'sgst_1';						
				//$Account_Name = 'Output SGST @ '.($percentage / 2).'%';
	
$value = number_format($percentage / 2, 2, '.', '');
$value = rtrim(rtrim($value, '0'), '.');
$Account_Name = 'Output SGST @ ' . $value . '%';
	
				$tax_per_day_per_room_sgst	=($reservation->total_tax ?? 0) / 2;
				
							
	$tariff_sgst_Round_Off +=round((($reservation->total_tax ?? 0) / 2),2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=round($tax_per_day_per_room_sgst,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']='Front Office';
	
	
	
				
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_cgst='cgst_'.round($percentage / 2);	//'cgst';	
				$reservation_id_mst_charges_sales_local=$id_reservations;//'cgst_1';						
				//$Account_Name = 'Output CGST @ '.($percentage / 2).'%';
	
	$value = number_format($percentage / 2, 2, '.', '');
$value = rtrim(rtrim($value, '0'), '.');
$Account_Name = 'Output CGST @ ' . $value . '%';
	
				$tax_per_day_per_room_sgst	=($reservation->total_tax ?? 0) / 2;
				
						
				$tariff_cgst_Round_Off +=round((($reservation->total_tax ?? 0) / 2),2);
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=round($tax_per_day_per_room_sgst,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']='Front Office';
	
	//Sales At Local=======================
	
		
	
}
	
	
	
	
	
	//die;
	
	
	
	
	
	
	
	
//============Additon Charges Start=================================================================
//echo "<br>============Additon Charges Starts============select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."' and id_fo_reservations='".$id_reservations."'";
	//echo "<br>============Additon Charges Starts============select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."' and id_fo_reservations='".$id_reservations."'";
	//and id_fo_reservations='".$id_reservations."'
$reservation_query = mysqli_query($connNew, "select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."' ");
while ($reservation = mysqli_fetch_object($reservation_query)) {
	
    
	
	$amount = $reservation->rate * $reservation->qty * $reservation->days;
    $tax = $reservation->tax_value * $reservation->qty * $reservation->days;
    $percentage = ($tax / $amount) * 100;
   
	
	$reservation->id_fo_bill =$k;
	
				$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_sgst='Charges Sales';	
								
				//$Account_Name = 'Food Plan Sales';
	
	
				$Account_Name = selectColumn(TBL_CHARGES, $chagesFieldName,'WHERE id="'.$reservation->id_mst_charges.'"  ');
				$tax_per_day_per_room_sgst	=$reservation->tariff_price_per_day_per_room ?? 0;
	$charges_Charges_Round_Off+=$amount;
	$reservation_id_mst_charges_sales_local=$Account_Name;//'charges_Sales';		
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']='Charges';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['cramount']+=$amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']='Front Office';
	
	$id_mst_charges_sgst = selectColumn(TBL_CHARGES, 'id_mst_charges_sgst', "WHERE id='{$reservation->id_mst_charges}' AND id_shop='{$id_shop}'");
	$id_mst_charges_cgst = selectColumn(TBL_CHARGES, 'id_mst_charges_cgst', "WHERE id='{$reservation->id_mst_charges}' AND id_shop='{$id_shop}'");
	
	$percentage_sgst = selectColumn(TBL_CHARGES, 'percentage', "WHERE id='{$id_mst_charges_sgst}' AND id_shop='{$id_shop}'");
	$percentage_cgst = selectColumn(TBL_CHARGES, 'percentage', "WHERE id='{$id_mst_charges_cgst}' AND id_shop='{$id_shop}'");
	
				//$percentage_sgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				//$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_sgst='charges_sgst'.($percentage_sgst);	
				//$reservation_id_mst_charges_sales_local=$id_reservations;//'charges_sgst_1';						
				$Account_Name = 'Output SGST @ '.(floatval($percentage_sgst)).'%';
				$tax_per_day_per_room_sgst	=($reservation->tax_per_day_per_room ?? 0) / 2;
				$charges_sgst_Round_Off+=round(($tax) / 2,2)+round(($tax) / 2,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['cramount']+=round(($tax) / 2,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']='Front Office';
	
	
				
				//$percentage_cgst	=round($percentage > 0 ? ($percentage / 2) : 0);
				$taxMethod_cgst='charges_cgst'.($percentage_cgst);	
				//$reservation_id_mst_charges_sales_local=$id_reservations;//'charges_cgst_1';						
				$Account_Name = 'Output CGST @ '.(floatval($percentage_cgst)).'%';
				$tax_per_day_per_room_sgst	=($reservation->tax_per_day_per_room ?? 0) / 2;
				
				
				
				//$charges_cgst_Round_Off+=round(($tax) / 2,2);
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']='Tariff';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['cramount']+=round(($tax) / 2,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$reservation_id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']='Front Office';
	
	//Sales At Local=======================
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}
//=============Additonal Charges end===================================================================


	
	//die;
	
	
	
	
	
// FOOD START=========================================================================>




//Purch Data Start============================================================================================================================================
//debugdata($GetArrayForFoBillNo);
$varNet_itemss='';
 $SQLSalesReport1="select p.id ,p.id_attribute_shift ,p.id_doc_type_configuration,p.id_fo_bill,
sum(ppd.item_sgst_amount) as sub_sgst_amount,
sum(ppd.item_cgst_amount) as sub_cgst_amount,
sum(ppd.item_vat_amount) as sub_vat_amount,
sum(ppd.item_surcharge_amount) as sub_surcharge_amount,
p.sc_sgst,p.sc_cgst,p.sc_charges_net_amount,
sum(ppd.item_discount_amount) as item_discount_amount_total,
sum((ppd.rate_per_main_unit*ppd.qty)) as sub_item_amount,p.id_mst_charges_discounts,
p.doc_type,  comp.name as 'outlet_Name' ,usr.name ,p.mdoc_no ,p.id_mst_outlet ,p.sub_total_items ,p.sgst_total_items ,p.cgst_total_items ,p.igst_total_items ,p.cess_total_items ,p.vat_total_items ,p.surcharge_total_items ,p.round_off_amount ,p.net_amount_items ,p.grant_total_amount ,p.others_charges_net_amount ,p.discount_amount_additional ,p.total_discount_items  ,DATE(p.date_created) as date_created ,p.pax ,p.id_attribute_table,
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

INNER JOIN mst_outlets as comp on comp.id = p.id_mst_outlet 
INNER JOIN mst_users usr on usr.id=p.id_mst_user_created_by WHERE p.id!=0 and cancelled!=1 AND  p.id_fo_bill IN (".$bill_id.") $SqlConn112  group by ppd.id_mst_charges_sales_local,p.id_fo_bill";
//INNER JOIN mst_attributes att on att.id = p.id_attribute_shift 
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
	

	$outlet_name = 'Front Office';//selectColumn(TBL_OUTLETS,'name','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');	

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
	
	if($RecordsSalesReport->id_mst_charges_sgst>0  || $RecordsSalesReport->id_mst_charges_vat>0){
			
	
	$Account_Name_local = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sales_local.'"  ');
	$taxMethod_sales_local='id_mst_charges_sales_local';
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['Account_Name']=$Account_Name_local;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['cramount']=$RecordsSalesReport->sub_item_amount-$RecordsSalesReport->item_discount_amount_total;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['assessable_value']=$RecordsSalesReport->sub_item_amount-$RecordsSalesReport->item_discount_amount_total;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['outlet_name']=$outlet_name;
	//Sales At Local=======================
	
	}
	
	
	
	//Addition Discount--------------------------------------
	if(($RecordsSalesReport->discount_amount_additional>0) ){
			$taxMethod_discount='discount_amount_additional';
	
	/*										
	$Account_Name = 'Discount Coupon';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->discount_amount_additional;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['outlet_name']=$outlet_name;
	*/
	}
	//Addition Discount----End----------------------------------
	
	
	//total_discount_items Discount--------------------------------------
	if(($RecordsSalesReport->total_discount_items>0) ){
			$taxMethod_discount='total_discount_items';
	
											
	//$Account_Name = 'Discount Coupon';
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_discounts.'"  ');
	if($Account_Name!=''){
		/*
		//$Account_Name='Discount Coupon';
	
	//}
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->total_discount_items;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['outlet_name']=$outlet_name;
	*/
	}
	}
	
	
	if($enable_split_bill_by_sales_account_groupLoop=='1' ){
	//SERVICE CHarge =================================
	
	
			//("Service Charges 10% :");
			
	if($RecordsSalesReport->sc_charges_net_amount>0 && $checkServiceChargeAdded!=$RecordsSalesReport->id){
			$taxMethod_sgst='sc_charges_net_amount';
	
												
	
	$id_service_charge = selectColumn(TBL_OUTLETS,'id_service_charge','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');
	$Account_Name =selectColumn(TBL_CHARGES,'name','WHERE id="'.$id_service_charge.'"  ');
	
	//'Service Charges 10% '; 
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$RecordsSalesReport->sc_charges_net_amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
		$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	
	$checkServiceChargeAdded=$RecordsSalesReport->id;
	}		
	
	if($RecordsSalesReport->sc_sgst){
			$taxMethod_sgst='sc_sgst';
	
												
	
	$Account_Name = 'Output SGST @ 2.5%';
	//	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=round($RecordsSalesReport->sc_sgst,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->sc_cgst>0){
			$taxMethod_cgst='sc_cgst';
	
											
	$Account_Name = 'Output CGST @ 2.5%';
		//$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=round($RecordsSalesReport->sc_cgst,2);
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']=$outlet_name;
	
	
	
	}
	
	//SERVICE CHarge =================================
	
	
	if($RecordsSalesReport->id_mst_charges_sgst>0){
			$taxMethod_sgst='id_mst_charges_sgst';
	
												
	
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=round($RecordsSalesReport->sub_sgst_amount,2);
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_cgst>0){
			$taxMethod_cgst='id_mst_charges_cgst';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=$RecordsSalesReport->sub_cgst_amount;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
		$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']=$outlet_name;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_igst>0){  //igst===============
			$taxMethod_igst='id_mst_charges_igst';
	
											
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['id_mst_charges_igst']=$RecordsSalesReport->id_mst_charges_igst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['outlet_name']=$outlet_name;
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_cess>0){ //cess===========================
			$taxMethod_cess='id_mst_charges_cess';
	


											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cess.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['id_mst_charges_cess']=$RecordsSalesReport->id_mst_charges_cess;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['outlet_name']=$outlet_name;
	}
	
	

	if($RecordsSalesReport->id_mst_charges_vat>0){ //Vat==================================
			$taxMethod_vat='id_mst_charges_vat';
	
				$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_vat.'"  ');	
				
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['voucher_type']=$voucher_type;			
										
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['cramount']=$RecordsSalesReport->sub_vat_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['outlet_name']=$outlet_name;
	
	
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_surcharge>0){ //_surcharge==========================
			$taxMethod_surcharge='id_mst_charges_surcharge';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_surcharge.'"  ');
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['id_mst_charges_surcharge']=$RecordsSalesReport->id_mst_charges_surcharge;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['cramount']=$RecordsSalesReport->sub_surcharge_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['outlet_name']=$outlet_name;
	
	if($enable_split_bill_by_sales_account_group==1){
			
				$enable_split_bill_by_sales_account_groupLoop=2;
			}
	}
	/*$RecordsSalesReport->sub_total_items+$RecordsSalesReport->sgst_total_items+$RecordsSalesReport->cgst_total_items+
	$RecordsSalesReport->igst_total_items+$RecordsSalesReport->sc_charges_net_amount+$RecordsSalesReport->sc_igst+$RecordsSalesReport->sc_sgst+
	$RecordsSalesReport->sc_cgst+$RecordsSalesReport->vat_total_items+$RecordsSalesReport->surcharge_total_items+$RecordsSalesReport->cess_total_items*/
	
	
	$varNet_itemss += ($RecordsSalesReport->sub_item_amount+$RecordsSalesReport->sub_sgst_amount+$RecordsSalesReport->sub_cgst_amount+
	$RecordsSalesReport->sub_igst_amount+$RecordsSalesReport->sc_charges_net_amount+$RecordsSalesReport->sc_igst+$RecordsSalesReport->sc_sgst+
	$RecordsSalesReport->sc_cgst+$RecordsSalesReport->sub_vat_amount+$RecordsSalesReport->sub_surcharge_amount+$RecordsSalesReport->cess_total_items
	-$RecordsSalesReport->item_discount_amount_total);
	
	$grant_total_amount_pos	+=$RecordsSalesReport->grant_total_amount;

}

}


// FOOD END  =========================================================================>
	
//	echo '<br>=============>'. 'Round Off'.'Total Amount==>'.$total_dramount.'=========';			
	 $varNet_items	=	$varNet_itemss+$charges_Charges_Round_Off+$charges_sgst_Round_Off+$charges_cgst_Round_Off+$tariff_Round_Off+$tariff_sgst_Round_Off +$tariff_cgst_Round_Off;
	//echo '<br>===========================>charges: '.$tariff_Round_Off.'====>'.$tariff_cgst_Round_Off.'====>'.$tariff_sgst_Round_Off;
	//echo '<br>===========================>'.$matchedMdocNo.'====>'.$total_dramount.'====>'.$varNet_items;
	//$round_Off	=	round((round($total_dramount,0)-$varNet_items),2);
	//$RecordsSalesReport->grant_total_amount-$RecordsSalesReport->net_amount_items;
	
	
	$difference = $total_dramount - $varNet_items;

echo '======round_Off==>'.$round_Off= number_format($difference, 2);
	
	if($round_Off>0 && $round_Off!=0.00){
			$taxMethod_round_off='round_off_amount';
	
					
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['cramount']=$round_Off;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['outlet_name']=$outlet_name;
	}
	
	if($round_Off<0 && $round_Off!=0.00 ){
			$taxMethod_round_off='round_off_amount';
	
							
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['dramount']= -$round_Off;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['outlet_name']=$outlet_name;
	
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
 $SQLSalesReport1="select p.id ,p.id_attribute_shift ,p.id_doc_type_configuration,p.id_fo_bill,
sum(ppd.item_sgst_amount) as sub_sgst_amount,
sum(ppd.item_cgst_amount) as sub_cgst_amount,
sum(ppd.item_vat_amount) as sub_vat_amount,
sum(ppd.item_surcharge_amount) as sub_surcharge_amount,
sum(ppd.item_discount_amount) as item_discount_amount_total,


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
INNER JOIN mst_users usr on usr.id=p.id_mst_user_created_by WHERE p.id!=0 and cancelled!=1 AND  p.id IN (".$bill_id.") $SqlConn112  group by ppd.id_mst_charges_sales_local,p.id_fo_bill";

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
	
	if($RecordsSalesReport->id_mst_charges_sgst>0  || $RecordsSalesReport->id_mst_charges_vat>0){
			
	
	$Account_Name_local = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sales_local.'"  ');
	$taxMethod_sales_local='id_mst_charges_sales_local';
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['Account_Name']=$Account_Name_local;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['cramount']=$RecordsSalesReport->sub_item_amount-$RecordsSalesReport->item_discount_amount_total;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['assessable_value']=$RecordsSalesReport->sub_item_amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['outlet_name']=$outlet_name;
	//Sales At Local=======================
	
	}
	
	
	
	//Addition Discount--------------------------------------
	if(($RecordsSalesReport->discount_amount_additional>0) ){
		/*	$taxMethod_discount='discount_amount_additional';
	
											
	$Account_Name = 'Discount Coupon';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->discount_amount_additional;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['outlet_name']=$outlet_name;*/
	}
	//Addition Discount----End----------------------------------
	
	
	//total_discount_items Discount--------------------------------------
	if(($RecordsSalesReport->total_discount_items>0) ){
			/*$taxMethod_discount='total_discount_items';
	
											
	//$Account_Name = 'Discount Coupon';
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_discounts.'"  ');
	if($Account_Name==''){$Account_Name='Discount Coupon';}
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->total_discount_items;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_discount][$taxMethod_discount]['outlet_name']=$outlet_name;
	*/
	}
	
	
	if($enable_split_bill_by_sales_account_groupLoop=='1' ){
	//SERVICE CHarge =================================
	
	
			//("Service Charges 10% :");
			
	if($RecordsSalesReport->sc_charges_net_amount>0 && $checkServiceChargeAdded!=$RecordsSalesReport->id){
			$taxMethod_sgst='sc_charges_net_amount';
	
												
	
	$id_service_charge = selectColumn(TBL_OUTLETS,'id_service_charge','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');
	$Account_Name =selectColumn(TBL_CHARGES,'name','WHERE id="'.$id_service_charge.'"  ');
	
	//'Service Charges 10% '; 
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$RecordsSalesReport->sc_charges_net_amount;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
		$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	
	$checkServiceChargeAdded=$RecordsSalesReport->id;
	}		
	
	if($RecordsSalesReport->sc_sgst){
			$taxMethod_sgst='sc_sgst';
	
												
	
	$Account_Name = 'Output SGST @ 2.5%';
	//	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=round($RecordsSalesReport->sc_sgst,2);
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->sc_cgst>0){
			$taxMethod_cgst='sc_cgst';
	
											
	$Account_Name = 'Output CGST @ 2.5%';
		//$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=round($RecordsSalesReport->sc_cgst,2);
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']=$outlet_name;
	
	
	
	}
	
	//SERVICE CHarge =================================
	
	
	if($RecordsSalesReport->id_mst_charges_sgst>0){
			$taxMethod_sgst='id_mst_charges_sgst';
	
												
	
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=round($RecordsSalesReport->sub_sgst_amount,2);
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['outlet_name']=$outlet_name;
	
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_cgst>0){
			$taxMethod_cgst='id_mst_charges_cgst';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=$RecordsSalesReport->sub_cgst_amount;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['vchDate']=$vchDate;
		$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['outlet_name']=$outlet_name;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_igst>0){  //igst===============
			$taxMethod_igst='id_mst_charges_igst';
	
											
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['id_mst_charges_igst']=$RecordsSalesReport->id_mst_charges_igst;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['outlet_name']=$outlet_name;
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_cess>0){ //cess===========================
			$taxMethod_cess='id_mst_charges_cess';
	


											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cess.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['id_mst_charges_cess']=$RecordsSalesReport->id_mst_charges_cess;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['outlet_name']=$outlet_name;
	}
	
	

	if($RecordsSalesReport->id_mst_charges_vat>0){ //Vat==================================
			$taxMethod_vat='id_mst_charges_vat';
	
				$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_vat.'"  ');	
				
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['voucher_type']=$voucher_type;			
										
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['cramount']=$RecordsSalesReport->sub_vat_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['outlet_name']=$outlet_name;
	
	
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_surcharge>0){ //_surcharge==========================
			$taxMethod_surcharge='id_mst_charges_surcharge';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_surcharge.'"  ');
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['id_mst_charges_surcharge']=$RecordsSalesReport->id_mst_charges_surcharge;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['cramount']=$RecordsSalesReport->sub_surcharge_amount;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['outlet_name']=$outlet_name;
	
	if($enable_split_bill_by_sales_account_group==1){
			
				$enable_split_bill_by_sales_account_groupLoop=2;
			}
	}
	/*$RecordsSalesReport->sub_total_items+$RecordsSalesReport->sgst_total_items+$RecordsSalesReport->cgst_total_items+
	$RecordsSalesReport->igst_total_items+$RecordsSalesReport->sc_charges_net_amount+$RecordsSalesReport->sc_igst+$RecordsSalesReport->sc_sgst+
	$RecordsSalesReport->sc_cgst+$RecordsSalesReport->vat_total_items+$RecordsSalesReport->surcharge_total_items+$RecordsSalesReport->cess_total_items*/
	
	$discounts = $RecordsSalesReport->total_discount_items;
	$varNet_itemss += ($RecordsSalesReport->sub_item_amount+$RecordsSalesReport->sub_sgst_amount+$RecordsSalesReport->sub_cgst_amount+
	$RecordsSalesReport->sub_igst_amount+$RecordsSalesReport->sc_charges_net_amount+$RecordsSalesReport->sc_igst+$RecordsSalesReport->sc_sgst+
	$RecordsSalesReport->sc_cgst+$RecordsSalesReport->sub_vat_amount+$RecordsSalesReport->sub_surcharge_amount+$RecordsSalesReport->cess_total_items-$discounts);
	
	$grant_total_amount_pos	+=$RecordsSalesReport->grant_total_amount;

}

}



// FOOD END  =========================================================================>
	
//	echo '<br>=============>'. 'Round Off'.'Total Amount==>'.$total_dramount.'=========';			
	 $varNet_items	=	$varNet_itemss+$charges_Charges_Round_Off+$charges_sgst_Round_Off+$charges_cgst_Round_Off+$tariff_Round_Off+$tariff_sgst_Round_Off +$tariff_cgst_Round_Off;
	//echo '<br>===========================>charges: '.$tariff_Round_Off.'====>'.$tariff_cgst_Round_Off.'====>'.$tariff_sgst_Round_Off;
	//echo '<br>===========================>'.$matchedMdocNo.'====>'.$total_dramount.'====>'.$varNet_items;
	//$round_Off	=	round((round($total_dramount,0)-$varNet_items),2);
	//$RecordsSalesReport->grant_total_amount-$RecordsSalesReport->net_amount_items;
	
	//-$discounts
	$difference = ($total_dramount) - $varNet_items;

echo '======round_Off==>'.$round_Off= number_format($difference, 2);
	
	if($round_Off>0 && $round_Off!=0.00){
			$taxMethod_round_off='round_off_amount';
	
					
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['cramount']=$round_Off;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['outlet_name']=$outlet_name;
	}
	
	if($round_Off<0 && $round_Off!=0.00 ){
			$taxMethod_round_off='round_off_amount';
	
							
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$date_created;//$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$matchedMdocNo;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['dramount']= -$round_Off;
	
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchref']=$vchref;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['vchDate']=$vchDate;
	$SalesRegisterArray['Sales Register'][$id_fo_folio_to][$matchedMdocNo][$taxMethod_round_off][$taxMethod_round_off]['outlet_name']=$outlet_name;
	
	}
	
	
	
	
	
	
}

//debugdata($SalesRegisterArray);die;
//debugdata($SalesRegisterArray);

//die;
foreach($SalesRegisterArray as $SalesRegisterArraylist=> $SalesRegisterArray1){
	
	
	foreach($SalesRegisterArray1 as $SalesRegisterArraylist1=> $SalesRegisterArray2){
		
		
		foreach($SalesRegisterArray2 as $SalesRegisterArraylist2=> $SalesRegisterArray3){
			//echo '==================='.$VoucherNo=$SalesRegisterArraylist2;
			//debugdata($SalesRegisterArray2);
			
			foreach($SalesRegisterArray3 as $SalesRegisterArraylist3=> $SalesRegisterArray4){
				//debugdata($SalesRegisterArray4);
				foreach($SalesRegisterArray4 as $SalesRegisterArraylist4=> $SalesRegisterArray5){
					
					
					if($SalesRegisterArray5['dramount']>0 || $SalesRegisterArray5['cramount']>0){   //Amount Debit or Credit >0
					$InvDate=date('d-m-Y',strtotime($SalesRegisterArray5['date_created']));
					
					
					
					//PHPExcel_Style_NumberFormat::toFormattedString(date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])), 'dd-mmm-yyyy');
					
			//$InvDate = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($SalesRegisterArray5['date_created']));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Q'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Sales')//$SalesRegisterArray5['voucher_type']
				->setCellValue('B'.$con, $SalesRegisterArray5['mdoc_no']);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))); //trim(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					/*->setCellValueByColumnAndRow(2, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(2, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);*/	
				//->setCellValue('C'.$con, $InvDate)//trim(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
						
			

// Narration + Reference

			
						
			$remarks = $SalesRegisterArray5['narration'] . $SalesRegisterArray5['reference'];

if (!empty(trim($SalesRegisterArray5['guest_name']))) {

    $remarks .= '; ' . $SalesRegisterArray5['guest_name'];

    if (!empty($SalesRegisterArray5['checkin']) && !empty($SalesRegisterArray5['checkout'])) {
        $remarks .= '; ' .
            date('d-m-Y', strtotime($SalesRegisterArray5['checkin'])) .
            ' To ' .
            date('d-m-Y', strtotime($SalesRegisterArray5['checkout']));
    }
}

$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('D'.$con, $SalesRegisterArray5['Account_Name'])
    ->setCellValue('E'.$con, $remarks)
    ->setCellValue('F'.$con, $SalesRegisterArray5['dramount'] > 0 ? $SalesRegisterArray5['dramount'] : 0)
    ->setCellValue('G'.$con, $SalesRegisterArray5['cramount'] > 0 ? $SalesRegisterArray5['cramount'] : 0)
    ->setCellValue('H'.$con, $SalesRegisterArray5['ptype'])
    ->setCellValue('I'.$con, $SalesRegisterArray5['vchref']);			
						
			/*$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$con, $SalesRegisterArray5['Account_Name'])
				->setCellValue('E'.$con, $SalesRegisterArray5['narration'].$SalesRegisterArray5['reference'].'; '.$SalesRegisterArray5['guest_name'].'; '.date('d-m-Y',strtotime($SalesRegisterArray5['checkin'])).' To '.date('d-m-Y',strtotime($SalesRegisterArray5['checkout'])))
				->setCellValue('F'.$con, $SalesRegisterArray5['dramount']>0?$SalesRegisterArray5['dramount']:0)

				->setCellValue('G'.$con, $SalesRegisterArray5['cramount']>0?$SalesRegisterArray5['cramount']:0)
				->setCellValue('H'.$con, $SalesRegisterArray5['ptype'])
				->setCellValue('I'.$con, $SalesRegisterArray5['vchref']);*/
				
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['vchDate'])));
					/*->setCellValueByColumnAndRow(9, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(9, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);*/
				//->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])))
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$con, $SalesRegisterArray5['assessable_value']>0?$SalesRegisterArray5['assessable_value']:0)
				->setCellValue('L'.$con, '0')
				->setCellValue('M'.$con, $SalesRegisterArray5['gst'])
				->setCellValue('N'.$con, $SalesRegisterArray5['state'])
				->setCellValue('O'.$con, $SalesRegisterArray5['city'])
				->setCellValue('P'.$con, $SalesRegisterArray5['gst_type'])
				->setCellValue('Q'.$con, $SalesRegisterArray5['outlet_name']);
					$con++;
			
			//debugdata($SalesRegisterArray5);
					}
				}
				
			}
			
		}
		
	}
	
}//die;
$objPHPExcel->getActiveSheet($sheetIndexFive)->getColumnDimension('D')->setWidth(35);
		
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



	$filename=	'SalesReport'.date('d-M-Y').'.xls';
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