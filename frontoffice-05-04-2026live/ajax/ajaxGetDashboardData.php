<?php include_once("../../config/auto_loader.php");


$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$yesterday = date('Y-m-d',strtotime('-1 day',strtotime($today)));
$last_week = date('Y-m-d',strtotime('-1 day',strtotime($yesterday)));
$till_30_days = date('Y-m-d',strtotime('-30 day',strtotime($today)));

$start = new DateTime($till_30_days);
$end = new DateTime($today);
$date_range_text = '';
$date_range = [];

while ($start <= $end) {
    $date_range_text .= "'".$start->format('Y-m-d')."'";
    $date_range[] = $start->format("Y-m-d");
    $start->modify('+1 day');
    if ($start <= $end) {
        $date_range_text .= ",";
    }
}

$results = [];
$hotel_room_query = mysqli_query($connNew, "SELECT id_mst_room_types FROM mst_assign_hotel_rooms GROUP BY id_mst_room_types");
$hotel_rooms = [];

while ($row = mysqli_fetch_object($hotel_room_query)) {
    $hotel_rooms[] = $row->id_mst_room_types;
}

$room_ids_string = implode(",", $hotel_rooms);
$sql = "SELECT * FROM " . TBL_ROOMNO . " WHERE id_mst_room_types IN (" . $room_ids_string . ") and status = 1 GROUP BY room_no";
$room_no_allocations = mysqli_query($connNew, $sql);

// Check for query execution errors
if (!$room_no_allocations) {
    die("Error executing query while room no allocations: " . mysqli_error($connNew));
}

$total_rooms = mysqli_num_rows($room_no_allocations);

$occupied_room_query = mysqli_query($connNew,"SELECT DISTINCT resdetails.id as resdetail_id,room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkout_time as reservation_checkout_time, resdetails.checkin_time as reservation_checkin_time, resdetails.id_fo_rate_plan 

FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."'");
$arrival_pending_query = "SELECT * FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id 
where ".FO_RESERVATIONS_DETAILS.".checkin_status = '0' AND ".FO_RESERVATIONS_DETAILS.".no_showoff = '0' and `".FO_RESERVATIONS."`.booking_status IN ('1','2')
AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated) = '".$today."' AND DATE(`".FO_RESERVATIONS."`.checkin) = '".$today."'  order by `".FO_RESERVATIONS."`.id desc";
$arrival_pending_result = mysqli_query($connNew, $arrival_pending_query);
$arrival_pendings = mysqli_num_rows($arrival_pending_result);
$arrival_pending_result_array=array();
//===============arrival_pending_result



$newDate	=	date('Y-m-d',strtotime($today));
	$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";



$arrival_pending_Sql	= "SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* 
  FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  
  where   
    ".FO_RESERVATIONS_DETAILS.".checkin_status ='0' AND ".FO_RESERVATIONS_DETAILS.".no_showoff ='0'    and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  ".$searchDocumentType." ".$searchDocumentTypeDetails."
 
  group by  order_by_room ,id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";

$arrival_pending_result1 = mysqli_query($connNew, $arrival_pending_Sql);


if (mysqli_num_rows($arrival_pending_result1) > 0) {
    while ($arrival_pending_result_row = mysqli_fetch_object($arrival_pending_result1)) {
		//echo '<br>11';
		
		//debugData($arrival_pending_result_row);
        $GuestName = selectColumn("mst_guest",'first_name'," WHERE `id` = '".$arrival_pending_result_row->id_mst_guest."'");
        $lastName = selectColumn("mst_guest",'last_name'," WHERE `id` = '".$arrival_pending_result_row->id_mst_guest."'");
        $id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$arrival_pending_result_row->id_mst_guest."'");
        $Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");

        $arrival_pending_result_date = date('Y-m-d', strtotime($arrival_pending_result_row->checkin_date));
        $checkout_date = date('Y-m-d', strtotime($arrival_pending_result_row->checkout_date));
        $reservation_checkout = date('Y-m-d', strtotime($arrival_pending_result_row->reservation_checkout));

        //if ($arrival_pending_result_date == $today) {
            $plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$arrival_pending_result_row->id_fo_rate_plan."'");
            $arrival_pending_result_array[$arrival_pending_result_row->order_by_room.'-'.$arrival_pending_result_row->id_fo_reservations] = [
                'guest_name' => $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'',
                'room_no' => $arrival_pending_result_row->room_no==0?'-':$arrival_pending_result_row->room_no,
                'checkin_time' => date('H:i',strtotime($arrival_pending_result_date)),
                'child_per_room' => $arrival_pending_result_row->child_below_5_year + $arrival_pending_result_row->child_above_5_year,
                'adults_per_room' => $arrival_pending_result_row->adults_per_room,
                'plan_name' => $plan_name,
            ];
       // }

       
        
       
        
    }
}
//===============arrival_pending_result
//debugData($arrival_pending_result_array);

