<?php

include_once("../../../config/auto_loader.php");
//error_reporting(E_ALL);


//debugData($_REQUEST);
$from = date('Y-m-d',strtotime($_REQUEST['period']));
//print_r($_SESSION);
$PeriodDateArray	=	explode('to',$_REQUEST['period']);

$from = date('Y-m-d',strtotime($PeriodDateArray[0]));
///$to = date('Y-m-d',strtotime($PeriodDateArray[1]));
//$to = date('Y-m-d',strtotime($PeriodDateArray[1]. ' +1 day'));
if(date('Y-m-d',strtotime($PeriodDateArray[1]))<date('Y-m-d')){
$to_TodaysDate=date('Y-m-d',strtotime($PeriodDateArray[1]));
}else{
    $to_TodaysDate=date('Y-m-d');
}


$PeriodDateArray[1]=date('Y-m-d');
$from = date('Y-m-d',strtotime($PeriodDateArray[1]));



$CurrentPeriodDateArray	=	explode('to',$_REQUEST['period']);
$from_book=date('Y-m-d',strtotime($CurrentPeriodDateArray[0]));;
$to_book=date('Y-m-d',strtotime($CurrentPeriodDateArray[1]));



  $Diffrence='';
  $CompareFinancialYear	=	explode('-',$_REQUEST['CompareFinancialYear']);
  $CurrentFinancialYear	=	explode('-',$_REQUEST['CurrentFinancialYear']);
 
   $Diffrence =($CompareFinancialYear[0] - $CurrentFinancialYear[0]);
   
$FromLastYearDate   =   date($CompareFinancialYear[0].'-04-01');
$ToLastYearDate   =     date('Y-m-d',strtotime('-1 years',strtotime(date('Y-m-d'))));

$FromCurrentYearDate   =   date($CurrentFinancialYear[0].'-04-01');
$ToCurrentYearDate   =   date('Y-m-d');

$LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
$StartdateCurrentmonth   = date('Y-m-d',strtotime(date('01-m-Y')));
$EnddateCurrentmonth   =date('Y-m-d',strtotime($LastDateCurrentmonth));




$mtdLastValues = array();
$mtdThisMonthValues= array();
$yearToDayLastValues= array();
$budgetRoomNightsThisMonthValues= array();
$mtdThisValues = array();

$mtdVisits = array();
$mtdRateLetters = array();


$budgetValues = array();


$ytdLastValues = array();
$ytdThisValues = array();

$ytdVisits = array();
$ytdRateLetters = array();
$ytdTotalExpense = array();

$exeNameArr = array();
$returnData = array();

$stackedArr = array();
$stackedDataSet = array();
$budgetRoomNightsValues=array();
$achievedRoomNightsThisMonthValue=array();

$yearToDayHotelPrevYearValues=array();
$budgetHotelRoomNightsValues=array();
$achievedHotelValues=array();

$mtdHotelPrevYearValues=array();
$budgetHotelRoomNightsThisMonthValues=array();
$mtdHotelThisMonthValues=array();




$budgetHotelValueCurrentYEARValue=array();
$budgetHotelValueThisMonthValue=array();

$budgetValueCurrentYEARValues=array();
$budgetValueThisMonthValues=array();
$achievedValueYEARMonthValues=array();
$achievedValueThisMonthValues=array();
$achievedValuePrveYEARValues=array();
$achievedValueCurrentYearValues=array();
$hotelNameValue=array();
$days=0;
$weekends=0;

$totalDaysGoneMtd=0;
$totalDaysGoneYtd=0;
$cond='';
$_REQUEST['period']=date('Y-m-d',strtotime($PeriodDateArray[1]));
 $_REQUEST['period']=date('Y-m-d',strtotime($PeriodDateArray[1]));
  $period = $_REQUEST['period'];
$from = '';
$to='';
if(date('m',strtotime($period))<=3){
	$from = date('Y-04-01',strtotime('-1 years',strtotime($period)));
	$to = date('Y-m-d',strtotime($period));
}
else{
	$from = date('Y-04-01',strtotime($period));
	$to = date('Y-m-d',strtotime($period));
}

  $MonthFrom=date('Y-m-01',strtotime($period));
  //$MonthFrom=date('Y-m-01',strtotime($PeriodDateArray[0]));

$UserInActive	=	"  AND ( ".TBL_USERS.".status_inactive_date>='".$to."' ||  ".TBL_USERS.".status_inactive_date='0000-00-00') ";


if(!isset($_SESSION['teamMemberLevel']) && $_SESSION['userLevel']!=1){
	//$cond = ' AND id="'.$_SESSION['userId'].'" ';
	$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');
	if($team_data_access_approved=='1'){
		$cond = '';
		}else{
			$cond = ' AND "'.TBL_USERS.'".id="'.$_SESSION['userId'].'" ';
			}
}

//echo $_SESSION['teamNewMembers'];
 if($_REQUEST['id_team']==0){
	$id_teams=$_SESSION['teamId'];
	}else{
		$id_teams=$_REQUEST['id_team'];
		}
		
		//FIND_IN_SET('".$id_teams."',ids_team)
       // $sqlExe = "SELECT id,name,user_type FROM ".TBL_USERS." WHERE ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") ".$cond." order by name";


