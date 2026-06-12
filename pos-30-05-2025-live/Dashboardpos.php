<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
  <?php 
  include_once("include/function.php");
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

?>
  <style type="text/css">
		.fieldset {
		  border: 2px groove #3C8DBC;
		  border-top: none;
		  padding: 0.5em;
		  margin: 1em 2px;
		}

		.fieldset>p {
		  font: 1.4em normal;
		  margin: -0.8em -0.4em 0;
		}

		.fieldset>p>span {
		  float: left;
		}

		.fieldset>p:before {
		  border-top: 3px solid #3C8DBC;
		  content: ' ';
		  float: left;
		  margin: 0.5em 2px 0 -1px;
		  width: 0.75em;
		}

		.fieldset>p:after {
		  border-top: 3px solid #3C8DBC;
		  content: ' ';
		  display: block;
		  height: 0.5em;
		  left: 2px;
		  margin: 0 1px 0 0;
		  overflow: hidden;
		  position: relative;
		  top: 0.5em;
		}

		.text{
			font-size:20px;
		}
	</style>
	<?php 
		
			$DispalyClass="display:none;";
			$viewClass="";
			$viewIcons='fa fa-plus-square-o fa-1x';
		
			$DispalyClass="";
			$viewClass='fieldset';
			$viewIcons='fa fa-minus-square-o fa-1x';
		//}
	?><style>
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
  </style>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
     	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
	  
    <section class="content">
      <div class="row">
        <div class="col-xs-12"> 
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              
              <small class="text-center has-error">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
              </small>
              </div>
            <form name="searchForm" action="" method="get">
           <input type="hidden" value="1" name="searchFormSubmit" /> 
        <div class="box-body">











<section class="content">
      <div class="container-fluid">
        <h5 class="mb-2">Info Box</h5>
        <div class="row">

          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="far fa-envelope"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Messages</span>
                <span class="info-box-number">1,410</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-success"><i class="far fa-flag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Bookmarks</span>
                <span class="info-box-number">410</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="far fa-copy"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Uploads</span>
                <span class="info-box-number">13,648</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
              <span class="info-box-icon bg-danger"><i class="far fa-star"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Likes</span>
                <span class="info-box-number">93,139</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
         </div>
        <!-- /.row -->
      </section>  

















          <div class="row">
