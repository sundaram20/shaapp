<style>
.skin-blue .sidebar-menu .treeview-menu>li>a {
    color: #8aa4af;
}
.skin-blue .sidebar-menu .treeview-menu>li.active>a, .skin-blue .sidebar-menu .treeview-menu>li>a:hover {
    color: #fff;
}
</style>
<script>
function closeModalFooterButton(){
	$('#bookereditModal').modal('hide');	
	}
</script>
<footer class="main-footer">
    <div class="pull-right hidden-xs">
      <b>Version</b> 2.0.0
    </div>
    <strong>Copyright &copy; <?=date("Y")?> <a href="#">Aadiyar Infotech Pvt Ltd</a>.</strong> All rights reserved.
</footer>
 <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
   <!-- <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>-->
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-birthday-cake bg-red"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>

                <p>Will be 23 on April 24th</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-user bg-yellow"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Frodo Updated His Profile</h4>

                <p>New phone +1(800)555-1234</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-envelope-o bg-light-blue"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Nora Joined Mailing List</h4>

                <p>nora@example.com</p>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <i class="menu-icon fa fa-file-code-o bg-green"></i>

              <div class="menu-info">
                <h4 class="control-sidebar-subheading">Cron Job 254 Executed</h4>

                <p>Execution time 5 seconds</p>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

        <h3 class="control-sidebar-heading">Tasks Progress</h3>
        <ul class="control-sidebar-menu">
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Custom Template Design
                <span class="label label-danger pull-right">70%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Update Resume
                <span class="label label-success pull-right">95%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-success" style="width: 95%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Laravel Integration
                <span class="label label-warning pull-right">50%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-warning" style="width: 50%"></div>
              </div>
            </a>
          </li>
          <li>
            <a href="javascript:void(0)">
              <h4 class="control-sidebar-subheading">
                Back End Framework
                <span class="label label-primary pull-right">68%</span>
              </h4>

              <div class="progress progress-xxs">
                <div class="progress-bar progress-bar-primary" style="width: 68%"></div>
              </div>
            </a>
          </li>
        </ul>
        <!-- /.control-sidebar-menu -->

      </div>

      <!-- /.tab-pane -->

      <!-- Settings tab content -->
      <?php /*?><div class="tab-pane" id="control-sidebar-settings-tab">
        <form method="post">
          <h3 class="control-sidebar-heading">General Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Report panel usage
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Some information about this general settings option
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Allow mail redirect
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Other sets of options are available
            </p>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Expose author name in posts
              <input type="checkbox" class="pull-right" checked>
            </label>

            <p>
              Allow the user to show his name in blog posts
            </p>
          </div>
          <!-- /.form-group -->

          <h3 class="control-sidebar-heading">Chat Settings</h3>

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Show me as online
              <input type="checkbox" class="pull-right" checked>
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Turn off notifications
              <input type="checkbox" class="pull-right">
            </label>
          </div>
          <!-- /.form-group -->

          <div class="form-group">
            <label class="control-sidebar-subheading">
              Delete chat history
              <a href="javascript:void(0)" class="text-red pull-right"><i class="fa fa-trash-o"></i></a>
            </label>
          </div>
          <!-- /.form-group -->
        </form>
      </div><?php */?>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>

</div>

  <!--add guest starts-->
    <div id="addPosGuestModal" class="well p-4" style="width:300px;margin:0 15px;display: none;"> 
      <form id="FormAddPosGuest" autocomplete="on">
          
     <!-- <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
            <div id="kot_mdoc_no"> </div>-->
      <div class="form-group">
          <label for="title">Guest Name</label>
          
          <input type="text" class="form-control input-sm" placeholder="Enter Guest Name" id="name" name="name" value=""/>
        </div>
        <div class="form-group">
          <label for="title">Mobile No</label>
          
          <input type="text" class="form-control input-sm" placeholder="Enter Guest Mobile No" id="mobile" name="mobile"  required value=""/>
        </div>
      
      
      
      <div class="form-group">
         <label for="btn">&nbsp;<br><br></label>
             
        <button class="btn c-btn" onclick="ajaxAddPosGuest();" type="button"><i class="far fa-save"></i> Add</button>
        <a  href="" class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</a>
      </div>
       
      
      </form>
    </div>
    <!--pos guest  popup ends-->
<!-- Start of LiveChat (www.livechatinc.com) code -->
<!--<script type="text/javascript">
window.__lc = window.__lc || {};
window.__lc.license = 9461310;
(function() {
  var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true;
  lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js';
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s);
})();
</script>-->
<!-- End of LiveChat code -->


<!-- ./wrapper -->
<!-- jQuery 3 -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->

<script src="<?php echo $SITE_URL; ?>/js/bootbox.min.js"></script>
<script src="<?php echo $SITE_URL; ?>/js/sweetalert.min.js" ></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $SITE_URL; ?>/plugins/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- PACE -->
<script src="<?php echo $SITE_URL; ?>/plugins/pace/pace.min.js"></script>
<!-- FastClick -->
<script src="<?php echo $SITE_URL; ?>/plugins/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="<?php echo $SITE_URL; ?>/plugins/dist/js/adminlte.min.js"></script>
<!-- Sparkline -->
<script src="<?php echo $SITE_URL; ?>/plugins/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap  -->
<script src="<?php echo $SITE_URL; ?>/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="<?php echo $SITE_URL; ?>/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- SlimScroll -->
<script src="<?php echo $SITE_URL; ?>/plugins/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- ChartJS -->
<script src="<?php echo $SITE_URL; ?>/plugins/chart.js/Chart.js"></script>
<!-- CK Editor -->
<script src="<?php echo $SITE_URL; ?>/plugins/ckeditor/ckeditor.js"></script>

