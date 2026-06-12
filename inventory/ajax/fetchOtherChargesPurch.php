<?php
	include_once("../../config/auto_loader.php");
	
	 $eId = $_POST["eid"];
	 $id_inv_poo = $_POST["id_inv_poo"];
	 $id_inv_po = $_POST["id_inv_po"];
	
	if($eId==''){
		 $sqll = "SELECT * FROM inv_purch_details WHERE  bal_qty>0 AND id_inv_purch IN (".$_REQUEST['id_inv_po'].") ";
	}else{
		 $sqll = "SELECT * FROM inv_purch_details WHERE id_inv_purch IN (".$_REQUEST['id_inv_po'].") ";
	}
	
	// echo $sql1;
	 
	$ress = mysqli_query($connNew,$sqll);
	//echo $num = mysqli_num_rows($ress);
	while($roww = mysqli_fetch_object($ress)){
		$newid .= $roww->id_inv_purch.',';
	}
 $final = rtrim($newid,',');
 
 
 if($id_inv_poo != ''){ 
		$ids = $_POST["id_inv_poo"].','.$_POST["id_inv_po"];
	}
$finall = rtrim($ids,',');
	 
 
 

	if($_REQUEST['id_inv_po']!=''){
		
		if($id_inv_poo != '' && $id_inv_poo != 'undefined'){
			$sql = "SELECT * FROM ".TBL_INV_OTHERS_CHARGES_PURCH." WHERE id_inv_purch IN (".$finall.") ";
		}else{
			$sql = "SELECT * FROM ".TBL_INV_OTHERS_CHARGES_PURCH." WHERE id_inv_purch IN (".$final.") ";
		}
		
		//echo $sql;
		
		$res = mysqli_query($connNew,$sql);
		$i='';
		$returnData='';
		$returnArr=array();
		$returnArr['discount_total']=0;
		
		while($row = mysqli_fetch_object($res)){
			
		//$sqll = "SELECT * FROM inv_purch_details WHERE  bal_qty>0 AND id_inv_purch IN (".$row->id_inv_purch.") ";
		//$ress = mysqli_query($connNew,$sqll);
		//echo $num = mysqli_num_rows($ress);
			

			if($row->type=='1'){
				$returnData.='<tr>';		
				$returnData.= '<td style="display:none"><select class="form-control select3" id="type' .$i.'" name="type'.$i.'" onchange="type_funt(this.id)">
					<option value="">Select Charges</option>
					<option value="1" selected="selected">OTHERS</option>
					<option value="2" >DISCOUNT</option>
					</select></td>';


       			$returnData.= '<td>
        				<div id="otherss'.$i.'"name="otherss'.$i.'" >
        				<select  name="id_mst_charges_others'.$i.'" id="id_mst_charges_others'.$i.'" class="form-control select3" style="width:100%;" onchange="charges_others(this.id)">';

        		//$sqlCharge = "SELECT * FROM mst_charges where id_shop='".$_SESSION['shop']."' AND charges_account=7 ";
        		$sqlCharge = "SELECT * FROM mst_charges where id_shop='".$_SESSION['shop']."' AND charges_account=4 ";

				$resCharge = mysqli_query($connNew,$sqlCharge);
				$returnData.='<option value="">Select Charge</option>';

				while($rowCharge = mysqli_fetch_object($resCharge)){

					if($rowCharge->id==$row->id_mst_charges_others)
						$selected="selected='selected'";
					else
						$selected="";

					$returnData.='<option '.$selected.' value="'.$rowCharge->id.'">'.$rowCharge->name.'</option>';
				}

				$returnData.='</select>';
	                              

				$returnData.= '<td><input onkeyup="subtotal_calc(this.id)" type="text"  autocomplete="off" placeholder="Percentage" class="form-control" name="others_charges_percent'.$i.'" value="'.$row->otherChargesPercent.'" id="others_charges_percent'.$i.'" style="display:none;" /></td>'; 

        		$returnData.= '<td><input onkeyup="charges_amount_calc(this.id);" onclick="charges_amount_calc(this.id);" type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="others_charges_amount'.$i.'" value="'.$row->others_charges_amount.'" id="others_charges_amount'.$i.'" /></td>';  

				$returnData.= '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="Total" class="form-control" name="total_amount_others'.$i.'" value="'.$row->total_amount_others.'" id="total_amount_others'.$i.'" readonly/></td>'; 



        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_sgst_amount'.$i.'" id="others_charges_sgst_amount'.$i.'" placeholder="SGST Amount"  class="form-control" value="'.$row->others_charges_sgst_amount.'"  readonly/></td>';

        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_cgst_amount'.$i.'" id="others_charges_cgst_amount'.$i.'" value="'.$row->others_charges_cgst_amount.'" placeholder="CGST Amount"  class="form-control" readonly /></td>';

        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_igst_amount'.$i.'" id="others_charges_igst_amount'.$i.'" value="'.$row->others_charges_igst_amount.'" placeholder="IGST Amount"  class="form-control" readonly/></td>';
 

        		$returnData.= '<td  style="display:none;"><div id="chargestaxconfig"><!-- SGST --><input type="text"  autocomplete="off"  name="others_charges_sgst_percent'.$i.'" id="others_charges_sgst_percent'.$i.'" value="'.$row->others_charges_sgst_percent.'" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_sgst_others'.$i.'" value="'.$row->id_mst_charges_sgst_others.'" id="id_mst_charges_sgst_others'.$i.'" placeholder="SGST"  class="form-control" /><!-- CGST --><input type="text"  autocomplete="off" value="'.$row->others_charges_cgst_percent.'"  name="others_charges_cgst_percent'.$i.'" id="others_charges_cgst_percent'.$i.'" placeholder="CGST"  class="form-control" /><input type="text" value="'.$row->id_mst_charges_cgst_others.'"  autocomplete="off"  name="id_mst_charges_cgst_others'.$i.'" id="id_mst_charges_cgst_others'.$i.'" placeholder="CGST"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off" value="'.$row->others_charges_igst_percent.'"  name="others_charges_igst_percent'.$i.'" id="others_charges_igst_percent'.$i.'" placeholder="IGST"  class="form-control" /><input type="text" value="'.$row->id_mst_charges_igst_others.'"  autocomplete="off"  name="id_mst_charges_igst_others'.$i.'" id="id_mst_charges_igst_others'.$i.'" placeholder="IGST"  class="form-control" /></div></td>';  

        		$returnData.= '<td  style="display:none;"><div id="otherschargestaxconfig" id="otherschargestaxconfig"><!-- Discount --><input type="text"  autocomplete="off"  name="others_discount_percent'.$i.'" value="'.$row->others_discount_percent.'" id="others_discount_percent'.$i.'" placeholder="Discount"  class="form-control" /><input type="text"  autocomplete="off" value="'.$row->others_discount_amount.'"  name="others_discount_amount'.$i.'" id="others_discount_amount'.$i.'" placeholder="Amount"  class="form-control"  /></div></td>';        
	 		  	if($i>=1){
					$returnData.= '<td><img src="images/delete.gif"  class="ibtnDel2" id="deletes'.$i.'" name="deletes'.$i.'" style="cursor:pointer;" title="Delete"/></td>';
	 		  	}
				
	$returnArr['others_total']+=$row->others_charges_amount;			

 		  		$returnData.='</tr>';

			}
			else if($row->type=='2'){
				$returnData.='<tr>';		
				$returnData.= '<td style="display:none;"><select class="form-control select3" id="type' .$i.'" name="type'.$i.'" onchange="type_funt(this.id)">
					<option value="">Select Charges</option>
					<option value="1" >OTHERS</option>
					<option value="2" selected="selected">DISCOUNT</option>
					</select></td>';


       			$returnData.= '<td>
        				<div  id="otherss'.$i.'"name="otherss'.$i.'" >
        				<select  name="id_mst_charges_others'.$i.'" id="id_mst_charges_others'.$i.'" class="form-control select3" style="width:100%;" onchange="charges_others(this.id)">';

        		$sqlCharge = "SELECT * FROM mst_charges where id_shop='".$_SESSION['shop']."' AND charges_account=6 ";

				$resCharge = mysqli_query($connNew,$sqlCharge);
				$returnData.='<option value="">Select Charge</option>';

				while($rowCharge = mysqli_fetch_object($resCharge)){

					if($rowCharge->id==$row->id_mst_charges_others)
						$selected="selected='selected'";
					else
						$selected="";

					$returnData.='<option '.$selected.' value="'.$rowCharge->id.'">'.$rowCharge->name.'</option>';
				}

				$returnData.='</select></div></td>';
	                              

				$returnData.= '<td><input onkeyup="subtotal_calc(this.id)" type="hidden"  autocomplete="off" placeholder="Percentage" class="form-control" name="others_charges_percent'.$i.'" value="'.$row->otherChargesPercent.'" id="others_charges_percent'.$i.'" /></td>'; 

        		$returnData.= '<td><input onkeyup="charges_amount_calc(this.id);" onclick="charges_amount_calc(this.id);" type="text"  autocomplete="off" placeholder="Amount" class="form-control" name="others_charges_amount'.$i.'" value="'.$row->others_charges_amount.'" id="others_charges_amount'.$i.'" /></td>';  

				$returnData.= '<td style="display:none;"><input type="text"  autocomplete="off" placeholder="Total" class="form-control" name="total_amount_others'.$i.'" value="'.$row->total_amount_others.'" id="total_amount_others'.$i.'" readonly/></td>'; 



        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_sgst_amount'.$i.'" id="others_charges_sgst_amount'.$i.'" placeholder="SGST Amount"  class="form-control" value="'.$row->others_charges_sgst_amount.'"  readonly/></td>';

        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_cgst_amount'.$i.'" id="others_charges_cgst_amount'.$i.'" value="'.$row->others_charges_cgst_amount.'" placeholder="CGST Amount"  class="form-control" readonly /></td>';

        		$returnData.= '<td><input type="text"  autocomplete="off"  name="others_charges_igst_amount'.$i.'" id="others_charges_igst_amount'.$i.'" value="'.$row->others_charges_igst_amount.'" placeholder="IGST Amount"  class="form-control" readonly/></td>';
 

        		$returnData.= '<td  style="display:none;"><div id="chargestaxconfig" ><!-- SGST --><input type="text"  autocomplete="off"  name="others_charges_sgst_percent'.$i.'" id="others_charges_sgst_percent'.$i.'" value="'.$row->others_charges_sgst_percent.'" placeholder="SGST"  class="form-control" /><input type="text"  autocomplete="off"  name="id_mst_charges_sgst_others'.$i.'" value="'.$row->id_mst_charges_sgst_others.'" id="id_mst_charges_sgst_others'.$i.'" placeholder="SGST"  class="form-control" /><!-- CGST --><input type="text"  autocomplete="off" value="'.$row->others_charges_cgst_percent.'"  name="others_charges_cgst_percent'.$i.'" id="others_charges_cgst_percent'.$i.'" placeholder="CGST"  class="form-control" /><input type="text" value="'.$row->id_mst_charges_cgst_others.'"  autocomplete="off"  name="id_mst_charges_cgst_others'.$i.'" id="id_mst_charges_cgst_others'.$i.'" placeholder="CGST"  class="form-control" /><!-- IGST --><input type="text"  autocomplete="off" value="'.$row->others_charges_igst_percent.'"  name="others_charges_igst_percent'.$i.'" id="others_charges_igst_percent'.$i.'" placeholder="IGST"  class="form-control" /><input type="text" value="'.$row->id_mst_charges_igst_others.'"  autocomplete="off"  name="id_mst_charges_igst_others'.$i.'" id="id_mst_charges_igst_others'.$i.'" placeholder="IGST"  class="form-control" /></div></td>';  

        		$returnData.= '<td  style="display:none;"><div id="otherschargestaxconfig" id="otherschargestaxconfig"><!-- Discount --><input type="text"  autocomplete="off"  name="others_discount_percent'.$i.'" value="'.$row->others_discount_percent.'" id="others_discount_percent'.$i.'" placeholder="Discount"  class="form-control" /><input type="text"  autocomplete="off" value="'.$row->others_discount_amount.'"  name="others_discount_amount'.$i.'" id="others_discount_amount'.$i.'" placeholder="Amount"  class="form-control"  /></div></td>';        
	 		  	if($i>=1){
					$returnData.= '<td><img src="images/delete.gif"  class="ibtnDel2" id="deletes'.$i.'" name="deletes'.$i.'" style="cursor:pointer;" title="Delete"/></td>';
	 		  	}


$returnArr['discount_total'] += $row->others_charges_amount;

 		  		$returnData.='</tr>';
			}
			
			
			//$returnArr['discount_total'] += $row->others_charges_amount;
			
			
			$i++;

		}
		$returnArr['count']=($i-1);
		$returnArr['data']=$returnData;
		echo json_encode($returnArr);

	}