<input type="hidden" name="SelectedreportType" id="SelectedreportType" value="1">
                <input type="hidden" name="SelectedViewType" id="SelectedViewType" value="1">
                <input type="hidden" name="SelectedSummaryViewType" id="SelectedSummaryViewType" value="7">
                <input type="hidden" name="SelectedCompareType" id="SelectedCompareType" value="1">
                <input type="hidden" name="SelectedMonthView" id="SelectedMonthView" value="1">
                 <input type="hidden" name="SelectedComparePeriodDate" id="SelectedComparePeriodDate" value="<?php echo date('01-04-'.($FinanceStarYear-1)).' to '.date("31-03-".($FinanceEndYear-1))?>">
 <div class="form-group col-md-2">
              
         
               	 <div style="width:100%;">
                <label>&nbsp; </label>
          </div>	
            
                 <button type="button" style="margin-right: 5px;" class="btn btn-foursquare col-md-3" id="rdb21"  title="Chart View"   name="CharSummarytoggler" value="SalesSummary" >
                <i class="fa fa-bar-chart"></i>&nbsp;
                </button>
                <button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3" id="rdb22"   title="Reports "   name="CharSummarytoggler" value="CustomRangeBookingPeriod2" >
                <i class="fa fa-list-alt" aria-hidden="true"></i>&nbsp;
                </button>
                
                <!--<button type="button" style="margin-right: 5px;" class="btn btn-default col-md-3" id="rdb23" title="Compare View"    name="CharSummarytoggler" value="CompareRangeBookingPeriod2" >
                <i class="fa fa-exchange"></i>&nbsp; 
                </button>-->
                
            
          
          
            </div>




            <div class="col-md-4">
              <div class="form-group">
               	<label> Select Period</label>	
	          	<div class="input-group"> 
          			<div class="input-group-addon">
						<i class="fa fa-calendar"></i> 
				   	</div>  
					<!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
					<input type="text" class="form-control pull-right dateRangeReport1" placeholder="Select From -  To" name="datedashboard" id="dateRangeReportd" data-parsley-required value="" data-parsley-errors-container="#report_dateError"  autocomplete="off">
				</div>
             </div>
              <!-- /.form-group -->




            </div>

           
       
            
          </div>
        
 <div id="blk-CustomRangeBookingPeriod2" class="toHideCharSummarytoggler" style="display:none">
                   <div class="form-group col-sm-10" >                   
                
            </div>
            <div class="row">

	        		<div class="col-md-9 col-sm-9">	        		
		        		<div  id="fieldset" class="<?php echo $viewClass; ?>">
		        			<p id="showheadelion" class="text-primary"><span id="textReportShow" style="font-size:15px;">More Options</span></p>
		        			<div  id="moreReportDiv" style="padding-left:0px;<?php echo $DispalyClass; ?>">
		        				<div class="box-header" style="padding:5px">
			        				<div class="box-tools pull-right">
			        					<!--<button type="button" class="btn btn-box-tool" id="btnMoreReportDiv">X
		                    			</button>-->
			        				</div>
		        				</div>
		        				<div class="box-body">
	        						<div class="row">
	        						<div class="col-md-3">
	 <div class="form-group">
                	<label>Select Report</label>
					<div class="input-group"> 
		              	<div class="input-group-addon">
							<i class="fa fa-dot-circle-o"></i> 
						</div> 
						<select class="form-control select2" name="id_report_type" data-parsley-required id="id_report_type" style="width:100%">	
                        <option  value="1">Sales Report</option>
							<option  value="2">Settlement Report</option>
							<option  value="3">Analysis Report</option>
                            
                                
						</select> 
					</div>                              
                </div>
            </div>

	<div class="col-md-3"> <div class="form-group">
                	<label>Format</label>
					<div class="input-group"> 
		              	<div class="input-group-addon">
							<i class="fa fa-dot-circle-o"></i> 
						</div> 
						<select class="form-control select2" name="id_format" data-parsley-required id="id_format" style="width:100%">	
                            <option  value="1">Item</option>
							<option  value="1">Group</option>
                             <option  value="1">Sub Group</option>
							<option  value="1">Shift</option>
							<option  value="1">Steward</option>
							<option  value="1">Discount</option>   
						</select> 
					</div>                              
                </div>
            </div>

	<div class="col-md-3"> <div class="form-group">
                	<label>Order By</label>
					<div class="input-group"> 
		              	<div class="input-group-addon">
							<i class="fa fa-dot-circle-o"></i> 
						</div> 
						<select class="form-control select2" name="order_by" data-parsley-required id="order_by" style="width:100%">
							<option  value="1">Name</option>
							<option  value="1">Quantity</option>
							<option  value="1">Value</option>
                            
                                
						</select> 
					</div>                              
                </div>
            </div>
	    	 </div>
            </div>					
		        				</div>
		        			</div>
		        		</div>
		        	



	
             
<!---Filter Star---------------------------------------------------------------------------->
 

	        		<div class="col-md-9 col-sm-9">	        		
		        		<div  id="fieldset" class="<?php echo $viewClass; ?>">
		        			<p id="showheadelion" class="text-primary"><span id="textReportShow" style="font-size:15px;">Filter </span></p>
		        			<div  id="moreReportDiv" style="padding-left:0px;<?php echo $DispalyClass; ?>">
		        				<div class="box-header" style="padding:5px">
			        				<div class="box-tools pull-right">
			        					<!--<button type="button" class="btn btn-box-tool" id="btnMoreReportDiv">X
		                    			</button>-->
			        				</div>
		        				</div>
		        				<div class="box-body">
	        						<div class="row">
	 
	 <!----Row----->
	 
 <div class="col-md-4">                                
             <div class="form-group">
                <label>Select Main Group (leave blank for all)</label>
							<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-dot-circle-o"></i> 
							   	</div> 
								 <select class="form-control select2" name="id_main_group[]" data-parsley-required id="id_main_group" multiple="multiple"  onChange="mainGroup(this);" style="width:100%">	
								 <?php
                                $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' and field_value!='Laundry' and field_value!='Spa And Health Club'",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';			

		
									}?>
	</select>
								 <?php }?>
							</div>                              
                </div>
            </div>    

 <div class="col-md-4">
              <div class="form-group">
              <input type="hidden" name="id_data_main_group" id="id_data_main_group" value="">
                <label>Select Sub Group(leave blank for all)	</label>
							<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-dot-circle-o"></i> 
							   	</div> 
                                <div id="ListSubGroup">
								 <select class="form-control select2" name="id_sub_group[]" data-parsley-required id="id_sub_group" multiple="multiple"  onChange="subGroup(this);" style="width:100%">	
								 <?php
                               /* $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';			

		
									}*/?>
