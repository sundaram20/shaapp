<?php
include_once("../../config/auto_loader.php");


$RoomArray 	= json_decode($_REQUEST['RoomArray'],true);
		
$id_hotel = $_REQUEST['id_hotel'];
$rrcounter = $_REQUEST['rrcounter'];

$reservation_date 	= explode(' to ',$_REQUEST['reservation_date']);

$checkinDate 		= $reservation_date[0];
$checkoutDate 		= $reservation_date[1];
$days =  abs((strtotime($reservation_date['0']) - strtotime($reservation_date['1']))/ 86400 );
if($days == '0'){
	$noOfDays = '1';
}else {
	$noOfDays = $days;
}

$RoomListArray=array();
$masterArray	=array();
$lenght=1;
foreach($RoomArray as $listi=>$roomLineWiseDate){
	//print_r($Data);
$roomLineWiseDate['plan'];
	
	$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
	$tax_detail	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$roomLineWiseDate['plan']."'");
	if($tax_detail==2){ //Tariff Per Room Exclusive Taxes
			
					$price2 					= $roomLineWiseDate['tariffperroom'];			
					
					$resNewTax2= mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price2."' and tax_slabs_to  >= '".$price2."') OR ( tax_slabs_from between '".$price2."' and '".$price2."') OR ( tax_slabs_to between '".$price2."' and '".$price2."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
					
					if(mysqli_num_rows($resNewTax2) >0 ){
					$rowNewTax2 = mysqli_fetch_object($resNewTax2);
					$RoomListArray[$listi]['taxes']  = round(($price2)*($rowNewTax2->tax_percent/100),2);	
					$RoomListArray[$listi]['tariffperroom']	=	$price2;
					$RoomListArray[$listi]['tariffperroomtax'] =$price2 +(($price2)*($rowNewTax2->tax_percent/100));
					$RoomListArray[$listi]['chargespernight']  =(round($price2)* $noOfDays)*$roomLineWiseDate['noofRooms'];
					$RoomListArray[$listi]['taxes_noofroom_noofdays']  =((($price2)*($rowNewTax2->tax_percent/100))* $noOfDays)*$roomLineWiseDate['noofRooms'];
					}
			}else{
				//Tariff Per Room inclusive Taxes
				
				
				
				$SelectTaxDateSQL		=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  order by start_date desc");
				$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
				$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
				$NewpriceValue=$roomLineWiseDate['tariffperroomtax'];
				$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
				
				if(mysqli_num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				$tax_new_percent	='1.'.$rowNewTaxInclution->tax_percent;
					
				$RoomListArray[$listi]['tariffperroom']	  =	round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP);
				$RoomListArray[$listi]['tariffperroomtax']   =   $NewpriceValue;
				$RoomListArray[$listi]['taxes']  = round($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP),2);
				$RoomListArray[$listi]['chargespernight']  =(round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP)* $noOfDays)*$roomLineWiseDate['noofRooms'];
				$RoomListArray[$listi]['taxes_noofroom_noofdays']  = (($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP))* $noOfDays)*$roomLineWiseDate['noofRooms'];
				
				}
				
			}
	$masterArray['length']=$lenght++;
	}
	foreach($RoomListArray as $row=>$BaseTotal){
		//debugData($BaseTotal);
		$total_taxes 		+=$RoomListArray[$row]['taxes_noofroom_noofdays'];
		$subtotal 		   +=$RoomListArray[$row]['chargespernight'];
		
		
		}
		
	$total_discount 		=	$_REQUEST['total_discount'];
	$additional_charges 	=	$_REQUEST['additional_charges'];
	$total 				=	($subtotal+$additional_charges+$total_taxes)-$total_discount;
	$payment_received 	=	$_REQUEST['payment_received'];
	$balance 			=	$total-$payment_received;
	
	//debugData($RoomListArray);
	
	$masterArray['subtotal']			=$subtotal;
	$masterArray['total_discount']	  =$total_discount;
	
	$masterArray['additional_charges']  =$additional_charges;
	
	$masterArray['total_taxes']		 =$total_taxes;
	$masterArray['total']			   =$total;
	$masterArray['payment_received']	=$payment_received;
	$masterArray['balance']			 =$balance;
	$masterArray['Data']				=$RoomListArray;
	//print_r($masterArray);
	echo json_encode($masterArray);
 ?>


