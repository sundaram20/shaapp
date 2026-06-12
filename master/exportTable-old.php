<?php include_once("../config/auto_loader.php");

//echo $_REQUEST['field_name']['name']; exit;
//$string = implode(",",$_REQUEST);
//echo $string; exit;

//echo $_POST['tableName'];exit;
//echo "<pre>";
//print_r($_REQUEST); exit; 



checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
function exportTable($dataBaseName ='', $tableName = '', $fileType = '',$link = ''){
	$err = 0;
	//dbConnection-------------------------------------
	if($link && is_resource($link)){
		$conn = $link;
	}else{
		$conn = @mysql_connect("localhost", "root", "");
	}
	@mysql_select_db($dataBaseName,$conn);
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
				/*SELECT a.name As Company , a.address As Address, b.name As Area
FROM  `fs_company` AS a,  `fs_areas_assign` AS b
WHERE a.`id_shop` =2
AND a.area = b.id*/

//create ssql string=
				if($tableName	=='mst_company'){

					$query = "SELECT ";
					if(in_array('name', $_REQUEST['field_name'])){
						$query .= "a.name AS Company, ";

					}

					if(in_array('address', $_REQUEST['field_name'])){
						$query .= "a.address AS Address, ";
					}

					if(in_array('id_area', $_REQUEST['field_name'])){
						$query .= "e.name AS Area, ";
					}

					if(in_array('id_state', $_REQUEST['field_name'])){
						//$query .= "f.name AS State, ";
						$query .= " CASE WHEN a.id_state = 10000 THEN a.other_state ELSE f.name END AS State, ";
					}

					if(in_array('city', $_REQUEST['field_name'])){
						$query .= "a.city AS City, ";
					}

					if(in_array('id_country', $_REQUEST['field_name'])){
						//$query .= "d.name AS Country, ";
						$query .= " CASE WHEN a.id_country = 10000 THEN a.other_country ELSE d.name END AS Country, ";
					}

					if(in_array('postcode', $_REQUEST['field_name'])){
						$query .= "a.postcode AS Postcode, ";

					}

					if(in_array('primary_contact', $_REQUEST['field_name'])){
						$query .= " CASE WHEN a.primary_contact = 1 THEN a.primary_mobile WHEN a.primary_contact = 2 THEN a.primary_landline END AS Primary_contact, ";
					}

					if(in_array('email', $_REQUEST['field_name'])){
						
						$query .= "a.email AS Email, ";
					}

					if(in_array('company_credibility', $_REQUEST['field_name'])){
						
						//$query .= "a.company_credibility As `Company Credibility`, ";

						//$query .= "CASE WHEN a.id_gender = 1 THEN 'Male' WHEN a.id_gender = 2 THEN 'Female' ELSE 'Other' END AS Gender, ";
						$query .= "CASE WHEN a.company_credibility = 1 THEN 'Allowed' WHEN a.company_credibility = 2 THEN 'Not Allowed' END  As `Company Credibility`, ";
					}

					$query .= " a.date_created AS `Creation Date`, a.last_modified AS `Modified Date` FROM mst_company a LEFT JOIN mst_company_area c ON a.deals_in = c.id LEFT JOIN mst_country_lang d ON a.id_country = d.id_country LEFT JOIN mst_state f ON a.id_state = f.id_state LEFT JOIN mst_areas_assign e ON a.id_area = e.id LEFT JOIN mst_users g ON e.id_user = g.id LEFT JOIN mst_users h ON a.last_modified = h.id WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' AND a.name!='' ORDER BY a.".$_REQUEST['EnableOrderBy']." ASC ";
				

		
					//$field_name = implode(",",$_REQUEST['field_name']);

					//$query = "SELECT ".$field_name." FROM mst_company ORDER BY ".$_REQUEST['EnableOrderBy'];


				//$query="SELECT a.name AS Company,  a.address AS Address, e.name AS Area, f.name AS State, a.city AS City, d.name AS Country, a.postcode AS Postcode, g.name AS `Executive Name` , a.email AS Email, a.secondary_email AS `Secondry Email`, a.company_credibility As `Company Credibility` , a.status AS Status, a.date_created AS `Creation Date`, a.last_modified AS `Modified Date`, h.name AS `Modified By` FROM mst_company a LEFT JOIN mst_company_area c ON a.deals_in = c.id LEFT JOIN mst_country_lang d ON a.id_country = d.id_country LEFT JOIN mst_state f ON a.id_state = f.id_state LEFT JOIN mst_areas_assign e ON a.id_area = e.id LEFT JOIN mst_users g ON e.id_user = g.id LEFT JOIN mst_users h ON a.last_modified = h.id WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' AND a.name!='' ORDER BY a.name ASC ";	

				$fileName = 'Company Data Base Report As On ';	
				$result = mysql_query($query, $conn);

				}else if($tableName	=='mst_guest'){
		
					$query = "SELECT ";

					if(in_array('doc_no', $_REQUEST['field_name'])){
						$query .= "a.doc_no As RegNo, ";

					}

					if(in_array('first_name', $_REQUEST['field_name'])){
						$query .= "CONCAT(a.first_name, ' ', a.last_name) AS Name, ";

					}
					if(in_array('address', $_REQUEST['field_name'])){
						$query .= "a.address AS Address, ";
					}


					if(in_array('id_state', $_REQUEST['field_name'])){
						//$query .= "f.name AS State, ";
						$query .= " CASE WHEN a.id_state = 10000 THEN a.other_state ELSE f.name END AS State, ";
					}

					if(in_array('city', $_REQUEST['field_name'])){
						$query .= "a.city AS City, ";
					}

					if(in_array('id_country', $_REQUEST['field_name'])){
						//$query .= "d.name AS Country, ";
						$query .= " CASE WHEN a.id_country = 10000 THEN a.other_country ELSE d.name END AS Country, ";
					}

					if(in_array('id_nationality', $_REQUEST['field_name'])){
						//$query .= "d.name AS Country, ";
						$query .= " a.other_nationality, ";
					}

					if(in_array('postcode', $_REQUEST['field_name'])){
						$query .= "a.postcode AS Postcode, ";
					}

					if(in_array('primary_contact', $_REQUEST['field_name'])){
						$query .= " CASE WHEN a.primary_contact = 1 THEN a.primary_mobile WHEN a.primary_contact = 2 THEN a.primary_landline END AS Primary_contact, ";
					}

					if(in_array('email', $_REQUEST['field_name'])){
						
						$query .= "a.email AS Email, ";
					}

					if(in_array('id_gender', $_REQUEST['field_name'])){
						
						$query .= "CASE WHEN a.id_gender = 1 THEN 'Male' WHEN a.id_gender = 2 THEN 'Female' ELSE 'Other' END AS Gender, ";
					}

					$query .= " a.date_created AS `Creation Date`, a.last_modified AS `Modified Date` FROM mst_guest a  LEFT JOIN mst_country_lang d ON a.id_country = d.id_country LEFT JOIN mst_state f ON a.id_state = f.id_state WHERE a.id_shop =  '".addslashes($_SESSION['shop'])."' ORDER BY a.".$_REQUEST['EnableOrderBy']." ASC ";



					$fileName = 'Guest Data Base Report As On ';	
					$result = mysql_query($query, $conn);
				}
				
				if (!$result) {
					die("Query to show fields from table failed");
				}
				$fields_num = mysql_num_fields($result);
				//echo "<h1>Table: {$table}</h1>";
				if($fileType != 'csv'){

						$dataWrite .= "<table border='1'>
						<tr><td style='height:50px;font-weight:bold;text-align:center;font-size:1.5em;' colspan='".$fields_num."' >".$fileName.date('d-F-Y')."</td></tr>
						<tr>
						";
						// printing table headers
						for($i=0; $i<$fields_num; $i++){
							$field = mysql_fetch_field($result);
							$dataWrite .= "<th>{$field->name}</th>";
						}
						$dataWrite .= "</tr>";
						// printing table rows
				
					while($row = mysql_fetch_row($result)){

						if($tableName == 'fs_users'){						
							$hotel_access = explode(',',$row[6]);
							$hotels = array();
							for($i= 0 ; $i < count($hotel_access); $i++){
								$sql = "SELECT name from `mst_hotels` WHERE id = '".$hotel_access[$i]."' ";
								$res = mysql_query($sql,$conn);
								$name = mysql_fetch_row($res);
								array_push($hotels,$name[0]);
							}
							$hotels_name = array();
							for($i=0 ; $i < count($hotels) ; $i++){
								$hotels_name = $hotels[$i][0];
							}
							$string = implode(', ',$hotels);
							$row[6] = $string;
						}

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

						//Replacing Ids with text
						if($tableName	=='fs_company'){

							if($row[13] == 1){
								$row[13] = "Credit Allowed";
							}
							elseif($row[13] == 2){
								$row[13] = "Credit Not Allowed";
							}

							if($row[14] == 0){
								$row[14] = "Inactive";
							}
							elseif($row[14] == 1){
								$row[14] = "Active";
							}

							if($row[9] == ""){
								$row[9] = "N/A";
							}
						}

						if( $tableName	=='fs_company_area'  OR $tableName	=='fs_operator_master' OR $tableName	=='fs_cancellation_master' OR $tableName	=='fs_amendment_remarks' OR $tableName	=='fs_user_levels' ){
							if($row[1] == 1 ){
								$row[1] = "Active";
							}
							elseif($row[1] == 0){
								$row[1] = "Inactive";
							}
						}

						if($tableName	=='fs_customer'){
							if($row[14] == 1 ){
								$row[14] = "Active";
							}
							elseif($row[14] == 0){
								$row[14] = "Inactive";
							}
						}

						if($tableName	=='fs_series_master' OR $tableName	=='fs_segment_master' OR $tableName	=='fs_users' ){
							if($row[2] == 1 ){
								$row[2] = "Active";
							}
							elseif($row[2] == 0){
								$row[2] = "Inactive";
							}
						}

						if($tableName	=='fs_company_group'){
							if($row[2] == 1 ){
								$row[2] = "TAX included";
							}
							elseif($row[2] == 0){
								$row[2] = "TAX excluded";
							}

							if($row[3] == 1 ){
								$row[3] = "Active";
							}
							elseif($row[3] == 0){
								$row[3] = "Inactive";
							}


						}

						if($tableName	=='fs_general_services' OR $tableName	=='fs_outdoor_activities' OR $tableName	=='fs_dining_services' OR $tableName	=='fs_hotel_services'){
							if($row[4] == 1 ){
								$row[4] = "Active";
							}
							elseif($row[4] == 0){
								$row[4] = "Inactive";
							}
						}

						if($tableName	=='fs_room_type'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}

							if($row[1] == '' OR $row[1] == 0){
								$row[1] = "N/A";
							}
						}

						if($tableName	=='fs_hotel_type'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}

							if($row[1] == '' OR $row[1] == 0){
								$row[1] = "N/A";
							}
						}

						if($tableName	=='fs_areas_assign' OR $tableName	=='fs_user_permissions'){
							if($row[5] == 1 ){
								$row[5] = "Active";
							}
							elseif($row[5] == 0){
								$row[5] = "Inactive";
							}
						}

						if($tableName	=='mst_hotels'){
							if($row[19] == 1 ){
								$row[19] = "Active";
							}
							elseif($row[19] == 0){
								$row[19] = "Inactive";
							}

							if($row[20] == 1 ){
								$row[20] = "Short Term";
							}
							elseif($row[20] == 2){
								$row[20] = "Long Term";
							}
							elseif($row[20] == 0){
								$row[20] = "N/A";
							}

							if($row[24] == 1 ){
								$row[24] = "Active";
							}
							elseif($row[24] == 0){
								$row[24] = "Inactive";
							}
						}

						//Ids replacement end

						$dataWrite .= "<tr>";
						foreach($row as $cell){
							$dataWrite .= "<td>$cell</td>";
						}	
					
						$dataWrite .= "</tr>";
					}
				//-------------------------------------------------
				}elseif($fileType == 'csv'){
					for($i=0; $i<$fields_num; $i++){
						$field = mysql_fetch_field($result);
						$endOfLine = ($i == ($fields_num-1))? true:false;
						$dataWrite .= csvFieldFormating($field->name,$endOfLine,'');
					}
					while($row = mysql_fetch_row($result)){
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
				
				$savedFileName = $tableName."_".date("Y-m-d_h-i-s_".rand(11111,99999));
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
$db->open() or die($db->error());
adminLoginCheck();
checkUserLevelPermission($_SESSION['userLevel'],$_REQUEST['tableName'],'export');
if($_REQUEST['fileType'] && $_REQUEST[tableName]){
	exportTable($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType'], $db->conn);
}else{
	echo "Invalid input.";
}?>