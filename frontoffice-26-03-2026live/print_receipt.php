<?php
include_once("../config/auto_loader.php");
$id_fo_bill	=	selectColumn('fo_receipt','id_fo_bill','WHERE id="'.addslashes(encryptor(decrypt,$_REQUEST['idrec'])).'"');
//echo '===================='.$id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
$id_fo_folio_to=selectColumn('fo_receipt','id_fo_folio','WHERE id="'.addslashes(encryptor(decrypt,$_REQUEST['idrec'])).'"');



//$id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
//$id_fo_folio_to=addslashes(encryptor(decrypt,$_REQUEST['id_folio']));
$id_fo_bill =  selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_fo_folio_to."'");
$bill_mdoc_no =  selectColumn(FO_BILL,'mdoc_no'," WHERE `id_fo_folio_to` = '".$id_fo_folio_to."'");
$folio_mdoc_no  = selectColumn('fo_folio','mdoc_no'," WHERE `id` = '".$id_fo_folio_to."'");
$folio_doc_date  = selectColumn('fo_folio','doc_date'," WHERE `id` = '".$id_fo_folio_to."'");
$id_mst_guest  = selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$id_fo_folio_to."'");
$id_mst_room_no_allocation= addslashes(encryptor(decrypt,$_REQUEST['id_mst_room_no_allocation']));
$folioArray=array();

$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_folio_to='".addslashes($id_fo_folio_to)."'  ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$roomNumberArray=array();
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					$id_fo_reservations	=$rowOrderDetail->id_fo_reservations;
					$pax				   =$rowOrderDetail->adults_per_room;
					$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					$mdoc_no	= selectColumn(FO_BILL,'mdoc_no'," WHERE `id` = '".$id_fo_bill."'");
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;	
					
		  $folioArray[$RoomNoAndRoomName][$mdoc_no]['RoomType']=$RoomNoAndRoomName;
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['Type']='Reservation';
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['source']= 'Room Tariff';
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['tariff']=$rowOrderDetail->tariff_price_per_day_per_room;
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['tax']+=$rowOrderDetail->tax_per_day_per_room;
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['Total']+=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
          $folioArray[$RoomNoAndRoomName][$mdoc_no]['InvoiceNo']=$bill_mdoc_no;
          $total_received +=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
		$roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");		
				}
				
				
		}
		//pos_purch_details
		
		$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($id_fo_folio_to)."' and cancelled!=1 ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					
					$outletName =selectColumn(TBL_OUTLETS,'name','WHERE id="'.$rowOrderDetail->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo;	
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']='';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->doc_date));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $outletName;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']='-';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']=$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->grant_total_amount+$rowOrderDetail->tax_per_day_per_room;
					
					$total_received	+=$rowOrderDetail->grant_total_amount;
				}
				
				
		}


$sqlOrderDetail = mysqli_query($connNew,"Select  * from `fo_reservations_addons_details` where id_fo_folio_to='".addslashes($id_fo_folio_to)."' ");
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//$roomNo= selectColumn(TBL_CHARGES,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
					//$RoomName	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
									
					$chargesname= selectColumn(TBL_CHARGES,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_charges."'");
					$outletName ='Post Charges';
					$RoomNoAndRoomName=$RoomName.'/'.$roomNo.'POS';	
					
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['RoomType']='';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Type']='POS';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['dated']= date('d-m-Y',strtotime($rowOrderDetail->dated));
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source']= $chargesname;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tariff']='-';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['tax']=$rowOrderDetail->tax_per_day_per_room;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['InvoiceNo']='-';$chargesname;//$rowOrderDetail->mdoc_no;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_table']= 'pos_purch';
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['source_split_id']= $rowOrderDetail->id;
					$folioArray[$RoomNoAndRoomName][$rowOrderDetail->id]['Total']=$rowOrderDetail->total;
					
					$total_received	+=$rowOrderDetail->total;
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
		 $doc_date=  $rowFoBill->doc_date=='1970-01-01 00:00:00'?'-':date('d-m-Y',strtotime($rowFoBill->doc_date)); 
		 $id_resevation	=  selectColumn(FO_BILL,'id_reservations'," WHERE `id` = '".$id_fo_bill."'");
		 $checkout_date=  date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_resevation.'"')));//$rowFoBill->status=='2'?date('d-m-Y',strtotime($rowFoBill->checkout_date)):'-'; 
		  $checkin_date=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_resevation.'"')));//date('d-m-Y',strtotime($rowFoBill->date_created));            
//FO BILL STATUS==========================================

$total_received	=	selectColumn('fo_receipt','sum(amount)','WHERE id_fo_bill="'.$id_fo_bill.'"');

$BalanceAmount = $total_received-$receipt_amount;

  $id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE id_fo_reservations = '".$id_resevation."'");
