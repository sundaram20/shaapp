<?php
include_once("../config/auto_loader.php");

/*$dataArr[] = array(
		'id' => 0,
		'title'   => 'Total'
	);*/			


$divide = 10.28;
$id=$_POST['str'];

if($id==''){
	
	$select="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where status='1' and id_mst_hotels=1 ";

	$res = mysqli_query($connNew,$select);
	
			while($row = mysqli_fetch_object($res)){
					
			    $idnew=$row->id_mst_room_types;
			    $inventory=$row->inventory;
			    $val = $idnew;
				$valTotal = $idnew+5;
				//}
				
				
				
				
			$selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where status='1' and id=$idnew";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
						
					// $name=$rownew->name;
					$dataArr[] = array(
					  'id'=> $val,
					  'title'   => $rownew->name.'('.$inventory.')'
					);
					
					
					
										
					$vals = $val/10.25;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Confirmed',
						'bookingstatus'   => 'Confirmed'
					);
					
					$dataArr5[] = array(
					    'parentId'=> 0,
					    'id'=>'TotalConfirmed',
					    'title'   => 'Confirmed',
						'bookingstatus'   => 'Confirmed'
					);
					
						
					
					$vals = $val/10.26;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Tentative',
						'bookingstatus'   => 'Tentative'
					);
					$dataArr5[] = array(
					   'parentId'=> 0,
					    'id'=>'TotalTentative',
					    'title'   => 'Tentative',
						'bookingstatus'   => 'Tentative'
					);
					
					
					
					
					$vals = $val/10.27;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => ' Waitlisted',
						'bookingstatus'   => 'Waitlisted'
					);
					
					
						
					
					
					$vals = $val/10.28;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => ' Blocked',
						'bookingstatus'   => 'Blocked'
					);
					
					
					
					$dataArr5[] = array(
					   'parentId'=> 0,
					    'id'=>'TotalWaitlisted',
					    'title'   => ' Waitlisted',
						'bookingstatus'   => 'Waitlisted'
					);
					
					$dataArr5[] = array(
					   'parentId'=> 0,
					    'id'=>'TotalBlocked',
					    'title'   => ' Blocked',
						'bookingstatus'   => 'Blocked'
					);
					
					
					
					
					
				/*$selectnewp="SELECT ".TBL_RATE_PLAN.".name FROM ".TBL_BEST_AVAILABLE_RATE." JOIN ".TBL_ROOM_PLAN_LINKS."
				ON ".TBL_BEST_AVAILABLE_RATE.".id_room_plan_link = ".TBL_ROOM_PLAN_LINKS.".id
				JOIN ".TBL_RATE_PLAN." ON ".TBL_RATE_PLAN.".id = ".TBL_ROOM_PLAN_LINKS.".id_plan where ".TBL_BEST_AVAILABLE_RATE.".id_hotel = '1' group BY fo_rate_plan.name";
				*/
				$selectnewp="SELECT ".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '1' AND ". 
				TBL_ROOM_PLAN_LINKS.".id_room = '$idnew' group BY ".TBL_RATE_PLAN.".name";
				
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					$vals = $val/$divide;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);
					
					
					$divide = $divide +  1;
				} 
				 	/*$vals = $val/10.29;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);
					
					$vals = $val/10.30;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);   */
					
				
					
			  $selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types=$idnew && id_mst_hotels=1 and status='1' order by room_no,id_mst_room_types   asc" ;
					$resneww = mysqli_query($connNew,$selectneww);
					while($rowneww = mysqli_fetch_object($resneww)){
						 $block = $rowneww->id_mst_hotel_room_block;
							
					$selectneww1="SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id=$block " ;
					$resneww1 = mysqli_query($connNew,$selectneww1);
					while($rowneww1 = mysqli_fetch_object($resneww1)){
						 $block1 = $rowneww1->name;	
							
							
					  $dataArr[] = array(
						  'parentId'=> $val,
						  'roomno'   =>  $rowneww->room_no,
						  'id'=>$rowneww->id.'- s',
						  'title'   =>  $rowneww->room_no .' - ' .'' .$block1.''
						);
						
					}}
				
			    }
			}
		$dataArr6[] = array(
		'id' => 0,
		'title'   => 'Total'
	);	
	
	$q=array_merge($dataArr6,$dataArr5);
	$dataArr	=array_merge($q,$dataArr);
	echo json_encode($dataArr);
	
} 

