<?php
include_once("../config/auto_loader.php");

if($_REQUEST['htlmReturnId'] !=''){
	$sql = "SELECT id,name FROM ".TBL_RATE_PLAN." WHERE id_shop=".$_SESSION['shop']." AND status=1 ";

	$res = mysqli_query($connNew,$sql);
	$options='';
	while($row=mysqli_fetch_object($res)){
		if($row->id==$_REQUEST['selectId'])
			$selected='selected="selected"';
		else
			$selected='';

		$options.='<option '.$selected.' value="'.$row->id.'">'.$row->name.'</option>';
	}
	echo $options;
}
else{
	echo '';
}
?>