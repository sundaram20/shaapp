<?php
function fetchdataprint($pos_purch_id_array,$grouparray=0){	
	global $connNew;
	foreach($pos_purch_id_array as $posID){
	
	 $pos_purch_sql="SELECT * FROM ".TBL_PURCH." AS A  WHERE A.id_shop='".$_SESSION['shop']."'  AND id='".$posID."'";
	$resultPosPurch = mysqli_query($connNew, $pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
		 //echo $posPurchResult->id_pos_details_split;
		 
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
 $ResultKotdocQuerymdoc_no=   array();
$GetKotdocSql = "SELECT id,mdoc_no FROM `".TBL_PURCH."` WHERE FIND_IN_SET(id,'".$posPurchResult->kot_doc_no."') ";
$KotdocQuery	=	mysqli_query($connNew,$GetKotdocSql); 
						
while($ResultKotdocQuery = mysqli_fetch_object($KotdocQuery)){
	$ResultKotdocQuerymdoc_no[]= $ResultKotdocQuery->mdoc_no;
}
$ip=	implode(',',$ResultKotdocQuerymdoc_no); 


					
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
			
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_sgst_percentage'][]=$resResult->item_sgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cgst_percentage'][]=$resResult->item_cgst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_igst_percentage'][]=$resResult->item_igst_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_cess_percentage'][]=$resResult->item_cess_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_vat_percentage'][]=$resResult->item_vat_percent;
			$printgroup['print_BillSplit'][$grouparray]['item_Tax_surcharge_percentage'][]=$resResult->item_surcharge_percent;
			
			
			
			$printgroup['print_BillSplit'][$grouparray]['discount_amount_additional']=$posPurchResult->discount_amount_additional;
			$printgroup['print_BillSplit'][$grouparray]['others_charges_net_amount']=$posPurchResult->others_charges_net_amount; 
			
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
		
		if($item_Tax_sgst_percentage>0){
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['percentage']=$details['item_Tax_sgst_percentage'][$id_index];
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['Tax'] +=$details['item_Tax_sgst'][$id_index];
		$taxRecord[$item_Tax_sgst_percentage]['sgst']['name']='SGST';
		}
		if($item_Tax_cgst_percentage>0){
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['percentage']=$details['item_Tax_cgst_percentage'][$id_index];
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['Tax'] +=$details['item_Tax_cgst'][$id_index];
		$taxRecord[$item_Tax_cgst_percentage]['cgst']['name']='CGST';
		}
		if($item_Tax_igst_percentage>0){
		$taxRecord[$item_Tax_igst_percentage]['igst']['percentage']=$details['item_Tax_igst_percentage'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['Tax'] +=$details['item_Tax_igst'][$id_index];
		$taxRecord[$item_Tax_igst_percentage]['igst']['name']='IGST';
		}
		if($item_Tax_cess_percentage>0){
		$taxRecord[$item_Tax_cess_percentage]['cess']['percentage']=$details['item_Tax_cess_percentage'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['Tax'] +=$details['item_Tax_cess'][$id_index];
		$taxRecord[$item_Tax_cess_percentage]['cess']['name']='cess';
		}
		if($item_Tax_vat_percentage>0){
		$taxRecord[$item_Tax_vat_percentage]['vat']['percentage']=$details['item_Tax_vat_percentage'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['Tax'] +=$details['item_Tax_vat'][$id_index];
		$taxRecord[$item_Tax_vat_percentage]['vat']['name']='VAT';
		}
		if($item_Tax_surcharge_percentage>0){
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['percentage']=$details['item_Tax_surcharge_percentage'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['Tax'] +=$details['item_Tax_surcharge'][$id_index];
		$taxRecord[$item_Tax_surcharge_percentage]['surcharge']['name']='surcharge';
		}
		
		
					 
		}
	}
	
	
	foreach($taxRecord  as $indexTax => $detailss){
		foreach ($detailss as $id_Taxindex => $idtaxname) {
			
			$printer.=($idtaxname['name'].' '.trim($idtaxname['percentage'])." %: ");
			$printer.=($idtaxname['Tax']);
			$printer.=("<br>");
		}
			
	}
			
			
			
			
	return $printer;
}



function printPreview($printgroup){	
	if(is_array($printgroup)){

		foreach ($printgroup as $index => $details) {
		//debugData($printgroup);
		//print_r($printgroup);
		 
		
			/**** Outlet Information ***/
			$id_outlet = selectColumn(TBL_OUTLETS,'id','WHERE id_shop="'.$_SESSION['shop'].'" ');
			$outletName = selectColumn(TBL_OUTLETS,'name','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');


			$outletAddress = selectColumn(TBL_OUTLETS,'CONCAT(address,", ",city)','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$id_state = selectColumn(TBL_OUTLETS,'	id_mst_state','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletState = selectColumn(TBL_STATE,'name','WHERE id_state="'.$id_state.'" ' );
			$id_country = selectColumn(TBL_OUTLETS,'id_mst_country_lang','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletCountry = selectColumn(TBL_COUNTRY_LANG,'name','WHERE id_country="'.$id_country.'" ' );

			$outletPincode = selectColumn(TBL_OUTLETS,'pincode','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$outletPan = selectColumn(TBL_OUTLETS,'pan_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletGstin = selectColumn(TBL_OUTLETS,'gst_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletTin = selectColumn(TBL_OUTLETS,'tin_no','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
			$outletHsn = selectColumn(TBL_OUTLETS,'hsn_code','WHERE id="'.$id_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');

			$biller =selectColumn(TBL_USERS,'name','WHERE id="'.$_SESSION['userId'].'" ');
			

			$id_attribute_steward =selectColumn(TBL_ATTRIBUTES,'field_value','WHERE id_shop="'.$_SESSION['shop'].'"  and status = 1 AND table_name ="steward"  AND id="'.$printgroup[0]['id_attribute_steward'].'" ');

			$printer.=("<br>");
			
			/* Print top logo */
			$logoImage=selectColumn(TBL_OUTLETS,'image','WHERE id_shop="'.$_SESSION['shop'].'" ');
			//$logo = EscposImage::load('../../uploaded_files/outlets/medium-'.$logoImage, false);
			$printer .='<div style="text-align:center;" ><img src='.$SITE_URL.'/uploaded_files/outlets/small-'.$logoImage.'  alt=""></div>';
			
			//$printer.=($outletName);
	
			$printer.=("<br><br><br><br><br>");

			$printer.=($outletAddress);
			
			$printer.=("<br>");
			$printer.=($outletState.'-'.$outletPincode.', '.$outletCountry);
			
			$printer.=("<br>");
			$printer.=("---------------------------------------------\n");
			
			$printer.=(str_pad('GST No : '.$outletGstin,25," "));
			$printer.=('PAN No : '.$outletPan);
			$printer.=("<br>");
			$printer.=(str_pad('TIN No : '.$outletTin,25," "));
			$printer.=('HSN Code : '.$outletHsn);
			$printer.=("<br>");

			
			$printer.=("---------------------------------------------\n");
			
			//$printer.=(str_pad('Bill No : '.$index,25," "));
			$printer.=(str_pad('Bill No : '.$printgroup[0]['mdoc_no'],25," "));
			$printer.=("<br>");
			$printer.=('Kot No : '.$printgroup[0]['kot_doc_no']);
			$printer.=("<br>");
			$printer.=(str_pad('Bill Date : '.date('d-M-Y',strtotime($printgroup[0]['doc_date'])),25," "));
			$printer.=('Table No : 2');
			$printer.=("<br>\n");
			$printer.=("---------------------------------------------\n");

			
			
			$printer.=(str_pad('Steward :'.$id_attribute_steward,25," "));
			$printer.=('Covers : '.$printgroup[0]['pax']);
			$printer.=("<br>\n");
			$printer.=('Party Name : Addiyar Infotech Pvt Ltd.');
			$printer.=("<br>");
			$printer.=('Party GST No : AXH 125482');
			$printer.=("<br>");
			$printer.=("---------------------------------------------<br>");

			
			$printer.=(str_pad('S No.',6," "));
			$printer.=(str_pad('Description',15," "));
			$printer.=(str_pad('Qty',8," "));
			$printer.=(str_pad('Rate',9," "));
			$printer.=str_pad('Amount',7," ");
			$printer.=("<br>");
			$printer.=("---------------------------------------------<br>");
			$sno=1;
			
			
			foreach ($details['item_id'] as $id_index => $id_item) {
				
				$printer.=(columnify($sno++,trim($details['item_description'][$id_index]),2,15,4));
				$printer.=(str_pad($details['item_qty'][$id_index],8," "));
				$printer.=(str_pad($details['item_rate'][$id_index],9," "));
				$printer.=(number_format(str_pad($details['item_amount'][$id_index],30," "),2));
				$printer.=("<br>");
				
			}

			$printer.=("---------------------------------------------<br>");
			//$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
			$printer.=(str_pad("",7," "));
			$printer.=(str_pad("Sub Total",14," "));
			$printer.=(str_pad(array_sum($details['item_qty']),17," "));
			$printer.=(trim($printgroup[0]['sub_total_items']));
			//$printer -> selectPrintMode();
			//$printer->feed();
			$printer.=("<br>---------------------------------------------<br>");
			$printer.="<div style='float:left;margin-left:130px'>";
			if($details['total_item_discount_amount']>0){
			$printer.=('Item Discount : '.$printgroup[0]['total_item_discount_amount']);
			$printer.=("<br>");
			}
			if($details['discount_amount_additional']>0){
			$printer.=('Discount : '.$details['discount_amount_additional']);
			$printer.=("<br>");
			}
					
			if($printgroup[0]['sc_reverse']>0){
			$printer.=("Service Charges 10% :");
			$printer.=($printgroup[0]['sc_charges_net_amount']);
			$printer.=("<br>");
			}
			
			$printer.= calculateTaxprint($printgroup);
			
			
			
			$printer.=("Total: ".round($details['net_amount'],2));
			
			$printer.=("<br>");
			$printer.=("Round Off : ");
			$printer.=(round((round($details['net_amount'],0)-$details['net_amount']),2));
			$printer.=("<br>");

			
			$printer.=("Grand Total : ");
			$printer.=(str_pad(round($details['net_amount'],0),5," "));
			$printer.=("</div> <br>");
			
			/*** Outlet Information End ***/
			
		}

	}
		
	return $printer;
}
function consolidatedItemWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show){	
	global $connNew;
	//echo '==================='.$id_report_type;
//echo '.==================='.$report_show;die;
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SqlRepDateConn =	" AND doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";	
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
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
	
		

               
  $pos_purch_sql="select 
			doc_date,     
			id_mst_items,
			item_code,
			item_description,
			sum(qty) as qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub
 
	from 
(
    
    select pp.doc_date,pp.kot_doc_no,ppp.id_mst_items,ppp.item_description,  ppp.id as id_purch_detail,inv.item_code,
ppp.qty, ppp.item_amount, ppp.item_discount_amount,((ppp.qty*ppp.item_amount)-ppp.item_discount_amount)  as  total,

inv.id as id_item,
inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub

from pos_purch  pp
left join
pos_purch_details ppp

on
FIND_IN_SET(ppp.id_pos_purch,pp.kot_doc_no)

Inner join
inv_items inv
ON inv.id=ppp.id_mst_items
  where pp.pos_bill_type='2' and pp.cancelled=0 and  pp.doc_type=21 and pp.id_shop= '".addslashes($_SESSION['shop'])."'
  	$SqlRepDateConn	
  
  order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id
    ) as purch_rpt

WHERE id_mst_items!=0 $SqlRepConn
group by id_mst_items
order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$DatewiseArray['Report']['id_mst_attributes_group_main']=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 
		 
		 
		 $DatewiseArray['Report']['id_mst_attributes_group_main'][]=$posPurchResult->id_mst_attributes_group_main;
		 $DatewiseArray['Report']['id_mst_attributes_group_sub'][$posPurchResult->id_mst_attributes_group_main][]=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report']['id_inv_items'][$posPurchResult->id_mst_attributes_group_main][$posPurchResult->id_mst_attributes_group_sub][]=$posPurchResult->id_mst_items;
		 $DatewiseArray['Report']['name'][$posPurchResult->id_mst_attributes_group_main][$posPurchResult->id_mst_attributes_group_sub][] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report']['item_code'][$posPurchResult->id_mst_attributes_group_main][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->item_code;
		 $DatewiseArray['Report']['grandtotal'][$posPurchResult->id_mst_attributes_group_main][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report']['qty'][$posPurchResult->id_mst_attributes_group_main][$posPurchResult->id_mst_attributes_group_sub][] =$posPurchResult->qty;
		 
	 }
	
	
	$MainGroup=array_unique($DatewiseArray['Report']['id_mst_attributes_group_main']);
	
	//echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>
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

$BackgroundColorMain	='background-color:#c0c0c0;';
$BackgroundColor	='background-color:#008080;';
if($report_show!=1){
	$content .= '<table  class="table" style=" text-align:center;margin-bottom: 0px;border: 0px;  ">
						<tr>					
						  <th>
						  <img src="'.$pathImg.'/uploaded_files/shop/'.$logo.'" class="img-responsive" alt="logo" title="logo"   />
						 </th>
						</tr>
			</table>';
}
			$content .= '<table class="table table-striped text-center">
	<tr style="vertical-align:central;text-align:center;"><th colspan="5" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b>Consolidated Itemwise Sales Report '.$ReportDate.'</b></th></tr>
		</table>
		
						';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%;    text-align: center;
    color: #000;
    background-color: #c2d69a;">';
		$content .= '<tr style="font-size:16px !important;">
		<th width="60px;"><b>S.no</b></th>';
		if($id_report_type==196){
		$content .= '<th width="100px;"><b>Item Code</b></th>';
		}
		$content .= '<th ><b>Description</b></th>
		<th width="100px;"><b>Qty</b></th>
		<th width="100px;"><b>Amount</b></th>';
		$content .= '			 
				</tr>
			</table>
	    ';
		
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%">';

	
	
	
	
	$GrandTotalQTY=0;
	$GrandTotalAmount=0;
	if($id_report_type==196){
		$colspa1=5;
	}else{
		$colspa1=6;
		}
		
		
		
		
		
	foreach($DatewiseArray['Report']['name'] as $id_main=>$subindexvalue){
		
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  `id` = '".$id_main."'"));		
		$content .= '<tr style="'.$BackgroundColorMain.'color:#ooo !important;font-size:16px !important;">
			<th colspan="'.$colspa1.'" ><b>'.$maingroupName.'</b></th>
			</tr>';
			
		$MainGroupTotalQTY=0;
		$MainGroupTotalAmount=0;
		$subgroupInc=1;	
		foreach($subindexvalue as $id_subindex=>$data){
			
			$subgroupName=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND  `id` = '".$id_subindex."'"));
			//if($id_report_type==196){
			$content .= '<tr style="'.$BackgroundColor.'color:#fff !important;font-size:16px !important;">
			<th colspan="5" ><b>'.$subgroupName.'</b></th>
			</tr>';
			//}
			
			$k=0;
			$i=1;
			
			$subgroupTotalQTY=0;
			$SubGroupTotalAmounts=0;
			foreach($data as $datavalue){
			
			
			$qty=		number_format($DatewiseArray['Report']['qty'][$id_main][$id_subindex][$k],2);
			$grandtotal=	number_format($DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k],2);
			 if($id_report_type==196){
			 $content .= '<tr style="border:1px solid:font-size:11px !important;">
			<td width="60px;" style="text-align:center">'.$i.'</td>
			<td  width="100px;">'.$DatewiseArray['Report']['item_code'][$id_main][$id_subindex][$k].'</td>
			<td>'.strtoupper($datavalue).'</td>
			
			<td style="width:100px;text-align:right;">'.$qty.'</td>
			<td style=width:100px;text-align:right;">'.$grandtotal.'</td>
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
		if($id_report_type==196){//Sub GroupItem
			$content .= '<tr style="border:1px solid:font-size:11px !important;">
			<td width="60px;" style="text-align:center">'.$subgroupInc.'</td>
			
			<td>'.strtoupper($subgroupName).'</td>
			
			<td style="width:100px;text-align:right;">'.number_format($subgroupTotalQTY,2).'</td>
			<td style=width:100px;text-align:right;">'.number_format($SubGroupTotalAmounts,2).'</td>
			</tr>';
			$colspa=2;
			}else{
				$colspa=3;
				$content .= '<tr style="font-size:12px !important;color:#800000;">
			<td colspan="'.$colspa.'" style="text-align:right;margin-left:50px !important;color:#cc2c2c;"><b>Sub Group Total '.ucfirst(strtolower($subgroupName)).'</b></td>          
						<td  style=text-align:right;"><b>'.number_format($subgroupTotalQTY,2).'</b></td>
						<td  style=text-align:right;"><b>'.number_format($SubGroupTotalAmounts,2).'</b></td>
			</tr>';
					
		}
			$subgroupInc++;	
			}
		//$content .= '<tr style="font-size:12px !important;color:#800000;"><td colspan="5"></td>&nbsp;</tr>';
		
		
		$content .= '<tr style="font-size:12px !important;color:#800000;">
			<td colspan="'.$colspa.'" style="text-align:right;margin-left:50px !important;color:#cc2c2c;"><b>Main Group Total '.ucfirst(strtolower($maingroupName)).'</b></td>
						<td  style=text-align:right;"><b>'.number_format($MainGroupTotalQTY,2).'</b></td>
						<td  style=text-align:right;"><b>'.number_format($MainGroupTotalAmount,2).'</b></td>
			</tr>';
		
		
		
		}
		
		$content .= '<tr style="font-size:12px !important;color:#800000;">
			<td colspan="'.$colspa.'" style="text-align:right;margin-left:50px !important;color:#cc2c2c;"><b>Grand Total </b></td>
						<td  style=text-align:right;"><b>'.number_format($GrandTotalQTY,2).'</b></td>
						<td  style=text-align:right;"><b>'.number_format($GrandTotalAmount,2).'</b></td>
			</tr>';
			
			
		$content .= '</table>';
		//echo $content;
//		die;
		$date=date('d-m-yy');
if($id_report_type==196){		
$Filename='consolidatedItemWiseReport_'.$date;
}else{
$Filename='consolidatedSubGroupWiseReport_'.$date;	
	}

	if($report_show==3){
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
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$Filename".'.xls');
        echo $test;die;
	}
	else{
		 echo $content;
		die;
		}
		}	
		
		
		
function settlementSummaryReport($Date,$id_outlet,$id_shift){
	global $connNew;
	global $objPHPExcel;
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
		$startDate = date('Y-m-d',strtotime($DateExplode['0']));
		$endDate	=	date('Y-m-d',strtotime($DateExplode['1']));
		$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
			
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

$objDrawing = new PHPExcel_Worksheet_Drawing();
	$objDrawing->setName('Paid');
	$objDrawing->setDescription('Paid');
	$objDrawing->setPath('../uploaded_files/shop/'.$logo);
	$objDrawing->setCoordinates('L1');
	$objDrawing->setOffsetX(0);
	$objDrawing->setRotation(0);
	$objDrawing->getShadow()->setVisible(true);
	$objDrawing->getShadow()->setDirection(0);
	$objDrawing->setWorksheet($objPHPExcel->getActiveSheet());

$head_cntr = "C";
	$setcellcount	=8;
	$HotesCount=$setcellcount;
	$Comy	=	$setcellcount;
$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A7', "Settlement Summary");
	$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A7:Y7');



 $styleThinBlackBorderOutline = array(
	'borders' => array(
	'allborders' => array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('argb' => '000'),
	),
	),
 );
$objPHPExcel->getActiveSheet()->getStyle('A7:Y7')->applyFromArray($styleThinBlackBorderOutline);

$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)

	);

	$objPHPExcel->getActiveSheet()->getStyle('A7')->getAlignment()->applyFromArray(

	array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)



	);
$con=$setcellcount;


	
$objPHPExcel->setActiveSheetIndex(0)
->setCellValue('A'.$con,'From Date'.$Date);

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':Y'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('A'.$con)->getAlignment()->applyFromArray(
		array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
	);

$con++;


	
$SQL="select 
       id as 'Payment ID'
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
	  ,sum(GIFTVOUCHER) as 'GiftVoucher'
	  ,sum(ONLINETRANSFER) as 'OnlineTransfer'
	  ,sum(COMPANY) as Company
	  ,field_value as 'FeildValue'
      ,max(date_created) as 'CreatedDate'
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
	  ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH
	  ,case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='GIFTVOUCHER' then IFNULL(amount,0) else null end as GIFTVOUCHER
	  ,case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER
	  ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY
	  ,att.field_value
      ,pp.date_created as date_created
	  ,p.pax
	  ,p.id_attribute_table
	  
    
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
GROUP BY id,name,field_value  ORDER BY id_mst_outlet,id_attribute_shift asc";
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
			cellColor('C'.$con.':Y'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill: '.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetNetAmountItems,2))
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
				->setCellValue('Y'.$con++, round($OutLetOnlineTransfer,2));
			
			
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
			
			
			$con=$con+2;
			cellColor('A'.$con.':B'.$con,'e0e0e0');
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, 'SUMMARY DETAILS')
				
				->setCellValue('A'.$con, 'CASH')
				->setCellValue('B'.$con++, round($sub_total_Cash,2))
				
				->setCellValue('A'.$con, 'CARD')
				->setCellValue('B'.$con++, round($sub_total_Card,2))
				
				->setCellValue('A'.$con, 'COMPANY')
				->setCellValue('B'.$con++, round($sub_total_Company,2))
				
				->setCellValue('A'.$con, 'CHEQUE')
				->setCellValue('B'.$con++, round($sub_total_Cheque,2))


				->setCellValue('A'.$con, 'ONLINE')
				->setCellValue('B'.$con++, round($sub_total_OnlineTransfer,2))
				
								
				->setCellValue('A'.$con, 'TOTAL COLLECTION')
				->setCellValue('B'.$con, round($grant_total_amount,2));
				cellColor('A'.$con.':B'.$con++,'e0e0e0');
				$con=$con+2;
				
				}
		//SUMMARY DETAILS====================================================
	
	
		cellColor('A'.$con.':Y'.$con,'ecf0f5');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':Y'.$con);
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
		$sub_total_Card ='';
		$sub_total_Company='';
		$sub_total_Cheque ='';
		$sub_total_OnlineTransfer ='';
		$grant_total_amount ='';
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A'.$con, 'S:No.')
			->setCellValue('B'.$con, 'Outlet')
			->setCellValue('C'.$con, 'Bill')
			->setCellValue('D'.$con, 'Date')
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
			
			->setCellValue('P'.$con, 'Other Charge')
			->setCellValue('Q'.$con, 'Round')
			->setCellValue('R'.$con, 'Total Amount')
			->setCellValue('S'.$con, 'User ID')
			->setCellValue('T'.$con, 'Time')
			//->setCellValue('U'.$con, 'Remark')
			->setCellValue('u'.$con, 'Cash')
			->setCellValue('v'.$con, 'Card')
			->setCellValue('w'.$con, 'Company')
			->setCellValue('x'.$con, 'Cheque')
			->setCellValue('Y'.$con, 'ONLINE');									

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Y'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$con++;
		}
if($Records->id_mst_outlet!=$mstoutlet2){//Outlet Split


		
		if($count2>1){
			$con=$con+1;
			cellColor('C'.$con.':Y'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill: '.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetNetAmountItems,2))
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
				->setCellValue('Y'.$con++, round($OutLetOnlineTransfer,2));
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
		}
	}
	
	$SqlAttrbuteTable =  mysqli_query($connNew,"SELECT * FROM `".TBL_ATTRIBUTES."` where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'table' AND id= '".$Records->id_attribute_table."'");
	
    $resultAttrbuteTable = mysqli_fetch_object($SqlAttrbuteTable);
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con, $InCount)
				->setCellValue('B'.$con, $Records->outlet_Name)
				->setCellValue('C'.$con, $Records->mdoc_no)
				->setCellValue('D'.$con, date('d-m-Y',strtotime($Records->CreatedDate)))
				->setCellValue('E'.$con, $resultAttrbuteTable->field_value.'('.$Records->pax.')')
				->setCellValue('F'.$con, $Records->outlet_Name)
				->setCellValue('G'.$con, $Records->sub_total_items)
				->setCellValue('H'.$con, $Records->Discount)
				->setCellValue('I'.$con, $Records->net_amount_items)
				->setCellValue('J'.$con, $Records->sgst)
				->setCellValue('K'.$con, $Records->cgst)
				->setCellValue('L'.$con, $Records->igst)
				->setCellValue('M'.$con, $Records->cess)
				->setCellValue('N'.$con, $Records->vat)
				->setCellValue('O'.$con, $Records->surcharge)
				->setCellValue('P'.$con, $Records->OtherCharges)
				->setCellValue('Q'.$con, $Records->round_off_amount)
				->setCellValue('R'.$con, $Records->grant_total_amount)
				->setCellValue('S'.$con, $Records->UserName)
				->setCellValue('T'.$con, $Records->Time)
				->setCellValue('U'.$con, $Records->Cash)
				->setCellValue('V'.$con, $Records->Card)
				->setCellValue('W'.$con, $Records->Company)
				->setCellValue('X'.$con, $Records->Cheque)
				->setCellValue('Y'.$con, $Records->OnlineTransfer);
				//->setCellValue('Z'.$con, $Records->FeildValue)
				//->setCellValue('AA'.$con, $Records->CreatedDate)
				//->setCellValue('AA'.$con, $Records->CreatedDate);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
		
		
		//SUMMARY DETAILS====================================================
		$sub_total_Cash +=$Records->Cash;
		$sub_total_Card +=$Records->Card;
		$sub_total_Company +=$Records->Company;
		$sub_total_Cheque +=$Records->Cheque;
		$sub_total_OnlineTransfer +=$Records->OnlineTransfer;
		$grant_total_amount +=$Records->grant_total_amount;
		
		
		//outlet SubTotal
		$OutLetSubTotalItem +=$Records->sub_total_items;
		$OutLetDiscount +=$Records->Discount;
		$OutLetNetAmountItems +=$Records->net_amount_items;
		$OutLetSGST +=$Records->sgst;
		$OutLetCGST +=$Records->cgst;
		$OutLetIGST +=$Records->igst;
		$OutLetCESS +=$Records->cess;
		$OutLetVAT +=$Records->vat;
		$OutLetSurcharge +=$Records->surcharge;
		$OutLetOtherCharges +=$Records->OtherCharges;
		$OutLetRoundOffAmount +=$Records->round_off_amount;
		$OutLetGrantTotalAmount +=$Records->grant_total_amount;
		$OutLetCash +=$Records->Cash;
		$OutLetCard +=$Records->Card;
		$OutLetCompany +=$Records->Company;
		$OutLetCheque +=$Records->Cheque;
		$OutLetOnlineTransfer +=$Records->OnlineTransfer;
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
			cellColor('C'.$con.':Y'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Y'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$TotalBillCount=$TotalBillCount+1;
			
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('C'.$con, 'Total Bill:'.$TotalBillCount)
				->setCellValue('E'.$con, 'Total Pax: '.$TotalPax)
				->setCellValue('G'.$con, round($OutLetSubTotalItem,2))
				->setCellValue('H'.$con, round($OutLetDiscount,2))
				->setCellValue('I'.$con, round($OutLetNetAmountItems,2))
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
				->setCellValue('Y'.$con++, round($OutLetOnlineTransfer,2));
			
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
			
			$con=$con+2;
			cellColor('A'.$con.':B'.$con,'e0e0e0');
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A'.$con++, 'SUMMARY DETAILS')
				
				->setCellValue('A'.$con, 'CASH')
				->setCellValue('B'.$con++, round($sub_total_Cash,2))
				
				->setCellValue('A'.$con, 'CARD')
				->setCellValue('B'.$con++, round($sub_total_Card,2))
				
				->setCellValue('A'.$con, 'COMPANY')
				->setCellValue('B'.$con++, round($sub_total_Company,2))
				
				->setCellValue('A'.$con, 'CHEQUE')
				->setCellValue('B'.$con++, round($sub_total_Cheque,2))


				->setCellValue('A'.$con, 'ONLINE')
				->setCellValue('B'.$con++, round($sub_total_OnlineTransfer,2))
				
								
				->setCellValue('A'.$con, 'TOTAL COLLECTION')
				->setCellValue('B'.$con, round($grant_total_amount,2));
				cellColor('A'.$con.':B'.$con++,'e0e0e0');
			
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
	?>