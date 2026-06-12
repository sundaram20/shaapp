<?php include_once("../../config/auto_loader.php");
?>
<script>
$('#id_kot').select2();
</script>
<?php
	
 $table_group=$_REQUEST['id_attribute_table'];
  
 
if($table_group){
	
if($_REQUEST['po_date']!=''){
	$date =date('Y-m-d',strtotime($_REQUEST['po_date']));
}else{
	$date =date('Y-m-d');
}


$doc_type			 =	'21';//21//$_REQUEST['doc_type'];
$id_subsection		=	$_REQUEST['outlet'];

//print_r($retunDocConfig);
//print_r($_REQUEST);
$posbilling	=	$_REQUEST['id_posbilling'];


$retunDocConfig	   =	docConfigNoValidator($doc_type,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no		= $retunDocConfig['po_no'];
$mdoc_no	= $retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix	    = $retunDocConfig['prefix'];
$suffix	    = $retunDocConfig['suffix'];
$mdoc_no	= $prefix.$po_no.$suffix;


$id_editpos	=	$_REQUEST['id_posbilling'];
if($_REQUEST['id_posbilling']==''){

}else{
$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND id= '".$_REQUEST['id_posbilling']."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']=$ResultupdateRow->id_attribute_table_group;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	$_REQUEST['id_attribute_table']=$ResultupdateRow->id_attribute_table;
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']='2';
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	if($id_editpos!=''){
	$id_attribute_shift=$ResultupdateRow->id_attribute_shift;
	}
	
	
$id_doc_type_configuration	=	$ResultupdateRow->id_doc_type_configuration;
$po_no		=$ResultupdateRow->doc_no;
$mdoc_no	  =$ResultupdateRow->mdoc_no;
}


/*

if($_REQUEST['id_posbilling']==''){
	
$retunDocConfig	   =	docConfigNoValidator($doc_type,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no		= $retunDocConfig['po_no'];
$mdoc_no	= $retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix	    = $retunDocConfig['prefix'];
$suffix	    = $retunDocConfig['suffix'];
$mdoc_no	= $prefix.$po_no.$suffix;

}else{
$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE pos_bill_type= '2' AND id= '".$_REQUEST['id_posbilling']."'");

	$ResultupdateRow = mysqli_fetch_object($updateSql);
	$_REQUEST['id_attribute_table_group']=$ResultupdateRow->id_attribute_table_group;
	$_REQUEST['id_attribute_shift']=$ResultupdateRow->id_attribute_shift;
	$_REQUEST['doc_type_bill']=$ResultupdateRow->doc_type;
	
	$_REQUEST['id_attribute_table']=$ResultupdateRow->id_attribute_table;
	$_REQUEST['id_attribute_steward']=$ResultupdateRow->id_attribute_steward;
	$_REQUEST['doc_type_kot']==$ResultupdateRow->kot_doc_no;
	$_REQUEST['outlet']='2';
	$_REQUEST['id_posbilling']=encryptor(decrypt,$_REQUEST['updateid']);
	
	
$id_doc_type_configuration	=	$ResultupdateRow->id_doc_type_configuration;
$po_no		=$ResultupdateRow->doc_no;
$mdoc_no	  =$ResultupdateRow->mdoc_no;
}


*/

?> 
                   <div class="form-group col-xs-12 col-md-3 col-sm-2">
                             <input type="hidden" name="id_attribute_table" id="id_attribute_table" value="<?php echo $table_group;?>">
                             
                <label> KOT</label>	                

               <select class="form-control select2" name="id_kot[]" data-parsley-required id="id_kot" multiple="multiple" data-parsley-errors-container="#id_kotError" onChange="OrderItemList(this);"> 
                  <?php 
				  if($posbilling!=''){
					  
					   $kot_doc_no	=	 selectColumn(TBL_PURCH,'kot_doc_no'," WHERE id_shop='".$_SESSION['shop']."'   AND `id` = '".$posbilling."'");
					  
					    $CheckBlockedTable_Sql = "SELECT id,id_attribute_table,mdoc_no,id_doc_type_configuration FROM pos_purch WHERE pos_bill_type='1' AND cancelled=0 AND doc_type!='24' AND id in(".$kot_doc_no.") ";
				  }else{
					  $CheckBlockedTable_Sql = "SELECT id,id_attribute_table,mdoc_no,id_doc_type_configuration FROM pos_purch WHERE pos_bill_type='1' AND cancelled=0 AND doc_type!='24' AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) and id_attribute_table in(".$table_group.") ";  
					  }
					  $db->query($CheckBlockedTable_Sql); 
	                  while($ResultBlockedtable1 = $db->fetch_object()){
						   $Kot_id_doc_type_configuration=$ResultBlockedtable1->id_doc_type_configuration;
	                  	$id_attribute_table_name	=	 selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$ResultBlockedtable1->id_attribute_table."'");
													
			echo $KotDropDown = '<option selected value="'.$ResultBlockedtable1->id.'">KOT -'.($ResultBlockedtable1->mdoc_no).'</option>';
												}
											  
							?></select>
                                           
                        </div>
     		<?php
				if($posbilling!=''){
					$CheckBlockedTable_Sql = "SELECT id,id_attribute_table,pax,id_attribute_steward,id_attribute_shift,id_mst_country_lang FROM pos_purch WHERE pos_bill_type='1' AND cancelled=0 AND  id_attribute_table in(".$table_group.") group by id_attribute_table";
					}else{
					$CheckBlockedTable_Sql = "SELECT id,id_attribute_table,pax,id_attribute_steward,id_attribute_shift,id_mst_country_lang FROM pos_purch WHERE pos_bill_type='1' AND cancelled=0 AND id IN (SELECT id_pos_purch as posid FROM pos_purch_details WHERE qty-adj_qty>0) and id_attribute_table in(".$table_group.") and id_doc_type_configuration='".$Kot_id_doc_type_configuration."' group by id_attribute_table";
				
					
					}
	                   $db->query($CheckBlockedTable_Sql); 
					   $ResultBlockedtable1_Pax = '';
					   $id_attribute_steward_name = '';
	 $id_mst_country_lang_list=array();
	                  while($ResultBlockedtable1 = $db->fetch_object()){
						  $ResultBlockedtable1_Pax +=$ResultBlockedtable1->pax;
						  $ResultBlockedtable1_steward =$ResultBlockedtable1->id_attribute_steward;
						  $id_attribute_steward_array = array();
						  $id_attribute_steward_array[]	=	$ResultBlockedtable1->id_attribute_steward;
						  $id_attribute_steward_name = array();
						  $id_attribute_steward_name[]	=	selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE  id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'steward'."' AND `id` = '".$ResultBlockedtable1->id_attribute_steward."'");
						
						$id_attribute_shift_array = array();
						  $id_mst_country_lang_list[]=$ResultBlockedtable1->id_mst_country_lang;
						  $id_attribute_shift_array[] =	$ResultBlockedtable1->id_attribute_shift;
						  if($id_editpos==''){
						$id_attribute_shift=$ResultBlockedtable1->id_attribute_shift;
						}
					  }
					  $id_mst_country_lang=implode(',', array_unique($id_mst_country_lang_list));
					  $steward_name	=	implode(',',$id_attribute_steward_name);
					   $id_attribute_steward	=	implode(',', array_unique($id_attribute_steward_array));
					  $id_attribute_shift_array= array_unique($id_attribute_shift_array);
					    //$id_attribute_shift	=	implode(',',$id_attribute_shift_array);
