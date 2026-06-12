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
    .bg-color{
        background-color: #f4f4f4;
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
                        <div class="col-md-4 form-group">
                            <label for="">Select Hotel</label>
                            <select name="" id="id_hotel" class="form-control">
                                <option value="1003">Balsamand Lake Palace</option>
                            </select>
                        </div>
                        
                        <div class=" col-md-4 form-group">
                            <label>Date:</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="text" class="form-control pull-right" id="datepicker">
                                </div>   
                         </div>
                        <div class="col-md-4 form-group">
                            <label for="">&nbsp;</label>
                            <button onClick="jumpTodate()" class="form-control btn btn-success">Search</button>
                        </div>
                    </div>
                    <div id="calendar" style=""></div>
                </div>
                <div class="tab-pane " id="tab_2">
                     <!-- Reservation -->
                    <div class="row ">
                        <div class="col-xs-12">
                            <!-- form start -->
                            <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off">
                                <div class="box box-success">
                                    <div class="box-header with-border bg-color">
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
                                    <div class="box-header with-border bg-color">
                                        <h3 class="box-title">Guest Information</h3>
                                    </div>
                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md- col-sm-6 col-xs-12 right_border">
                                              <div class="form-horizontal">
                                                  <div class="form-group row">
                                                      <label for="res_guestName" class="col-sm-2 col-md-2 col-form-label" style="border: 1px solid black;padding: 4px 0px 4px 10px">Guest Name</label>
                                                      <div class="col-sm-10 col-md-10">
                                                          <div class="input-group">
                                                            <input type="text" class="form-control" name="res_guestName" id="res_guestName"/>
                                                            <div class="input-group-addon">
                                                                <a href="#" style="color:black;" id="res_guestId"><i class="fa fa-plus"></i> </a>
                                                            </div>
                                                             <div class="input-group-addon">
                                                                <a href="#" style="color:black;" id="res_guestId"><i class="fa fa-edit"></i> </a>
                                                            </div>
                                                        </div>
                                                      </div>
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_bookingType" class="col-sm-2 col-md-2 col-form-label" style="padding: 6px 0px 4px 8px">Booking Type</label>
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
                                                      <label for="res_source" class="col-sm-2 col-md-2 col-form-label" style="padding: 6px 0px 4px 8px">Source</label>
                                                      <div class="col-sm-6 col-md-6">
                                                        <select class="form-control select2" style="width: 100%;" id="res_source" name="res_source" data-parsley-errors-container="#res_sourceError" data-parsley-required>
                                                            <option selected="selected" value="">Select Source</option>
                                                        </select>
                                                        <span id="res_sourceError"><?php echo $err_res_sourceError;?></span>
                                                      </div>  
                                                  </div>

                                              </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                              <div class="form-horizontal">
                                                  <div class="form-group row">
                                                      <label for="res_bookerName" class="col-sm-2 col-md-2 col-form-label" style="padding: 4px 0px 4px 10px">Booker Name</label>
                                                      <div class="col-sm-10 col-md-10">
                                                        <select class="form-control select2" style="width: 100%;" id="res_bookerName" name="res_bookerName" data-parsley-errors-container="#res_bookerNameError" data-parsley-required>
                                                          <option selected="selected" value="">Select Contacts</option>
                                                          <option value="Contact1">Contact1</option>
                                                          <option value="Contact2">Contact1</option> 
                                                        </select>
                                                        <span id="res_bookerNameError"><?php echo $err_res_bookerNameError;?></span>
                                                      </div>
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_rateType" class="col-sm-2 col-md-2 col-form-label">Rate Type</label>
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
                                                      <label for="res_rateLetter" class="col-sm-2 col-md-2 col-form-label">Rate Letter</label>
                                                      <div class="col-sm-6 col-md-6">
                                                        <select class="form-control select2" style="width: 100%;" id="res_rateLetter" name="res_rateLetter" data-parsley-errors-container="#res_rateLetterError" data-parsley-required>
                                                        <option selected="selected" value="">Select Rate Letter</option>
                                                        <option value="Rate Letter1">Rate Letter1</option>
                                                        <option value="Rate Letter2">Rate Letter2</option>
                                              
                                                        </select>
                                                        <span id="res_rateLetterError"><?php echo $err_res_rateLetterError;?></span>
                                                      </div>
                                                      <div class="col-sm-2 col-md-2">
                                                         <button type="button" class="btn btn-danger"><i class="fa fa-eye"></i>View</button> 
                                                      </div>
                                                      
                                                  </div>

                                              </div>
                                            </div>
                                        </div>
                                    </div>   
                                 </div>
                                 <div class="box box-success">
                                     <div class="box-header with-border bg-color">
                                         <h3 class="box-title">Rooms and Rates </h3> &nbsp;
                                         <span><i class="fa fa-plus"></i></span>
                                     </div>
                                     <div class="box-body ">
                                        <div class="well table-responsive">
                                            <table class="table table-hover">
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
                                             <tr>
                                                 <td>
                                                     <select class="form-control parsley-error" name="roomtype[]" data-parsley-required style="width: 120px;">
                                                        <option selected="selected" value="">Room type</option>
                                                        <option value="Cottage Villa">Cottage Villa</option>
                                                        <option class="Waitlist Room">Waitlist Room</option>
                                                     </select>
                                                 </td>
                                                 <td>
                                                     <select class="form-control parsley-error" name="plan[]" data-parsley-required style="width: 100px;">
                                                        <option selected="selected" value="">Plan</option>
                                                        <option value="Plan1">Plan1</option>
                                                        <option class="Plan2">Plan2</option>
                                                     </select>
                                                 </td>
                                                 <td class="form-group">
                                                     <input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms[]" id="noofRooms[]" data-parsley-type="digits" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson[]" id="adultperperson[]" data-parsley-type="digits" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson[]" id="childperperson[]" data-parsley-type="digits" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild[]" id="extrachild[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson[]" id="tariffperperson[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes[]" id="taxes[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight[]" id="chargespernight[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                   <button class="btn btn-danger" type="button"><i class="fa fa-trash"></i></button>
                                                 </td>
                                             </tr>
                                        </table>
                                        </div>
                                     </div>
                                 </div>
                                 <div class="box box-info ">
                                     <div class="box-header with-border bg-color">
                                         <h3 class="box-title">Add Ons </h3> &nbsp;
                                         <span><i class="fa fa-plus"></i></span>
                                     </div>
                                     <div class="box-body">
                                        <div class="well table-responsive">
                                          <table class="table table-hover">
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
                                            <tr>
                                                <td>
                                                    <select class="form-control parsley-error" style="width: 120px;" name="item[]" id="item[]"data-parsley-required>
                                                        <option selected="selected" value="">Select Item</option>
                                                        <option value="Item1">Item1</option>
                                                        <option class="Item2">Item2</option>
                                                    </select>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:130px;" name="additionalcharges[]" id="additionalcharges[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:100px;" name="qty[]" id="qty[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                    <select class="form-control parsley-error" style="width: 120px;" name="unit[]" id="unit[]"data-parsley-required>
                                                        <option selected="selected" value="">Select Unit</option>
                                                        <option value="Day">Day</option>
                                                        <option class="Nos">Nos</option>
                                                    </select>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:100px;" name="rate[]" id="rate[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:100px;" name="tax[]" id="tax[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:100px;" name="taxvalue[]" id="taxvalue[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                     <input type="text" class="form-control parsley-error" style="width:100px;"name="amount[]" id="amount[]" data-parsley-required/>
                                                 </td>
                                                 <td>
                                                   <button class="btn btn-danger" type="button"><i class="fa fa-trash"></i></button>
                                                 </td>
                                            </tr>
                                        </table>
                                        </div>
                                     </div>
                                 </div>
                                 <div class="box box-success">
                                     <div class="row">
                                        <div class="col-md-6 col-sm-6 col-xs-12 right_border">
                                            <div class="box-header with-border bg-color">
                                                <h3 class="box-title">Charges Summary</h3>
                                            </div>
                                            <div class="box-body">
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
                                                      <label for="res_advance" class="col-sm-3 col-md-3 col-form-label">Advance</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_advance" name="res_advance" value="5000"/>
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
                                                      <label for="res_reference" class="col-sm-3 col-md-3 col-form-label">Reference</label>
                                                      <div class="col-sm-7 col-md-7">
                                                          <input type="text" class="form-control" id="res_reference" name="res_reference" placeholder="Enter Reference" disableddata-parsley-errors-container="#res_referenceError" data-parsley-required/>
                                                          <span id="res_referenceError"><?php echo $err_res_referenceError;?></span>
                                                      </div>
                                                      
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_remarks" class="col-sm-3 col-md-3 col-form-label">Remarks</label>
                                                      <div class="col-sm-7 col-md-7">
                                                          <input type="text" class="form-control" id="res_remarks" name="res_remarks" placeholder="Enter Remarks" disableddata-parsley-errors-container="#res_remarksError" data-parsley-required/>
                                                      </div>
                                                      <span id="res_remarksError"><?php echo $err_res_remarksError;?></span>
                                                      
                                                  </div>
                                              </div> 
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <div class="box-header with-border bg-color">
                                                <h3 class="box-title">Pickup and Arrival Details</h3>
                                            </div>
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
                                                  <div class="form-group row">
                                                      <label for="res_modeoftravel" class="col-sm-3 col-md-3 col-form-label">Mode of Travel</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <select class="form-control  select2" style="width:100%" id="res_modeoftravel" name="res_modeoftravel" data-parsley-errors-container="#res_modeoftravelError" data-parsley-required>
                                                            <option selected="selected" value="">Select please</option>
                                                            <option value="By Air">By Air</option>
                                                            <option value="By Train">By Train</option>
                                                            <option value="By Road">By Road</option>
                                                        </select>
                                                        <span id="res_modeoftravelError"><?php echo 
                                                        $err_res_modeoftravelError;?> </span>
                                                      </div>

                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_pickupdetails" class="col-sm-3 col-md-3 col-form-label">Pickup Details</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_pickupdetails" name="res_pickupdetails"
                                                        placeholder="Enter Pickup Details" disableddata-parsley-errors-container="#res_pickupdetailsError" data-parsley-required />
                                                        <span id="res_pickupdetailsError"><?php echo $err_res_pickupdetailsError;?></span>
                                                      </div>
                                                      
                                                  </div>
                                                   <div class="form-group row">
                                                      <label for="res_arrivingfrom" class="col-sm-3 col-md-3 col-form-label">Arriving from</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_arrivingfrom" name="res_arrivingfrom"
                                                        placeholder="Enter Arriving from" disableddata-parsley-errors-container="#res_arrivingfromError" data-parsley-required />
                                                        <span id="res_arrivingfromError"><?php echo $err_res_arrivingfromError;?></span>
                                                      </div>
                                                  </div>
                                                  <div class="form-group row">
                                                      <label for="res_departingto" class="col-sm-3 col-md-3 col-form-label">Departing to</label>
                                                      <div class="col-sm-6 col-md-6">
                                                          <input type="text" class="form-control" id="res_departingto" name="res_departingto"
                                                        placeholder="Enter Departing to" disableddata-parsley-errors-container="#res_departingtoError" data-parsley-required />
                                                        <span id="res_departingtoError"><?php echo $err_res_departingtoError;?></span>
                                                      </div>
                                                  </div>
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

                    <!--<div class="from-group"><label for="">Hotel</label><input id="newHotel" type="text" /></div>
                    <div class="from-group"><label for="">Room</label><input id="newRoom" type="text" /></div>
                    <div class="from-group"><label for="">Guest</label><input id="newGuest" type="text" /></div>
                    <div class="from-group"><label for="">checkin</label><input id="newCheckin" type="text" /></div>
                    <div class="from-group"><label for="">checkout</label><input id="newCheckout" type="text" /></div>
                    <div class="from-group"><label for="">rooms</label><input id="newRooms" type="text" /></div> -->

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
                <form action="" id="reservationForm" class="form">
                    <input type="hidden" name="res_hotel" id="res_hotel" />
                    <input type="hidden" name="res_room" id="res_room" />
                    <input type="hidden" name="res_checkin" id="res_checkin" />
                    <input type="hidden" name="res_checkout" id="res_checkout" />
                    <div class="form-group">
                      <!--<label for="guest">Guest Details</label> -->
                      <input name="guest" id="guest" type="text" class="form-control" />
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="guestname">Guest Name</label>
                            <input type="text" name="guestname" id="guestname" class="form-control" placeholder="Enter Guest Name">
                          </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                            <label for="bookingType">Booking Type</label>
                            <select class="form-control" id="bookingType" name="bookingType">
                              <option selected="selected" value="">Select Booking Type</option>
                              <option  value="1">Direct Guest</option>
                              <option value="2">Travel Agent</option>
                              <option value="3">Corporate</option>           
                            </select>
                          </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="bookingSource">Source</label>
                            <select name="bookingSource" id="bookingSource" class="form-control">
                              <option value="">Select Source</option>
                            </select>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="bookerName">Booker Name</label>
                          <select class="form-control" name="bookerName" id="bookerName">
                            <option value="" selected="selected">Select Booker Name</option>
                            <option value="Contact1">Contact1</option>
                            <option value="Contact2">Contact2</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="rateType">Rate Type</label>
                          <select class="form-control" name="rateType" id="rateType">
                            <option value="" selected="selected">Select Rate Type</option>
                            <option value="Adoc">Adoc</option>
                            <option value="Contract">Contract</option>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                         <label for="rateLetter">Rate Letter</label>
                          <select class="form-control" name="rateLetter" id="rateLetter">
                            <option value="" selected="selected">Select Rate Letter</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="row ">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Plan</label>
                                <select name="id_plan" id="id_plan" class="form-control">
                                    <option value="3">CP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Rooms</label>
                                <select name="rooms" id="rooms" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Adult</label>
                                <select name="adults" id="adults" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="">Child</label>
                                <select name="childs" id="childs" class="form-control">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Rate</label>
                                <input name="rate" type="text" class="form-control" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Booking Status</label>
                                <select name="booking_status" id="booking_status" class="form-control">
                                    <option value="con">Confirmed</option>
                                    <option value="ten">Tentative</option>
                                    <option value="a3">Waitlisted</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Payment Status</label>
                                <select name="payment_status" id="payment_status" class="form-control">
                                    <option value="3">Paid</option>
                                    <option value="4">Advance</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="">Amount Received</label>
                                <input type="text" name="received_amount" class="form-control" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-info" id="more-options">More options</button>
                <button type="button" class="btn btn-success" onClick="saveReservation()">Save</button>
            </div>
        </div>
    </div>
</div>
<!-- End Reservation Modal -->
  
   
<?php include_once("../includes/footer.php")?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.0/bootbox.min.js"></script>
<script>
    $(document).ready(function(){
        // append dropdown 
        $(document).on('change','#idProof',function(){
            
            var idProof = $(this).val();
             
            if(idProof == "Voter_Id"){
                var Vote_Id = '<div class="form-group col-xs-12 col-md-3 col-sm-2"><label for="voterIdNumber">Voter Id Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="voterIdNumber" name="voterIdNumber" placeholder="Enter Voter Id Number" data-parsley-errors-container="#voterIdNumberError" data-parsley-required /></div><span id="voterIdNumberError"><?php echo $err_voterIdNumberError;?></span></div>'; 
                $("#appenddata").html(Vote_Id);
             }
             else if(idProof == "Passport")
            {
                var pass ='<div class="form-group col-xs-12 col-md-3 col-sm-2"><label for="passportNumber">Passport Number <font color="#FF0000">*</font></label><div class="input-group"> <div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="passportNumber" name="passportNumber" placeholder="Enter Passport Number" data-parsley-errors-container="#passportNumberError" data-parsley-required /> </div> <span id="passportNumberError"><?php echo $err_passportNumberError;?></span></div><div class="form-group col-xs-12 col-md-3 col-sm-2"><label for="authority">Authority<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-arrows"></i></div><input type="text" class="form-control" id="authority" name="authority" placeholder="Enter Authority" data-parsley-errors-container="#authorityError" data-parsley-required /></div><span id="authorityError"><?php echo $err_authorityError;?></span></div><div class="form-group col-xs-12 col-md-3 col-sm-2"><label for="expiryDate">Expiry Date<font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa-calendar-minus-o"></i></div><input type="text" class="form-control datepicker" id="expiryDate" name="expiryDate" placeholder="dd-mm-yyyy" data-parsley-errors-container="#expiryDateError" data-parsley-required /></div><span id="expiryDateError"><?php echo $err_expiryDateError;?></span></div>';
                
                $("#appenddata").html(pass);

            }
            else if(idProof == "Aadhar")
            {
                var Aadhar = '<div class="form-group col-xs-12 col-md-3 col-sm-2"><label for="aadharNumber">Aadhar Number <font color="#FF0000">*</font></label><div class="input-group"><div class="input-group-addon"><i class="fa fa fa-address-book"></i></div><input type="text" class="form-control" id="aadharNumber" name="aadharNumber" placeholder="Enter Aadhar Number" data-parsley-errors-container="#aadharNumberError" data-parsley-required /></div><span id="aadharNumberError"><?php echo $err_aadharNumberError;?></span></div>'; 
                $("#appenddata").html(Aadhar);
            }
        });


        initializeTable = (target,targetArr,ordering=false) =>{
            $(target || '#expected_arrivals,#room_statistics').DataTable({
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

        initializeTable1 = (target,targetArr,ordering=false) =>{
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
                                    <td onclick="guestDetails('${value.id}')"><a href="#" style="color:black;">${(value.guest)}</a></td>
                                    <td>${(value.source)}</td>
                                    <td>${(value.roomType)}</td>
                                    <td>${(value.persons)}</td>
                                    <td>${(value.booked)}</td>
                                    <td>${(value.pending)}</td>
                                    <td><button class="btn btn-success" onClick="popup();">Checkin</button></td>
                                </tr>
                            `;
                        });
                        
                        $("#expected_arrivals").DataTable().destroy();
                        $("#expected_arrivals tbody").html(tableData);
                        initializeTable('#expected_arrivals',[0,1,3,5,6,7,8],true);
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
                                    <td onclick="guestDetails('${value.id}')"><a href="#" style="color:black;">${value.guest}</a></td>    
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
                initializeTable("#room_statistics", [1, 3, 4, 5]);
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
                    initializeTable1("#foliotable", [1, 3, 4, 5]);  
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

    
    function guestDetails(Id){
       // alert("hello");
        $.ajax({
           type: "POST",
           url: 'ajax/ajaxGuestDetails.php',
           data:{ID:Id}, 
           dataType:'JSON',
           success: function (result) {     
               // $("#tab_2,#tab_1,#tab_4,.nav-tabs li").removeClass("active");
                $("#tab_4,#tab_1,#tab_2,#tab_3,#tab_6,.nav-tabs li").removeClass("active");
                $("#tab_5,#guest_tab").addClass("active");

                /* filling more options form here */
                $("#fname").val(result.Fname); 
                $("#lname").val(result.Lname); 
                $("#mobile").val(result.Mobile); 
                $("#phone").val(result.Phone_no); 
                $("#email").val(result.Email); 
                $("#email").val(result.Email); 
                $("#address").val(result.Address); 
                $("#city").val(result.City); 
                $("#country").html('<option value="'+result.Country+'">'+result.Country +'</option>'); 
            }
         }); 
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
            $("#res_guestName").val(result.Guest) ;  
            $("#res_guestId").attr('onClick','guestDetails('+result.Id+')');
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
<!-- calender JS -->
<script>

 $(document).ready(function() {
    var calendarEl = document.getElementById("calendar");

    const calendar = new FullCalendar.Calendar(calendarEl, {
        resourceAreaWidth: 200,
        resourcesInitiallyExpanded: false,
        selectable: true,
        defaultDate: moment(new Date()).subtract(2,'days').format('YYYY-MM-DD'),
        slotLabelFormat: [{ day:"2-digit",weekday: "short" }],
        plugins: ["interaction", "resourceTimeline","resourceTimelinePlugin"],
        header: {
            left: "today prev,next",
            center: "title",
            right: false
        },
        // editable: true,
        aspectRatio: 1.5,
        defaultView: "customWeek",
        views: {
            customWeek: {
                type: "resourceTimeline",
                duration: { days: 15 },
                slotDuration: { days: 1 },
                buttonText: "Custom Week"
            }
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
            $.ajax({
                url: "events.php",
                dataType: "JSON",
                success: function(data) {
                    successCallback(data);
                }
            });
        },
        select: function(info) {
            let checkin = moment(info.start).format("YYYY-MM-DD");
            let checkout = moment(info.end).format("YYYY-MM-DD");
            let id_hotel = $("#id_hotel").val();
            let id_room = 0;
            id_room = info.resource._resource.parentId == "" ? info.resource._resource.id : info.resource._resource.parentId;
            /* Setting vaules in reserve form */
            
            let roomType = info.resource._resource.parentId == "" ? info.resource._resource.title :calendar.getResourceById(info.resource._resource.parentId).title  ;
            let label = 'Room : '+roomType+',Checkin : '+moment(checkin).format('DD-MM-YYYY')+',Checkout : '+moment(checkout).subtract(1,'days').format('DD-MM-YYYY');
            $("#reservationModalLabel").html(label);
            $("#res_checkin").val(checkin);
            $("#res_checkout").val(checkout);
            $("#res_hotel").val(id_hotel);
            $("#res_room").val(id_room);
            /* end */
            $("#reservationModal").modal("show");
            $("#booking_status").val(info.resource._resource.id);
        }
    });

    calendar.render();

    jumpTodate = (date=$("#datepicker").val()) =>{
        calendar.gotoDate(moment(date).utc().format());
    }

    $("#more-options").click(function() {
        $("#reservationModal").modal("hide");

        $("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
        $("#tab_2,#reservation_tab").addClass("active");

        //$("#tab_1,#tab_2,.nav-tabs li").toggleClass("active");
        /* filling more options form here */
        $("#newHotel").val($("#res_hotel").val()) ; 
        $("#newRoom").val($("#res_room").val()) ; 
        $("#newRooms").val($("#rooms").val()) ;
        $("#newGuest").val($("#guest").val()) ;  
        $("#newCheckin").val($("#res_checkin").val()) ; 
        $("#newCheckout").val($("#res_checkout").val()) ; 
    });

    $(".select2").select2();
    $("#reservation").daterangepicker({
        locale: { format: "DD/MM/YYYY" }
    });

    saveReservation = () => {
        $.ajax({
            url: "reservation.php",
            data: $("#reservationForm").serialize(),
            dataType:'JSON',
            success: function(data) {
               $("#reservationModal").modal("hide");
               calendar.refetchEvents()
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
<!-- End -->
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
            }
        });
    });
</script>
