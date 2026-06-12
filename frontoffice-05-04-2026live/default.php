<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<style>
    .table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
        background-color: #F5F5F5;
    }
    .right_border{
        border-right: 1px solid #E3E3E5;   
    }
    @media(max-width:720px){
        .right_border{
            border-right: none;  
        }
    }
    .bg-color-success{
        background-color: #00A65A;
        color: white; 
        height: 30px;
        padding-top: 4px;
        padding-bottom: 0px;
        
    }
    .bg-color-info{
        background-color: #00C0EF;
        color: white; 
        height: 30px;
        padding-top: 4px;
        padding-bottom: 0px;
    }
	a.fc-timeline-event.fc-h-event.fc-event.fc-start.fc-end {
    font-size: 12.8px;
    font-weight: 600;
	padding-left:8px;
    margin: 6px 6px 0px 6px;;
    border-radius: 3px;
}

</style>


<!-- <div id="preloader"></div> -->


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            One Window
            <small>Optional description</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#"><i class="fa fa-dashboard"></i> Level</a>
            </li>
            <li class="active">Here</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content container-fluid">
        <!-- Info boxes -->
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box" style="min-height: 40px;">
                    <span class="info-box-icon bg-aqua" style="height: 40px; "></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Exepected Arrivals &nbsp; - &nbsp; <b>10</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box" style="min-height: 40px;">
                    <span class="info-box-icon bg-red" style="height: 40px;"></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Today's Checkin &nbsp; - &nbsp; <b>50</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->

            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>

            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box" style="min-height: 40px;">
                    <span class="info-box-icon bg-yellow " style="height: 40px;"></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Opening Date &nbsp; - &nbsp;<b>14/Mar/2020</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->

        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab" onClick="reload()" >  Reservation Chart</a>
                </li>
                <li id="reservation_tab"><a href="#" data-toggle="tab" >Reservations</a></li>
                <li>
                    <a href="#tab_3" data-toggle="tab">Expected Arrivals</a>
                </li>
                <li><a href="#tab_4" data-toggle="tab">Room Statistics</a></li>
                <li id="guest_tab"><a href="#" data-toggle="tab">Guest Details</a></li>
                <li id="folio_tab"><a href="#" data-toggle="tab">Folio</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Select Hotel</label>
                         
							<?php 
								$sqlHot="SELECT id,name FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."' && status='1' ";
								$resHot=mysqli_query($connNew,$sqlHot);
							?>
							
						<form method="post" action="#" id="srform">  							
							<select class="select2 form-control" id="id_hotel" onchange="showUser(this.value);"  name="id_hotel">
								<option value="">---SELECT HOTEL---</option>
									<?php while($objHot=mysqli_fetch_object($resHot)){
										if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']==$objHot->id){
											$selected="selected";
										}
										else{
											$selected="";
										}
										echo "<option ".$selected." value='".$objHot->id.'-'.$objHot->name."'>".$objHot->name."</option>";
									} ?>
							</select>
									
					</form>
						
							<!--   <select name="" id="id_hotel" class="form-control">
                                <option value="1003">Balsamand Lake Palace</option>
                            </select> -->
							
                        </div>
						<div id="txtHint"></div>                        
                        <div class="col-md-4 form-group">
                            <label>Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right " value="<?php echo date('d-m-Y');  ?>" name="date" id="datepicker">
                            </div>   
                        </div>
                        <div class="col-md-4 form-group">
                            <label for="">&nbsp;</label>
							<button onClick="jumpTodate()"  id="addsubmit" class="form-control btn btn-success">Search</button>
						</div>
					
                    </div>
                  <!--  <div id="calendar" style="text-align:center;color:#dd4b39"> <h3 class="box-title">Please Select a Hotel Name</h3></div>  -->
                    <div id="calendar" style=""></div>
                    <div id="calendar1" style=""></div>
                  
                </div>
                <div class="tab-pane" id="tab_2">
                     <!-- Reservation -->
                    <div class="row ">
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
												  <input type="hidden" name="id_mst_hotels" id="id_mst_hotels" />
												  <input type="hidden" class="form-control" id="parentId" name="parentId" />
										
                                 <!--hidden 
								 <label class="col-sm-3 col-md-3 col-form-label">Room type id</label>
									<div class="col-sm-12 col-md-12">
                                        <input type="hidden" name="res_room" id="res_room" />
									</div>	
										
										<label class="col-sm-3 col-md-3 col-form-label">hotel id</label>
										<div class="col-sm-12 col-md-12">
                                            <input type="hidden" name="id_mst_hotels" id="id_mst_hotels" />
										</div>
										
										<label class="col-sm-3 col-md-3 col-form-label">Room number id</label>
										<div class="col-sm-12 col-md-12">
										    <input type="hidden" class="form-control" id="parentId" name="parentId" />
										</div>
										<!-- hide -->
                                                      <label for="res_bookingNo" class="col-sm-3 col-md-3 col-form-label">Booking Number</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" id="res_bookingNo" name="res_bookingNo" value="w310" disabled />
                                                      </div>
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_bookingDate" class="col-sm-3 col-md-3 col-form-label">Booking Date</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" value="<?php echo date('d-m-Y');?>" id="res_bookingDate" name="res_bookingDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_bookingDateError" data-parsley-required />
                                                          <span id="res_bookingDateError"><?php echo $err_res_bookingDateError;?></span>
                                                      </div>

                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_bookingStatus" class="col-sm-3 col-md-3 col-form-label">Booking Status</label>
                                                      <div class="col-sm-6 col-md-6">
                                                       <!-- <input type="text" class="form-control" id="res_bookingStatus" name="res_bookingStatus" /> --> 
														<select class="form-control select2" style="width: 100%;" id="res_bookingStatus" name="res_bookingStatus" data-parsley-errors-container="#res_bookingStatusError" data-parsley-required>
                                                              <option value="Confirmed" selected="selected">Confirmed</option>
                                                              <option value="Tentative">Tentative</option>
                                                              <option value="Waitlisted">Waitlisted</option>
                                                              <option value="Cancelled">Cancelled</option>
                                                        </select>
                                                        <span id="res_bookingStatusError"><?php echo $err_res_bookingStatusError;?></span>
                                                      </div>
                                                      
                                                  </div>
                                                  <div id="res_bookingList"></div>

                                              </div>
                                            </div>
                                            <div class="col-md- col-sm-6 col-xs-12">
                                                <div class="form-horizontal">
											  
													<div class="form-group row">
                                                      <label for="res_hotelName" class="col-sm-3 col-md-3 col-form-label">Hotel Name</label>
                                                      <div class="col-sm-9 col-md-9">
                                                    
													<input type="text" class="form-control" id="res_hotelName" name="res_hotelName" /> 
                                                      

													   <!-- <select class="form-control select2" style="width: 100%;" id="res_hotelName" name="res_hotelName" data-parsley-errors-container="#res_hotelNameError" data-parsley-required>
                                                              <option selected="selected" value="">Select Hotel</option>
                                                          </select>  -->
                                                          <span id="res_hotelNameError"><?php echo $err_res_hotelNameError;?></span>
                                                      </div>
                                                    </div>
												  
													<div class="form-group row">
                                                      <label for="res_checkinDate" class="col-sm-3 col-md-3 col-form-label">Check In</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" id="res_checkinDate" name="res_checkinDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_checkinDateError" data-parsley-required />
                                                          <span id="res_checkinDateError"><?php echo $err_res_checkinDateError;?></span>
                                                      </div>
													</div>
												  
													<div class="form-group row">
                                                      <label for="res_checkOutDate" class="col-sm-3 col-md-3 col-form-label">Check Out</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" id="res_checkOutDate" name="res_checkOutDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_checkOutDateError" data-parsley-required />
                                                          <span id="res_checkOutDateError">
                                                              <?php echo $err_res_checkOutDateError;?>
                                                          </span>
                                                      </div>
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
                                            <div class="col-md- col-sm-6 col-xs-12 right_border">
                                                <div class="form-horizontal">
													<div class="form-group row">
                                                      <label for="res_guestName" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Guest Name</label>
														<div class="col-sm-9 col-md-9 col-xs-10" style="padding-right: 0px;">
															<div class="input-group">
																<input type="text" class="form-control" name="res_guestName" id="res_guestName"/>
																<div class="input-group-addon">
																  <a href="#" style="color:black;" id="res_guestAddId"><i class="fa fa-plus"></i> </a>
																</div>
															</div>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-2" style="padding: 0px 10px 0px 4px;">
                                                            <a class="btn btn-success btn-sm" id="res_guestEditId"><i class="fa fa-edit"></i></a>
														</div>
													</div>
                                                  
													<div class="form-group row">
														  <label for="res_bookingType" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Booking Type</label>
														  <div class="col-sm-6 col-md-6">
															  <select class="form-control select2" style="width: 100%;" id="res_bookingType" name="res_bookingType" data-parsley-errors-container="#res_bookingTypeError" data-parsley-required>
															  <option selected="selected" value="">Select Booking Type</option>
															  <option  value="1">Direct Guest</option>
															  <option value="2">Travel Agent</option>
															  <option value="3">Corporate</option>
															</select>
															<span id="res_bookingTypeError"><?php echo $err_res_bookingTypeError;?></span>
														  </div>
													</div>
													
													<div class="form-group row">
														  <label for="res_source" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Source</label>
														  <div class="col-sm-6 col-md-6">
															<select class="form-control select2" style="width: 100%;" id="res_source" name="res_source" data-parsley-errors-container="#res_sourceError" data-parsley-required>
																<option selected="selected" value="">Select Source</option>
															</select>
															<span id="res_sourceError"><?php echo $err_res_sourceError;?></span>
														  </div>  
													</div>

                                              </div>
                                            </div>
                                            <div class="col-md- col-sm-6 col-xs-12">
												<div class="form-horizontal">
													<div class="form-group row">
														<label for="res_bookerName" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Booker Name</label>
														<div class="col-sm-9 col-md-9 col-xs-10" style="padding-right: 0px; ">
															<div class="input-group">
																<select class="form-control select2" style="width: 100%;" id="res_bookerName" name="res_bookerName" data-parsley-errors-container="#res_bookerNameError" data-parsley-required>
																  <option selected="selected" value="">Select Contacts</option>
																  <option value="Contact1">Contact1</option>
																  <option value="Contact2">Contact1</option> 
																</select>
																<div class="input-group-addon">
																  <a href="javascript:void(0);" style="color:black;" id="res_bookerAddId"><i class="fa fa-plus"></i> </a>
																</div>
															</div>
														  <span id="res_bookerNameError"><?php echo $err_res_bookerNameError;?></span>
														</div>
														<div class="col-md-1 col-sm-1 col-xs-2" style="padding: 0px 10px 0px 4px;">
                                                             <a class="btn btn-success btn-sm" id="res_bookerEditId"><i class="fa fa-edit"></i></a>
														</div>
													</div>
												  
													<div class="form-group row">
														  <label for="res_rateType" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Rate Type</label>
														  <div class="col-sm-6 col-md-6">
															<select class="form-control select2" style="width: 100%;" id="res_rateType" name="res_rateType" data-parsley-errors-container="#res_rateTypeError" data-parsley-required>
																<option selected="selected" value="">Select Rate Type</option>
																<option value="Adoc">Adoc</option>
																<option value="Contract">Contract</option>
													  
															</select>
															<span id="res_rateTypeError"><?php echo $err_res_rateTypeError;?></span>
														  </div>
													</div>
												  
													<div class="form-group row">
														  <label for="res_rateLetter" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 12px">Rate Letter</label>
														  <div class="col-sm-6 col-md-6 col-xs-10">
															<select class="form-control select2" style="width: 100%;" id="res_rateLetter" name="res_rateLetter" data-parsley-errors-container="#res_rateLetterError" data-parsley-required>
															<option selected="selected" value="">Select Rate Letter</option>
															<option value="Rate Letter1">Rate Letter1</option>
															<option value="Rate Letter2">Rate Letter2</option>
												  
															</select>
															<span id="res_rateLetterError"><?php echo $err_res_rateLetterError;?></span>
														  </div>
														  <div class="col-sm-2 col-md-2 col-xs-2" style="padding-left: 0px;">
															 <button type="button" class="btn btn-danger btn-sm"><i class="fa fa-eye"></i> View</button> 
														  </div>
													</div>

												</div>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
								
                                <div class="box box-success">
                                    <div class="box-header with-border bg-color-success">
                                         <h3 class="box-title">Rooms and Rates </h3> &nbsp;
                                         <a href="javascript:void(0)" onclick="roomsRates();" style="color:white"><i class="fa fa-plus"></i></a>
                                    </div>
                                    <div class="box-body ">
                                        <div class="well table-responsive">
                                          <table class="table table-hover">
                                            <thead>
                                              <tr>
                                                <th>Room Type</th>
                                                <th>Plan</th>
                                                <th>No. of Rooms</th>
                                                <th>Adult Per Person</th>
                                                <th>Child Per Room</th>
                                                <th>Extra Child/Person <br/> with Bed per Room</th> 
                                                <th>Tariff Per Room <br/> per Nights</th>
                                                <th>Taxes</th>
                                                <th>Charges <br/>Per Night</th>
                                             </tr>
                                            </thead>
                                            <tbody id="roomsRates">
                                              
                                            </tbody>  
                                        </table>
                                        </div>
                                    </div>
                                </div>
								
                                <div class="box box-info ">
                                    <div class="box-header with-border bg-color-info">
                                         <h3 class="box-title">Add Ons </h3> &nbsp;
                                         <a href="javascript:void(0)" onclick="addOns();" style="color:white"><i class="fa fa-plus"></i></a>
                                    </div>
                                    <div class="box-body">
										<div class="well  table-responsive">
                                        <table class="table table-hover">
                                          <thead>
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
                                          <tbody id="addons">
                                            
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                </div>	
								
                                <div class="box box-success">
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
                                                          <input type="text" class="form-control" id="res_tariffamt" name="res_tariffamt" value="5000" disabled />
                                                      </div>
                                                   </div>
												   
                                                    <div class="form-group row">
                                                      <label for="res_addonamt" class="col-sm-3 col-md-3 col-form-label">Add On Amount</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_addonamt" name="res_addonamt" value="5000" disabled />
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
                                                          <input type="text" class="form-control" id="res_taxes" name="res_taxes" value="5000" disabled />
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
                                                          <input type="text" class="form-control" id="res_balance" name="res_balance" value="500" disabled />
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
                                </div>
                                <br/>
                                
								 <div class="box-footer">
								<input name="onewindow" id="onewindow" type="submit" class="btn btn-primary" value="Add" />
                                       <!-- <button name="onewindow" id="onewindow" type="submit" class="btn btn-success">Add</button> -->
                                        &nbsp;&nbsp; 
                                        <button type="button" class="btn btn-danger">Cancel </button>
                                    </div>
                            </form>  
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <!-- Table 1 Start -->
                    <div class="row mt-5">
                        <div class="col-xs-12">
                          <div class="box box-info">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-2 col-sx-12 col-sm-4">
                                        <h4 class="box-title" style="font-size:15px">Expected Arrivals On</h4>
                                    </div>
                                    <div class="col-md-2 col-xs-5 col-sm-4">
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" value = "<?php echo date('d-m-Y'); ?>" class="form-control pull-right  datepicker">
                                        </div>   
                                    </div>
                                    
                                    <div class="col-md-1 col-sm-2 col-xs-3">
                                        <button class="btn btn-success">Search</button>
                                    </div>
                                    <div class="col-md-1 col-sm-2 col-xs-4">
                                        <button class="btn btn-success">Download</button>
                                    </div>

                                </div>
                              
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <table id="expected_arrivals" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>S.No</th>
                                  <th>Reservation Id</th>
                                  <th>Guest Name</th>
                                  <th>Source</th>
                                  <th>Room Type</th>
                                  <th>Adults | Childs</th>
                                  <th>Booked Rooms</th>
                                  <th>Checkin Pending</th>
                                  <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                              </table>
                            </div>
                            <!-- /.box-body -->
                        </div>
                        <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 1 End -->

                </div>
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_4">
                    <!-- Table 2 Start -->
                    <div class="row mt-5">
                        <div class="col-xs-12">
                          <div class="box box-success">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-xs-12"><h3 class="box-title">Room Statistics</h3></div>
                                    <div class="col-md-2 col-sm-4 col-xs-5">
                                        <!-- input type="text"  class="form-control" id="datepicker1" value="<?php// echo Date('d-m-Y'); ?>" -->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" value = "<?php echo date('d-m-Y'); ?>" class="form-control pull-right datepicker">
                                        </div>   
                                    </div>
                                    <div class="col-md-1 col-sm-2 col-xs-3">
                                        <button class="btn btn-success">Search</button>
                                    </div>
                                    <div class="col-md-1 col-sm-3 col-xs-4">
                                        <button class="btn btn-success">Download</button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <table id="room_statistics" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>Room type</th>
                                  <th>Room No</th>
                                  <th>Status</th>
                                  <th>Res#</th>
                                  <th>Guest Name</th>
                                  <th>Folio#</th>
                                  <th>Checkin Date</th>
                                  <th>Checkout Date</th>
                                  <th width="120">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                
                              </table>
                            </div>
                            <!-- /.box-body -->
                          </div>
                          <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 2 End -->
                </div>

                <div class="tab-pane" id="tab_5">
                  <div id="DisplayGuestForm">rterte</div>
                    <?php //include_once("../actions/editGuestForm.php"); ?>
                </div>
                <div class="tab-pane" id="tab_6">
                    <!-- Table 3 Start -->
                    <div class="row">
                        <div class="col-xs-12">
                          <div class="box box-success">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
                                        <table class="table table-striped table-bordered" cellspacing="0">
                                            <thead>
                                                <tr class="info">
                                                    <th>Folio no.</th>
                                                    <th>Guest Name</th>
                                                    <th>Check In</th>
                                                    <th>Check Out</th>
                                                    <th>Current Total</th>
                                                    <th>Amount Received</th>
                                                    <th>Balance</th>
                                                    <th>Folio Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="folio_no">F001</td>
                                                    <td id="folio_guestname">S.Sundaram</td>
                                                    <td id="folio_checkin">01-01-2020</td>
                                                    <td id="folio_checkout">03-01-2020</td>
                                                    <td id="folio_currenttotal">23600</td>
                                                    <td id="folio_amtreceived">10000</td>
                                                    <td id="folio_balance">16600</td>
                                                    <td id="foliostatus" class="text-center">
                                                        <select name="foliostatus" id="select_foliostatus">
                                                            <option value="Open">Open</option>
                                                             <option value="Open">Close</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                
                                            </tbody>
                                        </table>
                                       <hr/>
                                    </div>
                                </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive" style="margin-top: -20px; max-height: 400px">
                                <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Room Type/No</th>
                                            <th>Date</th>
                                            <th>Invoice#</th>
                                            <th>Source</th>
                                            <th>Tariff</th>
                                            <th>Extra Bed</th>
                                            <th>SGST</th>
                                            <th>CGST</th>
                                            <th>IGST</th>
                                            <th>DR</th>
                                            <th>CR</th>
                                            <th><input type="checkbox" name="DId" id="did" /></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                              </table>
                            </div>
                            <!-- /.box-body -->
                            <div class="card text-dark bg-light">
                                <hr>
                                <div class="bg-primary text-center ">
                                    <h5 style="padding: 5px;">Invoice Summary</h5>
                                </div> 
                                <hr>
                            </div>
                            
                            <div class="box-body table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr class="info">
                                            <th>Invoice No</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>invo001</td>
                                            <td>70800</td>
                                        </tr>
                                        <tr>
                                            <td>invo001</td>
                                            <td>35400</td>
                                        </tr>
                                        <tr>
                                            <td>R1</td>
                                            <td>3000</td>
                                        </tr>
                                    </tbody>
                                </table>
                                
                            </div>
                          </div>
                          <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 3 End -->
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->

    </section>
    <!-- /.content -->
