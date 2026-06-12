<?php
include_once("../../config/auto_loader.php");

	
	if($_REQUEST['editid']!=''){ //EDIT =========================================
		$insertGrid = "UPDATE `".FO_RESERVATIONS."` SET   
				
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
				
		
		
		mysqli_query($connNew,$insertGrid);
		
		
		foreach($_REQUEST['reservation'] as $editDate=>$dataList){
			
			foreach($dataList as $editDeatilID=>$data){
						
			$insertGridDetail = "UPDATE `".FO_RESERVATIONS_DETAILS."` SET   
				
				
						`tariff_price_per_day_per_room` = '".$data['tariffperroom']."',
						
						`tax_per_day_per_room` = '".$data['taxes']."'
					
					  	where id='".$editDeatilID."'   and `dated` = '".date('Y-m-d',strtotime($editDate))."'";
				
		
		
		mysqli_query($connNew,$insertGridDetail);
			
			
			
			
			}
			}
		
		echo 'Updated Successfully';
	}
 ?>