$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
//$roomNumber= implode(',',$roomNumberArray);


//$id_mst_guest	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_guest','WHERE id_fo_bill="'.$id_fo_bill.'" ');

$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  `id` = '".addslashes($id_mst_guest)."' ";



$query=mysqli_query($connNew, $SQL);		
$row=mysqli_fetch_assoc($query);

$GuestTitle	=	selectColumn(TBL_ATTRIBUTES,'field_value','WHERE `id_shop`="'.addslashes($_SESSION['shop']).'" and id="'.$row['id_mst_attributes_title'].'"');

$GuestName = $GuestTitle.''.$row['first_name'].' '. $row['last_name'];
$GuestAddress = $row['address'];
$GuestCity = $row['city'];


//$row['email'].' , ' . $row['phone'] . '  ' . $row['city'];


//$GuestNationality =$row['city'];
$GuestNationality	=	selectColumn(TBL_COUNTRY_LANG,'nationality','WHERE id_country="'.$row['id_mst_country_lang_nationality'].'"');
$pax	=	selectColumn(FO_RESERVATIONS_DETAILS,'adults_per_room','WHERE `id_fo_reservations`= "'.$id_resevation.'"');
$id_mst_company	=	selectColumn(FO_RESERVATIONS,'id_mst_company','WHERE id="'.$id_resevation.'"');
$reservation_mdoc_no	=	selectColumn(FO_RESERVATIONS,'booking_no','WHERE id="'.$id_resevation.'"');
$selectnew = "select *  from ".TBL_COMPANY." where status='1'  and name !='' and `id` = '".$id_mst_company."'";
$resnew = mysqli_query($connNew,$selectnew);		
		$rownew = mysqli_fetch_object($resnew);	
			
	
$id_bill_to_company	=  selectColumn('fo_folio','id_bill_to_company'," WHERE `id` = '".$id_fo_folio_to."'");
	
	if($id_bill_to_company=='0'){
		$CompanyName= ucwords($rownew->name);
		$CompanyGST = $rownew->fax;
	}else{
		$selectnew = "select *  from ".TBL_COMPANY." where status='1'  and name !='' and `id` = '".$id_bill_to_company."'";
		$resnew = mysqli_query($connNew,$selectnew);		
		$rownew = mysqli_fetch_object($resnew);
		$CompanyName= ucwords($rownew->name);
		$CompanyGST = $rownew->fax;
		}
	
	
	$SQL_Hotel = "select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."'";	
	$query_Hotel=mysqli_query($connNew, $SQL_Hotel);		
	$row_Hotel=mysqli_fetch_object($query_Hotel);
		
		
		
		
		$HotelName	   =$row_Hotel->name;
		$HotelState	  =selectColumn(TBL_STATE,'name','WHERE id_state="'.$row_Hotel->id_mst_state.'"');
		$HotelCity	   =$row_Hotel->city;
		$HotelPincode	=$row_Hotel->pincode;
		$HotelGST		=$row_Hotel->gstin;
		$HotelAddress	=$row_Hotel->address;
		$Hotelsecondary_landline	=$row_Hotel->secondary_landline;

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
$amount = round(($total_received),0);
$convert_amount_to_words= convert_amount_to_words($amount);
?>


