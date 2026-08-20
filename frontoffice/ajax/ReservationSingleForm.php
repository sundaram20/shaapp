<?php include_once("../../config/auto_loader.php"); ?>
<style>
		.ui-datepicker .ui-datepicker-prev,
.ui-datepicker .ui-datepicker-next {
    background: none;
    position: absolute;
    top: 2px;
    width: 20px;
    height: 20px;
}

.ui-datepicker .ui-datepicker-prev:before,
.ui-datepicker .ui-datepicker-next:before {
    content: '';
    display: block;
    width: 0;
    height: 0;
    margin: auto;
    border-style: solid;
    position: relative;
    top: 6px;
}

.ui-datepicker .ui-datepicker-prev:before {
    border-width: 5px 7px 5px 0;
    border-color: transparent black transparent transparent;
    left: 5px;
}

.ui-datepicker .ui-datepicker-next:before {
    border-width: 5px 0 5px 7px;
    border-color: transparent transparent transparent black;
    right: 5px;
}
.disabled-datepicker {
    pointer-events: none; /* Prevents the calendar from opening */
    background-color: #f0f0f0; /* Optional: visually indicate readonly */
}
.error {
	color: #F00;
	font-size: 12px;
}
.deleteBox {
	width: 35px;
	height: 35px;
	background-color: #fff;
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
	max-height: 100% !important;
	overflow:auto;
}
#EditReservationModal .modal-content {
	min-height: 100vh !important;
}
.input-validation-error ~ .select2 .select2-selection__rendered {
	border: 1px solid red;
}
.table-responsive {
	overflow-x: auto;
}
.table {
	width: 100%;
	margin-bottom: 1rem;
	background-color: #fff;
}
@media (max-width: 767px) {
    .table thead {
	    display: none;
    }
    .table, .table tbody, .table tr, .table td {
        display: block;
        width: 100%;
    }
    .table td {
        position: relative;
        padding-left: 50%;
        text-align: right;
    }
    .table td::before {
        content: attr(data-label);
        position: absolute;
        left: 0;
        width: 45%;
        padding-left: 10px;
        font-weight: bold;
        text-align: left;
    }
}
.form-control {
	width: 100%;
	box-sizing: border-box;
}
.deleteBox {
	cursor: pointer;
	color: #dc3545;
}
.col-lg-1, .col-lg-10, .col-lg-11, .col-lg-12, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-md-1, .col-md-10, .col-md-11, .col-md-12, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-sm-1, .col-sm-10, .col-sm-11, .col-sm-12, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-xs-1, .col-xs-10, .col-xs-11, .col-xs-12, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9 {
	position: relative;
	min-height: 1px;
	padding-right: 8px!important;
	padding-left: 8px!important;
}
.form-control {
	font-size: 13px;
	padding: 5px 5px!important;
}
.form-group label {
	display: block;         /* Ensure the label behaves like a block element */
	white-space: nowrap;    /* Prevent the text from wrapping to the next line */
	overflow: hidden;       /* Hide the overflowing text */
	text-overflow: ellipsis; /* Show ellipsis (...) when text overflows */
	width: 100%;
}
#EditReservationModal::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Edge, and Opera */
}
</style>
<script>
//$('.select2').select2();
$('.select2').each(function() {
    $(this).select2({
        dropdownParent: $(this).parent(), // fix select2 search input focus bug
    });
});

// fix select2 bootstrap modal scroll bug
$(document).on('select2:close', '.select2', function(e) {
    var evt = "scroll.select2"
    $(e.target).parents().off(evt)
    $(window).off(evt)
})
</script>
<?php
if ($_REQUEST['BookingType'] == 'Edit') {
    $id_fo_folio_to	= $_REQUEST['id_folio'];
    $EditReservationId = addslashes(encryptor('decrypt',$_REQUEST['id']));
    $HotelSplit = explode('-',$_REQUEST['id_hotel']);

    $hotelname = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$HotelSplit[0]."'");
    $id_mst_hotels = $HotelSplit[0];
    $sqlRes = "SELECT * FROM `".FO_RESERVATIONS."` where `id` = '".addslashes(encryptor('decrypt',$_REQUEST['id']))."'";
	$_SESSION['eId'] = $_REQUEST['eId'];
	$db->query($sqlRes);
	$row = $db->fetch_object();
	$Guestname = selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".$row->id_mst_guest."'");
	$booking_date = date('d-m-Y',strtotime($row->booking_date));
	$Checkindata = date('d-m-Y',strtotime($row->checkin));
	$checkoutDate =	date('d-m-Y',strtotime($row->checkout));
	$BookingDate = date('d-m-Y',strtotime($row->doc_date));
    $room_additional_charges_query = mysqli_query($connNew, "select sum(rate*qty*unit) as rate, sum(tax_value) as tax_value from fo_reservations_addons_details WHERE  `id_fo_reservations` = '".$EditReservationId."'");// `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."'");
	
	// $room_additional_charges_query = mysqli_query($connNew, "select sum(rate) as rate, sum(tax_value) as tax_value from fo_reservations_addons_details WHERE `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."'");
    $room_additionals = mysqli_fetch_object($room_additional_charges_query);
    $room_additional_charges = $room_additionals->rate ?? 0;
    $room_additional_charges_tax_val = $room_additionals->tax_value ?? 0;

    $order_by_room = [];
    $get_order_by_query = mysqli_query($connNew, "select order_by_room from fo_reservations_details WHERE `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."'");
    while ($get_room = mysqli_fetch_object($get_order_by_query)) {
        $order_by_room[] = $get_room->order_by_room;
    }
    $order_by_room_str = implode(',', array_map('intval', $order_by_room));

    $room_tariff_query = mysqli_query($connNew, "select sum(tariff_price_per_day_per_room) as tariff_price_per_day_per_room, sum(tax_per_day_per_room) as tax_per_day_per_room from fo_reservations_details WHERE `id_fo_reservations` = '".$EditReservationId."' and order_by_room IN ($order_by_room_str)");
    $room_tariff_result = mysqli_fetch_object($room_tariff_query);
	$room_tariff_price = $room_tariff_result->tariff_price_per_day_per_room ?? 0;
	$total_tax = ($room_tariff_result->tax_per_day_per_room ?? 0) + $room_additional_charges_tax_val;
	$discount = $row->discount;
	$total_addon_price = $row->total_addon_price;
	$amount_received = $row->amount_received;
	$net_booking_amount	= $room_tariff_price + $total_tax + $room_additional_charges;
    
	
	/*$recepit_query = mysqli_query($connNew, "select sum(amount) as amount from fo_receipt WHERE `id_fo_folio` = '".addslashes($_REQUEST['id_folio'])."'");
    $receipt_result = mysqli_fetch_object($recepit_query);
	*/
	$id_folio = isset($_REQUEST['id_folio']) ? (int)$_REQUEST['id_folio'] : 0;
$EditReservationId = (int)$EditReservationId;

if ($id_folio > 0) {
    $whereCondition = "id_fo_folio = '".$id_folio."'";
	$StyleDisable='pointer-events:none; background:#eee;';
} else {
    $whereCondition = "id_reservation = '".$EditReservationId."'";
}

$sql = "SELECT SUM(amount) AS amount 
        FROM fo_receipt 
        WHERE $whereCondition";

$recepit_query = mysqli_query($connNew, $sql);
$receipt_result = mysqli_fetch_object($recepit_query);
	
	
	$balance = round($net_booking_amount,0) - $receipt_result->amount ?? 0;
	$BookingMdoc_no = $row->mdoc_no;

    $selectneww = "SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '".$row->id_mst_hotels."'";
    $resneww = mysqli_query($connNew,$selectneww);
    $dataArr = '';
	
	while ($rowneww = mysqli_fetch_object($resneww)) {
		$roomno = $rowneww->id_mst_room_types;
        $selectnew = "SELECT * FROM ".TBL_ROOM_TYPE."  where id = $roomno";
		$resnew = mysqli_query($connNew,$selectnew);
		while($rownew = mysqli_fetch_object($resnew)) {
			$romm = $rownew->name;
			$id = $rownew->id;
			if($id == $rowOrderDetail->id_mst_room_types) {
				$selected = "selected";
			} else {
				$selected = "";
			}
			$dataArr.=  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
			//$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
		}	
	}
	//room type========================
	$ReservationTitle = 'Edit <span style="font-weight:bold;">'.$BookingMdoc_no.'</span>';
	$id_mst_hotels = $row->id_mst_hotels;
	} else { //ADD New RESERVATION ======================================================
	    $readonly = 'readonly="readonly"';
	    include_once("../functions/function.php");
 	    $id_doc_type = '801'; //DOCUMENT TYPE FOLIO 803
	    $doc_table_name = FO_RESERVATIONS;
	    $date = date('Y-m-d');
	    $id_subsection = '1';
        $id_shop = $_SESSION['shop'];
	    $docConfig = docTypeConfig($id_doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop);
	    $BookingMdoc_no = addslashes($docConfig['prefix']).addslashes($docConfig['po_no']).addslashes($docConfig['suffix']);
	    $Checkindata = $_REQUEST['checkin'] == '' ? date('d-m-Y') : $_REQUEST['checkin'];
	    $checkoutDate = $_REQUEST['checkout'] == '' ? date('d-m-Y',strtotime("+1 day", strtotime(date('d-m-Y')))) : $_REQUEST['checkout'];

	    $BookingDate = date('d-m-Y');
	    $ReservationTitle	='Reservation Form';
	    $id_mst_hotels	=$_REQUEST['id_hotel'];
	    $id_room = $_REQUEST['id_room']!=''?$_REQUEST['id_room'] : '0';
	?>
    <script> 
	    LoadRoomType(<?php echo $id_mst_hotels;?>,<?php echo $id_room;?>);
	</script>
    <?php
	}
    ?>
<div class="loading" style="
    display:none;
    position:absolute;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(255,255,255,0.7);
    z-index:9999;
    justify-content:center;
    align-items:center;
    flex-direction:column;
">
    <i class="fa fa-spinner fa-spin" style="font-size:40px;color:#3498db;"></i>
    <p style="margin-top:10px;">Saving reservation...</p>
