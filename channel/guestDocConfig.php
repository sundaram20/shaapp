<?php 

 $doc_type = '501';
 $guest_reg_date = date('Y-m-d');
 $date = date('Y-m-d');
 $status  =1;
 $idss = 0;

 $sql4 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."'  "; 
	$db->query($sql4);  
	$numRows= $db->num_rows(); 
	while($row4 = $db->fetch_object()){	  
		if(($row4->effective_date <= $date  || $row4->effective_date >= $date) && $row4->effective_date <= $guest_reg_date){
			$idss = $row4->id;
		 } 
	}

$sql = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ";

//echo $sql or  exit();

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

		$sqls = "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' ";
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

	$sql2 = " SELECT * FROM `".TBL_GUEST."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id_mst_doc_type_configuration` = '".$id."' ";
	$db->query($sql2);  
	$numRows= $db->num_rows();

		if($numRows != 0){
			while($row2 = $db->fetch_object()){ 
				$doc_no = $row2->doc_no;
				$doc_no  = $doc_no + 1;
				$guestResultConfig['doc_no'] = $doc_no;
				$guestResultConfig['id_mst_doc_type_configuration'] = $id;
				$guestResultConfig['prefix'] = $prefix;
				$guestResultConfig['suffix'] = $suffix;
				$guestResultConfig['method'] = '1';
			}
		}
		else{
			$guestResultConfig['doc_no'] = $start_no;
			$guestResultConfig['id_mst_doc_type_configuration'] = $id;
			$guestResultConfig['prefix'] = $prefix;
			$guestResultConfig['suffix'] = $suffix;
			$guestResultConfig['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}

	$sql3 = " SELECT * FROM `".TBL_GUEST."` WHERE `id_shop` = '".addslashes($id_shop)."' AND  `doc_type`='".$doc_type."' and `id_mst_doc_type_configuration` = '".$id."' ";
	$db->query($sql3);
	$numRows= $db->num_rows();

	if($numRows > 0){
		while($row3 = $db->fetch_object()){
			$doc_no = $row3->doc_no;
			$doc_no  = $doc_no + 1;
			$guestResultConfig['doc_no'] = $doc_no;
			$guestResultConfig['id_mst_doc_type_configuration'] = $id;
			$guestResultConfig['prefix'] = $prefix;
			$guestResultConfig['suffix'] = $suffix;
			$guestResultConfig['method'] = '2';
		}
	}else{
		$guestResultConfig['doc_no'] = $start_no;
		$guestResultConfig['id_mst_doc_type_configuration'] = $id;
		$guestResultConfig['prefix'] = $prefix;
		$guestResultConfig['suffix'] = $suffix;
		$guestResultConfig['method'] = '2';
	}
}
?>