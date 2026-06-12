<?php include_once("../config/auto_loader.php");
     // include_once("../config/auto_loader.php");
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
      <!--<h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>-->
      <ol class="breadcrumb print-bread">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"> Print</li>
      </ol>
    </section>
    <!-- Main content -->
	
<?php
if($session=='96'){
	$list = 'manageIndent.php';
	$edit = 'editIndent.php';
	$NewKot='editIndent.php';
}
if($session=='97'){
	$list = 'manageIndentPO.php';
	$edit = 'editIndentPO.php';
	$NewKot='editIndentPO.php';
}
if($session=='103'){
	$list = 'managePhysicalStock.php';
	$edit = 'editPhysicalStock.php';
	$NewKot= 'editPhysicalStock.php';

}

?>	
<?php
if($_REQUEST['eId'] != ''){
	$id=$_REQUEST['eId'];
}

//echo $id;
?>
    <section class="content  print-con pt-0">

      <div class="row">

      	<!--buttons start-->
        <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		  <a title="Add" href="<?php echo $NewKot; ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session'] ?> ">
		    <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
		  </a>
		</div>

		<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	<a title="Edit" href="<?php echo $edit ?>?eId=<?php echo $id ?>&submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&print=1 ">
		<div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
	</a>
</div>


<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	<a title="List" href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
	  <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-list fa-1x"></i> List</div >
	 </a>
</div>

      	<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
	      <button title="Print" class="btn c-btn" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print</button >
       </div>




<div class="form-group col-xs-12 col-sm-4 col-md-3  ">
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
      <li><a title="Share on WhatsApp" onClick="downloadExcelPdf(3);" href="javascript:void(0)"><img src="images/whatsapp.png" width="20px">Whatsapp</a></li>
    </ul>
  </div>
</div>
   </div>
<!--end of row-->
<div class="container-fluid">
   <hr class="br-line">
</div>


