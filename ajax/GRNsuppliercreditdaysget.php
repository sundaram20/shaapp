<?php include_once("../config/auto_loader.php");

 $id_mst_party_supplier = $_POST["id_mst_party_supplier"]; 
 $counter1 = $_POST["counter1"]; 
 $wpop = $_POST["wpop"]; 
 $pono = $_POST["pono"]; 
 $podate = $_POST["podate"]; 
 

	$sql2 = " SELECT * FROM `".TBL_PARTY."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_party_supplier."' ";
	$db->query($sql2);  
	$numRows= $db->num_rows();
		
		while($row2 = $db->fetch_object()){  
				$res['credit_days'] = $row2->credit_days;   
				$res['ledger'] = $row2->ledger;   
				
				$transaction_currency_code = $row2->transaction_currency_code;   
				$transaction_currency_code1 = $row2->transaction_currency_code;   
		}
		//Field Value Get
		$transaction_currency_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($transaction_currency_code)."'");
		$res['transaction_currency_code'] = $transaction_currency_code;
		$res['transaction_currency_code1'] = $transaction_currency_code1;

		//Select Box Value Load Here
		$i=1;
		
//echo	"SELECT inv_po.*, inv_po_details.bal_qty FROM inv_po LEFT JOIN inv_po_details ON inv_po_details.id_inv_po = inv_po.id WHERE inv_po.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_po.id_mst_party_supplier = '".$id_mst_party_supplier."' AND inv_po_details.bal_qty>0 GROUP BY inv_po_details.id_inv_po";
		
 // $sql2 = " SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$id_mst_party_supplier."' ";
		
	$sql2 ="SELECT inv_po.*, inv_po_details.bal_qty FROM inv_po LEFT JOIN inv_po_details ON inv_po_details.id_inv_po = inv_po.id WHERE inv_po.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_po.id_mst_party_supplier = '".$id_mst_party_supplier."' AND inv_po_details.bal_qty>0 GROUP BY inv_po_details.id_inv_po";
		 
		 
		$db->query($sql2);  
		$numRows= $db->num_rows();
		
		while($row2 = $db->fetch_object()){  
				$res["id".''.$i] = $row2->id;   
				$res["doc_no".''.$i] = $row2->doc_no;   
				$res["date".''.$i] = date('d-m-Y' , strtotime(addslashes($row2->doc_date)));
				$i++;
		}
		$res['i'] = $i;
		$res['counter1'] = $counter1;
		$res['wpop'] = $wpop;
		$res['pono'] = $pono;
		$res['podate'] = $podate;

echo json_encode($res);
 empty($res);
?>