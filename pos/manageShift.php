<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_ATTRIBUTES,'view');

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
	executeSql($delSql);
	$delSql = "DELETE FROM `mst_attributes_shift` WHERE `id_mst_attributes_shift` = '".$_REQUEST['delId']."'";

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


// ----------cate---------
$sql = " SELECT * FROM `".TBL_ATTRIBUTES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'Shift'  ";

if($_REQUEST['search_name'] != ''){
	$sql .= " AND `name` LIKE '%".addslashes($_REQUEST['search_name'])."%'";
}
if($_REQUEST['status'] != ''){
	$sql .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
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
    <div class="row">
		<div class="col-md-4 col-xs-12"> 
		<h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
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
							  <a type="button" class="btn c-btn2" href="editShift.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']?>" >Add Shift</a>
							  <button type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ATTRIBUTES;?>&submenu=<?php echo $_GET['submenu']; ?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ATTRIBUTES;?>&submenu=<?php echo $_GET['submenu']; ?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div> 	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header  table-h text-center">
              <h3 class="box-title">Shift List</h3>
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
                  <th>Shift Name</th>
				  <th>Shift Description</th>
                  <th>From Time</th>
                  <th>To Time</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
	<?php 

	$i=1;		 
	while($row = $db->fetch_object()){
	
	$from	=	selectColumn('mst_attributes_shift','shift_from'," WHERE  id_mst_attributes_shift='".addslashes($row->id)."' ");
	$to	  =	selectColumn('mst_attributes_shift','shift_to'," WHERE  id_mst_attributes_shift='".addslashes($row->id)."' "); 


	?>
                  <tr>
                  <td><?php echo $i++; ?></td>
                  <td><?php echo $row->field_value; ?></td>
                  <td><?php echo $row->field_description; ?></td>  
                
				  <td><?php echo $from!=''?stripslashes(date('h:i A',strtotime($from))):''; ?></td>
                  <td><?php echo $to!=''?stripslashes(date('h:i A',strtotime($to))):'';?></td>
                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageShift.php?inactiveId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageShift.php?activeId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		
           <?php 
				$table_name = array(TBL_PURCH);						
				$ajaxCheckTransactions = CheckTransactionsSteward($row->id, $table_name); 
				if($ajaxCheckTransactions != '1'){
					
                 	 ?>
				  <td><img src="../images/edit.png" style="cursor:pointer;height:20px;" title=" View / Edit " onClick="window.location.href='editShift.php?eId=<?=encryptor(encrypt,$row->id)?>&submenu=<?php echo $_GET['submenu']; ?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/close.png" style="cursor:pointer;height:15px;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deletes(this.id)"/></td>
				  	<?php } else{ ?> 
				  		<td><img src="../images/edit.png" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editShift.php?eId=<?=encryptor(encrypt,$row->id)?>&submenu=<?php echo $_GET['submenu']; ?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  		<img src="../images/chat.png" style="cursor:pointer;height:20px; " title="In Use" /> 
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
	        self.location='manageShift.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';
	   } 
	   else
	   {
	       self.location='manageShift.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  