$checkin_results = [];
$checkout_results = [];
$checkout_pendings = 0;
$occupied_rooms = 0;
$guest_count = [];
if (mysqli_num_rows($occupied_room_query) > 0) {
    while ($checkin = mysqli_fetch_object($occupied_room_query)) {
        $GuestName = selectColumn("mst_guest",'first_name'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $lastName = selectColumn("mst_guest",'last_name'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$checkin->id_mst_guest."'");
        $Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'");

        $checkin_date = date('Y-m-d', strtotime($checkin->checkin_date));
        $checkout_date = date('Y-m-d', strtotime($checkin->checkout_date));
        $reservation_checkout = date('Y-m-d', strtotime($checkin->reservation_checkout));

        // echo $checkin_date == $today;
        // echo "<br/>";
        if ($checkin_date == $today) {
            // echo $checkin->id;
            // echo "<br/>";
            $plan_name = selectColumn("fo_rate_plan",'name'," WHERE `id` = '".$checkin->id_fo_rate_plan."'");
            $checkin_results[$checkin->resdetail_id] = [
                'guest_name' => $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'',
                'room_no' => $checkin->room_no,
                'checkin_time' => $checkin->reservation_checkin_time,
                'child_per_room' => $checkin->child_below_5_year + $checkin->child_above_5_year,
                'adults_per_room' => $checkin->adults_per_room,
                'plan_name' => $plan_name,
            ];
        }

        if ($checkout_date == $today) {
            $checkout_results[$checkin->resdetail_id] = [
                'guest_name' => $GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'',
                'room_no' => $checkin->room_no,
                'checkout_time' => $checkin->reservation_checkout_time,
            ];
        }
        
        if ($reservation_checkout == $today && $checkin->checkout_date == '') {
            $checkout_pendings++;
        }
        if ($checkin->no_showoff == '0' && $checkin->checkout_date == '') {
            $occupied_rooms += 1;
            $guest_count[$checkin->resdetail_id] = [
                'adults_per_room' => $checkin->adults_per_room,
                'child_below_5_year' => $checkin->child_below_5_year,
                'child_above_5_year' => $checkin->child_above_5_year,
            ];
        }
    }
}
// echo "<pre>";
// echo $occupied_rooms;
// echo "<br/>";
// print_r($checkin_results);
// echo "<br/>";
// echo(count($checkin_results));
// exit;

$vacant_rooms = $total_rooms - $occupied_rooms;

//Yesterday============================================

$yesterday_room_night_query_new = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` INNER JOIN fo_reservations as reservation ON fo_reservations_details.id_fo_reservations=reservation.id INNER JOIN mst_room_no_allocation ON fo_reservations_details.id_mst_room_no_allocation=mst_room_no_allocation.id WHERE `dated` = '".$yesterday."' AND `no_showoff` = '0' ");
$yesterday_room_nights_new = mysqli_num_rows($yesterday_room_night_query_new);



$last_week_room_night_query_new = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` INNER JOIN fo_reservations as reservation ON fo_reservations_details.id_fo_reservations=reservation.id INNER JOIN mst_room_no_allocation ON fo_reservations_details.id_mst_room_no_allocation=mst_room_no_allocation.id WHERE `dated` = '".$last_week."' AND `no_showoff` = '0' ");
$last_week_room_nights_new = mysqli_num_rows($last_week_room_night_query_new);

$yesterday_revenue_query_new = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as price, SUM(tax_per_day_per_room) as tax FROM `fo_reservations_details` WHERE `dated` = '".$yesterday."' AND `no_showoff` = '0' ");
$yesterday_revenues_new = mysqli_fetch_assoc($yesterday_revenue_query_new);
$yesterday_price_new = $yesterday_revenues_new['price'] ?? 0;
$yesterday_tax_new = $yesterday_revenues_new['tax'] ?? 0;

$last_week_revenue_query_new = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as price, SUM(tax_per_day_per_room) as tax FROM `fo_reservations_details` WHERE `dated` = '".$last_week."' AND `no_showoff` = '0' ");
$last_week_revenues_new = mysqli_fetch_assoc($last_week_revenue_query_new);
$last_week_price_new = $last_week_revenues_new['price'] ?? 0;
$last_week_tax_new = $last_week_revenues_new['tax'] ?? 0;

$total_yesterday_revenue_new = $yesterday_price_new + $yesterday_tax_new;
$total_last_week_revenue_new = $last_week_price_new + $last_week_tax_new;

$last_week_arr_new = 0;
if ($total_last_week_revenue_new != 0) {
    $last_week_arr_new = ($total_last_week_revenue_new / $last_week_room_nights_new);
}

$yesterday_arr_new = 0;
if ($total_yesterday_revenue_new != 0) {
    $yesterday_arr_new = $total_yesterday_revenue_new / $yesterday_room_nights_new;
}

$yesterday_total_receipt_query_new = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."'");
$yesterday_total_receipts_new = mysqli_fetch_assoc($yesterday_total_receipt_query_new);
$yesterday_total_receipt_amount_new = $yesterday_total_receipts_new['price'] ?? 0;

