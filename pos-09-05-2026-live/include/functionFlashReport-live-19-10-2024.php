<?php


function cellColor($cells,$color,$objPHPExcel){
	    //global $objPHPExcel;
		
	}
	
function FlashReport($DateArray,$id_report_type,$report_show,$showItemReport,$kot_nc,$appConnect,$connNew,$id_shop,$cronSet,$Filename,$objPHPExcel){	

	

	
	//debugData($DateArray);
$PrintCount=0;
$TableReportArray=array();$TableReportArray22=array();
	foreach($DateArray as $reportTypeFlash=>$Datelest){
		$ReportAsOnDate	= $DateArray['Day']['StartDate'];
		$printer='';
		//echo '==='.$reportTypeFlash;
		 $Datelest['StartDate'];
		$SqlConn='';
		$Date = $Datelest['StartDate'];
		 $endDate = $Datelest['EndDate'];
	if($Date != ''){
		$DateExplode = $Date;
		$startDate = date('Y-m-d',strtotime($Date));
		//$endDate	=	date('Y-m-d',strtotime($Date));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		 $SqlConn .= " AND DATE(`date_created`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		 $SqlConnCancelled = " AND DATE(`date_created`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		
	}
	
	
	
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}


	$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".$id_shop."'");
	$rowShop = mysqli_fetch_object($resShop);
	$logo	=	$rowShop->image;
	



	
$SQL="select 
       id as 'PaymentID'
	  ,id_attribute_shift
	  ,id_attribute_table
	  ,pax
      ,id_mst_outlet
      ,outlet_Name
      ,mdoc_no
      ,sub_total_items
      ,(discount_amount_additional+total_discount_items) AS 'Discount'
      ,others_charges_net_amount As 'OtherCharges'      
      ,sgst_total_items as sgst
      ,cgst_total_items as cgst
      ,igst_total_items as igst
      ,cess_total_items as cess
      ,vat_total_items as vat
      ,surcharge_total_items as surcharge      
      ,round_off_amount
      ,net_amount_items
      ,grant_total_amount
      ,name as 'UserName'
	  ,max(time) as Time
	  ,sum(CASH) as Cash
	  ,sum(CARD) as Card

	  ,sum(CHEQUE) as Cheque
	  ,sum(UPI) as 'Upi'
	  ,sum(ONLINETRANSFER) as 'OnlineTransfer'
	  ,sum(COMPANY) as Company
	   ,sum(BIllONHOLD) as BillOnHold
	  ,field_value as 'FeildValue'
      ,max(date_created) as 'CreatedDate'
	  ,max(bill_date_created) as 'bill_date_created'
	  ,id_charges_master
	  ,remark
	  ,doc_no
	  ,id_company
	  ,sc_charges_net_amount
	  ,sc_sgst
	  ,sc_cgst
	  ,doc_type
	  ,id_fo_bill
	  
from 
(
select
       p.id
	   ,p.id_attribute_shift
      ,comp.name as 'outlet_Name'
      ,usr.name
	  ,time as Time
	  ,p.mdoc_no
      ,p.id_mst_outlet
      ,p.sub_total_items
      ,p.sgst_total_items
      ,p.cgst_total_items
      ,p.igst_total_items
      ,p.cess_total_items
      ,p.vat_total_items
      ,p.surcharge_total_items
      ,p.round_off_amount
      ,p.net_amount_items
      ,p.grant_total_amount
      ,p.others_charges_net_amount
      ,p.discount_amount_additional
      ,p.total_discount_items
	  ,p.sc_charges_net_amount
	  ,p.sc_sgst
	  ,p.sc_cgst
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD
	  
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI
	  ,case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
	  ,att.field_value
      ,pp.doc_date as date_created
	  ,p.pax
	  ,p.id_attribute_table
	  ,p.date_created as bill_date_created
	  ,p.doc_no
	  ,pp.id_charges_master
	  ,pp.remark
	  ,pp.id_company
	  ,p.doc_type
	   ,p.id_fo_bill
	  
    
	from pos_purch_pay pp
	INNER JOIN
	pos_purch p
	on
	p.id = pp.id_purch
		
	
	INNER JOIN
	mst_attributes att
	on
	att.id = p.id_attribute_shift   
	
	INNER JOIN
	mst_outlets as comp
	on
	comp.id = p.id_mst_outlet   
		
		
	INNER JOIN
	mst_users usr
	on
	usr.id=pp.id_mst_user_created_by
   
    
) as settlement_summary
WHERE id!=0 $SqlConn
GROUP BY id,name,field_value  ORDER BY id,doc_no asc";
//echo '<br>========='.$SQL;
		//die;

$query = mysqli_query($connNew,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;

$ReportArrayOutlet=array();
$ReportArray=array();
$grant_total_amount='';
$BillNoArray=array();
$Discount='';
$Packing=array();

$NoOfBills	=	mysqli_num_rows($query);
$cancelled= selectColumn('pos_purch','count(id)'," WHERE `cancelled` = '1' AND pos_bill_type='2' ".$SqlConnCancelled."  ");
$rePrinted= selectColumn('pos_purch','count(id)'," WHERE `printed` > '1' AND pos_bill_type='2' ".$SqlConnCancelled."  ");		
if($NoOfBills>0){
while($Records	   =	mysqli_fetch_object($query)){
	$TotalCollection='';
	$shiftSummary	=	$Records->id_attribute_shift; 
	//echo " WHERE `item_description` like '%Packing%'  AND id_pos_purch ='".$Records->PaymentID."' ";
	$Packing[]=$Records->PaymentID;
	
	//= selectColumn('pos_purch_details','(item_amount*qty)'," WHERE  `item_description` like '%Packing%'  AND id_pos_purch ='".$Records->PaymentID."' ");
	$mstoutlet=$Records->id_mst_outlet;
	
	$name = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$Records->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');
	$ReportArrayOutlet['Outlet'][$name]+=$Records->sub_total_items;
	$ReportArrayOutlet['OutletData'][$name]=$Records->id_mst_outlet;
	//$ReportArrayOutlet['OutletData'][id_mst_outlet]=$Records->id_mst_outlet;
	$id_mst_outlet_prim = $Records->id_mst_outlet;
	$BillNoArray[]=$Records->mdoc_no;
	$Discount +=$Records->Discount==''?'0':$Records->Discount;
	$grant_total_amount += $Records->grant_total_amount;
	$pax += $Records->pax;
	$sub_total_items += $Records->sub_total_items;
	
				$ReportArray['PODDAYWISE']['sub_total_items']  += $Records->sub_total_items;
				///$ReportArray['PODDAYWISE']['Tax']['Discount']  +=$Records->Discount==''?'0':$Records->Discount;
				$ReportArray['PODDAYWISE']['Tax']['Sub Total']   +=($Records->sub_total_items-$Records->Discount);
				$ReportArray['PODDAYWISE']['Tax']['Service Charges']  += $Records->OtherCharges+$Records->sc_charges_net_amount;
				$ReportArray['PODDAYWISE']['Tax']['sgst']  += $Records->sgst+$Records->sc_sgst;
				$ReportArray['PODDAYWISE']['Tax']['cgst']  += $Records->cgst+$Records->sc_cgst;
				$ReportArray['PODDAYWISE']['Tax']['igst']  += $Records->igst;
				$ReportArray['PODDAYWISE']['Tax']['cess']   +=$Records->cess;
				$ReportArray['PODDAYWISE']['Tax']['vat']  += $Records->vat;
				$ReportArray['PODDAYWISE']['Tax']['Surcharge']  += $Records->surcharge;
				$ReportArray['PODDAYWISE']['Tax']['Round Off']   +=$Records->round_off_amount;
				//$ReportArray['PODDAYWISE']['Tax']['Grand Total']  += $Records->grant_total_amount;
				
				$ReportArray['PODDAYWISE']['grant_total_amount']  += $Records->grant_total_amount;
				$ReportArray['PODDAYWISE']['Type']['Cash']  += $Records->Cash;
				$ReportArray['PODDAYWISE']['Type']['Card']  += $Records->Card;
				$ReportArray['PODDAYWISE']['Type']['Company']  += $Records->Company;
				$ReportArray['PODDAYWISE']['Type']['Cheque']  += $Records->Cheque;
				$ReportArray['PODDAYWISE']['Type']['OnlineTransfer']  += $Records->OnlineTransfer;
				$ReportArray['PODDAYWISE']['Type']['BillOnHold']  += $Records->BillOnHold;
	
	
				//$ReportArrayOutlet['Outlet'][$name]+=$Records->sub_total_items;
				$TableReportArray22['PODDAYWISE'][$name][$reportTypeFlash]  += $Records->sub_total_items;
				//$TableReportArray['PODDAYWISE']['Revenue']['sub_total_items'][$reportTypeFlash]  += $Records->sub_total_items;
				
				$TableReportArray['PODDAYWISE']['Revenue']['Discount'][$reportTypeFlash]  +=$Records->Discount==''?'0':$Records->Discount;
				$TableReportArray['PODDAYWISE']['Revenue']['Service Charges'][$reportTypeFlash]  += $Records->OtherCharges+$Records->sc_charges_net_amount;
				$TableReportArray['PODDAYWISE']['Revenue']['Sub Total'][$reportTypeFlash]   +=($Records->sub_total_items-$Records->Discount);
				
				$TableReportArray['PODDAYWISE']['Revenue']['sgst'][$reportTypeFlash]  += $Records->sgst+$Records->sc_sgst;
				$TableReportArray['PODDAYWISE']['Revenue']['cgst'][$reportTypeFlash]  += $Records->cgst+$Records->sc_cgst;
				$TableReportArray['PODDAYWISE']['Revenue']['igst'][$reportTypeFlash]  += $Records->igst;
				$TableReportArray['PODDAYWISE']['Revenue']['cess'][$reportTypeFlash]   +=$Records->cess;
				$TableReportArray['PODDAYWISE']['Revenue']['vat'][$reportTypeFlash]  += $Records->vat;
				$TableReportArray['PODDAYWISE']['Revenue']['Surcharge'][$reportTypeFlash]  += $Records->surcharge;
				$TableReportArray['PODDAYWISE']['Revenue']['Round Off'][$reportTypeFlash]   +=$Records->round_off_amount;
				//$TableReportArray['PODDAYWISE']['Revenue']['Grand Total']  += $Records->grant_total_amount;
				
				$TableReportArray['PODDAYWISE']['Revenue']['Grant Total'][$reportTypeFlash]  += $Records->grant_total_amount;
				$TableReportArray['PODDAYWISE']['Collections']['Cash'][$reportTypeFlash]  += $Records->Cash;
				$TableReportArray['PODDAYWISE']['Collections']['Card'][$reportTypeFlash]  += $Records->Card;
				$TableReportArray['PODDAYWISE']['Collections']['Company'][$reportTypeFlash]  += $Records->Company;
				$TableReportArray['PODDAYWISE']['Collections']['Cheque'][$reportTypeFlash]  += $Records->Cheque;
				$TableReportArray['PODDAYWISE']['Collections']['OnlineTransfer'][$reportTypeFlash]  += $Records->OnlineTransfer;
				$TableReportArray['PODDAYWISE']['Collections']['BillOnHold'][$reportTypeFlash]  += $Records->BillOnHold;
				$TotalCollection+=($Records->Cash+$Records->Card+$Records->Company+$Records->Cheque+$Records->OnlineTransfer+$Records->BillOnHold);
				$TableReportArray['PODDAYWISE']['Collections']['Total Collection'][$reportTypeFlash]  += $TotalCollection;
				
				
				$PackingReport[$reportTypeFlash][]=$Records->PaymentID;
				
				
				
				
				
}
   
	
	//debugData($TableReportArray);
	$listPackingid	=implode(',',$Packing);
	
	$PackingCharges = selectColumn('pos_purch_details','sum(item_amount*qty)'," WHERE  `item_description` like '%Packing%'  AND id_pos_purch IN (".$listPackingid.") ");
	
	$TableReportArray22['PODDAYWISE']['Packing Charges'][$reportTypeFlash]  += $PackingCharges;
	$Acp=round($sub_total_items/$pax);
	$id_outlet= $ReportArrayOutlet['OutletData']['id_mst_outlet'];
     $OutletSql="SELECT * FROM ".TBL_OUTLETS."   WHERE id='".$id_mst_outlet_prim."' AND id_shop='".$id_shop."' ";
	$resultPosOutlet = mysqli_query($connNew, $OutletSql); 
	
	$posOuletResult = mysqli_fetch_object($resultPosOutlet);	
	
	
	
	
	
	/*
			$printer.= (str_pad('No Of Bills : '.$NoOfBills,40," "));
			$printer.=("<br>");  
			$printer.= (str_pad('Bill From '.$BillNoArray['0'].' To '.$BillNoArray[count($BillNoArray)-1],40," ")); 
			$printer.=("<br>");  
			$printer.= (str_pad('No of Cancelled Bills : '.$cancelled,40," ")); 
			$printer.=("<br>"); 
			$printer.= (str_pad('No of Pax : '.$pax,28," ")); 
			$printer.= (str_pad('APC : Rs '.$Acp,10," ")); 
			$printer.=("<br>"); */
	
	$TableReportArray['PODDAYWISE']['Bill Details']['No Of Bills'][$reportTypeFlash]  = $NoOfBills;
 $TableReportArray['PODDAYWISE']['Bill Details']['Bill From'][$reportTypeFlash]  = ''.$BillNoArray['0'].' To '.$BillNoArray[count($BillNoArray)-1];
	$TableReportArray['PODDAYWISE']['Bill Details']['No of Cancelled Bills'][$reportTypeFlash]  = $cancelled;
	//$TableReportArray['PODDAYWISE']['Bill Details']['No of Re-Printed Bills'][$reportTypeFlash]  = $rePrinted;
	$TableReportArray['PODDAYWISE']['Bill Details']['No of Pax'][$reportTypeFlash]  = $pax;
	$TableReportArray['PODDAYWISE']['Bill Details']['APC'][$reportTypeFlash]  = $Acp;
	
	
	
	
	
	
	//debugData($BillNoArray);
	if($id_report_type!='270'){
$DataList='';
$DataList ='<div class="col-xs-12"> 

  <div class="box">
    <div class="row">
      <div class="col-md-9 col-lg-10">
      <div id="printTable'.$PrintCount++.'">
          <div id="invoice-POS"> 
          <div id="bot">
              <pre style="font-size:12px!important;">
			  <style>#wordwarkwirh{ word-warp:break-word; width:200px;}</style>';
             
     $printer.='<div style="text-align:right;display:table;text-align:right;width:100%;"><span style="text-align:right;font-size:12px;margin-right:15px;">Time:'.date('h:i A').'</span></div>';              
     $printer.='<div style="text-align:center;display:table;">';             				 
		$printer .='<div style="text-align:center;"><span style="text-align:center;font-size:18px;font-weight:bold">'.$posOuletResult->name.' </span><br></div>';
		$printer .='<div style="text-align:center;"><span style="font-size:13px;font-weight:bold">'.$posOuletResult->description.'</span></div>';
		$printer.=('<div style="text-align:center;">'.$posOuletResult->address.'</div>');
			if($posOuletResult->mobile!=''){	
				$printer.=('<div style="text-align:center;"><span>Contact:'.$posOuletResult->mobile.'</span></div>');
			}
			if($posOuletResult->email!=''){	
				$printer.=('<div style="text-align:center;"><span> Email1:'.$posOuletResult->email.'</span></div>');
					}
			if($posOuletResult->cin_no!=''){			
			$printer.=('<div style="text-align:center;">CIN : '.$posOuletResult->cin_no.'</div>');			
			}
			if($posOuletResult->fssai_no!=''){
			$printer.=('<div style="text-align:center;">FSSAI No : '.$posOuletResult->fssai_no.'</div>');
			
			
			}
		$printer.=("----------------------------------------<br>"); 
		
		//$printer.=("<br>");  
		if($reportTypeFlash=='mtd'){
			$printer.=(str_pad('<span style="margin-left:15px;"><b>MTD Report<br> From '.$startDate = date('d/M/Y',strtotime($Date)).' To '.date('d/M/Y',strtotime($endDate)).'</b></span>',45," "));
			}else{
		$printer.=(str_pad('<span style="margin-left:15px;"><b>Daily Summary Report for '.$startDate = date('d/M/Y',strtotime($Date)).' </b></span>',45," "));
			}
			$printer.=("<br>");  
		
		
			$printer.=("----------------------------------------<br>");
			$printer.= (str_pad('No Of Bills : '.$NoOfBills,40," "));
			$printer.=("<br>");  
			$printer.= (str_pad('Bill From '.$BillNoArray['0'].' To '.$BillNoArray[count($BillNoArray)-1],40," ")); 
			$printer.=("<br>");  
			$printer.= (str_pad('No of Cancelled Bills : '.$cancelled,40," ")); 
			$printer.=("<br>"); 
			//$printer.= (str_pad('No of Re-Printed Bills : '.$rePrinted,40," ")); 
			//$printer.=("<br>");
			$printer.= (str_pad('No of Pax : '.$pax,28," ")); 
			$printer.= (str_pad('APC : Rs '.$Acp,10," ")); 
			$printer.=("<br>"); 
			$printer.=("----------------------------------------<br>"); 
			$printer.=("<div>");
			foreach($ReportArrayOutlet['Outlet'] as  $OutletName=>$OutletValue){
			$printer.=(str_pad('<b>'.$OutletName.' Sales</b>',35.5," "));
			$printer.=(str_pad('<span style="">'.number_format(($OutletValue-$PackingCharges),2).'</span>',0," "));
			$printer.=("<br>");
			
			}
			if($PackingCharges){
			$printer.=(str_pad('<b>Packing Charges </b>',39.5," "));
			$printer.=(str_pad('<span style="">'.number_format($PackingCharges,2).'</span>',0," "));
			$printer.=("<br>");
			}
      
      $printer.=("</div>");

      //$sno=1;
    //  $printer.=("----------------------------------------<br>");
      $printer.=("<div style='float:left;margin-left:79px'>");
      $printer.="<div style=''>";
	  // $printer.=("<br>");
	   
	   //$printer.=(str_pad('Discount : ',26," "));
	   $printer.=(str_pad('<span style="float:left;">Discount : </span>',22," "));
      $printer.=(str_pad('<span style="float:right;">'.number_format($Discount,2).'</span>',14," "));
	  $printer.=("<br>");
	  foreach($ReportArray['PODDAYWISE']['Tax'] as $typename2=>$typevalue2){
	if($typevalue2>0){
	  
      $printer.=(str_pad('<span style="float:left;">'.$typename2.' :</span> ',18," "));
      $printer.=(str_pad('<span style="float:right;">'.(number_format($typevalue2,2)).'</span>',15," "));
	  $printer.=("<br>");
	}
}
	  
        $printer.=(str_pad('<b><span style="float:left;">Grand Total : </span>',22," "));
        $printer.=(str_pad('<span style="float:right;">'.$grant_total_amount.'</b></span>',15," "));
		$printer.=("<br>");
      
    
     $printer.="</div>";
      $printer.="</div>";
        $printer.=("<br>");

        $printer.=("<div style='float:left;'>");
        $printer.=("----------------------------------------<br>");
       $printer.=("<div style='width:200px;overflow:hidden;loat:left;margin-left:67px'>");
      $printer.=("<div>");
      $printer.=(str_pad('<b>Collections</b>',31," "));
      
foreach($ReportArray['PODDAYWISE']['Type'] as $typename=>$typevalue){
	if($typevalue>0){
	  $printer.=("<br>");
      $printer.=(str_pad($typename.' : ',20," "));
      $printer.=(str_pad('<span style="float:right;">'.$typevalue.'</span>',10," "));
	  
	}
}
   $printer.=("<br>");
        $printer.=(str_pad('<b>Total Collections : ',22," "));
        $printer.=(str_pad('<span style="float:right;">'.$grant_total_amount.'</b></span>',10," "));
		$printer.=("<br>");
	
		
        $printer.=("</div></div>");
        $printer.=("----------------------------------------<br>");


        $printer.=("</div>");
		$printer.=("<div style='width:100%'></div>");
                $printer.=("<br>");


        $printer.=("<br>");
			$DataList .= $printer; 
	$DataList .='<br><br><br><br><br><br><br>
</pre>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>';	
}
			  
			  
}

$FinalData.=$DataList;
$DataList='';
$printer='';
//Table View STARTS=======================================
$content ='';
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

  
	  
		
		$content .='<table class="table table-striped text-center">';
	$content .='<tr><th colspan="10" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$maintitle.' Flash Summary Report As On  '.date('d-m-Y',strtotime($ReportAsOnDate)).'</b></th></tr>';
    $content .='<tr>
    <th    style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Particulars</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Today</th>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">This Week</th>
    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">This Month</th>
    </tr>';
	
	
	
    
     //debugData($TableReportArray);
   
		
//Table View STARTS=======================================

	}
	if($id_report_type=='270'){
		
		foreach($PackingReport as $pks=>$packingChargeList){
		  $pks; $Packingli=array();$PackingCharges=array();
		foreach($packingChargeList  as $packingChargeList2){
			
			$Packingli[]= $packingChargeList2;
			}
			$listPackingidli	=implode(',',$Packingli);
	
	$PackingChargesli[$pks] = selectColumn('pos_purch_details','sum(item_amount*qty)'," WHERE  `item_description` like '%Packing%'  AND id_pos_purch IN (".$listPackingidli.") ");
		}
	
	//debugData($TableReportArray22);
	foreach($TableReportArray22['PODDAYWISE']  as  $rev=>$revlist1){
		
		if($rev=='Packing Charges'){
			$TableReportArray22='';
			$TableReportArray22=array();
			$TableReportArray22['PODDAYWISE'][$finalDayname]['Day']=$finalDay-$revlist1['Day'];
			$TableReportArray22['PODDAYWISE'][$finalDayname]['week']=$finalweek-$revlist1['week'];
			$TableReportArray22['PODDAYWISE'][$finalDayname]['mtd']=$finalmtd-$revlist1['mtd'];
			
			$TableReportArray22['PODDAYWISE'][$rev]['Day']=$revlist1['Day'];
			$TableReportArray22['PODDAYWISE'][$rev]['week']=$revlist1['week'];
			$TableReportArray22['PODDAYWISE'][$rev]['mtd']=$revlist1['mtd'];
		//debugData($revlist1);
		
		
		}else{
			$finalDay=$revlist1['Day'];
			$finalweek=$revlist1['week'];
			$finalmtd=$revlist1['mtd'];
			$finalDayname=$rev;
			}
		}
	
	//	debugData($PackingChargesli);
		
		//debugData($TableReportArray);
		$TableReportArray2222['PODDAYWISE']['Revenue']	=array_merge($TableReportArray22['PODDAYWISE'],$TableReportArray['PODDAYWISE']['Revenue']);
		$coll['Collections']=$TableReportArray['PODDAYWISE']['Collections'];
		$BillDetails['Bill Details']=$TableReportArray['PODDAYWISE']['Bill Details'];
		$TableReportArray4['PODDAYWISE']	=array_merge($TableReportArray2222['PODDAYWISE'],$coll);
		$TableReportArray222['PODDAYWISE']	=array_merge($TableReportArray4['PODDAYWISE'],$BillDetails);
	//debugData($TableReportArray222);
		
		foreach($TableReportArray222 as $dataList1){
			foreach($dataList1 as $dataHead=>$dataList2){
		$content .='<tr style="">';
   
    $content .='<td colspan="4" style="border:1px solid #000;background-color:#c2d69a;text-align:left; font-weight:bold; font-size:15px;">'.$dataHead.'</td>';
    
    $content .='
<tr>';

foreach($dataList2 as $dataHead2=>$dataList3){
	if(($dataList3['Day']>0 || $dataList3['week']>0 || $dataList3['mtd']>0) || $dataHead=='Bill Details'){
	$content .='<tr style=" font-size:15px;">';   
    $content .='<td style="border:1px solid #000;text-align:left;">'.$dataHead2.'</td>
    <td style="border:1px solid #000;text-align:right;">'.$dataList3['Day'].'</td>
    <td style="border:1px solid #000;text-align:right;">'.$dataList3['week'].'</td>';    
    $content .='<td style="border:1px solid #000;text-align:right;">'.$dataList3['mtd'].'</td>';
    $content .='</tr>';
	}
}



			}
	}
$content .= '</table><br/><br/>';		
$FinalData= $content;
// return $FinalData;	
}
				if($cronSet=='1'){
					
					//require_once '../../phplib/PHPExcel-1.8/Classes/PHPExcel.php';
					//include("'../../phplib/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
//$objPHPExcel = new PHPExcel();

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'ffffff'),
        'size'  => 11,
        'name'  => 'Verdana'
    ));
	$styleArrayBlack = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));	
