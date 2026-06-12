<?php

function cellColor($cells,$color){
    	global $objPHPExcel;

	    $objPHPExcel->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'startcolor' => array(
        'rgb' => $color
    			)	
    	));
	}
function GetTotalRoom2($hotelId,$roomId='')
	{	
		global $connNew;
		if($roomId==''){ 
		 $sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."'");
		 }else{
		  $sql=mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoom from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.room_id = rt.id where ahr.status='1' and rt.status='1' and ahr.hotel_id='".addslashes($hotelId)."' and ahr.room_id='".addslashes($roomId)."'");
		 }
		 
		 $row = mysqli_fetch_array($sql);
		 return $row['totalRoom'];
	}

function OccupancyStatisticsReport($Date,$id_report_type,$report_show,$showItemReport,$kot_nc,$appConnect,$connNew,$shop,$cronSet,$pdfNameReport3,$objPHPExcel){
	
	
	//global $connNew;
	///global $objPHPExcel;
	
	
	
		
	define('TBL_RATE_PLAN','fo_rate_plan');
	define('TBL_INVENTORY','fo_inventory');
	define('TBL_ORDERS','fo_reservations');
	define('TBL_ORDER_DETAIL','fo_reservations_details');
	
	$_REQUEST['id_mst_hotels']='1';
$id_mst_hotels = '1';
	$id_mst_room_types = $_POST['room_id'];
	 $reservation_date = explode(' to ',$Date);
	 
	$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDate = date ("Y-m-d", strtotime($reservation_date['1']));
	$checkinDateOrg = date ("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDateOrg = date ("Y-m-d", strtotime($reservation_date['1']));
	//$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));	

	$totalRoom = GetTotalRoom2($id_mst_hotels);
	
	$j=1;$k=0;
	$reportOccupancyArray=array(); 
	$reportOccupancyArraySum=array();	
	$i=1;
	$emp=1;	
		$crs_available='';
		$confirmed='';
		$tentative='';
		$waitlisted='';
		$totalRoominventory='';
		$Totalcrs_available='';
	while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
	
//if($_REQUEST['id_mst_hotels'] != '0' && !in_array(0, $_REQUEST['id_mst_hotels'])){
   // if($_REQUEST['id_mst_hotels'] != ''){
	   $_REQUEST['id_mst_hotels']='1';
		$id_mst_hotelss= '1';//$_REQUEST['id_mst_hotels'];
		$cond = "  `".TBL_INVENTORY."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
		$connAssign = "  `".TBL_ASSIGN_HOTEL_ROOM."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
	//}else{
		//$cond 	   =	"  `".TBL_INVENTORY."`.`id_mst_hotels` in (".$_SESSION['ActiveListHotelPerLogin'].") AND ";
	//	$connAssign = 	"  `".TBL_ASSIGN_HOTEL_ROOM."`.`id_mst_hotels` in (".$_SESSION['ActiveListHotelPerLogin'].") AND ";
	//}
		 //`".TBL_INVENTORY."`.`id_mst_hotels`='".addslashes($id_mst_hotels)."' and
		 //`".TBL_INVENTORY."`.`id_mst_hotels` IN('174','182') an
		   $sql  = mysqli_query($connNew,"Select
			 `".TBL_INVENTORY."`.id_mst_hotels,`".TBL_INVENTORY."`.allocation_date,`".TBL_INVENTORY."`.id_mst_room_types,
			 
			 SUM(`".TBL_INVENTORY."`.crs_available) AS crs_available,
			 SUM(`".TBL_INVENTORY."`.blocked_hotel) AS offline_block_hotel,
			 SUM(`".TBL_INVENTORY."`.confirmed+ `".TBL_INVENTORY."`.tentative+ `".TBL_INVENTORY."`.waitlisted) AS crs_blocked,
			 SUM(`".TBL_INVENTORY."`.confirmed) AS confirmed,
			 SUM(`".TBL_INVENTORY."`.tentative) AS tentative,
			 SUM(`".TBL_INVENTORY."`.waitlisted) AS waitlisted
    		
			 from `".TBL_INVENTORY."` left join `".TBL_ASSIGN_HOTEL_ROOM."`
			 ON   $connAssign `".TBL_ASSIGN_HOTEL_ROOM."`.id_mst_room_types = `".TBL_INVENTORY."`.id_mst_room_types  and `".TBL_ASSIGN_HOTEL_ROOM."`.id_mst_hotels = `".TBL_INVENTORY."`.id_mst_hotels
			 left join `".TBL_HOTELS."` 
			 ON `".TBL_HOTELS."`.id =`".TBL_INVENTORY."`.id_mst_hotels and `".TBL_HOTELS."`.status =1
			 WHERE   $cond `".TBL_INVENTORY."`.allocation_date = '".date('Y-m-d',strtotime($checkinDate))."' and `".TBL_INVENTORY."`.status = '1'
			 and  `".TBL_ASSIGN_HOTEL_ROOM."`.status='1' and `".TBL_HOTELS."`.status =1  and `".TBL_HOTELS."`.id_shop ='".$shop."'
			 group by `".TBL_INVENTORY."`.id_mst_hotels order by `".TBL_HOTELS."`.name  
			 
			");
			
			

while($rowRoom_update = mysqli_fetch_object($sql)){
	
	$inv = selectColumn(TBL_ASSIGN_HOTEL_ROOM,'sum(inventory)',"WHERE `id_mst_hotels` = '".$id_mst_hotelss."' and status='1'" );
	
	$Totalavailable =($inv - ($rowRoom_update->confirmed + $rowRoom_update->tentative + $rowRoom_update->waitlisted)) - $rowRoom_update->offline_block_hotel;
	
	
	//print_r($rowRoom_update);
	$roomId = $rowRoom_update->id_mst_room_types;
	$hotelId= $rowRoom_update->id_mst_hotels;
	//echo "SELECT sum(ahr.inventory) as totalRoominventory from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' order by ahr.id_mst_hotels	";die;	
	$resRoomInventory = mysqli_query($connNew,"SELECT sum(ahr.inventory) as totalRoominventory from `".TBL_ASSIGN_HOTEL_ROOM."` ahr left join `".TBL_ROOM_TYPE."` rt on ahr.id_mst_room_types = rt.id where ahr.status='1' and rt.status='1' and ahr.id_mst_hotels='".addslashes($hotelId)."' order by ahr.id_mst_hotels	");
		$row = mysqli_fetch_array($resRoomInventory);
		
		/*echo $row['totalRoominventory'];
		echo '<pre>';
		print_r($rowRoom_update);
		echo '</pre>';*/
		





	//Initial Colum Empty Binding
	if((strtotime($rowRoom_update->allocation_date)==strtotime($checkinDateOrg) )|| (date("Y-m-d", strtotime($rowRoom_update->allocation_date))==(date("Y-m-01", strtotime($rowRoom_update->allocation_date))))){
		
		//$rowRoom_update->crs_blocked=$rowRoom_update->confirmed+$rowRoom_update->tentative;
		if((date("Y-m-d", strtotime($rowRoom_update->allocation_date))==date("Y-m-01", strtotime($rowRoom_update->allocation_date)))){
		$lableDateEmp	=	'empty'.$emp;
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['dated'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['totalRoominventory'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['crs_available'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['confirmed'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['tentative'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['rnsSold'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['offline_block_hotel'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['Occupancy'][]='';
		$emp++;
		/*$lableDateEmp	=	'empty'.$emp;
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['dated'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['totalRoominventory'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['crs_available'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['confirmed'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['tentative'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['rnsSold'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['Occupancy'][]='';*/
		}
		
		//Lable Binding
		$lableDate	=	date("M Y", strtotime($rowRoom_update->allocation_date));
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['dated'][]=$lableDate;
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['totalRoominventory'][]='Inventory';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['crs_available'][]='Availability';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['confirmed'][]='Confirmed Rns';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['tentative'][]='Tentative Rns';		
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['rnsSold'][]='Rns Sold';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['offline_block_hotel'][]='Offline Blocked';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['Occupancy'][]='Occupancy';
		
		
	}
			//Row Wise Calculation Binding
	$allocation_date	=	date("d D", strtotime($rowRoom_update->allocation_date));
	$OccupancyT	=	(($rowRoom_update->crs_blocked+$rowRoom_update->offline_block_hotel)/$row['totalRoominventory'])*100;
	
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['dated'][]=$allocation_date;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['totalRoominventory'][]=$row['totalRoominventory'];
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['crs_available'][]=$Totalavailable;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['confirmed'][]=$rowRoom_update->confirmed;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['tentative'][]=$rowRoom_update->tentative;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['rnsSold'][]=$rowRoom_update->crs_blocked;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['offline_block_hotel'][]=$rowRoom_update->offline_block_hotel;
	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowRoom_update->allocation_date]['Occupancy'][]=round($OccupancyT).' %';
	
	
	//RowWise Sum For Occupancy
	$last_day = date('Y-m-t', strtotime($rowRoom_update->allocation_date));
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['totalRoominventory_'.$last_day][]=$row['totalRoominventory'];
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['crs_available_'.$last_day][]=$Totalavailable;
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['confirmed_'.$last_day][]=$rowRoom_update->confirmed;
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['tentative_'.$last_day][]=$rowRoom_update->tentative;
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day][]=$rowRoom_update->crs_blocked;
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['offline_block_hotel_'.$last_day][]=$rowRoom_update->offline_block_hotel;
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['Occupancy_'.$last_day][]=round((($rowRoom_update->crs_blocked+$rowRoom_update->offline_block_hotel)/$row['totalRoominventory'])*100).' %';
	
	
	 //RowWise Sum For Total Occupancy
	if(($last_day == $rowRoom_update->allocation_date) || ($checkoutDateOrg ==$rowRoom_update->allocation_date)){  
	     $allocation_date_month	=	date("D", strtotime($rowRoom_update->allocation_date));
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['dated'][]='Total';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['totalRoominventory'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['totalRoominventory_'.$last_day]);
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['crs_available'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['crs_available_'.$last_day]);
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['confirmed'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['confirmed_'.$last_day]);
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['tentative'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['tentative_'.$last_day]);		
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['rnsSold'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day]);
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['offline_block_hotel'][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['offline_block_hotel_'.$last_day]);
		if(array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['offline_block_hotel_'.$last_day])>0){
		$TotalOccpancyFinal= round((array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day])+array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['offline_block_hotel_'.$last_day]))/array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['totalRoominventory_'.$last_day])*100);  
		}else{
		$TotalOccpancyFinal= round(array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day])/array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['totalRoominventory_'.$last_day])*100);      
		}
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month]['Occupancy'][]=$TotalOccpancyFinal.' %';
				
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['totalRoominventory_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['crs_available_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['confirmed_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['tentative_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['offline_block_hotel_'.$last_day]);
		var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['Occupancy_'.$last_day]);
		
		
		
		$Totalcrs_available='';
		$confirmed='';
		$tentative='';
		$waitlisted='';
		$totalRoominventory='';
		$i++;
		}
		
	 
	}


	
	 
	 
	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
	$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
		
					
	}
	//debugData($reportOccupancyArraySum);
	// debugData($reportOccupancyArray);
	//die;
	
	
	$id_mst_hotels = '1';
	$id_mst_room_types = $_POST['id_mst_room_types'];
	$reservation_date = explode(' to ',$Date);
	$checkinDate = date ("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDate = date ("Y-m-d", strtotime($reservation_date['1']));
	$checkinDateOrg = date ("Y-m-d", strtotime($reservation_date['0']));
	$checkoutDateOrg = date ("Y-m-d", strtotime($reservation_date['1']));
	//$checkoutDate =date ("Y-m-d", strtotime("+6 day", strtotime($checkinDate)));	

	$totalRoom = GetTotalRoom2($id_mst_hotels);
	
	//$reportOccupancyArray=array(); 
//	$reportOccupancyArraySum=array();	

	$emp=1;	
		$crs_available='';
		$confirmed='';
		$tentative='';
		$waitlisted='';
		$totalRoominventory='';
		$Totalcrs_available='';$Numk=1;
	while (strtotime($checkinDate) <= strtotime($checkoutDate)) {	
		
//if($_REQUEST['id_mst_hotels'] != '0' && !in_array(0, $_REQUEST['id_mst_hotels'])){
  if($_REQUEST['id_mst_hotels'] != '0' && !in_array(0, $_REQUEST['id_mst_hotels'])){
		$id_mst_hotelss = $_REQUEST['id_mst_hotels'];
		$cond2 = "  `".TBL_ORDERS."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
		$connAssign2 = "  `".TBL_ORDERS."`.`id_mst_hotels` in ('".$id_mst_hotelss."') and ";
	}
		 
            
		 $Query ="
         
            Select
			 `".TBL_ORDERS."`.id_mst_hotels as id_mst_hotels,`fo_rate_plan`.id ,'Total Revenue' as name ,`".TBL_ORDER_DETAIL."`.id_mst_room_types,
			 `".TBL_ORDER_DETAIL."`.id_fo_rate_plan
	
				
			 ,sum(case when (`fo_reservations`.`booking_status` = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated )= '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `confimed_revenue`

            ,sum(case when ( `fo_reservations`.`booking_status` = '2') AND DATE(`".TBL_ORDER_DETAIL."`.dated )= '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `tentative_revenue`

			from `".TBL_RATE_PLAN."`
			left join `".TBL_ORDER_DETAIL."`  on `".TBL_RATE_PLAN."`.id  =`".TBL_ORDER_DETAIL."`.id_fo_rate_plan 
			left join `".TBL_ORDERS."` ON `".TBL_ORDERS."`.id = `".TBL_ORDER_DETAIL."`.id_fo_reservations
			left join `".TBL_HOTELS."`  ON `".TBL_HOTELS."`.id =`".TBL_ORDERS."`.id_mst_hotels and `".TBL_HOTELS."`.status =1
			
			WHERE   $cond2 `".TBL_RATE_PLAN."`.id_shop ='".addslashes($shop)."' AND `".TBL_RATE_PLAN."`.status ='1' and `".TBL_HOTELS."`.status =1
				group by `".TBL_ORDERS."`.id_mst_hotels
				
				
		UNION
            select    id_mst_hotels,id , name ,id_mst_room_types,id_fo_rate_plan,(confimed_revenue/confirmed) as confimed_revenue,tentative_revenue from (
			 
                Select
    			 `".TBL_ORDERS."`.id_mst_hotels as id_mst_hotels,`fo_rate_plan`.id ,'ARR' as name ,`".TBL_ORDER_DETAIL."`.id_mst_room_types,
    			 `".TBL_ORDER_DETAIL."`.id_fo_rate_plan
    	
    				
    			  ,sum(case when (`fo_reservations`.`booking_status` = '1' )  AND DATE(`".TBL_ORDER_DETAIL."`.dated )= '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `confimed_revenue`

                ,sum(case when ( `fo_reservations`.`booking_status` = '2') AND DATE(`".TBL_ORDER_DETAIL."`.dated )= '".date('Y-m-d',strtotime($checkinDate))."' then `fo_reservations_details`.tariff_price_per_day_per_room else 0 end) as `tentative_revenue`
                
                 ,sum(case when (`".TBL_ORDERS."`.`booking_status` = '1' ) AND DATE(`".TBL_ORDER_DETAIL."`.dated )= '".date('Y-m-d',strtotime($checkinDate))."' then `".TBL_ORDER_DETAIL."`.room_quantity else 0 end) as `confirmed`
    			from `".TBL_RATE_PLAN."`
    			left join `".TBL_ORDER_DETAIL."`  on `".TBL_RATE_PLAN."`.id  =`".TBL_ORDER_DETAIL."`.id_fo_rate_plan 
    			left join `".TBL_ORDERS."` ON `".TBL_ORDERS."`.id = `".TBL_ORDER_DETAIL."`.id_fo_reservations
    			left join `".TBL_HOTELS."`  ON `".TBL_HOTELS."`.id =`".TBL_ORDERS."`.id_mst_hotels and `".TBL_HOTELS."`.status =1
    			
    			WHERE   $cond2 `".TBL_RATE_PLAN."`.id_shop ='".addslashes($shop)."' AND `".TBL_RATE_PLAN."`.status ='1' and `".TBL_HOTELS."`.status =1
    				group by `".TBL_ORDERS."`.id_mst_hotels	) 
				
				subgroptabble
            
          
			 
			";
			
	
			//echo '========'.$Query;
			//die;
		  $sql  = mysqli_query($connNew,$Query);
		$numrows    =   mysqli_num_rows($sql);
		
		if($numrows>0){
		    $SetRow=0;
		    
		}
	
	//$j++;
while($rowRoom_update = mysqli_fetch_object($sql)){
		$SetRow++;
	$rowallocation_date = $checkinDate;
	$roomId = $rowRoom_update->id_mst_room_types;
    $hotelId= $rowRoom_update->id_mst_hotels;
		

		/*echo $row['totalRoominventory'];
		echo '<pre>';
		//print_r($rowRoom_update);
		echo '</pre>';*/
		




	//Initial Colum Empty Binding
	if((strtotime($rowallocation_date)==strtotime($checkinDateOrg) )|| (date("Y-m-d", strtotime($rowallocation_date))==(date("Y-m-01", strtotime($rowallocation_date))))){
		
		
		if((date("Y-m-d", strtotime($rowallocation_date))==date("Y-m-01", strtotime($rowallocation_date)))){
		$lableDateEmp	=	'empty'.$emp;
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['dated'][]='';
	    $reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['confirmed'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['tentative'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['rnsSold'][]='';
	
		$emp++;
		/*$lableDateEmp	=	'empty'.$emp;
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['dated'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['totalRoominventory'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['crs_available'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['confirmed'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['tentative'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['rnsSold'][]='';
		$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDateEmp]['Occupancy'][]='';*/
		}
		
		//Lable Binding
		$lableDate	=	date("M Y", strtotime($rowallocation_date));
		
		   	$reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['plan'][]=$rowRoom_update->name;//.'-'.$rowRoom_update->id;
		if($numrows==$SetRow){
		   // $reportOccupancyArray[$rowRoom_update->id_mst_hotels][$lableDate]['Total Revenue'][]='Total Revenue';//.'-'.$rowRoom_update->id;
		    
		    
		}
		
	}
			//Row Wise Calculation Binding
	$allocation_date	=	date("d D", strtotime($rowallocation_date));
	 $allocation_date_month	=	date("D", strtotime($rowallocation_date));
	$OccupancyT	=	(($rowRoom_update->crs_blocked+$rowRoom_update->offline_block_hotel)/$row['totalRoominventory'])*100;
	
		
		    $reportOccupancyArray[$rowRoom_update->id_mst_hotels][$rowallocation_date]['revenue'][]=round($rowRoom_update->confimed_revenue);
		
	
	

	//RowWise Sum For Occupancy
	$last_day = date('Y-m-t', strtotime($rowallocation_date));
    //$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['confirmed_'.$last_day][]=$rowRoom_update->confirmed;
	//$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['tentative_'.$last_day][]=$rowRoom_update->tentative;
	//$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total']['rnsSold_'.$last_day][]=$rowRoom_update->crs_blocked;
	
	$reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total'][$rowRoom_update->name.'_'.$last_day][]=round($rowRoom_update->confimed_revenue);
	
	
	 //RowWise Sum For Total Occupancy
	if(($last_day == $rowallocation_date) || ($checkoutDateOrg ==$rowallocation_date)){   
	  
	    $reportOccupancyArray[$rowRoom_update->id_mst_hotels]['Total_'.$rowRoom_update->id_mst_hotels.'_'.$allocation_date_month][$rowRoom_update->name][]=array_sum($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total'][$rowRoom_update->name.'_'.$last_day]);
	    
		
		//var_dump($reportOccupancyArraySum[$rowRoom_update->id_mst_hotels]['Total'][$rowRoom_update->name.'_'.$last_day]);
	
		$Totalcrs_available='';
		$confirmed='';
		$tentative='';
		$waitlisted='';
		$totalRoominventory='';
		$j++;
		
		
		}
		
	 	$Numk++;
	}


	
	 
	 
	$checkinDate = date ("Y-m-d", strtotime("+1 day", strtotime($checkinDate)));
	$startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));
		
					
	}

	//reportOccupancyArray Array End
	//echo '<pre>';
		//print_r($reportOccupancyArray);
		//echo '</pre>';
		
		//die;
	
	
	
	// Set document properties
	$objPHPExcel->getProperties()->setCreator("Gaurav Sharma")
								 ->setLastModifiedBy("Gaurav Sharma")
								 ->setTitle("Occupancy Report")
								 ->setSubject("Occupancy Report")
								 ->setDescription("Occupancy Report")
								 ->setKeywords("Occupancy Report")
								 ->setCategory("Report");



	// Add some data
	$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));



$styleArray_1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));
$totalArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));

