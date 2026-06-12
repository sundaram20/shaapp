<?php
include_once("../../config/auto_loader.php");
$startDate=$_POST['startDate'];
$startEnd=$_POST['startEnd'];
$id_hotel=$_POST['id_hotel'];


$sqlHot="SELECT id,name FROM ".TBL_HOTELS." WHERE id_shop='".$_SESSION['shop']."' AND status='1' ";
$resHot=mysqli_query($connNew,$sqlHot);
$objHot=mysqli_fetch_object($resHot);


$data5=array();
$checkoutDate_upadate = date ("Y-m-d", strtotime($startEnd));
$startDateCheckAvailability = date ("Y-m-d", strtotime($startDate));
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
		
		
	$sqlOrderDetail = "
    SELECT 
        d.*,
        r.booking_status,
        r.booking_no,
        r.checkin AS res_checkin,
        r.checkout AS res_checkout,
        b.status AS bill_status,
        g.first_name,
        g.last_name,
        g.id_mst_attributes_title,
        a.field_value AS title_value
    FROM `" . FO_RESERVATIONS_DETAILS . "` d
    INNER JOIN `" . FO_RESERVATIONS . "` r ON r.id = d.id_fo_reservations
    LEFT JOIN `" . FO_BILL . "` b ON b.id_reservations = d.id_fo_reservations
    LEFT JOIN mst_guest g ON g.id = d.id_mst_guest
    LEFT JOIN " . TBL_ATTRIBUTES . " a 
        ON a.id = g.id_mst_attributes_title 
        AND a.id_shop = '" . $_SESSION['shop'] . "' 
        AND a.status = '1' 
        AND a.table_name = 'title'
    WHERE DATE(d.dated) >= '" . date('Y-m-d', strtotime($startDate)) . "'
        AND d.id_mst_room_no_allocation > 0
        AND d.no_showoff = '0'
    GROUP BY d.id_fo_reservations, d.id_mst_room_no_allocation
";
$sqlOrderDetailResult = mysqli_query($connNew, $sqlOrderDetail);

if (mysqli_num_rows($sqlOrderDetailResult) > 0) {
    while ($row = mysqli_fetch_object($sqlOrderDetailResult)) {
        // Guest Full Name
        $guestName = trim($row->title_value . ' ' . ucwords(strtolower($row->first_name)) . ' ' . ucwords(strtolower($row->last_name)));

        // Get updated checkin status for room
        $checkinStatusSql = "
            SELECT checkin_status 
            FROM " . FO_RESERVATIONS_DETAILS . " 
            WHERE id_fo_reservations = '" . $row->id_fo_reservations . "' 
              AND order_by_room = '" . $row->order_by_room . "' 
              AND id_mst_hotels = '" . $row->id_mst_hotels . "' 
              AND id_mst_room_types = '" . $row->id_mst_room_types . "' 
            ORDER BY order_by_room DESC 
            LIMIT 1
        ";
        $checkinRes = mysqli_query($connNew, $checkinStatusSql);
        $checkinRow = mysqli_fetch_object($checkinRes);
        $checkin_status = $checkinRow->checkin_status ?? $row->checkin_status;

        // If "no_showoff" record exists for same room/reservation, use that as checkout
        $datedSql = "
            SELECT dated 
            FROM " . FO_RESERVATIONS_DETAILS . " 
            WHERE id_fo_reservations = '" . $row->id_fo_reservations . "' 
              AND id_mst_room_no_allocation = '" . $row->id_mst_room_no_allocation . "' 
              AND no_showoff = '1'
            LIMIT 1
        ";
        $datedRes = mysqli_query($connNew, $datedSql);
        $datedRow = mysqli_fetch_object($datedRes);
        $dated = $datedRow->dated ?? '';

        $checkin  = $row->res_checkin;
        $checkout = ($dated != '') ? $dated : $row->res_checkout;

        // Color logic
        if ($row->checkin_status == 1 && $row->bill_status == '1') {
            $backgroundColor = 'red';
        } elseif ($row->bill_status == '2') {
            $backgroundColor = '#e5a9a9';
        } else {
            $backgroundColor = '#24ca7d';
        }

        // Append to data
        $data[] = [
            'id'               => $row->id_fo_reservations,
            'resourceId'       => $row->id_mst_room_no_allocation . '- s',
            'parentId'         => $row->id_mst_room_types,
            'start'            => date('Y-m-d', strtotime($checkin)),
            'end'              => date('Y-m-d', strtotime($checkout)),
            'title'            => $guestName,
            'booking_no'       => $row->booking_no,
            'checkin_status'   => $row->checkin_status,
            'id_room'          => $row->id_mst_room_no_allocation,
            'id_mst_room_types'=> $row->id_mst_room_types,
            'order_by_room'    => $row->order_by_room,
            'checkin_status'   => $checkin_status,
            'backgroundColor'  => $backgroundColor
        ];
    }
}

	
	
	
	
	$AssRoomRoomType = "
    SELECT id_mst_room_types 
    FROM `".TBL_ASSIGN_HOTEL_ROOM."` 
    WHERE `id_mst_hotels` = '".mysqli_real_escape_string($connNew, $objHot->id)."' 
    ORDER BY status_active_date DESC
