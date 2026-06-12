
<?php 
 
if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}

?>

<?php
 $vals=	$_REQUEST['vals'];
 $array_s =  explode(",", $vals);
 $valcount =  count($array_s);
 
$counter1=	$_REQUEST['counter1'];
$outleType=	$_REQUEST['outleType'];
$outlet=	$_REQUEST['outlet'];
 $id_item_type=	$_REQUEST['id_item_type'];
$delid=	$_REQUEST['id'];
//BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);

/////////////////////////////////////////////////////////////////
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
			
			$Outlet	="AND FIND_IN_SET('".$_REQUEST['outlet']."',id_mst_outlet )"; 
			$Outlet1	="FIND_IN_SET('".$_REQUEST['outlet']."',id_mst_outlet )"; 
 
 
if($_REQUEST['id_posbilling']==''){
if($_REQUEST['outlet']!=''){
	$id_outlet	= $_REQUEST['outlet'];
	}else{
		$id_outlet	=0	;
		}
  $CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-bal_qty>0  $Outlet AND id_inv_purch  IN  (SELECT id FROM pos_purch WHERE  id_mst_outlet  IN(".$id_outlet.") )";	
}else{
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE id= '".$_REQUEST['id_posbilling']."'");
      $ResultupdateRow = mysqli_fetch_object($updateSql);
	   $ResultupdateRow->sc_reverse;
	  $others_charges_net_amount = $ResultupdateRow->others_charges_net_amount;
	  $disc_amount_additional1 = $ResultupdateRow->discount_amount_additional;
	   $kot_doc_no=$ResultupdateRow->kot_doc_no;
	   if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$ResultupdateRow->discount_amount_additional;
	   }
		
  $CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE $Outlet1";
}
		//echo $CheckBlockedTable_Sql;
		$db->query($CheckBlockedTable_Sql); 
	    $ResultBlockedtable1 = $db->fetch_object();
		   $numRows= $db->num_rows();
			$i=1;
			$inc=1;
			 $id_inv_items = $ResultBlockedtable1->id_mst_items; 
			 $ResultBlockedtable1->id;
			 
			$id_mst_charges_sales_Interstate	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_interstate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

			$id_mst_attributes_unit_mainID	=	selectColumn(TBL_INV_ITEMS,'id_mst_attributes_unit_main'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");

			$id_mst_attributes_unit_main	=  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id_shop='".$_SESSION['shop']."' AND  table_name ='unit' and status = '1'  AND `id` = '".$id_mst_attributes_unit_mainID."'");

			$sale_rate	=	selectColumn(TBL_INV_ITEMS,'sale_rate'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$id_inv_items."'");
			 
			 
	        $CheckBlockedTable_Sql1 = "SELECT * FROM inv_items WHERE id = '".$id_inv_items."' ";		 
	        $db->query($CheckBlockedTable_Sql1);
            $ResultBlockedtable2 = $db->fetch_object();	
		    $ResultBlockedtable2->name; 
			
			
			
				   
			  
			    $resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$ResultBlockedtable2->id_mst_charges_sales_local."' ",'');
				$resultCat1 = $db->fetch_object2($resCat1);
	
/*			 
$GetPrevious	='<tr>
<td>'.$i++.'</td>
<td>'.round($ResultBlockedtable1->qty).'</td>
</tr>';
echo $GetPrevious;
$inc++;
*/	

		 
?>	
 

 
<?php 
 

for($i=0;$i<count($array_s);$i++){
	
	$id_item_type_s = $array_s[$i];
 
?>
<tr id="trdelete<?php echo $counter1?>">

<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $delid?>][item_amount]" id="item_amount<?php echo $delid?>" value="0" style="width:70px;" readonly="readonly" /> 


<td> <?php 
   $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND status='1' AND id_mst_attributes_item_type='".$id_item_type."'  "; 
  


