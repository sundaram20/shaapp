<?php

include_once("../config/auto_loader.php");

$id_inv_items = isset($_POST["id_inv_items"]) ? (int) $_POST["id_inv_items"] : 0;
$shopId = (int) $_SESSION['shop'];

$res = [];

$sql = "
    SELECT
        i.name,
        i.conversion_qty,
        i.id_mst_attributes_unit_main,
        i.id_mst_attributes_unit_alt,
        i.id_mst_attributes_store,
        i.id_mst_charges_purchase_local,

        main_unit.field_value AS main_unit,
        alt_unit.field_value AS alt_unit,
        store.field_value AS store

    FROM `" . TBL_INV_ITEMS . "` AS i

    LEFT JOIN `" . TBL_ATTRIBUTES . "` AS main_unit
        ON main_unit.id = i.id_mst_attributes_unit_main
        AND main_unit.id_shop = '" . $shopId . "'
        AND main_unit.status = '1'

    LEFT JOIN `" . TBL_ATTRIBUTES . "` AS alt_unit
        ON alt_unit.id = i.id_mst_attributes_unit_alt
        AND alt_unit.id_shop = '" . $shopId . "'
        AND alt_unit.status = '1'

    LEFT JOIN `" . TBL_ATTRIBUTES . "` AS store
        ON store.id = i.id_mst_attributes_store
        AND store.id_shop = '" . $shopId . "'
        AND store.status = '1'

    WHERE i.id_shop = '" . $shopId . "'
      AND i.id = '" . $id_inv_items . "'
      AND i.status = '1'
    LIMIT 1
";

$db->query($sql);

if ($db->num_rows() > 0) {

    $row = $db->fetch_object();

    $res['name'] = $row->name;
    $res['main_unit'] = $row->main_unit;
    $res['alt_unit'] = $row->alt_unit;
    $res['conversion_qty'] = $row->conversion_qty;
    $res['id_mst_attributes_store'] = $row->id_mst_attributes_store;
    $res['store'] = $row->store;
    $res['id_mst_charges_purchase_local'] = $row->id_mst_charges_purchase_local;

} else {

    $res['name'] = '';
    $res['main_unit'] = '';
    $res['alt_unit'] = '';
    $res['conversion_qty'] = '';
    $res['id_mst_attributes_store'] = '';
    $res['store'] = '';
    $res['id_mst_charges_purchase_local'] = '';
}

header('Content-Type: application/json');

echo json_encode($res);
exit;
?>