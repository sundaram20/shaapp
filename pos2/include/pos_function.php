<?php
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
//error_reporting(E_ALL);

function BillingOrderItemList($conn,$shop_id,$cron){
	echo $Conten ='==========================================';
	//return $Content;
}

/*** wrap Text For Printer ***/ 
function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
	{
	    $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
	    $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

	    $leftLines = explode("\n", $leftWrapped);
	    $rightLines = explode("\n", $rightWrapped);
	    $allLines = array();
	    for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
	        $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
	        $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
	        $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
	    }
	    return implode($allLines, "\n");
}

	function printBill($billData = array(),$reprint = 0){
	//	debugData($billData);
	//	exit;
		global $image_display_path ;

		foreach ($billData as $index => $details) {

			

			/** Connecting To Printer **/
			$connector = new NetworkPrintConnector('103.103.59.51');
						

			/* Start the printer */
			$printer = new Printer($connector);

			/*$printer->cut();
			$printer->close();
			exit;*/
			
			
			/**** Outlet Information ***/
			$id_outlet = selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$outletName = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');


			$outletAddress = selectColumn(TBL_OUTLETS,'CONCAT(address,", ",city)','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$id_state = selectColumn(TBL_OUTLETS,'id_state','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = selectColumn(TBL_OUTLETS,'id_country','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = selectColumn(TBL_OUTLETS,'pincode','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$outletPan = selectColumn(TBL_OUTLETS,'pan_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletGstin = selectColumn(TBL_OUTLETS,'gst_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletTin = selectColumn(TBL_OUTLETS,'tin_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletHsn = selectColumn(TBL_OUTLETS,'hsn_code','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$biller =selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');

			
			/* Print top logo */
			/*$logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$logo = EscposImage::load('../../uploaded_files/outlets/medium-'.$logoImage, false);
			$printer -> setJustification(Printer::JUSTIFY_CENTER);
			$printer -> graphics($logo);

			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text($outletName);
			$printer -> selectPrintMode();
			$printer->feed();
			$printer->feed();

			$printer->text($outletAddress);
			$printer->feed();
			$printer->text($outletState.'-'.$outletPincode.', '.$outletCountry);
			
			$printer->feed();
			$printer->text("-----------------------------------------------\n");
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer->text(str_pad('GST No : '.$outletGstin,25," "));
			$printer->text('PAN No : '.$outletPan);
			$printer->feed();
			$printer->text(str_pad('TIN No : '.$outletTin,25," "));
			$printer->text('HSN Code : '.$outletHsn);
			$printer->feed();

			
			$printer->text("-----------------------------------------------\n");
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer->text(str_pad('Bill No : '.$index,25," "));
			$printer->text('Kot No : 6');
			$printer->feed();
			$printer->text(str_pad('Bill Date : '.date('d-M-Y'),25," "));
			$printer->text('Table No : 2');
			$printer->feed();
			$printer->text("-----------------------------------------------\n");

			
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer->text(str_pad('Steward : Hitesh',25," "));
			$printer->text('Covers : 8');
			$printer->feed();
			$printer->text('Party Name : Addiyar Infotech Pvt Ltd.');
			$printer->feed();
			$printer->text('Party GST No : AXH 125482');
			$printer->feed();
			$printer->text("-----------------------------------------------\n");*/

			
			$printer -> setJustification(Printer::JUSTIFY_LEFT);
			$printer->text(str_pad('S No.',8," "));
			$printer->text(str_pad('Description',18," "));
			$printer->text(str_pad('Qty',8," "));
			$printer->text(str_pad('Rate',7," "));
			$printer->text('Amount');
			$printer->feed();
			$printer->text("-----------------------------------------------\n");
			$sno=1;

			foreach ($details['item_id'] as $id_index => $id_item) {
				$printer -> setJustification(Printer::JUSTIFY_LEFT);
				//$printer->text(str_pad($sno++,8," "));
				$printer->text(columnify($sno++,trim($details['item_description'][$id_index]),2,18,6));
				$printer->text(str_pad($details['item_qty'][$id_index],8," "));
				$printer->text(str_pad($details['item_rate'][$id_index],7," "));
				$printer->text($details['item_amount'][$id_index]."\n");
				
			}

			$printer->text("-----------------------------------------------\n");
			$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text(str_pad("Sub Total",13," "));
			$printer->text(str_pad(array_sum($details['item_qty']),6," "));
			$printer->text(trim($details['net_amount_items']));
			$printer -> selectPrintMode();
			$printer->feed();
			$printer->text("-----------------------------------------------\n");
		
			$printer -> setJustification(Printer::JUSTIFY_RIGHT);
			$printer->text('Discount : '.array_sum($details['discount_amount_additional']));
			$printer->feed();
			$printer->text("SGST 2.5 % : ");
			$printer->text($details['sgst_net_amount']);
			$printer->feed();
			$printer->text("CGST 2.5 % : ");
			$printer->text($details['sgst_net_amount']);
			$printer->feed();
			$printer->text("Total: ".round($details['net_amount'],2));
			$printer->text($details['sgst_net_amount']);
			$printer->feed();
			$printer->text("Round Off : ");
			$printer->text(round((round($details['net_amount'],0)-$details['net_amount']),2));
			$printer->feed();

			$printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer->text("Grand Total : ");
			$printer->text(str_pad(round($details['net_amount'],0),5," "));
			$printer -> selectPrintMode();
			$printer->feed();
			$printer->text("-----------------------------------------------\n");
			/*** Outlet Information End ***/
			$printer->cut();
			$printer->close();
		}
		
		
		exit;
	}

	?>