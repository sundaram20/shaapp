<?php
include_once("../../config/auto_loader.php");

$id=$_REQUEST['id'];

$key=$_GET['search'];
if($_REQUEST['id_mst_company']!=''){
	$conn =" and `id_mst_company` = '".$_REQUEST['id_mst_company']."'";
	
	}
	
	if($key!=''){
	$conn =" AND first_name LIKE '{$key}%' ";
	
	}
//		$selectnew="SELECT * FROM ".TBL_COMPANY_CONTACTS."  where id_mst_company=$id";

		 $selectnew = "select *  from ".TBL_COMPANY_CONTACTS." where status='1'  and first_name !='' $conn order BY first_name LIMIT 0,12";

		//$romm[] = '<option>Select Booker Name</option>';

		$resnew = mysqli_query($connNew,$selectnew);
		$num_rows = mysqli_num_rows($resnew);
		if($num_rows >=1){
		
		while($rownew = mysqli_fetch_object($resnew)){
				$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$rownew->id_mst_attributes_title."'"); 
			$Name =$Title.ucwords($rownew->first_name).' '.ucwords($rownew->last_name);
			
			//$romm[] ='<option value="'.ucwords($rownew->id).'">'.ucwords($rownew->first_name).' - '.ucwords($rownew->email).' - '.ucwords($rownew->primary_mobile).'<option>';
			$romm[] = array('id'=>$rownew->id, 'text'=>$Name.' - '.ucwords($rownew->email).' - '.ucwords($rownew->primary_mobile));
					 
		}					
				

	echo json_encode($romm);
	}else{
		$romm[]=array('id'=>-1,'text'=>'No Records Found');
		echo json_encode($romm);
	}
 
	

	
 ?>


