<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php 
  include_once("include/function.php");
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
.text {
	font-size: 20px;
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
  <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"> <?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
    <?php //echo currentNavigation()['submenu']; ?>
  </h3>
  <?php echo breadCrumbs(); ?> </section>
<section class="content">
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
<div class="col-md-3">
  <div class="form-group">
    <label>Report Type</label>
    <div class="input-group">
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
      <select class="form-control select2" name="id_report_type" data-parsley-required id="id_report_type" style="width:100%">
        <option value="">---Select Report Type---</option>
        <?php
            $sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE 1=1 and status='1' and type='2'  ".$subMenuCond."   order by display_order";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            while($rowSubMenu = mysqli_fetch_object($resSubMenu)){
              $moduleName = selectField(APP_MODULE,'name','WHERE id="'.$rowSubMenu->id_module.'"',$appConnect); 
				if($_REQUEST['submenu'] == $rowSubMenu->id){				
					$selected = 'selected="selected"';			
				}else{				
					$selected = '';				
				}
           ?>
        <option <?php echo $selected ?>value="<?php echo $rowSubMenu->id; ?>"><?php echo ucwords(strtolower($rowSubMenu->name)) ; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
</div>
<div class="col-md-3">
  <div class="form-group">
    <label> Period</label>
    <div class="input-group">
      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
      <!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
      <input type="text" class="form-control pull-right dateRangeReport" placeholder="Select From -  To" name="datefilter" id="per_report_date" data-parsley-required value="" data-parsley-errors-container="#report_dateError"  autocomplete="off">
    </div>
  </div>
  <!-- /.form-group --> 
</div>
<div class="col-md-3">
  <div class="form-group">
    <label>Order By</label>
    <div class="input-group">
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
      <select class="form-control select2" name="id_order_by" data-parsley-required id="id_order_by" style="width:100%">
        <option  value="">---Select Order By---</option>
        <option  value="1">Name</option>
        <option  value="2">Qty</option>
        <option  value="3">Value</option>
      </select>
    </div>
  </div>
</div>
<div class="form-group col-md-3 pull-right ">
  <div style="width:100%;">
    <label>&nbsp;</label>
  </div>
  <div class="btn-group " > <a type="button" class="btn btn-success" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> </a>
    <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
    <ul class="dropdown-menu" role="menu">
      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
      <li><a title="Export to csv file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
    </ul>
  </div>
  &nbsp;&nbsp; <a type="button" class="btn btn-info"  href="javascript:void(0)"><i class="fa fa-fw fa-print"></i> Print</a> </div>

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
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
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
    <label>Select Sub Group(leave blank for all) </label>
    <div class="input-group">
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
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
      <div class="input-group-addon"> <i class="fa fa-dot-circle-o"></i> </div>
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
             
             
<!---Compare Start----------------------------------------------------------------------------->  
<div class="col-md-3 col-sm-3">
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








		        		</div>
<!---Compare End----------------------------------------------------------------------------->               
            
                                          
            <!-- /.col -->
            
            
         
             
            
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        
        <div class="box-footer">
        	
            <input name="Download" type="button" class="btn btn-primary" value="Generate" onclick="loadSalesReport(1);" />
		        <!--<input name="clear" id="clear" type="button" class="btn btn-danger" value="Reset Form" onclick="fomrdata_clear();" />-->
		   
        </div>
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
	//Form Data Clear
	downloadExcelPdf = (ReportShowType) => {
    
        var period = $("#per_report_date").val();
		var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_sub_group = $("#id_sub_group").val();
		var id_item = $("#id_item").val();
   
    
        let url2 = 'ajax/ajaxPosSalesReport.php?period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_sub_group='+id_sub_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType;
         window.open(url2);
	
        
   }
   
   function loadSalesReport(ReportShowType){
   
	 //id_report_type,id_main_group,id_data_main_group,id_item
     $("#loading").show();
	 	var period = $("#per_report_date").val();
		var id_report_type = $("#id_report_type").val();
		var id_main_group = $("#id_main_group").val();
		var id_data_main_group = $("#id_data_main_group").val();
		var id_item = $("#id_item").val();
		 reportTypeFile  ='ajaxPosSalesReport.php';
		 $.ajax({
				url:'ajax/'+reportTypeFile,
				type:'POST',
				data:'period='+period+'&id_report_type='+id_report_type+'&id_main_group='+id_main_group+'&id_data_main_group='+id_data_main_group+'&id_item='+id_item+'&ReportShowType='+ReportShowType,
				success:function(data){
					$("#ShowResultContent").html(data);
                    
                    $("#loading").hide();
                    
					
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
