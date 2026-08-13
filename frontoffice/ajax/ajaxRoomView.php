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
	padding : 0.3rem 0.7rem;
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
include_once("../functions/function.php");
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));


$yes = date('Y-m-d',strtotime($rowNightAudit->dated));


					$post_tariff_date	=	date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));//$Dated;
					$id_post_tariff='1';
					$id_fo_bill	= '';	
					$shop=$_SESSION['shop'];
					postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew);


//$today =date('Y-m-d',strtotime($_REQUEST['DateFilter']));
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
					
					
					
		  "<br>------SELECT DISTINCT 		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,room.house_keeping_status,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room, resdetails.id_fo_rate_plan as id_fo_rate_plan,resdetails.checkin_time,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
		WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."' and resdetails.`id_fo_folio_to`!='0' and room.room_status!='2' AND DATE(resdetails.dated) = '$today' ";
					
					
		$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,room.house_keeping_status,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room, resdetails.id_fo_rate_plan as id_fo_rate_plan,resdetails.checkin_time,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
		WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."' and resdetails.`id_fo_folio_to`!='0' and room.room_status!='2' AND (DATE(resdetails.dated) = '$today' || DATE(resdetails.dated) = '$yes')");
					
					//AND (DATE(resdetails.dated) = '$today' || DATE(resdetails.dated) = '$yes')
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

	if( $_REQUEST['room_status']=='0' ||  $_REQUEST['room_status']=='2'){ //$today = '2026-03-25';
	  $Sqln = "SELECT DISTINCT 
    resdetails.id_mst_room_no_reserved AS room_id,
    resdetails.id_fo_reservations,
    resdetails.id_mst_guest,
    resdetails.id_shared_guest,
    resdetails.id_fo_folio_to,
    resdetails.id_fo_bill,
    resdetails.order_by_room,
    resdetails.id_fo_rate_plan,
    resdetails.checkin_time,
    resdetails.child_below_5_year,
    resdetails.child_above_5_year,
    resdetails.adults_per_room,resdetails.id_mst_room_types
    
FROM fo_reservations_details AS resdetails
INNER JOIN fo_reservations AS res
    ON res.id = resdetails.id_fo_reservations

WHERE DATE(res.checkin) = '$today' AND DATE(resdetails.dated) = '$today'  AND
   resdetails.checkout_status = '0'
  AND resdetails.checkin_status = '0'
  AND resdetails.no_showoff = '0'
  AND resdetails.id_mst_room_no_reserved > '0'
 ";			 
				 
		$sqlRoomNumber = mysqli_query($connNew,$Sqln);	 
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
$roomClass	='cstmBgReserved';
			$roomStatus='Reserved';
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ echo '<br>';//print_r($rowRoomNumbers);
				
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
					
					
					$roomNo	  = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowRoomNumbers->room_id."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");
					
					$plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$rowRoomNumbers->id_fo_rate_plan."'");
					$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_folio_to."'");
					
					$RoomNoAndRoomName=$RoomName.' / '.$plan_name;
					
					$folioArray[$rowRoomNumbers->room_id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$rowRoomNumbers->room_id]['room_no']=$roomNo;
					$folioArray[$rowRoomNumbers->room_id]['RoomName']=$RoomName;
					$folioArray[$rowRoomNumbers->room_id]['status']=$roomStatus;
					$folioArray[$rowRoomNumbers->room_id]['roomClass']=$roomClass;
					$folioArray[$rowRoomNumbers->room_id]['plan_name']=$plan_name;
					
					$folioArray[$rowRoomNumbers->room_id]['total_child']=$rowRoomNumbers->child_below_5_year+$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->room_id]['child_below_5_year']=$rowRoomNumbers->child_below_5_year;
					$folioArray[$rowRoomNumbers->room_id]['child_above_5_year']=$rowRoomNumbers->child_above_5_year;
					$folioArray[$rowRoomNumbers->room_id]['adults_per_room']=$rowRoomNumbers->adults_per_room;
					$folioArray[$rowRoomNumbers->room_id]['house_keeping_status']=$rowRoomNumbers->house_keeping_status;
					
				if($bill_checkout_status!='2'){	
						
					
					$folioArray[$rowRoomNumbers->room_id]['id_mst_room_no_allocation']=$rowRoomNumbers->id;
					$folioArray[$rowRoomNumbers->room_id]['order_by_room']=$rowRoomNumbers->order_by_room;
					$folioArray[$rowRoomNumbers->room_id]['GuestName']=$GuestName!=''?$Title.' '.ucfirst(strtolower($GuestName)).' '.ucfirst(strtolower($lastName)):'';
					$folioArray[$rowRoomNumbers->room_id]['Guest'] = $guests;
					$folioArray[$rowRoomNumbers->room_id]['id_mst_guest']=$rowRoomNumbers->id_mst_guest;
					$folioArray[$rowRoomNumbers->room_id]['folio_mdoc_no']=$folio_mdoc_no!=''?$folio_mdoc_no:'';
					$folioArray[$rowRoomNumbers->room_id]['mdoc_no']=$booking_no!=''?$booking_no:'';
					$folioArray[$rowRoomNumbers->room_id]['id_fo_reservations']=$rowRoomNumbers->id_fo_reservations!=''?$rowRoomNumbers->id_fo_reservations:'';
					$folioArray[$rowRoomNumbers->room_id]['id_fo_view_folio']=$rowRoomNumbers->id_fo_folio_to;//$rowRoomNumbers->id_fo_reservations.'_'.$rowRoomNumbers->id_fo_bill.'_'.$rowRoomNumbers->id;
					
					
					$folioArray[$rowRoomNumbers->room_id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$rowRoomNumbers->room_id]['id_fo_bill']=$rowRoomNumbers->id_fo_bill;
					
					$folioArray[$rowRoomNumbers->room_id]['Checkin']=$checkin!=''?date('d M Y',strtotime($checkin)):'';
					$folioArray[$rowRoomNumbers->room_id]['Checkout']=$checkout!=''?date('d M Y',strtotime($checkout)):'';
					$folioArray[$rowRoomNumbers->room_id]['checkout_text']= $checkout != '' ? date('Y-m-d',strtotime($checkout)) : '';
					$folioArray[$rowRoomNumbers->room_id]['BalanceAmount']=0;//$BalanceAmount;
					$folioArray[$rowRoomNumbers->room_id]['CheckinTime']=$checkinTime!=''?$checkinTime:'';
					
					
					
					
				
				}
				
					
				}
				
				
		}else{   //Room Allocation End =============================================================================
			 	$updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='4'	where `id` IN (".$rowAllRooms->id.") ";
				mysqli_query($connNew,$updateRoomstatus);
				
				
			}
}
		//debugData($folioArray);
    // Define arrays for room types, guest names, and room statuses
   // $roomTypes = ["Deluxe Room", "Standard Room", "Superior Room", "Suite"];
    //$guestNames = ["John Doe", "Jane Smith", "Michael Johnson", "Emily Davis", "William Brown", "Sophia Wilson", "James Miller", "Isabella Taylor"];
    //$roomStatuses = ["Occupied", "Reserved", "Blocked", "Maintenance"]; // Array for room statuses
	if (isset($_REQUEST['excel']) && $_REQUEST['excel'] == '1') {
		    $filename = "room_export_" . date('Ymd_His') . ".xls";

    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Pragma: no-cache");
    header("Expires: 0");

    // Group rooms by status
    $groupedRooms = [];
    foreach ($folioArray as $roomData) {
        $status = $roomData['status'] ?? 'Unknown';
        $groupedRooms[$status][] = $roomData;
    }

    echo '<table border="1" cellpadding="5" cellspacing="0" width="100%">';

    foreach ($groupedRooms as $status => $rooms) {

        $isVacant   = (strtolower($status) === 'vacant');
        $isOccupied = (strtolower($status) === 'occupied');

        // 🔹 Status Heading
        echo '
        <tr style="background:#d9d9d9;font-weight:bold;">
            <td colspan="' . ($isVacant ? 2 : 5) . '">
                STATUS: ' . strtoupper($status) . ' (' . count($rooms) . ')
            </td>
        </tr>';

        // 🔹 Column headers
        if ($isVacant) {
            echo '
            <tr style="font-weight:bold;">
                <th>Room No</th>
                <th>Room Type</th>
            </tr>';
        } else {
            echo '
            <tr style="font-weight:bold;">
                <th>Room No</th>
                <th>Room Type</th>
                <th>Pax</th>
                <th>Check-in</th>
                <th>Check-out</th>
            </tr>';
        }

        // 🔹 Plan counter (only for occupied)
        $planCount = [];

        // 🔹 Data rows
        foreach ($rooms as $roomData) {

            if ($isVacant) {
                echo '<tr>
                    <td>' . $roomData['room_no'] . '</td>
                    <td>' . $roomData['RoomType'] . '</td>
                </tr>';
            } else {
                echo '<tr>
                    <td>' . $roomData['room_no'] . '</td>
                    <td>' . $roomData['RoomType'] . '</td>
                    <td>' . ($roomData['adults_per_room'] ?? 0) . '</td>
                    <td>' . ($roomData['Checkin'] ?? '-') . '</td>
                    <td>' . ($roomData['Checkout'] ?? '-') . '</td>
                </tr>';

                // Count plan only for occupied rooms
                if ($isOccupied) {
                    $plan = strtoupper($roomData['plan_name'] ?? 'UNKNOWN');
                    $planCount[$plan] = ($planCount[$plan] ?? 0) + 1;
                }
            }
        }

        // 🔹 Plan-wise summary (ONLY after occupied table)
        if ($isOccupied && !empty($planCount)) {

            echo '
            <tr>
                <td colspan="5" style="background:#f2f2f2;font-weight:bold;">
                    PLAN WISE SUMMARY
                </td>
            </tr>';

            foreach ($planCount as $plan => $count) {
                echo '
                <tr>
                    <td colspan="5">
                        ' . $plan . ' - ' . $count . ' Room' . ($count > 1 ? 's' : '') . '
                    </td>
                </tr>';
            }
        }

        // 🔹 Space after each status
        echo '<tr><td colspan="' . ($isVacant ? 2 : 5) . '" style="height:12px;border:none;"></td></tr>';
    }

    echo '</table>';
    exit;
	}else{
        // 1. CAPTURE GROUPING PREFERENCE
        $groupBy = isset($_REQUEST['group_by']) ? $_REQUEST['group_by'] : '';
        
        $groupedRooms = [];
        $i = 0;
        $yy = '';

        // 2. PRE-FILTER AND GROUP THE DATA
        foreach($folioArray as $roomcount => $roomData) {
            $showRoom = false;
            
            // Check visibility logic
            if (isset($actual_room_status) && $actual_room_status != '5') {
                $showRoom = true;
            } elseif (!isset($actual_room_status)) {
                 $showRoom = true;
            } else {
                if ($today == $roomData['checkout_text']) {
                    $showRoom = true;
                    $yy = 'BC';
                }
            }

            if ($showRoom) {
                $i++;
                
                // Determine the group key
                $key = '';
                if ($groupBy == 'room_type') {
                    $key = $roomData['RoomName']; // Groups by base Room Type name
                } elseif ($groupBy == 'status') {
                    $key = ucfirst($roomData['status']); // Groups by Occupied, Vacant, etc.
                }

                $groupedRooms[$key][$roomcount] = $roomData;
            }
        }

        // 3. LOOP THROUGH GROUPS AND PRINT CARDS
        foreach ($groupedRooms as $groupName => $roomsInGroup) {
            
            // Print the Group Header if a grouping is selected
           // Print the Group Header if a grouping is selected
            if ($groupName !== '') {
                echo '<div style="grid-column: 1 / -1; width: 100%; margin-top: 8px; margin-bottom: 0px; border-bottom: 2px solid #e2e8f0; padding-bottom: 8px; padding-left: 4px;">
                        <h5 style="font-weight: 700; color: #334155; margin: 0; font-size: 1.25rem; display: flex; align-items: center; font-family: \'Inter\', sans-serif;">' . 
                            $groupName . ' 
                            <span style="background: #64748b; color: white; border-radius: 20px; padding: 2px 10px; font-size: 1.1rem; font-weight: 600; margin-left: 10px; box-shadow: 0 1px 2px rgba(0,0,0,0.1);">' . count($roomsInGroup) . ' Rooms</span>
                        </h5>
                      </div>';
            }

            // Print the cards for this specific group
            foreach ($roomsInGroup as $roomcount => $roomData) {
                
                $roomNumber = $roomData['room_no'];
                $roomType = $roomData['RoomType']; 
                $reservationNumber =  $roomData['mdoc_no'];
                $guestName = $roomData['GuestName']; 
                $guests = $roomData['Guest'];
                $adults = $roomData['adults_per_room'];
                $childBelow = $roomData['child_below_5_year'];
                $childAbove = $roomData['child_above_5_year'];
                $checkInDate = $roomData['Checkin'];
                $checkOutDate = $roomData['Checkout'];
                $folioNumber = $roomData['folio_mdoc_no'];
                $balance = "&#8377;".$roomData['BalanceAmount'];
                
                $roomStatus = $roomData['status'];
                $roomClass = $roomData['roomClass'];
                if ($roomStatus == 'Occupied' && $today == $roomData['checkout_text']) {
                    $roomClass = 'cstmBgOccupiedDepart';
                }
                
                $id_mst_guest = $roomData['id_mst_guest'];
                $id_resevation = $roomData['id_fo_reservations'];
                $id_mst_guest_order_by_room = $roomData['id_fo_reservations'];
                $id_owner_room = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$roomData['id_fo_bill']."'");
                $id_folio = $roomData['id_fo_view_folio'];
                
                // Determine HK Status
                if($roomData['house_keeping_status'] == '4'){
                    $HKStatus ='Clean';
                } elseif($roomData['house_keeping_status'] == '2'){
                    $HKStatus ='Maintenance';
                } elseif($roomData['house_keeping_status'] == '3'){
                    $HKStatus ='Block';
                } else{
                    $HKStatus ='Dirty';
                }

                $vare="''"; 

                // Generate Card HTML
             $text = '
        <div class="rvn-room-card" 
            data-room-number="' . strtolower($roomNumber) . '" 
            data-room-type="' . strtolower($roomType) . '" 
            data-res-no="' . strtolower($reservationNumber) . '" 
            data-guest-name="' . strtolower($guestName) . '" 
            data-folio-no="' . strtolower($folioNumber) . '">
            
            <div class="rvn-room-card-sub">
                <!-- Cleaned up Header Structure for Dense Packing -->
                <div class="rvn-room-header '.$roomClass.'" >
                    <div class="rvn-room-header-left">
                        <span class="rvn-room-number">' . $roomNumber . '</span>
                        <span class="rvn-room-type" title="' . $roomType . '">' . $roomType . '</span>
                    </div>
                    <div class="rvn-room-header-right">
                        <span class="res-no-txt">Res No: #' . $reservationNumber . '</span>
                        <span class="rvn-reservation-status rvn-status-' . strtolower($roomStatus) . '">' . $roomStatus . '</span>
                    </div>
                </div>

                <!-- START COLLAPSIBLE BODY -->
                <div class="collapsible-card-body" style="display: none;">
                    <div class="rvn-room-details" style="padding: 10px;">
                        <div style="width: 48%;">
                                    <p>Folio Owner: <strong><a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
                                    onclick="GetEditGuestDetail('.$id_mst_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio.');">' . $guestName . '</a></strong></p>';

                                    foreach ($guests as $guest) { }

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
                                    <p><b>Folio No:</b> <a href="'.$SITE_URL.'/frontoffice/onewindow.php?p=6&folio='.$id_folio.'">' . $folioNumber . '</a></p>
                                </div>
                                <div style="width: 51%;">
                                    <p><b>Folio Total:</b> <span class="rvn-room-balance">' . $balance . '</span></p>
                                </div>
                                <div style="width: 100%;">';
                                 
                                $k = 1;
                                foreach ($guests as $id_guest => $guest) {
                                    $gtatile = ($k == 1) ? 'Room Guest: <br/>' : '';
                                    $k++;
                                    $text .= '<p> '.$gtatile.' <strong><a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
                                    onclick="GetEditGuestDetail('.$id_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio.');">'  . $guest . '</a></strong></p>';
                                }

                                $text .= '</div>
                            </div>
                            
                            <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #f1f1f1; padding: 0px 15px !important;">
                                <!-- Left Side: HK Status -->
                                <div class="roomServiceStatus" style="display: flex; flex-direction: column;">
                                    <span style="font-size: 12px; margin-bottom: 2px; font-family: inter; color: #6c757d;">HK Status</span>
                                    <a type="button" data-toggle="modal" data-target="#EditRoomStatusModal'.$roomcount.'" 
                                       style="display: flex; align-items: center; text-decoration: none; cursor: pointer; color: #0275d8;" title="Change HK Status">
                                        <span class="rvnRoomserviceStatus" id="rvnRoomserviceStatus_'.$roomcount.'" style="font-weight: 500;">'.$HKStatus.'</span> 
                                        <i class="fas fa-edit" style="margin-left: 5px; font-size: 0.9rem;"></i>
                                    </a>
                                </div>

                                <!-- Right Side: Add Guest Button -->
                                <div class="rvn-room-actions">
                                    <a href="javascript:void(0);" style="text-decoration: none;" id="res_guestAddId"
                                       onclick="GetAddNewSharedGuestDetail('.$vare.','. addslashes($id_resevation).','.$roomData['id_mst_room_no_allocation'].','.$roomData['order_by_room'].','.$id_owner_room.',2,'.$id_folio.');">
                                        <button type="button" id="guestBtn" class="cstmActionBtn" title="Add Guest" 
                                                style="background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 50%; height: 36px; width: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer; padding: 0; margin: 0; box-shadow: none;">
                                            <i class="fas fa-user-plus" style="font-size: 1.2rem; color: #0284c7; margin-left: 2px;"></i>
                                        </button>
                                    </a>
                                </div>
                            </div>
                        </div> <!-- END COLLAPSIBLE BODY -->
                    </div>
                </div>'; 
                
                // Add the Modal
                $text .= '
                <div class="modal " id="EditRoomStatusModal'.$roomcount.'" tabindex="-1" role="dialog" aria-labelledby="EditRoomStatusModalLabel">
                    <div style="width : 40%!important; margin : auto;!important;">
                        <div class="modal-dialog" role="document" style="width : 60%!important;">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">House Keeping Status <br><b>Room No '.$roomNumber.'</b></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute!important; top: 15px!important; right: 10px!important;">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form id="roomstatusform'.$roomData['id_mst_room_no_allocation'].'" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" class="rm_id" name="rm_id" id="rm_id'.$roomData['id_mst_room_no_allocation'].'" value="'.$roomcount.'">
                                        <label for="exampleSelect">Status</label>
                                        <select class="form-control" class="cur_room_status" id="cur_room_status'.$roomData['id_mst_room_no_allocation'].'" name="cur_room_status">
                                            <option value="4" ' . (($roomData['house_keeping_status'] == 4) ? 'selected' : '') . '>Clean</option>
                                            <option value="1" ' . (($roomData['house_keeping_status'] == 1) ? 'selected' : '') . '>Dirty</option>
                                        </select>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-primary" onclick="saveHouseKeepingStatusForm(this);">Update</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';

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