$team_data_access_approved	= selectColumn(TBL_USER_LEVELS,'teamdataaccess_approved','WHERE id="'.$_SESSION['userLevel'].'" ');

	if($team_data_access_approved=='1' || $_SESSION['userLevel']==1){ //Yes
	
	if($_REQUEST['id_team']==0){ 
		//echo 'All';
		
		
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
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE id_shop=".$_SESSION['shop']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."')  ".$UserInActive."  ";
		$resTeam =  mysqli_query($connNew,$teamSql);

		$teamArray=array();

		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
		$teamMembers=implode(',',$teamArray);
		
		$allUser= " AND ".TBL_USERS.".ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND fs_users.id IN (".$teamMembers.") ";
		//$userIdTeam	=	selectColumn(TBL_USERS,'ids_team','WHERE id='.$_SESSION['userId'].'  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND id IN (".$_SESSION['teamMembers'].") AND id_shop='.$_SESSION['shop'].' ');
		
	}else{
		//echo 'Team';
		  $userIdTeam	=	selectColumn(TBL_USERS,"ids_team","WHERE id=".$_SESSION['userId']." AND ids_team REGEXP CONCAT('(^|,)(', REPLACE(".$_REQUEST['id_team'].", ',', '|'), ')(,|$)')  AND id_shop=".$_SESSION['shop']."  ".$UserInActive." ");
	//$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id_shop= ".$_SESSION['shop']."";
		
		$teamSql = "SELECT id FROM ".TBL_USERS." WHERE  myownteam_id='".$_REQUEST['id_team']."'   AND id_shop= '".$_SESSION['shop']."'  ".$UserInActive."";
		$resTeam =  mysqli_query($connNew,$teamSql);
	
		$teamArray=array();
	
		while($rowTeam=mysqli_fetch_object($resTeam)){
			array_push($teamArray,$rowTeam->id);
		}
	
		$teamMembers=implode(',',$teamArray);
		
		//$id_teams = selectColumn(TBL_USERS,'ids_team','WHERE id="'.$_SESSION['userId'].'" ');
		
		//$allUser =" ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$userIdTeam."', ',', '|'), ')(,|$)') AND id IN (".$teamMembers.") ";
		$allUser =" AND ".TBL_USERS.".id IN (".$teamMembers.") ";
		}	
		
		
	}else{ //NO Access
	if($team_data_access_approved=='1' ){
		$cond = '';}
		else{
		    $cond = ' AND "'.TBL_USERS.'".id="'.$_SESSION['userId'].'" ';
		}
		
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
					}//print_r($GroupArrayList);
				$GroupArrayList=	implode(',',$GroupArrayList);
		$cond .= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
		$condBOB.= " AND `".TBL_GROUP_MASTER."`.`id`  in (".$GroupArrayList.")";
	}
	
$reportArray=array();	

if($_REQUEST['id_group_sub_master']>0){
    
    $condTeamGroup.= " AND `".TBL_TEAM."`.`id` =".$_REQUEST['id_group_sub_master']." ";
}else{
    
    $condTeamGroup='';
}


//========================================================================================================	
$reportArray=array();
//SELECT id,name,user_type FROM fs_users WHERE  ids_team REGEXP CONCAT('(^|,)(', REPLACE('', ',', '|'), ')(,|$)') AND id IN ()  order by name
//echo $cond;
            $sqlExe = "SELECT ".TBL_USERS.".id,".TBL_USERS.".name,".TBL_USERS.".user_type,".TBL_USERS.".city
            ,`mst_team`.id_group,
            `fs_users`.myownteam_id as MyOwnteam
             FROM ".TBL_USERS."
             LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
             LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
            
            WHERE ".TBL_USERS.".id!='' AND  ".TBL_USERS.".status='1' AND  ".TBL_USERS.".`sales_status_active`='1' AND  ".TBL_USERS.".`user_level`!='1' AND `".TBL_GROUP_MASTER."`.status='1'  AND  `".TBL_GROUP_MASTER."`.name='sales' ".$cond." ".$allUser." order by ".TBL_USERS.".city,".TBL_USERS.".name";
		// echo $sqlExe;
		
$resExe = mysqli_query($connNew,$sqlExe);
$userIdArray=array();
while($rowExe = mysqli_fetch_object($resExe)){
	
	
	    $sqlUserCountExe = "SELECT count(".TBL_USERS.".city) usercount  ,".TBL_USERS.".id,".TBL_USERS.".name,".TBL_USERS.".user_type,".TBL_USERS.".city
            ,`mst_team`.id_group,
            `fs_users`.myownteam_id as MyOwnteam
             FROM ".TBL_USERS."
             LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
             LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
            
            WHERE ".TBL_USERS.".id!='' AND  ".TBL_USERS.".status='1' AND  ".TBL_USERS.".`sales_status_active`='1' AND  ".TBL_USERS.".`user_level`!='1' AND `".TBL_GROUP_MASTER."`.status='1'  AND  `".TBL_GROUP_MASTER."`.name='sales' AND `city` = '".$rowExe->city."' ".$cond." ".$allUser." 
			group by ".TBL_USERS.".city
			order by ".TBL_USERS.".city,".TBL_USERS.".name";
			
			$resUserCount = mysqli_query($connNew,$sqlUserCountExe);
			$rowUserCount = mysqli_fetch_object($resUserCount);
    
        $companyname= selectColumn(TBL_TEAM,'name'," WHERE `id` = '".$rowExe->MyOwnteam."'");
	    $exeNameArr[]=ucwords(strtolower($companyname));
	    
	    $GroupName= selectColumn(TBL_GROUP_MASTER,'name'," WHERE `id` = '".$rowExe->id_group."'");
	    $BusinessSourceName=  selectColumn(TBL_GROUP,'name'," WHERE `id_group` = '".$rowExe->id_default_group."'");;
	    
	    
	    $emptytext7 ='empty_'.$empty7++;
        
        
     
    
		
	//Budget value month_value 
	
	//if($assignedCompany >0){


		if($rowExe->user_type!=2){
			$rateTable = TBL_RATE;
			$budgetTable = TBL_AGENT_BUDGET;
			$achievedTable = TBL_AGENT_ACHIEVED;
		}
		else{
			$rateTable = TBL_RATE_UNIT;
			$budgetTable = TBL_UNIT_AGENT_BUDGET;
			$achievedTable = TBL_UNIT_AGENT_ACHIEVED;
		}
		
			$budgetTable = TBL_BUDGET_MASTER;
			$achievedTable = TBL_BUDGET_MASTER;
			
			
			
		array_push($userIdArray, $rowExe->id);	
		
		$prevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month='".date('Y-m-01',strtotime('-1 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

//+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ;

		$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');
        $visitToday = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-d',strtotime($to_TodaysDate)).'" AND "'.date('Y-m-d',strtotime($to_TodaysDate)).'" ');
        
        
        $TeleCallToday  =selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND `id_other_activity` = "29" AND dated between "'.date('Y-m-d',strtotime($to_TodaysDate)).'" AND "'.date('Y-m-d',strtotime($to_TodaysDate)).'" ');		
        
	//	echo '<br><br><br>'.TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-d',strtotime($to_TodaysDate)).'" AND "'.date('Y-m-d',strtotime($to_TodaysDate)).'" ';
		//echo '<br>'.TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-d',strtotime($to_TodaysDate)).'" AND "'.date('Y-m-d',strtotime($to_TodaysDate)).'" ';
		//$visitMtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($from)).'" ');


		$totalExpenseMtd = selectColumn(TBL_DAILYVISIT,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'(sum(total)+sum(entertainment)+sum(lunch))',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');
		

		

		$rateLetterMtd = selectColumn($rateTable,'count(id)',' WHERE created_by="'.$rowExe->id.'" AND date_created between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');

	
	//echo TBL_DAILYVISIT.'count(id)'.' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-m-01',strtotime($MonthFrom)).'" AND "'.date('Y-m-d',strtotime($to)).'" ';
	
	
	  $day	=	date('d',strtotime($_REQUEST['period']));
	 $month	=	date('m',strtotime($_REQUEST['period']));
	 $Year	=	date('Y',strtotime($_REQUEST['period']));
	
	
if (date($month) > 6) {
    $yearStart = date($Year);
	$yearEnd	=(date($Year) +1);
}
else {
    $yearStart = (date($Year)-1);
	$yearEnd=date($Year);
}

	 $reportPeriodMonth= date('F',strtotime($_REQUEST['period'])).' '.$Year;
	$LYMONTH	=	date('Y-'.$month.'-01',strtotime('-1 years',strtotime($from)));
	$CYMONTH	=	date('Y-m-01',strtotime($from));
	$LYPEROD	= 	date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));
	$CYPERIOD   =	date('01-04-Y',strtotime('01-04-'.$yearStart)).' to '.date('d-m-Y',strtotime($yearEnd));
	
	
	 $date = date("".$yearStart."-04-01");
     $end =  date("".$Year."-".$month."-".$day);	
	
	
		$prevYear = strtotime($date);
		$prevYear = strtotime("-1 year",$prevYear);
		$prevYear = date('Y-m-d',$prevYear);
		$prevYearEnd=strtotime("-1 year",strtotime($end));
		$prevYearEnd = date('Y-m-d',$prevYearEnd);
		
		$prevYear2 = strtotime($date);
		$prevYear2 = strtotime("-2 year",$prevYear2);
		$prevYear2 = date('Y-m-d',$prevYear2);
		$prevYear2End = strtotime("-2 year",strtotime($end));
		$prevYear2End = date('Y-m-d',$prevYear2End);
	
   
	
	
	
	if(date('m',strtotime($from))<=3){
			//echo $budgetTable,'sum(qty)'," WHERE `id_user` = '".$rowExe->id."' AND month between '".date('Y-04-01',strtotime('-1 years',strtotime($from)))."' and '".date('Y-03-31',strtotime($from))."'   ";
			$reportDisplayPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($to));
			
			$reportPeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime('-1 years',strtotime($from))).' to '.date('d-m-Y',strtotime($from));

			$ytdPrevYear = selectColumn($achievedTable,'sum(month_value)'," WHERE month between '".date('Y-04-01',strtotime('-2 years',strtotime($from)))."' and '".date('Y-m-01',strtotime('-2 years',strtotime($from)))."'  and id_shop='".$_SESSION['shop']."' and id_user='".$rowExe->id."'  ");

			$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime('-1 years',strtotime($from))).'" AND "'.date('Y-m-d',strtotime($from)).'" ');


		}
		else{
            $reportDisplayPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($to));
			$reportPeriod = date('01-04-Y',strtotime($from)).' To '.date('d-m-Y',strtotime($from));

			$datePeriod = date('01-04-Y',strtotime($from)).' to '.date('d-m-Y',strtotime($from));

