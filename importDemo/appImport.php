<?php
/*

$DB_HOST                        = "ls-b2e60044536f2eec0addbe53dd9287ba11700950.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com"; // Database Host Server
$DB_USERNAME_APP                   = "inroomhu_crsRooms";              // Database Username
$DB_PASSWORD_APP                   = "Kallal9876#";
$DB_NAME_APP                        = "app";
$appConnect = mysqli_connect($DB_HOST,$DB_USERNAME_APP, $DB_PASSWORD_APP, $DB_NAME_APP);
?><label>Database Name</label>
<select class="form-control select2" name="id_shop" style="width:30%">
									<option value="">Select Shop</option>
						<?php 			
									$resCat1 = mysqli_query($appConnect,"SELECT * FROM app_shops ");
									
										while($resultCat1 = mysqli_fetch_object($resCat1)){
											if($_REQUEST['id_shop'] == $resultCat1->id){
												$selected = 'selected="selected"';
											}else{
												$selected = '';
											}
											$categoryDropDown .= '<option '.$selected.' value="'.$resultCat1->id.'">'.$resultCat1->database.'</option>';
										
									}	
									
									
								
								 	echo $categoryDropDown .= '</select>';
						?>
						</div>*/
                        
                       
      

$DB_HOST="ls-b2e60044536f2eec0addbe53dd9287ba11700950.ck2rf8frnqrs.ap-south-1.rds.amazonaws.com:3306";
/*$DB_USERNAME='krkgf';
$DB_PASSWORD='ho20u?0G';//'cz01Vi?5';
echo 'Database Name: '.$DB_NAME='krk-gf';*/

/*$DB_USERNAME='app_tt002';
$DB_PASSWORD='3Ja$75ih';//'cz01Vi?5';
echo 'Database Name: '.$DB_NAME='TT002';*/

$DB_USERNAME='demo';
$DB_PASSWORD='cz01Vi?5';//'cz01Vi?5';
echo 'Database Name: '.$DB_NAME='demo';


/*$DB_USERNAME='krk-ch';
$DB_PASSWORD='aQi05y$3';//'cz01Vi?5';
echo 'Database Name: '.$DB_NAME='krk-ch';*/

$connNew2=mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);
$DOCUMENT_ROOT  = $_SERVER['DOCUMENT_ROOT'];
$LIB_DIR      = "$DOCUMENT_ROOT$MAP_VROOT_PATH/phplib";
//print_r($_REQUEST);

include("$LIB_DIR/imageprocess.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/roomstatus.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");
include("$LIB_DIR/PHPMailer/PHPMailerAutoload.php");
include("$LIB_DIR/admin.pagingClass.php");
include("$LIB_DIR/dompdf/dompdf_config.inc.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel.php");
include("$LIB_DIR/PHPExcel-1.8/Classes/PHPExcel/IOFactory.php");
include("$LIB_DIR/class.mailer.php");
$DB_REPORT_ERROR                = true;                        // To Report Error
$DB_PERSISTENT_CONN             = false; 

$connNew = $connCrs = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);




?>
<!DOCTYPE html>
<html>
<body>
<div style="text-align:center;">
<lable>Item Import</lable><br/><br/>
<form action="" method="post" enctype="multipart/form-data">
    Select csv to upload:
    <input type="file" name="fileToUpload" id="fileToUpload"><br/><br/>
    <input type="checkbox" name="success" value="1" > checked Proced to Insert 
    <br> <br>
    <input type="submit" value="Upload csv" name="submit">
</form>
</div>
</body>
</html>
<?php

