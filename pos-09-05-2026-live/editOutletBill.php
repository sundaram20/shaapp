<?php
 include_once("../config/auto_loader.php");
//include_once("include/pos_function.php");

unset($_SESSION['LINELEVEL']);
unset($_SESSION['discountamount']);
unset($_SESSION['AdditionalChargeamount']);
unset($_SESSION['outdetailitemID']);
//print_r($_REQUEST);
//print_r($_SESSION);
//echo $_REQUEST['updateid'];

if($_REQUEST['updateid']!=''){
	$session=$_REQUEST['session'];
}else{
	$session=$_GET['session'];
}

if($_REQUEST['updateid']!=''){
	
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND  id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']=$ResultupdateRow->id_attribute_table_group;//117;
	//$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;

	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']=$ResultupdateRow->id_mst_outlet;
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	
	
$id_doc_type_configuration	=  $ResultupdateRow->id_doc_type_configuration;
$po_no		                =  $ResultupdateRow->doc_no;
$mdoc_no	                =  $ResultupdateRow->mdoc_no;	

}else{
	$_REQUEST['id_posbilling']='';
}

$updateSql1 = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE  id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");
$row1 = mysqli_fetch_object($updateSql1);

$editSql = mysqli_query($connNew,"SELECT * FROM pos_purch_details WHERE  id_pos_purch= '".encryptor(decrypt,$_REQUEST['updateid'])."'");
while($editrow = mysqli_fetch_object($editSql)){
 $edit_id1 = $editrow->id;

  $string1 .= $edit_id1.',';
}
 $edit_id = rtrim($string1,',');
 
 
 

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
 
if($session=='25'){
	$outleType	=	'2';
	$MenuType	=	'172';
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Laundry" ');
}
if($session=='26'){
	$outleType	=	'3';
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Spa and Health Club" ');
}

if($session=='29'){
	$outleType	=	'4';
	$id_item_type=selectColumn(TBL_ATTRIBUTES,'id','WHERE table_name="items_type" AND field_value="Others" ');
}
$_SESSION['id_document']==$session;
 $doc_type = $session;	

//Title Section 
/*
$referPage = strtok(strtoupper(basename($_SERVER['HTTP_REFERER'])),'?');
	if($session!=''){
		$pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE id='".$session."' ";
	}
	else{
		$pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE UPPER(file_name)='".$referPage ."' ";
	}

	$pageChkRes = mysqli_query($appConnect,$pageChkSql);

	if(mysqli_num_rows($pageChkRes) > 0){
		$row = mysqli_fetch_object($pageChkRes);

		$color   = ucwords(strtolower(selectField(APP_MODULE,'color','WHERE id="'.$row->id_module.'"',$appConnect)));
		$icon    = selectField(APP_MODULE,'icon','WHERE id="'.$row->id_module.'"',$appConnect);
		$module  = ucwords(strtolower(selectField(APP_MODULE,'name','WHERE id="'.$row->id_module.'"',$appConnect)));
		$menu    = ucwords(strtolower(selectField(APP_MENU,'name','WHERE id="'.$row->id_menu.'"',$appConnect)));
		$submenu = ucwords(strtolower($row->name));
 
	 
	} */
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style>
table.dataTable tbody th, table td{
		padding:0!important;
		font-size:12px;
	}
	.form-control[readonly]{
		background: none;
	}
	.form-control{
		font-size:12px;
	}
	#ViewOrderItemList .form-group{
	margin-bottom:4px;

	}
</style>
<div class="content-wrapper">

	<?php  $session; ?>

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
	
	
        <div class="modal-content">
            <div class="modal-header" style="background-color: #172635;color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:12px;">Alteration History</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<div style="text-align:center;font-weight:600;font-size:12px"> Bill No - <?php echo $ResultupdateRow->mdoc_no ?> </div>
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
               <button type="button" class="btn c-btn" data-dismiss="modal"> <i class="far fa-window-close"></i> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->

