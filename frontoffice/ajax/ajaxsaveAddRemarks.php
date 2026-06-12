<?php
include_once("../../config/auto_loader.php");
// debugData($_REQUEST);
// die();
$id_reservation	= $_REQUEST['editid'];

foreach ($_REQUEST['PostChargesDataArray'] as $Q => $listData) {

	$attribute_name = selectColumn('mst_attributes','field_value'," WHERE `id` = '".$listData['id_remarks']."'");
	if ($attribute_name == 'Internal Remarks') {
		$reservation_query = mysqli_query($connNew, "update fo_reservations set res_internal_remarks = '".$listData['res_remark']."' where id = '".$id_reservation."'");
	}
	$roomdetails = " INSERT INTO `fo_remarks_details` SET id_fo_reservations = '".$id_reservation."', id_type = '".$listData['id_remarks'] ."', remark = '".$listData['res_remark']."', id_fo_folio = '".$_REQUEST['id_folio']."'";
	mysqli_query($connNew,$roomdetails);
}

$ResultArray = array();
$ResultArray['message'] = 'Remark Update Successfully';
$ResultArray['id_follio'] = $_REQUEST['id_folio'];
echo json_encode($ResultArray);
die;
?>