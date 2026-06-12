<?php
include_once("../../config/auto_loader.php");
include_once("../../functions/inventoryUpdateFunctions.php"); 
//include_once("../../functions/inventoryUpdateFunctionsNewBE.php"); 
/*echo "<pre>";
print_r($_REQUEST);
echo "</pre>";
exit;*/
$dateArr = explode(' to ',$_REQUEST['effective_date']);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$ids_room = explode(',',$_REQUEST['id_room']);
$i=0;


while($i < count($ids_room)){
	$singleGrid='';
	/*$doubleGrid='';
	$extraBedGrid='';
	$extraChildGrid='';
	$minStayGrid='';
	$maxStayGrid='';*/
	$dateGrid='';
	$stopSellGrid='';
	$from = date('Y-m-d',strtotime($dateArr[0]));
	$to = date('Y-m-d',strtotime($dateArr[1]));
	while(strtotime($from)<=strtotime($to)){

		$singleGrid=$_REQUEST['room_'.$ids_room[$i].'_'.$from];
		/*$doubleGrid=$_REQUEST['double_'.$ids_link[$i].'_'.$from];
		$extraBedGrid=$_REQUEST['extraBed_'.$ids_link[$i].'_'.$from];
		$extraChildGrid=$_REQUEST['extraChild_'.$ids_link[$i].'_'.$from];
		$minStayGrid=$_REQUEST['min_'.$ids_link[$i].'_'.$from];
		$maxStayGrid=$_REQUEST['max_'.$ids_link[$i].'_'.$from];*/
		$stopSellGrid=$_REQUEST['status_'.$ids_room[$i].'_'.$from];
		$dateGrid=$from;

		$insertGrid = "UPDATE ".TBL_BE_INVENTORY." 
					SET 
					`online_allocation`='".$singleGrid."',
					`status`='".$stopSellGrid."',
					`last_modified`='".date('Y-m-d H:i:s')."',
					`id_mst_user_modified_by`='".$_SESSION['userId']."'
					";
		$insertGrid .="WHERE id_mst_hotels='".$_REQUEST['id_hotel']."' AND 
						id_mst_room_types='".$ids_room[$i]."'  AND id_shop='".$_SESSION['shop']."' AND allocation_date='".$from."' ";			 
		
						
		mysqli_query($connNew,$insertGrid);			 

		$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
	}
	$i++;
}
updateOTA($_REQUEST['id_hotel'],$dateArr[0],$dateArr[1],$connNew);

echo "Update Successfully";
?>