<?php $session=$_GET['session'];



 ?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <div class="row">
     <div class="col-md-6 col-xs-12"> 	
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php //echo '<span style="color:'.$color.'">&nbsp;<i class="fa '.$icon.'"></i> '.$submenu.'</span>'; ?>
		<?php //echo '<span style="color:'.$data['color'].'">&nbsp;<i class="fa '.$data['icon'].'"></i> '.$data['submenu'].'</span>'; ?>
		<?php echo '<span style="color:'.currentNavigation_s($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_s($session)['icon'].'"></i> '.currentNavigation_s($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>-->
         <h6 class="box-title" ><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> 
					<?php echo currentNavigation_s($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  </h6>
         </div>
  
     <div class="col-md-6 col-xs-12 tb-br">				  
      <?php echo breadCrumbs(); ?>
      </div>
</div>
    </section>
    <!-- Main content -->
    <section class="content">
	
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
			<div class="nav-tabs-custom mb-0">
		
			
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form" action="savePrintBillothers.php?updateid=<?php echo $_REQUEST['updateid']; ?>&session=<?php echo $_REQUEST['session'] ?>&submenu=<?php echo $_REQUEST['submenu']; ?>"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
			 
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
              		
	              	<div class="row">	
        <div class="form-group col-xs-6 col-md-2 col-sm-6" >
        <input type="hidden" value="<?php echo $id_item_type;?>" name="id_item_type" id="id_item_type" />
		<input type="hidden" name="outleType" id="outleType" value="<?php echo $outleType; ?>" >
         <input type="hidden" value="<?php echo $_REQUEST['id_posbilling'];?>" name="id_posbilling" id="id_posbilling" />
				
 <label for="outlet">Outlet <font color="#FF0000">*</font> </label>
         
               	<select class="form-control select2" name="outlet" id="outlet" data-parsley-required data-parsley-errors-container="#outletError" onChange="loadkotOutlet(this);">			
        <?php $categoryDropDown = '
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
                     
				 <div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div><!---   onChange="hideandshow2();"  onclick="hideandshow2();"  -->
						
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="eeEnter PO Date" id="po_date" name="po_date" value="<?php if($_POST) echo $_POST['po_date'];else if($row1->doc_date!='') echo date('d-m-Y',strtotime($row1->doc_date));else echo date('d-m-Y');?>" onChange="hideandshow2();" onclick="hideandshow2();" <?php echo $readonly; ?>>
		                   
						   
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
	              		
		         <div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			
		              		<!-- ///onchange="changeFunc()" -->
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

		              		
	              				<input type="hidden" class="form-control" placeholder="Enter Remarks" id="id_attribute_shift" name="id_attribute_shift" value="711"  >
							
			
                      
                            
                    <div class="col-md-2 col-sm-6 col-xs-6">
             
	              			<label for="name">Remarks </label>
	              			
		              		
	              				<input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row1->remarks);?>"  >
							
			
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
                 
 						<div class="form-group col-xs-6 col-md-6 col-sm-6" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>
		    </div>        
 
		        </div>
		       
		        
              	<div class="card text-dark bg-light">
              	
                     <div id="ViewOrderItemList">
					    
					</div>
				<div class="row">
		            	
		            	<table id="myTable1" class=" table order-list1 mb-0">
				            
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
                                  
				           	<input type="text" name="counter1" id="counter1" value="0" hidden=""> 
				            <!--	<input type="text" name="counter1" id="counter1" value="<?php echo $counts=0; ?>" hidden=""> -->
				            </tbody>
				          
				        </table>
				        
		            </div> 
		        	
		        	<!-- Total Amount Section -->
		            
		        </div>         
				
		        </div>
		       
 
				           
              </div>
         	
              <!-- /.box-body -->	
			 <div class="box-footer"> 
                
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save ':'Edit')?>' class="btn c-btn ml-10" name="Save"  >
				
			   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("manageOutletBill.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>"); '><i class="far fa-window-close"></i> Close</a>
			<br><br>

<?php if($ResultupdateRow->date_created){?>
					<div class="row">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($ResultupdateRow->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$ResultupdateRow->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($ResultupdateRow->last_modified));?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$ResultupdateRow->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
					   <a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
					   		 <i class="fas fa-history"></i> Alteration History</a>
				<?php } ?>  


			
				 
				 
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
 	$array_s =  explode(",", $edit_id);
 	$valcount =  count($array_s);
 ?>		
<?php include_once("../includes/footer.php");?> 

<input type="hidden" id="counterval" value="<?php echo $counteridnw ?>"/>


<script>
	<?php if($_GET['reload'] != '1'){ ?>
	$(document).ready(function (){  
		location.href = "editOutletBill.php?updateid="+<?php echo $_REQUEST['updateid']; ?>+"&session="+<?php echo $_GET['session']; ?>+"&reload=1";
	});
	<?php } ?>
</script>


<script>
 
<?php if($_REQUEST['updateid']!= ''){ ?>
	$('document').ready(function(){
		$('#po_date').click();
		
	var vals = '<?php echo $edit_id; ?>'	
		
	/*	var vals = '<?php echo $edit_id; ?>';
		 setTimeout(() => {
		        AddMoreItem_test(vals); 
		    },300);  */
	});
	
	function AddMoreItem_test(vals){
	
	var counter1 =  $("#counter1").val(); 
//alert(counter1);	
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
		
		$(".select2").last().next().next().remove();	
		$(".select2").select2({});
       
        var vals = '<?php echo $valcount; ?>';		
			$( "#counter1" ).val(vals);
			$( "#ViewOrderItemListtest" ).append(result);
	 	}
	});
	}
	
