<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
 



 

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_PO."`
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
     <!-- <h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>-->
      <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Print</li>
      </ol>
    </section>
    <!-- Main content -->
	
	
    <section class="content print-con pt-0">
      <div class="row">
      	
				
				

<?php
if($session=='98'){
	$list = 'managePO.php';
	$edit = 'editPO.php';
}
if($session=='97'){
	$list = 'manageIndentPO.php';
	$edit = 'editIndentPO.php';
}

?>





<?php
if($_REQUEST['eId'] != ''){
	$id=$_REQUEST['eId'];
}

//echo $id;
?>


			
		

      	 <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		  <a title="Add" href="<?php echo $edit; ?>?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>&doc_type=3">
		  	<!--editPurch.php?doc_type=<?php echo $doc_type; ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>-->
		    <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
		  </a>
		</div>

	
       <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		
				<a title="Edit" href="<?php echo $edit ?>?eId=<?=encryptor(encrypt, $row->id)?>&submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&doc_type=3&print=0 ">
			

				<div class="btn c-btn" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
			</a>
		</div>	
	<!--	<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $edit ?>?eId=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=1 ">
		<div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-file-o fa-1x"> Edit</i></div >
	</a>
</div>	-->

		<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
			<a title="List" href="<?php echo $list ?>?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
			  <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
			 </a>
		</div>
		<!--<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
	  <div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-edit fa-1x"> List</i></div >
	 </a>
</div>-->
     
        <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		        	
         			 <button title="Print" class="btn c-btn" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print</button> 
         		</div>

			
				<div class="form-group col-xs-12 col-sm-4 col-md-3  ">
				<div class="btn-group " title="Export" style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
				    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
				    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
				    <ul class="dropdown-menu " role="menu">
				      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
				      <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
				       <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="images/jpg.png" width="20px">&nbsp;JPG</a></li>
				    </ul>
				  </div>

				<div class="btn-group s-btt" > <a type="button" title="Share" class="btn c-btn2" href="javascript:void(0)"><i class="fas fa-share"></i> Share</a>
				    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
				    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
				    <ul class="dropdown-menu " role="menu">
				      <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="images/gmail.png" width="20px">&nbsp;Email</a></li>
				      <li><a title="Share on WhatsApp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="images/whatsapp.png" width="20px">&nbsp;Whatsapp</a></li>
				    </ul>
				  </div>
			   </div>
			<!--end of buttons-->
		</div>
			<!--end of row-->


