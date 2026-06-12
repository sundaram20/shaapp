<?php
include_once("../../config/auto_loader.php");


$RoomArray 	= json_decode($_REQUEST['RoomArray'],true);
		
$id_hotel = $_REQUEST['res_ledeger'];


$RoomListArray=array();
$masterArray	=array();

 $selectnew="SELECT * FROM ".TBL_CHARGES."  where charges_account='3' and id='".$id_hotel."' ";
				$resnew = mysqli_query($connNew,$selectnew);
				$resResult = mysqli_fetch_object($resnew);
				
				
 $sgst_percent	=	  selectColumn(TBL_CHARGES,'percentage','WHERE id="'.$resResult->id_mst_charges_sgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
 $cgst_percent	=	  selectColumn(TBL_CHARGES,'percentage','WHERE id="'.$resResult->id_mst_charges_cgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
		 $tax_percent=	$sgst_percent+$cgst_percent;
					
	
	
	
			
				 	$price2 					= $_REQUEST['res_tariff_per_room_per_night'];			
					
					
					
					$RoomListArray['taxes']  = round(($price2)*($tax_percent/100),2);	
					$RoomListArray['tariffperroom']	=	$price2;
					$RoomListArray['tariffperroomtax'] =$price2 +(($price2)*($tax_percent/100));
					$RoomListArray['chargespernight']  =(round($price2)* $noOfDays)*$roomLineWiseDate['noofRooms'];
					$RoomListArray['taxes_noofroom_noofdays']  =((($price2)*($tax_percent/100))* $noOfDays)*$roomLineWiseDate['noofRooms'];
					
			
	
	
		//debugData($RoomListArray);
		$total_taxes 		+=$RoomListArray[$row]['taxes_noofroom_noofdays'];
		$subtotal 		   +=$RoomListArray[$row]['chargespernight'];
		
		
		
		

	
	//debugData($RoomListArray);
	
	$masterArray['subtotal']			=$subtotal;
	$masterArray['total_discount']	  =$total_discount;
	
	$masterArray['additional_charges']  =$additional_charges;
	
	$masterArray['total_taxes']		 =$RoomListArray['taxes'] ;
	$masterArray['Inctotal']			   =$RoomListArray['tariffperroomtax'];
	$masterArray['total']			   =$RoomListArray['tariffperroomtax']*$_REQUEST['res_no_days']*$_REQUEST['res_no_of_Room'];
	$masterArray['payment_received']	=$payment_received;
	$masterArray['balance']			 =$balance;
	$masterArray['Data']				=$RoomListArray;
	$masterArray['uncode']				=$_REQUEST['uncode'];
	//print_r($masterArray);
	echo json_encode($masterArray);
 ?>


