<?php include_once("../config/auto_loader.php");
	checkUserLevelPermission($_SESSION['userLevel'],TBL_USER_PERMISSIONS,'view');
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<?php
	   $sql="SELECT a.* FROM mst_user_permissions as a 
	 LEFT JOIN mst_user_levels AS b ON b.id=a.id_mst_user_levels  
	 WHERE a.id_shop='".$_SESSION['shop']."' and b.status='1' GROUP BY a.id_mst_user_levels ";

	$res = mysqli_query($connNew,$sql);

?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
        <?php echo '<span style="color:'.currentNavigation()['color'].'">&nbsp;<i class="fa '.currentNavigation()['icon'].'"></i> '.currentNavigation()['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>
      <ol class="breadcrumb">
        <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="manageUserPermission.php">User  Permission</a></li>
        <li class="active">Manage User Permission</li>
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
          <h3 class="box-title">User Level Permission List</h3>
          <a href="editUserPermissions.php" class="btn btn-success pull-right">Add Permission</a>
		</div>
		            <div class="box-body table-responsive">
		              <table id="example2" class="table table-bordered table-striped">
		                <thead>
		                <tr>
		                  <!--<th width="10%"><input type='checkbox' name='CheckAll' id="CheckAll" value='Check All' />S.no&nbsp;</th>-->
						  <th>S.no</th>
						  <th>User Level</th>
						  <th>Action</th>
		                </tr>
		                </thead>
		                <tbody>
						<?php 				 				
						if(mysqli_num_rows($res) > 0){$counter = 1;
						
						  while($row = mysqli_fetch_object($res)){ 
						  
						 ?>
		                <tr>
		                  
						  <td><?php echo $counter ++ ;?></td>
						  <td><?php echo selectColumn(TBL_USER_LEVELS,'name','WHERE id="'.$row->id_mst_user_levels.'" ');   ?></td>
						  

						  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editUserPermissions.php?eId=<?=encryptor(encrypt,$row->id_mst_user_levels)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<!--<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageAssignHotelRoom.php?delId=<?=encryptor(encrypt,$row->id)?>&eId=<?=$_GET['eId']?>&action=delete&page=<?=$_REQUEST['page']?>';}"/>--></td>
		                </tr>
		               <?php }?> 
					    <!--<tr>
		                     <td align="left" colspan="5">
							 <input name="delete_sel" type="button" class="btn btn-warning" value="Delete" onClick="javascript:formSubmit('delete');"/>&nbsp;&nbsp;&nbsp;&nbsp; 
							 <input name="active_sel" type="button" class="btn btn-success" value="Active" onClick="javascript:formSubmit('activate');"/>&nbsp;&nbsp;&nbsp;&nbsp;
							  <input name="inactive_sel" type="button" class="btn btn-danger" value="Inactive" onClick="javascript:formSubmit('inactivate');"/> </td>
						</tr>-->
						<tr>	 
							  <td align="right" colspan="5"><?php  echo $pagging->getLinks();?> </td>
		                 </tr>                
						<?php }else {?>
						
						 <tr>
		                      <td height="200" align="center" colspan="4">---- No Record Found ---- </td>
		                 </tr>                 
						<?php }?>
		                </tbody>                
		              </table>			  
		            </div>
    </div>
    </section>
    </div>

<?php include_once("../includes/footer.php")?>        