$styleArray_1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '000000'),
        'size'  => 10,
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
$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");
	 $head_cntr = "A";
	$objPHPExcel->setActiveSheetIndex(0)
								
->setCellValue($head_cntr++.'2', 'Particulars')
->setCellValue($head_cntr++.'2', 'Today')
->setCellValue($head_cntr++.'2', 'This Week')
->setCellValue($head_cntr++.'2', 'This Month');
				
			
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	        'rgb' => '4a6129'
	    	)	
	    ));

$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	        'rgb' => 'c6d79c'
	    	)	
	    ));

$counter = 3;

foreach($TableReportArray222 as $dataList1){
			foreach($dataList1 as $dataHead=>$dataList2){
				$head_cntr_val = "A";
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue($head_cntr_val . $counter,$dataHead);
				
					$objPHPExcel->getActiveSheet()->getStyle($head_cntr_val . $counter,$dataHead)->getFont()->setBold( true );
	//cellColor($head_cntr_val++ . $counter++,'c6c6c6');
//cellColor('A1:D1','75923c',$objPHPExcel);
$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':D'.$counter)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	        'rgb' => 'c6d79c'
	    	)	
	    ));
$objPHPExcel->getActiveSheet()->getStyle('A'.$counter)->applyFromArray($styleArrayBlack);

