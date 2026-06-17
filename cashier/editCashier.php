<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container {
        width: 100% !important;
    }
    .hr-m {
        margin-top: 10px;
        margin-bottom: 10px;
    }

    
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h5 class="box-title">Add Cashier : <span style="color:#1296f3"> New Record </span></h5>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="manageCashier.php">Cashier Master</a></li>
          <li class="active">Add Cashier</li>
        </ol>
    </section>
    
    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">
                    <ul class="nav nav-tabs">
                       <li class="active"><a href="#tab_1" data-toggle="tab">Overview</a></li>  
                    </ul>

                    <form name="cashier_form" action="" method="post" data-parsley-validate autocomplete="off" id="cashier_form">
                        
                        <div class="box-body">
                            
                            <div class="row">
                                <div class="form-group col-xs-12 col-md-4">
                                    <label for="cashier_name">Cashier Name <font color="#1296f3">*</font></label>
                                    <div class="input-group"> 
                                        <div class="input-group-addon">
                                            <i class="fa fa-user"></i> 
                                        </div>
                                        <input type="text" class="form-control" placeholder="Enter Full Name" id="cashier_name" name="cashier_name" value="Vansh" required>
                                    </div> 
                                </div>

                                <div class="form-group col-xs-12 col-md-4">
                                    <label for="email_address">Email Address <font color="#1296f3">*</font></label>
                                    <div class="input-group"> 
                                        <div class="input-group-addon">
                                            <i class="fa fa-envelope"></i> 
                                        </div>
                                        <input type="email" class="form-control" placeholder="name@company.com" id="email_address" name="email_address" value="vansh@company.com" required>
                                    </div> 
                                </div>

                                <div class="form-group col-xs-12 col-md-4">
                                    <label for="contact_no">Contact No <font color="#1296f3">*</font></label>
                                    <div class="input-group"> 
                                        <div class="input-group-addon">
                                            <i class="fa fa-phone"></i> 
                                        </div>
                                        <input type="text" class="form-control discountvalue" placeholder="Enter Contact Number" id="contact_no" name="contact_no" value="9876543210" required>
                                    </div> 
                                </div>
                            </div>
                            
                            <div class="row mt-10">
                                <div class="form-group col-xs-12">
                                    <label for="status">Status</label><br>
                                    <label style="font-weight: normal; margin-right: 15px;">
                                        <input class="flat-blue" type="radio" checked value="1" name="status" id="status_active" /> Active
                                    </label>
                                    <label style="font-weight: normal;">
                                        <input class="flat-blue" type="radio" value="0" name="status" id="status_inactive" /> Inactive
                                    </label>
                                </div> 
                            </div>

                            <div class="row mt-10">
                                <div class="form-group col-xs-12 col-md-3">
                                    <label for="date_created">Date Created</label>
                                    <input type="text" disabled="disabled" class="form-control" id="date_created" value="10-06-2026 10:30 AM">                
                                </div> 
                                
                                <div class="form-group col-xs-12 col-md-3">
                                    <label for="last_modified">Last Updated</label>
                                    <input type="text" disabled="disabled" class="form-control" id="last_modified" value="10-06-2026 11:45 AM">               
                                </div> 

                                <div class="form-group col-xs-12 col-md-3">
                                    <label for="created_by">Created By</label>
                                    <input type="text" disabled="disabled" class="form-control" id="created_by" value="Admin User">               
                                </div>  
                            </div>

                        </div>
        
                        <div class="box-footer">                                     
                            <button type='submit' class="btn btn-primary" name="Save" style="background-color: #1296f3; border-color: #1296f3;">
                                Save
                            </button>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <a type='button' class="btn btn-danger" onclick='location.replace("manageCashier.php");'>
                                Close
                            </a>
                        </div>
                    </form>         
                </div>
            </div>
        </div>
    </section>
</div>  

<?php include_once("../includes/footer.php");?>  

<script type="text/javascript">
    $(document).ready(function() {
        // Enforce numeric constraint for phone entry
        $(document).on('keypress keyup blur', '.discountvalue', function (event) {
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });
        
        if ($.fn.select2) {
            $('.select2').select2();
        }
    });
</script>