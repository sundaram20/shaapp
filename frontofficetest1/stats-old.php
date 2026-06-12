<?php
include_once("../config/auto_loader.php"); 

//Room Stat
$folioArray=array();
	  
	 
		$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.*,`".FO_RESERVATIONS."`.booking_no,`".FO_RESERVATIONS."`.checkin,`".FO_RESERVATIONS."`.checkout from `".FO_RESERVATIONS_DETAILS."` INNER JOIN `".FO_RESERVATIONS."` ON `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations =`".FO_RESERVATIONS."`.id where `".FO_RESERVATIONS_DETAILS."`.id_fo_bill!='0' group by `".FO_RESERVATIONS_DETAILS."`.id_mst_room_types,`".FO_RESERVATIONS_DETAILS."`.id_fo_reservations order by  `".FO_RESERVATIONS_DETAILS."`.id_mst_room_types asc ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$GuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
					$lastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$rowOrderDetail->id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_fo_folio_to."'");
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;	
					
					$folioArray[$RoomName][$rowOrderDetail->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomName][$rowOrderDetail->id]['room_no']=$roomNo;
					$folioArray[$RoomName][$rowOrderDetail->id]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;
					$folioArray[$RoomName][$rowOrderDetail->id]['RoomName']=$RoomName;
					$folioArray[$RoomName][$rowOrderDetail->id]['GuestName']=$GuestName.' '.$lastName;
					$folioArray[$RoomName][$rowOrderDetail->id]['id_mst_guest']=$rowOrderDetail->id_mst_guest;
					$folioArray[$RoomName][$rowOrderDetail->id]['folio_mdoc_no']=$folio_mdoc_no;
					$folioArray[$RoomName][$rowOrderDetail->id]['mdoc_no']=$rowOrderDetail->booking_no;
					$folioArray[$RoomName][$rowOrderDetail->id]['id_fo_reservations']=$rowOrderDetail->id_fo_reservations;
					$folioArray[$RoomName][$rowOrderDetail->id]['id_fo_view_folio']=$rowOrderDetail->id_fo_reservations.'_'.$rowOrderDetail->id_fo_bill.'_'.$rowOrderDetail->id_mst_room_no_allocation;
					
					
					$folioArray[$RoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					
					$folioArray[$RoomName][$rowOrderDetail->id]['Checkin']=date('Y-m-d',strtotime($rowOrderDetail->checkin));
					$folioArray[$RoomName][$rowOrderDetail->id]['Checkout']=date('Y-m-d',strtotime($rowOrderDetail->checkout));

				
					
				}
				
				
		}
		$demoData = array();
		foreach($folioArray as $RoomName=>$Array1){
			
			foreach($Array1 as $rowid=>$Array2){
				
				//echo '==='.$Array2['Checkout'];
				$demoData[]= 
        array("id"=>encryptor(encrypt,'12'),'type'=>$Array2['RoomName'],'room_no' => $Array2['room_no'],'status' => '1','res_id'=>$Array2['mdoc_no'],'guest' =>$Array2['GuestName'],'folio' => $Array2['folio_mdoc_no'],'checkin'=>$Array2['Checkin'],
        'checkout'=>$Array2['Checkout'],'action'=>'1','id_fo_reservations'=>encryptor(encrypt,$Array2['id_fo_reservations']),'id_mst_guest'=>encryptor(encrypt,$Array2['id_mst_guest']),'id_fo_view_folio'=>$Array2['id_fo_view_folio']);
				}
			
			}
		//debugData($folioArray);
$demoData11 = array(
        array("id"=>encryptor(encrypt,'12'),'type'=>'Deluxe Room','room_no' => '101','status' => '2','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'27-Sep-2022',
        'checkout'=>'30-Sep-2022','action'=>'1'),

        array('id'=>'2','type'=>'Deluxe Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Deluxe Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

         array("id"=>encryptor(encrypt,'21'),'type'=>'Deluxe Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),
  array('id'=>'5','type'=>'Deluxe Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),

        array("id"=>encryptor(encrypt,'12'),'type'=>'Suit Room','room_no' => '101','status' => '1','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'2','type'=>'Suit Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Suit Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),
  array('id'=>'5','type'=>'Deluxe Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),

        array("id"=>encryptor(encrypt,'12'),'type'=>'Suit Room','room_no' => '101','status' => '1','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'2','type'=>'Suit Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Suit Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array("id"=>encryptor(encrypt,'21'),'type'=>'Suit Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),
        array("id"=>encryptor(encrypt,'21'),'type'=>'Suit Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),
        array('id'=>'5','type'=>'Deluxe Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),

        array("id"=>encryptor(encrypt,'12'),'type'=>'Suit Room','room_no' => '101','status' => '1','res_id'=>'wh201','guest' => 'Shubhi','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'2','type'=>'Suit Room','room_no' => '102','status' => '1','res_id'=>'wh202','guest' => 'Hitesh Aloney','folio' => 'FO-89','checkin'=>'13-Mar-2020','checkout'=>'15-Mar-2020','action'=>'1'),

        array('id'=>'3','type'=>'Suit Room','room_no' => '103','status' => '1','res_id'=>'wh203','guest' => 'Sumit Kumar','folio' => 'FO-99','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),

        array("id"=>encryptor(encrypt,'21'),'type'=>'Suit Room','room_no' => '104','status' => '1','res_id'=>'wh204','guest' => 'Sundaram','folio' => 'FO-89','checkin'=>'13-Mar-2020',
        'checkout'=>'15-Mar-2020','action'=>'1'),
		

        array('id'=>'5','type'=>'Suit Room','room_no' => '105','status' => '2','res_id'=>'','guest' => '','folio' => '','checkin'=>'','checkout'=>'','action'=>'0'),
);

echo json_encode($demoData);