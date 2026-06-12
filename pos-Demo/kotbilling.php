<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
 
 if($_REQUEST['updateid']=='')
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'add');
else
	checkUserLevelPermission($_SESSION['userLevel'],TBL_PURCH,'edit');

//---------------------------------------------------------------------------------------------------------
 include_once("include/function.php");
// include_once("include/pos_function.php");
unset($_SESSION['LINELEVEL']);
unset($_SESSION['discountamount']);
unset($_SESSION['AdditionalChargeamount']);
unset($_SESSION['purchdetailitemID']);
//kot
//debugData($_REQUEST);
//echo encryptor(decrypt,$_REQUEST['updateid']);
if($_REQUEST['updateid']!=''){
	$posID= encryptor(decrypt,$_REQUEST['updateid']);
	$PaymentStatus	=	checkPaymentStatus($posID);
	if($PaymentStatus!='Pending'){
		$PaymentStatusDisable='disabled';
		$ShowPointStyle='pointer-events:none';
			$StatusOfPaymentis="<section class='content'><div class='timeline-footer'>
                  <a class='btn btn-primary btn-xs'>Bill Status :  ".$PaymentStatus."</a>
                 
                </div></section>";
	}
	
	//}
	$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND id= '".encryptor(decrypt,$_REQUEST['updateid'])."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']='117';//$ResultupdateRow->id_attribute_table_group;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	
	$_REQUEST['id_attribute_table']=$ResultupdateRow->id_attribute_table;
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']=$ResultupdateRow->id_mst_outlet;
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	
	
$id_doc_type_configuration	=	$ResultupdateRow->id_doc_type_configuration;
$po_no		=$ResultupdateRow->doc_no;
$mdoc_no	  =$ResultupdateRow->mdoc_no;	

}else{
		
	$_REQUEST['id_posbilling']='';
}
	
?>
<?php   //echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
/*	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
*/
	$doc_type = $_REQUEST['doc_type_bill'];//13;
	//echo "document type edit ".$doc_type;
	//echo "hello".$_REQUEST['doc_type_bill'];
	//AND `doc_type`='".$doc_type."'	
/*4
	if($doc_type == 10){
		$table_doc_type = "1";
		$redirect_page="manageGRN.php";
	}elseif($doc_type == 5){
		$table_doc_type = "2";
		$redirect_page="managePurch.php";
	}*/
	/*$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$doc_type."'	 ";
								$db->query($sql2);   
										$row2 = $db->fetch_object();
										 $prefix= $row2->prefix; 
										 $suffix = $row2->suffix; */
										
if($doc_type == '10'){
		$add = "POS Sales Bill";		
		/*$table_field = "PO";
		$field = "POS";*/
	}
	if($doc_type == '13'){
		$add = "POS Sales Bill(nc)";		
		/*$table_field = "PO";
		$field = "POS";*/
	}
	/*echo $doc_type;	
	echo $po_date = TBL_DOC_TYPE_CONFIG.'effective_date'." WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";*/							
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">

	
<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="auditModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #172635; color: #fff;text-align: center;">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> 
               <!-- <h4 class="modal-title" id="roomtypeModalLabel">Rooms Select</h4>  -->
                <label class="modal-title" id="roomtitle1" style="font-size:12px;">Alteration History</label>
            </div>
            <div class="modal-body" style="overflow-y: scroll; max-height:100%;height:250px ">
                <table class="table table-bordered table-striped">
				<div style="text-align:center;font-weight:600;font-size:12px"> Bill No - <?php echo $ResultupdateRow->mdoc_no ?> </div>
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
               <button type="button" class="btn c-btn" data-dismiss="modal"><i class="far fa-window-close"></i> Close</button> 
            </div>
     </form>
        </div>
    </div>
</div>
<!-- End Audit trail Modal -->

    <!-- Content Header (Page header) -->
	
	 <?php $session=$_GET['submenu'];
