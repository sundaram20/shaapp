<?php include_once("../config/auto_loader.php");?>

<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>
<style type="text/css">
	table {
	  table-layout: fixed; 
	  width: 100%;
	  *margin-left: -100px;/*ie7*/

	}
	td, th {
	  vertical-align: top;
	  border-top: 1px solid #ccc;
	  padding:10px;
	  width:100px;
	   text-align: center;
	}
	th {
	/*  position:absolute;
	  *position: relative; /*ie7*/
	/*  left:0; */
	  width:100px;
	}
	.hard_left {
	  position:absolute;
	  *position: relative; /*ie7*/
	  left:0; 
	  margin-right: 0px;
	  width:100px;
	}
	/*.next_left {
	  position:absolute;
	  *position: relative; 
	  left:100px; 
	  width:100px;
	}*/
	.outer {
		position:relative;
		overflow-x:hidden;
	}
	.inner {
	  overflow-x:scroll;
	  overflow-y:visible;
	  width:100%; 
	  margin-left:100px;
	}
	tbody{
		padding: 0px 0px 0px 0px;
	}

	.inputGrid{
		margin-right:5px;
		text-align:center;
		margin-left:5px;
		width:50px;
		height:30px;
	}
	
	.arrows:hover{
		color:green;
		font-size:20px;
	}
	
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage  Inventory</small>
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
		<form name="searchForm" id="searchForm" action="" method="get">
          <input type="hidden" >  
        <div class="box-body">
          <div class="row">
            <div class="col-md-4">
              <div class="form-group">
        <label for="hotel">Hotel<font color="#FF0000">*</font></label>
        	<?php $categoryDropDown = '<select class="form-control select2" onchange="fetchRoomsForHotel(this.value,\'id_room\');fetchInvGrid();"  required name="id_hotel" id="id_hotel">
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
        	<span id="inventoryError" style="color:red;"></span>
         </div>
            </div>

            <!---- Rooms-plan-link -->
            <div class="col-md-4">
                <div class="form-group">
              <label for="link_id">Room</label>

			  <select class="form-control select2" required name="id_room[]" onchange="fetchInvGrid();" id="id_room" multiple="multiple">
                             
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
                    <input  type="text" class="form-control pull-right dateRangeInv" id="effective_date" name="effective_date" data-parsley-required value="" data-parsley-errors-container="#effective_dateError"  autocomplete="off" >
                </div>
                    <!-- /.input group -->
                <span id="effective_dateError"></span>
            </div>


          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <!--<input name="Search" id="Search" type="submit" class="btn btn-primary" value="Search" />-->
        <input name="update" id="update" type="button" class="btn btn-success" onclick="updateInventoryGrid();" value="Update" style="display: none;" />
        <a href="manageInventory.php" class="btn btn-warning">Cancel</a>
        <span id="loadMe" style="display: none;color: red;" >Plaese Wait...</span>
        </div>
		</form>		
      
    </section>
    <hr/>
    <section style="margin-top:-60px; " id="gridLayout" class="content">
    		
    </section>
    <!-- /.content -->
  </div>
<script type="text/javascript">
	var dayExtend=15;
       
</script>                                   
<?php include_once("../includes/footer.php")?>  

<script type="text/javascript">

	function fetchInvGrid(){
		var formData = $("#searchForm").serialize();
        $.ajax({
        	type: "POST",
            url: 'ajax/ajaxFetchInventoryGrid.php',
            data: formData,
            success: function(data){
            	if(data!=''){
            		$("#update").show();
            	}
            	else{
            		$("#update").hide();
            	}
              	//console.log(data);	
               	$("#gridLayout").html(data);

               	
            },
        });	
	}

	function updateInventoryGrid(){
		var id_room=[];
		id_room = $('#id_room').val();
		var completeData='';
		for(let i=0;i<id_room.length;i++){
			completeData += '&'+$('#form'+id_room[i]).serialize();
		}

		$.ajax({
        		type: "POST",
        	    url: 'ajax/ajaxUpdateInventory.php',
        	    data: completeData+'&effective_date='+$('#effective_date').val()+'&id_room='+id_room+'&id_hotel='+$('#id_hotel').val(),
        	    success:function(data){
        	    	alert(data);
        	    	//window.location.href="manageInventory.php";
        	    },
	       	});
		
		
	}
	$(document).ajaxStart(function(){
			$('#loadMe').show();
	});

	$(document).ajaxComplete(function(){
		$('#loadMe').hide();
	});

	function fillLeft(from,till,id){

		var fillVal = $("input[name='"+id+"_"+till+"']").val();

		var end = new Date(till);
		var  start = new Date(from);
		
		while(start <=end){
			var day = ("0" + (start.getDate())).slice(-2);
			var month = ("0" + (start.getMonth() + 1)).slice(-2);
			var year = start.getFullYear();

			var date = (year+'-'+month+'-'+day);

			$("input[name='"+id+"_"+date+"']").val(fillVal);

			start.setDate(start.getDate() + 1);

		}
	}

	function fillRight(from,till,id){
		var fillVal = $("input[name='"+id+"_"+from+"']").val();
		var end = new Date(till);
		var  start = new Date(from);
				
		while(start <=end){
			var day = ("0" + (start.getDate())).slice(-2);
			var month = ("0" + (start.getMonth() + 1)).slice(-2);
			var year = start.getFullYear();

			var date = (year+'-'+month+'-'+day);

			$("input[name='"+id+"_"+date+"']").val(fillVal);

			start.setDate(start.getDate() + 1);

		}
	}
	
	///////////// range picker//////////
	

</script>