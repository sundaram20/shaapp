<?php
include_once("../../config/auto_loader.php");

$id_room=$_POST['id_room'];


	$selectneww="SELECT * FROM audit_trail where table_name='mst_attributes' ORDER BY id DESC" ;
	$resneww = mysqli_query($connNew,$selectneww);
	while($rowneww = mysqli_fetch_object($resneww)){
		$user = $rowneww->id_mst_user_created_by; 
		 $date_created =  stripslashes(dateformat($rowneww->date_created));
		 $last_modified =  stripslashes(dateformat($rowneww->last_modified));
         $roomid = $rowneww->user_id; 
         $type = $rowneww->type;

if($type=='1')		
{

$mes='added';

}	
else
{
	$mes='Modified';

}
		  
			 $selectnew1="SELECT * FROM ".TBL_ATTRIBUTES." where id='$roomid' " ;
	          $resnew1 = mysqli_query($connNew,$selectnew1);
			   while($rownew1 = mysqli_fetch_object($resnew1)){
				$name = $rownew1->field_value;
			
			
			$selectnew="SELECT * FROM ".TBL_USERS." where id='$user' " ;
	          $resnew = mysqli_query($connNew,$selectnew);
			   while($rownew = mysqli_fetch_object($resnew)){
				$user1 = $rownew->user_name;
			
			
			//$dataArr[] =  '<tr><td>  Room No '.$roomno.' Details Created on '.$date_created.' and modified on '.$last_modified.' by '.$user.' </td></tr>';
			
			$dataArr[] =  '<tr><td> <b>'.$date_created.' : </b> '.$name.' Details '.$mes.'  by '.$user1.' </td></tr>';
			
		}
		}
	}

								
				

	echo json_encode($dataArr);



 ?>