<?php } ?>
</script>

<script>
	

function getItemRate(id,rowCount){
	//alert(rowCount);
	
var sub = $("#sub_total_items").val();	
var discount = document.getElementById("discount").value;
var total = $("#totalvalue").val;
var sgst = document.getElementById("TotalTax_sgst").value;
var cgst = document.getElementById("TotalTax_cgst").value
var igst = document.getElementById("TotalTax_igst").value;
var cess = document.getElementById("TotalTax_cess").value;
var vat = document.getElementById("TotalTax_vat").value;
var sur = document.getElementById("TotalTax_surcharge").value;


var TotalTax_sgst2 = document.getElementById("sc_sgst1").value;
var TotalTax_cgst2 = document.getElementById("sc_cgst1").value;
//alert(TotalTax_sgst2);	
var itemsubtotal2 = document.getElementById("item_amount"+rowCount).value;
if(itemsubtotal2==''){
	var itemsubtotal1 = '0';
}else{
	var itemsubtotal1 = document.getElementById("item_amount"+rowCount).value;
}
	
	var outlet = $("#outlet").val();
	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetItemRate.php',
		data: 'id='+id+'&rowCount='+rowCount+'&outlet='+outlet+'&DbConnect=1',
		success: function (result) {
			data = JSON.parse(result);
			
			//alert(result);  			
            $( "#items_id"+rowCount).val(id); 
            $( "#items_idd"+rowCount).val(data.dis1); 
            $( "#subitems_id"+rowCount).val(data.dis); 
            $( "#mainitem_id"+rowCount).val(data.dis12); 
            $( "#outlet"+rowCount).val(data.outlet);
			
            $( "#item_name"+rowCount).val(data.item_name); 

            $( "#item_code"+rowCount).val(data.itemcode); 
            $( "#subitems_name"+rowCount).val(data.subitems_name); 
            $( "#subitems_name1"+rowCount).val(data.subitems_name1); 
			
            $( "#id_mst_charges_sales_local"+rowCount).val(data.id_mst_charges_sales_local); 
            $( "#id_mst_charges_sales_interstate"+rowCount).val(data.id_mst_charges_sales_interstate); 
            $( "#item_amount1"+rowCount).val(data.itemRate); 
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
			$( "#Tax_sgst_percentage"+rowCount).val(data.Tax_sgst_percentage);
			$( "#Tax_cgst_percentage"+rowCount).val(data.Tax_cgst_percentage);
			$( "#Tax_igst_percentage"+rowCount).val(data.Tax_igst_percentage);
			$( "#Tax_cess_percentage"+rowCount).val(data.Tax_cess_percentage);
			$( "#Tax_vat_percentage"+rowCount).val(data.Tax_vat_percentage);
			$( "#Tax_surcharge_percentage"+rowCount).val(data.Tax_surcharge_percentage);
			
			$( "#id_mst_charges_sgst"+rowCount).val(data.id_mst_charges_sgst);
			$( "#id_mst_charges_cgst"+rowCount).val(data.id_mst_charges_cgst);
			$( "#id_mst_charges_igst"+rowCount).val(data.id_mst_charges_igst);
			$( "#id_mst_charges_cess"+rowCount).val(data.id_mst_charges_cess);
			var tr = rowCount;
			$( "#valcount").val(tr);
		
		
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
//alert(itemsgst);
	
	var subtotal = (parseFloat(sub) + parseFloat(itemsubtotal))-  parseFloat(itemsubtotal1);
	var discounttotal = (parseFloat(discount) + parseFloat(itemdiscount));
	var totalvalue = parseFloat(subtotal) - parseFloat(discounttotal);
//	var total_sgst = (parseFloat(sgst) + parseFloat(itemsgst)).toFixed(2);
var total_sgst = ((parseFloat(sgst) + parseFloat(itemsgst)) - (parseFloat(TotalTax_sgst2))).toFixed(2);
var total_cgst = ((parseFloat(cgst) + parseFloat(itemcgst)) - (parseFloat(TotalTax_cgst2))).toFixed(2);
	//var total_cgst = (parseFloat(cgst) + parseFloat(itemcgst)).toFixed(2);
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
	


	$( "#totalvalue").val(totalvalue.toFixed(2));
	$( "#sub_total_items").val(subtotal.toFixed(2));
	$( "#discount").val(discounttotal.toFixed(2));
	$( "#TotalTax_sgst").val(total_sgst);
	$( "#TotalTax_sgst1").val(total_sgst);
	$( "#TotalTax_cgst").val(total_cgst);
	$( "#TotalTax_cgst1").val(total_cgst);
	$( "#TotalTax_igst").val(total_igst);
	$( "#TotalTax_cess").val(total_cess);
	$( "#TotalTax_vat").val(total_vat);
	$( "#TotalTax_surcharge").val(total_sur);
	$( "#netfinalint").val(netamount);
	$( "#netfinal").val(netamount_final.toFixed(2));
	$( "#round_off_amount").val(RoundAmonut);
		scharge(subtotal);	
	
		}
	  });
	}
	
	
