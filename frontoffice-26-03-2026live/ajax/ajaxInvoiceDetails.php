<?php 

include_once("../../config/auto_loader.php");
$ids=$_REQUEST['id'];
//echo $_REQUEST['id'];
$doc_type	=	'803';
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit = mysqli_num_rows($sqlNightAudit);
$rowNightAudit = mysqli_fetch_object($sqlNightAudit);
$today = date('Y-m-d',strtotime('+1 day',strtotime($rowNightAudit->dated)));
if($ids!='0'){
									
		$id_folio		=  $ids;
		$id_fo_bill		=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$id_folio."'");
		$fo_bill_no		=  selectColumn(FO_BILL,'mdoc_no'," WHERE `id` = '".$id_fo_bill."'");
		$id_owner_room  = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$id_fo_bill."'");
		$room_select 	= '<option value="">Select</option>';
									// $id_fo_bill	=  selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_folio."'");
  $id_bill_to_company	=  selectColumn('fo_folio','id_bill_to_company'," WHERE `id` = '".$id_folio."'");
  $is_force_checkout 	= selectColumn('mst_shops','force_system_date_as_checkout_date'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");
  $check_checkout_date  = 0;
  $system_date = strtotime(date('Y-m-d'));
  if ($is_force_checkout && $system_date != strtotime($today)) {
    $check_checkout_date = 1;
  }
  //$id_mst_guest	=  selectColumn(FO_BILL,'id_mst_guest'," WHERE `id_fo_folio_to` = '".$id_folio."'");
  $id_resevation	=  selectColumn(FO_BILL,'id_reservations'," WHERE `id` = '".$id_fo_bill."'"); //$ids[1];
  $id_mst_guest	=  selectColumn('fo_reservations_details','id_mst_guest'," WHERE `id_fo_reservations` = '".$id_resevation."' and id_mst_room_no_allocation = '".$id_owner_room."'");
   $id_mst_guest_order_by_room	=  selectColumn('fo_reservations_details','order_by_room'," WHERE `id_fo_reservations` = '".$id_resevation."' and id_mst_room_no_allocation = '".$id_owner_room."'");
									$id_room	='0';
					$sql	=	"SELECT * FROM ".FO_RESERVATIONS." where id='".addslashes($id_resevation)."'";
					$res 	= 	mysqli_query($connNew,$sql);
					if(mysqli_num_rows($res)>0){
					$row = mysqli_fetch_object($res);
					 $id_mst_guest;
					//$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
					$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest."'");				
	$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
	$Firstname	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
	$Lastname	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest."'");
	$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));


	$folioArray=array();
						
	// and `id_mst_room_no_allocation`='".addslashes($id_room)."'
		$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_folio_to` = '".$id_folio."' ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
         // $room_select .= '<option value="'.$rowOrderDetail->id_mst_room_no_allocation.'">'.$roomNo.'</option>';
									
					$mdoc_no	= selectColumn(FO_BILL,'mdoc_no'," WHERE `id_fo_folio_to` = '".$id_folio."'");
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;
					
					
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$rowOrderDetail->id_fo_folio_to."'");  
					
					
					$array_roomNo[$rowOrderDetail->id_mst_room_no_allocation]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;
					$array_roomNo[$rowOrderDetail->id_mst_room_no_allocation]['roomNo']=$roomNo;
					$array_roomNo[$rowOrderDetail->id_mst_room_no_allocation]['folio_mdoc_no']=$folio_mdoc_no;
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='Reservation';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= 'Tariff';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->tariff_price_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_table']= FO_RESERVATIONS_DETAILS;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_id']= $rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$mdoc_no;
					$CurrentTotal	+=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
					$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
					$TariffPerRoomperNights =$rowOrderDetail->tariff_price_per_day_per_room;
					$TaxPerRoomperNights = $rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Table']=FO_RESERVATIONS_DETAILS;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_table']=$rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;
					$is_room_owner = ($rowOrderDetail->id_mst_room_no_allocation === $id_owner_room) ? '1' : 0;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['is_room_owner']=$is_room_owner;
					
					
					
 $id_mst_guest_line_details = selectColumn('fo_reservations_details', 'id_mst_guest', "WHERE `id_fo_reservations` = '{$id_resevation}' AND id_mst_room_no_allocation = '{$rowOrderDetail->id_mst_room_no_allocation}'");
	$id_mst_attributes_title_line_details	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest_line_details."'");				
	$Title_line_details=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title_line_details."'"); 				
	$Firstname_line_details	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$Lastname_line_details	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$guestName_line_details=$Title_line_details.' '.ucwords(strtolower($Firstname_line_details)).' '.ucwords(strtolower($Lastname_line_details));					
			$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['guest_name']=$guestName_line_details;		
					
					
					
				}
				
			 $ReservationAvaliable='1';	
		}else{
			$ReservationAvaliable='0';
			}
}//echo '<pre>';
	//print_r($folioArray);
		//pos_purch_details
    // exit;
	
		$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($id_folio)."' and cancelled!=1 ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
				$id_mst_room_no_allocation=selectColumn(TBL_ATTRIBUTES,'id_mst_room_no'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND id= '".$rowOrderDetail->id_attribute_table."'");	
					
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
					$id_mst_room_types= selectColumn(TBL_ROOMNO,'id_mst_room_types'," WHERE `id` = '".$id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_mst_room_types."'");
									
					
					$outletName =selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowOrderDetail->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;//.'POS';	
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->doc_date));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $outletName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->sub_total_items-$rowOrderDetail->total_discount_items;//'-';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=($rowOrderDetail->sgst_total_items+$rowOrderDetail->cgst_total_items+$rowOrderDetail->vat_total_items+$rowOrderDetail->surcharge_total_items);
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_table']= 'pos_purch';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_id']= $rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->grant_total_amount+$rowOrderDetail->tax_per_day_per_room;
					$rowOrderDetail->grant_total_amount;
					$CurrentTotal	+=$rowOrderDetail->grant_total_amount;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Table']='pos_purch';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_table']=$rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_mst_room_no_allocation']=$id_mst_room_no_allocation;
					$is_room_owner = ($id_mst_room_no_allocation === $id_owner_room) ? '1' : 0;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['is_room_owner']=$is_room_owner;
					
					
					$id_mst_guest_line_details = selectColumn('fo_reservations_details', 'id_mst_guest', "WHERE `id_fo_reservations` = '{$id_resevation}' AND id_mst_room_no_allocation = '{$id_mst_room_no_allocation}'");
	$id_mst_attributes_title_line_details	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest_line_details."'");				
	$Title_line_details=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title_line_details."'"); 				
	$Firstname_line_details	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$Lastname_line_details	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$guestName_line_details=$Title_line_details.' '.ucwords(strtolower($Firstname_line_details)).' '.ucwords(strtolower($Lastname_line_details));					
			$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['guest_name']=$guestName_line_details;		
					
					
				}
				
				
		}
$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($id_folio)."' ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$id_mst_room_types	=  selectColumn('fo_reservations_details','id_mst_room_types'," WHERE `id_fo_reservations` = '".$rowOrderDetail->id_fo_reservations."' and id_mst_room_no_allocation = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$roomNo= selectColumn(TBL_CHARGES,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$id_mst_room_types."'");
					
					
					$chargesname= selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					$outletName ='Post Charges';
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;//.'POS';	
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']=$RoomNoAndRoomName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $outletName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']=$rowOrderDetail->amount;//'-';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_value;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$chargesname.' - '.$rowOrderDetail->id;//$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_table']= 'fo_reservations_addons_details';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_id']= $rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->total;
					$rowOrderDetail->total;
					$CurrentTotal	+=$rowOrderDetail->total;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Table']='fo_reservations_addons_details';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_table']=$rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['id_mst_room_no_allocation']=$rowOrderDetail->id_mst_room_no_allocation;
					$is_room_owner = ($rowOrderDetail->id_mst_room_no_allocation === $id_owner_room) ? '1' : 0;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['is_room_owner']=$is_room_owner;
					
					$id_mst_guest_line_details = selectColumn('fo_reservations_details', 'id_mst_guest', "WHERE `id_fo_reservations` = '{$id_resevation}' AND id_mst_room_no_allocation = '{$rowOrderDetail->id_mst_room_no_allocation}'");
	$id_mst_attributes_title_line_details	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest_line_details."'");				
	$Title_line_details=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title_line_details."'"); 				
	$Firstname_line_details	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$Lastname_line_details	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest_line_details."'");
	$guestName_line_details=$Title_line_details.' '.ucwords(strtolower($Firstname_line_details)).' '.ucwords(strtolower($Lastname_line_details));					
			$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['guest_name']=$guestName_line_details;		
				}
				
				
		}
		
//FO BILL STATUS=========================================
 $sqlFoBill	=	"SELECT * FROM fo_folio where `id` = '".$id_folio."'";
$resFoBill 	= 	mysqli_query($connNew,$sqlFoBill);

$rowFoBill = mysqli_fetch_object($resFoBill);
 				if($rowFoBill->folio_status == '0'){
                      $rowFoBillSelect1 ='selected="selected"';
					  $rowFoBillSelect2='';
					  
                        }
						if($rowFoBill->folio_status == '1'){
                          $rowFoBillSelect2 =  'selected="selected"';
						  $rowFoBillSelect1='';
						  $buttonHide='style="display:none;"';
						
                        } 
       				//$id_fo_folio_to	= selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");
					$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_folio."'");                     
//FO BILL STATUS==========================================
//`id_fo_folio_to` = '".$id_folio."'
$receipt_amount	=	round(selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$id_folio.'"'),2);


$CurrentTotal =round($CurrentTotal);
$BalanceAmount = round($CurrentTotal-$receipt_amount);
$checkout_date	= selectColumn(FO_BILL,'checkout_date'," WHERE `id_fo_folio_to` = '".$id_folio."'");
$checkoutstatus	= selectColumn(FO_BILL,'status'," WHERE `id_fo_folio_to` = '".$id_folio."'");
	
$sqlVa = "SELECT * FROM ".FO_BILL." where id='".$id_fo_bill."' and id_reservations='".$id_resevation."' and `doc_no`='0' and id_doc_type_configuration='0'";
$Vali =	mysqli_query($connNew,$sqlVa);
if (mysqli_num_rows($Vali) > 0) {
	$CheckFoBillStatus = '0';
	//$dataArray['message'] = ' Please Generate FO Bill.';
	
} else {
	$CheckFoBillStatus = '1';
}
		
		
		foreach($array_roomNo as $roomlist=>$allocation){
			//print_r($allocation['id_mst_room_no_allocation']);	
			
			$room_select .= '<option value="'.$allocation['id_mst_room_no_allocation'].'">'.$allocation['folio_mdoc_no'].' - Room No: '.$allocation['roomNo'].'</option>';
			}
      $id_parent_folio = selectColumn('fo_folio','id_parent_folio'," WHERE `id` = '".$id_folio."'");
?>
<div class="box-header">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 table-responsive">
      <table class="table table-striped table-bordered" cellspacing="0">
        <thead>
          <tr class="info">
            <th>Folio no.</th>
            <th>Fo Bill no.</th>
            <th>Folio Owner</th>
            <th>Bill To Company</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Expected Check Out</th>
            <th>Current Total</th>
            <th>Amount Received</th>
            <th>Balance</th>
            <th>Linked Folio`s</th>
            <?php
              if ($id_parent_folio == 0) {
            ?>
            <th>Folio Status</th>
            <?php } ?>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="folio_no"><?php echo $folio_mdoc_no; ?></td>
            <td id="fo_bill_no"><?php echo $fo_bill_no == '' ? '-' : $fo_bill_no; ?></td>
            <td id="folio_guestname">

              <a href="javascript:void(0);" style="color:black;" id="res_guestAddId"
                onclick="GetEditGuestDetail(<?php echo $id_mst_guest.','. addslashes($id_resevation).','.$id_owner_room.','.$id_mst_guest_order_by_room.','.$id_owner_room.',1,'.$id_folio; ?>);"><?php echo $guestName; ?>
                &nbsp;<i class="fa fa-pencil"></i></a>
            </td>
            <td id="folio_company">
              <?php echo selectColumn(MST_COMPANY,'name'," WHERE `id` = '".$id_bill_to_company."'");?></td>
            <td id="folio_checkin"><?php echo date('d-m-Y',strtotime($row->checkin)); ?></td>

            <td id="folio_checkout_display">
              <?php echo  $checkoutstatus=='2'?date('d-m-Y',strtotime($checkout_date)):'-'; ?></td>
            <td id="folio_checkout"><?php echo  date('d-m-Y',strtotime($row->checkout)); ?></td>
            <td id="folio_currenttotal"><?php echo $CurrentTotal; ?></td>
            <td id="folio_amtreceived"><?php  echo $receipt_amount; ?></td>
            <td id="folio_balance"><?php echo $BalanceAmount; ?></td>
            <td id="sub_folio">
              <?php
              $folio_array = [];
                $id_parent_folio = selectColumn('fo_folio','id_parent_folio'," WHERE `id` = '".$id_folio."'");
                $folio_array[] = selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_folio."'");
                $sub_folio_query = mysqli_query($connNew, "select * from fo_folio where id_parent_folio = '".$id_folio."'");
                while ($folio_result = mysqli_fetch_object($sub_folio_query)) {
                    $folio_array[] = $folio_result->mdoc_no;
                }
                if ($id_parent_folio != 0) {
                  $parent_folio_query = mysqli_query($connNew, "select * from fo_folio where id = '".$id_parent_folio."'");
                  $parent_folio_result = mysqli_fetch_object($parent_folio_query);
                  $folio_array[] = $parent_folio_result->mdoc_no;
                }
                echo implode(',' ,$folio_array);
              ?>
            </td>
            <?php
              if ($id_parent_folio == 0) {
            ?>
            <td id="foliostatus" class="text-center"><select name="foliostatus" id="select_foliostatus"
                onchange="changeFolioStatus(this.value,<?php echo $id_folio; ?>,<?php echo $id_resevation; ?>,<?php echo $id_fo_bill;?>,<?php echo $ReservationAvaliable;?>,<?php echo $BalanceAmount; ?>);">
                <option <?php echo $rowFoBillSelect1;?> value="0">Open</option>
                <option <?php echo $rowFoBillSelect2;?> value="1">Close</option>
              </select></td>
          </tr>
          <?php
              }
          ?>
        </tbody>
      </table>
      <hr style="margin:0;" />
    </div>
    <style>
      <blade media|%20(max-width%3A%20768px)%20%7B%0D>.c-box2 {
        flex: 0 0 100%;
        /* Each button takes full width on small screens */
        max-width: 100%;
      }
      }
    </style> <?php 
									