</div>
  <div id="resData" style="position:relative;">
        <form id="saveReservationDateform" method="post" class="saveReservationDateform" data-parsley-validate autocomplete="off">
            <input type="hidden" name="editid" id="editid" value="<?php echo addslashes(encryptor('decrypt',$_REQUEST['id']));?>">
            <input type="hidden" name="id_fo_folio_to" id="id_fo_folio_to" value="<?php echo addslashes($_REQUEST['id_folio']);?>">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo $ReservationTitle;?> </h3>
                <div class="box-tools pull-right">
                    <button type="button" class="viewincPopUp_close btn btn-box-tool" data-dismiss="modal"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div style="display:flex!important; flex-direction:column!important;">
                <div style="padding:5px 10px;">
                    <div class="form-group col-md-2">
                        <label for="checkout" style="float:left;">Hotel Name</label>
                        <?php
				            $categoryDropDown = '<select class="form-control select2" name="id_mst_hotels_new" id="id_mst_hotels_new" onchange="LoadRoomType(this.value,0)">
                                <option value="">Select Hotel</option>';
                                $SQL = "select * from mst_hotels where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";
                                $query = mysqli_query($connNew, $SQL);
                                while ($resultCat = mysqli_fetch_assoc($query)) {
                                    if ($id_mst_hotels == $resultCat['id']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'.$resultCat['name'].'</option>';
                                }
                            echo $categoryDropDown .= '</select>';
                        ?>
                        <p class="error id_mst_hotels_new-error"></p>
                    </div>
                    <div class="form-group col-sm-1">
                        <label for="checkin" style="float:left;">Booking Number</label>
                        <input type="text" class="form-control" id="bookingNo" name="bookingNo" value="<?php echo $BookingMdoc_no; ?>" readonly="readonly" style="">
                    </div>
                    <div class="form-group col-md-1">
                        <label for="checkout" style="float:left;">Booking Date</label>
                        <input type="text" class="form-control pull-right pickerdate" value="<?php echo date('d-m-Y',strtotime($BookingDate));  ?>" readonly="readonly" name="bookingDate" id="bookingDate">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="checkout" style="float:left;">Check In</label>
                        <input type="text" class="form-control  disablecheckin_extend_date" placeholder="Enter checkin Date" id="checkin_extend_date" name="checkin_extend_date" value="<?php echo $Checkindata; ?>">
                    </div>
                    <div class="form-group col-md-2">
                        <label for="checkin" style="float:left;">Check Out</label>
                        <input type="text" class="form-control  disablecheckout_extend_date" placeholder="Enter checkout Date" id="checkout_extend_date" name="checkout_extend_date" value="<?php echo $checkoutDate; ?>">
                    </div>
					
				<script>
					$('#checkin_extend_date, #checkout_extend_date').on('keydown paste', function(e) {
    e.preventDefault();
});
$(function() {
    const dateFormat = 'dd-mm-yy';

    // Initialize datepickers
    $('#checkin_extend_date').datepicker({
        dateFormat: dateFormat,
        onSelect: function() {
            handleDateUpdate();
        }
    });

    $('#checkout_extend_date').datepicker({
        dateFormat: dateFormat
    });

    // Function to calculate and set min checkout date
    function handleDateUpdate() {
        const checkinStr = $('#checkin_extend_date').val();

        if (checkinStr) {
            const checkinDate = $.datepicker.parseDate(dateFormat, checkinStr);
            const nextDay = new Date(checkinDate);
            nextDay.setDate(nextDay.getDate() + 1);

            $('#checkout_extend_date').datepicker('option', 'minDate', nextDay);

            const currentCheckoutStr = $('#checkout_extend_date').val();
            if (!currentCheckoutStr) {
                // Set checkout to next day if empty
                $('#checkout_extend_date').datepicker('setDate', nextDay);
            } else {
                const currentCheckout = $.datepicker.parseDate(dateFormat, currentCheckoutStr);
                if (currentCheckout < nextDay) {
                    $('#checkout_extend_date').datepicker('setDate', nextDay);
                }
            }
        }
    }

    // Trigger logic on page load
    handleDateUpdate();
});
</script>	
                    <div class="form-group col-sm-2" style="display:none;">
                        <label for="checkin" style="float:left;">Bussiness source</label>
                        <?php
                            $categoryDropDown = '<select class="form-control select2" name="id_mst_attributes_company_group" id="id_mst_attributes_company_group">
                                <option value="">Select Company</option>';
                                $SQL = "select *  from ".TBL_ATTRIBUTES." where status = '1' and id_shop = '".addslashes($_SESSION['shop'])."' AND table_name = 'company_group' and field_value != '' order BY field_value LIMIT 0,5";
                                $query = mysqli_query($connNew, $SQL);
                                while ($resultCat=mysqli_fetch_assoc($query)) {
                                    if ($row->id_mst_attributes_company_group == $resultCat['id']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'. $resultCat['field_value'].'</option>';
                                }
                                echo $categoryDropDown .= '</select>';
                        ?>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="checkin" style="float:left;">Company</label>
                        <select class="form-control select2" name="id_mst_company_new" id="id_mst_company_new" onChange="getCompanyContact(this.value,'');">
                            <option value="">Select Company</option>
                            <?php
                                $categoryDropDown = '';
                                $SQL = "select *  from ".TBL_COMPANY." where status ='1' and name != ''";
                                $query = mysqli_query($connNew, $SQL);
                                while ($resultCat = mysqli_fetch_assoc($query)) {
                                    if ($row->id_mst_company == $resultCat['id']) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
                                    $categoryDropDown .= '<option value="'.$resultCat['id'].'"  '.$selected.' >'. $resultCat['name'].'- '. $resultCat['city'].'</option>';
                                }
                                echo $categoryDropDown;
                            ?>
                        </select>
                        <p class="error id_mst_company_new-error"></p>
                    </div>
                    <div class="form-group col-sm-2">
                        <label for="checkin">Booker By</label>
                        <div style="display: flex; align-items: center;">
                            <div class="col-sm-10" style="padding: 0px!important;">
                                <select class="form-control select2" name="id_mst_company_contacts_new" id="id_mst_company_contacts_new" style="flex-grow: 1; margin-right: 5px;">
                                    <option value="">Select Company Contact</option>
                                    <?php 
                                        $categoryDropDown = '';
                                        $SQL = "select *  from ".TBL_COMPANY_CONTACTS." where status='1' and first_name !='' ";
                                        $query = mysqli_query($connNew, $SQL);
                                        while($resultCat = mysqli_fetch_assoc($query)) {
                                            $selected = ($row->id_mst_company_contacts == $resultCat['id']) ? 'selected="selected"' : '';
                                            $categoryDropDown .= '<option value="'.$resultCat['id'].'" '.$selected.' >'. $resultCat['first_name'].' '.$resultCat['last_name'].' - '.$resultCat['email'].' - '.$resultCat['primary_mobile'].'</option>';
                                        }
                                        echo $categoryDropDown;
                                    ?>
                                </select>
                            </div>
                            <div class="col-sm-2" style="padding: 0px!important;">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bookereditModal" style="height: 100%; width: 100%;">+</button>
                        </div>
                    </div>
                    <p class="error id_mst_company_contacts_new-error"></p>
                </div>
                <div class="form-group col-sm-2" style="">
                    <label for="checkin" style="float:left;" readonly="readonly">Guest Name</label>
                    <select class="form-control select2-guest itemGuest" 
        name="id_mst_guest_form" 
        id="id_mst_guest_form">
    <option value="">Select Guest</option>
</select>
                    <p class="error id_mst_guest_form-error"></p>
                </div>
                <div class="input-group-addon form-group col-sm-1" data-toggle="modal" data-target="#guestNewaddeditModal" style="width: auto;border: 1px solid #fefefe; margin-top : 2.5rem;">
                    <a href="javascript:void(0);" style="color:black;" id="res_guestAddId">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="input-group-addon form-group col-sm-1" id="guestEditBtnWrap" style="width: auto;border: 1px solid #fefefe; margin-top : 2.5rem; display:none;" title="Edit Guest">
                    <a href="javascript:void(0);" style="color:black;" id="res_guestEditId" onclick="openGuestEditPage();">
                        <i class="fa fa-pencil"></i>
                    </a>
                </div>

                <div class="input-group-addon form-group col-sm-1" id="guestHistoryBtnWrap" style="width: auto;border: 1px solid #fefefe; margin-top : 2.5rem; display:none;" title="Guest Stay History">
                    <a href="javascript:void(0);" style="color:black;" id="res_guestHistoryId" onclick="openGuestStayHistory();">
                        <i class="fa fa-history"></i>
                    </a>
                </div>
                <div class="form-group col-md-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Booking Status</label>
                    <select class="form-control select2" style="width: 100%;" id="res_bookingStatus_new" name="res_bookingStatus_new" ata-parsley-required-message="Please insert your name">
                        <?php
							$categoryDropDown = '<option value="">Select Booking Status</option>';
							$resCat = selectSql('fo_booking_status'," where status='1'",'');
							if ($db->num_rows2($resCat)) {
								while($resultCat = $db->fetch_object2($resCat)) {
                                    if($_REQUEST['booking_status'] == $resultCat->id) {
                                        $selected = 'selected="selected"';
                                    } elseif($row->booking_status == $resultCat->id) {
                                        $selected = 'selected="selected"';
                                    } else {
                                        $selected = '';
                                    }
									$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
                                }
			                }
							echo $categoryDropDown ;
						?>
                    </select>
                    <p class="error res_bookingStatus_new-error"></p>
                </div>
				  <div class="form-group col-md-2" id="tentative_wrapper">
    <label style="float:left;">Tentative Date</label>

  <input type="text"
       id="tentative_date"
       name="tentative_date"
       class="form-control pull-right pickerdate"
       autocomplete="off"
       value="<?php echo !empty($row->payment_date) ? date('d-m-Y', strtotime($row->payment_date)) : ''; ?>">
</div>
                <?php
					if ($row->res_complimentary_booking == 1)
						$selectedcomplimentary_booking = "selected='selected'";
					else
					$selectedcomplimentary_booking = "";
					?>
                <div class="form-group col-sm-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Complimentary Booking</label>
                    <select class="form-control select2" style="width: 100%;" id="res_complimentary_booking" name="res_complimentary_booking" ata-parsley-required-message="Please Select complimentry booking">
                        <option value="0">No</option>
                        <option value="1" <?php echo $selectedcomplimentary_booking; ?> >Yes</option>
                    </select>
                </div>
                <?php
					if ($row->res_payment_status == 1)
						$selectedres_payment_status = "selected='selected'";
					else
						$selectedres_payment_status = "";
				?>
                <div class="form-group col-sm-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Payment Status</label>
                    <select class="form-control select2" style="width: 100%;" id="res_payment_status" name="res_payment_status">
					<?php					
						$resCat = selectSql(TBL_ATTRIBUTES," where status='1' and id_shop='".addslashes($_SESSION['shop'])."' AND table_name='payment_status' ",' ORDER BY `field_value`');
						if ($db->num_rows2($resCat)) {
							$res_payment_status = '<option value="">Select Payment Status</option>';
							while ($resultCat = $db->fetch_object2($resCat)) {
								if ($row->res_payment_status == $resultCat->id) {
									$selected2 = 'selected="selected"';
								} else {
									$selected2 = '';
								}
								$res_payment_status .= '<option '.$selected2.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
							}
				        }
						echo $res_payment_status;
                    ?>
                    </select>   <p class="error res_payment_status-error"></p>
                </div>
                <div class="form-group col-sm-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Advance Details</label>
                    <textarea style="width: 100% !important;" id="res_payment_instruction" name="res_payment_instruction">
                        <?php echo $row->res_payment_instruction;?>
                    </textarea>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Room Type </th>
                                <th >Plan </th>
                                <th >No. Of Rooms</th>
                                <th >Adult Per Room</th>
                                <th >Child below 5 years</th>
                                <th >Child above 5 years</th>
                                <th>Tariff Per Room per Nights</th>
                                <th >Taxes</th>
                                <th >Tariff Per Room inclusive Taxes</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td id="folio_no">
                                    <select class="form-control select2" name="res_room_type_new" id="res_room_type_new" onchange="selectRoomType();" style="width : 16rem!important;">
                                        <option value="">Select RoomType</option>
                                        <?php echo $dataArr;?>
                                    </select>
                                    <p class="error res_room_type_new-error"></p>
                                </td>
                                <td id="folio_guestname">
                                    <select class="form-control select2" name="res_rate_plan_new" id="res_rate_plan_new" onchange="selectRateType();selectRateInclusivePlan(this.value);">
                                        <?php
                                            $dataArrplan = '';
                                            $dataArrplan =  '<option value="">Select one</option><option value="1">Multiplan</option>';
                                            $selectnewp = "SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM  ".TBL_RATE_PLAN." where status='1' and id_shop='".addslashes($_SESSION['shop'])."'";               
                                            $resnewp = mysqli_query($connNew,$selectnewp);
                                            while ($rownewp = mysqli_fetch_object($resnewp)) {
                                                if ($rownewp->id==$rowOrderDetail->id_fo_rate_plan) {
                                                    $selected = 'selected="selected"';
                                                } else {
                                                    $selected = "";
                                                }
                                                $dataArrplan .= '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
                                                $RatePlanOption .= '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
                                            }
                                            echo $dataArrplan;
                                        ?>
                                    </select>
                                    <p class="error res_rate_plan_new-error"></p>
                                </td>
                                <td id="folio_company">
                                    <select class="form-control select2" name="res_room_no" id="res_room_no">
                                        <?php
                                        for ($i=1; $i<=100; $i++) {
                                            $roomQty .= '<option value="'.$i.'"';
                                            if ($row->room_quantity == $i) {
                                                $roomQty .= 'selected="selected"';
                                            }
                                            $roomQty .='>'.$i.'</option>';

                                        }
                                        echo $roomQty;
                                        ?>
                                    </select>
                                </td>
                                <td id="folio_checkin">
                                    <select class="form-control select2" name="res_adult_per_room" id="res_adult_per_room" onchange="selectAdultPerRoom();">
                                        <option value="2">2</option>
                                        <option value="1">1</option>
                                        <option value="3">3</option>
                                    </select>
                                </td>
                                <td id="folio_checkout">
                                    <select class="form-control select2" name="res_child_below_5_year" id="res_child_below_5_year" onchange="selectBelow5Years();">
                                        <option selected="selected" value="0">0</option>
                                        <option value="1">1</option>
                                        <option class="2">2</option>
                                    </select>
                                </td>
                                <td id="folio_currenttotal">
                                    <select class="form-control select2" name="res_child_above_5_year" id="res_child_above_5_year">
                                        <option selected="selected" value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                    </select>
                                </td>
                                <td id="folio_amtreceived">
                                    <input type="text" class="form-control" name="res_tariff_per_room_per_night" id="res_tariff_per_room_per_night" onkeyup="tariffCalculationNew('');" value="0" >
                                </td>
								<?php
										if($_SESSION['shop_code']=='cch'){
											$disabled= 'disabled';
										}else{
											$disabled= '';
										}
								?>
                                <td id="folio_balance">
                                    <input <?php echo $disabled; ?> type="text" class="form-control" name="res_tax" id="res_tax" value="0" >
                                </td>
                                <td id="foliostatus" class="text-center">
                                    <input <?php echo $disabled; ?> type="text" class="form-control" name="res_tariff_per_room_inclusive_tax" value="0" id="res_tariff_per_room_inclusive_tax"  onkeyup="tariffCalculationInclusiveNew('');">
                                </td>
                                <td>
                                    <div class="input-group-addon" style="width: auto;border: 1px solid #fefefe;" title="add">
                                        <a href="#" onClick="AddTextBox();"> <i class="fas fa-plus"></i></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                </div>
            </div>
            <?php
                $AdultPerRoomOption = '<option value="1">1</option><option value="2">2</option><option value="3">3</option>';
				$childbelowRoomOption = '<option value="0">0</option><option value="1">1</option><option value="2">2</option>';
				$childabovePerRoomOption = '<option value="0">0</option><option value="1">1</option><option value="2">2</option>';
			    $selectneww = "SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '1'";
	            $resneww = mysqli_query($connNew,$selectneww);
                $dataArr='';
	            while ($rowneww = mysqli_fetch_object($resneww)) {
		            $roomno = $rowneww->id_mst_room_types;
                    $selectnew = "SELECT * FROM ".TBL_ROOM_TYPE."  where id = $roomno";
				    $resnew = mysqli_query($connNew,$selectnew);
				        while ($rownew = mysqli_fetch_object($resnew)) {
                            $romm = $rownew->name;
                            $id = $rownew->id;
				            if ($id == $rowOrderDetail->id_mst_room_types) {
						        $selected="selected";
					        } else {
						        $selected="";
					        }
				            $dataArr.= '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
				            //$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
				        }
			        }
                    $dataArr;
            ?>
            <div class="form-group col-12 col-sm-12" style="margin-bottom:1px; display:flex;flex-direction:column;">
                <div class="box-body">
                    <div class="well table-responsive" style="padding:0px 0px;height:auto!important;">
                        <table class="table order-list1 table-hover">
                            <thead id="roomhideandshow" style="">
                                <tr>
                                    <th style="/* width: 150px; */">No Of Room </th>
                                    <th style="width: 142px;">Date </th>
                                    <th style="width: 177px;">Room Type</th>
                                    <th style="width: 113px;">Plan</th>
                                    <th style="width: 93px;">Room No</th>
                                    <th>Adult Per Room</th>
                                    <th style="width: 124px;">Child below 5 years</th>
                                    <th style="width: 131px;">Child above 5 years</th>
                                    <th>Tariff Per Room <br> per Nights</th>
                                    <th style="width: 80px;">Taxes</th>
                                    <th>Tariff Per Room <br> inclusive Taxes</th>
                                    <th>Post Tariff </th>
                                    <th>Action</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <div id="TextBoxContainerFormEdit">
                                <?php
			                        $order_by_roomRowCount = selectColumn(FO_RESERVATIONS_DETAILS,'order_by_room','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'" order by id DESC');
                                ?>
                                <input style="width: 126px;" type="hidden" class="form-control" id="order_by_roomRowCount" name="order_by_roomRowCount" value="<?php echo $order_by_roomRowCount;?>">
                                <?php
			                        $sqlOrderDetailFolio = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' AND  `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."' group by order_by_room");
		                            if (mysqli_num_rows($sqlOrderDetailFolio) > 0) {
				                        while ($sqlOrderDetail_folio = mysqli_fetch_object($sqlOrderDetailFolio)) {
                                            $start = 1;
                                            $sqlOrderDetail = mysqli_query($connNew,"Select * from fo_reservations_details where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' and `order_by_room` = '".$sqlOrderDetail_folio->order_by_room."' ORDER BY order_by_room, dated");
		                                    if (mysqli_num_rows($sqlOrderDetail) > 0) {
				                                while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)) {
				                                    if ($rowOrderDetail->id_mst_room_types!=$AssHotelRooms) {
					                                    $AssRoomtype = '0';
					                                    $AssHotelRooms = $rowOrderDetail->id_mst_room_types;
					                                }
                                                    if($rowOrderDetail->order_by_room != $order_by_room) {
				                                        $AssRoomtype++;
				                                        $Random	= (rand(100,1000));
				                                        $start = 1;
				                                        $rowOrderDetail->order_by_room.'Order'.$RowCount = selectColumn(FO_RESERVATIONS_DETAILS,'count(id)','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'" AND  order_by_room ="'.$rowOrderDetail->order_by_room.'"');
			   		                                    $order_by_room = $rowOrderDetail->order_by_room;
					                                    $trs = 'style="border-top: solid #7FB3E0;"';
					                                    $RecordTD = '<td style="width: 80px;"><B>Room '.$AssRoomtype.'</B></td>';
						                            } else {
                                                        $trs ='';
							                            $RecordTD = '<td style="width: 80px;"></td>';
							                            $start++;
							                        }
				                                    $Array_id_mst_room_types = $rowOrderDetail->id_mst_room_types;
				                                    $id_reservation_detailArray[$Random][] = $rowOrderDetail->id;
				                                    foreach ($id_reservation_detailArray as $datas) {
					                                    $yy = implode(',',$datas);
					                                }
					            ?>
                                <tr <?php echo $trs;?> data-reservation-id="<?php echo $Random;?>">
                                <?php echo $RecordTD; ?>
                                    <td style="width: 120px;">
                                        <input style="width: 126px;" type="text" class="form-control" id="res_date" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][resdate][]" value="<?php echo date('d-m-Y',strtotime($rowOrderDetail->dated));?>" readonly>
                                    </td>
                                    <td style="width: 180px;">
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][room_type_id][]" id="room_type_id[]" data-parsley-required="" class="form-control mst_room_types room_type_id_<?php echo $rowOrderDetail->id;?>" data-hotel_id="<?php echo $id_mst_hotels?>" data-id="<?php echo $rowOrderDetail->id;?>" data-order_by_room="<?php echo $rowOrderDetail->order_by_room;?>" style="<?php echo $StyleDisable; ?>">
                                        <?php
                                            $selectneww="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where id_mst_hotels = '1'";
                                            $resneww = mysqli_query($connNew,$selectneww);
                                            $dataArr = '';
                                            while ($rowneww = mysqli_fetch_object($resneww)) {
                                                $roomno = $rowneww->id_mst_room_types;
                                                $selectnew = "SELECT * FROM ".TBL_ROOM_TYPE."  where id = $roomno";
                                                $resnew = mysqli_query($connNew,$selectnew);
                                                while ($rownew = mysqli_fetch_object($resnew)) {
                                                    $romm = $rownew->name;
                                                    $id = $rownew->id;
                                                    if ($id == $rowOrderDetail->id_mst_room_types) {
                                                        $selected = "selected";
                                                    } else {
                                                        $selected = "";
                                                    }
                                                    $dataArr.=  '<option '.$selected.' value="'.$id.'" >'.$romm.'</option>';
                                                    //$RoomTypeOption .= $dataArr;//'<option '.$selected.' value="'.$id.'" >'.$romm.'---</option>';
                                                }
                                            }
                                            echo $dataArr;
                                        ?>
                                        </select>
                                    </td>
                                    <td style="width: 120px;">
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][rate_plan_id][]" id="rate_plan_id[]" class="form-control rate_plan_id_<?php echo $rowOrderDetail->id;?>" data-parsley-required="">
                                            <option value="">Rate Plan</option>
                                            <?php
                                                $dataArrplan = '';
                                                $dataArrplan = '<option value="">Select one</option><option value="1">Multiplan</option>';
                                                $selectnewp = "SELECT ".TBL_RATE_PLAN.".id,".TBL_RATE_PLAN.".name FROM  ".TBL_RATE_PLAN." where status = '1' and id_shop='".addslashes($_SESSION['shop'])."'";
                                                $resnewp = mysqli_query($connNew,$selectnewp);
                                                while ($rownewp = mysqli_fetch_object($resnewp)) {
                                                    if ($rownewp->id==$rowOrderDetail->id_fo_rate_plan) {
                                                        $selected ='selected="selected"';
                                                    } else {
                                                        $selected = "";
                                                    }
                                                    $dataArrplan .= '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
                                                    $RatePlanOption .= '<option '.$selected.' value="'.$rownewp->id.'" >'.$rownewp->name.'</option>';
                                                }
                                                echo $dataArrplan;
                                            ?>
                                        </select>
                                    </td>
                                    <td style="width: 100px;">
                                    
                                    
                                   
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][room_number][]" id="room_number[]" data-parsley-required="" class="form-control id_mst_room_number room_type_id" data-hotel_id="<?php echo $id_mst_hotels?>" data-id="<?php echo $rowOrderDetail->id;?>" data-order_by_room="<?php echo $rowOrderDetail->order_by_room;?>"  data-room_no="<?php echo $rowOrderDetail->id_mst_room_no_allocation ?>" style="<?php echo $StyleDisable; ?>">
                                            <option value="0">Any</option>
                                            <?php 											
                                                $dataArrplan = '';									
											
											
                                               $selectRoomno = "SELECT * FROM  ".TBL_ROOMNO." where  id_mst_room_types = '" . $rowOrderDetail->id_mst_room_types . "' and status = '1'  and ( room_status='4') OR (id IN('".$rowOrderDetail->id_mst_room_no_allocation."') and room_status='3')";
                                                $resRoomno = mysqli_query($connNew,$selectRoomno);
                                                while ($rowRoomno = mysqli_fetch_object($resRoomno)) {
                                                    if ($rowRoomno->id == $rowOrderDetail->id_mst_room_no_allocation) {
                                                        $selectedRoom = 'selected="selected"';
                                                    } else {
                                                        $selectedRoom ='';
                                                    }
                                                    $dataArrplan .= '<option '.$selectedRoom.' value="'.$rowRoomno->id.'" >'.$rowRoomno->room_no.'</option>';
                                                }
                                                echo $dataArrplan;
                                            ?>
                                        </select>
                                    </td>
                                    <td style="width: 100px;">
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][adult_per_room][]" id="adult_per_room[]" data-parsley-required="" class="form-control adult_per_room_<?php echo $rowOrderDetail->id;?>">
                                        <?php echo $rowOrderDetail->adults_per_room;
                                            if ($rowOrderDetail->adults_per_room == '1') {
                                                $selectedAdultNo1 =  'selected="selected"';
                                            } else {
                                                $selectedAdultNo1 ='';
                                            }
                                            if ($rowOrderDetail->adults_per_room == '2') {
                                                $selectedAdultNo2 =  'selected="selected"';
                                            } else {
                                                $selectedAdultNo2 ='';
                                            }
                                            if ($rowOrderDetail->adults_per_room == '3') {
                                                $selectedAdultNo3 =  'selected="selected"';
                                            } else {
                                                $selectedAdultNo3 ='';
                                            }
            
                                            echo '<option value="1" '.$selectedAdultNo1.'>1</option>
                                            <option value="2" '.$selectedAdultNo2.'>2</option>                				
                                            <option value="3" '.$selectedAdultNo3.'>3</option>';?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][child_below_5_year][]" id="child_below_5_year_<?php echo $rowOrderDetail->id;?>" data-parsley-required="" class="form-control child_below_5_year_<?php echo $rowOrderDetail->id;?>">
                                            <?php 
                                                if ($rowOrderDetail->child_below_5_year == '0') {
                                                    $selectedChildNo1 =  'selected="selected"';
                                                } else {
                                                    $selectedChildNo1 = '';
                                                }
                                                if ($rowOrderDetail->child_below_5_year == '1') {
                                                    $selectedChildNo2 =  'selected="selected"';
                                                } else {
                                                    $selectedChildNo2 ='';
                                                }
                                                if ($rowOrderDetail->child_below_5_year == '2') {
                                                    $selectedChildNo3 =  'selected="selected"';
                                                } else {
                                                    $selectedChildNo3 ='';
                                                }
                                                echo '<option value="0" '.$selectedChildNo1.'>0</option>
                                                <option value="1" '.$selectedChildNo2.'>1</option>
                                                <option value="2" '.$selectedChildNo3.'>2</option>';
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][child_above_5_year][]" id="child_above_5_year_<?php echo $rowOrderDetail->id;?>" data-parsley-required="" class="form-control child_above_5_year_<?php echo $rowOrderDetail->id;?>">
                                        <?php
                                            if ($rowOrderDetail->child_above_5_year == '0') {
                                                $selectedChildNo1 =  'selected="selected"';
                                            } else {
                                                $selectedChildNo1 ='';
                                            }
                                            if ($rowOrderDetail->child_above_5_year == '1') {
                                                $selectedChildNo2 =  'selected="selected"';
                                            } else {
                                                $selectedChildNo2 ='';
                                            }
                                            if ($rowOrderDetail->child_above_5_year == '2') {
                                                $selectedChildNo3 =  'selected="selected"';
                                            } else {
                                                $selectedChildNo3 ='';
                                            }
                                            echo '<option value="0" '.$selectedChildNo1.'>0</option>
                                            <option value="1" '.$selectedChildNo2.'>1</option>
                                            <option value="2" '.$selectedChildNo3.'>2</option>';
                                        ?>
                                        </select>
                                    </td>
                                    <td style="width: 100px;">
                                        <input type="text" class="form-control" onKeyUp="tariffCalculationNew(<?php echo $rowOrderDetail->id;?>);" id="tariff_per_room_per_night_<?php echo $rowOrderDetail->id;?>" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_per_night][]" value="<?php echo $rowOrderDetail->tariff_price_per_day_per_room;?>">
                                    </td>
                                    <td style="width: 100px;">
                                        <input type="text" class="form-control" id="perday_tax_<?php echo $rowOrderDetail->id;?>" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][perday_tax][]" value="<?php echo $rowOrderDetail->tax_per_day_per_room;?>">
                                    </td>
                                    <td style="width: 100px;">
                                        <input type="text" class="form-control" id="tariff_per_room_inclusive_tax_<?php echo $rowOrderDetail->id;?>" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_inclusive_tax][]" value="<?php echo $rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;?>"  onKeyUp="tariffCalculationInclusiveNew(<?php echo $rowOrderDetail->id;?>);" >
                                    </td>
                                    <td style="width: 100px;">
                                        <?php
                                            if ($rowOrderDetail->checkin_status == 1) {
                                                $selectedYes = "selected='selected'";
                                                $selectedNo = '';
                                            } elseif ( $rowOrderDetail->checkin_status==0) {
                                                $selectedNo="selected='selected'";
                                                $selectedYes='';
                                            }
                                        ?>
						<?php if ($id_folio > 0){  ?>
                                        <select id="postcharges_<?php echo $rowOrderDetail->id;?>" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][postcharges][]" class="form-control"   >
                                            <option <?php echo $selectedYes; ?> value="1">Yes</option>
                                            <option <?php echo $selectedNo; ?> value="0">No</option>
                                        </select>
											<?php } ?>
                                        <input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detail" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][id_reservation_detail][]" value="<?php echo $rowOrderDetail->id;?>">
                                        <input style="width: 126px;" type="hidden" class="form-control" id="order_by_room" name="ReservationDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][order_by_room][]" value="<?php echo $rowOrderDetail->order_by_room;?>">
                                        <?php
                                            if ($start == $RowCount) {
                                        ?>
                                        <input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detailArray_<?php echo $Random;?>" name="id_reservation_detailArray_<?php echo $Random;?>" value="<?php echo $yy;?>">
                                        <?php } ?>
                                    </td>
                                    <?php if ($start == 1 && $rowOrderDetail->checkin_status == 0) {?>
                                    <td style="width: 83px;">
                                        <button type="button" value="Remove" onClick="RemoveTextBoxEdit('<?php echo $Random; ?>')" class="deleteBox"> <i class="fas fa-trash"></i></button>
									</td>
                                    <?php } else {?>
                                    <td style="width: 83px;"></td>
                                    <?php } ?>
                                </tr>
                                <?php
                                                }
                                            }
                                        }
                                    }
                                ?>
                                </div>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div style="margin-top:0.5rem!important;padding:5px 10px;">
                <div class="form-group col-md-1">
                    <label for="checkin" style="float:left;">Tariff Amount</label>
                    <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Tariff Amount" id="room_tariff_price" name="room_tariff_price" value="<?php echo $room_tariff_price;?>">
                </div>
                <div class="form-group col-md-1">
                    <label for="checkin" style="float:left;"> Additional Charges</label>
                    <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Additional Charges" id="room_additional_charges" name="room_additional_charges" value="<?php echo $room_additional_charges==''?'0':$room_additional_charges;?>">
                </div>
                <div class="form-group col-md-1">
                    <label for="checkin" style="float:left;">Taxes</label>
                    <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Taxes" id="total_tax_edit" name="total_tax_edit" value="<?php echo $total_tax;?>">
                </div>
                <div class="form-group col-md-1">
                    <label for="checkin" style="float:left;">Tota1</label>
                    <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Total" id="net_booking_amount_edit" name="net_booking_amount_edit" value="<?php echo $net_booking_amount?>">
                </div>
                <div class="form-group col-sm-1">
                    <label for="checkin" style="float:left;">Balance</label>
                    <input type="text" class="form-control " <?php echo $readonly; ?> placeholder="Balance" id="balance_edit" name="balance_edit" value="<?php echo $balance?>">
                </div>
                <div class="form-group col-sm-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Internal Remarks</label>
                    <textarea style="width: 100% !important;height: 3.5rem;" id="res_interenal_remarks" name="res_interenal_remarks">
                    <?php echo $row->res_internal_remarks;?>
                    </textarea>
                </div>
                <div class="form-group col-sm-2">
                    <label for="checkin" style="float:left;" readonly="readonly">Special Notes</label>
                    <textarea style="width: 100% !important;height: 3.5rem;" id="res_special_notes" name="res_special_notes">
                        <?php echo $row->res_special_notes;?>
                    </textarea>
                </div>
				
				
				
                <div class="form-group col-sm-2">
                    <label for="reference" style="float:left;">Reference</label>
                    <input type="text" class="form-control " <?php //echo $readonly; ?> placeholder="Reference" id="reference" name="reference" value="<?php echo $row->reference?>">
                </div>
				
                <div class="col-md-9 col-sm-9 col-xs-9" style="display : flex; flex-direction : column;  border : 1px solid #d2d6de;">
                <h4>Other Charges</h4>
                <div class="" style="display:flex;flex-wrap:wrap;padding:0px 10px;border:1px solid #d2d6de;">
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="well table-responsive" style="padding:0px 0px;height:auto!important;">
                    <table class="table order-list1 table-hover table-striped table-bordered" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ledger</th>
                                <th>Description</th>
                                <th>No of Days</th>
                                <th>Unit</th>
                                <th>Nos</span></th>
                                <th>Rate Per Room Per Day </span></th>
                                <th>Taxes</th>
                                <th>Inclusive of Taxes</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody id="tableOtherChargesBody">
                            <input type="hidden" class="form-control pickerdate" placeholder="Enter checkin Date" id="dateRange" name="dateRange" value="<?php echo $Checkindata;  ?>">
                            <?php
				                $selectnew = "SELECT * FROM ".TBL_CHARGES."  where charges_account='3'";
				                $resnew = mysqli_query($connNew,$selectnew);
				                while ($rownew = mysqli_fetch_object($resnew)) {
					                $chargesName = $rownew->name;
					                $id = $rownew->id;
							        $charges.=  '<option  value="'.$id.'" >'.$chargesName.'</option>';
							    }
				                $charges;
				            ?>
                            </select>
                            
		                    <?php
                            $ChargesUnit ='<option value="1">Per room</option><option value="2">Per Adult</option> <option value="3">Per Nos</option>';?>
                            <style>
                                .thisIsChrgTbl th, td {
                                    font-size : 1.5rem!important;
                                }
                            </style>
                            <?php
			                    $order_by_roomRowCount = selectColumn(FO_RESERVATIONS_DETAILS,'order_by_room','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'"  order by id DESC');
                                $Addtotal =	selectColumn('fo_reservations_addons_details','sum(total)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'"');
                                $total_rate	= selectColumn('fo_reservations_addons_details','sum(rate)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'"');
                                $tax_value = selectColumn('fo_reservations_addons_details','sum(tax_value)','WHERE `id_fo_folio_to` = "'.addslashes($id_fo_folio_to).'"');
                            ?>
                            <input style="width: 126px;" type="hidden" class="form-control" id="order_by_roomRowCount" name="order_by_roomRowCount" value="<?php echo $order_by_roomRowCount;?>">
                            <?php
			                    $start = 1;
			                    $sqlOrderDetail = mysqli_query($connNew,"Select  `fo_reservations_addons_details`.* from `fo_reservations_addons_details` where `id_fo_reservations` = '".addslashes(encryptor(decrypt,$_REQUEST['id']))."' and `id_fo_folio_to` = '".addslashes($_REQUEST['id_folio'])."' ORDER BY  dated");
		                        if (mysqli_num_rows($sqlOrderDetail) > 0) {
				                    while ($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)) {
				                        $AssRoomtype++;
				                        $Random	= (rand(100,1000));
				                        $start = 1;
				                        if ($rowOrderDetail->id_mst_room_types != $AssHotelRooms) {
					                        $AssRoomtype = '0';
					                        $AssHotelRooms = $rowOrderDetail->id_mst_room_types;
					                    }
                                        if($rowOrderDetail->order_by_room != $order_by_room) {
				                            $rowOrderDetail->order_by_room.'Order'.$RowCount = selectColumn(FO_RESERVATIONS_DETAILS,'count(id)','WHERE `id_fo_reservations` = "'.addslashes(encryptor(decrypt,$_REQUEST['id'])).'" and  order_by_room ="'.$rowOrderDetail->order_by_room.'"');
			   		                        $order_by_room = $rowOrderDetail->order_by_room;
			                ?>
                            <div id="<?php echo $Random;?>" style="border-bottom: solid #7FB3E0;">
                            <?php 
						                } else {	
							                $start++;
							            }
				                        $Array_id_mst_room_types = $rowOrderDetail->id_mst_room_types;
				                        $id_reservation_detailArray[$Random][] =$rowOrderDetail->id;
				                        foreach ($id_reservation_detailArray as $datas) {
					                        $yy=implode(',',$datas);
					                    }
					        ?>
                            <input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detail" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][id_reservation_detail][]" value="<?php echo $rowOrderDetail->id;?>">
                            <input style="width: 126px;" type="hidden" class="form-control" id="order_by_room" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][order_by_room][]" value="<?php echo $rowOrderDetail->order_by_room;?>">
                            <tr data-reservation-id="<?php echo $rowOrderDetail->id; ?>">
                                <?php echo $RecordTD2; ?>
                                <td style="width: 120px;">
                                <?php
                                $res_date = date('d-m-Y',strtotime($rowOrderDetail->dated));
                                if ($rowOrderDetail->dated == '' || $rowOrderDetail->dated == '1970-01-01') {
                                    $res_date = date('d-m-Y');
                                }

                                ?>
                                    <input type="text" class="form-control" id="res_date" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][resdate][]" value="<?php echo $res_date;?>" readonly>
                                </td>
                                <td style="width:180px;">
                                    <select name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][room_type_id][]" id="room_type_id[]" data-parsley-required="" class="form-control room_type_id_<?php echo $rowOrderDetail->id;?>"  readonly="readonly">
                                    <?php
                                        $selectnew = "SELECT * FROM ".TBL_CHARGES."  where charges_account='3'";
                                        $resnew = mysqli_query($connNew,$selectnew);
                                        while ($rownew = mysqli_fetch_object($resnew)) {
                                            $chargesName = $rownew->name;
                                            $id = $rownew->id;
                                            if ($id == $rowOrderDetail->id_mst_charges) {
                                                $selected = "selected";
                                            } else {
                                                $selected="";
                                            }
                                            $charges2.=  '<option '.$selected.' value="'.$id.'" >'.$chargesName.'</option>';
                                        }
                                        echo $charges2;
                                    ?>
                                    </select>
                                </td>
                                
                               <td style="width: 100px;">
                               
                                <input type="text" class="form-control" placeholder="Enter Description" id="additional_description_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][additional_description][]" value="<?php echo $rowOrderDetail->additional_description;  ?>" readonly="readonly">
                                </td>
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" onKeyUp="tariffCalculationNew(<?php echo $rowOrderDetail->id;?>);" id="tariff_per_room_per_night_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_per_night][]" value="<?php echo $rowOrderDetail->days;?>"  readonly="readonly">
                                </td>
                                <td style="width: 100px;">
                                    <select name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][res_unit][]" id="res_unit[]" data-parsley-required="" class="form-control res_unit_<?php echo $rowOrderDetail->id;?>"  readonly="readonly">
                                        <?php echo $rowOrderDetail->unit;
                                        if ($rowOrderDetail->unit == 'Per room') {
                                            $selectedAdultNo1 =  'selected="selected"';
                                        } else {
                                            $selectedAdultNo1 ='';
                                        }
                                        if ($rowOrderDetail->unit == 'Per Adult') {
                                            $selectedAdultNo2 =  'selected="selected"';
                                        } else {
                                            $selectedAdultNo2 ='';
                                        }
                                        if ($rowOrderDetail->unit == 'Per Nos') {
                                            $selectedAdultNo3 =  'selected="selected"';
                                        } else {
                                            $selectedAdultNo3 ='';
                                        }
                                        echo '<option value="1" '.$selectedAdultNo1.'>Per room</option>
                                            <option value="2" '.$selectedAdultNo2.'>Per Adult</option>                				
                                            <option value="3" '.$selectedAdultNo3.'>Per Nos</option>';?>
                                    </select>
                                </td>
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" id="res_no_of_Room_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][res_no_of_Room][]" value="<?php echo $rowOrderDetail->qty;?>" readonly>
                                </td>
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" onKeyUp="PostChargesTaxCalculation(<?php echo $rowOrderDetail->id;?>);" id="tariff_per_room_per_night_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_per_night][]" value="<?php echo $rowOrderDetail->rate;?>" readonly="readonly">
                                </td>
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" id="perday_tax_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][perday_tax][]" value="<?php echo $rowOrderDetail->tax_value;?>"  readonly="readonly">
                                </td>   
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" id="tariff_per_room_inclusive_tax_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][tariff_per_room_inclusive_tax][]" value="<?php echo $rowOrderDetail->rate+$rowOrderDetail->tax_value;?>"  readonly="readonly">
                                </td>
                                <td style="width: 100px;">
                                    <input type="text" class="form-control" id="total_<?php echo $rowOrderDetail->id;?>" name="EditPostChargesDataArray[<?php echo $Array_id_mst_room_types; ?>][<?php echo $Random;?>][total][]" value="<?php echo $rowOrderDetail->total;?>"  readonly="readonly">
                                </td>
                                <!--<td style="width: 83px;">
                                    <button type="button" value="Remove" onClick="RemoveTextBoxOtherChargesEdit('<?php echo $rowOrderDetail->id; ?>')" class="deleteBox"><i class="fas fa-trash"></i></button>
                                </td>-->
                            </tr>
                            <?php
                                if($start == $RowCount) {
                            ?>
                            <input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detailArray_<?php echo $Random;?>" name="id_reservation_detailArray_<?php echo $Random;?>" value="<?php echo $yy;?>">
                            <?php } ?>
                            </div>
                            <?php
                                    }
                                }
                            ?>
                        <!-- </div>
                        </div> -->
                        </tbody>
                    </table>
                    <div id="TextBoxContainerForm"></div>
		<?php // if($_SESSION['shop_code']=='demo_pms'){ ?>
                   <!--<div class="input-group-addon" style="border: 1px solid #fefefe;" title="add"><a href="#" onClick="AddOtherChargesTextBox();"> <i class="fas fa-plus"></i> Add Other Charges</a></div>-->
		<?php // }; ?>
                </div>
            </div>
        </div>  
         <?php if($row->date_created){?><br>
         <div class="col-md-9 col-sm-9 col-xs-9" style="display : flex; margin-top:10px;  border : 0px solid #d2d6de;">
                <div class="clearfix"></div>
                <div class="form-group col-sm-3">
                   <label for="last_modified_by">Created By</label>
                  
                   <?php
                  
                    $created_by    = selectColumn('mst_users','name','WHERE id = "'.$row->created_by.'"');
                                      
                    ?>
                   <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($created_by);?>">
                 </div>
                <div class="form-group col-sm-3">
                   <label for="date_created">Date Created</label>
                   <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">

                 </div>
                <div class="form-group col-sm-3">
                   <label for="last_modified">Last Updated</label>
                   <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">
                 </div>
                <div class="form-group col-sm-3">
                   <label for="last_modified_by">Last Updated By</label>
                   <?php 
                    $last_modified_by    = selectColumn('mst_users','name','WHERE id = "'.$row->last_modified_by.'"');
                   
                   ?>
    <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"
      value="<?php echo $last_modified_by;?>">
                 </div>  </div>
                <?php } ?>


                
        <div class="" style="padding : 15px; display : flex; justify-content: flex-end; width : 100%!important;">
            <div>
                <button class="btn n-btn" onclick="saveReservationSingleForm();" type="button" style="padding: 7px 20px!important; ">Save</button>
                &nbsp; &nbsp;
                <button type="button" class="btn n-btn" data-dismiss="modal" style="padding: 7px 20px!important; ">Close</button>
            </div>
        </div>
    </div>
