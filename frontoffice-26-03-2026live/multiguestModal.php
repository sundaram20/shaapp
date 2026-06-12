<div class="modal fade" id="guestMultipleeditModal" tabindex="-1" role="dialog"
  aria-labelledby="guestMultipleeditModalLabel" style="width: 100%; height: 100%;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="roomratesModalLabel">Guest Details </h4>
      </div>

      <div class="modal-body">

        <form id="multipleguestNewpopupform" data-parsley-validate="" autocomplete="off" method="post" action="" novalidate>
          <input type="hidden" id="multiple_EditCustomerID" name="multiple_EditCustomerID" value="">
          <input type="hidden" id="multiple_edit_order_by_room" name="multiple_edit_order_by_room" value="">
          <input type="hidden" id="multiple_guest_id_edit_reservation" name="multiple_guest_id_edit_reservation" value="">		 
          <input type="hidden" id="multiple_guest_room_no" name="multiple_guest_room_no" value="">
           <input type="hidden" id="multiple_guest_id_mst_room_no_allocation" name="multiple_guest_id_mst_room_no_allocation" value="">
           <input type="hidden" id="multiple_guest_type" name="multiple_guest_type" value="">
            <input type="hidden" id="multiple_id_folio" name="multiple_id_folio" value="">
           <input type="hidden" id="multiple_id_guest_room" name="multiple_id_guest_room" value="">
          <?php // 1 for Owner and 2 for Sharer Guest?>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Title</label>
                <?php
		  $categoryDropDown = '<select name="multiple_Nametitle" id="multiple_Nametitle" class="form-control input-sm" data-parsley-required="">
           <option value="">-Select-</option>';
		   $resCat1 = selectSql(TBL_ATTRIBUTES," where  table_name ='title' AND status='1'  ");
									if($db->num_rows2($resCat1)){
										while($resultCat1 = $db->fetch_object2($resCat1)){
											if($row->id_mst_attributes_title == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->field_value.'</option>';
										}
									}	echo $categoryDropDown .= '</select>'; ?>
                <?php /*?> <select name="Nametitle" id="Nametitle" class="form-control input-sm"
                  data-parsley-required="">
                  <option value="">-Select-</option>
                  <option value="Dr.">Dr.</option>
                  <option value="Miss.">Miss.</option>
                  <option value="Mr.">Mr.</option>
                  <option value="Mrs.">Mrs.</option>
                  <option value="Ms.">Ms.</option>
                  <option value="Pr.">Pr.</option>
                  <option value="Prof.">Prof.</option>
                  <option value="Rev.">Rev.</option>
                  <option value="Group.">Group.</option>
                </select> <?php */ ?>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter first name" id="multiple_first_name"
                  name="multiple_first_name" value="" data-parsley-required="">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control input-sm" placeholder="Enter last name" id="multiple_last_name"
                  name="multiple_last_name" value="" data-parsley-required="">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="email">Email Id</label>
                <input type="text" name="multiple_email" id="multiple_email" class="form-control" placeholder="Enter Email Id"
                  automcomplete="off">
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">Mobile No.</label>
                <input type="text" name="multiple_mobile" id="multiple_mobile" class="form-control" placeholder="Enter mobile number"
                  automcomplete="off">
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label for="mobile">City</label>
                <input type="text" name="multiple_city" id="multiple_city" class="form-control" placeholder="Enter City"
                  automcomplete="off">
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Country</label>
                <select class="form-control" name="multiple_id_country" id="multiple_id_country" data-parsley-required="">
                <option value="">Select Country</option>
                <?php 
                  $resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');

                  if($db->num_rows2($resCat)){

                    while($resultCat = $db->fetch_object2($resCat)){
                      $countryDropDown .= '<option value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
                    }
                  }
                    echo $countryDropDown;
                ?>
                </select>
              </div>
            </div>
            
            
           
            <div class="col-md-6">
              <div class="form-group">
                <label>Guest type</label>
                <select name="multiple_user_type" id="multiple_user_type" class="form-control input-sm">
                  <option value="0">-Select-</option>
                  <option value="1">VIP</option>
                  <option value="2">CIP</option>
                </select>
              </div>
            </div>
            </div>
            <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Nationality</label>
                <select class="form-control" name="multiple_id_nationality" id="multiple_id_nationality" data-parsley-required="">
                </select>
              </div>
            </div>
           
            
           
            </div> 
          <div class="card text-dark bg-light">
            <div class="bg-primary text-center ">
              <h5 style="padding: 5px;">ID Proof Details</h5>
            </div>
            <hr />
            <div class="row">
              <div class="form-group col-md-6">
                <label for="proof_type">Id Proof Details</label>
                <div class="input-group">
                  <div class="input-group-addon">
                    <i class="fa fa-address-card"></i>
                  </div>
                  <select class="form-control" style="width: 100%;" id="multiple_proof_type" name="multiple_proof_type">
                    <?php if($row->proof_type == 1){ ?>
                    <option value="1" selected="selected">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3">Passport</option>
                    <option value="4">Form C</option>
                    <?php }else if($row->proof_type == 2){?>
                    <option value="2" selected="selected">Adhar</option>
                    <option value="1">Voter Id</option>
                    <option value="3">Passport</option>
                    <?php }else if($row->proof_type == 3){?>
                    <option value="1">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3" selected="selected">Passport</option>
                    <?php }else{ ?>
                    <option selected="selected" value="">Select Id Proof</option>
                    <option value="1">Voter Id</option>
                    <option value="2">Adhar</option>
                    <option value="3">Passport</option>
                    <?php } ?>

                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div id="multipleappenddata">
                <?php if($row->proof_type == 1){ ?>
                <div class="form-group col-md-6">
                  <label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="multiple_voter_no" name="multiple_voter_no"
                      placeholder="Enter Voter Id Number"
                      value="<?php if($_POST['voter_no']) echo $_POST['voter_no']; else echo $row->voter_no;?>"
                      data-parsley-errors-container="#voter_noError" data-parsley-required />
                  </div>
                  <span id="voter_noError"><?php echo $err_voter_noError;?></span>
                </div>
                <?php }else if($row->proof_type == 2){ ?>
                <div class="form-group col-md-6">
                  <label for="adhar_no">Adhar Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="multiple_adhar_no" name="multiple_adhar_no"
                      placeholder="Enter Aadhar Number"
                      value="<?php if($_POST['adhar_no']) echo $_POST['adhar_no']; else echo $row->adhar_no;?>"
                      data-parsley-errors-container="#adhar_noError" data-parsley-required />
                  </div>
                  <span id="adhar_noError"><?php echo $err_adhar_noError;?></span>
                </div>
                <?php }else if($row->proof_type == 3){ ?>
                <div class="form-group col-md-6">
                  <label for="passport_no">Passport Number <font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa fa-address-book"></i>
                    </div>
                    <input type="text" class="form-control" id="multiple_passport_no" name="multiple_passport_no"
                      placeholder="Enter Passport Number"
                      value="<?php if($_POST['passport_no']) echo $_POST['passport_no']; else echo $row->passport_no;?>"
                      data-parsley-errors-container="#passport_noError" data-parsley-required />
                  </div>
                  <span id="passport_noError"><?php echo $err_passport_noError;?></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="authority">Authority<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-arrows"></i>
                    </div>
                    <input type="text" class="form-control" id="multiple_authority" name="multiple_authority"
                      placeholder="Enter Authority"
                      value="<?php if($_POST['authority']) echo $_POST['authority']; else echo $row->authority;?>"
                      data-parsley-errors-container="#authorityError" data-parsley-required />
                  </div>
                  <span id="authorityError"><?php echo $err_authorityError;?></span>
                </div>
                <div class="form-group col-md-6">
                  <label for="passport_expiry_date">Passport Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="multiple_passport_expiry_date"
                      name="multiple_passport_expiry_date" placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['passport_expiry_date']) echo $_POST['passport_expiry_date']; else echo date('d-m-Y',strtotime($row->passport_expiry_date));?>"
                      data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span>
                </div>

                <div class="form-group col-md-6">
                  <label for="passport_expiry_date">Visa Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="multiple_visa_expiry_date" name="multiple_visa_expiry_date"
                      placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['visa_expiry_date']) echo $_POST['visa_expiry_date']; else echo date('d-m-Y',strtotime($row->visa_expiry_date));?>"
                      data-parsley-errors-container="#visa_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="visa_expiry_dateError"><?php echo $err_visa_expiry_dateError;?></span>
                </div>

                <div class="form-group col-md-6">
                  <label for="cform_expiry_date">C Form Expiry Date<font color="#FF0000">*</font></label>
                  <div class="input-group">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar-minus-o"></i>
                    </div>
                    <input type="text" class="form-control datepicker" id="multiple_cform_expiry_date" name="multiple_cform_expiry_date"
                      placeholder="dd-mm-yyyy"
                      value="<?php if($_POST['cform_expiry_date']) echo $_POST['cform_expiry_date']; else echo date('d-m-Y',strtotime($row->cform_expiry_date));?>"
                      data-parsley-errors-container="#cform_expiry_dateError" data-parsley-required />
                  </div>
                  <span id="cform_expiry_dateError"><?php echo $err_cform_expiry_dateError;?></span>
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div style="text-align:center">
            <input type="button" class="btn btn-primary" onClick="saveMultipleGuestNewPopupform();" value="Save">
            <button type="button" class="guest_close btn btn-danger" data-dismiss="modal" aria-label="Close">Close</button>
          </div>
      </div>
      </form>
    </div>
    <div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div>
  </div>
