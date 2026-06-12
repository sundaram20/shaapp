<?php include_once("../config/auto_loader.php");?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php include_once('GuestModalRoomview.php');?>
<div class="content-wrapper">

	<?php 
	

 $sqlRes="SELECT count(fo_reservations_details.room_quantity) as qty ,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status!='4' 
GROUP by fo_reservations_details.dated ,fo_reservations_details.id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";



//"SELECT count(room_quantity) as qty ,dated,id_mst_room_types,id_mst_hotels FROM `fo_reservations_details` WHERE booking_status!='761' GROUP by dated ,id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";

$resRes = mysqli_query($connNew,$sqlRes);
						
						
						while($rowRes = mysqli_fetch_object($resRes)){ 
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						$rownew = mysqli_fetch_object($resnew);
						
						
						$sqlRoom=  "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels = '".$rowRes->id_mst_hotels."' and ahr.id_mst_room_types='".addslashes($rowRes->id_mst_room_types)."'" ;
						
						
						$resRoom = mysqli_query($connNew,$sqlRoom);
						$rowRoom = mysqli_fetch_object($resRoom);
						
						
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$confirmed =  $rowRes->qty ; 
							
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						
						mysqli_query($connNew,$insertGrid);
						
						
						
						
						
						
						}?>
	<!-- Audit Trail Modal -->

	<!-- End Audit trail Modal -->

	<!-- Content Header (Page header) -->

	<?php $session=$_GET['submenu'];
