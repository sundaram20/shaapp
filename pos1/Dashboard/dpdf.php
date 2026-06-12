<?php include_once("../../config/auto_loader.php"); 
//checkUserLevelPermission($_SESSION['userLevel'],'fs_dashboard','view');
?>
<?php include_once("../../includes/header.php")?>
<?php include_once("../../includes/left.php");
  include_once("deviceType.php");?>
<style>
.nav-tabs > li {
	float: left;
	margin-bottom: -1px;
	background-color: #3c8dbc!important;
	border: 1px solid #fff!important;
}
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover {
	color: #555!important;
}
.nav-tabs > li > a {
	color: #fff!important;
	margin-right: 0px !important;
}
.nav > li > a:hover, .nav > li > a:active, .nav > li > a:hover {
	background-color: #69b8e5!important;
}
.mystyle {
	color: #fff;
	background-color: #f71752;
	border-color: rgba(0,0,0,0.2);
}
.ranges {
	padding: 9px !important;
}
.daterangepicker .ranges li:hover {
	background-color: #08c !important;
}
.chart-height1 {
	height: 200px;
}
 @media screen and (max-width:480px) {
.mobileSummary-today {
	width: 50%;
}
.mobile-today {
	width: 25%;
}
.mobile-responseset {
	margin-top: 10px;
	width: 45%;
}
.mobile-customrange {
	margin-top: 10px;
	width: 45%;
}
}
.chart-height1 {
/* height:550px !important;*/
}
@media screen and (max-width:320px) {
.mobile-today {
	width: 25%;
}
.mobile-responseset {
	margin-top: 10px;
	width: 45%;
}
.mobile-customrange {
	margin-top: 10px;
	width: 45%;
}
}
.chart-height1 {
/*height:550px !important;*/
}
.category, .item, .chzn-container-single .chzn-single {
	font-family: sans-serif !important;
}
.category {
	font-weight: bold !important;
}
.chzn-results li.item {
	padding-left: 25px !important;
}
</style>
<div class="content-wrapper">
  <section class="content-header">
    <h4>Trending</h4>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Trending</li>
    </ol>
  </section>
  <section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <?php 
		  
	
/*$monthsarray=array();
$CurrentDate	=	date("Y-m-d");

$previousmonthStart= date("Y-n-j", strtotime("first day of previous month"));
$previousmonthEnd = date("Y-n-j", strtotime("last day of previous month"));

 $nextmonthStart= date("Y-n-j", strtotime("first day of next month"));
 $nextmonthEnd = date("Y-n-j", strtotime("last day of next month"));

$Currentmonth   =date("Y-m-01", strtotime(date("Y-m-d")));
$monthsarray[0]['monthno']=date("m", strtotime($previousmonthStart));
$monthsarray[1]['monthno']=date("m", strtotime($Currentmonth));
$monthsarray[2]['monthno']=date("m", strtotime($nextmonthStart));
$monthsarray[0]['year']=date("Y", strtotime($previousmonthStart));
$monthsarray[1]['year']=date("Y", strtotime($Currentmonth));
$monthsarray[2]['year']=date("Y", strtotime($nextmonthStart));


$dates =array();

    $week = 1;
foreach($monthsarray as $monthandyear){
	
	$year= $monthandyear['year'];
	$month= $monthandyear['monthno'];
	
  
   
   
   
    
    $date = new DateTime("$year-$month-01");
    $days = (int)$date->format('t'); // total number of days in the month

    $oneDay = new DateInterval('P1D');

    for ($day = 1; $day <= $days; $day++) {
        $dates["Week-$week"] []= $date->format('d-m-Y');

        $dayOfWeek = $date->format('l');
        if ($dayOfWeek === 'Sunday') {
            $week++;
        }

        $date->add($oneDay);
    }
$week++;
}


$weeks=array();$k=0;
foreach($dates  as $listDate){
	
	
	foreach($listDate as $d3=>$d2){ 
	echo count($listDate);
	  if ($d3 === array_key_first($listDate)) {
        echo 'FIRST ELEMENT!'.$d2;
		$FromData	=	$d2 ;
    }

    if ($d3 === array_key_last($listDate)) {
        echo 'LAST ELEMENT!'.$d2;
		echo 'To=>'.$ToData	=   $d2;
	  }
	
	
	
	}
	$day_from   = date('j', strtotime($FromData));
    $day_to     = date('j', strtotime($ToData));
	
	$month_from = date('M', strtotime($FromData));
    $month_to   = date('M', strtotime($ToData));

    $year_from  = date('Y', strtotime($FromData));
    $year_to    = date('Y', strtotime($ToData));
	
	
	$weeks['week-'.$k]['daterange'] = "$month_from $day_from - $month_to $day_to";
	$weeks['week-'.$k]['dated'] = $FromData.' to '.$ToData;
	$weeks['week-'.$k]['WeekStart'] ='week'.$k;
	$weeks['week-'.$k]['monthName'] =$month_from;
	$returnData['weerkcon'][]= "$month_from $day_from - $month_to $day_to";
	$k++;
	}*/
	//debugData($weeks);
	//die;
	
	









		//=====================================
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
$Current_financial_year=$FinanceStarYear."-".$FinanceEndYear;

