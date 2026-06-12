<?php 


if($_REQUEST['DbConnect']==1){

include_once("../config/auto_loader.php");

}



  
$id_attribute_table=$_REQUEST['id_attribute_table'];
$UniqueCodeold=$_REQUEST['UniqueCode'];
$discountType=$_REQUEST['discountType'];
$outlet =	$_REQUEST['outlet'];
$id	=	$_REQUEST['id'];
 
 $total1	=	$_REQUEST['total1'];
 $total	=	$_REQUEST['total'];
 $sgst	=	$_REQUEST['sgst'];
 $cgst	=	$_REQUEST['cgst'];
 
 
 
$itemqty2=$_REQUEST['qty'];
  $sub_total=$_REQUEST['sub_total'];
  
if($itemqty2==''){
	$itemqty='1.00';
}else{
	$itemqty=$itemqty2;
}

			
 
 
 $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$_REQUEST['outlet']."' AND  `service_charge_apply`='1'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				 $id_service_charge = $rowOutlet->id_service_charge;
				$taxtype = $rowOutlet->taxtype;
	      
		if($id_service_charge=='0'){
			 $id_sgst = '0';
			 $id_cgst = '0';				
			 $percentage= '0';
		} else{
		   $sqlCharges = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_service_charge."'";
	       $db->query($sqlCharges); 
	       $rowCharges= $db->fetch_object();
	      		 $id_sgst = $rowCharges->id_mst_charges_sgst;
				 $id_cgst = $rowCharges->id_mst_charges_cgst;				
				  $percentage= $rowCharges->percentage;
		}
		  
		if($id_sgst=='0'){
			 $serviceSGST = '0';
		} else{
		   $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_sgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      	  $serviceSGST = $row2->percentage; 
	      	}
		}
		
		if($id_cgst=='0'){
			 $serviceCGST = '0';
		} else{
		    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_cgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		 $serviceCGST = $row2->percentage; 
	      	}
		}
						
			
//echo $sub_total;

//if($id_attribute_table){

 $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$outlet."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				$id_service_charge = $rowOutlet->id_service_charge;
			   $taxtype = $rowOutlet->taxtype;

		   
$sqlitemnew = "SELECT *  from pos_purch WHERE id='".$_REQUEST['id_posbilling']."' ";
$resitem = mysqli_query($connNew,$sqlitemnew);
		$selectoption1=mysqli_fetch_object($resitem);
		$sc_charges_net_amount = $selectoption1->sc_charges_net_amount;
	if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$selectoption1->discount_amount_additional;
	}
	
	
	if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge'] != ''){
		$service_charge_amount='0';
		$serviceTotalSGST= '0';
		$serviceTotalCGST= '0';
		$serviceChargeTotal	='0';
	}else {	
	    $service_charge_amount	=	(($sub_total*$percentage)/100);
		$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);//2.50
		$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
		$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
	}
	//echo $total_sgst;
	$ItemRate['service_charge_amount']	=	$service_charge_amount;
	$ItemRate['serviceChargeTotal']	=	$serviceChargeTotal;
	
	$netamount = $total+$serviceChargeTotal;
	//$total_sgst = $sgst+$serviceChargeTotal;
	 $total_cgst = $cgst+$serviceChargeTotal;
	 $netamount1 = round($netamount,2);
	 $RoundOfAmount = $netamount1 - $netamount ;
	 $ItemRate['netamount1']	=	$netamount1;
	 $ItemRate['serviceTotalSGST']	=	$serviceTotalSGST;
	 $ItemRate['serviceTotalCGST']	=	$serviceTotalCGST;
	// $ItemRate['TotalTax_sgst']	=	$total_sgst;
	 $ItemRate['TotalTax_cgst']	=	$total_cgst;
	 $ItemRate['round_off_amount']	=	$RoundOfAmount;
	 $ItemRate['serviceChargeTotal']	=	$serviceChargeTotal;
	
