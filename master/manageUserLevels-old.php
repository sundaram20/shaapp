<?php include_once("../config/auto_loader.php");

if($_REQUEST['action'] == 'change'){
  
  if($_REQUEST['activeId'] != ''){

    if(checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'active')){

    $statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
    $statusSql = "  UPDATE `".TBL_USER_LEVELS."`
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

    if(checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'inactive')){
    
    $statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
    $statusSql = "  UPDATE `".TBL_USER_LEVELS."` 
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
  
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
  
  if(checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_LEVELS,'delete')){
    $delSql = "DELETE FROM `".TBL_USER_LEVELS."` WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
  
    if(executeSql($delSql)){    
      $err = 0;
      $_SESSION['successMsg'] = 'One Unit  has been deleted sucessfully.';
    }else{
      $err = 1;
      $_SESSION['errorMsg'] = 'Unable to delete unit ';
    }
  } 
}



// ----------cate---------
if($_POST['Search'] == 'Search' && $_POST['search_name'] != ''){
	$sqlUserLevel = " SELECT * FROM `".TBL_USER_LEVELS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `name` LIKE '%".addslashes($_POST['search_name'])."%'ORDER BY `display_order`,`name`";
}else{
	$sqlUserLevel = " SELECT * FROM `".TBL_USER_LEVELS."` WHERE  `id_shop` = '".addslashes($_SESSION['shop'])."' ORDER BY `display_order`,`name`";
}
$db->query($sqlUserLevel);
$numRows =$db->num_rows();
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        User Levels Manager
        <small>Manage User Levels</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUserLevels.php">User Levels Manager</a></li>
        <li class="active">Manage User Levels</li>
      </ol>
    </section>
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
							  <a type="button" class="btn btn-success" href="editUserLevels.php" >Add User Level</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_USER_LEVELS;?>"><img src="images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_USER_LEVELS;?>"><img  src="images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="post">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label> User Level Title</label>				
				        <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
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
              <h3 class="box-title">User Levels List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>&nbsp;</th>
                  <th>User Level Title</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php if($numRows > 0){$counter = 1;
						while($rowUserLevel = $db->fetch_object()){?>
                <tr>
                  <td><?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$rowUserLevel->name;?></td>
                  <td><?=$rowUserLevel->status=='1'?'<span onclick="location.href=\'manageUserLevels.php?inactiveId='.encryptor(encrypt,$rowUserLevel->id).'&action=change\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageUserLevels.php?activeId='.encryptor(encrypt,$rowUserLevel->id).'&action=change\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>				 
				  <td><img src="images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUserLevels.php?eId=<?=$rowUserLevel->id?>&action=edit';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $rowUserLevel->name; ?>" id="<?php echo encryptor(encrypt,($rowUserLevel->id));?>" onClick="deletes(this.id)";/></td>
                </tr>
               <?php }?>               
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
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
          self.location='manageUserLevels.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
     } 
     else
     {
        self.location='manageUserLevels.php';
      }
     });
    }
  </script> 

<?php include_once("../includes/footer.php")?>  