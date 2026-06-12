<?php include_once("../config/auto_loader.php");?>
<?php include_once("../includes/header.php");?>
<?php include_once("../includes/left.php");?>
<style>
  body {
    font: 13px 'Segoe UI', Tahoma, Arial, Helvetica, sans-serif;
  }


  pre {
    background: #fff !important;
  }

  #bot pre {
    overflow: hidden;
  }


  .newFormatTbl {

    border: 1px solid #333;
    border-collapse: collapse;
  }

  .newFormatTbl td {

    border: 1px solid #333;
    padding: 0px !important;
  }

  .newFormatTbl td table {
    width: 100% !important;
  }

  .out-table .table>tbody>tr>td {
    padding: 0px !important;

  }

  table.dataTable tbody td {
    padding-top: 0px !important;
    padding-bottom: 0px !important;
  }
  
	@media print {
    /* Hide the print button when printing */
    .hide-on-print {
        display: none;
    }

    /* Your existing print styles for other elements */
    section.content-header {
        display: none;
    }

    section.content > .row:first-child {
        display: none;
    }

    footer {
        display: none;
    }

    .sidebar-mini.sidebar-collapse .content-wrapper {
        margin-left: 0px !important;
    }
		
		.box-header{
		display : none;	
		}
		
		aside{
		display : none;	
		}
		
		.content-header{
			display : none;	
		}
		
		
}
</style>

<?php 

$id_fo_bill	     =  addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
$id_folio	     =  addslashes(encryptor(decrypt,$_REQUEST['id_folio']));

$id_fo_folio_to	 =  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
$id_fo_folio		=  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
$id_parent_folio		=  selectColumn('fo_folio','id_parent_folio'," WHERE `id` = '".$id_fo_folio."'");
$id_parent_fo_bill		=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$id_parent_folio."'");
$fo_bill_query = mysqli_query($connNew, "select * from ".TBL_DOC_TYPE_CONFIG." where doc_type = '803' order by id desc");
$fo_bill_result = mysqli_fetch_object($fo_bill_query);
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
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->sub_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['sgst']=$rowOrderDetail->sgst_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['cgst']=$rowOrderDetail->cgst_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['vat']=$rowOrderDetail->vat_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['surcharge']=$rowOrderDetail->surcharge_total_items;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->net_amount_items;//+$rowOrderDetail->tax_per_day_per_room;
					
					$CurrentTotal	+=$rowOrderDetail->net_amount_items;
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
    $reservation_based_on_percentage[$percentage][$reservation->id]['taxable_amount'] = $reservation->tariff_price_per_day_per_room ?? 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_percentage'] = $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_amount'] = $reservation->tax_per_day_per_room ?? 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_percentage'] = $percentage > 0 ? ($percentage / 2) : 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_amount'] = ($reservation->tax_per_day_per_room ?? 0) / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_percentage'] = $percentage > 0 ? ($percentage / 2) : 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_amount'] = ($reservation->tax_per_day_per_room ?? 0) / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_percentage'] = 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_amount'] = 0;
}

$reservation_addon_query = mysqli_query($connNew, "select * from fo_reservations_addons_details where id_fo_folio_to = '".$id_folio."'");
while ($reservation = mysqli_fetch_object($reservation_addon_query)) {
    $amount = $reservation->rate * $reservation->qty * $reservation->days;
    $tax = $reservation->tax_value * $reservation->qty * $reservation->days;
    $percentage = ($tax / $amount) * 100;
    $reservation_based_on_percentage[$percentage][$reservation->id]['taxable_amount'] = $amount;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_percentage'] = $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_amount'] = $tax ?? 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_percentage'] = $percentage / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_amount'] = ($tax) / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_percentage'] = $percentage / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_amount'] = ($tax) / 2;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_percentage'] = 0;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_amount'] = 0;
}