$visitYtd = selectColumn(TBL_DAILYVISIT,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ')+selectColumn(TBL_OTHER,'count(id)',' WHERE id_user="'.$rowExe->id.'" AND dated between "'.date('Y-04-01',strtotime($from)).'" AND "'.date('Y-m-d',strtotime($to)).'" ');


		}

		
		 $stackedDataSet['label']=$rowExe->name;
		
		$stackedDataSet['backgroundColor']='rgba('.rand(0,255).', '.rand(0,55).', '.rand(0,150).',0.7)';
		//$stackedDataSet['borderColor']='rgba('.rand(0,255).', '.rand(0,255).', '.rand(0,255).',1)';
		$stackedDataSet['data'][0]=($budget==''?0:$budget);

		array_push($stackedArr,$stackedDataSet);


		array_push($exeNameArr,ucwords(strtolower($rowExe->name).'-'.$rowExe->city));
		array_push($mtdLastValues, ($mtdPrevYear==''?0:$mtdPrevYear));
		
		
		array_push($mtdThisMonthValues, ($mtdThisMonth==''?0:$mtdThisMonth));
				
		
		array_push($yearToDayLastValues, ($yearToDayPrevYear==''?0:$yearToDayPrevYear));
		
		array_push($mtdThisValues, ($achieved==''?0:$achieved));

		array_push($budgetValues, ($budget==''?0:$budget));
		

		array_push($ytdLastValues, ($ytdPrevYear==''?0:$ytdPrevYear));
		array_push($ytdThisValues, ($ytdAchieved==''?0:$ytdAchieved));

		array_push($mtdVisits,$visitMtd);
		array_push($mtdRateLetters,$rateLetterMtd);

		array_push($ytdVisits,$visitYtd);
		array_push($ytdRateLetters,$rateLetterYtd);

		
		array_push($ytdTotalExpense, $totalExpenseYtd);
		
		array_push($budgetRoomNightsValues, ($budgetRoomNights==''?0:$budgetRoomNights));
		array_push($achievedRoomNightsThisMonthValue, ($achievedRoomNightsThisMonth==''?0:$achievedRoomNightsThisMonth));
		array_push($budgetRoomNightsThisMonthValues, ($budgetRoomNightsThisMonth==''?0:$budgetRoomNightsThisMonth));
		
	
		
		array_push($budgetValueCurrentYEARValues, ($budgetValueCurrentYEAR==''?0:$budgetValueCurrentYEAR));
		array_push($budgetValueThisMonthValues, ($budgetValueThisMonth==''?0:$budgetValueThisMonth));
		
		array_push($achievedValueYEARMonthValues, ($achievedValueYEARMonth==''?0:$achievedValueYEARMonth));
		array_push($achievedValueThisMonthValues, ($achievedValueThisMonth==''?0:$achievedValueThisMonth));
		array_push($achievedValuePrveYEARValues, ($achievedValuePrveYEAR==''?0:$achievedValuePrveYEAR));
		array_push($achievedValueCurrentYearValues, ($achievedValueCurrentYear==''?0:$achievedValueCurrentYear));
		
		/*
		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '1'  AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN `fs_orders`.booking_status = '1' AND DATE(`".TBL_ORDERS."`.booking_confirm_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
			*/
