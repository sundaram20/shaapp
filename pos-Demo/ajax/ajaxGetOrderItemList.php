<?php 

if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}

//debugData($_REQUEST);
//debugData($_SESSION);
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
<br><br>
<div class="row">
  <div class="col-md-12">
  	<div class=" text-center ">
    <h6 class="box-title" style="padding: 5px;margin:0;border-top:1px solid #6e6e633d;font-weight: bold;">Item Bill Details</h6>
  </div>
    <div class="form-group" style="margin-bottom: 1px;" >
      <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >
          <thead style="font-size:10px;padding: 0px 0px;">
            <tr>
              <th style="padding: 5px 9px;"> S.No.&nbsp;</th>
              <th style="width:132px;padding: 5px 9px;">Items Name</th>
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
		  // if($rowOutlet->service_charge_apply=='1' && $_REQUEST['id_posbilling']==''){//$_REQUEST['revServiceChargeDefault']!='0'){
			if($rowOutlet->service_charge_apply=='1' && $_REQUEST['id_posbilling']=='' && $_REQUEST['revServiceChargeDefault']=='0'){
				  $_REQUEST['revServiceCharge'] = $rowOutlet->service_charge_apply;
			   
			   }
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
if($_REQUEST['id_posbilling']!=''){
	$SplitStatusDisable='disabled="disabled"';
}
if($_REQUEST['id_posbilling']==''){
//	$CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-adj_qty>0  $Outlet AND id_pos_purch  IN  (SELECT id FROM pos_purch WHERE pos_bill_type= '1' and cancelled=0 AND id_attribute_table IN(".$_REQUEST['id_attribute_table'].") )";
if($_REQUEST['id_kot']!=''){
	 $id_kot	=$_REQUEST['id_kot'];
	}else{
		$id_kot	=0	;
		}
		
			/*if($_REQUEST['outlet']=='2'){
				$Taxapplication1 = '1';
			}else{// if($_REQUEST['outlet']=='3'){
				$Taxapplication1 = '48';
			}*/
			
			$Taxapplication1	=	$taxtype;
			$Taxapplication1	=	selectColumn('mst_charges','id'," WHERE  tax_applicable='".$Taxapplication1."' and charges_account='1' and status = '1'  ORDER by date_created desc limit 0,1");
			
			
			// debugData($_REQUEST);
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
	 

$editSql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE  id_pos_purch= '".$_REQUEST['id_posbilling']."'");

while($editrow = mysqli_fetch_object($editSql)){
 $edit_id1 = $editrow->id;
  $string1 .= $edit_id1.',';
}
$edit_id = rtrim($string1,','); 

 echo $CheckBlockedTable_Sql = "SELECT * FROM ".TBL_PURCH_DETAILS."  WHERE  id  IN (".$edit_id.")";

// $CheckBlockedTable_Sql = "SELECT * FROM ".TBL_PURCH_DETAILS." AS A  WHERE  id IN (".$ResultupdateRow->id_pos_details_split.")";
}
		//echo $CheckBlockedTable_Sql;
		   $db->query($CheckBlockedTable_Sql);
		   $numRows= $db->num_rows();
			$i=1;
			$inc=1;
	            while($ResultBlockedtable1 = $db->fetch_object()){
					
					
					//Discount Ledger=Start============================
	if($_REQUEST['id_mst_charges_discounts']==0){
		$discount_ledger_percentage  =0;
		$fieldreadonly='readonly';	
		//$readonlyLineWise='readonly';
		
		
		
		if($ResultupdateRow->id_mst_charges_discounts>0  && $ResultupdateRow->discount_charges_percent>0  && $_REQUEST['id_mst_charges_discounts']=='' ){
		$ResultupdateRow->discount_charges_percent;
					$discount_ledger_percentage2  =  selectColumn(TBL_CHARGES,'percentage'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$ResultupdateRow->id_mst_charges_discounts."'");
			if($discount_ledger_percentage2>0){
						
				$readonlyLineWise='readonly';
				$fieldreadonly='readonly';
			}else{
				$fieldreadonly='';	
				$readonlyLineWise='readonly';
				}
		}
	}else{
		
		$discount_ledger_percentage  =  selectColumn(TBL_CHARGES,'percentage'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".$_REQUEST['id_mst_charges_discounts']."'");
			if($discount_ledger_percentage>0){		
				$readonlyLineWise='readonly';
				$fieldreadonly='readonly';
				$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$discount_ledger_percentage;
			}else{
				
					$readonlyLineWise='readonly';
					
					$discount_ledger_percentage=($_REQUEST['total_discount_amount']/$_REQUEST['sub_total_items'])*100;
					$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$discount_ledger_percentage;
				}
		
		
		
		//$fieldreadonly='';
		}
	//Discount Ledger====End=========================	
					
					
					$valueid = $ResultBlockedtable1->id;
	
			?>
			
<input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id; ?>" />
		  
          <?php
		  
	
						$id_inv_items	=	$ResultBlockedtable1->id_mst_items;
					 $item_enable_desc_billing	=	selectColumn(TBL_INV_ITEMS,'item_enable_desc_billing'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_charges_sales_local	=$ResultBlockedtable1->id_mst_charges_sales_local;
						//$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_charges_sales_Interstate	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_interstate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_attributes_unit_mainID	=	selectColumn(TBL_INV_ITEMS,'id_mst_attributes_unit_main'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

						$id_mst_attributes_unit_main	=  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id_shop='".$_SESSION['shop']."' AND  table_name ='unit' and status = '1'  AND `id` = '".$id_mst_attributes_unit_mainID."'");

					$id_mst_items_details	=	$ResultBlockedtable1->id_mst_items_details;	
					 $itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$id_inv_items."' and id='".$id_mst_items_details."' and enabled='1' ";//die;
					
					if (isset($_REQUEST['id']) && $_REQUEST['id'] == $ResultBlockedtable1->id) {
           //   $ResultBlockedtable1->item_amount = $_REQUEST['rate']; // 🔥 override rate
          }

          if(isset($_REQUEST['id']) && $_REQUEST['rate'] != ''){

    $_SESSION['MANUAL_RATE'][$_REQUEST['id']] = $_REQUEST['rate'];

}
/* MANUAL RATE OVERRIDE */

if(isset($_SESSION['MANUAL_RATE'][$ResultBlockedtable1->id])){

    $manualRate = $_SESSION['MANUAL_RATE'][$ResultBlockedtable1->id];

   echo 'Onew====>'.$ResultBlockedtable1->item_amount = $manualRate;

}else{

   echo 'Rwo====>'.  $manualRate = $ResultBlockedtable1->item_amount;

}
					
					if(isset($_REQUEST['desc_id']) && isset($_REQUEST['item_description'])){

    $_SESSION['MANUAL_DESC'][$_REQUEST['desc_id']] = $_REQUEST['item_description'];

}
					$Totalitem_amount = ($manualRate * $ResultBlockedtable1->qty);
					$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
					$itemNameNumRows = mysqli_num_rows($resitemName);
					if($itemNameNumRows>0){
						$rowitemDetail     =  mysqli_fetch_object($resitemName);
						$sale_rate	=	$rowitemDetail->rate;	
						if (isset($_REQUEST['id']) && $_REQUEST['id'] == $ResultBlockedtable1->id) {
    $sale_rate = $_REQUEST['rate']; // 🔥 override rate
}
					}else{
						
						$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
						if (isset($_REQUEST['id']) && $_REQUEST['id'] == $ResultBlockedtable1->id) {
              $sale_rate = $_REQUEST['rate']; // 🔥 override rate
            }
						
						}
					 $sale_rate = round($manualRate, 2);
						//$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
						
						//echo "select * from mst_charges where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."'";
						
						 $resCat = selectSql(TBL_CHARGES," where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');
						$resultCat = $db->fetch_object2($resCat);

						$Taxapplication 	  = $resultCat->tax_applicable;

						$Totalitem_amount	= ($manualRate*$ResultBlockedtable1->qty);

						$lineUniqueCode= 'LINELEVEL'.rand(0000,9999);	 

					;
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
								if($discount_ledger_percentage==0 && $_REQUEST['id_mst_charges_discounts']==''){
								$_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id]=$ResultBlockedtable1->item_discount_percent;
								$discount_ledger_percentage=$ResultupdateRow->discount_charges_percent;	
								}
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
												
							if($ResultBlockedtable1->id==$_REQUEST['UniqueCode'] && $discount_ledger_percentage==0){
								$_SESSION['LINELEVEL']['purchdetailitemID'][$itemUniqueCode]=$_REQUEST['discount'];
							}	
						}
					}
					
					$DiscountAmount		  =	round($Totalitem_amount*($_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id])/100,2);
					
					$SubTotalAmount		 +=($manualRate*$ResultBlockedtable1->qty);
					//$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;
					
					$SubTotalAmountFinal	=	$Totalitem_amount-$DiscountAmount;
					if($resultCat->tax_applicable=='2'){ //VAT Without Disocunt
						$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;						
					}else{
						$Totalitem_amount		=	$Totalitem_amount-$DiscountAmount;					
						}
					
					
					$DiscountTotalAmount	 +=$DiscountAmount;
					$TotalAmountFinal		+=$SubTotalAmountFinal;
				   //SERVICE CHARGE
				   
	
					
				  if($_REQUEST['revServiceCharge'] == 0 && ($_REQUEST['revServiceCharge']!='' || $_REQUEST['revServiceCharge']=='')){
					  
						$service_charge_amount='0';
						$serviceTotalSGST= '0';
						$serviceTotalCGST= '0';
						$serviceChargeTotal	='0';
						
					}else{
						//echo $SubTotalAmount.'<br>';
						//echo $percentage;
					  if(strtotime(date('21-03-2022'))>=strtotime(date('d-m-Y',strtotime($ResultupdateRow->doc_date)))){
							$service_charge_amount	=	((($SubTotalAmount-$DiscountTotalAmount)*$percentage)/100);	
						}else{
							$service_charge_amount	=	((($SubTotalAmount)*$percentage)/100);		
						}
	  $service_charge_amount	=	((($SubTotalAmount-$DiscountTotalAmount)*$percentage)/100);	
						//$service_charge_amount	=	(($SubTotalAmount*$percentage)/100);		
						$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
						$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
						$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
					}
					//SERVICE CHARGE
					

					if($Taxapplication==1){

							

								
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
							$Tax_surcharge	=	(($Tax_vat*($surcharge))/100);//(($Totalitem_amount*($surcharge))/100);
							$Tax_vat_percentage		  =	$vat;	
							$Tax_surcharge_percentage	=	$surcharge;
							$Tax_vat_id	   = $resultCat->id_mst_charges_vat;
							$Tax_surcharge_id = $resultCat->id_mst_charges_surcharge;
							$TotalAmountItem  =	$Totalitem_amount+($Tax_vat+$Tax_surcharge); 
							$TotalTax_sgst_sum	+=	$Tax_sgst;
							$TotalTax_cgst_sum	+=	$Tax_cgst;
							$serviceTotalSGST1 = $serviceTotalSGST;
							$serviceTotalCGST1 = $serviceTotalCGST;
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
				$GetPrevious = '<tr id="row_'.$ResultBlockedtable1->id.'">';
    
    $GetPrevious .= '<td>'.$i++.'</td>';

    if ($item_enable_desc_billing == 1) {

    $GetPrevious .= '
    <td>
        <input type="text"
               class="form-control"
               name="item_description['.$ResultBlockedtable1->id.']"
               id="item_description_'.$ResultBlockedtable1->id.'"
               value="'.htmlspecialchars(
    isset($_SESSION['MANUAL_DESC'][$ResultBlockedtable1->id])
    ? $_SESSION['MANUAL_DESC'][$ResultBlockedtable1->id]
    : $ResultBlockedtable1->item_description
).'"
               style="width:180px;height:24px;padding:2px 6px;">
    </td>';

} else {

    $GetPrevious .= '
    <td id="desc_'.$ResultBlockedtable1->id.'">
        '.$ResultBlockedtable1->item_description.'
    </td>';
}

    $GetPrevious .= '<td id="qty_'.$ResultBlockedtable1->id.'">'.round($ResultBlockedtable1->qty).'</td>';

