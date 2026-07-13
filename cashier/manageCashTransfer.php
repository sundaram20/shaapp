<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'view');

// ── DELETE ────────────────────────────────────────────────────────────────────
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
    if(checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'delete')){
        $delId = addslashes(encryptor(decrypt, $_REQUEST['delId']));
        executeSql("DELETE FROM `cash_transaction_details` WHERE `id_cash_transaction` = '".$delId."'");
        executeSql("DELETE FROM `cash_transaction` WHERE `id` = '".$delId."'");
        $_SESSION['successMsg'] = 'Cash Transfer has been deleted successfully.';
    }
    header("location:manageCashTransfer.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── SEARCH FILTERS ────────────────────────────────────────────────────────────
$where     = "WHERE ct.`doc_type` = '902' AND ct.`Transaction_type` = '2'";
$dateFilter = '';

if(isset($_GET['search_name']) && $_GET['search_name'] != ''){
    $where .= " AND ct.`mdoc_no` LIKE '%".addslashes($_GET['search_name'])."%'";
}
if(isset($_GET['cashier_from']) && $_GET['cashier_from'] != ''){
    $where .= " AND ctd.`cashier_from` = '".addslashes($_GET['cashier_from'])."'";
}
if(isset($_GET['cashier_to']) && $_GET['cashier_to'] != ''){
    $where .= " AND ctd.`cashier_to` = '".addslashes($_GET['cashier_to'])."'";
}
if(isset($_GET['datefilter']) && $_GET['datefilter'] != ''){
    $dateRange = explode(' to ', $_GET['datefilter']);
    if(count($dateRange) == 2){
        $dateFrom    = date('Y-m-d', strtotime(trim($dateRange[0])));
        $dateTo      = date('Y-m-d', strtotime(trim($dateRange[1])));
        $where      .= " AND ct.`doc_date` BETWEEN '".$dateFrom."' AND '".$dateTo."'";
        $dateFilter  = $_GET['datefilter'];
    }
}

// ── FETCH RECORDS ─────────────────────────────────────────────────────────────
$sql = "SELECT
            ct.id,
            ct.mdoc_no,
            ct.doc_date,
            ct.created_by,
            ctd.amount,
            ctd.payment_mode,
            ctd.remarks,
            ctd.cashier_from,
            ctd.cashier_to,
            ma_from.field_value  AS from_cashier_name,
            ma_to.field_value    AS to_cashier_name,
            u.name               AS created_by_name
        FROM `cash_transaction` ct
        LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
        LEFT JOIN `mst_attributes` ma_from ON ma_from.id = ctd.cashier_from
        LEFT JOIN `mst_attributes` ma_to   ON ma_to.id   = ctd.cashier_to
        LEFT JOIN `".TBL_USERS."` u         ON u.id        = ct.created_by
        $where
        ORDER BY ct.id DESC";

$db->query($sql);
$records    = array();
$totalCount = 0;
while($r = $db->fetch_object()){
    $records[] = $r;
    $totalCount++;
}

// ── Cashier options for filter dropdowns ──────────────────────────────────────
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

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <h6 class="p-0 m-0">
                    <span style="color:#333;">&nbsp;<i class="fa fa-exchange"></i> Cash Transfers</span>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12 dd-f">
                <div class="icn-box"><div class="btn-group"></div></div>
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
                       href="editCashTransfer.php?action=edit&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>">
                       Add New Transfer
                    </a>
                </div>
            </div>

            <form name="searchForm" action="" method="get">
                <input type="hidden" name="submenu" value="<?php echo $_GET['submenu']; ?>">
                <input type="hidden" name="session" value="<?php echo $_GET['session']; ?>">

                <div class="box-body">
                    <div class="row">

                        <!-- Doc No -->
                        <div class="col-md-2 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Document No</label>
                                <input type="text" name="search_name" id="search_name"
                                       value="<?php echo htmlspecialchars($_GET['search_name']); ?>"
                                       class="form-control" placeholder="Search Doc No" />
                            </div>
                        </div>

                        <!-- From Cashier -->
                        <div class="form-group col-xs-6 col-md-3 col-sm-6">
                            <label for="cashier_from">From Cashier</label>
                            <select class="form-control select2" name="cashier_from" id="cashier_from" style="width:100%">
                                <option value="">All Cashiers</option>
                                <?php foreach($cashierOptions as $cr){
                                    $sel = (isset($_GET['cashier_from']) && $_GET['cashier_from'] == $cr->id) ? 'selected' : '';
                                    echo '<option '.$sel.' value="'.$cr->id.'">'.htmlspecialchars($cr->field_value).'</option>';
                                } ?>
                            </select>
                        </div>

                        <!-- To Cashier -->
                        <div class="form-group col-xs-6 col-md-3 col-sm-6">
                            <label for="cashier_to">To Cashier</label>
                            <select class="form-control select2" name="cashier_to" id="cashier_to" style="width:100%">
                                <option value="">All Cashiers</option>
                                <?php foreach($cashierOptions as $cr){
                                    $sel = (isset($_GET['cashier_to']) && $_GET['cashier_to'] == $cr->id) ? 'selected' : '';
                                    echo '<option '.$sel.' value="'.$cr->id.'">'.htmlspecialchars($cr->field_value).'</option>';
                                } ?>
                            </select>
                        </div>

                        <!-- Date Range -->
                        <div class="col-md-3 col-sm-6 col-xs-12 mobile-mb">
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
                        <input name="Search" type="submit" class="btn o-btn" value="Apply Filters" />
                        <a href="manageCashTransfer.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"
                           class="btn c-btn">Clear</a>
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
                        <table id="CashTransferTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="1%">S.No.</th>
                                    <th>Doc No</th>
                                    <th>Date</th>
                                    <th>From Cashier <i class="fas fa-arrow-right text-muted" style="margin:0 8px;"></i> To Cashier</th>
                                    <th>Payment Mode</th>
                                    <th>Amount</th>
                                    <th>Created By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if(!empty($records)){
                                    $i = 1;
                                    foreach($records as $rec){
                                        $pmMap = array('1'=>'Cash','2'=>'Online/UPI','3'=>'Credit/Debit Card','4'=>'Cheque');
                                        $pm    = isset($pmMap[$rec->payment_mode]) ? $pmMap[$rec->payment_mode] : '-';
                                    ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($rec->mdoc_no); ?></td>
                                        <td><?php echo date('d-m-Y', strtotime($rec->doc_date)); ?></td>
                                        <td>
                                            <span><?php echo htmlspecialchars($rec->from_cashier_name); ?></span>
                                            <i class="fas fa-arrow-right text-muted" style="margin:0 8px;"></i>
                                            <span><?php echo htmlspecialchars($rec->to_cashier_name); ?></span>
                                        </td>
                                        <td><?php echo $pm; ?></td>
                                        <td><b style="color:#333;"><?php echo number_format($rec->amount, 2); ?></b></td>
                                        <td><?php echo htmlspecialchars($rec->created_by_name); ?></td>
                                        <td class="d-flex">
                                            <img src="../images/edit.png" style="cursor:pointer;height:20px;" title="View / Edit"
                                                 onClick="window.location.href='editCashTransfer.php?eId=<?php echo encryptor(encrypt, $rec->id); ?>&action=edit&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <img src="../images/preview.png" style="cursor:pointer;height:20px;" title="Page Preview"
                                                 onClick="window.location.href='printCashTransfer.php?eId=<?php echo encryptor(encrypt, $rec->id); ?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete"
                                                 onClick="deletes('<?php echo encryptor(encrypt, $rec->id); ?>')" />
                                        </td>
                                    </tr>
                                    <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="8" class="text-center">No transfer records found.</td></tr>';
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
    function deletes(sid){
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
        function(isConfirm){
            if(isConfirm){
                window.location.href = 'manageCashTransfer.php?delId=' + sid
                    + '&action=delete'
                    + '&submenu=<?php echo $_GET['submenu']; ?>'
                    + '&session=<?php echo $_GET['session']; ?>';
            }
        });
    }

    $(document).ready(function(){
        if($.fn.select2){ $('.select2').select2(); }

        if($('#dateRangeReport').daterangepicker){
            $('#dateRangeReport').daterangepicker({
                autoUpdateInput: false,
                locale: { format: 'DD-MM-YYYY', separator: ' to ', cancelLabel: 'Clear' }
            });
            $('#dateRangeReport').on('apply.daterangepicker', function(ev, picker){
                $(this).val(picker.startDate.format('DD-MM-YYYY') + ' to ' + picker.endDate.format('DD-MM-YYYY'));
            });
            $('#dateRangeReport').on('cancel.daterangepicker', function(){
                $(this).val('');
            });
        }
    });
</script>

<?php include_once("../includes/footer.php"); ?>