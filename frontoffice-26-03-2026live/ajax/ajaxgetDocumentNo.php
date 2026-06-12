<?php
include_once("../../config/auto_loader.php");

$id_mst_hotels = $_REQUEST['id_mst_hotels'];
$doc_type = $_REQUEST['doc_type'];
$id_reservation = $_REQUEST['id_reservation'];
$id_fo_bill = $_REQUEST['id_fo_bill'];

$id_fo_folio_to = $_REQUEST['id_folio'];

$complimentory = selectColumn('fo_reservations','res_complimentary_booking',"WHERE id = '".addslashes($id_reservation)."'");
//Check Room TAx Value is zero===========================
if($complimentory == 0){
$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($id_reservation)."' and  id_fo_folio_to='".addslashes($id_fo_folio_to)."' ");
if(mysqli_num_rows($sqlOrderDetail) >0 ){
	$CheckOrderByRoom=array();
	while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
		
		$CheckOrderByRoom[$rowOrderDetail->order_by_room]=$rowOrderDetail->order_by_room;
		
	}
}
}
$CheckOrderByRoom	=implode(',',$CheckOrderByRoom);

$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($id_reservation)."' and  order_by_room IN (".addslashes($CheckOrderByRoom).")  and `no_showoff`='0' and  (tariff_price_per_day_per_room=0 || tax_per_day_per_room=0)");
if(mysqli_num_rows($sqlOrderDetail) >0 ){
	$data = array();
	$data['status'] = '0';
	$data['message'] = 'Tax value can’t be 0.';
	echo json_encode($data);die;
	
}
//Check RoomTax Value is Zero============================



 		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));

$data = array();
$sqlVa = "SELECT * FROM ".FO_BILL." where id='".$id_fo_bill."' and id_reservations='".$id_reservation."' and `doc_no`='0'";
$Vali =	mysqli_query($connNew,$sqlVa);
if (mysqli_num_rows($Vali) > 0) {
	$sql_s = "SELECT * FROM ".FO_BILL." where doc_type = $doc_type ORDER BY `fo_bill`.`doc_date` DESC, `fo_bill`.`doc_no` DESC limit 1";
	$db->query($sql_s);
	$inc_no = $db->num_rows();
	$row = $db->fetch_object();
    $id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id_mst_shops` = '".addslashes($_SESSION['shop'])."' and id='".$id_fo_bill."' and id_reservations='".$id_reservation."'");

 	 $selectnew = "SELECT mst_doc_type_configuration.doc_type, mst_doc_type_configuration_detail.* FROM mst_doc_type_configuration
	INNER JOIN mst_doc_type_configuration_detail ON mst_doc_type_configuration.id=mst_doc_type_configuration_detail.id_mst_doc_type_config
	AND mst_doc_type_configuration_detail.id_subsection = $id_mst_hotels where mst_doc_type_configuration.doc_type = $doc_type
	order by mst_doc_type_configuration.id desc limit 1";
	$resnew = mysqli_query($connNew,$selectnew);
	while ($rownew = mysqli_fetch_object($resnew)) {
		$id_mst_doc_type_config	= $rownew->id_mst_doc_type_config;
		$suffix = $rownew->suffix; 
		$prefix = $rownew->prefix;
		$start_no = $rownew->start_no;
		if ($start_no == '') {
			$start_no = 1;
		}
	}
	if ($row->doc_no=='') {
		$total = $start_no + $row->doc_no;
    } else {
		$total = $row->doc_no + 1;
	}	
	$data = $prefix.($total) .$suffix;

	 $sql = "UPDATE ".FO_BILL." SET 
		`id_doc_type_configuration`='".$id_mst_doc_type_config."',
		`doc_no`='".$total."',
		`mdoc_no`='".$data."',
		`doc_type`='".$doc_type."',
		`doc_date`='".date($NightAuditDated.' H:i:s')."'
	WHERE id = '".$id_fo_bill."' and id_reservations = '".$id_reservation."'";

	if (mysqli_query($connNew,$sql)) {
		$data = array();
		$data['status'] = '1';
		$data['message'] = 'FO Bill Generated Sucuessfully. Invoice no: '.$prefix.($total) .$suffix;
		$data['value'] = $id_fo_folio_to;
	}
} else {
	$data = array();
	$data['status'] = '0';
	$data['message'] = 'FO Bill No Already Generated for this Folio';
}
echo json_encode($data);
?>