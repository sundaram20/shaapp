<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
?>
<?php include_once("../includes/header.php")?>
  <?php include_once("../includes/left.php")?>
  <?php 
  include_once("include/functionDemo.php");
  if($_REQUEST['Download'] == 'Generate'){
	$Date =$_REQUEST['datefilter'];
	$id_outlet=implode(',',$_REQUEST['id_outlet']);
	$id_shift=implode(',',$_REQUEST['id_shift']);

	 posSalesRegisterReport($Date,$id_outlet,$id_shift,$objPHPExcel);
	 
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
        <!---->
        <div class="row">
          <div class="col-md-6">
     <!-- <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h6>-->
      <div class="row">
       <div class="form-group col-xs-3 col-md-2 col-sm-2 c-box">


        <!--<div class="btn-group " > <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class=" fa-solid fa-code-compare"></i> View</a>
    <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false"> 
    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </button>
    <ul class="dropdown-menu " role="menu">
      <li><a title="Compare LY "  href="javascript:void(0)">LY</a></li>
      <li><a title="Compare LLY " href="javascript:void(0)">LLY</a></li>
        <li><a title="Compare LY and LLY"  href="javascript:void(0)">LY and LLY</a></li>
    </ul>
  </div>-->
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
            <div class="col-md-2">
              <div class="form-group">
               <label>Period</label>	 
	          			
              
	          		
							<!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
							<input type="text" class="form-control pull-right dateRangeReport" placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php echo date('d-m-Y').' to '.date('d-m-Y') ?>" data-parsley-errors-container="#report_dateError"  autocomplete="off">
						              </div>
              <!-- /.form-group -->
 
              
            </div>
            
              <div class="col-md-2">                                
             <div class="form-group">
                <label>Select Outlet <span class="text-danger">*</span></label>
						
								 <select class="form-control select2" name="id_outlet[]" data-parsley-required id="id_outlet" multiple="multiple" style="width:100%">	
								 <?php
                                $resCat = selectSql(mst_outlets,"where id_shop='".$_SESSION['shop']."'  and status = '1' ",'');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';			

		
									}?>
								</select>
								 <?php }?>
							                             
                </div>
            </div>                             
            <!-- /.col -->
            
            <div class="col-md-2">
              <div class="form-group">
                <label>Select Shift <span class="text-danger">*</span></label>
						
                                <div id="ListSubGroup">
								 <select class="form-control select2" name="id_shift[]" data-parsley-required id="id_shift" multiple="multiple" style="width:100%">	
								 <?php
                                $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1'  AND table_name ='".'shift'."'  ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

									  

								  	while($resultCat = $db->fetch_object2($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';			

		
									}?>
</select>
								  <?php }?>
                                  </div>
							                           
                </div>              
              <!-- /.form-group -->
            </div>
            </div>
             
          <!-- /.row -->
                <label class="mb-4"><span class="text-danger">*</span>leave blank for all</label><br>

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
