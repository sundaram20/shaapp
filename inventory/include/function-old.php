<?php


function InventoryStockBalanceReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){	
	global $connNew;$contentstyle='';
	global $appConnect;
	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;


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
	
	$asd =	" AND p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	
	
		
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
	}
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
	  
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
}
  	  if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($SearchDate[0]))."'";
	
	
		
	}
  	  $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,pp.main_unit,
			
					
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `issue_qty`,
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `issue_item_amount`,
			
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_issue_qty`,
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_issue_item_amount`
			
			
			FROM inv_purch_details as pp
			LEFT JOIN inv_items inv ON inv.id=pp.id_inv_items
			LEFT JOIN inv_purch p ON p.id=pp.id_inv_purch
			
			
			WHERE pp.id>0 
			$SqlRepDateConn	and inv.id_mst_attributes_item_type='17'
			GROUP by pp.id_inv_items
			
    ";
	//GROUP BY pp.id_inv_items
	
		//echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 
		 $OpenaningQty				=($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty);
		 $OpenaningItemAmount		 =($posPurchResult->opening_receipt_item_amount-$posPurchResult->opening_issue_item_amount);
		 
		 $BalanceQty				  =(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty);
		 $BalanceItemAmount		   =(($OpenaningItemAmount+$posPurchResult->receipt_item_amount)-$posPurchResult->issue_item_amount);
		 
		$maingroupName=$posPurchResult->id_inv_items;
		 
		 $DatewiseArray['Report'][$maingroupName]['id_inv_items']=$posPurchResult->id_inv_items;
		 $DatewiseArray['Report'][$maingroupName]['name'] =ucfirst($posPurchResult->name);
		 $DatewiseArray['Report'][$maingroupName]['item_code'] =$posPurchResult->item_code;
		 //$DatewiseArray['Report']['grandtotal'][$maingroupName]['item2'][] =$posPurchResult->grandtotal;
		 $DatewiseArray['Report'][$maingroupName]['qty']=$posPurchResult->qty;	
		 $DatewiseArray['Report'][$maingroupName]['main_unit'] =$posPurchResult->main_unit;
		 
		 $DatewiseArray['Report'][$maingroupName]['receipt_qty']=$posPurchResult->receipt_qty;	
		 $DatewiseArray['Report'][$maingroupName]['receipt_item_amount'] =$posPurchResult->receipt_item_amount;
		 
		 
		 $DatewiseArray['Report'][$maingroupName]['issue_qty']=$posPurchResult->issue_qty;	
		 $DatewiseArray['Report'][$maingroupName]['issue_item_amount'] =$posPurchResult->issue_item_amount;
		 
		 
		 $DatewiseArray['Report'][$maingroupName]['opening_qty']=$OpenaningQty;	
		 $DatewiseArray['Report'][$maingroupName]['opening_item_amount'] =$OpenaningItemAmount;
		 
		 
		 $DatewiseArray['Report'][$maingroupName]['balance_qty']= $BalanceQty;	
		 $DatewiseArray['Report'][$maingroupName]['balance_item_amount'] =$BalanceItemAmount;
		 
		 
		 $DatewiseArray['Report'][$maingroupName]['Rate']=round($BalanceItemAmount/$BalanceQty,2);	
		 
		 
		 
		 	
		
	 }
	
	
	//$MainGroup=array_unique($DatewiseArray['Report']['id_mst_attributes_group_main']);
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>
	?>
    
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
				
				
			$content .= '<table class="table table-striped text-center">';
	$content .= '<tr style="vertical-align:central;text-align:center;"><th colspan="4" style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b> Stock Balance  Report Period '.$date.' Order By '.$OrderDiplay.' </b></th>
	<th  style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:13px !important"><b> Report Date: '.date('d-m-Y H:m:i').' </b></th></tr>';
	
