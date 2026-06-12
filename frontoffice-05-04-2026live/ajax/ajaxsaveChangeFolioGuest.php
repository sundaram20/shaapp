<?php
include_once("../../config/auto_loader.php");
//debugData($_REQUEST);
//die;
if($_REQUEST['id_mst_guest_form']!=''){
	
	
	$roomDetails	=	explode('_',$_REQUEST['id_room_no_edit']);
	
	$FOlio_id	= $roomDetails[0];
	$id_mst_guest	= $roomDetails[1];
	$order_by_room	= $roomDetails[2];
	$id_reservation	= $_REQUEST['editid'];
	
	if($FOlio_id =='000'){
	  $roomdetails = " UPDATE  `fo_folio` SET				 
						
						`id_mst_guest`='".$_REQUEST['id_mst_guest_form']."'
					
						
						WHERE  						
						 id='".$_REQUEST['id_folio']."'
						
						";
				
				mysqli_query($connNew,$roomdetails);
	}else{
		 $roomdetails = " UPDATE  `fo_reservations_details` SET
						`id_mst_guest`='".$_REQUEST['id_mst_guest_form']."'					
						
						WHERE  						
						 id_fo_reservations='".$id_reservation."' and  order_by_room='".$order_by_room."'
						
						";
				
				mysqli_query($connNew,$roomdetails);
		
		
		}
	$ResultArray=array();

	$ResultArray['message']='Guest Update Successfully';
	$ResultArray['id_follio']=$_REQUEST['id_folio'];
	echo  json_encode($ResultArray);
	
}else{
	$ResultArray['message']='Please select Guest';
	$ResultArray['id_follio']=$_REQUEST['id_folio'];
	echo  json_encode($ResultArray);
	}
	
	die;
	
	
	
	
	
 ?>