?>
                


 <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div> <!-- ///onchange="changeFunc()" -->
								
								 <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_steward" id="id_attribute_steward" data-parsley-required style="width:100%"> 
									<option value="">Select Steward</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='steward' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['id_attribute_steward'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($steward_name == $resultCat->field_value){
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
	                  </div>  

				
                <!--    <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
                                <input type="hidden" class="form-control" placeholder="Enter Steward Name" id="id_attribute_steward" name="id_attribute_steward" value="<?php echo $id_attribute_steward;?>"  data-parsley-required>
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="StewardName" name="StewardName" value="<?php echo $steward_name;?>"  data-parsley-required>
							</div>
	                 </div> -->
                                           </div>
                    <div class="row">                        
</div> <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Document Type</label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-book"></i> 
						   	</div>
                  	 		<?php  
										if($doc_type == '21'){
				              	 			$docName= "POS Sales Bill";
				              	 		}elseif($doc_type == '22'){
				              	 			$docName	= "KOT";
				              	 		}elseif($doc_type == '23'){
				              	 			$docName	= "POS sales Bil(nc)";
              	 						}elseif($doc_type == '24'){
				              	 			$docName	= "KOT(nc)";
              	 						}elseif($doc_type == '25'){
				              	 			$docName	= "Laundry";
              	 						}elseif($doc_type == '27'){
				              	 			$docName	= "Laundry(nc)";
              	 						}elseif($doc_type == '26'){
				              	 			$docName	= "Spa and Health Club";
              	 						}elseif($doc_type == '28'){
				              	 			$docName	= "Spa and Health Club(nc)";
              	 						}elseif($doc_type == '29'){
				              	 			$docName	= "Others";
              	 						}else{
											$docName='';
				              	 		}
										
										
			                  	 		/*if($doc_type == '9'){
				              	 			 $docName	= "Physical Stock";
				              	 		}elseif($doc_type == '10'){
				              	 			$docName	= "POS Sales Bill";
				              	 		}elseif($doc_type == '11'){
				              	 			$docName	= "KOT";
				              	 		}elseif($doc_type == '13'){
				              	 			$docName	= "POS sales Bil(nc)";
              	 						}elseif($row->doc_type == '14'){
				              	 			$docName	= "KOT(nc)";
              	 						}elseif($row->doc_type == '25'){
				              	 			$docName	= "Lan";
              	 						}else{

				              	 		}*/
							
							?>
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()">	<option selected="selected" value="<?php echo $doc_type; ?>"><?php echo $docName; ?></option>  
			                  	</select>	 
	              			<?php 
	              				/*$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
								$db->query($sql2);   
									while($row2 = $db->fetch_object()){ 
										$prefix= $row2->prefix; 
										$suffix = $row2->suffix; 
									} */
	              			?></div>
	              			<?php if($row->id !=''){
	              				$readonly = 'disabled';
	              			}else{
	              				$readonly = '';
	              			}
	              			?>
 
	              		</div>  
	              		
                        </div>
                        
		                

<!--<div class="form-group col-xs-12 col-md-5 col-sm-2">-->
		                  <?php if($row->id ==''){?>
	              			<style type="text/css">
	              				 /*#ind{
	              				 	display: none;
	              				 }*/
	              			</style>
	              			<?php } ?>
	              			<div id="ind" name="ind">
                            <input type="hidden" class="form-control" placeholder="Prefix" id="prefix" name="prefix" value="<?php  echo stripslashes($prefix);?>" readonly> 
	              			<input type="hidden" class="form-control" placeholder="PU No" id="po_no" name="po_no" value="<?php echo stripslashes($po_no);?>" readonly>
                            <input type="hidden" class="form-control" placeholder="Suffix" id="suffix" name="suffix" value="<?php  echo stripslashes($suffix);?>" readonly> 
                            <div class=" col-xs-12 col-md-2 col-sm-2">
	              					<label for="name">Bill No</label>
	              					<div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-caret-square-o-left"></i> 
								   	</div>
		              				<input type="text" class="form-control" placeholder="Bill No" id="billno" name="billno" value="<?php  echo $mdoc_no;?>" readonly> 
		              				</div>
			                	</div>
		              			
			                	
			                </div>
			                <?php if($row->id ==''  || $prefix != ''){ ?>
			                  <style type="text/css">
			                  	#hideandshow{
			                  		display: none;
			                  	}
			                  </style>
		              	  	<?php } ?>
		                  	<div id="hideandshow" name="hideandshow">
				                <div class="form-group col-xs-12 col-md-12 col-sm-6">
				                  <label for="name">Manual PU No</label>
				                  <div class="input-group"> 
			              			<div class="input-group-addon">
										<i class="fa fa-list-ol"></i> 
								   	</div>
				                  <input type="text" class="form-control" placeholder="Enter Manual PU No" id="mdoc_no" name="mdoc_no" value="<?php if($_POST) echo $_POST['mdoc_no'];else echo stripslashes($row->mdoc_no); ?>">
				                  </div> 
				                </div> 			                 
				            </div>
<!--</div>-->
  		                     

<input type="hidden" name="id_attribute_shift" id="id_attribute_shift" value="<?php echo $id_attribute_shift;?>" />
<input type="hidden" name="id_mst_country_lang" id="id_mst_country_lang" value="<?php echo $id_mst_country_lang;?>" />
 <?php /*?><input type="text" name="id_attribute_shift" id="id_attribute_shift" value="<?php echo $_REQUEST['id_attribute_shift'];?>" />
 <?php */?>
 
<div class="col-md-2 col-sm-6 col-xs-6">
              <div class="form-group">
                <label for="id_shop">Shift </label>
                <select class="form-control select2" name="id_attribute_shift" id="id_attribute_shift" style="width: 100%">
                  <?php 
          					  $resUserShop = selectSql(TBL_ATTRIBUTES," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' and `table_name` = 'shift'  and  `status` = '1'",' ORDER BY `field_value`');
											  if($db->num_rows2($resUserShop)){
											  	while($resultUserShop = $db->fetch_object2($resUserShop)){
													if($id_attribute_shift == $resultUserShop->id){
														$selected = 'selected="selected"';
													
													}else{
														$selected = '';
													}
													$shopDropDown .= '<option '.$selected.' value="'.$resultUserShop->id.'">'.ucfirst($resultUserShop->field_value).'</option>';
												}
											  }
											 	echo $shopDropDown .= '</select>';
											  ?>
       
        </div>
        </div>
                         <div class="form-group col-xs-12 col-md-2 col-sm-1" >
	              			<label for="name">Pax <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
								
								<?php
								if($ResultupdateRow->pax !=''){
									$paxx = $ResultupdateRow->pax;
								}else{
									
									$paxx =$ResultBlockedtable1_Pax;
								}
								?>
								
								<input type="text" class="form-control" placeholder="Enter Pax" id="noOfPax" name="noOfPax" value="<?php echo $paxx;?>"  data-parsley-required>
								
							</div>


	                  </div>
	                                    
                                           
                         </div>                  
              
        <?php
			}else{?>
            <div class="row">
				  <div class="form-group col-xs-12 col-md-3 col-sm-2">
                        
		                  <label for="name">KOT <font color="#FF0000">*</font></label>
		                  <div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-renren"></i> 
						   	</div>
			                 <input type="text" class="form-control" placeholder="Enter Table No" id="field_value" name="field_value" value="<?php echo $id_attribute_shift_name	= selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' AND table_name ='".'table'."' AND `id` = '".$_POST['id_attribute_table']."'");  ?>"  data-parsley-required>
		              		</div>
                            
		                </div> 
 
		             
