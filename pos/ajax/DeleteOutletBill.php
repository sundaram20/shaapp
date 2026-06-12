<?php include_once("../../config/auto_loader.php");
$row_id=$_REQUEST['row_id'];
$main_id	=	$_REQUEST['main_id'];

$sql2 = " SELECT * FROM  pos_purch WHERE  `id`='".$main_id."'";
$db->query($sql2); 
while($row2 = $db->fetch_object()){ 
	$id_pos_details_split = $row2->id_pos_details_split; 
}
$arrayval = explode(',', $id_pos_details_split);
echo $arrayval;
$k=0;
for($i=0;$i<count($arrayval);$i++){
	if($arrayval[$i] == $row_id){}else{
		if($k==0){
			$main_array  = $arrayval[$i];
		}else{
			$main_array .= ','.''.$arrayval[$i];			
		}
		$k++;
	}

} 
 
			$editSql  = "  UPDATE `".TBL_PURCH."` SET
					`id_pos_details_split` = '".$main_array."'
					WHERE id='".$main_id."'   ";
			executeSql($editSql); 
			
			
			
			
			

echo json_encode($row_id);
//executeSql("DELETE from `".TBL_PURCH_DETAILS."` where `id`='".$row_id."' ");
 
?>

 





	
	