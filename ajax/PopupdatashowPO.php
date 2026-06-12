<?php include_once("../config/auto_loader.php");


$idss = $_POST["id_inv_indent"];

 $id_inv_indent = explode('-', $_POST["id_inv_indent"]); 
 $array =$_POST["array"]; 
 $counter1 =$_POST["counter1"]; 
 $match =$_POST["match"]; 
 $checkbox =$_POST["checkbox"]; 

 $id = $id_inv_indent['1'];

//Popup Table Show
if($checkbox){ 
	$where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' " ;
}else{
	$where = "mst_attributes.id=inv_indent.id_mst_attributes_department and inv_indent.id = inv_indent_details.id_inv_indent and inv_indent_details.id_inv_items = inv_items.id  and inv_indent.id_shop = '".addslashes($_SESSION['shop'])."' and inv_indent.doc_type = '2' and inv_indent_details.id_inv_indent = '".$id."' " ;
}



 $sql = "SELECT inv_indent.doc_date, inv_indent.mdoc_no,  inv_indent.doc_no, 
		                   	inv_indent_details.qty,inv_indent_details.alt_qty, inv_indent_details.id,inv_indent_details.id_inv_indent, inv_indent_details.main_unit, inv_indent_details.alt_unit, inv_indent_details.bal_qty, inv_indent_details.ordered_qty, 
		                   	inv_items.item_code, inv_items.name, inv_items.conversion_qty, inv_items.id as item_id, 
		                   	mst_attributes.field_value 
		                   	FROM inv_items, mst_attributes, inv_indent_details, inv_indent WHERE $where";
		                   	$db->query($sql);
		                   	$numRows= $db->num_rows();

		                   	//Table Section
		                   	$table =  '';
		                   	$i=1;
		                   	$table = '<span style="float:right;"><input type="checkbox" id="parent" onclick="numR('.$numRows.');"  /> Select All</span>
		                  <table  class="table table-striped  table-bordered dataTable no-footer" style="background:#fff;width:1226px;display: table-caption;" border="1"> 
		                   	<thead>					        	 	
					                <tr style="text-align: center; font-size: 14px;">
					                    <th style="width: 5%;">S.NO</th> 
					                    <th style="width: 10%;">Indent No</th> 
					                    <th  style="width: 10%;">Item Code</th> 
					                    <th  style="width: 15%;">Description</th> 
					                    <th  style="width: 10%;">Department</th> 
					                    <th style="width: 10%;">Stock In Hand</th> 
					                    <th style="width: 10%;">Unit</th>  	
					                    <th style="width: 10%;">Indent Received</th> 	
					                    <th style="width: 10%;">PO Issued</th> 	
					                    <th style="width: 10%;display:none;">AltUnit</th>  
					                    <th style="width: 10%;">PO Pending</th> 
					                    <th style="width: 10%;">Select</th> 
					                </tr>
					            </thead>';
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
								//GRN DETAILS 
								$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->item_id."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->item_id."'");
								//Physical Stock
								$physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->item_id."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->item_id."'");

								$stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;
								
								//Indent Po Qty
								$indent_po_qty = selectColumn(TBL_INV_INDENT_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='2' AND id_inv_items ='".$row22->item_id."'");
								
								
								//Indent Po Balance Qty
								 $indent_po_balance_qty= selectColumn(TBL_INV_INDENT_DETAILS,'sum(bal_qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='2' AND id_inv_items ='".$row22->item_id."' AND id_inv_indent ='".$row22->id_inv_indent."' ");
								
								//$item_code=selectColumn(TBL_INV_ITEMS,'item_code','WHERE id="'.$row->id_inv_items.'" ');
								
								//$indent_po_balance_qty= selectColumn(TBL_INV_INDENT_DETAILS,'bal_qty'," WHERE  id_inv_items ='".$row22->item_id."' ");
								
								if($indent_po_balance_qty>0){
								
								if($type == 0){
		                   		$table .= ' <tr class="table-row" style="text-align: center;">
			                    		<td>
			                    			<input type="text" class="form-control" id="wmatch" name="wmatch"  value="'.$match.'" style="display: none;">
			                    			<input type="text" class="form-control" id="wid'.$i.'" name="wid'.$i.'"  value="'.$row22->id.'" style="display: none;">

			                    			<input type="text" class="form-control" id="witemid'.$i.'" name="witemid'.$i.'"  value="'.$row22->item_id.'" style="display: none;">

			                    			<input type="text" class="form-control" id="wconversion_qty'.$i.'" name="wconversion_qty'.$i.'"  value="'.$row22->conversion_qty.'" style="display: none;">

			                    			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wdoc_no'.$i.'" name="wdoc_no'.$i.'"  value="'.$row22->doc_no.' " readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="witem_code'.$i.'" name="witem_code'.$i.'"  value="'.$row22->item_code.'" readonly>
			                    		</td>
			                    		<td>'; 
			                    		$table .= '<span>'.$row22->name.'</span>';
			                    		$table .= '<input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="'.$row22->name.' " readonly style="display:none;">
			                    		</td> 
			                    		<td>
			                    			<input type="text" class="form-control" id="wdepartment'.$i.'" name="wdepartment'.$i.'"  value="'.$row22->field_value.'" readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wstock_hand'.$i.'" name="wstock_hand'.$i.'"  value="'.$stock_in_hand.'" readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wmain_unit'.$i.'" name="wmain_unit'.$i.'"  value="'.$row22->main_unit.' " readonly> 
			                    		</td>
			                    		
			                    		<td>
			                    			<input type="text" class="form-control" id="windent_qty'.$i.'" name="windent_qty'.$i.'"  value="'.$row22->qty.'" readonly>
			                    		</td>

			                    		<td>
			                    			<input type="text" class="form-control" id="wordered_qty'.$i.'" name="wordered_qty'.$i.'"  value="'.$row22->ordered_qty.'" readonly>
			                    		</td>
			                    		
			                    		<td style="display:none;">
			                    			<input type="text" class="form-control" id="walt_unit'.$i.'" name="walt_unit'.$i.'"  value="'.$row22->alt_unit.' " readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wbalance'.$i.'" name="wbalance'.$i.'"  value="'.$indent_po_balance_qty.' "  readonly >
			                    		</td>
			                    		<td> 
			                    			 <input type="checkbox" class="child" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">
			                    			<input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
			                    		</td>
			                    		<td style="display: none;">
			                    			<input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
			                    		</td> 
			                    		<td style="display: none;">
			                    			<input type="text" class="form-control" id="wpop'.$i.'" name="wpop'.$i.'"  value="'.$idss.'" style="display: none;">
			                    		</td> 
			                    	</tr>  '; 
				                    	$i++;
				                     }
				                     }
		                   	} 

		                   	 	$table .= '</table>';
		                  
		                    
 
echo ($table); 
?>

</div> 
<script type="text/javascript">

//select all function starts
function numR(id){
	//alert(id);
    var clicked =false;

	$('.child').prop('checked', !clicked);
	clicked = !clicked;

     // var id;
     //var match =2;
     for(i=1;i<=id;i++){

     	//alert(i);
		var wcheckbox = document.getElementById("wcheckbox"+i).checked;
       // alert(wcheckbox);
		if(clicked == true){
			document.getElementById("wselect"+i).value = '1';
		}else if(clicked == false){
			//match=0;
			document.getElementById("wselect"+i).value = '0';
		}

	//this.innerHTML = clicked ? 'DESELECT' : 'SELECT';

     }
	
}

//select all function ends

	function checkboxs(clicked_id){
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(clicked_id));
		var wcheckbox = document.getElementById("wcheckbox"+match).checked;

		if(wcheckbox == true){
			document.getElementById("wselect"+match).value = '1';
		}else if(wcheckbox == false){
			document.getElementById("wselect"+match).value = '0';
		}
	}

	function myFunction() {
	  var input, filter, table, tr, td, i, txtValue;
	  input = document.getElementById("myInput");
	  filter = input.value.toUpperCase();
	  table = document.getElementById("myTables");
	  tr = table.getElementsByTagName("tr");
	  for (i = 0; i < tr.length; i++) {
	    td = tr[i].getElementsByTagName("td")[3];
	    if (td) {
	      txtValue = td.textContent || td.innerText;
	      if (txtValue.toUpperCase().indexOf(filter) > -1) {
	        tr[i].style.display = "";
	      } else {
	        tr[i].style.display = "none";
	      }
	    }       
	  }
	}
</script>