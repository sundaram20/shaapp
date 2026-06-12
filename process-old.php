<?php session_start();	
include("config/data.config.php");
include("$LIB_DIR/functions.library.php");
include("$LIB_DIR/msgs.inc.php");
include("$LIB_DIR/class.database.php");
include("$LIB_DIR/data.constant.php");


if($_REQUEST['process'] !='secureLogout'){
	

$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	 $sqlShopCodeChk = "SELECT * FROM ".APP_SHOP." WHERE shop_code= '".$_POST['shopCode']."' ";
	
	$resShopChk = mysqli_query($conn,$sqlShopCodeChk);

	if($resShopChk && mysqli_num_rows($resShopChk) == 1){
		
		$dataShopChk = mysqli_fetch_object($resShopChk);
		
		$_SESSION['database'] = $DB_NAME	=	$dataShopChk->database;
		$_SESSION['user_name'] = $DB_USERNAME	=	$dataShopChk->user_name;
		$_SESSION['password'] = $DB_PASSWORD	=	$dataShopChk->password;
		$_SESSION['module_access']	=	$dataShopChk->module_access;
		$_SESSION['shop_code']	= $dataShopChk->shop_code;
		
		$process = $_REQUEST['process'];
		mysqli_close($conn);
		
		$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
		
		$connNew = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME);

		$db->open() or die($db->error());

	}
	else{
		$_SESSION['errorMsg']=$_POST['shopCode'].' '.' incorrect shop code !';
		mysqli_close($conn);
		header("location:index.php");
		exit;
	}
}
else{
	$process = $_REQUEST['process'];
	$DB_NAME;
	$db=new DbConnect($DB_HOST, $DB_USERNAME, $DB_PASSWORD, $DB_NAME, $DB_REPORT_ERROR, $DB_PERSISTENT_CONN);
	$db->open() or die($db->error());
}	