</div>

<!-- Checkin Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" role="dialog" aria-labelledby="checkinModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="checkinModalLabel">Ckeckin Form</h4>
            </div>
            <div class="modal-body">
                <div class="form-group"><label for="">Rooms</label>
                <select name="" id="" class="form-control">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="2">3</option>
                </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onClick="saveReservation()">Checkin</button>
            </div>
        </div>
    </div>
</div>
<!--- End Checkin modal -->

<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="reservationModalLabel">Quick Form</h4>
            </div>
            <div class="modal-body">
                <form id="reservationForm" class="form">
                    <input type="hidden" name="res_hotel" id="res_hotel" />
                    <input type="hidden" name="res_room" id="res_room" />
                    <input type="hidden" name="res_checkin" id="res_checkin" />
                    <input type="hidden" name="res_checkout" id="res_checkout" />
                    <div class="form-group">
                      <!--<label for="guest">Guest Details</label> -->
                      <input name="guest" id="guest" type="text" class="form-control" />
                    </div>
                    <div class="row">
                      <div class="col-md-6" style="padding-right: 0px">
                        <div class="form-group">
                            <label for="guestname">Guest Name</label>
                            <div class="input-group">
                              <input type="text" name="guestname" id="guestname" class="form-control" placeholder="Enter Guest Name" data-parsley-errors-container="#guestnameError" data-parsley-required>
                              <div class="input-group-addon">
                                <a href="#" class="text-success" id="guestAddId"><i class="fa fa-plus"></i> </a>
                              </div>
                              <div class="input-group-addon">
                                <a href="#" class="text-info" id="guestEditId" data-guestid="<?php echo encryptor(encrypt, '21') ?>"><i class="fa fa-edit"></i> </a>
                              </div>
                            </div>
                        </div>
                        <span id="guestnameError"><?php echo $err_guestnameError;?></span>
                      </div>
                      <div class="col-md-3" style="padding-right: 0px;">
                        <div class="form-group">
                            <label for="bookingType">Booking Type</label>
                            <select class="form-control" id="bookingType" name="bookingType">
                              <option selected="selected" value="">Select Please</option>
                              <option  value="1">Direct Guest</option>
                              <option value="2">Travel Agent</option>
                              <option value="3">Corporate</option>           
                            </select>
                          </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label for="bookingSource">Source</label>
                            <select name="bookingSource" id="bookingSource" class="form-control">
                              <option value="">Select Please</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-5" style="padding-right: 0px;">
                        <div class="form-group">
                          <label for="bookerName">Booker Name</label>
                          <div class="input-group">
                            <select class="form-control" name="bookerName" id="bookerName">
                              <option value="" selected="selected">Select Please</option>
                              <option value="Contact1">Contact1</option>
                              <option value="Contact2">Contact2</option>
                            </select>
                            <div class="input-group-addon">
                                <a href="javascript:void(0);" class="text-success" id="bookerAddId"><i class="fa fa-plus"></i> </a>
                              </div>
                              <div class="input-group-addon">
                                <a href="javascript:void(0);" class="text-info" id="bookerEditId"><i class="fa fa-edit"></i> </a>
                              </div>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-3" style="padding-right: 0px;">
                        <div class="form-group">
                          <label for="rateType">Rate Type</label>
                          <select class="form-control" name="rateType" id="rateType">
                            <option value="" selected="selected">Select Please</option>
                            <option value="Adoc">Adoc</option>
                            <option value="Contract">Contract</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-3 col-xs-10" style="padding-right: 4px;">
                        <div class="form-group">
                         <label for="rateLetter">Rate Letter</label>
                          <select class="form-control" name="rateLetter" id="rateLetter">
                            <option value="" selected="selected">Select Please</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-1 col-xs-2" style="padding-left: 0px;padding-top:5px ">
                        <br/>
                        <button class="btn btn-danger btn-sm" type="button" style="padding-right:1px;padding-left: 1px;" id="viewRateLetter"><i class="fa fa-eye"></i> View</button>
                      </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-3" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="id_plan">Plan</label>
                                <select name="id_plan" id="id_plan" class="form-control">
                                    <option value="3">CP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="rooms">Rooms</label>
                                <select name="rooms" id="rooms" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="adults">Adult</label>
                                <select name="adults" id="adults" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="child">Child</label>
                                <select name="child" id="child" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="rate">Rate</label>
                                <input name="rate" id="rate" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="booking_status">Booking Status</label>
                                <select name="booking_status" id="booking_status" class="form-control">
                                    <option value="con">Confirmed</option>
                                    <option value="ten">Tentative</option>
                                    <option value="a3">Waitlisted</option>
                                </select>
                            </div>
                        </div>
                        <div id="holdtillDiv"></div>
                        <div class="col-md-3" style="padding-right: 0px;">
                            <div class="form-group">
                                <label for="paymentStatus">Payment Status</label>
                                <select name="paymentStatus" id="paymentStatus" class="form-control">
                                    <option value="3">Paid</option>
                                    <option value="4">Advance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Amount Received</label>
                                <input type="text" name="receivedAmount" id="receivedAmount" class="form-control" />
                            </div>
                        </div>
                    </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="more-options">More options</button>
				<input name="addBaseRate" id="addBaseRate" type="submit" class="btn btn-primary" value="Add" />
                <!-- <button type="button" class="btn btn-success" onclick="saveReservation()">Save</button>-->
            </div>
		 </form>
        </div>
    </div>