$yesterday_cash_receipt_query_new = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."' and payment_mode = 'CASH'");
$yesterday_cash_receipts_new = mysqli_fetch_assoc($yesterday_cash_receipt_query_new);
$yesterday_cash_receipt_amount_new = $yesterday_cash_receipts_new['price'] ?? 0;

$yesterday_other_receipt_query_new = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."' and payment_mode != 'CASH'");
$yesterday_other_receipts_new = mysqli_fetch_assoc($yesterday_other_receipt_query_new);
$yesterday_other_receipt_amount_new = $yesterday_other_receipts_new['price'] ?? 0;







//YesterDay===============================================

$yesterday_room_night_query = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` INNER JOIN fo_reservations as reservation ON fo_reservations_details.id_fo_reservations=reservation.id INNER JOIN mst_room_no_allocation ON fo_reservations_details.id_mst_room_no_allocation=mst_room_no_allocation.id WHERE `dated` = '".$yesterday."' AND `no_showoff` = '0' AND checkout_date IS NULL");
$yesterday_room_nights = mysqli_num_rows($yesterday_room_night_query);

$last_week_room_night_query = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` INNER JOIN fo_reservations as reservation ON fo_reservations_details.id_fo_reservations=reservation.id INNER JOIN mst_room_no_allocation ON fo_reservations_details.id_mst_room_no_allocation=mst_room_no_allocation.id WHERE `dated` = '".$last_week."' AND `no_showoff` = '0' AND checkout_date IS NULL");
$last_week_room_nights = mysqli_num_rows($last_week_room_night_query);

$yesterday_revenue_query = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as price, SUM(tax_per_day_per_room) as tax FROM `fo_reservations_details` WHERE `dated` = '".$yesterday."' AND `no_showoff` = '0' AND checkout_date IS NULL");
$yesterday_revenues = mysqli_fetch_assoc($yesterday_revenue_query);
$yesterday_price = $yesterday_revenues['price'] ?? 0;
$yesterday_tax = $yesterday_revenues['tax'] ?? 0;

$last_week_revenue_query = mysqli_query($connNew, "SELECT SUM(tariff_price_per_day_per_room) as price, SUM(tax_per_day_per_room) as tax FROM `fo_reservations_details` WHERE `dated` = '".$last_week."' AND `no_showoff` = '0' AND checkout_date IS NULL");
$last_week_revenues = mysqli_fetch_assoc($last_week_revenue_query);
$last_week_price = $last_week_revenues['price'] ?? 0;
$last_week_tax = $last_week_revenues['tax'] ?? 0;

$total_yesterday_revenue = $yesterday_price + $yesterday_tax;
$total_last_week_revenue = $last_week_price + $last_week_tax;

$last_week_arr = 0;
if ($total_last_week_revenue != 0) {
    $last_week_arr = ($total_last_week_revenue / $last_week_room_nights);
}

