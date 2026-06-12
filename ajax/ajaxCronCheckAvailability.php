<?php 
set_time_limit(600);
/************************ FILE DESCRIPTION ************************/
/*
AUTHOR : HITESH ALONEY
DATE CREATED : 05/12/2018
DESCRIPTION : This file is for cron tab which executes every night at 11:30 PM
it checks the CRS AVAILABLE value ,if it is less than zero than it updates the 
value of online_allocation field value to zero and then fetch all the records 
having zero value for online_allocatio from today till +90 days 
will be updated on channle manager. 

NOTE : this file is using similar functionality as check availability report 
*/
/************************ END**************************************/


/************************CRON JOB COMMAND**************************/

// /usr/local/bin/php -q /home/admingcs/public_html/crs/adminpanel/ajax/ajaxCronCheckAvailability.php /dev/null 2>&1 


///////////////// LOCAL PATH ///////////////////

/*include_once("../../config/data.config.php");
include_once("../../phplib/data.constant.php");	
include_once("../../phplib/cronRoomstatus.library.php");	
include_once("../../phplib/functions.library.php");
include("../../phplib/PHPMailer/PHPMailerAutoload.php");
include_once("../../phplib/class.mailer.php");
$sendMail = new sendMail;*/

///////////////// CRON JOB PATH ///////////////////
$path = getcwd().'/public_html/crs/';
include_once($path."/config/data.config.php");
include_once($path."/phplib/data.constant.php");	
include_once($path."/phplib/cronRoomstatus.library.php");	
include_once($path."/phplib/functions.library.php");
include_once($path."/phplib/PHPMailer/PHPMailerAutoload.php");
include_once($path."/phplib/class.mailer.php");
$sendMail = new sendMail;

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

$shopSql ="SELECT id FROM `fs_shop` ";
$resShop = mysqli_query($conn,$shopSql);