</select>
								  <?php //}?>
                                  </div>
							</div>                              
                </div>              
              <!-- /.form-group -->
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label>Select Item(leave blank for all)</label>
							<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-dot-circle-o"></i> 
							   	</div> 
                                <div id="ListSubGroup">
								 <select class="form-control select2" name="id_item[]" data-parsley-required id="id_item" multiple="multiple" style="width:100%" />	
								 <?php
                                /*$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';			

		
									}*/?>
</select>
								  <?php //}?>
                                  </div>
							</div>                              
                </div>              
              <!-- /.form-group -->
            </div>




   
	 <!----Row-->

	    	 </div>



            </div>					
		        				</div>
		        			</div>








		        		</div>
		        	



	
<!---Filter End----------------------------------------------------------------------------->

<div class="col-md-3">
            <!-- Info Boxes Style 2 -->
            <div class="info-box mb-3 bg-warning">
              <span class="info-box-icon"><i class="fas fa-tag"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Inventory</span>
                <span class="info-box-number">5,200</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-success">
              <span class="info-box-icon"><i class="far fa-heart"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Mentions</span>
                <span class="info-box-number">92,050</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-danger">
              <span class="info-box-icon"><i class="fas fa-cloud-download-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Downloads</span>
                <span class="info-box-number">114,381</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
            <div class="info-box mb-3 bg-info">
              <span class="info-box-icon"><i class="far fa-comment"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Direct Messages</span>
                <span class="info-box-number">163,921</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
