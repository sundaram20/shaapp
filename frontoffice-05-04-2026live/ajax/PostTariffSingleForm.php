<?php 
include_once("../../config/auto_loader.php");

?>
<style>
.error {
	color: #F00;
	font-size: 12px;
}
.deleteBox {
	width: 35px;
	height: 35px;
	background-color: #fff;
	/* White background by default */
	display: flex;
	align-items: center;
	justify-content: center;
	cursor: pointer;
	transition: background-color 0.3s;
	border: 1px solid #d2d6de !important;/* margin-top : 7px; */

}
.deleteBox:hover {
	background-color: #db3434;/* Blue color on hover */
}
.deleteBox:active {
	background-color: #2980b9;/* Darker blue color when clicked */
}
.deleteBox i {
	color: #db3434;
	/* Blue color for the icon by default */
	font-size: 15px;
	transition: color 0.3s;
}
.deleteBox:hover i {
	color: #fff;/* White color for the icon on hover */
}
.deleteBox:active i {
	color: #fff;/* White color for the icon when clicked */
}
#EditReservationModal .modal-dialog {
	width: 100% !important;
	margin: 0 !important;
}
#EditReservationModal {
	padding: 0px !important;
	min-height: 100vh !important;
}
#EditReservationModal .modal-content {
	min-height: 100vh !important;
}
.input-validation-error ~ .select2 .select2-selection__rendered {
	border: 1px solid red;
}
</style>
<script>
//$('.select2').select2();
$('.select2').each(function() {
    $(this).select2({
        dropdownParent: $(this).parent(), // fix select2 search input focus bug
    })
})

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function(e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})
</script>
<?php

	
	//debugData($_REQUEST);
	
	if($_REQUEST['BookingType']=='Edit'){
		
		 $id_fo_folio_to	= $_REQUEST['id_folio'];
		$EditReservationId	=	addslashes(encryptor('decrypt',$_REQUEST['id']));
		$HotelSplit	=	explode('-',$_REQUEST['id_hotel']);


$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelSplit[0]."'");

$id_mst_hotels=$HotelSplit[0];

  $sql = "SELECT * FROM `".FO_RESERVATIONS."` where `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
	$_SESSION['eId']	=	$_REQUEST['eId'];
	$db->query($sql);
	//if($db->num_rows() > 0){
		$row = $db->fetch_object();
	//$hotelname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_hotels."'");
	$Guestname	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_guest."'");
	$booking_date	=date('d-m-Y',strtotime($row->booking_date));
	$Checkindata =date('d-m-Y',strtotime($row->checkin));
	$checkoutDate	=	date('d-m-Y',strtotime($row->checkout));
	$BookingDate =date('d-m-Y',strtotime($row->checkin));
	
	
	$room_tariff_price	= $row->sub_total;
	$discount	=	$row->discount;
	$total_addon_price	=$row->total_addon_price;
	$total_tax=$row->total_tax;
	$amount_received	=	$row->amount_received;
	$net_booking_amount	=	$row->net_booking_amount;
	$balance	= $row->balance;
	$BookingMdoc_no =$row->mdoc_no;
	 
	 
 
		//room type========================
		$ReservationTitle	='Post Charges';// <span style="font-weight:bold;">'.$BookingMdoc_no.'</span>';
		
		
		 $sqlOrderDetailPOST = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' and id_fo_folio_to!=0 and `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."'  group by id_mst_room_no_allocation  ");
		if(mysqli_num_rows($sqlOrderDetailPOST) >0 ){
			$PostChargesRoomArray=array();
			//$rowOrderDetail->id_mst_room_types;
				while($rowOrderDetailPost= mysqli_fetch_object($sqlOrderDetailPOST)){ 
				
				
				 $id_fo_folio_to	= $_REQUEST['id_folio'];//$rowOrderDetailPost->id_fo_folio_to;
					
					
					$PostChargesRoomArray[]	=	$rowOrderDetailPost->id_mst_room_no_allocation;
					
					
				}
				
				$PostChargesRoomArray	= implode(',',array_unique($PostChargesRoomArray));
		  $selectRoomno="SELECT * FROM  ".TBL_ROOMNO."  where id IN (".$PostChargesRoomArray.")";				
				$resRoomno = mysqli_query($connNew,$selectRoomno);				
				while($rowRoomno = mysqli_fetch_object($resRoomno)){					
					$listRoomArray[$rowRoomno->id]	=$rowRoomno->room_no;
				}
				
				$listRoomArray=	array_unique($listRoomArray);
				//debugData($listRoomArray); 
		}
		
			foreach($listRoomArray as $id=>$value){
				
				$ListRoomNo.=  '<option  value="'.$id.'" >'.$value.'</option>';
				
				}
				
				
				
		$folio_mdoc_no	= selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_fo_folio_to."'"); 		
		}else{ //ADD New RESERVATION ======================================================
			
			
	$readonly ='readonly="readonly"';
	include_once("../functions/function.php");
 	$id_doc_type='801'; //DOCUMENT TYPE FOLIO 803
	$doc_table_name=FO_RESERVATIONS;
	$date = date('Y-m-d');
	$id_subsection='1';$id_shop=$_SESSION['shop'];
	$docConfig	=	docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	
	$BookingMdoc_no =addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix']);
	$Checkindata= date('d-m-Y');
	$checkoutDate	=date('d-m-Y',strtotime("+1 day", strtotime(date('d-m-Y'))));	
	$BookingDate =date('d-m-Y');
	
	$ReservationTitle	='Reservation Form';
			}
