<?php include_once("../config/auto_loader.php"); ?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<style type="text/css">
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
</style>

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
                    <a href="#tab_1" data-toggle="tab">Reservation Chart</a>
                </li>
                <li id="reservation_tab"><a href="#" data-toggle="tab">Reservations</a></li>
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
                       <div class="col-md-12">
                         <div class="box box-primary box-outline">
                           <div class="box-header with-border" >
                             <h3 class="box-title">Rooms Detail</h3>
                           </div>
                           <div class="box-body">
                              <div class="row">
                                <div class="col-md-12 col-sm-12">
                                  <h4>Delux Room</h4> 
                                  <hr/>
                                </div>
                                <div class="col-md-12">
                                  <div class="row text-center">
                                    <div class="col-md-1 col-sm-1" style="padding-right:0px; margin-top: 2px; "><button class="btn btn-success btn-block" onclick="selectRoom();">101</button></div>
                                  </div>
                                </div>
                              </div>
                              <hr/>
                              <div class="row">
                                <div class="col-md-12 col-sm-12">
                                  <h4>Delux Room</h4> 
                                  <hr/>
                                </div>
                                <div class="col-md-12">
                                  <div class="row text-center">
                                    <div class="col-md-1 col-sm-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" onclick="selectRoom();">101</button></div>
                                  </div>
                                </div>
                              </div>
                           </div>

                           <div class="box-footer">
                             <button class="btn btn-primary pull-right">CheckIn</button>
                           </div>
                         </div>
                       </div> 
                    </div>
                </div>
                <div class="tab-pane " id="tab_2">
                     <!-- Reservation -->
                    <div class="row ">
                        <div class="col-xs-12">
                            <!-- form start -->
                            <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
                                <div class="box box-success">
                                    <div class="box-header with-border bg-color-success">
                                        <h3 class="box-title">Main Information</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md- col-sm-6 col-xs-12 right_border">
                                              <div class="form-horizontal">
                                                  <div class="form-group row">
                                                      <label for="res_bookingNo" class="col-sm-3 col-md-3 col-form-label">Booking Number</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" id="res_bookingNo" name="res_bookingNo" value="w310" disabled />
                                                      </div>
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_bookingDate" class="col-sm-3 col-md-3 col-form-label">Booking Date</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control datepicker" id="res_bookingDate" name="res_bookingDate" placeholder="dd-mm-yyyy"   data-parsley-errors-container="#res_bookingDateError" data-parsley-required />
                                                          <span id="res_bookingDateError"><?php echo $err_res_bookingDateError;?></span>
                                                      </div>

                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_bookingStatus" class="col-sm-3 col-md-3 col-form-label">Booking Status</label>
                                                      <div class="col-sm-6 col-md-6">
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
                                                      <label for="for="res_hotelName" class="col-sm-3 col-md-3 col-form-label">Hotel Name</label>
                                                      <div class="col-sm-9 col-md-9">
                                                          <select class="form-control select2" style="width: 100%;" id="res_hotelName" name="res_hotelName" data-parsley-errors-container="#res_hotelNameError" data-parsley-required>
                                                              <option selected="selected" value="">Select Hotel</option>
                                                          </select>
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
                                                          <button class="btn btn-success btn-sm" id="res_guestEditId"><i class="fa fa-edit"></i></button>
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
                                                          <button class="btn btn-success btn-sm" id="res_bookerEditId"><i class="fa fa-edit"></i></button>
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
                                                          <select class="form-control  select2" style="width:100%" id="res_paymentStatus" name="res_paymentStatus" data-parsley-errors-container="#res_paymentStatusError" data-parsley-required>
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
                                        <button type="submit" class="btn btn-success">Add</button>
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
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-xs-6 col-md-3 col-sm-6">
                                <h3 class="box-title">Add Guest Details</h3>
                            </div>
                            <!-- form start -->
                            <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
                            <div class="col-xs-6 col-md-3 col-sm-6 text-center">
                                <h3  class="box-title" style="font-size: 16px;">Guest Registration Number : 44</h3>
                            </div>
                            <div class="col-xs-12 col-md-6 col-sm-6">
                                <div class="input-group"> 
                                    <select class="form-control select2" style="width: 100%;" id="user" name="user">
                                      <option selected="selected" value="">Select person</option> 
                                      <option value="India"></option>
                                      <option value="Alaska">Alaska</option>
                                      <option value="California">California</option>
                                      <option value="Delaware">Delaware</option>
                                      <option value="Tennessee">Tennessee</option>
                                      <option value="Texas">Texas</option>
                                      <option value="Washington">Washington</option>
                                    </select>
                                    <div class="input-group-addon">
                                        <i class="fa fa-plus"></i> 
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        <div class="box-body">
                            <!-- /.box-header -->
                            <!-- form start -->
                            <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
                            
                                <div class="card text-dark bg-light">
                                    <div class="bg-primary text-center">
                                        <h5 style="padding: 5px;">General Details</h5>
                                    </div> 
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="title">Title<font color="#FF0000">*</font></label>
                                              <select class="form-control select2" style="width: 100%;" id="title" name="title" data-parsley-errors-container="#titleError" data-parsley-required>
                                                    <option selected="selected" value="">Select Title</option> 
                                                    <option value="Mr.">Mr.</option>    
                                                    <option value="Mrs.">Mrs.</option>
                                                </select>
                                            <span id="titleError"><?php echo $err_title;?></span>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="fname">Guest Firstname<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user-o"></i> 
                                                </div>
                                              <input type="text" class="form-control" placeholder="Enter Guest Firstname" id="fname" name="fname" data-parsley-errors-container="#fnameError" data-parsley-required/>
                                            </div>
                                            <span id="fnameError"><?php echo $err_fnameError;?></span>
                                        </div>
                                         <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="lname">Lastname<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user-o"></i> 
                                                </div>
                                              <input type="text" class="form-control" placeholder="Enter Lastname" id="lname" name="lname" data-parsley-errors-container="#lnameError" data-parsley-required/>
                                            </div>
                                            <span id="lnameError"><?php echo $err_lnameError;?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                       <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="mobile">Mobile Number<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-mobile"></i> 
                                                </div>
                                              <input type="number" class="form-control" placeholder="Enter Mobile Number" id="mobile" name="mobile" data-parsley-errors-container="#mobileError" data-parsley-required />
                                            </div>
                                            <span id="mobileError"><?php echo $err_mobileError;?></span>
                                        </div>
                                         <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="phone">Phone Number<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-phone"></i> 
                                                </div>
                                              <input type="number" class="form-control" placeholder="Enter Mobile Number" id="phone" name="phone" data-parsley-errors-container="#phoneError" data-parsley-required />
                                            </div>
                                            <span id="mobileError"><?php echo $err_mobileError;?></span>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="email">Email Id<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-envelope"></i> 
                                                </div>
                                              <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id" data-parsley-errors-container="#emailError" data-parsley-required />
                                            </div>
                                            <span id="emailError"><?php echo $err_emailError;?></span>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="address">Address<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-building"></i> 
                                                </div>
                                              <input type="text" class="form-control" placeholder="Enter Address" id="address" name="address" data-parsley-errors-container="#addressError" data-parsley-required/>
                                            </div>
                                            <span id="addressError"><?php echo $err_addressError;?></span>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="city">City<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-home"></i> 
                                                </div>
                                              <input type="text" class="form-control" id="city" name="city" placeholder="Enter City" data-parsley-errors-container="#cityError" data-parsley-required/>
                                            </div>
                                            <span id="cityError"><?php echo $err_cityError;?></span>
                                        </div>
                                         <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="country">Country<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-flag"></i> 
                                                </div>
                                                <select class="form-control select2" style="width: 100%;" id="country" name="country" data-parsley-errors-container="#countryError" data-parsley-required>
                                                  <option selected="selected" value="">Select Country</option> 
                                                  <option value="India">India</option>
                                                  <option value="Alaska">Alaska</option>
                                                  <option value="California">California</option>
                                                  <option value="Delaware">Delaware</option>
                                                  <option value="Tennessee">Tennessee</option>
                                                  <option value="Texas">Texas</option>
                                                  <option value="Washington">Washington</option>
                                                </select>
                                            </div>
                                            <span id="countryError"><?php echo $err_countryError;?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="state">State<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-adjust"></i> 
                                                </div>
                                                  <select class="form-control select2" style="width: 100%;" id="state" name="state" data-parsley-errors-container="#stateError" data-parsley-required>
                                                    <option selected="selected" value="">Select State</option> 
                                                    <option  value="Uttar Pradesh">Uttar Pradesh</option>
                                                    <option value="Madhya Pradesh">Madhya Pradesh</option>
                                                    <option value="California">California</option>
                                                    <option value="Delaware">Delaware</option>
                                                    <option value="Tennessee">Tennessee</option>
                                                    <option value="Texas">Texas</option>
                                                    <option value="Washington">Washington</option>
                                                </select>  
                                            </div>
                                            <span id="stateError"><?php echo $err_stateError;?></span>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="pincode">Pincode<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-map-pin"></i> 
                                                </div>
                                              <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Enter Pincode"  data-parsley-errors-container="#pincodeError" data-parsley-required />
                                            </div>
                                             <span id="pincodeError"><?php echo $err_pincodeError;?></span>
                                        </div>
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="nationality">Nationality<font color="#FF0000">*</font></label>
                                             <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-flag-o"></i> 
                                                </div>
                                                  <select class="form-control select2" style="width: 100%;" id="nationality" name="nationality" data-parsley-errors-container="#nationalityError" data-parsley-required>
                                                    <option selected="selected" value="">Select Nationality</option> 
                                                    <option  value="Indian">Indian</option>
                                                    <option value="American">American</option>
                                                </select>  
                                            </div>
                                            <span id="nationalityError"><?php echo $err_nationalityError;?></span>
                                        </div>  
                                    </div>
                                    
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="dobday">Date of Birth<font color="#FF0000">*</font></label>
                                           <div class="row">
                                               <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-birthday-cake"></i> 
                                                        </div> 
                                                        <select class="form-control select2" style="width: 100%;" id="dobday" name="dobday" data-parsley-errors-container="#dobdayError" data-parsley-required>
                                                            <option selected="selected" value="">Select Day</option> 
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                            <option value="13">13</option>
                                                            <option value="14">14</option>
                                                            <option value="15">15</option>
                                                            <option value="16">16</option>
                                                            <option value="17">17</option>
                                                            <option value="18">18</option>
                                                            <option value="19">19</option>
                                                            <option value="20">20</option>
                                                            <option value="21">21</option>
                                                            <option value="22">22</option>
                                                            <option value="23">23</option>
                                                            <option value="24">24</option>
                                                            <option value="25">25</option>
                                                            <option value="26">26</option>
                                                            <option value="27">27</option>
                                                            <option value="28">28</option>
                                                            <option value="29">29</option>
                                                            <option value="30">30</option>
                                                            <option value="30">31</option>
                                                        </select>  
                                                    </div>
                                                    <span id="dobdayError"><?php echo $err_dobdayError;?></span>
                                                </div>
                                                <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i> 
                                                        </div> 
                                                        <select class="form-control select2" style="width: 100%;" id="dobmonth" name="dobmonth" data-parsley-errors-container="#dobmonthError" data-parsley-required>
                                                        <option selected="selected" value="">Select Month</option> 
                                                        <option value="January">January</option>
                                                        <option value="February">February</option>
                                                        <option value="March">March</option>
                                                        <option value="April">April</option>
                                                        <option value="May">May</option>
                                                        <option value="June">June</option>
                                                        <option value="July">July</option>
                                                        <option value="August">August</option>
                                                        <option value="September">September</option>
                                                        <option value="October">October</option>
                                                        <option value="November">November</option>
                                                        <option value="December">October</option>
                                                    </select>  
                                                </div>
                                                <span id="dobmonthError"><?php echo $err_dobmonthError;?></span>
                                                </div>
                                           </div> 
                                        </div>   
                                       
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="anniversary">Anniversary<font color="#FF0000">*</font></label>
                                            <div class="row">
                                               <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-gift"></i> 
                                                        </div> 
                                                        <select class="form-control select2" style="width: 100%;" id="anniversaryday" name="anniversaryday" data-parsley-errors-container="#anniversarydayError" data-parsley-required>
                                                            <option selected="selected" value="">Select Day</option> 
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                            <option value="6">6</option>
                                                            <option value="7">7</option>
                                                            <option value="8">8</option>
                                                            <option value="9">9</option>
                                                            <option value="10">10</option>
                                                            <option value="11">11</option>
                                                            <option value="12">12</option>
                                                            <option value="13">13</option>
                                                            <option value="14">14</option>
                                                            <option value="15">15</option>
                                                            <option value="16">16</option>
                                                            <option value="17">17</option>
                                                            <option value="18">18</option>
                                                            <option value="19">19</option>
                                                            <option value="20">20</option>
                                                            <option value="21">21</option>
                                                            <option value="22">22</option>
                                                            <option value="23">23</option>
                                                            <option value="24">24</option>
                                                            <option value="25">25</option>
                                                            <option value="26">26</option>
                                                            <option value="27">27</option>
                                                            <option value="28">28</option>
                                                            <option value="29">29</option>
                                                            <option value="30">30</option>
                                                            <option value="30">31</option>
                                                        </select>  
                                                    </div>
                                                    <span id="anniversarydayError"><?php echo $err_anniversarydayError;?></span>
                                                </div>
                                                <div class="form-group col-xs-12 col-md-6 col-sm-2">
                                                    <div class="input-group">
                                                        <div class="input-group-addon">
                                                            <i class="fa fa-calendar"></i> 
                                                        </div> 
                                                        <select class="form-control select2" style="width: 100%;" id="anniversarymonth" name="anniversarymonth" data-parsley-errors-container="#anniversarymonthError" data-parsley-required>
                                                        <option selected="selected" value="">Select Month</option> 
                                                        <option value="January">January</option>
                                                        <option value="February">February</option>
                                                        <option value="March">March</option>
                                                        <option value="April">April</option>
                                                        <option value="May">May</option>
                                                        <option value="June">June</option>
                                                        <option value="July">July</option>
                                                        <option value="August">August</option>
                                                        <option value="September">September</option>
                                                        <option value="October">October</option>
                                                        <option value="November">November</option>
                                                        <option value="December">December</option>
                                                    </select>  
                                                </div>
                                                <span id="anniversarymonthError"><?php echo $err_anniversarymonthError;?></span>
                                                </div>
                                           </div>
                                            
                                        </div> 
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="gender">Gender<font color="#FF0000">*</font></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-intersex"></i> 
                                                </div>
                                                <select class="form-control select2" style="width: 100%;" id="gender" name="gender" data-parsley-errors-container="#genderError" data-parsley-required>
                                                  <option selected="selected" value="">Select Gender</option>    
                                                  <option  value="male">Male</option>
                                                  <option value="female">Female</option>
                                                </select>
                                            </div>
                                            <span id="genderError"><?php echo $err_genderError;?></span>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="guestType">Guest VIP Status<font color="#FF0000">*</font></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-user"></i> 
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"  id="guestType" name="guestType" data-parsley-errors-container="#guestTypeError" data-parsley-required>
                                                  <option selected="selected" value="">Select Guest Status</option>    
                                                  <option value="VIP">VIP</option>
                                                  <option value="VIP">CIP</option>
                                                </select>
                                            </div>
                                            <span id="guestTypeError"><?php echo $err_guestTypeError;?></span>
                                        </div>     
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="memebership">Membership Status <font color="#FF0000">*</font></label>
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-group"></i> 
                                                </div>
                                                <select class="form-control select2" style="width: 100%;"  id="memebership" name="memebership" data-parsley-errors-container="#memebershipError" data-parsley-required>
                                                  <option selected="selected" value="">Select Memebership</option>    
                                                  <option value="Active">Active</option>
                                                  <option value="Deactive">Deactive</option>
                                                </select>
                                            </div>
                                            <span id="memebershipError"><?php echo $err_memebershipError;?></span>
                                        </div> 
                                        <div class="form-group col-xs-12 col-md-4 col-sm-2">
                                            <label for="guestnote">Guest Note <font color="#FF0000">*</font></label>   
                                            <div class="input-group">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-comment"></i> 
                                                </div>
                                                <textarea class="form-control" name="guestnote" id="guestnote" data-parsley-errors-container="#guestnoteError" data-parsley-required></textarea>
                                            </div>
                                            <span id="guestnoteError"><?php echo $err_guestnoteError;?></span>
                                        </div>    
                                    </div>
                                </div>  
                                <hr>
                                 
                                <div class="card text-dark bg-light">
                                    <div class="bg-primary text-center ">
                                        <h5 style="padding: 5px;">ID Proof Details</h5>
                                    </div> 
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-xs-12 col-md-3 col-sm-2">
                                            <label for="idProof">Id Proof Details<font color="#FF0000">*</font></label>
                                            <div class="input-group"> 
                                                <div class="input-group-addon">
                                                    <i class="fa fa-address-card"></i> 
                                                </div>
                                                <select class="form-control select2" style="width: 100%;" id="idProof" name="idProof" data-parsley-errors-container="#idProofError" data-parsley-required>
                                                    <option selected="selected" value="">Select Id Proof</option> 
                                                    <option value="Voter_Id">Voter Id</option>    
                                                    <option value="Passport">Passport</option>
                                                    <option value="Aadhar">Aadhar</option>
                                                </select>
                                            </div>
                                            <span id="idProofError"><?php echo $err_idProofError;?></span>
                                        </div>
                                        <span id="appenddata"></span>  
                                    </div>
                                </div>
                                <hr>
                                <div class="card text-dark bg-light">
                                    <div class="bg-primary text-center ">
                                        <h5 style="padding: 5px;">Guest Photo</h5>
                                    </div> 
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="image">Guest Photo &nbsp;&nbsp;</label>
                                                <div class="btn btn-default btn-file">
                                                  <i class="fa fa-upload"></i> Upload
                                                 <input type="file" class="form-control" placeholder="Item Image" id="item_image" name="item_image" value="">   
                                                 <input type="hidden" name="old_image" value="<?php echo stripslashes($row->item_image);?>"/>                    
                                            
                                                </div>
                                                <p class="help-block">Must be of width:600px and height:300px.<br />Max. Size: 1MB</p>      
                                            </div>
                                            <?php echo $err_image;?>
                                        </div>
                                        <div class="col-sm-9">                                                  
                                            <ul class="mailbox-attachments clearfix"> 
                                                <li id="imageCallback">
                                                <?php if(@file_exists($image_path.$row->item_image) && $row->item_image!=''){ ?>
                                                <span class="mailbox-attachment-icon has-img">                           
                                                    <img src="<?php echo $image_display_path.$row->item_image; ?>" alt="Item Image">                              
                                                  </span>           
                                                  <div class="mailbox-attachment-info">
                                                    <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> <?php echo $row->item_image; ?></a>
                                                        <span class="mailbox-attachment-size">
                                                          <?php echo round(filesize($image_path.$row->item_image)/ 1024 ,2).' KB'; ?>
                                                          <a href="<?php echo $image_display_path.$row->item_image; ?>" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                                        </span>
                                                  </div>
                                                <?php }else{ ?>                         
                                                <span class="mailbox-attachment-icon has-img">                           
                                                    <img src="../images/no-hotel-image.jpg" alt="Item Image">                             
                                                  </span>           
                                                  <div class="mailbox-attachment-info">
                                                    <a href="javascript:void(0);" class="mailbox-attachment-name"><i class="fa fa-camera"></i> no-hotel-image.jpg</a>
                                                        <span class="mailbox-attachment-size">
                                                           <?php echo round(filesize('../images/no-hotel-image.jpg')/ 1024 ,2).' KB'; ?>
                                                          <a href="../images/no-hotel-image.jpg" download class="btn btn-default btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
                                                        </span>
                                                  </div>                            
                                                <?php }?> 
                                                  
                                                </li>                
                                            </ul>           
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="card text-dark bg-light">
                                    <div class="bg-primary text-center ">
                                        <h5 style="padding: 5px;">Previous History</h5>
                                    </div> 
                                    <hr>
                                    <div class="row">
                                        <div class="col-xs-12 col-md-12 col-sm-12">
                                            <div>
                                                <div class="box-body">
                                                    <table  class="table table-bordered table-hover table-striped table-responsive">
                                                        <thead>
                                                            <tr class="info">
                                                              <th>S.No</th>
                                                              <th>Check In</th>
                                                              <th>Days Stayed</th>
                                                              <th>Room</th> 
                                                              <th>Guest Note</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>Example</td>
                                                                <td>Example</td>
                                                                <td>Example</td>
                                                                <td>Example</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div> 
                            </div>
                          <!-- /.box-body -->

                            <div class="box-footer">
                                <button type="submit" class="btn btn-success">Add</button>
                                &nbsp;&nbsp; 
                                <button type="button" class="btn btn-danger">Cancel </button>
                            </div>
                        </form>
 
                    </div>
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



  
   