$yesterday_arr = 0;
if ($total_yesterday_revenue != 0) {
    $yesterday_arr = $total_yesterday_revenue / $yesterday_room_nights;
}

$yesterday_total_receipt_query = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."'");
$yesterday_total_receipts = mysqli_fetch_assoc($yesterday_total_receipt_query);
$yesterday_total_receipt_amount = $yesterday_total_receipts['price'] ?? 0;

$yesterday_cash_receipt_query = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."' and payment_mode = 'CASH'");
$yesterday_cash_receipts = mysqli_fetch_assoc($yesterday_cash_receipt_query);
$yesterday_cash_receipt_amount = $yesterday_cash_receipts['price'] ?? 0;

$yesterday_other_receipt_query = mysqli_query($connNew, "SELECT SUM(amount) as price FROM `fo_receipt` WHERE `doc_date` = '".$yesterday."' and payment_mode != 'CASH'");
$yesterday_other_receipts = mysqli_fetch_assoc($yesterday_other_receipt_query);
$yesterday_other_receipt_amount = $yesterday_other_receipts['price'] ?? 0;

$inventry_query = mysqli_query($connNew, "SELECT sum(inventory) as inventory FROM mst_assign_hotel_rooms");
$inventry_results = mysqli_fetch_assoc($inventry_query);
$inventry_results = $inventry_results['inventory'] ?? 0;

