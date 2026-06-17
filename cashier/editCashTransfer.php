<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container{
        width:100%!important;
    }
    .hr-m {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .text-center-action {
        text-align: center;
        vertical-align: middle !important;
    }

    .c-btn {
    background-color: #f56616 !important;
    color: #ffffff;
    width: 115px !important;}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h5 class="box-title">Edit Cash Transfer : <span style="color:#1296f3"> DOC-2026-001 </span></h5>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="manageCashTransfer.php">Manage Transfers</a></li>
          <li class="active">Edit Transfer</li>
        </ol>
    </section>
    
    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">
                    <form name="cash_transfer_form" action="" method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="cash_transfer_form" onsubmit="return validateTransfer()">
                        
                        <input type="hidden" id="submission_timestamp" name="submission_timestamp" value="">
                        <input type="hidden" id="gclid" name="gclid" value="">

                        <div class="box-body">
                            <div class="card text-dark bg-light">
                                <div class="row">   
                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
                                        <label for="doc_type">Document Type</label>
                                        <input type="text" class="form-control" id="doc_type" name="doc_type" value="Cash Transfer" readonly style="background-color: #e9ecef;">
                                    </div>
                                    
                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
                                        <label for="doc_date">Date <font color="#1296f3">*</font></label>
                                        <div class="input-group"> 
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i> 
                                            </div>
                                            <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Date" id="doc_date" name="doc_date" value="05-06-2026">
                                        </div> 
                                    </div>

                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
                                        <label for="mdoc_no">Document No</label>
                                        <div class="input-group"> 
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i> 
                                            </div>
                                            <input type="text" class="form-control" placeholder="Enter Document No" id="mdoc_no" name="mdoc_no" value="DOC-2026-001" readonly style="background-color: #e9ecef;">
                                        </div> 
                                    </div> 
                                </div>
                            </div>

                            <div class="box-body table-responsive2 mt-10" style="padding: 0;">
                                <div class="card text-dark bg-light" style="padding: 15px;">
                                    <div class="row">
                                        <hr class="br-line" style="margin-top: 0;">
                                        <div class="text-center">
                                            <h6 class="tb-heads">Transfer Details</h6>
                                        </div>  
                                        
                                        <div class="row" style="margin-top: 15px;">
                                            <div class="form-group col-xs-12 col-md-3">
                                                <label for="cashier_from">From Cashier <font color="#1296f3">*</font></label>
                                                <select class="form-control select2" name="cashier_from" id="cashier_from" required onchange="checkCashiers()">
                                                    <option value="">Select Cashier</option>
                                                    <option value="1" selected>Vansh</option>
                                                    <option value="2">Admin User</option>
                                                    <option value="3">Rahul</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-xs-12 col-md-3">
                                                <label for="cashier_to">To Cashier <font color="#1296f3">*</font></label>
                                                <select class="form-control select2" name="cashier_to" id="cashier_to" required onchange="checkCashiers()">
                                                    <option value="">Select Cashier</option>
                                                    <option value="1">Vansh</option>
                                                    <option value="2" selected>Admin User</option>
                                                    <option value="3">Rahul</option>
                                                </select>
                                            </div>

                                            <div class="form-group col-xs-12 col-md-2">
                                                <label for="amount">Amount <font color="#1296f3">*</font></label>
                                                <input type="text" autocomplete="off" name="amount" id="amount" placeholder="0.00" class="form-control discountvalue text-right" value="25000.00" required style="font-weight: bold;" />
                                            </div>

                                            <div class="form-group col-xs-12 col-md-4">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" autocomplete="off" name="remarks" id="remarks" placeholder="Enter Remarks" class="form-control" value="End of shift transfer"/>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <span id="cashier_error" style="color: red; display: none; font-weight: bold; padding-left: 15px;"><i class="fa fa-exclamation-triangle"></i> 'From' and 'To' Cashier cannot be the same person!</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                            
                            <div class="row" style="display:none">                  
                                <div class="form-group col-xs-12 col-md-6 col-sm-2"> 
                                    <label for="status">Status : </label> 
                                    <input class="flat-blue" type="radio" checked value="1" name="status" id="status_active" /> Active
                                    <input class="flat-blue" type="radio" value="0" name="status" id="status_inactive" /> Inactive
                                </div>  
                            </div> 

                        </div>
        
                        <hr class="br-line mb-10">     
                        <div class="box-footer p-0 br-none">                                     
                            <input type='submit' value='Save' class="btn c-btn" name="Save" id="save_btn">
                            <a type='button' class="btn c-btn" onclick='location.replace("manageCashTransfer.php");'>
                                <i class="far fa-window-close"></i> Close
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
    // Compliance logic for tracking submission details
    function setSubmissionData() {
        document.getElementById('submission_timestamp').value = new Date().toISOString();
        
        // GCLID capture logic from URL params
        const urlParams = new URLSearchParams(window.location.search);
        let gclid = urlParams.get('gclid');
        if(gclid) {
            gclid = gclid.replace(/[^a-zA-Z0-9_-]/g, ""); 
            document.getElementById('gclid').value = gclid;
        }
    }

    // Logical Validation: Prevent transfer to the same cashier
    function checkCashiers() {
        let fromCashier = document.getElementById('cashier_from').value;
        let toCashier = document.getElementById('cashier_to').value;
        let errorSpan = document.getElementById('cashier_error');
        let saveBtn = document.getElementById('save_btn');

        if (fromCashier !== "" && toCashier !== "" && fromCashier === toCashier) {
            errorSpan.style.display = "block";
            saveBtn.disabled = true;
            return false;
        } else {
            errorSpan.style.display = "none";
            saveBtn.disabled = false;
            return true;
        }
    }

    function validateTransfer() {
        if (!checkCashiers()) {
            return false;
        }
        setSubmissionData();
        return true;
    }

    // Allow only numeric input and decimals for Amount field
    $(document).on('keypress keyup blur', '.discountvalue', function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

    // Initialize datepicker and select2 plugins
    $(document).ready(function() {
        if ($.fn.select2) {
            $('.select2').select2();
        }
        if ($.fn.datepicker) {
            $('.pickerdate').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        }
    });
</script>