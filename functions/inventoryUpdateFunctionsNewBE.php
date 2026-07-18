<?php 

	function updateOTA_JSON($id_hotel, $checkin_date, $checkout_date) {
    global $connNew;
	$be_inventory_source = '3';
    $checkout_date = date('Y-m-d', strtotime($checkout_date . ' +2 day'));
    $checkin_dateBE = strtotime($checkin_date);
    $checkout_dateBE = strtotime($checkout_date);

 
	//Booking Engine OTA Update start=============
    $channel_id = selectColumn('fs_channel_manager', 'id', 'WHERE name="Booking Engine" AND status=1');
	$auto_sync_inv = selectColumn(TBL_HOTEL_MAPPING, 'auto_sync_inv', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");
	if($channel_id>0 && $auto_sync_inv=='1'){
		
	$channel_mapping_code = selectColumn('fs_channel_manager', 'channel_mapping_code', 'WHERE name="Booking Engine" AND status=1');
   	
		
    $BENew_id_hotel_booking_engine = selectColumn(TBL_HOTEL_MAPPING, 'booking_engine_id', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");

    $id_hotel_mapping = selectColumn(TBL_HOTEL_MAPPING, 'id', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");
    if (!$id_hotel_mapping) return; // nothing to update

    // Fetch room mappings
    $ids_room = [];
    $ids_room_booking_engine = [];

    $resRoomType = mysqli_query($connNew, "SELECT room_id, booking_engine_id FROM fs_room_mapping WHERE hotel_mapping_id='$id_hotel_mapping' AND status=1 ORDER BY id");
    while ($row = mysqli_fetch_assoc($resRoomType)) {
        $ids_room[] = $row['room_id'];
        $ids_room_booking_engine[] = $row['booking_engine_id'];
    }

    $payloads = [];

    foreach ($ids_room_booking_engine as $index => $id_room) {
        $startDate = $checkin_dateBE;
        $inventory = [];

        while ($startDate <= $checkout_dateBE) {
            // Determine available inventory
            if ($be_inventory_source == '4') { // Lowest
                $pms_available = selectColumn('fo_inventory', 'pms_available', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
                $online_allocation = selectColumn('fo_inventory', 'online_allocation', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
                $online_avail = min((int)$pms_available, (int)$online_allocation);
            } elseif ($be_inventory_source == '2') { // PMS
                $online_avail = selectColumn('fo_inventory', 'pms_available', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
            } else { // CRS
                $online_avail = selectColumn('fo_inventory', 'online_allocation', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
            }
            //$blocked_hotel=selectColumn('fo_inventory','blocked_hotel','WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"');
						//$online_avail=$online_avail-$blocked_hotel;
            $invStatus = selectColumn('fo_inventory', 'status', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");

            $online_avail = max(0, (int)$online_avail);
            $stopSell = ($invStatus == 0);

            $inventory[] = [
                "startDate" => date('Y-m-d', $startDate),
                "endDate"   => date('Y-m-d', $startDate),
                "free"      => $online_avail,
                "stopsell"  => $stopSell
            ];

            $startDate = strtotime('+1 day', $startDate);
        }

        $payloads[] = [
            "auth" => [
                "key" => "cG1zYmVjb25uZWN0QDk4NzY="
            ],
            "shop_code" => (string)$channel_mapping_code,
            "data" => [
                "propertyId" => (string)$BENew_id_hotel_booking_engine,
                "roomType"   => (string)$id_room,
                "inventory"  => $inventory
            ]
        ];
    }
 
    // Send JSON payloads
    foreach ($payloads as $dataToSend) {
        $jsonData = json_encode($dataToSend, JSON_PRETTY_PRINT);
		
		
		$reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,sourcefrom,date_created,id_channel ,ip_address,created_by,action_by,start_date,end_date) VALUES('".$id_hotel."','".$jsonData."','inventoryUpdateFunctions','".date('Y-m-d H:i:s')."','".$channel_id."','".ipCheck()."','".$_SESSION['userId']."','2','".date('Y-m-d',($checkin_dateBE))."','".date('Y-m-d',strtotime($checkout_date))."') ";
				   	
					mysqli_query($connNew,$reqSql);
                    $lastInsertID   =   mysqli_insert_id($connNew);

        $ch = curl_init('https://api.roomstatushub.in/api/inventoryUpdate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $response = curl_exec($ch);
	 $sqlResp="UPDATE api_inv_request 
SET response = '".$response."', 
    response_date = '".date("Y-m-d H:i:s")."' 
WHERE id = '".$lastInsertID."'";
					mysqli_query($connNew,$sqlResp);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
    }
	}
		//Booking Engine OTA Update End=============
}

?>