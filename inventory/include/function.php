<?php

function reportStatementAndBalance($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){/*	
	global $connNew;$contentstyle='';
	global $appConnect;
	if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	$SearchIDate = explode(' to ',$date);
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($SearchIDate[0]))."'";
	
	
		
	}
	
  	  $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id_mst_attributes_item_type,		
			inv.min_qty,inv.id_mst_attributes_unit_main,
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `issue_item_amount`,			
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_issue_item_amount`
			
			
			FROM inv_purch_details as pp
			LEFT JOIN inv_items inv ON inv.id=pp.id_inv_items 
			LEFT JOIN inv_purch p ON p.id=pp.id_inv_purch
			
			
			WHERE pp.id>0 and pp.qty!='0' and inv.status='1'
			$SqlRepDateConn	and inv.id_mst_attributes_item_type='17'
			GROUP by pp.id_inv_items
			
    ";
	//GROUP BY pp.id_inv_items
	
		// echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$GrandTotal=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 
		  $sqlsub_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  id='".$posPurchResult->id_mst_attributes_unit_main."'";
           
            $ressub_group = mysqli_query($connNew,$sqlsub_group);
		  
           $rowsub_group = mysqli_fetch_object($ressub_group);
		 
		 
		$attributes_unit_main	=  $rowsub_group->field_value;
		 
		 
		 $OpenaningQty =round($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty,2);
		 
		  $RateQTY	 =	 ($OpenaningQty+$posPurchResult->receipt_qty);
		  
		  $Qty	=	($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty);
		  
		 $Rate =  $Qty!='0'?round(($posPurchResult->opening_receipt_item_amount+$posPurchResult->receipt_item_amount)/($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty),2):'0';
		  
		
		 
		 $opening_issue_item_amount	=($posPurchResult->opening_issue_qty*$Rate);
		 
		 $OpenaningItemAmount		 =$OpenaningQty*$Rate;//($posPurchResult->opening_receipt_item_amount-$opening_issue_item_amount);
		 
		
		 
		 $BalanceQty				  =round(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty,2);
		 $BalanceItemAmount		   =($BalanceQty*$Rate);
		 
		 
		 
		 //round($posPurchResult->receipt_item_amount/$posPurchResult->receipt_qty,2);
		 
		 if($Qty!='0' && ($BalanceQty!='0' ||  $OpenaningQty!='0' || $posPurchResult->issue_qty!='0' || $posPurchResult->receipt_qty!='0')){
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
		 $DatewiseArray['Report'][$id_inv_items]['attributes_unit_main']=$attributes_unit_main;
		 $DatewiseArray['Report'][$id_inv_items]['min_qty']=$posPurchResult->min_qty;
		 
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
	 }
	 return;
	
	
	*/}
function InventoryStockBalanceReport($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport){	
	global $connNew;$contentstyle='';
	global $appConnect;
	$DatewiseArray = array();
	//echo '==================='.$id_report_type;
//echo '.= =================='.$report_show;die;
//echo '=======================>'.$id_main_group;
//echo '=======================>'.$id_sub_group;
//echo '=======================>'.$id_items;

 //$y = reportStatementAndBalance($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport);

$content ='';
		
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	$SearchIDate = explode(' to ',$date);
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($SearchIDate[0]))."'";
	
	
		
	}

if($id_main_group!=''){
	$SqlRepConn .=	" AND inv.id_mst_attributes_group_main IN  (".$id_main_group.")";	
	}


	
if($id_sub_group!=''){
	$SqlRepConn .=	" AND inv.id_mst_attributes_group_sub  IN (".$id_sub_group.")";	
	}
