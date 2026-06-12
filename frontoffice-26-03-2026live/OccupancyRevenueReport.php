<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php 
	  include_once("functions/functionsOccupancyStatisticsReport.php");
	
 
 if($_REQUEST['dwn_availabilty'] == 'Generate'){
	$shop=$_SESSION['shop'];
	 	echo '==========='.$Date =$_REQUEST['datefilter'];
	$id_outlet=implode(',',$_REQUEST['id_outlet']);
	$id_shift=implode(',',$_REQUEST['id_shift']);

	 OccupancyStatisticsReport($Date,$id_report_type,$report_show,$showItemReport,$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel);
 }
?>
    
    <?php
	
?> 
  
 
    
    <?php 
		
			$DispalyClass="display:none;";
			$viewClass="";
			$viewIcons='fa fa-plus-square-o fa-1x';
		
			$DispalyClass="";
		//	$viewClass='fieldset';
			$viewIcons='fa fa-minus-square-o fa-1x';
		
		
		
	$subMenuCond = " AND `id_module` = '8'";
 
	?>
    <style>
.ranges {
	padding: 9px !important;
}
.daterangepicker .ranges li:hover {
	background-color: #08c !important;
}
</style>
<div class="content-wrapper">
<!-- Content Header (Page header) -->

<?php  $session=$_GET['submenu']; ?>
<section class="content-header">
     <div class="row">
          <div class="col-md-6">
  <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"> <?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
    <?php //echo currentNavigation()['submenu']; ?>
  </h3>-->
     <div class="row">
       <div class="form-group col-xs-3 col-md-2 col-sm-2 c-box">
<a type="button" class="btn c-btn"  href="javascript:void(0)"><i class="fa fa-fw fa-print"></i> Print</a>
</div>
<div class="form-group col-xs-9 col-sm-3 col-md-10 mb-0 ">
<div class="btn-group " style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
    <button type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
     <ul class="dropdown-menu " role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
       <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fas fa-file-image"></i>&nbsp;JPG</a></li>
    </ul>
  </div>

<div class="btn-group s-btt"  > <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Share</a>
    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><i class="fas fa-envelope-open-text"></i>&nbsp;Email</a></li>
      <li><a title="Share on Whatsapp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><i class="fab fa-whatsapp"></i>&nbsp;Whatsapp</a></li>
    </ul>
  </div>
  </div>
    </div>
  </div>
  
  
       <div class="col-md-6 col-xs-12">
      <?php echo breadCrumbs(); ?>
     </div>
   </div>
 
   </section>
<section class="content ">
  <div class="row">
 
<div class="col-xs-12">
<!-- /.box -->
<div class="box">
<div class="box-header"> <small class="text-center has-error">
  <?php if($_SESSION['errorMsg']){?>
  <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
  <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
  <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
  <?php unset($_SESSION['successMsg']);}?>
  </small> </div>
<form name="searchForm" action="" method="get">
<input type="hidden" value="1" name="searchFormSubmit" />
<input type="hidden" value="1" name="report_show" id="report_show" />
<div class="box-body">
<div class="row">

<div class="col-md-2 col-sm-12">
  <div class="form-group">
    <label>Method</label>
   <?php
      
	      ?>
   <!--<div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>-->
      <select class="form-control select2 parsley-error"  name="id_report_type" data-parsley-required id="id_report_type" style="width:100%">
       
        <?php
           /* $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE 1=1 and status='1' and type='2'  ".$subMenuCond."   order by display_order";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
              $moduleName = selectField(APP_MODULE,'name','WHERE id="'.$rowSubMenu->id_module.'"',$appConnect); 
				if($_REQUEST['submenu'] == $rowSubMenu->id){				
					$selected = 'selected="selected"';			
				}else{				
					$selected = '';				
				}*/
           ?>
        <option <?php echo $selected ?> value="1">Occupancy Report</option>
        
        
        <?php // } ?>
      </select>
      <span style="margin-left:45px;color:red;font-size:11px;" id="id_report_type_error"></span>
  </div>
</div>
<div class="col-md-2 col-sm-12">
  <div class="form-group">
    <label> Period</label>
  
      <!--<div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>-->
      <!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
      <input type="text" class="form-control pull-right dateRangeReport" placeholder="Select From -  To" name="datefilter" id="per_report_date" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">
  
  </div>
  <!-- /.form-group --> 
</div>

     


<!---Filter Star---------------------------------------------------------------------------->

<div class="col-md-12 col-sm-12">
<div  id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne" id="fieldset" >
<!--<p id="showheadelion" class="text-o"><span id="textReportShow" style="font-size:15px;">Filter </span></p>-->
<div  id="moreReportDiv" style="padding-left:0px;<?php echo $DispalyClass; ?>">
<div class="box-header" style="padding:5px">
  <div class="box-tools pull-right"> 
    <!--<button type="button" class="btn btn-box-tool" id="btnMoreReportDiv">X
		                    			</button>--> 
  </div>
</div>
<div class="box-body pt-0 pl-0">


  <label><span class="text-danger">*</span><b> leave blank for all</b></label>

       </div>					
      		        				</div>
      		        			</div>








		        		</div>
		        	



	
