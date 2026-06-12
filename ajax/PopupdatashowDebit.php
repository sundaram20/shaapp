<?php include_once("../config/auto_loader.php");

 $id = $_POST["id_inv_po"];  

$array =$_POST["array"]; 
 $counter1 =$_POST["counter1"]; 
 $match =$_POST["match"]; 
$checkbox =$_POST["checkbox"]; 
$id_mst_party_supplier =$_POST["id_mst_party_supplier"]; 

if($checkbox){ 
	$where = " inv_purch_details.id_inv_purch = inv_purch.id AND inv_purch.id_mst_party_supplier ='".$id_mst_party_supplier."' AND inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_purch_details.qty>0 AND inv_purch_details.doc_type IN (5,12) " ;
}else{
	$where = " inv_purch_details.id_inv_purch = inv_purch.id AND inv_purch.id_mst_party_supplier ='".$id_mst_party_supplier."' AND inv_purch.id_shop = '".addslashes($_SESSION['shop'])."' AND inv_purch_details.qty>0 AND inv_purch_details.id_inv_purch = '".$id."' AND inv_purch_details.doc_type IN (5,12)  " ;
}
 //echo "SELECT inv_purch.id as id_purch, inv_purch_details.* FROM  inv_purch, inv_purch_details WHERE $where ";
 $sql = "SELECT inv_purch.id as id_purch, inv_purch_details.* FROM  inv_purch, inv_purch_details WHERE $where ";
		                   	$db->query($sql);
		                   	$numRows= $db->num_rows();

		                   	$table =  '';
		                   	$i=1;
		                   	$table = '<thead>					        	 	
					                <tr style="text-align: center; font-size: 14px;">
					                    <th style="width: 5%;">S.NO</th> 
					                    <th style="width: 10%;">PO No</th> 
					                    <th  style="width: 10%;">Item Code</th> 
					                    <th  style="width: 15%;">Description</th>
					                    <th style="width: 10%;">Stock In Hand</th>  
					                    <th style="width: 10%;">Qty</th> 	
					                    <th style="width: 10%;">TransUnit</th>   
					                    <th style="width: 10%;">Rate</th> 
					                    <th style="width: 10%;">Per</th> 
					                    <th style="width: 10%;">Item Amount</th> 
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
									$id = $row22->id_purch;
								}
								//Item Code And Description Get Here
$doc_no=$row22->doc_no;