//echo $_REQUEST['id_posbilling'];
	 ?>
	<section class="content-header">
		<div class="row">
			<div class="col-md-4 col-xs-12">
				<!--<h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>-->

			</div>
			<div class="col-md-4 col-xs-12 dd-f">
				<div class="icn-box">







				</div>

			</div>
			<div class="col-md-4 col-xs-12 tb-br">
				<?php echo breadCrumbs(); ?>
			</div>
		</div>
	</section>


	<!--  <section class="content-header">
      <h1>
        Billing Manager
      </h1>
      <ol class="breadcrumb">
        <li><a href="managePO.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  Billing</li>
      </ol>
    </section>  -->
	<!-- Main content -->

	<style>
		/* Container for room cards */
		.rvn-room-card-container {
			display: flex;
			flex-wrap: wrap;

		}

		/* Individual room card */
		.rvn-room-card {

			padding: 10px;
			width: 25%;
		}

		.rvn-room-card-sub {
			background-color: #fff;
			border-radius: 12px;
			box-shadow: 0px 2px 15px rgba(0, 0, 0, 0.1);

			padding: 10px;
			position: relative;
			font-family: 'Arial', sans-serif;
			transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
		}

		/* Card hover effect for more modern feel */
		.rvn-room-card-sub:hover {
			transform: translateY(-8px);
			box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
		}

		/* Header section (room number and reservation status) */
		.rvn-room-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			margin-bottom: 12px;
			border-bottom: 1px solid #f1f1f1;
			padding: 0px 0px 5px 0px;
		}

		.rvn-room-number {
			font-size: 22px;
			font-weight: bold;
			color: #333;
		}

		.rvn-room-type {
			font-size: 13px;
			color: #888;
			margin-top: 4px;
		}

		.rvn-reservation-status {
			font-size: 13px;
			font-weight: bold;
			padding: 4px 10px;
			border-radius: 6px;
			text-align: center;
		}

		/* Status colors */


		.rvn-status-available {
			background-color: #e0f7e9;
			color: #388e3c;
		}

		.rvn-status-maintenance {
			background-color: #fff6d1;
			color: #fbc02d;
		}

		/* Room details */
		.rvn-room-details {
			font-size: 13px;
			color: #444;
			line-height: 1.5;
			margin-bottom: 10px;
			display: flex;
			flex-wrap: wrap;
			justify-content: space-between;
			align-items: center;
		}

		.rvn-room-details p {
			margin: 2px 0;
		}

		.rvn-room-balance {
			font-weight: bold;
			color: #388e3c;
		}

		/* Action buttons */
		.rvn-room-actions {
			display: flex;
			justify-content: space-between;
			padding-top: 12px;

		}

		.rvn-room-actions button {
			background-color: transparent;
			border: none;
			cursor: pointer;
			font-size: 16px;
			color: #555;
			display: flex;
			align-items: center;
			justify-content: center;
			transition: color 0.2s;
		}

		.rvn-room-actions button:hover {
			color: #000;
		}

		.rvn-action-icon {
			width: 20px;
			height: 20px;
		}

		/* Notes count badge */
		.rvn-notes-count {
			background-color: #f44336;
			color: #fff;
			font-size: 12px;
			padding: 4px 6px;
			border-radius: 50%;
			position: absolute;
			top: 10px;
			right: 10px;
		}

		.rvn-status-rs-clean {
			background: #dcfce7 !important;
			color: #166534 !important;
			font-size: 1.1rem !important;
		}


		/* Button Style for Opening Drawer */
		.rvn-open-drawer-btn {
			padding: 10px 20px;
			background-color: #0056b3;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
			margin: 20px;
		}

		/* Drawer Container */
		.rvn-drawer {
			position: fixed;
			right: -100%;
			/* Initially Hidden */
			top: 0;
			width: 400px;
			height: 100vh;
			background-color: white;
			box-shadow: -5px 0px 15px rgba(0, 0, 0, 0.2);
			display: flex;
			flex-direction: column;
			transition: right 0.3s ease;
			z-index: 1001;
		}

		/* Drawer Header */
		.rvn-drawer-header {
			padding: 10px;
			background-color: #f1f1f1;
			height: 10%;
			/* 10% height */
			display: flex;
			justify-content: space-between;
			align-items: center;
		}

		/* Drawer Body */
		.rvn-drawer-body {
			padding: 20px;
			flex-grow: 1;
			/* 80% height */
			overflow-y: auto;
		}

		/* Drawer Footer */
		.rvn-drawer-footer {
			padding: 10px;
			background-color: #f1f1f1;
			height: 10%;
			/* 10% height */
			display: flex;
			justify-content: flex-end;
			align-items: center;
			gap: 10px;
		}

		/* Footer Button Styling */
		.rvn-footer-action-btn {
			padding: 10px 15px;
			background-color: #0056b3;
			color: white;
			border: none;
			border-radius: 4px;
			cursor: pointer;
		}

		/* Overlay Background */
		.rvn-overlay {
			position: fixed;
			top: 0;
			left: 0;
			width: 100vw;
			height: 100vh;
			background-color: rgba(0, 0, 0, 0.5);
			visibility: hidden;
			/* Initially Hidden */
			opacity: 0;
			transition: opacity 0.3s ease, visibility 0.3s ease;
			z-index: 1000;
		}

		/* Active Drawer State */
		.rvn-drawer-active {
			right: 0;
			z-index: 99999 !important;
			/* Slide in drawer */
		}

		/* Active Overlay State */
		.rvn-overlay-active {
			visibility: visible;
			opacity: 1;
		}

		.cstmDrawer {
			position: fixed;
			right: 0;
			top: 0;
			width: 25%;
			height: 100%;
			background-color: white;
			box-shadow: -2px 0 5px rgba(0, 0, 0, 0.2);
			transition: transform 0.3s ease;
			z-index: 99999;
			/* Ensure the drawer is above the overlay */
		}


		.drawer-header {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 1rem;
			background-color: #f8f8f8;
			border-bottom: 1px solid #eaeaea;
		}

		.drawer-content {
			padding: 1rem;
			height: 80%;
		}


		.drawer-footer {
			display: flex;

			padding: 1rem;
			background-color: #f8f8f8;
			border-top: 1px solid #eaeaea;
		}

		.overlay {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-color: rgba(0, 0, 0, 0.5);
			/* Semi-transparent background */
			z-index: 50;
			/* Ensure the overlay is above other content */
		}

		.rvnHeader {
			display: flex;

			align-items: center;
			padding: 1rem;
			background-color: #f8f9fa;
			border-bottom: 1px solid #ddd;
		}

		.rvnSearchBar {
			flex-grow: 1;
			margin-right: 1rem;
			display: flex;
			align-items: center;
		}

		.rvnSearchBar input {
			width: 100%;
			padding: 0.5rem 1rem;
			border: 1px solid #ccc;
			border-radius: 0.375rem;
		}

		.rvnFilterGroup {
			display: flex;
			gap: 1rem;
		}

		.rvnDropdown {
			padding: 0.8rem 1rem;
			border: 1px solid #ccc;
			border-radius: 0.375rem;
			background-color: white;
		}

		.rvn-status-occupied {
			background-color: #fde2e2;
			/* Light red background */
			color: #d32f2f;
			/* Dark red text */
			font-size: 11px !important;
		}

		.rvn-status-reserved {
			background-color: #effae2;
			color: #499855;
			font-size: 11px !important;
		}

		.rvn-status-blocked {
			background-color: #e6f0ff;
			/* Light blue background */
			color: #00f;
			/* Blue text */
			font-size: 11px !important;
		}

		.rvn-status-maintenance {
			background-color: #fff6d1;
			color: #d5a01b;
			font-size: 11px !important;
		}

		.cstmStatusStrp {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 10px;
	margin-left: 3rem;
}

