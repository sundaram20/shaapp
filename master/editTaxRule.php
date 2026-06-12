<?php 
include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_RATE,'view');

$err = 0;

// ---------- ADD ----------
if(($_POST['Save'] == 'Add') && empty($_POST['eId'])){
    $CheckDuplicateDateSQl = executeSql("SELECT * FROM `".TBL_TAX_RULE."` 
        WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' 
        AND `start_date` > '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."'");

    if(num_rows($CheckDuplicateDateSQl) == 0){
        $addDateSql = "INSERT INTO `".TBL_TAX_DATE_RULE."` SET 	
                        `start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
                        `id_shop` = '".addslashes($_SESSION['shop'])."',
                        `date_created` = '".currenDateTime()."',
                        `last_modified` = '".currenDateTime()."',
                        `last_modified_by` = '".$_SESSION['userId']."',
                        `status` = '".addslashes($_POST['status'])."'";
        executeSql($addDateSql);
        $date_rule_id= $db->insert_id();			

        foreach($_REQUEST['tax_slabs_from'] as $data =>$value){
            $addSql = "INSERT INTO `".TBL_TAX_RULE."` SET 							
                        `tax_uniqueid` = '".addslashes($date_rule_id)."',
                        `start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
                        `id_shop` = '".addslashes($_SESSION['shop'])."',
                        `id_shop_group` = '1',							
                        `tax_slabs_from` = '".addslashes($_POST['tax_slabs_from'][$data])."',
						`charges_name` = '".addslashes($_POST['charges_name'][$data])."',
                        `tax_slabs_to` = '".addslashes($_POST['tax_slabs_to'][$data])."',
                        `tax_inc_slabs_from` = '".addslashes($_POST['tax_inc_slabs_from'][$data])."',
                        `tax_inc_slabs_to` = '".addslashes($_POST['tax_inc_slabs_to'][$data])."',
                        `tax_percent` = '".addslashes($_POST['tax_percentage'][$data])."',
                        `date_created` = '".currenDateTime()."',
                        `last_modified` = '".currenDateTime()."',
                        `last_modified_by` = '".$_SESSION['userId']."',
                        `status` = '1'";
            executeSql($addSql);
        }
        unset($_POST);
        $_SESSION['successMsg'] = 'New Tax Configuration details has been added successfully.';
        header("location:manageTaxRule.php");
        exit;
    }else{
        $_SESSION['errorMsg'] = 'Selected Date is less than the Previous Date.';
    }
}

