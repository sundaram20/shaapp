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
	position: absolute;
	top: 50%;
	left: 50%;
	transform: translate(-50%, -50%);
}
#EditReservationModal {
	padding: 0px !important;
	min-height: 50vh !important;
}
#EditReservationModal .modal-content {
	min-height: 50vh !important;
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
	$BookingDate =date('d-m-Y',strtotime($row->doc_date));
	
	
	$room_tariff_price	= $row->sub_total;
	$discount	=	$row->discount;
	$total_addon_price	=$row->total_addon_price;
	$total_tax=$row->total_tax;
	$amount_received	=	$row->amount_received;
	$net_booking_amount	=	$row->net_booking_amount;
	$balance	= $row->balance;
	$BookingMdoc_no =$row->mdoc_no;
	 
	 
$selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '".$row->id_mst_hotels."' " ;
$resneww = mysqli_query($connNew,$selectneww);
$dataArr='';
	
	
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
				//$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
				}	
			}  
		//room type========================
		$ReservationTitle	='Guest</span>';
			
		}else{}
		$id_mst_guest	=	selectColumn("fo_folio",'id_mst_guest'," WHERE `id` = '".$_REQUEST['id_folio']."'");
		
		
		
		
		
	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' GROUP BY order_by_room  ORDER BY id_mst_room_types,order_by_room, dated  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
		$Arrayroom_no=array();
		
		$folio_mdoc_no	=	selectColumn("fo_folio",'mdoc_no'," WHERE `id` = '".$_REQUEST['id_folio']."'");
			$Arrayroom_no['000_'.$id_mst_guest.'_000']=$folio_mdoc_no.' - Folio Owner';		
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$Arrayroom_no[$rowOrderDetail->id_mst_room_no_allocation.'_'.$rowOrderDetail->id_mst_guest.'_'.$rowOrderDetail->order_by_room]	=	'Room No : '.selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				}
		}
		
			
		
		
		
		
		
		
		
		
		
?>

<form id="saveReservationDateform" method="post" class="saveReservationDateform" data-parsley-validate
    autocomplete="off">
  <input type="hidden" name="id_folio" id="id_folio"
        value="<?php echo addslashes($_REQUEST['id_folio']);?>">
  <input type="hidden" name="editid" id="editid"
        value="<?php echo addslashes(encryptor('decrypt',$_REQUEST['id']));?>">
  <div class="box-header with-border">
    <h3 class="box-title"><?php echo $ReservationTitle;?> </h3>
    <div class="box-tools pull-right">
      <button type="button" class="viewincPopUp_close btn btn-box-tool" data-dismiss="modal"><i
                    class="fa fa-times"></i></button>
    </div>
  </div>
  <!-- <div class="form-group col-sm-12" style="background-color:#3C8DBC; color:#fff;"> </div> -->
  <div style="display : flex!important; flex-direction : column!important;">
    <div style="padding : 5px 10px;">
      <div class="form-group col-sm-2">
        <label for="checkout" style="float:left;">Hotel Name</label>
        <?php 
				 
				    $categoryDropDown = '<select class="form-control select2" name="id_mst_hotels_new" id="id_mst_hotels_new" onchange="LoadRoomType(this.value)" >

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
      <div class="form-group col-sm-2">
        <label for="checkin" style="float:left;">Folio Number</label>
        <input type="text" class="form-control" id="foli" name="foli"
                    value="<?php echo $folio_mdoc_no; ?>" readonly="readonly">
      </div>
		
		<?php
		
		$decrypted_id = addslashes(encryptor('decrypt', $_REQUEST['id']));
		$folio_id = addslashes($_REQUEST['id_folio']);
		
		$id_owner_room = selectColumn('fo_bill', 'id_owner_room', "WHERE id_reservations = '$decrypted_id' AND id_fo_folio = '$folio_id'");
		
		$id_folio_owner = selectColumn('fo_reservations_details', 'id_mst_guest', "WHERE id_fo_reservations = '$decrypted_id' AND id_mst_room_no_reserved='$id_owner_room' AND id_fo_folio = '$folio_id'");
		
		$id_guest = selectColumn('fo_reservations', 'id_mst_guest', "WHERE id = '$decrypted_id'");
		
		$target_id = (!empty($id_folio_owner)) ? $id_folio_owner : $id_guest;
		
		if($_SESSION['shop_code']=='deo_demo' || $_SESSION['shop_code']=='TIG'){
		?>
		
<div class="form-group" style="margin:0; flex:0 0 auto;">
          <label style="display:block; margin-bottom:4px; visibility:hidden;">Action</label>
          <button
            type="button"
            class="btn btn-default"
            style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border:1px solid #ccc; border-radius:4px; background:#fff; color:#333; font-size:13px; cursor:pointer; white-space:nowrap; transition:background 0.15s, color 0.15s;"
            title="Print Guest Card"
            onmouseover="this.style.background='#f0f4ff';this.style.color='#1a56db';"
            onmouseout="this.style.background='#fff';this.style.color='#333';"
            onclick="window.open('../master/guestCard1.php?gId=<?php echo encryptor('encrypt', $target_id); ?>&resId=<?php echo encryptor('encrypt', $decrypted_id);?>&folioId=<?php echo encryptor('encrypt',$folio_id); ?>&action=change&page=<?php echo $_REQUEST['page']; ?>', '_blank')">
            <i class="fa fa-print" style="font-size:13px;"></i>
            <span>Print GRC</span>
          </button>
        </div>
		<?php }; ?>
		
    </div>
  </div>
  
  <!-- tabel feild form ends -->
  
  <?php ?>
  
