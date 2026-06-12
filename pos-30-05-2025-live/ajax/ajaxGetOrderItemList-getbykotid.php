<?php 

if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}
/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION);
echo '</pre>';*/
?>

<?php

 $id_attribute_table=$_REQUEST['id_attribute_table'];
 $UniqueCodeold=$_REQUEST['UniqueCode'];
 $discountType=$_REQUEST['discountType'];
  if($discountType==2){	// Additonal Discount
	 $_SESSION['discountamount']=$_REQUEST['discountamount'];
  }

  if($discountType==3){	// Additonal Charges
	 $_SESSION['AdditionalChargeamount']=$_REQUEST['discountamount'];
  }

if($id_attribute_table){
	//BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);
?>

<div class="row">
  <div class="col-md-12">
    <div class="form-group" style="margin-bottom: 1px;" >
      <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >
          <thead style="font-size:10px;padding: 0px 0px;">
            <tr>
              <th style="padding: 5px 9px;"> S.No.&nbsp;</th>
              <th style="width:200px;padding: 5px 9px;">Items Name</th>
              <th style="padding: 5px 9px;">Qty</th>
              <th style="padding: 5px 9px;">Rate</th>
              <th style="padding: 5px 9px;">Amount</th>
              <th style="padding: 5px 9px;">Disc%</th>
              <th style="padding: 5px 9px;">Disc.Amount</th>
              <th style="padding: 5px 9px;">Tax A/c</th>
              <th style="padding: 5px 9px;">Tax %</th>
              <th style="padding: 5px 9px;">Tax Amt</th>
              <th style="padding: 5px 9px;">SGST</th>
              <th style="padding: 5px 9px;">CGST</th>
              <th style="padding: 5px 9px;">IGST</th>
              <th style="padding: 5px 9px;">CESS</th>
              <th style="padding: 5px 9px;">VAT</th>
              <th style="padding: 5px 9px;">Surch</th>
              <th style="padding: 5px 9px;">Total</th>
              <th style="padding: 5px 9px;">SPLIT</th>
            </tr>
          </thead>
          <tbody>
          <input type="hidden" name="id_attribute_table" id="id_attribute_table" value="<?php echo $_REQUEST['id_attribute_table']; ?>" />
           <input type="hidden" name="outlet" id="outlet" value="<?php echo $_REQUEST['outlet']; ?>" />
          <?php 
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
			
		$Outlet	="AND FIND_IN_SET('".$_REQUEST['outlet']."',pos_purch_details.id_mst_outlet )";
		
if($_REQUEST['id_posbilling']==''){
//	$CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0  $Outlet AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id_attribute_table IN(".$_REQUEST['id_attribute_table'].") )";
if($_REQUEST['id_kot']!=''){
	 $id_kot	=$_REQUEST['id_kot'];
	}else{
		$id_kot	=0	;
		}
		
		
			if($_REQUEST['outlet']=='2'){
				$Taxapplication1 = '1';
			}else if($_REQUEST['outlet']=='3'){
				$Taxapplication1 = '2';
			}
			
			 
//$CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0  $Outlet AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id IN(".$id_kot.") )";
 
 $CheckBlockedTable_Sql = "SELECT pos_purch_details.id,pos_purch_details.*, mst_charges.tax_applicable FROM pos_purch_details LEFT JOIN mst_charges ON mst_charges.id = pos_purch_details.id_mst_charges_sales_local WHERE pos_purch_details.qty-adj_qty>0 $Outlet AND pos_purch_details.id_pos_purch IN (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id IN(".$id_kot.") AND doc_type='22' ) AND mst_charges.tax_applicable IN (SELECT tax_applicable FROM mst_charges WHERE id= ".$Taxapplication1." ) ";
}else{
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2'  AND id= '".$_REQUEST['id_posbilling']."'");
      $ResultupdateRow = mysqli_fetch_object($updateSql);
	   $ResultupdateRow->sc_reverse;
	   $kot_doc_no=$ResultupdateRow->kot_doc_no;
	   $pos_id=$ResultupdateRow->id;
	   if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$ResultupdateRow->discount_amount_additional;
	 }

 $CheckBlockedTable_Sql = "SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$ResultupdateRow->id_pos_details_split.")";
}
		//echo $CheckBlockedTable_Sql;
		   $db->query($CheckBlockedTable_Sql);
		   $numRows= $db->num_rows();
			$i=1;
			$inc=1;
	            while($ResultBlockedtable1 = $db->fetch_object()){
			?>
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id; ?>" />
          <?php
						$id_inv_items	=	$ResultBlockedtable1->id_mst_items;
						$id_mst_charges_sales_local	=$ResultBlockedtable1->id_mst_charges_sales_local;
						//$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_charges_sales_Interstate	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_interstate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_attributes_unit_mainID	=	selectColumn(TBL_INV_ITEMS,'id_mst_attributes_unit_main'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_attributes_unit_main	=  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id_shop='".$_SESSION['shop']."' AND  table_name ='unit' and status = '1'  AND `id` = '".$id_mst_attributes_unit_mainID."'");

						$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
						
						//echo "select * from mst_charges where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."'";
						
						 $resCat = selectSql(TBL_CHARGES," where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');
						$resultCat = $db->fetch_object2($resCat);

						$Taxapplication 	  = $resultCat->tax_applicable;

						$Totalitem_amount	= ($ResultBlockedtable1->item_amount*$ResultBlockedtable1->qty);

						$lineUniqueCode= 'LINELEVEL'.rand(0000,9999);	 

					
					$itemUniqueCode	=$_REQUEST['UniqueCode'];
					if($_REQUEST['id_posbilling']!='' && $_REQUEST['filluptill']=='' && $_REQUEST['filldowntill']==''){
						
						if($_SESSION['LINELEVEL']['purchdetailitemID'][$itemUniqueCode]>0){	
						    
						//$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$ResultBlockedtable1->item_discount_percent;						
						if($ResultBlockedtable1->id==$_REQUEST['UniqueCode']){
						    
								$_SESSION['LINELEVEL']['purchdetailitemID'][$itemUniqueCode]=$_REQUEST['discount'];
							}
						}else{
						    
						
							if($ResultBlockedtable1->id==$_REQUEST['UniqueCode']){
								$_SESSION['LINELEVEL']['purchdetailitemID'][$itemUniqueCode]=$_REQUEST['discount'];
							}else{
								$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$ResultBlockedtable1->item_discount_percent;						    
							}	
						}
					}elseif($_REQUEST['filluptill']!=''){
						
					if($_REQUEST['fillupfrom']>=$inc){
						
						$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$_REQUEST['fillupVal'];
						}
					}elseif($_REQUEST['filldowntill']!=''){
						
					if($_REQUEST['filldownfrom']<=$inc){
						
						$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$_REQUEST['filldownVal'];
						}
					}
					else{
					if($ResultBlockedtable1->item_discount_percent>0){	
										
						$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$ResultBlockedtable1->item_discount_percent;						
						}else{
												
							if($ResultBlockedtable1->id==$_REQUEST['UniqueCode']){
								$_SESSION['LINELEVEL']['purchdetailitemID'][$itemUniqueCode]=$_REQUEST['discount'];
							}	
						}
					}
					
					$DiscountAmount		  =	($Totalitem_amount*($_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id])/100);
					$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;
					$SubTotalAmount		 +=($ResultBlockedtable1->item_amount*$ResultBlockedtable1->qty);
					$DiscountTotalAmount	 +=$DiscountAmount;
					$TotalAmountFinal		+=$Totalitem_amount;
				   //SERVICE CHARGE
				   
				
					
				  if($_REQUEST['revServiceCharge'] == 0 && ($_REQUEST['revServiceCharge']!='' || $_REQUEST['revServiceCharge']=='')){
					  
						$service_charge_amount='0';
						$serviceTotalSGST= '0';
						$serviceTotalCGST= '0';
						$serviceChargeTotal	='0';
						
					}else{
						//echo $SubTotalAmount.'<br>';
						//echo $percentage;
						$service_charge_amount	=	(($SubTotalAmount*$percentage)/100);		
						$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
						$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
						$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
					}
					//SERVICE CHARGE
					

					if($Taxapplication==1){

							

						//if($_REQUEST['id_posbilling']==''){	
							/*$sgst	=	$ResultBlockedtable1->item_sgst_percent;
							$cgst	=	$ResultBlockedtable1->item_cgst_percent;

							$igst	=	$ResultBlockedtable1->item_igst_percent;
			
							$cess	=	$ResultBlockedtable1->item_cess_percent;*/
							/* $sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_cess."'");
*/
							//}else{
								
							$sgst	=	$ResultBlockedtable1->item_sgst_percent;
							$cgst	=	$ResultBlockedtable1->item_cgst_percent;
							$igst	=	$ResultBlockedtable1->item_igst_percent;
							$cess	=	$ResultBlockedtable1->item_cess_percent;
							//	}
							$sumTaxPersentge	=$sgst+$cgst+$igst+$cess;
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
							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$serviceTotalSGST1 = $serviceTotalSGST;
							$serviceTotalCGST1 = $serviceTotalCGST;
							
					}elseif($Taxapplication==2){
						
							$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_vat."'");

							$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_surcharge."'");
							
							$sumTaxPersentge	=$vat+$surcharge;
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
							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$serviceTotalSGST1 = '0';
							$serviceTotalCGST1 = '0';
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
							$sumTaxPersentge	= 	'0';
							$TotalAmountItem	=	$Totalitem_amount;
							
							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$serviceTotalSGST1 = $serviceTotalSGST;
							$serviceTotalCGST1 = $serviceTotalCGST;
							}
							}
							
							
							$TotalTax_sgst	=	$TotalTax_sgst_sum+$serviceTotalSGST1;	
							$TotalTax_cgst	=	$TotalTax_cgst_sum+$serviceTotalCGST1;
							$TotalTax_igst	+=	$Tax_igst;
							$TotalTax_cess	+=	$Tax_cess;
							$TotalTax_vat	+=	$Tax_vat;
							$TotalTax_surcharge	+=	$Tax_surcharge;
		
	$totalBeforeRound = ($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_cgst+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];

	$NetAmount		=	($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$TotalTax_cgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];

	$RoundOfAmount	=	round((round($NetAmount,0)-$totalBeforeRound),2);

				$name =	ucfirst($resultCat->name);
					
				//.'--KOT:'.$ResultBlockedtable1->id_pos_purch
				$GetPrevious	='<tr>
					
				<td>'.$i++.'</td>

				<td>'.$ResultBlockedtable1->item_description.'</td>

				<td>'.round($ResultBlockedtable1->qty).'</td>

				<td>'.round($ResultBlockedtable1->item_amount).'</td>

				<td>'.$ResultBlockedtable1->item_amount*$ResultBlockedtable1->qty.'</td>';
				
				$from=1;
				$GetPrevious	.='<td>';
				if($inc>1){
				$GetPrevious	.='<i style="margin-left:22px;" onclick="fillup(\''.$inc.'\',\''.$from.'\',\''.$ResultBlockedtable1->id.'\');" class="arrows fa fa-angle-double-up"></i>';
				}
				
				if($_REQUEST['id_posbilling']==''){
					$GetPrevious	.='
					<input type="text" class="form-control first-input"  name="discount|'.$ResultBlockedtable1->id.'" id="discount|'.$ResultBlockedtable1->id.'" value="0.00"   style="width: 60px;float: left;padding: 1px 12px;height: 24px;" onchange="calculateDiscountSingleItem('.$ResultBlockedtable1->id.',2,this.value)"  autofocus>
					<br>';
				}else{				
					$GetPrevious	.='
					<input type="text" class="form-control first-input"  name="discount|'.$ResultBlockedtable1->id.'" id="discount|'.$ResultBlockedtable1->id.'" value="'.$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id].'"   style="width: 60px;float: left;padding: 1px 12px;height: 24px;" onchange="calculateDiscountSingleItem('.$ResultBlockedtable1->id.',2,this.value)"  autofocus>
					<br>';
				}
				
				
				
				if($numRows!=$inc){
				$GetPrevious	.='<i style="margin-left:22px;" onclick="filldown(\''.$inc.'\',\''.$numRows.'\',\''.$ResultBlockedtable1->id.'\');" class="arrows fa fa-angle-double-down"></i>';
                $GetPrevious	.='</td>';
				}

				$GetPrevious	.='<td><input type="text" class="form-control"  readonly name="quantity|'.$uniqueCode.'" id="quantity|'.$uniqueCode.'" value="'.$DiscountAmount.'"   style="width: 60px;float: left;padding: 1px 12px;height: 24px;" ></td>

				<td>'.$name.'</td>

				<td>'.round($sumTaxPersentge,2).'</td>

				<td>'.round($sumTaxAmount,2).'</td>

				<td>'.round($Tax_sgst,2).'</td>

				<td>'.round($Tax_cgst,2).'</td>

				<td>'.round($Tax_igst,2).'</td>

				<td>'.round($Tax_cess,2).'</td>

				<td>'.round($Tax_vat,2).'</td>

				<td>'.round($Tax_surcharge,2).'</td>

				<td>'.round($TotalAmountItem,2).'</td>

				<td><input type="text" class="form-control first-input" name="id_pos_detail['.$ResultBlockedtable1->id.'][item_BillSplit]" id="id_pos_detail" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/></td>

				</tr>';

