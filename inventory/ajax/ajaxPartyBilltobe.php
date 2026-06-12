<?php
	include_once("../../config/auto_loader.php");

	  $resCat = selectSql(TBL_PARTY,"where id_shop='".$_SESSION['shop']."' and status = '1'",' ORDER BY `company_name`');
								  if($db->num_rows2($resCat)){
								  	while($resultCat = $db->fetch_object2($resCat)){
										if($_REQUEST['Id'] == $resultCat->id){
											$selected = 'selected="selected"';
										}else{
											$selected = '';
										}
										$categoryDropDown .= '<option '.$selected.' value="'.$resultCat->id.'">'.ucfirst($resultCat->company_name).' - '.ucfirst($resultCat->city).'</option>';
									}
								  }
								 	echo $categoryDropDown;
								  ?>



