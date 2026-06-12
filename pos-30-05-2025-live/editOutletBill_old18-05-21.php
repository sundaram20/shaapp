<?php
 include_once("../config/auto_loader.php");
//include_once("include/pos_function.php");

unset($_SESSION['LINELEVEL']);
unset($_SESSION['discountamount']);
unset($_SESSION['AdditionalChargeamount']);
unset($_SESSION['outdetailitemID']);
//print_r($_REQUEST);
//print_r($_SESSION);
//echo encryptor(decrypt,$_REQUEST['updateid']);exit;

if($_REQUEST['updateid']!=''){

	//$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND  id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']=$ResultupdateRow->id_attribute_table_group;//117;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	
	//$_REQUEST['id_attribute_table']=$ResultupdateRow->id_attribute_table;
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']=$ResultupdateRow->id_mst_outlet;
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	
	
$id_doc_type_configuration	=	$ResultupdateRow->id_doc_type_configuration;
$po_no		= $ResultupdateRow->doc_no;
$mdoc_no	  = $ResultupdateRow->mdoc_no;	

}else{
		
	$_REQUEST['id_posbilling']='';
}

$updateSql1 = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE  id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");
$row1 = mysqli_fetch_object($updateSql1);


$counterid=	$_REQUEST['counter1'];
 $counterid=$_POST['counter1'];
if($counterid=='')
{
	$counteridnw='1';
}
else
{
	$counteridnw=$counterid;
}
 $counteridnw;
 
 
if($_SESSION['id_document']==25){
	$outleType	=	2;
	$MenuType	=	172;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Laundry" ');
}
if($_SESSION['id_document']==26){
	$outleType	=	3;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Spa and Health Club" ');
}

if($_SESSION['id_document']==29){
	$outleType	=	4;
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Others" ');
}

