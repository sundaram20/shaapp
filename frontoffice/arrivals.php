<?php
include_once("../config/auto_loader.php"); 
if($_REQUEST['date']==''){
	
	
	if($_REQUEST['reservation_id_arrivals'] == '' && $_REQUEST['other_reference_no_arrivals'] == ''){
	
	 $newDate	=	date('Y-m-d');
	$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";
	}
	
	
}


if($_REQUEST['date']!=''){
	$newDate	=	date('Y-m-d',strtotime($_REQUEST['date']));
	$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($newDate)."' ";
	}
	
	
	
if($_REQUEST['reservation_id_arrivals'] != ''){	
	$searchDocumentType = " AND `".FO_RESERVATIONS."`.`booking_no` ='".addslashes($_REQUEST['reservation_id_arrivals'])."'";

}
if($_REQUEST['other_reference_no_arrivals'] != ''){	
	$searchDocumentType = " AND `".FO_RESERVATIONS."`.`reference` ='".addslashes($_REQUEST['other_reference_no_arrivals'])."'";

}

	
	
	
	
 $TodaysData	=	$newDate;
     $sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* 
  FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  
  where   
    ".FO_RESERVATIONS_DETAILS.".checkin_status ='0' AND ".FO_RESERVATIONS_DETAILS.".no_showoff ='0'    and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  ".$searchDocumentType." ".$searchDocumentTypeDetails."
 
  group by id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";
 //$sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id    group by id_fo_reservations order by `".FO_RESERVATIONS."`.id desc";

//$sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  where     ".FO_RESERVATIONS_DETAILS.".checkin_status ='0'   group by id_fo_reservations order by `".FO_RESERVATIONS."`.id desc";




//and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($TodaysData)."'
$res = mysqli_query($connNew,$sql);
?>
 <table id="expected_arrivals" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>S.No</th>
                                  <th>Reservation Id</th>
                                  <th>Guest Name</th>
                                  <th>Source</th>
                                  <th>Room Type</th>
                                  <th>Plan</th>
                                  <th>Adults | Childs</th>
                                  <th>Booked Rooms</th>
                                  <th>Checkin Pending</th>
                                  <th>No Show</th>
									<th>Assigned Rooms</th>
                                   <th>Checkin</th>
                                   <th>checkout</th>
                                  <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
<?php 



$y=1;

	if(mysqli_num_rows($res)>0){
	while($row = mysqli_fetch_object($res)){
		
		$roomCheckinArray=array();
		$roomPIcked=0;
		
			$sqlOrderDetailRoomData = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ");
		
			
		
		$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ");
		
		
		//================================
			$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' and id_mst_room_types='".addslashes($row->id_mst_room_types)."'  group by order_by_room ");
		
			$allocation_room_ids = [];	
		$allocated_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_reserved INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$newDate."' and room.id_mst_room_types = '".addslashes($row->id_mst_room_types)."' and resdetails.id_fo_reservations = '".addslashes($row->id)."' and room_availability = 'Reserv'");
if (mysqli_num_rows($allocated_room_query) > 0) {
    while ($row112 = mysqli_fetch_object($allocated_room_query)) {
		$allocation_room_ids[$row112->id] = [
			'room_id' => $row112->id,
			'reservation_id' => $row112->id_fo_reservations,
			'room_no' =>$row112->room_no,
		];
	}
}
		
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			$RoomWiseArray=array();
			$roomNoshowoffArray=array();
			$GetOrderByRoom=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail2= mysqli_fetch_object($sqlOrderDetail2)){
					
							if($rowOrderDetail2->checkin_status==1 ){	
							$roomCheckinArray[]=$rowOrderDetail2->id_mst_room_no_reserved;
							}
							
							if($rowOrderDetail2->no_showoff==1){	
							$roomNoshowoffArray[]=$rowOrderDetail2->id_mst_room_no_reserved;
							}	
							if($rowOrderDetail2->no_showoff==0 && $rowOrderDetail2->checkin_status==0){	
							$GetOrderByRoom[]=$rowOrderDetail2->order_by_room;
							}
							
							
	}	
	
		}
		//==============================
		 $GetOrderByRoom = implode(',',$GetOrderByRoom);
		//debugData($GetOrderByRoom);
		if(is_array($roomCheckinArray) ){
			$roomPIcked = count($roomCheckinArray);
			}else{
			$roomPIcked = 0;
			}
		if(is_array($roomNoshowoffArray) ){
			$roomNoshowoffArrayCount = count($roomNoshowoffArray);
			}else{
			$roomNoshowoffArrayCount = 0;
			}
		
		if(mysqli_num_rows($sqlOrderDetailRoomData) >0 ){
			$RoomWiseArray=array();
			$roomname=array();
			$roomPlanName=array();
			$room_quantity='';
			$adults_per_room='';
			$child_per_room='';
			$sqlOrderDetailRoomData34 = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ");
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetailRoomData)){ 
					
					
					$rowOrderDetail34= mysqli_fetch_object($sqlOrderDetailRoomData34);
					
				$roomname[]	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$roomPlanName[]	=	selectColumn(TBL_RATE_PLAN,'name'," WHERE `id` = '".$rowOrderDetail->id_fo_rate_plan."'");
				$room_quantity	+=	1;//$rowOrderDetail->room_quantity;	
				$adults_per_room	+=	$rowOrderDetail34->adults_per_room; //$rowOrderDetail34->adults_per_room;	
				$child_per_room += ($rowOrderDetail->child_below_5_year + $rowOrderDetail->child_above_5_year);
				
				
				
				
			
				}
		}
		
		$room	=	implode(',',array_unique($roomname));
		$roomPlan	=	implode(',',array_unique($roomPlanName));
		$source	=	selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$row->id_mst_company."'");
				