//echo $_REQUEST['id_posbilling'];
	 ?>
    <section class="content-header">
    	<div class="row">
     <div class="col-md-4 col-xs-12"> 
      <!--<h5 style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;">
		<?php echo '<span style="color:'.currentNavigation_id($session)['color'].'">&nbsp;<i class="fa '.currentNavigation_id($session)['icon'].'"></i> '.currentNavigation_id($session)['submenu'].'</span>'; ?>

        <?php //echo currentNavigation()['submenu']; ?>
      </h5>-->
       <h5 class="box-title" style="margin:0px 0px 0px 0px !important;padding:0px 0px 0px 0px !important;"><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> 
					<?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  </h5>
			   </div>
     <div class="col-md-4 col-xs-12 dd-f">	
        <div class="icn-box">
                    <div class="btn-group  "> <a type="button"  title="Add KOT" class="btn n-btn pull-right" href="managePosKot.php?submenu=178" ><i class="fas fa-plus "></i> KOT </a> </div>
                     <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn pull-right" href="manageKot.php?submenu=178&session=22" > <i class="fas fa-list "></i> KOT</a> </div>
                      
                     <div class="btn-group"> <a type="button"  title="List Bill" class="btn n-btn pull-right" href="manageOutletBilling.php?submenu=177&session=21" > <i class="fas fa-list"></i> Bill</a> </div>
                     
  <div class="btn-group"> <a type="button"  title="List KOT" class="btn n-btn pull-right" href="pendingkots.php?submenu=178" > <i class="fas fa-table"></i> KOT</a> </div>        
                
                 </div>
       
     </div> 
     <div class="col-md-4 col-xs-12 tb-br">	
      <?php echo breadCrumbs(); ?>
  </div>
