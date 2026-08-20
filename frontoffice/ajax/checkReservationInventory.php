<?php
include_once("../../config/auto_loader.php");


$startDate=$_POST['checkin_extend_date'];
$startEnd=date ("Y-m-d", strtotime("+7 day", strtotime($_POST['checkout_extend_date'])));  ;
$id_hotel='1';

$data5=array();
$checkoutDate_upadate = date ("Y-m-d", strtotime($startEnd));
$startDateCheckAvailability = date ("Y-m-d", strtotime($startDate));

$sqlHot="SELECT id,name FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."' AND status='1' ";
				$resHot=mysqli_query($connNew,$sqlHot);
				$objHot=mysqli_fetch_object($resHot);	
//HOtel====================================================
//HOtel====================================================
$fromDate =$startDateCheckAvailability;
$toDate =$checkoutDate_upadate;

updateBlockedHotelsForShop($connNew, $_SESSION['shop'], $fromDate, $toDate);
function updateBlockedHotelsForShop($conn, $shopId, $fromDate, $toDate) {
    // get all hotels for this shop
    $sqlHot = "SELECT id, name 
               FROM ".TBL_HOTELS." 
               WHERE id_shop='".mysqli_real_escape_string($conn, $shopId)."' 
               AND status='1'";
    $resHot = mysqli_query($conn, $sqlHot);

    while ($objHot = mysqli_fetch_object($resHot)) {
        $hotelId = $objHot->id;

        // get all assigned room types for this hotel
        $sqlRoomType = "SELECT id_mst_room_types 
                        FROM `".TBL_ASSIGN_HOTEL_ROOM."` 
                        WHERE id_mst_hotels = '$hotelId' 
                        ORDER BY status_active_date DESC";
        $resRoomType = mysqli_query($conn, $sqlRoomType);

        while ($rowRoomType = mysqli_fetch_object($resRoomType)) {
             $roomTypeId = $rowRoomType->id_mst_room_types;

            // call processor for this (hotel, room type)
            processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate);
        }
    }

   // return true;
}
function processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate) {
    $arrayOfDates = [];
    $from = new DateTime($fromDate);
    $to   = new DateTime($toDate);

    // prepare all dates in range with 0 first
    for ($current = clone $from; $current <= $to; $current->modify('+1 day')) {
        $dateStr = $current->format('Y-m-d');
        $arrayOfDates[$dateStr] = 0;
    }

    // fetch blocked ranges
    $query = "SELECT blocked_room_dates 
              FROM `".TBL_ROOMNO."`
              WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
              AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
              AND status = '1'
              AND blocked_room_dates != ''";
    $resSQL = mysqli_query($conn, $query);

    while ($Record = mysqli_fetch_object($resSQL)) {
        $ranges = explode(',', $Record->blocked_room_dates);

        foreach ($ranges as $selectedDateRange) {
            $dates = explode(' - ', trim($selectedDateRange));
            if (count($dates) != 2) continue;

            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]));
            $end   = DateTime::createFromFormat('d/m/Y', trim($dates[1]));
            if (!$start || !$end) continue;

            for ($current = clone $start; $current <= $end; $current->modify('+1 day')) {
                if ($current >= $from && $current <= $to) {
                    $dateStr = $current->format('Y-m-d');
                    $arrayOfDates[$dateStr]++;  // increment block count
                }
            }
        }
    }

    // now update inventory (always write a value, even if 0)
    foreach ($arrayOfDates as $date => $count) {
         $sql = "UPDATE fo_inventory
                SET blocked_hotel = $count
                WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
                AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
                AND allocation_date = '$date'";
        mysqli_query($conn, $sql);
    }

    return true;
}