//PIckup Base SQLStart=========================================================================================================
$sqlPIckupBase = " SELECT `".TBL_ORDERS."`.id_hotel,`".TBL_ORDERS."`.id_company,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_COMPANY."`.id_default_group,`".TBL_USERS."`.id AS id_executive,`".TBL_AREAS."`.id AS id_area,`".TBL_ORDERS."`.booking_status,`".TBL_USERS."`.ids_team,
    
    		SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".date('Y-m-d',strtotime($to_TodaysDate))."' and '".date('Y-m-d',strtotime($to_TodaysDate))."'))  THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newConfirmed,
    		SUM(CASE WHEN `".TBL_ORDERS."`.booking_status = '2'  AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`".TBL_ORDERS."`.total_products*`".TBL_ORDERS."`.no_of_days,0) ELSE 0 END ) AS newTentative,   
    		
    		SUM(CASE WHEN (`".TBL_ORDERS."`.booking_status = '1' || `".TBL_ORDERS."`.booking_status = '2') and ( ( `".TBL_ORDERS."`.booking_confirm_date between '".date('Y-m-d',strtotime($to_TodaysDate))."' and '".date('Y-m-d',strtotime($to_TodaysDate))."')) THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS confimed_revenue, 
			
			
            SUM(CASE WHEN `fs_orders`.booking_status = '2'AND DATE(`".TBL_ORDERS."`.invoice_date) BETWEEN '".date('Y-m-d',strtotime($to_TodaysDate))."' AND '".date('Y-m-d',strtotime($to_TodaysDate))."' THEN ROUND(`fs_orders`.subtotal,0) ELSE 0 END ) AS tentative_revenue ,
			
    					
		`".TBL_USERS."`.name as name_executive FROM `".TBL_ORDERS."`  
		LEFT JOIN `".TBL_COMPANY."` ON `".TBL_ORDERS."`.id_company = `".TBL_COMPANY."`.id_company
		LEFT JOIN `".TBL_AREAS."` ON `".TBL_COMPANY."`.area = `".TBL_AREAS."`.id
		
		
		LEFT JOIN `".TBL_USERS."` ON `fs_areas_assign`.user_id=".TBL_USERS.".id  
		LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
		
		LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
		where `".TBL_ORDERS."`.`id_shop` = '".addslashes($_SESSION['shop'])."'  
       
       ".$cond1." AND `".TBL_USERS."`.id='".$rowExe->id."'  ".$condTeamGroup."  GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id Order BY `".TBL_GROUP_MASTER."`.display_order,`mst_team`.id_group,`fs_users`.myownteam_id";
     // echo '<br><br><br>'.$sqlPIckupBase;
     // die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
      
       $resultPIckupBase = mysqli_query($connNew,$sqlPIckupBase);
       $empty7=0;
	$rowPIckupBase = mysqli_fetch_object($resultPIckupBase);
	    
//PIckup Base SQLEnd=========================================================================================================	    
//Bob Based Start=========================================================================================
$sqlBobBased = "SELECT `fs_orders`.*,`fs_users`.name as name_executive,`fs_company_group`.name as name_company_group, `fs_order_detail`.room_id ,`mst_team`.id_group,`fs_users`.myownteam_id as MyOwnteam,`".TBL_USERS."`.ids_team,`".TBL_COMPANY."`.id_default_group
      

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `confimed_revenue`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newConfirmed`

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($from_book))."' And '".date('Y-m-d',strtotime($to_book))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentative`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$FromLastYearDate."' And '".$ToLastYearDate."')) then `fs_order_detail`.tarrif_price else 0 end) as `lastYearconfimed_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$FromCurrentYearDate."' And '".$ToCurrentYearDate."')) then `fs_order_detail`.tarrif_price else 0 end) as `CurrentYearconfimed_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$StartdateCurrentmonth."' And '".$EnddateCurrentmonth."')) then `fs_order_detail`.tarrif_price else 0 end) as `MTDconfimed_revenue`

,sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$StartdateCurrentmonth."' And '".$EnddateCurrentmonth."')) then `fs_order_detail`.room_quantity else 0 end) as `MTDnewConfirmed`
  

,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($StartdateCurrentmonth))."' And '".date('Y-m-d',strtotime($EnddateCurrentmonth))."')) then `fs_order_detail`.room_quantity else 0 end) as `newTentativeMTD`
,sum(case when ( `fs_orders`.`booking_status` = '2') AND ( ( `fs_order_detail` .dated BETWEEN '".date('Y-m-d',strtotime($StartdateCurrentmonth))."' And '".date('Y-m-d',strtotime($EnddateCurrentmonth))."')) then `fs_order_detail`.tarrif_price else 0 end) as `tentative_revenueMTD`

FROM `fs_orders` 
LEFT JOIN `fs_company`  ON fs_orders.id_company = fs_company.id_company
LEFT JOIN `fs_areas_assign`  ON fs_company.area = fs_areas_assign.id
LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id   
LEFT JOIN `fs_company_group`  ON fs_company.id_default_group = fs_company_group.id_group
LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id  and `mst_team`.id_shop='".$_SESSION['shop']."'
LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
INNER join `fs_order_detail` on fs_orders.id_order=fs_order_detail.id_order 


where `fs_orders`.`id_shop` = '".addslashes($_SESSION['shop'])."'  AND `".TBL_USERS."`.id='".$rowExe->id."'  ".$allUser." ".$condBOB.$condTeamGroup."  
GROUP BY `".TBL_USERS."`.myownteam_id,`fs_users`.id

Order BY `mst_team`.id_group,`fs_users`.myownteam_id";
       
       //echo '<br><br>'.$sqlBobBased;
   //die;
     //  LEFT JOIN  `".TBL_TEAM."` ON `".TBL_TEAM."`.ids_team REGEXP CONCAT('(^|,)(', REPLACE('".$id_teams."', ',', '|'), ')(,|$)') AND  FIND_IN_SET(myownteam_id,'".$id_teams."') 
      
       $resultBobBased = mysqli_query($connNew,$sqlBobBased);
       $empty7=0;
	$rowBobBased = mysqli_fetch_object($resultBobBased);