<!DOCTYPE html>
<html>
<head>
<title>Payment Receipt</title>
<style>
    body { font-family: Arial, sans-serif; padding:20px; background:white; }
    .receipt-box {
        max-width: 650px;
        margin: auto;
        border: 1px solid #000;
        padding: 20px;
        font-size: 14px;
        line-height: 22px;
    }
    .title { text-align:center; font-size:22px; font-weight:bold; margin-bottom:5px; }
    .sub-title { text-align:center; font-size:14px; margin-bottom:15px; }
    table { width:100%; border-collapse: collapse; margin-top:15px; }
    td { padding:6px; }
    .border { border:1px solid #000; }
    .amount-box {
        font-size:20px;
        font-weight:bold;
        text-align:center;
        margin-top:20px;
        padding:10px;
        border:1px dashed #000;
    }
    @media print {
        #printBtn { display:none; }
    }
</style>
</head>

<body>

<div class="receipt-box">



    <!-- HOTEL HEADER -->
    <div class="title"><?php echo $HotelName; ?></div>
    <div class="sub-title"><?php echo $HotelAddress.', '.$HotelCity.', '.$HotelState.' - '.$HotelPincode; ?><br>Phone: +91 <?=$Hotelsecondary_landline?> | GST: <?=$HotelGST?></div>

    <hr>

    <!-- RECEIPT TITLE -->
    <h3 style="text-align:center; margin-bottom:0;">PAYMENT RECEIPT</h3>
    <!--<p style="text-align:center; margin-top:3px;">Receipt No: <?php echo ($Array2['InvoiceNo']); ?></p>-->
	<p style="text-align:center; margin-top:3px;">Booking No: <?php echo $reservation_mdoc_no; ?></p>
    <hr>

    <!-- CUSTOMER / BOOKING INFO -->
    <table>
        <tr>
			<td ><b>Guest Name:</b> <?php echo $GuestName; ?></td>
            <td><b>Folio No:</b> <?php echo $folio_mdoc_no; ?></td>
            <td><b>Folio Date:</b> <?php echo date('d-m-Y',strtotime($folio_doc_date)); ?></td>
        </tr>
        <tr>
			<td ><b>Check In:</b> <?php echo $checkin_date; ?></td>
			<td ><b>Check Out:</b> <?php echo $checkout_date; ?></td>
			<td ><b>Pax:</b> <?php echo $pax; ?></td>
        </tr>
		 <tr>
			 <?php if($CompanyName != ''){ ?>
			<td ><b>Company Name: </b> <?php echo $CompanyName; ?></td>
			 <?php }
			 	if($CompanyGST != ''){
			 ?>
			<td ><b>Party GSTIN: </b> <?php echo $CompanyGST; ?></td>
			 <?php } ?>
        </tr>
    </table>

    <!-- PAYMENT DETAILS -->
    <h4 style="margin-top:15px;">Received amount ₹<?= number_format($total_received, 2);?> 
    (<?php echo $convert_amount_to_words; ?> )</b> with the following details:</h4>
<h5 style="margin-top:-15px; margin-bottom:-10px;">Room No: <?php echo $roomNumber; ?></h5>
<table class="border">
    <tr>
        <th class="border" style="background:#eee;">S.No</th>
        <th class="border" style="background:#eee;">Bill No</th>
        <th class="border" style="background:#eee;">Date</th>
        <th class="border" style="background:#eee;">Payment Mode</th>
        <!--<th class="border" style="background:#eee;">Charges</th>-->
        <!-- <th class="border" style="background:#eee;">Amount (₹)</th> -->
        <th class="border" style="background:#eee;">Receipt</th>
    </tr>

    <?php /*
    $i = 1;
    foreach($folioArray as $RoomName=>$Array1){
        foreach($Array1 as $rowid=>$Array2){
    ?>
    <tr>
        <td class="border"><?= $i++; ?></td>
        <td class="border"><?= ($Array2['InvoiceNo']); ?></td>
        <td class="border"><?= date('d-m-Y',strtotime($Array2['dated'])); ?></td>
        <td class="border"><?= $Array2['source']; ?></td>
        <!-- <td class="border"><b><?= number_format($p['amount'],2); ?></b></td> -->
		<td class="border"><?php //$Array2['Total']; ?></td>
        <td class="border"><?= $Array2['Total']; ?></td>
    </tr>
    <?php } }; */?>
	
	<?php 
	//$id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
 //$id_fo_folio_to=addslashes(encryptor(decrypt,$_REQUEST['id_folio']));
	$sql_fo_receipt = "SELECT * FROM `fo_receipt` where  id_fo_folio='".$id_fo_folio_to."'";
$res_fo_receipt   =   mysqli_query($connNew,$sql_fo_receipt);
if(mysqli_num_rows($res_fo_receipt)>0){
	?>
	 <?php 
  $i=1;$row_fo_receiptAmount=0;
  while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
  
    //pos_purch_details
    
    
        $row_fo_receiptAmount += $row_fo_receipt->amount;
        ?>  <tr>
                             <td class="border"><?php echo $i++; ?></td>
                           <td class="border">
                              
                               <?php echo 'REC/'.$row_fo_receipt->id; ?>
                             
                            </td>
                       
                            <td class="border">
                              
                              <?php echo date('d-m-Y',strtotime($row_fo_receipt->doc_date)); ?><br>
                               
                            </td>
                            
                           <td class="border">
                            
                                <b><?php echo $row_fo_receipt->payment_mode;?></b>
                            </td>
                            <td class="border">
                              
                            <?php echo $row_fo_receipt->amount;?>
                            </td>

                            <!--<td class="border">
                              <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                             -</p>
                            </td>-->

                          </tr>
                          <?php } ?>                    
<?php } ?>         
</table>

<!-- TOTAL AMOUNT -->
<div class="amount-box">
    TOTAL RECEIVED: ₹ <?= number_format($total_received, 2); ?>
</div>

    <!-- FOOTER -->
    <p style="margin-top:30px; text-align:center; font-size:12px;">
        This is a system-generated receipt and does not require a signature.
    </p>

</div>

<div style="text-align:center; margin-top:20px;">
    <button id="printBtn" 
            onclick="window.print()" 
            style="padding:8px 20px; background:#007bff; color:white; border:none; border-radius:4px; cursor:pointer;">
        Print Receipt
    </button>
</div>

</body>
</html>
