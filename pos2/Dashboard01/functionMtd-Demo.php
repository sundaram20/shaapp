<?php //include_once("../../config/auto_loader.php"); ?>
<?php 
//include_once("../../config/auto_loader.php");

function tableViewMtdfunction($Report_period,$Report_id_hotel,$id_mst_hotel,$Report_id_group_master,$Report_reportType,$Report_viewMonthwise,$Report_summaryReportType,$CronSet,$ComparePeriodDate,$CurrentFinancialYearDate,$reportViewGrapTable){
global $connNew;

	
$OccpanYTDLastYear;
$OccpanYTDThisYear;	
if($id_mst_hotel!=''){
$HotelFilterConn=" AND FIND_IN_SET(`".TBL_HOTELS."`.id,'".$id_mst_hotel."') ";
}
	
$PeriodDateArray	=	explode('to',$Report_period);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));

$to = date('Y-m-d',strtotime($PeriodDateArray[1]));


 $Diffrence='';
  $CompareFinancialYear	=	explode('-',$ComparePeriodDate);
  $CurrentFinancialYear	=	explode('-',$CurrentFinancialYearDate);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);
 $CompareFinancialYearLastToLastYear = ($CompareFinancialYear[0]-1).'-'.($CompareFinancialYear[1]-1);
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
		$cond = "  where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  ";
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
	
	
	
	

 $Diffrence='';
  $CompareFinancialYear	=	explode('-',$ComparePeriodDate);
  $CurrentFinancialYear	=	explode('-',$CurrentFinancialYearDate);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);	
$FromLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($from_book)));
$ToLastYearDate   =   date('Y-m-d',strtotime($Diffrence.' years',strtotime($to_book)));	

$DiffrenceLastToLastYear	=	 $Diffrence-1;
$FromLastToLastYearDate   =   date('Y-m-d',strtotime($DiffrenceLastToLastYear.' years',strtotime($from_book)));
$ToLastToLastYearDate   =   date('Y-m-d',strtotime($DiffrenceLastToLastYear.' years',strtotime($to_book)));

//	echo $FromLastYearDate.'====='.$ToLastYearDate;
$reportZonalArray=array();	


 if(($_REQUEST['pdf']==1 && $Report_reportType==1) || ($CronSet==1 && $Report_reportType==1) || ($Report_summaryReportType == '62' && $Report_reportType==1)){ //Hotel Wise  Summary PDF PICKUP Report
      
     $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_ZONAL."`.name as zonename,
   
     
    `".TBL_ORDERS."`.booking_status,
   
    	
  sum(case when (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `newConfirmed`,
         
        
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date= '".$current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `ThisYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( `".TBL_ORDERS."`.booking_confirm_date = '".$last_year_current_date."') then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearConfirmandTendRoomNights`,



sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `YTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearYTDRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `lastYearYTDconfimed_revenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$from_year_to_date."' and '".$to_year_to_date."')) then ROUND(`".TBL_ORDERS."`.subtotal,0) else 0 end) as `YTDthisyearconfimed_revenue`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `MTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".$FromLastYearDate."' AND '".$ToLastYearDate."')) then ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) else 0 end) as `LastYearMTDRoomNights`,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS ThisYearMTDconfimed_revenue,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS LastYearMTDconfimed_revenue,

SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastToLastYearDate."' AND '".$ToLastToLastYearDate."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmedLastToLastYear,    		
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' ||`".TBL_ORDERS."`.booking_status = '2')  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".$FromLastToLastYearDate."' AND '".$ToLastToLastYearDate."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenueLastToLastYear
            

    		
    	,`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
       LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
       LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
       LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
       LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
       LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
       LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_hotel
	   LEFT JOIN `".TBL_ZONAL."` ON `".TBL_ZONAL."`.id = `".TBL_HOTELS."`.zonal
       ".$cond."  ".$allUser.$HotelFilterConn."
       GROUP BY `".TBL_ORDERS."`.id_hotel,`".TBL_HOTELS."`.zonal";
   // echo  $sql;
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

		$daysMTD   =   strtotime("+1 day",strtotime($to_book));
		$daysYTD   =   strtotime("+1 day",strtotime($to_book));
		
	    $MTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_book) - $daysMTD) / 86400);
		$YTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_book) - $daysYTD) / 86400);
	    
	       $ThisYearConfirmandTendRoomNights= ($rowList->ThisYearConfirmandTendRoomNights);
           $LastYearConfirmandTendRoomNights= ($rowList->LastYearConfirmandTendRoomNights);
           $MTDthisyearRoomNights= ($rowList->MTDthisyearRoomNights);
           $LastYearMTDRoomNights= ($rowList->LastYearMTDRoomNights);
           $YTDthisyearRoomNights= ($rowList->YTDthisyearRoomNights);
           $LastYearYTDRoomNights= ($rowList->LastYearYTDRoomNights);
           $ThisYearMTDconfimed_revenue= ($rowList->ThisYearMTDconfimed_revenue);
		   $LastYearYTDRoomNights= ($rowList->LastYearYTDRoomNights);
           $ThisYearMTDconfimed_revenue= ($rowList->ThisYearMTDconfimed_revenue);
		   
	    	$newConfirmednewTentativeLastToLastYear=($rowList->newConfirmedLastToLastYear);
			$newConfirmednewTentative_revenueLastToLastYear=($rowList->confimed_revenueLastToLastYear);
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
		
		 
		 $reportZonalArray['Hotelwise Summary']['MainTotalRoomNights']['MsubtotalRoomNights'] +=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;  
		 
		 $reportZonalArray['Hotelwise Summary']['MainTotalConfimedRevenue']['MsubtotalConfimedRevenue'] +=$ThisYearMTDconfimed_revenue==''?'0':$ThisYearMTDconfimed_revenue;  
	    
		$reportZonalArray['Hotelwise Summary'][$rowList->zonename]['MTDthisyearRoomNightsSubTotal']['subtotalRoomNights'] +=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;  
		 
		 $reportZonalArray['Hotelwise Summary'][$rowList->zonename]['MTDthisyearConfimedRevenueSubTotal']['subtotalConfimedRevenue'] +=$ThisYearMTDconfimed_revenue==''?'0':$ThisYearMTDconfimed_revenue;  
	    
		
	  		$reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['totalRooms']=$row['totalRoominventory'];
			 $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['roomnights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    
	   // $reportArray['Hotelwise Summary'][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearConfirmandTendRoomNights']=$ThisYearConfirmandTendRoomNightsValue==''?'0':$ThisYearConfirmandTendRoomNightsValue;
	  //  $reportArray['Hotelwise Summary'][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearConfirmandTendRoomNights']=$LastYearConfirmandTendRoomNightsValue==''?0:round($LastYearConfirmandTendRoomNightsValue);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['MTDthisyearRoomNights']=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDRoomNights']=$LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearRoomNights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearYTDRoomNights']=$LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']    =   $LastYearYTDRoomNights==''?'0':$LastYearYTDRoomNights;
	    
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearYTDconfimed_revenue']    =   $lastYearYTDconfimed_revenueTen==''?'0':$lastYearYTDconfimed_revenueTen;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearconfimed_revenue']    =   $YTDthisyearconfimed_revenue==''?'0':$YTDthisyearconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDconfimed_revenue']    =   $LastYearMTDconfimed_revenue==''?'0':$LastYearMTDconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['MTDRoominventory']    =   $MTDtotalRoominventory;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDRoominventory']    =   $YTDtotalRoominventory;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearMTDconfimed_revenue']    =   $ThisYearMTDconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnightsLastToLastYear']    = $newConfirmednewTentativeLastToLastYear==''?'0':$newConfirmednewTentativeLastToLastYear;
		 $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearconfimed_revenueLastToLastYear']    = $newConfirmednewTentative_revenueLastToLastYear==''?'0':$newConfirmednewTentative_revenueLastToLastYear;
	       
 	   //}
	}  
  }    
//  echo '<pre>';
   // print_r($reportZonalArray);
    //echo '</pre>';die;
 if(($_REQUEST['pdf']==1 && $Report_reportType==2) ||($CronSet==1 && $Report_reportType==2) || ($Report_summaryReportType == '62' && $Report_reportType==2)){//Hotel Wise  Summary PDF BOP
      
   $sql = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`".TBL_ZONAL."`.name as zonename,
   
    
   
    `".TBL_ORDERS."`.booking_status,
   
    	
  sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`,
         
        
     sum(case when (`".TBL_ORDERS."`.booking_status = '1') and ( DATE(`".TBL_ORDER_DETAIL."`.dated)= '".$current_date."') then `fs_order_detail`.room_quantity else 0 end) as `ThisYearConfirmandTendRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( DATE(`".TBL_ORDER_DETAIL."`.dated)= '".$last_year_current_date."') then `fs_order_detail`.room_quantity else 0 end) as `LastYearConfirmandTendRoomNights`,




sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.room_quantity else 0 end) as `YTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then `fs_order_detail`.room_quantity else 0 end) as `LastYearYTDRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$last_year_from_year_date."' and '".$last_year_to_year_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `lastYearYTDconfimed_revenue`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1'  ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$from_year_to_date."' and '".$to_year_to_date."')) then `fs_order_detail`.tarrif_price else 0 end) as `YTDthisyearconfimed_revenue`,

sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."' )) then `fs_order_detail`.room_quantity else 0 end) as `MTDthisyearRoomNights`,
sum(case when (`".TBL_ORDERS."`.booking_status = '1' ) and ( ( DATE(`".TBL_ORDER_DETAIL."`.dated) between '".$FromLastYearDate."' AND '".$ToLastYearDate."' )) then `fs_order_detail`.room_quantity else 0 end) as `LastYearMTDRoomNights`,

SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".date('Y-m-d',strtotime($from_book))."' AND '".date('Y-m-d',strtotime($to_book))."'  THEN `fs_order_detail`.tarrif_price ELSE 0 END ) AS ThisYearMTDconfimed_revenue,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1')  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".$FromLastYearDate."' AND '".$ToLastYearDate."'  THEN `fs_order_detail`.tarrif_price ELSE 0 END ) AS LastYearMTDconfimed_revenue,
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".$FromLastToLastYearDate."' AND '".$ToLastToLastYearDate."' THEN `fs_order_detail`.room_quantity else 0 end) AS newConfirmedLastToLastYear,    		
SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated) BETWEEN '".$FromLastToLastYearDate."' AND '".$ToLastToLastYearDate."' THEN `fs_order_detail`.tarrif_price ELSE 0 END ) AS confimed_revenueLastToLastYear
  


    		
    	FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_hotel 
 LEFT JOIN `".TBL_ZONAL."` ON `".TBL_ZONAL."`.id = `".TBL_HOTELS."`.zonal
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where    `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."'  ".$allUser." ".$condBOB.$HotelFilterConn."  GROUP BY `".TBL_ORDERS."`.id_hotel ,`".TBL_HOTELS."`.zonal  Order BY `".TBL_GROUP_MASTER."`.display_order,`fs_users`.myownteam_id";
       

   
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

$daysMTD   =   strtotime("+1 day",strtotime($to_book));
$daysYTD   =   strtotime("+1 day",strtotime($to_book));
		
	    $MTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_book) - $daysMTD) / 86400);
		$YTDtotalRoominventory = $row['totalRoominventory']*ceil(abs(strtotime($from_book) - $daysYTD) / 86400);
	    
	       $ThisYearConfirmandTendRoomNights= ($rowList->ThisYearConfirmandTendRoomNights);
           $LastYearConfirmandTendRoomNights= ($rowList->LastYearConfirmandTendRoomNights);
           $MTDthisyearRoomNights= ($rowList->MTDthisyearRoomNights);
           $LastYearMTDRoomNights= ($rowList->LastYearMTDRoomNights);
           $YTDthisyearRoomNights= ($rowList->YTDthisyearRoomNights);
           $LastYearYTDRoomNights= ($rowList->LastYearYTDRoomNights);
           $ThisYearMTDconfimed_revenue= ($rowList->ThisYearMTDconfimed_revenue);
	        $newConfirmednewTentativeLastToLastYear=($rowList->newConfirmedLastToLastYear);
			$newConfirmednewTentative_revenueLastToLastYear=($rowList->confimed_revenueLastToLastYear);
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
		 $reportZonalArray['Hotelwise Summary']['MainTotalRoomNights']['MsubtotalRoomNights'] +=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;  
		 
		 $reportZonalArray['Hotelwise Summary']['MainTotalConfimedRevenue']['MsubtotalConfimedRevenue'] +=$ThisYearMTDconfimed_revenue==''?'0':$ThisYearMTDconfimed_revenue;  
	    
		  $reportZonalArray['Hotelwise Summary'][$rowList->zonename]['MTDthisyearRoomNightsSubTotal']['subtotalRoomNights'] +=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;  
		 
		 $reportZonalArray['Hotelwise Summary'][$rowList->zonename]['MTDthisyearConfimedRevenueSubTotal']['subtotalConfimedRevenue'] +=$ThisYearMTDconfimed_revenue==''?'0':$ThisYearMTDconfimed_revenue;  
	    
		$reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['totalRooms']=$row['totalRoominventory'];
	   $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['roomnights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['confimed_revenue']=$newConfirmednewTentative_revenue==''?0:round($newConfirmednewTentative_revenue);
	    
	    //$reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearConfirmandTendRoomNights']=$ThisYearConfirmandTendRoomNightsValue==''?'0':$ThisYearConfirmandTendRoomNightsValue;
	   //$reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearConfirmandTendRoomNights']=$LastYearConfirmandTendRoomNightsValue==''?0:round($LastYearConfirmandTendRoomNightsValue);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['MTDthisyearRoomNights']=$MTDthisyearRoomNights==''?'0':$MTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDRoomNights']=$LastYearMTDRoomNights==''?0:round($LastYearMTDRoomNights);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearRoomNights']=$YTDthisyearRoomNights==''?'0':$YTDthisyearRoomNights;
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearYTDRoomNights']=$LastYearYTDRoomNights==''?0:round($LastYearYTDRoomNights);
	    $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnights']    =   $LastYearYTDRoomNights==''?'0':$LastYearYTDRoomNights;
	    
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearYTDconfimed_revenue']    =   $lastYearYTDconfimed_revenueTen==''?'0':$lastYearYTDconfimed_revenueTen;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDthisyearconfimed_revenue']    =   $YTDthisyearconfimed_revenue==''?'0':$YTDthisyearconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['LastYearMTDconfimed_revenue']    =   $LastYearMTDconfimed_revenue==''?'0':$LastYearMTDconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['MTDRoominventory']    =   $MTDtotalRoominventory;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['YTDRoominventory']    =   $YTDtotalRoominventory;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['ThisYearMTDconfimed_revenue']    =   $ThisYearMTDconfimed_revenue;
	     $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearroomnightsLastToLastYear']    = $newConfirmednewTentativeLastToLastYear==''?'0':$newConfirmednewTentativeLastToLastYear;
		 $reportZonalArray['Hotelwise Summary'][$rowList->zonename][strtolower($companyname==''?$emptytext7:$companyname)]['lastYearconfimed_revenueLastToLastYear']    = $newConfirmednewTentative_revenueLastToLastYear==''?'0':$newConfirmednewTentative_revenueLastToLastYear;
	      
   	    //}
	}  
  } 
    
      
 
  
 
 
 
  

	
		array_push($mtdLastValues, ($prevYearRoomNightsMtd==''?0:$prevYearRoomNightsMtd));
		array_push($mtdThisValues, ($ThisMonthRoomNightsMtd==''?0:$ThisMonthRoomNightsMtd));
		
		

		

	
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

