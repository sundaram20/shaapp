<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
// include_once("include/pos_function.php");
unset($_SESSION['LINELEVEL']);
unset($_SESSION['discountamount']);
unset($_SESSION['AdditionalChargeamount']);
unset($_SESSION['purchdetailitemID']);

//print_r($_REQUEST);

if($_REQUEST['updateid']!=''){

	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']='117';//$ResultupdateRow->id_attribute_table_group;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	
	$_REQUEST['id_attribute_table']=$ResultupdateRow->id_attribute_table;
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']=$ResultupdateRow->id_mst_outlet;
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	
	
$id_doc_type_configuration	=	$ResultupdateRow->id_doc_type_configuration;
$po_no		=$ResultupdateRow->doc_no;
$mdoc_no	  =$ResultupdateRow->mdoc_no;	

}else{
		
	$_REQUEST['id_posbilling']='';
}
	
?>
<?php   //echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
/*	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
*/
	$doc_type = $_REQUEST['doc_type_bill'];//13;
	//echo "document type edit ".$doc_type;
	//echo "hello".$_REQUEST['doc_type_bill'];
	//AND `doc_type`='".$doc_type."'	
/*4
	if($doc_type == 10){
		$table_doc_type = "1";
		$redirect_page="manageGRN.php";
	}elseif($doc_type == 5){
		$table_doc_type = "2";
		$redirect_page="managePurch.php";
	}*/
	/*$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$doc_type."'	 ";
								$db->query($sql2);   
										$row2 = $db->fetch_object();
										 $prefix= $row2->prefix; 
										 $suffix = $row2->suffix; */
										
