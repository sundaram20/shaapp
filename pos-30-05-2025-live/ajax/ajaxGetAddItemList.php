
<?php 
if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}
/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION);
echo '</pre>';*/
//die;
?>

<?php
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
  $CheckBlockedTable_Sql = "SELECT * FROM pos_purch_details WHERE qty-bal_qty>0  $Outlet AND id_inv_purch  IN  (SELECT id FROM pos_purch WHERE  id_mst_outlet  IN(".$id_outlet.") )";	
}else{
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE id= '".$_REQUEST['id_posbilling']."'");
      $ResultupdateRow = mysqli_fetch_object($updateSql);
	   $ResultupdateRow->sc_reverse;
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
 
 
 
 

<tr id="trdelete<?php echo $counter1?>">
	

<td> <?php 
  $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND status='1'  AND id_mst_attributes_item_type='".$id_item_type."'  "; ?>
<select class="form-control select2" name="id_pos_detail[<?php echo $counter1?>][itemName]" id="itemName" onchange="getItemRate(this.value,<?php echo $counter1?>);" data-parsley-required data-parsley-errors-container="#itemNameError" style="width:180px;" >
		
<option value='' alt=''>Select Item</option>

<?php $resitem = mysqli_query($connNew,$itemSql);

	while ($rowItem = mysqli_fetch_object($resitem)){
		//print_r($rowState);
		$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$rowItem->id."' and enabled='1' ";
		$resitemName=mysqli_query($connNew,$itemNameSelectSQL); 
		$itemNameNumRows = mysqli_num_rows($resitemName);
		if($itemNameNumRows>0){
			$SelectItemList .='<optgroup label="'.$rowItem->name.'">';
				while($rowitemName = mysqli_fetch_object($resitemName)){
					$SelectItemList .='<option '.$selected.' value="'.$rowitemName->id.'">'.ucwords($rowitemName->name).' - '.$rowItem->name.'</option>';
				}
			$SelectItemList .=	'</optgroup>';
	    }else{
			$SelectItemList .='<option '.$selected.' value="'.$rowItem->id.'">'.ucwords($rowItem->name).'</option>';
		}
	}
	echo $SelectItemList ;
	?>            		
</select>
         </td>
	<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id]" id="id_pos_detail" value="<?php echo $ResultBlockedtable1->id; ?>" />
	
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][items_idd]" id="items_idd<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][mainitem_id]" id="mainitem_id<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][subitems_id]" id="subitems_id<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][outlet]" id="outlet<?php echo $counter1; ?>" value="" /> 


<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_name]" id="item_name<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_code]" id="item_code<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][subitems_name]" id="subitems_name<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][subitems_name1]" id="subitems_name1<?php echo $counter1; ?>" value="" /> 






<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_mst_charges_sales_local]" id="id_mst_charges_sales_local<?php echo $counter1; ?>" value="" /> 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][item_amount]" id="item_amount1<?php echo $counter1; ?>" value="" /> 
	 
<input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_inv_detail]" id="id_purch_details<?php echo $counter1?>" value="" />
	
 <?php 
  //echo  $_REQUEST['items_id'];
