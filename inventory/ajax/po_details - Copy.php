
<?php

include_once("../../config/auto_loader.php");

//if($_REQUEST['id_inv_po']!=''){
 $itemDetailSizeOf=	sizeof($_REQUEST['id_inv_po']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
        $id_po .= $_REQUEST['id_inv_po'][$i].',';
 }		
		
 $id = rtrim($id_po,',');

 $array =$_POST["array"]; 
 $doc_type =$_POST["doctype"]; 
  $counter1 =$_POST["counter1"]; 
 $match =$_POST["match"];
 //$match ='3'; 
$checkbox =$_POST["checkbox"]; 
$id_mst_party_supplier =$_POST["id_mst_party_supplier"]; 


$eId =$_POST["eid"];

if($eId !=''){ 
	$where = " inv_po_details.id_inv_po = inv_po.id AND inv_po.id_mst_party_supplier ='".$id_mst_party_supplier."' AND inv_po.id_shop = '".addslashes($_SESSION['shop'])."'  AND inv_po_details.id_inv_po IN ($id)  " ;
	//$where = " inv_po_details.id_inv_po = inv_po.id AND inv_po.id_mst_party_supplier ='".$id_mst_party_supplier."' AND inv_po.id_shop = '".addslashes($_SESSION['shop'])."'" ;
}else{
	$where = " inv_po_details.id_inv_po = inv_po.id AND inv_po.id_mst_party_supplier ='".$id_mst_party_supplier."' AND inv_po.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_po_details.bal_qty>0  AND inv_po_details.id_inv_po IN ($id)  " ;
}

//echo "SELECT inv_po.id as id_po, inv_po_details.* FROM  inv_po, inv_po_details WHERE $where".'<br>'; 
 
if($eId ==''){
	
 $sql = "SELECT inv_po.id as id_po, inv_po_details.* FROM  inv_po, inv_po_details WHERE $where";
 
}else if($eId !='' && $id != ''){
	
	$sql = " SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND qty>0  AND id_inv_po IN ($id) AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$eId))."' ";
	
	// $sql = "SELECT inv_po.id as id_po, inv_po_details.* FROM  inv_po, inv_po_details WHERE  $where AND qty>0";
	
} 

//echo $sql;
		                   	
		$res = mysqli_query($connNew,$sql);
		$numRows = mysqli_num_rows($res);
		$i='';
		$returnData='';
		$returnArr=array();
		$returnArr['discount_total']= 0 ;
		
		
		while($row = mysqli_fetch_object($res)){
			
		$sql1 = "SELECT * FROM ".TBL_INV_OTHERS_CHARGES." WHERE id_inv_po IN (".$id.") ";
		$res1 = mysqli_query($connNew,$sql1);
			while($row1 = mysqli_fetch_object($res1)){
				$ot_type1 =  $row1->type.',';
			}
		$ot_type = rtrim($ot_type1,',');
		
		
		if($eId !=''){ 
			$qty_value = $row->qty;
		}else{
			$qty_value = $row->bal_qty;
		}
		
			
  $item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_inv_items)."'");
  $item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_inv_items)."'");
  
  
  if($row->id_mst_attributes_store == ''){
		                    		//Item Table
	$item_store  =  selectColumn(TBL_INV_ITEMS,'id_mst_attributes_store'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_inv_items)."'");

	$store  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($item_store)."'");
	//Store Id
	$storeid = $item_store;
}else{
	$store  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_mst_attributes_store)."'");
	//Store Id
	$storeid = $row->id_mst_attributes_store;
}
  
  
$doc_date  =  selectColumn('inv_po','doc_date'," WHERE  `id` = '".addslashes($row->id_inv_po)."'");
$date_po = date('d-m-Y' , strtotime(addslashes($doc_date)));
	                			
	                			//Unit and Amount Show Here
	                			$transaction_unit = $row->transaction_unit;
		                    	$main_unit = $row->main_unit;
		                    	$alt_unit = $row->alt_unit;
		                    	$per_unit = $row->per_unit;
		                    	if($transaction_unit == $main_unit){
		                    		$qty = $row->qty; 
		                    	}else{
		                    		$qty = $row->alt_qty; 
		                    	}
		                    	if($per_unit == $main_unit){ 
		                    		$rate_per_main_unit = $row->rate_per_main_unit;
		                    	}else{ 
		                    		$rate_per_main_unit = $row->rate_per_alt_unit;
		                    	}
		                    	if($row->id_mst_charges_purchase_local != 0){

		                    	$local  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_mst_charges_purchase_local)."'");
		                    	}
		                    	if($row->id_mst_charges_purchase_interstate != 0){

		                    	$interstate  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->id_mst_charges_purchase_interstate)."'");
		                    	}
		                    	
		                    	//GRN DETAILS 
								$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row->id_inv_items."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row->id_inv_items."'");
								//Physical Stock
								$physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row->id_inv_items."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row->id_inv_items."'"); 
 //echo "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$id_mst_party_supplier."' ";

			