$rate = round($manualRate, 2);

if ($item_enable_desc_billing == 1) {

    $GetPrevious .= '
    <td>
        <input type="text"
               class="form-control rate_input"
               name="rate['.$ResultBlockedtable1->id.']"
               id="rate_'.$ResultBlockedtable1->id.'"
               value="'.$rate.'"
               data-id="'.$ResultBlockedtable1->id.'"
               data-qty="'.$ResultBlockedtable1->qty.'"
               onchange="updateRate(this)"
               style="width:80px;height:24px;padding:2px 6px;">
    </td>';

} else {

    $GetPrevious .= '<td id="rate_text_'.$ResultBlockedtable1->id.'">'.$rate.'-11</td>';
}

/* Amount */
$amount = $manualRate * $ResultBlockedtable1->qty;

$GetPrevious .= '
<td id="amount_'.$ResultBlockedtable1->id.'">'.round($amount,2).'</td>';

$from = 1;

$GetPrevious .= '<td>';

if($inc > 1){
    $GetPrevious .= '
    <i style="margin-left:22px;"
       onclick="fillup(\''.$inc.'\',\''.$from.'\',\''.$ResultBlockedtable1->id.'\');"
       class="arrows fa fa-angle-double-up"></i>';
}

if($_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id] == ''){
    $val = '0.00';
}else{
    $val = $_SESSION['LINELEVEL']['purchdetailitemID'][$ResultBlockedtable1->id];
}

