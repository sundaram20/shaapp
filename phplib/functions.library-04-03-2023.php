<?php  ob_start();
#-------------------------------------------------------------
// Function	: ReadTemplate
// Description	: Reads Template File and return the Content of the File 
// Developer : Gaurav Sharma
// Date : 20 October 2017
#-------------------------------------------------------------

function pdfGeneratorAttach($content='', $fileName=''){
		global $attachPath;
	   // echo $attachPath;
	    $attachPath='/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/';
		$dompdf = new DOMPDF();
		$dompdf->set_paper('landscape', 'landscape');
		$dompdf->load_html($content);
		$dompdf->render();
		$font = Font_Metrics::get_font("helvetica", "bold");
		$dompdf->get_canvas()->page_text(720, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 6, array(0,0,0));
		$gen = $dompdf->output();
	//	$dompdf->stream($fileName.'.pdf', array("Attachment" => true));
		file_put_contents($attachPath.$fileName.".pdf", $gen);
		
	}
function docConfigNoValidator($doc_type,$date,$id_subsection){
	global $connNew;
	 $doc_type = $doc_type;	 
	 $po_date = date('Y-m-d' , strtotime($date)); 
	 $date = $date;//date('Y-m-d');
	 $status  =1;
	 $idss = 0;
 
//echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$doc_type."' AND date(`effective_date`) <= date('".$date."')   ORDER BY effective_date desc Limit 0,1 ";
 	$sql4 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$doc_type."' AND date(`effective_date`) <= date('".$date."')   ORDER BY effective_date desc Limit 0,1 ");
	
	$numRows= mysqli_num_rows($sql4); 
	while($row4 = mysqli_fetch_object($sql4)){	  
		 if($row4->effective_date <= $date ){
			 $idss = $row4->id;
			 $effective_date = $row4->effective_date;
		 } 
	}

//echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ";
 	$sql = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ");
 	$numRows =  mysqli_num_rows($sql);
	    while($row =  mysqli_fetch_object($sql)){ 
		$idDocConfigDetail= $row->id;
		$method= $row->method; 
			if($id_subsection>0){
				//echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ";
					 $sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						 //echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ";
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config<span>';
							exit;
							}
						 
						 
						 }
			}else{
					
					$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config.<span>';
							exit;
							}
						 
						 
						 }
			
			if($doc_type==22){ //KOT

			$sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config.<span>';
							exit;
							}
						 
						 }
				
				}
	    }

	if($numRows == 0){

		$sqls = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ");
		    
		    while($rows =  mysqli_fetch_object($sqls)){ 
		$idDocConfigDetail= $row->id;
		$method= $row->method; 
			if($id_subsection>0){
					 $sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
					 
					 if($numRowsDocConfigDetail>0){
					 
				      	while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  	}
					 }else{
						$sqlDocConfigDetail_1 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='0' limit 1 ");
					    $numRowsDocConfigDetail_1 =  mysqli_num_rows($sqlDocConfigDetail_1);
					 	if($numRowsDocConfigDetail_1>0){		 
								 while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail_1)){  
										$id= $rowDocConfigDetail->id_mst_doc_type_config; 
										$start_no= $rowDocConfigDetail->start_no;
										$prefix= $rowDocConfigDetail->prefix; 
										$suffix= $rowDocConfigDetail->suffix;  
					  				}
						}else{
							
							echo '<span style="color:red;">Please create Doc Config<span>';
							exit;
							}
						 }
			}
	    }
	}


	 $result = getDocConfigIncValue($doc_type,$date,$id,$method,$id_subsection,$start_no,$prefix,$suffix);	
	
	return $result;
	
	}