/*if($_SESSION['id_document']==25){
	$outleType	=	0;
}*/

 $doc_type = $_SESSION['id_document'];										
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">

	<?php echo $_SESSION['id_document']; ?>

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
	
	
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Details</th>   
					</tr>
				</thead>
				
				<tbody id="roombutton">
					
				</tbody>
			</table>
            </div>
			
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
    <!-- Main content -->
    <section class="content">
	
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
			<div class="nav-tabs-custom">
		 
			<div class="box-header with-border">
               <h3 class="box-title"><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  
			  <a><?php echo selectColumn(TBL_INV_INDENT,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form" action="savePrintBillothers.php"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
                <input type="hidden" value="<?php echo $doc_type;?>" name="doc_type" id="doc_type" />
               
				<div class="form-group has-error" align="center">
					<?php if($_SESSION['errorMsg']){?>
					 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
					<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
					<?php unset($_SESSION['successMsg']);}?>
				</div> 
					
              <div class="box-body">
              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              		   <h5 style="padding: 5px;">Billing</h5>
              		</div> 
              		<hr>
	              	<div class="row">	
        <div class="form-group col-xs-12 col-md-2 col-sm-2" >
        <input type="hidden" value="<?php echo $id_item_type;?>" name="id_item_type" id="id_item_type" />
		<input type="hidden" name="outleType" id="outleType" value="<?php echo $outleType; ?>" >
         <input type="hidden" value="<?php echo $_REQUEST['id_posbilling'];?>" name="id_posbilling" id="id_posbilling" />
				
 <label for="outlet">Outlet <font color="#FF0000">*</font> </label>
         
               				
        <?php $categoryDropDown = '<select class="form-control select2" name="outlet" id="outlet" data-parsley-required data-parsley-errors-container="#outletError" onChange="loadkotOutlet(this);">
			<option value="">Select Outlet</option>';
			  $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='".$outleType."' ",'');
			  if($db->num_rows2($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){
				if($_REQUEST['outlet'] == $resultCat->id){
					$selected = 'selected="selected"';
				}elseif($row1->id_mst_outlet == $resultCat->id){
					$selected = 'selected="selected"';
				}else{
					$selected = '';
				}
				$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
			}
		  }
			echo $categoryDropDown .= '</select>';
		?>
              <span id="outletError"></span>
              </div>
                     
				 <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control dates" placeholder="eeEnter PO Date" id="po_date" name="po_date" value="<?php if($_POST) echo $_POST['po_date'];elseif($row1->doc_date!='') echo date('d-m-Y',strtotime($row1->doc_date));else echo date('d-m-Y');?>" onChange="hideandshow2();"  onclick="hideandshow2();" <?php echo $readonly; ?>>
		                   <input style="display: none;"  type="text" class="form-control dates" placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php if($_POST) echo $_POST['po_date'];elseif($row1->doc_date!='') echo date('d-m-Y',strtotime($row1->doc_date));else echo date('d-m-Y');?>" >
		                  </div> 
                          
	              		</div> 
                        		<?php /*?><div class="col-md-4">
             
	              			<label for="name">Remarks </label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  >
							</div>
			
                      </div><?php */?> 
                      
				<div id="ViewKotSelectedTable">
		        </div>
	              		
		         <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div> <!-- ///onchange="changeFunc()" -->
								 <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_steward" id="id_attribute_steward" data-parsley-required style="width:100%"> 
									<option value="">Select Steward</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='steward' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['id_attribute_steward'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row1->id_attribute_steward == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
								
                            <!--    <input type="hidden" class="form-control" placeholder="Enter Steward Name" id="id_attribute_steward" name="id_attribute_steward" value="<?php echo $id_attribute_steward;?>"  data-parsley-required>
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="StewardName" name="StewardName" value="<?php echo $steward_name;?>"  data-parsley-required> -->
							</div>
	                  </div>  
                            
                    <div class="col-md-4">
             
	              			<label for="name">Remarks </label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row1->remarks);?>"  >
							</div>
			
                      </div>  
                             
		                <?php if($row->id !='' && $row->base_currency_code != $row->transaction_currency_code){?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: block;
		                		}
		                		#base_currency{
		                			display: block;
		                		}
		                		#trans_currency{
		                			display: block;
		                		}
		                	</style>
		                <?php  } else{ ?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: none;
		                		}
		                		#base_currency{
		                			display: none;
		                		}
		                		#trans_currency{
		                			display: none;
		                		}
		                	</style>
		                <?php } ?>
		                
		                <?php if($row->id !=''){ 
		                	$transaction_currency_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->transaction_currency_code)."'");
		                }else{
		                	$transaction_currency_code  = '';
		                }?>
                 
 						<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>
		    </div>        
 
		        </div>
		        <hr>
		         <div class="box-body">
              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Item Bill Details</h5>
              		</div>  
                     <div id="ViewOrderItemList">
					    
					</div>
				<div class="row">
		            	
		            	<table id="myTable1" class=" table order-list1">
				            
				            <tbody>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select
				           	$sql2 = "  SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_purch'.''.$i] = $rowsID->id_inv_purch; 
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po; 
							 		 $array['id_inv_po_details'.''.$i] = $rowsID->id_inv_po_details;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items; 
							 		 $array['transaction_unit'.''.$i] = $rowsID->transaction_unit; 
							 		 $array['qty'.''.$i] = $rowsID->qty; 
							 		 $array['conver_rate_per_unit'.''.$i] = $rowsID->conver_rate_per_unit;
							 		 $array['id_mst_charges_purchase_interstate'.''.$i] = $rowsID->id_mst_charges_purchase_interstate;
							 		 $array['id_mst_charges_purchase_local'.''.$i] = $rowsID->id_mst_charges_purchase_local;
							 		 $array['rate_per_main_unit'.''.$i] = $rowsID->rate_per_main_unit;
							 		 $array['discount_percent'.''.$i] = $rowsID->discount_percent;
							 		 $array['discount_amount'.''.$i] = $rowsID->discount_amount;
							 		 $array['item_amount_before_discount'.''.$i] = $rowsID->item_amount_before_discount; 
							 		 $array['item_amount'.''.$i] = $rowsID->item_amount; 
							 		 $array['id_mst_charges_sgst'.''.$i] = $rowsID->id_mst_charges_sgst;
							 		 $array['item_sgst_percent'.''.$i] = $rowsID->item_sgst_percent;
							 		 $array['item_sgst_amount'.''.$i] = $rowsID->item_sgst_amount;
							 		 $array['id_mst_charges_cgst'.''.$i] = $rowsID->id_mst_charges_cgst;
							 		 $array['item_cgst_percent'.''.$i] = $rowsID->item_cgst_percent;
							 		 $array['item_cgst_amount'.''.$i] = $rowsID->item_cgst_amount;
							 		 $array['id_mst_charges_igst'.''.$i] = $rowsID->id_mst_charges_igst;
							 		 $array['item_igst_percent'.''.$i] = $rowsID->item_igst_percent;
							 		 $array['item_igst_amount'.''.$i] = $rowsID->item_igst_amount;
							 		 $array['item_remarks'.''.$i] = $rowsID->item_remarks;
							 		 $array['main_unit'.''.$i] = $rowsID->main_unit;
							 		 $array['alt_unit'.''.$i] = $rowsID->alt_unit;
							 		 $array['per_unit'.''.$i] = $rowsID->per_unit; 
							 		 $array['alt_qty'.''.$i] = $rowsID->alt_qty; 
							 		 $array['rate_per_alt_unit'.''.$i] = $rowsID->rate_per_alt_unit; 
							 		 $array['id_mst_attributes_store'.''.$i] = $rowsID->id_mst_attributes_store; 
							 		 
							 		 
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 } 
				            	?>
				            	
				                <?php if($row->id == ''){ $ledger_id = ''; ?>
					                <style type="text/css">
					                	#locals{
					                		display: none;
					                	}
					                	#interstates{
					                		display: none;
					                	}
					                	#localss{
					                		display: none;
					                	}
					                	#interstatess{
					                		display: none;
					                	}
					                </style>
					                <?php } elseif($array['id_mst_charges_purchase_local'.''.$j] != 0) {
					                 $ledger_id = 1; ?>
					                	<style type="text/css">
					                	#locals<?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: none;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: block;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: none;
					                	}
					                	</style>
					                <?php } elseif($array['id_mst_charges_purchase_interstate'.''.$j] != 0) { $ledger_id = 2; ?>
					                	<style type="text/css"> 
					                	#locals<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstates<?php echo $k;?>{
					                		display: block;
					                	}
					                	#localss<?php echo $k;?>{
					                		display: none;
					                	}
					                	#interstatess<?php echo $k;?>{
					                		display: block;
					                	}
					                	</style>
					                <?php } ?>
					                <input id="ledger_id" name="ledger_id" value="<?php if($_POST) echo $ledger_id;else echo stripslashes($ledger_id); ?>" hidden="">

				            	<?php }  ?>
                                  
				            	<input type="text" name="counter1" id="counter1" value="<?php echo $counts=0; ?>" hidden=""> 
				            </tbody>
				          
				        </table>
				        
		            </div> 
		        	
		        	<!-- Total Amount Section -->
		            
		        </div>         
				
		        </div>
		        <hr> 
 
				           
              </div>
         	</div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save Print':'Edit')?>' class="btn btn-success" name="Save"  >
				&nbsp;&nbsp;&nbsp;&nbsp; 
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("manageOutletBill.php"); '>
			   <input type='button' value='Audit Trail' class="btn btn-success"  onclick="audittrial(this.value);" style="float:right">
				 
				 
			   <?php if($row->id !=''){?>
			   	<center>
		          <a href="editPO.php" type="button" class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Another Purchase Order</i></a> 
		          <?php                   		 
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 
	                  		$custom_print_file = $row1->custom_print_file;	                  		 
		                  	if($custom_print_file !=''){
		                  		$print = $custom_print_file;
		                  	}else{
		                  		$print = 'printPO.php';
		                  	}
	                  	} 
	                  		                  	
                  	?>
		          <a href="<?php echo $print; ?>?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>" target="_blank" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"> Print</i></a>    
	        	</center>
			   <?php } ?>
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	
<?php 
 	$array_s =  explode(",", $row1->id_pos_details_split);
 	$valcount =  count($array_s);
 ?>		
