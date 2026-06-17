<?php 
// 1. Load your system functions and database connections first!
include_once("../config/auto_loader.php"); 

// 2. Then load the visual layout
include_once("../includes/header.php");
include_once("../includes/left.php");
?>

<div class="content-wrapper">
  
  <section class="content-header">
    <div class="row">
      <div class="col-md-4 col-xs-12"> 
        <h6 class="p-0 m-0">
          <span style="color:#333;">&nbsp;<i class="fa fa-money"></i> Cash Memo</span>
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
          <li class="active">Cash Memo</li>
        </ol>
      </div> 
    </div>
  </section> 

  <section class="content">       
    <div class="box box-default">
      <div class="box-header"> 
        <h6 class="box-title">Search <small> Records:(2) &nbsp;</small> </h6>
        <div class="btn-group pull-right">
          <a type="button" class="btn n-btn" href="editCashMemo.php">Add Cash Memo</a>
        </div>
      </div>
      
      <form name="searchForm" action="" method="get">
        <div class="box-body">
          <div class="row">
            <div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
                <label>Document No</label>
                <input type="text" name="search_name" id="search_name" value="" class="form-control" placeholder="Search Document No" />
              </div>
            </div>
            
            <div class="form-group col-xs-6 col-md-2 col-sm-6" >
              <label for="ledger">Ledger</label>
              <select class="form-control select2" name="ledger" id="ledger" style="width:100%">
                <option value="">All Ledgers</option>
                <option value="cash">Cash Account</option>
                <option value="bank">Bank Account</option>
                <option value="sales">Sales Account</option>
              </select>
            </div>

          <div class="form-group col-xs-6 col-md-2 col-sm-6" >
        <label for="cashier_name">Cashier </label>
        <div class="input-group" style="width: 100%;">
            <select class="form-control select2" name="cashier_name" id="cashier_name" required data-parsley-required data-parsley-errors-container="#cashierError">
                <option value="">Select Cashier</option>
                <option value="1">Vansh</option>
                <option value="2">Admin User</option>
                <option value="3">Afsal</option>
            </select>
        </div>
        <span id="cashierError"></span>
    </div>

            <div class="col-md-2 col-sm-6 col-xs-12 mobile-mb">
              <div class="form-group">
                <label>Period</label>  
                <input type="text" class="form-control pull-right" placeholder="Select From - To" name="datefilter" id="dateRangeReport" value="01-06-2026 to 10-06-2026" autocomplete="off">
              </div>
            </div>  
          </div>

          <div class="box-footer pt-0 pl-0 br-none">
            <input name="Search" type="submit" class="btn o-btn" value="Apply" />
          </div>
        </div>
      </form>
      </div>
           
    <div class="row">
      <div class="col-xs-12">          
        <div class="box">
          <div class="box-header table-h text-center">
            <h3 class="box-title">Cash Memo List</h3>
          </div>
          
          <div class="box-body table-responsive">
            <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
              <thead>
                <tr>
                  <th width="1%">S.No.</th>
                  <th>Document No</th>
                  <th>Ledger</th>
                  <th>Payment Mode</th>
                  <th>Date</th> 
                  <th>Amount</th> 
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>  
                <tr>
                  <td>1</td>
                  <td>DOC-2026-001</td>
                  <td>Cash Account</td> 
                  <td>Cash</td> 
                  <td>05-06-2026</td> 
                  <td>1,500.00</td>
                  <td class="d-flex">
                    <img src="../images/edit.png" style="cursor:pointer;height:20px" title="View / Edit" onClick="window.location.href='#';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview" onClick="window.location.href='./printCashMemo.php';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" onClick="deletes('1')"/>
                  </td>
                </tr>
                <tr>
                  <td>2</td>
                  <td>DOC-2026-002</td>
                  <td>Bank Account</td> 
                  <td>Online/UPI</td> 
                  <td>08-06-2026</td> 
                  <td>340.00</td>
                  <td class="d-flex">
                    <img src="../images/edit.png" style="cursor:pointer;height:20px" title="View / Edit" onClick="window.location.href='#';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview" onClick="window.location.href='./printCashMemo.php';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" onClick="deletes('2')"/>
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
      text: "Delete this Document?",
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
</script> 

<?php include_once("../includes/footer.php")?>