<?php 
	include_once("../../config/auto_loader.php");

	if(isset($_POST['countryId']) && $_POST['countryId'] != ''){

		$resCat = selectSql(TBL_STATE," where id_mst_country_lang='".$_POST['countryId']."' ",' ORDER BY `name` ');
		if($db->num_rows2($resCat)){
			while($resultCat = $db->fetch_object2($resCat)){
				echo  '<option value="'.htmlentities($resultCat->id_state).'">'.ucfirst($resultCat->name) .'</option>';
		}
		echo '<option value="10000">other</option>';
	}else{
		echo '<option value="notFound">Record not found</option>';
		echo '<option value="10000">other</option>';
	}
		   
	}


?>