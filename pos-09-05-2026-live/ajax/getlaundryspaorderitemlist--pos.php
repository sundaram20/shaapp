<?php 

if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

}

?>





<?php

$id_attribute_table=$_REQUEST['id_attribute_table'];
$UniqueCodeold=$_REQUEST['UniqueCode'];
$discountType=$_REQUEST['discountType'];
$outlet=	$_REQUEST['outlet'];
 $id	=	$_REQUEST['id'];
$itemqty2=$_REQUEST['qty'];
  $sub_total=$_REQUEST['sub_total'];
  
if($itemqty2==''){
	$itemqty='1.00';
}else{
	$itemqty=$itemqty2;
}

			
 
 
  $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$_REQUEST['outlet']."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				 $id_service_charge = $rowOutlet->id_service_charge;
				$taxtype = $rowOutlet->taxtype;
	      
		if($id_service_charge=='0'){
			 $id_sgst = '0';
			 $id_cgst = '0';				
			 $percentage= '0';
		} else{
		   $sqlCharges = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_service_charge."'";
	       $db->query($sqlCharges); 
	       $rowCharges= $db->fetch_object();
	      		 $id_sgst = $rowCharges->id_mst_charges_sgst;
				 $id_cgst = $rowCharges->id_mst_charges_cgst;				
				  $percentage= $rowCharges->percentage;
		}
		  
		if($id_sgst=='0'){
			 $serviceSGST = '0';
		} else{
		   $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_sgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      	 $serviceSGST = $row2->percentage; 
	      	}
		}
		
		if($id_cgst=='0'){
			 $serviceCGST = '0';
		} else{
		    $sql2 = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$id_cgst."'";
	       $db->query($sql2); 
	       while($row2 = $db->fetch_object()){ 
	      		$serviceCGST = $row2->percentage; 
	      	}
		}
						
			
 

