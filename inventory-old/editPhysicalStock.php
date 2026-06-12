<?php include_once("../config/auto_loader.php");
 

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'edit');

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0;
		
	//Insert Here
	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add 

			 checkUserLevelPermission($_SESSION['userLevel'], TBL_INV_PURCH,'add');
			 //Purch No Check Here
			 $po_no = $_POST['po_no'];

			 $sql5 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `po_no`='".$po_no."'  and `doc_type` = '1'  ";
				$db->query($sql5);
				$numRows= $db->num_rows();
					if($numRows > 0){
						while($row5 = $db->fetch_object()){ 
							$po_no= $row5->po_no; 
							$po_no = $po_no+1; 
						} 
					}else{
						 $po_no = $_POST['po_no'];
					}

			 //Values Add Here

			if($_POST['prefix'] !='' && $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$po_no.''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			//Purch Table Section Here
			$addSql = "   	INSERT INTO `".TBL_INV_PURCH."` SET

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`po_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['po_date'])))."',  
							`po_no` = '".addslashes($po_no)."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',   
							`remarks` = '".addslashes($_POST['remarks'])."',
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

							$lastInsertId= $db->insert_id();


				//Purch Details Table Here Detault Value Set
				$addSql = "   	INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

							`id_inv_purch` = '".addslashes($lastInsertId)."',
							`id_inv_po` = '".addslashes($_POST["id_inv_po"])."',  
							`doc_type` = '".'4'."',  
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`qty` = '".addslashes($_POST['variance'])."',   
							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."',   
							`actual_stock` = '".addslashes($_POST['actual_stock'])."',   
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$addSql .= "	,`date_created` = '".currenDateTime()."',
							`last_modified` = '".currenDateTime()."',
							`id_mst_user_modified_by` = '".$_SESSION['userId']."',
							`id_mst_user_created_by` = '".$_SESSION['userId']."',
							`status` = '".addslashes($_POST['status'])."'";
							executeSql($addSql);

				//Purch Details Table Here For Loop Value Set
							$counter1 = $_POST['counter1'];

							for($i = 1; $i <= $counter1; $i++){

								if($_POST['id_inv_items'.''.$i] != ''){

									$addSql = "   	INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET

									`id_inv_purch` = '".addslashes($lastInsertId)."',
									`id_inv_po` = '".addslashes($_POST["id_inv_po".''.$i])."',  
									`doc_type` = '".'4'."',  
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`qty` = '".addslashes($_POST['variance'.''.$i])."',   
									`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."',   
									`actual_stock` = '".addslashes($_POST['actual_stock'.''.$i])."',
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
							}

			if(1){
				//unset($_POST);addslashes(encryptor(decrypt,$_POST[eId]))."'")
				

				$_SESSION['successMsg'] = 'New  Purch has been added sucessfully.';
				header("location:editPhysicalStock.php?eId=".addslashes(encryptor(encrypt,$lastInsertId))."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']."&print=1");
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = ' Purch has not been saved. Please make corrections below.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		 
			checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'update');

			if($_POST['prefix'] !='' && $_POST['suffix'] !=''){
				$mdoc_no = $_POST['prefix'].''.$_POST['po_no'].''.$_POST['suffix'];
			}else{
				$mdoc_no = $_POST['mdoc_no'];
			}
			//Update Purch Table
			 $editSql = " UPDATE `".TBL_INV_PURCH."`  SET  

							`doc_type` = '".addslashes($_POST['doc_type'])."', 
							`po_date` = '".date('Y-m-d' , strtotime(addslashes($_POST['po_date'])))."',  
							`po_no` = '".addslashes($_POST['po_no'])."',  
							`id_doc_type_configuration` = '".addslashes($_POST['id_doc_type_configuration'])."',   
							`remarks` = '".addslashes($_POST['remarks'])."',
							`mdoc_no` = '".addslashes($mdoc_no)."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
							executeSql($editSql);

				//Update Purch Details
							$editSql = "   	UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  
							`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
							`id_inv_po` = '".addslashes($_POST["id_inv_po"])."',  
							`doc_type` = '".'4'."',  
							`id_inv_items` = '".addslashes($_POST['id_inv_items'])."',  
							`qty` = '".addslashes($_POST['variance'])."',   
							`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'])."',   
							`actual_stock` = '".addslashes($_POST['actual_stock'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

							$editSql .= "	
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'
							WHERE `id` = '".addslashes($_POST['update_id'])."'";
							executeSql($editSql);

				//Update Id For Loops Section
							if($_POST['update_count'] == ''){
								$update_count = 0;								
							}else{
								$update_count = $_POST['update_count'];									
							}

							for($i = 1; $i <= $update_count; $i++){

								$editSql = "   	UPDATE `".TBL_INV_PURCH_DETAILS."`  SET  
								`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
								`id_inv_po` = '".addslashes($_POST["id_inv_po".''.$i])."',  
								`doc_type` = '".'4'."',  
								`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
								`qty` = '".addslashes($_POST['variance'.''.$i])."',   
								`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."',   
								`actual_stock` = '".addslashes($_POST['actual_stock'.''.$i])."',
								`id_shop` = '".addslashes($_SESSION['shop'])."'";

								$editSql .= "	
								,`last_modified` = '".currenDateTime()."'
								,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
								,`status` = '".addslashes($_POST['status'])."'
								WHERE `id` = '".addslashes($_POST['update_id'.''.$i])."'";
								executeSql($editSql);
							}
				//Update Field More Fields Add Here

							if($_POST['counter1'] == ''){
								$counter1 = 0;								
							}else{
								$counter1 = $_POST['counter1'];									
							}

							for($i = $counter1; $i > $update_count; $i--){

								 
								if($_POST['id_inv_items'.''.$i] != '' ){

									$addSql = "INSERT INTO `".TBL_INV_PURCH_DETAILS."` SET
									`id_inv_purch` = '".addslashes(encryptor(decrypt,$_POST[eId]))."',
									`id_inv_po` = '".addslashes($_POST["id_inv_po".''.$i])."',  
									`doc_type` = '".'4'."',  
									`id_inv_items` = '".addslashes($_POST['id_inv_items'.''.$i])."',  
									`qty` = '".addslashes($_POST['variance'.''.$i])."',   
									`id_mst_attributes_store` = '".addslashes($_POST['id_mst_attributes_store'.''.$i])."',   
									`actual_stock` = '".addslashes($_POST['actual_stock'.''.$i])."',
									`id_shop` = '".addslashes($_SESSION['shop'])."'";

									$addSql .= "	,`date_created` = '".currenDateTime()."',
									`last_modified` = '".currenDateTime()."',
									`id_mst_user_modified_by` = '".$_SESSION['userId']."',
									`id_mst_user_created_by` = '".$_SESSION['userId']."',
									`status` = '".addslashes($_POST['status'])."'";
									executeSql($addSql);
								}
							}
								
			if(1){  

				$_SESSION['successMsg'] = selectColumn(TBL_INV_PURCH, 'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';  
				header("location:editPhysicalStock.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&action=edit&page=".$_REQUEST['page']."&print=1"); 	exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_INV_PURCH,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = ' Purch has not been saved. Please make corrections.';
	}
}
// ----------cate---------

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Purch Table

	$sql = "  SELECT * FROM `".TBL_INV_PURCH."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		//echo encryptor(decrypt, $_REQUEST['eId']);
	}  
		  			 
}	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>

