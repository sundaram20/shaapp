<?php //include_once("../../config/auto_loader.php"); ?>
<?php 
//include_once("../../config/auto_loader.php");

function tableViewMtdYtdfunction($Report_period,$Report_id_hotel,$id_mst_hotel,$Report_id_group_master,$Report_reportType,$Report_viewMonthwise,$Report_summaryReportType,$CronSet){
global $connNew;

if($id_mst_hotel!=''){
$HotelFilterConn=" AND FIND_IN_SET(`".TBL_HOTELS."`.id,'".$id_mst_hotel."') ";
}
	
$PeriodDateArray	=	explode('to',$Report_period);
//print_r($PeriodDateArray);
$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
///$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//$to = date('Y-m-d',strtotime($PeriodDateArray[1]. ' +1 day'));
$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//strtotime('+1 day', $stop_date)
//print_r($_SESSION);
//print_r($_REQUEST);

//$dateCalcultion = dateCalcultion($from,$to);

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
 $mtdRoomRevenueArr=array();
  $MonthWiseRoomNightsData=array();
  $MonthWiseRoomNightsLastYearData=array();
  $MonthWiseRevenueCurrentYearData=array();
      $ytdPrevYearRevenueData=array();
      
       $ThisYearConfirmandTendRoomNightsValue=array();
	    $LastYearConfirmandTendRoomNightsValue=array();
	    $MTDthisyearRoomNightsValue=array();
	    $LastYearMTDRoomNightsValue=array();
	    $YTDthisyearRoomNightsValue=array();
	    $LastYearYTDRoomNightsValue=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
if($Report_reportType==1){	
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
 //$to = date('31-03-'.$FinanceEndYear);
 
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

  
		
  	$reportPeriodMonth= date('F',strtotime($_POST['period'])).' '.$Year;
	$LYMONTH	=	date('Y-'.$month.'-01',strtotime('-1 years',strtotime($yearStart)));
	$CYMONTH	=	date('Y-m-01',strtotime($yearStart));
	$LYPEROD	= 	date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[0]))).' to '.date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[1])));
	$CYPERIOD   =	date('Y-m-d',strtotime($PeriodDateArray[0])).' to '.date('Y-m-d',strtotime($PeriodDateArray[1]));
  
  
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
$Quarterstart_date = date('Y-m-d', strtotime(date('Y') . '-' . (($current_quarter * 3) - 2) . '-1'));
$Quarterlast_date = date('Y-m-t', strtotime(date('Y') . '-' . (($current_quarter * 3)) . '-1'));

$date=date_create(date('Y-m-d'));

date_format($date,"m");
if (date_format($date,"m") >= 4) {//On or After April (FY is current year - next year)
    $financial_year = (date_format($date,"Y")) . 'To' . (date_format($date,"Y")+1);
} else {//On or Before March (FY is previous year - current year)
    $financial_year = (date_format($date,"Y")-1) . 'To' . date_format($date,"Y");
}
$financial_year=explode('To',$financial_year);
$FinanceStarYear=$financial_year[0];
$FinanceEndYear=$financial_year[1];


// get month name from number
function month_name($month_number){
	return date('F', mktime(0, 0, 0, $month_number, 10));
}


// get get last date of given month (of year)
function month_end_date($year, $month_number){
	return date("t", strtotime("$year-$month_number-0"));
}

// return two digit month or day, e.g. 04 - April
function zero_pad($number){
	if($number < 10)
		return "0$number";
	
	return "$number";
}

// Return quarters between tow dates. Array of objects
function get_quarters($start_date, $end_date){
	
	$quarters = array();
	
	$start_month = date( 'm', strtotime($start_date) );
	$start_year = date( 'Y', strtotime($start_date) );
	
	$end_month = date( 'm', strtotime($end_date) );
	$end_year = date( 'Y', strtotime($end_date) );
	
	$start_quarter = ceil($start_month/3);
	$end_quarter = ceil($end_month/3);

	$quarter = $start_quarter; // variable to track current quarter
	
	// Loop over years and quarters to create array
	for( $y = $start_year; $y <= $end_year; $y++ ){
		if($y == $end_year)
			$max_qtr = $end_quarter;
		else
			$max_qtr = 4;
		
		for($q=$quarter; $q<=$max_qtr; $q++){
			
			$current_quarter = new stdClass();
			
			$end_month_num = zero_pad($q * 3);
			$start_month_num = ($end_month_num - 2);

			$q_start_month = month_name($start_month_num);
			$q_end_month = month_name($end_month_num);
			
			$current_quarter->period = "Qtr $q ($q_start_month - $q_end_month) $y";
			$current_quarter->period_start = "$y-$start_month_num-01";      // yyyy-mm-dd    
			$current_quarter->period_end = "$y-$end_month_num-" . month_end_date($y, $end_month_num);
			
			$quarters[] = $current_quarter;
			unset($current_quarter);
		}

		$quarter = 1; // reset to 1 for next year
	}
	
	return $quarters;
	
}

$quarters = get_quarters(date($FinanceStarYear.'-04-01'), date($FinanceEndYear.'-03-31'));
     
// Test	   
/*echo '<pre>';
print_r($quarters);
echo '</pre>';*/

//echo "<br> FY ".$financial_year;



//printing dates
/*echo "<br>Today";
echo "<br>1)Current_date :".$current_date;
echo "<br>2)Last_year_current_date :".$last_year_current_date;
echo "<br><br>MTD";
echo "<br>3) From_month_to_date :".$from_month_to_date;
echo "<br>4)To_month_to_date :".$to_month_to_date;
echo "<br>5)Last_year_to_month_date :".$last_year_to_month_date;
echo "<br>6)Last_year_from_month_date : ".$last_year_from_month_date;
echo "<br><br>YTD";
echo "<br>7)from_year_to_date :".$from_year_to_date;
echo "<br>8)to_year_to_date :".$to_year_to_date;
echo "<br>9)Last_year_to_year_to_date :".$last_year_to_year_date;
echo "<br>10)Last_year_from_year_to_date :".$last_year_from_year_date;
echo "<br><br>YTD PERIOD";
echo "<br>4)CYP :".$CYPERIOD;
echo "<br>3) LYP :".$LYPEROD;*/

if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
}
else {
    $year = (date('Y')-1)."-".date('Y');
}
//echo $year; // 2015-2016
 date($FinanceStarYear.'-04-01').date($FinanceEndYear.'-03-31');
