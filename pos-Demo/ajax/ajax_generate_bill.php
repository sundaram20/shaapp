<?php
include_once("../../config/auto_loader.php");

$table_ids = $_REQUEST['id_attribute_table'] ?? '';
$pax       = $_REQUEST['pax'] ?? 1;
$date      = !empty($_REQUEST['po_date']) ? date('Y-m-d', strtotime($_REQUEST['po_date'])) : date('Y-m-d');
$id_shop   = $_SESSION['shop'];
$id_user   = $_SESSION['user'];

if (!$table_ids) {
    echo json_encode(['status' => 'error', 'message' => 'Missing table selection']);
    exit;
}

// ensure array
$table_id_list = array_map('trim', explode(',', $table_ids));
$table_id_in = implode(',', array_map('intval', $table_id_list));

// ---- 1️⃣ Fetch table + outlet info ----
echo $table_sql = "
   SELECT id,id_mst_outlet as outlet_id FROM pos_purch WHERE pos_bill_type= '2' AND id_attribute_table= '".$table_ids."'
";
$table_res = mysqli_query($connNew, $table_sql);

$outlet_data = [];

while ($tbl = mysqli_fetch_assoc($table_res)) {
    $tbl_id   = (int)$tbl['id'];
    $tbl_name = $tbl['table_name'];
    $outlet_id_tbl = (int)$tbl['outlet_id'];

    // ---- 2️⃣ Generate DocConfig per outlet/table ----
    $doc_type = 21; // POS Bill Type
	echo $doc_type.'===='.$date.'===='.$outlet_id_tbl;
    $retunDocConfig = docConfigNoValidator($doc_type, $date, $outlet_id_tbl);
    $id_doc_type_configuration = $retunDocConfig['id_doc_type_configuration'];
    $po_no   = $retunDocConfig['po_no'];
    $mdoc_no = $retunDocConfig['prefix'] . $po_no . $retunDocConfig['suffix'];

// --- Refactored POS billing save/insert/upsert block ---
// Assumes helper functions: executeSql(), selectColumn(), currenDateTime(), docConfigNoValidator(), constants TBL_* and globals $connNew, $db, $SITE_URL, $_SESSION

// Basic sanitization helper
	
	 $kot_sql = "
        SELECT id, mdoc_no
        FROM pos_purch 
        WHERE pos_bill_type = '1'
          AND cancelled = 0
          AND doc_type != '24'
          AND id IN (
              SELECT id_pos_purch 
              FROM pos_purch_details 
              WHERE qty - adj_qty > 0
          )
          AND id_attribute_table = $tbl_id
    ";
    $kot_res = mysqli_query($connNew, $kot_sql);

    $k = [];
    while ($row = mysqli_fetch_assoc($kot_res)) {
        $k[] = $row['mdoc_no'];
    }
function rq($k, $default = '') {
    return isset($_REQUEST[$k]) ? $_REQUEST[$k] : $default;
}
function rqArr($k) {
    return isset($_REQUEST[$k]) && is_array($_REQUEST[$k]) ? $_REQUEST[$k] : [];
}

// Convert several request variables to local cleaned vars
$doc_type = addslashes(rq('doc_type'));
$po_date = rq('po_date') ? date('Y-m-d', strtotime(rq('po_date'))) : date('Y-m-d');
$pos_bill_type = 2;
$kot_doc_no = implode(',', rqArr('id_kot'));
$outlet = addslashes(rq('outlet'));
$id_attribute_table = addslashes(rq('id_attribute_table'));
$id_attribute_shift = addslashes(rq('id_attribute_shift'));
$id_attribute_steward = addslashes(rq('id_attribute_steward'));
$noOfPax = intval(rq('noOfPax'));
$remarks = addslashes(rq('remarks'));
$prefix = rq('prefix','');
$suffix = rq('suffix','');

// id_pos_detail is expected array of arrays
echo '====='.$id_pos_detail = rqArr('id_pos_detail');

// build item_BillSplit array unique
$item_BillSplit = [];
foreach($id_pos_detail as $pd) {
    if (isset($pd['item_BillSplit'])) $item_BillSplit[] = $pd['item_BillSplit'];
}
$arrayBillSplit = array_values(array_unique($item_BillSplit));
$countSplits = count($arrayBillSplit);

// Start transaction
mysqli_begin_transaction($connNew);

try {

    // Pre-calc helper: sum per split and item-level sums
    // We'll compute per-split totals to insert/update TBL_PURCH
    $splitData = []; // split => aggregated totals
    foreach ($arrayBillSplit as $split) {
        $splitData[$split] = [
            'ids' => [], // purch_detail ids in this split
            'sgst' => 0.0, 'cgst' => 0.0, 'igst' => 0.0, 'cess' => 0.0, 'vat' => 0.0, 'surcharge' => 0.0,
            'sub_total_items1' => 0.0, 'total_item_discount_amount' => 0.0
        ];
    }

    foreach ($id_pos_detail as $pd) {
        $split = isset($pd['item_BillSplit']) ? $pd['item_BillSplit'] : 1;
        if (!isset($splitData[$split])) {
            $splitData[$split] = ['ids'=>[],'sgst'=>0,'cgst'=>0,'igst'=>0,'cess'=>0,'vat'=>0,'surcharge'=>0,'sub_total_items1'=>0,'total_item_discount_amount'=>0];
        }

        $id = addslashes($pd['id']);
        $splitData[$split]['ids'][] = $id;
        $splitData[$split]['sgst'] += floatval(rq('id_pos_detail')[$id]['item_Tax_sgst'] ?? ($pd['item_Tax_sgst'] ?? 0));
        $splitData[$split]['cgst'] += floatval(rq('id_pos_detail')[$id]['item_Tax_cgst'] ?? ($pd['item_Tax_cgst'] ?? 0));
        $splitData[$split]['igst'] += floatval(rq('id_pos_detail')[$id]['item_Tax_igst'] ?? ($pd['item_Tax_igst'] ?? 0));
        $splitData[$split]['cess'] += floatval(rq('id_pos_detail')[$id]['item_Tax_cess'] ?? ($pd['item_Tax_cess'] ?? 0));
        $splitData[$split]['vat'] += floatval(rq('id_pos_detail')[$id]['item_Tax_vat'] ?? ($pd['item_Tax_vat'] ?? 0));
        $splitData[$split]['surcharge'] += floatval(rq('id_pos_detail')[$id]['item_Tax_surcharge'] ?? ($pd['item_Tax_surcharge'] ?? 0));
        $splitData[$split]['sub_total_items1'] += floatval(rq('id_pos_detail')[$id]['item_TotalAmountItem1'] ?? ($pd['item_TotalAmountItem1'] ?? 0));
        $splitData[$split]['total_item_discount_amount'] += floatval(rq('id_pos_detail')[$id]['item_discount_amount'] ?? ($pd['item_discount_amount'] ?? 0));
    }

    // Prepare doc configuration for each split
    $pos_purch_ids_created = []; // to collect created/updated purch ids
    $pos_val = []; // mapping for distributing purch_details later if multiple bills

    foreach ($arrayBillSplit as $split) {
        // Document config
        $retunDocConfig = docConfigNoValidator($doc_type, $po_date, $outlet);
        $id_doc_type_configuration = $retunDocConfig['id_doc_type_configuration'];
        $po_no = $retunDocConfig['po_no'];
        $mdoc_no = ($retunDocConfig['prefix'] ?? '') . $po_no . ($retunDocConfig['suffix'] ?? '');

        // split aggregates
        $sgst_net_amount = $splitData[$split]['sgst'];
        $cgst_net_amount = $splitData[$split]['cgst'];
        $igst_net_amount = $splitData[$split]['igst'];
        $cess_net_amount = $splitData[$split]['cess'];
        $vat_net_amount = $splitData[$split]['vat'];
        $surcharge_net_amount = $splitData[$split]['surcharge'];
        $sub_total_items1 = $splitData[$split]['sub_total_items1'];
        $total_item_discount_amount = $splitData[$split]['total_item_discount_amount'];

        // Additional/discount per split (divide equally across splits)
        $additional_discount_amount = floatval(rq('additional_discount_amount', 0)) / max(1, $countSplits);
        $others_charges_net_amount = floatval(rq('others_charges_net_amount', 0)) / max(1, $countSplits);

        // service_charge_amount and sc_sgst/sc_cgst come from top-level request (already computed)
        $service_charge_amount = floatval(rq('service_charge_amount', 0));
        $sc_sgst = floatval(rq('sc_sgst', 0));
        $sc_cgst = floatval(rq('sc_cgst', 0));

        // net calculation per original logic
        $net_amount_calc = ($sub_total_items1 + $sc_sgst + $sc_cgst + $service_charge_amount + $sgst_net_amount + $cgst_net_amount + $igst_net_amount + $cess_net_amount + $vat_net_amount + $surcharge_net_amount) - ($total_item_discount_amount);
        $net_amount_before_round_and_others = ($net_amount_calc + $others_charges_net_amount) - $additional_discount_amount;
        $round_of_amount = round((round($net_amount_before_round_and_others, 0) - $net_amount_before_round_and_others), 2);
        $grant_total_amount = intval(round($net_amount_before_round_and_others, 0));

        // decide insert/update
        $id_posbilling = rq('id_posbilling', '');
        if ($id_posbilling === '') {
            // Check duplicate (existing bill matching same split ids)
            $id_pos_details_split2 = implode(',', $splitData[$split]['ids']);
            $sqlDup = "SELECT id FROM `".TBL_PURCH."` WHERE id_doc_type_configuration = '".addslashes($id_doc_type_configuration)."' AND doc_type = '".addslashes($doc_type)."' AND id_attribute_shift = '".addslashes($id_attribute_shift)."' AND id_attribute_steward = '".addslashes($id_attribute_steward)."' AND id_attribute_table = '".addslashes($id_attribute_table)."' AND id_pos_details_split = '".addslashes($id_pos_details_split2)."' AND cancelled = 0";
            $resDup = mysqli_query($connNew, $sqlDup);
            if ($resDup && mysqli_num_rows($resDup) > 0) {
                // Duplicate bill found: throw to stop creation
                throw new Exception("Bill already processed for split: {$split}");
            }

            // Insert new TBL_PURCH row
           echo  $addSql = "INSERT INTO `".TBL_PURCH."` SET
                `id_shop` = '".addslashes($_SESSION['shop'])."',
                `id_doc_type_configuration` = '".addslashes($id_doc_type_configuration)."',
                `doc_no` = '".addslashes($po_no)."',
                `sc_reverse` = '".addslashes(rq('revServiceCharge',0))."',
                `doc_date` = '".addslashes($po_date)."',
                `mdoc_no` = '".addslashes($mdoc_no)."',
                `doc_type` = '".addslashes($doc_type)."',
                `id_mst_outlet` = '".addslashes($outlet)."',
                `id_mst_charges_discounts` = '".addslashes(rq('id_mst_charges_discounts',0))."',
                `discount_charges_percent` = '".addslashes(rq('total_discount_percentage',0))."',
                `pos_bill_type` = '".addslashes($pos_bill_type)."',
                `kot_doc_no` = '".addslashes($kot_doc_no)."',
                `id_pos_details_split` = '".addslashes(implode(',', $splitData[$split]['ids']))."',
                `id_attribute_shift` = '".addslashes($id_attribute_shift)."',
                `id_attribute_steward` = '".addslashes($id_attribute_steward)."',
                `id_mst_country_lang` = '".addslashes(rq('id_mst_country_lang',''))."',
                `pax` = '".addslashes($noOfPax)."',
                `id_attribute_table` = '".addslashes($id_attribute_table)."',
                `sc_charges_net_amount` = '".addslashes($service_charge_amount)."',
                `discount_amount_additional` = '".addslashes($additional_discount_amount)."',
                `others_charges_net_amount` = '".addslashes($others_charges_net_amount)."',
                `sgst_total_items` = '".addslashes($sgst_net_amount)."',
                `cgst_total_items` = '".addslashes($cgst_net_amount)."',
                `igst_total_items` = '".addslashes($igst_net_amount)."',
                `cess_total_items` = '".addslashes($cess_net_amount)."',
                `vat_total_items` = '".addslashes($vat_net_amount)."',
                `sc_sgst` = '".addslashes($sc_sgst)."',
                `sc_cgst` = '".addslashes($sc_cgst)."',
                `surcharge_total_items` = '".addslashes($surcharge_net_amount)."',
                `sub_total_items` = '".addslashes($sub_total_items1)."',
                `total_discount_items` = '".addslashes($total_item_discount_amount)."',
                `net_amount_items` = '".addslashes($net_amount_before_round_and_others)."',
                `round_off_amount` = '".addslashes($round_of_amount)."',
                `grant_total_amount` = '".addslashes($grant_total_amount)."',
                `remarks` = '".addslashes($remarks)."',
                `date_created` = '".currenDateTime()."',
                `last_modified` = '".currenDateTime()."',
                `id_mst_user_created_by` = '".addslashes($_SESSION['userId'])."',
                `id_mst_user_modified_by` = '".addslashes($_SESSION['userId'])."'";

            executeSql($addSql);
            $lastInsertId_purch = $db->insert_id();
            if (!$lastInsertId_purch) {
                throw new Exception("Failed to insert TBL_PURCH for split {$split}");
            }

            $pos_purch_id = $lastInsertId_purch;
            $pos_purch_ids_created[] = $pos_purch_id;
            // Build pos_val mapping for distributing purch_details to pool of bills (needed later)
            foreach ($splitData[$split]['ids'] as $singleId) {
                $pos_val[] = $singleId . '-' . $lastInsertId_purch;
            }

        } else {
            // update path (id_posbilling provided) or when countSplits == 1 update existing
            $pos_bill_id_to_update = addslashes(rq('id_posbilling'));
            // if multiple splits, still create new entry for additional splits; original code had several branches; keep safe behavior:
            if ($countSplits == 1 && $pos_bill_id_to_update != '') {
                // update the existing purchase row
                $updateSql = "UPDATE `".TBL_PURCH."` SET
                    `id_shop` = '".addslashes($_SESSION['shop'])."',
                    `id_doc_type_configuration` = '".addslashes($id_doc_type_configuration)."',
                    `doc_date` = '".addslashes($po_date)."',
                    `sc_reverse` = '".addslashes(rq('revServiceCharge',0))."',
                    `doc_type` = '".addslashes($doc_type)."',
                    `id_mst_outlet` = '".addslashes($outlet)."',
                    `id_mst_charges_discounts` = '".addslashes(rq('id_mst_charges_discounts',0))."',
                    `discount_charges_percent` = '".addslashes(rq('total_discount_percentage',0))."',
                    `pos_bill_type` = '".addslashes($pos_bill_type)."',
                    `id_pos_details_split` = '".addslashes(implode(',', $splitData[$split]['ids']))."',
                    `id_attribute_shift` = '".addslashes($id_attribute_shift)."',
                    `id_attribute_steward` = '".addslashes($id_attribute_steward)."',
                    `id_mst_country_lang` = '".addslashes(rq('id_mst_country_lang',''))."',
                    `pax` = '".addslashes($noOfPax)."',
                    `id_attribute_table` = '".addslashes($id_attribute_table)."',
                    `sc_charges_net_amount` = '".addslashes($service_charge_amount)."',
                    `discount_amount_additional` = '".addslashes($additional_discount_amount)."',
                    `others_charges_net_amount` = '".addslashes($others_charges_net_amount)."',
                    `sgst_total_items` = '".addslashes($sgst_net_amount)."',
                    `cgst_total_items` = '".addslashes($cgst_net_amount)."',
                    `igst_total_items` = '".addslashes($igst_net_amount)."',
                    `cess_total_items` = '".addslashes($cess_net_amount)."',
                    `vat_total_items` = '".addslashes($vat_net_amount)."',
                    `sc_sgst` = '".addslashes($sc_sgst)."',
                    `sc_cgst` = '".addslashes($sc_cgst)."',
                    `surcharge_total_items` = '".addslashes($surcharge_net_amount)."',
                    `sub_total_items` = '".addslashes($sub_total_items1)."',
                    `total_discount_items` = '".addslashes($total_item_discount_amount)."',
                    `net_amount_items` = '".addslashes($net_amount_before_round_and_others)."',
                    `round_off_amount` = '".addslashes($round_of_amount)."',
                    `grant_total_amount` = '".addslashes($grant_total_amount)."',
                    `remarks` = '".addslashes($remarks)."',
                    `last_modified` = '".currenDateTime()."',
                    `id_mst_user_modified_by` = '".addslashes($_SESSION['userId'])."'
                    WHERE id = '".addslashes($pos_bill_id_to_update)."'";
                executeSql($updateSql);
                $pos_purch_id = $pos_bill_id_to_update;
                $pos_purch_ids_created[] = $pos_purch_id;
            } else {
                // fallback: create new if multiple splits or id_posbilling empty
                $addSql = "INSERT INTO `".TBL_PURCH."` SET
                    `id_shop` = '".addslashes($_SESSION['shop'])."',
                    `id_doc_type_configuration` = '".addslashes($id_doc_type_configuration)."',
                    `doc_no` = '".addslashes($po_no)."',
                    `sc_reverse` = '".addslashes(rq('revServiceCharge',0))."',
                    `doc_date` = '".addslashes($po_date)."',
                    `mdoc_no` = '".addslashes($mdoc_no)."',
                    `doc_type` = '".addslashes($doc_type)."',
                    `id_mst_outlet` = '".addslashes($outlet)."',
                    `id_mst_charges_discounts` = '".addslashes(rq('id_mst_charges_discounts',0))."',
                    `discount_charges_percent` = '".addslashes(rq('total_discount_percentage',0))."',
                    `pos_bill_type` = '".addslashes($pos_bill_type)."',
                    `kot_doc_no` = '".addslashes($kot_doc_no)."',
                    `id_pos_details_split` = '".addslashes(implode(',', $splitData[$split]['ids']))."',
                    `id_attribute_shift` = '".addslashes($id_attribute_shift)."',
                    `id_attribute_steward` = '".addslashes($id_attribute_steward)."',
                    `id_mst_country_lang` = '".addslashes(rq('id_mst_country_lang',''))."',
                    `pax` = '".addslashes($noOfPax)."',
                    `id_attribute_table` = '".addslashes($id_attribute_table)."',
                    `sc_charges_net_amount` = '".addslashes($service_charge_amount)."',
                    `discount_amount_additional` = '".addslashes($additional_discount_amount)."',
                    `others_charges_net_amount` = '".addslashes($others_charges_net_amount)."',
                    `sgst_total_items` = '".addslashes($sgst_net_amount)."',
                    `cgst_total_items` = '".addslashes($cgst_net_amount)."',
                    `igst_total_items` = '".addslashes($igst_net_amount)."',
                    `cess_total_items` = '".addslashes($cess_net_amount)."',
                    `vat_total_items` = '".addslashes($vat_net_amount)."',
                    `sc_sgst` = '".addslashes($sc_sgst)."',
                    `sc_cgst` = '".addslashes($sc_cgst)."',
                    `surcharge_total_items` = '".addslashes($surcharge_net_amount)."',
                    `sub_total_items` = '".addslashes($sub_total_items1)."',
                    `total_discount_items` = '".addslashes($total_item_discount_amount)."',
                    `net_amount_items` = '".addslashes($net_amount_before_round_and_others)."',
                    `round_off_amount` = '".addslashes($round_of_amount)."',
                    `grant_total_amount` = '".addslashes($grant_total_amount)."',
                    `remarks` = '".addslashes($remarks)."',
                    `date_created` = '".currenDateTime()."',
                    `last_modified` = '".currenDateTime()."',
                    `id_mst_user_created_by` = '".addslashes($_SESSION['userId'])."',
                    `id_mst_user_modified_by` = '".addslashes($_SESSION['userId'])."'";
                executeSql($addSql);
                $lastInsertId_purch = $db->insert_id();
                if (!$lastInsertId_purch) throw new Exception("Failed to insert TBL_PURCH fallback for split {$split}");
                $pos_purch_ids_created[] = $lastInsertId_purch;
                foreach ($splitData[$split]['ids'] as $singleId) {
                    $pos_val[] = $singleId . '-' . $lastInsertId_purch;
                }
            }
        }
    } // end foreach split

    // Now update/insert purch_details
    // We'll iterate through incoming id_pos_detail and update corresponding records
    // If id_posbilling provided and countSplits==1, update; otherwise, either INSERT into new purch id or update existing id by id

    // Create a pointer for pos_val mapping when distributing new purch_details across multiple bills
    $pos_val_index = 0;

    foreach ($id_pos_detail as $pd) {
        $detailId = addslashes($pd['id']);
        // Always update the purch_details row with latest adj_qty, tax amounts and percent, discount etc.
        $upd = "UPDATE `".TBL_PURCH_DETAILS."` SET
            `adj_qty` = '".addslashes($pd['item_qty'] ?? 0)."',
            `item_sgst_amount` = '".addslashes($pd['item_Tax_sgst'] ?? 0)."',
            `item_cgst_amount` = '".addslashes($pd['item_Tax_cgst'] ?? 0)."',
            `item_igst_amount` = '".addslashes($pd['item_Tax_igst'] ?? 0)."',
            `item_cess_amount` = '".addslashes($pd['item_Tax_cess'] ?? 0)."',
            `item_vat_amount` = '".addslashes($pd['item_Tax_vat'] ?? 0)."',
            `item_surcharge_amount` = '".addslashes($pd['item_Tax_surcharge'] ?? 0)."',
            `item_sgst_percent` = '".addslashes($pd['item_Tax_sgst_percentage'] ?? 0)."',
            `item_cgst_percent` = '".addslashes($pd['item_Tax_cgst_percentage'] ?? 0)."',
            `item_igst_percent` = '".addslashes($pd['item_Tax_igst_percentage'] ?? 0)."',
            `item_cess_percent` = '".addslashes($pd['item_Tax_cess_percentage'] ?? 0)."',
            `item_vat_percent` = '".addslashes($pd['item_Tax_vat_percentage'] ?? 0)."',
            `item_surcharge_percent` = '".addslashes($pd['item_Tax_surcharge_percentage'] ?? 0)."',
            `item_discount_percent` = '".addslashes($pd['item_discount_percentage'] ?? 0)."',
            `item_discount_amount` = '".addslashes($pd['item_discount_amount'] ?? 0)."',
            `id_mst_charges_sales_interstate` = '".addslashes($pd['id_mst_charges_sales_Interstate'] ?? 0)."',
            `main_unit` = '".addslashes($pd['id_mst_attributes_unit_main'] ?? 0)."',
            `rate_per_main_unit` = '".addslashes($pd['sale_rate'] ?? 0)."',
            `last_modified` = '".currenDateTime()."',
            `id_mst_user_modified_by` = '".addslashes($_SESSION['userId'])."'
            WHERE `id` = '".addslashes($detailId)."'";

        executeSql($upd);

        // If original record not found or we need to insert (some branches in original code), insert if necessary
        // For safety, check if row exists; if not, insert new row into purch_details and link to appropriate purch id.
        $checkSql = "SELECT id, id_pos_purch FROM `".TBL_PURCH_DETAILS."` WHERE id = '".addslashes($detailId)."' LIMIT 1";
        $checkRes = mysqli_query($connNew, $checkSql);
        if (!$checkRes || mysqli_num_rows($checkRes) == 0) {
            // Insert into purch_details mapping to next available purch id (pos_val)
            if (!empty($pos_val)) {
                // pop from pos_val queue
                $map = $pos_val[$pos_val_index % count($pos_val)];
                $mapParts = explode('-', $map);
                $targetPurchId = isset($mapParts[1]) ? $mapParts[1] : (isset($pos_purch_ids_created[0]) ? $pos_purch_ids_created[0] : null);
                $pos_val_index++;
            } else {
                $targetPurchId = isset($pos_purch_ids_created[0]) ? $pos_purch_ids_created[0] : null;
            }

            $items = addslashes($pd['item_id'] ?? '');
            $item_desc = addslashes($pd['item_description'] ?? '');
            $item_amount = addslashes($pd['item_amount'] ?? 0);
            $item_amount_before_discount = addslashes($pd['item_TotalAmountItem1'] ?? 0);
            $id_mst_charges_sgst = addslashes($pd['item_Tax_sgst_id'] ?? 0);
            $id_mst_charges_cgst = addslashes($pd['item_Tax_cgst_id'] ?? 0);
            $id_mst_charges_igst = addslashes($pd['item_Tax_igst_id'] ?? 0);
            $id_mst_charges_cess = addslashes($pd['item_Tax_cess_id'] ?? 0);
            $id_mst_charges_vat = addslashes($pd['item_Tax_vat_id'] ?? 0);
            $id_mst_charges_surcharge = addslashes($pd['item_Tax_surcharge_id'] ?? 0);
            $adj_qty = addslashes($pd['item_qty'] ?? 0);

            $ins = "INSERT INTO `".TBL_PURCH_DETAILS."` SET
                `id_pos_purch` = '".addslashes($targetPurchId)."',
                `id_mst_items` = '".addslashes($items)."',
                `id_mst_items_details` = '".addslashes($pd['item_details'] ?? '')."',
                `id_mst_outlet` = '".addslashes($outlet)."',
                `id_mst_charges_sales_local` = '".addslashes($pd['id_mst_charges_sales_Interstate'] ?? 0)."',
                `qty` = '".addslashes($pd['item_qty'] ?? 0)."',
                `item_amount` = '".$item_amount."',
                `item_description` = '".$item_desc."',
                `id_mst_charges_sgst` = '".$id_mst_charges_sgst."',
                `id_mst_charges_cgst` = '".$id_mst_charges_cgst."',
                `id_mst_charges_igst` = '".$id_mst_charges_igst."',
                `id_mst_charges_cess` = '".$id_mst_charges_cess."',
                `id_mst_charges_vat` = '".$id_mst_charges_vat."',
                `id_mst_charges_surcharge` = '".$id_mst_charges_surcharge."',
                `item_amount_before_discount` = '".$item_amount_before_discount."',
                `adj_qty` = '".$adj_qty."',
                `item_sgst_amount` = '".addslashes($pd['item_Tax_sgst'] ?? 0)."',
                `item_cgst_amount` = '".addslashes($pd['item_Tax_cgst'] ?? 0)."',
                `item_igst_amount` = '".addslashes($pd['item_Tax_igst'] ?? 0)."',
                `item_cess_amount` = '".addslashes($pd['item_Tax_cess'] ?? 0)."',
                `item_vat_amount` = '".addslashes($pd['item_Tax_vat'] ?? 0)."',
                `item_surcharge_amount` = '".addslashes($pd['item_Tax_surcharge'] ?? 0)."',
                `item_sgst_percent` = '".addslashes($pd['item_Tax_sgst_percentage'] ?? 0)."',
                `item_cgst_percent` = '".addslashes($pd['item_Tax_cgst_percentage'] ?? 0)."',
                `item_igst_percent` = '".addslashes($pd['item_Tax_igst_percentage'] ?? 0)."',
                `item_cess_percent` = '".addslashes($pd['item_Tax_cess_percentage'] ?? 0)."',
                `item_vat_percent` = '".addslashes($pd['item_Tax_vat_percentage'] ?? 0)."',
                `item_surcharge_percent` = '".addslashes($pd['item_Tax_surcharge_percentage'] ?? 0)."',
                `item_discount_percent` = '".addslashes($pd['item_discount_percentage'] ?? 0)."',
                `item_discount_amount` = '".addslashes($pd['item_discount_amount'] ?? 0)."',
                `id_mst_charges_sales_interstate` = '".addslashes($pd['id_mst_charges_sales_Interstate'] ?? 0)."',
                `main_unit` = '".addslashes($pd['id_mst_attributes_unit_main'] ?? 0)."',
                `rate_per_main_unit` = '".addslashes($pd['sale_rate'] ?? 0)."',
                `date_created` = '".currenDateTime()."',
                `last_modified` = '".currenDateTime()."',
                `id_mst_user_created_by` = '".addslashes($_SESSION['userId'])."',
                `id_mst_user_modified_by` = '".addslashes($_SESSION['userId'])."'";
            executeSql($ins);
        }
        $pos_val_index++;
    } // end foreach purch detail

    // Update totals on each original id_kot if present (bulk update)
    $id_kot_arr = rqArr('id_kot');
    foreach ($id_kot_arr as $idkot) {
        $total_qty = selectColumn(TBL_PURCH_DETAILS, 'sum(qty)', " WHERE `id_pos_purch`='".addslashes($idkot)."'");
        $total_adj_qty = selectColumn(TBL_PURCH_DETAILS, 'sum(adj_qty)', " WHERE `id_pos_purch`='".addslashes($idkot)."'");
        $UpdateTotalQTY = "UPDATE `".TBL_PURCH."` SET `total_qty`='".addslashes($total_qty)."', `total_adj_qty`='".addslashes($total_adj_qty)."' WHERE `id`='".addslashes($idkot)."'";
        mysqli_query($connNew, $UpdateTotalQTY);
    }

    // Guest handling: insert/update
    $guest_name = trim(rq('guest_name',''));
    $guest_mobile = trim(rq('guest_mobile',''));
    if ($guest_name !== '' || $guest_mobile !== '') {
        // try to find by both fields if provided, otherwise by mobile or name
        $guest_where = [];
        if ($guest_mobile !== '') $guest_where[] = "mobile = '".addslashes($guest_mobile)."'";
        if ($guest_name !== '') $guest_where[] = "name = '".addslashes($guest_name)."'";
        $guest_where_sql = implode(' AND ', $guest_where);

        // restrict to a single result if exists
        $pos_Guest_sql = "SELECT * FROM pos_guest WHERE {$guest_where_sql} LIMIT 1";
        $resultPosGuest = mysqli_query($connNew, $pos_Guest_sql);
        $numGuestRows = $resultPosGuest ? mysqli_num_rows($resultPosGuest) : 0;
        $posGuestResult = $numGuestRows ? mysqli_fetch_object($resultPosGuest) : null;

        $pos_purch_id_for_guest = rq('id_posbilling') != '' ? rq('id_posbilling') : (end($pos_purch_ids_created) ? end($pos_purch_ids_created) : '');

        if ($numGuestRows == 0) {
            // insert
            $posGuestSql = "INSERT INTO pos_guest SET `name` = '".addslashes($guest_name)."', `mobile` = '".addslashes($guest_mobile)."', `ids_pos_purch` = '".addslashes($pos_purch_id_for_guest)."'";
            executeSql($posGuestSql);
        } else {
            // append current purch id to the guest.ids_pos_purch if not present
            $existingIds = $posGuestResult->ids_pos_purch ? explode(',', $posGuestResult->ids_pos_purch) : [];
            $existingIds = array_filter(array_map('trim', $existingIds));
            if (!in_array($pos_purch_id_for_guest, $existingIds)) $existingIds[] = $pos_purch_id_for_guest;
            $ids_str = implode(',', array_unique($existingIds));
            $posGuestSql = "UPDATE pos_guest SET `ids_pos_purch` = '".addslashes($ids_str)."' WHERE id = '".addslashes($posGuestResult->id)."'";
            executeSql($posGuestSql);
        }
    }

    // Commit all changes
    mysqli_commit($connNew);

    // Return success — original code often redirected; we return the last created/updated id
    echo "SUCCESS";
} catch (Exception $e) {
    // rollback on error
    mysqli_rollback($connNew);
    // log error optionally
    error_log("POS Save Error: " . $e->getMessage());
    // show user-friendly message
    echo "ERROR: " . htmlspecialchars($e->getMessage());
}

print_r($retunDocConfig);die;
    // ---- 3️⃣ Find KOTs for this table ----
    $kot_sql = "
        SELECT id, mdoc_no
        FROM pos_purch 
        WHERE pos_bill_type = '1'
          AND cancelled = 0
          AND doc_type != '24'
          AND id IN (
              SELECT id_pos_purch 
              FROM pos_purch_details 
              WHERE qty - adj_qty > 0
          )
          AND id_attribute_table = $tbl_id
    ";
    $kot_res = mysqli_query($connNew, $kot_sql);

    $kot_display = [];
    while ($row = mysqli_fetch_assoc($kot_res)) {
        $kot_display[] = $row['mdoc_no'];
    }

    // ---- 4️⃣ Steward & Shift Info ----
    $kot_info_sql = "
        SELECT id_attribute_steward, id_attribute_shift, pax 
        FROM pos_purch 
        WHERE pos_bill_type = '1' 
          AND cancelled = 0 
          AND id_attribute_table = $tbl_id
        LIMIT 1
    ";
    $kot_info_res = mysqli_query($connNew, $kot_info_sql);
    $kot_info = mysqli_fetch_assoc($kot_info_res);

    $id_attribute_steward = $kot_info['id_attribute_steward'] ?? 0;
    $id_attribute_shift   = $kot_info['id_attribute_shift'] ?? 0;
    $pax_total            = $kot_info['pax'] ?? $pax;

    $steward_name = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id='" . $id_attribute_steward . "'");
    $shift_name   = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id='" . $id_attribute_shift . "'");
    $outlet_name  = selectColumn(TBL_ATTRIBUTES, 'field_value', "WHERE id='" . $outlet_id_tbl . "'");

    // ---- 5️⃣ Append data ----
    if (!isset($outlet_data[$outlet_id_tbl])) {
        $outlet_data[$outlet_id_tbl] = [
            'outlet_id' => $outlet_id_tbl,
            'outlet_name' => $outlet_name,
            'tables' => []
        ];
    }

    $outlet_data[$outlet_id_tbl]['tables'][] = [
        'table_id' => $tbl_id,
        'table_name' => $tbl_name,
        'kot_list' => $kot_display,
        'steward' => $steward_name,
        'shift' => $shift_name,
        'pax' => $pax_total,
        'bill_no' => $mdoc_no,
        'doc_no' => $po_no,
        'id_doc_type_configuration' => $id_doc_type_configuration,
    ];
}

// ---- 6️⃣ Output JSON ----
echo json_encode([
    'status' => 'success',
    'date' => $date,
    'data' => array_values($outlet_data),
    'message' => 'Bill config generated per table successfully'
]);
?>