</div><script>
function saveMultipleGuestNewPopupform() {

    var form = $("#multipleguestNewpopupform");
	var order_by_room = $("#multiple_edit_order_by_room").val();
	var multiple_id_guest_room = $("#multiple_id_guest_room").val();
    if (form.parsley().validate()) {

      $('.loading').show();

      $.ajax({

        type: "POST",

        url: 'ajax/ajaxSaveMultipleGuestNew.php',

        data: form.serialize(),

        success: function (result) {
//var data = JSON.parse(result);
          if (result != '') {

            $('#id_mst_guest_form_'+multiple_id_guest_room).empty();

            $('#id_mst_guest_form_'+multiple_id_guest_room).html(result);

            $('#showGuest').click();

            $("#multipleguestNewpopupform")[0].reset();
            $("#guestMultipleeditModal").modal("hide");
            alert("Guest added sucessfully ");
			 /*if(data.sharerGuest!=null){
			 $("#guestroomorder_"+data.order_by_room).html(data.sharerGuest);
			 }
			 if(data.mainGuest!=null){
			 $("#mainGuestguestroomorder_"+data.order_by_room).html(data.mainGuest);
			 }
			 if(data.invoiceGuest!=null){
			  $("#folio_guestname").html(data.invoiceGuest);
			 }*/
			  
          }

        },

        complete: function () {

          $('.loading').hide();

        }

      });

      return false;

    }

  }

	
	
	function GetEditGuestDetail_Zero(id,id_fo_reservations,id_mst_room_no_allocation,order_by_room,room_no,guest_type,id_folio){
		
			$("#multiple_edit_order_by_room").val(order_by_room);
$("#multiple_guest_id_edit_reservation").val(id_fo_reservations);
$("#multiple_guest_id_mst_room_no_allocation").val(id_mst_room_no_allocation);
$("#multiple_guest_room_no").val(room_no);
$("#multiple_guest_type").val(guest_type);
$("#multiple_id_folio").val(id_folio);


if(guest_type==1){
			$('#ownerguesthide').show();
			//$('#service_charge_per').attr('required','required');
		}	
		else{
			$('#ownerguesthide').hide();
			//$('#service_charge_per').removeAttr('required','required');
		}
			$.ajax({
			type: "POST",
			url: 'ajax/ajaxGuestEditSearch.php',
			data: 'id='+id+'&id_folio='+id_folio+'&id_mst_room_no_allocation='+id_mst_room_no_allocation,  
			success: function (response) {
				
				var data = JSON.parse(response);
			$('#guestMultipleeditModal').modal('show');				
			$("#multiple_first_name").val(data.first_name);
			$("#multiple_last_name").val(data.last_name);
			$("#multiple_email").val(data.email);
			$("#multiple_mobile").val(data.primary_mobile);
			$("#multiple_city").val(data.city);
			$("#multiple_EditCustomerID").val(data.id);
			$("#multiple_id_country").val(data.id_mst_country_lang);
			$("#multiple_Nametitle").val(data.id_mst_attributes_title);
			$("#multiple_user_type").val(data.guest_vipstatus).change();
			$("#owner_guest").val(data.owner_guest).change();
			$("#multiple_id_nationality").html(JSON.parse(data.nationality));
			
			if(data.proof_type=='0'){
			$("#multiple_proof_type").val('');
			}else{
				$("#multiple_proof_type").val(data.proof_type);
			}

    var idProof = data.proof_type;
             
            if(idProof == 1){
                var Vote_Id = '<div class="form-group col-md-6"><label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="multiple_voter_no" name="multiple_voter_no" placeholder="Enter Voter Id Number" value="'+data.voter_no+'" data-parsley-errors-container="#voter_noError" data-parsley-required /></div><span id="voter_noError"><?php echo $err_voter_noError;?></span></div>'; 
                $("#multipleappenddata").html(Vote_Id);
             }
             else if(idProof == 3)
            {
                var pass ='<div class="form-group col-md-6"> <label for="passport_no">Passport Number <font color="#FF0000">*</font></label><div class="input-group"> <div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="multiple_passport_no" name="multiple_passport_no" value="'+data.passport_no+'" placeholder="Enter Passport Number" data-parsley-errors-container="#passport_noError" data-parsley-required /> </div> <span id="passport_noError"><?php echo $err_passport_noError;?></span></div><div class="form-group col-md-6">  <label for="authority">Authority<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-arrows"></i></div><input type="text" class="form-control" id="multiple_authority" name="multiple_authority" placeholder="Enter Authority" value="'+data.authority+'" passport_expiry_datedata-parsley-errors-container="#authorityError" data-parsley-required /></div><span id="authorityError"><?php echo $err_authorityError;?></span></div><div class="form-group col-md-6"> <label for="passport_expiry_date">Passport Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_passport_expiry_date" name="multiple_passport_expiry_date" placeholder="dd-mm-yyyy" value="'+data.passport_expiry_date+'" data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required /></div><span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span></div>';
                
				 pass +='<div class="form-group col-md-6"> <label for="visa_expiry_date">Visa Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_visa_expiry_date" name="multiple_visa_expiry_date" placeholder="dd-mm-yyyy"  value="'+data.visa_expiry_date+'" data-parsley-errors-container="#visa_expiry_dateError" data-parsley-required /></div><span id="visa_expiry_dateError"><?php echo $err_visa_expiry_dateError;?></span></div> ';
                
				pass +='<div class="form-group col-md-6"> <label for="cform_expiry_date">C Form Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_cform_expiry_date" name="multiple_cform_expiry_date" placeholder="dd-mm-yyyy" value="'+data.cform_expiry_date+'" data-parsley-errors-container="#cform_expiry_dateError" data-parsley-required /></div><span id="cform_expiry_dateError"><?php echo $err_cform_expiry_dateError;?></span></div> ';
                
				
                $("#multipleappenddata").html(pass);

            }
            else if(idProof == 2)
            {
                var Aadhar = '<div class="form-group col-md-6"><label for="adhar_no">Aadhar Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="multiple_adhar_no" name="multiple_adhar_no" placeholder="Enter Adhar Number" value="'+data.adhar_no+'" data-parsley-errors-container="#adhar_noError" data-parsley-required /></div><span id="adhar_noError"><?php echo $err_adhar_noError;?></span></div>'; 
                $("#multipleappenddata").html(Aadhar);
            }else{
              $("#multipleappenddata").html('<div></div>');
            }




$("#multiple_voter_no").val(data.voter_no);
$("#multiple_adhar_no").val(data.adhar_no);
$("#multiple_passport_no").val(data.passport_no);


$("#multiple_authority").val(data.authority);
$("#multiple_passport_expiry_date").val(data.passport_expiry_date);
$("#multiple_visa_expiry_date").val(data.visa_expiry_date);
$("#multiple_cform_expiry_date").val(data.cform_expiry_date);




			//$('#guestNewaddeditModal').modal('show');//*/
					//$("#EditReservationModal").modal('show');
					//$('#EditReservationForm').html(result);	
					// $(".select3").select2({});
         
         
				}
		});
        
		}
	
	function removeGuestDetail(id_fo_reservations,id_mst_room_no_allocation,order_by_room,room_no,id_multiple_guest,id_folio){
		
		
		
		$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxremoveGuestDetail.php',

	   data: "id_fo_reservations="+id_fo_reservations+'&id_mst_room_no_allocation='+id_mst_room_no_allocation+"&order_by_room="+order_by_room+'&room_no='+room_no+"&id_multiple_guest="+id_multiple_guest+"&id_folio="+id_folio, 

	   success: function (result) {
		  $("#guestroomorder_"+order_by_room).html(result); 
		
		//$("#showGuestDeatils").html(result);
		

		}

	 

	});
		
		
	}
		
		function GetAddNewMultipleGuestDetail(edit_id_mst_guest,id_fo_reservations,id_mst_room_no_allocation,order_by_room,room_no,guest_type,id_folio){
			
			$('#guestMultipleeditModal').modal('show');
			$("#multiple_first_name").val('');
$("#multiple_last_name").val('');
$("#multiple_email").val('');
$("#multiple_mobile").val('');
$("#multiple_city").val('');
$("#multiple_EditCustomerID").val('');
$("#multiple_id_country").val('');
$("#multiple_Nametitle").val('');
$("#multiple_proof_type").val('');
$("#multiple_voter_no").val('');
$("#multiple_adhar_no").val('');
$("#multiple_passport_no").val('');


$("#multiple_authority").val('');
$("#multiple_passport_expiry_date").val('');
$("#multiple_visa_expiry_date").val('');
$("#multiple_cform_expiry_date").val('');




$("#multiple_edit_order_by_room").val(order_by_room);
$("#multiple_guest_id_edit_reservation").val(id_fo_reservations);
$("#multiple_guest_id_mst_room_no_allocation").val(id_mst_room_no_allocation);
$("#multiple_guest_room_no").val(room_no);
$("#multiple_guest_type").val(guest_type);
$("#multiple_id_folio").val(id_folio);

if(guest_type==1){
			$('#ownerguesthide').show();
			//$('#service_charge_per').attr('required','required');
		}	
		else{
			$('#ownerguesthide').hide();
			//$('#service_charge_per').removeAttr('required','required');
		}

		}
		
		
	function LoadGuestDetails(id_room_edit){
		
		var id_folio = $("#id_folio").val();
		
		$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxLoadGuestDetails.php',

	   data: "id_room_edit="+id_room_edit+'&id_folio='+id_folio, 

	   success: function (result) {
		   
		
		$("#showGuestDeatils").html(result);
		

		}

	 

	});
		}
		
		
		$(document).ready(function () {
    $(document).on('change', '#multiple_proof_type', function () {

      var idProof = $(this).val();

      if (idProof == 1) {
        var Vote_Id =
          '<div class="form-group col-md-6"><label for="voter_no">Voter Id Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="multiple_voter_no" name="multiple_voter_no" placeholder="Enter Voter Id Number" data-parsley-errors-container="#voter_noError" data-parsley-required /></div><span id="voter_noError"><?php echo $err_voter_noError;?></span></div>';
        $("#multipleappenddata").html(Vote_Id);
      } else if (idProof == 3) {
        var pass =
          '<div class="form-group col-md-6"> <label for="passport_no">Passport Number <font color="#FF0000">*</font></label><div class="input-group"> <div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="multiple_passport_no" name="multiple_passport_no" placeholder="Enter Passport Number" data-parsley-errors-container="#passport_noError" data-parsley-required /> </div> <span id="passport_noError"><?php echo $err_passport_noError;?></span></div><div class="form-group col-md-6">  <label for="authority">Authority<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-arrows"></i></div><input type="text" class="form-control" id="multiple_authority" name="multiple_authority" placeholder="Enter Authority" data-parsley-errors-container="#authorityError" data-parsley-required /></div><span id="authorityError"><?php echo $err_authorityError;?></span></div><div class="form-group col-md-6"> <label for="passport_expiry_date">Passport Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_passport_expiry_date" name="multiple_passport_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#passport_expiry_dateError" data-parsley-required /></div><span id="passport_expiry_dateError"><?php echo $err_passport_expiry_dateError;?></span></div>';

        pass +=
          '<div class="form-group col-md-6"> <label for="visa_expiry_date">Visa Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_visa_expiry_date" name="multiple_visa_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#visa_expiry_dateError" data-parsley-required /></div><span id="visa_expiry_dateError"><?php echo $err_visa_expiry_dateError;?></span></div> ';

        pass +=
          '<div class="form-group col-md-6"> <label for="cform_expiry_date">C Form Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="date" class="form-control datepicker" id="multiple_cform_expiry_date" name="multiple_cform_expiry_date" placeholder="dd-mm-yyyy" data-parsley-errors-container="#cform_expiry_dateError" data-parsley-required /></div><span id="cform_expiry_dateError"><?php echo $err_cform_expiry_dateError;?></span></div> ';


        $("#multipleappenddata").html(pass);

      } else if (idProof == 2) {
        var Aadhar =
          '<div class="form-group col-md-6"><label for="adhar_no">Aadhar Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="adhar_no" name="adhar_no" placeholder="Enter Adhar Number" data-parsley-errors-container="#adhar_noError" data-parsley-required /></div><span id="adhar_noError"><?php echo $err_adhar_noError;?></span></div>';
        $("#multipleappenddata").html(Aadhar);
      } else {
        $("#multipleappenddata").html('<div></div>');
      }
    });
  });	

  $(document).on('change', '#multiple_id_country', function(e) {
    var country = e.target.value;
    console.log(country);
    $.ajax({
      type : 'POST',
      url : '../actions/ajax/ajaxGetNationality.php',
      data : {countryId : country},
      success : function(data){
        $("#multiple_id_nationality").html(data); 
      }
    });
  })
</script>