function scharge(subtotal){
	//alert(count); 
var sub = $("#sub_total_items").val();	
var TotalTax_sgst2 = $("#sc_sgst1").val();	

	var revServiceCharge = $("#revServiceCharge").val();
	//alert(revServiceCharge);
	var outleType = $("#outleType").val();
	var outlet = $("#outlet").val();	
	var id_posbilling = $("#id_posbilling").val();
	var sub_total_items = $("#sub_total_items").val();
	
	$.ajax({
		
		type: "POST",
		url: 'ajaxGetLaundrySpaOrderItemList2.php',
		data: 'DbConnect=1&outleType='+outleType+'&sub_total='+subtotal+'&outlet='+outlet+'&id_posbilling='+id_posbilling+'&revServiceCharge='+revServiceCharge,
		success: function (result) {
			//alert(subtotal);
		$( "#revServiceCharge" ).val(revServiceCharge);
		
       data = JSON.parse(result);
	   
       $( "#service_charge_amount").val((data.service_charge_amount).toFixed(2));			
       $( "#serviceChargeTotal").val(data.serviceChargeTotal);
       $( "#sc_sgst1").val(data.serviceTotalSGST);
       $( "#sc_cgst1").val(data.serviceTotalCGST);
	 
	 
		var sgst = document.getElementById("TotalTax_sgst1").value;
		var cgst = document.getElementById("TotalTax_cgst1").value;
		var total1 = document.getElementById("netfinalint").value;
		var total = document.getElementById("totalvalue").value;
		var adddiscount = document.getElementById("additional_discount_amount").value;
		var serviceTotalSGST1 = data.serviceTotalSGST;
		var serviceTotalCGST1 = data.serviceTotalCGST;
		var serviceChargeTotal = data.serviceChargeTotal;
		
		//alert(serviceTotalSGST1);
		
		var serviceTotalSGST = (parseFloat(sgst))+(parseFloat(serviceTotalSGST1));
		var serviceTotalCGST = (parseFloat(cgst))+(parseFloat(serviceTotalCGST1));
		var netamount = ((parseFloat(total))+(parseFloat(serviceChargeTotal))+(parseFloat(serviceTotalSGST))+(parseFloat(serviceTotalCGST))+(parseFloat(serviceTotalSGST1))+(parseFloat(serviceTotalCGST1)))-parseFloat(adddiscount);
		
		var netamount1 = Math.round(netamount);
		var RoundOfAmount = (parseFloat(netamount1))-(parseFloat(netamount));
		
		var RoundAmonut = RoundOfAmount.toFixed(2);
				
		$( "#round_off_amount").val(RoundAmonut);
		$( "#netfinal").val(netamount1.toFixed(2));
		$( "#netfinalint").val(netamount);
		
		$( "#TotalTax_sgst").val(serviceTotalSGST.toFixed(2));
		$( "#TotalTax_cgst").val(serviceTotalCGST.toFixed(2));
		
	 	}
	});
}
	

