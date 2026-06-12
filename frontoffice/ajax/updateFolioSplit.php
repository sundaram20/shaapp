<?php
include_once("../../config/auto_loader.php");
include_once("../functions/function.php");

$splitArray = explode(',',$_REQUEST['folio_split']);

 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
 $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
		 
if ($_REQUEST['folio_split'] != '') {
	$id_mst_guest = $_REQUEST['id_mst_guest'];
	$id_resevation = $_REQUEST['id_resevation'];
	$id_fo_bill	= $_REQUEST['id_fo_bill'];
	$id_owner_room	= $_REQUEST['id_owner_room'];
	$folio_type	= $_REQUEST['folio_type'];

	// $get_owner_room = mysqli_query($connNew, "select * from fo_bill where id_owner_room = '".$id_owner_room."'");
	// if (mysqli_num_rows($get_owner_room) > 0) {
	// 	$prev_folio = selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id_owner_room` = '".$id_owner_room."'");
	// 	$prev_fo_bill = selectColumn(FO_BILL,'id'," WHERE `id_owner_room` = '".$id_owner_room."'");
	// 	$reservation_detail_query = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_allocation != '".$id_owner_room."' and id_fo_reservations = '".$id_resevation."' and id_fo_folio_to = '".$prev_folio."'");
	// 	$reservation_detail_result = mysqli_fetch_object($reservation_detail_query);
	// 	mysqli_query($connNew, "update fo_bill set id_owner_room = '".$reservation_detail_result->id_mst_room_no_allocation."' where id = '".$prev_fo_bill."'");
	// }
	
	$folio_bill_query = mysqli_query($connNew, "select * from fo_folio where id_fo_bill = '".$id_fo_bill."' order by id desc");
	$folio_bill_result = mysqli_fetch_object($folio_bill_query);
	$folio_id = $folio_bill_result->id ?? 0;

	$TableArray = array();
	$TableArrayID = array();
	foreach($splitArray as $Data ) {
		$Data;
		$split = explode('-',$Data);
		$TableArray['Table'][$split[1]][$split[0]]['id'] = $split[0];
		$TableArray['Table'][$split[1]][$split[0]]['tableName'] = $split[1];
		$TableArrayID[$split[1]][] = $split[0];
	}

	$resvId = $id_resevation;
	$id_mst_guest = $id_mst_guest;
	$id_doc_type = '804'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name = 'fo_folio';
	$date = date('Y-m-d');
	$id_subsection = '1';
	$id_shop = $_SESSION['shop'];
	$docConfig = docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);

	$insertdocConfig = "INSERT INTO fo_folio  SET
		`id_mst_shops`='".$_SESSION['shop']."',				
		`id_mst_guest`='".$id_mst_guest."',
		`id_fo_bill`='".$_REQUEST['id_fo_bill']."',
		`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
		`doc_no`='".addslashes($docConfig['po_no'])."',
		`doc_date`='".date('Y-m-d',strtotime($NightAuditDated))."',
		`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
		`doc_type` = '".addslashes($id_doc_type)."',
		`date_created` = '".currenDateTime()."',
		`id_mst_user_created_by` = '".$_SESSION['userId']."',
		`last_modified` = '".currenDateTime()."',
		`id_mst_user_modified_by` = '".$_SESSION['userId']."'";
	mysqli_query($connNew,$insertdocConfig);
	$id_fo_folio = mysqli_insert_id($connNew);

	$sql_s = "SELECT * FROM ".FO_BILL." where doc_type = '803' order by doc_no desc limit 1";
	$db->query($sql_s);
	$inc_no = $db->num_rows();
	$row = $db->fetch_object();
    $id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id_mst_shops` = '".addslashes($_SESSION['shop'])."' and id='".$_REQUEST['id_fo_bill']."'");

 	$selectnew = "SELECT mst_doc_type_configuration.doc_type, mst_doc_type_configuration_detail.* FROM mst_doc_type_configuration
	INNER JOIN mst_doc_type_configuration_detail ON mst_doc_type_configuration.id=mst_doc_type_configuration_detail.id_mst_doc_type_config
	 where mst_doc_type_configuration.doc_type = '803' order by mst_doc_type_configuration.id desc limit 1";
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

	$insertGrid = "INSERT INTO ".FO_BILL." SET
		`id_reservations` = '".$resvId."',
		`id_mst_shops`='".$_SESSION['shop']."',
		`folio_no`='".addslashes($_REQUEST['po_no'])."',
		`id_fo_folio`='".addslashes($id_fo_folio)."',
		`id_fo_folio_to`='".addslashes($id_fo_folio)."',
		`date_created` = '".currenDateTime()."',
		`id_mst_user_created_by` = '".$_SESSION['userId']."',
		`last_modified` = '".currenDateTime()."',
		`id_mst_user_modified_by` = '".$_SESSION['userId']."'";
	mysqli_query($connNew,$insertGrid);
	$id_fo_bill = mysqli_insert_id($connNew);
	foreach($TableArrayID as $tablename => $splitid) {
		$PID = implode(',',$splitid);
		$insertFolioGrid = "UPDATE `".$tablename."` SET `id_fo_folio_to`='".addslashes($id_fo_folio)."',id_fo_bill = '".$id_fo_bill."' where`id` IN (".$PID.")";
		mysqli_query($connNew,$insertFolioGrid);
	}
	if ($folio_type == "1") {
		mysqli_query($connNew, "update fo_folio set id_parent_folio = '".$folio_id."', id_fo_bill = '".$id_fo_bill."' where id = '".$id_fo_folio."'");
	}
	mysqli_query($connNew, "update fo_bill set id_owner_room = '".$id_owner_room."' where id = '".$id_fo_bill."'");
	mysqli_query($connNew, "update fo_folio set id_fo_bill = '".$id_fo_bill."' where id = '".$id_fo_folio."'");
	
	echo " New Folio Successfully ".$docConfig['prefix'].addslashes($docConfig['po_no']).addslashes($docConfig['suffix']);
	die;
} else {
	echo " Please Select";
	die;
}
?>