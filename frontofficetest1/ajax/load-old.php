<?php
include_once("../../config/auto_loader.php");
$startDate=$_POST['startDate'];
$startEnd=$_POST['startEnd'];
$id_hotel=$_POST['id_hotel'];


$sql	=	"SELECT * FROM ".FO_RESERVATIONS." ";
$res 	= 	mysqli_query($connNew,$sql);
	
	while($row = mysqli_fetch_object($res)){
		$sqlOrderDetail = mysqli_query($connNew,"Select sum(tariff_price_per_day_per_room) as tariff , sum(tax_per_day_per_room) as taxes, `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where id_fo_reservations='".addslashes($row->id)."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room,id_mst_room_no_allocation ");
		
		
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					
					
					
	$Firstname	   =	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
$Lastname		=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");

$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");				
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				

$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));

	
	
		$data[] = array(
		  'id'   => $row->id,
		  'resourceId'   => $rowOrderDetail->id_mst_room_no_allocation.'- s',
		  'parentId'   => $rowOrderDetail->id_mst_room_types,
		  'start'   =>  date('Y-m-d', strtotime($row->checkin)),
		  'end'   => date('Y-m-d', strtotime($row->checkout)),
		  'title'   => $guestName,
		  'booking_no'   => $row->booking_no,
		  'backgroundColor'=>'#24ca7d',

		);
	}	
	
		}
	}
	




  $sqlnewt="SELECT SUM(crs_available) as total, allocation_date  FROM  ".FO_INVENTORY." where 
DATE(allocation_date) between '".date('Y-m-d',strtotime($startDate))."' and '".date('Y-m-d',strtotime($startEnd))."' and  id_mst_hotels='".$id_hotel."' GROUP BY allocation_date ";
$resnewt = mysqli_query($connNew,$sqlnewt);
//echo 'dateavi';

	while($rownetw = mysqli_fetch_object($resnewt)){ 
	
	 $avl =  $rownetw->crs_available;
	    
		$sumTotal +=  $avl;
	 $tot = $rownetw->total;
	  
	  if($tot > 0){
		  $data[] = array(
		  'resourceId'   => 0,
		  'start'   => $rownetw->allocation_date,
		  'title' =>   $tot . ' AVL'
		);
	  }else{
		   $data[] = array(
		  'resourceId'   => 0,
		  'start'   => $rownetw->allocation_date,
		  'title' =>   $tot . ' AVL',
		  'backgroundColor'=>'#ff797b'
		
		);
	  }
	}
	//echo '<pre>';
	//print_r($data);
//die;

$i=0;

 $sqlnew="SELECT * FROM  ".FO_INVENTORY." where allocation_date between '".$startDate."' and '".$startEnd."' and  id_mst_hotels='".$id_hotel."' ";

