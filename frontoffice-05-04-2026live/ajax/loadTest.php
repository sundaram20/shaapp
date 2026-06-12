<?php
include_once("../../config/auto_loader.php");
$startDate=$_POST['startDate'];
$startEnd=$_POST['startEnd'];
$id_hotel=$_POST['id_hotel'];

$data5=array();
$checkoutDate_upadate = date ("Y-m-d", strtotime($startEnd));
$startDateCheckAvailability = date ("Y-m-d", strtotime($startDate));

$sqlHot="SELECT id,name FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."' AND status='1' ";
				$resHot=mysqli_query($connNew,$sqlHot);
				$objHot=mysqli_fetch_object($resHot);	
//HOtel====================================================
$fromDate =$startDateCheckAvailability;
$toDate =$checkoutDate_upadate;

updateBlockedHotelsForShop($connNew, $_SESSION['shop'], $fromDate, $toDate);
function updateBlockedHotelsForShop($conn, $shopId, $fromDate, $toDate) {
    // get all hotels for this shop
    $sqlHot = "SELECT id, name 
               FROM ".TBL_HOTELS." 
               WHERE id_shop='".mysqli_real_escape_string($conn, $shopId)."' 
               AND status='1'";
    $resHot = mysqli_query($conn, $sqlHot);

    while ($objHot = mysqli_fetch_object($resHot)) {
        $hotelId = $objHot->id;

        // get all assigned room types for this hotel
        $sqlRoomType = "SELECT id_mst_room_types 
                        FROM `".TBL_ASSIGN_HOTEL_ROOM."` 
                        WHERE id_mst_hotels = '$hotelId' 
                        ORDER BY status_active_date DESC";
        $resRoomType = mysqli_query($conn, $sqlRoomType);

        while ($rowRoomType = mysqli_fetch_object($resRoomType)) {
             $roomTypeId = $rowRoomType->id_mst_room_types;

            // call processor for this (hotel, room type)
            processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate);
        }
    }

   // return true;
}
function processBlockedRoomDates($conn, $hotelId, $roomTypeId, $fromDate, $toDate) {
    $arrayOfDates = [];
    $from = new DateTime($fromDate);
    $to   = new DateTime($toDate);

    // prepare all dates in range with 0 first
    for ($current = clone $from; $current <= $to; $current->modify('+1 day')) {
        $dateStr = $current->format('Y-m-d');
        $arrayOfDates[$dateStr] = 0;
    }

    // fetch blocked ranges
    $query = "SELECT blocked_room_dates 
              FROM `".TBL_ROOMNO."`
              WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
              AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
              AND status = '1'
              AND blocked_room_dates != ''";
    $resSQL = mysqli_query($conn, $query);

    while ($Record = mysqli_fetch_object($resSQL)) {
        $ranges = explode(',', $Record->blocked_room_dates);

        foreach ($ranges as $selectedDateRange) {
            $dates = explode(' - ', trim($selectedDateRange));
            if (count($dates) != 2) continue;

            $start = DateTime::createFromFormat('d/m/Y', trim($dates[0]));
            $end   = DateTime::createFromFormat('d/m/Y', trim($dates[1]));
            if (!$start || !$end) continue;

            for ($current = clone $start; $current <= $end; $current->modify('+1 day')) {
                if ($current >= $from && $current <= $to) {
                    $dateStr = $current->format('Y-m-d');
                    $arrayOfDates[$dateStr]++;  // increment block count
                }
            }
        }
    }

    // now update inventory (always write a value, even if 0)
    foreach ($arrayOfDates as $date => $count) {
         $sql = "UPDATE fo_inventory
                SET blocked_hotel = $count
                WHERE id_mst_room_types = '".mysqli_real_escape_string($conn, $roomTypeId)."'
                AND id_mst_hotels = '".mysqli_real_escape_string($conn, $hotelId)."'
                AND allocation_date = '$date'";
        mysqli_query($conn, $sql);
    }

    return true;
}