$sql_fo_receipt	=	"SELECT * FROM `fo_receipt` where  id_fo_folio='".$id_folio."'";
$res_fo_receipt 	= 	mysqli_query($connNew,$sql_fo_receipt);
if(mysqli_num_rows($res_fo_receipt)>0){

	?>
    <div class="box-body table-responsive" style="margin-top: -20px; max-height: 400px">
      <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th>SNo</th>
            <th>Date</th>
            <th>Payment Mode</th>
            <th>Receipt Amount</th>
            <th>-</th>



          </tr>
        </thead>
        <tbody>
          <?php 
	$i=1;
	while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
	
		//pos_purch_details
		
		
				
				?>
          <tr>
            <td><?php echo $i++;?></td>
            <td><?php echo date('d-m-Y',strtotime($row_fo_receipt->doc_date)); ?></td>
			  <td><?php if($row_fo_receipt->is_advance == 1){
              echo $row_fo_receipt->payment_mode.' (Advance)';
            }else{
              echo $row_fo_receipt->payment_mode;
            }
            ?></td>
            <!--<td><?php echo $row_fo_receipt->payment_mode;?></td>-->
            <td><?php echo $row_fo_receipt->amount;?></td>
            <td><a class="btn btn-danger btn-sm"
                style="float: left;padding: 1px 12px;height: 24px;display:flex;align-items:center;"
                href="javascript:void(0);"
                onClick="ajaxAddBillPaymentUnsettledFO(<?php echo $id_folio;?>,0,<?php echo $row_fo_receipt->id;?>);">

                <i class="fas fa-trash-alt"></i> </a>

            </td>

          </tr>
          <?php }?>
        </tbody>
      </table>
    </div>
    <?php  } ?>

    <?php 
	   $ids= "'".encryptor('encrypt',$id_resevation)."'";
				$Type= "'Edit'";
				
				?>
    <hr style="margin:0;" />
	  <div class="" style="background-color:#F5F5F5; margin:10px; padding:10px; border-radius:4px;">
	  <?php 
		  $resAddOn = executeSQl("SELECT * FROM `fo_reservations_addons_details` 
    WHERE id_fo_reservations = '".addslashes($id_resevation)."' AND id_fo_folio_to = '0'");
	if (mysqli_num_rows($resAddOn) > 0) { 
		echo '<h5 style="margin:10px 0; color:#f56616">Other charges to be added</h5>';
		echo '<ul style="list-style-type: disc; padding-left:20px; margin:0;">';
		while ($res = mysqli_fetch_object($resAddOn)) {
			
            $item  = ucwords(strtolower($res->item));
            $price = number_format($res->rate, 2);
            $qty   = $res->qty;

           echo '<li style="margin-bottom:2px; font-size:12px;">'.$item.'@'.$price.'×'.$qty.'</li>';
        }
		echo '</ul>';
	}
		  ?> 
		  
	  </div>
    <?php //include('../paymentForm.php');?>
    <div id="showGenerateButton" <?php echo $buttonHide; ?>>
      <?php /*?><div class="col-md-12 box-cont d-flex">
        <div class=" c-box2"> <a class="btn btn-block o-btn " href="javascript:void(0);" style=""
            onclick="ajaxGenerateBill(<?php echo $row->id_mst_hotels; ?>,<?php echo $id_fo_bill; ?>,<?php echo $id_resevation; ?>,<?php echo $doc_type; ?>,<?php echo $id_room; ?>);">
            Generate Bill </a></div>

        <div class=" c-box2"> <a class="btn btn-block o-btn " id="showSingle" href="javascript:void(0);"> Receipt </a>
        </div>
        <?php if($ReservationAvaliable=='1'){?>
        <div class=" c-box"> <a type="button" value="Close" class="btn c-btn"
            onclick="UpdateCheckout(<?php echo $row->id_mst_hotels; ?>,<?php echo $id_fo_bill; ?>,<?php echo $id_resevation; ?>,<?php echo $doc_type; ?>,<?php echo $id_room; ?>);">
            Checkout </a> </div>

        <?php }?>

        <div class=" c-box2"> <a type="button" value="Close" class="btn btn-block o-btn"
            href="manageFolioPrint.php?idfobill=<?=encryptor(encrypt, $id_fo_bill);?>&id_folio=<?=encryptor(encrypt, $id_folio);?>&id_mst_room_no_allocation=<?=encryptor(encrypt, $id_room);?>&submenu=<?php echo $_REQUEST['submenu']; ?>"
            target="_blank"> Print Folio </a> </div>
        <div class=" c-box2">
          <a target="_blank"
            href="frontbillprint.php?idfobill=<?=encryptor(encrypt, $id_fo_bill);?>&id_mst_room_no_allocation=<?=$id_room;?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"
            class="btn btn-block o-btn">Print Fo Bill</a>&nbsp;&nbsp;</div>

        <div class=" c-box2"> <a class="btn btn-block o-btn " id="showRecheckin" href="javascript:void(0);"> Recheckin
          </a> </div>

        <div class=" c-box2">
          <a href="#" class="btn btn-block o-btn"
            onclick="updateFolioSplit(<?php echo $id_resevation?>,<?php echo $id_mst_guest?>,<?php echo $id_fo_bill;?>);">Split</a>
        </div>

        <div class=" c-box2"> <a class="btn btn-block o-btn " id="showBillToCompany" href="javascript:void(0);"> Bill To
            Company </a> </div>
        <div class=" c-box2"> <a href="#" class="btn btn-block o-btn "
            onclick="PostCharges(<?php echo $ids.','.$Type; ?>);">Charges </a> </div>
        <div class=" c-box2"><a href="#" class="btn btn-block o-btn "
            onclick="ReservationSingleForm(<?php echo $ids.','.$Type; ?>);">Tariff</a>
        </div>
        <div class=" c-box2"> <a href="#" class="btn btn-block o-btn "
            onclick="ChangeFolioGuest(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Guest Details</a> </div>

      </div><?php */?>


      <div class="col-md-12 box-cont d-flex flex-wrap">
        <div class="c-box2 mb-2">
          <a class="btn btn-block o-btn" href="javascript:void(0);"
            onclick="ajaxGenerateBill(<?php echo $row->id_mst_hotels; ?>,<?php echo $id_fo_bill; ?>,<?php echo $id_resevation; ?>,<?php echo $doc_type; ?>,<?php echo $id_room; ?>,<?php echo $id_folio; ?>);">Generate
            Bill</a>
        </div>

        <div class="c-box2 mb-2">
          <a class="btn btn-block o-btn" id="showSingle" href="javascript:void(0);">Receipt</a>

        </div>




        <script>
          function openDrawer() {
            const drawer = document.getElementById('newChkoutDrwer');
            const overlay = document.getElementById('newChkoutDrwerOverlay');

            // Show drawer and overlay
            drawer.classList.remove('newChkoutDrwer-hidden');
            overlay.classList.remove('newChkoutDrwer-hidden');
            drawer.classList.add('newChkoutDrwer-open');
            overlay.classList.add('newChkoutDrwer-active');

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
          }

          function closeDrawer() {
            const drawer = document.getElementById('newChkoutDrwer');
            const overlay = document.getElementById('newChkoutDrwerOverlay');

            // Hide drawer and overlay
            drawer.classList.remove('newChkoutDrwer-open');
            overlay.classList.remove('newChkoutDrwer-active');

            setTimeout(() => {
              drawer.classList.add('newChkoutDrwer-hidden');
              overlay.classList.add('newChkoutDrwer-hidden');

              // Restore body scroll
              document.body.style.overflow = '';
            }, 300); // Matches transition duration
          }
        </script>



        <?php //if ($ReservationAvaliable == '1') { ?>
        <?php
      $reservation_details = mysqli_query($connNew, "select * from fo_reservations_details where id_fo_folio_to = '".$id_folio."' and no_showoff = '0'");
			if (mysqli_num_rows($reservation_details) > 0) {
      ?>
        <div class="c-box mb-2">
          <?php
        $checkout_date = strtotime($row->checkout);
        $today = strtotime($today);
        $is_pre_checkout = ($checkout_date > $today) ? '1' : '0';
      ?>
          <a type="button" value="Close" class="btn c-btn"
            onclick="UpdateCheckout(<?php echo $row->id_mst_hotels; ?>,<?php echo $id_fo_bill; ?>,<?php echo $id_resevation; ?>,<?php echo $doc_type; ?>,<?php echo $id_room; ?>,<?php echo $is_pre_checkout; ?>, <?php echo $check_checkout_date; ?>, <?php echo $CheckFoBillStatus; ?>, <?php echo $BalanceAmount; ?>);">Checkout</a>




        </div>
        <?php } ?>
        <?php 
	
	
	$printFolio = selectColumn('mst_shops','folio_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");  
	$frontbillprint = selectColumn('mst_shops','fobill_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");  
	
	
	
	
        //if($_SESSION['database']=='swanand' || $_SESSION['database']=='demo_swanand'){
        if($printFolio==''){    
           
            $printFolio ='folioformat1.php';
        }else{
            
             $printFolio = selectColumn('mst_shops','folio_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'");  
			 //='taxInvoiceRestaurantBill.php';;
            
            }
		?>
        <div class="c-box2 mb-2">
          <a type="button" value="Close" class="btn btn-block o-btn"
            href="<?=$printFolio;?>?idfobill=<?=encryptor(encrypt, $id_fo_bill);?>&id_folio=<?=encryptor(encrypt, $id_folio);?>&id_mst_room_no_allocation=<?=encryptor(encrypt, $id_room);?>&submenu=<?php echo $_REQUEST['submenu']; ?>"
            target="_blank">Print Folio</a>
        </div>
        <?php 
       // if($_SESSION['database']=='swanand' || $_SESSION['database']=='demo_swanand'){
        if($frontbillprint==''){
          //$frontbillprint='fobillformat1.php';
        }else{
          $frontbillprint = selectColumn('mst_shops','fobill_url'," WHERE `id` = '".addslashes($_SESSION['shop'])."'"); 
        }
        $fo_bill_url = $frontbillprint."?idfobill=".encryptor(encrypt, $id_fo_bill)."&id_folio=".encryptor(encrypt, $id_folio)."&id_mst_room_no_allocation=".$id_room."&submenu=".$_GET['submenu']."&session=".$_GET['session'];
		?>
        <div class="c-box2 mb-2">
          <a href="#"
            onclick="checkIsFoBillGenerated('<?=encryptor(encrypt, $id_fo_bill);?>', '<?=encryptor(encrypt, $id_folio);?>', '<?=encryptor(encrypt, $id_room);?>', '<?=$_GET['submenu'];?>', '<?=$_GET['session'];?>','<?php echo $fo_bill_url ?>','<?php echo $BalanceAmount; ?>','<?php echo $fo_bill_no; ?>','<?php echo $checkoutstatus; ?>')"
            class="btn btn-block o-btn">Print Fo Bill</a>
        </div>

        <?php 
        if($_SESSION['database']=='hip' && $id_parent_folio ==0){
        ?>

        <div class="c-box2 mb-2">
          <a class="btn btn-block o-btn" id="showRecheckin" href="javascript:void(0);">Recheckin</a>
        </div>

        <?php
      }
      ?>

        <?php
        if ($id_parent_folio == 0) {
        ?>
        <div class="c-box2 mb-2">
         
			<a href="#" class="btn btn-block o-btn" id="openCstmSplitModalBtn">Split</a>
        </div>
        <?php
        }
        ?>
<?php
    // Include the modal file. It now only contains HTML.
    include './folioSplitModal.php';
    ?>
        <div class="c-box2 mb-2">
          <a class="btn btn-block o-btn" id="showBillToCompany" href="javascript:void(0);">Bill To Company</a>
        </div>

        <div class="c-box2 mb-2">
          <a href="#" class="btn btn-block o-btn"
            onclick="PostCharges(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Charges</a>
        </div>

        <?php
        if ($id_parent_folio == 0) {
        ?>

        <div class="c-box2 mb-2">
          <a href="#" class="btn btn-block o-btn"
            onclick="ReservationSingleForm(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Tariff</a>
        </div>

        

        <div class="c-box2 mb-2">
          <a href="#" class="btn btn-block o-btn"
            onclick="ChangeFolioGuest(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Guest Details</a>
        </div>
        
        <div class="c-box2 mb-2">
          <a href="#" class="btn btn-block o-btn"
            onclick="AddRemarks(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Remarks</a>
        </div>
        <?php
        }
        ?>

        <div class="c-box2 mb-2">
          <a href="#" class="btn btn-block o-btn"
            onclick="FolioTransfer(<?php echo $ids.','.$Type.','.$id_folio; ?>);">Folio Transfer</a>
        </div>

      </div>

    </div>
  </div>
  <?php 
$purch_id=$id_fo_bill;


$grand_total_amount=$BalanceAmount;
?>
  <div class="targetDivShowRecheckin">

    <div class="box box-success" style="border:1px solid green;margin-bottom:39px;margin-top: 10px;">
      <div class="box-header with-border bg-color-success">
        <h3 class="box-title">Recheckin</h3>
      </div>
      <div class="box-body">
        <input type="hidden" name="id_reservation" id="id_reservation" value="<?php echo $id_resevation; ?>" />
        <input type="hidden" name="re_id_mst_hotels" id="re_id_mst_hotels" value="<?php echo $row->id_mst_hotels; ?>" />
        <div class="row">
          <div class="form-group col-sm-3">
            <label for="id_contacts">Checkout Date</label>
            <div class="input-group" id="showbookedby">
              <input type="text" class="form-control  pickerFutureDate" id="re_reservation_date"
                name="re_reservation_date" data-parsley-required value="<?php echo date('d-m-Y'); ?>"
                data-parsley-errors-container="#re_reservation_dateError" automcomplete="off" style="width:200px;">
              <span id="contactError"></span>

            </div>
          </div>
          <div class="form-group col-sm-3">
            <label for="TariffPerRoomperNights">Tariff Per Room per Nights</label>
            <div class="input-group" id="showbookedby">
              <input type="text" class="form-control  " id="TariffPerRoomperNights" name="TariffPerRoomperNights"
                data-parsley-required value="<?php echo $TariffPerRoomperNights; ?>"
                data-parsley-errors-container="#TariffPerRoomperNightsError" automcomplete="off" style="width:200px;">
              <span id="TariffPerRoomperNightsError"></span>

            </div>
          </div>
          <div class="form-group col-sm-3">
            <label for="id_contacts">Tax Per Room per Nights</label>
            <div class="input-group" id="showbookedby">
              <input type="text" class="form-control " id="TaxPerRoomperNights" name="TaxPerRoomperNights"
                data-parsley-required value="<?php echo $TaxPerRoomperNights;  ?>"
                data-parsley-errors-container="#TaxPerRoomperNightsError" automcomplete="off" style="width:200px;">
              <span id="TaxPerRoomperNightsError"></span>

            </div>
          </div>

          <div class="col-md- col-sm-12 col-xs-12">
            <div class=" c-box2"> <a class="btn btn-block o-btn " id="f" href="javascript:void(0);"
                onclick="recheckin();"> Save </a> </div>
          </div>
        </div>
      </div>
    </div>

  </div>


  <div class="targetDivShowBillToCompany" style="margin-top: 10px;">

    <div class="box box-success" style="border:1px solid green;margin-bottom:39px;">
      <div class="box-header with-border bg-color-success">
        <h3 class="box-title">Bill To Company</h3>

        <div class="box-tools pull-right">

          <button type="button" class="btn btn-box-tool" data-widget="remove" onclick="CloseBillToCompany();"
            style="color:#fff;"><i class="fa fa-times"></i></button>
        </div>
      </div>

      <div class="box-body">
        <input type="hidden" name="id_reservation" id="id_reservation" value="<?php echo $id_resevation; ?>" />
        <input type="hidden" name="re_id_mst_hotels" id="re_id_mst_hotels" value="<?php echo $row->id_mst_hotels; ?>" />

        <div class="row">


          <div class=" form-group col-md-4 ">
            <label for="id_contacts">Company</label>


            <select class="form-control first-input select2" style="width:100% !important;" name="id_bill_to_company"
              id="id_bill_to_company">
              <option value="0">Select Company </option>
              <?php  $resCat = selectSql(MST_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_bill_to_company == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
            </select>
            <span id="contactError"></span>



          </div>
          <div class=" form-group col-md-1 ">
            <label for="id_contacts">&nbsp;</label>
            <div class="input-group-addon" onclick="AddnewCompany();" style="width:10px;"> <i class="fa fa-plus"></i>
            </div>
          </div>
          <div class="col-md- col-sm-12 col-xs-12">
            <div class="box-footer">
              <a class="btn btn-block o-btn " style="    width: 5%;
    float: left;
    margin-right: 9px;" id="f" href="javascript:void(0);" onclick="saveBillToCompany(<?php echo $id_folio; ?>);"> Save
              </a>
              <a type="button" value="Close" class="btn c-btn" onclick="CloseBillToCompany();"> <i
                  class="far fa-window-close"></i> Close
              </a>
              <br><br>





            </div>
          </div>

        </div>
      </div>
    </div>

  </div>

  <div class="targetDivShowAddRemarks" style="margin-top:10px;">
    <div class="box box-success" style="border:1px solid green;margin-bottom:39px;">
      <div class="box-header with-border bg-color-success">
        <h3 class="box-title">Remarks</h3>
        <div class="box-tools pull-right">
          <button type="button" class="btn btn-box-tool" data-widget="remove" onclick="CloseAddRemarks();"
            style="color:#fff;"><i class="fa fa-times"></i></button>
        </div>
      </div>
      <div class="box-body">

        <input type="hidden" name="id_reservation" id="id_reservation" value="<?php echo $id_resevation; ?>" />
        <input type="hidden" name="re_id_mst_hotels" id="re_id_mst_hotels" value="<?php echo $row->id_mst_hotels; ?>" />


        <?php 
	//  include('ajaxAddRemarks.php');?>


        <div class="row">






          <div class="col-md- col-sm-12 col-xs-12">
            <div class="box-footer"> <a class="btn btn-block o-btn " style="    width: 5%;
    float: left;
    margin-right: 9px;" id="f" href="javascript:void(0);" onclick="saveAddRemarks(<?php echo $id_folio; ?>);"> Save
              </a>
              <a type="button" value="Close" class="btn c-btn" onclick="CloseAddRemarks();"> <i
                  class="far fa-window-close"></i> Close </a> <br>

              <br>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="div<?php echo $purch_id;?>" class="targetDivShow">
    <form name="listingForm_<?php echo $purch_id;?>" id="listingForm_<?php echo $purch_id;?>" action="" method='POST'
      data-parsley-validate>
      <input type="hidden" value="" name="act" />
      <input type="hidden" name="get_purch_id" id="get_purch_id" value="<?php echo $purch_id;?>" />
      <input type="hidden" name="id_invoice_detail" value="<?php echo $id_resevation.'_'.$id_fo_bill.'_'.$id_room; ?>"
        <div class="box-body">
      <div class="card text-dark bg-light">
        <div class="row">
          <input type="hidden" class="form-control" readonly placeholder="mdock_no" id="mdock_no" name="mdock_no"
            value="<?php echo $purch_row->mdoc_no;?>">
        </div>
        <div class="row">
          <div class="form-group col-xs-12 col-md-12 col-sm-12">
            <div class="box-body" style=" padding-bottom:0px !important;">
              <div class="card text-dark bg-light">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group" style="margin-bottom: 1px;">
                      <div class="box-body table-responsive"
                        style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
                        <table id="myTableOrder1" class="table dataTable no-footer table-responsive" cellspacing="0"
                          style="font-size:14px;padding: 0px 0px;border: 1px solid #3c8dbc;">
                          <thead style="font-size:10px;padding: 0px 0px;">
                            <tr
                              style="background-color: #3c8dbc;color: #fff;font-variant-caps: all-petite-caps;font-size: 14px;">
                              <th></th>
                              <th style="width:350px;padding: 5px 9px;"> Payment Mode.&nbsp;</th>
                              <th style="width:100px;padding: 5px 9px;">Amount</th>
                              <th style=" padding: 5px 9px;">Remarks</th>
                              <th style="width:100px;padding: 5px 9px;">Tips</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox" <?php  if($amount[$purch_id][1]>0){ echo 'checked="checked"';} ?>
                                  class="flat-red i-checks checkboxpayamount_1_<?php echo $purch_id;?>"
                                  name="checkboxpayamount" id="checkboxpayamount"
                                  value="<?php echo $amount[$purch_id][1].'_1_'.$grand_total_amount.'_'.$purch_id; ?>" />
                              </td>
                              <td>
                                <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span">
                                    <img src="../images/cashpay.png" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">CASH</span> </div>
                                  <!-- /.info-box-content -->
                                </div>

                                <!-- /.info-box -->
                              </td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input billingamount_<?php echo $purch_id;?>"
                                  name="payamount[<?php echo $purch_id;?>][CASH][]"
                                  id="payamount_1_<?php echo $purch_id;?>"
                                  onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,1);"
                                  value="<?php echo $amount[$purch_id][1]?$amount[$purch_id][1]:0; ?>"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" /></td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" placeholder="Remarks"
                                  name="remarks[<?php echo $purch_id;?>][CASH][]" id="remarks_1_<?php echo $purch_id;?>"
                                  value="<?php echo $remarks[$purch_id][1]; ?>" style="float: left;" /></td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][1]>0){ $amount[$purch_id][1];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" name="tips[<?php echo $purch_id;?>][CASH][]"
                                  id="tips_1_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][1]; ?>"
                                  style="float: left;" /></td>
                            </tr>
                            <!----------------------CARD PAYMENT------------------------------------>
                            <?php $cardStarCount	=	1;?>
                            <?php 
								
								 ?>
                            <tr style="border:1px solid red;background-color:#fff;"
                              id="grid<?php echo $gridNo;?>_<?php echo $purch_id;?>">
                              <td style="width: 2.5%;"><input type="checkbox"
                                  <?php  if($CardAmount>0){ echo 'checked="checked"';} ?>
                                  class="flat-red i-checks checkboxpayamount_2_<?php echo $purch_id;?>"
                                  name="checkboxpayamount" id="checkboxpayamount"
                                  value="<?php echo $amount[$purch_id][2].'_2_'.$grand_total_amount.'_'.$purch_id; ?>" />
                              </td>
                              <td>
                                <div class="info-box"
                                  style="height:90px !important;min-height: 90px !important;margin-bottom: 0px !important;">
                                  <span class="info-box-icon bg-aqua"
                                    style="height:90px !important;line-height: 90px !important;">


                                    <img src="../images/credit_cards_card-512.png" style="cursor:pointer;"
                                      title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content" style="width: 83%;height: 28px;"> <span
                                      class="info-box-text" style="width:87%;float:left;">CARD </span>
                                    <button class="pull-left btn btn-success btn-xs" type="button"
                                      onclick="addNewGrid(<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);"
                                      style="margin: 0px;float:right;"><i class="fa fa-plus-circle"></i></button>
                                  </div>
                                  <!-- /.info-box-content -->




                                  <div class="info-box"
                                    style="height:60px !important;min-height: 60px !important;margin-bottom: 0px !important;">
                                    <span class="info-box-number">

                                      <div class="box-body"
                                        style="width: 16%;float: left;padding: 0px !important;height: 60px;margin-left: 16px;">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                          <div style="margin-left: 15px;">
                                            <label for="name" class="paymentlable">
                                              <input type="radio" class="flat-red"
                                                <?php if($id_cardtype == '1'){echo "checked";} ?> value="1"
                                                name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]"
                                                id="cardtype" />
                                            </label>
                                          </div>
                                          <img class="flagimgs first"
                                            src="<?php echo $SITE_URL; ?>/plugins/dist/img/credit/visa.png" alt="Visa">
                                        </div>
                                      </div>
                                     <!-- <div class="box-body"
                                        style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                          <div style="margin-left: 15px;">
                                            <label for="name" class="paymentlable">
                                              <input type="radio" <?php if($id_cardtype == '2'){echo "checked";} ?>
                                                class="flat-red" value="2"
                                                name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]"
                                                id="cardtype" />
                                            </label>
                                          </div>
                                          <img src="../images/upi.png" style="cursor:pointer;" title="upi" />
                                        </div>
                                      </div>-->
                                      <div class="box-body"
                                        style="width: 16%;float: left;padding: 0px !important; height: 60px;">
                                        <div class="form-group" style="margin-bottom: 0px;">
                                          <div style="margin-left: 15px;">
                                            <label for="name" class="paymentlable">
                                              <input type="radio" class="flat-red"
                                                <?php if($id_cardtype == '3'){echo "checked";} ?> value="3"
                                                name="cardtype[<?php echo $purch_id;?>][CARDTYPE][][<?php echo $gridNo;?>]"
                                                id="cardtype" />
                                            </label>
                                          </div>
                                          <img src="../images/neft.png" style="cursor:pointer;" title="upi" />
                                        </div>
                                      </div>





                                    </span> </div>
                                </div>
                              </td>



                              <td style="width: 12.5%;"><input type="text"
                                  <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input billingamount_<?php echo $purch_id;?>"
                                  name="payamount[<?php echo $purch_id;?>][CARD][]"
                                  id="payamount_2_<?php echo $purch_id;?>"
                                  onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,2);"
                                  value="<?php echo $CardAmount?$CardAmount:0; ?>" style="float: left;"
                                  data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                              <td style="width: 35.5%;">
                                <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">


                                  <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                    <select class="form-control first-input select2" style="width:100% !important;"
                                      name="id_bank[<?php echo $purch_id;?>][BANK][]"
                                      id="id_bank_2_<?php echo $purch_id;?>"
                                      <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>>
                                      <option value="0">--- Select Bank --- </option>
                                      <!--select bank-->
                                      <?php  $resCat = selectSql(TBL_CHARGES," where status='1' and charges_account='8' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_charges_master == $resultCat->id){
														$selected = '';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                    </select>
                                  </div>



                                </div>
                                <input type="text"
                                  <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" placeholder="Remarks"
                                  name="remarks[<?php echo $purch_id;?>][CARD][]" id="remarks_2_<?php echo $purch_id;?>"
                                  value="<?php echo $cardRemark; ?>" style="float: left;" />
                              </td>



                              <td>
                                <div class="form-group" style="width:100% !important;margin-bottom: 5px!important;">
                                  <div class="input-group" style="width:100% !important;">
                                    <input type="text"
                                      <?php  if($amount[$purch_id][2]>0){ $amount[$purch_id][2];}else {echo 'disabled="disabled"';} ?>
                                      class="form-control first-input" name="tips[<?php echo $purch_id;?>][CARD][]"
                                      id="tips_2_<?php echo $purch_id;?>" value="<?php echo $CardTips; ?>"
                                      style="float: left;" />
                                  </div>
                                </div>
                                <?php if($gridNo>1){?> <a class="btn btn-danger btn-sm" href="javascript:void(0);"
                                  onclick="removeGrid(<?php echo $gridNo;?>,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>);">
                                  <i class="fa fa-trash-o fa-lg"></i> </a><?php } ?>
                              </td>
                              </td>

                            </tr>






                            <!----------------------ONLINE TRANSFER ------------------------------------>
<tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox" <?php  if($amount[$purch_id][6]>0){ echo 'checked';} ?>
                                  class="flat-red i-checks checkboxpayamount_6_<?php echo $purch_id;?>"
                                  name="checkboxpayamount" id="checkboxpayamount"
                                  value="<?php echo $amount[$purch_id][6].'_6_'.$grand_total_amount.'_'.$purch_id; ?>" />
                      </div>
                      </td>
<td>
                        <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span"> <img
                              src="../images/gift.jpg" style="cursor:pointer;" title=" Bill Payment " /> </span>
                          <div class="info-box-content"> <span class="info-box-text">UPI</span> 
							 <img src="../images/upi.png" style="cursor:pointer;margin-left:60px;" title="upi"  /></div>
                          <!-- /.info-box-content -->
                        </div>
                      </td>
                      <td><input type="text"
                          <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>
                          class="form-control first-input billingamount_<?php echo $purch_id;?>"
                          name="payamount[<?php echo $purch_id;?>][UPI][]"
                          id="payamount_6_<?php echo $purch_id;?>"
                          onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,6);"
                          value="<?php echo $amount[$purch_id][6]?$amount[$purch_id][6]:0; ?>" style="float: left;"
                          data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                          
                               <td><input type="text"
                          <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>
                          class="form-control first-input" placeholder="Remarks"
                          name="remarks[<?php echo $purch_id;?>][UPI][]" id="remarks_6_<?php echo $purch_id;?>"
                          value="<?php echo $remarks[$purch_id][6]; ?>" style="float: left;" /></td>
                      <td><input type="text"
                          <?php  if($amount[$purch_id][6]>0){ $amount[$purch_id][6];}else {echo 'disabled="disabled"';} ?>
                          class="form-control first-input" name="tips[<?php echo $purch_id;?>][UPI][]"
                          id="tips_6_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][6]; ?>"
                          style="float: left;" /></td>
                      </tr>


                            <!------------------COMPANY--------START------------------------------>
                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox" <?php  if($amount[$purch_id][4]>0){ echo 'checked';} ?>
                                  class="flat-red i-checks checkboxpayamount_4_<?php echo $purch_id;?>"
                                  name="checkboxpayamount" id="checkboxpayamount"
                                  value="<?php echo $amount[$purch_id][4].'_4_'.$grand_total_amount.'_'.$purch_id; ?>" />
                              </td>
                              <td>
                                <div class="info-box"
                                  style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;">
                                  <span class="info-box-icon bg-aqua"
                                    style="height:80px !important;line-height: 70px !important;"> <img
                                      src="../images/company.png" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">COMPANY</span> </div>
                                  <!-- /.info-box-content -->
                                </div>
                              </td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input billingamount_<?php echo $purch_id;?>"
                                  name="payamount[<?php echo $purch_id;?>][COMPANY][]"
                                  id="payamount_4_<?php echo $purch_id;?>"
                                  onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,4);"
                                  value="<?php echo  $amount[$purch_id][4]?$amount[$purch_id][4]:0;  ?>"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" /></td>
                              <td>
                                <div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                                  <div class="input-group" style="width:100% !important;">
                                    <select class="form-control first-input select2" style="width:100% !important;"
                                      name="id_company[<?php echo $purch_id;?>][COMPANY][]"
                                      id="id_company_4_<?php echo $purch_id;?>"
                                      <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>>
                                      <option value="0">Select Company </option>
                                      <?php  $resCat = selectSql(MST_COMPANY," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' and name !=' ' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($id_company[$purch_id][4] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }?>
                                    </select>
                                  </div>
                                </div>
                                <input type="text"
                                  <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" placeholder="Remarks"
                                  name="remarks[<?php echo $purch_id;?>][COMPANY][]"
                                  id="remarks_4_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][4]; ?>"
                                  style="float: left;" />
                              </td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][4]>0){ $amount[$purch_id][4];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" name="tips[<?php echo $purch_id;?>][COMPANY][]"
                                  id="tips_4_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][4]; ?>"
                                  style="float: left;" /></td>
                            </tr>
                            <!------------------COMPANY---END----------------------------------->

                            <tr id="trbgcolor">
                              <td style="width: 2.5%;">
                                <input type="checkbox" <?php  if($amount[$purch_id][5]>0){ echo 'checked';} ?>
                                  class="flat-red i-checks checkboxpayamount_5_<?php echo $purch_id;?>"
                                  name="checkboxpayamount" id="checkboxpayamount"
                                  value="<?php echo $amount[$purch_id][5].'_5_'.$grand_total_amount.'_'.$purch_id; ?>" />
                              </td>
                              <td>
                                <div class="info-box paymentmode"> <span class="info-box-icon bg-aqua paymode-span">
                                    <img src="../images/cheq.jpg" style="cursor:pointer;" title=" Bill Payment " />
                                  </span>
                                  <div class="info-box-content"> <span class="info-box-text">CHEQUE</span> </div>
                                  <!-- /.info-box-content -->
                                </div>
                              </td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input billingamount_<?php echo $purch_id;?>"
                                  name="payamount[<?php echo $purch_id;?>][CHEQUE][]"
                                  id="payamount_5_<?php echo $purch_id;?>"
                                  onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,5);"
                                  value="<?php echo $amount[$purch_id][5]?$amount[$purch_id][5]:0; ?>"
                                  style="float: left;" data-parsley-required
                                  data-parsley-errors-container="#payamountError" /></td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" placeholder="Remarks"
                                  name="remarks[<?php echo $purch_id;?>][CHEQUE][]"
                                  id="remarks_5_<?php echo $purch_id;?>" value="<?php echo $remarks[$purch_id][5]; ?>"
                                  style="float: left;" /></td>
                              <td><input type="text"
                                  <?php  if($amount[$purch_id][5]>0){ $amount[$purch_id][5];}else {echo 'disabled="disabled"';} ?>
                                  class="form-control first-input" name="tips[<?php echo $purch_id;?>][CHEQUE][]"
                                  id="tips_5_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][5]; ?>"
                                  style="float: left;" /></td>
                            </tr>
                            
                      
                 


                      <!--Room TO Settle Start------------------>
                      <tr id="trbgcolor">
                        <td style="width: 2.5%;">
                          <input type="checkbox" <?php  if($amount[$purch_id][7]>0){ echo 'checked';} ?>
                            class="flat-red i-checks checkboxpayamount_7_<?php echo $purch_id;?>"
                            name="checkboxpayamount" id="checkboxpayamount"
                            value="<?php echo $amount[$purch_id][7].'_7_'.$grand_total_amount.'_'.$purch_id; ?>" />
                        </td>
                        <td>
                          <div class="info-box"
                            style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;">
                            <span class="info-box-icon bg-aqua"
                              style="height:80px !important;line-height: 70px !important;"> <img
                                src="../images/company.png" style="cursor:pointer;" title=" Bill Payment " /> </span>
                            <div class="info-box-content"> <span class="info-box-text">ROOM TO </span> </div>
                            <!-- /.info-box-content -->
                          </div>
                        </td>
                        <td><input type="text"
                            <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input billingamount_<?php echo $purch_id;?>"
                            name="payamount[<?php echo $purch_id;?>][ROOMTO][]" id="payamount_7_<?php echo $purch_id;?>"
                            onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,7);"
                            value="<?php echo  $amount[$purch_id][7]?$amount[$purch_id][7]:0;  ?>" style="float: left;"
                            data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                        <td>
                          <div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                            <div class="input-group" style="width:100% !important;">
                              <select class="form-control first-input select2" style="width:100% !important;"
                                name="id_fo_bill[<?php echo $purch_id;?>][ROOMTO][]"
                                id="id_fo_bill_7_<?php echo $purch_id;?>"
                                <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>>
                                <option value="0">Select Room </option>
                                <?php  $resCat = selectSql(FO_BILL," where status='1' and id_mst_shops='".addslashes($_SESSION['shop'])."'  ",' ');
														  
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
			
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($resultCat->id_reservations)."'   group by id_mst_room_no_allocation ");

			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			//$id_mst_guest=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest'," WHERE `id_fo_reservations` = '".addslashes($resultCat->id_reservations)."' and DATE(dated) = '".date('Y-m-d')."'");
			$firstName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
      $lastName	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
      $guestName = $firstName . " " . $lastName;
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($resultCat->id_reservations)."' ");					
													
													
if($id_fo_bill[$purch_id][7] == $resultCat->id){
	$selected = 'selected="selected"';
}else{
	$selected = '';
}
echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">Room No: '.$roomNumber.' Guest Name: '.$guestName.'</option>';
												}
											  }
											  }?>
                              </select>
                            </div>
                          </div>
                          <input type="text"
                            <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input" placeholder="Remarks"
                            name="remarks[<?php echo $purch_id;?>][ROOMTO][]" id="remarks_7_<?php echo $purch_id;?>"
                            value="<?php echo $remarks[$purch_id][7]; ?>" style="float: left;" />
                        </td>
                        <td><input type="text"
                            <?php  if($amount[$purch_id][7]>0){ $amount[$purch_id][7];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input" name="tips[<?php echo $purch_id;?>][ROOMTO][]"
                            id="tips_7_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][7]; ?>"
                            style="float: left;" /></td>
                      </tr>




                      <!--Room TO Settle END------------------>

                      <!-----------------------BILL ON HOLD START----------------- ------>



                      <tr id="trbgcolor">
                        <td style="width: 2.5%;">
                          <input type="checkbox" <?php  if($amount[$purch_id][8]>0){ echo 'checked';} ?>
                            class="flat-red i-checks checkboxpayamount_8_<?php echo $purch_id;?>"
                            name="checkboxpayamount" id="checkboxpayamount"
                            value="<?php echo $amount[$purch_id][8].'_8_'.$grand_total_amount.'_'.$purch_id; ?>" />
                        </td>
                        <td>
                          <div class="info-box"
                            style="height:80px !important;min-height: 80px !important;margin-bottom: 0px !important;">
                            <span class="info-box-icon bg-aqua"
                              style="height:80px !important;line-height: 70px !important;"> <img
                                src="../images/hold.png" style="cursor:pointer;padding:16px;" title=" Bill Payment " />
                            </span>
                            <div class="info-box-content"> <span class="info-box-text">BIll ON HOLD </span> </div>
                            <!-- /.info-box-content -->
                          </div>
                        </td>
                        <td><input type="text"
                            <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input billingamount_<?php echo $purch_id;?>"
                            name="payamount[<?php echo $purch_id;?>][BIllONHOLD][]"
                            id="payamount_8_<?php echo $purch_id;?>"
                            onKeyUp="getpayamount(this.value,<?php echo $purch_id;?>,<?php echo $grand_total_amount;?>,8);"
                            value="<?php echo  $amount[$purch_id][8]?$amount[$purch_id][8]:0;  ?>" style="float: left;"
                            data-parsley-required data-parsley-errors-container="#payamountError" /></td>
                        <td>
                          <div class="form-group" style="width:100% !important; margin-bottom:5px !important;">
                            <div class="input-group" style="width:100% !important;">

                            </div>
                          </div>
                          <input type="text"
                            <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input" placeholder="Remarks"
                            name="remarks[<?php echo $purch_id;?>][BIllONHOLD][]" id="remarks_8_<?php echo $purch_id;?>"
                            value="<?php echo $remarks[$purch_id][8]; ?>" style="float: left;" />
                        </td>
                        <td><input type="text"
                            <?php  if($amount[$purch_id][8]>0){ $amount[$purch_id][8];}else {echo 'disabled="disabled"';} ?>
                            class="form-control first-input" name="tips[<?php echo $purch_id;?>][BIllONHOLD][]"
                            id="tips_8_<?php echo $purch_id;?>" value="<?php echo $tips[$purch_id][8]; ?>"
                            style="float: left;" /></td>
                      </tr>




                      <!-----------------------BILL ON HOLD END----------------- ------>


                      </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card text-dark bg-light" style="background-color:#3c8dbc;">
                <div class="row">
                  <div class="form-group col-xs-12 col-md-2 col-sm-2">
                    <label for="name" style="margin-left:5px;color:#fff;">Date</label>
                    <div class="input-group" style="margin-left:5px;">
                      <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                      <input type="text" class="form-control pickerdateretwodays" placeholder="sreEnter PO Date"
                        id="po_date1" name="po_date1"
                        value="<?php echo $edit_doc_date!=''?date('d-m-Y',strtotime($edit_doc_date)):date('d-m-Y');?>">
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-2 col-sm-2">
                    <label for="name" style="color:#fff;">Bill Amount</label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
                      <input type="text" class="form-control" placeholder="Total Amount" id="grand_total_amount"
                        name="grand_total_amount" value="<?php echo $grand_total_amount; ?>" readonly>
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-2 col-sm-2">
                    <label for="name" style="color:#fff;">Paid Amount</label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                      <input type="text" class="form-control" disabled placeholder="Total Pay Amount"
                        id="pay_total_amount_<?php echo $purch_id;?>" name="pay_total_amount_<?php echo $purch_id;?>"
                        value="<?php echo $TotalPaidAmount;?>" style="text-align:right;" data-parsley-required
                        data-parsley-errors-container="#pay_total_amountError">
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-2 col-sm-2">
                    <label for="name" style="color:#fff;">Balance</label>
                    <div class="input-group">
                      <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
                      <input type="text" class="form-control" disabled placeholder="Balance Amount"
                        id="balance_amount_<?php echo $purch_id;?>" name="balance_amount_<?php echo $purch_id;?>"
                        value="<?php echo round($balance_amount,2); ?>" style="text-align:right;">
                    </div>
                  </div>
                  <div class="form-group col-xs-12 col-md-4 col-sm-4">
                    <div class="input-group" style="margin-top:24px;">
                      <input type='button' name="saveForm" id="saveForm" value='Save' class="btn btn-success"
                        onClick="ajaxAddBillPaymentFO(<?php echo $purch_id;?>,1,<?php echo $id_folio; ?>);">
                      &nbsp;
                      <!--  <input type='button' name="cancelled" id="cancelled" value='Cancel Bill' class="btn btn-danger"  onClick="ajaxcancel(<?php echo $purch_id;?>);" >-->
                      &nbsp;
                      <?php  if($row->payment_status=='Settled' || $row->payment_status=='Partial'){
								?>
                      <input type='button' name="saveunsettled" id="saveunsettled" value='Unsettle'
                        class="btn btn-success"
                        onClick="ajaxAddBillPaymentFO(<?php echo $purch_id;?>,0,,<?php echo $id_folio; ?>);">

                      <?php }	?>

                    </div>
                  </div>
                </div>
              </div>
              <!-- Total Amount Section -->

            </div>
          </div>
        </div>
        <div> </div>
      </div>
  </div>
</div>
</form>
</div>


















<!-- /.box-header -->
<div class="box-body table-responsive" style="margin-top: -20px; max-height: 400px">
  <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%">
    <thead>
      <tr>
        <th style="width:40px;">Split<input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' /> </th>
        <th>Room Type/No</th>
        <th>Reference No#</th>
        <th>Reference Date</th>
        <th>Source</th>
        <th>Tariff</th>
        <th>Tax</th>
        <th>Total</th>

        <?php /*?> <th>SGST</th>
        <th>CGST</th>
        <th>IGST</th>
        <th>DR</th>
        <th>CR</th>
        <th><input type="checkbox" name="DId" id="did" /></th>
        <th>Action</th><?php */?>
      </tr>
    </thead>
    <tbody>
      <?php 
									
/*$sql	=	"SELECT * FROM ".FO_RESERVATIONS." id='".addslashes($id_resevation)."'";
$res 	= 	mysqli_query($connNew,$sql);
	
	$row = mysqli_fetch_object($res);*/ 
	
		//pos_purch_details
	//foreach($folioArray as $Array1526){
		
	
	//}
		foreach($folioArray as $RoomName=>$Array156){
			$counter = 1;
			foreach($Array156 as $rowid=>$Array2){
				
				
				?>
      <tr>
        <th><input type="checkbox" name="folio_split[]" id="folio_split"
            value="<?php echo $Array2['source_split_id'].'-'.$Array2['source_split_table'];?>" />
          <?php //echo $counter++;?></th>
        <td><?php echo $Array2['RoomType'];?></td>
        <td><?php echo $Array2['InvoiceNo'];?></td>
        <td><?php echo date('d-m-Y',strtotime($Array2['dated'])); ?></td>
        <td><?php echo $Array2['source'];?></td>
        <td><?php echo $Array2['tariff'];?></td>
        <td><?php echo $Array2['tax'];?></td>
        <th><?php echo $Array2['Total'];?></th>

        <?php /*?><th>-</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
        <th><input type="checkbox" name="DId" id="did" /></th>
        <th>Action</th><?php */?>
      </tr>
      <?php
			}
			}
		/*echo '<pre>';
print_r($folioArray);		


if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
									print_r($rowOrderDetail);
									$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
									$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									?>
      <tr>
        <th><?php echo $RoomName.'/'.$roomNo;?></th>
        <th><?php echo date('d-m-Y',strtotime($rowOrderDetail->dated)); ?></th>
        <th>Invoice#</th>
        <th>Tariff</th>
        <th><?php echo $rowOrderDetail->tariff_price_per_day_per_room;?></th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
        <th>-</th>
        <th><input type="checkbox" name="DId" id="did" /></th>
        <th>Action</th>
      </tr>


      <?php } 
		} 
	?> */
      ?>
    </tbody>
  </table>
</div>



<div class="row">
  <div class="col-md-12">
    <!--cancel pop start-->
    <div id="cancelpop" class="well p-4" style="margin:0 15px;display: none;">

      <div id="fo_paymentForm"></div>



    </div>
    <!--cancel pop ends-->
  </div>

</div>















<!-- /.box-body -->
<?php /*?><div class="card text-dark bg-light">
  <hr>
  <div class="bg-primary text-center ">
    <h5 style="padding: 5px;">Invoice Summary</h5>
  </div>
  <hr>
</div><?php */?>
<?php /*?><div class="box-body table-responsive">
  <table class="table table-bordered table-hover table-striped">
    <thead>
      <tr class="info">
        <th>Invoice No</th>
        <th>Amount</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>invo001</td>
        <td>70800</td>
      </tr>
      <tr>
        <td>invo001</td>
        <td>35400</td>
      </tr>
      <tr>
        <td>R1</td>
        <td>3000</td>
      </tr>
    </tbody>
  </table>

</div><?php */?>
<?php } ?>

