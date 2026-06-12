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

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_DOC_TYPE_CONFIG,'add');
			$addSql = "   	INSERT INTO `".TBL_DOC_TYPE_CONFIG."` SET
							`id_app_modules` = '1', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`start_no` = '".addslashes($_POST['start_no'])."',  
							`numeric_part` = '".addslashes($_POST['numeric_part'])."', 
							`prefix` = '".addslashes($_POST['prefix'])."',
							`suffix` = '".addslashes($_POST['suffix'])."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
							 `id_mst_party_billtobe` = '".addslashes($_POST['id_mst_party_billtobe'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
								
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();

				$_SESSION['successMsg'] = 'New  Document Type Configuration has been added sucessfully.';
				header("location:manageDocumentTypeConfig.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Document Type Configuration has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'update');
			 $editSql = "   	UPDATE `".TBL_DOC_TYPE_CONFIG."`  SET  
							`id_app_modules` = '1', 
							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`effective_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['effective_date'])))."',  
							`method` = '".addslashes($_POST['method'])."',  
							`start_no` = '".addslashes($_POST['start_no'])."',  
							`numeric_part` = '".addslashes($_POST['numeric_part'])."', 
							`prefix` = '".addslashes($_POST['prefix'])."',
							`suffix` = '".addslashes($_POST['suffix'])."',
							`custom_print_file` = '".addslashes($_POST['custom_print_file'])."',
						    `id_mst_party_billtobe` = '".addslashes($_POST['id_mst_party_billtobe'])."',

							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){  

				$_SESSION['successMsg'] = selectColumn(TBL_DOC_TYPE_CONFIG, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
 
				header("location:manageDocumentTypeConfig.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
 

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

	$sql = "  SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
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
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

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
             <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->prefix.$row->start_no.$row->suffix ?> </span>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="form1"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" >
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

			                  	 		if($row->doc_type == '1'){
				              	 			echo "Requestion";
				              	 		}elseif($row->doc_type == '2'){
				              	 			echo "Indent Purchase Order";
				              	 		} 
				              	 		elseif($row->doc_type == '3'){
				              	 			echo "Purchase Order";
				              	 		}
				              	 		elseif($row->doc_type == '4'){
				              	 			echo "Goods Receipt Note"; 
				              	 		}
				              	 		elseif($row->doc_type == '5'){
				              	 			echo "Purchase Bill";
				              	 		}
				              	 		elseif($row->doc_type == '6'){
				              	 			echo "Store Issue Note";
				              	 		}
				              	 		elseif($row->doc_type == '7'){
				              	 			echo "Credit Note";
				              	 		}elseif($row->doc_type == '8'){
				              	 			echo "Debite Note";
				              	 		}elseif($row->doc_type == '9'){
				              	 			echo "Physical Stock";
				              	 		}elseif($row->doc_type == '12'){
				              	 			echo "Direct Purchase";
				              	 		}elseif($row->doc_type == '51'){
				              	 			echo "Price Matrix";
				              	 		}else{

				              	 		}

		                  	 		?>
		                  	 			
		                  	 	</option>
		                  	 <?php } ?>
		                  	 	<option value="1">Requestion</option> 
		                  	 	<option value="2">Indent Purchase Order</option> 
		                  	 	<option value="3">Purchase Order</option> 
		                  	 	<option value="4">Goods Receipt Note</option> 
		                  	 	<option value="5">Purchase Bill</option> 
		                  	 	<option value="12">Direct Purchase</option> 
		                  	 	<option value="6">Store Issue Note</option> 
		                  	 	<option value="7">Credit Note</option> 
		                  	 	<option value="8">Debite Note</option> 
		                  	 	<option value="9">Physical Stock</option> 
		                  	   <option value="51">Price Matrix</option> 

		                  	 </select></div>
	                  </div>

		             <!--   <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Effective Date <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-calendar"></i> 
							   	</div>
		                  <input type="text" class="form-control effective_date" placeholder="Enter Effective Date" id="effective_date" name="effective_date" value="<?php if($_POST) echo $_POST['effective_date'];else echo date('d-m-Y' , strtotime(addslashes($row->effective_date)));?>" data-parsley-required> </div>
		                </div> 	 -->

						<div class="form-group col-xs-12 col-md-6 col-sm-2">
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
	                  <div id="hideandshow" name="hideandshow">
			                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Start No</label>
			                  <input type="text" class="form-control" placeholder="Enter Start No" id="start_no" name="start_no" value="<?php if($_POST) echo $_POST['start_no'];else echo stripslashes($row->start_no);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Numeric Part</label>
			                  <input type="text" class="form-control" placeholder="Enter Numeric Part" id="numeric_part" name="numeric_part" value="<?php if($_POST) echo $_POST['numeric_part'];else echo stripslashes($row->numeric_part);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Prefix</label>
			                  <input type="text" class="form-control" placeholder="Enter Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($row->prefix);?>"> 
			                </div> 

			                <div class="form-group col-xs-12 col-md-6 col-sm-2">
			                  <label for="name">Suffix</label>
			                  <input type="text" class="form-control" placeholder="Enter Suffix" id="suffix" name="suffix" value="<?php if($_POST) echo $_POST['suffix'];else echo stripslashes($row->suffix);?>"> 
			                </div>
			            </div>
			            <div  class="form-group col-xs-12 col-md-6 col-sm-2">
			            	 <label for="name">Custom Print File</label>
			            	 <input type="text" class="form-control" placeholder="Custom Print File" id="custom_print_file" name="custom_print_file" value="<?php if($_POST) echo $_POST['custom_print_file'];else echo stripslashes($row->custom_print_file);?>"> 
			            </div>

			               <div class="form-group  col-xs-12 col-md-6 col-sm-6 ">
					                     <label for="name">Bill To Be  </label>
					                    <div class="input-group"> 
					              			<div class="input-group-addon">
												<i class="far fa-money-bill-alt"></i> 
										   	</div>
						                     <select  class="form-control select2" name="id_mst_party_billtobe" id="id_mst_party_billtobe" style="width:100%">
												<?php $categoryDropDown = '	<option value="">Select Bill To Be</option>';
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
														$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
													}
												  }
												 	echo $categoryDropDown .= '</select>';
												  ?>
										       <?php echo $err_deparment;?> 
					              	    </div>
			              		</div>
			              		  	<!--end of bill to be -->
						
		            </div>

		            <hr>
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
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-success" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp; 
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("manageDocumentTypeConfig.php?eId=<?php echo encryptor(encrypt,$mst_party_company_id); ?>&action=edit&page=<?php echo $_GET['page']; ?>"); '>
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

	function hideandshow() {
		var method = document.getElementById("method");
	    var method = method.options[method.selectedIndex].value;
		 
		 if(method == 1){
		 	$('#hideandshow').show();
		 }else{
		 	$('#hideandshow').hide();
		 }
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



