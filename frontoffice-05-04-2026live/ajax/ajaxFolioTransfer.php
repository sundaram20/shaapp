<?php 
include_once("../../config/auto_loader.php");
//debugData($_REQUEST);

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
		$ReservationTitle	='Folio Transfer  </span>';
			
		}else{}
		$id_mst_guest	=	selectColumn("fo_folio",'id_mst_guest'," WHERE `id` = '".$_REQUEST['id_folio']."'");
		
		
		
		
		
	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' GROUP BY order_by_room  ORDER BY id_mst_room_types,order_by_room, dated  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
		$Arrayroom_no=array();
		
		$folio_mdoc_no	=	selectColumn("fo_folio",'mdoc_no'," WHERE `id` = '".$_REQUEST['id_folio']."'");
			$Arrayroom_no['000_'.$id_mst_guest.'_000']=$folio_mdoc_no.' - Folio Guest';		
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					$Arrayroom_no[$rowOrderDetail->id_mst_room_no_allocation.'_'.$rowOrderDetail->id_mst_guest.'_'.$rowOrderDetail->order_by_room]	=	'Room No : '.selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
				}
		}
		
			
		
		
		
		
		
		
		
		
		
?>

<form id="saveReservationDateform" method="post" class="saveReservationDateform" data-parsley-validate
    autocomplete="off">
  <input type="hidden" name="id_folio" id="id_folio"
        value="<?php echo addslashes($_REQUEST['id_folio']);?>">
        
        <input type="hidden" name="folio_split_transfer" id="folio_split_transfer"
        value="<?php echo ($_REQUEST['folio_split']);?>">
        
        
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
    </div>
  </div>
  
  <!-- tabel feild form ends -->
  <div class="box-body table-responsive" style="margin-top: -20px; max-height: 400px">
  
  <div class="col-md-9 col-sm-9 col-xs-9" style="display : flex; flex-direction : column;  ">
         
           <label for="checkin" style="float:left;">Folio Transfer To</label>
       <div class="col-md-6 col-sm- col-xs-6">
                                      <!-- input type="text"  class="form-control" id="datepicker1" value="<?php// echo Date('d-m-Y'); ?>" -->
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            
                                            <select class="form-control first-input select2" style="width:100% !important;" name="transfer_folio_to" id="transfer_folio_to" >
                                                          <option value="0">Select Folio </option>
       <?php  $resCat = mysqli_query($connNew,"SELECT * FROM `fo_folio` WHERE  folio_status='0' and status='1' and  id NOT IN ('".$_REQUEST['id_folio']."')");
															   
			/*"SELECT *,fo.mdoc_no as folio_mdoc_no 
															   FROM `fo_folio` as fo 
															   INNER JOIN fo_bill as bi 
															   ON fo.id=bi.id_fo_folio_to where bi.folio_status='0'"*/												   
															   
	   //selectSql('fo_folio'," where  id_mst_shops='".addslashes($_SESSION['shop'])."' and folio_status='0'  ",' ');
														  
	if(mysqli_num_rows($resCat)){
	while($resultCat = mysqli_fetch_object($resCat)){
			$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$resultCat->id_mst_guest."'");
		$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
	$Firstname	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
	$Lastname	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$resultCat->id_mst_guest."'");
	$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));
	//$id_fo_bill	=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
	
	$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$resultCat->id_fo_bill."'");
	$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
									
			if($resultCat->id == $_REQUEST['ID']){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}								

			echo $categoryDropDown = '<option '.$selected.'  value="'.$resultCat->id.'">'.$resultCat->mdoc_no.'    </option>';
												//}
											  }
											  }?>
                                                        </select>
                                          
                                        </div>   
                                    
       
       
       
      </div>
</div>


<br /><br /><br /><br /><br /><br />


  <?php ?>
  <div class="col-md-9 col-sm-9 col-xs-9" style="padding : 5px 10px; display : flex!important; align-items : center!important;">
  <div class="box-footer"> 
			 				
				

			   
			 
      
    
   
  
  <div class="" style="padding : 15px; display : flex; justify-content: flex-end;">
    <button class="btn n-btn" onclick="updatetransferFolioTo();" type="button" style="padding: 7px 20px!important; ">Transfer </button>
    &nbsp; &nbsp;
    <button type="button" class="btn n-btn" data-dismiss="modal" style="padding: 7px 20px!important; ">Close</button>
  </div></div>
  </div>
</form>

<!-----------Guest Room Wise -Start------------------------->
 <?php 			
											
		$resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='remark_type' ",' ORDER BY `field_value` ');
		  if($db->num_rows2($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){				
				$remark_type .= '<option  value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
			}
		  }
															
                    ?> 


<br />
<!-------FOLIO GUEST-- END------------------------------------------>
























<br /><br /><br />
<script>



