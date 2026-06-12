<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
  <?php 
  include_once("functions/functionBookingReport.php");
  if($_REQUEST['Download'] == 'Generate'){
	// 1. Collect input from request safely
$reservationNo  = trim($_REQUEST['search_name'] ?? '');
$otherRefNo     = trim($_REQUEST['other_reference_no'] ?? '');
$companyId      = $_REQUEST['id_mst_company_new'] ?? '';
$checkinRadio   = $_REQUEST['checkin_radio'] ?? '2';
$bookingDate    = $_REQUEST['datefilter'] ?? date('d-m-Y') . ' to ' . date('d-m-Y');
$reservation_checkindate    = $_REQUEST['reservation_checkindate'] ?? date('d-m-Y') . ' to ' . date('d-m-Y');
$bookingStatus  = $_REQUEST['booking_status'] ?? [];
$asOnDate       = $_REQUEST['datefilterNightAudit'] ?? date('d-m-Y');

// 2. Prepare other variables for the function
//$appConnect      = true;                          // example flag
$connNew         = $connNew;             // your DB connection
             // set appropriately
$cronSet         = false;                        // example: whether running via cron
$pdfNameReport3  = 'report_' . date('Ymd') . '.pdf';
//$objPHPExcel     = new PHPExcel();               // your PHPExcel object

// 3. Call the function with all necessary inputs
DateWiseReports(
           // $Date
    $reservation_checkindate,
	$bookingDate,
    $companyId,         // $id_report_type
    $checkinRadio,      // $report_show
    $bookingStatus,     // $showItemReport
    $otherRefNo,        // $kot_nc
    $reservationNo,
    $appConnect,        // $appConnect
    $connNew,           // $connNew
    $_SESSION['shop'],              // $shop
    $cronSet,           // $cronSet
    $pdfNameReport3,    // $pdfNameReport3
    $objPHPExcel        // $objPHPExcel
); 
  }?>
    <style>
        .breadcrumb{
        margin-bottom:0;
      }
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
  </style>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
	
      	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
    <div class="row">
          <div class="col-md-6">
      <!--<h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h6>-->
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
  
  
       <div class="col-md- pull-right">
      <?php echo breadCrumbs(); ?>
     </div>
   </div>
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


<div class="row">
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Reservation No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Other Reference No</label>
                  <input type="text" name="other_reference_no" id="other_reference_no" value="<?php echo trim($_REQUEST['other_reference_no']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
                <div class="form-group col-sm-2">
                        <label for="checkin" style="float:left;">Company</label>
                        <select class="form-control select2" name="id_mst_company_new" id="id_mst_company_new">
                            <option value="">Select Company</option>
                            <?php
                                $categoryDropDown = '';
                                $SQL = "select *  from ".TBL_COMPANY." where status ='1' and name != ''";
                                $query = mysqli_query($connNew, $SQL);
                                while ($resultCat = mysqli_fetch_assoc($query)) {
                                    if ($row->id_mst_company == $resultCat['id']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'. $resultCat['name'].'- '. $resultCat['city'].'</option>';
                                }
                                echo $categoryDropDown;
                            ?>
                        </select>
                        <p class="error id_mst_company_new-error"></p>
                    </div>
              <!-- /.col -->
              <!--col start-->
	
	 <div class="form-group col-sm-2">
                    <label for="reservation_date"><input type="radio" name="checkin_radio" value="1" <?php if($_REQUEST['checkin_radio']=='1'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='2'  ){}?>/>&nbsp;Checkin Date : From - To </label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                      <input type="text" class="form-control pull-right appdaterange" id="reservation_checkindate" placeholder="Enter Checkin date" name="reservation_checkindate"  value="<?php if(isset($_REQUEST['reservation_checkindate'])){ echo $_REQUEST['reservation_checkindate'];}else{ echo date('d-m-Y').' to '.date('d-m-Y');}?>"   automcomplete="off">
					   
                    </div>
                    <!-- /.input group -->
                    <span id="reservation_dateError"></span> </div>
                  
	
                <div class="form-group col-sm-2">
                       <?php //debugData($_REQUEST); ?>
                           <label for="booking_date"><input type="radio" name="checkin_radio" value="2" <?php if($_REQUEST['checkin_radio']=='2'){echo 'checked="checked"'; }else if(isset($_REQUEST['checkin_radio']) && $_REQUEST['checkin_radio']=='1'  ){}else{echo 'checked="checked"';}?>/>&nbsp;Booking Date : From - To</label>
                                <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                          <input type="text" class="form-control pull-right"  placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo date('d-m-Y').' to '.date('d-m-Y'); }?>"   autocomplete="off">
                        </div>
                    </div>
				
                  
          <div class="form-group col-md-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Booking Status</label>
                 <select class="form-control" name="booking_status[]" multiple="multiple" id="bookOpStatus" >
                        <?php $categoryDropDown='';
							//$categoryDropDown = '<option value="">Select Booking Status</option>';
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
               

             
          <!-- /.row -->
            <div class="box-footer pt-0 pl-0">
	         
	            <input name="Download" type="submit" class="btn c-btn" value="Generate" />
			        <input name="clear" id="clear" type="button" class="btn o-btn" value="Reset Form" onclick="fomrdata_clear();" />
			  
	        </div>
        </div>
        <!-- /.box-body -->
        
      
		</form>
            
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