function AddMoreItem(){
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
         	
			$( "#counter1" ).val(counter1);
			//alert(result);
			$( "#ViewOrderItemListtest" ).append(result);
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
	var TotalTax_sgst2       =  $("#sc_sgst1").val(); 
	var TotalTax_cgst2       =  $("#sc_cgst1").val(); 
//alert(TotalTax_sgst2);	


	
	
	
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
//var ssgst = document.getElementById("item_sgst"+id).value;
	
	var subtotal    = (parseFloat(sub_total_items) - parseFloat(itemamount));
	var discount    = (parseFloat(discountvalue) - parseFloat(itemdiscount));
	var totalvalue  = (parseFloat(subtotal) - parseFloat(discount));
	//var total_sgst  = (parseFloat(sgst) - parseFloat(itemsgst)).toFixed(2);
	//var total_cgst  = (parseFloat(cgst) - parseFloat(itemcgst)).toFixed(2);
var total_sgst = ((parseFloat(sgst)) - (parseFloat(itemsgst)+parseFloat(TotalTax_sgst2))).toFixed(2);
var total_cgst = ((parseFloat(cgst)) - (parseFloat(itemcgst)+parseFloat(TotalTax_cgst2))).toFixed(2);
	var total_igst  = (parseFloat(igst) - parseFloat(itemigst)).toFixed(2);
	var total_cess  = (parseFloat(cess) - parseFloat(itemcess)).toFixed(2);
	var total_vat   = (parseFloat(vat) - parseFloat(itemvat)).toFixed(2);
	var total_sur   = (parseFloat(surcharge) - parseFloat(itemsur)).toFixed(2);
//	alert(total_sgst);
	var netamount = (parseFloat(totalvalue)+parseFloat(total_sgst)+parseFloat(total_cgst)+parseFloat(total_igst)+parseFloat(total_cess)+parseFloat(total_vat)+parseFloat(total_sur));
	
	var netamount_final = Math.round(netamount);
	var RoundOfAmount	=	 (parseFloat(netamount_final)-parseFloat(netamount)).toFixed(2);
			//alert();
			
	$("#trdelete"+id).remove();	
	$( "#totalvalue").val(totalvalue.toFixed(2));
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
	$( "#netfinal").val(netamount_final.toFixed(2));
	$( "#round_off_amount").val(RoundOfAmount);
	scharge(totalvalue);
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
			$( "#netfinal").val(netamount_final.toFixed(2));
			
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
			$( "#counter1" ).val(0);
var vals = '<?php echo $edit_id; ?>';
AddMoreItem_test(vals);

			
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
		var form_name ='LaundrySpaOthers';
		var id = document.getElementById("id_posbilling").value;
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	function amount_cal(count,type,discount){
		

		//var regex = /[+-]?\d+(?:\.\d+)?/g;
			var match = count;
			var qty = document.getElementById("item_qty"+match).value;
			var rate = document.getElementById("item_rate"+match).value;
			var taxPercentage = document.getElementById("sumtax"+match).value;
			//var discount = document.getElementById("discount_percent"+match).value;
	 		
			var itemTotalsAmount = qty * rate;
			document.getElementById("item_amount"+match).value = itemTotalsAmount;
			
			
			
		 	var discountpercent = document.getElementById("discountpercent"+match).value;
			if(discountpercent>0){
			var discounted_calc = (itemTotalsAmount / 100) * discountpercent;
	 		var discount_total = Number(discounted_calc);
			document.getElementById("item_dis1"+match).value =discount_total;
			
			var itemTotals =	itemTotalsAmount - discount_total;
			}else{
				var itemTotals	=itemTotalsAmount;
				}
			
			//var itemTotals = (parseFloat(qty * rate))-(discount_total);	
		 	//var netamount = totals - (totals * (discount / 100));
		 	
		
	var item_cgst_percent = document.getElementById("Tax_cgst_percentage"+match).value;	
	
	var item_sgst_percent = document.getElementById("Tax_sgst_percentage"+match).value;
	
	
	var item_igst_percent = document.getElementById("Tax_igst_percentage"+match).value;
//alert(item_cgst_percent);
		if(item_sgst_percent != '' && item_cgst_percent != ''){
				//SGST
				
				var item_sgst_amount = (itemTotals * (item_sgst_percent / 100));
				document.getElementById("item_sgst"+match).value = (item_sgst_amount).toFixed(2);
				
				//CGST
				
				var item_cgst_amount = (itemTotals * (item_cgst_percent / 100));
				document.getElementById("item_cgst"+match).value = (item_cgst_amount).toFixed(2);
				
			}
			
			var item_row_total =((parseFloat(itemTotals))+(parseFloat(item_sgst_amount))+(parseFloat(item_cgst_amount))).toFixed(2);
			document.getElementById("sumtaxamount"+match).value =((parseFloat(item_sgst_amount))+(parseFloat(item_cgst_amount))).toFixed(2) ;
			document.getElementById("total"+match).value =item_row_total ;
			
			
			
				 var counter1 = document.getElementById("counter1").value;
	 			//var sub_1 = document.getElementById("net_amount_items1").value;
	 			//var sub = document.getElementById("sub_total_items1").value;

	 			//SGST - CGST
				//var sgst_first = document.getElementById("sgst1").value;
				//var cgst_first = document.getElementById("cgst1").value;
				//IGST
				//var igst_first = document.getElementById("igst1").value;

	 			//Discount Section 
	 				//var totalrate = document.getElementById("item_amount_before_discount").value;
	 				//var discounted_price = (totalrate / 100) * discount;
	 			//Discounted Price					
					//var total_discount_items = document.getElementById("total_discount_items1").value; 

	 				var total = 0; 
	 				var discount_total = 0;
	 				var sgst_total = 0; 
		 			var cgst_total = 0; 
		 			var igst_total = 0;

		 			var vat_total = 0; 
		 			var cess_total = 0; 
		 			var surcharge_total = 0;

		 			var total_rate = 0;
					
	 				for(var i=1;i<=counter1;i++){ 
	 					var qty = document.getElementById("item_qty"+i).value;
						var rate = document.getElementById("item_rate"+i).value;	 					
	 					var totalrate = document.getElementById("item_amount"+i).value;
						var value = document.getElementById("item_amount"+i).value;
	 					//var totalrate = document.getElementById("item_amount_before_discount"+i).value;
	 					var discountpercent = document.getElementById("discountpercent"+i).value; 
//alert(value);
	 					//SGST- CGST 
	 					var sgst = document.getElementById("item_sgst"+i).value; 
	 					var cgst = document.getElementById("item_cgst"+i).value;
	 					//IGST 
	 					var igst = document.getElementById("item_igst"+i).value; 

	 					if(totalrate >=1 ){
	 						total = Number(total) + Number(value);
	 						//Total Rate
	 						total_rate = Number(total_rate) + Number(totalrate);

	 						//Total Discount Section Here
	 						discounted_calc = (totalrate / 100) * discountpercent;
	 						discount_total = Number(discount_total) + Number(discounted_calc);

	 						//SGST-CGST
	 						sgst_total = Number(sgst_total) + Number(sgst);
	 						cgst_total = Number(cgst_total) + Number(cgst);
	 						//IGST
	 						igst_total = Number(igst_total) + Number(igst);
	 					} 
	 				}
	 				//total = Number(total) + Number(sub);
					
	 				
					
	 				//Total Rate Section
	 				total_rate = Number(total_rate);// + Number(sub_1);
	 				document.getElementById("sub_total_items").value =total_rate;
					//total_rate = Number(total_rate);
					
					document.getElementById("totalvalue").value =(total_rate -discount_total).toFixed(2);
	 				//Discount Total Section
	 				//discount_total = Number(discount_total) + Number(total_discount_items);
	 				document.getElementById("discount").value =(discount_total).toFixed(2);

	 				//SGST
	 				sgst_total = Number(sgst_total);//+ Number(sgst);
	 				//document.getElementById("sgst_net_amount").value =sgst_total;
	 				//document.getElementById("sgst2").value =sgst_total;
	 				document.getElementById("TotalTax_sgst").value =(Number(sgst_total)).toFixed(2);
	 				//CGST
	 				cgst_total = Number(cgst_total);// + Number(cgst);
	 				//document.getElementById("cgst_net_amount").value =cgst_total;
	 				//document.getElementById("cgst2").value =cgst_total;
	 				document.getElementById("TotalTax_cgst").value =(Number(cgst_total)).toFixed(2);
	 				//IGST
	 				igst_total = Number(igst_total);// + Number(igst);
	 				//document.getElementById("igst_net_amount").value =igst_total;
	 				//document.getElementById("igst2").value =igst_total;
	 				document.getElementById("TotalTax_igst").value =(Number(igst_total)).toFixed(2);

	 			
			
			
			
	
		 	var totalTax =((parseFloat(sgst_total))+(parseFloat(cgst_total))+(parseFloat(igst_total))).toFixed(2);
			
			//document.getElementById("total"+match).value =item_row_total
			
				var TotalNetAmount	= ((parseFloat(total_rate))+(parseFloat(totalTax))).toFixed(2);
			    var NetAmount	 = ((parseFloat(TotalNetAmount))-(parseFloat(discount_total))).toFixed(2);
				document.getElementById("netfinal").value =NetAmount; 
	 										

		//}else{}
	 	//Total Amount Adding
	 	//net_amount();

	
		
		
		}
</script>