<?php include_once("../includes/footer.php");?> 


<script>
  function calculateDiscountSingleItem(type,discount){
	
	var countper=document.getElementById("countper").value;
	var idnew=document.getElementById("per"+countper).value;

	//alert();
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();	
	
	$.ajax({
		type: "POST",
		url: 'ajax/discountaj.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&discount='+discount+'&UniqueCode='+idnew+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge, 
		success: function (result) {
			//alert(result);
			//$( "#item_subcode"+countper).val(data.itemcode);
			$( "#ViewOrderItemList").html(result);
	 	}
	});
	
	
	
	/* var table = document.getElementById('myTableOrder1');
	var rowCounts = table.rows.length;
	var rowCount = rowCounts - 2;*/
	
	}
</script>


<input type="hidden" id="counterval" value="<?php echo $counteridnw ?>"/>

<script>
 
<?php if($_REQUEST['updateid']!= ''){ ?>
	$('document').ready(function(){
		$('#po_date').click();

		/*var vals = '<?php echo $valcount; ?>';
 	 	for(var i =0; i<vals; i++){
 	 		var ids = '<?php echo $array_s[$i]; $i++; ?>';
 	 		alert();
			 setTimeout(() => {
		        AddMoreItem_test(ids); 
		    },300); 
		}*/
		var vals = '<?php echo $row1->id_pos_details_split; ?>';
		 setTimeout(() => {
		        AddMoreItem_test(vals); 
		    },300);
	});


function AddMoreItem_test(vals){
	//alert(vals);
	var counter1 =  $("#counter1").val();  
	var outleType = $("#outleType").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	var id_posbilling = $("#id_posbilling").val();
	counter1++;
	//alert(counter1);
	$(".select3").select2({});
    $(".select3").last().next().next().remove();
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetEditItemList.php',
		data: 'counter1='+counter1+'&outleType='+outleType+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&DbConnect=1&id_item_type='+id_item_type+'&vals='+vals,
		success: function (result) {
			//alert(result);
			//resulthtml = result.split('EXPLODE');
			//alert(resulthtml[0]);	
			 $(".select2").last().next().next().remove();	
			$(".select2").select2({});
         
        // $(".select2").last().next().next().remove();
        var vals = '<?php echo $valcount; ?>';		
			$( "#counter1" ).val(vals);
			//alert(result);
				$( "#ViewOrderItemListtest" ).append(result);
				//$( "#per" ).html(result);
	 	}
	});
	}
	
	
	
