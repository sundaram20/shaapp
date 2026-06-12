<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_INVENTORY,'add');
?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>

<?php 
    if($_REQUEST['eId']!=''){
        $editId  = encryptor(decrypt,$_REQUEST['eId']);
        $roomId  = encryptor(decrypt,$_REQUEST['id_room']);
        
        //echo "<pre>";
        $sqlEdit = "SELECT *,MAX(allocation_date) AS max_date  FROM ".TBL_BE_INVENTORY." WHERE id_hotel='".$editId."' AND id_room='".$roomId."' ";
        //echo "</pre>";
        $res = mysqli_query($connNew,$sqlEdit);
        $row = mysqli_fetch_object($res);

        $disabled = 'disabled="disabled"';
        $days = (strtotime(date('Y-m-d',strtotime($row->max_date)))-strtotime(date('Y-m-d')))/86400;
    } 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage  Inventory  </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Manage  Inventory</li>
      </ol>
    </section>
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
        
        <!-- /.box-header -->
		<form name="addForm" id="addForm" action="" method="post">
          <input type="hidden" >  
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
        <label for="hotel">Hotel</label>
        	<?php $categoryDropDown = '<select class="form-control select2" '.$disabled.' onchange="fetchRoomsForHotel(this.value,\'id_room\');"  required name="id_hotel" id="id_hotel">
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

            <!---- Rooms- -->
            <div class="col-md-3">
                <div class="form-group">
              <label for="room_id">Room</label>

			  <select <?php echo $disabled;?> class="form-control select2" required name="id_room" id="id_room">
                             
              </select>     
         	</div>
            </div>
            <!-- from to date -->
            <!-- from to date -->
            <div class="form-group col-sm-3">
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
            <!--<hr width="100%">-->
            <!--add grid-->
            <div class="form-group col-sm-3">
            	<label for="online">Online Inventory</label>
            	<input placeholder="Enter Value" class="form-control" type="text" required="" value="" name="online_inventory">
            </div>

            <!--<div class="form-group col-sm-3">
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
            </div>-->
            
            <div class="form-group col-sm-3">
                <label for="note">Stop Sell : &nbsp;</label>
                <label  for="note">Yes</label>&nbsp;<input value="0" class="flat-red" type="radio" name="status">
                <label for="note">No</label>&nbsp;<input value="1" checked="" class="flat-red" type="radio" name="status">
            </div>    

            <div class="form-group col-sm-3">
            	<label for="note">Note: All fields are required </label>
            	
            </div>

          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="addInventory" id="addInventory" type="submit" class="btn btn-primary" value="Add" />
        <a href="manageInventory.php" class="btn btn-warning">Cancel</a>
        <span id="loadMe" style="display: none;color: red;" >&nbsp; &nbsp;Wait Updating...</span>
        </div>
		</form>		
      
    </section>
    
    <!-- /.content -->
  </div>
 <script type="text/javascript">
     var dayExtend=<?php echo ($days !=''?$days:30); ?>;
 </script>                                  
<?php include_once("../includes/footer.php")?>  

<script type="text/javascript">
	$(document).ready(function(){
		$("#addForm").submit(function(e){
        	e.preventDefault();
        	var formData = $("#addForm").serialize();
        	$.ajax({
        		type: "POST",
        	    url: 'ajax/ajaxAddInventory.php',
        	    data: formData,
        	    success: function(data){
        	      	alert(data);	
        	       	//window.location.href="manageInventory.php";
        	    },
	       	});
        	
    	});

        fetchRoomsForHotel('<?php echo $row->id_hotel; ?>','id_room','<?php echo $row->id_room; ?>');
	});

	$(document).ajaxStart(function(){
			$('#loadMe').show();
	});

	$(document).ajaxComplete(function(){
		$('#loadMe').hide();
	});
</script>