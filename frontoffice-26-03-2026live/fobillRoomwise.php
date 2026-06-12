<!DOCTYPE html>
<html lang="en">
<?php include_once("../config/auto_loader.php");?>
<?php 

$id_fo_bill	     =  addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
$id_folio	     =  addslashes(encryptor(decrypt,$_REQUEST['id_folio']));

$id_fo_folio_to	 =  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
$id_fo_folio		=  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
$id_parent_folio		=  selectColumn('fo_folio','id_parent_folio'," WHERE `id` = '".$id_fo_folio."'");
$id_parent_fo_bill		=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$id_parent_folio."'");
$fo_bill_query = mysqli_query($connNew, "select * from ".TBL_DOC_TYPE_CONFIG." where doc_type = '803' order by id desc");
$fo_bill_result = mysqli_fetch_object($fo_bill_query);
$show_pos_bill_item = $fo_bill_result->show_pos_bill_item;
$fo_bill_notes = $fo_bill_result->fo_bill_notes ?? '';
$show_food_description = $fo_bill_result->show_food_description ?? '';
$rate_plan_name = "";
$id_mst_room_no_allocation  = addslashes(encryptor(decrypt,$_REQUEST['id_mst_room_no_allocation']));
$folioArray=array();
$paxArray=array();
$roomNumberArray=array();
$id_fo_reservations = ""; 
		$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_folio_to='".addslashes($id_folio)."'  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$roomNumberArray=array();
				while($rowOrderDetail	= mysqli_fetch_object($sqlOrderDetail)){
					
					$id_fo_reservations	=$rowOrderDetail->id_fo_reservations;
					$pax				   =$rowOrderDetail->adults_per_room;
					$paxArray[$rowOrderDetail->id_mst_room_no_allocation]=$rowOrderDetail->adults_per_room;
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					$checkout	= selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_fo_reservations.'"');
					$checkin	= selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_fo_reservations.'"');
					$rate_plan_name = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowOrderDetail->id_fo_rate_plan.'"');
					
					
					$checkoutDated	=	selectColumn(FO_RESERVATIONS_DETAILS,'dated'," WHERE `id_fo_reservations` = '".$id_fo_reservations."' and id_mst_room_types  = '".$rowOrderDetail->id_mst_room_types."' 
					and  id_fo_folio_to='".addslashes($id_fo_folio_to)."' and  order_by_room='".$rowOrderDetail->order_by_room."' order by dated DESC");
					
									
					$mdoc_no	= selectColumn(FO_BILL,'mdoc_no'," WHERE `id` = '".$id_fo_bill."'");
					$rate_plan	= selectColumn(TBL_RATE_PLAN,'remarks'," WHERE `id` = '".$rowOrderDetail->id_fo_rate_plan."'");
					$RoomNoAndRoomName = '';

if (!empty($RoomName) && !empty($roomNo)) {
    $RoomNoAndRoomName = $RoomName . ' / ' . $roomNo;
} elseif (!empty($RoomName)) {
    $RoomNoAndRoomName = $RoomName;
} elseif (!empty($roomNo)) {
    $RoomNoAndRoomName = $roomNo;
}
//$RoomNoAndRoomName=$RoomName.' / '.$roomNo;	
					
					
					
					
					$tax	=	$rowOrderDetail->tax_per_day_per_room;
					$sgst	=	$tax/2;
					$cgst	=	$tax/2;
					$Days	=1;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['Type']='Reservation';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['dated']= date('d-m-Y',strtotime($checkin));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['checkoutdated']=  date('d-m-Y',strtotime($checkoutDated));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['source']= 'Room Tariff@'.$rowOrderDetail->tariff_price_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['sac_no']= '996311';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['rate_plan']= $rate_plan;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['tariff'] +=$rowOrderDetail->tariff_price_per_day_per_room;
					//$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['tax'] +=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['Days']+=$Days;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['sgst']+=$sgst;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['cgst']+=$cgst;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['Total'] +=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['InvoiceNo']=$mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id_mst_room_types]['adults_per_room']=$rowOrderDetail->adults_per_room;
					$CurrentTotal	+=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
					$roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				}
				
				
		} else {
			if($id_parent_folio=='0'){
				  $sqlFoBill23	=	"SELECT * FROM ".FO_BILL." where id='".addslashes($id_fo_bill)."'";
$resFoBill23 	= 	mysqli_query($connNew,$sqlFoBill23);

$rowFoBill23 = mysqli_fetch_object($resFoBill23);

//print_r($roomNumberArray);
//$roomNumberArray[$rowFoBill23->id_owner_room ]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowFoBill23->id_owner_room ."'");
//$id_parent_folio=$rowFoBill23->id_owner_room;
				//$id_parent_folio=$rowFoBill23->id_owner_room;
				//$id_parent_folio22=$rowFoBill23->id_fo_folio_to;
			//$id_mst_guest
			$id_mst_guest	=selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$rowFoBill23->id_fo_folio_to."'");//=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest','WHERE id_mst_room_no_allocation="'.$rowFoBill23->id_owner_room.'" and id_fo_reservations="'.$rowFoBill23->id_reservations.'"');
	$set='1';
			
		  $sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($rowFoBill23->id_reservations)."' and id_mst_room_no_allocation='".addslashes($rowFoBill23->id_owner_room)."'");
        if(mysqli_num_rows($sqlOrderDetail) >0 ){
          $roomNumberArray=array();
            while($rowOrderDetail	= mysqli_fetch_object($sqlOrderDetail)){
              $roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
              $id_fo_reservations	=$rowOrderDetail->id_fo_reservations;
              $pax = $rowOrderDetail->adults_per_room;
              $rate_plan_name = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowOrderDetail->id_fo_rate_plan.'"');
				$paxArray[$rowOrderDetail->id_mst_room_no_allocation]=$rowOrderDetail->adults_per_room;
            }
        }		
				
				
				
			}else{
			
			
       /* $sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_folio_to='".addslashes($id_parent_folio)."'");
        if(mysqli_num_rows($sqlOrderDetail) >0 ){
          $roomNumberArray=array();
            while($rowOrderDetail	= mysqli_fetch_object($sqlOrderDetail)){
              $roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
              $id_fo_reservations	=$rowOrderDetail->id_fo_reservations;
              $pax = $rowOrderDetail->adults_per_room;
              $rate_plan_name = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowOrderDetail->id_fo_rate_plan.'"');
            }
        }*/
				
			$sqlFoBill23	=	"SELECT * FROM ".FO_BILL." where id='".addslashes($id_fo_bill)."'";
$resFoBill23 	= 	mysqli_query($connNew,$sqlFoBill23);

$rowFoBill23 = mysqli_fetch_object($resFoBill23);


			$id_mst_guest	=selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$rowFoBill23->id_fo_folio_to."'");
	$set='1';
			
		  $sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($rowFoBill23->id_reservations)."' and id_mst_room_no_allocation='".addslashes($rowFoBill23->id_owner_room)."'");
        if(mysqli_num_rows($sqlOrderDetail) >0 ){
          $roomNumberArray=array();
            while($rowOrderDetail	= mysqli_fetch_object($sqlOrderDetail)){
              $roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
              $id_fo_reservations	=$rowOrderDetail->id_fo_reservations;
              $pax = $rowOrderDetail->adults_per_room;
              $rate_plan_name = selectColumn(TBL_RATE_PLAN,'name','WHERE id="'.$rowOrderDetail->id_fo_rate_plan.'"');
				$paxArray[$rowOrderDetail->id_mst_room_no_allocation]=$rowOrderDetail->adults_per_room;
            }
        }	
				
				
			}
    }
		//pos_purch_details
		
		$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($id_folio)."' and cancelled='0'");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					$id_mst_room_no_allocation=selectColumn(TBL_ATTRIBUTES,'id_mst_room_no'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id= '".$rowOrderDetail->id_attribute_table."'");	
					
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
					$id_mst_room_types= selectColumn(TBL_ROOMNO,'id_mst_room_types'," WHERE `id` = '".$id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_mst_room_types."'");
					
					
					
									
					
					$outletName =selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowOrderDetail->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
					
					
					$sac_no=  selectColumn(TBL_OUTLETS,'sac_no','WHERE id="'.$rowOrderDetail->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

									$RoomNoAndRoomName = '';

if (!empty($RoomName) && !empty($roomNo)) {
    $RoomNoAndRoomName = $RoomName . ' / ' . $roomNo;
} elseif (!empty($RoomName)) {
    $RoomNoAndRoomName = $RoomName;
} elseif (!empty($roomNo)) {
    $RoomNoAndRoomName = $roomNo;
} //$RoomNoAndRoomName;
					
					//$RoomNoAndRoomName=$RoomName.' / '.$roomNo;	
					$tax	=	$rowOrderDetail->tax_per_day_per_room;
					$sgst	=	$tax/2;
					$cgst	=	$tax/2;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']=$RoomName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->doc_date));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $outletName.' - '.$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['sac_no']=  $sac_no != '' ? $sac_no : '-';
          //$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->grant_total_amount-($rowOrderDetail->sgst_total_items+$rowOrderDetail->cgst_total_items+$rowOrderDetail->vat_total_items+$rowOrderDetail->surcharge_total_items);
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->sub_total_items-$rowOrderDetail->total_discount_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['sgst']=$rowOrderDetail->sgst_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['cgst']=$rowOrderDetail->cgst_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['vat']=$rowOrderDetail->vat_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['surcharge']=$rowOrderDetail->surcharge_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->net_amount_items;//+$rowOrderDetail->tax_per_day_per_room;
					
					$CurrentTotal	+=$rowOrderDetail->net_amount_items;
						$sqlOrderDetailitem = mysqli_query($connNew,"Select  * from `pos_purch_details` where id_pos_purch='".addslashes($rowOrderDetail->id)."' ");
		if(mysqli_num_rows($sqlOrderDetailitem) >0 ){
			
				while($rowOrderDetailitemList= mysqli_fetch_object($sqlOrderDetailitem)){
						 $amountrowOrderDetailitemList = ($rowOrderDetailitemList->item_sgst_amount + $rowOrderDetailitemList->item_cgst_amount + $rowOrderDetailitemList->item_vat_amount);
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['purch_details'][$rowOrderDetailitemList->id]['name']=ucwords(strtolower($rowOrderDetailitemList->item_description)).' Qty '.round($rowOrderDetailitemList->qty).' @ Rs '.($rowOrderDetailitemList->item_amount+$amountrowOrderDetailitemList1);
				}
		}
			
				}
				
				
		}


