<?php 
function checkAvailability($id_hotel,$checkin_date,$checkout_date){

global $connNew;
 $checkoutDate_upadate = date ("Y-m-d", strtotime($checkout_date));
 $startDateCheckAvailability = date ("Y-m-d", strtotime($checkin_date));

while (strtotime($startDateCheckAvailability) < strtotime($checkoutDate_upadate)){	

$startDateCheckAvailability = date("Y-m-d",strtotime($startDateCheckAvailability));	


			
		  $AssRoomRoomType	=	" SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id_mst_hotels` = '".$id_hotel."' ORDER BY status_active_date DESC ";
			$resHotRoomType=mysqli_query($connNew,$AssRoomRoomType);	
			
		while($rowResRoomType = mysqli_fetch_object($resHotRoomType)){
			
			 $sqlRes="SELECT count(fo_reservations_details.room_quantity) as qty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status!='4' and fo_reservations_details.no_showoff='0'  and  fo_reservations_details.dated='".$startDateCheckAvailability."' 
 and fo_reservations_details.id_mst_room_types='".$rowResRoomType->id_mst_room_types."'
GROUP by fo_reservations_details.dated ,fo_reservations_details.id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";




$resRes = mysqli_query($connNew,$sqlRes);
			if(mysqli_num_rows($resRes)>0){
			while($rowRes = mysqli_fetch_object($resRes)){ 
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						$rownew = mysqli_fetch_object($resnew);
						
						//================================
					 $sqlResConfirm="SELECT count(fo_reservations_details.room_quantity) as Confirmqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='1' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";		
						$resnewConfirm = mysqli_query($connNew,$sqlResConfirm);	
							$rownewConfirm = mysqli_fetch_object($resnewConfirm);
							$Confirmqty	= $rownewConfirm->Confirmqty;
							$Confirmqty=$Confirmqty==''?'0':$Confirmqty;
	
 $sqlResTenditive="SELECT count(fo_reservations_details.room_quantity) as Tenditivemqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='2' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";			
						$resnewTenditive = mysqli_query($connNew,$sqlResTenditive);	
							$rownewTenditive = mysqli_fetch_object($resnewTenditive);
							$Tenditiveqty	= $rownewTenditive->Tenditivemqty;							
								$Tenditiveqty=$Tenditiveqty==''?'0':$Tenditiveqty;
								
								
								
								//==============================
						$sqlRoom=  "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels = '".$rowRes->id_mst_hotels."' and ahr.id_mst_room_types='".addslashes($rowRes->id_mst_room_types)."'" ;
						
						
						$resRoom = mysqli_query($connNew,$sqlRoom);
						$rowRoom = mysqli_fetch_object($resRoom);
						//if($rowRes->booking_status=='2'){
						
							
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$tentative =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`tentative`='".$Tenditiveqty."',`confirmed`='".$Confirmqty."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						//echo '<br><br>2==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						/*}else{
						
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$confirmed =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						echo '<br><br>1==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						}*/
					
						
						}
			}else{
				
				
					 $roomId=$rowResRoomType->id_mst_room_types;
				$hotelId=$id_hotel;		
				//echo "SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'";
				$sqlSum=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' and ahr.id_mst_room_types='".addslashes($roomId)."'");
		$rowResSum = mysqli_fetch_object($sqlSum);
		$totalRoom	= $rowResSum->totalRoom;
	
		$crs_available = $rowRoom->inventory - $rowResRoomType->qty ; 
							$confirmed =  $rowResRoomType->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$totalRoom."',`confirmed`='0',`tentative`='0' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowResRoomType->id_mst_room_types."' and allocation_date = '".$startDateCheckAvailability."' and   id_mst_hotels = '".$rowResRoomType->id_mst_hotels."'";
						//echo $insertGrid;
						  mysqli_query($connNew,$insertGrid);
		
				
			}
		
		
		
		
		
		
		
		}
		
		
		
		
			
				
	$startDateCheckAvailability = date ("Y-m-d", strtotime("+1 day", strtotime($startDateCheckAvailability)));	

			  			
  }	


}
function updateBlockedHotelsForShop($conn, $shopId, $fromDate, $toDate) {
    // get all hotels for this shop
    $sqlHot = "SELECT id, name 
               FROM ".TBL_HOTELS." 
               WHERE  status='1'";
    $resHot = mysqli_query($conn, $sqlHot);

    while ($objHot = mysqli_fetch_object($resHot)) {
        $hotelId = $objHot->id;

        // get all assigned room types for this hotel
        $sqlRoomType = "SELECT id_mst_room_types 
                        FROM `".TBL_ASSIGN_HOTEL_ROOM."` 
                        WHERE id_mst_hotels = '$hotelId' 
                        ORDER BY status_active_date DESC";
        $resRoomType = mysqli_query($conn, $sqlRoomType);

        while ($rowRoomType = mysqli_fetch_object($resRoomType)) {
             $roomTypeId = $rowRoomType->id_mst_room_types;

            // call processor for this (hotel, room type)
            processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate);
        }
    }

   // return true;
}

function processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate) {
    $arrayOfDates = [];
    $from = new DateTime($fromDate);
    $to   = new DateTime($toDate);

    // prepare all dates in range with 0 first
    for ($current = clone $from; $current <= $to; $current->modify('+1 day')) {
        $dateStr = $current->format('Y-m-d');
        $arrayOfDates[$dateStr] = 0;
    }

    // fetch blocked ranges
    $query = "SELECT blocked_room_dates 
              FROM `".TBL_ROOMNO."`
              WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
              AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
              AND status = '1'
              AND blocked_room_dates != ''";
    $resSQL = mysqli_query($conn, $query);

    while ($Record = mysqli_fetch_object($resSQL)) {
        $ranges = explode(',', $Record->blocked_room_dates);

        foreach ($ranges as $selectedDateRange) {
            $dates = explode(' - ', trim($selectedDateRange));
            if (count($dates) != 2) continue;

            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]));
            $end   = DateTime::createFromFormat('d/m/Y', trim($dates[1]));
            if (!$start || !$end) continue;

            for ($current = clone $start; $current <= $end; $current->modify('+1 day')) {
                if ($current >= $from && $current <= $to) {
                    $dateStr = $current->format('Y-m-d');
                    $arrayOfDates[$dateStr]++;  // increment block count
                }
            }
        }
    }

    // now update inventory (always write a value, even if 0)
    foreach ($arrayOfDates as $date => $count) {
         $sql = "UPDATE fo_inventory
                SET blocked_hotel = $count
                WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
                AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
                AND allocation_date = '$date'";
        mysqli_query($conn, $sql);
    }

    return true;
}
	function updateOTA($id_hotel,$checkin_date,$checkout_date,$connNew){ 
		
		$Org_checkin_date	= $checkin_date;
		$Org_checkout_date=	$checkout_date;
		global $connNew;
		$checkout_date = date('Y-m-d', strtotime($checkout_date . ' +2 day'));
		$checkin_dateBE = strtotime($checkin_date);
		$checkout_dateBE = strtotime($checkout_date);
		$channel_id='';
    	$auto_sync_inv='';
		//echo $id_hotel.'============='.$checkin_date.'============='.$checkout_date.'============='.$ids_room_sync;
		updateBlockedHotelsForShop($connNew, '2', $checkin_date, $checkout_date);
		checkAvailability($id_hotel,$checkin_date,$checkout_date);
		
		//checkAvailability INVENTORY UPDATE API START ======================================================>
		
		//Resavenue=================================================
		//$channel_id = selectColumn('fs_channel_manager','id','Where name="ResAvenue" AND  status=1 ');
		$sqlChannel = "SELECT id FROM fs_channel_manager WHERE name='ResAvenue' AND status=1 LIMIT 1";

// Step 2: Execute query
$resChannel = mysqli_query($connNew, $sqlChannel);

// Step 3: Check if record exists
if ($resChannel && mysqli_num_rows($resChannel) > 0) {		
		$rowChannel = mysqli_fetch_assoc($resChannel);
    $channel_id = $rowChannel['id'];
	
	
		$auto_sync_inv = selectColumn(TBL_HOTEL_MAPPING, 'auto_sync_inv', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");
		if($channel_id>0 && $auto_sync_inv=='1'){
		
		
		$id_hotel_booking_engineResavenue = selectColumn(TBL_HOTEL_MAPPING,'booking_engine_id','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');
		
		
		
		
		 
		
		if($id_hotel_booking_engineResavenue!='' ){
		
	


		////////////////////  TEST AT CRS URL   ////////////////////////////
		//$url = "https://www.roomstatushub.in/crs/channel/apiResponseInventory.php";
		
		////////////////////  MAIN LIVE CHANNEL URL   ////////////////////////////
		$url = "https://cm.resavenue.com/channelcontroller/PmsRateInventoryNotification";

		///////////////////  TEST AT LOCAL URL   ////////////////////////////
		//$url = "http://localhost:8181/roomstatushub/channel/apiResponseInventory.php";

		/*** required variables data ***/
		$hotelName = selectColumn(TBL_HOTELS,'name','Where id="'.$id_hotel.'" ');
		//$hotelName  =   selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$_REQUEST['hotelId']."'");
$hotel_name = str_replace("'", '', $hotelName);
		$id_hotel_booking_engine = selectColumn(TBL_HOTEL_MAPPING,'booking_engine_id','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');

		$id_hotel_mapping = selectColumn(TBL_HOTEL_MAPPING,'id','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');
		
		
		$channel_user_id=selectColumn(TBL_HOTEL_MAPPING,'channel_user_name','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');
		$channel_password=selectColumn(TBL_HOTEL_MAPPING,'channel_password','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');
		$ids_room_booking_engine=array();
		$ids_room=array();

		 $checkin_date = $checkin_dateBE;
		 $checkout_date = $checkout_dateBE;
		$online_avail=0;

		/*** end ***/
		
		if($id_hotel_mapping !=''){

				/*** Fetching Room type id ***/
					$sqlRoomType = "SELECT room_id FROM fs_room_mapping WHERE hotel_mapping_id ='".$id_hotel_mapping."' AND status=1 ORDER BY id";
					
					$resRoomType = mysqli_query($connNew,$sqlRoomType);
					while($rowRoomType = mysqli_fetch_object($resRoomType)){
						array_push($ids_room,$rowRoomType->room_id);
					}	
				/*** End ***/

				/*** Fetching Room mapping id ***/
					 $sqlMapRoomType = "SELECT booking_engine_id FROM fs_room_mapping WHERE hotel_mapping_id ='".$id_hotel_mapping."' AND status=1 ORDER BY id";
					
					$resMapRoomType = mysqli_query($connNew,$sqlMapRoomType);
					while($rowMapRoomType = mysqli_fetch_object($resMapRoomType)){
						array_push($ids_room_booking_engine,$rowMapRoomType->booking_engine_id);
					}	
				/*** End ***/
				
				$xmlToSend='<?xml version="1.0" encoding="UTF-8"?>
								<OTA_HotelInvCountNotifRQ Version="1.0" Target="Production" TimeStamp="'.date('Y-m-d H:i:s').'" EchoToken="'.base64_encode($id_hotel.'|'.date('Y-m-d')).'" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
							    <POS>
							        <Source>
							            <RequestorID MessagePassword="'.$channel_password.'" ID="'.$channel_user_id.'" />
							        </Source>
							    </POS>
								    
							    <Inventories HotelName="'.$hotelName.'" HotelCode="'.$id_hotel_booking_engine.'">';
						    
				foreach ($ids_room_booking_engine as $index => $id_room) {
					
					$startDate = $checkin_date;
					while($startDate < $checkout_date){
						
						/*** fetch live inventory ***/
						$online_avail=selectColumn('fo_inventory','crs_available','WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"');

						$blocked_hotel=selectColumn('fo_inventory','blocked_hotel','WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"');
						$online_avail=$online_avail-$blocked_hotel;
						$invStatus=selectColumn('fo_inventory','status','WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"');


						if($online_avail<=0){
							$online_avail=0;
						}

						if($invStatus==0){
							$stopSell='<StopSell>True</StopSell>';
						}
						else{
							$stopSell='<StopSell>false</StopSell>';
						}


 $sqlRoomInv = 'SELECT * FROM fo_inventory WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"';
$resRoomQuery = mysqli_query($connNew,$sqlRoomInv);
if(mysqli_num_rows($resRoomQuery)>0){

						/*** fetch live inventory end ***/

						$xmlToSend.='<Inventory>
						          		<StatusApplicationControl  End="'.date('Y-m-d',$startDate).'" Start="'.date('Y-m-d',$startDate).'" InvTypeCode="'.$id_room.'" />
						            	<InvCounts >
						                	<InvCount Count="'.$online_avail.'" />
						                	'.$stopSell.'
						                </InvCounts>
						        	</Inventory>';	
	
}

						$startDate = strtotime('+1 days',$startDate);        	
					}	        	

				}
				
				$xmlToSend.='</Inventories>
					</OTA_HotelInvCountNotifRQ>';			    
				
					//echo $xmlToSend;die;
			
			    /*** Inserting In Request Table ***/
                $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,sourcefrom,date_created,id_channel ,ip_address,created_by,action_by,start_date,end_date) VALUES('".$id_hotel."','".$xmlToSend."','inventoryUpdateFunctions','".date('Y-m-d H:i:s')."','".$channel_id."','".ipCheck()."','".$_SESSION['userId']."','2','".date('Y-m-d',($checkin_date))."','".date('Y-m-d',($checkout_date))."') ";
				   	
					mysqli_query($connNew,$reqSql);
                    $lastInsertID   =   mysqli_insert_id($connNew);
				/*** Running CURL Operation ***/
				$headers = array(
				    "Content-type: text/xml",
				    "Content-length: " . strlen($xmlToSend),
				    "Connection: close",
				);
				
				$ch = curl_init(); 
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_TIMEOUT, 60);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $xmlToSend);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

				$dataResp = curl_exec($ch);
				//print_r($dataResp);
														
				if(curl_errno($ch)){
				    print curl_error($ch);
				}
				else{
					curl_close($ch);
					
            
					/*** End ***/

					/*** Inserting In Response Table ***/
					//$sqlResp="INSERT INTO `api_inv_response` (response,date_created,id_inv_request) VALUES('".$dataResp."','".date("Y-m-d H:i:s")."','".$lastInsertID."') ";
					
					 $sqlResp="UPDATE api_inv_request 
SET response = '".$dataResp."', 
    response_date = '".date("Y-m-d H:i:s")."' 
WHERE id = '".$lastInsertID."'";
					mysqli_query($connNew,$sqlResp);
					/*** End **/

					$xml = json_decode(json_encode(simplexml_load_string($dataResp)), true);

					$timeStamp = @$xml['@attributes']['TimeStamp'];
					$errorMsg  = @$xml['Errors']['Error']['@attributes']['ShortText'];

					if($errorMsg!=''){
					    mail('support@roomstatushub.com','Inventory Update Failed At ResAvenue','Hotel : '.$hotel_name.' --- Time Stamp : '.$timeStamp.' --- Error Msg : '.$errorMsg.' ');
						mail('sundaram@roomstatushub.com','Inventory Update Failed At ResAvenue','Hotel : '.$hotel_name.' --- Time Stamp : '.$timeStamp.' --- Error Msg : '.$errorMsg.' ');
					}
				}
				    
				/*** CURL END ***/		    
			}	
	}
	}
	}	
		//Resavenue End===============================
		
	//Booking Engine OTA Update start=============$checkin_date,$checkout_date){ 
	$channel_id='';
    $auto_sync_inv='';
		
	$checkout_date = date('Y-m-d', strtotime($Org_checkout_date . ' +2 day'));
    $checkin_dateBE = strtotime($Org_checkin_date);
    $checkout_dateBE = strtotime($Org_checkout_date);
    //$channel_id = selectColumn('fs_channel_manager', 'id', 'WHERE name="Booking Engine" AND status=1');
	$sqlChannel = "SELECT id FROM fs_channel_manager WHERE name='Booking Engine' AND status=1 LIMIT 1";

