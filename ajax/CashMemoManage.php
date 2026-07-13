<?php include_once("../config/auto_loader.php");

$doc_type = $_POST["doc_type"];
$doc_date = date('Y-m-d', strtotime(addslashes($_POST['doc_date'])));
$date     = date('Y-m-d');
$idss     = 0;

// Step 1: Find the applicable doc type config based on effective_date
$sql4 = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."`
          WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'
          AND `doc_type` = '".$doc_type."'
          AND `id_app_modules` = '8'
          ORDER BY effective_date DESC LIMIT 0,1";
$db->query($sql4);
$numRows = $db->num_rows();
while($row4 = $db->fetch_object()){
    if(($row4->effective_date <= $date || $row4->effective_date >= $date) && $row4->effective_date <= $doc_date){
        $idss = $row4->id;
    }
}

// Step 2: Load config + detail (prefix/suffix/start_no live in the detail table)
$id = 0; $method = ''; $start_no = 1; $prefix = ''; $suffix = '';

$sql = "SELECT c.id, c.method, c.start_no,
               d.prefix, d.suffix, d.start_no AS detail_start_no, d.numeric_part
        FROM `".TBL_DOC_TYPE_CONFIG."` c
        LEFT JOIN `mst_doc_type_configuration_detail` d ON d.id_mst_doc_type_config = c.id
        WHERE c.id_shop = '".addslashes($_SESSION['shop'])."'
        AND c.doc_type = '".$doc_type."'
        AND c.id_app_modules = '8'
        AND c.id = '".$idss."'
        ORDER BY d.effective_date DESC LIMIT 1";
$db->query($sql);
$numRows = $db->num_rows();
while($row = $db->fetch_object()){
    $id       = $row->id;
    $method   = $row->method;
    // Use detail start_no if available, fall back to config start_no
    $start_no = ($row->detail_start_no > 0) ? intval($row->detail_start_no) : intval($row->start_no);
    $prefix   = $row->prefix;
    $suffix   = $row->suffix;
}

// Step 3: Fallback — if no config matched by effective_date
if($numRows == 0){
    $sqls = "SELECT c.id, c.method, c.start_no,
                    d.prefix, d.suffix, d.start_no AS detail_start_no, d.numeric_part
             FROM `".TBL_DOC_TYPE_CONFIG."` c
             LEFT JOIN `mst_doc_type_configuration_detail` d ON d.id_mst_doc_type_config = c.id
             WHERE c.id_shop = '".addslashes($_SESSION['shop'])."'
             AND c.doc_type = '".$doc_type."'
             AND c.id_app_modules = '8'
             ORDER BY d.effective_date DESC LIMIT 1";
    $db->query($sqls);
    while($rows = $db->fetch_object()){
        $id       = $rows->id;
        $method   = $rows->method;
        $start_no = ($rows->detail_start_no > 0) ? intval($rows->detail_start_no) : intval($rows->start_no);
        $prefix   = $rows->prefix;
        $suffix   = $rows->suffix;
    }
}

// Step 4: Get next doc number from cash_transaction using MAX(doc_no)
if($method == '1'){
    $sql2 = "SELECT MAX(doc_no) as max_doc_no FROM `cash_transaction`
              WHERE `doc_type` = '".$doc_type."'
              AND `id_doc_type_configuration` = '".$id."'";
    $db->query($sql2);
    $row2    = $db->fetch_object();
    $max_doc = intval($row2->max_doc_no);

    $doc_no = ($max_doc > 0) ? ($max_doc + 1) : (($start_no > 0) ? $start_no : 1);

    $res['doc_no']                    = $doc_no;
    $res['id_doc_type_configuration'] = $id;
    $res['prefix']                    = $prefix;
    $res['suffix']                    = $suffix;
    $res['method']                    = '1';

} elseif($method == '2'){
    $sql3 = "SELECT MAX(doc_no) as max_doc_no FROM `cash_transaction`
              WHERE `doc_type` = '".$doc_type."'
              AND `id_doc_type_configuration` = '".$id."'";
    $db->query($sql3);
    $row3    = $db->fetch_object();
    $max_doc = intval($row3->max_doc_no);

    $doc_no = ($max_doc > 0) ? ($max_doc + 1) : (($start_no > 0) ? $start_no : 1);

    $res['doc_no']                    = $doc_no;
    $res['id_doc_type_configuration'] = $id;
    $res['prefix']                    = $prefix;
    $res['suffix']                    = $suffix;
    $res['method']                    = '2';
}

echo json_encode($res);
empty($res);
?>