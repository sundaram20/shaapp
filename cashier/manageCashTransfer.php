<?php 
// 1. Load system functions and database connections
include_once("../config/auto_loader.php"); 

// 2. Load visual layout
include_once("../includes/header.php");
include_once("../includes/left.php");
?>

<style>
.label {
    display: inline;
    padding: .2em .6em .3em;
    font-size: 100%!important;
}
</style>

<div class="content-wrapper">
  
  <section class="content-header">
    <div class="row">
      <div class="col-md-4 col-xs-12"> 
        <h6 class="p-0 m-0">
          <span style="color:#333;">&nbsp;<i class="fa fa-exchange"></i> Cash Transfers</span>
        </h6>
      </div>
      <div class="col-md-4 col-xs-12 dd-f">    
        <div class="icn-box">
          <div class="btn-group"> 
            </div>
        </div>            
      </div> 
      <div class="col-md-4 col-xs-12 tb-br">
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li class="active">Manage Transfers</li>
        </ol>
      </div> 
    </div>
  </section> 

  <section class="content">       
    <div class="box box-default">
      <div class="box-header"> 
        <h6 class="box-title">Search <small> Records:(3) &nbsp;</small> </h6>
        <div class="btn-group pull-right">
          <a type="button" class="btn n-btn" href="editCashTransfer.php">Add New Transfer</a>
        </div>
      </div>
      
      <form name="searchForm" action="" method="get">
        <div class="box-body">
          <div class="row">
            
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
                <label>Document No</label>
                <input type="text" name="search_name" id="search_name" value="" class="form-control" placeholder="Search Doc No" />
              </div>
            </div>
            
            <div class="form-group col-xs-6 col-md-3 col-sm-6" >
              <label for="cashier_from">From Cashier</label>
              <select class="form-control select2" name="cashier_from" id="cashier_from" style="width:100%">
                <option value="">All Cashiers</option>
                <option value="1">Vansh</option>
                <option value="2">Admin User</option>
                <option value="3">Rahul</option>
              </select>
            </div>

            <div class="form-group col-xs-6 col-md-3 col-sm-6" >
              <label for="cashier_to">To Cashier</label>
              <select class="form-control select2" name="cashier_to" id="cashier_to" style="width:100%">
                <option value="">All Cashiers</option>
                <option value="1">Vansh</option>
                <option value="2">Admin User</option>
                <option value="3">Rahul</option>
              </select>
            </div>

            <div class="col-md-3 col-sm-6 col-xs-12 mobile-mb">
              <div class="form-group">
                <label>Period</label>  
                <input type="text" class="form-control pull-right" placeholder="Select From - To" name="datefilter" id="dateRangeReport" value="01-06-2026 to 10-06-2026" autocomplete="off">
              </div>
            </div>  

          </div>

          <div class="box-footer pt-0 pl-0 br-none">
            <input name="Search" type="submit" class="btn o-btn" value="Apply Filters" />
          </div>
        </div>
      </form>
    </div>
           
    <div class="row">
      <div class="col-xs-12">          
        <div class="box">
          <div class="box-header table-h text-center">
            <h3 class="box-title">Transfer History List</h3>
          </div>
          
          <div class="box-body table-responsive">
            <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
              <thead>
                <tr>
                  <th width="1%">S.No.</th>
                  <th>Doc No</th>
                  <th>Date</th> 
                  <th>From Cashier <i class="fas fa-arrow-right text-muted" style="margin: 0 8px;"></i> To Cashier</th>
                  <th>Amount</th> 
                  <th>Created by</th> 
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>  
                <tr>
                  <td>1</td>
                  <td>DOC-2026-001</td>
                  <td>05-06-2026</td> 
                  <td>
                    <span>Vansh</span> 
                    <i class="fas fa-arrow-right text-muted" style="margin: 0 8px;"></i>
                    <span>Admin User</span>
                  </td> 
                  <td><b style="color:#333;">25,000.00</b></td>
                  <td>Vansh</td> 
                  <td class="d-flex">
                    <img src="../images/edit.png" style="cursor:pointer;height:20px" title="View / Edit" onClick="window.location.href='editCashTransfer.php?id=1';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview" onClick="window.location.href='./printCashTransfer.php';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" onClick="deletes('1')"/>
                  </td>
                </tr>
                
                <tr>
                  <td>2</td>
                  <td>DOC-2026-002</td>
                  <td>08-06-2026</td> 
                  <td>
                    <span>Rahul</span> 
                   <i class="fas fa-arrow-right text-muted" style="margin: 0 8px;"></i>
                    <span>Vansh</span>
                  </td> 
                  <td><b style="color:#333;">4,350.00</b></td>
                  <td>Rahul</td> 
                  <td class="d-flex">
                    <img src="../images/edit.png" style="cursor:pointer;height:20px" title="View / Edit" onClick="window.location.href='editCashTransfer.php?id=2';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview" onClick="window.location.href='./printCashTransfer.php';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" onClick="deletes('2')"/>
                  </td>
                </tr>

                <tr>
                  <td>3</td>
                  <td>DOC-2026-003</td>
                  <td>10-06-2026</td> 
                  <td>
                    <span>Admin User</span> 
                    <i class="fas fa-arrow-right text-muted" style="margin: 0 8px;"></i>
                    <span>Rahul</span>
                  </td> 
                  <td><b style="color:#333;">1,000.00</b></td>
                  <td>Admin User</td> 
                  <td class="d-flex">
                    <img src="../images/edit.png" style="cursor:pointer;height:20px" title="View / Edit" onClick="window.location.href='editCashTransfer.php?id=3';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview" onClick="window.location.href='./printCashTransfer.php';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" onClick="deletes('3')"/>
                  </td>
                </tr>
              </tbody>
            </table>   
          </div>
        </div>
      </div>
    </div>
  </section>
</div>   

<script type="text/javascript">
  function deletes(sid) {  
    swal({
      title: "Are you sure?",
      text: "Delete this Fund Transfer?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#1296f3',
      confirmButtonText: 'Yes, I am sure!',
      cancelButtonText: "No, cancel it!",
      closeOnConfirm: false,
      closeOnCancel: true
    },
    function(isConfirm) {
      if (isConfirm) {  
        swal("Deleted!", "The record has been deleted.", "success");
      } 
    });
  }

  $(document).ready(function() {
      if ($.fn.select2) {
          $('.select2').select2();
      }
  });
</script> 

<?php include_once("../includes/footer.php")?>