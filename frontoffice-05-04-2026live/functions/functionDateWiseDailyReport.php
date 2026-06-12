<?php 

	function FoDateWiseReportDaily($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$res_bookingStatus_new,$hk_status,$id_shop,$pdfNameReport3){
		
		
		
	global $connNew;
	global $objPHPExcel;
	
	//echo  $_REQUEST['hk_status'];
	//echo '--->'.$hk_status;die;
	
	//$_REQUEST['period'],$id_main_group,$id_sub_group,$id_items,$_REQUEST['id_report_type'],$report_show,$_REQUEST['id_order_by'],$_REQUEST['showItemReport'],$_REQUEST['res_bookingStatus_new'],$_REQUEST['hk_status']
		
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


$con=$setcellcount;



$objPHPExcel->getActiveSheet()
->getStyle('M')
->getNumberFormat()
->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2 );

	
			$_REQUEST['hotelId']=1;	
			

$SalesRegisterArray=array();
 $cond = "  where `fo_reservations`.`id_mst_shops` = '".addslashes($id_shop)."' ";
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
	$_REQUEST['period']=$date;
	if($_REQUEST['period'] != ''){
		//list($checkin,$checkout) = split(" to ",$_REQUEST['period']);	
		$splitArray= explode(" to ",$_REQUEST['period']);
		$checkin = $splitArray['0'];
		$checkout = $splitArray['1'];
		//$cond .= " AND `fo_reservations`.`checkin` = '".date('Y-m-d',strtotime($checkin))."' and `fo_reservations`.`checkout` = '".date('Y-m-d',strtotime($checkout))."'";
		if(strtotime($checkin)!=strtotime($checkout)){
			$tillcheckout = date ("Y-m-d", strtotime("-1 day", strtotime($checkout)));
			$cond .= " AND `fo_reservations_details`.`dated` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
			$condlunch .= " `fo_reservations`.`checkin` BETWEEN '".date('Y-m-d',strtotime($checkin))."' And '".date('Y-m-d',strtotime($checkout))."'";
		}else{
			$cond .= " AND `fo_reservations_details`.`dated`='".date('Y-m-d',strtotime($checkin))."'";
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
	`fo_reservations_details`.room_quantity,
	`fo_reservations_details`.adults_per_room as adults,`fo_reservations_details`.id_mst_room_no_allocation,`fo_reservations_details`.checkin_date as checkin_user_status, `fo_reservations_details`.checkout_date as checkout_user_status,`fo_reservations_details`.no_showoff,`fo_reservations_details`.checkin_status 
      FROM `fo_reservations`  
     
    
     LEFT JOIN `fo_reservations_details` on fo_reservations.id=fo_reservations_details.id_fo_reservations
     ".$cond." order by `fo_reservations`.booking_status,`fo_reservations`.checkin, `fo_reservations`.id";
 $SQLSalesReportPayment=$sql;
//echo '=================>'.$SQLSalesReportPayment;






$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);
$BookingStatusArray=array();
$BookingSummaryArray=array();
$BookingSummaryPlanArray=array();
$ics=1;
while($row	   =	mysqli_fetch_object($querySalesReportPayment)){
	
		foreach($datewise_array as $checkinDatearr){			
				
				
				if(strtotime($checkinDatearr)>=strtotime($row->checkin) && strtotime($checkinDatearr)<strtotime($row->checkout)){
					
					
					$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				$FHstatusGroup='';$hk_statusCondtionValue='';
			if($hk_status==0){
				//$FHstatusGroup='All';
				//$hk_statusCondtionValue=0;
				
			//}else{
			$hk_statusCondtion=0;	
			}else{
				
				
				}
				
				
				
				
					if ($checkinDatearr == $hkcheckin_user_status ) {
						$FHstatusGroup = "Occuiped";//"Checkin/Occuiped";
						$hk_statusCondtionValue=1;
					}
					elseif ($checkinDatearr == $hkcheckout_user_status) {
						$FHstatusGroup =  "Checkout/Vacant";
						$hk_statusCondtionValue=2;
					}
					elseif ($checkinDatearr == $hkcheckin_user_status && $checkinDatearr == $hkcheckout_user_status ) {
						$FHstatusGroup =  "Checkin/Checkout";
						$hk_statusCondtionValue=3;
					}
					
					elseif ($hkno_showoff=='1' && $hkcheckin_user_status=='') {
						$FHstatusGroup =  "No showoff";
						$hk_statusCondtionValue=4;
					}
					
					elseif($hkcheckin_user_status=='' && $hkno_showoff=='0' && $hkcheckin_status==0  ) {
						if($row->booking_status=='4'){
						$FHstatusGroup =  "Cancelled";
						$hk_statusCondtionValue=7;	
						}else{
						$FHstatusGroup =  "Pending";
						$hk_statusCondtionValue=5;
						}
					}elseif( $hkno_showoff=='0' && $hkcheckin_status==1 && $checkinDatearr != $hkcheckout_user_status && $checkinDatearr != $hkcheckin_user_status){
						
					$FHstatusGroup = "Occuiped";
					$hk_statusCondtionValue=6;	
						}
						
					
							
					
			//}
					//if($hk_status==0 || $hk_status==$hk_statusCondtionValue){
					if(($hk_status==0 &&  ($hk_statusCondtionValue==6 || $hk_statusCondtionValue==1  || $hk_statusCondtionValue==5  )) || ($hk_status==$hk_statusCondtionValue)){
					
					
					 $room_no =  $row->id_mst_room_no_allocation=='0'?'0':selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$row->id_mst_room_no_allocation."'");
					 
					 
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["booking_no"]=$row->booking_no;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["id_mst_hotels"]=$row->id_mst_hotels;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["id_mst_room_types"]=$row->id_mst_room_types;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["id_fo_rate_plan"]=$row->id_fo_rate_plan;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["lunch_type"]=$row->type==L?"Yes":"No";
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["lunch_count"]=$row->type==L?$row->adults:0;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["reference"]=$row->reference;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["company"]=$row->id_mst_company;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["customer"]=$row->id_mst_guest;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["payment_status"]=$row->payment_status;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["booking_status"]=$row->booking_status;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["invoice_date"]=$row->invoice_date;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["name_executive"]=$row->name_executive;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["checkin"]=date('Y-m-d',strtotime($row->checkin));
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["checkout"]=date('Y-m-d',strtotime($row->checkout));
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["no_of_days"]=$row->no_of_days;
					
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["adults"]=$row->adults;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["total_infants"]=$row->infants;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["total_child"]=$row->child;
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["total_products"]=$row->room_quantity;      				$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["room_no"]=$room_no;
					
						$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["checkin_user_status"]=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["checkout_user_status"]=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
											
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["no_showoff"]=$row->no_showoff;
				$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["checkin_status"]=$row->checkin_status;
			
			
			$BookingStatusName = selectColumn('fo_booking_status','name'," WHERE `id` = '".$row->booking_status."'");		
					 
			$hkcheckout_user_status=$row->checkout_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkout_user_status));
			$hkcheckin_user_status=$row->checkin_user_status == '' ? '' : date('Y-m-d',strtotime($row->checkin_user_status));
			$hkcheckin_status=$row->checkin_status;
			$hkno_showoff=$row->no_showoff;
				
			
				
					if ($checkinDatearr == $hkcheckin_user_status ) {
						$FHstatus = 'Occuiped';//"Checkin/Occuiped";
						
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
						
						if($row->booking_status=='4'){						
							$FHstatus =  "Cancelled";
							
						}else{							
							$FHstatus =  "Pending";
						
						}
						
						
					}elseif( $hkno_showoff=='0' && $hkcheckin_status==1 && $checkinDatearr != $hkcheckout_user_status && $checkinDatearr != $hkcheckin_user_status){
						
						$FHstatus = "Occuiped";
						
						}		
					//echo $status
					
					 $BookingStatusArray[$checkinDatearr][$FHstatusGroup]['Booking Status'][$BookingStatusName] +=1;
			 		 $BookingStatusArray[$checkinDatearr][$FHstatusGroup]['Front Office Status'][$FHstatus] +=1;
					 
					$datawisearrayFinal[$checkinDatearr][$FHstatusGroup][$row->id_reservations_details][$FHstatusGroup]["HK_status"]=$FHstatus;
					
					
					$planname	=	selectColumn('fo_rate_plan','name'," WHERE `id` = '".$row->id_fo_rate_plan."'");
					if($FHstatus=='Occuiped' || $FHstatus=='Pending'){
					$BookingSummaryArray[$checkinDatearr]['Summary'][$FHstatus]['RoomCount'] +=1;
					$BookingSummaryArray[$checkinDatearr]['Summary'][$FHstatus]['Name'] =$FHstatus;
					$BookingSummaryArray[$checkinDatearr]['Summary'][$FHstatus]['adults'] +=$row->adults;
					$BookingSummaryArray[$checkinDatearr]['Summary'][$FHstatus]['child'] +=$row->child;
					$BookingSummaryArray[$checkinDatearr]['Summary'][$FHstatus]['Plan'][$planname] +=1;
					}
					
					
					if($FHstatus=='Occuiped' || $FHstatus=='Pending'){
					$BookingSummaryPlanArray[$checkinDatearr]['Summary'][$planname]['RoomCount'] +=1;
					$BookingSummaryPlanArray[$checkinDatearr]['Summary'][$planname]['Name'] =$FHstatus;
					$BookingSummaryPlanArray[$checkinDatearr]['Summary'][$planname]['adults'] +=$row->adults;
					$BookingSummaryPlanArray[$checkinDatearr]['Summary'][$planname]['child'] +=$row->child;
					$BookingSummaryPlanArray[$checkinDatearr]['Summary'][$planname]['Plan'] +=1;
					}
				} //Filter Condition 
				
			}
	}
 
	}

	

