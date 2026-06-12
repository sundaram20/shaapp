
<?php 
 
if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}

?>

<?php
 $vals=	$_REQUEST['vals'];
 $array_s =  explode(",", $vals);

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
	
/*			 
$GetPrevious	='<tr>
<td>'.$i++.'</td>
<td>'.round($ResultBlockedtable1->qty).'</td>
</tr>';
echo $GetPrevious;
$inc++;
*/			 
?>	
 
 
 
<?php for($i=0;$i<count($array_s);$i++){
	$id_item_type_s = $array_s[$i];
 
?>
<tr id="trdelete<?php echo $counter1?>">
<td> <?php 
  $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."'  "; 

//Id Inv Get 
    $itemSql_s = "SELECT * FROM `".TBL_INV_PURCH_DETAILS."` where id='".$id_item_type_s."'  "; 
    //Id inv items
	 $resitem_s = mysqli_query($connNew,$itemSql_s);
	 
	 while ($rowItem_s = mysqli_fetch_object($resitem_s)){
	    $id_inv_items = $rowItem_s->id_inv_items; 
	    $qty_s = $rowItem_s->qty; 
	    $discount = $rowItem_s->discount_percent;
	    $discount_amount = $rowItem_s->discount_amount;
	    $sgst_amount = $rowItem_s->item_sgst_amount;
	    $cgst_amount = $rowItem_s->item_cgst_amount;
	    $igst_amount = $rowItem_s->item_igst_amount;
	    $cess_amount = $rowItem_s->item_cess_amount;
	    $vat_amount = $rowItem_s->item_vat_amount;
	    $sur_amount = $rowItem_s->item_surcharge_amount;
	 }

	//Item Code Get
	 $itemcode = "SELECT * FROM `".TBL_INV_ITEMS."` where id='".$id_inv_items."'  "; 
    //Id inv items
	 $itemcode_s = mysqli_query($connNew,$itemcode);
	 
	 while ($itemcode_ss = mysqli_fetch_object($itemcode_s)){
	    $item_code = $itemcode_ss->item_code; 
	    $id_mst_attributes_unit_main = $itemcode_ss->id_mst_attributes_unit_main; 
	    $sale_rate = $itemcode_ss->sale_rate; 
	    $id_mst_charges_sales_local = $itemcode_ss->id_mst_charges_sales_local; 
	 }
	 $mainunit = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$id_mst_attributes_unit_main."'  ");
	 $resCat1 = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '1' and transaction_type = '1' and id='".$id_mst_charges_sales_local."' ",'');

				$resultCat1 = $db->fetch_object2($resCat1);
				$Taxapplication 	  = $resultCat1->tax_applicable;
				$Taxapplication1 	  = $resultCat1->name;
				if($Taxapplication==1){
								
							$sgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_sgst."'");

							$cgst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cgst."'");

							$igst	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_igst."'");
			
							$cess	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat1->id_mst_charges_cess."'");
				}
				elseif($Taxapplication==2){
						
					$vat	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_vat."'");

					$surcharge	=	selectColumn(TBL_CHARGES,'percentage'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1'  AND `id` = '".$resultCat->id_mst_charges_surcharge."'");
				}

	 $rate_amount = $sale_rate * $qty_s;
	 
	
                   $percentage1=($discount/100)*$rate_amount;
				   $Totalitem_amount		=	$rate_amount-$percentage1;
				  // $Totalitem_amount		=	$Totalitem_amount;
				   $total_item		=	$Totalitem_amount;
				   
	 
	 
  ?>