</div>
             
        <!-- /.box-body -->
       



             </div> 








       
		</form> </div>
        <!-- /.col --> 
  </div>
      <!-- /.row --> 
   <!-- /.box-body --> 
          </div>
          <!-- /.box --> 

           <style>.overlay {
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
                  
                  <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...-->
              </span> 
          </div>
         


    </section>
    <!-- /.content --> 
  </div>
  <script type="text/javascript">
	 $("[name=CharSummarytoggler]").click(function(){
            $('.toHideCharSummarytoggler').hide();
            $('.toHideCharSummarytogglerContent').hide();
            if($(this).val()=='CustomRangeBookingPeriod2'){
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
               document.getElementById("rdb22").className = "btn btn-foursquare col-md-3"; 
               $("#blk-CustomRangeBookingPeriod2").show();
               /* $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod3").show();
                $('#performanceChart').hide();
                $("#blk-CompareRangeBookingPeriod2").hide();
                $("#blk-CompareRangeBookingPeriod3").hide(); 
               $( "#SelectedViewType" ).val('0'); //TableView
              
               document.getElementById("rdb23").className = "btn btn-default col-md-3";
               document.getElementById("rdb22").className = "btn btn-foursquare col-md-3";
               
document.getElementById("rdb17").className = "btn bg-default margin btn-foursquare mobileSummary-today";
document.getElementById("rdb12").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb14").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb15").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb11").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb18").className = "btn bg-default margin mobileSummary-today";
//document.getElementById("rdb19").className = "btn bg-default margin mobileSummary-today";
document.getElementById("rdb120").className = "btn bg-default margin mobileSummary-today";

*/


			$( "#SelectedSummaryViewType" ).val('7');
                var viewMonthwise = $("#SelectedMonthView").val();
                var summaryReportType = $("#SelectedSummaryViewType").val();
                fetchPerformanceSummaryData('0','7');
               
               
            }else if($(this).val()=='CompareRangeBookingPeriod2'){
                 
                $("#blk-"+$(this).val()).show();
                $("#blk-CustomRangeBookingPeriod2").hide();
                $("#blk-CustomRangeBookingPeriod3").hide(); //Data List Table 
                $("#blk-CompareRangeBookingPeriod3").show(); 
                $('#performanceChart').hide();
                $( "#SelectedViewType" ).val('2'); //TableView
                 var summaryReportType = $("#SelectedCompareType").val();
                fetchCompareReportData('2','1');
                

           
               document.getElementById("rdb21").className = "btn btn-default col-md-3";
               document.getElementById("rdb22").className = "btn btn-default col-md-3";
               document.getElementById("rdb23").className = "btn btn-foursquare col-md-3";
            }else{
               
              var SelectedMonthView = $("#SelectedMonthView").val();
              fetchPerformanceGraphData(SelectedMonthView);
              $('#performanceChart').show(); 
              $("#blk-CustomRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod2").hide();
              $("#blk-CompareRangeBookingPeriod3").hide(); 
              $( "#SelectedViewType" ).val('1'); //ChartView'
              
              document.getElementById("rdb22").className = "btn btn-default col-md-3";
              
              document.getElementById("rdb21").className = "btn btn-foursquare col-md-3";
              //alert($(this).val()); 
              
            }
            
    });
     
     
 

function updateDateQuickSearchCompare(ComparePeriod){
   
    
    
    
    var res = ComparePeriod.split("-");
    var FinanceComparePeriodStarYear = res[0];
    var FinanceComparePeriodEndYear = res[1];
    let ComparePeriodquickDate = "01-04-"+FinanceComparePeriodStarYear+" to "+"31-03-"+FinanceComparePeriodEndYear;
    
    $("#SelectedComparePeriodDate").val(ComparePeriodquickDate);
	    
    let quickDate = $("#per_report_date").val();
    updateDateQuickSearch(quickDate,1);
    
}
function getfinancialyear(year,Currentfinancialyear){
	    var year = $("#financialyearselected").val();
	    //alert(Currentfinancialyear);
	    //alert(year);
	    
	   
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
 function fetchPerformanceSummaryData(viewMonthwise,summaryReportType){
     
     $("#loading").show();
     //alert('2');
  $("#loading").hide();

 }


  function mainGroup(sel){
		
		
	
	var opts = [],opt;	
	var len = sel.options.length;
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
  	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'id_main_group='+opts+'&group=1', 
		success: function (result) {
				$( "#id_sub_group" ).html(result);
				$( "#id_data_main_group" ).val(opts);
				
	 	}
	});
		
		}
function subGroup(sel){
		
	var id_data_main_group=$("#id_data_main_group").val();	
	
	var opts = [],opt;	
	var len = sel.options.length;
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
  	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'id_sub_group='+opts+'&group=2&id_data_main_group='+id_data_main_group, 
		success: function (result) {
				$( "#id_item" ).html(result);
				
	 	}
	});
		
		}		
	function consolidateItemreport(){
		
		var datefilter=$("#datefilter").val();
		alert(datefilter);
		$.ajax({
		type: "POST",
		url: 'ajax/ajaxconsolidateItemreport.php',
		data: 'datefilter='+datefilter+'&group=2', 
		success: function (result) {
				alert(result);
	 	}
	});
		
		}	
	</script>
  <?php include_once("../includes/footer.php")?>
	<script>
  function fetchPerformanceGraphData(viewMonthwise){
        $("#loading").show();
				//alert('1');	
				

					//graphCount++;
                    $("#loading").hide();
    }
window.onload = function() {
    //getfinancialyear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
    //getCompareYear('<?php echo $Current_financial_year;?>','<?php echo $Current_financial_year;?>');
    // $('.toHideWholeDiv').hide();
        fetchPerformanceGraphData(1);
}
	</script>