</form>
</div>
<script>
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


function GetDynamicTextBox(uncode, res_date, res_room_type_new, uncodeRoomCode, k, DiffDays, order_by_roomRowCount,roomCount,CategoryCount,groupRowCount) {
    //var res_date = $("#res_date").val();

    var res_tax = $("#res_tax").val();
    var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
    var res_tariff_per_room_inclusive_tax = $("#res_tariff_per_room_inclusive_tax").val();

 if (CategoryCount == '1') {
	 
	 var cssborder='style="border-top:3px solid #7FB3E0;"';
 }else{
	 
	 var cssborder='';
	 }
    var Utext = '<tr data-reservation-id="' + groupRowCount + '" '+cssborder+'>';
	
	
	if (k == 0) {
        Utext += '<td style="width: 80px;"><b>Room '+roomCount+'</b></td>';
    } else {
        Utext += '<td  style="width: 80px;"></td>';
    }
    Utext +=
        '<td style="width: 120px;"><input style="width: 126px;" type="text" class="form-control" id="res_date" name="ReservationDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][resdate][]" value="' + res_date + '"></td>';
		
    Utext += '<td style="width: 180px;"><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][room_type_id][]" id="room_type_id[]" data-parsley-required  class="form-control mst_room_types room_type_id_' + uncode +
        '" ><option value="">Room Type</option><?php echo $dataArr; ?></select></td>';
    Utext += '<td style="width: 120px;"><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][rate_plan_id][]" id="rate_plan_id[]" class="form-control rate_plan_id_' + uncode +
        '" data-parsley-required  ><option value="">Rate Plan</option><?php echo $RatePlanOption; ?></select></td>';

    Utext += '<td style="width: 100px;"><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][room_number][]" id="room_number[]" data-parsley-required  class="form-control id_mst_room_number room_type_id" ><option value="0">Any</option><?php echo $RoomNumberOption2; ?></select></td>';
    Utext += '<td style="width: 100px;"><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][adult_per_room][]" id="adult_per_room[]" data-parsley-required  class="form-control adult_per_room_' +
        uncode + '" ><?php echo $AdultPerRoomOption; ?></select></td>';
    Utext += '<td><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][child_below_5_year][]" id="child_below_5_year[]" data-parsley-required  class="form-control child_below_5_year_' +
        uncode + '" ><?php echo $childbelowRoomOption; ?></select></td>';
    Utext += '<td><select name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][child_above_5_year][]" id="child_above_5_year[]" data-parsley-required  class="form-control child_above_5_year_' +
        uncode + '" ><?php echo $childabovePerRoomOption; ?></select></td>';

    Utext += '<td style="width: 100px;"><input type="text" class="form-control" onkeyup="tariffCalculationNew(' +
        uncode + ');" id="tariff_per_room_per_night_' + uncode + '" name="ReservationDataArray[' + res_room_type_new +
        '][' + uncodeRoomCode + '][tariff_per_room_per_night][]" value="' + res_tariff_per_room_per_night + '"></td>';
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="perday_tax_' + uncode +
        '" name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][perday_tax][]" value="' + res_tax +
        '"></td>';
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="tariff_per_room_inclusive_tax_' +
        uncode + '" name="ReservationDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][tariff_per_room_inclusive_tax][]" value="' + res_tariff_per_room_inclusive_tax + '" onkeyup="tariffCalculationInclusiveNew(' +
        uncode + ');"></td>';
		
		 Utext += '<td  style="width: 100px;">';
		
    Utext +=
        '<input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detail" name="ReservationDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][id_reservation_detail][]" value="0">';
    Utext +=
        '<input style="width: 126px;" type="hidden" class="form-control" id="order_by_room" name="ReservationDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][order_by_room][]" value="' + order_by_roomRowCount + '">';
Utext +=
        '<input style="width: 126px;" type="hidden" class="form-control" id="post_charges" name="ReservationDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][post_charges][]" value="' + order_by_roomRowCount + '">';
		
	Utext += '</td>';	
		
    if (CategoryCount == '1') {
        Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBox(' + groupRowCount +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
    } else {
        Utext += '<td  style="width: 83px;"></td>';
    }
    Utext += '</tr>';



    return Utext;
}


function AddTextBox() {
	
	var res_room_type_new = $("#res_room_type_new").val();
	var res_rate_plan_new = $("#res_rate_plan_new").val();
	//alert(res_room_type_new);
  if(res_room_type_new.trim() === ""){
      document.querySelector(".res_room_type_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".res_room_type_new-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".res_room_type_new-error").innerHTML = 
      "";
	   document.querySelector(".res_room_type_new-error").style.display = 
      "none";
	   } 
	   
	   
	   	   
   if(res_rate_plan_new.trim() === ""){
      document.querySelector(".res_rate_plan_new-error").innerHTML = 
      "This value is required.";
 
      document.querySelector(".res_rate_plan_new-error").style.display = 
      "block";
 
     
   }else{
	    document.querySelector(".res_rate_plan_new-error").innerHTML = 
      "";
	   document.querySelector(".res_rate_plan_new-error").style.display = 
      "none";
	   }  
	   
	if(res_rate_plan_new.trim() === ""  || res_room_type_new.trim() === ""){
	    return false;
   }   
	   
    var add1 = $("#order_by_roomRowCount").val();
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);



    var start = document.getElementById("checkin_extend_date").value; //$("#res_checkinDate").datepicker("getDate");
    var end = document.getElementById("checkout_extend_date").value; //$("#res_checkOutDate").datepicker("getDate");


    var dateSplit = start.split('-');
    var currentDate = dateSplit[2] + '/' + dateSplit[1] + '/' + dateSplit[0];

    var dateSplit1 = end.split('-');
    var currentDate1 = dateSplit1[2] + '/' + dateSplit1[1] + '/' + dateSplit1[0];



    var res_room_no = $("#res_room_no").val();

    var date12 = new Date(currentDate);
    var date22 = new Date(currentDate1);
    var diffTime = Math.abs(date22 - date12);
    var DiffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    days = DiffDays;

    var DiffDays = Math.round(days);
    var uncodeRoomCode = Math.floor(Math.random() * 10000) + 155;
	var roomCount=0;
   
		//alert(DiffDays);
		//alert(res_room_no);
	for (var q = 0; q < res_room_no; q++) {
		 roomCount =roomCount +  Number(1); 
		 var groupRowCount = Math.floor(Math.random() * 15000) + 54;
        var loopDate = start;
       var row;
	   
	    var CategoryCount=0;
	
		//var div2 = document.createElement('DIV');
       // div2.setAttribute('id', uncodeRoomCode);
	   
		CategoryCount =CategoryCount +  Number(1); 
		//var div = document.createElement('TR');
         //   div.setAttribute('id', '');
        for (var k = 0; k < DiffDays; k++) {

          
            var res_room_type_new = $("#res_room_type_new").val();
            var res_rate_plan_new = $("#res_rate_plan_new").val();
            var res_adult_per_room = $("#res_adult_per_room").val();
            var res_child_below_5_year = $("#res_child_below_5_year").val();
            var res_child_above_5_year = $("#res_child_above_5_year").val();
            var uncode = Math.floor(Math.random() * 1000000) + 1 + res_room_type_new; //Math.floor(Math.random() * 15);
			
			
			//alert('RowCount = '+k+new_date);

            
              row = GetDynamicTextBox(uncode, loopDate, res_room_type_new, uncodeRoomCode, k, DiffDays,
                order_by_roomRowCount,roomCount,CategoryCount,groupRowCount);
				
				CategoryCount ='5';
            
		   //$('#TextBoxContainerForm').append(row);
            //document.getElementById(groupRowCount).style.borderBottom = "solid #7FB3E0";
			document.getElementById('tableBody').insertAdjacentHTML('beforeend', row);
            $(".room_type_id_" + uncode).val(res_room_type_new).change();
            $(".rate_plan_id_" + uncode).val(res_rate_plan_new).change();

            $(".adult_per_room_" + uncode).val(res_adult_per_room).change();
            $(".child_below_5_year_" + uncode).val(res_child_below_5_year).change();
            $(".child_above_5_year_" + uncode).val(res_child_above_5_year).change();

 //$('#' + targetRowId).after(newRow);
//$('#' + targetRowId).after

            //start = start.setDate(start.getDate() + 1);

            //loopDate = moment(loopDate, "DD-MM-YYYY").add(1, 'days'); //moment(loopDate).add(1, 'days');
            var new_date = moment(loopDate, "DD-MM-YYYY");
            var loopDate = new_date.add(1, 'days').format('DD-MM-YYYY');
        }
			

        $('#order_by_roomRowCount').val(order_by_roomRowCount);
		order_by_roomRowCount=Number(order_by_roomRowCount) +  Number(add);
        
    }

    TotoalTarffiData();
	checkTableBody();
	$("#res_room_type_new").val("").change();
	$("#res_rate_plan_new").val("").change();
	$("#res_room_no").val("1").change();
	$("#res_adult_per_room").val("1").change();
	$("#res_child_below_5_year").val("0").change();
	$("#res_child_above_5_year").val("0").change();
	$("#res_tariff_per_room_per_night").val("0");
	$("#res_tax").val("0");
	$("#res_tariff_per_room_inclusive_tax").val("0");
	
	
}

