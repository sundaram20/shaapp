<?php

function postAutoTariff($post_tariff_date,$id_post_tariff,$id_fo_bill,$shop,$connNew){
	//echo 'check';die;
	global $connNew;
	if($id_post_tariff !=''){
		
	 	//echo $post_tariff_date.'==='.$id_post_tariff.'==='.$id_fo_bill.'==='.$shop;
	// die;
	 if($id_post_tariff =='2'){
	 //$conn = " AND  id IN (".$id_fo_bill.")";
	 }
		 
	  $resCat = selectSql('fo_folio'," where  id_mst_shops='".addslashes($shop)."' and folio_status='0' $conn  ",' ');
		
		if(mysqli_num_rows($resCat)){ 
		while($resultCat = mysqli_fetch_object($resCat)){
			$id_fo_folio= $resultCat->id;
			$id_fo_bill=  selectColumn(FO_BILL,'id'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
		
			$id_resevation	=  selectColumn(FO_BILL,'id_reservations'," WHERE `id_fo_folio_to` = '".$resultCat->id."'");
			$sqlOrderDetail = mysqli_query($connNew,"Select `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($id_resevation)."' and `no_showoff`='0' and `id_fo_folio_to` = '".$id_fo_folio."' group by `order_by_room` order by id asc");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
			
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($id_resevation)."' ");
			$id_mst_room_types=         selectColumn(TBL_ROOMNO,'id_mst_room_types'," WHERE `room_no` = '".$roomNumber."' ");										

	
			$insertGrid =  "UPDATE `".FO_RESERVATIONS_DETAILS."` SET 				
				  `checkin_status`='1',				 
				 `id_fo_bill`='".$id_fo_bill."',	
				`id_fo_folio`='".addslashes($id_fo_folio)."',
				`id_fo_folio_to`='".addslashes($id_fo_folio)."'
				  where `id_fo_reservations` = '".$id_resevation."' and `no_showoff`='0'
				   and id_mst_room_types='".$id_mst_room_types."' and order_by_room='".$rowOrderDetail->order_by_room."' and  id_mst_room_no_allocation='".$rowOrderDetail->id_mst_room_no_allocation."'
				   and  DATE(dated)='".addslashes($post_tariff_date)."' ";
				// return $insertGrid;die;
			
		mysqli_query($connNew,$insertGrid);
		

												}
											  }
											  }	
		return 'Post Tariff Updated Sucessfully';									   
		
	}else{
		
		return 'Please Select Post Tariff';
		
		}
		 
		 
		
	}

	function docTypeConfig($doc_type,$date,$id_subsection,$doc_table_name,$connNew,$id_shop){
	// global $connNew;
	 $doc_type = $doc_type;	 
	 $po_date = date('Y-m-d' , strtotime($date)); 
	 $date = $date;//date('Y-m-d');
	 $status  =1;
	 $idss = 0;
 

 	$sql4 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND `doc_type`='".$doc_type."' AND date(`effective_date`) <= date('".$date."')   ORDER BY effective_date desc Limit 0,1 ");
	
	$numRows= mysqli_num_rows($sql4); 
	while($row4 = mysqli_fetch_object($sql4)){	  
		 if($row4->effective_date <= $date ){
			 $idss = $row4->id;
			 $effective_date = $row4->effective_date;
		 } 
	}

	$sql = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ");
 	$numRows =  mysqli_num_rows($sql);
	    while($row =  mysqli_fetch_object($sql)){ 
		$idDocConfigDetail= $row->id;
		$method= $row->method; 
			if($id_subsection>0){
				//echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ";
					 $sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						 //echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ";
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config<span>';
							exit;
							}
						 
						 
						 }
			}else{
					
					$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config.<span>';
							exit;
							}
						 
						 
						 }
			
			if($doc_type==22){ //KOT

			$sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config.<span>';
							exit;
							}
						 
						 }
				
				}
	    }

	if($numRows == 0){

		$sqls = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' ");
		    
		    while($rows =  mysqli_fetch_object($sqls)){ 
		$idDocConfigDetail= $row->id;
		$method= $row->method; 
			if($id_subsection>0){
					 $sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config<span>';
							exit;
							}
						 }
			}
	    }
	}

