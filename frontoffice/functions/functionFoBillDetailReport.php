<?php 

	function FoBillDetailReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($date != ''){
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date('Y-m-d',strtotime($DateExplode['1']));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConn .= " AND pp.`doc_date` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
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
if($NumberOfRowsSalesReportPayment>0){
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
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['amount'] =$grant_total_amount+$pos_amount;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['id_fo_bill']=$RecordsSalesReportPayment->id_fo_bill;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['id_fo_folio']=$RecordsSalesReportPayment->id_fo_folio;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['receipt_no']=$RecordsSalesReportPayment->id;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['roomNo']=$roomNumber;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['payment_type']=$payment_type;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['GuestName']=$GuestName;
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['fo_bill_doc_date']=$RecordsSalesReportPayment->fo_bill_doc_date=='1970-01-01'?'-':date('d-m-Y',strtotime($RecordsSalesReportPayment->fo_bill_doc_date));
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_doc_date']=$RecordsSalesReportPayment->folio_doc_date;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['receipt_doc_date']=$RecordsSalesReportPayment->receipt_doc_date;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['date']=$RecordsSalesReportPayment->fo_bill_doc_date;
	
	
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_mdoc_no']=$folio_mdoc_no;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['bill_mdoc_no']=$bill_mdoc_no;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['bill_bill_mdoc_no']=$bill_mdoc_no_fo_bill;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['folio_status']=$RecordsSalesReportPayment->folioStatus;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['frontoffice_total_amount']=$grant_total_amount;//$grant_total_amount;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['pos_total_amount']=$pos_amount;
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CASH']+=$RecordsSalesReportPayment->CASH;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['BIllONHOLD']+=$RecordsSalesReportPayment->BIllONHOLD;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CARD']+=$RecordsSalesReportPayment->CARD;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['CHEQUE']+=$RecordsSalesReportPayment->CHEQUE;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['UPI']+=$RecordsSalesReportPayment->UPI;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['ONLINETRANSFER']+=$RecordsSalesReportPayment->ONLINETRANSFER;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['COMPANY']+=$RecordsSalesReportPayment->COMPANY;
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['ROOMTO']+=$RecordsSalesReportPayment->ROOMTO;
	
	
	
	
	
	
	$OtherChargeTotal=0;
	$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($RecordsSalesReportPayment->id_fo_folio)."'");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
			$TotalAddon=0;
			$tax=0;
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
				$tax	+=	$rowOrderDetail->tax_value*$rowOrderDetail->qty*$rowOrderDetail->days;	
				$TotalAddon+=$rowOrderDetail->rate*$rowOrderDetail->qty*$rowOrderDetail->days;	
				$OtherChargeTotal=$TotalAddon+$tax;
				}
				$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['others']=$OtherChargeTotal;
				
		}else{
			
			
			$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['others']=0;
			}
	
	$SalesRegisterArray[$RecordsSalesReportPayment->fo_bill_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['amount'] =$grant_total_amount+$pos_amount+$OtherChargeTotal;
	
	//$SalesRegisterArray[$RecordsSalesReportPayment->folio_doc_date][$RecordsSalesReportPayment->id_fo_folio][$RecordsSalesReportPayment->id]['amount'] =$grant_total_amount+$pos_amount+$OtherChargeTotal;
	
	
	
}
	}
 
	

	

//debugdata($SalesRegisterArray);

//die;
 $content ='';		
 
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

		
	$content = '<style>
body { 
    margin: 0; 
    padding: 0;
    font-size: 13px !important;
}
.table-bordered {
    border: 0.5px solid #000;
    border-collapse: collapse;
}
.table {
    font-size: 11px !important; 
    margin-bottom: 20px;	   
    width: 100%;
    background-color: transparent;
}
.table-bordered > tbody > tr > td, 
.table-bordered > tbody > tr > th, 
.table-bordered > tfoot > tr > td,  
.table-bordered > thead > tr > td, 
.table-bordered > thead > tr > th {	
    border: 0.8px solid #000 !important;
    color: #000;
}
</style>';