function TotoalTarffiData() {


    $.ajax({
        url: "ajax/ajaxDataSum.php",
        data: $("#saveReservationDateform").serialize(),
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





    //const parentElement = document.getElementById('TextBoxContainerForm');

    // Get the child element
   // const childElement = document.getElementById(uncode);
const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
						TotoalTarffiData();
						checkTableBody();
    // Check if both parent and child elements exist
    /*if (parentElement && childElement) {
        // Remove the child element from the parent
        parentElement.removeChild(childElement);
        TotoalTarffiData();
    } else {
        console.log('Parent or child element not found.');
    }*/
}

function selectRoomType() {

    $("#res_room_type_new option:selected").attr("selected", "selected");


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



function LoadRoomType(id_hotel,id_room) { 

    $.ajax({

        type: "GET",

        url: 'ajax/ajaxGetRoomType.php',

        data: 'id_hotel=' + id_hotel+'&id_room='+id_room,

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

                    url: 'ajax/ajaxRemoveDetailRecord.php',

                    data: 'uncode=' + uncode + '&' + id_reservation_detailArray,

                    success: function(result) {
						
						
						
						const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
						TotoalTarffiData();
checkTableBody();
                       



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

function tariffCalculationInclusiveNew(uncode){
	
	
	
	
	if(uncode==''){
	var res_tariff_per_room_per_night = $("#res_tariff_per_room_inclusive_tax").val();
	}else{
		//alert(uncode);tariff_per_room_inclusive_tax_51
		var res_tariff_per_room_per_night = $("#tariff_per_room_inclusive_tax_"+uncode).val();
		
		}
	
			//alert('LOad');
	
		$.ajax({
		   type: "POST",
		   url: 'ajax/ajaxNewTariffExceInclusiveCalculation.php',
		   data: {"res_tariff_per_room_per_night" : res_tariff_per_room_per_night,"uncode":uncode},
		   success: function (result) {				
		  
					
					result = JSON.parse(result);
					
					if(result.uncode==''){//res_tariff_per_room_inclusive_tax

					$("#res_tax").val(result.total_taxes);
					$("#res_tariff_per_room_per_night").val(result.total);
					}else{
						$("#perday_tax_"+result.uncode).val(result.total_taxes);
					$("#tariff_per_room_per_night_"+result.uncode).val(result.total);
					TotoalTarffiData();
						}
					
				
				
						
			}
			
		})
		 
 
	
}

/* 
function checkTableBody() {  
        if ($('#tableBody').children().length > 0) {
            // Table body is not empty
            //$('#checkin_extend_date').prop('readonly', true);
			 // $('#checkout_extend_date').prop('readonly', true);
			  
 $('#checkin_extend_date').removeClass('pickerdate').prop('readonly', true);
    		$('#checkout_extend_date').removeClass('pickerdate').prop('readonly', true);
        } else {
            // Table body is empty
           
$('#checkin_extend_date').addClass('pickerdate').prop('readonly', false);
   			  $('#checkout_extend_date').addClass('pickerdate').prop('readonly', false);
        }
    }	*/
function checkTableBody() {  
    if ($('#tableBody').children().length > 0) {
        // Table body is not empty
        $('#checkin_extend_date').prop('readonly', true).addClass('disabled-datepicker');
        $('#checkout_extend_date').prop('readonly', true).addClass('disabled-datepicker');
    } else {
        // Table body is empty
        $('#checkin_extend_date').prop('readonly', false).removeClass('disabled-datepicker');
        $('#checkout_extend_date').prop('readonly', false).removeClass('disabled-datepicker');
    }
}	
<!-----------Other Charges Start------------------------------------------>


function selectres_ledeger() {

    $("#res_ledeger option:selected").attr("selected", "selected");


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
function AddOtherChargesTextBox() {
	
	
	   
    var add1 = 0;
    var add = 1;
    var order_by_roomRowCount = Number(add1) + Number(add);



    var start = document.getElementById("dateRange").value; 


var res_unit = 0;
var res_no_of_Room = 0;
var res_total = 0;

 			var res_ledeger = $("#res_ledeger").val();
            var res_unit = $("#res_unit").val();
            var res_room_no = $("#res_room_no").val();
            var res_no_days = 1;
            var res_no_of_Room = $("#res_no_of_Room").val();

if(res_unit=='1'){
	
	var DiffDays =res_no_of_Room;
	
	var res_total =  (res_total/res_no_of_Room);
	res_no_of_Room=1;
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
		//var div2 = document.createElement('DIV');
       // div2.setAttribute('id', uncodeRoomCode);
		CategoryCount =CategoryCount +  Number(1); 
		
	for (var q = 0; q < DiffDays; q++) {
		 roomCount =roomCount +  Number(1); 
        var loopDate = start;
       // var div2 = document.createElement('DIV');
       // div2.setAttribute('id', uncodeRoomCode);
		
		
        for (var k = 0; k <1 ; k++) {

          
           
            var uncode = Math.floor(Math.random() * 500) + 1; //Math.floor(Math.random() * 15);


           // var div = document.createElement('DIV');
            //div.setAttribute('id', uncode);
            row = GetDynamicOtherChargesTextBox(uncode, loopDate, res_room_type_new, uncodeRoomCode, k, DiffDays,
                order_by_roomRowCount,roomCount,CategoryCount,res_total);
				
				CategoryCount ='5';

            //div2.innerHTML=div.innerHTML
            //    var Datas	=	div2.append(div);
            //div2.append(div);
            //div2.appendChild(div)
            //document.getElementById("TextBoxContainerForm").div2.appendChild(div);

            $('#tableOtherChargesBody').append(row);
            document.getElementById(uncodeRoomCode).style.borderBottom = "solid #7FB3E0";

            $(".ledger_id_" + uncode).val(res_ledeger).change();
            $(".res_unit_id_" + uncode).val(res_unit).change();

            //$(".room_number_id_" + uncode).val(res_room_no).change();
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

	TotoalTarffiDataOtherCharges(); 
	$("#res_tariff_per_room_per_night").val('0');
	$("#res_tariff_per_room_inclusive_tax").val('0');
	$("#res_total").val('0')
	$("#res_tax").val('0')
	
	
	
}

function TotoalTarffiDataOtherCharges() {


    $.ajax({
        url: "ajax/ajaxDataSum.php",
        data: $("#saveReservationDateform").serialize(),
        type: "POST",
        success: function(result) {
            // $("#reservationModal").modal("hide");

            data = JSON.parse(result);
            $("#room_additional_charges").val(data.additional_charges);
            $("#total_tax_edit").val(data.perday_tax);
            $("#net_booking_amount_edit").val(data.tariff_per_room_inclusive_tax);
            $("#balance_edit").val(data.Balance);

        }
    });

}

function GetDynamicOtherChargesTextBox(uncode, res_date, res_room_type_new, uncodeRoomCode, k, DiffDays, order_by_roomRowCount,roomCount,CategoryCount,res_total) {
    //var res_date = $("#res_date").val();
res_room_type_new='POST';
    var res_tax = $("#res_tax").val();
    var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
    var res_tariff_per_room_inclusive_tax = $("#res_tariff_per_room_inclusive_tax").val();


   
	 
	 
	 var cssborder='style="border-top:3px solid #7FB3E0;"';

    var Utext = '<tr data-reservation-id="' + uncodeRoomCode + '" '+cssborder+'>';
	
	if (k == 0) {
       // Utext += '<td style="width: 83px;"><b></b></td>';
    } else {
        //Utext += '<td  style="width: 83px;"></td>';
    }
    Utext +=
        '<td style="width: 120px;"><input type="text" class="form-control" id="res_date" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][resdate][]" value="' + res_date + '"></td>';
		
		
	
		
    Utext += '<td style="width: 180px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][ledger_id][] "id="ledger_id_' + uncode +
        '" data-parsley-required  class="form-control ledger_id_' + uncode +
        '" ><option value="">Select Ledger</option><?php echo $charges; ?></select></td>';
		

   Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="additional_description_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][additional_description][]" value=""></td>';
		
		
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="res_no_days_id_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][res_no_days_id][]" value="1"></td>';
		
		
	
		
	    Utext += '<td style="width: 100px;"><select name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][res_unit_id][]" id="res_unit_id[]" class="form-control res_unit_id_' + uncode +
        '" data-parsley-required  ><option value="">Rate Plan</option><?php echo $ChargesUnit; ?></select></td>';
	
	
	
		
	 Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="res_no_of_Room_id_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][res_no_of_Room_id][]" value="1"></td>';
 			
		
		
		
		
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" onkeyup="PostChargesTaxCalculation(' +
        uncode + ');" id="tariff_per_room_per_night_' + uncode + '" name="PostChargesDataArray[' + res_room_type_new +
        '][' + uncodeRoomCode + '][tariff_per_room_per_night][]" value="0"></td>';
		
		
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="perday_tax_' + uncode +
        '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode + '][perday_tax][]" value="0"></td>';
    Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="tariff_per_room_inclusive_tax_' +
        uncode + '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][tariff_per_room_inclusive_tax][]" value="0" onkeyup="PostChargesCalculationInclusiveNew(' +
        uncode + ');"></td>';
		
		
	  Utext += '<td style="width: 100px;"><input type="text" class="form-control" id="total_' +
        uncode + '" name="PostChargesDataArray[' + res_room_type_new + '][' + uncodeRoomCode +
        '][total][]" value="' + res_total + '" ></td>';	
		
    Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="id_reservation_detail" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][id_reservation_detail][]" value="0">';
		
    Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="order_by_room" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][order_by_room][]" value="' + order_by_roomRowCount + '">';
		
		
	Utext +='<input style="width: 126px;" type="hidden" class="form-control" id="post_charges" name="PostChargesDataArray[' +
        res_room_type_new + '][' + uncodeRoomCode + '][post_charges][]" value="' + order_by_roomRowCount + '">';
		
		
		
    if (CategoryCount == '1') {
        Utext += '<td style="width: 83px;"><button type="button" value="Remove" onclick = "RemoveTextBoxOtherCharges(' + uncodeRoomCode +
            ')" class="deleteBox"> <i class="fas fa-trash"></i></button></td>';
			
    } else {
        Utext += '<td  style="width: 83px;"></td>';
    }
    Utext += '</tr>';



    return Utext;
}

function PostChargesTaxCalculation(uncode){
	
	
	
	if(uncode==''){
		
	var res_tariff_per_room_per_night = $("#res_tariff_per_room_per_night").val();
	var res_no_days = $("#res_no_days").val();
	var res_no_of_Room = $("#res_no_of_Room").val();
	}else{
		var res_ledeger = $("#ledger_id_"+uncode).val();
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
					TotoalTarffiDataOtherCharges();
						}
					
				
				
						
			}
			
		})
		 
 
	
}


function RemoveTextBoxOtherCharges(uncode) {



const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
TotoalTarffiData();
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

function RemoveTextBoxOtherChargesEdit(uncode) {



    RemoveDetailRecordOtherCharges(uncode);





}

function RemoveDetailRecordOtherCharges(uncode) {
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
				
				
				const rows = document.querySelectorAll(`tr[data-reservation-id="${uncode}"]`);
            
            // Iterate over all selected rows and remove them
            			rows.forEach(row => row.remove());
				
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




                            TotoalTarffiDataOtherCharges();
                        } else {
                            console.log('Parent or child element not found.');
                        }



                    }

                });
            }
        }
    });

}
function getRoomNo(field, hotelId) {
    var id_room_type = field.value;
    var ratePlanTd = $(field).closest('td').next();
    var roomNoTd = ratePlanTd.closest('td').next();
    var roomSelect = roomNoTd.find('select');
    var currentRoomId = $(roomSelect).data('room_no');
    console.log(currentRoomId);

    $.ajax({
        url: 'ajax/ajaxGetRoomNumber.php',
        type: "POST",
        data: {
            hotelId: hotelId,
            id_room_type: id_room_type,
            room_id: currentRoomId,
        },
        success: function(data) {
            roomSelect.empty();
            // Populate with new data
            roomSelect.html(data);
        },
        error: function(xhr, status, error) {
            // Handle the error
            console.error("AJAX Error: " + status + ": " + error);
            $("#room_id").html("<p>An error occurred while fetching room numbers.</p>");
        }
    });
}

