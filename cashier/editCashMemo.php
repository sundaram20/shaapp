<?php include_once("../config/auto_loader.php"); ?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container{
        width:100%!important;
    }
    table.dataTable tfoot th, table.dataTable tfoot td {
        border-top: none;
    }
    .hr-m {
        margin-top: 10px;
        margin-bottom: 10px;
    }
    .text-center-action {
        text-align: center;
        vertical-align: middle !important;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h5 class="box-title">Add Cash Memo : <span style="color:#1296f3"> DOC-2026-001 </span></h5>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="manageCashMemo.php">Cash Memo</a></li>
          <li class="active">Add Cash Memo</li>
        </ol>
    </section>
    
    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">
                    <form name="cash_memo_form" action="" method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="cash_memo_form" onsubmit="setSubmissionData()">
                        
                        <input type="hidden" id="submission_timestamp" name="submission_timestamp" value="">
                        <input type="hidden" id="gclid" name="gclid" value="">

                        <div class="box-body">
                            <div class="card text-dark bg-light">
                                <div class="row">   
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label for="doc_type">Document Type</label>
                                        <input type="text" class="form-control" id="doc_type" name="doc_type" value="Cash Memo" readonly style="background-color: #e9ecef;">
                                    </div>
                                    
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label for="doc_date">Date <font color="#1296f3">*</font></label>
                                        <div class="input-group"> 
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i> 
                                            </div>
                                            <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Date" id="doc_date" name="doc_date" value="10-06-2026">
                                        </div> 
                                    </div>

                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label for="mdoc_no">Document No</label>
                                        <div class="input-group"> 
                                            <div class="input-group-addon">
                                                <i class="fa fa-list-ol"></i> 
                                            </div>
                                            <input type="text" class="form-control" placeholder="Enter Document No" id="mdoc_no" name="mdoc_no" value="DOC-2026-001" readonly>
                                        </div> 
                                    </div> 

                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
        <label for="cashier_name">Cashier <font color="#1296f3">*</font></label>
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
                                </div>
                            </div>

                            <div class="box-body table-responsive2 mt-10">
                                <div class="card text-dark bg-light">
                                    <div class="row">
                                        <hr class="br-line">
                                        <div class="text-center">
                                            <h6 class="tb-heads">Payment Details</h6>
                                        </div>  
                                        <table id="myTable1" class="table table-striped table-bordered dataTable no-footer order-list1 max-h2">
                                            <thead>
                                                <tr>
                                                    <th style="width:250px;padding: 5px 9px;">Ledger</th>
                                                    <th style="width:200px;">Payment Mode</th> 
                                                    <th style="width:150px;">Amount</th> 
                                                    <th>Remarks</th> 
                                                    <th style="width:80px;" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">
                                                <tr>
                                                    <td class="form-group"> 
                                                        <select class="form-control select2" name="ledger_1" id="ledger_1" style="width:100%" required>
                                                            <option value="">Select Ledger</option>
                                                            <option value="cash">Cash Account</option>
                                                            <option value="bank">Bank Account</option>
                                                            <option value="sales">Sales Account</option>
                                                        </select>  
                                                    </td> 
                                                    <td class="form-group">
                                                        <select class="form-control" name="payment_mode_1" id="payment_mode_1" required>
                                                            <option value="">Select Mode</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="Online">Online/UPI</option>
                                                            <option value="Card">Credit/Debit Card</option>
                                                            <option value="Cheque">Cheque</option>
                                                        </select>
                                                    </td> 
                                                    <td class="form-group"> 
                                                        <input type="text" autocomplete="off" name="amount_1" id="amount_1" placeholder="0.00" class="form-control discountvalue text-right" value="" required />
                                                    </td>
                                                    <td class="form-group"> 
                                                        <input type="text" autocomplete="off" name="remarks_1" id="remarks_1" placeholder="Enter Remarks" class="form-control" value=""/>
                                                    </td>
                                                    <td class="form-group text-center-action">
                                                        <a class="deleteRow"></a> </td>
                                                </tr> 
                                                <input type="hidden" name="counter1" id="counter1" value="1"> 
                                            </tbody>
                                            <tfoot>
                                                <tr> 
                                                    <td colspan="5" style="text-align:right;">
                                                        <hr class="hr-m">
                                                        <a type="button" class="btn n-btn btn-block" id="addrow1"> <i class="fa fa-plus"></i> Add Row</a>
                                                    </td> 
                                                </tr>
                                            </tfoot>
                                        </table>
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
                            <input type='submit' value='Save' class="btn c-btn" name="Save">
                            <a type='button' class="btn c-btn" onclick='location.replace("manageCashMemo.php");'>
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

    // Static Options for dynamically added rows
    const staticLedgerOptions = `<option value="">Select Ledger</option>
                                 <option value="cash">Cash Account</option>
                                 <option value="bank">Bank Account</option>
                                 <option value="sales">Sales Account</option>`;

    const staticModeOptions = `<option value="">Select Mode</option>
                               <option value="Cash">Cash</option>
                               <option value="Online">Online/UPI</option>
                               <option value="Card">Credit/Debit Card</option>
                               <option value="Cheque">Cheque</option>`;

    let counter1 = parseInt(document.getElementById("counter1").value) || 1;  

    // Add Row Logic
    $("#addrow1").on("click", function () { 
        counter1++;  

        let newRow1 = $("<tr>");
        let cols1 = ""; 
        cols1 += '<td class="form-group"><select name="ledger_' + counter1 + '" id="ledger_' + counter1 + '" class="form-control select3" style="width:100%" required>' + staticLedgerOptions + '</select></td>'; 
        cols1 += '<td class="form-group"><select name="payment_mode_' + counter1 + '" id="payment_mode_' + counter1 + '" class="form-control" required>' + staticModeOptions + '</select></td>';
        cols1 += '<td class="form-group"><input type="text" autocomplete="off" placeholder="0.00" class="form-control discountvalue text-right" name="amount_' + counter1 + '" id="amount_' + counter1 + '" required /></td>';  
        cols1 += '<td class="form-group"><input type="text" autocomplete="off" placeholder="Enter Remarks" class="form-control" name="remarks_' + counter1 + '" id="remarks_' + counter1 + '"/></td>';       
        cols1 += '<td class="form-group text-center-action"><a class="btn n-btn abtn ibtnDel" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>'; 
        
        document.getElementById("counter1").value = counter1; 
        newRow1.append(cols1);
        $("table.order-list1").append(newRow1); 
        
        if ($.fn.select2) {
            $(".select3").select2({});
        }
    });

    // Delete Row Logic
    $("table.order-list1").on("click", ".ibtnDel", function (event) {
        $(this).closest("tr").remove();                
    });  

    // Allow only numeric input for Amount fields
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