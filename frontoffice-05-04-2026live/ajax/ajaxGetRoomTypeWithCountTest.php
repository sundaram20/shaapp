<?php include_once("../../config/auto_loader.php");

$id_mst_room_types = $_REQUEST['id_mst_room_types'];
$pending_rooms = $_REQUEST['pending_rooms'];
$get_room_type_query = mysqli_query($connNew, "select * from mst_room_types where status = '1'");
$reservation_id	=$_REQUEST['reservation_id'];
$room_type_text = '';
while ($room_type = mysqli_fetch_object($get_room_type_query)) {
    if ($room_type->id != $id_mst_room_types) {
        $room_type_text .= '<option value="'.$room_type->id.'">'.$room_type->name.'</option>';
    }
}

$room_count_text = '';

for ($i = 1; $i <= $pending_rooms; $i++) {
    $room_count_text .= '<option value="'.$i.'">'.$i.'</option>';
}

$data['room_type_text'] = $room_type_text;
$data['room_count_text'] = $room_count_text;

$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_mst_room_types` = '".$id_mst_room_types."'  and `id_fo_reservations` = '".$_REQUEST['reservation_id']."' group by order_by_room ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$roomNo	  = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$trList	.=	'<tr>
        <td>
          <input type="checkbox" class="selected_rooms_changed_room" name="selected_rooms_changed_room[]" id="selected_rooms_changed_room[]" value='.$reservation_id.'-'.$rowOrderDetail->id_mst_room_types.'-'.$rowOrderDetail->id_mst_room_no_allocation.'-'.$rowOrderDetail->order_by_room.'>
        </td>
        <td>'.$RoomName.'</td>
        <td>'.$roomNo.'</td>
      </tr>';
					
				}
				
			 	
		}
$data['html'] =	'<style>
 
  #roomForm {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    max-width: 100%;
  }

  .table-container {
    overflow-x: auto;
    max-width: 100%;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    min-width: 400px;
  }

  th, td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ccc;
  }

  th {
    background-color: #f4f4f4;
  }

  button[type="submit"] {
    align-self: flex-start;
    padding: 10px 20px;
    font-size: 16px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  button[type="submit"]:hover {
    background-color: #0056b3;
  }

  /* Responsive tweak for smaller screens */
  @media (max-width: 600px) {
    table {
      font-size: 14px;
    }

    th, td {
      padding: 8px;
    }

    button[type="submit"] {
      width: 100%;
    }
  }
</style><form method="post" action="" id="roomForm" name="roomForm">
  <table border="1" cellpadding="6">
    <thead>
      <tr>
        <th>Select</th>
        <th>Room Type</th>
        <th>Room No</th>
      </tr>
    </thead>
    <tbody>'.$trList.'
     
    </tbody>
  </table>

 
</form>';

//
echo json_encode($data);