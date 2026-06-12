<?php include_once("../config/auto_loader.php"); ?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<style type="text/css">
    .table-striped>tbody>tr:nth-child(odd)>td, .table-striped>tbody>tr:nth-child(odd)>th {
        background-color: #F5F5F5;
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
                    <a href="#tab_1" data-toggle="tab">Expected Arrivals</a>
                </li>
                <li><a href="#tab_2" data-toggle="tab">Room Statistics</a></li>
                <li id="guest_tab"><a href="#" data-toggle="tab">Guest Details</a></li>
                <li id="folio_tab"><a href="#" data-toggle="tab">Folio</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
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
                              <table id="example2" class="table table-bordered table-hover">
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
                <div class="tab-pane" id="tab_2">
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
                              <table id="example1" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>Room type</th>
                                  <th>Room No</th>
                                  <th>Status</th>
                                  <th>Guest Name</th>
                                  <th>Folio No.</th>
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

                <div class="tab-pane" id="tab_3">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-xs-6 col-md-3 col-sm-6">
                                <h3 class="box-title">Add Guest Details</h3>
                            </div>
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
                <div class="tab-pane" id="tab_4">
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
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Ckeckin Form</h4>
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
            $(target || '#example2,#example1').DataTable({
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
                                    <td>${(value.reservation_id)}</td>
                                    <td>${(value.guest)}</td>
                                    <td>${(value.source)}</td>
                                    <td>${(value.roomType)}</td>
                                    <td>${(value.persons)}</td>
                                    <td>${(value.booked)}</td>
                                    <td>${(value.pending)}</td>
                                    <td><button class="btn btn-success" onClick="popup();">Checkin</button></td>
                                </tr>
                            `;
                        });
                        
                        $("#example2").DataTable().destroy();
                        $("#example2 tbody").html(tableData);
                        initializeTable('#example2',[0,1,3,5,6,7,8],true);
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

                    var guestname = value.guest;    

                    tableData += `<td onclick="guestDetails('${value.id}')">${value.guest}</td>    
                                    <td onclick="folioDetails('${value.id}')">${value.folio}</td>
                                    <td>${value.checkin}</td>
                                    <td>${value.checkout}</td>
                                    <td>
                                        <button class="btn btn-warning">Extend</button>
                                        <button class="btn btn-info">Change</button>
                                    </td>
                                </tr>`;
                });

                $("#example1")
                    .DataTable()
                    .destroy();
                $("#example1 tbody").html(tableData);
                initializeTable("#example1", [1, 3, 4, 5]);
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
            $("#myModal").modal('show');
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
                $("#tab_2,#tab_1,#tab_4,.nav-tabs li").removeClass("active");
                $("#tab_3,#guest_tab").addClass("active");

                /* filling more options form here */
                $("#fname").val(result.Fname); 
                $("#lname").val(result.Lname); 
                $("#mobile").val(result.Mobile); 
                $("#phone").val(result.Phone_no); 
                $("#email").val(result.Email); 
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
            $("#tab_2,#tab_1,#tab_3,.nav-tabs li").removeClass("active");
            $("#tab_4,#folio_tab").addClass("active");

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
  })  

</script>


