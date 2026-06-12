<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'view');

//---------------------------------------------------------------------------------------------------------

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_INV_INDENT."`
						SET `status` = '1'
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
	}elseif($_REQUEST['inactiveId'] != ''){
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_INV_INDENT."` 
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
	
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'delete')){
		$delSql = "DELETE FROM `".TBL_INV_INDENT."` WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
	
		if(executeSql($delSql)){
			$delSql2 = "DELETE FROM `".TBL_INV_INDENT_DETAILS."` WHERE `id_inv_indent` = '".encryptor(decrypt,$_REQUEST['delId'])."'";	
			if(executeSql($delSql2)){	
				$err = 0;
				$_SESSION['successMsg'] = 'One Unit  has been deleted sucessfully.';
			}
			else{
				$err = 0;
				$_SESSION['successMsg'] = 'Values Not deleted from details table.';
			}	
		}else{
			$err = 1;
			$_SESSION['errorMsg'] = 'Unable to delete unit ';
		}
	}	
}

 
?>
<?php $mst_party_company_id =  encryptor(decrypt,$_REQUEST['eId']);
	$resCat = selectSql(TBL_PARTY," where id_shop= '".addslashes($_SESSION['shop'])."' and id ='".$mst_party_company_id."' order by last_modified DESC "); 
		while($row = $db->fetch_object2($resCat)){ 
			$company_name = $row->company_name;
		}
 ?> 

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
	 <!-- Content Header (Page header) -->
	 
	 
   <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section> 

	
   <!-- <section class="content-header">
      <h1>
        Requisition Note Manager
        <small>Manage Requisition Note</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage Requisition Note</li>
      </ol>
    </section>   -->

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
							  <a type="button" class="btn btn-success" href="editIndent.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>" >Add Requisition Note</a>
							  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_INV_INDENT;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_INV_INDENT;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>-->
							</div>
		</div></div>
           
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Requisition List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">

            	<table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
		        <thead>
		            <tr>
                  <th width="1%"> S.No.&nbsp;</th>
                  <th>Document Type</th> 
				  <th>Requisition No</th>
				  <th>Date</th> 
				  <th>Created by</th> 
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php  

		        	 $resCat = selectSql(TBL_INV_INDENT," where id_shop= '".addslashes($_SESSION['shop'])."'  and doc_type = '1' order by last_modified desc  "); 
		        	 
		        	 $i=1;
					  while($row = $db->fetch_object2($resCat)){ 
				  	?>
                  <tr>

                  <td><?php echo $i++;?></td>
                  <td>
                  	<?php  
                  		if($row->doc_type == '1'){
              	 			echo "Requestion";
              	 		}elseif($row->doc_type == '2'){
              	 			echo "Indent Purchase Order";
              	 		} 
              	 		elseif($row->doc_type == '3'){
              	 			echo "Purchase Order";
              	 		}
              	 		elseif($row->doc_type == '4'){
              	 			echo "Goods Receipt Note"; 
              	 		}
              	 		elseif($row->doc_type == '5'){
              	 			echo "Purchase Bill";
              	 		}
              	 		elseif($row->doc_type == '6'){
              	 			echo "Store Issue Note";
              	 		}
              	 		elseif($row->doc_type == '7'){
              	 			echo "Credit Note";
              	 		}elseif($row->doc_type == '8'){
              	 			echo "Debite Note";
              	 		}elseif($row->doc_type == '9'){
              	 			echo "Physical Stock";
              	 		}else{

              	 		}
		            ?>
                  </td> 
                  <td>
				  <?php //echo $indent_no = $row->indent_no;?>
				  <?php 
                  	
                  	$id='';$prefix='';$suffix='';
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 
	                  			$id = $row1->id; 
	                  			$prefix = $row1->prefix; 
	                  			$suffix = $row1->suffix; 
	                  		if($prefix != ''){
	                  			//echo $prefix.''.$indent_no.''.$suffix;
	                  		}
	                  	}
	                  	if($prefix != ''){ echo $row->mdoc_no;}
	                  	//$id='';$prefix='';$suffix=''; 
                  	?>
				  </td> 
				  
				    
                  <td><?php echo date('d-m-Y' , strtotime(addslashes($row->indent_date)));?></td> 
                 
                  <td><?php echo $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$row->id_mst_user_created_by.'" '); ?></td> 

                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'manageIndent.php?inactiveId='.encryptor(encrypt, $row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageIndent.php?activeId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editIndent.php?eId=<?=encryptor(encrypt, $row->id)?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu'] ?>&action=edit&page=<?=$_REQUEST['page']?>&print=1';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/print.png" style="cursor:pointer;" title=" Print " onClick="window.location.href='print.php?eId=<?=encryptor(encrypt, $row->id)?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu'] ?>&action=edit&page=<?=$_REQUEST['page']?>'; " />&nbsp;&nbsp;&nbsp;&nbsp;

				  	<?php 
				  		$tableArray = array();
				  		$idsIndent = array();
				  		$resIds = mysqli_query($connNew,"SELECT id FROM ".TBL_INV_INDENT_DETAILS." WHERE id_inv_indent='".$row->id."' ");
				  		
				  		while($rowIds = mysqli_fetch_object($resIds)){
				  			array_push($idsIndent, $rowIds->id);
				  		}

				  		$tableArray = array(TBL_INV_PURCH_DETAILS=>'id_inv_indent_details IN ('.implode(',',$idsIndent).') AND 1');
				  		if(!checkTransBeforeDelete($tableArray,1)){

				  		?>
				  	<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id)"/></td>
				  <?php }else{
				  	echo '<img src="../images/chat.png" style="cursor:pointer; " title="In Use" />';
				  } ?>
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
	        self.location='manageIndent.php?delId='+sid+'&submenu=<?php echo $_GET["submenu"] ?>&action=delete&page=<?=$_REQUEST["page"]?>';
	   } 
	   else
	   {
	      self.location='manageIndent.php?submenu=<?php echo $_GET["submenu"] ?>';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  

 <script>
	<?php if($_GET['reload'] != '1'){ ?>
	$(document).ready(function (){  
		location.href = "manageIndent.php?submenu="+<?php echo $_GET['submenu']; ?>+"&session="+<?php echo $_GET['session']; ?>+"&reload=1";
	});
	<?php } ?>
 </script>