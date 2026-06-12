<?php

function customPrintDefaultPreview($printgroup){	
	global $connNew;
	if(is_array($printgroup)){

		foreach ($printgroup as $index => $details) {
		//debugData($printgroup);
		//print_r($printgroup);
		
		 
		$details['id_purch'];
		
			/**** Outlet Information ***/
			$CompanyID = selectColumn(TBL_PURCH_PAY,'id_company','WHERE id_type =4 AND  id_purch="'.$details['id_purch'].'" and id_company>0 ');
			if($CompanyID>0){
				
			$CompanyName = selectColumn(MST_COMPANY,'name','WHERE id="'.$CompanyID.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$GSTno = selectColumn(MST_COMPANY,'fax','WHERE id="'.$CompanyID.'" AND id_shop="'.$_SESSION['shop'].'" ');
			
				}
			$id_outlet = $details['id_mst_outlet'];//selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$outletName = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

$city = selectColumn(TBL_OUTLETS,'city','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletAddress = selectColumn(TBL_OUTLETS,'address','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$id_state = selectColumn(TBL_OUTLETS,'id_mst_state','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = selectColumn(TBL_OUTLETS,'id_mst_country_lang','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = selectColumn(TBL_OUTLETS,'pincode','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletMobile = selectColumn(TBL_OUTLETS,'mobile','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
            $outletEmail = selectColumn(TBL_OUTLETS,'email','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$outletPan = selectColumn(TBL_OUTLETS,'pan_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletGstin = selectColumn(TBL_OUTLETS,'gst_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletTin = selectColumn(TBL_OUTLETS,'tin_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletHsn = selectColumn(TBL_OUTLETS,'hsn_code','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$biller =selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');
			
			  $OutletSql="SELECT * FROM ".TBL_OUTLETS."   WHERE id='".$id_outlet."' AND id_shop='".$_SESSION['shop']."' ";
	$resultPosOutlet = mysqli_query($connNew, $OutletSql); 
	
	$posOuletResult = mysqli_fetch_object($resultPosOutlet);
	//debugData($posOuletResult);
	//tes
			$id_attribute_steward =selectColumn(TBL_ATTRIBUTES,'field_value','WHERE id_shop="'.$_SESSION['shop'].'"  and status = 1 AND table_name ="steward"  AND id="'.$printgroup[0]['id_attribute_steward'].'" ');

			$printer.=("<br>");
			$printer .='<style>#wordwarkwirh{ word-warp:break-word; width:200px;}</style>';
			/* Print top logo */
			$logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');
			//$logo = EscposImage::load('../../uploaded_files/outlets/medium-'.$logoImage, false);
			//$printer .='<div><img src='.$SITE_URL.'/uploaded_files/outlets/small-'.$logoImage.'  alt="" style="width:80px;"><span style="margin-left:10px;font-weight:bold">'.$posOuletResult->name.' </span><span style="margin-left:10px;font-weight:bold">'.$posOuletResult->city.'</span></div>';
			$printer.=("<br>");
			$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[0]['doc_date'])).'Time:'. date('h:i A',strtotime($printgroup[0]['date_created'])),24," "));
			$printer.=("<br>");
			$printer.=("<br>");

		//	$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:12px;margin-right:15px;">Time:'. date('h:i A',strtotime($printgroup[0]['date_created'])).'</span></div>';
			if($printgroup[0]['cancelled']==1){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:13px;font-weight:bold">CANCELLED</span></div>';
			}
			$printer .='<div style="text-align:center;"><span style="text-align:center;font-size:18px;font-weight:bold">'.$posOuletResult->name.' </span><br></div>';
			//<span style="margin-left:10px;font-weight:bold">'.$posOuletResult->city.'</span>
			$printer.=("<br>");
			$printer .='<div style="text-align:center;"><span style="font-size:13px;font-weight:bold">'.$posOuletResult->description.'</span></div>';
			
			//$printer.=(columnify('',trim($posOuletResult->address),2,33," "));
			$printer.=('<div style="text-align:center;">'.$posOuletResult->address.'</div>');
			if($posOuletResult->mobile!=''){	
				$printer.=('<div style="text-align:center;"><span>Contact:'.$posOuletResult->mobile.'</span></div>');
			}
			if($posOuletResult->email!=''){	
				$printer.=('<div style="text-align:center;"><span> Email:'.$posOuletResult->email.'</span></div>');
					}
			if($posOuletResult->cin_no!=''){			
			$printer.=('<div style="text-align:center;">CIN : '.$posOuletResult->cin_no.'</div>');			
			}
			if($posOuletResult->fssai_no!=''){
			$printer.=('<div style="text-align:center;">FSSAI No : '.$posOuletResult->fssai_no.'</div>');
			
			
			}
			
			
if($posOuletResult->registered_office_address!=''){
	//$printer.=("<br>");
	$printer.=('<div style="text-align:center;font=size:10px;font-weight:bold;">Reg off </div>');
			$printer.=('<div style="text-align:center;">'.$posOuletResult->registered_office_address.'</div>');
			
			
			}			
			
			//$printer.=((trim($outletAddress)));
			
			
			
			
			$printer.=("----------------------------------------\n");
			
			$printer.=(str_pad('GST No : '.$outletGstin,25," "));
			$printer.=("<br>");			
			$printer.=('PAN No:'.$outletPan);
			
			//$printer.=(str_pad('TIN No : '.$outletTin,25," "));
			$printer.=(' HSN Code:'.$outletHsn);
			$printer.=("<br>");
			
			if($outletTin!=''){
			$printer.=(str_pad('TIN No : '.$outletTin,25," "));
			$printer.=("<br>");
			}
			$printer.=("----------------------------------------\n");
			
			//$printer.=(str_pad('Bill No : '.$index,25," "));
			$printer.=(str_pad('Bill No : '.$printgroup[0]['mdoc_no'],25," "));
			$printer.=("<br>");
			$printer.=('Kot No : '.wordwrap($printgroup[0]['kot_doc_no'],30,'<br>',true));
			$printer.=("<br>");
			//$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[0]['doc_date'])).'Time:'. date('h:i A',strtotime($printgroup[0]['date_created'])),24," "));
			$printer.=('Table No: '.$printgroup[0]['attribute_table_name']);
			$printer.=("<br>");
			//$printer.=("-------------------------------------\n");

			
			
			$printer.=(str_pad('Steward :'.$id_attribute_steward,25," "));
			$printer.=('Covers : '.$printgroup[0]['pax']);
			$printer.=("<br>");
			if($CompanyName!=''){
			$printer.=('Party Name: '.$CompanyName);
			$printer.=("<br>");
			}
			if($GSTno!=''){
			$printer.=('Party GST No:'.$GSTno);
			$printer.=("<br>");
			}
			$printer.=("----------------------------------------<br>");

			
			$printer.=(str_pad('SNo.',5," "));
			$printer.=(str_pad('Description',19," "));
			$printer.=(str_pad('Qty',4," "));
			$printer.=(str_pad('Rate',5," "));
			$printer.=str_pad('Amount',8," ");
			$printer.=("<br>");
			$printer.=("----------------------------------------<br>");
			$sno=1;
			
			
			/*foreach ($details['item_id'] as $id_index => $id_item) {
				
				$printer.=(columnify($sno++,trim($details['item_description'][$id_index]),2,15,4));
				$printer.=(str_pad($details['item_qty'][$id_index],8," "));
				$printer.=(str_pad($details['item_rate'][$id_index],9," "));
				$printer.=(number_format(str_pad($details['item_amount'][$id_index],30," "),2));
				$printer.=("<br>");
				
			}*/
			
			foreach ($details['item_id'] as $id_index => $id_item) {
				
				$printer.=(columnify($sno++,trim($details['item_description'][$id_index]),2,21,2));
				$printer.=(str_pad(round($details['item_qty'][$id_index]),2," "));
				$printer.=(str_pad(round($details['item_rate'][$id_index],2),7," "));
				//$printer.="<div >";
				$printer.=(round(str_pad($details['item_amount'][$id_index],8," ")));
				//$printer.=("</div>");
				$printer.=("<br>");
				
			}

			$printer.=("----------------------------------------<br>");
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer.=(str_pad("",7," "));
			$printer.=(str_pad(" Total",16," "));
			$printer.=(str_pad(array_sum($details['item_qty']),6," "));
			$printer.=(trim($printgroup[0]['sub_total_items']));
			//$printer -> selectPrintMode();
			//$printer->feed();
			$printer.=("<br>----------------------------------------<br>");
			$printer.="<div style='float:left;margin-left:95px'>";
			if($details['total_item_discount_amount']>0){
			$printer.=($printgroup[0]['discount_display_alias_name'].' : '.$printgroup[0]['total_item_discount_amount']);
			$printer.=("<br>");
			}
			if($details['discount_amount_additional']>0){
			$printer.=('Discount : '.$details['discount_amount_additional']);
			$printer.=("<br>");
			}
			
			$printer.=('Sub Total : '.(($printgroup[0]['sub_total_items'])-($details['discount_amount_additional']+$printgroup[0]['total_item_discount_amount'])));
			$printer.=("<br>");
			
					
			if($printgroup[0]['sc_reverse']>0){
			$printer.=("Service Charges 10% :");
			$printer.=($printgroup[0]['sc_charges_net_amount']);
			$printer.=("<br>");
			}
			
			$printer.= calculateTaxprint($printgroup);
			
			
			$printer.="<div style='float:left; width:135px;text-align:right;'>";
			//$printer.=("<div style='float:left;'>Total:</div> <div style='float:right;'> ".round($details['net_amount'],2).'</div>');
			$printer.=("<div style='float:left;'>Round Off:</div> <div style='float:right; '> ".round((round($details['net_amount'],0)-$details['net_amount']),2).'</div>');
			$printer.=("<div style='float:left;font-weight:bold;font-size:13px;'>Grand Total:</div> <div style='float:right;font-weight:bold;font-size:13px;'>".str_pad(round($details['net_amount'],0),0," ").'</div>');
			$printer.="</div>";
			$printer.="</div>";
			
			if($printgroup[0]['footer_remarks']!=''){
		       $printer.=(" <div style='  margin-top: 20px;float: right;width:265px;white-space:break-spaces;text-align: center;'>".($printgroup[0]['footer_remarks']).'</div>');
		    }
			
			$printer.=("<br>");
			$printer.=("<br>");
			$printer.=("<br>");
			$printer.=("<br>");$printer.=("<br>");
			$printer.=("<br>");
			$printer.=("<br>");
			/*$printer.=("Round Off : ");
			$printer.=(round((round($details['net_amount'],0)-$details['net_amount']),2));
			$printer.=("<br>");

			
			$printer.=("Grand Total : ");
			$printer.=(str_pad(round($details['net_amount'],0),5," "));
			$printer.=("</div> <br>");*/
			
			/*** Outlet Information End ***/
			
		}

	}
		
	return $printer;
}









	function customPrintSalesAccountPreview($printgroup){
	
	
	//debugData($printgroup);
		
	global $connNew;
	if(is_array($printgroup)){
$GrandTotalCount	=	count($printgroup);

$RecordCount=1;
		foreach ($printgroup as $index => $details) {
			
			
		//debugData($printgroup);
		//print_r($printgroup);
		
		
		$details['id_purch'];
		
			/**** Outlet Information ***/
			$CompanyID = selectColumn(TBL_PURCH_PAY,'id_company','WHERE id_type =4 AND  id_purch="'.$details['id_purch'].'" and id_company>0 ');
			if($CompanyID>0){
				
			$CompanyName = selectColumn(MST_COMPANY,'name','WHERE id="'.$CompanyID.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$GSTno = selectColumn(MST_COMPANY,'fax','WHERE id="'.$CompanyID.'" AND id_shop="'.$_SESSION['shop'].'" ');
			
				}
			$id_outlet = $details['id_mst_outlet'];//selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$outletName = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

$city = selectColumn(TBL_OUTLETS,'city','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletAddress = selectColumn(TBL_OUTLETS,'address','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$id_state = selectColumn(TBL_OUTLETS,'id_mst_state','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = selectColumn(TBL_OUTLETS,'id_mst_country_lang','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = selectColumn(TBL_OUTLETS,'pincode','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletMobile = selectColumn(TBL_OUTLETS,'mobile','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
            $outletEmail = selectColumn(TBL_OUTLETS,'email','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$outletPan = selectColumn(TBL_OUTLETS,'pan_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletGstin = selectColumn(TBL_OUTLETS,'gst_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletTin = selectColumn(TBL_OUTLETS,'tin_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletHsn = selectColumn(TBL_OUTLETS,'hsn_code','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$biller =selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');
			
			  $OutletSql="SELECT * FROM ".TBL_OUTLETS."   WHERE id='".$id_outlet."' AND id_shop='".$_SESSION['shop']."' ";
	$resultPosOutlet = mysqli_query($connNew, $OutletSql); 
	
	$posOuletResult = mysqli_fetch_object($resultPosOutlet);
	//debugData($posOuletResult);
	//tes
			$id_attribute_steward =selectColumn(TBL_ATTRIBUTES,'field_value','WHERE id_shop="'.$_SESSION['shop'].'"  and status = 1 AND table_name ="steward"  AND id="'.$printgroup[$index]['id_attribute_steward'].'" ');

			$printer.=("<br>");
			$printer .='<style>#wordwarkwirh{ word-warp:break-word; width:200px;}</style>';
			/* Print top logo */
			$logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');
			//$logo = EscposImage::load('../../uploaded_files/outlets/medium-'.$logoImage, false);
			//$printer .='<div><img src='.$SITE_URL.'/uploaded_files/outlets/small-'.$logoImage.'  alt="" style="width:80px;"><span style="margin-left:10px;font-weight:bold">'.$posOuletResult->name.' </span><span style="margin-left:10px;font-weight:bold">'.$posOuletResult->city.'</span></div>';
			//$printer.=("<br>");
			/*if($RecordCount=='1'){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:12px;margin-right:15px;">Time:'. date('h:i A',strtotime($printgroup[$index]['date_created'])).'</span></div>';
			}*/
			
			//$printer.=("<br>");
			//$printer.=("<br>");

			if($printgroup[$index]['cancelled']==1){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:13px;font-weight:bold">CANCELLED</span></div>';
			}
			$printer .='<div style="text-align:center;"><span style="text-align:center;font-size:18px;font-weight:bold">'.$posOuletResult->name.' </span></div>';
			//<span style="margin-left:10px;font-weight:bold">'.$posOuletResult->city.'</span>
			//$printer.=("<br>");
			$printer .='<div style="text-align:center;"><span style="font-size:13px;font-weight:bold">'.$posOuletResult->description.'</span></div>';
			
			//$printer.=(columnify('',trim($posOuletResult->address),2,33," "));
			$printer.=('<div style="text-align:center;">'.$posOuletResult->address.'</div>');
			if($posOuletResult->mobile!=''){	
				$printer.=('<div style="text-align:center;"><span>Contact:'.$posOuletResult->mobile.'</span></div>');
			}
			if($posOuletResult->email!=''){	
				$printer.=('<div style="text-align:center;"><span> Email:'.$posOuletResult->email.'</span></div>');
					}
			if($posOuletResult->cin_no!=''){			
			$printer.=('<div style="text-align:center;">CIN : '.$posOuletResult->cin_no.'</div>');			
			}
			if($posOuletResult->fssai_no!=''){
			$printer.=('<div style="text-align:center;">FSSAI No : '.$posOuletResult->fssai_no.'</div>');
			
			
			}
			
			
if($posOuletResult->registered_office_address!='' && $RecordCount=='1'){
	//$printer.=("<br>");
			$printer.=('<div style="text-align:center;font=size:10px;font-weight:bold;">Reg off </div>');
			$printer.=('<div style="text-align:center;">'.$posOuletResult->registered_office_address.'</div>');
			
			
			}			
			
			//$printer.=((trim($outletAddress)));
			
			
			
			
			$printer.=("----------------------------------------\n");
			if($index!='74'){
			$printer.=(str_pad('GST No : '.$outletGstin,25," "));
			$printer.=("<br>");
				if($outletPan!=''){

				$printer.=('PAN No:'.$outletPan);
				
				}
			//$printer.=(str_pad('TIN No : '.$outletTin,25," "));
				if($outletHsn!=''){
			     	$printer.=(' HSN Code:'.$outletHsn);
			     	$printer.=("<br>");	
		      	}
			
		}else{
			$printer.=(str_pad('GST No : 29ADQPT6726K1ZK',25," "));
			$printer.=("<br>");
			$printer.=(str_pad('Ashutosh Tripathy',25," "));
			$printer.=("<br>");
			}
			
			
			if($outletTin!=''){
			$printer.=(str_pad('TIN No : '.$outletTin,25," "));
			$printer.=("<br>");
			}
			$printer.=("----------------------------------------\n");
			$printer.=('<span style="float:left;">'.str_pad('<b>Bill Date: '.date('d-m-y',strtotime($printgroup[$index]['doc_date'])),25," ").'</span>'); 
			$printer.=('<span style="float:right;">'.str_pad('Time: '.date('h:i A',strtotime($printgroup[$index]['date_created'])).'</b>',-24," ").'</span>');
			//$printer.=(str_pad('Bill No : '.$index,25," "));
			$printer.=('<span style="float:left;">'.str_pad('<b>Bill No : '.$printgroup[$index]['mdoc_no'].'</b>',25," ").'</span>');
			//$printer.=("<br>");
			$printer.=('<span style="float:right;">'.str_pad('<b>Table No: '.$printgroup[$index]['attribute_table_name'].'</b>',-25," ").'</span>');
			$printer.=("<br>");
			$printer.=('<span style="float:left;">'.str_pad('Steward : '.$id_attribute_steward,25," ").'</span>');
			$printer.=('<span style="float:right;">'.str_pad('Covers : '.$printgroup[$index]['pax'],-20," ").'</span>');
			$printer.=("<br>");
		    $printer.=("<br>");

			$printer.=('Kot No : '.wordwrap($printgroup[$index]['kot_doc_no'],30,'<br>',true));
			$printer.=("<br>");
			//$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[$index]['doc_date'])).,24," "));
			//$printer.=("<br>");
		
			//$printer.=("-------------------------------------\n");
		
			if($CompanyName!=''){
			$printer.=('Party Name: '.$CompanyName);
			$printer.=("<br>");
			}
			if($GSTno!=''  ){
			$printer.=('Party GST No:'.$GSTno);
			$printer.=("<br>");
			}
			$printer.=("----------------------------------------<br>");

			
			$printer.=(str_pad('SNo.',5," "));
			$printer.=(str_pad('Description',19," "));
			$printer.=(str_pad('Qty',4," "));
			$printer.=(str_pad('Rate',5," "));
			$printer.=str_pad('Amount',8," ");
			$printer.=("<br>");
			$printer.=("----------------------------------------<br>");
			$sno=1;
			
			
			/*foreach ($details['item_id'] as $id_index => $id_item) {
				
				$printer.=(columnify($sno++,trim($details['item_description'][$id_index]),2,15,4));
				$printer.=(str_pad($details['item_qty'][$id_index],8," "));
				$printer.=(str_pad($details['item_rate'][$id_index],9," "));
				$printer.=(number_format(str_pad($details['item_amount'][$id_index],30," "),2));
				$printer.=("<br>");
				
			}*/
			
			foreach ($details['item_id'] as $id_index => $id_item) {
				
				$printer.=(columnify($sno++,trim($details['item_description'][$id_index]),2,21,2));
				$printer.=(str_pad(round($details['item_qty'][$id_index]),3," "));
				$printer.=(str_pad(round($details['item_rate'][$id_index],2),6," "));
				//$printer.="<div >";
				$printer.=('<span style="float:right;">'.round(str_pad($details['item_amount'][$id_index],8," ")).'</span>');
				//$printer.=("</div>");
				$printer.=("<br>");
				
			}

			$printer.=("----------------------------------------<br>");
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer.=(str_pad("",6," "));
			$printer.=(str_pad(" Total",19," "));
			$printer.=(str_pad(array_sum($details['item_qty']),9," "));
			$printer.=('<span style="float:right;">'.trim($printgroup[$index]['sub_total_items']).'</span>');
			//$printer -> selectPrintMode();
			//$printer->feed();
			$printer.=("<br>----------------------------------------<br>");
			$printer.=("<div style='width:265px;text-align: right;'>");
			$printer.="<div>";
			if($details['total_item_discount_amount']>0){
			$printer.=('<div style="margin-left:69px;"><span style="float:left;">'.str_pad('Discount : ',18," ").'</span>');
			$printer.=('<span style="float:right;">'.str_pad($printgroup[$index]['total_item_discount_amount'],-10," ").'</span>');

			$printer.=("<br>");
			}

			if($details['discount_amount_additional']>0){
			$printer.=('<span style="float:left;">'.str_pad('Discount : ',18," ").'</span>');
		    $printer.=('<span style="float:right;">'.str_pad($details['discount_amount_additional'],-10," ").'</span>');
			$printer.=("<br>");
			}
			if($printgroup[$index]['sc_reverse']>0 && $index!='74'){
			$printer.=('<span style="float:left;">'.str_pad("Service Charges 10% :",18," ").'</span>');
			$printer.=($printgroup[$index]['sc_charges_net_amount']);
			$printer.=("<br>");
				$sc_charges_net_amount	= $printgroup[$index]['sc_charges_net_amount'];
			}else{
				$details['net_amount']=$details['net_amount']-($printgroup[$index]['sc_charges_net_amount']+$printgroup[$index]['sc_sgst']+$printgroup[$index]['sc_cgst']);
				$sc_charges_net_amount	= 0;
				}
		    $printer.=("</div>");

			$printer.=("<div style='margin-left:69px;'>");	
				$printer.=('<b><span style="float:left;">'.str_pad('Sub Total : ',18," ").'</span>');
			$printer.=('<span style="float:right;">'.str_pad((($printgroup[$index]['sub_total_items']+$sc_charges_net_amount)-($details['discount_amount_additional']+$printgroup[$index]['total_item_discount_amount'])),-10," ").'</b></span>');	
			$printer.=("<br>");						
			$printer.= calculateTaxprintSplitIcon($printgroup,$index);
		    $printer.=("</div>");
			$printer.="<div><div style='margin-left:69px;'>";
			//$printer.=("<div style='float:left;'>Total:</div> <div style='float:right;'> ".round($details['net_amount'],2).'</div>');
			//$printer.=("Round Off:".round((round($details['net_amount'],0)-$details['net_amount']),2).'');
			$printer.=('<span style="float:left;">'.str_pad('Round Off:',18," ").'</span>');
			$printer.=('<span style="float:right;">'.str_pad(round((round($details['net_amount'],0)-$details['net_amount']),2).'',-10," ").'</span>');

			$printer.=("<br><span style='float:left;font-size:17px;'><b>Total:</span>");
			$printer.=("<span style='float:right;font-size:17px;'>".str_pad(round($details['net_amount'],0),-10," ").'</b></span></div>');
			$printer.="</div>";
			$printer.="</div>";
			//debugData($details);
			if($printgroup[$index]['footer_remarks']!=''){
		       $printer.=("<br><br><div style='margin-top: 0px;float: right;width:265px;white-space:break-spaces;text-align: center;'>".($printgroup[$index]['footer_remarks']).'</div>');
		    }
			
			$printer.="</div>";
			//$printer.=("<br>");
			//$printer.=("<br>");
			//$printer.=("<br>");
			//$printer.=("<br>");
			//$printer.=("<br>");
			//$printer.=("<br>");
			/*$printer.=("Round Off : ");
			$printer.=(round((round($details['net_amount'],0)-$details['net_amount']),2));
			$printer.=("<br>");

			
			$printer.=("Grand Total : ");
			$printer.=(str_pad(round($details['net_amount'],0),5," "));
			$printer.=("</div> <br>");*/
			
			/*** Outlet Information End ***/
			$TotalArray[$details['grouparray']]['name']=$details['charges_sales_local_alias_name'];
			$TotalArray[$details['grouparray']]['total']=str_pad(round($details['net_amount'],2),0," ");
			$RecordCount++;
		}
		//debugData($TotalArray);
		//GRAND Total =========================================================
		//number_format((float)$foo, 2, '.', '')
		$GrandTotalCount	=	count($TotalArray);
		$printer.=("<div style='width:285px;text-align: right;'><div style='text-align:right;margin-right:45px;font-size:13px;'>");
		
		$printer.=("<br>");
		if($GrandTotalCount>1){
		$printer.=("-------------------------------------<br>");	
		foreach($TotalArray as $totalData){
			$GrandTotal	+=$totalData['total'];
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH)
			$printer.=(str_pad("",0," "));
			$printer.=(str_pad($totalData['name'],24.5," "));
			$printer.=(str_pad('',4," "));
			$printer.=(trim(number_format((float)$totalData['total'], 2, '.', '')));
			$printer.=("<br>");
		}
		$printer.=("-------------------------------------<br>");
		
			
			
		
		
		
	

	

			
	
	
		
		
		//$printer.=("Round Off  ".round((round($GrandTotal,0)-$GrandTotal),2).'');
		$printer.=("</div></div>");
		//$printer.=("---------------------------------------");
		$printer.=("<div style='font-weight:bold;font-size:13px;'>");
			$printer.=(str_pad("",19," "));
			$printer.=(str_pad('Grand Total:',1," "));
			$printer.=(str_pad('',0," "));
			$printer.=(trim(str_pad(round($GrandTotal,0),0," ")));
			$printer.=("</div>");
			
		$printer.=("----------------------------------------<br>");
		}
		//GRAND Total =========================================================
	}
		
	return $printer;
}	


	function calculateTaxprintSplitIcon($printgroup,$index){	
	global $connNew;
	$resultcc = array_unique($printgroup[$index]['item_Tax_sgst_percentage']);
	
	 $GrandTotalCount	=	count($printgroup);
//debugData($printgroup);
	
	//foreach ($printgroup as  $details) {
		//debugData($details);
		
		foreach ($printgroup[$index]['item_id'] as $id_index => $details) {
		//debugData($details);
		$item_Tax_sgst_percentage		  =	 $printgroup[$index]['item_Tax_sgst_percentage'][$id_index];
		$item_Tax_cgst_percentage		  =	 $printgroup[$index]['item_Tax_cgst_percentage'][$id_index];
		$item_Tax_igst_percentage		  =	 $printgroup[$index]['item_Tax_igst_percentage'][$id_index];
		$item_Tax_cess_percentage		  =	 $printgroup[$index]['item_Tax_cess_percentage'][$id_index];
		$item_Tax_vat_percentage	 	   =	 $printgroup[$index]['item_Tax_vat_percentage'][$id_index];
		$item_Tax_surcharge_percentage	 =	 $printgroup[$index]['item_Tax_surcharge_percentage'][$id_index];
		
		if($item_Tax_sgst_percentage>0.00 || ($printgroup[$index]['sc_sgst'][$id_index]>0 && $GrandTotalCount=='1')){
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['percentage']=$printgroup[$index]['item_Tax_sgst_percentage'][$id_index];
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['Tax'] +=($printgroup[$index]['item_Tax_sgst'][$id_index]);
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['name']=$printgroup[$index]['sgst_display_alias_name'][$id_index] ;//'SGST';
		}
		if($item_Tax_cgst_percentage>0.00 || ($printgroup[$index]['sc_cgst'][$id_index]>0 && $GrandTotalCount=='1')){
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['percentage']=$printgroup[$index]['item_Tax_cgst_percentage'][$id_index];
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['Tax'] +=($printgroup[$index]['item_Tax_cgst'][$id_index]);
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['name']=$printgroup[$index]['cgst_display_alias_name'][$id_index];//'CGST';
		}
		
		if($item_Tax_igst_percentage>0.00){
		$taxRecord[$item_Tax_igst_percentage]['igst']['percentage']=$printgroup[$index]['item_Tax_igst_percentage'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['Tax'] +=$printgroup[$index]['item_Tax_igst'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['name']=$printgroup[$index]['igst_display_alias_name'][$id_index];//'IGST';
		}
		if($item_Tax_cess_percentage>0.00){
		$taxRecord[$item_Tax_cess_percentage]['cess']['percentage']=$printgroup[$index]['item_Tax_cess_percentage'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['Tax'] +=$printgroup[$index]['item_Tax_cess'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['name']=$printgroup[$index]['cess_display_alias_name'][$id_index];//'cess';
		}
		if($item_Tax_vat_percentage>0.00){
		$taxRecord[$item_Tax_vat_percentage]['vat']['percentage']=$printgroup[$index]['item_Tax_vat_percentage'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['Tax'] +=$printgroup[$index]['item_Tax_vat'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['name']=$printgroup[$index]['vat_display_alias_name'][$id_index];//''VAT';
		}
		if($item_Tax_surcharge_percentage>0.00){
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['percentage']=$printgroup[$index]['item_Tax_surcharge_percentage'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['Tax'] +=$printgroup[$index]['item_Tax_surcharge'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['name']=$printgroup[$index]['surcharge_display_alias_name'][$id_index];//'surcharge';
		}
		
		
					 
		}
		//debugData($taxRecord);
	///}
	
	if( $printgroup[$index]['sc_sgst']>0  &&  $index!='74'){		
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['Tax'] +=$printgroup[$index]['sc_sgst'];
		}
	if( $printgroup[$index]['sc_cgst']>0 && $index!='74'){		
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['Tax'] +=$printgroup[$index]['sc_sgst'];		
		}
	
	foreach($taxRecord  as $indexTax => $detailss){
		foreach ($detailss as $id_Taxindex => $idtaxname) {
			
			if($idtaxname['percentage']>0){
			$taxPerc	=	trim($idtaxname['percentage'])."% ";
			}
			$printer.=('<span style="float:left;">'.str_pad($idtaxname['name'].' '.$taxPerc." : ",18," ").'</span>');
			$printer.=('<span style="float:right;">'.str_pad($idtaxname['Tax'],-10," ").'</span>');
			$printer.=("<br>");
		}
			
	}
			
			
			
			



	return $printer;
}
	?>