switch($process){
    case "login": 
   		/*if($_SESSION['security_number'] != $_POST['secure']){
			 $_SESSION['sessMsg'] = 50;
			 header("location:index.php");
			 exit;
		}else if($_POST['secure'] == ''){
			 $_SESSION['sessMsg'] = 50;
			 header("location:index.php");
			 exit;
		}  */
       	$query = "	SELECT * FROM ".TBL_USERS." 
					WHERE `email` = '".addslashes($_POST['email'])."' 
					AND `password` = '".encrypt($_POST['password'])."'";
		$db->query($query);
		if($db->num_rows()>0){			
			$row = $db->fetch_array(); 
			$_SESSION['userId'] = $row['id'];
			$_SESSION['sessAdminUsername'] = $row['email'];
			$_SESSION['sessAdminType'] = $row['email'];
			$query = "	UPDATE ".TBL_USERS." 
						SET `lastlogin` = '".currenDateTime()."' 
						WHERE `id` = '".$row['id']."'";
			$db->query($query);
			header("location:home.php");
			exit;
		}else{
			$_SESSION['sessMsg'] = 50;
			header("location:index.php");
		}
	break;
	case "changePassword":
		$query = "	SELECT * FROM ".TBL_USERS." 
					WHERE  `password` = '".encrypt($_POST['oldPassword'])."'";
		$db->query($query);
		if($db->num_rows()>0){	
			$row=$db->fetch_array();
			$query = "	UPDATE ".TBL_USERS." SET 
						`password` = '".encrypt($_POST['newPassword'])."'  
						WHERE `id` = '".$row['id']."'";
			$db->query($query);
			$_SESSION['sessSucMsg'] = 52;
			header("location:changePassword.php");
			exit;
		}else{
			$_SESSION['sessErrorMsg'] = 53;
			header("location:changePassword.php");
			exit;
		}
	break;
	
	case "changeEmail":
		$query = "SELECT * FROM ".TBL_USERS." WHERE `id` = '".$_SESSION['userId']."'"   ;
		$db->query($query);
		if($db->num_rows()>0){	
			$row = $db->fetch_array();
			if($row['email'] == $_POST['oldEmail']){
				$query = "UPDATE ".TBL_USERS." SET `email` = '".$_POST['newEmail']."' WHERE `id` = '".$row['id']."'";
				$db->query($query);
				$_SESSION['sessSucMsg'] = 54;
				header("location:changeEmail.php");
				exit;
			}else{
				$_SESSION['sessErrorMsg'] = 55;
				header("location:changeEmail.php");
				exit;
			}
		}else{
			header("location:index.php");
			exit;
		}
	break;

	case "logout":
				$_SESSION['userid']="";
				$_SESSION['username']="";
				session_destroy();
				header("location:index.php");
	break;
	
	case "secureLogin":
	
		$err = 0;
		if($_POST['username'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= 'Please enter username.';
		}
		if($_POST['password'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= ' Please enter password.';
		}
		/*if($_POST['secure'] == ''){
			$err++;
			$_SESSION['errorMsg'] .= ' Please enter security code.';
		}elseif($_SESSION['security_number'] != $_POST['secure']){
			$err++;
			$_SESSION['errorMsg'] .= ' Invalid security code. Please try again.';
		} */
		if($err == 0){
			if(($_POST['process'] == 'secureLogin') && $_POST['submit']   && $_POST['g-recaptcha-response']){
              


				$sqlLogin = "SELECT * FROM `".TBL_USERS."` WHERE `user_name` = '".addslashes($_POST['username'])."' AND `password` = '".base64_encode($_POST['password'])."' AND `status` = '1'";

				$resLogin = @mysqli_query($connNew, $sqlLogin);

				$numLogin = @mysqli_num_rows($resLogin);
				if($numLogin > 0){
					$resultLogin = @mysqli_fetch_assoc($resLogin);
					$_SESSION['shop'] = $resultLogin['id_shop'];
					$_SESSION['userName'] = $resultLogin['user_name'];
					$_SESSION['userId'] = $resultLogin['id'];
					$_SESSION['userEmail'] = $resultLogin['email'];
					$_SESSION['userLevel'] = $resultLogin['id_mst_user_levels'];
					$_SESSION['userLastLogin'] = $resultLogin['last_login'];
					$_SESSION['hotel_access'] = $resultLogin['ids_hotel_access'];
					$_SESSION['outlet_access'] = $resultLogin['ids_mst_outlet'];
					$_SESSION['sessionId'] = session_id(); 

					$printerSql = "SELECT id FROM ".TBL_ATTRIBUTES." WHERE status = '1' AND `table_name` = 'printer' AND  FIND_IN_SET ('".$_SESSION['userId']."',`ids_mst_user`)  AND id_shop= ".$_SESSION['shop']."";

$resPrinter =  mysqli_query($connNew,$printerSql);

$printerArray=array();

while($rowPrinter=mysqli_fetch_object($resPrinter)){
array_push($printerArray,$rowPrinter->id);
}

$_SESSION['userPrinter']=implode(',',$printerArray);
					
					
					if($_SESSION['userLevel']!=1){
						$_SESSION['module_access']=selectColumn(TBL_USER_LEVELS,'ids_module_access','WHERE id="'.$_SESSION['userLevel'].'" ');
					}
					
					@mysqli_query($connNew, "UPDATE `".TBL_USERS."` SET `last_login` = '".currenDateTime()."', `id_session` = '".$_SESSION['sessionId']."', ip_address='".ipCheck()."', browser='".$_SERVER['HTTP_USER_AGENT']."' WHERE `id` = '".$_SESSION['userId']."' AND `user_name` = '".$_SESSION['userName']."'");
					$_SESSION['successMsg'] = 'You have been sucessfully logged in.';

					header('location:dashboard.php');
					exit;
				}else{
					$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
					header("location:index.php");
					exit;
				}
			}else{
				if(empty($_POST['g-recaptcha-response'])){
					 $_SESSION['errorMsg'] = 'Unable to verify';
						header("location:index.php");
							//alert('hi');
					//exit;
					
				

				}
				else{
					$_SESSION['errorMsg'] = 'Invalid login details. Please try again.';
				header("location:index.php");
				exit;
				}
				
			}
		}else{
			header("location:index.php");
			exit;
		}
	break;
	case "secureLogout":
		$sqlLogout ="UPDATE `".TBL_USERS."` SET `last_logout` = '".currenDateTime()."', `id_session` = '' WHERE `id` = '".$_SESSION['userId']."' AND `user_name` = '".$_SESSION['userName']."'";
		
		@mysqli_query($connNew, $sqlLogout);
		unset($_SESSION['userName']);
		unset($_SESSION['userId']);
		unset($_SESSION['userEmail']);
		unset($_SESSION['userLevel']);
		unset($_SESSION['userLastLogin']);
		unset($_SESSION['sessionId']);
		unset($_SESSION['HotelUserPermission']);
		unset($_SESSION['HotelPerHotel']);
		unset($_SESSION['database']);
		unset($_SESSION['shop_code']);
		unset($_SESSION['module_access']);
		unset($_SESSION['userPrinter']);
		unset($_SESSION['ActiveListHotelPerLogin']);
		$_SESSION['successMsg'] = 'You have been sucessfully logged out.';
		mysqli_close($conn);
		header("location:index.php");
		exit;
	break;	
}?>