<select class="form-control select2" name="itemName[]" id="itemName" onchange="getItemRate(this.value,<?php echo $counter1?>);" data-parsley-required data-parsley-errors-container="#itemNameError" style="width:180px;" >
		
 
<?php $resitem = mysqli_query($connNew,$itemSql);

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
				}elseif($id_inv_items == $rowitemName->id){
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
				}elseif($id_inv_items == $rowItem->id){
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
	 
	 <input type="hidden" name="id_inv_detail" id="id_purch_details" value="" />
     <input type="hidden" name="counter" id="<?php echo $counter1?>" value="<?php echo $counter1 ?>" />  
 
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_subcode]" id="item_subcode<?php echo $counter1?>" value="<?php echo $item_code;?>" readonly="readonly" style="width:70px;"/>        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_qty]" id="item_qty<?php echo $counter1?>" value="<?php echo $qty_s;?>" onKeyup="itemqty<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)" style="width:50px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_unit]" id="item_unit<?php echo $counter1?>" value="<?php echo $mainunit; ?>" readonly style="width:80px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_rate]" id="item_rate<?php echo $counter1?>" value="<?php echo $sale_rate;?>" style="width:60px;" readonly="readonly" />        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_amount]" id="item_amount<?php echo $counter1?>" value="<?php echo $rate_amount;?>" style="width:50px;" readonly="readonly" />        
        </td>
		
		<!-- <div id="getid"> </div> -->
		
        <td class="form-group col-md-2">
		   <input type="hidden" id="per<?php echo $counter1?>" value="<?php echo $id_inv_items ?>" >
		   <input type="hidden" id="countper<?php echo $counter1?>" value="<?php echo $counter1?>" >
		   <input type="hidden" id="countper" value="<?php echo $counter1?>" >
		   
          <!-- <input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $selectoption->discount_percent; ?>" onchange="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> -->

		   <input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $discount;?>" onKeyup="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> 
        </td>
		
	
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_dis]" id="item_dis1<?php echo $counter1?>" readonly  value="<?php echo $discount_amount; ?>" style="width:50px;" /> 
        </td>
		
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][taxAccountName]" id="taxAccountName<?php echo $counter1?>" readonly value="<?php echo $Taxapplication1; ?>"  style="width:150px;" />        
        </td>
		
				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtax]" id="sumtax<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
				<?php //echo round($sumTaxPersentge,2);?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtaxamount]" id="sumtaxamount<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
				<?php //echo round($sumTaxAmount,2); ?></td>

				<td><input class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sgst]" id="item_sgst<?php echo $counter1?>" value="<?php echo $sgst_amount;?>" readonly style="width:50px;" />
				<?php //echo stripslashes($Tax_sgst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cgst]" id="item_cgst<?php echo $counter1?>" value="<?php echo $cgst_amount;?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($Tax_cgst,2); ?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_igst]" id="item_igst<?php echo $counter1?>" value="<?php echo $igst_amount;?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($Tax_igst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cess]" id="item_cess<?php echo $counter1?>" value="<?php echo $cess_amount;?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($Tax_cess,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_vat]" id="item_vat<?php echo $counter1?>" value="<?php echo $vat_amount;?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($Tax_vat,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sur]" id="item_sur<?php echo $counter1?>" value="<?php echo $sur_amount;?>" style="width:50px;" readonly="readonly" />
				<?php //echo round($Tax_surcharge,2); ?></td>
        
				<td class="col-xs-12 col-md-3 col-sm-2"> 
					<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][total]" id="total<?php echo $counter1?>" value="<?php echo $total_item ?>"  style="width:80px;" readonly="readonly" />        
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
		
		                       
    <td><img src="../images/delete.gif"  onclick="deleteitemRow(<?php echo $counter1?>);" class="ibtnDel1" id="deletes<?php echo $counter1?>" name="deletes<?php echo $counter1?>" style="cursor:pointer;" title="Delete"/></td>
	
<?php 
  // }
?>
	
     </tr>   



	<script>
function calculateDiscountSingleItem<?php echo $counter1 ?>(count,type,discount){
	var countper=document.getElementById("countper").value;
	
	var idnew=document.getElementById("per"+count).value;
	//var idnew=document.getElementById('itemName'+count).value;
	var item_qty=document.getElementById("item_qty"+count).value;
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	
	var counter1 =  $("#counter1").val();  
	//alert(outlet);
	//alert(counter1);
	
	$.ajax({
		type: "POST",
		//url: 'ajax/ajaxGetLaundrySpaOrderItemList.php',
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
	//alert();
//var idnew=document.getElementById('itemName'+count).value;
var idnew=document.getElementById("per"+count).value;
//alert(idnew);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'qty='+qty+'&rowCount='+count+'&DbConnect=1&id='+idnew, 
		success: function (result) {
			//alert(result);
			data = JSON.parse(result);
			//$( "#item_rate"+count).val(data.itemRate);
			$( "#item_amount"+count).val(data.item_amount);
	 	}
	});
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetLaundrySpaOrderItemList.php',
		//url: 'ajax/discountaj.php',
		data: 'qty='+qty+'&id='+idnew,
		success: function (result) {
			console.log(result);
	 	}
	});
	
} 
</script>
	
	<?php $counter1++; } ?>