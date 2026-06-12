

<?php

include_once("../config/auto_loader.php");

//if($_REQUEST['id_inv_indent']!=''){
 $itemDetailSizeOf=	sizeof($_REQUEST['id_inv_indent']);
 for($i=0;$i<$itemDetailSizeOf;$i++){
	 
	 $id_inv_indent1 = explode('-', $_REQUEST['id_inv_indent'][$i]); 
        $id_po .= $id_inv_indent1[0].',';
        $id_po1 .= $id_inv_indent1[1].',';
 }		
		
 $id = rtrim($id_po,',');
 $id1 = rtrim($id_po1,',');

//$idss = $id_inv_indent;

//echo $id_inv_indent1 = explode('-', $id_inv_indent[0]); 

 $array =$_POST["array"]; 
 $counter1 =$_POST["counter1"]; 
 $match =$_POST["match"]; 
 $eId =$_POST["eid"]; 
 $checkbox =$_POST["checkbox"];
 
 
 //echo "SELECT * FROM inv_indent_details WHERE  id = '".$id1."'";
 
//echo "SELECT inv_purch_details.*, inv_indent_details.id_inv_indent FROM `".TBL_INV_PURCH_DETAILS."` LEFT JOIN inv_indent_details ON inv_indent_details.id = inv_purch_details.id_inv_indent_details WHERE inv_purch_details.id_inv_purch = '".addslashes(encryptor(decrypt,$eId))."' AND inv_indent_details.id_inv_indent IN ($id) ";


 

 //Popup Table Show
if($eId !=''){ 
	$where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '1' and inv_indent_details.id_inv_indent IN ($id) " ; 
}else{
	$where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '1' and inv_indent_details.bal_qty>0 and inv_indent_details.id_inv_indent IN ($id) " ;
}


if($eId !='' && $id !=''){ 
   $sql= "SELECT inv_purch_details.*, inv_indent_details.id_inv_indent FROM `".TBL_INV_PURCH_DETAILS."` LEFT JOIN inv_indent_details ON inv_indent_details.id = inv_purch_details.id_inv_indent_details WHERE inv_purch_details.id_inv_purch = '".addslashes(encryptor(decrypt,$eId))."' AND inv_indent_details.id_inv_indent IN ($id) ";
	
}else{
	
	$sql = "SELECT inv_indent.doc_date, inv_indent.mdoc_no,  inv_indent.doc_no, 
	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id,inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, inv_indent_details.bal_qty, 
	inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
	mst_attributes.field_value FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE $where ";
}
	


	$db->query($sql);
	$numRows= $db->num_rows();
	
		$i='';
		$returnData='';
		$returnArr=array();
		
			while($row22 = $db->fetch_object()){  

		                   	 	if($counter1 == 0){
		                   	 		$type = 0;
		                   	 	}else{
									 if (in_array($row22->id, $array)) 
									  { 
									  	$type = 1; 
									  }else{
									  	$type = 0;
									  } 
								}
								if($checkbox){
									$idss = $row22->id.'-'.$row22->id_inv_indent;
								}
								
								if($eId !=''){ 
									$qty = $row22->qty;
								}else{
									$qty = $row22->bal_qty;
								}
								
					if($eId !=''){ 		
						$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_inv_items)."'");
						$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_inv_items)."'");
						$alt_qty = $row22->alt_qty;
						
					$conversion_qty =  selectColumn(TBL_INV_ITEMS,'conversion_qty'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_inv_items)."'");
					
					}else{
						$item_code  =  $row22->item_code;
						$item_description = $row22->name;
						$alt_qty = "";
						$conversion_qty =  $row22->conversion_qty;
					}
