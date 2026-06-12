<?php 
	include_once("../../config/auto_loader.php");
	$sql = executeSql(" SELECT * FROM `".TBL_ROOMNO."` ");

 				 				 
								if($db->num_rows2($sql) > 0){$counter = 1;
								
								  while($row = $db->fetch_object2($sql)){
									 $status = $row->room_status;
									 if($status==1){
										 $status = "Dirty";
									 }else if($status==2){
										 $status = "Reserved";
									 }else if($status==3){
										 $status = "Occupied";
									 }else if($status==4){
										 $status = "Clean";
									 }else if($status==5){
										 $status = "Blocked";
									 }else if($status==6){
										 $status = "Under Maintenance";
									 }
								$data[] = '<tr>';
								$data[] =  '<td>'.selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$row->id_mst_room_types."'").'<a href="javascript:void(0);" onclick="audittrial(id='.$row->id.');" style="color:green;" id="res_guestAddId"> <i class="fa fa-search-plus"></i> </a></td>';
								
								$data[] = '<td>'.$row->room_no.'</td>';
								 $data[] = '<td>'.selectColumn(TBL_HOTEL_ROOM_BLOCK,'name'," WHERE `id` = '".$row->id_mst_hotel_room_block."'").'</td>';
								$data[] = '<td>'.$status.'</td>';
								$data[] = '<td>'.selectColumn(FO_HOUSE_KEEPING,'date'," WHERE `id_mst_room_allocation` = '".$row->id."'").'</td>';
								$data[] = '<td>'.selectColumn(FO_HOUSE_KEEPING,'time'," WHERE `id_mst_room_allocation` = '".$row->id."'").'</td>';
								$data[] = '<td>'.selectColumn(FO_HOUSE_KEEPING,'executive'," WHERE `id_mst_room_allocation` = '".$row->id."'").'</td>';
								$data[] = '<td><button class="btn btn-success" onclick="saveHouseKeeping(id='.$row->id.');" style="margin:5px;">Change</button></td>';
								 
								$data[] = '</tr>';
								
								  }
								}
						echo json_encode($data);
								  ?> 
 