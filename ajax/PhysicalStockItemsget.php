<?php include_once("../config/auto_loader.php");

 $id_inv_items = $_POST["id_inv_items"];
  $doc_date = $_POST["doc_date"]; 

$sql = "SELECT * FROM `".TBL_INV_ITEMS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_inv_items."' and `status` = '".'1'."' ";

//print_r($sql);

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$name= $row->name; 
	    	$conversion_qty= $row->conversion_qty; 
	    	$id_mst_attributes_unit_main= $row->id_mst_attributes_unit_main; 
	    	$id_mst_attributes_unit_alt= $row->id_mst_attributes_unit_alt; 
	    	$id_mst_attributes_store= $row->id_mst_attributes_store; 
	    }

//Main Unit Get Here

/*$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_main."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$main_unit= $row->field_value;  
	    }  

//Alt Unit Get Here

$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_alt."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$alt_unit= $row->field_value;  
	    } 

//Store Get Here

$sql = "SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_store."' and `status` = '".'1'."' ";

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$store= $row->field_value;  
	    } */

//Stock In Hand
//GRN DETAILS 
/*$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$id_inv_items."'");
//Opening Balance
$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$id_inv_items."'");
//Physical Stock
$physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$id_inv_items."'");
//Store Issue Note
$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$id_inv_items."'");
*/

	$SearchDate =	"  p.doc_date between '".date('Y-m-d',strtotime($doc_date))."' and '".date('Y-m-d',strtotime($doc_date))."'";
	//"  p.doc_date < '".date('Y-m-d',strtotime($doc_date))."'";	
	$SearchIssueDate =	"  p.doc_date < '".date('Y-m-d',strtotime($doc_date))."'";
	
     $pos_purch_sql="
    
			select 
			pp.id_inv_items,inv.name,inv.item_code,inv.id_mst_attributes_group_main,inv.id_mst_attributes_group_sub,inv.id_mst_attributes_item_type,		
			
			
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
			
			
			WHERE pp.id>0 
			AND id_inv_items ='".$id_inv_items."'	and inv.id_mst_attributes_item_type='17' GROUP by pp.id_inv_items
			
			
    ";


	$resultPosPurch = mysqli_query($connNew,$pos_purch_sql); 
	$numRows = mysqli_num_rows($resultPosPurch);
	$GrandTotal=array();
	$posPurchResult = mysqli_fetch_object($resultPosPurch);
//print_r($posPurchResult);







		 $OpenaningQty				=($posPurchResult->opening_receipt_qty-$posPurchResult->opening_issue_qty);
		 $OpenaningItemAmount		 =($posPurchResult->opening_receipt_item_amount-$posPurchResult->opening_issue_item_amount);
		 
		 $RateQTY					 =	 ($OpenaningQty+$posPurchResult->receipt_qty);
		 $Rate						= $RateQTY>0?round(($OpenaningItemAmount+$posPurchResult->receipt_item_amount)/($OpenaningQty+$posPurchResult->receipt_qty),2):0;
		 
		 $BalanceQty				  =(($OpenaningQty+$posPurchResult->receipt_qty)-$posPurchResult->issue_qty);
		 $BalanceItemAmount		   =($BalanceQty*$Rate);

//$stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;

//Display Method

$res['name'] = $name;
$res['main_unit'] = $main_unit;
$res['alt_unit'] = $alt_unit;
$res['conversion_qty'] = $conversion_qty;
$res['id_mst_attributes_store'] = $id_mst_attributes_store;
$res['store'] = 0;//$store;
$res['stock_in_hand'] = $BalanceQty;//.'-'.$BalanceItemAmount.'rate: '.$Rate;
 $res['rate'] = $Rate;
 
echo json_encode($res);
 empty($res);
?>