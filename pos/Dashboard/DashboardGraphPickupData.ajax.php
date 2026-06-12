<?php 
include_once("../../../config/auto_loader.php");
//print_r($_SESSION);

$sqlNat = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' ";
			$resToNat = mysqli_query($connNew,$sqlNat);
			$numRowsNat =  mysqli_num_rows($resToNat);
			$rowNat =  mysqli_fetch_object($resToNat);
			$enable_nationality= $rowNat->enable_nationality;	

//debugData($_POST);

//die;
//error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_POST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));


$ComparePeriodDateArray	=	explode('to',$_POST['ComparePeriod']);
$ComparePeriod_from = date('Y-m-d',strtotime($ComparePeriodDateArray[0]));
$ComparePeriod_to = date('Y-m-d',strtotime($ComparePeriodDateArray[1]));





$dateFromForm = DateTime::createFromFormat("Y-m-d", $from);
$FinacialYearFrom   =    $dateFromForm->format("Y");
$FinacialDayMonthFrom   =    $dateFromForm->format("d-m");

$dateToYear = DateTime::createFromFormat("Y-m-d", $to);
$FinacialYearTo  =    $dateToYear->format("Y");
$FinacialDayMonthTo   =    $dateToYear->format("d-m");

$CompareFromForm = DateTime::createFromFormat("Y-m-d", $ComparePeriod_from);
$FinacialCompareYearFrom   =    $CompareFromForm->format("Y");

$CompareToYear = DateTime::createFromFormat("Y-m-d", $ComparePeriod_to);
$FinacialCompareYearTo  =    $CompareToYear->format("Y");

//============================================================
//$Diffrence  =($FinacialYearTo-$FinacialCompareYearTo);
  $Diffrence='';
  $CompareFinancialYear	=	explode('-',$_POST['CompareFinancialYear']);
  $CurrentFinancialYear	=	explode('-',$_POST['CurrentFinancialYear']);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);

 

//strtotime('+1 day', $stop_date)
//print_r($_SESSION);
//print_r($_REQUEST);
$mtdLastValues = array();
$mtdThisValues = array();

$mtdVisits = array();
$mtdRoomRevenue = array();
$ytdRoomRevenue= array();
$mtdTotalExpense = array();
$mtdThisAllHotelValues= array();
$ytdAllHotelValues= array();

$mtdThisAllHotelValuesMAT= array();
$ytdAllHotelValuesMAT= array();
$MonthWiseRevenueCurrentYearDataMAT=array();
   $ytdPrevYearRevenueDataMAT=array();

$budgetValues = array();
$graphotelName=array();

$ytdLastValues = array();
$ytdThisValues = array();

$ytdVisits = array();
$ytdRateLetters = array();
$ytdTotalExpense = array();

$exeNameArr = array();
$returnData = array();

$stackedArr = array();
$stackedDataSet = array();
 $monthNameData=array();
// $monthNameDataQuarterly=array();
 $mtdRoomRevenueArr=array();
  $MonthWiseRoomNightsData=array();
  $MonthWiseRoomNightsLastYearData=array();
  $MonthWiseRevenueCurrentYearData=array();
      $ytdPrevYearRevenueData=array();
      $mtdRoomRevenueLastYearArr=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if($_POST['reportType']==1){	
	$reportfieldVarible=	'created_at';
}else{
	$reportfieldVarible=	'checkin_date';
	
	
	if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceEndYear=(date('Y') +1);
}
else {
    $year = (date('Y')-1)."-".date('Y');
}
 $to = date('31-03-'.$FinanceEndYear);
 
	}
if(date('m',strtotime($from))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($from)));
	$lastDate = date('Y-m-d',strtotime($from));
}
else{
	$startDate =date('Y-04-01',strtotime($from));
	$lastDate = date('Y-m-d',strtotime($from));
}

 $from_book=$from;
 $to_book=$to;


