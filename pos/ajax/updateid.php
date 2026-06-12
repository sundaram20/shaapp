<div id="ViewOrderItemListtest">
<?php 
include_once("../../config/auto_loader.php");
?>



<?php
/*$id_attribute_table=$_REQUEST['id_attribute_table'];
$UniqueCodeold=$_REQUEST['UniqueCode'];
$discountType=$_REQUEST['discountType'];
$outlet=	$_REQUEST['outlet'];
$id	=	$_REQUEST['id'];*/

$id_attribute_table=$_REQUEST['id_attribute_table'];
$UniqueCodeold=$_REQUEST['UniqueCode'];
$discountType=$_REQUEST['discountType'];
$outlet=	$_REQUEST['outlet'];
//$id	=	'76';
$id	=	$_REQUEST['id'];

$sqlitemnew1 = "SELECT *  from inv_purch_details WHERE id_inv_items='".$id."' ";
$resitem1 = mysqli_query($connNew,$sqlitemnew1);
		$selectoption11=mysqli_fetch_object($resitem1);
		$SubTotalAmount		 =($selectoption11->item_amount*$selectoption11->qty);
 
 
 
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
			
			if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge']!=''){
						$service_charge_amount='0';
						$serviceTotalSGST= '0';
						$serviceTotalCGST= '0';
						$serviceChargeTotal	='0';
						
					}else{
						$service_charge_amount	=	(($SubTotalAmount*$percentage)/100);		
						$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
						$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
						$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
					}
			
if($id_attribute_table){

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

?>

<div class="row">
  <div class="col-md-12">
    <div class="form-group" style="margin-bottom: 1px;" >
      <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
        <table id="myTableOrder1" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >
          <thead style="font-size:10px;padding: 0px 0px;">
            
            <tr>
              <th style=" width:200px;padding: 5px 9px;">Items Name</th>
              <th style="padding: 5px 9px;">Items Code</th>
              <th style="padding: 5px 9px;">Qty</th>
              <th style="padding: 5px 9px;">Unit</th>
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
            </tr>
          </thead>
         
          
<?php 
if($_REQUEST['DbConnect']==1){
}

$counter1=	$_REQUEST['counter1'];
$outleType=	$_REQUEST['outleType'];
$outlet=	$_REQUEST['outlet'];
$id_item_type=	$_REQUEST['id_item_type'];
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
 
// echo $_REQUEST['id_posbilling'];
 
if($_REQUEST['id_posbilling']==''){
if($_REQUEST['outlet']!=''){
	$id_outlet	= $_REQUEST['outlet'];
	}else{
		$id_outlet	=0	;
		}
  $CheckBlockedTable_Sql = "SELECT * FROM inv_purch_details WHERE qty-bal_qty>0  $Outlet AND id_inv_purch  IN  (SELECT id FROM inv_purch WHERE  id_mst_outlet  IN(".$id_outlet.") )";	
}else{
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM inv_purch WHERE id= '".$_REQUEST['id_posbilling']."'");
      $ResultupdateRow = mysqli_fetch_object($updateSql);
	   $ResultupdateRow->id_inv_details_split;
	   $ResultupdateRow->sc_reverse;
	   $kot_doc_no=$ResultupdateRow->kot_doc_no;
	   if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$ResultupdateRow->discount_amount_additional;
	   }
		
  $CheckBlockedTable_Sql = "SELECT * FROM inv_purch_details WHERE $Outlet1";
}
		//echo $CheckBlockedTable_Sql;
		$db->query($CheckBlockedTable_Sql); 
	    $ResultBlockedtable1 = $db->fetch_object();
		   $numRows= $db->num_rows();
			$i=1;
			$inc=1;
			 $id_inv_items = $ResultBlockedtable1->id_inv_items; 
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
		?>	 



 
 