if($showiMainGroupName!=''){	
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Main Group : </b>'.ucwords(strtolower($showiMainGroupName)).'  </th></tr>';
}if($showSubGroupName!=''){
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Sub Group : </b>'.ucwords(strtolower($showSubGroupName)).'  </th></tr>';
}if($showitemName!=''){		
	$content .= '<tr style="vertical-align:left;text-align:left;"><th colspan="5" style="vertical-align:left;text-align:left; font-size:13px !important"><b> Item Name : </b>'.ucwords(strtolower($showitemName)).'  </th></tr>';
}
		$content .= '</table>';
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#c2d69a;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>';
		
		$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';	
		$content .= '<th width="60px;"  ><b>S.no</b></th>';	
		$content .= '<th width="140px;"><b>Item Code</b></th>';
		$content .= '<th ><b>Item Description</b></th>
		<th style="width:200px;text-align: center; " ><b>Unit</b></th>
		<th style="width:200px;text-align: center;  "><b>Min</b></th><th style="width:200px;text-align: center;  "><b>Stock</b></th><th style="width:200px;text-align: center;  "><b>Value</b></th>';
		$content .= '</tr>';
		
	
	
	
	
	$GrandTotalQTY=0;
	$GrandTotalAmount=0;
	
		$colspa1=3;
		
		
		
		
		
		
	foreach($DatewiseArray as $subindexvalue){
		//Main Group=======================>
			
		/*$content .= '<tr class="line" style="'.$BackgroundColorMain.'background-color:#c2d69a;color:#ooo !important;font-size:16px !important;">
			<th  colspan="'.$colspa1.'" ><b>'.$maingroupName.'</b></th>
			</tr>';*/
			
		$MainGroupTotalQTY=0;
		$MainGroupTotalAmount=0;
		$subgroupInc=1;	$contentSubGroup='';$contentItem='';
		$i=1;
		foreach($subindexvalue as $data){
			
			
			
			$k=0;
			
			
			$subgroupTotalQTY=0;
			$SubGroupTotalAmounts=0;
				//debugData($data);
				
				
				$qty=		number_format($DatewiseArray['Report']['qty'][$id_main][$id_subindex][$k],2);
				$grandtotal=	number_format($DatewiseArray['Report']['grandtotal'][$id_main][$id_subindex][$k],2);
					
				$contentItem .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentItem .= '<td style="text-align:center;width:200px;">'.$i.'</td>';
				$contentItem .= '<td  style="text-align:left;">'.$data['item_code'].'</td>';
				
				
						$contentItem .= '<td style="text-align:left;">'.strtoupper($data['name']).'</td>';
						
				$contentItem .= '<td  style="text-align:left;">'.$data['main_unit'].'</td>';
				$contentItem .= '<td style="text-align:right;width:200px;">'.$qty.'</td>';
				$contentItem .= '<td style=text-align:right;width:200px;">'.$data['balance_qty'].'</td>';
				$contentItem .= '<td style=text-align:right;width:200px;">'.$data['balance_item_amount'].'</td>';
				$contentItem .= '</tr>';
				
				$i++;
				$k++;
			
			
			$colspa=3;
			$contentSubGroup .= $contentItem;
			$subgroupInc++;	$contentItem='';
			}
		
		
		$content .= $contentSubGroup;	
		//$content .= $content2;
		}
		
		
	
			
			
		$content .= '</table>';
		//echo $content;
//		die;
		$date=date('d-m-yy');

