<?php
// DB connection
$host = "ls-cdbb14163c8c94432e8c07692092483200dee4a3.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com"; 
$user = "deo";
$pass = "@dg7f8I0";
$db   = "deo";

echo '✅ Reference Update Tool (fo_reservations)<br><br>';

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['import'])) {
    $filename = $_FILES["file"]["tmp_name"];
    $successCount = 0;
    $skipCount = 0;
    $failCount = 0;
    $failedRecords = [];

    if ($_FILES["file"]["size"] > 0) {
        $file = fopen($filename, "r");
        $row = 0;

        while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE) {
            $row++;
            if ($row == 1) continue; // Skip header

            $id_order   = trim($emapData[0]);
            $excelReference  = trim($emapData[1]);

                      // Fetch matching record in table
            $checkSql = "SELECT id, reference, other_reference 
                         FROM fo_reservations 
                         WHERE other_reference = '".mysqli_real_escape_string($conn, $id_order)."' 
                         LIMIT 1";
            $res = mysqli_query($conn, $checkSql);

          

            $rowData = mysqli_fetch_assoc($res);
            $dbReference = trim($rowData['reference']);
            $dbOtherRef  = trim($rowData['other_reference']);

            // Verify reference value
         
			if($dbReference!=$excelReference){
            // If not same, update reference
            echo '<br><br><br>'.$row.'====>'.$updateSql = "UPDATE fo_reservations 
                          SET reference = '".mysqli_real_escape_string($conn, $excelReference)."' 
                          WHERE other_reference = '".mysqli_real_escape_string($conn, $id_order)."'";

           // $updateRes = mysqli_query($conn, $updateSql);

			}
        }
        fclose($file);
    }

    // ===== Summary =====
    echo "<hr><h3>Update Summary</h3>";
    echo "✅ Updated: $successCount<br>";
    echo "⚪ Skipped (already same): $skipCount<br>";
    echo "❌ Failed: $failCount<br>";

    if ($failCount > 0) {
        echo "<h4>Failed Records</h4>";
        echo "<table border='1' cellpadding='5'>
                <tr><th>id_order</th><th>reference</th><th>Reason</th></tr>";
        foreach ($failedRecords as $f) {
            echo "<tr>
                    <td>".$f['id_order']."</td>
                    <td>".$f['reference']."</td>
                    <td>".$f['reason']."</td>
                  </tr>";
        }
        echo "</table>";
    }
}
?>

<div style="display:flex; justify-content:center; margin-top:20px;">
    <form method="post" enctype="multipart/form-data" style="text-align:center; padding:20px; border:1px solid #ccc; border-radius:8px;">
        <h3>Upload CSV to Update References</h3>
        <p><small>Columns required: id_order, reference</small></p>
        <input type="file" name="file" accept=".csv" required /><br><br>
        <button type="submit" name="import">Update</button>
    </form>
</div>
