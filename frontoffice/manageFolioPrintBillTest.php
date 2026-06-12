<?php
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');

$statuscase = "";
$searchDocumentType = "";
$dateCondition = "";
$searchfolio_no = "";

/* ================= FILTER ================= */

// Default Date
if (!isset($_REQUEST["datefilter"]) && !isset($_REQUEST["search_name"]) && !isset($_REQUEST["folio_no"])) {
    $defaultStartDate = date("Y-m-d", strtotime("-1 day"));
    $defaultEndDate   = date("Y-m-d");
    $dateCondition = " AND DATE(doc_date) BETWEEN '$defaultStartDate' AND '$defaultEndDate'";
}

// Date filter
if (!empty($_REQUEST["datefilter"])) {
    $DateExplode = explode(" to ", $_REQUEST["datefilter"]);
    $startDate = date("Y-m-d", strtotime($DateExplode[0]));
    $endDate   = date("Y-m-d", strtotime($DateExplode[1]));
    $dateCondition = " AND DATE(doc_date) BETWEEN '$startDate' AND '$endDate'";
}

// FO No
if (!empty($_REQUEST["search_name"])) {
    $searchDocumentType = " AND mdoc_no LIKE '%" . addslashes($_REQUEST["search_name"]) . "%'";
}

// Folio No
if (!empty($_REQUEST["folio_no"])) {
    $searchfolio_no = " AND mdoc_no LIKE '%" . addslashes($_REQUEST["folio_no"]) . "%'";
}

/* ================= EXPORT ================= */

if(isset($_REQUEST['export_excel'])){

    ob_clean();

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=folio_report_".date('Ymd').".xls");

    $SQL = "SELECT * FROM fo_folio WHERE id!=0 $statuscase $dateCondition $searchDocumentType $searchfolio_no ORDER BY id DESC";
    $result = mysqli_query($connNew, $SQL);

    echo "S.No\tFolio No\tDate\tFO Bill No\tGuest Name\tFolio Amount\tBalance\tReceived\n";

    $i=1;

    while($row=mysqli_fetch_object($result)){

        $id_folio = (int)$row->id;

        // TOTAL
        $sqlTotal = "
        SELECT SUM(amount) AS total FROM (
            SELECT (tariff_price_per_day_per_room + tax_per_day_per_room) AS amount
            FROM ".FO_RESERVATIONS_DETAILS."
            WHERE id_fo_folio_to = '$id_folio'

            UNION ALL

            SELECT grant_total_amount AS amount
            FROM pos_purch
            WHERE id_fo_folio_to = '$id_folio' AND cancelled != 1

            UNION ALL

            SELECT total AS amount
            FROM fo_reservations_addons_details
            WHERE id_fo_folio_to = '$id_folio'
        ) AS combined";

        $resTotal = mysqli_query($connNew, $sqlTotal);
        $rowTotal = mysqli_fetch_assoc($resTotal);
        $CurrentTotal = $rowTotal['total'] ?? 0;

        // RECEIVED
        $receipt_amount = round(selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$row->id.'"'),2);

        // BALANCE
        $BalanceAmount = round($CurrentTotal - $receipt_amount);

        // BILL
        $bill_no = selectColumn(FO_BILL,'mdoc_no'," WHERE id='".$row->id_fo_bill."'");

        // GUEST
        $GuestName = selectColumn(TBL_GUEST,'first_name'," WHERE id='".$row->id_mst_guest."'");

        echo $i++."\t".
            $row->mdoc_no."\t".
            date('d-m-Y',strtotime($row->doc_date))."\t".
            $bill_no."\t".
            $GuestName."\t".
            $CurrentTotal."\t".
            $BalanceAmount."\t".
            $receipt_amount."\n";
    }

    exit;
}
?>

<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<?php
$SQL = "SELECT * FROM fo_folio WHERE id!=0 $statuscase $dateCondition $searchDocumentType $searchfolio_no ORDER BY id DESC";
$SqlKotList = mysqli_query($connNew, $SQL);
?>

<div class="content-wrapper">
<section class="content">

