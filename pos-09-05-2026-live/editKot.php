<?php include_once("../config/auto_loader.php");?>
<script src="<?php echo $SITE_URL; ?>/pos/js/custom.js" ></script>
<?php
checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH_DETAILS,'edit');
include_once("include/function.php");
//unset($_SESSION['POSKOT']);


$UniqueCodeGen = 'UNIC'.rand(0000,9999);
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
	}else if($_REQUEST['editKotviewid']!=''){
		$EditID	=	$_REQUEST['editKotviewid'];
		}else  if($_REQUEST['CancelledKOT']!=''){
		 $EditID	=	$_REQUEST['CancelledKOT'];
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
<div class="content-wrapper" >
    <!-- Content Header (Page header) -->
	
	
    <?php $session=$_GET['submenu'];
     $status_name=$_GET['staus'];
	 
	if($status_name=="view"){
		$mode = "View";
		$update_id = encryptor(decrypt,$_REQUEST['editKotviewid']);
	}else if($status_name=="edit"){
		$mode = "Edit";
		$update_id = encryptor(decrypt,$_REQUEST['editKotid']);
	}else if($status_name=="cancel"){
		$mode = "Cancelled";
		$update_id = encryptor(decrypt,$_REQUEST['CancelledKOT']);
	}	
 
//echo encryptor(decrypt,$_REQUEST['editKotid']);
	?>
    <section class="content-header">
    	 <div class="row">
     <div class="col-md-4 col-xs-12"> 
      <!--<h3 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h3>-->
      <h6 class="" style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"><?php echo $_REQUEST['eId']!=''?'Add': $mode ?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc;"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes($update_id)."' ") ?> </span> 
			  
			  <a><?php echo selectColumn(TBL_INV_INDENT,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h6>
			  </div>
     <div class="col-md-4 col-xs-12 dd-f">
       
     			 <div class="icn-box">
                     <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn pull-right" href="manageKot.php?submenu=178&session=22" > <i class="fas fa-list "></i> KOT</a> </div>
                       <div class="btn-group  "> <a type="button"  title="Add Bill" class="btn n-btn pull-right" href="kotbilling.php?submenu=177&session=21" ><i class="fas fa-plus "></i> Bill </a> </div>
                     <div class="btn-group"> <a type="button"  title="List Bill" class="btn n-btn pull-right" href="manageOutletBilling.php?submenu=177&session=21" > <i class="fas fa-list "></i> Bill</a> </div>
                
                 </div>
     </div> 
     <div class="col-md-4 col-xs-12 tb-br">	
      <?php echo breadCrumbs(); ?>
     </div>
     </div> 
    </section> 
	
   
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
			 <div class="nav-tabs-custom">
			 
			 <?php //echo encryptor(decrypt,$_REQUEST['editKotid']); ?>
			<!--<div class="box-header with-border">
			
             <!-- <h3 class="box-title"><?php echo $_REQUEST['eId']!=''?'Add': $mode ?> <?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes($update_id)."' ") ?> </span> 
			  
			  <a><?php echo selectColumn(TBL_INV_INDENT,'doc_type'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND 'id' = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'"); ?></a></h3>
            </div>-->
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
            <input type="hidden" value="<?php echo $_REQUEST['submenu'];?>" name="submenu1" id="submenu1">
			
<input type="hidden" value="<?php echo encryptor(decrypt, $EditID);?>" name="pos_id" id="pos_id">
           
            <input type="hidden" value="<?php echo $purch_row->id;?>" name="id_pos_purch" id="id_pos_purch">
            <input name="id_attribute_table_group" id="id_attribute_table_group" type="hidden"  value="<?php echo $purch_row->id_attribute_table_group;?>"  >
            <input type="hidden" value="<?php echo $purch_row->id_attribute_table;?>" name="id_attribute_table" id="id_attribute_table">
            <input type="hidden" value="<?php echo $purch_row->id_attribute_shift;?>" name="id_attribute_shift" id="id_attribute_shift">
            <input type="hidden" value="<?php echo $purch_row->pax;?>" name="pax" id="pax">
            <input type="hidden" value="1" name="FormSubmitPosKot" /> 
            
            
           <div class="box-body">
          <div class="row">
            <div class="col-md-2">
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
//AND id_table_group='".$resultCat->id."'
				  $resContact = selectSql(TBL_ATTRIBUTES," where id_shop='".$_SESSION['shop']."'     AND  status = '1' AND table_name ='".'table'."' ",' ORDER BY `field_value`');

					if($db->num_rows2($resContact) > 0){?>

                      <?php	

                		$i=1;
					$categoryDropDown = '<select  disabled="disabled" class="form-control select2" name="id_attribute_table" data-parsley-required data-parsley-errors-container="#id_tableError" style="width: 100%">

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
		<?php	}}	echo $categoryDropDown .= '</select>'; ?>
                </div>
                
                <!-- /.form-group --> 
                
              </div>
            
              <div class="col-md-2">
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
            
            <div class="col-md-2">
                <div class="form-group">
                  <label>Shift</label>
                              <?php $categoryDropDown = '<select  disabled="disabled" class="form-control select2" name="id_attribute_shift" data-parsley-required data-parsley-errors-container="#id_shiftError" style="width: 100%">

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
              <div class="col-md-2">
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
                </div>
                <!-- /.form-group --> 
                 <?php 