$(document).ready(function() {
    $('.mst_room_types').on('change', function() {
        let current_value = this.value;
        var data_id = $(this).data('id');
        var hotelId = $(this).data('hotel_id');
        var order_by_room = $(this).data('order_by_room');
        
        $('.mst_room_types').each(function() {
            if ($(this).data('id') >= data_id && order_by_room == $(this).data('order_by_room')) {
                var field = this;
                $(this).val(current_value);
                setTimeout(() => {
                    getRoomNo(field, hotelId);
                }, 100);
            }
        });
    });
});

$(document).ready(function() {
    $('.id_mst_room_number').on('change', function() {
        let current_value = this.value;
        var data_id = $(this).data('id');
        var order_by_room = $(this).data('order_by_room');
        
        $('.id_mst_room_number').each(function() {
            if ($(this).data('id') >= data_id && order_by_room == $(this).data('order_by_room')) {
                var field = this;
                $(this).val(current_value);
            }
        });
    });
});
	$(document).ready(function () {

    var selectedId = "<?= $row->id_mst_guest ?>";

    // ✅ INIT SELECT2
    $('#id_mst_guest_form').select2({
        dropdownParent: $('#EditReservationModal'),
        placeholder: 'Select Guest',
        minimumInputLength: 0,
        ajax: {
            url: "ajax/ajax_guest_search.php",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return {
                    search: params.term || '',
                    id: selectedId
                };
            },
            processResults: function (data) {
                return {
                    results: data || []
                };
            },
            cache: true
        }
    });

    // 🔥 PRESELECT USING ONLY ID (AJAX CALL)
   if (parseInt(selectedId) > 0) {

    $.ajax({
        url: "ajax/ajax_guest_search.php",
        type: "GET",
        data: { id: selectedId },
        dataType: "json",
        success: function (data) {

            if (data.length > 0) {

                var item = data.find(function(obj) {
                    return obj.id == selectedId;
                });

                if (item) {
                    var option = new Option(item.text, item.id, true, true);
                    $('#id_mst_guest_form')
                        .append(option)
                        .trigger('change.select2');

                        toggleGuestActionButtons();
                }

            }
        }
    });
}

});
	
	/*$(document).ready(function () {

    function toggleTentativeDate() {
        var status = $('#res_bookingStatus_new').val();

        if (parseInt(status) === 2) {
            $('#tentative_date').prop('disabled', false);
            setTentativeRange(); // apply range
        } else {
            $('#tentative_date').prop('disabled', true).val('');
        }
    }

    function setTentativeRange() {

        var bookingDate = $('#bookingDate').val();     // dd-mm-yyyy
        var checkinDate = $('#checkin_extend_date').val(); // dd-mm-yyyy

        if (bookingDate && checkinDate) {

            var b = bookingDate.split('-');
            var c = checkinDate.split('-');

            // convert → yyyy-mm-dd
            var minDate = b[2] + '-' + b[1] + '-' + b[0];
            var maxDate = c[2] + '-' + c[1] + '-' + c[0];

            $('#tentative_date').attr('min', minDate);
            $('#tentative_date').attr('max', maxDate);
        }
    }

    // Status change
    $('#res_bookingStatus_new').on('change', toggleTentativeDate);

    // When check-in changes → update max range
    $('#checkin_extend_date').on('change', function () {
        setTentativeRange();
    });

    // Initial load
    toggleTentativeDate();
    setTentativeRange();

});*/
	
	$(document).ready(function () {

    const dateFormat = 'dd-mm-yy';

// correct destroy
$('.pickerdate').datepicker('destroy');

// init
$('.pickerdate').datepicker({
    dateFormat: dateFormat
});

// convert
function getDate(dateStr) {
    return $.datepicker.parseDate(dateFormat, dateStr);
}

// range
function setTentativeRange() {

    var bookingDate = $('#bookingDate').val();     
    var checkinDate = $('#checkin_extend_date').val();

    if (bookingDate && checkinDate) {

        var minDate = getDate(bookingDate);
        var maxDate = getDate(checkinDate);

        $('#tentative_date').datepicker('option', 'minDate', minDate);
        $('#tentative_date').datepicker('option', 'maxDate', maxDate);

        // Calculate 30 days before check-in
        var checkinDateObj = getDate(checkinDate);
        var defaultTentativeDate = new Date(checkinDateObj);
        defaultTentativeDate.setDate(defaultTentativeDate.getDate() - 30);

        var today = new Date();
        today.setHours(0, 0, 0, 0);

        if (defaultTentativeDate < today) {
            defaultTentativeDate = today;
        }

        $('#tentative_date').datepicker('setDate', defaultTentativeDate);

    }
}

    // 🔥 Enable / Disable based on Booking Status
    function toggleTentativeDate() {
        var status = $('#res_bookingStatus_new').val();

        if (parseInt(status) === 2) {
            $('#tentative_date').prop('disabled', false);
            setTentativeRange();
        } else {
            $('#tentative_date').prop('disabled', true).val('');
        }
    }

    // 🔁 EVENTS

    // Booking status change
    $('#res_bookingStatus_new').on('change', function () {
        toggleTentativeDate();
    });

    // Check-in change (IMPORTANT: jQuery UI event)
    $('#checkin_extend_date').on('change', function () {
        setTentativeRange();
    });

    // Initial load
    toggleTentativeDate();

});
$(document).ready(function () {

    function toggleTentativeDate() {
        var status = $('#res_bookingStatus_new').val();

        if (parseInt(status) === 2) {
            $('#tentative_wrapper').show();   // ✅ show full div
            $('#tentative_date').prop('disabled', false);
        } else {
            $('#tentative_wrapper').hide();   // ❌ hide full div
            $('#tentative_date').prop('disabled', true).val('');
        }
    }

    $('#res_bookingStatus_new').on('change', toggleTentativeDate);

    // initial load
    toggleTentativeDate();

});$(document).ready(function () {
    var val = $('#tentative_date').val();
	
    if (val) {
        $('#tentative_date').datepicker('setDate', val);
    }
});