//Bob Based End=========================================================================================
if($rowBobBased->MTDconfimed_revenue>0){
	$mtr_bob_Arr =round($rowBobBased->MTDconfimed_revenue/$rowBobBased->MTDnewConfirmed);
}else{
	$mtr_bob_Arr =0;
	}
    $ytd_bob_revenue_growth = $rowBobBased->lastYearconfimed_revenue>0?round((($rowBobBased->CurrentYearconfimed_revenue-$rowBobBased->lastYearconfimed_revenue)/$rowBobBased->lastYearconfimed_revenue)*100,2):'0';
    
        //Total Company Count===============
		$sqlCountCompany	="SELECT count(*) as totalCompany FROM `fs_company` LEFT JOIN `fs_areas_assign` ON fs_company.area = fs_areas_assign.id LEFT JOIN `fs_users` ON fs_areas_assign.user_id = fs_users.id where fs_users.id='".$rowExe->id."' and fs_users.status='1'";
		$resultCountCompany = mysqli_query($connNew,$sqlCountCompany);       
		$rowCountCompany = mysqli_fetch_object($resultCountCompany);
        //Total Company Count===============
		
		
		//Forecast=====================================================================
		
	$dateBudget = date('Y-m-d',strtotime($StartdateCurrentmonth));
	$ddateBudget = date_parse_from_format("Y-m-d", $dateBudget);
	$startMoBudget = $ddateBudget["month"];
	$monthNUmersBudget =  DateTime::createFromFormat('!m', $startMoBudget);
	$monthNUmersBudget = $monthNUmersBudget->format('m');
	$startYrBudget = date("Y");
		//sum(case when (`fs_orders`.`booking_status` = '1' ) AND ( ( `fs_order_detail` .dated BETWEEN '".$StartdateCurrentmonth."' And '".$EnddateCurrentmonth."')) then `fs_order_detail`.room_quantity else 0 end) as `MTDnewConfirmed`
		$ConbudgetSql ="AND MONTH(`".TBL_BUDGET_MASTER."`.month) = '".$monthNUmersBudget."' AND YEAR(`".TBL_BUDGET_MASTER."`.month) = '".$startYrBudget."' ";
		$sqlBudgetForecast = "SELECT  
	    
      
        sum(`".TBL_BUDGET_MASTER."`.forecast_qty) As forecast_qty,
        sum(`".TBL_BUDGET_MASTER."`.forecast_month_value) As forecast_month_value,
        `".TBL_BUDGET_MASTER."`.month,`".TBL_BUDGET_MASTER."`.id_company,
        `".TBL_BUDGET_MASTER."`.id_user,`mst_team`.id_group,
        `fs_users`.myownteam_id as MyOwnteam
	    FROM `".TBL_BUDGET_MASTER."`   
		LEFT JOIN `fs_users` ON `".TBL_BUDGET_MASTER."`.id_user = fs_users.id
		LEFT JOIN `mst_team` ON `mst_team`.id=`fs_users`.myownteam_id 
		LEFT JOIN `".TBL_GROUP_MASTER."` ON `".TBL_GROUP_MASTER."`.id = `mst_team`.id_group
		    	    
	    where  `".TBL_BUDGET_MASTER."`.`id_shop` = '".addslashes($_SESSION['shop'])."' ".$ConbudgetSql." AND   `".TBL_BUDGET_MASTER."`.`id_user` IN (".$rowExe->id.") 
	    AND `".TBL_BUDGET_MASTER."`.type=1     GROUP BY `".TBL_USERS."`.myownteam_id,`mst_team`.id_group  ORDER BY `".TBL_BUDGET_MASTER."`.month";;	
		
  // echo '<br> <br> <br> '.$sqlBudgetForecast;
   $resultBudgetForecast = mysqli_query($connNew,$sqlBudgetForecast);
   
   $rowBudgetForecast = mysqli_fetch_object($resultBudgetForecast);
		
	//Forecast END=====================================================================	
		
		
		$CityName=	strtoupper($rowExe->city);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['MTDforecast_revenue']=round($rowBudgetForecast->forecast_month_value,2);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['totalCompany']=$rowCountCompany->totalCompany;
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['confirmed_pickup_roomNights']=$rowPIckupBase->newConfirmed==''?0:round($rowPIckupBase->newConfirmed);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['confirmed_pickup_revenue']=$rowPIckupBase->confimed_revenue==''?0:round($rowPIckupBase->confimed_revenue/100000,2);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_pickup_roomNights']=$rowPIckupBase->newTentative==''?0:round($rowPIckupBase->newTentative);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_pickup_revenue']=$rowPIckupBase->tentative_revenue==''?0:round($rowPIckupBase->tentative_revenue/100000,2);
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_pickup_roomNightsMTD']=$rowBobBased->newTentativeMTD==''?0:round($rowBobBased->newTentativeMTD);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_pickup_revenueMTD']=$rowBobBased->tentative_revenueMTD==''?0:round($rowBobBased->tentative_revenueMTD/100000,2);
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['confirmed_bob_roomNights']=$rowBobBased->newConfirmed==''?0:round($rowBobBased->newConfirmed);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['confirmed_bob_revenue']=$rowBobBased->confimed_revenue==''?0:round($rowBobBased->confimed_revenue/100000,2);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_bob_roomNights']=$rowBobBased->newTentative==''?0:round($rowBobBased->newTentative);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['tentative_bob_revenue']=$rowBobBased->tentative_revenue==''?0:round($rowBobBased->tentative_revenue/100000,2);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['lastYearconfimed_bob_revenue']=$rowBobBased->lastYearconfimed_revenue==''?0:round($rowBobBased->lastYearconfimed_revenue/100000,2);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['CurrentYearconfimed_bob_revenue']=$rowBobBased->CurrentYearconfimed_revenue==''?0:round($rowBobBased->CurrentYearconfimed_revenue/100000,2);
		
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['mtd_bob_roomNights']=$rowBobBased->MTDnewConfirmed==''?0:round($rowBobBased->MTDnewConfirmed);
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['mtd_bob_revenue']=$rowBobBased->MTDconfimed_revenue==''?0:round($rowBobBased->MTDconfimed_revenue/100000,2);
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['mtd_bob_Arr']=$mtr_bob_Arr;
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['ytd_bob_revenue_growth']=$ytd_bob_revenue_growth;
		
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['Visits']=$visitMtd;
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['VisitsYtd']=$visitYtd;
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['VisitsToday']=$visitToday;
		$reportArray['Sales Activity'][$GroupName][$CityName][$rowExe->name]['TeleCallToday']=$TeleCallToday;
		
		
		
		
		//Sub Total City Wise============================================================================
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_MTDforecast_revenue'] +=round($rowBudgetForecast->forecast_month_value,2);
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_totalCompany'] +=$rowCountCompany->totalCompany;		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_confirmed_pickup_roomNights'] +=$rowPIckupBase->newConfirmed==''?0:round($rowPIckupBase->newConfirmed);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_confirmed_pickup_revenue']    +=$rowPIckupBase->confimed_revenue==''?0:round($rowPIckupBase->confimed_revenue/100000,2);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_pickup_roomNights'] +=$rowPIckupBase->newTentative==''?0:round($rowPIckupBase->newTentative);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_pickup_revenue']    +=$rowPIckupBase->tentative_revenue==''?0:round($rowPIckupBase->tentative_revenue/100000,2);
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_pickup_roomNightsMTD'] +=$rowBobBased->newTentativeMTD==''?0:round($rowBobBased->newTentativeMTD);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_pickup_revenueMTD']    +=$rowBobBased->tentative_revenueMTD==''?0:round($rowBobBased->tentative_revenueMTD/100000,2);
		
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_confirmed_bob_roomNights']    +=$rowBobBased->newConfirmed==''?0:round($rowBobBased->newConfirmed);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_confirmed_bob_revenue']       +=$rowBobBased->confimed_revenue==''?0:round($rowBobBased->confimed_revenue/100000,2);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_bob_roomNights']    +=$rowBobBased->newTentative==''?0:round($rowBobBased->newTentative);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_tentative_bob_revenue']       +=$rowBobBased->tentative_revenue==''?0:round($rowBobBased->tentative_revenue/100000,2);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_lastYearconfimed_bob_revenue']+=$rowBobBased->lastYearconfimed_revenue==''?0:round($rowBobBased->lastYearconfimed_revenue/100000,2);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_CurrentYearconfimed_bob_revenue']+=$rowBobBased->CurrentYearconfimed_revenue==''?0:round($rowBobBased->CurrentYearconfimed_revenue/100000,2);
		
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_mtd_bob_roomNights']+=$rowBobBased->MTDnewConfirmed==''?0:round($rowBobBased->MTDnewConfirmed);
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_mtd_bob_revenue'] +=$rowBobBased->MTDconfimed_revenue==''?0:round($rowBobBased->MTDconfimed_revenue/100000,2);
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_mtd_bob_Arr'] +=$mtr_bob_Arr;
		
		
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_ytd_bob_revenue_growth'] +=$ytd_bob_revenue_growth;
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_Visits'] +=$visitMtd;
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_VisitsYtd']+=$visitYtd;
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_VisitsToday']+=$visitToday;
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_TeleCallToday']+=$TeleCallToday;
		$reportTotalArray['Sales Activity'][$GroupName][$CityName]['Total_usercount']=$rowUserCount->usercount;
		
		
		
		
		
		
	//}	 
} //While User End
 
