<?php
include_once("../config/auto_loader.php"); 

//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_mst_room_no_allocation='0' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ";
$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_mst_room_no_allocation='0' group by id_fo_reservations,id_mst_room_types");
			
			
			if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					$reservation_id	=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$checkin	=	selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$checkout	=	selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					
					$id_mst_company	=	selectColumn(FO_RESERVATIONS,'id_mst_company'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$company_name	=	selectColumn('mst_company','name'," WHERE `id` = '".$id_mst_company."'");
					
					$id_mst_guest	=	selectColumn(FO_RESERVATIONS,'id_mst_guest'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$Guest_name	=	selectColumn('mst_guest','first_name'," WHERE `id` = '".$id_mst_guest."'").' '.selectColumn('mst_guest','last_name'," WHERE `id` = '".$id_mst_guest."'");
					
					$room_name	=	selectColumn('mst_room_types','name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					
					$RoomWiseArray[]=array("id"=>$rowOrderDetail->id_fo_reservations,"reservation_id"=>$reservation_id,"roomType"=>$room_name,"guest"=>$Guest_name,"source"=>$company_name,"checkIn"=>date('d-m-Y',strtotime($checkin)),"checkOut"=>date('d-m-Y',strtotime($checkout)),"NoOfRoom"=>$rowOrderDetail->room_quantity,"pending"=>"2","id_mst_room_types"=>$rowOrderDetail->id_mst_room_types,"id_mst_hotels"=>$rowOrderDetail->id_mst_hotels);
				}}
$demoData = $RoomWiseArray;

/*array(
    array("id"=>encryptor(encrypt,'12'),"reservation_id"=>"eee","roomType"=>"Garden Room","guest"=>"Shubhi","source"=>"cox and kings","checkIn"=>"27-09-2022","checkOut"=>"30-09-2022","booked"=>"4","pending"=>"2"),
    array("id"=>"2","reservation_id"=>"wh202","roomType"=>"Maharani Suit","guest"=>"Hitesh","source"=>"sita travel","checkIn"=>"18-03-2021","checkOut"=>"25-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"3","reservation_id"=>"wh203","roomType"=>"Deluxe Room","guest"=>"Sumit","source"=>"cox and kings","checkIn"=>"20-03-2021","checkOut"=>"24-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>encryptor(encrypt,'21'),"reservation_id"=>"wh204","roomType"=>"Maharani Suit","guest"=>"Sundaram","source"=>"cox and kings","checkIn"=>"25-03-2021","checkOut"=>"27-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"5","reservation_id"=>"wh205","roomType"=>"Garden Room","guest"=>"Shafeer","source"=>"cox and kings","checkIn"=>"5-03-2021","checkOut"=>"9-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"6","reservation_id"=>"wh206","roomType"=>"Deluxe Room","guest"=>"Vipin","source"=>"cox and kings","checkIn"=>"10-03-2021","checkOut"=>"12-03-2021","booked"=>"4","pending"=>"2"),$RoomWiseArray
);*/

echo json_encode($demoData);

?>