<?php
include_once("../../config/auto_loader.php");

$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));

$id_room = $_POST['id_room'];
$id_hotel = $_POST['id_hotel'];
$checkbox = $_POST['checkbox'];
$id_room_type = $_POST['id_room_type'];



$res_checkinDate = date('Y-m-d', strtotime($_POST['res_checkinDate']));
$res_checkOutDate = date('Y-m-d', strtotime($_POST['res_checkOutDate']));

$SQLReservation = "SELECT DISTINCT id_mst_room_no_allocation FROM `fo_reservations_details` WHERE DATE(dated) >= '".$res_checkinDate."' and DATE(dated) <= '".$res_checkOutDate."' ORDER BY `fo_reservations_details`.`id_mst_room_no_allocation` ASC";
$QueryReservation = mysqli_query($connNew,$SQLReservation);
$roomNoArray = array();
while($RowReservation = mysqli_fetch_object($QueryReservation)) {
	$roomNoArray[] = $RowReservation->id_mst_room_no_allocation;
}

if (!empty($roomNoArray)) {
	$id_mst_room_no_allocation = implode(',',$roomNoArray);
	$conn = " AND id NOT IN (".$id_mst_room_no_allocation.")";
}

$occupied_room_ids = [];
$occupied_room_query = mysqli_query($connNew, "SELECT DISTINCT room.id,room.room_no,room.id_mst_room_types,room.room_status,resdetails.id_fo_reservations, resdetails.id_mst_guest,resdetails.id_fo_folio_to ,resdetails.id_fo_bill, resdetails.order_by_room, resdetails.child_below_5_year,resdetails.child_above_5_year,resdetails.adults_per_room, resdetails.checkin_date, resdetails.checkout_date, resdetails.checkout_status, reservation.booking_status, resdetails.`no_showoff`, resdetails.`dated`, reservation.checkout as reservation_checkout, resdetails.checkin_time FROM `mst_room_no_allocation` as room INNER JOIN fo_reservations_details as resdetails ON room.id=resdetails.id_mst_room_no_allocation INNER JOIN fo_reservations as reservation ON resdetails.id_fo_reservations=reservation.id WHERE resdetails.`no_showoff`='0' and resdetails.`dated`='".$today."' and room.id_mst_room_types = '".$id_room_type."' and room_availability = 'Checkin'");
if (mysqli_num_rows($occupied_room_query) > 0) {
    while ($row = mysqli_fetch_object($occupied_room_query)) {
		$occupied_room_ids[] = $row->id;
	}
}

