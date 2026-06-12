<?php
include_once("../config/auto_loader.php"); 
if($_REQUEST['date']==''){
	$newDate	=	date('Y-m-d');
}else{
	$newDate	=	date('Y-m-d',strtotime($_REQUEST['date']));
	}
 $TodaysData	=	$newDate;
$id_fo_reservations	= $_REQUEST['id_fo_reservations'];

//DATE(`".FO_RESERVATIONS_DETAILS."`.dated)='".addslashes($TodaysData)."' 
//and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($TodaysData)."' 
   $sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id 
   where  `".FO_RESERVATIONS."`.id IN ('".$id_fo_reservations."')    
  and ".FO_RESERVATIONS_DETAILS.".checkin_status ='0' 
  and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  group by id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";
 //$sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id    group by id_fo_reservations order by `".FO_RESERVATIONS."`.id desc";

//$sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  where     ".FO_RESERVATIONS_DETAILS.".checkin_status ='0'   group by id_fo_reservations order by `".FO_RESERVATIONS."`.id desc";




//and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($TodaysData)."'
$res = mysqli_query($connNew,$sql);



					 $sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
					 $numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
					 $rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
					 $NightAuditDated = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
?>
<style>
.error{
	color:#F00;
	font-size:12px;}
.deleteBox {
    width: 35px;
    height: 35px;
    background-color: #fff;
    /* White background by default */
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background-color 0.3s;
    border: 1px solid #d2d6de !important;
    /* margin-top : 7px; */

}


.deleteBox:hover {
    background-color: #db3434;
    /* Blue color on hover */
}

.deleteBox:active {
    background-color: #2980b9;
    /* Darker blue color when clicked */
}

.deleteBox i {
    color: #db3434;
    /* Blue color for the icon by default */
    font-size: 15px;
    transition: color 0.3s;
}

.deleteBox:hover i {
    color: #fff;
    /* White color for the icon on hover */
}

.deleteBox:active i {
    color: #fff;
    /* White color for the icon when clicked */
}






#EditReservationModal .modal-dialog {
    width: 100% !important;
    margin: 0 !important;

}

#EditReservationModal {
    padding: 0px !important;
    min-height: 100vh !important;
}


#EditReservationModal .modal-content {
    min-height: 100vh !important;
}

.input-validation-error ~ .select2 .select2-selection__rendered {
  border: 1px solid red;
}
</style>
<div id="saveReservationDateform"  class="saveReservationDateform" >
     <div class="box-header with-border"><span style="text-align:center;"></span>
        <h3 class="box-title"><div id="alloctiontitle"></div></h3>
        <div class="box-tools pull-right">
            <button type="button" class="viewincPopUp_close btn btn-box-tool" data-dismiss="modal"><i
                    class="fa fa-times"></i></button>
        </div>
    </div>
    
 <table id="expected_arrivals" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                  <th>S.No</th>
                                  <th>Reservation Id</th>
                                  <th>Guest Name</th>
                                  <th>Source</th>
                                  <th>Room Type</th>
                                  <th>Adults | Childs</th>
                                  <th>Booked Rooms</th>
                                  <th>Checkin Pending</th>
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
		
		
		
		if($NightAuditDated ==	date('d-m-Y',strtotime($row->checkin))){
          
	$LoadAllocationText	='Checkin';
	}else{
			$LoadAllocationText	='Allocate';
		}
		?>
		<input type="hidden"  name="LoadAllocationText"
 id="LoadAllocationText"   value="<?php echo $LoadAllocationText; ?>" /> 
		<?php 
		$roomCheckinArray=array();
		$roomPIcked=0;
		
		//$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` ");
		//$countRoom	=  selectColumn(FO_RESERVATIONS_DETAILS,'count(id)'," WHERE where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room,order_by_room ");
		$sqlOrderDetailRoomData = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ");
		
		
		//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ";
		
		
		$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ");
		
		
		//================================
			$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' group by id_mst_room_types,id_mst_room_no_allocation ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail2= mysqli_fetch_object($sqlOrderDetail2)){
					
							if($rowOrderDetail2->checkin_status==1){	
							$roomCheckinArray[]=$rowOrderDetail2->id_mst_room_no_allocation;
							}
	}	
	
		}
		//==============================
		
		if(is_array($roomCheckinArray) ){
			$roomPIcked = count($roomCheckinArray);
			}else{
		$roomPIcked = 0;
			}
		
		
		if(mysqli_num_rows($sqlOrderDetailRoomData) >0 ){
			$RoomWiseArray=array();
			$roomname=array();
			$room_quantity='';
			$adults_per_room='';
			$child_per_room='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetailRoomData)){ 
				$roomname[]	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$room_quantity	+=	1;//$rowOrderDetail->room_quantity;	
				$adults_per_room	+=	$rowOrderDetail->adults_per_room;	
				$child_per_room += ($rowOrderDetail->child_below_5_year + $rowOrderDetail->child_above_5_year);
				
				
				
				
				
			
				}
		}
		
		$room	=	implode(',',array_unique($roomname));
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
                                     <td><?php  echo $adults_per_room." | ".$child_per_room; ?></td>
                                    <td><?php  echo $room_quantity; ?></td>
                                    <td><?php echo ($room_quantity-$roomPIcked);?></td>
									<td><?php  echo date('d-m-Y',strtotime($row->checkin)); ?></td>
									<td><?php  echo date('d-m-Y',strtotime($row->checkout)); ?></td>
                                    <td><button class="btn btn-success btn-xs" onclick="getRoomDetailsSingleForm(<?php echo $reservation_id;?>,2,'<?php echo $id; ?>','<?php echo $row->id_mst_room_types;?>');" data-toggle="tooltip" title="view rooms"><i class="fa fa-eye"></i></button></td>
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
                              </table><div class="" style="padding : 15px; display : flex; justify-content: flex-end;">

       <a href="generateOrderPdf3.php?id=<?=encryptor(encrypt, $id_fo_reservations);?>" class="btn n-btn" target="_blank"><img src="../images/pdf-icon.png" style="cursor:pointer;height:20px;" title="Download "  /> Generate PDF</a>&nbsp;&nbsp;
                      
        &nbsp; &nbsp;
        <button type="button" class="btn n-btn" data-dismiss="modal" style="padding: 7px 20px!important; ">Close</button>
    </div></div>
<?php 

//echo json_encode($demoData);

?>