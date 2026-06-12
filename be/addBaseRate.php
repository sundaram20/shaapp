<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],'','add');
?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
   <!-- <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage Base Rate Inventory</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Manage Base Rate Inventory</li>
      </ol>
    </section> -->
    <!-- Main content -->
    <section class="content">		
	<div class="box box-success">
	 <div class="form-group has-error" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
		
		<div class="box-header with-border">
              <h3 class="box-title"> Add <?php echo currentNavigation()['submenu']; ?> 
			  
            </div>
        
        <!-- /.box-header -->
		<form name="addForm" id="addForm" action="" method="post">
          <input type="hidden" >  
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
        <label for="hotel">Hotel<font color="#FF0000">*</font></label>
        	<?php $categoryDropDown = '<select class="form-control select2" onchange="fetchRoomPlanLink(this.value,\'id_link\');"  required name="id_hotel" id="id_hotel">
				<option value="">Select Hotel</option>';
				$resHotel = selectSql(TBL_HOTELS," WHERE `status` = '1' AND id_shop='".$_SESSION['shop']."' ",' ORDER BY `name`');
				if($db->num_rows2($resHotel)){
				  	while($rowHotel = $db->fetch_object2($resHotel)){
						if($row->id_hotel == $rowHotel->id){
							$selected = 'selected="selected"';
						}else{
							$selected = '';
						}
						$categoryDropDown .= '<option '.$selected.' value="'.$rowHotel->id.'">'.ucfirst($rowHotel->name).'</option>';
					}
				}
				echo $categoryDropDown .= '</select>';
			?>
        
         </div>
            </div>

            <!---- Rooms-plan-link -->
            <div class="col-md-4">
                <div class="form-group">
              <label for="link_id">Room-Plan-Link</label>

			  <select class="form-control select2" required name="id_link" id="id_link">
                             
              </select>     
         	</div>
            </div>
            <!-- from to date -->
            
            <div class="form-group col-sm-4">
                <label for="effective_date">Valid From - Valid Till </label>
                <div class="input-group">
                    <div class="input-group-addon">
                     <i class="fa fa-calendar"></i> 
                    </div>
                    <input  type="text" class="form-control pull-right dateRangeFree" id="effective_date" name="effective_date" data-parsley-required value="" data-parsley-errors-container="#effective_dateError"  autocomplete="off" >
                </div>
                    <!-- /.input group -->
                <span id="effective_dateError"></span>
            </div>
            <hr width="100%">
            <!--add grid-->
            <div class="form-group col-sm-3">
            	<label for="single">Single </label>
            	<input placeholder="Enter Price" class="form-control" type="text" required="" value="" name="single_pax_price">
            </div>

            <div class="form-group col-sm-3">
            	<label for="single">Double </label>
            	<input placeholder="Enter Price" class="form-control" type="text" required="" value="" name="double_pax_price">
            </div>

            <div class="form-group col-sm-3">
            	<label for="single">Extra Bed </label>
            	<input placeholder="Enter Price" class="form-control" type="text" required="" value="" name="extra_bed_price">
            </div>

            <div class="form-group col-sm-3">
            	<label for="single">Extra Child </label>
            	<input placeholder="Enter Price" class="form-control" type="text" required="" value="" name="extra_child_price">
            </div>

            <div class="form-group col-sm-3">
            	<label for="note">Note: All fields are required </label>
            	
            </div>
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="addBaseRate" id="addBaseRate" type="submit" class="btn btn-primary" value="Add" />
        <a href="manageBaseRate.php" class="btn btn-warning">Cancel</a>
        <span id="loadMe" style="display: none;color: red;" >&nbsp; &nbsp;Wait Updating...</span>
        </div>
		</form>		
      
    </section>
    
    <!-- /.content -->
  </div>
 <script type="text/javascript">
     var dayExtend=0;
 </script>                                  
<?php include_once("../includes/footer.php")?>  

<script type="text/javascript">
	$(document).ready(function(){
		$("#addForm").submit(function(e){
        	e.preventDefault();
        	var formData = $("#addForm").serialize();
        	$.ajax({
        		type: "POST",
        	    url: 'ajax/ajaxAddBaseRate.php',
        	    data: formData,
        	    success: function(data){
        	      	alert(data);	
        	       	window.location.href="manageBaseRate.php";
        	    },
	       	});
        	
    	});
	});

	$(document).ajaxStart(function(){
			$('#loadMe').show();
	});

	$(document).ajaxComplete(function(){
		$('#loadMe').hide();
	});

    function fetchGrid(){
        return;
    }
</script>