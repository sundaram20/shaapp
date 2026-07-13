<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'view');

// ── DELETE ───────────────────────────────────────────────────────────────────
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
    if(checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'delete')){
        $delId = addslashes(encryptor(decrypt, $_REQUEST['delId']));

        // Only delete if cashier is not used in any cash_transaction
        $checkSql = "SELECT id FROM `cash_transaction` WHERE `id_mst_cashier` = '".$delId."' LIMIT 1";
        $db->query($checkSql);
        if($db->num_rows() > 0){
            $_SESSION['errorMsg'] = 'This cashier cannot be deleted as they are linked to existing transactions.';
        } else {
            $delSql = "DELETE FROM `".TBL_ATTRIBUTES."`
                       WHERE `id` = '".$delId."'
                       AND `table_name` = 'cashier'
                       AND `id_shop` = '".$_SESSION['shop']."'";
            if(executeSql($delSql)){
                $_SESSION['successMsg'] = 'Cashier has been deleted successfully.';
            } else {
                $_SESSION['errorMsg'] = 'Cashier could not be deleted. Please try again.';
            }
        }
    }
    header("location:manageCashier.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── STATUS CHANGE ────────────────────────────────────────────────────────────
if($_REQUEST['action'] == 'change'){
    if($_REQUEST['activeId'] != ''){
        if(checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'active')){
            $statusId = addslashes(encryptor(decrypt, $_REQUEST['activeId']));
            $statusSql = "UPDATE `".TBL_ATTRIBUTES."` SET
                          `status` = '1',
                          `last_modified` = '".currenDateTime()."',
                          `id_mst_user_modified_by` = '".$_SESSION['userId']."'
                          WHERE `id` = '".$statusId."'
                          AND `table_name` = 'cashier'";
            if(executeSql($statusSql)){
                $_SESSION['successMsg'] = 'Status has been changed successfully.';
            } else {
                $_SESSION['errorMsg'] = 'Status could not be changed.';
            }
        }
    } elseif($_REQUEST['inactiveId'] != ''){
        if(checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'inactive')){
            $statusId = addslashes(encryptor(decrypt, $_REQUEST['inactiveId']));
            $statusSql = "UPDATE `".TBL_ATTRIBUTES."` SET
                          `status` = '0',
                          `last_modified` = '".currenDateTime()."',
                          `id_mst_user_modified_by` = '".$_SESSION['userId']."'
                          WHERE `id` = '".$statusId."'
                          AND `table_name` = 'cashier'";
            if(executeSql($statusSql)){
                $_SESSION['successMsg'] = 'Status has been changed successfully.';
            } else {
                $_SESSION['errorMsg'] = 'Status could not be changed.';
            }
        }
    }
    header("location:manageCashier.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
    exit;
}

// ── SEARCH FILTERS ────────────────────────────────────────────────────────────
$where = "WHERE `table_name` = 'cashier'
          AND `field_name` = 'cashier_name'
          AND `id_shop` = '".addslashes($_SESSION['shop'])."'";

if(isset($_GET['search_name']) && $_GET['search_name'] != ''){
    $where .= " AND `field_value` LIKE '%".addslashes($_GET['search_name'])."%'";
}
if(isset($_GET['status']) && $_GET['status'] != ''){
    $where .= " AND `status` = '".addslashes($_GET['status'])."'";
}

// ── FETCH RECORDS ─────────────────────────────────────────────────────────────
$resList    = selectSql(TBL_ATTRIBUTES, $where, ' ORDER BY field_value ASC');
$totalCount = $db->num_rows2($resList);
?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .label {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 100% !important;
    }
