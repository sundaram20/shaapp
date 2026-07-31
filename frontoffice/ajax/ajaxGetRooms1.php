<?php include_once("../../config/auto_loader.php");
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$DayNightAudit	= date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$today =date('Y-m-d',strtotime($_REQUEST['dated']));// date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$nightAuditDate =$_REQUEST['dated'] ;//"'".date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)))."'";

$is_force_checkout = selectColumn('mst_shops','force_system_date_as_checkout_date'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
$check_checkout_date = 0;
$system_date = strtotime(date('Y-m-d'));
if ($is_force_checkout && $system_date != strtotime($today)) {
  $check_checkout_date = 1;
}

$id_mst_room_types = $_REQUEST['id_mst_room_types'];
$reservation_id = encryptor(decrypt,$_REQUEST['Id']);



$fo_reservationCheck = mysqli_query($connNew, "select * from fo_reservations where id = '".$reservation_id."'");
$reservation_resultCheck = mysqli_fetch_object($fo_reservationCheck);
 $reservation_checkout	=	date('Y-m-d', strtotime($reservation_resultCheck->checkout . ' -1 day'));//date('Y-m-d',strtotime($reservation_resultCheck->checkout));

$all_rooms = [];
$sql = "SELECT * FROM " . TBL_ROOMNO . " WHERE id_mst_room_types = '" . $id_mst_room_types . "' and management_block='No' and status = 1 GROUP BY room_no";
$room_no_allocations = mysqli_query($connNew, $sql);

while ($row = mysqli_fetch_object($room_no_allocations)) { //echo '<br/>=====>'.$row->room_no;
    $all_rooms[$row->id] = [
		'room_id' => $row->id,
		'room_no' => $row->room_no,
	];
}

$occupied_room_ids = [];
$allocation_room_ids = [];
$allocation_All_room_ids=[];
$occupied_based_on_reservation_room_ids = [];

$occupied_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."' and room.id_mst_room_types = '".$id_mst_room_types."' and room_availability = 'Checkin'");
if (mysqli_num_rows($occupied_room_query) > 0) {
    while ($row = mysqli_fetch_object($occupied_room_query)) {
		$occupied_room_ids[$row->id] = [
			'room_id' => $row->id,
			'reservation_id' => $row->id_fo_reservations,
		];
	}
}

$folio_array = [];
$occupied_room_based_on_reservation_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."' and room.id_mst_room_types = '".$id_mst_room_types."' and resdetails.id_fo_reservations = '".$reservation_id."' and room_availability = 'Checkin'");
if (mysqli_num_rows($occupied_room_based_on_reservation_query) > 0) {
    while ($row = mysqli_fetch_object($occupied_room_based_on_reservation_query)) {
		$folio = selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$row->id_fo_folio_to."'");
		$id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");
		$Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
		$Firstname = selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
		$Lastname = selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$row->id_mst_guest."'");
		$guestName = $Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));
		$folio_text = $folio.'--- Guest: '.$guestName;
		$folio_array[$row->id_fo_folio_to] = [
			'folio' => $folio_text,
			'room_id' => $row->id
		];
		$occupied_based_on_reservation_room_ids[$row->id] = [
			'room_id' => $row->id,
			'reservation_id' => $row->id_fo_reservations,
		];
	}
}

$folio_array = json_encode($folio_array);

  //echo "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0'and  resdetails.dated BETWEEN '".$today."' AND '".$reservation_checkout."' and room.id_mst_room_types = '".$id_mst_room_types."'  and room_availability = 'Reserv'";
$allocated_All_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0'and  resdetails.dated BETWEEN '".$today."' AND '".$reservation_checkout."' and room.id_mst_room_types = '".$id_mst_room_types."'  and room_availability = 'Reserv'");
if (mysqli_num_rows($allocated_All_room_query) > 0) {
    while ($row = mysqli_fetch_object($allocated_All_room_query)) {
		$allocation_All_room_ids[$row->id] = [
			'room_id' => $row->id,
			'reservation_id' => $row->id_fo_reservations,
		];
	}
}

