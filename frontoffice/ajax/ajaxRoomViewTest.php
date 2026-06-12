<style>

.cstmBgReserved{
background :  #0091fb3b!important
}

.cstmBgOccupied{
background :  #fde0e0
}

.cstmBgVacant{
background :  #a7ffa7!important
}

.rvn-room-header {
	padding : 0rem 0.7rem;
	border-radius : 4px;
}
.cstmBgBlocked{
	background : #c7d6a9 !important
}
.cstmBgOccupiedDepart{
	background : #ffa50070 !important
}

/* Media query for mobile devices (commonly used breakpoint) */
@media (max-width: 768px) {
.rvn-room-card{
	width : 100%!important;
}


.search-container .shortcut{
	display : none!important;
}

}

</style>

<?php
include_once("../../config/auto_loader.php");

$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));

// $selectneww11 = "SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel";
// $resneww11 = mysqli_query($connNew,$selectneww11);

// $room_types = [];
// while ($rowneww11 = mysqli_fetch_object($resneww11)) {
// 	$roomno = $rowneww11->id_mst_room_types;
// 	$selectneww = "SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$roomno."' $conn";
// 	$resneww = mysqli_query($connNew,$selectneww);
// 	while ($ty = mysqli_fetch_object($resneww)) {

// 	}
// }

	$folioArray=array();
	
	if($_REQUEST['room_status']>0){
	  $actual_room_status = $room_status	= $_REQUEST['room_status'];
	  $room_status	= $room_status == '5' ? '3' : $_REQUEST['room_status'];
	  $SqlConn = " AND `room_status` IN (".$room_status.")";
	}
$sqlAllRooms = mysqli_query($connNew,"SELECT *

FROM `mst_room_no_allocation`  
 


WHERE status = '1'  $SqlConn order by display_order 
		
		 "); // management_block = 'No' and
		if(mysqli_num_rows($sqlAllRooms) >0 ){
			
				while($rowAllRooms= mysqli_fetch_object($sqlAllRooms)){	  
		$CurrentTotal='0';
		if($rowAllRooms->room_status=='1'){
			$roomClass	='';
			$roomStatus='Dirty';
		}elseif($rowAllRooms->room_status=='2'){
			$roomClass	='cstmBgReserved';
			$roomStatus='Reserved';
		}elseif($rowAllRooms->room_status=='3'){
			$roomClass	='cstmBgOccupied';
			$roomStatus='Occupied';
		}elseif($rowAllRooms->room_status=='4'){
			$roomClass	='cstmBgVacant';
			$roomStatus='Vacant';
		}elseif($rowAllRooms->room_status=='5'){
			$roomClass	='';
			$roomStatus='Blocked';
		}elseif($rowAllRooms->room_status=='6'){
			$roomClass	='';
			$roomStatus='Under Maintenance';
		}
					
					
			 $blocked_room_dates =$rowAllRooms->blocked_room_dates;	
					
					$inRange = false;

foreach (explode(',', $blocked_room_dates) as $range) {
    list($start, $end) = explode(' - ', $range);

    
    $startDate = date('Y-m-d', strtotime(str_replace('/', '-', trim($start))));
    $endDate   = date('Y-m-d', strtotime(str_replace('/', '-', trim($end))));

    if ($today >= $startDate && $today <= $endDate) {
        $inRange = true;
        break;
    }
}

if ($inRange) {
    //echo "$today is inside a blocked date range.";
	$roomClass	='cstmBgBlocked';
			$roomStatus='Blocked';
} else {
    //echo "$today is NOT in any blocked date range.";
}
					
					
if($rowAllRooms->management_block=='Yes'){
			$roomClass	='cstmBgBlocked';
			$roomStatus='Blocked';
		}
					
					
					
					
		$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,room.house_keeping_status,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room, resdetails.id_fo_rate_plan as id_fo_rate_plan,resdetails.checkin_time,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
		WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."' and resdetails.`id_fo_folio_to`!='0' and room.room_status!='2'");
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
			
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ //print_r($rowOrderDetail);
				
				$booking_no	= selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkin	= selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkout	= selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkinTime =$rowRoomNumbers->checkin_time;
					$bill_checkout_status	= selectColumn(FO_BILL,'status'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					
				$id_owner_room =selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
		//================================================
		
		$id_mst_guest_id_owner_room	=  selectColumn('fo_reservations_details','id_mst_guest'," WHERE `id_fo_reservations` = '".$rowRoomNumbers->id_fo_reservations."' and id_mst_room_no_allocation = '".$id_owner_room."'");
		
			$id_mst_attributes_title	=	selectColumn("mst_guest",'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
					$GuestTitle = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
					
					$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
					$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
		
		
		//========================================
					
					
					
					
					$GuestNameDetailRoom	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					$lastNameDetailRoom	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					
					$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
	$guests = [];
					
		$guests[$rowRoomNumbers->id_mst_guest] = $GuestNameDetailRoom.$lastNameDetailRoom!=''?$Title.' '.ucfirst(strtolower($GuestNameDetailRoom.' '.$lastNameDetailRoom)):'';	
					
	if ($rowRoomNumbers->id_shared_guest != '') {
		$id_shared_guests = explode(',', $rowRoomNumbers->id_shared_guest);
		foreach ($id_shared_guests as $id_guest) {
			$SharedGuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$id_guest."'");
			$sharedGuestLastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$id_guest."'");
			
			$shared_guest_id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_guest."'");				
			$sharedGuestTitle = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$shared_guest_id_mst_attributes_title."'");
			$guests[$id_guest] = $SharedGuestName!=''?$sharedGuestTitle.' '.ucfirst(strtolower($SharedGuestName)).' '.ucfirst(strtolower($sharedGuestLastName)):'';
		}
	}	
	
	$id_folio =$rowRoomNumbers->id_fo_folio_to;
	
	
	

	
	
	$CurrentTotal = 0;

