<?php 
include_once("../../../config/auto_loader.php");
//print_r($_SESSION);

$sqlNat = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' ";
$resToNat = mysqli_query($connNew,$sqlNat);
$numRowsNat =  mysqli_num_rows($resToNat);
$rowNat =  mysqli_fetch_object($resToNat);
$enable_nationality= $rowNat->enable_nationality;	
  $course_hour = "1"; // 1 || 1.5 || 2
$starttime = "07:00";
$endtime = "24:00";
 

 
$TimeArray	=(get_time_range($course_hour, $starttime, $endtime));



function get_time_range($course_hour, $starttime, $endtime){
  $to_add = '';
  switch($course_hour):
    case 1:
     $to_add = " +1 hours";
     break;
   case 1.5:
     $to_add = " +1 hours 30 minutes";
     break;
   case 2:
     $to_add = " +2 hours";
     break;
   default:
     $to_add = " +1 hours";
     break;
  endswitch;

 // get the total hours
 $from_time = strtotime($starttime); 
 $to_time = strtotime($endtime); 
 $diff_mins = round(abs($from_time - $to_time)/60);
 $diff_hours = $diff_mins/60;

 $hours_arr =array();

 for($i=1; $i<=$diff_hours; $i+= $course_hour):

   $new_end_strtime = strtotime($starttime . $to_add);
   $endtime = date('H:i', $new_end_strtime);

   if($new_end_strtime <= $to_time): // checking the last end time is not greater than defined $endtime
     $str = $starttime . ' - '. $endtime;
     $tmp = strtotime($starttime . $to_add);
     $starttime = date('H:i', $tmp);
     array_push($hours_arr, $str);
   endif;

  endfor;

  return $hours_arr;
}
//debugData($_POST);

//die;
//error_reporting(E_ALL);
$PeriodDateArray	=	explode('to',$_POST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));


//date('Y-m-d', strtotime('+1 days',strtotime($PeriodDateArray)));


  //die;

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
  $MonthWiseRoomSubTotalItemsData=array();
  $MonthWiseRoomNightsLastYearData=array();
  $MonthWiseRevenueCurrentYearData=array();
      $ytdPrevYearRevenueData=array();
      $mtdRoomRevenueLastYearArr=array();


$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';




//die;

/**************** END ***********************/ 
   

 
 //$PrevstartYr = date('Y',strtotime('-1 years',strtotime($startYr)));
 $sqlSumConnCY='';
 $sqlSumConnLY='';
   //Yearly Graph Conditions
  

  
  $listmonthArray='';
$sqlSumConnCY1	="";
$listmonthArray=array();

$startDate=$from;
	foreach($TimeArray as $TimeKey=>$TimeValue){
		//echo '=='.$TimeValue;
	$TimeData	     =	explode(' - ',$TimeValue);//echo $TimeData['0'];
	$TimeDataSplit	=	explode(':',$TimeData[0]);//echo date('H'.':00:00',strtotime($TimeData[0]));//die;
	
			$listmonthArray[]=$TimeData[0];
	
	}
//while($startDate <= $to){

	  $day = date("d",strtotime($startDate)).'_'.date("m",strtotime($startDate));
	
	
foreach($TimeArray as $TimeKey=>$TimeValue){
	
	//echo '=='.$TimeValue;
	$TimeData	     =	explode(' - ',$TimeValue);//echo $TimeData['0'];
	$TimeDataSplit	=	explode(':',$TimeData[0]);//echo date('H'.':00:00',strtotime($TimeData[0]));//die;
	//SUM(CASE WHEN TIME(pp.date_created)>= '".date('H:i:s',strtotime($TimeData[0]))."'  AND TIME(pp.date_created) <= '".date('H:i:s',strtotime($TimeData[1]))."'  and DATE(pp.doc_date)='".date('Y-m-d',strtotime($startDate))."' THEN ROUND(((ppp.qty*ppp.item_amount)-ppp.item_discount_amount),0) ELSE 0 END ) AS confimed_revenue_".$TimeDataSplit[0].'_'.$day.",
		
	$sqlSumConnCY1   .="
		SUM(CASE WHEN  TIME(pp.date_created)  >= '".date('H:i:s',strtotime($TimeData[0]))."' AND TIME(pp.date_created) <= '".date('H:i:s',strtotime($TimeData[1]))."'  and DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($startDate))."' AND '".date('Y-m-d',strtotime($to))."' THEN ROUND(pp.pax,0) ELSE 0 END ) AS newConfirmed_".$TimeDataSplit[0].'_'.$day.",
		SUM(CASE WHEN  TIME(pp.date_created)  >= '".date('H:i:s',strtotime($TimeData[0]))."' AND TIME(pp.date_created) <= '".date('H:i:s',strtotime($TimeData[1]))."'  and DATE(pp.doc_date) BETWEEN '".date('Y-m-d',strtotime($startDate))."' AND '".date('Y-m-d',strtotime($to))."' THEN ROUND(pp.sub_total_items,0) ELSE 0 END ) AS sub_total_items_".$TimeDataSplit[0].'_'.$day.",
";
			//$listmonthArray[]=$TimeData[0];
	}
