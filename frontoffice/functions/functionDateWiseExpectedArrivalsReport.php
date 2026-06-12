<?php 

function ExpectedArrivalsDateWiseReport($period,$report_show,$shop,$pdfName,$cronSet){
	
	global $connNew;
	global $objPHPExcel;
	//echo '111';die;
	// e.g. "18-03-2025 to 24-03-2025"
	list($startStr, $endStr) = explode(' to ', $period);

// Convert to DateTime objects
$startDate = DateTime::createFromFormat('d-m-Y', trim($startStr));
$endDate = DateTime::createFromFormat('d-m-Y', trim($endStr));

// Calculate the difference in days
$interval = $startDate->diff($endDate);
$days = (int)$interval->format('%a');
		
		
	if($period != ''){
		$DateExplode = explode(' to ',$period);
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

$BookingperiodArray=array();

 $periodRangeHeading=' Expected Arrivals From '.date('d-m-Y',strtotime($DateExplode['0'])).' To '.date('d-m-Y',strtotime($DateExplode['1']));



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
 $cond = "  where `fo_reservations`.`id_mst_shops` = '".addslashes($shop)."' and `fo_reservations`.`booking_status` In (1,2)";
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
	if($period != ''){
		//list($checkin,$checkout) = split(" to ",$period);	
		$splitArray= explode(" to ",$period);
		$checkin = $splitArray['0'];
		$checkout = $splitArray['1'];
		//$cond .= " AND `fo_reservations`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `fo_reservations`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND `fo_reservations_details`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
			$condSum .= " AND `fo_reservations_details`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
			$condlunch .= " `fo_reservations`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND `fo_reservations_details`.`dated`='".date('Y-m-d',strtotime($checkin))."'";
			$condSum .= " AND `fo_reservations_details`.`dated`='".date('Y-m-d',strtotime($checkin))."'";
			
			$condlunch .= " `fo_reservations`.`checkin`='".date('Y-m-d',strtotime($checkin))."'";
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
	
	
	
	
	(fo_reservations_details.room_quantity) AS room_quantity,
    (fo_reservations_details.adults_per_room) AS adults,
	sum(1) AS Totalroom_quantity,
    sum(fo_reservations_details.adults_per_room) AS Totaladults,
	
	
	`fo_reservations_details`.id_mst_room_no_allocation,`fo_reservations_details`.checkin_date as checkin_user_status, `fo_reservations_details`.checkout_date as checkout_user_status,`fo_reservations_details`.no_showoff,`fo_reservations_details`.checkin_status,fo_reservations_details.dated ,fo_reservations_details.id_fo_reservations,fo_reservations_details.tariff_price_per_day_per_room,
	fo_reservations_details.tax_per_day_per_room
  ,fo_reservations.net_booking_amount ,fo_reservations.res_internal_remarks ,fo_reservations.res_special_notes   FROM `fo_reservations`  
     
    
     LEFT JOIN `fo_reservations_details` on fo_reservations.id=fo_reservations_details.id_fo_reservations
     ".$cond."  group by `fo_reservations_details`.id_mst_room_types,`fo_reservations_details`.id_fo_rate_plan	, fo_reservations_details.adults_per_room,`fo_reservations_details`.id_fo_reservations,`fo_reservations_details`.dated  order by `fo_reservations`.checkin, `fo_reservations`.id, `fo_reservations_details`.id_mst_room_types,`fo_reservations_details`.id_fo_rate_plan";
	 
	 
 $SQLSalesReportPayment=$sql;
//echo '=================>'.$SQLSalesReportPayment;






$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);
$BookingStatusArray=array();
$ics=1;
while($row	   =	mysqli_fetch_object($querySalesReportPayment)){
	
	
	
	 '<br>========>'."SELECT 
SUM(fo_reservations_details.room_quantity) AS room_quantity,
SUM(fo_reservations_details.adults_per_room) AS adults

 FROM `fo_reservations_details`  where   fo_reservations_details.id_fo_reservations='.$row->id.'". $condSum;
	
	
	
		//foreach($datewise_array as $checkinDatearr){			
				$checkinDatearr=$row->dated;
				
			//	if(strtotime($checkinDatearr)>=strtotime($row->checkin) && strtotime($checkinDatearr)<strtotime($row->checkout)){
					
					
					$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				$FHstatusGroup='';$hk_statusCondtionValue='';
			if($hk_statusCondtion==0){
				$FHstatusGroup='All';
				$hk_statusCondtionValue=0;
				
			}else{
					if ($checkinDatearr == $hkcheckin_user_status && $hk_statusCondtion=='1') {
						$FHstatusGroup = "Checkin/Occupied";
						$hk_statusCondtionValue=1;
					}
					elseif ($checkinDatearr == $hkcheckout_user_status && $hk_statusCondtion=='2') {
						$FHstatusGroup =  "Checkout/Vacant";
						$hk_statusCondtionValue=2;
					}
					elseif ($checkinDatearr == $hkcheckin_user_status && $checkinDatearr == $hkcheckout_user_status && $hk_statusCondtion=='3') {
						$FHstatusGroup =  "Checkin/Checkout";
						$hk_statusCondtionValue=3;
					}
					
					elseif ($hkno_showoff=='1' && $hkcheckin_user_status=='' && $hk_statusCondtion=='4') {
						$FHstatusGroup =  "No showoff";
						$hk_statusCondtionValue=4;
					}
					elseif($hkcheckin_user_status=='' && $hkno_showoff=='0' && $hkcheckin_status==0  && $hk_statusCondtion=='5') {
						$FHstatusGroup =  "Pending";
						$hk_statusCondtionValue=5;
					}elseif($hk_statusCondtion=='6' && $hkno_showoff=='0' && $hkcheckin_status==1 && $checkinDatearr != $hkcheckout_user_status && $checkinDatearr != $hkcheckin_user_status){
						
					$FHstatusGroup = "Occupied";
					$hk_statusCondtionValue=6;	
						}	
					
			}
					//if($hk_statusCondtion==$hk_statusCondtionValue){
					
					 $room_no =  $row->id_mst_room_no_allocation=='0'?'0':selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$row->id_mst_room_no_allocation."'");
					 $FHstatusGroup=$row->id_fo_rate_plan;
					 $row->id_mst_room_types;
					 
					 
					 $datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["booking_no"]=$row->booking_no;
					 $datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["reference"]=$row->reference;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["company"]=$row->id_mst_company;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["customer"]=$row->id_mst_guest;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["payment_status"]=$row->payment_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["booking_status"]=$row->booking_status;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["checkin"]=date('Y-m-d',strtotime($row->checkin));
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["checkout"]=date('Y-m-d',strtotime($row->checkout));
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["net_booking_amount"]=$row->net_booking_amount;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["res_internal_remarks"]=$row->res_internal_remarks;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["res_special_notes"]=$row->res_special_notes;
		//Addon
					
					  $room_additional_charges_query = mysqli_query($connNew, "select * from fo_reservations_addons_details WHERE `id_fo_reservations` = '".addslashes($row->id_fo_reservations)."' and id_fo_folio_to = '0'");
    while($room_additionals = mysqli_fetch_object($room_additional_charges_query)){
					$additional_description	= $room_additionals->additional_description!=''?'-'.$room_additionals->additional_description:'';
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["addons_details"][]=$room_additionals->item.$additional_description.' @'.($room_additionals->amount+$room_additionals->tax_value);
	}
					//Addon
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["TotalQuantity"]+=$row->Totalroom_quantity;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["TotalAdults"]+=$row->Totaladults;
	
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["sub_adults"]+=$row->Totaladults;
					
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]["sub_total_products"]+=$row->Totalroom_quantity;     
					
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["id_mst_room_types"]=$row->id_mst_room_types;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["id_fo_rate_plan"]=$row->id_fo_rate_plan;
					 
	
	$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["TotalQuantity"]+=$row->Totalroom_quantity;
	$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["TotalAdults"]=$row->adults*$row->Totalroom_quantity;
	
			$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["Adults_per_room"]=$row->adults;
	
	
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["id_mst_hotels"]=$row->id_mst_hotels;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["id_mst_room_types"]=$row->id_mst_room_types;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["id_fo_rate_plan"]=$row->id_fo_rate_plan;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["lunch_type"]=$row->type==L?"Yes":"No";
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["lunch_count"]=$row->type==L?$row->adults:0;
					
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["invoice_date"]=$row->invoice_date;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["name_executive"]=$row->name_executive;
					
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["no_of_days"]=$row->no_of_days;
					
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["adults"]=$row->adults*$row->room_quantity;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["total_infants"]=$row->infants;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["total_child"]=$row->child;
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["total_products"]=$row->room_quantity;     
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["room_no"]=$room_no;
					
						$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["checkin_user_status"]=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["checkout_user_status"]=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
											
					$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["no_showoff"]=$row->no_showoff;
				$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["checkin_status"]=$row->checkin_status;
	$datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["tariff_price_per_day_per_room"]+=$row->tariff_price_per_day_per_room+$row->tax_per_day_per_room;
			
			$BookingStatusName = selectColumn('fo_booking_status','name'," WHERE `id` = '".$row->booking_status."'");		
					 
			$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				
			
					/*(if ($checkinDatearr == $hkcheckin_user_status) {
						$FHstatus = "Checkin/Occupied";
					}
					elseif ($checkinDatearr == $hkcheckout_user_status) {
						$FHstatus =  "Checkout/Vacant";
					}
					elseif ($checkinDatearr == $hkcheckin_user_status && $checkinDatearr == $hkcheckout_user_status) {
						$FHstatus =  "Checkin/Checkout";
					}
					
					elseif ($hkno_showoff=='1' && $hkcheckin_user_status=='') {
						$FHstatus =  "No showoff";
					}
					elseif($hkcheckin_user_status=='' && $hkno_showoff=='0' && $hkcheckin_status==0  ) {
						$FHstatus =  "Pending";
					}else{
						
					$FHstatus = "Occupied";	
						}*/
						
					if ($checkinDatearr == $hkcheckin_user_status ) {
						$FHstatus = "Checkin/Occupied";
						
					}
					elseif ($checkinDatearr == $hkcheckout_user_status ) {
						$FHstatus =  "Checkout/Vacant";
						
					}
					elseif ($checkinDatearr == $hkcheckin_user_status && $checkinDatearr == $hkcheckout_user_status ) {
						$FHstatus =  "Checkin/Checkout";
						
					}
					
					elseif ($hkno_showoff=='1' && $hkcheckin_user_status=='') {
						$FHstatus =  "No showoff";
						
					}
					elseif($hkcheckin_user_status=='' && $hkno_showoff=='0' && $hkcheckin_status==0  ) {
						if($BookingStatusName=='Cancelled'){
							$FHstatus =  "Cancelled";
						}else{
						$FHstatus =  "Pending";
						}
						
					}elseif( $hkno_showoff=='0' && $hkcheckin_status==1 && $checkinDatearr != $hkcheckout_user_status && $checkinDatearr != $hkcheckin_user_status){
						
					$FHstatus = "Occupied";
						
						}		
					//echo $status
					$planname	=	selectColumn('fo_rate_plan','name'," WHERE `id` = '".$row->id_fo_rate_plan."'");
					$BookingStatusArray[$checkinDatearr]['Booking Status'][$BookingStatusName] +=$row->Totalroom_quantity;
					$BookingStatusArray[$checkinDatearr]['Front Office Status'][$FHstatus] +=1;
					$BookingStatusArray[$checkinDatearr]['Plan List'][$planname]['PlanCount'] +=$row->Totalroom_quantity;
					//$BookingStatusArray[$checkinDatearr]['Plan List'][$planname]['adults'] +=$row->adults*$row->room_quantity;
	$BookingStatusArray[$checkinDatearr]['Plan List'][$planname]['adults'] +=$row->adults*$row->Totalroom_quantity;
					$BookingStatusArray[$checkinDatearr]['Plan List'][$planname]['child']  +=$row->child;
	
	
					$BookingperiodArray[$periodRangeHeading][$planname]['PlanCount'] +=$row->Totalroom_quantity;
					//$BookingperiodArray[$periodRangeHeading][$planname]['adults'] +=$row->adults*$row->room_quantity;
	$BookingperiodArray[$periodRangeHeading][$planname]['adults'] +=$row->adults*$row->Totalroom_quantity;
					$BookingperiodArray[$periodRangeHeading][$planname]['child']  +=$row->child;
	
	
	
					
					 $datawisearrayFinal[$checkinDatearr][$row->id_fo_reservations]['BookingDeatils'][$row->id_mst_room_types][$FHstatusGroup][$row->adults]["HK_status"]=$FHstatus;
			
					
				//}
				
			//}
	//}
 
	}

	

