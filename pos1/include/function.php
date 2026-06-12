<?php
function columnifySet($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
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
		//return implode($allLines) . "\n";
	    return implode($allLines, "\n");
}
function fetchdataprint($pos_purch_id_array,$grouparray=0){	
	global $connNew;
	foreach($pos_purch_id_array as $posID){
	//Com
	 $pos_purch_sql="SELECT * FROM ".TBL_PURCH." AS A  WHERE A.id_shop='".$_SESSION['shop']."'  AND id='".$posID."'";
	$resultPosPurch = mysqli_query($connNew, $pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
	
	//================================================
	/*$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$posPurchResult->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);*/
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	
	//==============footer_remarks==================================
	$pos_AccountDoc2="SELECT footer_remarks FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";	
	$resultpos_AccountDoc2 = mysqli_query($connNew, $pos_AccountDoc2); 
	$posDocResultpos_AccountDoc2 = mysqli_fetch_object($resultpos_AccountDoc2);
	$footer_remarks	= $posDocResultpos_AccountDoc2->footer_remarks;
	//===============footer_remarks=================================
	
	//================================================
	
	
	
		 //echo $posPurchResult->id_pos_details_split;
		 //debugData($posPurchResult);
		  checkPaymentStatus($posID);
 $editSql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE  id_pos_purch= '".$posID."'");
while($editrow = mysqli_fetch_object($editSql)){
 $edit_id1 = $editrow->id;
  $string1 .= $edit_id1.',';
}
 $edit_id = rtrim($string1,',');	 
		 
		 $pos_purch_detail_sql="SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$edit_id.")";
		 //$pos_purch_detail_sql="SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$posPurchResult->id_pos_details_split.")";
		$resultPosPurchDerails = mysqli_query($connNew,$pos_purch_detail_sql); 
		$numRows = @mysqli_num_rows($resultPosPurchDerails);
		while($resResult = @mysqli_fetch_object($resultPosPurchDerails)){
			

$id_item_type=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$posPurchResult->id_attribute_steward."'  AND table_name ='".'steward'."' ");
$attribute_table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$posPurchResult->id_attribute_table."'  AND table_name ='".'table'."' ");
 $ResultKotdocQuerymdoc_no=   array();
$GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$posPurchResult->kot_doc_no."') ";
$KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
	$ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
}
$ip=	implode(',',$ResultKotdocQuerymdoc_no); 

$sgst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_sgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$cgst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_cgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$igst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_igst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$cess_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_cess.'" AND id_shop="'.$_SESSION['shop'].'" ');
$vat_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_vat.'" AND id_shop="'.$_SESSION['shop'].'" ');
$surcharge_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_surcharge.'" AND id_shop="'.$_SESSION['shop'].'" ');
			
$discount_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$posPurchResult->id_mst_charges_discounts.'" AND id_shop="'.$_SESSION['shop'].'" ');			
			
			$id_service_charge = selectColumn(TBL_OUTLETS,'id_service_charge','WHERE id="'.$posPurchResult->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
	$service_charge_display_alias_name =selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$id_service_charge.'"  ');
			
			$printgroup['print_BillSplit'][$grouparray]['item_id'][]=$resResult->id_mst_items;			
			$printgroup['print_BillSplit'][$grouparray]['item_description'][]=$resResult->item_description;			
			$printgroup['print_BillSplit'][$grouparray]['item_qty'][]=$resResult->qty;	
			$printgroup['print_BillSplit'][$grouparray]['item_rate'][]=$resResult->item_amount;	
			$printgroup['print_BillSplit'][$grouparray]['item_amount'][]=round(($resResult->item_amount*$resResult->qty),2);	
			$printgroup['print_BillSplit'][$grouparray]['item_discount_amount'][]=$resResult->item_discount_amount;	
			$printgroup['print_BillSplit'][$grouparray]['item_discount_percent'][]=$resResult->item_discount_percent;	
			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst'][]=$resResult->item_sgst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst'][]=$resResult->item_cgst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst'][]=$resResult->item_igst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess'][]=$resResult->item_cess_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat'][]=$resResult->item_vat_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge'][]=$resResult->item_surcharge_amount; 

			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst_id'][]=$resResult->id_mst_charges_sgst;			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst_id'][]=$resResult->id_mst_charges_cgst;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst_id'][]=$resResult->id_mst_charges_igst;			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess_id'][]=$resResult->id_mst_charges_cess;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat_id'][]=$resResult->id_mst_charges_vat; 			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge_id'][]=$resResult->id_mst_charges_surcharge; 
			
			//
			$printgroup['print_BillSplit'][$grouparray]['sgst_display_alias_name'][]=$sgst_display_alias_name!=''?$sgst_display_alias_name:'SGST @2.5% ';
			$printgroup['print_BillSplit'][$grouparray]['cgst_display_alias_name'][]=$cgst_display_alias_name!=''?$cgst_display_alias_name:'CGST @2.5%';
			$printgroup['print_BillSplit'][$grouparray]['igst_display_alias_name'][]=$igst_display_alias_name!=''?$igst_display_alias_name:'IGST';
			$printgroup['print_BillSplit'][$grouparray]['cess_display_alias_name'][]=$cess_display_alias_name!=''?$cess_display_alias_name:'CESS';
			$printgroup['print_BillSplit'][$grouparray]['vat_display_alias_name'][]=$vat_display_alias_name!=''?$vat_display_alias_name:'VAT';
			$printgroup['print_BillSplit'][$grouparray]['surcharge_display_alias_name'][]=$surcharge_display_alias_name!=''?$surcharge_display_alias_name:'SURCHARGE';//'cb cess';
			$printgroup['print_BillSplit'][$grouparray]['discount_display_alias_name']=$discount_display_alias_name!=''?$discount_display_alias_name:'Discount';
				$printgroup['print_BillSplit'][$grouparray]['service_charge_display_alias_name']=$service_charge_display_alias_name!=''?$service_charge_display_alias_name:'Service Charge 10% ';
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst_percentage'][]=$resResult->item_sgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst_percentage'][]=$resResult->item_cgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst_percentage'][]=$resResult->item_igst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess_percentage'][]=$resResult->item_cess_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat_percentage'][]=$resResult->item_vat_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge_percentage'][]=$resResult->item_surcharge_percent;
			
			
			$printgroup['print_BillSplit'][$grouparray]['id_mst_outlet']=$posPurchResult->id_mst_outlet;
			$printgroup['print_BillSplit'][$grouparray]['discount_amount_additional']=$posPurchResult->discount_amount_additional;
			$printgroup['print_BillSplit'][$grouparray]['others_charges_net_amount']=$posPurchResult->others_charges_net_amount; 
			
			$printgroup['print_BillSplit'][$grouparray]['sc_sgst']=$posPurchResult->sc_sgst; 
			$printgroup['print_BillSplit'][$grouparray]['sc_cgst']=$posPurchResult->sc_cgst;
			$printgroup['print_BillSplit'][$grouparray]['sc_reverse']=$posPurchResult->sc_reverse;
			$printgroup['print_BillSplit'][$grouparray]['sc_charges_net_amount']=$posPurchResult->sc_charges_net_amount;
			
			
			$printgroup['print_BillSplit'][$grouparray]['id_doc_type_configuration']=$posPurchResult->id_doc_type_configuration; 
			$printgroup['print_BillSplit'][$grouparray]['enable_split_bill_by_sales_account_group']=$enable_split_bill_by_sales_account_group; 
			$printgroup['print_BillSplit'][$grouparray]['footer_remarks']=$footer_remarks; 
			$printgroup['print_BillSplit'][$grouparray]['id_attribute_steward']=$posPurchResult->id_attribute_steward; 
			$printgroup['print_BillSplit'][$grouparray]['doc_date']=$posPurchResult->doc_date; 
			$printgroup['print_BillSplit'][$grouparray]['doc_no']=$posPurchResult->doc_no; 
			$printgroup['print_BillSplit'][$grouparray]['kot_doc_no']=$ip;//$posPurchResult->kot_doc_no; 
			$printgroup['print_BillSplit'][$grouparray]['mdoc_no']=$posPurchResult->mdoc_no; 
			$printgroup['print_BillSplit'][$grouparray]['id_attribute_table']=$posPurchResult->id_attribute_table;
			$printgroup['print_BillSplit'][$grouparray]['pax']=$posPurchResult->pax;
			$printgroup['print_BillSplit'][$grouparray]['id_purch']=$posPurchResult->id;
			$printgroup['print_BillSplit'][$grouparray]['attribute_table_name']=$attribute_table_name;
			$printgroup['print_BillSplit'][$grouparray]['cancelled']=$posPurchResult->cancelled;
			$printgroup['print_BillSplit'][$grouparray]['date_created']=$posPurchResult->date_created;

			/* calculations---------------------------------------------------------- */
			$printgroup['print_BillSplit'][$grouparray]['sub_total_items']  +=($resResult->item_amount*$resResult->qty);			
			$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']  +=$resResult->item_discount_amount;			
			$printgroup['print_BillSplit'][$grouparray]['net_amount_items'] +=(($resResult->item_amount*$resResult->qty)-$resResult->item_discount_amount);
			
			$printgroup['print_BillSplit'][$grouparray]['sgst_net_amount'] += $resResult->item_sgst_amount;
			$printgroup['print_BillSplit'][$grouparray]['cgst_net_amount'] += $resResult->item_cgst_amount;			
			$printgroup['print_BillSplit'][$grouparray]['igst_net_amount'] += $resResult->item_igst_amount;			
			$printgroup['print_BillSplit'][$grouparray]['cess_net_amount'] += $resResult->item_cess_amount;			
			$printgroup['print_BillSplit'][$grouparray]['vat_net_amount']  += $resResult->item_vat_amount;			
			$printgroup['print_BillSplit'][$grouparray]['surcharge_net_amount'] += $resResult->item_surcharge_amount;
			
			$printgroup['print_BillSplit'][$grouparray]['item_net_sgst_percentage'] +=$resResult->item_sgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_cgst_percentage'] +=$resResult->item_cgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_igst_percentage'] +=$resResult->item_igst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_cess_percentage'] +=$resResult->item_cess_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_vat_percentage']  +=$resResult->item_vat_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_surcharge_percentage'] +=$resResult->item_surcharge_percent;
		
			$RoundOfAmount	+=	(($resResult->item_amount*$resResult->qty)+$resResult->item_sgst_amount+$resResult->item_cgst_amount+$resResult->item_igst_amount+$resResult->item_cess_amount+$resResult->item_vat_amount+$resResult->item_surcharge_amount);
			
			$printgroup['print_BillSplit'][$grouparray]['round_off_amount'] = (($RoundOfAmount+$posPurchResult->others_charges_net_amount)-($posPurchResult->discount_amount_additional+$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']));
						
			$printgroup['print_BillSplit'][$grouparray]['net_amount'] =(($RoundOfAmount+$posPurchResult->sc_charges_net_amount+$posPurchResult->sc_sgst+$posPurchResult->sc_cgst+$posPurchResult->others_charges_net_amount)-($posPurchResult->discount_amount_additional+$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']));
			}
			$grouparray++;
		}
		//print_r($printgroup);
		return $printgroup['print_BillSplit'];
	}
function calculateTaxprint($printgroup){	
	global $connNew;
	$resultcc = array_unique($printgroup[0]['item_Tax_sgst_percentage']);
	
	
	
	
	foreach ($printgroup as $index => $details) {
		
		foreach ($details['item_id'] as $id_index => $id_item) {
		
		$item_Tax_sgst_percentage		  =	 $details['item_Tax_sgst_percentage'][$id_index];
		$item_Tax_cgst_percentage		  =	 $details['item_Tax_cgst_percentage'][$id_index];
		$item_Tax_igst_percentage		  =	 $details['item_Tax_igst_percentage'][$id_index];
		$item_Tax_cess_percentage		  =	 $details['item_Tax_cess_percentage'][$id_index];
		$item_Tax_vat_percentage	 	   =	 $details['item_Tax_vat_percentage'][$id_index];
		$item_Tax_surcharge_percentage	 =	 $details['item_Tax_surcharge_percentage'][$id_index];
		
		if($item_Tax_sgst_percentage>0 || $details['sc_sgst']>0){
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['percentage']=$details['item_Tax_sgst_percentage'][$id_index];
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['Tax'] +=($details['item_Tax_sgst'][$id_index]);
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['name']=$details['sgst_display_alias_name'][$id_index];//'SGST';
		}
		if($item_Tax_cgst_percentage>0 || $details['sc_cgst']>0){
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['percentage']=$details['item_Tax_cgst_percentage'][$id_index];
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['Tax'] +=($details['item_Tax_cgst'][$id_index]);
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['name']=$details['cgst_display_alias_name'][$id_index];//'CGST';
		}
		if($item_Tax_igst_percentage>0){
		$taxRecord[$item_Tax_igst_percentage]['igst']['percentage']=$details['item_Tax_igst_percentage'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['Tax'] +=$details['item_Tax_igst'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['name']=$details['igst_display_alias_name'][$id_index];//'IGST';
		}
		if($item_Tax_cess_percentage>0){
		$taxRecord[$item_Tax_cess_percentage]['cess']['percentage']=$details['item_Tax_cess_percentage'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['Tax'] +=$details['item_Tax_cess'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['name']=$details['cess_display_alias_name'][$id_index];//'cess';
		}
		if($item_Tax_vat_percentage>0){
		$taxRecord[$item_Tax_vat_percentage]['vat']['percentage']=$details['item_Tax_vat_percentage'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['Tax'] +=$details['item_Tax_vat'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['name']=$details['vat_display_alias_name'][$id_index];//''VAT';
		}
		if($item_Tax_surcharge_percentage>0){
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['percentage']=$details['item_Tax_surcharge_percentage'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['Tax'] +=$details['item_Tax_surcharge'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['name']=$details['surcharge_display_alias_name'][$id_index];//'surcharge';
		}
		
		
					 
		}
	}
	if( $details['sc_sgst']>0){		
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['Tax'] +=$details['sc_sgst'];
		}
	if( $details['sc_cgst']>0){		
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['Tax'] +=$details['sc_sgst'];		
		}
	
	foreach($taxRecord  as $indexTax => $detailss){
		foreach ($detailss as $id_Taxindex => $idtaxname) {
			
			if($idtaxname['percentage']>0){
			$taxPerc	=	trim($idtaxname['percentage'])."% ";
			}
			$printer.=($idtaxname['name'].' '.$taxPerc." : ");
			$printer.=($idtaxname['Tax']);
			$printer.=("<br>");
		}
			
	}
			
			
			
			



	return $printer;
}



function printDefaultPreview($printgroup){	
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
			$id_fo_bill = selectColumn(TBL_PURCH_PAY,'id_fo_bill','WHERE id_type =7 AND  id_purch="'.$details['id_purch'].'" and id_fo_bill>0 ');
			if($id_fo_bill>0){
			$id_mst_guest = selectColumn('fo_folio','id_mst_guest','WHERE  id_fo_bill="'.$id_fo_bill.'"  ');	
			$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$id_mst_guest."'");				
			$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				
			$Firstname	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
			$Lastname	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$id_mst_guest."'");
			$guestName=ucwords(strtolower(trim($Title))).''.ucwords(strtolower(trim($Firstname))).' '.ucwords(strtolower(trim($Lastname)));
			
			$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$id_fo_bill."'");
			$RoomNO= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
			
			}
			$id_outlet = $details['id_mst_outlet'];//selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$TableOutlet = mysqli_query($connNew, 'SELECT * FROM '.TBL_OUTLETS.' WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'"'); 			
			$OutletRecord = mysqli_fetch_object($TableOutlet);			
			
			$outletName = $OutletRecord->name;

			$city = $OutletRecord->city;
			$outletAddress = $OutletRecord->address;
			$id_state = $OutletRecord->id_mst_state;
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = $OutletRecord->id_mst_country_lang;
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = $OutletRecord->pincode;
			$outletMobile = $OutletRecord->mobile;
            $outletEmail = $OutletRecord->email;

			$outletPan = $OutletRecord->pan_no;
			$outletGstin = $OutletRecord->gst_no;
			$outletTin = $OutletRecord->tin_no;
			$outletHsn = $OutletRecord->hsn_code;

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
		if($posOuletResult->display_logo_on_the_pos_bill=='1'){
	
		
			$printer .='<div style="
    display: flex;
    justify-content: center;
"><img src="'.$SITE_URL.'/uploaded_files/outlets/'.$logoImage.'"  alt="" style="width:35%;"></div>';
			
		}
			//$printer.=("<br>");
			
			
			if($printgroup[0]['cancelled']==1){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:13px;font-weight:bold">CANCELLED</span></div>';
			}
			$printer .='<div style="text-align:center;"><span style="text-align:center;font-size:18px;font-weight:bold">'.$posOuletResult->name.' </span><br></div>';
			
			
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:12px;margin-right:15px;">Time:'. date('h:i A',strtotime($printgroup[0]['date_created'])).'</span></div>';
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
			$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[0]['doc_date'])),24," "));
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
			if($guestName!=''){
			$printer.=('Guest Name:'.$guestName);
			$printer.=("<br>");
			$printer.=('Room No:'.$RoomNO);
			$printer.=("<br>");
			
			$printer.=("----------------------------------------<br>");
			}
			$printer .= str_pad('SNo.', 5, " ", STR_PAD_RIGHT);
$printer .= str_pad('Description', 19, " ", STR_PAD_RIGHT);
$printer .= str_pad('Qty', 3, " ", STR_PAD_LEFT);
$printer .= str_pad('Rate', 6, " ", STR_PAD_LEFT);
$printer .= str_pad('Amount', 7, " ", STR_PAD_LEFT);
$printer .= "<br>";
$printer .= "----------------------------------------<br>";

$sno = 1;

foreach ($details['item_id'] as $id_index => $id_item) {
    $desc = trim($details['item_description'][$id_index]);
    $descWidth = 20; // max width for description column

    // Break description into chunks of 15 characters
    $descLines = str_split($desc, $descWidth);

    // First line with SNo, Description, Qty, Rate, Amount
    $printer .= str_pad($sno++, 3, " ", STR_PAD_RIGHT);
    $printer .= str_pad($descLines[0], $descWidth, " ", STR_PAD_RIGHT);
    $printer .= str_pad((int)$details['item_qty'][$id_index], 4, " ", STR_PAD_LEFT);
    $printer .= str_pad(str_replace(',', '', number_format($details['item_rate'][$id_index], 0)), 5, " ", STR_PAD_LEFT);
    $printer .= str_pad(str_replace(',', '', number_format($details['item_amount'][$id_index], 0)), 6, " ", STR_PAD_LEFT);
    $printer .= "<br>";

    // Print remaining description lines (if any) under the first line
    for ($i = 1; $i < count($descLines); $i++) {
        $printer .= str_pad("", 3, " "); // empty SNo space
        $printer .= str_pad($descLines[$i], $descWidth, " ", STR_PAD_RIGHT);
        $printer .= "<br>";
    }
}


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
		$GuestQuery=	"SELECT name 
FROM pos_guest 
WHERE FIND_IN_SET('".$details['id_purch']."', TRIM(LEADING ',' FROM ids_pos_purch)) > 0";
			
				$resultGuest= mysqli_query($connNew, $GuestQuery); 
	if(mysqli_num_rows($resultGuest)>0){
	$rowGuest = mysqli_fetch_object($resultGuest);
		
		//$printer.=($rowGuest->name);
		
	}
			
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



function printBillSummary($printgroup){	
	global $connNew;
	
	if(is_array($printgroup)){

		foreach ($printgroup as $index => $details) {
		//debugData($printgroup);
		//print_r($printgroup);
	
		
	
		$details['id_purch'];
		
			/**** Outlet Information ***/
			
			$id_outlet = selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			
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
			
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:12px;">Time:'. date('h:i A',strtotime($printgroup[0]['date_created'])).'</span></div>';
			if($printgroup[0]['cancelled']==1){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:13px;font-weight:bold">CANCELLED</span></div>';
			}
			$printer .='<div style="text-align:center;"><span style="text-align:center;font-size:18px;font-weight:bold">'.$posOuletResult->name.' </span><br><span style="margin-left:10px;font-weight:bold">'.$posOuletResult->city.'</span></div>';
			$printer.=("<br>");
			
			

			//$printer.=((trim($outletAddress)));
			
			
			
			
			
			$printer.=("----------------------------------------\n");
			
			//$printer.=(str_pad('Bill No : '.$index,25," "));
			//$printer.=(str_pad('Bill No : '.$printgroup[0]['mdoc_no'],25," "));
			//$printer.=("<br>");
			//$printer.=('<span style="white-space:pre-wrap">Kot No : '.$printgroup[0]['kot_doc_no']).'</span>' ;
			//$printer.=("<br>");
			$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[0]['doc_date'])),24," "));
			$printer.=('Table No: '.$printgroup[0]['attribute_table_name']);
			$printer.=("<br>");
			//$printer.=("-------------------------------------\n");

			
			
			
			$printer.=("----------------------------------------<br>");

			
			$printer.=(str_pad('SNo.',5," "));
			$printer.=(str_pad('Bill No',19," "));
			
			$printer.=str_pad('Amount',8," ");
			$printer.=("<br>");
			$printer.=("----------------------------------------<br>");
			$sno=1;
			
			
			

 /*$TableBillExistSql	=	"SELECT * from ( select pp.*, max(pp.grant_total_amount) as amount_need_to_pay, sum(ppp.amount) as amount_paid, (case when max(pp.cancelled)=1 then 'cancelled' when max(pp.grant_total_amount)=sum(ppp.amount) then 'Settled' when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)<>0 then 'Partial' when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)=0 then 'Pending' when max(pp.grant_total_amount)=0 and sum(ppp.amount) is NULL then 'Pending' when max(pp.grant_total_amount)>0 and sum(ppp.amount) is NULL then 'Pending' end) as payment_status from pos_purch pp left join pos_purch_pay ppp on ppp.id_purch=pp.id where pos_bill_type=2 group by pp.id ORDER BY last_modified desc )as managekotlist WHERE id!=0 AND doc_type=21 AND payment_status='Pending'  AND id_attribute_table='".$details['id_attribute_table']."'";*/
		 
	$CurrentDate	=date('Y-m-d',strtotime("-3 day", strtotime(date('d-m-Y'))));	   
$TableBillExistSql = "

select pp.*,

(pp.grant_total_amount) as amount_need_to_pay, 
(pp.payment_amount_received) as amount_paid,
(pp.grant_total_amount-pp.payment_amount_received) as balance_amount

from pos_purch  pp 
 where pp.pos_bill_type=2 and DATE(pp.doc_date)>'".$CurrentDate."'
and  pp.id!=0 AND pp.doc_type=21 AND  pp.id_attribute_table='".$details['id_attribute_table']."' and cancelled!=1 and pp.grant_total_amount-pp.payment_amount_received>0
ORDER BY pp.last_modified desc";	 
		 
	$resultTableBillExist = mysqli_query($connNew, $TableBillExistSql); 
	mysqli_num_rows($resultTableBillExist);
	while($rowTableBillExist = mysqli_fetch_object($resultTableBillExist)){
	//print_r($rowTableBillExist);
	
				$printer.=(columnify($sno++,trim($rowTableBillExist->mdoc_no),2,22,2));
				
				$printer.=(round(str_pad($rowTableBillExist->amount_need_to_pay,8," ")));
				//$printer.=("</div>");
				$printer.=("<br>");
				$amount_need_to_pay	+=$rowTableBillExist->amount_need_to_pay;
	}













			$printer.=("----------------------------------------<br>");
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer.=(str_pad("",10," "));
			$printer.=(str_pad(" Total",16," "));
			//$printer.=(str_pad(array_sum($details['item_qty']),6," "));
			$printer.=(trim($amount_need_to_pay));
			//$printer -> selectPrintMode();
			//$printer->feed();
			$printer.=("<br>----------------------------------------<br>");
			
			$printer.=("<br>");
			
			
		}

	}
		
	return $printer;
}













function consolidatedItemWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$kot_nc,$appConnect,$connNew,$id_shop,$cronSet,$pdfName,$id_report_format, $id_outlet,$production_item){	
	//global $connNew;
	$contentstyle='';
	//global $appConnect;
	$_SESSION['shop']=$id_shop;
	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;
//print_r($_REQUEST);

if ($id_outlet!='') {
	$sqloutlet="SELECT * FROM mst_outlets WHERE status='1'  AND FIND_IN_SET(id,'".$id_outlet."')";
           
	$resoutlet = mysqli_query($connNew,$sqloutlet);
   $outletSelectSearch=array();
   while($rowoutlet = mysqli_fetch_object($resoutlet)){
	   array_push($outletSelectSearch,$rowoutlet->name);
   }
	$showoutletName	=	implode(',',$outletSelectSearch);	
}
if($id_items!=''){
			 $sqlinvItem="SELECT * FROM inv_items WHERE status='1'  AND FIND_IN_SET(id,'".$id_items."')";
           
            $resinvItem = mysqli_query($connNew,$sqlinvItem);
		   $ItemSelectSearch=array();
           while($rowinvItem = mysqli_fetch_object($resinvItem)){
			   array_push($ItemSelectSearch,$rowinvItem->name);
		   }
		    $showitemName	=	implode(',',$ItemSelectSearch);

}

if($id_main_group!=''){
			 $sqlmain_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  FIND_IN_SET(id,'".$id_main_group."')";
           
            $resmain_group = mysqli_query($connNew,$sqlmain_group);
		   $main_groupSelectSearch=array();
           while($rowmain_group = mysqli_fetch_object($resmain_group)){
			   array_push($main_groupSelectSearch,$rowmain_group->field_value);
		   }
		     $showiMainGroupName	=	implode(',',$main_groupSelectSearch);

}

if($id_sub_group!=''){
			   $sqlsub_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND  FIND_IN_SET(id,'".$id_sub_group."')";
           
            $ressub_group = mysqli_query($connNew,$sqlsub_group);
		   $sub_groupSelectSearch=array();
           while($rowsub_group = mysqli_fetch_object($ressub_group)){
			   array_push($sub_groupSelectSearch,$rowsub_group->field_value);
		   }
		    $showSubGroupName	=	implode(',',$sub_groupSelectSearch);

}

$content ='';
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SqlRepDateConn =	" AND DATE(doc_date) between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";	
	}
if($id_main_group!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_attributes_group_main,'".$id_main_group."')";	
	}
	
if($id_sub_group!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_attributes_group_sub,'".$id_sub_group."')";	
	}
if($id_items!=''){
	$SqlRepConn .=	" AND FIND_IN_SET(id_mst_items,'".$id_items."')";	
	}
	if($id_outlet!=''){
		$SqlRepConn .=	" AND FIND_IN_SET(id_mst_outlet,'".$id_outlet."')";	
		}
	
	if($production_item!=''){
		
		$SqlRepConn .=	" AND item_production_item='".$production_item."'";	
		}
	
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
	
		//196">Consolidated Item Wise //pp.id_mst_outlet, pp.id_attribute_steward, pp.id_attribute_table,pp.id_attribute_shift
if($id_report_type!=''){

	if($id_report_type=='197'){//Pos Day Wise
		$SqlGroupByConn =',doc_date';
		$SqlOrderByConn ='doc_date';
		$SqlOrderByConnType =' desc ';
	}elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='199'){//POS Outlet
		$SqlGroupByConn =',id_mst_outlet';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';	
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}
	
	}
if($id_order_by!=''){	
  if($id_order_by==1){
	 // $OrderDiplay	=	'Name';
	 // $SqlOrderByConn ='item_description';
	 // $SqlOrderByConnType =' ASC ';
	  
	  if($id_report_type=='197'){//Pos Day Wise
		$OrderDiplay	=	'Date Wise';
		$SqlOrderByConn ='doc_date';
		$SqlOrderByConnType =' DESC ';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$OrderDiplay	=	'Steward Name';
		$SqlOrderByConn ='steward_name';
		$SqlOrderByConnType =' ASC ';	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$OrderDiplay	=	'Name';
	  $SqlOrderByConn ='item_description';
	  $SqlOrderByConnType =' ASC ';
	}elseif($id_report_type=='238'){//	Pos Shift Wise<
		$OrderDiplay	=	'shift name';
	  $SqlOrderByConn ='shift_name';
	  $SqlOrderByConnType =' ASC ';
	}elseif($id_report_type=='199'){//POS Outlet
		$OrderDiplay	=	'Outlets name';
	  $SqlOrderByConn ='outlets_name';
	  $SqlOrderByConnType =' ASC ';
	}/*elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';	
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	
	}elseif($id_report_type=='196'){//	Pos Item Wise<
		$SqlGroupByConn ='';
		$SqlOrderByConn ='id_mst_attributes_group_main,id_mst_attributes_group_sub';
	}*/
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  }
	 if($id_order_by==2){
	  $SqlOrderByConn ='qty';
	  $SqlOrderByConnType =' DESC ';
	  $OrderDiplay	=	'Qty';
	  }
	  if($id_order_by==3){
	  $SqlOrderByConn ='grandtotal';
	  $SqlOrderByConnType =' DESC ';
	  $OrderDiplay	=	'Amount';
	  } 	  
} if($SqlOrderByConn==''){
	$SqlOrderByConn ='id_mst_items';
		$SqlOrderByConnType =' ASC ';
	
	}
	
	if($kot_nc =='0'){ //0">Without KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type='2' and pp.doc_type='21'";
}
if($kot_nc =='1'){ //1">With KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type IN (1,2) and pp.doc_type IN (21,24)";
}
if($kot_nc =='2'){ //2">Only KOT NC
$SqlRepDateConn .=" and pp.pos_bill_type='1' and pp.doc_type='24'";
}
  $pos_purch_sql="select 
			doc_date,     
			id_mst_items,
			item_code,
			item_description,
			sum(qty) as qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub,
 id_mst_outlet, id_attribute_steward, id_attribute_table,id_attribute_shift,outlets_name,steward_name,shift_name
	from 