$GetPrevious .= '
<input type="text"
       '.$readonlyLineWise.'
       class="form-control first-input discountvalue"
       name="discount|'.$ResultBlockedtable1->id.'"
       id="discount_'.$ResultBlockedtable1->id.'"
       value="'.round($val,2).'"
       style="width: 60px;float: left;padding: 1px 12px;height: 24px;"
       onchange="calculateDiscountSingleItem('.$ResultBlockedtable1->id.',2,this.value)"
       pattern="^\d*(\.\d{0,2})?$" >
<br>';

if($numRows != $inc){

    $GetPrevious .= '
    <i style="margin-left:22px;"
       onclick="filldown(\''.$inc.'\',\''.$numRows.'\',\''.$ResultBlockedtable1->id.'\');"
       class="arrows fa fa-angle-double-down"></i>';

    $GetPrevious .= '</td>';
}

/* Discount Amount */
$GetPrevious .= '
<td>
<input type="text"
       class="form-control"
       readonly
       name="quantity|'.$uniqueCode.'"
       id="discount_amount_'.$ResultBlockedtable1->id.'"
       value="'.$DiscountAmount.'"
       style="width: 60px;float: left;padding: 1px 12px;height: 24px;">
</td>';

/* Tax Name */
$GetPrevious .= '
<td id="tax_name_'.$ResultBlockedtable1->id.'">'.$name.'</td>';

