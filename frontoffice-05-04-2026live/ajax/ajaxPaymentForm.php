<?php include_once("../../config/auto_loader.php"); 

$id = $_GET['id'] ?? '';
$booking_id = encryptor('decrypt', $id);
$res_id = selectColumn('fo_reservations','booking_no','WHERE id = "'.$booking_id.'"');
//guest name
$guest_id = selectColumn('fo_reservations','id_mst_guest','WHERE id = "'.$booking_id.'"');
$first_name = selectColumn('mst_guest','first_name','WHERE id = "'.$guest_id.'"');
$last_name = selectColumn('mst_guest','last_name','WHERE id = "'.$guest_id.'"');
$name = $first_name.' '.$last_name;
$guest_name = htmlspecialchars($name);
//====

$sql_paid = "SELECT SUM(amount) AS total_amount
FROM fo_receipt
WHERE id_reservation = '".$booking_id."'";
$res_paid = executeSql($sql_paid);

// Fetch the row (your function usually returns mysqli result)
$row_paid = mysqli_fetch_assoc($res_paid);

// Get the sum
$total_paid = $row_paid['total_amount'] ?? 0;
?>

<div id="div" class="targetDivShow">
    <form name="paymentForm" id="paymentForm" action="" method='POST'
      data-parsley-validate>
      <input type="hidden" value="" name="act" />
      <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>" />
        <div class="box-body">
      <div class="card text-dark bg-light">
      
      <div class="history-wrapper" style="margin-bottom:15px;">
    
    <div class="history-wrapper" style="margin-bottom:15px;">

    <!-- Header Row -->
    <div style="
        display:flex; 
        justify-content:space-between; 
        align-items:center; 
        background:#f1f1f1; 
        padding:10px; 
        border-radius:5px;
    ">

        <!-- Toggle Button (LEFT) -->
        <div id="toggleHistory" style="cursor:pointer;">
            <b>Show Transaction History</b> ▾
        </div>

        <!-- Print Receipt Button (RIGHT) -->
         <?php if($total_paid != 0){ ?>
        <button type="button" 
                id="printReceiptBtn"
                style="
                    background:#007bff; 
                    color:white; 
                    border:none; 
                    padding:6px 12px; 
                    border-radius:4px; 
                    cursor:pointer;
                ">
            Print Receipt
        </button>
        <?php }?>
    </div>

    <!-- History Box (hidden initially) -->
    <div id="transactionHistory" style="display:none; margin-top:10px;"></div>

