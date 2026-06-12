<?php include_once("../config/auto_loader.php");



$image_path = $UPLOAD_FILES.'/items/';
$image_display_path = $UPLOAD_FILES_PATH ."/items/";
//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_INV_ITEMS."`
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

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_INV_ITEMS."` 
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
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_ITEMS,'delete')){	
		$deleteIds = encryptor(decrypt,$_REQUEST['delId']);	
		$delSql = "DELETE FROM `".TBL_INV_ITEMS."` WHERE `id` IN (".addslashes($deleteIds).")";
		$delSqlImage = selectSql(TBL_INV_ITEMS,"where `id` in (".addslashes($deleteIds).") ",'');	
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
}

// ----------cate---------
//$sql = " SELECT * FROM `".TBL_INV_ITEMS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";
// if(!empty($_SESSION['hotel_access'])){
// $sql .= " AND `id` in (".addslashes($_SESSION['hotel_access']).")";
// }
// if($_REQUEST['search_name'] != ''){
// 	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
// }
// if($_REQUEST['status'] != ''){
// 	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
// }
// if($_REQUEST['hotel_category'] != ''){
// 	$sql .= " AND `hotel_category` = '".addslashes($_REQUEST['hotel_category'])."'";
// }

// if($_REQUEST['order'] != ''){
// 	$sql .= " ORDER BY `date_created` DESC";
// }else{
// 	$sql .= " ORDER BY `date_created` DESC";
// }
//echo $sql;

// $db->query($sql);
// $numRows= $db->num_rows();
// $pagging = new pagingClass($sql,$setpage);
// $db->query($pagging->getQuery());
// $total = $db->num_rows();
//var_dump($numRows);
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Items Manager
        <small>Manage Items</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Items</li>
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
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="editItems.php" >Add Items</a>
							  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_INV_ITEMS;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_INV_ITEMS;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>-->
							</div>
          
        </div> 	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Items List</h3>
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
                  <th>Item Name</th>
                  <th>Item Type</th>
				  <th>Item MainUnit</th> 
				  <th>MainGroup</th>
				  <th>SubGroup</th> 
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php 
			        	
			        	 $resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."' "); 
						  while($row = $db->fetch_object2($resCat)){ 
				  	?>
                  <tr>

                  <td><?php echo $sno++;?></td>
                  <td><?php echo $row->name;?></td>
                  <td>
                  	<?php 
	                  $sql1 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_mst_attributes_item_type."'";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 
	                  		echo $row1->field_value; 
	                  	} 
                  	?>
                  	
                  </td>
                  <td>
                  	<?php 
	                  $sql2 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_mst_attributes_unit_main."'";
	                   $db->query($sql2); 
	                   while($row2 = $db->fetch_object()){ 
	                  		echo $row2->field_value; 
	                  	} 
                  	?>
                  </td>  
                  <td>
                  	<?php 
	                  $sql3 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_mst_attributes_group_main."'";
	                   $db->query($sql3); 
	                   while($row3 = $db->fetch_object()){ 
	                  		echo $row3->field_value; 
	                  	} 
                  	?>
                  </td>
                  <td>
                  	<?php 
	                  $sql4 = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id`='".$row->id_mst_attributes_group_sub."'";
	                   $db->query($sql4); 
	                   while($row4 = $db->fetch_object()){ 
	                  		echo $row4->field_value; 
	                  	} 
                  	?>
                  </td>

                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageItems.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageItems.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editItems.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id)"/></td>
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
  	
  	//
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
	        self.location='manageItems.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
	   } 
	   else
	   {
	      self.location='manageItems.php';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  