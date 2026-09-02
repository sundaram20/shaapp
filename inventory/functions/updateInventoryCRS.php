<?php

	
function updateInventoryCRS($connNew,$arrayOfDates,$id_mst_room_type,$id_mst_hotels){
		//global $connNew;
		
		echo $sqlMappingInventory = 'SELECT auto_sync_inv,channel_type,A.id as chid,B.channel_user_name,B.channel_password FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
								WHERE  B.hotel_id="'.$id_mst_hotels.'" AND B.status=1 and A.channel_type=3';
	$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
	$resultMapping   =    mysqli_fetch_object($QueryMapping);
   $autoInventoryUpdate=$resultMapping->auto_sync_inv;
	 $channelIdOneFineRate=$resultMapping->channel_type;
	$channel_user_name=$resultMapping->channel_user_name;
	$channel_password=$resultMapping->channel_password;
	if($autoInventoryUpdate==1){

	$id_hotel_booking_engine = selectColumn('fs_hotel_mapping','booking_engine_id','Where hotel_id="'.$id_mst_hotels.'" AND channel_id="'.$resultMapping->chid.'" AND status=1 ');
		
		 $id_hotel_mapping = selectColumn('fs_hotel_mapping','id','Where hotel_id="'.$id_mst_hotels.'" AND channel_id="'.$resultMapping->chid.'" AND status=1 ');
		
			
			$ids_room_booking_engine = selectColumn('fs_room_mapping','booking_engine_id','Where hotel_mapping_id="'.$id_hotel_mapping.'" AND room_id="'.$id_mst_room_type.'" AND status=1 ');

		//echo '=='.$id_mst_room_type;
		//echo '===>'.$id_mst_hotels;
		//echo '<pre>';print_r($arrayOfDates);
	
	$dataArray = [];
foreach ($arrayOfDates as $date => $count) {
    $dataArray[$date] = [
        'date' => $date,
        'blocked_hotel' => $count
    ];
}
	
	//echo '<pre>Testing';print_r($dataArray);die;
$data = [
	"channel_user_name" => $channel_user_name,
	"channel_password" => $channel_password,
    "hotel_id" => $id_hotel_booking_engine,
    "room_type_id" => $ids_room_booking_engine,
    "dates" => $dataArray
];
//echo '<pre>';print_r($data);die;
$data = json_encode($data);
	
	  $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,sourcefrom,date_created,id_channel ,ip_address,created_by,action_by,start_date,end_date) VALUES('".$id_hotel_booking_engine."','".$data."','inventoryUpdateFunctions','".date('Y-m-d H:i:s')."','1','".ipCheck()."','".$_SESSION['userId']."','2','".date('Y-m-d',($checkin_date))."','".date('Y-m-d',($checkout_date))."') ";
				   	
					mysqli_query($connNew,$reqSql);
// Initialize cURL session
$ch = curl_init("https://www.roomstatushub.in/crs/channel/PMSinventoryRequestApi.php");

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true); // POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $data); // POST body

// setting headers
/*curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "X-Authorization: Bearer YWRpQDk4NzYjc3RhYWhjb25uZWN0",    
    "Content-Type: application/json"
]);*/

// Execute cURL
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    echo 'cURL Error: ' . curl_error($ch);
}

// Close cURL
curl_close($ch);

// Output response
//echo '<pre>'.$response;
//echo '</pre>';
	
	//die;
	
	
	
	
	
	echo '==================>Start End';
	}	
	
	}?>