<?php
include_once("../../config/auto_loader.php");
$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);

	if($_REQUEST['comId'] !=0 && $_REQUEST['comId']!=''){
		$sql="SELECT * FROM `".TBL_PARTY."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  id='".$_REQUEST['comId']."' ";
		$res = mysqli_query($conn,$sql);


		

		if($res){
			$data = mysqli_fetch_object($res);
				if($data->ledger == '1'){
			                 			$categoryDropDown = 'Local';
			                 		}else{
			                 			$categoryDropDown = 'Interstate';
			                 		}
			                 			
		                  	// $country_id = selectColumn(TBL_PARTY,'id_mst_attributes_country'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$_REQUEST[]."'");
			//print_r($country_id);	
		  // $res2 = mysqli_query($conn,$country_id);
				 $country_id = selectColumn(TBL_PARTY,'id_mst_state'," WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `id` = '".$_REQUEST['comId']."'");
		   	  $resCat = selectSql(TBL_STATE,"where  status = '1' and id_state = '".$data->id_mst_state."'   ",' ');
 			
		   	  $resultCat = $db->fetch_object2($resCat);
		   //$data2 = mysqli_fetch_object($res2);
         

			if($data->company_name==""){
				//echo "Executive not assigned<br>";
				echo  '<div class="col-form-label">Address : '.$data->address.' ,'.$data->city.'-'.$data->postcode.' ,'.$data->city.'<br>State - '.$resultCat->name.' , '.$categoryDropDown.'<br> Email : '.$data->email.'<br>Mobile : '.$data->mobile.'<br>GST No : '.$data->gstin.'</div>';
			}
			else{
				//echo "Sales Executive : ".$data->name."<br>";
				//echo "Company Details: ".$data->company_name;
				//	echo  '<div class="col-form-label">Address : '.$data->address.' ,'.$data->city.'-'.$data->postcode.' ,'.$data->city.'<br>'.$data->id_mst_attributes_state.'<br> Email : '.$data->email.'<br>Mobile : '.$data->mobile.'<br>GST No : '.$data->gstin.'</div>';
					echo  '<div class="col-form-label">Address : '.$data->address.' ,'.$data->city.'-'.$data->postcode.' ,'.$data->city.'<br>State - '.$resultCat->name.' , '.$categoryDropDown.'<br> Email : '.$data->email.'<br>Mobile : '.$data->mobile.'<br>GST No : '.$data->gstin.'</div>';
			}
		}	  
	}
	else{
		echo "";
	}


/*$conn = mysqli_connect($DB_HOST,$DB_USERNAME,$DB_PASSWORD,$DB_NAME);
	if(isset($_REQUEST['id_company']) && $_REQUEST['id_company']!=''){
		$_REQUEST['areaId']=selectColumn(TBL_COMPANY,'area','WHERE id_company="'.$_REQUEST['id_company'].'" ');	
	}

	if($_REQUEST['areaId'] !=0 && $_REQUEST['areaId']!=''){
		$sql="SELECT B.name,A.description FROM `fs_areas_assign` AS A
			  LEFT JOIN `fs_users` AS B ON A.user_id=B.id
			  WHERE A.id='".$_REQUEST['areaId']."' ";
		$res = mysqli_query($conn,$sql);
		
		if($res){
			$data = mysqli_fetch_object($res);
			if($data->name==""){
				echo "Executive not assigned<br>";
				echo "Area Description : ".$data->description;
			}
			else{
				echo "Sales Executive : ".$data->name."<br>";
				echo "Area Description : ".$data->description;
			}
		}	  
	}
	else{
		echo "";
	}
	*/

?>