?>
<input type="hidden" id="valcount" value="" >
	
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_subcode]" id="item_subcode<?php echo $counter1?>" value="" readonly="readonly" style="width:70px;"/>        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control discountvalue" type="text" name="id_pos_detail[<?php echo $counter1?>][item_qty]" id="item_qty<?php echo $counter1?>" value="" onKeyup="amount_cal(<?php echo $counter1?>,2,this.value)" style="width:50px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" 
            name="id_pos_detail[<?php echo $counter1?>][item_unit]" id="item_unit<?php echo $counter1?>" value="" readonly style="width:80px;"/>        
        </td>
		
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_rate]" id="item_rate<?php echo $counter1?>" value="" style="width:70px;" onKeyup="amount_cal(<?php echo $counter1?>,2,this.value)"  />        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_amount]" id="item_amount<?php echo $counter1?>" class="items" value="" style="width:70px;"  />        
        </td>
		
		<!-- <div id="getid"> </div> -->
		
        <td class="form-group col-md-2">
		
		   <input type="hidden" id="per<?php echo $counter1?>" value="" >
		   <input type="hidden" id="countper<?php echo $counter1?>" value="<?php echo $counter1?>" >
		   <input type="hidden" id="countper" value="<?php echo $counter1?>" >
		   
          <!-- <input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="<?php echo $selectoption->discount_percent; ?>" onchange="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> -->

		   <input autocomplete="off" class="form-control discountvalue" type="text" name="id_pos_detail[<?php echo $counter1?>][discount]" id="discountpercent<?php echo $counter1?>" value="" onKeyup="amount_cal(<?php echo $counter1?>,2,this.value)"  style="width:50px;" /> 
		 <?php /*?> onKeyup="calculateDiscountSingleItem<?php echo $counter1?>(<?php echo $counter1?>,2,this.value)"<?php */?>
        </td>
		
	
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_dis]" id="item_dis1<?php echo $counter1?>" readonly  value="" style="width:100px;" /> 
        </td>
		
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][taxAccountName]" id="taxAccountName<?php echo $counter1?>" readonly value=""  style="width:150px;" />        
        </td>
		
				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtax]" id="sumtax<?php echo $counter1?>" value="" style="width:50px;" readonly="readonly" />
				<?php //echo round($sumTaxPersentge,2);?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][sumtaxamount]" id="sumtaxamount<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($sumTaxAmount,2); ?></td>

				<td><input class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sgst]" id="item_sgst<?php echo $counter1?>" value="" readonly style="width:60px;" />
				<?php //echo stripslashes($Tax_sgst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cgst]" id="item_cgst<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_cgst,2); ?></td>

				<td>
				<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_igst]" id="item_igst<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_igst,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_cess]" id="item_cess<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_cess,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_vat]" id="item_vat<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_vat,2); ?></td>

				<td><input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_sur]" id="item_sur<?php echo $counter1?>" value="" style="width:60px;" readonly="readonly" />
				<?php //echo round($Tax_surcharge,2); ?></td>
        
				<td class="col-xs-12 col-md-3 col-sm-2"> 
					<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][total]" id="total<?php echo $counter1?>" value=""  style="width:80px;" readonly="readonly" />        
				
			 <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][id_mst_charges_sgst]" id="id_mst_charges_sgst<?php echo $counter1?>" value="" />
        <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][id_mst_charges_cgst]" id="id_mst_charges_cgst<?php echo $counter1?>" value="" />
        <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][id_mst_charges_igst]" id="id_mst_charges_igst<?php echo $counter1?>" value="" />
        <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][id_mst_charges_cess]" id="id_mst_charges_cess<?php echo $counter1?>" value="" />       
	
</td>
		<input type="hidden" class="form-control first-input" name="id_pos_detail[<?php echo $counter1 ?>][item_BillSplit]" id="id_pos_detail<?php echo $counter1?>" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/>
		
		<input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][discount_amount_additional]" id="discount_amount" value="" />
		
        <input type="hidden" name="id_pos_detail[<?php echo $counter1 ?>][others_charges_net_amount]" id="othercharges" value="" />
		
		<input type="hidden" name="sc_sgst" id="sc_sgst" value="" />
        <input type="hidden" name="sc_cgst" id="sc_cgst" value="" />
		
        <input type="hidden" name="Tax_sgst_percentage" id="Tax_sgst_percentage<?php echo $counter1?>" value="" />
        <input type="hidden" name="Tax_cgst_percentage" id="Tax_cgst_percentage<?php echo $counter1?>" value="" />
        <input type="hidden" name="Tax_igst_percentage" id="Tax_igst_percentage<?php echo $counter1?>" value="" />
        <input type="hidden" name="Tax_cess_percentage" id="Tax_cess_percentage<?php echo $counter1?>" value="" />
        <input type="hidden" name="Tax_surcharge_percentage" id="Tax_surcharge_percentage<?php echo $counter1?>" value="" />
        <input type="hidden" name="Tax_vat_percentage" id="Tax_vat_percentage<?php echo $counter1?>" value="" />
		
		 <input type="hidden" name="id_pos_detail[<?php echo $counter1; ?>][id_mst_charges_sales_Interstate]" id="id_pos_detail"  value="<?php echo $id_mst_charges_sales_Interstate; ?>" />
		 
		<input type="hidden" class="form-control first-input" name="id_pos_detail[<?php echo $counter1 ?>][item_BillSplit1]" id="id_pos_detail<?php echo $counter1?>" value="1" style="width: 50px;float: left;padding: 1px 12px;height: 24px;"/>
		
		
    <td><img src="../images/delete.gif" class="ibtnDel1" id="deletes<?php echo $counter1?>" name="deletes<?php echo $counter1?>" onclick="deleteitemRow(<?php echo $counter1?>);" style="cursor:pointer;" title="Delete"/></td>
	
