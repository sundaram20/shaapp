<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'view');
$image_path = $UPLOAD_FILES.'/hotel_gallery/';
$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";
//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'activate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'deactivate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_ATTRIBUTES."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".$statusId."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'delete');
	$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` = '".$_REQUEST['delId']."'";
	$sqlDelUserLevel = selectRow(TBL_ATTRIBUTES," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		if(file_exists($image_path.$sqlDelUserLevel['image'])){
			@unlink($image_path.$sqlDelUserLevel['image']);
			@unlink($image_path.'small-'.$sqlDelUserLevel['image']);
			@unlink($image_path.'medium-'.$sqlDelUserLevel['image']);
		}
		$_SESSION['successMsg'] = 'One Unit '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete unit '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_ATTRIBUTES."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_ATTRIBUTES."` WHERE `id` IN (".addslashes($deleteIds).")";
	$delSqlImage = selectSql(TBL_ATTRIBUTES,"where `id` in (".addslashes($deleteIds).") ",'');	
	if(executeSql($delSql)){		
		$err = 0;
		while($delResultImage = mysql_fetch_array($delSqlImage)){
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

// ----------cate---------
$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'table_group'  ";
if(!empty($_SESSION['hotel_access'])){
$sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";
}
if($_REQUEST['search_name'] != ''){
	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
}
if($_REQUEST['hotel_category'] != ''){
	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";
}

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `date_created` DESC";
}else{
	$sql .= " ORDER BY `date_created` DESC";
}
//echo $sql;

$db->query($sql);
$numRows= $db->num_rows();
// $pagging = new pagingClass($sql,$setpage);
// $db->query($pagging->getQuery());
// $total = $db->num_rows();
//var_dump($total);
?>
<?php include_once("../includes/header.php");?>
<?php include_once("../includes/left.php");?>
<?php include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    
   <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
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
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="editTableGroup.php?submenu=<?php echo $_GET['submenu']; ?>" >Add Table Group</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&submenu=<?php echo $_GET['submenu']; ?>&tableName=<?php echo TBL_ATTRIBUTES;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&submenu=<?php echo $_GET['submenu']; ?>&tableName=<?php echo TBL_ATTRIBUTES;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div> 	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Table Group List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            	<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Table Group Name</th>
				  <th>Table Group Description</th>
				   <th>Display Order</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php 
		        	$i=1;		 
				  while($row = $db->fetch_object()){ ?>
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td><?php echo $row->field_value;?></td>
                  <td><?php echo $row->field_description;?></td>  
                  <td><input type="number" disabled="" value ="<?php echo $row->display_order;?>"></td>  

                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageTableGroup.php?inactiveId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageTableGroup.php?activeId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

                  <?php 
                  		$table_name = array(TBL_PURCH);						
				  		$ajaxCheckTransactions = CheckTransactionsTableGroup($row->id, $table_name); 

						if($ajaxCheckTransactions != '1'){
                 	 ?>
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editTableGroup.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>&submenu=<?php echo $_GET['submenu']; ?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deletes(this.id)"/></td>
				  	<?php } else{ ?> 
				  		<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editTableGroup.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>&submenu=<?php echo $_GET['submenu']; ?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  		<img src="../images/chat.png" style="cursor:pointer; " title="In Use" /> 
				  	</td>
				  	 <?php } ?>
              </tr>
               <?php } ?>  
				 
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
  	//Data Table Script
  	
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
	        self.location='manageTableGroup.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>&submenu=<?php echo $_GET['submenu']; ?>';
	   } 
	   else
	   {
	       self.location='manageTableGroup.php?submenu=<?php echo $_GET['submenu']; ?>';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  