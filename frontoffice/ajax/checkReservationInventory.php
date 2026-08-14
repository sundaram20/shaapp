<?php
include_once("../../config/auto_loader.php");



//debugData($_REQUEST);

//header('Content-Type: application/json');

try {

    $id_hotel = (int) ($_POST['id_mst_hotels_new'] ?? 0);

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

            $dates      = $reservation['resdate'] ?? [];
            $roomTypes  = $reservation['room_type_id'] ?? [];

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

                /*
                 * Count requested rooms
                 */
                $requestedRooms[$roomTypeId][$date]++;
            }
        }
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 2
    | Get all inventory for requested date range
    |--------------------------------------------------------------------------
    */

    $allDates = [];

    foreach ($requestedRooms as $roomTypeId => $dates) {

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
    | Get minimum and maximum date
    |--------------------------------------------------------------------------
    */

    $dateObjects = [];

    foreach ($allDates as $date) {

        $dateObj = DateTime::createFromFormat(
            'd-m-Y',
            $date
        );

        if ($dateObj) {
            $dateObjects[] = $dateObj;
        }
    }

    $startDate = min($dateObjects)->format('Y-m-d');
    $endDate   = max($dateObjects)->format('Y-m-d');


    /*
    |--------------------------------------------------------------------------
    | STEP 3
    | Get inventory in ONE query
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

    mysqli_stmt_bind_param(
        $stmt,
        "iss",
        $id_hotel,
        $startDate,
        $endDate
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    /*
    |--------------------------------------------------------------------------
    | Store inventory
    |--------------------------------------------------------------------------
    */

    $inventoryData = [];

    while ($row = mysqli_fetch_assoc($result)) {

        $roomTypeId = (int) $row['id_mst_room_types'];

        $date = $row['allocation_date'];

        $inventoryData[$roomTypeId][$date] = [

            'confirmed' => (int) $row['confirmed'],

            'tentative' => (int) $row['tentative'],

            'blocked' => (int) $row['blocked_hotel']
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 4
    | Check availability
    |--------------------------------------------------------------------------
    */

    $availability = [];

    $overallAvailable = true;

    foreach ($requestedRooms as $roomTypeId => $dates) {

        /*
         * Total inventory assigned to this room type
         */
		$allowOverbooking = selectColumn(
    TBL_ASSIGN_HOTEL_ROOM,
    'allow_overbooking',
    "WHERE `id_mst_hotels` = '" . $id_hotel . "'
     AND `id_mst_room_types` = '" . (int)$roomTypeId . "'"
);


if ($allowOverbooking == '1') {


        $inventory = selectColumn(
            TBL_ASSIGN_HOTEL_ROOM,
            'inventory',
            "WHERE `id_mst_hotels` = '" . $id_hotel . "'
             AND `id_mst_room_types` = '" . (int)$roomTypeId . "' and allow_overbooking='1'"
        );

        $inventory = (int) $inventory;


        foreach ($dates as $date => $requestedQty) {

            $dateObj = DateTime::createFromFormat(
                'd-m-Y',
                $date
            );

            $sqlDate = $dateObj->format('Y-m-d');


            /*
             * If inventory record doesn't exist
             */
            if (
                !isset(
                    $inventoryData[$roomTypeId][$sqlDate]
                )
            ) {

                $available = 0;

                $confirmed = 0;
                $tentative = 0;
                $blocked = 0;

            } else {

                $inv = $inventoryData[$roomTypeId][$sqlDate];

                $confirmed = $inv['confirmed'];

                $tentative = $inv['tentative'];

                $blocked = $inv['blocked'];


                /*
                 * YOUR ORIGINAL CALCULATION
                 */
                $available =
                    $inventory
                    - $confirmed
                    - $tentative
                    - $blocked;

                /*
                 * Don't return negative availability
                 */
                $available = max(0, $available);
            }


            /*
             * Compare requested vs available
             */
            $isAvailable = $available >= $requestedQty;


            if (!$isAvailable) {
                $overallAvailable = false;
            }


            /*
             * Result
             */
            $availability[] = [

                'room_type_id' => (int) $roomTypeId,

                'date' => $date,

                'requested' => (int) $requestedQty,

                'inventory' => (int) $inventory,

                'confirmed' => (int) $confirmed,

                'tentative' => (int) $tentative,

                'blocked' => (int) $blocked,

                'available' => (int) $available,

                'status' => $isAvailable
                    ? 'AVAILABLE'
                    : 'NOT AVAILABLE'
            ];
        }
	}
    }


    /*
    |--------------------------------------------------------------------------
    | STEP 5
    | Return result
    |--------------------------------------------------------------------------
    */

    echo json_encode([

        'status' => $overallAvailable ? '1' : '0',

        'message' => $overallAvailable
            ? 'All rooms are available.'
            : 'Please check the availability. Overbooking is not Allowed.',

        'availability' => $availability

    ]);

//'Some rooms are unavailable. Please check the availability.',
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