?>

<form id="savePostChargesDateform" method="post" class="savePostChargesDateform" data-parsley-validate
    autocomplete="off">
  <input type="hidden" name="editid" id="editid"
        value="<?php echo addslashes(encryptor('decrypt',$_REQUEST['id']));?>">
  
  <!-- <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> </div> -->
  <div style="display : flex!important; flex-direction : column!important;">
    <div style="padding : 5px 10px;">
      <div class="form-group col-sm-2">
        <label for="checkout" style="float:left;">Hotel Name</label>
        <?php 
				 
				    $categoryDropDown = '<select class="form-control select2" name="id_mst_hotels_new" id="id_mst_hotels_new" >

                          <option value="">Select Hotel</option>';

                            $SQL = "select *  from mst_hotels where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
            
            $query=mysqli_query($connNew, $SQL);
            
            
            
              while($resultCat=mysqli_fetch_assoc($query)){

                            if($row->id_mst_hotels== $resultCat['id']){

                              $selected = 'selected="selected"';

                            }else{

                              $selected = '';

                            }

                            $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['name'].'</option>';

                          }

                        

                          echo $categoryDropDown .= '</select>';

                          ?>
        <p class="error id_mst_hotels_new-error"></p>
      </div>
      <div class="form-group col-sm-2">
        <label for="checkin" style="float:left;">Booking Number</label>
        <input type="text" class="form-control" id="bookingNo" name="bookingNo"
                    value="<?php echo $BookingMdoc_no; ?>" readonly="readonly">
      </div>
      <div class="form-group col-sm-1">
        <label for="checkout" style="float:left;">Booking Date</label>
        <input type="text" class="form-control pull-right pickerdate" value="<?php echo date('d-m-Y');  ?>"
                    readonly="readonly" name="bookingDate" id="bookingDate">
      </div>
      <div class="form-group col-sm-1">
        <label for="checkout" style="float:left;">Check In</label>
        <input type="text" class="form-control pickerdate" placeholder="Enter checkin Date"
                    id="checkin_extend_date" name="checkin_extend_date" value="<?php echo $Checkindata;  ?>">
      </div>
      <div class="form-group col-sm-1">
        <label for="checkin" style="float:left;">Check Out</label>
        <input type="text" class="form-control pickerdate" placeholder="Enter checkout Date"
                    id="checkout_extend_date" name="checkout_extend_date" value="<?php echo $checkoutDate;  ?>">
      </div>
      <div class="form-group col-sm-1">
        <label for="checkin" style="float:left;">Folio no</label>
        <input type="text" class="form-control" placeholder="Folio no"
                    id="res_folio" name="res_folio" value="<?php echo $folio_mdoc_no;  ?>">
        <input type="hidden" class="form-control" placeholder="Folio no"
                    id="id_fo_folio_to" name="id_fo_folio_to" value="<?php echo $id_fo_folio_to;  ?>">
      </div>
    </div>
    
    <!-- table feild form -->
    
    <div class="col-md-12 col-sm-12 col-xs-12">
      <table class="table table-striped table-bordered" cellspacing="0">
        <thead>
          <tr class="info">
            <th>Date	</th>
            <th>Ledger</th>
             <th>Description</th>
            <th>Link Room</th>
			  <th>No of Days</th>
            <th>Unit</th>
            <th><span id="changeDynamicField">No of Room</span></th>
            <th><span id="changeDynamicRateField">Rate Per Room Per Day </span></th>
            <th>Taxs</th>
            <th>Inclusive of Taxes</th>
            <th>Total</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td id="folio_no">  <input type="text" class="form-control pickerdate" placeholder="Enter checkin Date"
                    id="dateRange" name="dateRange" value="<?php echo $Checkindata;  ?>"  ></td>
            <td id="folio_guestname"><select class="form-control select2" name="res_ledeger" id="res_ledeger"  onchange="selectres_ledeger();">
				<option value="">--Select Ledger--</option>
              <?php 
							 
							   $selectnew="SELECT * FROM ".TBL_CHARGES."  where charges_account='3'";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$chargesName = $rownew->name;
					$id = $rownew->id;
					
				
				$charges.=  '<option  value="'.$id.'" >'.$chargesName.'</option>';
				//$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
				}echo $charges;?>
            </select>
            <p class="error res_ledeger-error"></p></td>
			 
             <td>
                               
                                <input type="text" class="form-control" placeholder="Enter Description" id="additional_description" name="additional_description" value="">
                                </td> 
            <td>
              <select class="form-control select2" name="res_room_number" id="res_room_number">
                <option value="">Select</option>
                <?php
                  foreach ($listRoomArray as $id => $value) {
                ?>
				        <option  value="<?php echo $id; ?>" ><?php echo $value; ?></option>
				<?php
				}
        ?>
              </select>
              <p class="error res_room_number-error"></p>
            </td>
<td>
    <input type="text" class="form-control" name="res_no_days" id="res_no_days" value="1" onkeyup="PostChargesTaxCalculation('');">
   <p class="error res_no_days-error"></p></td>
<td>
    <select class="form-control select2" name="res_unit" id="res_unit" onchange="ChangeHeaderName(this.value)">
      <?php echo $ChargesUnit ='<option value="1">Per room</option><option value="2">Per Adult</option> <option value="3">Per Nos</option>';?>
    </select>
  </td>