<title>RoomStatusHUB | Edit Physical Stock Manager</title>
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
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->mdoc_no ?> </span>
            </div>
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

              <div class="box-body">

              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">General</h5>
              		</div> 
              		<hr>

	              	<div class="row">	

	              		<div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Document Type</label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-book"></i> 
						   	</div> 
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()" style="width:100%">                	  		                  	  
			                  	 	<option selected="selected" value="9">Physical Stock</option>  
			                  	</select>	 
	              			<?php 
	              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 
									} 
	              			?></div>
	              			
 
	              		</div>  
	              		<div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Physical Stock Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter Physical Stock Date" id="po_date" name="po_date" value="<?php if($_POST) echo $_POST['po_date'];elseif($row->po_date!='') echo date('d-m-Y',strtotime($row->po_date));else echo date('d-m-Y');?>" onchange="hideandshow()" onclick="hideandshow()">
		                  </div> 
	              		</div>


		                <div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 /*#ind{
	              				 	display: none;
	              				 }*/
	              			</style>
	              			<?php } ?>
	              			<div id="ind" name="ind">
	              				<div class=" col-xs-12 col-md-4 col-sm-6">
	              					<label for="name">Prefix</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php if($_POST) echo $_POST['prefix'];else echo stripslashes($prefix);?>" readonly> 
		              				</div>
			                	</div>
		              			<div class=" col-xs-12 col-md-4 col-sm-6">
		              				<label for="name">Physical Stock No</label>
		              				<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Enter Physical Stock No" id="po_no" name="po_no" value="<?php if($_POST) echo $_POST['po_no'];else echo stripslashes($row->po_no);?>" readonly>
		              				</div> 
			                	</div>
			                	<div class=" col-xs-12 col-md-4 col-sm-6">
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
				                  <label for="name">Manual Physical Stock No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input type="text" class="form-control" placeholder="Enter Manual Physical Stock No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div> 
		                </div> 			                	                
						
		            </div>

		            <div class="row">	

	              		<div class="form-group col-xs-12 col-md-6 col-sm-2">
		                  <label for="name">Remarks</label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-renren"></i> 
						   	</div>
		                  <input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['remarks'];else echo stripslashes($row->remarks);?>"> 
		              		</div>
		                </div> 
 

			            <div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>

		            <div class="row">
	              	</div> 

		        </div>
		        <hr>

		        <!-- The Modal -->
					<div class="modal" id="config_model"   >
					    <div class="modal-dialog">
					      <div class="modal-content"  style="width: 120%;">
					      
					        <!-- Modal Header -->
					        <div class="modal-header">
					          <h4 class="modal-title">Physical Stock</h4>
					          <button type="button" class="close" data-dismiss="modal">&times;</button>
					        </div>
					        
					        <!-- Modal body -->
					        <div class="modal-body">
					        	<table id="popuptable" border="1"> 
					        	</table>
					        </div>
					        
					        <!-- Modal footer -->
					        <div class="modal-footer">
					        	<button type="button" class="btn btn-success ok"  data-dismiss="modal" onclick="po();"><i class="fa fa-plus-circle" aria-hidden="true" > Insert</i></button>
					          <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button>
					        </div> 
		              		</div>
		                </div> 
		            </div>
		            <button type="button" id="config_button" name="config_button" class="btn btn-info" data-toggle="modal" data-target="#config_model"  style="display: none"><i class="fa fa-check-square-o"> PO Help</i>
    				</button>

		         <div class="box-body">

              	<div class="card text-dark bg-light">
              		<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Physical Stock Details</h5>
              		</div>  
	              	<div class="row">
	              		<?php 
              				$sql2 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'6'."' ";
							$db->query($sql2);  
							$numRows= $db->num_rows();

								if($numRows != 0){
									while($row2 = $db->fetch_object()){ 
										$id_indent_id= $row2->id; 
										$id_indent_id = $id_indent_id + 1; 
									}
								}
								else{
									 $id_indent_id = '1'; 
								}
              			?>	
	              		<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;" >
		                  <label for="name"></label>
		                  <input type="text" class="form-control" id="id_inv_po" name="id_inv_po"  value="<?php echo $id_indent_id; ?>" > 
		                </div>
		            </div>

		            <div class="row">
		            	<table id="myTable1" class=" table order-list1">
				            <thead>
				                <tr> 
				                    <td>Item Code</td>
				                    <td>Item Description</td> 
				                    <td>Store</td> 
				                    <td>Stock In Hand</td> 
				                    <td>Actual Stock</td> 
				                    <td>Variance</td>  
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
				            	//Purch Details Here First Row Only Select
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 

								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_po'.''.$i] = $rowsID->id_inv_po;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items; 
							 		 $array['id_mst_attributes_store'.''.$i] = $rowsID->id_mst_attributes_store; 
							 		 $array['actual_stock'.''.$i] = $rowsID->actual_stock; 
							 		 $array['qty'.''.$i] = $rowsID->qty; 
							 		 $i++;
							 		 
								}  
								for($j=0; $j<$i; $j++){ 
								 if($j == 0){
								 	$k='';
								 }else{
								 	$k = $j;
								 }
								 //Stock In Hand
								//GRN DETAILS 
								$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Physical Stock
								// $physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");

								$stock_in_hand = $grn_qty + $openbal_qty - $sin_qty;
				            	?>
				            	<div class="form-group col-xs-12 col-md-6 col-sm-2" style="display: none;"  >
				                  <label for="name">Update Id</label>
				                  <input type="text" class="form-control" id="update_id<?php echo $k;?>" name="update_id<?php echo $k;?>" value="<?php echo $array['id'.''.$j];?>"> 

				                  <label for="name">Update Count</label>
				                  <input type="text" class="form-control" id="update_count" name="update_count" value="<?php echo $k;?>"> 
				                </div>
				                <tr> 
					                
				                	<td class="form-group col-xs-12 col-md-3 col-sm-2"> 
					                 	<input hidden id="select<?php echo $k;?>" name="select<?php echo $k;?>"> 
					                 	<?php 
				                		//Name Get
				                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND status=1 AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                			//Item Description Get
				                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND status=1 AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
				                		?>
				                		<select class="form-control select2" name="id_inv_items<?php echo $k;?>" id="id_inv_items<?php echo $k;?>" onchange="itemget(this.id)" style="width:100%">
										<?php $categoryDropDown = '<option value="">Select Item Code</option>';
											 						 
							                   	$sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1 and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
							                  
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
					                </td> 
				                    <td class="form-group col-xs-12 col-sm-2">
				                       <input type="text"  autocomplete="off" name="item_description<?php echo $k;?>" id="item_description<?php echo $k;?>" placeholder="Item Description"  class="form-control"   value="<?php echo $item_description; ?>" readonly="" />
				                    </td> 
				                    <td class="form-group col-md-2">  
					                	<select class="form-control select2" name="id_mst_attributes_store<?php echo $k;?>" id="id_mst_attributes_store<?php echo $k;?>" style="width:100%">
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
				                    <td class="form-group col-xs-12 col-sm-2"> 
				                        <input type="text"  autocomplete="off"  name="stock_in_hand<?php echo $k;?>" id="stock_in_hand<?php echo $k;?>" placeholder="Stock In Hand" class="form-control" value="<?php if($_POST) echo $_POST['stock_in_hand'];else echo stripslashes($stock_in_hand); ?>" readonly/>
				                    </td>
				                    <td class="form-group col-xs-12 col-sm-2"> 
				                        <input type="text"  autocomplete="off"  name="actual_stock<?php echo $k;?>" id="actual_stock<?php echo $k;?>" placeholder="Actual Stock"  class="form-control" value="<?php if($_POST) echo $_POST['actual_stock'];else echo stripslashes($array['actual_stock'.''.$j]); ?>"  onkeyup="actualcalc(this.id)"/>
				                    </td>
				                    <td class="form-group col-xs-12 col-sm-2">
				                        <input type="text"  autocomplete="off"  name="variance<?php echo $k;?>" id="variance<?php echo $k;?>" placeholder="variance" onkeyup="altqtycalc(this.id)" class="form-control" value="<?php if($_POST) echo $_POST['qty'];else echo stripslashes($array['qty'.''.$j] ); ?>" />
				                    </td>
				                    
				                    <?php if($k>=1){?>
				                    <td> 

					                   	<img src="images/delete.gif"  class="ibtnDel2" style="cursor:pointer;" title="Delete" id="<?php echo $array['id'.''.$j]; ?>"  name="<?php echo $array['id'.''.$j]; ?>"/>
				                    </td>
				                	<?php } 
				                	 if($row->id ==''){
				                	 	$counts = 0;
				                	 }else{
				                	 	$counts = $k;
				                	 }
				                	 ?>
				                	 <td class="form-group col-xs-12 col-sm-2"><a class="deleteRow"></a></td>
				                </tr> 
				            	<?php } ?>
				            	<input type="text" name="counter1" id="counter1" value="<?php echo 
				                    $counts; ?>" hidden=""> 
				            </tbody>
				            <tfoot>
				                <tr> 
			                        <td colspan="9" style="text-align: left;">
			                            <input  type="button" class="btn btn-sm btn-block" id="addrow1" value="Add More" />
			                             <input  type="button" class="btn btn-sm btn-block" id="addrow2" value="Add More" style="display: none;" />
			                        </td> 
				                </tr>
				                <tr>
				                </tr>
				            </tfoot>
				        </table>
		            </div>
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
					<div class="row">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($row->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($row->last_modified));?>">				
		                </div>  
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
				<?php } ?>  
				
         	</div>
              <!-- /.box-body -->	
			 <div class="box-footer">                                       
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-success" name="Save"  >
				&nbsp;&nbsp;&nbsp;&nbsp; 
			   <input type='button' value='Cancel' class="btn btn-danger" onclick='location.replace("managePhysicalStock.php?submenu=<?php echo $_GET['submenu']; ?>"); '>
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

	//Combo Box
	function itemget(clicked_id) {

			var id_inv_items = document.getElementById("id_inv_items");
		    var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;


		    var regex = /[+-]?\d+(?:\.\d+)?/g;
		    var match = parseInt(regex.exec(clicked_id));
			if(match >=1 ){

				var id_inv_items = document.getElementById("id_inv_items"+match);
			   	var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;

		     
 			 
		     	var regex = /[+-]?\d+(?:\.\d+)?/g;
		     	var match = parseInt(regex.exec(clicked_id));
		     	var id_inv_items = document.getElementById("id_inv_items"+match);
		    	var id_inv_items = id_inv_items.options[id_inv_items.selectedIndex].value;
		    	 
 			 
		    $.ajax({

					type: "POST",
					url: "../ajax/PhysicalStockItemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data); 
						document.getElementById("item_description"+match).value = mydata['name'];
						document.getElementById("stock_in_hand"+match).value = mydata['stock_in_hand'];   
						var id_mst_attributes_store = mydata['id_mst_attributes_store'];
						var store = mydata['store']; 
						 
						//Store Section Here
						document.getElementById("id_mst_attributes_store"+match).innerHTML = "<option value='" + id_mst_attributes_store + "' selected='selected'>" + store + "</option>"+"<?php  $sql = "SELECT mst_attributes.*FROM mst_attributes WHERE mst_attributes.id_shop = '".addslashes($_SESSION['shop'])."' AND  status = '1' AND table_name ='store' ";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->field_value ?></option> <?php } ?>"; 
 					}
				}); 
		}else{		 
		 
			 
			if(id_inv_items != '') {

				$.ajax({
					type: "POST",
					url: "../ajax/PhysicalStockItemsget.php",
					data:{id_inv_items:id_inv_items},
					success: function(data){
						//console.log(data); 
						var mydata = JSON.parse(data);
						var id_mst_attributes_store = mydata['id_mst_attributes_store'];
						var store = mydata['store']; 
						document.getElementById("item_description").value = mydata['name']; 
						document.getElementById("stock_in_hand").value = mydata['stock_in_hand']; 
					  
						//Store Section Here
						document.getElementById("id_mst_attributes_store").innerHTML = "<option value='" + id_mst_attributes_store + "' selected='selected'>" + store + "</option>"+"<?php  $sql = "SELECT mst_attributes.*FROM mst_attributes WHERE mst_attributes.id_shop = '".addslashes($_SESSION['shop'])."' AND  status = '1' AND table_name ='store' ";$db->query($sql); while($row1 = $db->fetch_object()){ ?> <option value='<?php echo $row1->id; ?>''><?php echo $row1->field_value ?></option> <?php } ?>";   
					}
				});
			}	
	    
		}
			
	}
 

	//Delete Row Section Here
	 

	$("table.order-list1").on("click", ".ibtnDel2", function (event) { 
		$(this).closest("tr").remove(); 
		var clicked_id = $(this).attr("id");

			$.ajax({
				type: "POST",
				url: "../ajax/PhysicalStockManageDeleteRow.php",
				data:{clicked_id:clicked_id},
				success: function(data){
					console.log(data);
					var mydata = JSON.parse(data);  
					if(mydata['delete'] == 1){      
						alert('Item Deleted');
					} 
				}
			});     
                      
    });

	function hideandshow() {

		var doc_type = document.getElementById("doc_type");
	    var doc_type = doc_type.options[doc_type.selectedIndex].value;

	    var po_date = document.getElementById("po_date").value; 
		 
		if(doc_type != '' && po_date !='') {
			$('#ind').show(); 
			
			$.ajax({
				type: "POST",
				url: "../ajax/PhysicalStockManage.php",
				data:{doc_type:doc_type, po_date:po_date},
				success: function(data){
					var mydata = JSON.parse(data);  
					if(mydata['method'] == 1){
						$('#hideandshow').hide();   
						$('#ind').show();   
						<?php if($row->id == ''){?>
							document.getElementById("po_no").value = mydata['po_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = mydata['prefix'];
						document.getElementById("suffix").value = mydata['suffix'];

					}else{
						$('#hideandshow').show();
						$('#ind').hide(); 
						<?php if($row->id == ''){?> 
							document.getElementById("po_no").value = mydata['po_no'];
							document.getElementById("id_doc_type_configuration").value = mydata['id_doc_type_configuration'];
						<?php } ?>
						document.getElementById("prefix").value = '';
						document.getElementById("suffix").value = '';
					}
				}
			});
		}
	} 

</script>
<?php 
	$sql2 = " SELECT max(po_date) as po_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".'9'."' ";
		$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$po_date= $row2->po_date;
				$po_date = date('d-m-Y' , strtotime(addslashes($po_date)));  
			}  
			if($row->id != '' && $_REQUEST['print'] == 1){ 

?>
<script type="text/javascript">
	var eid = '<?php echo $_GET['eId']; ?>';  
</script>

	<button type="button" id="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal" style="display: none;">
    </button>
	<!-- The Modal -->
	<div class="modal" id="myModal">
	    <div class="modal-dialog">
	      <div class="modal-content"  style="margin-top: 50%; width: 72%;margin-left: 20%;"> 
	       
	        <!-- Modal body -->
	        <div class="modal-body">
	        	<center>
	          <a href="editPhysicalStock.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-success"  id="buttons_radius"><i class="fa fa-plus-circle" aria-hidden="true"> Another Physical Stock</i></a> 
	          <a href="printPhysicalStock.php?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>" target="_blank" type="button" class="btn btn-primary"  id="buttons_radius"><i class="fa fa-print" aria-hidden="true"> Print</i></a> 
	          <button type="button" class="btn btn-danger" data-dismiss="modal"  id="buttons_radius"><i class="fa fa-times-circle" aria-hidden="true"> Cancel</i></button>
	          <a href="managePhysicalStock.php?submenu=<?php echo $_GET['submenu']; ?>" type="button" class="btn btn-info"  id="buttons_radius"><i class="fa fa-info-circle" aria-hidden="true"> Close</i></a>  
        	  <!-- <button type="button" class="btn btn-info" data-dismiss="modal">Cancel</button> -->
        	</center>
	        </div> 
	        
	      </div>
		</div>
	</div>
	<script type="text/javascript">
		document.getElementById('button').click();
	</script>

<?php } ?>

 <script type="text/javascript">
 	$(document).ready(function() {
 		<?php 
 		/*	if($row->id == ''){

			$sql2 = " SELECT max(po_date) as po_date FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='9' ";
			$db->query($sql2);   
			while($row2 = $db->fetch_object()){ 
				$po_date= $row2->po_date; 
				if($po_date == ''){
					$po_date = selectColumn(TBL_DOC_TYPE_CONFIG,'effective_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='9' ");
				}
				$po_date = date('d-m-Y' , strtotime(addslashes($po_date)));  
			}   */

		?>
 		var dates = '<?php echo $po_date; ?>';
 		//document.getElementById("po_date").value = dates; 
 		document.getElementById('po_date').click();  
		$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });

		
	<?php //} ?>
		 
	});

