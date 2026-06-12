<?php


		
function kotVsBillReportReport($Date,$id_outlet,$id_shift,$objPHPExcel){
	
	global $connNew;
	global $objPHPExcel;
	
	
		
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilterreport']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
		$SqlConn .= " AND pp.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
	}
	if($id_outlet != ''){
		$SqlConn .= " AND pp.`id_mst_outlet` IN (".$id_outlet.")";
	}
	if($id_shift != ''){
		$SqlConn .= " AND pp.`id_attribute_shift` IN (".$id_shift.")";
	}


	$resShop  =  mysqli_query($connNew,"SELECT * FROM `".TBL_SHOP."` WHERE id= '".$_SESSION['shop']."'");
	$rowShop = mysqli_fetch_object($resShop);
	$logo	=	$rowShop->image;
	
//echo '======================================================1';
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
				->setCellValue('A7', "Settlement Summary");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:L7');



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A7:K7')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
$con=$setcellcount;


	//echo 'From Date'.date('d-m-Y',strtotime($startDate)).' To '.date('d-m-Y',strtotime($endDate)).$startDate;die;
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$con,'From Date'.date('d-m-Y',strtotime($startDate)).' To '.date('d-m-Y',strtotime($endDate)));

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':K'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':K'.$con)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

$con++;


	
$SQL ="SELECT pp.id as purch_id,pp.id_attribute_steward,pp.id_attribute_table,pp.id_mst_user_created_by,pp.pax,pp.mdoc_no,ppd.item_description,
ppd.qty,pp.date_created,pp.kot_doc_no,pp.cancelled,pp.cancel_remark,pp.doc_type 
FROM `pos_purch` as pp 
left join pos_purch_details as ppd ON ppd.id_pos_purch=pp.id 
where pp.pos_bill_type=1 and pp.doc_type!=24 and pp.id!=0 $SqlConn order by pp.doc_no asc


";
//echo '==================='.$SQL;die;//FIND_IN_SET('".$id_teams."',ids_team)
//die;
//SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id IN(".$id_kot.") AND doc_type='22'
$query = mysqli_query($connNew,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'KOT No.')
			//->setCellValue('B'.$con, 'Bill No')			
			->setCellValue('B'.$con, 'Date')
			->setCellValue('C'.$con, 'Cancelled')
			->setCellValue('D'.$con, 'stward')
			->setCellValue('E'.$con, 'Table/Room')				
			->setCellValue('F'.$con, 'Pax')				
			->setCellValue('G'.$con, 'item')
			->setCellValue('H'.$con, 'Qty')
			->setCellValue('I'.$con, 'User ID')
			->setCellValue('J'.$con, 'Time')			
			->setCellValue('K'.$con, 'REMARK');									

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$con++;
while($Records	   =	mysqli_fetch_object($query)){
	$shiftSummary	=	$Records->id_attribute_shift;
	
	
			
	    //SUMMARY DETAILS====================================================
		if($count2>1){}
		//SUMMARY DETAILS====================================================
	
	
		/*cellColor('A'.$con.':Z'.$con,'ecf0f5');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':Z'.$con);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(16)
                                ->getColor()->setRGB('ed154b');
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, strtoupper($Records->FeildValue));*/
				
					
		$shift=$Records->id_attribute_shift;
		$shiftSummary=$Records->id_attribute_shift;
		$mstoutlet=$Records->id_mst_outlet;
		$checkshift =$shiftSummary;
		$sub_total_Cash ='';
		$sub_total_Card ='';
		$sub_total_Company='';
		$sub_total_Cheque ='';
		$sub_total_OnlineTransfer ='';
		$grant_total_amount ='';
	
	
		

	
	$SqlAttrbuteTable =  mysqli_query($connNew,"SELECT * FROM `".TBL_ATTRIBUTES."` where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$Records->id_attribute_table."'");
	
    $resultAttrbuteTable = mysqli_fetch_object($SqlAttrbuteTable);
	if($Records->id_company>0 && $Records->Company>0){
	 $company	=	selectColumn(MST_COMPANY,'name','WHERE id="'.$Records->id_company.'" AND id_shop="'.$_SESSION['shop'].'" ');
	}else{ $company='';}
	 if($Records->id_charges_master>0){
	$bank	=	  selectColumn(TBL_CHARGES,'name','WHERE id="'.$Records->id_charges_master.'" AND id_shop="'.$_SESSION['shop'].'" ');
	 }else{ $bank='';
		 }
	 $remarks=$bank.' '.$company.' '.($Records->remark!=''?'('.$Records->remark.')':'');	
	$id_mst_user_created_by	=	  selectColumn('mst_users','name','WHERE id="'.$Records->id_mst_user_created_by.'" AND id_shop="'.$_SESSION['shop'].'" ');
	$steward	=	  selectColumn('mst_attributes','field_value','WHERE id="'.$Records->id_attribute_steward.'"  ');
	$table	=	  selectColumn('mst_attributes','field_value','WHERE id="'.$Records->id_attribute_table.'"  ');
	//$mdoc_noBill	=	  selectColumn('pos_purch','mdoc_no','WHERE  id_shop="'.$_SESSION['shop'].'" AND pos_bill_type= "2" AND FIND_IN_SET("'.$Records->purch_id.'",kot_doc_no)');
	
	
	
	if($Records->cancelled==1){
		$cancelledText='Yes';
	}else{
		$cancelledText='No';
		}
	//test
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, $Records->mdoc_no)
				//->setCellValue('B'.$con, $mdoc_noBill)
				
				->setCellValue('B'.$con, date('d-m-Y',strtotime($Records->date_created))) 
				->setCellValue('C'.$con, $cancelledText) //->setCellValue('D'.$con, date('d-m-Y',strtotime($Records->CreatedDate)))
				->setCellValue('D'.$con, $steward)
				->setCellValue('E'.$con, $table)
				->setCellValue('F'.$con, $Records->pax)
				->setCellValue('G'.$con, $Records->item_description)
				->setCellValue('H'.$con, $Records->qty)
				->setCellValue('I'.$con, $id_mst_user_created_by)
				->setCellValue('J'.$con, date('H:i:s',strtotime($Records->date_created)))
				->setCellValue('K'.$con, $Records->cancel_remark);
				
				
				//->setCellValue('Z'.$con, $Records->FeildValue)
				//->setCellValue('AA'.$con, $Records->CreatedDate)
				//->setCellValue('AA'.$con, $Records->CreatedDate);
		//$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':K'.$con)->applyFromArray($styleThinBlackBorderOutline);
		
		
		
		
		
		
		
			
		
		//SUMMARY DETAILS====================================================
		$count2++;
		$con++;
		$InCount++;
		$TotalBillCount++;
	}
	// Rename worksheet
		 $objPHPExcel->getSecurity()->setLockWindows(true);
         $objPHPExcel->getSecurity()->setLockStructure(true);
         $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");
         $objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');
         $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
         // This should be enabled in order to enable any of the following!
         $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
         $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);	
		 $objPHPExcel->getActiveSheet()->setTitle('Settlement Summary');	
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





	$filename=	'KotWiseItemReport'.date('d-M-Y').'.xls';
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