if($id_items!=''){
	$SqlRepConn .=	" AND inv.id IN (".$id_items.")";	
	}	

  	  $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id_mst_attributes_item_type,		
			inv.min_qty,inv.id_mst_attributes_unit_main,
			mg.field_value AS main_group_name,
			sg.field_value AS sub_group_name,
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `issue_item_amount`,			
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_issue_item_amount`
			
			
			FROM inv_purch_details as pp
			LEFT JOIN inv_items inv ON inv.id=pp.id_inv_items 
			LEFT JOIN inv_purch p ON p.id=pp.id_inv_purch
			LEFT JOIN ".TBL_ATTRIBUTES." mg ON mg.id=inv.id_mst_attributes_group_main
			LEFT JOIN ".TBL_ATTRIBUTES." sg ON sg.id=inv.id_mst_attributes_group_sub
			
			WHERE pp.id>0 and pp.qty!='0' and inv.status='1'
			$SqlRepDateConn	and inv.id_mst_attributes_item_type!='16' ".$SqlRepConn." 
			GROUP by pp.id_inv_items
			
    ";
	//GROUP BY pp.id_inv_items
	
	// echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$GrandTotal=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 
		  $sqlsub_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  id='".$posPurchResult->id_mst_attributes_unit_main."'";
           
            $ressub_group = mysqli_query($connNew,$sqlsub_group);
		  
           $rowsub_group = mysqli_fetch_object($ressub_group);
		 
		 
		$attributes_unit_main	=  $rowsub_group->field_value;
		 
		 
		 $OpenaningQty =round($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty,2);
		 
		  $RateQTY	 =	 ($OpenaningQty+$posPurchResult->receipt_qty);
		  
		  $Qty	=	($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty);
		  
		 $Rate =  $Qty!='0'?round(($posPurchResult->opening_receipt_item_amount+$posPurchResult->receipt_item_amount)/($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty),2):'0';
		  
		
		 
		 $opening_issue_item_amount	=($posPurchResult->opening_issue_qty*$Rate);
		 
		 $OpenaningItemAmount		 =$OpenaningQty*$Rate;//($posPurchResult->opening_receipt_item_amount-$opening_issue_item_amount);
		 
		
		 
		 $BalanceQty				  =round(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty,2);
		 $BalanceItemAmount		   =($BalanceQty*$Rate);
		 
		 
		 
		 //round($posPurchResult->receipt_item_amount/$posPurchResult->receipt_qty,2);
		 
		 if($Qty!='0' && ($BalanceQty!='0' ||  $OpenaningQty!='0' || $posPurchResult->issue_qty!='0' || $posPurchResult->receipt_qty!='0')){
		$id_inv_items=$posPurchResult->id_inv_items;
		 
		 $DatewiseArray['Report'][$id_inv_items]['id_inv_items']=$posPurchResult->id_inv_items;
		 $DatewiseArray['Report'][$id_inv_items]['name'] =ucfirst($posPurchResult->name);
		 $DatewiseArray['Report'][$id_inv_items]['item_code'] =$posPurchResult->item_code;
		 $DatewiseArray['Report'][$id_inv_items]['main_group'] = ucwords(strtolower($posPurchResult->main_group_name));
		 $DatewiseArray['Report'][$id_inv_items]['sub_group']  = ucwords(strtolower($posPurchResult->sub_group_name));
		 $DatewiseArray['Report'][$id_inv_items]['main_group_id'] = $posPurchResult->id_mst_attributes_group_main;
		 $DatewiseArray['Report'][$id_inv_items]['sub_group_id']  = $posPurchResult->id_mst_attributes_group_sub;
		 
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
		 $DatewiseArray['Report'][$id_inv_items]['attributes_unit_main']=$attributes_unit_main;
		 $DatewiseArray['Report'][$id_inv_items]['min_qty']=$posPurchResult->min_qty;
		 
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
	 }
	 if(($id_order_by=='main' || $id_order_by=='sub') && !empty($DatewiseArray['Report'])){
    $gid   = ($id_order_by=='main') ? 'main_group_id' : 'sub_group_id';
    $gname = ($id_order_by=='main') ? 'main_group'    : 'sub_group';
    $grouped = array();
    foreach($DatewiseArray['Report'] as $row){
        $k = $row[$gid];
        if(!isset($grouped[$k])){
            $grouped[$k] = array(
                'group_name'          => ($row[$gname]!='' ? $row[$gname] : 'Not Assigned'),
                'balance_qty'         => 0,
                'balance_item_amount' => 0
            );
        }
        $grouped[$k]['balance_qty']         += $row['balance_qty'];
        $grouped[$k]['balance_item_amount'] += $row['balance_item_amount'];
    }
    uasort($grouped, function($a,$b){ return strcmp($a['group_name'],$b['group_name']); });
    $DatewiseArray['Report'] = $grouped;
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
	
		
			
		$showItem = ($id_order_by=='');
$showMain = ($id_order_by=='' || $id_order_by=='main');
$showSub  = ($id_order_by=='' || $id_order_by=='sub');
$totalCols = $showItem ? 10 : 4;

$th  = 'border:1px solid #000;background-color:'.$titleColor.';color:'.$titleTextColor.';';
$emp = '<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';"></th>';

$content .= '<table class="table table-striped" style="margin-bottom:0px;border:1px;width:100%;text-align:center;color:#000;">';
$content .= '<tr style="font-size:16px !important;"><th colspan="'.$totalCols.'" style="vertical-align:central;text-align:center;color:'.$headerTextColor.';background-color:'.$headerColor.';font-size:16px !important"><b> Stock Balance Report Period '.$date.' </b></th></tr>';

// header row 1
$content .= '<tr id="head1" style="color:#000 !important;font-size:12px !important;font-weight:600">';
$content .= '<th style="'.$th.'text-align:left;width:10px;"><b>S.no</b></th>';
if($showItem){
    $content .= '<th style="'.$th.'text-align:left;width:100px;"><b>Item Code</b></th>';
    $content .= '<th style="'.$th.'text-align:left;width:100px;"><b>Item Name</b></th>';
}
if($showMain){ $content .= '<th style="'.$th.'text-align:left;width:100px;"><b>Main Group</b></th>'; }
if($showSub){  $content .= '<th style="'.$th.'text-align:left;width:100px;"><b>Sub Group</b></th>'; }
if($showItem){
    $content .= '<th style="'.$th.'text-align:center;width:34px;"><b>Unit</b></th>';
    $content .= '<th style="'.$th.'text-align:center;width:34px;"><b>Min Qty</b></th>';
    $content .= '<th style="'.$th.'text-align:center;width:34px;"><b>Rate</b></th>';
}
$content .= '<th colspan="2" style="'.$th.'text-align:center;"><b>Balance</b></th>';
$content .= '</tr>';

// header row 2
$content .= '<tr id="head2" style="font-size:12px !important;">';
for($c=0; $c < $totalCols-2; $c++){ $content .= $emp; }
$content .= '<th style="'.$th.'"><b>Qty</b></th><th style="'.$th.'"><b>Amount</b></th>';
$content .= '</tr>';

// body
$i = 1;
if(!empty($DatewiseArray['Report'])){
    foreach($DatewiseArray['Report'] as $data){
        $content .= '<tr style="font-size:11px !important;color:#000;background-color:#fff;">';
        $content .= '<td style="border:1px solid #000;text-align:center;width:50px;">'.$i.'</td>';
        if($showItem){
            $content .= '<td style="border:1px solid #000;text-align:left;">'.$data['item_code'].'</td>';
            $content .= '<td style="border:1px solid #000;text-align:left;width:150px;">'.strtoupper($data['name']).'</td>';
            $content .= '<td style="border:1px solid #000;text-align:left;">'.$data['main_group'].'</td>';
            $content .= '<td style="border:1px solid #000;text-align:left;">'.$data['sub_group'].'</td>';
            $content .= '<td style="border:1px solid #000;text-align:center;">'.strtoupper($data['attributes_unit_main']).'</td>';
            $content .= '<td style="border:1px solid #000;text-align:center;">'.$data['min_qty'].'</td>';
            $content .= '<td style="border:1px solid #000;text-align:right;">'.$data['Rate'].'</td>';
        }else{
            $content .= '<td style="border:1px solid #000;text-align:left;">'.$data['group_name'].'</td>';
        }
        $content .= '<td style="border:1px solid #000;text-align:right;width:80px;">'.round($data['balance_qty'],2).'</td>';
        $content .= '<td style="border:1px solid #000;text-align:right;width:80px;">'.round($data['balance_item_amount'],2).'</td>';
        $content .= '</tr>';
        $i++;
    }
}

// grand total
foreach($GrandTotal as $data){
    $content .= '<tr style="font-size:11px !important;color:#000;background-color:#c2d69a;">';
    $content .= '<td style="border:1px solid #000;text-align:center;" colspan="'.($showItem ? 7 : 2).'">GRAND TOTAL</td>';
    if($showItem){
        $content .= '<td style="border:1px solid #000;text-align:right;"></td>'; // Rate total is not meaningful
    }
    $content .= '<td style="border:1px solid #000;text-align:right;">'.round($data['balance_qty'],2).'</td>';
    $content .= '<td style="border:1px solid #000;text-align:right;">'.round($data['balance_item_amount'],2).'</td>';
    $content .= '</tr>';
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

 //$y = reportStatementAndBalance($date,$id_main_group,$id_sub_group,$id_items,$id_report_type,$report_show,$id_order_by,$showItemReport);
 $sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category Not IN ('Menu Items','Both','Other Items') AND id_shop = ".$_SESSION['shop'] ." ";
												$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
												$list2=array();
													while($sqlRow = mysqli_fetch_object($QuerySQL1)){
												        $list2[] = $sqlRow->id;
														//$string .= $list.',';
													}	$uarray =array_unique($list2);
													
													
													$input = array_map("unserialize", array_unique(array_map("serialize",$list2)));
											$item_list2 = implode(',',$input);


$content ='';
		
	$logo	= selectField(TBL_SHOP,'image','WHERE id="'.$_SESSION['shop'].'" ');
if($date!=''){
	
	$SearchDate = explode(' to ',$date);
	$ReportDate =	'From '.$SearchDate[0].' To '.$SearchDate[1];
	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($SearchDate[0]))."' and '".date('Y-m-d',strtotime($SearchDate[1]))."'";
	$SearchIDate = explode(' to ',$date);
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($SearchIDate[0]))."'";		
	}
	
if($id_main_group!=''){
	$SqlRepConn .=	" AND inv.id_mst_attributes_group_main IN  (".$id_main_group.")";	
	}


	
if($id_sub_group!=''){
	$SqlRepConn .=	" AND inv.id_mst_attributes_group_sub  IN (".$id_sub_group.")";	
	}
if($id_items!=''){
	$SqlRepConn .=	" AND inv.id IN (".$id_items.")";	
	}	
  	  $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id_mst_attributes_item_type,		
			inv.min_qty,inv.id_mst_attributes_unit_main,
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.qty else 0 end) as `issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchDate.")) then `pp`.item_amount else 0 end) as `issue_item_amount`,			
			
			
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_receipt_qty`,
			sum(case when ( p.doc_type IN ('4','12','100','9')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_receipt_item_amount`,
			
			
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.qty else 0 end) as `opening_issue_qty`,
			sum(case when ( p.doc_type IN ('6','8')) AND ( (".$SearchIssueDate.")) then `pp`.item_amount else 0 end) as `opening_issue_item_amount`
			
			
			FROM inv_purch_details as pp
			LEFT JOIN inv_items inv ON inv.id=pp.id_inv_items 
			LEFT JOIN inv_purch p ON p.id=pp.id_inv_purch
			
			
			WHERE pp.id>0 and pp.qty!='0' and inv.status='1'
			$SqlRepDateConn	and inv.id_mst_attributes_item_type IN (".$item_list2.") ".$SqlRepConn." 
			GROUP by pp.id_inv_items
			
    ";
	//GROUP BY pp.id_inv_items
	
		// echo $pos_purch_sql;//die;
	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$GrandTotal=array();
	 while($posPurchResult = mysqli_fetch_object($resultPosPurch)){
		 //print_r($posPurchResult);
		 
		  $sqlsub_group="SELECT * FROM ".TBL_ATTRIBUTES." WHERE id_shop='".$_SESSION['shop']."'  and status = '1'  AND  id='".$posPurchResult->id_mst_attributes_unit_main."'";
           
            $ressub_group = mysqli_query($connNew,$sqlsub_group);
		  
           $rowsub_group = mysqli_fetch_object($ressub_group);
		 
		 
		$attributes_unit_main	=  $rowsub_group->field_value;
		 
		 
		 $OpenaningQty =round($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty,2);
		 
		  $RateQTY	 =	 ($OpenaningQty+$posPurchResult->receipt_qty);
		  
		  $Qty	=	($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty);
		  
		 $Rate =  $Qty!='0'?round(($posPurchResult->opening_receipt_item_amount+$posPurchResult->receipt_item_amount)/($posPurchResult->opening_receipt_qty+$posPurchResult->receipt_qty),2):'0';
		  
		
		 
		 $opening_issue_item_amount	=($posPurchResult->opening_issue_qty*$Rate);
		 
		 $OpenaningItemAmount		 =$OpenaningQty*$Rate;//($posPurchResult->opening_receipt_item_amount-$opening_issue_item_amount);
		 
		
		 
		 $BalanceQty				  =round(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty,2);
		 $BalanceItemAmount		   =($BalanceQty*$Rate);
		 
		 
		 
		 //round($posPurchResult->receipt_item_amount/$posPurchResult->receipt_qty,2);
		 
		 if($Qty!='0' && ($BalanceQty!='0' ||  $OpenaningQty!='0' || $posPurchResult->issue_qty!='0' || $posPurchResult->receipt_qty!='0')){
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
		 $DatewiseArray['Report'][$id_inv_items]['attributes_unit_main']=$attributes_unit_main;
		 $DatewiseArray['Report'][$id_inv_items]['min_qty']=$posPurchResult->min_qty;
		 
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
				
				
		
		$headerColor = selectField(APP_COLOR_CONFIG,'report_header_color','',$appConnect); 
		$headerTextColor = selectField(APP_COLOR_CONFIG,'report_header_text_color','',$appConnect); 
		$titleColor = selectField(APP_COLOR_CONFIG,'report_title_color','',$appConnect); 
		$titleTextColor = selectField(APP_COLOR_CONFIG,'report_title_text_color','',$appConnect); 
		$subtitleColor = selectField(APP_COLOR_CONFIG,'report_subtitle_color','',$appConnect); 
		$subtitleTextColor = selectField(APP_COLOR_CONFIG,'report_subtitle_text_color','',$appConnect); 	
		$content .= '<table class="table"  style=" margin-bottom: 0px;border: 1px; width:100%; text-align: center;">';
		//$content .= '<tr style="font-size:16px !important;" id="hideheadinglable">';
		//$content .= '<th  width="60px;" ><b>S.no</b></th>'; 
		$content .= '<tr style="font-size:16px !important;" ><th colspan="14"  style="vertical-align:central;text-align:center;color:#fff;background-color:'.$headerColor.';color:'.$headerTextColor.'; font-size:16px !important"><b> Stock Statement  Report Period '.$date.' </b></th></tr>';
		
		
		$content .= '<tr  id="head1"  style="font-size:12px !important;" >';	
		$content .= '<th style="border:1px solid #000;text-align:left;width:10px;background-color:'.$titleColor.';color:'.$titleTextColor.'"><b>S.no</b></th>';	
		$content .= '<th style="border:1px solid #000;text-align:left;width:10px;background-color:'.$titleColor.';color:'.$titleTextColor.'"><b>Item Code</b></th>';
		$content .= '<th colspan="0" style="border:1px solid #000;text-align:left;width:100px;background-color:'.$titleColor.';color:'.$titleTextColor.'"><b>Item Name</b></th>
		<th style="border:1px solid #000;width:34px;text-align:center;background-color:'.$titleColor.';color:'.$titleTextColor.'" ><b>Unit</b></th>
		<th  style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'" ><b>Min Qty</b></th>
		<th style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'"  ><b>Rate</b></th>
		
		
		<th colspan="2" style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'"  ><b>Opening</b></th>
		<th  colspan="2"  style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'"  ><b>Receipt</b></th>
		<th  colspan="2" style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'"  ><b>Issue</b></th>
		<th  colspan="2" style="border:1px solid #000;text-align:center;width:34px;background-color:'.$titleColor.';color:'.$titleTextColor.'"  ><b>Balance</b></th>
		
		';
		$content .= '</tr>';
		
		$content .= '<tr  id="head2" style="font-size:12px !important;" >';	
		$content .= '<th  style="background-color:'.$titleColor.';color:'.$titleTextColor.';"  ></th>';	
		$content .= '<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';" ></th>';
		$content .= '<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';" ></th>
		<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';" ></th>
		<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';" ></th>
		<th style="background-color:'.$titleColor.';color:'.$titleTextColor.';" ></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Qty</b></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Amount</b></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Qty</b></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Amount</b></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Qty</b></th>
		<th style="border:1px solid #000;width:50px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Amount</b></th>
		<th style="border:1px solid #000;width:70px;background-color:'.$titleColor.';color:'.$titleTextColor.';" ><b>Qty</b></th>
		<th style="border:1px solid #000;width:70px;background-color:'.$titleColor.';color:'.$titleTextColor.';"  ><b>Amount</b></th>
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
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:center;">'.$i.'</td>';
				$contentItem .= '<td  style="width:90px;border:1px solid #000;text-align:left;">'.$data['item_code'].'</td>';
				
				
				$contentItem .= '<td style="width:170px;border:1px solid #000;text-align:left;">'.strtoupper($data['name']).'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:center;">'.strtoupper($data['attributes_unit_main']).'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:center;">'.$data['min_qty'].'</td>';						
				$contentItem .= '<td  style="width:50px;border:1px solid #000;text-align:right;">'.$data['Rate'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['opening_qty'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['opening_item_amount'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['receipt_qty'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['receipt_item_amount'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['issue_qty'].'</td>';
				$contentItem .= '<td style="width:50px;border:1px solid #000;text-align:right;">'.$data['issue_item_amount'].'</td>';
				$contentItem .= '<td style="width:70px;border:1px solid #000;text-align:right;">'.$data['balance_qty'].'</td>';
				$contentItem .= '<td style="width:70px;border:1px solid #000;text-align:right;">'.$data['balance_item_amount'].'</td>';
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
				$content .= '<td style="text-align:center;width:200px;" colspan="5">GRAND TOTAL</td>';
										
				$content .= '<td  style="border:1px solid #000;width:50px;text-align:right;">'.$data['Rate'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['opening_qty'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['opening_item_amount'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['receipt_qty'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['receipt_item_amount'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['issue_qty'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['issue_item_amount'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['balance_qty'].'</td>';
				$content .= '<td style="border:1px solid #000;width:50px;text-align:right;">'.$data['balance_item_amount'].'</td>';
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