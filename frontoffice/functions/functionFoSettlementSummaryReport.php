<?php 

	function FoSettlementSummaryReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($date != ''){
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date('Y-m-d',strtotime($DateExplode['1']));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConn .= " AND p.`doc_date` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
		//$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
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

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);


			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
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
			->setCellValue('L'.$con, 'Surcharge');	
				
$con++;
$SalesRegisterArray=array();
 /*$SQLSalesReportPayment="select p.id ,

case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'CASH' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then 'CARD'
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'CHEQUE'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then 'UPI'
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then 'ONLINETRANSFER'
     when payment_mode='COMPANY' and IFNULL(amount,0)>0 then 'COMPANY'
	 when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then 'ROOMTO'
    else 0 end
    as payment_type ,
    
    case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'Cash Sales' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then id_charges_master
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'Cash Sales'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then remark
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then id_charges_master  	  
    else 0 end
    as payment_remarks ,
	
	
	
	
	case when payment_mode='COMPANY' and IFNULL(amount,0)>0 then id_company
    else 0 end
    as id_company ,
	pp.id_fo_bill ,
	pp.id_fo_folio ,
	
	case when payment_mode='CASH' and IFNULL(amount,0)>0 then amount 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then amount
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then amount
    when payment_mode='UPI' and IFNULL(amount,0)>0 then amount
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then amount
    when payment_mode='COMPANY' and IFNULL(amount,0)>0 then amount
	when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then amount
    else 0 end
    as dramount ,


case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH ,
case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD ,

case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE ,
case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI ,
case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER ,
case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY ,
case when payment_mode='ROOMTO' then IFNULL(amount,0) else null end as ROOMTO ,

DATE(p.date_created) as date_created 
 from fo_receipt pp 
INNER JOIN 	fo_folio p on p.id = pp.id_fo_folio 
WHERE p.id!=0 and pp.amount>0 $SqlConn";*/



$SQLSalesReportPayment="select p.id ,pp.doc_date as receipt_doc_date,fobill.doc_date as fo_bill_doc_date,p.doc_date as folio_doc_date,p.id as id_folio_to,

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
	
	case when p.folio_status='1'  then 'Close' 
	when p.folio_status='0' then 'Open'
   	  
    else 0 end
    as folioStatus ,
	
	
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


case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH ,
case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD ,
case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD ,

case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE ,
case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI ,
case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER ,
case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY ,
case when payment_mode='ROOMTO' then IFNULL(amount,0) else null end as ROOMTO ,

DATE(p.date_created) as date_created 
 from fo_receipt pp 
INNER JOIN 	fo_folio p on p.id = pp.id_fo_folio

INNER JOIN 	fo_bill fobill on fobill.id = p.id_fo_bill
 
WHERE p.id!=0 and pp.amount>0  $SqlConn ORDER BY  fobill.doc_no DESC";

//echo '=================>'.$SQLSalesReportPayment;
//die;
$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);

