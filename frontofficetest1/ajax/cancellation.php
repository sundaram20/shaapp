<?php
include_once("../../config/auto_loader.php");

	
	$dataArr[] =  '<option value="">Select Cancellation</option>';
	
   //echo "select * from mst_attributes where status='1' AND table_name='cancellation_reason' ORDER BY `field_value`";
				 $selectnewp="select * from mst_attributes where status='1' AND table_name='cancellation_reason' ORDER BY `field_value`";
				
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					$dataArr[] =  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->field_value.'</option>';
				} 


		
	echo json_encode($dataArr);
	
	// all roomtypes plan	
/*	$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = $id_hotel " ;
	$resneww = mysqli_query($connNew,$selectneww);

	$dataArr[] =  '<option value="">Select one</option><option value="1">Multiplan</option>';
	
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
               
				 $selectnewp="SELECT ".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ". 
				TBL_ROOM_PLAN_LINKS.".id_room = '$roomno' group BY ".TBL_RATE_PLAN.".name";
				
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					$dataArr[] =  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				} 



			  /* $selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name;
					$id = $rownew->id;
					
				if($id == $id_room){
						$selected="selected";
					}
					else{
						$selected="";
					}
				$dataArr[] =  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
		
				}	
	}  */	

	
 ?>