<script>
  /*
 $(function() {
	
  $('#showSingle').click(function() { 
    $('.targetDivShow').not('#div' + $(this).attr('target')).hide();
    $('#div' + $(this).attr('target')).toggle();
  });
}); */

  $(".targetDivShow").hide();
  $("#showSingle").click(function () {
    $(".targetDivShowBillToCompany").hide();
    $(".targetDivShowRecheckin").hide();
    $(".targetDivShowAddRemarks").hide();
    $(".targetDivShow").toggle();
  });

  function CloseBillToCompany() {
    $(".targetDivShowBillToCompany").hide();
    $(".targetDivShowRecheckin").hide();
  }




  $(".targetDivShowRecheckin").hide();
  $("#showRecheckin").click(function () {
    $(".targetDivShowBillToCompany").hide();
    $(".targetDivShow").hide();
    $(".targetDivShowAddRemarks").hide();
    $(".targetDivShowRecheckin").toggle();
  });


  $(".targetDivShowBillToCompany").hide();
  $("#showBillToCompany").click(function () {
    $(".targetDivShowRecheckin").hide();
    $(".targetDivShow").hide();
    $(".targetDivShowAddRemarks").hide();
    $(".targetDivShowBillToCompany").toggle();
  });



  //Add Remarks	===================================  
  $(".targetDivShowAddRemarks").hide();
  $("#showAddRemarks").click(function () {
    $(".targetDivShowRecheckin").hide();
    $(".targetDivShow").hide();
    $(".targetDivShowBillToCompany").hide();
    $(".targetDivShowAddRemarks").toggle();
  });



  function CloseAddRemarks() {
    $(".targetDivShowAddRemarks").hide();

  }