// ---------- EDIT ----------
else if(($_POST['Save'] == 'Edit')){ 
    $uniqueCode = addslashes($_REQUEST['uniqueCode']);

    // Get submitted IDs safely
    $submittedIds = isset($_POST['tax_id']) ? array_filter($_POST['tax_id']) : [];

    // 1. Delete slabs that were removed in UI
    $existingIdsRes = executeSql("SELECT id FROM `".TBL_TAX_RULE."` 
        WHERE `tax_uniqueid` = '".$uniqueCode."' 
        AND `id_shop` = '".addslashes($_SESSION['shop'])."'");

    $existingIds = [];
    while($row = mysqli_fetch_array($existingIdsRes)){
        $existingIds[] = $row['id'];
    }

    $idsToDelete = array_diff($existingIds, $submittedIds);
    if(!empty($idsToDelete)){
         $deleteSql = "DELETE FROM `".TBL_TAX_RULE."` 
                      WHERE id IN (".implode(',', $idsToDelete).") 
                      AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
        executeSql($deleteSql);
    }

    // 2. Insert or Update submitted slabs
    foreach($_REQUEST['tax_slabs_from'] as $data =>$value){
        $id = $_POST['tax_id'][$data] ?? ''; // safe
        $from = addslashes($_POST['tax_slabs_from'][$data]);
        $to = addslashes($_POST['tax_slabs_to'][$data]);
        $inc_from = addslashes($_POST['tax_inc_slabs_from'][$data]);
        $inc_to = addslashes($_POST['tax_inc_slabs_to'][$data]);
        $percent = addslashes($_POST['tax_percentage'][$data]);

        if($id != ''){
            // update
            $editSql = "UPDATE `".TBL_TAX_RULE."` SET 							
                        `tax_slabs_from` = '".$from."',
                        `tax_slabs_to` = '".$to."',
                        `tax_inc_slabs_from` = '".$inc_from."',
                        `tax_inc_slabs_to` = '".$inc_to."',
						`charges_name` = '".addslashes($_POST['charges_name'][$data])."',
                        `tax_percent` = '".$percent."',
                        `last_modified` = '".currenDateTime()."',
                        `last_modified_by` = '".$_SESSION['userId']."',
                        `status` = '1'
                        WHERE `id` = '".$id."'
                        AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
            executeSql($editSql);
        }else{
            // insert new
            $addSql = "INSERT INTO `".TBL_TAX_RULE."` SET 							
                        `tax_uniqueid` = '".$uniqueCode."',
                        `start_date` = '".addslashes(date('Y-m-d',strtotime($_POST['start_date'])))."',
                        `id_shop` = '".addslashes($_SESSION['shop'])."',
                        `id_shop_group` = '1',							
                        `tax_slabs_from` = '".$from."',
                        `tax_slabs_to` = '".$to."',
                        `tax_inc_slabs_from` = '".$inc_from."',
						`charges_name` = '".addslashes($_POST['charges_name'][$data])."',
                        `tax_inc_slabs_to` = '".$inc_to."',
                        `tax_percent` = '".$percent."',
                        `date_created` = '".currenDateTime()."',
                        `last_modified` = '".currenDateTime()."',
                        `last_modified_by` = '".$_SESSION['userId']."',
                        `status` = '1'";
            executeSql($addSql);
        }
    }

     $_SESSION['successMsg'] = 'Tax Configuration details have been updated successfully.';
    header("location:manageTaxRule.php");
    exit;
}

// ---------- FETCH FOR EDIT ----------
if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
    $sql = "SELECT * FROM `".TBL_TAX_RULE."`
             WHERE `tax_uniqueid` = '".addslashes(encryptor('decrypt',$_REQUEST['eId']))."'  
             AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
    $res = $db->query($sql);
    $rows = [];
    if($db->num_rows() > 0){
        while($r = $db->fetch_object()){
            $rows[] = $r;
        }
        $row = $rows[0]; // for header fields
    }	
    $Disable='disabled="disabled"';					
}	
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>

<div class="content-wrapper">
  <section class="content-header">
    <h1> Tax Manager <small>Tax Configuration Master</small> </h1>
    <ol class="breadcrumb">
      <li><a href="home.php"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Tax Configuration Master</li>
    </ol>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="nav-tabs-custom">

<script type="text/javascript">
function GetDynamicRow(from='', to='', inc_from='', inc_to='', percent='', id='',charges_name='') {
    return '<tr>'
        + '<td><input type="hidden" name="tax_id[]" value="'+id+'">'
        + '<input type="text" class="form-control tax_slabs_from" name="tax_slabs_from[]" value="'+from+'" placeholder="Tariff From" data-parsley-required data-parsley-type="number"></td>'
        + '<td><input type="text" class="form-control tax_slabs_to" name="tax_slabs_to[]" value="'+to+'" placeholder="Tariff To" data-parsley-required data-parsley-type="number"></td>'
        + '<td><input type="text" class="form-control tax_inc_slabs_from" name="tax_inc_slabs_from[]" value="'+inc_from+'" placeholder="Inc Slabs From" data-parsley-type="number"></td>'
        + '<td><input type="text" class="form-control tax_inc_slabs_to" name="tax_inc_slabs_to[]" value="'+inc_to+'" placeholder="Inc Slabs To" data-parsley-type="number"></td>'
        + '<td><input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="'+percent+'" placeholder="Tax %" data-parsley-required data-parsley-type="number"></td>'
	 + '<td><input type="text" class="form-control charges_name" name="charges_name[]" value="'+charges_name+'" placeholder="charges_name" data-parsley-required ></td>'
        + '<td><button type="button" onclick="RemoveTextBox(this)" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span></button></td>'
        + '</tr>';
}

function AddTextBox(from='', to='', inc_from='', inc_to='', percent='') {
    var row = GetDynamicRow(from, to, inc_from, inc_to, percent, '');
    document.getElementById("TextBoxContainer").insertAdjacentHTML('beforeend', row);
}

function RemoveTextBox(button) {
    button.closest('tr').remove();
}
</script>
          
<div class="box-header with-border">
  <h3 class="box-title"><?php echo $_REQUEST['id']==''?'Add':'Edit'?> Tax Configuration </h3>
