<?php
include_once("../../config/auto_loader.php");
include_once("../functions/function.php");

$to	= date('Y-m-d',strtotime($_REQUEST['re_reservation_date']));
$sqlOrder = mysqli_query($connNew,"Select  * from `".FO_RESERVATIONS."` WHERE id = '".$_REQUEST['id_reservation']."'");
$rowOrder = mysqli_fetch_object($sqlOrder);
$id_owner_room = $_REQUEST['id_owner_room'] ?? 0;

$sqlOrderDetail = mysqli_query($connNew,"Select  * from `".FO_RESERVATIONS_DETAILS."` WHERE id_fo_reservations= '".$_REQUEST['id_reservation']."' and id_mst_room_no_allocation = '".$id_owner_room."'");
$rowOrderDetail = mysqli_fetch_object($sqlOrderDetail);

$start = $rowOrder->checkout;				
$days = abs((strtotime($start) - strtotime($to)) / 86400);
if ($days == '0') {
	$noOfDays = '1';
} else {
	$noOfDays = $days;
}
$tariff_price_per_day_per_room = $_REQUEST['TariffPerRoomperNights'];
$tax_per_day_per_room = $_REQUEST['TaxPerRoomperNights'];
$total_tax = $tax_per_day_per_room * $noOfDays;
$sub_total = $tariff_price_per_day_per_room * $noOfDays;
$net_booking_amount = $sub_total + $total_tax;
$balance = $sub_total + $total_tax;

$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
$doc_table_name = FO_RESERVATIONS;
$date = date('Y-m-d');
$id_subsection = '1';
$id_shop = $_SESSION['shop'];
$docConfig = docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);

$insertGrid = "INSERT INTO ".FO_RESERVATIONS."  SET
	`doc_no`='".addslashes($docConfig['po_no'])."',
	`doc_date`='".date('Y-m-d',strtotime($date))."',
	`mdoc_no`=	'".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
	`doc_type` = '".addslashes($id_doc_type)."',
	`id_doc_type_configuration`	='".addslashes($docConfig['id_doc_type_configuration'])."',
	`booking_no`='".addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix'])."',
	`id_mst_shops`='".$_SESSION['shop']."',
	`id_shop_group`='1',
	`id_mst_country_lang`='0',
	`id_cart`='0',
	`id_mst_currency_base`='0',
	`id_mst_currency_transaction`='0',
	`conversion_rate`='1',
	`sub_total`='".$sub_total."',
	`net_booking_amount`='".$net_booking_amount."',
	`booking_confirm_date`='".date('Y-m-d')."',
	`tentative_hold_date`='".$rowOrder->tentative_hold_date."',
	`other_reference`='".$rowOrder->other_reference."',
	`id_mst_attributes_cancellation`='".$rowOrder->id_mst_attributes_cancellation."',
	`id_mst_attributes_amendment`='".$rowOrder->id_mst_attributes_amendment."',
	`no_of_days`='".$noOfDays."',
	`id_mst_hotels`='".$rowOrder->id_mst_hotels."',
	`id_mst_guest`='".$rowOrder->id_mst_guest."',
	`id_mst_attributes_company_group`='".$rowOrder->id_mst_attributes_company_group."',
	`id_mst_company`='".$rowOrder->id_mst_company."',
	`id_mst_company_contacts`='".$rowOrder->id_mst_company_contacts."',
	`id_mst_attributes_payment_status`='".$rowOrder->id_mst_attributes_payment_status."',
	`booking_status`='".$rowOrder->booking_status."',
	`room_tariff_price`='".$rowOrder->room_tariff_price."',
	`discount`='".$rowOrder->discount."',
	`total_addon_price`='".$rowOrder->total_addon_price."',
	`total_tax`='".$total_tax."',
	`amount_received`='0',
	`balance`='".$balance."',
	`booking_date`='".date('Y-m-d')."',
	`checkin`='".$start."',
	`checkout`='".$to."',
	`arrival_time`='".$rowOrder->arrival_time."',
	`arrival_from`='".$rowOrder->arrival_from."',
	`departing_to`='".date('Y-m-d')."',
	`pickup`='".$rowOrder->pickup."',
	`pickup_details`='".$rowOrder->pickup_details."',
	`id_mst_attributes_mode_of_travel`='".$rowOrder->id_mst_attributes_mode_of_travel."',
	`special_requests`='".$rowOrder->special_requests."',
	`internal_remarks`='".$rowOrder->internal_remarks."',
	`id_mst_attributes_segments`='".$rowOrder->id_mst_attributes_segments."',
	`id_mst_attributes_booking_source`='".$rowOrder->id_mst_attributes_booking_source."',
	`id_mst_attributes_booking_through`='".$rowOrder->id_mst_attributes_booking_through."',						
	`food_plan_price`='".$rowOrder->food_plan_price."',
	`extra_bed_price`='".$rowOrder->extra_bed_price."',
	`total_adults`='".$rowOrder->total_adults."',
	`total_child_with_bed`='".$rowOrder->total_child_with_bed."',
	`total_child_without_bed`='".$rowOrder->total_child_without_bed."',
	`date_created` = '".currenDateTime()."',
	`created_by` = '".$_SESSION['userId']."',
	`last_modified` = '".currenDateTime()."',
	`last_modified_by` = '".$_SESSION['userId']."'";