//echo $start_no.'--'.$prefix.'--'.$suffix;
	 $result = getDocConfigIncValue2($doc_type,$date,$id,$method,$id_subsection,$start_no,$prefix,$suffix,$doc_table_name,$connNew,$id_shop);	
	
	return $result;
	
	}



function getDocConfigIncValue2($doc_type,$date,$id,$method,$id_subsection,$start_no,$prefix,$suffix,$doc_table_name,$connNew,$id_shop){
	
	//global $connNew;
	if($method == '1'){

	 $sql2 =  mysqli_query($connNew, "SELECT * FROM `".$doc_table_name."` WHERE `id_mst_shops` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."'  AND date(`doc_date`) <= date('".$date."') order by doc_no desc limit 0,1");    
	
	$numRows= mysqli_num_rows($sql2);

		if($numRows != 0){
			while($row2 = mysqli_fetch_object($sql2)){  
			
				$po_no= $row2->doc_no;
				$po_no =$po_no + 1;
				$res['po_no'] = $po_no;
				$res['id_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['po_no'] = $start_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}


	$sql3 = mysqli_query($connNew," SELECT * FROM `".$doc_table_name."` WHERE `id_mst_shops` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."'  order by doc_no desc limit 0,1");
	
	$numRows= mysqli_num_rows($sql3);

	if($numRows > 0){
		while($row3 = mysqli_fetch_object($sql3)){  
			$po_no= $row3->doc_no;
			$po_no = $po_no + 1;
			$res['po_no'] = $po_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['po_no'] = $start_no;
		$res['id_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}


	return $res;
	
	}	
	





function nightAuditReport($Date,$id_outlet,$id_shift,$objPHPExcel){
	
	
	global $connNew;
	global $objPHPExcel;
	
	
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConn .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
		
	/*if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConn .= " AND `date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlReservationConn .= " AND `dated` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
*/
	$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".$_SESSION['shop']."'");
	$rowShop = mysqli_fetch_object($resShop);
	$logo	=	$rowShop->image;
	
echo '======================================================1';
	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");


 function cellColor($cells,$color){
    	global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}
echo '======================================================4';
//$objDrawing = new PHPExcel_Worksheet_Drawing();
	/*$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../uploaded_files/shop/'.$logo);
	$objDrawing->setCoordinates('L1');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());*/
echo '======================================================1';//die;
$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A7', "Night Audit");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:M7');



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A7:M7')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
$con=$setcellcount;


	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$con,'From Date '.$Date);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':M'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

$con++;

$sqlOrderDetail="select p.id ,

case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'CASH' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then 'CARD'
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'CHEQUE'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then 'UPI'
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then 'ONLINETRANSFER'
     when payment_mode='COMPANY' and IFNULL(amount,0)>0 then 'COMPANY'
	 when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then 'ROOMTO'
	 when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then 'BIllONHOLD'
    else 0 end
    as payment_type ,
    
    case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'Cash Sales' 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then 'Bill On Hold'
	when payment_mode='CARD' and IFNULL(amount,0)>0 then id_charges_master
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'Cash Sales'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then remark
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then id_charges_master  	  
    else 0 end
    as payment_remarks ,
	
	case when folio_status='1'  then 'Close' 
	when folio_status='0' then 'Open'
   	  
    else 0 end
    as folio_status ,
	
	
	case when payment_mode='COMPANY' and IFNULL(amount,0)>0 then id_company
    else 0 end
    as id_company ,
	pp.id_fo_bill ,
	pp.id_fo_folio ,
	
	
	case when payment_mode='CASH' and IFNULL(amount,0)>0 then amount 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then amount
	when payment_mode='CARD' and IFNULL(amount,0)>0 then amount
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then amount
    when payment_mode='UPI' and IFNULL(amount,0)>0 then amount
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then amount
    when payment_mode='COMPANY' and IFNULL(amount,0)>0 then amount
	when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then amount
    else 0 end
    as dramount ,


case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH ,
case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD ,
case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD ,

case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE ,
case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI ,
case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER ,
case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY ,
case when payment_mode='ROOMTO' then IFNULL(amount,0) else null end as ROOMTO ,

DATE(p.date_created) as date_created 
 from fo_receipt pp 
INNER JOIN 	fo_folio p on p.id = pp.id_fo_folio
 
WHERE p.id!=0 and pp.amount>0  $SqlConn ORDER BY  pp.id_fo_folio ASC";

echo $sqlOrderDetail;
	 // echo $sqlOrderDetail = "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where checkin_status='1' $SqlReservationConn ";
		


//die;

$queryReceipt = mysqli_query($connNew,$sqlOrderDetail);
$TotalNumberOfRowReceipts = mysqli_num_rows($queryReceipt);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
$nightAudit=array();
while($RecordsReceipt	   =	mysqli_fetch_object($queryReceipt)){
	
	
	//$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$RecordsSalesReportPayment->id_fo_bill."'");
	//$roomNumber= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
	
	$id_reservations	=	selectColumn('fo_bill','id_reservations','WHERE `id`="'.$RecordsReceipt->id_fo_bill.'"');
	
	$tax_per_day_per_room	=	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tax_per_day_per_room)'," WHERE `id_fo_folio` = '".$RecordsReceipt->id_fo_bill."'");
	
	
	
	
	
	
	$tariff_price_per_day_per_room	=	selectColumn(FO_RESERVATIONS_DETAILS,'sum(tariff_price_per_day_per_room)'," WHERE `id_fo_folio` = '".$RecordsReceipt->id_fo_bill."'");
	
				$outlet_Name ='Tariff';
				$id_mst_outlet='000121';
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Taxes']+=$RecordsReceipt->tax_per_day_per_room;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['mdoc_no']= $RecordsReceipt->mdoc_no;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['bill_date_created']= date('d-m-Y',strtotime($RecordsReceipt->bill_date_created));  
				
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Taxes'] += $tax_per_day_per_room;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['sub_total_items'] += $tariff_price_per_day_per_room;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Discount']+=  $RecordsReceipt->Discount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['NetAmount']+=  ($tax_per_day_per_room + $tariff_price_per_day_per_room)-$RecordsReceipt->Discount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['sgst']+=  $RecordsReceipt->sgst+$RecordsReceipt->sc_sgst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['cgst']+=  $RecordsReceipt->cgst+$RecordsReceipt->sc_cgst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['igst']+=  $RecordsReceipt->igst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['cess']+=  $RecordsReceipt->cess;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['vat']+=  $RecordsReceipt->vat;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['surcharge']+=  $RecordsReceipt->surcharge;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['OtherCharges']+=  $RecordsReceipt->OtherCharges+$RecordsReceipt->sc_charges_net_amount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['round_off_amount']+=  $RecordsReceipt->round_off_amount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['grant_total_amount']+=  ($tariff_price_per_day_per_room+$tax_per_day_per_room);
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['UserName']= $RecordsReceipt->UserName;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Time']= $RecordsReceipt->Time;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Cash']+=  $RecordsReceipt->CASH;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Card']+=  $RecordsReceipt->CARD;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Company']+=  $RecordsReceipt->COMPANY;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Cheque']+=  $RecordsReceipt->CHEQUE;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['OnlineTransfer']+=  $RecordsReceipt->ONLINETRANSFER;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Billonhold']+=  $RecordsReceipt->BIllONHOLD;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['RoomTo']+=  $RecordsReceipt->ROOMTO;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['mode']= $RecordsReceipt->paidTo;
				
	
	}
	//END TARIFF====================================================
	//debugData($nightAudit);
	//die;
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConnOutLet .= " AND `date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConnOutLet .= " AND `id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConnOutLet .= " AND `id_attribute_shift` IN (".$id_shift.")";
	}
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date("Y-m-d",  strtotime($endDate));//date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		
	}
 $SQL="select 
       id as 'Payment ID'
	  ,id_attribute_shift
	  ,id_attribute_table
	  ,pax
      ,id_mst_outlet
      ,outlet_Name
      ,mdoc_no
      ,sum(sub_total_items)sub_total_items
      ,sum(discount_amount_additional+total_discount_items) AS 'Discount'
      ,sum(others_charges_net_amount) As 'OtherCharges'      
      ,sum(sgst_total_items) as sgst
      ,sum(cgst_total_items) as cgst
      ,sum(igst_total_items) as igst
      ,sum(cess_total_items) as cess
      ,sum(vat_total_items) as vat
      ,sum(surcharge_total_items) as surcharge      
      ,sum(round_off_amount) as round_off_amount
      ,sum(net_amount_items) as net_amount_items
      ,sum(grant_total_amount) as grant_total_amount
      ,name as 'UserName'
	  ,max(time) as Time
	  ,sum(CASH) as Cash
	  ,sum(CARD) as Card
	  ,sum(BIllONHOLD) as Billonhold
 	  ,sum(ROOMTO) as RoomTo
	  ,sum(CHEQUE) as Cheque
	  ,sum(UPI) as 'GiftVoucher'
	  ,sum(ONLINETRANSFER) as 'OnlineTransfer'
	  ,sum(COMPANY) as Company
	  ,field_value as 'FeildValue'
      ,max(date_created) as 'CreatedDate'
	  ,max(bill_date_created) as 'bill_date_created'
	  ,id_charges_master
	  ,remark
	  ,doc_no
	  ,id_company
	  ,sc_charges_net_amount
	  ,sc_sgst
	  ,sc_cgst
	  ,doc_type
	  ,paidTo
	 
	  
from 
(
select
       p.id
	   ,p.id_attribute_shift
      ,comp.name as 'outlet_Name'
      ,usr.name
	  ,time as Time
	  ,p.mdoc_no
      ,p.id_mst_outlet
      ,p.sub_total_items
      ,p.sgst_total_items
      ,p.cgst_total_items
      ,p.igst_total_items
      ,p.cess_total_items
      ,p.vat_total_items
      ,p.surcharge_total_items
      ,p.round_off_amount
      ,p.net_amount_items
      ,p.grant_total_amount
      ,p.others_charges_net_amount
      ,p.discount_amount_additional
      ,p.total_discount_items
	  ,p.sc_charges_net_amount
	  ,p.sc_sgst
	  ,p.sc_cgst
	  ,(
	  case  when (payment_mode='CASH' and amount>0) then 'CASH' 
			when (payment_mode='CARD' and amount>0 and id_cardtype=1) then 'CARD'
			when (payment_mode='CHEQUE' and amount>0) then 'CHEQUE'
			when (payment_mode='UPI' and amount>0) then 'UPI'
			when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) and amount>0  then 'ONLINETRANSFER'
			when (payment_mode='COMPANY' and amount>0) then 'COMPANY'
			when (payment_mode='BIllONHOLD' and amount>0) then 'BIllONHOLD'
			when (payment_mode='ROOMTO' and amount>0) then 'ROOMTO'
			
			
			 end) as paidTo	  
	  
	  
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI
	  ,case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
	  ,case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD
	   ,case when payment_mode='ROOMTO' then IFNULL(amount,0) else null end as ROOMTO
	  ,att.field_value
      ,pp.doc_date as date_created
	  ,p.pax
	  ,p.id_attribute_table
	  ,p.date_created as bill_date_created
	  ,p.doc_no
	  ,pp.id_charges_master
	  ,pp.remark
	  ,pp.id_company
	  ,p.doc_type
	   ,p.id_fo_bill
	  
    
	from pos_purch_pay pp
	INNER JOIN
	pos_purch p
	on
	p.id = pp.id_purch
		
	
	INNER JOIN
	mst_attributes att
	on
	att.id = p.id_attribute_shift   
	
	INNER JOIN
	mst_outlets as comp
	on
	comp.id = p.id_mst_outlet   
		
		
	INNER JOIN
	mst_users usr
	on
	usr.id=pp.id_mst_user_created_by
   
    
) as settlement_summary
WHERE id!=0 $SqlConnOutLet
GROUP BY id_mst_outlet,id,name,field_value  ORDER BY id_attribute_shift,id_mst_outlet,doc_no asc";


$query = mysqli_query($connNew,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
//$nightAudit=array();
while($Records	   =	mysqli_fetch_object($query)){
	
				
	
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['outlet_Name']= $Records->outlet_Name;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['mdoc_no']= $Records->mdoc_no;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['bill_date_created']= date('d-m-Y',strtotime($Records->bill_date_created));  
				
				
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['sub_total_items'] += $Records->sub_total_items;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Discount']+=  $Records->Discount;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['NetAmount']+=  $Records->sub_total_items-$Records->Discount;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['sgst']+=  $Records->sgst+$Records->sc_sgst;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['cgst']+=  $Records->cgst+$Records->sc_cgst;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['igst']+=  $Records->igst;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['cess']+=  $Records->cess;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['vat']+=  $Records->vat;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['surcharge']+=  $Records->surcharge;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['OtherCharges']+=  $Records->OtherCharges+$Records->sc_charges_net_amount;
				
				
				$Taxes =($Records->sgst+$Records->sc_sgst+$Records->cgst+$Records->sc_cgst+$Records->igst+$Records->cess+$Records->vat+$Records->surcharge+$Records->OtherCharges+$Records->sc_charges_net_amount);
				
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Taxes']+=$Taxes;
				
				
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['round_off_amount']+=  $Records->round_off_amount;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['grant_total_amount']+=  $Records->grant_total_amount;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['UserName']= $Records->UserName;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Time']= $Records->Time;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Cash']+=  $Records->Cash;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Card']+=  $Records->Card;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Company']+=  $Records->Company;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Cheque']+=  $Records->Cheque;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['OnlineTransfer']+=  $Records->OnlineTransfer;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['mode']= $Records->paidTo;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['Billonhold']+=  $Records->Billonhold;
				$nightAudit['NightAudit'][$Records->outlet_Name][$Records->id_mst_outlet]['RoomTo']+=  $Records->RoomTo;
	
	}
	
	debugData($nightAudit);
	
	foreach($nightAudit as $Datalist1){
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'S:No.')			
			->setCellValue('B'.$con, 'Outlet')
							
			->setCellValue('C'.$con, 'Amount')				
			->setCellValue('D'.$con, 'Discount')
			->setCellValue('E'.$con, 'Net Amount')				
			->setCellValue('F'.$con, 'Taxes')
			
			->setCellValue('G'.$con, 'Round')
			->setCellValue('H'.$con, 'Total Amount')
			->setCellValue('I'.$con, 'Cash')
			->setCellValue('J'.$con, 'Card')
			->setCellValue('K'.$con, 'Company')
			->setCellValue('L'.$con, 'Cheque')
			->setCellValue('M'.$con, 'ONLINE')
			->setCellValue('N'.$con, 'Billonhold')
			->setCellValue('O'.$con, 'RoomTo');
												
			cellColor('A'.$con.':O'.$con,'d9edb1');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);
		foreach($Datalist1 as $outlet=>$Datalist2){
			
		
			
			
			
			
			foreach($Datalist2 as $Datalist3){
				
		

				$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, $InCount)
				
				->setCellValue('B'.$con, $Datalist3['outlet_Name'])
				
				->setCellValue('C'.$con, $Datalist3['sub_total_items'])
				->setCellValue('D'.$con, $Datalist3['Discount'])
				->setCellValue('E'.$con, $Datalist3['sub_total_items']-$Datalist3['Discount'])
				->setCellValue('F'.$con, $Datalist3['Taxes'])
				
				->setCellValue('G'.$con, $Datalist3['round_off_amount'])
				->setCellValue('H'.$con, $Datalist3['grant_total_amount'])
				->setCellValue('I'.$con, $Datalist3['Cash'])
				->setCellValue('J'.$con, $Datalist3['Card'])
				->setCellValue('K'.$con, $Datalist3['Company'])
				->setCellValue('L'.$con, $Datalist3['Cheque'])
				->setCellValue('M'.$con, $Datalist3['OnlineTransfer'])
				->setCellValue('N'.$con, $Datalist3['Billonhold'])
				->setCellValue('O'.$con, $Datalist3['RoomTo']);
				
				$sub_total_items	+= $Datalist3['sub_total_items'];
				$Discount	+= $Datalist3['Discount'];
				$sub_total_itemsDiscount	+= $Datalist3['sub_total_items']-$Datalist3['Discount'];
				$sgst	+=$Datalist3['sgst']+$Datalist3['sc_sgst'];
				$cgst	+= $Datalist3['cgst']+$Datalist3['sc_cgst'];
				$igst	+= $Datalist3['igst'];
				$cess	+=$Datalist3['cess'];
				$vat	+= $Datalist3['vat'];
				$surcharge	+= $Datalist3['surcharge'];
				$OtherCharges	+= $Datalist3['OtherCharges']+$Datalist3['sc_charges_net_amount'];
				$round_off_amount	+= $Datalist3['round_off_amount'];
				$grant_total_amount	+= $Datalist3['grant_total_amount'];
				$Cash	+= $Datalist3['Cash'];
				$Card	+= $Datalist3['Card'];
				$Company	+= $Datalist3['Company'];
				$Cheque	+= $Datalist3['Cheque'];
				$OnlineTransfer	+= $Datalist3['OnlineTransfer'];
				$Billonhold	+= $Datalist3['Billonhold'];
				$RoomTo	+= $Datalist3['RoomTo'];	
				$Taxes	+= $Datalist3['Taxes'];
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
		
			}
			//debugData($Datalist2);
		
		}
	}
	
			$con=$con+1;
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, '')
				
				->setCellValue('B'.$con, 'Total ')
				
				->setCellValue('C'.$con, $sub_total_items)
				->setCellValue('D'.$con, $Discount)
				->setCellValue('E'.$con, $sub_total_itemsDiscount)
				->setCellValue('F'.$con, $Taxes)//($sgst+$cgst+$igst+$cess+$vat+$surcharge+$OtherCharges))
				
				->setCellValue('G'.$con, $round_off_amount)
				->setCellValue('H'.$con, $grant_total_amount)
				->setCellValue('I'.$con, $Cash)
				->setCellValue('J'.$con, $Card)
				->setCellValue('K'.$con, $Company)
				->setCellValue('L'.$con, $Cheque)
				->setCellValue('M'.$con, $OnlineTransfer)
				->setCellValue('N'.$con, $Billonhold)
				->setCellValue('O'.$con, $RoomTo);
				
		cellColor('A'.$con.':O'.$con,'d9edb1');		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':O'.$con)->applyFromArray($styleThinBlackBorderOutline);
		$InCount++;
	
	
	//die;
	// Rename worksheet
		 $objPHPExcel->getSecurity()->setLockWindows(true);
         $objPHPExcel->getSecurity()->setLockStructure(true);
         $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");
         $objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');
         $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
         // This should be enabled in order to enable any of the following!
         $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
         $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);	
		 $objPHPExcel->getActiveSheet()->setTitle('Night Audit');	
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToPage(true);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToWidth(1);
		 $objPHPExcel->getActiveSheet()->getPageSetup()->setFitToHeight(0);
		 $objPHPExcel->getDefaultStyle()->getFont()->setSize(12);
		 
		 $objPHPExcel->getActiveSheet()
			->getPageMargins()->setTop(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setRight(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setLeft(0.25);
		 $objPHPExcel->getActiveSheet()
		    ->getPageMargins()->setBottom(0.25);

//Print
	$objPHPExcel->setActiveSheetIndex(0);
	ob_end_clean();





	$filename=	'NightAuditReport'.date('d-M-Y').'.xls';
	// Redirect output to a client’s web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	//exit;
	
	}

	?>