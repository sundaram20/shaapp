<?php




function PurchaseOrderReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){

    

	global $connNew;$contentstyle='';
	global $appConnect;




	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;
//print_r($_REQUEST);
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
	$SqlRepConn .=	" AND id_inv_items IN (".$id_items.")";	
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
	   if($id_order_by==1){
	
	  $OrderDiplay22	=	'Name ';
	  $SqlOrderByConn22 = ' item_description';

	  } 




} 

if($id_order_by==''){
	  $SqlOrderByConn22 = 'doc_date ';
	   $SqlOrderByConnType22 =' DESC ';
	     $OrderDiplay22	=	' Date';
}



if($id_report_type!=''){
	$SqlRepDocTypeConn =	"  AND pp.doc_type IN (".$id_report_type.")";	
	}
  $pos_purch_sql="select 
			id as id_inv_po,
			doc_date,     
			doc_no,
			mdoc_no,
			id_inv_items,
			item_code,
			name as item_description,
			sum(qty) as qty,
			sum(ordered_qty) as ordered_qty,
			sum(bal_qty) as bal_qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub,
			id_mst_party_supplier,
			rate_per_main_unit as item_amount,
			doc_type
			from 
(
    
			select pp.id,pp.doc_date,pp.doc_no,pp.mdoc_no,ppp.id_inv_items,inv.name,  ppp.id as id_purch_detail,inv.item_code,
			ppp.qty, ppp.ordered_qty,ppp.bal_qty,ppp.rate_per_main_unit, ppp.discount_amount,((ppp.item_amount)-ppp.discount_amount)  as  total,
			
			inv.id as id_item,
			inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,pp.id_mst_party_supplier
			,pp.doc_type
			FROM inv_po  pp
			LEFT JOIN inv_po_details ppp ON ppp.id_inv_po=pp.id
					
			INNER JOIN inv_items inv ON inv.id=ppp.id_inv_items
			
			WHERE   pp.id_shop= '".addslashes($_SESSION['shop'])."' 
			$SqlRepDocTypeConn
			$SqlRepDateConn	
  
  			order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.name
			
    ) as purch_rpt

WHERE id_inv_items!=0 $SqlRepConn
group by doc_no,id_inv_items $SqlGroupByConn order by $SqlOrderByConn $SqlOrderByConnType $SqlOrderByConn22  $SqlOrderByConnType22 
 ";
