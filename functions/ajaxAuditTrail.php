<?php
include_once("../config/auto_loader.php");
$tablename=$_POST['tablename']; 
$form_name=$_POST['form_name']; 
$id=$_POST['id']; 


if($tablename != ''){
	$where = "tables_name='".$tablename."' and voucher_id='".$id."' ";
}else{
	$where = "form_code='".$form_name."' and voucher_id='".$id."'  ";
}

	 $selectneww="SELECT * FROM audit_trail where ".$where." ORDER BY id DESC LIMIT 0,10" ;
	$resneww = mysqli_query($connNew,$selectneww);
	$i='1';
		while($rowneww = mysqli_fetch_object($resneww)){
			$user = $rowneww->id_mst_user_created_by; 
			 $date_created =  stripslashes(dateformat($rowneww->date_created));
			 $last_modified =  stripslashes(dateformat($rowneww->last_modified));
			 $roomid = $rowneww->voucher_id; 
			 $type = $rowneww->type;
			 $field = $rowneww->form_code;
			$Change = $rowneww->changes;
			$result1 = str_replace(',', ' ', $Change);
		//echo $result1.'hi';
			
			
$Changed = str_replace('-', ' ', $Change);
$changes='';		
$Ch = explode(",", $Changed);




$change1 = $Ch[0];
$change2 = $Ch[1];
$change3 = $Ch[2];
$change4 = $Ch[3];
$change5 = $Ch[4];
$change6 = $Ch[5];
$change7 = $Ch[6];
$change8 = $Ch[7];
$change9 = $Ch[8];
$change10 = $Ch[9];
$change11 = $Ch[10];
$change12 = $Ch[11];
$change13 = $Ch[12];
$change14 = $Ch[13];
$change15 = $Ch[14];
$change16 = $Ch[15];
$change17 = $Ch[16];
$change18 = $Ch[17];
$change19 = $Ch[18];
$change20 = $Ch[19];
$change21 = $Ch[20];
$change22 = $Ch[21];
$change23 = $Ch[22];
$change24 = $Ch[23];
$change25 = $Ch[24];
$change26 = $Ch[25];
$change27 = $Ch[26];
$change28 = $Ch[27];
$change29 = $Ch[28];
$change30 = $Ch[29];
$change31 = $Ch[30];
$change32 = $Ch[31];
$change33 = $Ch[32];
$change34 = $Ch[33];
$change35 = $Ch[34];
$change36 = $Ch[35];
$change37 = $Ch[36];
$change38 = $Ch[37];
$change39 = $Ch[38];

				if($Ch=='No Change'){		
				  $changes=''; 
				}else { 
				  if(!empty($change1)){
					  $changes .= '</br> '.$change1 ;
				  }if(!empty($change2)){
					  $changes .= ', </br> '.$change2;
				  }if(!empty($change3)){
					  $changes .= ', </br> '.$change3;
				  }if(!empty($change4)){
					  $changes .= ', </br> '.$change4;
				  }if(!empty($change5)){
					  $changes .= ', </br> '.$change5;
				  }if(!empty($change6)){
					  $changes .= ', </br> '.$change6;
				  }if(!empty($change7)){
					  $changes .= ', </br> '.$change7;
				  }if(!empty($change8)){
					  $changes .= ', </br> '.$change8;
				  }if(!empty($change9)){
					  $changes .= ', </br> '.$change9;
				  }if(!empty($change10)){
					  $changes .= ', </br> '.$change10;
				  }if(!empty($change11)){
					  $changes .= ', </br> '.$change11;
				  }if(!empty($change12)){
					  $changes .= ', </br> '.$change12;
				  }if(!empty($change13)){
					  $changes .= ', </br> '.$change13;
				  }if(!empty($change14)){
					  $changes .= ', </br> '.$change14;
				  }if(!empty($change15)){
					  $changes .= ', </br> '.$change15;
				  }if(!empty($change16)){
					  $changes .= ', </br> '.$change16;
				  }if(!empty($change17)){
					  $changes .= ', </br> '.$change17;
				  }if(!empty($change18)){
					  $changes .= ', </br> '.$change18;
				  }if(!empty($change19)){
					  $changes .= ', </br> '.$change19;
				  }if(!empty($change20)){
					  $changes .= ', </br> '.$change20;
				  }if(!empty($change21)){
					  $changes .= ', </br> '.$change21;
				  }if(!empty($change22)){
					  $changes .= ', </br> '.$change22;
				  }if(!empty($change23)){
					  $changes .= ', </br> '.$change23;
				  }if(!empty($change24)){
					  $changes .= ', </br> '.$change24;
				  }if(!empty($change25)){
					  $changes .= ', </br> '.$change25;
				  }if(!empty($change26)){
					  $changes .= ', </br> '.$change26;
				  }if(!empty($change27)){
					  $changes .= ', </br> '.$change27;
				  }if(!empty($change28)){
					  $changes .= ', </br> '.$change28;
				  }if(!empty($change29)){
					  $changes .= ', </br> '.$change29;
				  }if(!empty($change30)){
					  $changes .= ', </br> '.$change30;
				  }if(!empty($change31)){
					  $changes .= ', </br> '.$change31;
				  }if(!empty($change32)){
					  $changes .= ', </br> '.$change32;
				  }if(!empty($change33)){
					  $changes .= ', </br> '.$change33;
				  }if(!empty($change34)){
					  $changes .= ', </br> '.$change34;
				  }if(!empty($change35)){
					  $changes .= ', </br> '.$change35;
				  }if(!empty($change36)){
					  $changes .= ', </br> '.$change36;
				  }if(!empty($change37)){ //not Used
					  $changes .= ', </br>'.$change37;
				  }if(!empty($change38)){  //Not User
					  $changes .= ', </br>'.$change38;
				  }if(!empty($change39)){
					  $changes .= ',</br><b><span class="text-danger">Cancelled</span><br >Remarks : </b> '.$change39;
				  }
				   
				}
	$k=1;		  
			 $selectnew1="SELECT * FROM ".TBL_ROOMNO." where id='$roomid' " ;
			  $resnew1 = mysqli_query($connNew,$selectnew1);
				while($rownew1 = mysqli_fetch_object($resnew1)){
					$roomno = $rownew1->roomno;
					$room =  $roomno ;
				}
					$selectnew="SELECT * FROM ".TBL_USERS." where id='$user' " ;
					  $resnew = mysqli_query($connNew,$selectnew);
						while($rownew = mysqli_fetch_object($resnew)){
						 $user1 = $rownew->name;
				
$changes1 = str_replace(',', ' ', $changes);

				
			if($result1!=''){
				if($type=='1'){		
				  $dataArr[] =  '<tr><td> <b>'.$date_created.' : </b>  New Details Saved updated  by '.$user1.' </td></tr>';	
				}else if($type=='2') { 
				
				  if($changes!=''){
				  	//$addTag='<span class="text-danger">Cancelled</span><br >
				  	//<b>Remarks : </b>';
				  }
				  $dataArr[] =  '<tr><td> <b>'.$date_created.' : </b> '.$addTag.'  ' .$changes.'  <br><i> <b>Updated  by<b> '.$user1.' </i>  </td></tr>';	
				  $k++;
				}
			}else{
				$dataArr[] = '';
			}

						
			}
				
				 
		}
	

	echo json_encode($dataArr);



 ?>


