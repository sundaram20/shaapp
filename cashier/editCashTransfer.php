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

    // Validate: from and to cashier cannot be same
    if($_POST['cashier_from'] == $_POST['cashier_to']){
        $err++;
        $_SESSION['errorMsg'] = 'From and To Cashier cannot be the same person.';
    }

    if($err == 0){

        //---------- ADD ----------
        if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){

            $doc_no = addslashes($_POST['doc_no']);

            $sql5 = "SELECT * FROM `cash_transaction`
                     WHERE `doc_no` = '".$doc_no."'
                     AND `doc_type` = '902'
                     AND `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."'";
            $db->query($sql5);
            if($db->num_rows() > 0){
                while($row5 = $db->fetch_object()){
                    $doc_no = $row5->doc_no + 1;
                }
            }

            $prefix = addslashes($_POST['prefix']);
            $suffix = addslashes($_POST['suffix']);
            if($prefix != '' || $suffix != ''){
                $mdoc_no = $prefix.$doc_no.$suffix;
            } else {
                $mdoc_no = addslashes($_POST['mdoc_no']);
            }

            $addSql = "INSERT INTO `cash_transaction` SET
                        `doc_date`                  = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                        `doc_type`                  = '902',
                        `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',
                        `mdoc_no`                   = '".$mdoc_no."',
                        `doc_no`                    = '".$doc_no."',
                        `Transaction_type`          = '2',
                        `id_mst_cashier`            = '".addslashes($_POST['cashier_from'])."',
                        `status`                    = '1',
                        `date_created`              = '".currenDateTime()."',
                        `date_modified`             = '".currenDateTime()."',
                        `created_by`                = '".$_SESSION['userId']."',
                        `modified_by`               = '".$_SESSION['userId']."'";

            executeSql($addSql);
            $lastInsertId = mysqli_insert_id($connNew);

            $addDetailSql = "INSERT INTO `cash_transaction_details` SET
                            `id_cash_transaction` = '".$lastInsertId."',
                            `dated`               = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                            `payment_mode`        = '".addslashes($_POST['payment_mode'])."',
                            `amount`              = '".addslashes($_POST['amount'])."',
                            `remarks`             = '".addslashes($_POST['remarks'])."',
                            `cashier_from`        = '".addslashes($_POST['cashier_from'])."',
                            `cashier_to`          = '".addslashes($_POST['cashier_to'])."',
                            `status`              = '1'";
            executeSql($addDetailSql);

            $_SESSION['successMsg'] = 'Cash Transfer has been added successfully.';
            header("location:manageCashTransfer.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;

        //---------- UPDATE ----------
        } else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){

            $prefix = addslashes($_POST['prefix']);
            $suffix = addslashes($_POST['suffix']);
            if($prefix != '' || $suffix != ''){
                $mdoc_no = $prefix.addslashes($_POST['doc_no']).$suffix;
            } else {
                $mdoc_no = addslashes($_POST['mdoc_no']);
            }

            $cashId = addslashes(encryptor(decrypt, $_POST['eId']));

            $editSql = "UPDATE `cash_transaction` SET
                        `doc_date`                  = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                        `doc_type`                  = '902',
                        `id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',
                        `mdoc_no`                   = '".$mdoc_no."',
                        `doc_no`                    = '".addslashes($_POST['doc_no'])."',
                        `Transaction_type`          = '2',
                        `id_mst_cashier`            = '".addslashes($_POST['cashier_from'])."',
                        `date_modified`             = '".currenDateTime()."',
                        `modified_by`               = '".$_SESSION['userId']."'
                        WHERE `id` = '".$cashId."'";
            executeSql($editSql);

            executeSql("DELETE FROM `cash_transaction_details` WHERE `id_cash_transaction` = '".$cashId."'");

            $addDetailSql = "INSERT INTO `cash_transaction_details` SET
                            `id_cash_transaction` = '".$cashId."',
                            `dated`               = '".date('Y-m-d', strtotime(addslashes($_POST['doc_date'])))."',
                            `payment_mode`        = '".addslashes($_POST['payment_mode'])."',
                            `amount`              = '".addslashes($_POST['amount'])."',
                            `remarks`             = '".addslashes($_POST['remarks'])."',
                            `cashier_from`        = '".addslashes($_POST['cashier_from'])."',
                            `cashier_to`          = '".addslashes($_POST['cashier_to'])."',
                            `status`              = '1'";
            executeSql($addDetailSql);

            $_SESSION['successMsg'] = 'Cash Transfer has been updated successfully.';
            header("location:manageCashTransfer.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;
        }
    }
}

//---------------------------------------------------------------------------------------------------------
// Load existing record
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

$detail               = new stdClass();
$detail->payment_mode = '';
$detail->amount       = '';
$detail->remarks      = '';
$detail->cashier_from = '';
$detail->cashier_to   = '';

$prefix = '';
$suffix = '';

if(!empty($_REQUEST['eId']) && $_REQUEST['action'] == 'edit'){

    $sql = "SELECT * FROM `cash_transaction`
            WHERE `id` = '".addslashes(encryptor(decrypt, $_REQUEST['eId']))."'";
    $db->query($sql);
    if($db->num_rows() > 0){
        $row = $db->fetch_object();
    }

    $sqlDet = "SELECT * FROM `cash_transaction_details`
               WHERE `id_cash_transaction` = '".addslashes(encryptor(decrypt, $_REQUEST['eId']))."'
               LIMIT 1";
    $db->query($sqlDet);
    if($db->num_rows() > 0){
        $detail = $db->fetch_object();
    }
}

// Load prefix/suffix
if($row->id_doc_type_configuration != ''){
    $sql2 = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."`
             WHERE `id` = '".addslashes($row->id_doc_type_configuration)."'";
    $db->query($sql2);
    while($row2 = $db->fetch_object()){
        $prefix = $row2->prefix;
        $suffix = $row2->suffix;
    }
}

