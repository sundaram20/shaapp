<?php
	include_once("../../config/auto_loader.php");
	include_once("../functions/function.php");


//echo "Check-in Processed Successfully";
//debugData($_REQUEST);
//die;
	$jsondeocde = json_decode($_REQUEST['bookedRoom'], true);
	$RoomNoArray = explode(',',$_REQUEST['dataselected']);
	$id_mst_room_types = $_REQUEST['id_mst_room_types'];
	$checkin_time = $_REQUEST['checkin_time'] ?? '';
	$room_id = json_decode($_REQUEST['room_id'] ?? '', true);

	$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1");
	$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
	$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
	$NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));

	$TodaysData	=	$NightAuditDated;
	
	$sql = "SELECT * FROM ".FO_RESERVATIONS."  where id='".addslashes($_REQUEST['resvId'])."'";
	$res = mysqli_query($connNew,$sql);
	$row = mysqli_fetch_object($res);

	$checkout =	$row->checkout;
	$checkin = $row->checkin;
	$dated = $checkin;
	$DateArray = array();

	if($_SESSION['database'] == 'whm' || $_SESSION['database'] == 'ddemo'){		
		$id_mst_company	= $row->id_mst_company;
		$updateBillToCompany = ",id_bill_to_company='".$id_mst_company."'";
		
	}else{
		$updateBillToCompany='';
		}


	$sqlOrderGroupby = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and checkin_status='0'  and `no_showoff`='0'	 and dated='".date('Y-m-d',strtotime($TodaysData))."' and  id_mst_room_types='".addslashes($id_mst_room_types)."'  group by order_by_room");
	while ($rowOrderDetailGroupBy = mysqli_fetch_object($sqlOrderGroupby)) {
		$groupbyReservedRoom[]=$rowOrderDetailGroupBy->id_mst_room_no_allocation;
	}

	$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and checkin_status='0' and `no_showoff`='0' and dated='".date('Y-m-d',strtotime($TodaysData))."' and  id_mst_room_types='".addslashes($id_mst_room_types)."'");
	$UserSelectedDiff = array_diff($RoomNoArray,$groupbyReservedRoom);
	if (mysqli_num_rows($sqlOrderDetail2) > 0) {
		$order_by_roomArray = array();
		$order_by_roomCheckInArray = array();
		$ArrayOrder	= 0;
		while ($rowOrderDetail2 = mysqli_fetch_object($sqlOrderDetail2)) {
			if ($rowOrderDetail2->id_mst_room_no_allocation == 0) {
				$order_by_roomArray[] =$rowOrderDetail2->order_by_room;
			}

			$room_noCheckArray= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail2->id_mst_room_no_allocation."'");
			if (in_array($room_noCheckArray, $RoomNoArray)) {
				foreach (array_keys($RoomNoArray, $room_noCheckArray) as $key) {
					unset($RoomNoArray[$key]);
				}

				$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation'] =	$rowOrderDetail2->id_mst_room_no_allocation;
				$order_by_roomAllocationInArray[$ArrayOrder]['Status'] = 'Matched';
				$order_by_roomAllocationInArray[$ArrayOrder]['StatusMatched'] =	'1';
				$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved'] =	$rowOrderDetail2->id_mst_room_no_allocation;
				$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_types'] =	$rowOrderDetail2->id_mst_room_types;
			} else {
				$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation'] = 0;
				$order_by_roomAllocationInArray[$ArrayOrder]['Status'] ='Not Matched';
				$order_by_roomAllocationInArray[$ArrayOrder]['StatusMatched'] = '0';
				$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_types'] = $rowOrderDetail2->id_mst_room_types;

				if ($rowOrderDetail2->id_mst_room_no_allocation == '0') {
					$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved'] = 'zero';
				} else {
					$order_by_roomAllocationInArray[$ArrayOrder]['Room Reserved'] = $rowOrderDetail2->id_mst_room_no_allocation;
					$order_by_roomAllocationInArray[$ArrayOrder]['id_mst_room_no_allocation_new'] = $RoomNoArray[$ArrayOrder];
				}
			}
			$order_by_roomAllocationInArray[$ArrayOrder]['order_by_room'] = $rowOrderDetail2->order_by_room;
			$ArrayOrder++;
		}
	}

	// echo '===============';
	// debugData($groupbyReservedRoom);
	// debugData($RoomNoArray);
	// debugData($order_by_roomAllocationInArray);
	// die;

	while (strtotime($dated) != strtotime($checkout)) {
		$DateArray[] = date("Y-m-d",strtotime($dated));
		$dated = date('Y-m-d',strtotime('+1 day',strtotime($dated)));	
	}
	$DateArray= "'".implode ( "','", $DateArray )."'";

	$sqlOrderDetailArray = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and   DATE(dated)='".addslashes($TodaysData)."' and  id_mst_room_types='".addslashes($id_mst_room_types)."' group by `order_by_room` order by id asc");
	if (mysqli_num_rows($sqlOrderDetailArray) > 0) {
		$RoomWiseArray = array();
		while ($rowOrderDetailArray = mysqli_fetch_object($sqlOrderDetailArray)) {
			$RoomWiseArray[] = $rowOrderDetailArray->id_mst_room_types;
		}
	}
	$id_rooms = implode(',',$RoomWiseArray);
	$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and   DATE(dated)='".addslashes($TodaysData)."' and `checkin_status`='0'");

	if (mysqli_num_rows($sqlOrderDetail) > 0) {
		$rowReservationDetail = mysqli_fetch_object($sqlOrderDetail);
		$id_mst_room_no_allocation = $rowReservationDetail->id_mst_room_no_allocation;
		$id_fo_bill	= selectColumn(FO_BILL,'id'," WHERE `id_reservations` = '".addslashes($_REQUEST['resvId'])."'");
		$id_fo_folio = selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id_reservations` = '".addslashes($_REQUEST['resvId'])."'");

		if ($id_fo_bill == 0 || $id_fo_bill == '') {
			$id_doc_type = '804'; //DOCUMENT TYPE FOLIO 803
			$doc_table_name = 'fo_folio';
			$date = date('Y-m-d');
			$id_subsection = '1';
			$id_shop = $_SESSION['shop'];
			$docConfig = docTypeConfig($id_doc_type,$NightAuditDated,$id_subsection,$doc_table_name,$connNew,$id_shop);

			$insertdocConfig = "INSERT INTO fo_folio  SET
			`id_mst_shops`='".$_SESSION['shop']."',
			`id_mst_guest`='".$row->id_mst_guest."',
			`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
			`doc_no`='".addslashes($docConfig['po_no'])."',
			`doc_date`='".date('Y-m-d',strtotime($NightAuditDated))."',
			`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
			`doc_type` = '".addslashes($id_doc_type)."',
			`date_created` = '".currenDateTime()."',
			`id_mst_user_created_by` = '".$_SESSION['userId']."',
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."' ".$updateBillToCompany." ";
			mysqli_query($connNew, $insertdocConfig);
			$id_fo_folio = mysqli_insert_id($connNew);

		  	$insertGrid = "INSERT INTO ".FO_BILL." SET
			`id_reservations` = '".addslashes($_REQUEST['resvId'])."',	
			`id_mst_shops`='".$_SESSION['shop']."',				
			`folio_no`='".addslashes($_REQUEST['po_no'])."',
			`id_fo_folio`='".addslashes($id_fo_folio)."',
			`id_fo_folio_to`='".addslashes($id_fo_folio)."',
			`id_doc_type_configuration`	='".addslashes($_REQUEST['id_doc_type_configuration'])."',
			`doc_no`='".addslashes($_REQUEST['po_no'])."',
			`doc_date`='".date('Y-m-d',strtotime($_REQUEST['po_date']))."',
			`mdoc_no`=	'".addslashes($_REQUEST['prefix']).addslashes($_REQUEST['po_no']).addslashes($_REQUEST['suffix'])."',
			`doc_type` = '".addslashes($_REQUEST['id_doc_type_configuration'])."',
			`date_created` = '".currenDateTime()."',
			`id_mst_user_created_by` = '".$_SESSION['userId']."',
			`last_modified` = '".currenDateTime()."',
			`id_mst_user_modified_by` = '".$_SESSION['userId']."'";
			mysqli_query($connNew,$insertGrid);
			$id_fo_bill = mysqli_insert_id($connNew);
			
			
			
			
			
			
			$updateFolioGrid =  "UPDATE `fo_folio` SET `id_fo_bill`='".addslashes($id_fo_bill)."' where`id` IN (".$id_fo_folio.")";
			mysqli_query($connNew,$updateFolioGrid);
		}

		
		/*foreach ($room_id as $key => $room) {
			if ($room['key'] == 0) {
				$fo_bill_no = $id_fo_bill;
			} else {
				$room_key = $room['key'];
				$fo_bill_no = selectColumn('fo_folio','id_fo_bill'," WHERE id = '".$room_key."'");
			}
			$room_no = $room['value'];
			$updateFoBill =  "UPDATE `fo_bill` SET `id_owner_room`='".$room_no."' where`id` = '".$fo_bill_no."'";
			mysqli_query($connNew,$updateFoBill);
		}*/
		

		foreach ($order_by_roomAllocationInArray as $keyOrder => $orderListValue) {
			$id_mst_room_no_allocation = selectColumn(TBL_ROOMNO,'id'," WHERE `room_no` = '".$RoomNoArray[$keyOrder]."' and id_mst_room_types IN (".$id_rooms.")");
			$reservation_details = mysqli_query($connNew, "select * from fo_reservations_details where `id_fo_reservations` = '".$_REQUEST['resvId']."'	and order_by_room='".$orderListValue['order_by_room']."' and `no_showoff` = '0'");
			$reservation_detail_result = mysqli_fetch_object($reservation_details);
			if (!empty($reservation_detail_result)) {
				if ($reservation_detail_result->fo_folio_temp > 0) {
					$id_fo_folio = $reservation_detail_result->fo_folio_temp;
				}
			}

			if ($orderListValue['StatusMatched'] == '1') {

				$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
				`id_mst_room_no_allocation`='".$orderListValue['id_mst_room_no_allocation']."' where
				`id_fo_reservations` = '".$_REQUEST['resvId']."' and `no_showoff`='0'
				and order_by_room ='".$orderListValue['order_by_room']."'
				and id_mst_room_types ='".$id_mst_room_types."'
				and  DATE(dated) IN (".stripslashes($DateArray).") ";
				$insertOrder = mysqli_query($connNew,$insertGrid);
			
				$insertGrid2 =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET
				`checkin_status`='1',
				`checkin_date` = '".date($NightAuditDated.' H:i:s')."',
				`id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."',
				`id_fo_bill`='".$id_fo_bill."' where
				`id_fo_reservations` = '".$_REQUEST['resvId']."'	
				and order_by_room='".$orderListValue['order_by_room']."'			   
				and id_mst_room_types='".$id_mst_room_types."' 
				and  DATE(dated) ='".$TodaysData."'
				and `id_mst_room_no_allocation`='".$orderListValue['id_mst_room_no_allocation']."'
				and `no_showoff` = '0'";
				mysqli_query($connNew,$insertGrid2);

				$updateSql = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET
				`fo_folio_temp`='".addslashes($id_fo_folio)."' where
				`id_fo_reservations` = '".$_REQUEST['resvId']."'
				and order_by_room='".$orderListValue['order_by_room']."'
				and id_mst_room_types='".$id_mst_room_types."'
				and `id_mst_room_no_allocation`='".$orderListValue['id_mst_room_no_allocation']."'
				and `no_showoff`='0' and DATE(dated) IN (".stripslashes($DateArray).")";
				mysqli_query($connNew,$updateSql);

				if ($checkin_time != '') {
					$insertGrid = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkin_time`='".$checkin_time."', `room_availability`='Checkin' where
					`id_fo_reservations` = '".$_REQUEST['resvId']."'  and `no_showoff`='0' and order_by_room='".$orderListValue['order_by_room']."'
					and id_mst_room_types='".$id_mst_room_types."' and DATE(dated) IN (".stripslashes($DateArray).")";
					$insertOrder	=mysqli_query($connNew,$insertGrid);
				}

				$allocated_room_query = mysqli_query($connNew, "select * from ".FO_RESERVATIONS_DETAILS." where `id_mst_room_no_allocation`='".$orderListValue['id_mst_room_no_allocation']."' and `no_showoff`='0' and DATE(dated) ='".$TodaysData."' and checkin_time is null");
				if (mysqli_num_rows($allocated_room_query) > 0) {
					$allocated_room_result = mysqli_fetch_object($allocated_room_query);
					mysqli_query($connNew, "update ".FO_RESERVATIONS_DETAILS." SET id_mst_room_no_allocation = '0' where id_fo_reservations = '".$allocated_room_result->id_fo_reservations."' and id_mst_room_no_allocation = '".$allocated_room_result->id_mst_room_no_allocation."'");
				}
				
					//fo_receipt update

				$update_receipt = "UPDATE `fo_receipt` SET 
						`id_fo_bill` = '$id_fo_bill',
						`id_fo_folio` = '$id_fo_folio'
					WHERE id_fo_bill = 0 
					AND id_fo_folio = 0 
					AND is_advance = '1'
					AND id_reservation = '".$_REQUEST['resvId']."'
					";

					mysqli_query($connNew,$update_receipt);

				//=================
			
			 	$updateRoomstatus =  "UPDATE `mst_room_no_allocation` SET `room_status`='3'	  where id='".$orderListValue['id_mst_room_no_allocation']."'";
			  	mysqli_query($connNew,$updateRoomstatus);
				unset($order_by_roomAllocationInArray[$keyOrder]);
			}
		}
			
		$order_by_roomAllocationInArray = array_values($order_by_roomAllocationInArray);
		$RoomNoArray = array_values(array_filter($RoomNoArray));
		$loopKeys = 0;

		foreach ($order_by_roomAllocationInArray as $key => $orderListValue) {
			if ($orderListValue['StatusMatched'] == '0' && $RoomNoArray[$key] != '') {

				if ($_REQUEST['id_mst_room_types'] == $orderListValue['id_mst_room_types']) {
					$loopKeys;
					$RoomNoArray[$key];
					$id_mst_room_no_allocation = selectColumn(TBL_ROOMNO,'id'," WHERE `room_no` = '".$RoomNoArray[$key]."' and id_mst_room_types IN (".$id_rooms.")");
					$occupied_room_query = mysqli_query($connNew, "select * from ".FO_RESERVATIONS_DETAILS." where `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."' and `no_showoff`= '0' and DATE(dated) ='".$TodaysData."' and room_availability = 'Checkin'");
					if (mysqli_num_rows($occupied_room_query) == 0) {
						$reservation_details = mysqli_query($connNew, "select * from fo_reservations_details where `id_fo_reservations` = '".$_REQUEST['resvId']."' and order_by_room='".$orderListValue['order_by_room']."' and `no_showoff` = '0'");
						$reservation_detail_result = mysqli_fetch_object($reservation_details);
						if (!empty($reservation_detail_result)) {
							if ($reservation_detail_result->fo_folio_temp > 0) {
								$id_fo_folio = $reservation_detail_result->fo_folio_temp;
							}
						}

						$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
						`id_mst_room_no_allocation`='".$id_mst_room_no_allocation."' where
						`id_fo_reservations` = '".$_REQUEST['resvId']."'
						and order_by_room='".$orderListValue['order_by_room']."'	 		
						and id_mst_room_types='".$id_mst_room_types."' 
						and  DATE(dated) IN (".stripslashes($DateArray).")  and `no_showoff`='0'";
						$insertOrder = mysqli_query($connNew,$insertGrid);

						$insertGrid2 =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
						`checkin_status`='1',
						`checkin_date` = '".date($NightAuditDated.' H:i:s')."',
						`id_fo_folio`='".addslashes($id_fo_folio)."',
						`id_fo_folio_to`='".addslashes($id_fo_folio)."',
						`id_fo_bill`='".$id_fo_bill."' where
						`id_fo_reservations` = '".$_REQUEST['resvId']."'	
						and order_by_room='".$orderListValue['order_by_room']."'			   
						and id_mst_room_types='".$id_mst_room_types."' 
						and DATE(dated) ='".$TodaysData."'
						and `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."'
						and `id_mst_room_no_allocation` > '0'
						and `no_showoff`='0'";
						mysqli_query($connNew,$insertGrid2);

						$updateSql =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
						`fo_folio_temp`='".addslashes($id_fo_folio)."' where
						`id_fo_reservations` = '".$_REQUEST['resvId']."'	
						and order_by_room='".$orderListValue['order_by_room']."'			   
						and id_mst_room_types='".$id_mst_room_types."' 
						and `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."' and `id_mst_room_no_allocation` > '0' and `no_showoff`='0' and DATE(dated) IN (".stripslashes($DateArray).")";
						mysqli_query($connNew,$updateSql);

						if ($checkin_time != '') {
							$insertGrid = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET `checkin_time`='".$checkin_time."', `room_availability`='Checkin' where
							`id_fo_reservations` = '".$_REQUEST['resvId']."'  and `no_showoff`='0' and order_by_room='".$orderListValue['order_by_room']."'
							and id_mst_room_types='".$id_mst_room_types."' and DATE(dated) IN (".stripslashes($DateArray).")";
							$insertOrder = mysqli_query($connNew,$insertGrid);
						}

						$allocated_room_query = mysqli_query($connNew, "select * from ".FO_RESERVATIONS_DETAILS." where `id_mst_room_no_allocation`='".$id_mst_room_no_allocation."' and `no_showoff`='0' and DATE(dated) ='".$TodaysData."' and checkin_time is null");
						if (mysqli_num_rows($allocated_room_query) > 0) {
							$allocated_room_result = mysqli_fetch_object($allocated_room_query);
							mysqli_query($connNew, "update ".FO_RESERVATIONS_DETAILS." SET id_mst_room_no_allocation = '0' where id_fo_reservations = '".$allocated_room_result->id_fo_reservations."' and id_mst_room_no_allocation = '".$allocated_room_result->id_mst_room_no_allocation."'");
						}
						
						//fo_receipt update

						$update_receipt = "UPDATE `fo_receipt` SET 
								`id_fo_bill` = '$id_fo_bill',
								`id_fo_folio` = '$id_fo_folio'
							WHERE id_fo_bill = 0 
							AND id_fo_folio = 0 
							AND is_advance = '1'
							AND id_reservation = '".$_REQUEST['resvId']."'
							";

							mysqli_query($connNew,$update_receipt);

						//=================

						$updateRoomstatus = "UPDATE `mst_room_no_allocation` SET `room_status`='3'	  where id='".$id_mst_room_no_allocation."'";
						mysqli_query($connNew,$updateRoomstatus);
					}
				}
			}
		}

		echo "Check-in Processed Successfully";

	} else {
		$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and   DATE(dated)='".addslashes($TodaysData)."' and `checkin_status`='1' and `no_showoff`='0'");
		if (mysqli_num_rows($sqlOrderDetail2) > 0) {
			echo 'Check-in Status Already Update';
		} else {
			echo 'Invalid Check-in Date';
		}
	}