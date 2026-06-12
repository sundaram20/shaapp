<?php 
include_once("../../config/auto_loader.php");

?>
<script>// $(".select3").select2();
$('.select2').each(function () {
    $(this).select2({
        dropdownParent: $(this).parent(),// fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function (e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})

</script>
<?php

 	$sql = "SELECT * FROM `".FO_RESERVATIONS."` where `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
	$_SESSION['eId']	=	$_REQUEST['eId'];
	$db->query($sql);
	//if($db->num_rows() > 0){
		$row = $db->fetch_object();
	$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_hotels."'");
	$Guestname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_guest."'");
	$booking_date	=date('d-m-Y',strtotime($row->booking_date));
	$checkin =date('d-m-Y',strtotime($row->checkin));
	$checkout	=	date('d-m-Y',strtotime($row->checkout));
	
	
	
	$room_tariff_price	= $row->sub_total;
	$discount	=	$row->discount;
	$total_addon_price	=$row->total_addon_price;
	$total_tax=$row->total_tax;
	$amount_received	=	$row->amount_received;
	$net_booking_amount	=	$row->net_booking_amount;
	$balance	= $row->balance;
	
	$readonly ='readonly="readonly"';
  $room_no_allocation_id = '';
$reservation_owner_room = mysqli_query($connNew, "SELECT * FROM `fo_bill` WHERE `id_reservations` ='".addslashes(encryptor('decrypt',$_REQUEST['id']))."' and `id_owner_room` ='".addslashes($_REQUEST['id_mst_room_no_allocation'])."'  ");
	if($room_owner= mysqli_num_rows($reservation_owner_room)>0){
    $reservation_room_result = mysqli_fetch_object($reservation_owner_room);
		$ownerRoomStatus	='1';
	}else{
	$ownerRoomStatus	='0';
	}
?>

<form id="saveReservationDateform" method="post"  class="saveReservationDateform" data-parsley-validate autocomplete="off">
  <input type="hidden" name="editid" id="editid" value="<?php echo addslashes(encryptor('decrypt',$_REQUEST['id']));?>">
	<input type="hidden" name="old_id_mst_room_no_allocation" id="old_id_mst_room_no_allocation" value="<?php echo addslashes($_REQUEST['id_mst_room_no_allocation']);?>">
	
	<input type="hidden" name="owner_room" id="owner_room" value="<?php echo addslashes($ownerRoomStatus);?>">
	
  <div class="box-header with-border">
    <h3 class="box-title">Change Room </h3>
    <div class="box-tools pull-right">
      <button type="button" class="viewincPopUp_close btn btn-box-tool" data-dismiss="modal"><i class="fa fa-times"></i></button>
    </div>
  </div>
  <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> </div>
  <div class="form-group col-sm-2">
    <label for="checkout" style="float:left;">Hotel Name</label>
    <input type="text" class="form-control"    id="hotelname" name="hotelname" value="<?php echo  $hotelname; ?>"  readonly="readonly" >
    <input type="hidden" class="form-control"    id="id_mst_hotels" name="id_mst_hotels" value="<?php echo  $row->id_mst_hotels; ?>"  >
  </div>
  <div class="form-group col-sm-2">
    <label for="checkin" style="float:left;">Booking Number</label>
    <input type="text" class="form-control"  id="bookingNo" name="bookingNo" value="<?php echo $row->booking_no;?>"  readonly="readonly">
  </div>
  <div class="form-group col-sm-2">
    <label for="checkout" style="float:left;">Booking Date</label>
    <input type="text" class="form-control"  <?php echo $readonly; ?> placeholder="Enter checkin Date" id="bookingDate" name="bookingDate" value="<?php echo $booking_date;?>" readonly="readonly" >
  </div>
  <div class="form-group col-sm-6">
    <label for="checkin" style="float:left;" readonly="readonly">Guest Name</label>
    <?php 
      
      
      $categoryDropDown = '<select class="form-control select2" name="id_mst_guest" id="id_mst_guest" >

                  <option value="">Select Guest</option>';

                  	$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
		
		$query=mysqli_query($connNew, $SQL);
		
		
		
	    while($resultCat=mysqli_fetch_assoc($query)){

                    if($row->id_mst_guest == $resultCat['id']){

                      $selected = 'selected="selected"';

                    }else{

                      $selected = '';

                    }

                    $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['guest_reg_no'] . ' - ' . $resultCat['first_name'].' '. $resultCat['last_name'].' - '.$resultCat['email'].'-' . $resultCat['city'].'</option>';

                  }

                 

                  echo $categoryDropDown .= '</select>';

                  ?>
  </div>
  <div class="form-group col-sm-2">
    <label for="checkout" style="float:left;">Check In</label>
    <input type="text" class="form-control datepickercheckin"  <?php echo $readonly; ?> placeholder="Enter checkin Date" id="checkin_extend_date" name="checkin_extend_date" value="<?php echo $checkin;?>" readonly="readonly" >
  </div>
  <div class="form-group col-sm-2">
    <label for="checkin" style="float:left;">Check Out</label>
    <input type="text" class="form-control datepickercheckout"  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="checkout_extend_date" name="checkout_extend_date" value="<?php echo $checkout;?>" readonly="readonly" >
  </div>
  
  
  
  <div class="form-group col-sm-12">
    <div class="box-body">
      <div class=" table-responsive" style="padding: 0px 0px;   
    padding: 19px;
    margin-bottom: 20px;
    background-color: #f5f5f5;
    border: 1px solid #e3e3e3;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.05);
">
        <table class="table order-list1 table-hover">
          <thead id="roomhideandshow" style="">
            <tr>
              <th style="width: 140px;">Effective from </th>
              <th>Room Type</th>
              <th>Plan</th>
              <th>Room No</th>
             
            
              <th>Tariff Per Room <br>
                per Nights</th>
              <th>Taxes</th>
              <th>Tariff Per Room <br>
                inclusive Taxes</th>
              <?php /*?> <th>Charges <br>
                    Per Night</th><?php */?>
              <th></th>
            </tr>
          </thead>
          <?php 	
					 for ($i=1; $i<=100; $i++)
    				{
        
            $roomQty .='<option value="'.$i.'"';
			 if($row->room_quantity ==$i){
			 $roomQty .='selected="selected"';
			 }
			 
			  $roomQty .='>'.$i.'</option>';
       
    } ?>
          <?php 
			
			$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' and  id_mst_room_no_allocation ='".addslashes($_REQUEST['id_mst_room_no_allocation'])."'");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				$rowOrderDetail= mysqli_fetch_object($sqlOrderDetail);
				$checkin =date('d-m-Y',strtotime($row->checkin));
				$checkout	=	date('d-m-Y',strtotime($row->checkout));
				
				$topArrow	=	'<a href="javascript:void(0);" onclick="fillLeft(\''.date('Y-m-d',strtotime($checkin)).'\',\''.date('Y-m-d',strtotime($checkout)).'\',\''.$rowOrderDetail->dated.'\',\''.$rowOrderDetail->id.'\');"  class="text-green input-group-addon"><i  class="arrows fa fa fa-angle-double-down" style="margin-left:-4px;"></i></a>';
				
				$downArrow	='<a href="javascript:void(0);" onclick="fillRight(\''.date('Y-m-d',strtotime($checkin)).'\',\''.date('Y-m-d',strtotime($checkout)).'\',\''.$rowOrderDetail->dated.'\',\''.$rowOrderDetail->id.'\');" class="text-green input-group-addon"><i  class="arrows fa fa-angle-double-up" style="margin-left:-4px;"></i></a>';		
				
				
				$topArrowInc	=	'<a href="javascript:void(0);" onclick="fillLeftInc(\''.date('Y-m-d',strtotime($checkin)).'\',\''.date('Y-m-d',strtotime($checkout)).'\',\''.$rowOrderDetail->dated.'\',\''.$rowOrderDetail->id.'\');"  class="text-green input-group-addon" ><i  class="arrows fa fa fa-angle-double-down" style="margin-left:-4px;"></i></a>';
				
				$downArrowInc	='<a href="javascript:void(0);" onclick="fillRightInc(\''.date('Y-m-d',strtotime($checkin)).'\',\''.date('Y-m-d',strtotime($checkout)).'\',\''.$rowOrderDetail->dated.'\',\''.$rowOrderDetail->id.'\');" class="text-green input-group-addon"><i  class="arrows fa fa-angle-double-up"  style="margin-left:-4px;"></i></a>';		
				
					?>
          <tr>
            <td style="width: 140px;">
             <input type="text" class="form-control "  style="width: 84px;" <?php echo $readonly; ?> placeholder="Enter checkout Date" id="effectivefrom_date" name="effectivefrom_date" value="<?php echo $checkout;?>"  >
             <input type="hidden" class="form-control "  style="width: 84px;" <?php echo $readonly; ?> id="order_by_room" name="reservation[<?php echo $rowOrderDetail->id;?>][order_by_room]" value="<?php echo $rowOrderDetail->order_by_room;?>"  >
          
		  <?php /*?>  
            <input type="text" class="form-control parsley-error" style="width: 84px;" name="dated[]" id="dated" value="<?php echo date('d-m-Y',strtotime($rowOrderDetail->dated)); ?>" readonly=""></td>
           <?php */?> <td>
            <select class="form-control parsley-error" name="reservation[<?php echo $rowOrderDetail->id;?>][roomtype]" id="roomtype_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" onchange="roomtypechangepopup(this.id,this.value);" style="width: 100px;" <?php echo $readonly; ?>>
             <?php
			 echo  $selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '".$row->id_mst_hotels."' " ;
	$resneww = mysqli_query($connNew,$selectneww);
$dataArr='';
	$dataArr=  '<option value="">Select Type</option>';
	
	while($rowneww = mysqli_fetch_object($resneww)){
		$roomno = $rowneww->id_mst_room_types;
		
                $selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where id=$roomno";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$romm = $rownew->name;
					$id = $rownew->id;
					
				if($id == $rowOrderDetail->id_mst_room_types){
						$selected="selected";
					}
					else{
						$selected="";
					}
				$dataArr.=  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
		
				}	
			}echo $dataArr;
		//room type========================
        ?>
              </select></td>
             
            <td><select class="form-control parsley-error" name="reservation[<?php echo $rowOrderDetail->id;?>][plan]" id="plan_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" onchange="getval(this.id)" style="width: 80px;" <?php echo $readonly; ?>>
               <?php 
			    $dataArrplan='';
			   $dataArrplan=  '<option value="">Select one</option><option value="1">Multiplan</option>';
			   $selectnewp="SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM  ".TBL_RATE_PLAN."
				where status='1' and id_shop='".addslashes($_SESSION['shop'])."'";
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){
					
					if($rownewp->id==$rowOrderDetail->id_fo_rate_plan){
						$selected ='selected="selected"';
					}else{
						$selected ="";
					}
					
					$dataArrplan.=  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
				}
				echo $dataArrplan;
                ?>

              </select>
              <input type="hidden" name="plantype[]" id="plantype1" value=""></td>
              <?php 
			  
			  $roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			  
			  ?>
              
              <td>
              <select class="form-control parsley-error roombuttonpopup" name="reservation[<?php echo $rowOrderDetail->id;?>][id_mst_room_no_allocation]" id="id_mst_room_no_allocation_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" onchange="getval(this.id)" style="width: 80px;" <?php echo $readonly; ?>>
               <?php 
			   $dataArrplan='';
			   $dataArrplan='<option value="">Select one</option>';
			   $selectnewp="SELECT * FROM  ".TBL_ROOMNO." where status='1' and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."' and room_status = '4'";
				$resnewp = mysqli_query($connNew,$selectnewp);				
        $selected ='selected="selected"';
				while($rownewp = mysqli_fetch_object($resnewp)){					
					$dataArrplan.=  '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->room_no.'</option>';
				}
        $room_no_allocation = "select * from mst_room_no_allocation where id='".$rowOrderDetail->id_mst_room_no_allocation."'";
        $room_no_allocation_query = mysqli_query($connNew,$room_no_allocation);
        if ($room_no_allocation_query) {
          $room_no_allocation_record = mysqli_fetch_assoc($room_no_allocation_query);
          $room_no_allocation_id = $room_no_allocation_record['id'];
          $room_no_text = $room_no_allocation_record['room_no'];
          $dataArrplan.=  '<option '.$selected.' value="'.$room_no_allocation_id.'" >'.$room_no_text.'</option>';
        }
        echo $dataArrplan;
                ?>

              </select>
              
              
              
              
              </td>
           
            <td style="display:inline-flex"><?php echo $topArrow;?>
              <input type="text" class="form-control parsley-error" style="width: 70px;" name="reservation[<?php echo $rowOrderDetail->id;?>][tariffperroom]" id="tariffperroom_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" value="<?php echo $rowOrderDetail->tariff_price_per_day_per_room; ?>"
                      onkeyup="tariffCalculationEdit('<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>',this.value);" id="tariffperroom_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>">
              <?php echo $downArrow;?></td>
            <td><input type="text" class="form-control parsley-error" style="width: 70px;" name="reservation[<?php echo $rowOrderDetail->id;?>][taxes]" id="taxes_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" value="<?php echo $rowOrderDetail->tax_per_day_per_room; ?>" readonly=""></td>
            <td style="display:inline-flex"><?php echo $topArrowInc;?>
              <input type="text" class="form-control parsley-error" style="width: 60px" name="reservation[<?php echo $rowOrderDetail->id;?>][tariffperroomtax]" id="tariffperroomtax_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>"  
                     onkeyup="tariffCalculationIncEdit('<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>',this.value);" value="<?php echo $rowOrderDetail->tax_per_day_per_room+$rowOrderDetail->tariff_price_per_day_per_room; ?>">
              <?php echo $downArrowInc;?></td>
            <?php /*?> <td><input type="text" class="form-control parsley-error" style="width: 70px;" value="<?php echo $rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room; ?>" name="chargespernight[]" id="chargespernight_<?php echo date('Y-m-d',strtotime($rowOrderDetail->dated));?>" readonly=""></td><?php */?>
          </tr>
          <?php } ?>
        </table>
        <input type="hidden" name="old_room_no_allocation_id" id="old_room_no_allocation_id" value="<?php echo $room_no_allocation_id;?>">
      </div>
    </div>
  </div>
  <?php /* ?>
  <div class="form-group col-sm-3">
    <label for="checkin" style="float:left;">Tariff Amount</label>
    <input type="text" class="form-control "  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="room_tariff_price" name="room_tariff_price" value="<?php echo $room_tariff_price;?>"  >
  </div>
  <div class="form-group col-sm-3">
    <label for="checkin" style="float:left;">Taxes</label>
    <input type="text" class="form-control "  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="total_tax_edit" name="total_tax_edit" value="<?php echo $total_tax;?>"  >
  </div>
  <div class="form-group col-sm-3">
    <label for="checkin" style="float:left;">Tota1</label>
    <input type="text" class="form-control "  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="net_booking_amount_edit" name="net_booking_amount_edit" value="<?php echo $net_booking_amount;?>"  >
  </div>
  <div class="form-group col-sm-3">
    <label for="checkin" style="float:left;">Balance</label>
    <input type="text" class="form-control "  <?php echo $readonly; ?> placeholder="Enter checkout Date" id="balance_edit" name="balance_edit" value="<?php echo $balance;?>"  >
  </div><?php */ ?>
  <div class="modal-footer">
    <button class="btn n-btn" onclick="saveUpdateRoomNumbers();" type="button">Save</button>
    &nbsp;
    <button type="button" class="btn n-btn" data-dismiss="modal">Close</button>
  </div>
</form>
<script>


 var checkin  ='<?php echo $checkin; ?>';
 var checkout ='<?php echo $checkout; ?>';
 
 	 $("#effectivefrom_date").val('<?php echo $checkin; ?>');
	 //checkout=checkout'+10d';
	var checkinarray	=checkin.split('-');
	var checkoutarray	=checkout.split('-');
	
		var end = new Date(checkoutarray[2] + "-" +checkoutarray[1] + "-" +checkoutarray[0]);
		var  start = new Date(checkinarray[2] + "-" +checkinarray[1] + "-" +checkinarray[0]);
	
	end.setDate(end.getDate() -1);
	
	$('#effectivefrom_date').datepicker('destroy');
	$("#effectivefrom_date").datepicker({
	dateFormat : 'dd-mm-yy',
	minDate : start,
	maxDate : end ,
	//beforeShowDay: noWeekendsOrHolidaysOrBlockedDates
	
	});
	</script>