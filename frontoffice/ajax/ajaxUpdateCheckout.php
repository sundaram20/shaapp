<?php
include_once("../../config/auto_loader.php");

$id_fo_bill = $_REQUEST['id_fo_bill'];
$status = $_REQUEST['status'];
$id_reservation = $_REQUEST['id_reservation'];
$checkout_time = $_REQUEST['checkout_time'] ?? '';
$dataArray = array();

$sqlVa = "SELECT * FROM ".FO_BILL." where id = '".$id_fo_bill."' and id_reservations = '".$id_reservation."' and `doc_no`='0' and id_doc_type_configuration = '0'";
$Vali =	mysqli_query($connNew,$sqlVa);
if (mysqli_num_rows($Vali) > 0) {
	$dataArray['status'] = '0';
	$dataArray['message'] = 'Please Generate FO Bill.';
	echo json_encode($dataArray);
	die;
} else {
		$id_folio = selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
		//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_folio_to` = '".$id_folio."'";
		$DateOrderByRoom = array();
		$sqlOrderDetailFolio = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_folio_to` = '".$id_folio."' group by order_by_room order by id_fo_folio_to");
		if (mysqli_num_rows($sqlOrderDetailFolio) > 0) {
			while($sqlOrderDetailFolioRow = mysqli_fetch_object($sqlOrderDetailFolio)) {
				$DateOrderByRoom[]= $sqlOrderDetailFolioRow->order_by_room;
			}
		}
//echo '111';
$DateOrderByRoom	= implode(',',$DateOrderByRoom);
//print_r($DateOrderByRoom);die;

		//==Balance================================================
		$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_folio_to` = '".$id_folio."' and `order_by_room` IN (".$DateOrderByRoom.")");
		if (mysqli_num_rows($sqlOrderDetail) > 0) {
			while($rowOrderDetail = mysqli_fetch_object($sqlOrderDetail)) {
				$CurrentTotal += $rowOrderDetail->tariff_price_per_day_per_room + $rowOrderDetail->tax_per_day_per_room;
			}
		}
		$sqlOrderDetail = mysqli_query($connNew, "Select * from `pos_purch` where id_fo_folio_to = '".addslashes($id_folio)."' and cancelled != 1");
		if (mysqli_num_rows($sqlOrderDetail) > 0) {
			while($rowOrderDetail = mysqli_fetch_object($sqlOrderDetail)) {
				$CurrentTotal += $rowOrderDetail->grant_total_amount;
			}
		}

		$sqlOrderDetail = mysqli_query($connNew, "Select  * from `fo_reservations_addons_details` where id_fo_folio_to = '".addslashes($id_folio)."'");
		if (mysqli_num_rows($sqlOrderDetail) > 0) {
			while ($rowOrderDetail = mysqli_fetch_object($sqlOrderDetail)) {
				$CurrentTotal += $rowOrderDetail->total;
			}
		}

		$receipt_amount	= round(selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$id_folio.'"'),2);
 		$BalanceAmount = round($CurrentTotal - $receipt_amount);
		//==================================================

		if ($BalanceAmount == 0) {
		} else {
			/*$_REQUEST['precheckout'] = 0;
			$dataArray['status'] = '1';
			$dataArray['message'] = 'Your Receipt Balance Is Pending.';//.$BalanceAmount;
			echo json_encode($dataArray);
			die;*/
		}

		$sqlCheckoutStatus = mysqli_query($connNew,"Select * From  ".FO_BILL."    WHERE id = '".$id_fo_bill."' AND status = '2' and id_reservations = '".$id_reservation."'");
		if (mysqli_num_rows($sqlCheckoutStatus) > 0) {
			$dataArray['status'] = '1';
			$dataArray['message'] = 'Checkout Already Processed';
			echo json_encode($dataArray);
			die;
		} else {
			$reservation_checkout =	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
			$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
			$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
			$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
			$rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
			$DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));

			if (strtotime($reservation_checkout) > strtotime($DatedNightAudit) && $_REQUEST['precheckout'] == '0') {
				$_REQUEST['precheckout'] = 0;
				$dataArray['status'] = '5';
				$dataArray['message'] = 'Do you want to Pre-Checkout ?';
	 			echo json_encode($dataArray);
				die;
			} else {
				$_REQUEST['precheckout'] = 1;
			}
			
			if ($_REQUEST['precheckout'] == '1') {

				$reservation_checkout = date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
				if (strtotime($reservation_checkout) < strtotime($DatedNightAudit)) {
					$dataArray['status'] = '0';
					$dataArray['message'] = 'you are in wrong day close date?';
					echo json_encode($dataArray);
					die;
				}

				$DateArray = array();
				while(strtotime($DatedNightAudit) != strtotime($reservation_checkout)) {
					$check_status = selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status','WHERE id_fo_reservations = "'.$id_reservation.'" and checkin_status = "0" and dated = "'.date("Y-m-d",strtotime($DatedNightAudit)).'" and `order_by_room` IN (".$DateOrderByRoom.")');
					if ($check_status == '0') {
						$DateArray[] = date("Y-m-d",strtotime($DatedNightAudit));
					}
					$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($DatedNightAudit)));	
				}
				$DateArray = "'".implode ( "','", $DateArray )."'";
				//================================================================================
				$sqlOrderDetail = mysqli_query($connNew, "SELECT * FROM ( SELECT * FROM fo_reservations_details WHERE id_fo_reservations = '".$id_reservation."' and `order_by_room` IN (".$DateOrderByRoom.") ORDER BY dated DESC LIMIT 18446744073709551615 ) AS sub GROUP BY order_by_room;");
				if (mysqli_num_rows($sqlOrderDetail) > 0) { 
					while ($rowOrderDetail = mysqli_fetch_object($sqlOrderDetail)) {
						$roomNo = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
						$RoomName =	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
						$id_mst_room_no_allocationArray[] = $rowOrderDetail->id_mst_room_no_allocation;
						$roomNumberArray[] = $roomNo;
					}
				}
				$roomNumberArray = implode(',',$roomNumberArray);
				$id_mst_room_no_allocationArray	= implode(',',$id_mst_room_no_allocationArray);
				//================================================================================
				$updateRoomstatus = "UPDATE `mst_room_no_allocation` SET `room_status` = '4' where `id` IN (".$id_mst_room_no_allocationArray.")";
				mysqli_query($connNew, $updateRoomstatus);
				
				$UpdateHouseKeeping =  "UPDATE `mst_room_no_allocation` SET `house_keeping_status` = '1' where id IN (".$id_mst_room_no_allocationArray.")";
					$HouseKeeping= mysqli_query($connNew, $UpdateHouseKeeping);
				
				
				
				$sqlNightAudit = mysqli_query($connNew, "SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1");
				$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
				$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
				$Dated = date('Y-m-d',strtotime($rowNightAudit->dated));
				$DatedNightAudit2 = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
				$DatedNightAudit1 = date('Y-m-d',strtotime($rowNightAudit->dated));
				$DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
				$id_owner_room = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$id_fo_bill."'");
				$sql = "UPDATE ".FO_BILL." SET status = '2' , `checkout_date`='".date($DatedNightAudit2.' H:i:s')."' WHERE id_reservations='".$id_reservation."' and `id` = '".$id_fo_bill."'";
				if (mysqli_query($connNew,$sql)) {
	 				$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `no_showoff`= '1' where `id_fo_reservations` = '".$id_reservation."' and  DATE(dated) IN (".stripslashes($DateArray).") and `order_by_room` IN (".$DateOrderByRoom.")";
					$insertOrder = mysqli_query($connNew,$insertGrid);

					if ($checkout_time != '') {
						$updateCheckoutTime = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkout_time`='".$checkout_time."', `room_availability`='checkout' where `id_fo_reservations` = '".$id_reservation."' and `order_by_room` IN (".$DateOrderByRoom.")";
						$insertOrder = mysqli_query($connNew,$updateCheckoutTime);
					}
					//================================================================================
					$sqlOrderDetailOrderby = mysqli_query($connNew, "SELECT id FROM fo_reservations_details WHERE id_fo_reservations = '".$id_reservation."' AND checkin_status = '1' AND dated = ( SELECT MAX(dated) FROM fo_reservations_details WHERE id_fo_reservations = '".$id_reservation."'  AND checkin_status = '1' and `no_showoff`='0' )");
					if (mysqli_num_rows($sqlOrderDetailOrderby) > 0) {
						while ($rowOrderDetailOrder = mysqli_fetch_object($sqlOrderDetailOrderby)) {
							$idOfOrder[] = $rowOrderDetailOrder->id;
						}
					}
					$idOfOrder = implode(',',$idOfOrder);
					//================================================================================
					$reservationcheckin = date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_reservation.'"')));
					$reservationcheckout = date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
					$daysNew = abs((strtotime($reservationcheckin) - strtotime($reservationcheckout))/ 86400 );
					if ($daysNew == '1') {
						$reservationcheckout = date('Y-m-d',strtotime($reservationcheckout));
						$checkOutDated = selectColumn(FO_RESERVATIONS_DETAILS,'dated','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" and no_showoff="0"  and checkout_status="0" order by dated desc');
	  					$insertGrid2 = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkout_status`='1', `checkout_date` = '".date($reservationcheckout.' H:i:s')."' where `id_fo_reservations` = '".$id_reservation."' and `order_by_room` IN (".$DateOrderByRoom.") and id IN (".stripslashes($idOfOrder).")";
						mysqli_query($connNew, $insertGrid2);
					} else {
						$checkOutDated = selectColumn(FO_RESERVATIONS_DETAILS,'dated','WHERE id_fo_reservations="'.$id_reservation.'"  and checkin_status="1" and no_showoff="0"  and checkout_status="0" order by dated desc');
	 					$insertGrid2 = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkout_status` = '1', `checkout_date` = '".date($checkOutDated.' H:i:s')."' where `id_fo_reservations` = '".$id_reservation."' and `order_by_room` IN (".$DateOrderByRoom.") and id IN (".stripslashes($idOfOrder).")";
						mysqli_query($connNew,$insertGrid2);
					}

					$reservationcheckout = date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_reservation.'"')));
					$DateArrayDate = array();
					while (strtotime($DatedNightAudit) != strtotime($reservationcheckout)) {
						$check_status = selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status','WHERE id_fo_reservations = "'.$id_reservation.'" and checkin_status = "0" and dated = "'.date("Y-m-d",strtotime($DatedNightAudit)).'" and `order_by_room` IN (".$DateOrderByRoom.")');
						if ($check_status == '0') {
							$DateArrayDate[] = date("Y-m-d",strtotime($DatedNightAudit));
						}
						$DatedNightAudit = date('Y-m-d',strtotime('+1 day',strtotime($DatedNightAudit)));
					}
					$DateArrayDate = "'".implode ( "','", $DateArrayDate )."'";
					$insertGridDate =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `no_showoff` = '1' where `id_fo_reservations` = '".$id_reservation."' and DATE(dated) IN (".stripslashes($DateArrayDate).") and `order_by_room` IN (".$DateOrderByRoom.")";
					$insertOrder = mysqli_query($connNew, $insertGridDate);
					
										
					
					$dataArray['status'] = '1';
					$dataArray['message'] = 'Checkout updated sucessfully';
					$dataArray['checkoutdate'] = date('d-m-Y',strtotime($DatedNightAudit2));
					echo json_encode($dataArray);
				} else {
					$dataArray['status'] = '0';
					$dataArray['message'] = 'Please verify data';
					echo json_encode($dataArray);
				}
			}
		}
	}
?>