function toggleGuestActionButtons() {
    var guestId = $('#id_mst_guest_form').val();
    if (guestId && parseInt(guestId) > 0) {
        $('#guestEditBtnWrap').show();
        $('#guestHistoryBtnWrap').show();
    } else {
        $('#guestEditBtnWrap').hide();
        $('#guestHistoryBtnWrap').hide();
    }
}

$('#id_mst_guest_form').on('change', toggleGuestActionButtons);

function openGuestEditPage() {
    var guestId = $('#id_mst_guest_form').val();
    if (!guestId) return;

    $.ajax({
        url: 'ajax/ajax_guest_get_details.php',
        type: 'GET',
        data: { id: guestId },
        dataType: 'json',
        success: function (g) {
            if (!g || !g.id) return;

            // reset to Add mode fields, then fill for Edit
            $('#EditCustomerID').val(g.id);

            $('#Nametitle').val(g.id_mst_attributes_title || '').trigger('change');
            $('#first_name').val(g.first_name || '');
            $('#last_name').val(g.last_name || '');
            $('#email').val(g.email || '');
            $('#mobile').val(g.primary_mobile || g.mobile || '');
            $('#city').val(g.city || '');
            $('#id_country').val(g.id_country || '').trigger('change');
            $('#user_type').val(g.user_type || '').trigger('change');

            // proof_type + its conditional fields
            $('#proof_type').val(g.proof_type || '').trigger('change');

            // give the proof_type change handler (which likely reloads #appenddata
            // via AJAX) a moment to finish, then fill in the specific proof fields
            setTimeout(function () {
                $('#voter_no').val(g.voter_no || '');
                $('#adhar_no').val(g.adhar_no || '');
                $('#passport_no').val(g.passport_no || '');
                $('#authority').val(g.authority || '');
                if (g.passport_expiry_date && g.passport_expiry_date !== '0000-00-00') {
                    $('#passport_expiry_date').val(formatDateDMY(g.passport_expiry_date));
                }
                if (g.visa_expiry_date && g.visa_expiry_date !== '0000-00-00') {
                    $('#visa_expiry_date').val(formatDateDMY(g.visa_expiry_date));
                }
                if (g.cform_expiry_date && g.cform_expiry_date !== '0000-00-00') {
                    $('#cform_expiry_date').val(formatDateDMY(g.cform_expiry_date));
                }
            }, 300);

            $('#guestNewaddeditModal .modal-title').text('Edit Guest Details');
            $('#guestNewaddeditModal').modal('show');
        }
    });
}