$date =date('Y-m-d');	
$doc_type_kot='22';
$id_subsection = '0' ;	 
$retunDocConfig	=	docConfigNoValidator($doc_type_kot,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];

			  
			$sqlNat = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE   `doc_type`='22'  and id='".$id_doc_type_configuration."'";
			$resToNat = mysqli_query($connNew,$sqlNat);
			$numRowsNat =  mysqli_num_rows($resToNat);
			$rowNat =  mysqli_fetch_object($resToNat);
			$enable_nationality= $rowNat->enable_nationality;
			if($enable_nationality=='1'){
		?> 
              <div class="col-md-2">
                <div class="form-group">
                  <label>Nationality</label>
                 
                  <?php $categoryDropDown = '<select class="form-control select2" name="id_mst_country_lang" data-parsley-required data-parsley-errors-container="#id_stewardError" style="width: 100%">

									<option value="">Select nationality</option>';

								  $resCat = selectSql(TBL_COUNTRY_LANG,"where status = '1' AND `id_lang` = '1' AND nationality!='' ",' ORDER BY `name` asc');

								  if($db->num_rows2($resCat)){

								  	while($resultCat = $db->fetch_object2($resCat)){

										if($_REQUEST['id_mst_country_lang'] == $resultCat->id_country){

											$selected = 'selected="selected"';

										}elseif($purch_row->id_mst_country_lang == $resultCat->id_country){

											$selected = 'selected="selected"';

										}else{

											$selected = '';

										}

										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id_country.'">'.ucfirst($resultCat->nationality).'</optiin>';

									}

								  }

								 	echo $categoryDropDown .= '</select>';

								  ?>
                </div>
               
              </div>
            <?php } ?> 
             
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
   </form>
  
   
   <div class="col-md-12" id="GetItemListViewEdit"><div>
   
   





 <?php if(!empty($_REQUEST['editKotid'])){?> 
  <script>
window.onload = function() { 
		editKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>,'<?php echo $UniqueCodeGen;?>');
		//editViewKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>);
		};
</script>
 <?php } ?>
  <?php if(!empty($_REQUEST['editKotviewid'])){?> 
   <script>
window.onload = function() { 
		editViewKOT(<?php echo encryptor(decrypt, $_REQUEST['editKotviewid']); ?>,'<?php echo $UniqueCodeGen;?>');
		};
</script>
 <?php } ?>
 <?php if(!empty($_REQUEST['CancelledKOT'])){?> 
   <script>
window.onload = function() { 
		ViewCancelledKOT(<?php echo encryptor(decrypt, $_REQUEST['CancelledKOT']); ?>,'<?php echo $UniqueCodeGen;?>');
		};
</script>
 <?php } ?>
  
               </div>
         	</div> 
              <!-- /.box-body -->	
			<!--  
			<?php// if($purch_row->date_created){?>
					<div class="row">
						<div class="form-group col-md-3" style="margin-top:25px">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($purch_row->date_created));?>">				
		                </div> 
				
						<div class="form-group col-md-3" style="margin-top:25px">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($purch_row->last_modified));?>">				
		                </div> 

		                <div class="form-group col-md-3" style="margin-top:25px">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$purch_row->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div>  
				
						<div class="form-group col-md-3" style="margin-top:25px">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$purch_row->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
				<?php //} ?>   
			  -->
			  
          </div>
          <!-- /.box -->
		  
        </div>
       
      </div>
      <!-- /.row -->
      
        
      		      	<!--cancel pop start-->
		  <div id="cancelpop" class="modal-dialog modal-lg " style="margin:0 15px;display: none;">
          
          
          <div class="modal-dialog modal-lg">