$reservation_query = mysqli_query($connNew, "SELECT  
    d.id_mst_charges_sales_local,
    SUM(d.item_cgst_amount) AS item_cgst_amount,
    SUM(d.item_sgst_amount) AS item_sgst_amount,
    SUM(d.item_vat_amount) AS item_vat_amount,
    SUM(d.item_surcharge_amount) AS item_surcharge_amount,
	SUM(d.rate_per_main_unit*d.qty) AS item_amount,
    
    MAX(d.item_sgst_percent) AS item_sgst_percent,
    MAX(d.item_cgst_percent) AS item_cgst_percent,
    MAX(d.item_vat_percent) AS item_vat_percent,
    MAX(d.item_surcharge_percent) AS item_surcharge_percent,

    (SELECT SUM(grant_total_amount)
     FROM pos_purch
     WHERE id_fo_folio_to = '".$id_folio."'
       AND cancelled = '0') AS grant_total_amount
FROM pos_purch p
JOIN pos_purch_details d ON d.id_pos_purch = p.id
WHERE p.id_fo_folio_to = '".$id_folio."'
  AND p.cancelled = '0'
GROUP BY d.id_mst_charges_sales_local;");

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

$item_amount = $reservation->item_amount;

//echo '=============='.$reservation->item_amount;
    $reservation_percentage[] = $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['taxable_amount'] += $item_amount;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_percentage'] += $percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['total_tax_amount'] += $amount;
	$reservation_based_on_percentage[$percentage][$reservation->id]['TotalAmountWithoutTax'] +=$item_amount;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_percentage'] += $sgst_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['sgst_amount'] += $reservation->item_sgst_amount;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_percentage'] += $cgst_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['cgst_amount'] += $reservation->item_cgst_amount;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_percentage'] += $vat_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['vat_amount'] += $reservation->item_vat_amount;
	$reservation_based_on_percentage[$percentage][$reservation->id]['surcharge_percentage'] += $surcharge_percentage;
    $reservation_based_on_percentage[$percentage][$reservation->id]['surcharge_amount'] += $reservation->item_surcharge_amount;
	$reservation_based_on_percentage[$percentage][$reservation->id]['table'] = 'pos_purch';
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
<div class="content-wrapper" style="min-height: 391px;">

  <!-- Content Header (Page header) -->
  <?php /* echo '<pre>';
print_r($folioArray);
echo '</pre>';*/
?>

  <section class="content-header">
    <!-- <h1> Laundry Print </h1>-->
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Laundry Print </li>
    </ol>
  </section>

  <!-- Main content -->

  <section class="content">

    <div class="row">

      <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box " style="margin-right:45px">
        <a href="onewindow.php?submenu=218&session=0">
          <div class="btn o-btn"><i class="fa-solid fa-window-maximize"></i> One window</div>
        </a>
      </div>


      <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
       <button class="btn c-btn btn-block" style="margin-right:15px; display : none" onclick="printdiv('div_print');"><i
            class="fa fa-print fa-1x"></i> Print</button>
		  
		  <button class="print-button-only-table btn c-btn btn-block" onclick="printFrontTable()"><i
            class="fa fa-print fa-1x"></i>Print</button>

   <script>
    function printFrontTable() {
        var printContents = document.getElementById('frontprintTable').innerHTML;
        var originalContents = document.body.innerHTML; // Store original content

        // 1. Create a new div to hold the print content and apply styling
        var printContainer = document.createElement('div');
        printContainer.innerHTML = printContents;

        // 2. Apply inline style directly to the printContainer or the body if it contains only the table
        // We'll apply it to the body itself, as it becomes the print content.
        // First, temporarily clear the body.
        document.body.innerHTML = '';

        // Now, append the print content to the body
        document.body.appendChild(printContainer);

        // 3. Apply the desired margin directly to the body element using JavaScript
        // This will be applied *before* printing.
        document.body.style.margin = '5mm'; // You can use '2cm', '1in', '50px', etc.

        // 4. Trigger the print dialog
        window.print();

        // 5. Restore the original page content after printing
        // It's crucial to clear the applied style when restoring to avoid affecting the original page.
        document.body.style.margin = ''; // Clear the inline margin
        document.body.innerHTML = originalContents;
    }
    </script>
      </div>
    </div>

    <div class="row">
      <div class="col-xs-12 col-md-7">

        <!-- /.box -->

        <div class="box">
          <div class="box-header">

          </div>
          <div id="">
            <div class="box-body" id="frontprintTable">

              <table class="table dataTable  no-footer table-responsive out-table" width="100%" border="0"
                cellspacing="0" cellpadding="10" style="border:0.4px solid #000;">
                <tbody>

                  <tr>
                    <td style="border-bottom: 0.4px solid #000;padding:0px!important;">

                      <table class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0"
                        cellpadding="10">

                        <tbody>
                          <tr>
						<?php 
							  $image_path = $UPLOAD_FILES.'/shop/';
							  $image_display_path = $UPLOAD_FILES_PATH ."/shop/";
							  $image	= selectColumn(TBL_SHOP,'image'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
							  if(@file_exists($image_path.$image) && $image!=''){ ?>	  
							  
                            <td class="pm"
                              style="display: flex;justify-content: center;align-items: center;padding: 12px 0px !important;">
                              <img src="<?php echo $image_display_path.$image; ?>" width="80px" alt="" style="margin:15px 0px 0px 10px;">
                            </td>
							  
							  <?php }else{ ?>
							  <td class="pm"
                              style="display:table-cell;width:20%;border-right:.4px solid #000!important;display: none;">
                              <img src="" width="137px" alt="">
                            </td>
							  <?php } ?>
                            <?php if($_SESSION['database']=='mmr_pms'){?>
                            <td class="pm" style="display:table-cell;width:5%;border-right:.4px solid #000!important;">
                              
                            </td>
                            <?php } ?>
                            <?php if($_SESSION['database']=='jungle_home'){
							 $logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');?>
                            <td class="pm" style="display:table-cell;width:35%;border-right:.4px solid #000!important;">
                              <img src="<?php echo $SITE_URL.'/uploaded_files/outlets/'.$logoImage; ?>" width="100%"
                                alt="">
                            </td>

                            <?php } ?>
                            <td class="pm" style="width:80%;font-family: sans-serif;font-size:11px;">
                              <center>
                                <p style="font-size:14px;font-weight:500;padding-bottom:0;"><b>Tax Invoice</b></p>
                              </center>
                              <center>
                                <p><b style="font-size:30px;"><?php echo strtoupper($HotelName); ?></b><br>
                                  <?php echo $HotelAddress.', '; ?>
                                  <?php echo $HotelCity .' - '.$HotelPincode,','.$HotelState; ?><br>
                                  <?php if($HotelCIN!=''){?>
                                  <b> CIN:</b> <?php echo $HotelCIN; ?>
                                  <?php } ?>
                                  <b> GST NO : </b><?php echo $HotelGST; ?>
                                  <?php echo '<br><b> PAN:</b> '.$Hotelpan; ?>
                                  <br><b> PH:</b> +91 <?php echo $Hotelsecondary_landline.' '; ?>

                                  <?php echo '<b> Email:</b> '.$HotelEmail; ?><br>
                                </p>
                              </center>

                            </td>

                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>


                  <tr>
                    <td style="padding:0px!important;">


                      <table class="table dataTable table-striped no-footer " width="100%" border="0" cellspacing="0"
                        cellpadding="10">
                        <thead>
                          <tr>
                            <td class="pm"
                              style="width:40%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Guest Name</p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Room No</p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Bill No</p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Bill Date</p>
                                </center>
                              </b>
                            </td>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <td class="pm" rowspan="2"
                              style="text-align:left;width:40%;border-bottom:.4px solid #000;border-right:.4px solid #000;font-size:11px;font-family: sans-serif; padding-left : 4px!important;">

                              <left>

                                <p> <span><b><?php echo $GuestName; ?></b></span><br>
                                  <?php  echo $GuestAddress.$GuestCity!=''?','.$GuestCity:'';
									$k=1;
						foreach ($guests as $id_guest=>$guest) {
							
							echo $guest.'<br/>' ;
						}

									
									?>

                                  <br></p>
                              </left>
                            </td>
                            <td class="pm"
                              style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;"><?php echo $roomNumber;?>
                              </p>
                            </td>

                            <td class="pm"
                              style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;"><?php echo $mdoc_no;?>
                              </p>
                            </td>
                            <td class="pm"
                              style="text-align:center;width:20%;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;"><?php echo $doc_date; ?>
                              </p>
                            </td>
                          </tr>
                          <td class="pm"
                            style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                            <b>
                              <center>
                                <p style="padding:5px;margin:0;">Check-In</p>
                              </center>
                            </b>
                          </td>
                          <td class="pm"
                            style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                            <b>
                              <center>
                                <p style="padding:5px;margin:0;">Check-Out</p>
                              </center>
                            </b>
                          </td>
                          <td class="pm"
                            style="width:20%;font-size:11px;font-family: sans-serif;border-bottom:.4px solid #000;padding:0;margin:0;">
                            <b>
                              <center>
                                <p style="padding:5px;margin:0;">Pax</p>
                              </center>
                            </b>
                          </td>
                          <tr>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">
                                    <?php
							if ($is_bill_to_company > 0) {
						  ?>
                                    Company Name
                                    <?php
              }
                                ?>
                                  </p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;">
                                <?php echo $checkin_date; ?>&nbsp;<?php echo $checkin_time != '' ? $checkin_time : ""; ?>
                              </p>
                            </td>

                            <td class="pm"
                              style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;">
                                <?php  echo $checkout_date; ?>&nbsp;<?php echo $checkout_time != '' ? $checkout_time : ""; ?>
                              </p>
                            </td>
                            <td class="pm"
                              style="text-align:center;width:20%;border-bottom:.4px solid #000;font-family: sans-serif;">
                              <p style="font-size:11px;"><?php echo array_sum($paxArray)." / ".$rate_plan_name; ?>
                              </p>
                            </td>
                          </tr>
                          <tr>
                            <?php
							if ($is_bill_to_company > 0) {
						  ?>

                            <td class="pm"
                              style="text-align:left;;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif; padding-left : 5px!important;">
                              <p style="font-size:11px;"><span> <b><?php echo $CompanyName;?></b></span>
                                <br> <?php echo $CompanyAddress.$CompanyCity.$CompanyState,$pincode;?></span>
                              </p>
                            </td>
                            <?php
                            
							}
              $colspan = $is_bill_to_company > 0 ? "3" : "4";
						  ?>
                            <td class="pm" colspan="<?php echo $colspan; ?>"
                              style="text-align:left;border-bottom:.4px solid #000;font-family: sans-serif; vertical-align : middle!important; padding-left : 5px!important;">
                              <p style="font-size:11px;"><b>Booking By : <?php echo $company_contacts;?></b>
                              </p>
                            </td>

                          </tr>
                          <tr class="companyGst">
                            <?php
                          if ($is_bill_to_company > 0) {
                          ?>
                            <td class="pm" colspan="1"
                              style="text-align:left;font-family: sans-serif;margin:0;padding:5px !important;border-right: 0.4px solid #000;">
                              <p style="font-size:11px;"><span> <b>Company GST :</b></span>
                                <?php echo $CompanyGST;?></span>
                              </p>
                            </td>
                            <?php
                          }
                            ?>
                            <td class="pm" colspan="3"
                              style="text-align:left;font-family: sans-serif;margin:0;padding:5px !important;">
                              <p style="font-size:11px;"><span> </b></span>
                                </span>
                              </p>
                            </td>

                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:0px!important;border-bottom: 0.4px solid #000;border-top: 0.4px solid #000;">




                      <table id="myTable1" class="table table-striped no-footer dataTable" width="100%" border="0"
                        cellspacing="0" cellpadding="10">
                        <thead>
                          <tr>
                           

                            <td class="pm" colspan="3"
                              style="width:65%;font-size:10px;font-family: sans-serif;padding:0px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>Description</b></p>
                            </td>
                            <td class="pm"
                              style="font-size:10px;font-family: sans-serif;width: 8%;padding:0px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>SAC</b></p>
                            </td>
                            <td class="pm"
                              style="font-size:10px;font-family: sans-serif;padding:0px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>Charges</b></p>
                            </td>
                            <td class="pm"
                              style="font-size:10px;font-family: sans-serif;width: 11%;padding:0px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>Tax </b></p>
                            </td>



                            <td class="pm"
                              style="font-size:10px;font-family: sans-serif; padding:0px 4px!import0.4px solid #000;border-bottom: 0.4px solid #000; vertical-align: middle; border-right: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>Credits</b></p>
                            </td>

                            <td class="pm"
                              style="font-size:10px;font-family: sans-serif;width: 8%;padding:0px 4px!important;margin:0;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;padding: 0 !important;"><b>Amount</b></p>
                            </td>
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
	
	}
    foreach ($Array1 as $rowid => $Array2) { 
        $SubCurrentTotal += $Array2['Total']; // Accumulate total per room
		if($Array2['RoomType']==''){
			$RoomName='';
		}
?>
    <tr>
        <td class="pm" colspan="3" style="width:5px;padding:0px!important;margin:0;border-right:.4px solid #000;padding:0px 4px!important;">
            <p style="font-size:10px;font-family: sans-serif;margin:0!important;padding-left:4px;padding: 0 !important;">
                <?php if ($ics === 1): ?>
                    <b><?php echo $RoomName; ?></b><br>
                   
                <?php endif; ?>

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
                
            </p>
        </td>

        <td class="pm" style="width: 3%;padding:0px!important;padding-right:3px!important;margin:0;border-right:.4px solid #000;text-align:center;vertical-align:middle;">
            <p style="font-size:10px;font-family: sans-serif;padding:0!important;margin:0!important;">
                 <?php if ($ics === 1): ?> <br> <?php endif; ?> <?php echo $Array2['sac_no']; ?>
            </p>
        </td>

        <td class="pm" style="width: 12%;padding:0px!important;padding-right:3px!important;margin:0;border-right:.4px solid #000;vertical-align:middle;">
            <p style="font-size:10px;font-family: sans-serif;padding:0!important;margin:0!important;text-align:right;">
               <?php if ($ics === 1): ?> <br> <?php endif; ?>   <?php echo number_format($Array2['tariff'], 2); ?>
            </p>
        </td>

        <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;vertical-align:middle;">
            <p style="font-size:10px;font-family: sans-serif;padding:0!important;margin:0!important;text-align:right;">
                <?php if ($ics === 1): ?> <br> <?php endif; ?>  <?php echo number_format($Array2['cgst'] + $Array2['sgst'] + $Array2['vat'] + $Array2['surcharge'], 2); ?>
            </p>
        </td>

        <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;">
            <p style="font-size:10px;font-family: sans-serif;padding:0!important;margin:0!important;text-align:right;">
            </p>
        </td>

        <td class="pm" style="width: 20%;padding:0px!important;padding-right:3px!important;margin:0;vertical-align:middle;">
            <p style="font-size:10px;font-family: sans-serif;padding:0!important;margin:0!important;text-align:right;">
               <?php if ($ics === 1): ?> <br> <?php endif; ?>   <?php echo number_format($Array2['Total'], 2); ?>
            </p>
        </td>
    </tr> <?php $ics = 0; ?>
<?php 
    } // end inner loop