//debugData($reportArray);
//debugData($reportTotalArray);




$userIdArray=implode(',',$userIdArray);

/***** Total Gone Days Calculatiing Days ****/
$days=1;
$weekends=1;

$totalDaysGoneMtd=1;
$totalDaysGoneYtd=1;

//YTD
if(date('m',strtotime($period))<=3){
	$startDate = date('Y-04-01',strtotime('-1 years',strtotime($period)));
	$lastDate = date('Y-m-d',strtotime($period));
}
else{
	$startDate =date('Y-04-01',strtotime($period));
	$lastDate = date('Y-m-d',strtotime($period));
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


$startDate=date('Y-m-01',strtotime($period));


$days=1;
$weekends=1;
// MTD
while($startDate <= $to){

	$day = date("N",strtotime($startDate));
	if($day == 6 || $day == 7) {
	  $weekends++;
	} 

	$days++;
	$startDate = date('Y-m-d',strtotime('+1 days',strtotime($startDate)));
}
$totalDaysGoneMtd = $days-$weekends;

/**************** END ***********************/ 
//print_r($exeNameArr);
//print_r($hotelNameValue);
 $SummaryHedding='Sales Activity Summary';


 $content ='';		
 $content = '<style>


.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {
    border: 1px solid #000 !important;
}
<style>
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
';
 $content .= '</style>';
 
 
$returnData['reportPeriod']=$reportDisplayPeriod;
$returnData['reportPeriodMonth']=$reportPeriodMonth;
$returnData['datePeriod']=$datePeriod;

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
    $resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
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
  
	   $content .=    '<br><table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="5" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$ReportTypeMainTitle.'REPORT AS ON  '.date('d-m-Y').'</b></th></tr>
		</table><br>'; 
}

//$content .= '<h4 class="text-center" style="text-align:center;background-color: #4f6228;margin: 5px;padding: 10px;color: #fff;"><strong>'.$SummaryHedding.' As On '.date('d-m-Y', strtotime($to_TodaysDate)).'</strong></h4>';