<!-- CK finder 3.5 -->
<!-- <script src="<?php echo $SITE_URL; ?>/plugins/ckfinder/ckfinder.js"></script>-->
<!-- iCheck 1.0.1 -->
<script src="<?php echo $SITE_URL; ?>/plugins/iCheck/icheck.min.js"></script>
<!-- Select2 -->
<script src="<?php echo $SITE_URL; ?>/plugins/select2/dist/js/select2.full.min.js"></script>
<!-- AdminLTE dashboard  -->
<script src="<?php echo $SITE_URL; ?>/plugins/dist/js/pages/dashboard2.js"></script>
<!-- AdminLTE demo  -->
<script src="<?php echo $SITE_URL; ?>/plugins/dist/js/demo.js"></script>	
<!-- date-range-picker -->
<script src="<?php echo $SITE_URL; ?>/plugins/moment/min/moment.min.js"></script>
<script src="<?php echo $SITE_URL; ?>/plugins/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- bootstrap datetimepicker -->
<script src="<?php echo $SITE_URL; ?>/js/bootstrap-datetimepicker.min.js"></script>
<script src="<?php echo $SITE_URL; ?>/js/custom-admin.js"></script>	
 
<!-- autocomplete js -->
<script src="<?php echo $SITE_URL; ?>/js/awesomplete.min.js" ></script>
<!--parsley validation -->
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>-->
<?php /*?><script src="<?php echo $SITE_URL; ?>/js/jquery-ui.min.js"></script><?php */?>
<script src="<?php echo $SITE_URL; ?>/js/parsley.min.js"></script>
<!-- popup overlay js -->
<script src="<?php echo $SITE_URL; ?>/js/jquery.popupoverlay.js" ></script>
<script src="<?php echo $SITE_URL; ?>/js/jquery-ui.js" ></script>


<script src="<?php echo $SITE_URL; ?>/plugins/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="<?php echo $SITE_URL; ?>/plugins/datatables.net/js/jquery.dataTables.min.js"></script>  
<script src="<?php echo $SITE_URL; ?>/pos/js/owl.js" ></script>



<script>
    
    /*** awesome Plete Event **/
    //var menuSearch = $('#menuSearch');

    /*new Awesomplete(menuSearch, {
      list: [
        { label: "Belarus", value: "BY" },
        { label: "China", value: "CN" },
        { label: "United States", value: "US" }
      ],
      // insert label instead of value into the input.
      replace: function(suggestion) {
        this.input.value = suggestion.label;
      }
    });*/

    document.getElementById('menuSearch').addEventListener("awesomplete-select", function(event) {
        window.location.href=event.text.value;
        console.log( event.text.label, event.text.value );
    });
    /*** awesome plete end ***/

    $(document).ready( function () { 
        $('#myTable').DataTable({
                printColumns: [0, 1, 2], 
                "oLanguage": {
                 "sLengthMenu": "Show  _MENU_ records", // **dont remove _MENU_ keyword**
                },
        }); 
        
    });


    $('.datepicker').datepicker({dateFormat: "dd-mm-yy" });  
    $('.todaydate_start').datepicker({minDate : new Date()});     
    $('.monthandday').datepicker({ dateFormat: "dd/mm" });

 

   

////////////////////////apply discount - booknow-step2.php/////////////////////////////////////////
function discountType(discountVar) {
	if(discountVar == '1' ){
		$("#flat").css('display', 'block');
		$("#percent").css('display', 'none');
	}else{
		$("#flat").css('display', 'none');
		$("#percent").css('display', 'block');
	}
}



function applyDiscount() {
var discountType = $('#discountType').val();
	if(discountType == '1' ){
		var discountVar = $('#flatDiscount').val();
	}else{
		var discountVar = $('#percentDiscount').val();
	}
 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxUpdateDiscount.php',
	   data: 'discount=apply'+'&discountType='+discountType+'&discountVar='+discountVar, 
	   success: function (result) {
		 var  resultArray = result.split('|||');	
		 $('#discount').html(resultArray['0']);	
		 $('#totalPrice').html(resultArray['1']);				
		 $('#discountMsg').html(resultArray['2']);	 
		}
	});

}

/////////////////////////////////ajaxaddroommsg -book-now.php 1 2 and 3/////////////////////////////////////////////
function ajaxAddRoommsgUpdate(){
	
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxUpdateSessionBooking.php',
   data: 'remove=removeAll', 
   success: function (result) {					
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#addRoommsg').html(result);
		$('#createBooking').css('visibility', 'hidden');
		$('#roomLimitMsg').css('display', 'none');
		$('#roomLimitMsgRoomType').css('display', 'none');
		
	}
	})
}


/////////////////////////room remove - booknow.php//////////////////////////////////////// 
function ajaxRoomRemove(uniqueCode){
 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateSessionBooking.php',
			   data: 'remove=removeOne'+'&uniqueCode='+uniqueCode, 
			   success: function (result) {	
			   resultArray = result.split('|||');			
				 $('#'+uniqueCode).remove();
				 if(resultArray['1']=='removeroomLimitMsg'){
					$('#roomLimitMsg').css('display', 'none');	
					}
					if(resultArray['2']=='roomLimitMsgRoomType'){
					$('#roomLimitMsgRoomType').css('display', 'none');	
					}
		$( ".ajaxAddRoom" ).remove();
		$('#addRoommsg').show();
		$('#subtotal').html('<i class="fa fa-inr"></i> 0');
		$('#discount').html('<i class="fa fa-inr"></i> 0');
		$('#addchargesvalue').html('<i class="fa fa-inr"></i> 0');
		$('#tax').html('<i class="fa fa-inr"></i> 0');
		$('#totalPrice').html('<i class="fa fa-inr"></i> 0');
		$('#amountReceived').html('<i class="fa fa-inr"></i> 0');
		$('#balance').html('<i class="fa fa-inr"></i> 0');
		$('#flatDiscount').val(0);
		$('#percentDiscount').val(0);
		$('#flatAdditionalCharges').val(0);
		$('#percentAdditionalCharges').val(0);
				}
		});
}



