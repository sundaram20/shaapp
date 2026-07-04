<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'view');

// ── DELETE ──────────────────────────────────────────────────────────────────
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
    if(checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'delete')){
        $delId = addslashes(encryptor(decrypt, $_REQUEST['delId']));

        executeSql("DELETE FROM `cash_transaction_details` WHERE `id_cash_transaction` = '".$delId."'");
        executeSql("DELETE FROM `cash_transaction` WHERE `id` = '".$delId."'");

        $_SESSION['successMsg'] = 'Cash Memo has been deleted successfully.';
    }
    header("location:manageCashMemo.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── SEARCH FILTERS ───────────────────────────────────────────────────────────
$where      = "WHERE ct.`doc_type` = '901' AND ct.`Transaction_type` = '1'";
$dateFilter = '';

// Document No search
if($_GET['search_name'] != ''){
    $where .= " AND ct.`mdoc_no` LIKE '%".addslashes($_GET['search_name'])."%'";
}

// Cashier filter
if($_GET['id_mst_cashier'] != ''){
    $where .= " AND ct.`id_mst_cashier` = '".addslashes($_GET['id_mst_cashier'])."'";
}

// Ledger filter (on details table)
if($_GET['ledger'] != ''){
    $where .= " AND ctd.`id_mst_charges_expenses_ledger` = '".addslashes($_GET['ledger'])."'";
}

// Date range filter
if($_GET['datefilter'] != ''){
    $dateRange = explode(' to ', $_GET['datefilter']);
    if(count($dateRange) == 2){
        $dateFrom = date('Y-m-d', strtotime(trim($dateRange[0])));
        $dateTo   = date('Y-m-d', strtotime(trim($dateRange[1])));
        $where   .= " AND ct.`doc_date` BETWEEN '".$dateFrom."' AND '".$dateTo."'";
        $dateFilter = $_GET['datefilter'];
    }
}

// ── FETCH RECORDS ────────────────────────────────────────────────────────────
// Join cash_transaction with details and ledger name, group to get total amount per memo
$sql = "SELECT 
            ct.id,
            ct.mdoc_no,
            ct.doc_date,
            ct.id_mst_cashier,
            ma.field_value AS cashier_name,
            GROUP_CONCAT(mc.name ORDER BY ctd.id SEPARATOR ', ') AS ledger_names,
            GROUP_CONCAT(ctd.payment_mode ORDER BY ctd.id SEPARATOR ', ') AS payment_modes,
            SUM(ctd.amount) AS total_amount
        FROM `cash_transaction` ct
        LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
        LEFT JOIN `mst_charges` mc ON mc.id = ctd.id_mst_charges_expenses_ledger
        LEFT JOIN `mst_attributes` ma ON ma.id = ct.id_mst_cashier
        $where
        GROUP BY ct.id
        ORDER BY ct.id DESC";

$db->query($sql);
$records    = array();
$totalCount = 0;
while($r = $db->fetch_object()){
    $records[] = $r;
    $totalCount++;
}
?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <h6 class="p-0 m-0">
                    <span style="color:#333;">&nbsp;<i class="fa fa-money"></i> Cash Memo</span>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12 dd-f">
                <div class="icn-box">
                    <div class="btn-group"></div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12 tb-br">
                <?php echo breadCrumbs(); ?>
            </div>
        </div>
    </section>

    <section class="content">

        <!-- Messages -->
        <div class="form-group has-error" align="center">
            <?php if($_SESSION['errorMsg']){ ?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
            <?php unset($_SESSION['errorMsg']); } elseif($_SESSION['successMsg']){ ?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
            <?php unset($_SESSION['successMsg']); } ?>
        </div>

        <div class="box box-default">
            <div class="box-header">
                <h6 class="box-title">Search <small>Records:(<?php echo $totalCount; ?>) &nbsp;</small></h6>
                <div class="btn-group pull-right">
                    <a type="button" class="btn n-btn"
                       href="editCashMemo.php?action=edit&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>">
                       Add Cash Memo
                    </a>
                </div>
            </div>

            <form name="searchForm" action="" method="get">
                <input type="hidden" name="submenu" value="<?php echo $_GET['submenu']; ?>">
                <input type="hidden" name="session" value="<?php echo $_GET['session']; ?>">

                <div class="box-body">
                    <div class="row">

                        <!-- Document No -->
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Document No</label>
                                <input type="text" name="search_name" id="search_name"
                                       value="<?php echo htmlspecialchars($_GET['search_name']); ?>"
                                       class="form-control" placeholder="Search Document No" />
                            </div>
                        </div>

                        <!-- Ledger -->
                        <div class="form-group col-xs-6 col-md-2 col-sm-6">
                            <label for="ledger">Ledger</label>
                            <select class="form-control select2" name="ledger" id="ledger" style="width:100%">
                                <option value="">All Ledgers</option>
                                <?php
                                    $resLedger = selectSql('mst_charges',
                                        "WHERE `charges_account` = '4' AND `status` = '1'",
                                        'ORDER BY `name`');
                                    while($lr = $db->fetch_object2($resLedger)){
                                        $sel = ($_GET['ledger'] == $lr->id) ? 'selected' : '';
                                        echo '<option '.$sel.' value="'.$lr->id.'">'.htmlspecialchars($lr->name).'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Cashier -->
                        <div class="form-group col-xs-6 col-md-2 col-sm-6">
                            <label for="id_mst_cashier">Cashier</label>
                            <select class="form-control select2" name="id_mst_cashier" id="id_mst_cashier" style="width:100%">
                                <option value="">All Cashiers</option>
                                <?php
                                    $resCashier = selectSql('mst_attributes',
                                        "WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'
                                         AND `table_name` = 'cashier'
                                         AND `field_name` = 'cashier_name'
                                         AND `status` = '1'",
                                        'ORDER BY `field_value`');
                                    while($cr = $db->fetch_object2($resCashier)){
                                        $sel = ($_GET['id_mst_cashier'] == $cr->id) ? 'selected' : '';
                                        echo '<option '.$sel.' value="'.$cr->id.'">'.htmlspecialchars($cr->field_value).'</option>';
                                    }
                                ?>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-2 col-sm-6 col-xs-12 mobile-mb">
                            <div class="form-group">
                                <label>Period</label>
                                <input type="text" class="form-control pull-right"
                                       placeholder="Select From - To"
                                       name="datefilter" id="dateRangeReport"
                                       value="<?php echo htmlspecialchars($dateFilter); ?>"
                                       autocomplete="off">
                            </div>
                        </div>

                    </div>

                    <div class="box-footer pt-0 pl-0 br-none">
                        <input name="Search" type="submit" class="btn o-btn" value="Apply" />
                        <a href="manageCashMemo.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"
                           class="btn c-btn">Clear</a>
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
                        <table id="myTable" class="table table-striped table-bordered" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="1%">S.No.</th>
                                    <th>Document No</th>
                                    <th>Cashier</th>
                                    <th>Ledger</th>
                                    <th>Payment Mode</th>
                                    <th>Date</th>
                                    <th>Total Amount</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($records)){
                                    $i = 1;
                                    foreach($records as $rec){

                                        // Payment mode labels
                                        $pmodes = explode(', ', $rec->payment_modes);
                                        $pmLabels = array();
                                        foreach($pmodes as $pm){
                                            if($pm == '1')      $pmLabels[] = 'Cash';
                                            elseif($pm == '2')  $pmLabels[] = 'Online/UPI';
                                            elseif($pm == '3')  $pmLabels[] = 'Credit/Debit Card';
                                            elseif($pm == '4')  $pmLabels[] = 'Cheque';
                                            else                $pmLabels[] = $pm;
                                        }
                                        $pmDisplay = implode(', ', array_unique($pmLabels));
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($rec->mdoc_no); ?></td>
                                        <td><?php echo htmlspecialchars($rec->cashier_name); ?></td>
                                        <td><?php echo htmlspecialchars($rec->ledger_names); ?></td>
                                        <td><?php echo $pmDisplay; ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($rec->doc_date)); ?></td>
                                        <td><?php echo number_format($rec->total_amount, 2); ?></td>
                                        <td class="d-flex">
                                            <img src="../images/edit.png" style="cursor:pointer;height:20px;"
                                                 title="View / Edit"
                                                 onClick="window.location.href='editCashMemo.php?eId=<?php echo encryptor(encrypt, $rec->id); ?>&action=edit&page=<?php echo $_REQUEST['page']; ?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <img src="../images/preview.png" style="cursor:pointer;height:20px;"
                                                 title="Page Preview"
                                                 onClick="window.location.href='printCashMemo.php?eId=<?php echo encryptor(encrypt, $rec->id); ?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <img src="../images/close.png" style="cursor:pointer;height:15px;"
                                                 title="Delete"
                                                 onClick="deletes('<?php echo encryptor(encrypt, $rec->id); ?>')" />
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="text-center">No records found.</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script type="text/javascript">
    // Date range picker
    $(document).ready(function(){
        if($.fn.select2){
            $('.select2').select2();
        }
        if(typeof daterangepicker !== 'undefined' || $('#dateRangeReport').daterangepicker){
            $('#dateRangeReport').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    format: 'DD-MM-YYYY',
                    separator: ' to ',
                    cancelLabel: 'Clear'
                }
            });
            $('#dateRangeReport').on('apply.daterangepicker', function(ev, picker){
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
            });
            $('#dateRangeReport').on('cancel.daterangepicker', function(){
                $(this).val('');
            });
        }

    });

    // Delete with confirmation
    function deletes(sid){
        swal({
            title: "Are you sure?",
            text: "Delete this Cash Memo?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#1296f3',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if(isConfirm){
                window.location.href = 'manageCashMemo.php?delId=' + sid
                    + '&action=delete'
                    + '&submenu=<?php echo $_GET['submenu']; ?>'
                    + '&session=<?php echo $_GET['session']; ?>';
            }
        });
    }
</script>

<?php include_once("../includes/footer.php"); ?>