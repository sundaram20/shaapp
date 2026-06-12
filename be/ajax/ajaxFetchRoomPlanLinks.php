<?php 
include_once("../../config/auto_loader.php");
/*debugData($_REQUEST);
exit;*/
$options='';
if($_REQUEST['id_hotel'] !=''){
	$sql= "SELECT * FROM ".TBL_PACKAGE_LINKING." WHERE id_hotel=".$_REQUEST['id_hotel']." AND status=1 ";
	
	$res = mysqli_query($connNew,$sql);

	if($_REQUEST['all']=='set'){
		$selectAll='selected="selected"';
	}
	else{
		$selectAll='';
	}
	
	while($row=mysqli_fetch_object($res)){

		$roomName = selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$row->id_room.'" ');
		$planName = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$row->id_plan.'" ');

		if($_REQUEST['id_room_plan_link']==$row->id){
			$selected ='selected="selected"';
		}
		else{
			$selected ='';
		}

		$options.="<option ".$selectAll." ".$selected." value='".$row->id."'>".strtoupper($roomName)."-".strtoupper($planName)."</option>";
	}
	echo $options;
}
else{
	echo $options;
}
?>