$FinanceStarLastYear=$financial_year[0]-1;
$FinanceEndLastYear=$financial_year[1]-1;
$Last_financial_year=$FinanceStarLastYear."-".$FinanceEndLastYear;


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


$previousmonthStart= date("Y-n-j", strtotime("first day of previous month"));
$previousmonthEnd = date("Y-n-j", strtotime("last day of previous month"));

$LastDateCurrentmonth   =date("Y-m-t", strtotime(date("Y-m-d")));
//$crs_sales_both_active  = selectColumn(TBL_SHOP,'crs_sales_both_active'," WHERE id= '".addslashes($_SESSION['shop'])."'");


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


//===========================Booking CompleteChar horizontalBar===============================================================
?>
          <script>
    $(".chzn-select").chosen({
		create_option: true,
		persistent_create_option: true,
		create_option_text: 'add',
	});
</script>
          <div class="form-group col-md-3">
            <div >
              <label>
                <input type="radio" id="reportType1"   name="reportType" value="1" checked="checked" onclick="updatereportType(this.value);ReportTypePickupBob();"/>
                Pickup Based </label>
              <label >
                <input type="radio" id="reportType2"   name="reportType" value="2"  onclick="updatereportType(this.value);ReportTypePickupBob();" />
                BOB Based </label>
            </div>
            <button type="button" style="margin-right: 5px;" class="btn btn-foursquare col-md-3" id="rdbcolor1"  title="Chart View"  onclick="chartType(1);" name="CharSummarytoggler11"
               value="SalesSummary" > <i class="fa fa-bar-chart"></i>&nbsp; </button>
            <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3"  id="rdbcolor2"  title="Line View" onclick="chartType(2);"  name="CharSummarytoggler11" value="Bar View" > <i class="fa fa-line-chart" aria-hidden="true"></i>&nbsp; </button>
          </div>
          <?php
