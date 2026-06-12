<?php
include_once("../../config/auto_loader.php");
include_once("../../functions/inventoryUpdateFunctions.php");

//debugData($_REQUEST);
	//die;
	if($_REQUEST['editid']!=''){ //EDIT =========================================
		
		
		$to =	date("Y-m-d", strtotime($_REQUEST['checkout_extend_date']));
		$effectivefrom_date = date("Y-m-d", strtotime($_REQUEST['effectivefrom_date']));
		$old_room_no_allocation_id = $_REQUEST['old_room_no_allocation_id'];
		
		foreach($_REQUEST['reservation'] as $editDeatilID=>$dataList2){
			
			
			
			 $dataList2['tariffperroom'];
		while(strtotime($effectivefrom_date)<strtotime($to)){
			
				//echo '==='.$effectivefrom_date;
		
		
		
		 $insertGridDetail = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET  
				
						`id_mst_room_types` = '".$dataList2['roomtype']."',
							`id_fo_rate_plan` = '".$dataList2['plan']."',
						`tariff_price_per_day_per_room` = '".$dataList2['tariffperroom']."',
						
						`tax_per_day_per_room` = '".$dataList2['taxes']."',
						`id_mst_room_no_allocation` = '".$dataList2['id_mst_room_no_allocation']."',
						`id_mst_room_no_reserved` = '".$dataList2['id_mst_room_no_allocation']."'
					
					  	where order_by_room='".$dataList2['order_by_room']."'   and `dated` = '".date('Y-m-d',strtotime($effectivefrom_date))."' and `id_fo_reservations` = '".$_REQUEST['editid']."'";
						
		mysqli_query($connNew,$insertGridDetail);
			
		//=======Update Id Owner Room----------------------------------
			if($_REQUEST['owner_room']=='1'){
			 $id_fo_bill	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_fo_bill'," WHERE `id_fo_reservations` = '".$_REQUEST['editid']."' and order_by_room='".$dataList2['order_by_room']."'   and `dated` = '".date('Y-m-d',strtotime($effectivefrom_date))."'");
			
			$updateFobill = "UPDATE `fo_bill` SET  
				
						
						`id_owner_room` = '".$dataList2['id_mst_room_no_allocation']."'
						
					
					  	where id='".$id_fo_bill."'  ";
						
		mysqli_query($connNew,$updateFobill);
			}
		//=======Update Id Owner Room----------------------------------	
			
		if ($old_room_no_allocation_id != '') {
			if ($old_room_no_allocation_id != $dataList2['id_mst_room_no_allocation']) {
				$current_room_no_allocation_id = $dataList2['id_mst_room_no_allocation'];
				$update_current_room_no_allocation = "UPDATE mst_room_no_allocation SET room_status = '3' where id = '".$current_room_no_allocation_id."'";
				mysqli_query($connNew,$update_current_room_no_allocation);
				
				$update_old_room_no_allocation = "UPDATE mst_room_no_allocation SET room_status = '4' where id = '".$old_room_no_allocation_id."'";
				mysqli_query($connNew,$update_old_room_no_allocation);
			}
		}
		
			
		$effectivefrom_date = date ("Y-m-d", strtotime("+1 day", strtotime($effectivefrom_date)));	
			
		
			
		}
			}
		//=============================================
		 
		$sqlOrderDetail = mysqli_query($connNew,"Select  sum(`".FO_RESERVATIONS_DETAILS."`.tariff_price_per_day_per_room) as tariff_price_per_day_per_room,sum(`".FO_RESERVATIONS_DETAILS."`.tax_per_day_per_room) as tax_per_day_per_room from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".$_REQUEST['editid']."'");;
			
				$rowOrderDetail= mysqli_fetch_object($sqlOrderDetail);
				
			 $insertGrid = "UPDATE `".FO_RESERVATIONS."` SET   
				
					
					
					`sub_total`='".$rowOrderDetail->tariff_price_per_day_per_room."',
					`net_booking_amount`='".($rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room)."',								
					
					`balance`='".($rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room)."',
					
					`total_tax`='".$rowOrderDetail->tax_per_day_per_room."',					
									
				
					`last_modified` = '".currenDateTime()."',
					`last_modified_by` = '".$_SESSION['userId']."'  where id='".$_POST['editid']."'";
				
		
		
		mysqli_query($connNew,$insertGrid);	
				
		
		//=============================================
		
		
		$today = date('Y-m-d');
		$effectiveDate = date("Y-m-d", strtotime($_REQUEST['effectivefrom_date']));

		if ($effectiveDate < $today) {
			$final_date = $today;
		} else {
			$final_date = $effectiveDate;
		}
		
		$to =	date("Y-m-d", strtotime($_REQUEST['checkout_extend_date']));
		
		$res_id = $_REQUEST['editid'];
		
		
		$apiHotelID= selectColumn('fo_reservations','id_mst_hotels',"WHERE id = '".$res_id."'");
		
		$sqlMappingInventory = 'SELECT auto_sync_inv FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
									WHERE  B.hotel_id="'.$apiHotelID.'" AND B.status=1 and channel_type=1';
			$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
			$resultMapping   =    mysqli_fetch_object($QueryMapping);
			$autoInventoryUpdate=$resultMapping->auto_sync_inv;



			//if($autoInventoryUpdate==1){
				updateOTA($apiHotelID,$final_date,$to,$connNew);
			//}
		
		
		//=============================================
		
		echo 'Updated Successfully';
	}
 ?>