(
    
			select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.id_mst_items_details,ppp.item_description,  ppp.id as id_purch_detail,inv.item_code,
			ppp.qty, ppp.item_amount, ppp.item_discount_amount,((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)  as  total,
			
			inv.id as id_item,inv.item_production_item,
			inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,
			pp.id_mst_outlet, pp.id_attribute_steward, pp.id_attribute_table,pp.id_attribute_shift,attributesshift.field_value as shift_name,attributessteward.field_value as steward_name,outlets.name as outlets_name
			
			FROM pos_purch  pp
			LEFT JOIN pos_purch_details ppp ON ppp.id_pos_purch=pp.id
			LEFT JOIN mst_attributes AS  attributesshift ON attributesshift.`table_name` = 'shift' and attributesshift.id=pp.id_attribute_shift	
			LEFT JOIN mst_attributes AS  attributessteward ON attributessteward.`table_name` = 'steward' and attributessteward.id=pp.id_attribute_steward
			LEFT JOIN mst_outlets AS  outlets ON  outlets.id=pp.id_mst_outlet		
			INNER JOIN inv_items inv ON inv.id=ppp.id_mst_items
			
			WHERE  pp.cancelled=0 and  pp.id_shop= '".addslashes($_SESSION['shop'])."'
			$SqlRepDateConn	
  
  			order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.name
			
    ) as purch_rpt

WHERE id_mst_items!=0 $SqlRepConn
group by id_mst_items,id_mst_items_details $SqlGroupByConn
order by  $SqlOrderByConn  $SqlOrderByConnType ";
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		//echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$DatewiseArray['Report']['id_mst_attributes_group_main']=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 if($id_report_type!=''){
	if($id_report_type=='197'){//Pos Day Wise
		$maingroupName	=date('d-m-Y',strtotime($posPurchResult->doc_date));
	}elseif($id_report_type=='198'){//Pos User Wise
		$SqlGroupByConn ='';
	}elseif($id_report_type=='199'){//POS Outlet
		$maingroupName	= strtoupper(selectColumn(TBL_OUTLETS,'name'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND `id` = '".$posPurchResult->id_mst_outlet."'  "));
		
	
	}elseif($id_report_type=='238'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_shift';
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  `id` = '".$posPurchResult->id_attribute_shift."'"));
	
	}elseif($id_report_type=='200'){//Pos Steward Wise<
		$SqlGroupByConn =',id_attribute_steward';
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  `id` = '".$posPurchResult->id_attribute_steward."'"));
	}elseif($id_report_type=='201'){//	Pos Discount Wise<
		$SqlGroupByConn ='';
	}else{
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  `id` = '".$posPurchResult->id_mst_attributes_group_main."'"));
		}
	
	}
		 
		if($id_report_type=='196'){ $maingroupName='item';
		 $DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 $DatewiseArray['Report']['id_mst_attributes_group_sub'][$maingroupName][]=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report']['id_inv_items'][$maingroupName]['item2'][]=$posPurchResult->id_mst_items;
		 $DatewiseArray['Report']['name'][$maingroupName]['item2'][] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report']['item_code'][$maingroupName]['item2'][] =$posPurchResult->item_code;
		 $DatewiseArray['Report']['grandtotal'][$maingroupName]['item2'][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report']['qty'][$maingroupName]['item2'][] =$posPurchResult->qty;	
		}else{
		 $DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 $DatewiseArray['Report']['id_mst_attributes_group_sub'][$maingroupName][]=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report']['id_inv_items'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][]=$posPurchResult->id_mst_items;
		 $DatewiseArray['Report']['name'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report']['item_code'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->item_code;
		 $DatewiseArray['Report']['grandtotal'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report']['qty'][$maingroupName][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->qty;
		}
	 }
	
	
	$MainGroup=array_unique($DatewiseArray['Report']['id_mst_attributes_group_main']);
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>
	?>
    <script>$(function() {
		
	
  
  
    $('.table').on("click", ".line", function(e){
		var checkstatus=this.classList.toggle('maingroupi');
		
		if(checkstatus == true){ 
		
		 $(".mainplusclose").show();
          $(".mainplusopen").hide();
		  
      }if(checkstatus == false){ 
	  
		   $(".mainplusopen").show(); 
        $(".mainplusclose").hide();
       
      }
		
      //$(this).addClass('fa fa-plus').siblings().removeClass('fa fa-plus').addClass('fa fa-minus');
        $(this).siblings('.subgrouphideclass').toggle(100);
	//	$(this).toggleClass('fa fa-pluse').siblings().removeClass('fa fa-pluse');
		//$(this).closest('.maingroupi').find('.hidden-item').removeClass('shown');
		$('tr.line')  
                .css("cursor", "pointer")  
                .attr("title", "Click to expand/collapse")  
                .click(function () {  
				
                    $(this).siblings('.child-' + this.id).toggle();  
                });  
            $('tr[@class^=child-]').hide().children('td');
			
			
		//var thisHiddenItem = $(this).find(".table1shafeer");
    //$(this).siblings('line').removeClass('fa fa-plus');
		//$(this).siblings(thisHiddenItem).toggleClass("fa fa-plus table1shafeer");
		//$(".line").find(".table-plus-btn table1").removeClass("fa fa-pluse").addClass("fa fa-minus");
		if (this.classList.toggle('maingroupi') == true) { 
		 //$(this).addClass('fa fa-plus').siblings().removeClass('fa fa-minus').addClass('fa fa-minus');
		//$(this).addClass('fa fa-minus').siblings().removeClass('fa fa-minus');
		//$(this).toggleClass('fa fa-pluse').siblings().removeClass('fa fa-fa fa-pluse');
		//$("#shafeer").hide();
		//$(this).siblings('.subgrouphideclass').removeClass('fa fa-plus');
		//$('fa fa-plus').not(this).removeClass('fa fa-plus');
   // $(this).toggleClass('fa fa-plus');
          //     $('#ahamed').hide();
		//document.getElementById("maingroupi").className = "fa fa-minus";
		//$(this).addClass('fa fa-pluse').siblings().addClass('fa fa-minus');
//  $("maingroupi").find(".maingroupi").removeClass("fa fa-pluse").addClass("fa fa-minus");
}
		//$(this).addClass('fa fa-minus').siblings().removeClass('fa fa-minus').addClass('fa fa-minus');
		   //document.getElementById("rdb21").className = "btn btn-foursquare col-md-3";
      }).on("click", ".subgrouphideclass", function(){ 
      ///
	  var submov	=	this.classList.toggle('submov');
	  // alert(submov);
	 // var showItemReport=$("#showItemReport").val();
	//$( "#showItemReport" ).val(opts);
	 if(submov == true){
		 $( "#showItemReport" ).val('1');
		// alert(submov);
		   $(".mainplusclose").show();
          $(".mainplusopen").hide();
		  $("#showheadinglable").show(); 
        $("#hideheadinglable").hide();
      } if(submov == false){   //alert('=='+submov);
	  $( "#showItemReport" ).val('0');
         $(".mainplusopen").show(); 
        $(".mainplusclose").hide();
		
		
		 $("#showheadinglable").hide(); 
        $("#hideheadinglable").show();
      }
	
        $(this).siblings('.mov').toggle(100);
		 //$(this).siblings('#sp1').toggle(100);
      //$(".sp1").hide();
      }).on("click", ".delete", function(){
      
        $(this).closest("table").fadeOut(function(){alert('12'); 
        	$(this)/*the table*/.remove();
        });
      
    });
  function toggleButtons(show) {alert(show);
      if (show) {
        $("#sp1").hide();
        $("#close").show();
      } else {
        $("#sp1").show();
        $("#close").hide();
      }
  }
});
	  </script>
    <?php 
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

if($report_show =='1' ){
		$contentstyle='';
$contentstyle= '
	.table tr.mov
{
    display:none;}';
	
}else{
	$contentstyle='';
	
	if( $report_show!=3){
		$contentstyle= '
	.table tr.mov,
.table tr.subgrouphideclass{
    display:block;
}
';
	}
	}
	

$content.=$contentstyle;

$content .= '</style>';
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
$sqlSubMenu="SELECT * FROM ".APP_SUB_MENU." WHERE status='1' and type='2' AND id='".$id_report_type."'";
           
            $resSubMenu = mysqli_query($appConnect,$sqlSubMenu);

            $rowSubMenu = mysqli_fetch_object($resSubMenu);
				
				//selectColumn(APP_SUB_MENU,'name'," WHERE status='1' and type='2' AND id='".$id_report_type."'");
					$headerColor = selectField(APP_COLOR_CONFIG,'report_header_color','',$appConnect); 
		$headerTextColor = selectField(APP_COLOR_CONFIG,'report_header_text_color','',$appConnect); 
		$titleColor = selectField(APP_COLOR_CONFIG,'report_title_color','',$appConnect); 
		$titleTextColor = selectField(APP_COLOR_CONFIG,'report_title_text_color','',$appConnect); 
		$subtitleColor = selectField(APP_COLOR_CONFIG,'report_subtitle_color','',$appConnect); 
		$subtitleTextColor = selectField(APP_COLOR_CONFIG,'report_subtitle_text_color','',$appConnect); 
		
	
//POS Print View FORMAT=START================================================
		if($id_report_type==196 &&  $id_report_format=='2'){	
		
		$printer.=("<br>");
			$printer.=('<div style="text-align:center;">'.ucwords($rowSubMenu->name).'  Report <br>Period '.$date.'</div>');
		
			
		$printer.=("----------------------------------------<br>");
		$printer.=(str_pad('SNo.',5," "));
			$printer.=(str_pad('Description',21," "));
			$printer.=(str_pad('Qty',4," "));
$printer.=str_pad('Amount',8," ");
			$printer.=("<br>");
			$printer.=("----------------------------------------<br>");			
$s=1;
			foreach($DatewiseArray['Report']['name'] as $id_main=>$subindexvalue){
			//foreach ($details['item_id'] as $id_index => $id_item) {
			foreach($subindexvalue as $id_subindex=>$data){$k=0;$i=1;
					$printer.=("<br>");
			foreach($data as $datavalue){
				$qty=		number_format($DatewiseArray['Report']['qty'][$id_main][$id_subindex][$k],2);
				$grandtotal=	round($DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k]);
				$printer.=(columnifySet($s++,trim($datavalue),2,23,2));
				$printer.=(str_pad(round($qty),5," "));
				
				//$printer.="<div >";
				$printer.=(str_pad(trim($grandtotal),3," "));
				
				$printer.=("<br>");
				
				$i++;
				$k++;
			}
			}
			}

$DataList='';
$DataList ='<style>
 body{font: 12px Segoe UI, Tahoma, Arial, Helvetica, sans-serif;}   
#invoice-POS{
	 
  box-shadow: 0 0 1in -0.25in rgba(0, 0, 0, 0.5);
  padding:2mm;
  margin: 0 auto;
  width: 80mm;
  background: #FFF;
  
  
::selection {background: #f31544; color: #FFF;}
::moz-selection {background: #f31544; color: #FFF;}
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
h4{
  font-size: 3px;
  font-weight: bold;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 


#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/logo1.png) no-repeat;
	background-size: 60px 60px;
}
.clientlogo{
  float: left;
	height: 60px;
	width: 60px;
	background: url(http://michaeltruong.ca/images/client.jpg) no-repeat;
	background-size: 60px 60px;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  padding: 5px 0 5px 15px;
  border: 1px solid #EEE
}
.tabletitle{
  padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}

  
  
}

</style><div class="col-xs-12"> 

  <div class="box">
    <div class="row">
      
      <div id="printTable">
          <div id="invoice-POS"> 
          <div id="bot">
              <pre style="font-size:12px!important;">
			  <style>#wordwarkwirh{ word-warp:break-word; width:200px;}</style>';
   
     
		
        $printer.=("</div>");      

        $printer.=("</div>");
		
                $printer.=("<br>");


        $printer.=("<br>");
			$DataList .= $printer; 
	$DataList .='<br><br><br>
</pre>
            </div>
        
        </div>
      </div>
    </div>
  </div>
</div>';
		//$content .= $DataList;	
		}
	//POS Print View FORMAT=START================================================


			$content .= '<table class="table table-striped text-center">';
	$content .= '<tr style="vertical-align:central;text-align:center;"><th colspan="4" style="vertical-align:central;text-align:left;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:16px !important"><b> '.ucwords($rowSubMenu->name).'  Report Period '.$date.' Order By '.$OrderDiplay.' </b></th>
	<th  style="vertical-align:central;text-align:center;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:13px !important"><b> Report Date: '.date('d-m-Y H:m:i').' </b></th></tr>';
	
	
if($showiMainGroupName!=''){	
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Main Group : </b>'.ucwords(strtolower($showiMainGroupName)).'  </th></tr>';
}if($showSubGroupName!=''){
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Sub Group : </b>'.ucwords(strtolower($showSubGroupName)).'  </th></tr>';
}if($showitemName!=''){		
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Item Name : </b>'.ucwords(strtolower($showitemName)).'  </th></tr>';
}if($showoutletName!=''){		
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Outlet Name : </b>'.ucwords(strtolower($showoutletName)).'  </th></tr>';
}
		$content .= '</table>';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>';
		if($id_report_type==196){
		$content .= '<tr style="color:'.$titleTextColor.';background-color:'.$titleColor.';font-size:16px !important;" id="hideheadinglable">';	
		$content .= '<th width="60px;"  ><b>S.no</b></th>';	
		$content .= '<th width="140px;"><b>Item Code</b></th>';
		$content .= '<th ><b>Item Name</b></th>
		<th style="width:200px;text-align: center; " ><b>Qty</b></th>
		<th style="width:200px;text-align: center;  "><b>Amount</b></th>';
		$content .= '</tr>';
		}else{
		
				if(($report_show==3) ||  ($report_show==2)){
					$displayClass	='';
				}else{
					if($cronSet!='1'){
					$displayClass	=' display:none;';} }
		$content .= '<tr style="font-size:16px !important; '.$displayClass.'" id="showheadinglable">';
		//$content .= '<th  width="60px;" >&nbsp;</th>';
		$content .= '<th  style="width:60px;" ><b>S.no</b></th>';	
		$content .= '<th style="width:140px;"><b>Item Code</b></th>';		
		$content .= '<th   ><b>Item Name</b></th>
		<th style="width:200px;text-align: center; " ><b>Qty</b></th>
		<th style="width:200px;text-align: center;  "><b>Amount</b></th>';
		$content .= '</tr>';
		}
			//$content .= '</table>	    ';
		
		//$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%">';

	
	
	
	
	$GrandTotalQTY=0;
	$GrandTotalAmount=0;
	if($id_report_type==196){
		$colspa1=3;
	}else{
		$colspa1=3;
		}
		
		
		
		
		
	foreach($DatewiseArray['Report']['name'] as $id_main=>$subindexvalue){
		//Main Group=======================>
			
		/*$content .= '<tr class="line" style="'.$BackgroundColorMain.'background-color:#c2d69a;color:#ooo !important;font-size:16px !important;">
			<th  colspan="'.$colspa1.'" ><b>'.$maingroupName.'</b></th>
			</tr>';*/
			
		$MainGroupTotalQTY=0;
		$MainGroupTotalAmount=0;
		$subgroupInc=1;	$contentSubGroup='';$contentItem='';
		foreach($subindexvalue as $id_subindex=>$data){
			
			
			
			$k=0;
			$i=1;
			
			$subgroupTotalQTY=0;
			$SubGroupTotalAmounts=0;
				
				foreach($data as $datavalue){
				
				if($id_report_type!='196'){
					$listTagClass	=	'class="mov"';
				}
				$qty=		number_format($DatewiseArray['Report']['qty'][$id_main][$id_subindex][$k],2);
				$grandtotal=	number_format($DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k],2);
				if(($report_show==1)  || ($report_show==2  && $showItemReport==1 ) ||( $report_show==3 && $showItemReport==1) || ($id_report_type=='196') || $cronSet=='1'){
					
				$contentItem .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentItem .= '<td style="text-align:center;width:200px;">'.$i.'</td>';
				$contentItem .= '<td  style="text-align:left;">'.$DatewiseArray['Report']['item_code'][$id_main][$id_subindex][$k].'</td>';
				if($id_report_type==196){
					$contentItem .= '<td  style="text-align:left;" >'.strtoupper($datavalue).'</td>';
					}else{
						$contentItem .= '<td style="text-align:left;">'.strtoupper($datavalue).'</td>';
						}
				
				$contentItem .= '<td style="text-align:right;width:200px;">'.$qty.'</td>
				<td style=text-align:right;width:200px;">'.$grandtotal.'</td>
				</tr>';
				}
				$subgroupTotalQTY+=$qty;
				$SubGroupTotalAmounts+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
				
				$MainGroupTotalQTY+=$qty;
				$MainGroupTotalAmount+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
						
				$GrandTotalQTY+=$qty;
				$GrandTotalAmount+=$DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k];
				
				$i++;
				$k++;
			}
			//$content.=$content;
		//Sub GroupItem
			/*$content .= '<tr style="border:1px solid:font-size:11px !important;">
			<td width="60px;" style="text-align:center">'.$subgroupInc.'</td>
			
			<td>'.strtoupper($subgroupName).'</td>
			
			<td style="width:100px;text-align:right;">'.number_format($subgroupTotalQTY,2).'</td>
			<td style=width:100px;text-align:right;">'.number_format($SubGroupTotalAmounts,2).'</td>
			</tr>';*/
			
			
			//Sub Group=======================>
			if($id_report_type!='196'){
				$subgroupName=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND  `id` = '".$id_subindex."'"));
			
			$contentSubGroup .= '<tr class="subgrouphideclass" style="'.$BackgroundColor.'color:#000 !important;font-size:12px !important; font-weight:bold;">';
			
			
		$contentSubGroup .=  '<td  colspan="3"  style="color:#ooo !important;text-align:left;">&nbsp;<b>'.$subgroupName.'</b></td>';
			
			
			$contentSubGroup .=  '<td style=text-align:right;width:200px;">'.number_format($subgroupTotalQTY,2).'</td>';
			$contentSubGroup .=  '<td style=text-align:right;width:200px;">'.number_format($SubGroupTotalAmounts,2).'</td>';
			$contentSubGroup .=  '</tr>';
			}
			//$contentItem2.=$contentItem;
			
			$colspa=3;
			$contentSubGroup .= $contentItem;
			$subgroupInc++;	$contentItem='';
			}
		
		if($id_report_type!='196'){  //First Step
			$content .= '<tr class="line" style="'.$BackgroundColorMain.'color:#ooo !important;font-size:16px !important;">';
			//$content .= ' <td width="120px; border-right:none;" colspan="2"  ></td>';
			$content .= ' <td  colspan="3"  style="text-align:center;" >';
			
			
			$content .= ' <b>'.$id_main.'</b></td>';
			
			
			
			$content .= '<td  style=text-align:right;width:200px;"><b>'.number_format($MainGroupTotalQTY,2).'</b></td>
						<td  style=text-align:right;width:200px;"><b>'.number_format($MainGroupTotalAmount,2).'</b></td>
			</tr>';
		}
		$content .= $contentSubGroup;	
		//$content .= $content2;
		}
		
		
	
			
			
		$content .= '</table>';
		if($id_report_type==196 && $id_report_format=='2'){
			$content = '';
		$content .= $DataList;	
		}
		$content;
		//die;
		$date=date('d-m-yy');
if($id_report_type==196){		
$Filename='consolidatedItemWiseReport_'.$date;
}else{
$Filename='consolidatedSubGroupWiseReport_'.$date;	
	}

	if($report_show==3){ 
		
//pdfGeneratorAttach($content,'te2s.pdf');
		if($cronSet=='1'){
			
			pdfGeneratorAttach($content,$pdfName);
		}else{
			$dompdf = new DOMPDF();
			$dompdf->set_paper('landscape', 'landscape');
			$dompdf->load_html($content);
			$dompdf->render();
			$font = Font_Metrics::get_font("helvetica", "bold");
			$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
			$dompdf->output();
			$dompdf->stream($Filename.'.pdf', array("Attachment" => true));
		}
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
		
	function cellColorExcel($cells,$color,$objPHPExcel){
    	//global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}	
		
function settlementSummaryReport($Date,$id_outlet,$id_shift,$objPHPExcel,$appConnect,$connNew,$id_shop,$cronSet,$pdfName){
	

	$_SESSION['shop']=$id_shop;
	
	
	//global $connNew;
	//global $objPHPExcel;
	
	
	
		
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
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



//echo '======================================================4';
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
//echo '======================================================1';//die;
$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A7', "Settlement Summary");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:Z7');



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A7:Z7')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
$con=$setcellcount;


	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$con,'From Date'.$Date);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':AB'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':AB'.$con)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

$con++;


	
$SQL="select 
       id as 'id_purch_payment'
	  ,id_attribute_shift
	  ,id_attribute_table
	  ,pax
      ,id_mst_outlet
      ,outlet_Name
      ,mdoc_no
      ,sub_total_items
      ,(discount_amount_additional+total_discount_items) AS 'Discount'
      ,others_charges_net_amount As 'OtherCharges'      
      ,sgst_total_items as sgst
      ,cgst_total_items as cgst
      ,igst_total_items as igst
      ,cess_total_items as cess
      ,vat_total_items as vat
      ,surcharge_total_items as surcharge      
      ,round_off_amount
      ,net_amount_items
      ,grant_total_amount
      ,name as 'UserName'
	  ,max(time) as Time
	  ,sum(CASH) as Cash
	  ,sum(CARD) as Card

	  ,sum(CHEQUE) as Cheque
	  ,sum(UPI) as 'Upi'
	  ,sum(ONLINETRANSFER) as 'OnlineTransfer'
	  ,sum(COMPANY) as Company
	   ,sum(BIllONHOLD) as BillOnHold
	   ,sum(ROOMTO) as RoomTo
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
	  ,id_fo_bill
	  
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
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD
	  
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI
	  ,case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
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
WHERE id!=0 $SqlConn
GROUP BY id,name,field_value  ORDER BY id_attribute_shift,id_mst_outlet,doc_no asc";
//echo $SQL;
//die;

$query = mysqli_query($connNew,$SQL);
$TotalNumberOfRows = mysqli_num_rows($query);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
while($Records	   =	mysqli_fetch_object($query)){
	$shiftSummary	=	$Records->id_attribute_shift;
	
	
	if($Records->id_attribute_shift==$shift2){
		
	//$shiftSummary=$Records->id_attribute_shift;
    //$checkshift =$shiftSummary;
 
 
 	
	}else{		
	    //SUMMARY DETAILS====================================================
		if($count2>1){
			
			$con=$con+1;
			cellColorExcel('C'.$con.':AC'.$con,'e0e0e0', $objPHPExcel);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':AC'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill: '.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetSubTotalItem-$OutLetDiscount,2))
				->setCellValue('J'.$con, round($OutLetSGST,2))
				->setCellValue('K'.$con, round($OutLetCGST,2))
				->setCellValue('L'.$con, round($OutLetIGST,2))
				->setCellValue('M'.$con, round($OutLetCESS,2))
				->setCellValue('N'.$con, round($OutLetVAT,2))
				->setCellValue('O'.$con, round($OutLetSurcharge,2))
				->setCellValue('P'.$con, round($OutLetOtherCharges,2))
				->setCellValue('Q'.$con, round($OutLetRoundOffAmount,2))
				->setCellValue('R'.$con, round($OutLetGrantTotalAmount,2))
				
				->setCellValue('U'.$con, round($OutLetCash,2))
				->setCellValue('V'.$con, round($OutLetCard,2))
				->setCellValue('W'.$con, round($OutLetCompany,2))
				->setCellValue('X'.$con, round($OutLetCheque,2))
				
				->setCellValue('Y'.$con, round($OutLetOnlineTransfer,2))
			->setCellValue('Z'.$con, round($OutLetBillOnHold,2))
			->setCellValue('AA'.$con, round($OutLetUpi,2))
			->setCellValue('AB'.$con++, round($OutLetRoomTo,2));
			
			$OutLetSubTotalItem=0;
			$TotalPax=0;
			$TotalBillCount=0;
			$OutLetDiscount= 0;
			$OutLetNetAmountItems =0;
			$OutLetSGST =0;
			$OutLetCGST =0;
			$OutLetIGST =0;
			$OutLetCESS =0;
			$OutLetVAT =0;
			$OutLetSurcharge =0;
			$OutLetOtherCharges =0;
			$OutLetRoundOffAmount =0;
			$OutLetGrantTotalAmount =0;
			$OutLetCash =0;
			$OutLetCard =0;
			$OutLetCompany =0;
			$OutLetCheque =0;
			$OutLetOnlineTransfer =0;
			$OutLetBillOnHold=0;
			$OutLetRoomTo=0;
			$OutLetUpi=0;
			
			$con=$con+2;
			cellColorExcel('A'.$con.':B'.$con,'e0e0e0', $objPHPExcel);
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, 'SUMMARY DETAILS')
				
				->setCellValue('A'.$con, 'CASH')
				->setCellValue('C'.$con++, round($sub_total_Cash,2));
				
				$objPHPExcel->setActiveSheetIndex(0)		
				->setCellValue('A'.$con, 'CARD');
				
				
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'CARD');
				foreach($sub_total_Card as $k=>$checklist){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$con, $k)
				->setCellValue('C'.$con++, round($checklist,2));
				
				}
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'BILLONHOLD')
				->setCellValue('C'.$con++, round($sub_total_BillOnHold,2));
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'UPI')
				->setCellValue('C'.$con++, round($sub_total_Upi,2));
				
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'ROOMTO')
				->setCellValue('C'.$con++, round($sub_total_RoomTo,2));			
		
		
		
		$objPHPExcel->setActiveSheetIndex(0)		
				->setCellValue('A'.$con, 'COMPANY');
				
		
				foreach($sub_total_Company as $k2=>$checklist2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$con, $k2)
				->setCellValue('C'.$con++, round($checklist2,2));
				
				}		
				
			$objPHPExcel->setActiveSheetIndex(0)
				
				->setCellValue('A'.$con, 'CHEQUE')
				->setCellValue('C'.$con++, round($sub_total_Cheque,2))


				->setCellValue('A'.$con, 'ONLINE')
				->setCellValue('C'.$con++, round($sub_total_OnlineTransfer,2))
				
								
				->setCellValue('A'.$con, 'TOTAL COLLECTION')
				->setCellValue('C'.$con, round($grant_total_amount,2));
				cellColorExcel('A'.$con.':C'.$con++,'e0e0e0', $objPHPExcel);
				$con=$con+2;
				
				}
		//SUMMARY DETAILS====================================================
	
	
		cellColorExcel('A'.$con.':AC'.$con,'ecf0f5', $objPHPExcel);
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':AB'.$con);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getFont()->setBold(true)
                                ->setName('Calibri')
                                ->setSize(16)
                                ->getColor()->setRGB('ed154b');
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, strtoupper($Records->FeildValue));
				
					
		$shift=$Records->id_attribute_shift;
		$shiftSummary=$Records->id_attribute_shift;
		$mstoutlet=$Records->id_mst_outlet;
		$checkshift =$shiftSummary;
		$sub_total_Cash ='';
		$sub_total_BillOnHold='';
		$sub_total_RoomTo='';
		$sub_total_Card ='';
		$sub_total_Company='';
		$sub_total_Cheque ='';
		$sub_total_Upi ='';
		$sub_total_OnlineTransfer ='';
		$grant_total_amount ='';
	$sub_total_Card=array();
		$sub_total_Company=array();
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'S:No.')
			->setCellValue('B'.$con, 'Outlet')
			->setCellValue('C'.$con, 'Bill No')
			->setCellValue('D'.$con, 'Bill Date')
			->setCellValue('E'.$con, 'Room/Table/Pax')
			->setCellValue('F'.$con, 'Particulars')				
			->setCellValue('G'.$con, 'Amount')				
			->setCellValue('H'.$con, 'Discount')
			->setCellValue('I'.$con, 'Net Amount')				
			->setCellValue('J'.$con, 'sgst')
			->setCellValue('K'.$con, 'cgst')
			->setCellValue('M'.$con, 'igst')
			->setCellValue('L'.$con, 'cess')
			->setCellValue('N'.$con, 'vat')
			->setCellValue('O'.$con, 'surcharge')
			
			->setCellValue('P'.$con, 'Service Charge')
			->setCellValue('Q'.$con, 'Round')
			->setCellValue('R'.$con, 'Total Amount')
			->setCellValue('S'.$con, 'User ID')
			->setCellValue('T'.$con, 'Time')
			//->setCellValue('U'.$con, 'Remark')
			->setCellValue('u'.$con, 'Cash')
			->setCellValue('v'.$con, 'Card')
			->setCellValue('w'.$con, 'Company')
			->setCellValue('x'.$con, 'Cheque')
			->setCellValue('Y'.$con, 'ONLINE')
			->setCellValue('Z'.$con, 'BILLONHOLD')
			->setCellValue('AA'.$con, 'ROOMTO')
			->setCellValue('AB'.$con, 'UPI')
			->setCellValue('AC'.$con, 'REMARK');									

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':AC'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':AC'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$con++;
		}