$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($id_folio)."'");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_CHARGES,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
					
					$id_mst_room_types	=  selectColumn('fo_reservations_details','id_mst_room_types'," WHERE `id_fo_reservations` = '".$rowOrderDetail->id_fo_reservations."' and id_mst_room_no_allocation = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$roomNo= selectColumn(TBL_CHARGES,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_mst_room_types."'");
					
					
									
					$chargesname= selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					$sac_no= selectColumn(TBL_CHARGES,'sac_no'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					$outletName ='Post Charges';
									$RoomNoAndRoomName = '';

if (!empty($RoomName) && !empty($roomNo)) {
    $RoomNoAndRoomName = $RoomName . ' / ' . $roomNo;
} elseif (!empty($RoomName)) {
    $RoomNoAndRoomName = $RoomName;
} elseif (!empty($roomNo)) {
    $RoomNoAndRoomName = $roomNo;
}
					//$RoomNoAndRoomName=$RoomName.' / '.$roomNo;	
					$tax	=	$rowOrderDetail->tax_value*$rowOrderDetail->qty*$rowOrderDetail->days;
					$sgst	=	$tax/2;
					$cgst	=	$tax/2;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']='';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $chargesname;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['sac_no']= $sac_no != '' ? $sac_no : '-';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->rate*$rowOrderDetail->qty*$rowOrderDetail->days;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['sgst']=$sgst;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['cgst']=$cgst;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']='-';$chargesname;//$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_table']= 'pos_purch';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_id']= $rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->total;
					
					$CurrentTotal	+=$rowOrderDetail->total;
				}
				
				
		}
//FO BILL STATUS=========================================
 $sqlFoBill	=	"SELECT * FROM ".FO_BILL." where id='".addslashes($id_fo_bill)."'";
$resFoBill 	= 	mysqli_query($connNew,$sqlFoBill);

$rowFoBill = mysqli_fetch_object($resFoBill);



 				if($rowFoBill->folio_status == '0'){
                      $rowFoBillSelect1 ='selected="selected"';
					  $rowFoBillSelect2='';
					  $buttonHide='style="display:none;"';
                        }
						if($rowFoBill->folio_status == '1'){
                          $rowFoBillSelect2 =  'selected="selected"';
						  $rowFoBillSelect1='';
						
                        }  
         $mdoc_no=  $rowFoBill->mdoc_no==''?'Performa':$rowFoBill->mdoc_no; 
		 $doc_date=  $rowFoBill->mdoc_no==''?'-':date('d-m-Y',strtotime($rowFoBill->doc_date)); 
		  $id_resevation =  selectColumn(FO_BILL,'id_reservations'," WHERE `id` = '".$id_fo_bill."'");
	if($id_fo_reservations==''){
		$id_fo_reservations=$id_resevation;
	}