$ics=1;
while($RecordsSalesReportPayment	   =	mysqli_fetch_object($querySalesReportPayment)){
	
	$id_mst_guest	=  selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$RecordsSalesReportPayment->id_fo_folio."'");
	$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest."'");
	$GuestTitle	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE `id_shop`="'.addslashes($_SESSION['shop']).'" and id="'.$id_mst_attributes_title.'"');

	$first_name	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
	$last_name	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest."'");
	$GuestName	=	$GuestTitle.' '.$first_name.' '.$last_name;
	
	$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$RecordsSalesReportPayment->id_fo_bill."'");
	$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
	$grant_total_amount=0;
	$pos_amount=0;
	$pos_amount	=	selectColumn('pos_purch_pay','sum(amount)','WHERE `id_fo_bill`="'.$RecordsSalesReportPayment->id_fo_bill.'"');
	
	
	$RecordsSalesReportPayment->id_fo_bill.'---'.$grant_total_amount	=	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room+tax_per_day_per_room)','WHERE checkin_status="1" and `id_fo_folio_to`="'.$RecordsSalesReportPayment->id_fo_folio.'" ');
	
	$bill_mdoc_no_fo_bill	= selectColumn(FO_BILL,'mdoc_no'," WHERE `id_fo_folio_to` = '".$RecordsSalesReportPayment->id_fo_folio."'");
	
	$folio_mdoc_no	=  selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$RecordsSalesReportPayment->id_fo_folio."'");
	
	$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($RecordsSalesReportPayment->id_fo_folio)."' ");
		$folioArray='';
		$bill_mdoc_no='';
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$folioArray=array();
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					
					$outletName =selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowOrderDetail->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;	
					
					
					$folioArray[]=$rowOrderDetail->mdoc_no;
					
				}
				
				$bill_mdoc_no=implode(', ',$folioArray);
		}
	
	$CompanyName = selectColumn(TBL_COMPANY,'name','WHERE id="'.$RecordsSalesReportPayment->id_company.'" ');
	//"select *  from ".TBL_COMPANY." where status='1'  and name !='' and `id` = '".$RecordsSalesReportPayment->id_company."'";
	if($CompanyName!=''){
		$payment_type	= $CompanyName;
		
		}else{
			$payment_type=$RecordsSalesReportPayment->payment_type;
			}
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['id_fo_bill']=$RecordsSalesReportPayment->id_fo_bill;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['id_fo_folio']=$RecordsSalesReportPayment->id_fo_folio;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['receipt_no']=$RecordsSalesReportPayment->id;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['roomNo']=$roomNumber;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['payment_type']=$payment_type;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['GuestName']=$GuestName;
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['fo_bill_doc_date']=$RecordsSalesReportPayment->fo_bill_doc_date=='1970-01-01'?'-':date('d-m-Y',strtotime($RecordsSalesReportPayment->fo_bill_doc_date));
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_doc_date']=$RecordsSalesReportPayment->folio_doc_date;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['receipt_doc_date']=$RecordsSalesReportPayment->receipt_doc_date;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['date']=$RecordsSalesReportPayment->folio_doc_date;
	
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_mdoc_no']=$folio_mdoc_no;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['bill_mdoc_no']=$bill_mdoc_no;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['bill_bill_mdoc_no']=$bill_mdoc_no_fo_bill;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_status']=$RecordsSalesReportPayment->folioStatus;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['frontoffice_total_amount']=$grant_total_amount;//$grant_total_amount;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['pos_total_amount']=$pos_amount;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CASH']+=$RecordsSalesReportPayment->CASH;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['BIllONHOLD']+=$RecordsSalesReportPayment->BIllONHOLD;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CARD']+=$RecordsSalesReportPayment->CARD;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CHEQUE']+=$RecordsSalesReportPayment->CHEQUE;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['UPI']+=$RecordsSalesReportPayment->UPI;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['ONLINETRANSFER']+=$RecordsSalesReportPayment->ONLINETRANSFER;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['COMPANY']+=$RecordsSalesReportPayment->COMPANY;
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['ROOMTO']+=$RecordsSalesReportPayment->ROOMTO;
	
	
	$OtherChargeTotal=0;
	$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($RecordsSalesReportPayment->id_folio_to)."'");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
			$TotalAddon=0;
			$tax=0;
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
				$tax	+=	$rowOrderDetail->tax_value*$rowOrderDetail->qty*$rowOrderDetail->days;	
				$TotalAddon+=$rowOrderDetail->rate*$rowOrderDetail->qty*$rowOrderDetail->days;	
				$OtherChargeTotal=$TotalAddon+$tax;
				}
				$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['others']=$TotalAddon+$tax;
				
		}else{
			
			
			$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['others']=0;
			}
	
	$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['amount'] =$grant_total_amount+$pos_amount+$OtherChargeTotal;
	}
 
	

	

//debugdata($SalesRegisterArray);

//die;
$contentqq = '<style>
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
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
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
.table th{
    padding:5px 10px;
    border:1px solid #444;
}';


$contentsss .= '</style>';

$content111 ='';
//if($_REQUEST['pdf']==1){
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
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
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
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';
$foldername =    "/app";

$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;

