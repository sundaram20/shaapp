<?php

include_once("../../config/auto_loader.php");


function GetTotalRoomAllotedTwo1($dated,$hotelId,$roomId,$connNew)
	{	
	
		global $connNew;
		if($roomId=='0'){
			
		 $sqlGuestDetail = mysqli_query($connNew,"SELECT * FROM `mst_assign_hotel_rooms` WHERE `id_mst_hotels`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysqli_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['id_mst_room_types'];
			$str 		 = implode (", ", $AllRoomId);
		}
			
				
		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory`  where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types` IN ($str) and status=1");
				
			 
		 }else{
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and status=1 and  `id_mst_room_types`='".addslashes($roomId)."'");
		 }
		
		
		  $row = mysqli_fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}	
function GetTotalRoomAlloted2($dated,$hotelId,$roomId,$connNew)
	{	
	
	global $connNew;
		if($roomId==''){
			
		 $sqlGuestDetail = mysqli_query($connNew,"SELECT * FROM `mst_assign_hotel_rooms` WHERE `id_mst_hotels`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysqli_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['id_mst_room_types'];
			$str 		 = implode (", ", $AllRoomId);
		}

			
			if($roomId!=''){
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'");
			}else{
				
					$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."`  where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types` IN ($str) ");
				}
			 
			 
		 }else{//echo '11'."Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'";
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'");
		 }
		
		
		  $row = mysqli_fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}

if(isset($_REQUEST['chkRoomNum']) && $_REQUEST['chkRoomId']){
	$chkRoomNum = $_REQUEST['chkRoomNum'];
	$chkRoomId = $_REQUEST['chkRoomId'];
	$EditID = addslashes(encryptor('decrypt',$_REQUEST['EditID']));
	$rate_plan_id = $_REQUEST['rate_plan_id'];
	$adults = $_REQUEST['adult_no'];
	$child = $_REQUEST['child_no'];
}


$id_mst_hotels = '1';
$id_mst_room_types = $_POST['room_id'];
$reservation_date = explode(' to ',$_POST['period']);
$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));

 $sqlMappingInventory = 'SELECT auto_sync_inv FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
								WHERE  B.id_mst_hotels="'.$id_mst_hotels.'" AND B.status=1 and channel_type=1';
	$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
	$resultMapping   =    mysqli_fetch_object($QueryMapping);
    $autoInventoryUpdate=$resultMapping->auto_sync_inv;

//$autoInventoryUpdate = selectColumn(TBL_HOTEL_MAPPING,'auto_sync_inv','Where id_mst_hotels="'.$id_mst_hotels.'" AND channel_id=1 AND status=1 ');

//$overbooking_notallowed = selectColumn(TBL_HOTELS,'overbooking_notallowed','Where id="'.$id_mst_hotels.'" AND  status=1 ');

function functionAvailabilityCheckOnLoad($reservation_date,$connNew){
    $id_mst_hotels = '1';
/*-------------------Update Room Availability START----------------------------*/
$checkoutDate_upadate = date ("Y-m-d", strtotime($reservation_date['1']));
$checkoutDate_OverBooking = date ("Y-m-d", strtotime($reservation_date['1']));
$startDate = date ("Y-m-d", strtotime($reservation_date['0']));

$daysNew =  abs((strtotime($startDate) - strtotime($checkoutDate_upadate))/ 86400 );
	if($daysNew < '7'){
		 $checkoutDate_upadate = date ("Y-m-d", strtotime("+7 day", strtotime($checkinDate)));
	}else {
		 $checkoutDate_upadate = date ("Y-m-d", strtotime($reservation_date['1']));
	}

while (strtotime($checkinDate) < strtotime($checkoutDate_upadate)) {	
					  
					  
					  
 $checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
				  
 if($id_mst_room_types == 0){ 
	$resRoom1 = mysqli_query($connNew,"SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($id_mst_hotels)."'");
	}else{
	$resRoom1 = mysqli_query($connNew,"SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.over_booking_limit from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($id_mst_hotels)."' and ahr.room_id='".addslashes($room_id)."'");
	}
		while($rowRoom_update = mysqli_fetch_object($resRoom1)){
			
			  
			  $totalRoom 							= GetAssignTotalRoom($id_mst_hotels,$rowRoom_update->id_mst_room_types);
			  
		$ResDetailSql	=	mysqli_query($connNew,"Select  fo_reservations.booking_no,`fo_reservations`.booking_status,`fo_reservations_details`.dated ,fo_reservations_details.no_showoff,
			sum( CASE WHEN `fo_reservations`.booking_status = '4'   THEN ROUND(1,0) ELSE 0 END)  AS ca , 
			sum(CASE WHEN `fo_reservations`.booking_status = '1'   THEN ROUND(1,0) ELSE 0 END ) AS Confirmed,
    		 sum(CASE WHEN `fo_reservations`.booking_status = '2'   THEN ROUND(1,0) ELSE 0 END)  AS Tentative,   
			sum(CASE WHEN `fo_reservations`.booking_status = '3'   THEN ROUND(1,0) ELSE 0 END ) AS Waitlisted ,
            
             fo_reservations_details.id_mst_room_types
    		
			 from `fo_reservations` left join `fo_reservations_details`  on `fo_reservations`.`id`=`fo_reservations_details`.`id_fo_reservations` 
			 WHERE  
			 
			 `fo_reservations`.`id_mst_hotels`='".addslashes($id_mst_hotels)."'  
			 and `fo_reservations_details`.`id_mst_room_types`='".addslashes($rowRoom_update->id_mst_room_types)."'
			  and fo_reservations_details.no_showoff='0'
			  and  `fo_reservations_details`.dated = '".date('Y-m-d',strtotime($startDate))."'");
			  $GetTotalRoomAllotedConfirmed = mysqli_fetch_array($ResDetailSql);
			  
			  $orderTableAvailableRooms =$GetTotalRoomAllotedConfirmed['Confirmed']+$GetTotalRoomAllotedConfirmed['Tentative']+$GetTotalRoomAllotedConfirmed['Waitlisted'];
			
			
			$crs_available			=	$totalRoom-($orderTableAvailableRooms+$GetTotalRoomoffline_block_hotel);
			$availableData1 = "UPDATE  `fo_inventory`  SET 
								crs_available = '".addslashes($crs_available)."',
								".$liveCond."								
								blocked_hotel = '".addslashes(isset($orderTableAvailableRooms)?$orderTableAvailableRooms:0)."',
								confirmed = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Confirmed'])?$GetTotalRoomAllotedConfirmed['Confirmed']:0)."' ,
								tentative = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Tentative'])?$GetTotalRoomAllotedConfirmed['Tentative']:0)."',
								waitlisted = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Waitlisted'])?$GetTotalRoomAllotedConfirmed['Waitlisted']:0)."' 								
								
								where  `id_mst_hotels`='".addslashes($id_mst_hotels)."' and 
						  		`id_mst_room_types`='".addslashes($rowRoom_update->id_mst_room_types)."' and 
								allocation_date = '".date('Y-m-d',strtotime($startDate))."'";
			
			$updateInventory = mysqli_query($connNew,$availableData1);
			} 
		 $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));	

			  			
  }
}

/*-------------------Update Room Availability End----------------------------*/


	?>