<?php } ?>
</script>

<script>
function getItemRate(id,rowCount){
	
	
var sub = $("#sub_total_items").val();	
var discount = document.getElementById("discount").value;
var total = $("#totalvalue").val;
var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;
//alert(vat);

	


/*

var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value;
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;



var sub = $("#sub_total_items").val();	
var discount = $("#discount").val;
var total = $("#totalvalue").val
var sgst = $("#TotalTax_sgst").val;
var cgst = $("#TotalTax_cgst").val;
var igst = $("#TotalTax_igst").val;
var cess = $("#TotalTax_cess").val;
var vat = $("#TotalTax_vat").val;
var sur = $("#TotalTax_surcharge").val;
*/
	
	
	var outlet = $("#outlet").val();
	//alert(outlet);
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'id='+id+'&rowCount='+rowCount+'&outlet='+outlet+'&DbConnect=1',
		success: function (result) {
			data = JSON.parse(result);
			
			//alert(result);  			
            $( "#items_id"+rowCount).val(id); 
			$( "#id_purch_details"+rowCount).val(data.id_purch_details);
			$( "#item_subcode"+rowCount).val(data.itemcode);
			$( "#item_qty"+rowCount).val(data.item_qty);
			$( "#item_unit"+rowCount).val(data.itemunit);
			$( "#item_rate"+rowCount).val(data.itemRate);
            $( "#per"+rowCount).val(data.dis);      			
            $( "#id"+rowCount).val(data.dis);   			
			$( "#item_amount"+rowCount).val(data.item_amount);
			$( "#item_tax_percentage"+rowCount).val(data.dis1);
			$( "#taxAccountName"+rowCount).val(data.taxAccountName);
			$( "#discountpercent"+rowCount).val(data.discountpercent);
			$( "#item_dis1"+rowCount).val(data.discountamount);
			$( "#sumtax"+rowCount).val(data.sumTaxPersentge);
			$( "#sumtaxamount"+rowCount).val(data.sumTaxAmount);
			$( "#item_sgst"+rowCount).val(data.sgst);
			$( "#item_cgst"+rowCount).val(data.cgst);
			$( "#item_igst"+rowCount).val(data.Tax_igst);
			$( "#item_cess"+rowCount).val(data.Tax_cess);
			$( "#item_vat"+rowCount).val(data.Tax_vat);
			$( "#item_sur"+rowCount).val(data.Tax_surcharge);
			$( "#total"+rowCount).val(data.item_TotalAmountItem);
			$( "#id_purch_detailsid").val(data.id_purch_detailsid);
			$( "#Tax_sgst_percentage").val(data.Tax_sgst_percentage);
			$( "#Tax_cgst_percentage").val(data.Tax_cgst_percentage);
			$( "#Tax_igst_percentage").val(data.Tax_igst_percentage);
			$( "#Tax_cess_percentage").val(data.Tax_cess_percentage);
			
			var tr = rowCount;
			$( "#valcount").val(tr);
			
			/*
		var itemsubtotal = $("#item_amount"+rowCount).val;
		var itemdiscount = $("#item_dis1"+rowCount).val;
		var itemtotal = $("#total"+rowCount).val;
		var itemsgst = $("#item_sgst"+rowCount).val;
		var itemcgst = $("#item_cgst"+rowCount).val;
		var itemigst = $("#item_igst"+rowCount).val;
		var itemcess = $("#item_cess"+rowCount).val;
		var itemvat = $("#item_vat"+rowCount).val;
		var itemsur = $("#item_sur"+rowCount).val;
			*/
			
		var itemsubtotal = document.getElementById("item_amount"+rowCount).value;
		var itemdiscount = document.getElementById("item_dis1"+rowCount).value;
		var itemtotal = document.getElementById("total"+rowCount).value;
		var itemsgst = document.getElementById("item_sgst"+rowCount).value;
		var itemcgst = document.getElementById("item_cgst"+rowCount).value;
		var itemigst = document.getElementById("item_igst"+rowCount).value;
		var itemcess = document.getElementById("item_cess"+rowCount).value;
		var itemvat = document.getElementById("item_vat"+rowCount).value;
		var itemsur = document.getElementById("item_sur"+rowCount).value;

//alert(sub);
//alert(itemsubtotal);
	
	var subtotal = parseFloat(sub) + parseFloat(itemsubtotal);
	var discounttotal = (parseFloat(discount) + parseFloat(itemdiscount));
	//var discounttotal = '0';
	//alert(discount);
	//alert(itemdiscount);
	var totalvalue = parseFloat(subtotal) - parseFloat(discounttotal);
	var total_sgst = (parseFloat(sgst) + parseFloat(itemsgst)).toFixed(2);
	var total_cgst = (parseFloat(cgst) + parseFloat(itemcgst)).toFixed(2);
	var total_igst = (parseFloat(igst) + parseFloat(itemigst)).toFixed(2);
	var total_cess = (parseFloat(cess) + parseFloat(itemcess)).toFixed(2);
	var total_vat = (parseFloat(vat) + parseFloat(itemvat)).toFixed(2);
	var total_sur = (parseFloat(sur) + parseFloat(itemsur)).toFixed(2);
	
	var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
	//var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess));
				
	var netamount_final = Math.round(netamount);
	var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
	var RoundAmonut = RoundOfAmount.toFixed(2);
	//alert(subtotal);
	


	$( "#totalvalue").val(totalvalue);
	$( "#sub_total_items").val(subtotal);
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
	$( "#netfinal").val(netamount_final);
	$( "#round_off_amount").val(RoundAmonut);
			
			
			
			
			
			
			
			
	
/*	var j=1; var kk=0; var kk1=0; var kk2=0; var kk3=0; var kk4=0; var kk5=0; var kk6=0; var kk7=0; var kk8=0; var kk9=0;
			//alert(tr);
			for (var i=0; i < tr ; i++) {
				//alert(j);
				var subtotal1 = document.getElementById("item_amount"+j).value;
				kk1 +=parseFloat(subtotal1);
				var subtotal=kk1;
				//alert(subtotal1);
				
				var dis = document.getElementById("item_dis1"+j).value;
				kk2 +=parseFloat(dis);
				var discount=kk2;
				//alert(subtotal);
				
				kk =kk1-kk2;
				var totalvalue=kk;
				
				var sgst = document.getElementById("item_sgst"+j).value;
				kk3 +=parseFloat(sgst);
				var total_sgst1=kk3;
				var total_sgst = total_sgst1.toFixed(2);
				
				var cgst = document.getElementById("item_cgst"+j).value;
				kk4 +=parseFloat(cgst);
				var total_cgst1=kk4;
				var total_cgst = total_cgst1.toFixed(2);
				
				var igst = document.getElementById("item_igst"+j).value;
				kk5 +=parseFloat(igst);
				var total_igst1=kk5;
				var total_igst = total_igst1.toFixed(2);
				
				var cess = document.getElementById("item_cess"+j).value;
				kk6 +=parseFloat(cess);
				var total_cess1=kk6;
				var total_cess = total_cess1.toFixed(2);
				
				var vat = document.getElementById("item_vat"+j).value;
				kk7 +=parseFloat(vat);
				var total_vat1=kk7;
				var total_vat = total_vat1.toFixed(2);
				
				var sur = document.getElementById("item_sur"+j).value;
				kk8 +=parseFloat(sur);
				var total_sur1=kk8;
				var total_sur = total_sur1.toFixed(2);
				
				//alert(totalvalue);
				j++;
				
				$( "#totalvalue").val(totalvalue);
				$( "#sub_total_items").val(subtotal);
				$( "#discount").val(discount);
				$( "#TotalTax_sgst").val(total_sgst);
				$( "#TotalTax_sgst1").val(total_sgst);
				$( "#TotalTax_cgst").val(total_cgst);
				$( "#TotalTax_cgst1").val(total_cgst);
				$( "#TotalTax_igst").val(total_igst);
				$( "#TotalTax_cess").val(total_cess);
				$( "#TotalTax_vat").val(total_vat);
				$( "#TotalTax_surcharge").val(total_sur);
				$( "#additional_discount_amount").val(0);
				
				var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
				
				var netamount_final = Math.round(netamount);
				$( "#netfinalint").val(netamount);
				$( "#netfinal").val(netamount_final);
				
				var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
				var RoundAmonut = RoundOfAmount.toFixed(2);
				//alert(RoundAmonut);
				$( "#round_off_amount").val(RoundAmonut);
			} */
			
		}
	  });
	}
	