?>

<!-- Room Sub Total -->
<tr>
    <td class="pm" colspan="3" style="border-top:.4px solid #000;text-align:center;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;">
        <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
            <b>Room Sub Total : </b>
        </p>
    </td>

    <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;border-top:.4px solid #000;"></td>
    <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;border-top:.4px solid #000;"></td>
    <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;padding-right:3px!important;border-bottom:.4px solid #000;border-top:.4px solid #000;"></td>
    <td class="pm" style="padding:0px!important;margin:0;border-right:.4px solid #000;text-align:right;padding-right:4px!important;border-bottom:.4px solid #000;border-top:.4px solid #000;"></td>
    <td class="pm" style="width: 20%;padding:0px!important;padding-left:5px!important;margin:0;text-align:right;border-bottom:.4px solid #000;border-top:.4px solid #000;">
        <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
             <b> <?php echo number_format($SubCurrentTotal, 2); ?>  </b>
        </p>
    </td>
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
                  <?php 
	$i=1;
	while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
	
		//pos_purch_details
		
		
				
				?>
                  <tr>
                   
                    <td class="pm" colspan="6"
                      style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;padding:0px 4px!important;">
                      <p style="font-size:11px;font-family: sans-serif;margin:0!important;padding: 1px 0 !important;">
                       <b><?php echo $i=='1'?'Receipt<br/>':'';?></b>
                        <?php echo date('d-m-Y',strtotime($row_fo_receipt->doc_date)); ?>-
                     
                        
                        <?php echo $row_fo_receipt->payment_mode;?><?php
						  if($row_fo_receipt->payment_mode =='COMPANY'){
							$id_mst_receipt_company	=$row_fo_receipt->id_company;
							
							echo '  -  '.selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$id_mst_receipt_company."' and status='1' ");	
							}?></p>
                    </td>
                   

                    

                    <td class="pm"
                      style="margin:0;border-right:.4px solid #000; vertical-align: middle; text-align: right; padding-right: 3px !important;">
                      <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;padding: 1px 0 !important;">
                      <?php echo $i=='1'?'<br/>':'';?>  <?php echo number_format($row_fo_receipt->amount, 2);?></p>
                    </td>

                    <td class="pm"
                      style="width: 20%;padding:0px!important;padding-left:5px!important;margin:0;">
                      <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                        </p>
                    </td>

                  </tr>
                  <?php $i++;
				  
				  } ?>
                  <?php } ?>
                  <tr>
                   

                    <td class="pm" colspan="6"
                      style="border-top:.4px solid #000;padding-top:4px!important;width: 15%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;text-align:center;">
                      <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                        <b>Sub Total : </b>
                      </p>
                    </td>
					  
                 
                    <td class="pm"
                      style="border-top:.4px solid #000;padding-top:4px!important; padding:0px!important;margin:0;border-right:.4px solid #000; text-align : right!important; padding-right : 4px!important;">
                      <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                         <b> <?php echo number_format($receipt_amount,2); ?>  </b>
                      </p>
                    </td>

                    <td class="pm"
                      style="border-top:.4px solid #000;padding-top:4px!important;width: 20%;padding:0px!important;padding-left:5px!important;margin:0; text-align : right!important;">
                      <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                          <b><?php echo number_format($CurrentTotal,2); ?>  </b></p>
                    </td>

                  </tr>
                </tbody>
              </table>
              </td>
              </tr>




              <tr>
                <td>
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

    $result = $integerPartWords . ' Rupees';
    if ((int)$decimalPart > 0) {
        $result .= ' and ' . $decimalPartWords . ' Paise';
    }

    return $result;
}