if($_REQUEST['submit']	==	'Upload csv'){


	  $target_dir = $_SERVER['DOCUMENT_ROOT']."/importDemo/";

	//$target_dir = "/var/www/vhosts/roomstatushub.in/httpdocs/import/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
	
	$csv_file = $_FILES['fileToUpload']['name'];
	$fieldseparator = ",";
	$lineseparator = "\n";
	$file = fopen($csv_file, "r");
	$count = 1;                                         // add this line
	$sno=1;
	if(!file_exists($csv_file)) {
			echo "File not found. Make sure you specified the correct path.\n";
			exit;
		}		
		$file = fopen($csv_file,"r");		
		if(!$file) {
			echo "Error opening data file.\n";
			exit;
		}		
		$size = filesize($csv_file);		
		if(!$size) {
			echo "File is empty.\n";
			exit;
		}
	$CountInc=1;
	
	 $content .='<table class="table table-striped text-center">';
	
    $content .='<tr>
    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">S No</th>
    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Item Code.</th>
   <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Item Desc.</th>
      <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Item Type.</th>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Main Group</th>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Sub Group</th>
	
	<th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Main Unit</th>
    <th  style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;">Alt. Unit</th>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Purchase Account Local</th>
    <th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Purchase Account Interstate</th>
	<th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Store</th>
	<th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a; ">Rate</th>


	<th   style="vertical-align:central;text-align:center;color:#000;background-color:#c2d69a;width:250px; ">Remarks</th>
   ';
    
 									

  
  
   $content .='</tr>';
	
	
	while (($emapData = fgetcsv($file, 10000, ",")) !== FALSE)
		{    
    	$count++;                                      // add this line
//echo "<pre>";print_r($emapData);
   
    	if($count>2){   

$itemCode=$emapData[0]; 
$ItemName=$emapData[1]; 
$ItemType=$emapData[2]; 

$ItemGroupMain=$emapData[3]; 
$ItemSubGroup=$emapData[4]; 
$ItemMainUnit=$emapData[5]; 
$ItemAltUnit=$emapData[6]; 
$PurchaseAccountLocal=$emapData[7]; 
$PurchaseAccountInterstate =$emapData[8]; 
$Store=$emapData[9]; 
$ConversionQty=$emapData[10]; 
 
				 
				$itemMenuType='17'; // 17 is Ingredients
	if($ItemType!=''){			
	$ItemTypeSql= "SELECT field_value,id FROM ".TBL_ATTRIBUTES." WHERE table_name = 'items_type' AND id_shop = '2' AND  field_value LIKE '%".$ItemType."%'" ;
	$resultItemType = mysqli_query($connNew2,$ItemTypeSql);
	$ItemTypeVerify = mysqli_num_rows($resultItemType);
	$RowTypeVerify = 		mysqli_fetch_object($resultItemType);
	$itemMenuType=$RowTypeVerify->id;//'17';
	$RowTypeVerify->field_value;
			if($ItemTypeVerify!=''){
			//$StausRemark .='Item Type is Alreay Exist'.$ItemType;
			}	 
	}else{
		$StausRemark .='<span><br/> Item Type is:Null</span>';
	}
				
	$ItemNameSql= "SELECT item_code,name FROM inv_items WHERE LOWER(name) = '".strtolower($ItemName)."' and `id_mst_attributes_item_type`=  '".$itemMenuType."' " ;
	$resultItemName = mysqli_query($connNew2,$ItemNameSql);
	$ItemNameVerify = mysqli_num_rows($resultItemName);
	if($ItemNameVerify!=''){
	$StausRemark .='Item Name is Alreay Exist'.$ItemName;
	}

	
	$ItemCodeSql= "SELECT item_code,name FROM inv_items WHERE item_code='".$itemCode."' " ;
	$resultItemCode = mysqli_query($connNew2,$ItemCodeSql);
	$ItemCodeVerify = mysqli_num_rows($resultItemCode);				
	if($ItemCodeVerify!=''){
	$StausRemark .='<br/>Item Code is Alreay Exist'.$itemCode;
	}
	
	//Main Group >
								
		


						
	if($ItemGroupMain!=''){					
	 $GroupMainSql= "SELECT field_value,id FROM ".TBL_ATTRIBUTES." WHERE  id_shop='2' and status = '1' AND table_name ='".'item_group_main'."' AND  field_value LIKE '%".$ItemGroupMain."%'" ;
	$resultGroupMain = mysqli_query($connNew2,$GroupMainSql);	
	$GroupMainVerify = mysqli_num_rows($resultGroupMain);				
			if($GroupMainVerify==''){
			$StausRemark .='<br/>Create Main Group :'.$ItemGroupMain;
			}else{
				$RowGroupMain = 		mysqli_fetch_object($resultGroupMain);
				$id_mst_attributes_group_main=$RowGroupMain->id;
				}		 
	}else{
		$StausRemark .='<span><br/> Sub Group is:Null</span>';
		}
		
		
	if($ItemSubGroup!=''){	
	 $GroupSubSql= "SELECT field_value,id FROM ".TBL_ATTRIBUTES." WHERE  id_shop='2' and status = '1' AND table_name ='".'item_group_sub'."' AND  field_value LIKE '%".$ItemSubGroup."%'" ;
	$resultSubGroupMain = mysqli_query($connNew2,$GroupSubSql);
	$SubGroupVerify = mysqli_num_rows($resultSubGroupMain);				
			if($SubGroupVerify==''){
			$StausRemark .='<span><br/>Create Sub Group :'.$ItemGroupMain.'</span>';
			}else{
				$RowSubGroupMain = 		mysqli_fetch_object($resultSubGroupMain);
				$id_mst_attributes_group_sub=$RowSubGroupMain->id;
				}				 		
							
	}else{
		$StausRemark .='<span><br/> Sub Group is:Null</span>';
		}
									
								  
	if($ItemMainUnit!=''){	
	 $MainUnitSql= "SELECT field_value,id FROM ".TBL_ATTRIBUTES." WHERE  id_shop='2' and status = '1' AND table_name ='".'unit'."' AND  field_value LIKE '%".$ItemMainUnit."%'" ;
	$resultMainUnit = mysqli_query($connNew2,$MainUnitSql);
	$MainUnitVerify = mysqli_num_rows($resultMainUnit);				
			if($MainUnitVerify==''){
			$StausRemark .='<span><br/>Create Main Unit :'.$ItemMainUnit.'</span>';
			}else{
				$RowMainUnit = 		mysqli_fetch_object($resultMainUnit);
				$id_mst_attributes_unit_main=$RowMainUnit->id;
				}				 		
							
	}else{
		$StausRemark .='<span><br/> Main Unit is:Null</span>';
		}							 	
									
  if($ItemAltUnit!=''){	
 $ItemAltSql= "SELECT field_value,id FROM ".TBL_ATTRIBUTES." WHERE  id_shop='2' and status = '1' AND table_name ='".'unit'."' AND  field_value LIKE '%".$ItemAltUnit."%'" ;
	$resultItemAltUnit = mysqli_query($connNew2,$ItemAltSql);
	$ItemAltUnitVerify = mysqli_num_rows($resultItemAltUnit);				
			if($ItemAltUnitVerify==''){
			$StausRemark .='<span><br/>Create Alt Unit :'.$ItemAltUnit.'</span>';
			}else{
				$RowItemAltUnit = 		mysqli_fetch_object($resultItemAltUnit);
				$id_mst_attributes_unit_alt=$RowItemAltUnit->id;
				}				 		
							
	}else{
		$StausRemark .='<span><br/> Alt Unit is:Null</span>';
		}							
		 			
		
	  if($PurchaseAccountLocal!=''){	
 echo $PurchaseAccountLocalSql= "SELECT name FROM ".TBL_CHARGES." WHERE  id_shop='2'  and status = '1'  and charges_account = '2' and transaction_type = '1' AND  name LIKE '%".$PurchaseAccountLocal."%'" ;
	$resultPurchaseAccountLocal = mysqli_query($connNew2,$PurchaseAccountLocalSql);
	$PurchaseAccountLocalVerify = mysqli_num_rows($resultPurchaseAccountLocal);				
			if($PurchaseAccountLocalVerify==''){
			$StausRemark .='<span><br/>Create PurchaseAccountLocal :'.$PurchaseAccountLocal.'</span>';
			}				 		
							
	}else{
		//$StausRemark .='<span><br/> PurchaseAccountLocal is:Null</span>';
		}	
		
 if($PurchaseAccountInterstate!=''){	
 $PurchaseAccountInterstateSql= "SELECT item_code,name FROM ".TBL_CHARGES." WHERE  id_shop='2'  and status = '1'  and charges_account = '2' and transaction_type = '1' AND  name LIKE '%".$PurchaseAccountInterstate."%'" ;
	$resultPurchaseAccountInterstate = mysqli_query($connNew2,$PurchaseAccountInterstateSql);
	$PurchaseAccountInterstateVerify = mysqli_num_rows($resultPurchaseAccountLocal);				
			if($PurchaseAccountInterstateVerify==''){
			$StausRemark .='<span><br/>Create PurchaseAccountInterstate :'.$PurchaseAccountInterstate.'</span>';
			}				 		
							
	}else{
		//$StausRemark .='<span><br/>PurchaseAccountInterstate is:Null</span>';
		}




   $content .='<tr><td style="border:1px solid #000;text-align:center;">'.$sno++.'</td>
    <td style="border:1px solid #000;text-align:center;">'.$itemCode.'</td>
    <td style="border:1px solid #000;text-align:center;">'.$ItemName.'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ItemType.'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ItemGroupMain.'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ItemSubGroup .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ItemMainUnit .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ItemAltUnit .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$PurchaseAccountLocal .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$PurchaseAccountInterstate .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$Store .'</td>
	 <td style="border:1px solid #000;text-align:center;">'.$ConversionQty.'</td>
	
	<td style="border:1px solid #000;text-align:center;color:red;">'.$StausRemark.'</td>';
    
    
    
 $content .='</tr>';
  $StausRemarkCheckFinal .= $StausRemark;
  
	
	
	
		
	if($StausRemark=='' && $_REQUEST['success']==1){				
				$queryCompany = "SELECT item_code,name FROM inv_items WHERE name LIKE '%".$ItemName."%' and item_code='".$itemCode."'" ;
				$resultCompany = mysqli_query($connNew2,$queryCompany);
				$NumberCompany = mysqli_num_rows($resultCompany);
				
				if($NumberCompany=='0'){		
							$addNewCompanyName ="INSERT INTO `inv_items` SET 
											 `item_code`='".$itemCode."',
											 `name`='".$ItemName."',
											 `id_mst_attributes_item_type`='".$itemMenuType."',
											 `id_mst_attributes_group_main`='".$id_mst_attributes_group_main."',
											 `id_mst_attributes_group_sub`='".$id_mst_attributes_group_sub."',
											 `id_mst_attributes_unit_main`='".$id_mst_attributes_unit_main."',
											 `id_mst_attributes_unit_alt`='".$id_mst_attributes_unit_alt."',
											 `id_mst_charges_sales_local`='0',
											 `id_mst_charges_sales_interstate`='0',
											 `id_mst_charges_purchase_local`='0',
											 `id_mst_charges_purchase_interstate`='0',
											 `id_mst_attributes_store`='0',
											 `id_mst_attributes_printer`='".$id_mst_attributes_printer."',
											 `ids_mst_outlet`='".$ids_mst_outlet."',
											 `conversion_qty`='1.000',
											 `min_qty`='0.00',
											 `max_qty`='0.00',
											 `rol`='0.00',
											 `roq`='0.00',
											 `item_class`='A',
											 `bal_qty`='0.00',
											 `open_qty`='0.00',
											 `open_amount`='0.00',
											 `last_purchase_rate`='0.00',
											 `item_enable_desc_billing`='0',
											 `stockable_enable_disable`='0',
											 `edit_name_enable_disable`='0',
											 `item_get_expiry_details`='0',
											 `item_production_item`='0',
											 `item_allow_additional`='0',
											 `item_disable`='0',
											 `sale_rate`='".$rate."',
											 `purchase_rate`='0.00',
											 `batch_details`='0',
											 `item_details`='1',
											 `display_order`='0',
											 `item_image`='',
											 `id_shop`='2',
											 `status`='1',
											 `deactivate_date`='',
											 `date_created`='".currenDateTime()."',
											 `last_modified`='".currenDateTime()."',
											 `id_mst_user_created_by`='10',
											 `id_mst_user_modified_by`='10'";
															 
															 
															
				//echo '<br><br><br>'.$addNewCompanyName;
					  
							
												$InsertSucess	=	 mysqli_query($connNew2,$addNewCompanyName);
												if($InsertSucess==1){
												echo $count.' - Sucessful Record <br>';
												}else{
												echo '<p style="color:red;font-weight:bold;">Error'.$ItemName.'</p><br>';
												}
				
				
					
					
				}else{
				echo '<p style="color:Green;font-weight:bold;">ItemName Already Exist.'.$ItemName.'</p><br>';
				}


	}//End Success

$StausRemark='';
		
    }   
	
	
	
	                                          
}

 
 
  
  if($StausRemarkCheckFinal==''){
	  $error=0;
	  $button='';
	  }
	  else{  $error=1;
	  $button='disabled="disabled"';
		  }
   $content .='</table>';
  		
   
            /*  $content .='<form name="listingForm" action="" method="post" enctype="multipart/form-data"><input type="hidden" value="1" name="success" />
			   <input type="text" value="Upload csv" name="submit" value="'.$_FILES["fileToUpload"]["name"].'">
			   <input type="file" name="fileToUpload" id="fileToUpload" value="">
			   <input type="hidden" value="'.$error.'" name="error" /><input style="background-color:green;color:#fff;" '.$button.' name="Search" type="submit" class="btn btn-primary" value="CLICK HERE To INSERT" />
			   </form>';*/
		
			   
			   
			   
			   
 echo $content ;
echo "Sucessful";

}


?>