// === ROOM CHARGES (Tariff + Tax) ===
$query = "SELECT tariff_price_per_day_per_room, tax_per_day_per_room 
          FROM `".FO_RESERVATIONS_DETAILS."` 
          WHERE id_fo_folio_to = '".addslashes($id_folio)."'";
$result = mysqli_query($connNew, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $CurrentTotal += $row['tariff_price_per_day_per_room'] + $row['tax_per_day_per_room'];
}

// === POS PURCHASES ===
$query = "SELECT grant_total_amount 
          FROM `pos_purch` 
          WHERE id_fo_folio_to = '".addslashes($id_folio)."' AND cancelled != 1";
$result = mysqli_query($connNew, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $CurrentTotal += $row['grant_total_amount'];
}

// === ADDONS ===
$query = "SELECT total 
          FROM `fo_reservations_addons_details` 
          WHERE id_fo_folio_to = '".addslashes($id_folio)."'";
$result = mysqli_query($connNew, $query);

while ($row = mysqli_fetch_assoc($result)) {
    $CurrentTotal += $row['total'];
}

// === RECEIPTS ===
$receipt_amount = round(selectColumn('fo_receipt', 'SUM(amount)', 'WHERE id_fo_folio="'.addslashes($id_folio).'"'), 2);

// === BALANCE ===
$BalanceAmount = round($CurrentTotal);
//==================================================	
$ExtraAdultSqL=	"SELECT 
    ch.name AS charge_name,
    (fo.qty) AS extraAdult
FROM 
    `fo_reservations_addons_details` fo
JOIN 
    `mst_charges` ch ON fo.id_mst_charges = ch.id
WHERE 
    ch.name LIKE '%Extra person%' and  fo.id_fo_folio_to = '".addslashes($id_folio)."' and fo.id_mst_room_no_allocation='".$rowRoomNumbers->id."'
GROUP BY 
    ch.name";				
		$resultAdult = mysqli_query($connNew, $ExtraAdultSqL);
