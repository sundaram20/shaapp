<?php
include_once("../../config/auto_loader.php");
//debugData($_REQUEST);
	//die;
	if($_REQUEST['editid']!=''){ //EDIT =========================================
		/*$insertGrid = "UPDATE `".FO_RESERVATIONS."` SET   
				
					`id_mst_guest`='".$_REQUEST['id_mst_guest']."',
					`id_mst_attributes_company_group`='".$_REQUEST['id_mst_attributes_company_group']."',
					`id_mst_company`='".$_REQUEST['id_mst_company']."',
					`id_mst_company_contacts`='".$_REQUEST['id_mst_company_contacts']."',
					
					`sub_total`='".$_REQUEST['room_tariff_price']."',
					`net_booking_amount`='".$_REQUEST['net_booking_amount_edit']."',								
					
					`room_tariff_price`='".$_REQUEST['room_tariff_price']."',
					`discount`='".$_REQUEST['dicount']."',
					`total_addon_price`='".$_REQUEST['total_addon_price']."',
					`total_tax`='".$_REQUEST['total_tax_edit']."',					
					`balance`='".$_REQUEST['balance_edit']."',					
				
					`last_modified` = '".currenDateTime()."',
					`last_modified_by` = '".$_SESSION['userId']."'  where id='".$_POST['editid']."'";
				
		
		
		mysqli_query($connNew,$insertGrid);*/
		
		$to =	date("Y-m-d", strtotime($_REQUEST['checkout_extend_date']));
		$effectivefrom_date = date("Y-m-d", strtotime($_REQUEST['effectivefrom_date']));
		$old_room_no_allocation_id = $_REQUEST['old_room_no_allocation_id'];
		
		foreach($_REQUEST['reservation'] as $editDeatilID=>$dataList2){
			
			
			
			 $dataList2['tariffperroom'];
		while(strtotime($effectivefrom_date)<strtotime($to)){
			
				//echo '==='.$effectivefrom_date;
		/*	foreach($dataList as $editDeatilID=>$data){
						
			echo $insertGridDetail = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET  
								
						`tariff_price_per_day_per_room` = '".$data['tariffperroom']."',
						
						`tax_per_day_per_room` = '".$data['taxes']."'
					
					  	where id='".$editDeatilID."'   and `dated` = '".date('Y-m-d',strtotime($editDate))."'";
						
		mysqli_query($connNew,$insertGridDetail);*/
		
		
		 $insertGridDetail = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET  
				
						`id_mst_room_types` = '".$dataList2['roomtype']."',
							`id_fo_rate_plan` = '".$dataList2['plan']."',
						`tariff_price_per_day_per_room` = '".$dataList2['tariffperroom']."',
						
						`tax_per_day_per_room` = '".$dataList2['taxes']."',
						`id_mst_room_no_allocation` = '".$dataList2['id_mst_room_no_allocation']."'
					
					  	where order_by_room='".$dataList2['order_by_room']."'   and `dated` = '".date('Y-m-d',strtotime($effectivefrom_date))."' and `id_fo_reservations` = '".$_REQUEST['editid']."'";
						
		mysqli_query($connNew,$insertGridDetail);

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
		echo 'Updated Successfully';
	}
 ?>



