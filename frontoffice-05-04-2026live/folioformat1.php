<?php include_once("../config/auto_loader.php");
  ?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>


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
</style>

<?php 

$id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
$id_fo_folio_to=addslashes(encryptor(decrypt,$_REQUEST['id_folio']));

$id_fo_bill =  selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_fo_folio_to."'");
$id_fo_folio =  selectColumn('fo_folio','id'," WHERE `id` = '".$id_fo_folio_to."'");
//$id_fo_folio_to =  selectColumn(FO_BILL,'id_fo_folio_to'," WHERE `id` = '".$id_fo_bill."'");

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
          $CurrentTotal +=$rowOrderDetail->tariff_price_per_day_per_room+$rowOrderDetail->tax_per_day_per_room;
		$roomNumberArray[$rowOrderDetail->id_mst_room_no_allocation]=selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");		
				}
				
				
		}
		//pos_purch_details
		
		$sqlOrderDetail = mysqli_query($connNew,"Select  * from `pos_purch` where id_fo_folio_to='".addslashes($id_fo_folio_to)."' ");
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
					
					$CurrentTotal	+=$rowOrderDetail->grant_total_amount;
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
		 $doc_date=  $rowFoBill->doc_date=='1970-01-01 00:00:00'?'-':date('d-m-Y',strtotime($rowFoBill->doc_date)); 
		 $id_resevation	=  selectColumn(FO_BILL,'id_reservations'," WHERE `id` = '".$id_fo_bill."'");
		 $checkout_date=  date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkout','WHERE id="'.$id_resevation.'"')));//$rowFoBill->status=='2'?date('d-m-Y',strtotime($rowFoBill->checkout_date)):'-'; 
		  $checkin_date=	date('d-m-Y',strtotime(selectColumn(FO_RESERVATIONS,'checkin','WHERE id="'.$id_resevation.'"')));//date('d-m-Y',strtotime($rowFoBill->date_created));            
      $checkin_time_query = mysqli_query($connNew,"SELECT * FROM `fo_reservations_details` WHERE id_fo_reservations='".$id_fo_reservations."' order by id desc limit 1");
      $checkin_time_result =  mysqli_fetch_object($checkin_time_query);
      $checkin_time = $checkin_time_result->checkin_time ?? '';
      $checkout_time = $checkin_time_result->checkout_time ?? '';
//FO BILL STATUS==========================================

$receipt_amount	=	selectColumn('fo_receipt','sum(amount)','WHERE id_fo_folio="'.$id_fo_folio.'"');