if($resShop){

	while($shopRow = mysqli_fetch_object($resShop)){

		$hotelSel = "SELECT * FROM `fs_room_mapping` AS a LEFT JOIN `fs_hotel_mapping` AS b ON a.hotel_mapping_id=b.id where id_shop='".$shopRow->id."' AND  auto_online_alloc=1 "  ;
					


		$hotelRes = mysqli_query($conn,$hotelSel);



		if($hotelRes){

			while($hotelRow = mysqli_fetch_object($hotelRes)){
				
				$hotel_id =$hotelRow->hotel_id;
				$room_id = $hotelRow->room_id;
				$checkinDate = date ("Y-m-d");
				$checkoutDate = date ("Y-m-d", strtotime("+45 day", strtotime($checkinDate)));

				$nameHotel='SELECT name FROM `fs_hotels` where id="'.$hotel_id.'" and id_shop="'.$shopRow->id.'" ';

				$resName =mysqli_query($conn,$nameHotel);
				if($resName){
					$hotelName = mysqli_fetch_object($resName);
				}


				/*-------------------Update Room Availability START----------------------------*/
				$checkoutDate_upadate = date ("Y-m-d", strtotime($checkoutDate));
				$startDate = date ("Y-m-d", strtotime($checkinDate));
				while (strtotime($checkinDate) <= strtotime($checkoutDate_upadate)) {	
									  
					$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
									  
				 	 if($room_id == 0){
				 		$roomSql = "SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."'";
				 	}else{
				 		$roomSql = "SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotel_id)."' and ahr.room_id='".addslashes($room_id)."'" ;
				 	}
				 		 	
					
					$resRoom1 = mysqli_query($conn,$roomSql);
					
						
					while($rowRoom_update = mysqli_fetch_object($resRoom1)){

						$totalRoom	= GetAssignTotalRoom($hotel_id,$rowRoom_update->room_id,$conn);	
								
						$blocked_hotel	= GetTotalRoomBlocked_Hotel($startDate,$hotel_id,$rowRoom_update->room_id,$conn);

						$GetTotalRoomoffline_block_hotel 	= GetTotalRoomoffline_block_hotel($startDate,$hotel_id,$rowRoom_update->room_id,$conn);
									
						$roomAvailable 	= GetTotalRoomAlloted($startDate,$hotel_id,$rowRoom_update->room_id,$conn);	
											
						$orderTableAvailableRooms 			= GetTotalRoomAllotedOld($startDate,$hotel_id,$rowRoom_update->room_id,$conn);	
										
						$Total_blocked_hotel	=	$orderTableAvailableRooms;
						$crs_available			=	$totalRoom-($Total_blocked_hotel+$GetTotalRoomoffline_block_hotel);

						$nameRoom	='SELECT name FROM `fs_room_type` WHERE id="'.$rowRoom_update->room_id.'" and id_shop="'.$shopRow->id.'"  '	;	
						$resName =mysqli_query($conn,$nameRoom);
						
							if($resName){
						
								$roomName = mysqli_fetch_object($resName);
							}

							
						/*echo "<br>".$hotelName->name."<br>";	
						echo "<br>".$roomName->name."<br>";	
						echo "<br>".$startDate."<br>";	
						
						
						echo "<br>Total Room ".$totalRoom	= GetAssignTotalRoom($hotel_id,$rowRoom_update->room_id,$conn);	
								
						echo "<br>Blocked hotel ".$blocked_hotel	= GetTotalRoomBlocked_Hotel($startDate,$hotel_id,$rowRoom_update->room_id,$conn);

						echo "<br>Offline Room ".$GetTotalRoomoffline_block_hotel 	= GetTotalRoomoffline_block_hotel($startDate,$hotel_id,$rowRoom_update->room_id,$conn);
									
						$roomAvailable 	= GetTotalRoomAlloted($startDate,$hotel_id,$rowRoom_update->room_id,$conn);	
											
						echo "<br>CRS BLOCKED ".$orderTableAvailableRooms 			= GetTotalRoomAllotedOld($startDate,$hotel_id,$rowRoom_update->room_id,$conn);	
					
							
						$Total_blocked_hotel	=	$orderTableAvailableRooms;
							
						echo "<br>CRS avail ".$crs_available			=	$totalRoom-($Total_blocked_hotel+$GetTotalRoomoffline_block_hotel);*/
						if($crs_available <=0){
							
							$availableData1 = " UPDATE  `".TBL_INVENTORY."`  SET 
												online_allocation = '0' 								
												where  `hotel_id`='".addslashes($hotel_id)."' and 
										  		`room_id`='".addslashes($rowRoom_update->room_id)."' and 
												allocation_date = '".date('Y-m-d',strtotime($startDate))."' ";
								

							$updateInventory = mysqli_query($conn,$availableData1);	
							
						} 
						$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));	
					}
					

				}
			}

			

			$invSel = "SELECT DISTINCT b.hotel_id,b.notification_email,b.booking_engine_id as chnl_hotel_id,b.id AS hotel_map_id FROM `fs_room_mapping` AS a LEFT JOIN `fs_hotel_mapping` AS b ON a.hotel_mapping_id=b.id where id_shop='".$shopRow->id."' AND  auto_online_alloc=1 ";
				
			
			$invRes = mysqli_query($conn,$invSel);
			$numRows = mysqli_num_rows($invRes);
			$flag=0;
			
			
			
			while($invRow = mysqli_fetch_object($invRes)){
				
				$sqlForRoom = "SELECT * FROM `fs_room_mapping` WHERE `hotel_mapping_id`='".$invRow->hotel_map_id."' ";

				$resForRoom = mysqli_query($conn,$sqlForRoom);

				///// ONLY TO FETCH HOTEL NAME////////
				$nameHotel='SELECT name FROM `fs_hotels` where id="'.$invRow->hotel_id.'" and id_shop="'.$shopRow->id.'" ';

				$resName =mysqli_query($conn,$nameHotel);
				if($resName){
					$hotelName = mysqli_fetch_object($resName);
				}
				///////////////////////////////////////

				//////////// GENERATING HTML FOR MAIL /////////////////

				$htmlforMail = " Dear Sir/Madam,<br><br>   Greetings From RoomStatusHUB !! <br><br>
						Inventory marked as <b>0</b> <br><br>Kindly find the details below:- <br>
						";

				$htmlforMail.="<br><span style='font-size:1.5em;color:green;'>".$hotelName->name."</span style=''><br><br><table border='5px solid #000'>
										<tr >
											<th>Room Type</th>
											<th>Date</th>
										</tr>
										";

				/////////////// GENERATING XML ////////////////						
				$xml = '<?xml version="1.0" encoding="UTF8"?>
						<OTA_HotelInvCountNotifRQ Version="1.0" Target="Production" TimeStamp="'.date('Y-m-d H:i:s').'" EchoToken="'.md5(rand(0000,9999)).'" xmlns="http://www.opentravel.org/OTA/2003/05" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchemainstance">
					    <POS>
					        <Source>
					            <RequestorID MessagePassword="welcome@123" ID="sundaram@globalcomputersolutions.in" />
					        </Source>
					    </POS>
						    
					    <Inventories HotelName="'.$hotelName->name.'" HotelCode="'.$invRow->chnl_hotel_id.'">';		


				while($roomRow = mysqli_fetch_object($resForRoom)){
					$flag=0;
					$slqInv = "SELECT * FROM `fs_inventory` WHERE crs_available<=0  AND hotel_id='".$invRow->hotel_id."' AND room_id='".$roomRow->room_id."'   AND allocation_date BETWEEN '".date('Y-m-d')."' AND '".$checkoutDate."' ";	
				
					$fetchInv = mysqli_query($conn,$slqInv);

					if($fetchInv){
						$numRow=mysqli_num_rows($fetchInv);

						if($numRow>0){
							
							while($record = mysqli_fetch_object($fetchInv)){
								$flag++;
								$xml .= '
					       		<Inventory>
					          		<StatusApplicationControl  End="'.$record->allocation_date.'" Start="'.$record->allocation_date.'" InvTypeCode="'.$roomRow->booking_engine_id.'" />
					            	<InvCounts >
					                	<InvCount Count="0" />
					                </InvCounts>
					        	</Inventory> ';

					        	
					        	/////// ONLY FOR FETCHING ROOM TYPE ///////////////////
					        	$nameRoom	='SELECT name FROM `fs_room_type` WHERE id="'.$record->room_id.'" and id_shop="'.$shopRow->id.'"  '	;	
					        	$resName =mysqli_query($conn,$nameRoom);
					        
					        	if($resName){
					        
					        		$roomName = mysqli_fetch_object($resName);
					        	}

					        	///////////////////////////////////////////////////////

					        	$htmlforMail .="<tr >
					        					<td >".$roomName->name."</td>
					        					<td >".date('d-M-Y',strtotime($record->allocation_date))."</td>
					        				</tr>
					        				";
							}
							
						}
					}
				}
				$xml.='</Inventories>
				</OTA_HotelInvCountNotifRQ>';

				$htmlforMail .="</table>";
				
				////////////////////  CRS URL   ////////////////////////////
				//$url = "https://crs.roomstatushub.com/channel/apiResponseInventory.php";


				////////////////////  TEST CHANNEL URL   ////////////////////////////
				//$url = "http://203.109.97.241:8080/ChannelController/PmsRateInventoryNotification";	

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
				    $reqSql = "INSERT INTO `api_inv_request` (hotel_id,request,date_created) VALUES('".$invRow->hotel_id."','".$xml."','".date('Y-m-d h:i:s')."') ";
				    mysqli_query($conn,$reqSql);

				    if($dataResp != ""){
				    	$sqlResp="INSERT INTO `api_inv_response` (response,date_created) VALUES('".$dataResp."','".date("Y-m-d h:i:s")."') ";
				    	mysqli_query($conn,$sqlResp);

				    	
				    }
				    if($flag>0 AND $invRow->notification_email !=""){
					$emails = explode(",", $invRow->notification_email);
					$sendMail->sendMail("support@roomstatushub.com","","Invertory Update Info for Hotel : ".$hotelName->name,$htmlforMail,"",$emails); 

					}
					$sendMail->ClearAllRecipients( );
				}	
			}
		}else{
			exit;
		}
	}	
}	
else{
	exit;
}
mysqli_close($conn);

?>