.cstmStatus {
    display: flex;
    align-items: center;
    gap: 5px;
}

.cstmColorBox {
    width: 15px;
    height: 15px;
    display: inline-block;
    border-radius: 3px;
}

.cstmStatusStrpReserved {
    background-color: #0091fba3 !important
}

.cstmStatusStrpOccupied {
    background-color: #ef8787;
}

.cstmStatusStrpVacant {
    background-color: #7beb7b;
}

.cstmStatusStrpDeparture {
    background-color: #ffa50099;
}

	</style>
	<section class="content">

		<header class="rvnHeader">
			<!-- Search Bar -->
			<div class="search-container col-md-5" style="position: relative;">
				<input type="text" id="roomSearch"
					placeholder="Search by Room No., Room Type, Res No, Guest Name, or Folio No." class="search-input"
					style="padding: 8px; width: 100%; border-radius: 4px; border: 1px solid #ccc;">
				<span class="shortcut"
					style="position: absolute; right: 25px; top: 50%; transform: translateY(-50%); font-size: 11px; color: #888; border : 1px solid gray; padding : 3px 5px; border-radius : 4px; font-family: 'Inter';">
					Ctrl+S
				</span>
			</div>



			<!-- Filter Dropdowns -->
			<div class="rvnFilterGroup">
				<!-- Status Filter -->
				<select class="rvnDropdown roomStatusFilter" onChange="loadRoomFilter(this.value);">
					<option value="0">All</option>
					<option selected value="3">Occupied</option>
					<option value="4">Vacant</option>
					<option value="2">Expected Arrivals</option>
					<option value="5">Expected Departures</option>
					<!-- <option value="5">Blocked</option>
                    <option value="1">Dirty</option>
					<option value="6">Maintenance</option> -->
				</select>
				
				
				<p style=" width:72px;padding-top: 10px;"><b><span id="room_count"></span></b></p>
			</div>
            <?php 
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$checkin_results = [];
$checkout_results = [];
$checkout_pendings = 0;
$occupied_rooms = 0;
$guest_count = [];	
$occupied_room_query = mysqli_query($connNew,"SELECT DISTINCT resdetails.id as resdetail_id,room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkout_time as reservation_checkout_time, resdetails.checkin_time as reservation_checkin_time, resdetails.id_fo_rate_plan 

FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."'");

 while ($checkin = mysqli_fetch_object($occupied_room_query)) {
	$checkin_results[$checkin->resdetail_id]='1'; 
	
	
        $GuestName = selectColumn("mst_guest",'first_name'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $lastName = selectColumn("mst_guest",'last_name'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");

        $checkin_date = date('Y-m-d', strtotime($checkin->checkin_date));
        $checkout_date = date('Y-m-d', strtotime($checkin->checkout_date));
        $reservation_checkout = date('Y-m-d', strtotime($checkin->reservation_checkout));

        // echo $checkin_date == $today;
        // echo "<br/>";
        if ($checkin_date == $today) {
            // echo $checkin->id;
            // echo "<br/>";
            $plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$checkin->id_fo_rate_plan."'");
            $checkin_results[$checkin->resdetail_id] = [
                'guest_name' => $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'',
                'room_no' => $checkin->room_no,
                'checkin_time' => $checkin->reservation_checkin_time,
                'child_per_room' => $checkin->child_below_5_year + $checkin->child_above_5_year,
                'adults_per_room' => $checkin->adults_per_room,
                'plan_name' => $plan_name,
            ];
        }

        if ($checkout_date == $today) {
            $checkout_results[$checkin->resdetail_id] = [
                'guest_name' => $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'',
                'room_no' => $checkin->room_no,
                'checkout_time' => $checkin->reservation_checkout_time,
            ];
        }
        
        if ($reservation_checkout == $today && $checkin->checkout_date == '') {
            $checkout_pendings++;
        }
        if ($checkin->no_showoff == '0' && $checkin->checkout_date == '') {
            $occupied_rooms += 1;
            $guest_count[$checkin->resdetail_id] = [
                'adults_per_room' => $checkin->adults_per_room,
                'child_below_5_year' => $checkin->child_below_5_year,
                'child_above_5_year' => $checkin->child_above_5_year,
            ];
        }
    
 }
 
 
 $arrival_pending_query = "SELECT * FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id 
where ".FO_RESERVATIONS_DETAILS.".checkin_status = '0' AND ".FO_RESERVATIONS_DETAILS.".no_showoff = '0' and `".FO_RESERVATIONS."`.booking_status IN ('1','2')
AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated) = '".$today."' AND DATE(`".FO_RESERVATIONS."`.checkin) = '".$today."'  order by `".FO_RESERVATIONS."`.id desc";
$arrival_pending_result = mysqli_query($connNew, $arrival_pending_query);
$arrival_pendings = mysqli_num_rows($arrival_pending_result);
 
 
 
 
 
 
 
 
 
 
 
 $results = [];