if($Records->id_mst_outlet!=$mstoutlet2){//Outlet Split


		
		if($count2>1){
			$con=$con+1;
			cellColorExcel('C'.$con.':AC'.$con,'e0e0e0', $objPHPExcel);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':AC'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill: '.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetSubTotalItem-$OutLetDiscount,2))
				->setCellValue('J'.$con, round($OutLetSGST,2))
				->setCellValue('K'.$con, round($OutLetCGST,2))
				->setCellValue('L'.$con, round($OutLetIGST,2))
				->setCellValue('M'.$con, round($OutLetCESS,2))
				->setCellValue('N'.$con, round($OutLetVAT,2))
				->setCellValue('O'.$con, round($OutLetSurcharge,2))
				->setCellValue('P'.$con, round($OutLetOtherCharges,2))
				->setCellValue('Q'.$con, round($OutLetRoundOffAmount,2))
				->setCellValue('R'.$con, round($OutLetGrantTotalAmount,2))
				
				->setCellValue('U'.$con, round($OutLetCash,2))
				->setCellValue('V'.$con, round($OutLetCard,2))
				->setCellValue('W'.$con, round($OutLetCompany,2))
				->setCellValue('X'.$con, round($OutLetCheque,2))
				
				->setCellValue('Y'.$con, round($OutLetOnlineTransfer,2))
				->setCellValue('Z'.$con, round($OutLetBillOnHold,2))
				->setCellValue('AA'.$con, round($OutLetRoomTo,2))
				->setCellValue('AB'.$con++, round($OutLetUpi,2));
			$con=$con+3;
			$OutLetSubTotalItem=0;
			$TotalPax=0;
			$TotalBillCount=0;
			$OutLetDiscount= 0;
			$OutLetNetAmountItems =0;
			$OutLetSGST =0;
			$OutLetCGST =0;
			$OutLetIGST =0;
			$OutLetCESS =0;
			$OutLetVAT =0;
			$OutLetSurcharge =0;
			$OutLetOtherCharges =0;
			$OutLetRoundOffAmount =0;
			$OutLetGrantTotalAmount =0;
			$OutLetCash =0;
			$OutLetCard =0;
			$OutLetCompany =0;
			$OutLetCheque =0;
			$OutLetOnlineTransfer =0;
			$OutLetBillOnHold=0;
			$OutLetRoomTo=0;
			$OutLetUpi=0;
		}
	}
	
	$SqlAttrbuteTable =  mysqli_query($connNew,"SELECT * FROM `".TBL_ATTRIBUTES."` where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$Records->id_attribute_table."'");
	
    $resultAttrbuteTable = mysqli_fetch_object($SqlAttrbuteTable);
	
	
	
	
	
	$RoomNO='';
	$guestName='';
	 if($Records->doc_type=='25'){
		 
$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$Records->id_fo_bill."'");
	$RoomNO= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
	$id_mst_guest= selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$Records->id_fo_bill."'");
	$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
	// $RoomAndTable	=	$guestName.' ('.$RoomNO.')';
		 //$RoomAndTable	='Lan';
		 