$allocated_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."' and room.id_mst_room_types = '".$id_mst_room_types."' and resdetails.id_fo_reservations = '".$reservation_id."' and room_availability = 'Reserv'");
if (mysqli_num_rows($allocated_room_query) > 0) {
    while ($row = mysqli_fetch_object($allocated_room_query)) {
		$allocation_room_ids[$row->id] = [
			'room_id' => $row->id,
			'reservation_id' => $row->id_fo_reservations,
		];
	}
}

$pending_folio_id = '0';
$checkin_pending_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$reservation_id."' and no_showoff = '0' and room_availability = 'Reserv'");
if (mysqli_num_rows($checkin_pending_query) > 0) {
	while ($row = mysqli_fetch_object($checkin_pending_query)) {
		$pending_folio_id = $row->fo_folio_temp;
	}
}

$room_type = selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_mst_room_types."' and status='1'");
$fo_reservation = mysqli_query($connNew, "select * from fo_reservations where id = '".$reservation_id."'");
$reservation_result = mysqli_fetch_object($fo_reservation);
$sqlOrderDetailRoom = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($reservation_id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' and room_availability = 'Reserv' and `no_showoff`='0' group by order_by_room order by id asc");
$roomNoshowoffArray = array();
if(mysqli_num_rows($sqlOrderDetailRoom) > 0) {
	while ($rowOrderDetailRoom = mysqli_fetch_object($sqlOrderDetailRoom)) {
		// $listRoomInBooking += 1;
		if($rowOrderDetailRoom->no_showoff == 1) {
			$roomNoshowoffArray[] = $rowOrderDetailRoom->id_mst_room_no_reserved;
		}
	}
}

$reservation_rooms = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($reservation_id)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' group by order_by_room order by id asc");
$listRoomInBooking = 0;
if(mysqli_num_rows($reservation_rooms) > 0) {
	while ($rowOrderDetailRoom = mysqli_fetch_object($reservation_rooms)) {
		$listRoomInBooking += 1;
	}
}

$listRoomInBooking = $listRoomInBooking - count($roomNoshowoffArray);
$roomCount = "'".$listRoomInBooking."'";

$start .='
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<h4>'.$room_type.'</h4>
			<div style="text-align:center; margin:9px; font-size:13px;" id="RoomCountSelected_'.$listRoomInBooking.'_'.$reservation_id.'"></div>
		</div>
		<div class="col-md-12">
			<div class="row text-center">';

// echo "<pre>";
//debugData($allocation_room_ids);echo "<br>";
//debugData($allocation_All_room_ids);echo "<br>";
 //debugData($occupied_room_ids);echo "<br>";
//debugData($occupied_based_on_reservation_room_ids);echo "<br>";
// debugData($all_rooms);
// echo "sdf";
// exit;

 $checkin = date('Y-m-d',strtotime(selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$reservation_id."'")));
$checkout = date('Y-m-d',strtotime(selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$reservation_id."'")));




$allocated_room_array = array_column($allocation_room_ids, 'room_id');
$occupied_room_array = array_column($occupied_room_ids, 'room_id');
$allocation_All_room_array = array_column($allocation_All_room_ids, 'room_id');
$occupied_based_on_reservation_room_array = array_column($occupied_based_on_reservation_room_ids, 'room_id');


