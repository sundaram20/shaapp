<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_PURCH, 'view');

if ($_REQUEST['action'] == 'change') {
    if ($_REQUEST['activeId'] != '') {
        checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'activate');
        $statusId = addslashes(encryptor('decrypt', $_REQUEST['activeId']));
        $statusSql = "	UPDATE `" . TBL_COMPANY_MAPPING . "`
						SET `status` = '1'
						,`last_modified` = '" . currenDateTime() . "'
						,`last_modified_by` = '" . $_SESSION['userId'] . "'
						WHERE `id` = '" . addslashes($statusId) . "'";
    } elseif ($_REQUEST['inactiveId'] != '') {
        checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'deactivate');
        $statusId = addslashes(encryptor('decrypt', $_REQUEST['inactiveId']));
        $statusSql = "	UPDATE `" . TBL_COMPANY_MAPPING . "` 
						SET `status` = '0' 
						,`last_modified` = '" . currenDateTime() . "'
						,`last_modified_by` = '" . $_SESSION['userId'] . "'
						WHERE `id` = '" . addslashes($statusId) . "'";
    }
    if (executeSql($statusSql)) {
        $err = 0;
        $_SESSION['successMsg'] = 'Mapping status has been changed sucessfully.';
    } else {
        $err = 1;
        $_SESSION['errorMsg'] = 'Mapping status has not been changed sucessfully.';
    }
} else if ($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != '') {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'delete');
    $delSql = "DELETE FROM `" . TBL_COMPANY_MAPPING . "` WHERE `id` = '" . addslashes(encryptor('decrypt', $_REQUEST['delId'])) . "'";
    $sqlDelUserLevel = selectRow(TBL_COMPANY_MAPPING, " WHERE `id` = '" . addslashes(encryptor('decrypt', $_REQUEST['delId'])) . "'");
    if (executeSql($delSql)) {
        $err = 0;
        $_SESSION['successMsg'] = 'One Mapping has been deleted sucessfully.';
    } else {
        $err = 1;
        $_SESSION['errorMsg'] = 'Unable to delete Mapping';
    }
}
if ($_REQUEST["act"] == "activate" && !empty($_REQUEST['ids'])) {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'activate');
    $activateIds = implode(',', $_REQUEST['ids']);
    $statusSql = "	UPDATE `" . TBL_COMPANY_MAPPING . "`
						SET `status` = '1'
						,`last_modified` = '" . currenDateTime() . "'
						,`last_modified_by` = '" . $_SESSION['userId'] . "'
						WHERE `id` IN (" . addslashes($activateIds) . ")";

    if (executeSql($statusSql)) {
        $err = 0;
        $_SESSION['successMsg'] = 'Selected records status has been activated sucessfully.';
    } else {
        $err = 1;
        $_SESSION['errorMsg'] = 'Selected records status has not been activated sucessfully.';
    }
} else if ($_REQUEST["act"] == "inactivate" && !empty($_REQUEST['ids'])) {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'deactivate');
    $deactivateIds = implode(',', $_REQUEST['ids']);
    $statusSql = "	UPDATE `" . TBL_COMPANY_MAPPING . "`
						SET `status` = '0'
						,`last_modified` = '" . currenDateTime() . "'
						,`last_modified_by` = '" . $_SESSION['userId'] . "'
						WHERE `id` IN (" . addslashes($deactivateIds) . ")";

    if (executeSql($statusSql)) {
        $err = 0;
        $_SESSION['successMsg'] = 'Selected records status has been inactivated sucessfully.';
    } else {
        $err = 1;
        $_SESSION['errorMsg'] = 'Selected records status has not been inactivated sucessfully.';
    }
} else if ($_REQUEST["act"] == "delete" && !empty($_REQUEST['ids'])) {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_COMPANY_MAPPING, 'delete');
    $deleteIds = implode(',', $_REQUEST['ids']);
    $delSql = "DELETE FROM `" . TBL_COMPANY_MAPPING . "` WHERE `id` IN (" . addslashes($deleteIds) . ")";
    if (executeSql($delSql)) {
        $err = 0;
        $_SESSION['successMsg'] = 'Selected records has been deleted sucessfully.';
    } else {
        $err = 1;
        $_SESSION['errorMsg'] = 'Unable to delete selected records';
    }
}
// ----------cate---------
$sql = " SELECT * FROM `" . TBL_COMPANY_MAPPING . "` WHERE `id_shop` = '" . addslashes($_SESSION['shop']) . "'";
if ($_REQUEST['channel_id'] != '') {
    $sql .= " AND `channel_id` = '" . addslashes($_REQUEST['channel_id']) . "%'";
}
if ($_REQUEST['order'] != '') {
    $sql .= " ORDER BY `date_created` DESC";
} else {
    $sql .= " ORDER BY `date_created` DESC";
}
if ($_REQUEST['company_id'] != '') {
    $sql .= " AND `company_id` = '" . addslashes($_REQUEST['company_id']) . "%'";
}
$db->query($sql);
$numRows = $db->num_rows();
$pagging = new pagingClass($sql, $setpage);
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
                    <a type="button" class="btn btn-success" href="editCompanyMapping.php"> Add Company Mapping</a>

                </div>
            </div>

            <!-- /.box-header -->
            <?php //debugData($_REQUEST);
            //echo $SQL;?>
            <form name="searchForm" action="" method="get">
                <input type="hidden" value="1" name="searchFormSubmit" />
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Company</label>
                                <?php $categoryDropDown = '<select class="form-control select2" name="company_id">
											    <option value="">Select Company</option>';
                                $resCat = selectSql(TBL_COMPANY, " where status='1' and `id_shop` = '" . addslashes($_SESSION['shop']) . "' ", ' ORDER BY `id`');
                                if ($db->num_rows2($resCat)) {
                                    while ($resultCat = $db->fetch_object2($resCat)) {
                                        if ($_REQUEST['company_id'] == $resultCat->id) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        $categoryDropDown .= '<option ' . $selected . ' value="' . $resultCat->id . '">' . ucfirst($resultCat->name) . '</option>';
                                    }
                                }
                                echo $categoryDropDown .= '</select>';
                                ?>
                            </div>

                        </div>
                        <!-- /.col -->
                        <div class="col-md-6">
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
                            <!-- /.form-group -->
                        </div>
                        <!--col start -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Channel</label>
                                <?php $categoryDropDown = '<select class="form-control select2" name="channel_id">
											    <option value="">Select Channel</option>';

                                $resCat = selectSql(TBL_CHANNEL_MANAGER, " where status='1' ", ' ORDER BY `id`');
                                if ($db->num_rows2($resCat)) {
                                    while ($resultCat = $db->fetch_object2($resCat)) {
                                        if ($_REQUEST['id'] == $resultCat->id) {
                                            $selected = 'selected="selected"';
                                        } else {
                                            $selected = '';
                                        }
                                        $categoryDropDown .= '<option ' . $selected . ' value="' . $resultCat->id . '">' . ucfirst($resultCat->name) . '</option>';
                                    }
                                }
                                echo $categoryDropDown .= '</select>';
                                ?>
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
                        <table id="example2" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th> S.No</th>
				  <th>Shop</th>
				  <th>Channel</th>
                  <th>Company</th>
				  <th>Mapping Id</th>
                  <th>Status</th>
				  <th>Action</th>
                </tr>
                </thead>
                <tbody>
				<?php 
				 				
				if($total > 0){$counter = 1;
				  while($row = $db->fetch_object()){?>
                <tr>
                  <td> <?php echo $counter++;?>.&nbsp;</td>
				  <td><?php echo selectColumn(TBL_SHOP,'name'," WHERE `id` = '".$row->id_shop."'");   ?></td>
				  <td><?php echo selectColumn(TBL_CHANNEL_MANAGER,'name'," WHERE `id` = '".$row->channel_id."'");   ?></td>
				  <td><?php echo selectColumn(TBL_COMPANY,'name'," WHERE `id` = '".$row->company_id."'");   ?></td>
                  <td><?=$row->booking_engine_name;?></td>				 
                  <td><?=$row->status=='1'?'<span onclick="location.href=\'manageCompanyMapping.php?inactiveId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'" style="color:green;cursor:pointer;">Active</span>':'<span onclick="location.href=\'manageCompanyMapping.php?activeId='.encryptor('encrypt',$row->id).'&action=change&page='.$_REQUEST['page'].'\'"  style="color:red;cursor:pointer;">Inactive</span>';?>&nbsp;</td>			 
				  <td><img src="../images/view_edit.gif" style="cursor:pointer;" title=" View / Edit " onClick="window.location.href='editCompanyMapping.php?eId=<?=encryptor('encrypt',$row->id)?>&action=edit&page=<?=$_REQUEST['page']?>';" />&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/delete.gif" style="cursor:pointer;" title="Delete" onClick="if(confirm('Are you sure that you want to delete this record <?=$row->name;?>?')){window.location.href='manageCompanyMapping.php?delId=<?=encryptor('encrypt',$row->id)?>&action=delete&page=<?=$_REQUEST['page']?>';}"/></td>
                </tr>
               <?php }?> 
			 
				<tr>	 
					  <td align="right" colspan="6"><?php  echo $pagging->getLinks();?> </td>
                 </tr>                
				<?php }else {?>
				
				 <tr>
                      <td height="200" align="center" colspan="6">---- No Record Found ---- </td>
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