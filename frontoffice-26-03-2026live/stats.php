<?php
include_once("../config/auto_loader.php"); 

//Room Stat
$folioArray=array();
	  $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		$DayCloseDate =date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated))); 
	 	// $DayCloseDate =date('Y-m-d',strtotime($rowNightAudit->dated)); 
		
		
		$sqlRoomNumber = mysqli_query($connNew,"SELECT DISTINCT 
		room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations,
		resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill,
		resdetails.order_by_room,
		fo_bill.status as occupanyStatus,resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room

FROM `mst_room_no_allocation` as room 
INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation 
INNER JOIN fo_bill as fo_bill ON fo_bill.id=resdetails.id_fo_bill 
 


WHERE   fo_bill.status='1'  and resdetails.`checkout_status`='0' and  resdetails.`no_showoff`='0'
		
		 ");
		if(mysqli_num_rows($sqlRoomNumber) >0 ){
			
				while($rowRoomNumbers= mysqli_fetch_object($sqlRoomNumber)){ //print_r($rowOrderDetail);
				
				
				
				$booking_no	= selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkin	= selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				$checkout	= selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowRoomNumbers->id_fo_reservations."'");
				
					$bill_checkout_status	= selectColumn(FO_BILL,'status'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");
					
					$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowRoomNumbers->id_mst_guest."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
	
					
					
					
					$roomNo	  = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowRoomNumbers->id."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowRoomNumbers->id_mst_room_types."'");
									
					$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$rowRoomNumbers->id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_fo_folio_to."'");
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;
					$folioArray[$RoomName][$rowRoomNumbers->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomName][$rowRoomNumbers->id]['room_no']=$roomNo;
					$folioArray[$RoomName][$rowRoomNumbers->id]['RoomName']=$RoomName;
					$folioArray[$RoomName][$rowRoomNumbers->id]['status']=$rowRoomNumbers->room_status;
					
					$folioArray[$RoomName][$rowRoomNumbers->id]['total_child']=$rowRoomNumbers->child_below_5_year+$rowRoomNumbers->child_above_5_year;
					$folioArray[$RoomName][$rowRoomNumbers->id]['child_below_5_year']=$rowRoomNumbers->child_below_5_year;
					$folioArray[$RoomName][$rowRoomNumbers->id]['child_above_5_year']=$rowRoomNumbers->child_above_5_year;
					$folioArray[$RoomName][$rowRoomNumbers->id]['adults_per_room']=$rowRoomNumbers->adults_per_room;
					
					
				if($bill_checkout_status!='2'){	
						
					
					$folioArray[$RoomName][$rowRoomNumbers->id]['id_mst_room_no_allocation']=$rowRoomNumbers->id;
					$folioArray[$RoomName][$rowRoomNumbers->id]['order_by_room']=$rowRoomNumbers->order_by_room;
					$folioArray[$RoomName][$rowRoomNumbers->id]['GuestName']=$GuestName!=''?$Title.' '.$GuestName.' '.$lastName:'';
					$folioArray[$RoomName][$rowRoomNumbers->id]['id_mst_guest']=$rowRoomNumbers->id_mst_guest;
					$folioArray[$RoomName][$rowRoomNumbers->id]['folio_mdoc_no']=$folio_mdoc_no!=''?$folio_mdoc_no:'';
					$folioArray[$RoomName][$rowRoomNumbers->id]['mdoc_no']=$booking_no!=''?$booking_no:'';
					$folioArray[$RoomName][$rowRoomNumbers->id]['id_fo_reservations']=$rowRoomNumbers->id_fo_reservations!=''?$rowRoomNumbers->id_fo_reservations:'';
					$folioArray[$RoomName][$rowRoomNumbers->id]['id_fo_view_folio']=$id_fo_folio_to;//$rowRoomNumbers->id_fo_reservations.'_'.$rowRoomNumbers->id_fo_bill.'_'.$rowRoomNumbers->id;
					
					
					$folioArray[$RoomName][$rowRoomNumbers->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$RoomName][$rowRoomNumbers->id]['id_fo_bill']=$rowRoomNumbers->id_fo_bill;
					
					$folioArray[$RoomName][$rowRoomNumbers->id]['Checkin']=$checkin!=''?date('d-m-Y',strtotime($checkin)):'';
					$folioArray[$RoomName][$rowRoomNumbers->id]['Checkout']=$checkout!=''?date('d-m-Y',strtotime($checkout)):'';
					
					
					
				
				}
				
					
				}
				
				
		}
		$is_force_checkout = selectColumn('mst_shops','force_system_date_as_checkout_date'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
		$check_checkout_date = 0;
		$system_date = strtotime(date('Y-m-d'));
		if ($is_force_checkout && $system_date != strtotime($today)) {
		  $check_checkout_date = 1;
		}
		$demoData = array();
		foreach($folioArray as $RoomName=>$Array1){
			
			foreach($Array1 as $rowid=>$Array2){
				
				//echo '==='.$Array2['Checkout'];
				$is_pre_checkout = (strtotime($Array2['Checkout']) > strtotime($DayCloseDate)) ? '1' : '0';
				$demoData[]= 
        array("id"=>encryptor(encrypt,'12'),'type'=>$Array2['RoomName'],'room_no' => $Array2['room_no'],'status' => $Array2['status'],'res_id'=>$Array2['mdoc_no'],'guest' =>$Array2['GuestName'],'folio' => $Array2['folio_mdoc_no'],'checkin'=>$Array2['Checkin'],
        'checkout'=>$Array2['Checkout'],'action'=>'1','id_fo_bill'=>$Array2['id_fo_bill'],'id_fo_reservations'=>encryptor(encrypt,$Array2['id_fo_reservations']),'id_mst_guest'=>encryptor(encrypt,$Array2['id_mst_guest']),'id_fo_view_folio'=>$Array2['id_fo_view_folio'],'id_mst_room_no_allocation'=>$Array2['id_mst_room_no_allocation'],'order_by_room'=>$Array2['order_by_room'],'child_below_5_year'=>$Array2['child_below_5_year'],'child_above_5_year'=>$Array2['child_above_5_year'],'adults_per_room'=>$Array2['adults_per_room'],'total_child'=>$Array2['total_child'], 'is_pre_checkout' => $is_pre_checkout, 'check_checkout_date' => $check_checkout_date);
				}
			
			}
		//debugData($folioArray);


echo json_encode($demoData);