$reportPeriod = date('d-m-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($PeriodDateArray[1]));
$datePeriod = date('d-m-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($PeriodDateArray[1]));



		

   
   
   //Custom Report Start==============================================================================
     $sqlCustomeReport = "select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(ppp.qty,0) ELSE 0 END ) AS newConfirmed,
		SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS confimed_revenue,
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'" ;
       
       
      //echo $sqlCustomeReport;
      //die;
     //  
       
       $resultListCustomeReport = mysqli_query($connNew,$sqlCustomeReport);
	while($rowListCustomeReport = mysqli_fetch_object($resultListCustomeReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
		
	    $mtdThisCustomeReportValues2+=($rowListCustomeReport->newConfirmed+$rowListCustomeReport->newTentative);
	    $mtdRoomCustomeReportRevenue2+=round(($rowListCustomeReport->confimed_revenue+$rowListCustomeReport->tentative_revenue)/100000,2);

	}
		
    $mtdThisCustomeReportValues=array();
    $mtdRoomCustomeReportRevenue=array();
    
    array_push($mtdThisCustomeReportValues,$mtdThisCustomeReportValues2);
    array_push($mtdRoomCustomeReportRevenue,$mtdRoomCustomeReportRevenue2);
    //booking _date end
    
    
    
    $sqlCustomeLastYearReport = "select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(ppp.qty,0) ELSE 0 END ) AS newConfirmed,
		SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)))."' AND '".date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)))."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS confimed_revenue,
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2' ";
       
       
    //  echo 'LAST Year ='.$sqlCustomeLastYearReport;
    // die;
     //  
       
       $resultListCustomeLastYearReport = mysqli_query($connNew,$sqlCustomeLastYearReport);
	while($rowListCustomeLastYearReport = mysqli_fetch_object($resultListCustomeLastYearReport)){
	  
	    //$exeNameArr[]=ucwords(strtolower($rowList->name_executive));
	    $mtdThisCustomeLastYearReportValues2+=($rowListCustomeLastYearReport->newConfirmed+$rowListCustomeLastYearReport->newTentative);
	    $mtdRoomCustomeLastYearReportRevenue2+=round(($rowListCustomeLastYearReport->confimed_revenue+$rowListCustomeLastYearReport->tentative_revenue)/100000,2);
//round($ytdPrevYearRevenue/100000,2)
	}
		
    $mtdThisCustomeLastYearReportValues=array();
    $mtdRoomCustomeLastYearReportRevenue=array();
    
    array_push($mtdThisCustomeLastYearReportValues,$mtdThisCustomeLastYearReportValues2);
    array_push($mtdRoomCustomeLastYearReportRevenue,$mtdRoomCustomeLastYearReportRevenue2);
    //booking _date end
    
    
    
    //Custom Report End==============================================================================
   
   
  /***** Total Gone Days Calculatiing Days ****/
$days=1;
$weekends=1;

$totalDaysGoneMtd=1;
$totalDaysGoneYtd=1;

//YTD
if(date('m',strtotime($from))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($from)));
	$lastDate = date('Y-m-d',strtotime($from));
}
else{
	$startDate =date('Y-04-01',strtotime($from));
	$lastDate = date('Y-m-d',strtotime($from));
}

while($startDate <= $lastDate){

	$day = date("N",strtotime($startDate));
	if($day == 6 || $day == 7) {
	  $weekends++;
	} 

	$days++;
	$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
}
$totalDaysGoneYtd = $days-$weekends;
$startDate=date('Y-m-01',strtotime($from));
$PrevstartYr='';
$days=1;
$weekends=1;
// MTD
while($startDate <= $from){

	$day = date("N",strtotime($startDate));
	if($day == 6 || $day == 7) {
	  $weekends++;
	} 

	$days++;
	$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
}
$totalDaysGoneMtd = $days-$weekends;