$checkout	= selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_fo_reservations.'"');
$checkin	= selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_fo_reservations.'"');
$res_internal_remarks	=	selectColumn(FO_RESERVATIONS,'res_internal_remarks','WHERE id="'.$id_fo_reservations.'"');
$checkin_time_query = mysqli_query($connNew,"SELECT * FROM `fo_reservations_details` WHERE id_fo_reservations='".$id_fo_reservations."' order by id desc limit 1");
$checkin_time_result =  mysqli_fetch_object($checkin_time_query);
$checkin_time = $checkin_time_result->checkin_time ?? '';
$checkout_time = $checkin_time_result->checkout_time ?? '';

 //$checkout_date=  $rowFoBill->status=='2'?date('d-m-Y',strtotime($checkout)):'-';
		 $checkout_date=  $rowFoBill->status=='2'?date('d-m-Y',strtotime($rowFoBill->checkout_date)):'-'; 
		  $checkin_date= date('d-m-Y',strtotime($checkin));            
//FO BILL STATUS==========================================
$id_fo_folio_to	=  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
$receipt_amount	=	selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$id_fo_folio_to.'"');
$id_bill_to_company	=  selectColumn('fo_folio','id_bill_to_company'," WHERE `id` = '".$id_folio."'");
$is_bill_to_company = $id_bill_to_company > 0;

//$receipt_amount	=	selectColumn('fo_receipt','sum(amount)','WHERE id_fo_bill="'.$id_fo_bill.'"');

$BalanceAmount = $CurrentTotal-$receipt_amount;

	
 // $id_mst_room_no_allocation  = selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE id_fo_reservations = '".$id_resevation."'");
//$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
$roomNumber= implode(',',$roomNumberArray);
 if($id_fo_folio_to>0 && $set==''){ 
$id_mst_guest	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest','WHERE id_fo_folio_to="'.$id_fo_folio_to.'" ');
 }elseif($id_parent_folio>0 && $set==''){//if ($id_mst_guest == '') {
	
  $id_mst_guest	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest','WHERE id_fo_folio_to="'.$id_parent_folio.'"');
	
	
	
	
	
}

