<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
 error_reporting(0);
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'view');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

 

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_PURCH."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}	
?>
 

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Print</li>
      </ol>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">

		 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 

              <div class="box-body" id="printTable">
              	<?php 
      				$sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
					$db->query($sql12);   
						while($row12 = $db->fetch_object()){ 
							$name= $row12->name; 
							$image = $row12->image; 
							$address = $row12->address; 
							$city = $row12->city; 
							$website_url = $row12->website_url; 
							$base_currency_code = $row12->base_currency_code; 
						} 
      			?>
      			

      			<table id="myTable1" class="table no-footer" width="100%" >
				        	<thead>
				                <tr style="font-weight: 800;"> </tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        			<h3>
					        			<?php echo "GOODS RECEIPT NOTE";
					        			 ?>
				        			</h3>
				        		</td> 
				        	</tbody>
				        	
				        </table> 

              	<table  class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10"   >
			         
			          <tbody style="border: 1">
			                <tr>
			                    <td style="width: 10%"> 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>"  alt=""> 
			                   </td> 
			                    <td  style="width: 50%"><center><h4><?php echo $name; ?></h4></center>
			                    	<center><h5><?php echo $address; ?></h5></center>
			                    	<center><h5><?php echo $city; ?></h5></center>
			                    	<center><h5><?php echo $website_url; ?></h5></center>
			                    </td> 
			                    <td  style="width: 40%">
			                    	
									<?php 
			              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
										$db->query($sql2);   
											while($row2 = $db->fetch_object()){ 
												$prefix= $row2->prefix; 
												$suffix = $row2->suffix; 
											} 
			              			?>

									<center><h4>GRN No: <?php echo stripslashes($prefix).''.stripslashes($row->po_no).''.stripslashes($suffix); ?> </h4></center>
									<center><h4>Date: <?php echo date('d-M-Y',strtotime($row->po_date)); ?></h4></center>
			              			<!--<center><h4>Credit Days: <?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_billtobe ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->credit_days);
										}
									  } 
									?>
									</h4></center>-->
									
									</h5></center>
									
			                    </td>   
			                </tr>  
			            </tbody>
			    </table> 
			    <table  class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10"   >
			    	<thead>
			    		<td style="padding: 2px;"><b><center>Supplier</center></b></td>
			    		<!--<td style="padding: 2px;"><b><center>Delivery</center></b></td>-->
			    	</thead>
			         
			          <tbody style="border: 1">
			                <tr>
			                    <td style="width: 100%;padding:2px 0px 0px 5px;"> 
			                    	
									<left><h4><?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_supplier ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->company_name).'<br>';
											echo ucfirst($resultCat->address).'<br>'; 
											echo ucfirst($resultCat->city).'-'.ucfirst($resultCat->postcode).'<br>';
											$state  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_state)."' AND table_name='state'");
											$country  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_country)."' AND table_name='country'");
											echo $state.'-'.$country.'<br>';

											echo ucfirst($resultCat->phone).'<br>';
											echo ucfirst($resultCat->mobile).'<br>';
											echo ucfirst($resultCat->email).'<br>';
										}
									  } 
									?>
			                   	</td> 
			                    <!--<td  style="width: 50%;padding:2px;"> 
			                    	<left><h4><?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_billtobe ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->company_name).'<br>';
											echo ucfirst($resultCat->address).'<br>'; 
											echo ucfirst($resultCat->city).'-'.ucfirst($resultCat->postcode).'<br>';
											$state  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_state)."' AND table_name='state'");
											$country  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_country)."' AND table_name='country'");
											echo $state.'-'.$country.'<br>';

											echo ucfirst($resultCat->phone).'<br>';
											echo ucfirst($resultCat->mobile).'<br>';
											echo ucfirst($resultCat->email).'<br>';
										}
									  } 
									?>
									</h4></left>
			                    </td>-->   
			                </tr>  
			            </tbody>
			    </table> 
			    	 
 					<table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr style="font-weight: 800;">
				                    <td style="width: 1%;padding: 1px 0px 0px 2px;">S.No</td>
				                    <td style="width: 5%;padding: 1px 0px 0px 2px;">Item Code</td> 
				                    <td style="width: 15%;padding: 1px 0px 0px 2px;">Item Description</td> 
				                    <td style="width: 3%;padding: 1px 0px 0px 2px;">Qty</td>  
				                    <td style="width: 5%;padding: 1px 0px 0px 2px;">Rate</td>  
				                    <td style="width: 5%;padding: 1px 0px 0px 2px;">Disc(%)</td>  
				                    <td style="width: 10%;padding: 1px 0px 0px 2px;">Amount</td>  
				                    <td style="width: 25%;padding: 1px 0px 0px 2px;">Taxes</td>    
				                </tr>
				            </thead>
				            <tbody>
				            	<tr>
				            		<h4 style="padding: 1px;text-align: center;">Goods Order Details</h4>
				            	</tr>
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
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent; 
							 		 $array['id_inv_indent_details'.''.$i] = $rowsID->id_inv_indent_details;
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
							 		 $i++;
								}  
								$count = 1;
								for($j=0; $j<$i; $j++){ 
									if($j == 0){
								 		$k='';
									}else{
									 	$k = $j;
									}

								?>
				            	 
				                <tr>
				                	<td style="padding:2px 0px 0px 2px;"> 
					                 	<?php echo $count++; ?> 
					                </td> 
					                <?php 
			                		//Name Get
			                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                			//Item Description Get
			                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                		?>
				                    <td style="padding:2px 0px 0px 2px;"><?php echo $item_code; ?></td>  
				                    <td style="padding:2px 0px 0px 2px;"><?php echo $item_description; ?><br><span style="font-size: 10px;">Remarks:<?php echo stripslashes($array['item_remarks'.''.$j]); ?></span></td>  
				                    <td style="padding:2px 0px 0px 2px;"><?php echo stripslashes($array['qty'.''.$j]); ?>&nbsp;&nbsp;<?php echo $array['transaction_unit'.''.$j];?></td>   
				                    <td style="padding:2px 0px 0px 2px;"><?php echo round($array['rate_per_main_unit'.''.$j],2); ?>/<?php echo $array['per_unit'.''.$j];?></td>  
				                    
				                    <td style="padding:2px 0px 0px 2px;"><?php echo stripslashes($array['discount_percent'.''.$j]); ?></td>  
				                    <td style="padding:2px 0px 0px 2px;"><?php echo stripslashes($array['item_amount'.''.$j]); ?></td>  
				                    <td style="padding:2px 0px 0px 2px;"><?php 
					                    if($array['id_mst_charges_purchase_local'.''.$j] != 0) {
						                 	$ledger_id = 1; 
						             	}else if($array['id_mst_charges_purchase_interstate'.''.$j] != 0){
						             		$ledger_id = 2; 
						                }else{
						                	$ledger_id = 0;
						                }
						                if($ledger_id == 1){ 
						                	echo 'SGST: '.' '.$array['item_sgst_amount'.''.$j].', ';
						                	echo 'CGST: '.' '.$array['item_cgst_amount'.''.$j];
						                }else if($ledger_id == 2){ 
						                	echo 'IGST: '.' '.$array['item_igst_amount'.''.$j];
						                }else{}
				                    ?></td>     
				                     
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>  
				    <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr style="font-weight: 800;">
				                    <td style="padding: 1px 0px 0px 2px;">S.No</td> 
				                    <td style="width: 35%;padding: 1px 0px 0px 2px;">Charges/Discount</td>
				                    <td style="width: 20%;padding: 1px 0px 0px 2px;">Disc(%)</td> 
				                    <td style="width: 15%;padding: 1px 0px 0px 2px;">Amount</td>
				                    <td style="width: 30%;padding: 1px 0px 0px 2px;">Taxes</td> 
				                </tr>
				            </thead>
				            <tbody>
				            	<tr>
				            		<h4 style="padding: 3px;text-align: center;">Others/Charges Details</h4>
				            	</tr>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select 
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_OTHERS_CHARGES_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
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
								$count = 1;
								for($j=0; $j<$i; $j++){ 
									if($j == 0){
								 		$k='';
									}else{
									 	$k = $j;
									}

								?>
				            	 
				                <tr>
				                	<td> 
					                
					                <?php echo $count++; ?>
					                </td> 

					                <?php if($row->id != ''){ ?>
						                <?php if($array['id_mst_charges_others'.''.$j] != 0) { ?>
						                	<style type="text/css">
						                	#others<?php echo $k;?>{
						                		display: block;
						                	}
						                	#discounts<?php echo $k;?>{
						                		display: none;
						                	}
						                	</style>
						                <?php } if($array['id_mst_charges_discounts'.''.$j] != 0) { ?>
						                	<style type="text/css">						                	
						                	#discounts<?php echo $k;?>{
						                		display: block;
						                	}
						                	#others<?php echo $k;?>{
						                		display: none;
						                	}
						                	</style>
						                <?php } ?>
					                <?php }else{ ?>
					                <style type="text/css">
					                	#others{
					                		display: none;
					                	}
					                	#discounts{
					                		display: none;
					                	}
					                </style>
					                <?php } ?>
				                    <td style="padding:1px 0px 0px 3px;">
				                    	<div id="others<?php echo $k;?>" name="others<?php echo $k;?>">
				                    		<center><h5><?php 
										  	$others  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_mst_charges_others'.''.$j])."'");
							                	echo $others; 
											?>
											</h5></center>
				                    	</div>
				                    	<div id="discounts<?php echo $k;?>" name="discounts<?php echo $k;?>">
				                    		<center><h5><?php 
										  	$discount  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_mst_charges_discounts'.''.$j])."'");
							                	echo $discount; 
											?>
											</h5></center>
				                    	</div>
				                    </td>  
				                    <td style="padding:1px 0px 0px 3px;"><?php echo stripslashes($array['others_charges_percent'.''.$j]); ?></td>  
				                    <td style="padding:1px 0px 0px 3px;"><?php  echo stripslashes($array['others_charges_amount'.''.$j]); ?></td>   
				                    <td style="padding:1px 0px 0px 3px;">
					                    <?php 
						                    
							                if($ledger_id == 1){
							                	 	
							                	echo 'SGST: '.' '.$array['others_charges_sgst_amount'.''.$j].', ';
							                	echo 'CGST: '.' '.$array['others_charges_cgst_amount'.''.$j];
							                }else if($ledger_id == 2){ 
							                	echo 'IGST: '.' '.$array['others_charges_igst_amount'.''.$j];
							                }else{}
					                    ?>
				                    	
				                    </td>   
				                     
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>

				       <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%"  >
				        	<thead>
				                <td style="width: 20%;"></td> 
			                    <td style="width: 20%;"></td>
			                    <td style="width: 15%;"></td> 
			                    <td style="width: 16%;"></td>
			                    <td></td> 
				            </thead>
				            
				        	<tbody> 
				            	<tr>
				            		<td ><b>Items Sub Total :</b> <?php echo stripslashes($row->sub_total_items); ?></td>
				            		<td><b>Items Discount :</b> <?php echo stripslashes($row->total_discount_items); ?></td>
				            		<td style="text-align: right;"><b>Items Total :</b></td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->net_amount_items); ?></td>
				            		<td ></td> 
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>Others Charges :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->others_charges_net_amount); ?></td>
				            		<td></td> 
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>SGST :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->sgst_net_amount); ?></td>
				            		<td></td> 
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>CGST :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->cgst_net_amount); ?></td>
				            		<td></td>  
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>IGST :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->igst_net_amount); ?></td>
				            		<td></td> 
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>Additional Discount :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->disc_amount_additional); ?></td>
				            		<td></td> 
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td style="text-align: right;"><b>Round Off :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->round_off_amount); ?></td>
				            		<td></td>  
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;"><b>Net Amount :</b> </td>
				            		<td style="text-align: right;"><?php echo stripslashes($row->net_amount); ?></td>
				            		<td></td> 
				            	</tr>
				            	 
				        	</tbody>
				        	
				        </table>
 

				        <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr style="font-weight: 800;">
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td style="width:25%">Prepared By</td>
				        		<td style="width:25%"></td>
				        		<td style="width:25%"></td>
				        		<td style="width:25%">Authorised By</td>
				        	</tbody>
				        	
				        </table>
		            </div> 
		        <hr> 
		        <div class="form-group col-xs-12 col-md-2 col-sm-2">
		        	
         			 <button class="btn btn-primary btn-block" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"> Print</i></button> 
         		</div>
				
				
	
<?php
if($session=='100'){
	$list = 'manageGRN.php';
	$edit = 'editPurch.php';
}

?>



<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
	  <div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-edit fa-1x"> List</i></div >
	 </a>
</div>

<?php
if($_REQUEST['eId'] != ''){
	$id=$_REQUEST['eId'];
}

//echo $id;
?>


<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $edit ?>?eId=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=1 ">
		<div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-file-o fa-1x"> Edit</i></div >
	</a>
</div>				
				
				
				
				
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

 		
<?php include_once("../includes/footer.php")?>



<script language="javascript">
       
      function printData()
        {
           var divToPrint=document.getElementById("printTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        } 

        $('button').on('click',function(){
        printData();
        });
    </script>