$returnData.='<tr id="edittrdelete'.$i.'">';
				
$returnData.= '<td class="form-group col-md-2"><select onchange="popupshow(this.id);" onclick="popupshow(this.id);" name="id_inv_po'.$i.'" id="id_inv_po'.$i.'" class="form-control select2" style="width:100%;">';

 $sqlCharge = "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$id_mst_party_supplier."' ";
	
$resCharge = mysqli_query($connNew,$sqlCharge);
while($rowCharge = mysqli_fetch_object($resCharge)){
	if($rowCharge->id==$row->id_inv_po){
		$selected="selected='selected'";
	}else{
	$selected="";}
	$returnData.='<option '.$selected.' value="'.$rowCharge->id.'">'.$rowCharge->doc_no.' | '.$date_po. '</option>';
}
    $returnData.='</select>';
	
$returnData.= '<td id="hideshow_item_code"><input type="text"  autocomplete="off"  class="form-control" name="item_code'.$i.'" value='.$item_code.' id="item_code'.$i.'" readonly /></td><td class="form-group col-md-2"><input type="text"  autocomplete="off" class="form-control" name="item_description'.$i.'" value="'.$item_description.'" id="item_description'.$i.'" readonly /><input type="hidden"  autocomplete="off" class="form-control" name="counterby" value="'.$i.'" id="counterby" readonly /></td>';
	
	
$returnData.= '<td class="form-group col-md-2"><select class="form-control select2" name="id_mst_attributes_store'.$i.'" id="id_mst_attributes_store'.$i.'" style="width:100%;" >';
$sqlCharge = "SELECT * FROM mst_charges where id_shop='".$_SESSION['shop']."' AND charges_account IN (6,7) ";
$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and table_name='store' ",' ORDER BY `field_value`');
if($db->num_rows2($resCat)){
while($resultCat = $db->fetch_object2($resCat)){

	if($storeid==$resultCat->id)
		$selected="selected='selected'";
	else
		$selected="";

	$returnData.='<option '.$selected.' value="'.$resultCat->id.'">'.$resultCat->field_value.'</option>';
 }}
$returnData.='</select></td>';


$returnData.= '<td class="form-group col-md-2" style="display:none"><select class="form-control select2" onchange="itemget(this.id)" name="id_inv_items_po'.$i.'" id="id_inv_items_po'.$i.'" style="width:100%;" >';

$sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
	$QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
	
		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
	        $list = $sqlRow->id;
			$string .= $list.',';
		}	
$item_list = rtrim($string,',');


	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and  inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
		 $db->query($sql); 
		while($row1 = $db->fetch_object()){	

	$returnData.='<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name).'</option>';
 }
$returnData.='</select></td>';

