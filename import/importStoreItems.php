<?php
// DB connection
$host = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com"; 
$user = "hmk";
$pass = "Obl&06m59";
$db   = "hmk";
echo 'UNIT HMK <br>Store Items';

$connNew2 = mysqli_connect($host, $user, $pass, $db);
if (!$connNew2) {
    die("Connection failed: " . mysqli_connect_error());
}

function currenDateTime() {
    return date("Y-m-d H:i:s");
}

if (isset($_POST['import'])) {
    $filename = $_FILES["file"]["tmp_name"];
    $successCount = 0;
    $failCount = 0;
    $failedRecords = [];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        $row = 0;

        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            $row++;
            if ($row == 1) continue; // skip header row

            $itemCode = trim($emapData[0]);
            $ItemName = trim($emapData[1]);

            // ===== LOOKUPS =====
            // Group Main
            echo $sqlgroup_main = "SELECT * FROM `mst_attributes` 
                              WHERE status='1' AND table_name='item_group_main' 
                              AND LOWER(field_value) = '".strtolower(trim($emapData[2]))."' ";
            $resTogroup_main = mysqli_query($connNew2, $sqlgroup_main);
            $rowgroup_main = mysqli_fetch_object($resTogroup_main);
            $id_mst_attributes_group_main = $rowgroup_main->id ?? null;

            // Group Sub
            $sqlgroup_sub = "SELECT * FROM `mst_attributes` 
                             WHERE status='1' AND table_name='item_group_sub' 
                             AND LOWER(field_value) = '".strtolower(trim($emapData[3]))."' ";
            $resTogroup_sub = mysqli_query($connNew2, $sqlgroup_sub);
            $rowgroup_sub = mysqli_fetch_object($resTogroup_sub);
            $id_mst_attributes_group_sub = $rowgroup_sub->id ?? null;

            // Unit Main
            $sqlunitMain = "SELECT * FROM `mst_attributes` 
                            WHERE status='1' AND table_name='unit' 
                            AND LOWER(field_value) = '".strtolower(trim($emapData[4]))."' ";
            $resTounitMain = mysqli_query($connNew2, $sqlunitMain);
            $rowunitMain = mysqli_fetch_object($resTounitMain);
            $id_mst_attributes_unit_main = $rowunitMain->id ?? null;

            // Unit Alt
            $sqlunitSub = "SELECT * FROM `mst_attributes` 
                           WHERE status='1' AND table_name='unit' 
                           AND LOWER(field_value) = '".strtolower(trim($emapData[5]))."' ";
            $resTounitSub = mysqli_query($connNew2, $sqlunitSub);
            $rowunitSub = mysqli_fetch_object($resTounitSub);
            $id_mst_attributes_unit_alt = $rowunitSub->id ?? null;

            // Other fields
            $conversion_qty = $emapData[7];
            $store = '864'; // default
            $itemMenuType = '970'; // Ingredients
            $min_qty = $emapData[15] == '' ? '0.00' : $emapData[15];
            $max_qty = $emapData[16] == '' ? '0.00' : $emapData[16];

            // ===== VALIDATION =====
            $errorReason = "";
            if (empty($itemCode) || empty($ItemName)) {
                $errorReason = "Missing item code or name";
            } elseif (!$id_mst_attributes_group_main) {
                $errorReason = "Invalid Group Main";
            } elseif (!$id_mst_attributes_group_sub) {
                $errorReason = "Invalid Group Sub";
            } elseif (!$id_mst_attributes_unit_main) {
                $errorReason = "Invalid Unit Main";
            } elseif (!$id_mst_attributes_unit_alt) {
                $errorReason = "Invalid Unit Alt";
            }

            if ($errorReason != "") {
                $failCount++;
                $failedRecords[] = [
                    'code' => $itemCode,
                    'name' => $ItemName,
                    'reason' => $errorReason
                ];
                continue;
            }

            // ===== DUPLICATE CHECK =====
            $checkDuplicate = "SELECT id FROM `inv_items` 
                               WHERE item_code = '".mysqli_real_escape_string($connNew2, $itemCode)."' 
                                  OR name = '".mysqli_real_escape_string($connNew2, $ItemName)."' 
                               LIMIT 1";
            $resDuplicate = mysqli_query($connNew2, $checkDuplicate);

            if (mysqli_num_rows($resDuplicate) > 0) {
                $failCount++;
                $failedRecords[] = [
                    'code' => $itemCode,
                    'name' => $ItemName,
                    'reason' => "Duplicate item code or name"
                ];
                continue; // skip insert
            }

            // ===== INSERT =====
            $addNewCompanyName = "INSERT INTO `inv_items` SET 
                `item_code`='".$itemCode."',
                `name`='".$ItemName."',
                `id_mst_attributes_item_type`='".$itemMenuType."',
                `id_mst_attributes_group_main`='".$id_mst_attributes_group_main."',
                `id_mst_attributes_group_sub`='".$id_mst_attributes_group_sub."',
                `id_mst_attributes_unit_main`='".$id_mst_attributes_unit_main."',
                `id_mst_attributes_unit_alt`='".$id_mst_attributes_unit_alt."',
                `id_mst_charges_sales_local`='0',
                `id_mst_charges_sales_interstate`='0',
                `id_mst_charges_purchase_local`='0',
                `id_mst_charges_purchase_interstate`='0',
                `id_mst_attributes_store`='".$store."',
                `id_mst_attributes_printer`='0',
                `ids_mst_outlet`='0',
                `conversion_qty`='".$conversion_qty."',
                `min_qty`='".$min_qty."',
                `max_qty`='".$max_qty."',
                `rol`='0.00',
                `roq`='0.00',
                `item_class`='A',
                `bal_qty`='0.00',
                `open_qty`='0.00',
                `open_amount`='0.00',
                `last_purchase_rate`='0.00',
                `item_enable_desc_billing`='0',
                `stockable_enable_disable`='0',
                `edit_name_enable_disable`='0',
                `item_get_expiry_details`='0',
                `item_production_item`='0',
                `item_allow_additional`='0',
                `item_disable`='0',
                `sale_rate`='0',
                `purchase_rate`='0.00',
                `batch_details`='0',
                `item_details`='1',
                `display_order`='0',
                `item_image`='',
                `id_shop`='2',
                `status`='1',
                `deactivate_date`='',
                `date_created`='".currenDateTime()."',
                `last_modified`='".currenDateTime()."',
                `id_mst_user_created_by`='10',
                `id_mst_user_modified_by`='10'";

            $InsertSucess = mysqli_query($connNew2, $addNewCompanyName);

            if ($InsertSucess) {
                $successCount++;
            } else {
                $failCount++;
                $failedRecords[] = [
                    'code' => $itemCode,
                    'name' => $ItemName,
                    'reason' => mysqli_error($connNew2)
                ];
            }
        }
        fclose($file);
    }

    // ===== RESULTS =====
    echo "<h3>Import Summary</h3>";
    echo "✅ Success: $successCount <br>";
    echo "❌ Failed: $failCount <br>";

    if ($failCount > 0) {
        echo "<h4>Failed Records</h4>";
        echo "<table border='1' cellpadding='5'><tr><th>Code</th><th>Name</th><th>Reason</th></tr>";
        foreach ($failedRecords as $fail) {
            echo "<tr>
                    <td>".$fail['code']."</td>
                    <td>".$fail['name']."</td>
                    <td>".$fail['reason']."</td>
                  </tr>";
        }
        echo "</table>";
    }
}
?>

<div style="display:flex; justify-content:center; ">
    <form method="post" enctype="multipart/form-data" style="text-align:center; padding:20px; border:1px solid #ccc; border-radius:8px;">
        <input type="file" name="file" required /><br><br>
        <button type="submit" name="import">Import</button>
    </form>
</div>