$ExtraRoom=0;
while ($rowAdult = mysqli_fetch_assoc($resultAdult)) {
    $ExtraRoom = $rowAdult['extraAdult'];
}//echo '==========>'.$ExtraRoom;
					
					
					
					$roomNo	  = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowRoomNumbers->id."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");
					
					$plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$rowRoomNumbers->id_fo_rate_plan."'");
					$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_folio_to."'");
					
					$RoomNoAndRoomName=$RoomName.' / '.$plan_name;
					
					$folioArray[$rowRoomNumbers->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$rowRoomNumbers->id]['room_no']=$roomNo;
					$folioArray[$rowRoomNumbers->id]['RoomName']=$RoomName;
					$folioArray[$rowRoomNumbers->id]['status']=$roomStatus;
					$folioArray[$rowAllRooms->id]['roomClass']=$roomClass;
					$folioArray[$rowRoomNumbers->id]['plan_name']=$plan_name;
					
					$folioArray[$rowRoomNumbers->id]['total_child']=$rowRoomNumbers->child_below_5_year+$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->id]['child_below_5_year']=$rowRoomNumbers->child_below_5_year;
					$folioArray[$rowRoomNumbers->id]['child_above_5_year']=$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->id]['adults_per_room']=$rowRoomNumbers->adults_per_room;//+$ExtraRoom;
					$folioArray[$rowRoomNumbers->id]['house_keeping_status']=$rowRoomNumbers->house_keeping_status;
					//echo '<br><br><br>'.$roomNo.'--'.$CurrentTotal.'---'.$id_folio.'--'.$folio_mdoc_no;
				if($bill_checkout_status!='2'){	
						
					
					$folioArray[$rowRoomNumbers->id]['id_mst_room_no_allocation']=$rowRoomNumbers->id;
					$folioArray[$rowRoomNumbers->id]['order_by_room']=$rowRoomNumbers->order_by_room;
					$folioArray[$rowRoomNumbers->id]['GuestName']=$GuestName!=''?$Title.' '.ucfirst(strtolower($GuestName)).' '.ucfirst(strtolower($lastName)):'';
					$folioArray[$rowRoomNumbers->id]['Guest'] = $guests;
					$folioArray[$rowRoomNumbers->id]['id_mst_guest']=$rowRoomNumbers->id_mst_guest;
					$folioArray[$rowRoomNumbers->id]['folio_mdoc_no']=$folio_mdoc_no!=''?$folio_mdoc_no:'';
					$folioArray[$rowRoomNumbers->id]['mdoc_no']=$booking_no!=''?$booking_no:'';
					$folioArray[$rowRoomNumbers->id]['id_fo_reservations']=$rowRoomNumbers->id_fo_reservations!=''?$rowRoomNumbers->id_fo_reservations:'';
					$folioArray[$rowRoomNumbers->id]['id_fo_view_folio']=$rowRoomNumbers->id_fo_folio_to;//$rowRoomNumbers->id_fo_reservations.'_'.$rowRoomNumbers->id_fo_bill.'_'.$rowRoomNumbers->id;
					
					
					$folioArray[$rowRoomNumbers->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$rowRoomNumbers->id]['id_fo_bill']=$rowRoomNumbers->id_fo_bill;
					
					$folioArray[$rowRoomNumbers->id]['Checkin']=$checkin!=''?date('d M Y',strtotime($checkin)):'';
					$folioArray[$rowRoomNumbers->id]['Checkout']=$checkout!=''?date('d M Y',strtotime($checkout)):'';
					$folioArray[$rowRoomNumbers->id]['checkout_text']= $checkout != '' ? date('Y-m-d',strtotime($checkout)) : '';
					$folioArray[$rowRoomNumbers->id]['BalanceAmount']=$BalanceAmount;
					$folioArray[$rowRoomNumbers->id]['CheckinTime']=$checkinTime!=''?$checkinTime:'';
					
					
				
				}
				
					
				}
				
				
		}else{
			//Room Allocation Start =============================================================================
			 if($rowAllRooms->room_status=='2'){
				 
				/* $sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,room.house_keeping_status,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room, resdetails.id_fo_rate_plan as id_fo_rate_plan,resdetails.checkin_time,
		resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		 
		WHERE   resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."'");*/
	 $sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,room.house_keeping_status,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room, resdetails.id_fo_rate_plan as id_fo_rate_plan,resdetails.checkin_time,
		resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		 
		WHERE  resdetails.dated='".$today."' and  resdetails.`checkout_status`='0' and resdetails.`checkin_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."'");			 
				 
				 
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
			
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ //print_r($rowOrderDetail);
				
				$booking_no	= selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkin	= selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkout	= selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkinTime =$rowRoomNumbers->checkin_time;
					$bill_checkout_status	= selectColumn(FO_BILL,'status'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					
				$id_owner_room =selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
		//================================================
		
		$id_mst_guest_id_owner_room	=  selectColumn('fo_reservations_details','id_mst_guest'," WHERE `id_fo_reservations` = '".$rowRoomNumbers->id_fo_reservations."' and id_mst_room_no_allocation = '".$id_owner_room."'");
		
			$id_mst_attributes_title	=	selectColumn("mst_guest",'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
					$GuestTitle = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
					
					$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
					$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$id_mst_guest_id_owner_room."'");
		
		
		//========================================
					
					
					
					
					$GuestNameDetailRoom	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					$lastNameDetailRoom	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					
					$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");
	$guests = [];
					
		$guests[$rowRoomNumbers->id_mst_guest] = $GuestNameDetailRoom.$lastNameDetailRoom!=''?$Title.' '.ucfirst(strtolower($GuestNameDetailRoom.' '.$lastNameDetailRoom)):'';	
					
	if ($rowRoomNumbers->id_shared_guest != '') {
		$id_shared_guests = explode(',', $rowRoomNumbers->id_shared_guest);
		foreach ($id_shared_guests as $id_guest) {
			$SharedGuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$id_guest."'");
			$sharedGuestLastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$id_guest."'");
			
			$shared_guest_id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_guest."'");				
			$sharedGuestTitle = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$shared_guest_id_mst_attributes_title."'");
			$guests[$id_guest] = $SharedGuestName!=''?$sharedGuestTitle.' '.ucfirst(strtolower($SharedGuestName)).' '.ucfirst(strtolower($sharedGuestLastName)):'';
		}
	}	
	
	$id_folio =$rowRoomNumbers->id_fo_folio_to;
	
	
	
$CurrentTotal='0';
	
	
	//==Balance================================================
	
				$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_folio_to` = '".$id_folio."' ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					$CurrentTotal	+=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
					
				}
				
			 ;	
		}
		$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($id_folio)."' and cancelled!=1 ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					;
					$CurrentTotal	+=$rowOrderDetail->grant_total_amount;
				}
				
				
		}
