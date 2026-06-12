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


//print_r($_REQUEST);//die;
consolidatedItemWiseReport($Date,$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type']);  
	 
  }?>
  <style>
  .ranges{padding: 9px !important;}
 .daterangepicker .ranges li:hover {background-color:#08c !important;}
  </style>
  <div class="content-wrapper"> 
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"> <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>
        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?> </section>
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
            <div class="col-md-4">
              <div class="form-group">
               <label>From Date & To Date</label>	 
	          			
              
	          			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>  
							<!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
							<input type="text" class="form-control pull-right dateRangeReport" placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="" data-parsley-errors-container="#report_dateError"  autocomplete="off">
						</div>
              </div>
              <!-- /.form-group -->
 
              
            </div>
            <div class="col-md-4">                                
             <div class="form-group">
                <label>Select Report</label>
							<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-dot-circle-o"></i> 
							   	</div> 
								 <select class="form-control select2" name="id_report_type" data-parsley-required id="id_report_type" style="width:100%">	
								<option  value="1">Item Wise Report</option>
                                <option  value="2">Subgroup Wise Report</option>
                                
	</select> 
							</div>                              
                </div>
            </div>
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

								  if(mysqli_num_rows($resCat)){

									  

								  	while($resultCat = mysqli_fetch_object($resCat)){

							echo $hotelDropDown = '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';			

		
									}?>
	</select>
								 <?php }?>
							</div>                              
                </div>
            </div>                             
            <!-- /.col -->
            
            
            </div>
             <div class="row">
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
								 <select class="form-control select2" name="id_item[]" data-parsley-required id="id_item" multiple="multiple" style="width:100%"/>	
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
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        
        <div class="box-footer">
        	<center>
            <input name="Download" type="submit" class="btn btn-primary" value="Generate" />
		        <input name="clear" id="clear" type="button" class="btn btn-danger" value="Reset Form" onclick="fomrdata_clear();" />
		    </center>
        </div>
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