$BackgroundColorMain	='background-color:#edf2f4;';
$BackgroundColor	='background-color:#fff;';
if($report_show!=1){
	/*$content .= '<table  class="table" style=" text-align:center;margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />
						 </th>
						</tr>
			</table>';*/
}

		
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#c2d69a;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>'; 
		$content .= '<tr style="font-size:16px !important;" ><th colspan="20"  style="vertical-align:central;text-align:center;color:#fff;background-color:#0770b2; font-size:16px !important"><b> Folio wise Details Report  </b></th></tr>';
		
		
		
		
		
	
	
	
	
	
		
		
		
		
		
		$i=1;
	foreach($SalesRegisterArray as $group=>$subindexvalue1){
		//Main Group=======================>
			
		$content .= '<tr class="line" style="'.$BackgroundColorMain.'background-color:#b7cdda;color:#ooo !important;font-size:16px !important;">
			<th  colspan="20" ><b>'.date('d-m-Y',strtotime($group)).'</b></th>
			</tr>';	
		$content .= '<tr style="font-size:12px !important;background-color:#cdecff!important;" >';	
		$content .= '<th style="width:10px;" ><b>S.no</b></th>';	
		$content .= '<th style="text-align: center;width:80px;"><b>Folio No</b></th>
		<th style="text-align: center;width:128px;"><b>Folio Date</b></th>';
		$content .= '
		<th style="text-align: center; width:100px; " ><b>FO Bills</b></th>';
		$content .= '<th style="text-align: center;width:128px;"><b>FO Date</b></th>';
		
		
		$content .= '<th style="text-align: center; width:400px; " ><b>POS Bills </b></th>
		';
		//$content .= '<th style="text-align: center; " ><b>Room No</b></th>';
		//$content .= '<th style="text-align: center; " ><b>Receipt No</b></th>';
		
		
		
		$content .= '<th  style="text-align: center; width:300px; " ><b>Guest Name</b></th>';
		
		//$content .= '<th  style="text-align: center; " ><b>Particulars </b></th>';
		
		$content .= '<th  style="text-align: center; " ><b>Room Tariff</b></th>
		<th  style="text-align: center; " ><b>POS </b></th>
		<th  style="text-align: center; " ><b>Others </b></th>';
		
		$content .= '<th  style="text-align: center; " ><b>Folio Amount</b></th>
		<th  style="text-align: center; " ><b>CASH</b></th>
		<th  style="text-align: center; " ><b>BIllONHOLD</b></th>
		<th  style="text-align: center; " ><b>CARD</b></th>
		<th  style="text-align: center; " ><b>CHEQUE</b></th>
		<th  style="text-align: center; " ><b>UPI</b></th>
		<th  style="text-align: center; " ><b>ONLINETRANSFER</b></th>
		<th  style="text-align: center; " ><b>COMPANY</b></th>
		<th  style="text-align: center; " ><b>ROOMTO</b></th>
		
		<th  style="text-align: center; " ><b>Status</b></th>
		
		';
		$content .= '</tr>';
		$frontoffice_total_amount ='';
			$pos_total_amount ='';
			$other_total_amount ='';
			$TypeWiseTotal ='';
			$CASH ='';
			$BIllONHOLD ='';
			$CARD ='';
			$CHEQUE ='';
			$UPI ='';
			$ONLINETRANSFER ='';
			$COMPANY='';
			$ROOMTO ='';
		
		
		foreach($subindexvalue1 as $Date=>$subindexvalue){
			
			
		$contentSubGroup='';
		//$TypeWiseTotal='';
		foreach($subindexvalue as $data){
			
			
			
			
				//debugData($data);				
				
								
				$contentSubGroup .= '<tr  '.$listTagClass.' style="font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentSubGroup .= '<td style="text-align:center;width:50px;border:0.05em solid #000;">'.$i.'</td>';
				$contentSubGroup .= '<td style="text-align:left;">'.strtoupper($data['folio_mdoc_no']).'</td>';
				$contentSubGroup .= '<td  style="text-align:left;">'.date('d-m-Y',strtotime($data['folio_doc_date'])).'</td>';
				
				$contentSubGroup .= '<td style="text-align:center;">'.strtoupper($data['bill_bill_mdoc_no']).'</td>';
				$contentSubGroup .= '<td  style="text-align:left;">'.$data['fo_bill_doc_date'].'</td>';
				
				$contentSubGroup .= '<td style="text-align:center;">'.strtoupper($data['bill_mdoc_no']).'</td>';
				
				//$contentSubGroup .= '<td style="text-align:center;">'.$data['roomNo'].'</td>';						
				//$contentSubGroup .= '<td  style="text-align:center;">'.$data['receipt_no'].'</td>';				
				$contentSubGroup .= '<td style=text-align:left;">'.$data['GuestName'].'</td>';
				//$contentSubGroup .= '<td style=text-align:left;">'.$data['payment_type'].'</td>';				
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['frontoffice_total_amount']).'</td>';
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['pos_total_amount']).'</td>';			
				
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['others']).'</td>';
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['amount']).'</td>';
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['CASH']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['BIllONHOLD']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['CARD']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['CHEQUE']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['UPI']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['ONLINETRANSFER']).'</td>';	
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['COMPANY']).'</td>';
				$contentSubGroup .= '<td style=text-align:right;">'.round($data['ROOMTO']).'</td>';	
				
			
				$contentSubGroup .= '<td style=text-align:right;">'.$data['folio_status'].'</td>';
				
				$contentSubGroup .= '</tr>';
				
				$i++;
				
			$frontoffice_total_amount +=round($data['frontoffice_total_amount']);
			$pos_total_amount +=round($data['pos_total_amount']);
			$TypeWiseTotal +=round($data['amount']);
			$CASH +=round($data['CASH']);
			$BIllONHOLD +=round($data['BIllONHOLD']);
			$CARD +=round($data['CARD']);
			$CHEQUE +=round($data['CHEQUE']);
			$UPI +=round($data['UPI']);
			$ONLINETRANSFER +=round($data['ONLINETRANSFER']);
			$COMPANY +=round($data['COMPANY']);
			$ROOMTO +=round($data['ROOMTO']);
			
			
			$other_total_amount +=round($data['others']);
			
			
			
			
		}
				/*$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000; font-weight:bold;  background-color:#cdecff!important;">';				
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td  style="text-align:left;" colspan="2">'.$group.' SUB TOTAL</td>';			
				$content .= '<td style=text-align:right;">'.$TypeWiseTotal.'</td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '</tr>';*/
		$content .= $contentSubGroup;	
	}
		
	
	$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000; font-weight:bold;  background-color:#cdecff!important;">';				
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td  style="text-align:left;" colspan="2"> TOTAL</td>';
				$content .= '<td style=text-align:right;">'.round($frontoffice_total_amount).'</td>';			
				$content .= '<td style=text-align:right;">'.round($pos_total_amount).'</td>';
				$content .= '<td style=text-align:right;">'.round($other_total_amount).'</td>';
				$content .= '<td style=text-align:right;">'.round($TypeWiseTotal).'</td>';			
				$content .= '<td style=text-align:right;">'.round($CASH).'</td>';
				$content .= '<td style=text-align:right;">'.round($BIllONHOLD).'</td>';
				$content .= '<td style=text-align:right;">'.round($CARD).'</td>';
				$content .= '<td style=text-align:right;">'.round($CHEQUE).'</td>';
				$content .= '<td style=text-align:right;">'.round($UPI).'</td>';
				$content .= '<td style=text-align:right;">'.round($ONLINETRANSFER).'</td>';
				$content .= '<td style=text-align:right;">'.round($COMPANY).'</td>';
				$content .= '<td style=text-align:right;">'.round($ROOMTO).'</td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '</tr>';
				
				/*$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000; font-weight:bold;  background-color:#cdecff!important;">';				
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style="text-align:center;" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td style=text-align:right;"></td>';
				$content .= '<td  style="text-align:left;" colspan="3"> TOTAL COLLECTION </td>';
						
				$content .= '<td style=text-align:right;">'.($CASH+$BIllONHOLD+$CARD+$CHEQUE+$UPI+$ONLINETRANSFER+$COMPANY+$ROOMTO).'</td>';
				$content .= '</tr>';*/
				
				
			
	}	
		$content .= '</table>';
		//echo $content;
//		die;
		$date=date('d-m-Y');

$Filename='FoliowiseDetailsReport_'.$date;	
	

	if($report_show==3){ //print_r($_REQUEST);die;
			$dompdf = new DOMPDF();
			$dompdf->set_paper('landscape', 'landscape');
			$dompdf->load_html($content);
			$dompdf->render();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
			$dompdf->output();
			$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	}else if($report_show==2){
			 $test=$content;
			//die;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;die;
	}
	else{
		 echo $content;
		die;
		}

	
    
    }
    ?>