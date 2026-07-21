<?php 

	function FoDateWiseCheckoutReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($date != ''){
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConn .= " AND fobill.`doc_date` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
		//$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		$SqlConn2 .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}







$head_cntr = "C";
	$setcellcount	=1;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->applyFromArray($styleThinBlackBorderOutline);

$con=$setcellcount;



$objPHPExcel->getActiveSheet()
->getStyle('M')
->getNumberFormat()
->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2 );

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);


			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$con=1;	
			//Voucher Type	Voucher No.	Voucher Dt.	Account Name	Narration	Dr Amount	Cr Amount		vchref	vchDate	Assessable Value	Surcharge

				$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Voucher Type')
			->setCellValue('B'.$con, 'Voucher No.')
			->setCellValue('C'.$con, 'Voucher Dt.')
			->setCellValue('D'.$con, 'Account Name')
			->setCellValue('E'.$con, 'Narration')
			->setCellValue('F'.$con, 'Dr Amount')				
			->setCellValue('G'.$con, 'Cr Amount')
			->setCellValue('H'.$con, 'Type')
			->setCellValue('I'.$con, 'vchref')
			->setCellValue('J'.$con, 'vchDate')
			->setCellValue('K'.$con, 'Assessable Value')
			->setCellValue('L'.$con, 'Surcharge');	
			$_REQUEST['hotelId']=1;	
			
$con++;
$SalesRegisterArray=array();
 $cond = "  where `fo_reservations`.`id_mst_shops` = '".addslashes($_SESSION['shop'])."' ";
 $cond 	   .=	" AND `fo_reservations`.`id_mst_hotels` in ('1')";
	if($_REQUEST['search_name'] != ''){
	$cond .= " AND (`reference` LIKE '%".addslashes($_REQUEST['search_name'])."%' || concat(reference,'-', code) LIKE '%".addslashes($_REQUEST['search_name'])."%' )";
	}
	/*if($_REQUEST['hotelId'] != ''){
		$hotel_ids = implode(',',$_REQUEST['hotelId']);
		$cond 	   .=	" AND `fo_reservations`.`id_mst_hotels` in (".$hotel_ids.")";
		$condlunch .= 	"  `fo_reservations`.`id_mst_hotels` in (".$hotel_ids.") AND ";
	}else{
		$cond 	   .=	" AND `fo_reservations`.`id_mst_hotels` in ('1')";
		$condlunch .= 	"  `fo_reservations`.`id_mst_hotels` in ('1') AND ";
	}*/
	if($_REQUEST['res_bookingStatus_new'] != ''){
		$booking_status_arr = $_REQUEST['res_bookingStatus_new'];
		$cond .= " AND `fo_reservations`.`booking_status` in (".$booking_status_arr.") ";
	}
	if($_REQUEST['hk_status'] != ''){
		$hk_statusCondtion = $_REQUEST['hk_status'];
		
	}else{
		$hk_statusCondtion='0';
		}
	if($_REQUEST['company_id'] != ''){
		$cond .= " AND `fo_reservations`.`id_company` = '".addslashes($_REQUEST['company_id'])."'";
	}
	if($_REQUEST['guest'] != ''){
		$cond .= " AND `fo_reservations`.`id_customer` = '".addslashes($_REQUEST['guest'])."'";
	}
	if($_REQUEST['payment_status'] != ''){
		$payment_status_arr = implode(',',$_REQUEST['payment_status']);
		$cond .= " AND `fo_reservations`.`payment_status` in (".$payment_status_arr.") ";
	}
	
	//checkin_radio
	if($_REQUEST['period'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['period']);	
		$splitArray= explode(" to ",$_REQUEST['period']);
		$checkin = $splitArray['0'];
		$checkout = $splitArray['1'];
		//$cond .= " AND `fo_reservations`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `fo_reservations`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND DATE(`fo_reservations_details`.`checkout_date`) BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
			$condlunch .= " DATE(`fo_reservations`.`checkout`) BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND DATE(`fo_reservations_details`.`checkout_date`) ='".date('Y-m-d',strtotime($checkin))."'";
			$condlunch .= " DATE(`fo_reservations`.`checkout`)='".date('Y-m-d',strtotime($checkin))."'";
		}
		$datewise_array = array();
		$checkinDate = date('Y-m-d',strtotime($checkin));
		$checkoutDate = date('Y-m-d',strtotime($checkout));
		while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
			$datewise_array[] = $checkinDate;
			$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
		}
	}	
	if($_REQUEST['id_executive'] != ''){
		//$cond .= " AND `fs_users`.`id` = '".addslashes($_REQUEST['id_executive'])."'";		
	}
   /* $sql = " SELECT `fo_reservations`.*, `fo_reservations_details`.id_mst_room_types ,`fo_reservations_details`.id_fo_rate_plan,
	sum(`fo_reservations_details`.room_quantity) as room_quantity,
	sum(`fo_reservations_details`.room_quantity * `fo_reservations_details`.adults_per_room) as adults
      FROM `fo_reservations`  
     
    
     INNER JOIN `fo_reservations_details` on fo_reservations.id=fo_reservations_details.id_fo_reservations
     ".$cond." group by `fo_reservations_details`.id_mst_room_types,`fo_reservations_details`.id_fo_rate_plan	, `fo_reservations_details`.id_fo_reservations,`fo_reservations_details`.dated order by `fo_reservations`.checkin, `fo_reservations`.id_mst_hotels";*/
