<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("../includes/header.php")?>
<link rel="stylesheet" href="<?php echo $SITE_URL; ?>/frontoffice/css/frontoffice.css">

<?php include_once("../includes/left.php")?>

<style>
    #foliotable thead{
      position:sticky;top:0;
    }
.daybtn{
    margin-left: 21px;
   display:flex;
}
.daybtn .info-box-icon{
    height: 51px;
    width: 93px;
    text-align: center;
    font-size: 16px;
    line-height: 55px;
        border-radius: 49%;
}
.daybtn .dayclose{
        width: 194px;
    text-align: center;
    margin-left: 10px;
    display:flex;
        position: relative;
    right: -162px;
}
.daybtn .info-box-text{
    padding: 6px;
   
    color: #363434;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 6px;
    border-top-right-radius: 0px;
    border-top-left-radius: 0px;
    font-size: 12px!important;
}
.daybtn .btn{
        width:75px;
    display: flex;
    justify-content: center;
    align-items: center;
        height: 26px;
    /* line-height: 55px; */
    margin-top: 6px;
}
@media only screen and (min-width:1200px){
  .daybtn{  min-height: 40px;
    position: absolute;
       top: -45px;
    right: 130px;
}}
</style>


<!-- 

<div id="preloader"></div> -->


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header" style="padding-top:1px;">
        <h4>
            One Window
            <small>Optional description</small>
        </h4>
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
            <?php /*?><div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box" style="min-height: 40px;">
                    <span class="info-box-icon bg-aqua" style="height: 40px; "></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Exepected Arrivals &nbsp; - &nbsp; <b>10</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div><?php */?>
            <!-- /.col -->
            <?php /*?><div class="col-md-4 col-sm-6 col-xs-12">
                <div class="info-box" style="min-height: 40px;">
                    <span class="info-box-icon bg-red" style="height: 40px;"></span>

                    <div class="info-box-content">
                        <span class="info-box-text">Today's Checkin &nbsp; - &nbsp; <b>50</b></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div><?php */?>
            <!-- /.col -->
			<div class="col-md-3 col-sm-6 col-xs-12"></div>
            <div class="col-md-3 col-sm-6 col-xs-12"></div>
            <div class="col-md-3 col-sm-6 col-xs-12"></div>
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
<?php 
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					 $Dated = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					 
					 
					 ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box daybtn" style="min-height: 40px;">
                   <!-- <span class="info-box-icon bg-yellow " style="">DAY CLOSE</span>-->

                    <span class="dayclose">
                        <span class="info-box-text"> <b><div id="auditDate"><?php echo $Dated;?></div></b></span>
                          <a href="#" class="btn btn-block o-btn " onclick="nightAduitUpdate();">DAY CLOSE</a>
                    </span>
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
                    <a href="#tab_1" data-toggle="tab" id="tabs1" > <!--  onClick="reload()" --> Reservation Chart</a>
                </li>
                <li id="reservation_tab"><a href="#">Reservations</a></li>
                <li>
                    <a href="#tab_3"  data-toggle="tab" id="tabs3" >Expected Arrivals</a>
                </li>
                <li><a href="#tab_4" data-toggle="tab" id="tabs4" >Room Statistics</a></li>
                <li id="guest_tab"><a href="#" data-toggle="tab" id="tabs5" >Guest Details</a></li>
                <li id="folio_tab"><a href="#tab_6" data-toggle="tab" id="tabs6" onClick="folioDetails();">Folio</a></li>
               <?php /*?> <li><a href="#tab_7" data-toggle="tab" id="tabs7" >House Keeping</a></li><?php */?>
                <li id="list_view_tab"><a href="#tab_9" data-toggle="tab" id="tabs9" onClick="list_ajax();" >Lists</a></li>
				<li class="active_cart" id="roomaloc"><!--id="roomaloc"--><a href="#tab_8" data-toggle="tab" id="tabs8" >Room Allocation</a></li>
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
							
						<!--	<select class="form-control select2 hotelName" name="id_hotel" id="id_hotel"  onchange="showUser(this.value);" style="width:100%;">

		                  	</select> -->
							
							<select class="select2 form-control" id="id_hotel" onchange="showUser(this.value);"  name="id_hotel">
								<!--	<option value="">---SELECT HOTEL---</option> -->
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
                        <div class="col-md-2 form-group">
                            <label for="">&nbsp;</label>
							<button id="listshow" class="form-control btn btn-info">List</button>              
						</div>
            <div class="col-md-2 form-group">
                            <label for="">&nbsp;</label>
              <button onClick="jumpTodate()"  id="addsubmit" class="form-control btn btn-success">Search</button>
            </div>
						
					<!--	<div class="col-md-2 form-group" style="padding-top:15px;">                             							
							<div class="navbar-custom-menu">
								<ul class="nav navbar-nav">
								<li class="dropdown notifications-menu">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown">
									  <i class="fa fa-shopping-cart fa-2x" style="color:#00a65a"></i>
									 </a> 
									<ul class="dropdown-menu">									 
									  <li>
										<!-- inner menu: contains the actual data -->
										<!--<ul class="menu">
										  <li>
											<a href="#tab_8" data-toggle="tab" class="dropdown-toggle" onclick="active_cart()">
											    <i class="fa fa-shopping-cart text-aqua"></i> Unassigned Details
											</a>
										  </li>
										</ul>
									  </li>
									</ul>
								  </li>
								</ul>
							  </div>
						</div>  -->
					
                    </div>
					 
                  <!--  <div id="calendar" style="text-align:center;color:#dd4b39"> <h3 class="box-title">Please Select a Hotel Name</h3></div>  -->
                
                    <div id="calendar" style="width:100%;"></div>
                    <div id="calendar1" style=""></div>
                  
                </div>
 

                <div class="tab-pane" id="tab_2">
                <?php include_once('reservationForm.php');?>
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
                                             <input data-parsley-required type="text" class="form-control frontofficedatepicker" placeholder="Enter Date"
                                              id="room_statistics_date" name="room_statistics_date" value="<?php echo date('d-m-Y'); ?>" >
                                           
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
                                <thead >
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
                                    <div class="col-md-2 col-sm-2 col-xs-12"><h3 class="box-title">Room</h3></div>
                                    <div class="col-md-4 col-sm-4 col-xs-5">
                                        <!-- input type="text"  class="form-control" id="datepicker1" value="<?php// echo Date('d-m-Y'); ?>" -->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            
                                            <select class="form-control first-input select2" style="width:100% !important;" name="id_fo_bill" id="id_fo_bill" onChange="InvoiceDetails(this.value);" >
                                                          <option value="0">Select Room </option>
       <?php  $resCat = mysqli_query($connNew,"SELECT * FROM `fo_folio` WHERE  folio_status='0' and status='1'  ");
															   
			/*"SELECT *,fo.mdoc_no as folio_mdoc_no 
															   FROM `fo_folio` as fo 
															   INNER JOIN fo_bill as bi 
															   ON fo.id=bi.id_fo_folio_to where bi.folio_status='0'"*/												   
															   
	   //selectSql('fo_folio'," where  id_mst_shops='".addslashes($_SESSION['shop'])."' and folio_status='0'  ",' ');
														  
	if(mysqli_num_rows($resCat)){
	while($resultCat = mysqli_fetch_object($resCat)){
	$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
	
	//$id_fo_bill	=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
	
	$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$resultCat->id_fo_bill."'");
	$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
	//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
	/*
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* 
			from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($resultCat->id_reservations)."'   group by id_mst_room_no_allocation ");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			//$id_mst_guest=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest'," WHERE `id_fo_reservations` = '".addslashes($resultCat->id_reservations)."' and DATE(dated) = '".date('Y-m-d')."'");
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($resultCat->id_reservations)."' ");					
													
					*/								

echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">
'.$resultCat->mdoc_no.'---    Room No:'.$roomNumber.' ---  Guest: '.$guestName.'</option>';
												//}
											  }
											  }?>
                                                        </select>
                                             <?php /*?> <select class="form-control first-input select2" style="width:100% !important;" name="id_fo_bill" id="id_fo_bill" onChange="InvoiceDetails(this.value);" >
                                                          <option value="0">Select Room </option>
       <?php  $resCat = selectSql(FO_BILL," where  id_mst_shops='".addslashes($_SESSION['shop'])."' and folio_status='0'  ",' ');
														  
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
			
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($resultCat->id_reservations)."'   group by id_mst_room_no_allocation ");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			//$id_mst_guest=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest'," WHERE `id_fo_reservations` = '".addslashes($resultCat->id_reservations)."' and DATE(dated) = '".date('Y-m-d')."'");
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($resultCat->id_reservations)."' ");					
													
													

echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id_reservations.'_'.$resultCat->id.'_'.$rowOrderDetail->id_mst_room_no_allocation.'">Room No: '.$roomNumber.' Guest Name: '.$guestName.'</option>';
												}
											  }
											  }?>
                                                        </select><?php */?>
                                        </div>   
                                    </div>
                                    <?php /*?><div class="col-md-1 col-sm-2 col-xs-3">
                                        <button class="btn btn-success">Search</button>
                                    </div>
                                    <div class="col-md-1 col-sm-3 col-xs-4">
                                        <button class="btn btn-success">Download</button>
                                    </div><?php */?>
                                </div>
                            </div>
                          <div id="ShowInvoiceDetails"></div>  
							  
							  
                           
                          </div>
                          <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 3 End -->
                </div>
				
				
				
				<div class="tab-pane" id="tab_7">
                    <!-- Table 2 Start -->
                    <div class="row mt-5">
                        <div class="col-xs-12">
                          <div class="box box-success">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-xs-12"><h3 class="box-title">House Keeping</h3></div>
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
							
							
								<table class="table table-bordered table-striped" >
								<thead>
								<tr>
								  <th>Room Type</th>
								  <th>Room No</th>
								  <th>Block</th>
								  <th>Room Status</th>
								  <th>Last Cleaned Date</th>
								  <th>Last Cleaned Time</th>
								  <th>Executive</th>
								  <th>Action</th>
								</tr>
								</thead>
								<tbody id="housekeep_ajax">
								
								</tbody>                
							  </table>	
							  
							  
                            </div>
                            <!-- /.box-body    echo $id = $_REQUEST['id'];	 -->
                          </div>
                          <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 2 End -->
                </div>



          <div class="tab-pane" id="tab_9">
                    <!-- Table 2 Start -->
                    <div class="row mt-5">
                        <div class="col-xs-12">
                          <div class="box box-success">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-2 col-sm-3 col-xs-12"><h3 class="box-title"></h3></div>
                                    <table id="BookingList" class="display table table-bordered table-hover" style="width:100%">
        <thead>
            <tr>
                <th>Guest Name</th>
                <th>Res#</th>
                <th>Hotel Name</th>
                <th>Booking Date</th>
                <th>Checkin</th>
                <th>Checkout </th>
                <th>Action</th>
            </tr>
        </thead>
        
    </table>
                                   
                                </div>
                            </div>
                            <!-- /.box-header -->
                         
                            <!-- /.box-body    echo $id = $_REQUEST['id'];   -->
                          </div>
                          <!-- /.box -->
                     </div>
                        <!-- /.col -->
                   </div> 
                    <!-- Table 2 End -->
                </div>




				
				 <div class="tab-pane" id="tab_8">
                    <!-- Table 1 Start -->
                    <div class="row mt-5">
                        <div class="col-xs-12">
                          <div class="box box-info">
                            <div class="box-header">
                                <div class="row">
                                    <div class="col-md-2 col-sx-12 col-sm-4">
                                        <h4 class="box-title" style="font-size:15px">Room Allocation</h4>
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
									<div class="col-md-1 col-sm-2 col-xs-4">
                                        <button class="btn btn-success" onclick="bd_applyy();">Close</button>
                                    </div>

                                </div>
                              
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body table-responsive">
                              <table id="calendar_cart" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>Booking No</th>
                                  <th>Room Type</th>
                                  <th>Guest Name</th>
                                  <th>Source</th>
                                  <th>CheckIn</th>
                                  <th>CheckOut</th>
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
<!-- Begain Guest Model -->
<div class="modal fade" id="guestaddeditModal" tabindex="-1" role="dialog" aria-labelledby="guestaddeditModalLabel" style="width: 100%; height: 100%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
		
		 <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                <h4 class="modal-title" id="roomratesModalLabel">Guest Name Details</h4>
         </div>
		
          <div class="modal-body">
 
    <form id="guestpopupform" data-parsley-validate="" autocomplete="off" method="post" action="" novalidate>
     <input type="hidden" id="EditCustomerID" name="EditCustomerID" value="">
    
    
 <div class="row">
	  <div class="col-md-6"> 
	<div class="form-group">
        <label>Title</label>
        <select name="Nametitle" id="Nametitle" class="form-control input-sm" data-parsley-required="">
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
         </select>
      </div>
      </div>
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="first_name">First Name</label>
        <input type="text" class="form-control input-sm" placeholder="Enter first name" id="first_name" name="first_name" value="" data-parsley-required="">
      </div>
      </div>
      </div>
	  
	 <div class="row">
	  <div class="col-md-6">   
       <div class="form-group">
        <label for="last_name">Last Name</label>
        <input type="text" class="form-control input-sm" placeholder="Enter last name" id="last_name" name="last_name" value="" data-parsley-required="">
      </div>
      </div>
	  <div class="col-md-6">  
       <div class="form-group">
        <label for="email">Email Id</label>
        <input type="text" name="email" id="email" class="form-control" placeholder="Enter Email Id" automcomplete="off">
      </div>
      </div>
      </div>
	  
	  
	   <div class="row">
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="mobile">Mobile No.</label>
        <input type="text" name="mobile" id="mobile" class="form-control" placeholder="Enter mobile number" automcomplete="off">
      </div>
      </div>
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="mobile">City</label>
        <input type="text" name="city" id="city" class="form-control" placeholder="Enter City" automcomplete="off">
      </div>
      </div>
      </div>
	  
	    <div class="row">
	  <div class="col-md-6"> 
       <div class="form-group">
        <label>Country</label>
        <select class="form-control" name="id_country" id="id_country" data-parsley-required="">
           <option value="">Select Country</option>
           <option value="231">Afghanistan</option><option value="244">Aland Islands</option><option value="230">Albania</option><option value="38">Algeria</option><option value="39">American Samoa</option><option value="40">Andorra</option><option value="41">Angola</option><option value="42">Anguilla</option><option value="232">Antarctica</option><option value="43">Antigua and Barbuda</option><option value="44">Argentina</option><option value="45">Armenia</option><option value="46">Aruba</option><option value="24">Australia</option><option value="2">Austria</option><option value="47">Azerbaijan</option><option value="48">Bahamas</option><option value="49">Bahrain</option><option value="50">Bangladesh</option><option value="51">Barbados</option><option value="52">Belarus</option><option value="3">Belgium</option><option value="53">Belize</option><option value="54">Benin</option><option value="55">Bermuda</option><option value="56">Bhutan</option><option value="34">Bolivia</option><option value="233">Bosnia and Herzegovina</option><option value="57">Botswana</option><option value="234">Bouvet Island</option><option value="58">Brazil</option><option value="235">British Indian Ocean Territory</option><option value="59">Brunei</option><option value="236">Bulgaria</option><option value="60">Burkina Faso</option><option value="61">Burma (Myanmar)</option><option value="62">Burundi</option><option value="63">Cambodia</option><option value="64">Cameroon</option><option value="4">Canada</option><option value="65">Cape Verde</option><option value="237">Cayman Islands</option><option value="66">Central African Republic</option><option value="67">Chad</option><option value="68">Chile</option><option value="5">China</option><option value="238">Christmas Island</option><option value="239">Cocos (Keeling) Islands</option><option value="69">Colombia</option><option value="70">Comoros</option><option value="71">Congo, Dem. Republic</option><option value="72">Congo, Republic</option><option value="240">Cook Islands</option><option value="73">Costa Rica</option><option value="74">Croatia</option><option value="75">Cuba</option><option value="76">Cyprus</option><option value="16">Czech Republic</option><option value="20">Denmark</option><option value="245">Details Awaited</option><option value="77">Djibouti</option><option value="78">Dominica</option><option value="79">Dominican Republic</option><option value="80">East Timor</option><option value="81">Ecuador</option><option value="82">Egypt</option><option value="83">El Salvador</option><option value="84">Equatorial Guinea</option><option value="85">Eritrea</option><option value="86">Estonia</option><option value="87">Ethiopia</option><option value="88">Falkland Islands</option><option value="89">Faroe Islands</option><option value="90">Fiji</option><option value="7">Finland</option><option value="246">Foreigner</option><option value="8">France</option><option value="241">French Guiana</option><option value="242">French Polynesia</option><option value="243">French Southern Territories</option><option value="91">Gabon</option><option value="92">Gambia</option><option value="93">Georgia</option><option value="1">Germany</option><option value="94">Ghana</option><option value="97">Gibraltar</option><option value="9">Greece</option><option value="96">Greenland</option><option value="95">Grenada</option><option value="98">Guadeloupe</option><option value="99">Guam</option><option value="100">Guatemala</option><option value="101">Guernsey</option><option value="102">Guinea</option><option value="103">Guinea-Bissau</option><option value="104">Guyana</option><option value="105">Haiti</option><option value="106">Heard Island and McDonald Islands</option><option value="108">Honduras</option><option value="22">HongKong</option><option value="143">Hungary</option><option value="109">Iceland</option><option value="110">India</option><option value="111">Indonesia</option><option value="112">Iran</option><option value="113">Iraq</option><option value="26">Ireland</option><option value="29">Israel</option><option value="10">Italy</option><option value="32">Ivory Coast</option><option value="115">Jamaica</option><option value="11">Japan</option><option value="116">Jersey</option><option value="117">Jordan</option><option value="118">Kazakhstan</option><option value="119">Kenya</option><option value="120">Kiribati</option><option value="121">Korea, Dem. Republic of</option><option value="122">Kuwait</option><option value="123">Kyrgyzstan</option><option value="124">Laos</option><option value="125">Latvia</option><option value="126">Lebanon</option><option value="127">Lesotho</option><option value="128">Liberia</option><option value="129">Libya</option><option value="130">Liechtenstein</option><option value="131">Lithuania</option><option value="12">Luxemburg</option><option value="132">Macau</option><option value="133">Macedonia</option><option value="134">Madagascar</option><option value="135">Malawi</option><option value="136">Malaysia</option><option value="137">Maldives</option><option value="138">Mali</option><option value="139">Malta</option><option value="114">Man Island</option><option value="140">Marshall Islands</option><option value="141">Martinique</option><option value="142">Mauritania</option><option value="35">Mauritius</option><option value="144">Mayotte</option><option value="145">Mexico</option><option value="146">Micronesia</option><option value="147">Moldova</option><option value="148">Monaco</option><option value="149">Mongolia</option><option value="150">Montenegro</option><option value="151">Montserrat</option><option value="152">Morocco</option><option value="153">Mozambique</option><option value="154">Namibia</option><option value="155">Nauru</option><option value="156">Nepal</option><option value="13">Netherlands</option><option value="157">Netherlands Antilles</option><option value="158">New Caledonia</option><option value="27">New Zealand</option><option value="159">Nicaragua</option><option value="160">Niger</option><option value="31">Nigeria</option><option value="161">Niue</option><option value="162">Norfolk Island</option><option value="163">Northern Mariana Islands</option><option value="23">Norway</option><option value="164">Oman</option><option value="165">Pakistan</option><option value="166">Palau</option><option value="167">Palestinian Territories</option><option value="168">Panama</option><option value="169">Papua New Guinea</option><option value="170">Paraguay</option><option value="171">Peru</option><option value="172">Philippines</option><option value="173">Pitcairn</option><option value="14">Poland</option><option value="15">Portugal</option><option value="174">Puerto Rico</option><option value="175">Qatar</option><option value="176">Reunion Island</option><option value="36">Romania</option><option value="177">Russian Federation</option><option value="178">Rwanda</option><option value="179">Saint Barthelemy</option><option value="180">Saint Kitts and Nevis</option><option value="181">Saint Lucia</option><option value="182">Saint Martin</option><option value="183">Saint Pierre and Miquelon</option><option value="184">Saint Vincent and the Grenadines</option><option value="185">Samoa</option><option value="186">San Marino</option><option value="187">Sao Tome and Principe</option><option value="188">Saudi Arabia</option><option value="189">Senegal</option><option value="190">Serbia</option><option value="191">Seychelles</option><option value="192">Sierra Leone</option><option value="25">Singapore</option><option value="37">Slovakia</option><option value="193">Slovenia</option><option value="194">Solomon Islands</option><option value="195">Somalia</option><option value="30">South Africa</option><option value="196">South Georgia and the South Sandwich Islands</option><option value="28">South Korea</option><option value="6">Spain</option><option value="197">Sri Lanka</option><option value="198">Sudan</option><option value="199">Suriname</option><option value="200">Svalbard and Jan Mayen</option><option value="201">Swaziland</option><option value="18">Sweden</option><option value="19">Switzerland</option><option value="202">Syria</option><option value="203">Taiwan</option><option value="204">Tajikistan</option><option value="205">Tanzania</option><option value="206">Thailand</option><option value="33">Togo</option><option value="207">Tokelau</option><option value="208">Tonga</option><option value="209">Trinidad and Tobago</option><option value="210">Tunisia</option><option value="211">Turkey</option><option value="212">Turkmenistan</option><option value="213">Turks and Caicos Islands</option><option value="214">Tuvalu</option><option value="215">Uganda</option><option value="216">Ukraine</option><option value="217">United Arab Emirates</option><option value="17">United Kingdom</option><option value="21">United States</option><option value="218">Uruguay</option><option value="219">Uzbekistan</option><option value="220">Vanuatu</option><option value="107">Vatican City State</option><option value="221">Venezuela</option><option value="222">Vietnam</option><option value="223">Virgin Islands (British)</option><option value="224">Virgin Islands (U.S.)</option><option value="225">Wallis and Futuna</option><option value="226">Western Sahara</option><option value="227">Yemen</option><option value="228">Zambia</option><option value="229">Zimbabwe</option>         </select>
      </div>
      </div>
	  <div class="col-md-6"> 
       <div class="form-group">
        <label>Guest type</label>
        <select name="user_type" id="user_type" class="form-control input-sm">
           <option value="">-Select-</option>
           <option value="VIP">VIP</option>
           <option value="CIP">CIP</option>
         </select>
      </div>
      </div>
      </div>
	  
	  <div style="text-align:center">
           <input name="save" id="save" type="submit" class="btn btn-primary" value="Save" /> 
			<!-- <input type="submit" class="btn btn-primary" onclick="saveBookerPopupform();" value="Save">-->
		   <button class="guest_close btn btn-danger">Close</button>
	   </div>
        </div>
     </form>
  </div><div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div></div>