//Actual Stock Calculations
function actualcalc(clicked_id){

	var regex = /[+-]?\d+(?:\.\d+)?/g;
	var match = parseInt(regex.exec(clicked_id));

	if(match >=1){
		var actual_stock = document.getElementById('actual_stock'+match).value;
		var stock_in_hand = document.getElementById('stock_in_hand'+match).value;
		//Calculations
		var total = Number(actual_stock) - Number(stock_in_hand);
		document.getElementById('variance'+match).value = total;
	}else{
		var actual_stock = document.getElementById('actual_stock').value;
		var stock_in_hand = document.getElementById('stock_in_hand').value;
		//Calculations
		var total = Number(actual_stock) - Number(stock_in_hand);
		document.getElementById('variance').value = total;
	}
	
}

//Select 2  Resolve Here


	 

    $("#addrow1").on("click", function () { 	
		
		var counter1 =  document.getElementById("counter1").value;  
        
        counter1++;   

         
        var newRow1 = $("<tr>");
        var cols1 = ""; 
       
       cols1 += '<td><select onchange="itemget(this.id)" name="id_inv_items' + counter1 + '" id="id_inv_items' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Item Code</option><?php 
	                $sql = "SELECT inv_items.*, mst_attributes.field_value FROM inv_items, mst_attributes WHERE  mst_attributes.id=inv_items.id_mst_attributes_group_main and inv_items.status=1  and inv_items.id_shop = '".addslashes($_SESSION['shop'])."'";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->item_code.' | '.$row1->name.' | '.$row1->field_value; ?></option> <?php } 
                  	?></select> </td>';
  
		
		cols1 += '<td><input type="text"  autocomplete="off" placeholder="Item Description" class="form-control" name="item_description' + counter1 + '" id="item_description' + counter1 + '" readonly=""/></td>';

		cols1 += '<td><select  name="id_mst_attributes_store' + counter1 + '" id="id_mst_attributes_store' + counter1 + '" class="form-control select3"  style="width:100%"><option>Select Store</option><?php 
	                $sql = "SELECT mst_attributes.field_value FROM  mst_attributes WHERE id_shop = '".addslashes($_SESSION['shop'])."' and table_name ='store' ";
	                   	 $db->query($sql); 
	                    while($row1 = $db->fetch_object()){ ?>
	                  		<option value="<?php echo $row1->id; ?>"><?php echo $row1->field_value; ?></option> <?php } 
                  	?></select></td>';  

        cols1 += '<td><input type="text"  autocomplete="off" placeholder="Stock In Hand" class="form-control" name="stock_in_hand' + counter1 + '" id="stock_in_hand' + counter1 + '" readonly=""/></td>'; 

        cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Actual Stock" class="form-control" name="actual_stock' + counter1 + '" id="actual_stock' + counter1 + '" onkeyup="actualcalc(this.id)"/></td>';
         cols1 += '<td><input  type="text"  autocomplete="off" placeholder="Variance" class="form-control" name="variance' + counter1 + '" id="variance' + counter1 + '"/></td>'; 

        		  
		cols1 += '<td><img src="images/delete.gif"  class="ibtnDel1" style="cursor:pointer;" title="Delete"/></td>'; 
		document.getElementById("counter1").value = counter1;
		newRow1.append(cols1);
        $("table.order-list1").append(newRow1); 
          $(".select3").select2({});
         
         $(".select3").last().next().next().remove();

          
    });

    $("table.order-list1").on("click", ".ibtnDel1", function (event) {
        $(this).closest("tr").hide();                
    }); 
     

