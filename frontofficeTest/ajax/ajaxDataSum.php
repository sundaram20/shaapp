<?php
include_once("../../config/auto_loader.php");

	$dataArray=array();
	
	//debugData($_REQUEST);
$id_fo_folio_to	= $_REQUEST['id_fo_folio_to'];


$Addtotal	=	  selectColumn('fo_reservations_addons_details','sum(total)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');

$total_rate	=	  selectColumn('fo_reservations_addons_details','sum(rate)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');
$tax_value	=	  selectColumn('fo_reservations_addons_details','sum(tax_value)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');

	foreach($_REQUEST['ReservationDataArray'] as $reservationRoom){
		foreach($reservationRoom as $reservationRoomType){
		$tariff_per_room_per_night +=  array_sum($reservationRoomType['tariff_per_room_per_night']);

		$perday_tax +=  array_sum($reservationRoomType['perday_tax']);

		$tariff_per_room_inclusive_tax +=  array_sum($reservationRoomType['tariff_per_room_inclusive_tax']);
		//debugData($reservationRoomType);
		}
		}

		foreach($_REQUEST['ReservationDataEditArray'] as $reservationRoom){
			foreach($reservationRoom as $reservationRoomType){
			$tariff_per_room_per_night +=  array_sum($reservationRoomType['tariff_per_room_per_night']);
			$additional_charges+=  array_sum($reservationRoomType['tariff_per_room_per_night']);
			$perday_tax +=  array_sum($reservationRoomType['perday_tax']);
	
			$tariff_per_room_inclusive_tax +=  array_sum($reservationRoomType['tariff_per_room_inclusive_tax']);
			//debugData($reservationRoomType);
			}
		}
		
		foreach($_REQUEST['PostChargesDataArray'] as $reservationRoom){
		foreach($reservationRoom as $reservationRoomType){
		$tariff_per_room_per_night +=  array_sum($reservationRoomType['tariff_per_room_per_night']);
		$additional_charges+=  array_sum($reservationRoomType['tariff_per_room_per_night']);
		$perday_tax +=  array_sum($reservationRoomType['perday_tax']);

		$tariff_per_room_inclusive_tax +=  array_sum($reservationRoomType['tariff_per_room_inclusive_tax']);
		//debugData($reservationRoomType);
		}
		}
		
		
		foreach($_REQUEST['EditPostChargesDataArray'] as $reservationRoom){
		foreach($reservationRoom as $reservationRoomType){
		$tariff_per_room_per_night +=  array_sum($reservationRoomType['tariff_per_room_per_night']);
		$additional_charges+=  array_sum($reservationRoomType['tariff_per_room_per_night']);
		$perday_tax +=  array_sum($reservationRoomType['perday_tax']);

		$tariff_per_room_inclusive_tax +=  array_sum($reservationRoomType['tariff_per_room_inclusive_tax']);
		//debugData($reservationRoomType);
		}
		}
	$dataArray['additional_charges'] =$additional_charges;	
	$dataArray['tariff_per_room_per_night'] =$tariff_per_room_per_night;//+$total_rate;
	$dataArray['perday_tax'] =$perday_tax;//+$tax_value;
	$dataArray['tariff_per_room_inclusive_tax'] =$tariff_per_room_inclusive_tax;//+$Addtotal;
	$dataArray['Balance'] =$tariff_per_room_inclusive_tax;//+$Addtotal;
	
	
	echo json_encode($dataArray);
 ?>