function formatDateDMY(mysqlDate) {
    var parts = mysqlDate.split('-'); // yyyy-mm-dd
    if (parts.length !== 3) return '';
    return parts[2] + '-' + parts[1] + '-' + parts[0];
}

$('#res_guestAddId').on('click', function () {
    $('#guestNewpopupform')[0].reset();
    $('#EditCustomerID').val('');
    $('#guestNewaddeditModal .modal-title').text('Add Guest Details');
});

function openGuestStayHistory() {
    var guestId = $('#id_mst_guest_form').val();
    if (!guestId) return;

    $('#guestStayHistoryBody').html('<tr><td colspan="4" class="text-center">Loading...</td></tr>');
    $('#guestStayHistoryModal').modal('show');

    $.ajax({
        url: 'ajax/ajax_guest_stay_history.php',
        type: 'GET',
        data: { guest_id: guestId },
        dataType: 'json',
        success: function (rows) {
            if (!rows || rows.length === 0) {
                $('#guestStayHistoryBody').html('<tr><td colspan="4" class="text-center">No stay history found.</td></tr>');
                return;
            }

            var html = '';
            $.each(rows, function (i, r) {
                html += '<tr>' +
                    '<!--<td data-label="Booking Number">' + (r.booking_no || '') + '</td>-->' +
                    '<td data-label="Check In">' + (r.checkin || '') + '</td>' +
                    '<td data-label="Check Out">' + (r.checkout || '') + '</td>' +
                    '<td data-label="Room No.">' + (r.room_nos || '') + '</td>' +
                    '</tr>';
            });
            $('#guestStayHistoryBody').html(html);
        },
        error: function () {
            $('#guestStayHistoryBody').html('<tr><td colspan="4" class="text-center">Failed to load stay history.</td></tr>');
        }
    });
}

</script>