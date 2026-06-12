<?php include_once("../../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INVENTORY,'update');
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$hotelId = $_REQUEST['hotelId'];
$roomId = $_REQUEST['roomId'];
$blocked_hotel = $_REQUEST['blocked_hotel'];
$crs_available = $_REQUEST['crs_available'];
$online_allocation = $_REQUEST['online_allocation'];
$allocation_date = $_REQUEST['allocation_date'];
$type=$_REQUEST['type'];
$start_date=$_REQUEST['start_date'];
$end_date=$_REQUEST['end_date'];

if($type==1){
	$res = "UPDATE `".TBL_INVENTORY."` set 
						offline_block_hotel='".addslashes($blocked_hotel)."',
						crs_available='".addslashes($crs_available)."',
						online_allocation='".addslashes($online_allocation)."',
						color='#f39c12',
						`last_modified` = '".currenDateTime()."'
 						where  allocation_date = '".addslashes($allocation_date)."' and
						 room_id ='".addslashes($roomId)."' and
						 hotel_id ='".addslashes($hotelId)."'";
	
	if(executeSql($res)){
		echo 'Success';

		$hotelChk = selectColumn(TBL_HOTEL_MAPPING,'booking_engine_id'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and status='1'");
		
		if($hotelChk !=""){
			$mapId = selectColumn(TBL_HOTEL_MAPPING,'id'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and status='1'");	 

			$roomChk = selectColumn(TBL_ROOM_MAPPING,'booking_engine_id'," WHERE `room_id` = '".$roomId."' AND hotel_mapping_id='".$mapId."' ");

			if($roomChk !="" && $_REQUEST['OTA_req']==1){
				$xml = '<?xml version="1.0" encoding="UTF8"?>
			 	<OTA_HotelInvCountNotifRQ Version="1.0" Target="Production" TimeStamp="'.date('Y-m-d H:i:s').'" EchoToken="'.md5(rand(0000,9999)).'" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
			 	    <POS>
			 	        <Source>
			 	            <RequestorID MessagePassword="welcome@123" ID="sundaram@globalcomputersolutions.in" />
			 	        </Source>
			 	    </POS>
			 					    
			 	    <Inventories HotelName="'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").'" HotelCode="'.$hotelChk.'">';

			 	$xml .= '
		       		<Inventory>
		          		<StatusApplicationControl  End="'.addslashes($allocation_date).'" Start="'.addslashes($allocation_date).'" InvTypeCode="'.$roomChk.'" />
			           	<InvCounts >
							<InvCount Count="'.($online_allocation<=0?0:$online_allocation).'" />
						</InvCounts>
					</Inventory> ';
				$xml.='</Inventories>
							</OTA_HotelInvCountNotifRQ>';

				////////////////////  LIVE CHANNEL URL   ////////////////////////////
				$url = "http://cm.resavenue.com/channelcontroller/PmsRateInventoryNotification";

				////////////////////  LOCAL URL   ////////////////////////////
				//$url = "http://localhost:8181/roomstatushub/channel/apiResponseInventory.php";
					
				$headers = array(
				    "Content-type: text/xml",
				    "Content-length: " . strlen($xml),
				    "Connection: close",
				);

				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$dataResp = curl_exec($ch);
				
										
				if(curl_errno($ch)){
				    print curl_error($ch);
				}
				else{
				    curl_close($ch);
				    $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,date_created) VALUES('".$_REQUEST['hotelId']."','".$xml."','".date('Y-m-d h:i:s')."') ";
				    mysqli_query($conn,$reqSql);

				    if($dataResp != ""){
				    	$sqlResp="INSERT INTO `api_inv_response` (response,date_created) VALUES('".$dataResp."','".date("Y-m-d h:i:s")."') ";
				    	mysqli_query($conn,$sqlResp);
				    }
				}	


			}		
		}


	}else{
	    echo 'error';
	}


}elseif($type==2){
	$date = new DateTime($end_date); 
	$date->modify("-1 day");
	$flagHotel=0;

	$sqlQ = "SELECT * FROM `fs_inventory` WHERE `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' AND allocation_date='".addslashes($start_date)."' and status='1' ";

	$availableInventory = selectSql(TBL_INVENTORY,'crs_available'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' AND allocation_date='".addslashes($start_date)."' and status='1'"); 

	$hotelChk = selectColumn(TBL_HOTEL_MAPPING,'booking_engine_id'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and status='1'");
	
	if($hotelChk !=""){
		$xml = '<?xml version="1.0" encoding="UTF8"?>
			 	<OTA_HotelInvCountNotifRQ Version="1.0" Target="Production" TimeStamp="'.date('Y-m-d H:i:s').'" EchoToken="'.md5(rand(0000,9999)).'" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
			 	    <POS>
			 	        <Source>
			 	            <RequestorID MessagePassword="welcome@123" ID="sundaram@globalcomputersolutions.in" />
			 	        </Source>
			 	    </POS>
			 					    
			 	    <Inventories HotelName="'.selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'").'" HotelCode="'.$hotelChk.'">';

		$mapId = selectColumn(TBL_HOTEL_MAPPING,'id'," WHERE  `hotel_id` = '".addslashes($_REQUEST['hotelId'])."' and status='1'");	 	    
		$flagHotel=1;	 	    
	}

	while($row=$db->fetch_object2($availableInventory)){
		
		$res = "UPDATE `".TBL_INVENTORY."` set 
			offline_block_hotel='".addslashes($row->crs_available)."',
			crs_available='0',
			color='#dd4b39',
			`last_modified` = '".currenDateTime()."'
			where  allocation_date between '".addslashes($start_date)."' and '".addslashes($date->format("Y-m-d"))."' and
		    hotel_id ='".addslashes($hotelId)."' and 
			room_id ='".addslashes($row->room_id)."'";

			if(executeSql($res)){
				$roomChk = selectColumn(TBL_ROOM_MAPPING,'booking_engine_id'," WHERE `room_id` = '".$row->room_id."' AND hotel_mapping_id='".$mapId."' ");	
	
				if($roomChk !=""){
					$xml .= '
		       		<Inventory>
		          		<StatusApplicationControl  End="'.addslashes($start_date).'" Start="'.addslashes($start_date).'" InvTypeCode="'.$roomChk.'" />
			           	<InvCounts >
							<InvCount Count="0" />
						</InvCounts>
					</Inventory> ';
					$flagRoom=1;    	
				}					 	
				echo 'success';
			}else{
			    echo 'error';
			}
	}//end of while loop
	if($flagHotel ==1 && $flagRoom==1 && $_REQUEST['OTA_req']==1){
		$xml.='</Inventories>
					</OTA_HotelInvCountNotifRQ>';

		////////////////////  LIVE CHANNEL URL   ////////////////////////////
		//$url = "http://cm.resavenue.com/channelcontroller/PmsRateInventoryNotification";

		////////////////////  LOCAL URL   ////////////////////////////
		$url = "http://localhost:8181/roomstatushub/channel/apiResponseInventory.php";
			
		$headers = array(
		    "Content-type: text/xml",
		    "Content-length: " . strlen($xml),
		    "Connection: close",
		);

		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$dataResp = curl_exec($ch);
		
								
		if(curl_errno($ch)){
		    print curl_error($ch);
		}
		else{
		    curl_close($ch);
		    $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,date_created) VALUES('".$_REQUEST['hotelId']."','".$xml."','".date('Y-m-d h:i:s')."') ";
		    mysqli_query($conn,$reqSql);

		    if($dataResp != ""){
		    	$sqlResp="INSERT INTO `api_inv_response` (response,date_created) VALUES('".$dataResp."','".date("Y-m-d h:i:s")."') ";
		    	mysqli_query($conn,$sqlResp);
		    }
		}			

	}

}else{

echo 'Error, Please Try again.';
}
?>