<div class="modal-content">
 <div class="modal-header">
<h4 class="modal-title">Cancel KOT</h4>
<button type="button" class="close" data-dismiss="modal" aria-label="Close">

</button>
</div>
<div class="modal-body">
<div id="showreadyItem"> </div>
<form id="Formkotremarks" autocomplete="off">
		  <input type="hidden" id="pos_purch_id" name="pos_purch_id" value="<?php echo encryptor(decrypt, $_REQUEST['editKotid']); ?>">
		  	<div class="form-group">
		      <label for="title">Reason</label>
		      
		      <textarea rows="2" cols="70" type="text" class="form-control input-sm" placeholder="Enter Reason" id="remark" name="remark" value="" data-parsley-required></textarea>
		    </div>
			<div class="row">
      	<div class="col-md-6">
      		      	<!--cancel pop start-->
		  <div id="cancelpop" class="well p-4" > 
        Are you sure you want to Continue ?
        </div>
        </div></div>
			
			<div class="form-group">
				
                 <div class=" c-box2" style="float:left;">	
				<a class="btn btn-block cancelpop_open o-btn" href="javascript:void(0);" onclick="ajaxCancelKot();" >
				<i class="fa fa-times fa-lg"></i> Continue </a>
                
				</div>
                
				<?php /*?><button class="btn c-btn" onclick="ajaxCancelKot();" type="button"><i class="far fa-save"></i> Continue &nbsp;</button><?php */?>
				<button class="cancelpop_close btn c-btn"><i class="far fa-window-close"></i> Close</button>
			</div>
		  </form>
</div>

</div>

</div>

          
         
          
           
		  
		</div>
		<!--cancel pop ends-->
      
      <!---row ends-->
	  
    </section>
    <!-- /.content -->
     </div>	

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color:#172635; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:12px;">Alteration History</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<div style="text-align:center;font-weight:600;font-size:12px"> KOT No - <?php echo $purch_row->mdoc_no ?> </div>
        <!--<thead>
          <tr>
            <th>Details</th>   
          </tr>
        </thead>-->
        
        <tbody id="roombutton">
          
        </tbody>
      </table>
            </div>
            <div class="modal-footer"  style="background-color: #e4e4e4;color: #fff;text-align:center">
               <button type="button" class="btn c-btn" data-dismiss="modal"> <i class="far fa-window-close"></i> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->
  <div class="modal" id="showKotstatus">
  <div class="modal-dialog">
     <div class="modal-content">
      
        
         
          <div id="resultkotstatus"></div>
       
        
        
         
   </div> 
  </div> 
</div> 
  
<?php include_once("../includes/footer.php");?>  



<script>
function ajaxKOTcancel(id_pos_purch){
	
	$.ajax({
		
		
			   type: "GET",
			   url: 'ajax/ajaxCheckKotCancelItemStatus.php',
			   data: 'id_pos_purch='+id_pos_purch,  
			   success: function (result) {
				   
			
	//$("#cancelled").addClass("bookedby_open");
	$('#cancelpop').popup({
        			transition: 'all 0.3s',
           			 autoopen: true,            
        			});
	$("#pos_purch_id").val(id_pos_purch);
	 $('#showreadyItem').html(result);
	
			   }
	})
					
	}
	
	
$(".discountvalue").keyup(function() {
    var $this = $(this);
    $this.val($this.val().replace(/[^\d.]/g, ''));        
});


</script>


