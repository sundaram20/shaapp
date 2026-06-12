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

function nightAuditReports($Date,$id_report_type,$report_show,$showItemReport,$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel){
	
	
	//global $connNew;
	///global $objPHPExcel;
	
	
	
		
	if($Date != ''){
		$DateExplode = $Date;
		$startDate = date('Y-m-d',strtotime($DateExplode));
		$endDate	=	date('Y-m-d',strtotime($DateExplode));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConn .= " AND `date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
	if($Date != ''){
		$DateExplode = $Date;
		$startDate = date('Y-m-d',strtotime($DateExplode));
		$endDate	=	date('Y-m-d',strtotime($DateExplode));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		
		
$setFormat = date_create($startDate);
$current_date = $setFormat->format('Y-m-d');
$last_year_current_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));

//MTD
$from = date_create($current_date);
$from_month_to_date = date_create($from->format('Y-m-01'));
$from_month_to_date = $from_month_to_date->format('Y-m-d');
$to_month_to_date = $current_date;
$last_year_to_month_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_month_date);

$last_year_from_month_date = date_create($from->format('Y-m-01'));
$last_year_from_month_date = $last_year_from_month_date->format('Y-m-d');

//YTD
$to_year_to_date = $current_date;
$from = date_create($current_date);

if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
	$from_year_to_date = date('Y-m-d',strtotime('-1 year',strtotime($from_year_to_date)));
}
else{
	$from_year_to_date = date_create($from->format('Y-04-01'));
	$from_year_to_date = $from_year_to_date->format('Y-m-d');
}

$last_year_to_year_date = date('Y-m-d',strtotime('-1 year',strtotime($current_date)));
$from = date_create($last_year_to_year_date);
$last_year_from_year_date = date_create($from->format('Y-04-01'));
if(date('m',strtotime($current_date)) == '01' || date('m',strtotime($current_date)) == '02' || date('m',strtotime($current_date)) == '03' ){
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
    $last_year_from_year_date = date('Y-m-d',strtotime('-1 year',strtotime($last_year_from_year_date)));
  }
  else{
    $last_year_from_year_date = $last_year_from_year_date->format('Y-m-d');
  }
  $current_quarter = ceil(date('n') / 3);
$QuarterThisYearstart_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
$QuarterThisYearlast_date = date('Y-m-t', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));

$Quarterlast_yearstart_date = date('Y-m-d',strtotime('-1 year',strtotime($QuarterThisYearstart_date)));
$Quarterlast_yeartart_date = date('Y-m-d',strtotime('-1 year',strtotime($QuarterThisYearlast_date)));

if($_REQUEST['viewMonthwise']=='1'){
    
    $QuarterThisYearlast_date=$to_year_to_date;
    $Quarterlast_yeartart_date=$last_year_to_year_date;
}

$yesterdaysDate	=	 date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
$last_year_yesterdaysDate = date('Y-m-d',strtotime('-1 year',strtotime($yesterdaysDate)));
	
		
		
		
		


		
		
		
		
		
		
			
		$SqlReservationConn .= " AND `dated` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}

	$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".$_SESSION['shop']."'");
	$rowShop = mysqli_fetch_object($resShop);
	$logo	=	$rowShop->image;
	

	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");


 