function getSeriesBooking() {
	
	var seriesId = $('#series').val();
	var operatorId = $('#operator').val();
	var hotel_id = $('#hotel_id').val();
	var reservation_date = $("#reservation_date").val();
	
		var id_company = $('#id_company').val();
			 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxSeriesBookingMaster.php',
			   data: 'seriesId='+seriesId+'&operatorId='+operatorId+'&hotel_id='+hotel_id+'&id_company='+id_company+'&reservation_date='+reservation_date, 
			   success: function (result) {	
			   		   
			   		//resultArray = result.split('|||');
					$('#SeriesBookingMasterDetail').html(result);
						//$company.val(resultArray[0]).trigger('change');
						//getContact(resultArray[0],resultArray[1]);
						//$guest.val(resultArray[2]).trigger('change');
						
						//$('#id_guest').val(resultArray[2]).trigger('change');
						
				   }
				})	
}


function getSeriesOperator() {
	
	var seriesId = $('#series').val();
	var operatorId = $('#operator').val();
	 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxseriesOperator.php',
			   data: 'seriesId='+seriesId+'&operatorId='+operatorId, 
			   success: function (result) {			   
			   		resultArray = result.split('|||');
									
						//$('#id_company option[value="'+resultArray[0]+'"]').prop('selected', "selected");
						$company.val(resultArray[0]).trigger('change');
						getContact(resultArray[0],resultArray[1]);
						$guest.val(resultArray[2]).trigger('change');
						//$('#id_guest option[value="'+resultArray[2]+'"]').prop('selected', true);
						
					
				   }
				})	
}
//////////////////////ger rate - booknow.php///////////////////////////////////////////////
function getRate(uniqueCode){
	var arrayCode = uniqueCode.split("|");
	var uniqueId = arrayCode['1'];
	var room_quantity = $('#room_quantity\\|'+uniqueId).val();
	var dataValue = $('#dataValue\\|'+uniqueId).val();
	var adult_no = $('#adult_no\\|'+uniqueId).val();
	var infant_no = $('#infant_no\\|'+uniqueId).val();
	var child_no = $('#child_no\\|'+uniqueId).val();
	var uniqueCode = $('#uniqueCode\\|'+uniqueId).val();
	 $.ajax({
			   type: "GET",
			   url: 'ajax/ajaxupdateRoomDetails.php',
			   data: 'dataValue='+dataValue+'&room_quantity='+room_quantity+'&adult_no='+adult_no+'&child_no='+child_no+'&infant_no='+infant_no+'&uniqueCode='+uniqueCode, 
			   success: function (result) {
			   		resultArray = result.split('|||');	
						$('#roomLimitMsg').css('display', 'block');
						$('#roomLimitMsg').html(resultArray['0']);
						$('#roomLimitMsgRoomType').css('display', 'block');
						$('#roomLimitMsgRoomType').html(resultArray['1']);
					
					$('#price_'+uniqueId).html(resultArray['2']);					
				   }
				})	
}
//////////////////////////////book now/////////////////////////////////////////
$("#createBooking").on('click', function (){
	
var date = $("#reservation_date").val().split(' to ');
/*var Roomtax = $("#Roomtax").val().split(' to ');

if(Roomtax == ''){
alert('Update Room Tax');
return false;
}*/


var checkin = date[0];
var checkout = date[1];
if(checkin == checkout){
alert('Checkin And Checkout Date Are Same.');
return false;
}

 var form=$("#addRoomForm");
  $("#id_guest").attr('data-parsley-required',true);
   $("#id_contacts").attr('data-parsley-required',true);
   $("#series").attr('data-parsley-required',true);
   $("#operator").attr('data-parsley-required',true);
 if(form.parsley().validate()){
 		form.submit();	
	//window.location.href='booknow-step2.php';
 }
});





////////////////////////show payment date- booknow-step3.php/////////////////////////////////////////
function showPaymentDate(bookingStatusId){
 	  	//alert(bookingStatusId);
if(bookingStatusId == '2'){
	$("#paymentDate").css('display', 'block');
	$( "#payment_date" ).attr( 'data-parsley-required',""); 
}else{
	$("#paymentDate").css('display', 'none');
	$( "#payment_date" ).removeAttr( 'data-parsley-required',""); 
}


if(bookingStatusId == '4'){
	$("#CancellationReason").css('display', 'block');
	$( "#CancellationReason_status" ).attr( 'data-parsley-required',""); 
}else{
	$("#CancellationReason").css('display', 'none');
	$( "#CancellationReason_status" ).removeAttr( 'data-parsley-required',""); 
}





}

/*$("#addInfo").click(function(){
    $("#addInfoDisplay").slideToggle();
});
$("#miscInfo").click(function(){
    $("#miscInfoDisplay").slideToggle();
});*/