function AddMoreItem(){
	var counter1 =  $("#counter1").val();  
	var outleType = $("#outleType").val();
	var id_item_type =$("#id_item_type").val();
	var outlet = $("#outlet").val();
	var id_posbilling = $("#id_posbilling").val();
   // alert(outlet);
	counter1++;
	//alert(counter1);
	
	
	$(".select3").select2({});
    $(".select3").last().next().next().remove();
	
	$.ajax({
		type: "POST",	
		data: 'counter1='+counter1+'&outleType='+outleType+'&DbConnect=1&id_item_type='+id_item_type,
		 success: function(response){
		}
		
	});
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetAddItemList.php',
		data: 'counter1='+counter1+'&outleType='+outleType+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&DbConnect=1&id_item_type='+id_item_type,
		success: function (result) {
			//resulthtml = result.split('EXPLODE');
			//alert(resulthtml[0]);	
			$(".select2").last().next().next().remove();	
			$(".select2").select2({});
         
            // $(".select2").last().next().next().remove();		
			$( "#counter1" ).val(counter1);
			//alert(result);
			$( "#ViewOrderItemListtest" ).append(result);
			//$( "#per" ).html(result);
			
	 	}
	});
}

	


function deleteitemRow(id, row_id){
	
var idnew1=$("#valcount").val();
 
var main_id = "<?php echo $_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);?>";
//alert(main_id);
	//$("#trdelete"+id).remove();
	
	//$("#trdelete"+id).hide();
	
	/*$('#itemName'+id).append('<option value="0" selected="selected">0</option>'); 
	$.ajax({
		type: "POST",
		url: 'ajax/DeleteOutletBill.php',
		data: {row_id:row_id,main_id:main_id},
		dataType: 'json',
		success: function (result) {
			
	 	}
	}); */
	
	var sub_total_items  =  $("#sub_total_items").val();  
	var totalvalue1      =  $("#totalvalue").val();  
	var discountvalue    =  $("#discount").val();  
	var sgst1            =  $("#TotalTax_sgst").val();  
	var cgst1            =  $("#TotalTax_cgst").val();  
	var igst1            =  $("#TotalTax_igst").val();  
	var cess1            =  $("#TotalTax_cess").val(); 
	var vat1             =  $("#TotalTax_vat").val(); 
	var surcharge1       =  $("#TotalTax_surcharge").val(); 
	
	if(vat1 == null){ var vat =  0; }else{ var vat =  $("#TotalTax_vat").val(); }
	if(surcharge1 == null){ var surcharge =  0; }else{ var surcharge =  $("#TotalTax_surcharge").val(); }
	if(sgst1 == null){ var sgst =  0; }else{ var sgst =  $("#TotalTax_sgst").val(); }
	if(cgst1 == null){ var cgst =  0; }else{ var cgst =  $("#TotalTax_cgst").val(); }
	if(igst1 == null){ var igst =  0; }else{ var igst =  $("#TotalTax_igst").val(); }
	if(cess1 == null){ var cess =  0; }else{ var cess =  $("#TotalTax_cess").val(); }
	
	var itemdiscount  = $("#item_dis1"+id).val();  
	var itemamount    = $("#item_amount"+id).val();
	var itemsgst      = $("#item_sgst"+id).val();
	var itemcgst      = $("#item_cgst"+id).val();
	var itemigst      = $("#item_igst"+id).val();
	var itemcess      = $("#item_cess"+id).val();
	var itemvat       = $("#item_vat"+id).val();
	var itemsur       = $("#item_sur"+id).val();
	
	var subtotal    = (parseFloat(sub_total_items) - parseFloat(itemamount));
	var discount    = (parseFloat(discountvalue) - parseFloat(itemdiscount));
	var totalvalue  = (parseFloat(subtotal) - parseFloat(discount));
	var total_sgst  = (parseFloat(sgst) - parseFloat(itemsgst)).toFixed(2);
	var total_cgst  = (parseFloat(cgst) - parseFloat(itemcgst)).toFixed(2);
	var total_igst  = (parseFloat(igst) - parseFloat(itemigst)).toFixed(2);
	var total_cess  = (parseFloat(cess) - parseFloat(itemcess)).toFixed(2);
	var total_vat   = (parseFloat(vat) - parseFloat(itemvat)).toFixed(2);
	var total_sur   = (parseFloat(surcharge) - parseFloat(itemsur)).toFixed(2);
	
	var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
	
	var netamount_final = Math.round(netamount);
	var RoundOfAmount	=	 (parseFloat(netamount_final)-parseFloat(netamount)).toFixed(2);
			//alert();
			
	$("#trdelete"+id).remove();	
	$( "#totalvalue").val(totalvalue);
	$( "#sub_total_items").val(subtotal);
	$( "#discount").val(discount);
	$( "#TotalTax_sgst").val(total_sgst);
	$( "#TotalTax_sgst1").val(total_sgst);
	$( "#TotalTax_cgst").val(total_cgst);
	$( "#TotalTax_cgst1").val(total_cgst);
	$( "#TotalTax_igst").val(total_igst);
	$( "#TotalTax_cess").val(total_cess);
	$( "#TotalTax_vat").val(total_vat);
	$( "#TotalTax_surcharge").val(total_sur);
	$( "#netfinalint").val(netamount);
	$( "#netfinal").val(netamount_final);
	$( "#round_off_amount").val(RoundOfAmount);
	//newone(id);  
}	

	
function newone(rowcount){
	//alert(rowcount);
	
var sub = document.getElementById("sub_total_items").value;
var discount = document.getElementById("discount").value;
var total = document.getElementById("totalvalue").value;
var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value;
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;


			
		var itemsubtotal = document.getElementById("item_amount"+rowCount).value;
		var itemdiscount = document.getElementById("item_dis1"+rowCount).value;
		var itemtotal = document.getElementById("total"+rowCount).value;
		var itemsgst = document.getElementById("item_sgst"+rowCount).value;
		var itemcgst = document.getElementById("item_cgst"+rowCount).value;
		var itemigst = document.getElementById("item_igst"+rowCount).value;
		var itemcess = document.getElementById("item_cess"+rowCount).value;
		var itemvat = document.getElementById("item_vat"+rowCount).value;
		var itemsur = document.getElementById("item_sur"+rowCount).value;

    //alert(subtotal1);
	//alert(sub);	
	
	var subtotal = parseFloat(sub) + parseFloat(itemsubtotal);
	var discounttotal = '0';
	var totalvalue = parseFloat(subtotal) - parseFloat(discounttotal);
	var total_sgst = parseFloat(sgst) + parseFloat(itemsgst);
	var total_cgst = parseFloat(cgst) + parseFloat(itemcgst);
	var total_igst = parseFloat(igst) + parseFloat(itemigst);
	var total_cess = parseFloat(cess) + parseFloat(itemcess);
	var total_vat = parseFloat(vat) + parseFloat(itemvat);
	var total_sur = parseFloat(sur) + parseFloat(itemsur);
	
	
	var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
				
	var netamount_final = Math.round(netamount);
	var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
	var RoundAmonut = RoundOfAmount.toFixed(2);
	//alert(RoundAmonut);
	


	$( "#totalvalue").val(totalvalue);
	$( "#sub_total_items").val(subtotal);
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
	$( "#netfinal").val(netamount_final);
	$( "#round_off_amount").val(RoundAmonut);
	
}