$sql = " SELECT `fo_reservations`.*, `fo_reservations_details`.id as id_reservations_details ,`fo_reservations_details`.id_mst_room_types ,`fo_reservations_details`.id_fo_rate_plan,
	`fo_reservations_details`.room_quantity,
	`fo_reservations_details`.adults_per_room as adults,`fo_reservations_details`.id_mst_room_no_allocation,`fo_reservations_details`.checkin_date as checkin_user_status, `fo_reservations_details`.checkout_date as checkout_user_status,`fo_reservations_details`.no_showoff,`fo_reservations_details`.checkin_status,fo_reservations_details.dated 
      FROM `fo_reservations`  
     
    
     LEFT JOIN `fo_reservations_details` on fo_reservations.id=fo_reservations_details.id_fo_reservations
     ".$cond." and ".FO_RESERVATIONS_DETAILS.".checkout_status ='1' order by `fo_reservations`.checkout, `fo_reservations`.id, `fo_reservations`.id_mst_hotels";
 $SQLSalesReportPayment=$sql;
//echo '=================>'.$SQLSalesReportPayment;






$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);
$BookingStatusArray=array();
$ics=1;
while($row	   =	mysqli_fetch_object($querySalesReportPayment)){
	
		//foreach($datewise_array as $checkinDatearr){			
				$checkinDatearr = date('d-m-Y',strtotime($row->checkout_user_status));
				
			//	if(strtotime($checkinDatearr)>=strtotime($row->checkin) && strtotime($checkinDatearr)<strtotime($row->checkout)){
					
					
					$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				$FHstatusGroup='';$hk_statusCondtionValue='';
			
					
					 $room_no =  $row->id_mst_room_no_allocation=='0'?'0':selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$row->id_mst_room_no_allocation."'");
					 $FHstatusGroup=$FHstatusGroup.$hkcheckout_user_status;
					 
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["booking_no"]=$row->booking_no;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["id_mst_hotels"]=$row->id_mst_hotels;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["id_mst_room_types"]=$row->id_mst_room_types;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["id_fo_rate_plan"]=$row->id_fo_rate_plan;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["lunch_type"]=$row->type==L?"Yes":"No";
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["lunch_count"]=$row->type==L?$row->adults:0;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["reference"]=$row->reference;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["company"]=$row->id_mst_company;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["customer"]=$row->id_mst_guest;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["payment_status"]=$row->payment_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["booking_status"]=$row->booking_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["invoice_date"]=$row->invoice_date;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["name_executive"]=$row->name_executive;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["checkin"]=date('Y-m-d',strtotime($row->checkin));
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["checkout"]=date('Y-m-d',strtotime($row->checkout));
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["no_of_days"]=$row->no_of_days;
					
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["adults"]=$row->adults;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["total_infants"]=$row->infants;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["total_child"]=$row->child;
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["total_products"]=$row->room_quantity;      $datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["room_no"]=$room_no;
					
						$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["checkin_user_status"]=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["checkout_user_status"]=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
											
					$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["no_showoff"]=$row->no_showoff;
				$datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["checkin_status"]=$row->checkin_status;
			
			
			$BookingStatusName = selectColumn('fo_booking_status','name'," WHERE `id` = '".$row->booking_status."'");		
					 
			$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				
			$FHstatus='Checkout';
					
					 $BookingStatusArray[$checkinDatearr]['Booking Status'][$BookingStatusName] +=1;
			 		 $BookingStatusArray[$checkinDatearr]['Front Office Status'][$FHstatus] +=1;
					 
					 $datawisearrayFinal[$checkinDatearr][$row->id_reservations_details][$FHstatusGroup]["HK_status"]=$FHstatus;
			
					
				
				
			//}
	//}
 
	}

	

//debugdata($BookingStatusArray);

//die;
$contentqq = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 

 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style> 