function getDocConfigIncValue($doc_type,$date,$id,$method,$id_subsection,$start_no,$prefix,$suffix){
	
	global $connNew;
	if($method == '1'){

	$sql2 =  mysqli_query($connNew, "SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' and `id_mst_outlet`='".$id_subsection."' AND date(`doc_date`) <= date('".$date."') order by doc_no desc limit 0,1");    
	
	$numRows= mysqli_num_rows($sql2);

		if($numRows != 0){
			while($row2 = mysqli_fetch_object($sql2)){  
			
				$po_no= $row2->doc_no;
				$po_no =$po_no + 1;
				$res['po_no'] = $po_no;
				$res['id_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['po_no'] = $start_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}


	$sql3 = mysqli_query($connNew," SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' and `id_mst_outlet`='".$id_subsection."' order by doc_no desc limit 0,1");
	
	$numRows= mysqli_num_rows($sql3);

	if($numRows > 0){
		while($row3 = mysqli_fetch_object($sql3)){  
			$po_no= $row3->doc_no;
			$po_no = $po_no + 1;
			$res['po_no'] = $po_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['po_no'] = $start_no;
		$res['id_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}

return $res;
	}




function checkTransBeforeDelete($tableArray = array(),$valueToChk=''){
	// table array must be asscociative 
	// key table name
	// value field name
	global $connNew;
	$flag = false;
	foreach ($tableArray as $table_name => $field_name) {
		$sql = "SELECT * FROM ".$table_name." WHERE ".$field_name." ='".$valueToChk."'  ";
		$res = mysqli_query($connNew,$sql);

		if(mysqli_num_rows($res)>0){
			$flag = true;
			return $flag;
		}
	}

}

function adminLoginCheck(){
	
	if(isset($_SESSION['sessionId']) && isset($_SESSION['userId']) && isset($_SESSION['userName']) && isset($_SESSION['userEmail']) && isset($_SESSION['userLevel']) && isset($_SESSION['userLastLogin']) && isset($_SESSION['shop'])){
						/*if (!$conn) {
				  die("Connection failed:w " . mysqli_connect_error());
				  adminLoginCheck();
				  unset($_SESSION['userName']);
		unset($_SESSION['userId']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userLastLogin']);
		unset($_SESSION['sessionId']);
		unset($_SESSION['shop']);
		$_SESSION['errorMsg'] = 'Session has been expired. Please make login.';
		header('location:index.php');
		exit;*/
				//}
	}else{
		unset($_SESSION['userName']);
		unset($_SESSION['userId']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userLastLogin']);
		unset($_SESSION['sessionId']);
		unset($_SESSION['shop']);
		$_SESSION['errorMsg'] = 'Session has been expired. Please make login.';
		header('location:index.php');
		exit;
	}
}
function securityCheck(){
	return true;
}
function checkUserLevelPermission($userLevel,$related_table='',$actionName){
	global $connNew;
	global $appConnect;
	if($userLevel == 1){
		//super admin all access
	return true;

	}else{
		$referPage = strtok(strtoupper(basename($_SERVER['HTTP_REFERER'])),'?');
		$selfPage = strtoupper(basename($_SERVER['PHP_SELF']));
		
		$pageChkSql = "SELECT id FROM ".APP_SUB_MENU." WHERE UPPER(file_name)='".$referPage ."' ";

		$pageChk = mysqli_num_rows(mysqli_query($appConnect,$pageChkSql));
		
		
		
		if($pageChk > 0  ){
			$statusTxt='';
			if(strtoupper($actionName)=='ACTIVE' || strtoupper($actionName)=='INACTIVE')
			$actionName="status";

			if($actionName=="status")
				$statusTxt=" change ";

			if(strtoupper($actionName)=='EDIT')	
				$actionName="update";
	
			 $resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = '".$actionName."'");
			 
			$id_sub_menu=mysqli_fetch_object(mysqli_query($appConnect,$pageChkSql))->id;
			$chkPerSql = "SELECT id FROM ".TBL_USER_PERMISSIONS." WHERE id_sub_menu='".$id_sub_menu."' AND id_mst_user_levels='".$userLevel."' AND FIND_IN_SET(".$resActionId.",ids_user_actions) ";
			$chkPer = mysqli_num_rows(mysqli_query($connNew,$chkPerSql));

			if($chkPer>0 ){
				return true;
			}
			else{
				$_SESSION['errorMsg'] = 'You Don\'t have '.$statusTxt.$actionName.' permission.' ;
				if($referPage!=$selfPage){
					
					if($_SERVER['HTTP_REFERER']!=''){
						header('location:'.$_SERVER['HTTP_REFERER']);
						exit;
					}else{
						header('location:dashboard.php');
						exit;		
					}
				}
				else{
					return false;
				}	
			}
		}
		else{
			return true;
		}

		/*if(strtoupper($actionName)=='ACTIVE' || strtoupper($actionName)=='INACTIVE')
			$actionName="status";	
	
		$resActionId = selectColumn(TBL_USER_ACTIONS, 'id' , "WHERE name = '".$actionName."'");

		$chkSql="SELECT * FROM ".TBL_USER_PERMISSIONS." WHERE id_sub_menu='".$id_sub_menu."' AND id_user_level='".$userLevel."' AND FIND_IN_SET(".$action.",ids_user_actions) ";
		$count = mysqli_num_rows(mysqli_query($connNew,$chkSql));
		if($count>0){
			return true;
		}
		else{
			
		}*/
	}	
	return false;
}



/***** MYSQLI DB FUNCTIONS WRITTEN BY HITESH ALONEY *****/

function insertData($table,$data=array()){
	global $appConnect;
	
	$fieldValuePairs=array();
	
	foreach ($data as $field => $value) {
		array_push($fieldValuePairs,$field."="."'".mysqli_real_escape_string($appConnect,$value)."'");
	}
	
	$sql = "INSERT INTO ".$table." SET ".implode(",",$fieldValuePairs)." ";
	$res = mysqli_query($appConnect,$sql);
	
	if($res){
		return mysqli_insert_id($appConnect);
	}
	else{
		debugData(mysqli_error($appConnect),'DATA INSERTION FAILED');
		debugData($sql,'SQL QUERY');
	}
}

function insertData1($table,$data=array()){
	global $connNew;
	
	$fieldValuePairs=array();
	
	foreach ($data as $field => $value) {
		array_push($fieldValuePairs,$field."="."'".mysqli_real_escape_string($connNew,$value)."'");
	}
	
	$sql = "INSERT INTO ".$table." SET ".implode(",",$fieldValuePairs)." ";
	$res = mysqli_query($connNew,$sql);
	
	if($res){
		return mysqli_insert_id($connNew);
	}
	else{
		debugData(mysqli_error($connNew),'DATA INSERTION FAILED');
		debugData($sql,'SQL QUERY');
	}
}

function updateData($table,$data=array(),$cond){
	global $appConnect;
	$fieldValuePairs=array();

	foreach ($data as $field => $value) {
		array_push($fieldValuePairs,$field."="."'".mysqli_real_escape_string($appConnect,$value)."'");
	}

	$sql = "UPDATE ".$table." SET ".implode(",",$fieldValuePairs)." WHERE ".$cond."";
	$res = mysqli_query($appConnect,$sql);
	
	if($res){
		return true;
	}
	else{
		debugData(mysqli_error($appConnect),'DATA UPDATION FAILED');
		debugData($sql,'SQL QUERY');
	}

}

function updateData1($table,$data=array(),$cond){
	global $connNew;
	$fieldValuePairs=array();

	foreach ($data as $field => $value) {
		array_push($fieldValuePairs,$field."="."'".mysqli_real_escape_string($connNew,$value)."'");
	}

	$sql = "UPDATE ".$table." SET ".implode(",",$fieldValuePairs)." WHERE ".$cond."";
	$res = mysqli_query($connNew,$sql);
	
	if($res){
		return true;
	}
	else{
		debugData(mysqli_error($connNew),'DATA UPDATION FAILED');
		debugData($sql,'SQL QUERY');
	}

}

function selectField($table,$field,$cond="WHERE",$conn=''){
	global $connNew;
	if($conn=='')
		$conn = $connNew;

	$sql = "SELECT  `".$field."` FROM ".$table." ".$cond." ";
	
	$res = mysqli_query($conn,$sql);
	return mysqli_fetch_object($res)->$field;
}

/***** MYSQLI DB FUNCTIONS END *****/


function breadCrumbs(){
	$currentNav = currentNavigation();
	$breadCrumbs='<ol class="breadcrumb">
        			<li><i class="fa fa-dashboard"></i> Home</li>
       				<li >'.$currentNav['module'].'</li>
       				<li>'.$currentNav['menu'].'</li>
        			<li>'.$currentNav['submenu'].'</li>
      			 </ol>';
	return $breadCrumbs;
}

function currentNavigation(){
	
	/*
	return array having module name,module color,menu name,sub menu name
	*/

	global $appConnect;
	//echo $_SERVER['HTTP_REFERER'];

	$returnData = array();	
	$referPage = strtok(strtoupper(basename($_SERVER['HTTP_REFERER'])),'?');

		
	if(isset($_REQUEST['submenu']))
		$_SESSION['submenu']=@$_REQUEST['submenu'];
	
	
	//$submenu = $_SESSION['submenu'];
    $submenu = $session;

	if($_SESSION['submenu']!=''){
		 $pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE id='".$_SESSION['submenu']."' ";
	}
	else{
		$pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE UPPER(file_name)='".$referPage ."' ";
	}

	$pageChkRes = mysqli_query($appConnect,$pageChkSql);

	if(mysqli_num_rows($pageChkRes) > 0){
		$row = mysqli_fetch_object($pageChkRes);

		$returnData['color']   = ucwords(strtolower(selectField(APP_MODULE,'color','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['icon']    = selectField(APP_MODULE,'icon','WHERE id="'.$row->id_module.'"',$appConnect);
		$returnData['module']  = ucwords(strtolower(selectField(APP_MODULE,'name','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['menu']    = ucwords(strtolower(selectField(APP_MENU,'name','WHERE id="'.$row->id_menu.'"',$appConnect)));
		$returnData['submenu'] = ucwords(strtolower($row->name));

		$_SESSION['submenu']     = $row->id;
		$_SESSION['id_document'] = $row->id_document;
	}
	else{
		$returnData['color']   = '';
		$returnData['icon']    = '';
		$returnData['module']  = '';
		$returnData['menu']    = '';
		$returnData['submenu'] = '';
	}
	//var_dump($returnData);exit;
	return $returnData;
}



//id_document
function currentNavigation_s($session){
	/*
	return array having module name,module color,menu name,sub menu name
	*/

	global $appConnect;
	//echo $_SERVER['HTTP_REFERER'];

	$returnData = array();	
	$referPage = strtok(strtoupper(basename($_SERVER['HTTP_REFERER'])),'?');

		
	if(isset($_REQUEST['submenu']))
		$_SESSION['submenu']=@$_REQUEST['submenu'];
	
	$submenu = $session;

	if($session!=''){
		 $pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE id_document='".$session."' ";
	}
	else{
		 $pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE UPPER(file_name)='".$referPage ."' ";
	}

	$pageChkRes = mysqli_query($appConnect,$pageChkSql);

	if(mysqli_num_rows($pageChkRes) > 0){
		$row = mysqli_fetch_object($pageChkRes);

		$returnData['color']   = ucwords(strtolower(selectField(APP_MODULE,'color','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['icon']    = selectField(APP_MODULE,'icon','WHERE id="'.$row->id_module.'"',$appConnect);
		$returnData['module']  = ucwords(strtolower(selectField(APP_MODULE,'name','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['menu']    = ucwords(strtolower(selectField(APP_MENU,'name','WHERE id="'.$row->id_menu.'"',$appConnect)));
		$returnData['submenu'] = ucwords(strtolower($row->name));

		$_SESSION['submenu']     = $row->id;
		$_SESSION['id_document'] = $row->id_document;
	}
	else{
		$returnData['color']   = '';
		$returnData['icon']    = '';
		$returnData['module']  = '';
		$returnData['menu']    = '';
		$returnData['submenu'] = '';
	}

	//var_dump($returnData);exit;
	return $returnData;
}



//Copy
function currentNavigation_id($sessionid){
	
	/*
	return array having module name,module color,menu name,sub menu name
	*/

	global $appConnect;
	//echo $_SERVER['HTTP_REFERER'];

	$returnData = array();	
	$referPage = strtok(strtoupper(basename($_SERVER['HTTP_REFERER'])),'?');

		
	if(isset($_REQUEST['submenu']))
		$_SESSION['submenu']=@$_REQUEST['submenu'];
	
	
    $submenu = $session;
	//echo $session;

	if($sessionid!=''){
		$pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE id='".$sessionid."' ";
	}
	else{
		 $pageChkSql = "SELECT * FROM ".APP_SUB_MENU." WHERE UPPER(file_name)='".$referPage ."' ";
	}

	$pageChkRes = mysqli_query($appConnect,$pageChkSql);

	if(mysqli_num_rows($pageChkRes) > 0){
		$row = mysqli_fetch_object($pageChkRes);

		$returnData['color']   = ucwords(strtolower(selectField(APP_MODULE,'color','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['icon']    = selectField(APP_MODULE,'icon','WHERE id="'.$row->id_module.'"',$appConnect);
		$returnData['module']  = ucwords(strtolower(selectField(APP_MODULE,'name','WHERE id="'.$row->id_module.'"',$appConnect)));
		$returnData['menu']    = ucwords(strtolower(selectField(APP_MENU,'name','WHERE id="'.$row->id_menu.'"',$appConnect)));
		$returnData['submenu'] = ucwords(strtolower($row->name));

		$_SESSION['submenu']     = $row->id;
		$_SESSION['id_document'] = $row->id_document;
	}
	else{
		$returnData['color']   = '';
		$returnData['icon']    = '';
		$returnData['module']  = '';
		$returnData['menu']    = '';
		$returnData['submenu'] = '';
	}

	//var_dump($returnData);exit;
	return $returnData;
}



function currenDateTime(){
	return date("Y-m-d H:i:s");
}
function executeSql($sql){
	global $connNew;
	$resSql = @mysqli_query($connNew, $sql) or die(" SQL synatx error :<br> SQL : <font color=red>".$sql."</font><br>Error : ".mysql_error());
	return $resSql;
}

function fetch_object($data){
	global $connNew;
	$rowSql = @mysqli_fetch_object($connNew, $data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function fetch_assoc($data){
	global $connNew;
	$rowSql = @mysqli_fetch_assoc($connNew, $data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function fetch_array($data){
	global $connNew;
	$rowSql = @mysqli_fetch_array($connNew, $data) or die(" SQL synatx error :<br> SQL : <font color=red>".$data."</font><br>Error : ".mysql_error());
	return $rowSql;
}
function num_rows($data){
	global $connNew;
	$rowSql = @mysqli_num_rows($connNew, $data);
	return $rowSql;
}

function debugData($data,$text=''){
	echo "<pre style='background-color:#252525;color:yellow;'>---".$text.'---';
	print_r($data);
	echo "</pre>";
}

function updateOrder($table,$id,$value){
	global $connNew;
	$sql="UPDATE ".$table." set display_order='".$value."',last_modified='".date('Y-m-d H:i:s')."',id_mst_user_modified_by='".$_SESSION['userId']."' WHERE id='".$id."' ";
	mysqli_query($connNew,$sql);
}

function getInitials($str) {
    $ret = '';
    foreach (explode(' ', $str) as $word)
        $ret .= strtoupper($word[0]);
    return $ret;
}
//----------------------------------------------------------------------------------
function selectSql($tableName = '', $where = '', $limit = ''){
	global $connNew;
	$sqlTable = "SELECT * FROM ".$tableName."";
	if($where != ''){
		$sqlTable .= " ".$where;
	}	
	if($limit != ''){
		$sqlTable .= " ".$limit;
	}
	//echo $sqlTable;

	$resTable = @mysqli_query($connNew, $sqlTable) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlTable."</font><br>Error : ".mysqli_error());
	return 	$resTable;
}


//----------------------------------------------------------------------------------
function selectColumn($tableName = '', $columnName = '' , $where = '',$limit=''){
	global $connNew;
	$sqlColumn = "SELECT ".$columnName." FROM ".$tableName."";
	if($where != ''){
		$sqlColumn .= " ".$where;
	}	
	if($limit != ''){
		$sqlColumn .= " ".$limit;
	}
	//echo $sqlColumn;
	$resColumn = @mysqli_query($connNew, $sqlColumn) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlColumn."</font><br>Error : ".mysqli_error());
	$resultColumn =  @mysqli_fetch_assoc($resColumn);
	return 	$resultColumn["$columnName"];
}
//----------------------------------------------------------------------------------
function selectRow($tableName = '', $where = ''){
	global $connNew;
	$sqlRow = "SELECT * FROM ".$tableName."";
	if($where != ''){
		$sqlRow .= " ".$where;
	}	
	$sqlRow .= " LIMIT 0,1";
	
	//echo $sqlRow;
	$resRow = @mysqli_query($connNew, $sqlRow) or die(" SQL synatx error :<br> SQL : <font color=red>".$sqlColumn."</font><br>Error : ".mysqli_error());
	$resultRow =  @mysqli_fetch_assoc($resRow);
	return 	$resultRow;
}
//----------------------------------------------------------------------------------
function outputHtml($resource = '',$htmlStructure = '', $replaceVariable = array()){

	$outputHtml = $htmlStructure;
	if(is_resource($resource)){
		if(@mysqli_num_rows($resource)){
			if($outputHtml != '' && count($replaceVariable)){
				$outPutRep = '';
				while($resultSqlArray =  @mysqli_fetch_assoc($resource)){
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

/*function ReplaceContent($VarList) {
	for($i=0; $i<count($VarList); $i++) {
		global $$VarList[$i];
		$$VarList[$i] = preg_replace("/__(\w+)__/e","\$GLOBALS['$1']",$$VarList[$i]);
	}
	return 1;
	// For Future Refrence :  $RIGHT_HOME_CONTENT=preg_replace("/__(\w+)__/e","$$1",$RIGHT_HOME_CONTENT);
}*/

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

	echo '<img src="'.$SITE_URL.'/images/error.gif" align="absmiddle" hspace="3"> '.$msg;

}
function messageSuc($msg)
{
	global $SITE_URL;

	echo '<img src="'.$SITE_URL.'/images/success.gif" align="absmiddle" hspace="3"> '.$msg;

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
	global $connNew;
	if(!isset($_SESSION['sessstatic']))
	{
	$ip=$_SERVER['REMOTE_ADDR'];
		$sql = "insert into ".TBL_STAT."(ip,date_added)values('".$ip."','".currdate_time()."')";
		//echo"sql--$sql<br>";
		if(mysqli_query($connNew, $sql))
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
