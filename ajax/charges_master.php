<?php include_once("../config/auto_loader.php");

 $account = $_POST['account'];

if ($account == 'salesaccountlocal')
{
	 
		$selectedValue = $_POST['selectedValue'];

		$sql1 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$selectedValue."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){ 
	      		$sgst = $row1->id_mst_charges_sgst;
				$cgst = $row1->id_mst_charges_cgst;
				$vat = $row1->id_mst_charges_vat;
				$cess = $row1->id_mst_charges_cess;
				$surcharge = $row1->id_mst_charges_surcharge;
				$res['percentage'] = $row1->percentage;
	      	} 

	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$sgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['sgst'] = $row2->percentage; 
	      	}
	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$cgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['cgst'] = $row2->percentage; 
	      	}
	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$vat."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['vat'] = $row2->percentage; 
	      	}
		
		$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$cess."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['cess'] = $row2->percentage; 
	      	}
			
		$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$surcharge."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['surcharge'] = $row2->percentage; 
	      	}	

		echo json_encode($res); 
		
} 
//SaleAccountInterstate
if ($account == 'salesaccountinterstate')
{
	 
		$selectedValue = $_POST['selectedValue'];

		$sql1 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$selectedValue."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){ 
	      		$igst = $row1->id_mst_charges_igst; 
	      	} 

	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$igst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['igst'] = $row2->percentage; 
	      	} 

		echo json_encode($res); 
}
//PurchaseAccountLocal
if ($account == 'purchaseaccountlocal')
{
	 
		$selectedValue = $_POST['selectedValue'];

		$sql1 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$selectedValue."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){  
				$vat = $row1->id_mst_charges_vat;
				//$cess = $row1->id_mst_charges_cess;
				$surcharge = $row1->id_mst_charges_surcharge;
	      	} 
 
	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$vat."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['vat'] = $row2->percentage; 
	      	}
		
		$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id`='".$surcharge."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['surcharge'] = $row2->percentage; 
	      	}

		echo json_encode($res); 
}
//PurchaseAccountInterstate
if ($account == 'purchaseaccountinterstate')
{
	 
		$selectedValue = $_POST['selectedValue'];

		$sql1 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$selectedValue."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){ 
	      		$igst = $row1->id_mst_charges_igst; 
	      	} 

	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$igst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$res['igst'] = $row2->percentage; 
	      	} 

		echo json_encode($res); 
} 
//Main Alt Unit Section Here
if ($account == 'mainaltunit')
{
	 
		$id_mst_attributes_unit_main = $_POST['id_mst_attributes_unit_main'];
		$id_mst_attributes_unit_alt = $_POST['id_mst_attributes_unit_alt'];

		$sql1 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_main."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){ 
	      		$main = $row1->field_value; 
	      		$res['main'] = '1'.' - '.$main; 
	      	} 
	    $sql1 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_mst_attributes_unit_alt."'";
	       $db->query($sql1); 
	       while($row1 = $db->fetch_object()){ 
	      		$alt = $row1->field_value; 
	      		$res['alt'] = $alt; 
	      	} 	    

		echo json_encode($res); 
} 
?>