<div class="box">
<div class="box-header">
<h4>Folio List</h4>
</div>

<form method="get">
<div class="row" style="padding:10px;">

<div class="col-md-2">
<label>Folio No</label>
<input type="text" name="folio_no" class="form-control" value="<?= $_REQUEST['folio_no'] ?? '' ?>">
</div>

<div class="col-md-2">
<label>FO No</label>
<input type="text" name="search_name" class="form-control" value="<?= $_REQUEST['search_name'] ?? '' ?>">
</div>

<div class="col-md-3">
<label>Date</label>
<input type="text" name="datefilter" class="form-control"
value="<?= $_REQUEST['datefilter'] ?? date('d-m-Y',strtotime('-1 day')).' to '.date('d-m-Y') ?>">
</div>

<div class="col-md-3" style="margin-top:25px;">
<button type="submit" class="btn btn-primary">Apply</button>
<button type="submit" name="export_excel" value="1" class="btn btn-success">Export Excel</button>
</div>

</div>
</form>

<div class="box-body table-responsive">

<table id="myTable" class="table table-bordered table-striped">
<thead>
<tr>
<th>S.No</th>
<th>Folio No</th>
<th>Date</th>
<th>FO Bill No</th>
<th>Guest</th>
<th>Folio Amount</th>
<th>Balance</th>
<th>Received</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php 
$i=1; 
while($row=mysqli_fetch_object($SqlKotList)){ 

    $id_folio = (int)$row->id;

    // TOTAL
    $sqlTotal = "
    SELECT SUM(amount) AS total FROM (
        SELECT (tariff_price_per_day_per_room + tax_per_day_per_room) AS amount
        FROM ".FO_RESERVATIONS_DETAILS."
        WHERE id_fo_folio_to = '$id_folio'

        UNION ALL

        SELECT grant_total_amount AS amount
        FROM pos_purch
        WHERE id_fo_folio_to = '$id_folio' AND cancelled != 1

        UNION ALL

        SELECT total AS amount
        FROM fo_reservations_addons_details
        WHERE id_fo_folio_to = '$id_folio'
    ) AS combined";

    $resTotal = mysqli_query($connNew, $sqlTotal);
    $rowTotal = mysqli_fetch_assoc($resTotal);
    $CurrentTotal = $rowTotal['total'] ?? 0;

    // RECEIVED
    $receipt_amount = round(selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$row->id.'"'),2);

    // BALANCE
    $BalanceAmount = round($CurrentTotal - $receipt_amount);

    // BILL
    $bill_no = selectColumn(FO_BILL,'mdoc_no'," WHERE id='".$row->id_fo_bill."'");

    // GUEST
    $GuestName = selectColumn(TBL_GUEST,'first_name'," WHERE id='".$row->id_mst_guest."'");

    // PRINT URL
    $frontbillprint = selectColumn('mst_shops','folio_url'," WHERE id='".$_SESSION['shop']."'");
    if($frontbillprint==''){
        $frontbillprint='folioformat1.php';
    }
?>
<tr>
<td><?= $i++ ?></td>
<td><?= $row->mdoc_no ?></td>
<td><?= date('d-m-Y',strtotime($row->doc_date)) ?></td>
<td><?= $bill_no ?></td>
<td><?= $GuestName ?></td>

<td><?= number_format($CurrentTotal,2) ?></td>
<td><?= number_format($BalanceAmount,2) ?></td>
<td><?= number_format($receipt_amount,2) ?></td>

<td>
<a target="_blank" 
href="<?= $frontbillprint ?>?idfobill=<?=encryptor(encrypt,$row->id_fo_bill)?>&id_folio=<?=encryptor(encrypt,$row->id)?>">
<img src="../images/preview.png" style="height:20px;" title="Preview">
</a>
</td>

</tr>
<?php } ?>
</tbody>

</table>

</div>
</div>

</section>
</div>

<?php include_once("../includes/footer.php"); ?>

<script>
$(document).ready(function() {
    $('#myTable').DataTable();
});
</script>