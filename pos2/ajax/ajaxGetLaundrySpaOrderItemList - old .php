<?php 

//if($_REQUEST['DbConnect']==1){

include_once("../../config/auto_loader.php");

//}
/*echo '<pre>';
print_r($_REQUEST);
print_r($_SESSION);
echo '</pre>';*/
?>
<script>

$('#discount|'+<?php echo $_REQUEST['UniqueCode']; ?>).focus();

</script>
<script type="text/javascript">

document.getElementById('string').focus();

</script>
<style>
.discount|<?php echo $_REQUEST['UniqueCode'];
?> {
 autofocus:"autofocus";
}
</style>
<?php



 $id_attribute_table=$_REQUEST['id_attribute_table'];

 $UniqueCodeold=$_REQUEST['UniqueCode'];

  $discountType=$_REQUEST['discountType'];

  

  if($discountType==2){	// Additonal Discount

  $_SESSION['discountamount']=$_REQUEST['discountamount'];

  }

   if($discountType==3){	// Additonal Charges

  $_SESSION['AdditionalChargeamount']=$_REQUEST['discountamount'];

  }

if($id_attribute_table){

	//BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);

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
            <input  type="button" class="btn btn-sm btn-block" id="addrow1" onClick="AddMoreItem();" value="Add More" />
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
        <?php if($row->id == ''){

							   		$sub_total_items = 0;

							   	}else{

							   		$sub_total_items = $row->sub_total_items;

							   	}

							   	?>
        <input type="text" class="form-control" placeholder="Sub Total" id="sub_total_items" name="sub_total_items" value="<?php echo stripslashes($SubTotalAmount); ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Discount</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-diamond"></i> </div>
        <input type="text" class="form-control" placeholder="Discount" id="total_discount_amount" name="total_discount_amount" value="<?php echo stripslashes($DiscountTotalAmount); ?>" readonly>
      </div>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Total</label>
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-asterisk"></i> </div>
        <input type="text" class="form-control" placeholder="Total" id="net_amount_items" name="net_amount_items" value="<?php echo stripslashes($TotalAmountFinal); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
  <div class="row">
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2"></div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <label for="name">Service Charges 
      <?php if($_REQUEST['revServiceCharge']==0 && $_REQUEST['revServiceCharge']!=''){?>
      <input type="checkbox" class="minimal-red" value="0" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" ></label>
      <?php }else{ ?>
        <input type="checkbox" class="minimal-red" value="1" name="revServiceCharge" id="revServiceCharge" onclick="reverceServiceCharge()" checked="checked"></label>
     <?php } ?>
    </div>
    <div class="form-group col-xs-12 col-md-3 col-sm-2">
      <div class="input-group">
        <div class="input-group-addon"> <i class="fa fa-hashtag"></i> </div>
        <input type="text" class="form-control" placeholder="Service Charges" id="service_charge_amount" name="service_charge_amount" value="<?php if($service_charge_amount) echo $service_charge_amount;else echo '0';?>" onChange="additionalDiscount(3,this.value);" style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="SGST" id="sgst_net_amount" name="sgst_net_amount" value="<?php echo stripslashes(round($TotalTax_sgst,2)); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="CGST" id="cgst_net_amount" name="cgst_net_amount" value="<?php if($_POST) echo stripslashes(round($TotalTax_cgst,2)); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="IGST" id="igst_net_amount" name="igst_net_amount" value="<?php echo stripslashes(round($TotalTax_igst,2)); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="CESS" id="cess_net_amount" name="cess_net_amount" value="<?php echo stripslashes(round($TotalTax_cess,2)); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="VAT" id="vat_net_amount" name="vat_net_amount" value="<?php echo round($TotalTax_vat,2); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="surcharge" id="surcharge_net_amount" name="surcharge_net_amount" value="<?php echo stripslashes(round($TotalTax_surcharge,2)); ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Discount Amount" id="additional_discount_amount" name="additional_discount_amount" value="<?php if($_SESSION['discountamount']) echo $_SESSION['discountamount'];else echo '';?>" onChange="additionalDiscount(2,this.value);" style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Round Amount" id="round_off_amount" name="round_off_amount" value="<?php echo $RoundOfAmount; ?>" readonly style="text-align:right;">
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
        <input type="text" class="form-control" placeholder="Net Amount" id="net_amount" name="net_amount" value="<?php echo stripslashes(round($NetAmount,0)); ?>" readonly style="text-align:right;">
      </div>
    </div>
  </div>
</div>
<?php 

 

 } ?>
