<?php include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'view');

if($_REQUEST['eId'] == ''){
    checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'add');
} else {
    checkUserLevelPermission($_SESSION['userLevel'], TBL_ATTRIBUTES, 'edit');
}

//---------------------------------------------------------------------------------------------------------
if($_POST['Save']){

    $err = 0;

    if($err == 0){

        //---------- ADD ----------
        if(($_POST['Save'] == 'Save') && empty($_POST['eId'])){

            $addSql = "INSERT INTO `".TBL_ATTRIBUTES."` SET
                        `table_name`            = 'cashier',
                        `field_name`            = 'cashier_name',
                        `field_value`           = '".addslashes($_POST['cashier_name'])."',
                        `field_description`     = '".addslashes($_POST['field_description'])."',
                        `id_shop`               = '".$_SESSION['shop']."',
                        `status`                = '".addslashes($_POST['status'])."',
                        `date_created`          = '".currenDateTime()."',
                        `last_modified`         = '".currenDateTime()."',
                        `id_mst_user_created_by`  = '".$_SESSION['userId']."',
                        `id_mst_user_modified_by` = '".$_SESSION['userId']."'";

            if(executeSql($addSql)){
                $_SESSION['successMsg'] = 'Cashier has been added successfully.';
            } else {
                $_SESSION['errorMsg'] = 'Cashier could not be saved. Please try again.';
            }
            header("location:manageCashier.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;

        //---------- UPDATE ----------
        } else if(($_POST['Save'] == 'Save') && !empty($_POST['eId'])){

            $cashierId = addslashes(encryptor(decrypt, $_POST['eId']));

            $editSql = "UPDATE `".TBL_ATTRIBUTES."` SET
                        `field_value`             = '".addslashes($_POST['cashier_name'])."',
                        `field_description`       = '".addslashes($_POST['field_description'])."',
                        `status`                  = '".addslashes($_POST['status'])."',
                        `last_modified`           = '".currenDateTime()."',
                        `id_mst_user_modified_by` = '".$_SESSION['userId']."'
                        WHERE `id` = '".$cashierId."'
                        AND `table_name` = 'cashier'
                        AND `id_shop` = '".$_SESSION['shop']."'";

            if(executeSql($editSql)){
                $_SESSION['successMsg'] = 'Cashier has been updated successfully.';
            } else {
                $_SESSION['errorMsg'] = 'Cashier could not be updated. Please try again.';
            }
            header("location:manageCashier.php?submenu=".$_GET['submenu']."&session=".$_GET['session']);
            exit;
        }

    } else {
        $_SESSION['errorMsg'] = 'Cashier has not been saved. Please make corrections.';
    }
}

//---------------------------------------------------------------------------------------------------------
// Load existing record for Edit
$row                        = new stdClass();
$row->id                    = '';
$row->field_value           = '';
$row->field_description     = '';
$row->status                = 1;
$row->date_created          = '';
$row->last_modified         = '';
$row->id_mst_user_created_by  = '';
$row->id_mst_user_modified_by = '';

if(!empty($_REQUEST['eId']) && $_REQUEST['action'] == 'edit'){
    $sql = "SELECT * FROM `".TBL_ATTRIBUTES."`
            WHERE `id` = '".addslashes(encryptor(decrypt, $_REQUEST['eId']))."'
            AND `table_name` = 'cashier'
            AND `id_shop` = '".$_SESSION['shop']."'";
    $db->query($sql);
    if($db->num_rows() > 0){
        $row = $db->fetch_object();
    }
}
?>
<?php include_once("../includes/header.php"); ?>
<?php include_once("../includes/left.php"); ?>

<style>
    .select2-container { width: 100% !important; }
</style>

<div class="content-wrapper">

    <?php $session = $_GET['submenu']; ?>
    <section class="content-header">
        <h5 class="box-title">
            <?php echo $_REQUEST['eId'] == '' ? 'Add' : 'Edit'; ?> Cashier :
            <span style="color:#1296f3">
                <?php echo $_REQUEST['eId'] == '' ? 'New Record' : htmlspecialchars($row->field_value); ?>
            </span>
        </h5>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="manageCashier.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>">Cashier Master</a></li>
            <li class="active"><?php echo $_REQUEST['eId'] == '' ? 'Add' : 'Edit'; ?> Cashier</li>
        </ol>
    </section>

    <section class="content">
        <hr class="br-line">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom mb-0 shadow-none">

                    <form name="cashier_form" action="" method="post"
                          data-parsley-validate autocomplete="off" id="cashier_form">

                        <input type="hidden" value="<?php echo $_REQUEST['eId']; ?>" name="eId" id="eId" />
                        <input type="hidden" value="<?php echo $_GET['submenu']; ?>" name="submenu" id="submenu" />
                        <input type="hidden" value="<?php echo $_GET['session']; ?>" name="session" id="session" />

                        <!-- Messages -->
                        <div class="form-group has-error" align="center">
                            <?php if($_SESSION['errorMsg']){ ?>
                                <p class="help-block"><?php echo messageError($_SESSION['errorMsg']); ?></p>
                            <?php unset($_SESSION['errorMsg']); } elseif($_SESSION['successMsg']){ ?>
                                <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']); ?></p>
                            <?php unset($_SESSION['successMsg']); } ?>
                        </div>

                        <div class="box-body">

                            <div class="row">

                                <!-- Cashier Name -->
                                <div class="form-group col-xs-12 col-md-4">
                                    <label for="cashier_name">Cashier Name <font color="#1296f3">*</font></label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-user"></i></div>
                                        <input type="text" class="form-control"
                                               placeholder="Enter Cashier Name"
                                               id="cashier_name" name="cashier_name"
                                               value="<?php if($_POST) echo htmlspecialchars($_POST['cashier_name']); else echo htmlspecialchars($row->field_value); ?>"
                                               data-parsley-required required>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div class="form-group col-xs-12 col-md-8">
                                    <label for="field_description">Description</label>
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-info-circle"></i></div>
                                        <input type="text" class="form-control"
                                               placeholder="Enter Description"
                                               id="field_description" name="field_description"
                                               value="<?php if($_POST) echo htmlspecialchars($_POST['field_description']); else echo htmlspecialchars($row->field_description); ?>">
                                    </div>
                                </div>

                            </div>

                            <!-- Status -->
                            <div class="row mt-10">
                                <div class="form-group col-xs-12">
                                    <label>Status</label><br>
                                    <label style="font-weight:normal; margin-right:15px;">
                                        <input class="flat-blue" type="radio" value="1" name="status"
                                               <?php if($_POST) { if($_POST['status']=='1') echo 'checked'; } elseif($row->status == 1 || $row->id == '') echo 'checked'; ?> />
                                        Active
                                    </label>
                                    <label style="font-weight:normal;">
                                        <input class="flat-blue" type="radio" value="0" name="status"
                                               <?php if($_POST) { if($_POST['status']=='0') echo 'checked'; } elseif($row->status == 0 && $row->id != '') echo 'checked'; ?> />
                                        Inactive
                                    </label>
                                </div>
                            </div>

                            <!-- Audit fields (edit only) -->
                            <?php if($row->date_created != ''){ ?>
                            <div class="row mt-10">
                                <div class="form-group col-xs-12 col-md-3">
                                    <label>Date Created</label>
                                    <input type="text" disabled class="form-control"
                                           value="<?php echo stripslashes(dateformat($row->date_created)); ?>">
                                </div>
                                <div class="form-group col-xs-12 col-md-3">
                                    <label>Created By</label>
                                    <input type="text" disabled class="form-control"
                                           value="<?php echo stripslashes(selectColumn(TBL_USERS, 'name', 'WHERE id="'.$row->id_mst_user_created_by.'"')); ?>">
                                </div>
                                <div class="form-group col-xs-12 col-md-3">
                                    <label>Last Updated</label>
                                    <input type="text" disabled class="form-control"
                                           value="<?php echo stripslashes(dateformat($row->last_modified)); ?>">
                                </div>
                                <div class="form-group col-xs-12 col-md-3">
                                    <label>Last Updated By</label>
                                    <input type="text" disabled class="form-control"
                                           value="<?php echo stripslashes(selectColumn(TBL_USERS, 'name', 'WHERE id="'.$row->id_mst_user_modified_by.'"')); ?>">
                                </div>
                            </div>
                            <?php } ?>

                        </div>

                        <hr class="br-line mb-10">
                        <div class="box-footer p-0 br-none">
                            <input type="submit" value="Save" class="btn c-btn" name="Save">
                            <a type="button" class="btn c-btn"
                               onclick='location.replace("manageCashier.php?submenu=<?php echo $_GET['submenu']; ?>&session=<?php echo $_GET['session']; ?>");'>
                                <i class="far fa-window-close"></i> Close
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include_once("../includes/footer.php"); ?>

<script type="text/javascript">
    $(document).ready(function(){
        if($.fn.select2){
            $('.select2').select2();
        }
    });
</script>