//Id Inv Get 
    //$itemSql_s = "SELECT * FROM `".TBL_INV_PURCH_DETAILS."` where id='".$id_item_type_s."'  "; 
    $itemSql_s = "SELECT * FROM `".TBL_PURCH_DETAILS."` where id IN ($id_item_type_s)  "; 
	
    //Id inv items
	 $resitem_s = mysqli_query($connNew,$itemSql_s);
	 
	 while ($rowItem_s = mysqli_fetch_object($resitem_s)){
	    $id_inv_items = $rowItem_s->id_mst_items; 
	    $id_no = $rowItem_s->id; 
	    $id_inv_items_details = $rowItem_s->id_mst_items_details; 
	    $qty_s = $rowItem_s->qty; 
	    $discount = $rowItem_s->item_discount_percent;
	   // $discount_amount = $rowItem_s->discount_amount;
	    $sgst_amount = $rowItem_s->item_sgst_amount;
	    $cgst_amount = $rowItem_s->item_cgst_amount;
	    $igst_amount = $rowItem_s->item_igst_amount;
	    $cess_amount = $rowItem_s->item_cess_amount;
	    $vat_amount = $rowItem_s->item_vat_amount;
	    $sur_amount = $rowItem_s->item_surcharge_amount;
	    $discount_amount1 = $rowItem_s->item_discount_amount;
	    $main_unit = $rowItem_s->main_unit;
	    $item_Name = $rowItem_s->item_description;
	    $item_code1 = $rowItem_s->item_code;
		
			 $subitems_id =  $rowItem_s->id_mst_items_details;
			 $items_id =  $rowItem_s->id_mst_items;
			 $outlet =  $rowItem_s->id_mst_outlet;
			 $id_mst_charges_sales_local =  $rowItem_s->id_mst_charges_sales_local;
			 $id_mst_charges_sales_interstate =  $rowItem_s->id_mst_charges_sales_interstate;
			 $item_rate =  $rowItem_s->rate_per_main_unit;
		
	 }
	 
 $itemSql0 = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND status='0' AND id='".$id_inv_items."' ";  
	 
	 
	 