if($doc_type == 10){
		$add = "POS Sales Bill";		
		/*$table_field = "PO";
		$field = "POS";*/
	}
	if($doc_type == 13){
		$add = "POS Sales Bill(nc)";		
		/*$table_field = "PO";
		$field = "POS";*/
	}
	/*echo $doc_type;	
	echo $po_date = TBL_DOC_TYPE_CONFIG.'effective_date'." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";*/							
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">

	
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
      <h1>
        Billing Manager
      </h1>
      <ol class="breadcrumb">
        <li><a href="managePO.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  Billing</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         <?php //print_r($_REQUEST);?>
           
			 <div class="nav-tabs-custom">
		 
			<div class="box-header with-border">
              <h3 class="box-title">Billing : <a><?php echo selectColumn(TBL_INV_PO,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form" action="savePrintBill.php"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
                <input type="hidden" value="<?php echo $doc_type;?>" name="doc_type" id="doc_type" />
                <input type="hidden" value="<?php echo $_REQUEST['id_posbilling'];?>" name="id_posbilling" id="id_posbilling" />
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
		
 <label for="outlet">Outlet <font color="#FF0000">*</font> </label>
         
               				
 <?php $categoryDropDown = '<select class="form-control select2" name="outlet" id="outlet" data-parsley-required data-parsley-errors-container="#outletError" onChange="loadkotOutlet(this);">
				<option value="">Select Outlet</option>';
			  $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='1' ",'');
			  if($db->num_rows2($resCat)){
				while($resultCat = $db->fetch_object2($resCat)){
					if($_REQUEST['outlet'] == $resultCat->id){
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
		                  <input data-parsley-required type="text" class="form-control dates" placeholder="eeEnter PO Date" id="po_date" name="po_date" value="<?php echo date('d-m-Y');?>" onChange="hideandshow2();"  onclick="hideandshow2();" <?php echo $readonly; ?>>
		                   <input style="display: none;"  type="text" class="form-control dates" placeholder="sreEnter PO Date" id="po_date1" name="po_date1" value="<?php if($_POST) echo $_POST['po_date'];elseif($row->po_date!='') echo date('d-m-Y',strtotime($row->po_date));else echo date('d-m-Y');?>" >
		                  </div> 
	              		</div> 
                        		<?php  /*?><div class="col-md-4">
             
	              			<label for="name">Remarks </label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  >
							</div>
			
                      </div><?php */?> 
                      
                       			                	                
						
                        
           <div class="form-group col-xs-12 col-md-3 col-sm-2" >
                <label>Table No</label>	                
              <select class="form-control select2" name="id_attribute_table[]" data-parsley-required id="id_attribute_table" multiple="multiple" data-parsley-errors-container="#id_attribute_tableError" onChange="loadkotOrder(this);">				  
                  <?php 
				  
				  if($_REQUEST['updateid']==''){
					$CheckBlockedTable_Sql = "SELECT id_attribute_table FROM pos_purch WHERE pos_bill_type='1' and cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) group by id_attribute_table";
	                   $db->query($CheckBlockedTable_Sql); 
	                  while($ResultBlockedtable1 = $db->fetch_object()){
	                  	$id_attribute_table_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$ResultBlockedtable1->id_attribute_table."'");
							if(isset($_REQUEST['id_attribute_table']))
								if($ResultBlockedtable1->id_attribute_table==$_REQUEST['id_attribute_table']){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$hotelDropDown .= '<option '.$selected.' value="'.$ResultBlockedtable1->id_attribute_table.'">'.ucfirst($id_attribute_table_name).'</option>';
												}
								echo $hotelDropDown .= '</select>';
				  }else{
					  
					  
					  $id_attribute_table_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$_REQUEST['id_attribute_table']."'");
													/*if(isset($_REQUEST['id_attribute_table']))
													if($ResultBlockedtable1->id_attribute_table==$_REQUEST['id_attribute_table']){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}*/
								$selected = 'selected="selected"';
								$hotelDropDown .= '<option '.$selected.' value="'.$_REQUEST['id_attribute_table'].'">'.ucfirst($id_attribute_table_name).'</option>';
												
								echo $hotelDropDown .= '</select>';
						}
				  
				  ?>
                    <span id="id_attribute_tableError"></span>
              </div>
              
                
	  
	              		

<div id="ViewKotSelectedTable">
	<div class="form-group col-xs-12 col-md-3 col-sm-2">
    <label>KOT</label>	                
    <select class="form-control select2" name="id_kot[]" data-parsley-required id="id_kot" multiple="multiple" data-parsley-errors-container="#id_kotError" >				  
<?php 
echo $CheckBlockedTable_Sql = "SELECT id,id_attribute_table FROM pos_purch WHERE pos_bill_type='1'  AND cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)  ";
   $db->query($CheckBlockedTable_Sql); 
  while($ResultBlockedtable1 = $db->fetch_object()){
	$id_attribute_table_name	=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$ResultBlockedtable1->id_attribute_table."'");
								
								$KotDropDown .= '<option '.$selected.' value="'.$ResultBlockedtable1->id.'">KOT ='.$id_attribute_table_name.'-'.ucfirst($ResultBlockedtable1->id).'</option>';
							}
						 echo  $KotDropDown .= '</select>';
					   ?>
					   <span id="id_kotError"></span> 
					   </div>
</div>
		              <?php /*?><div class="row">  	
		                <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Pax <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Table No" id="noOfPax" name="noOfPax" value="<?php echo $_POST['pax'];?>" data-parsley-required  data-parsley-errors-container="#noOfPaxError">
							 </div><span id="noOfPaxError"></span>
	                  </div>
	                  <div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
<input type="hidden" class="form-control" placeholder="Enter Steward Name" id="id_attribute_steward" name="id_attribute_steward" value="<?php echo $_POST['id_attribute_steward'];?>"  />
<input type="hidden" name="id_attribute_shift" id="id_attribute_shift" value="<?php echo $_POST['id_attribute_shift'];?>" />
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="StewardName" name="StewardName" value="<?php echo $_POST['attribute_steward_name'];?>"  data-parsley-required data-parsley-errors-container="#StewardNameError">
							</div>
                            <span id="StewardNameError"></span>
	                  </div>
</div><?php */?>
	                     
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
					   <?php
					   //BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);
						include_once("ajax/ajaxGetOrderItemList.php");
					   ?>
											
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
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("manageOutletBilling.php"); '>
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
<?php include_once("../includes/footer.php");?> 


<script type="text/javascript">
	function audittrial(clicked_value){

		//alert(clicked_value);
		$('#auditModal').modal('show');
		//var table ='mst_assign_hotel_rooms';
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

 


<script type="text/javascript">

	function calculateDiscountSingleItem(lineUniqueCode,type,discount){
		
		var revServiceCharge = $("#revServiceCharge").val();
		var opts = $("#id_attribute_table").val();
		var outlet = $("#outlet").val();
		var id_kot = $("#id_kot").val();	
		var id_posbilling = $("#id_posbilling").val();
		$.ajax({
			type: "POST",
			url: 'ajax/ajaxGetOrderItemList.php',
			data: 'id_attribute_table='+opts+'&DbConnect=1&discount='+discount+'&UniqueCode='+lineUniqueCode+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot, 
			success: function (result) {
			   $( "#ViewOrderItemList" ).html(result);	
		 	}
		});
	
	}
</script>


<script type="text/javascript">

 function reverceServiceCharge(){
	var revServiceCharge = $("#revServiceCharge").val();
    if (revServiceCharge==0) {
		// alert("Check box in Checked"); 
		$("#revServiceCharge").val('1');
		loadkotOutlet();
    }else { 
					
        // alert("Check box is Unchecked"); 
		$("#revServiceCharge").val('0');
		loadkotOutlet();
						
						
    } 
 }
 </script>


<script type="text/javascript">
/*function reverceServiceCharge(){
	var revServiceCharge = $("#revServiceCharge").val();
	 $("#revServiceCharge").click(function() { 
                    if ($("input[type=checkbox]").prop( 
                      ":checked")) { 
                        alert("Check box in Checked"); 
                    } else { 
                        alert("Check box is Unchecked"); 
                    } 
                }); 
	alert(revServiceCharge);}*/
function loadkotOutlet(){
	//alert("hello");
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	var id_posbilling = $("#id_posbilling").val();
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling, 
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);				
				OrderItemList(id_attribute_table);
	 	}
	});
}
</script>