//echo $alt_qty;					
								
								

								//GRN DETAILS 
								$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->item_id."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->item_id."'");
								//Physical Stock
								$physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->item_id."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->item_id."'");

								//Indent Po Qty
								$indent_po_qty = selectColumn(TBL_INV_INDENT_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->item_id."'");
								//Indent Po Balance Qty
								$indent_po_balance_qty= selectColumn(TBL_INV_INDENT_DETAILS,'sum(bal_qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->item_id."'");

								$stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;
								
								//echo $row22->main_unit;
  
			
$returnData.='<tr>';
				
$returnData.= '<td class="form-group col-md-2"><select onchange="popupshow(this.id);" name="id_inv_indent'.$i.'" id="id_inv_indent'.$i.'" class="form-control select2" style="width:100%;">';

$sqlCharge = "SELECT A.doc_no,A.doc_date,B.* FROM ".TBL_INV_INDENT." A LEFT JOIN ".TBL_INV_INDENT_DETAILS." B ON A.id=B.id_inv_indent
	WHERE A.doc_type=1 AND A.id_shop=".$_SESSION['shop']."  AND B.bal_qty>'0' GROUP BY B.id_inv_indent ";

$resCharge = mysqli_query($connNew,$sqlCharge);

while($rowCharge = mysqli_fetch_object($resCharge)){
	if($row22->id_inv_indent == $rowCharge->id_inv_indent){
		$selected="selected='selected'";
	}else{
	$selected="";}
	
	$returnData.='<option '.$selected.' value="'.$rowCharge->id.'">'.$rowCharge->doc_no.' | '.date('d-m-Y',strtotime($rowCharge->doc_date)). '</option>';
}
    $returnData.='</select><input type="text"  autocomplete="off"  class="form-control" name="id_inv_indent_details'.$i.'" value="'.$row22->id.'" id="id_inv_indent_details'.$i.'" readonly style="display:none;" /></td>';
	
$returnData.= '<td class="form-group col-xs-12 col-md-3 col-sm-2"><input hidden id="select'.$i.'" name="select'.$i.'"> <input type="text"  autocomplete="off"  class="form-control" name="id_inv_items'.$i.'" value="'.$row22->item_id.'" id="id_inv_items'.$i.'" style="display:none" /><input type="text"  autocomplete="off" class="form-control" name="item_code'.$i.'" value="'.$item_code.'" id="item_code'.$i.'" readonly /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-2"><input type="text"  autocomplete="off" class="form-control" name="item_description'.$i.'" value="'.$item_description.'" id="item_description'.$i.'" readonly /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-1"><input type="text"  autocomplete="off" class="form-control discountvalue" onkeyup="qtycalc(this.id)" onclick="qtycalc(this.id)" name="qty'.$i.'" value="'.$qty.'" id="qty'.$i.'"  /><input type="hidden"  autocomplete="off" class="form-control" name="conversion_qty'.$i.'" value="'.$conversion_qty.'" id="conversion_qty'.$i.'"  /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-1"><input type="text"  autocomplete="off" class="form-control" name="main_unit'.$i.'" value="'.$row22->main_unit.'" id="main_unit'.$i.'"  /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-1"><input type="text" onkeyup="altqtycalc(this.id)"  autocomplete="off" class="form-control discountvalue" name="alt_qty'.$i.'" value="'.$alt_qty.'" id="alt_qty'.$i.'"  /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-1"><input type="text"  autocomplete="off" class="form-control" name="alt_unit'.$i.'" value="'.$row22->alt_unit.'" id="alt_unit'.$i.'"  /><input type="text"  autocomplete="off" class="form-control" name="conver_rate_per_unit'.$i.'" value="'.$conversion_qty.'" id="conver_rate_per_unit'.$i.'" style="display:none" /></td>';

$returnData.= '<td class="form-group col-xs-12 col-sm-3"><input type="text"  autocomplete="off" class="form-control" name="remarks_purch_details'.$i.'" value="'.$row22->remarks_purch_details.'" placeholder="Remarks" id="remarks_purch_details'.$i.'"  /><input type="text"  autocomplete="off" class="form-control" name="item_amount'.$i.'" value="" id="item_amount'.$i.'" style="display:none" /></td>';


if($i>=1){
	$returnData.= '<td><img src="images/delete.gif"  class="ibtnDel2" id="deletes'.$i.'" name="deletes'.$i.'" style="cursor:pointer;" title="Delete"/></td><td class="form-group col-xs-12 col-sm-2"><a class="deleteRow"></a></td>';
}
$returnData.='</tr>';
			
 $returnArr['countby']	= $i;	
		//$returnArr['discount_total']+=$row->others_charges_amount;
			$i++;

		}
		 $returnArr['count']=($i-1);
		$returnArr['data']=$returnData;
		echo json_encode($returnArr);

	//}
?>