<td>
    <input type="text" class="form-control" name="res_no_of_Room" id="res_no_of_Room" value="1"  onkeyup="PostChargesTaxCalculation('');">
     <p class="error res_no_of_Room-error"></p>
  </td>
<td>
    <input type="text" class="form-control" name="res_tariff_per_room_per_night"
                                id="res_tariff_per_room_per_night" onkeyup="PostChargesTaxCalculation('');" value="0" >
  </td>
<td>
    <input type="text" class="form-control" name="res_tax"
                                id="res_tax" onkeyup="PostChargesTaxCalculation('');" value="0" readonly>
                                 <p class="error res_tax-error"></p>
  </td>
<td>
    <input type="text" class="form-control" name="res_tariff_per_room_inclusive_tax" value="0"
                                id="res_tariff_per_room_inclusive_tax"  onkeyup="PostChargesCalculationInclusiveNew('');">
  </td>
<td>
    <input type="text" class="form-control" name="res_total" value="0"
                                id="res_total" readonly>
  </td>
              
         <td><div class="input-group-addon"  style="width: auto;
    border: 1px solid #fefefe;" title="add"> <a href="#" onClick="AddTextBox();"> <i class="fas fa-plus"></i> </a> </div>
        </div>
      
        </td>      
          
          </tr>
        </tbody>
      </table>
      <hr>
    </div>
    
    
   
  </div>
  
  <!-- tabel feild form ends -->
  
 			
  <?php

		//room type========================
        ?>
  <div class="form-group col-12 col-sm-12 " style="margin-bottom : 0px; display: flex;
    flex-direction: column;">
    <div class="box-body">
      <div class="well table-responsive" style="padding: 0px 0px; height : auto!important ;">
        <table class="table order-list1 table-hover">
          <thead id="roomhideandshow" style="">
            <tr>
              <th style="width: 96px;"> </th>
              <th style="width: 154px;">Post Date </th>
              <th style="width: 117px;">Room no </th>
              <th style="width: 199px;">Ledger List</th>
              <th style="width: 125px;">Description</th>
              <th style="width: 139px;">Unit</th>
              <th style="width: 112px;">Days</th>
              <th style="width: 112px;">Nos</th>
              <th style="width: 107px;">Rate</th>
              <th style="width: 109px;">Taxes</th>
              <th style="width: 120px;">Inclusive of Taxes</th>
              <th style="width: 109px;">Total</th>
              <th>Action</th>
              <?php /*?> <th>Charges <br>
                                Per Night</th><?php */?>
              <th></th>
            </tr>
          </thead>
        </table>
        <div id="TextBoxContainerFormEdit">
                    <?php 
			   $order_by_roomRowCount	=	  selectColumn(FO_RESERVATIONS_DETAILS,'order_by_room','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'"  order by id DESC');
$Addtotal	=	  selectColumn('fo_reservations_addons_details','sum(total)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');

$total_rate	=	  selectColumn('fo_reservations_addons_details','sum(rate)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');
$tax_value	=	  selectColumn('fo_reservations_addons_details','sum(tax_value)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'" ');


?>
                    <input style="width: 126px;" type="hidden" class="form-control" id="order_by_roomRowCount"
                        name="order_by_roomRowCount" value="<?php echo $order_by_roomRowCount;?>">

                    <?php
			$start	=1;
			$sqlOrderDetail = mysqli_query($connNew,"Select  `fo_reservations_addons_details`.* from `fo_reservations_addons_details` where  `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."' ORDER BY  dated ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$rowOrderDetail->id_mst_room_types;
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){ 
				
				$AssRoomtype++;
				   $Random	=	(rand(100,1000));				   
				  $start=1;
				if($rowOrderDetail->id_mst_room_types!=$AssHotelRooms){
					$AssRoomtype	='0';
					
					$AssHotelRooms	=	$rowOrderDetail->id_mst_room_types;
					
					}
					
				?>
                    <?php  if($rowOrderDetail->order_by_room!=$order_by_room){
				  
				   
				   $rowOrderDetail->order_by_room.'Order'.$RowCount	=	  selectColumn(FO_RESERVATIONS_DETAILS,'count(id)','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'" and  order_by_room ="'.$rowOrderDetail->order_by_room.'"');

			   		$order_by_room =$rowOrderDetail->order_by_room;
					//$id_reservation_detailArray=array();
					
					$RecordTD	='<td style="width: 80px;"><B>Room '.$AssRoomtype.'</B></td>';
			   ?>
                    <div id="<?php echo $Random;?>" style="border-bottom: solid #7FB3E0;">

                        <?php 
						}else{
							$RecordTD	='<td style="width: 80px;"></td>';							
							$start++;
							}
				$Array_id_mst_room_types    =	$rowOrderDetail->id_mst_room_types;
				$id_reservation_detailArray[$Random][] =$rowOrderDetail->id;
				
				
				foreach($id_reservation_detailArray as $datas){
					
					
					 $yy=implode(',',$datas);
					}
				//echo $id_reservation_detailArray	=implode(',',$id_reservation_detailArray);
				
				
					 ?>
                        <div id="<?php echo $rowOrderDetail->id;?>">
                            <table class="table table-hover" style="margin-bottom:none !important;">
                                <tbody>
                                    <input style="width: 126px;" type="hidden" class="form-control"
                                        id="id_reservation_detail"
                                        name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][id_reservation_detail][]"
                                        value="<?php echo $rowOrderDetail->id;?>" >
                                    <input style="width: 126px;" type="hidden" class="form-control" id="order_by_room"
                                        name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][order_by_room][]"
                                        value="<?php echo $rowOrderDetail->order_by_room;?>">
                                    <tr>
                                    <?php echo $RecordTD; ?>
                                        <td style="width: 120px;"><input type="text"
                                                class="form-control" id="res_date"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][resdate][]"
                                                value="<?php echo date('d-m-Y',strtotime($rowOrderDetail->dated));?>" readonly></td>         
                                        
                                    <td style="width: 100px;"><select
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][room_number_id][]"
                                                id="room_number_id_<?php echo $rowOrderDetail->id;?>" data-parsley-required=""
                                                class="form-control room_number_id_<?php echo $rowOrderDetail->id;?>" readonly="readonly">
                                                <option value="0">Any</option>                <?php
                  foreach ($listRoomArray as $id => $value) {
                    $selected = $rowOrderDetail->id_mst_room_no_allocation == $id ? "selected" : "";
                ?>
				        <option  <?php echo $selected; ?> value="<?php echo $id; ?>" ><?php echo $value; ?></option>
				<?php
				}
        ?>
                                            </select></td>    
                                        
                                        
                                        
                                        
                                        <td style="width: 180px;"><select
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][room_type_id][]"
                                                id="room_type_id[]" data-parsley-required=""
                                                class="form-control room_type_id_<?php echo $rowOrderDetail->id;?>"  readonly="readonly">
                                            
			    <?php 
							 
							  $selectnew="SELECT * FROM ".TBL_CHARGES."  where charges_account='3'";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
					$chargesName = $rownew->name;
					$id = $rownew->id;
						
				if($id == $rowOrderDetail->id_mst_charges){
						$selected="selected";
					}
					else{
						$selected="";
					}
				
				$charges2.=  '<option '.$selected.' value="'.$id.'" >'.$chargesName.'</option>';
				//$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
				}echo $charges2;
				
				?>
                                            </select></td>
                                        
                                        <td style="width: 100px;">
                               
                                <input type="text" class="form-control" placeholder="Enter Description" id="additional_description_<?php echo $rowOrderDetail->id;?>" name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][additional_description][]" value="<?php echo $rowOrderDetail->additional_description;  ?>" readonly="readonly">
                                </td>
                                           
                                          <td style="width: 100px;"><input type="text" class="form-control"
                                                onKeyUp="tariffCalculationNew(<?php echo $rowOrderDetail->id;?>);"
                                                id="tariff_per_room_per_night_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_per_night][]"
                                                value="<?php echo $rowOrderDetail->days;?>"  readonly="readonly">
                                        </td>
                                        
                                         <td style="width: 100px;"><select
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][res_unit][]"
                                                id="res_unit[]" data-parsley-required=""
                                                class="form-control res_unit_<?php echo $rowOrderDetail->id;?>"  readonly="readonly">
                                                <?php echo $rowOrderDetail->unit;
				if($rowOrderDetail->unit == 'Per room' ){$selectedAdultNo1 =  'selected="selected"';}else{$selectedAdultNo1 =''; }
				if($rowOrderDetail->unit == 'Per Adult' ){$selectedAdultNo2 =  'selected="selected"';}else{$selectedAdultNo2 =''; }
				if($rowOrderDetail->unit == 'Per Nos' ){$selectedAdultNo3 =  'selected="selected"';}else{$selectedAdultNo3 =''; }
	
			echo '<option value="1" '.$selectedAdultNo1.'>Per room</option>
				  <option value="2" '.$selectedAdultNo2.'>Per Adult</option>                				
				  <option value="3" '.$selectedAdultNo3.'>Per Nos</option>';?>
                                            </select></td>
                                            
                                            
                                         <td style="width: 100px;"><input type="text" class="form-control"
                                                
                                                id="res_no_of_Room_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][res_no_of_Room][]"
                                                value="<?php echo $rowOrderDetail->qty;?>" readonly="readonly">
                                        </td>
                                        
                                        
                                        
                                        <td style="width: 100px;"><input type="text" class="form-control"
                                                onKeyUp="PostChargesTaxCalculation(<?php echo $rowOrderDetail->id;?>);"
                                                id="tariff_per_room_per_night_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_per_night][]"
                                                value="<?php echo $rowOrderDetail->rate;?>"  readonly="readonly">
                                        </td>
                                        <td style="width: 100px;"><input type="text" class="form-control"
                                                id="perday_tax_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][perday_tax][]"
                                                value="<?php echo $rowOrderDetail->tax_value;?>"  readonly="readonly"></td>
                                                
                                        <td style="width: 100px;"><input type="text" class="form-control"
                                                id="tariff_per_room_inclusive_tax_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_inclusive_tax][]"
                                                value="<?php echo $rowOrderDetail->rate+$rowOrderDetail->tax_value;?>"  readonly="readonly" >
                                        </td>        
                                                
                                                
                                        <td style="width: 100px;"><input type="text" class="form-control"
                                                id="total_<?php echo $rowOrderDetail->id;?>"
                                                name="ReservationDataEditArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][total][]"
                                                value="<?php echo $rowOrderDetail->total;?>"  readonly="readonly">
                                        </td>
										
                                        <?php //if( $start==1){?>

                                        <td style="width: 83px;"><button type="button" value="Remove"
                                                onClick="RemoveTextBoxEdit('<?php echo $rowOrderDetail->id; ?>')" class="deleteBox">
                                                <i class="fas fa-trash"></i></button></td>
                                        <?php //}else{?>
                                       <?php /*?> <td style="width: 83px;"></td><?php */?>

                                        <?php //} ?>
                                    </tr>
                                </tbody>
                            </table>

                        </div>

                        <?php  if($start==$RowCount){
				   

				  			   
			   ?> <input style="width: 126px;" type="hidden" class="form-control"
                            id="id_reservation_detailArray_<?php echo $Random;?>"
                            name="id_reservation_detailArray_<?php echo $Random;?>" value="<?php echo $yy;?>">

                    </div>

                    <?php } ?>
                    <?php  
}?><?php } ?>
                </div>
        <div id="TextBoxContainerForm"></div>
      </div>
    </div>
  </div>
  <?php ?>
  <div style="margin-top : 1rem!important; padding: 5px 10px;">
    <div class="form-group col-sm-3">
      <label for="checkin" style="float:left;">Total Rate</label>
      <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Tariff Amount"
                id="room_tariff_price" name="room_tariff_price" value="<?php echo $total_rate;?>">
    </div>
    <div class="form-group col-sm-3">
      <label for="checkin" style="float:left;">Taxes</label>
      <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Taxes" id="total_tax_edit"
                name="total_tax_edit" value="<?php echo $tax_value;?>">
    </div>
    <div class="form-group col-sm-3">
      <label for="checkin" style="float:left;">Tota1</label>
      <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Total"
                id="net_booking_amount_edit" name="net_booking_amount_edit" value="<?php echo $Addtotal;?>">
    </div>
    
  </div>
  <div style="padding : 5px 10px; display : flex!important; align-items : center!important;"> </div>
  <div class="" style="padding : 15px; display : flex; justify-content: flex-end;">
    <button class="btn n-btn" onclick="savePostChargesSingleForm();" type="button" style="padding: 7px 20px!important; ">Save</button>
    &nbsp; &nbsp;
   <button type="button" class="btn n-btn" data-dismiss="modal" style="padding: 7px 20px!important; ">Close</button>

  </div>
  </div>
