<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_COUNTRY_LANG,'view');

//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COUNTRY_LANG,'activate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_COUNTRY_LANG."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_country` = '".$statusId."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_COUNTRY_LANG,'deactivate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_COUNTRY_LANG."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id_country` = '".$statusId."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = ''.selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$statusId."'").' status has been changed sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = ''.selectColumn(TBL_COUNTRY_LANG,'name'," WHERE `id_country` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_COUNTRY_LANG,'delete');
	$delSql = "DELETE FROM `".TBL_COUNTRY_LANG."` WHERE `id_country` = '".$_REQUEST['delId']."'";
	$sqlDelUserLevel = selectRow(TBL_COUNTRY_LANG," WHERE `id_country` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		
		$_SESSION['successMsg'] = 'One '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete  '.$sqlDelUserLevel["name"];
	}
}

// ----------cate---------
$sql = " SELECT * FROM `".TBL_COUNTRY_LANG."` WHERE `id_lang` = '1' ";


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
     <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

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
							  <a type="button" class="btn btn-success" href="editCountry.php" >Add Country</a>
							  <!-- <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_ATTRIBUTES;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_ATTRIBUTES;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul> -->
							</div>
          
        </div> 	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Country List</h3>
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
		                  		<th>Country Name</th>
						  		<th>Nationality</th>
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
			                  			<td><?php echo $row->name;?></td>
			                  			<td><?php echo $row->nationality;?></td>  

			                  			<td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageCountry.php?inactiveId='.encryptor(encrypt,$row->id_country).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCountry.php?activeId='.encryptor(encrypt,$row->id_country).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

			                  			<?php 
			                  			//echo $row->id_country;
			                  				$table_name = array(TBL_HOTELS, TBL_OUTLETS, TBL_COMPANY, TBL_COMPANY_CONTACTS, TBL_GUEST);
							  				$ajaxCheckTransactions = CheckTransactionsCountry($row->id_country, $table_name); 

											if($ajaxCheckTransactions != '1'){
			                 	 		?>
							 			<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCountry.php?eId=<?=encryptor(encrypt,$row->id_country)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
							  			<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id_country;?>" onClick="deletes(this.id)"/></td>
							  			<?php } else{ ?> 
							  			<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCountry.php?eId=<?=encryptor(encrypt,$row->id_country)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
							  			<img src="../images/chat.png" style="cursor:pointer; " title="In Use" /> </td>
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
	        self.location='manageCountry.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
	   } 
	   else
	   {
	       self.location='manageCountry.php';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  