<?php 
  // }
?>
	
     </tr>   



<script>
function calculateDiscountSingleItem<?php echo $counter1 ?>(count,type,discount){
	//var countper=document.getElementById("countper").value;
	var countper=document.getElementById("countper").value;
	var idnew1=document.getElementById("valcount").value;
	var idnew=document.getElementById("per"+count).value;
	var item_qty=document.getElementById("item_qty"+count).value;
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	var idnew1 = $("#valcount").val();
	
var sub = $("#sub_total_items").val();	
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

var TotalTax_sgst2 = document.getElementById("sc_sgst1").value;
var TotalTax_cgst2 = document.getElementById("sc_cgst1").value;
	//alert(discount1);
	//alert(ssgst1);
	
	var counter1 =  $("#counter1").val();  
	//alert(idnew1);
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
			$( "#item_sgst"+count).val(data.sgst);
			//document.getElementById("item_sgst"+count).value=data.sgst;	
			$( "#item_cgst"+count).val(data.cgst);
			$( "#item_igst"+count).val(data.Tax_igst);
			$( "#item_cess"+count).val(data.Tax_cess);
			$( "#item_vat"+count).val(data.Tax_vat);
			$( "#item_sur"+count).val(data.Tax_surcharge);
			$( "#item_dis1"+count).val(data.dis1);
			$( "#discount").val(data.dis1);
			//$( "#round_off_amount").val(data.RoundOfAmount);
			$( "#net_amount").val(data.NetAmount);
			$( "#service_charge_amount").val(data.service_charge_amount);
			$( "#sub_total_items").val(data.item_amount);
			$( "#total").val(data.total);
			$("#taxAccountName"+count).val(data.taxAccountName);
			$("#additional_discount_amount").val(data.adddis);
			$("#othercharges").val(data.Additional);
			$("#discount_amount").val(data.discountt);
			$("#sc_sgst").val(data.sc_sgst);
			$("#sc_cgst").val(data.sc_cgst);
			var serviceChargeTotal = data.serviceChargeTotal;
			var serviceTotalSGST = data.sc_sgst;
			var serviceTotalCGST = data.sc_cgst;
			var itemdiscount  =  document.getElementById("item_dis1"+count).value;
			//alert(itemdiscount);
			
		var itemsubtotal = document.getElementById("item_amount"+count).value;
		var itemtotal = document.getElementById("total"+count).value;
		var itemsgst = document.getElementById("item_sgst"+count).value;
		var itemcgst = document.getElementById("item_cgst"+count).value;
		var itemigst = document.getElementById("item_igst"+count).value;
		var itemcess = document.getElementById("item_cess"+count).value;
		var itemvat = document.getElementById("item_vat"+count).value;
		var itemsur = document.getElementById("item_sur"+count).value;

	
	var subtotal = parseFloat(sub);
	//var subtotal = (parseFloat(sub) + parseFloat(itemsubtotal)) - parseFloat(amount);
	//alert(subtotal);
	var discounttotal = ((parseFloat(discount1) + parseFloat(itemdiscount))-parseFloat(disam)).toFixed(2);
	//alert(itemdiscount);
	//alert(discount1);
	var totalvalue = parseFloat(subtotal) - parseFloat(discounttotal);
	//var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - parseFloat(ssgst1)).toFixed(2);
	
	//var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - parseFloat(csgst)).toFixed(2);
	
	
	var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - (parseFloat(ssgst1)+parseFloat(TotalTax_sgst2))).toFixed(2);
	var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - (parseFloat(csgst)+parseFloat(TotalTax_cgst2))).toFixed(2);
	
	var total_igst = ((parseFloat(igst) + parseFloat(itemigst)) - parseFloat(isgst)).toFixed(2);
	var total_cess = ((parseFloat(cess) + parseFloat(itemcess)) - parseFloat(cesgst)).toFixed(2);
	var total_vat  = ((parseFloat(vat) + parseFloat(itemvat)) - parseFloat(vsgst)).toFixed(2);
	var total_sur  = ((parseFloat(sur) + parseFloat(itemsur)) - parseFloat(susgst)).toFixed(2);
	
	
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
			
			
		var ssgst=document.getElementById("serviceTotalSGST").value;
				var ccgst=document.getElementById("serviceTotalCGST").value;
				var service=document.getElementById("serviceChargeTotal").value;
				var finaltotal=totalvalue;
				var TotalTax_sgst1=total_sgst;
				var TotalTax_cgst1=total_cgst;
				var TotalTax_igst=total_igst;
				var TotalTax_cess=total_cess;
				var TotalTax_vat=total_vat;
				var TotalTax_surcharge=total_sur;
				
				var TotalTax_sgst = (parseFloat(TotalTax_sgst1))+(parseFloat(serviceTotalSGST));
				var TotalTax_cgst = (parseFloat(TotalTax_cgst1))+(parseFloat(serviceTotalCGST));
				
				 var netamount = (parseFloat(finaltotal)+parseFloat(serviceChargeTotal)+parseFloat(serviceTotalSGST)+parseFloat(serviceTotalCGST)+parseFloat(TotalTax_sgst)+parseFloat(TotalTax_cgst)+parseFloat(TotalTax_igst)+parseFloat(TotalTax_cess)+parseFloat(TotalTax_vat)+parseFloat(TotalTax_surcharge));
				
				var netamount_final = Math.round(netamount);
				$( "#netfinalint").val(netamount);
				$( "#netfinal").val(netamount_final.toFixed(2));
				
				var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
				var RoundAmonut = RoundOfAmount.toFixed(2);
				$( "#round_off_amount").val(RoundAmonut);  		
			
				scharge(totalvalue);	

	 	}
	});
} 



