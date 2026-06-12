<?php include_once("../config/auto_loader.php");

	/*

$asheet = $objPHPExcel->getActiveSheet();

    $table = '<table></table>'; //put your table

    $tmpfile = tempnam(sys_get_temp_dir(), 'html');
    file_put_contents($tmpfile, $table);
    $excelHTMLReader = PHPExcel_IOFactory::createReader('HTML');
    $excelHTMLReader->loadIntoExisting($tmpfile, $objPHPExcel);
    unlink($tmpfile);

    // sheet 2
    $objPHPExcel2 = new PHPExcel();
    $objPHPExcel2->setActiveSheetIndex(0);
    $asheet2 = $objPHPExcel2->getActiveSheet();

    $table2 = '<table></table>'; //put your another table

    $tmpfile2 = tempnam(sys_get_temp_dir(), 'html');
    file_put_contents($tmpfile2, $table2);
    $excelHTMLReader2 = PHPExcel_IOFactory::createReader('HTML');
    $excelHTMLReader2->loadIntoExisting($tmpfile2, $objPHPExcel2);
    unlink($tmpfile2);

    //$objPHPExcel->addSheet($asheet2, 0);
    $objPHPExcel->addExternalSheet($asheet2, 0); //copy sheet2 in first objPHPExcel

    header("Content-Type:application/vnd.ms-excel");
    header("Content-Disposition:attachment;filename=simple.xls");

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
	
	*/
	
	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Booking Report")
	  						 	 ->setSubject("Booking Report")
								 ->setDescription("Booking Report")
								 ->setKeywords("Booking Report")
								 ->setCategory("Report");
								 
								 $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'username')
				  ->setCellValue('C1', 'username')
				    ->setCellValue('D1', 'username')  ->setCellValue('E1', 'username')
                ->setCellValue('F1', 'city_name');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	ob_end_clean();
	// Redirect output to a client’s web browser (Excel2007)
	//header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="booking_Summary.xls"');
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
	exit;
 
?>

<?php //include_once("../includes/footer.php")?>  