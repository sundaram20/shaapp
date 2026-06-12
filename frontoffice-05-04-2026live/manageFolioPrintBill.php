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

    //echo "S.No\tFolio No\tDate\tFO Bill No\tGuest Name\tFolio Amount\tBalance\tReceived\n";
	
	echo "S.No\tFolio No\tDate\tFO Bill No\tPayment Mode\tBill To\tGuest Name\tEmail\tMobile\tCheckin\tCheckout\tFolio Amount\tBalance\tReceived\n";

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
		$id_reservations = selectColumn(FO_BILL,'id_reservations'," WHERE id='".$row->id_fo_bill."'");

        // GUEST
        $GuestName = selectColumn(TBL_GUEST,'first_name'," WHERE id='".$row->id_mst_guest."'");
		$Email = selectColumn(TBL_GUEST,'email'," WHERE id='".$row->id_mst_guest."'");
	$mobile = selectColumn(TBL_GUEST,'primary_mobile'," WHERE id='".$row->id_mst_guest."'");
		$checkin = selectColumn('fo_reservations','checkin'," WHERE id='".$id_reservations."'");
		$checkout = selectColumn('fo_reservations','checkout'," WHERE id='".$id_reservations."'");
		// PAYMENT MODE & BILL TO
			$SQL_receipt = "SELECT * FROM fo_receipt WHERE id_fo_folio = ? ORDER BY id DESC LIMIT 1";
			$receipt = $connNew->prepare($SQL_receipt);
			$receipt->bind_param("i", $row->id);
			$receipt->execute();
			$receiptResult = $receipt->get_result();
			$receiptRow = $receiptResult->fetch_assoc();

			$payment_mode = ($receiptRow && !empty($receiptRow['payment_mode'])) ? $receiptRow['payment_mode'] : '';
			$id_company   = ($receiptRow && !empty($receiptRow['id_company']))   ? $receiptRow['id_company']   : '0';
			$company_name = selectColumn('mst_company', 'name', "WHERE id = '".$id_company."'") ?? '-';
		
        echo $i++."\t".
            $row->mdoc_no."\t".
            date('d-m-Y',strtotime($row->doc_date))."\t".
            $bill_no."\t".
			$payment_mode."\t".
    		$company_name."\t".
            $GuestName."\t".
			$Email."\t".
			$mobile."\t".
			date('d-m-Y',strtotime($checkin))."\t".
			date('d-m-Y',strtotime($checkout))."\t".
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
    <!-- Content Header (Page header) -->
	
	 <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
    <div class="row">
     <div class="col-md-4 col-xs-12"> 
      <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h6>
     </div>
     <div class="col-md-4 col-xs-12 dd-f">	
     
                 
                  
       
     </div> 
     <div class="col-md-4 col-xs-12 tb-br">	            
      <?php echo breadCrumbs(); ?>

     </div> 
    </div>
    </section> 
	  
	  
	  
	  
    <section class="content">
    <div class="box box-default">
        <div class="form-group has-error mb-0" align="center">
          <?php if($_SESSION['errorMsg']){?>
          <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
          <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
          <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
          <?php unset($_SESSION['successMsg']);}?>
        </div>
        <div class="box-header with-border">
          <h6 class="box-title">Search <small> Records:(
            <?=$numRows;?>
            ) &nbsp;</small> </h6>
          <?php /*?><div class="btn-group  pull-right"> <a type="button" class="btn n-btn pull-right" href="managePosKot.php?submenu=<?php echo $_GET['submenu']; ?>" >Add <?php echo currentNavigation()['submenu']; ?> </a> </div><?php */?>
        </div>

 <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
           <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
            <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
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
	<th>Payment Mode</th>	
<th>Bill To</th>
<th>Guest</th>

<th>Folio Amount</th>
<th>Received Amount</th>
<th>Balance</th>

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
	$first_name = selectColumn(TBL_GUEST,'first_name'," WHERE id='".$row->id_mst_guest."'");
	$last_name = selectColumn(TBL_GUEST,'last_name'," WHERE id='".$row->id_mst_guest."'");
    $GuestName = $first_name . ' ' . $last_name;

    // PRINT URL
    $frontbillprint = selectColumn('mst_shops','folio_url'," WHERE id='".$_SESSION['shop']."'");
    if($frontbillprint==''){
        $frontbillprint='folioformat1.php';
    }
	
	//Payment Modes
	
		$SQL_receipt = "SELECT * FROM fo_receipt 
			WHERE id_fo_folio = ?  
			ORDER BY id DESC LIMIT 1";
			$receipt = $connNew->prepare($SQL_receipt);
			$receipt->bind_param("i", $row->id);
			$receipt->execute();
			$receiptResult = $receipt->get_result();
			$receiptRow = $receiptResult->fetch_assoc();
		
		$payment_mode = ($receiptRow && !empty($receiptRow['payment_mode'])) ? $receiptRow['payment_mode'] : '';
		$id_company = ($receiptRow && !empty($receiptRow['id_company'])) ? $receiptRow['id_company'] : '0';
	
	$company_name = selectColumn('mst_company','name',"WHERE id = '".$id_company."'")??'-';

?>
<tr>
<td><?= $i++ ?></td>
<td><?= $row->mdoc_no ?></td>
<td><?= date('d-m-Y',strtotime($row->doc_date)) ?></td>
<td><?= $bill_no ?></td>
	<td><?= $payment_mode; ?></td>
	<td><?= $company_name; ?></td>
<td><?= $GuestName ?></td>
	

<td><?= number_format($CurrentTotal,2) ?></td>
<td><?= number_format($receipt_amount,2) ?></td>
<td><?= number_format($BalanceAmount,2) ?></td>


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