$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($id_folio)."' ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					$CurrentTotal	+=$rowOrderDetail->total;
				}
				
				
		}
		

$receipt_amount	=	round(selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$id_folio.'"'),2);



$BalanceAmount = round($CurrentTotal-$receipt_amount,2);
//==================================================	
					
					
					$roomNo	  = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowRoomNumbers->id."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");
					
					$plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$rowRoomNumbers->id_fo_rate_plan."'");
					$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_folio_to."'");
					
					$RoomNoAndRoomName=$RoomName.' / '.$plan_name;
					
					$folioArray[$rowRoomNumbers->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$rowRoomNumbers->id]['room_no']=$roomNo;
					$folioArray[$rowRoomNumbers->id]['RoomName']=$RoomName;
					$folioArray[$rowRoomNumbers->id]['status']=$roomStatus;
					$folioArray[$rowAllRooms->id]['roomClass']=$roomClass;
					$folioArray[$rowRoomNumbers->id]['plan_name']=$plan_name;
					
					$folioArray[$rowRoomNumbers->id]['total_child']=$rowRoomNumbers->child_below_5_year+$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->id]['child_below_5_year']=$rowRoomNumbers->child_below_5_year;
					$folioArray[$rowRoomNumbers->id]['child_above_5_year']=$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->id]['adults_per_room']=$rowRoomNumbers->adults_per_room;
					$folioArray[$rowRoomNumbers->id]['house_keeping_status']=$rowRoomNumbers->house_keeping_status;
					
				if($bill_checkout_status!='2'){	
						
					
					$folioArray[$rowRoomNumbers->id]['id_mst_room_no_allocation']=$rowRoomNumbers->id;
					$folioArray[$rowRoomNumbers->id]['order_by_room']=$rowRoomNumbers->order_by_room;
					$folioArray[$rowRoomNumbers->id]['GuestName']=$GuestName!=''?$Title.' '.ucfirst(strtolower($GuestName)).' '.ucfirst(strtolower($lastName)):'';
					$folioArray[$rowRoomNumbers->id]['Guest'] = $guests;
					$folioArray[$rowRoomNumbers->id]['id_mst_guest']=$rowRoomNumbers->id_mst_guest;
					$folioArray[$rowRoomNumbers->id]['folio_mdoc_no']=$folio_mdoc_no!=''?$folio_mdoc_no:'';
					$folioArray[$rowRoomNumbers->id]['mdoc_no']=$booking_no!=''?$booking_no:'';
					$folioArray[$rowRoomNumbers->id]['id_fo_reservations']=$rowRoomNumbers->id_fo_reservations!=''?$rowRoomNumbers->id_fo_reservations:'';
					$folioArray[$rowRoomNumbers->id]['id_fo_view_folio']=$rowRoomNumbers->id_fo_folio_to;//$rowRoomNumbers->id_fo_reservations.'_'.$rowRoomNumbers->id_fo_bill.'_'.$rowRoomNumbers->id;
					
					
					$folioArray[$rowRoomNumbers->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$rowRoomNumbers->id]['id_fo_bill']=$rowRoomNumbers->id_fo_bill;
					
					$folioArray[$rowRoomNumbers->id]['Checkin']=$checkin!=''?date('d M Y',strtotime($checkin)):'';
					$folioArray[$rowRoomNumbers->id]['Checkout']=$checkout!=''?date('d M Y',strtotime($checkout)):'';
					$folioArray[$rowRoomNumbers->id]['checkout_text']= $checkout != '' ? date('Y-m-d',strtotime($checkout)) : '';
					$folioArray[$rowRoomNumbers->id]['BalanceAmount']=0;//$BalanceAmount;
					$folioArray[$rowRoomNumbers->id]['CheckinTime']=$checkinTime!=''?$checkinTime:'';
					
					
					
					
				
				}
				
					
				}
				
				
		}else{   //Room Allocation End =============================================================================
			 	$updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='4'	where `id` IN (".$rowAllRooms->id.") ";
				mysqli_query($connNew,$updateRoomstatus);
				
				
			}
			}else{
					$roomNo	  = $rowAllRooms->room_no;
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowAllRooms->id_mst_room_types."'");
									
					
					$RoomNoAndRoomName=$RoomName;//.'/'.$roomNo;
					$folioArray[$rowAllRooms->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$rowAllRooms->id]['room_no']=$roomNo;
					$folioArray[$rowAllRooms->id]['RoomName']=$RoomName;
					$folioArray[$rowAllRooms->id]['status']=$roomStatus;
					$folioArray[$rowAllRooms->id]['roomClass']=$roomClass;
				 $folioArray[$rowAllRooms->id]['house_keeping_status']=$rowAllRooms->house_keeping_status;
			 }
			}
				}
		}

		
		
		//debugData($folioArray);
    // Define arrays for room types, guest names, and room statuses
   // $roomTypes = ["Deluxe Room", "Standard Room", "Superior Room", "Suite"];
    //$guestNames = ["John Doe", "Jane Smith", "Michael Johnson", "Emily Davis", "William Brown", "Sophia Wilson", "James Miller", "Isabella Taylor"];
    //$roomStatuses = ["Occupied", "Reserved", "Blocked", "Maintenance"]; // Array for room statuses

	$i = 0;
    foreach($folioArray as $roomcount=>$roomData) { //echo 'Shafeer '; 
        // Replace these dynamic variables with real data from a database or an array.
        $roomNumber = $roomData['room_no'];
        $roomType = $roomData['RoomType']; // Pick a random room type
        $reservationNumber =  $roomData['mdoc_no'];
        $guestName = $roomData['GuestName']; // Pick a random guest name
        $guests = $roomData['Guest'];
        $adults = $roomData['adults_per_room'];
        $childBelow = $roomData['child_below_5_year'];
		$childAbove = $roomData['child_above_5_year'];
        $checkInDate = $roomData['Checkin'];
        $checkOutDate = $roomData['Checkout'];
        $folioNumber = $roomData['folio_mdoc_no'];
        $balance = "&#8377;".$roomData['BalanceAmount'];
        $roomServiceStatus = $roomData['status']; // Static example, if you want to keep it

        // Randomly assign a room status
        $roomStatus = $roomData['status'];
		$roomClass = $roomData['roomClass'];
		if ($roomStatus == 'Occupied' && $today == $roomData['checkout_text']) {
			$roomClass = 'cstmBgOccupiedDepart';
		}
		$id_mst_guest = $roomData['id_mst_guest'];
		$id_resevation = $roomData['id_fo_reservations'];
		
		$id_mst_guest_order_by_room	=  selectColumn('fo_reservations_details','order_by_room'," WHERE `id_fo_reservations` = '".$id_resevation."' and id_mst_room_no_allocation = '".$roomData['id_mst_room_no_allocation']."'");
		$id_mst_guest_order_by_room =$roomData['id_fo_reservations'];
		$id_owner_room =selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$roomData['id_fo_bill']."'");
		//================================================
		
		
		
		//========================================
		
		$id_folio =$roomData['id_fo_view_folio'];
		
		//echo $id_mst_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio;//
        $text = '
        <div class="rvn-room-card " 
            data-room-number="' . strtolower($roomNumber) . '" 
            data-room-type="' . strtolower($roomType) . '" 
            data-res-no="' . strtolower($reservationNumber) . '" 
            data-guest-name="' . strtolower($guestName) . '" 
            data-folio-no="' . strtolower($folioNumber) . '">
            
            <div class="rvn-room-card-sub">
                <div class="rvn-room-header '.$roomClass.'" >
                    <div>
                        <span class="rvn-room-number">' . $roomNumber . '</span><br>
                        <span class="rvn-room-type" style="font-family: inter;">' . $roomType . '</span>
                    </div>
                    <div>
                        <div style="margin-bottom : 5px!important;"><span style="font-family: inter;">Res No: #' . $reservationNumber . '</span></div>
                        <span class="rvn-reservation-status rvn-status-' . strtolower($roomStatus) . '" style="margin-top : 5px!important;">' . $roomStatus . '</span>
                    </div>
                </div>
                <div class="rvn-room-details">
                    <div style="width: 48%;">
                        <p>Folio Owner: <strong><a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
                onclick="GetEditGuestDetail('.$id_mst_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio.');">' . $guestName . '
                </a></strong></p>';

						foreach ($guests as $guest) {
							//$text .= '<p>Sharer Guest : <strong>' . $guest . '</strong></p>';
						}


						$text .= '</div>
                    <div style="width: 51%;">
                        <p>Adults:<b> ' . $adults . ' </b></p>
						<p>Child:<b> ' . $childAbove . ' | ' . $childBelow . '</b></p>
                    </div>
                    <div style="width: 48%;">
                        <p style="font-family: inter;"><b>Check-in:</b> ' . $checkInDate . '</p>
                    </div>
                    <div style="width: 51%;">
                        <p style="font-family: inter;"><b>Exp. Check-out:</b> ' . $checkOutDate . '</p>
                    </div>
					<div style="width: 48%;">
                        <p style="font-family: inter;"><b>Check-in Time:</b> ' . $roomData['CheckinTime'] . '</p>
                    </div>
					
					
                    <div style="width: 48%;">
                        <p ><b>Folio No:</b> <a  href="'.$SITE_URL.'/frontoffice/onewindow.php?p=6&folio='.$id_folio.'">' . $folioNumber . '</a></p>
                    </div>
                    <div style="width: 51%;">
                        <p><b>Folio Total:</b> <span class="rvn-room-balance">' . $balance . '</span></p>
                    </div>
					<div style="width: 100%;">';
                     
							$k=1;
						foreach ($guests as $id_guest=>$guest) {
							if($k==1){
							$gtatile	=	'Room Guest: <br/>';
							}else{
							$gtatile	=	'';
							}
							$k++;
							$text .= '<p> '.$gtatile.' <strong><a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
							onclick="GetEditGuestDetail('.$id_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio.');">'  . $guest . '</a></strong></p>';
						}

