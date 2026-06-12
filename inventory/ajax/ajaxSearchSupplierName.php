<?php include_once("../../config/auto_loader.php");



	 //$logincheck=adminLoginCheck(1);     
	 //if($logincheck==1){
    $key=$_GET['q'];
	$response = array();
	if($_GET['id_mst_party_supplier']!=''){
	$id_mst_party_supplier=$_GET['id_mst_party_supplier'];
	$id_suppliersql=" AND `id` = '".addslashes($id_mst_party_supplier)."'";
	 $SQL	= "
select *  from ".TBL_PARTY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  company_name !=' ' ".$id_suppliersql."  order BY `company_name` LIMIT 0,25
";
}else{
	 $SQL	= "
select *  from ".TBL_PARTY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and company_name !='' AND company_name LIKE '{$key}%'  order BY `company_name` LIMIT 0,25
";
	}
    
   
	
	
	$query=mysqli_query($connNew, $SQL);
	
	//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
	
    while($row=mysqli_fetch_assoc($query))
    {
       $CityName =   $row['city']==''?'':' - '.ucwords($row['city']);
	  $response[] = array('id'=>$row['id'], 'text'=>$row['company_name'].$CityName);
	  
	  //array("value"=>$row['id_company'],"label"=>$row['name'].' - '.ucfirst($row['city']));
	 			
    }
	
    echo json_encode($response);
	 //}else{
		 
		// $response[0] = array('id'=>0, 'text'=>'Session expired');
		 //    echo json_encode($response);
	 //}
	   ?>