<?php
include_once("../../config/auto_loader.php");

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