<tr id="trdelete<?php echo $counter1?>">
<?php
$itemSqlnew = "SELECT * FROM inv_purch_details where id IN ($ResultupdateRow->id_inv_details_split)";
$resitemnew = mysqli_query($connNew,$itemSqlnew);
	while ($rowItem1 = mysqli_fetch_object($resitemnew)){
		$size[] = $rowItem1->id;
	}
	$itemDetailSizeOf =	sizeof($size); 
	 
	for($i=0;$i<$itemDetailSizeOf;$i++){
        $sqlitemDetail = "SELECT * FROM inv_purch_details where id IN (108,118,105)";
		$resitem1 = mysqli_query($connNew,$sqlitemDetail);
		$rowitemDetail = mysqli_fetch_object($resitem1);
	    $id_item_name1 = $rowitemDetail->id_inv_items;
	}
	
?>

<td> <?php 
  $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."'  ";?>
<select class="form-control select2" name="itemName[]" id="itemName" onchange="getItemRate(this.value,<?php echo $counter1?>);getItemRate1(this.value,<?php echo $counter1?>);" data-parsley-required data-parsley-errors-container="#itemNameError" style="width:180px;" >
		
<option value='' alt=''>Select Item</option>

<?php $resitem = mysqli_query($connNew,$itemSql);

	while ($rowItem = mysqli_fetch_object($resitem)){
		//print_r($rowState);
		$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$rowItem->id."' ";
		$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
		$itemNameNumRows = mysqli_num_rows($resitemName);
		if($itemNameNumRows>0){
			$SelectItemList .='<optgroup label="'.$rowItem->name.'">';
				while($rowitemName = mysqli_fetch_object($resitemName)){
					
				/*if($_REQUEST['itemName'] == $rowitemName->id){
					$selected = 'selected="selected"';
				}elseif( == $rowitemName->id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				} */
					$SelectItemList .='<option '.$selected.' value="'.$rowitemName->id.'">'.ucwords($rowitemName->name).' - '.$rowItem->name.'</option>';
				}
			$SelectItemList .=	'</optgroup>';
	    }else{
			/* if($_REQUEST['itemName'] == $rowItem->id){
					$selected = 'selected="selected"';
				}elseif($id_item_name1 == $rowItem->id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				} */
			$SelectItemList .='<option '.$selected.' value="'.$rowItem->id.'">'.ucwords($rowItem->name).'</option>';
		}
	}
	echo $SelectItemList ;
?>            		
</select>
</td>

<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id;?>" /><input type="hidden" name="id_inv_detail" id="id_purch_details" value="" />
<input type="hidden" name="counter" id="<?php echo $counter1?>" value="<?php echo $counter1 ?>" />  
 
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_subcode]" id="item_subcode<?php echo $counter1?>" value="" readonly="readonly" style="width:70px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_qty]" id="item_qty<?php echo $counter1?>" value="" onchange="itemqty<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)" style="width:50px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_unit]" id="item_unit<?php echo $counter1?>" value="" readonly style="width:80px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_rate]" id="item_rate<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_amount]" id="item_amount<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />        
        </td>
		
        <td class="form-group col-md-2">
		   <input type="hidden" id="per<?php echo $counter1?>" value="" >
		   <input type="hidden" id="countper<?php echo $counter1?>" value="<?php echo $counter1?>" >
		   <input type="hidden" id="countper" value="<?php echo $counter1?>" >
           <input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $selectoption->discount_percent; ?>" onchange="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> 
        </td>
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_dis]" id="item_dis1<?php echo $counter1?>" readonly  value="" style="width:50px;" /> 
        </td>
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][taxAccountName]" id="taxAccountName<?php echo $counter1?>" readonly value=""  style="width:150px;" />        
        </td>
		
		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtax]" id="sumtax<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($sumTaxPersentge,2);?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtaxamount]" id="sumtaxamount<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($sumTaxAmount,2); ?>
		</td>

		<td>
			<input class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sgst]" id="item_sgst<?php echo $counter1?>" value="" readonly style="width:50px;" />
		<?php //echo stripslashes($Tax_sgst,2); ?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cgst]" id="item_cgst<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($Tax_cgst,2); ?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_igst]" id="item_igst<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($Tax_igst,2); ?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cess]" id="item_cess<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($Tax_cess,2); ?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_vat]" id="item_vat<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($Tax_vat,2); ?>
		</td>

		<td>
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sur]" id="item_sur<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
		<?php //echo round($Tax_surcharge,2); ?>
		</td>

		<td class="col-xs-12 col-md-3 col-sm-2"> 
			<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][total]" id="total<?php echo $counter1?>" value=""  style="width:80px;" readonly="readonly" />        
		</td>
		
		
