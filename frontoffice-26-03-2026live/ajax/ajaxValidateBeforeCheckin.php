<?php
	include_once("../../config/auto_loader.php");
	
	//echo $resvId;
	

	//debugData($_REQUEST);
	
	
	
// Decode JSON into array
$data = json_decode($_REQUEST['expected_arrivals_rooms'], true);

// Extract keys into a new array
$keys = array_column($data, 'key');

// Convert to comma-separated string
 $RoomNoArray = implode(',', $keys);


	
	//$jsondeocde 			= 	json_decode($_REQUEST['bookedRoom'], true);
	//$RoomNoArray		   =	explode(',',$_REQUEST['dataselected']);
	$id_mst_room_types	 =	$_REQUEST['id_mst_room_types'];
	//debugData($RoomNoArray);
	//die;
		 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
		 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
		 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
		  $NightAuditDated = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
					 
					 
		$TodaysData	=	$NightAuditDated;//date('Y-m-d');  
	//echo "Select `".TBL_ROOMNO."`.* from `".TBL_ROOMNO."` where room_no In (".$RoomNoArray.")  and id_mst_room_types IN (".$id_mst_room_types.") and room_status NOT IN (4,2)";
		
$response=array();
$sqlCheckVacant = mysqli_query($connNew,"Select `".TBL_ROOMNO."`.* from `".TBL_ROOMNO."` where id In (".$RoomNoArray.")  and id_mst_room_types IN (".$id_mst_room_types.") and room_status NOT IN (4,2)");
	
if(mysqli_num_rows($sqlCheckVacant) >0 ){
		//while($rowReservationVac= mysqli_fetch_object($sqlCheckVacant)){
			
			//echo '<pre>';print_r($rowReservationVac);echo '<pre>';
		//}
	
	$response = array(
        'allow' => false,
        'message' => "Room Is Occupied Checkout And Try."
    );
		
	

}else{
	$response = array('allow' => true);
	}

echo json_encode($response);
			
						
		
	