<?php
include_once("../../config/auto_loader.php");

 $first_name = $_REQUEST['room_type'];
$last_name = $_REQUEST['room_no'];
 $email = $_REQUEST['room_status'];
$last_cleaned = $_REQUEST['last_cleaned'];
$executive = $_REQUEST['executive'];
 
 $id=$_POST['id']; 

				$selectnew="SELECT * FROM ".TBL_ROOMNO."  where id=$id  ";
				$resnew = mysqli_query($connNew,$selectnew); 
				  while($rownew = mysqli_fetch_object($resnew)){
  			          $roomno = $rownew->id_mst_room_types;
			           $status = $rownew->room_status;
			           $rblo = $rownew->id_mst_hotel_room_block;
			 
			 
					 $selectneww21="SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK."  where id=$rblo  ";
				     $resneww21 = mysqli_query($connNew,$selectneww21);
						while($rowneww21 = mysqli_fetch_object($resneww21)){ 
							$roname = $rowneww21->name;
			        }
					 $selectneww="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno  ";
				     $resneww = mysqli_query($connNew,$selectneww);
						while($rowneww = mysqli_fetch_object($resneww)){ 
							$roomname = $rowneww->name;
			        }
					
					 $selectnew1="SELECT * FROM ".FO_HOUSE_KEEPING."  where id_mst_room_allocation=$id  ";
				     $resnew1 = mysqli_query($connNew,$selectnew1);
						while($rownew1 = mysqli_fetch_object($resnew1)){ 
							$date = $rownew1->date;
							$time = $rownew1->time;
							$exe = $rownew1->executive;
							$act = $rownew1->activity;
							$remark = $rownew1->remarks;
			        }
			 
					 
						$data['id'] =$rownew->id;
						$data['id_mst_room_type'] = $roomname ;
						$data['roomno'] =$rownew->room_no;
						$data['block_floor'] =$roname;
						$data['room_status'] =$status;
						$data['activity'] =$act;
						$data['last_cleaned'] =date('Y-m-d', strtotime( $date ));
						$data['last_cleaned_time'] ="<option value='".$time."' selected='selected'>".$time.'</option><option value="12.00am">12.00am</option> <option value="12.15am">12.15am</option> <option value="12.30am">12.30am</option> <option value="12.45am">12.45am</option> <option value="1.00am">1.00am</option>  <option value="1.15am">1.15am</option>  <option value="1.30am">1.30am</option>  <option value="1.45am">1.45am</option> <option value="2.00am">2.00am</option>  <option value="2.15am">2.15am</option>  <option value="2.30am">2.30am</option>  <option value="2.45am">2.45am</option>  <option value="3.00am">3.00am</option>  <option value="3.15am">3.15am</option> <option value="3.30am">3.30am</option>  <option value="3.45am">3.45am</option>  <option value="4.00am">4.00am</option><option value="4.15am">4.15am</option><option value="4.30am">4.30am</option> <option value="4.45am">4.45am</option> <option value="5.00am">5.00am</option>  <option value="5.15am">5.15am</option> <option value="5.30am">5.30am</option> <option value="5.45am">5.45am</option> <option value="6.00am">6.00am</option>  <option value="6.15am">6.15am</option> <option value="6.30am">6.30am</option> <option value="6.45am">6.45am</option><option value="7.00am">7.00am</option> <option value="7.15am">7.15am</option> <option value="7.30am">7.30am</option> <option value="7.45am">7.45am</option> <option value="8.00am">8.00am</option> <option value="8.15am">8.15am</option> <option value="8.30am">8.30am</option> <option value="8.45am">8.45am</option><option value="9.00am">9.00am</option> <option value="9.15am">9.15am</option> <option value="9.30am">9.30am</option> <option value="9.45am">9.45am</option><option value="10.00am">10.00am</option> <option value="10.15am">10.15am</option> <option value="10.30am">10.30am</option> <option value="10.45am">10.45am</option><option value="11.00am">11.00am</option> <option value="11.15am">11.15am</option> <option value="11.30am">11.30am</option> <option value="12.45am">11.45am</option><option value="12.00pm">12.00pm</option> <option value="12.15pm">12.15pm</option> <option value="12.30am">12.30pm</option> <option value="12.45pm">12.45pm</option> <option value="1.00pm">1.00pm</option> <option value="1.15pm">1.15pm</option>  <option value="1.30pm">1.30pm</option>  <option value="1.45pm">1.45pm</option> <option value="2.00pm">2.00pm</option>  <option value="2.15pm">2.15pm</option>  <option value="2.30pm">2.30pm</option>  <option value="2.45pm">2.45pm</option>  <option value="3.00pm">3.00pm</option>  <option value="3.15pm">3.15pm</option> <option value="3.30pm">3.30pm</option>  <option value="3.45pm">3.45pm</option>  <option value="4.00pm">4.00pm</option><option value="4.15pm">4.15pm</option><option value="4.30pm">4.30pm</option> <option value="4.45pm">4.45pm</option> <option value="5.00pm">5.00pm</option>  <option value="5.15pm">5.15pm</option> <option value="5.30pm">5.30pm</option> <option value="5.45pm">5.45pm</option> <option value="6.00pm">6.00pm</option>  <option value="6.15pm">6.15pm</option> <option value="6.30pm">6.30pm</option> <option value="6.45pm">6.45pm</option><option value="7.00pm">7.00pm</option> <option value="7.15pm">7.15pm</option> <option value="7.30pm">7.30pm</option> <option value="7.45pm">7.45pm</option> <option value="8.00pm">8.00pm</option> <option value="8.15pm">8.15pm</option> <option value="8.30pm">8.30pm</option> <option value="8.45pm">8.45pm</option><option value="9.00pm">9.00pm</option> <option value="9.15pm">9.15pm</option> <option value="9.30pm">9.30pm</option> <option value="9.45pm">9.45pm</option><option value="10.00pm">10.00pm</option> <option value="10.15pm">10.15pm</option> <option value="10.30pm">10.30pm</option> <option value="10.45pm">10.45pm</option><option value="11.00pm">11.00pm</option> <option value="11.15pm">11.15pm</option> <option value="11.30pm">11.30pm</option> <option value="11.45pm">11.45pm</option>';
						$data['executive'] =$exe;
						$data['remarks'] =$remark;
				 				
                       
						 mysqli_query($connNew,$insertGrid);  
				 
				 }
						

 
	echo json_encode($data);

	
 ?>


