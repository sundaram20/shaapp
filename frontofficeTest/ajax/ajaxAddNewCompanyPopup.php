<?php 
include_once("../../config/auto_loader.php");

?>
<style>
.input-group .form-control:last-child, .input-group-addon:last-child, .input-group-btn:first-child>.btn-group:not(:first-child)>.btn, .input-group-btn:first-child>.btn:not(:first-child), .input-group-btn:last-child>.btn, .input-group-btn:last-child>.btn-group>.btn, .input-group-btn:last-child>.dropdown-toggle {
    /* border-top-left-radius: 0; */
    /* border-bottom-left-radius: 0; */
}
#EditReservationModal .well {
    min-height: 80vh !important;
}
.error {
	color: #F00;
	font-size: 12px;
}
.deleteBox {
	width: 35px;
	height: 35px;
	background-color: #fff;
	/* White background by default */
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: background-color 0.3s;
	border: 1px solid #d2d6de !important;/* margin-top : 7px; */

}
.deleteBox:hover {
	background-color: #db3434;/* Blue color on hover */
}
.deleteBox:active {
	background-color: #2980b9;/* Darker blue color when clicked */
}
.deleteBox i {
	color: #db3434;
	/* Blue color for the icon by default */
	font-size: 15px;
	transition: color 0.3s;
}
.deleteBox:hover i {
	color: #fff;/* White color for the icon on hover */
}
.deleteBox:active i {
	color: #fff;/* White color for the icon when clicked */
}
#EditReservationModal .modal-dialog {
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
	width:50%;
}
#EditReservationModal {
	padding: 0px !important;
	min-height: 50vh !important;
}
#EditReservationModal .modal-content {
	min-height: 80vh !important;
}
.input-validation-error ~ .select2 .select2-selection__rendered {
	border: 1px solid red;
}
</style>
<script>
//$('.select2').select2();
$('.select2').each(function() {
    $(this).select2({
        dropdownParent: $(this).parent(), // fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function(e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})
</script>
<?php

	
	//debugData($_REQUEST);
	
	if($_REQUEST['BookingType']=='Edit'){
		
		
		$EditReservationId	=	addslashes(encryptor('decrypt',$_REQUEST['id']));
		$HotelSplit	=	explode('-',$_REQUEST['id_hotel']);


$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelSplit[0]."'");

$id_mst_hotels=$HotelSplit[0];

  $sql = "SELECT * FROM `".FO_RESERVATIONS."` where `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
	$_SESSION['eId']	=	$_REQUEST['eId'];
	$db->query($sql);
	//if($db->num_rows() > 0){
		$row = $db->fetch_object();
	//$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_hotels."'");
	$Guestname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_guest."'");
	$booking_date	=date('d-m-Y',strtotime($row->booking_date));
	$Checkindata =date('d-m-Y',strtotime($row->checkin));
	$checkoutDate	=	date('d-m-Y',strtotime($row->checkout));
	$BookingDate =date('d-m-Y',strtotime($row->doc_date));
	
	
	$room_tariff_price	= $row->sub_total;
	$discount	=	$row->discount;
	$total_addon_price	=$row->total_addon_price;
	$total_tax=$row->total_tax;
	$amount_received	=	$row->amount_received;
	$net_booking_amount	=	$row->net_booking_amount;
	$balance	= $row->balance;
	$BookingMdoc_no =$row->mdoc_no;
	 
	 
$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '".$row->id_mst_hotels."' " ;
$resneww = mysqli_query($connNew,$selectneww);
$dataArr='';
	
	
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
                $selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name;
					$id = $rownew->id;
					
				if($id == $rowOrderDetail->id_mst_room_types){
						$selected="selected";
					}
					else{
						$selected="";
					}
				$dataArr.=  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
				//$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
				}	
			}  
		//room type========================
		$ReservationTitle	='Remarks</span>';
			
		}else{}
		$id_mst_guest	=	selectColumn("fo_folio",'id_mst_guest'," WHERE `id` = '".$_REQUEST['id_folio']."'");
		
		
		
		
		
	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' GROUP BY order_by_room  ORDER BY id_mst_room_types,order_by_room, dated  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
		$Arrayroom_no=array();
		
		$folio_mdoc_no	=	selectColumn("fo_folio",'mdoc_no'," WHERE `id` = '".$_REQUEST['id_folio']."'");
			$Arrayroom_no['000_'.$id_mst_guest.'_000']=$folio_mdoc_no.' - Folio Guest';		
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$Arrayroom_no[$rowOrderDetail->id_mst_room_no_allocation.'_'.$rowOrderDetail->id_mst_guest.'_'.$rowOrderDetail->order_by_room]	=	'Room No : '.selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				}
		}
		
			
		
		
		
		
		
		
		
		
		
