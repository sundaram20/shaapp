<?php
include_once("../config/auto_loader.php"); 
/*if($_REQUEST['date'] != ''){
    $DateExplode = explode(' to ',$_REQUEST['date']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
    //$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
      
    $searchDocumentType .= " AND DATE(`dated`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  } else{
      $searchDocumentType .= " AND DATE(`dated`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
	
if($_REQUEST['reference_no_search'] != ''){
	
	$searchDocumentType .= " AND pp.`booking_no` ='".addslashes($_REQUEST['reference_no_search'])."'";

}
if($_REQUEST['other_reference_no_search'] != ''){
	
	$searchDocumentType .= " AND pp.`reference` ='".addslashes($_REQUEST['other_reference_no_search'])."'";

}
if($_REQUEST['date']==''){
	
	
	if($_REQUEST['reservation_id_arrivals'] == '' && $_REQUEST['other_reference_no_arrivals'] == ''){
	
	 $newDate	=	date('Y-m-d');
	$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";
	}
	
	
}
*/

if($_REQUEST['date']!=''){
	$DateExplode = explode(' to ',$_REQUEST['date']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
	
	
	//$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	//$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";
	
	
	$searchDocumentTypeDetails .= " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  } else{
      $searchDocumentTypeDetails .= " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
	
	
	
if($_REQUEST['reference_no_search'] != ''){	
	$searchDocumentType = " AND `".FO_RESERVATIONS."`.`booking_no` ='".addslashes($_REQUEST['reference_no_search'])."'";

}
if($_REQUEST['other_reference_no_search'] != ''){	
	$searchDocumentType = " AND `".FO_RESERVATIONS."`.`other_reference` ='".addslashes($_REQUEST['other_reference_no_search'])."'";

}

			
			 $sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* 
  FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  
  where   
   ".FO_RESERVATIONS_DETAILS.".checkin_status ='0'   and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  ".$searchDocumentType." ".$searchDocumentTypeDetails."
 
  group by id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";
  
  $sqlOrderDetail = mysqli_query($connNew,$sql);
			if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){  
					
					$reservation_id	=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$other_reservation_id	=	selectColumn(FO_RESERVATIONS,'other_reference'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$checkin	=	selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$checkout	=	selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					
					$id_mst_company	=	selectColumn(FO_RESERVATIONS,'id_mst_company'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$company_name	=	selectColumn('mst_company','name'," WHERE `id` = '".$id_mst_company."'");
					
					$id_mst_guest	=	selectColumn(FO_RESERVATIONS,'id_mst_guest'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
					$Guest_name	=	selectColumn('mst_guest','first_name'," WHERE `id` = '".$id_mst_guest."'").' '.selectColumn('mst_guest','last_name'," WHERE `id` = '".$id_mst_guest."'");
					
					$room_name	=	selectColumn('mst_room_types','name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					
					$RoomWiseArray[]=array("id"=>$rowOrderDetail->id_fo_reservations,"other_reservation_id"=>$other_reservation_id,"reservation_id"=>$reservation_id,"roomType"=>$room_name,"guest"=>$Guest_name,"source"=>$company_name,"checkIn"=>date('d-m-Y',strtotime($checkin)),"checkOut"=>date('d-m-Y',strtotime($checkout)),"NoOfRoom"=>$rowOrderDetail->room_quantity,"pending"=>"2","id_mst_room_types"=>$rowOrderDetail->id_mst_room_types,"id_mst_hotels"=>$rowOrderDetail->id_mst_hotels);
				}}
$demoData = $RoomWiseArray;

/*array(
    array("id"=>encryptor(encrypt,'12'),"reservation_id"=>"eee","roomType"=>"Garden Room","guest"=>"Shubhi","source"=>"cox and kings","checkIn"=>"27-09-2022","checkOut"=>"30-09-2022","booked"=>"4","pending"=>"2"),
    array("id"=>"2","reservation_id"=>"wh202","roomType"=>"Maharani Suit","guest"=>"Hitesh","source"=>"sita travel","checkIn"=>"18-03-2021","checkOut"=>"25-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"3","reservation_id"=>"wh203","roomType"=>"Deluxe Room","guest"=>"Sumit","source"=>"cox and kings","checkIn"=>"20-03-2021","checkOut"=>"24-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>encryptor(encrypt,'21'),"reservation_id"=>"wh204","roomType"=>"Maharani Suit","guest"=>"Sundaram","source"=>"cox and kings","checkIn"=>"25-03-2021","checkOut"=>"27-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"5","reservation_id"=>"wh205","roomType"=>"Garden Room","guest"=>"Shafeer","source"=>"cox and kings","checkIn"=>"5-03-2021","checkOut"=>"9-03-2021","booked"=>"4","pending"=>"2"),
    array("id"=>"6","reservation_id"=>"wh206","roomType"=>"Deluxe Room","guest"=>"Vipin","source"=>"cox and kings","checkIn"=>"10-03-2021","checkOut"=>"12-03-2021","booked"=>"4","pending"=>"2"),$RoomWiseArray
);*/

echo json_encode($demoData);

?>