$BalanceAmount = $CurrentTotal-$receipt_amount;

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
              <div class="btn o-btn" ><i class="fa-solid fa-window-maximize"></i> One window</div>
            </a>
          </div>
        

          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
            <button class="btn c-btn btn-block" style="margin-right:15px" onclick="printdiv('div_print');"><i
                class="fa fa-print fa-1x"></i> Print</button>
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
                            <td class="pm"
                              style="display:table-cell;display:none;width:20%;border-right:.4px solid #000!important;">
                              <img
                                src=""
                                width="137px" alt="">
                            </td>
                            <td class="pm" style="width:80%;font-family: sans-serif;font-size:11px;">
                              <center>
                                <p style="font-size:15px;font-weight:600;padding-bottom:0;">Room Bill</p>
                              </center>
                              <center>
                                <p><b><?php echo $HotelName; ?></b><br>
                                 <?php echo $HotelAddress; ?><br>
                                 <?php echo $HotelCity .',  '.$HotelState. '-' .$HotelPincode; ?><br>
                               <b> PH:</b> +91 <?php echo $Hotelsecondary_landline.' '; ?><b> GST NO : </b><?php echo $HotelGST; ?>

                                  
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
                                  <p style="padding:5px;margin:0;">Name & Address </p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Nationality</p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Folio No</p>
                                </center>
                              </b>
                            </td>
                            <td class="pm"
                              style="width:20%;font-size:11px;font-family: sans-serif;border-bottom:.4px solid #000;padding:0;margin:0;">
                              <b>
                                <center>
                                  <p style="padding:5px;margin:0;">Folio Date</p>
                                </center>
                              </b>
                            </td>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <td class="pm" rowspan="3"
                                    style="text-align:left;width:40%;border-bottom:.4px solid #000;border-right:.4px solid #000;font-size:11px;font-family: sans-serif;">

                                    <left>
                                    
                                      <p>  <span><b><?php echo $GuestName ?></b></span><br>
                                       <?php  echo $GuestAddress.', '.$GuestCity; ?>
                                      
                                      <br></p>
                                    </left>
                                  </td>
                                  <td class="pm" style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                                    <p style="font-size:11px;"><?php echo ucwords($GuestNationality);?>
                                  </p>
                                  </td>

                                  <td class="pm" style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                                    <p style="font-size:11px;"><?php echo $folio_mdoc_no;?>  </p>
                                  </td>
                                  <td class="pm" style="text-align:center;width:20%;border-bottom:.4px solid #000;font-family: sans-serif;">
                                    <p style="font-size:11px;"><?php echo date('d-m-Y',strtotime($folio_doc_date)); ?>
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
                          
                                <td class="pm" style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                                  <p style="font-size:11px;"><?php echo $checkin_date; ?>&nbsp;<?php echo $checkin_time != '' ? $checkin_time : "00:00"; ?>
                                </p>
                                </td>

                                <td class="pm" style="text-align:center;width:20%;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                                  <p style="font-size:11px;"><?php  echo $checkout_date; ?>&nbsp;<?php echo $checkout_time != '' ? $checkout_time : "00:00"; ?>
                                </p>
                                </td>
                                <td class="pm" style="text-align:center;width:20%;border-bottom:.4px solid #000;font-family: sans-serif;">
                                  <p style="font-size:11px;"><?php echo $pax; ?>
                                </p>
                                </td>
                          </tr>
                          <tr>
                          
                              <td class="pm" style="text-align:left;;border-right:.4px solid #000;border-bottom:.4px solid #000;font-family: sans-serif;">
                                <p style="font-size:11px;"><span><b>Company Name : </b> <?php echo $CompanyName;?></span>
                                <br><span><b>Party GSTIN : <?php echo $CompanyGST;?></b></span>
                              </p>
                              </td>

                              <td class="pm" colspan="3" style="text-align:left;border-bottom:.4px solid #000;font-family: sans-serif;">
                                <p style="font-size:11px;"><b>Booking No :  <?php echo $reservation_mdoc_no;?></b>
                              </p>
                              </td>
                            
                         </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:0px!important;border-bottom: 0.4px solid #000;">



                      <p
                        style="text-align:left;font-size:11px;font-family: sans-serif;border-bottom:0.4px solid #000;margin:0;padding:5px;">
                        <b>Room No :  <?php echo $roomNumber;?></b>
                      </p>
                     <table id="myTable1" class="table table-striped no-footer dataTable" width="100%" border="0"
                        cellspacing="0" cellpadding="10">
                        <thead>
                          <tr>
                               <td class="pm"
                              style="width:6%;font-size:11px;font-family: sans-serif;padding:5px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;"><b>  S.No</b></p>
                            </td>
                          <td class="pm"
                              style="width: 26%;font-size:11px;font-family: sans-serif;padding:5px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;"><b>Bill No</b></p>
                            </td>
                            <td class="pm"
                              style="width: 20%;font-size:11px;font-family: sans-serif;padding:5px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;"><b>Date</b></p>
                            </td>
                        
                            <td class="pm"
                              style="font-size:11px;font-family: sans-serif;width:20%;padding:5px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px;"><b>Description</b></p>
                            </td>
                           <td class="pm"
                              style="width: 20%;font-size:11px;font-family: sans-serif;padding:5px 4px!important;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px"><b>Receipt</b></p>
                            </td>

                            <td class="pm"
                              style="font-size:11px;font-family: sans-serif;width: 8%;padding:5px 4px!important;margin:0;border-bottom: 0.4px solid #000;">
                              <p style="margin:3px"><b>Charges</b></p>
                            </td>
                          </tr>
                        </thead>
                        <tbody>
                       <?php 
              $count=1;
    foreach($folioArray as $RoomName=>$Array1){
      
      foreach($Array1 as $rowid=>$Array2){?>

                          <tr>
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                             <?php echo $count++; ?> 
                              </p>
                            </td>
                          <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                               <?php echo ($Array2['InvoiceNo']); ?>
                              </p>
                            </td>
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                               <?php echo date('d-m-Y',strtotime($Array2['dated'])); ?>
                              </p>
                            </td>
                       
                          <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                              <?php echo $Array2['source'];?><br>
                                <b><?php //echo $Array2['RoomType'];?></b></p>
                            </td>
                           
                           <td class="pm"
                              style="border-bottom:.4px solid #000;padding-top:4px!important;width: 15%;padding:5px 4px!important;margin:0;border-right:.4px solid #000;">
                              <p style="font-size:11px;font-family: sans-serif;margin:0!important;">
                             <br>
                                <b><?php //echo $Array2['RoomType'];?></b></p>
                            </td>
                            

                           <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;text-align:right;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                             <?php echo $Array2['Total'];?></p>
                            </td>

                          </tr>
                         <?php } }?> 
   <?php 
  $id_fo_bill=addslashes(encryptor(decrypt,$_REQUEST['idfobill']));
 $id_fo_folio_to=addslashes(encryptor(decrypt,$_REQUEST['id_folio']));

