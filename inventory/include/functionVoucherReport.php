<?php




function consolidatedItemWiseReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport,$id_mst_party_supplier){

    

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
	
if($id_mst_party_supplier!=''){
	$SqlRepConn .=	" AND id_mst_party_supplier IN (".$id_mst_party_supplier.")";	
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
			id as id_inv_purch,
			doc_date,     
			doc_no,
			mdoc_no,
			id_inv_items,
			item_code,
			name as item_description,
			sum(qty) as qty,
			sum(total)as grandtotal,
			id_mst_attributes_group_main,
			id_mst_attributes_group_sub,
			id_mst_party_supplier,
			rate_per_main_unit as item_amount,
			doc_type,
			supplier_ref_no,
			supplier_date
			from 
(
    
			select pp.id,pp.doc_date,pp.doc_no,pp.mdoc_no,ppp.id_inv_items,inv.name,  ppp.id as id_purch_detail,inv.item_code,
			ppp.qty, ppp.rate_per_main_unit, ppp.discount_amount,((ppp.item_amount)-ppp.discount_amount)  as  total,
			
			inv.id as id_item,
			inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,pp.id_mst_party_supplier
			,pp.doc_type,pp.supplier_ref_no,
			pp.supplier_date
			FROM inv_purch  pp
			LEFT JOIN inv_purch_details ppp ON ppp.id_inv_purch=pp.id
					
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
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['id_inv_purch']=$posPurchResult->id_inv_purch;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['party_name']=$party_name;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['mdoc_no'] =($posPurchResult->mdoc_no);
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_date'] =$posPurchResult->doc_date;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['grandtotal'] +=$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['qty'] +=$posPurchResult->qty;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['doc_name'] =$doc_name;
		$DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['supplier_ref_no'] =$posPurchResult->supplier_ref_no;
		 $DatewiseArray['Report'][$doc_type][$maingroupName]['Heading-'.$maingroupName]['supplier_date'] =$posPurchResult->supplier_date;



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

				$content .= '<td style="text-align:left;width:10px;"><a href="'.$PageURL.'?eId='.encryptor(encrypt, $data['id_inv_purch']).'&session='.$session.'&submenu='.$submenu.'&action=edit&page=&doc_type='.$doc_type.'&print=0"  target="_blank">'.$data['mdoc_no'].'</a></td>';	
				$content .= '<td style="text-align:left;width:10px;"> '.date('d-m-Y', strtotime($data['doc_date'])).'</td>';
				if($data['supplier_ref_no']!=''){
				$supplier_ref_no	= $data['supplier_ref_no']!=''?'Supplier Ref No: '.$data['supplier_ref_no']:'';
				$supplier_date ='Supplier Ref Date: '.date('d-m-Y', strtotime($data['supplier_date']));
				}$content .= '<td style="text-align:left;width:10px;"> '.$supplier_ref_no.'</td>';
				$content .= '<td style="text-align:left;width:10px;" > '.$supplier_date.'</td>';
				
				$content .= '<td  colspan="3" style=text-align:right;width:34px;">'.$data['grandtotal'].'</td>
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
$Filename='GoodsReceiptNoteReport_'.$date;
}else{
$Filename='GoodsReceiptNoteReport_'.$date;	
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
		
		
		
function settlementSummaryReport($Date,$id_outlet,$id_shift,$objPHPExcel){
	
	
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

$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':Z'.$con);

$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
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
	  ,max(bill_date_created) as 'bill_date_created'
	  ,id_charges_master
	  ,remark
	  ,doc_no
	  ,id_company
	  ,sc_charges_net_amount
	  ,sc_sgst
	  ,sc_cgst
	  
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
	  ,case when payment_mode='CARD' and id_cardtype=1 then IFNULL(amount,0) else null end as CARD
	  ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE
	  ,case when payment_mode='GIFTVOUCHER' then IFNULL(amount,0) else null end as GIFTVOUCHER
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
			cellColor('C'.$con.':Z'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
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
				cellColor('A'.$con.':C'.$con++,'e0e0e0');
				$con=$con+2;
				
				}
		//SUMMARY DETAILS====================================================
	
	
		cellColor('A'.$con.':Z'.$con,'ecf0f5');
		$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A'.$con.':Z'.$con);
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
			->setCellValue('Z'.$con, 'REMARK');									

			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Z'.$con)->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
			$con++;
		}
if($Records->id_mst_outlet!=$mstoutlet2){//Outlet Split


		
		if($count2>1){
			$con=$con+1;
			cellColor('C'.$con.':Z'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
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
				->setCellValue('E'.$con, $resultAttrbuteTable->field_value.'('.$Records->pax.')')
				->setCellValue('F'.$con, $Records->outlet_Name)
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
				->setCellValue('Z'.$con, $remarks);
				//->setCellValue('Z'.$con, $Records->FeildValue)
				//->setCellValue('AA'.$con, $Records->CreatedDate)
				//->setCellValue('AA'.$con, $Records->CreatedDate);
		$objPHPExcel->getActiveSheet()->getStyle('A'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
		
		
		//SUMMARY DETAILS====================================================
		
		//$sub_total_Card +=$Records->Card;
		//$sub_total_Company +=$Records->Company;
		
		$sub_total_Cash +=$Records->Cash;
		if($Records->Card>0){
		$sub_total_Card[selectColumn(TBL_CHARGES,'name','WHERE id="'.$Records->id_charges_master.'" AND id_shop="'.$_SESSION['shop'].'" ')] +=$Records->Card;
		}
		if($Records->Company>0){
		$sub_total_Company[$company] +=$Records->Company;
		}
		
		$sub_total_Cheque +=$Records->Cheque;
		$sub_total_OnlineTransfer +=$Records->OnlineTransfer;
		$grant_total_amount +=$Records->grant_total_amount;
		
		
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
			cellColor('C'.$con.':Z'.$con,'e0e0e0');
			$objPHPExcel->getActiveSheet()->getStyle('C'.$con.':Z'.$con)->applyFromArray($styleThinBlackBorderOutline);
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

//echo $sql;
	
	
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




function posSalesRegisterReport($Date,$id_outlet,$id_shift,$objPHPExcel){
	

	
	global $connNew;
	global $objPHPExcel;
	
	
	
		
	if($Date != ''){
		$DateExplode = explode(' to ',$_REQUEST['datefilter']);
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
			->setCellValue('L'.$con, 'Surcharge');	
				
$con++;
$SalesRegisterArray=array();
 $SQLSalesReportPayment="select p.id ,p.id_attribute_shift ,

case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'CASH' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then 'CARD'
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'CHEQUE'
    when payment_mode='GIFTVOUCHER' and IFNULL(amount,0)>0 then 'GIFTVOUCHER'
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then 'ONLINETRANSFER'
     when payment_mode='COMPANY' and IFNULL(amount,0)>0 then 'COMPANY'
    else 0 end
    as payment_type ,
    
    case when payment_mode='CASH' and IFNULL(amount,0)>0 then 'Cash Sales' 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then id_charges_master
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then 'Cash Sales'
    when payment_mode='GIFTVOUCHER' and IFNULL(amount,0)>0 then remark
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then id_charges_master  	  
    else 0 end
    as payment_remarks ,
	
	
	
	
	case when payment_mode='COMPANY' and IFNULL(amount,0)>0 then id_company
    else 0 end
    as id_company ,
	
	
	case when payment_mode='CASH' and IFNULL(amount,0)>0 then amount 
	when payment_mode='CARD' and IFNULL(amount,0)>0 then amount
    when payment_mode='CHEQUE' and IFNULL(amount,0)>0 then amount
    when payment_mode='GIFTVOUCHER' and IFNULL(amount,0)>0 then amount
    when payment_mode='ONLINETRANSFER' and IFNULL(amount,0)>0 then amount
    when payment_mode='COMPANY' and IFNULL(amount,0)>0 then amount
    else 0 end
    as dramount ,

comp.name as 'outlet_Name' ,usr.name ,time as Time ,p.mdoc_no ,p.id_mst_outlet ,p.sub_total_items ,p.sgst_total_items ,p.cgst_total_items ,p.igst_total_items ,p.cess_total_items ,p.vat_total_items ,p.surcharge_total_items ,p.round_off_amount ,p.net_amount_items ,p.grant_total_amount ,p.others_charges_net_amount ,p.discount_amount_additional ,p.total_discount_items ,case when payment_mode='CASH' then IFNULL(amount,0) else null end as CASH ,case when payment_mode='CARD' then IFNULL(amount,0) else null end as CARD ,case when payment_mode='CHEQUE' then IFNULL(amount,0) else null end as CHEQUE ,case when payment_mode='GIFTVOUCHER' then IFNULL(amount,0) else null end as GIFTVOUCHER ,case when payment_mode='ONLINETRANSFER' then IFNULL(amount,0) else null end as ONLINETRANSFER ,case when payment_mode='COMPANY' then IFNULL(amount,0) else null end as COMPANY ,att.field_value ,DATE(p.date_created) as date_created ,p.pax ,p.id_attribute_table,p.discount_amount_additional,p.id_doc_type_configuration
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
	
	
		$doc_type = selectColumn(TBL_PURCH,'doc_type','WHERE id="'.$RecordsSalesReportPayment->id.'" AND id_shop="'.$_SESSION['shop'].'" ');									
	$Payment	=	$RecordsSalesReportPayment->payment_type.$ics++;
	if($RecordsSalesReportPayment->id_company>0){
		$payment_remarks = selectColumn(MST_COMPANY,'name','WHERE id="'.$RecordsSalesReportPayment->id_company.'" AND id_shop="'.$_SESSION['shop'].'" ');
	}else{
		$payment_remarks=$RecordsSalesReportPayment->payment_remarks;
		}
		
		
	if($RecordsSalesReportPayment->payment_type=='CARD' || $RecordsSalesReportPayment->payment_type=='ONLINETRANSFER'){
		$payment_remarks=selectColumn(TBL_CHARGES,'name','WHERE id="'.$RecordsSalesReportPayment->payment_remarks.'" AND id_shop="'.$_SESSION['shop'].'" ');
		}
		
		
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReportPayment->id_doc_type_configuration.'" AND id_shop="'.$_SESSION['shop'].'" ');
	
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
sum((ppd.item_amount*ppd.qty)) as sub_item_amount,p.id_mst_charges_discounts,
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


while($RecordsSalesReport	   =	mysqli_fetch_object($querySalesReport)){
	
	
	$voucher_type=selectColumn(TBL_DOC_TYPE_CONFIG,'doc_name','WHERE id="'.$RecordsSalesReport->id_doc_type_configuration.'" AND id_shop="'.$_SESSION['shop'].'" ');
	
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
	
	
	
	//SERVICE CHarge =================================
	
	
			//("Service Charges 10% :");
			
	if($RecordsSalesReport->sc_charges_net_amount>0){
			$taxMethod_sgst='sc_charges_net_amount';
	
												
	
	$id_service_charge = selectColumn(TBL_OUTLETS,'id_service_charge','WHERE id="'.$RecordsSalesReport->id_mst_outlet.'" AND id_shop="'.$_SESSION['shop'].'" ');
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
				->setCellValue('A'.$con, $SalesRegisterArray5['voucher_type'])
				->setCellValue('B'.$con, $SalesRegisterArray5['mdoc_no']);
			$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(2, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(2, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);	
				//->setCellValue('C'.$con, $InvDate)//trim(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
			$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('D'.$con, $SalesRegisterArray5['Account_Name'])
				->setCellValue('E'.$con, $SalesRegisterArray5['narration'])
				->setCellValue('F'.$con, $SalesRegisterArray5['dramount']>0?$SalesRegisterArray5['dramount']:0)
				->setCellValue('G'.$con, $SalesRegisterArray5['cramount']>0?$SalesRegisterArray5['cramount']:0)
				->setCellValue('H'.$con, $SalesRegisterArray5['ptype'])
				->setCellValue('I'.$con, $SalesRegisterArray5['mdoc_no']);
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValueByColumnAndRow(9, $con, PHPExcel_Shared_Date::PHPToExcel(date('d-m-Y',strtotime($SalesRegisterArray5['date_created']))))
					->getStyleByColumnAndRow(9, $con)
    						->getNumberFormat()->setFormatCode(
        					PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY
							);
				//->setCellValue('J'.$con, date('d-m-Y',strtotime($SalesRegisterArray5['date_created'])))
				$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('K'.$con, $SalesRegisterArray5['assessable_value']>0?$SalesRegisterArray5['assessable_value']:0)
				->setCellValue('L'.$con, 0)
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
	?>