function itemqty<?php echo $counter1 ?>(count,type,qty){ alert(<?php echo $counter1 ?>);
var idnew=document.getElementById("per"+count).value;
var idnew1=document.getElementById("valcount").value;

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
//var TotalTax_sgst2 = $("#sc_sgst1").val();	
//alert(csgst);
var qty = document.getElementById("item_qty"+count).value;
			var rate = document.getElementById("item_rate"+count).value;
			//var discount = document.getElementById("discount_percent"+count).value;
alert(qty+'==='+rate);
alert(<?php echo $counter1 ?>);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'qty='+qty+'&rowCount='+count+'&DbConnect=1&id='+idnew, 
		success: function (result) {
			data = JSON.parse(result);
			$( "#item_rate"+count).val(data.itemRate);
			$( "#item_amount"+count).val(data.item_amount);
			document.getElementById("item_sgst"+count).value=data.sgst;
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
	
	var subtotal = (parseFloat(sub) + parseFloat(itemsubtotal)) - parseFloat(amount);
	var discounttotal = (parseFloat(discount) + parseFloat(itemdiscount));
	var totalvalue = (parseFloat(subtotal) - parseFloat(discounttotal)).toFixed(2);
	var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - (parseFloat(ssgst)+parseFloat(TotalTax_sgst2))).toFixed(2);
	var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - (parseFloat(csgst)+parseFloat(TotalTax_cgst2))).toFixed(2);
	var total_igst = ((parseFloat(igst) + parseFloat(itemigst)) - parseFloat(isgst)).toFixed(2);
	var total_cess = ((parseFloat(cess) + parseFloat(itemcess)) - parseFloat(cesgst)).toFixed(2);
	var total_vat  = ((parseFloat(vat) + parseFloat(itemvat)) - parseFloat(vsgst)).toFixed(2);
	var total_sur  = ((parseFloat(sur) + parseFloat(itemsur)) - parseFloat(susgst)).toFixed(2);
	
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
	$( "#netfinal").val(netamount_final.toFixed(2));
	$( "#round_off_amount").val(RoundAmonut);
		scharge(subtotal);
	 	
		}
	});
	
} 

function scharge(subtotal){
	//alert(count); 
var sub = $("#sub_total_items").val();	
var TotalTax_sgst2 = $("#sc_sgst1").val();	

	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var outleType = $("#outleType").val();
	var outlet = $("#outlet").val();	
	var id_posbilling = $("#id_posbilling").val();
	var sub_total_items = $("#sub_total_items").val();
	
	$.ajax({
		
		type: "POST",
		url: 'ajaxGetLaundrySpaOrderItemList2.php',
		data: 'DbConnect=1&outleType='+outleType+'&sub_total='+subtotal+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			//alert(subtotal);
		$( "#revServiceCharge" ).val(revServiceCharge);
		
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
		
		//alert(serviceTotalSGST1);
		
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

<script>

$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));        
});

</script>		