if($crs_sales_both_active==1){  ?>
            <div class="col-md-3" style="width:260px;">
              <div class="form-group">
                
              <label>Group</label>
              </br>
              <?php 
            				$sql_team = "SELECT id,name FROM ".TBL_GROUP_MASTER." WHERE status='1' and id_shop='".$_SESSION['shop']."' ORDER BY display_order";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <select  class="selectpicker " name="id_group_master" id="id_group_master" Onchange="updateDateQuickSearchHotel();" data-size="7" data-show-subtext="true" data-live-search="true" style="    border: 1px solid #d2d6de; background-color:#fff;
    border-radius: 0;
    padding: 6px 12px;
    height: 34px;
">
                <option class='item' value="0">All Without Unit</option>
                <option class='item' value="10000">All With Unit</option>
                <?php while($objHot=mysqli_fetch_object($res_team)){
						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						$optionlist .= "<option class='category' ".$selected." value='".$objHot->id."_0'>".$objHot->name."</option>";
						
						 $sql_team_group = "SELECT id,name FROM ".TBL_TEAM." WHERE status='1' and id_shop='".$_SESSION['shop']."' and id_group='".$objHot->id."'  ";
            			$res_teamgroup = mysqli_query($connNew,$sql_team_group);
						
        						 while($objHotgroup=mysqli_fetch_object($res_teamgroup)){
        						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
        							$selected="selected";
        						}
        						else{
        							$selected="";
        						}
        						$optionlist .= "<option class='item'  ".$selected." value='".$objHot->id."_".$objHotgroup->id."'>".$objHotgroup->name."</option>";
        						
        						}
					} 
					
					echo $optionlist; ?>
              </select>
            </div></div>
            <?php ?>
          <div class="col-md-2  mobile-width-100"  style="width:245px;">
              <div class="form-group">
                <label>Hotel Name</label>				
				<?php /*?><input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" /><?php */?>
                

              

                <?php $hotelDropDown = '<select class="form-control select2" name="id_hotel" Onchange="updateDateQuickSearchHotel();" id="id_hotel" '.$disabledHotel.'>

														  <option value="">Select Hotel</option>';

														if(empty($_SESSION['hotel_access'])){

															$resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name`');		

														  }else{

														  $resCat = selectSql(TBL_HOTELS," where  id_shop='".addslashes($_SESSION['shop'])."' and find_in_set(id,'".$_SESSION['hotel_access']."') ",' ORDER BY `name`');												}

														  if($db->num_rows2($resCat)){

															while($resultCat = $db->fetch_object2($resCat)){

																if($resultCat->id == $row->hotelId){

																	$selected = 'selected="selected"';

																}else if($_REQUEST['search_name']== $resultCat->id){

																	$selected = 'selected="selected"';

																}else{

																	$selected = '';

																}	

																$hotelDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).' - '.strtoupper($resultCat->city).'</option>';

															}

														  }

															echo $hotelDropDown .= '</select>';

														  ?>
              </div>
              <!-- /.form-group -->
            </div>
          <?php }else{ ?>
          
          <?php } ?>
          <?php /*?><div class="form-group col-md-6">
              <div style="width:100%;">
                <label>
                 &nbsp;
              </div>
              <button type="button" style="margin-right: 5px;" class="btn btn-foursquare" id="rdb21"  title="Chart View"   name="CharSummarytoggler"
               value="SalesSummary" > Day Wise Breakup &nbsp; </button>
              <button type="button" style="margin-right: 5px;"  class="btn btn-default" id="rdb22"   title="Table View"   name="CharSummarytoggler" value="CustomRangeBookingPeriod2" >  Weekend Breakup &nbsp; </button>
             
            </div><?php */?>
          <input type="hidden" name="SelectedreportType" id="SelectedreportType" value="1">
          <input type="hidden" name="SelectedViewType" id="SelectedViewType" value="6100">
          <input type="hidden" name="SelectedSummaryViewType" id="SelectedSummaryViewType" value="7">
          <input type="hidden" name="SelectedCompareType" id="SelectedCompareType" value="1">
          <input type="hidden" name="SelectedMonthView" id="SelectedMonthView" value="1">
          <input type="hidden" name="SelectedComparePeriodDate" id="SelectedComparePeriodDate" value="<?php echo date('01-04-'.($FinanceStarYear-1)).' to '.date("31-03-".($FinanceEndYear-1))?>">
          <div class="form-group col-sm-10" >
            <button type="radio" style="margin-right: 5px;<?php echo $salesActiviteDispalay; ?>" class="btn btn-foursquare margin mobileSummary-today" id="rdb16100"    name="SummaryReportRadio" value="SalesSummary" onclick="updatereportbase('0','6100');"> Daily </button>
            <button type="radio" style="margin-right: 5px;<?php echo $salesActiviteDispalay; ?>" class="btn bg-foursquare margin mobileSummary-today" id="rdb16101"    name="SummaryReportRadio" value="SalesSummary" onclick="updatereportbase('0','6101');"> Week Days/ Weekend </button>
            
             <button type="radio" style="margin-right: 5px;<?php echo $salesActiviteDispalay; ?>" class="btn bg-foursquare margin mobileSummary-today" id="rdb16102"    name="SummaryReportRadio" value="SalesSummary" onclick="updatereportbase('0','6102');"> Weekly</button>
             
              <button type="radio" style="margin-right: 5px;<?php echo $salesActiviteDispalay; ?>" class="btn bg-foursquare margin mobileSummary-today" id="rdb16103"    name="SummaryReportRadio" value="SalesSummary" onclick="updatereportbase('0','6103');">Monthly </button>
          </div>
          <?php 
		  
		  
		  
		  
		  
		 	  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
		  
$weeks          = array();
$dayOfWeek      = date('w');
$thursday       = 1;
$diff           = $thursday - $dayOfWeek;
$minus_weeks    = 12; // past 3 months

// create week range that starts with Thursday and ends with Wednesday for the past 3 months
for ($i = 0; $i < $minus_weeks; $i++) {

    $k = $i + 1;
//echo '<br>'.$i.'==='.$diff;
    $from_formula   = strtotime("$i week $diff day");
    $to_formula     = strtotime("$k week " . ($diff - 1 ) . " day");
    $ymd_week_range = date('Y-m-d', $from_formula) . ',' . date('Y-m-d', $to_formula);
	$ymd_week_range2 = date('Y-m-d', $from_formula) . ' to ' . date('Y-m-d', $to_formula);

    $day_from   = date('j', $from_formula);
    $day_to     = date('j', $to_formula);

    $month_from = date('M', $from_formula);
    $month_to   = date('M', $to_formula);

    $year_from  = date('Y', $from_formula);
    $year_to    = date('Y', $to_formula);

    $weeks['week-'.$i]['daterange'] = "$month_from $day_from - $month_to $day_to";
	$weeks['week-'.$i]['dated'] = $ymd_week_range2;
	$weeks['week-'.$i]['WeekStart'] ='week'.$i;
}

//echo '<pre>';print_r($weeks);echo '</pre>';
				   
				   
				   foreach($weeks as $iclist=>$datedaily){
	
$StartDateList=	explode('to',$datedaily['dated']);
						
				 $StartDateListFor= $StartDateList['0'];   
			 $from = date("Y-m-d",strtotime($StartDateListFor));
							$to= $StartDateList['1'];
					   
					   
				while(strtotime($from)<=strtotime($to)){
					
				//	echo '<br> '.$from;
				$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
	}
					  
	//echo '<br>';
	}
	
	//debugData($weeks);
?>
          <style>
.overlay {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: fixed;
    background: #222;
}

.overlay__inner {
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    position: absolute;
}

.overlay__content {
    left: 50%;
    position: absolute;
    top: 50%;
    transform: translate(-50%, -50%);
}

.spinner {
    width: 55px;
    height: 55px;
    display: inline-block;
    border-width: 2px;
    border-color: rgba(247,23,82);
    border-top-color: #fff;
    animation: spin 1s infinite linear;
    border-radius: 100%;
    border-style: solid;
}

@keyframes spin {
  100% {
    transform: rotate(360deg);
  }
}

</style>
          <div class="col-sm-12">
            <label for="">&nbsp;</label>
            <br>
            <span style="color:red;display:none;" id="loading">
            <div class="overlay">
              <div class="overlay__inner">
                <div class="overlay__content"><span class="spinner"></span></div>
              </div>
            </div>
            
            <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...--></span> </div>
        </div>
        <?php  if($UserDeviceType=='desktop'){
             $HeightWidth='width="800" height="300"';
			 $HeightWidthHotel='width="800" height="400"';
			 $HeightWidthMonth='width="800" height="400"';
         }else{
         $HeightWidth='width="800" height="750"';
		 $HeightWidthHotel='width="800" height="750"';
         }?>
        <!--col-lg-offset-4 col-sm-offset-3 col-sm-3 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4-->
        <div id="WholeDivshowContent" class="toHideWholeDiv" >
          <div id="performanceChart" class="toHideperformanceChar">
         
		   
		  
           
          
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           
           <?php // DAYWISE CHART START  ===================================== ?>
            <div >
              <div >
                <div class="row" >
                  <div class="form-group col-sm-12">
                    <div class="box-header with-border">
                      <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                        <ul class="dropdown-menu" role="menu">
                          <li> <a title="Export to csv file" onclick="BarChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="chart-shafeer">Test</div>
                <div id="chart-container">
                  <?php 
		foreach($weeks as $iclist=>$datedaily){ 
		$WeekStart	= $datedaily['WeekStart'];
		$StartDateList=	explode('to',$datedaily['dated']);
		
		$StartDateListFor= $StartDateList['0'];   
		$from = date("Y-m-d",strtotime($StartDateListFor));
		$to= $StartDateList['1'];
		
		
		 ?>
                  <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>
                    <div id="<?php echo $iclist;?>">
                    <?php echo $datedaily['daterange'];?>
                    <div>
                    </strong></h5>
                  <div class="row" style="margin-bottom:25px;">
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Room Nights </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-roomnights';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Average Room Revenue </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-arr';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-revenue';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- /.col --> 
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php // LINE CHART START  ===================================== ?>
              <div id="blk-LinechartShow" class="toHideLinechart" style="display:none;">
                <div class="row" >
                  <div class="form-group col-sm-12">
                    <div class="box-header with-border">
                      <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                        <ul class="dropdown-menu" role="menu">
                          <li> <a title="Export to csv file" onclick="LineChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="chart-containerline">
                  <?php 
            foreach($weeks as $iclist=>$datedaily){ 
            $WeekStart	= $datedaily['WeekStart'];
            $StartDateList=	explode('to',$datedaily['dated']);            
            $StartDateListFor= $StartDateList['0'];   
            $from = date("Y-m-d",strtotime($StartDateListFor));
            $to= $StartDateList['1'];		
        ?>
                  <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"> <strong>
                    <div id="<?php echo 'line'.$iclist;?>"><?php echo $datedaily['daterange'];?></div>
                    </strong></h5>
                  <?php // LINE CHART START  ===================================== ?>
                  <div class="row" style="margin-bottom:25px;">
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Room Nights </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-lineroomnights';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Average Room Revenue </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-linearr';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-4" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-linerevenue';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- /.col --> 
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php // LINE CHART END  ===================================== ?>
              <!----Stat PIe Chart--> 
            </div>
            
            
            
            
            
            
            
             <?php //MOnth Weekly Start ===========================>?>
            <div id="blk-MonthWeeklyShow" class="toHideMonthWeeklychart" style="display:none">
              <div id="blk-BarMonthWeeklychartShow" class="toHideBarMonthWeeklychart">
                <div class="row" >
                  <div class="form-group col-sm-12">
                    <div class="box-header with-border">
                      <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                        <ul class="dropdown-menu" role="menu">
                          <li> <a title="Export to csv file" onclick="BarMonthlyWeeklyChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="chart-containerMonthlyweekly">
                  <?php 
		for($weekloop2=0;$weekloop2<=4;$weekloop2++){ 
			$WeekStart	='week'.$weekloop2;
			$WeekHeading	='Heading'.$weekloop2;	
		
		 ?>
                  <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>
                    <div id="<?php echo $WeekHeading;?>">
                    <?php echo $datedaily['daterange'];?>
                    <div>
                    </strong></h5>
                  <div class="row" style="margin-bottom:25px;">
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Room Nights </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-roomnightsMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Average Room Revenue </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-arrMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-revenueMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">RPD </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-rpdBarDataMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>

                    </div>
                    
                    <!-- /.col --> 
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php // LINE CHART START  ===================================== ?>
              <div id="blk-LineMonthWeeklychartShow" class="toHideLineMonthWeeklychart" style="display:none;">
                <div class="row" >
                  <div class="form-group col-sm-12">
                    <div class="box-header with-border">
                      <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                        <ul class="dropdown-menu" role="menu">
                          <li> <a title="Export to csv file" onclick="LineMonthlyWeeklyChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="chart-containerlineMonthlyWeekly">
                  <?php 
           for($weekloop3=0;$weekloop3<=4;$weekloop3++){ 
			$WeekStart	='week'.$weekloop3;	
			$WeekHeading	='Heading2'.$weekloop3;	
        ?>
                  <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"> <strong>
                    <div id="<?php echo $WeekHeading;?>"><?php echo $datedaily['daterange'];?></div>
                    </strong></h5>
                  <?php // LINE CHART START  ===================================== ?>
                  <div class="row" style="margin-bottom:25px;">
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Room Nights </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-lineroomnightsMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Average Room Revenue </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-linearrMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-linerevenueMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-6" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo $WeekStart.'-linerpdMonthWeekly';?>" <?php echo $HeightWidthMonth; ?>></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- /.col --> 
                  </div>
                  <?php }?>
                </div>
              </div>
              <?php // LINE CHART END  ===================================== ?>
              <!----Stat PIe Chart--> 
            </div>
           <?php //MOnth Weekly End ===========================>?> 
            
            
            
            
            
            
            
            
            
            
            
            
            
            <?php // DAYWISE CHART END  ===================================== ?>
            
             <div id="blk-WeeklyBarchartShow" class="toHideWeeklyBarchart" > 
             <div id="blk-BarWeeklychartShow" class="toHideBarWeeklychart">
                <div class="row" >
                  <div class="form-group col-sm-12">
                    <div class="box-header with-border">
                      <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                        <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                        <ul class="dropdown-menu" role="menu">
                          <li> <a title="Export to csv file" onclick="BarWeeklyChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
                <div id="chart-containerweekly">
                  <?php 
		/*foreach($weeks as $iclist=>$datedaily){ 
		$WeekStart	= $datedaily['WeekStart'];
		$StartDateList=	explode('to',$datedaily['dated']);
		
		$StartDateListFor= $StartDateList['0'];   
		$from = date("Y-m-d",strtotime($StartDateListFor));
		$to= $StartDateList['1'];
*/	//	$WeekStart='week0';
		
		 ?>
                  <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong>
                    <div class="WeelyChartListTitle">Weekly
                    <?php //echo $datedaily['daterange'];?>
                    <div>
                    </strong></h5>
                  <div class="row" style="margin-bottom:25px;">
                    <div class="col-md-12" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Room Nights </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo 'week0-roomnightweekly';?>" <?php //echo $HeightWidthMonth; ?> width="800" height="250"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-12" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RoomNightsYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Average Room Revenue </strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo 'week0-arrweekly';?>" <?php //echo $HeightWidthMonth; ?>  width="800" height="250"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-md-12" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Revenue (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo 'week0-revenueweekly';?>" <?php //echo $HeightWidthMonth; ?>  width="800" height="250"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-md-12" >
                      <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                          <p class="text-center"> <strong class="col-lg-offset-2">Rpd (In Lacs)</strong> </p>
                          <div class="chart ">
                            <canvas id="<?php echo 'week0-rpdweekly';?>" <?php //echo $HeightWidthMonth; ?>  width="800" height="250"></canvas>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- /.col --> 
                  </div>
                  <?php //}?>
                </div>
              </div>
              <?php // LINE CHART START  ===================================== ?>
              
              </div>
              
              
              
              
              </div>
              <?php // LINE CHART END  ===================================== ?>
              <!----Stat PIe Chart--> 
             </div>
            
            
            
            
            
          </div>
        </div>
        
        <!--<div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="SummaryDataloading"><img src="../images/ajax-loader1.gif">Loading Please Wait...</span> </div>
          </div>-->
      
                
        <!-- <button id="exportButton" type="button">Export as PDF</button>--> 
        <!--<div class="form-group col-sm-2" >
          <label for="">&nbsp;</label>
          <a  onclick="downloadPdf();" class="btn btn-warning form-control">Download</a> </div>--> 
      </div>
    </div>
  </div>
</div>
</div>
</section>
</div>
<?php include_once("../../includes/footer.php")?>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> 
<script src="https://cdn.jsdelivr.net/gh/emn178/chartjs-plugin-labels/src/chartjs-plugin-labels.js"></script> 

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script> 
<script>
  

function BarChartsaveAsPDF() { 
    
    $("#loading").show();
   html2canvas(document.getElementById("chart-shafeer"), {
      onrendered: function(canvas) {
          
         
       
         var d = new Date();
         let filename1 =  'DashboardChart_'+d;
         var imgData = canvas.toDataURL('image/png');

          /*
          Here are the numbers (paper width and height) that I found to work. 
          It still creates a little overlap part between the pages, but good enough for me.
          if you can find an official number from jsPDF, use them.
          */
          var imgWidth = 210; 
          var pageHeight = 295;  
          var imgHeight = canvas.height * imgWidth / canvas.width;
          var heightLeft = imgHeight;

          var doc = new jsPDF('p', 'mm');
          var position = 0;

          doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;

          while (heightLeft >= 0) {
            position = heightLeft - imgHeight;
            doc.addPage();
            doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
          }
          doc.save(filename1 + '.pdf');
         
          
      }
      
      
      
   });
   $("#loading").hide(); 
}

</script>