<?php include_once("../config/auto_loader.php");

checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'view');

if($_REQUEST['eId'] == ''){
    checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'add');
} else {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'edit');
}

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

    $err = 0;

    if($err == 0){

        //---------- ADD ----------
        if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){

            $doc_no = addslashes($_POST['doc_no']);

            $sql5 = "SELECT * FROM `cash_transaction`
                     WHERE `doc_no` = '".$doc_no."'
                     AND `doc_type` = '901'
                     AND `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."'";
            $db->query($sql5);
            if($db->num_rows() > 0){
                while($row5 = $db->fetch_object()){
                    $doc_no = $row5->doc_no + 1;
                }
            }

            // Build mdoc_no
            $prefix  = addslashes($_POST['prefix']);
            $suffix  = addslashes($_POST['suffix']);
            if($prefix != '' || $suffix != ''){
                $mdoc_no = $prefix.$doc_no.$suffix;
            } else {
                $mdoc_no = addslashes($_POST['mdoc_no']);
            }

            $addSql = "INSERT INTO `cash_transaction` SET
                        `doc_date`                  = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                        `doc_type`                  = '901',
                        `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',
                        `mdoc_no`                   = '".$mdoc_no."',
                        `doc_no`                    = '".$doc_no."',
                        `Transaction_type`          = '1',
                        `id_mst_cashier`            = '".addslashes($_POST['id_mst_cashier'])."',
                        `status`                    = '1',
                        `date_created`              = '".currenDateTime()."',
                        `date_modified`             = '".currenDateTime()."',
                        `created_by`                = '".$_SESSION['userId']."',
                        `modified_by`               = '".$_SESSION['userId']."'";

            executeSql($addSql);
            $lastInsertId = mysqli_insert_id($connNew);

            // Insert detail rows
            $counter1 = intval($_POST['counter1']);
            for($i = 1; $i <= $counter1; $i++){
                if($_POST['id_mst_charges_expenses_ledger'.$i] != '' && $_POST['amount'.$i] != ''){
                    $addDetailSql = "INSERT INTO `cash_transaction_details` SET
                                    `id_cash_transaction`            = '".$lastInsertId."',
                                    `dated`                          = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                                    `id_mst_charges_expenses_ledger` = '".addslashes($_POST['id_mst_charges_expenses_ledger'.$i])."',
                                    `payment_mode`                   = '".addslashes($_POST['payment_mode'.$i])."',
                                    `amount`                         = '".addslashes($_POST['amount'.$i])."',
                                    `remarks`                        = '".addslashes($_POST['remarks'.$i])."',
                                    `status`                         = '1'";
                    executeSql($addDetailSql);
                }
            }

            $_SESSION['successMsg'] = 'Cash Memo has been added successfully.';
            header("location:manageCashMemo.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;

        //---------- UPDATE ----------
        } else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){

            $prefix  = addslashes($_POST['prefix']);
            $suffix  = addslashes($_POST['suffix']);
            if($prefix != '' || $suffix != ''){
                $mdoc_no = $prefix.addslashes($_POST['doc_no']).$suffix;
            } else {
                $mdoc_no = addslashes($_POST['mdoc_no']);
            }

            $cashId = addslashes(encryptor(decrypt, $_POST['eId']));

            $editSql = "UPDATE `cash_transaction` SET
                        `doc_date`                  = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                        `doc_type`                  = '901',
                        `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',
                        `mdoc_no`                   = '".$mdoc_no."',
                        `doc_no`                    = '".addslashes($_POST['doc_no'])."',
                        `Transaction_type`          = '1',
                        `id_mst_cashier`            = '".addslashes($_POST['id_mst_cashier'])."',
                        `date_modified`             = '".currenDateTime()."',
                        `modified_by`               = '".$_SESSION['userId']."'
                        WHERE `id` = '".$cashId."'";
            executeSql($editSql);

            executeSql("DELETE FROM `cash_transaction_details` WHERE `id_cash_transaction` = '".$cashId."'");

            $counter1 = intval($_POST['counter1']);
            for($i = 1; $i <= $counter1; $i++){
                if($_POST['id_mst_charges_expenses_ledger'.$i] != '' && $_POST['amount'.$i] != ''){
                    $addDetailSql = "INSERT INTO `cash_transaction_details` SET
                                    `id_cash_transaction`            = '".$cashId."',
                                    `dated`                          = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                                    `id_mst_charges_expenses_ledger` = '".addslashes($_POST['id_mst_charges_expenses_ledger'.$i])."',
                                    `payment_mode`                   = '".addslashes($_POST['payment_mode'.$i])."',
                                    `amount`                         = '".addslashes($_POST['amount'.$i])."',
                                    `remarks`                        = '".addslashes($_POST['remarks'.$i])."',
                                    `status`                         = '1'";
                    executeSql($addDetailSql);
                }
            }

            $_SESSION['successMsg'] = 'Cash Memo has been updated successfully.';
            header("location:manageCashMemo.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;
        }

    } else {
        $_SESSION['errorMsg'] = 'Cash Memo has not been saved. Please make corrections.';
    }
}

//---------------------------------------------------------------------------------------------------------
// Load existing record for Edit
$row                            = new stdClass();
$row->id                        = '';
$row->doc_date                  = '';
$row->mdoc_no                   = '';
$row->doc_no                    = '';
$row->id_doc_type_configuration = '';
$row->id_mst_cashier            = '';
$row->date_created              = '';
$row->date_modified             = '';
$row->created_by                = '';
$row->modified_by               = '';
$prefix                         = '';
$suffix                         = '';
$detailRows                     = array();

if(!empty($_REQUEST['eId']) && $_REQUEST['action'] == 'edit'){

    $sql = "SELECT * FROM `cash_transaction`
            WHERE `id` = '".addslashes(encryptor(decrypt, $_REQUEST['eId']))."'";
    $db->query($sql);
    if($db->num_rows() > 0){
        $row = $db->fetch_object();
    }

    $sqlDet = "SELECT * FROM `cash_transaction_details`
               WHERE `id_cash_transaction` = '".addslashes(encryptor(decrypt, $_REQUEST['eId']))."'";
    $db->query($sqlDet);
    while($dr = $db->fetch_object()){
        $detailRows[] = $dr;
    }
}

// Load prefix/suffix from doc type config
if($row->id_doc_type_configuration != ''){
    $sql2 = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."`
             WHERE `id` = '".addslashes($row->id_doc_type_configuration)."'";
    $db->query($sql2);
    while($row2 = $db->fetch_object()){
        $prefix = $row2->prefix;
        $suffix = $row2->suffix;
    }
}