$RoomAndTable	=	$RoomNO; 

		 }else{
			  if($Records->id_fo_bill>0){
			$id_mst_room_no_allocation	=	selectColumn(FO_RESERVATIONS_DETAILS,'id_mst_room_no_allocation'," WHERE `id_fo_bill` = '".$Records->id_fo_bill."'");
			$RoomNO= selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$id_mst_room_no_allocation."'");
			$id_mst_guest= selectColumn('fo_folio','id_mst_guest'," WHERE `id` = '".$Records->id_fo_bill."'");
			$guestName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$id_mst_guest."'");
			//$RoomAndTable	=	$guestName.' ('.$RoomNO.')';
				  
			 }else{
			 //$RoomAndTable	=	 $resultAttrbuteTable->field_value.' ('.$Records->pax.')';
			 }
		 
			if($RoomNO=='' && $resultAttrbuteTable->field_value!=''){
				$Slash	='';
			}elseif($RoomNO!='' && $resultAttrbuteTable->field_value=='' && $Records->pax=='0'){
				$Slash	='';
			}else{
				$Slash	=' / ';
			}
			 $RoomAndTable	=	$RoomNO.$Slash.($resultAttrbuteTable->field_value!=''?$resultAttrbuteTable->field_value:'').($Records->pax>0? ' / ('.$Records->pax.')':''); 
			 }
			 
			//$RoomAndTable	=	$RoomNO.$resultAttrbuteTable->field_value.' / ('.$Records->pax.')'; 
			 
	if($Records->id_company>0 && $Records->Company>0){
	 $company	=	selectColumn(MST_COMPANY,'name','WHERE id="'.$Records->id_company.'" AND id_shop="'.$_SESSION['shop'].'" ');
	}else{ $company='';}
	 if($Records->id_charges_master>0){
	$bank	=	  selectColumn(TBL_CHARGES,'name','WHERE id="'.$Records->id_charges_master.'" AND id_shop="'.$_SESSION['shop'].'" ');
	 }else{ $bank='';
		 }
	 $remarks=$bank.' '.$company.' '.($Records->remark!=''?'('.$Records->remark.')':'');	
	
	//test
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, $InCount)
				->setCellValue('B'.$con, $Records->outlet_Name)
				->setCellValue('C'.$con, $Records->mdoc_no)
				->setCellValue('D'.$con, date('d-m-Y',strtotime($Records->bill_date_created)))  //->setCellValue('D'.$con, date('d-m-Y',strtotime($Records->CreatedDate)))
				->setCellValue('E'.$con, $RoomAndTable)
				->setCellValue('F'.$con, $guestName==''?$Records->outlet_Name:$guestName)
				->setCellValue('G'.$con, $Records->sub_total_items)
				->setCellValue('H'.$con, $Records->Discount)
				->setCellValue('I'.$con, $Records->sub_total_items-$Records->Discount)
				->setCellValue('J'.$con, $Records->sgst+$Records->sc_sgst)
				->setCellValue('K'.$con, $Records->cgst+$Records->sc_cgst)
				->setCellValue('L'.$con, $Records->igst)
				->setCellValue('M'.$con, $Records->cess)
				->setCellValue('N'.$con, $Records->vat)
				->setCellValue('O'.$con, $Records->surcharge)
				->setCellValue('P'.$con, $Records->OtherCharges+$Records->sc_charges_net_amount)
				->setCellValue('Q'.$con, $Records->round_off_amount)
				->setCellValue('R'.$con, $Records->grant_total_amount)
				->setCellValue('S'.$con, $Records->UserName)
				->setCellValue('T'.$con, $Records->Time)
				->setCellValue('U'.$con, $Records->Cash)
				->setCellValue('V'.$con, $Records->Card)
				->setCellValue('W'.$con, $Records->Company)
				->setCellValue('X'.$con, $Records->Cheque)
				->setCellValue('Y'.$con, $Records->OnlineTransfer)
				->setCellValue('Z'.$con, $Records->BillOnHold)
				->setCellValue('AA'.$con, $Records->RoomTo)
				->setCellValue('AB'.$con, $Records->Upi)
				->setCellValue('AC'.$con, $remarks);
				//->setCellValue('Z'.$con, $Records->FeildValue)
				//->setCellValue('AA'.$con, $Records->CreatedDate)
				//->setCellValue('AA'.$con, $Records->CreatedDate);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':AC'.$con)->applyFromArray($styleThinBlackBorderOutline);
		
		
		//SUMMARY DETAILS====================================================
		
		//$sub_total_Card +=$Records->Card;
		//$sub_total_Company +=$Records->Company;
		
		$sub_total_Cash +=$Records->Cash;
		if($Records->Card>0){
		//$sub_total_Card[selectColumn(TBL_CHARGES,'name','WHERE id="'.$Records->id_charges_master.'" AND id_shop="'.$_SESSION['shop'].'" ')] +=$Records->Card;
			//==============Multi Card Payment==================================
	$pos_AccountDoc2="SELECT amount,id_charges_master FROM pos_purch_pay AS A  WHERE id_purch='".$Records->id_purch_payment."' AND payment_mode='CARD'";	
	$resultpos_AccountDoc2 = mysqli_query($connNew, $pos_AccountDoc2); 
	while($posDocResultpos_AccountDoc2 = mysqli_fetch_object($resultpos_AccountDoc2)){
	
	//===============Multi Card Payment=================================
	$sub_total_Card[selectColumn(TBL_CHARGES,'name','WHERE id="'.$posDocResultpos_AccountDoc2->id_charges_master.'" AND id_shop="'.$_SESSION['shop'].'" ')] +=$posDocResultpos_AccountDoc2->amount;
	}
		}
		if($Records->Company>0){
		//$sub_total_Company[$company] +=$Records->Company;
				//==============footer_remarks==================================
	$pos_AccountDoc3="SELECT amount,id_charges_master,id_company FROM pos_purch_pay AS A  WHERE id_purch='".$Records->id_purch_payment."' and payment_mode='COMPANY'";	
	$resultpos_AccountDoc3 = mysqli_query($connNew, $pos_AccountDoc3); 
	while($posDocResultpos_AccountDoc3 = mysqli_fetch_object($resultpos_AccountDoc3)){
	
	//===============footer_remarks=================================	
		 $company	=	selectColumn(MST_COMPANY,'name','WHERE id="'.$posDocResultpos_AccountDoc3->id_company.'" AND id_shop="'.$_SESSION['shop'].'" ');
		$sub_total_Company[$company] +=$posDocResultpos_AccountDoc3->amount;
	}
		}
		
		$sub_total_Cheque +=$Records->Cheque;
		$sub_total_OnlineTransfer +=$Records->OnlineTransfer;
		$grant_total_amount +=$Records->grant_total_amount;
		$sub_total_BillOnHold +=$Records->BillOnHold;
		$sub_total_RoomTo +=$Records->RoomTo;
		$sub_total_Upi +=$Records->Upi;
		
		//outlet SubTotal
		$OutLetSubTotalItem +=$Records->sub_total_items;
		$OutLetDiscount +=$Records->Discount;
		$OutLetNetAmountItems +=$Records->net_amount_items;
		$OutLetSGST +=($Records->sgst+$Records->sc_sgst);
		$OutLetCGST +=($Records->cgst+$Records->sc_cgst);
		$OutLetIGST +=$Records->igst;
		$OutLetCESS +=$Records->cess;
		$OutLetVAT +=$Records->vat;
		$OutLetSurcharge +=$Records->surcharge;
		$OutLetOtherCharges +=($Records->OtherCharges+$Records->sc_charges_net_amount);
		$OutLetRoundOffAmount +=$Records->round_off_amount;
		$OutLetGrantTotalAmount +=$Records->grant_total_amount;
		$OutLetCash +=$Records->Cash;
		$OutLetCard +=$Records->Card;
		$OutLetCompany +=$Records->Company;
		$OutLetCheque +=$Records->Cheque;
		$OutLetOnlineTransfer +=$Records->OnlineTransfer;
		$OutLetBillOnHold +=$Records->BillOnHold;
		$OutLetRoomTo +=$Records->RoomTo;
		$OutLetUpi +=$Records->Upi;
		$TotalPax +=$Records->pax;
				
	    //outlet SubTotal
		
		if($Records->id_attribute_shift==$shift2){
				$shift2=$Records->id_attribute_shift;				
				//$mstoutlet2=$Records->id_mst_outlet;
	}else{
			$count2=1;
			$shift2=$Records->id_attribute_shift;
			//$mstoutlet2=$Records->id_mst_outlet;
			
		}
		$mstoutlet2=$Records->id_mst_outlet;
		if($TotalNumberOfRows==$InCount){//LAST RECORD
			
			$con=$con+2;
			cellColorExcel('C'.$con.':AC'.$con,'e0e0e0', $objPHPExcel);
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':AC'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$TotalBillCount=$TotalBillCount+1;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill:'.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetSubTotalItem-$OutLetDiscount,2))
				->setCellValue('J'.$con, round($OutLetSGST,2))
				->setCellValue('K'.$con, round($OutLetCGST,2))
				->setCellValue('L'.$con, round($OutLetIGST,2))
				->setCellValue('M'.$con, round($OutLetCESS,2))
				->setCellValue('N'.$con, round($OutLetVAT,2))
				->setCellValue('O'.$con, round($OutLetSurcharge,2))
				->setCellValue('P'.$con, round($OutLetOtherCharges,2))
				->setCellValue('Q'.$con, round($OutLetRoundOffAmount,2))
				->setCellValue('R'.$con, round($OutLetGrantTotalAmount,2))
				
				->setCellValue('U'.$con, round($OutLetCash,2))
				->setCellValue('V'.$con, round($OutLetCard,2))
				->setCellValue('W'.$con, round($OutLetCompany,2))
				->setCellValue('X'.$con, round($OutLetCheque,2))
				
				->setCellValue('Y'.$con, round($OutLetOnlineTransfer,2))
				->setCellValue('Z'.$con, round($OutLetBillOnHold,2))
				
				->setCellValue('AA'.$con, round($OutLetRoomTo,2))
				->setCellValue('AB'.$con++, round($OutLetUpi,2));
				
			
			$TotalBillCount=0;
			$TotalPax=0;
			$OutLetSubTotalItem=0;
			$OutLetDiscount= 0;
			$OutLetNetAmountItems =0;
			$OutLetSGST =0;
			$OutLetCGST =0;
			$OutLetIGST =0;
			$OutLetCESS =0;
			$OutLetVAT =0;
			$OutLetSurcharge =0;
			$OutLetOtherCharges =0;
			$OutLetRoundOffAmount =0;
			$OutLetGrantTotalAmount =0;
			$OutLetCash =0;
			$OutLetCard =0;
			$OutLetCompany =0;
			$OutLetCheque =0;
			$OutLetOnlineTransfer =0;
			$OutLetBillOnHold=0;
			$OutLetRoomTo=0;
			$OutLetUpi=0;
			
			$con=$con+2;
			cellColorExcel('A'.$con.':B'.$con,'e0e0e0', $objPHPExcel);
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, 'SUMMARY DETAILS')
				
				->setCellValue('A'.$con, 'CASH')
				->setCellValue('C'.$con++, round($sub_total_Cash,2));
				
				$objPHPExcel->setActiveSheetIndex(0)		
				->setCellValue('A'.$con, 'CARD');
				
				
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'CARD');
				foreach($sub_total_Card as $k=>$checklist){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$con, $k)
				->setCellValue('C'.$con++, round($checklist,2));
				
				}
						
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'UPI')
				->setCellValue('C'.$con++, round($sub_total_Upi,2));
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'BILLONHOLD')
				->setCellValue('C'.$con++, round($sub_total_BillOnHold,2));
				
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'ROOMTO')
				->setCellValue('C'.$con++, round($sub_total_RoomTo,2));		
		
		$objPHPExcel->setActiveSheetIndex(0)		
				->setCellValue('A'.$con, 'COMPANY');
				
		
				foreach($sub_total_Company as $k2=>$checklist2){
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B'.$con, $k2)
				->setCellValue('C'.$con++, round($checklist2,2));
				
				}		
				
			$objPHPExcel->setActiveSheetIndex(0)
				
				->setCellValue('A'.$con, 'CHEQUE')
				->setCellValue('C'.$con++, round($sub_total_Cheque,2))


				->setCellValue('A'.$con, 'ONLINE')
				->setCellValue('C'.$con++, round($sub_total_OnlineTransfer,2))
				
								
				->setCellValue('A'.$con, 'TOTAL COLLECTION')
				->setCellValue('C'.$con, round($grant_total_amount,2));
				cellColorExcel('A'.$con.':B'.$con++,'e0e0e0', $objPHPExcel);
			
			}
		
		
			
		
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


