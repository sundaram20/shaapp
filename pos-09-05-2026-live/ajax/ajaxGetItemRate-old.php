<?php include_once("../../config/auto_loader.php");
$rowCount=$_REQUEST['rowCount'];
$id	=	$_REQUEST['id'];
?>
	
	<?php
		 $sqlitemDetail = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$id."'");

				$numitemDetail=	mysqli_num_rows($sqlitemDetail);
				if($numitemDetail>0){
				$i=0;
				$rowitemDetail=mysqli_fetch_object($sqlitemDetail);
				
				$ItemRate['dis']	=	$rowitemDetail->id;
				$ItemRate['itemRate']	=	$rowitemDetail->rate;
				$ItemRate['itemcode']	=	$rowitemDetail->sub_code;
				$ItemRate['item_qty']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id='".$rowitemDetail->id_unit."' ");
				///$Totalitem_amount	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
				$Totalitem_amount	=	$rowitemDetail->rate;
				
				//$ItemRate['item_amount']	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
				///$item_TotalAmountItem	=	$rowitemDetail->id_unit*$rowitemDetail->rate;
				$item_TotalAmountItem	=	$rowitemDetail->rate;
				
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
					$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;
					$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	
					$Tax_igst	=	(($Totalitem_amount*($igst))/100);	
					$Tax_cess	=	(($Totalitem_amount*($cess))/100);
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
					
				$sqlitemDetail1 = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS."` WHERE id='".$id."'");
				$numitemDetail1 =  mysqli_num_rows($sqlitemDetail1);
				$rowitemDetail1 =  mysqli_fetch_object($sqlitemDetail1);
				
				$ItemRate['dis']	=	$rowitemDetail1->id;
				$ItemRate['itemRate']	=	$rowitemDetail1->sale_rate;
				$ItemRate['itemcode']	=	$rowitemDetail1->item_code;
				$ItemRate['item_qty']	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id='".$rowitemDetail1->id_mst_attributes_unit_main."' ");
				
				//$Totalitem_amount	=	$rowitemDetail1->id_unit*$rowitemDetail1->sale_rate;
				$Totalitem_amount	=	$rowitemDetail1->sale_rate;
				
				//$ItemRate['item_amount']	=	$rowitemDetail1->id_unit*$rowitemDetail1->rate;
				$item_TotalAmountItem	=	$rowitemDetail1->sale_rate;


			//$DiscountAmount		  =	($item_TotalAmountItem*($_SESSION['LINELEVEL']['outdetailitemID'][$rowitemDetail1->id])/100);
			 $percentage=($discount/100)*$item_TotalAmountItem;
			$Totalitem_amount		=	$Totalitem_amount-$percentage;
			$SubTotalAmount		 +=($rowitemDetail1->sale_rate);
			$DiscountTotalAmount	 +=$percentage;
				
			$ItemRate['dis1']	=	$percentage;	
				
				$id_mst_charges_sales_local = $rowitemDetail1->id_mst_charges_sales_local;

				$resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat1 = $db->fetch_object2($resCat1);
				$Taxapplication1 = $resultCat1->tax_applicable;
				 
				if($Taxapplication1==1){
				
					$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

					$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

					$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
	
					$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");

				$ItemRate['sumTaxPersentge']	=$sgst+$cgst+$igst+$cess;
					}
					
				$sumTaxAmount	=(($Totalitem_amount*($sgst+$cgst+$igst+$cess))/100);
				   
					$Tax_sgst	=	(($Totalitem_amount*($sgst))/100);//+$serviceTotalSGST;	
					$Tax_cgst	=	(($Totalitem_amount*($cgst))/100);//+$serviceTotalCGST;	
					$Tax_igst	=	(($Totalitem_amount*($igst))/100);	
					$Tax_cess	=	(($Totalitem_amount*($cess))/100);	
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
					//$ItemRate['item_amount']	=	$rowitemDetail1->id_unit*$rowitemDetail1->rate;
					$ItemRate['item_amount']	=	$rowitemDetail1->sale_rate;
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
				
				/*$ItemRate['itemRate']	=	0;
				$ItemRate['itemcode']	=	0;
				$ItemRate['item_qty']	=	0;*/
					}
					
		echo json_encode($ItemRate);			
?>
