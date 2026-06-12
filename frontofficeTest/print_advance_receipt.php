<?php
include_once("../config/auto_loader.php");

$enc_id = $_GET['res_id'] ?? "";
$res_id = encryptor('decrypt', $enc_id);

// Fetch ALL payments for the reservation
$sql = "SELECT * FROM fo_receipt WHERE id_reservation='$res_id' ORDER BY id ASC";
$payments = executeSql($sql);

// Store all payments in array
$payment_list = [];
$total_received = 0;

while($row = mysqli_fetch_assoc($payments)) {
    $payment_list[] = $row;
    $total_received += $row['amount'];
}

// Fetch reservation / guest
$booking_no = selectColumn('fo_reservations','booking_no',"WHERE id='".$res_id."'");
$guest_id = selectColumn('fo_reservations','id_mst_guest',"WHERE id='".$res_id."'");
$res = mysqli_fetch_assoc(executeSql("SELECT first_name, last_name FROM mst_guest WHERE id='$guest_id'"));
$guest_name = $res['first_name']." ".$res['last_name'];

//Hotel Details
$resHotel  = executeSQl("SELECT * FROM `".TBL_HOTELS."` WHERE id_shop='".addslashes($_SESSION['shop'])."'");
$resHotel = $db->fetch_object2($resHotel);
$hotel_name = $resHotel->name;
$address    = $resHotel->address;
$city       = $resHotel->city;
$pincode    = $resHotel->pincode;
$phone      = $resHotel->primary_mobile;
$GST = $resHotel->gstin;

function convert_number_to_words($number) {
    $words = array(
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 
        18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 
        80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
        100000 => 'Lakh', 10000000 => 'Crore'
    );

    if ($number == 0) {
        return 'Zero';
    }

    $number = (int)$number;
    $result = '';

    if ($number < 100) {
        if ($number <= 20) {
            $result = $words[$number];
        } else {
            $tens = (int)($number / 10) * 10;
            $units = $number % 10;
            $result = $words[$tens];
            if ($units) {
                $result .= '-' . $words[$units];
            }
        }
    } elseif ($number < 1000) {
        $hundreds = (int)($number / 100);
        $remainder = $number % 100;
        $result = $words[$hundreds] . ' Hundred';
        if ($remainder) {
            $result .= ' and ' . convert_number_to_words($remainder);
        }
    } elseif ($number < 100000) {
        $thousands = (int)($number / 1000);
        $remainder = $number % 1000;
        $result = convert_number_to_words($thousands) . ' Thousand';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    } elseif ($number < 10000000) {
        $lakhs = (int)($number / 100000);
        $remainder = $number % 100000;
        $result = convert_number_to_words($lakhs) . ' Lakh';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    } else {
        $crores = (int)($number / 10000000);
        $remainder = $number % 10000000;
        $result = convert_number_to_words($crores) . ' Crore';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    }

    return $result;
}

function convert_amount_to_words($amount) {
    $amount = number_format($amount, 2, '.', '');
    list($integerPart, $decimalPart) = explode('.', $amount);

    $integerPartWords = convert_number_to_words($integerPart);
    $decimalPartWords = convert_number_to_words($decimalPart);

    $result = $integerPartWords . ' Only';
    if ((int)$decimalPart > 0) {
        $result .= ' and ' . $decimalPartWords . ' Paise';
    }

    return $result;
}
$amount = round(($total_received),0);
$convert_amount_to_words= convert_amount_to_words($amount);
?>


<!DOCTYPE html>
<html>
<head>
<title>Advance Payment Receipt</title>
<style>
    body { font-family: Arial, sans-serif; padding:20px; background:white; }
    .receipt-box {
        max-width: 650px;
        margin: auto;
        border: 1px solid #000;
        padding: 20px;
        font-size: 14px;
        line-height: 22px;
    }
    .title { text-align:center; font-size:22px; font-weight:bold; margin-bottom:5px; }
    .sub-title { text-align:center; font-size:14px; margin-bottom:15px; }
    table { width:100%; border-collapse: collapse; margin-top:15px; }
    td { padding:6px; }
    .border { border:1px solid #000; }
    .amount-box {
        font-size:20px;
        font-weight:bold;
        text-align:center;
        margin-top:20px;
        padding:10px;
        border:1px dashed #000;
    }
    @media print {
        #printBtn { display:none; }
    }
</style>
</head>

<body>

<div class="receipt-box">



    <!-- HOTEL HEADER -->
    <div class="title"><?php echo $hotel_name; ?></div>
    <div class="sub-title"><?php echo $address.', '.$city.' - '.$pincode; ?><br>Phone: <?=$phone?> | GST: <?=$GST?></div>

    <hr>

    <!-- RECEIPT TITLE -->
    <h3 style="text-align:center; margin-bottom:0;">ADVANCE PAYMENT RECEIPT</h3>
    <p style="text-align:center; margin-top:3px;">Receipt No: <?php echo'adv/res/' .$res_id; ?></p>

    <hr>

    <!-- CUSTOMER / BOOKING INFO -->
    <table>
        <tr>
            <td><b>Reservation ID:</b> <?php echo $booking_no; ?></td>
            <td><b>Date:</b> <?php echo date("d-M-Y"); ?></td>
        </tr>
        <tr>
            <td colspan="2"><b>Guest Name:</b> <?php echo $guest_name; ?></td>
        </tr>
    </table>

    <!-- PAYMENT DETAILS -->
    <h4 style="margin-top:15px;">Received amount ₹<?= number_format($total_received, 2);?> 
    (<?php echo $convert_amount_to_words; ?> )</b> with the following details:</h4>

<table class="border">
    <tr>
        <th class="border" style="background:#eee;">S.No</th>
        <th class="border" style="background:#eee;">Date</th>
        <th class="border" style="background:#eee;">Payment Mode</th>
        <th class="border" style="background:#eee;">Amount (₹)</th>
        <th class="border" style="background:#eee;">Remarks</th>
    </tr>

    <?php 
    $i = 1;
    foreach ($payment_list as $p): 
    ?>
    <tr>
        <td class="border"><?= $i++; ?></td>
        <td class="border"><?= date("d-M-Y h:i A", strtotime($p['date_created'])); ?></td>
        <td class="border"><?= $p['payment_mode']; ?></td>
        <td class="border"><b><?= number_format($p['amount'],2); ?></b></td>
        <td class="border"><?= $p['remark'] ?: "-"; ?></td>
    </tr>
    <?php endforeach; ?>
</table>

<!-- TOTAL AMOUNT -->
<div class="amount-box">
    TOTAL RECEIVED: ₹ <?= number_format($total_received, 2); ?>
</div>

    <!-- FOOTER -->
    <p style="margin-top:30px; text-align:center; font-size:12px;">
        This is a system-generated receipt and does not require a signature.
    </p>

</div>

<div style="text-align:center; margin-top:20px;">
    <button id="printBtn" 
            onclick="window.print()" 
            style="padding:8px 20px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Print Receipt
    </button>
</div>

</body>
</html>