</div>
    </section> 
	
	
  <!--  <section class="content-header">
      <h1>
        Billing Manager
      </h1>
      <ol class="breadcrumb">
        <li><a href="managePO.php"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Manage  Billing</li>
      </ol>
    </section>  -->
    <!-- Main content -->
    <section class="content">
			
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         <?php //print_r($_REQUEST);?>
           
			 <div class="nav-tabs-custom mb-0">
			<!--<div class="box-header with-border">
               <h3 class="box-title"><?php echo $_REQUEST['updateid']==''?'Add':'Edit'?> 
					<?php echo currentNavigation_id($session)['submenu']; ?> : <span style="color:#3c8dbc"> <?php echo selectColumn(TBL_PURCH,'mdoc_no'," WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['updateid']))."' ") ?> </span> 
			  </h3>
            </div>-->
			
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form" action="savePrintBill.php?updateid=<?php echo $_REQUEST['updateid'] ?>&session=<?php echo $_REQUEST['session'] ?>&submenu=<?php echo $_REQUEST['submenu']; ?>"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
                <input type="hidden" value="<?php echo $doc_type;?>" name="doc_type" id="doc_type" />
                <input type="hidden" value="<?php echo $_REQUEST['id_posbilling'];?>" name="id_posbilling" id="id_posbilling" />
				 <input type="hidden" name="revServiceChargeDefault" id="revServiceChargeDefault" value="0" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 
              <div class="box-body">
              	<div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Billing</h5>
              		</div> -->
              		
	              	<div class="row">	
        <div class="form-group col-xs-6 col-md-2 col-sm-6" >
		
 <label for="outlet">Outlet <font color="#FF0000">*</font> </label>
     
               				
 <?php $categoryDropDown = '<select class="form-control select2" name="outlet" id="outlet" data-parsley-required data-parsley-errors-container="#outletError" onChange="loadkotOutlet(this);">
				<option value="">Select Outlet</option>';
			  $resCat = selectSql(mst_outlets," where id_shop='".$_SESSION['shop']."' AND  status = '1' and outlettype='1' ",'');
			  if($db->num_rows2($resCat)){
				while($resultCat = $db->fetch_object2($resCat)){
					if($_REQUEST['outlet'] == $resultCat->id){
						$selected = 'selected="selected"';
					}else{
						$selected = '';
					}
					$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->name).'</option>';
				}
			  }
				echo $categoryDropDown .= '</select>';
 ?>
              <span id="outletError"></span>
              </div>
                     
				 <div class="form-group col-xs-6 col-md-2 col-sm-6" >
	              			<label for="name">Date <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-calendar"></i> 
						   	</div>
                            <?php if($ResultupdateRow->doc_date!=''){?>
		                  <input data-parsley-required type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="po_date" name="po_date" 
                          value="<?php if($_POST) echo $_POST['po_date'];elseif($ResultupdateRow->doc_date!='') echo date('d-m-Y',strtotime($ResultupdateRow->doc_date));else echo date('d-m-Y');?>" onChange="hideandshow2();"  onclick="hideandshow2();" <?php echo $readonly; ?>>
                          <?php }else{
								
				$use_night_audit_date	=	selectColumn('mst_shops','use_night_audit_date'," WHERE `id` = '".$_SESSION['shop']."'");
if($use_night_audit_date=='1'){	 
$sqlNightAudit = mysqli_query($connNew,"SELECT max(night_audit_date) as dated FROM `night_audit` order by id desc limit 1 ");
$numRowsNightAudit =  mysqli_num_rows($sqlNightAudit);
$rowNightAudit =  mysqli_fetch_object($sqlNightAudit);
$rowNightAuditDated = date('d-m-Y',strtotime($rowNightAudit->dated));
$DatedNightAudit = date('d-m-Y',strtotime('+1 day',strtotime($rowNightAudit->dated)));
$DatedNightAudit	= date($DatedNightAudit);
}else{
	
	$DatedNightAudit	= date('d-m-Y');
	}  
											
								
								?>
                          <input data-parsley-required type="text" class="form-control pickerdaterestick" placeholder="Enter PO Date" id="po_date" name="po_date" 
                          value="<?php echo $DatedNightAudit;?>" onChange="hideandshow2();"  onclick="hideandshow2();" <?php echo $readonly; ?>>
						<?php } ?>						  
		                   <input style="display: none;"  type="text" class="form-control pickerdate" placeholder="Enter PO Date" id="po_date1" name="po_date1" value="<?php if($_POST) echo $_POST['po_date'];elseif($ResultupdateRow->doc_date!='') echo date('d-m-Y',strtotime($ResultupdateRow->doc_date));else echo date('d-m-Y');?>" >
		                  </div> 
	              		</div> 
                        		<?php  /*?><div class="col-md-4">
             
	              			<label for="name">Remarks </label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Remarks" id="remarks" name="remarks" value="<?php if($_POST) echo $_POST['field_value'];else echo stripslashes($row->field_value);?>"  >
							</div>
			
                      </div><?php */?> 
                      
                       			                	                
						
                        
           <div class="form-group col-xs-12 col-md-2 col-sm-12" >
                <label>Table No</label>	                
              <select class="form-control select2" name="id_attribute_table[]" data-parsley-required id="id_attribute_table" multiple="multiple" data-parsley-errors-container="#id_attribute_tableError" onChange="loadkotOrder(this);">				  
                  <?php 
				  
				  if($_REQUEST['updateid']==''){
					$CheckBlockedTable_Sql = "SELECT id_attribute_table FROM pos_purch WHERE pos_bill_type='1' and cancelled=0 AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) group by id_attribute_table";
	                   $db->query($CheckBlockedTable_Sql); 
	                  while($ResultBlockedtable1 = $db->fetch_object()){
						  
						 
						  
	                  	$id_attribute_table_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$ResultBlockedtable1->id_attribute_table."'");
							if(isset($_REQUEST['id_attribute_table']))
								if($ResultBlockedtable1->id_attribute_table==$_REQUEST['id_attribute_table']){
									$selected = 'selected="selected"';
								}else{
									$selected = '';
								}
								$hotelDropDown .= '<option '.$selected.' value="'.$ResultBlockedtable1->id_attribute_table.'">'.ucfirst($id_attribute_table_name).'</option>';
												}
								echo $hotelDropDown .= '</select>';
				  }else{
					  
					  
					  $id_attribute_table_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$_REQUEST['id_attribute_table']."'");
													/*if(isset($_REQUEST['id_attribute_table']))
													if($ResultBlockedtable1->id_attribute_table==$_REQUEST['id_attribute_table']){
														$selected = 'selected="selected"';
													}else{
														$selected = '';
													}*/
								$selected = 'selected="selected"';
								$hotelDropDown .= '<option '.$selected.' value="'.$_REQUEST['id_attribute_table'].'">'.ucfirst($id_attribute_table_name).'</option>';
												
								echo $hotelDropDown .= '</select>';
						}
				  
				  ?>
                    <span id="id_attribute_tableError"></span>
              </div>
              
                
	  