$("table.order-list1").on("click", ".ibtnDel1", function (event) {
       
	//alert('1');
	/*var clicked_id = $(this).attr("id");
	var deletes = clicked_id.indexOf("deletes"); 
	 
		var ids = this.id; 
		var regex = /[+-]?\d+(?:\.\d+)?/g;
		var match = parseInt(regex.exec(ids));
		var total  =-1;
		 
	$("#trdelete"+match).hide();
	$("#trdeletes"+match).hide();
	<?php if($row->id !=''){?>
		$("#edittrdelete"+match).hide();
		$("#edittrdeletes"+match).hide(); 
		var dbid = document.getElementById("dbid"+match).value;
		//Others Delete
		var others = 'po';

		$.ajax({
			type: "POST",
			url: "../ajax/PODeleteForm.php",
			data:{clicked_id:dbid, others:others},
			success: function(data){
				var mydata = JSON.parse(data);  
				if(mydata['delete'] == 1){      
				} 
			}
		});				  
		 //document.getElementById("indent_form").submit();
	<?php } ?> 
		
	//Total Amount Adding*/
	 	
}); 
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
	var id_posbilling = $("#id_posbilling").val();
		//alert(id_posbilling);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillNo.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&id_item_type='+id_item_type+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date, 
		success: function (result) {
			$( "#ViewKotSelectedTable" ).html(result);
			$( "#revServiceCharge" ).val(revServiceCharge);	
			$("#counter1").val('0');		
			OrderItemList(id_attribute_table);
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
	//alert(sub_total_items);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetLaundrySpaOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outleType='+outleType+'&sub_total='+sub_total_items+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			//alert(revServiceCharge);
			$( "#ViewOrderItemList" ).html(result);
			$( "#revServiceCharge" ).val(revServiceCharge);
	 	}
	});
}


	
	function OrderItemList(opts){
	//alert();
	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var outleType = $("#outleType").val();
	var outlet = $("#outlet").val();	
	var id_posbilling = $("#id_posbilling").val();
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetLaundrySpaOrderItemList.php',
		//data: 'id_attribute_table='+opts+'&DbConnect=1&outleType='+outleType+'&outlet='+outlet+'&id_posbilling='+id_posbilling,
		data: 'id_attribute_table='+opts+'&DbConnect=1&outleType='+outleType+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			//alert(result);
			$( "#ViewOrderItemList" ).html(result);
			//$( "#revServiceCharge" ).val(revServiceCharge);
	 	}
	});
	}
	
	