else {

 $select="SELECT * FROM ".TBL_ASSIGN_HOTEL_ROOM."  where status='1' and  id_mst_hotels=$id";

	$res = mysqli_query($connNew,$select);
	
			while($row = mysqli_fetch_object($res)){
					
			    $idnew=$row->id_mst_room_types;
			    $inventory=$row->inventory;
			    $val = $idnew;
				//}															
				
				$selectnew="SELECT * FROM ".TBL_ROOM_TYPE."  where status='1' and id=$idnew";
				$resnew = mysqli_query($connNew,$selectnew);
				while($rownew = mysqli_fetch_object($resnew)){
						
					// $name=$rownew->name;
					$dataArr[] = array(
					  'id'=> $val,
					  'title'   => $rownew->name.'('.$inventory.')'
					);
						
					$vals = $val/10.25;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Confirmed',
						'bookingstatus'   => 'Confirmed'
					);	
					
					$vals = $val/10.26;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Tentative',
						'bookingstatus'   => 'Tentative'
					);	
					
					$vals = $val/10.27;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Waitlisted',
						'bookingstatus'   => 'Waitlisted'
					);	
					
					$vals = $val/10.28;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => 'Blocked',
						'bookingstatus'   => 'Blocked'
					);	
				$selectnewp="SELECT ".TBL_RATE_PLAN.".name FROM ".TBL_ROOM_PLAN_LINKS." JOIN ".TBL_RATE_PLAN."
				ON ".TBL_ROOM_PLAN_LINKS.".id_plan = ".TBL_RATE_PLAN.".id where ".TBL_ROOM_PLAN_LINKS.".id_hotel = '$id' AND ". TBL_ROOM_PLAN_LINKS.".id_room = '$idnew' group BY ".TBL_RATE_PLAN.".name";
				
				
				$resnewp = mysqli_query($connNew,$selectnewp);
				
				while($rownewp = mysqli_fetch_object($resnewp)){	
					
					$vals = $val/$divide;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);
					$divide = $divide +  1;
				}
					
				/*	$vals = $val/10.29;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);
					
					$vals = $val/10.30;
					$dataArr[] = array(
					    'parentId'=> $val,
					    'id'=>$vals,
					    'title'   => $rownewp->name
					);   */
					
					$selectneww="SELECT * FROM ".TBL_ROOMNO." where id_mst_room_types=$idnew && id_mst_hotels=1 and status='1' order by room_no,id_mst_room_types  asc" ;
					$resneww = mysqli_query($connNew,$selectneww);
					while($rowneww = mysqli_fetch_object($resneww)){
						 $block = $rowneww->id_mst_hotel_room_block;
							
						$selectneww1="SELECT * FROM ".TBL_HOTEL_ROOM_BLOCK." where id=$block " ;
					$resneww1 = mysqli_query($connNew,$selectneww1);
					while($rowneww1 = mysqli_fetch_object($resneww1)){
						 $block1 = $rowneww1->name;	
							
							
					  $dataArr[] = array(
						  'parentId'=> $val,
						  'roomno'   =>  $rowneww->room_no,
						  'id'=>$rowneww->id.'- s',
						  'title'   =>  $rowneww->room_no .' - ' .'' .$block1.''
						  
						);
						
					}}
					
					/* $selectneww="SELECT * FROM ".TBL_ROOM_ALLOCATION." where id_mst_room_types=$idnew && id_mst_hotels=$id" ;
					$resneww = mysqli_query($connNew,$selectneww);
					while($rowneww = mysqli_fetch_object($resneww)){
						$block = $rowneww->block;
					  // $name=$rowneww->room_no;
						$dataArr[] = array(
						  'parentId'=> $val,
						  'id'=>$rowneww->id.'- s',
						  'title'   =>  $rowneww->room_no .' - ' .'' .$block.''
						);
						
					} */
					
				}
				 
				
			}


echo json_encode($dataArr);

}
?>