</form>
<script> 
function savePostChargesSingleForm(){

	var id_mst_hotels = $("#id_mst_hotels_new").val();
	
	var res_bookingStatus_new = $("#res_bookingStatus_new").val();
	var id_mst_guest_form = $("#id_mst_guest_form").val();
	
	

   
  // if(id_mst_hotels.trim() === "" || id_mst_company_new.trim() === "" || id_mst_company_contacts_new.trim() === "" || res_bookingStatus_new.trim() === "" || id_mst_guest_form.trim() === ""){
	 //   return false;
  // }
   
	var form=$("#savePostChargesDateform");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxsavePostChargesSingleForm.php',

	   data: form.serialize(), 

	   success: function (result) {
		   
		   var response = JSON.parse(result);
alert(response.message);

		  $("#EditReservationModal").modal("hide");
		  InvoiceDetails(response.id_follio);

		},

	  complete: function(){

		$('.loading').hide();

	  }

	});

	return false;

	}

	}
function ChangeHeaderName(value){ 
	if(value=='1'){
		
	var filedvalue	='No Of Rooms';
	var Ratefiled	='Rate Per Room Per Day';
	}else if(value=='2'){
		
		
	var filedvalue	='No Of Adults';
	var Ratefiled	='Rate Per Adult Per Day';	
	}else{
		
		var filedvalue	='Nos';
		var Ratefiled	='Rate Per No';
		}
		
		
	$("#changeDynamicField").html(filedvalue);
	$("#changeDynamicRateField").html(Ratefiled);
	
	}
	
	
	
	