$content .='<table class="table table-striped text-center">';
        $content .='<tr><th colspan="23" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>'.$SummaryHedding.' As On '.date('d-m-Y', strtotime($to_TodaysDate)).'</b></th></tr>';
    
        $content .='<tr style="color:white;">
        <th rowspan="2" style="background-color:#4f6228;color:#fff;vertical-align: middle;">S.No</th>
		<th rowspan="2" style="background-color:#4f6228;color:#fff;vertical-align: middle;">Executive</th>
        <th style="background-color:#4f6228;color:#fff;" colspan="8">Sales Calls of the Day</th>
		<th style="background-color:#4f6228;border-left:1px solid #252525;color:#fff;" colspan="2"> Today Tentative Pickup</th>
		<th style="background-color:#4f6228;border-left:1px solid #252525;color:#fff;" colspan="2"> Today Confirm Pickup		</th>
		<th style="background-color:#4f6228;border-left:1px solid #252525;color:#fff;" colspan="2">'.date("F").' Tentative </th>
		
		<th style="background-color:#4f6228;border-left:1px solid #252525;color:#fff;" colspan="4">'.date("F").' Confirm</th>
		<th style="background-color:#4f6228;border-left:1px solid #252525;color:#fff;" colspan="3">BOB of YTD	</th>	


        </tr>

        <tr style="background-color:#c2d69a;">
           <th >No. of Accounts</th >
		    <th >Tele Call</th>
            <th>Visits</th>
            <th>Total</th>
            <th>MTD</th>
            <th>Average</th>
            <th>YTD</th>
            <th style="border-right:1px solid #252525;">Average</th>
            <th>Room Nights</th>
            <th style="border-right:1px solid #252525;">Revenue (Lacs)</th>
			<th>Room Nights</th>
            <th style="border-right:1px solid #252525;">Revenue (Lacs)</th>
            <th> Room Nights </th>
            <th style="border-right:1px solid #252525;"> Revenue (Lacs)</th>
            <th>Room Nights</th>
            <th> Revenue (Lacs)</th>
            <th >	ARR</th>
			<th style="border-right:1px solid #252525;">	Forecast</th>
            <th>Revenue (Lacs) '.$_REQUEST['CompareFinancialYear'].'</th>
            <th>Revenue (Lacs) '.$_REQUEST['CurrentFinancialYear'].'</th>
            <th>GROWTH % </th>

        </tr>';
        
   foreach($reportArray as $maintitle=>$mainDatalist){
        foreach($mainDatalist as $teamGroup=>$subDataList1){
           $IncCount=1;
            foreach($subDataList1 as $TeamName=> $subDataList){
                 $content .='<tr style="vertical-align:central;text-align:center;background-color:#c2d69a;border: 1px solid #666; font-size:11px !important">
                 <th><b></b></th>
				 <th><b> '.strtoupper($TeamName).'</b></th>';
                 
                // echo '<br><br><br><br>';
                 foreach($reportTotalArray as $reportTotalHeader1){
                     foreach($reportTotalHeader1 as $reportTotalHeader2){
                         foreach($reportTotalHeader2 as $totalLable=> $totalDataheader){
                             if($totalLable==$TeamName){
                     $GrowthRevenue=    $totalDataheader['Total_lastYearconfimed_bob_revenue']>0?round((($totalDataheader['Total_CurrentYearconfimed_bob_revenue']-$totalDataheader['Total_lastYearconfimed_bob_revenue'])/$totalDataheader['Total_lastYearconfimed_bob_revenue'])*100,2):'0';   
                    $GrowthColor = $GrowthRevenue>=0?"":"color:red;";
                       $content .='<th><b>'.$totalDataheader['Total_totalCompany'].'</b></th>';
						$content .='<th><b>'.$totalDataheader['Total_TeleCallToday'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_VisitsToday'].'</b></th>';
                        $content .='<th><b>'.($totalDataheader['Total_TeleCallToday']+$totalDataheader['Total_VisitsToday']).'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_Visits'].'</b></th>';
                        $content .='<th><b>'.round(($totalDataheader['Total_Visits']/$totalDaysGoneMtd)/$totalDataheader['Total_usercount'],2).'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_VisitsYtd'].'</b></th>';
                        $content .='<th><b>'.round(($totalDataheader['Total_VisitsYtd']/$totalDaysGoneYtd)/$totalDataheader['Total_usercount'],2).'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_tentative_pickup_roomNights'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_tentative_pickup_revenue'].'</b></th>';
						
						$content .='<th><b>'.$totalDataheader['Total_confirmed_pickup_roomNights'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_confirmed_pickup_revenue'].'</b></th>';
						
						$content .='<th><b>'.$totalDataheader['Total_tentative_pickup_roomNightsMTD'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_tentative_pickup_revenueMTD'].'</b></th>';
						
                        
                        $content .='<th><b>'.$totalDataheader['Total_mtd_bob_roomNights'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_mtd_bob_revenue'].'</b></th>';
                        $content .='<th><b>'.($totalDataheader['Total_mtd_bob_roomNights']>0?round($totalDataheader['Total_mtd_bob_revenue']/$totalDataheader['Total_mtd_bob_roomNights']*100000):'0').'</b></th>';
						$content .='<th><b>'.$totalDataheader['Total_MTDforecast_revenue'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_lastYearconfimed_bob_revenue'].'</b></th>';
                        $content .='<th><b>'.$totalDataheader['Total_CurrentYearconfimed_bob_revenue'].'</b></th>';
                        $content .='<th style="text-align:center;'.$GrowthColor.'"><b>'.round($totalDataheader['Total_lastYearconfimed_bob_revenue']>0?round((($totalDataheader['Total_CurrentYearconfimed_bob_revenue']-$totalDataheader['Total_lastYearconfimed_bob_revenue'])/$totalDataheader['Total_lastYearconfimed_bob_revenue'])*100,2):'0').'</b></th>';
                           
                           
                           $Total_mtd_bob_revenueMain   +=$totalDataheader['Total_mtd_bob_revenue'];                           
                           $Total_mtd_bob_roomNightsMain    +=$totalDataheader['Total_mtd_bob_roomNights'];
                           
                           $Total_CurrentYearconfimed_bob_revenueMain   +=$totalDataheader['Total_CurrentYearconfimed_bob_revenue'];
                           $Total_lastYearconfimed_bob_revenueMain  +=$totalDataheader['Total_lastYearconfimed_bob_revenue'];
                         
                             
                             }
                         //debugData($totalData);
                         //echo '<br>'.$totalLable;
                         }
                     }
                     
                 }
                 $content .=' </tr>';
                
                foreach($subDataList as $list=> $DataList){
                    $GrowthInnerColor = $DataList['ytd_bob_revenue_growth']>=0?"":"color:red;";
                    
                    $content .='<tr  style="vertical-align:central;text-align:center;border: 1px solid #666;">';
                        $content .='<th><b>'.$IncCount++.'</b></th>';
						
						$content .='<td style="font-size:9px !important;">'.strtoupper($list).'</td>';
						$content .='<th>'.$DataList['totalCompany'].'</th>';
                        $content .='<td >'.$DataList['TeleCallToday'].'</td>';
                        $content .='<td >'.$DataList['VisitsToday'].'</td>';
                        $content .='<td >'.($DataList['TeleCallToday']+$DataList['VisitsToday']).'</td>';
                        $content .='<td >'.$DataList['Visits'].'</td>';
                        $content .='<td >'.round($DataList['Visits']/$totalDaysGoneMtd,2).'</td>';
                        $content .='<td >'.$DataList['VisitsYtd'].'</td>';
                        $content .='<td >'.round($DataList['VisitsYtd']/$totalDaysGoneYtd,2).'</td>';
                        
                        $content .='<td >'.$DataList['tentative_pickup_roomNights'].'</td>';
                        $content .='<td >'.$DataList['tentative_pickup_revenue'].'</td>';
						$content .='<td >'.$DataList['confirmed_pickup_roomNights'].'</td>';
                        $content .='<td >'.$DataList['confirmed_pickup_revenue'].'</td>';
						$content .='<td >'.$DataList['tentative_pickup_roomNightsMTD'].'</td>';
                        $content .='<td >'.$DataList['tentative_pickup_revenueMTD'].'</td>';
                        
                        
                        $content .='<td >'.$DataList['mtd_bob_roomNights'].'</td>';
                        $content .='<td >'.$DataList['mtd_bob_revenue'].'</td>';
                        $content .='<td >'.$DataList['mtd_bob_Arr'].'</td>';
						$content .='<td >'.$DataList['MTDforecast_revenue'].'</td>';
                        $content .='<td >'.$DataList['lastYearconfimed_bob_revenue'].'</td>';
                        $content .='<td >'.$DataList['CurrentYearconfimed_bob_revenue'].'</td>';
                        $content .='<td style="text-align:center;'.$GrowthInnerColor.'">'.round($DataList['ytd_bob_revenue_growth']).'</td>';
                        $content .='</tr>';
                        
                    $TeleCallToday      +=   $DataList['TeleCallToday'];
                    $VisitsToday        +=   $DataList['VisitsToday'];
                    $TeleCallTodayAndVisitsToday    +=($DataList['TeleCallToday']+$DataList['VisitsToday']);
                    $Visits        +=   $DataList['Visits'];
                    $VisitsAvgmtd        +=   round($DataList['Visits']/$totalDaysGoneMtd,2);
                    $VisitsYtd        +=   $DataList['VisitsYtd'];
                    $VisitsAvgytd       +=round($DataList['VisitsYtd']/$totalDaysGoneYtd,2);
                    
                     $tentative_pickup_roomNights      +=   $DataList['tentative_pickup_roomNights'];
                    $tentative_pickup_revenue        +=   $DataList['tentative_pickup_revenue'];
					$tentative_pickup_roomNightsMTD      +=   $DataList['tentative_pickup_roomNightsMTD'];
                    $tentative_pickup_revenueMTD        +=   $DataList['tentative_pickup_revenueMTD'];
					
                     $confirmed_pickup_roomNights      +=   $DataList['confirmed_pickup_roomNights'];
                    $confirmed_pickup_revenue        +=   $DataList['confirmed_pickup_revenue'];
                    
                     $mtd_bob_roomNights      +=   $DataList['mtd_bob_roomNights'];
                    $mtd_bob_revenue        +=   $DataList['mtd_bob_revenue'];
                     $mtd_bob_Arr     +=   $DataList['mtd_bob_Arr'];
                    $lastYearconfimed_bob_revenue        +=   $DataList['lastYearconfimed_bob_revenue'];
                     $CurrentYearconfimed_bob_revenue     +=   $DataList['CurrentYearconfimed_bob_revenue'];
                      $ytd_bob_revenue_growth    +=   $DataList['ytd_bob_revenue_growth'];
					 $total_totalCompany    += $DataList['totalCompany'];
					 $total_MTDforecast_revenue    += $DataList['MTDforecast_revenue'];
                }
                
            }
        }
       
   }	$IncCount=$IncCount-1;
        $FinalGrowth   =  $Total_lastYearconfimed_bob_revenueMain>0?round((($Total_CurrentYearconfimed_bob_revenueMain-$Total_lastYearconfimed_bob_revenueMain)/$Total_lastYearconfimed_bob_revenueMain)*100,2):'0';
        $content .='<tr  style="vertical-align:central;text-align:center;border: 1px solid #666;font-weight:bold;">';
                        $content .='<td colspan="2">Total</td>';
						
                        $content .='<td >'.$total_totalCompany.'</td>';
						$content .='<td >'.$TeleCallToday.'</td>';
                        $content .='<td >'.$VisitsToday.'</td>';
                        $content .='<td >'.$TeleCallTodayAndVisitsToday.'</td>';
                        $content .='<td >'.$Visits.'</td>';
                        $content .='<td >'.round($VisitsAvgmtd/$IncCount,2).'</td>';
                        $content .='<td >'.$VisitsYtd.'</td>';
                        $content .='<td >'.round($VisitsAvgytd/$IncCount,2).'</td>';
                        
                        $content .='<td >'.$tentative_pickup_roomNights.'</td>';
                        $content .='<td >'.$tentative_pickup_revenue.'</td>';
						 $content .='<td >'.$confirmed_pickup_roomNights.'</td>';
                        $content .='<td >'.$confirmed_pickup_revenue.'</td>';
						$content .='<td >'.$tentative_pickup_roomNightsMTD.'</td>';
                        $content .='<td >'.$tentative_pickup_revenueMTD.'</td>';
                       
                        
                        $content .='<td >'.$mtd_bob_roomNights.'</td>';
                        $content .='<td >'.$mtd_bob_revenue.'</td>';
                        $content .='<td >'.($Total_mtd_bob_roomNightsMain>0?round($Total_mtd_bob_revenueMain/$Total_mtd_bob_roomNightsMain*100000):'0').'</td>';
						$content .='<td >'.$total_MTDforecast_revenue.'</td>';
                        $content .='<td >'.$lastYearconfimed_bob_revenue.'</td>';
                        $content .='<td >'.$CurrentYearconfimed_bob_revenue.'</td>';
                        $content .='<td style="text-align:center;'.$GrowthInnerColor.'">'.round($FinalGrowth).'</td>';
                        $content .='</tr>';


$content .= '</table>';
//debugData($_REQUEST);die;
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
$Filename=$ReportTypeMainTitle.'SalesActivity_PickupReport_'.date("Y-m-d H:i:s");
	
	$dompdf->output();
	$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
}elseif($_REQUEST['pdf']==0 && $CronSet==0 && $_REQUEST['excel']==1){
            if($Report_reportType==1){
                       // $ReportTypeMainTitle ='PICKUP ';
                        $Filename='SalesActivity-PickupReport_'.date("Y-m-d").'.xls';
                    }
                    if($Report_reportType==2){
                        $ReportTypeMainTitle ='BOB ';
                         $Filename='SalesActivity-BobReport_'.date("Y-m-d").'.xls';
                    }
        $test=$content;
         $Filename='SalesActivity-PickupReport_'.date("Y-m-d").'.xls';
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename");
        echo $test;die;
            
    
    
}elseif($CronSet==1){
    if($Report_reportType==1){
   // $ReportTypeMainTitle ='PICKUP ';
    $Filename='SalesActivity-PickupReport_'.date("Y-m-d");
}
if($Report_reportType==2){
    $ReportTypeMainTitle ='BOB ';
     $Filename='SalesActivity-BobReport_'.date("Y-m-d");
     
     $PeriodDateArray	=	explode('to',$Report_period);

    $from = date('Y-m-d',strtotime($PeriodDateArray[0]));
    $to = date('Y-m-d',strtotime($PeriodDateArray[1]));
    $LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
    if(strtotime(date('Y-m-01'))==strtotime($from) && strtotime($LastDateCurrentmonth)==strtotime($to)){
        
         $Filename='SalesActivity-BobMonthReport_'.date("Y-m-d");
    }
}
    //$Filename='CompareView-PickupReport_'.date("Y-m-d");
   // echo $content;die;
   // pdfGeneratorAttach($content, $Filename);
    
}else{
echo $content;
//echo json_encode($returnData);
}
?>

 