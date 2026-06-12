<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
	
	//Insert Here
	//debugData($_POST);die;
	if($err == 0){//No error
		if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){//add 
		
	//die;

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG,'add');
			$addSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG."` SET
							`id_app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_name` = '".addslashes($_POST['doc_name'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."', 
								`enable_steward_passcode`='".addslashes($_POST['enable_steward_passcode']!=''?$_POST['enable_steward_passcode']:0)."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_mst_printer` = '".addslashes($_POT['id_mst_printer'])."',

							`enable_nationality`='".addslashes($_POST['enable_nationality']!=''?$_POST['enable_nationality']:0)."',
							`enable_split_print`='".addslashes($_POST['enable_split_print']!=''?$_POST['enable_split_print']:0)."',
							`enable_print_after_save`='".addslashes($_POST['enable_print_after_save']!=''?$_POST['enable_print_after_save']:0)."',
							`enable_split_bill_by_sales_account_group`='".addslashes($_POST['enable_split_bill_by_sales_account_group']!=''?$_POST['enable_split_bill_by_sales_account_group']:0)."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`footer_remarks` = '".addslashes($_POST['footer_remarks'])."',
							`status` = '".addslashes($_POST['status'])."'";
								
			if(executeSql($addSql)){

				$lastInsertId= $db->insert_id();
				$i=0;
				foreach($_POST['outlet'] as $value){
					
					$addDetailSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG_DETAIL."` SET
								`id_mst_doc_type_config` = '".$lastInsertId."',
								`id_subsection` = '".$_POST['outlet'][$i]."', 							
								`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  							
								`start_no` = '".addslashes($_POST['start_no'][$i])."',  
								`numeric_part` = '".addslashes($_POST['numeric_part'][$i])."', 
								`prefix` = '".addslashes($_POST['prefix'][$i])."',
								`suffix` = '".addslashes($_POST['suffix'][$i])."'
								
								";					
					echo executeSql($addDetailSql);
					$i++;
				}
				unset($_POST);
				$_SESSION['successMsg'] = 'New  Document Type Configuration has been added sucessfully.';
				header("location:managePosDocumentConfig.php?eId=".encryptor(encrypt,$lastInsertId)."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){//update
		
		
	 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'update');
			 $editSql = "   UPDATE `".TBL_DOC_TYPE_CONFIG."`  SET  
							`id_app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`doc_name` = '".addslashes($_POST['doc_name'])."',
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`start_no` = '".addslashes($_POST['start_no'])."',  
							`numeric_part` = '".addslashes($_POST['numeric_part'])."', 
							`prefix` = '".addslashes($_POST['prefix'])."',
							`suffix` = '".addslashes($_POST['suffix'])."',
								`enable_steward_passcode`='".addslashes($_POST['enable_steward_passcode']!=''?$_POST['enable_steward_passcode']:0)."',
							`enable_nationality`='".addslashes($_POST['enable_nationality']!=''?$_POST['enable_nationality']:0)."',
							`enable_split_print`='".addslashes($_POST['enable_split_print']!=''?$_POST['enable_split_print']:0)."',
							`enable_print_after_save`='".addslashes($_POST['enable_print_after_save']!=''?$_POST['enable_print_after_save']:0)."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`enable_split_bill_by_sales_account_group`='".addslashes($_POST['enable_split_bill_by_sales_account_group']!=''?$_POST['enable_split_bill_by_sales_account_group']:0)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."',
							`id_mst_printer` = '".addslashes($_POST['id_mst_printer'])."'";

							$editSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							,`footer_remarks` = '".addslashes($_POST['footer_remarks'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){  
				    $id_mst_doc_type_config= encryptor(decrypt,$_POST['eId']);
					mysqli_query($connNew,"DELETE FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$id_mst_doc_type_config."'");
					
					
					$i=0;
					foreach($_POST['outlet'] as $value){
					
					$addDetailSql = "   INSERT INTO `".TBL_DOC_TYPE_CONFIG_DETAIL."` SET
								`id_mst_doc_type_config` = '".$id_mst_doc_type_config."',
								`id_subsection` = '".$_POST['outlet'][$i]."', 							
								`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  							
								`start_no` = '".addslashes($_POST['start_no'][$i])."',  
								`numeric_part` = '".addslashes($_POST['numeric_part'][$i])."', 
								`prefix` = '".addslashes($_POST['prefix'][$i])."',
								`suffix` = '".addslashes($_POST['suffix'][$i])."'
								
								";					
					echo executeSql($addDetailSql);
					$i++;
				}
		
				$_SESSION['successMsg'] = selectColumn(TBL_DOC_TYPE_CONFIG, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
 
				header("location:managePosDocumentConfig.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']);
 

				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_DOC_TYPE_CONFIG,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_DOC_TYPE_CONFIG."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}						
}	
							

?>
<?php   

	if($_GET['eId'] == ''){
		$mst_party_company_id =  encryptor(decrypt,$_GET['doc_type_id']);
	}else{
 
		$mst_party_company_id = encryptor(decrypt,$_GET['doc_type_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <!--<h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>-->

         <h5 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php 
			
			$sqlDocConfig = mysqli_query($connNew,"SELECT *  from `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE id_mst_doc_type_config='".$row->id."'");

						$numDocConfigRows=	mysqli_num_rows($sqlDocConfig);
						
						$rowDocCofig=mysqli_fetch_object($sqlDocConfig);
			
			
			echo $rowDocCofig->prefix.$rowDocCofig->start_no.$rowDocCofig->suffix ?> </span>
           </h5>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <!-- Main content -->
    <section class="content">
	<hr class="br-line">
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom mb-0 shadow-none">
			 
			<div class="">
         
			
			<!--
			<span style="color:#3c8dbc"> 
			<?php 

			/*	if($row->doc_type == '21'){
					echo "POS Sales Bill";
				}elseif($row->doc_type == '22'){
					echo "KOT";
				}elseif($row->doc_type == '23'){
					echo "POS sales Bil(nc)";
				}elseif($row->doc_type == '24'){
					echo "KOT(nc)";
				}elseif($row->doc_type == '25'){
					echo "Laundry";
				}elseif($row->doc_type == '26'){
					echo "Spa and Health Club";
				}elseif($row->doc_type == '27'){
					echo "Laundry(nc)";
				}elseif($row->doc_type == '28'){
					echo "Spa and Health Club(nc)";
				}elseif($row->doc_type == '29'){
					echo "Others";
				}else{

				} */

			
			?> </span> -->
			
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1" id="documentTypeConfig"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>

              <div class="box-body p-0">

              	<div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Configuration</h5>
              		</div> -->
              	

	              	<div class="row">	

	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Document Type <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-book"></i> 
							   	</div>
		                  	 <select data-parsley-required class="form-control select2" id="doc_type" name="doc_type" onchange="datemethodhide()" >
		                  	 	<?php if(stripslashes($row->id == '')){ ?>
		                  	 	<option value="">Select Document Type</option>
		                  	 <?php } else{ ?>
		                  	 	<option selected="selected"  value="<?php echo $row->doc_type; ?>">

		                  	 		<?php  

			                  	 		if($row->doc_type == '21'){
				              	 			echo "POS Sales Bill";
				              	 		}elseif($row->doc_type == '22'){
				              	 			echo "KOT";
				              	 		}elseif($row->doc_type == '23'){
				              	 			echo "POS sales Bil(nc)";
              	 						}elseif($row->doc_type == '24'){
				              	 			echo "KOT(nc)";
              	 						}elseif($row->doc_type == '25'){
				              	 			echo "Laundry";
              	 						}elseif($row->doc_type == '26'){
				              	 			echo "Spa and Health Club";
              	 						}elseif($row->doc_type == '27'){
				              	 			echo "Laundry(nc)";
              	 						}elseif($row->doc_type == '28'){
				              	 			echo "Spa and Health Club(nc)";
              	 						}elseif($row->doc_type == '29'){
				              	 			echo "Others";
              	 						}elseif($row->doc_type == '30'){
				              	 			echo "POS Receipt";
              	 						}else{

				              	 		}
							
		                  	 		?>
		                  	 			
		                  	 	</option>
		                  	 <?php } ?>
		                  	 	<?php /*?><option value="1">Requestion</option> 
		                  	 	<option value="2">Indent Purchase Order</option> 
		                  	 	<option value="3">Purchase Order</option> 
		                  	 	<option value="4">Goods Receipt Note</option> 
		                  	 	<option value="5">Purchase Bill</option> 
		                  	 	<option value="6">Store Issue Note</option> 
		                  	 	<option value="7">Credit Note</option> 
		                  	 	<option value="8">Debite Note</option> 
		                  	 	<option value="9">Physical Stock</option> <?php */?>
                                <option value="21">POS Sales Bill</option> 
                                <option value="22">KOT</option> 
                                <option value="23">POS Sales Bill(nc)</option>
                                <option value="24">KOT(nc)</option>
                                <option value="25">Laundry</option>
                                <option value="27">Laundry(nc)</option>
                                <option value="26">Spa and Health Club</option> 
                                 <option value="28">Spa and Health Club(nc)</option> 
                                 <option value="29">Others</option> 
								 <option value="30">POS Receipt</option> 
		                  	 </select></div>
	                  </div>



		                 <div class="form-group col-xs-6 col-md-2 col-sm-6">
		                  <label for="name">Document Name<font color="#FF0000">*</font></label>
		                 
							   
		                  <input type="text" class="form-control " placeholder="Enter Document Name" id="doc_name" name="doc_name" value="<?php echo $row->doc_name; ?>" data-parsley-required> 
		                </div> 
					  
					  
					  <div class="form-group col-xs-6 col-md-2 col-sm-6">
		                  <label for="name">Effective Date <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-calendar"></i> 
							   	</div>
							   	<?php if($row->effective_date !=''){
							   		$date =date('d-m-Y', strtotime( $row->effective_date ));
							   	}else {
							   		$date = date('d-m-Y');
							   	} ?>
		                  <input type="text" class="form-control effective_date" placeholder="Enter Effective Date" id="effective_date" name="effective_date" value="<?php echo $date; ?>" data-parsley-required> </div>
		                </div> 
		           

	              		<div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Method <font color="#FF0000">*</font></label>
	              		
		              			
		                  	 <select data-parsley-required class="form-control select2" id="method" name="method"  onchange="hideandshow()">
		                  	 	<?php if(stripslashes($row->id == '')){ ?>
		                  	 	<option value="">Select Document Type</option>
		                  	 <?php } else{ ?>
		                  	 	<option selected="selected"  value="<?php echo $row->method; ?>">

		                  	 		<?php  if($row->method == '1'){
		                  	 			echo "Auto";
		                  	 		}else{
		                  	 			echo "Manual";
		                  	 			}
		                  	 		?>
		                  	 			
		                  	 	</option>
		                  	 <?php } ?>
		                  	 	<option value="1">Auto</option> 
		                  	 	<option value="2">Manual</option>
		                  	 </select>
	                  </div>


	              
			             
				            
					       
		            
	                  <?php if($row->id =='' || $row->method == '2' ){ ?>
		                  <style type="text/css">
		                  	#hideandshow{
		                  		display: none;
		                  	}
		                  </style>
	              	  <?php } ?>

	              	  	<div  class="form-group col-xs-6 col-md-2 col-sm-6">
					            	 <label for="name">Custom Print File</label>
					            	 <input type="text" class="form-control" placeholder="Custom Print File" id="custom_print_file" name="custom_print_file" value="<?php if($_POST) echo $_POST['custom_print_file'];else echo stripslashes($row->custom_print_file);?>"> 
					     </div>
                      

                       </div><!--end of row-->
			       
			    
	                  <div id="hideandshow" name="hideandshow">
                      
                      <?php 
					  $sqlDocConfig = mysqli_query($connNew,"SELECT *  from `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE id_mst_doc_type_config='".$row->id."'");

						$numDocConfigRows=	mysqli_num_rows($sqlDocConfig);
						if($numDocConfigRows>0){
						$i=1;
						while($rowDocCofig=mysqli_fetch_object($sqlDocConfig)){
						?>
                      <div class="row" id="grid<?php echo $i;?>">  
			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Sub Section</label><br>
			                   <select style="width:100%;" class="form-control select2" name="outlet[]" id="outlet" data-parsley-required data-parsley-errors-container="#outletError">
                               			    <option value="">Select Outlet</option>
											<?php  $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' ",'');
											  if($db->num_rows2($resCat)){
												  $k=1;
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($k==1){
													if($rowDocCofig->id_subsection==0){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
														
													 $categoryDropDown .= '<option '.$selected.' value="0">Not Applicable</option>';
													 
													}
													if($rowDocCofig->id_subsection == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												$k++;}
												echo  $categoryDropDown;
											  } ?>
											 	</select>
											  
			                </div> 
                            
                            
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Start No</label>
			                  <input type="text" class="form-control" placeholder="Enter Start No" id="start_no" name="start_no[]" value="<?php if($_POST) echo $_POST['start_no'];else echo stripslashes($rowDocCofig->start_no);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Numeric Part</label>
			                  <input type="text" class="form-control" placeholder="Enter Numeric Part" id="numeric_part" name="numeric_part[]" value="<?php if($_POST) echo $_POST['numeric_part'];else echo stripslashes($rowDocCofig->numeric_part);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Prefix</label>
			                  <input type="text" class="form-control" placeholder="Enter Prefix" id="prefix" name="prefix[]" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($rowDocCofig->prefix);?>"> 
			                </div> 

			                 <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Suffix</label>
			                  <input type="text" class="form-control" placeholder="Enter Suffix" id="suffix" name="suffix[]" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($rowDocCofig->suffix);?>"> 
			                </div>
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
                             <label for="name"></label>
							<?php if($i==1){?>	
                             <button class="pull-left btn n-btn btn-sm" type="button"  onclick="addNewGrid();"  style="margin-top: 29px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                             <?php }else{?>
                             <a class="btn n-btn btn-sm" style="margin-top: 29px;" href="javascript:void(0);"  onclick="removeGrid(<?php echo $i++; ?>);"><i class="fa fa-trash-o fa-lg"></i> </a>
                             <?php
								 
								 }
							 $i++;
							  ?>
                            </div>
                            </div>
                           <!--end of row-->

                            <?php } 
							
							}else{?>
							
								 <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Sub Section</label><br>
			                   <select style="width:100%;" class="form-control select2" name="outlet[]" id="outlet" data-parsley-required data-parsley-errors-container="#outletError">
                               			    <option value="">Select Outlet</option>
											<?php   $resCat = selectSql(TBL_OUTLETS," where id_shop='".$_SESSION['shop']."' AND  status = '1' ",'');
											  if($db->num_rows2($resCat)){
												  $k=1;
											  	while($resultCat = $db->fetch_object2($resCat)){
													if($k==1){
													/*if($rowDocCofig->id_subsection==0){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}*/
														
													 $categoryDropDown .= '<option '.$selected.' value="0">Not Applicable</option>';
													 
													}
													/*if($rowDocCofig->id_subsection == $resultCat->id){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}*/
													$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
												$k++;}
												echo  $categoryDropDown;
											  }  ?>
											 	</select>
											  
			                </div> 
                            
                            
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Start No</label>
			                  <input type="text" class="form-control" placeholder="Enter Start No" id="start_no" name="start_no[]" value="<?php if($_POST) echo $_POST['start_no'];else echo stripslashes($row->start_no);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Numeric Part</label>
			                  <input type="text" class="form-control" placeholder="Enter Numeric Part" id="numeric_part" name="numeric_part[]" value="<?php if($_POST) echo $_POST['numeric_part'];else echo stripslashes($row->numeric_part);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Prefix</label>
			                  <input type="text" class="form-control" placeholder="Enter Prefix" id="prefix" name="prefix[]" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($row->prefix);?>"> 
			                </div> 

			                 <div class="form-group col-xs-12 col-md-2 col-sm-2">
			                  <label for="name">Suffix</label>
			                  <input type="text" class="form-control" placeholder="Enter Suffix" id="suffix" name="suffix[]" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($row->suffix);?>"> 
			                </div>
                            <div class="form-group col-xs-12 col-md-2 col-sm-2">
                             <label for="name"></label>
								
                             <button class="pull-left btn n-btn btn-sm" type="button"  onclick="addNewGrid();"  style="margin-top: 29px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                            
                            </div>
								
								<?php }?>
			            </div>
			             <div id="rowGrid"></div>
			             
						 <!--footer message starts-->
		                 <div class="form-group col-xs-12 col-md-6 col-sm-12 p-0">
		                  <label for="name">Footer Message</label>
						  <textarea class="ckeditor" id="footer_remarks" name="footer_remarks" rows="1" height="120px"><?php if($_POST) echo $_POST['footer_remarks'];else echo stripslashes($row->footer_remarks);?></textarea>
							   
		                </div> 
			             <!--footer message ends--> 
		            </div>
		        </div>

                   	  <div class="boxx" style="width:100%;">
                   	    <div class="row">
                   	    	<div class="form-group col-md-12 col-sm-3">
			                	<div class="st-box">
			                		Status
			                	</div>
                 	 
		                   </div>

						 <div class="form-group col-md-2 col-sm-6"> 
		                	<label for="enableSplitPrint">Enable Split print : </label> 
			               <input type="radio"  name="enable_split_print" id="enable_split_print" <?php if($_POST['enable_split_print'] == '1'){echo "checked";}else{if($row->enable_split_print == 1)echo "checked";}?> value="1" /> Yes
			                 <input type="radio"  name="enable_split_print" id="enable_split_print" <?php if($_POST['enable_split_print'] == '0'){echo "checked";}else{if($row->enable_split_print == 0)echo "checked";}?> value="0" /> No
							 
		                 </div>  
                        <div class="form-group col-md-3 col-sm-6"> 
		                	<label for="enableSplitPrint">Enable Split Bill  Account Wise : </label> 
			               <input type="radio"  name="enable_split_bill_by_sales_account_group" id="enable_split_bill_by_sales_account_group" <?php if($_POST['enable_split_bill_by_sales_account_group'] == '1'){echo "checked";}else{if($row->enable_split_bill_by_sales_account_group == 1)echo "checked";}?> value="1" /> Yes
			                 <input type="radio"  name="enable_split_bill_by_sales_account_group" id="enable_split_bill_by_sales_account_group" <?php if($_POST['enable_split_bill_by_sales_account_group'] == '0'){echo "checked";}else{if($row->enable_split_bill_by_sales_account_group == 0)echo "checked";}?> value="0" /> No
							 
		                 </div>
                        <div class="form-group col-md-2 col-sm-6"> 
		                	<label for="enableSplitPrint">Enable Print After Save : </label> 
			               <input type="radio"  name="enable_print_after_save" id="enable_print_after_save" <?php if($_POST['enable_print_after_save'] == '1'){echo "checked";}else{if($row->enable_print_after_save == 1)echo "checked";}?> value="1" /> Yes

			                <input type="radio"  name="enable_print_after_save" id="enable_print_after_save" <?php if($_POST['enable_print_after_save'] == '0'){echo "checked";}else{if($row->enable_print_after_save == 0)echo "checked";}?> value="0" /> No
							 
		                </div>

                   <div class="form-group col-md-2 col-sm-6"> 
		                	<label for="enableNationality">Enable Nationality : </label> 
			               <input type="radio"  name="enable_nationality" id="enable_nationality" <?php if($_POST['enable_nationality'] == '1'){echo "checked";}else{if($row->enable_nationality == 1)echo "checked";}?> value="1" /> Yes

			                <input type="radio"  name="enable_nationality" id="enable_nationality" <?php if($_POST['enable_nationality'] == '0'){echo "checked";}else{if($row->enable_nationality == 0)echo "checked";}?> value="0" /> No
							 
		                </div>
							
		<div class="form-group col-md-2 col-sm-6"> 
		                	<label for="enableNationality">Enable Steward Passcode : </label> 
			               <input type="radio"  name="enable_steward_passcode" id="enable_steward_passcode" <?php if($_POST['enable_steward_passcode'] == '1'){echo "checked";}else{if($row->enable_steward_passcode == 1)echo "checked";}?> value="1" /> Yes

			                <input type="radio"  name="enable_steward_passcode" id="enable_steward_passcode" <?php if($_POST['enable_steward_passcode'] == '0'){echo "checked";}else{if($row->enable_steward_passcode == 0)echo "checked";}?> value="0" /> No
							 
		                </div> 					
							
							
		                <div class="form-group col-md-2 col-sm-16"> 
		                	<label for="enableNationality">Default Printer : </label> 
			                    
								  <?php 
								  
								$categoryDropDown = '<select class="form-control select2" name="id_mst_printer" id="id_mst_printer"  >
											<option value="">Select Printer</option>';
										  $resCat = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."' AND  status = '1' AND `table_name` = 'printer' ",'');
										  if($db->num_rows2($resCat)){
											while($resultCat = $db->fetch_object2($resCat)){
												if($row->id_mst_printer == $resultCat->id){
													$selected = 'selected="selected"';
												}else{
													$selected = '';
												}
												$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';
											}
										  }
											echo $categoryDropDown .= '</select>';
							 ?>
							 
		                </div>

		                  <?php 
			        	if($row->status == ''){
			        		$status = 1;
			        	}else{
			        		$status = $row->status;
			        	}
			        ?> 

			  
		                     	
						<div class="form-group col-xs-12 col-md-3 col-sm-3"> 
		                	<label for="status">Status : </label> 
			                <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                name="status" id="status" /> Active
							<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
							name="status"  id="status"   /> Inactive
							 <?php echo $err_status;?>
							 
		                </div>  
		               </div>
		             </div><!--end of boxx-->

		          
		     
		             
		               <!-- /.box-body -->
               <hr class="br-line mb-10">   	
						 <div class="box-footer p-0 br-none">                                       
							<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Save')?>' class="btn c-btn" name="Save" onClick="checksubsection();" >
							
						   <a type='button' value='Cancel' class="btn c-btn" onclick='location.replace("managePosDocumentConfig.php?eId=<?php echo encryptor(encrypt,$mst_party_company_id); ?>&action=edit&page=<?php echo $_GET['page']; ?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"); '>	<i class="far fa-window-close"></i>
						   	Close
						   </a>
						 </div>
		      

				<?php if($row->date_created){?>
		        <div class="row mt-10">
				<div class="form-group col-md-3 col-xs-6 ">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group  col-md-3 col-xs-6 ">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 

                <div class="form-group  col-md-3 col-xs-6 ">
                  <label for="last_modified_by">Created By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group  col-md-3 col-xs-6 ">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>
            </div><!--end of row-->
             

						<a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
					 <i class="fas fa-history"></i> Alteration History
				</a>
				  
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
<?php if($row->id !=''){ ?>
 <script type="text/javascript">
 	$('.effective_date').datepicker({ dateFormat: "dd-mm-yy"});
 </script>
<?php } ?>

<script type="text/javascript">
var gridNo=1;


function addNewGrid(){
//$outlet = $('#outlet'+gridNo).select2();

$('.select2').select2();
$('#outlet').select2();
$('#outlet_'+gridNo).select2();


   var grid ='<div class="row" id="grid'+gridNo+'"><table id="myTableOrder1" class="" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >   <tbody><tr><td style="width:213px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select style="width:187px;" class="form-control select2" name="outlet[]" id="outlet">'+$("#outlet").html()+'</select></div></td><td style="width:219px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Start No" id="start_no" name="start_no[]" value="" style="width:185px;"></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Numeric Part" id="numeric_part" name="numeric_part[]" value="" style="width:185px;"></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" style="width:185px;" placeholder="Enter Prefix" id="prefix" name="prefix[]" value=""></div></td><td style="width:219px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Suffix" id="suffix" name="suffix[]" value="" style="width:185px;" ></div></td><td><div class="form-group col-xs-12 col-md-2 col-sm-2"><a class="btn n-btn btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+');"><i class="fa fa-trash-o fa-lg"></i> </a></div> </td></tr> </tbody></table></div>';
        $('#outlet_'+gridNo).select2();
		$('#outlet').select2();
		$('#rowGrid').append(grid);
		 
        gridNo++;
    }
function removeGrid(id){
		
    $('#grid'+id).remove();
   
}
	function hideandshow() {
		var method = document.getElementById("method");
	    var method = method.options[method.selectedIndex].value;
		 
		 if(method == 1){
		 	$('#hideandshow').show();
		 }else{
		 	$('#hideandshow').hide();
		 }
	} 

function checksubsection(){
	 var form1=$("#documentTypeConfig");	
     var dataString = $("#documentTypeConfig").serialize();
     var serializedReturn = $('input[name!=outlet]', this).serialize();      
	 
	 exit;
	}
//Date Method Hide
function datemethodhide(){
	var doc_type = document.getElementById("doc_type");
	var doc_type = doc_type.options[doc_type.selectedIndex].value;

	$.ajax({
			type: "POST",
			url: "../ajax/DateMethodHide.php",	
			data:'doc_type='+doc_type, 
			success: function(data){

				var mydata = JSON.parse(data);  
 				
 				 $( ".effective_date" ).datepicker("destroy");

				if(mydata['date'] != ''){  
					$('.effective_date').datepicker({ dateFormat: "dd-mm-yy", minDate: mydata['date'], }); 

				} else{
					$('.effective_date').datepicker({ dateFormat: "dd-mm-yy" , startDate: new Date()});
				}
			}
		});
}
</script>	