function updatetransferFolioTo(){
		
	// var folio_split = $("input[name='folio_split[]']").map(function(){return $(this).val();}).get();
		var transfer_folio_to = $("#transfer_folio_to").val();
		var id_fo_bill = $("#id_fo_bill").val();	  
var id_folio= $("#id_folio").val();	  
 var folio_split= $("#folio_split_transfer").val();	
 	bootbox.confirm({
    title: "Transfer Folio ",
    message: "Do you want to Transfer?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
	$.ajax({
			   type: "GET",
			   url: 'ajax/updatetransferFolioTo.php',
			   data: 'folio_split='+folio_split+'&id_folio='+id_folio+'&id_fo_bill='+id_fo_bill+'&transfer_folio_to='+transfer_folio_to, 
			   success: function (result) {
				   folioDetails();
				   $("#EditReservationModal").modal("hide");
				   //$(".targetDivShowRecheckin").hide();
				  // window.location.href = "onewindow.php";	
				   //alert(result);
				   
				 }
				})
	
		}
 }
				})
		}		

function saveChangeAddRemarks(){

	var id_mst_hotels = $("#id_mst_hotels_new").val();
	var id_mst_company_new = $("#id_mst_company_new").val();
	var id_mst_company_contacts_new = $("#id_mst_company_contacts_new").val();
	var res_bookingStatus_new = $("#res_bookingStatus_new").val();
	var id_mst_guest_form = $("#id_mst_guest_form").val();
	
	

	
   
  
   
	var form=$("#saveReservationDateform");

	if(form.parsley().validate()){

	$('.loading').show(); 

	$.ajax({

	   type: "POST",

	   url: 'ajax/ajaxsaveAddRemarks.php',

	   data: form.serialize(), 

	   success: function (result) {
		   
		var response = JSON.parse(result);
		alert(response.message);
		$("#EditReservationModal").modal("hide");
		
		if(response.id_follio!='0'){
		InvoiceDetails(response.id_follio);   
		}
		   
//alert(result);
		//  $("#EditReservationModal").modal("hide");

		},

	  complete: function(){

		$('.loading').hide();

	  }

	});

	return false;

	}

	}
	

		
	
</script>

 <script>
	function AddRemarksTextBox() {
	
	
	   
    var add1 = 0;
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);




days = DiffDays;
    var DiffDays = Math.round(days);
    var uncodeRoomCode = Math.floor(Math.random() * 1000) + 5;
	var roomCount=0;
    var CategoryCount=0;
		
		
       

          
           
            var uncode = Math.floor(Math.random() * 500) + 1; //Math.floor(Math.random() * 15);


           // var div = document.createElement('DIV');
            //div.setAttribute('id', uncode);
            row = GetDynamicRemarksTextBox(uncode, uncodeRoomCode);
				
				CategoryCount ='5';
            

            $('#tableAddRemarkBodyData').append(row);
            document.getElementById(uncodeRoomCode).style.borderBottom = "solid #7FB3E0";

            



        //$('#order_by_roomRowCount').val(order_by_roomRowCount);
		//order_by_roomRowCount=Number(order_by_roomRowCount) +  Number(add);
       
	
	
	
}

	   
	function  GetDynamicRemarksTextBox(uncode,  uncodeRoomCode) { 
    //var res_date = $("#res_date").val();

   
	 var res_room_type_new='0';
	 
	 var cssborder='style="border-top:3px solid #7FB3E0;"';

    var Utext = '<tr data-reservation-id="' + uncodeRoomCode + '" '+cssborder+'>';
	
	
  	
		Utext += '<td style="width: 180px;"><select name="PostChargesDataArray[' + uncodeRoomCode +
        '][id_remarks]" id="id_remarks_' + uncode +
        '" data-parsley-required  class="form-control id_remarks_' + uncode +
        '" ><option value="">Select Remark Type</option><?php echo $remark_type; ?></select></td>';
		
		Utext +=
        '<td ><input type="text" class="form-control" id="res_remark" name="PostChargesDataArray[' + uncodeRoomCode + '][res_remark]" value=""></td>';
	
		 Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBoxRemarks(' + uncodeRoomCode +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
   
    Utext += '</tr>';



    return Utext;
}
     function RemoveTextBoxRemarks(uncode) {



const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());

    /*const parentElement = document.getElementById('TextBoxContainerForm');

    // Get the child element
    const childElement = document.getElementById(uncode);

    // Check if both parent and child elements exist
    if (parentElement && childElement) {
        // Remove the child element from the parent
        parentElement.removeChild(childElement);
        TotoalTarffiData();
    } else {
        console.log('Parent or child element not found.');
    }*/
}  







function RemoveDetailRecordRemarks(uncode) {
    //serializeArray();
    bootbox.confirm({
        title: "Remove",
        message: "Do you want to Remove this Remark?",
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
				
				const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
                $.ajax({

                    type: "GET",

                    url: 'ajax/ajaxRemoveDetailRemarks.php',

                    data: 'uncode=' + uncode + '&' + id_reservation_detailArray,

                    success: function(result) {

                        



                    }

                });
            }
        }
    });

}
	














    </script>