</div> 
    
<!-- End Guest Model -->



<!-- Begain Booker By Model -->
<div class="modal fade" id="bookereditModal" tabindex="-1" role="dialog" aria-labelledby="bookereditModalLabel" style="width: 100%; height: 100%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
		
		 <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                <h4 class="modal-title" id="roomratesModalLabel">Booker By Details</h4>
         </div>
		 
          <div class="modal-body">
 
    <form method="post" id="bookerpopupform" name="bookerpopupform" data-parsley-validate="" autocomplete="off" novalidate>
     <input type="hidden" id="booker_EditCustomerID" name="booker_EditCustomerID" value="">
	
	<div class="row">
	  <div class="col-md-6">
		   <div class="form-group">
			<label for="first_name">First Name</label>
			<input type="text" class="form-control input-sm" placeholder="Enter first name" id="booker_first_name" name="booker_first_name" value="" data-parsley-required="">
		  </div>
      </div>
	   <div class="col-md-6">
		   <div class="form-group">
			<label for="last_name">Last Name</label>
			<input type="text" class="form-control input-sm" placeholder="Enter last name" id="booker_last_name" name="booker_last_name" value="" data-parsley-required="">
		  </div>
      </div>
      </div>
	  
	 <div class="row">
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="email">Email Id</label>
        <input type="text" name="booker_email" id="booker_email" class="form-control" placeholder="Enter Email Id" automcomplete="off">
      </div>
      </div>
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="mobile">Mobile No.</label>
        <input type="text" name="booker_mobile" id="booker_mobile" class="form-control" placeholder="Enter mobile number" automcomplete="off">
      </div>
	 </div>
      </div> 
	  
	  
	  <div class="row">
	  <div class="col-md-6"> 
       <div class="form-group">
        <label for="mobile">City</label>
        <input type="text" name="booker_city" id="booker_city" class="form-control" placeholder="Enter City" automcomplete="off">
      </div>
      </div>
	  <div class="col-md-6"> 
	  <div class="form-group">
        <label for="mobile">Postcode</label>
        <input type="text" name="booker_postcode" id="booker_postcode" class="form-control" placeholder="Enter Postcode" automcomplete="off">
      </div>
     	 </div>
      </div>  
	    <div style="text-align:center">
           <!-- <input name="save" id="save" type="submit" class="btn btn-primary" value="Save" /> -->
			 <input type="submit" name="submit" class="btn btn-primary" value="Save">
		   <button class="guest_close btn btn-danger">Close</button>
	   </div>
    </form>
  </div><div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div></div>
</div> 
        
 </div>
<!-- End Booker By  Model -->



<!-- Begain Booker By Model -->
<div class="modal fade" id="houseeditModal" tabindex="-1" role="dialog" aria-labelledby="houseeditModalLabel" style="width: 100%; height: 100%;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
		
		 <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                <h4 class="modal-title" id="houseeditModalLabel">House Keeping</h4>
         </div>
		 
          <div class="modal-body">
 
    <form method="post" action="" id="housepopupform" name="housepopupform" data-parsley-validate="" autocomplete="off" novalidate>
     <input type="hidden" id="id" name="id" value="">
	
	<div class="row">
	  <div class="col-md-6">
		   <div class="form-group">
			<label for="first_name">Room Type</label>
			<input type="text" class="form-control input-sm" id="room_type" name="room_type" value="" readonly data-parsley-required="">
		  </div>
      </div>
	  <div class="col-md-6">
		   <div class="form-group">
			<label for="last_name">Room no</label>
			<input type="text" class="form-control input-sm" placeholder="Enter last name" id="room_no" name="room_no" value="" readonly data-parsley-required="">
		  </div>
      </div>
      </div>
	  
	 <div class="row">
	 <div class="col-md-4">
		   <div class="form-group">
			<label for="last_name">Block</label>
			<input type="text" class="form-control input-sm" placeholder="Enter last name" id="block_floor" name="block_floor" value="" readonly data-parsley-required="">
		  </div>
      </div>
	  <div class="col-md-4"> 
       <div class="form-group">
        <label for="email">Room Status</label>
        <select name="room_status" id="room_status" class="form-control input-sm">
            <option value="">--- Select Status ---</option>
            <option value="1">Dirty</option>
			<option value="2">Reserved</option>
			<option value="3">Occupied</option>
			<option value="4">Clean</option>
			<option value="5">Blocked</option>
			<option value="6">Under Maintenance</option>
         </select>
      </div>
      </div>
	   <div class="col-md-4"> 
       <div class="form-group">
        <label for="email">Activity</label>
        <select name="activity" id="activity" class="form-control input-sm">
            <option value="">--- Select Activity ---</option>
            <option value="Activity 1">Activity 1</option>
			<option value="Activity 2">Activity 2</option>
			<option value="Activity 3">Activity 3</option>
         </select>
      </div>
      </div>
      </div> 
	  
	  
	  <div class="row">
	   <div class="col-md-4"> 
       <div class="form-group">
        <label for="last_cleaned">Activity Date</label>
        <input type="date" name="last_cleaned" id="last_cleaned" class="form-control"  automcomplete="off">
      </div>
	 </div>
	 <div class="col-md-4"> 
       <div class="form-group">
        <label for="last_cleaned_time">Activity Time</label>
       <!-- <input type="text" name="last_cleaned_time" id="last_cleaned_time" class="form-control"  automcomplete="off"  > -->
		 <select name="last_cleaned_time" id="last_cleaned_time" class="form-control select2" style="width:100%">
         </select>  
      </div>
	 </div>
	  <div class="col-md-4"> 
       <div class="form-group">
        <label for="executive">Executive</label>
        <select name="executive" id="executive" class="form-control input-sm">
            <option value="">--- Please select ---</option>
            <option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
         </select>
      </div>
      </div>
	  
	 
		  <div class="col-md-12">
			   <div class="form-group">
				<label for="remarks">Remarks</label>
				<textarea class="form-control" name="remarks" id="remarks"></textarea>
			  </div>
		  </div>
     
	  
      </div>  
	    <div style="text-align:center">
           <input name="save" id="save" type="submit" class="btn btn-primary" value="Save" /> 
			<!-- <input type="submit" class="btn btn-primary" onclick="saveBookerPopupform();" value="Save">-->
		   <button class="guest_close btn btn-danger">Close</button>
	   </div>
    </form>
  </div><div class="popup_align" style="display: inline-block; vertical-align: middle; height: 100%;"></div></div>
</div> 
        
 </div>
<!-- End Booker By  Model -->


<!-- Unassigned Model -->
<div class="modal fade in" id="unallocModal" tabindex="-1" role="dialog" aria-labelledby="unallocModalLabel" >
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>  
               <label class="modal-title" id="roomtitle1" style="font-size:22px;">REGAL SUITE</label>
            </div> 
<div class="modal-body" style="overflow-y: scroll; max-height:100%; ">
	<div id="ajaxPlanData">
		<div class="box box-success  table-responsive no-padding">
            <input type="text" name="bd_count" id="bd_count1" hidden="">
			<div id="hotelbutton1"></div><div id="noofRooms1"></div><table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Room No</th>
						<th>Floor / Block</th>   
						<th>Select</th>
					</tr>
				</thead>
				
 					  <input type="hidden" id="roomselectall1" name="roomselectall" value="0" hidden="">
					  <input type="hidden" id="roomselect1" name="roomselect1" value="0" hidden="">
					  <input type="hidden" id="idcount_color1" name="idcount_color1" value="1" hidden="">
					  
					  
					  
				<tbody id="roombutton1"><tr> <td style="padding:0px 15px"> 301 </td>
                         <td style="padding:0px 15px"> Block 3 </td>	 
						 <td style="padding:0px 15px"> <input type="checkbox" id="btn301" name="all_room_select1" onclick="btncolorchange(this.id)" style="height:17px;width:20px"> </td> 	</tr></tbody>

			</table>
        </div>
   </div>
</div>
     <div class="modal-footer" style="background-color: #e4e4e4;color: #fff;"> 
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submit" > <!-- onclick="bd_apply();" --> <span class="glyphicon glyphicon-ok"></span> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
        
    </div>
  </div>
</div>
</div>
<!-- Unassigned Model End -->

	
<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="auditbutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->



<!-- Reservation Modal 
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
                      <!--<label for="guest">Guest Details</label> --
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
                                <a href="#" class="text-info" id="guestEditId" data-guestid="<?php //echo encryptor(encrypt, '21') ?>"><i class="fa fa-edit"></i> </a>
                              </div>
                            </div>
                        </div>
                        <span id="guestnameError"><?php //echo $err_guestnameError;?></span>
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
                <!-- <button type="button" class="btn btn-success" onclick="saveReservation()">Save</button>--
            </div>
		 </form>
        </div>
    </div>
</div>
 End Reservation Modal -->
 
 
 


<!-- Reservation Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
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
					  
						<div class="col-md-8 form-group">
                            <label>Booking No</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-list-ol"></i>
                                </div>
                                <input type="text" name="bookingno" id="bookingno" class="form-control" placeholder="Enter Booking No" readonly>
                            </div>   
                        </div>
					   
                      </div>
					  
					  <div class="col-md-6" style="padding-right: 0px">
						<div class="col-md-8 form-group">
                            <label>Guest Name</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-male"></i>
                                </div>
                                <input type="text" name="bookingguestname" id="bookingguestname" class="form-control" placeholder="Enter Guest Name" readonly>
                            </div>   
                        </div>
                      </div>
                      
                    </div>
					
					 <div class="row">
					  <div class="col-md-8" style="padding-right: 0px">					  
						<div class="col-md-12 form-group">
                            <label>Source</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="bookingsource" id="bookingsource" class="form-control" readonly>
                            </div>   
                        </div>
					   
                      </div> 
                    </div>
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;">
                <button type="button" class="btn btn-success" id="Checkin"> <span class="glyphicon glyphicon-ok"></span> CheckIn</button>
                <button type="button" class="btn btn-warning" id="Checkout"><span class="glyphicon glyphicon-remove"></span> CheckOut</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
				<!--<input name="addBaseRate" id="addBaseRate" type="submit" class="btn btn-primary" value="Add" />
                 <button type="button" class="btn btn-success" onclick="saveReservation()">Save</button>-->
            </div>
		 </form>
        </div>
    </div>
</div>
<!-- End Reservation Modal -->


<!-- Rooms & Rates Modal -->
<div class="modal fade" id="roomratesModal_night" tabindex="-1" role="dialog" aria-labelledby="roomratesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                <h4 class="modal-title" id="roomratesModalLabel">Tariff Per Room Per Night</h4>
            </div>
            <div class="modal-body">
                <form id="roomratesForm" class="form">
                      <input type="hidden" name="clicked_id" id="clicked_id" class="form-control" hidden="">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label style="padding-top:30px" id="tariff_price_night">Tariff Price</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                          <label>Base Value</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input value="0" type="text" name="basevalue1" id="basevalue1" class="form-control" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                           <label>Taxes</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input value="0" type="text" name="tax1" id="tax1" class="form-control" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          <label>Inclusive Taxes</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input value="0" type="text" name="intax1" id="intax1" class="form-control" readonly onkeyup="basevalue_night()">
                            </div>
                        </div>                                         
                    </div> <br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Food Price</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" name="basevalue2" id="basevalue2" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                           
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div> 
                                <input type="text" name="tax2" id="tax2" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax2" id="intax2" class="form-control" readonly  value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>                                         
                    </div><br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Extra Bed</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" name="basevalue3" id="basevalue3" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax3" id="tax3" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax3" id="intax3" class="form-control" readonly value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>                                         
                    </div> <br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Child With Extra Bed</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" name="basevalue4" id="basevalue4" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax4" id="tax4" class="form-control" value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax4" id="intax4" class="form-control" readonly value="0" onkeyup="basevalue_night()">
                            </div>
                        </div>                                         
                    </div><br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Total</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" name="basevalue5" id="basevalue5" readonly  class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax5" id="tax5" readonly class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax5" id="intax5" readonly class="form-control" value="0">
                            </div>
                        </div>                                         
                    </div>                 
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;">
			<button type="button" class="btn btn-info" data-dismiss="modal" id="submit" onclick="breakdown();"> <span class="glyphicon glyphicon-asterisk"></span> Daily Breakup</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submit" onclick="rrtaxset();"> <span class="glyphicon glyphicon-ok"></span> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
       
            </div>
     </form>
        </div>
    </div>
</div>

<!-- End Reservation Modal -->