$start    = new DateTime(date($FinanceStarYear.'-04-01'));
$start->modify('first day of this month');
$end      = new DateTime(date($FinanceEndYear.'-03-31'));
$end->modify('first day of next month');
$interval = DateInterval::createFromDateString('6 month');
$period   = new DatePeriod($start, $interval, $end);

foreach ($period as $dt) {
   // echo  "<br>\n".$dt->format("Y-m") . "<br>\n";
}


$end = strtotime(date($FinanceEndYear.'-10-01'));
$start = $month = strtotime("-12 months", $end);

while ( $month < $end ) {
   // echo "<br>\n".date("Y-m-d", $month);
    $month = strtotime("+6 month", $month);
}

$From_CY_Date   =   date('Y-m-d',strtotime($PeriodDateArray[0]));
$To_CY_Date     =   date('Y-m-d',strtotime($PeriodDateArray[1]));

$From_LY_Date   =date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[0])));
$To_LY_Date     =date('Y-m-d',strtotime('-1 years',strtotime($PeriodDateArray[1])));

// $From_CY_Finacial_Year=date('01-04-Y',strtotime('01-04-'.$yearStart));
// $To_CY_Finacial_Year=date('d-m-Y',strtotime($yearEnd));

//$From_LY_Finacial_Year=$PeriodDateArray[1];
//$To_LY_Finacial_Year=$PeriodDateArray[1];


//echo $_SESSION['teamNewMembers'];
 if($Report_id_hotel>0){
	
	$cond = ' AND id="'.$Report_id_hotel.'"   ';
	//$graphotelName='All Hotel';
	}else{
		//$cond = ' AND id="'.$Report_id_hotel.'" order by name LIMIT 0,5';
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       
	$reservationTable =TBL_BE_RESERVATION_QUERY;
if($Report_id_hotel>0){
	//$hname=$rowExe->name;
	}else{
		//$hname='Hotels';
		
		}
	//=========================================================================================
//	$UserInActive	=	"  AND ( ".TBL_USERS.".status_inactive_date>='".$to."' ||  ".TBL_USERS.".status_inactive_date='0000-00-00') ";

//print_r($_SESSION);
//print_r($_REQUEST);
if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
	//$cond = ' AND id="'.$_SESSION['userId'].'" ';
	$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');
	if($team_data_access_approved=='1'){
		$cond = '';
		}else{
			$cond = ' AND id="'.$_SESSION['userId'].'" ';
			}
}

//echo $_SESSION['teamNewMembers'];
 if($_REQUEST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$Report_id_hotel;
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($Report_id_hotel==0){ 
		//echo 'All';
		
		/*$teamIds = "SELECT id FROM ".TBL_TEAM." WHERE id_shop=".$_SESSION['shop']." ";
		$resTeamIds =  mysqli_query($connNew,$teamIds);

		$teamIdsArray=array();

		while($rowTeamIds=mysqli_fetch_object($resTeamIds)){
			array_push($teamIdsArray,$rowTeamIds->id);
		}

		$teamId=implode(',',$teamIdsArray);*/
		if($_SESSION['userLevel']==1){ //Super ADMIN
					$teamIds = "SELECT id FROM ".TBL_TEAM." WHERE id_shop=".$_SESSION['shop']." ";
					$resTeamIds =  mysqli_query($connNew,$teamIds);
					
					$teamIdsArray=array();
					
					while($rowTeamIds=mysqli_fetch_object($resTeamIds)){
					array_push($teamIdsArray,$rowTeamIds->id);
					}
					
					$id_teams=implode(',',$teamIdsArray);
		}else{
		$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" AND id_shop="'.$_SESSION['shop'].'"  ');// ".$UserInActive." 
		}
		
		//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";

		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		$allUser =" AND  ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		//$allUser= " AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$Report_id_hotel.", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$Report_id_hotel."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser =" AND ".TBL_USERS.".`id` IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if( $team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    //$cond = ' AND  id="'.$_SESSION['userId'].'" ';
		}
		
	}
if($Report_reportType==1){
    $ReportTypeMainTitle ='PICKUP ';
}
if($Report_reportType==2){
    $ReportTypeMainTitle ='BOB ';
}