<input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][discount_amount_additional]" id="id_pos_detail" value="<?php echo $_SESSION['discountamount']; ?>" />
<input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][others_charges_net_amount]" id="id_pos_detail" value="<?php echo $_SESSION['AdditionalChargeamount']; ?>" />
<input type="hidden" name="sc_sgst" id="sc_sgst" value="" />
<input type="hidden" name="sc_cgst" id="sc_cgst" value="" />
<input type="hidden" name="Tax_sgst_percentage" id="Tax_sgst_percentage" value="" />
<input type="hidden" name="Tax_cgst_percentage" id="Tax_cgst_percentage" value="" />
<input type="hidden" name="Tax_igst_percentage" id="Tax_igst_percentage" value="" />
<input type="hidden" name="Tax_cess_percentage" id="Tax_cess_percentage" value="" />
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_mst_charges_sales_Interstate]" id="id_pos_detail"  value="<?php echo $id_mst_charges_sales_Interstate; ?>" />
<input type="hidden" class="form-control first-input" name="id_pos_detail[<?php echo $counter1 ?>][item_BillSplit]" id="id_pos_detail<?php echo $counter1?>" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/>
		
		                       
    <td>
		<img src="../images/delete.gif"  onclick="deleteitemRow(<?php echo $counter1?>);" class="ibtnDel1" id="deletes<?php echo $counter1?>" name="deletes<?php echo $counter1?>" style="cursor:pointer;" title="Delete"/>
	</td>
<?php 
   //}
?>
</tr>   
		
	<tfoot>
		<tr> 
			<td colspan="12" style="text-align: left;">
				<input  type="button" class="btn btn-sm btn-block" id="addrow1" name="addrow1" onChange="AddMoreItem();" onClick="AddMoreItem();" value="Add More" />
				<input  type="button" class="btn btn-sm btn-block" id="addrow4" value="Add More" style="display: none;" />  
			</td> 
		</tr>
	</tfoot>
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
							    if($selectoption1->id == ''){
							   		$sub_total_items = 0;
							   	}else{
							   		$sub_total_items = $selectoption1->sub_total_items;
							   	}
							?>
        <input type="text" class="form-control" placeholder="Sub Total" id="sub_total_items" name="sub_total_items" value="<?php echo $sub_total_items; ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Discount</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
        <input type="text" class="form-control" placeholder="Discount" id="discount" name="total_discount_amount" value="<?php echo $selectoption1->total_discount_items; ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Total</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
        <input type="text" class="form-control" placeholder="Total" id="totalvalue" name="net_amount_items" value="<?php echo $selectoption1->grant_total_amount; ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Service Charges 
      <?php if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge']!=''){?>
      <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
      <?php }else{ ?>
        <input type="checkbox" class="minimal-red" value="1" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" checked="checked"></label>
     <?php } ?>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
        <input type="text" class="form-control" placeholder="Service Charges" id="service_charge_amount" name="service_charge_amount" value="<?php if($service_charge_amount) echo $service_charge_amount;else echo '0';?>" onChange="additionalDiscount(3,this.value);" style="text-align:right;">
      </div>
    </div>
  </div>
  <!-- SGST -->
  <?php if($taxtype==1){?>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">SGST</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-down"></i> </div>
        <input type="text" class="form-control" placeholder="SGST" id="TotalTax_sgst" name="sgst_net_amount" value="<?php echo $selectoption1->sgst_net_amount; ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  
  <!-- CGST -->
  
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">CGST</label>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-caret-square-o-left"></i> </div>
        <input type="text" class="form-control" placeholder="CGST" id="TotalTax_cgst" name="" value="<?php echo $selectoption1->cgst_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="IGST" id="TotalTax_igst" name="igst_net_amount" value="<?php echo $selectoption1->igst_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="CESS" id="TotalTax_cess" name="cess_net_amount" value="<?php echo $selectoption1->cess_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="VAT" id="TotalTax_vat" name="vat_net_amount" value="<?php echo $selectoption1->vat_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="surcharge" id="TotalTax_surcharge" name="surcharge_net_amount" value="<?php echo $selectoption1->surcharge_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Others Charges" id="others_charges_net_amount" name="others_charges_net_amount" value="<?php if($_SESSION['AdditionalChargeamount']) echo $_SESSION['AdditionalChargeamount'];else echo '';?>" onChange="additionalDiscount(3,this.value);" style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Discount Amount" id="additional_discount_amount" name="additional_discount_amount" value="<?php echo $selectoption1->disc_amount_additional1; ?>" onChange="additionalDiscount(2,this.value);" style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Round Amount" id="round_off_amount" name="round_off_amount" value="<?php echo $selectoption1->round_off_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Net Amount" id="net_amount" name="net_amount" value="<?php echo $selectoption1->net_amount_items; ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
</div>
<?php } ?>