echo $GetPrevious;
$inc++;
?>
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id_mst_items; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_description]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->item_description; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_qty]" id="id_pos_detail" value="<?php echo round($ResultBlockedtable1->qty); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_rate]" id="id_pos_detail" value="<?php echo round($ResultBlockedtable1->item_amount); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_amount]" id="id_pos_detail" value="<?php echo ($ResultBlockedtable1->item_amount*$ResultBlockedtable1->qty); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_discount_amount]" id="id_pos_detail" value="<?php echo $DiscountAmount; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_discount_percentage]" id="id_pos_detail" value="<?php echo $_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_tax_account_name]" id="id_pos_detail" value="<?php echo $name; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_tax_account_id]" id="id_pos_detail" value="<?php echo $resultCat->id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_sumTaxPersentge]" id="id_pos_detail" value="<?php echo round($sumTaxPersentge,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_sumTaxAmount]" id="id_pos_detail" value="<?php echo round($sumTaxAmount,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_sgst]" id="id_pos_detail" value="<?php echo round($Tax_sgst,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cgst]" id="id_pos_detail" value="<?php echo round($Tax_cgst,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_igst]" id="id_pos_detail" value="<?php echo round($Tax_igst,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cess]" id="id_pos_detail" value="<?php echo round($Tax_cess,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_vat]" id="id_pos_detail" value="<?php echo round($Tax_vat,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_surcharge]" id="id_pos_detail" value="<?php echo round($Tax_surcharge,2); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_TotalAmountItem]" id="id_pos_detail" value="<?php echo round($TotalAmountItem,2); ?>" />
		  
		   <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_TotalAmountItem1]" id="id_pos_detail" value="<?php echo round($ResultBlockedtable1->item_amount*$ResultBlockedtable1->qty,2); ?>" />
		  
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_sgst_percentage]" id="id_pos_detail" value="<?php echo $Tax_sgst_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cgst_percentage]" id="id_pos_detail" value="<?php echo $Tax_cgst_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_igst_percentage]" id="id_pos_detail" value="<?php echo $Tax_igst_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cess_percentage]" id="id_pos_detail" value="<?php echo $Tax_cess_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_vat_percentage]" id="id_pos_detail"  value="<?php echo $Tax_vat_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_surcharge_percentage]" id="id_pos_detail" value="<?php echo $Tax_surcharge_percentage; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_sgst_id]" id="id_pos_detail" value="<?php echo $Tax_sgst_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cgst_id]" id="id_pos_detail" value="<?php echo $Tax_cgst_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_igst_id]" id="id_pos_detail" value="<?php echo $Tax_igst_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_cess_id]" id="id_pos_detail" value="<?php echo $Tax_cess_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_vat_id]" id="id_pos_detail"  value="<?php echo $Tax_vat_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_Tax_surcharge_id]" id="id_pos_detail" value="<?php echo $Tax_surcharge_id; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][id_mst_charges_sales_Interstate]" id="id_pos_detail"  value="<?php echo $id_mst_charges_sales_Interstate; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][id_mst_charges_sales_local]" id="id_pos_detail" value="<?php echo $id_mst_charges_sales_local; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][id_mst_attributes_unit_main]" id="id_pos_detail"  value="<?php echo $id_mst_attributes_unit_main; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][sale_rate]" id="id_pos_detail" value="<?php echo $sale_rate; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][discount_amount_additional]" id="id_pos_detail" value="<?php echo $_SESSION['discountamount']; ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][others_charges_net_amount]" id="id_pos_detail" value="<?php echo $_SESSION['AdditionalChargeamount']; ?>" />
           <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][sc_charges_net_amount]" id="id_pos_detail" value="<?php echo $_SESSION['service_charge_amount']; ?>" />
            <input type="hidden" name="sc_sgst" id="sc_sgst" value="<?php echo $serviceTotalSGST; ?>" />
            <input type="hidden" name="sc_cgst" id="sc_cgst" value="<?php echo $serviceTotalCGST; ?>" />

          <?php } ?>
            </tbody>
          
        </table>
      </div>
    </div>
  </div>