if($cronSet=='1'){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/mailattach/'.$pdfName.'.xls');$objPHPExcel='';//exit;
}else{

	$filename=	'SettlementReport'.date('d-M-Y').'.xls';
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
																												 
}
	
function checkPaymentStatus($pos_purch_id){	
	global $connNew;
	
	 $sql = "SELECT *  from
(
select pp.*,

max(pp.grant_total_amount) as amount_need_to_pay, 
sum(ppp.amount) as amount_paid,
(case 
when max(pp.cancelled)=1 then 'cancelled'
when max(pp.grant_total_amount)=sum(ppp.amount) then 'Settled'
when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)<>0 then 'Partial'
when max(pp.grant_total_amount)<>sum(ppp.amount) and sum(ppp.amount)=0 then 'Pending'
when max(pp.grant_total_amount)=0 and sum(ppp.amount) is NULL then 'Pending'
when max(pp.grant_total_amount)>0 and sum(ppp.amount) is NULL then 'Pending'
end) as payment_status
from pos_purch  pp 
left join
pos_purch_pay ppp
on
ppp.id_purch=pp.id  where pos_bill_type=2 and  pp.id='".$pos_purch_id."'
group by
pp.id ORDER BY last_modified desc
)as managekotlist WHERE id!=0 AND doc_type=21 ".$statuscase." 
";
//last_modified


	
	
	$resultPosPurch = mysqli_query($connNew, $sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
		return $posPurchResult->payment_status;

}	
function checkKOTStatus($kot_id){	
	global $connNew;
	
	 $sql = "SELECT *  from
( select pp.*,  
	   (case  when COALESCE(pp.cancelled)=1 then 'cancelled'
	   		  when COALESCE(ppp.qty-ppp.adj_qty)>0 then 'Pending'
	          when COALESCE(ppp.qty-ppp.adj_qty)=0 then 'Billed' end) as kot_status
 
 from pos_purch pp left join pos_purch_details ppp on ppp.id_pos_purch=pp.id 
 where id_shop= '".addslashes($_SESSION['shop'])."' AND pp.pos_bill_type=1 AND pp.doc_type=22 and  pp.id='".$kot_id."'
 $searchDocumentType 
 group by pp.id ORDER BY pp.`last_modified` desc
 
 )as managekotlist WHERE id!=0 ".$statuscase." 
";
//last_modified


	
	
	$resultPosPurch = mysqli_query($connNew, $sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
		return $posPurchResult->kot_status;

}


function posSalesRegisterReport($Date,$id_outlet,$id_shift,$objPHPExcel,$connNew,$id_shop,$cronSet,$pdfName){
	

	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($Date != ''){
		$DateExplode = explode(' to ',$Date);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
		$SqlConn .= " AND p.`date_created` BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
			
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
			->setCellValue('L'.$con, 'Surcharge')
			->setCellValue('M'.$con, 'GSTIN')
			->setCellValue('N'.$con, 'State')
			->setCellValue('O'.$con, 'Place of Supply')
			->setCellValue('P'.$con, 'GST Type')
			->setCellValue('Q'.$con, 'Outlet');	
						
	
$con++;
$SalesRegisterArray=array();
  $SQLSalesReportPayment="select p.id ,p.id_attribute_shift ,

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
    
    case when (payment_mode='CASH' ) and IFNULL(amount,0)>0 then 'Cash Sales' 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then 'Bill On Hold'
	when payment_mode='CARD' and IFNULL(amount,0)>0 then id_charges_master
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'Cash Sales'
    when payment_mode='UPI' and IFNULL(amount,0)>0 then remark
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then id_charges_master  	  
    else 0 end
    as payment_remarks ,
	
	
	
	
	case when payment_mode='COMPANY' and IFNULL(amount,0)>0 then id_company
    else 0 end
    as id_company ,
	pp.id_fo_bill ,
	
	
	case when (payment_mode='CASH') and IFNULL(amount,0)>0 then amount 
	when payment_mode='BIllONHOLD' and IFNULL(amount,0)>0 then amount
	when payment_mode='CARD' and IFNULL(amount,0)>0 then amount
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then amount
    when payment_mode='UPI' and IFNULL(amount,0)>0 then amount
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then amount
    when payment_mode='COMPANY' and IFNULL(amount,0)>0 then amount
	when payment_mode='ROOMTO' and IFNULL(amount,0)>0 then amount
	
    else 0 end
    as dramount ,

comp.name as 'outlet_Name' ,usr.name ,time as Time ,p.mdoc_no ,p.id_mst_outlet ,p.sub_total_items ,p.sgst_total_items ,p.cgst_total_items ,p.igst_total_items ,p.cess_total_items ,p.vat_total_items ,p.surcharge_total_items ,p.round_off_amount ,p.net_amount_items ,p.grant_total_amount ,p.others_charges_net_amount ,p.discount_amount_additional ,p.total_discount_items ,

case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH ,
case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD ,
case when payment_mode='BIllONHOLD' then IFNULL(amount,0) else null end as BIllONHOLD ,
case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE ,
case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI ,
case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER ,
case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY ,
case when payment_mode='ROOMTO' then IFNULL(amount,0) else null end as ROOMTO ,


att.field_value ,DATE(p.date_created) as date_created ,p.pax ,p.id_attribute_table,p.discount_amount_additional,p.id_doc_type_configuration
 from pos_purch_pay pp 
INNER JOIN pos_purch p on p.id = pp.id_purch 
INNER JOIN mst_attributes att on att.id = p.id_attribute_shift 
INNER JOIN mst_outlets as comp on comp.id = p.id_mst_outlet 
INNER JOIN mst_users usr on usr.id=pp.id_mst_user_created_by WHERE p.id!=0 and pp.amount>0 and cancelled!=1 $SqlConn";

//echo '=================>'.$SQLSalesReportPayment;
//die;
$querySalesReportPayment = mysqli_query($connNew,$SQLSalesReportPayment);
$NumberOfRowsSalesReportPayment = mysqli_num_rows($querySalesReportPayment);

$ics=1;
while($RecordsSalesReportPayment	   =	mysqli_fetch_object($querySalesReportPayment)){
	
	
		$doc_type = selectColumn(TBL_PURCH,'doc_type','WHERE id="'.$RecordsSalesReportPayment->id.'" AND id_shop="'.$id_shop.'" ');									
	$Payment	=	$RecordsSalesReportPayment->payment_type.$ics++;
	if($RecordsSalesReportPayment->id_company>0){
		$payment_remarks = selectColumn(MST_COMPANY,'name','WHERE id="'.$RecordsSalesReportPayment->id_company.'" AND id_shop="'.$id_shop.'" ');
	}else{
		$payment_remarks=$RecordsSalesReportPayment->payment_remarks;
		}
		
		
	if($RecordsSalesReportPayment->payment_type=='CARD' || $RecordsSalesReportPayment->payment_type=='ONLINETRANSFER'){
		$payment_remarks=selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReportPayment->payment_remarks.'" AND id_shop="'.$id_shop.'" ');
		}
		if($RecordsSalesReportPayment->payment_type=='ROOMTO'){
			
			$id_reservations = selectColumn(FO_BILL,'id_reservations'," where  id_mst_shops='".addslashes($id_shop)."' and id= '".addslashes($RecordsSalesReportPayment->id_fo_bill)."' ",' ');
											
			$sqlOrderDetail = mysqli_query($connNew,"Select * from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($id_reservations)."'   group by id_mst_room_no_allocation ");
			
			
			while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){										
			
			$guestFirstName	=	selectColumn(TBL_GUEST,'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$guestLastName	=	selectColumn(TBL_GUEST,'last_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
				$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
			$guestTitle=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$id_mst_attributes_title."'  AND table_name ='".'title'."' ");
				
			$roomNumber = selectColumn(TBL_ROOMNO,'room_no'," WHERE `id` = '".$rowOrderDetail->id_mst_room_no_allocation."'");
			
			$booking_no=	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".addslashes($id_reservations)."' ");					
													
													

 		$RoomToLable = $guestTitle.$guestFirstName.' '.$guestLastName;
											
		$payment_remarks= $RoomToLable;//.'----ROOMTO';
		}
		$payTo='ROOMTO';
		}	else{
			$payTo='CASH';
			}
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReportPayment->id_doc_type_configuration.'" AND id_shop="'.$id_shop.'" ');
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type]['payment_type']=$RecordsSalesReportPayment->payment_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['date_created']=$RecordsSalesReportPayment->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['mdoc_no']=$RecordsSalesReportPayment->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['doc_type']=$doc_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['narration']=$RecordsSalesReportPayment->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['Account_Name']=$payment_remarks;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['dramount']=$RecordsSalesReportPayment->dramount;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReportPayment->id][$RecordsSalesReportPayment->mdoc_no][$RecordsSalesReportPayment->payment_type][$Payment]['ptype']='Dr';
	
	
	
	
}
 $SQLSalesReport1="select p.id ,p.id_attribute_shift ,p.id_doc_type_configuration,
sum(ppd.item_sgst_amount) as sub_sgst_amount,
sum(ppd.item_cgst_amount) as sub_cgst_amount,
sum(ppd.item_vat_amount) as sub_vat_amount,
sum(ppd.item_surcharge_amount) as sub_surcharge_amount,p.sc_sgst,p.sc_cgst,p.sc_charges_net_amount,
sum((ppd.rate_per_main_unit*ppd.qty)) as sub_item_amount,p.id_mst_charges_discounts,
p.doc_type,  comp.name as 'outlet_Name' ,usr.name ,p.mdoc_no ,p.id_mst_outlet ,p.sub_total_items ,p.sgst_total_items ,p.cgst_total_items ,p.igst_total_items ,p.cess_total_items ,p.vat_total_items ,p.surcharge_total_items ,p.round_off_amount ,p.net_amount_items ,p.grant_total_amount ,p.others_charges_net_amount ,p.discount_amount_additional ,p.total_discount_items ,att.field_value ,DATE(p.date_created) as date_created ,p.pax ,p.id_attribute_table,
ppd.id_mst_charges_sales_local,
ppd.id_mst_charges_sgst,
ppd.id_mst_charges_cgst,
ppd.id_mst_charges_igst,
ppd.id_mst_charges_cess,
ppd.id_mst_charges_vat,
ppd.id_mst_charges_surcharge,
ppd.item_sgst_percent,
ppd.item_cgst_percent

from pos_purch p 
INNER JOIN pos_purch_details ppd on ppd.id_pos_purch = p.id 
INNER JOIN mst_attributes att on att.id = p.id_attribute_shift 
INNER JOIN mst_outlets as comp on comp.id = p.id_mst_outlet 
INNER JOIN mst_users usr on usr.id=p.id_mst_user_created_by WHERE p.id!=0 and cancelled!=1 $SqlConn2  group by ppd.id_pos_purch,ppd.id_mst_charges_sales_local";


	$querySalesReport = mysqli_query($connNew,$SQLSalesReport1);
$NumberOfRowsSalesReport = mysqli_num_rows($querySalesReport);

$enable_split_bill_by_sales_account_groupLoop=2;
while($RecordsSalesReport	   =	mysqli_fetch_object($querySalesReport)){

	
	//================================================
	/*$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);*/
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$RecordsSalesReport->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	//echo '<br>==============================>'.$RecordsSalesReport->id_mst_charges_sales_local.$RecordsSalesReport->mdoc_no.'------'.$incs++.'====='.$RecordsSalesReport->id;
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	//================================================
	

		

$array = $checkArray;
$searchObject = $RecordsSalesReport->mdoc_no;
$keys = array_keys($array, $searchObject);




		$checkArray[$RecordsSalesReport->mdoc_no]=$RecordsSalesReport->mdoc_no;
		//debugData($y);
		if($enable_split_bill_by_sales_account_group==1){
			if($keys[0]==$RecordsSalesReport->mdoc_no){
		$enable_split_bill_by_sales_account_groupLoop=2;	
			}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
		}else{
			$enable_split_bill_by_sales_account_groupLoop=1;
			}
			
			
			
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReport->id_doc_type_configuration.'" AND id_shop="'.$id_shop.'" ');
	
	if($RecordsSalesReport->id_mst_charges_sgst>0  || $RecordsSalesReport->id_mst_charges_vat>0){
			
	
	$Account_Name_local = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sales_local.'"  ');
	$taxMethod_sales_local='id_mst_charges_sales_local';
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sales_local;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['Account_Name']=$Account_Name_local;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['cramount']=$RecordsSalesReport->sub_item_amount;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sales_local]['assessable_value']=$RecordsSalesReport->sub_item_amount;
	//Sales At Local=======================
	
	}
	
	
	
	//Addition Discount--------------------------------------
	if(($RecordsSalesReport->discount_amount_additional>0) ){
			$taxMethod_discount='discount_amount_additional';
	
											
	$Account_Name = 'Discount Coupon';
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->discount_amount_additional;
	}
	//Addition Discount----End----------------------------------
	
	
	//total_discount_items Discount--------------------------------------
	if(($RecordsSalesReport->total_discount_items>0) ){
			$taxMethod_discount='total_discount_items';
	
											
	//$Account_Name = 'Discount Coupon';
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_discounts.'"  ');
	if($Account_Name==''){$Account_Name='Discount Coupon';}
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_discount][$taxMethod_discount]['dramount']=$RecordsSalesReport->total_discount_items;
	}
	
	
	if($enable_split_bill_by_sales_account_groupLoop=='1' ){
	//SERVICE CHarge =================================
	
	
			//("Service Charges 10% :");
			
	if($RecordsSalesReport->sc_charges_net_amount>0 && $checkServiceChargeAdded!=$RecordsSalesReport->id){
			$taxMethod_sgst='sc_charges_net_amount';
	
												
	
	$id_service_charge = selectColumn(TBL_OUTLETS,'id_service_charge','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$id_shop.'" ');
	$Account_Name =selectColumn(TBL_CHARGES,'name','WHERE id="'.$id_service_charge.'"  ');
	
	//'Service Charges 10% '; 
	
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$RecordsSalesReport->sc_charges_net_amount;
	
	$checkServiceChargeAdded=$RecordsSalesReport->id;
	}		
	
	if($RecordsSalesReport->sc_sgst){
			$taxMethod_sgst='sc_sgst';
	
												
	
	$Account_Name = 'Output SGST @ 2.5%';
	//	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$RecordsSalesReport->sc_sgst;
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->sc_cgst>0){
			$taxMethod_cgst='sc_cgst';
	
											
	$Account_Name = 'Output CGST @ 2.5%';
		//$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=$RecordsSalesReport->sc_cgst;
	}
	
	//SERVICE CHarge =================================
	
	
	if($RecordsSalesReport->id_mst_charges_sgst>0){
			$taxMethod_sgst='id_mst_charges_sgst';
	
												
	
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_sgst.'"  ');
	
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['id_mst_charges_sgst']=$RecordsSalesReport->id_mst_charges_sgst;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_sgst]['cramount']=$RecordsSalesReport->sub_sgst_amount;
	}
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_cgst>0){
			$taxMethod_cgst='id_mst_charges_cgst';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cgst.'"  ');
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cgst]['cramount']=$RecordsSalesReport->sub_cgst_amount;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	if($RecordsSalesReport->id_mst_charges_igst>0){  //igst===============
			$taxMethod_igst='id_mst_charges_igst';
	
											
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['id_mst_charges_igst']=$RecordsSalesReport->id_mst_charges_igst;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_igst]['ptype']='Cr';
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_cess>0){ //cess===========================
			$taxMethod_cess='id_mst_charges_cess';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_cess.'"  ');
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['voucher_type']=$voucher_type;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['id_mst_charges_cess']=$RecordsSalesReport->id_mst_charges_cess;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_cess]['ptype']='Cr';
	
	}
	
	

	if($RecordsSalesReport->id_mst_charges_vat>0){ //Vat==================================
			$taxMethod_vat='id_mst_charges_vat';
	
				$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_vat.'"  ');	
				
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['voucher_type']=$voucher_type;			
										
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['ptype']='Cr';
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_vat]['cramount']=$RecordsSalesReport->sub_vat_amount;
	
	
	
	}
	
	
	if($RecordsSalesReport->id_mst_charges_surcharge>0){ //_surcharge==========================
			$taxMethod_surcharge='id_mst_charges_surcharge';
	
											
	$Account_Name = selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReport->id_mst_charges_surcharge.'"  ');
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['voucher_type']=$voucher_type;
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['id_mst_charges_surcharge']=$RecordsSalesReport->id_mst_charges_surcharge;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local][$taxMethod_surcharge]['cramount']=$RecordsSalesReport->sub_surcharge_amount;
	if($enable_split_bill_by_sales_account_group==1){
			
				$enable_split_bill_by_sales_account_groupLoop=2;
			}
	}
	