//sql for voucher report
 //echo '1 :'.$pos_purch_sql;
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		//echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$DatewiseArray=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 if($id_report_type!=''){
	
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  `id` = '".$posPurchResult->id_mst_attributes_group_main."'"));
		
	
	}  
	
	$maingroupName=$posPurchResult->mdoc_no;
	 $party_name	=strtoupper(selectColumn(TBL_PARTY,'company_name'," WHERE status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$posPurchResult->id_mst_party_supplier."'"));
		$doc_type = 'Group';selectField(APP_DOCTYPE,'name','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect);   
		$doc_name= selectField(APP_DOCTYPE,'name','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect); 
        		$id_doc_type= selectField(APP_DOCTYPE,'id_doc_type','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect); 



		
		
		 //$DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 //$doc_type	=$posPurchResult->doc_type;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['id_inv_po']=$posPurchResult->id_inv_po;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['party_name']=$party_name;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['mdoc_no'] =($posPurchResult->mdoc_no);
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_date'] =$posPurchResult->doc_date;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['grandtotal'] +=$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['qty'] +=$posPurchResult->qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['ordered_qty'] +=$posPurchResult->ordered_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['bal_qty'] +=$posPurchResult->bal_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_name'] =$doc_name;



		 //akm starts
		 		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['id_doc_type'] =$id_doc_type;





		// $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_name'] =$pageURL;;

	
		 
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['id_mst_attributes_group_sub']=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['id_inv_items']=$posPurchResult->id_inv_items;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['name'] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['item_code'] =$posPurchResult->item_code;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['grandtotal'] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['item_amount'] =$posPurchResult->item_amount;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['qty'] =$posPurchResult->qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['ordered_qty'] =$posPurchResult->ordered_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['bal_qty'] =$posPurchResult->bal_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['mdoc_no'] =$posPurchResult->mdoc_no;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['doc_date'] =$posPurchResult->doc_date;
		
	 }
	
	
	
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>

	?>
    <script>$(function() {});
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


$content .= '</style>';


		 		//selectColumn(APP_SUB_MENU,'name'," WHERE status='1' and type='2' AND id='".$id_report_type."'");
				
		foreach($DatewiseArray['Report'] as $doc=>$DatewiseArrayStep){
					
	   
 // echo $headerColor = 'hi';
		$headerColor = selectField(APP_COLOR_CONFIG,'report_header_color','',$appConnect); 
		$headerTextColor = selectField(APP_COLOR_CONFIG,'report_header_text_color','',$appConnect); 
		$titleColor = selectField(APP_COLOR_CONFIG,'report_title_color','',$appConnect); 
		$titleTextColor = selectField(APP_COLOR_CONFIG,'report_title_text_color','',$appConnect); 
		$subtitleColor = selectField(APP_COLOR_CONFIG,'report_subtitle_color','',$appConnect); 
		$subtitleTextColor = selectField(APP_COLOR_CONFIG,'report_subtitle_text_color','',$appConnect); 

					
			$content .= '<table class="table table-striped text-center">';
	$content .= '<tr><th colspan="6" style="border-right:1px solid #0093c7 ; vertical-align:central;text-align:left;color:'.$headerTextColor.';background-color:'.$headerColor.';font-size:16px !important"><b>  Report Period '.$date.' Order By '.$OrderDiplay.''.$OrderDiplay22.' </b></th>
	<th  colspan="4" style="text-align:right;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:13px !important"><b> Report Date: '.date('d-m-Y H:m:i').' </b></th></tr>';
	

		$content .= '</table>';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000; >';
		
		
		$content .= '<tr  '.$listTagClass.' style="color:#ooo !important;font-size:14px !important; font-weight:600;" >';				
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:5px;">SNo </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:25px;">Type </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:40px;">Party Name </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Doc No </td>';	
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Doc Date </td>';			
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Item Code</td>
				<td colspan="0" style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:40px;">Item Name</td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Qty</td>';
				
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Item Amount</td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Amount</td>
				</tr>';
	

	
		
		
		
	$i=1;	
	foreach($DatewiseArrayStep as $id_main=>$subindexvalue){
		//Main Group=======================>
			
	////	echo $id_main;
	//debugData($subindexvalue);
	$k=1;
	$count=count($subindexvalue)-1;
	
		foreach($subindexvalue as $id_subindex=>$data){
			
			//echo $id_subindex;
			
			//debugData($data);
//Goods Receipt Note
if($data['id_doc_type']==4){			 	
            $PageURL="editPurch.php";
            $session=4;
            $submenu=100;
            $doc_type=4;
		 }
	//purchase bill
 if($data['id_doc_type']==5){
            $PageURL="editPurch.php";
            $session=5;
            $submenu=101;
            $doc_type=5;
		 }
	//store issue note
	 if($data['id_doc_type']==6){
            $PageURL="editStoreIssueNote.php";
            $submenu=102;
            $session=6;
            $doc_type=6;      

		}
// debit note
if($data['id_doc_type']==8){
            $PageURL="editDebit.php";
            $session=8;
            $submenu=182;
            $doc_type=8;      

		 }
//Physical Stock
if($data['id_doc_type']==9){
            $PageURL="editPhysicalStock.php";
            $session=9;
            $submenu=103;
            $doc_type=9;
            

		 }
 if($data['id_doc_type']==12){
 	//Direct Purchase
            $PageURL="editPurch.php";
            $session=12;
            $submenu=181;
            $doc_type=12;
            

		 }


			if('Heading-'.$id_main==$id_subindex){?>
            <?php
			$content .= '<tr '.$listTagClass.' style="background-color:'.$subtitleColor.';color:#ooo !important;font-size:12px !important; font-weight:600">';				
				$content .= '<td style="text-align:left;">'.$i++.'</td>';
				$content .= '<td style="text-align:left;">'.$data['doc_name'].'</td>';
				$content .= '<td  style=text-align:left;width:40px;"> '.$data['party_name'].'</td>';

				$content .= '<td style="text-align:left;width:10px;"><a href="'.$PageURL.'?eId='.encryptor(encrypt, $data['id_inv_po']).'&session='.$session.'&submenu='.$submenu.'&action=edit&page=&doc_type='.$doc_type.'&print=0"  target="_blank">'.$data['mdoc_no'].'</a></td>';	
				$content .= '<td style="text-align:left;width:10px;"> '.date('d-m-Y', strtotime($data['doc_date'])).'</td>';
				
				$content .= '<td  colspan="5" style=text-align:right;width:34px;">'.$data['grandtotal'].'</td>
				</tr>';
			}else{
				$content .= '<tr   '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				
				if($k==1){
				   $content .= '<td     colspan="5" rowspan="'.($count).'" style="text-align:center;"></td>';
				}
				$k++;
				$content .= '<td style="text-align:left;width:10px;">'.$data['item_code'].'</td>';
				$content .= '<td style="text-align:left;width:50px;">'.$data['name'].'</td>';	
				
				$content .= '<td style="text-align:right;width:50px;">'.$data['qty'].'</td>';
				
				$content .= '<td style="text-align:right;width:50px;">'.$data['item_amount'].'</td>';
				$content .= '<td style=text-align:right;width:34px;">'.$data['grandtotal'].'</td>
				</tr>';
				
				
			}
						
				
				
			
			}
		
		
		}
		
		
	
			
			
		$content .= '</table>';
		
}
		//echo $content;
//		die;
		$date=date('d-m-yy');
if($id_report_type==196){		
$Filename='PurchaseOrderReport_'.$date;
}else{
$Filename='PurchaseOrderReport_'.$date;	
	}

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
		
	














function PurchaseOrderPendingReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){

    

	global $connNew;$contentstyle='';
	global $appConnect;




	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;
//print_r($_REQUEST);
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
	$SqlRepConn .=	" AND id_inv_items IN (".$id_items.")";	
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
	   if($id_order_by==1){
	
	  $OrderDiplay22	=	'Name ';
	  $SqlOrderByConn22 = ' item_description';

	  } 




} 

if($id_order_by==''){
	  $SqlOrderByConn22 = 'doc_date ';
	   $SqlOrderByConnType22 =' DESC ';
	     $OrderDiplay22	=	' Date';
}



if($id_report_type!=''){
	$SqlRepDocTypeConn =	"  AND pp.doc_type IN (".$id_report_type.")";	
	}
  $pos_purch_sql="select 
			id as id_inv_po,
			doc_date,     
			doc_no,
			mdoc_no,
			id_inv_items,
			item_code,
			name as item_description,
			sum(qty) as qty,
			sum(ordered_qty) as ordered_qty,
			sum(bal_qty) as bal_qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub,
			id_mst_party_supplier,
			rate_per_main_unit as item_amount,
			doc_type
			from 
(
    
			select pp.id,pp.doc_date,pp.doc_no,pp.mdoc_no,ppp.id_inv_items,inv.name,  ppp.id as id_purch_detail,inv.item_code,
			ppp.qty, ppp.ordered_qty,ppp.bal_qty,ppp.rate_per_main_unit, ppp.discount_amount,((ppp.item_amount)-ppp.discount_amount)  as  total,
			
			inv.id as id_item,
			inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,pp.id_mst_party_supplier
			,pp.doc_type
			FROM inv_po  pp
			LEFT JOIN inv_po_details ppp ON ppp.id_inv_po=pp.id
					
			INNER JOIN inv_items inv ON inv.id=ppp.id_inv_items
			
			WHERE   pp.id_shop= '".addslashes($_SESSION['shop'])."' 
			$SqlRepDocTypeConn
			$SqlRepDateConn	
  
  			order by inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.name
			
    ) as purch_rpt

WHERE id_inv_items!=0 $SqlRepConn
group by doc_no,id_inv_items $SqlGroupByConn order by $SqlOrderByConn $SqlOrderByConnType $SqlOrderByConn22  $SqlOrderByConnType22 
 ";
//sql for voucher report
 //echo '1 :'.$pos_purch_sql;
	 //"SELECT * FROM ".TBL_INV_ITEMS."   WHERE id_shop= '".addslashes($_SESSION['shop'])."' and status = '1' AND id_mst_attributes_item_type='".$id_item_type."' AND id IN(".$id_iteam_purch.")  order by id_mst_attributes_group_main,id_mst_attributes_group_sub";
		//echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$DatewiseArray=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 if($id_report_type!=''){
	
		$maingroupName	=strtoupper(selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_main' AND  `id` = '".$posPurchResult->id_mst_attributes_group_main."'"));
		
	
	}  
	
	$maingroupName=$posPurchResult->mdoc_no;
	 $party_name	=strtoupper(selectColumn(TBL_PARTY,'company_name'," WHERE status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$posPurchResult->id_mst_party_supplier."'"));
		$doc_type = 'Group';selectField(APP_DOCTYPE,'name','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect);   
		$doc_name= selectField(APP_DOCTYPE,'name','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect); 
        		$id_doc_type= selectField(APP_DOCTYPE,'id_doc_type','WHERE id_doc_type="'.$posPurchResult->doc_type.'"',$appConnect); 



		
		
		 //$DatewiseArray['Report']['id_mst_attributes_group_main'][]=$maingroupName;
		 //$doc_type	=$posPurchResult->doc_type;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['id_inv_po']=$posPurchResult->id_inv_po;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['party_name']=$party_name;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['mdoc_no'] =($posPurchResult->mdoc_no);
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_date'] =$posPurchResult->doc_date;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['grandtotal'] +=$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['qty'] +=$posPurchResult->qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['ordered_qty'] +=$posPurchResult->ordered_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['bal_qty'] +=$posPurchResult->bal_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_name'] =$doc_name;



		 //akm starts
		 		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['id_doc_type'] =$id_doc_type;





		// $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_name'] =$pageURL;;

	
		 
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['id_mst_attributes_group_sub']=$posPurchResult->id_mst_attributes_group_sub;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['id_inv_items']=$posPurchResult->id_inv_items;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['name'] =ucfirst($posPurchResult->item_description);
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['item_code'] =$posPurchResult->item_code;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['grandtotal'] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['item_amount'] =$posPurchResult->item_amount;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['qty'] =$posPurchResult->qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['ordered_qty'] =$posPurchResult->ordered_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['bal_qty'] =$posPurchResult->bal_qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['mdoc_no'] =$posPurchResult->mdoc_no;
		 $DatewiseArray['Report'][$doc_type][$maingroupName][$posPurchResult->id_inv_items]['doc_date'] =$posPurchResult->doc_date;
		
	 }
	
	
	
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>

	?>
    <script>$(function() {});
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


$content .= '</style>';


		 		//selectColumn(APP_SUB_MENU,'name'," WHERE status='1' and type='2' AND id='".$id_report_type."'");
				
		foreach($DatewiseArray['Report'] as $doc=>$DatewiseArrayStep){
					
	   
 // echo $headerColor = 'hi';
		$headerColor = selectField(APP_COLOR_CONFIG,'report_header_color','',$appConnect); 
		$headerTextColor = selectField(APP_COLOR_CONFIG,'report_header_text_color','',$appConnect); 
		$titleColor = selectField(APP_COLOR_CONFIG,'report_title_color','',$appConnect); 
		$titleTextColor = selectField(APP_COLOR_CONFIG,'report_title_text_color','',$appConnect); 
		$subtitleColor = selectField(APP_COLOR_CONFIG,'report_subtitle_color','',$appConnect); 
		$subtitleTextColor = selectField(APP_COLOR_CONFIG,'report_subtitle_text_color','',$appConnect); 

					
			$content .= '<table class="table table-striped text-center">';
	$content .= '<tr><th colspan="6" style="border-right:1px solid #0093c7 ; vertical-align:central;text-align:left;color:'.$headerTextColor.';background-color:'.$headerColor.';font-size:16px !important"><b>  Report Period '.$date.' Order By '.$OrderDiplay.''.$OrderDiplay22.' </b></th>
	<th  colspan="4" style="text-align:right;color:'.$headerTextColor.';background-color:'.$headerColor.'; font-size:13px !important"><b> Report Date: '.date('d-m-Y H:m:i').' </b></th></tr>';
	

		$content .= '</table>';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000; >';
		
		
		$content .= '<tr  '.$listTagClass.' style="color:#ooo !important;font-size:14px !important; font-weight:600;" >';				
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:5px;">SNo </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:25px;">Type </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:40px;">Party Name </td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Doc No </td>';	
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Doc Date </td>';			
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:10px;">Item Code</td>
				<td colspan="0" style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:left;width:40px;">Item Name</td>';
				
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Balance Qty</td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Item Amount</td>';
				$content .= '<td style="background-color:'.$titleColor.';color:'.$titleTextColor.';text-align:right;width:15px;">Amount</td>
				</tr>';
	

	
		
		
		
	$i=1;	
	foreach($DatewiseArrayStep as $id_main=>$subindexvalue){
		//Main Group=======================>
			
	////	echo $id_main;
	//debugData($subindexvalue);
	$k=1;
	$count=count($subindexvalue)-1;
	
		foreach($subindexvalue as $id_subindex=>$data){
			
			//echo $id_subindex;
			
			//debugData($data);
//Goods Receipt Note
if($data['id_doc_type']==4){			 	
            $PageURL="editPurch.php";
            $session=4;
            $submenu=100;
            $doc_type=4;
		 }
	//purchase bill
 if($data['id_doc_type']==5){
            $PageURL="editPurch.php";
            $session=5;
            $submenu=101;
            $doc_type=5;
		 }
	//store issue note
	 if($data['id_doc_type']==6){
            $PageURL="editStoreIssueNote.php";
            $submenu=102;
            $session=6;
            $doc_type=6;      

		}
// debit note
if($data['id_doc_type']==8){
            $PageURL="editDebit.php";
            $session=8;
            $submenu=182;
            $doc_type=8;      

		 }
//Physical Stock
if($data['id_doc_type']==9){
            $PageURL="editPhysicalStock.php";
            $session=9;
            $submenu=103;
            $doc_type=9;
            

		 }
 if($data['id_doc_type']==12){
 	//Direct Purchase
            $PageURL="editPurch.php";
            $session=12;
            $submenu=181;
            $doc_type=12;
            

		 }


			if('Heading-'.$id_main==$id_subindex){?>
            <?php
			$content .= '<tr '.$listTagClass.' style="background-color:'.$subtitleColor.';color:#ooo !important;font-size:12px !important; font-weight:600">';				
				$content .= '<td style="text-align:left;">'.$i++.'</td>';
				$content .= '<td style="text-align:left;">'.$data['doc_name'].'</td>';
				$content .= '<td  style=text-align:left;width:40px;"> '.$data['party_name'].'</td>';

				$content .= '<td style="text-align:left;width:10px;"><a href="'.$PageURL.'?eId='.encryptor(encrypt, $data['id_inv_po']).'&session='.$session.'&submenu='.$submenu.'&action=edit&page=&doc_type='.$doc_type.'&print=0"  target="_blank">'.$data['mdoc_no'].'</a></td>';	
				$content .= '<td style="text-align:left;width:10px;"> '.date('d-m-Y', strtotime($data['doc_date'])).'</td>';
				
				$content .= '<td  colspan="5" style=text-align:right;width:34px;">'.$data['grandtotal'].'</td>
				</tr>';
			}else{
				$content .= '<tr   '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				
				if($k==1){
				   $content .= '<td     colspan="5" rowspan="'.($count).'" style="text-align:center;"></td>';
				}
				$k++;
				$content .= '<td style="text-align:left;width:10px;">'.$data['item_code'].'</td>';
				$content .= '<td style="text-align:left;width:50px;">'.$data['name'].'</td>';	
				
				
				$content .= '<td style="text-align:right;width:50px;">'.$data['bal_qty'].'</td>';
				$content .= '<td style="text-align:right;width:50px;">'.$data['item_amount'].'</td>';
				$content .= '<td style=text-align:right;width:34px;">'.$data['grandtotal'].'</td>
				</tr>';
				
				
			}
						
				
				
			
			}
		
		
		}
		
		
	
			
			
		$content .= '</table>';
		
}
		//echo $content;
//		die;
		$date=date('d-m-yy');
if($id_report_type==196){		
$Filename='PurchaseOrderPendingReport_'.$date;
}else{
$Filename='PurchaseOrderPendingReport_'.$date;	
	}

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