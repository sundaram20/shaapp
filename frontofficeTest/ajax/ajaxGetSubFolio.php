<?php include_once("../../config/auto_loader.php"); ?>

<?php
$id_folio = $_REQUEST['id_folio'];
$text = '';
$resCat = mysqli_query($connNew, "SELECT * FROM `fo_folio` WHERE folio_status = '0' and status='1' and id_parent_folio = '".$id_folio."'");

if (mysqli_num_rows($resCat) > 0) {
    $text = '<div class="input-group date"><div class="input-group-addon"><i class="fa fa-calendar"></i></div>
    <select class="form-control first-input select2" style="width:100% !important;"  data-folio_type="sub" name="id_fo_bill" id="id_fo_bill" onChange="getFolioInvoiceDetails(this);"><option value="'.$id_folio.'">Select Sub Folio </option><option value="'.$id_folio.'">Select Main Folio </option>';

    while ($resultCat = mysqli_fetch_object($resCat)) {
        $id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$resultCat->id_mst_guest."'");
        $Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
        $Firstname = selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
        $Lastname =	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
        $guestName = $Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));

        $id_mst_room_no_allocation = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$resultCat->id_fo_bill."'");
        $roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
        if ($resultCat->id == $_REQUEST['ID']) {
            $selected = 'selected="selected"';
        } else {
            $selected = '';
        }

        $text .= '<option '.$selected.'  value="'.$resultCat->id.'">'.$resultCat->mdoc_no.'---    Room No:'.$roomNumber.' ---  Guest: '.$guestName.'</option>';
    }
    $text .= "</select></div></div>";
}

echo $text;
?>