if($id_attribute_table){

	
 $sqlOutlet = " SELECT * FROM `".TBL_OUTLETS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$outlet."'";
	       $db->query($sqlOutlet); 
	       $rowOutlet = $db->fetch_object();
	      		$service_charge_apply = $rowOutlet->service_charge_apply;
				$service_charge_per = $rowOutlet->service_charge_per;
				$id_service_charge = $rowOutlet->id_service_charge;
			   $taxtype = $rowOutlet->taxtype;

		   
 $sqlitemnew = "SELECT *  from pos_purch WHERE id='".$_REQUEST['id_posbilling']."' ";
$resitem = mysqli_query($connNew,$sqlitemnew);
		$selectoption1=mysqli_fetch_object($resitem);
		$sc_charges_net_amount = $selectoption1->sc_charges_net_amount;
	if($_REQUEST['discountamount']==''){
	   $_SESSION['discountamount']=$selectoption1->discount_amount_additional;
	}
	
	
	if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge'] != ''){
		$service_charge_amount='0';
		$serviceTotalSGST= '0';
		$serviceTotalCGST= '0';
		$serviceChargeTotal	='0';
		
	}else {	
	 $service_charge_amount	=	(($sub_total*$percentage)/100);
		$serviceTotalSGST= (($service_charge_amount*$serviceSGST)/100);
		$serviceTotalCGST= (($service_charge_amount*$serviceCGST)/100);
		$serviceChargeTotal=$service_charge_amount-($serviceTotalSGST+$serviceTotalCGST);
	}
	//echo $serviceTotalSGST;
	//echo $serviceTotalCGST;
	//echo $serviceChargeTotal;
			

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
		  
            <tbody id="ViewOrderItemListtest">
           
            </tbody>
		
            <tfoot>
            <tr> 
            <td colspan="12" style="text-align: left;">
            <input  type="button" class="btn btn-sm btn-block" id="addrow1" name="addrow1" onclick="AddMoreItem();"  value="Add More" />
            <input  type="button" class="btn btn-sm btn-block" id="addrow4" value="Add More" style="display: none;" />  
            </td> 
            </tr>
            <tr>
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
        <input type="text" class="form-control" class="sub_total_items" placeholder="Sub Total" id="sub_total_items" name="sub_total_items" value="<?php echo $sub_total_items; ?>" readonly>
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
        <input type="text" class="form-control" placeholder="Total" id="totalvalue" name="net_amount_items" value="<?php echo $selectoption1->net_amount_items; ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Service Charges 
     <?php 
	// echo $_REQUEST['revServiceCharge'];
	if($selectoption1->sc_reverse==0 && $_REQUEST['revServiceCharge']!=1){
			?>
	 <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
	<?php
	  }else{
	  
	  if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge']!=''){?>
     <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
      <?php }else { 
	  
	  ?>
        <input type="checkbox" class="minimal-red" value="1" <?php echo $sc_reverse0; ?> name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" checked="checked"></label>
     <?php } } ?>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
       <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
       <input type="text" class="form-control" placeholder="Service Charges" id="service_charge_amount" name="service_charge_amount" value="<?php if($service_charge_amount) echo $service_charge_amount;else echo '0';?>" onKeyup="additionalDiscount(3,this.value);" style="text-align:right;"> 
	  
		
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
		<input type="hidden" class="form-control" placeholder="SGST" id="TotalTax_sgst1" name="sgst_net_amount1" value="<?php echo $selectoption1->sgst_net_amount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="CGST" id="TotalTax_cgst" name="cgst_net_amount" value="<?php echo $selectoption1->cgst_net_amount; ?>" readonly style="text-align:right;">
		<input type="hidden" class="form-control" placeholder="CGST" id="TotalTax_cgst1" name="cgst_net_amount1" value="<?php echo $selectoption1->cgst_net_amount; ?>" readonly style="text-align:right;">
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
		
		<!-- <input type="hidden" class="form-control" placeholder="Discount Amount" id="adddiscount" name="adddiscount" value="<?php //echo $selectoption1->disc_amount_additional1; ?>" style="text-align:right;"> -->
		
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
        <input type="text" class="form-control" placeholder="Net Amount" id="netfinal" name="net_amount" value="<?php echo $selectoption1->grant_total_amount; ?>" readonly style="text-align:right;">
		<input type="hidden" class="form-control" placeholder="Net Amount" id="netfinalint" name="net_amount1" value="<?php echo $selectoption1->net_amount; ?>" readonly style="text-align:right;">
		
		<input type="hidden" class="form-control" id="serviceTotalSGST" name="serviceTotalSGST" value="<?php echo $serviceTotalSGST; ?>" readonly style="text-align:right;">
		<input type="hidden" class="form-control" id="serviceTotalCGST" name="serviceTotalCGST" value="<?php echo $serviceTotalCGST; ?>" readonly style="text-align:right;">
		<input type="hidden" class="form-control" id="serviceChargeTotal" name="serviceChargeTotal" value="<?php echo $serviceChargeTotal; ?>" readonly style="text-align:right;">
		
		<input type="hidden" class="form-control" placeholder="Net Amount" id="sc_sgst1" name="sc_sgst" value="" readonly style="text-align:right;">
		
		<input type="hidden" class="form-control" id="sc_cgst1" name="sc_cgst" value="" readonly style="text-align:right;">
		
      </div>
    </div>
  </div>
</div>
<?php }

 ?>
 

<script>
<?php  if($selectoption1->sc_reverse =='1'){ ?>
	$('document').ready(function(){
		$('#revServiceCharge').click();
	});
	
<?php } ?>
</script>
 
 <script>
 function reverceServiceCharge(){
	// alert();
		var revServiceCharge = $("#revServiceCharge").val();
		//alert(revServiceCharge);
			if(revServiceCharge == 0) {
				//alert("Check box in Checked"); 
				$("#revServiceCharge").val('1');
				loadkotOutlet();
			} else { 
			  // alert("Check box is Unchecked"); 
				$("#revServiceCharge").val('0');
				loadkotOutlet();
			} 
 }
 


