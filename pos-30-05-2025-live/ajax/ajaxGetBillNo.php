<?php include_once("../../config/auto_loader.php");
?>

<?php

 $table_group=$_REQUEST['id_attribute_table'];

//die;
//if($table_group){
	
if($_REQUEST['po_date']!=''){
$date =date('Y-m-d',strtotime($_REQUEST['po_date']));
}else{
	$date =date('Y-m-d');
	}
	
/*if($_REQUEST['outlet']=='4'){	
$doc_type='25';
}
if($_REQUEST['outlet']=='5'){	
$doc_type='26';
}
if($_REQUEST['outlet']=='6'){	
$doc_type='27';
}
if($_REQUEST['outlet']=='15'){	
$doc_type='29';
}
if($_REQUEST['outlet']=='16'){	
$doc_type='29';
}  */
 //$doc_type		 =	$_SESSION['id_document'];
$doc_type= $_REQUEST['doc_type'];
 $id_subsection	=	$_REQUEST['outlet'];
 $posbilling	=	$_REQUEST['id_posbilling'];

if($posbilling==''){
	
$retunDocConfig	   =	docConfigNoValidator($doc_type,$date,$id_subsection);	
$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no		=$retunDocConfig['po_no'];
$mdoc_no	  =$retunDocConfig['prefix'].$doc_no.$retunDocConfig['suffix'];	
$prefix	   =$retunDocConfig['prefix'];
$suffix	   =$retunDocConfig['suffix'];
$mdoc_no	= $prefix.$po_no.$suffix;

}else{
	
$updateSql = mysqli_query($connNew,"SELECT * FROM pos_purch WHERE  id= '".$_REQUEST['id_posbilling']."'");

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





/*
$retunDocConfig	=	docConfigNoValidator($doc_type,$date,$id_subsection);

$id_doc_type_configuration	=	$retunDocConfig['id_doc_type_configuration'];
$po_no=$retunDocConfig['po_no'];
$mdoc_no=$retunDocConfig['prefix'].$po_no.$retunDocConfig['suffix'];	
$prefix=$retunDocConfig['prefix'];
$suffix	= $retunDocConfig['suffix'];
*/

?>

                   
                     <div class="form-group col-xs-12 col-md-2 col-sm-2" >
	              			<label for="name">Document Type</label>
	              			<div class="input-group"> 
	              			<div class="input-group-addon">
								<i class="fa fa-book"></i> 
						   	</div>
                  	 		<?php  	
				              	 		if($doc_type == '25'){
				              	 			$docName= "Laundry";
              	 						}elseif($doc_type == '27'){
				              	 			$docName= "Laundry(nc)";
              	 						}elseif($doc_type == '26'){
				              	 			$docName= "Spa and Health Club";
              	 						}elseif($doc_type == '29'){
				              	 			$docName= "Others";
              	 						}else{

				              	 		}
							?>
		              			<select class="form-control select2" id="doc_type" name="doc_type" onchange="hideandshow()">
			                  	 	<option selected="selected" value="<?php echo $doc_type; ?>"><?php echo $docName; ?></option>  
			                  	</select>	 
	              			<?php 
	              			
	              			?></div>
	              			<?php if($row->id !=''){
	              				$readonly = 'disabled';
	              			}else{
	              				$readonly = '';
	              			}
	              			?>
 
	              		</div>  
	              		
                        </div>
                        

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
                         </div>                  
              
       