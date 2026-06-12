<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<?php  $session=$_GET['submenu']; 
 //  echo encryptor(decrypt, $_REQUEST['eId']);
   ?>
	<section class="content-header">
		<!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
			<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>
			<?php //echo currentNavigation()['submenu']; ?>
		</h3>-->
		  <h5 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo $add; ?> :<span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span></h5>
		<?php echo breadCrumbs(); ?>
	</section>
	<!-- Main content -->
	<section class="content">
			<hr class="br-line">
		<div class="row">
			<!-- left column -->
			<div class="col-md-12">
				<!-- general form elements -->

				<div class="nav-tabs-custom mb-0">
					<!--<div class="box-header with-border">
						<h3 class="box-title">
							<?php echo $_REQUEST['eId']==''?'Add':'Edit'?>
							<?php echo $add; ?> :<span style="color:#3c8dbc">
								<?php echo $row->mdoc_no ?>
							</span>
						</h3>
					</div>-->
					<!-- /.box-header -->
					<!-- form start -->
					<form name="indent_form" method="post" enctype="multipart/form-data" data-parsley-validate
						autocomplete="off" id="indent_form">
						<input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
						<input type="hidden" value="<?php echo encryptor(decrypt,$_REQUEST['eId']);?>" name="purchid"
							id="purchid">
						<input type="hidden" value="<?php echo $_GET['submenu'];?>" name="submenu" id="submenu" />
						<input type="hidden" value="<?php echo $_GET['session'];?>" name="session" id="session" />
						<div class="form-group has-error" align="center">
							<?php if($_SESSION['errorMsg']){?>
							<p class="help-block">
								<?php echo messageError($_SESSION['errorMsg']);?>
							</p>
							<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
							<p class="help-block">
								<?php echo messageSuc($_SESSION['successMsg']);?>
							</p>
							<?php unset($_SESSION['successMsg']);}?>
						</div>
						<div class="box-body">
							<div class="card text-dark bg-light">
								<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">General</h5>
              		</div> -->

								<div class="row">
									<?php 

//echo " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' "; ?>
									<div class="">
										<div class="form-group  col-xs-6 col-md-2 col-sm-6  form-p date-wd" >
	              			<label for="name">Document Type</label>
	              			
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" style="width: 100%">	                	  		                  	  
			                  	 	<option selected="selected" value="<?php echo $doc_type; ?>"><?php echo $add; ?></option>  
			                  	</select>	 
	              			<?php 
	              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";

								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 
									} 
	              			?>
	              			<?php if($row->id !=''){
	              				$readonly = 'disabled';
	              			}else{
	              				$readonly = '';
	              			}
	              			?>
 
	              		</div>  
	              		<div class="form-group  col-xs-6 col-md-2 col-sm-6 date-wd form-p" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="doc_date" name="doc_date" value="<?php if($_POST) echo $_POST['doc_date'];elseif($row->doc_date!='') echo date('d-m-Y',strtotime($row->doc_date));else echo date('d-m-Y');?>" onchange="hideandshow()" onclick="hideandshow()" <?php echo $readonly; ?>>

		                   <input style="display: none;"  type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="doc_date1" name="doc_date1" value="<?php if($_POST) echo $_POST['doc_date'];elseif($row->doc_date!='') echo date('d-m-Y',strtotime($row->doc_date));else echo date('d-m-Y');?>" >
		                  </div> 
	              		</div>


		                <div class="form-group col-xs-12 col-md-1 col-sm-6 p-0 form-p">
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 /*#ind{
	              				 	display: none;
	              				 }*/
	              			</style>
	              			<?php } ?>
	              			<div class=" col-xs-12 col-md-12 col-sm-12 form-p">
		              				<label for="name"><?php echo $field; ?> No</label>
		              				
			              		
		              				<input type="text" class="form-control" placeholder="PU No" id="mdoc_no2" name="mdoc_no2" value="<?php if($_POST) echo $_POST['mdoc_no2'];else echo stripslashes($row->mdoc_no);?>" readonly>
		              				
			                	</div>
	              			<div id="ind" name="ind" style="display:none;">
	              				<div class="col-xs-6 col-md-4 col-sm-6 tab-mb" style="display:none;">
	              					<label for="name">Prefix</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
		              				</div>
			                	</div>
		              			<div class="  col-xs-6 col-md-4 col-sm-6" style="display:none;">
		              				<label for="name"><?php echo $field; ?> No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="PU No" id="doc_no" name="doc_no" value="<?php if($_POST) echo $_POST['doc_no'];else echo stripslashes($row->doc_no);?>" readonly>
		              				</div> 
			                	</div>
			                	<div class=" col-xs-12 col-md-4 col-sm-12" style="display:none;">
			                		<label for="name">Suffix</label>
			                		<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-right"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Suffix" id="suffix" name="suffix" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($suffix);?>" readonly> 
		              				</div>
			                	</div>
			                </div>
			                <?php if($row->id ==''  || $prefix != ''){ ?>
			                  <style type="text/css">
			                  	#hideandshow{
			                  		display: none;
			                  	}
			                  </style>
		              	  	<?php } ?>
		                  	<div id="hideandshow" name="hideandshow">
				                <div class="form-group col-xs-12 col-md-12 col-sm-6">
				                  <label for="name">Manual PU No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input type="text" class="form-control" placeholder="Enter Manual PU No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		         

	              		<div class="form-group col-xs-12 col-md-4 col-sm-12 form-p" >
	              			<label for="name">Supplier <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fas fa-truck-loading"></i> 
						   	</div>
	              			<select class="form-control select2" name="id_mst_party_supplier" id="id_mst_party_supplier" onchange="supplier();comShow(this.value);partybilltobe(this.value);" data-parsley-required  data-parsley-errors-container="#outletError"  <?php echo $readonly; ?> style="width: 100%">
								<?php $categoryDropDown = '	<option value="">Select Supplier</option>';
								  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['id_mst_party_supplier'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_party_supplier == $resultCat->id){
											$selected = 'selected="selected"';
											$ledger = $resultCat->ledger;
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name.' - '.$resultCat->city).'</option>';
									}
								  }
								 	echo $categoryDropDown .= '</select>';
								  ?>
						<?php echo $err_deparment;?>
								</div><span id="outletError"></span>
								<?php //if($ledger == 1){ ?>

		                  	 	<input type="text" class="form-control "  id="id_mst_party_supplier1" name="id_mst_party_supplier1" value="<?php if($_POST) echo $_POST['id_mst_party_supplier'];else echo stripslashes($row->id_mst_party_supplier);?>" style="display: none;" >

		                  	 	   <div><span id="comData" style="color: red"></span></div>
	                  </div>

		             <div  class="form-group col-xs-12 col-md-4 col-sm-12">
		                  <label for="name">Supply To  <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fas fa-people-carry"></i> 
						   	</div>
			                  <select onchange="billtobe();comShow2(this.value);" class="form-control select2" name="id_mst_party_billtobe" id="id_mst_party_billtobe" style="width: 100%">
									<?php $categoryDropDown = '	<option value="">Select Supply  To </option>';
									  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){
											if($_REQUEST['id_mst_party_billtobe'] == $resultCat->id){
												$selected = 'selected="selected"';
											}elseif($row->id_mst_party_billtobe == $resultCat->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name.' - '.$resultCat->city).'</option>';
										}
									  }
									 	echo $categoryDropDown .= '</select>';
									  ?>
							<?php echo $err_deparment;?> 
							</div>
							<div><span id="comData2" style="color: red"></span></div>
		              		
		                </div>
					  
					  
				<?php //echo "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' "; ?>	 
				
<?php

if($_GET['doc_type'] != 4 && $_GET['doc_type'] != 5){ ?>

<style type="text/css">
	.display{
		display:;
	}
	
</style>
	
<?php }

?>		
				
				
				
					<div class="form-group col-xs-12 col-md-2 col-sm-6 display date-wd form-p" >  
					<?php if($_GET['doc_type'] == 4){
						$name = "Po No";
						$selectop = "Select Po";
					}else if($_GET['doc_type'] == 5){
						$name = "GRN No";
						$selectop = "Select GRN";
					}else if($_GET['doc_type'] == 12){
						$name = "PU No";
						$selectop = "Select PU";
					}