if ($id_room == 'checkbox') {
	if ($checkbox == '1') {
		$selectneww11 = "SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel";
		$resneww11 = mysqli_query($connNew,$selectneww11);
				
		while ($rowneww11 = mysqli_fetch_object($resneww11)) {
			$roomno = $rowneww11->id_mst_room_types;
			$selectneww = "SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$roomno."' and management_block = 'No' $conn";
			$resneww = mysqli_query($connNew,$selectneww);
			while ($rowneww = mysqli_fetch_object($resneww)) {
				$check_checkout = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_allocation = '".$room['room_id']."' and checkin_status = '1' and checkout_status = '0' and room_availability = 'checkout' and checkout_date is not null");
				if (!in_array($rowneww->id, $occupied_room_ids) && mysqli_num_rows($check_checkout) == 0) {
					$Blo = $rowneww->id_mst_hotel_room_block;
					$selectneww1 = "SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id = $Blo";
					$resneww1 = mysqli_query($connNew, $selectneww1);
					while ($rowneww1 = mysqli_fetch_object($resneww1)) {
						$block1 = $rowneww1->name;
						$dataArr .= '<option value="'.$rowneww->id.'"> '.$rowneww->room_no.' </option>';
					}
				}
			}
		}
	} else if($checkbox == '0') {
		$selectneww = "SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room_type."' and management_block = 'No' $conn";
		$resneww = mysqli_query($connNew,$selectneww);
		while ($rowneww = mysqli_fetch_object($resneww)) {
			$check_checkout = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_allocation = '".$room['room_id']."' and checkin_status = '1' and checkout_status = '0' and room_availability = 'checkout' and checkout_date is not null");
			if (!in_array($rowneww->id, $occupied_room_ids) && mysqli_num_rows($check_checkout) == 0) {
				$Blo = $rowneww->id_mst_hotel_room_block;
				$selectneww1="SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id=$Blo " ;
				$resneww1 = mysqli_query($connNew,$selectneww1);
				while ($rowneww1 = mysqli_fetch_object($resneww1)) {
					$block1 = $rowneww1->name;
					$dataArr.=  '<option value="'.$rowneww->id.'"> '.$rowneww->room_no.' </option>';
				}
			}
		}
	}
} else {
	$selectneww = "SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room."' and management_block = 'No' and room_status = '4' $conn";
	$resneww = mysqli_query($connNew, $selectneww);
	while ($rowneww = mysqli_fetch_object($resneww)) {
		$check_checkout = mysqli_query($connNew, "select * from fo_reservations_details where id_mst_room_no_allocation = '".$room['room_id']."' and checkin_status = '1' and checkout_status = '0' and room_availability = 'checkout' and checkout_date is not null");
		if (!in_array($rowneww->id, $occupied_room_ids) && mysqli_num_rows($check_checkout) == 0) {
			$Blo = $rowneww->id_mst_hotel_room_block;
			$selectneww1 = "SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id = $Blo";
			$resneww1 = mysqli_query($connNew,$selectneww1);
			while ($rowneww1 = mysqli_fetch_object($resneww1)) {
				$block1 = $rowneww1->name;
				$dataArr.=  '<option value="'.$rowneww->id.'"> '.$rowneww->room_no.' </option>';
			}
		}
		
		
		
	}
}
 	 $room_no_allocation = "select * from mst_room_no_allocation where id='".$_POST['id_mst_room_no_allocation']."' and id_mst_room_types='".$id_room."'";
        $room_no_allocation_query = mysqli_query($connNew,$room_no_allocation);
        if (mysqli_num_rows($room_no_allocation_query)>0) {
          $room_no_allocation_record = mysqli_fetch_assoc($room_no_allocation_query);
          $room_no_allocation_id = $room_no_allocation_record['id'];
          $room_no_text = $room_no_allocation_record['room_no'];
			 $selected = 'selected="selected"';
          $dataArr.=  '<option '.$selected.' value="'.$room_no_allocation_id.'" >'.$room_no_text.'</option>';
        }
	echo ($dataArr);
?>


<?php
//old

/*
if($id_room == 'all'){
	$id_hotel=$_POST['id_hotel'];

	$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel " ;
	$resneww = mysqli_query($connNew,$selectneww);

		
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
               // $selectnew="SELECT * FROM ".TBL_ROOM_ALLOCATION." where id_mst_room_types=$roomno " ;
				//$resnew = mysqli_query($connNew,$selectnew);
			//	while($rowneww = mysqli_fetch_object($resnew)){
					//	$dataArr[] =  '<td><button class="change_color btn btn-success btn-sm" style="padding:5px 23px;margin-bottom:15px;" //onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></td>';
				//	}  
					
				$selectnew="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types=$roomno " ;
				$resnew = mysqli_query($connNew,$selectnew);
				while($rowneww = mysqli_fetch_object($resnew)){
						$dataArr[] =  '<td><button class="change_color btn btn-success btn-sm" style="padding:5px 23px;margin-bottom:15px;" onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></td>';
					}
	}

}else{
					// $selectneww="SELECT * FROM ".TBL_ROOM_ALLOCATION." where id_mst_room_types=$id_room " ;
					// $resneww = mysqli_query($connNew,$selectneww);
					// while($rowneww = mysqli_fetch_object($resneww)){
					//	$dataArr[] =  '<td><button class="change_color btn btn-success btn-sm" style="padding:5px 23px;margin-bottom:15px;" //onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></td>';
					//}  

					$selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types=$id_room " ;
					 $resneww = mysqli_query($connNew,$selectneww);
					 while($rowneww = mysqli_fetch_object($resneww)){
						$Blo = $rowneww->id_mst_hotel_room_block;
						 
						$selectneww1="SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id=$Blo " ;
					$resneww1 = mysqli_query($connNew,$selectneww1);
					while($rowneww1 = mysqli_fetch_object($resneww1)){
						 $block1 = $rowneww1->name;	 
						 
						 
						$dataArr[] =  '<tr> <td style="padding:0px 15px"> '.$rowneww->room_no.' </td>
                         <td style="padding:0px 15px"> '.$block1.' </td>	
						 <td style="padding:0px 15px"> Vacant </td>
						 <td style="padding:0px 15px"> <input type="checkbox" id="btn'.$rowneww->room_no.'" name="all_room_select1"  onclick="btncolorchange(this.id)" style="height:17px;width:20px"> </td> 	</tr>';
						
					}} 
            }

*/














	
 ?>