<?php
// echo "SELECT id,id_attribute_table FROM pos_purch WHERE pos_bill_type='1'  AND cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)";
 ?>	              		

<div id="ViewKotSelectedTable">
	<div class="form-group col-xs-6 col-md-2 col-sm-6">
	
    <label>KOT</label>	                
    <select class="form-control select2" name="id_kot[]" data-parsley-required id="id_kot" multiple="multiple" data-parsley-errors-container="#id_kotError" >				  
<?php 


 /*$CheckBlockedTable_Sql = "SELECT id,id_attribute_table FROM pos_purch WHERE pos_bill_type='1'  AND cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0)  ";*/
		
	$POSCurrentStartDate = date('d-m-Y',strtotime("-3 day", strtotime(date('d-m-Y'))));
$POSCurrentEndDate 	= 	date('Y-m-d');
 $CheckBlockedTable_Sql ="SELECT id,id_attribute_table,sum(total_qty) as total_qty, sum(total_adj_qty) as total_adj_qty 
 FROM `pos_purch` 
 WHERE `pos_bill_type` = 1 
 and cancelled!=1 
 and (DATE(date_created) BETWEEN '".$POSCurrentStartDate."' and '".$POSCurrentEndDate."' ) 
 and doc_type!='24' 
 and total_qty-total_adj_qty>0 

  GROUP BY id_attribute_table";		
		
		
		
   $db->query($CheckBlockedTable_Sql); 
  while($ResultBlockedtable1 = $db->fetch_object()){
	$id_attribute_table_name	=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$ResultBlockedtable1->id_attribute_table."'");
								
								$KotDropDown .= '<option '.$selected.' value="'.$ResultBlockedtable1->id.'">KOT ='.$id_attribute_table_name.'-'.ucfirst($ResultBlockedtable1->id).'</option>';
							}
						 echo  $KotDropDown .= '</select>';
					   ?>
					   <span id="id_kotError"></span> 
					   </div>
