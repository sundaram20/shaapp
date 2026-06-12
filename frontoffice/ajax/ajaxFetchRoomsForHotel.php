<?php
include_once("../../config/auto_loader.php");

if($_REQUEST['id_hotel'] !=''){
	$sql = "SELECT room_id FROM ".TBL_ASSIGN_HOTEL_ROOM." WHERE hotel_id='".$_REQUEST['id_hotel']."' and status='1' ";
	$res = mysqli_query($connCrs,$sql);
	$options="<option value=''>Select Room</option>";
	while($row=mysqli_fetch_object($res)){

		if($row->room_id==$_REQUEST['selectId'])
			$selected='selected="selected"';
		else
			$selected='';

		$options .="<option ".$selected." value='".$row->room_id."'>".selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$row->room_id.'" ')."</option>";
	}
	echo $options;
}
else{
	echo '<option value="">Select Room</option>';
}
?>