$content .= '<table class="table table-bordered" style="width: 100%; border: 0.8px solid #000; border-collapse: collapse;">';
$content .= '<tr style="font-size: 16px; text-align: center;">
    <th colspan="20" style="vertical-align: middle; text-align: center; color: #fff; background-color: #0770b2; font-size: 16px; border: 0.8px solid #000;">
        <b>Fo Bill Wise Details Report</b>
    </th>
</tr>';

// Example table rows
/*for ($i = 0; $i < 5; $i++) {
    $content .= '<tr>';
    for ($j = 0; $j < 20; $j++) {
        $content .= '<td style="border: 0.8px solid #000; text-align: center;">Cell ' . ($i + 1) . ',' . ($j + 1) . '</td>';
    }
    $content .= '</tr>';
}*/



if ($excel == 1) {  $size ="font-size: 16px !important;";
    // Add inline borders for compatibility with Excel
    $content .= '<style>
    table, tr, th, td {
        border: 0.8px solid #000 !important;
        border-collapse: collapse;
		font-size: 10px !important;
    }
	table, tr, th {
        border: 0.8px solid #000 !important;
        border-collapse: collapse;
		font-size: 10px !important;
		background-color:#cdecff;
    }
	
    </style>';
}
		
		// Example table rows
/*for ($i = 0; $i < 5; $i++) {
    $content .= '<tr>';
    for ($j = 0; $j < 20; $j++) {
        $content .= '<td style="border: 0.8px solid #000; text-align: center;">Cell ' . ($i + 1) . ',' . ($j + 1) . '</td>';
    }
    $content .= '</tr>';
}*/
	
	
			$i=1;
	foreach($SalesRegisterArray as $group=>$subindexvalue1){
		//Main Group=======================>
			
		$content .= '<tr >
			<th  colspan="20" style="text-align: left;border: 0.8px solid #000;background-color:#b7cdda;color:#ooo !important;font-size:16px !important;" ><b>'.date('d-m-Y',strtotime($group)).'</b></th>
			</tr>';	
		$content .= '<tr>';	
		$content .= '<th style="width:10px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.'" ><b>S.no</b></th>';	
		
		$content .= '
		<th style="text-align: center; width:100px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>FO Bills</b></th>';
		$content .= '<th style="text-align: center;width:133px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.'"><b>FO Date</b></th>';
		
		$content .= '<th style="text-align: center;width:80px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.'"><b>Folio No</b></th>
		<th style="text-align: center;width:133px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;"'.$size.'><b>Folio Date</b></th>
		<th style="text-align: center; width:400px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>POS Bills </b></th>
		';
		//$content .= '<th style="text-align: center; " ><b>Room No</b></th>';
		//$content .= '<th style="text-align: center; " ><b>Receipt No</b></th>';
		
		
		
		$content .= '<th  style="text-align: center; width:300px;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>Guest Name</b></th>';
		
		//$content .= '<th  style="text-align: center; " ><b>Particulars </b></th>';
		
		$content .= '<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff !important;'.$size.' " ><b>Room Tariff</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>POS </b></th>
		<th  style="text-align: center;border: 0.8px solid #000; font-size:12px !important;background-color:#cdecff;'.$size.'" ><b>Others </b></th>';
		
		$content .= '<th  style="text-align: center;border: 0.8px solid #000; font-size:12px !important;background-color:#cdecff;'.$size.'" ><b>Folio Amount</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.'" ><b>CASH</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>BIllONHOLD</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>CARD</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>CHEQUE</b></th>
		<th  style="text-align: center;border: 0.8px solid #000; font-size:12px !important;background-color:#cdecff;'.$size.'" ><b>UPI</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>ONLINETRANSFER</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>COMPANY</b></th>
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>ROOMTO</b></th>
		
		<th  style="text-align: center;border: 0.8px solid #000;font-size:12px !important;background-color:#cdecff;'.$size.' " ><b>Status</b></th>
		
		';
		$content .= '</tr>';
		$frontoffice_total_amount ='';
			$pos_total_amount ='';
			$TypeWiseTotal ='';
			$CASH ='';
			$BIllONHOLD ='';
			$CARD ='';
			$CHEQUE ='';
			$UPI ='';
			$ONLINETRANSFER ='';
			$COMPANY='';
			$ROOMTO ='';
		$other_total_amount ='';
		
		foreach($subindexvalue1 as $Date=>$subindexvalue){
			
			
		$contentSubGroup='';
		//$TypeWiseTotal='';
		foreach($subindexvalue as $data){
			
			
			
			
				//debugData($data);				
				
								
				$contentSubGroup .= '<tr  '.$listTagClass.' style="font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentSubGroup .= '<td style="text-align:center;width:50px;border:0.05em solid #000;">'.$i.'</td>';
				
				
				$contentSubGroup .= '<td style="text-align:center;border:0.05em solid #000;">'.strtoupper($data['bill_bill_mdoc_no']).'</td>';
				$contentSubGroup .= '<td  style="text-align:left;border:0.05em solid #000;">'.$data['fo_bill_doc_date'].'</td>';
				$contentSubGroup .= '<td style="text-align:left;border:0.05em solid #000;">'.strtoupper($data['folio_mdoc_no']).'</td>';
				$contentSubGroup .= '<td  style="text-align:left;border:0.05em solid #000;">'.date('d-m-Y',strtotime($data['folio_doc_date'])).'</td>';
				$contentSubGroup .= '<td style="text-align:center;border:0.05em solid #000;">'.strtoupper($data['bill_mdoc_no']).'</td>';
				
				//$contentSubGroup .= '<td style="text-align:center;">'.$data['roomNo'].'</td>';						
				//$contentSubGroup .= '<td  style="text-align:center;">'.$data['receipt_no'].'</td>';				
				$contentSubGroup .= '<td style="text-align:left;border:0.05em solid #000;">'.$data['GuestName'].'</td>';
				//$contentSubGroup .= '<td style=text-align:right;border:0.05em solid #000;">'.$data['payment_type'].'</td>';				
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['frontoffice_total_amount']).'</td>';
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['pos_total_amount']).'</td>';			
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['others']).'</td>';
				
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['amount']).'</td>';
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['CASH']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['BIllONHOLD']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['CARD']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['CHEQUE']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['UPI']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['ONLINETRANSFER']).'</td>';	
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['COMPANY']).'</td>';
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.round($data['ROOMTO']).'</td>';	
				
			
				$contentSubGroup .= '<td style="text-align:right;border:0.05em solid #000;">'.$data['folio_status'].'</td>';
				
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
		
	
	$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000; font-weight:bold; >';				
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
		$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				//$content .= '<td style="text-align:center;" ></td>';
				$content .= '<td  style="text-align:left;border:0.05em solid #000; background-color:#cdecff !important;"" colspan="2"> TOTAL</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$frontoffice_total_amount.'</td>';			
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$pos_total_amount.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.round($other_total_amount).'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$TypeWiseTotal.'</td>';			
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$CASH.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$BIllONHOLD.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$CARD.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$CHEQUE.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$UPI.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$ONLINETRANSFER.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$COMPANY.'</td>';
				$content .= '<td style="text-align:right;border:0.05em solid #000; background-color:#cdecff !important;"">'.$ROOMTO.'</td>';
				$content .= '<td style="text-align:center;border:0.05em solid #000; background-color:#cdecff !important;"" ></td>';
				$content .= '</tr>';
				
				/*$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000; font-weight:bold;  background-color:#cdecff!important;">';				
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

$Filename='FoBillDetailReport_'.$date;	
	

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