$hotel_room_query = mysqli_query($connNew, "SELECT id_mst_room_types FROM mst_assign_hotel_rooms GROUP BY id_mst_room_types");
$hotel_rooms = [];

while ($row = mysqli_fetch_object($hotel_room_query)) {
    $hotel_rooms[] = $row->id_mst_room_types;
}

$room_ids_string = implode(",", $hotel_rooms);
$sql = "SELECT * FROM " . TBL_ROOMNO . " WHERE id_mst_room_types IN (" . $room_ids_string . ") and status = 1 GROUP BY room_no";
$room_no_allocations = mysqli_query($connNew, $sql);

// Check for query execution errors
if (!$room_no_allocations) {
    die("Error executing query while room no allocations: " . mysqli_error($connNew));
}

$total_rooms = mysqli_num_rows($room_no_allocations);
 $vacant_rooms = $total_rooms - $occupied_rooms;
 
 
 
$SqlConn = " AND `room_status` IN (3)"; 
 $sqlAllRooms = mysqli_query($connNew,"SELECT *

FROM `mst_room_no_allocation`  
 


WHERE management_block = 'No' and status = '1'  $SqlConn order by display_order 
		
		 ");
		 
		
		if(mysqli_num_rows($sqlAllRooms) >0 ){
			$y=0;
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

		$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
		WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."'");
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
			
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ //print_r($rowOrderDetail);
				
				$booking_no	= selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkin	= selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkout	= selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				
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
					
					$plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");				
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
					
					
					
				
				}
				
					
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
			
			}
				}
		}
 //echo count($folioArray);
?>


<div class="cstmStatusStrp" style="margin-left: 104px !important;">

   
    <div class="cstmStatus">
        <span class="cstmColorBox cstmStatusStrpOccupied"></span>
        <span>Occupied (<?php echo count($folioArray);?>)</span>
    </div>
    <div class="cstmStatus">
        <span class="cstmColorBox cstmStatusStrpVacant"></span>
        <span>Vacant (<?php echo ($vacant_rooms = $total_rooms - count($folioArray));?>)</span>
    </div>
	 <div class="cstmStatus">
        <span class="cstmColorBox cstmStatusStrpReserved"></span>
        <span>Expected Arrivals (<?php echo ($arrival_pendings);?>)</span>
    </div>
    
    
    <?php  $kk=0;
	$folioArray='';
	$folioArray=array();
	$SqlConn = " AND `room_status` IN (3)"; 
 $sqlAllRooms = mysqli_query($connNew,"SELECT *

FROM `mst_room_no_allocation`  
 


WHERE management_block = 'No' and status = '1'  $SqlConn order by display_order 
		
		 ");
		 
		
		if(mysqli_num_rows($sqlAllRooms) >0 ){
			$y=0;
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

		$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.display_order,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,
		resdetails.id_mst_guest,resdetails.id_shared_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room
		FROM `mst_room_no_allocation` as room 
		INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
		INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
		WHERE fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0' and  resdetails.id_mst_room_no_allocation='".$rowAllRooms->id."'");
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
			
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ //print_r($rowOrderDetail);
				
				$booking_no	= selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkin	= selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkout	= selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				
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
					
					$plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");				
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
					
					
					if ($today == date('Y-m-d',strtotime($checkout))) {
				
				 $kk=$kk+1;
			}
				
				}
				
					
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
			
			}
				}
		}
	$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
	$i = 0;//echo $today;
    foreach($folioArray as $roomcount=>$roomData) {

			if ($today == $roomData['checkout_text']) {
				$i++;
				
			}
		
    }	
	
	
	?>
    <div class="cstmStatus">
        <span class="cstmColorBox cstmStatusStrpDeparture"></span>
        <span>Expected Departures (<?php echo  ($i);?>)</span>
    </div>