<!-- Rooms select Modal -->
<div class="modal fade" id="roomtypeModal1" tabindex="-1" role="dialog" aria-labelledby="roomtypeModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Rooms Select</label>
            </div>
            <div class="modal-body">
                <div class="row">
                  <input type="checkbox" id="all_room_select1" name="all_room_select1" onclick="selectall1();" style="margin: 0px 5px 20px 17px;">Select All <br>
                  <input type="hidden" id="roomselectall1" name="roomselectall1" value="0" hidden="">
                  <input type="hidden" id="roomselect1" name="roomselect1" value="0" hidden="">
                  <input type="hidden" id="idcount_color1" name="idcount_color1" value="0" hidden="">
                  <div id="roombutton1"></div>
                  <div id="hotelbutton1"></div>
                  <div id="noofRooms1"></div>
                </div>
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;">
                <button type="button" class="btn btn-success" data-dismiss="modal" onclick="roomselectzero()"> <span class="glyphicon glyphicon-ok"></span> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Skip</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Reservation Modal -->




<!-- Room Select Popup -->
 <div class="modal fade" id="roomtypeModal" tabindex="-1" role="dialog" aria-labelledby="roomtypeModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
               <label class="modal-title" id="roomtitle" style="font-size:22px;">Rooms Select</label>
            </div>
<div class="modal-body" style="overflow-y: scroll; max-height:100%; " >
	<div id="ajaxPlanData">
		<div class="box box-success  table-responsive no-padding">
            <input type="text" name="bd_count" id="bd_count" hidden="">
			<table class="table table-bordered table-striped">
				<thead>
					<tr  id="room_title">
						<th>Room No</th>
						<th>Floor / Block</th>         
						<th>Status</th>
						<th>Select</th>
					</tr>
				</thead>
				
 					  <input type="hidden" id="roomselectall" name="roomselectall" value="0" hidden="">
					  <input type="hidden" id="roomselect" name="roomselect" value="0" hidden="">
					  <input type="hidden" id="idcount_color" name="idcount_color" value="0" hidden="">
					  
					  <div id="hotelbutton"></div>
					  <div id="noofRooms"></div>
					  
					 <input type="checkbox" name="checkbox" id="checkbox"  onclick="roomtypechangealltype(this.id);" > Show All
					  
				<tbody id="roombutton">
				    
				</tbody>

			</table>
        </div>
   </div>
</div>
    <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;"> 
		<button type="button" class="btn btn-success" data-dismiss="modal" id="submit" > <span class="glyphicon glyphicon-ok"></span> Submit</button>
	    <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
    </div>
  </div>
</div>
</div>
<!-- Room Select Popup End -->
      

<!-- Rooms & Rates Modal -->

<div class="modal fade" id="roomratesModal" tabindex="-1" role="dialog" aria-labelledby="roomratesModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
                <h4 class="modal-title" id="roomratesModalLabel">Tariff Per Room Inclusive Taxes</h4>
                 
            </div>
            <div class="modal-body">
                <form id="roomratesForm" class="form">
                      <input type="hidden" name="clicked_id_tax" id="clicked_id_tax" class="form-control" hidden="">
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label style="padding-top:30px" id="tariff_price_intax">Tariff Price</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                          <label>Base Value</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input value="0" type="text" readonly name="basevalue6" id="basevalue6" class="form-control" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                           <label>Taxes</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input value="0" type="text" name="tax6" id="tax6" class="form-control" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          <label>Inclusive Taxes</label>
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input value="0" type="text" name="intax6" id="intax6" class="form-control"  onkeyup="basevalue()">
                            </div>
                        </div>                                         
                    </div> <br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Food Price</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" readonly name="basevalue7" id="basevalue7" class="form-control" value="0" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                           
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div> 
                                <input type="text" name="tax7" id="tax7" class="form-control" value="0" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax7" id="intax7" class="form-control" value="0"  onkeyup="basevalue()">
                            </div>
                        </div>                                         
                    </div><br>
                    <div class="row" id="extra_bed">
                        <div class="col-md-2 col-sm-2">
                          <label>Extra Bed</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" readonly name="basevalue8" id="basevalue8" class="form-control" value="0" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax8" id="tax8" class="form-control" value="0" onkeyup="basevalue()">
                      </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax8" id="intax8" class="form-control" value="0"  onkeyup="basevalue()">
                            </div>
                        </div>                                         
                    </div> <br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Child With Extra Bed</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" readonly name="basevalue9" id="basevalue9" class="form-control"  value="0" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax9" id="tax9" class="form-control" value="0" onkeyup="basevalue()">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax9" id="intax9" class="form-control" value="0"   onkeyup="basevalue()">
                            </div>
                        </div>                                         
                    </div><br>
                    <div class="row">
                        <div class="col-md-2 col-sm-2">
                          <label>Total</label>
                        </div>
                        <div class="col-md-4 col-sm-2">
                         
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-arrows"></i>
                                </div>
                                <input type="text" name="basevalue10" id="basevalue10" readonly  class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-bars"></i>
                                </div>
                                <input type="text" name="tax10" id="tax10" readonly class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-2">
                          
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <input type="text" name="intax10" id="intax10" readonly  class="form-control" value="0">
                            </div>
                        </div>                                         
                    </div>                 
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;">
			<button type="button" class="btn btn-info" data-dismiss="modal" id="submit" onclick="breakdown();"> <span class="glyphicon glyphicon-asterisk"></span> Daily Breakup</button>
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submit" onclick="rrintaxset();"> <span class="glyphicon glyphicon-ok"></span> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
        
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Reservation Modal -->






<!-- Room Unassigned Begin -->
 <div class="modal fade" id="roomunallocModal" tabindex="-1" role="dialog" aria-labelledby="roomunallocModalLabel">
    <div class="modal-dialog" role="document" >
        <div class="modal-content" style=" width: 120%;">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>  
               <label class="modal-title" id="roomtitle1" style="font-size:22px;">Rooms Select</label>
            </div>
<div class="modal-body" style="overflow-y: scroll; height:300px; " >
  <div id="ajaxPlanData">
    <div class="box box-success  table-responsive no-padding">
            
                <table id="calendar_cart" class="table table-bordered table-hover">
                  <thead>
                  <tr>
                    <th>Booking No</th>
                    <th>Room Type</th>
                    <th>Guest Name</th>
                    <th>Source</th>
                    <th>CheckIn</th>
                    <th>CheckOut</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
        </div>
   </div>
</div>
     <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;"> 
                <button type="button" class="btn btn-success" data-dismiss="modal" id="submit" onclick="bd_apply();"> <span class="glyphicon glyphicon-ok"></span> Submit</button>
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Cancel</button>
        
    </div>
  </div>
</div>
</div>
<!-- Room Unassigned End -->

 <div id="ratePoint" class="well" style="display:none;">
	<form id="ratePointForm" autocomplete="off">
    <div ></div>
    <p class="help-block" id="updatestatussuccess"></p>
	</form>
	<button class="ratePoint_close btn btn-default pull-right" >Close</button>
</div>


<div id="ratePointfaild" class="well" style="display:none;">
	<form id="ratePointfaildForm" autocomplete="off">
    <div ></div>
    <p class="help-block" id="updatestatus"></p>
	</form>
	<button class="ratePointfaild_close btn btn-default pull-right" >Close</button>
</div>
<?php include_once("../includes/footer.php")?>