//echo  $items_id.'<br>';
//echo  $subitems_id;
 //$sqlitemDetail2 = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$id_inv_items."'");
 $sqlitemDetail2 = mysqli_query($connNew,"SELECT *  from `".TBL_INV_ITEMS_DETAILS."` WHERE id='".$id_inv_items_details."'");
	$numitemDetail=	mysqli_num_rows($sqlitemDetail2);
	 
		if($numitemDetail>0){
			//echo "detail";
			$sqlitemDetail = "SELECT *  from `".TBL_PURCH_DETAILS."` WHERE `id_mst_items_details` IN ($id_inv_items_details) ";
			$resitem1 = mysqli_query($connNew,$sqlitemDetail);
			$rowitemDetail = mysqli_fetch_object($resitem1);
	        $id_purch_details = $rowitemDetail->id; 
	        $discount_amount = $rowitemDetail->item_discount_amount;
			 $newid =  $rowitemDetail->id_mst_items_details;
			 
				$itemcode = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` where id='".$newid."'  "; 
				$itemcode_s = mysqli_query($connNew,$itemcode);
				 
				while ($itemcode_ss = mysqli_fetch_object($itemcode_s)){
					$item_code = $itemcode_ss->sub_code; 
					$id_mst_attributes_unit_main = $itemcode_ss->id_unit; 
					$sale_rate = $itemcode_ss->rate; 
					 $itemm_name = $itemcode_ss->name; 
					//$id_mst_charges_sales_local = $itemcode_ss->id_mst_charges_sales_local;
					//$id_mst_charges_sales_local	=	selectColumn(TBL_INV_ITEMS,'id_mst_charges_sales_local'," WHERE  `id` = '".$itemcode_ss->id_item."'");		
				}
		}else{
			//echo "main";
		     $sqlitemDetail = "SELECT *  from `".TBL_PURCH_DETAILS."` WHERE `id_mst_items` IN ($id_inv_items) ";
			$resitem1 = mysqli_query($connNew,$sqlitemDetail);
			$rowitemDetail = mysqli_fetch_object($resitem1);
	        $id_purch_details = $rowitemDetail->id; 
	        $discount_amount = $rowitemDetail->item_discount_amount;
			$newid =  $rowitemDetail->id_mst_items;
			
				$itemcode = "SELECT * FROM `".TBL_INV_ITEMS."` where id='".$newid."'  "; 
				$itemcode_s = mysqli_query($connNew,$itemcode);
				 
				while ($itemcode_ss = mysqli_fetch_object($itemcode_s)){
					$item_code = $itemcode_ss->item_code; 
					$itemm_name = $itemcode_ss->name; 
					$id_mst_attributes_unit_main = $itemcode_ss->id_mst_attributes_unit_main; 
					$sale_rate = $itemcode_ss->sale_rate ; 
					//$id_mst_charges_sales_local = $itemcode_ss->id_mst_charges_sales_local;
				}	
		}
		
		
		
		
		//echo $sale_rate;
		 $rate_amount1 = ($item_rate * $qty_s);
		 
		 $rate_amount = $rate_amount1 - $discount_amount1;
	 
	// echo $discount_amount1;

	
	 $mainunit = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$main_unit."'  ");
	 $resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat1 = $db->fetch_object2($resCat1);
				 $Taxapplication 	  = $resultCat1->tax_applicable;
				$Taxapplication1 	  = $resultCat1->name;
				if($Taxapplication==1){
							//echo $rate_amount;	
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");
							$tax	=$sgst+$cgst+$igst+$cess;
							$sumTaxAmount	=(($rate_amount*($sgst+$cgst+$igst+$cess))/100);
							$taxamount	=	$sumTaxAmount;
							$Tax_sgst	=	(($rate_amount*($sgst))/100);
							$ItemRate['sgst']	=	$Tax_sgst;
							$Tax_cgst	=	(($rate_amount*($cgst))/100);
							$ItemRate['cgst']	=	$Tax_cgst;
							$Tax_igst	=	(($rate_amount*($igst))/100);	
							$ItemRate['Tax_igst']	=	$Tax_igst;
							$Tax_cess	=	(($rate_amount*($cess))/100);
							$ItemRate['Tax_cess']	=	$Tax_cess;	
							$Tax_sgst_percentage	=	$sgst;
							$Tax_cgst_percentage	=	$cgst;	
							$Tax_igst_percentage	=	$igst;	
							$Tax_cess_percentage	=	$cess;
							 $Tax_vat		  = '0';
							$ItemRate['Tax_vat']	=	$Tax_vat;	
							$Tax_surcharge	= '0';
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	'0';
							$Tax_surcharge_percentage	=	'0';
							$Tax_vat_id	   = '0';
							$Tax_surcharge_id = '0';
							$TotalAmountItem=	$rate_amount+($Tax_sgst+$Tax_cgst+$Tax_igst+$Tax_cess);
				}
				elseif($Taxapplication==2){
					$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_vat."'");
					$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_surcharge."'");
					$tax	=$vat+$surcharge;
					$sumTaxAmount	=(($rate_amount*($vat+$surcharge))/100);
					$taxamount	=	$sumTaxAmount;

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
							$Tax_vat		  =	(($rate_amount*($vat))/100);	
							$ItemRate['Tax_vat']	=	$Tax_vat;
							$Tax_surcharge	=	(($rate_amount*($surcharge))/100);
							$ItemRate['Tax_surcharge']	=	$Tax_surcharge;
							$Tax_vat_percentage		  =	$vat;	
							$Tax_surcharge_percentage	=	$surcharge;
							$Tax_vat_id	   = $resultCat->id_mst_charges_vat;
							$Tax_surcharge_id = $resultCat->id_mst_charges_surcharge;
							$TotalAmountItem  =	$rate_amount+($Tax_vat+$Tax_surcharge); 
				}else{

						if($id_mst_charges_sales_local==0){

							$Tax_sgst	=	'0';	
							$ItemRate['sgst']	=	$Tax_sgst;
							$Tax_cgst	=	'0';
							$ItemRate['cgst']	=	$Tax_cgst;
							$Tax_igst	=	'0';
							$ItemRate['Tax_igst']	=$Tax_igst;
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
							$ItemRate['sumTaxAmount']	=	round($sumTaxAmount);
							$ItemRate['sumTaxPersentge']	= 	'0';
							$TotalAmountItem	=	$rate_amount;
						//	$ItemRate['item_TotalAmountItem']	=	round($TotalAmountItem);
							}
							}

	
                   $percentage1=($discount/100)*$rate_amount1;
				   $Totalitem_amount		=	($rate_amount+$sumTaxAmount);//-$percentage1;
				  // echo '===>'.($rate_amount.'--'.$sumTaxAmount).'--'.$percentage1;
				  // $Totalitem_amount		=	$Totalitem_amount;
				   $total_item		=	$Totalitem_amount;
				   
	 
	 
  ?>
  <?php //echo $id_inv_items; ?>
<select class="form-control select2" name="itemName[]" id="itemName<?php echo $counter1?>" onchange="getItemRate(this.value,<?php echo $counter1?>);" data-parsley-required data-parsley-errors-container="#itemNameError" style="width:180px;" >
		
 
<?php

$resitem = mysqli_query($connNew,$itemSql);
$resitem0 = mysqli_query($connNew,$itemSql0);

 $SelectItemList='';
 
 
 while ($rowItem0 = mysqli_fetch_object($resitem0)){

		if($rowItem0->id != ''){
		$itemNameSelectSQL0 = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$rowItem0->id."' ";
		$resitemName0=mysqli_query($connNew,$itemNameSelectSQL0); 
		$itemNameNumRows0 = mysqli_num_rows($resitemName0);
		if($itemNameNumRows0>0){
			$SelectItemList .='<optgroup label="'.$rowItem0->name.'">';
				while($rowitemName0 = mysqli_fetch_object($resitemName0)){
					
					if($_REQUEST['itemName'] == $rowitemName0->id){
						$selected = 'selected="selected"';
					}elseif($item_Name == $rowitemName0->name){
						 $selected = 'selected="selected"';
					}else{
						$selected = '';
					}  
					$SelectItemList .='<option '.$selected.' value="'.$rowitemName0->id.'">'.ucwords($rowitemName0->name).' - '.$rowItem0->name.'</option>';
				}
			$SelectItemList .=	'</optgroup>';
	    }else{
			    if($_REQUEST['itemName'] == $rowItem0->id){
					$selected = 'selected="selected"';
				}elseif($item_Name == $rowItem0->name){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}  
			$SelectItemList .='<option '.$selected.' value="'.$rowItem0->id.'">'.ucwords($rowItem0->name).'</option>';
		}
		
	}
	}
 

	while ($rowItem = mysqli_fetch_object($resitem)){

		//print_r($rowState);
		$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$rowItem->id."' ";
		$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
		$itemNameNumRows = mysqli_num_rows($resitemName);
		if($itemNameNumRows>0){
			$SelectItemList .='<optgroup label="'.$rowItem->name.'">';
				while($rowitemName = mysqli_fetch_object($resitemName)){
					
					if($_REQUEST['itemName'] == $rowitemName->id){
						$selected = 'selected="selected"';
					}elseif($item_Name == $rowitemName->name){
						 $selected = 'selected="selected"';
					}else{
						$selected = '';
					}  
					$SelectItemList .='<option '.$selected.' value="'.$rowitemName->id.'">'.ucwords($rowitemName->name).' - '.$rowItem->name.'</option>';
				}
			$SelectItemList .=	'</optgroup>';
	    }else{
			    if($_REQUEST['itemName'] == $rowItem->id){
					$selected = 'selected="selected"';
				}elseif($item_Name == $rowItem->name){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}  
			$SelectItemList .='<option '.$selected.' value="'.$rowItem->id.'">'.ucwords($rowItem->name).'</option>';
		}
		
	}
	
	echo $SelectItemList ;
	?>            		
</select>
         </td>
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id; ?>" /> 

<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_no]" id="id_pos_detail" value="<?php echo $id_no; ?>" />
	
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][items_idd]" id="items_idd<?php echo $counter1; ?>" value="<?php echo $items_id ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][mainitem_id]" id="mainitem_id<?php echo $counter1; ?>" value="<?php echo $id_inv_items; ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][subitems_id]" id="subitems_id<?php echo $counter1; ?>" value="<?php echo $subitems_id ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][outlet]" id="outlet<?php echo $counter1; ?>" value="<?php echo $outlet ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_mst_charges_sales_local]" id="id_mst_charges_sales_local<?php echo $counter1; ?>" value="<?php echo $id_mst_charges_sales_local ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_amount]" id="item_amount1<?php echo $counter1; ?>" value="<?php echo $item_rate ?>" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_name]" id="item_name<?php echo $counter1; ?>" value="<?php echo $item_Name ?>" /> 
	
	 <input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_inv_detail]" id="id_purch_details<?php echo $counter1?>" value="<?php echo $id_purch_details ?>" />
	
     <input type="hidden" name="counter" id="<?php echo $counter1?>" value="<?php echo $counter1 ?>" />  
 
		<td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_subcode]" id="item_subcode<?php echo $counter1?>" value="<?php echo $item_code1;?>" readonly="readonly" style="width:70px;"/>        
        </td>
		<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_code]" id="item_code<?php echo $counter1; ?>" value="<?php echo $item_code1;?>" /> 
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control discountvalue" type="text" name="id_pos_detail[<?php echo $counter1?>][item_qty]" id="item_qty<?php echo $counter1?>"  value="<?php echo $qty_s;?>" onKeyup="itemqty<?php echo $counter1?>(<?php echo $counter1?>,2,this.value,<?php echo $item_rate;?>)" style="width:50px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_unit]" id="item_unit<?php echo $counter1?>" value="<?php echo $main_unit; ?>" readonly style="width:80px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_rate]" id="item_rate<?php echo $counter1?>" value="<?php echo $item_rate;?>" style="width:70px;" readonly="readonly" />        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_amount]" id="item_amount<?php echo $counter1?>" value="<?php echo round($rate_amount1,2)?>" style="width:70px;" readonly="readonly" />        
        </td>
		
		<!-- <div id="getid"> </div> -->
		
        <td class="form-group col-md-2">
		   <input type="hidden" id="per<?php echo $counter1?>" value="<?php echo $newid ?>" >
		   <input type="hidden" id="countper<?php echo $counter1?>" value="<?php echo $counter1?>" >
		   <input type="hidden" id="countper" value="<?php echo $counter1?>" >
		    <input type="hidden" id="valcount" value="<?php echo $valcount?>" >
			
				
	
		   
          <!-- <input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $selectoption->discount_percent; ?>" onchange="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> -->

		   <input autocomplete="off" class="form-control discountvalue" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $discount;?>" onKeyup="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value,<?php echo $item_rate;?>)"  style="width:50px;" /> 
        </td>
		
	
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_dis]" id="item_dis1<?php echo $counter1?>" readonly  value="<?php echo $percentage1; ?>" style="width:100px;" /> 
        </td>
		
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][taxAccountName]" id="taxAccountName<?php echo $counter1?>" readonly value="<?php echo $Taxapplication1; ?>"  style="width:150px;" />        
        </td>
		
				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtax]" id="sumtax<?php echo $counter1?>" value="<?php echo $tax; ?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($sumTaxPersentge,2);?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtaxamount]" id="sumtaxamount<?php echo $counter1?>" value="<?php echo $taxamount; ?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($sumTaxAmount,2); ?></td>

				<td><input class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sgst]" id="item_sgst<?php echo $counter1?>" value="<?php echo round($Tax_sgst,2);?>" readonly style="width:60px;" />
				<?php //echo stripslashes($Tax_sgst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cgst]" id="item_cgst<?php echo $counter1?>" value="<?php echo round($Tax_cgst,2);?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_cgst,2); ?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_igst]" id="item_igst<?php echo $counter1?>" value="<?php echo round($Tax_igst,2);?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_igst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cess]" id="item_cess<?php echo $counter1?>" value="<?php echo round($Tax_cess,2);?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_cess,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_vat]" id="item_vat<?php echo $counter1?>" value="<?php echo round($Tax_vat,2);?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_vat,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sur]" id="item_sur<?php echo $counter1?>" value="<?php echo round($Tax_surcharge,2);?>" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_surcharge,2); ?></td>
        
				<td class="col-xs-12 col-md-3 col-sm-2"> 
					<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][total]" id="total<?php echo $counter1?>" value="<?php echo round($total_item,2) ?>"  style="width:80px;" readonly="readonly" />        
				</td>
				
				
		<input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][discount_amount_additional]" id="discount_amount" value="<?php echo $disc_amount_additional1 ?>" />
		
        <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][others_charges_net_amount]" id="othercharges" value="<?php echo $others_charges_net_amount ?>" />
		
		<input type="hidden" name="sc_sgst" id="sc_sgst" value="" />
        <input type="hidden" name="sc_cgst" id="sc_cgst" value="" />
		 
        <input type="hidden" name="Tax_sgst_percentage" id="Tax_sgst_percentage<?php echo $counter1 ?>" value="" />
        <input type="hidden" name="Tax_cgst_percentage" id="Tax_cgst_percentage<?php echo $counter1 ?>" value="" />
        <input type="hidden" name="Tax_igst_percentage" id="Tax_igst_percentage<?php echo $counter1 ?>" value="" />
        <input type="hidden" name="Tax_cess_percentage" id="Tax_cess_percentage<?php echo $counter1 ?>" value="" />
        <input type="hidden" name="Tax_surcharge_percentage" id="Tax_surcharge_percentage<?php echo $counter1 ?>" value="" />
        <input type="hidden" name="Tax_vat_percentage" id="Tax_vat_percentage<?php echo $counter1 ?>" value="" />
		
		 <input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_mst_charges_sales_Interstate]" id="id_pos_detail"  value="<?php echo $id_mst_charges_sales_Interstate; ?>" />
		 
		 
		 <input type="hidden" class="form-control first-input" name="id_pos_detail[<?php echo $counter1 ?>][item_BillSplit]" id="id_pos_detail<?php echo $counter1?>" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/>
		
		
		 <input type="hidden" class="form-control first-input" name="id_pos_detail[<?php echo $counter1 ?>][item_BillSplit1]" id="id_pos_detail<?php echo $counter1?>" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/>
		
		                       
    <td><a   onclick="deleteitemRow(<?php echo $counter1?>,<?php echo $id_item_type_s?>);" class="n-btn  abtn ibtnDel1" id="deletes<?php echo $counter1?>" name="deletes<?php echo $counter1?>" style="cursor:pointer;" title="Delete"><i class="fa fa-trash-o"></i></a></td>
	
<?php 
  // }
?>
	
     </tr>   



	<script>
function calculateDiscountSingleItem<?php echo $counter1 ?>(count,type,discount,rate){
	//alert(discount);
	var countper=document.getElementById("countper").value;
	var idnew=document.getElementById("per"+count).value;
	var idnew1=document.getElementById("valcount").value;
	var item_qty=document.getElementById("item_qty"+count).value;
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	
	var counter1 =  $("#counter1").val();  
	
	var sub_total = document.getElementById("sub_total_items").value;
	
	//alert(counter1);
	
		
var sub = document.getElementById("sub_total_items").value;	
var discount1 = document.getElementById("discount").value;
var total = $("#totalvalue").val;
var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;
var amount = document.getElementById("item_amount"+count).value;
var ssgst1 = document.getElementById("item_sgst"+count).value;
var csgst = document.getElementById("item_cgst"+count).value;
var isgst = document.getElementById("item_igst"+count).value;
var cesgst = document.getElementById("item_cess"+count).value;
var vsgst = document.getElementById("item_vat"+count).value;
var susgst = document.getElementById("item_sur"+count).value;
var disam = document.getElementById("item_dis1"+count).value;
	
//var TotalTax_sgst2 = document.getElementById("sc_sgst1").value;
//var TotalTax_cgst2 = document.getElementById("sc_cgst1").value;
	
	$.ajax({
		type: "POST",
		//url: 'ajax/ajaxGetLaundrySpaOrderItemList.php',
		url: 'ajax/spadiscount.php',
		data: 'id_attribute_table='+opts+'&rowCount='+count+'&rate='+rate+'&DbConnect=1&discount='+discount+'&sub_total='+sub_total+'&UniqueCode='+idnew+'&item_qty='+item_qty+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_item_type='+id_item_type+'&DbConnect=1', 
		success: function (result) {
			//alert(result);
			data = JSON.parse(result);
			$( "#id"+count).val(data.dis);
			$( "#total"+count).val(data.item_TotalAmountItem);
			$( "#sumtax"+count).val(data.sumTaxPersentge);
			$( "#sumtaxamount"+count).val(data.sumTaxAmount);
			$( "#item_sgst"+count).val(data.sgst);	
			$( "#item_cgst"+count).val(data.cgst);
			$( "#item_igst"+count).val(data.Tax_igst);
			$( "#item_cess"+count).val(data.Tax_cess);
			$( "#item_vat"+count).val(data.Tax_vat);
			$( "#item_sur"+count).val(data.Tax_surcharge);
			$( "#item_dis1"+count).val(data.dis1);
			//$( "#discount").val(data.dis1);
			$( "#net_amount").val(data.NetAmount);
			$( "#service_charge_amount").val(data.service_charge_amount);
			$( "#total").val(data.total);
			$("#taxAccountName"+count).val(data.taxAccountName);
			$("#additional_discount_amount").val(data.adddis);
			$("#sc_sgst").val(data.sc_sgst);
			$("#sc_cgst").val(data.sc_cgst);
			$("#othercharges").val(data.Additional);
			$("#discount_amount").val(data.discountt);
			var serviceChargeTotal = data.serviceChargeTotal;
			var serviceTotalSGST = data.sc_sgst;
			var serviceTotalCGST = data.sc_cgst;
			var Additional = data.Additional;
			var discountt = data.discountt;
			
			var itemdiscount  =  document.getElementById("item_dis1"+count).value;
				
		var itemsubtotal = document.getElementById("item_amount"+count).value;
		var itemtotal = document.getElementById("total"+count).value;
		var itemsgst = document.getElementById("item_sgst"+count).value;
		var itemcgst = document.getElementById("item_cgst"+count).value;
		var itemigst = document.getElementById("item_igst"+count).value;
		var itemcess = document.getElementById("item_cess"+count).value;
		var itemvat = document.getElementById("item_vat"+count).value;
		var itemsur = document.getElementById("item_sur"+count).value;
	
	var subtotal = parseFloat(sub);
	
	var discounttotal = ((parseFloat(discount1) + parseFloat(itemdiscount))-parseFloat(disam)).toFixed(2);
	//alert(itemdiscount);
	//alert(itemdiscount);
	var totalvalue = parseFloat(subtotal) - parseFloat(discounttotal);
	var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - parseFloat(ssgst1)).toFixed(2);
	
	var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - parseFloat(csgst)).toFixed(2);
	//var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - (parseFloat(ssgst1)+parseFloat(TotalTax_sgst2))).toFixed(2);
	//var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - (parseFloat(csgst)+parseFloat(TotalTax_cgst2))).toFixed(2);
	var total_igst = ((parseFloat(igst) + parseFloat(itemigst)) - parseFloat(isgst)).toFixed(2);
	var total_cess = ((parseFloat(cess) + parseFloat(itemcess)) - parseFloat(cesgst)).toFixed(2);
	var total_vat = ((parseFloat(vat) + parseFloat(itemvat)) - parseFloat(vsgst)).toFixed(2);
	var total_sur = ((parseFloat(sur) + parseFloat(itemsur)) - parseFloat(susgst)).toFixed(2);
	
	
	$( "#totalvalue").val(totalvalue.toFixed(2));
	$( "#sub_total_items").val(subtotal.toFixed(2));
	$( "#discount").val(discounttotal);
	$( "#TotalTax_sgst").val(total_sgst);
	$( "#TotalTax_sgst1").val(total_sgst);
	$( "#TotalTax_cgst").val(total_cgst);
	$( "#TotalTax_cgst1").val(total_cgst);
	$( "#TotalTax_igst").val(total_igst);
	$( "#TotalTax_cess").val(total_cess);
	$( "#TotalTax_vat").val(total_vat);
	$( "#TotalTax_surcharge").val(total_sur);	
				
		
				var finaltotal=document.getElementById("totalvalue").value;
				var TotalTax_sgst1=document.getElementById("TotalTax_sgst").value;
				var TotalTax_cgst1=document.getElementById("TotalTax_cgst").value;
				var TotalTax_igst=document.getElementById("TotalTax_igst").value;
				var TotalTax_cess=document.getElementById("TotalTax_cess").value;
				
				
				var ssgst=document.getElementById("serviceTotalSGST").value;
				var ccgst=document.getElementById("serviceTotalCGST").value;
				//var service=document.getElementById("serviceChargeTotal").value;
				
				var TotalTax_vat = total_vat;
				var TotalTax_surcharge = total_sur;
				
				var round_off=document.getElementById("round_off_amount").value;
			
				var TotalTax_sgst = (parseFloat(TotalTax_sgst1))+(parseFloat(serviceTotalSGST));
				var TotalTax_cgst = (parseFloat(TotalTax_cgst1))+(parseFloat(serviceTotalCGST));
	
			  var netamount = (parseFloat(finaltotal)+parseFloat(serviceChargeTotal)+parseFloat(serviceTotalSGST)+parseFloat(serviceTotalCGST)+parseFloat(TotalTax_sgst)+parseFloat(TotalTax_cgst)+parseFloat(TotalTax_igst)+parseFloat(TotalTax_cess)+parseFloat(TotalTax_vat)+parseFloat(TotalTax_surcharge));
				
				var netamount_final = Math.round(netamount);
				$( "#netfinalint").val(netamount);
				$( "#netfinal").val(netamount_final);
				
				var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
				var RoundAmonut = RoundOfAmount.toFixed(2);
				//alert(RoundAmonut);
				$( "#round_off_amount").val(RoundAmonut);
				//alert(netamount_final);
				
				scharge(totalvalue);
	 	}
	});
} 



function itemqty<?php echo $counter1 ?>(count,type,qty,rate){
	//alert();
//var idnew1=document.getElementById('itemName'+count).value;
var idnew=document.getElementById("per"+count).value;
var idnew1=document.getElementById("valcount").value;
//alert(idnew1);
//alert(idnew);



var sub = $("#sub_total_items").val();	
var discount = document.getElementById("discount").value;
var total = $("#totalvalue").val;
var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;
var amount = document.getElementById("item_amount"+count).value;
var ssgst = document.getElementById("item_sgst"+count).value;
var csgst = document.getElementById("item_cgst"+count).value;
var isgst = document.getElementById("item_igst"+count).value;
var cesgst = document.getElementById("item_cess"+count).value;
var vsgst = document.getElementById("item_vat"+count).value;
var susgst = document.getElementById("item_sur"+count).value;


var TotalTax_sgst2 = document.getElementById("sc_sgst1").value;
var TotalTax_cgst2 = document.getElementById("sc_cgst1").value;

	$.ajax({
		type: "POST",
		url: 'ajax/spaedit.php',
		data: 'qty='+qty+'&rowCount='+count+'&rate='+rate+'&DbConnect=1&id='+idnew, 
		success: function (result) {
			//alert(result);
			data = JSON.parse(result);
			//$( "#item_rate"+count).val(data.itemRate);
			$( "#item_amount"+count).val(data.item_amount);
			$( "#item_sgst"+count).val(data.sgst);
			$( "#item_cgst"+count).val(data.cgst);
			$( "#item_igst"+count).val(data.Tax_igst);
			$( "#item_cess"+count).val(data.Tax_cess);
			$( "#item_vat"+count).val(data.Tax_vat);
			$( "#item_sur"+count).val(data.Tax_surcharge);
			$( "#sumtaxamount"+count).val(data.sumTaxAmount);
			$( "#total"+count).val(data.item_TotalAmountItem);
			
		var itemsubtotal = document.getElementById("item_amount"+count).value;
		var itemdiscount = document.getElementById("item_dis1"+count).value;
		var itemtotal = document.getElementById("total"+count).value;
		var itemsgst = document.getElementById("item_sgst"+count).value;
		var itemcgst = document.getElementById("item_cgst"+count).value;
		var itemigst = document.getElementById("item_igst"+count).value;
		var itemcess = document.getElementById("item_cess"+count).value;
		var itemvat = document.getElementById("item_vat"+count).value;
		var itemsur = document.getElementById("item_sur"+count).value;
//alert(sub);
//alert(itemsubtotal);
//alert(amount);

	
	var subtotal = (parseFloat(sub) + parseFloat(itemsubtotal)) - parseFloat(amount);
	var discounttotal = ((parseFloat(discount) + parseFloat(itemdiscount))).toFixed(2);
	var totalvalue = (parseFloat(subtotal) - parseFloat(discounttotal)).toFixed(2);
	//var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - parseFloat(ssgst)).toFixed(2);
	//var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - parseFloat(csgst)).toFixed(2);
	var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - (parseFloat(ssgst)+parseFloat(TotalTax_sgst2))).toFixed(2);
	var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - (parseFloat(csgst)+parseFloat(TotalTax_cgst2))).toFixed(2);
	var total_igst = ((parseFloat(igst) + parseFloat(itemigst)) - parseFloat(isgst)).toFixed(2);
	var total_cess = ((parseFloat(cess) + parseFloat(itemcess)) - parseFloat(cesgst)).toFixed(2);
	var total_vat = ((parseFloat(vat) + parseFloat(itemvat)) - parseFloat(vsgst)).toFixed(2);
	var total_sur = ((parseFloat(sur) + parseFloat(itemsur)) - parseFloat(susgst)).toFixed(2);
	
	//alert(total_cgst);
	var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
	//var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess));
				
	var netamount_final = Math.round(netamount);
	var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
	var RoundAmonut = RoundOfAmount.toFixed(2);
	//alert(subtotal);


	$( "#totalvalue").val(totalvalue);
	$( "#sub_total_items").val(subtotal.toFixed(2));
	$( "#discount").val(discounttotal);
	$( "#TotalTax_sgst").val(total_sgst);
	$( "#TotalTax_sgst1").val(total_sgst);
	$( "#TotalTax_cgst").val(total_cgst);
	$( "#TotalTax_cgst1").val(total_cgst);
	$( "#TotalTax_igst").val(total_igst);
	$( "#TotalTax_cess").val(total_cess);
	$( "#TotalTax_vat").val(total_vat);
	$( "#TotalTax_surcharge").val(total_sur);
	$( "#netfinalint").val(netamount);
	$( "#netfinal").val(netamount_final);
	$( "#round_off_amount").val(RoundAmonut);
	scharge(subtotal);	
	 	}
	});
	
} 

function scharge(subtotal){
		 
var sub = $("#sub_total_items").val();	

	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var outleType = $("#outleType").val();
	var outlet = $("#outlet").val();	
	var id_posbilling = $("#id_posbilling").val();
	var sub_total_items = $("#sub_total_items").val();
	
	$.ajax({
		//alert(subtotal);
		type: "POST",
		url: 'ajaxGetLaundrySpaOrderItemList2.php',
		data: 'DbConnect=1&outleType='+outleType+'&sub_total='+subtotal+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			
			$( "#revServiceCharge" ).val(revServiceCharge);
		//	alert(sgst);
       data = JSON.parse(result);
	   
       $( "#service_charge_amount").val((data.service_charge_amount).toFixed(2));			
       $( "#serviceChargeTotal").val(data.serviceChargeTotal);
       $( "#sc_sgst1").val(data.serviceTotalSGST);
       $( "#sc_cgst1").val(data.serviceTotalCGST);
	 
	 
		var sgst = document.getElementById("TotalTax_sgst1").value;
		var cgst = document.getElementById("TotalTax_cgst1").value;
		var total1 = document.getElementById("netfinalint").value;
		var total = document.getElementById("totalvalue").value;
		var adddiscount = document.getElementById("additional_discount_amount").value;
		var serviceTotalSGST1 = data.serviceTotalSGST;
		var serviceTotalCGST1 = data.serviceTotalCGST;
		var serviceChargeTotal = data.serviceChargeTotal;
		
		//alert(total);
		
		var serviceTotalSGST = (parseFloat(sgst))+(parseFloat(serviceTotalSGST1));
		var serviceTotalCGST = (parseFloat(cgst))+(parseFloat(serviceTotalCGST1));
		var netamount = ((parseFloat(total))+(parseFloat(serviceChargeTotal))+(parseFloat(serviceTotalSGST))+(parseFloat(serviceTotalCGST))+(parseFloat(serviceTotalSGST1))+(parseFloat(serviceTotalCGST1)))-parseFloat(adddiscount);
		
		var netamount1 = Math.round(netamount);
		var RoundOfAmount = (parseFloat(netamount1))-(parseFloat(netamount));
		
		var RoundAmonut = RoundOfAmount.toFixed(2);
				
		$( "#round_off_amount").val(RoundAmonut);
		$( "#netfinal").val(netamount1.toFixed(2));
		$( "#netfinalint").val(netamount);
		
	    $( "#TotalTax_sgst").val(serviceTotalSGST.toFixed(2));
		$( "#TotalTax_cgst").val(serviceTotalCGST.toFixed(2));
		
	 	}
	});
}
</script>
	
<?php $counter1++; } ?>

<script>
$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));        
});
</script>