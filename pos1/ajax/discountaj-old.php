<?php 

//if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

//}
/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION);
echo '</pre>';*/
?>

<?php
   $rowCount=$_REQUEST['rowCount'];
   $id_attribute_table=$_REQUEST['id_attribute_table'];
   $id_item_type=	$_REQUEST['id_item_type'];
   $UniqueCodeold=$_REQUEST['UniqueCode'];
   $revServiceCharge=$_REQUEST['revServiceCharge'];
   
   $discountamount=$_REQUEST['discountamount'];

  $discountType=$_REQUEST['discountType'];
  
  $discount=$_REQUEST['discount'];
  


  if($discountType==2){	// Additonal Discount

  $_SESSION['discountamount']=$_REQUEST['discountamount'];

  }

   if($discountType==3){	// Additonal Charges

  $_SESSION['AdditionalChargeamount']=$_REQUEST['discountamount'];

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
  
  
  
  
  
  
   $sqlitemDetail ="SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` where id='".$UniqueCodeold."'";
  $resitemName=mysqli_query($connNew,$sqlitemDetail); 
  $numitemDetail=	mysqli_num_rows($resitemName);
  if($numitemDetail>0){
						$i=0;
						$rowitemDetail=mysqli_fetch_object($resitemName);
						
						$ItemRate['dis']	=	$rowitemDetail->id;
						$ItemRate['itemRate']	=	$rowitemDetail->rate;
						$ItemRate['itemcode']	=	$rowitemDetail->sub_code;
						$ItemRate['item_qty']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id='".$rowitemDetail->id_unit."' ");
						///$Totalitem_amount	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
						$item_TotalAmountItem	=	round($rowitemDetail->rate);
						$Totalitem_amount	=	$rowitemDetail->rate;
						
					
					
					/* $DiscountAmount		  =	($item_TotalAmountItem/100);
					$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;
					$SubTotalAmount		 +=($rowitemDetail->sale_rate);
					$DiscountTotalAmount	 +=$DiscountAmount;
					$TotalAmountFinal		+=$Totalitem_amount;	*/
					
						
					$percentage1=($discount/100)*$item_TotalAmountItem;
				    $Totalitem_amount		=	$Totalitem_amount-$percentage1;
					$SubTotalAmount		 +=($rowitemDetail->sale_rate);
					$DiscountTotalAmount	 +=$percentage1;
					$TotalAmountFinal		+=$Totalitem_amount;
					
						   $ItemRate['dis1']	=	$percentage;	
						//$ItemRate['item_amount']	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
						///$item_TotalAmountItem	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
						$item_TotalAmountItem	=	round($rowitemDetail->rate);
						
						$subtotalnew=($item_TotalAmountItem-$percentage);
						
						$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$rowitemDetail->id_item."'");

						$resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

						$resultCat = $db->fetch_object2($resCat);

						 $Taxapplication 	  = $resultCat->tax_applicable;
						
						if($Taxapplication==1){
						
							  $sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_sgst."'");

							 $cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cgst."'");

							 $igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_igst."'");
			
							 $cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cess."'");
							 
							 
							
							$ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
							}
							
							$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
							$Tax_sgst	=	(($subtotalnew*($sgst))/100);//+$serviceTotalSGST;
							$Tax_cgst	=	(($subtotalnew*($cgst))/100);//+$serviceTotalCGST;	
							$Tax_igst	=	(($subtotalnew*($igst1))/100);	
							$Tax_cess	=	(($subtotalnew*($cess))/100);
							$Tax_sgst_percentage	=	$sgst;	
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;
							$Tax_sgst_id	=	$resultCat->id_mst_charges_sgst;
							$Tax_cgst_id	=	$resultCat->id_mst_charges_cgst;
							$Tax_igst_id	=	$resultCat->id_mst_charges_igst;
							$Tax_cess_id	=	$resultCat->id_mst_charges_cess;
							$Tax_vat		  = '0';
							$Tax_surcharge	= '0';
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$ItemRate['item_TotalAmountItem']=	round($subtotalnew+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess)); 	
							///$ItemRate['item_amount']	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
							$ItemRate['item_amount']	=	$rowitemDetail->rate;
							$ItemRate['sgst']	=	round($Tax_sgst);
							$ItemRate['cgst']	=	round($Tax_cgst);
							$ItemRate['Tax_igst']	=	round($Tax_igst);
							$ItemRate['Tax_cess']	=	round($Tax_cess);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount);
							$ItemRate['Tax_vat']	=	round($Tax_vat);
							$ItemRate['Tax_surcharge']	=	round($Tax_surcharge);
							$ItemRate['discount']	=	round($discount);
							$ItemRate['total']	=	round($TotalAmountFinal);
							$ItemRate['taxAccountName'] =	ucfirst($resultCat->name);
						}
						
						else{
  
  $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id='".$UniqueCodeold."' ";
$resitem = mysqli_query($connNew,$itemSql);
$rowitemDetail1=mysqli_fetch_object($resitem);
						$ItemRate['dis']	=	$rowitemDetail1->id;
						$ItemRate['itemrate']	=	$rowitemDetail1->sale_rate;
						$ItemRate['itemcode']	=	$rowitemDetail1->item_code;
						$ItemRate['item_qty']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id='".$rowitemDetail1->id_mst_attributes_unit_main."' ");
						
						//$Totalitem_amount	=	$rowitemDetail1->id_unit*$rowitemDetail1->sale_rate;
						$Totalitem_amount	=	$rowitemDetail1->sale_rate;
						
						//$ItemRate['item_amount']	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
						$item_TotalAmountItem	=	$rowitemDetail1->sale_rate;



					/*$DiscountAmount		  =	($item_TotalAmountItem/100);
					$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;
					$SubTotalAmount		 +=($rowitemDetail->sale_rate);
					$DiscountTotalAmount	 +=$DiscountAmount;
					$TotalAmountFinal		+=$Totalitem_amount; */	


                   $percentage1=($discount/100)*$item_TotalAmountItem;
				   $Totalitem_amount		=	$Totalitem_amount-$percentage1;
					$SubTotalAmount		 +=($rowitemDetail1->sale_rate);
					$DiscountTotalAmount	 +=$percentage1;
					$TotalAmountFinal		+=$Totalitem_amount;	
				   
					$ItemRate['dis1']	=	$percentage1;	
						   
						   
						$item_TotalAmountItem	=	$rowitemDetail1->sale_rate;
						
						$subtotalnew=($item_TotalAmountItem-$percentage);	
						
						$id_mst_charges_sales_local = $rowitemDetail1->id_mst_charges_sales_local;

						$resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

						$resultCat1 = $db->fetch_object2($resCat1);
						$Taxapplication = $resultCat1->tax_applicable;
						
						
						
						if($Taxapplication==1){
								
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");
							//	}
							$ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
							$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
							$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;	
							$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	
							$Tax_igst	=	(($Totalitem_amount*($igst))/100);	
							$Tax_cess	=	(($Totalitem_amount*($cess))/100);	

							//die;

							$Tax_sgst_percentage	=	$sgst;
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;


							/*$Tax_sgst_id	=	$resultCat->id_mst_charges_sgst;
							$Tax_cgst_id	=	$resultCat->id_mst_charges_cgst;
							$Tax_igst_id	=	$resultCat->id_mst_charges_igst;
							$Tax_cess_id	=	$resultCat->id_mst_charges_cess;	
*/

							$Tax_vat		  = '0';	
							$Tax_surcharge	= '0';
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$TotalAmountItem=	$Totalitem_amount+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess);
							$ItemRate['item_TotalAmountItem'] =	$TotalAmountItem;
							
					}elseif($Taxapplication==2){
						
							$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_vat."'");

							$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_surcharge."'");
							
							$ItemRate['sumTaxPersentge']	=$vat+$surcharge;
							$sumTaxAmount	=(($Totalitem_amount*($vat+$surcharge))/100);

							$Tax_sgst	=	'0';	
							$Tax_cgst	=	'0';
							$Tax_igst	=	'0';
							$Tax_cess	=	'0';
							$Tax_sgst_percentage	=	'0';	
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		  =	(($Totalitem_amount*($vat))/100);	
							$Tax_surcharge	=	(($Totalitem_amount*($surcharge))/100);
							$Tax_vat_percentage		  =	$vat;	
							$Tax_surcharge_percentage	=	$surcharge;
							$Tax_vat_id	   = $resultCat->id_mst_charges_vat;
							$Tax_surcharge_id = $resultCat->id_mst_charges_surcharge;
							$TotalAmountItem  =	$Totalitem_amount+($Tax_vat+$Tax_surcharge); 
							$ItemRate['item_TotalAmountItem']  =	$TotalAmountItem; 
					}else{

						if($id_mst_charges_sales_local==0){

							$Tax_sgst	=	'0';	
							$Tax_cgst	=	'0';
							$Tax_igst	=	'0';
							$Tax_cess	=	'0';	
							$Tax_sgst_percentage	=	'0';
							$Tax_cgst_percentage	=	'0';	
							$Tax_igst_percentage	=	'0';	
							$Tax_cess_percentage	=	'0';
							$Tax_sgst_id	=	'0';	
							$Tax_cgst_id	=	'0';	
							$Tax_igst_id	=	'0';	
							$Tax_cess_id	=	'0';
							$Tax_vat		    =    '0';	
							$Tax_surcharge	  =    '0';
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$sumTaxAmount	   =	'0';
							$ItemRate['sumTaxPersentge']	= 	'0';
							$TotalAmountItem	=	$Totalitem_amount;
							$ItemRate['item_TotalAmountItem']	=	$TotalAmountItem;
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
							$ItemRate['TotalTax_cgst']	=	round($TotalTax_cgst);
							$ItemRate['TotalTax_cgst']	=	round($TotalTax_cgst);
							$ItemRate['TotalTax_vat']	=	round($TotalTax_vat);
							$ItemRate['TotalTax_cess']	=	round($TotalTax_cess);
							$ItemRate['TotalTax_igst']	=	round($TotalTax_igst);
							$ItemRate['TotalTax_surcharge']	=	round($TotalTax_surcharge);
						
						// $ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
						
						
						 
						if($Taxapplication1==1){
						
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");

						     
							}
							
					
						   $sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
							$Tax_sgst	=	(($subtotalnew*($sgst))/100);//+$serviceTotalSGST;
							$Tax_cgst	=	(($subtotalnew*($cgst))/100);//+$serviceTotalCGST;	
							$Tax_igst	=	(($subtotalnew*($igst))/100);	
							$Tax_cess	=	(($subtotalnew*($cess))/100);
							$Tax_sgst_percentage	=	$sgst;	
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;
							$Tax_sgst_id	=	$resultCat1->id_mst_charges_sgst;
							$Tax_cgst_id	=	$resultCat1->id_mst_charges_cgst;
							$Tax_igst_id	=	$resultCat1->id_mst_charges_igst;
							$Tax_cess_id	=	$resultCat1->id_mst_charges_cess;
							$Tax_vat		  = '0';
							$Tax_surcharge	= '0';
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$ItemRate['item_TotalAmountItem']=	round($subtotalnew+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess));  
							
					
							
							
							
							
							///$ItemRate['item_amount']	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
							$ItemRate['item_amount']	=	$rowitemDetail1->sale_rate;
							$ItemRate['sgst']	=	round($Tax_sgst);
							$ItemRate['sgst']	=	round($Tax_sgst);
							$ItemRate['cgst']	=	round($Tax_cgst);
							$ItemRate['Tax_igst']	=	round($Tax_igst);
							$ItemRate['Tax_cess']	=	round($Tax_cess);
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount);
							$ItemRate['Tax_vat']	=	round($Tax_vat);
							$ItemRate['Tax_surcharge']	=	round($Tax_surcharge);
							$ItemRate['discount']	=	round($discount);
							$ItemRate['total']	=	round($TotalAmountFinal);
							$ItemRate['taxAccountName'] =	ucfirst($resultCat1->name);
						}
echo json_encode($ItemRate);	



