<?php include_once("../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'view');
if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'activate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_BE_RESERVATION_QUERY."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'deactivate');
		$statusId = addslashes(encryptor(decrypt,$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_BE_RESERVATION_QUERY."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`id_mst_user_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Room Type '.selectColumn(TBL_BE_RESERVATION_QUERY,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
		
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Room Type '.selectColumn(TBL_BE_RESERVATION_QUERY,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}
else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'delete');
	$delSql = "DELETE FROM `".TBL_BE_RESERVATION_QUERY."` WHERE `id` = '".$_REQUEST['delId']."'";
	
	$sqlDelUserLevel = selectRow(TBL_BE_RESERVATION_QUERY," WHERE `id` = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Room Type '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete room type '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_BE_RESERVATION_QUERY."`
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
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_BE_RESERVATION_QUERY."`
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
	//checkUserLevelPermission($_SESSION['userLevel'],TBL_BE_RESERVATION_QUERY,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_BE_RESERVATION_QUERY."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}
// ----------cate---------
$sql = " SELECT * FROM ".TBL_BE_RESERVATION_QUERY." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."'  ";
if($_REQUEST['id_hotel'] != ''){
	$sql .= " AND `id_hotel` = '".addslashes($_REQUEST['id_hotel'])."'";
}


if($_REQUEST['offer_name']!=''){
  $sql .= " AND `name` LIKE '%".addslashes($_REQUEST['offer_name'])."%'";
}

if($_REQUEST['order'] != ''){
	$sql .= " ORDER BY `last_modified` DESC";
}else{
	$sql .= "  ORDER BY id DESC";
}

$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>
<?php include_once("../includes/header.php")?>

<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
   
<section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <?php echo breadCrumbs(); ?>
    </section>
<!--   <section class="content-header">
      <h1>
        Booking Engine
        <small>Manage Reservations</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i>Booking Engine</a></li>
        <li class="active">Manage Reservations</li>
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
          <h3 class="box-title">Search <small>Total Records: (<?=$numRows;?>) &nbsp;</small> </h3>
		  			
          
		  <div class="btn-group  pull-right">
							  <!--<a type="button" class="btn btn-success" href="editOffers.php" >Add New Offer</a>-->
							  &nbsp;&nbsp;
							  
							  <!--<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span>
							  </button>
							  <ul class="dropdown-menu" role="menu">
								<?php ?><li><a title="Export to excel file" href="exportTable.php?fileType=xls&tableName=<?php echo TBL_BE_RESERVATION_QUERY;?>"><img src="../images/excel-icon.jpg" width="20" height="20" />&nbsp;Export</a></li>
								<li><a title="Export to csv file" href="exportTable.php?fileType=csv&tableName=<?php echo TBL_BE_RESERVATION_QUERY;?>"><img  src="../images/excel-csv-icon.jpg" width="20" height="20"  />&nbsp;Export</a></li><?php ?>
							  
							  </ul>-->
							</div>

        </div>
        <!-- /.box-header -->
		<form name="searchForm" action="" method="get">
            <input type="hidden" value="1" name="searchFormSubmit" />
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Hotel Name</label>
                <?php 
                $sqlHot="SELECT id,name FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."' ORDER BY name";
                $resHot=mysqli_query($connNew,$sqlHot);

                ?>				
				<select class="select2 form-control" name="id_hotel">
					<option value="">---SELECT HOTEL---</option>
					<?php while($objHot=mysqli_fetch_object($resHot)){
						if(isset($_REQUEST['id_hotel']) && $_REQUEST['id_hotel']==$objHot->id){
							$selected="selected";
						}
						else{
							$selected="";
						}
						echo "<option ".$selected." value='".$objHot->id."'>".$objHot->name."</option>";
					} ?>
				</select>
              </div>
              <!-- /.form-group -->
            </div>
            
            <div class="col-md-6">
              <div>
                <label>Customer Name</label>
              <input type="text" placeholder="Type  name..." name="offer_name" class="form-control">
              </div>
            </div>
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
              <h3 class="box-title">Reservation List</h3>
            </div>
			<form name="listingForm" action="" method="post">
               <input type="hidden" value="" name="act" />
			     <div id="listingDiv"></div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th> S.no.&nbsp;</th>
                  <th>Hotel</th>
                  <th>Room</th>
                  <th>Customer Name</th>
                  <th>Checkin - Checkout</th>
                  <th>Payment Status</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td><!--<input type="checkbox" name="ids[]" id="ids" value="<?=$row->id;?>"/>--> <?php echo (($_REQUEST['page']-1)*$setpage)+$counter++;?>.&nbsp;</td>
                  <td><?=selectColumn(TBL_HOTELS,'name','WHERE id="'.$row->id_hotel.'" ');?></td>
                  <td><?=selectColumn(TBL_ROOM_TYPE,'name','WHERE id="'.$row->id_room.'" ');?></td>
                 
                  <td><?=$row->name;?></td>
                  <td><?=date('d-M-Y',strtotime($row->checkin_date)).' to '.date('d-M-Y',strtotime($row->checkout_date));?></td>
                  <td><?=($row->payment_status==0?'Pending':'Success')?></td>
                  <!--<td><?=$row->status=='1'?'<span onclick="location.href=\'manageRoomTypes.php?inactiveId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageRoomTypes.php?activeId='.encryptor(encrypt,$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>-->	

				  <!--<td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editOffers.php?eId=<?=encryptor(encrypt,$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;</td>-->
                </tr>
               <?php }?> 
			   <!--<tr>
                     <td align="left" colspan="4">
					 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
					 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
					  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
				</tr>-->
				<tr>	 
					  <td align="right" colspan="4"><?php  echo $pagging->getLinks();?> </td>
                 </tr>               
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
  	/*function deleteMe(id,name){
  		var xhttp = new XMLHttpRequest();
  		  xhttp.onreadystatechange = function() {
  		    if (this.readyState == 4 && this.status == 200) {
  		    	console.log(this.responseText);
  		      if(this.responseText == 1){
  		      	alert("Transaction Found In the Table");
  		      }
  		      else{
  		      	if(confirm('Are you sure that you want to delete this record '+name+'?')){
  		      		window.location.href='manageRoomTypes.php?delId='+id+'&action=delete&page=<?=$_REQUEST['page']?>';
  		      	}
  		      }
  		    }
  		  };
  		  xhttp.open("GET", "ajax/ajaxCheckCompanyDomain.php?id_room_type="+id, true);
  		  xhttp.send();
  	}*/
  </script>                                    
<?php include_once("../includes/footer.php")?>  