/*$objPHPExcel->getActiveSheet(1)->getColumnDimension('A')->setWidth(25);
$objPHPExcel->getActiveSheet(1)->getColumnDimension('B')->setWidth(28);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('C')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('D')->setWidth(20);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('E')->setWidth(15);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('F')->setWidth(28);	
$objPHPExcel->getActiveSheet(1)->getColumnDimension('G')->setWidth(15);*/				 





$styleThinBlackBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_THIN,
			'color' => array('argb' => '000'),
		),
	),
);	

$styleArray = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => '1e51bf'),
        'size'  => 15,
        'name'  => 'Verdana'
    ));

$styleArray_1 = array(
    'font'  => array(
        'bold'  => true,
        'color' => array('rgb' => 'FF0000'),
        'size'  => 10,
        'name'  => 'Verdana'
    ));





$objPHPExcel->getActiveSheet()->getStyle('B2')->applyFromArray($styleArray);
 $objPHPExcel->getActiveSheet()->getStyle('B2:M2')->getAlignment()->applyFromArray(
    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);

	$objPHPExcel->setActiveSheetIndex(0)
	
	->setCellValue('B2', 'Occupancy Report From '.date("d-m-Y",strtotime($checkinDateOrg)).' To '.date("d-m-Y",strtotime($checkoutDateOrg)));
	
	
	$objPHPExcel->getActiveSheet()->mergeCells('B2:M2');
		

	$head_cntr = "B";
	$Rowcount	=4;
	
	$RowcountStart	=	$Rowcount;
	 $startDate = date ("Y-m-d", strtotime($reservation_date['0']));
	 
	    
	 	
		foreach($reportOccupancyArray as $id_mst_hotels=>$hotelarray){
			//echo $s;
			$ColumWise='A';
			$Rowcount++;
			$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getFont()->setBold(true);
			$HotelName	=	selectColumn(TBL_HOTELS,'name'," WHERE `id` = '".addslashes($id_mst_hotels)."'");
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Rowcount++, strtoupper($HotelName));
			$objPHPExcel->getActiveSheet()->getStyle('A'.$Rowcount)->getFont()->setBold(true);
			

			foreach($hotelarray as $subarray=>$data3){
				//echo '<br>'.$subarray.'===';
				//print_r($data3);
		$RowcountIns=$Rowcount;	
		
			
//		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$Rowcount++, 'Inventory');	
$word = "empty";
$mystring = $subarray;
 
// Test if string contains the word 
if(strpos($mystring, $word) !== false){
    $empty= "empty";
	$JumpColum='4';
} else{
	$JumpColum='';
    $empty='';
}
if((date('D' ,strtotime($subarray)) == 'Sat' || date('D',strtotime($subarray)) == 'Sun') && $ColumWise!='A') { 
  //echo "Today is Saturday or Sunday."; 
  cellColor($ColumWise.$Rowcount,'a2a2ef');
  $DaySatSunday=1;
} else {
    $DaySatSunday=0;
 // echo "Today is not Saturday or Sunday.";
}



		//$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ColumWise.$Rowcount++, $subarray);
		if($empty==''){
		$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->applyFromArray($styleThinBlackBorderOutline);			
			$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getAlignment()->applyFromArray(
			array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getFont()->setBold(true);
		}
		if($FinalDataList!='Availibility'){
												
		
		}
			$InnerRowCount=0;
					foreach($data3 as $lab=>$subarray3){
						//$ColumWise='B';
					    //$Rowcount++;
								$InnerRowCount++;
								foreach($subarray3 as $FinalDataList){
									//echo $FinalDataList;
										if($empty==''){
										$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->applyFromArray($styleThinBlackBorderOutline);			
										$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getAlignment()->applyFromArray(
										array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
										);										
										
										    if($InnerRowCount==8 && $DaySatSunday==1){
												cellColor($ColumWise.$Rowcount,'a2a2ef');
											}
											
											if($InnerRowCount==3 && $FinalDataList!='Availability'){
												cellColor($ColumWise.$Rowcount,'ffe900');
											}
 // ||($InnerRowCount==7 && $FinalDataList=='Occupancy')
											if(($InnerRowCount==2 && $FinalDataList=='Inventory') || ($InnerRowCount==3 && $FinalDataList=='Availability')  ){                                       
												//cellColor($ColumWise.$Rowcount,'db4a4a');
											
											
												$objPHPExcel->getActiveSheet(0)->getColumnDimension($ColumWise)->setWidth(15);
											}
											if($FinalDataList=='Total'){                                       
												//cellColor($ColumWise.$Rowcount,'db4a4a');
											
												//$objPHPExcel->getActiveSheet()->getStyle($ColumWise.$Rowcount)->getFont()->setBold(true);
												//$objPHPExcel->getActiveSheet(0)->getColumnDimension($ColumWise)->setWidth(15);
											}
										}
											
										
										
										$objPHPExcel->setActiveSheetIndex(0)->setCellValue($ColumWise.$Rowcount++, $FinalDataList);
									}
						//echo $lab;
								//print_r($data3['totalRoominventory']);		
						//print($subarray3);
						//echo '<br>'.$subarray3;
							
					}
					
					
					
			$OldRowcount=$Rowcount;		
			$Rowcount=$RowcountIns;		
			$ColumWise++;		
			$ColumWise+$JumpColum;
			}
		
    			
			$Rowcount   =  $OldRowcount+13;
			}
		