/* Tax % */
$GetPrevious .= '<td id="tax_percent_'.$ResultBlockedtable1->id.'">'.round($sumTaxPersentge,2).'</td>';

/* Tax Amount */
$GetPrevious .= '<td id="tax_amount_'.$ResultBlockedtable1->id.'">'.round($sumTaxAmount,2).'</td>';

/* SGST */
$GetPrevious .= '<td id="sgst_'.$ResultBlockedtable1->id.'">'.round($Tax_sgst,2).'</td>';

/* CGST */
$GetPrevious .= '<td id="cgst_'.$ResultBlockedtable1->id.'">'.round($Tax_cgst,2).'</td>';

/* IGST */
$GetPrevious .= '<td id="igst_'.$ResultBlockedtable1->id.'">'.round($Tax_igst,2).'</td>';

/* CESS */
$GetPrevious .= '<td id="cess_'.$ResultBlockedtable1->id.'">'.round($Tax_cess,2).'</td>';

/* VAT */
$GetPrevious .= '<td id="vat_'.$ResultBlockedtable1->id.'">'.round($Tax_vat,2).'</td>';

/* Surcharge */
$GetPrevious .= '<td id="surcharge_'.$ResultBlockedtable1->id.'">'.round($Tax_surcharge,2).'</td>';

