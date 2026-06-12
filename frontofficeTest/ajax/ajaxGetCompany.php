<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//echo "SELECT * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  name !=' ' and `id` = '".addslashes($_REQUEST['id_mst_company'])."'  ";
$resContact =  mysqli_query($connNew,"SELECT * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  name !=' ' and `id` = '".addslashes($_REQUEST['id_mst_company'])."'  ");
if(mysqli_num_rows($resContact) > 0){	
	
		$rowContact =  mysqli_fetch_object($resContact);	
			
			
													echo '<option value="'.$rowContact->id.'" selected="selected">'.ucwords($rowContact->name).'</option>';
													
			}
?>