<?php
include_once("../../config/auto_loader.php");

$id_hotel=$_POST['id_hotel'];
$id_room=$_POST['id_room'];
 $dailybreakup=$_POST['dailybreakup'];


if($dailybreakup=='1'){
	
	$dataArr[] =  '<option value="">Select </option>';
	
				 $selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ". 
				TBL_ROOM_PLAN_LINKS.".id_room = '$id_room' group BY ".TBL_RATE_PLAN.".name";
				
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				$dataArr[]='';
				while($rownewp = mysqli_fetch_object($resnewp)){
					
					
					$dataArr[] =  '<option value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				} 
	
}else if($dailybreakup=='2'){
	 $select=$_POST['select'];
	 
	//$dataArr[] =  '<option value="">Select </option>';
	
	$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN." ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ".  TBL_ROOM_PLAN_LINKS.".id_room = '$id_room' group BY ".TBL_RATE_PLAN.".name";
	
	//$selectnewp="SELECT * FROM '".TBL_RATE_PLAN."' where id=".$select" " ;
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				$dataArr[]='';
				while($rownewp = mysqli_fetch_object($resnewp)){
					
					if($select==$rownewp->id){
						$selected ='selected="selected"';
					}else{
						$selected ="";
					}
					
					$dataArr[] =  '<option value="">Select </option><option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				}
}

else{
	
	$dataArr[] =  '<option value="">Select one</option><option value="1">Multiplan</option>';
   
				/*$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id_hotel' AND ". 
				TBL_ROOM_PLAN_LINKS.".id_room = '$id_room' group BY ".TBL_RATE_PLAN.".name";
				
				*/
				$selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM  ".TBL_RATE_PLAN."
				where status='1' and id_shop='".addslashes($_SESSION['shop'])."'";
				
				
				//selectSql(TBL_RATE_PLAN," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' ",'  order by display_order');
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					$dataArr[] =  '<option value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				} 
				
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