//$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
//}

  $sqlCurrentYearMonthWise="select pp.doc_date,
".$sqlSumConnCY1." pp.kot_doc_no

FROM pos_purch pp 

WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'  ";
  //debugData($listmonthArray);
  /*$sqlCurrentYearMonthWise="select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description, ppp.id as id_purch_detail,inv.item_code, ppp.qty,  ppp.item_discount_amount,
".$sqlSumConnCY1." 
ppp.item_amount
FROM pos_purch pp 
LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id 

INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items 
WHERE pp.pos_bill_type='2' and pp.cancelled=0 and pp.doc_type=21 and pp.id_shop= '2'  ";*/
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		//echo $sqlCurrentYearMonthWise;die;
	$resultCurrentYearMonthWise = mysqli_query($connNew,$sqlCurrentYearMonthWise);
        $rowListCurrentYearMonthWise = mysqli_fetch_object($resultCurrentYearMonthWise);
       // print_r($rowListCurrentYearMonthWise);
		
		$startDate=$from;
     // while($startDate <= $to){

	  $day = date("d",strtotime($startDate)).'_'.date("m",strtotime($startDate)); 
	 
        foreach($listmonthArray   as $monthkey=>$montharrayval){ //echo $montharrayval.'========';
            
			$TimeData	=	explode(' - ',$montharrayval);//echo $TimeData['0'];
			$TimeDataSplit	=	explode(':',$TimeData[0]);
			$montharrayval =$TimeDataSplit[0];
			
            $ConnectVal = '_'.$montharrayval;
            $ConnectValnewConfirmed = 'newConfirmed_'.$montharrayval.'_'.$day;
            
            $ConnectValSubTotalAmount = 'sub_total_items_'.$montharrayval.'_'.$day;
            
            
           // echo '<br>'.$rowListCurrentYearMonthWise->$ConnectValnewConfirmed;
            $MonthWiseRoomNightsCurrentYear=$rowListCurrentYearMonthWise->$ConnectValnewConfirmed;
            $MonthWiseRevenueCurrentYear =$rowListCurrentYearMonthWise->$ConnectValSubTotalAmount;
    //$monthName =  DateTime::createFromFormat('!m', $montharrayval);
    $monthName = date("g:i a", strtotime(date('H:i:s',strtotime($TimeData[0]))));//$montharrayval; //$monthName->format('F');
   
   array_push($monthNameData,$monthName);
   array_push($MonthWiseRoomNightsData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
   array_push($MonthWiseRoomSubTotalItemsData,$MonthWiseRevenueCurrentYear==''?0:$MonthWiseRevenueCurrentYear);
   
   
  
   $MonthWiseRoomNightsCurrentYear2  += $MonthWiseRoomNightsCurrentYear;
   $MonthWiseRevenueCurrentYear2  += $MonthWiseRevenueCurrentYear;
   array_push($MonthWiseRevenueCurrentYearData,$MonthWiseRoomNightsCurrentYear==''?0:$MonthWiseRoomNightsCurrentYear);
            
       if($MonthWiseRoomNightsCurrentYear>0 && $MonthWiseRevenueCurrentYear>0){
	$mtdRoomRevenueArr2  =round($MonthWiseRevenueCurrentYear/$MonthWiseRoomNightsCurrentYear);
	array_push($mtdRoomRevenueArr,$mtdRoomRevenueArr2);
	}else{
		array_push($mtdRoomRevenueArr,0);
		} 
		
		}
       // $startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
	// }
        
        //print_r($monthNameData);
	 	////print_r($MonthWiseRoomNightsData);
	 	//die;
	
		
		if($MonthWiseRoomNightsLastYearData>0  && $ytdPrevYearRevenue>0){
	$mtdRoomRevenueArrLastYear2  =round($ytdPrevYearRevenue/$ytdPrevYearRoomNights);
	array_push($mtdRoomRevenueLastYearArr,$mtdRoomRevenueArrLastYear2);
	}else{
		array_push($mtdRoomRevenueLastYearArr,0);
		}	
	
	
    	
	//ARR===============================
   

			
    //	echo '<pre>';
			//	array_sum($MonthWiseRoomNightsCurrentYearQuarterly);
			//	print_r($MonthWiseRoomNightsCurrentYearQuarterly);
			//	echo '</pre>';   
	
 //Month Wise End============================================























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
$returnData['MonthWiseRoomSubTotalItems']=$MonthWiseRoomSubTotalItemsData;
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


 