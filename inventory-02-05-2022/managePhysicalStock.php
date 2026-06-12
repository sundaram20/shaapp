<?php include_once("../config/auto_loader.php");

if($_REQUEST['action'] == 'change'){
	
	if($_REQUEST['activeId'] != ''){
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'active')){

		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_INV_PURCH."`
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
		if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'inactive')){
		
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_INV_PURCH."` 
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
	
	if(checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'delete')){
		$delSql = "DELETE FROM `".TBL_INV_PURCH."` WHERE `id` = '".encryptor(decrypt,$_REQUEST['delId'])."'";
	
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


 
  
	$SqlKotList = mysqli_query($connNew, $SQL); 
$numRows=	mysqli_num_rows($SqlKotList);	        	 
$i=1;


//filters start
 //if($_REQUEST['searchFormSubmit']==1){
 	if($_REQUEST['search_name'] != ''){
	$sname	=explode('-',$_REQUEST['search_name']);
	$statuscase .= " AND `po_no` ='".addslashes($_REQUEST['search_name'])."'";

}
	if($_REQUEST['status'] != ''){
	$statuscase .= " AND `status` = '".addslashes($_REQUEST['status'])."%'";
} 
if($_REQUEST['datefilter'] != ''){
    $DateExplode = explode(' to ',$_REQUEST['datefilter']);
    $startDate = date('Y-m-d',strtotime($DateExplode['0']));
    $endDate  = date('Y-m-d',strtotime($DateExplode['1']));
    //$endDate = date ("Y-m-d", strtotime("+1 day", strtotime($endDate)));
      
    $statuscase .= " AND DATE(`po_date`) BETWEEN '".date('Y-m-d',strtotime($startDate))."' And '".date('Y-m-d',strtotime($endDate))."'";
  } else{
      $statuscase .= " AND DATE(`po_date`) BETWEEN '".date('Y-m-d',strtotime('-1 days'))."' And '".date('Y-m-d')."'";
  }
		

//}


//$resCat = selectSql(TBL_INV_INDENT," where id_shop= '".addslashes($_SESSION['shop'])."'  and doc_type = '3' ".$statuscase."order by last_modified desc  "); 
 $resCat = selectSql(TBL_INV_PURCH," where id_shop= '".addslashes($_SESSION['shop'])."'  and doc_type = '9'  ".$statuscase."order by last_modified DESC "); 

$numRows=	mysqli_num_rows($resCat);	        	 

 ?> 

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
   <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h5 style="margin: 0px !important;padding: 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>
      <?php echo breadCrumbs(); ?>
    </section> 


    <!-- Main content -->
    <section class="content">		
	<div class="box box-default">
	 <div class="form-group has-error mb-0" align="center">
		<?php if($_SESSION['errorMsg']){?>
		 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
		<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
		<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
		<?php unset($_SESSION['successMsg']);}?>
		</div>
        <div class="box-header with-border"> 
        	  <h6 class="box-title">Search <small> Records:(
            <?=$numRows;?>
            ) &nbsp;</small> </h6>
		  <div class="btn-group  pull-right">
							  <a type="button" class="btn n-btn" href="editPhysicalStock.php?session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>" >Add <?php echo currentNavigation()['submenu'];  ?></a>
							  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
							<?php ?>	<li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_INV_PURCH;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_INV_PURCH;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>-
							</div>-->
		</div></div>

		 <!--listing start-->
	   <form name="searchForm" action="" method="get">
          <input type="hidden" value="1" name="searchFormSubmit" />
           <input type="hidden" value="<?php echo $_GET['session']; ?>" name="session" />
            <input type="hidden" value="<?php echo $_GET['submenu']; ?>" name="submenu" />
          <div class="box-body">
            <div class="row">
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>PS No</label>
                  <input type="text" name="search_name" id="search_name" value="<?php echo trim($_REQUEST['search_name']);?>" class="form-control" />
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.col -->
              <!--col start-->
                <div class="col-md-2 col-sm-6 col-xs-6">
                   <div class="form-group">
                     <label>Period</label>  
                         <div class="input-group"> 
                            <!--<div class="input-group-addon">
                              <i class="fa fa-calendar"></i> 
                            </div>  -->
                          <!-- <input type="text" name="datefilter" id="datefilter" placeholder="Date" class="form-control"  value="" /> -->
                          <input type="text" class="form-control pull-right"  placeholder="Select From -  To" name="datefilter" id="dateRangeReport" data-parsley-required value="<?php if($_REQUEST['datefilter']!=''){echo $_REQUEST['datefilter'];}else{ echo date('d-m-Y',strtotime('-1 days')).' to '.date('d-m-Y'); }?>"   autocomplete="off">
                        </div>
                    </div>
                  </div>  

              <!-- /.form-group -->
              <!--End col-->
              
              <div class="col-md-2 col-sm-6 col-xs-6">
                <div class="form-group">
                  <label>Status</label>
                 <?php 
								if($_REQUEST['status'] == '1'){
									$selected1 = 'selected="selected"';
								}elseif($_REQUEST['status'] == '0'){
									$selected0 = 'selected="selected"';
								}
				  				echo $statusDropDown = '<select class="form-control select2" name="status" style="width: 100%"> <option value="">Both</option>
				  					<option '.$selected1.' value="1">Active</option>
				  					<option '.$selected0.' value="0">Inactive</option>
				  				</select>';?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
              
              <!-- /.row --> 
              
            </div>

              <div class="box-footer pt-0 pl-0">
                 <input name="Search" type="submit" class="btn o-btn" value="Apply" />
             </div>
          </div>
          
          <!-- /.box-body -->
      
        </form>
 
		<!--form ends-->
 

	 <!--listing endst-->	
    
           
      <div class="row">
        <div class="col-xs-12">		     
          <!-- /.box -->
          <div class="box">
            <div class="box-header  table-h text-center">
              <h3 class="box-title">Physical Stock List</h3>
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
				  <th>Date</th> 
				  <th>PS No</th>
				  <th>Remarks</th>  
				  <th>MDocu Auto</th> 
				  <th>MDocu Manual</th> 
                  <th>Status</th>
				  <th>Action</th>
                </tr>
		          </thead>
		        <tbody>  
		        	<?php  

		        	// $resCat = selectSql(TBL_INV_PURCH," where id_shop= '".addslashes($_SESSION['shop'])."'  and doc_type = '9'  "); 
		        	 
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
                  <td><?php echo date('d-m-Y' , strtotime(addslashes($row->po_date)));?></td> 
                  <td><?php echo $po_no = $row->po_no;?></td> 
                  <td><?php echo $row->remarks;?></td> 
                  <td>
                  	<?php 
                  	$id='';$prefix='';$suffix='';
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 
	                  			$id = $row1->id; 
	                  			$prefix = $row1->prefix; 
	                  			$suffix = $row1->suffix; 
	                  		if($prefix != ''){
	                  			echo $prefix.''.$po_no.''.$suffix;
	                  		}
	                  	}
	                  	//$id='';$prefix='';$suffix=''; 
                  	?>
                  	
                  </td>
                  <td><?php if($prefix == ''){ echo $row->mdoc_no;} ?></td>  

                  <td><?php echo $row->status=='1'?'<span onclick="location.href=\'managePhysicalStock.php?inactiveId='.encryptor(encrypt, $row->id).'&submenu='.$_GET['submenu'].'&session='.$_GET['session'].'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'managePhysicalStock.php?activeId='.encryptor(encrypt,$row->id).'&submenu='.$_GET['submenu'].'&session='.$_GET['session'].'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>		

				  <td><img src="../images/edit.png" style="height:20px;cursor:pointer;" title=" View / Edit " onClick="window.location.href='editPhysicalStock.php?eId=<?=encryptor(encrypt, $row->id)?>&submenu=<?php echo $_GET["submenu"]; ?>&session=<?php echo $_GET["session"]; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=0';" />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/preview.png" style="height:20px;cursor:pointer;" title="Page Preview" onClick="window.location.href='print.php?eId=<?=encryptor(encrypt, $row->id)?>&submenu=<?php echo $_GET["submenu"]; ?>&session=<?php echo $_GET["session"]; ?>&action=edit&page=<?=$_REQUEST['page']?>'; " />&nbsp;&nbsp;&nbsp;&nbsp;
				  	<img src="../images/close.png" style="height:15px;cursor:pointer;" title="Delete" name="<?php echo $row->name; ?>" id="<?php echo encryptor(encrypt,$row->id);?>" onClick="deletes(this.id);"/></td>
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
	        self.location='managePhysicalStock.php?delId='+sid+'&action=delete&page=<?=$_REQUEST["page"]?>&submenu=<?php echo $_GET["submenu"]; ?>&session=<?php echo $_GET["session"]; ?>';
	   } 
	   else
	   {
	      self.location='managePhysicalStock.php?submenu=<?php echo $_GET["submenu"]; ?>&session=<?php echo $_GET["session"]; ?>';
	    }
	   });
	  }
  </script> 

<?php include_once("../includes/footer.php")?>  