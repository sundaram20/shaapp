<?php include_once("../config/auto_loader.php");

if($_REQUEST['eId']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_POS_GUEST,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_POS_GUEST,'edit');
//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

	$err = 0; 
	if(empty($_POST['mobile']) && empty($_POST['name'])){

		$err++;

		$err_mobile = '<font style="color:red;font-weight:normal;" >Please enter user Name or  mobile no .</font>';

	}
	

//	if($db->num_rows2(selectSql(TBL_POS_GUEST,"WHERE `id` NOT IN('".$_REQUEST[eId]."') AND `mobile` = '".addslashes($_POST['mobile'])."'",''))){
    	$sql = " SELECT * FROM `".TBL_POS_GUEST."`  WHERE  `id` NOT IN('".addslashes(encryptor(decrypt,$_POST[eId]))."') AND `mobile`='".$_REQUEST['mobile']."' ";
			//echo $sql;

		//echo $sql;

			$db->query($sql);
			$numRows= $db->num_rows();

			if($numRows > 0){  
		$err++;

		$err_mobile = '<font style="color:red;font-weight:normal;" >Mobile no all-ready exists in our database.</font>';

	}

	
	if($err == 0){//No error
		if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){//add

		//	$sql = " SELECT * FROM `".TBL_POS_GUEST."`  WHERE `mobile`='".$_REQUEST['mobile']."' ";
			//echo $sql;

		

		/*	$db->query($sql);
			$numRows= $db->num_rows();

			if($numRows == '0'){  */

			checkUserLevelPermission($_SESSION['userLevel'],TBL_POS_GUEST,'add');
			$addSql = "   	INSERT INTO `".TBL_POS_GUEST."` SET

							
							`name` = '".addslashes(trim($_POST['name']))."',
							`mobile` = '".addslashes($_POST['mobile'])."' ";

			$addSql .= "	,`date_created` = '".currenDateTime()."'
							,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`id_mst_user_created_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."'";


			if(executeSql($addSql)){
				//unset($_POST);
				$lastInsertId= $db->insert_id();
				$_SESSION['successMsg'] = 'New POS Guest details has been added sucessfully.';
				header("location:managePosGuest.php?eId=".encryptor(encrypt,$lastInsertId)."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = 'POS Guest details has not been saved. Please make corrections below.';
			}
			/*}else{
				$err++;
				$_SESSION['errorMsg'] = 'POS Guest Mobile No Already Exist.';
			}  */
		}

		//Update Section Here

		else if(($_POST['Save'] == 'Edit') && !empty($_POST['eId'])){//update
		
		
			checkUserLevelPermission($_SESSION['userLevel'],TBL_POS_GUEST,'update');
			 $editSql = "   	UPDATE `".TBL_POS_GUEST."` SET 
						
							`name` = '".addslashes(trim($_POST['name']))."',
							`mobile` = '".addslashes($_POST['mobile'])."'";
			 
			$editSql .= "	,`last_modified` = '".currenDateTime()."'
							,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
							,`status` = '".addslashes($_POST['status'])."' 
							WHERE `id` = '".addslashes(encryptor(decrypt,$_POST[eId]))."'";
								
			if(executeSql($editSql)){
				$_SESSION['successMsg'] = selectColumn(TBL_POS_GUEST,'name'," WHERE  `id`= '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has been updated sucessfully.';
				header("location:managePosGuest.php?eId=".$_GET['eId']."&submenu=".$_GET['submenu']."&session=".$_GET['session']."&action=edit&page=".$_REQUEST['page']);
				exit;
			}else{
				$err++;
				$_SESSION['errorMsg'] = selectColumn(TBL_POS_GUEST,'name'," WHERE  'id' = '".addslashes(encryptor(decrypt,$_POST[eId]))."'").' details has not been saved.Please make corrections below.';
			}
		}
	}else{//Error
		$err++;
		$_SESSION['errorMsg'] = 'POS Guest details has not been saved. Please make corrections.';
	}
}
// ----------cate---------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	$sql = "  SELECT * FROM `".TBL_POS_GUEST."`
								WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  ";
							//	echo $sql;
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
   	
   <?php  $session=$_GET['submenu']; ?>
   <section class="content-header">
      <div class="row">
	     <div class="col-md-4 col-xs-12"> 
		      <h6 class="p-0 m-0">
				<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

		        <?php //echo currentNavigation()['submenu']; ?>
		      </h6>
        </div>
       <div class="col-md-4 col-xs-12 dd-f">	
     
                      
       </div> 
       <div class="col-md-4 col-xs-12 tb-br">
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
         
           
			 <div class="nav-tabs-custom">
			<ul class="nav nav-tabs">
			   <li class="active" ><a href="#tab_1" data-toggle="tab">Overview</a></li>  
            </ul>
			<div class="box-header with-border">
             <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo $row->field_value ?> </span>
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
                  <label for="name">Guest Name</label>
              
                  <input type="text" class="form-control" placeholder="Enter  Name" id="name" name="name" value="<?php if($_POST) echo $_POST['name'];else echo stripslashes($row->name);?>"  >
			
                </div>
				
				<div class="form-group">
                  <label for="name">Mobile No</label>
                
                  <input type="text" class="form-control" placeholder="Enter Mobile No" id="mobile" name="mobile" value="<?php if($_POST) echo $_POST['mobile'];else echo stripslashes($row->mobile);?>"  >
			    <?php echo $err_mobile;?>
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
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn c-btn" name="Save" >
				&nbsp;&nbsp;&nbsp;&nbsp;
			   <input type='button' value='Close' class="btn c-btn" onclick='location.replace("managePosGuest.php?submenu=<?php echo $_GET["submenu"]; ?>&session=<?php echo $_GET["session"];?>"); '>
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


