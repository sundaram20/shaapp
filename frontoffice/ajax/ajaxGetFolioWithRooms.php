<?php include_once("../../config/auto_loader.php");
$id_mst_room_types = $_REQUEST['id_mst_room_types'];
$reservation_id = $_REQUEST['reservation_id'];
$expected_arrivals_rooms = json_decode($_REQUEST['expected_arrivals_rooms'], true);
$today = date('Y-m-d' ,strtotime($_REQUEST['today']));
$selectedRoomCount	= count($expected_arrivals_rooms);
$folio_array = [];
$text = '';
$id_mst_guest_reservations	= selectColumn('fo_reservations','id_mst_guest'," WHERE `id` = '".$reservation_id."'");
$occupied_room_based_on_reservation_query = mysqli_query($connNew, "SELECT * from fo_reservations_details WHERE no_showoff = '0' and dated = '".$today."' and id_fo_reservations = '".$reservation_id."' and checkin_time != '' group by id_fo_folio_to");

if (mysqli_num_rows($occupied_room_based_on_reservation_query) > 0) {
    while ($row = mysqli_fetch_object($occupied_room_based_on_reservation_query)) {
        $folio = selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$row->id_fo_folio_to."'");
        $id_owner_room = selectColumn('fo_bill','id_owner_room'," WHERE `id_fo_folio_to` = '".$row->id_fo_folio_to."'");
        $id_mst_attributes_title = selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");
        $Title = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."' and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 
		$OwnerRoom_no = selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$id_owner_room."'");                
        $Firstname = selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
        $Lastname = selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$row->id_mst_guest."'");
        $guestName = $Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));
        $folio_text = $folio.'- Guest: '.$guestName.' Room No: '.$OwnerRoom_no;

        $option = '';
        $reservation_query = mysqli_query($connNew, "SELECT * from fo_reservations_details WHERE id_fo_folio_to = '".$row->id_fo_folio_to."'");
        if (mysqli_num_rows($reservation_query) > 0) {
            while ($result = mysqli_fetch_object($reservation_query)) {
				$selected = $id_owner_room == $result->id_mst_room_no_allocation ? 'selected' : '';
                $room_no = selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$result->id_mst_room_no_allocation."'");
                $option .= '<option '.$selected.' value="'.$result->id_mst_room_no_allocation.'">'.$room_no.'</option>';
            }
        }
		$folioLinked='Link Folio Owner: '.$folio_text;
       // $text .= '<div class="form-group"><label for="selectInput">'.$folio_text.':</label><select class="form-control selectInput" data-folio="'.$row->id_fo_folio_to.'" id="selectInput_'.$row->id_fo_folio_to.'">'.$option.'</select></div>';
    }
}

$pending_folios = [];
$checkin_pending_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_reservations = '".$reservation_id."' and no_showoff = '0' and checkin_time is null group by fo_folio_temp");
if (mysqli_num_rows($checkin_pending_query) > 0) {
	while ($row = mysqli_fetch_object($checkin_pending_query)) {
        $pendings = mysqli_query($connNew, "select * from fo_reservations_details where fo_folio_temp = '".$row->fo_folio_temp."' and no_showoff = '0' and checkin_time is null group by order_by_room");
		$pending_folios[$row->fo_folio_temp] = [
            'folio' => $row->fo_folio_temp,
            'count' => mysqli_num_rows($pendings),
        ];
	}
}

$roomOptions = [];
$lastAddedRoomIndex = 0;