</div>
		              <?php /*?><div class="row">  	
		                <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Pax <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Table No" id="noOfPax" name="noOfPax" value="<?php echo $_POST['pax'];?>" data-parsley-required  data-parsley-errors-container="#noOfPaxError">
							 </div><span id="noOfPaxError"></span>
	                  </div>
	                  <div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
<input type="hidden" class="form-control" placeholder="Enter Steward Name" id="id_attribute_steward" name="id_attribute_steward" value="<?php echo $_POST['id_attribute_steward'];?>"  />
<input type="hidden" name="id_attribute_shift" id="id_attribute_shift" value="<?php echo $_POST['id_attribute_shift'];?>" />
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="StewardName" name="StewardName" value="<?php echo $_POST['attribute_steward_name'];?>"  data-parsley-required data-parsley-errors-container="#StewardNameError">
							</div>
                            <span id="StewardNameError"></span>
	                  </div>
</div><?php */?>
	                     
		                <?php if($row->id !='' && $row->base_currency_code != $row->transaction_currency_code){?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: block;
		                		}
		                		#base_currency{
		                			display: block;
		                		}
		                		#trans_currency{
		                			display: block;
		                		}
		                	</style>
		                <?php  } else{ ?>
		                	<style type="text/css">
		                		#xchange_rate{
		                			display: none;
		                		}
		                		#base_currency{
		                			display: none;
		                		}
		                		#trans_currency{
		                			display: none;
		                		}
		                	</style>
		                <?php } ?>
		                
		                <?php if($row->id !=''){ 
		                	$transaction_currency_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($row->transaction_currency_code)."'");
		                }else{
		                	$transaction_currency_code  = '';
		                }?>
                 
 						<div class="form-group col-xs-6 col-md-6 col-sm-2" style="display: none;">
		                  <label for="name">Id Doc Type</label>
		                  <input type="text" class="form-control" placeholder="Enter Id Doc Type" id="id_doc_type_configuration" name="id_doc_type_configuration" value="<?php if($_POST) echo $_POST['id_doc_type_configuration'];else echo stripslashes($row->id_doc_type_configuration); ?>"> 
		                </div>			                	                
						
		            </div>
		    </div>        
 
		       
              	<div class="card text-dark bg-light">
              		<!--<div class="bg-primary text-center ">
              			<h5 style="padding: 5px;">Item Bill Details</h5>
              		</div>  -->
                     <div id="ViewOrderItemList">
					   <?php
					   //BillingOrderItemList($conn,$_REQUEST['id_attribute_table'],$_SESSION['shop']);
						include_once("ajax/ajaxGetOrderItemList.php");
					   ?>
											
					</div>	
		<!-- Total Amount Section -->
		            
		        </div>     
		        </div>
		      
 
				           
              </div>
         	</div>
              <!-- /.box-body -->	
              
			 <div class="box-footer"> 
<?php //echo $StatusOfPaymentis;?>
			 <?php  if($_REQUEST['updateid']!=''){				 	
				 ?>
                 <input type='submit' style="'.$ShowPointStyle.'"  <?php //echo $PaymentStatusDisable; ?> value='<?=($_REQUEST['eId']==''?'Save':'Edit')?>' class="btn c-btn ml-10" name="Save"  >
                 <?php					
			 }else{	?>
				<input type='submit' value='<?=($_REQUEST['eId']==''?'Save':'Edit')?>' class="btn c-btn ml-10" name="Save"  >
			<?php } ?>
			   <a type='button' value='Close' class="btn c-btn" onclick='location.replace("manageOutletBilling.php?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>"); '> <i class="far fa-window-close"></i> Close
			   </a>
			   <br><br>
			
				<?php if($ResultupdateRow->date_created){?>
					<div class="row">
						<div class="form-group col-md-3">
		                	<label for="date_created">Date Created</label>
		                	<input type="text" disabled="disabled" class="form-control" id="date_created"  value="<?php echo stripslashes(dateformat($ResultupdateRow->date_created));?>">				
		                </div> 

		                <div class="form-group col-md-3">
		                  <label for="last_modified_by">Created By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$ResultupdateRow->id_mst_user_created_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
				
						<!--<div class="form-group col-md-3">
		                  <label for="last_modified">Last Updated</label>
		                  <input type="text" disabled="disabled" class="form-control" id="last_modified" value="<?php echo stripslashes(dateformat($ResultupdateRow->last_modified));?>">				
		                </div>  -->
				
						<div class="form-group col-md-3">
		                  <label for="last_modified_by">Last Updated By</label>
						   <?php $sqlUserDetail = selectColumn(TBL_USERS,'name','where id="'.$ResultupdateRow->id_mst_user_modified_by.'" ');?>
		                  <input type="text" disabled="disabled" class="form-control"  id="last_modified_by"  value="<?php echo stripslashes($sqlUserDetail);?>">				
		                </div> 
					</div> 
					 <a type='button' value='Alteration History' class="btn o-btn"  onclick="audittrial(this.value);" style="float:right">
					 <i class="fas fa-history"></i> Alteration History
					 </a>	
				<?php } ?>

				

			  <?php if($row->id !=''){?>
			   	<center>
		          <a href="editPO.php" type="button" class="btn btn-info"><i class="fa fa-plus-circle" aria-hidden="true"> Another Purchase Order</i></a> 
		          <?php                   		 
	                  $sql1 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$row->doc_type."' and `id`='".$row->id_doc_type_configuration."' limit 1 ";
	                   $db->query($sql1); 
	                   while($row1 = $db->fetch_object()){ 
	                  		$custom_print_file = $row1->custom_print_file;	                  		 
		                  	if($custom_print_file !=''){
		                  		$print = $custom_print_file;
		                  	}else{
		                  		$print = 'printPO.php';
		                  	}
	                  	} 
	                  		                  	
                  	?>
		          <a href="<?php echo $print; ?>?eId='<?php echo $_GET['eId']; ?>'&action=edit&page=<?php $_REQUEST['page']?>" target="_blank" type="button" class="btn btn-primary"><i class="fa fa-print" aria-hidden="true"> Print</i></a>    
				  
	        	</center>
			   <?php } ?> 
			 </div>
            </form>			
          </div>
          <!-- /.box -->
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div> 		
<?php include_once("../includes/footer.php");?> 