<!--second row start-->
   <div class="row">
      
        <!-- left column -->
        <div class="col-md-7 col-lg-7">
          <!-- general form elements -->
         
           
			 <div class="nav-tabs-custom">

		 
            <!-- /.box-header -->
            <!-- form start -->  			        
			 <form name="indent_form"  method="post" enctype="multipart/form-data" data-parsley-validate autocomplete="off" id="indent_form">
                <input type="hidden" value="<?php echo $_REQUEST['eId'];?>" name="eId" id="eId" />
					<div class="form-group has-error mb-0" align="center">
						<?php if($_SESSION['errorMsg']){?>
						 <p class="help-block"><?php echo messageError($_SESSION['errorMsg']);?></p>
						<?php unset($_SESSION['errorMsg']);}elseif($_SESSION['successMsg']){?>
					 	<p class="help-block"><?php echo messageSuc($_SESSION['successMsg']);?></p>
						<?php unset($_SESSION['successMsg']);}?>
					 </div> 

              <div class="box-body" id="printTable" >
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
				<table class="table  dataTable  no-footer table-responsive out-table" width="100%" border="0" cellspacing="0" cellpadding="10" style="border:0.4px solid #000;">
				<tr>	
      			<td     style="border-bottom: 0.4px solid #000;padding:0px!important;">
      				<table  id="myTable1"   class="table   dataTable table-responsive" width="100%" border="0" cellspacing="0" cellpadding="10"  >
				        	<thead>
				                <tr > </tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        			<p style="font-family: sans-serif;margin:0;padding:5px;"><b>
					        			<?php 	if($_GET['submenu']=='96'){
										echo 'REQUISITION NOTE ';
									}else if($_GET['submenu']=='97'){
										echo 'INDENT';
									}else if($_GET['submenu']=='103'){
										echo 'PHYSICAL STOCK ';
									}			
										 ?>
				        			</b></p>
				        		</td> 
				        	</tbody>
				        	
				        </table>
				 </td>
				</tr>
				<tr>
		   	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">

              	  <table  class="table table-striped  dataTable no-footer" width="100%" border="0"  cellspacing="0" cellpadding="10" >
			         
			          <tbody  >
			                <tr >
			                	<?php
			                	 if($image!=''){
			                	 	//echo $image;
			                	 	$dnone='table-cell';
			                	 } else{
			                	 	$dnone='none';
			                	 }
			                	?>
			                    <td style="display:<?=$dnone;?>;display:none;width:20%;border-right:.4px solid #000!important;" > 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>" width="137px"  alt=""> 
			                   </td> 
			                    <td class="pm" style="width:80%;font-size:12px;font-family: sans-serif;"><center><p style="font-size:18px;font-weight:600;"><?php echo $name; ?></p></center>
			                    	<center><p ><?php echo $address; ?></p></center>
			                    	<center><p><?php echo $city; ?></p></center>
			                    	<center><p><?php echo $website_url; ?></p></center>
			                    </td> 
			                   
			                </tr>  
			            </tbody>
			    </table>
			</td>
		</tr>


  	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">

              	  <table  class="table table-striped  dataTable no-footer" width="100%" border="0"  cellspacing="0" cellpadding="10" >
			         
			          <tbody  >
			                <tr >
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

			                			    <td class="pm" style="width: 30%;font-family: sans-serif;font-size:12px;">
			                    	
					
							     <p> <b><?php echo $name_no; ?></b> <?php echo stripslashes($prefix).''.stripslashes($row->doc_no).''.stripslashes($suffix); ?> </p>
									
			                    </td>   
			                      <td class="pm" style="width: 40%;font-family: sans-serif;font-size:12px;">
			                    	
					
								
									<p><b>Date: </b><?php echo date('d-M-Y',strtotime($row->doc_date)); ?></p>
								
			                    </td>   
			                      <td class="pm" style="width: 30%;font-family: sans-serif;font-size:12px;">
			                    	
					
									
									<p ><b>Department : </b><?php 
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
		            	<table   id="myTable1" class="table  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr >
				                    <td  class="pm" style="width:30%;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;" ><p style="font-size:12px;"><b>Item Code / Item Main Group</b></p></td>
				                    <td class="pm" style="width:40%;font-family: sans-serif;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:12px;"><b>Item Description</b></p></td> 
				                    <td  class="pm" style="width:30%;font-family: sans-serif;border-bottom:.4px solid #000;" ><p style="font-size:12px;"><b>Qty</b></p></td>  
				                     
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
				                	<td class="pm"  style="padding:0px!important;margin:0;border-right:.4px solid #000;font-family: sans-serif;"><p style="font-size:11px;font-family: sans-serif;padding:5px!important;padding-left:15px!important;margin:0!important;border-bottom:.4px solid #000;">
					                 	<?php 
					                 		$main_group = selectColumn(TBL_INV_ITEMS, 'id_mst_attributes_group_main'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 

					                 		echo selectColumn(TBL_INV_ITEMS, 'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 		echo ' | ';
					                 		echo selectColumn(TBL_ATTRIBUTES, 'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$main_group."'");
					                 	?>

					              </p>  </td> 
					                <td   class="pm" style="padding-top:4px!important;width:10%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;border-bottom:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:5px!important;margin:0!important;">
				                         <?php 
					                 		echo selectColumn(TBL_INV_ITEMS, 'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$rowsID->id_inv_items."'"); 
					                 	?>  <?php if($rowsID->remarks_indent_details!=''){?> <span style="display:block;font-size: 10px;"> remarks : <?php 
					                 		echo $rowsID->remarks_indent_details ; 
					                 	?></span> <?php } ?></p>
				                    </td>
					                
				                      
				                    
				                    <td class="pm" style="border-bottom:.4px solid #000;padding-top:4px!important;width: 20%;padding:0px!important;padding-left:5px!important;margin:0;"><p style="font-size:11px;font-family: sans-serif;padding:5px!important;margin:0!important;">
				                    	<?php 
					                 		echo $rowsID->qty.' '.$rowsID->main_unit; 
					                 		$countqty = $countqty + $rowsID->qty; 
					                 		$itemCount++;
					                 	?></p>
				                    </td>
				                    
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table>
				        	</td>
						</tr>

						

						<tr>
						<td style="padding:0px!important;border-bottom: 0.4px solid #000;">
				        <table   id="myTable1" class="table   dataTable no-footer" width="100%"   border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr >
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td style="width:30%;"></td>
				        		<td class="pm" style="width:40%;font-size:12px;font-family: sans-serif;text-align: right;border-right: 0.4px solid #000;"><p>Total Items: </p></td>
				        		<td class="pm" style="width:30%;font-size:12px;font-family: sans-serif;" ><p><?php echo $itemCount; ?></p></td>


				        		
				        	</tbody>
				        	
				        </table>
				    </td>
				  </tr>
				  <tr>
				  	<td>
				        <table  style=" padding-top:20px;padding-bottom:20px;border-color:black;" id="myTable1" class="table   dataTable" width="100%" border="0" cellspacing="0" cellpadding="10"  >
				        	<thead>
				                <tr>
				                    
				                </tr>
				            </thead>

				        	<tbody >
				        		<td style="width:25%;font-size:12px;font-family: sans-serif; " >Prepared By</td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif;"></td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif; "></td>
				        		<td style="width:25%;font-size:12px;font-family: sans-serif;">Authorised By</td>
				        	</tbody>
				        	
				        </table>
				    </td>
				 </tr>
				    </table>
		            </div> 
		        <hr>
	<?php //echo $_REQUEST['eId'] ?>				
				
				
         	</div> 
            </form>			
          </div>
          <!-- /.box -->
        </div>
        <!--end of col-md -->
          <div class="col-md-3 col-lg-2 order-sm-first">
        <div class="rightbtn">
        <?php /*?>  <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Select Printer</button >
          </div> 
          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          <button class="btn c-btn btn-block" style="margin-right:15px" onClick="printdiv('div_print');"><i class="fa fa-print fa-1x"></i> Print Copies</button >
          </div> <?php */?>
          <?php if($row->payment_status!='Settled'){?>
          <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
          
          <?php /*?><a  class="btn c-btn btn-block showSingle" target="<?php echo $pos_purch_id;?>" style="cursor:pointer;">Settle</a><?php */?>
          </div> 
          <?php } ?>
        </div>
    </div>
      <!--end of column-->
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