/* Total */
$GetPrevious .= '<td id="total_'.$ResultBlockedtable1->id.'">'.round($TotalAmountItem,2).'</td>';

/* Split */
$GetPrevious .= '<td>
<input type="hidden"
       class="form-control first-input discountvalue"
       name="id_pos_detail['.$ResultBlockedtable1->id.'][item_BillSplit]"
       value="1"
       style="width: 50px;float: left;padding: 1px 12px;height: 24px;"
       '.$SplitStatusDisable.'/>
</td>

</tr>';

echo $GetPrevious;
$inc++;
				//	echo '<br><br>';
?><input type="hidden" name="item_rate" value="<?php echo round($manualRate); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id_mst_items; ?>" />
        <input type="hidden" 
name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_description]" 
id="hidden_desc_<?php echo $ResultBlockedtable1->id; ?>" 
value="<?php echo htmlspecialchars(
    isset($_SESSION['MANUAL_DESC'][$ResultBlockedtable1->id])
    ? $_SESSION['MANUAL_DESC'][$ResultBlockedtable1->id]
    : $ResultBlockedtable1->item_description
); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_qty]" id="id_pos_detail" value="<?php echo round($ResultBlockedtable1->qty); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_rate]" id="id_pos_detail" value="<?php echo round($manualRate); ?>" />
          <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_amount]" id="id_pos_detail" value="<?php echo ($manualRate*$ResultBlockedtable1->qty); ?>" />
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
		  
		   <input type="hidden" name="id_pos_detail[<?php echo $ResultBlockedtable1->id; ?>][item_TotalAmountItem1]" id="id_pos_detail" value="<?php echo round($manualRate*$ResultBlockedtable1->qty,2); ?>" />
		  
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
</div><?php  //debugData($_SESSION);?>
<div class="card text-dark bg-light">
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-12 col-sm-12">
    	<div class=" text-center  ">
           <h6 class="box-title" style="padding: 5px;margin:0;border-top:1px solid #6e6e633d;font-weight: bold;">Billing Summary</h6>

  </div>
    </div></div>
    
    <div class="row" style="margin-bottom: 10px;">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
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
    </div>
    
    
    
      <div class="row">
      	 <div class="form-group col-xs-12 col-md-3 col-sm-2">
      	 </div>
    <div class="form-group col-xs-12 col-md-2 col-sm-2">
      <label for="name">Discount Ledger</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-plus"></i> </div>
        <select onchange="checksessionNotapplicable(this.value,this.id,1);" class="form-control select2" name="id_mst_charges_discounts" id="id_mst_charges_discounts"  style="width:100%;" >
											<?php   $categoryDropDown = '<option value="0">Not Applicable</option>';
											  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '6'  ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($ResultupdateRow->id_mst_charges_discounts == $resultCat->id && $_REQUEST['id_mst_charges_discounts']==''){
														$selected = 'selected="selected"';
													}elseif( $_REQUEST['id_mst_charges_discounts'] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
											<?php echo $err_item_chargestax;?>
                                             </div>
    </div>
    <div class="form-group col-xs-12 col-md-2 col-sm-2">
      <label for="name">% Discount</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
        <input type="text" class="form-control" placeholder="% Discount" id="total_discount_percentage" name="total_discount_percentage" value="<?php  echo $DiscountTotalAmount>0?round(($DiscountTotalAmount/$SubTotalAmount)*100,2):0; ?>" <?php echo $fieldreadonly;?> >
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-2 col-sm-2">
      <label for="name">Discount Amount</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
        <input type="text" class="form-control" onchange="calculateDiscountLedger(this.id,0);" placeholder="Discount" id="total_discount_amount" name="total_discount_amount" value="<?php echo stripslashes($DiscountTotalAmount); ?>" <?php echo $fieldreadonly;?>>
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
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
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
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-3">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
        <input type="text" class="form-control" placeholder="Service Charges" id="service_charge_amount" name="service_charge_amount" value="<?php if($service_charge_amount) echo $service_charge_amount;else echo '0';?>" onKeyup="additionalDiscount(3,this.value);" style="text-align:right;" readonly>
      </div>
    </div>
  </div>
  <!-- SGST -->
  <?php if($taxtype==1 || $TotalTax_sgst>0){?>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">SGST<?php //echo $serviceTotalSGST;?></label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-down"></i> </div>
        <input type="text" class="form-control" placeholder="SGST" id="sgst_net_amount" name="sgst_net_amount" value="<?php echo stripslashes(round($TotalTax_sgst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- CGST -->
  <?php }?>
   <?php if($taxtype==1 || $TotalTax_cgst>0){?> 
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 ">
      <label for="name">CGST<?php //echo $serviceTotalCGST;?></label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-left"></i> </div>
        <input type="text" class="form-control" placeholder="CGST" id="cgst_net_amount" name="cgst_net_amount" value="<?php echo stripslashes(round($TotalTax_cgst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- IGST -->
   <?php }?>
   <?php if($taxtype==1){?> 
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">IGST</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="IGST" id="igst_net_amount" name="igst_net_amount" value="<?php echo stripslashes(round($TotalTax_igst,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
   <?php }?>
   <?php if($taxtype==1){?> 
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">CESS</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-right"></i> </div>
        <input type="text" class="form-control" placeholder="CESS" id="cess_net_amount" name="cess_net_amount" value="<?php echo stripslashes(round($TotalTax_cess,2)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
    <?php }?>
	<?php if($taxtype==2){?>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">VAT</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
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
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
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
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-cog"></i> </div>
        <input type="text" class="form-control discountvalue" placeholder="Discount Amount" id="additional_discount_amount" name="additional_discount_amount" value="<?php if($_SESSION['discountamount']) echo $_SESSION['discountamount'];else echo '0';?>" onchange="additionalDiscount(2,this.value);" style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- Round Amount -->
  
  <div class="row">
     <div class="form-group col-xs-12 col-md-2 col-sm-2" >
   
    <?php 
	//$SelectGuest_sql="SELECT * FROM pos_guest   WHERE  find_in_set('".$ResultupdateRow->id."',ids_pos_purch)";
	if($ResultupdateRow->id!=''){
	 $SelectGuest_sql="SELECT * FROM pos_guest   WHERE  find_in_set('".$ResultupdateRow->id."',ids_pos_purch)";
	$resultPosGuest = mysqli_query($connNew, $SelectGuest_sql); 
	$numGuestRows = mysqli_num_rows($resultPosGuest);
	$posGuestResult = mysqli_fetch_object($resultPosGuest);
	}
	
	?>
     Guest Mobile
      <input type="text" class="form-control" placeholder="Guest Mobile" id="guest_mobile" name="guest_mobile" value="<?php echo $posGuestResult->mobile; ?>"  >
         
      
    
    
    </div>
    <div class="form-group col-xs-12 col-md-2 col-sm-2 " style="padding-left:0;">
   
    Guest Name
     <input type="text" class="form-control" placeholder="Guest Name" id="guest_name" name="guest_name" value="<?php echo $posGuestResult->name; ?>" >
     
         
      
    
    
    </div>
    

     <div class="form-group col-xs-12 col-md-2 col-sm-2">
     </div>	
   
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Round Off </label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
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
    <div class="form-group col-xs-12 col-md-3 col-sm-2 mb-0">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-plus-square"></i> </div>
        <input type="text" class="form-control" placeholder="Net Amount" id="net_amount" name="net_amount" value="<?php echo stripslashes(round($NetAmount,0)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
</div>
<?php } ?>


<script type="text/javascript">
	
function fillup(fillupfrom,filluptill,fillupid){
	
	var fillupVal = $("input[name='discount|"+fillupid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	//alert(id_posbilling);
	var data2 = '&fillupfrom='+fillupfrom+'&filluptill='+filluptill+'&fillupid='+fillupid+'&fillupVal='+fillupVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
				
//		lineUniqueCode,type,discount	
				
	 	}
	});
	
}
</script>


<script type="text/javascript">
function filldown(filldownfrom,filldowntill,filldownid){
	
	var filldownVal = $("input[name='discount|"+filldownid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	//alert(id_posbilling);
	var data2 = '&filldownfrom='+filldownfrom+'&filldowntill='+filldowntill+'&filldownid='+filldownid+'&filldownVal='+filldownVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
		
	 	}
	});
	
}
	