</style>

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 col-xs-12">
                <h6 class="p-0 m-0">
                    <span style="color:#333;">&nbsp;<i class="fa fa-users"></i> Cashier Master</span>
                </h6>
            </div>
            <div class="col-md-4 col-xs-12 dd-f">
                <div class="icn-box"><div class="btn-group"></div></div>
            </div>
            <div class="col-md-4 col-xs-12 tb-br">
                <?php echo breadCrumbs(); ?>
            </div>
        </div>
    </section>

    <section class="content">

        <!-- Messages -->
        <div class="form-group has-error" align="center">
            <?php if($_SESSION['errorMsg']){ ?>
                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
            <?php unset($_SESSION['errorMsg']); } elseif($_SESSION['successMsg']){ ?>
                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
            <?php unset($_SESSION['successMsg']); } ?>
        </div>

        <div class="box box-default">
            <div class="box-header">
                <h6 class="box-title">Search <small>Records:(<?php echo $totalCount; ?>) &nbsp;</small></h6>
                <div class="btn-group pull-right">
                    <a type="button" class="btn n-btn"
                       href="editCashier.php?action=edit&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>">
                       Add Cashier
                    </a>
                </div>
            </div>

            <form name="searchForm" action="" method="get">
                <input type="hidden" name="submenu" value="<?php echo $_GET['submenu']; ?>">
                <input type="hidden" name="session" value="<?php echo $_GET['session']; ?>">

                <div class="box-body">
                    <div class="row">

                        <!-- Cashier Name -->
                        <div class="col-md-3 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label>Cashier Name</label>
                                <input type="text" name="search_name" id="search_name"
                                       value="<?php echo htmlspecialchars($_GET['search_name']); ?>"
                                       class="form-control" placeholder="Search Name" />
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="form-group col-xs-6 col-md-3 col-sm-6">
                            <label for="status">Status</label>
                            <select class="form-control select2" name="status" id="status" style="width:100%">
                                <option value="">All</option>
                                <option value="1" <?php if($_GET['status'] === '1') echo 'selected'; ?>>Active</option>
                                <option value="0" <?php if($_GET['status'] === '0') echo 'selected'; ?>>Inactive</option>
                            </select>
                        </div>

                    </div>

                    <div class="box-footer pt-0 pl-0 br-none">
                        <input name="Search" type="submit" class="btn o-btn" value="Apply Filters" />
                        <a href="manageCashier.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>"
                           class="btn c-btn">Clear</a>
                    </div>
                </div>
            </form>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header table-h text-center">
                        <h3 class="box-title">Cashier List</h3>
                    </div>

                    <div class="box-body table-responsive">
                        <table id="CashierTable" class="table table-striped table-bordered dataTable no-footer" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="10%">S.No.</th>
                                    <th>Cashier Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                if($totalCount > 0){
                                    $i = 1;
                                    while($rec = $db->fetch_object2($resList)){

                                        // Check if cashier is in use (linked to cash_transaction)
                                        $inUseSql = "SELECT id FROM `cash_transaction`
                                                     WHERE `id_mst_cashier` = '".$rec->id."' LIMIT 1";
                                        $db->query($inUseSql);
                                        $inUse = ($db->num_rows() > 0);
                                        // Reset main result pointer is not needed since we use fetch_object2
                            ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($rec->field_value); ?></td>
                                        <td><?php echo htmlspecialchars($rec->field_description); ?></td>
                                        <td>
                                            <?php if($rec->status == '1'){ ?>
                                                <span onclick="location.href='manageCashier.php?inactiveId=<?php echo encryptor(encrypt, $rec->id); ?>&action=change&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>'"
                                                      style="color:green; cursor:pointer;">Active</span>
                                            <?php } else { ?>
                                                <span onclick="location.href='manageCashier.php?activeId=<?php echo encryptor(encrypt, $rec->id); ?>&action=change&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>'"
                                                      style="color:red; cursor:pointer;">Inactive</span>
                                            <?php } ?>
                                        </td>
                                        <td class="d-flex">
                                            <img src="../images/edit.png" style="cursor:pointer; height:20px;"
                                                 title="View / Edit"
                                                 onClick="window.location.href='editCashier.php?eId=<?php echo encryptor(encrypt, $rec->id); ?>&action=edit&submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>';" />
                                            &nbsp;&nbsp;&nbsp;&nbsp;
                                            <?php if($inUse){ ?>
                                                <img src="../images/chat.png" style="cursor:pointer; height:20px;"
                                                     title="In Use — cannot delete" />
                                            <?php } else { ?>
                                                <img src="../images/close.png" style="cursor:pointer; height:18px;"
                                                     title="Delete"
                                                     onClick="deletes('<?php echo encryptor(encrypt, $rec->id); ?>')" />
                                            <?php } ?>
                                        </td>
                                    </tr>
                            <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="5" class="text-center">No cashiers found.</td></tr>';
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script type="text/javascript">
    function deletes(sid){
        swal({
            title: "Are you sure?",
            text: "Delete this Cashier?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#1296f3',
            confirmButtonText: 'Yes, I am sure!',
            cancelButtonText: "No, cancel it!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if(isConfirm){
                window.location.href = 'manageCashier.php?delId=' + sid
                    + '&action=delete'
                    + '&submenu=<?php echo $_GET['submenu']; ?>'
                    + '&session=<?php echo $_GET['session']; ?>';
            }
        });
    }

    $(document).ready(function(){
        if($.fn.select2){
            $('.select2').select2();
        }
    });
</script>

<?php include_once("../includes/footer.php"); ?>