/*	echo '================='.$cond;
	echo '<br><pre>'.print_r($_REQUEST);$teamMembers;
	echo '<br>'.print_r($teamArray);
	echo $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE id!='' ".$cond." ".$allUser." order by name";
		 //echo $sqlExe;
		
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){
    
}
echo  ".$allUser.";	die;*/
	//=========================================================================================	
		$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."' and    `".TBL_HOTELS."`.status='1' ";
	if($_REQUEST['search_name'] != ''){
		$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
	if($_REQUEST['hotelId'] != ''){
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` in (".$hotel_ids.")";
	}
	if($_SESSION['HotelUserPermission'] != ''){//FIND_IN_SET('".$resActionId."',user_actions) 
		$cond .= " AND `".TBL_ORDERS."`.`id_hotel` IN  (".addslashes($_SESSION['HotelUserPermission']).")";
	}
	
	if($_REQUEST['booking_status'] != ''){
		$booking_status_arr = implode(',',$_REQUEST['booking_status']);
		$cond .= " AND `".TBL_ORDERS."`.`booking_status` in (".$booking_status_arr.") ";
	}
	
	if($_REQUEST['company_id'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
	}
	if($_REQUEST['guest'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
	}
	if($_REQUEST['payment_status'] != ''){
		$payment_status_arr = implode(',',$_REQUEST['payment_status']);
		$cond .= " AND `".TBL_ORDERS."`.`payment_status` in (".$payment_status_arr.") ";
	}
	if($_REQUEST['lunch_booking'] != ''){
		$cond .= " AND `".TBL_ORDERS."`.`type` = 'L'";
	}

	if($_REQUEST['id_executive'] != ''){
		//$id_executive = implode(',',$_REQUEST['id_executive']);
			//$cond .= " AND ".TBL_USERS.".`id` in (".$id_executive.")";
	}
	if($Report_id_group_master != '' && $Report_id_group_master != '0' && $Report_id_group_master != '10000' ){
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id` = '".addslashes($Report_id_group_master)."'";
	}elseif($Report_id_group_master == '10000'){
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  
						$GroupArrayList[] = 	$objGroup->id;
					
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	    
	}else{
	
	    
	    $sql_group = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' ORDER BY display_order";
        $res_group = mysqli_query($connNew,$sql_group);
        $GroupArrayList=array();
        while($objGroup=mysqli_fetch_object($res_group)){
						  if(strtoupper($objGroup->name)!='UNIT'){  
						$GroupArrayList[] = 	$objGroup->id;
						}
					}
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
	
	
	
	
$reportArray=array();	


 if(($_REQUEST['pdf']==1 && $Report_reportType==1) || ($CronSet==1 && $Report_reportType==1) || ($Report_summaryReportType == '5' && $Report_reportType==1)){ //Hotel Wise  Summary PDF PICKUP Report
      
     $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,
   
    
   
    `".TBL_ORDERS."`.booking_status,
   
    	
  sum(case when (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `newConfirmed`,
         
        
     sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date= '".$current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `ThisYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date = '".$last_year_current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_month_to_date."' and '".$to_month_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `MTDthisyearRoomNights`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_month_date."' and '".$last_year_to_month_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearMTDRoomNights`,


sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `YTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearYTDRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `lastYearYTDconfimed_revenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `YTDthisyearconfimed_revenue`,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_month_to_date))."' AND '".date('Y-m-d',strtotime($to_month_to_date))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS ThisYearMTDconfimed_revenue,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($last_year_from_month_date))."' AND '".date('Y-m-d',strtotime($last_year_to_month_date))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS LastYearMTDconfimed_revenue



    		
    	,`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_hotel and  `".TBL_HOTELS."`.status='1'
       ".$cond." ".$allUser.$HotelFilterConn."
       GROUP BY `".TBL_ORDERS."`.id_hotel";
   // echo $sql;
    // die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.'');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    $resRoomInventory = executeSql("SELECT sum(ahr.inventory) as totalRoominventory from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($rowList->id_hotel)."' order by ahr.hotel_id	");
		$row = fetch_array($resRoomInventory);

$daysMTD   =   strtotime("+1 day",strtotime($to_month_to_date));
$daysYTD   =   strtotime("+1 day",strtotime($to_year_to_date));
		
	    $MTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_month_to_date) - $daysMTD) / 86400);
		$YTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_year_to_date) - $daysYTD) / 86400);
	    
	       $ThisYearConfirmandTendRoomNights= ($rowList->ThisYearConfirmandTendRoomNights);
           $LastYearConfirmandTendRoomNights= ($rowList->LastYearConfirmandTendRoomNights);
           $MTDthisyearRoomNights= ($rowList->MTDthisyearRoomNights);
           $LastYearMTDRoomNights= ($rowList->LastYearMTDRoomNights);
           $YTDthisyearRoomNights= ($rowList->YTDthisyearRoomNights);
           $LastYearYTDRoomNights= ($rowList->LastYearYTDRoomNights);
           $ThisYearMTDconfimed_revenue= ($rowList->ThisYearMTDconfimed_revenue);
	    
	     if($ThisYearConfirmandTendRoomNights>0){
	    array_push($ThisYearConfirmandTendRoomNightsValue,($ThisYearConfirmandTendRoomNights==''?0:round($ThisYearConfirmandTendRoomNights)));
	     }if($LastYearConfirmandTendRoomNights>0){
	    array_push($LastYearConfirmandTendRoomNightsValue,($LastYearConfirmandTendRoomNights==''?0:round($LastYearConfirmandTendRoomNights)));
	     }if($MTDthisyearRoomNights>0){
	    array_push($MTDthisyearRoomNightsValue,($MTDthisyearRoomNights==''?0:round($MTDthisyearRoomNights)));
	     }if($LastYearMTDRoomNights>0){
	    array_push($LastYearMTDRoomNightsValue,($LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights)));
	     }if($YTDthisyearRoomNights>0){
	    array_push($YTDthisyearRoomNightsValue,($YTDthisyearRoomNights==''?0:round($YTDthisyearRoomNights)));
	     }if($LastYearYTDRoomNights>0){
	    array_push($LastYearYTDRoomNightsValue,($LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights)));
	     } 
	     if($ThisYearMTDconfimed_revenue>0){
	    array_push($ThisYearMTDconfimed_revenueValue,($ThisYearMTDconfimed_revenue==''?0:round($ThisYearMTDconfimed_revenue)));
	     }
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue);
	    //if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	     $lastYearYTDconfimed_revenueTen  = ($rowList->lastYearYTDconfimed_revenue);
	     $YTDthisyearconfimed_revenue  = ($rowList->YTDthisyearconfimed_revenue);
	     $LastYearMTDconfimed_revenue= ($rowList->LastYearMTDconfimed_revenue);
	    $emptytext7 ='empty_'.$empty7++;
	   $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['roomnights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearConfirmandTendRoomNights']=$ThisYearConfirmandTendRoomNightsValue==''?'0':$ThisYearConfirmandTendRoomNightsValue;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearConfirmandTendRoomNights']=$LastYearConfirmandTendRoomNightsValue==''?0:round($LastYearConfirmandTendRoomNightsValue);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['MTDthisyearRoomNights']=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDRoomNights']=$LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearRoomNights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearYTDRoomNights']=$LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']    =   $LastYearYTDRoomNights==''?'0':$LastYearYTDRoomNights;
	    
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearYTDconfimed_revenue']    =   $lastYearYTDconfimed_revenueTen==''?'0':$lastYearYTDconfimed_revenueTen;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearconfimed_revenue']    =   $YTDthisyearconfimed_revenue==''?'0':$YTDthisyearconfimed_revenue;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDconfimed_revenue']    =   $LastYearMTDconfimed_revenue==''?'0':$LastYearMTDconfimed_revenue;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['MTDRoominventory']    =   $MTDtotalRoominventory;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDRoominventory']    =   $YTDtotalRoominventory;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearMTDconfimed_revenue']    =   $ThisYearMTDconfimed_revenue;
	    
	        
	    //}
	}  
  }    
//  echo '<pre>';
   // print_r($reportArray);
    //echo '</pre>';die;
 if(($_REQUEST['pdf']==1 && $Report_reportType==2) ||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '5' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
   $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,
   
    
   
    `".TBL_ORDERS."`.booking_status,
   
    	
  sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`,
         
        
     sum(case when (`".TBL_ORDERS."`.booking_status = '1') and ( DATE(`".TBL_ORDER_DETAIL."`.dated)= '".$current_date."') then `fs_order_detail`.room_quantity else 0 end) as `ThisYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( DATE(`".TBL_ORDER_DETAIL."`.dated)= '".$last_year_current_date."') then `fs_order_detail`.room_quantity else 0 end) as `LastYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_month_to_date."' and '".$to_month_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `MTDthisyearRoomNights`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$last_year_from_month_date."' and '".$last_year_to_month_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearMTDRoomNights`,


sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `YTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearYTDRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `lastYearYTDconfimed_revenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `YTDthisyearconfimed_revenue`,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".date('Y-m-d',strtotime($from_month_to_date))."' AND '".date('Y-m-d',strtotime($to_month_to_date))."' THEN `fs_order_detail`.tarrif_price ELSE 0 END ) AS ThisYearMTDconfimed_revenue,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1')  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".date('Y-m-d',strtotime($last_year_from_month_date))."' AND '".date('Y-m-d',strtotime($last_year_to_month_date))."' THEN `fs_order_detail`.tarrif_price ELSE 0 END ) AS LastYearMTDconfimed_revenue



    		
    	FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_hotel and  `".TBL_HOTELS."`.status='1'
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where   `".TBL_HOTELS."`.status='1' AND `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."'".$allUser." ".$condBOB.$HotelFilterConn."  GROUP BY `".TBL_ORDERS."`.id_hotel Order BY `".TBL_GROUP_MASTER."`.display_order,`fs_users`.myownteam_id";
       
   
   // echo $sql;
    // die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
       $SummaryHedding='Hotel Wise ';
       $TaleName='Hotel Wise Source';
       $resultList = mysqli_query($connNew,$sql);
       $empty7=0;
	while($rowList = mysqli_fetch_object($resultList)){
	 // $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowList->MyOwnteam."'");
	 $companyname= selectColumn(TBL_HOTELS,'name','WHERE id='.$rowList->id_hotel.' ');
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	   $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowList->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowList->id_default_group."'");
	    //$ExecutiveName=  selectColumn(TBL_USERS,'name'," WHERE `id` = '".$rowList->id_default_group."'");
	    $resRoomInventory = executeSql("SELECT sum(ahr.inventory) as totalRoominventory from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($rowList->id_hotel)."' order by ahr.hotel_id	");
		$row = fetch_array($resRoomInventory);

$daysMTD   =   strtotime("+1 day",strtotime($to_month_to_date));
$daysYTD   =   strtotime("+1 day",strtotime($to_year_to_date));
		
	    $MTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_month_to_date) - ($daysMTD)) / 86400);
		$YTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_year_to_date) - ($daysYTD)) / 86400);
	    
	       $ThisYearConfirmandTendRoomNights= ($rowList->ThisYearConfirmandTendRoomNights);
           $LastYearConfirmandTendRoomNights= ($rowList->LastYearConfirmandTendRoomNights);
           $MTDthisyearRoomNights= ($rowList->MTDthisyearRoomNights);
           $LastYearMTDRoomNights= ($rowList->LastYearMTDRoomNights);
           $YTDthisyearRoomNights= ($rowList->YTDthisyearRoomNights);
           $LastYearYTDRoomNights= ($rowList->LastYearYTDRoomNights);
           $ThisYearMTDconfimed_revenue= ($rowList->ThisYearMTDconfimed_revenue);
	    
	     if($ThisYearConfirmandTendRoomNights>0){
	    array_push($ThisYearConfirmandTendRoomNightsValue,($ThisYearConfirmandTendRoomNights==''?0:round($ThisYearConfirmandTendRoomNights)));
	     }if($LastYearConfirmandTendRoomNights>0){
	    array_push($LastYearConfirmandTendRoomNightsValue,($LastYearConfirmandTendRoomNights==''?0:round($LastYearConfirmandTendRoomNights)));
	     }if($MTDthisyearRoomNights>0){
	    array_push($MTDthisyearRoomNightsValue,($MTDthisyearRoomNights==''?0:round($MTDthisyearRoomNights)));
	     }if($LastYearMTDRoomNights>0){
	    array_push($LastYearMTDRoomNightsValue,($LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights)));
	     }if($YTDthisyearRoomNights>0){
	    array_push($YTDthisyearRoomNightsValue,($YTDthisyearRoomNights==''?0:round($YTDthisyearRoomNights)));
	     }if($LastYearYTDRoomNights>0){
	    array_push($LastYearYTDRoomNightsValue,($LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights)));
	     } 
	     if($ThisYearMTDconfimed_revenue>0){
	    array_push($ThisYearMTDconfimed_revenueValue,($ThisYearMTDconfimed_revenue==''?0:round($ThisYearMTDconfimed_revenue)));
	     }
	    $newConfirmednewTentative=($rowList->newConfirmed+$rowList->newTentative);
	    $newConfirmednewTentative_revenue=($rowList->confimed_revenue);
	    //if($newConfirmednewTentative>0){
	    array_push($mtdThisValues,($newConfirmednewTentative==''?'0':$newConfirmednewTentative));
	    array_push($mtdRoomRevenue,($newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue)));
	    
	     $lastYearYTDconfimed_revenueTen  = ($rowList->lastYearYTDconfimed_revenue);
	     $YTDthisyearconfimed_revenue  = ($rowList->YTDthisyearconfimed_revenue);
	     $LastYearMTDconfimed_revenue= ($rowList->LastYearMTDconfimed_revenue);
	    $emptytext7 ='empty_'.$empty7++;
	   $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['roomnights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearConfirmandTendRoomNights']=$ThisYearConfirmandTendRoomNightsValue==''?'0':$ThisYearConfirmandTendRoomNightsValue;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearConfirmandTendRoomNights']=$LastYearConfirmandTendRoomNightsValue==''?0:round($LastYearConfirmandTendRoomNightsValue);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['MTDthisyearRoomNights']=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDRoomNights']=$LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearRoomNights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearYTDRoomNights']=$LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights);
	    $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']    =   $LastYearYTDRoomNights==''?'0':$LastYearYTDRoomNights;
	    
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearYTDconfimed_revenue']    =   $lastYearYTDconfimed_revenueTen==''?'0':$lastYearYTDconfimed_revenueTen;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearconfimed_revenue']    =   $YTDthisyearconfimed_revenue==''?'0':$YTDthisyearconfimed_revenue;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDconfimed_revenue']    =   $LastYearMTDconfimed_revenue==''?'0':$LastYearMTDconfimed_revenue;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['MTDRoominventory']    =   $MTDtotalRoominventory;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['YTDRoominventory']    =   $YTDtotalRoominventory;
	     $reportArray['Hotelwise'][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearMTDconfimed_revenue']    =   $ThisYearMTDconfimed_revenue;
	    
	        
	    //}
	}  
  } 
    
      
 
  
 
 
 
  
		$stackedDataSet['label']=$rowExe->name;
		
		$stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
		
		//array_push($exeNameArr,ucwords(strtolower($rowExe->name)));
		array_push($stackedArr,$stackedDataSet);

		
		$budget='';
		
		$visitMtd='';
		array_push($mtdLastValues, ($prevYearRoomNightsMtd==''?0:$prevYearRoomNightsMtd));
		array_push($mtdThisValues, ($ThisMonthRoomNightsMtd==''?0:$ThisMonthRoomNightsMtd));
		
		array_push($budgetValues, ($budget==''?0:$budget));
		

		array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
		array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

		array_push($mtdVisits,$visitMtd);
		//array_push($mtdRoomRevenue,($RevenueMtd==''?0:round($RevenueMtd)));
		array_push($ytdRoomRevenue,($ytdRevenue==''?0:round($ytdRevenue)));

		

	
