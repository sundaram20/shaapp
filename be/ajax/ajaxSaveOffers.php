<?php
include_once("../../config/auto_loader.php");

$dateArr = explode(' to ',$_REQUEST['effective_date']);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$ids_link = $_REQUEST['id_link'];

$i=0;

if($_REQUEST['operation']=='add'){

	if(!isset($_REQUEST['id_link'])){
		echo "Room Plan Links Are Not Availble";
		exit;
	}

	$chk=selectColumn(TBL_OFFER,'count(id)','WHERE id_hotel='.$_REQUEST['id_hotel'].' AND id_offer_master='.$_REQUEST['offer_master'].' AND id_shop='.$_SESSION['shop'].'  ');
	
	if($chk>0){
		echo "This Offer Type already added for the hotel.Please use edit option to modify ";
		exit;
	}
	$masterSql="SELECT * FROM ".TBL_OFFER_MASTER." WHERE id='".$_REQUEST['offer_master']."' ";
	$resMaster = mysqli_query($connNew,$masterSql);
	$offer_master=mysqli_fetch_object($resMaster);
	
	if($offer_master->id==""){
		echo "This Offer Not exits";
		exit;
	}

	$insertOffer = "INSERT INTO ".TBL_OFFER." 
					SET id_shop='".$_SESSION['shop']."',
					id_offer_master='".$_REQUEST['offer_master']."',
					id_hotel='".$_REQUEST['id_hotel']."',
					";

	$insertOffer .="`date_created`='".date('Y-m-d H:i:s')."',
					`last_modified`='".date('Y-m-d H:i:s')."',
					`id_mst_user_created_by`='".$_SESSION['userId']."', 
					`id_mst_user_modified_by`='".$_SESSION['userId']."',
					 `status`='1' ";
					 
	if(mysqli_query($connNew,$insertOffer)){
		$id_offer = mysqli_insert_id($connNew);
		
		while($i < count($ids_link)){

			$from = date('Y-m-d',strtotime($offer_master->valid_from));
			$to = date('Y-m-d',strtotime($offer_master->valid_till));
			while(strtotime($from)<=strtotime($to)){

				$discount_amount=$_REQUEST['dis_'.$ids_link[$i]];
				$dis_type=$_REQUEST['dis_type_'.$ids_link[$i]];
				
				$dateGrid=$from;

				 $insertOfferDetails = "INSERT INTO ".TBL_OFFER_DETAILS." 
									SET id_shop='".$_SESSION['shop']."',
									id_offer_master='".$_REQUEST['offer_master']."',
									id_offer='".$id_offer."',
									id_hotel='".$_REQUEST['id_hotel']."',
									effective_date='".$from."',
									id_room_plan_link='".$ids_link[$i]."',
									discount_amount='".$discount_amount."',
									discount_type='".$dis_type."',
																		
									";
				$insertOfferDetails .="`date_created`='".date('Y-m-d H:i:s')."',
									`last_modified`='".date('Y-m-d H:i:s')."',
									`id_mst_user_created_by`='".$_SESSION['userId']."', 
									`id_mst_user_modified_by`='".$_SESSION['userId']."',
									 `status`='1' ";			 
					
								
				mysqli_query($connNew,$insertOfferDetails);			 

				$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
			}
			$i++;
		}
		echo "Data Updated Successfully";
	}
	else{
		echo "Error While updating";
	}			 			
}
else if($_REQUEST['operation']=='edit'){

	//debugData($_REQUEST);

	$ids_link = array();
	$ids_link = explode(',',$_REQUEST['id_link']);
	
	

	if($_REQUEST['id_link']==""){
		echo "Link Not Selected";
		exit;
	}

	$id_offer = $_REQUEST['eId'];

	$mastersql="SELECT id AS id_offer_master,valid_from,valid_till FROM ".TBL_OFFER_MASTER." WHERE id=".selectColumn(TBL_OFFER,'id_offer_master','where id="'.$id_offer.'"')." ";
	
	$resMaster=mysqli_query($connNew,$mastersql);
    $rowMaster=mysqli_fetch_object($resMaster);
	
	if(mysqli_num_rows($resMaster)>0){
		
		

      	
		
		while($i < count($ids_link)){

			$from = date('Y-m-d',strtotime($rowMaster->valid_from));
			$to = date('Y-m-d',strtotime($rowMaster->valid_till));
			while(strtotime($from)<=strtotime($to)){

				$discount_amount=$_REQUEST['dis_'.$ids_link[$i].'_'.$from];
				$statusValue=$_REQUEST['status_'.$ids_link[$i].'_'.$from];
				$dis_type=$_REQUEST['offerType_'.$ids_link[$i].'_'.$from];
				
				$dateGrid=$from;

				$chk=selectColumn(TBL_OFFER_DETAILS,'count(id)','WHERE id_hotel='.$_REQUEST['id_hotel'].' AND  id_shop='.$_SESSION['shop'].' AND id_offer="'.$_REQUEST['eId'].'" AND effective_date="'.$from.'" AND id_room_plan_link="'.$ids_link[$i].'" ');

				if($chk>0){

					$updateOfferDetails = "UPDATE ".TBL_OFFER_DETAILS." 
									SET 
									discount_amount='".$discount_amount."',
									discount_type='".$dis_type."',								
									";
					$updateOfferDetails .="
									`last_modified`='".date('Y-m-d H:i:s')."',
									`id_mst_user_modified_by`='".$_SESSION['userId']."',
									 `status`='".$statusValue."' ";	
					$updateOfferDetails .="WHERE effective_date='".$from."'
										   AND  id_offer='".$id_offer."'
										   AND id_room_plan_link='".$ids_link[$i]."' ";				 		 
									
					mysqli_query($connNew,$updateOfferDetails);			 

					$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
				}
				else{
					$insertOfferDetails = "INSERT INTO ".TBL_OFFER_DETAILS." 
									SET id_shop='".$_SESSION['shop']."',
									id_offer_master='".$rowMaster->id_offer_master."',
									id_offer='".$id_offer."',
									id_hotel='".$_REQUEST['id_hotel']."',
									effective_date='".$from."',
									id_room_plan_link='".$ids_link[$i]."',
									discount_amount='".$discount_amount."',
									discount_type='".$dis_type."',
																		
									";
					$insertOfferDetails .="`date_created`='".date('Y-m-d H:i:s')."',
									`last_modified`='".date('Y-m-d H:i:s')."',
									`id_mst_user_created_by`='".$_SESSION['userId']."', 
									`id_mst_user_modified_by`='".$_SESSION['userId']."',
									 `status`='".$statusValue."' ";		 
						
									
					mysqli_query($connNew,$insertOfferDetails);			 

					$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
				}
			}
			$i++;
		}
		echo "Data Updated Successfully";
	}
	else{
		echo "Error While updating";
	}
}

?>