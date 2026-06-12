<?php include_once("../../config/auto_loader.php"); 
//checkUserLevelPermission($_SESSION['userLevel'],'fs_dashboard','view');
?>
<?php include_once("../../includes/header.php")?>
  <?php include_once("../../includes/left.php");
  include_once("deviceType.php");

$date =date('Y-m-d');	
$doc_type_kot='22';
$id_subsection = '0' ;	 
$retunDocConfig	=	docConfigNoValidator($doc_type_kot,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];

   $sqlNat = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22' and id='".$id_doc_type_configuration."' ";
			$resToNat = mysqli_query($connNew,$sqlNat);
			$numRowsNat =  mysqli_num_rows($resToNat);
			$rowNat =  mysqli_fetch_object($resToNat);
			$enable_nationality= $rowNat->enable_nationality;?>
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
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
 
 .chart-height1{
     height:200px;
 }
 
@media screen and (max-width:480px) {
     .mobileSummary-today{
       width:50%;  
     }
	.mobile-today{
		width:25%;}
	 .mobile-responseset{
		 margin-top:10px;
		 width:45%;}
	  .mobile-customrange{
		 margin-top:10px;
		 width:45%;}	 
 }
 .chart-height1{
    /* height:550px !important;*/
 }
@media screen and (max-width:320px) {
	 .mobile-today{
		width:25%;}
	 	.mobile-responseset{
		 margin-top:10px;
		 width:45%;}
	  .mobile-customrange{
		 margin-top:10px;
		 width:45%;}
		 }
		 .chart-height1{
     /*height:550px !important;*/
 }
 .category, .item, .chzn-container-single .chzn-single {
    font-family: sans-serif !important;}

.category {font-weight: bold !important;}

.chzn-results li.item {padding-left: 25px !important;}
  </style>
  <div class="content-wrapper">
    <section class="content-header">
      <h4>Dashboard</h4>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>
    <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box box-primary">
          <div class="box-header with-border">
            <?php 
				/*		$a_date = date("d-m-Y");
	// $year = ( date('m') > 6) ? date('Y') + 1 : date('Y');

if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceStarYear=date('Y');
	if(date('Y')==$FinanceStarYear){
	$FinanceEndYear=date('Y');
	}else{
		$FinanceEndYear=(date('Y') +1);
	}
}elseif(date('m') <=3){$FinanceStarYear=(date('Y')-1);$FinanceEndYear=(date('Y'));}
else {
    $year = (date('Y')-1)."-".date('Y');
	$FinanceStarYear=(date('Y')-1);
	if(date('Y')==$FinanceStarYear){
	$FinanceEndYear=date('Y');
	}
}
if (date('m') > 6) {
    $year = date('Y')."-".(date('Y') +1);
	$FinanceEndYear=(date('Y') +1);
}
else {
    $year = (date('Y')-1)."-".date('Y');
}
 // 2015-2016
//echo date("t-m-Y", strtotime($a_date));
*/
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

 $nextmonthStart= date("Y-n-j", strtotime("first day of next month"));
 $nextmonthEnd = date("Y-n-j", strtotime("last day of next month"));

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
            <div class="form-group col-md-2" style="width:230px;">
              <div>
                <label> &nbsp;
                 <?php /*?> <input type="radio" id="reportType1"   name="reportType" value="1" checked="checked" onclick="updatereportType(this.value);ReportTypePickupBob();"/>
                  Pickup Based </label>
                <label >
                  <input type="radio" id="reportType2"   name="reportType" value="2"  onclick="updatereportType(this.value);ReportTypePickupBob();" />
                  BOB Based<?php */?> </label>
              </div>
              <button type="button" style="margin-right: 5px;" class="btn btn-foursquare col-md-3" id="rdb21"  title="Chart View"   name="CharSummarytoggler" value="SalesSummary" > <i class="fa fa-bar-chart"></i>&nbsp; </button>
            
              <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3" id="rdb23" title="Compare View"    name="CharSummarytoggler" value="CompareRangeBookingPeriod2" > <i class="fa fa-code-compare"></i>&nbsp; </button>
            </div>
          
            <div class="col-md-3" style="width:260px;">
              <div class="form-group">
                
              <label>Group</label>
              </br>
              <?php 
            				 $sql_team = "SELECT id,field_value as name FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' and field_value!='Laundry' and field_value!='Spa And Health Club' ORDER BY field_value";
            				$res_team = mysqli_query($connNew,$sql_team);
            			?>
              <select  class="selectpicker " name="id_group_master" id="id_group_master" Onchange="updateDateQuickSearchHotel();" data-size="7" data-show-subtext="true" data-live-search="true" style="    border: 1px solid #d2d6de; background-color:#fff;
    border-radius: 0;
    padding: 6px 12px;
    height: 34px;
