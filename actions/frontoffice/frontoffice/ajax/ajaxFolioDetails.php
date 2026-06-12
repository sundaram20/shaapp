<?php
	extract($_POST);
	
	if($ID == 1){
		$data = array('Id'=>'1','Folio_no'=>'FO-89','Name'=>'Dipika Aloney','Check_in'=>'01-01-2020','Check_out'=>"03-01-2020",'Current_total'=>'23600','Amount_Received'=>'10000','Balance'=>'16600','Status'=>'1');
		echo json_encode($data);
	}else if($ID == 2){
		$data = array('Id'=>'1','Folio_no'=>'FO-89','Name'=>'Hitesh Aloney','Check_in'=>'01-01-2020','Check_out'=>"03-01-2020",'Current_total'=>'23600','Amount_Received'=>'10000','Balance'=>'16600','Status'=>'1');
		echo json_encode($data);
	}else if($ID == 3){
		$data = array('Id'=>'3','Folio_no'=>'FO-89','Name'=>'Sumit Kumar','Check_in'=>'01-01-2020','Check_out'=>"03-01-2020",'Current_total'=>'23600','Amount_Received'=>'10000','Balance'=>'16600','Status'=>'0');
		echo json_encode($data);
	}
	else if($ID == 4){
		$data = array('Id'=>'4','Folio_no'=>'FO-89','Name'=>'Sundaram','Check_in'=>'01-01-2020','Check_out'=>"03-01-2020",'Current_total'=>'23600','Amount_Received'=>'10000','Balance'=>'16600','Status'=>'0');
		echo json_encode($data);
	}
	else{
		
		$data = array("error"=>"sorry record not found");
		echo json_encode($data);
	}
?>