$(".datepickercheckin").datepicker({

    format: 'DD-MM-YYYY',
    autoclose: true
})

$('.datepickercheckout').datepicker({
    autoclose: true,
    format: 'DD-MM-YYYY'

})

$('.datepickerList').datepicker({
    autoclose: true,
    format: 'DD-MM-YYYY'

})
$('.pickerdate').datetimepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    minView: 2,
});


function GetDynamicTextBox(uncode, res_date, res_room_type_new, uncodeRoomCode, k, DiffDays, order_by_roomRowCount,roomCount,CategoryCount,res_total) {
    //var res_date = $("#res_date").val();
res_room_type_new='POST';
    var res_tax = $("#res_tax").val();
    var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
    var res_tariff_per_room_inclusive_tax = $("#res_tariff_per_room_inclusive_tax").val();
var additional_description= $("#additional_description").val();

    var Utext = '<table class="table table-hover" style="margin-bottom:none !important;"><tr>';
	
	
	if (k == 0) {
        Utext += '<td style="width: 83px;"><b>Room '+roomCount+'</b></td>';
    } else {
        Utext += '<td  style="width: 83px;"></td>';
    }
    Utext +=
        '<td style="width: 120px;"><input type="text" class="form-control" id="res_date" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][resdate][]" value="' + res_date + '"></td>';
		
		
	 Utext += '<td style="width: 100px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][room_number_id][]" id="room_number_id_' + uncode +'" data-parsley-required  class="form-control room_number_id_' + uncode +
        '" ><option value="0">Any</option><?php echo $ListRoomNo; ?></select></td>';
		
    Utext += '<td style="width: 180px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][ledger_id][]" id="ledger_id[]" data-parsley-required  class="form-control ledger_id_' + uncode +
        '" ><option value="">Select Ledger</option><?php echo $charges; ?></select></td>';
		
Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="additional_description_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][additional_description][]" value="' + additional_description +'"></td>';
		
   
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="res_no_days_id_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][res_no_days_id][]" value="' + res_tax +
        '"></td>';
		
	    Utext += '<td style="width: 100px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][res_unit_id][]" id="res_unit_id[]" class="form-control res_unit_id_' + uncode +
        '" data-parsley-required  ><option value="">Rate Plan</option><?php echo $ChargesUnit; ?></select></td>';
	
		
	 Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="res_no_of_Room_id_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][res_no_of_Room_id][]" value="' + res_tax +
        '"></td>';
 			
		
		
		
		
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" onkeyup="PostChargesTaxCalculation(' +
        uncode + ');" id="tariff_per_room_per_night_' + uncode + '" name="PostChargesDataArray[' + res_room_type_new +
        '][' + uncodeRoomCode + '][tariff_per_room_per_night][]" value="' + res_tariff_per_room_per_night + '"></td>';
		
		
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="perday_tax_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][perday_tax][]" value="' + res_tax +
        '" readonly></td>';
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="tariff_per_room_inclusive_tax_' +
        uncode + '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][tariff_per_room_inclusive_tax][]" value="' + res_tariff_per_room_inclusive_tax + '" onkeyup="PostChargesCalculationInclusiveNew(' +
        uncode + ');"></td>';
		
		
	  Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="total_' +
        uncode + '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][total][]" value="' + res_total + '" readonly></td>';	
		
    Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detail" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][id_reservation_detail][]" value="0">';
		
    Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="order_by_room" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][order_by_room][]" value="' + order_by_roomRowCount + '">';
		
		
	Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="post_charges" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][post_charges][]" value="' + order_by_roomRowCount + '">';
		
		
		
    if (CategoryCount == '1') {
        Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBox(' + uncodeRoomCode +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
    } else {
        Utext += '<td  style="width: 83px;"></td>';
    }
    Utext += '</tr></table>';



    return Utext;
}