$returnData.= '<td class="form-group col-md-1 "><input type="text"  onkeyup="amount_calc(this.id);" onclick="amount_calc(this.id);" autocomplete="off" name="qty'.$i.'" class="form-control discountvalue" value="'.$qty_value.'" id="qty'.$i.'" /><input type="text"  autocomplete="off" name="id_inv_po_details'.$i.'" id="id_inv_po_details'.$i.'" placeholder="ID"  class="form-control"  value="'.$row->id.'" readonly=""  style="display:none;" /><input type="text"  autocomplete="off"  class="form-control " name="main_unit'.$i.'" value="'.$row->main_unit.'" id="main_unit'.$i.'" style="display:none" /><input type="text"  autocomplete="off"  class="form-control " name="alt_unit'.$i.'" value="'.$row->alt_unit.'" id="alt_unit'.$i.'"  style="display:none" /><input type="text"  autocomplete="off"  class="form-control " name="conver_rate_per_unit'.$i.'" value="'.$row->conver_rate_per_unit.'" id="conver_rate_per_unit'.$i.'"  style="display:none" /><input type="text"  autocomplete="off"  class="form-control " name="item_amount_before_discount'.$i.'" value="" id="item_amount_before_discount'.$i.'"  style="display:none" /></td>'; 

$returnData.= '<td class="form-group col-md-1"><select  name="transaction_unit'.$i.'" class="form-control select2" id="transaction_unit'.$i.'"  style="width:100%;" ><option selected="selected" value="'.$row->main_unit.'">'.$row->main_unit.'</option><option value="'.$row->alt_unit.'">'.$row->alt_unit.'</option>';
$returnData.='</select>'; 

$returnData.= '<td class="form-group col-md-1"><input type="text"  autocomplete="off"  class="form-control discountvalue" name="rate_per_main_unit'.$i.'" value="'.$rate_per_main_unit.'" id="rate_per_main_unit'.$i.'"onkeyup="amount_calc(this.id)" /><input type="text"  autocomplete="off"  class="form-control" name="id_inv_items'.$i.'" value="'.$row->id_inv_items.'" id="id_inv_items'.$i.'"  style="display:none;"/><input type="text"  autocomplete="off"  class="form-control" name="id_inv_po_details'.$i.'" value="'.$row->id.'" id="id_inv_po_details'.$i.'"  style="display:none;"/><input type="text"  autocomplete="off"  class="form-control" name="id_inv_po_detailss'.$i.'" value="'.$row->id.'" id="id_inv_po_detailss'.$i.'"  style="display:none;"/></td>';

$returnData.= '<td class="form-group col-md-1"><select  name="per_unit'.$i.'" class="form-control select2" id="per_unit'.$i.'"  style="width:100%;" ><option selected="selected" value="'.$per_unit.'">'.$per_unit.'</option><option value="'.$row->main_unit.'">'.$row->main_unit.'</option><option value="'.$row->alt_unit.'">'.$row->alt_unit.'</option>';
$returnData.='</select>'; 	

$returnData.= '<td class="form-group col-md-1"><input type="text"  autocomplete="off" class="form-control discountvalue" name="discount_percent'.$i.'"  onkeyup="amount_calc(this.id);"  onclick="amount_calc(this.id);" value="'.$row->discount_percent.'" id="discount_percent'.$i.'" /></td>'; 

$returnData.= '<td class="form-group col-md-2"><input type="text"  autocomplete="off" class="form-control discountvalue" name="item_amount'.$i.'" value="'.$row->item_amount.'" id="item_amount'.$i.'" style="width:100px" readonly /><div id="taxconfigg" style="display:none"><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_sgst'.$i.'" value="" id="id_mst_charges_sgst'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_sgst_percent'.$i.'" value="" id="item_sgst_percent'.$i.'"  /><input type="text"  autocomplete="off"  class="form-control" name="item_sgst_amount'.$i.'" value="" id="item_sgst_amount'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_cgst'.$i.'" value="" id="id_mst_charges_cgst'.$i.'"   /><input type="text"  autocomplete="off"  class="form-control" name="item_cgst_percent'.$i.'" value="" id="item_cgst_percent'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_cgst_amount'.$i.'" value="" id="item_cgst_amount'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_igst'.$i.'" value="" id="id_mst_charges_igst'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_igst_percent'.$i.'" value="" id="item_igst_percent'.$i.'"   /><input type="text"  autocomplete="off"  class="form-control" name="item_igst_amount'.$i.'" value="" id="item_igst_amount'.$i.'"   /></div></td>'; 




