<?php include_once("../config/auto_loader.php");

 $others_charges = $_POST["others_charges"]; 

 	//Charges Locals
 
	if($others_charges == 'others'){

 		$id_mst_charges_others = $_POST["id_mst_charges_others"]; 
 		$ledger_id = $_POST["ledger_id"]; 

		$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_charges_others."' ";
		$db->query($sql2);  
		$numRows= $db->num_rows();
			
			while($row2 = $db->fetch_object()){  
					$sgst = $row2->id_mst_charges_sgst;        
					$cgst = $row2->id_mst_charges_cgst;        
					$igst = $row2->id_mst_charges_igst; 
					$account = $row2->charges_account; 
					//ID Store Here 
					$res['id_mst_charges_sgst_others']  = $row2->id_mst_charges_sgst;     
					$res['id_mst_charges_cgst_others']  = $row2->id_mst_charges_cgst;     
					$res['id_mst_charges_igst_others']  = $row2->id_mst_charges_igst;     
					$res['account']  = $row2->charges_account;     
			}
			if($ledger_id == 1){
				//SGST GET HERE
				$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$sgst."' ";
				$db->query($sql2);  
				$numRows= $db->num_rows();
				if($numRows >= 1){
					while($row2 = $db->fetch_object()){ 
						$ssgst = $row2->percentage;
						
						if($ssgst==''){
							$res['sgst'] = '0';
						}else{
							$res['sgst'] = $row2->percentage;
						}
					
							              
					}
				}
				//CGST GET HERE
				$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$cgst."' ";
				$db->query($sql2);  
				$numRows= $db->num_rows();
				if($numRows >= 1){
					while($row2 = $db->fetch_object()){

					$ccgst = $row2->percentage;
						
						if($ccgst==''){
							$res['cgst'] = '0';
						}else{
							$res['cgst'] = $row2->percentage;
						}

						
						//$res['cgst'] = $row2->percentage;              
					}
				}
				$res['igst'] = 0;
			}if($ledger_id == 2){
				//IGST GET HERE 
				$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$igst."' ";
				$db->query($sql2);  
				$numRows= $db->num_rows();
				if($numRows >= 1){
					while($row2 = $db->fetch_object()){ 

					$iigst = $row2->percentage;
						if($iigst==''){
							$res['igst'] = '0';
						}else{
							$res['igst'] = $row2->percentage;
						}
						
						//$res['igst'] = $row2->percentage;              
					}
					$res['sgst'] = 0;
					$res['cgst'] = 0;
				}
			}
		echo json_encode($res);
	 	empty($res);

	} 
	//Discount
	if($others_charges == 'discount'){

		$id_mst_charges_discounts = $_POST["id_mst_charges_discounts"];
		 
			//Doiscount GET HERE
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_charges_discounts."' ";
			$db->query($sql2);  
			$numRows= $db->num_rows();
			if($numRows >= 1){
				while($row2 = $db->fetch_object()){  
						$res['discount'] = $row2->percentage;              
				}
			}
		echo json_encode($res);
	 	empty($res);
	}
?>