<script>
<?php if($_REQUEST['updateid']!= ''){ ?>
	$('document').ready(function(){
		$('#po_date').click();
		
	});
<?php } ?>	
</script>


<script type="text/javascript">
	function audittrial(clicked_value){

		$('#auditModal').modal('show');
		var form_name ='POS';
		//var form_name ='POS';
		var id = document.getElementById("id_posbilling").value;
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
	
</script>

 
<script type="text/javascript">

	function calculateDiscountSingleItem(lineUniqueCode,type,discount){
		//alert("#discount|"+lineUniqueCode);
		var revServiceCharge = $("#revServiceCharge").val();
		var opts = $("#id_attribute_table").val();
		var outlet = $("#outlet").val();
		var id_kot = $("#id_kot").val();	
		var id_posbilling = $("#id_posbilling").val();
		var id_mst_charges_discounts=  $("#id_mst_charges_discounts").val();
		var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
		$.ajax({
			type: "POST",
			url: 'ajax/ajaxGetOrderItemList.php',
			data: 'id_attribute_table='+opts+'&DbConnect=1&discount='+discount+'&UniqueCode='+lineUniqueCode+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot+'&id_mst_charges_discounts='+id_mst_charges_discounts+'&revServiceChargeDefault='+revServiceChargeDefault, 
			success: function (result) {
			   $( "#ViewOrderItemList" ).html(result);	
		 	}
		});
	
	}

function checksessionNotapplicable(value,id,setvalue){ 
		//alert("#discount|"+lineUniqueCode);
		 
		//alert(setvalue);
		$.ajax({
			type: "POST",
			url: 'ajax/ajaxSetSessionDiscount.php',
			data: 'value='+value, 
			success: function (result) {
			  $( "#total_discount_amount" ).val('0');
			  calculateDiscountLedger(id);
		 	}
		});

		//}
	
	}	
	function calculateDiscountLedger(lineUniqueCode,type,discount){ //alert('Discount Ledger');
		//alert("#discount|"+lineUniqueCode);
		var revServiceCharge = $("#revServiceCharge").val();
		var opts = $("#id_attribute_table").val();
		var outlet = $("#outlet").val();
		var id_kot = $("#id_kot").val();	
		var id_posbilling = $("#id_posbilling").val();
		var id_mst_charges_discounts=  $("#id_mst_charges_discounts").val();
		var total_discount_amount=  $("#total_discount_amount").val();
		var sub_total_items=  $("#sub_total_items").val();
		var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
		//alert(total_discount_amount);
		$.ajax({
			type: "POST",
			url: 'ajax/ajaxGetOrderItemList.php',
			data: 'id_attribute_table='+opts+'&DbConnect=1&discount='+discount+'&UniqueCode='+lineUniqueCode+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot+'&id_mst_charges_discounts='+id_mst_charges_discounts+'&total_discount_amount='+total_discount_amount+'&sub_total_items='+sub_total_items+'&revServiceChargeDefault='+revServiceChargeDefault, 
			success: function (result) {
			   $( "#ViewOrderItemList" ).html(result);	
		 	}
		});
	
	}
</script>

<!--
 <script>
<?php // if($ResultupdateRow->sc_reverse =='1'){ ?>
	$('document').ready(function(){
		//alert();
		$('#revServiceCharge').click();
	});
	
<?php //} ?>
</script>
-->


<script type="text/javascript">

 function reverceServiceCharge(){
	var revServiceCharge = $("#revServiceCharge").val();
	$( "#revServiceChargeDefault" ).val('1');
    if (revServiceCharge==0) {
		// alert("Check box in Checked"); 
		$("#revServiceCharge").val('1');
		loadkotOutlet();
    }else { 
					
        // alert("Check box is Unchecked"); 
		$("#revServiceCharge").val('0');
		loadkotOutlet();
						
						
    } 
 }
 </script>
 
 


<script type="text/javascript">
/*function reverceServiceCharge(){
	var revServiceCharge = $("#revServiceCharge").val();
	 $("#revServiceCharge").click(function() { 
                    if ($("input[type=checkbox]").prop( 
                      ":checked")) { 
                        alert("Check box in Checked"); 
                    } else { 
                        alert("Check box is Unchecked"); 
                    } 
                }); 
	alert(revServiceCharge);}*/
function loadkotOutlet(){
	//alert("hello");
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	var id_posbilling = $("#id_posbilling").val();
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();	
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling, 
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);				
				OrderItemList(id_attribute_table);
				//alert(id_attribute_table);
	 	}
	});
}
</script>