//debugdata($BookingSummaryPlanArray);

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
		
                    <?php 		
				
				//if($total > 0){$counter = 1;
				
				foreach($datawisearrayFinal as $dateCheckin=>$dateData){
					
					$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con,'DATE: '.dateformat_date($dateCheckin));
			
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
                  
                   
                    
					foreach($dateData as $nameA	=>$dateData2){
						
						$con++;
							$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con,$nameA);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->getFont()->setBold(true);
						$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':L'.$con);
						 cellColor('A'.$con.':L'.$con,'cdecff');
						 $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
						$con++;
                
                       $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Hotel Name')			
			->setCellValue('B'.$con, 'Reservation Id')							
			->setCellValue('C'.$con, 'Other Referance')		
									
			->setCellValue('D'.$con, 'Room Type')
			
			->setCellValue('E'.$con, 'Rate Plan')							
			->setCellValue('F'.$con, 'Guest Name')
							
			->setCellValue('G'.$con, 'Source')
			
			
			->setCellValue('H'.$con, 'Booking Status')							
			->setCellValue('I'.$con, 'Checkin-Checkout')
							
			->setCellValue('J'.$con, 'Adults')
			->setCellValue('K'.$con, 'Room No')
			->setCellValue('L'.$con, 'Front Office Status');
											
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
			
			 cellColor('A'.$con.':L'.$con,'cdecff');
			//cellColor('E'.$con.':G'.$con,'bcb7b7');	
			//cellColor('H'.$con.':j'.$con,'cdecff');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);		
						
					
                    
                    
					
							
					foreach($dateData2 as $dateData3){		
					foreach($dateData3 as $room_idfromarr=>$order_data){
							
				
					
                         
                            
                                              
					
					
					
					
					
					$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, selectColumn('mst_hotels','name'," WHERE `id` = '".$order_data['id_mst_hotels']."'"))
				->setCellValue('B'.$con, $order_data['booking_no'])
				->setCellValue('C'.$con, $order_data['reference'])
				->setCellValue('D'.$con, selectColumn('mst_room_types','name'," WHERE `id` = '".$order_data['id_mst_room_types']."'"))				
			    ->setCellValue('E'.$con, selectColumn('fo_rate_plan','name'," WHERE `id` = '".$order_data['id_fo_rate_plan']."'"))
				->setCellValue('F'.$con, selectColumn('mst_guest','CONCAT(first_name," ",last_name)'," WHERE `id` = '".$order_data['customer']."'"))						
				->setCellValue('G'.$con, selectColumn('mst_company','name'," WHERE `id` = '".$order_data['company']."'"))
				->setCellValue('H'.$con, selectColumn('fo_booking_status','name'," WHERE `id` = '".$order_data['booking_status']."'"))
				->setCellValue('I'.$con, dateformat_date($order_data['checkin'])." - ".dateformat_date($order_data['checkout']))								
				->setCellValue('J'.$con, $order_data['adults'])
				->setCellValue('K'.$con, $order_data['room_no']);
				
				
					if ($dateCheckin == $order_data['checkin_user_status']) {
						$status = "Checkin/Occuiped";
					}
					elseif ($dateCheckin == $order_data['checkout_user_status']) {
						$status =  "Checkout/Vacant";
					}
					elseif ($dateCheckin == $order_data['checkin_user_status'] && $dateCheckin == $order_data['checkout_user_status']) {
						$status =  "Checkin/Checkout";
					}
					
					elseif ($order_data['no_showoff']=='1' && $order_data['checkin_user_status']=='') {
						$status =  "No showoff";
					}
					elseif($order_data['checkin_user_status']=='' && $order_data['no_showoff']=='0' && $order_data['checkin_status']==0  ) {
						
						
						if($order_data['booking_status']=='4'){
						
						$status =  "Cancelled";	
						}else{
							$status =  "Pending";
						}
						
					
					}else{
						
					$status = "Occuiped";	
						}
					

                        $objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('L'.$con, $status);        
                                           

                       $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
										    
					}
					}
					
					//$BookingStatusArray[$checkinDatearr]['Booking Status'][$BookingStatusName] +=1;
			 		// $BookingStatusArray[$checkinDatearr]['Front Office Status'][$FHstatus] +=1;
					
					$con=$con+1;
					
					 $objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('G'.$con, "Day Total"); 
				 
                  
                                    
									
									//$dateCheckin
									foreach($BookingStatusArray[$dateCheckin][$nameA]['Booking Status']  as $statusName=>$StatusValue){
									//	echo '<b>'.$statusName.': <span style="float: right;">'.$StatusValue.'</span></b><br>';
									}
									
									
									foreach($BookingStatusArray[$dateCheckin][$nameA]['Front Office Status']  as $statusName=>$StatusValue){
										$content .=  '<b>'.$statusName.': <span style="float: right;">'.$StatusValue.'</span></b><br/>';
										$objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('L'.$con, $statusName.": ".$StatusValue);
										
										$objPHPExcel->getActiveSheet()->getStyle('G'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
										
									}
									
						
					}
                         cellColor('G'.$con.':L'.$con,'bcb7b7');       
                        $con=$con+1;
						
                     $objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('A'.$con, "Summary Details"); 
									   
							$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':D'.$con);
								$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	
							
							
						 cellColor('A'.$con.':D'.$con,'cdecff');	
						 $con++;	   
						$con=$con++;
					 $objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'Name')			
			->setCellValue('B'.$con, 'No Of Room')							
			->setCellValue('C'.$con, 'Adults')		
									
			->setCellValue('D'.$con, 'Child');
			
			cellColor('A'.$con.':D'.$con,'bcb7b7');
			
				$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	
			 
							
					foreach($BookingSummaryArray[$dateCheckin] as $BookingSummaryStart){
						$TotalRoomCount	='';
						$Totalchild	='';
						$Totaladults	='';	
						$head_cntr = "A";
					foreach($BookingSummaryStart as $type=>$order_data){
						
						$TotalRoomCount	+=$order_data['RoomCount'];
						$Totalchild	+=$order_data['child'];
						$Totaladults	+=$order_data['adults'];
							$con++;
							$objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('A'.$con, $type)
									                              
                                        ->setCellValue('B'.$con, $order_data['RoomCount']) 
							 			->setCellValue('C'.$con, $order_data['adults'])
										->setCellValue('D'.$con, $order_data['child']); 
							
							
				$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
                    
					}
					$con++;
					$objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('A'.$con, "Total")
									                              
                                        ->setCellValue('B'.$con, $TotalRoomCount) 
							 			->setCellValue('C'.$con, $Totaladults)
										->setCellValue('D'.$con, $Totalchild); 
                   
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
					}
					
					foreach($BookingSummaryPlanArray[$dateCheckin] as $BookingSummaryStart){
						$TotalRoomCount	='';
						$Totalchild	='';
						$Totaladults	='';	
					foreach($BookingSummaryStart as $type=>$order_data){
						
						$TotalRoomCount	+=$order_data['RoomCount'];
						$Totalchild	+=$order_data['child'];
						$Totaladults	+=$order_data['adults'];
						$con++;
				$objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('A'.$con, $type)
									                              
                                        ->setCellValue('B'.$con, $order_data['RoomCount']) 
							 			->setCellValue('C'.$con, $order_data['adults'])
										->setCellValue('D'.$con, $order_data['child']);
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
                   
					}
					$con++;
                   $objPHPExcel->setActiveSheetIndex(0)                           
                                       ->setCellValue('A'.$con, "Total")
									                              
                                        ->setCellValue('B'.$con, $TotalRoomCount) 
							 			->setCellValue('C'.$con, $Totaladults)
										->setCellValue('D'.$con, $Totalchild); 
                   $objPHPExcel->getActiveSheet()->getStyle('A'.$con.':D'.$con)->applyFromArray($styleThinBlackBorderOutline);
                   
					}
					
					
					
					 
                    
				}
				
                  
         
		
	
	

		$date=date('d-m-Y');

//$Filename='FoDateWiseReport_'.$date;	
//$folderPath = "../mailattach/"; // Update this to your desired folder path
//$fileName = $pdfNameReport3.".xls";//$Filename.".xls";	
//$filePath = $folderPath . $fileName;


	if($report_show==3){
		
		$Filename=	$pdfNameReport3;//'nightAuditReports'.date('d-M-Y');
//$objPHPExcel->getActiveSheet(0)->setCellValue('A1',"Flash Summary Report As On  ".date('d-m-Y',strtotime($ReportAsOnDate)));
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');	
$objWriter->save('/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/'.$Filename.'.xls');


			
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
function cellColor($cells,$color){
    	global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}
    ?>