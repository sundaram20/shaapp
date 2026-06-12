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

	function updateOTA($id_hotel,$checkin_date,$checkout_date){ 
		global $connNew;
		$checkout_date = date('Y-m-d', strtotime($checkout_date . ' +2 day'));
		$checkin_dateBE = strtotime($checkin_date);
		$checkout_dateBE = strtotime($checkout_date);
		
		//echo $id_hotel.'============='.$checkin_date.'============='.$checkout_date.'============='.$ids_room_sync;
		//die;
		checkAvailability($id_hotel,$checkin_date,$checkout_date);
		
		//ONE FINE RATE INVENTORY UPDATE API START ======================================================>
		
		
		$channel_id = selectColumn('fs_channel_manager','id','Where name="ResAvenue" AND  status=1 ');
		
		
		
		$id_hotel_booking_engineResavenue = selectColumn(TBL_HOTEL_MAPPING,'booking_engine_id','Where hotel_id="'.$id_hotel.'" AND channel_id="'.$channel_id.'" AND status=1 ');
		
		
		
		
		 
		
		if($id_hotel_booking_engineResavenue!='' ){
		
		/*** Sample XML Format ResAvenue ***/
		/*<?xml version="1.0" encoding="UTF8"?>
						<OTA_HotelInvCountNotifRQ Version="1.0" Target="Production" TimeStamp="2019-08-16 23:30:18" EchoToken="f2501c71a070a8bb42e898a80baee401" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
					    <POS>
					        <Source>
					            <RequestorID MessagePassword="welcome@123" ID="sundaram@globalcomputersolutions.in" />
					        </Source>
					    </POS>
						    
					    <Inventories HotelName="Raj Niwas Palace" HotelCode="602">
					       		<Inventory>
					          		<StatusApplicationControl  End="2019-09-18" Start="2019-09-18" InvTypeCode="1894" />
					            	<InvCounts >
					                	<InvCount Count="0" />
					                </InvCounts>
					        	</Inventory> 
					       		<Inventory>
					          		<StatusApplicationControl  End="2019-09-22" Start="2019-09-22" InvTypeCode="1893" />
					            	<InvCounts >
					                	<InvCount Count="0" />
					                </InvCounts>
					        	</Inventory> 
					    </Inventories>
				</OTA_HotelInvCountNotifRQ>*/
		/*** END Sample XML FORMAT ***/


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
                $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,sourcefrom,date_created,id_channel ,ip_address,created_by,action_by,start_date,end_date) VALUES('".$id_hotel."','".$xmlToSend."','inventoryUpdateFunctions','".date('Y-m-d H:i:s')."','1','".ipCheck()."','".$_SESSION['userId']."','2','".date('Y-m-d',($checkin_date))."','".date('Y-m-d',($checkout_date))."') ";
				   	
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

?>