$Filename='StockBalanceReport_'.$date;	
	

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
		
		
	//STOCK STATEMENT REPORT START==============================================>
	
	
	function InventoryStockStatementReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){	
	global $connNew;$contentstyle='';
	global $appConnect;
	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;



$content ='';
		
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	$SearchIDate = explode(' to ',$date);
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($SearchIDate[0]))."'";
	
	
		
	}
  	  $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,		
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `issue_qty`,
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `issue_item_amount`,			
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_issue_qty`,
			sum(case when ( p.doc_type IN ('6')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_issue_item_amount`
			
			
			FROM inv_purch_details as pp
			LEFT JOIN inv_items inv ON inv.id=pp.id_inv_items
			LEFT JOIN inv_purch p ON p.id=pp.id_inv_purch
			
			
			WHERE pp.id>0 
			$SqlRepDateConn	and inv.id_mst_attributes_item_type='17'
			GROUP by pp.id_inv_items
			
    ";
	//GROUP BY pp.id_inv_items
	
		//echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$GrandTotal=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 
		 $OpenaningQty				=($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty);
		 $OpenaningItemAmount		 =($posPurchResult->opening_receipt_item_amount-$posPurchResult->opening_issue_item_amount);
		 
			$RateQTY	=	 ($OpenaningQty+$posPurchResult->receipt_qty);
		 $Rate			= $RateQTY>0?round(($OpenaningItemAmount+$posPurchResult->receipt_item_amount)/($OpenaningQty+$posPurchResult->receipt_qty),2):0;
		 
		 $BalanceQty				  =(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty);
		 $BalanceItemAmount		   =($BalanceQty*$Rate);
		 
		 
		 
		 //round($posPurchResult->receipt_item_amount/$posPurchResult->receipt_qty,2);
		 
		 
		$id_inv_items=$posPurchResult->id_inv_items;
		 
		 $DatewiseArray['Report'][$id_inv_items]['id_inv_items']=$posPurchResult->id_inv_items;
		 $DatewiseArray['Report'][$id_inv_items]['name'] =ucfirst($posPurchResult->name);
		 $DatewiseArray['Report'][$id_inv_items]['item_code'] =$posPurchResult->item_code;
		 
		 $DatewiseArray['Report'][$id_inv_items]['qty']=$posPurchResult->qty;	
		 $DatewiseArray['Report'][$id_inv_items]['main_unit'] =$posPurchResult->main_unit;
		 
		 $DatewiseArray['Report'][$id_inv_items]['receipt_qty']=$posPurchResult->receipt_qty;	
		 $DatewiseArray['Report'][$id_inv_items]['receipt_item_amount'] =$posPurchResult->receipt_item_amount;
		 
		 
		 $DatewiseArray['Report'][$id_inv_items]['issue_qty']=$posPurchResult->issue_qty;	
		 $DatewiseArray['Report'][$id_inv_items]['issue_item_amount'] =($Rate*$posPurchResult->issue_qty);//$posPurchResult->issue_item_amount;
		 
		 
		 $DatewiseArray['Report'][$id_inv_items]['opening_qty']=$OpenaningQty;	
		 $DatewiseArray['Report'][$id_inv_items]['opening_item_amount'] =$OpenaningItemAmount;
		 
		 
		 $DatewiseArray['Report'][$id_inv_items]['balance_qty']= $BalanceQty;	
		 $DatewiseArray['Report'][$id_inv_items]['balance_item_amount'] =$BalanceItemAmount;		 
		 
		 $DatewiseArray['Report'][$id_inv_items]['Rate']=$Rate;	
		 
		 
		 //GRand Total Start ========================================================
		 $GrandTotal['GrandTotal']['receipt_qty'] +=$posPurchResult->receipt_qty;
		 $GrandTotal['GrandTotal']['receipt_item_amount'] +=$posPurchResult->receipt_item_amount;
		 
		 
		 $GrandTotal['GrandTotal']['issue_qty'] +=$posPurchResult->issue_qty;
		 $GrandTotal['GrandTotal']['issue_item_amount'] +=($Rate*$posPurchResult->issue_qty);
		 
		 $GrandTotal['GrandTotal']['opening_qty'] +=$OpenaningQty;
		 $GrandTotal['GrandTotal']['opening_item_amount'] +=$OpenaningItemAmount;
		 
		 $GrandTotal['GrandTotal']['Rate'] +=$Rate;
		 $GrandTotal['GrandTotal']['balance_qty'] +=$BalanceQty;
		 $GrandTotal['GrandTotal']['balance_item_amount'] +=$BalanceItemAmount;
		 //GRand Total END  ========================================================
		
	 }
	//debugData($GrandTotal);
	
	//$MainGroup=array_unique($DatewiseArray['Report']['id_mst_attributes_group_main']);
	//debugData($DatewiseArray);