</div>
<div class="card text-dark bg-light">
  <div class="bg-primary text-center ">
    <h5 style="padding: 5px;">Total Amount</h5>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Sub Total</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-plus"></i> </div>
        <?php 
			if($row->id == ''){
				$sub_total_items = 0;
			}else{
				$sub_total_items = $row->sub_total_items;
			}
		?>
        <input type="text" class="form-control" placeholder="Sub Total" id="sub_total_items" name="sub_total_items" value="<?php echo stripslashes($SubTotalAmount); ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Discount</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
        <input type="text" class="form-control" placeholder="Discount" id="total_discount_amount" name="total_discount_amount" value="<?php echo stripslashes($DiscountTotalAmount); ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Total</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
        <input type="text" class="form-control" placeholder="Total" id="net_amount_items" name="net_amount_items" value="<?php echo stripslashes($TotalAmountFinal); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Service Charges 
      <?php 
	  
	  //echo $_REQUEST['revServiceCharge'];
	if($ResultupdateRow->sc_reverse==0 && $_REQUEST['revServiceCharge']!=1){
			?>
             <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
            <?php
			  }else{
	  
	  if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge']!=''){?>
     <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
      <?php }else{ 
	  
	  ?>
        <input type="checkbox" class="minimal-red" value="1" <?php echo $sc_reverse0; ?> name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" checked="checked"></label>
     <?php } } ?>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
        <input type="text" class="form-control" placeholder="Service Charges" id="service_charge_amount" name="service_charge_amount" value="<?php if($service_charge_amount) echo $service_charge_amount;else echo '0';?>" onKeyup="additionalDiscount(3,this.value);" style="text-align:right;">
      </div>
    </div>
  </div>
  <!-- SGST -->
  <?php if($taxtype==1){?>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">SGST<?php //echo $serviceTotalSGST;?></label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-down"></i> </div>
        <input type="text" class="form-control" placeholder="SGST" id="sgst_net_amount" name="sgst_net_amount" value="<?php echo stripslashes(round($TotalTax_sgst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- CGST -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">CGST<?php //echo $serviceTotalCGST;?></label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-left"></i> </div>
        <input type="text" class="form-control" placeholder="CGST" id="cgst_net_amount" name="cgst_net_amount" value="<?php echo stripslashes(round($TotalTax_cgst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- IGST -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">IGST</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="IGST" id="igst_net_amount" name="igst_net_amount" value="<?php echo stripslashes(round($TotalTax_igst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">CESS</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="CESS" id="cess_net_amount" name="cess_net_amount" value="<?php echo stripslashes(round($TotalTax_cess,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
    <?php }
	if($taxtype==2){?>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">VAT</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="VAT" id="vat_net_amount" name="vat_net_amount" value="<?php echo round($TotalTax_vat,2); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Surcharge</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="surcharge" id="surcharge_net_amount" name="surcharge_net_amount" value="<?php echo stripslashes(round($TotalTax_surcharge,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
    <?php } ?>
  <?php /*?><div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Others Charges</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
        <input type="text" class="form-control" placeholder="Others Charges" id="others_charges_net_amount" name="others_charges_net_amount" value="<?php if($_SESSION['AdditionalChargeamount']) echo $_SESSION['AdditionalChargeamount'];else echo '';?>" onKeyup="additionalDiscount(3,this.value);" style="text-align:right;">
      </div>
    </div>
  </div><?php */?>
  
  <!-- Additional Discount -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Additional Discount Amount</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-cog"></i> </div>
        <input type="text" class="form-control" placeholder="Discount Amount" id="additional_discount_amount" name="additional_discount_amount" value="<?php if($_SESSION['discountamount']) echo $_SESSION['discountamount'];else echo '0';?>" onchange="additionalDiscount(2,this.value);" style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- Round Amount -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Round Off </label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-plus-square"></i> </div>
        <input type="text" class="form-control" placeholder="Round Amount" id="round_off_amount" name="round_off_amount" value="<?php echo $RoundOfAmount; ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- Net Amount -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Net Amount</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-plus-square"></i> </div>
        <input type="text" class="form-control" placeholder="Net Amount" id="net_amount" name="net_amount" value="<?php echo stripslashes(round($NetAmount,0)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
</div>
<?php } ?>