?>

<div id="companyby" class="well">
<div class="box-header with-border">
    <h3 class="box-title">Add Company </h3>
    <div class="box-tools pull-right">
      <button type="button" class="viewincPopUp_close btn btn-box-tool" data-dismiss="modal"><i class="fa fa-times"></i></button>
    </div>
  </div>
        
           <!-- form start -->
         <?php    $companySql = "  SELECT * FROM `".TBL_COMPANY."`
                WHERE `id_company` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
  $db->query($companySql);
  if($db->num_rows() > 0){
    $companyrow = $db->fetch_object();

  }
  ?>
          <form   id="companybypopupform" method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off" >
        <input type="hidden" id="EditCompanyID" name="EditCompanyID" value="<?php echo $companyrow->id_company; ?>" > 
        
           <div class="form-group has-error" align="center">
              <?php if($_SESSION['errorMsg']){?>
              <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
              <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
              <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
              <?php unset($_SESSION['successMsg']);}?>
            </div>
           
            <div class="box-body">
              <div class="row">
                <div class="form-group col-sm-4">
                  <label for="id_mst_attributes_company_group">Company Group<font color="#FF0000">*</font></label>
                  <select class="form-control select2"  style="width:100%" name="id_mst_attributes_company_group" id="id_mst_attributes_company_group"  data-parsley-errors-container="#id_mst_attributes_company_groupError" required="required" data-parsley-required >
                  <?php $categoryDropDown = '
									<option value="">Select Company  Group</option>';
												  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='company_group' ",' ORDER BY `field_value`');
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_mst_attributes_company_group'] == $resultCat->id){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_attributes_company_group == $resultCat->id){
															$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
													}
												  }
												 	echo $categoryDropDown .= '</select>';?>
                                                    <span id="id_mst_attributes_company_groupError"></span> 
                </div>
                <div class="form-group col-sm-4">
                  <label for="name">Company Name<font color="#FF0000">*</font></label>
                 <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#mylist" placeholder="Enter Company name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>" data-parsley-required >
	                  <ul id="mylist" style="display:none;">
	                    <?php  $resCat = selectSql(TBL_COMPANY," where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id`');
												  if($db->num_rows2($resCat)){
												  	while($resultCat = $db->fetch_object2($resCat)){
														$companyDropDown .= '<li>'.ucfirst($resultCat->name).'-'.ucfirst($resultCat->city).'</li>';
													}
												  }
												 	echo $companyDropDown;
						?> </div>


               <!-- <div class="form-group col-sm-4">
                  <label for="email">Email Id<font color="#FF0000">*</font></label>
                  <input type="email" class="form-control"  placeholder="Enter email id" id="email" name="email"  >
                  </div>-->
<div class="form-group col-sm-4">
                  <label for="secondary_email">Seconday Email</label>
                  <input type="text" class="form-control" placeholder="Enter seconday email id" id="secondary_email" name="secondary_email"  data-parsley-type="email"  >
                  <?php echo $err_email;?> </div>
                  
                  <div class="form-group col-sm-4">
                  <label for="fax">GST Number</label>
                  <input type="text" class="form-control" placeholder="Enter fax number" id="fax" name="fax" >
                  <?php echo $err_fax;?> </div>
                <div class="form-group col-sm-4">
                  <label for="address">Address</label>
                  <textarea class="form-control" name="address" id="address"  rows="1" placeholder="Enter Address">
</textarea>
                  <?php echo $err_address;?> </div>
                  
                   <div class="form-group col-sm-4">
                  <label for="name">City<font color="#FF0000">*</font></label>
                  <input autocomplete="off" type="text" class="form-control awesomplete" data-list="#citylist" placeholder="Enter City" id="city" name="city" data-parsley-required >
                  <ul id="citylist" style="display:none;">
                    <?php  //$resCat = selectSql(TBL_COMPANY,'distinct',"  where status=1  and id_shop='".addslashes($_SESSION['shop'])."'",' ORDER BY `id_company`');

                    $citySql="SELECT DISTINCT city from `".TBL_COMPANY."` WHERE  status=1  and id_shop='".addslashes($_SESSION['shop'])."' ORDER BY `id_company`";

    $resCat = mysqli_query($connNew,$citySql);

                      


                        if($db->num_rows2($resCat)){
                          while($resultCat = $db->fetch_object2($resCat)){
                          $cityDropDown .= '<li>'.ucfirst($resultCat->city).'</li>';
                        }
                        }
                       echo $cityDropDown;
                        
          ?>
                  </ul>
                   </div>
                   <div class="form-group col-sm-4">
                  <label for="id_country" >Country<font color="#FF0000">*</font></label>               
                               <select class="form-control select2" style="width:100%" name="id_mst_country_lang" id="id_mst_country_lang" data-parsley-errors-container="#countryError" required="required" data-parsley-required>
	                <option value="">Select Country</option>
	                <?php 
									$resCat = selectSql(TBL_COUNTRY_LANG,"where id_lang='1' ",' ORDER BY `name`');
												  if($db->num_rows2($resCat)){
													while($resultCat = $db->fetch_object2($resCat)){
														if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){
															$selected = 'selected="selected"';
														}elseif($row->id_mst_country_lang == $resultCat->id_country){
														$selected = 'selected="selected"';
														}elseif(110 == $resultCat->id_country){
														$selected = 'selected="selected"';
														}else{
															$selected = '';
														}
														$countryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->name).'</option>';
													}
												  }
												  echo $countryDropDown;
									
									 ?>
								<?php if($row->id_mst_country_lang == 10000){?>
								<option value="10000" selected="selected">Other</option>
							   <?php } else{ ?>
							   		<option value="10000">Other</option>
							   <?php } ?>
	              </select>
	              <span id="countryError"></span>  </div>
                  
                  
                 <div class="form-group col-sm-4">
                  <label for="id_state">State <font color="#FF0000">*</font></label>
                <div class="input-group"> 
	                    <div class="input-group-addon">
	                        <i class="fa fa-adjust"></i> 
	                    </div>
	                      <div id="state"> 
	                       <select class="form-control select2"  name="id_mst_state" id="id_mst_state"  style="width:100%" data-parsley-errors-container="#id_mst_stateError" data-parsley-required><option value="">Select state</option>
	                        <?php  
	                           $resCat = selectSql(TBL_STATE," where id_mst_country_lang='110' ",' ORDER BY `name` ');
	                          if($db->num_rows2($resCat)){
	                            while($resultCat = $db->fetch_object2($resCat)){  
	                                if($row->id_mst_state == $resultCat->id_state){

	                                  $selected = 'selected="selected"';

	                                }else{

	                                  $selected = '';

	                                }

	                                $stateDropDown .= '<option '.$selected.' value="'.$resultCat->id_state.'">'.ucfirst($resultCat->name).'</option>';
	                            }
	                          }
	                           echo $stateDropDown;
	                          ?>
	                      </select>
                           
	                    </div>
	                </div><span id="id_mst_stateError"></span> </div> 
                    
                    
                    <div class="form-group col-sm-4">
                  <label for="postcode">Pincode</label>
                  <input type="text" class="form-control" placeholder="Enter pincode" id="postcode" name="postcode" >
                  <?php echo $err_postcode;?> </div>
                  
              