</div>
<!-- End Reservation Modal -->
  
   
<?php include_once("../includes/footer.php")?>

<script>
    $(document).ready(function(){

        initializeTableArrivals = (target,targetArr,ordering=false) =>{
            $(target || '#expected_arrivals').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : ordering,
            'info'        : true,
            'autoWidth'   : false,
            "oLanguage": { "sSearch": "Search By Guest Or Room:"} ,
            "columnDefs": [
                      { "searchable": false, "targets": targetArr }
                     ],
            })
        }

        initializeTableRoom = (target,targetArr,ordering=false) =>{
            $(target || '#room_statistics').DataTable({
            'paging'      : true,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : ordering,
            'info'        : true,
            'autoWidth'   : false,
            "oLanguage": { "sSearch": "Search By Guest Or Room:"} ,
            "columnDefs": [
                       { "searchable": false, "targets": targetArr }
                     ],
            })
        }

        initializeTableFolio = (target,targetArr,ordering=false) =>{
            $(target || '#foliotable').DataTable({
            'paging'      : false,
            'lengthChange': false,
            'searching'   : true,
            'ordering'    : ordering,
            'info'        : true,
            'autoWidth'   : false,
            "oLanguage": { "sSearch": "Search By Room Type/No:"} ,
            "columnDefs": [{
                     "searchable": false, "targets": targetArr 
                 }],
            });
        }

        $('#did').click(function(){
            if($(this). prop("checked") == true){
                $("#foliotable tbody tr input:nth-child(1)").attr('checked','checked');
                $("#foliotable tbody tr input:nth-child(2)").attr('checked','checked');
            }   
            else{
                $("#foliotable tbody tr input:nth-child(1)").removeAttr('checked','checked');
                $("#foliotable tbody tr input:nth-child(2)").removeAttr('checked','checked');
            }
        });

        arrivals = (date='') =>{
                $.ajax({
                    'url':'arrivals.php',
                    'data':'date='+date,
                    'dataType':'JSON',
                    success:function(data){
                        var tableData = '';
                        data.forEach((value,key,arr)=>{
                            tableData += `
                                <tr>
                                    <td>${(key+1)}</td>
                                    <td onclick="reservationDetails('${value.reservation_id}')"><a href="#" style="color:black;">${(value.reservation_id)}</a></td>
                                    <td onclick="guestDetails('${value.id}','edit')"><a href="#" style="color:black;">${(value.guest)}</a></td>
                                    <td>${(value.source)}</td><td>`;
                                    value.roomType.forEach((roomTypevalue,keys,arrays)=>{
                                      tableData += `${roomTypevalue}<br/>`;
                                    });
                                    
                                  tableData += `</td><td>${(value.persons)}</td>
                                    <td>${(value.booked)}</td>
                                    <td>${(value.pending)}</td>
                                    <td><button class="btn btn-success btn-xs" onclick="getRoomDetails('${value.reservation_id}',${value.pending},'${value.id}');" data-toggle="tooltip" title="view rooms"><i class="fa fa-eye"></i></button></td>
                                </tr>
                                <tr id="tr_${value.reservation_id}" style="display:none"></tr>
                            `;
                        });
                        
                        $("#expected_arrivals").DataTable().destroy();
                        $("#expected_arrivals tbody").html(tableData);
                        initializeTableArrivals('#expected_arrivals',[0,1,3,5,6,7,8],true);
                    }
                });
        }
       
        roomStats = (date = "") => {
            $.ajax({
                url: "stats.php",
                data: "date=" + date,
                dataType: "JSON",
                success: function(resp) {
                var tableData = "";
                var firstCol = "";
                resp.forEach((value, key, arr) => {
                    if (firstCol != value.type) {
                    tableData += `<tr><td class="bg-info">${value.type}</td>`;
                    } else {
                    tableData += `<tr><td style="color:white">${value.type}</td>`;
                    }
                    firstCol = value.type;

                    tableData += `<td>${value.room_no}</td>`;

                    if (value.status == 1) {
                    tableData += `<td><small class="label pull-right bg-green">Occupied</small></td>`;
                    } else if (value.status == 2) {
                    tableData += `<td><small class="label pull-right bg-red">Dirty</small></td>`;
                    }

                    //var guestname = value.guest;    

                    tableData += `<td onclick="reservationDetails('${value.res_id}')"><a href="#" style="color:black;">${value.res_id}</a></td>
                                    <td onClick="guestDetails('${value.id}','edit')"><a href="#" style="color:black;">${value.guest}</a></td>    
                                    <td onclick="folioDetails('${value.id}')"><a href="#" style="color:black;">${value.folio}</a></td>
                                    <td>${value.checkin}</td>
                                    <td>${value.checkout}</td>
                                    <td>
                                        <button class="btn btn-warning">Extend</button>
                                        <button class="btn btn-info">Change</button>
                                    </td>
                                </tr>`;
                });

                $("#room_statistics")
                    .DataTable()
                    .destroy();
                $("#room_statistics tbody").html(tableData);
                initializeTableRoom("#room_statistics", [1, 3, 4, 5]);
                }
            });
        }

        folio = (date = "") =>{
            $.ajax({
                url: "folio.php",
                data: "date=" + date,
                dataType: "JSON", 
                success: function(resp){
                    var tableData = "";
                    var firstCol = "";  
                    resp.forEach((value, key, arr) => {

                        if (firstCol != value.type_no) {
                            tableData += `<tr><td style="background-color:#D9EDF7">${value.type_no}</td>`;   
                        } else{
                            tableData += `<tr><td></td>`;
                        }

                        firstCol = value.type_no;

                        tableData += `<td style="width: 80px;">${value.date}</td>`;

                        tableData += `<td>${value.invoice}</td>
                                        <td>${value.source}</td>    
                                        <td>${value.tariff}</td>
                                        <td>${value.extra_bed}</td>
                                        <td>${value.sgst}</td>
                                        <td>${value.cgst}</td>
                                        <td>${value.igst}</td>
                                        <td>${value.dr_amount}</td>
                                        <td>${value.cr_amount}</td>
                                        
                                        <td class="text-center">
                                            <input type="checkbox" name='DId[]' id="did" />
                                        </td>
                                        <td>
                                            <button class="btn btn-info btn-xs" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"> </i></button>
                                            <button class="btn btn-danger btn-xs" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i> </button>
                                        </td>
                                    </tr>`;
                    });  
                    $("#foliotable")
                    .DataTable()
                    .destroy();
                    $("#foliotable tbody").html(tableData);
                    initializeTableFolio("#foliotable", [1, 3, 4, 5]);  
                }  
            });   
        }

        arrivals(); 
        roomStats();
        folio();
        popup = ()=>{
            $("#checkinModal").modal('show');
        }

    });

   function getRoomDetails(resId,pending,userid){
    //alert(userid);
      var tr = "#tr_"+resId;
      $.ajax({
        url : 'ajax/ajaxGetRooms.php',
        type : 'POST',
        data : {resId:resId,Id:userid},
        dataType : 'JSON',
        success : function(resp){
          //alert(resp.RoomName);
          var tableData = '';
          tableData += `<td colspan="9"><div class="row">
                       <div class="col-md-12 col-sm-12">
                         <div class="box box-primary box-outline">
                           <div class="box-body">`;
                            resp.forEach((value,key,arr) => {
                            tableData += `
                              <div class="row" >
                                <div class="col-md-12 col-sm-12">
                                  <h4>${value.RoomName} <span id="roomTypeName_${resId}_${value.room_id}" class="text-primary"></span></h4> 
                                </div>

                                <div class="col-md-12">
                                  <div class="row text-center">`;
                                    value.RoomDetails.forEach((datavalue,keys,arrays) => {
                                      tableData += `<div class="col-md-1 col-sm-1 col-xs-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
                                    });
                                  tableData += `</div></div></div>`;
                            });
                            tableData += `
                            <div id="showBookedRoom_${resId}"></div>
                            </div>
                            <div class="box-footer">
                             <button class="btn btn-primary pull-right" onclick="userCheckIn('`+ resId +`');">CheckIn</button>
                           </div>
                         </div>
                       </div> 
                    </div></td>`;

                    $(tr).html(tableData).toggle();
        }
      });
    }
    
    /*function guestDetails(gId){
		alert(gId);
		$("#tab_4,#tab_1,#tab_2,#tab_3,#tab_6,.nav-tabs li").removeClass("active");
		$("#tab_5,#guest_tab").addClass("active");
		$("#DisplayGuestForm")
    }*/

    function guestDetails(gId = '',action=''){
      //alert(gId+" " +action);
        $.ajax({
           type: "POST",
           url: '../actions/editGuestForm.php',
           data:{gId:gId,action:action},     
           success: function (result) {     
                $("#tab_4,#tab_1,#tab_2,#tab_3,#tab_6,.nav-tabs li").removeClass("active");
                $("#tab_5,#guest_tab").addClass("active");
                $("#DisplayGuestForm").html(result);
            }
         })
  }  



   function folioDetails(Id){
       //alert("hello");
    $.ajax({
       type: "POST",
       url: 'ajax/ajaxFolioDetails.php',
       data:{ID:Id}, 
       dataType:'JSON',
       success: function (result) {   
           // $("#tab_2,#tab_1,#tab_3,.nav-tabs li").removeClass("active");
            $("#tab_3,#tab_1,#tab_2,#tab_4,#tab_5,.nav-tabs li").removeClass("active");
            $("#tab_6,#folio_tab").addClass("active");

            /* filling more options form here */
            $("#folio_no").html(result.Folio_no); 
            $("#folio_guestname").html(result.Name); 
            $("#folio_checkin").html(result.Check_in); 
            $("#folio_checkout").html(result.Check_out); 
            $("#folio_currenttotal").html(result.Current_total); 
            $("#folio_amtreceived").html(result.Amount_Received); 
            $("#folio_balance").html(result.Balance); 
            if (result.Status == 1){
                $("#foliostatus").html('<button class="btn btn-success btn-sm" type="button" id="f_status" >open</button>'); 
            }else{
                $("#foliostatus").html('<button class="btn btn-danger btn-sm" type="button" id="f_status">close</button>'); 
            }
            
        }
     }); 
  } 
  function reservationDetails(resId){
       //alert("hello");
    $.ajax({
       type: "POST",
       url: 'ajax/ajaxReservationDetails.php',
       data:{resID:resId}, 
       dataType:'JSON',
       success: function (result) {   
           // $("#tab_2,#tab_1,#tab_3,.nav-tabs li").removeClass("active");
            $("#tab_3,#tab_1,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
            $("#tab_2,#reservation_tab").addClass("active");

            /* filling more options form here */

            $("#res_bookingNo").val(result.res_id);
            $("#res_bookingDate").val(result.booking_date);
            $("#res_hotelName").html('<option value="'+ result.Hotel +'">'+ result.Hotel +'</option>'); 
            $("#res_checkinDate").val(result.CheckIn) ; 
            $("#res_checkOutDate").val(result.CheckOut) ;  
            $("#res_guestName").val(result.Guest+" | "+result.Email+" | "+result.res_id) ; 
            $("#newCheckout").val(result.CheckOut) ;
            $("#newRooms").val(result.Rooms) ;  
            $("#res_guestAddId").attr('onClick','guestDetails("","add")');
            $("#res_guestEditId").attr('onClick','guestDetails("'+result.Id+'","edit")');
        }
     }); 
  } 

  $(document).ready(function(){
        $(document).on('click','#f_status', function(){
            var btnValue = $(this).html();
            if(btnValue == "open"){
                var btnV = "close";
            }else{
               var btnV = "open"; 
            }
            //var value = confirm("Are you sure. You want to close this record");
            bootbox.confirm({
                message: "Are you sure ? You want to " + btnV + " this record.",
                buttons: {
                    confirm: {
                        label: 'Yes',
                        className: 'btn-success'
                    },
                    cancel: {
                        label: 'No',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                   if(result == true){
                        if(btnValue == "open"){
                        $("#foliostatus").html('<button class="btn btn-danger btn-sm" type="button" id="f_status">close</button>');
                        }else{
                        $("#foliostatus").html('<button class="btn btn-success btn-sm" type="button" id="f_status">open</button>'); 
                        }  
                    }
                }
            });
            
        });
  });  
</script>


<script type="text/javascript">
  //var roomArr = [];
  var roomArr = {};

  function selectRoom(roomTypeId,roomNo,btnId,resvId,pending){ 
    var btn = "#"+btnId;
    alert(btn);
    var divId = "#showBookedRoom_"+resvId;
    var roomTypeName = "#roomTypeName_"+resvId+"_"+roomTypeId;
    var roomTypeId = roomTypeId;
    let newRoomTypeId = resvId+"_"+roomTypeId;
    let roomCount = 0;
    if($(btn).hasClass("btn-success")){            
     for(var key in roomArr){
      roomCount = roomCount + roomArr[key].length;
      }
      if(pending == roomCount){
               bootbox.alert("Please Select only " + pending + " rooms");
      }else{ 
          $(btn).removeClass("btn-success").addClass("btn-danger");
          if(roomArr.hasOwnProperty(newRoomTypeId)){
             roomArr[newRoomTypeId].push(roomNo);
          }else{
             roomArr[newRoomTypeId] = [roomNo];
          }
          roomCount = 0;
          for(var key in roomArr){
            roomCount = roomCount + roomArr[key].length;
          }
         alert(JSON.stringify(roomArr));
      }
      $(roomTypeName).html("Selected [ " + roomArr[newRoomTypeId].length + " ]");
      $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomCount +' )</h4>');
      }
      else if($(btn).hasClass("btn-danger")){
        $(btn).removeClass("btn-danger").addClass("btn-success");
       var removeRoomArr = roomArr[newRoomTypeId];
       const index = removeRoomArr.indexOf(roomNo);
       if (index > -1) {
          removeRoomArr.splice(index, 1);
          roomArr[newRoomTypeId] = removeRoomArr;
          
         roomCount = 0;
              for(var key in roomArr){
                roomCount = roomCount + roomArr[key].length;
              }
        }
        if(roomCount == 0){
          $(divId).html('<div></div>');
          $(roomTypeName).html(roomArr[newRoomTypeId]);
        }else if(roomCount > 0){
            $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomCount +' )</h4>');
         if(roomArr[newRoomTypeId].length > 0){
          $(roomTypeName).html("Selected [ " + roomArr[newRoomTypeId].length + " ]");
          }else{
            $(roomTypeName).html(roomArr[newRoomTypeId]);
          }
        }
      }
  }
    
  function userCheckIn(resvId){
   //var room = roomArr.toString();
   let rooms = JSON.stringify(roomArr);
   let roomCount=0;
   for(var key in roomArr){
            roomCount = roomCount + roomArr[key].length;
    }
    if(roomCount>0){
        $.ajax({
          url: 'ajax/ajaxUserCheckIn.php',
          type: 'POST',
          data: {resvId : resvId, bookedRoom : rooms},
          //dataType: 'JSON',
          success : function(data){
            bootbox.alert(data);
          }
        });
    }
    else{
       bootbox.alert("Please Select a room");
    }
    
  }

</script>


<!--Start onchange calender1 view JS -->
<script>
function showUser(str) {
	//alert(str);
if(str==''){
	window.location.reload();
	$('#calendar').show();
	$('#calendar1').hide();
}
else{
	$('#calendar').hide();
}
		
	var substr = str.split('-');
	var str = substr[0];
	var name = substr[1];

	var calendarEl = document.getElementById("calendar1");
	//$("#calendar1").fullCalendar('removeEvents', resources);
	//debugger;
	
	if(window.appCalendar && window.appCalendar.destroy){
		window.appCalendar.destroy();
	}
	function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
var eventSourceCallback = null;
	 window.appCalendar = new FullCalendar.Calendar(calendarEl, {
		 datesRender: function(info){
			//debugger; 
			var startDate = formatDate(info.view.activeStart);
			var startEnd = formatDate(info.view.activeEnd);
			//window.appCalendar.getEventSources()[0].remove();
			//window.appCalendar.getEventSources()[0].refetch();
			//$(info.el).fullCalendar('refetchEvents');
			/*$.ajax({
				url: "ajax/load.php",
				type: 'POST',
				data: { startDate : startDate,startEnd : startEnd },
				dataType: "JSON",
				success: function(data) {
					if(eventSourceCallback){
						eventSourceCallback([]);
					}
					//$(info.el).fullCalendar('removeEvents')
					//$(info.el).fullCalendar('addEventSource',data);
					
					//debugger;
					console.log(data);
					//successCallback(data);
				}
			});
			*/
			
			
		 },
		 

		 eventLimit: true,
		 eventLimitText: "more",
		  eventLimit: 1 ,
		  dayMaxEvent:true,
		resourceAreaWidth: 200,
		resourcesInitiallyExpanded: false,
		selectable: true,
		defaultDate: moment(new Date()).subtract(2,'days').format('YYYY-MM-DD'),
		slotLabelFormat: [{ day:"2-digit",weekday: "short" }],
	
    allDaySlot: true,
    allDayText: 'Volledige dag',
    firstHour: 8,
    slotMinutes: 30,
    defaultEventMinutes: 120,
    axisFormat: 'HH:mm',
    timeFormat: {
        agenda: 'H:mm{ - h:mm}'
    },
    dragOpacity: {
        agenda: .5
    },
    minTime: 0,
    maxTime: 24,
	
		plugins: ["interaction", "resourceTimeline","resourceTimelinePlugin"],
		header: {
			left: "today prev,next",
			center: "title",
			right: 'agendaDay,customWeek,month'
		},
		// editable: true,
		aspectRatio: 1.5,
		defaultView: "agendaDay",

		views: {
			agendaDay: {
				type: "resourceTimeline",
				duration: { days: 15 },
				slotDuration: { days: 1 },
				buttonText: "15 Days"
			},			
			customWeek: {
				type: "resourceTimeline",
				duration: { days: 7 },
				slotDuration: { days: 1 },
				buttonText: "Week"
			},
			month: {
				type: "resourceTimeline",
				duration: { days: 31 },
				slotDuration: { days: 1 },
				buttonText: "Month"
			},
			
			timeGrid: {
      dayMaxEventRows: 2 // adjust to 6 only for timeGridWeek/timeGridDay
    },
			
			


		},
		
		resourceLabelText: "Room Types",
		resources: function(fetchInfo, successCallback, failureCallback) {
			//debugger;
			$.ajax({
				url: "resources.php",
				type: 'POST',
				data: { str : str },
				dataType: "JSON",
				
				success: function(data) {
                    //console.log(data);
					//debugger;
                    successCallback(data);
                }
			});
		
		},
		
		events: function(fetchInfo, successCallback, failureCallback) {
			// debugger;
			 var startDate= formatDate(fetchInfo.start);
			 var endDate = formatDate(fetchInfo.end);
			 eventSourceCallback = successCallback;
				$.ajax({
					url: "ajax/load.php",
						type: 'POST',
					data: { startDate : startDate,startEnd : endDate,id_hotel : str },
					dataType: "JSON",
					success: function(data) {
						//console.log(data);
						successCallback(data);
					}
				});
		},
		selectAllow:function(data){
			
			//debugger;
			if(data.resource._resource.parentId && data.resource._resource.parentId.length>0)
				return true;
			return false;
		},
		viewDisplay :function(element)
		{
		//debugger;	
		},
		select: function(info) {
			//debugger;
			let checkin = moment(info.start).format("YYYY-MM-DD");
            let checkout = moment(info.end).format("YYYY-MM-DD");
            let id_hotel = $(str).val();
            let id_room = 0;
            id_room = info.resource._resource.parentId == "" ? info.resource._resource.id : info.resource._resource.parentId;
            /* Setting vaules in reserve form */
            
            let roomType = info.resource._resource.parentId == "" ? info.resource._resource.title :window.appCalendar.getResourceById(info.resource._resource.parentId).title  ;
            let label = 'Room : '+roomType+',Checkin : '+moment(checkin).format('DD-MM-YYYY')+',Checkout : '+moment(checkout).subtract(1,'days').format('DD-MM-YYYY');
            $("#reservationModalLabel").html(label);
            $("#res_checkinDate").val(checkin);
            $("#res_checkOutDate").val(checkout);
            $("#res_hotelName").val(name);
            $("#res_room").val(id_room);
            $("#id_mst_hotels").val(str);
            $("#roomtype").val(roomType);
			
			$("#parentId").val(info.resource._resource.id);
			
            /* end */
            //$("#reservationModal").modal("show");
			//$("#more-options").click(function() {
			//$("#reservationModal").modal("hide");
			
			$("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active"); 
			$("#tab_2,#reservation_tab").addClass("active");
			//window.open(url, "_blank");
			//});
			
		}
	});
	//debugger;
 $(document).ready(function() {

    window.appCalendar.render();
	//debugger;

    jumpTodate = (date=$("#datepicker").val()) =>{

        window.appCalendar.gotoDate(moment(date).utc().format());
    }

    $(".select2").select2();
    $("#reservation").daterangepicker({
        locale: { format: "DD-MM-YYYY" }
    });
    $(".select2").select2();
    $("#reservation").daterangepicker({
        locale: { format: "DD-MM-YYYY" }
    });

    saveReservation = () => {
        $.ajax({
            url: "reservation.php",
            data: $("#reservationDetailss").serialize(),
            dataType:'JSON',
            success: function(data) {
               $("#reservationModal").modal("hide");
               window.appCalendar.refetchEvents()
            }
        });
    };

    $(document).on('change','#bookingType', function(){
      var bookingTypeId = $(this).val();
      if(bookingTypeId == 1){
        var directGuest = "<option value='Guest Company1'>Guest Company1</option><option value='Guest Company2'>Guest Company2</option><option value='Guest Company3'>Guest Company3</option>";
              $("#bookingSource").html(directGuest);
      }
      else if(bookingTypeId == 2){
        var travelAgent = "<option value='Travel Agent1'>Travel Agent1</option><option value='Travel Agent2'>Travel Agent2</option><option value='Travel Agent3'>Travel Agent3</option>";
              $("#bookingSource").html(travelAgent);
      }
      else if(bookingTypeId == 3){
        var corporate = "<option value='Corporate1'>Corporate 1</option><option value='Corporate2'>Corporate2</option><option value='Corporate3'>Corporate3</option>";
              $("#bookingSource").html(corporate);
      }
    });

    $(document).on('change','#rateType', function(){
      var rateType = $(this).val();
      if(rateType == "Adoc"){
          var directGuest = "<option value='Adoc1'>Adoc1</option><option value='Adoc2'>Adoc2</option><option value='Adoc3'>Adoc3</option>";
                $("#rateLetter").html(directGuest);
      }
      else if(rateType == "Contract"){
        var travelAgent = "<option value='Contract1'>Contract1</option><option value='Contract2'>Contract2</option><option value='Contract3'>Contract3</option>";
        $("#rateLetter").html(travelAgent);
      }
    });
});

//Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format:'yyyy-mm-dd'
    })
}
</script>
<!--End onchange calender1 view JS -->






<!--Start Default calender view JS -->
<script>
   var calendarEl = document.getElementById("calendar");

	if(window.appCalendar && window.appCalendar.destroy){
		window.appCalendar.destroy();
	}
	function formatDate(date) {
		var d = new Date(date),
			month = '' + (d.getMonth() + 1),
			day = '' + d.getDate(),
			year = d.getFullYear();

		if (month.length < 2) 
			month = '0' + month;
		if (day.length < 2) 
			day = '0' + day;

		return [year, month, day].join('-');
	}


var eventSourceCallback = null;
	window.appCalendar = new FullCalendar.Calendar(calendarEl, {
	    datesRender: function(info){
			//debugger; 
			var startDate = formatDate(info.view.activeStart);
			var startEnd = formatDate(info.view.activeEnd);
	    },
		 
		  eventLimit: true,
		  eventLimitText: "more",
		  eventLimit: 1 ,
		  dayMaxEvent:true,
          resourceAreaWidth: 200,
          resourcesInitiallyExpanded: false,
          selectable: true,
          defaultDate: moment(new Date()).subtract(2,'days').format('YYYY-MM-DD'),
          slotLabelFormat: [{ day:"2-digit",weekday: "short" }],
       
       
		allDaySlot: true,
		allDayText: 'Volledige dag',
		firstHour: 8,
		slotMinutes: 30,
		defaultEventMinutes: 120,
		axisFormat: 'HH:mm',
		timeFormat: {
			agenda: 'H:mm{ - h:mm}'
		},
		dragOpacity: {
			agenda: .5
		},
		minTime: 0,
		maxTime: 24,
				
		plugins: ["interaction", "resourceTimeline","resourceTimelinePlugin"],
		header: {
				left: "today prev,next",
				center: "title",
				right: 'agendaDay,customWeek,month'
			},
        // editable: true,
        aspectRatio: 1.5,
        defaultView: "agendaDay",
        views: {
			
			agendaDay: {
				type: "resourceTimeline",
				duration: { days: 15 },
				slotDuration: { days: 1 },
				buttonText: "15 Days"
			},			
			customWeek: {
				type: "resourceTimeline",
				duration: { days: 7 },
				slotDuration: { days: 1 },
				buttonText: "Week"
			},
			month: {
				type: "resourceTimeline",
				duration: { days: 31 },
				slotDuration: { days: 1 },
				buttonText: "Month"
			},
			
			timeGrid: {
				dayMaxEventRows: 2 // adjust to 6 only for timeGridWeek/timeGridDay
			},
		
		},
        resourceLabelText: "Room Types",
        resources: function(fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: "resources.php",
                dataType: "JSON",
                success: function(data) {
                    console.log(data);
                    successCallback(data);
                }
            });
        },
       events: function(fetchInfo, successCallback, failureCallback) {
			// debugger;
			 var startDate= formatDate(fetchInfo.start);
			 var endDate = formatDate(fetchInfo.end);
			 eventSourceCallback = successCallback;
				$.ajax({
					url: "ajax/load.php",
						type: 'POST',
					data: { startDate : startDate,startEnd : endDate,id_hotel : 65 },
					dataType: "JSON",
					success: function(data) {
						//console.log(data);
						successCallback(data);
					}
				});
		},
		selectAllow:function(data){
			
			//debugger;
			if(data.resource._resource.parentId && data.resource._resource.parentId.length>0)
				return true;
			return false;
		},
		viewDisplay :function(element)
		{
		//debugger;	
		},
        select: function(info) {
			//debugger;
			let checkin = moment(info.start).format("YYYY-MM-DD");
            let checkout = moment(info.end).format("YYYY-MM-DD");
          //  let id_hotel = $(65).val();
            let id_room = 0;
            id_room = info.resource._resource.parentId == "" ? info.resource._resource.id : info.resource._resource.parentId;
            /* Setting vaules in reserve form */
            
            let roomType = info.resource._resource.parentId == "" ? info.resource._resource.title :window.appCalendar.getResourceById(info.resource._resource.parentId).title  ;
            let label = 'Room : '+roomType+',Checkin : '+moment(checkin).format('DD-MM-YYYY')+',Checkout : '+moment(checkout).subtract(1,'days').format('DD-MM-YYYY');
            $("#reservationModalLabel").html(label);
            $("#res_checkinDate").val(checkin);
            $("#res_checkOutDate").val(checkout);
            $("#res_hotelName").val('Bal Samand Lake Palace');
            $("#res_room").val(id_room);
            $("#id_mst_hotels").val(65);
            $("#roomtype").val(roomType);
			
			$("#parentId").val(info.resource._resource.id);
			
			$("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active"); 
			$("#tab_2,#reservation_tab").addClass("active");
			
		}
	});
	
$(document).ready(function() {
	
    window.appCalendar.render();

    jumpTodate = (date=$("#datepicker").val()) =>{
        window.appCalendar.gotoDate(moment(date).utc().format());
    }

    $(".select2").select2();
    $("#reservation").daterangepicker({
        locale: { format: "DD-MM-YYYY" }
    });
   

    saveReservation = () => {
        $.ajax({
            url: "reservation.php",
            data: $("#reservationForm").serialize(),
            dataType:'JSON',
            success: function(data) {
               $("#reservationModal").modal("hide");
               window.appCalendar.refetchEvents()
            }
        });
    };

    $(document).on('change','#bookingType', function(){
      var bookingTypeId = $(this).val();
      if(bookingTypeId == 1){
        var directGuest = "<option value='Guest Company1'>Guest Company1</option><option value='Guest Company2'>Guest Company2</option><option value='Guest Company3'>Guest Company3</option>";
              $("#bookingSource").html(directGuest);
      }
      else if(bookingTypeId == 2){
        var travelAgent = "<option value='Travel Agent1'>Travel Agent1</option><option value='Travel Agent2'>Travel Agent2</option><option value='Travel Agent3'>Travel Agent3</option>";
              $("#bookingSource").html(travelAgent);
      }
      else if(bookingTypeId == 3){
        var corporate = "<option value='Corporate1'>Corporate 1</option><option value='Corporate2'>Corporate2</option><option value='Corporate3'>Corporate3</option>";
              $("#bookingSource").html(corporate);
      }
    });

    $(document).on('change','#rateType', function(){
      var rateType = $(this).val();
      if(rateType == "Adoc"){
          var directGuest = "<option value='Adoc1'>Adoc1</option><option value='Adoc2'>Adoc2</option><option value='Adoc3'>Adoc3</option>";
                $("#rateLetter").html(directGuest);
      }
      else if(rateType == "Contract"){
        var travelAgent = "<option value='Contract1'>Contract1</option><option value='Contract2'>Contract2</option><option value='Contract3'>Contract3</option>";
        $("#rateLetter").html(travelAgent);
      }
    });

});

//Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format:'yyyy-mm-dd'
    })
	
</script>
<!--End Default calender view JS -->




<script type="text/javascript">
  $("#preDate").change(function(){
    var date = $(this).val();
    $("#reservation_date").val(date);
  });
</script>

<script type="text/javascript">
    $(document).ready(function(){

        $(document).on('change','#res_bookingStatus',function(){
            var bookingStatus = $(this).val();
            if(bookingStatus == "Tentative"){

                var holdtill = '<div class="form-group row"> <label for="res_holdTillDate" class="col-sm-3 col-md-3 col-form-label">Hold Till Date</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control datepicker" name="res_holdTillDate" id="res_holdTillDate" placeholder="dd-mm-yyyy"data-parsley-errors-container="#res_holdTillDateError" data-parsley-required /><span id="res_holdTillDateError"><?php echo $err_res_holdTillDateError;?></span></div></div>';

                $("#res_bookingList").html(holdtill);

            }
            else if(bookingStatus == "Cancelled"){

                var cancelled = '<div class="form-group row"> <label for="res_cancellation" class="col-sm-3 col-md-3 col-form-label">Cancellation Reason</label><div class="col-sm-6 col-md-6"><select class="form-control select2" style="width: 100%;" id="res_cancellation" name="res_cancellation" data-parsley-errors-container="#res_cancellationError" data-parsley-required><option value="Confirmed" selected="selected">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option></select><span id="res_cancellationError"><?php echo $err_res_cancellationError;?></span></div></div>';

                $("#res_bookingList").html(cancelled);
            }
        });


        $(document).on('change','#res_bookingType', function(){
            var bookingTypeId = $(this).val();
            if(bookingTypeId == 1){
                var directGuest = "<option value='Guest Company1'>Guest Company1</option><option value='Guest Company2'>Guest Company2</option><option value='Guest Company3'>Guest Company3</option>";
                $("#res_source").html(directGuest);
            }
            else if(bookingTypeId == 2){
                var travelAgent = "<option value='Travel Agent1'>Travel Agent1</option><option value='Travel Agent2'>Travel Agent2</option><option value='Travel Agent3'>Travel Agent3</option>";
                $("#res_source").html(travelAgent);
            }
            else if(bookingTypeId == 3){
                var corporate = "<option value='Corporate1'>Corporate 1</option><option value='Corporate2'>Corporate2</option><option value='Corporate3'>Corporate3</option>";
                $("#res_source").html(corporate);
            }else{
              $("#res_source").html('<option value="">Select Source</option');
            }
        });

        $(document).on('change','#res_rateType', function(){
            var rateType = $(this).val();
            if(rateType == "Adoc"){
                var directGuest = "<option value='Adoc1'>Adoc1</option><option value='Adoc2'>Adoc2</option><option value='Adoc3'>Adoc3</option>";
                $("#res_rateLetter").html(directGuest);
            }
            else if(rateType == "Contract"){
                var travelAgent = "<option value='Contract1'>Contract1</option><option value='Contract2'>Contract2</option><option value='Contract3'>Contract3</option>";
                $("#res_rateLetter").html(travelAgent);
            }else{
              $("#res_rateLetter").html('<option value="">Select Rate Letter</option');
            }
        });

        $(document).on('change','#res_pickuprequired', function(){
            var pickupStatus = $(this).val();
            if(pickupStatus == "Yes"){

                var pickup = '<div class="form-group row"><label for="res_modeoftravel" class="col-sm-3 col-md-3 col-form-label">Mode of Travel</label><div class="col-sm-6 col-md-6"><select class="form-control  select2" style="width:100%" id="res_modeoftravel" name="res_modeoftravel" data-parsley-errors-container="#res_modeoftravelError" data-parsley-required><option selected="selected" value="">Select please</option><option value="By Air">By Air</option><option value="By Train">By Train</option><option value="By Road">By Road</option></select><span id="res_modeoftravelError"><?php echo $err_res_modeoftravelError;?> </span></div></div><div class="form-group row"><label for="res_pickupdetails" class="col-sm-3 col-md-3 col-form-label">Pickup Details</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_pickupdetails" name="res_pickupdetails"placeholder="Enter Pickup Details" disableddata-parsley-errors-container="#res_pickupdetailsError" data-parsley-required /><span id="res_pickupdetailsError"><?php echo $err_res_pickupdetailsError;?></span></div></div><div class="form-group row"><label for="res_arrivingfrom" class="col-sm-3 col-md-3 col-form-label">Arriving from</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_arrivingfrom" name="res_arrivingfrom"placeholder="Enter Arriving from" disableddata-parsley-errors-container="#res_arrivingfromError" data-parsley-required /><span id="res_arrivingfromError"><?php echo $err_res_arrivingfromError;?></span></div></div><div class="form-group row"><label for="res_departingto" class="col-sm-3 col-md-3 col-form-label">Departing to</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_departingto" name="res_departingto"placeholder="Enter Departing to" disableddata-parsley-errors-container="#res_departingtoError" data-parsley-required /><span id="res_departingtoError"><?php echo $err_res_departingtoError;?></span></div></div>';

                $("#pickupDetails").html(pickup);
            }
            else if(pickupStatus == "No"){
                var pickup = "";
                $("#pickupDetails").html(pickup);
            }
        });

    });


    // addons function

    function addOns(){
      var x = 0; //Initial field counter
      var list_maxField = 10; //Input fields increment limitation
      //Check maximum number of input fields
      if(x < list_maxField){ 

          x++; //Increment field counter
		/*   $.ajax({
		   url: 'addon.php',
		   success: function(html) {
			  $("#addons").append(html);
		   }
		}); */

        var list_fieldHTML = '<tr><td><select class="form-control parsley-error" style="width: 120px;" name="item" id="item"data-parsley-required><option selected="selected" value="">Select Item</option><option value="Item1">Item1</option><option class="Item2">Item2</option></select></td><td><input type="text" class="form-control parsley-error" style="width:140px;" name="additionalcharges" id="additionalcharges" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width:100px;" name="qty" id="qty" data-parsley-required/></td><td><select class="form-control parsley-error" style="width: 120px;" name="unit" id="unit"data-parsley-required><option selected="selected" value="">Select Unit</option><option value="Day">Day</option><option class="Nos">Nos</option></select></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="rate" id="rate" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="tax" id="tax" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="taxvalue" id="taxvalue" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width:110px;"name="amount" id="amount" data-parsley-required/></td><td><button class="btn btn-danger addons_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>'; //New input field html 
         $('#addons').append(list_fieldHTML); //Add field html
      }

      //Once remove button is clicked
      $('#addons').on('click', '.addons_remove', function()
      {
         $(this).closest('tr').remove(); //Remove field html
         x--; //Decrement field counter
      });
    }

    // roomsRates function

    function roomsRates(){
      var x = 0; //Initial field counter
      var list_maxField = 10; //Input fields increment limitation
      //Check maximum number of input fields
      if(x < list_maxField){ 

          x++; //Increment field counter
	    /* $.ajax({
		   url: 'ajax/room.php',
		   success: function(html) {
			  $("#roomsRates").append(html);
		   }
		}); */
          var list_fieldHTML = '<tr><td><select class="form-control parsley-error" name="roomtype" id="roomtype" data-parsley-required style="width: 120px;"><option selected="selected" value="">Room type</option><option value="Cottage Villa">Cottage Villa</option><option class="Waitlist Room">Waitlist Room</option></select></td><td><select class="form-control parsley-error" name="plan" id="plan" data-parsley-required style="width: 100px;"><option selected="selected" value="">Plan</option><option value="Plan1">Plan1</option><option class="Plan2">Plan2</option></select></td><td class="form-group"><input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms" id="noofRooms" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson" id="adultperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson" id="childperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild" id="extrachild" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson" id="tariffperperson" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes" id="taxes" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight" id="chargespernight" data-parsley-required/></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></td></tr>'; //New input field html 
        <!--  var list_fieldHTML = '<tr><td><select class="form-control parsley-error" name="roomtype['+x+']" id="roomtype" data-parsley-required style="width: 120px;"><option selected="selected" value="">Room type</option><option value="Cottage Villa">Cottage Villa</option><option class="Waitlist Room">Waitlist Room</option></select></td><td><select class="form-control parsley-error" name="plan['+x+']" id="plan" data-parsley-required style="width: 100px;"><option selected="selected" value="">Plan</option><option value="Plan1">Plan1</option><option class="Plan2">Plan2</option></select></td><td class="form-group"><input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms['+x+']" id="noofRooms" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson['+x+']" id="adultperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson['+x+']" id="childperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild['+x+']" id="extrachild" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson['+x+']" id="tariffperperson" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes['+x+']" id="taxes" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight['+x+']" id="chargespernight" data-parsley-required/></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></td></tr>'; //New input field html -->
          $('#roomsRates').append(list_fieldHTML); //Add field html
      }

      //Once remove button is clicked
      $('#roomsRates').on('click', '.roomsRates_remove', function()
      {
         $(this).closest('tr').remove(); //Remove field html
         x--; //Decrement field counter
      });
    }
</script>

<script type="text/javascript">
	const curr_user = Math.random().toString(32).substring(2,10)+Math.random().toString(32).substring(2,30);
	console.log(curr_user);
	$(document).ready(function(){
		var websocket = new WebSocket("ws://localhost:8090"); 
		
		websocket.onopen = function(event) { 
			//console.log("Connection is established!");		
		}
		
		websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
			//console.log(Data);
			if(Data.chat_user == curr_user){
				window.location.reload();
				return;
			}
			if((Data.chat_user != null) && (Data.message_type == 'event')){
				 window.appCalendar.refetchEvents();
			}
		};
		
		websocket.onerror = function(event){
			//console.log("Problem due to some Error");
		};
		websocket.onclose = function(event){
			//console.log("Connection Closed");
		}; 
		
		
		
		$("#reservationDetailss").submit(function(e){
			
      	  e.preventDefault();
        	var formData = $("#reservationDetailss").serialize();
			
        	$.ajax({
        		type: "POST",
        	    url: 'ajax/ajaxOneWindow.php',
        	    data: formData,
        	    success: function(data){
				// alert(data);
					var messageJSON = {
						chat_user: curr_user,
						chat_message: 'new event added'
					};
					websocket.send(JSON.stringify(messageJSON));
					
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
	
	function refetchEventsCal(){
       calendar.refetchEvents();
    };
	
</script>

<script>
  function reload(){
	window.location.reload();
	}
</script>