$vare="''";		
	
		if($roomData['house_keeping_status'] == '4'){
			
			$HKStatus ='Clean';
		
		
	}elseif($roomData['house_keeping_status'] == '2'){
			
			$HKStatus ='Maintenance';
		
		
	}elseif($roomData['house_keeping_status'] == '3'){
			
			$HKStatus ='Block';
		
		
	}else{
	
	$HKStatus ='Dirty';
		}
		$text .= '</div>
					
					
					
                </div>
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap; align-items: center; border-top: 1px solid #f1f1f1; padding: 8px 0px !important;">
                    <div class="roomServiceStatus" style="width: 35%;">
                        <div style="float: left; margin: 5px 14px;">
                            <p style="font-size: 12px; margin-bottom: 4px!important; font-family: inter;">HK Status</p>
                        </div><a type="button" data-toggle="modal"
                                            data-target="#EditRoomStatusModal'.$roomcount.'" class="btn"
                                            style="border : 1px solid #f4f4f4; display : flex; justify-content : center; align-items : bottom; height: 25px; width: 25px;" title="Change HK Status">
                                            
                                       
                        <span class="rvnRoomserviceStatus" id="rvnRoomserviceStatus_'.$roomcount.'">'.$HKStatus.'</span> &nbsp;<i class="fas fa-edit" style="margin:3px 0px 0px 0px"></i></a>
                    </div>
                   <div class="rvn-room-actions" style="width: 65%; padding-top: 0!important;">
                      <!--  <button id="amendStayBtn"  onclick="amendStayBtnOpen();" class="cstmActionBtn" title="Amend Stay" style="background: #eff6ff; border-radius: 50%; height: 4rem; width: 4rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#3b82f6" class="bi bi-pencil-square" viewBox="0 0 16 16" stroke-width="0.7">
                                <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z" />
                            </svg>
                        </button>
                        <button id="changeRoomBtn" class="cstmActionBtn" title="Change Room" style="background: #fefce8; border-radius: 50%; height: 4rem; width: 4rem;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#eab308" class="bi bi-arrow-left-right" viewBox="0 0 16 16" stroke="#eab308" stroke-width="0.4">
                                <path fill-rule="evenodd" d="M1 11.5a.5.5 0 0 0 .5.5h11.793l-3.147 3.146a.5.5 0 0 0 .708.708l4-4a.5.5 0 0 0 0-.708l-4-4a.5.5 0 1 1 .708.708L13.293 11H1.5a.5.5 0 0 0-.5.5m14-7a.5.5 0 0 1-.5.5H2.707l3.147 3.146a.5.5 0 1 1-.708.708l-4-4a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 4H14.5a.5.5 0 0 1 .5.5" />
                            </svg>
                        </button>
                        <button id="checkOutBtn" class="cstmActionBtn" title="Check out" style="background: #fef2f2; border-radius: 50%; height: 4rem; width: 4rem; ">
                            

							<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="#ef4444" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
  <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