//<div id="taxconfigg"><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_sgst'.$i.'" value="" id="id_mst_charges_sgst'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_sgst_percent'.$i.'" value="" id="item_sgst_percent'.$i.'"  /><input type="text"  autocomplete="off"  class="form-control" name="item_sgst_amount'.$i.'" value="" id="item_sgst_amount'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_cgst'.$i.'" value="" id="id_mst_charges_cgst'.$i.'"   /><input type="text"  autocomplete="off"  class="form-control" name="item_cgst_percent'.$i.'" value="" id="item_cgst_percent'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_cgst_amount'.$i.'" value="" id="item_cgst_amount'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="id_mst_charges_igst'.$i.'" value="" id="id_mst_charges_igst'.$i.'" /><input type="text"  autocomplete="off"  class="form-control" name="item_igst_percent'.$i.'" value="" id="item_igst_percent'.$i.'"   /><input type="text"  autocomplete="off"  class="form-control" name="item_igst_amount'.$i.'" value="" id="item_igst_amount'.$i.'"   /></div>



$returnData.='</tr>';

$returnData.='<tr id="edittrdeletes'.$i.'">';
	$returnData.= '<td class="form-group col-md-1"><input type="text"  autocomplete="off" class="form-control" name="item_remarks'.$i.'" id="item_remarks'.$i.'" placeholder="Remarks" value='.$row->item_remarks.' ></td>';


if($_REQUEST['ledger_id'] == 1){
$returnData.= '<td class="form-group col-md-2"><select onchange="po_locals(this.id);" class="form-control select2" name="id_mst_charges_purchase_local'.$i.'" id="id_mst_charges_purchase_local'.$i.'" style="width:100%;" >';
$returnData.='<option selected="selected" value="'.$row->id_mst_charges_purchase_local.'">'.$local.'</option>';
//echo "SELECT * FROM mst_charges where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2' and transaction_type = '1' ";
 $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2' and transaction_type = '1' ",' ORDER BY `name`');
if($db->num_rows2($resCat)){
while($resultCat = $db->fetch_object2($resCat)){
	$returnData.='<option value="'.$resultCat->id.'">'.$resultCat->name.'</option>';
	//$returnData.='$("#id_mst_charges_purchase_local").change()';
 }}
$returnData.='</select>';
}else


if($_REQUEST['ledger_id'] == 2){
$returnData.= '<td class="form-group col-md-2"><select  onchange="po_interstate(this.id)" class="form-control select2" name="id_mst_charges_purchase_interstate'.$i.'" id="id_mst_charges_purchase_interstate'.$i.'" style="width:100%;" >';
$returnData.='<option selected="selected" value="'.$row->id_mst_charges_purchase_interstate.'">'.$interstate.'</option>';
$resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '2' and transaction_type = '2' ",' ORDER BY `name`');
if($db->num_rows2($resCat)){
while($resultCat = $db->fetch_object2($resCat)){

$categoryDropDown .= '<option value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';

//$returnData.='.$("#id_mst_charges_purchase_interstate").change().';

}}
$returnData.='</select>';
}


$returnData.='<td><div id="localss'.$i.'" name="localss'.$i.'" >
<span style="color:red;font-size:14px;" id="s_amount'.$i.'">'.$sgst.'</span>
<span style="color:red;font-size:14px;" id="c_amount'.$i.'">'.$cgst.'</span>
</div>
 <div id="interstatess'.$i.'" name="interstatess'.$i.'">
	<span style="color:red;font-size:14px;" id="i_amount'.$i.'">'.$igst.'</span>
</div>
</td>';

if($i>=1){
	$returnData.= '<td><img src="images/delete.gif"  class="ibtnDel1" id="deletes'.$i.'" name="deletes'.$i.'" style="cursor:pointer;" title="Delete"/></td>';
}
$returnData.='</tr>';
			
 $returnArr['countby']	= $i;	
		//$returnArr['discount_total']+=$row->others_charges_amount;
			$i++;

		}
		 $returnArr['count']=($i-1);
		 $returnArr['type']=($ot_type);
		// $returnArr['countby']=($i);
		$returnArr['data']=$returnData;
		echo json_encode($returnArr);

	//}
?>