//===========================Segment Wise Chart END==================================	

	if(empty($mtdRoomRevenue)) {
	    array_push($mtdRoomRevenue,'null');
	}
	if(empty($mtdThisValues)) {
	    array_push($mtdThisValues,'0');
	}

	
//======================ExecutiveWise  Summary Sorting Start
//======================ExecutiveWise  Summary Sorting Start
$HotelwisePerformanceSummary=array();
foreach($exeNameArr as $key=>$value){
	
	$HotelwisePerformanceSummary[$key]['Hotel']=$value;
	$HotelwisePerformanceSummary[$key]['RoomNights']=$mtdThisValues[$key];
	$HotelwisePerformanceSummary[$key]['RoomRevenue']=$mtdRoomRevenue[$key];
	}

$sort = array();
foreach($HotelwisePerformanceSummary as $k=>$v) {
$sort['Hotel'][$k] = $v['Hotel'];
$sort['RoomNights'][$k] = $v['RoomNights'];
}
# sort by event_type desc and then title asc
array_multisort($sort['RoomNights'], SORT_DESC, $sort['Hotel'], SORT_ASC,$HotelwisePerformanceSummary);
$mtdRoomRevenue='';
$mtdRoomRevenue=array();

foreach($HotelwisePerformanceSummary as $fkey=>$fvalue){	
	$exeNameArr[$fkey]=$HotelwisePerformanceSummary[$fkey]['Hotel'];
	$mtdThisValues[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomNights'];
	$mtdRoomRevenue[$fkey]=$HotelwisePerformanceSummary[$fkey]['RoomRevenue'];	
	}
//======================ExecutiveWise  Summary Sorting EnD
//======================ExecutiveWise  Summary Sorting EnD

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
$returnData['SummaryHedding']=$SummaryHedding;
$returnData['TaleName']=$TaleName;

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

$returnData['CompanyGroupNameArray']=$CompanyGroupNameArray;
$returnData['CompanyGroupListArray']=$CompanyGroupListArray;
$returnData['CompanyGroupListLastYearArray']=$CompanyGroupListLastYearArray;
//print_r($mtdThisValues);
//echo '<pre>';
//print_r($reportArray);
//die;


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
   
  overflow: scroll;
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
 

#table-wrapper {
  position:relative;
}
#table-scroll {
  height:150px;
  overflow:auto;  
  margin-top:20px;
}
#table-wrapper table {
  width:80%;

}
#table-wrapper table * {
  background:yellow;
  color:black;
}
#table-wrapper table thead th .text {
  position:absolute;   
  top:-20px;
  z-index:2;
  height:20px;
  width:35%;
  border:1px solid red;
}
.marginright{
        border-right: 3px solid red !important;

}

