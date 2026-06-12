<?php
include_once("../../config/auto_loader.php");

$dateArr = explode(' to ',$_REQUEST['effective_date']);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$ids_link = explode(',',$_REQUEST['id_link']);
$i=0;

if($_REQUEST['id_link']!=''){
	
	while($i < count($ids_link)){
		$singleGrid='';
		$doubleGrid='';
		$extraBedGrid='';
		$extraChildGrid='';
		$minStayGrid='';
		$maxStayGrid='';
		$dateGrid='';
		$status='';
		$from = date('Y-m-d',strtotime($dateArr[0]));
		$to = date('Y-m-d',strtotime($dateArr[1]));
		while(strtotime($from)<=strtotime($to)){


			$singleGrid=$_REQUEST['single_'.$ids_link[$i].'_'.$from];
			$doubleGrid=$_REQUEST['double_'.$ids_link[$i].'_'.$from];
			$extraBedGrid=$_REQUEST['extraBed_'.$ids_link[$i].'_'.$from];
			$extraChildGrid=$_REQUEST['extraChild_'.$ids_link[$i].'_'.$from];
			$minStayGrid=$_REQUEST['min_'.$ids_link[$i].'_'.$from];
			$maxStayGrid=$_REQUEST['max_'.$ids_link[$i].'_'.$from];
			$status=$_REQUEST['status_'.$ids_link[$i].'_'.$from];
			$dateGrid=$from;
			
			
			$insertGrid = "UPDATE ".TBL_BASE_RATE." 
							SET 
							`single_pax_price`='".$singleGrid."',
							`double_pax_price`='".$doubleGrid."',
							`extra_bed_price`='".$extraBedGrid."',
							`extra_child_price`='".$extraChildGrid."',
							`min_stay`='".$minStayGrid."',
							`max_stay`='".$maxStayGrid."',
							`last_modified`='".date('Y-m-d H:i:s')."',
							`id_mst_user_modified_by`='".$_SESSION['userId']."',
							 `status`='".$status."' ";
			$insertGrid .="WHERE id_hotel='".$_REQUEST['id_hotel']."' AND 
								id_room_plan_link='".$ids_link[$i]."'  AND id_shop='".$_SESSION['shop']."' AND effective_date='".$from."' ";			 
			
			
							
			mysqli_query($connNew,$insertGrid);			 

			$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
		}
		$i++;
	}
	echo "Updated Successfully.";
}
else{
	echo "Updation Failed.";
}	

?>