//=============================Load CheckAvailability	
while (strtotime($startDateCheckAvailability) < strtotime($checkoutDate_upadate)) {	

$startDateCheckAvailability = date("Y-m-d",strtotime($startDateCheckAvailability));	


			
		 $AssRoomRoomType	=	" SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id_mst_hotels` = '".$objHot->id."' ORDER BY status_active_date DESC ";
			$resHotRoomType=mysqli_query($connNew,$AssRoomRoomType);	
			
		while($rowResRoomType = mysqli_fetch_object($resHotRoomType)){
			
			 $sqlRes="SELECT count(fo_reservations_details.room_quantity) as qty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status!='4' and fo_reservations_details.no_showoff='0'  and  fo_reservations_details.dated='".$startDateCheckAvailability."' 
 and fo_reservations_details.id_mst_room_types='".$rowResRoomType->id_mst_room_types."'
GROUP by fo_reservations_details.dated ,fo_reservations_details.id_mst_room_types ORDER BY `fo_reservations_details`.`dated` DESC";




$resRes = mysqli_query($connNew,$sqlRes);
			if(mysqli_num_rows($resRes)>0){
			while($rowRes = mysqli_fetch_object($resRes)){ 
						
						$sqla = "SELECT * FROM ".FO_INVENTORY." WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."' ";
						$resnew = mysqli_query($connNew,$sqla);
						//$rownew = mysqli_fetch_object($resnew);
						
						$rownew = mysqli_fetch_object($resnew);
						
						//================================
					 $sqlResConfirm="SELECT count(fo_reservations_details.room_quantity) as Confirmqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='1' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";		
						$resnewConfirm = mysqli_query($connNew,$sqlResConfirm);	
							$rownewConfirm = mysqli_fetch_object($resnewConfirm);
							$Confirmqty	= $rownewConfirm->Confirmqty;
							$Confirmqty=$Confirmqty==''?'0':$Confirmqty;
	
 $sqlResTenditive="SELECT count(fo_reservations_details.room_quantity) as Tenditivemqty ,fo_reservations.booking_status,fo_reservations_details.dated,fo_reservations_details.id_mst_room_types,fo_reservations_details.id_mst_hotels 
FROM `fo_reservations_details` left join fo_reservations on fo_reservations_details.id_fo_reservations =fo_reservations.id
where fo_reservations.booking_status='2' and fo_reservations_details.no_showoff='0'  and   fo_reservations_details.id_mst_room_types='".$rowRes->id_mst_room_types."' and fo_reservations_details.dated='".$startDateCheckAvailability."' 
GROUP by fo_reservations_details.dated  ORDER BY `fo_reservations_details`.`dated` DESC";			
						$resnewTenditive = mysqli_query($connNew,$sqlResTenditive);	
							$rownewTenditive = mysqli_fetch_object($resnewTenditive);
							$Tenditiveqty	= $rownewTenditive->Tenditivemqty;							
								$Tenditiveqty=$Tenditiveqty==''?'0':$Tenditiveqty;
								
								
								
								//==============================
						$sqlRoom=  "SELECT rt.name, ahr.id_mst_hotels,ahr.inventory, ahr.id_mst_room_types from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels = '".$rowRes->id_mst_hotels."' and ahr.id_mst_room_types='".addslashes($rowRes->id_mst_room_types)."'" ;
						
						
						$resRoom = mysqli_query($connNew,$sqlRoom);
						$rowRoom = mysqli_fetch_object($resRoom);
						//if($rowRes->booking_status=='2'){
						
							
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$tentative =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`tentative`='".$Tenditiveqty."',`confirmed`='".$Confirmqty."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						//echo '<br><br>2==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						/*}else{
						
							$crs_available = $rowRoom->inventory - $rowRes->qty ; 
							$confirmed =  $rowRes->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$crs_available."',`confirmed`='".$confirmed."' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowRes->id_mst_room_types."' and allocation_date='".$rowRes->dated."' and id_mst_hotels = '".$rowRes->id_mst_hotels."'";
						echo '<br><br>1==='.$rowRes->dated.$insertGrid;
						  mysqli_query($connNew,$insertGrid);
						}*/
					
						
						}
			}else{
				
				
					 $roomId=$rowResRoomType->id_mst_room_types;
				$hotelId=$id_hotel;		
				//echo "SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'";
				$sqlSum=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' and ahr.id_mst_room_types='".addslashes($roomId)."'");
		$rowResSum = mysqli_fetch_object($sqlSum);
		$totalRoom	= $rowResSum->totalRoom;
	
		$crs_available = $rowRoom->inventory - $rowResRoomType->qty ; 
							$confirmed =  $rowResRoomType->qty ;
							$insertGrid = "UPDATE ".FO_INVENTORY." SET `crs_available`='".$totalRoom."',`confirmed`='0',`tentative`='0' ";
							$insertGrid .=" WHERE id_mst_room_types='".$rowResRoomType->id_mst_room_types."' and allocation_date = '".$startDateCheckAvailability."' and   id_mst_hotels = '".$rowResRoomType->id_mst_hotels."'";
						//echo $insertGrid;
						  mysqli_query($connNew,$insertGrid);
		
				
			}
		
		
		
		
		
		
		
		}
		
		
		
		
			
				
	//echo '<br>===='.
	$startDateCheckAvailability = date ("Y-m-d", strtotime("+1 day", strtotime($startDateCheckAvailability)));	

			  			
  }			
						
		//=============================Load CheckAvailability				
						
						
