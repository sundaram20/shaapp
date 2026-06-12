<?php 		 $id_doc_type_configuration='801';

	include_once("functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';
	$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	

//debugData($docConfig);

                  ?>

<div class="row">
  
  <div class="col-xs-12"> 
    <!-- form start -->
    <form id="reservationDetailss" method="post" class="form" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
      <div class="box box-success">
        <div class="box-header with-border bg-color-success">
          <h3 class="box-title">Main Information</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 right_border">
              <div class="form-horizontal">
                <div class="form-group row">
                  <input type="hidden" name="res_room" id="res_room" />
                  <input type="hidden" name="id_mst_hotels" id="id_mst_hotels" value="0" />
                  <input type="hidden" class="form-control" id="parentId" name="parentId" />
                  <input type="hidden" value="" name="eId" id="eId" />
                  <label for="res_bookingNo" class="col-sm-2 col-md-3 deskpr">Booking Number<?php echo $prefix.$inc_no.$suffix ?></label>
                  <div class="col-sm-10 col-md-9 deskpl">
                    <input type="text" class="form-control" id="res_bookingNo" name="res_bookingNo" value="<?php echo addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix']); ?>" readonly />
                    <input type="hidden" name="id_doc_type_configuration" value="<?php echo addslashes($docConfig['id_doc_type_configuration']); ?>" id="id_doc_type_configuration">
                  </div>
                </div>
                <div class="form-group row">
                  <label for="res_bookingDate" class="col-sm-2 col-md-3 deskpr">Booking Date</label>
                  <div class="col-sm-10 col-md-9 deskpl">
                    <input type="text" class="form-control datepicker" value="<?php echo date('d-m-Y');?>" id="res_bookingDate" name="res_bookingDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_bookingDateError" data-parsley-required />
                    <span id="res_bookingDateError"><?php echo $err_res_bookingDateError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_bookingStatus" class="col-sm-2 col-md-3 deskpr">Booking Status</label>
                  <div class="col-sm-10 col-md-9 deskpl"> 
                    <!-- <input type="text" class="form-control" id="res_bookingStatus" name="res_bookingStatus" /> -->
                    <select class="form-control select2" style="width: 100%;" id="res_bookingStatus" name="res_bookingStatus" data-parsley-errors-container="#res_bookingStatusError" data-parsley-required>
                      <?php 
																$categoryDropDown = '
																   <option value="">Select Booking Status</option>';   
																  $resCat = selectSql('fo_booking_status'," where status='1'  ",' ');
																  if($db->num_rows2($resCat)){
																	while($resultCat = $db->fetch_object2($resCat)){
																		if($_REQUEST['booking_status'] == $resultCat->id){
																			$selected = 'selected="selected"';
																		}elseif($row->booking_status == $resultCat->id){
																			$selected = 'selected="selected"';
																		}else{
																			$selected = '';
																		}
																		$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
																	}
																  }
																	echo $categoryDropDown ;
															 ?> 
                    </select>
                    <span id="res_bookingStatusError"><?php echo $err_res_bookingStatusError;?></span> </div>
                </div>
                <div id="res_bookingList"></div>
              </div>
            </div>
            <div class="col-md- col-sm-6 col-xs-12">
              <div class="form-horizontal">
                <div class="form-group row">
                  <label for="res_hotelName" class="col-sm-2 col-md-2  deskpr">Hotel Name</label>
                  <div class="col-sm-10 col-md-10 deskpl">
                    <input type="text" class="form-control" id="res_hotelName" name="res_hotelName" />
                    
                    <!-- <select class="form-control select2" style="width: 100%;" id="res_hotelName" name="res_hotelName" data-parsley-errors-container="#res_hotelNameError" data-parsley-required>
                                                              <option selected="selected" value="">Select Hotel</option>
                                                          </select>  --> 
                    <span id="res_hotelNameError"><?php echo $err_res_hotelNameError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_checkinDate" class="col-sm-2 col-md-2 deskpr">Check In</label>
                  <div class="col-sm-10 col-md-10 deskpl">
                    <input type="text" class="form-control datepicker" id="res_checkinDate" name="res_checkinDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_checkinDateError" data-parsley-required />
                    <span id="res_checkinDateError"><?php echo $err_res_checkinDateError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_checkOutDate" class="col-sm-2 col-md-2 deskpr">Check Out</label>
                  <div class="col-sm-10 col-md-10 deskpl">
                    <input type="text" class="form-control datepicker" id="res_checkOutDate" name="res_checkOutDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_checkOutDateError" data-parsley-required />
                    <span id="res_checkOutDateError"> <?php echo $err_res_checkOutDateError;?> </span> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="box box-info">
        <div class="box-header with-border bg-color-info">
          <h3 class="box-title">Guest Information</h3>
        </div>
        <div class="box-body">
          <div class="row">
            <div class="col-md-5 col-sm-5 col-xs-12 right_border">
              <div class="form-horizontal">
                <div class="form-group row">
                  <label for="res_bookingType" class="col-sm-2 col-md-3 deskpr">Bussiness source</label>
                  <div class="col-sm-10 col-md-9 deskpl">
                    <select onchange="source_labelchange();" class="form-control select2 bs_select2"  style="width:100%" name="id_mst_attributes_company_group" id="id_mst_attributes_company_group" data-parsley-required data-parsley-errors-container="#err_default_group">
                     
                      <?php 
																   $categoryDropDown = '
																   <option value="">Select Company  Group</option>';
																  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='company_group' ",' ORDER BY `field_value` ');
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
																	echo $categoryDropDown ;
															 ?>
                    </select>
                    <span id="res_bookingTypeError"><?php echo $err_res_bookingTypeError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label id="sourcename1" for="res_source" class="col-sm-2 col-md-3 deskpr"   id="source_label">
                  Source
                  </label>
                  <div class="col-sm-10 col-md-9 deskpl">
                    <select class="form-control select2 res_select2"  style="width: 100%;" id="res_source" name="id_mst_company" data-parsley-errors-container="#res_sourceError" data-parsley-required >
                      <option value="">Select Company Name</option>
                    </select>
                    
                    
                    <!--  <input type="text" class="form-control" name="res_source" id="res_source" placeholder="Enter Company Name" onkeyup="source_data()" />
															  <div id="resource_list"></div>--> 
                    <span id="res_sourceError"><?php echo $err_res_sourceError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label id="booker" for="res_bookerName2" class="col-sm-2 col-md-3 deskpr">Booker By</label>
                  <div class="col-sm-9 col-md-8 deskpl">
                    <div class="input-group">
                     
                     
                      <select class="form-control select2 bookername_select2" style="width: 100%;" id="res_bookerName" name="res_bookerName" data-parsley-errors-container="#res_bookerNameError" data-parsley-required>
                        
                      </select>
                      <div class="input-group-addon" data-toggle="modal" data-target="#bookereditModal"> <a href="javascript:void(0);" style="color:black;" id="res_bookerAddId"><i class="fa fa-plus"></i> </a> </div>
                    </div>
                    <span id="res_bookerNameError"><?php echo $err_res_bookerNameError;?></span> </div>
                  <div onclick="bookeredit()" class="col-md-1 col-sm-1 col-xs-2 deskpl" > <a class="btn o-btn btn-sm" id="res_bookerEditId"><i class="fa fa-edit"></i></a> </div>
                </div>
              </div>
            </div>
            <div class="col-md-7 col-sm-7 col-xs-12">
              <div class="form-horizontal">
                <div class="form-group row">
                  <label for="res_rateType" class="col-sm-2 col-md-2 deskpr">Rate Type</label>
                  <div class="col-sm-10 col-md-10 deskpl">
                    <select class="form-control select2" style="width: 100%;" id="res_rateType" name="res_rateType" >
                      <option  value="">Select Rate Type</option>
                      <option value="1">Adoc</option>
                      <option value="2">Contract</option>
                    </select>
                    <span id="res_rateTypeError"><?php echo $err_res_rateTypeError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_rateLetter" class="col-sm-2 col-md-2 deskpr">Rate Letter</label>
                  <div class="col-sm-8 col-md-8 deskpl">
                    <select class="form-control select2" style="width: 100%;" id="res_rateLetter" name="res_rateLetter" >
                      <option selected="selected" value="">Select Rate Letter</option>
                      <option value="1">Rate Letter1</option>
                      <option value="2">Rate Letter2</option>
                    </select>
                    <span id="res_rateLetterError"><?php echo $err_res_rateLetterError;?></span> </div>
                  <div class="col-sm-2 col-md-2 col-xs-2" style="padding-left: 0px;">
                    <button type="button" class="btn o-btn btn-sm"><i class="fa fa-eye"></i> View</button>
                  </div>
                </div>
                <div class="form-group row">
                  <label for="res_guestName" class="col-sm-2 col-md-2 deskpr" >Guest Name</label>
                  <div class="col-sm-9 col-md-9 col-xs-9 deskpl" >
                    <div class="input-group">
                      <select class="form-control select2 guestName" name="id_mst_guest" id="id_mst_guest" style="width: 100%;">
                      </select>
                      <div class="input-group-addon" data-toggle="modal" data-target="#guestaddeditModal"> <a href="javascript:void(0);" style="color:black;" id="res_guestAddId"><i class="fa fa-plus"></i> </a> </div>
                    </div>
                  </div>
                  <div onclick="guestaddedit()" class="col-md-1 col-sm-1 col-xs-2 deskpl"> <a class="btn o-btn btn-sm" id="res_guestEditId"><i class="fa fa-edit"></i></a> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <input type="hidden" class="form-control parsley-error" name="foodval" id="foodval" value="0">
      <p id="popupval1"></p>
      <p id="popupval2"></p>
      <p id="popupval3"></p>
      <div class="box box-success">
        <div class="box-header with-border bg-color-success">
          <h3 class="box-title">Rooms and Rates </h3>
          &nbsp; 
          <!-- <a href="javascript:void(0)" onclick="roomsRates();" style="color:white"><i class="fa fa-plus"></i></a>-->
          <input type="text" class="form-control parsley-error" name="roomtype" id="roomtype" style="display:none;">
          <i id="roomsRates" class="fa fa-plus" style="color:white"></i> </div>
        <div class="box-body">
          <div class="well table-responsive">
            <table class="table order-list1 table-hover">
              <thead id="roomhideandshow">
                <tr>
                  <th>Room Type</th>
                  <th>Plan</th>
                  <th>No. of Rooms</th>
                  <th>Adult Per Room</th>
                  <th>Child below 5 years</th>
                  <th>Child above 5 years</th>
                  <th>Tariff Per Room <br/>
                    per Nights</th>
                  <th>Taxes</th>
                  <th>Tariff Per Room <br/>
                    inclusive Taxes</th>
                  <th>Charges <br/>
                    Per Night</th>
                </tr>
              </thead>
				 <div id="Theader"></div>
              <tbody>
              <input type="hidden" name="rrcounter" id="rrcounter" value="0" text="">
              </tbody>
              
            </table>
          </div>
        </div>
      </div>
      <div class="box box-info ">
        <div class="box-header with-border bg-color-info">
          <h3 class="box-title">Add Ons </h3>
          &nbsp; <i id="addons_row" class="fa fa-plus" style="color:white"></i> </div>
        <div class="box-body">
          <div class="well  table-responsive">
            <table class="table order-list2 table-hover">
              <thead id="addonshideandshow">
                <tr>
                  <th>Item</th>
                  <th>Addional Description</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th>Rate</th>
                  <th>Tax%</th>
                  <th>Tax Value</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
              <input type="text" name="addonscounter" id="addonscounter" value="" hidden="">
              </tbody>
              
            </table>
          </div>
        </div>
      </div>
      <div class="box box-success">
        <div class="box-header with-border bg-color-success">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <h3 class="box-title"> Charges Summery</h3>
            </div>
          </div>
        </div>
        <div class="box-body ">
          <div class="well table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <th>Tariff Amount</th>
                  <th>Discount</th>
                  <th>Add On Amount</th>
                  <th>Taxes</th>
                  <th>Total</th>
                  <th>Payment <br/>
                    Received</th>
                  <th>Balance</th>
                  <th>Payment <br/>
                    Status</th>
                </tr>
              </thead>
              <tbody id="">
                <tr>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="subtotal" name="subtotal" value="0" style="width:80px;margin-left:-14px;" readonly />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="total_discount" name="total_discount"style="width:80px;margin-left:-14px;" value="0" readonly />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="additional_charges" name="additional_charges" value="0" style="width:80px;margin-left:-14px;" readonly />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="total_taxes" name="total_taxes" value="0" style="width:80px;margin-left:-14px;" readonly />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="total" name="total" style="width:80px;margin-left:-14px;" value="0" readonly  />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="payment_received"  name="payment_received" value="0" readonly style="width:80px;margin-left:-14px;"  />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6">
                      <input type="text" class="form-control" id="balance" name="balance" value="0" readonly style="width:80px;margin-left:-14px;"  />
                    </div></td>
                  <td><div class="col-sm-6 col-md-6" style="margin-left:-14px;">
                      <?php 
														 $categoryDropDown = '<select class="form-control select2" style="width:150px"   name="res_paymentStatus" id="res_paymentStatus" data-parsley-required data-parsley-errors-container="#err_default_group">
														   <option value="">Select Please</option>';
														   
												//	echo "select * from mst_attributes where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='payment_status'  ";	   
														   
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='payment_status' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_paymentStatus'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->id_mst_attributes_payment_status == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                      
                      <!-- <select class="form-control  select2" id="res_paymentStatus" name="res_paymentStatus" data-parsley-errors-container="#res_paymentStatusError" style="width:150px" >
                                                            <option selected="selected" value="">Select Payment</option>
                                                        </select>  --> 
                      <span id="res_paymentStatusError"><?php echo $err_res_paymentStatusError;?></span> </div></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      
      <!--<div class="box box-success">
                                    <div class="box-header with-border bg-color-success">
                                      <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                          <h3 class="box-title"> Charges Summery</h3>
                                        </div>
                                      </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                                            
                                            <div class="box-body right_border">
                                                <div class="form-horizontal">
                                                   <div class="form-group row">
                                                      <label for="res_tariffamt" class="col-sm-3 col-md-3 col-form-label">Tariff Amount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_tariffamt" name="res_tariffamt" value="5000" readonly />
                                                      </div>
                                                   </div>
													
                                                    <div class="form-group row">
                                                      <label for="res_discount" class="col-sm-3 col-md-3 col-form-label">Discount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_discount" name="res_discount" value="500"/>
                                                      </div>
                                                   </div>
												  
												  
                                                    <div class="form-group row">
                                                      <label for="res_balance" class="col-sm-3 col-md-3 col-form-label">Total</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="total" name="total"  />
                                                      </div>
                                                    </div>
													
													 <div class="form-group row">
                                                      <label for="res_balance" class="col-sm-3 col-md-3 col-form-label">Balance</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="balance" name="balance"  />

                                                      </div>
                                                    </div>
												  
                                              </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                           <div class="box-body">
                                             <div class="form-horizontal">
                                               <div class="form-group row">
                                                      <label for="res_addonamt" class="col-sm-3 col-md-3 col-form-label">Add On Amount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_addonamt" name="res_addonamt" value="5000" readonly />
                                                      </div>
                                                    </div>
                                                 <div class="form-group row">
                                                      <label for="res_taxes" class="col-sm-3 col-md-3 col-form-label">Taxes</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_taxes" name="res_taxes" value="5000" readonly />
                                                      </div>
                                                    </div>
													
													 <div class="form-group row">
                                                      <label for="res_taxes" class="col-sm-3 col-md-3 col-form-label">Payment Received</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="payment_received" name="payment_received"  />
                                                      </div>
                                                    </div>
													
													 <div class="form-group row">
                                                      <label for="res_paymentStatus" class="col-sm-3 col-md-3 col-form-label">Payment Status</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <select class="form-control  select2" style="width:100%" id="res_paymentStatus" name="res_paymentStatus" data-parsley-errors-container="#res_paymentStatusError">
                                                            <option selected="selected" value="">Select Payment</option>
                                                        </select>
                                                        <span id="res_paymentStatusError"><?php echo $err_res_paymentStatusError;?></span>
                                                      </div>
                                                    </div>
													
                                            </div> 
                                          </div>
                                        </div>
                                    </div>
                                </div> -->
      
      <div class="box box-info ">
        <div class="box-header with-border bg-color-info">
          <h3 class="box-title">Payment </h3>
          &nbsp; <i id="payments_row" class="fa fa-plus" style="color:#fff;"></i> </div>
        <div class="box-body">
          <div class="well  table-responsive">
            <table class="table order-list3 table-hover">
              <thead id="paymentshideandshow">
                <tr>
                  <th>Mode</th>
                  <th>Details</th>
                  <th>Amount</th>
                </tr>
              </thead>
              <tbody>
              <input type="text" name="paymentscounter" id="paymentscounter" value="" hidden="">
              </tbody>
              
            </table>
          </div>
        </div>
      </div>
      <div class="box box-success">
        <div class="box-header with-border bg-color-success">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <h3 class="box-title">Misc Details</h3>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-6 col-xs-12">
            <div class="box-body right_border">
              <div class="form-horizontal">
                <div class="form-group row">
                  <label for="res_bookingthrough" class="col-sm-3 col-md-3 deskpr">Booking Through</label>
                  <div class="col-sm-6 col-md-9 deskpl">
                  <select class="form-control select2"  style="width:100%" name="res_bookingthrough" id="res_bookingthrough" >
                    <?php 
														 $categoryDropDown = '
														   <option value="">Select Please</option>';
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='booking_through' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_bookingthrough'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->res_bookingthrough == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                    
                  
                    <span id="res_bookingthroughError"><?php echo $err_res_bookingthroughError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_segment" class="col-sm-3 col-md-3 deskpr">Segment</label>
                  <div class="col-sm-6 col-md-9 deskpl">
                    <?php 
														 $categoryDropDown = '<select class="form-control select2"  style="width:100%" name="res_segment" id="res_segment" >
														   <option value="">Select Please</option>';
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='segment' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_segment'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->id_mst_attributes_segments == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                    
                    <!--  <select class="form-control  select2" style="width:100%" id="res_segment" name="res_segment" data-parsley-errors-container="#res_segmentError" data-parsley-required>
                                                          <option selected="selected" value="">Select please</option>
                                                          <option value="FIT">FIT</option>
                                                          <option value="GIT">GIT</option>
                                                          <option value="DFIT">DFIT</option>
                                                      </select>  --> 
                    <span id="res_segmentError"><?php echo $err_res_segmentError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_bookingsourcee" class="col-sm-3 col-md-3  deskpr">Booking Source</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <?php 
														 $categoryDropDown = '<select class="form-control select2"  style="width:100%" name="res_bookingsourcee" id="res_bookingsourcee" >
														   <option value="">Select Please</option>';
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='booking_source' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_bookingsourcee'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->id_mst_attributes_booking_source == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                    <!--  <select class="form-control  select2" style="width:100%" id="res_bookingsourcee" name="res_bookingsourcee" data-parsley-errors-container="#res_bookingsourceeError" data-parsley-required>
                                                          <option selected="selected" value="">Select please</option>
                                                          <option value="WELCOME HERITAGE">WELCOME HERITAGE</option>
                                                          <option value="ITC">ITC</option>
                                                          <option value="UNIT">UNIT</option>
                                                      </select>  --> 
                    <span id="res_sbookingsourceeError"><?php echo $err_res_bookingsourceeError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_specialrequest" class="col-sm-3 col-md-3  deskpr">Special Requests By Guest</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <textarea class="form-control" name="res_specialrequest" id="res_specialrequest" disableddata-parsley-errors-container="#res_specialrequestError" ><?php echo $row->special_requests;?></textarea>
                    <span id="res_specialrequestError"><?php echo $err_res_specialrequestError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_remarks" class="col-sm-3 col-md-3  deskpr">Remarks(Internal)</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <textarea class="form-control" name="internal_remarks" id="internal_remarks" disableddata-parsley-errors-container="#internal_remarksError" ></textarea>
                    <span id="internal_remarksError"><?php echo $err_internal_remarksError;?></span> </div>
                </div>
                <div class="form-group row">
                  <label for="res_amendment" class="col-sm-3 col-md-3  deskpr">Amendment in</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <?php 
														 $categoryDropDown = '<select class="form-control select2"  style="width:100%" name="res_amendment" id="res_amendment" >
														   <option value="">Select Please</option>';
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='amendment_in' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_amendment'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->res_amendment == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                    
                    <!-- <select class="form-control  select2" style="width:100%" id="res_amendment" name="res_amendment" data-parsley-errors-container="#res_amendmentError" data-parsley-required>
                                                          <option selected="selected" value="">Select please</option>
                                                          <option value="Amendment1">Amendment1</option>
                                                          <option value="Amendment2">Amendment2</option>
                                                          <option value="Amendment3">Amendment3</option>
                                                      </select>  --> 
                    <span id="res_amendmentError"><?php echo $err_res_bookingsourceeError;?></span> </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-6 col-xs-12 ">
            <div class="box-body ">
              <div class="form-horizontal">
                <div class="form-group row">
                  <label for="res_pickuprequired" class="col-sm-3 col-md-3  deskpr">Pickup Required</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <select class="form-control  select2" style="width:100%" id="res_pickuprequired" name="res_pickuprequired" data-parsley-errors-container="#res_pickuprequiredError" data-parsley-required>
                      <!--  <option selected="selected" value="Yes">Select please</option> -->
                      <option value="1">Yes</option>
                      <option value="0">No</option>
                    </select>
                  </div>
                </div>
                <div id="hidee">
                  <div class="form-group row">
                    <label for="res_modeoftravel" class="col-sm-3 col-md-3  deskpr">Mode of Travel</label>
                    <div class="col-sm-6 col-md-9  deskpl">
                      <?php 
														 $categoryDropDown = '<select class="form-control select2"  style="width:100%" name="res_modeoftravel" id="res_modeoftravel" data-parsley-errors-container="#err_default_group">
														   <option value="">Select Please</option>';
														  $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='mode_of_travel' ",' ORDER BY `field_value` ');
														  if($db->num_rows2($resCat)){
															while($resultCat = $db->fetch_object2($resCat)){
																if($_REQUEST['res_modeoftravel'] == $resultCat->id){
																	$selected = 'selected="selected"';
																}elseif($row->res_modeoftravel == $resultCat->id){
																	$selected = 'selected="selected"';
																}else{
																	$selected = '';
																}
																$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
															}
														  }
															echo $categoryDropDown .= '</select>';
													?>
                      
                      <!-- <select class="form-control  select2" style="width:100%" id="res_modeoftravel" name="res_modeoftravel" data-parsley-errors-container="#res_modeoftravelError" data-parsley-required><option selected="selected" value="">Select please</option><option value="By Air">By Air</option><option value="By Train">By Train</option><option value="By Road">By Road</option></select> --> 
                      
                      <span id="res_modeoftravelError"><?php echo $err_res_modeoftravelError;?> </span></div>
                  </div>
                  <div class="form-group row">
                    <label for="res_pickupdetails" class="col-sm-3 col-md-3  deskpr">Pickup Details</label>
                    <div class="col-sm-6 col-md-9  deskpl">
                      <input type="text" class="form-control" id="res_pickupdetails" name="res_pickupdetails"placeholder="Enter Pickup Details" disableddata-parsley-errors-container="#res_pickupdetailsError" />
                      <span id="res_pickupdetailsError"><?php echo $err_res_pickupdetailsError;?></span></div>
                  </div>
                  <div class="form-group row">
                    <label for="res_arrivingfrom" class="col-sm-3 col-md-3  deskpr">Arriving from</label>
                    <div class="col-sm-6 col-md-9  deskpl">
                      <input type="text" class="form-control" id="res_arrivingfrom" name="res_arrivingfrom"placeholder="Enter Arriving from" disableddata-parsley-errors-container="#res_arrivingfromError" />
                      <span id="res_arrivingfromError"><?php echo $err_res_arrivingfromError;?></span></div>
                  </div>
                  <div class="form-group row">
                    <label for="res_arrivingtime" class="col-sm-3 col-md-3  deskpr">Arriving time</label>
                    <div class="col-sm-6 col-md-9  deskpl">
                      <select name="res_arrivingtime" id="res_arrivingtime" class="form-control select2" style="width:100%" >
                        <option value="">Select Time</option>
                        <option value="12.00am">12.00am</option>
                        <option value="12.15am">12.15am</option>
                        <option value="12.30am">12.30am</option>
                        <option value="12.45am">12.45am</option>
                        <option value="1.00am">1.00am</option>
                        <option value="1.15am">1.15am</option>
                        <option value="1.30am">1.30am</option>
                        <option value="1.45am">1.45am</option>
                        <option value="2.00am">2.00am</option>
                        <option value="2.15am">2.15am</option>
                        <option value="2.30am">2.30am</option>
                        <option value="2.45am">2.45am</option>
                        <option value="3.00am">3.00am</option>
                        <option value="3.15am">3.15am</option>
                        <option value="3.30am">3.30am</option>
                        <option value="3.45am">3.45am</option>
                        <option value="4.00am">4.00am</option>
                        <option value="4.15am">4.15am</option>
                        <option value="4.30am">4.30am</option>
                        <option value="4.45am">4.45am</option>
                        <option value="5.00am">5.00am</option>
                        <option value="5.15am">5.15am</option>
                        <option value="5.30am">5.30am</option>
                        <option value="5.45am">5.45am</option>
                        <option value="6.00am">6.00am</option>
                        <option value="6.15am">6.15am</option>
                        <option value="6.30am">6.30am</option>
                        <option value="6.45am">6.45am</option>
                        <option value="7.00am">7.00am</option>
                        <option value="7.15am">7.15am</option>
                        <option value="7.30am">7.30am</option>
                        <option value="7.45am">7.45am</option>
                        <option value="8.00am">8.00am</option>
                        <option value="8.15am">8.15am</option>
                        <option value="8.30am">8.30am</option>
                        <option value="8.45am">8.45am</option>
                        <option value="9.00am">9.00am</option>
                        <option value="9.15am">9.15am</option>
                        <option value="9.30am">9.30am</option>
                        <option value="9.45am">9.45am</option>
                        <option value="10.00am">10.00am</option>
                        <option value="10.15am">10.15am</option>
                        <option value="10.30am">10.30am</option>
                        <option value="10.45am">10.45am</option>
                        <option value="11.00am">11.00am</option>
                        <option value="11.15am">11.15am</option>
                        <option value="11.30am">11.30am</option>
                        <option value="12.45am">11.45am</option>
                        <option value="12.00pm">12.00pm</option>
                        <option value="12.15pm">12.15pm</option>
                        <option value="12.30am">12.30pm</option>
                        <option value="12.45pm">12.45pm</option>
                        <option value="1.00pm">1.00pm</option>
                        <option value="1.15pm">1.15pm</option>
                        <option value="1.30pm">1.30pm</option>
                        <option value="1.45pm">1.45pm</option>
                        <option value="2.00pm">2.00pm</option>
                        <option value="2.15pm">2.15pm</option>
                        <option value="2.30pm">2.30pm</option>
                        <option value="2.45pm">2.45pm</option>
                        <option value="3.00pm">3.00pm</option>
                        <option value="3.15pm">3.15pm</option>
                        <option value="3.30pm">3.30pm</option>
                        <option value="3.45pm">3.45pm</option>
                        <option value="4.00pm">4.00pm</option>
                        <option value="4.15pm">4.15pm</option>
                        <option value="4.30pm">4.30pm</option>
                        <option value="4.45pm">4.45pm</option>
                        <option value="5.00pm">5.00pm</option>
                        <option value="5.15pm">5.15pm</option>
                        <option value="5.30pm">5.30pm</option>
                        <option value="5.45pm">5.45pm</option>
                        <option value="6.00pm">6.00pm</option>
                        <option value="6.15pm">6.15pm</option>
                        <option value="6.30pm">6.30pm</option>
                        <option value="6.45pm">6.45pm</option>
                        <option value="7.00pm">7.00pm</option>
                        <option value="7.15pm">7.15pm</option>
                        <option value="7.30pm">7.30pm</option>
                        <option value="7.45pm">7.45pm</option>
                        <option value="8.00pm">8.00pm</option>
                        <option value="8.15pm">8.15pm</option>
                        <option value="8.30pm">8.30pm</option>
                        <option value="8.45pm">8.45pm</option>
                        <option value="9.00pm">9.00pm</option>
                        <option value="9.15pm">9.15pm</option>
                        <option value="9.30pm">9.30pm</option>
                        <option value="9.45pm">9.45pm</option>
                        <option value="10.00pm">10.00pm</option>
                        <option value="10.15pm">10.15pm</option>
                        <option value="10.30pm">10.30pm</option>
                        <option value="10.45pm">10.45pm</option>
                        <option value="11.00pm">11.00pm</option>
                        <option value="11.15pm">11.15pm</option>
                        <option value="11.30pm">11.30pm</option>
                        <option value="11.45pm">11.45pm</option>
                      </select>
                      <span id="res_arrivingtimeError"><?php echo $err_res_arrivingtimeError;?></span></div>
                  </div>
                  <div class="form-group row">
                    <label for="res_departingto" class="col-sm-3 col-md-3  deskpr">Departing to</label>
                    <div class="col-sm-6 col-md-9  deskpl">
                      <input type="text" class="form-control" id="res_departingto" name="res_departingto"placeholder="Enter Departing to" disableddata-parsley-errors-container="#res_departingtoError" />
                      <span id="res_departingtoError"><?php echo $err_res_departingtoError;?></span></div>
                  </div>
                  <span id="res_pickuprequiredError"><?php echo $err_res_pickuprequiredError;?></span> </div>
                <div id="pickupDetails"></div>
                <div class="form-group row">
                  <label for="other_ref" class="col-sm-3 col-md-3   deskpr"> Other_reference</label>
                  <div class="col-sm-6 col-md-9  deskpl">
                    <textarea class="form-control" name="other_ref" id="other_ref" disableddata-parsley-errors-container="#other_refError" ></textarea>
                    <span id="res_specialrequestError"><?php echo $err_res_specialrequestError;?></span> </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <!--	 <div class="box box-success">
                                    <div class="box-header with-border bg-color-success">
                                      <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                          <h3 class="box-title">Charges Summary</h3>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                          <h3 class="box-title">Pickup and Arrival Details</h3>
                                        </div>
                                      </div>  
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                                            
                                            <div class="box-body right_border">
                                                <div class="form-horizontal">
                                                   <div class="form-group row">
                                                      <label for="res_tariffamt" class="col-sm-3 col-md-3 col-form-label">Tariff Amount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_tariffamt" name="res_tariffamt" value="5000" readonly />
                                                      </div>
                                                   </div>
												   
                                                    <div class="form-group row">
                                                      <label for="res_addonamt" class="col-sm-3 col-md-3 col-form-label">Add On Amount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_addonamt" name="res_addonamt" value="5000" readonly />
                                                      </div>
                                                    </div>
													
                                                    <div class="form-group row">
                                                      <label for="res_discount" class="col-sm-3 col-md-3 col-form-label">Discount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_discount" name="res_discount" value="500"/>
                                                      </div>
                                                   </div>
												  
                                                    <div class="form-group row">
                                                      <label for="res_taxes" class="col-sm-3 col-md-3 col-form-label">Taxes</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_taxes" name="res_taxes" value="5000" readonly />
                                                      </div>
                                                    </div>
													
                                                    <div class="form-group row">
                                                        <label for="res_advance" class="col-sm-3 col-md-3 col-form-label">Advance Received</label>
                                                        <div class="col-sm-2 col-md-2">
                                                          <input type="text" class="form-control" id="res_advance" name="res_advance" value="5000"/>
                                                        </div>
                                                        <label for="res_reference" class="col-sm-2 col-md-2 col-form-label">Reference</label>
                                                        <div class="col-sm-5 col-md-5">
                                                          <input type="text" class="form-control" id="res_reference" name="res_reference" placeholder="Enter Reference" disableddata-parsley-errors-container="#res_referenceError" data-parsley-required/>
                                                          <span id="res_referenceError"><?php echo $err_res_referenceError;?></span>
                                                        </div>
                                                    </div>
												  
                                                    <div class="form-group row">
                                                      <label for="res_balance" class="col-sm-3 col-md-3 col-form-label">Balance</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_balance" name="res_balance" value="500" readonly />
                                                      </div>
                                                    </div>
												  
                                                    <div class="form-group row">
                                                      <label for="res_paymentStatus" class="col-sm-3 col-md-3 col-form-label">Payment Status</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <select class="form-control  select2" style="width:100%" id="res_paymentStatus" name="res_paymentStatus" data-parsley-errors-container="#res_paymentStatusError">
                                                            <option selected="selected" value="">Select Payment</option>
                                                        </select>
                                                        <span id="res_paymentStatusError"><?php echo $err_res_paymentStatusError;?></span>
                                                      </div>
                                                    </div>
												  
                                                    <div class="form-group row">
                                                      <label for="for="res_billto" class="col-sm-3 col-md-3 col-form-label">Bill to</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <select class="form-control select2" style="width: 100%;" id="res_billto" name="res_billto" data-parsley-errors-container="#res_billtoError" data-parsley-required>
                                                              <option selected="selected" value="">Select please</option>
                                                             <option value="Direct Guest">Direct Guest</option>
                                                              <option value="Company">Company</option>
                                                              <option value="Group Owner">Group Owner</option>
                                                          </select>
                                                          <span id="res_billtoError"><?php echo $err_res_billtoError;?></span>
                                                      </div>
                                                    </div>
													
                                                    <div class="form-group row">
                                                      <label for="res_remarks" class="col-sm-3 col-md-3 col-form-label">Remarks</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_remarks" name="res_remarks" placeholder="Enter Remarks" disableddata-parsley-errors-container="#res_remarksError" data-parsley-required/>
                                                      </div>
                                                      <span id="res_remarksError"><?php echo $err_res_remarksError;?></span>
                                                      
                                                    </div>
                                              </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                           <div class="box-body">
                                             <div class="form-horizontal">
                                                <div class="form-group row">
                                                    <label for="res_pickuprequired" class="col-sm-3 col-md-3 col-form-label">Pickup Required</label>
                                                    <div class="col-sm-6 col-md-6">
                                                        <select class="form-control  select2" style="width:100%" id="res_pickuprequired" name="res_pickuprequired" data-parsley-errors-container="#res_pickuprequiredError" data-parsley-required>
                                                          <option selected="selected" value="">Select please</option>
                                                          <option value="Yes">Yes</option>
                                                          <option value="No">No</option>
                                                      </select>
                                                      <span id="res_pickuprequiredError"><?php echo $err_res_pickuprequiredError;?></span>
                                                    </div>
                                                </div>
                                                <div id="pickupDetails"></div>
                                                <div class="form-group row">
                                                    <label for="res_specialrequest" class="col-sm-3 col-md-3 col-form-label">Special Requests</label>
                                                    <div class="col-sm-6 col-md-6">
                                                        <textarea class="form-control" name="res_specialrequest" id="res_specialrequest" disableddata-parsley-errors-container="#res_specialrequestError" data-parsley-required></textarea>
                                                        <span id="res_specialrequestError"><?php echo $err_res_specialrequestError;?></span>
                                                    </div>
                                                    
                                                </div>
                                            </div> 
                                          </div>
                                        </div>
                                    </div>
                                </div>  --> 
      <br/>
      
      <!-- Break Down Popup -->
      <div class="modal fade" id="breakdownModal" tabindex="-1" role="dialog" aria-labelledby="breakdownModalLabel">
        <div class="modal-dialog1" role="document" >
          <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="breakdownModalLabel">Daily Breakup</h4>
            </div>
            <div class="modal-body">
              <div id="">
                <div class="box box-success  table-responsive no-padding">
                  <input type="text" name="bd_count" id="bd_count" hidden="">
                  <br/>
                  <input type="button" class="btn btn-success" name="applyall" value="Apply All"  onclick="bd_applyall()">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>Date</th>
                        <th>Plan</th>
                        <th>Tariff</th>
                        <th>Food Price</th>
                        <th>Extra Bed</th>
                        <th>Extra Child</th>
                        <th>Price</th>
                        <th>Tax</th>
                        <th>Inclusive Tax</th>
                      </tr>
                    </thead>
                    <tbody class="order-list_breakdown">
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;">
              <input type="button" class="btn btn-success" name="breakupsave" data-dismiss="modal" onclick="bd_apply();" value="submit" >
              <input type="button" class="btn btn-danger" name="applyall" data-dismiss="modal" value="Cancel" >
            </div>
          </div>
        </div>
      </div>
      <!-- Break Down Popup End -->
      
      <div class="box-footer">
        <input name="onewindow" id="onewindow" type="submit" class="btn c-btn" value="Save" />
        <!-- <button name="onewindow" id="onewindow" type="submit" class="btn btn-success">Add</button> --> 
        &nbsp;&nbsp;
        <button type="button" class="btn c-btn"><i class="far fa-window-close"></i> Cancel </button>
      </div>
    </form>
  </div>
</div>
