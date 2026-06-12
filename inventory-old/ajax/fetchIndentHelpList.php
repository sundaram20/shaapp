<?php
include_once("../../config/auto_loader.php");
?>
<?php 
	if($_REQUEST['id_department']!=''){
	
	$returnData='';	
	
	//Total Items Id Get Here

	$i=1;
	$sno=1;
	

	 $sql ="SELECT sum(qty) AS qty,sum(ordered_qty) AS ordered_qty,sum(bal_qty) AS bal_qty,id_inv_items FROM ".TBL_INV_INDENT_DETAILS." LEFT JOIN ".TBL_INV_INDENT." B ON ".TBL_INV_INDENT_DETAILS.".id_inv_indent=B.id   WHERE B.id_shop='".$_SESSION['shop']."' AND B.id_mst_attributes_department='".$_REQUEST['id_department']."' AND B.doc_type=1 AND bal_qty > 0 group by id_inv_items order by id_inv_items";

	$res=mysqli_query($connNew,$sql);
	$numRows = mysqli_num_rows($res);

	while($row=mysqli_fetch_object($res)){
		$indent_qty = 0;
		$pendingIndent=0;
		$pendingPO = 0;
		$item_code='';
		$min_qty=0;
		$seggstionQty=0;
		//Stock in hand
		$openingBal=0;
		$issuedQty= 0;
		$directPurchQty=0;
		$stockAdd=0;
		$stockLess=0;
		$receivedQty=0;

		$wmain_unit='';
		$walt_unit='';
		

		// formula 
		//stock in hand = (opening bal + GRN + DP + Stock Add)-(Stock less + SIN )

		$stock_in_hand  =0;
		//end
		// items related 
		$item_code=selectColumn(TBL_INV_ITEMS,'item_code','WHERE id="'.$row->id_inv_items.'" ');
		$item_name=selectColumn(TBL_INV_ITEMS,'name','WHERE id="'.$row->id_inv_items.'" ');
		$min_qty=selectColumn(TBL_INV_ITEMS,'min_qty','WHERE id="'.$row->id_inv_items.'" ');

		$id_mst_attributes_unit_main=selectColumn(TBL_INV_ITEMS,'id_mst_attributes_unit_main','WHERE id="'.$row->id_inv_items.'" ');
		$id_mst_attributes_unit_alt =selectColumn(TBL_INV_ITEMS,'id_mst_attributes_unit_alt','WHERE id="'.$row->id_inv_items.'" ');

		// end

		// pending related

		$pendingIndent=selectColumn(TBL_INV_INDENT_DETAILS,'sum(bal_qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" AND doc_type=2 ');

		if($pendingIndent=='')
			$pendingIndent=0;

		$pendingPO = selectColumn(TBL_INV_PO_DETAILS,'sum(bal_qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" ');

		if($pendingPO=='')
			$pendingPO=0;

		//end

		$wmain_unit=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$id_mst_attributes_unit_main ."'"); 

		$walt_unit=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$id_mst_attributes_unit_alt ."'");

		// Calculating stock in hand for each item below

		$openingBal=selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" AND doc_type=100 ');

		$issuedQty= selectColumn(TBL_INV_PURCH,'sum('.TBL_INV_PURCH_DETAILS.'.qty)','RIGHT   JOIN '.TBL_INV_PURCH_DETAILS.' ON '.TBL_INV_PURCH.'.id='.TBL_INV_PURCH_DETAILS.'.id_inv_purch  WHERE '.TBL_INV_PURCH_DETAILS.'.id_inv_items="'.$row->id_inv_items.'" AND '.TBL_INV_PURCH.'.id_shop="'.$_SESSION['shop'].'" AND '.TBL_INV_PURCH.'.doc_type=6 ');

		$directPurchQty=selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" AND doc_type=12 ');

		$stockAdd=selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" AND doc_type=13 ');

		$stockLess=selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)','WHERE id_inv_items="'.$row->id_inv_items.'" AND id_shop="'.$_SESSION['shop'].'" AND doc_type=14 ');

		
		$receivedQty=selectColumn(TBL_INV_PURCH,'sum('.TBL_INV_PURCH_DETAILS.'.qty)','LEFT  JOIN '.TBL_INV_PURCH_DETAILS.' ON '.TBL_INV_PURCH.'.id='.TBL_INV_PURCH_DETAILS.'.id_inv_purch  WHERE '.TBL_INV_PURCH_DETAILS.'.id_inv_items="'.$row->id_inv_items.'" AND '.TBL_INV_PURCH.'.id_shop="'.$_SESSION['shop'].'" AND '.TBL_INV_PURCH.'.doc_type=4 ');

		$stock_in_hand  =($openingBal + $directPurchQty + $receivedQty + $stockAdd)-($stockLess + $issuedQty );

		//end

		// calculating suggest quantity
		$seggstionQty=($min_qty+$row->qty)-($stock_in_hand+$pendingPO+$pendingIndent);

		if($seggstionQty<0){
			$seggstionQty=0;
		}
	
?>





<?php

if($seggstionQty >0){
						$returnData.='<tr>
                    		<td>
                    			<input type="hidden" class="form-control" id="wid'.$i.'" name="wid'.$i.'"  value="'.$row->id_inv_items.'" >
                    			
                    			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$sno++.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="witem_code'.$i.'" name="witem_code'.$i.'"  value="'.$item_code.'" readonly>
                    		</td>
                    		<td>
                    			'.$item_name.'
                    			<input type="hidden" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="'.$item_name.'" >
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="wrequirement'.$i.'" name="wrequirement'.$i.'"  value="'.$row->bal_qty.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="wstock_hand'.$i.'" name="wstock_hand'.$i.'"  value="'.$stock_in_hand.'" readonly>
                    		</td>

                    		<td>
                    			<input type="text" class="form-control" id="wstock_min'.$i.'" name="wstock_min'.$i.'"  value="'.$min_qty.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="wpending_po'.$i.'" name="wpending_po'.$i.'"  value="'.$pendingPO.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="wpending_indent'.$i.'" name="wpending_indent'.$i.'"  value="'.$pendingIndent.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="wsuggstion'.$i.'" name="wsuggstion'.$i.'"  value="'.$seggstionQty.'" readonly>
                    		</td>
                    		<td>
                    			<input type="text" class="form-control" id="windent_qty'.$i.'" name="windent_qty'.$i.'"  placeholder="Enter Qty" value="" >
                    			<input type="hidden" class="form-control" id="wmain_unit'.$i.'" name="wmain_unit'.$i.'"  value="'.$wmain_unit.'" >
                    	<input type="hidden" class="form-control" id="walt_unit'.$i.'" name="walt_unit'.$i.'"  value="'.$walt_unit.'" >
                    		</td>
                    	</tr>
                    	
                	';
                    
                 
	$i++;
 
 $returnData.='<tr><td><input type="hidden" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" ></td></tr>';	
 
	} 
} 

 echo $returnData;

}
else{
	echo  "Please Select Department";
}

?> 