// Cashier options
$cashierOptions = array();
$resCashier = selectSql('mst_attributes',
    "WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'
     AND `table_name` = 'cashier'
     AND `field_name` = 'cashier_name'
     AND `status` = '1'",
    'ORDER BY field_value');
while($cr = $db->fetch_object2($resCashier)){
    $cashierOptions[] = $cr;
}
?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container { width: 100% !important; }
    .hr-m { margin-top: 10px; margin-bottom: 10px; }
</style>

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <h5 class="box-title">
            <?php echo $_REQUEST['eId'] == '' ? 'Add' : 'Edit'; ?> Cash Transfer :
            <span style="color:#1296f3"><?php echo htmlspecialchars($row->mdoc_no); ?></span>
        </h5>
        <?php echo breadCrumbs(); ?>
    </section>

    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">

                    <form name="cash_transfer_form" action="" method="post"
                          data-parsley-validate autocomplete="off" id="cash_transfer_form"
                          onsubmit="return validateTransfer()">

                        <input type="hidden" value="<?php echo $_REQUEST['eId']; ?>"  name="eId"     id="eId" />
                        <input type="hidden" value="<?php echo $_GET['submenu']; ?>"  name="submenu" id="submenu" />
                        <input type="hidden" value="<?php echo $_GET['session']; ?>"  name="session" id="session" />
                        <input type="hidden" id="prefix"                    name="prefix"
                               value="<?php echo htmlspecialchars($prefix); ?>">
                        <input type="hidden" id="suffix"                    name="suffix"
                               value="<?php echo htmlspecialchars($suffix); ?>">
                        <input type="hidden" id="doc_no"                    name="doc_no"
                               value="<?php echo htmlspecialchars($row->doc_no); ?>">
                        <input type="hidden" id="id_doc_type_configuration" name="id_doc_type_configuration"
                               value="<?php echo htmlspecialchars($row->id_doc_type_configuration); ?>">
                        <input type="hidden" name="status" value="1">

                        <!-- Messages -->
                        <div class="form-group has-error" align="center">
                            <?php if($_SESSION['errorMsg']){ ?>
                                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
                            <?php unset($_SESSION['errorMsg']); } elseif($_SESSION['successMsg']){ ?>
                                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                            <?php unset($_SESSION['successMsg']); } ?>
                        </div>

                        <div class="box-body">

                            <!-- Header row -->
                            <div class="card text-dark bg-light">
                                <div class="row">

                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
                                        <label>Document Type</label>
                                        <input type="text" class="form-control" value="Cash Transfer"
                                               readonly style="background-color:#e9ecef;">
                                    </div>

                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
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

                                    <!-- Document No display -->
                                    <div class="form-group col-xs-12 col-md-4 col-sm-6">
                                        <label>Document No</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><i class="fa fa-list-ol"></i></div>
                                            <input type="text" class="form-control" id="mdoc_no_display"
                                                   value="<?php echo htmlspecialchars($row->mdoc_no); ?>" readonly>
                                        </div>
                                    </div>

                                    <!-- Manual Doc No -->
                                    <div id="div_manual_doc_no" class="form-group col-xs-12 col-md-4 col-sm-6" style="display:none;">
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

                                </div>
                            </div>

                            <!-- Transfer Details -->
                            <div class="box-body table-responsive2 mt-10" style="padding:0;">
                                <div class="card text-dark bg-light" style="padding:15px;">
                                    <div class="row">
                                        <hr class="br-line" style="margin-top:0;">
                                        <div class="text-center">
                                            <h6 class="tb-heads">Transfer Details</h6>
                                        </div>

                                        <div class="row" style="margin-top:15px;">

                                            <!-- From Cashier -->
                                            <div class="form-group col-xs-12 col-md-3">
                                                <label for="cashier_from">From Cashier <font color="#1296f3">*</font></label>
                                                <select class="form-control select2" name="cashier_from" id="cashier_from"
                                                        required onchange="checkCashiers()">
                                                    <option value="">Select Cashier</option>
                                                    <?php foreach($cashierOptions as $cr){
                                                        $sel = ($detail->cashier_from == $cr->id) ? 'selected="selected"' : '';
                                                        echo '<option '.$sel.' value="'.$cr->id.'">'.htmlspecialchars($cr->field_value).'</option>';
                                                    } ?>
                                                </select>
                                            </div>

                                            <!-- To Cashier -->
                                            <div class="form-group col-xs-12 col-md-3">
                                                <label for="cashier_to">To Cashier <font color="#1296f3">*</font></label>
                                                <select class="form-control select2" name="cashier_to" id="cashier_to"
                                                        required onchange="checkCashiers()">
                                                    <option value="">Select Cashier</option>
                                                    <?php foreach($cashierOptions as $cr){
                                                        $sel = ($detail->cashier_to == $cr->id) ? 'selected="selected"' : '';
                                                        echo '<option '.$sel.' value="'.$cr->id.'">'.htmlspecialchars($cr->field_value).'</option>';
                                                    } ?>
                                                </select>
                                            </div>

                                            <!-- Payment Mode -->
                                            <div class="form-group col-xs-12 col-md-2">
                                                <label for="payment_mode">Payment Mode <font color="#1296f3">*</font></label>
                                                <select class="form-control" name="payment_mode" id="payment_mode" required>
                                                    <option value="">Select Mode</option>
                                                    <option value="1" <?php if($detail->payment_mode=='1') echo 'selected'; ?>>Cash</option>
                                                    <option value="2" <?php if($detail->payment_mode=='2') echo 'selected'; ?>>Online/UPI</option>
                                                    <option value="3" <?php if($detail->payment_mode=='3') echo 'selected'; ?>>Credit/Debit Card</option>
                                                    <option value="4" <?php if($detail->payment_mode=='4') echo 'selected'; ?>>Cheque</option>
                                                </select>
                                            </div>

                                            <!-- Amount -->
                                            <div class="form-group col-xs-12 col-md-2">
                                                <label for="amount">Amount <font color="#1296f3">*</font></label>
                                                <input type="text" autocomplete="off" name="amount" id="amount"
                                                       placeholder="0.00"
                                                       class="form-control discountvalue text-right"
                                                       value="<?php if($_POST) echo htmlspecialchars($_POST['amount']); else echo htmlspecialchars($detail->amount); ?>"
                                                       required style="font-weight:bold;" />
                                            </div>

                                            <!-- Remarks -->
                                            <div class="form-group col-xs-12 col-md-4">
                                                <label for="remarks">Remarks</label>
                                                <input type="text" autocomplete="off" name="remarks" id="remarks"
                                                       placeholder="Enter Remarks" class="form-control"
                                                       value="<?php if($_POST) echo htmlspecialchars($_POST['remarks']); else echo htmlspecialchars($detail->remarks); ?>" />
                                            </div>

                                        </div>

                                        <!-- Same cashier error -->
                                        <div class="row">
                                            <div class="col-md-12">
                                                <span id="cashier_error"
                                                      style="color:red; display:none; font-weight:bold; padding-left:15px;">
                                                    <i class="fa fa-exclamation-triangle"></i>
                                                    'From' and 'To' Cashier cannot be the same person!
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <hr class="br-line mb-10">
                        <div class="box-footer p-0 br-none">
                            <input type="submit" value="Save" class="btn c-btn" name="Save" id="save_btn">
                            <a type="button" class="btn c-btn"
                               onclick='location.replace("manageCashTransfer.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>");'>
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

    function checkCashiers(){
        let from    = document.getElementById('cashier_from').value;
        let to      = document.getElementById('cashier_to').value;
        let errSpan = document.getElementById('cashier_error');
        let saveBtn = document.getElementById('save_btn');

        if(from !== '' && to !== '' && from === to){
            errSpan.style.display = 'block';
            saveBtn.disabled = true;
            return false;
        } else {
            errSpan.style.display = 'none';
            saveBtn.disabled = false;
            return true;
        }
    }

    function validateTransfer(){
        return checkCashiers();
    }

    function hideandshow(){
        var doc_date = document.getElementById('doc_date').value;
        if(doc_date == '') return;

        $.ajax({
            type: 'POST',
            url: '../ajax/CashMemoManage.php',
            data: { doc_type: '902', doc_date: doc_date },
            success: function(data){
                var mydata = JSON.parse(data);
                var prefix = mydata['prefix'] ? mydata['prefix'] : '';
                var suffix = mydata['suffix'] ? mydata['suffix'] : '';
                var doc_no = mydata['doc_no']  ? mydata['doc_no']  : '';

                if(mydata['method'] == '1'){
                    $('#div_manual_doc_no').hide();
                    <?php if($row->id == ''){ ?>
                    $('#mdoc_no_display').val(prefix + doc_no + suffix);
                    document.getElementById('doc_no').value                    = doc_no;
                    document.getElementById('id_doc_type_configuration').value = mydata['id_doc_type_configuration'];
                    <?php } ?>
                    document.getElementById('prefix').value = prefix;
                    document.getElementById('suffix').value = suffix;
                } else {
                    $('#div_manual_doc_no').show();
                    $('#mdoc_no_display').val('');
                    <?php if($row->id == ''){ ?>
                    document.getElementById('doc_no').value                    = doc_no;
                    document.getElementById('id_doc_type_configuration').value = mydata['id_doc_type_configuration'];
                    <?php } ?>
                    document.getElementById('prefix').value = '';
                    document.getElementById('suffix').value = '';
                }
            }
        });
    }

    $(document).on('keypress keyup blur', '.discountvalue', function(event){
        $(this).val($(this).val().replace(/[^0-9\.]/g, ''));
        if((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)){
            event.preventDefault();
        }
    });

    $(document).ready(function(){
        if($.fn.select2){ $('.select2').select2(); }
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