//}	













/*  pos section

	

if($_REQUEST['DbConnect']==1){

include_once("../config/auto_loader.php");

}



  
$id_attribute_table=$_REQUEST['id_attribute_table'];
$UniqueCodeold=$_REQUEST['UniqueCode'];
$discountType=$_REQUEST['discountType'];
$outlet =	$_REQUEST['outlet'];
$id	=	$_REQUEST['id'];
 
 $total1	=	$_REQUEST['total1'];
 $total	=	$_REQUEST['total'];
 $sgst	=	$_REQUEST['sgst'];
 $cgst	=	$_REQUEST['cgst'];
 
 
 
 
$itemqty2=$_REQUEST['qty'];
  $sub_total=$_REQUEST['sub_total'];
  
if($itemqty2==''){
	$itemqty='1.00';
}else{
	$itemqty=$itemqty2;
}

			
 
 
  $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$_REQUEST['outlet']."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				 $id_service_charge = $rowOutlet->id_service_charge;
				$taxtype = $rowOutlet->taxtype;
	      
		if($id_service_charge=='0'){
			 $id_sgst = '0';
			 $id_cgst = '0';				
			 $percentage= '0';
		} else{
		   $sqlCharges = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_service_charge."'";
	       $db->query($sqlCharges); 
	       $rowCharges= $db->fetch_object();
	      		 $id_sgst = $rowCharges->id_mst_charges_sgst;
				 $id_cgst = $rowCharges->id_mst_charges_cgst;				
				  $percentage= $rowCharges->percentage;
		}
		  
		if($id_sgst=='0'){
			 $serviceSGST = '0';
		} else{
		   $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_sgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      	 $serviceSGST = $row2->percentage; 
	      	}
		}
		
		if($id_cgst=='0'){
			 $serviceCGST = '0';
		} else{
		    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_cgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$serviceCGST = $row2->percentage; 
	      	}
		}
						
			
 

if($id_attribute_table){

	//BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);
	
	

 $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$outlet."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				$id_service_charge = $rowOutlet->id_service_charge;
			   $taxtype = $rowOutlet->taxtype;

		   
 $sqlitemnew = "SELECT *  from inv_purch WHERE id='".$_REQUEST['id_posbilling']."' ";
$resitem = mysqli_query($connNew,$sqlitemnew);
		$selectoption1=mysqli_fetch_object($resitem);
		$sc_charges_net_amount = $selectoption1->sc_charges_net_amount;
	if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$selectoption1->disc_amount_additional1;
	}
	
	
	if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge'] != ''){
		$service_charge_amount='0';
		$serviceTotalSGST= '0';
		$serviceTotalCGST= '0';
		$serviceChargeTotal	='0';
	}else {	
	    $service_charge_amount	=	(($sub_total*$percentage)/100);
		$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
		$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
		$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
	}
	//echo $total_sgst;
	$ItemRate['service_charge_amount']	=	$service_charge_amount;
	$ItemRate['serviceChargeTotal']	=	$serviceChargeTotal;
	
	$netamount = $total+$serviceChargeTotal;
	//$total_sgst = $sgst+$serviceChargeTotal;
	 $total_cgst = $cgst+$serviceChargeTotal;
	 $netamount1 = round($netamount);
	 $RoundOfAmount = $netamount1 - $netamount ;
	 $ItemRate['netamount1']	=	$netamount1;
	 $ItemRate['serviceTotalSGST']	=	$serviceTotalSGST;
	 $ItemRate['serviceTotalCGST']	=	$serviceTotalCGST;
	// $ItemRate['TotalTax_sgst']	=	$total_sgst;
	 $ItemRate['TotalTax_cgst']	=	$total_cgst;
	 $ItemRate['round_off_amount']	=	$RoundOfAmount;
	 $ItemRate['serviceChargeTotal']	=	$serviceChargeTotal;
	
}		











*/

















echo json_encode($ItemRate);
 ?>