</div>



		</header>



		<script>
			document.addEventListener("DOMContentLoaded", function () {
				const roomStatusFilter = document.querySelector('.roomStatusFilter');
				const roomCards = document.querySelectorAll('.rvn-room-card');

				roomStatusFilter.addEventListener('change', function () {
					const selectedStatus = this.value;

					roomCards.forEach(card => {
						const roomStatus = card.querySelector('.rvn-reservation-status').innerText
							.trim();

						if (selectedStatus === 'all' || roomStatus === selectedStatus) {
							card.style.display = 'block';
						} else {
							card.style.display = 'none';
						}
					});
				});
			});
		</script>



		<div class="rvn-room-card-container" id="roomCardContainer">

			

		</div>



	</section>
	<!-- /.content -->
</div>
<div id="overlay" class="overlay hidden"></div>

<!-- Drawers -->
<div id="amendStayDrawer" class="cstmDrawer hidden">
	<div class="drawer-header">
		<h2>Amend Stay</h2>
		<button class="close-btn" onclick="closeDrawer('amendStayDrawer')">&times;</button>
	</div>
	<div class="drawer-content">

		<div class="form-group ">
			<label for="checkin" style="float:left;">New Check Out</label>
			<input type="date" class="form-control hasDatepicker" placeholder="Enter checkout Date"
				id="checkoutExtend_date" name="checkoutExtend_date" value="">
		</div>
	</div>
	<div class="drawer-footer flex justify-end space-x-3 p-4 border-t">
		<button
			class="btn-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring focus:ring-blue-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; border : none!important;">
			Save
		</button>
		<button
			class="close-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring focus:ring-gray-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; margin-left : 0.8rem!important;"
			onclick="closeDrawer('amendStayDrawer')">
			Close
		</button>
	</div>

</div>

<div id="changeRoomDrawer" class="cstmDrawer hidden">
	<div class="drawer-header">
		<h2>Change Room</h2>
		<button class="close-btn" onclick="closeDrawer('changeRoomDrawer')">&times;</button>
	</div>
	<div class="drawer-content">
		<p>Select a new room.</p>
		<select>
			<option>Room 101</option>
			<option>Room 102</option>
			<option>Room 103</option>
		</select>
	</div>
	<div class="drawer-footer">
		<button
			class="btn-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring focus:ring-blue-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; border : none!important;">Save</button>
		<button
			class="close-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring focus:ring-gray-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; margin-left : 0.8rem!important;"
			onclick="closeDrawer('changeRoomDrawer')">Close</button>
	</div>
</div>

<div id="checkOutDrawer" class="cstmDrawer hidden">
	<div class="drawer-header">
		<h2>Check Out</h2>
		<button class="close-btn" onclick="closeDrawer('checkOutDrawer')">&times;</button>
	</div>
	<div class="drawer-content">
		<p>Are you sure you want to check out?</p>
		<button class="confirm-btn">Confirm Check Out</button>
	</div>
	<div class="drawer-footer">

	</div>
</div>