</style>';
 
$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
$rowShop = mysqli_fetch_object($resShop);
$logo	=	$rowShop->image;
$Recpath =explode('/',getcwd());
if (in_array("crs", $Recpath)) {
    $foldername =    "/crs";
}

if (in_array("sales", $Recpath)) {
    $foldername =    "/sales";
}
$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;


//$Newrate_id	= addslashes(encryptor(decrypt,$_REQUEST['id']));
if($_REQUEST['pdf']==1 || $CronSet==1){
    if($CronSet==1){
    $resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '2'");
    $rowShop = mysqli_fetch_object($resShop);
    $logo	=	$rowShop->image;
    $pathImg='/home/inroomhub/public_html/crs';
    }
 $content .= '<table class="table" style=" margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  
	 $content .=    '<br><br><br><br><br><br><br><br><br>
 <table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br><br>';
}

foreach($reportArray as $maintitle=>$mainDatalist){
    
    $contentTeam ='<table class="table table-striped text-center">';
	$contentTeam .='<tr><th colspan="21" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle.' Breakup For Period '.$reportPeriod.'</b></th></tr>';
    $contentTeam .='<tr>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;" class="marginright">MTD</th>';
   // $contentTeam .='<th colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">QTD</th>';
    $contentTeam .='<th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">YTD</th></tr>';
    
    
     $contentTeam .='<tr style="vertical-align:central;"> <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
   ';
  
   $contentTeam .='
   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>INV </b></th>
   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY (Lacs)</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY (Lacs) </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>';
  
   /*$contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY (Lacs)</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY (Lacs) </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>';*/
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY </b></th>
  <th class="marginright" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  ';
  
  
  $contentTeam .='
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>INV </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY (Lacs)</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY (Lacs) </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>';
  
  
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>LY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>CY </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>GOLY % </b></th>
  ';
  
   $contentTeam .='</tr>';
   $contentTeam .='<tr>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>
    <th class="marginright" colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Occupancy%</th>
    
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>
    <th  colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Occupancy%</th>';
    
   /* $contentTeam .='<th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';*/
    
    
    $contentTeam .='</tr>';
    
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	// $mainDatalist=array(); 
    
    
    foreach($mainDatalist as   $key=> $Data){ 
$RoomNights[$key] =$Data['YTDthisyearRoomNights'];
$lastYearYTDconfimed_revenue[$key] = $Data['lastYearYTDconfimed_revenue'];
//$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($mainDatalist, 'YTDthisyearRoomNights');
$lastYearYTDconfimed_revenue = array_column($GroupArray, 'lastYearYTDconfimed_revenue');
array_multisort($RoomNights, SORT_DESC, $mainDatalist);  
    
    
     foreach($mainDatalist as $teamGroup=>$DataList){
       $TotalLastYearMTDRoomNights+=   $DataList['LastYearMTDRoomNights'];
       $TotalMTDthisyearRoomNights+=   $DataList['MTDthisyearRoomNights'];
       $TotalLastYearYTDRoomNights+=   $DataList['LastYearYTDRoomNights'];
       $TotalYTDthisyearRoomNights+= $DataList['YTDthisyearRoomNights'];
       
       $TotalMTDRoominventory += $DataList['MTDRoominventory'];
       $TotalYTDRoominventory += $DataList['YTDRoominventory'];
       
       
            $GolyMTDRoomNights         =   $DataList['LastYearMTDRoomNights']>0?round((($DataList['MTDthisyearRoomNights']-$DataList['LastYearMTDRoomNights'])/$DataList['LastYearMTDRoomNights'])*100):'0';
            $GolyMTDConfimedRevenue    =   $DataList['LastYearMTDconfimed_revenue']>0?round((($DataList['ThisYearMTDconfimed_revenue']-$DataList['LastYearMTDconfimed_revenue'])/$DataList['LastYearMTDconfimed_revenue'])*100):'0';
            
            $GolyYTDRoomNights         =   $DataList['LastYearYTDRoomNights']>0?round((($DataList['YTDthisyearRoomNights']-$DataList['LastYearYTDRoomNights'])/$DataList['LastYearYTDRoomNights'])*100):'0';
            $GolyYTDConfimedRevenue    =   $DataList['lastYearYTDconfimed_revenue']>0?round((($DataList['YTDthisyearconfimed_revenue']-$DataList['lastYearYTDconfimed_revenue'])/$DataList['lastYearYTDconfimed_revenue'])*100):'0';
            
            $OccpanMTDLastYear  =    $DataList['MTDRoominventory']>0?round((($DataList['LastYearMTDRoomNights'])/$DataList['MTDRoominventory'])*100):'0';
            $OccpanMTDThisYear  =    $DataList['MTDRoominventory']>0?round((($DataList['MTDthisyearRoomNights'])/$DataList['MTDRoominventory'])*100):'0';
            $OccpanGolyMTD      =    $OccpanMTDLastYear>0?round((($OccpanMTDThisYear-$OccpanMTDLastYear)/$OccpanMTDLastYear)*100,2):'0';
            
            
            $OccpanYTDLastYear  =    $DataList['YTDRoominventory']>0?round((($DataList['LastYearYTDRoomNights'])/$DataList['YTDRoominventory'])*100):'0';
            $OccpanYTDThisYear  =    $DataList['YTDRoominventory']>0?round((($DataList['YTDthisyearRoomNights'])/$DataList['YTDRoominventory'])*100):'0';
            $OccpanGolyYTD      =    $OccpanYTDLastYear>0?round((($OccpanYTDThisYear-$OccpanYTDLastYear)/$OccpanYTDLastYear)*100,2):'0';
            
            $TotalGolyYTDRoomNights  +=   $GolyYTDRoomNights;
            $TotalLastYearMTDconfimed_revenue +=$GolyMTDRoomNights;
            $TotalThisYearMTDconfimed_revenue +=$GolyMTDConfimedRevenue;
            $TotalLastYearYTDconfimed_revenue +=$GolyYTDRoomNights;
            $TotalThisYearYTDconfimed_revenue +=$GolyYTDConfimedRevenue;
            
            $TotalOccpanMTDLastYear+=$OccpanMTDLastYear;
            $TotalOccpanMTDThisYear+=$OccpanMTDThisYear;
            
            $TotalOccpanYTDLastYear+=$OccpanYTDLastYear;
            $TotalOccpanYTDThisYear+=$OccpanYTDThisYear;
            
    $contentTeam .='<tr>
                <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#fff; font-size:12px !important"><b>'.strtoupper($teamGroup).'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; font-size:12px !important"><b>' .$DataList['MTDRoominventory'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>' .$DataList['LastYearMTDRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> '.$DataList['MTDthisyearRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>'.$GolyMTDRoomNights.'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .round($DataList['LastYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($DataList['ThisYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.$GolyMTDConfimedRevenue.'</b></th>';
                
                //Occpeancy
                $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>'.$OccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> '.$OccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#ff; font-size:12px !important"><b> '.$OccpanGolyMTD.'</b></th>';
                
               /* $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>--</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> --</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ff; font-size:12px !important"><b> --</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>--</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> --</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> --</b></th>';*/
                
                
                
                $contentTeam .='
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; font-size:12px !important"><b>' .$DataList['YTDRoominventory'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>' .$DataList['LastYearYTDRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> '.$DataList['YTDthisyearRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>'.$GolyYTDRoomNights.'</b></th>
                
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>' .round($DataList['lastYearYTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> '.round($DataList['YTDthisyearconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>'.$GolyYTDConfimedRevenue.'</b></th>';
                 
                 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>'.$OccpanYTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> '.$OccpanYTDThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ff; font-size:12px !important"><b>'.$OccpanGolyYTD.'</b></th>';
                
                $contentTeam .='</tr>';
    
     }
     //SUB TOTAL=============================================================
    $TotalSumGolyMTDRoomNights         =   $TotalLastYearMTDRoomNights>0?round((($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights)/$TotalLastYearMTDRoomNights)*100):'0';
    $TotalSumGolyMTDRevenue            =   $TotalLastYearMTDconfimed_revenue>0?round((($TotalThisYearMTDconfimed_revenue-$TotalLastYearMTDconfimed_revenue)/$TotalLastYearMTDconfimed_revenue)*100):'0';
    
    $TotalSumGolyYTDRoomNights         =   $TotalLastYearYTDRoomNights>0?round((($TotalYTDthisyearRoomNights-$TotalLastYearYTDRoomNights)/$TotalLastYearYTDRoomNights)*100):'0';
    $TotalSumGolyYTDRevenue            =   $TotalLastYearYTDconfimed_revenue>0?round((($TotalThisYearYTDconfimed_revenue-$TotalLastYearYTDconfimed_revenue)/$TotalLastYearYTDconfimed_revenue)*100):'0';
    
    
            $TotalOccpanMTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalLastYearMTDRoomNights)/$TotalMTDRoominventory)*100):'0';
            $TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalMTDthisyearRoomNights)/$TotalMTDRoominventory)*100):'0';
            $TotalOccpanGolyMTD      =    $TotalOccpanMTDLastYear>0?round((($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights)/$TotalLastYearMTDRoomNights)*100,2):'0';
            
            
            $TotalOccpanYTDLastYear  =    $TotalYTDRoominventory>0?round((($TotalLastYearYTDRoomNights)/$TotalYTDRoominventory)*100):'0';
            $TotalOccpanYTDThisYear  =    $TotalYTDRoominventory>0?round((($TotalYTDthisyearRoomNights)/$TotalYTDRoominventory)*100):'0';
            $TotalOccpanGolyYTD      =    $TotalOccpanYTDLastYear>0?round((($TotalYTDthisyearRoomNights-$TotalLastYearYTDRoomNights)/$TotalLastYearYTDRoomNights)*100,2):'0';
            
            
           
    
     $contentTeam .='<tr>
                <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.strtoupper('Total').'</b></th>
                
                 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalMTDRoominventory.'</b></th>
                 
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalMTDthisyearRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.$TotalSumGolyMTDRoomNights.'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .round($TotalLastYearMTDconfimed_revenue).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.round($TotalThisYearMTDconfimed_revenue).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalSumGolyMTDRevenue.'</b></th>';
                
                
               /* $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b>--</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; font-size:12px !important"><b> --</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ff; font-size:12px !important"><b> --</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b>--</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> --</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; font-size:12px !important"><b> --</b></th>';*/
                
                $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.$TotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalOccpanGolyMTD.'</b></th>';
                
                
                $contentTeam .='
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalYTDRoominventory.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalLastYearYTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalYTDthisyearRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.$TotalSumGolyYTDRoomNights.'</b></th>
                
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalLastYearYTDconfimed_revenue.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalThisYearYTDconfimed_revenue.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.$TotalSumGolyYTDRevenue.'</b></th>
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>' .$TotalOccpanYTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b> '.$TotalOccpanYTDThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; font-size:12px !important"><b>'.$TotalOccpanGolyYTD.'</b></th>
                </tr>';
                
    
    
    
     $contentTeam .= '</table><br/><br/>';
     $UnitValueIs='';
     $UnitValueIsWithout='';
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)!='UNIT'){
                         $UnitValueIsWithout='1';
                     }else{
                         
                         $UnitValueIs='1';
                     }
     }         
     //=======================================================================================
         if($maintitle=='Hotelwise'){
        	    if($UnitValueIsWithout==1){
        	        //Office Team Wise For Period Start  
        	 $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
    
    $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp</th>
             
             
             
             
             
             </tr>';
foreach($GroupArray as   $key=> $Data){ 
$RoomNights[$key] =$Data['ThisYearConfirmandTendRoomNights'];
$RoomRevenue[$key] = $Data['RoomRevenue'];
$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($GroupArray, 'ThisYearConfirmandTendRoomNights');
$RoomRevenue = array_column($GroupArray, 'RoomRevenue');
array_multisort($RoomNights, SORT_DESC, $RoomRevenue, SORT_ASC, $GroupArray);       
   
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)!='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['lastYearroomnights'];
                     }
                 }
   
   
   
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)!='UNIT'){
                     $GolyGroupRoomNights=$GroupNameArray['lastYearroomnights']>0?round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['lastYearroomnights']) *100,2):'0';
                     $GolyGroupRoomRevenue=$GroupNameArray['lastYearYTDconfimed_revenue']>0?round((($GroupNameArray['RoomRevenue']-$GroupNameArray['lastYearYTDconfimed_revenue'])/$GroupNameArray['lastYearYTDconfimed_revenue']) *100,2):'0';
                     
                     $GolyGroupRoomRevenueColorW=$GolyGroupRoomRevenue>=0?"":"color:red;";
                     $GolyGroupRoomRevenueColorLastYear=$GolyGroupRoomNights>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColorLastYear.'">'.$GolyGroupRoomNights.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['lastYearYTDconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColorW.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td>';
                    
                    
                    $contentGroup .='</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearYTDconfimed_revenue'];
                    
                  }
                 }
                 
             $SumTotalTeamWiseArray= $TotalTeamWiseRoomNight>0?round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight):'0';
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights=$TotalTeamWiseRoomNightlastYear>0?round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNightlastYear) *100,2):'0';
              $SumTotalGolyTeamConfimedRevenue=$TotalTeamWiseConfimedRevenuelastYear>0?round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenuelastYear) *100,2):'0';
              
              
               $SumTotalGolyTeamRoomNightsColor=$SumTotalGolyTeamRoomNights>=0?"":"color:red;";
                     $SumTotalGolyTeamConfimedRevenueColor=$SumTotalGolyTeamConfimedRevenue>=0?"":"color:red;";
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamRoomNightsColor.'">'.$SumTotalGolyTeamRoomNights.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamConfimedRevenueColor.'">'.$SumTotalGolyTeamConfimedRevenue.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             //$TotalTeamWiseConfimedRevenue='';
             //$TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
        	    }
             
             if($UnitValueIs==1){
             //===================================================
             $contentGroup .='<table class="table table-striped text-center">';  
        	 $contentGroup .='<tr style="vertical-align:central;text-align:">
        	 <th colspan="8" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle0ch.' Groupwise Summary For Period '.$reportPeriod.'</b></th></tr>	';
              $contentGroup .='<tr>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
    <th colspan="3" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>
    <th colspan="3"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Revenue</th>
    <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">ARR</th></tr>';
    
    $contentGroup .=    '<tr >
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Office</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Last Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Current Year(Lacs)</th>
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">GOLY %</th>
             
             <th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp</th>
             
             
             
             
             
             </tr>';
             
 
     foreach($GroupArray as $name => $GroupNameArray){    
                     if(strtoupper($name)=='UNIT'){
                    $TotalTeamWiseRoomNightContribution+=$GroupNameArray['lastYearroomnights'];
                     }
                 }
   
   
   
                 foreach($GroupArray as $name => $GroupNameArray){
                     if(strtoupper($name)=='UNIT'){
                     $GolyGroupRoomNights=$GroupNameArray['lastYearroomnights']>0?round((($GroupNameArray['RoomNights']-$GroupNameArray['lastYearroomnights'])/$GroupNameArray['lastYearroomnights']) *100,2):'0';
                     $GolyGroupRoomRevenue=$GroupNameArray['lastYearYTDconfimed_revenue']>0?round((($GroupNameArray['RoomRevenue']-$GroupNameArray['lastYearYTDconfimed_revenue'])/$GroupNameArray['lastYearYTDconfimed_revenue']) *100,2):'0';
                     
                     $GolyGroupRoomRevenueColor=$GolyGroupRoomRevenue>=0?"":"color:red;";
                      $GolyGroupRoomRevenueColorLastYear=$GolyGroupRoomNights>=0?"":"color:red;";
                    $contentGroup .='<tr >';
                    $contentGroup .='<td style="text-align:left;">'.strtoupper($name).'</td>';
                    
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['lastYearroomnights'].'</td>';
                    $contentGroup .='<td style="text-align:center;">'.$GroupNameArray['RoomNights'].'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColorLastYear.'">'.$GolyGroupRoomNights.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['lastYearYTDconfimed_revenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['RoomRevenue']/100000,2).'</td>';
                    $contentGroup .='<td style="text-align:center;'.$GolyGroupRoomRevenueColor.'">'.$GolyGroupRoomRevenue.'</td>';
                    
                    
                    $contentGroup .='<td style="text-align:center;">'.round($GroupNameArray['Arr']).'</td>';
                    
                    
                    $contentGroup .='</tr>';
                    $TotalTeamWiseRoomNight+=$GroupNameArray['RoomNights'];
                    $TotalTeamWiseConfimedRevenue+=$GroupNameArray['RoomRevenue'];
                    
                    $TotalTeamWiseRoomNightlastYear+=$GroupNameArray['lastYearroomnights'];
                    $TotalTeamWiseConfimedRevenuelastYear+=$GroupNameArray['lastYearYTDconfimed_revenue'];
                    
                  }
                 }
                 
             $SumTotalTeamWiseArray= $TotalTeamWiseRoomNight>0?round($TotalTeamWiseConfimedRevenue/$TotalTeamWiseRoomNight):'0';
             //$SumTotalTeamWiselastYearArray= round($TotalTeamWiseConfimedRevenuelastYear/$TotalTeamWiseRoomNightlastYear);
              
              $SumTotalGolyTeamRoomNights=$TotalTeamWiseRoomNightlastYear>0?round((($TotalTeamWiseRoomNight-$TotalTeamWiseRoomNightlastYear)/$TotalTeamWiseRoomNightlastYear) *100,2):'0';
              $SumTotalGolyTeamConfimedRevenue=$TotalTeamWiseConfimedRevenuelastYear>0?round((($TotalTeamWiseConfimedRevenue-$TotalTeamWiseConfimedRevenuelastYear)/$TotalTeamWiseConfimedRevenuelastYear) *100,2):'0';
              
              
               $SumTotalGolyTeamRoomNightsColor=$SumTotalGolyTeamRoomNights>=0?"":"color:red;";
               $SumTotalGolyTeamConfimedRevenueColor=$SumTotalGolyTeamConfimedRevenue>=0?"":"color:red;";
              
              
              
             $contentGroup .='<tr style="font-weight:bold;background-color:#3C8DBC;color:white;">
             <td style="text-align:center;background-color:#c2d69a;">Total </td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNightlastYear.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$TotalTeamWiseRoomNight.'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamRoomNightsColor.'">'.$SumTotalGolyTeamRoomNights.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenuelastYear/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.round($TotalTeamWiseConfimedRevenue/100000,2).'</td>
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;'.$SumTotalGolyTeamConfimedRevenueColor.'">'.$SumTotalGolyTeamConfimedRevenue.'</td>
             
             <td style="border-left:1px solid #fff;background-color:#c2d69a;text-align:center;">'.$SumTotalTeamWiseArray.'</td></tr>';
        	 $SumTotalTeamWiseArray='';
             $TotalTeamWiseConfimedRevenue='';
             $TotalTeamWiseRoomNight='';
             
             
             $contentGroup .= '</table>';
             
             }
             
             
         }
         $content .=$contentGroup;
         $content .=$contentTeam;
         
         
         $contentGroup='';
         //$contentTeam='';
     //Office Team Wise For Period End
     
}

