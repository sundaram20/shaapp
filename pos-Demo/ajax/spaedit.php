<?php include_once("../../config/auto_loader.php");
$rowCount=$_REQUEST['rowCount'];
$id	=	$_REQUEST['id'];
?>


<?php
 $itemqty2=$_REQUEST['qty'];
 $rate=$_REQUEST['rate'];
if($itemqty2==''){
	$itemqty='1.00';
}else{
	$itemqty=$itemqty2;
}
 

  $discountamount=$_REQUEST['discountamount'];
   $discountType=$_REQUEST['discountType'];
  if($discountType==2){	// Additonal Discount
     $_SESSION['discountamount']=$discountamount;
  }
   if($discountType==3){	// Additonal Charges
     $_SESSION['AdditionalChargeamount']=$discountamount;
} 
   

 $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$_REQUEST['outlet']."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				$id_service_charge = $rowOutlet->id_service_charge;
				$taxtype = $rowOutlet->taxtype;
	      
			$sqlCharges = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_service_charge."'";
	       $db->query($sqlCharges); 
	       $rowCharges= $db->fetch_object();
	      		$id_sgst = $rowCharges->id_mst_charges_sgst;
				$id_cgst = $rowCharges->id_mst_charges_cgst;				
				$percentage= $rowCharges->percentage;
	      	
						
			$sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_sgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$serviceSGST = $row2->percentage; 
	      	}
	    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_cgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$serviceCGST = $row2->percentage; 
	      	}

   
		$sqlitemDetail1     = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS."` WHERE id='".$id."' ");
		$numitemDetail1     =  mysqli_num_rows($sqlitemDetail1);
		$rowitemDetail1     =  mysqli_fetch_object($sqlitemDetail1);
		$ItemRate['dis1']	=	$rowitemDetail1->id;
