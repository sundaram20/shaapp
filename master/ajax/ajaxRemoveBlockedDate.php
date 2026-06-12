<?php include_once("../../config/auto_loader.php");

	 include_once("../functions/updateInventoryCRS.php");

$daterange = trim($_POST['daterange']);
$cleanRange = str_replace(['+-+', '+'], [' - ', ' '], $daterange);

list($start, $end) = explode(' - ', $cleanRange);

$start_date = DateTime::createFromFormat('d/m/Y', trim($start))->format('Y-m-d');
$end_date   = DateTime::createFromFormat('d/m/Y', trim($end))->format('Y-m-d');
//echo "Start: $start_date | End: $end_date";

echo updateInventoryManageBlock($_POST['id_mst_room_types'],$_POST['id_mst_hotel'],$start_date,$end_date,$_POST['id']);

function updateInventoryManageBlock($id_mst_room_types, $id_mst_hotels,$start_date,$end_date,$editid_room) {
    global $connNew;

    echo $query = "SELECT * FROM `".TBL_ROOMNO."` 
              WHERE id_mst_room_types = '$id_mst_room_types' 
              AND id_mst_hotels = '$id_mst_hotels' 
              AND status='1' 
              AND blocked_room_dates != '' ";

    $resSQL = mysqli_query($connNew, $query);
$arrayOfDates = [];
    while ($Record = mysqli_fetch_object($resSQL)) {
        $roomId = $Record->id;
        $record1 = explode(',',$Record->blocked_room_dates);
print_r($record1);
		
	 foreach ($record1 as $selectedDateRange) {
    // First explode by '-'
     $dates = explode('-', $selectedDateRange);

    // Clean spaces (important)
    $startDate = trim($dates[0]);
    $endDate = trim($dates[1]);

    // Convert to timestamps
    $startTimestamp = strtotime(str_replace('/', '-', $startDate)); // Convert 01/05/2025 to 01-05-2025 for strtotime
    $endTimestamp = strtotime(str_replace('/', '-', $endDate));

    if ($startTimestamp && $endTimestamp) {
        // Loop from start to end
        for ($current = $startTimestamp; $current <= $endTimestamp; $current = strtotime('+1 day', $current)) {
            $currentDate = date('Y-m-d', $current);
            
            // Check if date already exists
            if (isset($arrayOfDates[$currentDate])) {
                $arrayOfDates[$currentDate] += 1; // add 1 if date exists
            } else {
                $arrayOfDates[$currentDate] = 1; // first time add 1
            }
        }
    }
}
		
       
    }
	echo '<pre>';print_r($arrayOfDates);
	/*$query = "SELECT * FROM `".TBL_ROOMNO."` 
              WHERE id_mst_room_types = '$id_mst_room_types' 
              AND id_mst_hotels = '$id_mst_hotels' 
              AND status='1' 
              AND blocked_room_dates != '' and management_block='Yes' and id='$editid_room'";

    $resSQL = mysqli_query($connNew, $query);
	$arrayOfDates = [];
    while ($Record = mysqli_fetch_object($resSQL)) {
     $roomId = $Record->id;
    $blockedRanges = explode(',', $Record->blocked_room_dates);
    $newRanges = [];

    foreach ($blockedRanges as $range) {
        list($blockStart, $blockEnd) = explode(' - ', $range);

        $blockStartYMD = DateTime::createFromFormat('d/m/Y', trim($blockStart))->format('Y-m-d');
        $blockEndYMD   = DateTime::createFromFormat('d/m/Y', trim($blockEnd))->format('Y-m-d');

        // If this block range matches the current selected range, skip it
        if ($blockStartYMD === $start_date && $blockEndYMD === $end_date) {
            continue; // Skip this matched range
        }

        // If not matched, keep it
        $newRanges[] = trim($range);
    }

    // Final cleaned blocked_room_dates string
    $finalBlockedDates = implode(',', $newRanges);

    //echo "Updated blocked_room_dates for room $roomId: $finalBlockedDates";
	 $queryUpdate1 = "UPDATE `".TBL_ROOMNO."`  
                        SET blocked_room_dates = '$finalBlockedDates' 
                        WHERE id = '$editid_room' 
                       ";

        // Execute the query
       // mysqli_query($connNew, $queryUpdate1);
	}*/
	
	
	
	
// Loop and adjust values
foreach ($arrayOfDates as $date => $count) {
    if ($date >= $start_date && $date <= $end_date) {
        if ($count > 1) {
            $arrayOfDates[$date] = $count - 1;
        } elseif ($count == 1) {
           $arrayOfDates[$date] = 0;
        }
    }
}

// Debug result
//echo "<pre>";print_r($arrayOfDates);echo "</pre>";
	
	 // Now, update the blocked_hotel field based on the array of dates
    foreach ($arrayOfDates as $date => $count) {
        // Prepare the query to update blocked hotel for each date
        $queryUpdate12 = "UPDATE fo_inventory 
                        SET blocked_hotel = '$count' 
                        WHERE id_mst_room_types = '$id_mst_room_types' 
                        AND id_mst_hotels = '$id_mst_hotels' 
                        AND allocation_date = STR_TO_DATE('$date', '%Y-%m-%d')";

        // Execute the query
        mysqli_query($connNew, $queryUpdate12);
    }
	
	updateInventoryCRS($connNew,$arrayOfDates,$id_mst_room_types,$id_mst_hotels);
	
	//die;
	//======================
	$query = "SELECT * FROM `".TBL_ROOMNO."` 
              WHERE id_mst_room_types = '$id_mst_room_types' 
              AND id_mst_hotels = '$id_mst_hotels' 
              AND status='1' 
              AND blocked_room_dates != '' and management_block='Yes' and id='$editid_room'";

    $resSQL = mysqli_query($connNew, $query);
	$arrayOfDates = [];
    while ($Record = mysqli_fetch_object($resSQL)) {
     $roomId = $Record->id;
    $blockedRanges = explode(',', $Record->blocked_room_dates);
    $newRanges = [];

    foreach ($blockedRanges as $range) {
        list($blockStart, $blockEnd) = explode(' - ', $range);

        $blockStartYMD = DateTime::createFromFormat('d/m/Y', trim($blockStart))->format('Y-m-d');
        $blockEndYMD   = DateTime::createFromFormat('d/m/Y', trim($blockEnd))->format('Y-m-d');

        // If this block range matches the current selected range, skip it
        if ($blockStartYMD === $start_date && $blockEndYMD === $end_date) {
            continue; // Skip this matched range
        }

        // If not matched, keep it
        $newRanges[] = trim($range);
    }

    // Final cleaned blocked_room_dates string
    $finalBlockedDates = implode(',', $newRanges);

    //echo "Updated blocked_room_dates for room $roomId: $finalBlockedDates";
	 $queryUpdate1 = "UPDATE `".TBL_ROOMNO."`  
                        SET blocked_room_dates = '$finalBlockedDates' 
                        WHERE id = '$editid_room' 
                       ";

        // Execute the query
        mysqli_query($connNew, $queryUpdate1);
	}
	
	
}


?>