if($_REQUEST['pdf']==1 && $CronSet==0){

    $dompdf = new DOMPDF();


//$dompdf->set_option("isPhpEnabled", true);
$dompdf->set_paper('landscape', 'landscape');


$dompdf->load_html($content);
//debugData($dompdf);

$dompdf->render();


//debugData($dompdf);

$font = Font_Metrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));




$Filename=$ReportTypeMainTitle.'PickupReport_'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}elseif($CronSet==1){
    
    //mail("shashafeer@gmail.com","My subject fa1 Content-GroupName=",$content);
    if($Report_reportType==1){
   // $ReportTypeMainTitle ='PICKUP ';
    $Filename='TableView-PickupReport_'.date("Y-m-d");
}
if($Report_reportType==2){
    $ReportTypeMainTitle ='BOB ';
     $Filename='TableView-BobReport_'.date("Y-m-d");
}

    //$Filename='TableView-PickupReport_'.date("Y-m-d");
   // echo $content;die;
   pdfGeneratorAttach($content, $Filename);
    
}elseif($_REQUEST['pdf']==0 && $CronSet==0 && $_REQUEST['excel']==1){
            if($_REQUEST['reportType']==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='CompareView-PickupRepor_'.date("Y-m-d H:i:s").'.xls';
                    }
                    if($_REQUEST['reportType']==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='CompareView-BobReport_'.date("Y-m-d H:i:s").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=".$Filename);
        echo $test;die;
            
    
    
}else{
echo $content;
//echo json_encode($returnData);
}
}







?>