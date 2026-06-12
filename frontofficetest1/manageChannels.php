<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_PURCH, 'view');

if($_REQUEST['action'] == 'change'){
	if($_REQUEST['activeId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'activate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['activeId']));
		$statusSql = "	UPDATE `".TBL_CHANNEL_MANAGER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}elseif($_REQUEST['inactiveId'] != ''){
		checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'deactivate');
		$statusId = addslashes(encryptor('decrypt',$_REQUEST['inactiveId']));
		$statusSql = "	UPDATE `".TBL_CHANNEL_MANAGER."` 
						SET `status` = '0' 
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` = '".addslashes($statusId)."'";
	}
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Channel '.selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".$statusId."'").' status has been changed sucessfully.';
		
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Channel '.selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".$statusId."'").' status has not been changed sucessfully.';
	}
}
else if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'delete');
	$delSql = "DELETE FROM `".TBL_CHANNEL_MANAGER."` WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'";
	$sqlDelUserLevel = selectRow(TBL_CHANNEL_MANAGER," WHERE `id` = '".addslashes(encryptor('decrypt',$_REQUEST['delId']))."'");
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'One Channel '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete Channel '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'activate');	
	$activateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CHANNEL_MANAGER."`
						SET `status` = '1'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($activateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
	}	
}else if($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'deactivate');	
	$deactivateIds = implode(',',$_REQUEST['ids']);	
	$statusSql = "	UPDATE `".TBL_CHANNEL_MANAGER."`
						SET `status` = '0'
						,`last_modified` = '".currenDateTime()."'
						,`last_modified_by` = '".$_SESSION['userId']."'
						WHERE `id` IN (".addslashes($deactivateIds).")";	
										
	if(executeSql($statusSql)){
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
	}	
}else if($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_CHANNEL_MANAGER,'delete');	
	$deleteIds = implode(',',$_REQUEST['ids']);	
	$delSql = "DELETE FROM `".TBL_CHANNEL_MANAGER."` WHERE `id` IN (".addslashes($deleteIds).")";
	if(executeSql($delSql)){		
		$err = 0;
		$_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete selected records';
	}
}
// ----------cate---------
$sql = " SELECT * FROM `".TBL_CHANNEL_MANAGER."` WHERE 1 ";
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
$db->query($sql);
$numRows= $db->num_rows();
$pagging = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total = $db->num_rows();
?>


<?php include_once("../includes/header.php") ?>
<?php include_once("../includes/left.php"); ?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <h6 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
                    <?php echo '<span style="color:' . currentNavigation_id($session)['color'] . '">&nbsp;<i class="fa ' . currentNavigation_id($session)['icon'] . '"></i> ' . currentNavigation_id($session)['submenu'] . '</span>'; ?>

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




    <section class="content">
        <div class="box box-default">
            <div class="form-group has-error mb-0" align="center">
                <?php if ($_SESSION['errorMsg']) { ?>
                    <p class="help-block">
                        <?php echo messageError($_SESSION['errorMsg']); ?>
                    </p>
                    <?php unset($_SESSION['errorMsg']);
                } elseif ($_SESSION['successMsg']) { ?>
                    <p class="help-block">
                        <?php echo messageSuc($_SESSION['successMsg']); ?>
                    </p>
                    <?php unset($_SESSION['successMsg']);
                } ?>
            </div>

            <div class="box-header with-border">
                <h6 class="box-title">Search <small> Records:(
                        <?= $numRows; ?>
                        ) &nbsp;
                    </small> </h6>
                <?php /*?><div class="btn-group  pull-right"> <a type="button" class="btn n-btn pull-right" href="managePosKot.php?submenu=<?php echo $_GET['submenu']; ?>" >Add <?php echo currentNavigation()['submenu']; ?> </a> </div><?php */?>
                <div class="btn-group  pull-right">
                <a type="button" class="btn btn-success" href="editChannels.php"> Add Channels</a>

            </div>
            </div>
           
            <!-- /.box-header -->
            <?php //debugData($_REQUEST);
            //echo $SQL;?>
            <form name="searchForm" action="" method="get">
                <input type="hidden" value="1" name="searchFormSubmit" />
                <input type="hidden" value="<?php echo $_GET['session'] ?>" name="session" />
                <input type="hidden" value="<?php echo $_GET['submenu'] ?>" name="submenu" />
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Channel Tittle</label>
                                <input type="text" name="search_name" id="search_name"
                                    value="<?php echo trim($_REQUEST['search_name']); ?>" class="form-control" />
                            </div>

                            <!-- /.form-group -->

                        </div>

                        <!-- /.col -->
                        <!--col start-->
                        <div class="col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Status</label>
                                <?php
                                if ($_REQUEST['status'] == '1') {
                                    $selected1 = 'selected="selected"';
                                } elseif ($_REQUEST['status'] == '0') {
                                    $selected0 = 'selected="selected"';
                                }
                                echo $statusDropDown = '<select class="form-control select2" name="status"> <option value="">Both</option>
				  <option ' . $selected1 . ' value="1">Active</option>
				  <option ' . $selected0 . ' value="0">Inactive</option>
				  </select>'; ?>
                            </div>
                        </div>

                    </div>

                    <!-- /.row -->

              

                <div class="box-footer pt-0 pl-0">
                    <input name="Search" type="submit" class="btn o-btn" value="Apply" />
                </div>
        </div>

        <!-- /.box-body -->

        </form>