<script type="text/javascript">
function loadkotOrder(sel){
	//alert(sel);
	var revServiceCharge = $("#revServiceCharge").val();
	
	var id_kot = $("#id_kot").val();
	//alert(id_kot);
	var outlet = $("#outlet").val();	
	var doc_type = $("#doc_type").val();
	var id_posbilling = $("#id_posbilling").val();
	var po_date = $("#po_date").val();
	var opts = [],opt;	
	var len = sel.options.length;
	//alert(len);
	
	
	
	for (var i = 0; i < len; i++) {
		opt = sel.options[i];	
		if (opt.selected) {
			opts.push(opt.value);
		}
	}
	//alert(opts);

	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		data: 'id_attribute_table='+opts+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot, 
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);		
				OrderItemList(opts);
	 	}
	});
}
</script>


<script type="text/javascript">
function OrderItemList(opts){
//alert(opts);
	var revServiceCharge = $("#revServiceCharge").val();
	var outlet = $("#outlet").val();	
	var id_kot = $("#id_kot").val();
	//alert(revServiceCharge);
	var id_posbilling = $("#id_posbilling").val();
	var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot+'&revServiceChargeDefault='+revServiceChargeDefault,
		success: function (result) {
			$( "#ViewOrderItemList" ).html(result);
			//$( "#revServiceCharge" ).val(revServiceCharge);
		}
	});
}
</script>


<script type="text/javascript">

function additionalDiscount(type,discountamount){
	
	var revServiceCharge = $("#revServiceCharge").val();
	var id_posbilling = $("#id_posbilling").val();	
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();	
	var id_kot = $("#id_kot").val();
	var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
	//alert(type+'==='+revServiceCharge);
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&discountType='+type+'&discountamount='+discountamount+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_posbilling='+id_posbilling+'&id_kot='+id_kot+'&revServiceChargeDefault='+revServiceChargeDefault,
		success: function (result) {
			//alert(result);
			//resulthtml = result.split('EXPLODE');
			//alert(resulthtml[0]);			
				$( "#ViewOrderItemList" ).html(result);
				//$( "#ViewPreviousOrder" ).html(result);
	 	}
	});
}
</script>


<script type="text/javascript">
	