//Shared Guest============================
  $guests = array();
  $id_shared_guest=selectColumn(FO_RESERVATIONS_DETAILS,'id_shared_guest','WHERE id_fo_folio_to="'.$id_parent_folio.'"');
  if ($id_shared_guest != '') {
		$id_shared_guests = explode(',', $id_shared_guest);
		foreach ($id_shared_guests as $id_guest) {
			$SharedGuestName	=	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$id_guest."'");
			$sharedGuestLastName	=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$id_guest."'");
			
			$shared_guest_id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_guest."'");				
			$sharedGuestTitle = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$shared_guest_id_mst_attributes_title."'");
			$guests[$id_guest] = $SharedGuestName!=''?$sharedGuestTitle.' '.ucfirst(strtolower($SharedGuestName)).' '.ucfirst(strtolower($sharedGuestLastName)):'';
		}
	}
  //Shared Guest============================
	
	



	$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id` = '".addslashes($id_mst_guest)."' ";


		
		$query=mysqli_query($connNew, $SQL);		
	    $row=mysqli_fetch_assoc($query);
		
$GuestTitle	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE `id_shop`="'.addslashes($_SESSION['shop']).'" and id="'.$row['id_mst_attributes_title'].'"');
		
 $GuestName = $GuestTitle.' '.$row['first_name'].' '. $row['last_name'];
$GuestAddress = $row['address'];
$GuestCity = $row['city'];

//$row['email'].' , ' . $row['phone'] . '  ' . $row['city'];


//$GuestNationality =$row['city'];
$GuestNationality	=	selectColumn(TBL_COUNTRY_LANG,'nationality','WHERE id_country="'.$row['id_mst_country_lang_nationality'].'"');


$id_mst_company	=	selectColumn(FO_RESERVATIONS,'id_mst_company','WHERE id="'.$id_fo_reservations.'"');
$reservation_mdoc_no	=	selectColumn(FO_RESERVATIONS,'booking_no','WHERE id="'.$id_fo_reservations.'"');
$selectnew = "select *  from ".TBL_COMPANY." where status='1'  and name !='' and `id` = '".$id_mst_company."'";


$id_mst_company_contacts	=	selectColumn(FO_RESERVATIONS,'id_mst_company_contacts','WHERE id="'.$id_resevation.'"');
$id_mst_attributes_titlecontacts	=	selectColumn('mst_company_contacts','id_mst_attributes_title'," WHERE `id` = '".$id_mst_company_contacts."'");
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_titlecontacts."'"); 				
$Firstname	=	selectColumn('mst_company_contacts','first_name'," WHERE `id` = '".$id_mst_company_contacts."'");
$Lastname	=	selectColumn('mst_company_contacts','last_name'," WHERE `id` = '".$id_mst_company_contacts."'");
$company_contacts=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));


$resnew = mysqli_query($connNew,$selectnew);		
		$rownew = mysqli_fetch_object($resnew);	
			
	
$id_bill_to_company	=  selectColumn('fo_folio','id_bill_to_company'," WHERE `id` = '".$id_fo_folio_to."'");
	
	if($id_bill_to_company=='0'){
		$CompanyName= '- '.ucwords($rownew->name);
		$CompanyGST = '- '.$rownew->fax;
		$CompanyAddress = '- '.$rownew->address;
    $CompanyCity = '-';$rownew->city;
    $CompanyState = '-';$rownew->other_state;
    $pincode = '-';$rownew->post_code;
	}else{
		$selectnew = "select *  from ".TBL_COMPANY." where status='1'  and name !='' and `id` = '".$id_bill_to_company."'";
		$resnew = mysqli_query($connNew,$selectnew);		
		$rownew = mysqli_fetch_object($resnew);
		$CompanyName= ucwords($rownew->name);
		$CompanyGST = $rownew->fax;
    $CompanyAddress = $rownew->address != "" ? $rownew->address.', ' : "";
    $CompanyCity = $rownew->city != "" ? $rownew->city.', ' : "";
    $CompanyState = $rownew->other_state != "" ? $rownew->other_state.', ' : "";
    $pincode = $rownew->post_code;
		}
	
	
	$SQL_Hotel = "select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";	
	$query_Hotel=mysqli_query($connNew, $SQL_Hotel);		
	$row_Hotel=mysqli_fetch_object($query_Hotel);
		
		$id_mst_outlet =  selectColumn('pos_purch','id_mst_outlet'," WHERE `id_fo_folio_to` = '".$id_fo_folio_to."'");
		
		
		$HotelName	   =$row_Hotel->name;
		$HotelState	  =selectColumn(TBL_STATE,'name','WHERE id_state="'.$row_Hotel->id_mst_state.'"');
		$HotelCity	   =$row_Hotel->city;
		$HotelPincode	=$row_Hotel->pincode;
		$HotelGST		=$row_Hotel->gstin;
		$HotelAddress	=$row_Hotel->address;
		$Hotelsecondary_landline	=$row_Hotel->primary_mobile;	
		$Hotelsecondary_mobile	=$row_Hotel->secondary_mobile!==''?'PH +91 '.$row_Hotel->secondary_mobile:'';	
		$Hotelsecondary_landline = $Hotelsecondary_landline.' '.$Hotelsecondary_mobile;
$Hotelpan	=	$row_Hotel->pan;
$HotelEmail  =	$row_Hotel->email;
$HotelCIN	=	$row_Hotel->cin_no;
$bank_name = $row_Hotel->bank_name;
$bank_account_legal_name = $row_Hotel->bank_account_legal_name;
$bank_account_no = $row_Hotel->bank_account_no;
$bank_account_type = $row_Hotel->bank_account_type;
$bank_ifsc_code = $row_Hotel->bank_ifsc_code;
$bank_swift_code = $row_Hotel->bank_swift_code;
$bank_branch = $row_Hotel->bank_branch;

$reservation_based_on_percentage = [];
$reservation_percentage = [];
$reservation_query = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_folio_to = '".$id_folio."'");
while ($reservation = mysqli_fetch_object($reservation_query)) {
    $percentage = ($reservation->tax_per_day_per_room ?? 0) / ($reservation->tariff_price_per_day_per_room ?? 0) * 100;
    $reservation_percentage[] = $percentage;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['taxable_amount'] = $reservation->tariff_price_per_day_per_room ?? 0;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['total_tax_percentage'] = $percentage;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['total_tax_amount'] = $reservation->tax_per_day_per_room ?? 0;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['sgst_percentage'] = $percentage > 0 ? ($percentage / 2) : 0;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['sgst_amount'] = ($reservation->tax_per_day_per_room ?? 0) / 2;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['cgst_percentage'] = $percentage > 0 ? ($percentage / 2) : 0;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['cgst_amount'] = ($reservation->tax_per_day_per_room ?? 0) / 2;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['vat_percentage'] = 0;
    $reservation_based_on_percentage['Room'][$percentage][$reservation->id]['vat_amount'] = 0;
}

$reservation_addon_query = mysqli_query($connNew, "select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_folio."'");
while ($reservation = mysqli_fetch_object($reservation_addon_query)) {
    $amount = $reservation->rate * $reservation->qty * $reservation->days;
    $tax = $reservation->tax_value * $reservation->qty * $reservation->days;
    $percentage = ($tax / $amount) * 100;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['taxable_amount'] = $amount;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['total_tax_percentage'] = $percentage;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['total_tax_amount'] = $tax ?? 0;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['sgst_percentage'] = $percentage / 2;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['sgst_amount'] = ($tax) / 2;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['cgst_percentage'] = $percentage / 2;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['cgst_amount'] = ($tax) / 2;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['vat_percentage'] = 0;
    $reservation_based_on_percentage['Charges'][$percentage][$reservation->id]['vat_amount'] = 0;
}




$reservation_query = mysqli_query($connNew, "SELECT
  t.id_mst_charges_sales_local,t.id_mst_outlet,
  SUM(t.item_cgst_amount)        AS item_cgst_amount,
  SUM(t.item_sgst_amount)        AS item_sgst_amount,
  SUM(t.item_vat_amount)         AS item_vat_amount,
  SUM(t.item_surcharge_amount)   AS item_surcharge_amount,
    sum(t.detail_item_discount_amount) as detail_item_discount_amount,

  SUM(t.item_amount)             AS item_amount,
  MAX(t.item_sgst_percent)       AS item_sgst_percent,
  MAX(t.item_cgst_percent)       AS item_cgst_percent,
  MAX(t.item_vat_percent)        AS item_vat_percent,
  MAX(t.item_surcharge_percent)  AS item_surcharge_percent,
  SUM(t.purchase_total_discount) AS total_discount_items,
  (SELECT SUM(grant_total_amount)
     FROM pos_purch
    WHERE id_fo_folio_to = '".$id_folio."'
      AND cancelled = '0')        AS grant_total_amount
FROM (
  -- aggregate per purchase + charge id so parent-level fields are not duplicated
  SELECT
    p.id                                     AS id_pos_purch,
    d.id_mst_charges_sales_local,
    SUM(d.item_cgst_amount)                  AS item_cgst_amount,
    SUM(d.item_sgst_amount)                  AS item_sgst_amount,
    SUM(d.item_vat_amount)                   AS item_vat_amount,
    SUM(d.item_surcharge_amount)             AS item_surcharge_amount,
		sum(d.item_discount_amount) as detail_item_discount_amount,
    SUM(d.rate_per_main_unit * d.qty)        AS item_amount,
    MAX(d.item_sgst_percent)                 AS item_sgst_percent,
    MAX(d.item_cgst_percent)                 AS item_cgst_percent,
    MAX(d.item_vat_percent)                  AS item_vat_percent,
    MAX(d.item_surcharge_percent)            AS item_surcharge_percent,
    p.total_discount_items                   AS purchase_total_discount,
	p.id_mst_outlet
  FROM pos_purch p
  JOIN pos_purch_details d ON d.id_pos_purch = p.id
  WHERE p.id_fo_folio_to = '".$id_folio."'
    AND p.cancelled = '0'
  GROUP BY p.id,p.id_mst_outlet, d.id_mst_charges_sales_local
) AS t
GROUP BY  t.id_mst_outlet,t.id_mst_charges_sales_local");

//"select * from pos_purch where id_fo_folio_to = '".$id_folio."' and cancelled != 1");
while ($reservation = mysqli_fetch_object($reservation_query)) {
//	echo "<pre>"; print_r($reservation);echo "</pre>";
	
   $amount = ($reservation->item_sgst_amount + $reservation->item_cgst_amount + $reservation->item_vat_amount + $reservation->item_surcharge_amount) ?? 0;
    $sgst_percentage = ($reservation->item_sgst_percent ?? 0);
    $cgst_percentage = ($reservation->item_cgst_percent ?? 0);
    $vat_percentage  = ($reservation->item_vat_percent ?? 0);
	 $surcharge_percentage  = ($reservation->item_surcharge_percent ?? 0);
    $percentage = $cgst_percentage + $sgst_percentage + $vat_percentage + $surcharge_percentage ;
	//if($percentage>0){
$item_amount = $reservation->item_amount - $reservation->detail_item_discount_amount;
//$item_amount = $reservation->item_amount- $reservation->total_discount_items;
$outletName =selectColumn(TBL_OUTLETS,'name','WHERE id="'.$reservation->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
//echo '=============='.$reservation->item_amount;
    $reservation_percentage[] = $percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['taxable_amount'] += $item_amount;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['total_tax_percentage'] += $percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['total_tax_amount'] += $amount;
	$reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['TotalAmountWithoutTax'] +=$item_amount;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['sgst_percentage'] = $sgst_percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['sgst_amount'] += $reservation->item_sgst_amount;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['cgst_percentage'] = $cgst_percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['cgst_amount'] += $reservation->item_cgst_amount;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['vat_percentage'] = $vat_percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['vat_amount'] += $reservation->item_vat_amount;
	$reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['surcharge_percentage'] = $surcharge_percentage;
    $reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['surcharge_amount'] += $reservation->item_surcharge_amount;
	$reservation_based_on_percentage[$outletName][$percentage][$reservation->id]['table'] = 'pos_purch';
	//}
	
}



/*$reservation_query = mysqli_query($connNew, "select * from pos_purch where id_fo_folio_to = '".$id_folio."' and cancelled != 1");
while ($reservation = mysqli_fetch_object($reservation_query)) {
    $amount = $reservation->sub_total_items ?? 0;
    $sgst_percentage = ($reservation->sgst_total_items ?? 0) / ($reservation->sub_total_items ?? 0) * 100;
    $cgst_percentage = ($reservation->cgst_total_items ?? 0) / ($reservation->sub_total_items ?? 0) * 100;
    $vat_percentage = ($reservation->vat_total_items ?? 0) / ($reservation->sub_total_items ?? 0) * 100;
    $percentage = $cgst_percentage + $sgst_percentage + $vat_percentage;
    $reservation_percentage[] = $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['taxable_amount'] = $amount ?? 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_percentage'] = $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_amount'] = $reservation->sgst_total_items + $reservation->cgst_total_items + $reservation->vat_total_items;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_percentage'] = $sgst_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_amount'] = $reservation->sgst_total_items;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_percentage'] = $cgst_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_amount'] = $reservation->cgst_total_items;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_percentage'] = $vat_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_amount'] = $reservation->vat_total_items;
}*/
// echo "<pre>";
krsort($reservation_based_on_percentage);
//echo "<pre>"; print_r($reservation_based_on_percentage);echo "</pre>";
// exit;

//$HotelCIN	=	selectColumn(TBL_OUTLETS,'cin_no','WHERE id="'.$id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');  //$row_Hotel->cin;
		?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo strtoupper($HotelName); ?></title>
    <link rel="stylesheet" href="invoice_style.css">
</head>

<body>

    <div class="print-button-container">
        <button class="print-button" onclick="printInvoice()">Print Invoice</button>
        <button class="print-button" onclick="window.close()">Close</button>
    </div>

    <div class="invoice-container">
        <table class="invoice-main-table">
            <thead>
                <tr>
                    <td colspan="6">
                        <header class="invoice-header">
                            <div class="header-content">
                                <div class="company-logo">
                                   <!-- <img src="https://app.roomstatushub.in/uploaded_files/shop/LOGO%20-software84625.JPG"
                                        alt="Deogarh Resorts Logo" style="width: 80px;">
                                        ?>
                                        
                                        -->
                                        <?php 
							  $image_path = $UPLOAD_FILES.'/shop/';
							  $image_display_path = $UPLOAD_FILES_PATH ."/shop/";
							  $image	= selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
							  if(@file_exists($image_path.$image) && $image!=''){ ?>	  
							  
                           
                              <img src="<?php echo $image_display_path.$image; ?>"  style="width: 80px;">
                            
							  
							  <?php }else{ ?>
							 
                              <img src=""  style="width: 80px;" alt="">
                            
							  <?php } ?>
                            <?php if($_SESSION['database']=='mmr_pms'){?>
                            
                            <?php } ?>
                            <?php if($_SESSION['database']=='jungle_home'){
							 $logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');?>
                          
                              <img src="<?php echo $SITE_URL.'/uploaded_files/outlets/'.$logoImage; ?>"  style="width: 80px;"
                                alt="">
                           

                            <?php } ?>
                                        
                                </div>
                                <div class="company-details">
                                    <div class="invoice-title">Tax Invoice</div>
                                    <h1><?php echo strtoupper($HotelName); ?></h1>
                                    <p> <?php echo $HotelAddress.', '; ?> <?php echo $HotelCity .' - '.$HotelPincode,','.$HotelState; ?></p>
                                     <?php if($HotelCIN!=''){?>
                                  <p>  CIN: <?php echo $HotelCIN; ?>
                                  <?php } ?></p>
                                  <p>GST NO: <?php echo $HotelGST; ?></p>
                                    <p>PAN: <?php echo $Hotelpan; ?></p>
                                    <p>PH: +91 <?php echo $Hotelsecondary_landline.' '; ?>

                                  <?php echo 'Email: '.$HotelEmail; ?></p>
                                </div>
                            </div>
                        </header>

                        <section class="booking-details">
                            <table>

                                <tr>
                                    <th  rowspan="2" ><b>Guest Name :</b> <span><?php echo $GuestName; ?> <?php  echo $GuestAddress.$GuestCity!=''?','.$GuestCity:'';
									$k=1;
						foreach ($guests as $id_guest=>$guest) {
							
							echo $guest.'<br/>' ;
						}

									
									?></th>
                                    <th><b>Booked By :</b> <span><?php echo $company_contacts;?></span></th>
                                    <th><b>Bill No :</b> <span><?php echo $mdoc_no;?></span></th>
                                    <th><b>Bill Date :</b> <span><?php echo $doc_date; ?></span></th>
                                </tr>




                                <tr>
                                    <td><b>Check-In :</b> <span><?php echo $checkin_date; ?>&nbsp;<?php echo $checkin_time != '' ? $checkin_time : ""; ?></span></td>
                                    <td><b>Check-Out :</b> <span> <?php  echo $checkout_date; ?>&nbsp;<?php echo $checkout_time != '' ? $checkout_time : ""; ?></span></td>
                                    <td colspan="2"><b>Pax :</b> <?php echo array_sum($paxArray)." / ".$rate_plan_name; ?></td>
                                </tr>
  <?php
							if ($is_bill_to_company > 0) {
						  ?>
                                <tr>
 
                                   
                                   
                                    <th colspan="4" style="line-height: 1.3!important;"><b>Company Name :</b>
                                        <span><?php echo $CompanyName;?></span> &nbsp; &nbsp;<b>Company GSTIN
                                            :</b> <span><?php echo $CompanyGST;?></span><br>
                                        <b>Address :</b> <span> <?php echo $CompanyAddress.$CompanyCity.$CompanyState,$pincode;?>
                                        </span>
                                    </th>


                                </tr>
 <?php
              }
                                ?>
                                <tr>

                                    <td colspan="4"><b>Room No. :</b> <span><?php echo $roomNumber;?></span> </td>

                                </tr>

                            </table>

                        </section>
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="dynamic-content-area">
                            <section class="description-section">
                                <div class="section-title">Description</div>
                                <table class="item-table">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>SAC</th>
                                            <th class="align-right">Charges</th>
                                            <th class="align-right">Tax</th>
                                            <th class="align-right">Credits</th>
                                            <th class="align-right">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
 <?php 
$count = 1;

foreach ($folioArray as $RoomName => $Array1) { 
    $ics = 1;
    $SubCurrentTotal = 0; // Initialize subtotal for this room
if($_REQUEST['hide_room_type']=='1'){
	$RoomName='Room - '.$count++;
	
	}?>
	
	 <tr class="date-group-header">
                                            <td colspan="6"><?php echo $RoomName; ?></td>
                                        </tr>
	<?php 
    foreach ($Array1 as $rowid => $Array2) { 
        $SubCurrentTotal += $Array2['Total']; // Accumulate total per room
		if($Array2['RoomType']==''){
			$RoomName='';
		}
?>
                                       
                                        <tr>
                                            <td>   

                <?php echo date('d-m-Y', strtotime($Array2['dated'])); ?>
                <?php 
                if (strtotime($Array2['dated']) != strtotime($Array2['checkoutdated'])) {
                    echo $Array2['checkoutdated'] != '' ? ' - ' . date('d-m-Y', strtotime($Array2['checkoutdated'])) : '';
                }
                ?>

				<?php echo $Array2['source']; ?>
                <?php //echo $Array2['RoomType']; ?>
                <?php if (!empty($Array2['rate_plan']) && $show_food_description): ?>
                    &nbsp;(<?php echo $Array2['rate_plan']; ?>)
                <?php endif; ?>

                <?php 
                if (!empty($Array2['Days']) && $Array2['Days'] > 1) {
                    echo ' X ' . $Array2['Days'] . ' Days ';
                }

                if (!empty($Array2['RoomType']) && $Array2['Type'] =='Reservation') {
                    $Ad = $Array2['adults_per_room'] == '1' ? ' Adult' : ' Adults';
                    echo '( ' . $Array2['adults_per_room'] . $Ad . ' )';
                }
                ?>
                
                 <?php
		if($show_pos_bill_item=='1'){
				foreach ($Array2['purch_details'] as $purch_detailsid => $purch_detailsList) { 
				echo '<p style="    line-height: 2px !important;font-size:10.5px;margin-left: 20px;">'.$purch_detailsList['name'].'</p>';
				?>
                <?php } }?>
                
                </td>
                                            <td><?php echo $Array2['sac_no']; ?></td>
                                            <td class="align-right"><?php echo number_format($Array2['tariff'], 2); ?></td>
                                            <td class="align-right"><?php echo number_format($Array2['cgst'] + $Array2['sgst'] + $Array2['vat'] + $Array2['surcharge'], 2); ?></td>
                                            <td class="align-right">-</td>
                                            <td class="align-right"><?php echo number_format($Array2['Total'], 2); ?></td>
                                        </tr>
                                        
                                       
<?php 
    } // end inner loop
?>
                                        
                                        <tr class="date-subtotal-row">
                                            <td colspan="5" class="total-label" style="text-align:right;">Room Sub Total :</td>
                                            <td class="align-right total-amount"> <?php echo number_format($SubCurrentTotal, 2); ?> </td>
                                        </tr>
<?php 
} // end outer loop 
?>
               <?php 
 $id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));

	$id_fo_folio_to	=  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");								
$sql_fo_receipt	=	"SELECT * FROM `fo_receipt` where  id_fo_folio='".$id_fo_folio_to."'";									
//$sql_fo_receipt	=	"SELECT * FROM `fo_receipt` where  id_fo_bill='".$id_fo_bill."'";
$res_fo_receipt 	= 	mysqli_query($connNew,$sql_fo_receipt);
if(mysqli_num_rows($res_fo_receipt)>0){

	?>
              <tr class="date-group-header">
                                            <td colspan="6">Receipt</td>
                                        </tr>     <?php 
	$i=1;
	while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
	
		//pos_purch_details
		
		
				
				?>
                <tr>
                 <td> 
                        <?php echo date('d-m-Y',strtotime($row_fo_receipt->doc_date)); ?>-
                     
                        
                       <?php echo $row_fo_receipt->payment_mode;?><?php
						  if($row_fo_receipt->payment_mode =='COMPANY'){
							$id_mst_receipt_company	=$row_fo_receipt->id_company;
							
							echo '  -  '.selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$id_mst_receipt_company."' and status='1' ");
							
							} 
							echo $row_fo_receipt->remark!=''?' - '.$row_fo_receipt->remark:'';	
							?></td>
                                            <td class="align-right">-</td>
                                            <td class="align-right">-</td>
                                            <td class="align-right">   -</td>
                                            <td class="align-right"><?php echo number_format($row_fo_receipt->amount, 2);?></td>
                                            <td class="align-right"> - </td>
                                        </tr>
                
                  <?php $i++;
				  
				  } ?>
                  <?php } ?>                               
                                    </tbody>
                                    <tfoot>

                                    </tfoot>
                                </table>
                            </section>

                            <div style="padding: 0px 4px!important; text-align: right;">
    <div class="subtotal-group">
        <span class="total-label">Subtotal:</span>
        <span class="total-amount"><?php echo number_format($CurrentTotal,2); ?></span>
    </div>
</div>

                              
                                <div class="grand-total-box" style="display : flex;">
                                <p style="margin : 0px 4px;">Round Off: <b><?php echo round(round($CurrentTotal,0) - $CurrentTotal,2); ?></b></p>
                                <p style="margin : 0px 4px;">Grand Total: <b><span id="grand-total-value2"><?php echo round($CurrentTotal,0); ?></span></b></p>
                                <p style="margin : 0px 4px;">Balance: <b><span id="balance-value2"><?php echo round(round($CurrentTotal,0)-round($receipt_amount,2),2); ?></span></b></p>
                                </div>

                                <div class="rupees-in-words">

                                <?php 

function convert_number_to_words($number) {
    $words = array(
        0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
        5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen', 
        14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen', 
        18 => 'Eighteen', 19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty', 
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty', 70 => 'Seventy', 
        80 => 'Eighty', 90 => 'Ninety', 100 => 'Hundred', 1000 => 'Thousand',
        100000 => 'Lakh', 10000000 => 'Crore'
    );

    if ($number == 0) {
        return 'Zero';
    }

    $number = (int)$number;
    $result = '';

    if ($number < 100) {
        if ($number <= 20) {
            $result = $words[$number];
        } else {
            $tens = (int)($number / 10) * 10;
            $units = $number % 10;
            $result = $words[$tens];
            if ($units) {
                $result .= '-' . $words[$units];
            }
        }
    } elseif ($number < 1000) {
        $hundreds = (int)($number / 100);
        $remainder = $number % 100;
        $result = $words[$hundreds] . ' Hundred';
        if ($remainder) {
            $result .= ' and ' . convert_number_to_words($remainder);
        }
    } elseif ($number < 100000) {
        $thousands = (int)($number / 1000);
        $remainder = $number % 1000;
        $result = convert_number_to_words($thousands) . ' Thousand';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    } elseif ($number < 10000000) {
        $lakhs = (int)($number / 100000);
        $remainder = $number % 100000;
        $result = convert_number_to_words($lakhs) . ' Lakh';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    } else {
        $crores = (int)($number / 10000000);
        $remainder = $number % 10000000;
        $result = convert_number_to_words($crores) . ' Crore';
        if ($remainder) {
            $result .= ' ' . convert_number_to_words($remainder);
        }
    }

    return $result;
}

function convert_amount_to_words($amount) {
    $amount = number_format($amount, 2, '.', '');
    list($integerPart, $decimalPart) = explode('.', $amount);

    $integerPartWords = convert_number_to_words($integerPart);
    $decimalPartWords = convert_number_to_words($decimalPart);

    $result = $integerPartWords . ' Only';
    if ((int)$decimalPart > 0) {
        $result .= ' and ' . $decimalPartWords . ' Paise';
    }

    return $result;
}

// Example usage:
$amount = round(($CurrentTotal),0);
$convert_amount_to_words= convert_amount_to_words($amount); // Outputs: One Million Two Hundred Thirty-Four Thousand Five Hundred Sixty-Seven Rupees and Eighty-Nine Paise
?> 

                                <p style="text-align : right;">Rupees(In Words): <span id="rupees-in-words-text"><?php echo $convert_amount_to_words; ?></span></p>
                                </div>
                        </div>
	 
                        <div class="bottom-sections">
                            <section class="summary-section">

                              

                             

                                

                                <div class="section-title">Tax Summary</div>
                                <table class="tax-table">
                                    <thead>
                                        <tr> 
                                        <th rowspan="2" style=" border-right: 1px solid #000;">Name</th>
                                            <th rowspan="2">Taxable Value</th>
                                            <th colspan="2">CGST</th>
                                            <th colspan="2">SGST</th>
                                            <th colspan="2">VAT</th>
                                            <th colspan="2">Surcharge</th>
                                            <th rowspan="2">Total Tax Amount</th>
                                        </tr>
                                        <tr>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                            <th>Rate</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                   <tbody>
                                    
               <?php
                                    $total_taxable_amount = 0;
                                    $total_cgst_amount = 0;
                                    $total_sgst_amount = 0;
                                    $total_vat_amount = 0;
                                    $total_tax_amount = 0;
									$total_surcharge_amount = 0;
                                    foreach ($reservation_based_on_percentage as $key1 => $reservation_based_on_percentage_step) {
                                    foreach ($reservation_based_on_percentage_step as $key => $reservations) {
                                      $taxable_amount = 0;
                                      $cgst_amount = 0;
                                      $sgst_amount = 0;
                                      $vat_amount = 0;
									   $surcharge_amount = 0;
                                      $tax_amount = 0;
                                      foreach ($reservations as $key => $reservation) {
                                        $taxable_amount += ($reservation['taxable_amount'] ?? 0);
                                        $cgst_amount += ($reservation['cgst_amount'] ?? 0);
                                        $sgst_amount += ($reservation['sgst_amount'] ?? 0);
                                        $vat_amount += ($reservation['vat_amount'] ?? 0);
										$surcharge_amount += ($reservation['surcharge_amount'] ?? 0);
                                        $tax_amount += $reservation['total_tax_amount'] ?? 0;
                                        $total_taxable_amount += ($reservation['taxable_amount'] ?? 0);
                                        $total_cgst_amount += ($reservation['cgst_amount'] ?? 0);
                                        $total_sgst_amount += ($reservation['sgst_amount'] ?? 0);
                                        $total_vat_amount += ($reservation['vat_amount'] ?? 0);
										$total_surcharge_amount += ($reservation['surcharge_amount'] ?? 0);
                                        $total_tax_amount += $reservation['total_tax_amount'] ?? 0;
                                      }
                                      if ($taxable_amount > 0) {
                                ?>                      
                                    
                                    
                                    
                                        <tr> <td> <?php echo $key1; ?></td>
                                            <td> <?php echo number_format($taxable_amount,2); ?></td>
                                            <td><?php echo round(($reservation['cgst_percentage']),2)." %"; ?></td>
                                            <td> <?php echo number_format($cgst_amount,2); ?></td>
                                            <td>  <?php echo round(($reservation['sgst_percentage']),2)." %"; ?></td>
                                            <td> <?php echo number_format($sgst_amount,2); ?></td>
                                            <td> <?php echo round(($reservation['vat_percentage']),2)." %"; ?></td>
                                            <td><?php echo number_format($vat_amount,2); ?></td>
                                            <td><?php echo round(($reservation['surcharge_percentage']),2)." %"; ?></td>
                                            <td><?php echo number_format($surcharge_amount,2); ?></td>
                                            <td><?php echo number_format($tax_amount,2); ?></td>
                                        </tr>
                                          <?php
                                    }
                                  }
 }
                                 // if ($total_taxable_amount > 0) {
                                ?>
                                       
                                    </tbody>
                                    <tfoot>
                                      <?php
                                   
                                  if ($total_taxable_amount > 0) {
                                ?>
                                        <tr><td> </td>
                                            <td>Total: <?php echo number_format($total_taxable_amount,2); ?></td>
                                            <td colspan="2"><?php echo number_format($total_cgst_amount,2); ?></td>
                                            <td colspan="2"><?php echo number_format($total_sgst_amount,2); ?></td>
                                            <td colspan="2"><?php echo number_format($total_vat_amount,2); ?></td>
                                            <td colspan="2"><?php echo number_format($total_surcharge_amount,2); ?></td>
                                            <td><?php echo number_format($total_tax_amount,2); ?></td>
                                        </tr>
                                         <?php } ?>
                                         
                                         
                                              <?php
	$sqlOrderDetail = mysqli_query($connNew,"Select  `fo_remarks_details`.* from `fo_remarks_details` where `id_fo_folio` = '".$id_fo_folio."'  ");
					if (mysqli_num_rows($sqlOrderDetail) > 0) {
						
						?>
              <tr>
                <td>
                 
                      <?php
                                    $sqlOrderDetail = mysqli_query($connNew,"Select  `fo_remarks_details`.* from `fo_remarks_details` where `id_fo_folio` = '".$id_fo_folio."'  ");
		                                if (mysqli_num_rows($sqlOrderDetail) > 0) {
				                                while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)) {
				                                    $resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='remark_type' and id='".$rowOrderDetail->id_type."' ",' ORDER BY `field_value` ');
			                                      $resultCat = $db->fetch_object2($resCat);
				                                    $type = ucfirst($resultCat->field_value);
                                            if ($type == "Billing Remarks") {
                                ?>
                      <tr>
                        <td style="margin:10px !important; padding:10px !important"> <b>Remarks :</b>
                          <?php echo  $rowOrderDetail->remark."<br />"; ?></td>
                      </tr>
                      <?php
                                            }
                                        }
                                    }
                                ?>    
                                   <?php
                                      
                                    }
                                ?>       
                                    </tfoot>
                                </table>
                            </section>

                            <section class="footer-details">
                                <div class="bank-info" style="display : flex; justify-content : space-between;">
                                    <div style="width : 49%!important;">
                                        <p><?php echo $bank_account_legal_name != '' ?  "Account Name :- ".$bank_account_legal_name : ""; ?>&nbsp;
                            <?php echo $bank_account_no != '' ?  "Account Number : ".$bank_account_no : ""; ?>&nbsp;
                            <br><?php echo $bank_account_type != '' ?  "Account Type : ".$bank_account_type : ""; ?>
                            <?php echo $bank_name != '' ?  "Bank Name : ".$bank_name : ""; ?>&nbsp;
                            <?php echo $bank_ifsc_code != '' ?  "IFSC Code : ".$bank_ifsc_code : ""; ?>&nbsp;<br />
                            <?php echo $bank_branch != '' ?  "Branch : ".ucwords($bank_branch) : ""; ?></p>
                                       
                                    </div>

                                    <div style="width : 30%!important;">
                                        <p>Yours Faithfully</p>
                                        <p><?php echo $HotelName; ?></p>

                                    </div>

                                </div>

                                <div class="signatures">

                                    <div class="signature-block guest-signature">
                                        <p>GUEST SIGNATURE</p>
                                    </div>

                                    <div class="signature-block">
                                        <p>AUTHORISED SIGNATURE</p>
                                    </div>
                                </div>
                                <div class="note">
                                    <p>PLEASE DEPOSIT YOUR ROOM KEY ON CHECKOUT</p>
                                   <?php
                if ($fo_bill_notes != '') {
                ?>  <p>Note: -   <?php echo $fo_bill_notes; ?>
                                    </p>
                                    <?php } ?>
                                </div>
                            </section>
                        </div>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <footer class="invoice-footer">
                            <div class="page-number"></div>
                        </footer>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
    // --- Helper function for number to words (remains the same) ---
    function numberToWords(num) {
        const units = ['', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
        const teens = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen',
            'Eighteen', 'Nineteen'
        ];
        const tens = ['', '', 'Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

        if (num === 0) return 'Zero';

        function convertLessThanOneThousand(n) {
            let s = '';
            if (n >= 100) {
                s += units[Math.floor(n / 100)] + ' Hundred ';
                n %= 100;
            }
            if (n >= 10 && n <= 19) {
                s += teens[n - 10];
            } else if (n >= 20) {
                s += tens[Math.floor(n / 10)];
                if (n % 10 !== 0) s += ' ' + units[n % 10];
            } else if (n > 0) {
                s += units[n];
            }
            return s.trim();
        }

        let result = '';
        let lakhs = Math.floor(num / 100000);
        let thousands = Math.floor((num % 100000) / 1000);
        let remainder = num % 1000;

        if (lakhs > 0) {
            result += convertLessThanOneThousand(lakhs) + ' Lakh ';
        }
        if (thousands > 0) {
            result += convertLessThanOneThousand(thousands) + ' Thousand ';
        }
        if (remainder > 0) {
            result += convertLessThanOneThousand(remainder);
        }

        return result.trim() + ' Rupees';
    }

    // --- DOMContentLoaded for initial data calculations (remains the same) ---
    document.addEventListener('DOMContentLoaded', function() {
        const itemTableBody = document.querySelector('.item-table tbody');
        const roomTariffRows = itemTableBody.querySelectorAll(
            'tr:not(.date-group-header):not(.date-subtotal-row)');

        //let dateSubTotal = 0;

       

        const dateSubTotalRowElement = document.querySelector('.date-subtotal-row .total-amount');
        if (dateSubTotalRowElement) {
            dateSubTotalRowElement.textContent = dateSubTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        const summarySubtotalElement = document.querySelector('.summary-section .total-amount');
        if (summarySubtotalElement) {
            summarySubtotalElement.textContent = dateSubTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        const grandTotalValueElement = document.getElementById('grand-total-value');
        const balanceValueElement = document.getElementById('balance-value');

        if (grandTotalValueElement) {
            grandTotalValueElement.textContent = dateSubTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }
        if (balanceValueElement) {
            balanceValueElement.textContent = dateSubTotal.toLocaleString('en-IN', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            });
        }

        const grandTotalValue = parseFloat((grandTotalValueElement ? grandTotalValueElement.textContent : '0')
            .replace(/,/g, ''));
        const rupeesInWordsTextElement = document.getElementById('rupees-in-words-text');
        if (!isNaN(grandTotalValue) && rupeesInWordsTextElement) {
            rupeesInWordsTextElement.textContent = numberToWords(Math.round(grandTotalValue));
        }
    });

    // --- Print function with dynamic margin/width adjustment via JS ---
    function printInvoice() {
        const invoiceContainerOriginal = document.querySelector('.invoice-container');
        const originalBodyHtml = document.body.innerHTML; // Store original body content

        // 1. Create a temporary container to manipulate the invoice content
        const tempPrintDiv = document.createElement('div');
        tempPrintDiv.innerHTML = invoiceContainerOriginal.innerHTML; // Copy the invoice content into the temp div

        // 2. Find the .invoice-container and .invoice-main-table within this temporary content
        const invoiceContainerInTemp =
            tempPrintDiv; // Since we copied the innerHTML, tempPrintDiv IS the invoice-container's content
        // So, we apply styles to tempPrintDiv directly.
        const invoiceMainTableInTemp = tempPrintDiv.querySelector('.invoice-main-table');

        // 3. Apply desired styles to the invoiceContainerInTemp
        if (invoiceContainerInTemp) {
            // *** APPLYING USER-REQUESTED STYLES VIA JS ***
            invoiceContainerInTemp.style.width = '95%';
            invoiceContainerInTemp.style.margin = '2mm auto';
            invoiceContainerInTemp.style.boxSizing = 'border-box'; // Ensure box model consistency
            // **********************************************
        }

        // 4. Also apply padding to the invoice-main-table within the temporary container for border visibility
        // This is crucial for ensuring the main table's border has internal space.
        if (invoiceMainTableInTemp) {
            invoiceMainTableInTemp.style.padding = '10mm'; // Apply padding to all sides for border visibility
            invoiceMainTableInTemp.style.boxSizing = 'border-box'; // Crucial for padding calculation
        }

        // 5. Replace the body's HTML with the modified content from the temporary div
        document.body.innerHTML = tempPrintDiv
            .outerHTML; // Use outerHTML to include the .invoice-container itself with styles

        // 6. Trigger the print dialog
        window.print();

        // 7. Restore the original body HTML after printing (with a slight delay for reliability)
        setTimeout(() => {
            document.body.innerHTML = originalBodyHtml;
        }, 10);
    }
    </script>
</body>

</html>