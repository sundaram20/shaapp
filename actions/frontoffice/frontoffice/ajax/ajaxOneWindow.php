<?php
include_once("../../config/auto_loader.php");
//echo "Hello";
 $guestName=$_REQUEST['guestname'];
 $checkIn=$_REQUEST['res_checkin'];
 $checkOut=$_REQUEST['res_checkout'];
$insertGrid = "INSERT INTO ".FO_RESERVATIONS."  SET
					
					`guest_Name`='".$guestName."',
					`check_In`='".$checkIn."',
					`check_Out`='".$checkOut."' ";
					
		mysqli_query($connNew,$insertGrid);
		

$sql = "SELECT * FROM ".FO_RESERVATIONS." ";

$res = mysqli_query($connNew,$sql);

while($row=mysqli_fetch_object($res)){
 
       // $check_In = selectColumn(FO_RESERVATIONS,'guest_Name');
		
		$guest_Name = selectColumn(FO_RESERVATIONS,'guest_Name' 'WHERE id="'.$row->id.'" ');
		 if($_REQUEST['guestname']==$row->id){
			$selected ='selected="selected"';
		}
		else{
			$selected ='';
		}

		$options.=$guest_Name;
	 echo $options;
	 }
		
		
/*	
$sql = "SELECT * FROM ".FO_RESERVATIONS." WHERE check_In='".$_REQUEST['res_checkin']."' AND check_Out='".$_REQUEST['res_checkout']."' ";

$res = mysqli_query($connNew,$sql);

while($row=mysqli_fetch_object($res)){

		$guest_Name = selectColumn(FO_RESERVATIONS,'guest_Name','WHERE id="'.$row->id.'" ');
		 if($_REQUEST['guestname']==$row->id){
			$selected ='selected="selected"';
		}
		else{
			$selected ='';
		}

		$options.=$guest_Name;
	 echo $options;
	 } */
	



/*$dateArr = explode(' to ',$_REQUEST['effective_date']);
$from = date('Y-m-d',strtotime($dateArr[0]));
$to = date('Y-m-d',strtotime($dateArr[1]));
$i=0;

$chkExisting = selectColumn(TBL_BASE_RATE,'count(id)','WHERE  id_shop="'.$_SESSION['shop'].'" AND id_hotel="'.$_REQUEST['id_hotel'].'" AND id_room_plan_link="'.$_REQUEST['id_link'].'" AND effective_date BETWEEN "'.$from.'" AND "'.$to.'" ');

if($chkExisting>0){
	echo "Data Already Exists For This Period ! Please Use Edit option.";
	exit;
}
else{
	while(strtotime($from)<=strtotime($to)){

		$guestName=$_REQUEST['guestname'];
		$doubleGrid=$_REQUEST['double_pax_price'];
		$extraBedGrid=$_REQUEST['extra_bed_price'];
		$extraChildGrid=$_REQUEST['extra_child_price'];
		$minStayGrid=0;
		$maxStayGrid=0;
		$dateGrid=$from;

		$insertGrid = "INSERT INTO ".FO_PREFIX." 
					
					`guest_Name`='".$guestName."' ";

		mysqli_query($connNew,$insertGrid);			 

		$from = date('Y-m-d',strtotime('+1 day',strtotime($from)));
	}
	echo "Data Submitted Successfully !";	
}*/

?>