</script>

<script type="text/javascript">
 

	//Quantity Check Here
	function qtycalc(clicked_id){

		<?php  if($row->id ==''){ ?>

		var main_unit = document.getElementById("main_unit").value;
		var alt_unit = document.getElementById("alt_unit").value;

		if(main_unit == alt_unit){
			var qty = document.getElementById("qty").value;
			var kg = 1; 
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}else{
			var qty = document.getElementById("qty").value;
			var kg = 1000; 
			var grams = qty * kg;  
			document.getElementById("alt_qty").value = grams;
		}
		

		<?php  }else{ ?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var qty = document.getElementById("qty").value;
				var kg = 1; 
				var grams = qty * kg;  
				document.getElementById("alt_qty").value = grams;
			}else{
				var qty = document.getElementById("qty").value;
				var kg = 1000; 
				var grams = qty * kg;  
				document.getElementById("alt_qty").value = grams;
			}


			var regex = /[+-]?\d+(?:\.\d+)?/g;
	     	var match = parseInt(regex.exec(clicked_id));

	     	var main_unit_row = document.getElementById("main_unit"+match).value;
			var alt_unit1_row = document.getElementById("alt_unit"+match).value;

			if(main_unit_row == alt_unit1_row){ 
		     	var qty = document.getElementById("qty"+match).value; 	 
				var kg = 1; 
				var grams = qty * kg;  
				document.getElementById("alt_qty"+match).value = grams; 
			}else{
				var qty = document.getElementById("qty"+match).value; 	 
				var kg = 1000; 
				var grams = qty * kg;  
				document.getElementById("alt_qty"+match).value = grams; 
			}
		<?php }?>
 
	}

	//Quantity Rows Section Here Check Here
	function qtycalc_rows(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
     	var match = parseInt(regex.exec(clicked_id)); 
     	var main_unit_row = document.getElementById("main_unit"+match).value;
		var alt_unit1_row = document.getElementById("alt_unit"+match).value;

		if(main_unit_row == alt_unit1_row){ 
	     	var qty = document.getElementById("qty"+match).value; 	 
			var kg = 1; 
			var grams = qty * kg;  
			document.getElementById("alt_qty"+match).value = grams; 
		}else{
			var qty = document.getElementById("qty"+match).value; 	 
			var kg = 1000; 
			var grams = qty * kg;  
			document.getElementById("alt_qty"+match).value = grams; 
		}
 
	}

	//Alt Quantity Check Here
	function altqtycalc(clicked_id){

		<?php  if($row->id ==''){?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}else{
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1000; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}

			


		<?php  }else{ ?>

			var main_unit = document.getElementById("main_unit").value;
			var alt_unit = document.getElementById("alt_unit").value;

			if(main_unit == alt_unit){
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}else{
				var alt_qty = document.getElementById("alt_qty").value;
				var kg = 1000; 
				var qty = alt_qty / kg;  
				document.getElementById("qty").value = qty; 
			}

			var regex = /[+-]?\d+(?:\.\d+)?/g;
	     	var match = parseInt(regex.exec(clicked_id));

	     	var main_unit_row = document.getElementById("main_unit"+match).value;
			var alt_unit1_row = document.getElementById("alt_unit"+match).value;

			if(main_unit_row == alt_unit1_row){ 
		     	var alt_qty = document.getElementById("alt_qty"+match).value; 
				var kg = 1; 
				var qty = alt_qty / kg;  
				document.getElementById("qty"+match).value = qty;  
			}else{
				var alt_qty = document.getElementById("alt_qty"+match).value; 
				var kg = 1000; 
				var qty = alt_qty / kg;  
				document.getElementById("qty"+match).value = qty;  
			}		 
	     	 

		<?php }?>

		 
	}

	//Alt Quantity Check Here
	function altqtycalc_rows(clicked_id){

		var regex = /[+-]?\d+(?:\.\d+)?/g;
     	var match = parseInt(regex.exec(clicked_id)); 
     	var main_unit_row = document.getElementById("main_unit"+match).value;
		var alt_unit1_row = document.getElementById("alt_unit"+match).value;

		if(main_unit_row == alt_unit1_row){ 
	     	var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = 1; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}else{
			var alt_qty = document.getElementById("alt_qty"+match).value; 
			var kg = 1000; 
			var qty = alt_qty / kg;  
			document.getElementById("qty"+match).value = qty;  
		}    			 
	}
</script>	



