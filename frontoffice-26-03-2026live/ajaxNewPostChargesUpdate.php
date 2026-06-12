<?php
include_once "../config/auto_loader.php";
 include_once "../includes/header.php"; ?>
<?php
include_once "../includes/left.php";
?>

<div class="content-wrapper"> 
	<?php $session=$_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12"> 
                <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		            <?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12 dd-f"></div>
            <div class="col-md-4 col-xs-12 tb-br">
                <?php echo breadCrumbs(); ?>
            </div>
        </div>
    </section>
    <?php
$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to>0 and tax_value=0");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){ 
		
$id_mst_charges = $rowOrderDetail->id_mst_charges;

echo $total = ($rowOrderDetail->total/$rowOrderDetail->days);//$rowOrderDetail->total.'========'.$rowOrderDetail->days.'========>'.$rowOrderDetail->qty.'===ID=>'.$rowOrderDetail->id;
$RoomListArray=array();
$masterArray	=array();


 $selectnew="SELECT * FROM ".TBL_CHARGES."  where charges_account='3' and id='".$id_mst_charges."' ";
				$resnew = mysqli_query($connNew,$selectnew);
				$resResult = mysqli_fetch_object($resnew);
				
				
$sgst_percent	=	  selectColumn(TBL_CHARGES,'percentage','WHERE id="'.$resResult->id_mst_charges_sgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$cgst_percent	=	  selectColumn(TBL_CHARGES,'percentage','WHERE id="'.$resResult->id_mst_charges_cgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
 $tax_percent	=	$sgst_percent+$cgst_percent;
			
				 	
					
				$NewpriceValue=$total;	//$roomLineWiseDate['tariffperroomtax'];
				
				
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
		
		
		
		

	$masterArray['subtotal']			=($rowOrderDetail->total/$rowOrderDetail->days)/$rowOrderDetail->qty;
	$masterArray['total_discount']	  =$total_discount;
	
	$masterArray['additional_charges']  =$additional_charges;
	
	$masterArray['total_taxes']		 =$RoomListArray['taxes'] ;
	$masterArray['Inctotal']			   =$RoomListArray['tariffperroom'];
	$masterArray['total']			   =$RoomListArray['tariffperroomtax']*$rowOrderDetail->days*$rowOrderDetail->qty;
	$masterArray['payment_received']	=$payment_received;
	$masterArray['balance']			 =$balance;
	$masterArray['Data']				=$RoomListArray;
	$masterArray['uncode']				=$_REQUEST['uncode'];
	
	
	 echo '<br>'.$roomdetails2 = " update  `fo_reservations_addons_details` SET		
	
		`tax_value` = '".$masterArray['total_taxes']."',
		`amount` = '".$masterArray['subtotal']."',
		`rate` = '".($masterArray['subtotal']-$masterArray['total_taxes'])."' 
		
		where `id` = '".$rowOrderDetail->id."' 
		";
				
			mysqli_query($connNew,$roomdetails2);
	
	
	
	echo '<pre>';print_r($masterArray);echo '</pre>';
		//echo json_encode($masterArray);
		}
		}
	//print_r($masterArray);
	
	
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


 <?php include_once("../includes/footer.php")?>