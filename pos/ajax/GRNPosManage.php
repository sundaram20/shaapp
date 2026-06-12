<?php 


echo '1111';

/*?><?php include_once("../../config/auto_loader.php");

 $doc_type = $_POST["doc_type"];
 $po_date = date('Y-m-d' , strtotime(addslashes($_POST['po_date']))); 
 $date = date('Y-m-d');
 $status  =1;$idss = 0;
/*
 $sql4 = " SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."'  ";
	$db->query($sql4);  
	$numRows= $db->num_rows(); 
	while($row4 = $db->fetch_object()){	  
		if($row4->effective_date >= $date and $row4->effective_date <= $po_date){
			 $idss = $row4->id;
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

	$sql2 = " SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ";
	$db->query($sql2);  
	$numRows= $db->num_rows();

		if($numRows != 0){
			while($row2 = $db->fetch_object()){ 
				$po_no= $row2->doc_no;
				$po_no = $po_no + 1;
				$res['po_no'] = $po_no;
				$res['id_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['po_no'] = $start_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}

	 $sql3 = " SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ";
	$db->query($sql3);
	$numRows= $db->num_rows();

	if($numRows > 0){
		while($row3 = $db->fetch_object()){
			$po_no= $row3->doc_no;
			$po_no = $po_no + 1;
			$res['po_no'] = $po_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['po_no'] = $start_no;
		$res['id_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}
//echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND date(`effective_date`) <= date('".$date."') `doc_type`='".$doc_type."' AND  ORDER BY effective_date desc ";
//die;
 $sql4 = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND `doc_type`='".$doc_type."' AND date(`effective_date`) <= date('".$date."')   ORDER BY effective_date desc Limit 0,1 ");
	
	$numRows= mysqli_num_rows($sql4); 
	while($row4 = mysqli_fetch_object($sql4)){
		 // echo 'DATE='.$effective_date = $row4->effective_date;	
		   
		/*if($row4->effective_date >= $date && $row4->effective_date <= $po_date){
			 $idss = $row4->id;
			 	 echo 'ID<br>==='.$effective_date = $row4->effective_date;
		 }
		 if($row4->effective_date <= $date ){
			 $idss = $row4->id;
			 	 $effective_date = $row4->effective_date;
		 }
		 
	}


 $sql = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id` = '".$idss."' limit 1 ");
 $numRows =  mysqli_num_rows($sql);
	    while($row =  mysqli_fetch_object($sql)){ 
		 $idDocConfigDetail= $row->id;
			$method= $row->method; 
		//	echo "SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' limit 1 ";
					$sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
				      while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id= $rowDocConfigDetail->id_mst_doc_type_config; 
							//$method= $rowDocConfigDetail->method; 
							$start_no= $rowDocConfigDetail->start_no;
							$prefix= $rowDocConfigDetail->prefix; 
							$suffix= $rowDocConfigDetail->suffix;  
					  }
	    }

	if($numRows == 0){

		$sqls = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' ");
		    
		    while($rows =  mysqli_fetch_object($sqls)){   
		    	$idDocConfigDetail = $rows->id;
				
				$method= $rows->method; 
					$sqlDocConfigDetail = mysqli_query($connNew,"SELECT * FROM `".TBL_DOC_TYPE_CONFIG_DETAIL."` WHERE `id_mst_doc_type_config` = '".$idDocConfigDetail."' and `id_subsection`='".$id_subsection."' limit 1 ");
					 $numRowsDocConfigDetail =  mysqli_num_rows($sqlDocConfigDetail);
				      while($rowDocConfigDetail =  mysqli_fetch_object($sqlDocConfigDetail)){  
							$id = $rowDocConfigDetail->id_mst_doc_type_config; 
							
							$start_no = $rowDocConfigDetail->start_no;
							$prefix = $rowDocConfigDetail->prefix; 
							$suffix = $rowDocConfigDetail->suffix;  
					  }
		    }
	}

	if($method == '1'){
	$sql2 =  mysqli_query($connNew," SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ");    
	
	$numRows= mysqli_num_rows($sql2);

		if($numRows != 0){
			while($row2 = mysqli_fetch_object($sql2)){  
				$po_no= $row2->doc_no;
				$po_no = $po_no + 1;
				$res['po_no'] = $po_no;
				$res['id_doc_type_configuration'] = $id;
				$res['prefix'] = $prefix;
				$res['suffix'] = $suffix;
				$res['method'] = '1';
			}
		}
		else{
			$res['po_no'] = $start_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '1';
		}



}elseif($method == '2'){
	if($start_no == '0'){
		$start_no = $start_no + 1;
	}

	 $sql3 = mysqli_query($connNew," SELECT * FROM `".TBL_PURCH."` WHERE `id_shop` = '".addslashes($_SESSION['shop'])."' AND  `doc_type`='".$doc_type."' and `id_doc_type_configuration` = '".$id."' ");
	
	$numRows= mysqli_num_rows($sql3);

	if($numRows > 0){
		while($row3 = mysqli_fetch_object($sql3)){  
			$po_no= $row3->doc_no;
			$po_no = $po_no + 1;
			$res['po_no'] = $po_no;
			$res['id_doc_type_configuration'] = $id;
			$res['prefix'] = $prefix;
			$res['suffix'] = $suffix;
			$res['method'] = '2';
		}
	}else{
		$res['po_no'] = $start_no;
		$res['id_doc_type_configuration'] = $id;
		$res['prefix'] = $prefix;
		$res['suffix'] = $suffix;
		$res['method'] = '2';
	}
}

echo json_encode($res);
 empty($res);
?><?php */?>