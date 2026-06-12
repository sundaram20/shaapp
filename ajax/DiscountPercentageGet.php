<?php include_once("../config/auto_loader.php");

 $id_mst_charges_discounts_all = $_POST["id_mst_charges_discounts_all"]; 

 	//Charges Locals
  
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_charges_discounts_all."' ";
			$db->query($sql2);  
			$numRows= $db->num_rows();
			if($numRows >= 1){
				while($row2 = $db->fetch_object()){  
						$res['percentage'] = $row2->percentage;              
				}
			} else{
				$res['percentage'] = '0.00';
				}
		echo json_encode($res);
	 	empty($res);

	 
?>