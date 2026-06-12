<?php
	extract($_POST);

	if($ID == 1){
		$data = array('Fname'=>'Deepika','Lname'=>'Aloney','Mobile'=>"9119677777",'Phone_no'=>'22345667','Email'=>'deepika@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}else if($ID == 2){
		$data = array('Fname'=>'Hitesh','Lname'=>'Aloney','Mobile'=>"9119670225",'Phone_no'=>'22345667','Email'=>'hitesh@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}else if($ID == 3){
		$data = array('Fname'=>'Sumit','Lname'=>'Kumar',"Mobile"=>"9119670225",'Phone_no'=>'22345667','Email'=>'sumit@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}else if($ID == 4){
		$data = array('Fname'=>'Sundaram','Lname'=>'Kumar',"Mobile"=>"9336789078",'Phone_no'=>'22345667','Email'=>'sundaram@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}else if($ID == 5){
		$data = array('Fname'=>'Shafeer','Lname'=>'Ahmad',"Mobile"=>"9119670225",'Phone_no'=>'22345667','Email'=>'shafeer@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}else if($ID == 6){
		$data = array('Fname'=>'Vipin','Lname'=>'Kumar',"Mobile"=>"9336789078",'Phone_no'=>'22345667','Email'=>'vipin@gmail.com','Address'=>'Chhatarpur','City'=>'Delhi','Country'=>'India');
		echo json_encode($data);
	}
	else{
		
		$data = array("error"=>"sorry record not found");
		echo json_encode($data);
	}
?>