function loadkotOutlet(){
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	//alert(id_attribute_table);
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();	
	var id_item_type =$("#id_item_type").val();
	//alert(outlet);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillNo.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&id_item_type='+id_item_type+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date, 
		success: function (result) {
			$( "#ViewKotSelectedTable" ).html(result);
			$( "#revServiceCharge" ).val(revServiceCharge);	
			$("#counter1").val('0');		
			//OrderItemList(id_attribute_table);
			id_get(id_attribute_table);
	 	}
	});
	}
	
	 
 function id_get(opts){
	//alert(value);
	//alert(opts);
	
	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var outleType = $("#outleType").val();
	var outlet = $("#outlet").val();	
	var id_posbilling = $("#id_posbilling").val();
	var sub_total_items = $("#sub_total_items").val();

	       // var total1 = document.getElementById("netfinalint").value;
			//var total = document.getElementById("netfinal").value;
			//var sgst = document.getElementById("TotalTax_sgst").value;
			//var cgst = document.getElementById("TotalTax_cgst").value;
			//var services = document.getElementById("serviceChargeTotal").value;
		//alert(sgst);	
		
				
	$.ajax({
		type: "POST",
		//dataType: 'JSON',
		url: 'ajaxGetLaundrySpaOrderItemList2.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outleType='+outleType+'&sub_total='+sub_total_items+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			//alert(revServiceCharge);
			$( "#revServiceCharge" ).val(revServiceCharge);
		//	alert(sgst);
       data = JSON.parse(result);
	   
       $( "#service_charge_amount").val(data.service_charge_amount);			
       $( "#serviceChargeTotal").val(data.serviceChargeTotal);
       $( "#sc_sgst1").val(data.serviceTotalSGST);
       $( "#sc_cgst1").val(data.serviceTotalCGST);
	   
       //$( "#netfinal").val(data.netamount1);			
       // $( "#TotalTax_sgst").val(data.TotalTax_sgst);			
       // $( "#TotalTax_cgst").val(data.TotalTax_cgst);			
       // $( "#round_off_amount").val(data.round_off_amount);		
        
		
		var sgst = document.getElementById("TotalTax_sgst1").value;
		var cgst = document.getElementById("TotalTax_cgst1").value;
		var total1 = document.getElementById("netfinalint").value;
		var total = document.getElementById("totalvalue").value;
		var adddiscount = document.getElementById("additional_discount_amount").value;
		var serviceTotalSGST1 = data.serviceTotalSGST;
		var serviceTotalCGST1 = data.serviceTotalCGST;
		var serviceChargeTotal = data.serviceChargeTotal;
		
		//alert(sgst);
		
		var serviceTotalSGST = (parseFloat(sgst))+(parseFloat(serviceTotalSGST1));
		var serviceTotalCGST = (parseFloat(cgst))+(parseFloat(serviceTotalCGST1));
		var netamount = (parseFloat(total))+(parseFloat(serviceChargeTotal))+(parseFloat(serviceTotalSGST))+(parseFloat(serviceTotalCGST))+(parseFloat(serviceTotalSGST1))+(parseFloat(serviceTotalCGST1))-parseFloat(adddiscount);
		var netamount1 = Math.round(netamount);
		var RoundOfAmount = (parseFloat(netamount1))-(parseFloat(netamount));
		
		var RoundAmonut = RoundOfAmount.toFixed(2);
				//alert(RoundAmonut);
		$( "#round_off_amount").val(RoundAmonut);
		$( "#netfinal").val(netamount1);
		//$( "#netfinalint").val(netamount);
		
	    $( "#TotalTax_sgst").val(serviceTotalSGST);
		$( "#TotalTax_cgst").val(serviceTotalCGST);
		//alert(serviceTotalSGST1);
	 	}
	});
}


 </script>