foreach ($pending_folios as $folio) {
    $option = '';

    $roomsToAdd = min($folio['count'], count($expected_arrivals_rooms) - $lastAddedRoomIndex);
    
    for ($i = 0; $i < $roomsToAdd; $i++) {
        $room_id = $expected_arrivals_rooms[$lastAddedRoomIndex]['key'];
        $room_no = $expected_arrivals_rooms[$lastAddedRoomIndex]['value'];
        $option .= '<option value="'.$room_id.'">'.$room_no.'</option>';
		
		$checl	=$i==0?'checked':'';
		$chec2	=$i==0?'1':'0';
		$RoomArray	.='<tr>
                                <td class="px-4 py-2 border">Room '.$room_no.'</td>
                                <td class="px-4 py-2 border flex items-center " >';
                                    
              /*      <select class="select2 w-full"  style="width : 80%;" name="id_mst_guest_form_'.$room_id.'" id="id_mst_guest_form_'.$room_id.'">
                            <option value="">Select Guest</option>';
                            
						   
						    $SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
                            $query = mysqli_query($connNew, $SQL);
                            while ($resultCat=mysqli_fetch_assoc($query)) {
								
								if($i=='0'){
									
									$id_mst_guest_reservations;
								}else{
									$id_mst_guest_reservations='';
									}
                                if ($id_mst_guest_reservations == $resultCat['id']) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }
                                $RoomArray .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['guest_reg_no'] . ' - ' . $resultCat['first_name'].' '. $resultCat['last_name'].' - '.$resultCat['email'].'-' . $resultCat['city'].'</option>';
                            }
                             $RoomArray .= '</select>';*/

                             $selectedGuestId = '';

if ($i == 0) {
    $selectedGuestId = $id_mst_guest_reservations;
}

$RoomArray .= '
<select
    class="select2 room-guest-select w-full itemGuest"
    style="width:80%;"
    name="id_mst_guest_form_'.$room_id.'"
    id="id_mst_guest_form_'.$room_id.'"
    data-room-id="'.$room_id.'"
    data-selected-guest="'.$selectedGuestId.'"
>
    <option value="">Select Guest</option>
</select>
';
                    //data-target="#guestMultipleeditModal"
                   $RoomArray	.='<div class="input-group-addon form-group col-sm-1" data-toggle="modal" onclick="OpenGuestRoomModel('.$room_id.');"  style="width: auto;border: 1px solid #fefefe;float:right;">
                    <a href="javascript:void(0);" style="color:black;" id="res_guestAddId">
                        <i class="fa fa-plus"></i>
                    </a>
                </div> <p class="error id_mst_guest_form-error"></p>
                
                                    
                                </td>
                                <td class="px-4 py-2 border text-center">';
								
							if($folioLinked==''){
								
								
								if($folioLinked=='' && $selectedRoomCount=='1'){
									
									$disable	='disabled="disabled"';
									
									}	
                                     $RoomArray	.='<input type="checkbox" name="room_check_folio_guest" id="room_check_folio_guest_'.$room_id.'" '.$checl.' value="'.$chec2.'" onclick="onlyOne(this,'.$room_id.')"  '.$disable.'/>';
							}
                                 $RoomArray	.='</td>
                            </tr>
							
							';

        $lastAddedRoomIndex++;
    }
    
    $roomOptions[$folio['folio']] = $option;
}


$RoomCheckinData	=	'<br/> <div class="box-body table-responsive" style="margin-top: -20px;max-height: 400px;">
                    <!--<table class="table w-full">-->
					<table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%">
                        <thead class="bg-gray-200 sticky top-0 z-[2]">
                            <tr>
                                <th class="border-b border-gray-300 text-gray-600 font-medium text-center">Room No.</th>
                                <th class="border-b border-gray-300 text-gray-600 font-medium text-center">Guest Name</th>
                                <th class="border-b border-gray-300 text-gray-600 font-medium text-center">Folio Owner</th>
                            </tr>
                        </thead>
                        <tbody>'.$RoomArray.'
                            <!--<tr>
                                <td class="px-4 py-2 border">Room 10112</td>
                                <td class="px-4 py-2 border flex items-center " >
                                    <select class="select2 w-full" name="options " style="width : 80%;">
                                        <option value="Option 1">Option 1</option>
                                        <option value="Option 2">Option 2</option>
                                        <option value="Option 3">Option 3</option>
                                    </select>
                                    <button type="button" class="px-2 py-1 bg-blue-500 text-white rounded " style="width : 20%;">+</button>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" />
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2 border">Room 101</td>
                                <td class="px-4 py-2 border flex items-center " >
                                    <select class="select2 w-full" name="options " style="width : 80%;">
                                        <option value="Option 1">Option 1</option>
                                        <option value="Option 2">Option 2</option>
                                        <option value="Option 3">Option 3</option>
                                    </select>
                                    <button type="button" class="px-2 py-1 bg-blue-500 text-white rounded " style="width : 20%;">+</button>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" />
                                </td>
                            </tr>

                            <tr>
                                <td class="px-4 py-2 border">Room 101</td>
                                <td class="px-4 py-2 border flex items-center " >
                                    <select class="select2 w-full" name="options " style="width : 80%;">
                                        <option value="Option 1">Option 1</option>
                                        <option value="Option 2">Option 2</option>
                                        <option value="Option 3">Option 3</option>
                                    </select>
                                    <button type="button" class="px-2 py-1 bg-blue-500 text-white rounded " style="width : 20%;">+</button>
                                </td>
                                <td class="px-4 py-2 border text-center">
                                    <input type="checkbox" />
                                </td>
                            </tr>-->
                        </tbody>
                    </table>
                </div>';


$result['folioLinked'] = $folioLinked!=''?$folioLinked:'';
$result['text'] = $text;
$result['pending_folios'] = array_values($pending_folios);
$result['roomOptions'] = $roomOptions;
$result['RoomCheckinList'] = $RoomCheckinData;

echo json_encode($result);
die;
?>