</form>

<!-----------Guest Room Wise -Start------------------------->



<br />
<!-------FOLIO GUEST-- END------------------------------------------>























<div class="box-body table-responsive" style="margin-top: -20px; max-height: 400px">
  <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%">
    <thead>
      <tr>
      
        <th style="width:120px;">Room Type</th>
        <th style="width:80px;">Room No</th>
       <th style="width:250px;">Room Guest</th>
        <th style="width:390px;">Sharer Guest </th>
        <!--<th style="width:150px;">Id Proof Details</th>-->
        
        
                           </tr>
    </thead>
    <tbody>
    
    <?php $sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' GROUP BY order_by_room  ORDER BY id_mst_room_types,order_by_room, dated  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			//$rowOrderDetail->id_mst_room_types;
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){ 
				$Roomtype	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$room_no	=	selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				//$Guestname	=	selectColumn(TBL_GUEST,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
				$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and id='".$rowOrderDetail->id_mst_guest."'";
            
            $query=mysqli_query($connNew, $SQL);
            
            
            
              $resultCat=mysqli_fetch_assoc($query);
				  
				  $Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$resultCat['id_mst_attributes_title']."'"); 				
				  if($resultCat['proof_type']=='1'){ //Voter Id
					$ProofDetail='<br/><b>Voter Id : </b>'.$resultCat['voter_no'];
		  } 
		  elseif($resultCat['proof_type']=='2'){ //Adhar
			$ProofDetail='<br/><b>Adhar : </b>'.$resultCat['adhar_no'];
		  } 
		  elseif($resultCat['proof_type']=='3'){//Passport
			$ProofDetail='<br/><b>Passport  : </b> No : '.$resultCat['passport_no'].' <br/><b>Authority:</b> '.$resultCat['authority'] .'<br><b>Passport expiry date: </b>'.date('d-m-Y',strtotime($resultCat['passport_expiry_date']) ).' <br/><b>Visa Expiry Date: </b>'.date('d-m-Y',strtotime($resultCat['visa_expiry_date'])).'<br><b>C Form Expiry Date: </b>'.date('d-m-Y',strtotime($resultCat['cform_expiry_date']))   ;
		  } else{
			$ProofDetail='-';
		  }  
		  

