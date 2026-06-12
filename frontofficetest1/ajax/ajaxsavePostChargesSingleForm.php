<?php
include_once("../../config/auto_loader.php");
//debugData($_REQUEST);
//die;
$id_hotel=$_REQUEST['id_mst_hotels_new']; 
if($_REQUEST['editid']==''){}
if($_REQUEST['editid']!='' && $_REQUEST['editid']>0){ //EDIT===========

 		$fo_reservations_id = 	$_REQUEST['editid'];
}


//Delete


	$r=0;
	foreach($_REQUEST['PostChargesDataArray'] as $roomInc12=>$ReservationData1){
		
		foreach($ReservationData1 as $ReservationData2){
			//debugData($ReservationData1);
		foreach($ReservationData2['resdate'] as $roomInc=>$loop2){
			
			
			$ReservationData2['id_reservation_detail'][$roomInc];
		if($ReservationData2['id_reservation_detail'][$roomInc]==0){ //ADD Detail Table
			
if($ReservationData2['res_unit_id'][$roomInc]=='1'){
	$res_unit	='Per room';
	}
	if($ReservationData2['res_unit_id'][$roomInc]=='2'){
	$res_unit	='Per Adult';
	}
	if($ReservationData2['res_unit_id'][$roomInc]=='3'){
	$res_unit	='Per Nos';
	}

  $roomdetails = " INSERT INTO `fo_reservations_addons_details` SET
						`id_fo_reservations` = '".$fo_reservations_id."', 
						`id_shop` = '".$_SESSION['shop']."',
						`id_mst_hotels` = '".$id_hotel."',
						`id_mst_room_no_allocation` = '".$ReservationData2['room_number_id'][$roomInc]."',
						`id_mst_charges` = '".$ReservationData2['ledger_id'][$roomInc]."',
						`additional_description` = '".$ReservationData2['additional_description'][$roomInc]."',
						`id_fo_folio_to` = '".$_REQUEST['id_fo_folio_to']."',
						`dated` = '".date('Y-m-d',strtotime($loop2))."',
						`days` = '".$ReservationData2['res_no_days_id'][$roomInc]."',
						`qty` = '".$ReservationData2['res_no_of_Room_id'][$roomInc]."',
						
						
		`unit` = '".$res_unit."',
		`rate` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
		`tax_percent` = '".$ReservationData2['res_no_days_id'][$roomInc]."',
		`tax_value` = '".$ReservationData2['perday_tax'][$roomInc]."',
		`amount` = '".($ReservationData2['tariff_per_room_per_night'][$roomInc]*$ReservationData2['res_no_of_Room_id'][$roomInc])."',
		`total` = '".$ReservationData2['total'][$roomInc]."' ";
				
				mysqli_query($connNew,$roomdetails);
	//echo '<br><br><br>'.$roomdetails;
		}else{
			//EDIT========================================> Detail Table
			//echo "==========edit";
			/* $roomdetails = " UPDATE  `".FO_RESERVATIONS_DETAILS."` SET				 
						
						
						
						`id_fo_rate_plan` = '".$ReservationData2['rate_plan_id'][$roomInc]."',						
						`id_mst_room_types` = '".$ReservationData2['room_type_id'][$roomInc]."',
						`room_quantity` = '1',
						`adults_per_room` = '".$ReservationData2['adult_per_room'][$roomInc]."',
						`child_without_bed` = '".$_REQUEST['bd_extrachild'][$roomInc]."',
						`extra_bed_price_per_day_per_room` = '".$_REQUEST['bd_extrabed'][$roomInc]."',
						`tariff_price_per_day_per_room` = '".$ReservationData2['tariff_per_room_per_night'][$roomInc]."',
						`food_price_per_day_per_room` = '".$_REQUEST['bd_food'][$roomInc]."',
						`tax_per_day_per_room` = '".$ReservationData2['perday_tax'][$roomInc]."'
						
						
						WHERE  
						`id_fo_reservations` = '".$fo_reservations_id."'  and `dated` = '".date('Y-m-d',strtotime($loop2))."'
						AND id='".$ReservationData2['id_reservation_detail'][$roomInc]."'
						
						";
				
				mysqli_query($connNew,$roomdetails);*/
			
			}
		}
		$r++;
	}

	}
	
  $ResultArray=array();

	$ResultArray['message']='Post Charges Created Successfully';
	$ResultArray['id_follio']=$_REQUEST['id_fo_folio_to'];
	echo  json_encode($ResultArray);
	
	die;
	
	
	
	
	
 ?>