<div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div> <!-- ///onchange="changeFunc()" -->
								
								 <?php $categoryDropDown = '<select class="form-control select2" name="id_attribute_steward" id="id_attribute_steward" data-parsley-required style="width:100%"> 
									<option value="">Select Steward</option>';
								  $resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' and table_name ='steward' ",' ORDER BY `field_value`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){					  		
										if($_REQUEST['id_attribute_steward'] == $resultCat->id){
											$selected = 'selected="selected"';
										}elseif($steward_name == $resultCat->field_value){
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
	                  </div>



					 <!-- <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
                                <input type="hidden" class="form-control" placeholder="Enter Steward Name" id="id_attribute_steward" name="id_attribute_steward" value="<?php echo $id_attribute_steward;?>"  data-parsley-required>
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="StewardName" name="StewardName" value="<?php echo $steward_name;?>"  data-parsley-required>
							</div>
	                  </div>  -->

		                <?php /*?><div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Pax <font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Table No" id="field_value" name="field_value" value="<?php echo $_POST['pax'];?>"  data-parsley-required>
							</div>


	                  </div><?php */?>

	                  <?php /*?><div class="form-group col-xs-12 col-md-3 col-sm-2" >
	              			<label for="name">Steward Name<font color="#FF0000">*</font></label>
	              			<div class="input-group"> 
		              			<div class="input-group-addon">
									<i class="fa fa-asterisk"></i> 
							   	</div>
	              				<input type="text" class="form-control" placeholder="Enter Steward Name" id="field_value" name="field_value" value="<?php echo $_POST['id_attribute_steward'];?>"  data-parsley-required>
							</div>
	                  </div><?php */?></div>
				<?php }
?>