"><option class='item' value="0">--All Group--</option>
               <!-- <option class='item' value="0">All Without NC</option>
                <option class='item' value="10000">All With NC</option>-->
                <?php while($objHot=mysqli_fetch_object($res_team)){
						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						$optionlist .= "<option class='category' ".$selected." value='".$objHot->id."_0'>".$objHot->name."</option>";
						
						/* $sql_team_group = "SELECT id,field_value as name FROM ".TBL_ATTRIBUTES." WHERE status='1' and id_shop='".$_SESSION['shop']."' and `table_name` = 'item_group_sub' and id_group='".$objHot->id."'  ";
            			$res_teamgroup = mysqli_query($connNew,$sql_team_group);
						
        						 while($objHotgroup=mysqli_fetch_object($res_teamgroup)){
        						if(isset($_REQUEST['id_group_master']) && $_REQUEST['id_group_master']==$objHot->id){
        							$selected="selected";
        						}
        						else{
        							$selected="";
        						}
        						$optionlist .= "<option class='item'  ".$selected." value='".$objHot->id."_".$objHotgroup->id."'>".$objHotgroup->name."</option>";
        						
        						}*/
								
								
					$SubMenuSql	="   AND  id_mst_attributes_group_main IN (".$objHot->id.")";
				

		$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."'  $SubMenuSql GROUP BY id_mst_attributes_group_sub"); 
				  while($row = $db->fetch_object2($resCat)){ 

  $SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."'");					
						if($db->num_rows2($SqlAttrbute)){									  
								$resultAttrbute = $db->fetch_object2($SqlAttrbute);										
								$optionlist .='<option '.$selected.' value="'.$resultAttrbute->id.'">'.ucfirst($resultAttrbute->field_value).'</option>';
						}
					}
		// echo $subGroup;
								
								
								
								
								
					} 
					
					echo $optionlist; ?>
              </select>
            </div></div>
            
            
            <div  class="col-md-1"  style="width:245px;"> <div class="form-group">
              <label for="seasonId">Financial Year</label>
              <select class="form-control select2" name="financialyearselected" id="financialyearselected" 
                   onchange="getfinancialyear(this.value,'<?php echo $Current_financial_year;?>');getCompareYearTwo(this.value,'<?php echo $Current_financial_year;?>');" >
                <?php 
                  
                  //$seasonDropDown = '								  ';

											  $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name` desc');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->name == $Current_financial_year){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}	

													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->name.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown;

											  ?>
              </select>
              </div></div>
            <div  class="col-md-1"  style="width:245px;"> <div class="form-group">
              <label for="seasonId">Compare Year</label>
              <?php $seasonDropDown = '<select class="form-control select2" name="CompareYearselected" id="CompareYearselected"  onchange="updateDateQuickSearchCompare(this.value);">';

											  $resCat = selectSql(TBL_BUDGET_YEAR," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",' ORDER BY `name` desc');

											  if($db->num_rows2($resCat)){

											  	while($resultCat = $db->fetch_object2($resCat)){

													if($resultCat->name == $Last_financial_year){

														$selected = 'selected="selected"';

													}else{

														$selected = '';

													}	

													$seasonDropDown .= '<option '.$selected.' value="'.$resultCat->name.'">'.ucfirst($resultCat->name).'</option>';

												}

											  }

											 	echo $seasonDropDown .= '</select>';

											  ?>
              </div> </div>
              <div id="blk-FilterButtons" class="toHideFilterButtons" style="display:none">
            <div class="form-group col-md-10">
              <div class="box-bodyw">
                <button type="button" style="margin-right: 5px;" class="btn bg-default mobile-today" id="dateColor1"   name="toggler" value="1" onclick="updateDateQuickSearch('<?php echo date("d-m-Y").' to '.date("d-m-Y");?>','0');"> Today </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-foursquare" id="dateColor2"   name="toggler" value="2" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-1 days')).' to '.date('d-m-Y', strtotime('-1 days'));?>','0');"> Yesterday </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default" id="dateColor3"   name="toggler" value="3" onclick="updateDateQuickSearch('<?php echo  date('d-m-Y', strtotime('-6 days')).' to '.date("d-m-Y");?>','0');"> Last 7 Days </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor4"   name="toggler" value="4" onclick="updateDateQuickSearch('<?php echo date("01-m-Y").' to '.date("d-m-Y",strtotime($LastDateCurrentmonth));?>','0');"> This Month </button>
               
                <button type="button" style="margin-right: 5px;" class="btn btn-default mobile-responseset"  id="dateColor5"   name="toggler" value="5" onclick="updateDateQuickSearch('<?php echo date('d-m-Y', strtotime($previousmonthStart)).' to '.date('d-m-Y', strtotime($previousmonthEnd));?>','0');"> Last Month </button>
                
                
                
                <button type="button" style="margin-right: 5px;" class="btn btn-default  mobile-responseset" id="dateColorFinancialYear"   name="toggler" value="FinancialYear" data-target="#modal-warning" onclick="updateDateQuickSearch('<?php echo date("01-04-".$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);?>','1');"> This Year </button>
               
                <button type="button" class="btn btn-default mobile-customrange"  id="dateColorCustomRangeBookingPeriod"   name="toggler" data-target="#modal-success"  value="CustomRangeBookingPeriod"> Custom Range </button>
              </div>
            </div>
            
            <div id="blk-CustomRangeBookingPeriod" class="toHide" style="display:none">
              <div class="col-md-4">
                <div class="form-group">
                  <label for="start_date">Custom Range Booking Period </label>
                  <input type="text" class="form-control pull-right dateRangeEdit" placeholder="Booking Date From -  To" name="per_report_date" id="per_report_date" data-parsley-required value="<?php if($_POST) echo $_POST['pace_date'];elseif($row->pace_date) echo stripslashes(date('d-m-Y',strtotime($row->pace_date))); else echo date('01-04-'.$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear); ?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">
                </div>
              </div>
              <div class="form-group col-sm-2">
                <label for="">&nbsp;</label>
                <a  onclick="SearchButtonType();" class="btn btn-info form-control">Search</a> </div>
            </div>
            
            </div>
            
            <div class="col-sm-12"> </div>
            <input type="hidden" name="SelectedreportType" id="SelectedreportType" value="1">
            <input type="hidden" name="SelectedViewType" id="SelectedViewType" value="1">
            <input type="hidden" name="SelectedSummaryViewType" id="SelectedSummaryViewType" value="7">
            <input type="hidden" name="SelectedCompareType" id="SelectedCompareType" value="1">
            <input type="hidden" name="SelectedMonthView" id="SelectedMonthView" value="1">
            <input type="hidden" name="enable_nationality" id="enable_nationality" value="<?php echo $enable_nationality;?>">
            
            <input type="hidden" name="SelectedComparePeriodDate" id="SelectedComparePeriodDate" value="<?php echo date('01-04-'.($FinanceStarYear-1)).' to '.date("31-03-".($FinanceEndYear-1))?>">
            <div id="blk-CustomRangeBookingPeriod2" class="toHideCharSummarytoggler" style="display:none" >
              
              <div class="form-group col-sm-2" style="float:right;" >
                <div class="box-header with-border">
                  <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                    <ul class="dropdown-menu" role="menu">
                      <li><a title="Export to excel file" onclick="downloadSummaryPdf(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
                      <li><a title="Export to csv file" onclick="downloadSummaryPdf(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
                    </ul>
                  </div>
                </div>
                
                <!--  <a  onclick="downloadSummaryPdf();" class="btn btn-warning form-control">Download</a> --> 
              </div>
            </div>
            <div id="blk-CompareRangeBookingPeriod2" class="toHideCompareSummarytoggler" style="display:none" >
              <div class="box-body table-responsive col-sm-10">
                <div class="box-bodyw">
                  <button type="radio" style="margin-right: 5px;" class="btn btn-foursquare margin mobileSummary-today" id="compare1"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','1');"> Item Wise </button>
                  <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default  margin mobileSummary-today" id="compare2"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','2');"> Executivewise </button>
                  <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default margin mobileSummary-today" id="compare3"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','3');"> Hotelwise </button>
                  <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default margin mobileSummary-today" id="compare5"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','5');"> MTD | YTD </button>
                  <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default margin mobileSummary-today" id="compare62"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('2','62');"> Hotelwise Summary </button>
                  <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default margin mobileSummary-today" id="compare4"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','4');"> Top 100 Agent </button>
                   <button type="radio" style="margin-right: 5px;display:none;" class="btn bg-default margin mobileSummary-today" id="compare63"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','63');"> Top 10 Hotel </button>
                      <?php 
				 
			if($enable_nationality!='1'){
				$statusnun='style="margin-right: 5px;display:none;"';
			}else{
				$statusnun='style="margin-right: 5px;"';
				}?>
                  <button type="radio" <?php echo $statusnun; ?> class="btn bg-default margin mobileSummary-today" id="compare61"    name="SummaryReportRadio" value="SalesSummary" onclick="updateCompareSummarySearch('0','61');"> Nationality Wise </button>
                </div>
              </div>
              
              <!-- /.box-header -->
              
              <div class="form-group col-sm-2"  id="hidedownloadCompare" >
                <div class="box-header with-border">
                  <div class="btn-group  pull-right"> <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i></a>
                    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
                    <ul class="dropdown-menu" role="menu">
                     <?php /*?> <li><a title="Export to excel file" onclick="downloadComparePdf(0,1);" href="javascript:void(0)"><img src="images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
                      <li><a title="Export to csv file" onclick="downloadComparePdf(1,0);" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li><?php */?>
                       <li> <a title="Export to csv file" onclick="BarChartsaveAsPDF();" href="javascript:void(0)"><img src="images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a> </li>
                    </ul>
                  </div>
                </div>
                <!-- <label for="">&nbsp;</label>
                     <a  onclick="downloadComparePdf();" class="btn btn-warning form-control">Download</a> --> 
              </div>
            </div>
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
             $HeightWidth='width="800" height="200"';
			 $HeightWidthHotel='width="800" height="400"';
			 $HeightWidthMonth='width="800" height="400"';
         }else{
         $HeightWidth='width="800" height="750"';
		 $HeightWidthHotel='width="800" height="750"';
         }?>
		 
         <div id="chart-containerMonthlyweekly">
          <!--col-lg-offset-4 col-sm-offset-3 col-sm-3 col-xs-2 col-xs-offset-4 col-xs-2 col-md-offset-4-->
          <div id="WholeDivshowContent" class="toHideWholeDiv" >
            <div id="performanceChart" class="toHideperformanceChar">
              <div id="blk-FinancialYear" class="toHide"  >
                
                
           
                
                
                
                
                <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span> - Month Wise Report <span class="showPeriodChart"> </span> </strong></h5>
                <div class="row">
                  <div class="col-md-6" >
                      <p class="text-center"> <strong class="col-lg-offset-2">Amount(Lacs) </strong> 
                        <!--<div class="row">
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                            </div>--> 
                      </p>
                      <div class="chart ">
                        <canvas id="horizontalBar-Ytd-Mtd-Revenue" <?php //echo $HeightWidth; ?>></canvas>
                      </div>
                    </div>
                  <!-- /.col -->
                  <div class="col-md-6" >
                    <ul class="nav nav-tabs" role="tablist">
                      <li role="presentation" class="active"><a href="#RevenueYTD" aria-controls="home" role="tab" data-toggle="tab">Monthly View</a></li>
                      <li role="presentation"><a href="#RevenueQTD" aria-controls="profile" role="tab" data-toggle="tab">Quarterly View</a></li>
                      <li role="presentation"><a href="#RevenueHTD" aria-controls="profile" role="tab" data-toggle="tab">Halfyearly View</a></li>
                    </ul>
                    <div class="tab-content">
                      <div role="tabpanel" class="tab-pane active" id="RevenueYTD">
                        <p class="text-center"> <strong class="col-lg-offset-2">Amount (Month Wise in Lacs)</strong> 
                          <!--<div class="row">
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                            </div>--> 
                        </p>
                        <div class="chart ">
                          <canvas id="line-chart-revenue" <?php echo $HeightWidthMonth; ?>></canvas>
                        </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="RevenueQTD">
                        <p class="text-center"> <strong class="col-lg-offset-2">Amount (Quarterly in Lacs) </strong> 
                          <!-- <div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>--> 
                        </p>
                        <div class="chart ">
                          <canvas id="Quarterly-Revenue-chart" <?php echo $HeightWidthMonth; ?>></canvas>
                        </div>
                      </div>
                      <div role="tabpanel" class="tab-pane" id="RevenueHTD">
                        <p class="text-center"> <strong class="col-lg-offset-2">Amount (Halfyearly in Lacs) </strong> 
                          <!--<div class="row">
                         <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                          <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                          <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                        </div>--> 
                        </p>
                        <div class="chart ">
                          <canvas id="HalfYear-Revenue-chart" <?php echo $HeightWidthMonth; ?>></canvas>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <!-- /.col --> 
                </div>
                
                <!----Stat PIe Chart--> 
              </div>
             
              
              
              <div  >
              <div class="row">
                  
                  <!-- /.col -->
                  <div class="col-md-6" >
                <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showReportTypeHeadingChart"> </span>Month Wise Moving Annual Total (MAT) Report</strong></h5>
                
                    <p class="text-center"> <strong class="col-lg-offset-2">Amount (MAT in Lacs)</strong> 
                      <!--<div class="row">
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                  <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                  <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                </div>--> 
                    </p>
                    <div class="chart ">
                      <canvas id="line-chart-revenue-mat" width="800" height="450"></canvas>
                    </div>
                  </div>
                  <!-- /.col --> 
                  
                  <?php 
				 
			if($enable_nationality!='1'){
				$statusnun='style="display:none"';
			}?>
                  
                  <?php //} ?>
                  
                </div>
              </div>
              
              
              
              
                      
                      
                      
                      
              <div class="row">
                
                
               
               <?php /*?> <div class="col-md-12" >
                  <h5 class="text-center " style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"> <strong><span class="showReportTypeHeadingChart"> </span>Booking Through Report <span class="showPeriodChart"> </span></strong></h5>
                  <div class="row">
                    <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>


                  </div>
                  <div class="chart ">
                    <canvas id="BookingThroughChart" <?php echo $HeightWidth; ?>></canvas>
                  </div>
                </div><?php */?>
                
                
                
                
                
                
                
              
                  
                  
                 
                
                <!-- /.col --> 
                
                <!-- /.col --> 
              
                  
                  
                  
                  
                  
                  
                <!-- /.col --> 
                
              
                
                <!-- /.col --> 
                
                <!-- /.col --> 
              </div>
              <?php //Hotel Zone Wise start  ?>
              <?php ////Hotel Zone Wise End  ?>
            </div>
          </div>
          </div>
          <!--<div class="col-sm-12">
              <label for="">&nbsp;</label>
              <br>
              <span style="color:red;display:none;" id="SummaryDataloading"><img src="../images/ajax-loader1.gif">Loading Please Wait...</span> </div>
          </div>-->
          
          <div id="blk-CustomRangeBookingPeriod3" class="toHideCharSummarytogglerContent" style="display:none" > 
            <!-- <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showPeriod"> </span></strong></h5>-->
            
            <div class="box-body table-responsive">
              <div id="salesChartWrapper" style="padding:0px 10px 0px 10px;"> </div>
            </div>
          </div>
          <div id="blk-CompareRangeBookingPeriod3" class="toHideCharSummarytogglerContent" style="display:none" > 
            <!-- <h5 class="text-center" style="background-color: #1c4c7c;margin: 5px;padding: 10px;color: #fff;"><strong><span class="showPeriod"> </span></strong></h5>-->
            
            <div class="box-body table-responsive">
              
         <div id="chart-compareview">
        
        
        <div class=" showTopHotelGraph" id="toHideTopHotelGraph" >
         <div class="col-md-6 " id="" >
                   
                    
                        <p class="text-center"> <strong class="col-lg-offset-2">Amount </strong> 
                          <!--<div class="row">
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                            </div>--> 
                        </p>
                        <div class="chart ">
                          <canvas id="TopHotelBarRevenue-chart"<?php echo $HeightWidthHotel; ?>></canvas>
                        </div>
                      </div>
                      
                      
           <div class="col-md-6 ">
                   
                    
                        <p class="text-center"> <strong class="col-lg-offset-2">Amount </strong> 
                          <!--<div class="row">
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center"> &nbsp; </div>
                              <div class="col-md-3  col-sm-3 col-xs-3 text-center" style="background-color: #87CEFA;"> Last Year </div>
                              <div class="col-md-3 col-sm-3 col-xs-3 text-center" style="background-color: #3b8bba ;"> This Year </div>
                            </div>--> 
                        </p>
                        <div class="chart ">
                          <canvas id="TopHotelPieRevenue-chart" <?php echo $HeightWidthHotel; ?>></canvas>
                        </div>
                      </div>
                      
                      
                  </div>               
        
         <div id="ShowCompareReportData" style="padding:0px 10px 0px 10px;"> </div>
              
                   
        
        
        
        
        
        
        
        </div>
        
        
        
        
        
        
        
            </div>
          </div>
          
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
<script type="text/javascript">

$("#exportButton").click(function(){
  html2canvas(document.querySelector("#performanceChart"), { height: 1800, width: window.innerWidth * 2, scale: 1 }).then(canvas => {  	
    var dataURL = canvas.toDataURL();    
    var pdf = new jsPDF();
    pdf.addImage(dataURL, 'JPEG', 20, 20, 170, 120); //addImage(image, format, x-coordinate, y-coordinate, width, height)
    pdf.save("CanvasJS Charts.pdf");
  });
});

  downloadSummaryPdf = (pdf,excel) => {
    
        var id_hotel = $("#id_hotel").val();
        var period = $("#per_report_date").val();
           let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
        var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
        var id_shop ='<?php echo addslashes($_SESSION['shop']); ?>';
        var reportType = $("#SelectedreportType").val();
        var summaryReportType = $("#SelectedSummaryViewType").val();
         let CompareFinancialYear = $("#CompareYearselected").val();
        let CurrentFinancialYear = $("#financialyearselected").val();
		var id_mst_hotel = $("#id_mst_hotel").val();
	if(summaryReportType=='8'){
            let filename1 =  'DashboardagentTop25';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType=8';
            window.open(url1);
        }else if(summaryReportType=='9'){
             let filename1 =  'DashboardpaceReport';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
        }else if(summaryReportType=='20'){
             let filename1 =  'DashboardForecastReport1';
             let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
             
        }else if(summaryReportType=='4'){
			
			
		
	    //let filename1 =  'DashboardSalesActivityfern.ajax';
        if(id_shop=='6'){
           
			let filename1 =  'DashboardSalesActivityfern.ajax';
			  let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
             
       }else{
			
			let filename1 =  'DashboardSalesActivity.ajax';
			  let url1 = 'ajax/'+filename1+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate+'&CronSet=0';
            window.open(url1);
             
			}
			
             
           
        }else{
            let filename = 'DashboardTableView';
            let url = 'ajax/'+filename+'.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CronSet=0';
            window.open(url);
        }
	
	
	
        
        
        
   }
   downloadComparePdf = (pdf,excel) => {
    
       var id_hotel = $("#id_hotel").val();
        
        var period = $("#per_report_date").val();
        var summaryReportType = $("#SelectedCompareType").val();
        var reportType = $("#SelectedreportType").val();
        var CompareTypedownload = $("#SelectedCompareType").val();
        var id_mst_hotel = $("#id_mst_hotel").val();
        var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
        
        let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
        
        let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
   
    if(CompareTypedownload=='5'){
        let url2 = 'ajax/DashboardMtdYtdReport.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url2);
	}else if(CompareTypedownload=='4'){
        let url1 = 'ajax/DashboardCompareAgentTop.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url1);
	}else if(CompareTypedownload=='63'){
        let url1 = 'ajax/DashboardCompareTopHotel.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url1);
	}else if(CompareTypedownload=='61'){
        let url12 = 'ajax/DashboardCompareAgentDropOut.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url12);
	}else if(CompareTypedownload=='62'){
        let url12 = 'ajax/DashboardMtdReport-Demo.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&ComparePeriodDate='+ComparePeriodDate;
         window.open(url12);
	}else{
	   let url = 'ajax/DashboardCompareView.php?pdf='+pdf+'&excel='+excel+'&period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&summaryReportType='+summaryReportType+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear; 
	   window.open(url);
	}
        
   }
   

function updatereportType(value){
    
    $( "#SelectedreportType" ).val(value); 
    if(value==1){
        
       // $("#rdb19").show(); 
    }else{
       //  $("#rdb19").hide(); 
    }
    
    
}

function updateDateQuickSearchYear(year,viewMonthwise){
   
    
    var res = year.split("-");
    var FinanceStarYear = res[0];
    var FinanceEndYear = res[1];
    let quickDate = "01-04-"+FinanceStarYear+" to "+"31-03-"+FinanceEndYear;
     var SelectedComparePeriodDate = $("#SelectedComparePeriodDate").val();
     //alert(SelectedComparePeriodDate);
    updateDateQuickSearch(quickDate,viewMonthwise);
    
}

function updateDateQuickSearchCompare(ComparePeriod){
   
    
    
    
    var res = ComparePeriod.split("-");
    var FinanceComparePeriodStarYear = res[0];
    var FinanceComparePeriodEndYear = res[1];
    let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
    
    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
	    
    let quickDate = $("#per_report_date").val();
    updateDateQuickSearch(quickDate,1);
    

}
function updateDateQuickSearchHotel(){
        var SelectedViewType = $("#SelectedViewType").val();
        var viewMonthwise = $("#SelectedMonthView").val();
        var period = $("#per_report_date").val();
        
        var summaryReportType = $("#SelectedSummaryViewType").val();
        var SelectedViewType = $("#SelectedViewType").val();
        
        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }
        
        
        //alert(SelectedViewType);alert(summaryReportType);
       /* if(summaryReportType=4){
                SalesSummaryDateQuickForSearch('0','4');
        }else{
                if(SelectedViewType==1){
                    fetchPerformanceGraphData();
                }else{	
                	 fetchPerformanceSummaryData(SelectedViewType,summaryReportType);
                }
                	
        }*/
	}
	
function SearchButtonType(){
    
    var SelectedViewType = $("#SelectedViewType").val();
     var viewMonthwise = $("#SelectedMonthView").val();
        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
             var SelectedSummaryViewType = $("#SelectedSummaryViewType").val();
            if(SelectedSummaryViewType==4){
               // var summaryReportType = $("#SelectedCompareType").val();
                SalesSummaryDateQuickForSearchfunction(SelectedViewType,SelectedSummaryViewType);//viewMonthwise,summaryReportType
            
            }else{
                
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
            }
            
            
        }
    
    
}	
function updateDateQuickSearch(quickDate,viewMonthwise){
   
$( "#per_report_date" ).val(quickDate);
//var per_report_date = $("#per_report_date").val();
//alert(per_report_date);
var SelectedViewType = $("#SelectedViewType").val();
$( "#SelectedMonthView" ).val(viewMonthwise);


        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
           var SelectedSummaryViewType = $("#SelectedSummaryViewType").val();
            if(SelectedSummaryViewType==4){
               // var summaryReportType = $("#SelectedCompareType").val();
                SalesSummaryDateQuickForSearchfunction(SelectedViewType,SelectedSummaryViewType);//viewMonthwise,summaryReportType
            
            }else{
            
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
            }
            //var summaryReportType = $("#SelectedSummaryViewType").val();
            //fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }


	}
	
function ReportTypePickupBob(){
	
	
var SelectedViewType = $("#SelectedViewType").val();
var viewMonthwise = $("#SelectedMonthView").val();
//$( "#SelectedMonthView" ).val(viewMonthwise);


        if(SelectedViewType==1){
            fetchPerformanceGraphData(viewMonthwise);
        }else if(SelectedViewType==2){
            var summaryReportType = $("#SelectedCompareType").val();
            fetchCompareReportData(SelectedViewType,summaryReportType);
            
        }else{
            var summaryReportType = $("#SelectedSummaryViewType").val();
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);
        }


	}	

$(function() {
    	$("button").click(function() {
    	    
    	    var Qutare = this.id;
    	  
            if(Qutare=='rdb21'){
               
               $('.toHideFilterButtons').hide();
               
                
            }else{ 
                 $("#blk-FilterButtons").show();
                
                
            }
            if(Qutare=='dateColor6' || Qutare=='dateColor7' || Qutare=='dateColor8' || Qutare=='dateColor9'||
			 Qutare=='dateColor10'|| Qutare=='dateColor11' ){ 
            
            document.getElementById("dateColor1").className = "btn bg-default mobile-today";  
            document.getElementById("dateColor2").className = "btn bg-default";  
            document.getElementById("dateColor3").className = "btn bg-default";  
            document.getElementById("dateColor4").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor5").className = "btn btn-default mobile-responseset";
			document.getElementById("dateColor50").className = "btn btn-default mobile-responseset";
            
            document.getElementById("dateColor6").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor7").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor8").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor9").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor10").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor11").className = "btn btn-default mobile-responseset";
            
            
            
            document.getElementById("dateColor12").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor13").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor14").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColorFinancialYear").className = "btn bg-default  mobile-responseset";
            document.getElementById("dateColorCustomRangeBookingPeriod").className = "btn bg-default mobile-customrange";
            var classValue= ''; 
            document.getElementById(Qutare).className = "btn btn-foursquare "+classValue;
            }
});
    
    
    
    
    
    $("[name=toggler]").click(function(){
            $('.toHide').hide();
            $("#blk-"+$(this).val()).show();
           
          
            
            if($(this).val()=='rdb21'){
               
               $('.toHideFilterButtons').hide();
               
                
            }else{ 
                 $("#blk-FilterButtons").show();
                
                
            }
            
            //var buttons = document.getElementsByTagName("button");
            
            if($(this).val()=='FinancialYear' || $(this).val()=='CustomRangeBookingPeriod' || $(this).val()=='1' || $(this).val()=='2'|| $(this).val()=='3' || $(this).val()=='4' || $(this).val()=='5'|| $(this).val()=='12'|| $(this).val()=='13' || $(this).val()=='14' || $(this).val()=='50'){
             
            
            //dateColorFinancialYear
            document.getElementById("dateColor1").className = "btn bg-default mobile-today";  
            document.getElementById("dateColor2").className = "btn bg-default";  
            document.getElementById("dateColor3").className = "btn bg-default";  
            document.getElementById("dateColor4").className = "btn btn-default mobile-responseset";
            document.getElementById("dateColor5").className = "btn btn-default mobile-responseset";
			
            document.getElementById("dateColorFinancialYear").className = "btn bg-default  mobile-responseset";
            document.getElementById("dateColorCustomRangeBookingPeriod").className = "btn bg-default mobile-customrange"; 
            if($(this).val()=='FinancialYear' || $(this).val()=='4' || $(this).val()=='5' || $(this).val()=='6'|| $(this).val()=='7' || $(this).val()=='8' || $(this).val()=='9'|| $(this).val()=='10' || $(this).val()=='11' || $(this).val()=='50' ){
                var classValue= 'mobile-responseset';
            
            }else if($(this).val()=='1'){
                var classValue= 'mobile-today';
            }else if($(this).val()=='CustomRangeBookingPeriod'){
                var classValue= 'mobile-customrange';
            }else{
                
               var classValue= ''; 
            }
             
            document.getElementById("dateColor"+$(this).val()).className = "btn btn-foursquare "+classValue;
    }    
    });
    
    $("[name=CharSummarytoggler]").click(function(){  
            $('.toHideCharSummarytoggler').hide();
            $('.toHideCharSummarytogglerContent').hide();
            
            if($(this).val()=='CustomRangeBookingPeriod2'){
               
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod3").show();
                $('#performanceChart').hide();
                $("#blk-CompareRangeBookingPeriod2").hide();
                $("#blk-CompareRangeBookingPeriod3").hide(); 
               $( "#SelectedViewType" ).val('0'); //TableView
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
               document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
              
               





$( "#SelectedSummaryViewType" ).val('7');
                var viewMonthwise = $("#SelectedMonthView").val();
                var summaryReportType = $("#SelectedSummaryViewType").val();
                fetchPerformanceSummaryData('0','7');
               
               
            }else if($(this).val()=='SalesSummary'){ 
			$( "#SelectedMonthView" ).val('1');
				$("#per_report_date").val('<?php echo date("01-04-".$FinanceStarYear).' to '.date("31-03-".$FinanceEndYear);?>');
				 var SelectedMonthView = $("#SelectedMonthView").val();
              fetchPerformanceGraphData(SelectedMonthView);
              $('#performanceChart').show(); 
              $("#blk-FinancialYear").show();
              $("#blk-CompareRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod3").hide(); 
              $( "#SelectedViewType" ).val('1'); //ChartView'
              
              
              document.getElementById("rdb23").className = "btn btn-default col-md-3";
              document.getElementById("rdb21").className = "btn btn-foursquare col-md-3";
				
				}else if($(this).val()=='CompareRangeBookingPeriod2'){ 
                 
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod2").hide();
                $("#blk-CustomRangeBookingPeriod3").hide(); //Data List Table 
                $("#blk-CompareRangeBookingPeriod3").show(); 
                $('#performanceChart').hide();
                $( "#SelectedViewType" ).val('2'); //TableView
                 var summaryReportType = $("#SelectedCompareType").val();
				 
				 $("#per_report_date").val('<?php echo  date('d-m-Y', strtotime('-1 days')).' to '.date('d-m-Y', strtotime('-1 days'));?>');
				 
                fetchCompareReportData('2','0');
                
           
               document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
              
               
            }else{
               
              var SelectedMonthView = $("#SelectedMonthView").val();
              fetchPerformanceGraphData(SelectedMonthView);
              $('#performanceChart').show(); 
              
              $("#blk-CompareRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod3").hide(); 
              $( "#SelectedViewType" ).val('1'); //ChartView'
              
             
              document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
              document.getElementById("rdb21").className = "btn btn-default col-md-3";
              //alert($(this).val()); 
              
            }
            
    });
     
     $("[name=SummaryReportRadio]").click(function(){
            //$('.toHide').hide();
            //$("#blk-"+$(this).val()).show();
    });
 });
 
 function updateDateQuickForSummarySearch(viewMonthwise,summaryReportType){
//var SelectedViewType = $("#SelectedViewType").val();
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";

//document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today"
//document.getElementById("rdb16").className = "btn bg-default margin mobileSummary-today"
document.getElementById("rdb17").className = "btn bg-default margin mobileSummary-today"
document.getElementById("rdb1"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
            $( "#SelectedSummaryViewType" ).val(summaryReportType);
            fetchPerformanceSummaryData(viewMonthwise,summaryReportType);

	
	}
 function updateCompareSummarySearch(viewMonthwise,summaryReportType){
//var SelectedViewType = $("#SelectedViewType").val();
document.getElementById("compare1").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare2").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare3").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare4").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare5").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare61").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare62").className = "btn bg-default margin mobileSummary-today";
document.getElementById("compare63").className = "btn bg-default margin mobileSummary-today";

document.getElementById("compare"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedCompareType" ).val(summaryReportType);
 
 if(summaryReportType=='4222' || summaryReportType=='5' ){
    //$("#hidedownloadCompare").hide();
}else{
  //$("#hidedownloadCompare").show();  
}
 
            fetchCompareReportData(viewMonthwise,summaryReportType);
	}	
	
function SalesSummaryDateQuickForSearch(viewMonthwise,summaryReportType){

//	$( "#per_report_date" ).val(quickDate);
//	$( "#per_report_date" ).val(quickDate);
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb16").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb17").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb1"+summaryReportType).className = "btn bg-default margin btn-foursquare mobileSummary-today";
 $( "#SelectedSummaryViewType" ).val(summaryReportType);
SalesSummaryDateQuickForSearchfunction(viewMonthwise,summaryReportType);
	//fetchPerformanceGraphData(viewMonthwise);
	}
   downloadPdf = () => {
       var hotel_id = $("#id_hotel").val();
	   
	   var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
	   
	var reservation_date = $("#per_report_date").val();
	var reportType = $("#reportType").val();
        let url = 'ajax/DashboardajaxDownloadBookings.php?reservation_bookingDate='+reservation_date+'&reportType='+reportType+'&id_hotel='+hotel_id+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'';
        window.open(url);
   }

    function sumofArray(sum, num) { 
        return Number(sum) + Number(num); 
    } 

   
	var mtdPreValueArr = [];
	var budgetValueArr = [];
    var mtdThisValueArr = [];
	var ytdRoomRevenue=[];
	var ytdPreValueArr = [];
    var ytdThisValueArr = [];

	var exeNameArr = [];
    var graphCount = 0;
    var graphCountLead=0;
  
	var ytdRevenueChart='';
	var mtdRevenueChart='';
var mtdThisAllHotelValues= [];
var ytdAllHotelValues=[];

var MtdRevenueAllHotelValue= [];
var ytdRevenueAllHotelValue=[];
var graphCountPer = 0;
    var exeIdArr =[];
    var graphotelName=[];
    var totalGoneYtd=0;
    var totalGoneMtd=0;
    var datePeriod ='';
var ytdPrevYearAllHotelValue=[];
var ytdAchievedAllHotelValue=[];
var ytdPrevYearRevenuAllHotelLastYearValue=[];
var ytdRevenueAllHotelThisYearValue=[];
var monthNameData=[];
var MonthWiseRoomNightsData=[];
var MonthWiseRoomNightsLastYearData=[];
var MonthWiseRevenueCurrentYearData=[];
var ytdPrevYearRevenueData=[];
var mtdThisAllHotelValuesMat=[];
var ytdAllHotelValuesMat=[];
var MonthWiseRevenueCurrentYearDataMat=[];
var ytdPrevYearRevenueDataMat=[];
var mtdRoomRevenueArr=[];
var companygroupNamearray   =[];
var companygroupDatalist=[];
var CompanyGroupListLastYearArray=[];
var OfferNameArray=[];
var rowOfferListArray=[];
var mtdThisCustomeReportValues=[];
    var stacked = [];
    var CustomeReportValuesNameData=[];
var mtdRoomRevenueLastYearData= [];
var BookingThroughNameArray= [];
var	BookingThroughCurrentYearValue= [];
var rowBookingThroughLastYearValue= [];
var SegmentWiseListLastYearArrayValue =[];
var horizontalBarThisYearArrayValue=[];
var   horizontalBarLastYearArrayValue=[];
var  horizontalBarNameArrayValue    =[];
var horizontalBarThisYearRevenueArrayValue =[];
var horizontalBarLastYearRevenueArrayValue=[];
var MonthWiseRoomNightsCurrentYearQuarterlyArrayValue=[];
var MonthWiseRevenueCurrentYearQuarterlyArrayValue=    [];
var ytdPrevYearRoomNightsQuarterlyArrayValue    =[];
var ytdPrevYearRevenueQuarterlyArrayValue    =[];
var ymonthNameDataQuarterlyArrayValue    =[];

var MonthWiseRoomNightsCurrentYearHalfYearArrayValue=   [];
var MonthWiseRevenueCurrentYearHalfYearArrayValue=   [];
var ytdPrevYearRoomNightsHalfYearArrayValue    =[];
var ytdPrevYearRevenueHalfYearArrayValue    =[];
var monthNameDataHalfYearArrayValue    =[];
var HotelWiseSummaryZoanName=[];
var HotelWiseSummaryZoanRoomNightsCY=[];
var HotelWiseSummaryZoanRoomNightsLY=[];
var HotelWiseSummaryZoanRevenueCY	=[];
var HotelWiseSummaryZoanRevenueLY 	=[];
var horizontalBarThisYearAVGRoomRevenue=[];
var horizontalBarLastYearAVGRoomRevenue=[];				   
var NationalityRevenueArr=[];
var NationalityNameArr=[];				  
    function fetchPerformanceGraphData(viewMonthwise){
        $("#loading").show();
        
	
		$('.toHideWholeDiv').hide();
		let period = $("#per_report_date").val();
		let ComparePeriodDate = $("#SelectedComparePeriodDate").val();

		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		var id_mst_hotel = $("#id_mst_hotel").val();
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
        if(reportType==1){
            reportTypeFile  ='DashboardGraphPickupData.ajax.php';
        }else{
            reportTypeFile  ='DashboardGraphBOBData.ajax.php'; //reportType =2 BOB

            //alert('BOB');
        }
         if(reportType==1){
            reportHeading  =' Sales ';
       }else{
           reportHeading  =' BOB ';
       }
		//console.log($period+'---'+$id_team);	
		//if(id_hotel){	
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_team='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&id_group_sub_master='+id_group_sub_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&ComparePeriod='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear,
				success:function(data){
						$('#WholeDivshowContent').show();
                    
					data = JSON.parse(data);

                    exeNameArr = data.executives;
                    budgetValueArr =data.budgetVal;
                    mtdPreValueArr =data.mtdLastVal;
                    mtdThisValueArr =data.mtdThisVal;
                    ytdPreValueArr =data.ytdLastVal;
                    ytdThisValueArr =data.ytdThisVal;
                    mtdVisits = data.mtdVisits;
                    ytdVisits = data.ytdVisits;
                    mtdRoomRevenue = data.mtdRoomRevenue;
                    ytdRateletters = data.ytdRateLetters;
                    mtdTotalExpense = data.mtdTotalExpense;
                    ytdTotalExpense = data.ytdTotalExpense;
                    totalGoneYtd = data.totalDaysGoneYtd;
                    totalGoneMtd = data.totalDaysGoneMtd;
                    datePeriod = data.datePeriod;
                    stacked = data.stacked;
					ytdRoomRevenue = data.ytdRoomRevenue
					mtdThisAllHotelValues=data.mtdThisAllHotelValues;
					ytdAllHotelValues=data.ytdAllHotelValues;
                    graphotelName=data.graphotelName;
					ytdPrevYearAllHotelValue=data.ytdPrevYearAllHotelValue;
					ytdAchievedAllHotelValue=data.ytdAchievedAllHotelValue;
					mtdRoomRevenueLastYearData=data.mtdRoomRevenueLastYearArr;
					MtdRevenueAllHotelValue=data.MtdRevenueAllHotelValue;
					ytdRevenueAllHotelValue=data.ytdRevenueAllHotelValue;
					
					
					ytdPrevYearRevenuAllHotelLastYearValue=data.ytdPrevYearRevenuAllHotelLastYearValue;
					ytdRevenueAllHotelThisYearValue=data.ytdRevenueAllHotelThisYearValue;
					monthNameData=data.monthNameData;
					MonthWiseRoomNightsData=data.MonthWiseRoomNightsData;
					MonthWiseRoomNightsLastYearData=data.MonthWiseRoomNightsLastYearData;
					MonthWiseRevenueCurrentYearData=data.MonthWiseRevenueCurrentYearData;
					ytdPrevYearRevenueData=data.ytdPrevYearRevenueData;
				    
                    
					
					mtdThisAllHotelValuesMat=data.mtdThisAllHotelValuesMat;
					ytdAllHotelValuesMat=data.ytdAllHotelValuesMat;
					MonthWiseRevenueCurrentYearDataMat=data.MonthWiseRevenueCurrentYearDataMat;
					ytdPrevYearRevenueDataMat=data.ytdPrevYearRevenueDataMat;
					mtdRoomRevenueArr=data.mtdRoomRevenueArr;
					OfferNameArray=data.OfferNameArray;
					rowOfferListArray=data.rowOfferListArray;
                    mtdThisCustomeReportValues=data.mtdThisCustomeReportValues;
                    
                    CustomeReportValuesNameData=    data.CustomeReportValuesName
                    mtdRoomCustomeReportRevenue =   data.mtdRoomCustomeReportRevenue;
                    
                    
                    
                    mtdThisCustomeLastYearReportValues=    data.mtdThisCustomeLastYearReportValues;
                    mtdRoomCustomeLastYearReportRevenue=    data.mtdRoomCustomeLastYearReportRevenue;
                    
                    
                   
                    
                    MonthWiseRoomNightsCurrentYearQuarterlyArrayValue=    data.MonthWiseRoomNightsCurrentYearQuarterly;
                    MonthWiseRevenueCurrentYearQuarterlyArrayValue=    data.MonthWiseRevenueCurrentYearQuarterly;
                    ytdPrevYearRoomNightsQuarterlyArrayValue    =data.ytdPrevYearRoomNightsQuarterly;
                    ytdPrevYearRevenueQuarterlyArrayValue    =data.ytdPrevYearRevenueQuarterly;
                    ymonthNameDataQuarterlyArrayValue    =data.monthNameDataQuarterly;
                   
                    MonthWiseRoomNightsCurrentYearHalfYearArrayValue=    data.MonthWiseRoomNightsCurrentYearHalfYear;
                    MonthWiseRevenueCurrentYearHalfYearArrayValue=    data.MonthWiseRevenueCurrentYearHalfYear;
                    ytdPrevYearRoomNightsHalfYearArrayValue    =data.ytdPrevYearRoomNightsHalfYear;
                    ytdPrevYearRevenueHalfYearArrayValue    =data.ytdPrevYearRevenueHalfYear;
                    monthNameDataHalfYearArrayValue    =data.monthNameDataHalfYear;
                   CYLable=data.CYLable;
                   LYLable=data.LYLable;
				   
				   horizontalBarThisYearArrayValue=    data.horizontalBarThisYear;
                    horizontalBarLastYearArrayValue=    data.horizontalBarLastYear;
                    horizontalBarNameArrayValue    =data.horizontalBarName;
					horizontalBarThisYearRevenueArrayValue=    data.horizontalBarThisYearRevenue;
                    horizontalBarLastYearRevenueArrayValue=    data.horizontalBarLastYearRevenue;
				   
				   HotelWiseSummaryZoanName=data.HotelWiseSummaryZoanName;
				   HotelWiseSummaryZoanRoomNightsCY=data.HotelWiseSummaryZoanRoomNightsCY;
				   HotelWiseSummaryZoanRoomNightsLY=data.HotelWiseSummaryZoanRoomNightsLY;
				   
				   HotelWiseSummaryZoanRevenueCY=data.HotelWiseSummaryZoanRevenueCY;
				   HotelWiseSummaryZoanRevenueLY=data.HotelWiseSummaryZoanRevenueLY;
				NationalityRevenueArr=data.NationalityRevenueArr;
				NationalityNameArr=data.NationalityNameArr;
				   
                  // alert(data.enable_nationality);
                    //alert(CustomeReportValuesNameData);
				    performanceChart(graphCount,viewMonthwise,data.enable_nationality);
				    
				    $(".showReportTypeHeadingChart").html(reportHeading);
				    $(".showPeriodChart").html(' For Period '+data.reportPeriod);
                   
                    
                                       
                    graphCount++;
                    $("#loading").hide();
                    
				}
			})
		//}else{
		//	alert('Please Select Hotel.');
		//}
        //leadGraphData();
        

	}

    
    

    
    function performanceChart(graphCount,viewMonthwise,enablenationality){
		
		if(graphCount>0){
			
            monthlyBarRevenueChart.destroy();
		    HalfYearRevenueBar.destroy();
			 QuarterlyRevenueBar.destroy();
			 monthlyBarRevenueLineChart.destroy();
			CompleteChartHorizontalBarRevenue.destroy();
			
			
		
			
			
		  
        }
		
		let QuarterlyRevenueBarChart = document.getElementById('Quarterly-Revenue-chart').getContext('2d');
		let monthRevenueBarPerChart = document.getElementById('line-chart-revenue').getContext('2d'); 
		let HalfYearRevenueBarChart = document.getElementById('HalfYear-Revenue-chart').getContext('2d');
		let monthRevenueBarLinePerChart = document.getElementById('line-chart-revenue-mat').getContext('2d');
		let ytdMtdChartHorizontalBarRevenue  = document.getElementById('horizontalBar-Ytd-Mtd-Revenue').getContext('2d');
	  			
		
	
	
		
	

		
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
CompleteChartHorizontalBarRevenue = new Chart(ytdMtdChartHorizontalBarRevenue, {
            type: 'horizontalBar',
            data: {
                labels: horizontalBarNameArrayValue,
                datasets: [
                {
                    //label: 'Year ('+CYLable+'): '+horizontalBarThisYearArrayValue+'',
                    backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
                    data: horizontalBarThisYearRevenueArrayValue
                },{
                    //label: 'Year ('+LYLable+'): '+horizontalBarLastYearArrayValue+'',
                     backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
                    data: horizontalBarLastYearRevenueArrayValue
                }
                
                ]
            },
options: {
    scales: {
        yAxes: [{
            ticks: {
                beginAtZero: true
            }
        }]
    },
    responsive: true,
            legend: {
                position: 'bottom',
                display: false,
 
            },
                plugins: {
                  labels: {
                    render: () => {}
                  }
                },"hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 10, bar._model.y + 10);
          });
        });
      }
    }
}
 });	
 
 
 	 monthlyBarRevenueChart = new Chart(monthRevenueBarPerChart, {
  type: 'bar',
  data: {
    labels: monthNameData,
    datasets: [{ 
        data: ytdPrevYearRevenueData,
        label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1
      }, { 
        data: MonthWiseRevenueCurrentYearData,
        label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(60,141,188,0.8)',
        borderColor: 'rgba(54, 162, 235,1)'
					
      }
    ]
  }, options: {
	 
  
 
	  responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
	  plugins: {
                    labels:{
                      render:'value',  
                    }   
	  },
    
  }
});
	
	QuarterlyRevenueBar = new Chart(QuarterlyRevenueBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: ymonthNameDataQuarterlyArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueQuarterlyArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRevenueQuarterlyArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearQuarterlyArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRevenueCurrentYearQuarterlyArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});


	HalfYearRevenueBar = new Chart(HalfYearRevenueBarChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: monthNameDataHalfYearArrayValue,
                datasets: [{
                    label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueHalfYearArrayValue.reduce(sumofArray)+'',
                  	 backgroundColor: 'rgba(54, 162, 235,0.5)',
					borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: ytdPrevYearRevenueHalfYearArrayValue
                },
                {
                    label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearHalfYearArrayValue.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: MonthWiseRevenueCurrentYearHalfYearArrayValue
                }]
				
            },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});	
	monthlyBarRevenueLineChart = new Chart(monthRevenueBarLinePerChart, {
  type: 'line',
  data: {
    labels: monthNameData,
    datasets: [{ 
    //MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)     
        label: 'Year ('+LYLable+'): '+ytdPrevYearRevenueData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(54, 162, 235,0.5)',
		borderColor: 'rgba(54, 162, 235,1)',
		margin: 1,
		data: ytdPrevYearRevenueDataMat
      }, { 
        
        label: 'Year ('+CYLable+'): '+MonthWiseRevenueCurrentYearData.reduce(sumofArray).toFixed(2)+'',
        backgroundColor: 'rgba(60,141,188,0.8)',
        borderColor: 'rgba(54, 162, 235,1)',
        margin: 1,
        data: MonthWiseRevenueCurrentYearDataMat,
					
      }
    ]
  },  // Configuration options go here
            options: {showAllTooltips: true,
			  
  /*  "hover": {
      "animationDuration": 0
    },
    "animation": {
      "duration": 1,
      "onComplete": function() {
        var chartInstance = this.chart,
          ctx = chartInstance.ctx;

        ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
        ctx.textAlign = 'center';
        ctx.textBaseline = 'bottom';
		ctx.defaultFontColor= '#981C1E';

        this.data.datasets.forEach(function(dataset, i) {
          var meta = chartInstance.controller.getDatasetMeta(i);
          meta.data.forEach(function(bar, index) {
            var data = dataset.data[index];
            ctx.fillText(data, bar._model.x + 13, bar._model.y + 15);
          });
        });
      }
    },*/
    legend: {
      "display": true,
	  fontColor: '#981C1E'
    },
    tooltips: {
      "enabled": true,
	  fontColor: '#981C1E'
    },maintainAspectRatio: false,
    plugins: {
      datalabels: {
        color: 'red'
      }
    }
  }
});	



















		}

function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++ ) {
            color += letters[Math.floor(Math.random() * 32)];
        }
		 var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return "rgb(" + r + "," + g + "," + b + ")";
        //return color;
    }

   
  
  
  function fetchPerformanceSummaryData(viewMonthwise,summaryReportType){}
	
	var TopHotelRoomNightsData=[];
var TopHotelNameData=[];
var TopHotelRevenueDataCY=[];
var PieTopHotelLable=[];
					var PieTopHotelRevenueDataCY=[];
 function fetchCompareReportData(viewMonthwise,summaryReportType){
     //alert('2');loading
     $("#loading").show();
        //$("#SummaryDataloading").show(); 
		let period = $("#per_report_date").val();
		let CompareFinancialYear = $("#CompareYearselected").val();
		let CurrentFinancialYear = $("#financialyearselected").val();
		//let reportType = $("#reportType").val();
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_hotel = $("#id_hotel").val();
		var id_mst_hotel = $("#id_mst_hotel").val();
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
	    
        if(summaryReportType==4){
            reportTypeFile  ='DashboardCompareAgentTop.php';
            
        }else if(summaryReportType==61){
            reportTypeFile  ='DashboardCompareAgentDropOut.php';
        }else if(summaryReportType==63){
            reportTypeFile  ='DashboardCompareTopHotel.php';
        }else if(summaryReportType==62){
            reportTypeFile  ='DashboardMtdReport-Demo.php';
        }else if(summaryReportType==5){
            reportTypeFile  ='DashboardMtdYtdReport.php';
        }else{summaryReportType=63;
            //reportTypeFile  ='DashboardCompareView.php';
			reportTypeFile  ='DashboardCompareTopHotel.php';
			
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_hotel='+id_hotel+'&id_mst_hotel='+id_mst_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&ComparePeriodDate='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
if(summaryReportType==63 || summaryReportType==61){
           
			data = JSON.parse(data);

                    TopHotelRoomNightsData=data.TopHotelRoomNightsData
	 				TopHotelNameData=data.TopHotelNameData;
					TopHotelRevenueDataCY=data.TopHotelRevenueDataCY;
					
					PieTopHotelLable=data.pie_top_hotel_lable;
					PieTopHotelRevenueDataCY=data.pie_top_hotel_data;
					
				$(".showTopHotelGraph").show();

					performanceTopHotelChart(graphCountPer);
			$("#ShowCompareReportData").html(data.content);
        }else{					
				
			 $("#toHideTopHotelGraph").hide();	
			$("#ShowCompareReportData").html(data);
		}
					graphCountPer++;
                    $("#loading").hide();
                    
					
				}
			})
	
        

	}	

	//=====================================================================
	
	 function performanceTopHotelChart(graphCountPer){   
          if(graphCountPer>0){
			  TopHotelRoomNightBarChart.destroy();
		   TopHotelRoomNightPieChart.destroy();		  
        
		  }
		let TopHotelBarPerChart = document.getElementById('TopHotelBarRevenue-chart').getContext('2d');
		 let TopHotelPiePerChart = document.getElementById('TopHotelPieRevenue-chart').getContext('2d');
		//let TopHotelRevenueBarPerChart = document.getElementById('TopHotelBarRevenue-chart').getContext('2d'); 		
		//let ChartJsImage = require('chartjs-to-image');
      
TopHotelRoomNightBarChart = new Chart(TopHotelBarPerChart, {
  type: 'bar',
  	// The data for our dataset
            data: {
                labels: TopHotelNameData,
                datasets: [{
                    label: "Top 10 Item Revenue : "+ TopHotelRevenueDataCY.reduce(sumofArray)+'',
                   backgroundColor: 'rgba(60,141,188,0.8)',
        			borderColor: 'rgba(54, 162, 235,1)',
					margin: 1,
                    data: TopHotelRevenueDataCY
                }]
				
            },
            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
               
               
                plugins: {
                    labels:{
                      render:'value',  
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});
	//TopHotelRoomNightBarChart.toFile('mychart.png');
	 
	 
	
	TopHotelRoomNightPieChart = new Chart(TopHotelPiePerChart, {
 type: 'pie',
  	// The data for our dataset
            data: {
    labels: PieTopHotelLable,
    datasets: [ { 
        data: PieTopHotelRevenueDataCY,
        label: 'Grand Total : '+PieTopHotelRevenueDataCY.reduce(sumofArray).toFixed(2)+'',
       backgroundColor: [
       "#2ecc71",
        "#3498db",
        "#95a5a6",
        "#9b59b6",
        "#f1c40f",
        "#eaa6a6",
        "#abeae8",
        "#83c1ea",
        "#c13275",
        "#3232c1",
		"#32c17c",
        "#e595aa",
		"#e1e283",	
		"#ead16e",
        "#abeae8",
        "#83c1ea",
        "#00fffa"
      ]
					
      }
    ]
  },

            // Configuration options go here
            options: {
				
				responsive: true,
            legend: {
                position: 'bottom',
                display: true,
 
            },
                plugins: {
                    labels:{
                       render: 'percentage',
					  //fontColor: ['red','red','red','red','red','red','red','red','red','red','red','red','red','red'],
					  precision: 2    
                    }   
                }/*,
                title:{
                    display:true,
                    text:'Total Room Revenue : '+MonthWiseRoomNightsData.reduce(sumofArray)+' '
                }*/
            }
 
});

 
	 
	 
	 
	 }
 
	//======================================================================
	
	
	
	
	
	
	
	
	
	
	
	
	
function SalesSummaryDateQuickForSearchfunction(viewMonthwise,summaryReportType){}	
window.onload = function() {
   getfinancialyear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
    getCompareYear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
     $('.toHideWholeDiv').hide();
        fetchPerformanceGraphData(1);
		
}
	function getfinancialyear(year,Currentfinancialyear){
	    var year = $("#financialyearselected").val();
	    //alert(Currentfinancialyear);
	    //alert(year);F
	    
	   
	    if(Currentfinancialyear==year){
	        
	         $("#chartCompleteView").show();
	          $("#dateColor12").show();$("#dateColor13").show();$("#dateColor14").show();
	    }else{
	        $("#chartCompleteView").hide();
	        $("#dateColor12").hide(); $("#dateColor13").hide(); $("#dateColor14").hide();
	    }
	    
	    
	    $("#loading").show();
	    reportTypeFile  ='ajaxfinancialyearsplit.php';
	   	$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'financialyear='+year,
				success:function(data){
				
                   let datas = JSON.parse(data);
                   
                    $("#per_report_date").val(datas.per_report_date);
                    $("#dateColor6").val(datas.Q1_APR_JUNE);
                    $("#dateColor7").val(datas.Q2_JULY_SEP);
                    $("#dateColor8").val(datas.Q3_OCT_DEC);
                    $("#dateColor9").val(datas.Q4_JAN_MARCH);
                    
                    $("#dateColor10").val(datas.H1_APR_SEP);
                    $("#dateColor11").val(datas.H2_OCT_MARCH);
                    
                    
    
                    
                    
                    
                    
                    
                    //$("#per_report_date").val(datas.per_report_date);
                    
                   // $("#loading").hide();
                    
					
				}
			}) 
	}
  function getCompareYear(CompareYear,Currentfinancialyear){	

 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxgetCompareYear.php',
			   data: 'CompareYear='+CompareYear+'&Currentfinancialyear='+Currentfinancialyear, 
			   success: function (result) {				   
			     $('#CompareYearselected').empty();
				 $('#CompareYearselected').html(result);
				 
        let ComparePeriod = $("#CompareYearselected").val();
        
        var res = ComparePeriod.split("-");
        var FinanceComparePeriodStarYear = res[0];
        var FinanceComparePeriodEndYear = res[1];
        let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
	    
	    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
                    
				 
				}
		});
  }
  
   function getCompareYearTwo(CompareYear,Currentfinancialyear){	

 	  	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxgetCompareYear.php',
			   data: 'CompareYear='+CompareYear+'&Currentfinancialyear='+Currentfinancialyear, 
			   success: function (result) {				   
			     $('#CompareYearselected').empty();
				 $('#CompareYearselected').html(result);
				 
        let ComparePeriod = $("#CompareYearselected").val();
        
        var res = ComparePeriod.split("-");
        var FinanceComparePeriodStarYear = res[0];
        var FinanceComparePeriodEndYear = res[1];
        let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
	    
	    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
                    
		updateDateQuickSearchYear(CompareYear,1)		 
				}
		});
		
  }
  function BarMonthlyWeeklyChartsaveAsPDF() { 
    
    $("#loading").show();
   html2canvas(document.getElementById("chart-containerMonthlyweekly"), {
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

<script>
  

function BarChartsaveAsPDF() { 
    
    $("#loading").show();
   html2canvas(document.getElementById("chart-compareview"), {
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
}</script>