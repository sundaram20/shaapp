<?php

include_once("../../config/auto_loader.php");

header('Content-Type: application/json');


/*
|--------------------------------------------------------------------------
| INPUT
|--------------------------------------------------------------------------
*/

$search     = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

$selectedId = isset($_GET['id'])
    ? trim($_GET['id'])
    : '';


/*
|--------------------------------------------------------------------------
| SHOP
|--------------------------------------------------------------------------
*/

$shopId = isset($_SESSION['shop'])
    ? (int)$_SESSION['shop']
    : 0;


if ($shopId <= 0) {

    echo json_encode([]);

    exit;
}


/*
|--------------------------------------------------------------------------
| BASE WHERE
|--------------------------------------------------------------------------
*/

$where = "
    status = '1'
    AND id_shop = '".$shopId."'
";


$data = [];

$addedIds = [];


/*
|--------------------------------------------------------------------------
| FUNCTION: FORMAT GUEST
|--------------------------------------------------------------------------
*/

function formatGuest($row)
{
    $name =
        trim(
            $row['first_name'].' '.$row['last_name']
        );

    return
        $row['guest_reg_no']
        .' - '
        .$name
        .' - '
        .$row['primary_mobile']
        .' - '
        .$row['email']
        .' - '
        .$row['city'];
}


/*
|--------------------------------------------------------------------------
| QUERY 1
| SELECTED GUEST
|
| This is important when the reservation guest is not
| available in the latest 20 records.
|--------------------------------------------------------------------------
*/

if ($selectedId !== '') {

    $selectedId = (int)$selectedId;


    if ($selectedId > 0) {

        $sqlSelected = "
            SELECT
                id,
                guest_reg_no,
                first_name,
                last_name,
                email,
                city,
                primary_mobile
            FROM ".TBL_GUEST."
            WHERE
                ".$where."
                AND id = '".$selectedId."'
            LIMIT 1
        ";


        $resSelected =
            mysqli_query(
                $connNew,
                $sqlSelected
            );


        if ($resSelected) {

            if (
                $row =
                mysqli_fetch_assoc(
                    $resSelected
                )
            ) {

                $addedIds[] =
                    (string)$row['id'];


                $data[] = [

                    "id" =>
                        $row['id'],

                    "text" =>
                        formatGuest($row)

                ];

            }

        }

    }

}


/*
|--------------------------------------------------------------------------
| QUERY 2
| SEARCH / DEFAULT GUESTS
|--------------------------------------------------------------------------
*/

$sql = "
    SELECT
        id,
        guest_reg_no,
        first_name,
        last_name,
        email,
        city,
        primary_mobile
    FROM ".TBL_GUEST."
    WHERE ".$where."
";


/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

if ($search !== '') {

    /*
     * Escape search value
     */
    $searchEscaped =
        mysqli_real_escape_string(
            $connNew,
            $search
        );


    $sql .= "
        AND (
            first_name LIKE '%".$searchEscaped."%'
            OR last_name LIKE '%".$searchEscaped."%'
            OR email LIKE '%".$searchEscaped."%'
            OR guest_reg_no LIKE '%".$searchEscaped."%'
            OR primary_mobile LIKE '%".$searchEscaped."%'
            OR city LIKE '%".$searchEscaped."%'
        )
    ";

}


/*
|--------------------------------------------------------------------------
| ORDER + LIMIT
|--------------------------------------------------------------------------
*/

$sql .= "
    ORDER BY first_name ASC
    LIMIT 50
";


/*
|--------------------------------------------------------------------------
| EXECUTE
|--------------------------------------------------------------------------
*/

$res =
    mysqli_query(
        $connNew,
        $sql
    );


/*
|--------------------------------------------------------------------------
| MERGE RESULTS
|--------------------------------------------------------------------------
*/

if ($res) {

    while (
        $row =
        mysqli_fetch_assoc($res)
    ) {

        /*
         * Don't duplicate selected guest
         */
        if (
            in_array(
                (string)$row['id'],
                $addedIds,
                true
            )
        ) {

            continue;

        }


        $addedIds[] =
            (string)$row['id'];


        $data[] = [

            "id" =>
                $row['id'],

            "text" =>
                formatGuest($row)

        ];

    }

}


/*
|--------------------------------------------------------------------------
| OUTPUT
|--------------------------------------------------------------------------
*/

echo json_encode(
    $data,
    JSON_UNESCAPED_UNICODE
);

exit;

?>