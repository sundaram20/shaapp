<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'edit');


//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	
	
	//Insert Here
	$_POST['ids_mst_user']=implode(',',$_POST['ids_mst_user']);
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

			$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `field_value` = '".addslashes(trim($_POST['field_value']))."' and `table_name` = 'printer' ";
			$db->query($sql);
			$numRows= $db->num_rows();
			if($numRows == '0'){

			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'add');
			$addSql = "   	INSERT INTO `".TBL_ATTRIBUTES."` SET

							`table_name` = 'printer',
							`field_name` = 'printer_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_category` = '".addslashes(trim($_POST['field_category']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
							`ids_mst_user` = '".addslashes($_POST['ids_mst_user'])."',
							`printer_port`= '".addslashes($_POST['printer_port'])."',
							
							`id_shop` = '".addslashes($_SESSION['shop'])."'";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";
			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New Printer details has been added sucessfully.';
				header("location:managePrinters.php?eId=".encryptor(encrypt,$lastInsertId)."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Printer details has not been saved. Please make corrections below.';
			}
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'Printer Master Name Already Exist.';
			}
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'update');
			 $editSql = "   	UPDATE `".TBL_ATTRIBUTES."` SET 
							`table_name` = 'printer',
							`field_name` = 'printer_name',
							`field_value` = '".addslashes(trim($_POST['field_value']))."',
							`field_category` = '".addslashes(trim($_POST['field_category']))."',
							`field_description` = '".addslashes($_POST['field_description'])."',
							`printer_port`= '".addslashes($_POST['printer_port'])."',
							`ids_mst_user` = '".addslashes($_POST['ids_mst_user'])."',
							`id_shop` = '".addslashes($_SESSION['shop'])."'";
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
						//echo $editSql;die;		
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:managePrinters.php?eId=".$_GET['eId']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'Printer details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_ATTRIBUTES."`
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
	 <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
	
    <!-- <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <ol class="breadcrumb">
        <li><a href="managePrinters.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Printer</li>
      </ol>
    </section> -->
    <!-- Main content -->
    <section class="content">
	
	
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Printers</a></li>  
            </ul>
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->field_value ?> </span>
			  <a><?php echo selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
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
                
				 <div class="form-group">
                  <label for="name">Printer Name<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-print"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Printer Name" id="field_value" name="field_value" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  data-parsley-required>
				<?php echo $err_unit_name;?></div>
                </div>
				
                
                <div class="form-group">
                  <label for="name">Type<font color="#FF0000">*</font></label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-print"></i> 
					   	</div>
                <?php 
				if($row->field_category==1)
                		$select1 = 'selected="selected"';
                	elseif($row->field_category==2)
                		$select2 = 'selected="selected"';
						?>
                  
                  <select class="form-control select2" name="field_category" id="field_category" >
                               <option value="1" <?php echo $select1; ?>>  USB</option>
                                <option value="2" <?php echo $select2; ?>> NETWORK</option>
                                
                      </select>
				<?php echo $err_unit_name;?></div>
                </div>
				
                
                
                
				 <div class="form-group">
                  <label for="name">IP Address</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Ip Address" id="field_description" name="field_description" value="<?php if($_POST) echo $_POST['field_field_description'];else echo stripslashes($row->field_description);?>" >
				<?php echo $err_unit_field_description;?></div>
                </div>
                
                
               <div class="form-group">
                  <label for="name">IP PORT</label>
                  <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div>
                  <input type="text" class="form-control" placeholder="Enter Ip PORT" id="printer_port" name="printer_port" value="<?php if($_POST) echo $_POST['printer_port'];else echo stripslashes($row->printer_port);?>" >
				<?php echo $err_unit_printer_port;?></div>
                </div> 
                
                <div class="form-group"> 
	        	<label for="outlet_access">Allowed Users</label>
                <div class="input-group"> 
              			<div class="input-group-addon">
							<i class="fa fa-audio-description"></i> 
					   	</div> 
	        	<?php
	        		$outletSql = "SELECT * FROM ".TBL_USERS." WHERE id_shop='".$_SESSION['shop']."'  AND status = '1' ";
	        		$resOutlet = mysqli_query($connNew,$outletSql);

	        	?>
	       		<select class="form-control select2" name="ids_mst_user[]" multiple="multiple" id="ids_mst_user" placeholder="Select User" style="width: 100%"> 
	       			<?php 
	       				while($rowOutlet = mysqli_fetch_object($resOutlet)) { 
	       					
	       					if(in_array($rowOutlet->id, explode(',',$row->ids_mst_user)))
	       						$selected="selected='selected'";
	       					else
	       						$selected="";	
	       			?>
						<option <?php echo $selected; ?> value="<?php echo $rowOutlet->id ;?>"><?php echo $rowOutlet->name; ?></option>
	       			<?php } ?>	
	      		</select></div>
	      		<p class="help-block">&nbsp;Leave Empty for all Users.</p>
	    	</div>
                
                
                
                
                
                
                
                <?php 
		        	if($row->status == ''){
		        		$status = 1;
		        	}else{
		        		$status = $row->status;
		        	}
		        ?>  
           				                				
				<div class="form-group">
                  <label for="status">Status</label>
                 <input class="flat-red" type="radio"  <?php if($_POST['status'] == '1'){echo "checked";}else{if($status == 1)echo "checked";}?> value="1" name="status"/> Active
				 <input class="flat-red" type="radio" <?php if($_POST['status'] == '0'){echo "checked";}else{if($status == 0)echo "checked";}?> value="0" name="status"/> Inactive
				 <?php echo $err_status;?>
                </div>
				
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
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn btn-danger" onclick='location.replace("managePrinters.php"); '>
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


