<?php include_once("../../config/auto_loader.php");



$id=$_POST['id'];
$autoload=$_POST['autoload'];

				$selectnew="SELECT * FROM ".TBL_COMPANY."  where name like '%".$autoload."%' and id_mst_attributes_company_group=$id  ";
				$resnew = mysqli_query($connNew,$selectnew);
				$output = '<ul class="list-unstyled autoul">';		

  				if ($resnew->num_rows > 0) {
					while($rownew = mysqli_fetch_object($resnew)){ 
						$output .= '<li class="auto">'.ucwords($rownew->name).' - '.ucwords($rownew->city).'</li>';
					}	
				}else{
  			 		$output .= '<li> List not Found</li>';
  				}
				$output .= '</ul>';
	  			echo $output;












/* old query
	 $logincheck=adminLoginCheck(1);     
	 if($logincheck==1){
    $key=$_GET['q'];
    $response = array();
   $SQL	= "
select *  from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !='' AND name LIKE '{$key}%'  order BY name LIMIT 0,25
";
	
	
	$query=mysqli_query($connNew, $SQL);
	
	//$query=mysqli_query($connNew, "select * from ".TBL_COMPANY." where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and name !=''  AND name LIKE '%{$key}%' order BY name LIMIT 0,25");
	
    while($row=mysqli_fetch_assoc($query))
    {
      
	  $response[] = array('id'=>$row['id_company'], 'text'=>$row['name'].' - '.$row['city']);
	  
	  //array("value"=>$row['id_company'],"label"=>$row['name'].' - '.ucfirst($row['city']));
	 			
    }
	
    echo json_encode($response);
	 }else{
		 
		 $response[0] = array('id'=>0, 'text'=>'Session expired');
		     echo json_encode($response);
	 }  */
	   ?>