<style>
	  .line:hover {
	background-color:#cf5;
	cursor: pointer;
}
.subgrouphideclass:hover {
	background-color:#cf5;
	cursor: pointer;
}
.table { 
    margin: 0 auto;
    width:100%;
    border-collapse: collapse;
    table-layout:fixed;
}
.table td,
.table th{
    padding:5px 10px;
    border:1px solid #444;
}';


$contentsss .= '</style>';

$content111 ='';
//if($_REQUEST['pdf']==1){
    $content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';
  $content = '<style>
body { 
	margin:0px; 
	padding:0px;
	font-size:13px !important;
 
 }
.table-bordered {
    	 border: 1px solid #000;
	 border-collapse: collapse;
}
.table {
	font-size:11px !important; 
    margin-bottom: 20px;	   
    width:100%;
} 
table {
	font-size:11px !important; 
    background-color: transparent;
    border-collapse: collapse;
    border-spacing: 0;
	}
.table-bordered > tbody > tr > td, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > td,  .table-bordered > thead > tr > td, .table-bordered > thead > tr > th {	
    border-collapse: collapse; border: 1px solid #000;
}
.table td, .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
    color: #000; border-collapse: collapse; border: 1px solid #000;
    
    
}
.fitwidth{
	
	}
.page_break { page-break-before: always;float:left;
 }
 
 .page_autobreak{ page-break-before: always;
 }
 .generalTermClass table{
 	width:100% !important;
 }
</style>';
$foldername =    "/app";

$pathImg = $_SERVER['DOCUMENT_ROOT'].$foldername;