function loadkotOrder(sel){
	var revServiceCharge = $("#revServiceCharge").val();
	var outlet = $("#outlet").val();	
	var doc_type = $("#doc_type").val();
	var po_date = $("#po_date").val();
	var opts = [],opt;	
	var len = sel.options.length;
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
  	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		//url: 'ajax/ajaxGetBillingLaundryspaOrder.php.php',
		data: 'id_attribute_table='+opts+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date, 
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);		
				OrderItemList(opts);
	 	}
	});
	}
	


function additionalDiscount(type,discountamount){
	//alert(discountamount);
	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();	
	//alert(type+'==='+revServiceCharge);
	$.ajax({
		type: "POST",
		url: 'ajax/discountaj.php',
		data: 'id_attribute_table='+opts+'&discountType='+type+'&discountamount='+discountamount+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			data = JSON.parse(result);
			
			var total1 = document.getElementById("netfinalint").value;
			//var total2 = document.getElementById("netfinal").value;
			var Additional = data.Additional;
			var discountt = data.discountt;
			var netamount = ((parseFloat(total1))+(parseFloat(Additional)))-parseFloat(discountt);
			//var netamount1 = ((parseFloat(total2))+(parseFloat(Additional)))-parseFloat(discountt);
			var netamount_final = Math.round(netamount);
			$( "#netfinal").val(netamount_final);
			
			var RoundOfAmount	=	 parseFloat(netamount_final)-parseFloat(netamount);
			
			var RoundAmonut = RoundOfAmount.toFixed(2);
			$( "#round_off_amount").val(RoundAmonut);
			//alert(total1); 
	 	}
	});
	
	}
	
	
	function additionalDiscount1(type,discountamount){
	//alert(discountamount);
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();	
	//alert(type+'==='+revServiceCharge);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'id_attribute_table='+opts+'&discountType='+type+'&discountamount='+discountamount+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			data = JSON.parse(result);
			//alert(result);
			//resulthtml = result.split('EXPLODE');
			//alert(resulthtml[0]);	
			
	 	}
	});
	}
	
	function hideandshow2() {
		//alert('hideandshow--12');
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();	
	var id_posbilling = $("#id_posbilling").val();
		//alert(id_posbilling);
	//alert(po_date);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillNo.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date, 
		success: function (result) {
			$( "#ViewKotSelectedTable" ).html(result);
			$( "#revServiceCharge" ).val(revServiceCharge);				
			OrderItemList(id_attribute_table);
	 	}
	});
			
			/*$.ajax({
				type: "POST",
				url: "ajax/GRNPosManage.php",
				data:{doc_type:'doc_type'},
				success: function(data){
					alert(data);
				}
			});*/
		
	}
	
</script>


<script type="text/javascript">
	function audittrial(clicked_value){

		//alert(clicked_value);
		$('#auditModal').modal('show');
		var table ='pos_purch_table';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
</script>