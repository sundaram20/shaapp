<?php include_once("../config/auto_loader.php");

 $doc_type = $_POST["doc_type"];
 
  

$sql = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."'";
	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    if($numRows > 0){
		    while($row = $db->fetch_object()){   
	 			$date_hide = date('d-m-Y', strtotime($row->effective_date));
	 			$res['date'] = date('d-m-Y', strtotime('+1 day', strtotime($date_hide)));
		    }
		}else{
			$res['date'] = date('d-m-Y');
		}
 	echo json_encode($res);
 	empty($res['date']);
 	empty($date_hide);
?>