/*$sql	=	"SELECT * FROM ".FO_RESERVATIONS." ";
$res 	= 	mysqli_query($connNew,$sql);
	
	while($row = mysqli_fetch_object($res)){*/
	//echo "Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where DATE(dated) >='".date('Y-m-d',strtotime($startDate))."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room,id_mst_room_no_allocation ";
		//$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where DATE(dated) >='".date('Y-m-d',strtotime($startDate))."' group by id_mst_room_types,id_fo_rate_plan,adults_per_room,id_mst_room_no_allocation ");
		
		
	$sqlOrderDetail = mysqli_query($connNew,"Select  `".FO_RESERVATIONS_DETAILS."`.* from `".FO_RESERVATIONS_DETAILS."` where DATE(dated) >='".date('Y-m-d',strtotime($startDate))."' and  id_mst_room_no_allocation >0  and no_showoff ='0' group by id_fo_reservations,id_mst_room_no_allocation ");
		
		
			
		
		if(mysqli_num_rows($sqlOrderDetail) >0 ){
			$RoomWiseArray=array();
			$rrcounter=1;
			$roomdetails='';
				while($rowOrderDetail= mysqli_fetch_object($sqlOrderDetail)){
					//debugData($rowOrderDetail);	
					//$checkin_status	   =	selectColumn(FO_RESERVATIONS_DETAILS,'checkin_status'," WHERE `id_fo_reservations` = '".$rowOrderDetail->id_fo_reservations."'");
	
	
	 $dated	   =	selectColumn(FO_RESERVATIONS_DETAILS,'dated'," WHERE `id_fo_reservations` = '".$rowOrderDetail->id_fo_reservations."' and id_mst_room_no_allocation = '".$rowOrderDetail->id_mst_room_no_allocation."' and  `no_showoff`='1'");
	
	
		
	$booking_status	   =	selectColumn(FO_RESERVATIONS,'booking_status'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
	
	if($booking_status=='1' || $booking_status=='2'){	
$checkin	   =	selectColumn(FO_RESERVATIONS,'checkin'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");


if($dated==''){
$checkout	   =	selectColumn(FO_RESERVATIONS,'checkout'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");
}else{
	$checkout	   =$dated;
	}
	
	
$status	=		selectColumn(FO_BILL,'status'," WHERE `id_reservations` = '".$rowOrderDetail->id_fo_reservations."' ");
	
				
$Firstname	   =	selectColumn("mst_guest",'first_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");
$Lastname		=	selectColumn("mst_guest",'last_name'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");

$id_mst_attributes_title	=	selectColumn(TBL_GUEST,'id_mst_attributes_title'," WHERE `id` = '".$rowOrderDetail->id_mst_guest."'");				
$Title=selectColumn(TBL_ATTRIBUTES,'field_value'," WHERE id_shop='".$_SESSION['shop']."'  and status = '1' and `table_name` = 'title' AND id= '".$id_mst_attributes_title."'"); 				

$guestName=$Title.' '.ucwords(strtolower($Firstname)).' '.ucwords(strtolower($Lastname));

	$booking_no	   =	selectColumn(FO_RESERVATIONS,'booking_no'," WHERE `id` = '".$rowOrderDetail->id_fo_reservations."'");


	
$sqla = "SELECT checkin_status FROM ".FO_RESERVATIONS_DETAILS." WHERE id_fo_reservations='".$rowOrderDetail->id_fo_reservations."' and order_by_room='".$rowOrderDetail->order_by_room."' and id_mst_hotels = '".$rowOrderDetail->id_mst_hotels."' and id_mst_room_types = '".$rowOrderDetail->id_mst_room_types."' order by  order_by_room desc";
						$resnew = mysqli_query($connNew,$sqla);
						
						$rownew = mysqli_fetch_object($resnew);
		$checkin_status	= $rownew->checkin_status;			
						
							 	
	if($rowOrderDetail->checkin_status==1 && $status=='1'){
		$backgroundColor ='red';
	}elseif($status=='2'){
		$backgroundColor ='#e5a9a9';
			}else{
			$backgroundColor ='#24ca7d';
			}
		$data[] = array(
		  'id'   => $rowOrderDetail->id_fo_reservations,		  
		  'resourceId'   => $rowOrderDetail->id_mst_room_no_allocation.'- s',
		  'parentId'   => $rowOrderDetail->id_mst_room_types,
		  'start'   =>  date('Y-m-d', strtotime($checkin)),
		  'end'   => date('Y-m-d', strtotime($checkout)),
		  'title'   => $guestName,
		  'booking_no'   => $booking_no,
		  'checkin_status'   => $rowOrderDetail->checkin_status,
		  'id_room'	=> $rowOrderDetail->id_mst_room_no_allocation,
		  'id_mst_room_types'	=> $rowOrderDetail->id_mst_room_types,
		  'order_by_room'	=> $rowOrderDetail->order_by_room,
		  'checkin_status'	=> $checkin_status,	  
		  'backgroundColor'=>$backgroundColor

		);
	}	
				}
		}
	//}
	
//debugData($data);	

$AssRoomRoomType	=	" SELECT * FROM `".TBL_ASSIGN_HOTEL_ROOM."` WHERE `id_mst_hotels` = '".$objHot->id."' ORDER BY status_active_date DESC ";
			$resHotRoomType=mysqli_query($connNew,$AssRoomRoomType);	
			
		while($rowResRoomType = mysqli_fetch_object($resHotRoomType)){
		$id_mst_room_types[] =$rowResRoomType->id_mst_room_types;
		}
		$id_mst_room_types	=implode(',',$id_mst_room_types);


  $sqlnewt="SELECT SUM(crs_available) as total, allocation_date, sum(blocked_hotel) as blocked_hotel  FROM  ".FO_INVENTORY." where 
DATE(allocation_date) between '".date('Y-m-d',strtotime($startDate))."' and '".date('Y-m-d',strtotime($startEnd))."' and  id_mst_hotels='".$id_hotel."'  and id_mst_room_types IN (".$id_mst_room_types.")   GROUP BY allocation_date ";
$resnewt = mysqli_query($connNew,$sqlnewt);
//echo 'dateavi';

	while($rownetw = mysqli_fetch_object($resnewt)){ 
	
	 $avl =  $rownetw->crs_available;
	    $blocked_hotel =  $rownetw->blocked_hotel;
		$sumTotal +=  $avl;
	 $tot = $rownetw->total-$blocked_hotel;
	  
	 
	  
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
		  'title' => ($rownew->crs_available - $rownew->blocked_hotel). ' AVL' 
		 // 'color'=>'#08ce4e'
		);
		
		}else{
			$data[] = array( 
		  'id'   => $row->id_mst_hotels,
		  'resourceId'   => $rownew->id_mst_room_types,
		  'start'   => $rownew->allocation_date,
		  'title' => ($rownew->crs_available - $rownew->blocked_hotel). ' AVL',
		  'backgroundColor'=>'#ff797b',
		  'eventTextColor'=>'#ff797b'
		);
		}
		
		
		$data5[$rownew->allocation_date]['parentId']=0;
		$data5[$rownew->allocation_date]['start']=$rownew->allocation_date;
		$data5[$rownew->allocation_date]['end']=$rownew->allocation_date;
		$data5[$rownew->allocation_date]['confirmed']+=$rownew->confirmed;
		$data5[$rownew->allocation_date]['tentative']+=$rownew->tentative;
		$data5[$rownew->allocation_date]['waitlisted']+=$rownew->waitlisted;
		$data5[$rownew->allocation_date]['backgroundColor']='transparent';
		/*$data5['TotalConfirmed'] = array(
		  'resourceId'   => 'TotalConfirmed',
		  'parentId'   => 0,
		  'start'   => $rownew->allocation_date, 
		  'end'   => $rownew->allocation_date, 
		  'title' =>  $rownew->confirmed,
		  'backgroundColor'=>'transparent'

		);*/
		
		
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
	
	}//debugData($data5);
	foreach($data5 as $y=>$con){
		
		//echo $con['confirmed'];
		$data6[] = array(
		  'resourceId'   => 'TotalConfirmed',
		  'parentId'   => 0,
		  'start'   => $con['start'], 
		  'end'   => $con['start'], 
		  'title' =>  $con['confirmed'],
		  'backgroundColor'=>'transparent'

		);
		$data6[] = array(
		  'resourceId'   => 'TotalTentative',
		  'parentId'   => 0,
		  'start'   => $con['start'], 
		  'end'   => $con['start'], 
		  'title' =>  $con['tentative'],
		  'backgroundColor'=>'transparent'

		);
		$data6[] = array(
		  'resourceId'   => 'TotalWaitlisted',
		  'parentId'   => 0,
		  'start'   => $con['start'], 
		  'end'   => $con['start'], 
		  'title' =>  $con['waitlisted'],
		  'backgroundColor'=>'transparent'

		);
		
		}
		
		$data=array_merge($data6,$data);
	//$dataArr	=array_merge($q,$dataArr);
//$data5;
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