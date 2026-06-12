<?php
include_once("../../config/auto_loader.php");

//$id=$_REQUEST['id'];
 
			

				//$key=$_GET['search'];
				
				//$selectnew="SELECT * FROM ".TBL_COMPANY."  where id_mst_attributes_company_group=$id and status = 1";
				//$selectnew = "select *  from ".TBL_COMPANY." where status='1' and `id_mst_attributes_company_group` = $id and name !='' AND name LIKE '{$key}%' order BY name LIMIT 0,2";
			//$romm[] =array('text'=>$title,  'selected'=>'selected');
			
			$key=$_REQUEST['searchValue'];
	$response = array();
	if($_REQUEST['id_mst_attributes_company_group']!=''){
	$id_company=$_REQUEST['id_mst_attributes_company_group'];
	//$id_mst_attributes_company_group=" AND `id_mst_attributes_company_group` = '".addslashes($id_company)."'";
	
	}
	if($key!=''){
		//$conn ="  AND name LIKE '{$key}%'";
	}
	
	
	if($_REQUEST['id_mst_company']!=''){
		$id_mst_company=" AND `id_company` = '".addslashes($_REQUEST['id_mst_company'])."'";
	}
	
	   $selectnew	= "
select *  from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  name !=' ' AND name LIKE '{$key}%'  order BY name LIMIT 0,50
";


		//	die;
			
				$resnew = mysqli_query($connNew,$selectnew);

					while($rownew = mysqli_fetch_object($resnew)){ 
						//$romm[]= '<option value="'.$rownew->id.'">'.ucwords($rownew->name).' - '.ucwords($rownew->city).'</option>';
						$romm[] = array('id'=>$rownew->id, 'text'=>$rownew->name.' - '.ucwords($rownew->city ));
					}	
					
	echo json_encode($romm);
	
 ?>