";

$resHotRoomType = mysqli_query($connNew, $AssRoomRoomType);	

$id_mst_room_types = [];
while ($rowResRoomType = mysqli_fetch_object($resHotRoomType)) {
    $id_mst_room_types[] = $rowResRoomType->id_mst_room_types;
}

$id_mst_room_types_str = implode(',', $id_mst_room_types);

// Step 2: Fetch total availability for each date in the range
$sqlnewt = "
    SELECT SUM(crs_available) as total, allocation_date  
    FROM ".FO_INVENTORY." 
    WHERE 
        DATE(allocation_date) BETWEEN '".date('Y-m-d', strtotime($startDate))."' 
        AND '".date('Y-m-d', strtotime($startEnd))."' 
        AND id_mst_hotels = '".mysqli_real_escape_string($connNew, $id_hotel)."' 
        AND id_mst_room_types IN ($id_mst_room_types_str)
    GROUP BY allocation_date
";

$resnewt = mysqli_query($connNew, $sqlnewt);

// Step 3: Build the event data array
//$data = [];
$sumTotal = 0;

while ($rownetw = mysqli_fetch_object($resnewt)) { 
    $avl = $rownetw->total; // Same as crs_available after SUM
    $sumTotal += $avl;

    $event = [
        'resourceId' => 0,
        'start' => $rownetw->allocation_date,
        'title' => $avl . ' AVL'
    ];

    if ($avl <= 0) {
        $event['backgroundColor'] = '#ff797b'; // Red background for 0 availability
    }

    $data[] = $event;
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
	
		
	
	
	
	// Ensure room type IDs are unique
$id_room = array_unique($id_room);

$divide = 10.28;

foreach ($id_room as $id_room_val) {
    // Step 1: Get all plan links for the room
    $sqlnews = "
        SELECT id, id_plan 
        FROM ".FO_ROOM_PLAN_LINKS." 
        WHERE id_room = '".mysqli_real_escape_string($connNew, $id_room_val)."' 
        AND id_hotel = '".mysqli_real_escape_string($connNew, $id_hotel)."' 
        ORDER BY id_plan ASC
    ";
    $resnews = mysqli_query($connNew, $sqlnews);

    while ($rownews = mysqli_fetch_object($resnews)) {
        $id_room_plan_links = $rownews->id;

        // Step 2: Get BAR pricing between the dates for this room plan link
        $sqlBar = "
            SELECT effective_date, double_pax_price 
            FROM ".FO_BEST_AVAILABLE_RATE." 
            WHERE effective_date BETWEEN '".mysqli_real_escape_string($connNew, $startDate)."' 
            AND '".mysqli_real_escape_string($connNew, $startEnd)."' 
            AND id_hotel = '".mysqli_real_escape_string($connNew, $id_hotel)."' 
            AND id_room_plan_link = '".mysqli_real_escape_string($connNew, $id_room_plan_links)."'
        ";
        $resBar = mysqli_query($connNew, $sqlBar);

        while ($rowBar = mysqli_fetch_object($resBar)) {
            $data[] = [
                'resourceId' => ($id_room_val / $divide),
                'parentId' => $id_room_val,
                'start' => $rowBar->effective_date,
                'title' => $rowBar->double_pax_price,
                'backgroundColor' => 'transparent',
            ];
        }

        // Increment divide to ensure unique resourceIds
        $divide += 1;
    }
}

// Step 3: Append total counts for confirmed, tentative, waitlisted
$data6 = [];

foreach ($data5 as $date => $con) {
    $reservationTypes = [
        'TotalConfirmed' => $con['confirmed'],
        'TotalTentative' => $con['tentative'],
        'TotalWaitlisted' => $con['waitlisted']
    ];

    foreach ($reservationTypes as $resourceId => $value) {
        $data6[] = [
            'resourceId' => $resourceId,
            'parentId' => 0,
            'start' => $con['start'],
            'end' => $con['start'],
            'title' => $value,
            'backgroundColor' => 'transparent'
        ];
    }
}

// Final event dataset for JSON output
$data = array_merge($data6, $data);
echo json_encode($data);
	
	
	
	
	
	
	
	
	
	
	//}
	
//debugData($data);	



/// Step 1: Get assigned room type IDs

// Step 1: Get assigned room types
/*$sqlAssignedRoomTypes = "SELECT id_mst_room_types FROM `" . TBL_ASSIGN_HOTEL_ROOM . "` 
                         WHERE `id_mst_hotels` = '" . $objHot->id . "' 
                         ORDER BY status_active_date DESC";
$resAssigned = mysqli_query($connNew, $sqlAssignedRoomTypes);

$id_mst_room_types = [];
while ($row = mysqli_fetch_object($resAssigned)) {
    $id_mst_room_types[] = $row->id_mst_room_types;
}

if (empty($id_mst_room_types)) {
    echo json_encode([]);
    exit;
}

$id_mst_room_types_str = implode(',', $id_mst_room_types);

// Step 2 & 3: Combined Inventory Query
$sqlInventory = "SELECT allocation_date, id_mst_room_types, id_mst_hotels, crs_available, confirmed, tentative, waitlisted
                 FROM " . FO_INVENTORY . "
                 WHERE allocation_date BETWEEN '$startDate' AND '$startEnd'
                   AND id_mst_hotels = '$id_hotel'
                   AND id_mst_room_types IN ($id_mst_room_types_str)
                 ORDER BY allocation_date, id_mst_room_types";
$resInventory = mysqli_query($connNew, $sqlInventory);

//$data = [];
$data5 = [];
$data6 = [];
$id_room = [];
$dailyTotal = [];

while ($row = mysqli_fetch_object($resInventory)) {
    $roomId = $row->id_mst_room_types;
    $date = $row->allocation_date;
    $id_room[$roomId] = true;

    // Track daily total availability
    if (!isset($dailyTotal[$date])) {
        $dailyTotal[$date] = 0;
    }
    $dailyTotal[$date] += $row->crs_available;

    // Room-level availability
    $data[] = [
        'id'              => $row->id_mst_hotels,
        'resourceId'      => $roomId,
        'start'           => $date,
        'title'           => $row->crs_available . ' AVL',
        'backgroundColor' => ($row->crs_available > 0) ? null : '#ff797b'
    ];

    // Aggregated booking data
    if (!isset($data5[$date])) {
        $data5[$date] = [
            'parentId'   => 0,
            'start'      => $date,
            'end'        => $date,
            'confirmed'  => 0,
            'tentative'  => 0,
            'waitlisted' => 0,
        ];
    }

$data[] = array(
		  'resourceId'   => ($row->id_mst_room_types/10.25),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $row->allocation_date, 
		  'end'   => $row->allocation_date, 
		  'title' =>  $row->confirmed,
		  'backgroundColor'=>'transparent'

		);

		  $data[] = array(
		  'resourceId'   => ($row->id_mst_room_types/10.26),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $row->allocation_date,
		  'title' =>  $row->tentative ,
		  'backgroundColor'=>'transparent'
		);

		$data[] = array(
		  'resourceId'   => ($row->id_mst_room_types/10.27),
		  'parentId'   => $row->id_mst_hotels,
		  'start'   => $row->allocation_date,
		  'title' =>  $row->waitlisted ,
		  'backgroundColor'=>'transparent'
		); 
		



    $data5[$date]['confirmed']  += $row->confirmed;
    $data5[$date]['tentative']  += $row->tentative;
    $data5[$date]['waitlisted'] += $row->waitlisted;

    // Per-room booking types
    foreach (['confirmed', 'tentative', 'waitlisted'] as $type) {
        $data[] = [
            'resourceId'      => $type . '_' . $roomId,
            'parentId'        => $row->id_mst_hotels,
            'start'           => $date,
            'end'             => $date,
            'title'           => $row->$type,
            'backgroundColor' => 'transparent'
        ];
    }
}

// Step 2 (replacement): Add daily totals for all rooms
foreach ($dailyTotal as $date => $total) {
    $data[] = [
        'resourceId'      => 0,
        'start'           => $date,
        'title'           => $total . ' AVL',
        'backgroundColor' => ($total > 0) ? null : '#ff797b'
    ];
}

// Step 4: Rate plans per room
$divider = 10.28; // This seems unused; keeping in case needed
foreach (array_keys($id_room) as $roomId) {
    $sqlLinks = "SELECT id, id_plan FROM " . FO_ROOM_PLAN_LINKS . " 
                 WHERE id_room = '$roomId' AND id_hotel = '$id_hotel' 
                 ORDER BY id_plan ASC";
    $resLinks = mysqli_query($connNew, $sqlLinks);

    while ($link = mysqli_fetch_object($resLinks)) {
        $sqlRates = "SELECT effective_date, double_pax_price FROM " . FO_BEST_AVAILABLE_RATE . "
                     WHERE effective_date BETWEEN '$startDate' AND '$startEnd'
                       AND id_hotel = '$id_hotel'
                       AND id_room_plan_link = '$link->id'";
        $resRates = mysqli_query($connNew, $sqlRates);

        while ($rate = mysqli_fetch_object($resRates)) {
            $data[] = [
                'resourceId'      => 'rate_' . $roomId . '_' . $link->id_plan,
                'parentId'        => $roomId,
                'start'           => $rate->effective_date,
                'title'           => $rate->double_pax_price,
                'backgroundColor' => 'transparent'
            ];
        }
    }
}

// Step 5: Global summary per day
foreach ($data5 as $summary) {
    foreach (['confirmed', 'tentative', 'waitlisted'] as $type) {
        $data6[] = [
            'resourceId'      => 'Total' . ucfirst($type),
            'parentId'        => 0,
            'start'           => $summary['start'],
            'end'             => $summary['start'],
            'title'           => $summary[$type],
            'backgroundColor' => 'transparent'
        ];
    }
}

// Step 6: Merge and output
$data = array_merge($data6, $data);
echo json_encode($data);*/
?>


