<?php include_once("../../config/auto_loader.php");

	//echo "hello"; 

	//$logincheck = adminLoginCheck(1);     
	//if($logincheck==1){
		//echo $logincheckm or die();
	    $key=$_GET['q'];
	    $response = array();
	   	$SQL = "select *  from ".TBL_GUEST." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and first_name !='' AND first_name LIKE '{$key}%' or email !='' AND email LIKE '{$key}%' or primary_mobile !='' AND primary_mobile LIKE '{$key}%' or primary_landline !='' AND primary_landline LIKE '{$key}%' or guest_reg_no !='' AND guest_reg_no LIKE '{$key}%' or city !='' AND city LIKE '{$key}%' order BY first_name LIMIT 0,5";
		
		$query=mysqli_query($connNew, $SQL);
		
		//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
		
	    while($row=mysqli_fetch_assoc($query))
	    {

	    	$phone = ($row['primary_contact_type'] == 1) ? $row['primary_mobile'] : $row['primary_landline'];
		  	$response[] = array('id'=>$row['id'], 'text'=>$row['guest_reg_no'] . ' - ' . $row['first_name'].' '. $row['last_name'].' - '.$row['email'].' - ' . $phone . ' - ' . $row['city']);
		  
		  //array("value"=>$row['id_company'],"label"=>$row['name'].' - '.ucfirst($row['city']));
		 			
	    }
		
	    echo json_encode($response);
	/* }else{
		 
		 $response[0] = array('id'=>0, 'text'=>'Session expired');
		     echo json_encode($response);
	 }*/
?>