//echo '==='.$id_fo_bill =  selectColumn('fo_folio','id_fo_bill'," WHERE `id` = '".$id_fo_folio_to."'");               
 $sql_fo_receipt = "SELECT * FROM `fo_receipt` where  id_fo_folio='".$id_fo_folio_to."'";
$res_fo_receipt   =   mysqli_query($connNew,$sql_fo_receipt);
if(mysqli_num_rows($res_fo_receipt)>0){

  ?> 
      <?php 
  $i=1;$row_fo_receiptAmount=0;
  while($row_fo_receipt = mysqli_fetch_object($res_fo_receipt)){
  
    //pos_purch_details
    
    
        $row_fo_receiptAmount += $row_fo_receipt->amount;
        ?>                       
      <tr>
                             <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                            </td>
                           <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                               <?php echo 'REC/'.$row_fo_receipt->id; ?>
                              </p>
                            </td>
                       
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                              <?php echo date('d-m-Y',strtotime($row_fo_receipt->doc_date)); ?><br>
                                </p>
                            </td>
                            
                           <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                                <b><?php echo $row_fo_receipt->payment_mode;?></b></p>
                            </td>
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;text-align:right;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                            <?php echo $row_fo_receipt->amount;?></p>
                            </td>

                            <td class="pm"
                              style="border-bottom:.4px solid #000;padding-top:4px!important;width: 20%;padding:0px!important;padding-left:5px!important;margin:0;">
                              <p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">
                             -</p>
                            </td>

                          </tr>
                          <?php } ?>                    
<?php } ?>                          
                          <tr>
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;">
                              <p
                                style="font-size:11px;font-family: sans-serif;padding:5px!important;margin:0!important;">
                             
                              </p>
                            </td>
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;">
                              <p
                                style="font-size:11px;font-family: sans-serif;padding:5px!important;margin:0!important;">
                             
                              </p>
                            </td>
                            
                       
                            <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;text-align:right;border-right:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                              <b>Sub Total  : </b>
                              </p>
                            </td>
                           
                            

                           <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;text-align:right;border-right:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                              <?php echo $row_fo_receiptAmount; ?></p>
                            </td>
                            
                            
                             <td class="pm"
                              style="width: 1%;padding:0px!important;margin:0;text-align:right;border-right:.4px solid #000;padding:5px 4px!important;">
                              <p
                                style="font-size:11px;font-family: sans-serif;margin:0!important;padding-left:10px;">
                              <?php echo $CurrentTotal; ?></p>
                            </td>

                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>



                 
                  <tr>
                    <td>

                      <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%">
                        <thead>
                          <tr>
                            <td style="width: 16%;"></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>
                            <td style="width: 25%;"></td>

                            <td></td>
                          </tr>
                        </thead>

                        <tbody>
                          <tr>
                            <td></td>
                            <td class="pm" style="font-family: sans-serif;font-size:11px;">
                            
                            </td>
                            <td class="pm" style="font-family: sans-serif;font-size:11px;">
                              <p></p>
                            </td>
                            <td class="pm" style="font-family: sans-serif;font-size:11px;text-align: right;">
                           <p style="padding-bottom:0;"><b>Round Off :  </b></p>
                              <p style="padding:0;"><b>Grand Total :</b></p>
                            </td>
                            <td class="pm" style="font-family: sans-serif;font-size:11px;text-align: left;">
                             <p style="padding-bottom :0;" >  <?php echo round(round($CurrentTotal,0) - $CurrentTotal,2); ?>  </p>
                              <p style="padding:0;">  <?php echo round(($CurrentTotal),0); ?></p>
                            </td>
                            <td  class="pm" >
                            </td>
                            <td>
                            </td>

                          </tr>
                         
                         

                        </tbody>

                      </table>
                    </td>
                  </tr>
                   
                  <tr>
                    <td style="border-top:.4px solid #000;">

                      <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%" >
                     
                        <tbody>
                          <tr>
                          
                            <td class="pm" style="padding: 5px 14px 16px 10px!important;font-family: sans-serif;font-size:11px;">
                              <p style="text-transform:uppercase;"><b>Please Deposit your room key on Checkout</p>
                              <p style="font-weight:100;font-size:10px;"> Cashier</p>
                            </td>
                          
                            <td class="pm" style="padding: 5px 14px 16px 10px!important;font-family: sans-serif;font-size:11px;text-align: center;">
                              <p><b>Guest Signatory</b></p>
                            </td>

                          </tr>
                         
                         

                        </tbody>

                      </table>
                    </td>
                  </tr>
                   
               

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
     </div>  <!--end of row-->



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