$BackgroundColorMain	='background-color:#edf2f4;';
$BackgroundColor	='background-color:#fff;';
if($report_show!=1){
	/*$content .= '<table  class="table" style=" text-align:center;margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />
						 </th>
						</tr>
			</table>';*/
}
?>
		<table class="table"  >
                  <thead>
                  </thead>
                  <tbody>
                    <?php 		
				
				//if($total > 0){$counter = 1;
				
				foreach($datawisearrayFinal as $dateCheckin=>$dateData){?>
                    <tr>
                      <th colspan=1 style="background-color:#01B9F5; color: white;">Date: <?php echo dateformat_date($dateCheckin)?></th>
                    </tr>
                    <tr>
                      <td colspan=11><table class="table"  style="  width:100%;">
                          <tr style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#c2d69a;">
                            <th>Hotel Name</th>
							  <th>Reservation Id</th>
                            <th>Other Referance</th>
                            <th>Room Type</th>
 							<th>Rate Plan</th>
                            <th>Guest Name</th>
                            <th>Source</th>
                            
                            <th>Booking Status</th>                           
                            
                            <th>Checkin-Checkout</th><th>Checkout</th>
                            <th>No of Nights</th>
                           
                            <th>Adults</th>
                            <th>Room No</th>
                            <th>Front Office Status</th>
                            <?php if(isset($_POST['total_products']) && $_POST['total_products']==1){
?>
                            <th>Rooms</th>
                            <?php } ?>
                            <?php if(isset($_POST['total_adults']) && $_POST['total_adults']==1){
?>
                            <th>Adults</th>
                            <?php } ?>
                            <?php if(isset($_POST['total_infants']) && $_POST['total_infants']==1){
?>
                            <th>Infants</th>
                            <?php } ?>
                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){
?>
                            <th>Lunch Booking</th>
                            <?php } ?>
                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){
?>
                            <th>Lunch Count</th>
                            <?php } ?>
                            <?php if(isset($_POST['total_child']) && $_POST['total_child']==1){
?>
                            <th>Child</th>
                            <?php } ?>
                            
                          </tr>
                          <?php
						
					
					foreach($dateData as $dateData2){
							
							
					foreach($dateData2 as $room_idfromarr=>$order_data){
							
				
					?>
                          <tr>
                            <td><?php echo selectColumn('mst_hotels','name'," WHERE `id` = '".$order_data['id_mst_hotels']."'");?></td>
							  <td><?php echo $order_data['booking_no']?></td>
                            <td><?php echo $order_data['reference']?></td>
                            <td><?php echo selectColumn('mst_room_types','name'," WHERE `id` = '".$order_data['id_mst_room_types']."'");?></td>
                         
                            <td><?php echo selectColumn('fo_rate_plan','name'," WHERE `id` = '".$order_data['id_fo_rate_plan']."'");?></td>
                            <td style="width:200px"><?php echo selectColumn('mst_guest','CONCAT(first_name," ",last_name)'," WHERE `id` = '".$order_data['customer']."'");?></td>
                            <td style="width:200px"><?php echo selectColumn('mst_company','name'," WHERE `id` = '".$order_data['company']."'");?></td>
                            
                            <td><?php echo $BookingStatus	=	selectColumn('fo_booking_status','name'," WHERE `id` = '".$order_data['booking_status']."'");?></td>
                           
                            
                            <td><?php echo dateformat_date($order_data['checkin'])." - ".dateformat_date($order_data['checkout']);?></td>
							<td><?php echo dateformat_date($order_data['checkout_user_status']);?></td>
                            <td><?php echo $order_data['no_of_days']?></td>
                            
                            <td><?=$order_data['adults'];?></td>
                            <td><?=$order_data['room_no'];?></td>
                            <td>
                                                <?php
					
					if ($dateCheckin == $order_data['checkin_user_status']) {
						$status = "Checkin/Occupied";
					}
					elseif ($dateCheckin == $order_data['checkout_user_status']) {
						$status = "Checkout ";
						//$status =  "Checkout/Vacant";
					}
					elseif ($dateCheckin == $order_data['checkin_user_status'] && $dateCheckin == $order_data['checkout_user_status']) {
						$status =  "Checkin/Checkout";
					}
					
					elseif ($order_data['no_showoff']=='1' && $order_data['checkin_user_status']=='') {
						$status =  "No showoff";
					}
					elseif($order_data['checkin_user_status']=='' && $order_data['no_showoff']=='0' && $order_data['checkin_status']==0  ) {
						$status =  "Pending";
						if($BookingStatus=='Cancelled'){
							$status =  "Cancelled";
							}
					}else{
						
					$status = "Occupied";	
						}
					echo $status;

                                                   
                                                ?>
                                            </td>
                            
                            <?php if(isset($_POST['total_products']) && $_POST['total_products']==1){
								?>
                            <td><?=round($order_data['total_products']);?></td>
                            <?php } ?>
                            <?php if(isset($_POST['total_adults']) && $_POST['total_adults']==1){
							?>
                            <td><?=$order_data['total_adults'];?></td>
                            <?php } ?>
                            <?php if(isset($_POST['total_infants']) && $_POST['total_infants']==1){
					?>
                            <td><?=$order_data['total_infants'];?></td>
                            <?php } ?>
                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){
						?>
                            <td><?php echo $order_data['lunch_type']?></td>
                            <?php } ?>
                            <?php if(isset($_POST['lunch_booking_chk']) && $_POST['lunch_booking_chk']==1){
						?>
                            <td><?php echo $order_data['lunch_count']?></td>
                            <?php } ?>
                            <?php if(isset($_POST['total_child']) && $_POST['total_child']==1){
					?>
                            <td><?=$order_data['total_child'];?></td>
                            <?php } ?>
                              </tr/>
                            <?php
					}
					}
					
					//$BookingStatusArray[$checkinDatearr]['Booking Status'][$BookingStatusName] +=1;
			 		// $BookingStatusArray[$checkinDatearr]['Front Office Status'][$FHstatus] +=1;
				?> <tr>
                                    <td colspan="6"></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b>Day Total</b></td>
                                    <td style="color:#000;background-color:#c2d69a;">
                                   
                  
                                    <?php 
									
									//$dateCheckin
									foreach($BookingStatusArray[$dateCheckin]['Booking Status']  as $statusName=>$StatusValue){
										echo '<b>'.$statusName.': <span style="float: right;">'.$StatusValue.'</span></b><br>';
									}
									
									?></td><td style="color:#000;background-color:#c2d69a;"><b><?php echo $adults; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $adults; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $child; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $tariffs; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b><?php echo $taxes; ?></b></td>
                                    <td style="color:#000;background-color:#c2d69a;"> <?php 
									
									//$dateCheckin
									foreach($BookingStatusArray[$dateCheckin]['Front Office Status']  as $statusName=>$StatusValue){
										echo '<b>'.$statusName.': <span style="float: right;">'.$StatusValue.'</span></b><br/>';
									}
									
									?></td>
                                </tr>
                                
                                
                        </table></td>
                    </tr>
                    
                    <?php
					}
					
				?>
                    
                  </tbody>
                </table>
		<?php 	
		
		
		
	
	
	
	
	
		
		
		
		
	
		//echo $content;
//		die;
		$date=date('d-m-Y');

$Filename='FoDateWiseReport_'.$date;	
	

	if($report_show==3){ //print_r($_REQUEST);die;
			$dompdf = new DOMPDF();
			$dompdf->set_paper('landscape', 'landscape');
			$dompdf->load_html($content);
			$dompdf->render();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
			$dompdf->output();
			$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	}else if($report_show==2){
			 $test=$content;
			//die;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;die;
	}
	else{
		 echo $content;
		die;
		}

	
    
    }
    ?>