<script type="text/javascript">

  function audittrial(clicked_value){
		//alert(clicked_value);
		$('#auditModal').modal('show');
		var form_name ='KOT';
		var id = document.getElementById("pos_id").value;
		$.ajax({
			url: "../functions/ajaxAuditTrail.php",
			  type: 'POST',
				data: 'form_name='+form_name+'&id='+id,
				dataType: "JSON",
				success: function(data) {
				// alert(data);
			  $('#roombutton').html(data);
			}
	   });
	}
	
	
	
	
	function ajaxUpdateKotEdit(id,UniqueCodeGen){
	
	 bootbox.confirm({
    title: "KOT ",
    message: "Are you sure you want to Continue ?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Continue'
        }
    },
    callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		if(result==true){
			
			
			var form=$("#FormPosKot");	
	var id_pos_purch=$("#id_pos_purch").val();
	
	if(id_pos_purch!='' && id_pos_purch==undefined){
		var purch = form.serialize()+'&id_pos_purch='+id_pos_purch+'&UniqueCodeGen='+UniqueCodeGen;
		var saveType='edit';
		//alert(purch);
	}else{
		var purch = form.serialize()+'&UniqueCodeGen='+UniqueCodeGen;
		var saveType='add';
		
	}
	var id_attribute_steward=$("#id_attribute_steward").val();
	var submenu1=$("#submenu1").val();
	var id_mst_country_lang=$("#id_mst_country_lang").val();
	var id_attribute_shift=$("#id_attribute_shift").val();
	var pax=$("#pax").val();
	var id_attribute_table=$("#id_attribute_table").val();
	if(id_attribute_table==''){
		alert('Please select Table');
		exit;
	}
	if(pax==''){
		alert('Please select Pax');
		exit;
	}
	if(id_attribute_shift==''){
		alert('Please select Shift');
		exit;
	}
	if(id_attribute_steward==''){
		alert('Please select Steward');
		exit;
	}
	if(id_mst_country_lang==''){
		alert('Please select nationality');
		exit;
	}
	$('.loading').show();
	
	
    if(form.parsley().validate()){

		$.ajax({
			type: "POST",
			url: 'ajax/ajaxUpdateKotEdit.php',
			data: purch, 
			success: function (result) {
			       // console.log(result);
			        //alert(result);
			       var data = JSON.parse(result);
					getPreviousOrder(data.purch_id);
					//alert(submenu1);
					alert(data.msg);
					if(saveType=='edit'){
						
						if(submenu1=='179'){
						//	window.location.href="managePosKot.php?doc_type=nc&submenu="+submenu1;
						}else{
							//alert('Print');
//	window.location.href="printKotPreview.php?printPreviewid="+data.printer+"&submenu="+submenu1+"&session=22&setPrint=1";
							//window.location.href="managePosKot.php?submenu="+submenu1;
						}
						
      				
					}else{ //alert(submenu1);
					if(submenu1=='179'){
						window.location.href="manageKotNc.php?submenu="+submenu1;
					}else{
						window.location.href="manageKot.php?submenu="+submenu1;
					}
					}
			}

		});

	}
	}
				 }
				})
}
function checkItemReadyStatus(uniqueCode,remove,id_pos_purch,UniqueCodeGen,id_purch_details){
	$.ajax({
		
		
			   type: "GET",
			   url: 'ajax/ajaxCheckKotItemStatus.php',
			   data: 'remove='+remove+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+uniqueCode+'&listsubgroup=11&id_pos_purch='+id_pos_purch+'&UniqueCodeGen='+UniqueCodeGen+'&id_purch_details='+id_purch_details,  
			   success: function (result) {
				   
				   $("#showKotstatus").modal('show');
				   $('#resultkotstatus').html(result);
				   				  
				  
				  
			   }
	})
	}
function ajaxRemoveKotEditItemList(uniqueCode,remove,id_pos_purch,UniqueCodeGen,id_purch_details){
	

	var submenu1=$("#submenu1").val();

 /*bootbox.confirm({
    title: "KOT ",
    message: "Are you sure you want to Remove this Item ?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Cancel'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Confirm'
        }
    },*/
   // callback: function (result) { //alert(result);
        //console.log('This was logged in the callback: ' + result);
		//if(result==true){
	$.ajax({
		
		
			   type: "GET",
			   url: 'ajax/ajaxRemoveKotItem.php',
			   data: 'remove='+remove+'&uniqueCode='+uniqueCode+'&OrderUniqueID='+uniqueCode+'&listsubgroup=11&id_pos_purch='+id_pos_purch+'&UniqueCodeGen='+UniqueCodeGen+'&id_purch_details='+id_purch_details,  
			   success: function (result) {//alert(result);
			   var response = JSON.parse(result);
				alert(response.msg);
				window.location.href="manageKot.php?submenu="+submenu1;
				 /* 
				  if(response.status=='1'){
				   $('#auditDate').html(response.dated);
				   //$(".targetDivShowRecheckin").hide();				  
				   alert(response.msg);
				  }else if(response.status=='2'){
					  $('#viewincPopUp').popup('show');
					   $('#dayclose_help').html(response.ContentData);
					    $('#daymessage').html(response.msg);
					   
					  }else{
					 // $('#viewincPopUp').popup('show');
					  // $('#dayclose_help').html(response.dated);
					   alert(response.msg);
					  }
				 */}
				
		
		
		})
			//	}
				// }
				//})
	
	
	
	}
</script>