</div>

<div class="row">
    <div class="col-xs-12">
    <h4 class="box-title">List Of
                    <?php echo currentNavigation_id($session)['submenu']; ?>
                </h4>
        <!-- /.box -->
        <div class="box">
            <div class="box-header  table-h text-center">
              
                <small class="text-center has-error">
                    <?php if ($_SESSION['errorMsg']) { ?>
                        <p class="help-block">
                            <?php echo messageError($_SESSION['errorMsg']); ?>
                        </p>
                        <?php unset($_SESSION['errorMsg']);
                    } elseif ($_SESSION['successMsg']) { ?>
                        <p class="help-block">
                            <?php echo messageSuc($_SESSION['successMsg']); ?>
                        </p>
                        <?php unset($_SESSION['successMsg']);
                    } ?>
                </small>
            </div>
            <form name="listingForm" action="" method="post">
                <input type="hidden" value="" name="act" />
                <div id="listingDiv"></div>
                <!-- /.box-header -->
                <div class="box-body table-responsive">
                    <table id="myTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Channels Title</th>
                                <th>Channels Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){
					if($row->channel_type == '1'){
							$channelSet = 'channel';
					}elseif($row->channel_type == '2'){
							$channelSet = 'PMS';
					}elseif($row->channel_type == '3'){
							$channelSet = 'CRM';
					}  
					elseif($row->channel_type == '4'){
							$channelSet = 'BE';
					}  
					  ?>
                <tr>
                  <td><?php echo $counter++;?>.&nbsp;</td>
                  <td><?=$row->name;?></td>
                  
                  <td><?=$channelSet;?></td>
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageChannels.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageChannels.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editChannels.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<?php /*?><img src="images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageChannels.php?delId=<?=encryptor('encrypt',$row->id)?>&action=delete&page=<?=$_REQUEST['page']?>';}"/><?php */?></td>
                </tr>
               <?php }?> 
			  
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
<div class="row">
    <div class="col-md-12">
        <!--cancel pop start-->
        <div id="cancelpop" class="well p-4" style="margin:0 15px;display: none;">
            <form id="Formkotremarks" autocomplete="off">

                <input type="hidden" id="pos_purch_id" name="pos_purch_id"
                    value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
                <div id="kot_mdoc_no"> </div>
                <div class="form-group">
                    <label for="title">Remarks</label>

                    <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark"
                        id="remark" name="remark" value="" data-parsley-required></textarea>
                </div>


                <div class="form-group">
                    <label for="btn">&nbsp;<br><br></label>
                    <?php echo $StatusOfPaymentis; ?>
                    <button class="btn c-btn" onclick="ajaxCancelKot();" type="button"><i class="far fa-save"></i>
                        Update</button>
                    <button class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</button>
                </div>
            </form>
        </div>
        <!--cancel pop ends-->
    </div>

</div>
<?php include_once("../includes/footer.php") ?>
<script>
    function ajaxCancelKot(id) {

        var form = $("#Formkotremarks");
        var id_pos_pos_purch_idpurch = $("#pos_purch_id").val();
        //var id_pos_purch=$("#id_pos_purch").val();

        var submenu1 = $("#submenu1").val();

        if (pos_purch_id != '' && pos_purch_id == undefined) {
            var purch = form.serialize() + '&pos_purch_id=' + pos_purch_id;
            var saveType = 'edit';

        } else {
            var purch = form.serialize();
            var saveType = 'add';

        }
        $('.loading').show();
        if (form.parsley().validate()) {

            $.ajax({
                type: "GET",
                url: 'ajax/ajaxCancelKot.php',
                data: purch,
                success: function (result) {
                    console.log(result);
                    data = JSON.parse(result);
                    //$( "#GetItemListView" ).html('');
                    //getPreviousOrder(data.purch_id);	
                    alert(data.msg);

                    if (submenu1 == '179') {
                        window.location.href = "manageKotNc.php?submenu=" + submenu1;
                    } else {
                        window.location.href = "manageKot.php?submenu=178&session=22";
                    }

                }

            });

        }


    }


    function ajaxKOTcancel(posid, mdoc_no) {

        //$("#cancelled").addClass("bookedby_open");
        $('#cancelpop').popup({
            transition: 'all 0.3s',
            autoopen: true,
        });
        $("#pos_purch_id").val(posid);
        $("#kot_mdoc_no").html(' KOT No: ' + mdoc_no);
    }
</script>