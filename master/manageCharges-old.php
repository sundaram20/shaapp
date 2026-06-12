<?php include_once("../config/auto_loader.php");

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_CHARGES."`
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

		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_CHARGES."` 
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
	
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_CHARGES,'delete')){
		$delSql = "DELETE FROM `".TBL_CHARGES."` WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
	
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
$sql = " SELECT * FROM `".TBL_CHARGES."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' ";
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
	$sql .= " ORDER BY charges_account,tax_type ASC";
}
//echo $sql;

$db->query($sql);
$numRows= $db->num_rows();
// $pagging = new pagingClass($sql,$setpage);
// $db->query($pagging->getQuery());
// $total = $db->num_rows();
//var_dump($total);
?>
<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<?php include_once("../ajax/ajaxCheckTransactions.php");?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Charges Master
        <small>Manage Charges</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Charges</li>
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
							  <a type="button" class="btn btn-success" href="editCharges.php" >Add Charges</a>
							  <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_CHARGES;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_CHARGES;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>
							</div>
          
        </div> 	
      </div>
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Charges List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
  

             <table id="myTable" class="table table-striped table-bordered dataTable no-footer cell-border" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="10%"> S.No.&nbsp;</th>
                  <th>Name</th>
                  <th>Charge Account</th>
                  <th>Tax Applicable</th>
				  <th>Tax Type</th>
				  <th>Transaction Type</th>  
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php 	
		        	$sno=1;	 
				  while($row = $db->fetch_object()){?>
                  
				  	<tr>
                  <td><?php echo $sno++;?></td>
                  <td><?php echo $row->name;?></td>

                  <td><?php if($row->charges_account == '1'){
                  		echo "SALES";
                  	}elseif($row->charges_account == '2'){
                  		echo "PURCHASE";
                  	}elseif($row->charges_account == '3'){
                  		echo "INCOME";
                  	}elseif($row->charges_account == '4'){
                  		echo "EXPENSE";
                  	}elseif($row->charges_account == '5'){
                  		echo "TAXES";
                  	}elseif($row->charges_account == '6'){
                  		echo "DISCOUNT";
                  	}
                  	elseif($row->charges_account == '7'){
                  		echo "OTHERS";
                  	}
                  	else{
                  		 
                  	}
                  	?></td>
                  	 <td><?php if($row->tax_applicable == '1'){
                  		echo "GST";
                  	}elseif($row->tax_applicable == '2'){
                  		echo "VAT";
                  	}else{
                  		echo "Not Applicable";
                  	}
                  	?></td> 

                  	<td><?php if($row->tax_type == '1'){
                  		echo "SGST";
                  	}elseif($row->tax_type == '2'){
                  		echo "CGST";
                  	}elseif($row->tax_type == '3'){
                  		echo "IGST";
                  	}elseif($row->tax_type == '4'){
                  		echo "VAT";
                  	}elseif($row->tax_type == '5'){
                  		echo "CESS";
                  	}else{
                  		echo "Not Applicable";
                  	}
                  	?></td> 
                  <td><?php if($row->transaction_type == '1'){
                  		echo "Local";
                  	}elseif($row->transaction_type == '2'){
                  		echo "Interstate";
                  	}else{
                  		echo "Not Applicable";
                  	}
                  	?></td>   

                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageCharges.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCharges.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>	

                   <?php 

						$table_name = array(TBL_INV_ITEMS);
				  		$ajaxCheckTransactions = CheckTransactionsChargesMaster($row->id, $table_name); 

						if($ajaxCheckTransactions != '1'){
                 	 ?>
	
				  <td><a style="cursor:pointer;" title=" View / Edit " href='editCharges.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit' /><i class="fa fa-pencil-square-o" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;
				 <img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id)"/></td>
				 <?php } else{ ?> 
				  		<td><a style="cursor:pointer;" title=" View / Edit " href='editCharges.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit' /><i class="fa fa-pencil-square-o" ></i></a>&nbsp;&nbsp;&nbsp;&nbsp;	
				  		<img src="../images/chat.png" style="cursor:pointer; " title="In Use" />
				  	</td>
				  	 <?php } ?>
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



  <script type="text/javascript"> 	//Data Table Script
  	
  	 	
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
	        self.location='manageCharges.php?delId='+sid+'&action=delete&page=<?=$_REQUEST['page']?>';
	   } 
	   else
	   {
	      self.location='manageCharges.php';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  