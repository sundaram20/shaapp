<?php
include_once("../../config/auto_loader.php");
if(($_SESSION['errorMsg']!='') || ($_SESSION['userId']=='')){
    //echo $_SESSION['errorMsg'];
    ?>
    <script type="text/javascript">
    window.location.href='<?php echo $SITE_URL;?>/adminpanel/index.php';
   
   </script>
<?php	
}



function GetTotalRoomAllotedTwo1($dated,$hotelId,$roomId,$connNew)
	{	
	
		global $connNew;
		if($roomId=='0'){
			
		 $sqlGuestDetail = mysqli_query($connNew,"SELECT * FROM `mst_assign_hotel_rooms` WHERE `id_mst_hotels`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysqli_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['id_mst_room_types'];
			$str 		 = implode (", ", $AllRoomId);
		}
			
				
		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory`  where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types` IN ($str) and status=1");
				
			 
		 }else{
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and status=1 and  `id_mst_room_types`='".addslashes($roomId)."'");
		 }
		
		
		  $row = mysqli_fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}	
function GetTotalRoomAlloted2($dated,$hotelId,$roomId,$connNew)
	{	
	
	global $connNew;
		if($roomId==''){
			
		 $sqlGuestDetail = mysqli_query($connNew,"SELECT * FROM `mst_assign_hotel_rooms` WHERE `id_mst_hotels`='".addslashes($hotelId)."'  and status=1 "); 
		 while($rowGuestDetail = mysqli_fetch_array($sqlGuestDetail)){

			$AllRoomId[] = $rowGuestDetail['id_mst_room_types'];
			$str 		 = implode (", ", $AllRoomId);
		}

			
			if($roomId!=''){
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'");
			}else{
				
					$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `".TBL_INVENTORY."`  where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types` IN ($str) ");
				}
			 
			 
		 }else{//echo '11'."Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'";
			 		$sql = mysqli_query($connNew,"Select sum(crs_available) as roomAlloted from `fo_inventory` where `id_mst_hotels`='".addslashes($hotelId)."' and allocation_date = '".date('Y-m-d',strtotime($dated))."' and  `id_mst_room_types`='".addslashes($roomId)."'");
		 }
		
		
		  $row = mysqli_fetch_array($sql);
		  if($row['roomAlloted']==''){return 0;}else{ return $row['roomAlloted'];}
		
		 
		 
	}

if(isset($_REQUEST['chkRoomNum']) && $_REQUEST['chkRoomId']){
	$chkRoomNum = $_REQUEST['chkRoomNum'];
	$chkRoomId = $_REQUEST['chkRoomId'];
	$EditID = addslashes(encryptor('decrypt',$_REQUEST['EditID']));
	$rate_plan_id = $_REQUEST['rate_plan_id'];
	$adults = $_REQUEST['adult_no'];
	$child = $_REQUEST['child_no'];
}


$id_mst_hotels = '1';
$id_mst_room_types = $_POST['room_id'];
$reservation_date = explode(' to ',$_POST['period']);
$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));

 $sqlMappingInventory = 'SELECT auto_sync_inv FROM '.TBL_CHANNEL_MANAGER.' AS A INNER JOIN '.TBL_HOTEL_MAPPING.' AS B ON A.id=B.channel_id
								WHERE  B.id_mst_hotels="'.$id_mst_hotels.'" AND B.status=1 and channel_type=1';
	$QueryMapping	=	mysqli_query($connNew,$sqlMappingInventory);
	$resultMapping   =    mysqli_fetch_object($QueryMapping);
    $autoInventoryUpdate=$resultMapping->auto_sync_inv;

//$autoInventoryUpdate = selectColumn(TBL_HOTEL_MAPPING,'auto_sync_inv','Where id_mst_hotels="'.$id_mst_hotels.'" AND channel_id=1 AND status=1 ');

//$overbooking_notallowed = selectColumn(TBL_HOTELS,'overbooking_notallowed','Where id="'.$id_mst_hotels.'" AND  status=1 ');


/*-------------------Update Room Availability START----------------------------*/
$checkoutDate_upadate = date ("Y-m-d", strtotime($reservation_date['1']));
$checkoutDate_OverBooking = date ("Y-m-d", strtotime($reservation_date['1']));
$startDate = date ("Y-m-d", strtotime($reservation_date['0']));

$daysNew =  abs((strtotime($startDate) - strtotime($checkoutDate_upadate))/ 86400 );
	if($daysNew < '7'){
		 $checkoutDate_upadate = date ("Y-m-d", strtotime("+7 day", strtotime($checkinDate)));
	}else {
		 $checkoutDate_upadate = date ("Y-m-d", strtotime($reservation_date['1']));
	}

while (strtotime($checkinDate) < strtotime($checkoutDate_upadate)) {	
					  
					  
					  
 $checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
				  
 if($id_mst_room_types == 0){ 
	$resRoom1 = mysqli_query($connNew,"SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($id_mst_hotels)."'");
	}else{
	$resRoom1 = mysqli_query($connNew,"SELECT rt.name, ahr.hotel_id,ahr.inventory, ahr.room_id,ahr.over_booking_limit from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($id_mst_hotels)."' and ahr.room_id='".addslashes($room_id)."'");
	}
		while($rowRoom_update = mysqli_fetch_object($resRoom1)){
			
			  
			  $totalRoom 							= GetAssignTotalRoom($id_mst_hotels,$rowRoom_update->id_mst_room_types);
			  
		$ResDetailSql	=	mysqli_query($connNew,"Select  fo_reservations.booking_no,`fo_reservations`.booking_status,`fo_reservations_details`.dated ,
			sum( CASE WHEN `fo_reservations`.booking_status = '4'   THEN ROUND(1,0) ELSE 0 END)  AS ca , 
			sum(CASE WHEN `fo_reservations`.booking_status = '1'   THEN ROUND(1,0) ELSE 0 END ) AS Confirmed,
    		 sum(CASE WHEN `fo_reservations`.booking_status = '2'   THEN ROUND(1,0) ELSE 0 END)  AS Tentative,   
			sum(CASE WHEN `fo_reservations`.booking_status = '3'   THEN ROUND(1,0) ELSE 0 END ) AS Waitlisted ,
            
             fo_reservations_details.id_mst_room_types
    		
			 from `fo_reservations` left join `fo_reservations_details`  on `fo_reservations`.`id`=`fo_reservations_details`.`id_fo_reservations` 
			 WHERE  `fo_reservations`.`id_mst_hotels`='".addslashes($id_mst_hotels)."'  and `fo_reservations_details`.`id_mst_room_types`='".addslashes($rowRoom_update->id_mst_room_types)."'
			  and  `fo_reservations_details`.dated = '".date('Y-m-d',strtotime($startDate))."'");
			  $GetTotalRoomAllotedConfirmed = mysqli_fetch_array($ResDetailSql);
			  
			  $orderTableAvailableRooms =$GetTotalRoomAllotedConfirmed['Confirmed']+$GetTotalRoomAllotedConfirmed['Tentative']+$GetTotalRoomAllotedConfirmed['Waitlisted'];
			
			
			$crs_available			=	$totalRoom-($orderTableAvailableRooms+$GetTotalRoomoffline_block_hotel);
			$availableData1 = "UPDATE  `fo_inventory`  SET 
								crs_available = '".addslashes($crs_available)."',
								".$liveCond."								
								blocked_hotel = '".addslashes(isset($orderTableAvailableRooms)?$orderTableAvailableRooms:0)."',
								confirmed = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Confirmed'])?$GetTotalRoomAllotedConfirmed['Confirmed']:0)."' ,
								tentative = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Tentative'])?$GetTotalRoomAllotedConfirmed['Tentative']:0)."',
								waitlisted = '".addslashes(isset($GetTotalRoomAllotedConfirmed['Waitlisted'])?$GetTotalRoomAllotedConfirmed['Waitlisted']:0)."' 								
								
								where  `id_mst_hotels`='".addslashes($id_mst_hotels)."' and 
						  		`id_mst_room_types`='".addslashes($rowRoom_update->id_mst_room_types)."' and 
								allocation_date = '".date('Y-m-d',strtotime($startDate))."'";
			
			$updateInventory = mysqli_query($connNew,$availableData1);
			} 
		 $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));	

			  			
  }

if(isset($_REQUEST['chkRoomNum']) && $_REQUEST['chkRoomId']){
		$overbooking=array();
		$overbooking['value']='3';
		$overbooking['content']='';
	echo json_encode($overbooking);
	exit;
}
/*-------------------Update Room Availability End----------------------------*/




//====================================================================================================
$reservation_date = explode(' to ',$_POST['period']);
$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));
$startDate_new='';
$startDate_new=array();
while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
					  					  
			$startDate_new[] = $checkinDate;
			$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
			
}

$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));

	//echo "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types,ahr.over_booking_limit from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($id_mst_hotels)."' ORDER BY ahr.display_order";die;
if($id_mst_room_types == 0){ 
$resRoom = mysqli_query($connNew,"SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($id_mst_hotels)."' ORDER BY ahr.display_order");
}else{
$resRoom = mysqli_query($connNew,"SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($id_mst_hotels)."' and ahr.id_mst_room_types='".addslashes($id_mst_room_types)."' ORDER BY ahr.display_order");
}

//$totalRoom = GetTotalRoom($id_mst_hotels);
$availableData = '<table class="table table-hover">
					<tr>
					  <th>Room Type</th>';					 
					  while (strtotime($checkinDate) <= strtotime($checkoutDate)) {					  
					  	$availableData .= '<th>'.date('l',strtotime($checkinDate)).'<br>'.dateformat_date($checkinDate).'<a href="javascript:void(0);" onclick="getEvents('.strtotime($checkinDate).');" class="text-red"></th>';
					  	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));		
					  }
					  				  
$availableData .= '</tr>';

if(mysqli_num_rows($resRoom) >0 ){
	

$startDate = date ("Y-m-d", strtotime($reservation_date['0']));
$ForcheckLoop = date ("Y-m-d", strtotime($reservation_date['0']));
$startDate2 = date ("Y-m-d", strtotime($reservation_date['0']));
	
$from_event	=$startDate;
$to_event	=$checkoutDate;	

//print_r($_REQUEST);

							
					//}
					
					


$counterDate=0;
$counterRoom=0;
$allRoomTotalInventory=0;
$occupancyArr = array();
while($rowRoom = mysqli_fetch_object($resRoom)){
$startDate = date ("Y-m-d", strtotime($reservation_date['0']));
$startDate2 = date ("Y-m-d", strtotime($reservation_date['0']));

//Fecthing room link plan
$roomPlanLinkList = array();
 $sqlLinkPlan="SELECT id_plan FROM ".TBL_ROOM_PLAN_LINKS." WHERE id_hotel='".$rowRoom->id_mst_hotels."' AND id_room='".$rowRoom->id_mst_room_types."' ORDER BY display_order";
$resRoomPlanLink = mysqli_query($connCrs,$sqlLinkPlan);
while($rowRoomPlanLink=mysqli_fetch_object($resRoomPlanLink)){
	array_push($roomPlanLinkList,'<small class="label pull-right bg-purple">'.selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowRoomPlanLink->id_plan.'"').' Plan</small><small id="sel_'.$rowRoomPlanLink->id_plan.'_'.$rowRoom->id_mst_room_types.'" onclick="makeSelected(this.id);ajaxAddRoom(0,0,'.$rowRoom->id_mst_room_types.','.$rowRoomPlanLink->id_plan.',0,\'quote\');" style="margin-right:4px;cursor:copy" class="label  pull-right bg-primary">Select</small>');
}
//Fetching End
//echo TBL_ASSIGN_HOTEL_ROOM;

$roomAvailabilityInfo='';
$totalRoomInventory =selectColumn(TBL_ASSIGN_HOTEL_ROOM,'inventory','WHERE id_mst_hotels="'.$id_mst_hotels.'" AND id_mst_room_types="'.$rowRoom->id_mst_room_types.'" ');

$allRoomTotalInventory+=$totalRoomInventory;

 $availableData .= '<tr>
                  <td><strong><span class="label bg-aqua ">'.$rowRoom->name.": ".$totalRoomInventory."</span><span style='cursor:zoom-in;' class='pull-right badge bg-yellow'>
<a  data-toggle='collapse' style='color:black;' data-target='.multi-collapse".$rowRoom->id_mst_room_types."' aria-expanded='true' aria-controls='room_".$rowRoom->id_mst_room_types."'><i class='fa fa-arrow-circle-down'></i>&nbsp;Expand</a></span></strong><br>".implode('<br>',$roomPlanLinkList).'</td>';	

					while (strtotime($startDate) <= strtotime($checkoutDate)) {

						//Fecthing rates
							$idsLink = array();
							$ratesPerDay=array();

							 $sqlLink="SELECT id FROM ".TBL_ROOM_PLAN_LINKS." WHERE id_hotel='".$rowRoom->id_mst_hotels."' AND id_room='".$rowRoom->id_mst_room_types."' ORDER BY display_order";
							$resLink = mysqli_query($connCrs,$sqlLink);
							while($rowLink=mysqli_fetch_object($resLink)){
								array_push($ratesPerDay,"<strong>Rs. ".selectColumn(TBL_BASE_RATE,'double_pax_price','WHERE id_room_plan_link="'.$rowLink->id.'" AND id_hotel="'.$rowRoom->id_mst_hotels.'" AND effective_date="'.date('Y-m-d',strtotime($startDate)).'"')."</strong>");
							}

							//rate End

						$roomAlloted = GetTotalRoomAlloted2($startDate,$id_mst_hotels,$rowRoom->id_mst_room_types,$connNew);

						
						$roomAvailable = $roomAlloted;
						$fullAvailable = ($roomAvailable+$rowRoom->over_booking_limit).' AVL';



						// checking for stop sell//
						$invStatus = selectColumn('fo_inventory','status','where id_mst_hotels="'.$rowRoom->id_mst_hotels.'" AND  id_mst_room_types="'.$rowRoom->id_mst_room_types.'" AND allocation_date="'.date('Y-m-d',strtotime($startDate)).'" ');
						
						if($invStatus==0){
							$badge='bg-purple';
							$fullAvailable='Stop Sell';
						}
						else{
							$badge='';
						}
						// end 
						
						if($fullAvailable<=0){
							$availableData .= '<td><span class="badge bg-red '.$badge.'">'.$fullAvailable.'  </span><br>'.implode("<br>",$ratesPerDay).'</td>';
						}
						else{
							$availableData .= '<td><span class="badge bg-green '.$badge.'">'.$fullAvailable.'  </span><br>'.implode("<br>",$ratesPerDay).'</td>';
						}
						

						 
						$totalConfirmed = selectColumn('fo_reservations','count(room_quantity)','LEFT JOIN fo_reservations_details ON '.'fo_reservations'.'.id=fo_reservations_details.id_fo_reservations WHERE fo_reservations.id_mst_hotels="'.$id_mst_hotels.'" AND id_mst_room_types="'.$rowRoom->id_mst_room_types.'" AND dated="'.date('Y-m-d',strtotime($startDate)).'" AND booking_status=1 ');

						$totalTentative = selectColumn('fo_reservations','count(room_quantity)','LEFT JOIN fo_reservations_details ON '.'fo_reservations'.'.id=fo_reservations_details.id_fo_reservations WHERE fo_reservations.id_mst_hotels="'.$id_mst_hotels.'" AND id_mst_room_types="'.$rowRoom->id_mst_room_types.'" AND dated="'.date('Y-m-d',strtotime($startDate)).'" AND booking_status=2 ');

						$totalWaitListed = selectColumn('fo_reservations','count(room_quantity)','LEFT JOIN fo_reservations_details ON '.'fo_reservations'.'.id=fo_reservations_details.id_fo_reservations WHERE fo_reservations.id_mst_hotels="'.$id_mst_hotels.'" AND id_mst_room_types="'.$rowRoom->id_mst_room_types.'" AND dated="'.date('Y-m-d',strtotime($startDate)).'" AND booking_status=3 ');

						 $netBooked=0;
						 $netBooked=$totalConfirmed+$totalTentative;

						 $occupancyArr[$startDate]+=$netBooked;
						
			$roomAvailabilityInfo.='<td>
										<div class="multi-collapse'.$rowRoom->id_mst_room_types.' collapse room_'.$rowRoom->id_mst_room_types.'" aria-expanded="true" >
												<div class="box-body" style="text-align:left;font-weight:bold;">
										
												'.($totalConfirmed==''?0:$totalConfirmed).'<br>'.($totalTentative==''?0:$totalTentative).'<br><span class="label bg-gray">'.($netBooked).'</span><br>'.($totalWaitListed==''?0:$totalWaitListed).'
										</div>
										</div>		
												
									</td>';

						$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));

						
						$counterDate++;	
						
						if($counterDate==7){
						$availableData .= '</tr>';
						}			
					}
					$availableData .= '<tr>
										<td>
											<div id="room_'.$rowRoom->id_mst_room_types.'" class="multi-collapse'.$rowRoom->id_mst_room_types.' collapse " aria-expanded="true" >
												<div class="box-body" style="text-align:right;font-weight:bold;">
													Confirmed <br>Tentative <br><span class="label bg-gray">Net Booked </span><br> Waitlisted
												</div>
											</div>
										</td>
										
										'.$roomAvailabilityInfo.'
										

										</tr>';
					

}//echo '===='.$availableData;
/*
<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="" aria-expanded="true">
                        Collapsible Group Danger
                      </a>

<div id="collapseTwo" class="panel-collapse collapse in" aria-expanded="true" style=""></div>
*/				 
	
$availableData .= '<tr>
                  <td><strong>Total Rooms Available<br> Occupancy (%) </strong></td>';				  
				  
while (strtotime($startDate2) <= strtotime($checkoutDate)) {
	$roomAllotedAll= GetTotalRoomAllotedTwo1($startDate2,$id_mst_hotels,0,$connNew);

	
		
	$inventory = $roomAllotedAll;
	
	if($inventory > 0){
		$availableClass = 'label-success';
	}else {
		$availableClass = 'label-danger';
	}
		$availableData .= '<td><span class="label '.$availableClass.'">'.$inventory.' AVL</span><br><span  class="label bg-orange">'.round(($occupancyArr[$startDate2]/$allRoomTotalInventory)*100,0).'&nbsp; % &nbsp;&nbsp;&nbsp;</span></td>';
		$startDate2 = date ("Y-m-d", strtotime("+1 day", strtotime($startDate2)));
}	
	  							  
$availableData .= '</tr>  
              </table>';
}else {
$availableData .= '<tr align="center">
                  <td colspan="8" >No Data Available. Please try different Search.</td>
                </tr>';
}
			  
echo $availableData;
?>