?>
            <tr>
      
        <td><?php echo $Roomtype;?></td>
        <td><?php echo $room_no; 
		
		$id_fo_bill	=	selectColumn("fo_folio",'id_fo_bill'," WHERE `id` = '".$_REQUEST['id_folio']."'");
		$id_owner_room = selectColumn('fo_bill','id_owner_room'," WHERE `id` = '".$id_fo_bill."'");
		if($id_owner_room ==$rowOrderDetail->id_mst_room_no_allocation){
			echo ' &nbsp;<a class="showSingle"><span class="label label-success">Folio Owner</span></a>';
			
			
		}?></td>
        <td>
			
		<span id="mainGuestguestroomorder_<?php echo $rowOrderDetail->order_by_room; ?>" class="font-semibold">
		<?php echo $resultCat['guest_reg_no'] . ' -<b> 👤 : </b>'.ucfirst($Title).'. '.ucfirst($resultCat['first_name']).' '.ucfirst($resultCat['last_name']).'
		 <br/><b> Email :</b> '.$resultCat['email'].' <br/><b> 📞 : </b>'.$resultCat['primary_mobile'];
		
		//Id Proof Details
		echo $ProofDetail; 
		
		?></span>
     
    <!-- <div class="input-group-addon"  style="width: auto;
    border: 1px solid #fefefe;float:right;"> 
    <a href="javascript:void(0);" style="color:black;" id="res_guestAddId" onclick="GetEditGuestDetail(<?php echo $resultCat['id'].','. addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',1,'.$_REQUEST['id_folio']; ?>);"><i class="fa fa-pencil"></i> </a> </div>
     
     <div class="input-group-addon"  style="width: auto;
    border: 1px solid #fefefe;float:right;"> 
    <a href="javascript:void(0);" style="color:black;" id="res_guestAddId" onclick="GetAddNewSharedGuestDetail('',<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',1,'.$_REQUEST['id_folio']; ?>);">Change </a> </div>-->
     
     		 <div class="flex space-x-2">
    
    <button class="text-gray-700 hover:text-gray-900" title="Change Room Guest" id="res_guestAddId" onclick="GetAddNewSharedGuestDetail('',<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',1,'.$_REQUEST['id_folio']; ?>);">Change Guest</button>
    <button class="text-blue-600 hover:text-blue-800" title="Edit Room Guest"  id="res_guestAddId" onclick="GetEditGuestDetail(<?php echo $resultCat['id'].','. addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',1,'.$_REQUEST['id_folio']; ?>);"><i class="fa fa-pencil"></i>  Edit</button>
				  
				 
				 <button class="text-blue-600 hover:text-blue-800" title="Other Details"  id="btn_other_detail" onclick="GetOtherGuestDetails(<?php echo $resultCat['id'].','. addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',1,'.$_REQUEST['id_folio']; ?>);"><i class="fa fa-pencil"></i>Other details</button>
				 
				
				 
  </div> 
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     
     </td>
      <td >
     <span id="guestroomorder_<?php echo $rowOrderDetail->order_by_room; ?>"> 
	 
	 <table id="foliotable" class="table table-bordered table-striped datatable" cellspacing="0" width="100%" style="    background-color: #F5F5F5;">
    <thead>
      <?php 
	  
	  if($rowOrderDetail->id_shared_guest!=''){
		 $id_shared_guest	= explode(',',$rowOrderDetail->id_shared_guest);
		 foreach($id_shared_guest as $sharecount=>$shareGuestid){
			 
			$SQL_ShareGuest = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and id='".$shareGuestid."'";
            
            $query_ShareGuest=mysqli_query($connNew, $SQL_ShareGuest);
            
			  $ShareGuestCheckout = "select *  from fo_shared_guest where shared_guest_status='1' and `id_mst_shops` = '".addslashes($_SESSION['shop'])."' and id_fo_folio_to='".$_REQUEST['id_folio']."' and id_shared_guest='".$shareGuestid."'";
            
            $queryShareGuestCheckout =mysqli_query($connNew, $ShareGuestCheckout);
			$checkoutNumRows =mysqli_num_rows($queryShareGuestCheckout);
            ?>
		<?php if($checkoutNumRows==1){?>
		<?php $csscolor="color: red"; }else{$csscolor="";
			}?>
            <tr><td style="<?php echo $csscolor;?>"><?php
            
              $result_ShareGuest=mysqli_fetch_assoc($query_ShareGuest);
			   $Title_ShareGuest=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$result_ShareGuest['id_mst_attributes_title']."'"); 	
			  ?>
           
               <?php 
			   echo $result_ShareGuest['guest_reg_no'] . ' - Name : '.ucfirst($Title_ShareGuest).'. '.ucfirst($result_ShareGuest['first_name']).' '.ucfirst($result_ShareGuest['last_name']).' |  Mobile : '.$result_ShareGuest['primary_mobile'];
			   ?></td>
				 <td class="text-center" style="width:273px;">
					 <?php if($checkoutNumRows==0){?>
               <!-- <button class="btn btn-sm btn-outline-danger" onclick="removeGuestDetail(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id']; ?>)">
                     <i class="fa fa-trash"></i>
                </button>
					 
                <button class="btn btn-sm btn-secondary" onclick="guestTransferSharedToMain(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">
                    <i class="fas fa-sign-out-alt"></i>Room Owner 
                </button>
                <button class="btn btn-sm btn-primary" onclick="promptCheckinTimeAndCheckout(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">
                    <i class="fas fa-sign-out-alt"></i> Pax Checkout
                </button>-->
					 
					 <div class="flex space-x-2">
    <button class="text-red-600 hover:text-red-800" title="Remove Guest" onclick="removeGuestDetail(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">🗑</button>
    <button class="text-gray-700 hover:text-gray-900" title="Make Room Owner" onclick="guestTransferSharedToMain(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">🏠 Room Owner</button>
    <button class="text-blue-600 hover:text-blue-800" title="Pax Checkout"  onclick="promptCheckinTimeAndCheckout(<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.','.$result_ShareGuest['id'].','.$_REQUEST['id_folio']; ?>)">📤 Pax Checkout</button>
  </div> 
					 
					 <?php } ?>
            </td>
            </tr>
		
		
		
		<?php 
		 }
			 } 
		  
		  
	  
	  ?></tr></thead></table>
       </span>     
     <!-- <div class="input-group-addon"  style="width: auto;
    border: 1px solid #fefefe;float:right;"> 
    <a href="javascript:void(0);" style="color:black;"  class="text-blue-600 hover:text-blue-800" id="res_guestAddId" onclick="GetAddNewSharedGuestDetail('',<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',2,'.$_REQUEST['id_folio']; ?>);">➕ Add Sharer Guest  </a> </div>-->
    <div class="flex space-x-2" style="float:right;">
    
    <button class="text-gray-700 hover:text-gray-900" title="Add Sharer Guest" id="res_guestAddId" onclick="GetAddNewSharedGuestDetail('',<?php echo addslashes(encryptor(decrypt,$_REQUEST['id'])).','.$rowOrderDetail->id_mst_room_no_allocation.','.$rowOrderDetail->order_by_room.','.$room_no.',2,'.$_REQUEST['id_folio']; ?>);"><i class="fas fa-sign-out-alt me-1"></i> Add Sharer Guest</button>
    </div>
    </td>
       <?php /*?> <td><?php echo $ProofDetail;?></td><?php */?>
      
              </tr>
              <?php } } ?>
            
          
              
       
    </tbody>
  </table>

</div>



<br /><br /><br />

