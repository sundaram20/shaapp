<?php include_once("../config/auto_loader.php"); ?>


<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<?php include_once("../config/auto_loader.php");  ?>

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

                        <div class="box box-default">
    
   <div class="box-header with-border"> 
   </div>
   <!-- /.box-header -->
   <form name="searchForm" action="" method="get">
      <input type="hidden" value="1" name="searchFormSubmit">
      <div class="box-body">
         <div class="row">
            <div class="col-md-4">
               <div class="form-group">
                  <label>Reservation Id</label>             
                  <input type="text" name="search_name" id="search_name" value="" class="form-control" placeholder="Enter Reservation Id">
               </div>
               <div class="form-group">
                  <label>Other Reference Id</label>             
                  <input type="text" name="other_reference" id="other_reference" value="" class="form-control" placeholder="Enter other reference Id">
               </div>
               <!-- /.form-group -->
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label>Hotel</label>              
                  <select class="form-control select2 select2-hidden-accessible" name="hotelId" tabindex="-1" aria-hidden="true">
                     <option value="">Select Hotel</option>
                     <?php 
                      $sql = executeSql(" SELECT * FROM mst_hotels order by id desc ");
                        while($row = $db->fetch_object2($sql)){ ?>
                            <option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
                       <?php }
                     ?>
                  </select> 
               </div>
               <!-- /.form-group -->
            </div>
            <!-- /.col -->  
            <div class="col-md-4">
               <div class="form-group">
                  <label>Payment Status</label>             
                  <select class="form-control select2 select2-hidden-accessible" name="payment_status" tabindex="-1" aria-hidden="true">
                     <option value="">Select Payment Status</option>
                     <option value="24">Advance  Awaited</option>
                     <option value="22">Advance / Partial Advance received</option> 
                  </select>
                  
               </div>
               <!-- /.form-group -->
            </div>
            <div class="col-md-4">
               <div class="form-group">
                  <label>Booking Status</label>             
                  <select class="form-control select2 select2-hidden-accessible" name="booking_status" tabindex="-1" aria-hidden="true">
                     <option value="">Select Booking Status</option>
                     <option value="4">Cancelled</option>
                     <option value="1">Confirmed</option>
                     <option value="2">Tentative</option>
                     <option value="3">Waitlisted</option>
                  </select>
                   
               </div>
               <!-- /.form-group -->
            </div>
            <div class="form-group col-sm-4">
               <label for="booking_date">Booking Date </label>
               <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="form-control pickerdate" placeholder="Enter booking date" id="booking_date" name="booking_date" value="">
               </div>
               <!-- /.input group -->
            </div>
            <div class="form-group col-sm-4">
               <label for="booking_date">Checkin Date </label>
               <div class="input-group">
                  <div class="input-group-addon"> <i class="fa fa-calendar"></i> </div>
                  <input type="text" class="form-control pickerdate" placeholder="Enter Checkin date" id="checkin_date" name="checkin_date" value="">
               </div>
               <!-- /.input group -->
            </div>
            <!-- /.row -->
         </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
         <input name="Search" type="submit" class="btn btn-primary" value="Search">
      </div>
   </form>
</div>
</div>
                    
                </div> 
            </div>
            <!-- /.tab-content -->

            <div class="row">
        <div class="col-xs-12">          
          <!-- /.box -->
          <div class="box">  
            <div class="box-body table-responsive">
                <table id="DocumentTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" role="grid" aria-describedby="DocumentTable_info">
                    <thead>
                        <tr>
                          <th>Guest Name</th>
                          <th>Booking No</th>
                          <th>Hotel Name</th>
                          <th>Booking Date</th>
                        </tr>
                    </thead>
                <tbody id="list_ajax">  
                                      
                                 
                      </tbody>
            </table> 
            </div> 

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
     

            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>




        </div>
        <!-- nav-tabs-custom -->
    </section> 
</div>

   
<?php include_once("../includes/footer.php")?>
 
      
<!-- calender JS -->
<script>

 $(document).ready(function() {
    list_ajax();
    $('#DocumentTable').DataTable({
        order: [ 1, 'DESC' ],  
    });
 
});

 function list_ajax(){
      
    $.ajax({
      url: "ajax/ajaxListOnLoad.php",
        type: 'POST',
        data: {},
        dataType: "JSON",
        success: function(data) { 
        $('#list_ajax').html(data);
      }
     });
  }

$('.select2').select2();



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