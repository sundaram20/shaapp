<?php include_once("../config/auto_loader.php");

 $doc_type = $_POST["doc_type"];
 $doc_date = date('Y-m-d' , strtotime(addslashes($_POST['doc_date']))); 
 $date = date('Y-m-d');
 $status  =1;$idss = 0;

 $sql4 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' AND date(`effective_date`) <= date('".$date."')   ORDER BY effective_date desc Limit 0,1  ";
	$db->query($sql4);  
	$numRows= $db->num_rows(); 
	while($row4 = $db->fetch_object()){	  
		/*if($row4->effective_date >= $date and $row4->effective_date <= $doc_date){
			 $idss = $row4->id;
		 } */
		  if($row4->effective_date <= $date ){
			 $idss = $row4->id;
			 $effective_date = $row4->effective_date;
		 } 
	}

 $sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ";

//print_r($sql);

	    $db->query($sql); 
	    $numRows= $db->num_rows();
	    while($row = $db->fetch_object()){  
	    	$id= $row->id; 
	    	$method= $row->method; 
	    	$start_no= $row->start_no;
	    	$prefix= $row->prefix; 
		    $suffix= $row->suffix;  
	    }

	if($numRows == 0){

		 $sqls = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ";
		    $db->query($sqls);  
		    while($rows = $db->fetch_object()){  
		    	$id= $rows->id; 
		    	$method= $rows->method; 
		    	$start_no= $rows->start_no; 
		    	$prefix= $rows->prefix; 
		    	$suffix= $rows->suffix; 
		    }
	}
 
if($method == '1'){

	$sql2 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ";
	$db->query($sql2);  
	$numRows= $db->num_rows();

		if($numRows != 0){
			while($row2 = $db->fetch_object()){ 
				$doc_no= $row2->doc_no;
				$doc_no = $doc_no + 1;
				$res['doc_no'] = $doc_no;
				$res['id_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['doc_no'] = $start_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}

	$sql3 = " SELECT * FROM `".TBL_INV_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ";
	$db->query($sql3);
	$numRows= $db->num_rows();

	if($numRows > 0){
		while($row3 = $db->fetch_object()){
			$doc_no= $row3->doc_no;
			$doc_no = $doc_no + 1;
			$res['doc_no'] = $doc_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['doc_no'] = $start_no;
		$res['id_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}
echo json_encode($res);
 empty($res);
?>