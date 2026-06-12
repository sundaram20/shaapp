<?php include_once("../../config/auto_loader.php");
//checkUserLevelPermission($_SESSION['userLevel'],TBL_HOTELS,'view');
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$start = $_REQUEST['start'];
$end = $_REQUEST['end'];
$hotelId = $_REQUEST['hotelId'];

$events = array();
$resState = executeSql("SELECT * from `fs_daily_visit` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated between '".addslashes($start)."' and '".addslashes($end)."' group by dated ");


if(num_rows($resState) > 0){

		while($row = $db->fetch_assoc2($resState)){	
		
		
$resSql = executeSql("SELECT count(*) as TotalCount from `fs_daily_visit` where status='1' and `id_shop` = '".addslashes($_SESSION['shop'])."' and  dated ='".addslashes($row['dated'])."'");
$TotalCountrow = $db->fetch_assoc2($resSql);





			$e = array();
			
			$e['id'] = $row['id'];
			$e['title'] = ' visit| '.$TotalCountrow['TotalCount'];
			$e['roomName'] = 'Follow Up| '; 
			
			
			$e['start'] = $row['dated'];
			$e['end'] = $row['dated'];
		
			
			if( $row['color']!=''){
			$e['backgroundColor'] = $row['color'];
			$e['borderColor'] = $row['color'];
			}else{
			$e['backgroundColor'] = '#00a65a';
			$e['borderColor'] = '#00a65a';
			}
			$e['allDay'] = true;	
			array_push($events, $e);
			
		}
	}
echo json_encode($events);
?>