<div class="form-group  col-sm-4">
                  <label for="phone">Phone Number</label>
                  <input type="text" class="form-control" placeholder="Enter phone number" id="phone" name="phone"  >
                  <?php echo $err_phone;?> </div>
                  </div>
             <div class="row">
              

                   
                </div><!--end of row-->  
 
                  
        

              <div class="row">
                
                
              
            <!--   </div>
              <div class="row">-->
                

                   
               <!-- <div class="form-group col-sm-4">
                  <label for="city">City<font color="#FF0000">*</font></label>
                  <input type="text" class="form-control" placeholder="Enter city" id="city" name="city" value="<?php if($_POST) echo $_POST['city'];else echo $row->city;?>" data-parsley-required>
                  <?php echo $err_city;?> </div>
              -->


                <!--<div class="form-group col-sm-4">

                <label for="city">City </label>

                <select class="form-control select2 itemName" name="city" id="city"   >

                </select>
             </div> --> 
  
            </div>
            
              
              </div>
        <br />
    
     
            <div class="form-group col-sm-12" align="left">
              <input  type="button" class="btn btn-default" onClick="savenewCompanyPopupform();" value="Save">
              
              <button type="button" class="viewincPopUp_close btn btn-default" data-dismiss="modal">Close</button>
            </div>

          </form><br />
    </div>