</script>



<script src="<?php echo $SITE_URL; ?>/js/custom-admin.js"></script>







<script>
  $('input').on('ifChanged', function (data) {
    //alert(data.type + ' callback');
    $('#input-1, #input-3').iCheck('check');


    var result = $(this).val();
    resulthtml = result.split('_');
    var linepay = resulthtml[0];
    var get_purch_id = resulthtml[3];
    var grand_total_amount = resulthtml[2];
    var type = resulthtml[1];
    var isChecked = data.currentTarget.checked;


    var sum = 0;
    $(".billingamount_" + get_purch_id).each(function () {
      sum += +$(this).val();
      //var cash = $(this).val();
    });


    $("#payamount_" + type + "_" + get_purch_id).attr('disabled', 'disabled');

    if (isChecked == true) {
      //alert(isChecked);

      $("#payamount_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#tips_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#remarks_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#id_company_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#id_fo_bill_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#cardnumber_" + type + "_" + get_purch_id).removeAttr('disabled');
      $("#id_bank_" + type + "_" + get_purch_id).removeAttr('disabled')
      var pay_total_amount = grand_total_amount - sum;
      //alert(pay_total_amount);
      //alert(sum);

      $("#payamount_" + type + "_" + get_purch_id).val(pay_total_amount);

      var balance_amount = grand_total_amount + sum;
      $('input[name="pay_total_amount_' + get_purch_id + '"').val(grand_total_amount);
      $('input[name="balance_amount_' + get_purch_id + '"').val(0);

      var checkboxpayamount = pay_total_amount + '_' + type + '_' + grand_total_amount + '_' + get_purch_id;
      $('.checkboxpayamount_' + type + '_' + get_purch_id).val(checkboxpayamount);

    } else {
      //alert(isChecked);
      //alert(grand_total_amount);
      //alert(sum);
      //alert(linepay);
      //alert("id_bank_"+type+"_"+get_purch_id);
      $("#payamount_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $("#tips_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $("#remarks_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $("#id_company_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $("#cardnumber_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $("#id_fo_bill_" + type + "_" + get_purch_id).attr('disabled', 'disabled');



      $("#id_bank_" + type + "_" + get_purch_id).attr('disabled', 'disabled');
      $('#tips_' + type + '_' + get_purch_id).val('');
      $('#remarks_' + type + '_' + get_purch_id).val('');
      $('#id_company_' + type + '_' + get_purch_id).val('0');
      $('#id_fo_bill_' + type + '_' + get_purch_id).val('0');
      $('#cardnumber_' + type + '_' + get_purch_id).val('');
      $('#id_bank_' + type + '_' + get_purch_id).val('0');
      //$('#id_company_'+type+'_'+get_purch_id).empty();

      var pay_total_amount = sum - linepay;
      $('input[name="pay_total_amount_' + get_purch_id + '"').val(pay_total_amount);
      //$(".billingamount_"+get_purch_id).val(0);
      $("#payamount_" + type + "_" + get_purch_id).val(0);
      var checkboxpayamount = '0_' + type + '_' + grand_total_amount + '_' + get_purch_id;
      $('.checkboxpayamount_' + type + '_' + get_purch_id).val(checkboxpayamount);
      //+ + +linepay
      var grand_total_amount = (+grand_total_amount) - pay_total_amount;
      $('input[name="balance_amount_' + get_purch_id + '"').val(grand_total_amount);
    }
  });

  function getpayamount(payamount, get_purch_id, grand_total_amount, type) {

    var sum = 0;
    $(".billingamount_" + get_purch_id).each(function () {
      sum += +$(this).val();
      //var cash = $(this).val();
    });

    if (grand_total_amount >= sum) {
      var balance_amount = grand_total_amount - sum;
      $('input[name="pay_total_amount_' + get_purch_id + '"').val(sum);
      $('input[name="balance_amount_' + get_purch_id + '"').val(balance_amount);

      var checkboxpayamount = payamount + '_' + type + '_' + grand_total_amount + '_' + get_purch_id;
      $('.checkboxpayamount_' + type + '_' + get_purch_id).val(checkboxpayamount);

      //$("#balance_amount_"+get_purch_id).val(balance_amount);
    } else {
      $("#payamount_2_" + get_purch_id).attr('disabled', 'disabled');
      $("#payamount_3_" + get_purch_id).attr('disabled', 'disabled');
      $("#payamount_4_" + get_purch_id).attr('disabled', 'disabled');
      $("#payamount_5_" + get_purch_id).attr('disabled', 'disabled');
      $("#payamount_6_" + get_purch_id).attr('disabled', 'disabled');

      //var checkboxpayamount =payamount+'_'+type+'_'+grand_total_amount+'_'+get_purch_id;
      $('.checkboxpayamount_1_' + get_purch_id).val('0_1_' + grand_total_amount + '_' + get_purch_id);
      $('.checkboxpayamount_2_' + get_purch_id).val('0_2_' + grand_total_amount + '_' + get_purch_id);
      $('.checkboxpayamount_3_' + get_purch_id).val('0_3_' + grand_total_amount + '_' + get_purch_id);
      $('.checkboxpayamount_4_' + get_purch_id).val('0_4_' + grand_total_amount + '_' + get_purch_id);
      $('.checkboxpayamount_5_' + get_purch_id).val('0_5_' + grand_total_amount + '_' + get_purch_id);
      $('.checkboxpayamount_6_' + get_purch_id).val('0_6_' + grand_total_amount + '_' + get_purch_id);


     // $('input[name="pay_total_amount_' + get_purch_id + '"').val(0);
      //$(".billingamount_" + get_purch_id).val(0);
      //payamount_"+type+"_"+get_purch_id
     // $('input[name="balance_amount_' + get_purch_id + '"').val(grand_total_amount);
      //alert('Greater than Total Amount');
		
		var balance_amount = grand_total_amount - sum;
      $('input[name="pay_total_amount_' + get_purch_id + '"').val(sum);
      $('input[name="balance_amount_' + get_purch_id + '"').val(balance_amount);

      var checkboxpayamount = payamount + '_' + type + '_' + grand_total_amount + '_' + get_purch_id;
      $('.checkboxpayamount_' + type + '_' + get_purch_id).val(checkboxpayamount);
		
    }

  }


  function recheckin() {

    var id_reservation = document.getElementById('id_reservation').value;

    var re_reservation_date = document.getElementById("re_reservation_date").value;
    var re_id_mst_hotels = document.getElementById("re_id_mst_hotels").value;


    var TariffPerRoomperNights = document.getElementById("TariffPerRoomperNights").value;
    var TaxPerRoomperNights = document.getElementById("TaxPerRoomperNights").value;

    var id_owner_room = "<?php echo $id_owner_room; ?>";

    $.ajax({
      type: "GET",
      url: 'ajax/ajaxSaveRecheckin.php',
      data: 're_reservation_date=' + re_reservation_date + '&id_reservation=' + id_reservation +
        '&re_id_mst_hotels=' + re_id_mst_hotels + '&TariffPerRoomperNights=' + TariffPerRoomperNights +
        '&TaxPerRoomperNights=' + TaxPerRoomperNights + '&id_owner_room=' + id_owner_room,
      success: function (result) {
        $(".targetDivShowRecheckin").hide();
        alert(result);
        window.location.href = "onewindow.php";

      }
    })



  }

  function updateFolioSplit(id_resevation, id_mst_guest, id_fo_bill, id_owner_room) {

    // var folio_split = $("input[name='folio_split[]']").map(function(){return $(this).val();}).get();

    var category_id = [];

    $.each($("input[name='folio_split[]']:checked"), function () {
      category_id.push($(this).val());
    });
    var folio_split = category_id;
    var room_select = '<?php echo $room_select; ?>';

    bootbox.confirm({
      title: "Split Folio",
      message: `
        <p>Do you want to Split this Folio?</p>
        <div class="form-group">
            <label for="folioSelect">Select Folio Type:</label>
            <select id="folioSelect" class="form-control">
                <option value="0">Select</option>
                <option value="1">Split as Sub Folio</option>
                <option value="2">Split as New main Folio</option>
            </select>
        </div>
        <div class="form-group" style="display:none;" id="folioSelectDiv">
            <label for="roomSelect">Select Room:</label>
            <select id="roomSelect" class="form-control">
                ${room_select}
            </select>
        </div>`,
      buttons: {
        cancel: {
          label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
          label: '<i class="fa fa-check"></i> Confirm'
        }
      },
      callback: function (result) {
        var roomSelect = $('#roomSelect').val();
        var folioSelect = $('#folioSelect').val();
        if (result == false) {
          $('.bootbox').modal('hide');
          return false;
        }
        if (folioSelect == 0) {
          bootbox.alert("Please fill in all fields.");
          return false;
        }

        if (folioSelect == 2 && roomSelect == '') {
          bootbox.alert("Please fill in all fields.");
          return false;
        }

        if (folioSelect == 1 && roomSelect == '') {
          roomSelect = id_owner_room;
        }
        // console.log(roomSelect);
        // return false;
        $.ajax({
          type: "GET",
          url: 'ajax/updateFolioSplit.php',
          data: 'folio_split=' + folio_split + '&id_resevation=' + id_resevation + '&id_mst_guest=' +
            id_mst_guest + '&id_fo_bill=' + id_fo_bill + '&id_owner_room=' + roomSelect + '&folio_type=' +
            folioSelect,
          success: function (result) {
            //$(".targetDivShowRecheckin").hide();
            window.location.href = "onewindow.php";
            alert(result);
          }
        });
      }
    });

    $('#folioSelect').change(function() {
        var selectedValue = $(this).val();
        if (selectedValue == '1') {
          $('#folioSelectDiv').hide();
        } else {
          $('#folioSelectDiv').show();
        }
    });
  }

  function saveBillToCompany(id_fo_bill) {

    var id_reservation = document.getElementById('id_reservation').value;


    var re_id_mst_hotels = document.getElementById("re_id_mst_hotels").value;

    var id_bill_to_company = document.getElementById("id_bill_to_company").value;

    $.ajax({
      type: "GET",
      url: 'ajax/ajaxSaveBillToCompany.php',
      data: 'id_reservation=' + id_reservation + '&re_id_mst_hotels=' + re_id_mst_hotels + '&id_fo_bill=' +
        id_fo_bill + '&id_bill_to_company=' + id_bill_to_company,
      success: function (result) {
        console.log(result);
        data = JSON.parse(result);
        $("#folio_company").html(data.msg_value);
        alert(data.msg);

      }
    })



  }

  function checkIsFoBillGenerated(idfobill, id_folio, id_room, submenu, session,fo_bill_url, balance_amount, fo_bill_no, checkout_status) {
    if (fo_bill_no == '') {
      bootbox.alert('Still FO Bill not Generated.');
      return false;
    }

    var message = "";
    if (balance_amount > 0 || checkout_status != 2) {
      if (balance_amount > 0 && checkout_status != 2) {
        message = "Checkout Date not updated & Bill Not Closed, Still do you want to print FO Bill?.";
      } else if (balance_amount > 0) {
        message = "Bill Not Closed, Still do you want to print FO Bill?.";
      } else if (checkout_status != 2) {
        message = "Checkout Date not updated, Still do you want to print FO Bill?.";
      }
    }

    if (message != "") {
      bootbox.confirm({
        title: "Print Folio ",
        message: message,
        buttons: {
          cancel: {
            label: '<i class="fa fa-times"></i> No'
          },
          confirm: {
            label: '<i class="fa fa-check"></i> Yes'
          }
        },
        callback: function (result) {
          if (result == true) {
            confirmPreviewOption(idfobill, id_folio, id_room, submenu, session);
          }
        }
      });
    } else {
      confirmPreviewOption(idfobill, id_folio, id_room, submenu, session);
    }
  }
</script>

<script>
function confirmPreviewOption(idfobill, id_folio, id_room, submenu, session) {
  bootbox.dialog({
    title: "Choose Bill Format",
    message: `
      
      <div class="form-group">
       
        <select id="previewType" class="form-control">
          <option value="">-- Select Option --</option>
          
          <?php if (!empty($frontbillprint)) { ?>
            <option value="customformats" selected>Custom Format</option>
			<option value="daywise_standard">Date Wise</option>         
          <option value="roomwise">Room Wise</option>
          <?php }else{?>
<option value="daywise_standard" selected>Date Wise</option>         
          <option value="roomwise">Room Wise</option>
<?php } ?>
		  
        </select>
      </div>
      <div id="hideRoomTypeContainer" class="form-group" style="display:none;">
        <div class="form-check">
          <input type="checkbox" class="form-check-input" id="hideRoomType">
          <label class="form-check-label" for="hideRoomType">Hide Room Type</label>
        </div>
      </div>
    `,
    buttons: {
      confirm: {
        label: "Print",
        className: "btn-primary",
        callback: function () {
          const selectedValue = document.getElementById("previewType").value;
          const hideRoomType = document.getElementById("hideRoomType")?.checked ? 1 : 0;

          if (!selectedValue) {
            bootbox.alert("Please select a preview type.");
            return false;
          }

          if (selectedValue.startsWith("daywise")) {
            openPreviewDayWise(selectedValue, idfobill, id_folio, id_room, submenu, session);
          } else if (selectedValue === "roomwise") {
            openPreviewRoomWise("roomwise", idfobill, id_folio, id_room, submenu, session, hideRoomType);
          } else if (selectedValue === "customformats") {
            openPreviewcustomformats("roomwise", idfobill, id_folio, id_room, submenu, session);
          }
        }
      },
      cancel: {
        label: "Cancel",
        className: "btn-secondary"
      }
    },
    onShown: function () {
      document.getElementById("previewType").addEventListener("change", function () {
        const selected = this.value;
        const container = document.getElementById("hideRoomTypeContainer");
        if (selected === "roomwise") {
          container.style.display = "block";
        } else {
          container.style.display = "none";
        }
      });
    }
  });
}

function openPreviewDayWise(type, idfobill, id_folio, id_room, submenu, session) { //const url = "fobillformat_DayWise.php"
  const url = "fobillDatewise.php"
    + "?idfobill=" + idfobill
    + "&id_folio=" + id_folio
    + "&id_mst_room_no_allocation=" + id_room
    + "&submenu=" + submenu
    + "&session=" + session
    + "&preview_type=" + type;

  window.open(url, "_blank");
}

function openPreviewRoomWise(type, idfobill, id_folio, id_room, submenu, session, hideRoomType) { //const url = "fobillformat_RoomWise.php"
  const url = "fobillRoomwise.php"
    + "?idfobill=" + idfobill
    + "&id_folio=" + id_folio
    + "&id_mst_room_no_allocation=" + id_room
    + "&submenu=" + submenu
    + "&session=" + session
    + "&preview_type=" + type
    + "&hide_room_type=" + hideRoomType;

  window.open(url, "_blank");
}

function openPreviewcustomformats(type, idfobill, id_folio, id_room, submenu, session) {
  const url = "<?php echo $frontbillprint;?>"
    + "?idfobill=" + idfobill
    + "&id_folio=" + id_folio
    + "&id_mst_room_no_allocation=" + id_room
    + "&submenu=" + submenu
    + "&session=" + session
    + "&preview_type=" + type;

  window.open(url, "_blank");
}
</script>