$resnew = mysqli_query($connNew,$sqlnew);

	while($rownew = mysqli_fetch_object($resnew)){ 
	
	 $avl =  $rownew->crs_available;
	     
		/* $sumTotal +=  $avl;
	  
		  $data[] = array(
		  'id'   => $row->id_mst_hotels,
		  'resourceId'   => 1,
		  'start'   => $rownew->allocation_date,
		  'title' =>   $sumTotal
		
		); */
	
		if($avl > 0){
		$data[] = array( 
		  'id'   => $row->id_mst_hotels,
		  'resourceId'   => $rownew->id_mst_room_types,
		  'start'   => $rownew->allocation_date,
		  'title' => $rownew->crs_available. ' AVL' 
		 // 'color'=>'#08ce4e'
		);
		
		}else{
			$data[] = array( 
		  'id'   => $row->id_mst_hotels,
		  'resourceId'   => $rownew->id_mst_room_types,
		  'start'   => $rownew->allocation_date,
		  'title' => $rownew->crs_available. ' AVL',
		  'backgroundColor'=>'#ff797b',
		  'eventTextColor'=>'#ff797b'
		);
		}
		
		
		$data[] = array(
		  'resourceId'   => ($rownew->id_mst_room_types/10.25),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $rownew->allocation_date, 
		  'end'   => $rownew->allocation_date, 
		  'title' =>  $rownew->confirmed,
		  'backgroundColor'=>'transparent'

		);

		  $data[] = array(
		  'resourceId'   => ($rownew->id_mst_room_types/10.26),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $rownew->allocation_date,
		  'title' =>  $rownew->tentative ,
		  'backgroundColor'=>'transparent'
		);

		$data[] = array(
		  'resourceId'   => ($rownew->id_mst_room_types/10.27),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $rownew->allocation_date,
		  'title' =>  $rownew->waitlisted ,
		  'backgroundColor'=>'transparent'
		); 
		
		
		

		$id_room[$i] = $rownew->id_mst_room_types;
		$i++;
	}
	
	
		
	
	
	
	$id_room = array_unique($id_room); 
	 
	$divide = 10.28;
	for($i=0;$i<count($id_room);$i++){
		
		$id_room_val = $id_room[$i];
		$sqlnews="SELECT * FROM  ".FO_ROOM_PLAN_LINKS." where  id_room = '".$id_room_val."' and  id_hotel='".$id_hotel."' order by id_plan asc ";
	
		$resnews = mysqli_query($connNew,$sqlnews);

		while($rownews = mysqli_fetch_object($resnews)){ 
		
			$id_room_plan_links = $rownews->id;
			$id_plan_room_plan_links = $rownews->id_plan;
		 	
			/*$sqlnewss="SELECT * FROM  ".FO_RATE_PLAN." where id = '".$id_plan_room_plan_links."' ";
	 
			$resnewss = mysqli_query($connNew,$sqlnewss);

			while($rownewss = mysqli_fetch_object($resnewss)){ 
			
				$name_fo_rate_plan = $rownewss->name; 
			
			}*/
		
		$sqlnewsss="SELECT * FROM  ".FO_BEST_AVAILABLE_RATE." where effective_date between '".$startDate."' and '".$startEnd."' and id_hotel = '".$id_hotel."'  and id_room_plan_link = '".$id_room_plan_links."'";
 
		$resnewsss = mysqli_query($connNew,$sqlnewsss);
		
		while($rownewsss = mysqli_fetch_object($resnewsss)){ 
			//echo  $rownewsss->effective_date;exit;
			 
			$data[] = array(
			  'resourceId'   => ($id_room_val/$divide),
			  'parentId'   => $id_room_val,
			  'start'   => $rownewsss->effective_date,
			  'title' =>  $rownewsss->double_pax_price ,
			  'backgroundColor'=>'transparent'
			); 			
	
		}
		$divide = $divide +  1;
		}
	
	}
	

//exit;	
echo json_encode($data);




/* 



$sqlnewt="SELECT * FROM  ".FO_INVENTORY." where  id_mst_hotels='".$id_hotel."' ";
$resnewt = mysqli_query($connNew,$sqlnewt);

	while($rownewt = mysqli_fetch_object($resnewt)){ 
	  $avl =$rownewt->crs_available;
	//  $sum += $av1;
	  
		  $data[] = array(
		  'resourceId'   => 1,
		  'start'   => $rownewt->allocation_date,
		  'title' =>  'wel'
		);
	}




$date=date('Y-m-d');
$sql="SELECT fo_reservations.guest_name,fo_reservations.parentId,fo_reservations.check_in,fo_reservations.check_out FROM fo_reservations LEFT JOIN fs_inventory ON fo_reservations.id_mst_hotels = fs_inventory.id_mst_hotels"; 

//'title' => ('AVl'.$rownew->crs_available) . "\n" . ('CON'.$rownew->confirmed). "\n" . ('CON'.$rownew->tentative)  . "\n" . ('CON'.$rownew->waitlisted)  ,

SELECT
  fo_rate_plan.name
FROM fo_best_available_rate
JOIN fo_room_plan_links
  ON fo_best_available_rate.id_room_plan_link = fo_room_plan_links.id
JOIN fo_rate_plan
 ON fo_rate_plan.id = fo_room_plan_links.id_plan where fo_best_available_rate.id_hotel = '65'


<!-- start expander toggle script -->

<script type="text/javascript" src="<?php echo $SITE_URL; ?>/hexpander/movingjs.js"></script>

<table align=center height="100px" border=0 width="500px" cellspacing=0 cellpadding=0>
	<tr>
		<td>
			<a style="font-family:verdana;font-size:12px;">
			<img src="<?php echo $SITE_URL; ?>/hexpander/insert.jpg" id="insert1" align="absmiddle" onClick="toggleSlide('div1',this.id);">Hioxindia</a>
			
			<div id="div1" style="display:none; overflow: hidden; height: 75px;margin:10px;"> 
			    <div style="font-family:verdana;font-size:12px;">
				   HIOX INDIA is currently involved in web services, software/application development, web content development,  
				   web hosting, domain registration, internet solutions and web design.
				</div>
			</div>
			
		</td>
	</tr>
</table>

<!-- end expander toggle script -->






		
			
*/		

?>		