/**************** END ***********************/ 
   
    
 if($_REQUEST['viewMonthwise']==1){
 $yr = $FinacialYearFrom;//date("Y");
    $start = date("".$yr."-04-01");
    $end =  date("".($yr+1)."-03-31");
    $startMo = date('m',strtotime($start));
    $endMo = date('m',strtotime($end));
    $startYr = date('Y',strtotime($start));
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $endYr = date('Y',strtotime($end));
    $diff = abs(($endMo-$startMo));
    $diffYr = ($endYr-$startYr);
    $reserve = array(date("d-m-Y",strtotime($start)),date("d-m-Y",strtotime($end)));
	$dataRN = array();
    $finalData = array();
  if($diffYr > 0){
    $diff = abs(($endMo+12-$startMo));
  }
 
 //$PrevstartYr = date('Y',strtotime('-1 years',strtotime($startYr)));
 $sqlSumConnCY='';
 $sqlSumConnLY='';
   //Yearly Graph Conditions
   $listmonthArray=array();
for($i = 0 ; $i <= $diff ; $i++){
	  $monthNUmers =  DateTime::createFromFormat('!m', $startMo);
	  $monthNUmers = $monthNUmers->format('m');
		if (date('m') > $monthNUmers ) {
		//$PrevstartYr = (date('Y') -1);
		
		}
		else {
		//$PrevstartYr = (date('Y')+1);	
		}
if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
     $startYr = $FinacialYearFrom + 1;
     $PrevstartYr = $ComparePeriod_from + 1;
	 ///$PrevstartYr = ($FinacialYearFrom);
}
else {
    $startYr = $FinacialYearFrom;
    $PrevstartYr= $ComparePeriod_from;
	///$PrevstartYr = ($FinacialYearFrom-1);
}		
		
if($_POST['id_hotel']>0){
	$ConnHotels=" and id_hotel='".$_POST['id_hotel']."'";
}else{
	$ConnHotels="";
	}
if ( date('m') > 6 ) {
    //echo  'first'.$year = $FinacialYearFrom + 1;
}else {
        //$checkYear=($FinacialYearFrom-1);
         $checkYear=$FinacialYearFrom;
        
        if ( date($monthNUmers) >= 1  &&    date($monthNUmers) <=3 ) {
        $startYr = date($checkYear) + 1;
        $PrevstartYr= date($ComparePeriod_from) + 1;
        ///$PrevstartYr = (date($checkYear));
        }
        else {
        $startYr = date($checkYear);
        $PrevstartYr= date($ComparePeriod_from);
        ///$PrevstartYr = (date($checkYear)-1);
        }
    
}
//echo '<br>'.$startYr.'====='.$PrevstartYr;
		$sqlSumConnCY	.="
		SUM(CASE WHEN  MONTH(pp.doc_date) = '".$monthNUmers."'  AND YEAR(pp.doc_date) = '".$startYr."' THEN ROUND(ppp.qty,0) ELSE 0 END ) AS newConfirmed_".$monthNUmers.",
		SUM(CASE WHEN  MONTH(pp.doc_date) = '".$monthNUmers."'  AND YEAR(pp.doc_date) = '".$startYr."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS confimed_revenue_".$monthNUmers.",
		
			
    		";
	
	
	$sqlSumConnLY	.="SUM(CASE WHEN  MONTH(pp.doc_date) = '".$monthNUmers."'  AND YEAR(pp.doc_date) = '".$PrevstartYr."' THEN ROUND(ppp.qty,0) ELSE 0 END ) AS newConfirmed_".$monthNUmers.",
		SUM(CASE WHEN  MONTH(pp.doc_date) = '".$monthNUmers."'  AND YEAR(pp.doc_date) = '".$PrevstartYr."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS confimed_revenue_".$monthNUmers.",
		
		
		
		";
			
	
	
	
   
   $listmonthArray[]=$monthNUmers;
   $listYearArray[]=$startYr;
   
     $startMo++;  
  }
  $sqlCurrentYearMonthWise="select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
".$sqlSumConnCY." 
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'  ";
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		echo $pos_purch_sql;//die;
	$resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
        $rowListCurrentYearMonthWise = mysqli_fetch_object($resultCurrentYearMonthWise);
        
        
        foreach($listmonthArray   as $monthkey=>$montharrayval){
            
            $ConnectVal = '_'.$montharrayval;
            $ConnectValnewConfirmed = 'newConfirmed_'.$montharrayval;
            
            $ConnectValconfimed_revenue = 'confimed_revenue_'.$montharrayval;
            
            
           // echo '<br>'.$rowListCurrentYearMonthWise->$ConnectValnewConfirmed;
            $MonthWiseRoomNightsCurrentYear=$rowListCurrentYearMonthWise->$ConnectValnewConfirmed+$rowListCurrentYearMonthWise->$ConnectValnewTentative;
            $MonthWiseRevenueCurrentYear =$rowListCurrentYearMonthWise->$ConnectValconfimed_revenue+$rowListCurrentYearMonthWise->$ConnectValtentative_revenue;
    $monthName =  DateTime::createFromFormat('!m', $montharrayval);
    $monthName = $monthName->format('F');
   
   array_push($monthNameData,$monthName);
   array_push($MonthWiseRoomNightsData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
   
            if($montharrayval==4 || $montharrayval==5 || $montharrayval==6){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ1+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ1+=round($MonthWiseRevenueCurrentYear/100000,2);
				} 
			if($montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ2+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ2+=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ3+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ3+=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
			if($montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$MonthWiseRoomNightsCurrentYearQuarterlyQ4+=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearQuarterlyQ4+=round($MonthWiseRevenueCurrentYear/100000,2);;
				}
				//half year======================
			 if($montharrayval==4 || $montharrayval==5 || $montharrayval==6 || $montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$MonthWiseRoomNightsCurrentYearHalfYearH1 +=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearHalfYearH1 +=round($MonthWiseRevenueCurrentYear/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12 || $montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$MonthWiseRoomNightsCurrentYearHalfYearH2 +=$MonthWiseRoomNightsCurrentYear;
				$MonthWiseRevenueCurrentYearHalfYearH2 +=round($MonthWiseRevenueCurrentYear/100000,2);;
				} 
		$monthNameDataQuarterly=array('Q1','Q2','Q3','Q4');
   //array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear/100000,2));
  
  
   $MonthWiseRoomNightsCurrentYear2  += $MonthWiseRoomNightsCurrentYear;
   $MonthWiseRevenueCurrentYear2  += $MonthWiseRevenueCurrentYear;
   array_push($mtdThisAllHotelValuesMAT,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear2);
   array_push($MonthWiseRevenueCurrentYearDataMAT,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear2/100000,2));
   array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRevenueCurrentYear==''?0:round($MonthWiseRevenueCurrentYear/100000,2));
            
       if($MonthWiseRoomNightsCurrentYear>0 && $MonthWiseRevenueCurrentYear>0){
	$mtdRoomRevenueArr2  =round($MonthWiseRevenueCurrentYear/$MonthWiseRoomNightsCurrentYear);
	array_push($mtdRoomRevenueArr,$mtdRoomRevenueArr2);
	}else{
		array_push($mtdRoomRevenueArr,0);
		} }
        //die;
        
        //print_r($monthNameData);
	 	////print_r($MonthWiseRoomNightsData);
	 	//die;
		$sqlPrevYearMonthWise="select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
".$sqlSumConnLY." 
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'  ";
	 	
       
       
    
       
      // echo $sqlPrevYearMonthWise;
       //die;
       
     // $graphotelName[]='Hotels';  
    $resultPrevYearMonthWise = mysqli_query($connNew,$sqlPrevYearMonthWise);
    $rowListPrevYearMonthWise = mysqli_fetch_object($resultPrevYearMonthWise);
    
    
     foreach($listmonthArray   as $monthkey=>$montharrayval){
    
     $ConnectVal = '_'.$montharrayval;
     
      $ConnectVal = '_'.$montharrayval;
            $ConnectVallastYearnewConfirmed = 'newConfirmed_'.$montharrayval;
            //$ConnectVallastYearnewTentative = 'newTentative_'.$montharrayval;
            
            $ConnectVallastYearconfimed_revenue = 'confimed_revenue_'.$montharrayval;
            
           // $ConnectVallastYeartentative_revenue = 'tentative_revenue_'.$montharrayval;
     
    //$ytdPrevYearRoomNights=$rowListPrevYearMonthWise->$ConnectVallastYearnewConfirmed+$rowListPrevYearMonthWise->$ConnectVallastYearnewTentative;
    //$ytdPrevYearRevenue =$rowListPrevYearMonthWise->$ConnectVallastYearconfimed_revenue+$rowListPrevYearMonthWise->$ConnectVallastYeartentative_revenue;
    
    
     if($montharrayval==4 || $montharrayval==5 || $montharrayval==6){
				$ytdPrevYearRoomNightsQuarterlyQ1+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ1+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==7 || $montharrayval==8 || $montharrayval==9){
				$ytdPrevYearRoomNightsQuarterlyQ2+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ2+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12){
				$ytdPrevYearRoomNightsQuarterlyQ3+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ3+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$ytdPrevYearRoomNightsQuarterlyQ4+=$ytdPrevYearRoomNights;
				$ytdPrevYearRevenueQuarterlyQ4+=round($ytdPrevYearRevenue/100000,2);
				} 
			 if($montharrayval==4 || $montharrayval==5 || $montharrayval==6 || $montharrayval==7 || $montharrayval==8 || $montharrayval==9){
					$ytdPrevYearRoomNightsHalfYearH1+=$ytdPrevYearRoomNights;
				    $ytdPrevYearRevenueHalfYearH1+=round($ytdPrevYearRevenue/100000,2);
				} 
			if($montharrayval==10 || $montharrayval==11 || $montharrayval==12 || $montharrayval==1 || $montharrayval==2 || $montharrayval==3){
				$ytdPrevYearRoomNightsHalfYearH2+=$ytdPrevYearRoomNights;
				    $ytdPrevYearRevenueHalfYearH2+=round($ytdPrevYearRevenue/100000,2);
				} 
			
			
			
				
				
   array_push($MonthWiseRoomNightsLastYearData,$ytdPrevYearRoomNights==''?0:$ytdPrevYearRoomNights);
     
    $ytdPrevYearRoomNights2  += $ytdPrevYearRoomNights;
     $ytdPrevYearRevenue2  += $ytdPrevYearRevenue;
    array_push($ytdPrevYearRevenueDataMAT,$ytdPrevYearRevenue==''?0:round($ytdPrevYearRevenue2/100000,2));
   array_push($ytdAllHotelValuesMAT,$ytdPrevYearRoomNights==''?0:$ytdPrevYearRoomNights2);
   
   array_push($ytdPrevYearRevenueData,$ytdPrevYearRevenue==''?0:round($ytdPrevYearRevenue/100000,2));      
     
   
 	//$mtdThisAllHotelValues[]=($mtdThisAllHotelValuesResult==''?0:$mtdThisAllHotelValuesResult);
	//$ytdAllHotelValues[]=($ytdAllHotelValuesResult==''?0:$ytdAllHotelValuesResult);
	
	 //ARR===============================
     
	 }
		
		if($MonthWiseRoomNightsLastYearData>0  && $ytdPrevYearRevenue>0){
	$mtdRoomRevenueArrLastYear2  =round($ytdPrevYearRevenue/$ytdPrevYearRoomNights);
	array_push($mtdRoomRevenueLastYearArr,$mtdRoomRevenueArrLastYear2);
	}else{
		array_push($mtdRoomRevenueLastYearArr,0);
		}	
		
     }	
	//ARR===============================
   
     
  //Yearly Graph Condition End
    $MonthWiseRoomNightsCurrentYearQuarterly=array($MonthWiseRoomNightsCurrentYearQuarterlyQ1,$MonthWiseRoomNightsCurrentYearQuarterlyQ2,$MonthWiseRoomNightsCurrentYearQuarterlyQ3,$MonthWiseRoomNightsCurrentYearQuarterlyQ4);
 $MonthWiseRevenueCurrentYearQuarterly=array($MonthWiseRevenueCurrentYearQuarterlyQ1,$MonthWiseRevenueCurrentYearQuarterlyQ2,$MonthWiseRevenueCurrentYearQuarterlyQ3,$MonthWiseRevenueCurrentYearQuarterlyQ4);
 
 
 $ytdPrevYearRoomNightsQuarterly=array($ytdPrevYearRoomNightsQuarterlyQ1,$ytdPrevYearRoomNightsQuarterlyQ2,$ytdPrevYearRoomNightsQuarterlyQ3,$ytdPrevYearRoomNightsQuarterlyQ4);
 $ytdPrevYearRevenueQuarterly=array($ytdPrevYearRevenueQuarterlyQ1,$ytdPrevYearRevenueQuarterlyQ2,$ytdPrevYearRevenueQuarterlyQ3,$ytdPrevYearRevenueQuarterlyQ4);
 
 
$MonthWiseRoomNightsCurrentYearHalfYear=array($MonthWiseRoomNightsCurrentYearHalfYearH1,$MonthWiseRoomNightsCurrentYearHalfYearH2);
 $MonthWiseRevenueCurrentYearHalfYear=array($MonthWiseRevenueCurrentYearHalfYearH1,$MonthWiseRevenueCurrentYearHalfYearH2);
 
 
 $ytdPrevYearRoomNightsHalfYear=array($ytdPrevYearRoomNightsHalfYearH1,$ytdPrevYearRoomNightsHalfYearH2);
 $ytdPrevYearRevenueHalfYear=array($ytdPrevYearRevenueHalfYearH1,$ytdPrevYearRevenueHalfYearH2);
 
			
    //	echo '<pre>';
			//	array_sum($MonthWiseRoomNightsCurrentYearQuarterly);
			//	print_r($MonthWiseRoomNightsCurrentYearQuarterly);
			//	echo '</pre>';   
	
 //Month Wise End============================================



















//===========================Booking CompleteChar horizontalBar===============================================================
$setFormat = date_create( date('Y-m-d'));
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

 $sqlHor = "select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,

SUM(CASE WHEN ( pp.doc_date between '".$from_month_to_date."' and '".$to_month_to_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `MTDThisYearRoomNights`,
SUM(CASE WHEN ( pp.doc_date between '".$last_year_from_month_date."' and '".$last_year_to_month_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `MTDLastYearRoomNights`,

SUM(CASE WHEN ( pp.doc_date = '".$yesterdaysDate."' ) THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `yesterdayThisYearRoomNights`,
SUM(CASE WHEN ( pp.doc_date = '".$last_year_yesterdaysDate."' ) THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `yesterdayLastYearRoomNights`,

SUM(CASE WHEN ( pp.doc_date between '".$from_year_to_date."' and '".$to_year_to_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `YTDThisYearRoomNights`,
SUM(CASE WHEN ( pp.doc_date between '".$last_year_from_year_date."' and '".$last_year_from_year_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `YTDLastYearRoomNights`,

SUM(CASE WHEN ( pp.doc_date = '".$current_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `ThisYearConfirmandTend`,
SUM(CASE WHEN ( pp.doc_date = '".$last_year_current_date."' ) THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `LastYearConfirmandTend`,

SUM(CASE WHEN ( pp.doc_date between '".$QuarterThisYearstart_date."' and '".$QuarterThisYearlast_date."') THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `QTYThisYearRoomNights`,
SUM(CASE WHEN ( pp.doc_date between '".$Quarterlast_yearstart_date."' and '".$Quarterlast_yeartart_date."' ) THEN ROUND(ppp.qty,0) ELSE 0 END ) AS `QTYLastYearRoomNights`,
		



SUM(CASE WHEN ( pp.doc_date between '".$from_month_to_date."' and '".$to_month_to_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `MTDThisYearRevenue`,
SUM(CASE WHEN ( pp.doc_date between '".$last_year_from_month_date."' and '".$last_year_to_month_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `MTDLastYearRevenue`,

SUM(CASE WHEN ( pp.doc_date = '".$yesterdaysDate."' ) THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `yesterdayThisYearRevenue`,
SUM(CASE WHEN ( pp.doc_date = '".$last_year_yesterdaysDate."' ) THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `yesterdayLastYearRevenue`,

SUM(CASE WHEN ( pp.doc_date between '".$from_year_to_date."' and '".$to_year_to_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `YTDThisYearRevenue`,
SUM(CASE WHEN ( pp.doc_date between '".$last_year_from_year_date."' and '".$last_year_from_year_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `YTDLastYearRevenue`,

SUM(CASE WHEN ( pp.doc_date = '".$current_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `ThisYearConfirmandTendRevenue`,
SUM(CASE WHEN ( pp.doc_date = '".$last_year_current_date."' ) THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `LastYearConfirmandTendRevenue`,

SUM(CASE WHEN ( pp.doc_date between '".$QuarterThisYearstart_date."' and '".$QuarterThisYearlast_date."') THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `QTYThisYearRevenue`,
SUM(CASE WHEN ( pp.doc_date between '".$Quarterlast_yearstart_date."' and '".$Quarterlast_yeartart_date."' ) THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS `QTYLastYearRevenue`,




		 
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'  
       ";
       //echo $sqlHor;
    // die;
    $resultListHor = mysqli_query($connNew,$sqlHor);
	$rowListHor = mysqli_fetch_object($resultListHor);
	//print_r($rowListHor);die;
$rowListHorThisYear =	array($rowListHor->ThisYearConfirmandTend,$rowListHor->yesterdayThisYearRoomNights,$rowListHor->MTDThisYearRoomNights,$rowListHor->QTYThisYearRoomNights,	$rowListHor->YTDThisYearRoomNights);

$rowListHorLastYear =	array($rowListHor->LastYearConfirmandTend,$rowListHor->yesterdayLastYearRoomNights,$rowListHor->MTDLastYearRoomNights,$rowListHor->QTYLastYearRoomNights,$rowListHor->YTDLastYearRoomNights);



$rowListHorThisYearRevenue =	array($rowListHor->ThisYearConfirmandTendRevenue>0?round($rowListHor->ThisYearConfirmandTendRevenue/100000,2):'0',$rowListHor->yesterdayThisYearRevenue>0?round($rowListHor->yesterdayThisYearRevenue/100000,2):'0',$rowListHor->MTDThisYearRevenue>0?round($rowListHor->MTDThisYearRevenue/100000,2):'0',$rowListHor->QTYThisYearRevenue>0?round($rowListHor->QTYThisYearRevenue/100000,2):'0',$rowListHor->YTDThisYearRevenue>0?round($rowListHor->YTDThisYearRevenue/100000,2):'0');

$rowListHorLastYearRevenue =	array($rowListHor->LastYearConfirmandTendRevenue>0?round($rowListHor->LastYearConfirmandTendRevenue/100000,2):'0',$rowListHor->yesterdayLastYearRevenue>0?round($rowListHor->yesterdayLastYearRevenue/100000,2):'0',$rowListHor->MTDLastYearRevenue>0?round($rowListHor->MTDLastYearRevenue/100000,2):'0',$rowListHor->QTYLastYearRevenue>0?round($rowListHor->QTYLastYearRevenue/100000,2):'0',$rowListHor->YTDLastYearRevenue>0?round($rowListHor->YTDLastYearRevenue/100000,2):'0');

$rowListHorThisYearARR =	array($rowListHor->ThisYearConfirmandTend>0?round(($rowListHor->ThisYearConfirmandTendRevenue/$rowListHor->ThisYearConfirmandTend)):'0',$rowListHor->yesterdayThisYearRoomNights>0?round(($rowListHor->yesterdayThisYearRevenue/$rowListHor->yesterdayThisYearRoomNights)):'0',$rowListHor->MTDThisYearRoomNights>0?round(($rowListHor->MTDThisYearRevenue/$rowListHor->MTDThisYearRoomNights)):'0',$rowListHor->QTYThisYearRoomNights>0?round(($rowListHor->QTYThisYearRevenue/$rowListHor->QTYThisYearRoomNights)):'0',$rowListHor->YTDThisYearRoomNights>0?round(($rowListHor->YTDThisYearRevenue/$rowListHor->YTDThisYearRoomNights)):'0');


$rowListHorLastYearARR =	array($rowListHor->LastYearConfirmandTend>0?round(($rowListHor->LastYearConfirmandTendRevenue/$rowListHor->LastYearConfirmandTend)):'0',$rowListHor->yesterdayLastYearRoomNights>0?round(($rowListHor->yesterdayLastYearRevenue/$rowListHor->yesterdayLastYearRoomNights)):'0',$rowListHor->MTDLastYearRoomNights>0?round(($rowListHor->MTDLastYearRevenue/$rowListHor->MTDLastYearRoomNights)):'0',$rowListHor->QTYLastYearRoomNights>0?round(($rowListHor->QTYLastYearRevenue/$rowListHor->QTYLastYearRoomNights)):'0',$rowListHor->YTDLastYearRoomNights>0?round(($rowListHor->YTDLastYearRevenue/$rowListHor->YTDLastYearRoomNights)):'0');


$rowListHorName =	array('Today','Yesterday','MTD','QTD','YTD');

//	array($rowListHor->MTDThisYearRoomNights,);

//==============================Booking CompleteChar==horizontalBar end ====================================================



//Nationality==========================================================>
$sqlNationality = "select pp.id_mst_country_lang,pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(ppp.qty,0) ELSE 0 END ) AS qty,
		SUM(CASE WHEN  DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS revenue,
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2' group by pp.id_mst_country_lang" ;
       
       
     // echo $sqlNationality;
     // die;
     //  
       $NationalityNameArr=array();
	    $mtdThisNationalityReportValues=array();
    $mtdRoomNationalityReportRevenue=array();
       $resultListNationality = mysqli_query($connNew,$sqlNationality);
	while($rowListNationality = mysqli_fetch_object($resultListNationality)){
	 $revenueNat= round(($rowListNationality->revenue)/100000,2);
	 $naname= $rowListNationality->id_mst_country_lang=='0'?'Others':ucwords(strtolower(selectColumn(TBL_COUNTRY_LANG,'nationality'," WHERE status = '1' AND `id_lang` = '1' AND nationality!='' AND id_country= '".$rowListNationality->id_mst_country_lang."'") ));
	    array_push($NationalityNameArr,$naname.'('.$revenueNat.')');//$rowListNationality->id_mst_country_lang=='0'?'Others':ucwords(strtolower(selectColumn(TBL_COUNTRY_LANG,'nationality'," WHERE status = '1' AND `id_lang` = '1' AND nationality!='' AND id_country= '".$rowListNationality->id_mst_country_lang."'") )).'('.$revenueNat.')');
		
	    $mtdThisNationalityReportValues2=($rowListNationality->qty);
	    $mtdRoomNationalityReportRevenue2=round(($rowListNationality->revenue)/100000,2);
	array_push($mtdThisNationalityReportValues,$mtdThisNationalityReportValues2);
    array_push($mtdRoomNationalityReportRevenue,$mtdRoomNationalityReportRevenue2);
	}
		
   
    
    
//Nationality END======================================================>


//=============================
$returnData['totalDaysGoneMtd']=$totalDaysGoneMtd;
$returnData['totalDaysGoneYtd']=$totalDaysGoneYtd;

$returnData['stacked']=$stackedArr;
$returnData['mtdThisVal']=$mtdThisValues;
$returnData['mtdLastVal']=$mtdLastValues;
$returnData['mtdThisAllHotelValues']=$mtdThisAllHotelValues;
$returnData['ytdAllHotelValues']=$ytdAllHotelValues;




$returnData['graphotelName']=$graphotelName;

$returnData['ytdPrevYearAllHotelValue']=$ytdPrevYearAllHotelValue;
$returnData['ytdAchievedAllHotelValue']=$ytdAchievedAllHotelValue;

$returnData['MtdRevenueAllHotelValue']=$MtdRevenueAllHotelValue;
$returnData['ytdRevenueAllHotelValue']=$ytdRevenueAllHotelValue;

$returnData['ytdPrevYearRevenuAllHotelLastYearValue']=$ytdPrevYearRevenuAllHotelLastYearValue;
$returnData['ytdRevenueAllHotelThisYearValue']=$ytdRevenueAllHotelThisYearValue;

$returnData['budgetVal']=$budgetValues;

$returnData['executives']=$exeNameArr;

$returnData['ytdLastVal']=$ytdLastValues;
$returnData['ytdThisVal']=$ytdThisValues;

$returnData['mtdVisits']=$mtdVisits;
$returnData['mtdRoomRevenue']=$mtdRoomRevenue;
$returnData['ytdRoomRevenue']=$ytdRoomRevenue;

$returnData['mtdTotalExpense']=$mtdTotalExpense;

$returnData['ytdVisits']=$ytdVisits;
$returnData['ytdRateLetters']=$ytdRateLetters;
$returnData['ytdTotalExpense']=$ytdTotalExpense;
$returnData['reportPeriod']=$reportPeriod;
$returnData['datePeriod']=$datePeriod;

$returnData['monthNameData']=$monthNameData;

$returnData['MonthWiseRoomNightsData']=$MonthWiseRoomNightsData;
$returnData['MonthWiseRoomNightsLastYearData']=$MonthWiseRoomNightsLastYearData;
$returnData['MonthWiseRevenueCurrentYearData']=$MonthWiseRevenueCurrentYearData;
$returnData['ytdPrevYearRevenueData']=$ytdPrevYearRevenueData;

$returnData['mtdThisAllHotelValuesMat']=$mtdThisAllHotelValuesMAT;
$returnData['ytdAllHotelValuesMat']=$ytdAllHotelValuesMAT; 

$returnData['MonthWiseRevenueCurrentYearDataMat']=$MonthWiseRevenueCurrentYearDataMAT;
$returnData['ytdPrevYearRevenueDataMat']=$ytdPrevYearRevenueDataMAT;
$returnData['mtdRoomRevenueArr']=$mtdRoomRevenueArr;

$returnData['OfferNameArray']=$OfferNameArray;
$returnData['rowOfferListArray']=$rowOfferListArray;
$returnData['mtdRoomRevenueLastYearArr']=$mtdRoomRevenueLastYearArr;
//COmpany Group
$returnData['CompanyGroupNameArray']=$CompanyGroupNameArray;
$returnData['CompanyGroupListArray']=$CompanyGroupListArray;
$returnData['CompanyGroupListLastYearArray']=$CompanyGroupListLastYearArray;
$returnData['SegmentWiseListLastYearArray']=$SegmentWiseListLastYearArray;


$returnData['mtdThisCustomeReportValues']=$mtdThisCustomeReportValues;
$returnData['mtdRoomCustomeReportRevenue']=	$mtdRoomCustomeReportRevenue;
$returnData['mtdThisCustomeLastYearReportValues']=	$mtdThisCustomeLastYearReportValues;
$returnData['mtdRoomCustomeLastYearReportRevenue']=	   $mtdRoomCustomeLastYearReportRevenue;


$returnData['BookingThroughNameArray']=	$BookingThroughNameArray;
$returnData['BookingThroughCurrentYearValue']=	$BookingThroughListArray;
$returnData['rowBookingThroughLastYearValue']=	   $BookingThroughListLastYearArray;
$returnData['horizontalBarThisYear']=	   $rowListHorThisYear;
$returnData['horizontalBarLastYear']=	   $rowListHorLastYear;

$returnData['horizontalBarThisYearAVGRoomRevenue']=	   $rowListHorThisYearARR;
$returnData['horizontalBarLastYearAVGRoomRevenue']=	   $rowListHorLastYearARR;


$returnData['horizontalBarThisYearRevenue']=	   $rowListHorThisYearRevenue;
$returnData['horizontalBarLastYearRevenue']=	   $rowListHorLastYearRevenue;

$returnData['horizontalBarName']=	   $rowListHorName;
$returnData['monthNameDataQuarterly']=$monthNameDataQuarterly;

$returnData['MonthWiseRoomNightsCurrentYearQuarterly']=$MonthWiseRoomNightsCurrentYearQuarterly;
$returnData['MonthWiseRevenueCurrentYearQuarterly']=$MonthWiseRevenueCurrentYearQuarterly;
$returnData['ytdPrevYearRoomNightsQuarterly']=$ytdPrevYearRoomNightsQuarterly;
$returnData['ytdPrevYearRevenueQuarterly']= $ytdPrevYearRevenueQuarterly;

$returnData['MonthWiseRoomNightsCurrentYearHalfYear']=$MonthWiseRoomNightsCurrentYearHalfYear;
$returnData['MonthWiseRevenueCurrentYearHalfYear']= $MonthWiseRevenueCurrentYearHalfYear;
$returnData['ytdPrevYearRoomNightsHalfYear']=  $ytdPrevYearRoomNightsHalfYear;
$returnData['ytdPrevYearRevenueHalfYear']= $ytdPrevYearRevenueHalfYear;
$returnData['monthNameDataHalfYear']=$monthNameDataHalfYear =array('H1','H2');
$returnData['CYLable']= $_POST['CurrentFinancialYear'];$FinacialYearFrom.'-'.$FinacialYearTo;
$returnData['LYLable']= $_POST['CompareFinancialYear'];$FinacialCompareYearFrom.'-'.$FinacialCompareYearTo;

$returnData['NationalityNameArr']=$NationalityNameArr;
$returnData['NationalityQtyArr']=$mtdThisNationalityReportValues;
$returnData['NationalityRevenueArr']=  $mtdRoomNationalityReportRevenue;

$mtdThisValuesAll=array();
$lable='All';
array_push($mtdThisValuesAll,$lable);
$returnData['CustomeReportValuesName']=$mtdThisValuesAll;
$returnData['enable_nationality']=$enable_nationality;
$reportViewGrapTable=1;
	
//$returnData['testing']='statushub1';

$_REQUEST['id_group_master']=	"0";
$_REQUEST['reportType']=	"1";
$_REQUEST['viewMonthwise']=	"2";
$_REQUEST['summaryReportType']='62';
	
//print_r($_REQUEST);
                               
	$result = $returnData;
	//$result = array_merge($returnData, $returnData32);
	//print_r($result);die;
echo json_encode($result);


 