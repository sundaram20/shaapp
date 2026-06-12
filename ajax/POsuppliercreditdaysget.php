<?php include_once("../config/auto_loader.php");

 $id_mst_party_supplier = $_POST["id_mst_party_supplier"]; 
 

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

echo json_encode($res);
 empty($res);
?>