</div>



        <div class="row">
          <div class="form-group col-xs-12 col-md-12 col-sm-12">
            <div class="box-body" style=" padding-bottom:0px !important;">
              <div class="card text-dark bg-light">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" style="margin-bottom: 1px;">
                      <div class="box-body table-responsive"
                        style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
                        <table id="myTableOrder1" class="table dataTable no-footer table-responsive" cellspacing="0"
                          style="font-size:14px;padding: 0px 0px;border: 1px solid #3c8dbc;">
                          <thead style="font-size:10px;padding: 0px 0px;">
                            <tr
                              style="background-color: #3c8dbc;color: #fff;font-variant-caps: all-petite-caps;font-size: 14px;">
                              <th></th>
                              <th style="width:350px;padding: 5px 9px;"> Payment Mode</th>
                              <th style="width:100px;padding: 5px 9px;">Amount</th>
                              <th style=" padding: 5px 9px;">Remarks</th>
                              <th style="width:100px;padding: 5px 9px;">Tips</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox"
                                  class="flat-red i-checks checkboxpayamount"
                                  name="cash_checkbox" id="cash_checkbox"
                                  value="" />
                              </td>
                              <td>
                                <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span">
                                    <img src="../images/cashpay.png" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">CASH</span> </div>
                                  <!-- /.info-box-content -->
                                </div>

                                <!-- /.info-box -->
                              </td>
                              <td>
                                <input type="text"
                                  <?php  ?>
                                  class="form-control first-input billingamount"
                                  name="cash_payment"
                                  id="cash_payment"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" />
                                </td>
                              <td>
                                <input type="text"
                                  class="form-control first-input" placeholder="Remarks"
                                  name="cash_remarks"
                                 style="float: left;" />
                                </td>
                              <td>
                                <input type="text"
                                  <?php ?>
                                  class="form-control first-input" name="cash_tips"
                                  id="cash_tips"
                                  style="float: left;" />
                                </td>
                            </tr>
                            <!----------------------CARD PAYMENT------------------------------------>
                            
                           
                            <tr style="border:1px solid red;background-color:#fff;"
                              id="grid">
                              <td style="width: 2.5%;">
                                <input type="checkbox"
                                  class="flat-red i-checks checkboxpayamount"
                                  name="checkboxpayamount" id="checkboxpayamount" />
                              </td>
                              <td>
                                <div class="info-box"
                                  style="height:90px !important;min-height: 90px !important;margin-bottom: 0px !important;">
                                  <span class="info-box-icon bg-aqua"
                                    style="height:90px !important;line-height: 90px !important;">
                                    <img src="../images/credit_cards_card-512.png" style="cursor:pointer;"
                                      title=" Bill Payment " /> 
                                  </span>
                                  <div class="info-box-content" style="width: 83%;height: 28px;"> <span
                                      class="info-box-text" style="width:87%;float:left;">CARD </span>
                                  </div>
                                  <!-- /.info-box-content -->

                                  <div class="info-box"
                                    style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;">
                                    <span class="info-box-number">

                                      <div class="box-body"
                                        style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                          <div style="margin-left: 15px;">
                                            <label for="name" class="paymentlable">
                                              <input type="radio" class="flat-red"
                                                value="1"
                                                name="cardtype[]"
                                                id="cardtype" />
                                            </label>
                                          </div>
                                          <img class="flagimgs first"
                                            src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa">
                                        </div>
                                      </div>
                                     
                                      <div class="box-body"
                                        style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                          <div style="margin-left: 15px;">
                                            <label for="name" class="paymentlable">
                                              <input type="radio" class="flat-red"
                                                value="3"
                                                name="cardtype[]"
                                                id="cardtype" />
                                            </label>
                                          </div>
                                          <img src="../images/neft.png" style="cursor:pointer;" title="upi" />
                                        </div>
                                      </div>


                                    </span> </div>
                                </div>
                              </td>

                              <td style="width: 12.5%;"><input type="text"
                                  class="form-control first-input billingamount"
                                  name="card_payment"
                                  id="card_payment"
                                   style="float: left;"
                                  data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                              <td style="width: 35.5%;">
                                <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                  <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                    <select class="form-control first-input select2" style="width:100% !important;"
                                      name="id_bank_name"
                                      id="id_bank_name"
                                      >
                                      <option value="0">--- Select Bank --- </option>
                                      <!--select bank-->
                                      <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_charges_master == $resultCat->id){
														$selected = '';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                    </select>
                                  </div>



                                </div>
                                <input type="text"
                                  class="form-control first-input" placeholder="Remarks"
                                  name="card_remarks" id="card_remarks"
                                  style="float: left;" />
                              </td>



                              <td>
                                <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                  <div class="input-group" style="width:100% !important;">
                                    <input type="text"
                                      class="form-control first-input" name="card_tips"
                                      id="card_tips"
                                      style="float: left;" />
                                  </div>
                                </div>
                                
                              </td>
                              </td>

                            </tr>






                            <!----------------------ONLINE TRANSFER ------------------------------------>
<tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox"
                                  class="flat-red i-checks checkboxpayamount"
                                  name="upi_checkbox" id="upi_checkbox" />
                      </div>
                      </td>
<td>
                        <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span"> <img
                              src="../images/gift.jpg" style="cursor:pointer;" title=" Bill Payment " /> </span>
                          <div class="info-box-content"> <span class="info-box-text">UPI</span> 
							 <img src="../images/upi.png" style="cursor:pointer;margin-left:60px;" title="upi"  /></div>
                          <!-- /.info-box-content -->
                        </div>
                      </td>
                      <td>
                        <input type="text"
                          class="form-control first-input billingamount"
                          name="upi_payment"
                          id="upi_payment"
                          style="float: left;"
                          data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                          
                               <td>
                                <input type="text"
                                  class="form-control first-input" placeholder="Remarks"
                                  name="upi_remarks" id="upi_remarks"
                                  style="float: left;" />
                                </td>
                      <td>
                        <input type="text"
                          class="form-control first-input" name="upi_tips"
                          id="upi_tips"
                          style="float: left;" />
                        </td>
                      </tr>


                            <!------------------COMPANY--------START------------------------------>
                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox"
                                  class="flat-red i-checks checkboxpayamount"
                                  name="company_checkbox" id="company_checkbox"/>
                              </td>
                              <td>
                                <div class="info-box"
                                  style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;">
                                  <span class="info-box-icon bg-aqua"
                                    style="height:80px !important;line-height: 70px !important;"> <img
                                      src="../images/company.png" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">COMPANY</span> </div>
                                  <!-- /.info-box-content -->
                                </div>
                              </td>
                              <td><input type="text"  
                                  class="form-control first-input billingamount"
                                  name="company_payment"
                                  id="company_payment"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" /></td>
                              <td>
                                <div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                  <div class="input-group" style="width:100% !important;">
                                    <select class="form-control first-input select2" style="width:100% !important;"
                                      name="id_company_name"
                                      id="id_company_name">
                                      <option value="0">Select Company </option>
                                      <?php  $resCat = selectSql(MST_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_company[$purch_id][4] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                    </select>
                                  </div>
                                </div>
                                <input type="text"
                                  class="form-control first-input" placeholder="Remarks"
                                  name="company_remarks"
                                  id="company_remarks"
                                  style="float: left;" />
                              </td>
                              <td><input type="text"
                                  class="form-control first-input" name="company_tips"
                                  id="company_tips"
                                  style="float: left;" />
                                </td>
                            </tr>
                            <!------------------COMPANY---END----------------------------------->

                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox" 
                                  class="flat-red i-checks checkboxpayamount"
                                  name="cheque_checkbox" id="cheque_checkbox" />
                              </td>
                              <td>
                                <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span">
                                    <img src="../images/cheq.jpg" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">CHEQUE</span> </div>
                                  <!-- /.info-box-content -->
                                </div>
                              </td>
                              <td><input type="text"
                                  class="form-control first-input billingamount"
                                  name="cheque_payment"
                                  id="cheque_payment"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" /></td>
                              <td><input type="text"
                                  class="form-control first-input" placeholder="Remarks"
                                  name="cheque_remarks"
                                  id="cheque_remarks"
                                  style="float: left;" />
                                </td>
                              <td><input type="text"
                                  class="form-control first-input" name="cheque_tips"
                                  id="cheque_tips"
                                  style="float: left;" />
                                </td>
                            </tr>


                      </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card text-dark bg-light" style="background-color:#3c8dbc;">
                <div class="row">
                  <div class="form-group col-xs-12 col-md-3 col-sm-3">
                    <label for="name" style="margin-left:5px;color:#fff;">Date</label>
                    <div class="input-group" style="margin-left:5px;">
                      <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                      <input type="text" class="form-control pickerdateretwodays" placeholder="sreEnter PO Date"
                        id="po_date1" name="po_date1" value="<?php echo date('d-m-Y');?>">
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-3 col-sm-3">
                    <label for="name" style="color:#fff;">Bill Amount</label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                      <input type="text" class="form-control" placeholder="Total Amount" id="grand_total_amount"
                        name="grand_total_amount" value="<?php //echo $grand_total_amount; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-3 col-sm-3">
                    <label for="name" style="color:#fff;">Paid Amount</label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                      <input type="text" class="form-control" disabled placeholder="Total Pay Amount"
                        id="pay_total_amount" name="pay_total_amount"
                        value="<?php echo $total_paid;?>" style="text-align:right;" data-parsley-required
                        data-parsley-errors-container="#pay_total_amountError">
                    </div>
                  </div>
                  
                  <div class="form-group col-xs-12 col-md-3 col-sm-3">
                    <div class="input-group" style="margin-top:24px;">
                      <input type='button' name="saveForm" id="saveForm" value='Save' class="btn btn-success"
                        onClick="">

                    </div>
                  </div>
                </div>
              </div>
              <!-- Total Amount Section -->

            </div>
          </div>
        </div>
        <div> </div>
      </div>
  </div>
</div>

<script>
$(document).ready(function(){

    // Common function to handle enabling/disabling
    function toggleRow(checkbox, fields){
        if($(checkbox).is(":checked")){
            fields.prop("readonly", false);
            fields.prop("disabled", false);
        } else {
            fields.prop("readonly", true);
            fields.prop("disabled", true);
            fields.val(""); // clear values when disabled
        }
    }

    // CASH
    toggleRow("#cash_checkbox", $("#cash_payment, #cash_remarks, #cash_tips"));
    $("#cash_checkbox").on("change", function(){
        toggleRow(this, $("#cash_payment, #cash_remarks, #cash_tips"));
    });

    // CARD
    toggleRow("#checkboxpayamount", $("#card_payment, #id_bank_name, #card_remarks, #card_tips, input[name='cardtype[]']"));
    $("#checkboxpayamount").on("change", function(){
        toggleRow(this, $("#card_payment, #id_bank_name, #card_remarks, #card_tips, input[name='cardtype[]']"));
    });

    // UPI
    toggleRow("#upi_checkbox", $("#upi_payment, #upi_remarks, #upi_tips"));
    $("#upi_checkbox").on("change", function(){
        toggleRow(this, $("#upi_payment, #upi_remarks, #upi_tips"));
    });

    // COMPANY
    toggleRow("#company_checkbox", $("#company_payment, #id_company_name, #company_remarks, #company_tips"));
    $("#company_checkbox").on("change", function(){
        toggleRow(this, $("#company_payment, #id_company_name, #company_remarks, #company_tips"));
    });

    // CHEQUE
    toggleRow("#cheque_checkbox", $("#cheque_payment, #cheque_remarks, #cheque_tips"));
    $("#cheque_checkbox").on("change", function(){
        toggleRow(this, $("#cheque_payment, #cheque_remarks, #cheque_tips"));
    });

});
</script>
<script>
$(document).ready(function(){

    // ---------------------------
    // ENABLE/DISABLE ROW FUNCTION
    // ---------------------------
    function toggleRow(checkbox, fields){
        if($(checkbox).is(":checked")){
            fields.prop("readonly", false).prop("disabled", false);
        } else {
            fields.prop("readonly", true).prop("disabled", true).val("");
        }
        calculateTotal(); // recalc every toggle
    }

    // ---------------------------
    // PAYMENT MODE TOGGLES
    // ---------------------------
    toggleRow("#cash_checkbox", $("#cash_payment,#cash_remarks,#cash_tips"));
    $("#cash_checkbox").on("change", function(){
        toggleRow(this, $("#cash_payment,#cash_remarks,#cash_tips"));
    });

    toggleRow("#checkboxpayamount", $("#card_payment,#id_bank_name,#card_remarks,#card_tips,input[name='cardtype[]']"));
    $("#checkboxpayamount").on("change", function(){
        toggleRow(this, $("#card_payment,#id_bank_name,#card_remarks,#card_tips,input[name='cardtype[]']"));
    });

    toggleRow("#upi_checkbox", $("#upi_payment,#upi_remarks,#upi_tips"));
    $("#upi_checkbox").on("change", function(){
        toggleRow(this, $("#upi_payment,#upi_remarks,#upi_tips"));
    });

    toggleRow("#company_checkbox", $("#company_payment,#id_company_name,#company_remarks,#company_tips"));
    $("#company_checkbox").on("change", function(){
        toggleRow(this, $("#company_payment,#id_company_name,#company_remarks,#company_tips"));
    });

    toggleRow("#cheque_checkbox", $("#cheque_payment,#cheque_remarks,#cheque_tips"));
    $("#cheque_checkbox").on("change", function(){
        toggleRow(this, $("#cheque_payment,#cheque_remarks,#cheque_tips"));
    });


    // ---------------------------------
    // AUTO CALCULATE TOTAL FUNCTION
    // ---------------------------------
    function calculateTotal(){
        let total = 0;

        // Add only active (enabled) inputs
        $(".billingamount").each(function(){
            if(!$(this).prop("disabled")){
                let v = parseFloat($(this).val()) || 0;
                total += v;
            }
        });

        $("#grand_total_amount").val(total.toFixed(2));
    }

    // ---------------------------------
    // TRIGGER AUTO SUM WHEN TYPING
    // ---------------------------------
    $(document).on("keyup", ".billingamount", function(){
        calculateTotal();
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function () {

    $("#saveForm").click(function () {

        let resId = "<?php echo $booking_id; ?>";
        let guestName = "<?php echo $guest_name; ?>";
        let billAmount = $("#grand_total_amount").val() || "0";
        let paidAmount = parseFloat($("#pay_total_amount").val()) || 0;

        // ----------------------------
        // NEW VALIDATION: AMOUNT <= 0
        // ----------------------------
        if (billAmount <= 0) {
            Swal.fire({
                title: "Invalid Amount!",
                text: "Paying amount must be greater than 0.",
                icon: "error",
                confirmButtonText: "OK"
            });
            return; // prevent showing confirmation popup
        }

        // ---------------------------------------
        // Main Confirmation Popup (VALID AMOUNT)
        // ---------------------------------------
        Swal.fire({
            title: "Confirm Payment?",
            html: `
                <div style='text-align: left; font-size:15px;'>
                    <b>Reservation ID:</b> ${resId} <br>
                    <b>Guest Name:</b> ${guestName} <br>
                    <b>Total Advance Received:</b> ₹ ${paidAmount}<br>
                    <b>Bill Amount:</b> ₹ ${billAmount} 
                </div>
            `,
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Yes, Save Payment",
            cancelButtonText: "Cancel",
        }).then((result) => {
            if (result.isConfirmed) {
                let formData = $("#paymentForm").serialize();

                $.ajax({
                  url:'ajax/save_advancePayment.php',
                  type:'POST',
                  data:formData,
                  dataType:'json',
                  success: function(res){
                    if(res.status === 'success'){
                    swal.fire({
                      title:'success',
                      html:`
                        Payment saved successfully.<br><br>
                    <a href="print_advance_receipt.php?res_id=${res.reservation_id}" target="_blank"
                       class="swal2-confirm swal2-styled">
                       Print Receipt
                    </a>
                      `,
                      icon:'success',
                      showConfirmButton: false,
                    });
                  }else{
                    Swal.fire("Error", res.message, "error");
                  }
                  }
                });
            }
        });

    });

});

//==================================================

// Toggle & Load History
$("#toggleHistory").click(function () {
    let box = $("#transactionHistory");

    if (box.is(":visible")) {
        box.slideUp();
        $("#toggleHistory").html("<b>Show Transaction History</b> ▾");
        return;
    }

    // Load via AJAX
    $.ajax({
        url: "ajax/load_payment_history.php",
        type: "POST",
        data: { booking_id: "<?php echo $booking_id; ?>" },
        success: function(response){
            box.html(response);
            box.slideDown();
            $("#toggleHistory").html("<b>Hide Transaction History</b> ▴");
        }
    });
});


// Delete Payment
$(document).on("click", ".deletePaymentBtn", function () {
    let paymentId = $(this).data("id");
    //console.log(paymentId);
    
    Swal.fire({
        title: "Are you sure?",
        text: "This payment entry will be permanently deleted.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Delete",
        cancelButtonText: "Cancel"
    }).then((result) => {

        if (result.isConfirmed) {
            $.ajax({
                url: "ajax/delete_payment.php",
                type: "POST",
                data: { id: paymentId },
                success: function(res){

                    Swal.fire("Deleted!", "Payment has been removed.", "success");

                    // Reload history without closing it
                    $("#toggleHistory").click();
                    $("#toggleHistory").click();

                }
            });
        }

    });

});


$("#printReceiptBtn").click(function() {
    let resId = "<?php echo $id; ?>";

    // Redirect to print receipt page
    window.open("print_advance_receipt.php?res_id=" + resId, "_blank");
});


</script>