<script>
    $(document).ready(function(){
		
		//Don't Delete hid and show
		$('#roomaloc').hide();

        initializeTableArrivals = (target,targetArr,ordering=false) =>{
            $(target || '#booking_list').DataTable({
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
                                    <td onclick="reservationDetails('${value.id}')"><a href="#" style="color:black;">${(value.booking_no)}</a></td>
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
                        $("#expected_arrivals")
                    .DataTable()
                    .destroy();
                $("#expected_arrivals tbody").html(tableData);
                initializeTableArrivals("#expected_arrivals", [1, 3, 4, 5]);
                       /* $("#expected_arrivals").DataTable().destroy();
                        $("#expected_arrivals tbody").html(tableData);
                        initializeTableArrivals('#expected_arrivals',[0,1,3,5,6,7,8],true);*/
						 
                    }
                });
        }
       
        roomStats = (date = "") => {
            $.ajax({
                url: "stats-old.php",
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

                   tableData += `<td onclick="reservationDetails('${value.id_fo_reservations}')"><a href="#" style="color:black;">${value.res_id}</a></td>
                                    <td onClick="guestDetails('${value.id_mst_guest}','edit')"><a href="#" style="color:black;">${value.guest}</a></td>    
                                    <td onclick="InvoiceDetails('${value.id_fo_view_folio}')"><a href="#" style="color:black;">${value.folio}</a></td>
                                    <td>${value.checkin}</td>
                                    <td>${value.checkout}</td>
                                    <td>
                                        
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
		
		
		
		
		 arrivals_cart = (date='') =>{
                $.ajax({
                    'url':'unassigned.php',
                    'data':'date='+date,
                    'dataType':'JSON',
                    success:function(data){
                        var tableData = '';
                        data.forEach((value,key,arr)=>{
                            tableData += `
                                <tr>
                                   <!-- <td>${(key+1)}</td> -->
                                    <td><a href="javascript:;" style="color:black;">${(value.reservation_id)}</a></td>
                                    <td><a href="javascript:;" style="color:black;">${(value.roomType)}</a></td>
                                    <td><a href="javascript:;" style="color:black;">${(value.guest)}</a></td>
                                    <td><a href="javascript:;" style="color:black;">${(value.source)}</a></td>
                                    <td><a href="javascript:;" style="color:black;">${(value.checkIn)}</a></td>
                                    <td><a href="javascript:;" style="color:black;">${(value.checkOut)}</a></td>
                                    
                                    <!-- <td><button class="btn btn-success btn-xs" onclick="getRoomDetails_cart('${value.reservation_id}',${value.pending},'${value.id}');" data-toggle="tooltip" title="view rooms"><i class="fa fa-eye"></i></button></td> -->
									<td><button class="btn btn-success btn-xs" onclick="unassigned_popup();" title="view rooms"><i class="fa fa-eye"></i></button>
                                </tr>
                                <tr id="trcart_${value.reservation_id}" style="display:none"></tr>
                            `;
                        });
                         
						
						 $("#calendar_cart").DataTable().destroy();
                        $("#calendar_cart tbody").html(tableData);
                        //initializeTableArrivals('#calendar_cart',[0,1,3,5,6,7,8],true);
                    }
                });
        }

        arrivals(); 
        arrivals_cart(); 
        roomStats();
        folio();
        popup = ()=>{
            $("#checkinModal").modal('show');
        }

    });

   function getRoomDetails(resId,pending,userid){
  //  alert(userid);
      var tr = "#tr_"+resId;
      $.ajax({
        url : 'ajax/ajaxGetRooms.php',
        type : 'POST',
        data : {resId:resId,Id:userid},
        dataType : 'JSON',
        success : function(resp){
         //alert(resp);
		 //resp= JSON.parse(resp);
          var tableData = '';
		  var roomid=[];
          tableData += `<td colspan="9"><div class="row">
                       <div class="col-md-12 col-sm-12">
                         <div class="box box-primary box-outline">
                           <div class="box-body">`;
                            resp.forEach((value,key,arr) => { //alert(value.room_id);
							//arra[key]=(value.room_id);
                            tableData += `
                              <div class="row" >
                                <div class="col-md-12 col-sm-12">
                                  <h4>${value.RoomName} <span id="roomTypeName_${resId}_${value.room_id}" class="text-primary"></span></h4> 
                                </div>

                                <div class="col-md-12">
                                  <div class="row text-center">`;
                                    value.RoomDetails.forEach((datavalue,keys,arrays) => {
									//roomArr =	value.bookedRoom;
									
                                      if ($.inArray(datavalue, value.Roomnopicked) !== -1) {
										 // alert(JSON.stringify(value.bookedRoom));
									roomArr =	(value.bookedRoom);
									
	   tableData += `<div class="col-md-1 col-sm-1 col-xs-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-block btn-danger" onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
    } else {
        tableData += `<div class="col-md-1 col-sm-1 col-xs-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" onclick="selectRoom(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
    }  
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
	
	function getRoomDetails_cart(resId,pending,userid){
    //alert(userid);
      var tr = "#trcart_"+resId;
      $.ajax({
        url : 'ajax/ajaxUnassignedRoom.php',
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
                                  <h4>${value.RoomName} <span id="roomTypeNamecart_${resId}_${value.room_id}" class="text-primary"></span></h4> 
                                </div>

                                <div class="col-md-12">
                                  <div class="row text-center">`;
                                    value.RoomDetails.forEach((datavalue,keys,arrays) => {
                                      tableData += `<div class="col-md-1 col-sm-1 col-xs-1" style="padding-right:0px; margin-top: 2px; margin-left:0px; margin-right: 0px;"><button class="btn btn-success btn-block" style="padding:6px 5px;" onclick="selectRoom_cart(${value.room_id},${datavalue},this.id,'`+ resId +`',`+ pending +`);" id="btn-`+resId+`-${datavalue}">${datavalue}</button></div> `;
                                    });
                                  tableData += `</div></div></div>`;
                            });
                            tableData += `
                            <div id="showBookedRoomcart_${resId}"></div>
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
     //  alert("reservationDetails");
    $.ajax({
       type: "POST",
       url: 'ajax/ajaxReservationDetails.php',
       data:{resID:resId}, 
       dataType:'JSON',
       success: function (result) {   
           // $("#tab_2,#tab_1,#tab_3,.nav-tabs li").removeClass("active");
            $("#tab_3,#tab_1,#tab_4,#tab_5,#tab_6,#tab_9,.nav-tabs li").removeClass("active");
            $("#tab_2,#reservation_tab").addClass("active");

            /* filling more options form here */
		   $("#Theader").html(result.tableheader);
			$("#onewindow").hide();
            $("#res_bookingNo").val(result.res_id);
            $("#res_bookingDate").val(result.booking_date);
            $("#res_hotelName").val(result.Hotel);//html('<option value="'+ result.id_mst_hotels +'">'+ result.Hotel +'</option>'); 
            $("#res_checkinDate").val(result.CheckIn) ; 
            $("#res_checkOutDate").val(result.CheckOut) ;  
            $("#res_guestName").val(result.Guest+" | "+result.Email+" | "+result.res_id) ; 
            $("#newCheckout").val(result.CheckOut) ;
            $("#newRooms").val(result.Rooms) ;  
			$("#rrcounter").val(result.rrcounter) ;
			$("#id_mst_hotels").val(result.id_mst_hotels);  
			
			$("#subtotal").val(result.sub_total);
			$("#discount").val(result.discount);
			$("#total_addon_price").val(result.total_addon_price);
			$("#total_taxes").val(result.total_tax);
			$("#amount_receive").val(result.amount_received);
			$("#balance").val(result.balance);
			$("#total").val(result.net_booking_amount);
			$("#eId").val(result.Id);
			$("#internal_remarks").val(result.internal_remarks);
			$("#res_specialrequest").val(result.special_requests);
			
			
			$("#id_mst_attributes_company_group").val(result.id_mst_attributes_company_group).change();
			$("#res_source").val(result.id_mst_company).change();
			companySelect(result.id_mst_company);
			bookerby_labelchange2(result.id_mst_company,result.res_bookerName);
			//$("#res_bookerName").val(result.res_bookerName).change();
			
			$("#res_bookingthrough").val(result.res_bookingthrough).change();
			$("#res_segment").val(result.res_segment).change();
			$("#res_bookingsourcee").val(result.res_bookingsourcee).change();
			$("#res_amendment").val(result.res_amendment).change();
			$("#res_modeoftravel").val(result.res_modeoftravel).change();
			$("#res_arrivingtime").val(result.res_arrivingtime).change();
						
			$("#res_pickupdetails").val(result.pickup_details).change();
			//$("#departing_to").val(result.res_arrivingtime).change();
			$("#res_arrivingfrom").val(result.arrival_from).change();
			$("#res_departingto").val(result.departing_to).change();
			$("#other_ref").val(result.other_reference).change();
			
			guestSelect(result.id_mst_guest);
			
			
			
		
            //$("#res_guestAddId").attr('onClick','guestDetails("","add")');
            //$("#res_guestEditId").attr('onClick','guestDetails("'+result.id_mst_guest+'","edit")');
			//$("#res_source").attr('onClick','companySelect("'+result.id_mst_company+'","edit")');
			
			$('table.order-list1').html('');
			  var list_fieldHTML = result.roomdetails;//'<tr><td><select class="form-control parsley-error" name="roomtype[]" id="roomtype'+rrcounter+'" onchange="roomtypechange(this.id,this.value);"  style="width: 140px;"></select> <input value="" type="hidden" name="roomno[]" id="roomno'+rrcounter+'" class="form-control" ></td><td><select class="form-control parsley-error" name="plan[]" id="plan'+rrcounter+'" onchange="getval(this.id)" style="width: 110px;"></select><input type="hidden" name="plantype[]" id="plantype'+rrcounter+'" value=""></td><td class="form-group" style="display:inline-flex"><select name="noofRooms[]" id="noofRooms'+rrcounter+'" onchange="countcolorchange(this.id);tariffCalculation(this.id,this.value);" class="form-control parsley-error"  style="width: 80px;"><option value="">Rooms</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option></select></td><td><select class="form-control parsley-error" name="adultperroom[]" id="adultperroom'+rrcounter+'" style="width: 70px;"><option value="1">1</option><option selected="selected"class="2">2</option><option class="3">3</option></select></td><td><select class="form-control parsley-error" name="childbelow[]" id="childbelow'+rrcounter+'" style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td><td><select class="form-control parsley-error" name="childabove[]" id="childabove'+rrcounter+'"  style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td><td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px;" name="tariffperroom[]" value="0" onkeyup="tariffCalculation(this.id,this.value);"  id="tariffperroom'+rrcounter+'" /><a class="btn btn-success btn-sm" id="night'+rrcounter+'" onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td><td><input type="text" class="form-control parsley-error" style="width: 70px;" name="taxes[]" id="taxes'+rrcounter+'" value="" readonly /></td><td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px" name="tariffperroomtax[]" id="tariffperroomtax'+rrcounter+'" onkeyup="tariffCalculation(this.id,this.value);" value="" /><a class="btn btn-success btn-sm" id="itform'+rrcounter+'"  onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td><td><input type="text" class="form-control parsley-error" style="width: 70px;" value="" name="chargespernight[]" id="chargespernight'+rrcounter+'"  readonly /></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>';


			  $('table.order-list1').append(list_fieldHTML); 
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

<script src='https://unpkg.com/popper.js/dist/umd/popper.min.js'></script>
<script src='https://unpkg.com/tooltip.js/dist/umd/tooltip.min.js'></script>

<script type="text/javascript">
  var roomArr = {};

   function selectRoom(roomTypeId,roomNo,btnId,resvId,pending){ 
    var btn = "#"+btnId;


    var divId = "#showBookedRoom_"+resvId;
    var roomTypeName = "#roomTypeName_"+resvId+"_"+roomTypeId;
    var roomTypeId = roomTypeId;
    let newRoomTypeId = resvId+"_"+roomTypeId;
    let roomCount = 0;
    if($(btn).hasClass("btn-success")){            
     for(var key in roomArr){ 
     // roomCount = roomCount + roomArr[key].length;   //(only select 2 pending option code)
      }
      if(pending == roomCount){
              bootbox.alert("Please Select only " + pending + " rooms");
      }else{ 
          $(btn).removeClass("btn-success").addClass("btn-danger");
          if(roomArr.hasOwnProperty(newRoomTypeId)){ alert('step1');
             roomArr[newRoomTypeId].push(roomNo);
          }else{
             roomArr[newRoomTypeId] = roomNo;
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
			   var removeRoomArr = eval(roomArr[newRoomTypeId]);
			   
			  
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
   //}
  }
  
  
  
  function selectRoom_cart(roomTypeId,roomNo,btnId,resvId,pending){ 
    var btn = "#"+btnId;
   // alert(btn);
    var divId = "#showBookedRoomcart_"+resvId;
    var roomTypeName = "#roomTypeNamecart_"+resvId+"_"+roomTypeId;
    var roomTypeId = roomTypeId;
    let newRoomTypeId = resvId+"_"+roomTypeId;
    let roomCount = 0;
    if($(btn).hasClass("btn-success")){            
     for(var key in roomArr){
     // roomCount = roomCount + roomArr[key].length;   //(only select 2 pending option code)
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
        // alert(JSON.stringify(roomArr));
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
		
        // editable: true,
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
		
		customButtons: {
			myCustomButton: {
			  text: 'Room Allocation',
			  click: function() {
				//alert('clicked the unassigned button!');
				//$("#roomunallocModal").modal("show");
				$("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
			    $("#tab_8,#roomaloc").addClass("active");
	            $('#roomaloc').show();
			  }
			}
		  },
  
		header: {
				left: "today prev,next, myCustomButton",
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
		
		 resourceRender: function(renderInfo) {
              // renderInfo.el.style.backgroundColor = '#0089c3'#004289;
               renderInfo.el.style.backgroundColor = '#0063ce';
               renderInfo.el.style.color = '#fff';
               renderInfo.el.style.height = '40px';
         },
		

			   
        resourceLabelText: "Room Types",
        resources: function(fetchInfo, successCallback, failureCallback) {
       
		   $.ajax({
                url: "resources.php",
                dataType: "JSON",
                success: function(data) {
                    //console.log(data); 
					successCallback(data);
                }
            });
        },
	
		
	
       eventMouseEnter: function(calEvent) {
            $.ajax({
      				type: "POST",
      				url: "ajax/popup_event.php",
      				data: "&id=" + calEvent.event.id,
      				success: function (response) {
      					var response = JSON.parse(response);
      					var id = response['id_value'];
      					  var booking_no = response['booking_no'];
      					   var guest_name = response['guest_name'];
      						var source = response['source'];
      							$("#bookingno").val(booking_no);
      							$("#bookingguestname").val(guest_name);
      							$("#bookingsource").val(source);
						    //$("#eventContent").dialog({ title: event.title, width:350});
							
							var tooltip = '<div  id="removepopup" onmouseLeave="tooltippopup()" class="tooltipevent container-fluid"><div class="card1"><h1 class="titlee">Guest Information <span style="float:right;font-size:20px;" onclick="normalImg(this)"> <span class="glyphicon glyphicon-remove"></span> </span></h1><table id="customers1"> <tr> <tr><td>Guest Name</td><td>'+guest_name+ '</td></tr><tr><td>Booking No</td><td>'+booking_no+ '</td></tr><tr><td>Source</td><td>'+source+'</td></tr></table><ul class="social-icons"><li><a href="#"><button type="button" class="btn btn-success" id="Checkin" style="padding:5px 5px;"> <span class="glyphicon glyphicon-ok"></span> CheckIn</button></a></li><li><a href="#"><button type="button" class="btn btn-warning" id="Checkout" style="padding:5px 5px;"><span class="glyphicon glyphicon-remove"></span> CheckOut</button></a></li><li><button onclick="normalImg(this)" type="button" class="btn btn-danger" data-dismiss="modal" style="padding:5px 5px;"> <span class="glyphicon glyphicon-off"></span> Cancel</button></li></ul></div></div>';
							$("body").append(tooltip);
							//$("#reservationModal").modal("show");
				}
			}); 
			
   
	//$("body").append(tooltip);
	/*$(this.el).mouseover(function(e) {
		$(this.el).css('z-index', 10000);
		$('.tooltipevent').fadeIn('500');
		$('.tooltipevent').fadeTo('10', 1.9);
	}).mousemove(function(e) {      
		$('.tooltipevent').css('top', e.pageY + -5);
		$('.tooltipevent').css('left', e.pageX + -200);
	});*/
}, 

	
       events: function(fetchInfo, successCallback, failureCallback) {
			// debugger;
			 var startDate= formatDate(fetchInfo.start);
			 var endDate = formatDate(fetchInfo.end);
			 eventSourceCallback = successCallback;
				$.ajax({
					url: "ajax/load.php",
						type: 'POST',
					data: { startDate : startDate,startEnd : endDate,id_hotel : 1 },
					dataType: "JSON",
					success: function(data) {
						//console.log(data);
						//alert(data[0]['title']);
						successCallback(data);
					}
				});
		},
		 
		 
		//calendar avl row not selected 
		/*selectAllow:function(data){
			//debugger;
			if(data.resource._resource.parentId && data.resource._resource.parentId.length>0)
				return true;
			return false;
		}, */
		
		viewDisplay :function(element)
		{
		//debugger;	
		},
        select: function(info) {
			//debugger;
			let checkin = moment(info.start).format("DD-MM-YYYY");
            let checkout = moment(info.end).format("DD-MM-YYYY");
          //  let id_hotel = $(65).val();

          	let id_room = 0;
            id_room = info.resource._resource.parentId == "" ? info.resource._resource.id : info.resource._resource.parentId;
            /* Setting vaules in reserve form */
			
            let roomType = info.resource._resource.parentId == "" ? info.resource._resource.title :window.appCalendar.getResourceById(info.resource._resource.parentId).title  ;
            let label = 'Room : '+roomType+',Checkin : '+moment(checkin).format('DD-MM-YYYY')+',Checkout : '+moment(checkout).subtract(1,'days').format('DD-MM-YYYY');
            $("#reservationModalLabel").html(label);
            $("#res_checkinDate").val(checkin);
            $("#res_checkOutDate").val(checkout);
            $("#res_hotelName").val('HIROHAMA INDIA PVT LTD');
            $("#res_room").val(id_room);
            $("#id_mst_hotels").val(1); 			
			
			//$('table.order-list1').html('');
			$("#subtotal").val('0');
			$("#discount").val('0');
			$("#total_addon_price").val('0');
			$("#total_taxes").val('0');
			$("#amount_receive").val('0');
			$("#balance").val('0');
			$("#total").val('0');
			$("#eId").val('');
			$("#internal_remarks").val('');
			$("#res_specialrequest").val('');
			
			
			$("#id_mst_attributes_company_group").val('').change();
			$("#res_source").val('').change();
			companySelect('');
			bookerby_labelchange2('','');
			//$("#res_bookerName").val(result.res_bookerName).change();
			
			$("#res_bookingthrough").val('').change();
			$("#res_segment").val('').change();
			$("#res_bookingsourcee").val('').change();
			$("#res_amendment").val('').change();
			$("#res_modeoftravel").val('').change();
			$("#res_arrivingtime").val('').change();
						
			$("#res_pickupdetails").val('').change();
			//$("#departing_to").val(result.res_arrivingtime).change();
			$("#res_arrivingfrom").val('').change();
			$("#res_departingto").val('').change();
			$("#other_ref").val('').change();
			guestSelect('');
			
			
			
			var roomtype = roomType.split('(');
	        var roomtype = roomtype[0];
			
            $("#roomtype").val(roomtype);
			
			$("#parentId").val(info.resource._resource.id);
			
			//$("#reservationModal").modal("show");
			$("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,#tab_9,.nav-tabs li").removeClass("active");
			$("#tab_2,#reservation_tab").addClass("active");
			
			hotelnameget(1);
			
		}
	});


	
$(document).ready(function() {
	/* Dynamic Table Hide and Show Section Begain */
	/* Dynamic Table Hide and Show Section End */
	
    window.appCalendar.render();

    $("#more-options").click(function() {
        $("#reservationModal").modal("hide");
        $("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
        $("#tab_2,#reservation_tab").addClass("active");
    }); 
	
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
	
function hide_show(){
	$('#roomaloc').hide();
}
	
</script>
<!--End Default calender view JS -->







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
		customButtons: {
			myCustomButton: {
			  text: 'Room Allocation',
			  click: function() {
				alert('clicked the unassigned button!');
			  }
			}
		  },
  
		header: {
				left: "today prev,next, myCustomButton",
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
		
		 resourceRender: function(renderInfo) {
               renderInfo.el.style.backgroundColor = '#0089c3';
               renderInfo.el.style.color = '#fff';
               renderInfo.el.style.height = '40px';
         },
		
		resourceLabelText: "Room Types",
		resources: function(fetchInfo, successCallback, failureCallback) {
			var startDate= formatDate(fetchInfo.start);
			var endDate = formatDate(fetchInfo.end);
			//debugger;
			$.ajax({
				url: "resources.php",
				type: 'POST',
				data: { str : str, startDate : startDate,startEnd : endDate,id_hotel : str },
				dataType: "JSON",
				
				success: function(data) {
                    //console.log(data);
					//debugger;
                    successCallback(data);
                }
			});
		
		}, 
		
		      eventMouseEnter: function(calEvent) {
            $.ajax({
      				type: "POST",
      				url: "ajax/popup_event.php",
      				data: "&id=" + calEvent.event.id,
      				success: function (response) {
      					var response = JSON.parse(response);
      					 //var id = response['id'];
      					  var booking_no = response['booking_no'];
      					   var guest_name = response['guest_name'];
      						var source = response['source'];
      							$("#bookingno").val(booking_no);
      							$("#bookingguestname").val(guest_name);
      							$("#bookingsource").val(source);
						    //$("#eventContent").dialog({ title: event.title, width:350});
							
							var tooltip = '<div  id="removepopup" onmouseLeave="tooltippopup()" class="tooltipevent container-fluid"><div class="card1"><h1 class="titlee">Guest Information <span style="float:right;font-size:20px;" onclick="normalImg(this)"> <span class="glyphicon glyphicon-remove"></span> </span></h1><table id="customers1"> <tr> <tr><td>Guest Name</td><td>'+guest_name+ '</td></tr><tr><td>Booking No</td><td>'+booking_no+ '</td></tr><tr><td>Source</td><td>'+source+'</td></tr></table><ul class="social-icons"><li><a href="#"><button type="button" class="btn btn-success" id="Checkin" style="padding:5px 5px;"> <span class="glyphicon glyphicon-ok"></span> CheckIn</button></a></li><li><a href="#"><button type="button" class="btn btn-warning" id="Checkout" style="padding:5px 5px;"><span class="glyphicon glyphicon-remove"></span> CheckOut</button></a></li><li><button onclick="normalImg(this)" type="button" class="btn btn-danger" data-dismiss="modal" style="padding:5px 5px;"> <span class="glyphicon glyphicon-off"></span> Cancel</button></li></ul></div></div>';
							$("body").append(tooltip);
							//$("#reservationModal").modal("show");
				}
			}); 
			
   
	//$("body").append(tooltip);
	$(this.el).mouseover(function(e) {
		$(this.el).css('z-index', 10000);
		$('.tooltipevent').fadeIn('500');
		$('.tooltipevent').fadeTo('10', 1.9);
	}).mousemove(function(e) {      
		$('.tooltipevent').css('top', e.pageY + -5);
		$('.tooltipevent').css('left', e.pageX + -200);
	});
}, 


/*
eventMouseLeave: function(calEvent, jsEvent) {
  $(this).css('z-index',8);
  $('.tooltipevent').remove();
  $("body").removeClass("tooltipevent");
}, 
  
	   
	/*	eventMouseEnter: function (calEvent) {
			$.ajax({
				type: "POST",
				url: "ajax/popup_event.php",
				data: "&id=" + calEvent.event.id,
				success: function (response) {
					var response = JSON.parse(response);
					 var id = response['id'];
					  var booking_no = response['booking_no'];
					   var guest_name = response['guest_name'];
						var source = response['source'];
							$("#bookingno").val(booking_no);
							$("#bookingguestname").val(guest_name);
							$("#bookingsource").val(source);
							$("#reservationModal").modal("show");
				}
			}); 
		},  

	  eventClick: function (calEvent) {
			//alert(calEvent.event.id);
                $.ajax({
                    type: "POST",
                    url: "ajax/popup_event.php",
                    data: "&id=" + calEvent.event.id,
                    success: function (response) {
						var response = JSON.parse(response);
                         var id = response['id'];
						  var booking_no = response['booking_no'];
						   var guest_name = response['guest_name'];
						    var source = response['source'];
								$("#bookingno").val(booking_no);
								$("#bookingguestname").val(guest_name);
								$("#bookingsource").val(source);
							$("#reservationModal").modal("show");
                    }
                });
            
        },	*/
		
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
		

		
	/* calendat avl row not drag
	selectAllow:function(data){
			
			//debugger;
			if(data.resource._resource.parentId && data.resource._resource.parentId.length>0)
				return true;
			return false;
			if(data.event._event.parentId && data.event._event.parentId.length>0)
				return true;
			return false;
		},*/
		
		
		viewDisplay :function(element)
		{
		//debugger;	
		},
		select: function(info) {
			//debugger;
			let checkin = moment(info.start).format("DD-MM-YYYY");
            let checkout = moment(info.end).format("DD-MM-YYYY");
            let id_hotel = $(str).val();
			
            let id_room = 0;
            id_room = info.resource._resource.parentId == "" ? info.resource._resource.id : info.resource._resource.parentId;
            /* Setting vaules in reserve form */
            
            let roomType = info.resource._resource.parentId == "" ? info.resource._resource.title :window.appCalendar.getResourceById(info.resource._resource.parentId).title  ;
            let label = 'Room : '+roomType+',Checkin : '+moment(checkin).format('DD-MM-YYYY')+',Checkout : '+moment(checkout).subtract(1,'days').format('DD-MM-YYYY');
            $("#reservationModalLabel").html(label);
            $("#res_checkinDate").val(checkin);
            $("#res_checkOutDate").val(checkout);
            $("#res_hotelName").val('check-'+name);
            $("#res_room").val(id_room);
            $("#id_mst_hotels").val(str);
            $("#roomtype").val(roomType);
			
			$("#parentId").val(info.resource._resource.id);
			
            /* end */
            //$("#reservationModal").modal("show");
			//$("#more-options").click(function() {
			//$("#reservationModal").modal("hide");
			
			$("#tab_1,#tab_3,#tab_4,#tab_5,#tab_6,#tab_9,.nav-tabs li").removeClass("active"); 
			$("#tab_2,#reservation_tab").addClass("active");
			//window.open(url, "_blank");
			//});
			
			 hotelnameget(str);
			 
			 
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
            data: $("#reservationDetails_s").serialize(),
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



<script type="text/javascript">
  $("#preDate").change(function(){
    var date = $(this).val();
    $("#reservation_date").val(date);
  });
</script>

<script type="text/javascript">
    $(document).ready(function(){
		//Don't Delete House Keep Onload Working
			housekeep_ajax();
     // list_ajax();
		//End

        $(document).on('change','#res_bookingStatus',function(){
            var bookingStatus = $(this).val();
            if(bookingStatus == "Confirmed"){

                var confirmed = '<div class="form-group row"> <label for="res_confirmDate" class="col-sm-3 col-md-3 col-form-label">Confirm Date</label><div class="col-sm-6 col-md-6"><input type="date" class="form-control datepicker" value="" name="res_confirmDate" id="res_confirmDate" placeholder="dd-mm-yyyy"data-parsley-errors-container="#res_confirmDateError" /><span id="res_confirmDateError"><?php echo $err_res_confirmDateDateError;?></span></div></div>';

                $("#res_bookingList").html(confirmed);

            }else if(bookingStatus == "Tentative"){

                var holdtill = '<div class="form-group row"> <label for="res_holdTillDate" class="col-sm-3 col-md-3 col-form-label">Hold Till Date</label><div class="col-sm-6 col-md-6"><input type="date" class="form-control datepicker" value="" name="res_holdTillDate" id="res_holdTillDate" placeholder="dd-mm-yyyy"data-parsley-errors-container="#res_holdTillDateError" /><span id="res_holdTillDateError"><?php echo $err_res_holdTillDateError;?></span></div></div>';

                $("#res_bookingList").html(holdtill);

            }
            else if(bookingStatus == "Cancelled"){

                var cancelled = '<div class="form-group row"> <label for="res_cancellation" class="col-sm-3 col-md-3 col-form-label">Cancellation Reason</label><div class="col-sm-6 col-md-6"><select class="form-control select2" style="width: 100%;" id="res_cancellation" name="res_cancellation" data-parsley-errors-container="#res_cancellationError" data-parsley-required></select><span id="res_cancellationError"><?php echo $err_res_cancellationError;?></span></div></div>';
				
				 $.ajax({
						url: "ajax/cancellation.php",
						  type: 'POST',
						data: { },
						dataType: "JSON",
						success: function(data) {
							// alert(data);
						  $('#res_cancellation').html(data);
						}
					  });

               $("#res_bookingList").html(cancelled);
            }
        });


      /*  $(document).on('change','#res_bookingType', function(){
            var bookingTypeId = $(this).val();
            if(bookingTypeId == 1){
              $('#sourcename').html('Call Center');
                var directGuest = "<option value='Direct Guest1'>Direct Guest1</option><option value='Direct Guest2'>Direct Guest2</option><option value='Direct Guest3'>Direct Guest3</option>";
                $("#res_source").html(directGuest);
            }
            else if(bookingTypeId == 2){
              $('#sourcename').html('Travel Agent');
                var travelAgent = "<option value='Travel Agent1'>Travel Agent1</option><option value='Travel Agent2'>Travel Agent2</option><option value='Travel Agent3'>Travel Agent3</option>";
                $("#res_source").html(travelAgent);
            }
            else if(bookingTypeId == 3){
              $('#sourcename').html('Corporate');
                var corporate = "<option value='Corporate1'>Corporate1</option><option value='Corporate2'>Corporate2</option><option value='Corporate3'>Corporate3</option>";
                $("#res_source").html(corporate);
            }else{
              $("#res_source").html('<option value="">Select Source</option>');
            }
        }); */
		

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
              $("#res_rateLetter").html('<option value="">Select Rate Letter</option>');
            }
        });

        $(document).on('change','#res_pickuprequired', function(){
            var pickupStatus = $(this).val();
		
			 if(pickupStatus == "1"){
				 $('#hidee').hide();

                var pickup = '<div class="form-group row"><label for="res_modeoftravel" class="col-sm-3 col-md-3 col-form-label">Mode of Travel</label><div class="col-sm-6 col-md-6"><select class="form-control  select2" style="width:100%" id="res_modeoftravel" name="res_modeoftravel" data-parsley-errors-container="#res_modeoftravelError" data-parsley-required><option selected="selected" value="">Select please</option><option value="By Air">By Air</option><option value="By Train">By Train</option><option value="By Road">By Road</option></select><span id="res_modeoftravelError"><?php echo $err_res_modeoftravelError;?> </span></div></div><div class="form-group row"><label for="res_pickupdetails" class="col-sm-3 col-md-3 col-form-label">Pickup Details</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_pickupdetails" name="res_pickupdetails"placeholder="Enter Pickup Details" disableddata-parsley-errors-container="#res_pickupdetailsError" data-parsley-required /><span id="res_pickupdetailsError"><?php echo $err_res_pickupdetailsError;?></span></div></div><div class="form-group row"><label for="res_arrivingfrom" class="col-sm-3 col-md-3 col-form-label">Arriving from</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_arrivingfrom" name="res_arrivingfrom"placeholder="Enter Arriving from" disableddata-parsley-errors-container="#res_arrivingfromError" data-parsley-required /><span id="res_arrivingfromError"><?php echo $err_res_arrivingfromError;?></span></div></div><div class="form-group row"><label for="res_arrivingtime" class="col-sm-3 col-md-3 col-form-label">Arriving time</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_arrivingtime" name="res_arrivingtime"placeholder="Enter Arriving time" disableddata-parsley-errors-container="#res_arrivingtimeError" data-parsley-required /><span id="res_arrivingtimeError"><?php echo $err_res_arrivingtimeError;?></span></div></div><div class="form-group row"><label for="res_departingto" class="col-sm-3 col-md-3 col-form-label">Departing to</label><div class="col-sm-6 col-md-6"><input type="text" class="form-control" id="res_departingto" name="res_departingto"placeholder="Enter Departing to" disableddata-parsley-errors-container="#res_departingtoError" data-parsley-required /><span id="res_departingtoError"><?php echo $err_res_departingtoError;?></span></div></div>';

                $("#pickupDetails").html(pickup);
            }
            else if(pickupStatus == "0"){
                var pickup = "";
				$('#hidee').hide();
               $("#pickupDetails").html(pickup);
            }
        });

    });

/*
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
		}); 

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
*/
    // roomsRates function


    $(document).ready(function () { 
    
    $('#roomhideandshow').hide();
    $('#addonshideandshow').hide();
    $('#paymentshideandshow').hide();
   // var rrcounter = 0;
    var addonscounter = 0;
    var paymentscounter = 0;
	
	
	

    $("#roomsRates").on("click", function () {
		var rrcounter 		= $("#rrcounter").val();		
        var list_maxField = 10;
        rrcounter++;
        if(rrcounter < list_maxField){ 
			  $('#roomhideandshow').show();    
//onclick="roomtypechange(this.id,this.value);"
			  var list_fieldHTML = '<tr><td><select class="form-control parsley-error" name="roomtype[]" id="roomtype'+rrcounter+'" onchange="roomtypechange(this.id,this.value);"  style="width: 140px;"></select> <input value="" type="hidden" name="roomno[]" id="roomno'+rrcounter+'" class="form-control" ></td><td><select class="form-control parsley-error" name="plan[]" id="plan'+rrcounter+'" onchange="getval(this.id)" style="width: 110px;"></select><input type="hidden" name="plantype[]" id="plantype'+rrcounter+'" value=""></td><td class="form-group" style="display:inline-flex"><select name="noofRooms[]" id="noofRooms'+rrcounter+'" onchange="countcolorchange(this.id);tariffCalculation(this.id,this.value);" class="form-control parsley-error"  style="width: 80px;"><option value="">Rooms</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option></select></td><td><select class="form-control parsley-error" name="adultperroom[]" id="adultperroom'+rrcounter+'" style="width: 70px;"><option value="1">1</option><option selected="selected"class="2">2</option><option class="3">3</option></select></td><td><select class="form-control parsley-error" name="childbelow[]" id="childbelow'+rrcounter+'" style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td><td><select class="form-control parsley-error" name="childabove[]" id="childabove'+rrcounter+'"  style="width: 70px;"><option selected="selected" value="0">0</option><option value="1">1</option><option class="2">2</option></select></td><td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px;" name="tariffperroom[]" value="0" onkeyup="tariffCalculation(this.id,this.value);"  id="tariffperroom'+rrcounter+'" /><a class="btn btn-success btn-sm" id="night'+rrcounter+'" onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td><td><input type="text" class="form-control parsley-error" style="width: 70px;" name="taxes[]" id="taxes'+rrcounter+'" value="" readonly /></td><td style="display:inline-flex"><input type="text" class="form-control parsley-error" style="width: 60px" name="tariffperroomtax[]" id="tariffperroomtax'+rrcounter+'" onkeyup="tariffCalculation(this.id,this.value);" value="" /><a class="btn btn-success btn-sm" id="itform'+rrcounter+'"  onclick="id_get_night(this.id)"><i class="fa fa-edit"></i></a></td><td><input type="text" class="form-control parsley-error" style="width: 70px;" value="" name="chargespernight[]" id="chargespernight'+rrcounter+'"  readonly /></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>';


			  $('table.order-list1').append(list_fieldHTML); //Add field html  id_get_indax
				/*<input type="text" class="form-control parsley-error" name="roomtype'+rrcounter+'" id="roomtype'+rrcounter+'" data-parsley-required style="width: 110px;">*/
				

				document.getElementById("rrcounter").value = rrcounter;
				
				 var roomtype = document.getElementById('roomtype').value; 
				 var noofRooms = document.getElementById('noofRooms').value;
				  var id_hotel = document.getElementById('id_mst_hotels').value;
	var res_checkinDate = document.getElementById("res_checkinDate").value;
	var res_checkOutDate = document.getElementById("res_checkOutDate").value; 
				  $('#roomtype'+rrcounter).val(roomtype);
				  $('#roomtitle').html(roomtype);
					$('#roomselect').val("0");
				    var id_room = document.getElementById('res_room').value;
					  $.ajax({
						url: "ajax/id_room_load.php",
						  type: 'POST',
						data: { id_room : id_room,id_hotel:id_hotel, res_checkinDate:res_checkinDate,res_checkOutDate:res_checkOutDate},
						dataType: "JSON",
						success: function(data) {
							// alert(data);
						  $('#roombutton').html(data);
						}
					  });
				 
				document.getElementById("rrcounter").value = rrcounter;
				 var roomtype = document.getElementById('roomtype').value; 
				  $('#roomtype'+rrcounter).val(roomtype);
				  var id_room = document.getElementById('res_room').value;
				 // alert(id_room);
				  var id_hotel = document.getElementById('id_mst_hotels').value;
				  
					  $.ajax({
						url: "ajax/id_hotel_load.php",
						  type: 'POST',
						data: { id_hotel : id_hotel,id_room : id_room  },
						dataType: "JSON",
						success: function(data) {
					   //alert(data);
						  $('#roomtype'+rrcounter).html(data);
						}
					  });
					  
					  
				 document.getElementById("rrcounter").value = rrcounter;
				 var roomtype = document.getElementById('roomtype').value; 
				  $('#roomtype'+rrcounter).val(roomtype);

				  var id_room = document.getElementById('res_room').value;
				/// alert(id_room);
				  var id_hotel = document.getElementById('id_mst_hotels').value;
				  
					  $.ajax({
						url: "ajax/id_plan_load.php",
						  type: 'POST',
						data: { id_hotel : id_hotel,id_room : id_room,dailybreakup:0  },
						dataType: "JSON",
						success: function(data) {
					   //alert(data);
						  $('#plan'+rrcounter).html(data);
						}
					  });
					  
        }
	});
	  
	  

        $("table.order-list1").on("click", ".roomsRates_remove", function (event) {
          $(this).closest("tr").remove();       
          rrcounter = rrcounter-1; 
          document.getElementById("rrcounter").value = rrcounter;
            if(rrcounter == 0){
               $('#roomhideandshow').hide();
             }
        });
		
        //Addons Table Section Here        
        $("#addons_row").on("click", function () {
          var list_maxField = 10; 
          addonscounter++;
          $('#addonshideandshow').show();
          if(addonscounter < list_maxField){


            var list_fieldHTML = '<tr><td><select class="form-control parsley-error" style="width: 120px;" name="item[]" id="item'+addonscounter+'"><option selected="selected" value="">Select Item</option><option value="Item1">Item1</option><option class="Item2">Item2</option></select></td><td><input type="text" class="form-control parsley-error" style="width:140px;" name="additionalcharges[]" value="" id="additionalcharges'+addonscounter+'" /></td><td><input type="text" class="form-control parsley-error" style="width:100px;" name="qty[]" id="qty'+addonscounter+'" /></td><td><select class="form-control parsley-error" style="width: 120px;" name="unit[]" id="unit'+addonscounter+'"><option selected="selected" value="">Select Unit</option><option value="Day">Day</option><option class="Nos">Nos</option></select></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="rate[]" id="rate'+addonscounter+'" /></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="tax[]" id="tax'+addonscounter+'" /></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="taxvalue[]" id="taxvalue'+addonscounter+'" /></td><td><input type="text" class="form-control parsley-error" style="width:110px;" name="amount[]" id="amount'+addonscounter+'" value="" /></td><td><button class="btn btn-danger addons_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>'; //New input field html 
             $('table.order-list2').append(list_fieldHTML); //Add field html
             document.getElementById("addonscounter").value = addonscounter;
      

          }
        });
        $("table.order-list2").on("click", ".addons_remove", function (event) {
          $(this).closest("tr").remove();       
          addonscounter = addonscounter-1; 
          document.getElementById("addonscounter").value = addonscounter;
            if(addonscounter == 0){
               $('#addonshideandshow').hide();
             }
        });

        //Payments Methods
         $("#payments_row").on("click", function () {
          var list_maxField = 10; 
          paymentscounter++;
          $('#paymentshideandshow').show();
          if(paymentscounter < list_maxField){

             var list_fieldHTML = '<tr><td><select onchange="payment_mode(this.id)" class="form-control parsley-error" style="width: 250px;" name="mode[]" id="mode'+paymentscounter+'"><option selected="selected" value="">Select Mode</option><option value="Cash">Cash</option><option value="Card">Card</option><option value="Online Transfer">Online Transfer</option><option value="Cheque">Cheque</option><option class="Gift Voucher">Gift Voucher</option></select></td><td><input type="text" class="form-control parsley-error" value="" style="width:250px;" name="details[]" id="details'+paymentscounter+'" /></td><td><input type="text" class="form-control parsley-error" style="width:250px;" name="amount[]" id="amount'+paymentscounter+'" /></td><td><button class="btn btn-danger payment_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>'; //New input field html  
             $('table.order-list3').append(list_fieldHTML); //Add field html
             document.getElementById("paymentscounter").value = paymentscounter;
          }
        });
        $("table.order-list3").on("click", ".payment_remove", function (event) {
          $(this).closest("tr").remove();       
          paymentscounter = paymentscounter-1; 
          document.getElementById("paymentscounter").value = paymentscounter;
            if(paymentscounter == 0){
               $('#paymentshideandshow').hide();
             }

        });
    });
  function payment_mode(clicked_id){
    var mode = document.getElementById(clicked_id).value;
    var matches = clicked_id.match(/(\d+)/); 
    if(mode == 'Cash'){var val = "Cash Details";}if(mode == 'Card'){var val = "Card Number";}if(mode == 'Online Transfer'){var val = "Reference number";}if(mode == 'Cheque'){var val = "Cheque Details";}if(mode == 'Gift Voucher'){var val = "Gift Voucher Details";}
      $('#details'+matches[0]).val(val);
  }
   /* function roomsRates(){
	  var x = 0;
       //Initial field counter
      
       //Input fields increment limitation
      //Check maximum number of input fields
      if(x < list_maxField){ 
	  $('#roomhideandshow').show();

          x++; //Increment field counter
	    /* $.ajax({
		   url: 'ajax/room.php',
		   success: function(html) {
			  $("#roomsRates").append(html);
		   }
		});
          var list_fieldHTML = '<tr><td><select class="form-control parsley-error" name="roomtype" id="roomtype" data-parsley-required style="width: 120px;"><option selected="selected" value="">Room type</option><option value="Cottage Villa">Cottage Villa</option><option class="Waitlist Room">Waitlist Room</option></select></td><td><select class="form-control parsley-error" name="plan" id="plan" data-parsley-required style="width: 100px;"><option selected="selected" value="">Plan</option><option value="Plan1">Plan1</option><option class="Plan2">Plan2</option></select></td><td class="form-group"><input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms" id="noofRooms" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson" id="adultperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson" id="childperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild" id="extrachild" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson" id="tariffperperson" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes" id="taxes" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight" id="chargespernight" data-parsley-required/></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>'; //New input field html 
        <!--  var list_fieldHTML = '<tr><td><select class="form-control parsley-error" name="roomtype['+x+']" id="roomtype" data-parsley-required style="width: 120px;"><option selected="selected" value="">Room type</option><option value="Cottage Villa">Cottage Villa</option><option class="Waitlist Room">Waitlist Room</option></select></td><td><select class="form-control parsley-error" name="plan['+x+']" id="plan" data-parsley-required style="width: 100px;"><option selected="selected" value="">Plan</option><option value="Plan1">Plan1</option><option class="Plan2">Plan2</option></select></td><td class="form-group"><input type="text" class="form-control parsley-error" style="width: 100px;" name="noofRooms['+x+']" id="noofRooms" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="adultperperson['+x+']" id="adultperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="childperperson['+x+']" id="childperperson" data-parsley-type="digits" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 120px;" name="extrachild['+x+']" id="extrachild" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 100px;" name="tariffperperson['+x+']" id="tariffperperson" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="taxes['+x+']" id="taxes" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width: 80px;" name="chargespernight['+x+']" id="chargespernight" data-parsley-required/></td><td><button class="btn btn-danger roomsRates_remove" type="button"><i class="fa fa-trash"></i></button></td></td></tr>'; //New input field html -->
          $('#roomsRates').append(list_fieldHTML); //Add field html
		  
      }

      //Once remove button is clicked
      $('#roomsRates').on('click', '.roomsRates_remove', function()
      {
         $(this).closest('tr').remove(); //Remove field html
         x--; //Decrement field counter
		 
		 if(x == 0){
			// $('#roomhideandshow').hide();
		 }
      });
	  
    } */

	
	
	/*
	// payment function
    function payment(){
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
		});                 

        var list_fieldHTML = '<tr><td><select class="form-control parsley-error" style="width: 250px;" name="mode" id="mode"data-parsley-required><option selected="selected" value="">Select Mode</option><option value="Cash">Cash</option><option class="Card">Card</option><option value="Online Transfer">Online Transfer</option><option value="Cheque">Cheque</option><option class="Gift Voucher">Gift Voucher</option></select></td><td><input type="text" class="form-control parsley-error" value="" style="width:250px;" name="details" id="details" data-parsley-required/></td><td><input type="text" class="form-control parsley-error" style="width:250px;" name="amount" id="amount" data-parsley-required/></td><td><button class="btn btn-danger payment_remove" type="button"><i class="fa fa-trash"></i></button></td></tr>'; //New input field html 
         $('#payment').append(list_fieldHTML); //Add field html
    
	}

      //Once remove button is clicked
      $('#payment').on('click', '.payment_remove', function()
      {
         $(this).closest('tr').remove(); //Remove field html
         x--; //Decrement field counter
      });
	  
    }
*/ 
</script>

<script type="text/javascript">
	//const curr_user = Math.random().toString(32).substring(2,10)+Math.random().toString(32).substring(2,30);
	//console.log(curr_user);
	
	$(document).ready(function(){
		/*var websocket = new WebSocket("ws://localhost:8090"); 
		
		websocket.onopen = function(event) { 
			//console.log("Connection is established!");		
		}
		
		websocket.onmessage = function(event) {
			var Data = JSON.parse(event.data);
			//console.log(Data);
			//alert(Data.chat_user);
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
		}; */
		
		$("#reservationDetailss").submit(function(e){
      	  e.preventDefault();
        	var formData = $("#reservationDetailss").serialize();
			//alert('in');
        	$.ajax({
        		type: "POST",
        	    url: 'ajax/ajaxOneWindow.php',
        	    data: formData,
        	    success: function(data){
					
				alert(data);
				 $("#tab_3,#tab_1,#tab_4,#tab_5,#tab_6,#tab_2,.nav-tabs li").removeClass("active");
                 $("#tab_9,#list_view_tab").addClass("active");
				 list_ajax();
					/*var messageJSON = {
						chat_user: curr_user,
						chat_message: 'new event added'
					};
					websocket.send(JSON.stringify(messageJSON));*/
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
	
function normalImg(x) {
	//alert();
  $('#removepopup').remove(); 
  $("body").removeClass("tooltipevent");
}	
	
</script>

<script>
  function reload(){
	window.location.reload();
	}
	
$('#tabs1').click(function(){ 

    var val = $(".fc-button-active").text();
  // alert(val);

   $("#tab_8,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
   $("#tab_1").addClass("active");
   $('#roomaloc').hide();

  var id_mst_hotels = document.getElementById("id_mst_hotels").value; 
  $('.fc-scroller-canvas').removeAttr("style");
  if(id_mst_hotels >=1) {
    $('col').removeAttr("style"); 
  }
  $('colgroup').removeAttr("style");
    if(val == "15 Days"){
      $('.fc-scroller-canvas').addClass("fc-scroller-canvas"); 
    } 
    if(val == "Month"){
      $('.fc-scroller-canvas').addClass("fc-scroller-canvasmonth"); 
    }
    if(val == "Week"){
      $('.fc-scroller-canvas').addClass("fc-scroller-canvasweek"); 
    }    
});

$('#tabs3').click(function(){$('#id_mst_hotels').val('0');});
$('#tabs4').click(function(){$('#id_mst_hotels').val('0');});
$('#tabs5').click(function(){$('#id_mst_hotels').val('0');});
$('#tabs6').click(function(){$('#id_mst_hotels').val('0');});
$('#tabs7').click(function(){$('#id_mst_hotels').val('0');});
$('#tabs8').click(function(){$('#id_mst_hotels').val('0');});

 

$(".fc-timeline-event").hover(function(){
    $(".fc-timeline-event").css("background-color", "yellow");
});


function tooltippopup() {
	/*var removepopup = document.getElementById("removepopup").className;
  if('tooltipevent container-fluid' == removepopup){
    console.log('class');
  }else{
    console.log('noclass');
  }*/ 
  $(this).css('z-index',8);
  $('.tooltipevent').remove();
  $("body").removeClass("tooltipevent");
}
function booker_by(){
 var res_bookingType = document.getElementById("res_bookingType").value;
    if(res_bookingType == 1){
        $('#booker').html('Booked BY(if other Than Guest)');
        //$('#res_bookerName').append('<option value="Booked BY(if other Than Guest)" selected>Booked BY(if other Than Guest)</option>');
    }else{
      $('#booker').html('Booked BY');
       //$('#res_bookerName').append('<option value="" selected>Select Contracts</option>');
    }
}


function basevalue(){
  
  //var basevalue6 = document.getElementById("basevalue6").value;
  var intax6 = document.getElementById("intax6").value;
  var tax6 = document.getElementById("tax6").value;
  
  //var basevalue7 = document.getElementById("basevalue7").value;
  var intax7 = document.getElementById("intax7").value;
  var tax7 = document.getElementById("tax7").value;
  

  //var basevalue8 = document.getElementById("basevalue8").value;
  var tax8 = document.getElementById("tax8").value;
  var intax8 = document.getElementById("intax8").value;

  //var basevalue9 = document.getElementById("basevalue9").value;
  var tax9 = document.getElementById("tax9").value;
  var intax9 = document.getElementById("intax9").value;
 

  var basevalue6 = parseInt(intax6) - parseInt(tax6);
  var basevalue7 = parseInt(intax7) - parseInt(tax7);
  var basevalue8 = parseInt(intax8) - parseInt(tax8);
  var basevalue9 = parseInt(intax9) - parseInt(tax9);

  $('#basevalue6').val(basevalue6);
  var basevalue6 = document.getElementById("basevalue6").value;
  $('#basevalue7').val(basevalue7);
  var basevalue7 = document.getElementById("basevalue7").value;
  $('#basevalue8').val(basevalue8);
  var basevalue8 = document.getElementById("basevalue8").value;
 $('#basevalue9').val(basevalue9);
  var basevalue9 = document.getElementById("basevalue9").value;

 var total = parseInt(basevalue6) + parseInt(basevalue7) + parseInt(basevalue8) + parseInt(basevalue9);
  var tax = parseInt(tax6) + parseInt(tax7) + parseInt(tax8) + parseInt(tax9); 
  var total_tax = parseInt(intax6) + parseInt(intax7) + parseInt(intax8) + parseInt(intax9);

  
  $('#basevalue10').val(total);
  $('#tax10').val(tax);
  $('#intax10').val(total_tax);

}

function basevalue_night(){
  var basevalue1 = document.getElementById("basevalue1").value;
  var tax1 = document.getElementById("tax1").value;
  

  var basevalue2 = document.getElementById("basevalue2").value;
  var tax2 = document.getElementById("tax2").value;
  

  var basevalue3 = document.getElementById("basevalue3").value;
  var tax3 = document.getElementById("tax3").value;
  var intax3 = document.getElementById("intax3").value;

  var basevalue4 = document.getElementById("basevalue4").value;
  var tax4 = document.getElementById("tax4").value;
  var intax4 = document.getElementById("intax4").value;
 

  var intax = parseInt(basevalue1) + parseInt(tax1);
  var intax2 = parseInt(basevalue2) + parseInt(tax2);
  var intax3 = parseInt(basevalue3) + parseInt(tax3);
  var intax4 = parseInt(basevalue4) + parseInt(tax4);

  $('#intax1').val(intax);
  var intax1 = document.getElementById("intax1").value;
  $('#intax2').val(intax2);
  var intax2 = document.getElementById("intax2").value;
  $('#intax3').val(intax3);
  var intax3 = document.getElementById("intax3").value;
  $('#intax4').val(intax4);
  var intax4 = document.getElementById("intax4").value;

  var total = parseInt(basevalue1) + parseInt(basevalue2) + parseInt(basevalue3) + parseInt(basevalue4);
  var tax = parseInt(tax1) + parseInt(tax2) + parseInt(tax3) + parseInt(tax4); 
  var total_tax = parseInt(intax1) + parseInt(intax2) + parseInt(intax3) + parseInt(intax4);

  
  $('#basevalue5').val(total);
  $('#tax5').val(tax);
  $('#intax5').val(total_tax);

}

function id_get_night(clicked_id){
	//alert(clicked_id);
	  var matches = clicked_id.match(/(\d+)/);
	  //alert(matches);
	  $('#clicked_id').val(matches[0]); 
	 // alert(matches[0]);
	  var count = matches[0];
	  var res_checkinDate = document.getElementById("res_checkinDate").value;
	  var res_checkOutDate = document.getElementById("res_checkOutDate").value;
 
	//var checkinDate = res_checkinDate.split('-');
	//var checkOutDate = res_checkOutDate.split('-');
	//var DiffDays = Math.round(checkOutDate[0] - checkinDate[0]);
	 
		
		
	 
		var day = 1000*60*60*24;
	
		var dateSplit = res_checkinDate.split('-');
		var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];
		
		var dateSplit1 = res_checkOutDate.split('-');
		var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];
			
var date1 = new Date(currentDate);
var date2 = new Date(currentDate1);
var diffTime = Math.abs(date2 - date1);
var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
//console.log(diffTime + " milliseconds");
//console.log(diffDays + " days");		
		//	alert(DiffDays + " days")	
				
				
				
	if(DiffDays == 0){
		DiffDays = 1;
	}
	  
	var dd = 0;
	var res = '';
 var list_fieldHTML='';
 
 
	for(var i=0;i<DiffDays; i++) {
		
		   var xx = date1.getTime()+day*i;
		   var yy = new Date(xx);

		   var dates_new = yy.getDate()+"-"+(yy.getMonth()+1)+"-"+yy.getFullYear();
	
	   var list_fieldHTML = '<tr name="rows'+count+i+'" id="rows'+count+i+'"><td><input style="width:95px;" type="text" name="bd_date[]" id="bd_date'+count+i+'" value="'+dates_new+'" class="form-control"></td><td> <select class="form-control parsley-error" name="bd_plan[]" id="bd_plan'+count+i+'" style="width: 110px;"></select> </td><td><input value="0" type="text" name="bd_tariff[]"  id="bd_tariff'+count+i+'" class="form-control" onkeyup="bd_basevalue(this.id)"></td><td><input value="0" type="text" name="bd_food[]" id="bd_food'+count+i+'" class="form-control" onkeyup="bd_basevalue(this.id)"></td><td><input value="0" type="text" name="bd_extrabed[]" id="bd_extrabed'+count+i+'" class="form-control" onkeyup="bd_basevalue(this.id)"></td><td><input value="0" type="text" name="bd_extrachild[]" id="bd_extrachild'+count+i+'" class="form-control" onkeyup="bd_basevalue(this.id)"></td><td><input value="0" type="text" name="bd_price[]" id="bd_price'+count+i+'" class="form-control" readonly></td><td><input value="0" type="text" name="bd_tax[]" id="bd_tax'+count+i+'" class="form-control" onkeyup="bd_basevalue(this.id)"></td><td><input value="0" type="text" name="bd_intax[]" id="bd_intax'+count+i+'" class="form-control" readonly></td></tr>'; //New input field html 
	 
	var roomtype = document.getElementById("roomtype"+matches[0]).value;
	var id_hotel = document.getElementById('id_mst_hotels').value;
	
		  $.ajax({
			url: "ajax/id_plan_load.php",
			  type: 'POST',
			data: { id_hotel : id_hotel,id_room : roomtype,dailybreakup:1 },
			dataType: "JSON",
			success: function(data) {
				//alert('#bd_plan'+i);
				var countof = i;
				for(var j=0;j<countof; j++) {
					$('#bd_plan'+count+j).html(data);
				} 
			  
			}
		  }); 

	   var res = res.concat(list_fieldHTML); 
	   var kk = i;
	  }
	   
	 var rrcounter = document.getElementById("rrcounter").value;
	 var tariffperroom = document.getElementById("tariffperroom"+count).value;
	  
	if(tariffperroom > 0){		 
			for(var ii=0; ii<=kk; ii++){  
				$("#rows"+count+ii).show();				
			}
			for(var mm=1; mm<=rrcounter; mm++){
				if(mm != count){
					for(var ii=0; ii<=kk; ii++){  
						$("#rows"+mm+ii).hide();				
					}
				}
			}
		
	}else{
		if(count > 1){	
			rrcounter = parseInt(rrcounter) - 1;
			for(var jj=rrcounter; jj>0; jj--){
				for(var ii=0; ii<=kk; ii++){ 
				$("#rows"+jj+ii).hide();  
				}
			}
			$('.order-list_breakdown').html(res); 
		}else{
			$('.order-list_breakdown').html(res); 
		}
	} 
	/*var rrcounter = document.getElementById("rrcounter").value;
	if(count < rrcounter){}
	else{
		$('.order-list_breakdown').append(res); 	
	}*/
	
	//$('.order-list_breakdown1').append(res);
	$('#bd_count').val(DiffDays);
	//$('#popupval'+count).html(list_fieldHTML);
		
	var plantype = document.getElementById("plantype"+matches[0]).value;
	if(plantype==1){
		$('#breakdownModal').modal('show');
	}
$('.main-footer').css('display','none');  
}






//BD Value Calc
function bd_basevalue(clicked_id){
  var matches = clicked_id.match(/(\d+)/);
  var count  = matches[0];
  //alert(count);
  var bd_tariff = document.getElementById("bd_tariff"+count).value; 
  var bd_food = document.getElementById("bd_food"+count).value;
  var bd_extrabed = document.getElementById("bd_extrabed"+count).value;
  var bd_extrachild = document.getElementById("bd_extrachild"+count).value;
  
  //Price Total
  var price = parseInt(bd_tariff) + parseInt(bd_food) + parseInt(bd_extrabed) + parseInt(bd_extrachild);
  $('#bd_price'+count).val(price);

  var bd_price = document.getElementById("bd_price"+count).value;
  var bd_tax = document.getElementById("bd_tax"+count).value;

  //In Tax Total
  var in_tax = parseInt(bd_tax) + parseInt(bd_price);
  $('#bd_intax'+count).val(in_tax);
}







function bd_applyall(){
	
	var start= document.getElementById("res_checkinDate").value;//$("#res_checkinDate").datepicker("getDate");
    var end= document.getElementById("res_checkOutDate").value;//$("#res_checkOutDate").datepicker("getDate");
   // days = (end - start) / (1000 * 60 * 60 * 24);
	
	
	
		var dateSplit = start.split('-');
		var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];
		
		var dateSplit1 = end.split('-');
		var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];
			
var date1 = new Date(currentDate);
var date2 = new Date(currentDate1);
var diffTime = Math.abs(date2 - date1);
var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
var days = DiffDays;

	
var DiffDays = Math.round(days);
  if(DiffDays == 0){
    DiffDays = 1;
}

var rrcounter = document.getElementById("rrcounter").value;
var bd_count = document.getElementById("bd_count").value;
  
  //First Value Get
  
  var bd_plan = document.getElementById("bd_plan"+rrcounter+"0").value; 
  var bd_tariff = document.getElementById("bd_tariff"+rrcounter+"0").value; 
  var bd_food = document.getElementById("bd_food"+rrcounter+"0").value;
  var bd_extrabed = document.getElementById("bd_extrabed"+rrcounter+"0").value;
  var bd_extrachild = document.getElementById("bd_extrachild"+rrcounter+"0").value;
  var bd_price = document.getElementById("bd_price"+rrcounter+"0").value;
  var bd_tax = document.getElementById("bd_tax"+rrcounter+"0").value;
  var bd_intax = document.getElementById("bd_intax"+rrcounter+"0").value;
 
  
  //'<?php  $selectneww = "SELECT * FROM fo_rate_plan  where id =8 "; $db->query($selectneww);  $rowneww = $db->fetch_object(); $namee = $rowneww->name;	 ?>';

 // '<?php echo $selectneww = "SELECT * FROM fo_rate_plan  where id ='+ bd_plan +' "; $db->query($selectneww);  $rowneww = $db->fetch_object(); $namee = $rowneww->name;	 ?>';
 
 
 var roomtype = document.getElementById("roomtype"+rrcounter).value;
  var id_hotel = document.getElementById('id_mst_hotels').value;
 
	  $.ajax({
		url: "ajax/id_plan_load.php",
		  type: 'POST',
		data: { id_hotel : id_hotel, id_room : roomtype,dailybreakup:2,select:bd_plan },
		dataType: "JSON",
		success: function(data) {
			//alert('#bd_plan'+i);
			//var countof = i;
			for(var j=1;j<=bd_count; j++) {
				$('#bd_plan'+rrcounter+j).html(data);
			} 
		  
		}
	  });
	  
  
  for(var i = 1; i<=bd_count; i++){
    //$('#bd_plan'+i).append("<option value="+bd_plan+" selected>"+name+"</option>");
    $('#bd_tariff'+rrcounter+i).val(bd_tariff);
    $('#bd_food'+rrcounter+i).val(bd_food);
    $('#bd_extrabed'+rrcounter+i).val(bd_extrabed);
    $('#bd_extrachild'+rrcounter+i).val(bd_extrachild);
    $('#bd_price'+rrcounter+i).val(bd_price);
    $('#bd_tax'+rrcounter+i).val(bd_tax);
    $('#bd_intax'+rrcounter+i).val(bd_intax);
  }

}

var no=1;
function bd_apply(){  //bd_price1

var res_checkinDate = document.getElementById("res_checkinDate").value;
var res_checkOutDate = document.getElementById("res_checkOutDate").value;
  
var checkinDate = res_checkinDate.split('-');
var checkOutDate = res_checkOutDate.split('-');
    
 //var DiffDays = Math.round(checkOutDate[0] - checkinDate[0]);
 
		var dateSplit = res_checkinDate.split('-');
		var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];
		
		var dateSplit1 = res_checkOutDate.split('-');
		var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];
			
var date1 = new Date(currentDate);
var date2 = new Date(currentDate1);
var diffTime = Math.abs(date2 - date1);
var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)); 
var days = DiffDays;
 
/* var start= document.getElementById("res_checkinDate").value;//$("#res_checkinDate").datepicker("getDate");
    var end= document.getElementById("res_checkOutDate").value;//$("#res_checkOutDate").datepicker("getDate");
    days = (end - start) / (1000 * 60 * 60 * 24);*/
	
    //var DiffDays = Math.round(days);
	
  if(DiffDays == 0){
    DiffDays = 1;
  }
  
var k=0;
var t=0;
var t1=0;
var t2=0;


 
 var countval = document.getElementById("rrcounter").value;
  var foodval = document.getElementById("foodval").value;
  //var status = 1;
for(var kk =1; kk <=countval; kk++){ 
	for(var i =0; i <DiffDays; i++){
		//alert("bd_price"+countval+i);
		var bd_price = document.getElementById("bd_price"+kk+i).value;
		var bd_tax = document.getElementById("bd_tax"+kk+i).value;
		var bd_intax = document.getElementById("bd_intax"+kk+i).value;
		
		//Food Value
		//var val=document.getElementById("bd_food"+countval+i).value;
		//alert(foodval);
		//var test = "bd_food"+countval + i;
		//alert(val2);
		
		//foodval = foodval + ',' + val;
		
	//	alert(bd_price);
		  k += parseFloat(bd_price);
		  t += parseFloat(bd_tax);
		  t1 += parseFloat(bd_intax);
		  //t2 += parseFloat(bd_intax);
		  //alert(k);
	}
	 var bd_total = k;
	 var bd_tax = t;
	 var bd_intax = t1;
	//  $('#foodval').val(foodval);

	 
	var  tariff = (parseFloat(bd_total) / parseFloat(DiffDays)).toFixed(2);
	var  tax = (parseFloat(bd_tax) / parseFloat(DiffDays)).toFixed(2);
	var  tariff_tax = (parseFloat(bd_intax) / parseFloat(DiffDays)).toFixed(2);

	 
	alert(countval);
		$('#tariffperroom'+kk).val(tariff); 
		$('#taxes'+kk).val(tax);
		$('#tariffperroomtax'+kk).val(tariff_tax);
		
		
		
	k=0;
		t=0;
		t1=0;
		bd_total = 0;
		bd_tax=0;
		bd_intax=0;
}
no++;	

}

function id_get_intax(clicked_id){
  //itform,night
  
  var matches = clicked_id.match(/(\d+)/);
 
  $('#clicked_id_tax').val(matches[0]);   

  var adultperroom = document.getElementById("adultperroom"+matches[0]).value;
  var childabove = document.getElementById("childabove"+matches[0]).value;

  document.getElementById("tax8").readOnly = true;
  document.getElementById("intax8").readOnly = true;

  if(childabove == 0 || childabove == 1 || childabove == 2){
    document.getElementById("tax9").readOnly = true;
   document.getElementById("intax9").readOnly = true;
  }else{
    document.getElementById("tax9").readOnly = false;
    document.getElementById("intax9").readOnly = false;  
  }

  if(adultperroom == 1){
    $('#tariff_price_intax').html('Tariff Price (Single)');
  }else if(adultperroom == 2 ){
    $('#tariff_price_intax').html('Tariff Price (Double)');
  }else{
    $('#tariff_price_intax').html('Tariff Price');
    document.getElementById("tax8").readOnly = false;
    document.getElementById("intax8").readOnly = false;  
  }

  $('#roomratesModal').modal('show');
}

function rrtaxset(){
  var basevalue5 = document.getElementById("basevalue5").value;
  var tax5 = document.getElementById("tax5").value;
  var intax5 = document.getElementById("intax5").value;
  var clicked_id = document.getElementById("clicked_id").value;
  var adultperroom = document.getElementById("adultperroom"+clicked_id).value;
  var charge_per_night = parseInt(basevalue5) * parseInt(adultperroom);
  $('#chargespernight'+clicked_id).val(charge_per_night);
  $('#tariffperroom'+clicked_id).val(basevalue5);
  $('#taxes'+clicked_id).val(tax5);
  $('#tariffperroomtax'+clicked_id).val(intax5);
}


function btncolorchange(clicked_id){ 
  //alert(clicked_id);
  var roomselect = document.getElementById("roomselect").value;
  var idcount_color = document.getElementById("idcount_color").value;
  var noofRooms = document.getElementById("noofRooms"+idcount_color).value;
  var btn = "#"+clicked_id;
  //alert(noofRooms);
  

var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var wcheckbox = document.getElementById("btn"+match).checked;

//alert(wcheckbox);

/*
var chekedValue = [];
 $('.checkedd:checked').each(function(){
   alert(chekedValue .push($(this).val()));
 }); */
 
 
var checkValues = $('.checkedd:checked').map(function()
{
	return $(this).val();
}).get();

//alert(checkValues);

var check_count = $('.checkedd:checked').length;

if(noofRooms >= check_count){
		document.getElementById("roomno"+idcount_color).value = checkValues;
}else{
	document.getElementById("btn"+match).checked = false;
	alert('Please select only ' + noofRooms +  ' rooms');
}


/* if($(btn).hasClass("btn-success")){   
	    roomselect = parseInt(roomselect) + 1;
         $('#roomselect').val(roomselect);	
         //alert(btn);		 
		$(btn).removeClass("btn-success").addClass("btn-danger");
		
    	if(noofRooms>roomselect){
          roomselect = parseInt(roomselect) + 1;
          $('#roomselect').val(roomselect);		 
    		  $(btn).removeClass("btn-success").addClass("btn-danger");
        }
        else{
      	  alert('Please select only ' + noofRooms +  ' rooms');
      	}  
        
    } else{ 
    	  roomselect = parseInt(roomselect) - 1; 
    	  $('#roomselect').val(roomselect);
    	  $(btn).removeClass("btn-danger").addClass("btn-success");
    	  if(noofRooms>roomselect){
    		var btn = "#"+clicked_id;
    		$(btn).removeClass("btn-danger").addClass("btn-success");
    	  }
     }  */
}

function countcolorchange(clicked_id){
	
	//alert(clicked_id);
	/*  var id_room = document.getElementById('res_room').value;
		$.ajax({
			url: "ajax/id_select_load.php",
			type: 'POST',
			data: { id_room : id_room,noofRooms : clicked_id },
			dataType: "JSON",
			success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
		  }); */
			 // 
    $('#roomtypeModal').modal('show');
  var roomtype = document.getElementById('roomtype').value;
		$('#roomtype').val(roomtype);
		$('#noofRooms').val(noofRooms);
   var matches = clicked_id.match(/(\d+)/);
//$('#roomtype'+matches).click(); 
   //alert(matches[0]);
   $('#idcount_color').val(matches[0]);
   
 
   
}



function roomselectzero(){
  $('#roomselect').val(0);
}

function roomtypechange(clicked_id,clicked_value){
//alert(clicked_value);
        
    var id_hotel = document.getElementById('id_mst_hotels').value;
	var res_checkinDate = document.getElementById("res_checkinDate").value;
	var res_checkOutDate = document.getElementById("res_checkOutDate").value;	
	var regex = /[+-]?\d+(?:\.\d+)?/g;
	var match = parseInt(regex.exec(clicked_id));	
		
  // alert(match);
	
            $.ajax({
				url: "ajax/id_hotel_load.php",
				  type: 'POST',
				data: { id_hotel : id_hotel,id_room : clicked_value},
				dataType: "JSON",
				success: function(data) {
				 //alert(data);
				  $('#roomtype'+match).html(data);
				}
            });
			
  
			
			
            /*var roomtype = document.getElementById('roomtype').value; 
            $('#roomtitle').html(roomtype);*/

              $.ajax({
              url: "ajax/id_room_load.php",
                type: 'POST',
              data: { id_hotel:id_hotel, id_room : clicked_value,res_checkinDate:res_checkinDate,res_checkOutDate:res_checkOutDate},
              dataType: "JSON",
              success: function(data) {
                // alert(data);
                $('#roombutton').html(data);
              }
            });
            //Onchange Time Popup Title change
            if(clicked_value == "all"){
                 $('#roomtitle').html("All Rooms");
                 $('#room_title').remove();
				 //	$('#room_title').css('display','none');
              }else{
                  $.ajax({
                  url: "ajax/popup_title_change.php",
                    type: 'POST',
                  data: { id_room : clicked_value},
                  dataType: "JSON",
                  success: function(data) {
                   //  alert(data);
                    $('#roomtitle').html(data);
                  }
                });
              }
			  
			  
			  
$.ajax({
	url: "ajax/id_plan_load.php",
	  type: 'POST',
	data: { id_hotel : id_hotel,id_room : clicked_value,dailybreakup:0  },
	dataType: "JSON",
	success: function(data) {
   //alert(data);
	 $('#plan'+match).html(data);
	}
});	  
			  
			  
}

function roomtypechangealltype(clicked_value){
//alert(clicked_value);
        
        var id_hotel = document.getElementById('id_mst_hotels').value; 
		var id_count = document.getElementById('idcount_color').value;
		var checkbox = document.getElementById("checkbox").checked;
		
	var res_checkinDate = document.getElementById("res_checkinDate").value;
	var res_checkOutDate = document.getElementById("res_checkOutDate").value;
		///alert(roomtype);
		
		
		for(var i=0; i<=id_count; i++){
			 	var roomtype = $("#roomtype"+i).val();
		} 
		
		//alert(roomtype);
		
 		if(checkbox == true){
 			var checkbox = 1;
 		}else{
 			var checkbox = 0;
 		}
		  
  //  alert(checkbox);
	
            $.ajax({
            url: "ajax/id_hotel_load.php",
              type: 'POST',
            data: { id_hotel : id_hotel,id_room : clicked_value },
            dataType: "JSON",
            success: function(data) {
             //alert(data);
              $('#roomtype'+rrcounter).html(data);
            }
            });
            /*var roomtype = document.getElementById('roomtype').value; 
            $('#roomtitle').html(roomtype);*/
//alert(checkbox);
              $.ajax({
              url: "ajax/id_room_load.php",
                type: 'POST',
              data: { id_hotel:id_hotel, id_room : clicked_value,checkbox : checkbox,id_room_type: roomtype, res_checkinDate:res_checkinDate,res_checkOutDate:res_checkOutDate},
              dataType: "JSON",
              success: function(data) {
                // alert(data);
                $('#roombutton').html(data);
              }
            });
            //Onchange Time Popup Title change
            if(clicked_value == "checkbox"){
                 $('#roomtitle').html("All Rooms");
				 //	$('#room_title').css('display','none');
              }else{
                  $.ajax({
                  url: "ajax/popup_title_change.php",
                    type: 'POST',
                  data: { id_room : clicked_value},
                  dataType: "JSON",
                  success: function(data) {
                   //  alert(data);
                    $('#roomtitle').html(data);
                  }
                });
              }
}

function selectall(){
  var roomselectall = document.getElementById('roomselectall').value; 
  if(roomselectall == '0'){
    $('.change_color').removeClass("btn-success").addClass("btn-danger");
    $('#roomselectall').val('1');
  }else{
    $('#roomselectall').val('0');
    $('.change_color').removeClass("btn-danger").addClass("btn-success");
     
  }
}

function rrintaxset(){
  var clicked_id_tax = document.getElementById('clicked_id_tax').value; 
  var intax10 = document.getElementById('intax10').value; 
  var tax10 = document.getElementById('tax10').value; 
  var basevalue10 = document.getElementById('basevalue10').value; 
  $('#tariffperroomtax'+clicked_id_tax).val(intax10);
  $('#taxes'+clicked_id_tax).val(tax10);
  $('#tariffperroom'+clicked_id_tax).val(basevalue10);
}


$(document).ready(function(){
	//alert('65');
	comCheck = () =>{
		window.location.href='https://localhost/application/dashboard.php';
	}
    $('.guestName').select2({
	
        placeholder: 'Select Guest',
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

    //Business Source Data
	$('.bs_select2').select2({ 
        placeholder: 'Select Business Source',
		ajax: {
		  url: "ajax/ajax_business_source.php",
		  dataType: 'json',
		  delay: 50,
		    processResults: function (data) {         
			 console.log(data[0].id);
			 //data1 = JSON.parse(data);
			 
			   if(data[0].id){
				return { results: data};
			   }
			   else{
				//comCheck(); 
				return { results: data};
			   }
		    },
		   cache: true
		}//ajax end //
    });

  $('.res_select2').select2({
    
    ajax: {
      url: "ajax/source_label_change_autoload.php", 
      data: function (params) {
      var query = {
        searchValue: params.term,
        id:document.getElementById('id_mst_attributes_company_group').value,
        type: 'public'
      }

      // Query parameters will be ?search=[term]&type=public
      return query;
      },
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
        //comCheck(); 
        return { results: data};
         }
        },
       cache: true
    }//ajax end
    });

    $('.bookername_select2').select2({
      
    ajax: {
      url: "ajax/ajaxSearchContactName.php", 
      data: function (params) {
      var query = {
        search: params.term,
        id:document.getElementById('res_source').value,
        type: 'public'
      }

      // Query parameters will be ?search=[term]&type=public
      return query;
      },
      dataType: 'json',
      delay: 50,
        processResults: function (data) {         
       //console.log(data[0].id);
       //data1 = JSON.parse(data);
       //alert(data1);
         if(data[0].id){
        return { results: data};
         }
         else{
        //comCheck(); 
        return { results: data};
         }
        },
       cache: true
    }//ajax end
    });
 
}); 
  

function source_labelchange(){
	
  var id_mst_attributes_company_group = document.getElementById('id_mst_attributes_company_group').value; 
  
 
	  $.ajax({
		  url: "ajax/source_label_change.php",
			type: 'POST',
		  data: { id : id_mst_attributes_company_group},
		  dataType: "JSON",
		  success: function(data) { 
			$('#sourcename1').html(data);  
      //$('#res_source').html('<option>Select '+data+' name</option>');  
		  }
	});
	$.ajax({
		  url: "ajax/source_label_change_autoload.php",
			type: 'POST',
		  data: { id_mst_attributes_company_group : id_mst_attributes_company_group},
		  dataType: "JSON",
		  success: function(data) {                
			$('#res_source').html(data); 
		  }
	});     
}



function source_data(){
    var id_mst_attributes_company_group = document.getElementById('id_mst_attributes_company_group').value; 
    var res_source = document.getElementById('res_source').value; 
	if (res_source !=="") {
		$.ajax({
			url: "ajax/ajaxSearchCompanyName.php",
			type: 'POST',
			cache:false,
			data: { id : id_mst_attributes_company_group,autoload:res_source},
			  
			success: function(data) {
				//console.log(data);
				$("#resource_list").html(data);
				$("#resource_list").fadeIn();
			}
		});  
	}else{
	  $("#resource_list").html("");  
	  $("#resource_list").fadeOut();
	}
}
$(document).on("click","li", function(){ 
  $('#res_source').val($(this).text());
  $('#resource_list').fadeOut("fast");
});

function guestaddedit(){
   var search_name = $('.guestName :selected').val();
  //alert(search_name);
  
		$.ajax({
		  url: "ajax/ajaxGuestEditSearch.php",
		  type: 'POST',
		  cache:false,
		  data: { id : search_name},

		  dataType: "JSON",
			
		  success: function(data) {
			//console.log(data);
			$("#first_name").val(data['first_name']);
			$("#last_name").val(data['last_name']);
			$("#email").val(data['email']);
			$("#mobile").val(data['primary_mobile']);
			$("#city").val(data['city']);
			$("#EditCustomerID").val(data['id']);
			$('#guestaddeditModal').modal('show');
		  }
		});


}

function bookeredit(){
   var search_name = $('.bookername :selected').val();
   //alert(search_name);  
   
  $.ajax({
      url: "ajax/ajaxBookerEditSearch.php",
      type: 'POST',
      cache:false,
      data: { id : search_name},
      dataType: "JSON",
        
      success: function(data) {
        //console.log(data);
        $("#booker_first_name").val(data['first_name']);
        $("#booker_last_name").val(data['last_name']);
        $("#booker_email").val(data['email']);
        $("#booker_mobile").val(data['primary_mobile']);
        $("#booker_city").val(data['city']);
        $("#booker_postcode").val(data['postcode']);
        $('#bookereditModal').modal('show');
      }
    }); 
	
	 $(document).ready(function(){ alert('1');
		// var search_name = $('.bookername :selected').val();
		$("#bookerpopupform").submit(function(){alert('55');
			$.ajax({
				url: "ajax/ajaxSaveBookerEdit.php", 
				data: $("#bookerpopupform").serialize() + "&id=" + search_name, 
				type: "POST", 
				dataType: 'JSON',
				success: function (e) {
              	$("#bookereditModal").modal("hide");
				console.log(JSON.stringify(e));
				},
				error:function(e){
					console.log(JSON.stringify(e));
				}
			}); 
			return false;
		});
	}); 
}



function breakdown(){
        $('#roomratesModal_night').modal('hide');	
        $('#breakdownModal').modal('show');	
		$('.main-footer').css('display','none');	
}


function getval(clicked_id)
{
	var matches = clicked_id.match(/(\d+)/);
 
  $('#clicked_id').val(matches[0]);  
  var plan = document.getElementById("plan"+matches[0]).value;
  
  var tariffperroom = document.getElementById("tariffperroom"+matches[0]).value;
  
 
  if(plan == 1) { 
    document.getElementById("tariffperroom"+matches[0]).readOnly = true;
	document.getElementById("tariffperroomtax"+matches[0]).readOnly = true;
	 jQuery('#night'+matches[0]).show();
	 jQuery('#itform'+matches[0]).show();
    $("#tariffperroom"+matches[0]).css("width", "70px");
    $("#tariffperroomtax"+matches[0]).css("width", "70px");
	document.getElementById("plantype"+matches[0]).value = 1;
  }else{
	  
	 
	//alert(res_source);
    $.ajax({
		url: "ajax/ajaxCheckPlanType.php",
		type: 'POST',
		data: { id : plan},
		dataType: "JSON",
		success: function(data) {
			var planInclusive =data;// alert(data);
			//$('#res_bookerName').html(data); 
			 if(planInclusive=='1'){  //1 For Inclusive Plan
		  document.getElementById("tariffperroom"+matches[0]).readOnly = true;
	 document.getElementById("tariffperroomtax"+matches[0]).readOnly = false;
	 jQuery('#night'+matches[0]).hide();
	 jQuery('#itform'+matches[0]).hide();
    $("#tariffperroom"+matches[0]).css("width", "90px");
    $("#tariffperroomtax"+matches[0]).css("width", "90px");
	document.getElementById("plantype"+matches[0]).value = 0;
	id_get_night('#night'+matches[0]);
		  }else{
			   document.getElementById("tariffperroom"+matches[0]).readOnly = false;
	 document.getElementById("tariffperroomtax"+matches[0]).readOnly = true;
	 jQuery('#night'+matches[0]).hide();
	 jQuery('#itform'+matches[0]).hide();
    $("#tariffperroom"+matches[0]).css("width", "90px");
    $("#tariffperroomtax"+matches[0]).css("width", "90px");
	document.getElementById("plantype"+matches[0]).value = 0;
	id_get_night('#night'+matches[0]);
			  }
		}
    });
	  
	 
	  
	
  }
  
}



 $(document).ready(function () {
	 
	$.ajax({
		url: "ajax/ajaxHouseKeeping.php",
		  type: 'POST',
		data: { id_room : id_room},
		dataType: "JSON",
		success: function(data) {
			// alert(data);
		  $('#roombutton').html(data);
		}
	  }); 
	 
 });



function saveHouseKeeping(clicked){
	//alert(clicked);
	 
   var search_name = clicked;
   //alert(search_name);  
   
  $.ajax({
      url: "ajax/ajaxHouseKeeping.php",
      type: 'POST',
      cache:false,
      data: { id : search_name},
      dataType: "JSON",
        
      success: function(data) {
        //console.log(data); 
         $("#id").val(data['id']);
         $("#room_type").val(data['id_mst_room_type']);
        $("#room_no").val(data['roomno']);
        $("#block_floor").val(data['block_floor']);
        $("#room_status").val(data['room_status']);
        $("#activity").val(data['activity']);
        $("#last_cleaned").val(data['last_cleaned']);
        $("#last_cleaned_time").html(data['last_cleaned_time']);
        $("#executive").val(data['executive']);
        $("#remarks").val(data['remarks']);
        $("#houseeditModal").modal("show");
        
		
      }
    }); 
	

		$(document).ready(function(){
			
		var search_name = clicked;
		//alert(search_name);
		$("#housepopupform").submit(function(){
			var id   = $("#id").val(); 
			$.ajax({
				url: "ajax/ajaxSaveHouseKeeping.php", 
				data:  $("#housepopupform").serialize(),
				type: "POST", 
				dataType: 'JSON',
				success: function (data) {
			    $("#houseeditModal").modal("hide");
				//window.location.reload();
				//window.location.href += "#tab_7";
				//console.log(JSON.stringify(e));
				housekeep_ajax();
				},
				
			}); 
			return false;
		});
	});

}

function audittrial(clicked){
		
		$('#auditModal').modal('show');
		var table ='fo_house_keeping';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#auditbutton').html(data);
			}
	   });
	}
	
function active_cart(){
	//removeClass('active_cart')
	$(".nav-tabs li").removeClass("active");
	$(".active_cart").addClass("active");
	$('#roomaloc').show();
}

//House Keeping  Onloadfunction audittrial(clicked){
		
	function housekeep_ajax(){
		  
		$.ajax({
			url: "ajax/ajaxHouseKeeponload.php",
			  type: 'POST',
				data: {},
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#housekeep_ajax').html(data);
			}
	   });
	}
	
  function list_ajax(){
     $("#BookingList").DataTable().destroy();
	 $('#BookingList').DataTable({
        ajax: 'ajax/ajaxListOnLoad.php',
        columns: [
            { data: 'name' },
            { data: 'booking_no' },
            { data: 'hotel' },
            { data: 'booking_date' },
            { data: 'checkin' },
			{ data: 'checkout' },
            { 
        "data": "id",
        "render": (data, type, row, meta) => `<a href="#" onclick="reservationDetails(${data});"><img height="20px" src="../images/view2.png" style="cursor:pointer;" title="View  "></a>`
      },
        ],
    });
	/* 
    $.ajax({
      url: "ajax/ajaxListOnLoad.php",
        type: 'POST',
        data: {},
        dataType: "JSON",
        success: function(data) {
        // alert(data);
       // $('#list_ajax').html(data);
	    var tableData = "";
        var firstCol = "";
		 $("#booking_list").DataTable().destroy();
		$("#booking_list").DataTable();
                $("#booking_list tbody").html(data);
                initializeTableArrivals("#booking_list", [0,1,2],true);
      }
     });*/
  }
  


	function unassigned_popup(){
		$('#unallocModal').modal('show');
        $("#roomunallocModal").modal("hide");
	}
     
	 function bd_applyy(){
	   $("#tab_8,#tab_3,#tab_4,#tab_5,#tab_6,.nav-tabs li").removeClass("active");
	   $("#tab_1").addClass("active");
	   $('#roomaloc').hide();
	}

  function hotelnameget(id_hotel){
    var id_doc_type_configuration = document.getElementById('id_doc_type_configuration').value;
    
  $.ajax({
        url: "ajax/ajaxhotelname_get.php",
        type: 'POST',
        data: { id : id_hotel, id_doc_type_configuration:id_doc_type_configuration },
        dataType: "JSON",
        success: function(data) {
         $('#res_bookingNo').val(data);
        
      }
     });
     
}
 function companySelect(id_mst_company){
  $.ajax({
		  url: "ajax/ajaxGetCompany.php",
			type: 'POST',
		  data: { id_mst_company : id_mst_company},
		  
		  success: function(data) { 
		   $("#res_source").empty();			                 
			$('#res_source').html(data); 
		  }
	});
}
function guestSelect(id_mst_guest){
  $.ajax({
		  url: "ajax/ajaxSearchGuestNameLoad.php",
			type: 'POST',
		  data: { id_mst_guest : id_mst_guest},
		  
		  success: function(data) { 
		   $("#id_mst_guest").empty();			                 
			$('#id_mst_guest').html(data); 
		  }
	});
}
function bookerby_labelchange2(res_source,res_bookerName){
  //$('#res_bookerName').html('<option>Select Booker Name</option>');  
   
//alert(res_source);
    $.ajax({
		url: "ajax/ajaxSearchContactNameLoad.php",
		type: 'POST',
		data: { id_mst_company : res_source,'res_bookerName':res_bookerName},
		
		success: function(data) {
			  //alert(data);
			   $("#res_bookerName").empty();
			$('#res_bookerName').html(data); 
		}
    });   
} 
	function InvoiceDetails(id){
  //$('#res_bookerName').html('<option>Select Booker Name</option>');  
   $("#tab_3,#tab_1,#tab_2,#tab_4,#tab_5,.nav-tabs li").removeClass("active");
            $("#tab_6,#folio_tab").addClass("active");
//alert(id);
    $.ajax({
		
		type: "GET",
   url: 'ajax/ajaxInvoiceDetails.php',
   data: 'id='+id, 
		
		success: function(data) {
			  //alert(data);
			  
			$('#ShowInvoiceDetails').html(data); 
		}
    });   
}

//05-10-2022=======================

function tariffCalculation(id,value){
	
	//alert(id+' - '+value);
	var matches = id.match(/(\d+)/);
	//alert(matches);
	//alert('matches='+matches);
		// alert('Test-');
		//var roomtype=[];
		var rrcounter = document.getElementById("rrcounter").value;
		//alert(rrcounter);
		var items=[];
		for ( var x = 1; x <= rrcounter; x++ ) {
			var item = {
			  roomtype: $("#roomtype"+x).val(),
			  plan: $("#plan"+x).val(),
			  noofRooms: $("#noofRooms"+x).val(),
			  adultperroom: $("#adultperroom"+x).val(),
			  childbelow: $("#childbelow"+x).val(),
			  childabove1: $("#childabove1"+x).val(),
			  tariffperroom: $("#tariffperroom"+x).val(),
			  taxes: $("#taxes"+x).val(),
			  tariffperroomtax: $("#tariffperroomtax"+x).val(),
			  chargespernight: $("#chargespernight"+x).val()
	
		
			};
		
			items.push(item);
		}
		
	RoomArray	 = JSON.stringify(items);
	var id_hotel = $("#id_mst_hotels").val();
	var res_checkinDate = document.getElementById("res_checkinDate").value;
	var res_checkOutDate = document.getElementById("res_checkOutDate").value;
	var reservation_date = res_checkinDate+' to '+res_checkOutDate;
	var subtotal= $("#subtotal").val();
	var total_discount= $("#total_discount").val();
	var balance= $("#balance").val();
	var additional_charges= $("#additional_charges").val();
	var total= $("#total").val();
	var total_taxes= $("#total_taxes").val();
	var payment_received= $("#payment_received").val();
			//alert('LOad');
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxTariffCalculation.php',
		   data: {"reservation_date" : reservation_date,"rrcounter" : rrcounter,"id_hotel" : id_hotel,"subtotal" : subtotal,"total_discount" : total_discount,"balance" : balance,"additional_charges" : additional_charges,"total" : total,"total_taxes" : total_taxes,"payment_received" : payment_received, "RoomArray" :RoomArray},
		   success: function (result) {				
		   
					
					result = JSON.parse(result);
					$("#subtotal").val(result.subtotal);
					$("#total_taxes").val(result.total_taxes);
					$("#total").val(result.total);
					$("#payment_received").val(result.payment_received);
					$("#balance").val(result.balance);
					$("#additional_charges").val(result.additional_charges);
					var x=1;
					for(var y=0;y<=result.length;y++){
					$("#tariffperroom"+x).val(result.Data[y].tariffperroom);
					$("#taxes"+x).val(result.Data[y].taxes);
					$("#tariffperroomtax"+x).val(result.Data[y].tariffperroomtax);
					$("#chargespernight"+x).val(result.Data[y].chargespernight);
					
				x++;
				}
				
					
								
			}
		})
		 
  
		 
	var start= document.getElementById("res_checkinDate").value;//$("#res_checkinDate").datepicker("getDate");
	var end= document.getElementById("res_checkOutDate").value;//$("#res_checkOutDate").datepicker("getDate");
	
	    var dateSplit = start.split('-');
        var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];
        
        var dateSplit1 = start.split('-');
        var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];
        
        var date12 = new Date(currentDate);
        var date22 = new Date(currentDate1);
        var diffTime = Math.abs(date22 - date12);
        var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
	
	days = DiffDays;
		
	var DiffDays = Math.round(days);
	 
	var day = 1000*60*60*24;
		date1 = start;
		date2 = end;

	if(DiffDays == 0){
		DiffDays = 1;
	  }
	  
	  var plantype = document.getElementById("plantype"+matches[0]).value;
		if(plantype==0){
			for(var i=0;i<DiffDays; i++) {
			   $('#bd_tariff'+i).val(value);
			}
		}
}

function checkinDetails(){
	alert('2');
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
                                    <td onclick="reservationDetails('${value.id}')"><a href="#" style="color:black;">${(value.booking_no)}</a></td>
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
                        $("#expected_arrivals")
                    .DataTable()
                    .destroy();
                $("#expected_arrivals tbody").html(tableData);
                initializeTableArrivals("#expected_arrivals", [1, 3, 4, 5]);
                       /* $("#expected_arrivals").DataTable().destroy();
                        $("#expected_arrivals tbody").html(tableData);
                        initializeTableArrivals('#expected_arrivals',[0,1,3,5,6,7,8],true);*/
						 
                    }
                });
	}
	
	function changeFolioStatus(status,id_fo_folio,id_reservation){
		
		bootbox.confirm({
    title: "Folio Status",
    message: "Do you want to Close this Folio?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateFolioStatus.php',
			   data: 'status='+status+'&id_fo_folio='+id_fo_folio+'&id_reservation='+id_reservation, 
			   success: function (result) {
				   if(result==0){
					   $("#showGenerateButton").hide();
					   
					   }else{
						   $("#showGenerateButton").show();
						   }
				   //alert(result);
				   //$("#CompanyGroupDetails").html('Business Group: '+ result);
				// $( ".my_popup_open" ).click();	
				// $("#TaxUpdateData").html(result);  
				 
				 }
				})
		}
		}
		})
		}
	function ajaxGenerateBill(id_mst_hotels,id_fo_bill,id_reservation,doc_type,id_room){
		
		//alert('ajaxGenerateBill');
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxgetDocumentNo.php',
			   data: 'id_mst_hotels='+id_mst_hotels+'&id_fo_bill='+id_fo_bill+'&id_reservation='+id_reservation+'&doc_type='+doc_type+'&id_room='+id_room, 
			   success: function (result) {
				   let datas = JSON.parse(result);
				   if(datas.status==1){
					   bootbox.alert(datas.message);
					   InvoiceDetails(datas.value);
					  // $("#showGenerateButton").hide();
					   }else{
						   bootbox.alert(datas.message);
						 //  $("#showGenerateButton").show();
						   }
				   //alert(result);
				   //$("#CompanyGroupDetails").html('Business Group: '+ result);
				// $( ".my_popup_open" ).click();	
				// $("#TaxUpdateData").html(result);  
				 
				 }
				})
	//$('#OldBookingTaxConfig').popup('hide');
	
	exit;
		} 
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
function UpdateCheckout(id_mst_hotels,id_fo_bill,id_reservation,doc_type,id_room){
	
	
	
	
	bootbox.confirm({
    title: "Checkout",
    message: "Do you want to Checkout?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
		$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxUpdateCheckout.php',
			   data: 'id_mst_hotels='+id_mst_hotels+'&id_fo_bill='+id_fo_bill+'&id_reservation='+id_reservation+'&doc_type='+doc_type+'&id_room='+id_room, 
			   success: function (result) {
				    let datas = JSON.parse(result);
				   if(datas.status==1){
				   }else{}
				   
				   
				   
				   bootbox.alert({
					   title: "Checkout",
    message: datas.message,
    backdrop: true
});
				   
				// $("#TaxUpdateData").html(result);  
				 
				 }
				})	
		}
		
    }
});
		
		}		
		
function paymenForm(id_mst_hotels,id_fo_bill,id_reservation,doc_type,id_room,amount){
	
	//$("#cancelled").addClass("bookedby_open");
	
	$.ajax({
			   type: "GET",
			   url: 'ajax/ajaxGetPaymentForm.php',
			   data: 'id_mst_hotels='+id_mst_hotels+'&id_fo_bill='+id_fo_bill+'&id_reservation='+id_reservation+'&doc_type='+doc_type+'&id_room='+id_room+'&amount='+amount,  
			   success: function (result) {
				    $('#cancelpop').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
				$("#fo_paymentForm").html(result);	
					
					
			   }
				})	
	
					
	}		


	

function ajaxAddBillPaymentFO(get_purch_id,savetype,id_folio){
	
	/* if(savetype == 0){

  		      	beforeSend:function(){
         return confirm("Are you sure?");
      }
  		      	}*/
				
   var form1=$("#listingForm_"+get_purch_id);	
  // alert(form1);
  
   var dataString = $("#listingForm_"+get_purch_id).serialize()+'&savetype='+savetype+'&id_folio='+id_folio;
	  // alert(dataString);
   
   if(form1.parsley().validate()){
	  // alert();
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxAddBillPaymentFO.php',
		   data: dataString,
		   beforeSend:function(){
         if(savetype == 0){
			 return confirm("Are you sure that you want to Unsettled?");
		 }
      }, 
		   success: function (result) {
			   
			   
			  
					
					console.log(result);
			        data = JSON.parse(result);
					
					
					//alert(datas.id_invoice_detail);
					
			   if(data.status ==1  || data.status ==2 ){
				  $("#updatestatussuccess").html(data.msg);
				//$( "#my_popup_open" ).click(); 
				$('#ratePoint').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});				 
				  $('.targetDivShow').not('#div' + $(this).attr('target')).hide();
				  InvoiceDetails(data.id_invoice_detail);
				  //window.location.href = "manageOutletBilling.php";	
				  }else{
					  
					  $("#updatestatus").html(data.msg);
					  //$( ".my_popupfaild_open" ).click(); 
					  $('#ratePointfaild').popup({
        						transition: 'all 0.3s',
           						 autoopen: true,            
        				});
						//window.location.href = "manageOutletBilling.php";
					  }
					
			}
		})
	}
}

function nightAduitUpdate(){
	

 bootbox.confirm({
    title: "Night Audit ",
    message: "Are you sure you want to Day Close ?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
	$.ajax({
			   type: "GET",
			   url: 'ajax/nightAduitUpdate.php',
			   data: 'folio_split=', 
			   success: function (result) {
				  var response = JSON.parse(result);
				  if(response.status=='1'){
				   $('#auditDate').html(response.dated);
				   //$(".targetDivShowRecheckin").hide();				  
				   alert(response.msg);
				  }else{
					   alert(response.msg);
					  }
				 }
				})
				}
				 }
				})
	
	}
</script>
<script type="text/javascript">
  $('.effective_date').datepicker({ dateFormat: "dd-mm-yy"});
 </script>
	