<script>
function calculateDiscountSingleItem<?php echo $counter1 ?>(count,type,discount){
	
	var countper=document.getElementById("countper").value;
	var idnew=document.getElementById("per"+count).value;
	var item_qty=document.getElementById("item_qty"+count).value;
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	var counter1 =  $("#counter1").val();  
	
	$.ajax({
		type: "POST",
		url: 'ajax/discountaj.php',
		data: 'id_attribute_table='+opts+'&rowCount='+count+'&DbConnect=1&discount='+discount+'&UniqueCode='+idnew+'&item_qty='+item_qty+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_item_type='+id_item_type+'&DbConnect=1', 
		success: function (result) {
			//alert(result);
			//$( "#ViewOrderItemList").html(result);
			//console.log(result);
			data = JSON.parse(result);
			$( "#id"+count).val(data.dis);
			$( "#total"+count).val(data.item_TotalAmountItem);
			$( "#sumtax"+count).val(data.sumTaxPersentge);
			$( "#sumtaxamount"+count).val(data.sumTaxAmount);
			//$( "#item_sgst"+count).html(data.sgst);
			document.getElementById("item_sgst"+count).value=data.sgst;	
			$( "#item_cgst"+count).val(data.cgst);
			$( "#item_igst"+count).val(data.Tax_igst);
			$( "#item_cess"+count).val(data.Tax_cess);
			$( "#item_vat"+count).val(data.Tax_vat);
			$( "#item_sur"+count).val(data.Tax_surcharge);
			$( "#item_dis1"+count).val(data.dis1);
			$( "#discount").val(data.dis1);
			$( "#round_off_amount").val(data.RoundOfAmount);
			$( "#net_amount").val(data.NetAmount);
			$( "#TotalTax_surcharge").val(data.TotalTax_surcharge);
			$( "#TotalTax_vat").val(data.TotalTax_vat);
			$( "#TotalTax_cess").val(data.TotalTax_cess);
			$( "#TotalTax_igst").val(data.TotalTax_igst);
			$( "#TotalTax_cgst").val(data.TotalTax_cgst);
			$( "#TotalTax_sgst").val(data.TotalTax_sgst);
			$( "#service_charge_amount").val(data.service_charge_amount);
			$( "#sub_total_items").val(data.item_amount);
			$( "#total").val(data.total);
			$("#taxAccountName"+count).val(data.taxAccountName);
			$("#additional_discount_amount").val(data.adddis);
			$("#sc_sgst").val(data.sc_sgst);
			$("#sc_cgst").val(data.sc_cgst);
			//$("#sc_cgst").val(data.serviceChargeTotal);
			//$("#net_amount").val(data.NetAmount);
			
				var j=1;
				var kk=0;
				var kk1=0;
				var kk2=0;
				 for (var i=0; i < count; i++) {
					/* var total = document.getElementById("total"+j).value;
					kk +=parseInt(total);
					var totalvalue=kk;*/
					
					var total1 = document.getElementById("item_amount"+j).value;
					kk1 +=parseInt(total1);
					var subtotal=kk1;
					
					var dis = document.getElementById("item_dis1"+j).value;
					kk2 +=parseInt(dis);
					var discount=kk2;
					//alert(subtotal);
					
					kk =kk1-kk2;
					var totalvalue=kk;
					//alert(totalvalue);
					j++;
					
					$( "#totalvalue").val(totalvalue);
					$( "#sub_total_items").val(subtotal);
					$( "#discount").val(discount);
				 }
				// $NetAmount		=	($TotalAmountFinal+$serviceChargeTotal+$TotalTax_sgst+$TotalTax_cgst+$serviceTotalSGST+$serviceTotalCGST+$TotalTax_igst+$TotalTax_cess+$TotalTax_vat+$TotalTax_surcharge+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];
				var finaltotal = totalvalue;
				var serviceChargeTotal = data.serviceChargeTotal;
				var TotalTax_sgst = data.TotalTax_sgst;
				var TotalTax_cgst = data.TotalTax_cgst;
				var serviceTotalSGST = data.serviceTotalSGST;
				var serviceTotalCGST = data.serviceTotalCGST;
				var TotalTax_igst = data.TotalTax_igst;
				var TotalTax_cess = data.TotalTax_cess;
				var TotalTax_vat = data.TotalTax_vat;
				var TotalTax_surcharge = data.TotalTax_surcharge;
				//var netamount = (finaltotal+serviceChargeTotal+TotalTax_sgst+TotalTax_cgst+serviceTotalSGST+serviceTotalCGST+TotalTax_igst+TotalTax_cess+TotalTax_surcharge+TotalTax_vat+$_SESSION['AdditionalChargeamount'])-$_SESSION['discountamount'];
				var netamount = (finaltotal+serviceChargeTotal+TotalTax_sgst+TotalTax_cgst+serviceTotalSGST+serviceTotalCGST+TotalTax_igst+TotalTax_cess+TotalTax_surcharge+TotalTax_vat);
				//alert(serviceTotalSGST);
	 	}
	});
	
} 