//echo '======================================================4';
//$objDrawing = new PHPExcel_Worksheet_Drawing();
	/*$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../uploaded_files/shop/'.$logo);
	$objDrawing->setCoordinates('L1');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
//echo '======================================================1';//die;
$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A7', "Daily Sales Summary Report As On ".date('d-m-Y',strtotime($startDate)));
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:J7');
	

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
 $objPHPExcel->getActiveSheet()->getStyle('A7:J7')->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A7:J7')->applyFromArray($styleArray);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
$con=$setcellcount;


	
//$objPHPExcel->setActiveSheetIndex(0)
//->setCellValue('A'.$con,'As On '.date('d-m-Y',strtotime($startDate)));



$ConnSQL	= "sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` = '".$current_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tariff_price_per_day_per_room,0) else 0 end) as `tariff_Today`,";


$ConnSQL	.= "sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` = '".$current_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tax_per_day_per_room,0) else 0 end) as `tax_Today`,";

$ConnSQL	.= "sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_month_to_date."' and '".$to_month_to_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tariff_price_per_day_per_room,0) else 0 end) as `tariff_MTD`,";


$ConnSQL	.= "sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_month_to_date."' and '".$to_month_to_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tax_per_day_per_room,0) else 0 end) as `tax_MTD`,";


$ConnSQL	.="sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_year_to_date."' and '".$to_year_to_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tax_per_day_per_room,0) else 0 end) as `tax_YTD`,";

$ConnSQL	.="sum(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_year_to_date."' and '".$to_year_to_date."') 
then ROUND(`".FO_RESERVATIONS_DETAILS."`.tariff_price_per_day_per_room,0) else 0 end) as `tariff_YTD`";



   $sqlOrderDetail = "Select  $ConnSQL from `".FO_RESERVATIONS_DETAILS."` where checkin_status='1' and id_fo_folio_to>0  ";
		

//die;

$queryReceipt = mysqli_query($connNew,$sqlOrderDetail);
$TotalNumberOfRowReceipts = mysqli_num_rows($queryReceipt);

$InCount=1;
$count2=1;
$TotalBillCount=0;
$nightAudit=array();
////while($RecordsReceipt	   =	mysqli_fetch_object($queryReceipt)){
	
	$RecordsReceipt	   =	mysqli_fetch_object($queryReceipt);
				$outlet_Name ='Room Tariff';
				$id_mst_outlet='000121';
				
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += $RecordsReceipt->tariff_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $RecordsReceipt->tariff_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $RecordsReceipt->tariff_YTD;
						
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += $RecordsReceipt->tariff_Today+$RecordsReceipt->tax_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $RecordsReceipt->tariff_MTD+$RecordsReceipt->tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $RecordsReceipt->tariff_YTD+$RecordsReceipt->tax_YTD;
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $RecordsReceipt->tax_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $RecordsReceipt->tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $RecordsReceipt->tax_YTD;
						
				
				
	//}
	//END TARIFF====================================================
	//debugData($nightAudit);
	//die;
	//PAYMENT =====================================================================
	if($date != ''){
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConnPayment .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
		//$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		$SqlConn2 .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConnPayment .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConnPayment .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
	 $SQLSalesReportPayment="select p.id ,

case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'CASH' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then 'CARD'
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'CHEQUE'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then 'UPI'
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then 'ONLINETRANSFER'
     when payment_mode='COMPANY' and IFNULL(amount,0)>0 then 'COMPANY'
	 when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then 'ROOMTO'
	 when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then 'BIllONHOLD'
    else 0 end
    as payment_type ,
    
    case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'Cash Sales' 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then 'Bill On Hold'
	when payment_mode='CARD' and IFNULL(amount,0)>0 then id_charges_master
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'Cash Sales'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then remark
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then id_charges_master  	  
    else 0 end
    as payment_remarks ,
	
	case when folio_status='1'  then 'Close' 
	when folio_status='0' then 'Open'
   	  
    else 0 end
    as folio_status ,
	
	
	case when payment_mode='COMPANY' and IFNULL(amount,0)>0 then id_company
    else 0 end
    as id_company ,
	pp.id_fo_bill ,
	pp.id_fo_folio ,
	
	
	case when payment_mode='CASH' and IFNULL(amount,0)>0 then amount 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then amount
	when payment_mode='CARD' and IFNULL(amount,0)>0 then amount
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then amount
    when payment_mode='UPI' and IFNULL(amount,0)>0 then amount
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then amount
    when payment_mode='COMPANY' and IFNULL(amount,0)>0 then amount
	when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then amount
    else 0 end
    as dramount ,


case when payment_mode='CASH' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CASH_Today ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_Today ,
case when payment_mode='CARD' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as CARD_Today,
case when payment_mode='CHEQUE' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CHEQUE_Today ,
case when payment_mode='UPI' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as UPI_Today ,
case when payment_mode='ONLINETRANSFER'  and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_Today ,
case when payment_mode='COMPANY' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as COMPANY_Today ,
case when payment_mode='ROOMTO' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ROOMTO_Today ,





case when payment_mode='CASH' and (pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CASH_MTD ,
case when payment_mode='BIllONHOLD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as BIllONHOLD_MTD ,
case when payment_mode='CARD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CARD_MTD,
case when payment_mode='CHEQUE' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as CHEQUE_MTD ,
case when payment_mode='UPI' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as UPI_MTD ,
case when payment_mode='ONLINETRANSFER'  and  ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ONLINETRANSFER_MTD ,
case when payment_mode='COMPANY' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as COMPANY_MTD ,
case when payment_mode='ROOMTO' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ROOMTO_MTD ,

case when payment_mode='CASH' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')   then IFNULL(amount,0) else null end as CASH_YTD ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_YTD ,
case when payment_mode='CARD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as CARD_YTD,
case when payment_mode='CHEQUE' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as CHEQUE_YTD ,
case when payment_mode='UPI' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as UPI_YTD ,
case when payment_mode='ONLINETRANSFER'  and  (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_YTD ,
case when payment_mode='COMPANY' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as COMPANY_YTD ,
case when payment_mode='ROOMTO' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ROOMTO_YTD ,

DATE(p.date_created) as date_created 
 from fo_receipt pp 
INNER JOIN 	fo_folio p on p.id = pp.id_fo_folio
 
WHERE p.id!=0 and pp.amount>0  $SqlConnPayment ORDER BY  pp.id_fo_folio ASC";
//echo '=================>'.$SQLSalesReportPayment;

$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);

$ics=1;
while($RecordsSalesReportPayment	   =	mysqli_fetch_object($querySalesReportPayment)){
	
	
	$RecordsSalesReportPayment->id_fo_folio='POS';
	

	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CASH']['outlet_Name']="CASH";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['BIllONHOLD']['outlet_Name']="BIllONHOLD";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CARD']['outlet_Name']="CARD";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CHEQUE']['outlet_Name']="CHEQUE";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['UPI']['outlet_Name']="UPI";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ONLINETRANSFER']['outlet_Name']="ONLINETRANSFER";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['COMPANY']['outlet_Name']="COMPANY";
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ROOMTO']['outlet_Name']="ROOMTO";
	
	
	
	
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CASH']['Today']+=$RecordsSalesReportPayment->CASH_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['BIllONHOLD']['Today']+=$RecordsSalesReportPayment->BIllONHOLD_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CARD']['Today']+=$RecordsSalesReportPayment->CARD_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CHEQUE']['Today']+=$RecordsSalesReportPayment->CHEQUE_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['UPI']['Today']+=$RecordsSalesReportPayment->UPI_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ONLINETRANSFER']['Today']+=$RecordsSalesReportPayment->ONLINETRANSFER_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['COMPANY']['Today']+=$RecordsSalesReportPayment->COMPANY_Today;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ROOMTO']['Today']+=$RecordsSalesReportPayment->ROOMTO_Today;
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CASH']['MTD']+=$RecordsSalesReportPayment->CASH_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['BIllONHOLD']['MTD']+=$RecordsSalesReportPayment->BIllONHOLD_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CARD']['MTD']+=$RecordsSalesReportPayment->CARD_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CHEQUE']['MTD']+=$RecordsSalesReportPayment->CHEQUE_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['UPI']['MTD']+=$RecordsSalesReportPayment->UPI_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ONLINETRANSFER']['MTD']+=$RecordsSalesReportPayment->ONLINETRANSFER_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['COMPANY']['MTD']+=$RecordsSalesReportPayment->COMPANY_MTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ROOMTO']['MTD']+=$RecordsSalesReportPayment->ROOMTO_MTD;
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CASH']['YTD']+=$RecordsSalesReportPayment->CASH_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['BIllONHOLD']['YTD']+=$RecordsSalesReportPayment->BIllONHOLD_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CARD']['YTD']+=$RecordsSalesReportPayment->CARD_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['CHEQUE']['YTD']+=$RecordsSalesReportPayment->CHEQUE_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['UPI']['YTD']+=$RecordsSalesReportPayment->UPI_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ONLINETRANSFER']['YTD']+=$RecordsSalesReportPayment->ONLINETRANSFER_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['COMPANY']['YTD']+=$RecordsSalesReportPayment->COMPANY_YTD;
	$SalesRegisterArray[$RecordsSalesReportPayment->id_fo_folio]['ROOMTO']['YTD']+=$RecordsSalesReportPayment->ROOMTO_YTD;
	
	
	
	}
 
	

	

//debugdata($SalesRegisterArray);die;
//PAYMENT END Tariff==================================================================>
	
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$POSSqlConn .= " AND `date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$POSSqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$POSSqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
	$SQL="select
      p.id_mst_outlet,pp.id_purch,
	
case when payment_mode='CASH' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CASH_Today ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_Today ,
case when payment_mode='CARD' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as CARD_Today,
case when payment_mode='CHEQUE' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CHEQUE_Today ,
case when payment_mode='UPI' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as UPI_Today ,
case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_Today ,
case when payment_mode='COMPANY' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as COMPANY_Today ,
case when payment_mode='ROOMTO' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ROOMTO_Today ,





case when payment_mode='CASH' and (pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CASH_MTD ,
case when payment_mode='BIllONHOLD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as BIllONHOLD_MTD ,
case when payment_mode='CARD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CARD_MTD,
case when payment_mode='CHEQUE' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as CHEQUE_MTD ,
case when payment_mode='UPI' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as UPI_MTD ,
case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ONLINETRANSFER_MTD ,
case when payment_mode='COMPANY' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as COMPANY_MTD ,
case when payment_mode='ROOMTO' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ROOMTO_MTD ,

case when payment_mode='CASH' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')   then IFNULL(amount,0) else null end as CASH_YTD ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_YTD ,
case when payment_mode='CARD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as CARD_YTD,
case when payment_mode='CHEQUE' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as CHEQUE_YTD ,
case when payment_mode='UPI' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as UPI_YTD ,
case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_YTD ,
case when payment_mode='COMPANY' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as COMPANY_YTD ,
case when payment_mode='ROOMTO' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ROOMTO_YTD, 


case when payment_mode='CASH' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CASH_Today_SUM ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_Today_SUM ,
case when payment_mode='CARD' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as CARD_Today_SUM,
case when payment_mode='CHEQUE' and (pp.`doc_date` = '".$current_date."')  then IFNULL(amount,0) else null end as CHEQUE_Today_SUM ,
case when payment_mode='UPI' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as UPI_Today_SUM ,
case when payment_mode='ONLINETRANSFER' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_Today_SUM ,
case when payment_mode='COMPANY' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as COMPANY_Today_SUM ,
case when payment_mode='ROOMTO' and (pp.`doc_date` = '".$current_date."') then IFNULL(amount,0) else null end as ROOMTO_Today_SUM ,





case when payment_mode='CASH' and (pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CASH_MTD_SUM ,
case when payment_mode='BIllONHOLD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as BIllONHOLD_MTD_SUM ,
case when payment_mode='CARD' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as CARD_MTD_SUM,
case when payment_mode='CHEQUE' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')   then IFNULL(amount,0) else null end as CHEQUE_MTD_SUM ,
case when payment_mode='UPI' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as UPI_MTD_SUM ,
case when payment_mode='ONLINETRANSFER'and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ONLINETRANSFER_MTD_SUM ,
case when payment_mode='COMPANY' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as COMPANY_MTD_SUM ,
case when payment_mode='ROOMTO' and ( pp.`doc_date` between '".$from_month_to_date."' and '".$to_month_to_date."')  then IFNULL(amount,0) else null end as ROOMTO_MTD_SUM ,

case when payment_mode='CASH' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')   then IFNULL(amount,0) else null end as CASH_YTD_SUM ,
case when payment_mode='BIllONHOLD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as BIllONHOLD_YTD_SUM ,
case when payment_mode='CARD' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as CARD_YTD_SUM,
case when payment_mode='CHEQUE' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."')  then IFNULL(amount,0) else null end as CHEQUE_YTD_SUM ,
case when payment_mode='UPI' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as UPI_YTD_SUM ,
case when payment_mode='ONLINETRANSFER' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ONLINETRANSFER_YTD_SUM ,
case when payment_mode='COMPANY' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as COMPANY_YTD_SUM ,
case when payment_mode='ROOMTO' and (pp.`doc_date` between '".$from_year_to_date."' and '".$to_year_to_date."') then IFNULL(amount,0) else null end as ROOMTO_YTD_SUM 

    
	from pos_purch_pay pp
	INNER JOIN
	pos_purch p
	on
	p.id = pp.id_purch
		
	    

WHERE pp.id!=0 and p.cancelled!='1'
";


//echo $SQL;die;
$query = mysqli_query($connNew,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;

$count2=1;
$TotalBillCount=0;
//$nightAudit=array();
while($Records	   =	mysqli_fetch_object($query)){
	
	
	
	
	
  
	
	$Tax_Today	=	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE `id` = '".$Records->id_purch."' and (DATE(`doc_date`) = '".$current_date."')");
	$Tax_MTD	  =	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE `id` = '".$Records->id_purch."' and ( DATE(`doc_date`) between '".$from_month_to_date."' and '".$to_month_to_date."') ");
	$Tax_YTD	  =	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE `id` = '".$Records->id_purch."' 	and (DATE(`doc_date`) between '".$from_year_to_date."' and '".$to_year_to_date."')");
	
				$outlet_Name	=	selectColumn('mst_outlets','name'," WHERE `id` = '".$Records->id_mst_outlet."'");
	//$outlet_Name =$Records->outlet_Name;
				$id_mst_outlet=$Records->id_mst_outlet;
				//,comp.name as 'outlet_Name'
				
				$Today = ($Records->CASH_Today_SUM+$Records->BIllONHOLD_Today_SUM+$Records->CARD_Today_SUM+$Records->CHEQUE_Today_SUM+$Records->UPI_Today_SUM+$Records->ONLINETRANSFER_Today_SUM+$Records->COMPANY_Today_SUM+$Records->ROOMTO_Today_SUM);      
				$MTD = ($Records->CASH_MTD_SUM+$Records->BIllONHOLD_MTD_SUM+$Records->CARD_MTD_SUM+$Records->CHEQUE_MTD_SUM+$Records->UPI_MTD_SUM+$Records->ONLINETRANSFER_MTD_SUM+$Records->COMPANY_MTD_SUM+$Records->ROOMTO_MTD_SUM);      
				$YTD = ($Records->CASH_YTD_SUM+$Records->BIllONHOLD_YTD_SUM+$Records->CARD_YTD_SUM+$Records->CHEQUE_YTD_SUM+$Records->UPI_YTD_SUM+$Records->ONLINETRANSFER_YTD_SUM+$Records->COMPANY_YTD_SUM+$Records->ROOMTO_YTD_SUM);      
				
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']=$outlet_Name;;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += ($Today-$Tax_Today);
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $MTD-$Tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $YTD-$Tax_YTD;
						
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $Tax_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $Tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $Tax_YTD;
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += ($Today);
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $YTD;
						
						
				$Records->id_fo_folio	='Front Office';	
	$SalesRegisterArray[$Records->id_fo_folio]['CASH']['outlet_Name']="CASH";
	$SalesRegisterArray[$Records->id_fo_folio]['BIllONHOLD']['outlet_Name']="BIllONHOLD";
	$SalesRegisterArray[$Records->id_fo_folio]['CARD']['outlet_Name']="CARD";
	$SalesRegisterArray[$Records->id_fo_folio]['CHEQUE']['outlet_Name']="CHEQUE";
	$SalesRegisterArray[$Records->id_fo_folio]['UPI']['outlet_Name']="UPI";
	$SalesRegisterArray[$Records->id_fo_folio]['ONLINETRANSFER']['outlet_Name']="ONLINETRANSFER";
	$SalesRegisterArray[$Records->id_fo_folio]['COMPANY']['outlet_Name']="COMPANY";
	//$SalesRegisterArray[$Records->id_fo_folio]['ROOMTO']['outlet_Name']="ROOMTO";
	
	
	
	
	
	
	$SalesRegisterArray[$Records->id_fo_folio]['CASH']['Today']+=$Records->CASH_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['BIllONHOLD']['Today']+=$Records->BIllONHOLD_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['CARD']['Today']=$Records->CARD_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['CHEQUE']['Today']+=$Records->CHEQUE_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['UPI']['Today']+=$Records->UPI_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['ONLINETRANSFER']['Today']+=$Records->ONLINETRANSFER_Today;
	$SalesRegisterArray[$Records->id_fo_folio]['COMPANY']['Today']+=$Records->COMPANY_Today;
	//$SalesRegisterArray[$Records->id_fo_folio]['ROOMTO']['Today']+=$Records->ROOMTO_Today;
	
	
	$SalesRegisterArray[$Records->id_fo_folio]['CASH']['MTD']+=$Records->CASH_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['BIllONHOLD']['MTD']+=$Records->BIllONHOLD_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['CARD']['MTD']=$Records->CARD_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['CHEQUE']['MTD']+=$Records->CHEQUE_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['UPI']['MTD']+=$Records->UPI_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['ONLINETRANSFER']['MTD']+=$Records->ONLINETRANSFER_MTD;
	$SalesRegisterArray[$Records->id_fo_folio]['COMPANY']['MTD']+=$Records->COMPANY_MTD;
	//$SalesRegisterArray[$Records->id_fo_folio]['ROOMTO']['MTD']+=$Records->ROOMTO_MTD;
	
	
	$SalesRegisterArray[$Records->id_fo_folio]['CASH']['YTD']+=$Records->CASH_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['BIllONHOLD']['YTD']+=$Records->BIllONHOLD_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['CARD']['YTD']=$Records->CARD_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['CHEQUE']['YTD']+=$Records->CHEQUE_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['UPI']['YTD']+=$Records->UPI_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['ONLINETRANSFER']['YTD']+=$Records->ONLINETRANSFER_YTD;
	$SalesRegisterArray[$Records->id_fo_folio]['COMPANY']['YTD']+=$Records->COMPANY_YTD;
	//$SalesRegisterArray[$Records->id_fo_folio]['ROOMTO']['YTD']+=$Records->ROOMTO_YTD;
				
	
	}
	
	//debugData($SalesRegisterArray);die;
	
	$ConnChargesSQL='';
	
	
	$ConnChargesSQL	= "sum(case when ( `fo_reservations_addons_details`.`dated` = '".$current_date."')  and  dated!='null'
then ROUND((`fo_reservations_addons_details`.rate)*qty*days,0) else 0 end) as `tariff_Today`,";


$ConnChargesSQL	.= "sum(case when ( `fo_reservations_addons_details`.`dated` = '".$current_date."')  and  dated!='null'
then ROUND((`fo_reservations_addons_details`.tax_value)*qty*days,0) else 0 end) as `tax_Today`,";

$ConnChargesSQL	.= "sum(case when ( `fo_reservations_addons_details`.`dated` between '".$from_month_to_date."' and '".$to_month_to_date."')  and  dated!='null'
then ROUND((`fo_reservations_addons_details`.rate)*qty*days,0) else 0 end) as `tariff_MTD`,";


$ConnChargesSQL	.= "sum(case when ( `fo_reservations_addons_details`.`dated` between '".$from_month_to_date."' and '".$to_month_to_date."')  and  dated!='null'
then ROUND((`fo_reservations_addons_details`.tax_value)*qty*days,0) else 0 end) as `tax_MTD`,";


$ConnChargesSQL	.="sum(case when ( `fo_reservations_addons_details`.`dated` between '".$from_year_to_date."' and '".$to_year_to_date."')  and  dated!='null' 
then ROUND((`fo_reservations_addons_details`.tax_value)*qty*days,0) else 0 end) as `tax_YTD`,";

$ConnChargesSQL	.="sum(case when ( `fo_reservations_addons_details`.`dated` between '".$from_year_to_date."' and '".$to_year_to_date."')  and  dated!='null'
then ROUND((`fo_reservations_addons_details`.rate)*qty*days,0) else 0 end) as `tariff_YTD`";


//echo "Select  $ConnChargesSQL from `fo_reservations_addons_details` ";die;
$sqlOrderDetail = mysqli_query($connNew,"Select  $ConnChargesSQL,id_mst_charges from `fo_reservations_addons_details` where id_fo_folio_to>0 and dated!='null' group by id_mst_charges");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_CHARGES,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					$outlet_Name= selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					$id_mst_outlet= selectColumn(TBL_CHARGES,'sac_no'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					//$outlet_Name ='Post Charges';
					
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += $rowOrderDetail->tariff_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $rowOrderDetail->tariff_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $rowOrderDetail->tariff_YTD;
						
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += $rowOrderDetail->tariff_Today+$rowOrderDetail->tax_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $rowOrderDetail->tariff_MTD+$rowOrderDetail->tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $rowOrderDetail->tariff_YTD+$rowOrderDetail->tax_YTD;
						
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $rowOrderDetail->tax_Today;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $rowOrderDetail->tax_MTD;
						$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $rowOrderDetail->tax_YTD;
					
					
					
				}
				
				
		}
	
	//Front Office Bill Summary STart===========================================>
	$id_fo_folio_to_today=array();
	//echo "Select  * from ".FO_BILL." where `dated` = '".$current_date."'"
	$sqlFoBillDetail = mysqli_query($connNew,"Select  * from ".FO_BILL." where `doc_date` = '".$current_date."'");
		if(mysqli_num_rows($sqlFoBillDetail) >0 ){
			
				while($rowFoBillDetail= mysqli_fetch_object($sqlFoBillDetail)){
					
					$id_fo_bill_to	=$rowFoBillDetail->id;
					$id_fo_folio_to_today[]	     =$rowFoBillDetail->id_fo_folio_to;
				}
		}
		$id_fo_folio_to_mtd=array();
	//echo "Select  * from ".FO_BILL." where `dated` = '".$current_date."'"
	$sqlFoBillDetail = mysqli_query($connNew,"Select  * from ".FO_BILL." where (DATE(`doc_date`) between '".$from_month_to_date."' and '".$to_month_to_date."')");
		if(mysqli_num_rows($sqlFoBillDetail) >0 ){
			
				while($rowFoBillDetail= mysqli_fetch_object($sqlFoBillDetail)){
					
					$id_fo_bill_to	=$rowFoBillDetail->id;
					$id_fo_folio_to_mtd[]	     =$rowFoBillDetail->id_fo_folio_to;
				}
		}
		
		$id_fo_folio_to_ytd=array();
	//echo "Select  * from ".FO_BILL." where `dated` = '".$current_date."'"
	
	
	$sqlFoBillDetail = mysqli_query($connNew,"Select  * from ".FO_BILL." where (DATE(`doc_date`) between '".$from_year_to_date."' and '".$to_year_to_date."')");
		if(mysqli_num_rows($sqlFoBillDetail) >0 ){
			
				while($rowFoBillDetail= mysqli_fetch_object($sqlFoBillDetail)){
					
					$id_fo_bill_to	=$rowFoBillDetail->id;
					$id_fo_folio_to_ytd[]	     =$rowFoBillDetail->id_fo_folio_to;
				}
		}	
		
		//print_r($id_fo_folio_to_today);
		//print_r($id_fo_folio_to_mtd);
		//print_r($id_fo_folio_to_ytd);
		
		if(count($id_fo_folio_to_ytd)>0){
		$id_fo_folio_to_ytd=implode(',',$id_fo_folio_to_ytd);
		
		$Tax_YTD_pos	  =	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_ytd.") and cancelled != 1");
	$YTD_pos	  =	selectColumn('pos_purch','sum(grant_total_amount)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_ytd.") and cancelled != 1");
		
		$Tax_YTD_frontoffice	  =	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tax_per_day_per_room)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_ytd.") ");
	$YTD_frontoffice	  =	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room)'," WHERE   id_fo_folio_to IN (".$id_fo_folio_to_ytd.") ");
		
		
		$Tax_YTD_addons	  =	selectColumn('fo_reservations_addons_details','sum(ROUND((`fo_reservations_addons_details`.rate)*qty*days,0))'," WHERE   id_fo_folio_to IN (".$id_fo_folio_to_ytd.") ");
	$YTD_addons	  =	selectColumn('fo_reservations_addons_details','sum(ROUND((`fo_reservations_addons_details`.tax_value)*qty*days,0))'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_ytd.") ");
		}
		
		
		
		
		if(count($id_fo_folio_to_mtd)>0){
		$id_fo_folio_to_mtd=implode(',',$id_fo_folio_to_mtd);
		
		$Tax_MTD_pos	  =	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE   id_fo_folio_to IN (".$id_fo_folio_to_mtd.") and cancelled != 1");

	
	
	$MTD_pos	  =	selectColumn('pos_purch','sum(grant_total_amount)'," WHERE  id_fo_folio_to IN (".$id_fo_folio_to_mtd.") and cancelled != 1");
	
	
	$Tax_MTD_frontoffice	  =	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tax_per_day_per_room)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_mtd.") ");	
	
	$MTD_frontoffice	  =	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_mtd.") ");
	
	$Tax_MTD_addons	  =	selectColumn('fo_reservations_addons_details','sum(ROUND((`fo_reservations_addons_details`.rate)*qty*days,0))'," WHERE  id_fo_folio_to IN (".$id_fo_folio_to_mtd.") ");	
	
	$MTD_addons	  =	selectColumn('fo_reservations_addons_details','sum(ROUND((`fo_reservations_addons_details`.tax_value)*qty*days,0))'," WHERE  id_fo_folio_to IN (".$id_fo_folio_to_mtd.") ");}
		
		
		
		if(count($id_fo_folio_to_today)>0){
			
		$id_fo_folio_to_today=implode(',',$id_fo_folio_to_today);
		
		$Today_pos	=	selectColumn('pos_purch','sum(grant_total_amount)'," WHERE   id_fo_folio_to IN (".$id_fo_folio_to_today.") and cancelled != 1");	
		
	$Tax_Today_pos	=	selectColumn('pos_purch','sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_today.") and cancelled != 1");
		
		
		
		$Today_frontoffice	=	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room)'," WHERE  id_fo_folio_to IN (".$id_fo_folio_to_today.") ");	
		$Tax_Today_frontoffice	=	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tax_per_day_per_room)'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_today.")");
		
		
		$Today_addons	=	selectColumn('fo_reservations_addons_details','sum(ROUND((rate)*qty*days,0))'," WHERE  id_fo_folio_to IN (".$id_fo_folio_to_today.") ");	
		$Tax_Today_addons	=	selectColumn('fo_reservations_addons_details','sum(ROUND((tax_value)*qty*days,0))'," WHERE    id_fo_folio_to IN (".$id_fo_folio_to_today.")");
		
		
		}
		
		
		
		
					
/*					
$FrontOfficeBillSummary = array();

$reservation_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_folio_to = '".$id_fo_folio_to."'");
while ($reservation = mysqli_fetch_object($reservation_query)) {
	
	
	
	

}

$reservation_addon_query = mysqli_query($connNew, "select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_fo_folio_to."'");
while ($reservation = mysqli_fetch_object($reservation_addon_query)) {
   
}*/

		//echo 'sum(sgst_total_items+cgst_total_items+igst_total_items+cess_total_items+vat_total_items+surcharge_total_items)'," WHERE `id` = '".$Records->id_purch."' and (DATE(`doc_date`) = '".$current_date."') and  id_fo_folio_to = '".$id_fo_folio_to."' and cancelled != 1";die;
		
	
	
	
	
	//echo " WHERE  (DATE(`doc_date`) between '".$from_year_to_date."' and '".$to_year_to_date."') and  id_fo_folio_to = '".$id_fo_folio_to."' and cancelled != 1"
						
					$outlet_Name= 'Tariff';
					$id_mst_outlet= '12112024';
					//$outlet_Name ='Post Charges';
					
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += $Today_frontoffice;
												
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += $Today_frontoffice+$Tax_Today_frontoffice;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $Tax_Today_frontoffice;
				
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $MTD_frontoffice;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $YTD_frontoffice;
						
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $MTD_frontoffice+$Tax_MTD_frontoffice;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $YTD_frontoffice+$Tax_YTD_frontoffice;
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $Tax_MTD_frontoffice;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $Tax_YTD_frontoffice;
					
	
	$outlet_Name= 'POS  Charges';
					$id_mst_outlet= '121120241';
					//$outlet_Name ='Post Charges';
					
					
					$Today_pos	= $Today_pos-$Tax_Today_pos;
					$MTD_pos	=$MTD_pos-$Tax_MTD_pos;
					$YTD_pos	= $YTD_pos+$Tax_YTD_pos;
					
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += $Today_pos;
												
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += $Today_pos+$Tax_Today_pos;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $Tax_Today_pos;
				
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $MTD_pos;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $YTD_pos;
						
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $MTD_pos+$Tax_MTD_pos;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $YTD_pos+$Tax_MTD_pos;
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $Tax_MTD_pos;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $Tax_YTD_pos;
						
						
	
		$outlet_Name= 'Other Charges';
					$id_mst_outlet= '121120242';
					//$outlet_Name ='Post Charges';
					
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Today'] += $Today_addons;
												
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_Today'] += $Today_addons+$Tax_Today_addons;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_Today'] += $Tax_Today_addons;
				
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_MTD'] += $Tax_MTD_addons;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_YTD'] += $Tax_YTD_addons;
						
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_MTD'] += $MTD_addons+$Tax_MTD_addons;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tariff_Tax_YTD'] += $YTD_addons+$Tax_YTD_addons;
	
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_MTD'] += $MTD_addons;
						$nightAuditDay['NightAudit'][$outlet_Name][$id_mst_outlet]['tax_YTD'] += $YTD_addons;
	
	
	
    //Front Office Bill Summary END 

	
	
	
	
	$objPHPExcel->setActiveSheetIndex(0)
			

			->setCellValue('B'.$con, 'For the Day')
			->setCellValue('E'.$con, 'For the Month')
			->setCellValue('H'.$con, 'For the Year');
			
			
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$con.':D'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$con.':G'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$con.':J'.$con);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':D'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':G'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':J'.$con)->getFont()->setBold(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			cellColor('A'.$con,'cdecff');	
			cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');	
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':D'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':G'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':J'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
			$con++;
			
			
			
			
			
			
	
			
			
			
			
			
			
	foreach($nightAudit as $Datalist1){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Description')			
			->setCellValue('B'.$con, 'Amount')							
			
			->setCellValue('C'.$con, 'Taxes')				
			->setCellValue('D'.$con, 'Total')
			
			->setCellValue('E'.$con, 'Amount')							
			
			->setCellValue('F'.$con, 'Taxes')				
			->setCellValue('G'.$con, 'Total')
			
			
			->setCellValue('H'.$con, 'Amount')							
			
			->setCellValue('I'.$con, 'Taxes')				
			->setCellValue('J'.$con, 'Total');
												
			cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
		foreach($Datalist1 as $outlet=>$Datalist2){
			
			foreach($Datalist2 as $Datalist3){			
		

				$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A'.$con, $InCount)
				
				->setCellValue('A'.$con, ucwords(strtolower($Datalist3['outlet_Name'])))
				
				->setCellValue('B'.$con, $Datalist3['tariff_Today'])
				
				->setCellValue('C'.$con, $Datalist3['tax_Today'])
				->setCellValue('D'.$con, $Datalist3['tariff_Tax_Today']-$Datalist3['Discount'])
				
				
				->setCellValue('E'.$con, $Datalist3['tariff_MTD'])				
				->setCellValue('F'.$con, $Datalist3['tax_MTD'])
				->setCellValue('G'.$con, $Datalist3['tariff_Tax_MTD']-$Datalist3['Discount'])
				
				
				->setCellValue('H'.$con, $Datalist3['tariff_YTD'])
				
				->setCellValue('I'.$con, $Datalist3['tax_YTD'])
				->setCellValue('J'.$con, $Datalist3['tariff_Tax_YTD']-$Datalist3['Discount']);
				
				/*->setCellValue('F'.$con, $Datalist3['Taxes'])
				
				->setCellValue('G'.$con, $Datalist3['round_off_amount'])
				->setCellValue('H'.$con, $Datalist3['grant_total_amount'])
				->setCellValue('I'.$con, $Datalist3['Cash'])
				->setCellValue('J'.$con, $Datalist3['Card'])
				->setCellValue('K'.$con, $Datalist3['Company'])
				->setCellValue('L'.$con, $Datalist3['Cheque'])
				->setCellValue('M'.$con, $Datalist3['OnlineTransfer']);*/
				
				$tariff_Today +=	$Datalist3['tariff_Today'];
				$tax_Today +=	$Datalist3['tax_Today'];
				$Discount_Today +=	$Datalist3['Discount'];
				$Net_tariff_Today +=	$Datalist3['tariff_Tax_Today']-$Datalist3['Discount'];
				
				
				$tariff_MTD +=	$Datalist3['tariff_MTD'];
				$tax_MTD +=	$Datalist3['tax_MTD'];
				$Discount_MTD +=	$Datalist3['Discount'];
				$Net_tariff_MTD +=	$Datalist3['tariff_Tax_MTD']-$Datalist3['Discount'];
				
				
				$tariff_YTD +=	$Datalist3['tariff_YTD'];
				$tax_YTD +=	$Datalist3['tax_YTD'];
				$Discount_YTD +=	$Datalist3['Discount'];
				$Net_tariff_YTD +=	$Datalist3['tariff_Tax_YTD']-$Datalist3['Discount'];
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
		
			}
			//debugData($Datalist2);
		
		}
	}
	
			$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Grand Total')
				
				->setCellValue('B'.$con, $tariff_Today)				
				->setCellValue('C'.$con, $tax_Today)
				
				->setCellValue('D'.$con, $Net_tariff_Today)
				
				->setCellValue('E'.$con, $tariff_MTD)
				->setCellValue('F'.$con, $tax_MTD)
												
				->setCellValue('G'.$con, $Net_tariff_MTD)
				
				->setCellValue('H'.$con, $tariff_YTD)
				->setCellValue('I'.$con, $tax_YTD)
				
				->setCellValue('J'.$con, $Net_tariff_YTD);
				
		cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
	
	
	$NewArray	=array();
	foreach($SalesRegisterArray as $Type=>$Datalist1){
		
		foreach($Datalist1 as $outlet=>$Datalist3){ 
		if($Type=='POS'){
			$NewArray[$Datalist3['outlet_Name']]['outlet_Name']=$Datalist3['outlet_Name'];
			$NewArray[$Datalist3['outlet_Name']]['POS_Today']+=$Datalist3['Today'];
			$NewArray[$Datalist3['outlet_Name']]['POS_MTD']+=$Datalist3['MTD'];
			$NewArray[$Datalist3['outlet_Name']]['POS_YTD']+=$Datalist3['YTD'];	
		}else{
			$NewArray[$Datalist3['outlet_Name']]['outlet_Name']=$Datalist3['outlet_Name'];
			$NewArray[$Datalist3['outlet_Name']]['Frontoffice_Today']+=$Datalist3['Today'];
			$NewArray[$Datalist3['outlet_Name']]['Frontoffice_MTD']+=$Datalist3['MTD'];
			$NewArray[$Datalist3['outlet_Name']]['Frontoffice_YTD']+=$Datalist3['YTD'];
			}
				
				
				
				
		
		}
	}
	//debugData($NewArray);
	
	//die;
	
	$tariff_Today =0;
				$tax_Today =0;
				$Discount_Today =0;
				$Net_tariff_Today =0;
				
				
				$tariff_MTD =0;
				$tax_MTD =0;
				$Discount_MTD =0;
				$Net_tariff_MTD =0;
				
				
				$tariff_YTD =0;
				$tax_YTD =0;
				$Discount_YTD =0;
				$Net_tariff_YTD =0;
			
			
			
			
			$con++;
			$con++;
	
	$objPHPExcel->setActiveSheetIndex(0)
			
->setCellValue('A'.$con, 'Front Office Bill Summary')
			->setCellValue('B'.$con, 'For the Day')
			->setCellValue('E'.$con, 'For the Month')
			->setCellValue('H'.$con, 'For the Year');
			
			
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$con.':D'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$con.':G'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$con.':J'.$con);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':D'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':G'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':J'.$con)->getFont()->setBold(true);
			
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			cellColor('A'.$con,'cdecff');	
			cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');	
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':D'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':G'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
	$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':J'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
			$con++;
			
	foreach($nightAuditDay as $Datalist1){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Description')			
			->setCellValue('B'.$con, 'Amount')							
			
			->setCellValue('C'.$con, 'Taxes')				
			->setCellValue('D'.$con, 'Total')
			
			->setCellValue('E'.$con, 'Amount')							
			
			->setCellValue('F'.$con, 'Taxes')				
			->setCellValue('G'.$con, 'Total')
			
			
			->setCellValue('H'.$con, 'Amount')							
			
			->setCellValue('I'.$con, 'Taxes')				
			->setCellValue('J'.$con, 'Total');
			cellColor('A'.$con,'cdecff');									
			cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
		foreach($Datalist1 as $outlet=>$Datalist2){
			
			foreach($Datalist2 as $Datalist3){			
		

				$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A'.$con, $InCount)
				
				->setCellValue('A'.$con, ucwords(strtolower($Datalist3['outlet_Name'])))
				
				->setCellValue('B'.$con, $Datalist3['tariff_Today'])
				
				->setCellValue('C'.$con, $Datalist3['tax_Today'])
				->setCellValue('D'.$con, $Datalist3['tariff_Tax_Today']-$Datalist3['Discount'])
				
				
				->setCellValue('E'.$con, $Datalist3['tariff_MTD'])
				
				->setCellValue('F'.$con, $Datalist3['tax_MTD'])
				->setCellValue('G'.$con, $Datalist3['tariff_Tax_MTD']-$Datalist3['Discount'])
				
				
				->setCellValue('H'.$con, $Datalist3['tariff_YTD'])
				
				->setCellValue('I'.$con, $Datalist3['tax_YTD'])
				->setCellValue('J'.$con, $Datalist3['tariff_Tax_YTD']-$Datalist3['Discount']);
				
				/*->setCellValue('F'.$con, $Datalist3['Taxes'])
				
				->setCellValue('G'.$con, $Datalist3['round_off_amount'])
				->setCellValue('H'.$con, $Datalist3['grant_total_amount'])
				->setCellValue('I'.$con, $Datalist3['Cash'])
				->setCellValue('J'.$con, $Datalist3['Card'])
				->setCellValue('K'.$con, $Datalist3['Company'])
				->setCellValue('L'.$con, $Datalist3['Cheque'])
				->setCellValue('M'.$con, $Datalist3['OnlineTransfer']);*/
				
				$tariff_Today +=	$Datalist3['tariff_Today'];
				$tax_Today +=	$Datalist3['tax_Today'];
				$Discount_Today +=	$Datalist3['Discount'];
				$Net_tariff_Today +=	$Datalist3['tariff_Tax_Today']-$Datalist3['Discount'];
				
				
				$tariff_MTD +=	$Datalist3['tariff_MTD'];
				$tax_MTD +=	$Datalist3['tax_MTD'];
				$Discount_MTD +=	$Datalist3['Discount'];
				$Net_tariff_MTD +=	$Datalist3['tariff_Tax_MTD']-$Datalist3['Discount'];
				
				
				$tariff_YTD +=	$Datalist3['tariff_YTD'];
				$tax_YTD +=	$Datalist3['tax_YTD'];
				$Discount_YTD +=	$Datalist3['Discount'];
				$Net_tariff_YTD +=	$Datalist3['tariff_Tax_YTD']-$Datalist3['Discount'];
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
		
			}
			//debugData($Datalist2);
		
		}
	}
	
			$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Grand Total')
				
				->setCellValue('B'.$con, $tariff_Today)				
				->setCellValue('C'.$con, $tax_Today)
				
				->setCellValue('D'.$con, $Net_tariff_Today)
				
				->setCellValue('E'.$con, $tariff_MTD)
				->setCellValue('F'.$con, $tax_MTD)
												
				->setCellValue('G'.$con, $Net_tariff_MTD)
				
				->setCellValue('H'.$con, $tariff_YTD)
				->setCellValue('I'.$con, $tax_YTD)
				
				->setCellValue('J'.$con, $Net_tariff_YTD);
				cellColor('A'.$con,'cdecff');
		cellColor('B'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':J'.$con,'cdecff');	
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;		
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			$tariff_Today =0;
				$tax_Today =0;
				$Discount_Today =0;
				$Net_tariff_Today =0;
				
				
				$tariff_MTD =0;
				$tax_MTD =0;
				$Discount_MTD =0;
				$Net_tariff_MTD =0;
				
				
				$tariff_YTD =0;
				$tax_YTD =0;
				$Discount_YTD =0;
				$Net_tariff_YTD =0;
			
			
			
			
			$con++;
			$con++;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//ROOM TYPE=======================================================
	 $SQL_ROOMTYPE="SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` ORDER BY status_active_date DESC";
	
	$query = mysqli_query($connNew,$SQL_ROOMTYPE);
