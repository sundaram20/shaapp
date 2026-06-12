<?php include_once("../config/auto_loader.php");
if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `app_auto_report_config_details`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_user_modified_by` = '".$_SESSION['userId']."'
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

		if(checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE app_auto_report_config_details
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_user_modified_by` = '".$_SESSION['userId']."'
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
	
	if(checkUserLevelPermission($_SESSION['userLevel'],'app_auto_report_config_details','delete')){
		 $delSql = "DELETE FROM app_auto_report_config_details WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
	
		if( mysqli_query($appConnect,$delSql)){		
			$err = 0;
			$_SESSION['successMsg'] = 'One Unit  has been deleted sucessfully.';
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete unit ';
		}
	}	
}


// ----------cate---------
$sql = " SELECT * FROM app_auto_report_config_details WHERE `id_shop` = '".addslashes($_SESSION['id_app_shop'])."'   ";
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

$Query= mysqli_query($appConnect,$sql);
$numRows= mysqli_num_rows($Query);
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
	
	
  <!--  <section class="content-header">
      <h1>
        Reports Manager
        <small>Manage Reports</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Reports</li>
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
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn btn-success" href="editAutoReportConfig.php" >Add Reports</a>
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
              <h3 class="box-title">Reports List</h3>
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
                  <th>Reports Name</th>
				
                  
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php 
		        	$i=1;		 
				  while($row = mysqli_fetch_object($Query)){ ?>
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td><?php $resCatshop = mysqli_query($appConnect,"SELECT * FROM app_auto_report_config WHERE id='".$row->id_auto_report_config."'");
								if(mysqli_num_rows($resCatshop)){
									$resultCatshop = mysqli_fetch_object($resCatshop);
									echo $reportName	=$resultCatshop->name;
									}
									
									?></td>
                 

                  

                  <?php 
                  		$table_name = array();
				  		$ajaxCheckTransactions = CheckTransactionsDepartment($row->id, $table_name); 

						if($ajaxCheckTransactions != '1'){
                 	 ?>
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editAutoReportConfig.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id)"/></td>
				  	<?php } else{ ?> 
				  		<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editAutoReportConfig.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;
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
	        self.location='manageAutoReportConfig.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
	   } 
	   else
	   {
	       self.location='manageAutoReportConfig.php';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  