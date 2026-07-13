<?php
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'view');

define('FO_START_DATE', '2026-07-14');

// ── DATE RANGE ────────────────────────────────────────────────────────────────
$dateFilter = isset($_GET['datefilter']) ? $_GET['datefilter'] : '';
$dateFrom   = '';
$dateTo     = '';

if($dateFilter != ''){
    $parts = explode(' to ', $dateFilter);
    if(count($parts) == 2){
        $dateFrom = date('Y-m-d', strtotime(trim($parts[0])));
        $dateTo   = date('Y-m-d', strtotime(trim($parts[1])));
    }
}
if($dateFrom == '' || $dateTo == ''){
    $dateFrom   = date('Y-m-01');
    $dateTo     = date('Y-m-d');
    $dateFilter = date('01-m-Y').' to '.date('d-m-Y');
}

// ── OPENING BALANCE (all transactions before dateFrom, from FO_START_DATE) ───
// = previous day's closing balance

$db->query("SELECT COALESCE(SUM(amount),0) AS total FROM `fo_receipt`
            WHERE `payment_mode`='CASH'
            AND `doc_date` >= '".FO_START_DATE."'
            AND `doc_date` < '".$dateFrom."'");
$foReceiptOpen = floatval($db->fetch_object()->total);

$db->query("SELECT COALESCE(SUM(ctd.amount),0) AS total
            FROM `cash_transaction` ct
            LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
            WHERE ct.`doc_type`='901' AND ct.`Transaction_type`='1'
            AND ct.`doc_date` < '".$dateFrom."'");
$cashMemoOpen = floatval($db->fetch_object()->total);

$db->query("SELECT COALESCE(SUM(ctd.amount),0) AS total
            FROM `cash_transaction` ct
            LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
            WHERE ct.`doc_type`='902' AND ct.`Transaction_type`='2'
            AND ct.`doc_date` < '".$dateFrom."'");
$transferOpen = floatval($db->fetch_object()->total);

$openingBalance = $foReceiptOpen - $cashMemoOpen - $transferOpen;

// ── PERIOD TRANSACTIONS ───────────────────────────────────────────────────────

$db->query("SELECT COALESCE(SUM(amount),0) AS total FROM `fo_receipt`
            WHERE `payment_mode`='CASH'
            AND `doc_date` >= '".$dateFrom."'
            AND `doc_date` <= '".$dateTo."'");
$receiptAmount = floatval($db->fetch_object()->total);

$db->query("SELECT COALESCE(SUM(ctd.amount),0) AS total
            FROM `cash_transaction` ct
            LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
            WHERE ct.`doc_type`='901' AND ct.`Transaction_type`='1'
            AND ct.`doc_date` >= '".$dateFrom."'
            AND ct.`doc_date` <= '".$dateTo."'");
$cashMemoPeriod = floatval($db->fetch_object()->total);

$db->query("SELECT COALESCE(SUM(ctd.amount),0) AS total
            FROM `cash_transaction` ct
            LEFT JOIN `cash_transaction_details` ctd ON ctd.id_cash_transaction = ct.id
            WHERE ct.`doc_type`='902' AND ct.`Transaction_type`='2'
            AND ct.`doc_date` >= '".$dateFrom."'
            AND ct.`doc_date` <= '".$dateTo."'");
$transferPeriod = floatval($db->fetch_object()->total);

$expenseTransfer = $cashMemoPeriod + $transferPeriod;
$balanceAmount   = $openingBalance + $receiptAmount - $expenseTransfer;

// ── DAILY BREAKDOWN ───────────────────────────────────────────────────────────
$sqlDaily = "SELECT
                dates.d                           AS report_date,
                COALESCE(fo.fo_cash, 0)           AS fo_cash,
                COALESCE(memo.memo_total, 0)      AS memo_total,
                COALESCE(tr.transfer_total, 0)    AS transfer_total
             FROM (
                SELECT DATE(doc_date) AS d FROM fo_receipt
                WHERE doc_date >= '".$dateFrom."' AND doc_date <= '".$dateTo."'
                UNION
                SELECT DATE(doc_date) AS d FROM cash_transaction
                WHERE doc_date >= '".$dateFrom."' AND doc_date <= '".$dateTo."'
             ) AS dates
             LEFT JOIN (
                SELECT DATE(doc_date) AS d, SUM(amount) AS fo_cash
                FROM fo_receipt
                WHERE payment_mode = 'CASH'
                AND doc_date >= '".$dateFrom."' AND doc_date <= '".$dateTo."'
                GROUP BY DATE(doc_date)
             ) fo ON fo.d = dates.d
             LEFT JOIN (
                SELECT DATE(ct.doc_date) AS d, SUM(ctd.amount) AS memo_total
                FROM cash_transaction ct
                LEFT JOIN cash_transaction_details ctd ON ctd.id_cash_transaction = ct.id
                WHERE ct.doc_type = '901' AND ct.Transaction_type = '1'
                AND ct.doc_date >= '".$dateFrom."' AND ct.doc_date <= '".$dateTo."'
                GROUP BY DATE(ct.doc_date)
             ) memo ON memo.d = dates.d
             LEFT JOIN (
                SELECT DATE(ct.doc_date) AS d, SUM(ctd.amount) AS transfer_total
                FROM cash_transaction ct
                LEFT JOIN cash_transaction_details ctd ON ctd.id_cash_transaction = ct.id
                WHERE ct.doc_type = '902' AND ct.Transaction_type = '2'
                AND ct.doc_date >= '".$dateFrom."' AND ct.doc_date <= '".$dateTo."'
                GROUP BY DATE(ct.doc_date)
             ) tr ON tr.d = dates.d
             ORDER BY dates.d ASC";
$db->query($sqlDaily);
$dailyRows = array();
while($dr = $db->fetch_object()){
    $dailyRows[] = $dr;
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
                    <span style="color:#333;">&nbsp;<i class="fa fa-bar-chart"></i> Cash Balance Report</span>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12"></div>
            <div class="col-md-4 col-xs-12 tb-br">
                <?php echo breadCrumbs(); ?>
            </div>
        </div>
    </section>

    <section class="content">

        <!-- Filter -->
        <div class="box box-default">
            <div class="box-header">
                <h6 class="box-title">Search &nbsp;<small>Period: <?php echo htmlspecialchars($dateFilter); ?></small></h6>
                <div class="pull-right no-print">
                    <button onclick="window.print()" class="btn n-btn btn-sm">
                        <i class="fa fa-print"></i> Print
                    </button>
                    &nbsp;
                    <button onclick="exportToExcel()" class="btn n-btn btn-sm">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </button>
                </div>
            </div>
            <form name="searchForm" action="" method="get">
                <input type="hidden" name="submenu" value="<?php echo $_GET['submenu']; ?>">
                <input type="hidden" name="session" value="<?php echo $_GET['session']; ?>">
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label>Period <font color="#1296f3">*</font></label>
                                <input type="text" class="form-control"
                                       placeholder="Select From - To"
                                       name="datefilter" id="dateRangeReport"
                                       value="<?php echo htmlspecialchars($dateFilter); ?>"
                                       autocomplete="off" required>
                            </div>
                        </div>
                    </div>
                    <div class="box-footer pt-0 pl-0 br-none">
                        <input name="Search" type="submit" class="btn o-btn" value="Apply" />
                        <a href="reportCashBalance.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"
                           class="btn c-btn">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Summary Cards -->
        <div class="row">

            <!-- Opening Balance -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-left:4px solid #1296f3;">
                    <span class="info-box-icon" style="background:#1296f3;"><i class="fa fa-history"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Opening Balance</span>
                        <span class="info-box-number">&#8377; <?php echo number_format($openingBalance, 2); ?></span>
                        <span class="progress-description" style="font-size:10px;">
                            Balance as of <?php echo date('d-m-Y', strtotime($dateFrom.' -1 day')); ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Receipt Amount -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-left:4px solid #00a65a;">
                    <span class="info-box-icon" style="background:#00a65a;"><i class="fa fa-plus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Receipt Amount</span>
                        <span class="info-box-number">&#8377; <?php echo number_format($receiptAmount, 2); ?></span>
                        <span class="progress-description" style="font-size:10px;">
                            FO Cash receipts in period
                        </span>
                    </div>
                </div>
            </div>

            <!-- Expense & Transfer combined -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-left:4px solid #dd4b39;">
                    <span class="info-box-icon" style="background:#dd4b39;"><i class="fa fa-minus-circle"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Expense &amp; Transfer</span>
                        <span class="info-box-number">&#8377; <?php echo number_format($expenseTransfer, 2); ?></span>
                        <span class="progress-description" style="font-size:10px;">
                            <span>Expense: &#8377;<?php echo number_format($cashMemoPeriod, 2); ?></span>
                            &nbsp;|&nbsp;
                            <span>Transfer: &#8377;<?php echo number_format($transferPeriod, 2); ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Balance -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box" style="border-left:4px solid <?php echo $balanceAmount >= 0 ? '#00a65a' : '#dd4b39'; ?>;">
                    <span class="info-box-icon" style="background:<?php echo $balanceAmount >= 0 ? '#00a65a' : '#dd4b39'; ?>;"><i class="fa fa-balance-scale"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Balance</span>
                        <span class="info-box-number" style="<?php echo $balanceAmount >= 0 ? 'color:#00a65a;' : 'color:#dd4b39;'; ?>">
                            &#8377; <?php echo number_format($balanceAmount, 2); ?>
                        </span>
                        <span class="progress-description" style="font-size:10px;">
                            Opening + Receipts - Expense &amp; Transfer
                        </span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Daily Breakdown Table -->
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header table-h text-center">
                        <h3 class="box-title">Daily Breakdown</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="CashBalanceTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th class="text-right">FO Cash Receipt</th>
                                    <th class="text-right">Expense (Cash Memo)</th>
                                    <th class="text-right">Transfer</th>
                                    <th class="text-right">Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $grandFo       = 0;
                                $grandMemo     = 0;
                                $grandTransfer = 0;
                                $grandNet      = 0;
                                $runningBal    = $openingBalance;

                                if(!empty($dailyRows)){
                                    foreach($dailyRows as $dr){
                                        $netDay        = floatval($dr->fo_cash)
                                                       - floatval($dr->memo_total)
                                                       - floatval($dr->transfer_total);
                                        $runningBal   += $netDay;
                                        $grandFo       += floatval($dr->fo_cash);
                                        $grandMemo     += floatval($dr->memo_total);
                                        $grandTransfer += floatval($dr->transfer_total);
                                        $grandNet       = $runningBal;
                                        $balColor       = $runningBal >= 0 ? 'color:green;' : 'color:red;';
                                    ?>
                                    <tr>
                                        <td><?php echo date('d-m-Y', strtotime($dr->report_date)); ?></td>
                                        <td class="text-right" style="color:#00a65a;"><?php echo number_format($dr->fo_cash, 2); ?></td>
                                        <td class="text-right" style="color:#dd4b39;"><?php echo number_format($dr->memo_total, 2); ?></td>
                                        <td class="text-right" style="color:#f39c12;"><?php echo number_format($dr->transfer_total, 2); ?></td>
                                        <td class="text-right font-bold" style="<?php echo $balColor; ?>">
                                            <?php echo number_format($runningBal, 2); ?>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr><td colspan="5" class="text-center">No transactions found for this period.</td></tr>
                                <?php } ?>
                            </tbody>
                            <?php if(!empty($dailyRows)){ ?>
                            <tfoot>
                                <tr style="font-weight:bold; background:#f5f5f5;">
                                    <td><strong>Period Total</strong></td>
                                    <td class="text-right" style="color:#00a65a;"><?php echo number_format($grandFo, 2); ?></td>
                                    <td class="text-right" style="color:#dd4b39;"><?php echo number_format($grandMemo, 2); ?></td>
                                    <td class="text-right" style="color:#f39c12;"><?php echo number_format($grandTransfer, 2); ?></td>
                                    <td class="text-right"></td>
                                </tr>
                                <tr style="font-weight:bold; background:#eaf4ff;">
                                    <td colspan="4" class="text-right">Opening Balance</td>
                                    <td class="text-right">&#8377; <?php echo number_format($openingBalance, 2); ?></td>
                                </tr>
                                <tr style="font-weight:bold; background:#eaf4ff;">
                                    <td colspan="4" class="text-right">+ Period Receipts</td>
                                    <td class="text-right" style="color:#00a65a;">&#8377; <?php echo number_format($receiptAmount, 2); ?></td>
                                </tr>
                                <tr style="font-weight:bold; background:#eaf4ff;">
                                    <td colspan="4" class="text-right">- Expense &amp; Transfer</td>
                                    <td class="text-right" style="color:#dd4b39;">&#8377; <?php echo number_format($expenseTransfer, 2); ?></td>
                                </tr>
                                <tr style="font-weight:bold; background:#d4edda; font-size:14px;">
                                    <td colspan="4" class="text-right">Balance</td>
                                    <td class="text-right" style="<?php echo $balanceAmount >= 0 ? 'color:green;' : 'color:red;'; ?>">
                                        &#8377; <?php echo number_format($balanceAmount, 2); ?>
                                    </td>
                                </tr>
                            </tfoot>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<style>
    @media print {
        .no-print, .sidebar, .main-header, .content-header { display: none !important; }
        .content-wrapper { margin: 0 !important; }
        .info-box { page-break-inside: avoid; }
    }
</style>

<script type="text/javascript">
    $(document).ready(function(){
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

    function exportToExcel(){
        if(typeof XLSX === 'undefined'){
            var script = document.createElement('script');
            script.src = 'https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js';
            script.onload = function(){ generateExcel(); };
            document.head.appendChild(script);
        } else {
            generateExcel();
        }
    }

    function generateExcel(){
        var wb = XLSX.utils.book_new();

        // Summary sheet
        var summaryData = [
            ['Cash Balance Report'],
            ['Period: <?php echo addslashes($dateFilter); ?>'],
            [''],
            ['', 'Amount (INR)'],
            ['Opening Balance (as of <?php echo date('d-m-Y', strtotime($dateFrom.' -1 day')); ?>)', <?php echo $openingBalance; ?>],
            ['Receipt Amount (period)',    <?php echo $receiptAmount; ?>],
            ['Expense & Transfer (period)',<?php echo $expenseTransfer; ?>],
            ['  Cash Memos',              <?php echo $cashMemoPeriod; ?>],
            ['  Transfers',               <?php echo $transferPeriod; ?>],
            [''],
            ['Balance',                   <?php echo $balanceAmount; ?>],
        ];
        var wsSummary = XLSX.utils.aoa_to_sheet(summaryData);
        wsSummary['!cols'] = [{wch:45},{wch:18}];
        XLSX.utils.book_append_sheet(wb, wsSummary, 'Summary');

        // Daily Breakdown sheet
        var dailyData = [
            ['Cash Balance Report - Daily Breakdown'],
            ['Period: <?php echo addslashes($dateFilter); ?>'],
            ['Opening Balance: <?php echo $openingBalance; ?>'],
            [''],
            ['Date', 'FO Cash Receipt', 'Expense (Cash Memo)', 'Transfer', 'Running Balance']
        ];

        <?php
        $runBal          = $openingBalance;
        $grandFoJs       = 0;
        $grandMemoJs     = 0;
        $grandTransferJs = 0;
        foreach($dailyRows as $dr){
            $netDay          = floatval($dr->fo_cash) - floatval($dr->memo_total) - floatval($dr->transfer_total);
            $runBal         += $netDay;
            $grandFoJs      += floatval($dr->fo_cash);
            $grandMemoJs    += floatval($dr->memo_total);
            $grandTransferJs+= floatval($dr->transfer_total);
            echo "dailyData.push(['"
                .date('d-m-Y', strtotime($dr->report_date))."',"
                .floatval($dr->fo_cash).","
                .floatval($dr->memo_total).","
                .floatval($dr->transfer_total).","
                .$runBal."]);";
        }
        ?>

        dailyData.push(['Period Total', <?php echo $grandFoJs; ?>, <?php echo $grandMemoJs; ?>, <?php echo $grandTransferJs; ?>, '']);
        dailyData.push(['']);
        dailyData.push(['Opening Balance',        '', '', '', <?php echo $openingBalance; ?>]);
        dailyData.push(['+ Period Receipts',      '', '', '', <?php echo $receiptAmount; ?>]);
        dailyData.push(['- Expense & Transfer',   '', '', '', <?php echo $expenseTransfer; ?>]);
        dailyData.push(['Balance',                '', '', '', <?php echo $balanceAmount; ?>]);

        var wsDaily = XLSX.utils.aoa_to_sheet(dailyData);
        wsDaily['!cols'] = [{wch:14},{wch:18},{wch:22},{wch:14},{wch:18}];
        XLSX.utils.book_append_sheet(wb, wsDaily, 'Daily Breakdown');

        var fileName = 'Cash_Balance_Report_<?php echo str_replace([' ', '-', '/'], '_', $dateFilter); ?>.xlsx';
        XLSX.writeFile(wb, fileName);
    }
</script>

<?php include_once("../includes/footer.php"); ?>