// Pre-build ledger options array
$ledgerOptionsArr = array();
$resLedger = selectSql('mst_charges',
    "WHERE `charges_account` = '4' AND `status` = '1'",
    'ORDER BY `name`');
while($lr = $db->fetch_object2($resLedger)){
    $ledgerOptionsArr[] = $lr;
}
?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container { width: 100% !important; }
    table.dataTable tfoot th, table.dataTable tfoot td { border-top: none; }
    .hr-m { margin-top: 10px; margin-bottom: 10px; }
    .text-center-action { text-align: center; vertical-align: middle !important; }
</style>

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <h5 class="box-title">
            <?php echo $_REQUEST['eId'] == '' ? 'Add' : 'Edit'; ?> Cash Memo :
            <span style="color:#1296f3"><?php echo htmlspecialchars($row->mdoc_no); ?></span>
        </h5>
        <?php echo breadCrumbs(); ?>
    </section>

    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">

                    <form name="cash_memo_form" action="" method="post"
                          data-parsley-validate autocomplete="off" id="cash_memo_form">

                        <!-- System hidden fields -->
                        <input type="hidden" value="<?php echo $_REQUEST['eId']; ?>"  name="eId"     id="eId" />
                        <input type="hidden" value="<?php echo $_GET['submenu']; ?>"  name="submenu" id="submenu" />
                        <input type="hidden" value="<?php echo $_GET['session']; ?>"  name="session" id="session" />

                        <!-- Doc number hidden fields (populated by AJAX) -->
                        <input type="hidden" id="prefix"                    name="prefix"
                               value="<?php echo htmlspecialchars($prefix); ?>">
                        <input type="hidden" id="suffix"                    name="suffix"
                               value="<?php echo htmlspecialchars($suffix); ?>">
                        <input type="hidden" id="doc_no"                    name="doc_no"
                               value="<?php echo htmlspecialchars($row->doc_no); ?>">
                        <input type="hidden" id="id_doc_type_configuration" name="id_doc_type_configuration"
                               value="<?php echo htmlspecialchars($row->id_doc_type_configuration); ?>">

                        <!-- Messages -->
                        <div class="form-group has-error" align="center">
                            <?php if($_SESSION['errorMsg']){ ?>
                                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
                            <?php unset($_SESSION['errorMsg']); } elseif($_SESSION['successMsg']){ ?>
                                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                            <?php unset($_SESSION['successMsg']); } ?>
                        </div>

                        <div class="box-body">
                            <div class="card text-dark bg-light">
                                <div class="row">

                                    <!-- Document Type (fixed) -->
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label>Document Type</label>
                                        <input type="text" class="form-control" value="Cash Memo"
                                               readonly style="background-color:#e9ecef;">
                                    </div>

                                    <!-- Date -->
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label for="doc_date">Date <font color="#1296f3">*</font></label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                            <input data-parsley-required type="text"
                                                   class="form-control pickerdate"
                                                   placeholder="Enter Date" id="doc_date" name="doc_date"
                                                   value="<?php
                                                        if($_POST) echo htmlspecialchars($_POST['doc_date']);
                                                        elseif($row->doc_date != '') echo date('d-m-Y', strtotime($row->doc_date));
                                                        else echo date('d-m-Y');
                                                   ?>"
                                                   onchange="hideandshow();">
                                        </div>
                                    </div>

                                    <!-- Document No (auto display) -->
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label>Document No</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-list-ol"></i></div>
                                            <input type="text" class="form-control" id="mdoc_no_display"
                                                   value="<?php echo htmlspecialchars($row->mdoc_no); ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- Manual Doc No (shown only when method = manual) -->
                                    <div id="div_manual_doc_no" class="form-group col-xs-12 col-md-3 col-sm-6" style="display:none;">
                                        <label for="mdoc_no">Manual Document No</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-list-ol"></i></div>
                                            <input type="text" class="form-control"
                                                   placeholder="Enter Manual Doc No"
                                                   id="mdoc_no" name="mdoc_no"
                                                   value="<?php
                                                        if($_POST) echo htmlspecialchars($_POST['mdoc_no']);
                                                        else echo htmlspecialchars($row->mdoc_no);
                                                   ?>">
                                        </div>
                                    </div>

                                    <!-- Cashier -->
                                    <div class="form-group col-xs-12 col-md-3 col-sm-6">
                                        <label for="id_mst_cashier">Cashier <font color="#1296f3">*</font></label>
                                        <select class="form-control select2" name="id_mst_cashier" id="id_mst_cashier"
                                                data-parsley-required
                                                data-parsley-errors-container="#cashierError">
                                            <option value="">Select Cashier</option>
                                            <?php
                                                $resCashier = selectSql('mst_attributes',
                                                    "WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'
                                                     AND `table_name` = 'cashier'
                                                     AND `field_name` = 'cashier_name'
                                                     AND `status` = '1'",
                                                    'ORDER BY `field_value`');
                                                while($cashierRow = $db->fetch_object2($resCashier)){
                                                    $sel = ($row->id_mst_cashier == $cashierRow->id) ? 'selected="selected"' : '';
                                                    echo '<option '.$sel.' value="'.$cashierRow->id.'">'.htmlspecialchars($cashierRow->field_value).'</option>';
                                                }
                                            ?>
                                        </select>
                                        <span id="cashierError"></span>
                                    </div>

                                </div>
                            </div>

                            <!-- Payment Details Table -->
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
                                                    <th style="padding:5px 9px;">Ledger</th>
                                                    <th style="width:180px;">Payment Mode</th>
                                                    <th style="width:130px;">Amount</th>
                                                    <th>Remarks</th>
                                                    <th style="width:80px;" class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="tableBody">

                                            <?php
                                                $rowsToRender = !empty($detailRows) ? $detailRows : array(null);
                                                $rowCounter   = 0;

                                                foreach($rowsToRender as $dRow){
                                                    $rowCounter++;
                                                    $isFirst = ($rowCounter === 1);

                                                    // Ledger select
                                                    $ledgerSelect = '<select class="form-control select2row" '
                                                        .'name="id_mst_charges_expenses_ledger'.$rowCounter.'" '
                                                        .'id="id_mst_charges_expenses_ledger'.$rowCounter.'" '
                                                        .'style="width:100%" required>'
                                                        .'<option value="">Select Ledger</option>';
                                                    foreach($ledgerOptionsArr as $lo){
                                                        $selL = ($dRow && $dRow->id_mst_charges_expenses_ledger == $lo->id) ? 'selected="selected"' : '';
                                                        $ledgerSelect .= '<option '.$selL.' value="'.$lo->id.'">'.htmlspecialchars($lo->name).'</option>';
                                                    }
                                                    $ledgerSelect .= '</select>';

                                                    // Payment mode select
                                                    $modeSelect = '<select class="form-control" '
                                                        .'name="payment_mode'.$rowCounter.'" '
                                                        .'id="payment_mode'.$rowCounter.'" required>'
                                                        .'<option value="">Select Mode</option>'
                                                        .'<option value="1" '.($dRow && $dRow->payment_mode=='1' ? 'selected' : '').'>Cash</option>'
                                                        .'<option value="2" '.($dRow && $dRow->payment_mode=='2' ? 'selected' : '').'>Online/UPI</option>'
                                                        .'<option value="3" '.($dRow && $dRow->payment_mode=='3' ? 'selected' : '').'>Credit/Debit Card</option>'
                                                        .'<option value="4" '.($dRow && $dRow->payment_mode=='4' ? 'selected' : '').'>Cheque</option>'
                                                        .'</select>';

                                                    $amountVal  = $dRow ? $dRow->amount  : '';
                                                    $remarksVal = $dRow ? $dRow->remarks : '';
                                            ?>
                                                <tr>
                                                    <td class="form-group"><?php echo $ledgerSelect; ?></td>
                                                    <td class="form-group"><?php echo $modeSelect; ?></td>
                                                    <td class="form-group">
                                                        <input type="text" autocomplete="off"
                                                               name="amount<?php echo $rowCounter; ?>"
                                                               id="amount<?php echo $rowCounter; ?>"
                                                               placeholder="0.00"
                                                               class="form-control discountvalue text-right"
                                                               value="<?php echo htmlspecialchars($amountVal); ?>"
                                                               required />
                                                    </td>
                                                    <td class="form-group">
                                                        <input type="text" autocomplete="off"
                                                               name="remarks<?php echo $rowCounter; ?>"
                                                               id="remarks<?php echo $rowCounter; ?>"
                                                               placeholder="Enter Remarks"
                                                               class="form-control"
                                                               value="<?php echo htmlspecialchars($remarksVal); ?>" />
                                                    </td>
                                                    <td class="form-group text-center-action">
                                                        <?php if(!$isFirst){ ?>
                                                            <a class="btn n-btn abtn ibtnDel" style="cursor:pointer;" title="Delete">
                                                                <i class="fa fa-trash-o"></i>
                                                            </a>
                                                        <?php } else { ?>
                                                            <a class="deleteRow"></a>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>

                                            <input type="hidden" name="counter1" id="counter1"
                                                   value="<?php echo $rowCounter; ?>">
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="5" style="text-align:right;">
                                                        <hr class="hr-m">
                                                        <a type="button" class="btn n-btn btn-block" id="addrow1">
                                                            <i class="fa fa-plus"></i> Add Row
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" name="status" value="1">

                        </div>

                        <hr class="br-line mb-10">
                        <div class="box-footer p-0 br-none">
                            <input type="submit" value="Save" class="btn c-btn" name="Save">
                            <a type="button" class="btn c-btn"
                               onclick='location.replace("manageCashMemo.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>");'>
                                <i class="far fa-window-close"></i> Close
                            </a>
                        </div>

                        <?php if($row->date_created != ''){ ?>
                        <div class="row mt-10">
                            <div class="form-group col-md-3">
                                <label>Date Created</label>
                                <input type="text" disabled class="form-control"
                                       value="<?php echo stripslashes(dateformat($row->date_created)); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Created By</label>
                                <input type="text" disabled class="form-control"
                                       value="<?php echo stripslashes(selectColumn(TBL_USERS, 'name', 'WHERE id="'.$row->created_by.'"')); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Last Updated</label>
                                <input type="text" disabled class="form-control"
                                       value="<?php echo stripslashes(dateformat($row->date_modified)); ?>">
                            </div>
                            <div class="form-group col-md-3">
                                <label>Last Updated By</label>
                                <input type="text" disabled class="form-control"
                                       value="<?php echo stripslashes(selectColumn(TBL_USERS, 'name', 'WHERE id="'.$row->modified_by.'"')); ?>">
                            </div>
                        </div>
                        <?php } ?>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once("../includes/footer.php"); ?>

<script type="text/javascript">

    // Static options for JS-added rows
    const staticLedgerOptions = `<option value="">Select Ledger</option><?php
        foreach($ledgerOptionsArr as $lo){
            echo '<option value="'.$lo->id.'">'.addslashes($lo->name).'</option>';
        }
    ?>`;

    const staticModeOptions = `<option value="">Select Mode</option>
        <option value="1">Cash</option>
        <option value="2">Online/UPI</option>
        <option value="3">Credit/Debit Card</option>
        <option value="4">Cheque</option>`;

    let counter1 = parseInt(document.getElementById("counter1").value) || 1;

    // Add Row — includes Remarks column
    $("#addrow1").on("click", function(){
        counter1++;
        let newRow = $("<tr>");
        let cols = "";
        cols += '<td class="form-group"><select name="id_mst_charges_expenses_ledger' + counter1 + '" id="id_mst_charges_expenses_ledger' + counter1 + '" class="form-control select3" style="width:100%" required><option value="">Select Ledger</option>' + staticLedgerOptions + '</select></td>';
        cols += '<td class="form-group"><select name="payment_mode' + counter1 + '" id="payment_mode' + counter1 + '" class="form-control" required>' + staticModeOptions + '</select></td>';
        cols += '<td class="form-group"><input type="text" autocomplete="off" placeholder="0.00" class="form-control discountvalue text-right" name="amount' + counter1 + '" id="amount' + counter1 + '" required /></td>';
        cols += '<td class="form-group"><input type="text" autocomplete="off" placeholder="Enter Remarks" class="form-control" name="remarks' + counter1 + '" id="remarks' + counter1 + '" /></td>';
        cols += '<td class="form-group text-center-action"><a class="btn n-btn abtn ibtnDel" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>';

        document.getElementById("counter1").value = counter1;
        newRow.append(cols);
        $("table.order-list1").append(newRow);
        if($.fn.select2){ $(".select3").select2({}); }
    });

    // Delete Row
    $("table.order-list1").on("click", ".ibtnDel", function(){
        $(this).closest("tr").remove();
    });

    // Numeric only for amount
    $(document).on('keypress keyup blur', '.discountvalue', function(event){
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });

    // Doc number AJAX
    function hideandshow(){
        var doc_date = document.getElementById("doc_date").value;
        if(doc_date == '') return;

        $.ajax({
            type: "POST",
            url: "../ajax/CashMemoManage.php",
            data: { doc_type: '901', doc_date: doc_date },
            success: function(data){
                var mydata = JSON.parse(data);

                var prefix = mydata['prefix'] ? mydata['prefix'] : '';
                var suffix = mydata['suffix'] ? mydata['suffix'] : '';
                var doc_no = mydata['doc_no']  ? mydata['doc_no']  : '';

                if(mydata['method'] == '1'){
                    // Auto — show generated doc no, hide manual input
                    $('#div_manual_doc_no').hide();
                    <?php if($row->id == ''){ ?>
                    $("#mdoc_no_display").val(prefix + doc_no + suffix);
                    document.getElementById("doc_no").value                    = doc_no;
                    document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
                    <?php } ?>
                    document.getElementById("prefix").value = prefix;
                    document.getElementById("suffix").value = suffix;
                } else {
                    // Manual — clear display, show manual input
                    $('#div_manual_doc_no').show();
                    $("#mdoc_no_display").val('');
                    <?php if($row->id == ''){ ?>
                    document.getElementById("doc_no").value                    = doc_no;
                    document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
                    <?php } ?>
                    document.getElementById("prefix").value = '';
                    document.getElementById("suffix").value = '';
                }
            }
        });
    }

    // Init plugins
    $(document).ready(function(){
        if($.fn.select2){
            $('.select2').select2();
            $('.select2row').select2();
        }
        if($.fn.datepicker){
            $('.pickerdate').datepicker({
                format: 'dd-mm-yyyy',
                autoclose: true,
                todayHighlight: true
            });
        }
        hideandshow();
    });

</script>