$id_inv_po_select = explode(',', $row->id_inv_po);

	//	echo "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4' ";	
					?>	
					
					
				<label for="name"> <?php echo $name ?> <font color="#FF0000">*</font></label><!-- onchange="popupshow(this.id);"  -->
				
					 <select class="form-control select2" id="id_inv_poo" name="id_inv_poo[]" multiple onchange="po_details(this,this.id);" style="width:100%"><option value=""><?php echo $selectop ?></option><?php if($_GET['doc_type'] != 5){?> <?php } if($row->id != ''){?>  <?php 
												
											
												if($_GET['doc_type'] !=5){
													$sql2 = "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `id` IN ($row->id_inv_po) ";
													//$sql2 = "SELECT * FROM `".TBL_INV_PO."` WHERE `id` IN ($id_inv_po_select) ";
													
												}else{
													$sql2 = "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4' AND `id` IN ($row->id_inv_po)";
												}
												
												
												
											$db->query($sql2);  
											$numRows= $db->num_rows();									
											while($row2 = $db->fetch_object()){ 
											
											if(in_array($row2->id,$id_inv_po_select)){
														$selected = 'selected="selected"';
													}else if($_REQUEST['id_inv_poo']){
													$selected = 'selected="selected"';
													}												
													else{
														$selected = '';
													} ?>
													
												<option <?php echo $selected ?> value="<?php echo $row2->id;?>" > <?php echo $row2->doc_no.' | '.date('d-m-Y' , strtotime(addslashes($row2->doc_date)))?></option>

											<?php	}   ?>
									
									
											<?php  }  ?>
					                  	 </select>   
										 
										
					  </div>
					  
					  
					  



						<div class="form-group col-xs-12 col-md-2 col-sm-4" id="supplier_inv_no" name="supplier_inv_no">
						  <label for="name">Supplier Invoice/ref  No</label>
						  <div class="input-group"> 
							<div class="input-group-addon">
									<i class="fa fa-list-ol"></i> 
							</div>
						  <input type="text" class="form-control" placeholder="Invoice/ref No" id="supplier_ref_no" name="supplier_ref_no" value="<?php if($_POST) echo $_POST['supplier_ref_no'];else echo stripslashes($row->supplier_ref_no); ?>" >

						  </div> 
						</div>	

						<div class="form-group col-xs-12 col-md-2 col-sm-4 date-wd form-p" id="sup_date" name="sup_date">
						  <label for="name">Supplier Date <font color="#FF0000">*</font></label>
							<div class="input-group"> 
							<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
							</div>
								
							<input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Date" id="supplier_date" name="supplier_date" value="<?php if($_POST) echo $_POST['supplier_date'];elseif($row->supplier_date!='') echo date('d-m-Y',strtotime($row->supplier_date));else echo date('d-m-Y'); ?>"> 
							</div>
						</div>
									
						
		                
						
					</div>
			  </div>


 

		               
						   <div class="form-group ">
		                  <div class=" b-box">
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
								#supplier_inv_no{
		                			display: block;
		                		}#sup_date{
		                			display: block;
		                		}
		                			#credit_day{
		                			display: flex;
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
								#supplier_inv_no{
		                			display: none;
		                		}
		                		#sup_date{
		                			display: none;
		                		}
		                			#credit_day{
		                			display: none;
		                		}
		                	</style>
		                <?php } ?>

		                <div  id="credit_day" name="credit_day">
		                 <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Credit Days :</label>
		                
	              			
		                  <input type="text" class="form-control bg-none br-none"  id="credit_days" name="credit_days" value="<?php if($_POST) echo $_POST['credit_days'];else echo stripslashes($row->credit_days); ?>" readonly>
		                  </div> 
		                </div>
		                <div  id="base_currency" name="base_currency" >
		                   <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Base Currency :</label>
		                
	              			
						   	<?php $base_currency  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($_SESSION['base_currency_code'])."'"); ?>
		                  <input type="text" class="form-control br-none bg-none" id="base_currency_code" name="base_currency_code" value="<?php echo stripslashes($base_currency); ?>" readonly>
		                  <input type="text" class="form-control" placeholder="Base Currency Code" id="base_currency_code1" name="base_currency_code1" value="<?php echo $_SESSION['base_currency_code']; ?>"  style="display: none;">
		                  </div> 
		                </div>
		                <?php if($row->id !=''){ 

		                	$transaction_currency_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->transaction_currency_code)."'");
		                }else{
		                	$transaction_currency_code  = '';
		                }?>

		                <div  id="xchange_rate" name="xchange_rate">
		                <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Exchange Rate :</label>
		                
						   	<input type="text" class="form-control br-none bg-none"  id="exchange_rate" name="exchange_rate" value="<?php if($_POST) echo $_POST['exchange_rate'];else echo stripslashes($row->exchange_rate); ?>" > 
		                  </div> 
		                </div>

		                <div  id="trans_currency" name="trans_currency">
		                 <div class="in-box d-flex">
		                  <label for="name" class="col-form-label">Transaction Currency :</label>
		                 
		                  <input type="text" class="form-control br-none bg-none" id="transaction_currency_code" name="transaction_currency_code" value="<?php if($_POST) echo $_POST['transaction_currency_code'];else echo stripslashes($transaction_currency_code); ?>" readonly>

		                  <input type="text" class="form-control" placeholder="Transaction Currency" id="transaction_currency_code1" name="transaction_currency_code1" value="<?php if($_POST) echo $_POST['transaction_currency_code'];else echo stripslashes($row->transaction_currency_code); ?>" style="display: none;">
		                  </div> 
		                </div>
						
					

								
						   </div>


		            
		              </div> <!--end of row-->
		              				
						
		                                 
 						<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>

		            <div class="row">
	              	</div> 

		        </div>

						<hr>
						<div class="box-body">
							<div class="card text-dark bg-light">
							
								<div class="row">
									<?php 
              				$sql2 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'4'."' ";
							$db->query($sql2);  
							$numRows= $db->num_rows();

								if($numRows != 0){
									while($row2 = $db->fetch_object()){ 
										$id_po_id= $row2->id; 
										$id_po_id = $id_po_id + 1; 
									}
								}
								else{
									 $id_po_id = '1'; 
								}
              			?>
									<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
										<label for="name"></label>
										<input type="text" class="form-control" id="id_inv_purch" name="id_inv_purch"
											value="<?php echo $id_po_id; ?>">
									</div>
								</div>

								<!-- The Modal -->
								<div class="modal" id="config_model">
									<div class="modal-dialog">
										<div class="modal-content" style="width: 120%;">

											<!-- Modal Header -->
											<div class="modal-header">
												<h4 class="modal-title">
													<?php echo $popup; ?>
												</h4>
												<button type="button" class="close" data-dismiss="modal"
													onclick="dismiss()">&times;</button>
											</div>

											<!-- Modal body -->
											<div class="modal-body">
												<input type="text" id="myInput" onkeyup="myFunction()"
													placeholder="Search For Item Name" title="Type In Item Name">
												<input type="checkbox" name="checkbox" id="checkbox"
													onclick="popupshow_checkbox(this.id);">
												Show All
												<table id="myTables" border="1">
												</table>
											</div>

											<!-- Modal footer -->
											<div class="modal-footer">
												<button type="button" id="test" class="btn btn-success ok"
													data-dismiss="modal" onclick="po();"><i class="fa fa-plus-circle"
														aria-hidden="true"> Insert</i></button>
												<button type="button" class="btn btn-danger" data-dismiss="modal"
													onclick="dismiss()"><i class="fa fa-times-circle"
														aria-hidden="true"> Cancel</i></button>
											</div>
										</div>
									</div>
								</div>
								<input type="text" name="inv_item_array" id="inv_item_array" value="">
								<button type="button" id="config_button" name="config_button" class="btn btn-info"
									data-toggle="modal" data-target="#config_model" style="display: none"><i
										class="fa fa-check-square-o"> </i> </button>
								<div class="row">
									<hr class="br-line">

									<div class="text-center ">
										<h6 class="tb-heads">Details</h6>
									</div>

									<div class="">
										<table id="myTable1"
											class="table table-striped table-responsive table-bordered dataTable no-footer order-list1 max-h2">
											<thead>
												<tr class="th-bg">
													<th>
														<?php echo $table_field; ?> No
													</th>
													<th style="width:10%;">Item Code</th>
													<th>Item Description</th>
													<th>Store</th>
													<th>Qty</th>
													<th>Unit</th>
													<th>Rate</th>
													<th>Per</th>
													<th>%Discount</th>
													<th>Amount</th>
												</tr>
											</thead>


											<tbody id="polist">
												<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select

								
				            	$sql2 = " SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND qty>0  AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

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
							 		 
							 		$datee = selectColumn('inv_po','doc_date'," WHERE  `id` = '".$array['id_inv_po'.''.$i] ."'");
									 
							 		$i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 } 
				            	?>
												<div class="form-group col-xs-12 col-md-6 col-sm-2"
													style="display: none;">
													<label for="name">Update Id</label>
													<input type="text" class="form-control"
														id="update_id<?php echo $k;?>" name="update_id<?php echo $k;?>"
														value="<?php echo $array['id'.''.$j];?>">

													<label for="name">Update Count</label>
													<input type="text" class="form-control" id="update_count"
														name="update_count" value="<?php echo $k;?>">
												</div>
												<?php if($row->id == ''){ $ledger_id = ''; ?>
												<style type="text/css">
													#locals {
														display: none;
													}

													#interstates {
														display: none;
													}

													#localss {
														display: none;
													}

													#interstatess {
														display: none;
													}
												</style>
												<?php } elseif($array['id_mst_charges_purchase_local'.''.$j] != 0) {
					                 $ledger_id = 1; ?>
												<style type="text/css">
													#locals<?php echo $k;

													?> {
														display: block;
													}

													#interstates<?php echo $k;

													?> {
														display: none;
													}

													#localss<?php echo $k;

													?> {
														display: block;
													}

													#interstatess<?php echo $k;

													?> {
														display: none;
													}
												</style>
												<?php } elseif($array['id_mst_charges_purchase_interstate'.''.$j] != 0) { $ledger_id = 2; ?>
												<style type="text/css">
													#locals<?php echo $k;

													?> {
														display: none;
													}

													#interstates<?php echo $k;

													?> {
														display: block;
													}

													#localss<?php echo $k;

													?> {
														display: none;
													}

													#interstatess<?php echo $k;

													?> {
														display: block;
													}
												</style>
												<?php } ?>
												<input id="ledger_id" name="ledger_id"
													value="<?php if($_POST) echo $ledger_id;else echo stripslashes($ledger_id); ?>"
													hidden="">

												<input type="hidden" id="doctype" name="doctype"
													value="<?php echo $_GET['doc_type'] ?>">

												<tr id="edittrdelete<?php echo $k;?>">
													<input hidden id="select<?php echo $k;?>"
														name="select<?php echo $k;?>">
													<td class="form-group col-md-2">

														<?php 
								
