<?php include_once("../../config/auto_loader.php");
include_once("selectQuery.php");

//echo "<pre>"; print_r($_REQUEST);//exit;



checkUserLevelPermission($_SESSION['userLevel'],'exportTable','view');
///////////////////////////////////////////////////////////////////////////////////
function local_ExportTable_Comman($dataBaseName ='', $tableName = '', $fileType = '',$link = ''){
	
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
				
				if($tableName	=='mst_company'){
					
					$query = local_SelectQuery_Mst_Company($_REQUEST['tableName'],$_REQUEST['field_name'],$_REQUEST['EnableOrderBy']);						
					$fileName = 'Company Data Base Report As On ';	
					$result = mysql_query($query, $conn);

				}else if($tableName	=='mst_guest'){
					
					$query = local_SelectQuery_Mst_Guest($_REQUEST['tableName'],$_REQUEST['field_name'],$_REQUEST['EnableOrderBy']);					
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
if($_REQUEST['fileType'] && $_REQUEST['tableName']){
	local_ExportTable_Comman($DB_NAME, $_REQUEST['tableName'], $_REQUEST['fileType'], $db->conn);
}else{
	echo "Invalid input.";
}?>