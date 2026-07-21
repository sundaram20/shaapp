<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php 
 // include_once("include/function.php");
  if($_REQUEST['Download'] == 'Generate'){
	$Date =$_REQUEST['datefilter'];

$type=$_POST['type'];

$id_main_group=implode(',',$_REQUEST['id_main_group']);
$id_sub_group=implode(',',$_REQUEST['id_sub_group']);
$id_items=implode(',',$_REQUEST['id_item']);

$report_show	= $_REQUEST['report_show'];
//print_r($_REQUEST);//die;
//$ShowResultContent	=	consolidatedItemWiseReport($Date,$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type'],$report_show);  
	 
  }?>
    <style type="text/css">
      /*--more btn css*/
          @import url("https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css");
   .panel-title{
    font-size:14px;
   }
   .panel-heading{
    border-color: #f56616!important;
    padding:8px 14px;
   }
    .panel-title>a:before {
      float: left !important;
      font-family: FontAwesome;
      content: "\f068";
      padding-right: 5px;
    }

    .panel-title>a.collapsed:before {
      float: left !important;
      content: "\f067";
    }

    .panel-title>a:hover,
    .panel-title>a:active,
    .panel-title>a:focus {
      text-decoration: none;
    }
      .breadcrumb{
        margin-bottom:0;
      }
    /*more btn css ends*/
.fieldset {
	border: 2px groove #f56616;
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
	border-top: 3px solid #f56616;
	content: ' ';
	float: left;
	margin: 0.5em 2px 0 -1px;
	width: 0.75em;
}
.fieldset>p:after {
	border-top: 3px solid #f56616;
	content: ' ';
	display: block;
	height: 0.5em;
	left: 2px;
	margin: 0 1px 0 0;
	overflow: hidden;
	position: relative;
	top: 0.5em;
}
.text {
	font-size: 20px;
}
</style>
    <?php 
		
			$DispalyClass="display:none;";
			$viewClass="";
			$viewIcons='fa fa-plus-square-o fa-1x';
		
			$DispalyClass="";
		//	$viewClass='fieldset';
			$viewIcons='fa fa-minus-square-o fa-1x';
		//}
		
		
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
        <option value="">---Select Report Type---</option>
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
        <option <?php echo $selected ?> value="1">Checkout Report</option>
      
        
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

     <div class="col-md-1">
        <label>More</label>
        <div class="panel-group form-group"  title="More" style="width:46px;" id="accordion" role="tablist" aria-multiselectable="true">
          <div class="panel panel-default" style="height:35px;">
            <div class="panel-heading" style="display: inline-block;" role="tab" id="headingOne">
              <h4 class="panel-title">
                <a class="collapsed"  data-toggle="collapse"  data-parent="#accordion" href="#collapseOne" aria-expanded="false"
                  aria-controls="collapseOne">
                
                </a>
              </h4>

            </div>
           
            </div>
          </div>

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
      		        				
           <div class="form-group col-md-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Booking Status</label>
                    <select class="form-control select2" style="width: 100%;" id="res_bookingStatus_new" name="res_bookingStatus_new" ata-parsley-required-message="Please insert your name">
                        <?php
							$categoryDropDown = '<option value="">Select Booking Status</option>';
							$resCat = selectSql('fo_booking_status'," where status='1'",'');
							if ($db->num_rows2($resCat)) {
								while($resultCat = $db->fetch_object2($resCat)) {
                                    if($_REQUEST['booking_status'] == $resultCat->id) {
                                        $selected = 'selected="selected"';
                                    } elseif($row->booking_status == $resultCat->id) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
									$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                }
			                }
							echo $categoryDropDown ;
						?>
                    </select>
                    <p class="error res_bookingStatus_new-error"></p>
                </div>
                
                
                
                
                
              <div class="form-group col-md-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Front Office Status</label>
                    <select class="form-control select2" style="width: 100%;" id="hk_status" name="hk_status" ata-parsley-required-message="Please insert your name">
                        
							<option value="">Select Front Office Status</option>
                            <option value="6">Occuiped</option>
							<option value="1">Checkin/Occuiped</option>
                            <option value="2">Checkout/Vacant</option>
                            <option value="3">Checkin/Checkout</option>
                            <option value="4">No showoff</option>
                            <option value="5">Pending</option>
                            <!--<option value="6">-</option>-->
                    </select>
                    <p class="error res_bookingStatus_new-error"></p>
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
            <input name="Download" type="button" class="btn o-btn" value="Apply" onclick="loadSalesReport(1);" />
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
	
    
        let url2 = 'ajax/ajaxDateWiseCheckoutReports.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&showItemReport='+showItemReport;
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
		var res_bookingStatus_new = $("#res_bookingStatus_new").val();
		var hk_status = $("#hk_status").val();
		
		if(id_report_type=='1'){
		 reportTypeFile  ='ajaxDateWiseCheckoutReports.php';
		}else{
			 reportTypeFile  ='ajaxAvailabilityReports.php';
		}
		 $.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_data_main_group='+id_data_main_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType+'&id_order_by='+id_order_by+'&id_sub_group='+id_sub_group+'&showItemReport='+showItemReport+'&res_bookingStatus_new='+res_bookingStatus_new+'&hk_status='+hk_status,
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
		let id_hotel = $("#id_hotel").val();
		
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
				data:'period='+period+'&id_hotel='+id_hotel+'&id_group_master='+id_group_master+'&reportType='+reportType+'&viewMonthwise='+viewMonthwise+'&summaryReportType='+summaryReportType+'&ComparePeriodDate='+ComparePeriodDate+'&CompareFinancialYear='+CompareFinancialYear+'&CurrentFinancialYear='+CurrentFinancialYear+'&id_group_sub_master='+id_group_sub_master,
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
