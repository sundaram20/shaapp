<?php include_once("../config/auto_loader.php");


checkUserLevelPermission($_SESSION['userLevel'],TBL_ORDERS,'view');

	
if($_POST['Download'] == 'Download'){
	error_reporting(1);
	$resShop  =  executeSQl("SELECT * FROM `".TBL_SHOP."` WHERE id= '".addslashes($_SESSION['shop'])."'");
	$rowShop = $db->fetch_object2($resShop);


	$db->query($sql);
	$numRows= $db->num_rows();
	//$pagging = new pagingClass($sql,$setpage);
	//$db->query($pagging->getQuery());
	$total = $db->num_rows();
	

	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
								 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");

	$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../uploaded_files/shop/'.$rowShop->image);
	$objDrawing->setCoordinates('A1');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

	// Add some data
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A8', 'CITY')
				->setCellValue('B8', 'HOTEL/RESORT')
				->setCellValue('C8', 'Rooms')
				->setCellValue('D8', 'ROOM  CATEGORY')
				->setCellValue('E8', 'Plan')
				->setCellValue('F8', 'YOUR SPECIAL RATES')
				->setCellValue('G8', 'INCLUSIONS')
				->setCellValue('H8', 'APPLICABLE TAXES');
				
				if(isset($_POST['booking_date_chk']) && $_POST['booking_date_chk']==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('J8', 'Booking Date');
				}
				if(isset($_POST['payment_date']) && $_POST['payment_date']==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('K8', 'Payment Date');
				}				
				if(isset($_POST['total_price']) && $_POST['total_price']==1){
				$objPHPExcel->setActiveSheetIndex(0)->setCellValue('L8', 'Total Price');
				};
				
				
				
	$objPHPExcel->getActiveSheet()->getStyle('A8:J8')->getFont()->setBold(true);
	$company_array=array();
	if($total > 0){$counter = 9;
					  while($row = $db->fetch_object()){
						  if(!in_array($row->id_company, $company_array, true)){
								array_push($company_array, $row->id_company);
							}
					$objPHPExcel->setActiveSheetIndex(0)
									->setCellValue('A' . $counter, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$row->id_company."'"))
									->setCellValue('B' . $counter, $row->reference)
									->setCellValue('C' . $counter, selectColumn(TBL_HTL_BOOKING_STATUS,'code'," WHERE `id` = '".$row->booking_status."'"))
									->setCellValue('D' . $counter, dateformat_date($row->checkin))
									->setCellValue('E' . $counter, dateformat_date($row->checkout))
									->setCellValue('F' . $counter, round($row->total_products))
									->setCellValue('G' . $counter, $row->no_of_days)
									->setCellValue('H' . $counter, (round($row->total_products)*$row->no_of_days))
									->setCellValue('I' . $counter, selectColumn(TBL_USERS,'name'," WHERE `id` = '".$row->id_executive."'"))
									->setCellValue('J' . $counter, dateformat_date($row->invoice_date)); 
						$counter++; 
					  }
		} 
		

	// Rename worksheet
	$objPHPExcel->getActiveSheet()->setTitle('Booking Detail Report');


	//Second sheet Agent wise
	$objPHPExcel->createSheet();

	$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue('B1', 'Period')
				->setCellValue('D1', 'To!');
				
				
				//"select * from "
	$setcell=2;
	
	foreach($company_array as $comp){
		
		$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue('A'.$setcell, 'Agent Name')
				->setCellValue('B' . $setcell, selectColumn(TBL_COMPANY,'name'," WHERE `id_company` = '".$comp."'"));
	}
	
							
	$objPHPExcel->getActiveSheet()->getStyle('B1:D1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('A1:A'.$setcell)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet(1)->setTitle('Agent wise Report');



	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);

	ob_end_clean();
	// Redirect output to a client's web browser (Excel2007)
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="booking_report.xlsx"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');

	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
 
}

if($_POST['Search'] == 'Search'){
	

	
	$db->query($sql);
	$numRows= $db->num_rows();
	$pagging = new pagingClass($sql,$setpage);
	$db->query($pagging->getQuery());
	$total = $db->num_rows();

}
?>
<?php include_once("includes/header.php")?>
<?php include_once("includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      
      
    </section>
    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 
        <div class="box-header with-border">
          <h3 class="box-title">Download Excel Reports &nbsp;</small> </h3>
		  
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        
        <!-- /.box-body -->
        <div class="box-footer">

       <input name="Download" type="submit" class="btn btn-primary" value="Download" />
        </div>
		</form>		
      </div>
      <div class="row">
        
        <!-- /.col -->
      </div>
      <!-- /.row -->
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>                                   
<?php include_once("includes/footer.php")?>  