function AddTextBox() {
	
	var res_room_type_new = $("#res_room_type_new").val();
	var res_rate_plan_new = $("#res_rate_plan_new").val();
	var res_ledeger = $("#res_ledeger").val();
	 var res_room_no = $("#res_room_number").val();
	 var res_tax = $("#res_tax").val(); 
	 var res_no_days = $("#res_no_days").val();
	  var res_no_of_Room = $("#res_no_of_Room").val();
  if(res_ledeger.trim() === ""){
      document.querySelector(".res_ledeger-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".res_ledeger-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".res_ledeger-error").innerHTML = 
      "";
	   document.querySelector(".res_ledeger-error").style.display = 
      "none";
	   } 
	  
	  
	   	   
   if(res_room_no.trim() === ""){
      document.querySelector(".res_room_number-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".res_room_number-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".res_room_number-error").innerHTML = 
      "";
	   document.querySelector(".res_room_number-error").style.display = 
      "none";
	   }  
	
	   	   
   if (res_tax <= 0) {
    	document.querySelector(".res_tax-error").innerHTML = "This value is required.";
    	document.querySelector(".res_tax-error").style.display = "block";
	} else {
   		document.querySelector(".res_tax-error").innerHTML = "";
    	document.querySelector(".res_tax-error").style.display = "none";
	} 
	
	 if (res_no_days <= 0) {
    	document.querySelector(".res_no_days-error").innerHTML = "This value is required.";
    	document.querySelector(".res_no_days-error").style.display = "block";
	} else {
   		document.querySelector(".res_no_days-error").innerHTML = "";
    	document.querySelector(".res_no_days-error").style.display = "none";
	}
	 if (res_no_of_Room <= 0) {
    	document.querySelector(".res_no_of_Room-error").innerHTML = "This value is required.";
    	document.querySelector(".res_no_of_Room-error").style.display = "block";
	} else {
   		document.querySelector(".res_no_of_Room-error").innerHTML = "";
    	document.querySelector(".res_no_of_Room-error").style.display = "none";
	}
	
	
	   
	 if(res_ledeger.trim() === ""  || res_room_no.trim() === "" || res_tax <= 0  || res_no_days <= 0  || res_no_days <= 0){
	    return false;
   } 
	   
    var add1 = $("#order_by_roomRowCount").val();
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);



    var start = document.getElementById("dateRange").value; //$("#res_checkinDate").datepicker("getDate");
   // var end = document.getElementById("checkout_extend_date").value; //$("#res_checkOutDate").datepicker("getDate");

/*
    var dateSplit = start.split('-');
    var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];

    var dateSplit1 = end.split('-');
    var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];
*/


var res_unit = $("#res_unit").val();
var res_no_of_Room = $("#res_no_of_Room").val();
var res_total = $("#res_total").val();

 			
            var res_unit = $("#res_unit").val();
           
           
           

if(res_unit=='1'){
	
	var DiffDays =1;
	// var DiffDays =res_no_of_Room;
	
	var res_total =  (res_total);
	// res_no_of_Room=1;
	}else{
		
		var DiffDays =1;
		
		
		}

    var res_no_days = $("#res_no_days").val();

   /* var date12 = new Date(currentDate);
    var date22 = new Date(currentDate1);
    var diffTime = Math.abs(date22 - date12);
    var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    days = DiffDays;*/
