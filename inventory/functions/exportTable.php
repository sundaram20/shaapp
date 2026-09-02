<?php include_once("../../config/auto_loader.php");
include_once("selectQuery.php");

//echo "<pre>"; print_r($_REQUEST);//exit;
//$appConnect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, 'app');
					


checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
function local_ExportTable_Comman($dataBaseName ='', $tableName = '', $fileType = ''){
	global $appConnect;
	global $connNew;
	$err = 0;
	
	//@mysql_select_db($dataBaseName,$conn);
	//endDbConnection-------------------------------------
	if($dataBaseName != ''){
		if($tableName != ''){
			if($fileType != ''){
				//Export type---------------------------------------	
				if($fileType == 'csv'){
					$contentType = '"Content-Type: text/comma-separated-values"';
					$fileEnding = "csv";
				}elseif($fileType = 'xls'){
					$contentType = '"Content-Type: application/vnd.ms-excel"';
					$fileEnding = "xls";
				}elseif($fileType = 'doc'){
					$contentType = '"Content-Type: msword"';
					$fileEnding = "doc";
				}
				//End export type-----------------------------------
				$filename = tempnam(sys_get_temp_dir(), $fileType);
				$dataWrite = '';
				// Write column names
				//--------------------------------------------------

				// -------- Field Label
				$field_name =$_REQUEST['field_name'];
				$fieldname = implode("','",$field_name);
				$sqlResult1 = mysqli_query($appConnect, "SELECT * FROM ".TBL_REPORT." WHERE table_name = '".$_REQUEST['tableName']."' AND id_shop = ".$_SESSION['shop'] ." AND field_name IN ('".$fieldname."')  ORDER BY display_order");


				if(@mysqli_num_rows($sqlResult1)){
						while ($sqlRow = mysqli_fetch_object($sqlResult1)){
	
							$field_label[]=$sqlRow->field_label;		
						}
				}

				//------------Field label------------
				if($tableName	=='mst_company'){
					
					$query = local_SelectQuery_Mst_Company($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);						
					$fileName = 'Company List as on ';	
					$xlFilename = "Company List";
					$result = mysqli_query($connNew, $query);
					//echo $query;

				}else if($tableName	=='mst_guest'){
					
					$query = local_SelectQuery_Mst_Guest($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);					
					$fileName = 'Guest List  as on ';	
					$xlFilename = "Guest List";
					$result = mysqli_query($connNew, $query);
					//echo $query;
					
				}else if($tableName	=='mst_hotels'){

					$query = local_SelectQuery_Mst_Hotels($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Hotel List as on ';	
					$xlFilename = "Hotel List";
					$result = mysqli_query($connNew, $query);
					//echo $query;

				}else if($tableName	=='mst_users'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Users($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Users List as on ';	
					$xlFilename = "Users List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_team'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Team($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Team List as on ';	
					$xlFilename = "Team List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_user_levels'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_UserLevels($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'User Levels List as on ';	
					$xlFilename = "User Levels List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_hotel_category'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Hotel_Category($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Hotel Category List as on ';	
					$xlFilename = "Hotel Category List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_room_types'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Room_Type($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Room Types List as on ';	
					$xlFilename = "Room Types List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_hotel_general_services'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_General_Services($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'General Services List as on ';	
					$xlFilename = "General Services List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_hotel_dining_services'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Dining_Services($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Dining Services List as on ';	
					$xlFilename = "Dining Services List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_hotel_outdoor_services'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Outdoor_Activities($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Outdoor Activities List as on ';	
					$xlFilename = "Outdoor Activities List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_hotel_conference_services'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Conference_Services($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Conference Services List as on ';	
					$xlFilename = "Conference Services List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_room_amenities'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Room_Amenities($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Room Amenities List as on ';	
					$xlFilename = "Room Amenities List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_attributes'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Company_Groups($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Company Group List as on ';	
					$xlFilename = "Company Group List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_company_area'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Company_Areas($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Company Area List as on ';	
					$xlFilename = "Company Area List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_portfolio_account'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Portfolio_Account($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Portfolio List as on ';	
					$xlFilename = "Portfolio List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_charges'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Charges_Master($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Charges List as on ';	
					$xlFilename = "Charges List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='inv_items'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Mst_Items($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			

					//echo $query		
					$fileName = 'Items List as on ';	
					$xlFilename = "Items List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}else if($tableName	=='mst_company_contacts'){
					//echo $_REQUEST['tableName'];
					$query = local_SelectQuery_Company_Customer($_REQUEST['tableName'],$_REQUEST['field_name'],$field_label,$_REQUEST['EnableOrderBy']);			
					//echo $query		
					$fileName = 'Company Contacts List as on ';	
					$xlFilename = "Company Contacts List";
					$result = mysqli_query($connNew, $query);
					//echo $query; die;

				}
				
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysqli_num_fields($result);
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){

						$dataWrite .= "<table border='1'>
						<tr><td style='height:50px;font-weight:bold;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						$dataWrite .= "<th>S.No.</th>";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysqli_fetch_field($result);

							if($field->name != 'id'){
								$dataWrite .= "<th>{$field->name}</th>";
							}

						}
						 $dataWrite .= "</tr>";
						// printing table rows
						$Sno = 1;
					while($row = mysqli_fetch_row($result)){
						$value = 1;
						if($tableName == 'fs_user_permissions'){
							$permissions = explode(',',$row[2]);
							$values = array();
							for($i=0 ; $i < count($permissions) ; $i++){
								if($permissions[$i] == 1){
									array_push($values,'View');
								}
								elseif($permissions[$i] == 2){
									array_push($values,'Add');
								}
								elseif($permissions[$i] == 3){
									array_push($values,'Update');
								}
								elseif($permissions[$i] == 4){
									array_push($values,'Activate');
								}
								elseif($permissions[$i] == 5){
									array_push($values,'Deactivate');
								}
								elseif($permissions[$i] == 6){
									array_push($values,'Delete');
								}
								elseif($permissions[$i] == 7){
									array_push($values,'Export');
								}
								elseif($permissions[$i] == 8){
									array_push($values,'Import');
								}
							}
							$string = implode(',  ',$values);
							$row[2] = $string; 
						}

							

						
						//Ids replacement end

						$dataWrite .= "<tr>";
						/*foreach($row as $cell){
							$dataWrite .= "<td>$cell</td>";
						}*/
						$dataWrite .= "<td>$Sno</td>";
						foreach($row as $cell){

							if($value == 1){
								
							}else{

								$dataWrite .= "<td>$cell</td>";
							}

							$value ++;
						}	
					
						$dataWrite .= "</tr>";
						$Sno++;
					}
				//-------------------------------------------------
				}elseif($fileType == 'csv'){
					for($i=0; $i<$fields_num; $i++){
						$field = mysqli_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
					}
					while($row = mysqli_fetch_row($result)){
						$counterCol = 0;
						foreach($row as $cell){
							$endOfLine = ($counterCol == ($fields_num-1))? true:false;
							$dataWrite .= csvFieldFormating($cell,$endOfLine,'');
							$counterCol++;	
						}
					}
					
				}
				//
				$file = @fopen($filename,"w");
				fwrite($file,$dataWrite);
				fclose($file);
				
				$savedFileName = $xlFilename."_".date("Y-m-d_h-i-s_".rand(11111,99999));
				//header($contentType);
				header("Content-type: application/octet-stream"); 
				header("Content-Disposition: attachment;Filename=".$savedFileName.".".$fileEnding."");
				// send file to browser
				header("Pragma: no-cache");
				header("Expires: 0");
				readfile($filename);
				unlink($filename);
			}else{
				$err++;
				$message = "Invalid file type.";
			}
		}else{
			$err++;
			$message = "Invalid table name.";
		}
	}else{
		$err++;
		$message = "Invalid database name.";
	}
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

$db = new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
//$appConnect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, 'app');

$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST['tableName']){
	//$appConnect = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, 'app');
					
	local_ExportTable_Comman($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType']);
}else{
	echo "Invalid input.";
}?>