//debugdata($BookingperiodArray);

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

		
		$content .= '<table class="table table-bordered" style="width: 100%; border: 0.8px solid #000; border-collapse: collapse;">';


?>
		
                    <?php 		
				
				//if($total > 0){$counter = 1;
				
				foreach($datawisearrayFinal as $dateCheckin=>$dateData){
                    $contentddd .= '<tr>
                      <th colspan=3 style="background-color:#01B9F5; color: white;">Date:'.dateformat_date($dateCheckin).'</th>
                    </tr>
                    <tr>
                      <td colspan=11>';
					  $content .= '<table class="table table-bordered" style="width: 100%; border: 0.8px solid #000; border-collapse: collapse;">';
					  
					   $content.= '<tr>
                      <th colspan=3 style="background-color:#01B9F5; color: white;">Date:'.dateformat_date($dateCheckin).'</th>
                    </tr>';
					  
					  
                          $content .= '<tr style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#c2d69a;">
                            <th>S No</th>
							  <th>Reservation Id</th>
                            <th>Other Referance</th>
                           
                            <th>Guest Name</th>
                            <th>Source</th>
                            
                            <th>Booking Status</th>                           
                            
                            
                             
                           
                            <th>Total Adults</th>
                            <th>Total Rooms</th>
                           
                           
                            
                          </tr>';
						//<th>Checkin-Checkout</th>
					$Sno=1;
					foreach($dateData as $dateData1){
						  //$content .= '<table class="table table-bordered" style="width: 100%; border: 0.8px solid #000; border-collapse: collapse;">';
						
						$content .= '<tr style="font-weight:bold;">
                            <td style="text-align: center;background-color:#eff4e3" >'.$Sno++.'</td>
							  <td style="text-align: center;background-color:#eff4e3">'.htmlspecialchars($dateData1['booking_no']).'</td>
                            <td style="text-align: center;background-color:#eff4e3">'.htmlspecialchars($dateData1['reference']).'</td>
                           
                            <td style="width:200px;background-color:#eff4e3">'.selectColumn('mst_guest','CONCAT(first_name," ",last_name)'," WHERE `id` = '".$dateData1['customer']."'").'</td>
                            <td style="width:200px;background-color:#eff4e3">'.selectColumn('mst_company','name'," WHERE `id` = '".$dateData1['company']."'").'</td>
                            
                            <td style="text-align: center;background-color:#eff4e3">'.$BookingStatus	=	selectColumn('fo_booking_status','name'," WHERE `id` = '".$dateData1['booking_status']."'").'</td>
                           
                            
                            
                            
                            
                            <td style="text-align: center;background-color:#eff4e3;">'.htmlspecialchars($dateData1['sub_adults']).'</td>
                            <td style="text-align: center;background-color:#eff4e3">'.htmlspecialchars($dateData1['sub_total_products']).'</td>
                           
                            
                           
                              </tr>';
                          
							
					$content .= '<tr >
                            <td style="text-align: center;" colspan="1" ></td>
							 <td style="text-align: center;" colspan="2"></td> 
                           

                            <td style="" colspan="5" ><b>Checkin-Checkout :</b>'.dateformat_date($dateData1['checkin'])." - ".dateformat_date($dateData1['checkout']).'</td>
                            
                            
                           
                           
                            
                           
                              </tr>';
					
						
						
					foreach($dateData1['BookingDeatils'] as $order_data3){
						foreach($order_data3 as $order_data2){
						foreach($order_data2 as $order_data){
						
						
						
//debugdata($order_data);
						
							
				
							
                         $content .= ' <tr>
                            <td style="text-align: center;"  ></td>
 <td style="text-align: center;"></td>
                           <td></td>
   
                            
                             <td >'.selectColumn('mst_room_types','name'," WHERE `id` = '".$order_data['id_mst_room_types']."'").'-'.selectColumn('fo_rate_plan','name'," WHERE `id` = '".$order_data['id_fo_rate_plan']."'").'</td>
                         
                           
                            <td  style="">Nights ('.htmlspecialchars($order_data['no_of_days']).')</td>
                             
                            <td  style="">'.htmlspecialchars($order_data['Adults_per_room']).' Adults ('.htmlspecialchars($order_data['TotalAdults']).')</td>
                            <td  >Rooms ('.htmlspecialchars($order_data['TotalQuantity']).')</td>
                            <td></td>
                            
                              </tr>';
								
								
							$content .= ' <tr>
                            <td></td>
							 <td></td>
                           <td></td> 
                            
                             <td  colspan="5"><b>Tariff: INR </b>'.htmlspecialchars($order_data['tariff_price_per_day_per_room']);
						
								
								}
					}
					} 
					  $content .= ' <br/><b> Total Payable amount: INR </b>'.htmlspecialchars($dateData1['net_booking_amount']);
                        
						 if($dateData1['res_internal_remarks']!='' || $dateData1['res_special_notes']!='' ){ 
							 $ins1 = $dateData1['res_internal_remarks'] ?? '';
							 $ins2 = $dateData1['res_special_notes'] ?? '';
							 
							 $ins = $ins1 . " " . $ins2;
                        $contentSpecialInstructions= ' <br/><b> Special Instructions: </b>'.$ins;    
						  }else{
						  $contentSpecialInstructions= '';
						  }
                            $content .= $contentSpecialInstructions;
                           
						
						
						
// 🟨 Addons Details
if (!empty($dateData1['addons_details']) && is_array($dateData1['addons_details'])) {
    $addonsList = implode(', ', array_unique($dateData1['addons_details']));
    $contentAddons = '<br/><b>Charges:</b> ' . $addonsList;
} else {
    $contentAddons = '';
}
			$content .= $contentAddons;			
						
						
						
                             $content .= '</td>  </tr>';
					}
					
					
				$content .= ' <tr>
                                    <td colspan="3"></td>
                                    <td style="color:#000;background-color:#c2d69a;"><b>Day Total</b></td>
                                    <td style="color:#000;background-color:#c2d69a;" >';
                                   
                  
									//$dateCheckin
									foreach($BookingStatusArray[$dateCheckin]['Booking Status']  as $statusName=>$StatusValue){
										$content .= '<b>'.$statusName.': <span style="float: right;">'.$StatusValue.'</span></b><br>';
									}
					/*foreach($BookingStatusArray[$dateCheckin]['Plan List']  as $statusName=>$PlanDetails){
						$content .= '<br/><b>'.$statusName.': <span >'.$PlanDetails['PlanCount'].'</span></b>';
						$content .= '<b>adults: <span >'.$PlanDetails['adults'].'</span></b>';
						$content .= '<b>child: <span >'.$PlanDetails['child'].'</span></b>';
					}*/
			
									
									$content .= ' </td>
                                    
                                    <td style="color:#000;background-color:#c2d69a;" colspan="3">';
					$totalPlans = 0;
$totalAdults = 0;
$totalChildren = 0;

$content .= '<table border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse; padding: 0px;">';
$content .= '<thead>';
$content .= '<tr>';
$content .= '<th style="width: 79px;text-align: center;margin: 0;padding: 0;">Plan</th>';
					$content .= '<th style="width: 79px;text-align: center;margin: 0;padding: 0;">No Of Room</th>';
$content .= '<th style="width: 79px;text-align: center;margin: 0;padding: 0;">Adults</th>';
$content .= '<th style="width: 79px;text-align: center;margin: 0;padding: 0;">Children</th>';
$content .= '</tr>';
$content .= '</thead>';
$content .= '<tbody>';

foreach ($BookingStatusArray[$dateCheckin]['Plan List'] as $statusName => $PlanDetails) {
    $planCount = (int)$PlanDetails['PlanCount'];
    $adults = (int)$PlanDetails['adults'];
    $children = (int)$PlanDetails['child'];

    $content .= '<tr>';
    $content .= '<td  style="border:0px;width: 79px;text-align: center;margin: 0;padding: 0;"> ' . htmlspecialchars($statusName) . '</td>';
    $content .= '<td style="border:0px;width: 79px;text-align: center;margin: 0;padding: 0;">' . $planCount . '</td>';
    $content .= '<td style="border:0px;width: 79px;text-align: center;margin: 0;padding: 0;">' . $adults . '</td>';
    $content .= '<td style="border:0px;width: 79px;text-align: center;margin: 0;padding: 0;">' . $children . '</td>';
    $content .= '</tr>';

    $totalPlans += $planCount;
    $totalAdults += $adults;
    $totalChildren += $children;
}

$content .= '</tbody>';

// Totals Row
$content .= '<tfoot>';
$content .= '<tr style="font-weight: bold;">';
$content .= '<td  style="border:0px;text-align: center;margin: 0;padding: 0;">Total</td>';
$content .= '<td  style="border:0px;text-align: center;margin: 0;padding: 0;">' . $totalPlans . '</td>';
$content .= '<td  style="border:0px;text-align: center;margin: 0;padding: 0;">' . $totalAdults . '</td>';
$content .= '<td  style="border:0px;text-align: center;margin: 0;padding: 0;">' . $totalChildren . '</td>';
$content .= '</tr>';
$content .= '</tfoot>';

$content .= '</table>';


					
					$content .= '</td>';
                                   
                                 // $content .= '<td style="color:#000;background-color:#c2d69a;"></td> ';     
                               //$content .= '<td style="color:#000;background-color:#c2d69a;"></td> ';    
                                $content .= ' </tr>';
                                
                                
                        
                   
					}
		if ($days > 0) {	
foreach ($BookingperiodArray as $dateRange => $plans) {
    $totalPlans = 0;
    $totalAdults = 0;
    $totalChildren = 0;

    // Start table
    $content .= '<table border="0" cellpadding="6" cellspacing="0" style="border-collapse: collapse; padding: 0px;width:50%;">';

    // First header row with date range
    $content .= '<thead>';
    $content .= '<tr style="background-color: #c2d69sssa;">';
    $content .= '<th colspan="4" style="border: 1px solid #ccc; padding: 10px; text-align: center; font-size: 15px;">' . htmlspecialchars($dateRange) . '</th>';
    $content .= '</tr>';

    // Column headers
    $content .= '<tr style="background-color: #c2d69a;">';
    $content .= '<th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Meal Plan</th>';
    $content .= '<th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Plans</th>';
    $content .= '<th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Adults</th>';
    $content .= '<th style="border: 1px solid #ccc; padding: 8px; text-align: center;">Children</th>';
    $content .= '</tr>';
    $content .= '</thead>';

    $content .= '<tbody>';

    foreach ($plans as $planName => $details) {
        $planCount = (int)$details['PlanCount'];
        $adults = (int)$details['adults'];
        $children = (int)$details['child'];

        $content .= '<tr>';
        $content .= '<td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . htmlspecialchars($planName) . '</td>';
        $content .= '<td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $planCount . '</td>';
        $content .= '<td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $adults . '</td>';
        $content .= '<td style="border: 1px solid #ddd; padding: 6px; text-align: center;">' . $children . '</td>';
        $content .= '</tr>';

        $totalPlans += $planCount;
        $totalAdults += $adults;
        $totalChildren += $children;
    }

    // Total row
    $content .= '<tr style="font-weight: bold; background-color: #c2d69a;">';
    $content .= '<td style="border: 1px solid #ccc; padding: 8px; text-align: center;">Total</td>';
    $content .= '<td style="border: 1px solid #ccc; padding: 8px; text-align: center;">' . $totalPlans . '</td>';
    $content .= '<td style="border: 1px solid #ccc; padding: 8px; text-align: center;">' . $totalAdults . '</td>';
    $content .= '<td style="border: 1px solid #ccc; padding: 8px; text-align: center;">' . $totalChildren . '</td>';
    $content .= '</tr>';

    $content .= '</tbody></table>';
	
}
}



		
	
	
	
	$content .= ' </table>
						';
                    
	
		
		
		
		
	
		//echo $content;
		//die;
		$date=date('d-m-Y');

$Filename='FoExpectedArrivalsReport_'.$date;	
	
	if($report_show==3 && $cronSet=='0'){
		
		//print_r($_REQUEST);die;
			$dompdf = new DOMPDF();
			$dompdf->set_paper('landscape', 'landscape');
			$dompdf->load_html($content);
			$dompdf->render();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
			$dompdf->output();
			$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
	}else if($report_show==2 && $cronSet=='0'){
			 $test=$content;
			//die;
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;die;
	}else if($cronSet=='1'){// echo $content;die;
			$dompdf = new Dompdf();
$dompdf->set_paper('A4', 'landscape');
$dompdf->load_html($content);
$dompdf->render();

// Add page numbers
//$font = FontMetrics::get_font("helvetica", "bold");
$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0, 0, 0));
//echo 'Doen';
// Save to server folder
$pdfOutput = $dompdf->output(); // Get PDF content
$filePath = "/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/{$pdfName}.pdf";
file_put_contents($filePath, $pdfOutput); // Save to file

// Auto-download
//$dompdf->stream($Filename . '.pdf', array("Attachment" => true));
		
		
		
	}
	else{
		 echo $content;
		die;
		}

	
    
    }
    ?>