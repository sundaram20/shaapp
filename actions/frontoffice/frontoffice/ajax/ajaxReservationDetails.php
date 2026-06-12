<?php
	include_once("../../config/auto_loader.php"); 
	extract($_POST);
	
	if($resID == 'wh201'){
		$data = array('Id'=>encryptor(encrypt,'12'),'res_id'=>'wh201','booking_date'=>'15-04-2020','Hotel'=>'Landmark','Room'=>'101','Guest'=>'shubhi','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Email'=>'shubhi@gmail.com');
		echo json_encode($data);
	}else if($resID == 'wh202'){
		$data = array('id'=>'2','res_id'=>'wh202','booking_date'=>'15-04-2020','Hotel'=>'RoomStatus','Room'=>'102','Guest'=>'Hitesh Aloney','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Email'=>'hitesh@gmail.com');
		echo json_encode($data);
	}else if($resID == 'wh203'){
		$data = array('id'=>'3','res_id'=>'wh203','booking_date'=>'18-04-2020','Hotel'=>'HeritageHotel','Room'=>'103','Guest'=>'Sumit Kumar','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Rooms'=>'3','Email'=>'sumit@gmail.com');
		echo json_encode($data);
	}
	else if($resID == 'wh204'){
		$data = array('Id'=>encryptor(encrypt,'21'),'res_id'=>'wh204','booking_date'=>'17-04-2020','Hotel'=>'RoomStatus','Room'=>'104','Guest'=>'Sundaram','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Email'=>'sundaram@gmail.com');
		echo json_encode($data);
	}
	else if($resID == 'wh205'){
		$data = array('id'=>'5','res_id'=>'wh205','booking_date'=>'16-04-2020','Hotel'=>'Landmark','Room'=>'101','Guest'=>'Shafeer','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Email'=>'shafeer@gmail.com');
		echo json_encode($data);
	}
	else if($resID == 'wh206'){
		$data = array('id'=>'6','res_id'=>'wh206','booking_date'=>'15-04-2020','Hotel'=>'RoomStatus','Room'=>'101','Guest'=>'Vipin','CheckIn'=>'2020-05-01','CheckOut'=>'2020-05-02','Email'=>'vipin@gmail.com');
		echo json_encode($data);
	}
	else{
		
		$data = array("error"=>"sorry record not found");
		echo json_encode($data);
	}
?>