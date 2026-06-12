<?php

function cellColor($cells,$color){
	global $objPHPExcel;
	$objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
		'type' => PHPExcel_Style_Fill::FILL_SOLID,
		'startcolor' => array('rgb' => $color)
	));
}

function GetTotalRoom2($hotelId,$roomId='')
{
	global $connNew;
	if($roomId==''){
		$sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");
	}else{
		$sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'");
	}
	$row = mysqli_fetch_array($sql);
	return $row['totalRoom'];
}

function OccupancyStatisticsReport($Date,$id_report_type,$report_show,$showItemReport,$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel){

	define('TBL_RATE_PLAN','fo_rate_plan');
	define('TBL_INVENTORY','fo_inventory');
	define('TBL_ORDERS','fo_reservations');
	define('TBL_ORDER_DETAIL','fo_reservations_details');

	$_REQUEST['id_mst_hotels'] = '1';
	$id_mst_hotels = '1';

	$reservation_date = explode(' to ', $Date);
	$checkinDate      = date("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDate     = date("Y-m-d", strtotime($reservation_date['1']));
	$checkinDateOrg   = date("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDateOrg  = date("Y-m-d", strtotime($reservation_date['1']));

	$reportOccupancyArray    = array();
	$reportOccupancyArraySum = array();
	$emp = 1;
	$i   = 1;

	// =========================================================
	// LOOP 1: Inventory — collect ALL room types per date first,
	// then write labels ONCE and one rns_ entry per room type
	// =========================================================
	while (strtotime($checkinDate) <= strtotime($checkoutDate)) {

		$_REQUEST['id_mst_hotels'] = '1';
		$id_mst_hotelss = '1';
		$cond       = "  `".TBL_INVENTORY."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
		$connAssign = "  `".TBL_ASSIGN_HOTEL_ROOM."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";

		$sql = mysqli_query($connNew,"
			SELECT
				`".TBL_INVENTORY."`.id_mst_hotels,
				`".TBL_INVENTORY."`.allocation_date,
				`".TBL_INVENTORY."`.id_mst_room_types,
				SUM(`".TBL_INVENTORY."`.blocked_hotel) AS offline_block_hotel,
				SUM(`".TBL_INVENTORY."`.confirmed + `".TBL_INVENTORY."`.tentative + `".TBL_INVENTORY."`.waitlisted) AS crs_blocked,
				SUM(`".TBL_INVENTORY."`.confirmed) AS confirmed,
				SUM(`".TBL_INVENTORY."`.tentative) AS tentative,
				SUM(`".TBL_INVENTORY."`.waitlisted) AS waitlisted
			FROM `".TBL_INVENTORY."`
			LEFT JOIN `".TBL_ASSIGN_HOTEL_ROOM."`
				ON $connAssign
				`".TBL_ASSIGN_HOTEL_ROOM."`.id_mst_room_types = `".TBL_INVENTORY."`.id_mst_room_types
				AND `".TBL_ASSIGN_HOTEL_ROOM."`.id_mst_hotels = `".TBL_INVENTORY."`.id_mst_hotels
			LEFT JOIN `".TBL_HOTELS."`
				ON `".TBL_HOTELS."`.id = `".TBL_INVENTORY."`.id_mst_hotels
				AND `".TBL_HOTELS."`.status = 1
			WHERE $cond
				`".TBL_INVENTORY."`.allocation_date = '".date('Y-m-d', strtotime($checkinDate))."'
				AND `".TBL_INVENTORY."`.status = '1'
				AND `".TBL_ASSIGN_HOTEL_ROOM."`.status = '1'
				AND `".TBL_HOTELS."`.status = 1
				AND `".TBL_HOTELS."`.id_shop = '".$shop."'
			GROUP BY `".TBL_INVENTORY."`.id_mst_hotels, `".TBL_INVENTORY."`.id_mst_room_types
			ORDER BY `".TBL_HOTELS."`.name, `".TBL_INVENTORY."`.id_mst_room_types
		");

		// Collect all room-type rows for this date into an array
		$dateRows = array();
		while ($r = mysqli_fetch_object($sql)) {
			$dateRows[] = $r;
		}

		if (empty($dateRows)) {
			$checkinDate = date("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
			continue;
		}

		$hotelId = $dateRows[0]->id_mst_hotels;

		// Hotel-level total inventory (all room types combined)
		$resInv = mysqli_query($connNew,"
			SELECT sum(ahr.inventory) as totalRoominventory
			FROM `".TBL_ASSIGN_HOTEL_ROOM."` ahr
			LEFT JOIN `".TBL_ROOM_TYPE."` rt ON ahr.id_mst_room_types = rt.id
			WHERE ahr.status='1' AND rt.status='1'
			AND ahr.id_mst_hotels='".addslashes($hotelId)."'
		");
		$invRow = mysqli_fetch_array($resInv);
		$totalRoominventory = $invRow['totalRoominventory'];

		// Sum occupancy figures across all room types
		$totalCrsBlocked = 0;
		$totalOffline    = 0;
		foreach ($dateRows as $r) {
			$totalCrsBlocked += $r->crs_blocked;
			$totalOffline    += $r->offline_block_hotel;
		}
		$OccupancyT = ($totalRoominventory > 0)
			? round(($totalCrsBlocked + $totalOffline) / $totalRoominventory * 100)
			: 0;

		$allocation_date       = date("d D", strtotime($checkinDate));
		$lableDate             = date("M Y", strtotime($checkinDate));
		$last_day              = date('Y-m-t', strtotime($checkinDate));
		$allocation_date_month = date("D", strtotime($checkinDate));

		$isFirstDayOfRange = (strtotime($checkinDate) == strtotime($checkinDateOrg));
		$isFirstDayOfMonth = (date("Y-m-d", strtotime($checkinDate)) == date("Y-m-01", strtotime($checkinDate)));
		$isTrueMonthBoundary = ($isFirstDayOfMonth && !$isFirstDayOfRange);

		// Write header labels only ONCE per hotel per month/range-start
		if ($isFirstDayOfRange || $isTrueMonthBoundary) {

			// Spacer at true month boundary only (not at range start)
			if ($isTrueMonthBoundary) {
				$lableDateEmp = 'empty'.$emp;
				$reportOccupancyArray[$hotelId][$lableDateEmp]['dated'][] = '';
				foreach ($dateRows as $r) {
					$reportOccupancyArray[$hotelId][$lableDateEmp]['rns_'.$r->id_mst_room_types][] = '';
				}
				$reportOccupancyArray[$hotelId][$lableDateEmp]['totalRoominventory'][] = ''; // Total Room spacer
				$reportOccupancyArray[$hotelId][$lableDateEmp]['Occupancy'][]          = '';
				$emp++;
			}

			// Month header labels — written exactly ONCE
			// Room type names WITHOUT "Sold"
			$reportOccupancyArray[$hotelId][$lableDate]['dated'][] = $lableDate;
			foreach ($dateRows as $r) {
				$roomTypeName = selectColumn(TBL_ROOM_TYPE, 'name', "WHERE id='".addslashes($r->id_mst_room_types)."'");
				$reportOccupancyArray[$hotelId][$lableDate]['rns_'.$r->id_mst_room_types][] = $roomTypeName; // no ' Sold'
			}
			$reportOccupancyArray[$hotelId][$lableDate]['totalRoominventory'][] = 'Total Booked Rooms'; // after room breakup
			$reportOccupancyArray[$hotelId][$lableDate]['Occupancy'][]          = 'Total Occupancy';
		}

		// Per-date data row — written exactly ONCE per date
		$reportOccupancyArray[$hotelId][$checkinDate]['dated'][] = $allocation_date;
		foreach ($dateRows as $r) {
			$reportOccupancyArray[$hotelId][$checkinDate]['rns_'.$r->id_mst_room_types][] = $r->confirmed;
		}
		// Total Booked Rooms = sum of confirmed across all room types
		$totalBookedRooms = 0;
		foreach ($dateRows as $r) { $totalBookedRooms += $r->confirmed; }
		$reportOccupancyArray[$hotelId][$checkinDate]['totalRoominventory'][] = $totalBookedRooms;
		$reportOccupancyArray[$hotelId][$checkinDate]['Occupancy'][]          = $OccupancyT.' %';

		// Running sums for Total column
		$reportOccupancyArraySum[$hotelId]['Total']['totalRoominventory_'.$last_day][] = $totalBookedRooms;
		$reportOccupancyArraySum[$hotelId]['Total']['crs_blocked_'.$last_day][]        = $totalCrsBlocked;
		$reportOccupancyArraySum[$hotelId]['Total']['offline_'.$last_day][]            = $totalOffline;
		foreach ($dateRows as $r) {
			$reportOccupancyArraySum[$hotelId]['Total']['rns_'.$r->id_mst_room_types.'_'.$last_day][] = $r->confirmed;
		}

		// Total column on last day of month or end of date range
		if ($last_day == $checkinDate || $checkoutDateOrg == $checkinDate) {
			$totalKey = 'Total_'.$hotelId.'_'.$allocation_date_month;
			$invTotal = array_sum($reportOccupancyArraySum[$hotelId]['Total']['totalRoominventory_'.$last_day]);
			$rnsTotal = array_sum($reportOccupancyArraySum[$hotelId]['Total']['crs_blocked_'.$last_day]);
			$offTotal = array_sum($reportOccupancyArraySum[$hotelId]['Total']['offline_'.$last_day]);
			$occTotal = ($invTotal > 0) ? round(($rnsTotal + $offTotal) / $invTotal * 100) : 0;

			$reportOccupancyArray[$hotelId][$totalKey]['dated'][] = 'Total';
			foreach ($dateRows as $r) {
				$reportOccupancyArray[$hotelId][$totalKey]['rns_'.$r->id_mst_room_types][]
					= array_sum($reportOccupancyArraySum[$hotelId]['Total']['rns_'.$r->id_mst_room_types.'_'.$last_day]);
			}
			$reportOccupancyArray[$hotelId][$totalKey]['totalRoominventory'][] = $invTotal; // Total Room in Total col
			$reportOccupancyArray[$hotelId][$totalKey]['Occupancy'][]          = $occTotal.' %';
			$i++;
		}

		$checkinDate = date("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
	}

	// =========================================================
	// LOOP 2: Revenue (Total Revenue + ARR)
	// =========================================================
	$reservation_date = explode(' to ', $Date);
	$checkinDate      = date("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDate     = date("Y-m-d", strtotime($reservation_date['1']));
	$checkinDateOrg   = date("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDateOrg  = date("Y-m-d", strtotime($reservation_date['1']));
	$emp  = 1;
	$j    = 1;
	$Numk = 1;
	$revLabelWritten = array();

	while (strtotime($checkinDate) <= strtotime($checkoutDate)) {

		if ($_REQUEST['id_mst_hotels'] != '0' && !in_array(0, $_REQUEST['id_mst_hotels'])) {
			$id_mst_hotelss = $_REQUEST['id_mst_hotels'];
			$cond2 = "  `".TBL_ORDERS."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
		}

		$Query = "
			SELECT
				`".TBL_ORDERS."`.id_mst_hotels as id_mst_hotels,
				`fo_rate_plan`.id,
				'Total Revenue' as name,
				`".TBL_ORDER_DETAIL."`.id_mst_room_types,
				`".TBL_ORDER_DETAIL."`.id_fo_rate_plan,
				sum(case when (`fo_reservations`.`booking_status` = '1') AND DATE(`".TBL_ORDER_DETAIL."`.dated) = '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `confimed_revenue`,
				sum(case when (`fo_reservations`.`booking_status` = '2') AND DATE(`".TBL_ORDER_DETAIL."`.dated) = '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `tentative_revenue`
			FROM `".TBL_RATE_PLAN."`
			LEFT JOIN `".TBL_ORDER_DETAIL."` ON `".TBL_RATE_PLAN."`.id = `".TBL_ORDER_DETAIL."`.id_fo_rate_plan
			LEFT JOIN `".TBL_ORDERS."` ON `".TBL_ORDERS."`.id = `".TBL_ORDER_DETAIL."`.id_fo_reservations
			LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_mst_hotels AND `".TBL_HOTELS."`.status = 1
			WHERE $cond2
				`".TBL_RATE_PLAN."`.id_shop = '".addslashes($shop)."'
				AND `".TBL_RATE_PLAN."`.status = '1'
				AND `".TBL_HOTELS."`.status = 1
			GROUP BY `".TBL_ORDERS."`.id_mst_hotels

		UNION

			SELECT id_mst_hotels, id, name, id_mst_room_types, id_fo_rate_plan,
				(confimed_revenue / confirmed) as confimed_revenue, tentative_revenue
			FROM (
				SELECT
					`".TBL_ORDERS."`.id_mst_hotels as id_mst_hotels,
					`fo_rate_plan`.id,
					'ARR' as name,
					`".TBL_ORDER_DETAIL."`.id_mst_room_types,
					`".TBL_ORDER_DETAIL."`.id_fo_rate_plan,
					sum(case when (`fo_reservations`.`booking_status` = '1') AND DATE(`".TBL_ORDER_DETAIL."`.dated) = '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `confimed_revenue`,
					sum(case when (`fo_reservations`.`booking_status` = '2') AND DATE(`".TBL_ORDER_DETAIL."`.dated) = '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `tentative_revenue`,
					sum(case when (`".TBL_ORDERS."`.`booking_status` = '1') AND DATE(`".TBL_ORDER_DETAIL."`.dated) = '".date('Y-m-d',strtotime($checkinDate))."' then `".TBL_ORDER_DETAIL."`.room_quantity else 0 end) as `confirmed`
				FROM `".TBL_RATE_PLAN."`
				LEFT JOIN `".TBL_ORDER_DETAIL."` ON `".TBL_RATE_PLAN."`.id = `".TBL_ORDER_DETAIL."`.id_fo_rate_plan
				LEFT JOIN `".TBL_ORDERS."` ON `".TBL_ORDERS."`.id = `".TBL_ORDER_DETAIL."`.id_fo_reservations
				LEFT JOIN `".TBL_HOTELS."` ON `".TBL_HOTELS."`.id = `".TBL_ORDERS."`.id_mst_hotels AND `".TBL_HOTELS."`.status = 1
				WHERE $cond2
					`".TBL_RATE_PLAN."`.id_shop = '".addslashes($shop)."'
					AND `".TBL_RATE_PLAN."`.status = '1'
					AND `".TBL_HOTELS."`.status = 1
				GROUP BY `".TBL_ORDERS."`.id_mst_hotels
			) subgroptabble
		";

		// Collect both UNION rows (Total Revenue + ARR) first to avoid duplicate label writes
		$revenueRows = array();
		$sql = mysqli_query($connNew, $Query);
		while ($rowRoom_update = mysqli_fetch_object($sql)) {
			$revenueRows[$rowRoom_update->name] = $rowRoom_update;
		}

		if (!empty($revenueRows)) {
			$hotelId               = reset($revenueRows)->id_mst_hotels;
			$lableDate             = date("M Y", strtotime($checkinDate));
			$allocation_date_month = date("D", strtotime($checkinDate));
			$last_day              = date('Y-m-t', strtotime($checkinDate));

			$isRevFirstDayOfRange    = (strtotime($checkinDate) == strtotime($checkinDateOrg));
			$isRevFirstDayOfMonth    = (date("Y-m-d", strtotime($checkinDate)) == date("Y-m-01", strtotime($checkinDate)));
			$isRevTrueMonthBoundary  = ($isRevFirstDayOfMonth && !$isRevFirstDayOfRange);

			// Spacer at true month boundary
			if ($isRevTrueMonthBoundary) {
				$lableDateEmp = 'empty'.$emp;
				$reportOccupancyArray[$hotelId][$lableDateEmp]['dated'][]   = '';
				$reportOccupancyArray[$hotelId][$lableDateEmp]['revenue'][] = '';
				$emp++;
			}

			// Write revenue labels ONCE per hotel+month into the same $lableDate key as Loop 1
			$revMonthKey = $hotelId.'_'.$lableDate;
			if (empty($revLabelWritten[$revMonthKey])) {
				foreach ($revenueRows as $revName => $revRow) {
					$reportOccupancyArray[$hotelId][$lableDate]['rev_'.$revName][] = $revName;
				}
				$revLabelWritten[$revMonthKey] = true;
			}

			// Per-date values — one key per revenue type, written ONCE per date
			foreach ($revenueRows as $revName => $revRow) {
				$reportOccupancyArray[$hotelId][$checkinDate]['rev_'.$revName][] = round($revRow->confimed_revenue);
				$reportOccupancyArraySum[$hotelId]['Total']['rev_'.$revName.'_'.$last_day][] = round($revRow->confimed_revenue);
			}

			// Total column on last day of month or end of range
			if ($last_day == $checkinDate || $checkoutDateOrg == $checkinDate) {
				foreach ($revenueRows as $revName => $revRow) {
					$reportOccupancyArray[$hotelId]['Total_'.$hotelId.'_'.$allocation_date_month]['rev_'.$revName][]
						= array_sum($reportOccupancyArraySum[$hotelId]['Total']['rev_'.$revName.'_'.$last_day]);
				}
				$j++;
			}
		}

		$Numk++;
		$checkinDate = date("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
	}

	// =========================================================
	// EXCEL GENERATION
	// =========================================================
	$objPHPExcel->getProperties()
		->setCreator("Gaurav Sharma")
		->setLastModifiedBy("Gaurav Sharma")
		->setTitle("Occupancy Report")
		->setSubject("Occupancy Report")
		->setDescription("Occupancy Report")
		->setKeywords("Occupancy Report")
		->setCategory("Report");

	$styleArray = array(
		'font' => array(
			'bold'  => true,
			'color' => array('rgb' => '1e51bf'),
			'size'  => 15,
			'name'  => 'Verdana'
		)
	);

	$styleThinBlackBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN,
				'color' => array('argb' => '000'),
			),
		),
	);

	$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
	$objPHPExcel->getActiveSheet()->getStyle('B2:M2')->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	);
	$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B2',
		'Occupancy Report From '.date("d-m-Y", strtotime($checkinDateOrg)).' To '.date("d-m-Y", strtotime($checkoutDateOrg))
	);
	$objPHPExcel->getActiveSheet()->mergeCells('B2:M2');

	$Rowcount      = 4;
	$RowcountStart = $Rowcount;

	foreach ($reportOccupancyArray as $id_mst_hotels => $hotelarray) {

		$ColumWise = 'A';
		$Rowcount++;
		$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getFont()->setBold(true);
		$HotelName = selectColumn(TBL_HOTELS, 'name', " WHERE `id` = '".addslashes($id_mst_hotels)."'");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Rowcount++, strtoupper($HotelName));
		$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getFont()->setBold(true);

		foreach ($hotelarray as $subarray => $data3) {

			$RowcountIns = $Rowcount;
			$isEmptyCol  = (strpos($subarray, 'empty') !== false);

			// Saturday/Sunday column highlight
			if (!$isEmptyCol && $ColumWise != 'A'
				&& (date('D', strtotime($subarray)) == 'Sat' || date('D', strtotime($subarray)) == 'Sun')) {
				cellColor($ColumWise.$Rowcount, 'a2a2ef');
				$DaySatSunday = 1;
			} else {
				$DaySatSunday = 0;
			}

			if (!$isEmptyCol) {
				$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->applyFromArray($styleThinBlackBorderOutline);
				$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getAlignment()->applyFromArray(
					array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
				);
				$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getFont()->setBold(true);
			}

			$InnerRowCount = 0;
			foreach ($data3 as $lab => $subarray3) {
				$InnerRowCount++;
				foreach ($subarray3 as $FinalDataList) {
					if (!$isEmptyCol) {
						$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->applyFromArray($styleThinBlackBorderOutline);
						$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getAlignment()->applyFromArray(
							array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
						);
						// Orange highlight for Total Occupancy, Total Revenue, ARR rows
						if ($lab == 'Occupancy'
							|| $lab == 'totalRoominventory'
							|| $lab == 'rev_Total Revenue'
							|| $lab == 'rev_ARR') {
							cellColor($ColumWise.$Rowcount, 'ffc000');
						}
						// Override with blue for Sat/Sun columns
						if ($DaySatSunday == 1) {
							cellColor($ColumWise.$Rowcount, 'a2a2ef');
						}
						// Auto column width
						$objPHPExcel->getActiveSheet(0)->getColumnDimension($ColumWise)->setWidth(15);
					}
					$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ColumWise.$Rowcount++, $FinalDataList);
				}
			}

			$OldRowcount = $Rowcount;
			$Rowcount    = $RowcountIns;
			$ColumWise++;
		}

		$Rowcount = $OldRowcount + 13;
	}

	$objPHPExcel->getActiveSheet()->getStyle('B'.$RowcountStart.':B'.$Rowcount)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('C'.$RowcountStart.':C'.$Rowcount)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	);
	$objPHPExcel->getActiveSheet()->getStyle('D'.$RowcountStart.':D'.$Rowcount)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
	);

	$objPHPExcel->getActiveSheet()->setTitle('Occupancy Report');
	$objPHPExcel->setActiveSheetIndex(0);

	if ($cronSet == '1') {
		$Filename  = $pdfNameReport3;
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/'.$Filename.'.xls');
	} else {
		ob_end_clean();
		$filename  = 'OccupancyStatisticsReport'.date('d-M-Y').'.xls';
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="'.$filename.'"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
	}
}
?>