days = DiffDays;
    var DiffDays = Math.round(days);
    var uncodeRoomCode = Math.floor(Math.random() * 1000) + 5;
	var roomCount=0;
    var CategoryCount=0;
		var div2 = document.createElement('DIV');
        div2.setAttribute('id', uncodeRoomCode);
		CategoryCount =CategoryCount +  Number(1); 
		// DiffDays = 1;
	for (var q = 0; q < DiffDays; q++) {
		 roomCount =roomCount +  Number(1); 
        var loopDate = start;
       // var div2 = document.createElement('DIV');
       // div2.setAttribute('id', uncodeRoomCode);
		
		
        for (var k = 0; k <1 ; k++) {

          
           
            var uncode = Math.floor(Math.random() * 500) + 1; //Math.floor(Math.random() * 15);


            var div = document.createElement('DIV');
            div.setAttribute('id', uncode);
            div.innerHTML = GetDynamicTextBox(uncode, loopDate, res_room_type_new, uncodeRoomCode, k, DiffDays,
                order_by_roomRowCount,roomCount,CategoryCount,res_total);
				
				CategoryCount ='5';
            //div2.innerHTML=div.innerHTML
            //    var Datas	=	div2.append(div);
            div2.append(div);
            //div2.appendChild(div)
            //document.getElementById("TextBoxContainerForm").div2.appendChild(div);

            $('#TextBoxContainerForm').append(div2);
            document.getElementById(uncodeRoomCode).style.borderBottom = "solid #7FB3E0";

            $(".ledger_id_" + uncode).val(res_ledeger).change();
            $(".res_unit_id_" + uncode).val(res_unit).change();

            $(".room_number_id_" + uncode).val(res_room_no).change();
            $("#res_no_of_Room_id_" + uncode).val(res_no_of_Room).change();
            $("#res_no_of_Room_id_" + uncode).val(res_no_of_Room).change();
            $("#res_no_days_id_" + uncode).val(res_no_days).change();




            //start = start.setDate(start.getDate() + 1);

            //loopDate = moment(loopDate, "DD-MM-YYYY").add(1, 'days'); //moment(loopDate).add(1, 'days');
            //var new_date = moment(loopDate, "DD-MM-YYYY");
           //// var loopDate = new_date.add(1, 'days').format('DD-MM-YYYY');
        }
			

        $('#order_by_roomRowCount').val(order_by_roomRowCount);
		order_by_roomRowCount=Number(order_by_roomRowCount) +  Number(add);
        //alert(loopDate)
    } //div2.innerHTML =Datas;

    TotoalTarffiData(); 
	$("#res_tariff_per_room_per_night").val('0');
	$("#res_tariff_per_room_inclusive_tax").val('0');
	$("#additional_description").val('');
	
	$("#res_total").val('0')
	$("#res_tax").val('0')
	
	
	
}

function TotoalTarffiData() {


    $.ajax({
        url: "ajax/ajaxDataSum.php",
        data: $("#savePostChargesDateform").serialize(),
        type: "POST",
        success: function(result) {
            // $("#reservationModal").modal("hide");

            data = JSON.parse(result);
            $("#room_tariff_price").val(data.tariff_per_room_per_night);
            $("#total_tax_edit").val(data.perday_tax);
            $("#net_booking_amount_edit").val(data.tariff_per_room_inclusive_tax);
            $("#balance_edit").val(data.Balance);

        }
    });

}



function RemoveTextBoxEdit(uncode) {



    RemoveDetailRecord(uncode);





}




function RemoveTextBox(uncode) {





    const parentElement = document.getElementById('TextBoxContainerForm');

    // Get the child element
    const childElement = document.getElementById(uncode);

    // Check if both parent and child elements exist
    if (parentElement && childElement) {
        // Remove the child element from the parent
        parentElement.removeChild(childElement);
        TotoalTarffiData();
    } else {
        console.log('Parent or child element not found.');
    }
}

function selectres_ledeger() {

    $("#res_ledeger option:selected").attr("selected", "selected");


}

function selectRateType() {
    $("#res_rate_plan_new option:selected").attr("selected", "selected");
}

function selectAdultPerRoom() {
    $("#res_adult_per_room option:selected").attr("selected", "selected");
}

function selectBelow5Years() {
    $("#res_child_below_5_year option:selected").attr("selected", "selected");
}

function selectAbove5Years() {
    $("#res_child_above_5_year option:selected").attr("selected", "selected");
}



function LoadRoomType(id_hotel) {

    $.ajax({

        type: "GET",

        url: 'ajax/ajaxGetRoomType.php',

        data: 'id_hotel=' + id_hotel,

        success: function(result) {

            $('#res_room_type_new').empty();

            $('#res_room_type_new').html(result);



        }

    });

}