//print_r($mtdThisValues);
//echo '<pre>';
//debugData($reportZonalArray);
//die;
if($reportViewGrapTable==0){

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
        border: 1px solid #705a5a !important;

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
						  <th colspan="24">
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />&nbsp;&nbsp;&nbsp; </th>';

						  
$content .= '</tr>	
			</table>
	    ';
  $content .=    '<br><br><br><br><br><br><br><br><br>
 <table class="table table-striped text-center">
	<tr class="marginright" style="vertical-align:central;text-align:center;"><th colspan="26" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br><br>';
	/*   $content .=    '<br><div id="table-wrapper">
  <div id="table-scroll"><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="11" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; */
}


  foreach($reportZonalArray as $maintitle=>$reportArray){
	  
	  
	   $contentTeam .='<table class="table table-striped text-center" >';
	$contentTeam .='<tr><th colspan="26" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle.' Breakup For Period '.$reportPeriod.'</b></th></tr>';
   
foreach($reportArray as $Zonal=>$mainDatalist){
	
	//debugData($mainDatalist);
	 if($Zonal=='MainTotalRoomNights'  ){
			$MainTotalValueRoomNights	=$mainDatalist['MsubtotalRoomNights'];			  
		 }
		 if( $Zonal=='MainTotalConfimedRevenue' ){
			  $MainTotalValueConfimedRevenue	=$mainDatalist['MsubtotalConfimedRevenue'];	
		 }
	
     if($Zonal!='MainTotalRoomNights'  && $Zonal!='MainTotalConfimedRevenue'){
		
    $contentTeam .='<tr ><th colspan="27" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$Zonal.'</b></th></tr>';
   
   // $contentTeam .='<tr>';
    // $contentTeam .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
   // $contentTeam .='<th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;" class="marginright">MTD</th>';
   // $contentTeam .='<th colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">QTD</th>';
  //  $contentTeam .='<th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">YTD</th></tr>';
    
    
     $contentTeam .='<tr class="marginright" style="vertical-align:central;"> <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
   ';
  
   $contentTeam .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>INV </b></th>';
   $contentTeam .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Room </b></th>';
   
 $contentTeam .='  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Variance</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>';
  
  
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  
   $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.' Lacs </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'  </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
 
  $contentTeam .='<th class="marginright" colspan="2" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>% of Contribution '.$CurrentFinancialYearDate.'  </b></th>

  ';
 
  
   $contentTeam .='</tr>';
   $contentTeam .='<tr >';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;height: 47px; width:150px;float:left; ">Name</th>';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';

 $contentTeam .='    <th class="marginright" colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';

 $contentTeam .='    <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>';
   $contentTeam .=' <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeam .='<th class="marginright" class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Occupancy%</th>';
    
    $contentTeam .='    <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';
   $contentTeam .=' <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeam .='</tr>';
    
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	// $mainDatalist=array(); 
    $TotalLastYearMTDconfimed_revenueshow='';
    $TotalLastToLastYearMTDconfimed_revenue='';
    foreach($mainDatalist as   $key=> $Data){ 
$RoomNights[$key] =$Data['YTDthisyearRoomNights'];
$lastYearYTDconfimed_revenue[$key] = $Data['lastYearYTDconfimed_revenue'];
//$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($mainDatalist, 'YTDthisyearRoomNights');
$lastYearYTDconfimed_revenue = array_column($GroupArray, 'lastYearYTDconfimed_revenue');
array_multisort($RoomNights, SORT_DESC, $mainDatalist);  
    
    $TotalLastYearMTDRoomNights='';
	$TotalMTDthisyearRoomNights='';
	$TotalLastYearYTDRoomNights='';
	$TotalYTDthisyearRoomNights='';
	$TotalLastToLastYearRoomNights='';
	 $TotalMTDRoominventory='';
	 $TotalYTDRoominventory='';
		$TotalLastYearMTDconfimed_revenueArr              ='';
		$TotalThisYearMTDconfimed_revenueArr               ='';
		$TotalLastToLastYearMTDconfimed_revenueArr		='';
		$TotalThisYearMTDconfimed_revenueshow='';
		$subTotalRooms='';
		//Subtotal For Contribution 
		/*foreach($mainDatalist as $teamGroupContribution=>$DataListContribution){
			echo $teamGroupContribution;
			print_r($DataListContribution);
			
		}*/
		//Subtotal For Contribution 
		
		
		
		//debugData($mainDatalist);
     foreach($mainDatalist as $teamGroup=>$DataList){
		
		 if($teamGroup=='MTDthisyearRoomNightsSubTotal'  ){
			 $subTotalValueRoomNights	=$DataList['subtotalRoomNights'];			  
		 }
		 if( $teamGroup=='MTDthisyearConfimedRevenueSubTotal' ){
			  $subTotalValueConfimedRevenue	=$DataList['subtotalConfimedRevenue'];	
		 }
		 if($teamGroup!='MTDthisyearRoomNightsSubTotal'  && $teamGroup!='MTDthisyearConfimedRevenueSubTotal'){
			
       $TotalLastYearMTDRoomNights+=   $DataList['LastYearMTDRoomNights'];
       $TotalMTDthisyearRoomNights+=   $DataList['MTDthisyearRoomNights'];
       $TotalLastYearYTDRoomNights+=   $DataList['LastYearYTDRoomNights'];
       $TotalYTDthisyearRoomNights+= $DataList['YTDthisyearRoomNights'];
	   $TotalLastToLastYearRoomNights+=$DataList['lastYearroomnightsLastToLastYear'];
       
       $TotalMTDRoominventory+= $DataList['MTDRoominventory'];
       $TotalYTDRoominventory+= $DataList['YTDRoominventory'];
       
       
            $GolyMTDRoomNights         =   $DataList['LastYearMTDRoomNights']>0?round((($DataList['MTDthisyearRoomNights']-$DataList['LastYearMTDRoomNights'])/$DataList['LastYearMTDRoomNights'])*100):'0';
            $GolyMTDConfimedRevenue    =   $DataList['LastYearMTDconfimed_revenue']>0?round((($DataList['ThisYearMTDconfimed_revenue']-$DataList['LastYearMTDconfimed_revenue'])/$DataList['LastYearMTDconfimed_revenue'])*100):'0';
            
			$golyRowWiseRoomNightsLastToLastYear    = $DataList['lastYearroomnightsLastToLastYear']>0?(round((($DataList['MTDthisyearRoomNights']-$DataList['lastYearroomnightsLastToLastYear'])/$DataList['lastYearroomnightsLastToLastYear']) *100,2)):0;
            $GolyConfimedRevenueLastToLastYear    =   $DataList['lastYearconfimed_revenueLastToLastYear']>0?round((($DataList['ThisYearMTDconfimed_revenue']-$DataList['lastYearconfimed_revenueLastToLastYear'])/$DataList['lastYearconfimed_revenueLastToLastYear'])*100):'0';
            
			
			
            $GolyYTDRoomNights         =   $DataList['LastYearYTDRoomNights']>0?round((($DataList['YTDthisyearRoomNights']-$DataList['LastYearYTDRoomNights'])/$DataList['LastYearYTDRoomNights'])*100):'0';
            $GolyYTDConfimedRevenue    =    $DataList['lastYearYTDconfimed_revenue']>0?round((($DataList['YTDthisyearconfimed_revenue']-$DataList['lastYearYTDconfimed_revenue'])/$DataList['lastYearYTDconfimed_revenue'])*100):'0';
            
            $OccpanMTDLastYear  =    $DataList['MTDRoominventory']>0?round((($DataList['LastYearMTDRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanMTDThisYear  =    $DataList['MTDRoominventory']>0?round((($DataList['MTDthisyearRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanGolyMTD      =    $OccpanMTDLastYear>0?round((($OccpanMTDThisYear-$OccpanMTDLastYear)/$OccpanMTDLastYear)*100,2):'0';
            
            
            $OccpanLastToLastYear  =    $DataList['MTDRoominventory']>0?round((($DataList['lastYearroomnightsLastToLastYear'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanLastToThisYear  =    $DataList['MTDRoominventory']>0?round((($DataList['MTDthisyearRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanGolyOccpanLastToThisYear      =    $OccpanLastToLastYear>0?round((($OccpanLastToLastYear-$OccpanLastToThisYear)/$OccpanLastToLastYear)*100,2):'0';
            
            $TotalGolyYTDRoomNights  +=   $GolyYTDRoomNights;
            $TotalLastYearMTDconfimed_revenue +=$GolyMTDRoomNights;
            
            $TotalLastYearMTDconfimed_revenueshow               +=round($DataList['LastYearMTDconfimed_revenue']/100000,2);
            $TotalThisYearMTDconfimed_revenueshow               +=round($DataList['ThisYearMTDconfimed_revenue']/100000,2);
            $TotalLastToLastYearMTDconfimed_revenue			 +=round($DataList['lastYearconfimed_revenueLastToLastYear']/100000,2);
			
			$TotalLastYearMTDconfimed_revenueArr               +=$DataList['LastYearMTDconfimed_revenue'];
            $TotalThisYearMTDconfimed_revenueArr               +=$DataList['ThisYearMTDconfimed_revenue'];
            $TotalLastToLastYearMTDconfimed_revenueArr		 +=$DataList['lastYearconfimed_revenueLastToLastYear'];
			
			
			$subTotalRooms	+=	$DataList['totalRooms'];
            $TotalThisYearMTDconfimed_revenue +=$GolyMTDConfimedRevenue;
            $TotalLastYearYTDconfimed_revenue +=$GolyYTDRoomNights;
            $TotalThisYearYTDconfimed_revenue +=$GolyYTDConfimedRevenue;
            
            $TotalOccpanMTDLastYear+=$OccpanMTDLastYear;
            $TotalOccpanMTDThisYear+=$OccpanMTDThisYear;
			
			$TotalOccpanLastToLastYear+=$OccpanLastToLastYear;
            
            $TotalOccpanYTDLastYear+=$OccpanYTDLastYear;
            $TotalOccpanYTDThisYear+=$OccpanYTDThisYear;
            
			//Arr---------------------------------------
			
			$ArrLastToLastYear=$DataList['lastYearroomnightsLastToLastYear']>0?round($DataList['lastYearconfimed_revenueLastToLastYear']/$DataList['lastYearroomnightsLastToLastYear']):0;
			$ArrLastYear	 =$DataList['LastYearMTDRoomNights']>0 ? round($DataList['LastYearMTDconfimed_revenue']/$DataList['LastYearMTDRoomNights']):0;
			$ArrThisYear	=$DataList['MTDthisyearRoomNights']>0 ? round($DataList['ThisYearMTDconfimed_revenue']/$DataList['MTDthisyearRoomNights']):0;
			
			 $ArrGolyThisYear      =    $ArrLastYear>0?round((($ArrThisYear-$ArrLastYear)/$ArrLastYear)*100,2):'0';
			 $ArrGolyLastYear      =    $ArrLastToLastYear>0?round((($ArrThisYear-$ArrLastToLastYear)/$ArrLastToLastYear)*100,2):'0';
            
			//Arr---------------------------------------
			$varient	=($DataList['MTDthisyearRoomNights']-$DataList['LastYearMTDRoomNights']);
			$Colorvarient=$varient>=0?"":"color:#FF0000;";
			$ColorgolyRowWiseRoomNightsLastToLastYear=$golyRowWiseRoomNightsLastToLastYear>=0?"":"color:#FF0000;";
			$ColorGolyMTDRoomNights=$GolyMTDRoomNights>=0?"":"color:#FF0000;";
			
			
			
$ColorArrGolyLastYear = $ArrGolyLastYear>=0?"":"color:#FF0000;";
$ColorArrGolyThisYear = $ArrGolyThisYear>=0?"":"color:#FF0000;";
             $ColorGolyConfimedRevenueLastToLastYear=   $GolyConfimedRevenueLastToLastYear>=0?"":"color:#FF0000;";
			$ColorGolyMTDConfimedRevenue=	$GolyMTDConfimedRevenue>=0?"":"color:#FF0000;";
			
			
    $contentTeam .='<tr class="marginright">';
       $contentTeam .='          <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#fff; "><b>'.strtoupper($teamGroup).'</b></th>';
                
     $contentTeam .='            <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; "><b>' .$DataList['MTDRoominventory'].'</b></th>';
	 $contentTeam .='            <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; "><b>' .$DataList['totalRooms'].'</b></th>';
$contentTeam .='          <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$DataList['lastYearroomnightsLastToLastYear'].'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>' .$DataList['LastYearMTDRoomNights'].'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$DataList['MTDthisyearRoomNights'].'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$Colorvarient.'"><b>'.$varient.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorgolyRowWiseRoomNightsLastToLastYear.'"><b>'.$golyRowWiseRoomNightsLastToLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorGolyMTDRoomNights.'"><b>'.$GolyMTDRoomNights.'</b></th>';

				 
$contentTeam .='          <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrLastToLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrThisYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorArrGolyLastYear.'"><b>'.$ArrGolyLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorArrGolyThisYear.'"><b>'.$ArrGolyThisYear.'</b></th>
';

                $contentTeam .=' <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>'.round($DataList['lastYearconfimed_revenueLastToLastYear']/100000,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>' .round($DataList['LastYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b> '.round($DataList['ThisYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; ;'.$ColorGolyConfimedRevenueLastToLastYear.'"><b> '.$GolyConfimedRevenueLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;;background-color:#e2f7b8; ;'.$ColorGolyMTDConfimedRevenue.'"><b>'.$GolyMTDConfimedRevenue.'</b></th>';
                
                //Occpeancy
				$ColorOccpanGolyOccpanLastToThisYear = $OccpanGolyOccpanLastToThisYear>=0?'':"color:#FF0000;";
				$ColorOccpanGolyMTD	= $OccpanGolyMTD>=0?'':"color:#FF0000;";
                $contentTeam .='				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$OccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$OccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$OccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;background-color:#ff; ;'.$ColorOccpanGolyOccpanLastToThisYear.'"><b> '.$OccpanGolyOccpanLastToThisYear.'</b></th>
								 <th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorOccpanGolyMTD.'"><b>'.$OccpanGolyMTD.'</b></th>';
                
              $contentTeam .='				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.round(($DataList['MTDthisyearRoomNights']/$MainTotalValueRoomNights)*100,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.round(($DataList['ThisYearMTDconfimed_revenue']/$MainTotalValueConfimedRevenue)*100,2).'</b></th>';
                
                /*
                $contentTeam .='
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; "><b>' .$DataList['YTDRoominventory'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>' .$DataList['LastYearYTDRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$DataList['YTDthisyearRoomNights'].'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$GolyYTDRoomNights.'</b></th>
                
                
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>' .round($DataList['lastYearYTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b> '.round($DataList['YTDthisyearconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>'.$GolyYTDConfimedRevenue.'</b></th>';
                 
                 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$OccpanYTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$OccpanYTDThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ff; "><b>'.$OccpanGolyYTD.'</b></th>';
                */
                $contentTeam .='</tr>';
    
	 }
	 }
     //SUB TOTAL START=============================================================
	 $contentTeamTopSheet='';
$TotalSumGolyMTDRoomNights         =   $TotalLastYearMTDRoomNights>0?round((($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights)/$TotalLastYearMTDRoomNights)*100):'0';
$TotalSumGolyMTDRevenue            =   $TotalLastYearMTDconfimed_revenue>0?round((($TotalThisYearMTDconfimed_revenue-$TotalLastYearMTDconfimed_revenue)/$TotalLastYearMTDconfimed_revenue)*100):'0';

$TotalSumGolyLastToLastYearRoomNights         =   $TotalLastToLastYearRoomNights>0?round((($TotalMTDthisyearRoomNights-$TotalLastToLastYearRoomNights)/$TotalLastToLastYearRoomNights)*100):'0';
$TotalSumGolyLastToLastYearRevenue            =   $TotalLastToLastYearMTDconfimed_revenue>0?round((($TotalThisYearMTDconfimed_revenue-$TotalLastToLastYearMTDconfimed_revenue)/$TotalLastToLastYearMTDconfimed_revenue)*100):'0';


$TotalSumGolyYTDRoomNights         =   $TotalLastYearYTDRoomNights>0?round((($TotalYTDthisyearRoomNights-$TotalLastYearYTDRoomNights)/$TotalLastYearYTDRoomNights)*100):'0';
$TotalSumGolyYTDRevenue            =   $TotalLastYearYTDconfimed_revenue>0?round((($TotalThisYearYTDconfimed_revenue-$TotalLastYearYTDconfimed_revenue)/$TotalLastYearYTDconfimed_revenue)*100):'0';


$TotalOccpanMTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanMTDLastYear)/$TotalMTDRoominventory)*100):'0';

$TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanMTDThisYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanGolyMTD      =    $TotalOccpanMTDLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanMTDLastYear)/$TotalOccpanMTDLastYear)*100,2):'0';


$TotalOccpanYTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanYTDLastYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanYTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanYTDThisYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanGolyYTD      =    $TotalOccpanYTDLastYear>0?round((($TotalOccpanYTDThisYear-$TotalOccpanYTDLastYear)/$TotalOccpanYTDLastYear)*100,2):'0';


//Arr Sub Total---------------------------------------
		
$ArrTotalLastToLastYear=$TotalLastToLastYearMTDconfimed_revenueArr>0?round($TotalLastToLastYearMTDconfimed_revenueArr/$TotalLastToLastYearRoomNights):0;
$ArrTotalLastYear	 =$TotalLastYearMTDconfimed_revenueArr>0 ? round($TotalLastYearMTDconfimed_revenueArr/$TotalLastYearMTDRoomNights):0;
$ArrTotalThisYear	=$TotalThisYearMTDconfimed_revenueArr>0 ? round($TotalThisYearMTDconfimed_revenueArr/$TotalMTDthisyearRoomNights):0;


$ArrGolyTotalThisYear      =    $ArrTotalLastYear>0?round((($ArrTotalThisYear-$ArrTotalLastYear)/$ArrTotalLastYear)*100,2):'0';
$ArrGolyTotalLastYear      =    $ArrTotalLastToLastYear>0?round((($ArrTotalThisYear-$ArrTotalLastToLastYear)/$ArrTotalLastToLastYear)*100,2):'0';

//Arr Sub Total---------------------------------------       


				
				
				
				
				
                $TotalOccpanMTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalLastYearMTDRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalMTDthisyearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpancy = $TotalOccpanMTDLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanMTDLastYear)/$TotalOccpanMTDLastYear)*100,2):'0';
				
                $TotalOccpanLastToLastYear  =    $TotalMTDRoominventory>0?round((($TotalLastToLastYearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalMTDthisyearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpancyLastToLastTo = $TotalOccpanLastToLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanLastToLastYear)/$TotalOccpanLastToLastYear)*100,2):'0';
				
				
$contentTeam .='<tr class="marginright">';
               $contentTeam .=' <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#c2d69a; "><b>'.strtoupper($Zonal).'</b></th>';
                
				
				
                 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalMTDRoominventory.'</b></th>';
				 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$subTotalRooms.'</b></th>';
                 
               $contentTeam .='
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalLastToLastYearRoomNights.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalMTDthisyearRoomNights.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalSumGolyLastToLastYearRoomNights.'</b></th>
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalSumGolyMTDRoomNights.'</b></th>';
                
                $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrTotalLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrTotalLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$ArrTotalThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrGolyTotalLastYear.'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrGolyTotalThisYear.'</b></th>';
                 $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalLastToLastYearMTDconfimed_revenue.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalLastYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalThisYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastToLastYearMTDconfimed_revenue)/$TotalLastToLastYearMTDconfimed_revenue*100,2).'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastYearMTDconfimed_revenueshow)/$TotalLastYearMTDconfimed_revenueshow*100,2).'</b></th>';
                
				
				
				
                
                $contentTeam .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpancyLastToLastTo.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpancy.'</b></th>';
                
                $contentTeam .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalMTDthisyearRoomNights/$MainTotalValueRoomNights)*100,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalThisYearMTDconfimed_revenueArr/$MainTotalValueConfimedRevenue)*100,2).'</b></th>
                </th>';
                $contentTeam .='</tr>';
			
			
							
				


//Topsheet start
     $contentTeamTopSheet .='<tr class="marginright">';
               $contentTeamTopSheet .=' <th  style="vertical-align:central;width:200px;text-align:Left; "><b>'.strtoupper($Zonal).'</b></th>';
                
				
				
                 $contentTeamTopSheet .='<th  style="vertical-align:central;text-align:center; "><b>' .$TotalMTDRoominventory.'</b></th>';
				 $contentTeamTopSheet .='<th  style="vertical-align:central;text-align:center; "><b>' .$subTotalRooms.'</b></th>';
                 
               $contentTeamTopSheet .='
			    <th  style="vertical-align:central;text-align:center; "><b>'.$TotalLastToLastYearRoomNights.'</b></th>
				 <th  style="vertical-align:central;text-align:center; "><b>' .$TotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b> '.$TotalMTDthisyearRoomNights.'</b></th>
				<th  style="vertical-align:central;text-align:center; "><b>'.($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights).'</b></th>
				<th  style="vertical-align:central;text-align:center; "><b>'.$TotalSumGolyLastToLastYearRoomNights.'</b></th>
			    <th  style="vertical-align:central;text-align:center; "><b>'.$TotalSumGolyMTDRoomNights.'</b></th>';
                
                $contentTeamTopSheet .='
				 <th  style="vertical-align:central;text-align:center; "><b>'.$ArrTotalLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center; "><b>'.$ArrTotalLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b> '.$ArrTotalThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b>'.$ArrGolyTotalLastYear.'</b></th>
 			    <th  style="vertical-align:central;text-align:center; "><b>'.$ArrGolyTotalThisYear.'</b></th>';
                 $contentTeamTopSheet .='
				 <th  style="vertical-align:central;text-align:center; "><b>'.$TotalLastToLastYearMTDconfimed_revenue.'</b></th>
				 <th  style="vertical-align:central;text-align:center; "><b>' .$TotalLastYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b> '.$TotalThisYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b> '.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastToLastYearMTDconfimed_revenue)/$TotalLastToLastYearMTDconfimed_revenue*100,2).'</b></th>
 			    <th  style="vertical-align:central;text-align:center; "><b>'.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastYearMTDconfimed_revenueshow)/$TotalLastYearMTDconfimed_revenueshow*100,2).'</b></th>';
                
				
				
				
                
                $contentTeamTopSheet .='
				<th  style="vertical-align:central;text-align:center; "><b>'.$TotalOccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center; "><b>'.$TotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center; "><b> '.$TotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center; "><b> '.$TotalOccpancyLastToLastTo.'</b></th>
				<th  style="vertical-align:central;text-align:center; "><b>'.$TotalOccpancy.'</b></th>';
                
                $contentTeamTopSheet .='
				<th  style="vertical-align:central;text-align:center; "><b>'.round(($TotalMTDthisyearRoomNights/$MainTotalValueRoomNights)*100,2).'</b></th>
				<th  style="vertical-align:central;text-align:center; "><b>'.round(($TotalThisYearMTDconfimed_revenueArr/$MainTotalValueConfimedRevenue)*100,2).'</b></th>
                </th>';
                $contentTeamTopSheet .='</tr>';
				
				
				$contMaintTotalRN += round(($TotalMTDthisyearRoomNights/$MainTotalValueRoomNights)*100,2);
                $contMaintTotalCR += round(($TotalThisYearMTDconfimed_revenueArr/$MainTotalValueConfimedRevenue)*100,2);
				      
                
    
    //$contentTeam .=$contentTeamTopSheet;
	$contentTeamTopSheetContent .=$contentTeamTopSheet;
    
     $MainTotalMTDRoominventory += $TotalMTDRoominventory;
     $MainTotalLastToLastYearRoomNights 	+= $TotalLastToLastYearRoomNights;
	 $MainTotalLastYearMTDRoomNights       +=$TotalLastYearMTDRoomNights;
	 $MainTotalMTDthisyearRoomNights	   +=$TotalMTDthisyearRoomNights;
     
	 
	 $MainTotalLastToLastYearMTDconfimed_revenue +=$TotalLastToLastYearMTDconfimed_revenue;
	 $MainTotalLastYearMTDconfimed_revenueshow +=$TotalLastYearMTDconfimed_revenueshow;
	 $MainTotalThisYearMTDconfimed_revenueshow+=$TotalThisYearMTDconfimed_revenueshow;
    // $MainTotalLastYearMTDRoomNights
	 //$TotalSumGolyMTDRoomNights
$MainTotalLastYearMTDconfimed_revenueArr               +=$TotalLastYearMTDconfimed_revenueArr;
$MainTotalThisYearMTDconfimed_revenueArr               +=$TotalThisYearMTDconfimed_revenueArr;
$MainTotalLastToLastYearMTDconfimed_revenueArr		 +=$TotalLastToLastYearMTDconfimed_revenueArr;
$MainTotalRooms	+=$subTotalRooms;
	 
}
}


//Arr Sub Total---------------------------------------
		
$ArrMainTotalLastToLastYear=$MainTotalLastToLastYearMTDconfimed_revenueArr>0?round($MainTotalLastToLastYearMTDconfimed_revenueArr/$MainTotalLastToLastYearRoomNights):0;
$ArrMainTotalLastYear	 =$MainTotalLastYearMTDconfimed_revenueArr>0 ? round($MainTotalLastYearMTDconfimed_revenueArr/$MainTotalLastYearMTDRoomNights):0;
$ArrMainTotalThisYear	=$MainTotalThisYearMTDconfimed_revenueArr>0 ? round($MainTotalThisYearMTDconfimed_revenueArr/$MainTotalMTDthisyearRoomNights):0;


$ArrMainGolyTotalThisYear      =    $ArrMainTotalLastYear>0?round((($ArrMainTotalThisYear-$ArrMainTotalLastYear)/$ArrMainTotalLastYear)*100,2):'0';
$ArrMainGolyTotalLastYear      =    $ArrMainTotalLastToLastYear>0?round((($ArrMainTotalThisYear-$ArrMainTotalLastToLastYear)/$ArrMainTotalLastToLastYear)*100,2):'0';

//Arr Sub Total--------------------------------------- 


$contentTeamMainTotal .='<tr class="marginright">';
               $contentTeamMainTotal .=' <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#c2d69a; "><b>'.strtoupper(' Total').'</b></th>';
                
                 $contentTeamMainTotal .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalMTDRoominventory.'</b></th>';
				 $contentTeamMainTotal .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalRooms.'</b></th>';
                 
               $contentTeamMainTotal .='
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalLastToLastYearRoomNights.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalMTDthisyearRoomNights.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.($MainTotalMTDthisyearRoomNights-$MainTotalLastYearMTDRoomNights).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalMTDthisyearRoomNights-$MainTotalLastToLastYearRoomNights)/$MainTotalLastToLastYearRoomNights*100,2).'</b></th>
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalMTDthisyearRoomNights-$MainTotalLastYearMTDRoomNights)/$MainTotalLastYearMTDRoomNights*100,2).'</b></th>';
                
                $contentTeamMainTotal .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainTotalLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainTotalLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$ArrMainTotalThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainGolyTotalLastYear.'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainGolyTotalThisYear.'</b></th>';
                 $contentTeamMainTotal .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalLastToLastYearMTDconfimed_revenue.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalLastYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalThisYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.round(($MainTotalThisYearMTDconfimed_revenueshow-$MainTotalLastToLastYearMTDconfimed_revenue)/$MainTotalLastToLastYearMTDconfimed_revenue*100,2).'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalThisYearMTDconfimed_revenueshow-$MainTotalLastYearMTDconfimed_revenueshow)/$MainTotalLastYearMTDconfimed_revenueshow*100,2).'</b></th>';
                
                $MainTotalOccpanMTDLastYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalLastYearMTDRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $MainTotalOccpanMTDThisYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalMTDthisyearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $TotalOccpancy = $MainTotalOccpanMTDLastYear>0?round((($MainTotalOccpanMTDThisYear-$MainTotalOccpanMTDLastYear)/$MainTotalOccpanMTDLastYear)*100,2):'0';
				
                $MainTotalOccpanLastToLastYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalLastToLastYearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $MainTotalOccpanMTDThisYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalMTDthisyearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $TotalOccpancyLastToLastTo = $MainTotalOccpanLastToLastYear>0?round((($MainTotalOccpanMTDThisYear-$MainTotalOccpanLastToLastYear)/$MainTotalOccpanLastToLastYear)*100,2):'0';
				
				
                
                $contentTeamMainTotal .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalOccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpancyLastToLastTo.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpancy.'</b></th>';
                $contentTeamMainTotal .='
				<th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$contMaintTotalRN.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$contMaintTotalCR.'</b></th>';
                
                
                      $contentTeamMainTotal .='</tr>';
