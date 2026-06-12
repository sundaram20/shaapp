<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_INDENT,'view');

$image_path = $UPLOAD_FILES.'/hotel_gallery/';

$image_display_path = $UPLOAD_FILES_PATH ."/hotel_gallery/";

 

if(!empty($_REQUEST['eId']) && $_REQUEST['action']=='edit'){

	//Indent Table

	$sql = "  SELECT * FROM `".TBL_INV_PURCH."`
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
     <!-- <h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>-->
      <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Print</li>
      </ol>
    </section>
    <!-- Main content -->
	
	
    <section class="content">
      <div class="row">



<?php
	$list = 'manageStoreIssueNote.php';
	$edit = 'editStoreIssueNote.php';

?>



<?php
if($_REQUEST['eId'] != ''){
	$id=$_REQUEST['eId'];
}

//echo $id;
?>



      	 <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		  <a title="Add" href="<?php echo $edit; ?>?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>&doc_type=5">
		  	<!--editPurch.php?doc_type=<?php echo $doc_type; ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>-->
		    <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
		  </a>
		</div>

	
       <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		
				<a title="Edit" href="<?php echo $edit ?>?eId=<?=encryptor(encrypt, $row->id)?>&submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=0 ">
			

				<div class="btn c-btn" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
			</a>
		</div>	





		<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
			<a title="List" href="<?php echo $list ?>?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
			  <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
			 </a>
		</div>


		
		
     
                  <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		        	
         			 <button title="Print" class="btn c-btn" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print</button> 
         		</div>

			
				<div class="form-group col-xs-12 col-sm-3 col-md-3  ">
				<div class="btn-group " title="Export" style="margin-left:6px;" >&nbsp; <a type="button" class="btn c-btn2" href="javascript:void(0)"><i class="fa fa-fw fa-cloud-download"></i> Export</a>
				    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
				    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
				    <ul class="dropdown-menu " role="menu">
				      <li><a title="Export to excel file" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="../images/excel-icon.jpg" width="20" height="20">&nbsp;Excel</a></li>
				      <li><a title="Export to pdf file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="../images/pdf.jpg" width="20" height="20">&nbsp;Pdf</a></li>
				       <li><a title="Export to JPG file" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="images/jpg.png" width="20px">&nbsp;JPG</a></li>
				    </ul>
				  </div>

				<div class="btn-group s-btt" > <a type="button" title="Share" class="btn c-btn2" href="javascript:void(0)"><i class="fas fa-share"></i> Share</a>
				    <a type="button" class="btn o-btn dropdown-toggle" data-toggle="dropdown" > 
				    <span class="caret"></span> <span class="sr-only">Toggle Dropdown</span> </a>
				    <ul class="dropdown-menu " role="menu">
				      <li><a title="Share on Email" onClick="downloadExcelPdf(2);" href="javascript:void(0)"><img src="images/gmail.png" width="20px">&nbsp;Email</a></li>
				      <li><a title="Share on WhatsApp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="images/whatsapp.png" width="20px">&nbsp;Whatsapp</a></li>
				    </ul>
				  </div>
			   </div>
			<!--end of buttons-->
        <!-- left column -->
        <div class="col-md-7 col-lg-7">
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
				            	$sql22 = "SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' limit 1";
								 $db->query($sql22);  

								while($rowsforms = $db->fetch_object()){ 
									$doc_type = $rowsforms->doc_type;
								}
						?>
				<table class="table  dataTable  no-footer table-responsive out-table" width="100%" border="0" cellspacing="0" cellpadding="10" style="border:0.4px solid #000;    padding-bottom: 200px;">
				      <tr>	
      			      <td     style="border-bottom: 0.4px solid #000;padding:0px!important;">
      					<table id="myTable1" class="table table-striped  dataTable " width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr ></tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        				<p style="font-family: sans-serif;margin:0;padding:5px;"><b>
					        		STORE ISSUE NOTE
				        			</b></p>
				        		</td> 
				        	</tbody>
				        	
				        </table>
				  	 </td>
			    	</tr>
			    	<tr>
		      	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">
  
              	<table  class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10"   >
			         
			          <tbody>
			                <tr>
			                	<?php
			                	 if($image!=''){
			                	 	//echo $image;
			                	 	$dnone='table-cell';
			                	 } else{
			                	 	$dnone='none';
			                	 }
			                	?>
			                    <td style="display:<?=$dnone;?>;display:none;width:20%;border-right:.4px solid #000!important;"> 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>"  width="137px" alt=""> 
			                   </td> 
			                    <td  class="pm" style="width:80%;font-size:12px;font-family: sans-serif;"><center><p style="font-size:18px;font-weight:600;"><?php echo $name; ?></p></center>
			                    	<center><p ><?php echo $address; ?></p></center>
			                    	<center><p><?php echo $city; ?></p></center>
			                    	<center><p ><?php echo $website_url; ?></p></center>
			                    </td> 
			                     
			                </tr>  
			            </tbody>
			    </table>
			    	</td>
			</tr>
			<tr>
		      	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">
  
              	<table  class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10"   >
			         
			          <tbody>
			                <tr>

			                	<?php 
			              				$sql2 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id`='".$row->id_doc_type_configuration."' ";
										$db->query($sql2);   
											while($row2 = $db->fetch_object()){ 
												$prefix= $row2->prefix; 
												$suffix = $row2->suffix; 
											} 
									
										$name_no = 'Store Issue Note No : ';
											
											
											
			              			?>
			                	
			                  
			                    <td class="pm" style="width: 40%;font-family: sans-serif;font-size:12px;">
			                    	
									
									
									
									<p> <b><?php echo $name_no; ?> </b><?php echo stripslashes($prefix).''.stripslashes($row->doc_no).''.stripslashes($suffix); ?> </p>
								
			                    </td>   

			                    <td class="pm" style="width: 30%;font-family: sans-serif;font-size:12px;">
			                    	
									
									
								
									<p><b>Date:</b> <?php echo date('d-M-Y',strtotime($row->doc_date)); ?></p>
								
			                    </td>   

			                    <td class="pm" style="width: 35%;font-family: sans-serif;font-size:12px;">
			                    	
									
									
									
									
									<p><b>Departmetns: </b><?php 
									  	$resCat = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."' and status = '1' AND id='".$row->id_mst_attributes_department."' AND table_name ='".'department'."' ",' ORDER BY `field_value`');
									  if($db->num_rows2($resCat)){
									  	while($resultCat = $db->fetch_object2($resCat)){ 
											echo ucfirst($resultCat->field_value);
										}
									  } 
									?>
									</p>
			                    </td>   
			                </tr>  
			            </tbody>
			    </table>
			    	</td>
			</tr>


	 							
			<tr>
			<td style="padding:0px!important;">

 
		            	<table id="myTable1"  class="table   dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr >
				                    <td  class="pm" style="width:30%;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;" ><p style="font-size:12px;"><b>Item Code / Item Main Group</b></p></td>
				                    <td class="pm" style="width:40%;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:12px;"><b>Item Description</b></p></td> 
				                    <td class="pm" style="width:30%;font-family: sans-serif;border-bottom:.4px solid #000;" ><p style="font-size:12px;"><b>Qty</b></p></td>  
				                     
				                </tr>
				            </thead>
				            <tbody>
				            	<?php 
				            	//Indent Details Here First Row Only Select
				            	$sql2 = "SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";
								 $db->query($sql2); 
								 $countqty = 0;
								 $itemCount=0;
								while($rowsID = $db->fetch_object()){  ?>
				            	 
				                <tr>
				                	<td class="form-group col-xs-12 col-md-3 col-sm-2 pm"  style="border-bottom:.4px solid #000;border-right:.4px solid #000;font-family: sans-serif;"><p style="font-size:12px;"> 
					                 	<?php 
					                 		$main_group = selectColumn(TBL_INV_ITEMS, 'id_mst_attributes_group_main'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 

					                 		echo selectColumn(TBL_INV_ITEMS, 'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 		echo ' | ';
					                 		echo selectColumn(TBL_ATTRIBUTES, 'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$main_group."'");
					                 	?>
					                 </p>
					                </td> 
					                <td class="form-group col-xs-12 col-sm-2 pm"  style="border-bottom:.4px solid #000;border-right:.4px solid #000;font-family: sans-serif;"><p style="font-size:12px;">
				                         <?php 
					                 		echo selectColumn(TBL_INV_ITEMS, 'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 	?>
					                 	<?php if($rowsID->remarks_purch_details!=''){?> <span style="display:block;font-size: 10px;"> remarks : <?php 
					                 		echo $rowsID->remarks_purch_details ; 
					                 	?></span> <?php } ?>
				                   </p>
				                   </td>
					                
				                      
				                    
				                    <td class="pm" style="border-bottom:.4px solid #000;font-family: sans-serif;"><p style="font-size:12px;">
				                    	<?php 
					                 		echo $rowsID->qty.' '.$rowsID->main_unit; 
					                 		$countqty = $countqty + $rowsID->qty; 
					                 		$itemCount++;
					                 	?>
					                 </p>
				                    </td>
				                    
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>
				        	</td>
						</tr>

						<tr>
						<td style="padding:0px!important;">
				      	  <table style="border-bottom: 0.4px solid #000; " id="myTable1" class="table dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr >
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		
				        		<td class="pm" style="width:40%;font-size:12px;font-family: sans-serif;text-align: right;border-right: 0.4px solid #000;"><p>Total Items: </p></td>
				        		<td class="pm" style="width:30%;font-size:12px;font-family: sans-serif;"><p><?php echo $itemCount; ?></p></td>
				        		
				        	</tbody>
				        	
				        </table>
				  
				  </tr>
				 
			</table>
			<br>

			  <table  style=" " id="myTable1" class="table  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr style="font-weight: 800;">
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td  style="width:25%;font-size:12px;font-family: sans-serif;">Prepared By</td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif;">Checked By</td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif;">Received By</td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif;">Authorised By</td>
				        	</tbody>
				        	
				        </table>
		            </div> 
		        <hr>
	

				
				
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