function itemqty<?php echo $counter1 ?>(count,type,qty){
var idnew=document.getElementById("per"+count).value;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'qty='+qty+'&rowCount='+count+'&DbConnect=1&id='+idnew, 
		success: function (result) {
			data = JSON.parse(result);
			$( "#item_rate"+count).val(data.itemRate);
			$( "#item_amount"+count).val(data.item_amount);
	 	}
	});
	
} 
</script>

<script>
function AddMoreItem(){
	//alert();
	var counter1 =  $("#counter1").val();  
	var outleType = $("#outleType").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	var id_posbilling = $("#id_posbilling").val();
//alert(outlet);
	counter1++;
	//alert(counter1);
	$(".select3").select2({});
    $(".select3").last().next().next().remove();
	
	$.ajax({
		type: "POST",	
		data: 'counter1='+counter1+'&outleType='+outleType+'&DbConnect=1&id_item_type='+id_item_type,
		 success: function(response){
		}
	});
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetAddItemList.php',
		data: 'counter1='+counter1+'&outleType='+outleType+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&DbConnect=1&id_item_type='+id_item_type,
		success: function (result) {
			//alert(result);
			$(".select2").last().next().next().remove();	
			$(".select2").select2({});
			$( "#counter1" ).val(counter1);
			$( "#ViewOrderItemListtest1" ).append(result);
	 	}
	});
	}
</script>

</div>