</svg>
                        </button>
                        <button id="noteBtn" class="cstmActionBtn" title="Notes" style="background: #f0f9ff; border-radius: 50%; height: 4rem; width: 4rem; position : relative; ">
                      


<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#38bdf8" class="bi bi-sticky-fill" viewBox="0 0 16 16">
<path d="M2.5 1A1.5 1.5 0 0 0 1 2.5v11A1.5 1.5 0 0 0 2.5 15h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 8.586V2.5A1.5 1.5 0 0 0 13.5 1zm6 8.5a1 1 0 0 1 1-1h4.396a.25.25 0 0 1 .177.427l-5.146 5.146a.25.25 0 0 1-.427-.177z"/>
</svg>

                            <span class="note-count" style="position: absolute; top: -5px;
							right: 0px; background: red; border-radius: 50%; color: white; padding: 2px 5px; font-size: 0.7rem;">0</span>
                        </button>-->
						
						<div style="margin-left: 156px;">
						<a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
		onclick="GetAddNewSharedGuestDetail('.$vare.','. addslashes($id_resevation).','.$roomData['id_mst_room_no_allocation'].','.$roomData['order_by_room'].','.$id_owner_room.',2,'.$id_folio.');">
						<button id="guestBtn" class="cstmActionBtn" title="Guest" style="background: #f0f9ff; border-radius: 50%; height: 4rem; width: 4rem; display: flex; align-items: center; justify-content: center; position: relative;">
    <!-- Plus Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
    </svg>

    <!-- Guest Icon -->
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="20" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16" style="position: absolute; bottom: 5px; right: 5px;">
        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
    </svg>