$TotalNumberOfRows = mysqli_num_rows($query);
$roomTypeArray=array();

while($Records	   =	mysqli_fetch_object($query)){
	
	$ConnSQL	= "count(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` = '".$current_date."') 
then 1 else 0 end) as `room_avl_Today`,";




$ConnSQL	.= "count(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_month_to_date."' and '".$to_month_to_date."') 
then 1 else 0 end) as `room_avl_MTD`,";





$ConnSQL	.="count(case when ( `".FO_RESERVATIONS_DETAILS."`.`dated` between '".$from_year_to_date."' and '".$to_year_to_date."') 
then 1 else 0 end) as `room_avl_YTD`";




    $sqlOrderDetail = "Select  $ConnSQL from `".FO_RESERVATIONS_DETAILS."` where checkin_status='1' and id_fo_folio_to>0 and id_mst_room_no_allocation ='".$Records->id_mst_room_types."' ";
		

//die;

$queryReceipt = mysqli_query($connNew,$sqlOrderDetail);
$TotalNumberOfRowReceipts = mysqli_num_rows($queryReceipt);

$InCount=1;

$count2=1;
$TotalBillCount=0;
$nightAudit=array();
////while($RecordsReceipt	   =	mysqli_fetch_object($queryReceipt)){
	
	$RecordsReceipt	   =	mysqli_fetch_object($queryReceipt);
	
	
	
	$roomType	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$Records->id_mst_room_types."'");
	
	
	
	
	$roomTypeArray['Rooms'][$roomType]['roomName']=$roomType;
	$roomTypeArray['Rooms'][$roomType]['TotalRooms']=$Records->inventory;
	$roomTypeArray['Rooms'][$roomType]['room_avl_Today']=$RecordsReceipt->room_avl_Today;
	$roomTypeArray['Rooms'][$roomType]['room_avl_MTD']=$RecordsReceipt->room_avl_MTD;
	$roomTypeArray['Rooms'][$roomType]['room_avl_YTD']=$RecordsReceipt->room_avl_YTD;
}
	//ROOM TYPE=======================================================