foreach ($all_rooms as $key => $room) {

	$check_checkout = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_reserved = '".$room['room_id']."' and checkin_status = '1' and checkout_status = '0' and room_availability = 'checkout' and checkout_date is not null");
	$checked = '';
	$roomNumbers = "'".$room['room_no']."'";
	$room_id = $room['room_id'];
	
	
	
	//====================
	$blocked_room_dates = selectColumn('mst_room_no_allocation','blocked_room_dates'," WHERE `id` = '".$room_id."'");
	$checkinTime  = strtotime($checkin);
	$checkoutTime = strtotime($checkout);
	$overlapFound = false;

foreach (explode(',', $blocked_room_dates) as $range) { //echo '===>'.$roomNumbers.'-'.$room_id;
    list($startT4, $endT4) = explode(' - ', $range);
    $blockStart = strtotime(str_replace('/', '-', trim($startT4)));
    $blockEnd   = strtotime(str_replace('/', '-', trim($endT4)));

    // Check for overlap
    if ($checkinTime <= $blockEnd && $checkoutTime >= $blockStart) {
        $overlapFound = true;
       // break;
    }
}

if ($overlapFound) { $overlapFound1='1';
  //  echo "Reservation dates overlap with blocked dates.";
} else {$overlapFound1='0';
  //  echo "Reservation dates are clear.";
}
	//=====================
	if (in_array($room['room_id'], $allocated_room_array) && !in_array($room['room_id'], $occupied_room_array)) {
		$start .='<label class="checkbox-label bg-green" for="myCheckbox"><input type="checkbox" checked="checked" id="myCheckboxData_'.$listRoomInBooking.'_'.$reservation_id.'" name="expected_arrivals_rooms[]" data-is_confirm="false" data-room_id="'.$room_id.'" class="roomdata_'.$listRoomInBooking.'_'.$reservation_id.'_'.$roomNumbers.'" onclick="ValidateRoomSelected('.$roomCount.','.$reservation_id.','.$roomNumbers.');" value="'.$room['room_no'].'"> '.$room['room_no'].'</label>';
	}
	
	
	if (in_array($room['room_id'], $occupied_based_on_reservation_room_array)) {
		$start .='<label class="checkbox-label bg-red" for="myCheckbox"><input type="hidden" checked="checked" id="myCheckboxData_'.$listRoomInBooking.'_'.$reservation_id.'" name="expected_arrivals_rooms[]" data-is_confirm="true" data-room_id="'.$room_id.'" class="roomdata_'.$listRoomInBooking.'_'.$reservation_id.'_'.$roomNumbers.'" onclick="ValidateRoomSelected('.$roomCount.','.$reservation_id.','.$roomNumbers.');" value="'.$room['room_no'].'"> '.$room['room_no'].'</label>';
	}
	
	
	if (!in_array($room['room_id'], $occupied_room_array) && !in_array($room['room_id'], $allocated_room_array) && !in_array($room['room_id'], $occupied_based_on_reservation_room_array) && mysqli_num_rows($check_checkout) == 0 && !in_array($room['room_id'],$allocation_All_room_array) && $overlapFound1=='0') {
		$start .='<label class="checkbox-label" for="myCheckbox"><input type="checkbox" id="myCheckboxData_'.$listRoomInBooking.'_'.$reservation_id.'" name="expected_arrivals_rooms[]" data-room_id="'.$room_id.'" data-is_confirm="false" class="roomdata_'.$listRoomInBooking.'_'.$reservation_id.'_'.$roomNumbers.'" onclick="ValidateRoomSelected('.$roomCount.','.$reservation_id.','.$roomNumbers.');" value="'.$room['room_no'].'"> '.$room['room_no'].'</label>';
	}
	$start .= '<script> ValidateRoomSelected('.$roomCount.','.$reservation_id.','.$roomNumbers.');</script>';
}

// exit;
$start .='</div></div></div>';
$returnData = array();
$returnData['rr'] = '<td colspan="9">
						<div class="row">
    						<div class="col-md-12 col-sm-12">
      							<div class="box box-primary box-outline">
        							<div class="box-body">'.$start. '<div id="showBookedRoom_${resId}"></div>
        						</div>
        					<div class="box-footer">';
if (strtotime($DayNightAudit) == strtotime($reservation_result->checkin) ) {
	$returnData['rr'] .='<button class="btn btn-primary pull-right"  style="margin-left:10px;" onClick="updateCheckinTime('.$reservation_id.','.$id_mst_room_types.','.$nightAuditDate.','.$pending_folio_id.','.$check_checkout_date.');">Check-in</button>';
}
$returnData['rr'] .='&nbsp;&nbsp;&nbsp;<button class="btn btn-primary pull-right" onClick="RoomAllocationsingleForm('.$reservation_id.','.$id_mst_room_types.');">Reserve</button>';
$returnData['rr'] .='</div></div></div></div></td>';
echo json_encode($returnData);
die;
?>