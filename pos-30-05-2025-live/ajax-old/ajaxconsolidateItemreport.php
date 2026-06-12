<?php include_once("../../config/auto_loader.php");
?>
<?php 

if($_POST['group']==1){ 

		if($_POST['id_main_group']!=''){
					$SubMenuSql	="   AND  id_mst_attributes_group_main IN (".$_POST['id_main_group'].")";
					} 

		$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."'  $SubMenuSql GROUP BY id_mst_attributes_group_sub"); 
				  while($row = mysqli_fetch_object($resCat)){ 

		 		  $SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."'");					
						if(mysqli_num_rows($SqlAttrbute)){									  
								$resultAttrbute = mysqli_fetch_object($SqlAttrbute);										
								$subGroup .='<option '.$selected.' value="'.$resultAttrbute->id.'">'.ucfirst($resultAttrbute->field_value).'</option>';
						}
					}
		 echo $subGroup;

  }
if($_POST['group']==2){ 

		if($_POST['id_data_main_group']!=''){
					$SubMenuSql	="   AND  id_mst_attributes_group_main IN (".$_POST['id_data_main_group'].")";
					} 
		if($_POST['id_sub_group']!=''){
					$SubMenuSql	.="   AND  id_mst_attributes_group_sub IN (".$_POST['id_sub_group'].")";
					} 

		$resCat = selectSql(TBL_INV_ITEMS," where id_shop= '".addslashes($_SESSION['shop'])."'  $SubMenuSql "); 
				  while($row = mysqli_fetch_object($resCat)){ 

		 		  //$SqlAttrbute = selectSql(TBL_ATTRIBUTES,"where id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'item_group_sub' AND id= '".addslashes($row->id_mst_attributes_group_sub)."'");					
						//if($db->num_rows2($SqlAttrbute)){									  
								//$resultAttrbute = $db->fetch_object2($SqlAttrbute);										
								$subGroup .='<option '.$selected.' value="'.$row->id.'">'.ucfirst($row->name).'</option>';
						//}
					}
		 echo $subGroup;

  }



?>



	