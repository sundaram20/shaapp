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
          <span style="color:#333;">&nbsp;<i class="fa fa-users"></i> Cashier Master</span>
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
          <li class="active">Manage Cashiers</li>
        </ol>
      </div> 
    </div>
  </section> 

  <section class="content">       
    <div class="box box-default">
      <div class="box-header"> 
        <h6 class="box-title">Search <small> Records:(3) &nbsp;</small> </h6>
        <div class="btn-group pull-right">
          <a type="button" class="btn btn-success" style="background-color: #1296f3; border-color: #1296f3;" href="editCashier.php">Add Cashier</a>
        </div>
      </div>
      
      <form name="searchForm" action="" method="get">
        <div class="box-body">
          <div class="row">
            
            <div class="col-md-3 col-sm-6 col-xs-6">
              <div class="form-group">
                <label>Cashier Name</label>
                <input type="text" name="search_name" id="search_name" value="" class="form-control" placeholder="Search Name" />
              </div>
            </div>

            <div class="form-group col-xs-6 col-md-3 col-sm-6" >
              <label for="status">Status</label>
              <select class="form-control select2" name="status" id="status" style="width:100%">
                <option value="">All</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
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
            <h3 class="box-title">Cashier List</h3>
          </div>
          
          <div class="box-body table-responsive">
            <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
              <thead>
                <tr>
                  <th width="10%">S.No.</th>
                  <th>Cashier Name</th> 
                  <th>Status</th> 
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>  
                <tr>
                  <td>1</td>
                  <td>Vansh</td> 
                  <td><span class="label" style="color:green;cursor:pointer;">Active</span></td> 
                  <td class="d-flex">
                    <img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCashier.php?id=1';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/chat.png" style="cursor:pointer;" title="In Use" />
                  </td>
                </tr>
                
                <tr>
                  <td>2</td>
                  <td>Admin User</td> 
                  <td><span class="label" style="color:green;cursor:pointer;">Active</span></td> 
                  <td class="d-flex">
                    <img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCashier.php?id=2';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/delete.gif" style="cursor:pointer;" title="Delete" onClick="deletes('2')"/>
                  </td>
                </tr>

                <tr>
                  <td>3</td>
                  <td>Rahul</td> 
                  <td><span class="label" style="color:red;cursor:pointer;">Inactive</span></td> 
                  <td class="d-flex">
                    <img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCashier.php?id=3';" />&nbsp;&nbsp;&nbsp;&nbsp;
                    <img src="../images/delete.gif" style="cursor:pointer;" title="Delete" onClick="deletes('3')"/>
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
      text: "Delete this Cashier?",
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