//echo "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' ";
								
		//echo "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4' AND `id` IN  ($row->id_inv_po)";
					
		//echo "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4' AND `id` IN (".$array['id_inv_po'.''.$j].")";
					
					
//echo $row->id_inv_po;
								?>


														<select class="form-control select2"
															id="id_inv_po<?php echo $k;?>"
															name="id_inv_po<?php echo $k;?>"
															onchange="popupshow(this.id);" onclick="popupshow(this.id);"
															style="width:100%" required>
															<option value="">Select
																<?php echo $table_field; ?>
															</option>

															<?php if($_GET['doc_type'] == '12' && $_GET['doc_type'] != '4'){ ?>
															<option value="na" selected>NA</option>
															<?php }else if($_GET['doc_type'] == '4' && $_GET['doc_type'] != '5' && $row->id == ''){ ?>
															<option value="na">NA</option>
															<?php	} ?>



															<?php	if($row->id != ''){
										
										if($array['id_inv_po'.''.$j] == 0){
													$name1 = 'na';
													$name2 = 'NA';
												}else{
													$name1 = $array['id_inv_po'.''.$j];
													$name2 = $array['id_inv_po'.''.$j];
												} ?>

															<?php if($array['id_inv_po'.''.$j] == 0){ ?>
															<option selected value="na"> NA </option>
															<?php } ?>


															<?php if($row->id !='' && $row->doc_type != '12'){
												
												if($_GET['doc_type'] !=5){
													$sql2 = "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `id` IN (".$array['id_inv_po'.''.$j].") ";
												}else{
													$sql2 = "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4' AND `id` IN (".$array['id_inv_po'.''.$j].")";
												}
												
												
											}else {
												if($_GET['doc_type'] !=5){
													$sql2 = "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' ";
												}else{
													$sql2 = "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4'";
												}
												
											}
											$db->query($sql2);  
											$numRows= $db->num_rows();									
											while($row2 = $db->fetch_object()){  
													$id = $row2->id;

												//if($id != $array['id_inv_po'.''.$j]){ 

												if($row->doc_type != '12'){ 
												
												if($row2->id==$array['id_inv_po'.''.$j]){
													$selected="selected='selected'";
												}else{
												$selected=""; 
												} ?>



															<option <?php echo $selected; ?> value="
																<?php echo $id;?>" >
																<?php echo $row2->doc_no .' | '.date('d-m-Y' , strtotime(addslashes($row2->doc_date)))?>
															</option>

															<?php }  } } ?>
														</select>

														<!--
										 
										 <select class="form-control select2" id="id_inv_po<?php echo $k;?>" name="id_inv_po<?php echo $k;?>"onchange="popupshow(this.id);" style="width:100%"><option value="">Select PO</option><?php if($_GET['doc_type'] != 5){?><option value="na">NA</option> <?php } if($row->id != ''){?> <option value="<?php echo $array['id_inv_po'.''.$j];?>" selected="selected"><?php echo $array['id_inv_po'.''.$j];?></option><?php if($row->id !=''){
												if($array['id_inv_po'.''.$j] == 0)	 {
													$categoryDropDown .= '<option selected="selected" value="na">NA</option>';
												}
												}?> <?php 
												if($_GET['doc_type'] !=5){
													$sql2 = "SELECT * FROM `".TBL_INV_PO."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' ";
												}else{
													$sql2 = "SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_mst_party_supplier`='".$row->id_mst_party_supplier."' AND `doc_type`='4'";
												}
											$db->query($sql2);  
											$numRows= $db->num_rows();									
											while($row2 = $db->fetch_object()){  
													$id = $row2->id; if($id != $array['id_inv_po'.''.$j]){  ?>
											<option value="<?php echo $id;?>" ><?php echo $id;?></option>
											<?php } } } ?>
					                  	 </select>
										 
										 -->



													</td>

													<input type="text" autocomplete="off"
														name="id_inv_po_details<?php echo $k;?>"
														id="id_inv_po_details<?php echo $k;?>" placeholder="ID"
														class="form-control"
														value="<?php if($_POST) echo $_POST['id_inv_po_details'];else echo stripslashes($array['id_inv_po_details'.''.$j]); ?>"
														readonly="" style="display:none;" />


													<td class="form-group col-md-2">
														<input type="text" autocomplete="off"
															name="id_inv_items<?php echo $k;?>"
															id="id_inv_items<?php echo $k;?>" placeholder="Item ID"
															class="form-control"
															value="<?php if($_POST) echo $_POST['id_inv_items'];else echo stripslashes($array['id_inv_items'.''.$j]); ?>"
															style="display:none;" />

														<?php 
				                		//Name Get
				                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			//Item Description Get
				                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                		?>
														<div id="hideshow_item_code">
															<input type="text" autocomplete="off"
																name="item_code<?php echo $k;?>"
																id="item_code<?php echo $k;?>" placeholder="Item Code"
																class="form-control" value="<?php echo $item_code; ?>"
																readonly="" />
														</div>
														<div id="hideshow_item_codes" style="display: none;">
															<select class="form-control select2"
																name="id_inv_items_po<?php echo $k;?>"
																id="id_inv_items_po<?php echo $k;?>"
																onchange="itemget(this.id)" style="width: 100%">
																<?php $categoryDropDown = '<option value="">Select Item Code</option>';
											 	

                      $sqlResult1 = "SELECT * FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND field_category IN ('Ingredients Items','Both') AND id_shop = ".$_SESSION['shop'] ." ";
                        	 $QuerySQL1	=	mysqli_query($connNew,$sqlResult1);
                        	
                        		while($sqlRow = mysqli_fetch_object($QuerySQL1)){
                        	        $list = $sqlRow->id;
                        			$string .= $list.',';
                        		}	
                        $item_list = rtrim($string,',');

												
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and  inv_items.id_mst_attributes_item_type IN ($item_list) and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_inv_items'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_inv_items'.''.$j] == $row1->id){
														$selected = 'selected="selected"';
														$item_description =  $row1->name;
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->item_code.' | '.$row1->name).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>'; 
										?>
														</div>

													</td>
													<td class="form-group col-md-2 ">
														<input type="text" autocomplete="off"
															name="item_description<?php echo $k;?>"
															id="item_description<?php echo $k;?>"
															placeholder="Item Description" class="form-control"
															value="<?php echo $item_description; ?>" readonly="" />
													</td>
													<td class="form-group col-md-1">
														<select class="form-control select2"
															name="id_mst_attributes_store<?php echo $k;?>"
															id="id_mst_attributes_store<?php echo $k;?>"
															style="width: 100%">
															<?php $categoryDropDown = '<option value="">Select</option>';
											 						 
							                   	$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and table_name='store' ",' ORDER BY `field_value`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_mst_attributes_store'] == $resultCat->id){ 
													}
													elseif($array['id_mst_attributes_store'.''.$j] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = "";
													}  
														$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
													 
												}
											  }
											 	echo $categoryDropDown .= '</select>'; 
										?>

													</td>
													<?php 
				                    	$transaction_unit = $array['transaction_unit'.''.$j];
				                    	$main_unit = $array['main_unit'.''.$j];
				                    	$alt_unit = $array['alt_unit'.''.$j];
				                    	$per_unit = $array['per_unit'.''.$j];
				                    	if($transaction_unit == $main_unit){
				                    		$qty = $array['qty'.''.$j]; 
				                    	}else{
				                    		$qty = $array['alt_qty'.''.$j]; 
				                    	}
				                    	if($per_unit == $main_unit){ 
				                    		$rate_per_main_unit = $array['rate_per_main_unit'.''.$j];
				                    	}else{ 
				                    		$rate_per_main_unit = $array['rate_per_alt_unit'.''.$j];
				                    	}
				                    ?>
													<td class="form-group  col-md-1 ">
														<input type="text" autocomplete="off" name="qty<?php echo $k;?>"
															id="qty<?php echo $k;?>" placeholder="Qty"
															onkeyup="amount_calc(this.id);"
															onclick="amount_calc(this.id);"
															class="form-control discountvalue"
															value="<?php if($_POST) echo $_POST['qty'];else echo $qty; ?>" />
													</td>
													<td class="form-group col-md-1">
														<select class="form-control select2"
															id="transaction_unit<?php echo $k;?>"
															name="transaction_unit<?php echo $k;?>"
															onchange="amount_calc(this.id);" style="width: 100%">
															<?php if($row->id != ''){?>
															<option
																value="<?php echo $array['transaction_unit'.''.$j];?>"
																selected="selected">
																<?php echo $array['transaction_unit'.''.$j];?>
															</option>
															<option value="<?php echo $array['main_unit'.''.$j];?>">
																<?php echo $array['main_unit'.''.$j];?>
															</option>
															<option value="<?php echo $array['alt_unit'.''.$j];?>">
																<?php echo $array['alt_unit'.''.$j];?>
															</option>
															<?php } ?>
														</select>
														<!-- Main Unit -->
														<input type="text" autocomplete="off"
															name="main_unit<?php echo $k;?>"
															id="main_unit<?php echo $k;?>" placeholder="Main Unit"
															class="form-control"
															value="<?php if($_POST) echo $_POST['main_unit'];else echo stripslashes($array['main_unit'.''.$j]); ?>"
															style="display:none;" />
														<!-- Alt Unit -->
														<input type="text" autocomplete="off"
															name="alt_unit<?php echo $k;?>"
															id="alt_unit<?php echo $k;?>" placeholder="Alt Unit"
															class="form-control"
															value="<?php if($_POST) echo $_POST['alt_unit'];else echo stripslashes($array['alt_unit'.''.$j]); ?>"
															style="display:none;" />
														<!-- Conversion Rate Per Unit -->
														<input type="text" autocomplete="off"
															name="conver_rate_per_unit<?php echo $k;?>"
															id="conver_rate_per_unit<?php echo $k;?>"
															placeholder="conver_rate_per_unit" class="form-control"
															value="<?php if($_POST) echo $_POST['conver_rate_per_unit'];else echo stripslashes($array['conver_rate_per_unit'.''.$j]); ?>"
															style="display:none;" />
													</td>

													<td class="form-group col-md-1">
														<input type="text" autocomplete="off"
															name="rate_per_main_unit<?php echo $k;?>"
															id="rate_per_main_unit<?php echo $k;?>" placeholder="Rate"
															class="form-control discountvalue"
															value="<?php if($_POST) echo $_POST['rate_per_main_unit'];else echo $rate_per_main_unit; ?>"
															onkeyup="amount_calc(this.id)" required />

														<input type="text" autocomplete="off"
															name="item_amount_before_discount<?php echo $k;?>"
															id="item_amount_before_discount<?php echo $k;?>"
															placeholder="Rate" class="form-control"
															value="<?php if($_POST) echo $_POST['item_amount_before_discount'];else echo stripslashes($array['item_amount_before_discount'.''.$j]); ?>"
															style="display:none;" />
													</td>
													<td class="form-group col-md-1">
														<select class="form-control select2"
															id="per_unit<?php echo $k;?>"
															name="per_unit<?php echo $k;?>"
															onchange="amount_calc(this.id);" style="width: 100%">
															<?php if($row->id != ''){?>
															<option value="<?php echo $array['per_unit'.''.$j];?>"
																selected="selected">
																<?php echo $array['per_unit'.''.$j];?>
															</option>
															<option value="<?php echo $array['main_unit'.''.$j];?>">
																<?php echo $array['main_unit'.''.$j];?>
															</option>
															<option value="<?php echo $array['alt_unit'.''.$j];?>">
																<?php echo $array['alt_unit'.''.$j];?>
															</option>
															<?php } ?>
														</select>
													</td>
													<td class="form-group col-md-1">
														<input type="text" autocomplete="off"
															name="discount_percent<?php echo $k;?>"
															id="discount_percent<?php echo $k;?>"
															placeholder="%Discount" class="form-control discountvalue"
															value="<?php if($_POST) echo $_POST['discount_percent'];else echo stripslashes($array['discount_percent'.''.$j]); ?>"
															onkeyup="amount_calc(this.id);"
															onclick="amount_calc(this.id);" style="width:100px" />
													</td>
													<td class="form-group col-md-2">
														<input type="text" autocomplete="off"
															name="item_amount<?php echo $k;?>"
															id="item_amount<?php echo $k;?>" placeholder="Amount"
															class="form-control"
															value="<?php if($_POST) echo $_POST['item_amount'];else echo stripslashes($array['item_amount'.''.$j]); ?>"
															style="width:100px" readonly />
													</td>

													<div id="taxconfig" style="display: none;">
														<!-- SGST -->
														<input type="text" autocomplete="off"
															name="id_mst_charges_sgst<?php echo $k;?>"
															id="id_mst_charges_sgst<?php echo $k;?>" placeholder="SGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['id_mst_charges_sgst'];else echo stripslashes($array['id_mst_charges_sgst'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_sgst_percent<?php echo $k;?>"
															id="item_sgst_percent<?php echo $k;?>" placeholder="SGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['item_sgst_percent'];else echo stripslashes($array['item_sgst_percent'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_sgst_amount<?php echo $k;?>"
															id="item_sgst_amount<?php echo $k;?>"
															placeholder="SGST Amount" class="form-control"
															value="<?php if($_POST) echo $_POST['item_sgst_amount'];else echo stripslashes($array['item_sgst_amount'.''.$j]); ?>" />

														<!-- CGST -->
														<input type="text" autocomplete="off"
															name="id_mst_charges_cgst<?php echo $k;?>"
															id="id_mst_charges_cgst<?php echo $k;?>" placeholder="CGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['id_mst_charges_cgst'];else echo stripslashes($array['id_mst_charges_cgst'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_cgst_percent<?php echo $k;?>"
															id="item_cgst_percent<?php echo $k;?>" placeholder="CGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['item_cgst_percent'];else echo stripslashes($array['item_cgst_percent'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_cgst_amount<?php echo $k;?>"
															id="item_cgst_amount<?php echo $k;?>"
															placeholder="CGST Amount" class="form-control"
															value="<?php if($_POST) echo $_POST['item_cgst_amount'];else echo stripslashes($array['item_cgst_amount'.''.$j]); ?>" />
														<!-- IGST -->
														<input type="text" autocomplete="off"
															name="id_mst_charges_igst<?php echo $k;?>"
															id="id_mst_charges_igst<?php echo $k;?>" placeholder="IGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['id_mst_charges_igst'];else echo stripslashes($array['id_mst_charges_igst'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_igst_percent<?php echo $k;?>"
															id="item_igst_percent<?php echo $k;?>" placeholder="IGST"
															class="form-control"
															value="<?php if($_POST) echo $_POST['item_igst_percent'];else echo stripslashes($array['item_igst_percent'.''.$j]); ?>" />

														<input type="text" autocomplete="off"
															name="item_igst_amount<?php echo $k;?>"
															id="item_igst_amount<?php echo $k;?>"
															placeholder="IGST Amount" class="form-control"
															value="<?php if($_POST) echo $_POST['item_igst_amount'];else echo stripslashes($array['item_igst_amount'.''.$j]); ?>" />
													</div>


													<?php if($k>=1){ ?>
													<td>

														<?php if($row->id != ''){?>
														<input type="text" autocomplete="off"
															name="dbid<?php echo $k;?>" id="dbid<?php echo $k;?>"
															class="form-control"
															value="<?php if($_POST) echo $_POST['dbid'];else echo stripslashes($array['id'.''.$j]); ?>"
															style="display: none;" />
														<?php } ?>
													</td>
													<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
												</tr>
												<tr id="edittrdeletes<?php echo $k;?>">
													<td class="form-group col-md-1">
														<input type="text" autocomplete="off"
															name="item_remarks<?php echo $k;?>"
															id="item_remarks<?php echo $k;?>" placeholder="Remarks"
															class="form-control"
															value="<?php if($_POST) echo $_POST['item_remarks'];else echo stripslashes($array['item_remarks'.''.$j]); ?>" />
													</td>
													<td class="form-group col-md-2">
														<div id="locals<?php echo $k;?>" name="locals<?php echo $k;?>">

															<select onchange="po_locals(this.id);"
																class="form-control select2"
																name="id_mst_charges_purchase_local<?php echo $k;?>"
																id="id_mst_charges_purchase_local<?php echo $k;?>"
																style="width:100%;">

																<?php $categoryDropDown = '<option value="">Select Tax Register</option>';
										  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1'  and charges_account = '2' and transaction_type = '1' ",' ORDER BY `name`');
										  if($db->num_rows2($resCat)){
										  	while($resultCat = $db->fetch_object2($resCat)){
												if($_REQUEST['id_mst_charges_purchase_local'] == $resultCat->id){
													$selected = 'selected="selected"';
												}elseif($array['id_mst_charges_purchase_local'.''.$j] == $resultCat->id){
													$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
											}
										  }
										 	echo $categoryDropDown .= '</select>';
										  ?>
																<?php echo $err_item_chargestax;?>
																<?php if($row->id !=''){
												$sgst = 'SGST: '.''.$array['item_sgst_amount'.''.$j];
												$cgst = 'CGST: '.''.$array['item_cgst_amount'.''.$j];
												$igst = 'IGST: '.''.$array['item_igst_amount'.''.$j];
											}else{
												$sgst = '';
												$cgst = '';
												$igst = '';
											}
											?>

														</div>

														<div id="interstates<?php echo $k;?>"
															name="interstates<?php echo $k;?>">
															<select onchange="po_interstate(this.id)"
																class="form-control select2"
																name="id_mst_charges_purchase_interstate<?php echo $k;?>"
																id="id_mst_charges_purchase_interstate<?php echo $k;?>"
																style="width:100%;">
																<?php $categoryDropDown = '<option value="">Select</option>';
											  $resCat = selectSql(TBL_CHARGES,"where id_shop='".$_SESSION['shop']."' and status = '1' and charges_account = '2' and transaction_type = '2' ",' ORDER BY `name`');
											  if($db->num_rows2($resCat)){
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($_REQUEST['id_mst_charges_purchase_interstate'] == $resultCat->id){
														$selected = 'selected="selected"';
													}elseif( $array['id_mst_charges_purchase_interstate'.''.$j] == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												}
											  }
											 	echo $categoryDropDown .= '</select>';
											  ?>
																<?php echo $err_item_chargestax;?>

														</div>
													</td>
													<td>
														<div id="localss<?php echo $k;?>"
															name="localss<?php echo $k;?>">
															<span style="color:red;font-size:14px;"
																id="s_amount<?php echo $k;?>">
																<?php echo $sgst;?>
															</span>
															<span style="color:red;font-size:14px;"
																id="c_amount<?php echo $k;?>">
																<?php echo $cgst;?>
															</span>
														</div>
														<div id="interstatess<?php echo $k;?>"
															name="interstatess<?php echo $k;?>">
															<span style="color:red;font-size:14px;"
																id="i_amount<?php echo $k;?>">
																<?php echo $igst;?>
															</span>
														</div>
													</td>

													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td></td>
													<td>
														<?php if($k>=1){ ?><img src="images/delete.gif" class="ibtnDel1"
															style="cursor:pointer;" title="Delete"
															id="ibtn<?php echo $k;?>" name="ibtn<?php echo $k;?>" />
														<?php } ?>
													</td>

													<td class="form-group col-xs-12 col-sm-2"><a class="deleteRows"></a>
													</td>
												</tr>

												<?php } ?>
												<input type="text" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" hidden="">
											</tbody>
											<tfoot>
												<tr>
													<td colspan="12" style="text-align: left;">

														<!--<input type="button" class="btn btn-sm btn-block"
															style="font-size:14px;font-weight:700" id="addrow1"
															value="Add Row" />-->
															 <a  type="button" class="btn n-btn btn-block"  id="addrow1" value="Add Row" ><span><i class="fa fa-plus"></i> Add Row</span> </a>
														<input type="button" class="btn btn-sm btn-block"
															style="font-size:14px;font-weight:700;display: none;"
															id="addrow4" value="Add" />
													</td>
												</tr>
												<tr>
												</tr>
											</tfoot>
										</table>


									</div>
								</div>

								<hr class="br-line mt-10 mb-10">
								<div class="card text-dark bg-light">
									
									<!--start of row-->
								
									<div class="row">
									<div class="col-md-8 p-0"  id="left-pane">
										<div class="container-fluid">
											<!--<div class="bg-primary text-center ">
										        <h5 style="padding: 5px;">Others Charges</h5>
									        </div>-->
									       <div class="card"> 
											<table id="myTable2" class="table table-bordered table-striped table-bordered order-list2 max-h2">
												<thead>
													<tr>
														<!--  <td>Others/Discount</td> -->
														<th style="width:21%;">Charges </th>
														<th>Percentage</th>
														<th>Amount</th>
														<th>SGST</th>
														<th>CGST</th>
														<th>IGST</th>
														<th class="p-0"><a type="button" class="btn n-btn"
													 title="Add Charges" id="addrow2"
													value="Add Row22" /><span><i class="fa fa-plus "></i></span></a>
								
                                                            </th>
													</tr>
												</thead>
												<tbody id="discountData">
													<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select
								//echo "SELECT * FROM  `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								
				            	$sql2 = "SELECT * FROM  `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_purch'.''.$i] = $rowsID->id_inv_purch;
							 		 $array['type'.''.$i] = $rowsID->type; 
							 		 $array['id_mst_charges'.''.$i] = $rowsID->id_mst_charges;
							 		 $array['id_mst_charges_discounts'.''.$i] = $rowsID->id_mst_charges_discounts; 
							 		 $array['others_discount_percent'.''.$i] = $rowsID->others_discount_percent; 
							 		 $array['others_discount_amount'.''.$i] = $rowsID->others_discount_amount; 
							 		 $array['others_charges_sgst_percent'.''.$i] = $rowsID->others_charges_sgst_percent; 
							 		 $array['others_charges_sgst_amount'.''.$i] = $rowsID->others_charges_sgst_amount; 
							 		 $array['others_charges_cgst_percent'.''.$i] = $rowsID->others_charges_cgst_percent; 
							 		 $array['others_charges_cgst_amount'.''.$i] = $rowsID->others_charges_cgst_amount; 
							 		 $array['others_charges_igst_percent'.''.$i] = $rowsID->others_charges_igst_percent; 
							 		 $array['others_charges_igst_amount'.''.$i] = $rowsID->others_charges_igst_amount;  
							 		 $array['others_charges_amount'.''.$i] = $rowsID->others_charges_amount;
							 		 $array['others_charges_percent'.''.$i] = $rowsID->others_charges_percent; 
							 		 $array['total_amount_others'.''.$i] = $rowsID->total_amount_others;  
							 		 $array['id_mst_charges_others'.''.$i] = $rowsID->id_mst_charges_others;  
							 		 $array['id_mst_charges_sgst_others'.''.$i] = $rowsID->id_mst_charges_sgst_others;  
							 		 $array['id_mst_charges_cgst_others'.''.$i] = $rowsID->id_mst_charges_cgst_others;  
							 		 $array['id_mst_charges_igst_others'.''.$i] = $rowsID->id_mst_charges_igst_others;  
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 }
				            	?>
													<div class="form-group col-xs-12 col-md-6 col-sm-2"
														style="display: none;">
														<label for="name">Update Id</label>
														<input type="text" class="form-control"
															id="chargesupdate_id<?php echo $k;?>"
															name="chargesupdate_id<?php echo $k;?>"
															value="<?php echo $array['id'.''.$j];?>">

														<label for="name">Update Count</label>
														<input type="text" class="form-control" id="chargesupdate_count"
															name="chargesupdate_count" value="<?php echo $k;?>">
													</div>
													<tr>
														<input hidden id="chargesselect<?php echo $k;?>"
															name="chargesselect<?php echo $k;?>">

														<td class="form-group col-xs-12 col-md-2 col-sm-2"
															style="display:none">

															<?php if($row->id == !''){  ?>
															<?php 
			                 		if($array['type'.''.$j] == '1'){ ?>
															<select class="form-control select2"
																name="type<?php echo $k;?>" id="type<?php echo $k;?>"
																style="width: 100%">
																<?php
										$categoryDropDown = '<option value="1">OTHERS</option>';
										echo $categoryDropDown;?>
																<option value="1">OTHERS</option>
																<option value="2">DISCOUNT</option>
															</select>
															<?php
			                 		}else if($array['type'.''.$j] == '2'){ ?>
															<select class="form-control select2"
																name="type<?php echo $k;?>" id="type<?php echo $k;?>"
																style="width: 100%">
																<?php
										$categoryDropDown = '<option value="2">DISCOUNT</option>';
			                 			echo $categoryDropDown;?>
																<option value="1">OTHERS</option>
																<option value="2">DISCOUNT</option>
															</select>
															<?php }else{
			                 			?>
															<select class="form-control select2"
																id="type<?php echo $k;?>" name="type<?php echo $k;?>"
																onchange="type_funt(this.id)" style="width: 100%">
																<option value="">Select Charges</option>
																<option value="1">OTHERS</option>
																<option value="2">DISCOUNT</option>
															</select>

															<?php } ?>

										</div>
									</div>
									<?php 
									} else{ ?>
									<select class="form-control select2" id="type<?php echo $k;?>"
										name="type<?php echo $k;?>" onchange="type_funt(this.id)" style="width: 100%">
										<option value="">select Charges</option>
										<option value="1">OTHERS</option>
										<option value="2">DISCOUNT</option>
									</select>
									<?php } ?>
									</td>
									<?php if($row->id != ''){ ?>
									<?php if($array['id_mst_charges_others'.''.$j] != 0) { ?>
									<style type="text/css">
										#others<?php echo $k;

										?> {
											display: block;
										}

										#discounts<?php echo $k;

										?> {
											display: none;
										}
									</style>
									<?php } else if($array['id_mst_charges_discounts'.''.$j] != 0) { ?>
									<style type="text/css">
										#discounts<?php echo $k;

										?> {
											display: block;
										}

										#others1<?php echo $k;

										?> {
											display: none;
										}
									</style>
									<?php }else{ ?>
									<style type="text/css">
										#others1 {
											display: none;
										}

										#discounts {
											display: none;
										}
									</style>
									<?php } }else{ ?>
									<style type="text/css">
										#others1 {
											display: none;
										}

										#discounts {
											display: none;
										}
									</style>
									<?php } ?>

									<td class="form-group col-xs-12 col-md-2 col-sm-1">
										<div id="others<?php echo $k;?>" name="others<?php echo $k;?>">
											<select class="form-control select2"
												name="id_mst_charges_others<?php echo $k;?>"
												id="id_mst_charges_others<?php echo $k;?>" style="width: 100%;"
												onchange="charges_others(this.id)" style="width: 100%">
												<?php $categoryDropDown = '<option value="">Select Others Charges</option>';
											 						 
							               
												   $sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account IN (4) ";
											   
											    //	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account IN (6,7) ";
												
												
							                   	 $db->query($sql); 
												 
							                    while($row1 = $db->fetch_object()){	
							                    	if($_REQUEST['id_mst_charges_others'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_mst_charges_others'.''.$j] == $row1->id){
														$selected = 'selected="selected"'; 
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
												
												
												if($array['id_mst_charges_others'.''.$j] != $row1->id){
													
													$sql1 = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id IN ('".$array['id_mst_charges_others'.''.$j]."') ";
													
													 $db->query($sql1); 
												 
													while($row2 = $db->fetch_object()){	
													
														$categoryDropDown .= '<option selected="selected" value="'.$row2->id.'">'.ucfirst($row2->name).'</option>';
												
													}
												
												}
												
												
												} 
												
											  
											 	echo $categoryDropDown .= '</select>';
											?>
										</div>
										<div id="discounts<?php echo $k;?>" name="discounts<?php echo $k;?>">
											<select class="form-control select2"
												name="id_mst_charges_discounts<?php echo $k;?>"
												id="id_mst_charges_discounts<?php echo $k;?>" style="width: 100%;"
												onchange="otherscharges_discount(this.id)" style="width: 100%">
												<?php $categoryDropDown = '<option value="">Select Discount Charges</option>';
											 						 
							                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
							                   	 $db->query($sql); 
							                    while($row1 = $db->fetch_object()){	

							                    	if($_REQUEST['id_mst_charges_discounts'] == $row1->id){
														$selected = 'selected="selected"';
													}elseif($array['id_mst_charges_discounts'.''.$j] == $row1->id){
														$selected = 'selected="selected"'; 
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
												} 
											  
											 	echo $categoryDropDown .= '</select>';
											?>
										</div>
									</td>
									<div id="otherschargesdiscount" name="otherschargesdiscount" style="display: none;">
										<!-- Discount -->
										<input type="text" autocomplete="off"
											name="others_discount_percent<?php echo $k;?>"
											id="others_discount_percent<?php echo $k;?>" placeholder="Discount"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_discount_percent'];else echo stripslashes($array['others_discount_percent'.''.$j]); ?>" />

										<input type="text" autocomplete="off"
											name="others_discount_amount<?php echo $k;?>"
											id="others_discount_amount<?php echo $k;?>" placeholder="Amount"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_discount_amount'];else echo stripslashes($array['others_discount_amount'.''.$j]); ?>" />
									</div>
									<div id="otherstaxconfig" id="otherstaxconfig" style="display:none;">
										<!-- SGST -->
										<input type="text" autocomplete="off"
											name="others_charges_sgst_percent<?php echo $k;?>"
											id="others_charges_sgst_percent<?php echo $k;?>" placeholder="SGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_sgst_percent'];else echo stripslashes($array['others_charges_sgst_percent'.''.$j]); ?>" />

										<input type="text" autocomplete="off"
											name="id_mst_charges_sgst_others<?php echo $k;?>"
											id="id_mst_charges_sgst_others<?php echo $k;?>" placeholder="SGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['id_mst_charges_sgst_others'];else echo stripslashes($array['id_mst_charges_sgst_others'.''.$j]); ?>" />


										<!-- CGST -->
										<input type="text" autocomplete="off"
											name="others_charges_cgst_percent<?php echo $k;?>"
											id="others_charges_cgst_percent<?php echo $k;?>" placeholder="CGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_cgst_percent'];else echo stripslashes($array['others_charges_cgst_percent'.''.$j]); ?>" />

										<input type="text" autocomplete="off"
											name="id_mst_charges_cgst_others<?php echo $k;?>"
											id="id_mst_charges_cgst_others<?php echo $k;?>" placeholder="CGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['id_mst_charges_cgst_others'];else echo stripslashes($array['id_mst_charges_cgst_others'.''.$j]); ?>" />


										<!-- IGST -->
										<input type="text" autocomplete="off"
											name="others_charges_igst_percent<?php echo $k;?>"
											id="others_charges_igst_percent<?php echo $k;?>" placeholder="IGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_igst_percent'];else echo stripslashes($array['others_charges_igst_percent'.''.$j]); ?>" />

										<input type="text" autocomplete="off"
											name="id_mst_charges_igst_others<?php echo $k;?>"
											id="id_mst_charges_igst_others<?php echo $k;?>" placeholder="IGST"
											class="form-control"
											value="<?php if($_POST) echo $_POST['id_mst_charges_igst_others'];else echo stripslashes($array['id_mst_charges_igst_others'.''.$j]); ?>" />

									</div>

									<td class="form-group col-xs-12 col-md-1 col-sm-2">
										<?php //if( $array['others_charges_percent'.''.$j] >=1 || $row->id == '') {?>
										<input type="text" autocomplete="off"
											name="others_charges_percent<?php echo $k;?>"
											id="others_charges_percent<?php echo $k;?>" placeholder="Percentage"
											class="form-control discountvalue"
											value="<?php if($_POST) echo $_POST['others_charges_percent'];else echo stripslashes($array['others_charges_percent'.''.$j]); ?>"
											onkeyup="subtotal_calc(this.id)" />
										<?php //} ?>
									</td>

									<td class="form-group col-xs-12 col-md-2  col-sm-2">

										<input type="text" autocomplete="off"
											name="others_charges_amount<?php echo $k;?>"
											id="others_charges_amount<?php echo $k;?>" placeholder="Amount"
											class="form-control discountvalue"
											value="<?php if($_POST) echo $_POST['others_charges_amount'];else echo stripslashes($array['others_charges_amount'.''.$j]); ?>"
											onkeyup="charges_amount_calc(this.id)"
											onclick="charges_amount_calc(this.id)" />

									</td>
									<td class="form-group col-xs-12 col-md-2  col-sm-2">
										<input type="text" autocomplete="off"
											name="others_charges_sgst_amount<?php echo $k;?>"
											id="others_charges_sgst_amount<?php echo $k;?>" placeholder="SGST Amount"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_sgst_amount'];else echo stripslashes($array['others_charges_sgst_amount'.''.$j]); ?>"
											readonly />
									</td>
									<td class="form-group col-xs-12 col-md-2  col-sm-2">
										<input type="text" autocomplete="off"
											name="others_charges_cgst_amount<?php echo $k;?>"
											id="others_charges_cgst_amount<?php echo $k;?>" placeholder="CGST Amount"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_cgst_amount'];else echo stripslashes($array['others_charges_cgst_amount'.''.$j]); ?>"
											readonly />
									</td>
									<td class="form-group col-xs-12 col-md-2  col-sm-2">
										<input type="text" autocomplete="off"
											name="others_charges_igst_amount<?php echo $k;?>"
											id="others_charges_igst_amount<?php echo $k;?>" placeholder="IGST Amount"
											class="form-control"
											value="<?php if($_POST) echo $_POST['others_charges_igst_amount'];else echo stripslashes($array['others_charges_igst_amount'.''.$j]); ?>"
											readonly />
									</td>
									<td class="form-group col-xs-12 col-md-2 col-sm-2" style="display: none;">
										<input type="text" autocomplete="off" name="total_amount_others<?php echo $k;?>"
											id="total_amount_others<?php echo $k;?>" placeholder="Total"
											class="form-control"
											value="<?php if($_POST) echo $_POST['total_amount_others'];else echo stripslashes($array['total_amount_others'.''.$j]); ?>"
											readonly />
									</td>
									

									<?php if($k>=1){ ?>
									<td>

										<img src="images/delete.gif" class="ibtnDel2" style="cursor:pointer;"
											title="Delete" id="ibtns<?php echo $k;?>" name="ibtns<?php echo $k;?>" />

										<?php if($row->id != ''){?>
										<input type="text" autocomplete="off" name="dbid2<?php echo $k;?>"
											id="dbid2<?php echo $k;?>" class="form-control"
											value="<?php if($_POST) echo $_POST['dbid2'];else echo stripslashes($array['id'.''.$j]); ?>"
											style="display: none;" />
										<?php } ?>
									</td>
									<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
									<td class="form-group col-xs-12  col-sm-2"><a class="deleteRow"></a></td>
									</tr>
									<?php } ?>

									</tbody>
									<tfoot>
										<input type="text" name="counter2" id="counter2" value="<?php echo 
				                    $counts; ?>" hidden="">
										<!--<tr>
											<td colspan="6" style="text-align: left;">
												<input type="button" class="btn btn-sm btn-block"
													style="font-size:14px;font-weight:700" id="addrow2"
													value="Add Row22" />
											</td>
										</tr>-->
										<tr>
										</tr>
									</tfoot>
									</table>
							     	</div><!--end of card-->
									<!--end of charges-->
									<!--start of dicount coupan-->

										<div class="card ">
											<div class="container-fluid mb-10 p-0">
												<div class="col-xs-12 col-md-3 col-sm-2">
													<label>Discount Scheme Apply</label>
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-circle-o-notch"></i>
														</div>
														<select class="form-control select2"
															name="id_mst_charges_discounts_items"
															id="id_mst_charges_discounts_items" style="width: 100%;"
															onchange="discount_all(this.id)" style="width: 100%">
															<?php $categoryDropDown = '<option value="">Select Discount</option>';
								 						 
				                   	$sql = "SELECT mst_charges.*FROM mst_charges WHERE mst_charges.id_shop = '".addslashes($_SESSION['shop'])."' AND mst_charges.charges_account = '6' ";
				                   	 $db->query($sql); 
				                    while($row1 = $db->fetch_object()){	

				                    	if($_REQUEST['id_mst_charges_discounts_items'] == $row1->id){
											$selected = 'selected="selected"';
										}elseif($row->id_mst_charges_discounts_items == $row1->id){
											$selected = 'selected="selected"'; 
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$row1->id.'">'.ucfirst($row1->name).'</option>';
									} 
								  
								 	echo $categoryDropDown .= '</select>';
								?>
													</div>
												</div>
												<div class="col-xs-12 col-md-3 col-sm-2">
													<label>Percentage</label>
													<div class="input-group">
														<div class="input-group-addon">
															<i class="fa fa-percent"></i>
														</div>
														<input type="text" class="form-control"
															id="discount_percent_items" name="discount_percent_items"
															placeholder="Percentage"
															value="<?php echo $row->discount_percent_items;?>" readonly>
													</div>
												</div>
												<div class="col-xs-12 col-md-3 col-sm-2">
													<label>Apply</label><br>
													<button type="button" id="button" class="btn o-btn"
														onclick="apply_percentage(this.id)">Apply</button>
												</div>
											</div>
										</div><!--end of discount-->
										<!--start of terms and condition-->
										
											        	<!-- Terms And Conditions -->
		        	 <div class="card text-dark bg-light border">
	              
			            <div class="container-fluid p-0">
			            	<table id="myTable3" class="table table-bordered table-striped order-list3 mt-10 max-h2">
					            <thead>
					                <tr>
					                    <th>Terms & Condition</th> 
					                    <th style="width:4%;"></th>
					                </tr>
					            </thead>
					            <tbody>
					            	<?php
					            	$k='';
					            	if($row->id ==''){
									 	$i=1;
									 }else{
									 	$i=0;
									 } 
					            	//Indent Details Here First Row Only Select
					            	$sql2 = "  SELECT * FROM  `".TBL_INV_TERMS_AND_CONDITIONS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
									$db->query($sql2);
									$numRows= $db->num_rows();
									while($rowsID = $db->fetch_object()){
								 		 $array['id'.''.$i] = $rowsID->id;
								 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
								 		 $array['terms'.''.$i] = $rowsID->terms; 
								 		  $i= $i+1;
									}  

if($numRows==0){ $i=1;
										 }
									for($j=0; $j<$i; $j++){ 
									 if($j == 0){
									 	$k='';
									 }else{
									 	$k = $j;
									 } 
									 
										
					            	?>
					            	<div class="form-group col-xs-12 col-md-6 col-sm-2 mb-0"  >
					                  <label for="name" style="display: none">Update Id</label>
					                  <input type="hidden" class="form-control" id="termsupdate_id<?php echo $k;?>" name="termsupdate_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

					                  <label for="name" style="display: none">Update Count</label>
					                  <input type="hidden" class="form-control" id="termsupdate_count" name="termsupdate_count" value="<?php echo $k;?>">
					                  	<tr>
						                  <td  class="form-group col-xs-12 col-md-12 col-sm-2">
						                  	<input type="text"  autocomplete="off"  name="terms<?php echo $k;?>" id="terms<?php echo $k;?>" placeholder="Terms And Conditions"  class="form-control" value="<?php if($_POST) echo $_POST['terms'];else echo stripslashes($array['terms'.''.$j]); ?>" />
						                 </td>	
                                         
                                         
						                  <td>	

						                  	 <a  type="button" title="Add Row" class="btn n-btn abtn" style="font-size:14px;font-weight:700" id="addrow3" value="Add Row"> <span style="font-size:14px;font-weight:700"><i class="fa fa-plus"></i> </span> </a>
						                  </td>	

						                   </a>
						                  	<?php 
						                  	if($row->id ==''){
						                	 	$counts = 0;
						                	}else{
						                	 	$counts = $numRows - 1;
						                	}
						                	?> 
						                  
						                	 <td class="form-group col-xs-12  col-sm-2"><a class="deleteRow2" ></a></td>
						                	
					              		</tr>
					              	<?php } ?>
					              	<input type="text" name="counter3" id="counter3" value="<?php echo $counts; ?>" hidden>
					              		
				                	</div>
					            </tbody>
					           </table>
					       </div>
					   </div>
					   <!--end of terms and condition-->

							
							</div>
							<!--end of col-->
								</div>
							
							<div class="col-md-4 col-xs-12" id="right-pane"> 
							<!-- Total Amount Section -->
							<div class="card text-dark bg-light">
								<!--<div class="bg-primary text-center ">
									<h5 style="padding: 5px;">Total Amount</h5>
								</div>-->
					    <div class="row">
                  
                     <div class="form-group col-xs-12 col-md-12 col-sm-12 mb-3">
                        <label for="name">Sub Total</label>
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fa fa-plus"></i>
                  </div>
                  <?php if($row->id == ''){
                    $sub_total_items = 0;
                  }else{
                    $sub_total_items = $row->sub_total_items;
                  }
                  ?>
                          <input type="text" class="form-control" placeholder="Sub Total" id="sub_total_items" name="sub_total_items" value="<?php if($_POST) echo $_POST['sub_total_items'];else echo stripslashes($sub_total_items); ?>" readonly>
                         <input type="text" class="form-control" placeholder="Sub Total" id="sub_total_items1" name="sub_total_items1" value="<?php if($_POST) echo $_POST['sub_total_items1'];else echo stripslashes($row->sub_total_items1); ?>" style="display: none;">
                        </div> 
                        </div> 
                       <div class="form-group col-xs-12 col-md-6 col-sm-6" style="margin-bottom: 6px;">
                        <label for="name">Discount</label>
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fa fa-percent"></i>
                  </div>
                        <input type="text" class="form-control" placeholder="Discount" id="total_discount_items" name="total_discount_items" value="<?php if($_POST) echo $_POST['total_discount_items'];else echo stripslashes($row->total_discount_items); ?>" readonly>
                        <input type="text" class="form-control" placeholder="total_discount_items" id="total_discount_items1" name="total_discount_items1" value="<?php if($_POST) echo $_POST['total_discount_items1'];else echo stripslashes($row->total_discount_items1); ?>" style="display: none;"> 
                        </div> 
                      </div>  

                     <div class="form-group  col-xs-12 col-md-6 col-sm-6 mb-0">
                        <label for="name">Total</label>
                        <div class="input-group"> 
                        <div class="input-group-addon">
                <i class="fa fa-plus"></i>
                  </div>
                        <input type="text" class="form-control" placeholder="Total" id="net_amount_items" name="net_amount_items" value="<?php if($_POST) echo $_POST['net_amount_items'];else echo stripslashes($row->net_amount_items); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="Total" id="net_amount_items1" name="net_amount_items1" value="<?php if($_POST) echo $_POST['net_amount_items1'];else echo stripslashes($row->net_amount_items1); ?>" style="display: none;">  
                        </div> 
                      </div>
                  </div>
                  <div class="row">
                      <div class="form-group col-xs-12 col-md-6 col-sm-6"><label for="name">Others Charges</label></div>
                        <div class="form-group mb-0 col-xs-12 col-md-6 col-sm-6">
                        
                        <div class="input-group"> 
                        <div class="input-group-addon">
                      <i class="far fa-bookmark"></i>
                  </div>
                        <input type="text" class="form-control" placeholder="0" id="others_charges_net_amount" name="others_charges_net_amount" value="<?php if($_POST) echo $_POST['others_charges_net_amount'];else echo stripslashes($row->others_charges_net_amount); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="others_charges_net_amount" id="others_charges_net_amount1" name="others_charges_net_amount1" value="<?php if($_POST) echo $_POST['others_charges_net_amount1'];else echo stripslashes($row->others_charges_net_amount1); ?>" style="display: none;">

                        </div> 
                      </div>
                  </div>

                  <!-- SGST -->
                  <div class="row">
                     <?php if($row->id == ''){
                    $sgst_net_amount = 0;
                  }else{
                    $sgst_net_amount = $row->sgst_net_amount;
                  }
                  ?>
                     
                       <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">SGST</label></div>
                      <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6">
                       
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fa fa-caret-square-o-down"></i>
                  </div>
                        <input type="text" class="form-control" placeholder="SGST" id="sgst_net_amount" name="sgst_net_amount" value="<?php if($_POST) echo $_POST['sgst_net_amount'];else echo stripslashes($sgst_net_amount); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="SGST" id="sgst1" name="sgst1" value="<?php if($_POST) echo $_POST['sgst1'];else echo stripslashes($row->sgst1); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="SGST" id="sgst2" name="sgst2" value="<?php if($_POST) echo $_POST['sgst2'];else echo stripslashes($row->sgst2); ?>" style="display: none;"> 

                        <!-- OC SGST--> 
                        <input type="text" class="form-control" placeholder="SGST" id="oc_sgst_total" name="oc_sgst_total" value="<?php if($_POST) echo $_POST['oc_sgst_total'];else echo stripslashes($row->oc_sgst_total); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="SGST" id="oc_sgst1" name="oc_sgst1" value="<?php if($_POST) echo $_POST['oc_sgst1'];else echo stripslashes($row->oc_sgst1); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="SGST" id="oc_sgst2" name="oc_sgst2" value="<?php if($_POST) echo $_POST['oc_sgst2'];else echo stripslashes($row->oc_sgst2); ?>" style="display: none;">  
                        </div> 
                      </div> 
                  </div>

                  <!-- CGST -->
                  <div class="row">
                     <?php if($row->id == ''){
                    $cgst_net_amount = 0;
                  }else{
                    $cgst_net_amount = $row->cgst_net_amount;
                  }
                  ?>
                       <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">CGST</label></div>
                      <div class="form-group  mb-3 col-xs-12 col-md-6 col-sm-6"> 
                        <div class="input-group"> 
                        <div class="input-group-addon">
                        <i class="fa fa-caret-square-o-left"></i>
                        </div>
                        <input type="text" class="form-control" placeholder="CGST" id="cgst_net_amount" name="cgst_net_amount" value="<?php if($_POST) echo $_POST['cgst_net_amount'];else echo stripslashes($cgst_net_amount); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="CGST" id="cgst1" name="cgst1" value="<?php if($_POST) echo $_POST['cgst1'];else echo stripslashes($row->cgst1); ?>" style="display: none;"> 
                        <input type="text" class="form-control" placeholder="CGST" id="cgst2" name="cgst2" value="<?php if($_POST) echo $_POST['cgst2'];else echo stripslashes($row->cgst2); ?>" style="display: none;"> 

                        <!-- OC CGST--> 
                        <input type="text" class="form-control" placeholder="CGST" id="oc_cgst_total" name="oc_cgst_total" value="<?php if($_POST) echo $_POST['oc_cgst_total'];else echo stripslashes($row->oc_cgst_total); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="CGST" id="oc_cgst1" name="oc_cgst1" value="<?php if($_POST) echo $_POST['oc_cgst1'];else echo stripslashes($row->oc_cgst1); ?>" style="display: none;"> 
                        <input type="text" class="form-control" placeholder="CGST" id="oc_cgst2" name="oc_cgst2" value="<?php if($_POST) echo $_POST['oc_cgst2'];else echo stripslashes($row->oc_cgst2); ?>" style="display: none;">
                        </div> 
                      </div> 
                  </div>

                  <!-- IGST -->
                  <div class="row">
                     <?php if($row->id == ''){
                    $igst_net_amount = 0;
                  }else{
                    $igst_net_amount = $row->igst_net_amount;
                  }
                  ?>
                     
                       <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">IGST</label></div>
                       <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fa fa-caret-square-o-right"></i>
                  </div>
                        <input type="text" class="form-control" placeholder="IGST" id="igst_net_amount" name="igst_net_amount" value="<?php if($_POST) echo $_POST['igst_net_amount'];else echo stripslashes($igst_net_amount); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="IGST" id="igst1" name="igst1" value="<?php if($_POST) echo $_POST['igst1'];else echo stripslashes($row->igst1); ?>" style="display: none;"> 
                        <input type="text" class="form-control" placeholder="IGST" id="igst2" name="igst2" value="<?php if($_POST) echo $_POST['igst2'];else echo stripslashes($row->igst2); ?>" style="display: none;">

                        <!-- OC IGST--> 
                        <input type="text" class="form-control" placeholder="IGST" id="oc_igst_total" name="oc_igst_total" value="<?php if($_POST) echo $_POST['oc_igst_total'];else echo stripslashes($row->oc_igst_total); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="IGST" id="oc_igst1" name="oc_igst1" value="<?php if($_POST) echo $_POST['oc_igst1'];else echo stripslashes($row->oc_igst1); ?>" style="display: none;">
                        <input type="text" class="form-control" placeholder="IGST" id="oc_igst2" name="oc_igst2" value="<?php if($_POST) echo $_POST['oc_igst2'];else echo stripslashes($row->oc_igst2); ?>" style="display: none;">  
                        </div> 
                      </div>
                  </div>

                  <!-- Additional Discount -->
                  <div class="row">
                      
                     <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Misc Discount Amount </label></div>
                       <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
                        <div class="input-group"> 
                        <div class="input-group-addon">

                  <i class="fas fa-tag"></i>  
                  </div>
                        <input type="text" class="form-control" placeholder="0" id="disc_amount_additional" name="disc_amount_additional" value="<?php if($_POST) echo $_POST['disc_amount_additional'];else echo stripslashes($row->disc_amount_additional); ?>" readonly style="text-align:right;">
                        <input type="text" class="form-control" placeholder="disc_amount_additional" id="disc_amount_additional1" name="disc_amount_additional1" value="<?php if($_POST) echo $_POST['disc_amount_additional1'];else echo stripslashes($row->disc_amount_additional1); ?>" style="display: none;">
                        </div> 
                      </div>
                  </div> 
                  <!-- Round Amount -->
                  <div class="row">
                      
                     <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Round Off</label></div>
                       <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fas fa-tag"></i>  
                  </div>
                        <input type="text" class="form-control" placeholder="0" id="round_off_amount" name="round_off_amount" value="<?php if($_POST) echo $_POST['round_off_amount'];else echo stripslashes($row->round_off_amount); ?>" readonly style="text-align:right;">
                        </div> 
                      </div>
                  </div>

                  <!-- Net Amount -->
                  <div class="row">
                      
                      <div class="form-group col-xs-12 col-md-6 col-sm-6"> <label for="name">Net Amount</label></div>
                       <div class="form-group mb-3 col-xs-12 col-md-6 col-sm-6"> 
                        <div class="input-group"> 
                        <div class="input-group-addon">
                  <i class="fas fa-tag"></i>  
                  </div>
                        <input type="text" class="form-control" placeholder="0" id="net_amount" name="net_amount" value="<?php if($_POST) echo $_POST['net_amount'];else echo stripslashes($row->net_amount); ?>" readonly style="text-align:right;">
                        </div> 
                      </div>
                  </div> <!--end of row-->
							</div>
						</div>
					</div><!--end of col-->
				</div><!--end of row-->


				  <hr class="br-line mb-10">   

						<?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>
						  <div class="row"> 	            	
					             <div class="form-group mb-0 col-xs-12 col-md-6 col-sm-2"> 
		                      	<label for="status">Status : </label> 
			                      <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                     name="status" id="status" /> Active
						             	<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
						             	name="status"  id="status"   /> Inactive
						          	 <?php echo $err_status;?>
							 
		                          </div>  
		         
                           </div> <!--end of row-->

				</div>

					<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn" name="Save">
				<a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("<?=$redirect_page;?>"); '><i class="far fa-window-close" aria-hidden="true"></i> Close</a>
				

				
 

				<?php if($row->date_created){?>
				<div class="row mt-10">
					<div class="form-group col-md-3">
						<label for="date_created">Date Created</label>
						<input type="text" disabled="disabled" class="form-control" id="date_created"
							value="<?php echo stripslashes(dateformat($row->date_created));?>">
					</div>

					<div class="form-group col-md-3">
						<label for="last_modified_by">Created By</label>
						<?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
						<input type="text" disabled="disabled" class="form-control" id="last_modified_by"
							value="<?php echo stripslashes($sqlUserDetail);?>">
					</div>

					<div class="form-group col-md-3">

						<label for="last_modified">Last Updated</label>
						<input type="text" disabled="disabled" class="form-control" id="last_modified"
							value="<?php echo stripslashes(dateformat($row->last_modified));?>">
					</div>

					<div class="form-group col-md-3">
						<label for="last_modified_by">Last Updated By</label>
						<?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
						<input type="text" disabled="disabled" class="form-control" id="last_modified_by"
							value="<?php echo stripslashes($sqlUserDetail);?>">
					</div>
				</div><!--end of row-->
				 <a type='button' value='Alteration History' class="btn o-btn "  onclick="audittrial(this.value);" style="float:right"> 
					   <i class="fas fa-history"></i> Alteration History</a>
				<?php } ?>

			</div>


			<!-- Another Modal -->
			<div class="modal fade" id="anotherModal" tabindex="-1" role="dialog" aria-labelledby="anotherModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
									aria-hidden="true">&times;</span></button>
							<!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
							<label class="modal-title" id="roomtitle1" style="font-size:22px;">
								<?php echo $add; ?>
							</label>
						</div>
						<div class="modal-body">
							<table class="table table-bordered table-striped">

								<div style="text-align:center;font-weight:600;font-size:15px">

									<div id="mge"></div>
									<br />
									<input type='submit' value="<?=($_REQUEST['eId']==''?'Add':'Edit')?>"
										class="btn btn-success" onclick="yes();" name="Save">
									<input type='button' value="No" class="btn btn-success" onclick="nosave();"
										name="no">
									<input type='hidden' value="" id="another" name="another">
								</div>

							</table>
						</div>
					</div>
				</div>
			</div>
			<!-- End Another Modal -->


			<!-- /.box-body -->
			<div class="box-footer">
				<!--<input type='submit' value='<?=($_REQUEST[' eId']=='' ?'Save':'Edit')?>' class="btn btn-success"
				name="Save">
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='button' value='Cancel' class="btn btn-success"
					onclick='location.replace("<?=$redirect_page;?>"); '>
				&nbsp;&nbsp;&nbsp;&nbsp; <input type='button' value='Another' class="btn btn-success"
					onclick="saveornot();">

				<input type='button' value='Audit Trail' class="btn btn-success" onclick="audittrial(this.value);"
					style="float:right">-->

				<?php if($row->id !=''){?>
				<!--   <a href="editPurch.php?doc_type=<?=$_REQUEST['doc_type']?>&submenu=<?php echo $_GET['submenu']?>&session=<?php echo $_GET['session']?>" type="button" class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Another <?php echo $add; ?></i></a>  -->
				<?php                   		 
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 

	                  		$custom_print_file = $row1->custom_print_file;	                  		 
		                  	if($row->doc_type == '4'){ 
			                  	if($custom_print_file !=''){
			                  		$print = $custom_print_file;
			                  	}else{
			                  		$print = 'printGRN.php';
			                  	}
			                }elseif($row->doc_type == '5'){
	              	 			 if($custom_print_file !=''){
			                  		$print = $custom_print_file;
			                  	}else{
			                  		$print = 'printPurchasebill.php';
			                  	}
	              	 		}elseif($row->doc_type == '12'){
	              	 			 if($custom_print_file !=''){
			                  		$print = $custom_print_file;
			                  	}else{
			                  		$print = 'printPurchasebill.php';
			                  	}
	              	 		}
	                  	} 
	                  		                  	
                  	?>
				<!--&nbsp;&nbsp;&nbsp;&nbsp; <a
					href="<?php echo $print; ?>?eId='<?php echo $_GET['eId']; ?>'&doc_type=<?php echo $_GET['doc_type'] ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>&action=edit&page=<?php $_REQUEST['page']?>"
					type="button" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"> Print</i></a>-->

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

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
						aria-hidden="true">&times;</span></button>
				<!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
				<label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
			</div>
			<div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
				<table class="table table-bordered table-striped">
					<div style="text-align:center;font-weight:600;font-size:15px"> Bill No -
						<?php echo $row->mdoc_no ?>
					</div>
					<thead>
						<tr>
							<th>Details</th>
						</tr>
					</thead>

					<tbody id="roombutton">

					</tbody>
				</table>
			</div>

			<div class="modal-footer" style="background-color: #e4e4e4;color: #fff;text-align:center">
				<button type="button" class="btn btn-danger" data-dismiss="modal"> <span
						class="glyphicon glyphicon-off"></span> Close</button>
			</div>
		</div>
	</div>
</div>
<!-- End Audit trail Modal -->