$con=$con+2;	
	
	
	
	//debugData($roomTypeArray);die;
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Total Collections');
				$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getFont()->setBold(true);
				
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B'.$con, 'For the Day')
			->setCellValue('F'.$con, 'For the Month')
			->setCellValue('J'.$con, 'For the Year');
			
			
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('B'.$con.':D'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('E'.$con.':G'.$con);
			$objPHPExcel->setActiveSheetIndex(0)->mergeCells('H'.$con.':J'.$con);
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':D'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E'.$con.':G'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('H'.$con.':J'.$con)->getFont()->setBold(true);
			
			
			cellColor('A'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':j'.$con,'cdecff');
			$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':E'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);
	$objPHPExcel->getActiveSheet()->getStyle('F'.$con.':I'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
	$objPHPExcel->getActiveSheet()->getStyle('J'.$con.':M'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
				
		$objPHPExcel->getActiveSheet()->getStyle('B'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);		
				
				
				
						
				$con++;
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Description')			
			->setCellValue('B'.$con, 'Front Office')							
			->setCellValue('C'.$con, 'POS')		
									
			->setCellValue('D'.$con, 'Total')
			
			->setCellValue('E'.$con, 'Front Office')							
			->setCellValue('F'.$con, 'POS')
							
			->setCellValue('G'.$con, 'Total')
			
			
			->setCellValue('H'.$con, 'Front Office')							
			->setCellValue('I'.$con, 'POS')
							
			->setCellValue('J'.$con, 'Total');
			cellColor('A'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':j'.$con,'cdecff');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);										
			
		foreach($NewArray as $outlet=>$Datalist3){
			
			
				$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				//->setCellValue('A'.$con, $InCount)
				
				
				->setCellValue('A'.$con, ucwords(strtolower($Datalist3['outlet_Name'])))
				
				->setCellValue('B'.$con, round($Datalist3['POS_Today']))
				->setCellValue('C'.$con, round($Datalist3['Frontoffice_Today']))
							
				->setCellValue('D'.$con, round(($Datalist3['POS_Today']+$Datalist3['Frontoffice_Today'])-$Datalist3['Discount']))
				
				
				->setCellValue('E'.$con, round($Datalist3['POS_MTD']))
				->setCellValue('F'.$con, round($Datalist3['Frontoffice_MTD']))
						
				->setCellValue('G'.$con, round(($Datalist3['Frontoffice_MTD']+$Datalist3['POS_MTD'])-$Datalist3['Discount']))
				
				
				->setCellValue('H'.$con, round($Datalist3['POS_YTD']))
				->setCellValue('I'.$con, round($Datalist3['Frontoffice_YTD']))
								
				->setCellValue('J'.$con, round(($Datalist3['Frontoffice_YTD']+$Datalist3['POS_YTD'])-$Datalist3['Discount']));
				
				
				
				$pos_sub_Today +=	round($Datalist3['POS_Today']);
				$Frontoffice_sub_Today +=	round($Datalist3['Frontoffice_Today']);
				$Discount_Today +=	$Datalist3['Discount'];
				$Net_Today +=	round(($Datalist3['POS_Today']+$Datalist3['Frontoffice_Today'])-$Datalist3['Discount']);
				
				
				$pos_sub_MTD +=	round($Datalist3['POS_MTD']);
				$Frontoffice_sub_MTD +=	round($Datalist3['Frontoffice_MTD']);
				$Discount_MTD +=	$Datalist3['Discount'];
				$Net_MTD +=	round(($Datalist3['POS_MTD']+$Datalist3['Frontoffice_MTD'])-$Datalist3['Discount']);
				
				
				$pos_sub_YTD +=	round($Datalist3['POS_YTD']);
				$Frontoffice_sub_YTD +=	round($Datalist3['Frontoffice_YTD']);
				$Discount_YTD +=	$Datalist3['Discount'];
				$Net_YTD +=	round(($Datalist3['POS_YTD']+$Datalist3['Frontoffice_YTD'])-$Datalist3['Discount']);
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
		
			
			//debugData($Datalist2);
		
		//}
	}
	$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Grand Total')
				
				->setCellValue('B'.$con, $pos_sub_Today)				
				->setCellValue('C'.$con, $Frontoffice_sub_Today)
				
				->setCellValue('D'.$con,  $Net_Today)
				
				->setCellValue('E'.$con, $pos_sub_MTD)
				->setCellValue('F'.$con, $Frontoffice_sub_MTD)
							
				->setCellValue('G'.$con, $Net_MTD)
				
				->setCellValue('H'.$con, $pos_sub_YTD)
				->setCellValue('I'.$con, $Frontoffice_sub_YTD)
				
				->setCellValue('J'.$con, $Net_YTD);
				
			cellColor('A'.$con.':D'.$con,'cdecff');
			cellColor('E'.$con.':G'.$con,'bcb7b7');	
			cellColor('H'.$con.':j'.$con,'cdecff');		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':J'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
	
	
	
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
	$filename=	'salesSummaryReports'.date('d-M-Y').'.xls';
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