<?php include_once("../includes/footer.php")?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<script>
    $(document).ready(function(){

        /* Formatting function for row details - modify as you need */
        function format ( d ) {
            // `d` is the original data object for the row
            return '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">'+
                '<tr>'+
                    '<td>Full name:</td>'+
                    '<td>Shubha</td>'+
                '</tr>'+
                '<tr>'+
                    '<td>Extension number:</td>'+
                    '<td>BhadauRIYA</td>'+
                '</tr>'+
                '<tr>'+
                    '<td>Extra info:</td>'+
                    '<td>And any further details here (images etc)...</td>'+
                '</tr>'+
            '</table>';
        }

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
                                    <td onclick="guestDetails('${value.id}')"><a href="#" style="color:black;">${(value.guest)}</a></td>
                                    <td>${(value.source)}</td><td>`;
                                    value.roomType.forEach((roomTypevalue,keys,arrays)=>{
                                      tableData += `${roomTypevalue}<br/>`;
                                    });
                                    
                                  tableData += `</td><td>${(value.persons)}</td>
                                    <td>${(value.booked)}</td>
                                    <td>${(value.pending)}</td>
                                    <td><button class="btn btn-success btn-xs btnDiv" onclick="getRoomDetails('${value.reservation_id}',${value.pending},${value.id});" id="btnLink" title="view rooms"><i class="fa fa-eye"></i></button></td>
                                </tr>
                                <tr id="tr_${value.reservation_id}"  style="display:none" class="explodeDiv" ></tr>
                            `;
                        });
                        
                        $("#expected_arrivals").DataTable().destroy();
                        $("#expected_arrivals tbody").html(tableData);
                        initializeTableArrivals('#expected_arrivals',[0,1,3,5,6,7,8],true);
                    }
                });
        }
       
        arrivals(); 

        popup = ()=>{
            $("#checkinModal").modal('show');
        }

        /*$('#expected_arrivals tbody').on('click', 'details-control', function () {

        var tr = $(this).closest('tr');
        var row = table.row( tr );
 
        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            // Open this row
            row.child( format(row.data()) ).show();
            tr.addClass('shown');
        }
    } ); */




    });

    function getRoomDetails(resId,pending,userid){
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
                                  <hr/>
                                </div>

                                <div class="col-md-12">
                                  <div class="row text-center">`;
                                    value.RoomDetails.forEach((datavalue,keys,arrays) => {
                                      tableData += `<div class="col-md-1 col-sm-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
                                    });
                                  tableData += `</div></div></div><hr/>`;
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
</script>

<script type="text/javascript">

  $(ducument).reday(function(){
    $("#btnLink i").click(function(e){
      $(".explodeDiv").hide();
       $this = $(this).find(".explodeDiv");
       $this.toggle();

    });
  });

  //var roomArr = [];
  var roomArr = {};

  function selectRoom1(roomTypeId,roomNo,btnId,resvId,pending){ 

      var btn = "#"+btnId;
      alert(btn);
      var divId = "#showBookedRoom_"+resvId;
      var roomTypeName = "#roomTypeName_"+roomTypeId;
      var roomTypeId = roomTypeId;
      var resvId = resvId;
      let roomCount = 0;
      if($(btn).hasClass("btn-success")){            
       for(var key in roomArr){
        roomCount = roomCount + roomArr[key].length;
        }
        if(pending == roomCount){
                 bootbox.alert("Please Select only " + pending + " rooms");
        }else{
            $(btn).removeClass("btn-success").addClass("btn-danger");
            let user = resvId+"_"+roomTypeId;
            if(roomArr.hasOwnProperty(user)){
              console.log("push resvId in object")
              roomArr[user].push(roomTypeId);
              if(roomArr[resvId].hasOwnProperty(roomTypeId)){
                console.log("push roomTypeId in object")
                roomArr[resvId][roomTypeId].push(roomNo);
                //alert(roomArr);
              }else{
                roomArr[resvId][roomTypeId] = [roomNo]
              }
            }else{
              roomArr[resvId] = [roomTypeId];
            }
            /*if(roomArr.hasOwnProperty(roomTypeId)){
               roomArr[roomTypeId].push(roomNo);
            }else{
               roomArr[roomTypeId] = [roomNo];
            }*/
            roomCount = 0;
            for(var key in roomArr){
              roomCount = roomCount + roomArr[key].length;
            }
           alert(JSON.stringify(roomArr));
        }
        $(roomTypeName).html("Selected [ " + roomArr[resvId][roomTypeId].length + " ]");
        $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomCount +' )</h4>');
        }
        else if($(btn).hasClass("btn-danger")){
          $(btn).removeClass("btn-danger").addClass("btn-success");
           var removeRoomArr = roomArr[resvId][roomTypeId];
           const index = removeRoomArr.indexOf(roomNo);
           if (index > -1) {
              removeRoomArr.splice(index, 1);
              roomArr[resvId][roomTypeId] = removeRoomArr;
              
             roomCount = 0;
                  for(var key in roomArr){
                    roomCount = roomCount + roomArr[key].length;
                  }
            }
           if(roomCount == 0){
            $(divId).html('<div></div>');
            $(roomTypeName).html(roomArr[resvId][roomTypeId]);
           }else if(roomCount > 0){
              $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomCount +' )</h4>');
           if(roomArr[resvId][roomTypeId].length > 0){
            $(roomTypeName).html("Selected [ " + roomArr[resvId][roomTypeId].length + " ]");
            }else{
              $(roomTypeName).html(roomArr[resvId][roomTypeId]);
            }
          }
        }
    }

  /*function selectRoom1(roomTypeId,roomNo,btnId,resvId,booked){
   
      var btn = "#"+btnId;
      var divId = "#showBookedRoom_"+resvId;
      var roomNo = roomNo;
      var roomTypeId = roomTypeId;

      if(booked == roomArr.length){
          bootbox.alert("Please Select only " + booked + " rooms");
      }
      else{
        if($(btn).hasClass("btn-success")){
          $(btn).removeClass("btn-success").addClass("btn-danger");
          //roomArr.push({roomTypeId: roomTypeId, roomNo: roomNo});
          roomArr.push(roomNo);
          roomArr.join(',');
          //alert(roomArr);
          $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomArr.length +' )</h4>');
        }
        else if($(btn).hasClass("btn-danger")){
          $(btn).removeClass("btn-danger").addClass("btn-success");
           roomArr = roomArr.filter(item => item !== roomNo);
           roomArr.join(',');
           //alert(roomArr);
           if(roomArr.length == 0){
            $(divId).html(roomArr);

           }
          else
          {
           $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomArr.length +' )</h4>');
           $(roomTypeCount).html(roomArr.length);
          }
        }
      }   
    } */

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
        /*if(pending == roomCount){
                 bootbox.alert("Please Select only " + pending + " rooms");
        }else{*/
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
        //}
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
    

   /*function selectRoom2(roomTypeId,roomId,btnId,resvId,pending){
      var btn = "#"+btnId;
      var divId = "#showBookedRoom_"+resvId;
      var roomTypeName = "#roomTypeName_"+roomTypeId;
      alert(roomTypeName);
      var roomNo = roomId;
      var roomTypeId = roomTypeId;
      //alert(roomTypeId);

        if($(btn).hasClass("btn-success")){
          if(pending == roomArr.length){
             bootbox.alert("Please Select only " + pending + " rooms");
          }
          else{
            $(btn).removeClass("btn-success").addClass("btn-danger");
            //roomArr.push({roomTypeId: roomTypeId, roomNo: roomNo});
            roomArr.push(roomNo);
            roomArr.join(',');
            alert(roomArr);
          }
          $(roomTypeName).html(roomArr.length);
          $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomArr.length +' )</h4>');
        }
        else if($(btn).hasClass("btn-danger")){
          $(btn).removeClass("btn-danger").addClass("btn-success");
           roomArr = roomArr.filter(item => item !== roomNo);
           roomArr.join(',');
           alert(roomArr);
           if(roomArr.length == 0){
            $(divId).html(roomArr);

           }
          else
          {
           $(divId).html('<h4 class="text-center text-info"> Selected Rooms ( '+ roomArr.length +' )</h4>');
           $(roomTypeCount).html(roomArr.length);
          }
        }
    } */

    function userCheckIn(resvId,){
     //var room = roomArr.toString();
     let room = JSON.stringify(roomArr);
     let roomCount=0;
     for(var key in roomArr){
              roomCount = roomCount + roomArr[key].length;
      }
      if(roomCount>0){
          $.ajax({
            url: 'ajax/ajaxUserCheckIn.php',
            type: 'POST',
            data: {resvId : resvId, bookedRoom : room},
            //dataType: 'JSON',
            success : function(data){
              bootbox.alert(data);
               window.location.reload();
            }
          });
      }
      else{
         bootbox.alert("Please Select a room");
      }
      
    }

</script>


