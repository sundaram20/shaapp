<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG, 'view');

$eId = addslashes(encryptor(decrypt, $_REQUEST['eId']));

// ── Load main transaction ─────────────────────────────────────────────────────
$sql = "SELECT ct.*, u.name AS created_by_name
        FROM `cash_transaction` ct
        LEFT JOIN `".TBL_USERS."` u ON u.id = ct.created_by
        WHERE ct.id = '".$eId."'";
$db->query($sql);
$row = $db->fetch_object();

if(!$row){
    header("location:manageCashTransfer.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── Load detail row ───────────────────────────────────────────────────────────
$sqlDet = "SELECT ctd.*,
                  ma_from.field_value AS from_cashier_name,
                  ma_to.field_value   AS to_cashier_name
           FROM `cash_transaction_details` ctd
           LEFT JOIN `mst_attributes` ma_from ON ma_from.id = ctd.cashier_from
           LEFT JOIN `mst_attributes` ma_to   ON ma_to.id   = ctd.cashier_to
           WHERE ctd.id_cash_transaction = '".$eId."'
           LIMIT 1";
$db->query($sqlDet);
$detail = $db->fetch_object();

if(!$detail){
    header("location:manageCashTransfer.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── Payment mode label ────────────────────────────────────────────────────────
function paymentModeLabel($pm){
    $map = array('1'=>'Cash','2'=>'Online/UPI','3'=>'Credit/Debit Card','4'=>'Cheque');
    return isset($map[$pm]) ? $map[$pm] : '-';
}

// ── Amount in words ───────────────────────────────────────────────────────────
function amountInWords($amount){
    $ones = array('','One','Two','Three','Four','Five','Six','Seven','Eight','Nine',
                  'Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen',
                  'Seventeen','Eighteen','Nineteen');
    $tens = array('','','Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety');

    function convertBelow1000($n, $ones, $tens){
        if($n == 0) return '';
        if($n < 20) return $ones[$n].' ';
        if($n < 100) return $tens[intval($n/10)].' '.($n%10 ? $ones[$n%10].' ' : '');
        return $ones[intval($n/100)].' Hundred '.convertBelow1000($n%100, $ones, $tens);
    }

    $amount  = round($amount, 2);
    $rupees  = intval($amount);
    $paise   = round(($amount - $rupees) * 100);
    $words   = '';

    if($rupees == 0){
        $words = 'Zero';
    } else {
        $crore = intval($rupees / 10000000); $rupees %= 10000000;
        $lakh  = intval($rupees / 100000);   $rupees %= 100000;
        $thou  = intval($rupees / 1000);     $rupees %= 1000;
        $rest  = $rupees;
        if($crore) $words .= convertBelow1000($crore, $ones, $tens).'Crore ';
        if($lakh)  $words .= convertBelow1000($lakh,  $ones, $tens).'Lakh ';
        if($thou)  $words .= convertBelow1000($thou,  $ones, $tens).'Thousand ';
        if($rest)  $words .= convertBelow1000($rest,  $ones, $tens);
    }

    $words = trim($words).' Indian Rupees';
    if($paise > 0) $words .= ' and '.convertBelow1000($paise, $ones, $tens).'Paise';
    return $words.' Only.';
}

// ── Shop / company details ────────────────────────────────────────────────────
$shopSql = "SELECT mh.name, mh.address, mh.city, mh.email, mh.primary_mobile,
                   mh.primary_landline, mh.primary_contact_type,
                   mh.gstin, mh.hotel_tagline, mh.image AS hotel_image,
                   ms.image AS shop_logo
            FROM `mst_hotels` mh
            LEFT JOIN `mst_shops` ms ON ms.id = mh.id_shop
            WHERE mh.id_shop = '".$_SESSION['shop']."'
            LIMIT 1";
$db->query($shopSql);
$shop = $db->fetch_object();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Transfer Voucher - <?php echo htmlspecialchars($row->mdoc_no); ?></title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <style>
        .erp-receipt-box {
            background: white;
            width: 210mm;
            margin: 0 auto;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            padding: 10mm;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            min-height: 177mm;
        }
        .erp-table { width: 100%; border-collapse: collapse; }
        .erp-table th, .erp-table td { border: 1px solid #000; padding: 8px 10px; vertical-align: middle; }
        .b-box { border: 1px solid #000; }
        .b-r  { border-right: 1px solid #000; }
        .b-b  { border-bottom: 1px solid #000; }
        .b-t  { border-top: 1px solid #000; }
        .font-bold { font-weight: 700; }
        .uppercase { text-transform: uppercase; }

        @media print {
            @page { size: A4; margin: 0mm !important; }
            html, body { margin: 0 !important; padding: 0 !important; background: white !important; }
            .no-print { display: none !important; }
            .erp-receipt-box {
                box-shadow: none !important;
                margin: 0 !important;
                padding: 10mm !important;
                width: 100% !important;
                min-height: 265mm !important;
                height: 297mm !important;
            }
        }
    </style>
</head>
<body class="p-4 bg-gray-100 min-h-screen print:p-0 print:min-h-0 print:bg-white">

    <!-- Buttons -->
    <div class="max-w-[210mm] mx-auto mb-4 flex justify-end no-print">
        <button onclick="window.print()"
                class="bg-gray-800 text-white px-6 py-2 rounded font-bold text-xs uppercase tracking-widest cursor-pointer hover:bg-gray-700">
            Print Voucher
        </button>
        &nbsp;&nbsp;
        <button onclick="window.location.href='manageCashTransfer.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>'"
                class="bg-gray-200 text-gray-800 px-6 py-2 rounded font-bold text-xs uppercase tracking-widest cursor-pointer hover:bg-gray-300">
            Close
        </button>
    </div>

    <div id="printable-area" class="erp-receipt-box">
        <div class="b-box flex-grow flex flex-col">

            <!-- Header: Company info -->
            <div class="flex justify-between items-center p-4 b-b">
                <div class="w-1/4">
                    <?php
                        $logo = ($shop->shop_logo != '') ? $shop->shop_logo : $shop->hotel_image;
                        if($logo != ''){
                    ?>
                        <img src="../uploads/<?php echo htmlspecialchars($logo); ?>"
                             style="max-height:50px;" alt="Logo">
                    <?php } ?>
                </div>
                <div class="w-2/4 text-center">
                    <h1 class="text-2xl font-bold uppercase tracking-tighter">
                        <?php echo htmlspecialchars($shop->name); ?>
                    </h1>
                    <p class="text-[10px] leading-tight">
                        <?php echo htmlspecialchars($shop->address);
                              if($shop->city != '') echo ', '.htmlspecialchars($shop->city); ?><br>
                        <?php
                            if($shop->primary_contact_type == '1' && $shop->primary_mobile != ''){
                                echo 'Phone: '.htmlspecialchars($shop->primary_mobile).'<br>';
                            } elseif($shop->primary_contact_type == '2' && $shop->primary_landline != ''){
                                echo 'Phone: '.htmlspecialchars($shop->primary_landline).'<br>';
                            }
                        ?>
                        <?php if($shop->email != '') echo '<strong>Email: '.htmlspecialchars($shop->email).'</strong>'; ?>
                    </p>
                    <?php if($shop->hotel_tagline != ''){ ?>
                    <p class="text-[9px] italic text-gray-500"><?php echo htmlspecialchars($shop->hotel_tagline); ?></p>
                    <?php } ?>
                </div>
                <div class="w-1/4 text-right">
                    <div class="text-xs font-bold uppercase underline">Transfer Voucher</div>
                    <?php if($shop->gstin != ''){ ?>
                    <div class="text-[9px] mt-1">GSTIN: <?php echo htmlspecialchars($shop->gstin); ?></div>
                    <?php } ?>
                </div>
            </div>

            <!-- Doc info row -->
            <div class="b-b bg-gray-50 flex text-[10px]">
                <div class="w-[15%] b-r p-2">
                    <strong>Doc No:</strong><br>
                    <?php echo htmlspecialchars($row->mdoc_no); ?>
                </div>
                <div class="w-[15%] b-r p-2">
                    <strong>Date:</strong><br>
                    <?php echo date('d-m-Y', strtotime($row->doc_date)); ?>
                </div>
                <div class="w-[35%] b-r p-2">
                    <strong>From Cashier:</strong><br>
                    <span class="text-sm font-bold"><?php echo htmlspecialchars($detail->from_cashier_name); ?></span>
                </div>
                <div class="w-[35%] p-2">
                    <strong>To Cashier:</strong><br>
                    <span class="text-sm font-bold"><?php echo htmlspecialchars($detail->to_cashier_name); ?></span>
                </div>
            </div>

            <!-- Payment mode row -->
            <div class="b-b bg-white flex text-[10px]">
                <div class="w-full p-2">
                    <strong>Payment Mode:</strong>
                    <?php echo paymentModeLabel($detail->payment_mode); ?>
                    <?php if($row->created_by_name != ''){ ?>
                        &nbsp;&nbsp;|&nbsp;&nbsp;
                        <strong>Created By:</strong> <?php echo htmlspecialchars($row->created_by_name); ?>
                    <?php } ?>
                </div>
            </div>

            <!-- Detail table -->
            <div class="flex-grow">
                <table class="erp-table">
                    <thead class="bg-gray-100 uppercase text-[9px]">
                        <tr>
                            <th class="w-[10%] text-center">Item</th>
                            <th class="w-[60%] text-left">Remarks</th>
                            <th class="w-[30%] text-right">Amount (INR)</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px]">
                        <tr>
                            <td class="text-center">1</td>
                            <td class="font-medium">
                                <?php echo $detail->remarks != '' ? htmlspecialchars($detail->remarks) : '-'; ?>
                            </td>
                            <td class="text-right font-bold text-base">
                                <?php echo number_format($detail->amount, 2); ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Footer: Amount in words + Total -->
            <div class="mt-auto b-t flex">
                <div class="w-3/5 b-r p-3 flex flex-col justify-center">
                    <div class="italic text-[10px]">
                        <strong>Amount in words:</strong><br>
                        <?php echo amountInWords($detail->amount); ?>
                    </div>
                </div>
                <div class="w-2/5 flex flex-col justify-end">
                    <div class="flex justify-between p-4 bg-gray-100 text-sm h-full items-center">
                        <span class="font-bold uppercase tracking-tighter">Total Transferred:</span>
                        <span class="font-bold text-lg">&#8377; <?php echo number_format($detail->amount, 2); ?></span>
                    </div>
                </div>
            </div>

            <!-- Footer note -->
            <div class="text-center b-t py-3 text-[10px] uppercase bg-gray-50 font-medium">
                This is a system-generated transfer receipt and does not require a signature.
            </div>

        </div>
    </div>

</body>
</html>