//////////////////////////////////end///////////////////////////////////////////////////////////
/////////////////////////sorting hotel gallery page - photogallery-page/////////////////////   
$('.sortable-list').sortable({
	handle: '.sortable-heading',
	update: function(event, ui) {
			var newOrder = $(this).sortable('toArray').toString();
			$.get('ajax/_saveorder_ajax.php', {tbl: '<?=TBL_HOTEL_GALLERY?>',order:newOrder});
	}
});
/////////////////////////show rate grid editRate.php/////////////////////    
function rateMasterFunction() {
  //alert('test');
  var form=$("#rateMaster");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: 'ajax/ajaxRateMaster.php',
	   data: form.serialize(), 
	   success: function (result) {
	   	
		  if(result!=''){
			$('#rateMasterDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
});
}else {
	$('#rateMasterDetail').html('');
}
	return false;
}

function rateLetterMasterFunction() {
  //alert('test');
  var form=$("#rateMaster");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: 'ajax/ajaxRateLetterMaster.php',
	   data: form.serialize(), 
	   success: function (result) {
	   	
		  if(result!=''){
			$('#rateMasterDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
});
}else {
	$('#rateMasterDetail').html('');
}
	return false;
}



function budgetMasterFunction() {
  //alert('test');
  var form=$("#rateMaster");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: 'ajax/ajaxBudget.php',
	   data: form.serialize(), 
	   success: function (result) {
	   	
		  if(result!=''){
			$('#rateMasterDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
});
}else {
	$('#rateMasterDetail').html('');
}
	return false;
}


function editTaxConfigurationTwoFunction() {
  //alert('test');
  var form=$("#rateMaster");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: '../ajax/ajaxTaxConfigurationTwo.php',
	   data: form.serialize(), 
	   success: function (result) {
	   	
		  if(result!=''){
			$('#rateMasterDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
});
}else {
	$('#rateMasterDetail').html('');
}
	return false;
}


/////////////////////////get ratePoints details date editRate.php/////////////////////   
function ratePoints(){

 var ratePointId = $('#rate_points option:selected').val();
 var ratePointDetail = $('#ratePointDetail').val();
	$.ajax({
	   type: "GET",
	   url: 'ajax/ajaxRatePoints.php',
	   data: 'ratePointId='+ratePointId+'&ratePointDetail='+ratePointDetail, 
	   success: function (result) {	
					$( "#ratePoinData" ).html(result);
					$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
		}
	})
}

function SaveRatePointPopup() { 
	var ratePointId = $('#rate_points option:selected').val();
	var form=$("#ratePointForm");
	var ratePointDetail = form.serialize(); 
	$.ajax({
	   type: "GET",
	   url: 'ajax/ajaxRatePointsSave.php',
	   data: 'ratePointId='+ratePointId+'&'+ratePointDetail, 
	   success: function (result) {	
					$( "#ratePointDetail" ).val(result);
		}
	})


}
 
 
/////////////////////////get inclusion details date editRate.php/////////////////////   
function getInclusionDetail(hotelId){
 var rate_id = $('#id').val();
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxInclusionDetail.php',
   data: 'hotelId='+hotelId+'&rate_id='+rate_id, 
   success: function (result) {	
	  			$( "#rateUpdateData" ).html(result);
				
	}
	})
}

 
/////////////////////////get season date editRate.php/////////////////////   
function getseasonDate(seasonId){
	
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxSeasonDate.php',
   data: 'seasonId='+seasonId, 
   success: function (result) {	
	   if(result !=''){
			seasondateArray = result.split(',');
			
			$('#start_date').val(seasondateArray[0]);
			
			seasondateArraySecond = seasondateArray[1].split('|||');

			
			$('#end_date').val(seasondateArraySecond[0]);
			//$('#rate_level_id').html(seasondateArraySecond[1]);
			//$('#new_rate_level_id').html(seasondateArraySecond[2]);
		}
	}
	})
}

function updateLevelAndMarket(){
	
	 var rate_level_id = $('#rate_level_id option:selected').val();
 var start_date = $('#start_date').val();
	
	$.ajax({
   type: "GET",
   url: 'ajax/ajaxLevelAndMarket.php',
   data: 'SelectedRateID='+rate_level_id+'&start_date='+start_date, 
   success: function (result) {	
  
	  if(result !=''){
			seasondateArray = result.split('|||');
			

			$('#new_rate_level_id').html(seasondateArray[0]);
			$('#market').html(seasondateArray[1]);
		}
	}
	})
	
	}
	