</script> 

<?php //echo $valueid; ?>


<script>

$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));        
});

</script>

<script type='text/javascript' >
    $( function() {
  
        $( "#guest_name" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "ajax/fetchNamePosGuestDetails.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );

                    }
                });
            },
            select: function (event, ui) {
                $('#guest_name').val(ui.item.label); // display the selected text
                $('#guest_mobile').val(ui.item.value); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $( "#guest_name" ).val( ui.item.label );
                $( "#guest_mobile" ).val( ui.item.value );
                return false;
            },
        });

      
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }



    //mobile auto first
    $( function() {
  
        $( "#guest_mobile" ).autocomplete({
            source: function( request, response ) {
                
                $.ajax({
                    url: "ajax/fetchMobilePosGuestDetails.php",
                    type: 'post',
                    dataType: "json",
                    data: {
                        search: request.term
                    },
                    success: function( data ) {
                        response( data );

                    }
                });
            },
            select: function (event, ui) {
                $('#guest_mobile').val(ui.item.label); // display the selected text
                $('#guest_name').val(ui.item.value); // save selected id to input
                return false;
            },
            focus: function(event, ui){
                $( "#guest_mobile" ).val( ui.item.label );
                $( "#guest_name" ).val( ui.item.value );
                return false;
            },
        });

     
    });

    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

