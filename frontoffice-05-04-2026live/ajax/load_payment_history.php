<?php
include_once("../../config/auto_loader.php");

$res_id = $_POST['booking_id'];

$sql = "SELECT * FROM fo_receipt 
        WHERE id_reservation = '$res_id'
        ORDER BY id DESC";

$res = executeSql($sql);

if ($res->num_rows == 0) {
    echo "<div style='padding:10px; font-size:13px; color:#555;'>No transactions found.</div>";
    exit;
}

echo "<div style='padding:10px; border:1px solid #ccc; border-radius:5px;'>";

while ($row = mysqli_fetch_assoc($res)) {

    echo "
    <div style='
        border-bottom:1px solid #eee; 
        padding:8px 0; 
        font-size:13px;
        display:flex; 
        justify-content:space-between;
        align-items:center;
    '>

        <div>
            <b>".date("d-M-Y h:i A", strtotime($row['date_created']))."</b><br>
            Mode: {$row['payment_mode']}<br>
            Amount: <b>₹ {$row['amount']}</b><br>
            <span style='color:#777; font-size:12px;'>Remarks: {$row['remark']}</span>
        </div>

        <!--<button type='button' class='deletePaymentBtn' 
                data-id='{$row['id']}'
                style='background:#ff4d4d; color:white; border:none; padding:5px 10px; border-radius:4px; cursor:pointer;'>
            Delete
        </button>-->

    </div>
    ";
}

echo "</div>";
?>
