<?php include_once("../../config/auto_loader.php");

// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';
// exit;
$id_attribute_table = $_REQUEST['id_attribute_table'];
$attribute_id = $_REQUEST['attribute_id'];
$id_posbilling = $_REQUEST['id_posbilling'];
$net_amount = $_REQUEST['net_amount'];
$po_date1 = $_REQUEST['po_date1'];
$id_fo_folio_to = "";
$id_fo_bill = "";
$sqlRoomNumber = mysqli_query($connNew, "SELECT DISTINCT 
    room.id, room.room_no, room.id_mst_room_types, room.room_status,
    resdetails.id_fo_reservations, resdetails.id_mst_guest, resdetails.id_fo_folio_to,
    resdetails.id_fo_bill, resdetails.order_by_room, fo_bill.status as occupanyStatus
    FROM mst_room_no_allocation as room 
    INNER JOIN fo_reservations_details as resdetails ON room.id = resdetails.id_mst_room_no_allocation 
    INNER JOIN fo_bill as fo_bill ON fo_bill.id = resdetails.id_fo_bill 
    WHERE fo_bill.status = '1'  
    AND resdetails.checkout_status = '0' 
    AND resdetails.no_showoff = '0' 
    AND resdetails.id_mst_room_no_allocation = '".$attribute_id."'");

if ($sqlRoomNumber) {
    $firstRecord = mysqli_fetch_assoc($sqlRoomNumber);
    if ($firstRecord) {
        $id_fo_folio_to = $firstRecord['id_fo_folio_to'];
        $id_fo_bill = $firstRecord['id_fo_bill'];
    }
} else {
    echo "Error: " . mysqli_error($connNew);
}

$id_fo_folio = selectColumn('fo_folio','id'," WHERE `id_fo_bill` = '".$id_fo_bill."'");
$insertGrid =  "UPDATE `".TBL_PURCH."` SET `id_fo_bill`='".$id_fo_bill."', `id_fo_folio`='".addslashes($id_fo_folio)."', `id_fo_folio_to`='".addslashes($id_fo_folio)."' where`id` = '".$id_posbilling."'";
mysqli_query($connNew,$insertGrid);	

$insertSql = "INSERT INTO ".TBL_PURCH_PAY." SET id_purch='".$id_posbilling."', id_type='7', payment_mode='ROOMTO', amount='".$net_amount."', id_fo_bill='".$id_fo_bill."',
doc_date='".date('Y-m-d',  strtotime($po_date1))."', time='".date('H:i:s')."', ccredit='ROOMTO'";

$insertSql .= ",last_modified='".date('Y-m-d H:i:s')."', date_created='".date('Y-m-d H:i:s')."', id_mst_user_created_by='".$_SESSION['userId']."', id_mst_user_modified_by='".$_SESSION['userId']."' ";
mysqli_query($connNew,$insertSql);  

$total_amount_recevied	=  selectColumn(TBL_PURCH_PAY,'sum(amount)'," WHERE id_purch='".$id_posbilling."'");
$UpdateTotalAmount =  "UPDATE `".TBL_PURCH."` SET `payment_amount_received`='".$total_amount_recevied."' where`id` = '".$id_posbilling."'";
mysqli_query($connNew,$UpdateTotalAmount);
echo "true";
exit;
?>