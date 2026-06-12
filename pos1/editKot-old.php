<?php include_once("../config/auto_loader.php");?>
<script src="<?php echo $SITE_URL; ?>/pos/js/custom.js" ></script>
<?php
include_once("include/function.php");
unset($_SESSION['POSKOT']);
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'view');
		    	
if($_REQUEST['action'] == 'delete' && $_REQUEST['delId'] != ''){
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH_DETAILS,'delete');
	$delSql = "DELETE FROM `".TBL_PURCH_DETAILS."` WHERE `id` = '".$_REQUEST['delId']."'";
	
	$sqlDelUserLevel = selectRow(TBL_PURCH_DETAILS," WHERE id = '".$_REQUEST['delId']."'");
	if(executeSql($delSql)){		
		$err = 0;		
		$_SESSION['successMsg'] = 'One KOT '.$sqlDelUserLevel["name"].' has been deleted sucessfully.';
	}else{
		$err = 1;
		$_SESSION['errorMsg'] = 'Unable to delete KOT '.$sqlDelUserLevel["name"];
	}
}
if($_REQUEST['editKotid']!=''){
		$EditID	=	$_REQUEST['editKotid'];
	}else{
		$EditID	=	$_REQUEST['editKotviewid'];
		}
$sql 		  = " SELECT * FROM `".TBL_PURCH_DETAILS."` WHERE `id_pos_purch` = '".encryptor(decrypt, $EditID)."' ";
$db->query($sql);
$numRows	  = $db->num_rows();
$pagging 	  = new pagingClass($sql,$setpage);
$db->query($pagging->getQuery());
$total 		= $db->num_rows();
$sql_purch 	= mysqli_query($connNew," SELECT * FROM `".TBL_PURCH."` WHERE `id` = '".encryptor(decrypt, $EditID)."' ");
$purch_row	= mysqli_fetch_object($sql_purch);

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
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
			 <div class="nav-tabs-custom">
			<div class="box-header with-border">
              <h3 class="box-title"><?php echo $_REQUEST['eId']==''?'Add':'Edit'?> <?php echo currentNavigation()['submenu']; ?> : <a><?php echo selectColumn(TBL_INV_INDENT,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->  			        
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 

            <div class="box-body">
		    <form name="FormPosKot" id="FormPosKot" action="kotbilling.php" method="post">
			<input type="hidden" value="1" name="FormSubmitPosKot" />
            <input type="hidden" value="<?php echo $purch_row->id;?>" name="id_pos_purch" id="id_pos_purch">
            <input name="id_attribute_table_group" id="id_attribute_table_group" type="hidden"  value="<?php echo $purch_row->id_attribute_table_group;?>"  >
            <input type="hidden" value="<?php echo $purch_row->id_attribute_table;?>" name="id_attribute_table" id="id_attribute_table">
            <input type="hidden" value="<?php echo $purch_row->id_attribute_shift;?>" name="id_attribute_shift" id="id_attribute_shift">
            <input type="hidden" value="<?php echo $purch_row->pax;?>" name="pax" id="pax">
            <input type="hidden" value="1" name="FormSubmitPosKot" /> 
            
            
        <div class="box-body">
          <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                  <label>Table</label>
                   <?php 

					  $ResultBlockedtable =array();

					  $CheckBlockedTable_Sql = "SELECT id_attribute_table FROM pos_purch WHERE pos_bill_type='1' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)";

	                   $db->query($CheckBlockedTable_Sql); 

	                  while($ResultBlockedtable1 = $db->fetch_object()){ 

	                  	$ResultBlockedtable[]	=	$ResultBlockedtable1->id_attribute_table;	
					  }

				$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table_group'."' ",' ORDER BY `id` asc');

								  if($db->num_rows2($resCat)){

								  	$resultCat = $db->fetch_object2($resCat);  

								  }

				  $resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'   AND id_table_group='".$resultCat->id."'  AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY `field_value`');

					if($db->num_rows2($resContact) > 0){?>

                      <?php	

                		$i=1;
					$categoryDropDown = '<select  class="form-control select2" name="id_attribute_table" data-parsley-required data-parsley-errors-container="#id_tableError" style="width: 100%">

									<option value="">Select Table</option>';

                while($rowContact = $db->fetch_object2($resContact)){
					if($_REQUEST['id_attribute_table'] == $rowContact->id){

											$selected = 'selected="selected"';

										}elseif($purch_row->id_attribute_table == $rowContact->id){

											$selected = 'selected="selected"';

										}else{

											$selected = '';
										}

		$categoryDropDown .= '<option '.$selected.' value="'.$rowContact->id.'">'.ucfirst($rowContact->field_value).'</option>';
					
	  ?>

		  <?php		}}
		       echo $categoryDropDown .= '</select>';
		   ?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
            
              <div class="col-md-3">
                <div class="form-group">
                  <label>No Of Paxs</label>
                   <select class="form-control select2" name="pax" id="pax" data-parsley-required style="width: 100%">		
				
			<?php	for ($i=1; $i<=50; $i++)
    				{
        
            $availableData .='<option value="'.$i.'"';
			 if($purch_row->pax ==$i){
			 $availableData .='selected="selected"';
			 }
			 
			 echo $availableData .='>'.$i.'</option>';
       
    }?>

                </select>
                 
                </div>
                
                <!-- /.form-group --> 
                
              </div>                             
            <!-- /.col -->
            
            <div class="col-md-3">
                <div class="form-group">
                  <label>Shift</label>
                              <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError" style="width: 100%">

									<option value="">Select Shift</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'shift'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($_REQUEST['id_mst_attributes_store'] == $resultCat->id){

											$selected = 'selected="selected"';

										}elseif($purch_row->id_attribute_shift == $resultCat->id){

											$selected = 'selected="selected"';

										}else{

											$selected = '';

										}

										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</option>';

									}

								  }

								 	echo $categoryDropDown .= '</select>';

								  ?>

                </div>
                
                <!-- /.form-group --> 
                
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Steward</label>
                 
                  <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_steward" data-parsley-required data-parsley-errors-container="#id_stewardError" style="width: 100%">

									<option value="">Select Steward</option>';

								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'steward'."' ",' ORDER BY `field_value`');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($_REQUEST['id_attribute_steward'] == $resultCat->id){

											$selected = 'selected="selected"';

										}elseif($purch_row->id_attribute_steward == $resultCat->id){

											$selected = 'selected="selected"';

										}else{

											$selected = '';

										}

										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->field_value).'</optiin>';

									}

								  }

								 	echo $categoryDropDown .= '</select>';

								  ?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
            </div>
             
          <!-- /.row -->
        </div>       
 			<div class="col-md-12" id="GetItemListView"><div>
                   
             

        <div class="box-body table-responsive" style="padding-top: 1px;padding-left: 1px;padding-right: 5px;">
          <table id="myTableOrder" class="table table-striped table-bordered dataTable no-footer" cellspacing="0" >
               <?php /*?><thead>
              <tr>
                <th width="10%"> S.No.&nbsp;</th>
                <th>Items Name</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Amount</th>
                <th>Action</th>
              </tr>
            </thead><?php */?>
            <tbody>
            </tbody>

          </table>
        </div>
    </div>
  </div>  
   </form><div class="col-md-12" id="GetItemListViewEdit"><div>
   
   <div id="bookedby" class="well" style="max-width:44em;"> 
  <form id="Formkotremarks" autocomplete="off">
  <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
  	<div class="form-group">
      <label for="title">Remarks</label>
      
      <textarea rows="4" cols="50" type="text" class="form-control input-sm" placeholder="Enter Remark" id="remark" name="remark" value="" data-parsley-required></textarea>
    </div>
	
	
	<div class="form-group">
		 <label for="btn">&nbsp;<br><br></label>
		<button class="btn btn-primary" onclick="ajaxCancelKot();" type="button">Save</button>
		<button class="bookedby_close btn btn-default">Close</button>

	</div>
  </form>
</div>
 <?php if(!empty($_REQUEST['editKotid'])){?> 
  <script>
window.onload = function() { 
		editKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>);
		//editViewKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>);
		};
</script>
 <?php } ?>
  <?php if(!empty($_REQUEST['editKotviewid'])){?> 
   <script>
window.onload = function() { 
		editViewKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotviewid']); ?>);
		};
</script>
 <?php } ?>
 <?php if(!empty($_REQUEST['CancelledKOT'])){?> 
   <script>
window.onload = function() { 
		ViewCancelledKOT(<?php echo encryptor(decrypt, $_REQUEST['CancelledKOT']); ?>);
		};
</script>
 <?php } ?>
  
               </div>
         	</div>
              <!-- /.box-body -->	
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>	


<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #1296f3; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:22px;">Audit Trail</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Details</th>   
          </tr>
        </thead>
        
        <tbody id="roombutton">
          
        </tbody>
      </table>
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn btn-danger" data-dismiss="modal"> <span class="glyphicon glyphicon-off"></span> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->


<?php include_once("../includes/footer.php");?>  

<script type="text/javascript">

  function audittrial(clicked_value){
		//alert(clicked_value);
		$('#auditModal').modal('show');
		var table ='pos_purch';
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: { tablename : table },
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
</script>