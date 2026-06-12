<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'view');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

 

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_INDENT."`
			WHERE `id` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."'  AND `id_shop` = '".addslashes($_SESSION['shop'])."'";
	 $db->query($sql);
	
	if($db->num_rows() > 0){
		$row = $db->fetch_object(); 
		
	}  
		  			 
}	
?>
<?php   

	if($_GET['eId'] == ''){
		$id_indent_id =  encryptor(decrypt,$_GET['id_indent_id']);
	}else{
 
		$id_indent_id = encryptor(decrypt,$_GET['id_indent_id']);
		encryptor(decrypt, $_REQUEST['eId']); 
 
	} 
?>

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
	
<?php //echo $_REQUEST['eId'] ?>	
	
<?php $session=$_GET['submenu']; ?>
    <section class="content-header">
      <h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>
      <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Print</li>
      </ol>
    </section>
    <!-- Main content -->
	
	
    <section class="content">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">

		 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
					<div class="form-group has-error" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 

              <div class="box-body" id="printTable">
              	<?php 
      				$sql12 = " SELECT * FROM `".TBL_SHOP."` WHERE `id`='".addslashes($_SESSION['shop'])."' ";
					$db->query($sql12);   
						while($row12 = $db->fetch_object()){ 
							$name= $row12->name; 
							$image = $row12->image; 
							$address = $row12->address; 
							$city = $row12->city; 
							$website_url = $row12->website_url; 
						} 
      			?>
      					<?php 
				            	//Indent Details Here First Row Only Select
				            	$sql22 = "SELECT * FROM  `".TBL_INV_INDENT_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_indent` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' limit 1";
								 $db->query($sql22);  

								while($rowsforms = $db->fetch_object()){ 
									$doc_type = $rowsforms->doc_type;
								}
						?>

      			<table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr style="font-weight: 800;"> </tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        			<h3>
					        			<?php if($doc_type == 1){
					        				echo 'REQUISITION';
					        			}else{
					        				echo "INDENT";
					        			} ?>
				        			</h3>
				        		</td> 
				        	</tbody>
				        	
				        </table><br>

              	<table  class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10"   >
			         
			          <tbody style="border: 1">
			                <tr>
			                    <td style="width: 10%"> 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>"  alt=""> 
			                   </td> 
			                    <td  style="width: 50%"><center><h4><?php echo $name; ?></h4></center>
			                    	<center><h6><?php echo $address; ?></h5></center>
			                    	<center><h5><?php echo $city; ?></h5></center>
			                    	<center><h5><?php echo $website_url; ?></h6></center>
			                    </td> 
			                    <td  style="width: 40%;">
			                    	
									<?php 
			              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
										$db->query($sql2);   
											while($row2 = $db->fetch_object()){ 
												$prefix= $row2->prefix; 
												$suffix = $row2->suffix; 
											} 
									if($_GET['submenu']=='96'){
										$name_no = 'Requestion No : ';
									}else if($_GET['submenu']=='97'){
										$name_no = 'Indent No : ';
									}		
											
											
			              			?>
									
									
									<center><h4> <?php echo $name_no; ?> <?php echo stripslashes($prefix).''.stripslashes($row->indent_no).''.stripslashes($suffix); ?> </h4></center>
									<center><h4>Date: <?php echo date('d-M-Y',strtotime($row->indent_date)); ?></h4></center>
									<center><h4>Departmetns: <?php 
									  	$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id='".$row->id_mst_attributes_department."' AND table_name ='".'department'."' ",' ORDER BY `field_value`');
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->field_value);
										}
									  } 
									?>
									</h4></center>
			                    </td>   
			                </tr>  
			            </tbody>
			    </table><br>

 
		            	<table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="1" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr style="font-weight: 800;">
				                    <td style="width:25%">Item Code / Item Main Group</td>
				                    <td style="width:25%">Item Description</td> 
				                    <td style="width:25%">Qty</td>  
				                     
				                </tr>
				            </thead>
				            <tbody>
				            	<?php 
				            	//Indent Details Here First Row Only Select
				            	$sql2 = "SELECT * FROM  `".TBL_INV_INDENT_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_inv_indent` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 
								 $countqty = 0;
								 $itemCount=0;
								while($rowsID = $db->fetch_object()){  ?>
				            	 
				                <tr>
				                	<td class="form-group col-xs-12 col-md-3 col-sm-2"> 
					                 	<?php 
					                 		$main_group = selectColumn(TBL_INV_ITEMS, 'id_mst_attributes_group_main'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 

					                 		echo selectColumn(TBL_INV_ITEMS, 'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 		echo ' | ';
					                 		echo selectColumn(TBL_ATTRIBUTES, 'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$main_group."'");
					                 	?>

					                </td> 
					                <td class="form-group col-xs-12 col-sm-2">
				                         <?php 
					                 		echo selectColumn(TBL_INV_ITEMS, 'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 	?>
				                    </td>
					                
				                      
				                    
				                    <td>
				                    	<?php 
					                 		echo $rowsID->qty.' '.$rowsID->main_unit; 
					                 		$countqty = $countqty + $rowsID->qty; 
					                 		$itemCount++;
					                 	?>
				                    </td>
				                    
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>
				        <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr style="font-weight: 800;">
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td style="width:25%">Total Items: </td>
				        		<td style="width:25%"><?php echo $itemCount; ?></td>
				        		<td style="width:25%"></td>

				        		<td style="width:25%"></td>
				        	</tbody>
				        	
				        </table>
				        <table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr style="font-weight: 800;">
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td style="width:25%">Prepared By</td>
				        		<td style="width:25%"></td>
				        		<td style="width:25%"></td>
				        		<td style="width:25%">Authorised By</td>
				        	</tbody>
				        	
				        </table>
		            </div> 
		        <hr>
	<?php //echo $_REQUEST['eId'] ?>				
<div class="form-group col-xs-12 col-md-2 col-sm-2">
	 <button class="btn btn-primary btn-block" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"> Print</i></button >
</div>


<?php
if($session=='96'){
	$list = 'manageIndent.php';
	$edit = 'editIndent.php';
}
if($session=='97'){
	$list = 'manageIndentPO.php';
	$edit = 'editIndentPO.php';
}

?>






<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
	  <div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-edit fa-1x"> List</i></div >
	 </a>
</div>

<?php
if($_REQUEST['eId'] != ''){
	$id=$_REQUEST['eId'];
}

//echo $id;
?>


<div class="form-group col-xs-12 col-md-2 col-sm-2">
	<a href="<?php echo $edit ?>?eId=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=1 ">
		<div class="btn btn-primary btn-block" style="margin-right:15px" ><i class="fa fa-file-o fa-1x"> Edit</i></div >
	</a>
</div>

				
				
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

 		
<?php include_once("../includes/footer.php")?>



<script language="javascript">
       
      function printData()
        {
           var divToPrint=document.getElementById("printTable");
           newWin= window.open("");
           newWin.document.write(divToPrint.outerHTML);
           newWin.print();
           newWin.close();
        } 

        $('button').on('click',function(){
        printData();
        });
    </script>