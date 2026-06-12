
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

$idd	=	$_REQUEST['idd'];
$counter1=	$_REQUEST['counter1'];
$outleType=	$_REQUEST['outleType'];
$id_item_type=	$_REQUEST['id_item_type'];
//BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);
?>

<tr id="trdelete<?php echo $counter1?>">
				
<td> <?php 
			
 $itemSql = "SELECT * FROM `".TBL_INV_ITEMS."` where id_shop='".addslashes($_SESSION['shop'])."' AND id_mst_attributes_item_type='".$id_item_type."'  ";?>
 <!-- "getItemRate(this.value,<?php echo $counter1?>);getItemRate1(this.value,<?php echo $counter1?>);" -->
<select class="form-control select2" name="itemName" id="itemName" onchange="getItemRate(this.value,<?php echo $counter1?>);" data-parsley-required data-parsley-errors-container="#itemNameError" style="width:180px;" >
		
<option value='' alt=''>Select Item</option>

<?php 	$resitem = mysqli_query($connNew,$itemSql);

	while ($rowItem = mysqli_fetch_object($resitem)){
		//print_r($rowState);
		$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS_DETAILS."` WHERE id_item='".$rowItem->id."' ";
		//$itemNameSelectSQL = "SELECT * FROM `".TBL_INV_ITEMS."` WHERE id='".$rowItem->id."' ";
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
		 
          
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_subcode]" id="item_subcode<?php echo $counter1?>" value="" readonly="readonly" style="width:70px;"/>        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_qty]" id="item_qty<?php echo $counter1?>" value="" style="width:60px;"/>        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_rate]" id="item_rate<?php echo $counter1?>" value="0" style="width:80px;" readonly="readonly" />        
        </td>
        <td class="form-group col-md-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_amount]" id="item_amount<?php echo $counter1?>" value="" style="width:80px;" readonly="readonly" />        
        </td>
		
		<!-- <div id="getid"> </div> -->
		
        <td class="form-group col-md-2">
		
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_discount_percentage]" id="item_discount_percentage<?php echo $counter1?>" value=""  style="width:40px;" />  
        </td>
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_tax_percentage]" id="item_tax_percentage<?php echo $counter1?>" value="" style="width:40px;" /> 
        </td>
		
		
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][taxAccountName]" id="taxAccountName<?php echo $counter1?>" readonly value="" style="width:40px;" />        
        </td>

				<td><?php echo round($sumTaxPersentge,2);?></td>

				<td><?php echo round($sumTaxAmount,2); ?></td>

				<td><?php echo round($Tax_sgst,2); ?></td>

				<td><?php echo round($Tax_cgst,2); ?></td>

				<td><?php echo round($Tax_igst,2); ?></td>

				<td><?php echo round($Tax_cess,2); ?></td>

				<td><?php echo round($Tax_vat,2); ?></td>

				<td><?php echo round($Tax_surcharge,2); ?></td>
        
        <td class="col-xs-12 col-md-3 col-sm-2"> 
        	<input autocomplete="off" class="form-control" type="text" name="id_pos_detail[<?php echo $counter1?>][item_TotalAmountItem]" id="item_TotalAmountItem<?php echo $counter1?>" value="" style="width:80px;" readonly="readonly" />        
        </td>
                                    
    <td><img src="../images/delete.gif"  onclick="deleteitemRow(<?php echo $counter1?>);" class="ibtnDel1" id="deletes<?php echo $counter1?>" name="deletes<?php echo $counter1?>" style="cursor:pointer;" title="Delete"/></td>
     </tr>   
<?php 

 

?>