function getbudgetyear(seasonId){
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxBudgetYear.php',
   data: 'seasonId='+seasonId, 
   success: function (result) {	
	   if(result !=''){
			seasondateArray = result.split(',');
			$('#start_date').val(seasondateArray[0]);
			$('#end_date').val(seasondateArray[1]);
		}
	}
	})
}
function pkgPopup(id){
	var Id = id.split('|');
	var roomId= Id[1];
	var ratePlanId= Id[2];	
	var planType= Id[3];
	$('#pkgroomEid').val(roomId);
	$('#pkgratePlanId').val(ratePlanId);
	$('#pkgplanType').val(planType);
 	$("#rack_rate").val($('#rack_rate\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_title").val($('#pkg_title\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_description").val($('#pkg_description\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_min_pax").val($('#pkg_min_pax\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_min_nights").val($('#pkg_min_nights\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_discount").val($('#pkg_discount\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$("#pkg_extra_price").val($('#pkg_extra_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
	var pkg_status = $('#pkg_status\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();
	if(pkg_status==1){
		$pkg_status1.iCheck('check');
		pkg_status_value=1;		
	}else{
		$pkg_status2.iCheck('check');
		pkg_status_value=0;
	}
}


	
function savePkgPopupData(){

	var form=$("#pkgpopupForm");
	var roomId= $('#pkgroomEid').val();
	var ratePlanId= $('#pkgratePlanId').val();
	var planType= $('#pkgplanType').val();
	var inclusionPriceFood = 0;	
	var inclusionPriceExtra = 0;
 	if(form.parsley().validate()){		
		$('#pkg_title\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_title").val());
		$('#pkg_description\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_description").val());
		$('#pkg_min_pax\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_min_pax").val());
		$('#pkg_min_nights\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_min_nights").val());
		$('#pkg_discount\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_discount").val());
		$('#pkg_extra_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val($("#pkg_extra_price").val());
		$('#pkg_status\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(pkg_status_value);
		$('.inclusion\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).each(function(){
				inclusionPriceFood += parseFloat(this.value);
			});
		inclusionPriceExtra = parseFloat($('.inclusionExtra').val());
	var rack_rate = parseFloat($('#rack_rate\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	discountPkgPrice = parseFloat((rack_rate*parseFloat($('#pkg_discount\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val()))/100);
	
	pkg_price = Math.round((rack_rate-discountPkgPrice)+parseFloat($("#pkg_extra_price").val())+(2*inclusionPriceFood)+inclusionPriceExtra);
	$('#pkg_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(pkg_price);
	$('#pkg_tarrif_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(Math.round((rack_rate-discountPkgPrice)));
	$('#pkgPopup').popup('hide');
	$("#pkgpopupForm")[0].reset();
	
	}
}

////////////////////////////////pkg popup editRate.php currently no use////////////////////////////////////////////////////

function pkgPopupdata(id){
	$('#pkgId').val(id);
}

////////////////////////////////savepkg popup editRate.php currently no use////////////////////////////////////////
function savePkgPopup() {  
  var form=$("#pkgForm");
  var form2=$("#rateMaster");
  if(form.parsley().validate()){
  	var Id =  $('#pkgId').val().split('|');	
   	var roomId= Id[1];
	var ratePlanId= Id[2];
	var planType= Id[3];
	var rack_rate = $('#rack_rate\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();
	var pkgCounter =  $('#pkgCounter\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();
	
	var rateMasterData = form2.serialize(); 
	 $.ajax({
	   type: "GET",
	   url: 'ajax/ajaxPkgInsert.php',
	   data: rateMasterData+'&pkgCounter='+pkgCounter+'&roomId='+roomId+'&ratePlanId='+ratePlanId+'&rack_rate='+rack_rate,  
	   success: function (result) {	
		   if(result !=''){
			$(result).insertAfter('#rateMaster\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType);
			$('#pkg').popup('hide');
			$("#pkgForm")[0].reset();
			var num = +$('#pkgCounter\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val() + 1;
			$('#pkgCounter\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(num);
			rateCalAll('#extras','');		
			}
		}
	})  	
	}
	return false;
}

/////////////////////////rate calculation editRate.php///////////////////// 

function rateCalSingle(id,value){
	var form1=$("#rateUpdate");
	var form2=$("#rateMaster");
	if(form1.parsley().validate() && form2.parsley().validate()){
	var Id = id.split('|');
	var roomId= Id[1];
	var ratePlanId= Id[2];	
	var planType= Id[3];
	var discounType = $('#discount_type\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();
	var rackRate = parseFloat($('#rack_rate\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
	var roomPrice = 0;
	var extraBedFinalPrice = 0;		
	var inclusionPriceFood = 0;	
	var inclusionPriceExtra = 0;
	var discountPrice = 0;
	var singlefinalPrice = 0;
	var doublefinalPrice = 0;
	var extraBedPrice = parseFloat($('#extra_bed\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());	
	$('.inclusion\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).each(function(){
				inclusionPriceFood += parseFloat(this.value);
			});
	inclusionPriceExtra = parseFloat($('.inclusionExtra').val());
	$('#discountFlat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).attr('data-parsley-max',rackRate);
		if(discounType == 2){
			$('#percent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'block');
			$('#flat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'none');
			discountPrice = parseFloat((rackRate*$('#discountPercent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val())/100);
		}else{	
			$('#percent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'none');
			$('#flat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'block');
			discountPrice = parseFloat($('#discountFlat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
		}
			roomPrice = parseFloat(rackRate-discountPrice);			
			singlefinalPrice = parseFloat(inclusionPriceFood+inclusionPriceExtra+(rackRate-discountPrice));
			doublefinalPrice = parseFloat((2*inclusionPriceFood)+inclusionPriceExtra+(rackRate-discountPrice));
			extraBedFinalPrice = parseFloat(extraBedPrice+inclusionPriceFood);
			$('#inclusion_food').val(inclusionPriceFood);	
			$('#inclusion_extra').val(inclusionPriceExtra);
			$('#room_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(roomPrice);		
			$('#single_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(singlefinalPrice);	
			$('#double_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(doublefinalPrice);	
			$('#extra_bed_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(extraBedFinalPrice);	
	
			
	}
}

function rateCalAll(id,value){
		var form1=$("#rateUpdate");
		var form2=$("#rateMaster");
		if(form1.parsley().validate() && form2.parsley().validate()){
		var thisId = id.split('|');
		var inclusionId= thisId[1];
		var inclusionTypeId = thisId[2];
		var roomPrice = 0;
		var extraBedFinalPrice = 0;		
		var inclusionPriceFood = 0;	
		var inclusionPriceExtra = 0;
		var discountPrice = 0;
		var singlefinalPrice = 0;
		var doublefinalPrice = 0;		
		var inclusion = parseFloat($('#inclusion\\|'+inclusionId+'\\|'+inclusionTypeId).val(value));
		inclusionPriceExtra = parseFloat($('.inclusionExtra').val());
		$('.rack_rate').each(function () {		
			var Id = this.id.split('|');			
			var roomId= Id[1];
			var ratePlanId= Id[2];
			var planType= Id[3];
			var discounType = $('#discount_type\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();
			var rackRate = parseFloat($('#rack_rate\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
			var extraBedPrice = parseFloat($('#extra_bed\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
			$('#discountFlat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).attr('data-parsley-max',rackRate);			
		$('.inclusion\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).each(function(){
				inclusionPriceFood += parseFloat(this.value);
			});			
		if(discounType == 2){
			$('#percent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'block');
			$('#flat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'none');
			discountPrice = parseFloat((rackRate*$('#discountPercent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val())/100);
		}else{	
			$('#percent\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'none');
			$('#flat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).css('display', 'block');
			discountPrice = parseFloat($('#discountFlat\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
		}
			roomPrice = parseFloat(rackRate-discountPrice);			
			singlefinalPrice = parseFloat(inclusionPriceFood+inclusionPriceExtra+(rackRate-discountPrice));
			doublefinalPrice = parseFloat((2*inclusionPriceFood)+inclusionPriceExtra+(rackRate-discountPrice));
			extraBedFinalPrice = parseFloat(extraBedPrice+inclusionPriceFood);
			$('#inclusion_food').val(inclusionPriceFood);	
			$('#inclusion_extra').val(inclusionPriceExtra);
			$('#room_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(roomPrice);		
			$('#single_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(singlefinalPrice);	
			$('#double_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(doublefinalPrice);	
			$('#extra_bed_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(extraBedFinalPrice);
	var pkgExtraPrice = parseFloat($('#pkg_extra_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());		
	discountPkgPrice = parseFloat((rackRate*parseFloat($('#pkg_discount\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val()))/100);
	pkg_price = Math.round((rackRate-discountPkgPrice)+pkgExtraPrice+(2*inclusionPriceFood)+inclusionPriceExtra);
	$('#pkg_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(pkg_price);
			inclusionPriceFood =0;		
		});
	}
}



function rateExtra(id,value){
	var form1=$("#rateUpdate");
	var form2=$("#rateMaster");
	if(form1.parsley().validate() && form2.parsley().validate()){
		var extra_bed_price = parseFloat(value);
		inclusionPriceFood =0;
		$('.rack_rate').each(function () {		
			var Id = this.id.split('|');			
			var roomId= Id[1];
			var ratePlanId= Id[2];
			var planType= Id[3];
			
			
			var extraBedPrice = parseFloat($('#extra_bed\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val());
			
		$('.inclusion\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).each(function(){
				inclusionPriceFood += parseFloat(this.value);
			});			
		
				
			
			extraBedFinalPrice = parseFloat(extra_bed_price+inclusionPriceFood);
			
			$('#extra_bed_price\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(extraBedFinalPrice);
	
		});		
			
	}
}


function rateCalSinglePopUp(Id,tarrifName,tarrifId) {
	var Id = Id.split('|');
	var roomId= Id[1];
	var ratePlanId= Id[2];	
	var planType= Id[3];
	var tarrifPrice = 0;
	var inclusionPriceFood = 0;
	tarrifPrice = $('#'+tarrifName+'\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val();		
	$('.inclusion\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).each(function(){
		inclusionPriceFood += parseFloat(this.value);
	});	
	if(tarrifName!='extra_bed'){
		$('#tarrif').prop( "disabled", true );
	}else{
		$('#tarrif').prop( "disabled", false );
	}
	$('#meal').val(inclusionPriceFood);
	$('#tarrif').val(tarrifPrice);	
	$('#roomEid').val(roomId);
	$('#ratePlanId').val(ratePlanId);
	$('#planType').val(planType);
	$('#tarrifId').val(tarrifId);
	$('#tarrifName').val(tarrifName);
}
	
function SavePopup(){
	var roomId= $('#roomEid').val();
	var planType= $('#planType').val();
	var tarrifName= $('#tarrifName').val();
	var ratePlanId= $('#ratePlanId').val();
	var tarrifId = $('#tarrifId').val();
	var inclusionPriceFood = parseFloat($('#meal').val());
	var inclusionPriceExtra = parseFloat($('.inclusionExtra').val());
	var tarrif = parseFloat($('#tarrif').val());	
	var totalPrice = parseFloat(inclusionPriceFood+tarrif);	
	$('#'+tarrifName+'\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(tarrif);
	if(tarrifName!='extra_bed'){			
			$('#single_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(tarrif+inclusionPriceFood+inclusionPriceExtra);	
			$('#double_pax_rice\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(tarrif+(inclusionPriceFood*2)+inclusionPriceExtra);	
	}else{
	$('#'+tarrifId+'\\|'+roomId+'\\|'+ratePlanId+'\\|'+planType).val(totalPrice);
	}
}


 
/////////////////////////////////update rate values -editRate.php/////////////////////////////// 
function submitRateForm(){
$("#rate_points").attr('data-parsley-required',true);
 	var form=$("#rateMaster");
	var form1=$("#rateUpdate");
	var dataString = $("#rateMaster, #rateUpdate").serialize();
	if(form.parsley().validate() && form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxRateUpdate.php',
		   data: dataString, 
		   success: function (result) {		
		  		$( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);				
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
}


function submitRateLetterForm(){
$("#rate_points").attr('data-parsley-required',true);
 	var form=$("#rateMaster");
	var form1=$("#rateUpdate");
	var dataString = $("#rateMaster, #rateUpdate").serialize();
	if(form.parsley().validate() && form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxRateLetterUpdate.php',
		   data: dataString, 
		   success: function (result) {		
		  		$( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);				
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
}



function submitBudgetForm(){
$("#rate_points").attr('data-parsley-required',true);
 	var form=$("#rateMaster");
	var form1=$("#rateUpdate");
	var dataString = $("#rateMaster, #rateUpdate").serialize();
	if(form.parsley().validate() && form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxBudgetUpdate.php',
		   data: dataString, 
		   success: function (result) {		
		  		$( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);				
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
}

function submitTaxConfigurationTwoForm(){
$("#rate_points").attr('data-parsley-required',true);
 	var form=$("#rateMaster");
	var form1=$("#rateUpdate");
	var dataString = $("#rateMaster, #rateUpdate").serialize();
	if(form.parsley().validate() && form1.parsley().validate()){
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxTaxConfigurationTwoUpdate.php',
		   data: dataString, 
		   success: function (result) {		
		  		$( ".my_popup_open" ).click();			
				$( "#rateUpdateData" ).html(result);				
				//$("#hotelId").val('1').attr('selected','selected');					
			}
		})
	}
}





///////////////////////////////pkg popup/////////////////////////////////////////

/////////////////////////show pup ///////////////////// 

 ////////editRate.php//////////////////
 $('#fadeandscale').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
  $('#my_popupfaild').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 $('#my_popup').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 ////////editSafariBooking.php//////////////////
 $('#guest').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
  $('#contact').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 $('#pkg').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 $('#pkgPopup').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 $('#inventory').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 $('#inventorySoldOut').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });

 $('#pricePopUp').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
  $('#duplicate').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });

 $('#bookedby').popup({
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 $('#cancelpop').popup({ 
        pagecontainer: '.container',
        transition: 'all 0.3s'
 });
 
 
 
 function getRateLetter(){		
	var hotel_id = $("#hotel_id").val();
	var reservation_date = $("#reservation_date").val();
	var id_company = $("#id_company").val();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxGetRateLetter.php',
		   data: 'reservation_date='+reservation_date+'&id_company='+id_company+'&hotel_id='+hotel_id, 
		   success: function (result) {			  	    
				$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();										
			}
		})
}


function showRateLetterView(bookingStatusId){ 	  	
if(bookingStatusId.value != 0){
      document.getElementById('view').style.display = "block";
	  document.getElementById('adhol').style.display = "none";
}
if(bookingStatusId.value == 0){
      document.getElementById('adhol').style.display = "block";
	  document.getElementById('view').style.display = "none";
}
}
function NewchangeEditData(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	
	
	
	var reservation_date = $("#reservation_date").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxChangeEditDateUpdate.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&uniqueCode='+uniqueCode,  
		   success: function (result) {			  	    
				//$( "#rate_id" ).html(result);
				//ajaxRoomRemoveAll();	
				//success: function (result) {					
				resultArray = result.split('|||');	
					$('#showRoom').append(resultArray['1']);					
					$('#pricingValue').html(resultArray['2']);					
					$('#addRoommsg').css('display', 'none');
					$('#flatDiscount').val();
					$('#percentDiscount').val();
					$('#flatAdditionalCharges').val();
					$('#percentAdditionalCharges').val();
					
					
				
				
				
				 
															
			}
		})
}




function NewchangeGreedData(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	
	
	
	var reservation_date = $("#reservation_date").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxChangeEditGreedDateUpdate.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&uniqueCode='+uniqueCode,  
		   success: function (result) {			  	    
							
				resultArray = result.split('|||');	
								
				resultArray11 = resultArray['0'].split('###');	
				
				var res1 = resultArray11['1'];
				var res3 = resultArray11['3'];

				for(var i=0;i<resultArray11.length; i++){
					if ((i % 2) != 0){
						 var ArrayUnicode	=	resultArray11[i];
						 var	even = i-1;
						 var ArrayValue		=	resultArray11[even]
			 
						$('#price_'+ArrayUnicode).html(ArrayValue);
					}
				}
				
				
				$('#pricingValue').html(resultArray['1']);	
				
				/*resultArray = result.split('|||');	
					
					
					
				resultArray11 = result.split('###');	
				
				var res1 = resultArray11['1'];
				var res3 = resultArray11['3'];

				for(var i=0;i<resultArray11.length; i++){
				if ((i % 2) != 0){
				 var ArrayUnicode	=	resultArray11[i];
				 var	even = i-1;
				 var ArrayValue		=	resultArray11[even]
	 
				$('#price_'+ArrayUnicode).html(ArrayValue);
				}
				}*/
							
				//$('#price_'+res2).html(res2);
				
				 
															
			}
		})
}

function EditCheckRateLatterAvailable(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	//alert("Check");
	var reservation_date = $("#reservation_date").val();
	var rate_id = $("#rate_id").val();
	var hotel_id = $("#hotel_id").val();
	
	
	
	var reservation_date = $("#reservation_date").val();
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/EditCheckRateLatterAvailable.php',
		   data: 'reservation_date='+reservation_date+'&hotel_id='+hotel_id+'&rate_id='+rate_id+'&rate_assign_id='+rate_assign_id+'&room_id='+room_id+'&rate_plan_id='+rate_plan_id+'&type='+type+'&uniqueCode='+uniqueCode,  
		   success: function (result) {			  	    
				
			if (result == 2) {
				var greeting = "Selected Period RateLetter Is Not Available ";
				//alert(greeting);				
				ajaxRoomRemoveAll();
				getRateLetter();
				
			}if (result == 1) {				
				getRateLetter();
				}

		}
		})
}

function ViewPaymentDate(rate_id,rate_assign_id,room_id,rate_plan_id,type){
	//alert("Check");
	var reservation_date = $("#reservation_date").val();
	var booking_status = $("#booking_status").val();
	

		$.ajax({
		   type: "POST",
		   url: 'ajax/AjaxViewPaymentDate.php',
		   data: 'reservation_date='+reservation_date+'&booking_status='+booking_status,  
		   success: function (result) {			  	    
			//("#paymentStatusDate").val();	
			$('#paymentStatusDate').html(result);

		}
		})
}

function ajaxMasterRateLetterLoad(seasonId){
	
 $.ajax({
   type: "GET",
   url: 'ajax/ajaxMasterRateLetter.php',
   data: 'seasonId='+seasonId, 
   success: function (result) {	
	   if(result !=''){
		   $('#rate_level_id').html(result);
			
		}
	}
	})
}
function editTaxConfigurationOneFunction() {
  //alert('test');
  var form=$("#rateMaster");
  if(form.parsley().validate()){
   $('.loading').show(); 
  $.ajax({
	   type: "POST",
	   url: 'ajax/ajaxTaxConfigurationOne.php',
	   data: form.serialize(), 
	   success: function (result) {
	   	
		  if(result!=''){
			$('#rateMasterDetail').html(result);
		  }
		},
	  complete: function(){
		$('.loading').hide();
	  }
});
}else {
	$('#rateMasterDetail').html('');
}
	return false;
}

function SaveRatePointPopup() { 
	var ratePointId = $('#rate_points option:selected').val();
	var form=$("#ratePointForm");
	var ratePointDetail = form.serialize(); 
	$.ajax({
	   type: "GET",
	   url: 'ajax/ajaxRatePointsSave.php',
	   data: 'ratePointId='+ratePointId+'&'+ratePointDetail, 
	   success: function (result) {	
					$( "#ratePointDetail" ).val(result);
		}
	})


}
 
</script>
<script type="text/javascript">
	$(document).ready( function () {
		$('.treeview-menu').css('display','none');
		$('.treeview').removeClass('menu-open');
	});
</script>



<?php  //include_once('graphGenerator.php'); ?>
<?php 
    mysqli_close($connNew);
    mysqli_close($appConnect);
 ?>	


<script>
  /*
function addShopDetails(){
  
  //$("#cancelled").addClass("bookedby_open");
  $('#addShopDetailsModal').popup({
                 autoopen: true,            
              });
  //$("#pos_purch_id").val(posid);
  //$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);        
  }



function closeAddShopDetails(){
$('#addShopDetailsModal').popin({
                 autoopen: false,            
              });
  //$("#pos_purch_id").val(posid);
  //$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);     


  }*/
</script>

<script>
  //script for pos guest
  function ajaxAddPosGuest(){

var form=$("#FormAddPosGuest"); 
  var name=$("#name").val();
  //var id_pos_purch=$("#id_pos_purch").val();
  
  var mobile=$("#mobile").val();
  

  $('.loading').show();
    if(form.parsley().validate()){

    $.ajax({
      type: "GET",
      url: '<?php echo $SITE_URL;?>/pos/ajax/ajaxAddPosGuest.php',
      data: 'name='+name+'&mobile='+mobile, 
      success: function (result) {
            //  console.log(result);
             window.location.reload();
              data = JSON.parse(result);
            
          //$( "#GetItemListView" ).html('');
          //getPreviousOrder(data.purch_id);  
        //  alert(data.msg);

          
           /*if(submenu1=='179'){
            window.location.href="manageKotNc.php?submenu="+submenu1;
          }else{
            window.location.href="manageKot.php?submenu=178&session=22";
          }  */
          
        }

    });

  }


}


function AddPosGuest(){
  
  //$("#cancelled").addClass("bookedby_open");
  $('#addPosGuestModal').popup({
              transition: 'all 0.3s',
                 autoopen: true,            
              });
  //$("#pos_purch_id").val(posid);
  //$("#kot_mdoc_no").html(' KOT No: '+mdoc_no);        
  }
	
	function onLoadIdGuest(id){	 
	 $.ajax({	  
	   url: "ajax/ajaxSearchGuestName.php?id_guest="+id,
	   dataType: 'json',
          delay: 1,
	  success: function(data){
		
                var id = data[0].id;
                var guestname = data[0].text;
				var tr_str = "<option value=" + id +">" + guestname + "</option>" ;
				$("#id_guest").append(tr_str);
				//$("#CompanyGroupDetails").html(companyname);
					    
          }           
	})

	}

	comCheck = () =>{
		window.location.href='http://localhost:8074/app/frontoffice/manageFoBillTest.php';
	}
	$('.guestNameSearch').select2({
        placeholder: 'Search Guest Name',
        ajax: {
          url: "ajax/ajaxSearchGuestName.php",
          dataType: 'json',
          delay: 50,
		  processResults: function (data) {
			  
			  console.log(data[0].id);
			  //data1 = JSON.parse(data);
			  //alert(data1);
			 if(data[0].id){
			 	return { results: data};
			 }
			 else{
				comCheck(); 
				return { results: data};
				
			 }
          },
           cache: true
        }//ajax end
		
      });

 
	
	
</script>

</body>
</html>