<script type="text/javascript">
function loadkotOrder(sel){
	var revServiceCharge = $("#revServiceCharge").val();
	var id_kot = $("#id_kot").val();
	//alert(id_kot);
	var outlet = $("#outlet").val();	
	var doc_type = $("#doc_type").val();
	var id_posbilling = $("#id_posbilling").val();
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
		data: 'id_attribute_table='+opts+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot, 
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);		
				OrderItemList(opts);
	 	}
	});
}
</script>


<script type="text/javascript">
	
function OrderItemList(opts){
	var revServiceCharge = $("#revServiceCharge").val();
	var outlet = $("#outlet").val();	
	var id_kot = $("#id_kot").val();
	//alert(id_kot);
	var id_posbilling = $("#id_posbilling").val();
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot,
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);
				
	 	}
	});
}
</script>


<script type="text/javascript">

function additionalDiscount(type,discountamount){
	var revServiceCharge = $("#revServiceCharge").val();
	var id_posbilling = $("#id_posbilling").val();	
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();	
	var id_kot = $("#id_kot").val();
	//alert(type+'==='+revServiceCharge);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&discountType='+type+'&discountamount='+discountamount+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot,
		success: function (result) {
			//alert(result);
			//resulthtml = result.split('EXPLODE');
			//alert(resulthtml[0]);			
				$( "#ViewOrderItemList" ).html(result);
				//$( "#ViewPreviousOrder" ).html(result);
			
				
	 	}
	});
}
</script>


<script type="text/javascript">
	
function hideandshow2() {
		
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	var id_posbilling = $("#id_posbilling").val();
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();	
	//alert(po_date);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling,
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
$(document).ready(function() {
	
 	<?php 

		if($doc_type != ''){
	   $po_date = selectColumn(TBL_DOC_TYPE_CONFIG,'effective_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' order by effective_date DESC LIMIT 0,1 ");
		echo $po_date = date('d-m-Y' , strtotime(addslashes($po_date))); 
		}	 
				
	?>
		
	var dates = '<?php echo ($po_date!=''?date("d-m-Y",strtotime($po_date)):date('d-m-Y')); ?>';
		//document.getElementById("po_date").value = dates; 
	document.getElementById('po_date').click();  
	$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });
	//Button hide */
	
		 
});
</script>


<script type="text/javascript">
	
function fillup(fillupfrom,filluptill,fillupid){
	
	var fillupVal = $("input[name='discount|"+fillupid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	//alert(id_posbilling);
	var data2 = '&fillupfrom='+fillupfrom+'&filluptill='+filluptill+'&fillupid='+fillupid+'&fillupVal='+fillupVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
				
//		lineUniqueCode,type,discount	
				
	 	}
	});
	
}
</script>


<script type="text/javascript">
function filldown(filldownfrom,filldowntill,filldownid){
	
	var filldownVal = $("input[name='discount|"+filldownid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	//alert(id_posbilling);
	var data2 = '&filldownfrom='+filldownfrom+'&filldowntill='+filldowntill+'&filldownid='+filldownid+'&filldownVal='+filldownVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
		
	 	}
	});
	
}
</script> 

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