$head_cntr_val++ ; $counter++;
foreach($dataList2 as $dataHead2=>$dataList3){
	if(($dataList3['Day']>0 || $dataList3['week']>0 || $dataList3['mtd']>0) || $dataHead=='Bill Details'){
	
	$head_cntr_val = "A";
	$objPHPExcel->setActiveSheetIndex(0)
->setCellValue($head_cntr_val++ . $counter,$dataHead2)
->setCellValue($head_cntr_val++ . $counter,$dataList3['Day']) 
->setCellValue($head_cntr_val++ . $counter,$dataList3['week'])
->setCellValue($head_cntr_val++ . $counter,$dataList3['mtd']);

if($dataHead2=='Grant Total'){
	$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':D'.$counter)->getFill()->applyFromArray(array(
	        'type' => PHPExcel_Style_Fill::FILL_SOLID,
	        'startcolor' => array(
	        'rgb' => 'c6d79c'
	    	)	
	    ));
$objPHPExcel->getActiveSheet()->getStyle('A'.$counter.':D'.$counter)->applyFromArray($styleArrayBlack);

	
	}
$counter++;
	}
}



			}
	}



	
$objPHPExcel->getActiveSheet($sheetTwelveIndex)->getColumnDimension('A')->setWidth(30);
$objPHPExcel->getActiveSheet($sheetTwelveIndex)->getColumnDimension('B')->setWidth(20);
$objPHPExcel->getActiveSheet($sheetTwelveIndex)->getColumnDimension('C')->setWidth(20);
$objPHPExcel->getActiveSheet($sheetTwelveIndex)->getColumnDimension('D')->setWidth(20);
$objPHPExcel->getActiveSheet()->mergeCells('A1:D1');
$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
$objPHPExcel->getActiveSheet()->getStyle('A2:'.$head_cntr_val.'2')->applyFromArray($styleArray_1);

$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	
	$objPHPExcel->getActiveSheet()->getStyle('A2:D2')->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

$objPHPExcel->getActiveSheet()->getStyle('A2:D'.($counter-1))->applyFromArray($styleThinBlackBorderOutline);	

$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->applyFromArray($styleThinBlackBorderOutline);






	
							
	
	$objPHPExcel->getActiveSheet(0)->setCellValue('A1',"Flash Summary Report As On  ".date('d-m-Y',strtotime($ReportAsOnDate)));
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');	
$objWriter->save('/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/'.$Filename.'.xls');
			//echo $path=$_SERVER['DOCUMENT_ROOT'];
				//echo '1212';die;
			}else{return $FinalData;
				}
	  //	return $FinalData;																								 
}
	


	
	?>