function hideandshow2() {
		
	var revServiceCharge = $("#revServiceCharge").val();
	var id_attribute_table = $("#id_attribute_table").val();
	var id_posbilling = $("#id_posbilling").val();
	var doc_type = $("#doc_type").val();	
	var outlet = $("#outlet").val();	
	var po_date = $("#po_date").val();
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetBillingKot.php',
		data: 'id_attribute_table='+id_attribute_table+'&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&doc_type='+doc_type+'&po_date='+po_date+'&id_posbilling='+id_posbilling,
		success: function (result) {
				$( "#ViewKotSelectedTable" ).html(result);
				$( "#revServiceCharge" ).val(revServiceCharge);				
				OrderItemList(id_attribute_table);
	 	}
	});
			
	/*$.ajax({
		type: "POST",
		url: "ajax/GRNPosManage.php",
		data:{doc_type:'doc_type'},
		success: function(data){
			alert(data);
		}
	});*/
		
}
</script>


<script type="text/javascript">
$(document).ready(function() {
	
 	<?php 

		if($doc_type != ''){
	   $po_date = selectColumn(TBL_DOC_TYPE_CONFIG,'effective_date'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' order by effective_date DESC LIMIT 0,1 ");
		echo $po_date = date('d-m-Y' , strtotime(addslashes($po_date))); 
		}	 
				
	?>
		
	var dates = '<?php echo ($po_date!=''?date("d-m-Y",strtotime($po_date)):date("d-m-Y")); ?>';
		//document.getElementById("po_date").value = dates; 
	document.getElementById('po_date').click();  
	$('.dates').datepicker({ dateFormat: "dd-mm-yy" , minDate: dates });
	//Button hide */
	
		 
});
</script>


<script type="text/javascript">
	
function fillup(fillupfrom,filluptill,fillupid){
	
	var fillupVal = $("input[name='discount|"+fillupid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
	//alert(id_posbilling);
	var data2 = '&fillupfrom='+fillupfrom+'&filluptill='+filluptill+'&fillupid='+fillupid+'&fillupVal='+fillupVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&revServiceChargeDefault='+revServiceChargeDefault+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
				
//		lineUniqueCode,type,discount	
				
	 	}
	});
	
}
</script>


<script type="text/javascript">
function filldown(filldownfrom,filldowntill,filldownid){
	
	var filldownVal = $("input[name='discount|"+filldownid+"']").val();
			
	var revServiceCharge = $("#revServiceCharge").val();
	var opts = $("#id_attribute_table").val();
	var outlet = $("#outlet").val();
	var id_kot = $("#id_kot").val();	
	var id_posbilling = $("#id_posbilling").val();
	var revServiceChargeDefault	=$("#revServiceChargeDefault").val();
	//alert(id_posbilling);
	var data2 = '&filldownfrom='+filldownfrom+'&filldowntill='+filldowntill+'&filldownid='+filldownid+'&filldownVal='+filldownVal;
	$.ajax({
		type: "POST",
		url: 'ajax/ajaxGetOrderItemList.php',
		data: 'id_attribute_table='+opts+'&revServiceChargeDefault='+revServiceChargeDefault+'&DbConnect=1&outlet='+outlet+'&revServiceCharge='+revServiceCharge+'&id_kot='+id_kot+'&id_posbilling='+id_posbilling+data2, 
		success: function (result) {
				$( "#ViewOrderItemList" ).html(result);
		
	 	}
	});
	
}
</script> 

<script>

$('#discount|'+<?php echo $_REQUEST['UniqueCode']; ?>).focus();

</script>
<script type="text/javascript">

document.getElementById('string').focus();

</script>
<style>
.discount|<?php echo $_REQUEST['UniqueCode'];
?> {
 autofocus:"autofocus";
}
</style>

<script>
	<?php //if($_GET['reload'] != '1'){ ?>
	/* $(document).ready(function (){  
		location.href = "kotbilling.php?submenu="+<?php echo $_GET['submenu']; ?>+"&session="+<?php echo $_GET['session']; ?>+"&reload=1";
	}); */
	<?php //} ?>
 </script>