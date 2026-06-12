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
 $tax_percent	=	$sgst_percent+$cgst_percent;
			
				 	
					
				$NewpriceValue=$_REQUEST['res_tariff_per_room_per_night'];	//$roomLineWiseDate['tariffperroomtax'];
				
				
				 $tax_new_percent	='1'+($tax_percent/100);
					//echo round($NewpriceValue/$tax_new_percent);
				$RoomListArray['tariffperroom']	  =	round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP);
				$RoomListArray['tariffperroomtax']   =   $NewpriceValue;
				$RoomListArray['taxes']  = round($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP),2);
				$RoomListArray['chargespernight']  =(round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP)* $noOfDays)*$roomLineWiseDate['noofRooms'];
				$RoomListArray['taxes_noofroom_noofdays']  = (($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP))* $noOfDays)*$roomLineWiseDate['noofRooms'];
				
				//}
	
	
		//debugData($RoomListArray);
		$total_taxes 		+=$RoomListArray[$row]['taxes_noofroom_noofdays'];
		$subtotal 		   +=$RoomListArray[$row]['chargespernight'];
		
		
		
		

	$masterArray['subtotal']			=$subtotal;
	$masterArray['total_discount']	  =$total_discount;
	
	$masterArray['additional_charges']  =$additional_charges;
	
	$masterArray['total_taxes']		 =$RoomListArray['taxes'] ;
	$masterArray['Inctotal']			   =$RoomListArray['tariffperroom'];
	$masterArray['total']			   =$RoomListArray['tariffperroomtax']*$_REQUEST['res_no_days']*$_REQUEST['res_no_of_Room'];
	$masterArray['payment_received']	=$payment_received;
	$masterArray['balance']			 =$balance;
	$masterArray['Data']				=$RoomListArray;
	$masterArray['uncode']				=$_REQUEST['uncode'];
	//print_r($masterArray);
	echo json_encode($masterArray)
	
	/*//debugData($RoomListArray);
	
	$masterArray['subtotal']			=$subtotal;
	$masterArray['total_discount']	  =$total_discount;
	
	$masterArray['additional_charges']  =$additional_charges;
	
	$masterArray['total_taxes']		 =$RoomListArray['taxes'] ;
	$masterArray['total']			   =$RoomListArray['tariffperroom'];
	$masterArray['payment_received']	=$payment_received;
	$masterArray['balance']			 =$balance;
	$masterArray['Data']				=$RoomListArray;
	$masterArray['uncode']				=$_REQUEST['uncode'];
	//print_r($masterArray);
	echo json_encode($masterArray);*/
 ?>