$sqll = "SELECT * FROM inv_purch_details WHERE id_inv_purch IN (".$id.") ";
 
	$ress = mysqli_query($connNew,$sqll);
	//echo $num = mysqli_num_rows($ress);
	while($roww = mysqli_fetch_object($ress)){
		$newid .= $roww->id_inv_purch.',';
	}
 $final = rtrim($newid,',');
 
 

  $sql1 = "SELECT * FROM ".TBL_INV_OTHERS_CHARGES_PURCH." WHERE id_inv_purch IN (".$final.") ";
		$res1 = mysqli_query($connNew,$sql1);
			while($row1 = mysqli_fetch_object($res1)){
				$ot_type1 =  $row1->type.',';
			}
		$ot_type = rtrim($ot_type1,',');
  

								//Name Get
	                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_inv_items)."'");
								
								
								
								$pono  =  selectColumn('inv_purch','doc_no'," WHERE `id` = '".addslashes($row22->id_purch)."'");
	                			$podate1  =  selectColumn('inv_purch','doc_date'," WHERE `id` = '".addslashes($row22->id_purch)."'");
								$podate  =    date('d-m-Y' , strtotime(addslashes($podate1)));	
								
	                			//Item Description Get
	                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_inv_items)."'");

	                			//Unit and Amount Show Here
	                			$transaction_unit = $row22->transaction_unit;
		                    	$main_unit = $row22->main_unit;
		                    	$alt_unit = $row22->alt_unit;
		                    	$per_unit = $row22->per_unit;
		                    	if($transaction_unit == $main_unit){
		                    		$qty = $row22->qty; 
		                    	}else{
		                    		$qty = $row22->alt_qty; 
		                    	}
		                    	if($per_unit == $main_unit){ 
		                    		$rate_per_main_unit = $row22->rate_per_main_unit;
		                    	}else{ 
		                    		$rate_per_main_unit = $row22->rate_per_alt_unit;
		                    	}
		                    	if($row22->id_mst_charges_purchase_local != 0){

		                    	$local  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_purchase_local)."'");
		                    	}
		                    	if($row22->id_mst_charges_purchase_interstate != 0){

		                    	$interstate  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_charges_purchase_interstate)."'");
		                    	}
		                    	//Store Name Get
		                    	$store  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row22->id_mst_attributes_store)."'");

		                    	//GRN DETAILS 
								$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$row22->id_inv_items."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$row22->id_inv_items."'");
								//Physical Stock
								$physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$row22->id_inv_items."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$row22->id_inv_items."'");

								$stock_in_hand = $grn_qty + $openbal_qty + $physicalstock_qty - $sin_qty;

								//Table Show Here
								if($type == 0){ 
		                   		$table .= ' <tr class="table-row" style="text-align: center;">
			                    		<td>';
			                    		$table .= '<span>'.$i.'</span>';
			                    		$table .= ' 
			                    			<input type="text" class="form-control" id="wmatch" name="wmatch"  value="'.$match.'" style="display: none;">

			                    			<input type="text" class="form-control" id="widstore'.$i.'" name="widstore'.$i.'"  value="'.$row22->id_mst_attributes_store.'" style="display: none;" >
			                    			<input type="text" class="form-control" id="wstorename'.$i.'" name="wstorename'.$i.'"  value="'.$store.'" style="display: none;" >

			                    			<input type="text" class="form-control" id="wid'.$i.'" name="wid'.$i.'"  value="'.$row22->id.'" style="display: none;">

			                    			<input type="text" class="form-control" id="witemid'.$i.'" name="witemid'.$i.'"  value="'.$row22->id_inv_items.'" style="display: none;">

			                    			<input type="text" class="form-control" id="wconver_rate_per_unit'.$i.'" name="wconver_rate_per_unit'.$i.'"  value="'.$row22->conver_rate_per_unit.'" style="display: none;">

			                    			<input type="text" class="form-control" id="wsno" name="wsno"  value="'.$i.'" style="display:none;">
			                    			</td>

			                    		<td>
			                    			<input type="text" class="form-control" id="wdoc_no'.$i.'" name="wdoc_no'.$i.'"  value="'.$row22->id_inv_purch.' " readonly>
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="witem_code'.$i.'" name="witem_code'.$i.'"  value="'.$item_code.'" readonly>
			                    		</td>
			                    		<td>'; 
			                    		$table .= '<span>'.$item_description.'</span>';
			                    		$table .= '<input type="text" class="form-control" id="witem_description'.$i.'" name="witem_description'.$i.'"  value="'.$item_description.' " readonly style="display:none;">
			                    		</td> 
			                    		<td>
			                    			<input type="text" class="form-control" id="wstock_hand'.$i.'" name="wstock_hand'.$i.'"  value="'.$stock_in_hand.'" readonly>
			                    		</td>
			                    		
			                    		<td>
			                    			<input type="text" class="form-control" id="windent_qty'.$i.'" name="windent_qty'.$i.'"  value="'.$qty.'" readonly>
			                    		</td>
			                    		<td> 
			                    		<input type="text" class="form-control" id="wtransaction_unit'.$i.'" name="wtransaction_unit'.$i.'"  value="'.$transaction_unit.' " readonly style="display:none;">

			                    			<input type="text" class="form-control" id="wmain_unit'.$i.'" name="wmain_unit'.$i.'"  value="'.$row22->main_unit.' " > 
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wrate_per_main_unit'.$i.'" name="wrate_per_main_unit'.$i.'"  value="'.$rate_per_main_unit.' " readonly> 
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="wper_unit'.$i.'" name="wper_unit'.$i.'"  value="'.$per_unit.' " readonly>

			                    			<input type="text" class="form-control" id="walt_unit'.$i.'" name="walt_unit'.$i.'"  value="'.$row22->alt_unit.' "  style="display:none;">
			                    		</td>
			                    		<td>
			                    			<input type="text" class="form-control" id="witem_amount'.$i.'" name="witem_amount'.$i.'"  value="'.$row22->item_amount.' "  readonly> 
			                    			<input type="text" class="form-control" id="wdiscount_percent'.$i.'" name="wdiscount_percent'.$i.'"  value="'.$row22->discount_percent.' "  style="display:none;"> 

			                    			<input type="text" class="form-control" id="witem_remarks'.$i.'" name="witem_remarks'.$i.'"  value="'.$row22->item_remarks.' "  style="display:none;"> 

			                    			<input type="text" class="form-control" id="wid_mst_charges_purchase_local'.$i.'" name="wid_mst_charges_purchase_local'.$i.'"  value="'.$row22->id_mst_charges_purchase_local.' "  style="display:none;">

			                    			<input type="text" class="form-control" id="wid_mst_charges_purchase_interstate'.$i.'" name="wid_mst_charges_purchase_interstate'.$i.'"  value="'.$row22->id_mst_charges_purchase_interstate.' "  style="display:none;">
			                    			
			                    			<input type="text" class="form-control" id="wlocal'.$i.'" name="wlocal'.$i.'"  value="'.$local.' "  style="display:none;">
			                    			<input type="text" class="form-control" id="winterstate'.$i.'" name="winterstate'.$i.'"  value="'.$interstate.' "  style="display:none;">  
			                    		</td>
			                    		<td style="display:none;">
			                    			<input type="text" class="form-control" id="wbalance'.$i.'" name="wbalance'.$i.'"  value="'.$qty.' "  readonly> 
			                    		</td>
			                    		<td> 
			                    			 <input type="checkbox" name="wcheckbox'.$i.'" id="wcheckbox'.$i.'"  onclick="checkboxs(this.id);">

			                    			<input type="text" class="form-control" id="wselect'.$i.'" name="wselect'.$i.'"  value="" style="display: none;"> 
			                    			<input type="text" class="form-control" id="wcounts" name="wcounts"  value="'.$numRows.'" style="display: none;">
			                    			<input type="text" class="form-control" id="ot" name="ot"  value="'.$ot_type.'" style="display: none;">
			                    			<input type="text" class="form-control" id="wpop'.$i.'" name="wpop'.$i.'"  value="'.$id.'" style="display: none;">
			                    			<input type="text" class="form-control" id="pono'.$i.'" name="pono'.$i.'"  value="'.$pono.'" style="display: none;">
			                    			<input type="text" class="form-control" id="podate'.$i.'" name="podate'.$i.'"  value="'.$podate.'" style="display: none;">
			                    		</td> 
			                    	</tr>  '; 
				                    	$i++;
				                     }
		                   	}  
 
echo ($table); 
?>
 
</div> 
<script type="text/javascript">
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