$Firstname	   =	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
$Lastname		=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$row->id_mst_guest."'");

$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");				
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				

$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));
		//$phone	=	selectColumn(TBL_GUEST,'phone'," WHERE `id` = '".$row->id_mst_guest."'");
		//$row['first_name'].' - '.$row['email'].' - ' . $phone . ' - ' . $row['city'].'
			
		/*$demoData[] = 
    array("id"=>encryptor(encrypt,$row->id),"reservation_id"=>$row->id,"booking_no"=>$row->booking_no,"guest"=>$guest,"source"=>$source,"roomType"=> array($room),"persons"=>$adults_per_room." | 2","booked"=>$room_quantity,"pending"=>"2","checkin"=>date('d-m-Y',strtotime($row->checkin)),"checkout"=>date('d-m-Y',strtotime($row->checkout)));*/
	
	$id=encryptor(encrypt,$row->id);
	$reservation_id=$row->id;
	?>
   
                                 <tr>
                                    <td><?php echo $y++; ?></td>
                                    <td onclick="reservationDetails(<?php echo $id; ?>)"><a href="#" style="color:black;"><?php echo $row->booking_no; ?></a></td>
                                    <td onclick="guestDetails('<?php echo $id; ?>','edit')"><a href="#" style="color:black;"><?php  echo $guestName; ?></a></td>
                                    <td><?php echo $source; ?></td>
                                    <td><?php echo $room; ?></td>
                                    <td><?php echo $roomPlan; ?></td>
                                    <td><?php  echo $adults_per_room." | ".$child_per_room; ?></td>
                                    <td><?php  echo $room_quantity; ?></td>
                                    <td><?php echo (($room_quantity-$roomPIcked)-($roomNoshowoffArrayCount));?></td>
                                    <td><?php echo ($roomNoshowoffArrayCount);?></td>
									  <td>
    <?php
    $roomNumbers = array_column($allocation_room_ids, 'room_no');
    $roomCount = count($roomNumbers);
    $visibleRooms = array_slice($roomNumbers, 0, 4); // show first 4 only
    $displayText = implode(', ', $visibleRooms);
    $tooltipText = implode(', ', $roomNumbers);

    if ($roomCount > 4) {
        $displayText .= '...';
    }
    ?>
    <span data-toggle="tooltip" data-placement="top" title="<?php echo htmlspecialchars($tooltipText); ?>">
        <?php echo $displayText; ?>
    </span>
</td>
									<td><?php  echo date('d-m-Y',strtotime($row->checkin)); ?></td>
									<td><?php  echo date('d-m-Y',strtotime($row->checkout)); ?></td>
                                    <td><button class="btn btn-success btn-xs" onclick="getRoomDetails(<?php echo $reservation_id;?>,2,'<?php echo $id; ?>','<?php echo $row->id_mst_room_types;?>','<?php  echo date('d-m-Y',strtotime($row->checkin)); ?>');" data-toggle="tooltip" title="Allocate / Checkin "><i class="fa fa-eye"></i></button>
                                    <button class="btn btn-danger btn-xs"   onclick="ChangeNoshowOff('<?php echo $reservation_id;?>','<?php echo $row->id_mst_room_types;?>','<?php  echo (($room_quantity-$roomPIcked)-($roomNoshowoffArrayCount));?>',[<?php echo $GetOrderByRoom;?>]);" data-toggle="tooltip" title="No Show Off"><i class="fa-solid fa-hotel"></i></button>
                                    <button class="btn btn-primary btn-xs"   onclick="changeRoomType('<?php echo $reservation_id;?>','<?php echo $row->id_mst_room_types;?>','<?php  echo (($room_quantity-$roomPIcked)-($roomNoshowoffArrayCount));?>');" data-toggle="tooltip" title="Change Room Types"><i class="fa-solid fa-cogs"></i></button>
										
										<?php if($_SESSION['shop_code']=='deo_demo' || $_SESSION['shop_code']=='TIG' || $_SESSION['shop_code']=='VED'){ ?>
										<button class="btn btn-primary btn-xs"    onclick="window.open('../master/guestCard1.php?gId=<?php echo encryptor('encrypt', $row->id_mst_guest); ?>&resId=<?php echo encryptor('encrypt', $row->id_fo_reservations);?>&folioId=0change&page=<?php echo $_REQUEST['page']; ?>', '_blank')" data-toggle="tooltip" title="Print GRC"><i class="fa-solid fa-print"></i></button>
										
										<?php }; ?>
                                    
                                    </td>
                                <tr id="tr_<?php echo $reservation_id;?>_<?php echo $row->id_mst_room_types;?>"  class="Exparrivals" style="display:none"><td>ertert</td></tr>
                               
                               
                                </tr>
                                
                               
    <?php
   
		}
	}else{ 
			echo '<tr>
                                    <td colspan="7" style="text-align:center;">No Record</td><tr>';
			
			}
?>
 </tbody>
                              </table>
<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip(); 
});
</script>
<?php 

//echo json_encode($demoData);

?>