// Step 2: Execute query
$resChannel = mysqli_query($connNew, $sqlChannel);

// Step 3: Check if record exists
if ($resChannel && mysqli_num_rows($resChannel) > 0) {		
		$rowChannel = mysqli_fetch_assoc($resChannel);
    $channel_id = $rowChannel['id'];	
		
	$auto_sync_inv = selectColumn(TBL_HOTEL_MAPPING, 'auto_sync_inv', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");
	if($channel_id>0 && $auto_sync_inv=='1'){
		
	$channel_mapping_code = selectColumn('fs_channel_manager', 'channel_mapping_code', 'WHERE name="Booking Engine" AND status=1');
   	
		
    $BENew_id_hotel_booking_engine = selectColumn(TBL_HOTEL_MAPPING, 'booking_engine_id', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");

    $id_hotel_mapping = selectColumn(TBL_HOTEL_MAPPING, 'id', "WHERE hotel_id='$id_hotel' AND channel_id='$channel_id' AND status=1");
    if (!$id_hotel_mapping) return; // nothing to update

    // Fetch room mappings
    $ids_room = [];
    $ids_room_booking_engine = [];

    $resRoomType = mysqli_query($connNew, "SELECT room_id, booking_engine_id FROM fs_room_mapping WHERE hotel_mapping_id='$id_hotel_mapping' AND status=1 ORDER BY id");
    while ($row = mysqli_fetch_assoc($resRoomType)) {
        $ids_room[] = $row['room_id'];
        $ids_room_booking_engine[] = $row['booking_engine_id'];
    }

    $payloads = [];

    foreach ($ids_room_booking_engine as $index => $id_room) {
        $startDate = $checkin_dateBE;
        $inventory = [];

        while ($startDate <= $checkout_dateBE) {
            // Determine available inventory
            if ($be_inventory_source == '4') { // Lowest
                $pms_available = selectColumn('fo_inventory', 'pms_available', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
                $online_allocation = selectColumn('fo_inventory', 'online_allocation', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
                $online_avail = min((int)$pms_available, (int)$online_allocation);
            } elseif ($be_inventory_source == '2') { // PMS
                $online_avail = selectColumn('fo_inventory', 'pms_available', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
            } else { // CRS
                $online_avail = selectColumn('fo_inventory', 'online_allocation', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");
            }
			$blocked_hotel=selectColumn('fo_inventory','blocked_hotel','WHERE allocation_date="'.date('Y-m-d',$startDate).'" AND id_mst_room_types="'.$ids_room[$index].'" AND id_mst_hotels="'.$id_hotel.'"');
						$online_avail=$online_avail-$blocked_hotel;
            $invStatus = selectColumn('fo_inventory', 'status', "WHERE allocation_date='" . date('Y-m-d', $startDate) . "' AND id_mst_room_types='" . $ids_room[$index] . "' AND id_mst_hotels='$id_hotel'");

            $online_avail = max(0, (int)$online_avail);
            $stopSell = ($invStatus == 0);

            $inventory[] = [
                "startDate" => date('Y-m-d', $startDate),
                "endDate"   => date('Y-m-d', $startDate),
                "free"      => $online_avail,
                "stopsell"  => $stopSell
            ];

            $startDate = strtotime('+1 day', $startDate);
        }

        $payloads[] = [
            "auth" => [
                "key" => "cG1zYmVjb25uZWN0QDk4NzY="
            ],
            "shop_code" => (string)$channel_mapping_code,
            "data" => [
                "propertyId" => (string)$BENew_id_hotel_booking_engine,
                "roomType"   => (string)$id_room,
                "inventory"  => $inventory
            ]
        ];
    }
 
    // Send JSON payloads
    foreach ($payloads as $dataToSend) {
        $jsonData = json_encode($dataToSend, JSON_PRETTY_PRINT);
		
		
		$reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,sourcefrom,date_created,id_channel ,ip_address,created_by,action_by,start_date,end_date) VALUES('".$id_hotel."','".$jsonData."','inventoryUpdateFunctions','".date('Y-m-d H:i:s')."','".$channel_id."','".ipCheck()."','".$_SESSION['userId']."','2','".date('Y-m-d',($checkin_dateBE))."','".date('Y-m-d',strtotime($checkout_date))."') ";
				   	
					mysqli_query($connNew,$reqSql);
                    $lastInsertID   =   mysqli_insert_id($connNew);

        $ch = curl_init('https://api.roomstatushub.in/api/inventoryUpdate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);

        $response = curl_exec($ch);
	 $sqlResp="UPDATE api_inv_request 
SET response = '".$response."', 
    response_date = '".date("Y-m-d H:i:s")."' 
WHERE id = '".$lastInsertID."'";
					mysqli_query($connNew,$sqlResp);
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);
    }
	}
		//Booking Engine OTA Update End=============
}	
	}

?>