</button>
						</a></div>
                    </div>
                </div>
            </div>
        </div>'; ?>

<div class="modal " id="EditRoomStatusModal<?=$roomcount; ?>" tabindex="-1"
                                            role="dialog" aria-labelledby="EditRoomStatusModalLabel" style="">
                                            <div style="width : 40%!important; margin : auto;!important;">
                                                <div class="modal-dialog" role="document" style="width : 60%!important;">
                                                    <div class="modal-content">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">House Keeping Status <br><b>Room No <?php echo $roomNumber ?></b></h5>
                                                                <button type="button" class="close" data-dismiss="modal"
                                                                    aria-label="Close"
                                                                    style="position: absolute!important; top: 15px!important; right: 10px!important;">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>

                                                            <form id="roomstatusform<?= $rmno->id ?>" method="post">
                                                            <!-- Modal Content -->
                                                            <div class="modal-body">

                                                                <input type="hidden" class="rm_id" name="rm_id" id="rm_id<?= $roomData['id_mst_room_no_allocation'] ?>" value="<?php echo $roomcount; ?>">

                                                                <!-- Select and Buttons -->
                                                                <label for="exampleSelect">Status</label>
                                                                <select class="form-control" class="cur_room_status" id="cur_room_status<?= $rmno->id ?>" name="cur_room_status">
                                                                    
                                                                   
                                                                    <option value="4"
                                                                        <?php echo ($roomData['house_keeping_status'] == 4) ? 'selected' : ''; ?>>Clean
                                                                    </option>
                                                                    <option value="1"
                                                                        <?php echo ($roomData['house_keeping_status'] == 1) ? 'selected' : ''; ?>>Dirty
                                                                    </option>
 <?php /* ?> <option value="2"
                                                                        <?php echo ($roomData['house_keeping_status'] == 2) ? 'selected' : ''; ?>>Maintenance 
                                                                    </option>
																	
				 <option value="3"
                                                                        <?php echo ($roomData['house_keeping_status'] == 3) ? 'selected' : ''; ?>>Block 
                                                                    </option>													
																	<?php */ ?>
																	
                                                                </select>

                                                            </div>

                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer">
                                                                <button type="button"
                                                                    class="btn btn-primary" onclick="saveHouseKeepingStatusForm(this);">Update</button>
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-dismiss="modal">Close</button>
                                                            </div>
                                                        </form>
                                                        </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- modal ends  -->
