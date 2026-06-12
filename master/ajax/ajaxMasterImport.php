<?php
include_once("../../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

$tableName =  $_POST['table_name'];

if(!empty($_FILES["companyImport"]["tmp_name"])){		
		
		$filename=$_FILES["companyImport"]["tmp_name"];		
  
		if($tableName == 'mst_company'){

			if($_FILES["companyImport"]["size"] > 0){

				$file = fopen($filename, "r");
		  		$x=0;
		  		$insertCount=0;
		  		$duplicateCount=0;
		  		$errorCount = 0;
		  		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
		  			if($x==0){
		         		//just to skip the first row
		         	}
	            	else{
	            		//Setting Values to insert

		            	$id_shop_group = 1;
		            	$id_shop = $_SESSION['shop'];
		            	$id_lang =1;
		            	$err = 0;

		            	$companyName1 = htmlentities(str_replace('�','',trim($getData[1])));

		            	$email1 =htmlentities(str_replace('�','',trim($getData[2]))) ;

		            	$query = "SELECT * FROM `".mst_company."` WHERE name = '".$companyName1."' AND email = '".$email1."'";
		            	$result = @mysqli_query($conn, $query);

		            	if(mysqli_num_rows($result)>0){

		            		$duplicateRecords .=  ($duplicateCount + 1).") ".$companyName1."  \n";
		            		$duplicateCount++;
		            		
		            	}else{

		            		$companyName = htmlentities(str_replace('�','',trim($getData[1])));
		            		$email =htmlentities(str_replace('�','',trim($getData[2]))) ;


			            	$secondaryEmail = htmlentities(str_replace('�','',trim($getData[3])));
			            	$otherCountry = htmlentities(str_replace('�','',trim($getData[5])));
			            	$otherState = htmlentities(str_replace('�','',trim($getData[7])));
			            	$postCode = htmlentities(str_replace('�','',trim($getData[8])));
			            	$city = htmlentities(str_replace('�','',trim($getData[9])));
			            	$address = htmlentities(str_replace('�','',trim($getData[10])));
			            	$primary_contact =  trim($getData[11]);

			            	if($primary_contact == "Mobile" || $primary_contact == "mobile"){
			            		$primary_contact_type = 1;
			            	}else if($primary_contact == "Landline" || $primary_contact == "landline"){
			            		$primary_contact_type = 2;
			            	}

			            	$primary_mobile = htmlentities(str_replace('�','',trim($getData[12])));
			            	$primary_landline = htmlentities(str_replace('�','',trim($getData[13])));

			            	$secondary_contact =  trim($getData[14]);

			            	if($secondary_contact == "Mobile" || $secondary_contact == "mobile"){
			            		$secondary_contact_type = 2;
			            		
			            	}else if($secondary_contact == "Landline" || $secondary_contact == "landline"){
			            		$secondary_contact_type = 1;
			            	}

			            	$secondary_mobile = htmlentities(str_replace('�','',trim($getData[15])));
			            	$secondary_landline = htmlentities(str_replace('�','',trim($getData[16])));
			            	
			            	$fax  = htmlentities(str_replace('�','',trim($getData[17])));

			            	$company_credibility = trim($getData[19]);
			            	if($company_credibility == "Credit Allowed" || $company_credibility == "credit allowed"){
			            		$company_credibility_value = 1;

			            	}else if($company_credibility == "Credit Not Allowed" || $company_credibility == "credit not allowed"){

			            		$company_credibility_value = 2;

			            	}
			            	$credit_limit  = htmlentities(str_replace('�','',trim($getData[20])));
			            	$national_account  = htmlentities(str_replace('�','',trim($getData[21])));
			            	$dealsIn = htmlentities(str_replace('�','',trim($getData[22])));
			            	$details  = htmlentities(str_replace('�','',trim($getData[23])));
			            	$status = 1;
			            	$date_created= date('Y-m-d');
			            	
			            	$lastModified = date('Y-m-d');
			            	$id_mst_user_created_by = $_SESSION['userId'];
			            	$id_mst_user_modified_by = $_SESSION['userId'];

			            	//fetching id default group
			            	$default_group_name = trim($getData[0]);

			            	$sql = "SELECT id FROM `mst_attributes` WHERE `id_shop`=".$id_shop." AND table_name='company_group' AND `field_value`='".$default_group_name."' ";
			            	$res = mysqli_query($conn,$sql);

			            	if($res){
			            		if(mysqli_num_rows($res)>0){
			            			$resData = mysqli_fetch_object($res);
			            			$id_group_got = $resData->id;
			            		}
			            		else{

			            			$errorRecords .=  ($errorCount + 1).") ".$companyName." - Error : at : ".$x."th data :Company Group :  ".$default_group_name. " Not Found \n";
			            			//echo "<br>Error : at : ".$x."th data :Company Group :  <span style='color:red;'>".$default_group_name. "</span>  Not Found";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

		            	
			            	//fetching id country

			            	$country = trim($getData[4]);

			            	//

			            	//echo "country : ".$companyName;

			            	$sql1 = "SELECT id_country FROM `mst_country_lang` WHERE  `name`='".$country."' ";
			            	$res1 = mysqli_query($conn,$sql1);

		            	
			            	if($res1){
			            		if(mysqli_num_rows($res1)>0){
			            			$resData1 = mysqli_fetch_object($res1);
			            			$id_country_got = $resData1->id_country;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$companyName." - Error : at : ".$x."th Data :Country : ".$country. " Not Found \n";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fecthing id state

			            	$state = trim($getData[6]);

			            	$sql2 = "SELECT id_state FROM `mst_state` WHERE  `name`='".$state."' AND id_mst_country_lang=".$id_country_got." ";

			            	$res2 = mysqli_query($conn,$sql2);

		            	
			            	if($res2){
			            		if(mysqli_num_rows($res2)>0){
			            			$resData2 = mysqli_fetch_object($res2);
			            			$id_state_got = $resData2->id_state;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$companyName." - Error : at : ".$x."th Data : State : ".$state. " For Country ".$country." Not Found \n";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}



			            	//fetching area 

			            	$area = trim($getData[18]);

			            	$sql3 = "SELECT id FROM `mst_portfolio_account` WHERE `id_shop`=".$id_shop." AND `name`='".$area."' ";

			            	$res3 = mysqli_query($conn,$sql3);

		            	
			            	if($res3){
			            		if(mysqli_num_rows($res3)>0){
			            			$resData3 = mysqli_fetch_object($res3);
			            			$id_area_got = $resData3->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$companyName." - Error : at : ".$x."th Data : Area : ".$area. " Not Found \n";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "TABLE AREA : SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//Inserting Records

			            	//echo "company Group : ".$id_group_got." Country : ".$id_country_got." Area : ".$id_area_got." State : ".$id_state_got;

			            	if($err == 0){

			            		if($id_group_got != "" && $id_area_got !="" && $id_country_got !="" && $id_state_got !=""){

					            		//echo "hello";
					            		$insertSql = "INSERT INTO `mst_company` 

					            					(`id_shop_group`,`id_shop`,`id_mst_attributes_company_group`,`id_lang`,`name`,`email`,`secondary_email`,`id_mst_country_lang`,`other_country`,`id_mst_state`,`other_state`,`postcode`,`city`,`address`,`primary_contact_type`,`primary_mobile`,`primary_landline`,`secondary_contact_type`,`secondary_mobile`,`secondary_landline`,`fax`,`id_mst_portfolio_account`,`id_nac`,`company_credibility`,`credit_limit`,`deals_in`,`details`,`status`,`date_created`,`last_modified`,`id_mst_user_created_by`,`id_mst_user_modified_by`) 
					            					VALUES (".$id_shop_group.",".$id_shop.",".$id_group_got.",".$id_lang.",'".$companyName."','".$email."','".$secondaryEmail."','".$id_country_got."','".$otherCountry."','".$id_state_got."','".$otherState."','".$postCode."','".$city."','".$address."','".$primary_contact_type."','".$primary_mobile."','".$primary_landline."','".$secondary_contact_type."','".$secondary_mobile."','".$secondary_landline."','".$fax."','".$id_area_got."','".$national_account."','".$company_credibility_value."','".$credit_limit."','".$dealsIn."','".$details."','".$status."','".$date_created."','".$lastModified."','".$id_mst_user_created_by."','".$id_mst_user_modified_by."')	
					            					";
					            		$finalRes = mysqli_query($conn,$insertSql);
					            		//$insertId = mysqli_insert_id($finalRes);
					            		if($finalRes){

					            			$companyImported .=  ($insertCount + 1).") ".$companyName."\n";
					            			$insertCount++;
					            		}

					            		else{
					            			echo "<br>Insertion Failed at ===".$x."Problem In below Query <br>".$insertSql."";
					            			exit;
					            		}

				            					
				            	}
				            	else{
				            		echo "one of the Ids are missing";
				            	}

			            	}

		            	}

	            	}

	            	$x++;

		  		}

		  		fclose($file);	
		        if($insertCount > 0){
		        	//$insertedRows = "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

		        	echo "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

		        	$imported = "Company Imported Successfully : \n";
		        }

		        if($errorCount > 0){

		        	echo "<span style='color:red'> Records Not Imported Successfully : ".($errorCount)."</span><br>";
		        	$error = "Errors  : \n";
		        	$line_break = "\n";
		        
		        }
		        
		        if($duplicateCount>0){
		        	$duplicate = "Duplicate Entries : \n";
		        	//$duplicateRows = "Duplicate Entries : ".$duplicateCount;
		        	echo "<span style='color:red'>Duplicate Entries : ".$duplicateCount."</span>";
		        	$line_break = "\n";
		        }

		        $directory = '../';
	        	$logPath = 'logs_file/company_logs'.date('Y-m-d_h-i-s_'.rand(1111,9999)).'.txt';
	        	//$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$error.$errorRecords." - ".$message.$line_break.$imported.$companyImported.PHP_EOL , FILE_APPEND | LOCK_EX);
	        	$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$line_break.$error.$errorRecords.$line_break.$imported.$companyImported.PHP_EOL , FILE_APPEND | LOCK_EX);
	        	echo "<br><a href=".$logPath." download> Download Log  "."</a>";
		        
		        
		        //echo json_encode($insertedRows.$duplicateRows.$fileDuplicateLink);

			}else{
				echo json_encode("File is empty ! Kindly Check");
			}

		}else if($tableName	=='inv_items'){

			if($_FILES["companyImport"]["size"] > 0){
		  		$file = fopen($filename, "r");
		  		$x=0;
		  		$insertCount=0;
		  		$duplicateCount=0;
		  		$errorCount = 0;
	        	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){

		         	if($x==0){
		         		//just to skip the first row
		         	}
	            	else{
	            		$err = 0;
		            	//setting Values to insert
		            	$id_shop = $_SESSION['shop'];
		            	$item_code = htmlentities(str_replace('�','',trim($getData[0])));
		            	$item_name1 = htmlentities(str_replace('�','',trim($getData[1]))) ;


		            	$query = "SELECT * FROM `".inv_items."` WHERE name = '".$item_name1."' ";
		            	$result = @mysqli_query($conn, $query);

		            	if(mysqli_num_rows($result)>0){

		            		$duplicateRecords .=  ($duplicateCount + 1).") ".$item_name1."  \n";
		            		$duplicateCount++;
		            		
		            	}else{
		            		$item_name = htmlentities(str_replace('�','',trim($getData[1]))) ;
		            		//fetching id item_type

			            	$item_type = trim($getData[2]);

			
			            	$sql1 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value` = '".$item_type."' ";
			            	$res1 = @mysqli_query($conn, $sql1);

			            	if($res1){
			            		if(mysqli_num_rows($res1)>0){
			            			$resData1 = mysqli_fetch_object($res1);
			            			$item_type_id = $resData1->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Type  : ".$item_type. "  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Item Group Main
			            	$item_group_main = trim($getData[3]);

			            	$sql2 = "SELECT id FROM `mst_attributes` WHERE `id_shop`=".$id_shop." AND `field_value`='".$item_group_main."' ";
			            	$res2 = @mysqli_query($conn, $sql2);

			            	if($res2){
			            		if(mysqli_num_rows($res2)>0){
			            			$resData2 = mysqli_fetch_object($res2);
			            			$item_group_main_id = $resData2->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Main Group   : ".$item_group_main. "  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Item Group Sub
			            	$item_group_sub = trim($getData[4]);

			            	$sql3 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`= '".$item_group_sub."' ";
			            	$res3 = @mysqli_query($conn, $sql3);

			            	if($res3){
			            		if(mysqli_num_rows($res3)>0){
			            			$resData3 = mysqli_fetch_object($res3);
			            			$item_group_sub_id = $resData3->id; 
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Sub Group  : ".$item_group_sub. "  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Item Unit Main
			            	$item_unit_main = trim($getData[5]);

			            	$sql4 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`= '".$item_unit_main."' ";
			            	$res4 = @mysqli_query($conn, $sql4);

			            	if($res4){
			            		if(mysqli_num_rows($res4)>0){
			            			$resData4 = mysqli_fetch_object($res4);
			            			$item_unit_main_id = $resData4->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Main Unit  : ".$item_unit_main. " Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}


			            	//fetching id Item Unit Alt
			            	$item_unit_alt = trim($getData[6]);

			            	$sql5 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`= '".$item_unit_alt."' ";
			            	$res5 = @mysqli_query($conn, $sql5);

			            	if($res5){
			            		if(mysqli_num_rows($res5)>0){
			            			$resData5 = mysqli_fetch_object($res5);
			            			$item_unit_alt_id = $resData5->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Alt Unit   : ".$item_unit_alt. "  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Sales Account Local
			            	$sales_local_account = trim($getData[7]);

			            	$sql6 = "SELECT id FROM `mst_charges` WHERE `id_shop`= '".$id_shop."' AND `name`='".$sales_local_account."' ";
			            	$res6 = @mysqli_query($conn, $sql6);

			            	if($res6){
			            		if(mysqli_num_rows($res6)>0){
			            			$resData6 = mysqli_fetch_object($res6);
			            			$sales_local_account_id = $resData6->id;
			            		}
			            		else{
			            			$sales_local_account_id = '';
			            			/*echo "<br>Error : at : ".$x."th data : Item Type  :  <span style='color:red;'>".$sales_local_account. "</span>  Not Found";
			            			exit;*/
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Charges Sales Interstate
			            	$charges_sales_interstate = trim($getData[8]);

			            	$sql7 = "SELECT id FROM `mst_charges` WHERE `id_shop`= '".$id_shop."' AND `name`= '".$charges_sales_interstate."' ";
			            	$res7 = @mysqli_query($conn, $sql7);

			            	if($res7){
			            		if(mysqli_num_rows($res7)>0){
			            			$resData7 = mysqli_fetch_object($res7);
			            			$charges_sales_interstate_id = $resData7->id;
			            		}
			            		else{

			            			$charges_sales_interstate_id = '';
			            			/*echo "<br>Error : at : ".$x."th data : Charges Sales Interstate  :  <span style='color:red;'>".$charges_sales_interstate. "</span>  Not Found";
			            			exit;*/
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Charges Purchase Local
			            	$charges_purchase_local = trim($getData[9]);

			            	$sql8 = "SELECT id FROM `mst_charges` WHERE `id_shop`= '".$id_shop."' AND `name`= '".$purchase_local_account."' ";
			            	$res8 = @mysqli_query($conn, $sql8);

			            	if($res8){
			            		if(mysqli_num_rows($res8)>0){
			            			$resData8 = mysqli_fetch_object($res8);
			            			$purchase_local_account_id = $resData8->id;
			            		}
			            		else{
			            			$purchase_local_account_id = '';
			            			/* echo "<br>Error : at : ".$x."th data : Charges Puchase Local  :  <span style='color:red;'>".$purchase_local_account. "</span>  Not Found";
			            			exit; */
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Charges Purchase Interstate
			            	$charges_purchase_interstate = trim($getData[10]);

			            	$sql9 = "SELECT id FROM `mst_charges` WHERE `id_shop`= '".$id_shop."' AND `name` = '".$charges_purchase_interstate."' ";
			            	$res9 = @mysqli_query($conn, $sql9);

			            	if($res9){
			            		if(mysqli_num_rows($res9)>0){
			            			$resData9 = mysqli_fetch_object($res9);
			            			$charges_purchase_interstate_id = $resData9->id;
			            		}
			            		else{

			            			$charges_purchase_interstate_id = '';
			            			/*echo "<br>Error : at : ".$x."th data : Charges Purchase Interstate  :  <span style='color:red;'>".$charges_purchase_interstate. "</span>  Not Found";
			            			exit;*/
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Item Store
			            	$item_store = trim($getData[11]);

			            	//echo $item_store;

			            	$sql10 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`= '".$item_store."' ";
			            	$res10 = @mysqli_query($conn, $sql10);

			            	if($res10){
			            		if(mysqli_num_rows($res10)>0){
			            			$resData10 = mysqli_fetch_object($res10);
			            			$item_store_id = $resData10->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Item Store  :  ".$item_store. " Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id printer
			            	$printer = trim($getData[12]);

			            	//echo $printer; 

			            	$sql11 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`='".$printer."' ";
			            	$res11 = @mysqli_query($conn, $sql11);

			            	if($res11){
			            		if(mysqli_num_rows($res11)>0){
			            			$resData11 = mysqli_fetch_object($res11);
			            			$printer_id = $resData11->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Printer  :  <span style='color:red;'>".$printer. "</span>  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//fetching id Outlet
			            	$outlet_name = trim($getData[13]);

			            	$outlet_name = explode(',', $getData[13]);
							$outlets = array();
							for($i= 0 ; $i < count($outlet_name); $i++){
								$sql12 = "SELECT id from `mst_outlets` WHERE name = '".$outlet_name[$i]."' ";
								$res12 =  @mysqli_query($conn, $sql12);
								$ids = mysqli_fetch_row($res12);
								array_push($outlets,$ids[0]);
							}
							$outlets_id = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_id = $outlets[$i][0];
							}
							$id = implode(',',$outlets);
							$outlet = $id;

			            	/*$sql12 = "SELECT id FROM `mst_outlets` WHERE `id_shop`= '".$id_shop."' AND `name`='".$outlet."' ";
			            	$res12 = @mysqli_query($conn, $sql12);

			            	if($res12){
			            		if(mysqli_num_rows($res12)>0){
			            			$resData12 = mysqli_fetch_object($res12);
			            			$outlet_id = $resData12->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$item_name." - Error : at : ".$x."th data : Outlet  : ".$outlet. " Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}*/



			            	$conversion_qty = htmlentities(str_replace('�','',trim($getData[14]))) ;
			            	$min_qty = htmlentities(str_replace('�','',trim($getData[15]))) ;
			            	$max_qty = htmlentities(str_replace('�','',trim($getData[16]))) ;
			            	$rol = htmlentities(str_replace('�','',trim($getData[17])));
			            	$roq = htmlentities(str_replace('�','',trim($getData[18]))) ;
			            	$item_class = htmlentities(str_replace('�','',trim($getData[19]))) ;
			            	$bal_qty = htmlentities(str_replace('�','',trim($getData[20]))) ;
			            	$open_qty = htmlentities(str_replace('�','',trim($getData[21]))) ;
			            	$open_amount = htmlentities(str_replace('�','',trim($getData[22]))) ;
			            	$last_purchase_rate = htmlentities(str_replace('�','',trim($getData[23]))) ;

			            	$enable_desc = trim($getData[24]);
			            	if($enable_desc == "Active"){
			            		$enable_desc_billing = 1;
			            	}else{
			            		$enable_desc_billing = 0;
			            	}

			            	$stockable = trim($getData[25]);
			            	if($stockable == "Active"){
			            		$stockable_enable_disable = 1;
			            	}else{
			            		$stockable_enable_disable = 0;
			            	}

			            	$edit_name = trim($getData[26]);
			            	if($edit_name == "Active"){
			            		$edit_name_enable_disable = 1;
			            	}else{
			            		$edit_name_enable_disable = 0;
			            	}

			            	$item_get_expiry = trim($getData[27]);
			            	if($item_get_expiry == "Active"){
			            		$item_get_expiry_details = 1;
			            	}else{
			            		$item_get_expiry_details = 0;
			            	}

			            	$item_production = trim($getData[28]);
			            	if($item_production == "Active"){
			            		$item_production_item = 1;
			            	}else{
			            		$item_production_item = 0;
			            	}

			            	$item_allow = trim($getData[29]);
			            	if($item_allow == "Active"){
			            		$item_allow_additional = 1;
			            	}else{
			            		$item_allow_additional = 0;
			            	}

			            	$item_disable = trim($getData[30]);
			            	if($item_disable == "Active"){
			            		$item_disabled = 1;
			            	}else{
			            		$item_disabled = 0;
			            	}

			            	$sale_rate = htmlentities(str_replace('�','',trim($getData[31]))) ;

			            	$purchase_rate = htmlentities(str_replace('�','',trim($getData[32]))) ;

			            	$batch_detail = trim($getData[33]);
			            	if($batch_detail == "Active"){
			            		$batch_details = 1;
			            	}else{
			            		$batch_details = 0;
			            	}

			            	$item_detail = trim($getData[34]);
			            	if($item_detail == "Active"){
			            		$item_details = 1;
			            	}else{
			            		$item_details = 0;
			            	}

			            	$display_order = htmlentities(str_replace('�','',trim($getData[35]))) ;

			            	$status = 1;
			            	$date_created= date('Y-m-d');
			            	$lastModified = date('Y-m-d');
			            	$id_mst_user_created_by = $_SESSION['userId'];
			            	$id_mst_user_modified_by = $_SESSION['userId'];

			            	//Inserting Records
			            	//echo "item type : ".$item_type_id . " item main group : ".$item_group_main_id." item sub group : ".$item_group_sub_id." item main : ".$item_unit_main_id." item alt : ".$item_unit_alt_id;

			            	if($err == 0){

			            		if($item_type_id != "" && $item_group_main_id != "" && $item_group_sub_id != "" && $item_unit_main_id !="" && $item_unit_alt_id !=""){

				            		$insertSql = "INSERT INTO `inv_items` 

				            					(item_code,`name`,`id_mst_attributes_item_type`,`id_mst_attributes_group_main`,`id_mst_attributes_group_sub`,`id_mst_attributes_unit_main`,`id_mst_attributes_unit_alt`,`id_mst_charges_sales_local`,`id_mst_charges_sales_interstate`,`id_mst_charges_purchase_local`,`id_mst_charges_purchase_interstate`,`id_mst_attributes_store`,`id_mst_attributes_printer`,`ids_mst_outlet`,`conversion_qty`,`min_qty`,`max_qty`,`rol`,`roq`,`item_class`,`bal_qty`,`open_qty`,`open_amount`,`last_purchase_rate`,`item_enable_desc_billing`,`stockable_enable_disable`,`edit_name_enable_disable`,`item_get_expiry_details`,`item_production_item`,`item_allow_additional`,`item_disable`,`sale_rate`,`purchase_rate`,`batch_details`,`item_details`,`display_order`,`id_shop`,
				            						`status`,`date_created`,`last_modified`,`id_mst_user_created_by`,`id_mst_user_modified_by`) 
				            					VALUES ('".$item_code."','".$item_name."','".$item_type_id."','".$item_group_main_id."','".$item_group_sub_id."','".$item_unit_main_id."','".$item_unit_alt_id."','".$sales_local_account_id."','".$charges_sales_interstate_id."','".$purchase_local_account_id."','".$charges_purchase_interstate_id."','".$item_store_id."','".$printer_id."','".$outlet."','".$conversion_qty."','".$min_qty."','".$max_qty."','".$rol."','".$roq."','".$item_class."','".$bal_qty."','".$open_qty."','".$open_amount."','".$last_purchase_rate."','".$enable_desc_billing."','".$stockable_enable_disable."','".$edit_name_enable_disable."','".$item_get_expiry_details."','".$item_production_item."','".$item_allow_additional."','".$item_disabled."','".$sale_rate."','".$purchase_rate."','".$batch_details."','".$item_details."','".$display_order."','".$id_shop."','".$status."','".$date_created."','".$lastModified."','".$id_mst_user_created_by."','".$id_mst_user_modified_by."')	
				            					";
				            		$finalRes = mysqli_query($conn, $insertSql);
				            		//$insertId = mysqli_insert_id($finalRes);
				            		if($finalRes){
				            			$itemImported .=  ($insertCount + 1).") ".$item_name."\n";
			            				$insertCount++;
				            			
				            		}
				            		else{
				            			echo "<br>Insertion Failed at ===".$x."Problem In below Query <br>".$insertSql."";
				            			exit;
				            		}

				            					
				            	}
				            	else{
				            		echo "one of the Ids are missing";
				            	}
			            	}

		            	}
	            	
	            	}
	        		$x++;
	       	 	}
			
		        fclose($file);	
		        if($insertCount > 0){
		        	//$insertedRows = "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

		        	echo "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

		        	$imported = "Item Imported Successfully : \n";
		        }

		        if($errorCount > 0){

		        	echo "<span style='color:red'> Records Not Imported Successfully : ".($errorCount)."</span><br>";
		        	$error = "Errors  : \n";
		        	$line_break = "\n";
		        
		        }
		        
		        if($duplicateCount>0){
		        	$duplicate = "Duplicate Entries : \n";
		        	//$duplicateRows = "Duplicate Entries : ".$duplicateCount;
		        	echo "<span style='color:red'>Duplicate Entries : ".$duplicateCount."</span>";
		        	$line_break = "\n";
		        }

		        $directory = '../';
	        	$logPath = 'logs_file/items_logs'.date('Y-m-d_h-i-s_'.rand(1111,9999)).'.txt';
	        	//$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$error.$errorRecords." - ".$message.$line_break.$imported.$companyImported.PHP_EOL , FILE_APPEND | LOCK_EX);
	        	$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$line_break.$error.$errorRecords.$line_break.$imported.$itemImported.PHP_EOL , FILE_APPEND | LOCK_EX);
	        	echo "<br><a href=".$logPath." download> Download Log  "."</a>";
			}
			else{
				echo json_encode("File is empty ! Kindly Check");
			}

		}else if($tableName == 'mst_users'){

			if($_FILES["companyImport"]["size"] > 0){
		  		$file = fopen($filename, "r");
		  		$x=0;
		  		$insertCount=0;
		  		$duplicateCount=0;
		  		$errorCount = 0;
	        	while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
		         	if($x==0){
		         		//just to skip the first row
		         	}
	            	else{
		            	//Setting Values to insert

		            	$id_shop_group = 1;
		            	$id_shop = $_SESSION['shop'];
		            	$err = 0;

		            	$userName1 = htmlentities(str_replace('�','',trim($getData[1])));

		            	$email1 =htmlentities(str_replace('�','',trim($getData[4]))) ;

		            	$query = "SELECT * FROM `".mst_users."` WHERE user_name = '".$userName1."' AND email = '".$email1."'";
		            	$result = @mysqli_query($conn, $query);

		            	
		            	if(mysqli_num_rows($result)>0){

		            		$duplicateRecords .=  ($duplicateCount + 1).") ".$userName1."  \n";
		            		$duplicateCount++;
		            		
		            	}else{

		            		$name = htmlentities(str_replace('�','',trim($getData[0])));

		            		$userName = htmlentities(str_replace('�','',trim($getData[1])));

		            		$password = htmlentities(str_replace('�','',trim($getData[2])));

		            		$email = htmlentities(str_replace('�','',trim($getData[4]))) ;

		            		$designation = htmlentities(str_replace('�','',trim($getData[5]))) ;

		            		$other_designation = htmlentities(str_replace('�','',trim($getData[6]))) ;

		            		$primary_contact =  trim($getData[7]);

			            	if($primary_contact == "Mobile" || $primary_contact == "mobile"){
			            		$primary_contact_type = 1;
			            	}else if($primary_contact == "Landline" || $primary_contact == "landline"){
			            		$primary_contact_type = 2;
			            	}

			            	$primary_mobile = htmlentities(str_replace('�','',trim($getData[8])));
			            	$primary_landline = htmlentities(str_replace('�','',trim($getData[9])));

			            	$secondary_contact =  trim($getData[10]);

			            	if($secondary_contact == "Mobile" || $secondary_contact == "mobile"){
			            		$secondary_contact_type = 2;
			            		
			            	}else if($secondary_contact == "Landline" || $secondary_contact == "landline"){
			            		$secondary_contact_type = 1;
			            	}

			            	$secondary_mobile = htmlentities(str_replace('�','',trim($getData[11])));
			            	$secondary_landline = htmlentities(str_replace('�','',trim($getData[12])));

		            		//$hotel_access = htmlentities(str_replace('�','',trim($getData[13])));

		            		$hotel_access = explode(',',$getData[13]);
							$hotels = array();
							for($i= 0 ; $i < count($hotel_access); $i++){
								$sqlhotel = "SELECT id from `mst_hotels` WHERE name = '".$hotel_access[$i]."' ";
								$reshotel = mysqli_query($connNew, $sqlhotel);
								$idshotel = mysqli_fetch_row($reshotel);
								array_push($hotels,$idshotel[0]);
							}
							$hotels_id = array();
							for($i=0 ; $i < count($hotels) ; $i++){
								$hotels_id = $hotels[$i][0];
							}
							 
							$idHotel = implode(',',$hotels);
							echo $idHotel;
							$hotelAccess = $idHotel;

		            		//$outlet_access = htmlentities(str_replace('�','',trim($getData[14])));
							//echo $getData[14];
		            		$ids_mst_outlets = explode(',',$getData[14]);
							$outlets = array();
							for($i= 0 ; $i < count($ids_mst_outlets); $i++){
								$sqlOutlets = "SELECT id from `mst_outlets` WHERE name = '".$ids_mst_outlets[$i]."' ";
								$resOutlets =  mysqli_query($connNew, $sqlOutlets);
								$idsOutlets = mysqli_fetch_row($resOutlets);
								array_push($outlets,$idsOutlets[0]);
							}
							$outlets_id = array();
							for($i=0 ; $i < count($outlets) ; $i++){
								$outlets_id = $outlets[$i][0];
							}
							$idOutlets = implode(',',$outlets);

							$Outlets = $idOutlets;

		            		//$team = htmlentities(str_replace('�','',trim($getData[15])));

		            		//$team_members = htmlentities(str_replace('�','',trim($getData[16])));

		            		$ids_mst_team = explode(',', $getData[16]);
							$teams = array();
							for($i= 0 ; $i < count($ids_mst_team); $i++){
								$sqlTeam = "SELECT id from `mst_team` WHERE name = '".$ids_mst_team[$i]."' ";
								$resTeam =  mysqli_query($connNew, $sqlTeam);
								$idsTeam = mysqli_fetch_row($resTeam);
								array_push($teams,$idsTeam[0]);
							}
							$teams_id = array();
							for($i=0 ; $i < count($teams) ; $i++){
								$teams_id = $teams[$i][0];
							}
							$idTeam = implode(',',$teams);
							$teamMembers = $idTeam;

		            		$company = htmlentities(str_replace('�','',trim($getData[17])));

		            		$address1 = htmlentities(str_replace('�','',trim($getData[18])));

		            		$address2 = htmlentities(str_replace('�','',trim($getData[19])));

		            		$city = htmlentities(str_replace('�','',trim($getData[20])));
		            		
			            	//$State = htmlentities(str_replace('�','',trim($getData[21])));
			            	$zipcode = htmlentities(str_replace('�','',trim($getData[22])));
			            	
			            	
			            	$skype  = htmlentities(str_replace('�','',trim($getData[23])));

			            	$comments  = htmlentities(str_replace('�','',trim($getData[24])));

			            	$ip_address  = htmlentities(str_replace('�','',trim($getData[25])));

			            	$browser  = htmlentities(str_replace('�','',trim($getData[26])));

			            	$id_session  = htmlentities(str_replace('�','',trim($getData[27])));

			            	$dsr_back_date  = htmlentities(str_replace('�','',trim($getData[28])));

			            	$app_geo_location = htmlentities(str_replace('�','',trim($getData[29])));
			            	
			            	$status = 1;
			            	$date_created= date('Y-m-d');
			            	
			            	$lastModified = date('Y-m-d');
			            	$id_mst_user_created_by = $_SESSION['userId'];
			            	$id_mst_user_modified_by = $_SESSION['userId'];


			            	$userLevel = trim($getData[3]) ;

			            	$sql1 = "SELECT id FROM `mst_user_levels` WHERE `id_shop`=".$id_shop." AND `name`='".$userLevel."' ";
			            	$res1 = mysqli_query($conn,$sql1);

			            	if($res1){
			            		if(mysqli_num_rows($res1)>0){
			            			$resData1 = mysqli_fetch_object($res1);
			            			$userLevel_id = $resData1->id;
			            		}
			            		else{

			            			$errorRecords .=  ($errorCount + 1).") ".$userName." - Error : at : ".$x."th data : User Level :  ".$userLevel. " Not Found \n";
			            			//echo "<br>Error : at : ".$x."th data :Company Group :  <span style='color:red;'>".$default_group_name. "</span>  Not Found";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}


			            	// fetching id designation

			            	$designation = trim($getData[5]);

			            	$sql2 = "SELECT id FROM `mst_attributes` WHERE `id_shop`= '".$id_shop."' AND `field_value`='".$designation."' ";
			            	$res2 = @mysqli_query($conn, $sql2);

			            	if($res2){
			            		if(mysqli_num_rows($res2)>0){
			            			$resData2 = mysqli_fetch_object($res2);
			            			$designation_id = $resData2->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$userName." - Error : at : ".$x."th data : Designation  :  <span style='color:red;'>".$designation. "</span>  Not Found \n";
			            			
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}



			            	//fecthing id state

			            	$state = trim($getData[21]);

			            	$sql3 = "SELECT id_state FROM `mst_state` WHERE  `name`='".$state."' ";

			            	$res3 = mysqli_query($conn,$sql3);

		            	
			            	if($res3){
			            		if(mysqli_num_rows($res3)>0){
			            			$resData3 = mysqli_fetch_object($res3);
			            			$id_state_got = $resData3->id_state;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$userName." - Error : at : ".$x."th Data : State : ".$state." Not Found \n";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	// fetching team

			            	$team = trim($getData[15]);


			            	$sql4 = "SELECT id FROM `mst_team` WHERE `id_shop`=".$id_shop." AND `name`='".$team."' ";

			            	$res4 = mysqli_query($conn, $sql4);

		            	
			            	if($res4){
			            		if(mysqli_num_rows($res4)>0){
			            			$resData4 = mysqli_fetch_object($res4);
			            			$id_team_got = $resData4->id;
			            		}
			            		else{
			            			$errorRecords .=  ($errorCount + 1).") ".$userName." - Error : at : ".$x."th Data : Team : ".$team. " Not Found \n";
			            			$err++;
			            			$errorCount++;
			            		}
			            		
			            	}
			            	else{
			            		echo "TABLE AREA : SQL QUERY NOT EXECUTED !";
			            		exit;
			            	}

			            	//Inserting Records

			            	//echo "userLevel : ".$userLevel_id." Designation : ".$designation_id." team : ".$id_team_got." State : ".$id_state_got;

			            	if($err == 0){

			            		if($userLevel_id != "" && $designation_id != "" && $id_team_got != "" && $id_state_got != ""){

			            			
			            			$insertSql = "INSERT INTO `mst_users` 
			            					(`id_shop`,`id_shop_group`,`name`,`user_name`,`password`,`id_mst_user_levels`, `email`,`id_mst_attributes_designations`,`other_designation`,`primary_contact_type`,`primary_mobile`,`primary_landline`,`secondary_contact_type`,`secondary_mobile`,`secondary_landline`,`ids_mst_hotels`,`ids_mst_outlet`,`id_mst_team`,`ids_mst_team`,`company`,`address`,`address2`,`city`,`id_mst_state`,`zip`,`skype`,`comments`,`ip_address`,`browser`,`id_session`,`dsr_num_days`,`geo_location_interval`,`status`,`date_created`,`last_modified`,`id_mst_user_created_by`,`id_mst_user_modified_by`) 
			            					VALUES ('".$id_shop."','".$id_shop_group."','".$name."','".$userName."','".$password."','".$userLevel_id."','".$email."','".$designation_id."','".$other_designation."','".$primary_contact_type."','".$primary_mobile."','".$primary_landline."','".$secondary_contact_type."','".$secondary_mobile."','".$secondary_landline."','".$hotelAccess."','".$Outlets."','".$id_team_got."','".$teamMembers."','".$company."','".$address1."','".$address2."','".$city."','".$id_state_got."','".$zipcode."','".$skype."','".$comments."','".$ip_address."','".$browser."','".$id_session."','".$dsr_num_days."','".$geo_location_interval."','".$status."','".$date_created."','".$lastModified."','".$id_mst_user_created_by."','".$id_mst_user_modified_by."')	
			            					";
			            		$finalRes = mysqli_query($conn,$insertSql);
			            		//$insertId = mysqli_insert_id($finalRes);
			            		if($finalRes){

			            			$userImported .=  ($insertCount + 1).") ".$userName."\n";
			            			$insertCount++;
			            		}

			            		else{
			            			echo "<br>Insertion Failed at ===".$x."Problem In below Query <br>".$insertSql."";
			            			exit;
			            		}

			            					
			            	}
			            	else{
			            		echo "one of the Ids are missing";
			            	}

			            }

			            	

		            }
	            	
	            }
	        	$x++;
	       	}
			
		    fclose($file);	
		    $data = array();
		    if($insertCount > 0){
	        	//$insertedRows = "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

	        	echo "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

	        	$imported = "User Imported Successfully : \n";
		    }

	        if($errorCount > 0){

	        	echo "<span style='color:red'> Records Not Imported Successfully : ".($errorCount)."</span><br>";
	        	$error = "Errors  : \n";
	        	$line_break = "\n";
	        
	        }
		        
	        if($duplicateCount>0){
	        	$duplicate = "Duplicate Entries : \n";
	        	//$duplicateRows = "Duplicate Entries : ".$duplicateCount;
	        	echo "<span style='color:red'>Duplicate Entries : ".$duplicateCount."</span>";
	        	$line_break = "\n";
	        }

	        $directory = '../';
        	$logPath = 'logs_file/user_logs'.date('Y-m-d_h-i-s_'.rand(1111,9999)).'.txt';
        	//$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$error.$errorRecords." - ".$message.$line_break.$imported.$companyImported.PHP_EOL , FILE_APPEND | LOCK_EX);
        	$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$line_break.$error.$errorRecords.$line_break.$imported.$userImported.PHP_EOL , FILE_APPEND | LOCK_EX);
        	echo "<br><a href=".$logPath." download> Download Log  "."</a>";
	        
	        
	        //echo json_encode($insertedRows.$duplicateRows.$fileDuplicateLink);

		}
		else{
			echo json_encode("File is empty ! Kindly Check");
		}

	}if($tableName == 'mst_attributes'){

		if($_FILES["companyImport"]["size"] > 0){

			$file = fopen($filename, "r");
	  		$x=0;
	  		$insertCount=0;
	  		$duplicateCount=0;
	  		$errorCount = 0;
	  		while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){
	  			if($x==0){
	         		//just to skip the first row
	         	}
            	else{
            		//Setting Values to insert
            		$table_name = 'designations';
            		$field_name = 'designations_name';
	            	$err = 0;

	            	$field_value1 = htmlentities(str_replace('�','',trim($getData[0])));

	            	$query = "SELECT * FROM `".mst_attributes."` WHERE 	field_value = '".$field_value1."' AND table_name = 'designations' ";
	            	$result = @mysqli_query($conn, $query);

	            	if(mysqli_num_rows($result)>0){

	            		$duplicateRecords .=  ($duplicateCount + 1).") ".$field_value1."  \n";
	            		$duplicateCount++;
	            		
	            	}else{

	            		$field_value = htmlentities(str_replace('�','',trim($getData[0])));
	            		$field_description =htmlentities(str_replace('�','',trim($getData[1]))) ;

	            		$id_shop = $_SESSION['shop'];

	            		$status = 1;

		            	$date_created= date('Y-m-d');
		            	
		            	$lastModified = date('Y-m-d');
		            	$id_mst_user_created_by = $_SESSION['userId'];
		            	$id_mst_user_modified_by = $_SESSION['userId'];

		            	
		            	//Inserting Records

		            	//echo "company Group : ".$id_group_got." Country : ".$id_country_got." Area : ".$id_area_got." State : ".$id_state_got;

		            	if($err == 0){

		            		if($field_value != "" && $field_description !=""){

				            		//echo "hello";
				            	$insertSql = "INSERT INTO `mst_attributes`(`table_name`,`field_name`,`field_value`,`field_description`,`id_shop`,`status`,`date_created`,`last_modified`,`id_mst_user_created_by`,`id_mst_user_modified_by`)VALUES ('".$table_name."','".$field_name."','".$field_value."','".$field_description."','".$id_shop."','".$status."','".$date_created."','".$lastModified."','".$id_mst_user_created_by."','".$id_mst_user_modified_by."')	
				            					";
				            		$finalRes = mysqli_query($conn,$insertSql);
				            		//$insertId = mysqli_insert_id($finalRes);
				            		if($finalRes){

				            			$designationImported .=  ($insertCount + 1).") ".$field_value."\n";
				            			$insertCount++;
				            		}

				            		else{
				            			echo "<br>Insertion Failed at ===".$x."Problem In below Query <br>".$insertSql."";
				            			exit;
				            		}

			            					
			            	}
			            	else{
			            		echo "one of the Ids are missing";
			            	}

		            	}

	            	}

            	}

            	$x++;

	  		}

	  		fclose($file);	
	        if($insertCount > 0){
	        	//$insertedRows = "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

	        	echo "<br>Number of Records Imported Successfully : ".($insertCount)."<br>";

	        	$imported = "Designation Imported Successfully : \n";
	        }

	        if($errorCount > 0){

	        	echo "<span style='color:red'> Records Not Imported Successfully : ".($errorCount)."</span><br>";
	        	$error = "Errors  : \n";
	        	$line_break = "\n";
	        
	        }
	        
	        if($duplicateCount>0){
	        	$duplicate = "Duplicate Entries : \n";
	        	//$duplicateRows = "Duplicate Entries : ".$duplicateCount;
	        	echo "<span style='color:red'>Duplicate Entries : ".$duplicateCount."</span>";
	        	$line_break = "\n";
	        }

	        $directory = '../';
        	$logPath = 'logs_file/company_logs'.date('Y-m-d_h-i-s_'.rand(1111,9999)).'.txt';
        	//$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$error.$errorRecords." - ".$message.$line_break.$imported.$companyImported.PHP_EOL , FILE_APPEND | LOCK_EX);
        	$myfile = file_put_contents($directory.$logPath, $duplicate.$duplicateRecords.$line_break.$error.$errorRecords.$line_break.$imported.$designationImported.PHP_EOL , FILE_APPEND | LOCK_EX);
        	echo "<br><a href=".$logPath." download> Download Log  "."</a>";
	        
	        
	        //echo json_encode($insertedRows.$duplicateRows.$fileDuplicateLink);

		}else{
			echo json_encode("File is empty ! Kindly Check");
		}

	}
}
else{
	echo json_encode("You Have not selected any file !");
} 
mysqli_close($conn);
?>