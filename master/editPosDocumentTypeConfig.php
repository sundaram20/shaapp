<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
 
	
	
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add 
		
	//die;

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG,'add');
			$addSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG."` SET
							`id_app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
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
				header("location:managePosDocumentConfig.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
	 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'update');
			 $editSql = "   UPDATE `".TBL_DOC_TYPE_CONFIG."`  SET  
							`id_app_modules` = '2', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`start_no` = '".addslashes($_POST['start_no'])."',  
							`numeric_part` = '".addslashes($_POST['numeric_part'])."', 
							`prefix` = '".addslashes($_POST['prefix'])."',
							`suffix` = '".addslashes($_POST['suffix'])."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
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
 
				header("location:managePosDocumentConfig.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
 

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
    <section class="content-header">
      <h1>
        Document Type Configuration Manager
        <small>Manage  Document Type Configuration</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="manageDocumentTypeConfig.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  Document Type Configuration</li>
      </ol>
    </section>
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			 
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> Document Type Configuration : <a><?php echo selectColumn(TBL_DOC_TYPE_CONFIG,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
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

              <div class="box-body">

              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Configuration</h5>
              		</div> 
              		<hr>

	              	<div class="row">	

	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" >
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
				              	 			echo "Laundry(nc)";
              	 						}elseif($row->doc_type == '27'){
				              	 			echo "Spa and Health Club";
              	 						}elseif($row->doc_type == '28'){
				              	 			echo "Spa and Health Club(nc)";
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
                                <option value="26">Laundry(nc)</option>
                                <option value="27">Spa and Health Club</option> 
                                 <option value="28">Spa and Health Club(nc)</option> 
		                  	 </select></div>
	                  </div>

		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Effective Date <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-calendar"></i> 
							   	</div>
		                  <input type="text" class="form-control effective_date" placeholder="Enter Effective Date" id="effective_date" name="effective_date" value="<?php if($_POST) echo $_POST['effective_date'];else echo stripslashes($row->effective_date);?>" data-parsley-required> </div>
		                </div> 			                	                
						
		            </div>

		            <div class="row">	

	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" >
	              			<label for="name">Method <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-bars"></i> 
							   	</div>
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
		                  	 </select></div>
	                  </div>
	                  <?php if($row->id =='' || $row->method == '2' ){ ?>
		                  <style type="text/css">
		                  	#hideandshow{
		                  		display: none;
		                  	}
		                  </style>
	              	  <?php } ?>
                      <div  class="form-group col-xs-12 col-md-6 col-sm-2">
			            	 <label for="name">Custom Print File</label>
			            	 <input type="text" class="form-control" placeholder="Custom Print File" id="custom_print_file" name="custom_print_file" value="<?php if($_POST) echo $_POST['custom_print_file'];else echo stripslashes($row->custom_print_file);?>"> 
			            </div>
	                  <div id="hideandshow" name="hideandshow">
                      
                      <?php 
					  $sqlDocConfig = mysqli_query($connNew,"SELECT *  from `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE id_mst_doc_type_config='".$row->id."'");

						$numDocConfigRows=	mysqli_num_rows($sqlDocConfig);
						if($numDocConfigRows>0){
						$i=1;
						while($rowDocCofig=mysqli_fetch_object($sqlDocConfig)){
						?>
                      <div id="grid<?php echo $i;?>">  
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
                             <button class="pull-left btn btn-success btn-sm" type="button"  onclick="addNewGrid();"  style="margin-top: 29px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                             <?php }else{?>
                             <a class="btn btn-danger btn-sm" style="margin-top: 29px;" href="javascript:void(0);"  onclick="removeGrid(<?php echo $i++; ?>);"><i class="fa fa-trash-o fa-lg"></i> </a>
                             <?php
								 
								 }
							 $i++;
							  ?>
                            </div>
                            </div>
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
								
                             <button class="pull-left btn btn-success btn-sm" type="button"  onclick="addNewGrid();"  style="margin-top: 29px;float:right;" ><i class="fa fa-plus-circle"></i></button>
                            
                            </div>
								
								<?php }?>
			            </div>
			             <div id="rowGrid"></div>
						
		            </div>
                    
		            <?php 
			        	if($row->status == ''){
			        		$status = 1;
			        	}else{
			        		$status = $row->status;
			        	}
			        ?> 

		            <div class="row"> 	            	
						<div class="form-group col-xs-12 col-md-6 col-sm-2"> 
		                	<label for="status">Status : </label> 
			                <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" 
			                name="status" id="status" /> Active
							<input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" 
							name="status"  id="status"   /> Inactive
							 <?php echo $err_status;?>
							 
		                </div>  
		            </div>

		        </div>
		        <hr> 

				<?php if($row->date_created){?>
				  
				<div class="form-group">
                  <label for="date_created">Date Created</label>
                  <input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
                </div> 
				
				<div class="form-group">
                  <label for="last_modified">Last Updated</label>
                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
                </div> 

                <div class="form-group">
                  <label for="last_modified_by">Created By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				
				<div class="form-group">
                  <label for="last_modified_by">Last Updated By</label>
				   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
                </div>  
				  
				  <?php } ?>            
              </div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-success" name="Save" onClick="checksubsection();" >
				&nbsp;&nbsp;&nbsp;&nbsp; 
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("managePosDocumentConfig.php?eId=<?php echo encryptor(encrypt,$mst_party_company_id); ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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



        var grid ='<div id="grid'+gridNo+'"><table id="myTableOrder1" class="" cellspacing="0" style="font-size:14px;padding: 0px 0px;" >   <tbody><tr><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"> <select style="width:185px;" class="form-control select2" name="outlet[]" id="outlet">'+$("#outlet").html()+'</select></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Start No" id="start_no" name="start_no[]" value="" style="width:185px;"></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Numeric Part" id="numeric_part" name="numeric_part[]" value="" style="width:185px;"></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" style="width:185px;" placeholder="Enter Prefix" id="prefix" name="prefix[]" value=""></div></td><td style="width:185px;"><div class="form-group col-xs-12 col-md-2 col-sm-2"><input type="text" class="form-control" placeholder="Enter Suffix" id="suffix" name="suffix[]" value="" style="width:185px;" ></div></td><td style="width: 4%;float: left;"><a class="btn btn-danger btn-sm" href="javascript:void(0);"  onclick="removeGrid('+gridNo+');"><i class="fa fa-trash-o fa-lg"></i> </a> </td></tr> </tbody></table></div>';

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



