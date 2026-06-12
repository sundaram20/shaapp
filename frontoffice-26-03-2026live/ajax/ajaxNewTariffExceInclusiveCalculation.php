<?php
include_once("../../config/auto_loader.php");


$RoomArray 	= json_decode($_REQUEST['RoomArray'],true);
		
$id_hotel = $_REQUEST['id_hotel'];


$RoomListArray=array();
$masterArray	=array();


	//print_r($roomLineWiseDate);
$_REQUEST['res_rate_plan'];
	
	$SelectTaxDateSQL		= executeSql("SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  AND start_date <= CURDATE() and status='1' order by start_date desc");
		$SelectTaxDateRow 		= $db->fetch_object2($SelectTaxDateSQL);
		$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
	 $tax_detail	=	selectColumn(TBL_RATE_PLAN,'tax_detail'," WHERE `id` = '".$_REQUEST['res_rate_plan']."'");
	
	 //Tariff Per Room Exclusive Taxes
			
				 	$price2 					= $_REQUEST['res_tariff_per_room_per_night'];			
					
					/*$resNewTax2= mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_slabs_from <=  '".$price2."' and tax_slabs_to  >= '".$price2."') OR ( tax_slabs_from between '".$price2."' and '".$price2."') OR ( tax_slabs_to between '".$price2."' and '".$price2."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
					
					if(mysqli_num_rows($resNewTax2) >0 ){
					$rowNewTax2 = mysqli_fetch_object($resNewTax2);
					
					$tax_new_percent	='1.'.$rowNewTax2->tax_percent;
					$RoomListArray['taxes']  = round(($price2)*($tax_new_percent/100),2);	
					$RoomListArray['tariffperroom']	=	$price2;
					$RoomListArray['tariffperroomtax'] =$price2 +(($price2)*($tax_new_percent/100));
					$RoomListArray['chargespernight']  =(round($price2)* $noOfDays)*$roomLineWiseDate['noofRooms'];
					$RoomListArray['taxes_noofroom_noofdays']  =((($price2)*($tax_new_percent/100))* $noOfDays)*$roomLineWiseDate['noofRooms'];
					}*/
				$SelectTaxDateSQL		=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_DATE_RULE."` where id_shop='".addslashes($_SESSION['shop'])."'  AND start_date <= CURDATE() and status='1' order by start_date desc");
				$SelectTaxDateRow 		= mysqli_fetch_object($SelectTaxDateSQL);
				$SlectedDateNewTax_id	= $SelectTaxDateRow->id;	
				$NewpriceValue=$_REQUEST['res_tariff_per_room_per_night'];	//$roomLineWiseDate['tariffperroomtax'];
				$resNewTaxInclution=  mysqli_query($connNew,"SELECT * FROM `".TBL_TAX_RULE."` where id_shop='".addslashes($_SESSION['shop'])."' AND ((tax_inc_slabs_from <=  '".$NewpriceValue."' and tax_inc_slabs_to  >= '".$NewpriceValue."') OR ( tax_inc_slabs_from between '".$NewpriceValue."' and '".$NewpriceValue."') OR ( tax_inc_slabs_to between '".$NewpriceValue."' and '".$NewpriceValue."')) and tax_uniqueid='".$SlectedDateNewTax_id."'  order by start_date desc");
				
				if(mysqli_num_rows($resNewTaxInclution) >0 ){
				$rowNewTaxInclution = mysqli_fetch_object($resNewTaxInclution);
				$tax_new_percent	=1 + ($rowNewTaxInclution->tax_percent / 100); //'1.'.$rowNewTaxInclution->tax_percent;
					
				$RoomListArray['tariffperroom']	  =	round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP);
				$RoomListArray['tariffperroomtax']   =   $NewpriceValue;
				$RoomListArray['taxes']  = round($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP),2);
				$RoomListArray['chargespernight']  =(round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP)* $noOfDays)*$roomLineWiseDate['noofRooms'];
				$RoomListArray['taxes_noofroom_noofdays']  = (($NewpriceValue-round(($NewpriceValue/$tax_new_percent),2,PHP_ROUND_HALF_UP))* $noOfDays)*$roomLineWiseDate['noofRooms'];
				
				}
	
	
		//debugData($RoomListArray);
		$total_taxes 		+=$RoomListArray[$row]['taxes_noofroom_noofdays'];
		$subtotal 		   +=$RoomListArray[$row]['chargespernight'];
		
		
		
		

	
	//debugData($RoomListArray);
	
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
	echo json_encode($masterArray);
 ?>