<!-------FOLIO GUEST-- END------------------------------------------>
























<script>


function savenewCompanyPopupform(){  

	var companyId = $("#company_id").val();
	if(companyId==undefined){
	  companyId = $("#id_company").val();
	}
  
	var form=$("#companybypopupform");
  
	if(form.parsley().validate()){
  
	$('.loading').show(); 
  
	$.ajax({
  
	   type: "POST",
  
	   url: 'ajax/ajaxSavenewCompany.php',
  
	   data: form.serialize()+"&id_company="+companyId, 
  
	   success: function (result) {
  
		if(result!=''){
		  console.log(result);
		  $('#id_bill_to_company').empty();
		$('#id_bill_to_company').html(result);
  
		
  
		$("#companybypopupform")[0].reset();
	//	$('#viewincPopUp_close').click();
		 $("#EditReservationModal").modal("hide");
  
		}
  
	  },
  
	  complete: function(){
  
	  $('.loading').hide();
  
	  }
  
	});
  
	return false;
  
	}
  
  }




function saveChangeAddRemarks(){

	var id_mst_hotels = $("#id_mst_hotels_new").val();
	var id_mst_company_new = $("#id_mst_company_new").val();
	var id_mst_company_contacts_new = $("#id_mst_company_contacts_new").val();
	var res_bookingStatus_new = $("#res_bookingStatus_new").val();
	var id_mst_guest_form = $("#id_mst_guest_form").val();
	
	

	
   
  
   
	var form=$("#saveReservationDateform");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxsaveAddRemarks.php',

	   data: form.serialize(), 

	   success: function (result) {
		   
		var response = JSON.parse(result);
		alert(response.message);
		$("#EditReservationModal").modal("hide");
		
		if(response.id_follio!='0'){
		InvoiceDetails(response.id_follio);   
		}
		   
//alert(result);
		//  $("#EditReservationModal").modal("hide");

		},

	  complete: function(){

		$('.loading').hide();

	  }

	});

	return false;

	}

	}
	

		
  $(document).on('change', '#id_mst_country_lang', function(){
            var otherCountry  = $(this).val();
            if(otherCountry == 10000){
              var countryDiv = `<label col="other_country">Other Country<font color="#FF0000">*</font></label><input type="text" name="other_country" id="other_country" class="form-control" placeholder="Enter Country Name" value="<?php if($_POST) echo $_POST['other_country']; else echo $row->other_country;?>" data-parsley-errors-container="#other_countryError" data-parsley-required /><span id="other_countryError"></span>`;

              $("#otherCountryDiv").html(countryDiv);

            }else{
              $("#otherCountryDiv").html('<div></div>');
            }
            $.ajax({
              type : 'POST',
              url : '../actions/ajax/ajaxGetState.php',
              data : {countryId : otherCountry},
              success : function(data){
                $("#id_mst_state").html(data); 
                if($("#id_mst_state").val() != 10000)
                {
                  $("#otherStateDiv").html('<div></div>');
                }
              }
            });
            
          });	
