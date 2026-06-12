<?php 

/************* if you are making any changes to this file kindly make the same changes in
phplib/cronRoomstatus.library.php file*************************************************/

function GetTotalRoomOld($hotelId,$roomId='')
	{	
		if($roomId==''){ 
		 $sql=executeSql("SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");
		 }else{
		  $sql=executeSql("SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'");
		 }
		 
		 $row = fetch_array($sql);
		 return $row['totalRoom'];
	}
	
	
function GetTotalRoomAllotedOld($dated,$hotelId,$roomId)
	{	
	
		if($roomId==''){
			 $sql = executeSql("Select sum(orderD.room_quantity) as roomAlloted from `".TBL_ORDERS."` orders left join `".TBL_ORDER_DETAIL."` orderD on orders.`id_order`=orderD.`id_order` where orders.`booking_status` IN (1,2) and orders.`valid`='1' and orders.`id_hotel`='".addslashes($hotelId)."' and dated = '".date('Y-m-d',strtotime($dated))."' ");
		 }else{
			 $sql = executeSql("Select sum(orderD.room_quantity) as roomAlloted from `".TBL_ORDERS."` orders left join `".TBL_ORDER_DETAIL."` orderD on orders.`id_order`=orderD.`id_order` where orders.`booking_status` IN (1,2) and orders.`valid`='1' and orders.`id_hotel`='".addslashes($hotelId)."' and  orderD.`room_id`='".addslashes($roomId)."' and dated = '".date('Y-m-d',strtotime($dated))."'");
		 }
		
		 $row = fetch_array($sql);		 
		 return $row['roomAlloted'];
		 
		 
	}
	

function GetTotalRoom($hotelId,$roomId='')
	{	
		if($roomId==''){ 
		 $sql=executeSql("SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");
		 }else{
		  $sql=executeSql("SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'");
		 }
		 
		 $row = fetch_array($sql);
		 return $row['totalRoom'];
	}
	
	
function GetTotalRoomAlloted($dated,$hotelId,$roomId)
	{	
	
	
		if($roomId==''){
			
		 $sqlGuestDetail = executeSql("SELECT * FROM `fs_assign_hotel_room` WHERE `hotel_id`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysql_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['room_id'];
			$str 		 = implode (", ", $AllRoomId);
		}

		 
			
			if($roomId!=''){
			 		$sql = executeSql("Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id`='".addslashes($roomId)."'");
			}else{
				
					$sql = executeSql("Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."`  where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id` IN ($str) ");
				}
			 
			 
		 }else{
			 		$sql = executeSql("Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id`='".addslashes($roomId)."'");
		 }
		
		
		  $row = fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}
	
function GetTotalRoomAllotedTwo($dated,$hotelId,$roomId)
	{	
	
		if($roomId=='0'){
			
		 $sqlGuestDetail = executeSql("SELECT * FROM `fs_assign_hotel_room` WHERE `hotel_id`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysql_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['room_id'];
			$str 		 = implode (", ", $AllRoomId);
		}
			
				
		$sql = executeSql("Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."`  where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id` IN ($str) ");
				
			 
			 
		 }else{
			 		$sql = executeSql("Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id`='".addslashes($roomId)."'");
		 }
		
		
		  $row = fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}	
	
function GetAssignTotalRoom($hotelId,$roomId)
	{
			global $connNew;
		if($roomId==''){ 
		 $sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' ");
		 }else{
		  $sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' and ahr.id_mst_room_types='".addslashes($roomId)."' and  `id_mst_room_types`='".addslashes($roomId)."'");
		 }
		 
		 $row = mysqli_fetch_array($sql);
		 return $row['totalRoom'];
	}
	
	
	
	
function GetTotalRoomoffline_block_hotel($dated,$hotelId,$roomId)
	{	
	
		if($roomId==''){
			 $sql = executeSql("Select sum(offline_block_hotel) as offline_block_hotel from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' ");
		 }else{
			 $sql = executeSql("Select sum(offline_block_hotel) as offline_block_hotel from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id`='".addslashes($roomId)."'");
		 }
		
		
		  $row = fetch_array($sql);
		  if($row['offline_block_hotel']==''){return 0;}else{ return $row['offline_block_hotel'];}
		
		 
		 
	}
	
function GetTotalRoomBlocked_Hotel($dated,$hotelId,$roomId)
	{	
	
		if($roomId==''){
			 $sql = executeSql("Select sum(blocked_hotel) as blocked_hotel from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' ");
		 }else{
			 $sql = executeSql("Select sum(blocked_hotel) as blocked_hotel from `".TBL_INVENTORY."` where `hotel_id`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `room_id`='".addslashes($roomId)."'");
		 }
		
		
		  $row = fetch_array($sql);
		  if($row['blocked_hotel']==''){return 0;}else{ return $row['blocked_hotel'];}
		
		 
		 
	}	


?>