<?php 
		if ($actual_room_status != '5') {
			$i++;
			echo $text;
		} else {
			if ($today == $roomData['checkout_text']) {
				$i++;$yy='BC';
				echo $text;
			}
		}
    }
    ?>

	<script>
		$('#room_count').text('<?php echo "Rooms :- ".$i.$yy; ?>');
	</script>


<script>
	// Open the drawer for the clicked button
	function openDrawer(drawerId) {
		document.getElementById(drawerId).classList.remove('hidden');
		document.getElementById('overlay').classList.remove('hidden'); // Show overlay
	}

	// Close the specified drawer
	function closeDrawer(drawerId) {
		document.getElementById(drawerId).classList.add('hidden');
		document.getElementById('overlay').classList.add('hidden'); // Hide overlay
	}

	// Event listeners for buttons to open drawers
	//document.getElementById('amendStayBtn').onclick = function () {
		//openDrawer('amendStayDrawer');
	//};

	document.getElementById('changeRoomBtn').onclick = function () {
		openDrawer('changeRoomDrawer');
	};

	document.getElementById('checkOutBtn').onclick = function () {
		openDrawer('checkOutDrawer');
	};

	document.getElementById('noteBtn').onclick = function () {
		openDrawer('noteDrawer');
	};
	function amendStayBtnOpen(){ 
		openDrawer('amendStayDrawer');
		
	}

		
</script>