<?php include_once("../../config/auto_loader.php");

	//echo "hello"; 

	//$logincheck = adminLoginCheck(1);     
	//if($logincheck==1){
		//echo $logincheckm or die();
	    $key=$_GET['q'];
	    $response = array();
	   	//$SQL = "select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$key}%'  or primary_mobile !='' AND primary_mobile LIKE '{$key}%' or primary_landline !='' AND primary_landline LIKE '{$key}%'  order BY name LIMIT 0,25";
		
		$SQL = "select *  from ".TBL_HOTELS." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$key}%'  or city !='' AND city LIKE '{$key}%'  order BY name LIMIT 0,25";
		
		
		$query=mysqli_query($connNew, $SQL);

		//echo $SQL; die;
		
		//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
		
	    while($row=mysqli_fetch_assoc($query))
	    {

	    	//$phone = ($row['primary_contact_type'] == 1) ? $row['primary_mobile'] : $row['primary_landline'];
		  	$response[] = array('id'=>$row['id'], 'text'=> $row['name'].' - '.ucfirst($row['city']));
		  
		  //array("value"=>$row['id_company'],"label"=>$row['name'].' - '.ucfirst($row['city']));
		 			
	    }
		
	    echo json_encode($response);
	/* }else{
		 
		 $response[0] = array('id'=>0, 'text'=>'Session expired');
		     echo json_encode($response);
	 }*/
?>