//	die;
			 
	
	
	$totalRooms = 0;	
$objPHPExcel->getActiveSheet()->getStyle('B'.$RowcountStart.':B'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


$objPHPExcel->getActiveSheet()->getStyle('C'.$RowcountStart.':C'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


$objPHPExcel->getActiveSheet()->getStyle('D'.$RowcountStart.':D'.$Rowcount)->getAlignment()->applyFromArray(
array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
);	


	$objPHPExcel->getActiveSheet()->setTitle('Occupancy Report');
	$objPHPExcel->setActiveSheetIndex(0);

	



if($cronSet=='1'){
	$Filename=	$pdfNameReport3;//'nightAuditReports'.date('d-M-Y');
//$objPHPExcel->getActiveSheet(0)->setCellValue('A1',"Flash Summary Report As On  ".date('d-m-Y',strtotime($ReportAsOnDate)));
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');	
$objWriter->save('/var/www/vhosts/app.roomstatushub.in/httpdocs/mailattach/'.$Filename.'.xls');

}else{
ob_end_clean();
	$filename=	'OccupancyStatisticsReport'.date('d-M-Y').'.xls';
	// Redirect output to a client's web browser (Excel2007)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$filename.'"');
	header('Cache-Control: max-age=0');
	// If you're serving to IE 9, then the following may be needed
	header('Cache-Control: max-age=1');
	// If you're serving to IE over SSL, then the following may be needed
	header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
	header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
	header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
	header ('Pragma: public'); // HTTP/1.0
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');
	//exit;
}
	}

	?>