<div id="noteDrawer" class="cstmDrawer hidden">
	<div class="drawer-header">
		<h2>Notes</h2>
		<button class="close-btn" onclick="closeDrawer('noteDrawer')">&times;</button>
	</div>
	<div class="drawer-content">
		<p>View and manage guest notes here.</p>
	</div>
	<div class="drawer-footer">
		<button
			class="btn-primary text-white px-4 py-2 rounded-lg shadow-md hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring focus:ring-blue-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; border : none!important;"> Save</button>
		<button
			class="close-btn bg-gray-200 text-gray-700 px-4 py-2 rounded-lg shadow-md hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:ring focus:ring-gray-300 transition duration-200"
			style="padding : 0.8rem 2.5rem!important; margin-left : 0.8rem!important;"
			onclick="closeDrawer('noteDrawer')">Close</button>
	</div>
</div>

<!-- drawer ends here -->

<script>
	document.addEventListener('keydown', function (event) {
		// Check if Shift + S is pressed

		if (event.ctrlKey && event.key === 's') {
			event.preventDefault(); // Prevent the default behavior (which might save the page)
			document.getElementById('roomSearch').focus(); // Focus on the input
		}

	});
</script>

<script>
	document.getElementById('roomSearch').addEventListener('keyup', function () {
		var input = this.value.toLowerCase();
		var roomCards = document.querySelectorAll('.rvn-room-card');

		roomCards.forEach(function (card) {
			var roomNumber = card.getAttribute('data-room-number');
			var roomType = card.getAttribute('data-room-type');
			var resNo = card.getAttribute('data-res-no');
			var guestName = card.getAttribute('data-guest-name');
			var folioNo = card.getAttribute('data-folio-no');

			// Check if any attribute matches the search input
			if (roomNumber.includes(input) || roomType.includes(input) || resNo.includes(input) ||
				guestName.includes(input) || folioNo.includes(input)) {
				card.style.display = ''; // Show the card if it matches
			} else {
				card.style.display = 'none'; // Hide the card if it doesn't match
			}
		});
	});
</script>


<?php include_once("../includes/footer.php");?>


<script>
	$(".targetDivShow").hide();

	function GetPostTariff(id_post_tariff) {
		if (id_post_tariff == '1') {
			$(".targetDivShow").hide();
		} else if (id_post_tariff == '2') {
			$(".targetDivShow").toggle();
		} else {
			$(".targetDivShow").hide();
		}


	}

	function inuseUpdate() {
		var id_post_tariff = $('#id_post_tariff').val();
		var post_tariff_date = $('#post_tariff_date').val();
		var id_fo_bill = $('#id_fo_bill').val();

		$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdateInUseDate.php',
			data: 'post_tariff_date=' + post_tariff_date + '&id_post_tariff=' + id_post_tariff + '&id_fo_bill=' +
				id_fo_bill,
			success: function (result) {
				alert(result);
			},

		});

	}
	window.onload = function () {

		roomview();
	};

	function roomview() {

		$.ajax({
			url: "ajax/ajaxRoomViewTest.php",
			data: 'room_status=3',
			type: "GET",
			success: function (data) {
				$("#roomCardContainer").html(data);
				// alert(data);
				//  roomStats();
			}
		});
		/* var newUrl = window.location.href + "&room_type=" + roomTypeName;
		window.location.href = newUrl; */
	}

	function loadRoomFilter(value) {
		$.ajax({
			url: "ajax/ajaxRoomViewTest.php",
			data: 'room_status=' + value,
			type: "GET",
			success: function (data) {
				$("#roomCardContainer").html(data);
				// alert(data);
				//  roomStats();
			}
		});
		/* var newUrl = window.location.href + "&room_type=" + roomTypeName;
		window.location.href = newUrl; */
	}

	//   loadRoomFilter('3');
	
 function saveHouseKeepingStatusForm(button) {
  event.preventDefault();

  var form = $(button).closest('form');
  var rm_id = form.find('.rm_id').val();
  var formData = form.serialize();

  $.ajax({
    url: "ajax/ajaxHouseKeepingStatusForm.php",
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.success) {
        // Close the modal
        $("#EditRoomStatusModal" + rm_id).modal("hide");

        // Update the UI with new status label
        $("#rvnRoomserviceStatus_" + rm_id).text(response.status_label);

        // Optional: Show a toast or alert
        // alert(response.message);
      } else {
        alert("Update failed: " + response.message);
      }
    },
    error: function () {
      alert("Something went wrong. Please try again.");
    }
  });
}

</script>