$varNet_items= ($RecordsSalesReport->sub_total_items+$RecordsSalesReport->sgst_total_items+$RecordsSalesReport->cgst_total_items+
	$RecordsSalesReport->igst_total_items+$RecordsSalesReport->sc_charges_net_amount+$RecordsSalesReport->sc_igst+$RecordsSalesReport->sc_sgst+
	$RecordsSalesReport->sc_cgst+$RecordsSalesReport->vat_total_items+$RecordsSalesReport->surcharge_total_items+$RecordsSalesReport->cess_total_items
	-$RecordsSalesReport->total_discount_items);

	$round_Off	=	round((round($RecordsSalesReport->grant_total_amount,0)-$varNet_items),2);
	//$RecordsSalesReport->grant_total_amount-$RecordsSalesReport->net_amount_items;
	
	if($round_Off>0 && $round_Off!=0.00){
			$taxMethod_round_off='round_off_amount';
	
							
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Cr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['cramount']=$round_Off;
	}
	}
	if($round_Off<0 && $round_Off!=0.00 ){
			$taxMethod_round_off='round_off_amount';
	
							
											
	$Account_Name = 'Round Off';
	
	//$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_sales_local']=$RecordsSalesReport->id_mst_charges_sales_local.$taxMethod;
	
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['voucher_type']=$voucher_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['date_created']=$RecordsSalesReport->date_created;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['mdoc_no']=$RecordsSalesReport->mdoc_no;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['doc_type']=$RecordsSalesReport->doc_type;
	
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['narration']=$RecordsSalesReport->field_value;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['Account_Name']=$Account_Name;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['ptype']='Dr';
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$taxMethod_round_off][$taxMethod_round_off]['dramount']= -$round_Off;
	}
	
	/*$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_cgst']=$RecordsSalesReport->id_mst_charges_cgst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_igst']=$RecordsSalesReport->id_mst_charges_igst;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_cess']=$RecordsSalesReport->id_mst_charges_cess;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_vat']=$RecordsSalesReport->id_mst_charges_vat;
	$SalesRegisterArray['Sales Register'][$RecordsSalesReport->id][$RecordsSalesReport->mdoc_no][$RecordsSalesReport->id_mst_charges_sales_local]['id_mst_charges_surcharge']=$RecordsSalesReport->id_mst_charges_surcharge*/;
	
	
	//}
	

	
}
//debugdata($SalesRegisterArray);

//die;
foreach($SalesRegisterArray as $SalesRegisterArraylist=> $SalesRegisterArray1){
	
	
	foreach($SalesRegisterArray1 as $SalesRegisterArraylist1=> $SalesRegisterArray2){
		
		
		foreach($SalesRegisterArray2 as $SalesRegisterArraylist2=> $SalesRegisterArray3){
			//echo '==================='.$VoucherNo=$SalesRegisterArraylist2;
			//debugdata($SalesRegisterArray2);
			
			foreach($SalesRegisterArray3 as $SalesRegisterArraylist3=> $SalesRegisterArray4){
				//debugdata($SalesRegisterArray4);
				foreach($SalesRegisterArray4 as $SalesRegisterArraylist4=> $SalesRegisterArray5){
					
					
					if($SalesRegisterArray5['dramount']>0 || $SalesRegisterArray5['cramount']>0){   //Amount Debit or Credit >0
					$InvDate=date('d-m-Y',strtotime($SalesRegisterArray5['date_created']));
					
					
					
					//PHPExcel_Style_NumberFormat::toFormattedString(date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])), 'dd-mmm-yyyy');
					
			//$InvDate = date($format = "Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($SalesRegisterArray5['date_created']));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':L'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, 'Sales')
				->setCellValue('B'.$con, $SalesRegisterArray5['mdoc_no']);
			$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('C'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))); //trim(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					/*->setCellValueByColumnAndRow(2, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(2, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);*/	
				//->setCellValue('C'.$con, $InvDate)//trim(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$con, $SalesRegisterArray5['Account_Name'])
				->setCellValue('E'.$con, $SalesRegisterArray5['narration'])
				->setCellValue('F'.$con, $SalesRegisterArray5['dramount']>0?$SalesRegisterArray5['dramount']:0)

				->setCellValue('G'.$con, $SalesRegisterArray5['cramount']>0?$SalesRegisterArray5['cramount']:0)
				->setCellValue('H'.$con, $SalesRegisterArray5['ptype'])
				->setCellValue('I'.$con, $SalesRegisterArray5['mdoc_no']);
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])));
					/*->setCellValueByColumnAndRow(9, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(9, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);*/
				//->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])))
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$con, $SalesRegisterArray5['assessable_value']>0?$SalesRegisterArray5['assessable_value']:0)
				->setCellValue('L'.$con, '')
				->setCellValue('M'.$con, '')
				->setCellValue('N'.$con, '')
				->setCellValue('O'.$con, '')
				->setCellValue('P'.$con, '')
				->setCellValue('Q'.$con,  $SalesRegisterArray5['voucher_type'])
				;
					$con++;
			
			//debugdata($SalesRegisterArray5);
					}
				}
				
			}
			
		}
		
	}
	
}//die;
$objPHPExcel->getActiveSheet($sheetIndexFive)->getColumnDimension('D')->setWidth(35);
	// Rename worksheet
		/* $objPHPExcel->getSecurity()->setLockWindows(true);
         $objPHPExcel->getSecurity()->setLockStructure(true);
         $objPHPExcel->getSecurity()->setWorkbookPassword("FreeBlocking");
         $objPHPExcel->getActiveSheet()->getProtection()->setPassword('FreeBlocking');
         $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
         // This should be enabled in order to enable any of the following!
         $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
         $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);*/	
		 $objPHPExcel->getActiveSheet()->setTitle('Sales Register');	
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