<!--second row start-->
   <div class="row">
		
        <!-- left column -->
        <div class="col-md-7 col-lg-7">
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
      			
      			<table  class="table dataTable  no-footer table-responsive out-table" width="100%" border="0" cellspacing="0" cellpadding="10" style="border:0.4px solid #000;">
      			  <tr>

      		     	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">
      		     		<table id="myTable1" class="table dataTable table-responsive" width="100%" >
				        	<thead>
				                <tr> </tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        			<p style="font-family: sans-serif;margin:0;padding:0px!important;font-size:11px;"><b>
					        			<?php echo "PURCHASE ORDER";
					        			 ?>
					        			</b>
				        			</p>
				        		</td> 
				        	</tbody>
				        	
				        </table> 
				     </td>
				  </tr> 

			     <tr>
			       <td style="border-bottom: 0.4px solid #000;padding:0px!important;">

                  	<table  class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10"   >
			         
			          <tbody >
			                <tr>
			                	<?php
			                	 if($image!=''){
			                	 	//echo $image;
			                	 	$dnone='table-cell';
			                	 } else{
			                	 	$dnone='none';
			                	 }
			                	?>
			                    <td class="pm" style="display:<?=$dnone;?>;display:none;width:20%;border-right:.4px solid #000!important;"> 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>"  width="137px" alt=""> 
			                   </td> 
			                    <td  class="pm" style="width:80%;font-family: sans-serif;font-size:11px;">
			                    	<center><p style="font-size:11px;font-weight:600;"><?php echo $name; ?></p></center>
			                    	<center><p ><?php echo $address; ?></p></center>
			                    	<center><p ><?php echo $city; ?></p></center>
			                    	<center><p><?php echo $website_url; ?></p></center>
			                    </td> 
			                 
			                </tr>  
			            </tbody>
			       </table> 
			      </td>
			     </tr>
			       <tr>
			       <td style="border-bottom: 0.4px solid #000;padding:0px!important;">

                  	<table  class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10"   >
			         
			          <tbody >
			                <tr>
			                		<?php 
			              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
			              					//$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  AND `id`='4' ";
			              				//print_r($sql2);
										$db->query($sql2);   
											while($row2 = $db->fetch_object()){ 
												$prefix= $row2->prefix; 
												$suffix = $row2->suffix; 
											} 
			              			?>
			                	
			                    <td class="pm" style="width:40%;font-size:11px;font-family: sans-serif;padding:3px!important;padding-left:20px!important;">
			                    	
								

								<p style="padding:0px!important;margin:0;"><b>Purchase No:</b> <?php echo stripslashes($prefix).''.stripslashes($row->doc_no).''.stripslashes($suffix); ?> </p>
									
									
								
									
			                    </td>   
			                     <td class="pm" style="width:30%;font-size:11px;font-family: sans-serif;padding:3px!important;">
			                    	
								
								
									<p style="padding:0px!important;margin:0;"><b>Date: </b><?php echo date('d-M-Y',strtotime($row->doc_date)); ?></p>
			              		
									
								
									
			                    </td>
			                     <td class="pm" style="text-align:center;width:35%;font-size:11px;font-family: sans-serif;padding:3px!important;">
			                    	
								
			              		 <p style="padding:0px!important;margin:0;"><b>Credit Days: </b><?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_billtobe ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->credit_days);
										}
									  } 
									?>
									</p>
									
								
									
			                    </td>
			                </tr>  
			            </tbody>
			       </table> 
			      </td>
			     </tr>


			    <tr>
			     <td  style="padding:0px!important;">


			    <table  class="table dataTable table-striped no-footer " width="100%" border="0" cellspacing="0" cellpadding="10"   >
			    	<thead >
			    		<td class="pm" style="width:50%;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;padding:0;margin:0;"><b><center><p style="padding:5px;margin:0;">Supplier</p></center></b></td>
			    		<td class="pm" style="width:50%;font-size:11px;font-family: sans-serif;border-bottom:.4px solid #000;padding:0;margin:0;" ><b><center><p style="padding:5px;margin:0;">Delivery</p></center></b></td>
			    	</thead>
			         
			          <tbody>
			                <tr>
			                    <td class="pm"  style="width:50%;border-bottom:.4px solid #000;border-right:.4px solid #000;font-size:11px;font-family: sans-serif;"> 
			                    	
									<left><p><?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_supplier ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->company_name).'<br>';
											echo ucfirst($resultCat->address).','; 
											echo ucfirst($resultCat->city).'-'.ucfirst($resultCat->postcode).',';
											$state  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_state)."' AND table_name='state'");
											$country  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_country)."' AND table_name='country'");
											echo $state.'-'.$country.'<br>';
												if($resultCat->gstin!=''){
											echo 'GST : '.ucfirst($resultCat->gstin).'<br>';
										    }
											echo 'Mob:'.ucfirst($resultCat->phone).',';
											echo ucfirst($resultCat->mobile).'&nbsp;&nbsp;Email:';
											echo $resultCat->email.'<br>';
										}
									  } 
									?></p>
			                   	</td> 
			                    <td  class="pm" style="width:50%;border-bottom:.4px solid #000;font-family: sans-serif;"><p style="font-size:11px;"><?php 
									  	$resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id = $row->id_mst_party_billtobe ");
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->company_name).'<br>';
											echo ucfirst($resultCat->address).','; 
											echo ucfirst($resultCat->city).'-'.ucfirst($resultCat->postcode).',';
											$state  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_state)."' AND table_name='state'");
											$country  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($resultCat->id_mst_attributes_country)."' AND table_name='country'");
											echo $state.'-'.$country.'<br>';
										    	if($resultCat->gstin!=''){
											echo 'GST : '.ucfirst($resultCat->gstin).'<br>';
										    }
											echo 'Mob:'.ucfirst($resultCat->phone).',';
											echo ucfirst($resultCat->mobile).'&nbsp;&nbsp;Email:';
											echo $resultCat->email.'<br>';
										}
									  } 
									?>
									</p></left>
			                    </td>   
			                </tr>  
			            </tbody>
			        </table> 
			     </td>
			   </tr>
			   <tr>
			   	<td style="padding:0px!important;border-bottom: 0.4px solid #000;">


			    	 
 					<table id="myTable1" class="table table-striped no-footer dataTable" width="100%" border="0" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr >
				                    <td class="pm" style="width: 1%;font-size:11px;font-family: sans-serif;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>S.No</b></p></td>
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 10%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Item Code</b></p></td> 
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 15%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Item Description</b></p></td> 
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 3%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Qty</b></p></td>  
				                    <td  class="pm" style="font-size:11px;font-family: sans-serif;width: 5%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Rate/Unit</b></p></td>  
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 5%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Disc(%)</b></p></td>  
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 10%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Amount</b></p></td>  
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 20%;padding:5px;margin:0;border-bottom: 0.4px solid #000;"><p style="margin:3px"><b>Taxes</b></p></td>    
				                </tr>
				            </thead>
				            <tbody>
				            	<tr>
				            		<p style="text-align: center;font-size:11px;font-family: sans-serif;border-bottom:0.4px solid #000;margin:0;padding:5px;"><b>Purchase Order Details</b></p>
				            	</tr>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select 
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_PO_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
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
				                	<td class="pm" style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;padding-left:15px!important;margin:0!important;"> 
					                 	<?php echo $count++; ?> 
					                </p></td> 
					                <?php 
			                		//Name Get
			                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                			//Item Description Get
			                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                		?>
				                    <td class="pm" style="padding-top:4px!important;width:10%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo $item_code; ?></p></td>  
				                    <td class="pm" style="padding-top:4px!important;width: 15%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo $item_description; ?><span style="display:block;font-size: 10px;"><?php  if(($array['item_remarks'.''.$j])!=''){ echo stripslashes($array['item_remarks'.''.$j]); } ?></span></td>  
				                    <td class="pm" style="padding-top:4px!important;width: 3%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['qty'.''.$j]); ?>&nbsp;&nbsp;<?php echo $array['transaction_unit'.''.$j];?></p></td>   
				                    <td class="pm" style="padding-top:4px!important;width: 5%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo round($array['rate_per_main_unit'.''.$j],2); ?>/<?php echo $array['per_unit'.''.$j];?></p></td>  
				                    
				                    <td class="pm" style="padding-top:4px!important;width: 5%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['discount_percent'.''.$j]); ?></p></td>  
				                    <td class="pm" style="padding-top:4px!important;width: 10%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['item_amount'.''.$j]); ?></p></td>  
				                    <td class="pm" style="padding-top:4px!important;width: 20%;padding:0px!important;padding-left:5px!important;margin:0;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php 


					                    if($array['id_mst_charges_purchase_local'.''.$j] >0  && $array['id_mst_charges_sgst'.''.$j]>0) {
											echo'SGST '. $array['item_sgst_percent'.''.$j].'% : '.' '.$array['item_sgst_amount'.''.$j].', ';
						                	echo 'CGST '. $array['item_cgst_percent'.''.$j].'% : '.' '.$array['item_cgst_amount'.''.$j];
						                 	$ledger_id = 1; 
						             	}//else if($array['id_mst_charges_purchase_interstate'.''.$j] != 0){
						             		else if($array['id_mst_charges_purchase_interstate'.''.$j] > 0){
												echo 'IGST  '. $array['item_igst_percent'.''.$j].'% : '.' '.$array['item_igst_amount'.''.$j];
						             		$ledger_id = 2; 
						                }else{
						                	$ledger_id = 0;
						                }
						              
										
				                    ?></p></td>     
				                     
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>  
				    </td>
				    </tr>


				      <?php
                     


                     	$sql6 = "  SELECT * FROM  `".TBL_INV_OTHERS_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";


							 $db->query($sql6); 
                    	// print_r($sql6);
                    	// $result6 = mysqli_query($conn,$sql6);

                    	//echo//  $db->num_rows($sql6);

                    	/*if ($db->query($sql6)){
                    	//	echo 'run';

                    		if($db->num_rows($sql6)>0){
                    			  $none='table-row';
                    		}else{
							 $none='none';
						} 


                    	} else {
                    		//echo 'not run';
                    	} */
                    	 

						
                       // $none="none"; */

                    	if($row->others_charges_net_amount>0){
                    			  $none='table-row';
                    		}else{
							 $none='none';
						} 
						?>

				    <tr style="display: <?php echo $none;?>">


				       <td  style="padding:0px!important;">
				        <table id="myTable1" class="table table-striped no-footer dataTable " width="100%" border="0" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr >
				                    <td style="width:1%;padding:5px;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;margin:3px;"><b>S.No</b></p></td> 
				                    <td style="width: 35%;padding:5px;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;margin:3px;"><b>Charges/Discount</b></p></td>
				                   <?php /*?> <td style="width: 20%;padding:5px;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;margin:3px;"><b>Disc(%)</b></p></td> <?php */?>
				                    <td style="width: 15%;padding:5px;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;margin:3px;"><b>Amount</b></p></td>
				                    <td style="width: 30%;padding:5px;margin:0;border-bottom:.4px solid #000;"><p style="font-size:11px;margin:3px;font-family: sans-serif;"><b>Taxes</b></p></td> 
				                </tr>
				            </thead>
				            <tbody>
				            	<tr >
				            		<p style="border-bottom:0.4px solid #000;padding:5px;font-size:11px;font-family: sans-serif;text-align: center;margin-bottom: 0"><b>Others/Charges Details</b></p>
				            	</tr>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select 
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_OTHERS_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
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
				                	<td class="pm" style="border-bottom:0.4px solid #000;border-right:.4px solid #000;padding:5px;margin:0;"><p style="font-size:11px;font-family: sans-serif;">
					                
					                <?php echo $count++; ?>
					                 </p>
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
				                    <td class="pm" style="border-bottom:0.4px solid #000;font-size:11px;font-family: sans-serif;border-right:.4px solid #000;padding:5px;margin:0;">
				                    	
				                    	<div id="others<?php echo $k;?>" name="others<?php echo $k;?>">
				                    	<p><?php 
										  	$others  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_mst_charges_others'.''.$j])."'");
							                	echo $others; 
											?>
											</p>
				                    	</div>
				                    	<div id="discounts<?php echo $k;?>" name="discounts<?php echo $k;?>">
				                    		<center><p syle="padding:5px;margin:0;"><?php 
										  	$discount  =  selectColumn(TBL_CHARGES,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_mst_charges_discounts'.''.$j])."'");
							                	echo $discount; 
											?>
											</p></center>
				                    	</div>
				                    </td>  
				                   <?php /*?> <td class="pm" style="border-bottom:0.4px solid #000;font-family: sans-serif;border-right:.4px solid #000;padding:5px;margin:0;"><p style="font-size:11px;"><?php echo stripslashes($array['others_charges_percent'.''.$j]); ?></p></td><?php */?>  
				                    <td class="pm" style="border-bottom:0.4px solid #000;font-family: sans-serif;border-right:.4px solid #000;padding:5px;margin:0;"><p style="font-size:11px;padding:0;"><?php  echo stripslashes($array['others_charges_amount'.''.$j]); ?></p></td>   
				                    <td class="pm" style="border-bottom:0.4px solid #000;font-family: sans-serif;font-size:11px;padding:5px;margin:0;">
					                    <p style="padding:0;"><?php 
						                    
							                if($ledger_id == 1){
							                	 	
							                	echo 'SGST : '.' '.$array['others_charges_sgst_amount'.''.$j].', ';
							                	echo 'CGST : '.' '.$array['others_charges_cgst_amount'.''.$j];
							                }else if($ledger_id == 2){ 
							                	echo 'IGST : '.' '.$array['others_charges_igst_amount'.''.$j];
							                }else{}
					                    ?>
				                    	
				                   </p> </td>   
				                     
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>
    					</td>
				    </tr>
				    <tr>
				    	<td>

				       <table id="myTable1" class="table table-striped  dataTable" border="0" width="100%" >
				        	<thead>
				        		<td style="width: 16%;"></td>
				                <td style="width: 25%;"></td> 
			                    <td style="width: 25%;"></td>
			                    <td style="width: 25%;"></td> 
			                    
			                    <td></td> 
				            </thead>
				            
				        	<tbody> 
				            	<tr>
				            		<td ></td> 
				            		<td class="pm" style="font-family: sans-serif;font-size:11px;"><p><b>Items Sub Total :</b><?php echo stripslashes($row->sub_total_items); ?></p></td>
				            		<td class="pm" style="font-family: sans-serif;font-size:11px;"><p><b>Items Discount :</b> <?php echo stripslashes($row->total_discount_items); ?></p></td>
				            		<td class="pm" style="font-family: sans-serif;font-size:11px;text-align: right;"><p><b>Items Total :</b></p></td>
				            		<td class="pm" style="font-family: sans-serif;font-size:11px;text-align: left;"><p><?php echo stripslashes($row->net_amount_items); ?></p></td>
				            		
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>Others Charges : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->others_charges_net_amount); ?></td>
				            		
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>SGST : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->sgst_net_amount); ?></td>
				            	
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>CGST : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->cgst_net_amount); ?></td>
				            		
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>IGST : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->igst_net_amount); ?></td>
				            		
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>Additional Discount : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->disc_amount_additional); ?></td>

				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td>
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>Round Off : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->round_off_amount); ?></td>
				            		
				            	</tr>
				            	<tr>
				            		<td></td>
				            		<td></td> 
				            		<td></td> 
				            		<td style="text-align: right;font-family: sans-serif;font-size:11px;"><b>Net Amount : </b> </td>
				            		<td style="text-align: left;font-family: sans-serif;font-size:11px;"><?php echo stripslashes($row->net_amount); ?></td>
				            		
				            	</tr>
				            	 
				        	</tbody>
				        	
				        </table>
				     </td>
				    </tr>

				    <?php 

				    	$sql2 = "  SELECT * FROM  `".TBL_INV_TERMS_AND_CONDITIONS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_po` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								 $rowsID = $db->fetch_object();

							
	
				    if($rowsID->terms!=''){
				    	$dnone='table-row';
				    } else{
				    	$dnone='none';
				    }

				    echo $rowsID->terms;


				    	?>
				    <tr style="display:<?php echo $dnone; ?>">
				    	<td class="border-bottom: 0.4px solid #000;padding:0px!important;">

				         <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        
				            
				        	<tbody>
				        		<tr>
				            		<p style="padding-left:10px;margin:0;text-align:left;font-family:sans-serif;font-size: 11px;"><b>Terms and Conditions</b></p>
				            	</tr>
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

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['terms'.''.$i] = $rowsID->terms; 
							 		 $i++;
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 } 
				            ?>
				        		
				            	<tr>
				        			<td style="font-family:sans-serif;font-size: 11px;"><?php echo stripslashes($array['terms'.''.$j]); ?></td> 
				        		</tr>
				        		<?php } ?>
				        	</tbody>
				        	
				        </table>
				    </td>
				</tr>
				
				</table>
				<br>
				    <table id="myTable1" class="table table-striped  dataTable no-footer mt-50" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr >
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td style="width:25%;font-family:sans-serif;font-size: 11px;">PURCHASE MANAGER</td>
				        		<td style="width:40%;font-family:sans-serif;font-size: 11px;">STORE</td>
				        		<td style="width:20%;font-family:sans-serif;font-size: 11px;">AGM</td>
				        		<td style="width:25%;font-family:sans-serif;font-size: 11px;">COO</td>

				        	</tbody>
				        		
				        	
				        </table>
		

		            </div> 
		     
		       		
				
         	</div> 
            </form>			
          </div>
          <!-- /.box -->
        </div>
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