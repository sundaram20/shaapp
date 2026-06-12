<?php 
	include_once("../../config/auto_loader.php");

	if(isset($_POST['countryId']) && $_POST['countryId'] != ''){

		$resCat = selectSql(TBL_COUNTRY_LANG," where id_country='".$_POST['countryId']."' ",' ORDER BY `name` ');
		if($db->num_rows2($resCat)){
			$resultCat = $db->fetch_object2($resCat);
			if($resultCat->nationality != ''){
				echo  '<option value="'.htmlentities($resultCat->id_country).'">'.ucfirst($resultCat->nationality) .'</option>';
			}else{
				echo '<option value="notFound">Record not found</option>';
				echo '<option value="10000">other</option>';
			}
			//
		//echo '<option value="10000">other</option>';
		}
		   
	}


?>