mysqli_query($connNew,$insertGrid);
$fo_reservations_id = mysqli_insert_id($connNew);

while (strtotime($start) != strtotime($to)) {

	$roomdetails = " INSERT INTO `".FO_RESERVATIONS_DETAILS."` SET
		`id_fo_reservations` = '".$fo_reservations_id."', 
		`id_shop` = '".$_SESSION['shop']."',
		`id_mst_hotels` = '".$rowOrderDetail->id_mst_hotels."',
		`id_mst_guest`='".$rowOrderDetail->id_mst_guest."',
		`id_mst_room_no_allocation` = '".$rowOrderDetail->id_mst_room_no_allocation."',
		`id_rate` = '',
		`plan` = '0',
		`id_fo_rate_plan` = '".$rowOrderDetail->id_fo_rate_plan."',
		`dated` = '".date('Y-m-d',strtotime($start))."',
		`id_mst_room_types` = '".$rowOrderDetail->id_mst_room_types."',
		`room_quantity` = '".$rowOrderDetail->room_quantity."',
		`adults_per_room` = '".$rowOrderDetail->adults_per_room."',
		`child_without_bed` = '".$rowOrderDetail->child_without_bed."',
		`extra_bed_price_per_day_per_room` = '".$rowOrderDetail->extra_bed_price_per_day_per_room."',
		`tariff_price_per_day_per_room` = '".$tariff_price_per_day_per_room."',
		`food_price_per_day_per_room` = '".$rowOrderDetail->food_price_per_day_per_room."',
		`tax_per_day_per_room` = '".$tax_per_day_per_room."',
		`room_availability` = 'Checkin',
		`unique_code` = '0'  ";

	mysqli_query($connNew,$roomdetails);		 
	$start = date('Y-m-d',strtotime('+1 day',strtotime($start)));
}
$resvId = $fo_reservations_id;
$id_mst_guest = $rowOrder->id_mst_guest;
$CheckInDate = $rowOrder->checkout;
include("SaveBillFolio.php");

$insertFolioGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 
	`checkin_status`='1',
	`id_fo_bill`='".$id_fo_bill."',
	`id_fo_folio`='".addslashes($id_fo_folio)."',
	`id_fo_folio_to`='".addslashes($id_fo_folio)."'
	where`id_fo_reservations` = '".$resvId."'
	and  DATE(dated)='".addslashes($CheckInDate)."'";
mysqli_query($connNew,$insertFolioGrid);
echo "recheckIn Successfully";
die;
?>