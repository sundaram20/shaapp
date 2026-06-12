<?php 
if(!isset($cron)){
include_once("../config/auto_loader.php");
//error_reporting(E_ALL);
checkUserLevelPermission($_SESSION['userLevel'],TBL_DAILYVISIT,'view');

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);


} 
 
?>

<?php 

	//error_reporting(1);
function stock_balance_report($clicked_id){ 

	 echo $clicked_id;

		// $dompdf = new DOMPDF();
		// //$dompdf->set_option("isPhpEnabled", true);
		// $dompdf->set_paper('landscape', 'landscape');
		// $dompdf->load_html($content);
		// $dompdf->render();
		// $font = Font_Metrics::get_font("helvetica", "bold");
		// $dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
		// if(!isset($cron)){
		// 	$dompdf->output();
		// //$dompdf->stream();
		// $dompdf->stream('ConveyanceReport'.'_'.selectColumn(TBL_USERS,'name','WHERE id="'.$idUser.'" ').'_'.date('d-M-Y H:i:s').'.pdf', array("Attachment" => true));	
		// exit;
			
		// }
		// else{
		// 	if($numRows>0){
		// 	$Filename = 'ConveyanceReport'.'_'.selectColumn(TBL_USERS,'name','WHERE id="'.$idUser.'" ');
			
		// 	$gen = $dompdf->output();
		// 	//$dompdf->stream($Filename.'.pdf', array("Attachment" => true));

			
		// 	//local
		// 	//$objWriter->save('../mailattach/'.$fileName.'.xls');
		// 	file_put_contents('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$Filename.'.pdf', $gen);
			
		// 	//cron server
		// 	//$objWriter->save('/home/admingcs/public_html/sales/adminpanel/mailattach/'.$fileName.'.xls');
		// 	//echo "ok";
		// 	}
		// }
		
		
	} 
?>