//=============================Load CheckAvailability	
while (strtotime($startDateCheckAvailability) < strtotime($checkoutDate_upadate)){	

$startDateCheckAvailability = date("Y-m-d",strtotime($startDateCheckAvailability));	


			
		 $AssRoomRoomType	=	" SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id_mst_hotels` = '".$objHot->id."' ORDER BY status_active_date DESC ";
			$resHotRoomType=mysqli_query($connNew,$AssRoomRoomType);	
			
		while($rowResRoomType = mysqli_fetch_object($resHotRoomType)){
			
			 $sqlRes="SELECT count(fo_reservations_details.room_quantity) as qty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status!='4' and fo_reservations_details.no_showoff='0'  and  fo_reservations_details.dated='".$startDateCheckAvailability."' 
 and fo_reservations_details.id_mst_room_types='".$rowResRoomType->id_mst_room_types."'
GROUP by fo_reservations_details.dated ,fo_reservations_details.id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";




$resRes = mysqli_query($connNew,$sqlRes);
			if(mysqli_num_rows($resRes)>0){
			while($rowRes = mysqli_fetch_object($resRes)){ 
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						$rownew = mysqli_fetch_object($resnew);
						
						//================================
					 $sqlResConfirm="SELECT count(fo_reservations_details.room_quantity) as Confirmqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='1' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";		
						$resnewConfirm = mysqli_query($connNew,$sqlResConfirm);	
							$rownewConfirm = mysqli_fetch_object($resnewConfirm);
							$Confirmqty	= $rownewConfirm->Confirmqty;
							$Confirmqty=$Confirmqty==''?'0':$Confirmqty;
	
 $sqlResTenditive="SELECT count(fo_reservations_details.room_quantity) as Tenditivemqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='2' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";			
						$resnewTenditive = mysqli_query($connNew,$sqlResTenditive);	
							$rownewTenditive = mysqli_fetch_object($resnewTenditive);
							$Tenditiveqty	= $rownewTenditive->Tenditivemqty;							
								$Tenditiveqty=$Tenditiveqty==''?'0':$Tenditiveqty;
								
								
								
								//==============================
						$sqlRoom=  "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels = '".$rowRes->id_mst_hotels."' and ahr.id_mst_room_types='".addslashes($rowRes->id_mst_room_types)."'" ;
						
						
						$resRoom = mysqli_query($connNew,$sqlRoom);
						$rowRoom = mysqli_fetch_object($resRoom);
						//if($rowRes->booking_status=='2'){
						
							
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$tentative =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`tentative`='".$Tenditiveqty."',`confirmed`='".$Confirmqty."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						//echo '<br><br>2==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						/*}else{
						
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$confirmed =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						echo '<br><br>1==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						}*/
					
						
						}
			}else{
				
				
					 $roomId=$rowResRoomType->id_mst_room_types;
				$hotelId=$id_hotel;		
				//echo "SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'";
				$sqlSum=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' and ahr.id_mst_room_types='".addslashes($roomId)."'");
		$rowResSum = mysqli_fetch_object($sqlSum);
		$totalRoom	= $rowResSum->totalRoom;
	
		$crs_available = $rowRoom->inventory - $rowResRoomType->qty ; 
							$confirmed =  $rowResRoomType->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$totalRoom."',`confirmed`='0',`tentative`='0' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowResRoomType->id_mst_room_types."' and allocation_date = '".$startDateCheckAvailability."' and   id_mst_hotels = '".$rowResRoomType->id_mst_hotels."'";
						//echo $insertGrid;
						  mysqli_query($connNew,$insertGrid);
		
				
			}
		
		
		
		
		
		
		
		}
		
		
		
		
			
				
	//echo '<br>===='.
	$startDateCheckAvailability = date ("Y-m-d", strtotime("+1 day", strtotime($startDateCheckAvailability)));	

			  			
  }		
/////////////////////////////////////////