<!---Filter End----------------------------------------------------------------------------->  
             
             
<!---Compare Start----------------------------------------------------------------------------->  
<?php /*?><div class="col-md-3 col-sm-3">
<div  id="fieldset" class="<?php echo $viewClass; ?>">
<p id="showheadelion" class="text-primary"><span id="textReportShow" style="font-size:15px;">Compare </span></p>
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

<div class="col-md-6">
  <div class="form-group">
    <label>Select Compare</label>
    <div class="input-group">
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
      <select class="form-control select2" name="id_compare" data-parsley-required id="id_main_group"  style="width:100%">
        <option value=""> --Select Compare--</option>
        <option value="1">LY</option>
	    <option value="2">LLY</option>
        <option value="3">LY and LLY</option>
        </select>
    </div>
  </div>
</div>



<!----Row-->

	    	 </div>



            </div>					
		        				</div>
		        			</div>








		        		</div><?php */?>
<!---Compare End----------------------------------------------------------------------------->               
            
                                          
            <!-- /.col ajaxPosReportOrderBy.php-->
            
            
         
             
            
            <!-- /.col -->
          </div>
          <!-- /.row -->

        <div class="box-footer pt-0 pl-0">
          <input type="hidden" name="showItemReport" id="showItemReport" value="0">
         
            <input name="dwn_availabilty" type="submit" class="btn o-btn" value="Generate" style="margin-top:25px;" />
            <!--<input name="clear" id="clear" type="button" class="btn btn-danger" value="Reset Form" onclick="fomrdata_clear();" />-->
       
        </div>
     </div>
        <!-- /.box-body -->
    
        
        
        
        

  
  
		</form>
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
                  
                  <!--<img src="../images/ajax-loader1.gif">Loading Please Wait...--></span> </div>
      
      
  
           <div class="box-body table-responsive"> <div class="row">
              <div id="ShowResultContent" style="padding:0px 10px 0px 10px;"> </div>
            </div> 
           
           
            <!-- /.box-body --> 
          </div>
          <!-- /.box --> 
          
          
        </div>
        <!-- /.col --> 
        
      </div>
      
      <!-- /.row --> 
    </section>
   
     <!-- /.content --> 
  </div>
  <script type="text/javascript">
	
function getReportOrderBy(id_order_by){	

		$.ajax({

			   type: "GET",

			   url: 'ajax/ajaxPosReportOrderBy.php',

			   data: 'id_order_by='+id_order_by, 

			   success: function (result) {				   

			     $('#id_order_by').empty();

				 $('#id_order_by').html(result);

				 

				}

		});

}


//Form Data Clear
	downloadExcelPdf = (ReportShowType) => {
    
        var period = $("#per_report_date").val();
		var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_item = $("#id_item").val();
    var id_order_by = $("#id_order_by").val();
	var showItemReport = $("#showItemReport").val();
	
    
        let url2 = 'ajax/ajaxDateWiseReports.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
         window.open(url2);
	
        
   }
   
   function loadSalesReport(ReportShowType){
   
	 //id_report_type,id_main_group,id_data_main_group,id_item
	 
	 var id_report_type = $("#id_report_type").val();
	  var id_order_by = $("#id_order_by").val();
	 if(id_report_type==''){
		 document.getElementById('id_report_type_error').innerHTML = 'Please Select Report Type';
		 return false;
		 }
		 document.getElementById('id_report_type_error').style.display = "none";
	/*if(id_order_by==''){
		 document.getElementById('id_order_by_error').innerHTML = 'Please Select Order By';
		 return false;
		 }	 
		  
		   document.getElementById('id_order_by_error').style.display = "none";*/
	// alert(id_report_type);
	 $("#showPrintExplode").hide();
     $("#loading").show();
	  var id_order_by = $("#id_order_by").val();
	 	var period = $("#per_report_date").val();
		//var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_data_main_group = $("#id_data_main_group").val();
		var showItemReport = $("#showItemReport").val();
		var id_item = $("#id_item").val();
		if(id_report_type=='1'){
		 reportTypeFile  ='ajaxDateWiseReports.php';
		}else{
			 reportTypeFile  ='ajaxAvailabilityReports.php';
		}
		 $.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_data_main_group='+id_data_main_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&id_sub_group='+id_sub_group+'&showItemReport='+showItemReport,
				success:function(data){
					$("#ShowResultContent").html(data);
                    
                    $("#loading").hide();
                   $("#showPrintExplode").show(); 
					
				}
			})
			
			
			//let reportType = $("#reportType").val();
       /* //$("#SummaryDataloading").show(); 
		
		//let reportType =  $('input[name="reportType"]:checked').val();
		var reportType = $("#SelectedreportType").val();
		let id_mst_hotels = $("#id_mst_hotels").val();
		
		var id_group_sun_master = $("#id_group_master").val();
		var groupsMenu = id_group_sun_master.split("_");
        var id_group_master = groupsMenu[0];
        var id_group_sub_master = groupsMenu[1];
		
	    let ComparePeriodDate = $("#SelectedComparePeriodDate").val();
	    
        if(summaryReportType==4){
            reportTypeFile  ='DashboardCompareAgentTop.php';
            
        }else if(summaryReportType==61){
            reportTypeFile  ='DashboardCompareAgentDropOut.php';
        }else if(summaryReportType==62){
            reportTypeFile  ='DashboardMtdReport.php';
        }else if(summaryReportType==5){
            reportTypeFile  ='DashboardMtdYtdReport.php';
        }else{
            reportTypeFile  ='DashboardCompareView.php';
        }
        
			$.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_mst_hotels='+id_mst_hotels+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&ComparePeriodDate='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
				success:function(data){
					$("#ShowCompareReportData").html(data);
                    
                    $("#loading").hide();
                    
					
				}
			})*/
	
        

	}
	
	function fomrdata_clear(){
		location.reload();	
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