// Example usage:
$amount = round(($CurrentTotal),0);
$convert_amount_to_words= convert_amount_to_words($amount); // Outputs: One Million Two Hundred Thirty-Four Thousand Five Hundred Sixty-Seven Rupees and Eighty-Nine Paise
?>
                  <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%">
                    <thead>
                      <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                    </thead>

                    <tbody>
                      <tr>
                        <td></td>
                        <td class="pm" colspan="5"
                          style="font-family: sans-serif;font-size:12px;padding: 5px 4px !important;">
                          Rupees (In Words): <b><?php echo $convert_amount_to_words; ?></b>
                        </td>
                        <td class="pm" style="font-family: sans-serif;font-size:11px;">
                          <p></p>
                        </td>
                        <td class="pm" style="font-family: sans-serif;font-size:11px;text-align: right;">
                          <p style="padding-bottom:0;">Round Off : </p>
                          <p style="padding:0;">Grand Total :</p>
                          <?php if($_SESSION['shop_code']!='hip'){ ?> <p style="padding:0;">Balance :</p>
                          <?php } ?>
                        </td>
                        <td class="pm" style="font-family: sans-serif;font-size:11px;text-align: left;">
                          <p style="padding-bottom :0;"> <b><?php echo round(round($CurrentTotal,0) - $CurrentTotal,2); ?></b>
                          </p>
                          <p style="padding:0;"> <b><?php echo round($CurrentTotal,0); ?></b></p>
                          <?php if($_SESSION['shop_code']!='hip'){ ?> <p style="padding:0;">
                            <b><?php echo round(round($CurrentTotal,0)-round($receipt_amount,2),2); ?></b></p><?php } ?>
                        </td>
                        <td class="pm">
                        </td>
                        <td>
                        </td>

                      </tr>



                    </tbody>

                  </table>
                </td>
              </tr>


              <tr>
                <td style="padding:0px!important;border-bottom: 0.4px solid #000;border-top: 0.4px solid #000;">
                  <table id="myTable1" class="table table-striped no-footer dataTable" width="100%" border="0"
                    cellspacing="0" cellpadding="10">
                    <thead>
                      <tr>
                        <td class="pm"
                          style="width:40%;font-size:11px;font-family: sans-serif; padding: 0px!important;margin:0; border-bottom: 0.4px solid #000; text-align : center; vertical-align : middle; padding-right : 10px!important; font-family: sans-serif!important; font-weight : 700;  font-size : "
                          colspan="6"> Tax Summary </td>
                      </tr>
                      <tr>
                        <td class="pm"
                          style="width:30%;font-size:11px;font-family: sans-serif; padding: 0px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000; text-align : right; vertical-align : middle; padding-right : 10px!important;"
                          rowspan="2">Taxable Value</td>
                        <td class="pm"
                          style="font-size:10px;font-family: sans-serif;padding: 0px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000; width : 15%;">
                          <table style="width : 100%;">
                            <tr style="border-bottom : 1px solid #333;">
                              <td colspan="2"
                                style="text-align : center; font-size : 11px!important; border-bottom : 1px solid #333!important;">
                                CGST</td>
                            </tr>
                            <tr>
                              <td
                                style="border : 0px solid #333;padding-left : 0!important;padding-right : 16px!important;text-align : center; border: 0; font-size : 11px!important;">
                                Rate</td>
                              <td
                                style="border : 1px solid #333;padding-left : 0!important;padding-right : 0!important;text-align : center;border-right: 0; border-bottom : 0; border-top : 0; font-size : 11px!important;">
                                Amount</td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="font-size:10px;font-family: sans-serif;padding: 0px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000; width : 15%;">
                          <table style="width : 100%;">
                            <tr>
                              <td colspan="2"
                                style="text-align : center; font-size : 11px!important; border-bottom : 1px solid #333!important;">
                                SGST</td>
                            </tr>
                            <tr>
                              <td
                                style="border : 0px solid #333;padding-left : 0!important;padding-right : 15px!important;text-align : center;border-left: 0; border-bottom : 0; font-size : 11px!important;">
                                Rate</td>
                              <td
                                style="border : 1px solid #333;padding-left : 0!important;padding-right : 0!important;text-align : center;border-right: 0; border-bottom : 0; border-top : 0; font-size : 11px!important;">
                                Amount</td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="font-size:10px;font-family: sans-serif;padding: 0px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000; width : 15%;">
                          <table style="width : 100%;">
                            <tr>
                              <td colspan="2"
                                style="text-align : center; font-size : 11px!important; border-bottom : 1px solid #333!important;">
                                VAT</td>
                            </tr>
                            <tr>
                              <td
                                style="border : 0px solid #333;padding-left : 0!important;padding-right : 15px!important;text-align : center;border-left: 0; border-bottom : 0; font-size : 11px!important;">
                                Rate</td>
                              <td
                                style="border : 1px solid #333;padding-left : 0!important;padding-right : 0!important;text-align : center;border-right: 0; border-bottom : 0; border-top : 0; font-size : 11px!important;">
                                Amount</td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="font-size:10px;font-family: sans-serif;padding: 0px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000; width : 15%;">
                          <table style="width : 100%;">
                            <tr>
                              <td colspan="2"
                                style="text-align : center; font-size : 11px!important; border-bottom : 1px solid #333!important;">
                                Surcharge</td>
                            </tr>
                            <tr>
                              <td
                                style="border : 0px solid #333;padding-left : 0!important;padding-right : 15px!important;text-align : center;border-left: 0; border-bottom : 0; font-size : 11px!important;">
                                Rate</td>
                              <td
                                style="border : 1px solid #333;padding-left : 0!important;padding-right : 0!important;text-align : center;border-right: 0; border-bottom : 0; border-top : 0; font-size : 11px!important;">
                                Amount</td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="width:50%;font-size:10px;font-family: sans-serif;padding: 0px!important;margin:0;border-bottom: 0.4px solid #000; width : 15%;">
                          <table style="width : 100%;">
                            <tr>
                              <td colspan="2" style="text-align : center; font-size : 11px!important;">Total</td>
                            </tr>
                            <tr>
                              <td colspan="2" style="text-align : center; font-size : 11px!important;">Tax Amount</td>
                            </tr>
                          </table>
                        </td>
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
                                    foreach ($reservation_based_on_percentage as $key => $reservations) {
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
                      <tr style="">
                        <td class="pm"
                          style=" padding-left :0px!important; padding-right :8px!important; margin:0;border-right:.4px solid #000; border-bottom : 1px solid #333; padding-top: 0 !important; padding-bottom: 0 !important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important; text-align : right;">
                            <?php echo number_format($taxable_amount,2); ?>
                          </p>
                        </td>
                        <td class="pm"
                          style="padding-top:0px!important; padding:0px!important;margin:0;border-right:.4px solid #000; border-bottom : 1px solid #333; padding-top: 0 !important; padding-bottom: 0 !important;">
                          <table
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important; width : 100%!important;">
                            <tr>
                              <td
                                style="padding : 0px!important; padding-left : 0!important; padding-right :4px!important;  width: 32% !important; text-align : right!important; border-right:.1px solid #000; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo round(($reservation['cgst_percentage']),2)." %"; ?></td>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo number_format($cgst_amount,2); ?></td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="padding-top:4px!important; padding:0px!important;margin:0; border-right:.4px solid #000; border-bottom : 1px solid #333; padding-top: 0 !important; padding-bottom: 0 !important;">
                          <table
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;  width : 100%!important;">
                            <tr>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; border-right:.4px solid #000; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo round(($reservation['sgst_percentage']),2)." %"; ?></td>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo number_format($sgst_amount,2); ?></td>
                            </tr>
                          </table>
                        </td>
                        <td class="pm"
                          style="padding-top:4px!important; padding:0px!important;margin:0;border-right:.4px solid #000; border-bottom : 1px solid #333; padding-top: 0 !important; padding-bottom: 0 !important;">
                          <table
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;  width : 100%!important;">
                            <tr>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; border-right:.4px solid #000; padding-top: 0 !important; padding-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo round(($reservation['vat_percentage']),2)." %"; ?></td>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; padding-top: 0 !important; padding-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo number_format($vat_amount,2); ?></td>
                            </tr>
                          </table>
                        </td>
                        
                        
                        <td class="pm"
                          style="padding-top:4px!important; padding:0px!important;margin:0;border-right:.4px solid #000; border-bottom : 1px solid #333; padding-top: 0 !important; padding-bottom: 0 !important;">
                          <table
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;  width : 100%!important;">
                            <tr>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; border-right:.4px solid #000; padding-top: 0 !important; padding-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo round(($reservation['surcharge_percentage']),2)." %"; ?></td>
                              <td
                                style=" padding-left : 0!important; padding-right :4px!important; width: 32% !important; text-align : right!important; padding-top: 0 !important; padding-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important;">
                                <?php echo number_format($surcharge_amount,2); ?></td>
                            </tr>
                          </table>
                        </td>
                        
                        <td class="pm"
                          style="padding-top:4px!important; padding:0px!important;margin:0; border-bottom : 1px solid #333; padding-right :4px!important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important; text-align : right;">
                            <?php echo number_format($tax_amount,2); ?>
                          </p>
                        </td>
                      </tr>
                      <?php
                                    }
                                  }
                                  if ($total_taxable_amount > 0) {
                                ?>
                      <tr>
                        <td class="pm "
                          style="padding: 0px !important; padding-left :0px!important; padding-right :8px!important; margin:0;border-right:.4px solid #000; border-bottom : 0px solid #333;  ">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important; text-align:right;">
                            <b>Total: <?php echo number_format($total_taxable_amount,2); ?></b>
                          </p>
                        </td>
                        <td class="pm"
                          style="padding-top:4px!important; padding:0px!important; padding-right : 4px!important; margin:0;border-right:.4px solid #000; vertical-align: middle; text-align: right;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important;">
                            <b><?php echo number_format($total_cgst_amount,2); ?></b>
                          </p>
                        </td>
                        <td class="pm" style="padding-top:4px!important; padding:0px!important; padding-right : 4px!important; margin:0;border-right:.4px solid #000; vertical-align: middle;
    text-align: right!important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important;">
                            <b><?php echo number_format($total_sgst_amount,2); ?></b>
                          </p>
                        </td>
                        <td class="pm" style="padding-top:4px!important; padding:0px!important; padding-right : 4px!important; margin:0;border-right:.4px solid #000; vertical-align: middle;
    text-align: right!important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important;">
                            <b><?php echo number_format($total_vat_amount,2); ?></b>
                          </p>
                        </td>
                         <td class="pm" style="padding-top:4px!important; padding:0px!important; padding-right : 4px!important; margin:0;border-right:.4px solid #000; vertical-align: middle;
    text-align: right!important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important;">
                            <b><?php echo number_format($total_surcharge_amount,2); ?></b>
                          </p>
                        </td>
                        <td class="pm" style="padding-top:4px!important; padding:0px!important; padding-right : 4px!important; margin:0; vertical-align: middle;
    text-align: right!important;">
                          <p
                            style="font-size:11px;font-family: sans-serif;padding : 0px!important; padding-left : 0!important; padding-right : 0!important;margin:0!important;">
                            <b><?php echo number_format($total_tax_amount,2); ?></b>
                          </p>
                        </td>
                      </tr>
                      <?php } ?>
                    </tbody>
                  </table>
                </td>
              </tr>
              <?php
	$sqlOrderDetail = mysqli_query($connNew,"Select  `fo_remarks_details`.* from `fo_remarks_details` where `id_fo_folio` = '".$id_fo_folio."'  ");
					if (mysqli_num_rows($sqlOrderDetail) > 0) {
						
						?>
              <tr>
                <td style="border-top:.4px solid #000;">
                  <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%">
                    <tbody>

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
                    </tbody>
                  </table>
                </td>
              </tr>


              <?php
                                      
                                    }
                                ?>
              <tr>
                <td style="">

                  <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%">

                    <tbody>
                      <tr>

                        <td class="pm"
                          style="padding: 5px 14px 16px 10px!important;font-family: sans-serif;font-size:11px;">
                          <p>
                            <?php echo $bank_account_legal_name != '' ?  "Account Name :- ".$bank_account_legal_name : ""; ?>&nbsp;
                            <?php echo $bank_account_no != '' ?  "Account Number :- ".$bank_account_no : ""; ?>&nbsp;
                            <?php echo $bank_account_type != '' ?  "Account Type :- ".$bank_account_type : ""; ?><br />
                            <?php echo $bank_name != '' ?  "Bank Name :- ".$bank_name : ""; ?>&nbsp;
                            <?php echo $bank_ifsc_code != '' ?  "IFSC Code :- ".$bank_ifsc_code : ""; ?>&nbsp;
                            <?php echo $bank_branch != '' ?  "Branch :- ".$bank_branch : ""; ?>
                            <p> </p>
                            <p> </p>
                            <p> </p>
                            <p> </p>
                            <p style="font-weight:100;font-size:10px;padding: 45px 17px 6px 4px!important"
                              class="text-uppercase"><b>Guest Signature</b></p>
                        </td>

                        <td class="pm"
                          style="padding: 35px 14px 16px 10px!important;font-family: sans-serif;font-size:11px;text-align: center;">
                          <p> Yours Faithfully <br> For <b class="text-uppercase"> <?php echo $HotelName; ?></b></p>
                          <p> </p>
                          <p> </p>
                          <p> </p>
                          <p> </p>
                          <p> </p>
                          <p> </p>
                          <p style="text-transform:uppercase;"><b>Authorised Signature</b></p>
                        </td>

                      </tr>

                      <?php /*?><tr>

                        <td class="pm"
                          style="padding: 5px 14px 16px 10px!important;font-family: sans-serif;font-size:11px;">
                          <p style="text-transform:uppercase;"><b>Authorised Signature</b></p>
                          <p style="font-weight:100;font-size:10px;padding: 45px 17px 6px 4px!important"></p>
                        </td>
                      </tr><?php */?>
                      <tr>
                          <td>
                          <p style="text-transform:uppercase; text-align:center"><b>Please
                          Deposit your room key on Checkout</p>
                          </td>
                      </tr>
                    </tbody>

                  </table>
                </td>
              </tr>
              <?php
                if ($fo_bill_notes != '') {
                ?>
              <tr>
                <td style="padding:0px!important;border-bottom: 0.4px solid #000;border-top: 0.4px solid #000;">
                  <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%">
                    <tbody>
                      <tr>
                        <td style="padding : 5px 10px!important; font-weight : 500; font-size : 10px; font-family: sans-serif!important;">
                          <p
                            style="margin-bottom: 0 !important; padding-top: 0 !important; padding-bottom: 0 !important; line-height : 1.4!important;">
                            <?php echo $fo_bill_notes; ?></p>
                        </td>
                      </tr>
                    </tbody>
                  </table>
              </tr>
              <?php } ?>



              </tbody>
              </table>
              <br>
              <!--<table id="myTable1" class="table table-striped  dataTable no-footer mt-50" width="100%" border="0"
                cellspacing="0" cellpadding="10">
                <thead>
                  <tr>

                  </tr>
                </thead>
                <tbody>

                  <tr>
                    <td style="width:25%;font-family:sans-serif;font-size: 11px;">PURCHASE MANAGER</td>
                    <td style="width:40%;font-family:sans-serif;font-size: 11px;">STORE</td>
                    <td style="width:20%;font-family:sans-serif;font-size: 11px;">AGM</td>
                    <td style="width:25%;font-family:sans-serif;font-size: 11px;">COO</td>

                  </tr>
                </tbody>


              </table>-->


            </div>
          </div>

          <!--End InvoiceBot-->
        </div>

        <!--End Invoice-->

        <!-- /.box-body -->

      </div>
      <div class="col-md-5">
      </div>
    </div>
    <!--end of row-->



  </section>
</div>
<script language="javascript">
  function printData() {
    var divToPrint = document.getElementById("frontprintTable");
    newWin = window.open("");
    newWin.document.write(divToPrint.outerHTML);
    newWin.print();
    newWin.close();
  }

  $('button').on('click', function () {
    printData();
  });
</script>
<?php include_once("../includes/footer.php")?>