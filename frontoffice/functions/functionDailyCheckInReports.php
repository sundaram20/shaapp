<?php 

	function FoDateWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($date != ''){
		$DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		
			
		//$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
		//$SqlConn2 .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
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
 
	
	if($date == ''){
	
	 $newDate	=	date('Y-m-d');
	//$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS."`.checkin) = '".date('Y-m-d',strtotime($newDate))."'  ";
	}
	



if($date!=''){
	    $newDate	=	date('Y-m-d',strtotime($date));
	
	   $DateExplode = explode(' to ',$_REQUEST['period']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
	
	
	
	//$searchDocumentType = " and DATE(`".FO_RESERVATIONS."`.checkin)='".addslashes($newDate)."' ";
	$searchDocumentTypeDetails = " AND DATE(`".FO_RESERVATIONS."`.checkin)=  '".date('Y-m-d',strtotime($startDate))."'  ";
	}
	
	
	

	
	
	
	
 $TodaysData	=	$newDate;
    $sql="SELECT ".FO_RESERVATIONS_DETAILS.".*,`".FO_RESERVATIONS."`.* 
  FROM ".FO_RESERVATIONS_DETAILS." LEFT JOIN `".FO_RESERVATIONS."` ON   `".FO_RESERVATIONS_DETAILS."`.id_fo_reservations=".FO_RESERVATIONS.".id  
  where   
   ".FO_RESERVATIONS_DETAILS.".checkin_status ='1'   and `".FO_RESERVATIONS."`.booking_status IN ('1','2') 
  ".$searchDocumentType." ".$searchDocumentTypeDetails."
 
  group by id_fo_reservations,id_mst_room_types order by `".FO_RESERVATIONS."`.id desc";

$res = mysqli_query($connNew,$sql);

	

	

//debugdata($datawisearrayFinal);

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

?>
		<table class="table"  >
                  <thead>
                  </thead>
                  <tbody>
                  
                  
                    <tr style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;    background-color:#ebf8a4;">
                             <th colspan="11" style=" text-align: center;    font-size: 15px;">Checkin Report For <?php echo date('d-m-Y',strtotime($newDate));?></th>
                            </tr>
                          <tr style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#01B9F5;">
                             <th>S.No</th>
                              <th>Room No</th>
                                  <th>Reservation Id</th>
                                  <th>Guest Name</th>
                                  <th>Source</th>
                                  <th>Room Type</th>
                                  <th>Adults | Childs</th>
                                 
                                  
                                   <th>Checkin</th>
                                   <th>checkout</th>
                                 
                           
                          </tr>
                     <?php    if(mysqli_num_rows($res)>0){$y=1;
	while($row = mysqli_fetch_object($res)){
		
		$roomCheckinArray=array();
		$roomPIcked=0;
		
		//$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` ");
		//$countRoom	=  selectColumn(FO_RESERVATIONS_DETAILS,'count(id)'," WHERE where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room,order_by_room ");
		$sqlOrderDetailRoomData = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ");
		
		
		//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by order_by_room ";
		
		
		$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id_fo_reservations)."' and  id_mst_room_types='".addslashes($row->id_mst_room_types)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room ");
		
		
		//================================
			$sqlOrderDetail2 = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' group by id_mst_room_types,id_mst_room_no_allocation ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetail2) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail2= mysqli_fetch_object($sqlOrderDetail2)){
					
							if($rowOrderDetail2->checkin_status==1){	
							$roomCheckinArray[]=$rowOrderDetail2->id_mst_room_no_allocation;
							}
	}	
	
		}
		//==============================
		
		if(is_array($roomCheckinArray) ){
			$roomPIcked = count($roomCheckinArray);
			}else{
		$roomPIcked = 0;
			}
		
		
		if(mysqli_num_rows($sqlOrderDetailRoomData) >0 ){
			$RoomWiseArray=array();
			$roomname=array();
			$room_quantity='';
			$adults_per_room='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetailRoomData)){ 
				$roomname[]	=	selectColumn(TBL_ROOM_TYPE,'name'," WHERE `id` = '".$rowOrderDetail->id_mst_room_types."'");
				$room_quantity	+=	1;//$rowOrderDetail->room_quantity;	
				$adults_per_room	+=	$rowOrderDetail->adults_per_room;	
				
				
				
				
				
			
				}
		}
		
		$room	=	implode(',',array_unique($roomname));
		$source	=	selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$row->id_mst_company."'");
				
$Firstname	   =	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$row->id_mst_guest."'");
$Lastname		=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$row->id_mst_guest."'");

$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$row->id_mst_guest."'");				
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				

$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));
		//$phone	=	selectColumn(TBL_GUEST,'phone'," WHERE `id` = '".$row->id_mst_guest."'");
		//$row['first_name'].' - '.$row['email'].' - ' . $phone . ' - ' . $row['city'].'
			
		/*$demoData[] = 
    array("id"=>encryptor(encrypt,$row->id),"reservation_id"=>$row->id,"booking_no"=>$row->booking_no,"guest"=>$guest,"source"=>$source,"roomType"=> array($room),"persons"=>$adults_per_room." | 2","booked"=>$room_quantity,"pending"=>"2","checkin"=>date('d-m-Y',strtotime($row->checkin)),"checkout"=>date('d-m-Y',strtotime($row->checkout)));*/
	
	$id=encryptor(encrypt,$row->id);
	$reservation_id=$row->id;
	$room_no	=	selectColumn('mst_room_no_allocation','room_no'," WHERE `id` = '".$row->id_mst_room_no_allocation."'");
	?>
   
                                 <tr>
                                    <td><?php echo $y++; ?></td>
                                    <td><?php  echo $room_no; ?></td>
                                    <td onclick="reservationDetails(<?php echo $id; ?>)"><a href="#" style="color:black;"><?php echo $row->booking_no; ?></a></td>
                                    <td onclick="guestDetails('<?php echo $id; ?>','edit')"><a href="#" style="color:black;"><?php  echo $guestName; ?></a></td>
                                    <td><?php echo $source; ?></td>
                                    <td><?php echo $room; ?></td>
                                    <td><?php  echo $adults_per_room." | 2"; ?></td>
                                                                       
									<td><?php  echo date('d-m-Y',strtotime($row->checkin)); ?></td>
									<td><?php  echo date('d-m-Y',strtotime($row->checkout)); ?></td>
                                   
                                
                               
    <?php
   
		}
	}else{ 
			echo '<tr>
                                    <td colspan="11" style="text-align:center;">No Record</td><tr>';
			
			}
?>
                        </table>
                    
                    
                  </tbody>
                </table>
		<?php 	
		
		
		
	
	
	
	
	
		
		
		
		
	
		//echo $content;
//		die;
		$date=date('d-m-Y');

$Filename='FoCheckinReport_'.$date;	
	

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