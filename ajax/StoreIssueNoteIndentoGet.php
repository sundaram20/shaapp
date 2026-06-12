<?php include_once("../config/auto_loader.php");

 $id_mst_attributes_department = $_POST["id_mst_attributes_department"]; 
 $counter1 = $_POST["counter1"]; 
 $wpops = $_POST["wpops"]; 
 $wpop = $_POST["wpop"];  
 $pono = $_POST["pono"]; 
 $podate = $_POST["podate"]; 
  

		//Select Box Value Load Here
		$i=1;
		
		//"SELECT inv_indent.*, inv_indent_details.bal_qty FROM inv_indent LEFT JOIN inv_indent_details ON inv_indent_details.id_inv_indent = inv_indent.id WHERE inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_indent.id_mst_attributes_department = '".$id_mst_attributes_department."' AND inv_indent.doc_type='1' AND inv_indent_details.bal_qty>0 ";
		
		
		
//$sql2 = " SELECT * FROM `".TBL_INV_INDENT."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_attributes_department`='".$id_mst_attributes_department."' and doc_type = '1'  ";
	
 $sql2 = "SELECT inv_indent.*,   inv_indent_details.bal_qty FROM inv_indent LEFT JOIN inv_indent_details ON inv_indent_details.id_inv_indent = inv_indent.id WHERE inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_indent.id_mst_attributes_department = '".$id_mst_attributes_department."' AND inv_indent.doc_type='1' AND inv_indent_details.bal_qty>0 GROUP BY inv_indent_details.id_inv_indent";
		
		
		
		$db->query($sql2);  
		$numRows= $db->num_rows();
		
		while($row2 = $db->fetch_object()){  
				$res["id".''.$i] = $row2->id.'-'.$row2->doc_no;   
				$res["doc_no".''.$i] = $row2->doc_no;   
				$res["date".''.$i] = date('d-m-Y' , strtotime(addslashes($row2->doc_date)));
				$i++;
		}
		$res['i'] = $i;
		$res['counter1'] = $counter1;
		$res['wpops'] = $wpops;
		$res['wpop'] = $wpop;
		$res['pono'] = $pono;
		$res['podate'] = $podate;

echo json_encode($res);
 empty($res);
?>