$occupied_room_results = [];
foreach ($date_range as $key => $date) {
	
	 //$occupied_room_query_date_wise = mysqli_query($connNew,"SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated` = '".$date."' ");
	
	
    $occupied_room_query_date_wise = mysqli_query($connNew,"SELECT * 

FROM  fo_reservations_details as resdetails 

WHERE  resdetails.`dated`= '".$date."'  and resdetails.checkin_status='1' AND `no_showoff` = '0'
ORDER BY `resdetails`.`checkin_status` ASC");
	
	//and resdetails.`checkout_date` IS NULL
    $occupied_room_results_date_wise = mysqli_fetch_all($occupied_room_query_date_wise, MYSQLI_ASSOC);
  //debugData($occupied_room_results_date_wise);
	foreach ($occupied_room_results_date_wise as $item) {
        $dated = $item['dated'];
        if (!isset($occupied_room_results[$dated])) {
            $occupied_room_results[$dated] = 0;
        }
        $status = "Occuiped";
        if ($date == date('Y-m-d' ,strtotime($item['checkin_date']))) {
            $status = "Checkin/Occuiped";
        }
        if ($date == date('Y-m-d' ,strtotime($item['checkout_date']))) {
            $status =  "Checkout/Vacant";
        }
        if ($date == date('Y-m-d' ,strtotime($item['checkin_date'])) && $date == date('Y-m-d' ,strtotime($item['checkout_date']))) {
            $status =  "Checkin/Checkout";
        }
        if ($status == "Checkin/Occuiped" || $status == "Occuiped") {
           // $occupied_room_results[$dated]++;
        }
		if ($item['checkin_status'] == "1") {
            $occupied_room_results[$dated]++;
        }
    }
}

/*mysqli_query($connNew,"SELECT * 

FROM  fo_reservations_details as resdetails 

WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='2024-10-24' and checkin_status='1' and resdetails.id_mst_room_no_allocation>0");
echo $occpi=  count($occupied_room_results_date_wise);*/
//debugData($occupied_room_results);
$occupied_room_results_date_wise = $occupied_room_results;

$room_nights_results = [];
foreach ($date_range as $key => $date) {
    $room_night_query_date_wise = mysqli_query($connNew, "SELECT * FROM `fo_reservations_details` INNER JOIN fo_reservations as reservation ON fo_reservations_details.id_fo_reservations=reservation.id INNER JOIN mst_room_no_allocation ON fo_reservations_details.id_mst_room_no_allocation=mst_room_no_allocation.id WHERE `dated` = '".$date."' AND fo_reservations_details.`no_showoff` = '0' and fo_reservations_details.checkin_status='1'");
	
	
	//AND checkout_date IS NULL
    $room_night_results_date_wise = mysqli_fetch_all($room_night_query_date_wise, MYSQLI_ASSOC);
    foreach ($room_night_results_date_wise as $item) {
        $dated = $item['dated'];
        if (!isset($room_nights_results[$dated])) {
            $room_nights_results[$dated] = 0;
        }
        $status = "Occuiped";
        if ($date == date('Y-m-d' ,strtotime($item['checkin_date']))) {
            $status = "Checkin/Occuiped";
        }
        if ($date == date('Y-m-d' ,strtotime($item['checkout_date']))) {
            $status =  "Checkout/Vacant";
        }
        if ($date == date('Y-m-d' ,strtotime($item['checkin_date'])) && $date == date('Y-m-d' ,strtotime($item['checkout_date']))) {
            $status =  "Checkin/Checkout";
        }
        if ($status == "Checkin/Occuiped" || $status == "Occuiped") {
           // $room_nights_results[$dated]++;
        }
		if ($item['checkin_status'] == "1") {
            $room_nights_results[$dated]++;
        }
    }
}
 $room_night_results_date_wise = $room_nights_results;

$date_range = array_reverse($date_range);
$occupancy_reports = array();;
foreach ($date_range as $key => $date) {
    $room_nights = $room_night_results_date_wise[$date] ?? 0;
    $occupied_percentage = 0;
    if ($room_nights > 0 && $inventry_results > 0) {
        $occupied_percentage = ($room_nights / $inventry_results) * 100;
    }
    $occupancy_reports[$date] = [
        'inventry' => $inventry_results,
        'availability' => $inventry_results - ($room_night_results_date_wise[$date] ?? 0),
        'room_nights' => $room_night_results_date_wise[$date] ?? 0,
        'occupied' => $occupied_room_results_date_wise[$date] ?? 0,
        'occupied_percentage' => round($occupied_percentage),
    ];
}

$results['total_rooms'] = $total_rooms;
$results['occupied_rooms'] = $occupied_rooms;
$results['vacant_rooms'] = $vacant_rooms;
$results['checkin'] = count($checkin_results);
$results['arrival_pendings'] = $arrival_pendings;
$results['checkout'] = count($checkout_results);
$results['checkout_pendings'] = $checkout_pendings;
$results['adults'] = array_sum(array_column($guest_count, 'adults_per_room'));
$results['child_below_5_year'] = array_sum(array_column($guest_count, 'child_below_5_year'));
$results['child_above_5_year'] = array_sum(array_column($guest_count, 'child_above_5_year'));
$results['checkin_results'] = $checkin_results;
$results['checkout_results'] = $checkout_results;

$results['arrivals_pending_table'] =$arrival_pending_result_array;

$results['yesterday_room_nights'] = number_format($yesterday_room_nights_new, 2);
$results['yesterday_revenue'] = number_format($total_yesterday_revenue_new, 2);
$results['yesterday_arr'] = number_format($yesterday_arr_new, 2);
$results['yesterday_total_receipt_amount'] = number_format($yesterday_total_receipt_amount_new, 2);
$results['yesterday_cash_receipt_amount'] = number_format($yesterday_cash_receipt_amount_new, 2);
$results['yesterday_other_receipt_amount'] = number_format($yesterday_other_receipt_amount_new, 2);


$results['room_percentage'] = 0;
if ($last_week_room_nights != 0) {
    $room_night_sup = $yesterday_room_nights - $last_week_room_nights;
    $room_night_div = ($room_night_sup / $last_week_room_nights) * 100;
    $results['room_percentage'] = round($room_night_div ,2);
}

$results['revenue_percentage'] = 0;
if ($total_last_week_revenue != 0) {
    $revenue_sup = $total_yesterday_revenue - $total_last_week_revenue;
    $revenue_div = ($revenue_sup / $total_last_week_revenue) * 100;
    $results['revenue_percentage'] = round($revenue_div ,2);
}

$results['arr_percentage'] = 0;
if ($last_week_arr != 0) {
    $arr_sup = $yesterday_arr - $last_week_arr;
	if($arr_sup>'0' && $last_week_arr>'0' ){
    $arr_div = ($arr_sup / $last_week_arr) * 100;
    $results['arr_percentage'] = round($arr_div,2);
	}else{
		 $results['arr_percentage'] =0;
		
		}
}
$results['occupancy_reports'] = $occupancy_reports;

//debugData($results);
header('Content-Type: application/json');

echo json_encode($results);
?>