if($cronSet=='1'){
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save($_SERVER['DOCUMENT_ROOT'].'/mailattach/'.$pdfName.'.xls');$objPHPExcel='';//exit;
}else{



	$filename=	'SalesReport'.date('d-M-Y').'.xls';
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

	}	
	
	//SPLIT SALES GROUP START =======================================================================================


	
	function fetchdataSalesGroupPrint($pos_purch_id_array,$grouparray=0){	
	global $connNew;
	foreach($pos_purch_id_array as $posID){
	//Com
	 $pos_purch_sql="SELECT * FROM ".TBL_PURCH." AS A  WHERE A.id_shop='".$_SESSION['shop']."'  AND id='".$posID."'";
	$resultPosPurch = mysqli_query($connNew, $pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
	
	
	/*$pos_purch_sqlDoc="SELECT max(id) as ids FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE doc_type='".$posPurchResult->id_doc_type_configuration."'";
	$resultPosPurchDoc = mysqli_query($connNew, $pos_purch_sqlDoc); 
	$numRowsDoc = mysqli_num_rows($resultPosPurchDoc);
	$posDocResult = mysqli_fetch_object($resultPosPurchDoc);*/
	
	
	$pos_AccountDoc="SELECT enable_split_bill_by_sales_account_group FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";
	$resultpos_AccountDoc = mysqli_query($connNew, $pos_AccountDoc); 
	$numRowspos_AccountDoc = mysqli_num_rows($resultpos_AccountDoc);
	$posDocResultpos_AccountDoc = mysqli_fetch_object($resultpos_AccountDoc);
	$enable_split_bill_by_sales_account_group	= $posDocResultpos_AccountDoc->enable_split_bill_by_sales_account_group;
	
	//==============footer_remarks==================================
	$pos_AccountDoc2="SELECT footer_remarks FROM ".TBL_DOC_TYPE_CONFIG." AS A  WHERE id='".$posPurchResult->id_doc_type_configuration."'";	
	$resultpos_AccountDoc2 = mysqli_query($connNew, $pos_AccountDoc2); 
	$posDocResultpos_AccountDoc2 = mysqli_fetch_object($resultpos_AccountDoc2);
	$footer_remarks	= $posDocResultpos_AccountDoc2->footer_remarks;
	//===============footer_remarks=================================
	
		 //echo $posPurchResult->id_pos_details_split;
		 //debugData($posPurchResult);
		  checkPaymentStatus($posID);
 $editSql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE  id_pos_purch= '".$posID."'");
while($editrow = mysqli_fetch_object($editSql)){
 $edit_id1 = $editrow->id;
  $string1 .= $edit_id1.',';
}
 $edit_id = rtrim($string1,',');	 
		 
		 $pos_purch_detail_sql="SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$edit_id.")";
		 //$pos_purch_detail_sql="SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$posPurchResult->id_pos_details_split.")";
		$resultPosPurchDerails = mysqli_query($connNew,$pos_purch_detail_sql); 
		$numRows = @mysqli_num_rows($resultPosPurchDerails);
		while($resResult = @mysqli_fetch_object($resultPosPurchDerails)){
			

$id_item_type=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$posPurchResult->id_attribute_steward."'  AND table_name ='".'steward'."' ");
$attribute_table_name=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id`='".$posPurchResult->id_attribute_table."'  AND table_name ='".'table'."' ");
 $ResultKotdocQuerymdoc_no=   array();
$GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$posPurchResult->kot_doc_no."') ";
$KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
	$ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
}
$ip=	implode(',',$ResultKotdocQuerymdoc_no); 

$sgst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_sgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$cgst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_cgst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$igst_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_igst.'" AND id_shop="'.$_SESSION['shop'].'" ');
$cess_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_cess.'" AND id_shop="'.$_SESSION['shop'].'" ');
$vat_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_vat.'" AND id_shop="'.$_SESSION['shop'].'" ');
$surcharge_display_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$resResult->id_mst_charges_surcharge.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$grouparray	= 	 selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local','WHERE id="'.$resResult->id_mst_items.'"');	
			
			$charges_sales_local_alias_name	=	  selectColumn(TBL_CHARGES,'display_alias_name','WHERE id="'.$grouparray.'" AND id_shop="'.$_SESSION['shop'].'" ');	
			$printgroup['print_BillSplit'][$grouparray]['item_id'][]=$resResult->id_mst_items;			
			$printgroup['print_BillSplit'][$grouparray]['item_description'][]=$resResult->item_description;			
			$printgroup['print_BillSplit'][$grouparray]['item_qty'][]=$resResult->qty;	
			$printgroup['print_BillSplit'][$grouparray]['item_rate'][]=$resResult->item_amount;	
			$printgroup['print_BillSplit'][$grouparray]['item_amount'][]=round(($resResult->item_amount*$resResult->qty),2);	
			$printgroup['print_BillSplit'][$grouparray]['item_discount_amount'][]=$resResult->item_discount_amount;	
			$printgroup['print_BillSplit'][$grouparray]['item_discount_percent'][]=$resResult->item_discount_percent;	
			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst'][]=$resResult->item_sgst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst'][]=$resResult->item_cgst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst'][]=$resResult->item_igst_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess'][]=$resResult->item_cess_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat'][]=$resResult->item_vat_amount; 
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge'][]=$resResult->item_surcharge_amount; 

			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst_id'][]=$resResult->id_mst_charges_sgst;			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst_id'][]=$resResult->id_mst_charges_cgst;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst_id'][]=$resResult->id_mst_charges_igst;			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess_id'][]=$resResult->id_mst_charges_cess;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat_id'][]=$resResult->id_mst_charges_vat; 			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge_id'][]=$resResult->id_mst_charges_surcharge; 
			
			//
			$printgroup['print_BillSplit'][$grouparray]['sgst_display_alias_name'][]=$sgst_display_alias_name!=''?$sgst_display_alias_name:'SGST @2.5% ';
			$printgroup['print_BillSplit'][$grouparray]['cgst_display_alias_name'][]=$cgst_display_alias_name!=''?$cgst_display_alias_name:'CGST @2.5%';
			$printgroup['print_BillSplit'][$grouparray]['igst_display_alias_name'][]=$igst_display_alias_name!=''?$igst_display_alias_name:'IGST';
			$printgroup['print_BillSplit'][$grouparray]['cess_display_alias_name'][]=$cess_display_alias_name!=''?$cess_display_alias_name:'CESS';
			$printgroup['print_BillSplit'][$grouparray]['vat_display_alias_name'][]=$vat_display_alias_name!=''?$vat_display_alias_name:'VAT';
			$printgroup['print_BillSplit'][$grouparray]['surcharge_display_alias_name'][]=$surcharge_display_alias_name!=''?$surcharge_display_alias_name:'SURCHARGE';//'cb cess';
			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst_percentage'][]=$resResult->item_sgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst_percentage'][]=$resResult->item_cgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst_percentage'][]=$resResult->item_igst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess_percentage'][]=$resResult->item_cess_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat_percentage'][]=$resResult->item_vat_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge_percentage'][]=$resResult->item_surcharge_percent;
			
			$printgroup['print_BillSplit'][$grouparray]['footer_remarks']=$footer_remarks; 
			$printgroup['print_BillSplit'][$grouparray]['id_mst_outlet']=$posPurchResult->id_mst_outlet;
			$printgroup['print_BillSplit'][$grouparray]['discount_amount_additional']=$posPurchResult->discount_amount_additional;
			$printgroup['print_BillSplit'][$grouparray]['others_charges_net_amount']=$posPurchResult->others_charges_net_amount; 
			
			
			$printgroup['print_BillSplit'][$grouparray]['id_doc_type_configuration']=$posPurchResult->id_doc_type_configuration; 
			$printgroup['print_BillSplit'][$grouparray]['enable_split_bill_by_sales_account_group']=$enable_split_bill_by_sales_account_group;
			$printgroup['print_BillSplit'][$grouparray]['charges_sales_local_alias_name']=$charges_sales_local_alias_name;
			$printgroup['print_BillSplit'][$grouparray]['grouparray']=$grouparray;
			
			$printgroup['print_BillSplit'][$grouparray]['sc_sgst']=$posPurchResult->sc_sgst; 
			$printgroup['print_BillSplit'][$grouparray]['sc_cgst']=$posPurchResult->sc_cgst;
			$printgroup['print_BillSplit'][$grouparray]['sc_reverse']=$posPurchResult->sc_reverse;
			$printgroup['print_BillSplit'][$grouparray]['sc_charges_net_amount']=$posPurchResult->sc_charges_net_amount; 
			
			$printgroup['print_BillSplit'][$grouparray]['id_attribute_steward']=$posPurchResult->id_attribute_steward; 
			$printgroup['print_BillSplit'][$grouparray]['doc_date']=$posPurchResult->doc_date; 
			$printgroup['print_BillSplit'][$grouparray]['doc_no']=$posPurchResult->doc_no; 
			$printgroup['print_BillSplit'][$grouparray]['kot_doc_no']=$ip;//$posPurchResult->kot_doc_no; 
			$printgroup['print_BillSplit'][$grouparray]['mdoc_no']=$posPurchResult->mdoc_no; 
			$printgroup['print_BillSplit'][$grouparray]['id_attribute_table']=$posPurchResult->id_attribute_table;
			$printgroup['print_BillSplit'][$grouparray]['pax']=$posPurchResult->pax;
			$printgroup['print_BillSplit'][$grouparray]['id_purch']=$posPurchResult->id;
			$printgroup['print_BillSplit'][$grouparray]['attribute_table_name']=$attribute_table_name;
			$printgroup['print_BillSplit'][$grouparray]['cancelled']=$posPurchResult->cancelled;
			$printgroup['print_BillSplit'][$grouparray]['date_created']=$posPurchResult->date_created;

			/* calculations---------------------------------------------------------- */
			$printgroup['print_BillSplit'][$grouparray]['sub_total_items']  +=($resResult->item_amount*$resResult->qty);			
			$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']  +=$resResult->item_discount_amount;			
			$printgroup['print_BillSplit'][$grouparray]['net_amount_items'] +=(($resResult->item_amount*$resResult->qty)-$resResult->item_discount_amount);
			
			$printgroup['print_BillSplit'][$grouparray]['sgst_net_amount'] += $resResult->item_sgst_amount;
			$printgroup['print_BillSplit'][$grouparray]['cgst_net_amount'] += $resResult->item_cgst_amount;			
			$printgroup['print_BillSplit'][$grouparray]['igst_net_amount'] += $resResult->item_igst_amount;			
			$printgroup['print_BillSplit'][$grouparray]['cess_net_amount'] += $resResult->item_cess_amount;			
			$printgroup['print_BillSplit'][$grouparray]['vat_net_amount']  += $resResult->item_vat_amount;			
			$printgroup['print_BillSplit'][$grouparray]['surcharge_net_amount'] += $resResult->item_surcharge_amount;
			
			$printgroup['print_BillSplit'][$grouparray]['item_net_sgst_percentage'] +=$resResult->item_sgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_cgst_percentage'] +=$resResult->item_cgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_igst_percentage'] +=$resResult->item_igst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_cess_percentage'] +=$resResult->item_cess_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_vat_percentage']  +=$resResult->item_vat_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_net_surcharge_percentage'] +=$resResult->item_surcharge_percent;
		
			$printgroup['print_BillSplit'][$grouparray]['RoundOfAmount']	+=	(($resResult->item_amount*$resResult->qty)+$resResult->item_sgst_amount+$resResult->item_cgst_amount+$resResult->item_igst_amount+$resResult->item_cess_amount+$resResult->item_vat_amount+$resResult->item_surcharge_amount);
			
			$printgroup['print_BillSplit'][$grouparray]['round_off_amount'] = (($printgroup['print_BillSplit'][$grouparray]['RoundOfAmount']+$posPurchResult->others_charges_net_amount)-($posPurchResult->discount_amount_additional+$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']));
						
			$printgroup['print_BillSplit'][$grouparray]['net_amount'] =(($printgroup['print_BillSplit'][$grouparray]['RoundOfAmount']+$posPurchResult->sc_charges_net_amount+$posPurchResult->sc_sgst+$posPurchResult->sc_cgst+$posPurchResult->others_charges_net_amount)-($posPurchResult->discount_amount_additional+$printgroup['print_BillSplit'][$grouparray]['total_item_discount_amount']));
			}
			$grouparray++;
		}
		//print_r($printgroup);
		return $printgroup['print_BillSplit'];
	}
	
	function printPreview($printgroup){
	
	
		if(is_array($printgroup)){

		foreach ($printgroup as $index => $details) {
			$enable_split_bill_by_sales_account_group= $details['enable_split_bill_by_sales_account_group'];
			$custom_print_file = selectColumn(TBL_DOC_TYPE_CONFIG,'custom_print_file','WHERE id="'.$details['id_doc_type_configuration'].'"  ');	
			}
		}
		
		if($custom_print_file==''){
		if($enable_split_bill_by_sales_account_group	=='1'){			
			$printStatus	= printSalesAccountPreview($printgroup);
			}else{				
			$printStatus	= printDefaultPreview($printgroup);			
			}
		}else{
			include("include/".$custom_print_file);
			if($enable_split_bill_by_sales_account_group	=='1'){		
			$printStatus	= customPrintSalesAccountPreview($printgroup);
			}else{				
			$printStatus	= customPrintDefaultPreview($printgroup);			
			}
			}
	return $printStatus;
	
	}
	
	function printSalesAccountPreview($printgroup){
	
	
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
			$TableOutlet = mysqli_query($connNew, 'SELECT * FROM '.TBL_OUTLETS.' WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'"'); 			
			$OutletRecord = mysqli_fetch_object($TableOutlet);			
			
			$outletName = $OutletRecord->name;

			$city = $OutletRecord->city;
			$outletAddress = $OutletRecord->address;
			$id_state = $OutletRecord->id_mst_state;
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = $OutletRecord->id_mst_country_lang;
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = $OutletRecord->pincode;
			$outletMobile = $OutletRecord->mobile;
            $outletEmail = $OutletRecord->email;

			$outletPan = $OutletRecord->pan_no;
			$outletGstin = $OutletRecord->gst_no;
			$outletTin = $OutletRecord->tin_no;
			$outletHsn = $OutletRecord->hsn_code;

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
			$printer.=("<br>");
			if($RecordCount=='1'){
			$printer .='<div style="text-align:right;"><span style="text-align:right;font-size:12px;margin-right:15px;">Time:'. date('h:i A',strtotime($printgroup[$index]['date_created'])).'</span></div>';
			}
			if($printgroup[$index]['cancelled']==1){
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
			$printer.=('PAN No:'.$outletPan);
			
			//$printer.=(str_pad('TIN No : '.$outletTin,25," "));
			$printer.=(' HSN Code:'.$outletHsn);
			$printer.=("<br>");	
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
			
			//$printer.=(str_pad('Bill No : '.$index,25," "));
			$printer.=(str_pad('Bill No : '.$printgroup[$index]['mdoc_no'],25," "));
			$printer.=("<br>");
			$printer.=('Kot No : '.wordwrap($printgroup[$index]['kot_doc_no'],30,'<br>',true));
			$printer.=("<br>");
			$printer.=(str_pad('Bill Date: '.date('d-M-Y',strtotime($printgroup[$index]['doc_date'])),24," "));
			$printer.=('Table No: '.$printgroup[$index]['attribute_table_name']);
			$printer.=("<br>");
			//$printer.=("-------------------------------------\n");

			
			
			$printer.=(str_pad('Steward :'.$id_attribute_steward,25," "));
			$printer.=('Covers : '.$printgroup[$index]['pax']);
			$printer.=("<br>");
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
				$printer.=(str_pad(round($details['item_qty'][$id_index]),2," "));
				$printer.=(str_pad(round($details['item_rate'][$id_index],2),7," "));
				//$printer.="<div >";
				$printer.=(round(str_pad($details['item_amount'][$id_index],8," ")));
				//$printer.=("</div>");
				$printer.=("<br>");
				
			}

			$printer.=("----------------------------------------<br>");
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer.=(str_pad("",6," "));
			$printer.=(str_pad(" Total",19," "));
			$printer.=(str_pad(array_sum($details['item_qty']),9," "));
			$printer.=(trim($printgroup[$index]['sub_total_items']));
			//$printer -> selectPrintMode();
			//$printer->feed();
			$printer.=("<br>----------------------------------------<br>");
			$printer.=("<div style='width:265px;text-align: right;'>");
			$printer.="<div style='margin-right:15px;'>";
			if($details['total_item_discount_amount']>0){
			$printer.=('Discount : '.$printgroup[$index]['total_item_discount_amount']);
			$printer.=("<br>");
			}
			if($details['discount_amount_additional']>0){
			$printer.=('Discount : '.$details['discount_amount_additional']);
			$printer.=("<br>");
			}
			if($printgroup[$index]['sc_reverse']>0 && $index!='74'){
			$printer.=("Service Charges 10% :");
			$printer.=($printgroup[$index]['sc_charges_net_amount']);
			$printer.=("<br>");
				$sc_charges_net_amount	= $printgroup[$index]['sc_charges_net_amount'];
			}else{
				$details['net_amount']=$details['net_amount']-($printgroup[$index]['sc_charges_net_amount']+$printgroup[$index]['sc_sgst']+$printgroup[$index]['sc_cgst']);
				$sc_charges_net_amount	= 0;
				}
				
			$printer.=('Sub Total : '.(($printgroup[$index]['sub_total_items']+$sc_charges_net_amount)-($details['discount_amount_additional']+$printgroup[$index]['total_item_discount_amount'])));
			$printer.=("<br>");
			
					
			
			
			$printer.= calculateTaxprintSplit($printgroup,$index);
			
			
			$printer.="<div style=''>";
			//$printer.=("<div style='float:left;'>Total:</div> <div style='float:right;'> ".round($details['net_amount'],2).'</div>');
			$printer.=("Round Off:".round((round($details['net_amount'],0)-$details['net_amount']),2).'');
			$printer.=("<div style='font-weight:bold;font-size:16px;'>Grand Total:".str_pad(round($details['net_amount'],0),0," ").'</div>');
			$printer.="</div>";
			$printer.="</div>";
			if($printgroup[$index]['footer_remarks']!=''){
		       $printer.=(" <div style='  margin-top: 20px;width:265px;text-align: center;'>".($printgroup[$index]['footer_remarks']).'</div>');
		    }
			$printer.="</div>";
			$printer.=("<br>");
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

function calculateTaxprintSplit($printgroup,$index){	
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
			$printer.=($idtaxname['name'].' '.$taxPerc." : ");
			$printer.=($idtaxname['Tax']);
			$printer.=("<br>");
		}
			
	}
			
			
			
			



	return $printer;
}		
	






function nightAuditReport($Date,$id_outlet,$id_shift,$objPHPExcel){
	
	
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($Date != ''){
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


	  $sqlOrderDetail = "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where checkin_status='1' $SqlReservationConn ";
		


//die;

$queryReceipt = mysqli_query($connNew,$sqlOrderDetail);
$TotalNumberOfRowReceipts = mysqli_num_rows($queryReceipt);

$InCount=1;
$con++;
$count2=1;
$TotalBillCount=0;
$nightAudit=array();
while($RecordsReceipt	   =	mysqli_fetch_object($queryReceipt)){
	
				$outlet_Name ='Tariff';
				$id_mst_outlet='000121';
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Taxes']+=$RecordsReceipt->tax_per_day_per_room;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['outlet_Name']= $outlet_Name;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['mdoc_no']= $RecordsReceipt->mdoc_no;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['bill_date_created']= date('d-m-Y',strtotime($RecordsReceipt->bill_date_created));  
				
				
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['sub_total_items'] += $RecordsReceipt->tariff_price_per_day_per_room;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Discount']+=  $RecordsReceipt->Discount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['NetAmount']+=  $RecordsReceipt->sub_total_items-$RecordsReceipt->Discount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['sgst']+=  $RecordsReceipt->sgst+$RecordsReceipt->sc_sgst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['cgst']+=  $RecordsReceipt->cgst+$RecordsReceipt->sc_cgst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['igst']+=  $RecordsReceipt->igst;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['cess']+=  $RecordsReceipt->cess;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['vat']+=  $RecordsReceipt->vat;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['surcharge']+=  $RecordsReceipt->surcharge;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['OtherCharges']+=  $RecordsReceipt->OtherCharges+$RecordsReceipt->sc_charges_net_amount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['round_off_amount']+=  $RecordsReceipt->round_off_amount;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['grant_total_amount']+=  ($RecordsReceipt->tariff_price_per_day_per_room+$RecordsReceipt->tax_per_day_per_room);
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['UserName']= $RecordsReceipt->UserName;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Time']= $RecordsReceipt->Time;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Cash']+=  $RecordsReceipt->CASH;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Card']+=  $RecordsReceipt->CARD;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Company']+=  $RecordsReceipt->COMPANY;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['Cheque']+=  $RecordsReceipt->CHEQUE;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['OnlineTransfer']+=  $RecordsReceipt->ONLINETRANSFER;
				$nightAudit['NightAudit'][$outlet_Name][$id_mst_outlet]['mode']= $RecordsReceipt->paidTo;
				
	
	}
	//END TARIFF====================================================
	//debugData($nightAudit);
	//die;
	
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

	  ,sum(CHEQUE) as Cheque
	  ,sum(UPI) as 'Upi'
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
			
			 end) as paidTo	  
	  
	  
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='UPI' then IFNULL(amount,0) else null end as UPI
	  ,case when (payment_mode='ONLINETRANSFER' || payment_mode='CARD') and (id_cardtype=2 || id_cardtype=3) then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
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
WHERE id!=0 $SqlConn
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
				
	
	}
	
	//debugData($nightAudit);
	
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
			->setCellValue('M'.$con, 'ONLINE');
												
			cellColor('A'.$con.':M'.$con,'d9edb1');
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->getAlignment()->applyFromArray(

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
				->setCellValue('M'.$con, $Datalist3['OnlineTransfer']);
				
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
				
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->applyFromArray($styleThinBlackBorderOutline);
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
				->setCellValue('F'.$con, ($sgst+$cgst+$igst+$cess+$vat+$surcharge+$OtherCharges))
				
				->setCellValue('G'.$con, $round_off_amount)
				->setCellValue('H'.$con, $grant_total_amount)
				->setCellValue('I'.$con, $Cash)
				->setCellValue('J'.$con, $Card)
				->setCellValue('K'.$con, $Company)
				->setCellValue('L'.$con, $Cheque)
				->setCellValue('M'.$con, $OnlineTransfer);
				
		cellColor('A'.$con.':M'.$con,'d9edb1');		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->getFont()->setBold(true);		
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':M'.$con)->applyFromArray($styleThinBlackBorderOutline);
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