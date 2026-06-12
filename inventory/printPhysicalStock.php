<?php include_once("../config/auto_loader.php");
 include_once("../config/auto_loader.php");
 error_reporting(0);
checkUserLevelPermission($_SESSION['userLevel'],TBL_INV_PURCH,'view');

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
 

<?php include_once("../includes/header.php")?>
<?php include_once("../includes/left.php")?>
<style>
	.p-5{
		padding:5px!important;
	}

	</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <?php $session=$_GET['submenu']; ?>
    <section class="content-header">
     <!-- <h1>
       <?php echo currentNavigation_id($session)['submenu'].' Print'; ?> 
      </h1>-->
      <ol class="breadcrumb">
        <li><a href="javascript:;"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo currentNavigation_id($session)['submenu'].' Print';?></li>
      </ol>
    </section>
    <!-- Main content -->
    <!-- Main content -->
    <section class="content print-con pt-0">
	
	 <div class="row">

      	<!--buttons start-->

				
	
<?php

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
        <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		  <a title="Add" href="<?php echo $edit; ?>?submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session'] ?>&doc_type=<?php echo $doc_type;?>">
		  	<!--editPurch.php?doc_type=<?php echo $doc_type; ?>&session=<?php echo $_GET['session']; ?>&submenu=<?php echo $_GET['submenu']; ?>-->
		    <div class="btn c-btn " style="margin-right:15px" ><i class="fa fa-pencil fa-1x"></i> Add</div >
		  </a>
		</div>

	
       <div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
		
				<a title="Edit" href="<?php echo $edit ?>?eId=<?=encryptor(encrypt, $row->id)?>&submenu=<?php echo $_GET['submenu'] ?>&session=<?php echo $_GET['session']; ?>&action=edit&page=<?=$_REQUEST['page']?>&doc_type=<?php echo $doc_type;?>&print=0 ">
			

				<div class="btn c-btn" style="margin-right:15px" ><i class="fa fa-pencil-square-o fa-1x"></i> Edit</div >
			</a>
		</div>	

		<div class="form-group col-xs-12 col-md-2 col-sm-2 c-box">
			<a title="List" href="<?php echo $list ?>?submenu=<?php echo $_REQUEST['submenu'] ?>&session=<?php echo $_GET['session']; ?> ">
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
<!--end of row-->
			
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
							$base_currency_code = $row12->base_currency_code; 
						} 
      			?>
      			
			<table  class="table  dataTable   no-footer table-responsive out-table" width="100%" border="0" cellspacing="0" cellpadding="10" style="border:0.4px solid #000;">
			      <tr>

      		     	<td style="border-bottom: 0.4px solid #000;padding:0px!important;">
      			<table id="myTable1" class="table  dataTable  table-responsive" width="100%" >
				        	<thead>
				                <tr> </tr>
				            </thead>
				        	<tbody>
				        		<td style="text-align: center;">
				        			<p style="font-size:11px;font-family: sans-serif;margin:0;" class="p-5"><b>
					        	   <?php if($_GET['submenu']=='103'){
										echo 'PHYSICAL STOCK ';
									}	 ?>
				        			</p>
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
			                    <td  class="pm" style="display:<?=$dnone;?>;display:none;width:20%;border-right:.4px solid #000!important;"> 
			                   	<img src="<?php echo $SITE_URL; ?>/uploaded_files/shop/<?php echo $image; ?>"  width="137px" alt=""> 
			                   </td> 
			                    <td  class="pm" style="width:80%;font-family: sans-serif;font-size:11px;">
			                    	<center><p style="font-size:11px;font-weight:600;"><?php echo $name; ?></p></center>
			                    	<center><p><?php echo $address; ?></p></center>
			                    	<center><p><?php echo $city; ?></p></center>
			                    	<center><p><?php echo $website_url; ?></p></center>
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
			              		if($row->supplier_ref_no!=''){
									$widthPer	='width:30%';
									$widthPer2	='width:40%';
								}else{
									$widthPer	='width:50%';}?>

			                  
			                    <td  class="pm" style="<?php echo $widthPer; ?>;font-size:11px;font-family: sans-serif;margin:0;padding:4px!important;padding-left:20px!important;">
			                    	
								
								<p class="p-5" style="padding:0px;margin:0;"><b>PHY No: </b><?php echo stripslashes($prefix).''.stripslashes($row->doc_no).''.stripslashes($suffix); ?> </p>
									
			                    </td>  
			                    <td  class="pm" style="<?php echo $widthPer; ?>;font-size:11px;font-family: sans-serif;padding:5px;margin:0;text-align:center;padding:4px!important;">
			                    

								
								<p  class="p-5" style="padding:0px;margin:0;text-align:center;"><b>Date: </b><?php echo date('d-M-Y',strtotime($row->doc_date)); ?></p>
			              		
								
									
			                    </td>  
                                <?php 
								if($row->supplier_ref_no!=''){?>
                                 <td  class="pm" style="<?php echo $widthPer2; ?>;text-align:right;font-size:11px;font-family: sans-serif;padding:5px;margin:0;padding:4px!important;padding-right:30px!important; ">
			                    

								
								<p class="p-5" style="padding:0px;margin:0;"><b>Supplier Invoice/ref No: </b><?php echo $row->supplier_ref_no; ?></p>
			              		
								
									
			                    </td> 
                                <?php } ?> 
			                </tr>  
			            </tbody>
			    </table> 
			      </td>
			     </tr>


			   <tr>
			   	<td style="padding:0px!important;">
 
			    	 
 					<table id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				            <thead>
				                <tr >
				                    <td class="pm" style="width: 1%;font-size:11px;font-family: sans-serif;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>S.No</b></p></td>
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width:3%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Item Code</b></p></td>
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width:22%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Item Description</b></p></td>
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width:15%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Store</b></p></td>
				                    <td  class="pm" style="font-size:11px;font-family: sans-serif;width: 11%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Stock in Hand</b></p></td>
                                    <td  class="pm" style="font-size:11px;font-family: sans-serif;width: 11%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Rate</b></p></td>  
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width:10%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Actual Stock</b></p></td>
                                    <td class="pm" style="font-size:11px;font-family: sans-serif;width:10%;padding:5px;margin:0;border-right: 0.4px solid #000;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Variance Qty</b></p></td>
				                    <td class="pm" style="font-size:11px;font-family: sans-serif;width: 5%;padding:5px;margin:0;border-bottom: 0.4px solid #000;"><p style="margin:3px;"><b>Variance Value</b></p></td>  
				                      
				                </tr>
				            </thead>
				            <tbody>
				            	<tr>
				            		<p style="text-align: center;padding:5px;font-size:11px;font-family: sans-serif;border-bottom:0.4px solid #000;margin:0;"><b>Physical Stock Details</b></p>
				            	</tr>
				            	<?php
				            	$k='';
				            	if($row->id ==''){
								 	$i=1;
								 }else{
								 	$i=0;
								 } 
				            	//Indent Details Here First Row Only Select 
				            	$sql2 = "  SELECT * FROM  `".TBL_INV_PURCH_DETAILS."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id_inv_purch` = '".addslashes(encryptor(decrypt,$_REQUEST['eId']))."' ";

								 $db->query($sql2); 
								  $itemCount=0;
								while($rowsID = $db->fetch_object()){
							 		 $array['id'.''.$i] = $rowsID->id;
							 		 $array['id_inv_purch'.''.$i] = $rowsID->id_inv_purch;
							 		 $array['id_inv_indent'.''.$i] = $rowsID->id_inv_indent; 
							 		 $array['id_inv_indent_details'.''.$i] = $rowsID->id_inv_indent_details;
							 		 $array['id_inv_items'.''.$i] = $rowsID->id_inv_items; 
							 		 $array['transaction_unit'.''.$i] = $rowsID->transaction_unit; 
							 		 	 $array['id_mst_attributes_store'.''.$i] = $rowsID->id_mst_attributes_store; 
							 		 $array['actual_stock'.''.$i] = $rowsID->actual_stock; 
									 $array['item_amount'.''.$i] = $rowsID->item_amount;
									 $array['rate'.''.$i] = $rowsID->rate_per_main_unit;
									 $array['stock_in_hand'.''.$i] = $rowsID->stock_in_hand;  
							 		 $array['qty'.''.$i] = $rowsID->qty; 
							 		$grn_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='1' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Opening Balance
								$openbal_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='100' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Physical Stock
								// $physicalstock_qty = selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='4' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");
								//Store Issue Note
								$sin_qty= selectColumn(TBL_INV_PURCH_DETAILS,'sum(qty)'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND doc_type ='3' AND id_inv_items ='".$array['id_inv_items'.''.$j]."'");

								$stock_in_hand = $grn_qty + $openbal_qty - $sin_qty;
							 		 $i++;
								}  
								$count = 1;
								for($j=0; $j<$i; $j++){ 
									if($j == 0){
								 		$k='';
									}else{
									 	$k = $j;
									}

								?>
				            	 
				                <tr>
				                	<td class="pm" style="width: 1%;padding:0px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;padding-left:15px!important;"> 
					                 	<?php echo $count++; 
					                 	 ?> 
					                </p></td> 
					                <?php 
			                		//Name Get
			                			$item_code  =  selectColumn(TBL_INV_ITEMS,'item_code'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                			//Item Description Get
			                			$item_description  =  selectColumn(TBL_INV_ITEMS,'name'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_inv_items'.''.$j])."'");
			                		?>
				                    <td class="pm" style="width:10%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo $item_code; ?></p></td>  
				                    <td class="pm" style="width: 15%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo $item_description; ?><br><span style="font-size: 10px;"><?php  if(($array['item_remarks'.''.$j])!=''){ echo stripslashes($array['item_remarks'.''.$j]); } ?></span></td>  


				                    <td class="pm" style="width: 3%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;">

				                    		<?php 	
				                    		  

				                    		 $item_code  =  selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `id` = '".addslashes($array['id_mst_attributes_store'.''.$j])."'"); ?>

				                    	<?php echo $item_code ?></p></td>   
				                    <td class="pm" style="width: 5%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['stock_in_hand'.''.$j]); ;?> </p></td>  
				                    
				                  <td class="pm" style="width: 5%;padding:0px!important;padding-left:5px!important;margin:0;border-right:.4px solid #000;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['rate'.''.$j]); ;?> </p></td>  
				                    
				                       
				                    <td class="pm" style="width: 10%;padding:0px!important;border-right:.4px solid #000;padding-left:5px!important;margin:0;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['actual_stock'.''.$j]); ?></p></td> 
                                      <td class="pm" style="width: 10%;padding:0px!important;border-right:.4px solid #000;padding-left:5px!important;margin:0;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['qty'.''.$j]); ?></p></td>  

				                    	  <td class="pm" style="width: 3%;padding:0px!important;padding-left:5px!important;margin:0;"><p style="font-size:11px;font-family: sans-serif;padding:0!important;margin:0!important;"><?php echo stripslashes($array['item_amount'.''.$j]); ?></p></td>
				                    <?php	$itemCount++;  ?>
				                  
				                </tr> 
				            	<?php } ?> 
				            </tbody> 
				        </table> 
				            </td>
				    </tr>

 <?php
                   
                    	 
                    		if($row->others_charges_net_amount>0){
                    			  $none='table-row';
                    		}else{
							 $none='none';
						} 
					
						?>
				    
				   
				
		      </table>

		       <table   id="myTable1" style="border:0.4px solid #000;border-top:none;" class="table   dataTable no-footer" width="100%"   border="0" cellspacing="0" cellpadding="10" >
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
		    

		      
				        <table   style=" padding-top:200px;padding-bottom:20px;border:0.4px solid #000;border-top:none;" id="myTable1" class="table table-striped  dataTable no-footer" width="100%" border="0" cellspacing="0" cellpadding="10" >
				        	<thead>
				                <tr>
				                    
				                </tr>
				            </thead>
				        	<tbody>
				        		<td  style="width:25%;font-size:11px;font-family: sans-serif;">Prepared By</td>
				        		<td style="width:25%;font-size:11px;font-family: sans-serif;">Checked By</td>
				        		<td style="width:25%;font-size:11px;font-family: sans-serif;">Received By</td>
				        		<td style="width:25%;font-size:11px;font-family: sans-serif;">Authorised By</td>
				        	</tbody>
				        	
				        </table>
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