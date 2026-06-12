<?php
include_once("../config/auto_loader.php");

if($_REQUEST['id_hotel'] !=''){
	 $sql = "SELECT id_mst_room_types FROM ".TBL_ASSIGN_HOTEL_ROOM." WHERE id_mst_hotels='".$_REQUEST['id_hotel']."' ";
	$res = mysqli_query($connNew,$sql);
	$options="<option value=''>Select Room</option>";
	while($row=mysqli_fetch_object($res)){

		if($row->id_mst_room_types==$_REQUEST['selectId'])
			$selected='selected="selected"';
		else
			$selected='';

		$options .="<option ".$selected." value='".$row->id_mst_room_types."'>".selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$row->id_mst_room_types.'" ')."</option>";
	}
	echo $options;
}
else{
	echo '<option value="">Select Room</option>';
}
?>