$contentTeam .=$contentTeamMainTotal;

$contentTeam .= '</table><br/><br/>';$contentGroup .= '</table>';


$content .=$contentGroup;
         $contentTeamTopHead .='<table class="table table-striped text-center" >';
     $contentTeamTopHead .='<tr class="marginright" style="vertical-align:central;"> <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
   ';
  
   $contentTeamTopHead .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>INV </b></th>';
   $contentTeamTopHead .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Room </b></th>';
   
 $contentTeamTopHead .='  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Variance</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>';
  
  
  $contentTeamTopHead .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  
   $contentTeamTopHead .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.' Lacs </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  $contentTeamTopHead .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'  </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
 
  $contentTeamTopHead .='<th class="marginright" colspan="2" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>% of Contribution '.$CurrentFinancialYearDate.'  </b></th>

  ';
 
  
   $contentTeamTopHead .='</tr>';
   $contentTeamTopHead .='<tr >';
 $contentTeamTopHead .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;height: 47px;background-color:#c2d69a; width:150px;float:left; ">Name</th>';
 $contentTeamTopHead .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';
 $contentTeamTopHead .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';

 $contentTeamTopHead .='    <th class="marginright" colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';

 $contentTeamTopHead .='    <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>';
   $contentTeamTopHead .=' <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeamTopHead .='<th class="marginright" class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Occupancy%</th>';
    
    $contentTeamTopHead .='    <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';
   $contentTeamTopHead .=' <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeamTopHead .='</tr>';
		 
		 
		 
		$content.= $contentTeamTopHead.$contentTeamTopSheetContent.$contentTeamMainTotal.'</table><br><br>'.$contentTeam;
         
         
         //$contentGroup='';
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
           /* if($_REQUEST['reportType']==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='CompareView-PickupRepor_'.date("Y-m-d H:i:s").'.xls';
                    }
                    if($_REQUEST['reportType']==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='CompareView-BobReport_'.date("Y-m-d H:i:s").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename");
        echo $test;die;*/
         if($_REQUEST['reportType']==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='CompareView-HotelwiseSummaryPickupReport_'.date("Y-m-d").'.xls';
                    }
                    if($_REQUEST['reportType']==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='CompareView-HotelwiseSummaryBobReport_'.date("Y-m-d").'.xls';
                    }
        $test=$content;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename");
        echo $test;die;   
    
    
		}else{
		echo $content;
		//echo json_encode($returnData);
		}

	}else{
		//$reportViewGrapTable Table View
	
	$returnData2=array();
	$GroupZoanName=array();
	
	$GroupZoanRoomNightsCYArr=array();
	$GroupZoanRoomNightsLYArr=array();
	$GroupZoanRoomNightsLLYArr=array();
	
	$GroupZoanRevenueCYArr=array();
	$GroupZoanRevenueLYArr=array();
	$GroupZoanRevenueLLYArr=array();
	
	
	
foreach($reportZonalArray as $maintitle=>$reportArray){
	  
	  
	   $contentTeam .='<table class="table table-striped text-center" >';
	$contentTeam .='<tr><th colspan="26" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.' '.$maintitle.' Breakup For Period '.$reportPeriod.'</b></th></tr>';
   
foreach($reportArray as $Zonal=>$mainDatalist){
	
	//debugData($mainDatalist);
	 if($Zonal=='MainTotalRoomNights'  ){
			$MainTotalValueRoomNights	=$mainDatalist['MsubtotalRoomNights'];			  
		 }
		 if( $Zonal=='MainTotalConfimedRevenue' ){
			  $MainTotalValueConfimedRevenue	=$mainDatalist['MsubtotalConfimedRevenue'];	
		 }
	
     if($Zonal!='MainTotalRoomNights'  && $Zonal!='MainTotalConfimedRevenue'){
		array_push($GroupZoanName,strtoupper($Zonal));
    $contentTeam .='<tr ><th colspan="27" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$Zonal.'</b></th></tr>';
   
   // $contentTeam .='<tr>';
    // $contentTeam .='<th style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Name</th>
   // $contentTeam .='<th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;" class="marginright">MTD</th>';
   // $contentTeam .='<th colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">QTD</th>';
  //  $contentTeam .='<th colspan="10" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">YTD</th></tr>';
    
    
     $contentTeam .='<tr class="marginright" style="vertical-align:central;"> <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:15px !important"><b>&nbsp; </b></th>
   ';
  
   $contentTeam .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>INV </b></th>';
   $contentTeam .='   <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Room </b></th>';
   
 $contentTeam .='  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>Variance</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>';
  
  
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'</b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  
   $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' Lacs</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.' Lacs </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
  $contentTeam .='<th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CompareFinancialYearLastToLastYear.'</b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$ComparePeriodDate.' </b></th>
  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>'.$CurrentFinancialYearDate.'  </b></th>

  <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLLY % </b></th>
    <th style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>GOLY % </b></th>
  ';
 
  $contentTeam .='<th class="marginright" colspan="2" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; "><b>% of Contribution '.$CurrentFinancialYearDate.'  </b></th>

  ';
 
  
   $contentTeam .='</tr>';
   $contentTeam .='<tr >';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; width:150px;float:left; ">Name</th>';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';
 $contentTeam .='    <th class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">&nbsp;</th>';

 $contentTeam .='    <th class="marginright" colspan="6" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';

 $contentTeam .='    <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">ARR</th>';
   $contentTeam .=' <th class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeam .='<th class="marginright" class="marginright" colspan="5" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Occupancy%</th>';
    
    $contentTeam .='    <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Room Nights</th>';
   $contentTeam .=' <th class="marginright"  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Revenue</th>';
    $contentTeam .='</tr>';
    
	 $TotalRoomNight='';
	 $TotalConfimedRevenue='';
	// $mainDatalist=array(); 
    $TotalLastYearMTDconfimed_revenueshow='';
    $TotalLastToLastYearMTDconfimed_revenue='';
    foreach($mainDatalist as   $key=> $Data){ 
$RoomNights[$key] =$Data['YTDthisyearRoomNights'];
$lastYearYTDconfimed_revenue[$key] = $Data['lastYearYTDconfimed_revenue'];
//$CancelledRoomNights[$key] = $Data['CancelledRoomNights'];

}
$RoomNights  = array_column($mainDatalist, 'YTDthisyearRoomNights');
$lastYearYTDconfimed_revenue = array_column($GroupArray, 'lastYearYTDconfimed_revenue');
array_multisort($RoomNights, SORT_DESC, $mainDatalist);  
    
    $TotalLastYearMTDRoomNights='';
	$TotalMTDthisyearRoomNights='';
	$TotalLastYearYTDRoomNights='';
	$TotalYTDthisyearRoomNights='';
	$TotalLastToLastYearRoomNights='';
	 $TotalMTDRoominventory='';
	 $TotalYTDRoominventory='';
		$TotalLastYearMTDconfimed_revenueArr              ='';
		$TotalThisYearMTDconfimed_revenueArr               ='';
		$TotalLastToLastYearMTDconfimed_revenueArr		='';
		$TotalThisYearMTDconfimed_revenueshow='';
		$subTotalRooms='';
		
		//debugData($mainDatalist);
     foreach($mainDatalist as $teamGroup=>$DataList){
		
		 if($teamGroup=='MTDthisyearRoomNightsSubTotal'  ){
			 $subTotalValueRoomNights	=$DataList['subtotalRoomNights'];			  
		 }
		 if( $teamGroup=='MTDthisyearConfimedRevenueSubTotal' ){
			  $subTotalValueConfimedRevenue	=$DataList['subtotalConfimedRevenue'];	
		 }
		 if($teamGroup!='MTDthisyearRoomNightsSubTotal'  && $teamGroup!='MTDthisyearConfimedRevenueSubTotal'){
			
       $TotalLastYearMTDRoomNights+=   $DataList['LastYearMTDRoomNights'];
       $TotalMTDthisyearRoomNights+=   $DataList['MTDthisyearRoomNights'];
       $TotalLastYearYTDRoomNights+=   $DataList['LastYearYTDRoomNights'];
       $TotalYTDthisyearRoomNights+= $DataList['YTDthisyearRoomNights'];
	   $TotalLastToLastYearRoomNights+=$DataList['lastYearroomnightsLastToLastYear'];
       
       $TotalMTDRoominventory+= $DataList['MTDRoominventory'];
       $TotalYTDRoominventory+= $DataList['YTDRoominventory'];
       
       
            $GolyMTDRoomNights         =   $DataList['LastYearMTDRoomNights']>0?round((($DataList['MTDthisyearRoomNights']-$DataList['LastYearMTDRoomNights'])/$DataList['LastYearMTDRoomNights'])*100):'0';
            $GolyMTDConfimedRevenue    =   $DataList['LastYearMTDconfimed_revenue']>0?round((($DataList['ThisYearMTDconfimed_revenue']-$DataList['LastYearMTDconfimed_revenue'])/$DataList['LastYearMTDconfimed_revenue'])*100):'0';
            
			$golyRowWiseRoomNightsLastToLastYear    = $DataList['lastYearroomnightsLastToLastYear']>0?(round((($DataList['MTDthisyearRoomNights']-$DataList['lastYearroomnightsLastToLastYear'])/$DataList['lastYearroomnightsLastToLastYear']) *100,2)):0;
            $GolyConfimedRevenueLastToLastYear    =   $DataList['lastYearconfimed_revenueLastToLastYear']>0?round((($DataList['ThisYearMTDconfimed_revenue']-$DataList['lastYearconfimed_revenueLastToLastYear'])/$DataList['lastYearconfimed_revenueLastToLastYear'])*100):'0';
            
			
			
            $GolyYTDRoomNights         =   $DataList['LastYearYTDRoomNights']>0?round((($DataList['YTDthisyearRoomNights']-$DataList['LastYearYTDRoomNights'])/$DataList['LastYearYTDRoomNights'])*100):'0';
            $GolyYTDConfimedRevenue    =    $DataList['lastYearYTDconfimed_revenue']>0?round((($DataList['YTDthisyearconfimed_revenue']-$DataList['lastYearYTDconfimed_revenue'])/$DataList['lastYearYTDconfimed_revenue'])*100):'0';
            
            $OccpanMTDLastYear  =    $DataList['MTDRoominventory']>0?round((($DataList['LastYearMTDRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanMTDThisYear  =    $DataList['MTDRoominventory']>0?round((($DataList['MTDthisyearRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanGolyMTD      =    $OccpanMTDLastYear>0?round((($OccpanMTDThisYear-$OccpanMTDLastYear)/$OccpanMTDLastYear)*100,2):'0';
            
            
            $OccpanLastToLastYear  =    $DataList['MTDRoominventory']>0?round((($DataList['lastYearroomnightsLastToLastYear'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanLastToThisYear  =    $DataList['MTDRoominventory']>0?round((($DataList['MTDthisyearRoomNights'])/$DataList['MTDRoominventory'])*100,2):'0';
            $OccpanGolyOccpanLastToThisYear      =    $OccpanLastToLastYear>0?round((($OccpanLastToLastYear-$OccpanLastToThisYear)/$OccpanLastToLastYear)*100,2):'0';
            
            $TotalGolyYTDRoomNights  +=   $GolyYTDRoomNights;
            $TotalLastYearMTDconfimed_revenue +=$GolyMTDRoomNights;
            
            $TotalLastYearMTDconfimed_revenueshow               +=round($DataList['LastYearMTDconfimed_revenue']/100000,2);
            $TotalThisYearMTDconfimed_revenueshow               +=round($DataList['ThisYearMTDconfimed_revenue']/100000,2);
            $TotalLastToLastYearMTDconfimed_revenue			 +=round($DataList['lastYearconfimed_revenueLastToLastYear']/100000,2);
			
			$TotalLastYearMTDconfimed_revenueArr               +=$DataList['LastYearMTDconfimed_revenue'];
            $TotalThisYearMTDconfimed_revenueArr               +=$DataList['ThisYearMTDconfimed_revenue'];
            $TotalLastToLastYearMTDconfimed_revenueArr		 +=$DataList['lastYearconfimed_revenueLastToLastYear'];
			
			
			$subTotalRooms	+=	$DataList['totalRooms'];
            $TotalThisYearMTDconfimed_revenue +=$GolyMTDConfimedRevenue;
            $TotalLastYearYTDconfimed_revenue +=$GolyYTDRoomNights;
            $TotalThisYearYTDconfimed_revenue +=$GolyYTDConfimedRevenue;
            
            $TotalOccpanMTDLastYear+=$OccpanMTDLastYear;
            $TotalOccpanMTDThisYear+=$OccpanMTDThisYear;
			
			$TotalOccpanLastToLastYear+=$OccpanLastToLastYear;
            
            $TotalOccpanYTDLastYear+=$OccpanYTDLastYear;
            $TotalOccpanYTDThisYear+=$OccpanYTDThisYear;
            
			//Arr---------------------------------------
			
			$ArrLastToLastYear=$DataList['lastYearroomnightsLastToLastYear']>0?round($DataList['lastYearconfimed_revenueLastToLastYear']/$DataList['lastYearroomnightsLastToLastYear']):0;
			$ArrLastYear	 =$DataList['LastYearMTDRoomNights']>0 ? round($DataList['LastYearMTDconfimed_revenue']/$DataList['LastYearMTDRoomNights']):0;
			$ArrThisYear	=$DataList['MTDthisyearRoomNights']>0 ? round($DataList['ThisYearMTDconfimed_revenue']/$DataList['MTDthisyearRoomNights']):0;
			
			 $ArrGolyThisYear      =    $ArrLastYear>0?round((($ArrThisYear-$ArrLastYear)/$ArrLastYear)*100,2):'0';
			 $ArrGolyLastYear      =    $ArrLastToLastYear>0?round((($ArrThisYear-$ArrLastToLastYear)/$ArrLastToLastYear)*100,2):'0';
            
			//Arr---------------------------------------
			$varient	=($DataList['MTDthisyearRoomNights']-$DataList['LastYearMTDRoomNights']);
			$Colorvarient=$varient>=0?"":"color:#FF0000;";
			$ColorgolyRowWiseRoomNightsLastToLastYear=$golyRowWiseRoomNightsLastToLastYear>=0?"":"color:#FF0000;";
			$ColorGolyMTDRoomNights=$GolyMTDRoomNights>=0?"":"color:#FF0000;";
			
			
			
$ColorArrGolyLastYear = $ArrGolyLastYear>=0?"":"color:#FF0000;";
$ColorArrGolyThisYear = $ArrGolyThisYear>=0?"":"color:#FF0000;";
             $ColorGolyConfimedRevenueLastToLastYear=   $GolyConfimedRevenueLastToLastYear>=0?"":"color:#FF0000;";
			$ColorGolyMTDConfimedRevenue=	$GolyMTDConfimedRevenue>=0?"":"color:#FF0000;";
			
			
    $contentTeam .='<tr class="marginright">';
       $contentTeam .='          <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#fff; "><b>'.strtoupper($teamGroup).'</b></th>';
                
     $contentTeam .='            <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; "><b>' .$DataList['MTDRoominventory'].'</b></th>';
	 $contentTeam .='            <th  style="vertical-align:central;text-align:center;color:#000;background-color:#ccc; "><b>' .$DataList['totalRooms'].'</b></th>';
$contentTeam .='          <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$DataList['lastYearroomnightsLastToLastYear'].'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>' .$DataList['LastYearMTDRoomNights'].'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$DataList['MTDthisyearRoomNights'].'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$Colorvarient.'"><b>'.$varient.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorgolyRowWiseRoomNightsLastToLastYear.'"><b>'.$golyRowWiseRoomNightsLastToLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorGolyMTDRoomNights.'"><b>'.$GolyMTDRoomNights.'</b></th>';

				 
$contentTeam .='          <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrLastToLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$ArrThisYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorArrGolyLastYear.'"><b>'.$ArrGolyLastYear.'</b></th>
<th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorArrGolyThisYear.'"><b>'.$ArrGolyThisYear.'</b></th>
';

                $contentTeam .=' <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>'.round($DataList['lastYearconfimed_revenueLastToLastYear']/100000,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b>' .round($DataList['LastYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#e2f7b8; "><b> '.round($DataList['ThisYearMTDconfimed_revenue']/100000,2).'</b></th>
                <th  style="vertical-align:central;text-align:center;background-color:#e2f7b8; ;'.$ColorGolyConfimedRevenueLastToLastYear.'"><b> '.$GolyConfimedRevenueLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;;background-color:#e2f7b8; ;'.$ColorGolyMTDConfimedRevenue.'"><b>'.$GolyMTDConfimedRevenue.'</b></th>';
                
                //Occpeancy
				$ColorOccpanGolyOccpanLastToThisYear = $OccpanGolyOccpanLastToThisYear>=0?'':"color:#FF0000;";
				$ColorOccpanGolyMTD	= $OccpanGolyMTD>=0?'':"color:#FF0000;";
                $contentTeam .='				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$OccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.$OccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b> '.$OccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;background-color:#ff; ;'.$ColorOccpanGolyOccpanLastToThisYear.'"><b> '.$OccpanGolyOccpanLastToThisYear.'</b></th>
								 <th  style="vertical-align:central;text-align:center;background-color:#fff; ;'.$ColorOccpanGolyMTD.'"><b>'.$OccpanGolyMTD.'</b></th>';
                
              $contentTeam .='				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.round(($DataList['MTDthisyearRoomNights']/$MainTotalValueRoomNights)*100,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#fff; "><b>'.round(($DataList['ThisYearMTDconfimed_revenue']/$MainTotalValueConfimedRevenue)*100,2).'</b></th>';
                
              
                $contentTeam .='</tr>';
    
	 }
	 }
     //SUB TOTAL START=============================================================
	 
$TotalSumGolyMTDRoomNights         =   $TotalLastYearMTDRoomNights>0?round((($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights)/$TotalLastYearMTDRoomNights)*100):'0';
$TotalSumGolyMTDRevenue            =   $TotalLastYearMTDconfimed_revenue>0?round((($TotalThisYearMTDconfimed_revenue-$TotalLastYearMTDconfimed_revenue)/$TotalLastYearMTDconfimed_revenue)*100):'0';

$TotalSumGolyLastToLastYearRoomNights         =   $TotalLastToLastYearRoomNights>0?round((($TotalMTDthisyearRoomNights-$TotalLastToLastYearRoomNights)/$TotalLastToLastYearRoomNights)*100):'0';
$TotalSumGolyLastToLastYearRevenue            =   $TotalLastToLastYearMTDconfimed_revenue>0?round((($TotalThisYearMTDconfimed_revenue-$TotalLastToLastYearMTDconfimed_revenue)/$TotalLastToLastYearMTDconfimed_revenue)*100):'0';


$TotalSumGolyYTDRoomNights         =   $TotalLastYearYTDRoomNights>0?round((($TotalYTDthisyearRoomNights-$TotalLastYearYTDRoomNights)/$TotalLastYearYTDRoomNights)*100):'0';
$TotalSumGolyYTDRevenue            =   $TotalLastYearYTDconfimed_revenue>0?round((($TotalThisYearYTDconfimed_revenue-$TotalLastYearYTDconfimed_revenue)/$TotalLastYearYTDconfimed_revenue)*100):'0';


$TotalOccpanMTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanMTDLastYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanMTDThisYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanGolyMTD      =    $TotalOccpanMTDLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanMTDLastYear)/$TotalOccpanMTDLastYear)*100,2):'0';


$TotalOccpanYTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanYTDLastYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanYTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalOccpanYTDThisYear)/$TotalMTDRoominventory)*100):'0';
$TotalOccpanGolyYTD      =    $TotalOccpanYTDLastYear>0?round((($TotalOccpanYTDThisYear-$TotalOccpanYTDLastYear)/$TotalOccpanYTDLastYear)*100,2):'0';


//Arr Sub Total---------------------------------------
		
$ArrTotalLastToLastYear=$TotalLastToLastYearMTDconfimed_revenueArr>0?round($TotalLastToLastYearMTDconfimed_revenueArr/$TotalLastToLastYearRoomNights):0;
$ArrTotalLastYear	 =$TotalLastYearMTDconfimed_revenueArr>0 ? round($TotalLastYearMTDconfimed_revenueArr/$TotalLastYearMTDRoomNights):0;
$ArrTotalThisYear	=$TotalThisYearMTDconfimed_revenueArr>0 ? round($TotalThisYearMTDconfimed_revenueArr/$TotalMTDthisyearRoomNights):0;


$ArrGolyTotalThisYear      =    $ArrTotalLastYear>0?round((($ArrTotalThisYear-$ArrTotalLastYear)/$ArrTotalLastYear)*100,2):'0';
$ArrGolyTotalLastYear      =    $ArrTotalLastToLastYear>0?round((($ArrTotalThisYear-$ArrTotalLastToLastYear)/$ArrTotalLastToLastYear)*100,2):'0';

//Arr Sub Total---------------------------------------       



array_push($GroupZoanRoomNightsCYArr,$TotalMTDthisyearRoomNights);
array_push($GroupZoanRoomNightsLYArr,$TotalLastYearMTDRoomNights);
array_push($GroupZoanRoomNightsLLYArr,$TotalLastToLastYearRoomNights);

array_push($GroupZoanRevenueCYArr,round($TotalThisYearMTDconfimed_revenueshow,2));
array_push($GroupZoanRevenueLYArr,round($TotalLastYearMTDconfimed_revenueshow,2));
array_push($GroupZoanRevenueLLYArr,round($TotalLastToLastYearMTDconfimed_revenue,2));

     $contentTeam .='<tr class="marginright">';
               $contentTeam .=' <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#c2d69a; "><b>'.strtoupper(' Sub Total').'</b></th>';
                
                 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalMTDRoominventory.'</b></th>';
				 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$subTotalRooms.'</b></th>';
                 
               $contentTeam .='
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalLastToLastYearRoomNights.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalMTDthisyearRoomNights.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.($TotalMTDthisyearRoomNights-$TotalLastYearMTDRoomNights).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalSumGolyLastToLastYearRoomNights.'</b></th>
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalSumGolyMTDRoomNights.'</b></th>';
                
                $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrTotalLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrTotalLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$ArrTotalThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrGolyTotalLastYear.'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrGolyTotalThisYear.'</b></th>';
                 $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalLastToLastYearMTDconfimed_revenue.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$TotalLastYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalThisYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastToLastYearMTDconfimed_revenue)/$TotalLastToLastYearMTDconfimed_revenue*100,2).'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalThisYearMTDconfimed_revenueshow-$TotalLastYearMTDconfimed_revenueshow)/$TotalLastYearMTDconfimed_revenueshow*100,2).'</b></th>';
                
				
				
				
				
				
				
                $TotalOccpanMTDLastYear  =    $TotalMTDRoominventory>0?round((($TotalLastYearMTDRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalMTDthisyearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpancy = $TotalOccpanMTDLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanMTDLastYear)/$TotalOccpanMTDLastYear)*100,2):'0';
				
                $TotalOccpanLastToLastYear  =    $TotalMTDRoominventory>0?round((($TotalLastToLastYearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpanMTDThisYear  =    $TotalMTDRoominventory>0?round((($TotalMTDthisyearRoomNights)/$TotalMTDRoominventory)*100,2):'0';
                $TotalOccpancyLastToLastTo = $TotalOccpanLastToLastYear>0?round((($TotalOccpanMTDThisYear-$TotalOccpanLastToLastYear)/$TotalOccpanLastToLastYear)*100,2):'0';
				
				
                
                $contentTeam .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpancyLastToLastTo.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpancy.'</b></th>';
                
                $contentTeam .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalMTDthisyearRoomNights/$MainTotalValueRoomNights)*100,2).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($TotalThisYearMTDconfimed_revenueArr/$MainTotalValueConfimedRevenue)*100,2).'</b></th>
                </th>';
                
				$contMaintTotalRN += round(($TotalMTDthisyearRoomNights/$MainTotalValueRoomNights)*100,2);
                $contMaintTotalCR += round(($TotalThisYearMTDconfimed_revenueArr/$MainTotalValueConfimedRevenue)*100,2);
				      $contentTeam .='</tr>';
                
    
    
    
     $MainTotalMTDRoominventory += $TotalMTDRoominventory;
     $MainTotalLastToLastYearRoomNights 	+= $TotalLastToLastYearRoomNights;
	 $MainTotalLastYearMTDRoomNights       +=$TotalLastYearMTDRoomNights;
	 $MainTotalMTDthisyearRoomNights	   +=$TotalMTDthisyearRoomNights;
     
	 
	 $MainTotalLastToLastYearMTDconfimed_revenue +=$TotalLastToLastYearMTDconfimed_revenue;
	 $MainTotalLastYearMTDconfimed_revenueshow +=$TotalLastYearMTDconfimed_revenueshow;
	 $MainTotalThisYearMTDconfimed_revenueshow+=$TotalThisYearMTDconfimed_revenueshow;
    // $MainTotalLastYearMTDRoomNights
	 //$TotalSumGolyMTDRoomNights
$MainTotalLastYearMTDconfimed_revenueArr               +=$TotalLastYearMTDconfimed_revenueArr;
$MainTotalThisYearMTDconfimed_revenueArr               +=$TotalThisYearMTDconfimed_revenueArr;
$MainTotalLastToLastYearMTDconfimed_revenueArr		 +=$TotalLastToLastYearMTDconfimed_revenueArr;
$MainTotalRooms	+=$subTotalRooms;
	 
}
}


//Arr Sub Total---------------------------------------
		
$ArrMainTotalLastToLastYear=$MainTotalLastToLastYearMTDconfimed_revenueArr>0?round($MainTotalLastToLastYearMTDconfimed_revenueArr/$MainTotalLastToLastYearRoomNights):0;
$ArrMainTotalLastYear	 =$MainTotalLastYearMTDconfimed_revenueArr>0 ? round($MainTotalLastYearMTDconfimed_revenueArr/$MainTotalLastYearMTDRoomNights):0;
$ArrMainTotalThisYear	=$MainTotalThisYearMTDconfimed_revenueArr>0 ? round($MainTotalThisYearMTDconfimed_revenueArr/$MainTotalMTDthisyearRoomNights):0;


$ArrMainGolyTotalThisYear      =    $ArrMainTotalLastYear>0?round((($ArrMainTotalThisYear-$ArrMainTotalLastYear)/$ArrMainTotalLastYear)*100,2):'0';
$ArrMainGolyTotalLastYear      =    $ArrMainTotalLastToLastYear>0?round((($ArrMainTotalThisYear-$ArrMainTotalLastToLastYear)/$ArrMainTotalLastToLastYear)*100,2):'0';

//Arr Sub Total--------------------------------------- 
$contentTeam .='<tr class="marginright">';
               $contentTeam .=' <th  style="vertical-align:central;width:200px;text-align:Left;color:#000;background-color:#c2d69a; "><b>'.strtoupper(' Total').'</b></th>';
                
                 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalMTDRoominventory.'</b></th>';
				 $contentTeam .='<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalRooms.'</b></th>';
                 
               $contentTeam .='
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalLastToLastYearRoomNights.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalLastYearMTDRoomNights.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalMTDthisyearRoomNights.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.($MainTotalMTDthisyearRoomNights-$MainTotalLastYearMTDRoomNights).'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalMTDthisyearRoomNights-$MainTotalLastToLastYearRoomNights)/$MainTotalLastToLastYearRoomNights*100,2).'</b></th>
			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalMTDthisyearRoomNights-$MainTotalLastYearMTDRoomNights)/$MainTotalLastYearMTDRoomNights*100,2).'</b></th>';
                
                $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainTotalLastToLastYear.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainTotalLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$ArrMainTotalThisYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainGolyTotalLastYear.'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$ArrMainGolyTotalThisYear.'</b></th>';
                 $contentTeam .='
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalLastToLastYearMTDconfimed_revenue.'</b></th>
				 <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>' .$MainTotalLastYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalThisYearMTDconfimed_revenueshow.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.round(($MainTotalThisYearMTDconfimed_revenueshow-$MainTotalLastToLastYearMTDconfimed_revenue)/$MainTotalLastToLastYearMTDconfimed_revenue*100,2).'</b></th>
 			    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.round(($MainTotalThisYearMTDconfimed_revenueshow-$MainTotalLastYearMTDconfimed_revenueshow)/$MainTotalLastYearMTDconfimed_revenueshow*100,2).'</b></th>';
                
                $MainTotalOccpanMTDLastYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalLastYearMTDRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $MainTotalOccpanMTDThisYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalMTDthisyearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $TotalOccpancy = $MainTotalOccpanMTDLastYear>0?round((($MainTotalOccpanMTDThisYear-$MainTotalOccpanMTDLastYear)/$MainTotalOccpanMTDLastYear)*100,2):'0';
				
                $MainTotalOccpanLastToLastYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalLastToLastYearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $MainTotalOccpanMTDThisYear  =    $MainTotalMTDRoominventory>0?round((($MainTotalMTDthisyearRoomNights)/$MainTotalMTDRoominventory)*100,2):'0';
                $TotalOccpancyLastToLastTo = $MainTotalOccpanLastToLastYear>0?round((($MainTotalOccpanMTDThisYear-$MainTotalOccpanLastToLastYear)/$MainTotalOccpanLastToLastYear)*100,2):'0';
				
				
                
                $contentTeam .='
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalOccpanLastToLastYear.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$MainTotalOccpanMTDLastYear.'</b></th>
                <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$MainTotalOccpanMTDThisYear.'</b></th>
                <th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$TotalOccpancyLastToLastTo.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$TotalOccpancy.'</b></th>';
                $contentTeam .='
				<th  class="marginright" style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b> '.$contMaintTotalRN.'</b></th>
				<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; "><b>'.$contMaintTotalCR.'</b></th>';
                
                
                      $contentTeam .='</tr>';


$contentTeam .= '</table><br/><br/>';$contentGroup .= '</table>';
$content .=$contentGroup;
         $content .=$contentTeam;
         
         
         //$contentGroup='';
}  //End ForEachLoop
$returnData2['HotelWiseSummaryZoanName']=$GroupZoanName;

$returnData2['HotelWiseSummaryZoanRoomNightsCY']=$GroupZoanRoomNightsCYArr;
$returnData2['HotelWiseSummaryZoanRoomNightsLY']=$GroupZoanRoomNightsLYArr;
$returnData2['HotelWiseSummaryZoanRoomNightsLLY']=$GroupZoanRoomNightsLLYArr;

$returnData2['HotelWiseSummaryZoanRevenueCY']=$GroupZoanRevenueCYArr;
$returnData2['HotelWiseSummaryZoanRevenueLY']=$GroupZoanRevenueLYArr;
$returnData2['HotelWiseSummaryZoanRevenueLLY']=$GroupZoanRevenueLLYArr;


	return $returnData2;
	}//$reportViewGrapTable Table View
}







?>