//	echo '<pre>';print_r($DatewiseArray);	echo '</pre>';//die;
	
	//HTML View START==============================================================================>
	?>
    
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
				
				
		
			
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center; color: #000;   background-color:#c2d69a;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>'; 
		$content .= '<tr style="font-size:16px !important;" ><th colspan="12"  style="vertical-align:central;text-align:center;color:#fff;background-color:#4f6228; font-size:16px !important"><b> Stock Statement  Report Period '.$date.' </b></th></tr>';
		
		
		$content .= '<tr style="font-size:12px !important;" >';	
		$content .= '<th ><b>S.no</b></th>';	
		$content .= '<th><b>Item Code</b></th>';
		$content .= '<th style=""><b>Item Description</b></th>
		<th style="text-align: center; " ><b>Rate</b></th>
		
		
		<th colspan="2" style="text-align: center; " ><b>Opening</b></th>
		<th  colspan="2"  style="text-align: center; " ><b>Receipt</b></th>
		<th  colspan="2"  style="text-align: center; " ><b>Issue</b></th>
		<th  colspan="2" style="text-align: center; " ><b>Balance</b></th>
		
		';
		$content .= '</tr>';
		
		$content .= '<tr style="font-size:12px !important;" >';	
		$content .= '<th   ></th>';	
		$content .= '<th></th>';
		$content .= '<th ></th>
		<th ></th>
		
		
		<th ><b>Qty</b></th>
		<th ><b>Amount</b></th>
		<th ><b>Qty</b></th>
		<th ><b>Amount</b></th>
		<th ><b>Qty</b></th>
		<th ><b>Amount</b></th>
		<th ><b>Qty</b></th>
		<th ><b>Amount</b></th>
		';
		$content .= '</tr>';
	
	
	
	$GrandTotalQTY=0;
	$GrandTotalAmount=0;
	
		$colspa1=3;
		
		
		
		
		
		
	foreach($DatewiseArray as $subindexvalue){
		//Main Group=======================>
			
		/*$content .= '<tr class="line" style="'.$BackgroundColorMain.'background-color:#c2d69a;color:#ooo !important;font-size:16px !important;">
			<th  colspan="'.$colspa1.'" ><b>'.$maingroupName.'</b></th>
			</tr>';*/		
		
		$i=1;
		foreach($subindexvalue as $data){
			$k=0;
			
			
			$subgroupTotalQTY=0;
			$SubGroupTotalAmounts=0;
				//debugData($data);				
				
								
				$contentItem .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color:#fff;">';				
				$contentItem .= '<td style="text-align:center;width:200px;">'.$i.'</td>';
				$contentItem .= '<td  style="text-align:left;">'.$data['item_code'].'</td>';
				
				
				$contentItem .= '<td style="text-align:left;">'.strtoupper($data['name']).'</td>';						
				$contentItem .= '<td  style="text-align:right;">'.$data['Rate'].'</td>';
				$contentItem .= '<td style="text-align:right;">'.$data['opening_qty'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['opening_item_amount'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['receipt_qty'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['receipt_item_amount'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['issue_qty'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['issue_item_amount'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['balance_qty'].'</td>';
				$contentItem .= '<td style=text-align:right;">'.$data['balance_item_amount'].'</td>';
				$contentItem .= '</tr>';
				
				$i++;
				$k++;
			
			
			$colspa=3;
			$contentSubGroup .= $contentItem;
			$subgroupInc++;	$contentItem='';
			}
		
		
		$content .= $contentSubGroup;	
		
		}
		//GRAND TOTAL START============================================
		//foreach($GrandTotal as $subindexvalue){
		
		foreach($GrandTotal as $data){
			
				//debugData($data);				
				
								
				$content .= '<tr  '.$listTagClass.' style="border:1px solid:font-size:11px !important;color: #000;   background-color: #c2d69a;">';				
				$content .= '<td style="text-align:center;width:200px;" colspan="3">GRAND TOTAL</td>';
										
				$content .= '<td  style="text-align:right;">'.$data['Rate'].'</td>';
				$content .= '<td style="text-align:right;">'.$data['opening_qty'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['opening_item_amount'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['receipt_qty'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['receipt_item_amount'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['issue_qty'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['issue_item_amount'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['balance_qty'].'</td>';
				$content .= '<td style=text-align:right;">'.$data['balance_item_amount'].'</td>';
				$content .= '</tr>';
				
				
			
			
			
			//}
		
		
			
		
		}
		//GRAND TOTAL END ====================================================
		
	
			
			
		$content .= '</table>';
		//echo $content;
//		die;
		$date=date('d-m-yy');

$Filename='StockStatementReport_'.$date;	
	

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