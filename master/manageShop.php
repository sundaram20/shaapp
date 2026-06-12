<?php include_once("../config/auto_loader.php");


checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'view');
$image_path = $UPLOAD_FILES.'/shop/';
$image_display_path = $UPLOAD_FILES_PATH ."/shop/";

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_SHOP."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed successfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}				
		}
	}elseif($_REQUEST['inactiveId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_SHOP."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
			
			if(executeSql($statusSql)){
				$err = 0;
				$_SESSION['successMsg'] = 'status has been changed sucessfully.';
			}else{
				$err = 1;
				$_SESSION['errorMsg'] = 'status has not been changed.';
			}		
		}				
	}
	
}else if($_REQUEST["action"] == "delete" && !empty($_REQUEST['delId'])){
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_SHOP,'delete')){	
		$deleteIds = encryptor(decrypt,$_REQUEST['delId']);	
		$delSql = "DELETE FROM `".TBL_SHOP."` WHERE `id` IN (".addslashes($deleteIds).")";
		$delSqlImage = selectSql(TBL_SHOP,"where `id` in (".addslashes($deleteIds).") ",'');	
		if(executeSql($delSql)){		
			$err = 0;
			while($delResultImage = mysqli_fetch_array($delSqlImage)){
				if(file_exists($image_path.$delResultImage['image'])){
					@unlink($image_path.$delResultImage['image']);
					@unlink($image_path.'small-'.$delResultImage['image']);
					@unlink($image_path.'medium-'.$delResultImage['image']);
				}
			}
			$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete selected records';
		}
	}
}

// ----------cate---------

$sqlUser = " SELECT * FROM `".TBL_SHOP."` WHERE `id` = '".addslashes($_SESSION['shop'])."'";
if($_REQUEST['search_name'] != ''){
	$sqlUser .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sqlUser .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['order'] != ''){
	$sqlUser .= " ORDER BY `name`";
}else{
	$sqlUser .= " ORDER BY `name`";
}

$db->query($sqlUser);
$numRows= $db->num_rows();

//$pagging = new pagingClass($sqlUser,$setpage);
//$db->query($pagging->getQuery());
$total = $db->num_rows();
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
	
	
   <!--  <section class="content-header">
      <h1>
         <span style="color: #f25e74;">  User Manager </span>
        <small>Manage Shop</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageShop.php">User  Manager</a></li>
        <li class="active">Manage Shop</li>
      </ol>
    </section>  -->
    <!-- Main content -->
    <section class="content">	
	
	<div class="box box-default">
	 <div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div>
        <div class="box-header with-border">
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
			<div class="btn-group  pull-right">
                  <a type="button" class="btn btn-success" href="editShop.php">Add Shop</a>
                  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu" role="menu">
                   <?php /*?> <li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_SHOP;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
                    <li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_SHOP;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php */?>
                  
                  </ul> -->
                </div>
				
        </div>
        <!-- /.box-header -->
		
		
		<form name="searchForm" action="" method="get">
           <input type="hidden" value="1" name="searchFormSubmit" /> 
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              
              <!-- /.form-group -->
              <div class="form-group">
                <label>User Title</label>
                <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
              </div>
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
            <div class="col-md-6">
              <div class="form-group">
                <label>Status</label>
                <?php 
						if($_REQUEST['status'] == '1'){
								$selected1 = 'selected="selected"';
						}elseif($_REQUEST['status'] == '0'){
								$selected0 = 'selected="selected"';
						}
						 echo $statusDropDown	 = '<select class="form-control select2" name="status"> <option value="">Both</option>
											 		 	<option '.$selected1.' value="1">Active</option>
											 		 	<option '.$selected0.' value="0">Inactive</option>
											  		</select>';
								?>
              </div>              
              <!-- /.form-group -->
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->
        </div>
        <!-- /.box-body -->
        <div class="box-footer">
        <input name="Search" type="submit" class="btn btn-primary" value="Search" />
        </div>
		</form>
		
		
      </div>
	  
	  
      <div class="row">
        <div class="col-xs-12">    
		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Users List</h3>
            </div>
			 <form name="listingForm" action="" method="post">
                <input type="hidden" value="" name="act" />
				  <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th width="10%">S.No.</th>
                  <th>Name</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				 <?php 
				if($total > 0){$counter = 1;
				  while($rowUser = $db->fetch_object()){?>
                <tr>
                  <td><?php echo $counter++;?>.&nbsp;</td>
                  <td><?=ucfirst($rowUser->name);?></td>
                  <td><?=$rowUser->status=='1'?'<span onclick="location.href=\'manageShop.php?inactiveId='.encryptor(encrypt,$rowUser->id).'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageShop.php?activeId='.encryptor(encrypt,$rowUser->id).'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>
				 
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editShop.php?eId=<?=$rowUser->id?>&action=edit';" />&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $rowUser->name; ?>" id="<?php echo encryptor(encrypt,$rowUser->id);?>" onClick="deletes(this.id);"/> </td>
                </tr>
               <?php }?>    
			   
			    <!--<tr>
                     <td align="left" colspan="5">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
                 </tr>             
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
                 </tr>                 
				<?php }?>
                </tbody>                
              </table>			  
            </div>
		  </form>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div> 

  <script type="text/javascript">
  	function deletes(sid)
    {  
    swal({
      title: "Are you sure?",
      text: "Delete?",
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: '#DD6B55',
      confirmButtonText: 'Yes, I am sure!',
      cancelButtonText: "No, cancel it!",
      closeOnConfirm: false,
      closeOnCancel: false
      },
     
     function(isConfirm)
     {

     if (isConfirm)
     {  
          self.location='manageShop.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
     } 
     else
     {
        self.location='manageShop.php';
      }
     });
    }
  </script> 

<?php include_once("../includes/footer.php")?>  
                                             
