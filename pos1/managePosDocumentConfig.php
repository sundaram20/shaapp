<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'view');

//---------------------------------------------------------------------------------------------------------
if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_DOC_TYPE_CONFIG."`
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
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_DOC_TYPE_CONFIG."` 
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
	
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_DOC_TYPE_CONFIG,'delete')){
		$delSql = "DELETE FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
	
		if(executeSql($delSql)){		
			$err = 0;
			$_SESSION['successMsg'] = 'One Unit  has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete unit ';
		}
	}	
}


?>
<?php $mst_party_company_id =  encryptor(decrypt,$_REQUEST['eId']);
	$resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."' and id ='".$mst_party_company_id."' "); 
		while($row = $db->fetch_object2($resCat)){ 
			$company_name = $row->company_name;
		}
 ?> 

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
  	
   <?php  $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h5 class="p-0 m-0">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>
      <?php echo breadCrumbs(); ?>
    </section>
	

    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error  mb-0" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border"> 
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn n-btn" href="editPosDocumentTypeConfig.php?eid=<?php echo encryptor(encrypt, $mst_party_company_id); ?>&action=edit&page=<?php echo $_GET['page']; ?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" >Add Document Type Configuration</a>
							  <button type="button" class="btn n-btn dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&submenu=<?php echo $_GET['submenu']; ?>&tableName=<?php echo TBL_DOC_TYPE_CONFIG;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&submenu=<?php echo $_GET['submenu']; ?>&tableName=<?php echo TBL_DOC_TYPE_CONFIG;?>&submenu=<?php echo $_GET['submenu']; ?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
		</div></div>
           
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header  table-h text-center">
              <h3 class="box-title">Document Type Configuration List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            	<table id="DocumentTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Document Type</th> 
                    <th>Document Name</th> 
				  <th>Date</th> 
				  <th>Method</th>
				  <?php /*?><th>Start No</th> 
				  <th>Numeric Part</th> 
				  <th>Prefix</th> 
				  <th>Suffix</th><?php */?> 
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php  

		        	 $resCat = selectSql(TBL_DOC_TYPE_CONFIG," where id_shop= '".addslashes($_SESSION['shop'])."' and `id_app_modules` = '2' ORDER BY last_modified desc "); 
		        	 
		        	 $i=1;
					  while($row = $db->fetch_object2($resCat)){ 
				  	?>
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td>
                  	<?php  
                  	
			                  	 		if($row->doc_type == '21'){
				              	 			echo "POS Sales Bill";
				              	 		}elseif($row->doc_type == '22'){
				              	 			echo "KOT";
				              	 		}elseif($row->doc_type == '23'){
				              	 			echo "POS sales Bil(nc)";
              	 						}elseif($row->doc_type == '24'){
				              	 			echo "KOT(nc)";
              	 						}elseif($row->doc_type == '25'){
				              	 			echo "Laundry";
              	 						}elseif($row->doc_type == '27'){
				              	 			echo "Laundry(nc)";
              	 						}elseif($row->doc_type == '26'){
				              	 			echo "Spa and Health Club";
              	 						}elseif($row->doc_type == '28'){
				              	 			echo "Spa and Health Club(nc)";
              	 						}elseif($row->doc_type == '29'){
				              	 			echo "Others";
              	 						}elseif($row->doc_type == '30'){
				              	 			echo "POS Receipt";
              	 						}else{

				              	 		}
		            ?>
                  </td>  
                    <td><?php echo (addslashes($row->doc_name));?></td> 
                  <td><?php echo date('d-m-Y' , strtotime(addslashes($row->effective_date)));?></td> 
                  <td>
                  	<?php  
                  		if($row->method == '1'){
              	 			echo "Auto";
              	 		}else{
              	 			echo "Manual";
              	 			}
              	 		?>
                  </td> 
                  <?php /*?><td><?php echo $row->start_no;?></td> 
                  <td><?php echo $row->numeric_part;?></td> 
                  <td><?php echo $row->prefix;?></td> 
                  <td><?php echo $row->suffix;?></td> 
<?php */?>
                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'managePosDocumentConfig.php?inactiveId='.encryptor(encrypt, $row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'managePosDocumentConfig.php?activeId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

				  <td><img src="../images/edit.png" style="cursor:pointer;height:20px;" title=" View / Edit " onClick="window.location.href='editPosDocumentTypeConfig.php?eId=<?=encryptor(encrypt, $row->id)?>&action=edit&page=<?=$_GET['page']?>&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  <!--	<img src="../images/close.png" style="height:18px;cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo $row->id;?>" onClick="deletes(this.id)"/>--></td>
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
	        self.location='managePosDocumentConfig.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>&submenu=<?php echo $_GET['submenu']; ?>';
	   } 
	   else
	   {
	      self.location='managePosDocumentConfig.php?submenu=<?php echo $_GET['submenu']; ?>';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  
<script type="text/javascript">
		$(document).ready( function () { 
	        $('#DocumentTable').DataTable({
	                order: [ 1, 'DESC' ],  
	        });
	    });
</script>