?>

	
	<?php
		 $sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$id."' ");
			$numitemDetail=	mysqli_num_rows($sqlitemDetail);
				if($numitemDetail>0){
					
					
		$sqlitemDetailnew = mysqli_query($connNew,"SELECT *  from pos_purch_details WHERE id_mst_items_details='".$id."' ");
		$selectoption=mysqli_fetch_object($sqlitemDetailnew);
		$ItemRate['id_purch_details'] = $selectoption->id;
	   //$ItemRate['discountpercent'] = $selectoption->item_discount_percent;
	    $ItemRate['discountpercent'] = '0.00';
	   // $ItemRate['discountamount'] = $selectoption->discount_amount;
	    $ItemRate['discountamount'] = '0';
	    $discount = $selectoption->item_discount_percent;	
					
					
				$i=0;
				$rowitemDetail=mysqli_fetch_object($sqlitemDetail);
				
				$ItemRate['dis']	=	$rowitemDetail->id;
				$ItemRate['item_name']	=	$rowitemDetail->name;
				$ItemRate['dis12']	=	$rowitemDetail->id_item;
				$ItemRate['subitems_name1']	=	$rowitemDetail1->name;
				
				$ItemRate['outlet']	=	selectColumn(TBL_INV_ITEMS,'ids_mst_outlet'," WHERE  id_shop='".$_SESSION['shop']."' and status = '1'  AND `id` = '".$rowitemDetail->id_item."'");
				$ItemRate['itemRate']	=	round($rate ,2);
				$ItemRate['itemcode']	=	$rowitemDetail->sub_code;
				$ItemRate['itemunit']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$rowitemDetail->id_unit."'  ");
				$ItemRate['item_qty']	=	'1.00';
				
				$ItemRate['item_amount']	=	round(($rate*$itemqty) ,2);
				
				$item_TotalAmountItem	=	$rate*$itemqty;
				$Totalitem_amount	=	$rate*$itemqty;
				
				$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$rowitemDetail->id_item."'");
				
				$id_mst_charges_sales_interstate	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_interstate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$rowitemDetail->id_item."'");
				
				
				$ItemRate['id_mst_charges_sales_local'] = $id_mst_charges_sales_local;
				$ItemRate['id_mst_charges_sales_interstate'] = $id_mst_charges_sales_interstate;

				$resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat = $db->fetch_object2($resCat);

				 $Taxapplication 	  = $resultCat->tax_applicable;
				
				
				$percentage1=($discount/100)*$item_TotalAmountItem;
				    $Totalitem_amount		=	$Totalitem_amount-$percentage1;
				   // $ItemRate['item_TotalAmountItem']		=	$Totalitem_amount;
					$SubTotalAmount		 +=($rate*$itemqty);
					$DiscountTotalAmount	 +=$percentage1;
					$TotalAmountFinal		+=$Totalitem_amount;
				
				if($Taxapplication==1){
								
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cess."'");
							//	}
							$ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
							$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
							$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;		
							$ItemRate['sgst']	=	round($Tax_sgst, 2);
							$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	
							$ItemRate['cgst']	=	round($Tax_cgst, 2);
							$Tax_igst	=	(($Totalitem_amount*($igst))/100);	
							$ItemRate['Tax_igst']	=	round($Tax_igst, 2);
							$Tax_cess	=	(($Totalitem_amount*($cess))/100);
							$ItemRate['Tax_cess']	=	round($Tax_cess, 2);	

							//die;

							$Tax_sgst_percentage	=	$sgst;
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;
							
							$ItemRate['Tax_sgst_percentage']	=	$sgst;
							$ItemRate['Tax_cgst_percentage']	=	$cgst;	
							$ItemRate['Tax_igst_percentage']	=	$igst;	
							$ItemRate['Tax_cess_percentage']	=	$cess;
							
							
							$Tax_vat		  = '0';
							$ItemRate['Tax_vat']	=	$Tax_vat;	
							$Tax_surcharge	= '0';
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$TotalAmountItem=	$Totalitem_amount+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess);
						$ItemRate['item_TotalAmountItem'] =	round($TotalAmountItem);
							
					}elseif($Taxapplication==2){
						
							$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_vat."'");

							$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_surcharge."'");
							
							$ItemRate['sumTaxPersentge']	=$vat+$surcharge;
							$sumTaxAmount	=(($Totalitem_amount*($vat+$surcharge))/100);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);

							$Tax_sgst	=	'0';	
							$ItemRate['sgst']	=	$Tax_sgst;
							$Tax_cgst	=	'0';
							$ItemRate['cgst']	=	$Tax_cgst;
							$Tax_igst	=	'0';
							$ItemRate['Tax_igst']	=	$Tax_igst;
							$Tax_cess	=	'0';
							$ItemRate['Tax_cess']	=	$Tax_cess;
							$Tax_sgst_percentage	=	'0';	
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		  =	(($Totalitem_amount*($vat))/100);	
							$ItemRate['Tax_vat']	=	$Tax_vat;
							$Tax_surcharge	=	(($Totalitem_amount*($surcharge))/100);
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	$vat;	
							$Tax_surcharge_percentage	=	$surcharge;
							$Tax_vat_id	   = $resultCat->id_mst_charges_vat;
							$Tax_surcharge_id = $resultCat->id_mst_charges_surcharge;
							$TotalAmountItem  =	$Totalitem_amount+($Tax_vat+$Tax_surcharge); 
						$ItemRate['item_TotalAmountItem']  =	round($TotalAmountItem); 
					}else{

						if($id_mst_charges_sales_local==0){

							$Tax_sgst	=	'0';	
							$ItemRate['sgst']	=	$Tax_sgst;
							$Tax_cgst	=	'0';
							$ItemRate['cgst']	=	$Tax_cgst;
							$Tax_igst	=	'0';
							$ItemRate['Tax_igst']	=	$Tax_igst;
							$Tax_cess	=	'0';	
							$ItemRate['Tax_cess']	=	$Tax_cess;
							$Tax_sgst_percentage	=	'0';
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		    =    '0';	
							$ItemRate['Tax_vat']	=	$Tax_vat;
							$Tax_surcharge	  =    '0';
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$sumTaxAmount	   =	'0';
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
							$ItemRate['sumTaxPersentge']	= 	'0';
							$TotalAmountItem	=	$Totalitem_amount;
							$ItemRate['item_TotalAmountItem']	=	round($TotalAmountItem);
							}
							}

							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$TotalTax_sgst	=	$TotalTax_sgst_sum+$serviceTotalSGST;	
							$TotalTax_cgst	=	$TotalTax_cgst_sum+$serviceTotalCGST;
							$TotalTax_igst	+=	$Tax_igst;
							$TotalTax_cess	+=	$Tax_cess;
							$TotalTax_vat	+=	$Tax_vat;
							$TotalTax_surcharge	+=	$Tax_surcharge;
							$ItemRate['TotalTax_sgst']	=	$TotalTax_sgst;
							$ItemRate['TotalTax_cgst']	=	$TotalTax_cgst;
							$ItemRate['TotalTax_vat']	=	$TotalTax_vat;
							$ItemRate['TotalTax_cess']	=	$TotalTax_cess;
							$ItemRate['TotalTax_igst']	=	$TotalTax_igst;
							$ItemRate['TotalTax_surcharge']	=	$TotalTax_surcharge;
						
						
	                //SERVICE CHARGE
					if($revServiceCharge==0 && $revServiceCharge!=''){
						$service_charge_amount='0';		
						$ItemRate['service_charge_amount']	=	$service_charge_amount;	
						
						$serviceTotalSGST= '0';
						$serviceTotalCGST= '0';
						$serviceChargeTotal	='0';
						$ItemRate['sc_sgst']	=	$serviceTotalSGST;	
						$ItemRate['sc_cgst']	=	$serviceTotalCGST;	
						
					}else{
						$service_charge_amount	=	(($SubTotalAmount*$percentage)/100);		
						$ItemRate['service_charge_amount']	=	$service_charge_amount;		
						$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
						$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
						$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
				        $ItemRate['sc_sgst']	=	$serviceTotalSGST;	
						$ItemRate['sc_cgst']	=	$serviceTotalCGST;	
				}
					//SERVICE CHARGE						
							
							
	$totalBeforeRound = ($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_cgst+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];

	$NetAmount		=	($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$TotalTax_cgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];
	
	$ItemRate['NetAmount']		=	round($NetAmount);

	$RoundOfAmount	=	round((round($NetAmount,0)-$totalBeforeRound),2);
	$ItemRate['RoundOfAmount']	=	$RoundOfAmount;
				
					
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
							//$ItemRate['Tax_vat']	=	$Tax_vat;
							//$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$ItemRate['discount']	=	round($discount);
							$ItemRate['total']	=	round($TotalAmountFinal);
							$ItemRate['taxAccountName'] =	ucfirst($resultCat->name);
					
					
				}
				
				else{
					
			
		$sqlitemDetailnew = mysqli_query($connNew,"SELECT *  from pos_purch_details WHERE id_mst_items='".$id."' ");
		$selectoption=mysqli_fetch_object($sqlitemDetailnew);
		$ItemRate['id_purch_details'] = $selectoption->id;
		$ItemRate['id_purch_detailsid'] = $selectoption->id;
	   // $ItemRate['discountpercent'] = $selectoption->item_discount_percent;
	    $ItemRate['discountpercent'] = '0.00';
	   // $ItemRate['discountamount'] = $selectoption->discount_amount;
	    $ItemRate['discountamount'] = '0';
	    $discount = $selectoption->item_discount_percent;
		
		$ItemRate['item_name']	=	$rowitemDetail1->name;		
		$ItemRate['item_code']	=	$rowitemDetail1->item_code;		
				
				$ItemRate['outlet']		=	$rowitemDetail1->ids_mst_outlet;
				$ItemRate['itemRate']	=	round($rate ,2);
				$ItemRate['itemcode']	=	$rowitemDetail1->item_code;
				$ItemRate['item_qty']	=	'1.00';
				$ItemRate['itemunit']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$rowitemDetail1->id_mst_attributes_unit_main."'  ");
				
					$ItemRate['item_amount']	=	round(($rate*$itemqty),2);
				
				
				
				//$ItemRate['item_qty']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id='".$rowitemDetail1->id_mst_attributes_unit_main."' ");
				
				
				$Totalitem_amount	=	$rate*$itemqty;
				
				$item_TotalAmountItem	=	$rate*$itemqty;


			//$DiscountAmount		  =	($item_TotalAmountItem*($_SESSION['LINELEVEL']['outdetailitemID'][$rowitemDetail1->id])/100);
				
				
				$id_mst_charges_sales_local = $rowitemDetail1->id_mst_charges_sales_local;
				$ItemRate['id_mst_charges_sales_local'] = $rowitemDetail1->id_mst_charges_sales_local;
				$ItemRate['id_mst_charges_sales_interstate'] = $rowitemDetail1->id_mst_charges_sales_interstate;

				$resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat1 = $db->fetch_object2($resCat1);
				$Taxapplication1 = $resultCat1->tax_applicable;
				
				$percentage1=($discount/100)*$item_TotalAmountItem;
				    $Totalitem_amount1		=	$Totalitem_amount-$percentage1;
				    $Totalitem_amount		=	$Totalitem_amount;
				    //$ItemRate['item_TotalAmountItem']		=	$Totalitem_amount;
					$SubTotalAmount		 +=($rate*$itemqty);
					$DiscountTotalAmount	 += $percentage1;
					$TotalAmountFinal		+= $Totalitem_amount1;
				
				
				$resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat1 = $db->fetch_object2($resCat1);

				 $Taxapplication 	  = $resultCat1->tax_applicable;
				
				if($Taxapplication==1){
								
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");
							//	}
							$ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
							$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
							$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;	
							$ItemRate['sgst']	=	round($Tax_sgst ,2);
							$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	
							$ItemRate['cgst']	=	round($Tax_cgst ,2);
							$Tax_igst	=	(($Totalitem_amount*($igst))/100);	
							$ItemRate['Tax_igst']	=	round($Tax_igst ,2);
							$Tax_cess	=	(($Totalitem_amount*($cess))/100);
							$ItemRate['Tax_cess']	=	round($Tax_cess ,2);	

							//die;

							$Tax_sgst_percentage	=	$sgst;
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;
							
							
							$ItemRate['Tax_sgst_percentage']	=	$sgst;
							$ItemRate['Tax_cgst_percentage']	=	$cgst;	
							$ItemRate['Tax_igst_percentage']	=	$igst;	
							$ItemRate['Tax_cess_percentage']	=	$cess;
							
							
							$Tax_vat		  = '0';
							$ItemRate['Tax_vat']	=	$Tax_vat;	
							$Tax_surcharge	= '0';
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$TotalAmountItem=	$Totalitem_amount+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess);
							$ItemRate['item_TotalAmountItem'] =	round($TotalAmountItem);
							
					}elseif($Taxapplication==2){
						//echo $Totalitem_amount;
							$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_vat."'");

							$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_surcharge."'");
							
							$ItemRate['sumTaxPersentge']	=$vat+$surcharge;
							$sumTaxAmount	=(($Totalitem_amount*($vat+$surcharge))/100);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount ,2);

							$Tax_sgst	=	'0';	
							$ItemRate['sgst']	=	$Tax_sgst;
							$Tax_cgst	=	'0';
							$ItemRate['cgst']	=	$Tax_cgst;
							$Tax_igst	=	'0';
							$ItemRate['Tax_igst']	=	$Tax_igst;
							$Tax_cess	=	'0';
							$ItemRate['Tax_cess']	=	$Tax_cess;
							$Tax_sgst_percentage	=	'0';	
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		  =	(($Totalitem_amount*($vat))/100);	
							$ItemRate['Tax_vat']	=	$Tax_vat;
							$Tax_surcharge	=	(($Totalitem_amount*($surcharge))/100);
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	$vat;	
							$Tax_surcharge_percentage	=	$surcharge;
							$Tax_vat_id	   = $resultCat1->id_mst_charges_vat;
							$Tax_surcharge_id = $resultCat1->id_mst_charges_surcharge;
							$TotalAmountItem  =	$Totalitem_amount+($Tax_vat+$Tax_surcharge); 
							$ItemRate['item_TotalAmountItem']  =	$TotalAmountItem; 
					}else{

						if($id_mst_charges_sales_local==0){

							$Tax_sgst	=	'0';	
							$ItemRate['sgst']	=	round($Tax_sgst ,2);
							$Tax_cgst	=	'0';
							$ItemRate['cgst']	=	round($Tax_cgst ,2);
							$Tax_igst	=	'0';
							$ItemRate['Tax_igst']	=	round($Tax_igst ,2);
							$Tax_cess	=	'0';	
							$ItemRate['Tax_cess']	=	round($Tax_cess ,2);
							$Tax_sgst_percentage	=	'0';
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		    =    '0';	
							$ItemRate['Tax_vat']	=	round($Tax_vat ,2);
							$Tax_surcharge	  =    '0';
							$ItemRate['Tax_surcharge']	=	round($Tax_surcharge ,2);
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$sumTaxAmount	   =	'0';
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
							$ItemRate['sumTaxPersentge']	= 	'0';
							$TotalAmountItem	=	$Totalitem_amount;
							$ItemRate['item_TotalAmountItem']	=	round($TotalAmountItem);
							}
							}

							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$TotalTax_sgst	=	$TotalTax_sgst_sum+$serviceTotalSGST;	
							$TotalTax_cgst	=	$TotalTax_cgst_sum+$serviceTotalCGST;
							$TotalTax_igst	+=	$Tax_igst;
							$TotalTax_cess	+=	$Tax_cess;
							$TotalTax_vat	+=	$Tax_vat;
							$TotalTax_surcharge	+=	$Tax_surcharge;
							$ItemRate['TotalTax_sgst']	=	$TotalTax_sgst;
							$ItemRate['TotalTax_cgst']	=	$TotalTax_cgst;
							$ItemRate['TotalTax_vat']	=	$TotalTax_vat;
							$ItemRate['TotalTax_cess']	=	$TotalTax_cess;
							$ItemRate['TotalTax_igst']	=	$TotalTax_igst;
							$ItemRate['TotalTax_surcharge']	=	$TotalTax_surcharge;
						
						
							
							
	                //SERVICE CHARGE
					if($revServiceCharge==0 && $revServiceCharge!=''){
						$service_charge_amount='0';		
						$ItemRate['service_charge_amount']	=	$service_charge_amount;	
						$serviceTotalSGST= '0';
						$serviceTotalCGST= '0';
						$serviceChargeTotal	='0';
						$ItemRate['sc_sgst']	=	$serviceTotalSGST;	
						$ItemRate['sc_cgst']	=	$serviceTotalCGST;	
						
					}else{
						$service_charge_amount	=	(($SubTotalAmount*$percentage)/100);		
						$ItemRate['service_charge_amount']	=	$service_charge_amount;		
						$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
						$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
						$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
						$ItemRate['sc_sgst']	=	$serviceTotalSGST;	
						$ItemRate['sc_cgst']	=	$serviceTotalCGST;	
					}
					//SERVICE CHARGE						
							
							
	$totalBeforeRound = ($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_cgst+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];

	 $NetAmount		=	($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$TotalTax_cgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];
	
	//$NetAmount		=	$_SESSION['discountamount'];
	
	$ItemRate['NetAmount']		=	round($NetAmount);

	$RoundOfAmount	=	round((round($NetAmount,0)-$totalBeforeRound),2);
	$ItemRate['RoundOfAmount']	=	$RoundOfAmount;
				
				 
					//$ItemRate['sgst']	=	round($Tax_sgst);
					//$ItemRate['cgst']	=	round($Tax_cgst);
					//$ItemRate['Tax_igst']	=	$Tax_igst;
					//$ItemRate['Tax_cess']	=	$Tax_cess;
					$ItemRate['sumTaxAmount']	=	round($sumTaxAmount,2);
					//$ItemRate['Tax_vat']	=	$Tax_vat;
					//$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
					$ItemRate['discount']	=	round($discount);
					$ItemRate['total']	=	round($TotalAmountFinal);
					$ItemRate['taxAccountName'] =	ucfirst($resultCat1->name);
				
				/*$ItemRate['itemRate']	=	0;
				$ItemRate['itemcode']	=	0;
				$ItemRate['item_qty']	=	0;*/
					}
					
		echo json_encode($ItemRate);			
?>