try {

    $id_hotel = (int) ($_POST['id_mst_hotels_new'] ?? 0);
    $editId   = (int) ($_POST['editid'] ?? 0);

    $reservationData = $_POST['ReservationDataArray'] ?? [];

    if (!$id_hotel) {
        echo json_encode([
            'status' => '0',
            'message' => 'Hotel is required.'
        ]);
        exit;
    }

    if (empty($reservationData)) {
        echo json_encode([
            'status' => '0',
            'message' => 'Reservation data is missing.'
        ]);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 1
    | Build requested quantity by room type + date
    |--------------------------------------------------------------------------
    */

    $requestedRooms = [];

    foreach ($reservationData as $group) {

        if (!is_array($group)) {
            continue;
        }

        foreach ($group as $reservation) {

            if (!is_array($reservation)) {
                continue;
            }

            $dates     = $reservation['resdate'] ?? [];
            $roomTypes = $reservation['room_type_id'] ?? [];

            foreach ($dates as $index => $date) {

                $roomTypeId = $roomTypes[$index] ?? null;

                if (!$roomTypeId || !$date) {
                    continue;
                }

                if (!isset($requestedRooms[$roomTypeId])) {
                    $requestedRooms[$roomTypeId] = [];
                }

                if (!isset($requestedRooms[$roomTypeId][$date])) {
                    $requestedRooms[$roomTypeId][$date] = 0;
                }

                $requestedRooms[$roomTypeId][$date]++;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 2
    | Get all requested dates
    |--------------------------------------------------------------------------
    */

    $allDates = [];

    foreach ($requestedRooms as $dates) {
        foreach ($dates as $date => $qty) {
            $allDates[] = $date;
        }
    }

    if (empty($allDates)) {
        echo json_encode([
            'status' => '0',
            'message' => 'No room dates found.'
        ]);
        exit;
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 3
    | Convert dates
    |--------------------------------------------------------------------------
    */

    $dateObjects = [];

    foreach ($allDates as $date) {

        $dateObj = DateTime::createFromFormat('d-m-Y', $date);

        if ($dateObj) {
            $dateObjects[] = $dateObj;
        }
    }

    if (empty($dateObjects)) {
        echo json_encode([
            'status' => '0',
            'message' => 'Invalid reservation dates.'
        ]);
        exit;
    }

    $startDate = min($dateObjects)->format('Y-m-d');
    $endDate   = max($dateObjects)->format('Y-m-d');

    /*
    |--------------------------------------------------------------------------
    | STEP 4
    | Get inventory
    |--------------------------------------------------------------------------
    */

    $sql = "
        SELECT
            allocation_date,
            id_mst_room_types,
            confirmed,
            tentative,
            blocked_hotel
        FROM " . FO_INVENTORY . "
        WHERE id_mst_hotels = ?
          AND allocation_date BETWEEN ? AND ?
    ";

    $stmt = mysqli_prepare($connNew, $sql);

    if (!$stmt) {
        throw new Exception(
            'Unable to prepare inventory query: ' . mysqli_error($connNew)
        );
    }

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $id_hotel,
        $startDate,
        $endDate
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception(
            'Unable to execute inventory query: ' . mysqli_stmt_error($stmt)
        );
    }

    $result = mysqli_stmt_get_result($stmt);

    $inventoryData = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $roomTypeId = (int) $row['id_mst_room_types'];
        $date       = $row['allocation_date'];

        $inventoryData[$roomTypeId][$date] = [
            'confirmed' => (int) $row['confirmed'],
            'tentative' => (int) $row['tentative'],
            'blocked'   => (int) $row['blocked_hotel']
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 5
    | EDIT MODE
    |
    | Get rooms already assigned to this reservation.
    |
    | fo_reservations_details:
    |     room type
    |
    | fo_reservations:
    |     checkin / checkout
    |--------------------------------------------------------------------------
    */

    $existingRooms = [];

if ($editId > 0) {

    $sqlExisting = "
        SELECT
            d.id_mst_room_types,
            DATE(r.checkin) AS checkin,
            DATE(r.checkout) AS checkout
        FROM fo_reservations_details d
        INNER JOIN fo_reservations r
            ON r.id = d.id_fo_reservations
        WHERE d.id_fo_reservations = ?
    ";

    $stmtExisting = mysqli_prepare($connNew, $sqlExisting);

    if (!$stmtExisting) {
        throw new Exception(
            'Unable to prepare existing reservation query: '
            . mysqli_error($connNew)
        );
    }

    mysqli_stmt_bind_param(
        $stmtExisting,
        "i",
        $editId
    );

    if (!mysqli_stmt_execute($stmtExisting)) {
        throw new Exception(
            'Unable to execute existing reservation query: '
            . mysqli_stmt_error($stmtExisting)
        );
    }

    $existingResult = mysqli_stmt_get_result($stmtExisting);

    while ($row = mysqli_fetch_assoc($existingResult)) {

        $roomTypeId = (int) $row['id_mst_room_types'];

        if (empty($row['checkin']) || empty($row['checkout'])) {
            continue;
        }

        $existingCheckin  = new DateTime($row['checkin']);
        $existingCheckout = new DateTime($row['checkout']);

        /*
         * Example:
         *
         * Checkin  = 15-08-2026
         * Checkout = 16-08-2026
         *
         * Consumed inventory:
         * 15-08-2026
         *
         * Checkout date is NOT consumed.
         */
        while ($existingCheckin < $existingCheckout) {

            $existingDate = $existingCheckin->format('Y-m-d');

            if (!isset($existingRooms[$roomTypeId])) {
                $existingRooms[$roomTypeId] = [];
            }

            if (!isset($existingRooms[$roomTypeId][$existingDate])) {
                $existingRooms[$roomTypeId][$existingDate] = 0;
            }

            $existingRooms[$roomTypeId][$existingDate]++;

            $existingCheckin->modify('+1 day');
        }
    }

    mysqli_stmt_close($stmtExisting);
}

    /*
    |--------------------------------------------------------------------------
    | STEP 6
    | Check availability
    |--------------------------------------------------------------------------
    */

    $availability     = [];
    $overallAvailable = true;

    foreach ($requestedRooms as $roomTypeId => $dates) {

        /*
         * Check overbooking configuration
         */

        $allowOverbooking = selectColumn(
            TBL_ASSIGN_HOTEL_ROOM,
            'allow_overbooking',
            "WHERE `id_mst_hotels` = '" . $id_hotel . "'
             AND `id_mst_room_types` = '" . (int) $roomTypeId . "'"
        );

        /*
         * Existing code checks only allow_overbooking = 1.
         * Keep the same behavior.
         */

        if ($allowOverbooking != '1') {
            continue;
        }

        /*
         * Total inventory
         */

        $inventory = selectColumn(
            TBL_ASSIGN_HOTEL_ROOM,
            'inventory',
            "WHERE `id_mst_hotels` = '" . $id_hotel . "'
             AND `id_mst_room_types` = '" . (int) $roomTypeId . "'
             AND allow_overbooking = '1'"
        );

        $inventory = (int) $inventory;

        foreach ($dates as $date => $requestedQty) {

            $dateObj = DateTime::createFromFormat(
                'd-m-Y',
                $date
            );

            if (!$dateObj) {
                $overallAvailable = false;

                $availability[] = [
                    'room_type_id' => (int) $roomTypeId,
                    'date'         => $date,
                    'requested'    => (int) $requestedQty,
                    'status'       => 'NOT AVAILABLE'
                ];

                continue;
            }

            $sqlDate = $dateObj->format('Y-m-d');

            /*
             * Current inventory usage
             */

            if (isset($inventoryData[$roomTypeId][$sqlDate])) {

                $inv = $inventoryData[$roomTypeId][$sqlDate];

                $confirmed = $inv['confirmed'];
                $tentative = $inv['tentative'];
                $blocked   = $inv['blocked'];

            } else {

                $confirmed = 0;
                $tentative = 0;
                $blocked   = 0;
            }

            /*
             * ---------------------------------------------------------------
             * IMPORTANT FOR EDIT
             * ---------------------------------------------------------------
             *
             * If reservation 231 already has 2 rooms on this date:
             *
             * inventory = 10
             * confirmed = 8
             * existing reservation = 2
             *
             * Normal availability:
             *
             * 10 - 8 = 2
             *
             * But those 2 confirmed rooms include reservation 231.
             *
             * So add the existing reservation rooms back.
             */

            $existingQty = 0;

            if (
                isset($existingRooms[$roomTypeId][$sqlDate])
            ) {
                $existingQty =
                    (int) $existingRooms[$roomTypeId][$sqlDate];
            }

            /*
             * Calculate availability after removing
             * this reservation's old allocation.
             */

            $available =
                $inventory
                - $confirmed
                - $tentative
                - $blocked
                + $existingQty;

            $available = max(0, $available);

            /*
             * Compare new requested quantity.
             */

            $isAvailable = $available >= $requestedQty;

            if (!$isAvailable) {
                $overallAvailable = false;
            }

            $availability[] = [

                'room_type_id' => (int) $roomTypeId,

                'date' => $date,

                'requested' => (int) $requestedQty,

                'inventory' => (int) $inventory,

                'confirmed' => (int) $confirmed,

                'tentative' => (int) $tentative,

                'blocked' => (int) $blocked,

                'existing' => (int) $existingQty,

                'available' => (int) $available,

                'status' => $isAvailable
                    ? 'AVAILABLE'
                    : 'NOT AVAILABLE'
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | STEP 7
    | Return result
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        'status' => $overallAvailable ? '1' : '0',

        'message' => $overallAvailable
            ? 'All rooms are available.'
            : 'Please check the availability. Overbooking is not Allowed.',

        'editid' => $editId,

        'availability' => $availability

    ]);

    exit;

} catch (Throwable $e) {

    echo json_encode([

        'status' => '0',

        'message' => 'Inventory check failed.',

        'error' => $e->getMessage()

    ]);

    exit;
}
?>