function updateRate(el) {

    // 🔹 Get values
    var id   = $(el).data('id') || 0;
    var rate = $(el).val() || 0;
    var qty  = $(el).data('qty') || 1;

    var id_attribute_table = $("#id_attribute_table").val() || '';
    var outlet             = $("#outlet").val() || '';
    var id_kot             = $("#id_kot").val() || '';
    var id_posbilling      = $("#id_posbilling").val() || '';
  var item_description = $('#item_description_' + id).val() || '';
    // 🔒 Validation
    if (!id || rate === '') {
        alert('Invalid data.');
        return;
    }

    /* =========================
       LIVE UI UPDATE
    ========================== */

    // Amount
    var amount = parseFloat(rate) * parseFloat(qty);
    $("#amount_" + id).html(amount.toFixed(2));

    // Tax %
    var taxPercent = parseFloat($("#tax_percent_" + id).text()) || 0;

    // Tax Amount
    var taxAmount = (amount * taxPercent) / 100;
    $("#tax_amount_" + id).html(taxAmount.toFixed(2));

    // SGST
    var sgst = taxAmount / 2;
    $("#sgst_" + id).html(sgst.toFixed(2));

    // CGST
    var cgst = taxAmount / 2;
    $("#cgst_" + id).html(cgst.toFixed(2));

    // Total
    var total = amount + taxAmount;
    $("#total_" + id).html(total.toFixed(2));

    /* =========================
       AJAX SAVE
    ========================== */

    $.ajax({
        url: 'ajax/ajaxGetOrderItemList.php',
        type: 'POST',
        data: 'id='+id+'&rate='+rate+'&id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+'&DbConnect=1&item_description='+item_description+'&desc_id='+id,

        beforeSend: function () {
            $("#ViewOrderItemList").css("opacity", "0.5");
        },

        success: function (result) {

            // Optional full refresh
            $("#ViewOrderItemList").html(result);

            console.log("Rate Updated");
        },

        error: function (xhr, status, error) {

            console.log(xhr.responseText);
            alert("Server Error");
        },

        complete: function () {
            $("#ViewOrderItemList").css("opacity", "1");
        }
    });
}
	
	$(document).on('keyup change', 'input[name^="item_description"]', function () {

    var id = $(this).attr('id').replace('item_description_', '');

    $('#hidden_desc_' + id).val($(this).val());

});
    </script>