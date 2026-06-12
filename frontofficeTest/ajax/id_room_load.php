<?php
include_once("../../config/auto_loader.php");

$id_room=$_POST['id_room'];
$id_hotel=$_POST['id_hotel'];
$checkbox=$_POST['checkbox'];
$id_room_type=$_POST['id_room_type'];


$res_checkinDate=date('Y-m-d',strtotime($_POST['res_checkinDate']));
$res_checkOutDate=date('Y-m-d',strtotime($_POST['res_checkOutDate']));


 $SQLReservation="SELECT DISTINCT id_mst_room_no_allocation FROM `fo_reservations_details` WHERE DATE(dated) >= '".$res_checkinDate."' and DATE(dated)<= '".$res_checkOutDate."' ORDER BY `fo_reservations_details`.`id_mst_room_no_allocation` ASC" ;
			$QueryReservation = mysqli_query($connNew,$SQLReservation);

				$roomNoArray=array();
				while($RowReservation = mysqli_fetch_object($QueryReservation)){
					
					$roomNoArray[]=$RowReservation->id_mst_room_no_allocation;
				}

if(!empty($roomNoArray)){
$id_mst_room_no_allocation =implode(',',$roomNoArray);
	$conn	=	" AND id NOT IN (".$id_mst_room_no_allocation.")";
}
//print_r($roomNoArray);

//echo TBL_ROOMNO;die;

if($id_room == 'checkbox'){
	if($checkbox=='1'){
			$selectneww11="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel " ;
			$resneww11 = mysqli_query($connNew,$selectneww11);

				
				while($rowneww11 = mysqli_fetch_object($resneww11)){
					$roomno = $rowneww11->id_mst_room_types;
					
							 $selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$roomno."'  $conn " ;
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
									 <td style="padding:0px 15px"> <input type="checkbox" id="btn'.$rowneww->room_no.'" name="all_room_select[]" value="'.$rowneww->id.'" class="checkedd"  onclick="btncolorchange(this.id)" style="height:17px;width:20px"> </td> 	</tr>';
									
								}
							} 
				}
	}else if($checkbox=='0'){
		//echo "sfs";
		$selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room_type."' $conn "  ;
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
						 <td style="padding:0px 15px"> <input type="checkbox" id="btn'.$rowneww->room_no.'" name="all_room_select[]" class="checkedd"  onclick="btncolorchange(this.id)" value="'.$rowneww->id.'" style="height:17px;width:20px"> </td> 	</tr>';
						
					}} 
	}

}else {
	// $selectneww="SELECT * FROM ".TBL_ROOM_ALLOCATION." where id_mst_room_types=$id_room " ;
					// $resneww = mysqli_query($connNew,$selectneww);
					// while($rowneww = mysqli_fetch_object($resneww)){
					//	$dataArr[] =  '<td><button class="change_color btn btn-success btn-sm" style="padding:5px 23px;margin-bottom:15px;" //onclick="btncolorchange(this.id)" id="btn'.$rowneww->room_no.'">'.$rowneww->room_no.'</button></td>';
					//}  

					  $selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types='".$id_room."' $conn " ;
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
						 <td style="padding:0px 15px"> <input type="checkbox" id="btn'.$rowneww->room_no.'" name="all_room_select[]" class="checkedd"  onclick="btncolorchange(this.id)" value="'.$rowneww->id.'" style="height:17px;width:20px"> </td> 	</tr>';
						
					}} 
			
	}			

	echo json_encode($dataArr);

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