</div>

<div class="form-group has-error" align="center" >
  <?php if($_SESSION['errorMsg']){?>
    <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
    <?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
    <p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
    <?php unset($_SESSION['successMsg']);}?>
</div>

<form name="rateMaster" id="rateMaster"  method="post" enctype="multipart/form-data" role="form" data-parsley-validate autocomplete="off">
  <div class="box-body"> 
    <?php 
    if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){
         $uniqueCode = $row->tax_uniqueid; 
    }else{
        $uniqueCode = rand(0000,9999); 
    }
    ?> 
    <input type="hidden" value="<?php echo $uniqueCode;?>" name="uniqueCode"  />

    <div class="form-group">
      <label for="start_date">Start Date</label>
      <?php 
      if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){?>
        <input type="text" class="form-control pickerdate" id="start_date" name="start_date"
          value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>" 
          disabled readonly data-parsley-required>
        <input type="hidden" name="start_date" value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>">
      <?php }else{?>     
        <input type="text" class="form-control pickerdate" id="start_date" name="start_date"
          value="<?php if($_POST) echo $_POST['start_date'];elseif($row->start_date) echo stripslashes(date('d-m-Y',strtotime($row->start_date))); else echo date('d-m-Y'); ?>" data-parsley-required>
      <?php }?>     
    </div>
    
    <div class="box box-success table-responsive no-padding" style="margin-bottom:0px !important;">
      <table class="table table-hover" style="margin-bottom:0px !important;">
        <thead>
          <tr>
            <th>Tariff From</th>
            <th>Tariff To</th>
            <th>Inc Slabs From</th>
            <th>Inc Slabs To</th>	
            <th>Tax %</th>	
            <th>Action</th>	
          </tr>
        </thead>
        <tbody id="TextBoxContainer">
          <?php 
          if(!empty($rows)){ 
              foreach($rows as $slab){ ?>
                  <tr>
                    <td>
                      <input type="hidden" name="tax_id[]" value="<?php echo $slab->id; ?>">
                      <input type="text" class="form-control tax_slabs_from" name="tax_slabs_from[]" value="<?php echo $slab->tax_slabs_from; ?>" placeholder="Tariff From" data-parsley-required data-parsley-type="number">
                    </td>
                    <td><input type="text" class="form-control tax_slabs_to" name="tax_slabs_to[]" value="<?php echo $slab->tax_slabs_to; ?>" placeholder="Tariff To" data-parsley-required data-parsley-type="number"></td>
                    <td><input type="text" class="form-control tax_inc_slabs_from" name="tax_inc_slabs_from[]" value="<?php echo $slab->tax_inc_slabs_from; ?>" placeholder="Inc Slabs From" data-parsley-type="number"></td>
                    <td><input type="text" class="form-control tax_inc_slabs_to" name="tax_inc_slabs_to[]" value="<?php echo $slab->tax_inc_slabs_to; ?>" placeholder="Inc Slabs To" data-parsley-type="number"></td>
                    <td><input type="text" class="form-control tax_percentage" name="tax_percentage[]" value="<?php echo $slab->tax_percent; ?>" placeholder="Tax %" data-parsley-required data-parsley-type="number"></td>
					  <td><input type="text" class="form-control charges_name" name="charges_name[]" value="<?php echo $slab->charges_name; ?>" placeholder="charges_name" data-parsley-required></td>
                    <td><button type="button" onclick="RemoveTextBox(this)" class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-minus"></span></button></td>
                  </tr>
              <?php }
          } ?>
        </tbody>
      </table>
    </div>

    <!-- Add button -->
    <button type="button" class="btn btn-success btn-sm" onclick="AddTextBox();" style="width: 87%;">
      <span class="glyphicon glyphicon-plus"></span> Add Tax Slab
    </button>
  </div>

  <div class="box-footer">
    <input type='submit' value='<?=($_REQUEST['eId']==''?'Add':'Edit')?>' class="btn btn-primary" name="Save" >
    &nbsp;&nbsp;&nbsp;&nbsp;
    <input type='button' value='Cancel' class="btn btn-default" onclick='location.replace("manageTaxRule.php?page=<?php echo $_GET['page']; ?>"); '>
  </div>
</form>
</div>
</div>
</div>
</section>
</div>

<?php include_once("../includes/footer.php")?>
