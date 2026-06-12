<?php include('header.php') ?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Page Header
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
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tab_1" data-toggle="tab">One Window</a>
                </li>
                <li><a href="#tab_2" data-toggle="tab">Reservations</a></li>
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
                <!-- /.tab-pane -->
                <div class="tab-pane" id="tab_2">
                    <div class="from-group"><label for="">Hotel</label><input id="newHotel" type="text" /></div>
                    <div class="from-group"><label for="">Room</label><input id="newRoom" type="text" /></div>
                    <div class="from-group"><label for="">Guest</label><input id="newGuest" type="text" /></div>
                    <div class="from-group"><label for="">checkin</label><input id="newCheckin" type="text" /></div>
                    <div class="from-group"><label for="">checkout</label><input id="newCheckout" type="text" /></div>
                    <div class="from-group"><label for="">rooms</label><input id="newRooms" type="text" /></div>
                </div>
            </div>
            <!-- /.tab-content -->
        </div>
        <!-- nav-tabs-custom -->
    </section>

    <!-- /.content -->

    <!-- Reservation Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Quick Form</h4>
                </div>
                <div class="modal-body">
                    <form action="" id="reservationForm" class="form">
                        <input type="hidden" name="res_hotel" id="res_hotel" />
                        <input type="hidden" name="res_room" id="res_room" />
                        <input type="hidden" name="res_checkin" id="res_checkin" />
                        <input type="hidden" name="res_checkout" id="res_checkout" />
                        <div class="form-group"><label for="">Guest Details</label><input name="guest" id="guest" type="text" class="form-control" /></div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Source</label>
                                    <select name="id_company" id="" class="form-control">
                                        <option value="">Select Source</option>
                                        <option value="2222">Cox And Kings</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Booked By</label>
                                    <select name="id_booked" id="id_booked" class="form-control">
                                        <option value="">Select Booked By</option>
                                        <option value="1234">Hitesh</option>
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
</div>
<!-- /.content-wrapper -->

<?php include('footer.php') ?>
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
            $("#myModalLabel").html(label);
            $("#res_checkin").val(checkin);
            $("#res_checkout").val(checkout);
            $("#res_hotel").val(id_hotel);
            $("#res_room").val(id_room);
            /* end */
            $("#myModal").modal("show");
            $("#booking_status").val(info.resource._resource.id);
        }
    });

    calendar.render();

    jumpTodate = (date=$("#datepicker").val()) =>{
        calendar.gotoDate(moment(date).utc().format());
    }

    $("#more-options").click(function() {
        $("#myModal").modal("hide");
        $("#tab_1,#tab_2,.nav-tabs li").toggleClass("active");
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
               $("#myModal").modal("hide");
               calendar.refetchEvents()
            }
        });
    };

});

//Date picker
    $('#datepicker').datepicker({
      autoclose: true,
      format:'yyyy-mm-dd'
    })
   
</script>
<!-- End -->