function RemoveDetailRecord(uncode) {
    //serializeArray();
    bootbox.confirm({
        title: "Remove",
        message: "Do you want to Remove this Room Type?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel'
            },
            confirm: {
                label: '<i class="fa fa-check"></i> Confirm'
            }
        },
        callback: function(result) {
            if (result == true) {
                //var myControls = saveReservationDateform.elements['p_id[]'];
                //$("#saveReservationDateform").serializearray()
                var id_reservation_detailArray = $("#id_reservation_detailArray_" + uncode).serialize();
                $.ajax({

                    type: "GET",

                    url: 'ajax/ajaxPostChargesRemoveDetailRecord.php',

                    data: 'uncode=' + uncode + '&' + id_reservation_detailArray,

                    success: function(result) {

                        const parentElement = document.getElementById(
                            'TextBoxContainerFormEdit');

                        // Get the child element
                        const childElement = document.getElementById(uncode);

                        // Check if both parent and child elements exist
                        if (parentElement && childElement) {
                            // Remove the child element from the parent
                            parentElement.removeChild(childElement);




                            TotoalTarffiData();
                        } else {
                            console.log('Parent or child element not found.');
                        }



                    }

                });
            }
        }
    });

}
	
function selectRateInclusivePlan(plan)
{ 

	    $.ajax({
		url: "ajax/ajaxCheckPlanType.php",
		type: 'POST',
		data: { id : plan},
		dataType: "JSON",
		success: function(data) {
			var planInclusive =data; 
			//$('#res_bookerName').html(data); 
			 if(planInclusive=='1'){  //1 For Inclusive Plan
		  document.getElementById("res_tariff_per_room_per_night").readOnly = true;
	 document.getElementById("res_tariff_per_room_inclusive_tax").readOnly = false;
	 jQuery('#night'+matches[0]).hide();
	 jQuery('#itform'+matches[0]).hide();
    $("#tariffperroom"+matches[0]).css("width", "90px");
    $("#tariffperroomtax"+matches[0]).css("width", "90px");
	document.getElementById("plantype"+matches[0]).value = 0;
	id_get_night('#night'+matches[0]);
		  }else{
			   document.getElementById("res_tariff_per_room_per_night").readOnly = false;
	 document.getElementById("res_tariff_per_room_inclusive_tax").readOnly = true;
	 jQuery('#night'+matches[0]).hide();
	 jQuery('#itform'+matches[0]).hide();
    $("#tariffperroom"+matches[0]).css("width", "90px");
    $("#tariffperroomtax"+matches[0]).css("width", "90px");
	document.getElementById("plantype"+matches[0]).value = 0;
	id_get_night('#night'+matches[0]);
			  }
		}
    });
}

function PostChargesCalculationInclusiveNew(uncode){
	
	
	
	var res_ledeger = $("#res_ledeger").val();
	if(uncode==''){
		
	var res_tariff_per_room_per_night = $("#res_tariff_per_room_inclusive_tax").val();
	var res_no_days = $("#res_no_days").val();
	var res_no_of_Room = $("#res_no_of_Room").val();
	}else{
		//alert(uncode);tariff_per_room_inclusive_tax_51
		var res_tariff_per_room_per_night = $("#tariff_per_room_inclusive_tax_"+uncode).val();
		var res_no_days = $("#res_no_days_id_"+uncode).val();
	var res_no_of_Room = $("#res_no_days_id_"+uncode).val();
		
		
		}
	
			//alert('LOad');
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxNewPostChargesInclusiveCalculation.php',
		  data: {"res_tariff_per_room_per_night" : res_tariff_per_room_per_night,"uncode":uncode,"res_ledeger":res_ledeger,"res_no_days":res_no_days,"res_no_of_Room":res_no_of_Room},
		   success: function (result) {				
		  
					
					result = JSON.parse(result);
					
					if(result.uncode==''){//res_tariff_per_room_inclusive_tax

					$("#res_tax").val(result.total_taxes);
					$("#res_tariff_per_room_per_night").val(result.Inctotal);
					$("#res_total").val(result.total);
					}else{
						$("#perday_tax_"+result.uncode).val(result.total_taxes);
					$("#tariff_per_room_per_night_"+result.uncode).val(result.Inctotal);
					$("#total_"+result.uncode).val(result.total);
					TotoalTarffiData();
						}
					
				
				
						
			}
			
		})
		 


 
	
}

function PostChargesTaxCalculation(uncode){
	
	
	var res_ledeger = $("#res_ledeger").val();
	if(uncode==''){
		
	var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
	var res_no_days = $("#res_no_days").val();
	var res_no_of_Room = $("#res_no_of_Room").val();
	}else{
		var res_tariff_per_room_per_night = $("#tariff_per_room_per_night_"+uncode).val();
		var res_no_days = $("#res_no_days_id_"+uncode).val();
		var res_no_of_Room = $("#res_no_days_id_"+uncode).val();
		
		}
	
	
			
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxPostChargesTaxCalculation.php',
		   data: {"res_tariff_per_room_per_night" : res_tariff_per_room_per_night,"uncode":uncode,"res_ledeger":res_ledeger,"res_no_days":res_no_days,"res_no_of_Room":res_no_of_Room},
		   success: function (result) {				
		  
					
					result = JSON.parse(result);
					
					if(result.uncode==''){

					$("#res_tax").val(result.total_taxes);
					$("#res_tariff_per_room_inclusive_tax").val(result.Inctotal);
					$("#res_total").val(result.total);
					}else{
						$("#perday_tax_"+result.uncode).val(result.total_taxes);
						$("#tariff_per_room_inclusive_tax_"+result.uncode).val(result.Inctotal);
						$("#total_"+result.uncode).val(result.total);
					TotoalTarffiData();
						}
					
				
				
						
			}
			
		})
		 
 
	
}
</script>