</script>

 <script>
	function AddRemarksTextBox() {
	
	
	   
    var add1 = 0;
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);




days = DiffDays;
    var DiffDays = Math.round(days);
    var uncodeRoomCode = Math.floor(Math.random() * 1000) + 5;
	var roomCount=0;
    var CategoryCount=0;
		
		
       

          
           
            var uncode = Math.floor(Math.random() * 500) + 1; //Math.floor(Math.random() * 15);


           // var div = document.createElement('DIV');
            //div.setAttribute('id', uncode);
            row = GetDynamicRemarksTextBox(uncode, uncodeRoomCode);
				
				CategoryCount ='5';
            

            $('#tableAddRemarkBodyData').append(row);
            document.getElementById(uncodeRoomCode).style.borderBottom = "solid #7FB3E0";

            



        //$('#order_by_roomRowCount').val(order_by_roomRowCount);
		//order_by_roomRowCount=Number(order_by_roomRowCount) +  Number(add);
       
	
	
	
}

	   
	function  GetDynamicRemarksTextBox(uncode,  uncodeRoomCode) { 
    //var res_date = $("#res_date").val();

   
	 var res_room_type_new='0';
	 
	 var cssborder='style="border-top:3px solid #7FB3E0;"';

    var Utext = '<tr data-reservation-id="' + uncodeRoomCode + '" '+cssborder+'>';
	
	
  	
		Utext += '<td style="width: 180px;"><select name="PostChargesDataArray[' + uncodeRoomCode +
        '][id_remarks]" id="id_remarks_' + uncode +
        '" data-parsley-required  class="form-control id_remarks_' + uncode +
        '" ><option value="">Select Remark Type</option><?php echo $remark_type; ?></select></td>';
		
		Utext +=
        '<td ><input type="text" class="form-control" id="res_remark" name="PostChargesDataArray[' + uncodeRoomCode + '][res_remark]" value=""></td>';
	
		 Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBoxRemarks(' + uncodeRoomCode +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
   
    Utext += '</tr>';



    return Utext;
}
     function RemoveTextBoxRemarks(uncode) {



const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());

    /*const parentElement = document.getElementById('TextBoxContainerForm');

    // Get the child element
    const childElement = document.getElementById(uncode);

    // Check if both parent and child elements exist
    if (parentElement && childElement) {
        // Remove the child element from the parent
        parentElement.removeChild(childElement);
        TotoalTarffiData();
    } else {
        console.log('Parent or child element not found.');
    }*/
}  







function RemoveDetailRecordRemarks(uncode) {
    //serializeArray();
    bootbox.confirm({
        title: "Remove",
        message: "Do you want to Remove this Remark?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function(result) {
            if (result == true) {
                //var myControls = saveReservationDateform.elements['p_id[]'];
                //$("#saveReservationDateform").serializearray()
                var id_reservation_detailArray = $("#id_reservation_detailArray_" + uncode).serialize();
				
				const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
                $.ajax({

                    type: "GET",

                    url: 'ajax/ajaxRemoveDetailRemarks.php',

                    data: 'uncode=' + uncode + '&' + id_reservation_detailArray,

                    success: function(result) {

                        



                    }

                });
            }
        }
    });

}
	














    </script>